<?php

namespace App\Http\Middleware;

use App\Models\FeedClient;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

/** Feed API auth: "Authorization: Bearer <key>" or "X-Api-Key: <key>", rate-limited per client. */
class AuthenticateFeedClient
{
    public function handle(Request $request, Closure $next): Response
    {
        $client = FeedClient::findByKey($request->bearerToken() ?: $request->header('X-Api-Key'));
        if (! $client) {
            return response()->json(['error' => 'invalid_api_key', 'message' => 'Send a valid key as "Authorization: Bearer <key>".'], 401);
        }

        $limiter = 'feed:'.$client->id;
        if (RateLimiter::tooManyAttempts($limiter, $client->per_minute)) {
            return response()->json(['error' => 'rate_limited', 'retry_after' => RateLimiter::availableIn($limiter)], 429)
                ->header('Retry-After', (string) RateLimiter::availableIn($limiter));
        }
        RateLimiter::hit($limiter, 60);

        $client->increment('requests', 1, $client->last_used_at?->gt(now()->subMinute()) ? [] : ['last_used_at' => now()]);
        $request->attributes->set('feed_client', $client);

        $response = $next($request);
        $response->headers->set('X-RateLimit-Limit', (string) $client->per_minute);
        $response->headers->set('X-RateLimit-Remaining', (string) RateLimiter::remaining($limiter, $client->per_minute));

        return $response;
    }
}
