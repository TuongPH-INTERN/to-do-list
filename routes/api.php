<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TodoController;
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
    // 'middleware' => ['auth:api'],
    'prefix' => 'task',
], function () {
    Route::GET('/', [TodoController::class, 'index']);
    Route::POST('/', [TodoController::class, 'store']);
    Route::GET('/{id}', [TodoController::class, 'show']);
    Route::PUT('/{id}', [TodoController::class, 'update']);
    Route::DELETE('/{id}', [TodoController::class, 'destroy']);
});
