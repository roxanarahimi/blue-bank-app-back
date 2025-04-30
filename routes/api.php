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
    Route::post('/tours', 'index');
});
