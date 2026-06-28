<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class TurnstileRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $siteKey = env('TURNSTILE_SITE_KEY', '0x4AAAAAADVwvVUur2OE6_b9');
        $isDummy = $siteKey === '0x4AAAAAADVwvVUur2OE6_b9';

        if (app()->environment('local') || in_array(request()->getHost(), ['localhost', '127.0.0.1']) || $isDummy) {
            return;
        }

        $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret' => env('TURNSTILE_SECRET_KEY'),
            'response' => $value,
            'remoteip' => request()->ip(),
        ]);

        if (! $response->json('success')) {
            $fail('Verifikasi keamanan gagal. Silakan coba lagi.');
        }
    }
}
