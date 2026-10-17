<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class VerifyTelegramWebhook
{
    public function handle(Request $request, Closure $next): Response
    {
        $secret = config('services.telegram.webhook_secret');
        if (! is_string($secret) || ! preg_match('/\A[A-Za-z0-9_-]{1,256}\z/', $secret)) {
            return response()->json(['message' => __('Webhook unavailable.')], 503);
        }
        $provided = $request->header('X-Telegram-Bot-Api-Secret-Token');
        abort_unless(is_string($provided) && hash_equals($secret, $provided), 403);

        // One authenticated webhook budget, independent of forwarded client IPs.
        $key = 'telegram:webhook';
        if (RateLimiter::tooManyAttempts($key, 60)) {
            return response()->json(['message' => __('Too many requests.')], 429)
                ->header('Retry-After', RateLimiter::availableIn($key));
        }
        RateLimiter::hit($key, 60);

        return $next($request);
    }
}
