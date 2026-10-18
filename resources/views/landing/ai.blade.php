<section class="lp-prep-section lp-wrap" id="preparation" data-reveal>
    <div class="lp-prep-banner">
        <div class="lp-prep-copy">
            <p class="lp-eyebrow">{{ __('landing.ai.eyebrow') }}</p>
            @include('landing.heading', ['copy' => 'ai.title'])
            <p class="lp-section-intro">{{ __('landing.ai.intro') }}</p>
            <a href="{{ $studioUrl }}" class="lp-button lp-button--dark">{{ __('landing.ai.action') }}<span>@include('landing.icon', ['name' => 'arrow'])</span></a>
            <div class="lp-prep-tools">@foreach(__('landing.ai.modes') as $mode)<span>{{ $mode }}</span>@endforeach</div>
            <p class="lp-prep-note">{{ __('landing.ai.note') }}</p>
        </div>
        <div class="lp-prep-visual">
            <img draggable="false" class="lp-prep-person" src="{{ asset('images/landing/preparation-portrait.webp') }}" width="1024" height="1536" alt="{{ __('landing.ai.image_alt') }}" loading="lazy" decoding="async">
            <div class="lp-prep-cards">
            <div class="lp-prep-interview">@include('landing.icon', ['name' => 'calendar'])<span>{{ __('landing.ai.modes')[2] }}</span>@include('landing.icon', ['name' => 'check'])</div>
            <div class="lp-prep-review">
                <div class="lp-prep-review-top"><span class="lp-mini-paper" aria-hidden="true"><b></b><i></i><i></i><i></i></span><span>{{ __('landing.ai.modes')[0] }}</span></div>
                <strong>{{ __('landing.ai.review_title') }}</strong>
                <div class="lp-prep-nara"><img draggable="false" src="{{ asset('images/brand/nara-avatar.svg') }}" width="29" height="29" alt=""><span>{{ __('landing.ai.partner') }}</span></div>
            </div>
            </div>
        </div>
    </div>
</section>
