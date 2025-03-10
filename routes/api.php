<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function(){
    Route::resource('task', TaskController::class);
    Route::get('profile', [UserController::class, 'index']);
    Route::put('profile', [UserController::class, 'update']);
    Route::post('logout', [AuthController::class, 'logout']);
}); 