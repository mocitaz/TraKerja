<header class="lp-header">
    <nav class="lp-nav lp-wrap" aria-label="{{ __('Main navigation') }}">
        <a class="lp-brand" href="{{ url('/') }}" aria-label="{{ __('TraKerja home') }}"><img draggable="false" src="{{ asset('images/icon.png') }}" width="29" height="29" alt=""><span>TraKerja</span></a>
        <div class="lp-nav-links">
            <a href="#product">{{ __('landing.nav.product') }}</a>
            <a href="#our-story">{{ __('landing.nav.workflow') }}</a>
            <a href="#testimonials">{{ __('landing.testimonials.nav') }}</a>
            <a href="#faq">{{ __('landing.nav.faq') }}</a>
        </div>
        <div class="lp-nav-actions">
            <a class="lp-nav-plans" href="#pricing">{{ __('landing.nav.plans') }}</a>
            <x-language-switch location="public" />
            <button type="button" class="lp-theme" data-theme-toggle aria-label="{{ __('landing.nav.theme') }}" title="{{ __('landing.nav.theme') }}">@include('landing.icon', ['name' => 'moon'])</button>
            @auth
                <a class="lp-button lp-button--dark lp-nav-primary" href="{{ $workspaceUrl }}">{{ __('landing.nav.dashboard') }}</a>
            @else
                <a class="lp-nav-login" href="{{ route('login') }}">{{ __('landing.nav.login') }}</a>
            @endauth
            <details class="lp-mobile-menu">
                <summary>{{ __('landing.nav.menu') }}<span aria-hidden="true">+</span></summary>
                <nav aria-label="{{ __('Mobile navigation') }}">
                    <a href="#product">{{ __('landing.nav.product') }}</a>
                    <a href="#our-story">{{ __('landing.nav.workflow') }}</a>
                    <a href="#pricing">{{ __('landing.nav.plans') }}</a>
                    <a href="#testimonials">{{ __('landing.testimonials.nav') }}</a>
                    <a href="#faq">{{ __('landing.nav.faq') }}</a>
                    @guest<a href="{{ route('login') }}">{{ __('landing.nav.login') }}</a>@endguest
                    <a class="lp-button lp-button--dark" href="{{ $workspaceUrl }}">{{ __('landing.nav.start') }}</a>
                </nav>
            </details>
        </div>
    </nav>
</header>
