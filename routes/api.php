<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\SearchHistoryController;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('google-login', [AuthController::class, 'googleLogin']);

// Secure route that requires authentication
Route::middleware('auth:api')->get('user', [AuthController::class, 'getUserData']);

Route::get('/iata-code', [FlightController::class, 'getIataCode']);

Route::middleware('auth:api')->group(function () {
    Route::post('/search-history', [SearchHistoryController::class, 'store']); // Salvează o căutare
    Route::get('/search-history', [SearchHistoryController::class, 'index']); // Obține istoricul căutărilor
    Route::delete('/search-history/{id}', [SearchHistoryController::class, 'destroy']); // Șterge o căutare
});
