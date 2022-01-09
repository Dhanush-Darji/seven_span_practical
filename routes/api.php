<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HobbyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('login',[AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::resources([
        'hobby' => HobbyController::class,
    ]);

    Route::post('logout',[AuthController::class,'logout']); 
});
