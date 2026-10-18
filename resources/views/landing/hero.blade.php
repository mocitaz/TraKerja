<section class="lp-hero lp-wrap" aria-labelledby="hero-title" data-reveal>
    <div class="lp-hero-copy">
        <h1 id="hero-title" aria-label="{{ __('landing.hero.line_one').' '.__('landing.hero.line_two') }}" data-hero-running>
            @foreach(['line_one', 'line_two'] as $line)
                <span class="lp-hero-line" aria-hidden="true">@foreach(explode(' ', __('landing.hero.'.$line)) as $word)<span class="lp-hero-word">@foreach(preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY) as $letter)<span class="lp-hero-letter">{{ $letter }}</span>@endforeach</span>@if(!$loop->last){{ ' ' }}@endif@endforeach</span>
            @endforeach
        </h1>
        <p class="lp-hero-intro">{{ __('landing.hero.intro') }}</p>
        <div class="lp-hero-actions">
            <a href="{{ $workspaceUrl }}" class="lp-button lp-button--dark">{{ auth()->check() ? __('landing.nav.dashboard') : __('landing.hero.primary') }}</a>
            <a href="#product" class="lp-button lp-button--outline" data-open-demo>{{ __('landing.hero.secondary') }} @include('landing.icon', ['name' => 'play'])</a>
        </div>
        <p class="lp-hero-note">{{ __('landing.hero.note') }}</p>
    </div>
    <div class="lp-hero-art" data-perspective>
        <div class="lp-art-wash" aria-hidden="true"></div>
        <img draggable="false" class="lp-hero-portrait" src="{{ asset('images/landing/hero-portrait-branded-v3.webp') }}" alt="{{ __('landing.hero.portrait_alt') }}" width="1254" height="1254" fetchpriority="high">
        <div class="lp-paper-ribbon lp-paper-ribbon--back" aria-hidden="true"></div>
        <div class="lp-paper-ribbon" aria-hidden="true"></div>
        <div class="lp-float-card lp-float-card--schedule">
            <span class="lp-card-top"><span class="lp-calendar-mark">@include('landing.icon', ['name' => 'calendar'])</span><span>{{ __('landing.hero.card_label') }}</span></span>
            <strong>{{ __('landing.hero.card_title') }}</strong>
            <p>{{ __('landing.hero.card_detail') }}</p>
            <span class="lp-card-bottom">@include('landing.icon', ['name' => 'check']) {{ __('landing.demo.online') }}</span>
        </div>
        <div class="lp-float-card lp-float-card--nara">
            <img draggable="false" src="{{ asset('images/brand/nara-avatar.svg') }}" width="34" height="34" alt="">
            <div><strong>{{ __('landing.hero.nara_label') }}</strong><p>{{ __('landing.hero.nara_detail') }}</p></div>
            <span class="lp-nara-tag">AI</span>
        </div>
        <div class="lp-float-card lp-float-card--cv"><span class="lp-mini-paper" aria-hidden="true"><b></b><i></i><i></i><i></i></span><div><span>{{ __('landing.hero.cv_label') }}</span><strong>{{ __('landing.hero.cv_title') }}</strong></div></div>
        <span class="lp-art-caption">{{ __('landing.hero.illustration') }}</span>
    </div>
    @include('landing.sources')
</section>
