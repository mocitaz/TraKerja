<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('code') · @yield('title') · TraKerja</title>
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}?v=2">
    <link rel="preconnect" href="https://fonts.bunny.net">
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

    @vite('resources/css/app.css')
</head>
<body class="utility-page error-page bg-[#fdfcf9] dark:bg-[#131314] text-[#34313d] dark:text-[#f0f4f9] transition-colors duration-200 relative">
    <div class="gemini-ambient-glow" aria-hidden="true"></div>
    <div class="error-shell">
        <main class="error-stage">
            <section class="error-scene" aria-labelledby="error-title">
                <div class="error-illustration" aria-hidden="true">
                    @include('errors.partials.workspace-illustration')
                </div>
                <div class="error-copy">
                    <p class="error-status">Error <span class="error-code">@yield('code')</span></p>
                    <h1 id="error-title">@yield('title')</h1>
                    <p class="error-description">@yield('description')</p>
                    <p class="error-recovery">@yield('recovery')</p>
                    <div class="error-actions">
                        @if(trim($__env->yieldContent('code')) === '419')
                            <a class="error-button error-button--primary" href="{{ url('/login') }}">Sign in again <span aria-hidden="true">→</span></a>
                        @elseif(trim($__env->yieldContent('code')) === '503')
                            <a class="error-button error-button--primary" href="{{ url()->current() }}">Try again <span aria-hidden="true">→</span></a>
                        @else
                            <a class="error-button error-button--primary" href="{{ url('/') }}">Back to home <span aria-hidden="true">→</span></a>
                        @endif
                        <a class="error-support" href="mailto:trakerja@teknalogi.id">Contact support <span aria-hidden="true">↗</span></a>
                    </div>
                    <p class="error-support-note">When contacting support, include error @yield('code') and the page address.</p>
                </div>
            </section>
        </main>
        <header class="error-brand-header">
            <a class="error-brand" href="{{ url('/') }}" aria-label="TraKerja home"><img src="{{ asset('images/icon.png') }}" alt="" width="28" height="28"><span>TraKerja</span></a>
        </header>
        <footer class="error-footer">
            <div><p>© {{ now()->year }} TraKerja. All rights reserved.</p><p>PT Teknalogi Transformasi Digital</p></div>
            <nav aria-label="Legal"><a href="{{ url('/privacy-policy') }}">Privacy</a><a href="{{ url('/terms-of-service') }}">Terms</a></nav>
        </footer>
    </div>
</body>
</html>
