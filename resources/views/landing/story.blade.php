<section class="lp-story lp-section" id="our-story" data-reveal>
    <div class="lp-story-surface lp-wrap">
        <div class="lp-story-heading">@include('landing.heading', ['copy' => 'story.title'])<p class="lp-section-intro">{{ __('landing.story.intro') }}</p></div>
        <nav class="lp-story-arc" aria-label="{{ __('landing.story.navigation') }}">
            <svg class="lp-story-line" viewBox="0 0 1000 210" fill="none" preserveAspectRatio="none" aria-hidden="true"><defs><mask id="story-arc-gaps" maskUnits="userSpaceOnUse" x="0" y="-80" width="1000" height="370"><rect x="0" y="-80" width="1000" height="370" fill="white"/><ellipse cx="60" cy="164"/><ellipse cx="270" cy="69"/><ellipse class="lp-story-logo-gap" cx="500" cy="42"/><ellipse cx="730" cy="67"/><ellipse cx="940" cy="162"/></mask></defs><path pathLength="1" d="M60 170C270 -12 730 -12 940 170" mask="url(#story-arc-gaps)"/></svg>
            <a class="lp-story-node lp-story-node--tracker" href="{{ $workspaceUrl }}"><span>@include('landing.icon', ['name' => 'tracker'])</span><small>{{ __('landing.demo.tracker') }}</small></a>
            <a class="lp-story-node lp-story-node--calendar" href="{{ auth()->check() ? route('interviews') : route('register') }}"><span>@include('landing.icon', ['name' => 'calendar'])</span><small>{{ __('landing.demo.calendar') }}</small></a>
            <a class="lp-story-node lp-story-node--nara" href="{{ $studioUrl }}"><span><img draggable="false" src="{{ asset('images/icon.png') }}" width="38" height="38" alt=""></span><small>Nara AI</small></a>
            <a class="lp-story-node lp-story-node--cv" href="{{ $cvUrl }}"><span>@include('landing.icon', ['name' => 'document'])</span><small>CV</small></a>
            <a class="lp-story-node lp-story-node--analytics" href="{{ auth()->check() ? route('summary') : route('register') }}"><span>@include('landing.icon', ['name' => 'analytics'])</span><small>{{ __('landing.story.analytics') }}</small></a>
        </nav>
        <div class="lp-story-message"><p>{{ __('landing.story.message') }}</p><div class="lp-story-founder"><span class="lp-story-portrait"><img draggable="false" src="{{ asset('images/landing/erlan-founder.webp') }}" width="52" height="52" alt="" loading="lazy" decoding="async"></span><div><strong>Erlan Irhab</strong><span>Co-founder, TraKerja</span></div></div></div>
    </div>
</section>
