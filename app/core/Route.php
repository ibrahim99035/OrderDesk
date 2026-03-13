<?php

namespace App\core;

use App\core\View;

class Route
{
    private static array $routes = [];

    public static function add(string $method, string $uri, array $action, array $middleware = []): void
    {
        $uri = trim($uri, '/');
        $uri = $uri ? "/$uri" : "/";

        self::$routes[$method][] = [
            'uri'        => $uri,
            'action'     => $action,
            'middleware' => $middleware,
        ];
    }

    public static function get(string $uri, array $action, array $middleware = []): void
    {
        self::add('GET', $uri, $action, $middleware);
    }

    public static function post(string $uri, array $action, array $middleware = []): void
    {
        self::add('POST', $uri, $action, $middleware);
    }

    public static function put(string $uri, array $action, array $middleware = []): void
    {
        self::add('PUT', $uri, $action, $middleware);
    }

    public static function delete(string $uri, array $action, array $middleware = []): void
    {
        self::add('DELETE', $uri, $action, $middleware);
    }

    public static function patch(string $uri, array $action, array $middleware = []): void
    {
        self::add('PATCH', $uri, $action, $middleware);
    }

    public static function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $uri = trim($uri, '/');
        $uri = $uri ? "/$uri" : "/";

        // Strip base path prefix so routes stay clean.
        $bases = [];
        if (defined('BASE_URL')) {
            $bases[] = rtrim(BASE_URL, '/');
        }
        $bases[] = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

        foreach (array_unique($bases) as $basePath) {
            if ($basePath !== '' && $basePath !== '/' && str_starts_with($uri, $basePath)) {
                $uri = substr($uri, strlen($basePath));
                $uri = trim($uri, '/');
                $uri = $uri ? "/$uri" : "/";
                break;
            }
        }

        if (!isset(self::$routes[$method])) {
            View::make('404');
            return;
        }

        foreach (self::$routes[$method] as $route) {

            $pattern = preg_replace(
                '#\{([a-zA-Z0-9_]+)\}#',
                '([^/]+)',
                $route['uri']
            );
            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);

                // Run middleware stack — each middleware can halt the request.
                foreach ($route['middleware'] as $middlewareClass) {
                    $middlewareClass::handle();
                }

                [$controllerClass, $function] = $route['action'];
                $controller = new $controllerClass;
                $controller->$function(...$matches);

                return;
            }
        }

        View::make('404');
    }
}