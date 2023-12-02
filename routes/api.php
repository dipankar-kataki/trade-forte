<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;

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

Route::post("login", [UserController::class, "login"])->name("login");

Route::group(["middleware" => 'auth:sanctum'], function () {
    Route::group(['prefix' => 'users'], function () {
        Route::post('create', [UserController::class, 'create']);
        Route::get('get/list', [UserController::class, 'index']);
        Route::get('get/{email}', [UserController::class, 'show']);
        Route::put('update/{id}', [UserController::class, 'update']);
        Route::delete('delete/{id}', [UserController::class, 'destroy']);
    });
});

