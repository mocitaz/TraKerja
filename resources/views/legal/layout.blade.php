<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description')">
    <title>@yield('title') · TraKerja</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body class="utility-page legal-page">
    <a class="utility-skip" href="#document">Skip to document</a>
    <div class="utility-shell">
        <header class="utility-topbar">
            <a class="utility-brand" href="{{ url('/') }}">TraKerja<span>Legal</span></a>
            <a class="utility-link" href="{{ url('/') }}">Back to home <span aria-hidden="true">↗</span></a>
        </header>
        <main id="document" tabindex="-1">
            <header class="legal-heading">
                <p class="utility-eyebrow">Legal information</p>
                <h1>@yield('title')</h1>
                <p>@yield('description')</p>
            </header>
            <nav class="legal-tabs" aria-label="Legal documents">
                <a href="{{ url('/terms-of-service') }}" @if(trim($__env->yieldContent('document')) === 'terms-of-service') aria-current="page" @endif>Terms of Service</a>
                <a href="{{ url('/privacy-policy') }}" @if(trim($__env->yieldContent('document')) === 'privacy-policy') aria-current="page" @endif>Privacy Policy</a>
            </nav>
            <div class="legal-grid">
                <aside class="legal-sidebar">
                    <nav aria-label="On this page"><h2>On this page</h2>@yield('contents')</nav>
                    <div class="legal-contact"><h2>Have a question?</h2><p>Contact the TraKerja team.</p><a href="mailto:trakerja@teknalogi.id">trakerja@teknalogi.id</a></div>
                </aside>
                <article class="legal-article" aria-label="@yield('title')">@yield('article')</article>
            </div>
        </main>
        <footer class="utility-footer"><span>TraKerja · PT Teknalogi Transformasi Digital</span><a href="#document">Back to top ↑</a></footer>
    </div>
</body>
</html>
