<?php

use App\Http\Controllers\API\V1\Auth\LoginController;
use App\Http\Controllers\API\V1\Auth\LogoutController;
use App\Http\Controllers\API\V1\CustomerController;
use App\Http\Controllers\API\V1\SellerController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function (){
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register', [LoginController::class, 'register']);
});

Route::prefix('out')->group(function (){
Route::post('/logout', [LogoutController::class]);
});

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