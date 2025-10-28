<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Rules\StrongPassword;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', new StrongPassword()],
        ]);

        // Get current monetization phase
        $currentPhase = Setting::getMonetizationPhase();
        
        // Prepare grandfathered benefits based on registration phase
        $grandfatheredBenefits = [];
        
        if ($currentPhase == 1) {
            // Phase 1 users get special benefits forever
            $grandfatheredBenefits = [
                'cv_templates_3_free',  // 3 CV templates forever
                'premium_discount_50',  // 50% off premium upgrade
            ];
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'registered_phase' => $currentPhase,
            'grandfathered_benefits' => $grandfatheredBenefits,
            'role' => 'user', // Default role
            'is_premium' => false,
            'payment_status' => 'free', // Default to 'free' (valid values: free, paid, expired)
        ]);

        event(new Registered($user));

        // Auto-send verification email
        $user->sendEmailVerificationNotification();

        // Optionally send welcome email
        try {
            Mail::to($user->email)->send(new WelcomeMail($user));
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email: ' . $e->getMessage());
        }

        // Logout and redirect to login with notice to verify first
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'please-verify-email');
    }
}
