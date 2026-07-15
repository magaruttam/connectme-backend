<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Auth
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login',[LoginController::class, 'login']);
Route::middleware('auth:sanctum')
    ->get('/me', function(Request $request){

        return $request->user();

    });