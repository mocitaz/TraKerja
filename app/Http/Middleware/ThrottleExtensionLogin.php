<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Str;

class ThrottleExtensionLogin extends ThrottleRequests
{
    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1, $prefix = '')
    {
        $email = $request->input('email');
        $normalizedEmail = is_string($email) ? Str::lower(trim($email)) : '';
        if (is_string($email)) {
            $request->merge(['email' => $normalizedEmail]);
        }

        return $this->handleRequestUsingNamedLimiter($request, $next, 'extension-login', fn () => [
            Limit::perMinute(5)->by('account:'.hash('sha256', $normalizedEmail)),
            Limit::perMinute(20)->by('ip:'.hash('sha256', (string) $request->ip())),
        ]);
    }
}
