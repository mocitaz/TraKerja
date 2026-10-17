<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsPremium
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user->isPremium()) {
            // Redirect to payment page with message
            return redirect()->route('payment.index')
                ->with('error', __('Fitur ini hanya tersedia untuk member Premium. Silakan upgrade akun Anda terlebih dahulu.'));
        }

        return $next($request);
    }
}

