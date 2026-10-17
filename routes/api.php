<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ExtensionController;
use App\Http\Middleware\EnsureVerifiedExtensionToken;
use App\Http\Middleware\ThrottleExtensionLogin;

Route::post('/extension/login', [ExtensionController::class, 'login'])->middleware([\App\Http\Middleware\SetLocale::class, ThrottleExtensionLogin::class]);

Route::middleware(['auth:sanctum', \App\Http\Middleware\SetLocale::class])->group(function () {
    Route::post('/extension/logout', [ExtensionController::class, 'logout'])
        ->middleware(EnsureVerifiedExtensionToken::class.':token:revoke');
    Route::post('/extension/jobs', [ExtensionController::class, 'saveJob'])
        ->middleware(EnsureVerifiedExtensionToken::class.':jobs:create');

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware(EnsureVerifiedExtensionToken::class.':account:read');
});

// Telegram Interactive Two-Way Bot Webhook
Route::post('/telegram/webhook', [\App\Http\Controllers\TelegramWebhookController::class, 'handle'])
    ->middleware(\App\Http\Middleware\VerifyTelegramWebhook::class);
