<?php

namespace App\Middleware;

use App\core\Session;

class AuthMiddleware
{
    public static function handle(): void
    {
        Session::start();

        if (!Session::isLoggedIn()) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }
}