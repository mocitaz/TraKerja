<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description')">
    <title>@yield('title') · TraKerja</title>
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
</head>
<body class="utility-page legal-page bg-[#fdfcf9] dark:bg-[#131314] text-[#34313d] dark:text-[#f0f4f9] transition-colors duration-200 relative">
    <div class="gemini-ambient-glow" aria-hidden="true"></div>
    <a class="utility-skip" href="#document">Skip to document</a>
    <div class="utility-shell legal-shell">
        <header class="legal-topbar">
            <a class="legal-brand" href="{{ url('/') }}" aria-label="TraKerja home">
                <img src="{{ asset('images/icon.png') }}" alt="" width="24" height="24">
                <span>TraKerja</span>
            </a>
            <div class="legal-topbar-actions">
                <!-- Theme Toggle Pill -->
                <div x-data="{
                         isDark: document.documentElement.classList.contains('dark'),
                         toggle() {
                             this.isDark = !this.isDark;
                             var theme = this.isDark ? 'dark' : 'light';
                             try {
                                 localStorage.setItem('theme', theme);
                             } catch (e) {}
                             document.cookie = 'theme=' + theme + '; path=/; max-age=31536000; SameSite=Lax';
                             if (this.isDark) {
                                 document.documentElement.classList.add('dark');
                             } else {
                                 document.documentElement.classList.remove('dark');
                             }
                             window.dispatchEvent(new CustomEvent('theme-changed', { detail: { isDark: this.isDark } }));
                         }
                     }"
                     @theme-changed.window="isDark = $event.detail.isDark">
                    <button type="button"
                        @click="toggle()"
                        class="w-9 h-9 rounded-full flex items-center justify-center transition-all duration-200 cursor-pointer focus:outline-none"
                        :class="isDark ? 'bg-[#1e1f20] hover:bg-[#282a2c] text-purple-300 hover:text-purple-200 border border-white/10 shadow-sm' : 'bg-white hover:bg-amber-50/70 text-amber-500 hover:text-amber-600 border border-[#dedbd6] shadow-3xs'"
                        :title="isDark ? 'Mode Gelap aktif (klik untuk ganti ke Mode Terang)' : 'Mode Terang aktif (klik untuk ganti ke Mode Gelap)'"
                        :aria-label="isDark ? 'Mode gelap aktif, klik untuk beralih ke mode terang' : 'Mode terang aktif, klik untuk beralih ke mode gelap'">
                        <i x-show="!isDark" class="ph-bold ph-sun text-lg transition-transform duration-200 hover:rotate-45" aria-hidden="true"></i>
                        <i x-show="isDark" x-cloak class="ph-bold ph-moon text-lg transition-transform duration-200 hover:-rotate-12" aria-hidden="true"></i>
                    </button>
                </div>
                <a class="legal-back-link" href="{{ url('/') }}">Back to home <span aria-hidden="true">→</span></a>
            </div>
        </header>
        <main id="document" tabindex="-1">
            <header class="legal-heading">
                <p class="legal-eyebrow">Legal information</p>
                <h1>@yield('title')</h1>
                <p class="legal-description">@yield('description')</p>
            </header>
            <nav class="legal-tabs" aria-label="Legal documents">
                <a href="{{ url('/terms-of-service') }}" @if(trim($__env->yieldContent('document')) === 'terms-of-service') aria-current="page" @endif>Terms of Service</a>
                <a href="{{ url('/privacy-policy') }}" @if(trim($__env->yieldContent('document')) === 'privacy-policy') aria-current="page" @endif>Privacy Policy</a>
            </nav>
            <div class="legal-grid">
                <aside class="legal-sidebar">
                    <nav aria-label="On this page">
                        <h2>On this page</h2>
                        @yield('contents')
                    </nav>
                    <div class="legal-contact">
                        <h2>Have a question?</h2>
                        <p>Contact the TraKerja team.</p>
                        <a href="mailto:trakerja@teknalogi.id">trakerja@teknalogi.id <span aria-hidden="true">↗</span></a>
                    </div>
                </aside>
                <article class="legal-article" aria-label="@yield('title')">
                    @yield('article')
                </article>
            </div>
        </main>
        <footer class="legal-footer">
            <div>
                <p>© {{ now()->year }} TraKerja. All rights reserved.</p>
                <p>PT Teknalogi Transformasi Digital</p>
            </div>
            <nav aria-label="Legal">
                <a href="{{ url('/privacy-policy') }}">Privacy Policy</a>
                <a href="{{ url('/terms-of-service') }}">Terms of Service</a>
                <a href="#document">Back to top ↑</a>
            </nav>
        </footer>
    </div>
</body>
</html>
