<?php

namespace App\controllers;

use App\core\Session;
use App\core\View;
use App\repositories\UserRepository;

class AuthController
{
    private UserRepository $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
    }

    public function showLogin()
    {
        if (Session::isLoggedIn()) {
            $this->redirectByRole();
            return;
        }

        View::make('auth.login');
    }

    public function login()
    {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            View::make('auth.login', ['error' => 'Email and password are required.']);
            return;
        }

        $user = $this->userRepo->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            View::make('auth.login', ['error' => 'Invalid email or password.']);
            return;
        }

        Session::set('user_id', $user['id']);
        Session::set('role',    $user['role']);
        Session::set('name',    $user['name']);

        $this->redirectByRole();
    }

    public function logout()
    {
        Session::destroy();
        header('Location: /login');
        exit;
    }

    private function redirectByRole(): void
    {
        if (Session::isAdmin()) {
            header('Location: /admin/home');
        } else {
            header('Location: /home');
        }
        exit;
    }
}