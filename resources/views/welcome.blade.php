<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('landing.meta_title') }}</title>
    <meta name="description" content="{{ __('landing.meta_description') }}">
    <meta name="theme-color" content="#ffffff">
    <link rel="canonical" href="{{ url('/') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icon.png') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="{{ __('landing.meta_title') }}">
    <meta property="og:description" content="{{ __('landing.meta_description') }}">
    <meta property="og:image" content="{{ asset('images/landing/career-path.webp') }}">
    <script>try { document.documentElement.classList.toggle('dark', localStorage.getItem('theme') === 'dark'); } catch (e) {}</script>
    @vite(['resources/css/landing.css', 'resources/js/landing.js'])
    @include('partials.locale-config')
</head>
<body class="landing-page" id="top">
    @php
        $workspaceUrl = auth()->check() ? url('/dashboard') : route('register');
        $cvUrl = auth()->check() ? url('/cv/builder') : route('register');
        $studioUrl = auth()->check() ? url('/ai-studio') : route('register');
    @endphp
    <a class="lp-skip" href="#main">{{ __('landing.nav.skip') }}</a>
    @include('landing.navigation')
    <main id="main">
        @include('landing.hero')
        @include('landing.demo')
        @include('landing.story')
        @include('landing.ai')
        @include('landing.plans')
        @include('landing.testimonials')
        @include('landing.people')
        @include('landing.faq')
    </main>
    @include('landing.footer')
</body>
</html>
