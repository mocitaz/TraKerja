@php
    $jobStack = array_values(array_filter($job->tech_stack ?? [], 'is_string'));
    $matchedSkills = array_values(array_filter($jobStack, fn ($tech) => in_array(mb_strtolower(trim($tech)), $userSkills, true)));
    $displaySkills = array_values(array_unique(array_merge($matchedSkills, $jobStack)));
    $savedId = $savedJobs[$job->id] ?? null;
    $bookmarked = in_array($job->id, $bookmarkedIds);
    $reported = in_array($job->id, $reportedIds);
    $active = $job->status === 'active';
@endphp
<article class="explore-job-card explore-enter {{ !$active ? 'is-unavailable' : '' }}" wire:key="explore-job-{{ $job->id }}" aria-labelledby="explore-title-{{ $job->id }}" style="--explore-delay: {{ min($loop->index, 5) * 35 }}ms">
    <div class="explore-job-header">
        <div class="explore-job-source"><x-platform-brand :name="$job->source_platform" /><button type="button" wire:click="toggleBookmark({{ $job->id }})" wire:loading.attr="disabled" wire:target="toggleBookmark({{ $job->id }})" class="explore-bookmark {{ $bookmarked ? 'is-bookmarked' : '' }}" aria-pressed="{{ $bookmarked ? 'true' : 'false' }}" aria-label="{{ $bookmarked ? __('Remove :title from saved jobs', ['title' => $job->title]) : __('Save :title for later', ['title' => $job->title]) }}" @disabled(!$workspaceReady || (!$active && !$bookmarked))><i class="ph {{ $bookmarked ? 'ph-bookmark-simple-fill' : 'ph-bookmark-simple' }}" aria-hidden="true"></i><span>{{ $bookmarked ? __('Saved') : __('Save for later') }}</span></button></div>
        <h3 id="explore-title-{{ $job->id }}">@if($active)<button type="button" wire:click="previewJob({{ $job->id }})">{{ $job->title }}</button>@else{{ $job->title }}@endif</h3>
        <p class="explore-job-company">{{ $job->company_name }}</p>
        <p class="explore-job-location"><i class="ph ph-map-pin" aria-hidden="true"></i>{{ $job->location ?: __('Location not listed') }}@if($job->work_type)<span>· {{ $job->work_type }}</span>@endif</p>
    </div>
    <div class="explore-job-body">
        @if(!$active)<p class="explore-unavailable-note">{{ __('This listing is no longer active. It stays here until you remove it.') }}</p>@else
            <p class="explore-job-description">{{ \Illuminate\Support\Str::limit($job->plain_description, 180) ?: __('Read the source listing for the role description and requirements.') }}</p>
            @if($displaySkills)<div class="explore-job-skills">@foreach(array_slice($displaySkills, 0, 4) as $tech)<span class="{{ in_array(mb_strtolower(trim($tech)), $userSkills, true) ? 'is-matched' : '' }}">{{ $tech }}</span>@endforeach @if(count($displaySkills) > 4)<span>+{{ count($displaySkills) - 4 }}</span>@endif</div>@endif
            @if($jobStack && $userSkills)<p class="explore-skill-note">{{ __(':value1/:value2 listed skills appear in your CV.', ['value1' => count($matchedSkills), 'value2' => count($jobStack)]) }}</p>@endif
        @endif
    </div>
    <div class="explore-job-actions">@if($active)<button type="button" wire:click="previewJob({{ $job->id }})" class="explore-open-button">{{ __('View details') }} <i class="ph ph-arrow-right" aria-hidden="true"></i></button>@else<span class="explore-source-unavailable">{{ __('Listing unavailable') }}</span>@endif</div>
    <div class="explore-job-bottom">
        @if($savedId)<a href="{{ route('jobs.show', $savedId) }}" wire:navigate class="explore-applied-link"><i class="ph ph-check" aria-hidden="true"></i>{{ __('In your tracker') }}</a>@elseif($active)<button type="button" wire:click="initiateTrackJob({{ $job->id }})" wire:loading.attr="disabled" wire:target="initiateTrackJob({{ $job->id }})">{{ __('Already applied?') }}</button>@else<span>{{ __('No longer active') }}</span>@endif
        <details class="explore-card-more"><summary>{{ __('More') }} <i class="ph ph-caret-down" aria-hidden="true"></i></summary><div><p>{{ $job->last_validated_at ? __('Last HTTP check: ').$job->last_validated_at->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M Y · H:i').__(' WIB') : __('No HTTP check recorded yet.') }}</p><p>{{ __('A page check does not confirm that hiring is still open.') }}</p>@if($job->source_url)<a href="{{ $job->source_url }}" target="_blank" rel="noopener noreferrer">{{ __('Open listing') }} ↗</a>@endif @if($reported)<span>{{ __('Report submitted') }}</span>@elseif($active)<button type="button" wire:click="initiateReportExpired({{ $job->id }})">{{ __('Report listing') }}</button>@endif</div></details>
    </div>
</article>
