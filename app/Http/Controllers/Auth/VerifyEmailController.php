<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\EmailVerificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function __invoke(Request $request, EmailVerificationService $verification): RedirectResponse
    {
        $request->validate(['otp' => ['required', 'string', 'regex:/^[0-9]{6}$/D']]);
        $verification->verify($request->user(), $request->string('otp')->toString());
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }

    public function legacy(Request $request): RedirectResponse
    {
        return redirect()->route($request->user() ? 'verification.notice' : 'login')
            ->with('status', __('verification.legacy'));
    }
}
