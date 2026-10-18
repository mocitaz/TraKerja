<x-auth-layout page="register">
    <details class="auth-email" @if($errors->any() || old('email') || old('name')) open @endif>
        <summary class="auth-button auth-button--primary"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="3"/><path d="m4 7 8 6 8-6"/></svg><span>{{ __('auth-ui.email_option') }}</span></summary>
        <form method="POST" action="{{ route('register') }}" class="auth-form" data-auth-form>
            @csrf
            <x-auth-field name="name" :label="__('auth-ui.name')" autocomplete="name" :value="old('name')" :placeholder="__('auth-ui.name_placeholder')" />
            <x-auth-field name="email" type="email" :label="__('auth-ui.email')" autocomplete="email" :value="old('email')" :placeholder="__('auth-ui.email_placeholder')" />
            <x-auth-field name="password" type="password" :label="__('auth-ui.password')" autocomplete="new-password" minlength="8" :hint="__('auth-ui.password_hint')" />
            <x-auth-field name="password_confirmation" type="password" :label="__('auth-ui.confirm_password')" autocomplete="new-password" minlength="8" />
            @include('auth.partials.turnstile')
            @include('auth.partials.submit', ['label' => __('auth-ui.create')])
        </form>
    </details>
    @include('auth.partials.social')
    <p class="auth-switch">{{ __('auth-ui.have_account') }} <a href="{{ route('login') }}">{{ __('auth-ui.sign_in') }}</a></p>
</x-auth-layout>
