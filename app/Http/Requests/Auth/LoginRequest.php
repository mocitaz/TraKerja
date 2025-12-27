<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = $this->only('email', 'password');
        
        // Log login attempt for debugging
        \Log::info('Login attempt', [
            'email' => $credentials['email'],
            'ip' => $this->ip()
        ]);

        // Check if user exists first
        $user = \App\Models\User::where('email', $credentials['email'])->first();
        
        if (!$user) {
            RateLimiter::hit($this->throttleKey());

            \Log::warning('Login failed - User not found', [
                'email' => $credentials['email'],
                'ip' => $this->ip()
            ]);

            throw ValidationException::withMessages([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ]);
        }

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            \Log::warning('Login failed - Invalid password', [
                'email' => $credentials['email'],
                'user_id' => $user->id,
                'ip' => $this->ip()
            ]);

            throw ValidationException::withMessages([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        
        \Log::info('Login successful', [
            'user_id' => Auth::id(),
            'email' => $credentials['email']
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        $key = $this->throttleKey();
        $maxAttempts = 5;
        $decayMinutes = 1; // 1 menit
        
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
        event(new Lockout($this));

            $seconds = RateLimiter::availableIn($key);

            \Log::warning('Login rate limited', [
                'email' => $this->input('email'),
                'ip' => $this->ip(),
                'seconds_remaining' => $seconds
            ]);

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
        }
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
