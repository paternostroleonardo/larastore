<?php

use App\Http\Controllers\API\V1\Auth\LoginController;
use App\Http\Controllers\API\V1\Auth\LogoutController;
use App\Http\Controllers\API\V1\CustomerController;
use App\Http\Controllers\API\V1\OrderController;
use App\Http\Controllers\API\V1\ProductController;
use App\Http\Controllers\API\V1\SellerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->group(function (){
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register', [LoginController::class, 'register']);
});

Route::post('/logout', LogoutController::class);

Route::middleware('auth:api')->group(function () {

    Route::prefix('customers')->group(function () {
        Route::get('/', [CustomerController::class, 'index']);
        Route::get('/show/{customer}', [CustomerController::class, 'show']);
        Route::post('/new', [CustomerController::class, 'store']);
        Route::put('/update/{customer}', [CustomerController::class, 'update']);
        Route::delete('/delete/{customer}', [CustomerController::class, 'destroy']);
    });

    Route::prefix('sellers')->group(function () {
        Route::get('/', [SellerController::class, 'index']);
        Route::get('/show/{seller}', [SellerController::class, 'show']);
        Route::post('/new', [SellerController::class, 'store']);
        Route::put('/update/{seller}', [SellerController::class, 'update']);
        Route::delete('/delete/{seller}', [SellerController::class, 'destroy']);
    });

    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/show/{product}', [ProductController::class, 'show']);
        Route::post('/new', [ProductController::class, 'store']);
        Route::put('/update/{product}', [ProductController::class, 'update']);
        Route::delete('/delete/{product}', [ProductController::class, 'destroy']);
    });

    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/show/{order}', [OrderController::class, 'show']);
        Route::get('/by-status', [OrderController::class, 'ordersByStatus']);
        Route::get('/by-customers/{customer}', [OrderController::class, 'ordersBuyerByCustomer']);
        Route::get('/by-sellers/{seller}', [OrderController::class, 'ordersSellBySeller']);
        Route::post('/new', [OrderController::class, 'store']);
        Route::put('/update-status/{order}', [OrderController::class, 'updateStatus']);
        Route::delete('/delete/{order}', [OrderController::class, 'destroy']);
    });
});
