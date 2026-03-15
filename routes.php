<?php

use App\core\Route;
use App\controllers\AuthController;
use App\Middleware\AdminMiddleware;
use App\controllers\UserController;
use App\controllers\ProductController;
use App\controllers\categoryController;
use App\controllers\HomeController;
use App\controllers\UserProudectController;
use App\Middleware\AuthMiddleware;

// Authentication
Route::get( '/login',  [AuthController::class, 'showLogin']);
Route::get( '/',       [AuthController::class, 'showLogin']);
Route::post('/login',  [AuthController::class, 'login']);
Route::get( '/logout', [AuthController::class, 'logout']);

//Home (after login)
Route::get("/admin/home", [HomeController::class, 'admin'] , [AdminMiddleware::class]);
Route::get("/admin/home", [HomeController::class, 'user'] , [AdminMiddleware::class]);

// User management (Admin)
// Route::get( '/admin/users',        [UserController::class, 'index'],  [AdminMiddleware::class]);
Route::post('/admin/users/store',  [UserController::class, 'store'],  [AdminMiddleware::class]);
Route::post('/admin/users/update', [UserController::class, 'update'], [AdminMiddleware::class]);
Route::post('/admin/users/delete', [UserController::class, 'delete'], [AdminMiddleware::class]);




// Products (Admin)
Route::get("products" , [ProductController::class , "index"] , [AdminMiddleware::class] ) ;
Route::post("products" , [ProductController::class , "store"] , [AdminMiddleware::class] ) ;
Route::post("products/delete/{id}" , [ProductController::class , "delete"] , [AdminMiddleware::class] ) ;
Route::post("products/update/{id}" , [ProductController::class , "update"] , [AdminMiddleware::class] ) ;
Route::get("/products/toggle/{id}" , [ProductController::class , "toggle"] , [AdminMiddleware::class] ) ;

// categories (admin)

Route::get("admin/categories" , [categoryController::class , "index"] ,  [AdminMiddleware::class]) ;
Route::post("admin/categories" , [categoryController::class , "store"] ,  [AdminMiddleware::class]) ;
Route::post("admin/categories/delete/{id}" , [categoryController::class , "delete"] , [AdminMiddleware::class] ) ;
Route::post("admin/categories/update/{id}" , [categoryController::class , "update"] ,  [AdminMiddleware::class]) ;


// user 
Route::get("product" , [UserProudectController::class , "index"] , [AuthMiddleware::class]);

Route::post("checkout" , [UserProudectController::class , "checkout"],[AuthMiddleware::class]) ;

