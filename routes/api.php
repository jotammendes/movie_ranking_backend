<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

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

Route::get('login', [AuthController::class, 'unauthenticatedUser'])->name('api.login');
Route::post('auth', [AuthController::class, 'authenticateUser']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [UserController::class, 'getAllUsers']);
    Route::post('user/store', [UserController::class, 'storeNewUser']);
    Route::post('user/update', [UserController::class, 'updateAuthenticatedUser']);
});
