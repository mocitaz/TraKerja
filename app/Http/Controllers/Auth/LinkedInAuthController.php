<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Setting;
use App\Mail\WelcomeMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class LinkedInAuthController extends Controller
{
    /**
     * Redirect the user to LinkedIn's OAuth page.
     */
    public function redirect(): RedirectResponse
    {
        try {
            return Socialite::driver('linkedin-openid')
                ->scopes(['openid', 'profile', 'email'])
                ->redirect();
        } catch (\Exception $e) {
            \Log::error('LinkedIn OAuth redirect error', [
                'class'   => get_class($e),
                'error'   => $e->getMessage(),
                'code'    => $e->getCode(),
                'file'    => $e->getFile() . ':' . $e->getLine(),
            ]);
            return redirect()->route('login')
                ->withErrors(['email' => __('Login dengan LinkedIn tidak tersedia saat ini. Silakan coba cara lain.')]);
        }
    }

    /**
     * Handle the callback from LinkedIn after authentication.
     */
    public function callback(): RedirectResponse
    {
        try {
            $linkedInUser = Socialite::driver('linkedin-openid')->user();
        } catch (InvalidStateException $e) {
            return redirect()->route('login')
                ->withErrors(['email' => __('Sesi login LinkedIn tidak valid atau sudah berakhir. Silakan mulai login kembali.')]);
        } catch (\Exception $e) {
            \Log::error('LinkedIn OAuth callback error', [
                'class'   => get_class($e),
                'code'    => $e->getCode(),
                'file'    => $e->getFile() . ':' . $e->getLine(),
            ]);
            return redirect()->route('login')
                ->withErrors(['email' => __('Login dengan LinkedIn gagal. Silakan coba lagi.')]);
        }

        // Cari user berdasarkan linkedin_id terlebih dahulu
        $user = User::where('linkedin_id', $linkedInUser->getId())->first();

        // Jika tidak ada, cari berdasarkan email
        if (!$user) {
            $user = User::where('email', $linkedInUser->getEmail())->first();
        }

        if ($user) {
            // User sudah ada — update linkedin_id jika belum tersimpan
            if (!$user->linkedin_id) {
                $user->update(['linkedin_id' => $linkedInUser->getId()]);
            }

            // Paksa verifikasi email (email LinkedIn sudah terverifikasi)
            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }

            Auth::login($user, remember: true);

            // Redirect sesuai role
            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.index'))->with('show_admin_welcome_popup', true);
            }

            return redirect()->intended(route('dashboard'))->with('show_telegram_popup', true);
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
            'name'                   => $linkedInUser->getName(),
            'email'                  => $linkedInUser->getEmail(),
            'linkedin_id'            => $linkedInUser->getId(),
            'logo'                   => $linkedInUser->getAvatar(),
            'password'               => null, // Password tidak diperlukan untuk OAuth
            'email_verified_at'      => now(), // Email LinkedIn sudah terverifikasi
            'role'                   => 'user',
            'is_premium'             => false,
            'payment_status'         => 'free',
            'registered_phase'       => $currentPhase,
            'grandfathered_benefits' => $grandfatheredBenefits,
            'photo_credits'          => 2, // Default 2 credits
        ]);

        // Kirim welcome email
        try {
            Mail::to($newUser)->send(new WelcomeMail($newUser));
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email (LinkedIn OAuth): ' . $e->getMessage());
        }

        // Kirim notifikasi ke admin via Telegram
        try {
            app(\App\Services\TelegramService::class)->sendNewUser($newUser, 'LinkedIn OAuth');
        } catch (\Throwable $e) {
            \Log::error('Failed to send Telegram notification (LinkedIn OAuth): ' . $e->getMessage());
        }

        Auth::login($newUser, remember: true);

        return redirect()->intended(route('dashboard'))->with('show_telegram_popup', true);
    }
}
