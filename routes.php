<?php

use App\core\Route;
use App\controllers\AuthController;
use App\Middleware\AdminMiddleware;
use App\controllers\UserController;
use App\controllers\ProductController;
use App\controllers\categoryController;
use App\controllers\OrderController;
use App\controllers\HomeController;


// Authentication
Route::get( '/login',  [AuthController::class, 'showLogin']);
Route::get( '/',       [AuthController::class, 'showLogin']);
Route::post('/login',  [AuthController::class, 'login']);
Route::get( '/logout', [AuthController::class, 'logout']);

//Home (after login)
Route::get("/admin/home", [HomeController::class, 'admin']);
Route::get("/home", [HomeController::class, 'user']);

// User management (Admin)
Route::get( '/admin/users',        [UserController::class, 'index'],  [AdminMiddleware::class]);
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



// ================================================================
// ADMIN + OFFICE BOY
// ================================================================

// ✅ Static routes FIRST
Route::get("/orders",                  [OrderController::class, 'index']);
Route::get("/orders/manual",           [OrderController::class, 'manualOrder']);
Route::post("/orders/manual",          [OrderController::class, 'manualOrder']);

// Filter by status - static
Route::get("/orders/processing",       [OrderController::class, 'processingOrders']);
Route::get("/orders/out_for_delivery", [OrderController::class, 'outForDeliveryOrders']);
Route::get("/orders/done",             [OrderController::class, 'doneOrders']);
Route::get("/orders/cancelled",        [OrderController::class, 'cancelledOrders']);

Route::get("/office-boy", [OrderController::class, 'officeBoyIndex']);

// ✅ {id} routes LAST
Route::get("/orders/view/{id}",        [OrderController::class, 'view']);
Route::post("/orders/update/{id}",     [OrderController::class, 'update']);
Route::post("/orders/delete/{id}",     [OrderController::class, 'delete']);
Route::post("/orders/confirm/{id}",    [OrderController::class, 'confirmOrder']);
Route::post("/orders/deliver/{id}",    [OrderController::class, 'deliverOrder']);
Route::post("/orders/complete/{id}",   [OrderController::class, 'complete']);
Route::post("/orders/cancel/{id}",     [OrderController::class, 'cancelOrder']);

// ================================================================
// USER
// ================================================================

// ✅ Static first
Route::get("/orders/my",               [OrderController::class, 'myOrders']);
Route::post("/orders/cancel",          [OrderController::class, 'cancelOrder']);

// ✅ {id} last
Route::get("/orders/my/{id}",          [OrderController::class, 'showMyOrder']);

