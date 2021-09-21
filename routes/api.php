<?php

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
Route::get('users/index', [UserController::class, 'index']);
Route::get('users/show/{id}', [UserController::class, 'show']);
Route::post('users/store', [UserController::class, 'store']);
Route::post('users/update/{id}', [UserController::class, 'update']);
Route::delete('users/delete/{id}', [UserController::class, 'destroy']);
