@php
    $titleId = $isModal ? 'community-modal-title' : 'community-page-title';
    $descriptionId = $isModal ? 'community-modal-description' : 'community-page-description';
@endphp
<section class="community-welcome" @if($isModal) role="dialog" aria-modal="true" aria-labelledby="{{ $titleId }}" aria-describedby="{{ $descriptionId }}" tabindex="-1" x-dialog="open" @endif>
    @if($isModal)<button type="button" class="community-close" @click="open = false" aria-label="{{ __('Close welcome message') }}"><i class="ph ph-x" aria-hidden="true"></i></button>@endif
    <div class="community-copy">
        @if($isModal)<h2 id="{{ $titleId }}">{{ __('Welcome back') }}<span class="community-heading-dot">.</span></h2>@else<h1 id="{{ $titleId }}">{{ __('Welcome back') }}<span class="community-heading-dot">.</span></h1>@endif
        <p class="community-intro">{{ __('Your next move feels better') }}<br class="community-desktop-break"> {{ __('with a little company.') }}</p>
        <p class="community-description" id="{{ $descriptionId }}">{{ __('Connect with fellow job seekers, share opportunities, and talk through your career journey in our Telegram community.') }}</p>
        <div class="community-actions">
            <a class="community-join" href="https://t.me/HubTrakerja" target="_blank" rel="noopener noreferrer"><i class="ph ph-telegram-logo" aria-hidden="true"></i><span>{{ __('Join HubTrakerja') }}</span></a>
            <a class="community-dashboard" href="{{ route('dashboard') }}" wire:navigate @if($isModal) @click="open = false" @endif>{{ __('Go to Dashboard') }} <span aria-hidden="true">→</span></a>
        </div>
        <p class="community-footnote">{{ __('Joining is optional. Your workspace is ready whenever you are.') }}</p>
    </div>
    <div class="community-art" aria-hidden="true">
        <div class="community-art-caption">{{ __('A place to find your people.') }}</div>
        <svg class="community-connections" viewBox="0 0 400 400" fill="none"><path d="M65 310C32 224 110 100 222 111C320 120 360 213 316 314" stroke="#bd91af" stroke-width="1.5" stroke-dasharray="5 8"/><path d="M103 284C151 360 269 358 309 290" stroke="#bd91af" stroke-width="1.5" stroke-dasharray="5 8"/></svg>
        <div class="community-avatar community-avatar--peach"><img src="{{ asset('images/avatars/peach.svg') }}" alt="" width="120" height="120"></div>
        <div class="community-avatar community-avatar--lilac"><img src="{{ asset('images/avatars/lilac.svg') }}" alt="" width="94" height="94"></div>
        <div class="community-avatar community-avatar--sky"><img src="{{ asset('images/avatars/sky.svg') }}" alt="" width="100" height="100"></div>
        <div class="community-speech community-speech--hello">{{ __('Hey, welcome in!') }} <span>✦</span></div>
        <div class="community-speech community-speech--share"><span class="community-speech-dot"></span>{{ __('New paths. Shared together.') }}</div>
        <div class="community-plane"><svg viewBox="0 0 90 90" fill="none"><path d="M12 38L77 15L57 77L39 52L12 38Z" fill="#fffafc" stroke="#994577" stroke-width="2" stroke-linejoin="round"/><path d="M39 52L77 15L30 45" stroke="#994577" stroke-width="2" stroke-linejoin="round"/><path d="M39 52L38 70L48 63" stroke="#994577" stroke-width="2" stroke-linejoin="round"/><path d="M7 61L19 58M14 75L28 69" stroke="#b65b90" stroke-width="2" stroke-linecap="round"/></svg></div>
        <span class="community-spark community-spark--one">✧</span><span class="community-spark community-spark--two">✦</span>
        <div class="community-art-footer"><span>{{ __('Different journeys.') }}</span><strong>{{ __('One community.') }}</strong></div>
    </div>
</section>
