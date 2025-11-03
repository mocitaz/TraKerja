<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'admin.throttle' => \App\Http\Middleware\AdminRateLimiter::class,
            'premium' => \App\Http\Middleware\EnsureUserIsPremium::class,
            'verified' => \App\Http\Middleware\EnsureEmailIsVerifiedExceptAdmin::class,
        ]);
        
        // Security headers - ONLY enable in production
        // Uncomment line below when deploying to production with proper CSP config
        // $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
