<section class="dashboard-welcome" x-data="dashboardWelcome()" :class="{'welcome-paused': paused || reduced}" @pointermove="move($event)" @pointerleave="reset()" @keydown.escape="if (open) close()" aria-labelledby="dashboard-greeting">
    <div class="dashboard-welcome-copy">
        <time datetime="{{ now()->timezone('Asia/Jakarta')->toDateString() }}">{{ now()->timezone('Asia/Jakarta')->locale(app()->getLocale())->isoFormat('dddd, LL') }}</time>
        <h1 id="dashboard-greeting">{{ __('Good to see you, :value1.', ['value1' => (explode(' ', Auth::user()->name)[0])]) }}</h1>
        <p>{{ __('Your applications, progress, and next steps in one place.') }}</p>
        <button type="button" onclick="openJobModal()" class="dashboard-primary-button"><i class="ph ph-plus" aria-hidden="true"></i>{{ __('Add application') }}</button>
    </div>
    <div class="welcome-scene">
        <div class="welcome-orbit" aria-hidden="true"></div>
        <div class="welcome-companion-position">
            <button type="button" class="welcome-companion" x-ref="companion" @click="open = !open" :aria-expanded="open" aria-controls="welcome-companion-note" aria-label="{{ __('Meet your TraKerja companion') }}">
                <svg viewBox="0 0 120 120" aria-hidden="true" focusable="false">
                    <circle cx="60" cy="60" r="59" fill="#f8e5d4"/>
                    <g stroke="#292b30" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 120V108Q21 89 48 85H72Q99 89 100 108V120" fill="#f2b793"/>
                        <path d="M52 74V86Q60 95 68 86V74" fill="#fff7e8"/>
                        <path d="M44 90Q60 106 76 90" fill="none"/>
                        <path d="M37 48Q29 43 31 54Q32 61 38 60M83 48Q91 43 89 54Q88 61 82 60" fill="#fff7e8"/>
                        <path d="M37 36Q38 19 60 19Q82 19 83 36V56Q82 77 60 82Q38 77 37 56Z" fill="#fff7e8"/>
                        <path d="M35 43Q28 35 36 28Q29 17 42 18Q41 9 53 14Q62 5 69 14Q82 8 86 22Q96 29 86 40L79 44V31Q63 39 42 30L41 45Z" fill="#ff953d"/>
                        <path d="M45 44L52 43M68 43L75 44M59 52L56 62H61M53 69Q60 74 67 69" fill="none"/>
                        <g class="welcome-eyes"><path d="M49 50V54M71 50V54"/></g>
                        <rect x="42" y="46" width="16" height="13" rx="4" fill="none" stroke="#ff8b38"/><rect x="63" y="46" width="16" height="13" rx="4" fill="none" stroke="#ff8b38"/><path d="M58 50H63M36 50H42M79 50H84M33 108V120M87 108V120" fill="none"/>
                    </g>
                </svg>
                <span class="welcome-hello">{{ __('Hey there') }} <span aria-hidden="true">↗</span></span>
            </button>
        </div>
        <a href="{{ route('cv.builder') }}" wire:navigate class="welcome-float welcome-float-cv" aria-label="{{ __('Open CV Builder') }}">
            <svg viewBox="0 0 40 46" aria-hidden="true"><rect x="6" y="2" width="28" height="40" rx="5" fill="white" stroke="currentColor" stroke-width="1.5"/><circle cx="20" cy="13" r="4" fill="#d8c7ef"/><path d="M13 23h14M13 29h10M13 35h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg><span>{{ __('Your CV') }}</span>
        </a>
        <a href="{{ route('interviews') }}" wire:navigate class="welcome-float welcome-float-calendar" aria-label="{{ __('Open interview calendar') }}">
            <svg viewBox="0 0 40 40" aria-hidden="true"><rect x="4" y="7" width="32" height="29" rx="6" fill="white" stroke="currentColor" stroke-width="1.5"/><path d="M4 16h32M13 4v7M27 4v7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="m14 25 4 4 8-8" fill="none" stroke="#db619c" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg><span>{{ __('Next conversation') }}</span>
        </a>
        <a href="{{ url('/explore') }}" wire:navigate class="welcome-float welcome-float-jobs" aria-label="{{ __('Explore job opportunities') }}"><i class="ph ph-briefcase" aria-hidden="true"></i><span>{{ __('Your next move') }}</span><i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>
        <button type="button" class="welcome-motion-toggle" @click="toggleMotion(); $dispatch('dashboard-motion', { paused })" :disabled="reduced" :aria-label="reduced ? @js(__('Motion reduced by your device settings')) : (paused ? @js(__('Resume welcome animations')) : @js(__('Pause welcome animations')))" x-cloak>
            <i class="ph" :class="paused || reduced ? 'ph-play' : 'ph-pause'" aria-hidden="true"></i><span x-text="reduced ? @js(__('Motion reduced')) : (paused ? @js(__('Resume motion')) : @js(__('Pause motion')))"></span>
        </button>
    </div>
    <div id="welcome-companion-note" class="welcome-companion-note" x-show="open" x-cloak x-transition.opacity @click.outside="open = false">
        <div><strong>{{ __('A little help for your next move.') }}</strong><button type="button" @click="close()" aria-label="{{ __('Close companion message') }}"><i class="ph ph-x" aria-hidden="true"></i></button></div>
        @if($upcomingInterviews->isNotEmpty())
            @php($nextConversation = $upcomingInterviews->first())
            <p>{{ __('Your next interview is with') }} <strong>{{ $nextConversation->company_name }}</strong> {{ __('on :value1 WIB.', ['value1' => ($nextConversation->interview_date->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M, H:i'))]) }}</p>
        @else
            <p>{{ __('Working on your next application? You can prepare your CV, cover letter, or interview in AI Studio.') }}</p>
        @endif
        <a href="{{ route('ai-studio.index') }}" wire:navigate>{{ __('Prepare in AI Studio') }} <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>
    </div>
</section>
