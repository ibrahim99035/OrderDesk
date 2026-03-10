<?php

use App\core\Route;
use App\controllers\UserController;

Route::get('/user/{id}', [UserController::class, 'index']);
Route::post('/user', [UserController::class, 'store']);
// Route::get('/',[User::class,'index']);