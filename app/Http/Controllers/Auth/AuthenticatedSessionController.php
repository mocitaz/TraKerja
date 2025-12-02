<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();

            $request->session()->regenerate();

            // Admin users skip email verification
            if (Auth::user()->isAdmin() || Auth::user()->role === 'admin') {
                return redirect()->intended(route('admin.index', absolute: false));
            }

            // For non-admin users, check email verification
            if (! Auth::user()->hasVerifiedEmail()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->with('status', 'email-not-verified');
            }

            // Regular users go to tracker
            return redirect()->intended(route('tracker', absolute: false));
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Re-throw validation exceptions (like rate limiting, wrong credentials)
            throw $e;
        } catch (\Exception $e) {
            // Log unexpected errors
            \Log::error('Login error', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('login')
                ->withErrors(['email' => 'Terjadi kesalahan saat login. Silakan coba lagi atau hubungi support jika masalah berlanjut.']);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
