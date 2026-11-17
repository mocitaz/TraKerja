@php
    $savedId = $savedJobs[$job->id] ?? null;
    $bookmarked = in_array($job->id, $bookmarkedIds);
    $reported = in_array($job->id, $reportedIds);
    $active = $job->status === 'active';
    $match = $matchScores[$job->id] ?? null;
    $company = (string) $job->company_name;
    $logo = $job->company?->logo_url;
    $chips = array_values(array_filter([
        $job->work_type ? __($job->work_type) : null,
        ['full_time' => __('Full-time'), 'part_time' => __('Part-time'), 'contract' => __('Contract'), 'internship' => __('Internship'), 'freelance' => __('Freelance')][$job->employment_type] ?? null,
        ['intern' => __('Intern'), 'entry' => __('Entry level'), 'mid' => __('Mid level'), 'senior' => __('Senior level'), 'lead' => __('Lead'), 'manager' => __('Manager')][$job->seniority] ?? null,
    ]));
    $chips = array_values(array_unique($chips));
@endphp
<article class="explore-job-card jc explore-enter {{ !$active ? 'is-unavailable' : '' }}" wire:key="explore-job-{{ $job->id }}" aria-labelledby="explore-title-{{ $job->id }}" style="--explore-delay: {{ min($loop->index, 5) * 35 }}ms">
    <div class="jc-head">
        <span class="jc-logo {{ $logo ? 'has-logo' : '' }}" aria-hidden="true">
            <i class="ph ph-buildings jc-initials"></i>
            @if($logo)<img src="{{ $logo }}" alt="" width="48" height="48" loading="lazy" decoding="async" referrerpolicy="no-referrer" onerror="this.parentNode.classList.remove('has-logo'); this.remove()">@endif
        </span>
        <div class="jc-title">
            <h3 id="explore-title-{{ $job->id }}">@if($active)<button type="button" wire:click="previewJob({{ $job->id }})">{{ $job->title }}</button>@else{{ $job->title }}@endif</h3>
            <p class="jc-company">{{ $company ?: __('Company not listed') }}</p>
        </div>
        <button type="button" wire:click="toggleBookmark({{ $job->id }})" wire:loading.attr="disabled" wire:target="toggleBookmark({{ $job->id }})" class="jc-bookmark {{ $bookmarked ? 'is-bookmarked' : '' }}" aria-pressed="{{ $bookmarked ? 'true' : 'false' }}" aria-label="{{ $bookmarked ? __('Remove :title from saved jobs', ['title' => $job->title]) : __('Save :title for later', ['title' => $job->title]) }}" title="{{ $bookmarked ? __('Saved') : __('Save for later') }}" @disabled(!$workspaceReady || (!$active && !$bookmarked))><i class="ph {{ $bookmarked ? 'ph-bookmark-simple-fill' : 'ph-bookmark-simple' }}" aria-hidden="true"></i></button>
    </div>

    @if($match || $chips)
        <div class="jc-chips">
            @if($match)<span class="jc-chip jc-match is-{{ $match['label'] }}" title="{{ __('The score compares your CV with the role description. It is a guide, not a hiring decision.') }}"><i class="ph-fill ph-sparkle" aria-hidden="true"></i>{{ __(':score% match', ['score' => $match['score']]) }}</span>@endif
            @foreach(array_slice($chips, 0, 3) as $chip)<span class="jc-chip">{{ $chip }}</span>@endforeach
        </div>
    @endif

    <dl class="jc-meta">
        <div><dt class="jc-sr">{{ __('Location') }}</dt><dd><i class="ph ph-map-pin" aria-hidden="true"></i><span>{{ $job->location ?: __('Location not listed') }}</span></dd></div>
        @if($job->salary_label)<div><dt class="jc-sr">{{ __('Salary') }}</dt><dd class="jc-salary"><i class="ph ph-money" aria-hidden="true"></i><span>{{ $job->salary_label }}</span></dd></div>@endif
        @if($job->valid_through)<div><dt class="jc-sr">{{ __('Deadline') }}</dt><dd><i class="ph ph-calendar-blank" aria-hidden="true"></i><span>{{ __('Apply by :date', ['date' => $job->valid_through->locale(app()->getLocale())->translatedFormat('d M Y')]) }}</span></dd></div>@endif
    </dl>

    @if(!$active)<p class="jc-note">{{ __('This listing is no longer active. It stays here until you remove it.') }}</p>@endif

    <p class="jc-status" title="{{ count($job->source_names) > 1 ? __('Listed on :platforms', ['platforms' => implode(', ', $job->source_names)]) : $job->source_platform }}">
            <x-platform-brand :name="$job->source_platform" :show-name="false" />
            <span class="jc-status-text">{{ $job->source_platform }}@if(count($job->source_names) > 1) +{{ count($job->source_names) - 1 }}@endif
                @if($active)@if($job->last_validated_at) · <span class="is-open">{{ __('Checked :time ago', ['time' => $job->last_validated_at->locale(app()->getLocale())->diffForHumans(null, \Carbon\CarbonInterface::DIFF_ABSOLUTE, true)]) }}</span>@endif
                @else · <span class="is-closed">{{ __('Job closed') }}</span>@endif</span>
    </p>

    <div class="jc-foot">
        <div class="jc-track">
            @if($savedId)<a href="{{ route('jobs.show', $savedId) }}" wire:navigate class="jc-tracked"><i class="ph ph-check-circle" aria-hidden="true"></i>{{ __('In your tracker') }}</a>@elseif($active)<button type="button" class="jc-link" wire:click="initiateTrackJob({{ $job->id }})" wire:loading.attr="disabled" wire:target="initiateTrackJob({{ $job->id }})">{{ __('Already applied?') }}</button>@endif
        </div>
        <div class="jc-actions">
            @if($active)<button type="button" wire:click="previewJob({{ $job->id }})" class="jc-open" aria-label="{{ __('View details') }}: {{ $job->title }}">{{ __('Details') }}<i class="ph ph-arrow-right" aria-hidden="true"></i></button>@endif
            <details class="jc-more" x-data @click.outside="$el.removeAttribute('open')" @keydown.escape.stop="$el.removeAttribute('open')"><summary aria-label="{{ __('More') }}"><i class="ph ph-dots-three" aria-hidden="true"></i></summary><div class="jc-menu" role="menu">
                <p class="jc-menu-info" title="{{ __('A page check does not confirm that hiring is still open.') }}"><i class="ph ph-clock-counter-clockwise" aria-hidden="true"></i><span>{{ $job->last_validated_at ? __('Checked :date', ['date' => $job->last_validated_at->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M Y · H:i').' WIB']) : __('No HTTP check recorded yet.') }}</span></p>
                @if($job->source_url)<a role="menuitem" href="{{ $job->source_url }}" target="_blank" rel="noopener noreferrer"><i class="ph ph-arrow-square-out" aria-hidden="true"></i>{{ __('Open listing') }}</a>@endif
                @if($reported)<span class="jc-menu-done"><i class="ph ph-check" aria-hidden="true"></i>{{ __('Report submitted') }}</span>@elseif($active)<button role="menuitem" type="button" class="is-danger" wire:click="initiateReportExpired({{ $job->id }})"><i class="ph ph-flag" aria-hidden="true"></i>{{ __('Report listing') }}</button>@endif
            </div></details>
        </div>
    </div>
</article>
