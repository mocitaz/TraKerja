<x-auth-layout page="forgot">
    <form method="POST" action="{{ route('password.email') }}" class="auth-form" data-auth-form>
        @csrf
        <x-auth-field name="email" type="email" :label="__('auth-ui.email')" autocomplete="email" :value="old('email')" :placeholder="__('auth-ui.email_placeholder')" />
        @include('auth.partials.submit', ['label' => __('auth-ui.send_link')])
    </form>
    <a class="auth-back" href="{{ route('login') }}">{{ __('auth-ui.back_login') }}</a>
</x-auth-layout>
