<?php
require "handel.errors.php";
require __DIR__ . "/vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

require __DIR__ . "/config/config.php";
use App\core\Session ;
$session = new Session ;
$session->start() ;
if (preg_match('#^/public/#', $_SERVER["REQUEST_URI"])) {
    return false;
}

require __DIR__ . "/routes.php";


\App\core\Route::dispatch();