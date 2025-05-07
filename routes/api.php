<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::get('/tst', function (Request $request) {
    return 'hi!';
});

Route::controller(App\Http\Controllers\MainController::class)->group(function () {
    Route::post('/tours', 'tours');
    Route::get('/test', 'test');
});

Route::controller(App\Http\Controllers\LoginController::class)->group(function () {


    Route::post('/user/login', 'login');
    Route::post('/user/register', 'register');
    Route::post('/check/user/token', 'updateLastActivity');
    Route::get('/user/logout/{user}', 'logout');
    Route::post('/user/logout', 'logout');

    Route::post('/get/otp', 'getOtp');
    Route::post('/verify/otp', 'verifyOtp');
});
