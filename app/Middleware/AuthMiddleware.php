<?php

namespace App\Middleware;

class AuthMiddleware
{
    public static function handle()
    {
        if(!isset($_SESSION['user'])){
            echo "Unauthorized";
            exit;
        }else{
            echo "hello" ;
        }
    }
}