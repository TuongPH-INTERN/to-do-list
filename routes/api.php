<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::POST('payment', [PaymentController::class, 'payment']);
Route::POST('/register', [AuthController::class, 'register']); //register
Route::POST('/login', [AuthController::class, 'login'])->name('login'); //login
Route::POST('/verify-email', [AuthController::class, 'verifyEmail'])->name('verify_email');
Route::GET('/logout', [AuthController::class, 'logout'])->middleware('auth:api');

Route::group([
    'prefix' => 'users',
    'middleware' => ['auth:api', 'not-user'],
], function () {
    Route::GET('/', [UserController::class, 'index']);
    Route::POST('/', [UserController::class, 'store']);
    Route::GET('/{id}', [UserController::class, 'show']);
    Route::PUT('/{id}', [UserController::class, 'update'])->middleware('update-user');;
    Route::DELETE('/{id}', [UserController::class, 'destroy']);
});
