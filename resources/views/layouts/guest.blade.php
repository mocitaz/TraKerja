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

        <!-- Phosphor Icons -->
        <script src="https://unpkg.com/@phosphor-icons/web"></script>

        <!-- Anti-FOUC & Theme Initialization Script -->
        <script>
            (function() {
                try {
                    if (!localStorage.getItem('trakerja_theme_force_light_v6')) {
                        localStorage.setItem('theme', 'light');
                        document.cookie = 'theme=light; path=/; max-age=31536000; SameSite=Lax';
                        localStorage.setItem('trakerja_theme_force_light_v6', '1');
                    }
                    var theme = localStorage.getItem('theme');
                    if (theme === 'dark') {
                        document.documentElement.classList.add('dark');
                        document.cookie = 'theme=dark; path=/; max-age=31536000; SameSite=Lax';
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                        document.cookie = 'theme=light; path=/; max-age=31536000; SameSite=Lax';
                    }
                } catch (e) {}
            })();
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        @include('partials.locale-config')
</head>
    <body class="guest-shell font-sans text-[#1a191e] dark:text-[#f0f4f9] bg-[#fdfcf9] dark:bg-[#131314] antialiased transition-colors duration-200 relative selection:bg-[#5b3d8c]/15">
        <div class="gemini-ambient-glow" aria-hidden="true"></div>

        <!-- Top Navigation Controls -->
        <header class="w-full pointer-events-none fixed top-0 inset-x-0 z-50 p-4 sm:p-6 flex items-center justify-end">
            <!-- Theme Toggle Button -->
            <button type="button"
                    x-data="{
                        toggle() {
                            if (typeof window.toggleTheme === 'function') {
                                window.toggleTheme();
                            } else {
                                var isDark = document.documentElement.classList.toggle('dark');
                                var theme = isDark ? 'dark' : 'light';
                                try { localStorage.setItem('theme', theme); } catch (e) {}
                                document.cookie = 'theme=' + theme + '; path=/; max-age=31536000; SameSite=Lax';
                                window.dispatchEvent(new CustomEvent('theme-changed', { detail: { isDark: isDark } }));
                            }
                        }
                    }"
                    @click="toggle()"
                    class="theme-toggle-btn pointer-events-auto"
                    aria-label="{{ __('Toggle dark/light mode') }}"
                    title="{{ __('Toggle dark/light mode') }}">
                <i class="ph-bold ph-sun theme-icon-sun" aria-hidden="true"></i>
                <i class="ph-bold ph-moon theme-icon-moon" aria-hidden="true"></i>
            </button>
        </header>

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
    <div class="guest-language"><x-language-switch location="guest" /></div>
</body>
</html>
