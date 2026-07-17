<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Auth
Route::prefix('auth')->group(function () {
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/login',[LoginController::class, 'login']);
    Route::post('/verify-otp', [RegisterController::class, 'verifyOtp']);
});


Route::middleware('auth:sanctum')->group(function () {
    //Logout
    Route::post('/logout', [LogoutController::class, 'logout']);
    //Get current user
    Route::get('/me',function(Request $request){
        return $request->user();
    });
});