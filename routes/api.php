<?php

use App\Http\Controllers\SearchPriceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\SearchHistoryController;
use App\Http\Controllers\FavoriteController;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('google-login', [AuthController::class, 'googleLogin']);

// Secure route that requires authentication
Route::middleware('auth:api')->get('user', [AuthController::class, 'getUserData']);

Route::get('/iata-code', [FlightController::class, 'getIataCode']);

Route::get('/airport-for-city', [FlightController::class, 'getAirportsForCity']);

Route::get('/airport-suggestions', [FlightController::class, 'getAirportSuggestions']);

Route::get('/search-history/search', [SearchHistoryController::class, 'search']); // Verifică dacă există o căutare similară

Route::get('airport-by-iata-code', [FlightController::class, 'getAirportNameByIataCode']);

Route::post('/search-history/store', [SearchHistoryController::class, 'store']);

Route::delete('/search-history/delete', [SearchHistoryController::class, 'delete']); // Obține detalii despre o căutare

Route::post('/search-history/prices', [SearchPriceController::class, 'store']);

Route::get('/search-history/last-price/{id}', [SearchHistoryController::class, 'getLastPrice']);

Route::middleware('auth:api')->put('/user/update', [AuthController::class, 'updateUser']);


Route::middleware('auth:api')->group(function () {
 
   
    Route::get('/search-history', [SearchHistoryController::class, 'index']); // Obține istoricul căutărilor
   
    Route::delete('/search-history/{id}', [SearchHistoryController::class, 'destroy']); // Șterge o căutare
});

