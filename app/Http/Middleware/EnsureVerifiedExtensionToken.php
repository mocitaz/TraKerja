<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class EnsureVerifiedExtensionToken
{
    public function handle(Request $request, Closure $next, string $ability): Response
    {
        $user = $request->user();
        abort_unless($user && $user->hasVerifiedEmail(), 403, 'Verifikasi email Anda terlebih dahulu.');
        $token = $user->currentAccessToken();
        abort_unless($token instanceof PersonalAccessToken, 403);
        abort_unless($token->created_at && $token->created_at->gt(now()->subDays(30)), 401, 'Unauthenticated.');
        abort_unless($user->tokenCan($ability), 403);

        return $next($request);
    }
}
