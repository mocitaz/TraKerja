<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminRateLimiter
{
    /**
     * The rate limiter instance.
     */
    protected RateLimiter $limiter;

    /**
     * Create a new middleware instance.
     */
    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = 'admin-access:' . $request->ip();
        
        // 60 requests per minute for admin panel
        if ($this->limiter->tooManyAttempts($key, 60)) {
            return response()->view('errors.429', [
                'message' => 'Too many requests. Please try again in a few minutes.'
            ], 429);
        }
        
        $this->limiter->hit($key, 60); // Decay after 60 seconds
        
        return $next($request);
    }
}
