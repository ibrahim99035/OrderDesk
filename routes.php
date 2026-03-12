<?php

use App\core\Route;
use App\controllers\UserController;
use App\controllers\ProductController;


Route::get('/user/{id}/edit', [UserController::class, 'index']);


//proudects in admin
Route::get("products" , [ProductController::class , "index"]) ;
Route::post("products" , [ProductController::class , "store"]) ;
