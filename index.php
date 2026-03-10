<?php
require "handel.errors.php";
require __DIR__ . "/vendor/autoload.php";
require __DIR__ . "/routes.php";
\App\core\Route::dispatch();