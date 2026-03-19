<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/ping', [AuthController::class, 'index']);

Route::controller(AuthController::class)->group(function(){
    Route::Post('/register', 'register');
});