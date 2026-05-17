<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Setting;
use App\Mail\WelcomeMail;
use App\Mail\NewUserRegistrationMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to Google's OAuth page.
     */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google after authentication.
     */
    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            \Log::error('Google OAuth callback error', [
                'error' => $e->getMessage(),
            ]);
            return redirect()->route('login')
                ->withErrors(['email' => 'Login dengan Google gagal. Silakan coba lagi.']);
        }

        // Cari user berdasarkan google_id terlebih dahulu
        $user = User::where('google_id', $googleUser->getId())->first();

        // Jika tidak ada, cari berdasarkan email
        if (!$user) {
            $user = User::where('email', $googleUser->getEmail())->first();
        }

        if ($user) {
            // User sudah ada — update google_id jika belum tersimpan
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }

            // Update avatar jika belum punya logo
            if (!$user->logo && $googleUser->getAvatar()) {
                $user->update(['logo' => $googleUser->getAvatar()]);
            }

            // Paksa verifikasi email untuk user Google (email Google sudah terverifikasi)
            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }

            Auth::login($user, remember: true);

            // Redirect sesuai role
            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.index'));
            }

            return redirect()->intended(route('tracker'));
        }

        // User belum ada — buat akun baru
        try {
            $currentPhase = Setting::getMonetizationPhase();
        } catch (\Exception $e) {
            $currentPhase = 1;
        }

        $grandfatheredBenefits = [];
        if ($currentPhase == 1) {
            $grandfatheredBenefits = [
                'cv_templates_3_free',
                'premium_discount_50',
            ];
        }

        $newUser = User::create([
            'name'                  => $googleUser->getName(),
            'email'                 => $googleUser->getEmail(),
            'google_id'             => $googleUser->getId(),
            'logo'                  => $googleUser->getAvatar(),
            'password'              => null, // Password tidak diperlukan untuk OAuth
            'email_verified_at'     => now(), // Email Google sudah terverifikasi
            'role'                  => 'user',
            'is_premium'            => false,
            'payment_status'        => 'free',
            'registered_phase'      => $currentPhase,
            'grandfathered_benefits'=> $grandfatheredBenefits,
        ]);

        // Kirim welcome email
        try {
            Mail::to($newUser->email)->send(new WelcomeMail($newUser));
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email (Google OAuth): ' . $e->getMessage());
        }

        // Kirim notifikasi ke admin
        try {
            $adminEmail = env('ADMIN_EMAIL', 'infoteknalogi@gmail.com');
            Mail::to($adminEmail)->send(new NewUserRegistrationMail($newUser));
        } catch (\Exception $e) {
            \Log::error('Failed to send admin notification (Google OAuth): ' . $e->getMessage());
        }

        Auth::login($newUser, remember: true);

        return redirect()->intended(route('tracker'));
    }
}