<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LimitKawanKerja
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        abort_unless($user && $user->hasVerifiedEmail(), 403, 'Verify your email before using KawanKerja.');
        $keys = [
            ['kk:minute:'.$user->id, 10, 60],
            ['kk:day:'.$user->id, 60, 86400],
            ['kk:ip:'.hash('sha256', (string)$request->ip()), 30, 60],
        ];
        $lock = Cache::lock('kk:active:'.$user->id, 180);
        if (!$lock->get()) return response()->json(['success'=>false,'message'=>'A response is still being processed.'],429)->header('Retry-After','3');
        try {
            foreach ($keys as [$key,$limit,$seconds]) {
                if (RateLimiter::tooManyAttempts($key,$limit)) {
                    $lock->release();
                    return response()->json(['success'=>false,'message'=>'KawanKerja usage limit reached. Try again later.'],429)
                        ->header('Retry-After',(string)RateLimiter::availableIn($key));
                }
            }
            foreach ($keys as [$key,$limit,$seconds]) RateLimiter::hit($key,$seconds);
            $response = $next($request);
            if ($response instanceof StreamedResponse) {
                $callback = $response->getCallback();
                $response->setCallback(function () use ($callback,$lock) {
                    try { $callback(); } finally { $lock->release(); }
                });
            } else $lock->release();
            return $response;
        } catch (\Throwable $e) {
            $lock->release();
            throw $e;
        }
    }
}
