<x-auth-layout page="reset">
    <form method="POST" action="{{ route('password.store') }}" class="auth-form" data-auth-form>
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <x-auth-field name="email" type="email" :label="__('auth-ui.email')" autocomplete="email" :value="old('email', $request->email)" :placeholder="__('auth-ui.email_placeholder')" />
        <x-auth-field name="password" type="password" :label="__('auth-ui.new_password')" autocomplete="new-password" minlength="8" :hint="__('auth-ui.reset_hint')" />
        <x-auth-field name="password_confirmation" type="password" :label="__('auth-ui.confirm_password')" autocomplete="new-password" minlength="8" />
        @include('auth.partials.submit', ['label' => __('auth-ui.reset_action')])
    </form>
    <a class="auth-back" href="{{ route('login') }}">{{ __('auth-ui.back_login') }}</a>
</x-auth-layout>
