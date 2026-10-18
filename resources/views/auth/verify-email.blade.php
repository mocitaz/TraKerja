<x-auth-layout page="verify">
    <div class="auth-email-address"><span>{{ __('auth-ui.sent_to') }}</span><strong>{{ auth()->user()->email }}</strong></div>
    <form method="POST" action="{{ route('verification.confirm') }}" class="auth-form" data-auth-form>
        @csrf
        <div class="auth-field">
            <label for="otp">{{ __('verification.label') }}</label>
            <div class="auth-input-wrap">
                <input id="otp" name="otp" type="text" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" minlength="6" maxlength="6" required aria-describedby="otp-hint otp-error" @if($errors->has('otp')) aria-invalid="true" @endif>
            </div>
            <p class="auth-field-hint" id="otp-hint">{{ __('verification.hint') }}</p>
            <p class="auth-field-error" id="otp-error">@error('otp'){{ $message }}@enderror</p>
        </div>
        @include('auth.partials.submit', ['label' => __('verification.submit')])
    </form>
    <form method="POST" action="{{ route('verification.send') }}" class="auth-logout" data-auth-form data-otp-resend data-wait="{{ $resendAfter ?? 0 }}">
        @csrf
        <button type="submit" class="auth-button auth-button--soft" data-auth-submit data-pending-label="{{ __('auth-ui.working') }}"><span data-submit-label>{{ __('verification.resend') }}</span><span class="auth-spinner" aria-hidden="true"></span></button>
        <p class="auth-field-hint" data-otp-countdown data-template="{{ __('verification.resend_wait', ['seconds' => ':seconds']) }}">@if(($resendAfter ?? 0) > 0){{ __('verification.resend_wait', ['seconds' => $resendAfter]) }}@endif</p>
    </form>
    <form method="POST" action="{{ route('logout') }}" class="auth-logout" data-auth-form>
        @csrf
        <button type="submit" class="auth-button auth-button--soft" data-auth-submit data-pending-label="{{ __('auth-ui.working') }}"><span data-submit-label>{{ __('auth-ui.sign_out') }}</span><span class="auth-spinner" aria-hidden="true"></span></button>
    </form>
    <p class="auth-account-note">{{ __('auth-ui.wrong_email') }}</p>
</x-auth-layout>
