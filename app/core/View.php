<?php

namespace App\Core;

class View
{
    public static function make($view , $data = [])
    {
        extract($data);

        $path = __DIR__ . "/../../views/" . str_replace(".", "/", $view) . ".php";

        if(file_exists($path)){
            require $path;
        }else{
            echo "View Not Found";
        }
    }
}