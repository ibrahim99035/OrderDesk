<?php

namespace App\controllers;

use App\core\Controller;
use App\core\Session;
use App\core\View;
use App\repositories\UserRepository;

class AuthController extends Controller
{
    private UserRepository $userRepo;

    public function __construct()
    {
        parent::__construct();
        $this->userRepo = new UserRepository();
    }

    public function showLogin(): void
    {
        if (Session::isLoggedIn()) {
            $this->redirectByRole();
            return;
        }

        View::make('auth.login');
    }

    public function login(): void
    {
        $email    = trim($_POST['email']    ?? '');
        $password =      $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            View::make('auth.login', ['error' => 'Email and password are required.']);
            return;
        }

        $user = $this->userRepo->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            View::make('auth.login', ['error' => 'Invalid email or password.']);
            return;
        }

        if (!$user['is_active']) {
            View::make('auth.login', ['error' => 'Your account is inactive. Contact an administrator.']);
            return;
        }

        Session::set('user_id', $user['id']);
        Session::set('role',    $user['role']);
        Session::set('name',    $user['name']);

        $this->redirectByRole();
    }

    public function logout(): void
    {
        Session::destroy();
        $this->redirect('/login');
    }

    private function redirectByRole(): void
    {
        if (Session::isAdmin()) {
            $this->redirect('/admin/users');
        } else {
            $this->redirect('/home');
        }
    }
}