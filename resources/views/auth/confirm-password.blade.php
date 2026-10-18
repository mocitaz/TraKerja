<x-auth-layout page="confirm">
    <form method="POST" action="{{ route('password.confirm') }}" class="auth-form" data-auth-form>
        @csrf
        <x-auth-field name="password" type="password" :label="__('auth-ui.password')" autocomplete="current-password" />
        @include('auth.partials.submit', ['label' => __('auth-ui.confirm_action')])
    </form>
    <a class="auth-back" href="{{ route('dashboard') }}">{{ __('auth-ui.continue_workspace') }}</a>
</x-auth-layout>
