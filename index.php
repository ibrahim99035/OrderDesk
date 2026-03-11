<?php
require "handel.errors.php";
require __DIR__ . "/vendor/autoload.php";
require __DIR__ . "/config/config.php";
use App\core\Session ;
$session = new Session ;
$session->start() ;

require __DIR__ . "/routes.php";


\App\core\Route::dispatch();