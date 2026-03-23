<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CampaignDataController;
use App\Http\Controllers\ClientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


# Route::get('/ping/{id}/data', [AuthController::class, 'index']);

Route::middleware('throttle:api')->group(function () {
    Route::controller(AuthController::class)->group(function(){
        Route::Post('/register', 'register');
        Route::Post('/login', 'login');
        Route::post('/logout', 'logout')->middleware('auth:sanctum');
    });

    Route::apiResource('/client', ClientController::class)->middleware('auth:sanctum'); 
});

Route::middleware(['auth:sanctum', 'throttle:uploads'])->group(function () {
    Route::controller(CampaignController::class)->group(function () {
        Route::post('/campaigns', 'store');
    });

    Route::controller(CampaignDataController::class)->group(function () {
        Route::post('/campaigns/{campaign_id}/data', 'store');
    });
});



