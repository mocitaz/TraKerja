<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use App\Mail\UserEmailVerifiedMail;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class VerifyEmailController extends Controller
{
    /**
     * Mark the user's email address as verified.
     */
    public function __invoke(Request $request, $id, $hash): RedirectResponse
    {
        $user = User::findOrFail($id);

        // Verify hash matches (hash is sha1 of email)
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Invalid verification link.');
        }

        // Check if already verified
        if ($user->hasVerifiedEmail()) {
            // If already verified, login and redirect to tracker
            Auth::login($user);
            return redirect()->intended(route('tracker', absolute: false).'?verified=1');
        }

        // Mark email as verified
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
            
            // Send welcome email after successful verification
            try {
                Mail::to($user->email)->send(new WelcomeMail($user));
            } catch (\Exception $e) {
                \Log::error('Failed to send welcome email after verification: ' . $e->getMessage());
            }
            
            // Send admin notification about email verification
            try {
                $adminEmail = env('ADMIN_EMAIL', 'infoteknalogi@gmail.com');
                Mail::to($adminEmail)->send(new UserEmailVerifiedMail($user));
            } catch (\Exception $e) {
                \Log::error('Failed to send admin notification about email verification: ' . $e->getMessage());
            }
            
            // Auto-login user after verification
            Auth::login($user);
            
            // Redirect to tracker with success message
            return redirect()->intended(route('tracker', absolute: false).'?verified=1');
        }

        // Fallback: redirect to login
        return redirect()->route('login')->with('status', 'verification-failed');
    }
}
