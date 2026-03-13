<?php

namespace App\controllers;

use App\core\Session;
use App\core\View;
use App\repositories\UserRepository;
use App\repositories\RoomRepository;

class UserController
{
    private UserRepository $userRepo;
    private RoomRepository $roomRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
        $this->roomRepo = new RoomRepository();
    }

    // GET /admin/users
    public function index()
    {
        View::make('admin.users', [
            'users' => $this->userRepo->allWithRoom(),
            'rooms' => $this->roomRepo->allRooms(),
        ]);
    }

    // POST /admin/users/store
    public function store()
    {
        $data   = $this->sanitizeInput($_POST);
        $errors = $this->validate($data);

        if (!empty($errors)) {
            View::make('admin.users', [
                'users'        => $this->userRepo->allWithRoom(),
                'rooms'        => $this->roomRepo->allRooms(),
                'createErrors' => $errors,
                'createOld'    => $data,
            ]);
            return;
        }

        if ($this->userRepo->findByEmail($data['email'])) {
            View::make('admin.users', [
                'users'        => $this->userRepo->allWithRoom(),
                'rooms'        => $this->roomRepo->allRooms(),
                'createErrors' => ['email' => 'This email is already taken.'],
                'createOld'    => $data,
            ]);
            return;
        }

        $data['image']    = $this->handleImageUpload('image');
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        $this->userRepo->createUser($data);

        header('Location: /admin/users');
        exit;
    }

    // POST /admin/users/update
    public function update()
    {
        $id   = (int) ($_POST['id'] ?? 0);
        $user = $this->userRepo->find($id);

        if (!$user) {
            header('Location: /admin/users');
            exit;
        }

        $data   = $this->sanitizeInput($_POST);
        $errors = $this->validate($data, isUpdate: true);

        if (!empty($errors)) {
            View::make('admin.users', [
                'users'      => $this->userRepo->allWithRoom(),
                'rooms'      => $this->roomRepo->allRooms(),
                'editErrors' => $errors,
                'editOld'    => array_merge($user, $data),
            ]);
            return;
        }

        $existing = $this->userRepo->findByEmail($data['email']);
        if ($existing && (int) $existing['id'] !== $id) {
            View::make('admin.users', [
                'users'      => $this->userRepo->allWithRoom(),
                'rooms'      => $this->roomRepo->allRooms(),
                'editErrors' => ['email' => 'This email is already taken.'],
                'editOld'    => array_merge($user, $data),
            ]);
            return;
        }

        // Build SET clause
        $name      = addslashes($data['name']);
        $email     = addslashes($data['email']);
        $role      = $data['role'];
        $roomId    = $data['room_id']   ? (int) $data['room_id']        : 'NULL';
        $extension = $data['extension'] ? "'" . addslashes($data['extension']) . "'" : 'NULL';
        $isActive  = $data['is_active'] ? 1 : 0;

        $set = "name = '$name', email = '$email', role = '$role',
                room_id = $roomId, extension = $extension, is_active = $isActive";

        if (!empty($data['password'])) {
            $hashed = password_hash($data['password'], PASSWORD_BCRYPT);
            $set   .= ", password = '$hashed'";
        }

        // Handle new image upload
        $newImage = $this->handleImageUpload('image');
        if ($newImage) {
            // Remove old image if exists
            if (!empty($user['image'])) {
                $old = __DIR__ . '/../../public/uploads/users/' . $user['image'];
                if (file_exists($old)) unlink($old);
            }
            $set .= ", image = '$newImage'";
        }

        $this->userRepo->where("id = $id")->update($set, []);

        header('Location: /admin/users');
        exit;
    }

    // POST /admin/users/delete
    public function delete()
    {
        $id = (int) ($_POST['id'] ?? 0);

        if ($id === (int) Session::get('user_id')) {
            header('Location: /admin/users');
            exit;
        }

        // Remove image file if exists
        $user = $this->userRepo->find($id);
        if ($user && !empty($user['image'])) {
            $path = __DIR__ . '/../../public/uploads/users/' . $user['image'];
            if (file_exists($path)) unlink($path);
        }

        $this->userRepo->where("id = $id")->delete();

        header('Location: /admin/users');
        exit;
    }

    // -------------------------------------------------------------------------

    private function handleImageUpload(string $field): ?string
    {
        if (empty($_FILES[$field]['name'])) return null;

        $file     = $_FILES[$field];
        $allowed  = ['image/jpeg', 'image/png', 'image/webp'];
        $maxSize  = 2 * 1024 * 1024; // 2 MB

        if (!in_array($file['type'], $allowed) || $file['size'] > $maxSize) return null;

        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = md5(uniqid()) . '.' . $ext;
        $dest     = __DIR__ . '/../../public/uploads/users/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $dest)) {
            return $filename;
        }

        return null;
    }

    private function sanitizeInput(array $input): array
    {
        return [
            'name'      => trim($input['name']      ?? ''),
            'email'     => trim($input['email']     ?? ''),
            'password'  => trim($input['password']  ?? ''),
            'role'      => in_array($input['role'] ?? '', ['admin', 'user']) ? $input['role'] : 'user',
            'room_id'   => !empty($input['room_id']) ? (int) $input['room_id'] : null,
            'extension' => trim($input['extension'] ?? ''),
            'is_active' => isset($input['is_active']) ? 1 : 0,
            'image'     => null, // handled separately via file upload
        ];
    }

    private function validate(array $data, bool $isUpdate = false): array
    {
        $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = 'Name is required.';
        }

        if (empty($data['email'])) {
            $errors['email'] = 'Email is required.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format.';
        }

        if (!$isUpdate && empty($data['password'])) {
            $errors['password'] = 'Password is required.';
        } elseif (!empty($data['password']) && strlen($data['password']) < 8) {
            $errors['password'] = 'Password must be at least 8 characters.';
        }

        return $errors;
    }
}