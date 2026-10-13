<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ request()->cookie('theme') === 'dark' ? 'dark' : '' }}">
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

        <!-- Anti-FOUC & Theme Initialization Script -->
        <script>
            (function() {
                try {
                    var theme = localStorage.getItem('theme');
                    var supportDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    var isDark = theme === 'dark' || (!theme && supportDark);
                    if (isDark) {
                        document.documentElement.classList.add('dark');
                        if (!document.cookie.includes('theme=dark')) {
                            document.cookie = 'theme=dark; path=/; max-age=31536000; SameSite=Lax';
                        }
                    } else {
                        document.documentElement.classList.remove('dark');
                        if (!document.cookie.includes('theme=light')) {
                            document.cookie = 'theme=light; path=/; max-age=31536000; SameSite=Lax';
                        }
                    }
                } catch (e) {}
            })();
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans text-[#1a191e] dark:text-[#f0f4f9] bg-[#fdfcf9] dark:bg-[#131314] antialiased transition-colors duration-200 relative selection:bg-[#35264f]/15">
        <div class="gemini-ambient-glow" aria-hidden="true"></div>

        <!-- Top Navigation Controls -->
        <header class="w-full pointer-events-none fixed top-0 inset-x-0 z-50 p-4 sm:p-6 flex items-center justify-between">
            <!-- Back to Home Button -->
            <a href="{{ url('/') }}" class="pointer-events-auto inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-[#dedbd6] dark:border-white/10 bg-white/80 dark:bg-[#1e1f20]/80 backdrop-blur text-xs font-semibold text-zinc-600 dark:text-[#94a3b8] hover:text-[#35264f] dark:hover:text-[#f0f4f9] hover:border-zinc-400 dark:hover:border-white/20 transition-all shadow-3xs hover:shadow-2xs">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span>TraKerja</span>
            </a>

            <!-- Theme Toggle Pill -->
            <div class="pointer-events-auto"
                 x-data="{ isDark: document.documentElement.classList.contains('dark') }"
                 @theme-changed.window="isDark = $event.detail.isDark">
                <button type="button"
                    @click="isDark = !isDark;
                            if (isDark) {
                                document.documentElement.classList.add('dark');
                                localStorage.setItem('theme', 'dark');
                                document.cookie = 'theme=dark; path=/; max-age=31536000; SameSite=Lax';
                            } else {
                                document.documentElement.classList.remove('dark');
                                localStorage.setItem('theme', 'light');
                                document.cookie = 'theme=light; path=/; max-age=31536000; SameSite=Lax';
                            }
                            window.dispatchEvent(new CustomEvent('theme-changed', { detail: { isDark } }));"
                    class="inline-flex items-center justify-center w-8 h-8 rounded-full border transition-all duration-200 cursor-pointer shadow-3xs border-[#dedbd6] bg-white text-amber-500 hover:bg-[#faf8f5] dark:border-white/10 dark:bg-[#1e1f20] dark:text-purple-300 dark:hover:bg-[#282a2c]"
                    title="Toggle light/dark mode"
                    aria-label="Toggle dark mode">
                    <template x-if="!isDark">
                        <svg class="w-4 h-4 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="4"></circle>
                            <path d="M12 2v2"></path>
                            <path d="M12 20v2"></path>
                            <path d="m4.93 4.93 1.41 1.41"></path>
                            <path d="m17.66 17.66 1.41 1.41"></path>
                            <path d="M2 12h2"></path>
                            <path d="M20 12h2"></path>
                            <path d="m6.34 17.66-1.41 1.41"></path>
                            <path d="m19.07 4.93-1.41 1.41"></path>
                        </svg>
                    </template>
                    <template x-if="isDark">
                        <svg class="w-4 h-4 text-purple-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path>
                        </svg>
                    </template>
                </button>
            </div>
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
    </body>
</html>
