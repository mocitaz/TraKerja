<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // Update last_activity_at only if it's been more than 1 minute since last update
            // to avoid hitting the database too frequently
            $user = Auth::user();
            if (!$user->last_activity_at || $user->last_activity_at->diffInMinutes(now()) >= 1) {
                $user->last_activity_at = now();
                $user->saveQuietly();
            }
        }

        return $next($request);
    }
}
