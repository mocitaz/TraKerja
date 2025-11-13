<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TraKerja') }}</title>
        
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col">
        {{ $slot ?? '' }}
        @yield('content')

        @php($noFooterRoutes = ['login', 'register', 'password.request', 'password.reset', 'verification.notice', 'legal.terms', 'legal.privacy'])
        @unless(in_array(Route::currentRouteName(), $noFooterRoutes))
            <!-- Footer -->
            <x-footer />
        @endunless
    </div>
        
        @livewireScripts
        
        {{-- Fallback: Load Livewire from CDN if local file fails --}}
        <script>
        // Wait a bit for Livewire to load, then check
        setTimeout(function() {
            if (typeof window.Livewire === 'undefined') {
                console.warn('Livewire not loaded from local assets, loading from CDN...');
                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/livewire@3/dist/livewire.min.js';
                script.onerror = function() {
                    console.error('Failed to load Livewire from CDN');
                };
                script.onload = function() {
                    console.log('Livewire loaded from CDN successfully');
                    // Re-initialize Livewire after CDN load
                    if (window.Livewire && typeof window.Livewire.start === 'function') {
                        window.Livewire.start();
                    }
                };
                document.head.appendChild(script);
            }
        }, 1000);
        </script>
    </body>
</html>
