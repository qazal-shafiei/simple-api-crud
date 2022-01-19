<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\Cart\CartController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\Cart\OrderController;
use Illuminate\Support\Facades\Route;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'jwt.verify'], function() {
    Route::apiResource('products', ProductController::class);
    Route::apiResource('cart', CartController::class);
    Route::apiResource('order', OrderController::class);
});

