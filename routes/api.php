<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ExtensionController;
use App\Http\Middleware\EnsureVerifiedExtensionToken;
use App\Http\Middleware\ThrottleExtensionLogin;

Route::post('/extension/login', [ExtensionController::class, 'login'])->middleware([\App\Http\Middleware\SetLocale::class, ThrottleExtensionLogin::class]);

Route::middleware(['auth:sanctum', \App\Http\Middleware\EnforceAccountRestriction::class, \App\Http\Middleware\SetLocale::class])->group(function () {
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

// Read-only vacancy feed (TraKerja 2.0, Fase 6). Keys: php artisan feed:client create <name>
Route::prefix('v1')->middleware(\App\Http\Middleware\AuthenticateFeedClient::class)->group(function () {
    Route::get('/vacancies', [\App\Http\Controllers\Api\FeedController::class, 'index'])->name('feed.vacancies');
    Route::get('/vacancies/{publicId}', [\App\Http\Controllers\Api\FeedController::class, 'show'])->where('publicId', '[0-9A-Za-z]{26}')->name('feed.vacancy');
    Route::get('/sources', [\App\Http\Controllers\Api\FeedController::class, 'sources'])->name('feed.sources');
});
