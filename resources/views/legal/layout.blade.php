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
    @vite('resources/css/app.css')
</head>
<body class="utility-page legal-page">
    <a class="utility-skip" href="#document">Skip to document</a>
    <div class="utility-shell legal-shell">
        <header class="legal-topbar">
            <a class="legal-brand" href="{{ url('/') }}" aria-label="TraKerja home">
                <img src="{{ asset('images/icon.png') }}" alt="" width="24" height="24">
                <span>TraKerja</span>
            </a>
            <a class="legal-back-link" href="{{ url('/') }}">Back to home <span aria-hidden="true">→</span></a>
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
