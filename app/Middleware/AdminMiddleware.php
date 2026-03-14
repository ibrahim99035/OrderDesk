<?php

namespace App\Middleware;

use App\core\Session;

class AdminMiddleware
{
    /**
     * Ensure the user is logged in AND has the admin role.
     * Called automatically by Route::dispatch() before the controller action.
     */
    public static function handle(): void
    {
    

        if (!Session::isLoggedIn()) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        if (!Session::isAdmin()) {
            http_response_code(403);
            $view = __DIR__ . '/../../views/403.php';
            if (file_exists($view)) require $view;
            else echo 'Forbidden.';
            exit;
        }
    }
}