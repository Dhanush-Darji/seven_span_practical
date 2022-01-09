<?php

use App\Http\Controllers\Admin\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('login',[AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::resources([
        'user' => UserController::class,
    ]);

    Route::post('logout',[AuthController::class,'logout']); 
});

