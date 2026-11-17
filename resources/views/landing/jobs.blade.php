@php
    // Live figures from the job engine, cached briefly; the section hides itself when there is no data yet.
    $jobsBoard = rescue(fn () => \Illuminate\Support\Facades\Cache::remember('landing.jobs-board', 600, function () {
        $visible = \App\Models\Vacancy::visible();

        return [
            'open' => (clone $visible)->count(),
            'companies' => (clone $visible)->distinct('company_id')->count('company_id'),
            'sources' => \App\Models\JobSource::where('is_active', true)->whereHas('listings', fn ($q) => $q->where('status', 'active'))->count(),
            'checked' => \App\Models\Vacancy::visible()->max('last_verified_at'),
            'latest' => \App\Models\Vacancy::visible()->with(['company', 'region', 'primaryListing.source', 'listings.source'])
                ->whereNotNull('summary')->orderByRaw('COALESCE(date_posted, first_seen_at) desc')->limit(6)->get(),
        ];
    }), null, false);
@endphp
@if($jobsBoard && $jobsBoard['open'] > 0)
<section class="lp-jobs lp-section lp-wrap" id="jobs" aria-labelledby="jobs-title" data-reveal>
    <div class="lp-section-top">
        <div>
            <p class="lp-eyebrow">{{ __('landing.jobs.eyebrow') }}</p>
            <h2 class="lp-heading" id="jobs-title">@foreach(explode('\n', __('landing.jobs.title')) as $line)<span>{{ $line }}</span>@endforeach</h2>
        </div>
        <p class="lp-section-intro">{{ __('landing.jobs.intro') }}</p>
    </div>
    <dl class="lp-jobs-stats">
        <div><dt>{{ __('landing.jobs.open') }}</dt><dd>{{ number_format($jobsBoard['open'], 0, ',', '.') }}</dd></div>
        <div><dt>{{ __('landing.jobs.companies') }}</dt><dd>{{ number_format($jobsBoard['companies'], 0, ',', '.') }}</dd></div>
        <div><dt>{{ __('landing.jobs.sources') }}</dt><dd>{{ $jobsBoard['sources'] }}</dd></div>
        <div><dt>{{ __('landing.jobs.checked') }}</dt><dd>{{ $jobsBoard['checked'] ? \Illuminate\Support\Carbon::parse($jobsBoard['checked'])->locale(app()->getLocale())->diffForHumans() : '–' }}</dd></div>
    </dl>
    <ul class="lp-jobs-grid">
        @foreach($jobsBoard['latest'] as $job)
            <li><a href="{{ parse_url($job->public_path, PHP_URL_PATH) }}" class="lp-job-card">
                <span class="lp-job-company">{{ $job->company?->name }}</span>
                <strong>{{ $job->title }}</strong>
                <span class="lp-job-meta">{{ $job->location ?: ($job->work_mode === 'remote' ? 'Remote' : __('landing.jobs.no_location')) }}@if($job->salary_label) · {{ $job->salary_label }}@endif</span>
                <span class="lp-job-foot"><span class="lp-job-open">{{ __('landing.jobs.still_open') }}</span><span>{{ implode(', ', $job->source_names) }}</span></span>
            </a></li>
        @endforeach
    </ul>
    <div class="lp-jobs-actions">
        <a href="{{ auth()->check() ? route('jobs.explore') : route('loker.index') }}" class="lp-button lp-button--dark">{{ __('landing.jobs.action') }}<span>@include('landing.icon', ['name' => 'arrow'])</span></a>
        <p>{{ __('landing.jobs.note') }}</p>
    </div>
</section>
@endif
