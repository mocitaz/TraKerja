<?php

namespace App\Services;

use App\Mail\OtpVerificationMail;
use App\Mail\WelcomeMail;
use App\Models\EmailVerificationCode;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class EmailVerificationService
{
    public function send(User $user): void
    {
        DB::transaction(function () use ($user) {
            $account = User::query()->lockForUpdate()->findOrFail($user->id);
            if ($account->hasVerifiedEmail()) {
                return;
            }

            $challenge = EmailVerificationCode::firstOrNew(['user_id' => $account->id]);
            $remaining = $challenge->sent_at ? max(0, 60 - (int) $challenge->sent_at->diffInSeconds(now())) : 0;
            if ($remaining > 0) {
                throw ValidationException::withMessages(['otp' => __('verification.cooldown', ['seconds' => $remaining])]);
            }
            if (! $challenge->send_window_started_at || $challenge->send_window_started_at->copy()->addHour()->lte(now())) {
                $challenge->send_window_started_at = now();
                $challenge->send_count = 0;
            }
            if ($challenge->send_count >= 5) {
                throw ValidationException::withMessages(['otp' => __('verification.send_limit')]);
            }

            do {
                $otp = (string) random_int(100000, 999999);
            } while ($challenge->code_hash && Hash::check($otp, $challenge->code_hash));
            $challenge->fill([
                'email' => $account->email,
                'code_hash' => Hash::make($otp),
                'expires_at' => now()->addMinutes(10),
                'sent_at' => now(),
                'attempts' => 0,
                'send_count' => $challenge->send_count + 1,
            ])->save();

            // Delivery failure rolls back the replacement so an existing code remains usable.
            Mail::to($account)->locale($account->preferredLocale())->send(new OtpVerificationMail($account, $otp));
        }, 1);
    }

    public function verify(User $user, string $otp): void
    {
        $result = DB::transaction(function () use ($user, $otp) {
            $account = User::query()->lockForUpdate()->findOrFail($user->id);
            if ($account->hasVerifiedEmail()) {
                return 'already_verified';
            }

            $challenge = EmailVerificationCode::where('user_id', $account->id)->first();
            if (! $challenge || ! $challenge->code_hash || $challenge->email !== $account->email) {
                return 'invalid';
            }
            if (! $challenge->attempt_window_started_at || $challenge->attempt_window_started_at->copy()->addMinutes(10)->lte(now())) {
                $challenge->attempt_window_started_at = now();
                $challenge->attempt_count = 0;
            }
            if ($challenge->attempt_count >= 10) {
                return 'attempt_limit';
            }
            if (! $challenge->expires_at || $challenge->expires_at->lte(now())) {
                return 'expired';
            }
            if ($challenge->attempts >= 5) {
                return 'code_limit';
            }

            $challenge->attempts++;
            $challenge->attempt_count++;
            $valid = preg_match('/^[0-9]{6}$/D', $otp) && Hash::check($otp, $challenge->code_hash);
            if ($valid) {
                $account->markEmailAsVerified();
                $challenge->code_hash = null;
                $challenge->expires_at = null;
            }
            $challenge->save();

            return $valid ? 'verified' : 'invalid';
        });

        if ($result === 'already_verified') {
            return;
        }
        if ($result !== 'verified') {
            throw ValidationException::withMessages(['otp' => __('verification.'.$result)]);
        }

        $user->refresh();
        event(new Verified($user));
        try {
            Mail::to($user)->locale($user->preferredLocale())->send(new WelcomeMail($user));
        } catch (\Throwable $exception) {
            \Log::warning('Welcome email delivery failed after verification', ['user_id' => $user->id, 'exception' => get_class($exception)]);
        }
    }

    public function resendAfter(User $user): int
    {
        if (! $user->exists) {
            return 0;
        }

        $sentAt = EmailVerificationCode::where('user_id', $user->id)->value('sent_at');

        return $sentAt ? max(0, 60 - (int) now()->diffInSeconds(Carbon::parse($sentAt), true)) : 0;
    }
}
