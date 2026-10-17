<div>
    @if($job->isGhosted())
        <div class="job-follow-up-panel"><div><h3>{{ __('Ready to check in?') }}</h3><p>{{ __('It has been at least 14 days since you applied. Draft a polite message to ask about your application.') }}</p></div>@include('tracker.partials.follow-up-button')</div>
    @endif
    @include('tracker.partials.follow-up')
</div>
