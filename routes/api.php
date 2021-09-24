<?php

use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\PolicyController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
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

Route::post('login', [LoginController::class, 'login']);
Route::any('logout', [LoginController::class, 'logout']);
Route::prefix('admin')->group(function () {
    Route::prefix('users')->group(function () {
        Route::get('index', [UserController::class, 'index']);
        Route::get('show/{id}', [UserController::class, 'show']);
        Route::post('store', [UserController::class, 'store']);
        Route::post('update/{id}', [UserController::class, 'update']);
        Route::delete('delete/{id}', [UserController::class, 'destroy']);
    });
    Route::prefix('roles')->group(function () {
        Route::get('index', [RoleController::class, 'index']);
        Route::get('show/{id}', [RoleController::class, 'show']);
        Route::post('store', [RoleController::class, 'store']);
        Route::post('update/{id}', [RoleController::class, 'update']);
        Route::delete('delete/{id}', [RoleController::class, 'destroy']);
    });
    Route::prefix('cars')->group(function () {
        Route::get('index', [CarController::class, 'index']);
        Route::get('show/{id}', [CarController::class, 'show']);
        Route::post('store', [CarController::class, 'store']);
        Route::post('update/{id}', [CarController::class, 'update']);
        Route::delete('delete/{id}', [CarController::class, 'destroy']);
    });
    Route::prefix('policies')->group(function () {
        Route::get('index', [PolicyController::class, 'index']);
        Route::get('show/{id}', [PolicyController::class, 'show']);
        Route::post('store', [PolicyController::class, 'store']);
        Route::post('update/{id}', [PolicyController::class, 'update']);
        Route::delete('delete/{id}', [PolicyController::class, 'destroy']);
    });
});
