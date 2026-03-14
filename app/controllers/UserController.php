<?php

namespace App\controllers;

use App\core\Controller;
use App\core\FileManager;
use App\core\View;
use App\core\Session;
use App\repositories\UserRepository;
use App\models\Room;

class UserController extends Controller
{
    private UserRepository $userRepo;

    public function __construct()
    {
        parent::__construct();
         $this->requireAdmin(); // controller-level guard (belt + middleware suspenders)
        $this->userRepo = new UserRepository();
    }

    // ─────────────────────────────────────────────
    //  GET  /admin/users
    // ─────────────────────────────────────────────
    public function index(): void
    {
        $users = $this->userRepo->allWithRoom();
        $rooms = (new Room())->all();

        // Pull flash data set by store/update when they redirect back with errors
        $createErrors = Session::flash('createErrors');
        $createOld    = Session::flash('createOld');
        $editErrors   = Session::flash('editErrors');
        $editOld      = Session::flash('editOld');
        $current      = 'users';

        View::make('admin.users', compact(
            'users', 'rooms', 'current',
            'createErrors', 'createOld',
            'editErrors',   'editOld'
        ));
    }

    // ─────────────────────────────────────────────
    //  POST /admin/users/store
    // ─────────────────────────────────────────────
    public function store(): void
    {
        $data = [
            'name'      => $this->post('name'),
            'email'     => $this->post('email'),
            'password'  => $this->post('password'),
            'role'      => $this->post('role', 'user'),
            'room_id'   => $this->post('room_id'),
            'extension' => $this->post('extension'),
            'is_active' => $this->post('is_active') ? 1 : 0,
        ];

        $errors = $this->validateUser($data, isCreate: true);

        // Check email uniqueness
        if (empty($errors['email']) && $this->userRepo->findByEmail($data['email'])) {
            $errors['email'] = 'This email is already registered.';
        }

        if (!empty($errors)) {
            Session::set('createErrors', $errors);
            Session::set('createOld',    $data);
            $this->redirect('/admin/users');
            return;
        }

        // Handle image upload
        $imagePath = null;
        if (!empty($_FILES['image']['name'])) {
            $fm = new FileManager(USER_UPLOAD_PATH);
            $fm->types(ALLOWED_IMAGE_TYPES);
            $fm->maxSize(MAX_UPLOAD_SIZE);
            $fullPath  = $fm->upload($_FILES['image']);
            if (!$fullPath) {
                Session::set('createErrors', ['image' => 'Invalid image. Allowed: JPEG, PNG, WebP (max 2 MB).']);
                Session::set('createOld',    $data);
                $this->redirect('/admin/users');
                return;
            }
            $imagePath = basename($fullPath);
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['image']    = $imagePath;

        $result = $this->userRepo->createUser($data);

        if ($result) {
            $_SESSION['success'] = 'User created successfully.';
        } else {
            $_SESSION['error'] = 'Failed to create user. Please try again.';
        }

        $this->redirect('/admin/users');
    }

    // ─────────────────────────────────────────────
    //  POST /admin/users/update
    // ─────────────────────────────────────────────
    public function update(): void
    {
        $id = (int) $this->post('id');

        if (!$id) {
            $this->redirect('/admin/users');
            return;
        }

        $existing = $this->userRepo->find($id);
        if (!$existing) {
            $_SESSION['error'] = 'User not found.';
            $this->redirect('/admin/users');
            return;
        }

        $data = [
            'name'      => $this->post('name'),
            'email'     => $this->post('email'),
            'password'  => $this->post('password'), // optional on edit
            'role'      => $this->post('role', 'user'),
            'room_id'   => $this->post('room_id'),
            'extension' => $this->post('extension'),
            'is_active' => $this->post('is_active') ? 1 : 0,
        ];

        $errors = $this->validateUser($data, isCreate: false);

        // Check email uniqueness (exclude current user)
        if (empty($errors['email'])) {
            $byEmail = $this->userRepo->findByEmail($data['email']);
            if ($byEmail && (int)$byEmail['id'] !== $id) {
                $errors['email'] = 'This email is already used by another account.';
            }
        }

        if (!empty($errors)) {
            Session::set('editErrors', $errors);
            Session::set('editOld',    array_merge($existing, $data, ['id' => $id]));
            $this->redirect('/admin/users');
            return;
        }

        // Handle image upload (keep old if none uploaded)
        $oldImage  = $existing['image'] ?? null;
        $imagePath = null;

        if (!empty($_FILES['image']['name'])) {
            $fm = new FileManager(USER_UPLOAD_PATH);
            $fm->types(ALLOWED_IMAGE_TYPES);
            $fm->maxSize(MAX_UPLOAD_SIZE);
            $fullPath  = $fm->upload($_FILES['image']);
            if (!$fullPath) {
                Session::set('editErrors', ['image' => 'Invalid image. Allowed: JPEG, PNG, WebP (max 2 MB).']);
                Session::set('editOld',    array_merge($existing, $data, ['id' => $id]));
                $this->redirect('/admin/users');
                return;
            }
            $imagePath = basename($fullPath);
        }

        // Hash password only if a new one was provided
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }

        $data['image'] = $imagePath; // null = keep existing (updateUser handles this)

        $result = $this->userRepo->updateUser($id, $data);

        // Delete old image only after successful update and only if replaced
        if ($result !== false && $imagePath && $oldImage) {
            $fm = new FileManager(USER_UPLOAD_PATH);
            $fm->delete(USER_UPLOAD_PATH . $oldImage);
        }

        if ($result !== false) {
            $_SESSION['success'] = 'User updated successfully.';
        } else {
            $_SESSION['error'] = 'Failed to update user. Please try again.';
        }

        $this->redirect('/admin/users');
    }

    // ─────────────────────────────────────────────
    //  POST /admin/users/delete
    // ─────────────────────────────────────────────
    public function delete(): void
    {
        $id = (int) $this->post('id');

        // Prevent self-deletion
        if ($id === (int) Session::get('user_id')) {
            $_SESSION['error'] = 'You cannot delete your own account.';
            $this->redirect('/admin/users');
            return;
        }

        $user = $this->userRepo->find($id);

        if (!$user) {
            $_SESSION['error'] = 'User not found.';
            $this->redirect('/admin/users');
            return;
        }

        // Delete profile image if it exists
        if (!empty($user['image'])) {
            $fm = new FileManager(USER_UPLOAD_PATH);
            $fm->delete(USER_UPLOAD_PATH . $user['image']);
        }

        $this->userRepo->where("id = $id")->delete();

        $_SESSION['success'] = 'User deleted successfully.';
        $this->redirect('/admin/users');
    }

    // ─────────────────────────────────────────────
    //  Validation helper
    // ─────────────────────────────────────────────
    private function validateUser(array $data, bool $isCreate): array
    {
        $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = 'Name is required.';
        }

        if (empty($data['email'])) {
            $errors['email'] = 'Email is required.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Enter a valid email address.';
        }

        if ($isCreate) {
            if (empty($data['password'])) {
                $errors['password'] = 'Password is required.';
            } elseif (strlen($data['password']) < 8) {
                $errors['password'] = 'Password must be at least 8 characters.';
            }
        } elseif (!empty($data['password']) && strlen($data['password']) < 8) {
            $errors['password'] = 'New password must be at least 8 characters.';
        }

        if (!in_array($data['role'], ['user', 'admin'], true)) {
            $errors['role'] = 'Invalid role selected.';
        }

        return $errors;
    }
}