<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('google-login', [AuthController::class, 'googleLogin']);

// Secure route that requires authentication
Route::middleware('auth:api')->get('user', [AuthController::class, 'getUserData']);
