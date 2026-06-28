<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use App\Mail\NewUserRegistrationMail;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use App\Rules\StrongPassword;
use App\Services\ActivityLogger;

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
        \Log::info('Register attempt started', ['email' => $request->email, 'name' => $request->name, 'ip' => $request->ip()]);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', new StrongPassword()],
        ];

        if (!app()->environment('local') && !in_array($request->getHost(), ['localhost', '127.0.0.1'])) {
            $rules['cf-turnstile-response'] = ['required', new \App\Rules\TurnstileRule()];
        }

        try {
            $request->validate($rules, [
                'cf-turnstile-response.required' => 'Silakan centang kotak verifikasi keamanan.',
            ]);
            \Log::info('Register validation passed');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Register validation failed', ['errors' => $e->errors()]);
            throw $e;
        }

        // Get current monetization phase (default to 1 if settings table doesn't exist)
        try {
            $currentPhase = Setting::getMonetizationPhase();
        } catch (\Exception $e) {
            \Log::warning('Error getting monetization phase, defaulting to 1: ' . $e->getMessage());
            $currentPhase = 1; // Default to phase 1 (free mode)
        }
        
        // Prepare grandfathered benefits based on registration phase
        $grandfatheredBenefits = [];
        
        if ($currentPhase == 1) {
            // Phase 1 users get special benefits forever
            $grandfatheredBenefits = [
                'cv_templates_3_free',  // 3 CV templates forever
                'premium_discount_50',  // 50% off premium upgrade
            ];
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'registered_phase' => $currentPhase,
                'grandfathered_benefits' => $grandfatheredBenefits,
                'role' => 'user', // Default role
                'is_premium' => false,
                'payment_status' => 'free', // Default to 'free' (valid values: free, paid, expired)
                'photo_credits' => 2, // 2 free credits as requested
            ]);
            \Log::info('User created successfully', ['user_id' => $user->id, 'email' => $user->email]);
        } catch (\Exception $e) {
            \Log::error('User creation failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            throw $e;
        }

        ActivityLogger::log(
            'register',
            "Pengguna baru mendaftar: {$user->name} ({$user->email})",
            'success',
            ['email' => $user->email],
            $user->id
        );

        try {
            event(new Registered($user));
            \Log::info('Registered event dispatched');
        } catch (\Exception $e) {
            \Log::error('Registered event error: ' . $e->getMessage());
        }

        // Send admin notification asynchronously after HTTP response is sent to user
        dispatch(function () use ($user) {
            try {
                $adminEmail = env('ADMIN_EMAIL', 'infoteknalogi@gmail.com');
                Mail::to($adminEmail)->send(new NewUserRegistrationMail($user));
                \Log::info('Admin notification email sent after response');
            } catch (\Exception $e) {
                \Log::error('Failed to send admin notification: ' . $e->getMessage());
            }
        })->afterResponse();

        // Keep user authenticated and redirect directly to verification notice page
        Auth::login($user);
        \Log::info('User logged in, redirecting to verification.notice');

        return redirect()->route('verification.notice');
    }
}
