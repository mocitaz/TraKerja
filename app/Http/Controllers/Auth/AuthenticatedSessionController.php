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
        $request->authenticate();

        $request->session()->regenerate();

        // If not verified, force logout and show notice
        if (! Auth::user()->hasVerifiedEmail()) {
            // Allow legacy users (registered in phase 1 or null) to login without verification
            $legacy = is_null(Auth::user()->registered_phase) || Auth::user()->registered_phase <= 1;
            if ($legacy) {
                // Let them in, but show a banner later to encourage verification
            } else {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('status', 'email-not-verified');
            }
        }

        // Redirect admin to admin dashboard, regular users to tracker
        if (Auth::user()->isAdmin() || Auth::user()->role === 'admin') {
            return redirect()->intended(route('admin.index', absolute: false));
        }

        return redirect()->intended(route('tracker', absolute: false));
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
