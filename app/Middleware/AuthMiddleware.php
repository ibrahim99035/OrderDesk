<?php

namespace App\Middleware;

use App\core\Session;

class AuthMiddleware
{
    public static function handle(): void
    {
        if (!Session::isUser()) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }
}