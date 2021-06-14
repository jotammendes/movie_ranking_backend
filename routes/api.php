<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GenreController;

use App\Http\Controllers\TMDBController;
use App\Http\Controllers\OMDBController;

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
    // Rotas para Usuário (User)
    Route::get('user/all', [UserController::class, 'getAllUsers']);
    Route::post('user/store', [UserController::class, 'storeNewUser']);
    Route::post('user/update', [UserController::class, 'updateAuthenticatedUser']);

    // Rotas para Gêneros (Genre)
    Route::get('genre/all', [TMDBController::class, 'getAllGenres']);
    Route::post('genre/verify', [GenreController::class, 'verifyAllGenres']);

    // Rotas para Filmes (Movie)
    Route::get('movie/top_rated', [TMDBController::class, 'getTopRatedMovies']);
});
