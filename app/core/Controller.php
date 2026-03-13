<?php

namespace App\core;

use App\core\Session;

class Controller
{
    public function __construct()
    {
        Session::start();
    }

    protected function render(string $view, array $data = []): void
    {
        extract($data);
        $viewFile = __DIR__ . "/../../views/{$view}.php";

        if (!file_exists($viewFile)) {
            $this->abort(500, "View not found: {$view}");
            return;
        }

        $headerFile = __DIR__ . "/../../views/admin/layout/header.php";
        $footerFile = __DIR__ . "/../../views/admin/layout/footer.php";

        require $headerFile;
        require $viewFile;
        require $footerFile;
    }

    protected function renderAuth(string $view, array $data = []): void
    {
        extract($data);
        $viewFile = __DIR__ . "/../../views/{$view}.php";

        if (!file_exists($viewFile)) {
            $this->abort(500, "View not found: {$view}");
            return;
        }

        require $viewFile;
    }

    protected function renderPartial(string $view, array $data = []): void
    {
        extract($data);
        $viewFile = __DIR__ . "/../../views/{$view}.php";

        if (!file_exists($viewFile)) {
            $this->abort(500, "View not found: {$view}");
            return;
        }

        require $viewFile;
    }

    protected function redirect(string $path): void
    {
        header("Location: " . BASE_URL . $path);
        exit;
    }

    protected function requireAuth(): void
    {
        if (!Session::isLoggedIn()) {
            $this->redirect('/login');
        }
    }

    protected function requireAdmin(): void
    {
        $this->requireAuth();
        if (!Session::isAdmin()) {
            $this->abort(403, '');
        }
    }

    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function isGet(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    protected function post(string $key, mixed $default = null): mixed
    {
        $value = $_POST[$key] ?? $default;
        return is_string($value) ? trim($value) : $value;
    }

    protected function get(string $key, mixed $default = null): mixed
    {
        $value = $_GET[$key] ?? $default;
        return is_string($value) ? trim($value) : $value;
    }

    protected function currentPage(): int
    {
        return max(1, (int) $this->get('page', 1));
    }

    protected function abort(int $code, string $message = ''): void
    {
        http_response_code($code);
        $viewFile = __DIR__ . "/../../views/{$code}.php";
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo $message;
        }
        exit;
    }

    protected function currentUser(): array
    {
        return [
            'id'   => Session::get('user_id'),
            'name' => Session::get('name'),
            'role' => Session::get('role'),
        ];
    }
}