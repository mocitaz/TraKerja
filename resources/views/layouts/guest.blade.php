<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TraKerja') }}</title>
        
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}?v=2">
        <link rel="apple-touch-icon" href="{{ asset('images/icon.png') }}?v=2">

        <!-- PWA Meta & Manifest -->
        <link rel="manifest" href="{{ asset('site.webmanifest') }}">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="TraKerja">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600|inter:300,400,500,600,700,800,900|plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Anti-FOUC Script -->
        <script>
            (function() {
                try {
                    var theme = localStorage.getItem('theme');
                    var supportDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    if (theme === 'dark' || (!theme && supportDark)) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                } catch (e) {}
            })();
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans text-gray-900 dark:text-[#f0f4f9] bg-[#fafafa] dark:bg-[#131314] antialiased transition-colors duration-200">
        <div class="gemini-ambient-glow" aria-hidden="true"></div>
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
        
        <!-- Register PWA Service Worker -->
        <script>
            @include('components.service-worker-registration')
        </script>
    </body>
</html>
