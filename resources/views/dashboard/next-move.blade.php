<section wire:poll.visible.30s id="dashboard-next-move" class="dashboard-next-move" x-data="{ motionPaused: false, activeMove: null }" @next-move-updated="activeMove = null" :class="{ 'ai-motion-paused': motionPaused }" @dashboard-motion.window="motionPaused = $event.detail.paused" aria-labelledby="next-move-title">
    <header class="next-move-header">
        <div><h2 id="next-move-title">{{ __('Your next move') }}</h2><p>{{ __('A few useful steps for where you are today.') }}</p></div>
        <a href="{{ route('ai-studio.index') }}" wire:navigate class="dashboard-ai-link" aria-label="{{ __('Prepare with TraKerja AI') }}" title="{{ __('TraKerja AI') }}"><x-trakerja-ai-mark class="ai-notice-mark" /><span>{{ __('Prepare with AI') }}</span><i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>
    </header>
    @if($notice)<div class="next-move-feedback" role="status"><i class="ph ph-check-circle" aria-hidden="true"></i><span>{{ $notice }}</span>@if($undoActionId)<button type="button" wire:click="undo" wire:loading.attr="disabled">{{ __('Undo') }}</button>@endif</div>@endif
    @if($deferredCount)<p class="next-move-deferred">{{ __(':value1 :value2 set aside until tomorrow.', ['value1' => ($deferredCount), 'value2' => (Str::plural('action',$deferredCount))]) }}</p>@endif
    <div class="next-move-cards">
        <article class="next-move-card next-move-interview {{ $interviewPrepared ? 'is-completed' : '' }}" :class="{ 'mobile-is-expanded': (activeMove || '{{ $primaryMove }}') === 'interview' }" data-mobile-primary="{{ $primaryMove === 'interview' ? 'true' : 'false' }}" wire:key="next-interview-{{ $upcomingInterviews->first()?->id ?? 'none' }}-{{ $interviewPrepared ? 'prepared' : 'open' }}">
            <button type="button" class="next-move-mobile-summary" x-ref="summaryinterview" @click="activeMove = 'interview'; $nextTick(() => $refs.headinginterview.focus())" :aria-expanded="((activeMove || '{{ $primaryMove }}') === 'interview').toString()" aria-controls="next-move-heading-interview"><span><strong>{{ __('Interview prep') }}</strong><small>{{ $interviewPrepared ? __('Prepared') : ($upcomingInterviews->first() ? $upcomingInterviews->first()->interview_date->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M · H:i').__(' WIB') : __('No upcoming interviews')) }}</small></span><i class="ph ph-caret-right" aria-hidden="true"></i></button>
            <button type="button" class="next-move-mobile-collapse" @click="activeMove = null; $nextTick(() => $refs.summaryinterview.focus())" aria-label="{{ __('Collapse Interview prep') }}"><i class="ph ph-caret-up" aria-hidden="true"></i></button>
            @php($nextInterview = $upcomingInterviews->first())
            <div class="next-move-card-top"><span>{{ __('Interview prep') }}</span><a href="{{ route('interviews') }}" wire:navigate aria-label="{{ __('View all interviews') }}">{{ __('View all') }} <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a></div>
            <h3 id="next-move-heading-interview" x-ref="headinginterview" tabindex="-1">{{ $nextInterview ? ($interviewPrepared ? __('You’re prepared') : __('Prepare for your interview')) : __('Make room for your next conversation') }}</h3>
            @if($nextInterview)
                <div class="next-move-content"><div class="next-move-appointment">
                    <time datetime="{{ $nextInterview->interview_date->toIso8601String() }}"><strong>{{ $nextInterview->interview_date->timezone('Asia/Jakarta')->format('d') }}</strong><span>{{ $nextInterview->interview_date->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('M') }}</span></time>
                    <div><strong>{{ $nextInterview->position }}</strong><span>{{ $nextInterview->company_name }}</span><span>{{ __(':value1 WIB · :value2', ['value1' => ($nextInterview->interview_date->timezone('Asia/Jakarta')->format('H:i')), 'value2' => ($nextInterview->recruitment_stage)]) }}</span></div>
                </div>
                @if($interviewPrepared)<p class="next-move-complete-label"><i class="ph ph-check-circle" aria-hidden="true"></i>{{ __('Preparation marked complete') }}</p>@else<div class="next-move-task-controls"><button type="button" wire:click="askComplete('interview', {{ $nextInterview->id }})" wire:loading.attr="disabled"><i class="ph ph-check" aria-hidden="true"></i>{{ __('Mark prepared') }}</button><button type="button" wire:click="snooze('interview', {{ $nextInterview->id }})" wire:loading.attr="disabled">{{ __('Remind me tomorrow') }}</button></div>@endif
                </div>
                <a class="next-move-action" href="{{ route('jobs.show', $nextInterview) }}" wire:navigate>{{ __('Review interview') }} <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
                @if($upcomingInterviews->count() > 1)
                    <details class="next-move-more"><summary>{{ __(':value1 more coming up', ['value1' => ($upcomingInterviews->count() - 1)]) }}</summary><ul>
                        @foreach($upcomingInterviews->skip(1) as $interview)
                            <li><a href="{{ route('jobs.show', $interview) }}" wire:navigate><span>{{ $interview->position }}<small>{{ $interview->company_name }}</small></span><time datetime="{{ $interview->interview_date->toIso8601String() }}">{{ __(':value1 WIB', ['value1' => ($interview->interview_date->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M · H:i'))]) }}</time></a></li>
                        @endforeach
                    </ul></details>
                @endif
            @else
                <div class="next-move-content"><p class="next-move-description">{{ __('No upcoming interviews. Add a schedule when a company invites you to meet.') }}</p></div>
                <a class="next-move-action" href="{{ route('interviews') }}" wire:navigate>{{ __('Open interview calendar') }} <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
            @endif
        </article>
        <article class="next-move-card next-move-followup" :class="{ 'mobile-is-expanded': (activeMove || '{{ $primaryMove }}') === 'followup' }" data-mobile-primary="{{ $primaryMove === 'followup' ? 'true' : 'false' }}" wire:key="next-followup-{{ $nextFollowUp?->id ?? 'none' }}">
            <button type="button" class="next-move-mobile-summary" x-ref="summaryfollowup" @click="activeMove = 'followup'; $nextTick(() => $refs.headingfollowup.focus())" :aria-expanded="((activeMove || '{{ $primaryMove }}') === 'followup').toString()" aria-controls="next-move-heading-followup"><span><strong>{{ __('Follow up') }}</strong><small>{{ $followUpCount ? $followUpCount.__(' ready to review') : __('Up to date') }}</small></span><i class="ph ph-caret-right" aria-hidden="true"></i></button>
            <button type="button" class="next-move-mobile-collapse" @click="activeMove = null; $nextTick(() => $refs.summaryfollowup.focus())" aria-label="{{ __('Collapse Follow up') }}"><i class="ph ph-caret-up" aria-hidden="true"></i></button>
            <div class="next-move-card-top"><span>{{ __('Keep in touch') }}</span><i class="ph ph-chat-circle-text" aria-hidden="true"></i></div>
            <h3 id="next-move-heading-followup" x-ref="headingfollowup" tabindex="-1">{{ $nextFollowUp ? __('Time to follow up') : __('You’re up to date') }}</h3>
            @if($nextFollowUp)
                <div class="next-move-content"><p class="next-move-subject"><strong>{{ $nextFollowUp->position }}</strong><span>{{ $nextFollowUp->company_name }}</span></p>
                <p class="next-move-description">{{ __('Applied :value1 days ago. Review the status before checking in.', ['value1' => ((int) $nextFollowUp->application_date->diffInDays(today()))]) }}</p>
                <div class="next-move-task-controls"><button type="button" wire:click="askComplete('followup', {{ $nextFollowUp->id }})" wire:loading.attr="disabled"><i class="ph ph-check" aria-hidden="true"></i>{{ __('Log follow-up') }}</button><button type="button" wire:click="snooze('followup', {{ $nextFollowUp->id }})" wire:loading.attr="disabled">{{ __('Remind me tomorrow') }}</button></div>
                </div>
                <a class="next-move-action" href="{{ route('jobs.show', $nextFollowUp) }}" wire:navigate>{{ __('Review & follow up') }} <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
                <p class="next-move-footnote">{{ __(':value1 :value2 to review · No message sent automatically', ['value1' => ($followUpCount), 'value2' => ($followUpCount === 1 ? 'application ready' : 'applications ready')]) }}</p>
            @else
                <div class="next-move-content"><p class="next-move-description">{{ __('Nothing due for a follow-up. Active applications become eligible 14 days after applying.') }}</p></div>
                <a class="next-move-action" href="{{ route('tracker') }}" wire:navigate>{{ __('Check your applications') }} <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
            @endif
        </article>
        <article class="next-move-card next-move-cv" :class="{ 'mobile-is-expanded': (activeMove || '{{ $primaryMove }}') === 'cv' }" data-mobile-primary="{{ $primaryMove === 'cv' ? 'true' : 'false' }}" wire:key="next-cv-{{ count(array_filter($cvEssentials)) }}">
            <button type="button" class="next-move-mobile-summary" x-ref="summarycv" @click="activeMove = 'cv'; $nextTick(() => $refs.headingcv.focus())" :aria-expanded="((activeMove || '{{ $primaryMove }}') === 'cv').toString()" aria-controls="next-move-heading-cv"><span><strong>{{ __('Continue your CV') }}</strong><small>{{ __(':value1/4 essentials added', ['value1' => (count(array_filter($cvEssentials)))]) }}</small></span><i class="ph ph-caret-right" aria-hidden="true"></i></button>
            <button type="button" class="next-move-mobile-collapse" @click="activeMove = null; $nextTick(() => $refs.summarycv.focus())" aria-label="{{ __('Collapse Continue your CV') }}"><i class="ph ph-caret-up" aria-hidden="true"></i></button>
            @php($essentialsAdded = count(array_filter($cvEssentials)))
            <div class="next-move-card-top"><span>{{ __('Your career story') }}</span><i class="ph ph-file-text" aria-hidden="true"></i></div>
            <h3 id="next-move-heading-cv" x-ref="headingcv" tabindex="-1">{{ $essentialsAdded === 0 ? __('Start your CV') : ($essentialsAdded === 4 ? __('Your CV essentials are ready') : __('Continue your CV')) }}</h3>
            <div class="next-move-content"><p class="next-move-description">{{ $essentialsAdded === 4 ? __('Your essentials are in place. Review your content and tailor it for the next role.') : __('Build the essentials, one section at a time.') }}</p>
            <ul class="next-move-checklist" aria-label="{{ __('CV essentials') }}">
                @foreach($cvEssentials as $label => $added)
                    <li class="{{ $added ? 'is-added' : '' }}"><i class="ph {{ $added ? 'ph-check-circle' : 'ph-circle' }}" aria-hidden="true"></i><span>{{ __($label) }}</span><span class="sr-only">{{ $added ? __(': added') : __(': not yet complete') }}</span></li>
                @endforeach
            </ul>
            @if($essentialsAdded===4)<p class="next-move-complete-label"><i class="ph ph-check-circle" aria-hidden="true"></i>{{ __('All four essentials saved') }}</p>@endif
            </div>
            <a class="next-move-action" href="{{ route('cv.builder') }}" wire:navigate>{{ $essentialsAdded === 4 ? __('Review your CV') : __('Continue in CV Builder') }} <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
            <p class="next-move-footnote">{{ __(':value1 of 4 essentials added', ['value1' => ($essentialsAdded)]) }}</p>
        </article>
    </div>
    @if($confirmAction)
    <div x-data class="activity-day-overlay" role="dialog" aria-modal="true" aria-labelledby="next-move-confirm-title" tabindex="-1" x-dialog="$wire.confirmAction" data-dialog-close="$wire.set('confirmAction', null)"><div class="activity-day-backdrop" wire:click="$set('confirmAction', null)"></div><section class="next-move-confirm-dialog"><h2 id="next-move-confirm-title">{{ $confirmAction==='followup' ? __('Already sent your follow-up?') : __('Finished preparing?') }}</h2><p>{{ $confirmAction==='followup' ? __('Record the follow-up you sent yourself. This does not send an email or change the application’s recruitment stage. We’ll set this suggestion aside for 14 days.') : __('Mark preparation complete for this interview schedule. If the schedule changes, we’ll remind you to prepare again.') }}</p><footer><button type="button" wire:click="$set('confirmAction', null)">{{ __('Cancel') }}</button><button type="button" class="is-primary" wire:click="complete('{{ $confirmAction }}', {{ $confirmJobId }})" wire:loading.attr="disabled">{{ $confirmAction==='followup' ? __('Yes, record follow-up') : __('Mark prepared') }}</button></footer></section></div>
    @endif
</section>
