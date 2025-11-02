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

        // Check if user is premium
        if (!$user->is_premium || $user->payment_status !== 'paid') {
            // Check if premium_until has expired (if set)
            if ($user->premium_until && $user->premium_until->isPast()) {
                $user->update([
                    'is_premium' => false,
                    'payment_status' => 'expired',
                ]);
            }

            // Redirect to payment page with message
            return redirect()->route('payment.index')
                ->with('error', 'Fitur ini hanya tersedia untuk member Premium. Silakan upgrade akun Anda terlebih dahulu.');
        }

        return $next($request);
    }
}

