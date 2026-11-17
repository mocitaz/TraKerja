<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} | TraKerja</title>
    <meta name="description" content="{{ $description }}">
    @isset($canonical)<link rel="canonical" href="{{ $canonical }}">@endisset
    @if($noindex ?? false)<meta name="robots" content="noindex">@endif
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="TraKerja">
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}?v=2">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <script>try { document.documentElement.classList.toggle('dark', localStorage.getItem('theme') === 'dark'); } catch (e) {}</script>
    @stack('head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white dark:bg-[#131314] text-slate-900 dark:text-[#f0f4f9] pj-body">
    <div class="workspace-shell pj-shell">
        <header class="workspace-navigation pj-navigation">
            <nav class="workspace-topbar" aria-label="Navigasi utama">
                <a href="{{ url('/') }}" class="workspace-brand" aria-label="TraKerja"><img src="{{ asset('images/icon.png') }}" alt="" width="28" height="28"><span>TraKerja</span></a>
                <div class="workspace-account-actions">
                    <a href="{{ url('/') }}" class="pj-btn pj-btn--primary pj-topbar-cta">Kenali TraKerja<i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>
                </div>
            </nav>
        </header>

        <main class="workspace-main pj-main">
            <div class="pj-container">@yield('content')</div>
            <x-workspace-footer />
        </main>
    </div>
</body>
</html>
