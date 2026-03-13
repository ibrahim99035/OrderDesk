<?php

namespace App\core;

use App\core\View;

class Route
{
    private static array $routes = [];

    public static function add($method,$uri,$action)
    {
        $uri = trim($uri,'/');
        $uri = $uri ? "/$uri" : "/";

        self::$routes[$method][] = [
            'uri'=>$uri,
            'action'=>$action
        ];
    }

    public static function get($uri,$action)
    {
        self::add('GET',$uri,$action);
    }

    public static function post($uri,$action)
    {
        self::add('POST',$uri,$action);
    }

    public static function put($uri,$action)
    {
        self::add('PUT',$uri,$action);
    }

    public static function delete($uri,$action)
    {
        self::add('DELETE',$uri,$action);
    }

    public static function patch($uri,$action)
    {
        self::add('PATCH',$uri,$action);
    }

    public static function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = trim($uri,'/');
        $uri = $uri ? "/$uri" : "/";

        if(!isset(self::$routes[$method])){
            echo "404 Route Not Found";
            return;
        }

        foreach(self::$routes[$method] as $route){

            $pattern = preg_replace(
                '#\{([a-zA-Z0-9_]+)\}#',
                '([^/]+)',
                $route['uri']
            );

            $pattern = "#^" . $pattern . "$#";

            if(preg_match($pattern,$uri,$matches)){

                array_shift($matches);

                [$controller,$function] = $route['action'];

                $controller = new $controller;

                $controller->$function(...$matches);

                return;
            }
        }

       View::make("404") ;
    }
}