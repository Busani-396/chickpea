<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CampaignDataController;
use App\Http\Controllers\ClientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/ping/{id}/data', [AuthController::class, 'index']);

Route::controller(AuthController::class)->group(function(){
    Route::Post('/register', 'register');
    Route::Post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});

Route::apiResource('/client', ClientController::class)->middleware('auth:sanctum'); 

// Route::controller(CampaignController::class)->group(function(){
//     Route::Post('/campaigns','store');
// })->middleware('auth:sanctum');

// Route::controller(CampaignDataController::class)->group(function(){
//     Route::Post('/campaigns/{campaign_id}/data','store');
// })->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(CampaignController::class)->group(function () {
        Route::post('/campaigns', 'store');
    });

    Route::controller(CampaignDataController::class)->group(function () {
        Route::post('/campaigns/{campaign_id}/data', 'store');
    });
});



