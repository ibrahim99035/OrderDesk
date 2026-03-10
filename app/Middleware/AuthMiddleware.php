<?php

namespace App\Middleware;

class AuthMiddleware
{
    public static function handle()
    {
        if(!isset($_SESSION['user1234'])){
            echo "Unauthorized";
            exit;

        }else{
            echo "hello" ;
        }
    }
}