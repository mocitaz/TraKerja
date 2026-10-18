<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description')">
    <title>@yield('page_title') | TraKerja</title>
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}?v=2">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700" rel="stylesheet">
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

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <script>
        function updateLegalToggleUI(isDark) {
            var btn = document.getElementById('legal-theme-toggle-btn');
            if (!btn) return;
            if (isDark) {
                btn.setAttribute('title', 'Mode Gelap aktif (klik untuk ganti ke Mode Terang)');
                btn.setAttribute('aria-label', @js(__('Mode gelap aktif, klik untuk beralih ke mode terang')));
            } else {
                btn.setAttribute('title', 'Mode Terang aktif (klik untuk ganti ke Mode Gelap)');
                btn.setAttribute('aria-label', @js(__('Mode terang aktif, klik untuk beralih ke mode gelap')));
            }
        }

        function toggleLegalTheme() {
            if (typeof window.toggleTheme === 'function') {
                var nextDark = window.toggleTheme();
                updateLegalToggleUI(nextDark);
                return;
            }

            var isDark = document.documentElement.classList.contains('dark');
            var nextDark = !isDark;
            var nextTheme = nextDark ? 'dark' : 'light';

            if (nextDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            try {
                localStorage.setItem('theme', nextTheme);
            } catch (e) {}
            document.cookie = 'theme=' + nextTheme + '; path=/; max-age=31536000; SameSite=Lax';

            if (typeof window.setTheme === 'function') {
                window.setTheme(nextTheme);
            } else {
                window.dispatchEvent(new CustomEvent('theme-changed', { detail: { isDark: nextDark } }));
            }

            updateLegalToggleUI(nextDark);
        }

        document.addEventListener('DOMContentLoaded', function() {
            var isDark = document.documentElement.classList.contains('dark');
            updateLegalToggleUI(isDark);
        });
        window.addEventListener('theme-changed', function(e) {
            if (e.detail && typeof e.detail.isDark === 'boolean') {
                updateLegalToggleUI(e.detail.isDark);
            }
        });
    </script>
    @include('partials.locale-config')
</head>
<body class="utility-page legal-page bg-white dark:bg-[#131314] text-[#34313d] dark:text-[#f0f4f9] transition-colors duration-200 relative">
    <div class="gemini-ambient-glow" aria-hidden="true"></div>
    <a class="utility-skip" href="#document">{{ __('Skip to document') }}</a>
    <div class="utility-shell legal-shell">
        <header class="legal-topbar">
            <a class="legal-brand" href="{{ url('/') }}" aria-label="{{ __('TraKerja home') }}">
                <img src="{{ asset('images/icon.png') }}" alt="" width="24" height="24">
                <span>{{ __('TraKerja') }}</span>
            </a>
            <div class="legal-topbar-actions">
                <!-- Theme Toggle Pill -->
                <button type="button"
                    id="legal-theme-toggle-btn"
                    onclick="toggleLegalTheme()"
                    class="legal-theme-toggle-btn"
                    title="{{ __('Beralih tema (Dark/Light)') }}"
                    aria-label="{{ __('Beralih mode gelap atau terang') }}">
                    <i class="ph-bold ph-sun legal-icon-sun" aria-hidden="true"></i>
                    <i class="ph-bold ph-moon legal-icon-moon" aria-hidden="true"></i>
                </button>
                <a class="legal-back-link" href="{{ url('/') }}">{{ __('Back to home') }} <span aria-hidden="true">→</span></a>
            </div>
        </header>
        <main id="document" tabindex="-1">
            <header class="legal-heading">
                <p class="legal-eyebrow">{{ __('Legal information') }}</p>
                <h1>@yield('title')</h1>
                <p class="legal-description">@yield('description')</p>
            </header>
            <nav class="legal-tabs" aria-label="{{ __('Legal documents') }}">
                <a href="{{ url('/terms-of-service') }}" @if(trim($__env->yieldContent('document')) === 'terms-of-service') aria-current="page" @endif>Terms of Service</a>
                <a href="{{ url('/privacy-policy') }}" @if(trim($__env->yieldContent('document')) === 'privacy-policy') aria-current="page" @endif>Privacy Policy</a>
            </nav>
            <div class="legal-grid">
                <aside class="legal-sidebar">
                    <nav aria-label="{{ __('On this page') }}">
                        <h2>{{ __('On this page') }}</h2>
                        @yield('contents')
                    </nav>
                    <div class="legal-contact">
                        <h2>{{ __('Have a question?') }}</h2>
                        <p>{{ __('Contact the TraKerja team.') }}</p>
                        <a href="mailto:trakerja@teknalogi.id">trakerja@teknalogi.id <span aria-hidden="true">↗</span></a>
                    </div>
                </aside>
                <article class="legal-article" aria-label="@yield('title')">
                    @yield('article')
                </article>
            </div>
        </main>
        <footer class="legal-footer"><x-language-switch location="legal" />
            <div>
                <p>{{ __('© :value1 TraKerja. All rights reserved.', ['value1' => (now()->year)]) }}</p>
                <p>{{ __('PT Teknalogi Transformasi Digital') }}</p>
            </div>
            <nav aria-label="{{ __('Legal') }}">
                <a href="{{ url('/privacy-policy') }}">{{ __('Privacy Policy') }}</a>
                <a href="{{ url('/terms-of-service') }}">{{ __('Terms of Service') }}</a>
                <a href="#document">{{ __('Back to top ↑') }}</a>
            </nav>
        </footer>
    </div>
    @livewireScripts
</body>
</html>
