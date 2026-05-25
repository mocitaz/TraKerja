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
            
            // Content Security Policy (relaxed for Livewire, Vite, and Cloudflare)
            $csp = "default-src 'self'; " .
                   "script-src 'self' 'unsafe-inline' 'unsafe-eval' cdn.jsdelivr.net unpkg.com challenges.cloudflare.com static.cloudflareinsights.com; " .
                   "style-src 'self' 'unsafe-inline' cdn.jsdelivr.net fonts.googleapis.com unpkg.com fonts.bunny.net; " .
                   "font-src 'self' fonts.gstatic.com cdn.jsdelivr.net fonts.bunny.net data:; " .
                   "img-src 'self' data: https: blob:; " .
                   "frame-src 'self' challenges.cloudflare.com; " .
                   "connect-src 'self' ws: wss: cdn.jsdelivr.net; " .
                   "object-src 'none'; " .
                   "base-uri 'self';";
            $response->headers->set('Content-Security-Policy', $csp);
            
            // HSTS (only if using HTTPS)
            if ($request->secure()) {
                $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
            }
        }

        return $response;
    }
}
