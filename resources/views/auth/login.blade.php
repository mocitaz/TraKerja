<x-auth-layout page="login">
    <details class="auth-email" @if($errors->any() || old('email')) open @endif>
        <summary class="auth-button auth-button--primary"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="3"/><path d="m4 7 8 6 8-6"/></svg><span>{{ __('auth-ui.email_option') }}</span></summary>
        <form method="POST" action="{{ route('login') }}" class="auth-form" data-auth-form>
            @csrf
            <x-auth-field name="email" type="email" :label="__('auth-ui.email')" autocomplete="email" :value="old('email')" :placeholder="__('auth-ui.email_placeholder')" />
            <x-auth-field name="password" type="password" :label="__('auth-ui.password')" autocomplete="current-password" />
            <div class="auth-form-options"><label class="auth-checkbox" for="remember_me"><input id="remember_me" name="remember" type="checkbox" @checked(old('remember'))><span>{{ __('auth-ui.remember') }}</span></label><a href="{{ route('password.request') }}">{{ __('auth-ui.forgot_link') }}</a></div>
            @include('auth.partials.turnstile')
            @include('auth.partials.submit', ['label' => __('auth-ui.sign_in')])
        </form>
    </details>
    @include('auth.partials.social')
    <p class="auth-switch">{{ __('auth-ui.new_here') }} <a href="{{ route('register') }}">{{ __('auth-ui.join') }}</a></p>
</x-auth-layout>
