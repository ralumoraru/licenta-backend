<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return "hello";
});

Route::get('/hello', function () {
    return "hello";
});


