<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FlightController;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('google-login', [AuthController::class, 'googleLogin']);

// Secure route that requires authentication
Route::middleware('auth:api')->get('user', [AuthController::class, 'getUserData']);

Route::get('/iata-code', [FlightController::class, 'getIataCode']);