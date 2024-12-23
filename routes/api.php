<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/users', [\App\Http\Controllers\Api\UserController::class, 'index']);
Route::post('/users', [\App\Http\Controllers\Api\UserController::class, 'store']);
