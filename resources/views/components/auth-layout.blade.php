@props(['page' => 'login'])
<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex,follow">
    <title>{{ __('page-titles.'.$page) }} | TraKerja</title>
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icon.png') }}">
    <link rel="preload" href="{{ asset('fonts/google-sans-flex/latin.woff2') }}" as="font" type="font/woff2" crossorigin>
    <script>try { document.documentElement.classList.toggle('dark', localStorage.getItem('theme') === 'dark'); } catch (e) {}</script>
    @vite(['resources/css/auth.css', 'resources/js/auth.js'])
    @include('partials.locale-config')
</head>
<body class="auth-page" data-auth-page="{{ $page }}">
    <a class="auth-skip" href="#auth-content">{{ __('auth-ui.skip') }}</a>
    <div class="auth-shell">
        <section class="auth-main" aria-labelledby="auth-title">
            <header class="auth-toolbar">
                <a class="auth-home" href="{{ url('/') }}">@include('landing.icon', ['name' => 'arrow'])<span>{{ __('auth-ui.home') }}</span></a>
                <div class="auth-controls"><x-language-switch location="public" /><button type="button" class="auth-theme" data-theme-toggle aria-label="{{ __('auth-ui.theme') }}" title="{{ __('auth-ui.theme') }}">@include('landing.icon', ['name' => 'moon'])</button></div>
            </header>
            <main class="auth-content" id="auth-content">
                <div class="auth-heading">
                    <a class="auth-brand" href="{{ url('/') }}" aria-label="TraKerja"><img draggable="false" src="{{ asset('images/icon.png') }}" width="31" height="31" alt=""><span>TraKerja</span></a>
                    <h1 id="auth-title">{{ __('auth-ui.'.$page.'.title') }}</h1>
                    <p>{{ __('auth-ui.'.$page.'.intro') }}</p>
                </div>
                @if ($errors->any())
                    <div class="auth-notice auth-notice--error" role="alert" tabindex="-1" data-error-summary><strong>{{ __('auth-ui.error_title') }}</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif
                @if (session('status'))
                    <div class="auth-notice" role="status">
                        @if(in_array(session('status'), ['registration-successful', 'email-not-verified'])){{ __('auth-ui.verify_needed') }}
                        @elseif(session('status') === 'verification-link-sent'){{ __('auth-ui.resent') }}
                        @else{{ __(session('status')) }}@endif
                    </div>
                @endif
                {{ $slot }}
                <footer class="auth-legal"><p>{{ __('auth-ui.terms_intro') }} <a href="{{ route('legal.terms') }}">{{ __('auth-ui.terms') }}</a> {{ __('auth-ui.and') }} <a href="{{ route('legal.privacy') }}">{{ __('auth-ui.privacy') }}</a>.</p><span>PT. Teknalogi Transformasi Digital</span></footer>
            </main>
        </section>
        <aside class="auth-showcase" aria-hidden="true">
            <div class="auth-showcase-visual"><img draggable="false" src="{{ asset('images/auth/iphone-landing-cutout.webp') }}" width="1536" height="1024" alt="" fetchpriority="high" decoding="async"></div>
        </aside>
    </div>
</body>
</html>
