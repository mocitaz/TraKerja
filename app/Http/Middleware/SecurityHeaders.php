<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Basic security headers (safe for development)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Only apply strict CSP in production
        if (app()->environment('production')) {
            // XSS Protection
            $response->headers->set('X-XSS-Protection', '1; mode=block');
            
            // Content Security Policy (relaxed for Livewire and Vite)
            $csp = "default-src 'self'; " .
                   "script-src 'self' 'unsafe-inline' 'unsafe-eval' cdn.jsdelivr.net unpkg.com; " .
                   "style-src 'self' 'unsafe-inline' cdn.jsdelivr.net fonts.googleapis.com; " .
                   "font-src 'self' fonts.gstatic.com data:; " .
                   "img-src 'self' data: https:; " .
                   "connect-src 'self' ws: wss:";  // Added WebSocket support for Livewire
            $response->headers->set('Content-Security-Policy', $csp);
            
            // HSTS (only if using HTTPS)
            if ($request->secure()) {
                $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
            }
        }

        return $response;
    }
}
