<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ExtensionController;

Route::post('/extension/login', [ExtensionController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/extension/logout', [ExtensionController::class, 'logout']);
    Route::post('/extension/jobs', [ExtensionController::class, 'saveJob']);
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
