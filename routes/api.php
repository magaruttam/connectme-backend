<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ProfileController;

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

//Profile
Route::prefix('profile')->middleware('auth:sanctum')->group(function(){
    //Get Profile
    Route::get('getprofile',[ProfileController::class, 'getProfile']);
    Route::put('/updateprofile', [ProfileController::class, 'updateProfile']);
    Route::post('/upload', [ProfileController::class, 'uploadProfileImage']);
    Route::post('/update', [ProfileController::class, 'updateImage']);
    Route::post('/deleteimage', [ProfileController::class, 'deleteImage']);
});


