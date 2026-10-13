<section wire:poll.visible.30s id="dashboard-next-move" class="dashboard-next-move" x-data="{ motionPaused: false, activeMove: null }" @next-move-updated="activeMove = null" :class="{ 'ai-motion-paused': motionPaused }" @dashboard-motion.window="motionPaused = $event.detail.paused" aria-labelledby="next-move-title">
    <header class="next-move-header">
        <div><h2 id="next-move-title">Your next move</h2><p>A few useful steps for where you are today.</p></div>
        <a href="{{ route('ai-studio.index') }}" wire:navigate class="dashboard-ai-link" aria-label="Prepare with TraKerja AI" title="TraKerja AI"><x-trakerja-ai-mark class="ai-notice-mark" /><span>Prepare with AI</span><i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>
    </header>
    @if($notice)<div class="next-move-feedback" role="status"><i class="ph ph-check-circle" aria-hidden="true"></i><span>{{ $notice }}</span>@if($undoActionId)<button type="button" wire:click="undo" wire:loading.attr="disabled">Undo</button>@endif</div>@endif
    @if($deferredCount)<p class="next-move-deferred">{{ $deferredCount }} {{ Str::plural('action',$deferredCount) }} set aside until tomorrow.</p>@endif
    <div class="next-move-cards">
        <article class="next-move-card next-move-interview {{ $interviewPrepared ? 'is-completed' : '' }}" :class="{ 'mobile-is-expanded': (activeMove || '{{ $primaryMove }}') === 'interview' }" data-mobile-primary="{{ $primaryMove === 'interview' ? 'true' : 'false' }}" wire:key="next-interview-{{ $upcomingInterviews->first()?->id ?? 'none' }}-{{ $interviewPrepared ? 'prepared' : 'open' }}">
            <button type="button" class="next-move-mobile-summary" x-ref="summaryinterview" @click="activeMove = 'interview'; $nextTick(() => $refs.headinginterview.focus())" :aria-expanded="((activeMove || '{{ $primaryMove }}') === 'interview').toString()" aria-controls="next-move-heading-interview"><span><strong>Interview prep</strong><small>{{ $interviewPrepared ? 'Prepared' : ($upcomingInterviews->first() ? $upcomingInterviews->first()->interview_date->timezone('Asia/Jakarta')->format('d M · H:i').' WIB' : 'No upcoming interviews') }}</small></span><i class="ph ph-caret-right" aria-hidden="true"></i></button>
            <button type="button" class="next-move-mobile-collapse" @click="activeMove = null; $nextTick(() => $refs.summaryinterview.focus())" aria-label="Collapse Interview prep"><i class="ph ph-caret-up" aria-hidden="true"></i></button>
            @php($nextInterview = $upcomingInterviews->first())
            <div class="next-move-card-top"><span>Interview prep</span><a href="{{ route('interviews') }}" wire:navigate aria-label="View all interviews">View all <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a></div>
            <h3 id="next-move-heading-interview" x-ref="headinginterview" tabindex="-1">{{ $nextInterview ? ($interviewPrepared ? 'You’re prepared' : 'Prepare for your interview') : 'Make room for your next conversation' }}</h3>
            @if($nextInterview)
                <div class="next-move-content"><div class="next-move-appointment">
                    <time datetime="{{ $nextInterview->interview_date->toIso8601String() }}"><strong>{{ $nextInterview->interview_date->timezone('Asia/Jakarta')->format('d') }}</strong><span>{{ $nextInterview->interview_date->timezone('Asia/Jakarta')->format('M') }}</span></time>
                    <div><strong>{{ $nextInterview->position }}</strong><span>{{ $nextInterview->company_name }}</span><span>{{ $nextInterview->interview_date->timezone('Asia/Jakarta')->format('H:i') }} WIB · {{ $nextInterview->recruitment_stage }}</span></div>
                </div>
                @if($interviewPrepared)<p class="next-move-complete-label"><i class="ph ph-check-circle" aria-hidden="true"></i>Preparation marked complete</p>@else<div class="next-move-task-controls"><button type="button" wire:click="askComplete('interview', {{ $nextInterview->id }})" wire:loading.attr="disabled"><i class="ph ph-check" aria-hidden="true"></i>Mark prepared</button><button type="button" wire:click="snooze('interview', {{ $nextInterview->id }})" wire:loading.attr="disabled">Remind me tomorrow</button></div>@endif
                </div>
                <a class="next-move-action" href="{{ route('jobs.show', $nextInterview) }}" wire:navigate>Review interview <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
                @if($upcomingInterviews->count() > 1)
                    <details class="next-move-more"><summary>{{ $upcomingInterviews->count() - 1 }} more coming up</summary><ul>
                        @foreach($upcomingInterviews->skip(1) as $interview)
                            <li><a href="{{ route('jobs.show', $interview) }}" wire:navigate><span>{{ $interview->position }}<small>{{ $interview->company_name }}</small></span><time datetime="{{ $interview->interview_date->toIso8601String() }}">{{ $interview->interview_date->timezone('Asia/Jakarta')->format('d M · H:i') }} WIB</time></a></li>
                        @endforeach
                    </ul></details>
                @endif
            @else
                <div class="next-move-content"><p class="next-move-description">No upcoming interviews. Add a schedule when a company invites you to meet.</p></div>
                <a class="next-move-action" href="{{ route('interviews') }}" wire:navigate>Open interview calendar <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
            @endif
        </article>
        <article class="next-move-card next-move-followup" :class="{ 'mobile-is-expanded': (activeMove || '{{ $primaryMove }}') === 'followup' }" data-mobile-primary="{{ $primaryMove === 'followup' ? 'true' : 'false' }}" wire:key="next-followup-{{ $nextFollowUp?->id ?? 'none' }}">
            <button type="button" class="next-move-mobile-summary" x-ref="summaryfollowup" @click="activeMove = 'followup'; $nextTick(() => $refs.headingfollowup.focus())" :aria-expanded="((activeMove || '{{ $primaryMove }}') === 'followup').toString()" aria-controls="next-move-heading-followup"><span><strong>Follow up</strong><small>{{ $followUpCount ? $followUpCount.' ready to review' : 'Up to date' }}</small></span><i class="ph ph-caret-right" aria-hidden="true"></i></button>
            <button type="button" class="next-move-mobile-collapse" @click="activeMove = null; $nextTick(() => $refs.summaryfollowup.focus())" aria-label="Collapse Follow up"><i class="ph ph-caret-up" aria-hidden="true"></i></button>
            <div class="next-move-card-top"><span>Keep in touch</span><i class="ph ph-chat-circle-text" aria-hidden="true"></i></div>
            <h3 id="next-move-heading-followup" x-ref="headingfollowup" tabindex="-1">{{ $nextFollowUp ? 'Time to follow up' : 'You’re up to date' }}</h3>
            @if($nextFollowUp)
                <div class="next-move-content"><p class="next-move-subject"><strong>{{ $nextFollowUp->position }}</strong><span>{{ $nextFollowUp->company_name }}</span></p>
                <p class="next-move-description">Applied {{ (int) $nextFollowUp->application_date->diffInDays(today()) }} days ago. Review the status before checking in.</p>
                <div class="next-move-task-controls"><button type="button" wire:click="askComplete('followup', {{ $nextFollowUp->id }})" wire:loading.attr="disabled"><i class="ph ph-check" aria-hidden="true"></i>Log follow-up</button><button type="button" wire:click="snooze('followup', {{ $nextFollowUp->id }})" wire:loading.attr="disabled">Remind me tomorrow</button></div>
                </div>
                <a class="next-move-action" href="{{ route('jobs.show', $nextFollowUp) }}" wire:navigate>Review & follow up <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
                <p class="next-move-footnote">{{ $followUpCount }} {{ $followUpCount === 1 ? 'application ready' : 'applications ready' }} to review · No message sent automatically</p>
            @else
                <div class="next-move-content"><p class="next-move-description">Nothing due for a follow-up. Active applications become eligible 14 days after applying.</p></div>
                <a class="next-move-action" href="{{ route('tracker') }}" wire:navigate>Check your applications <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
            @endif
        </article>
        <article class="next-move-card next-move-cv" :class="{ 'mobile-is-expanded': (activeMove || '{{ $primaryMove }}') === 'cv' }" data-mobile-primary="{{ $primaryMove === 'cv' ? 'true' : 'false' }}" wire:key="next-cv-{{ count(array_filter($cvEssentials)) }}">
            <button type="button" class="next-move-mobile-summary" x-ref="summarycv" @click="activeMove = 'cv'; $nextTick(() => $refs.headingcv.focus())" :aria-expanded="((activeMove || '{{ $primaryMove }}') === 'cv').toString()" aria-controls="next-move-heading-cv"><span><strong>Continue your CV</strong><small>{{ count(array_filter($cvEssentials)) }}/4 essentials added</small></span><i class="ph ph-caret-right" aria-hidden="true"></i></button>
            <button type="button" class="next-move-mobile-collapse" @click="activeMove = null; $nextTick(() => $refs.summarycv.focus())" aria-label="Collapse Continue your CV"><i class="ph ph-caret-up" aria-hidden="true"></i></button>
            @php($essentialsAdded = count(array_filter($cvEssentials)))
            <div class="next-move-card-top"><span>Your career story</span><i class="ph ph-file-text" aria-hidden="true"></i></div>
            <h3 id="next-move-heading-cv" x-ref="headingcv" tabindex="-1">{{ $essentialsAdded === 0 ? 'Start your CV' : ($essentialsAdded === 4 ? 'Your CV essentials are ready' : 'Continue your CV') }}</h3>
            <div class="next-move-content"><p class="next-move-description">{{ $essentialsAdded === 4 ? 'Your essentials are in place. Review your content and tailor it for the next role.' : 'Build the essentials, one section at a time.' }}</p>
            <ul class="next-move-checklist" aria-label="CV essentials">
                @foreach($cvEssentials as $label => $added)
                    <li class="{{ $added ? 'is-added' : '' }}"><i class="ph {{ $added ? 'ph-check-circle' : 'ph-circle' }}" aria-hidden="true"></i><span>{{ $label }}</span><span class="sr-only">{{ $added ? ': added' : ': not yet complete' }}</span></li>
                @endforeach
            </ul>
            @if($essentialsAdded===4)<p class="next-move-complete-label"><i class="ph ph-check-circle" aria-hidden="true"></i>All four essentials saved</p>@endif
            </div>
            <a class="next-move-action" href="{{ route('cv.builder') }}" wire:navigate>{{ $essentialsAdded === 4 ? 'Review your CV' : 'Continue in CV Builder' }} <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
            <p class="next-move-footnote">{{ $essentialsAdded }} of 4 essentials added</p>
        </article>
    </div>
    @if($confirmAction)
    <div x-data class="activity-day-overlay" role="dialog" aria-modal="true" aria-labelledby="next-move-confirm-title" tabindex="-1" x-dialog="$wire.confirmAction" data-dialog-close="$wire.set('confirmAction', null)"><div class="activity-day-backdrop" wire:click="$set('confirmAction', null)"></div><section class="next-move-confirm-dialog"><h2 id="next-move-confirm-title">{{ $confirmAction==='followup' ? 'Already sent your follow-up?' : 'Finished preparing?' }}</h2><p>{{ $confirmAction==='followup' ? 'Record the follow-up you sent yourself. This does not send an email or change the application’s recruitment stage. We’ll set this suggestion aside for 14 days.' : 'Mark preparation complete for this interview schedule. If the schedule changes, we’ll remind you to prepare again.' }}</p><footer><button type="button" wire:click="$set('confirmAction', null)">Cancel</button><button type="button" class="is-primary" wire:click="complete('{{ $confirmAction }}', {{ $confirmJobId }})" wire:loading.attr="disabled">{{ $confirmAction==='followup' ? 'Yes, record follow-up' : 'Mark prepared' }}</button></footer></section></div>
    @endif
</section>
