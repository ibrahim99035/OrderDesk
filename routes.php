<?php

use App\core\Route;
use App\controllers\AuthController;
use App\controllers\HomeController;
use App\controllers\UserController;
use App\controllers\ProductController;

// Authentication
Route::get("/login", [AuthController::class, 'showLogin']);
Route::post("/login", [AuthController::class, 'login']);
Route::get("/logout", [AuthController::class, 'logout']);

//Home (after login)
// Route::get("/admin/home", [HomeController::class, 'admin']);
// Route::get("/home", [HomeController::class, 'user']);

// User management (Admin)
Route::get("admin/users", [UserController::class, 'index']);
Route::post("admin/users/store", [UserController::class, 'store']);
Route::post("admin/users/update", [UserController::class, 'update']);
Route::post("admin/users/delete", [UserController::class, 'delete']);

// Products (Admin)
Route::get("products" , [ProductController::class , "index"]) ;
Route::post("products" , [ProductController::class , "store"]) ;
Route::post("products/delete/{id}" , [ProductController::class , "delete"]) ;
Route::post("products/update/{id}" , [ProductController::class , "update"]) ;

