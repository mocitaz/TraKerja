@php
    $jobStack = array_values(array_filter($job->tech_stack ?? [], 'is_string'));
    $matchedSkills = array_filter($jobStack, fn ($tech) => in_array(mb_strtolower(trim($tech)), $userSkills, true));
    $savedId = $savedJobs[$job->id] ?? null;
    $reported = in_array($job->id, $reportedIds);
    $tone = ['blue', 'lavender', 'peach'][$loop->index % 3];
@endphp
<article class="explore-job-card explore-enter" wire:key="explore-job-{{ $job->id }}" aria-labelledby="explore-title-{{ $job->id }}" style="--explore-delay: {{ min($loop->index, 5) * 35 }}ms">
    <div class="explore-job-header explore-job-header--{{ $tone }}">
        <div class="explore-job-source"><x-platform-brand :name="$job->source_platform" /><span>{{ $job->work_type ?: __('Arrangement not listed') }}</span></div>
        <h3 id="explore-title-{{ $job->id }}"><button type="button" wire:click="previewJob({{ $job->id }})">{{ $job->title }}</button></h3><p class="explore-job-company">{{ $job->company_name }}</p>
        <p class="explore-job-location"><i class="ph ph-map-pin" aria-hidden="true"></i>{{ $job->location ?: __('Location not listed') }}</p>
    </div>
    <div class="explore-job-body">
        @if($job->category_field || $job->category_major)<div class="explore-job-categories">@if($job->category_field)<span>{{ str_replace('Sektor ', '', $job->category_field) }}</span>@endif @if($job->category_major)<span>{{ $job->category_major }}</span>@endif</div>@endif
        <p class="explore-job-description">{{ \Illuminate\Support\Str::limit($job->plain_description, 180) ?: __('Read the source listing for the role description and requirements.') }}</p>
        @if($jobStack)<div class="explore-job-skills">@foreach(array_slice($jobStack, 0, 4) as $tech)<span class="{{ in_array(mb_strtolower(trim($tech)), $userSkills, true) ? 'is-matched' : '' }}">{{ $tech }}</span>@endforeach @if(count($jobStack) > 4)<span>+{{ count($jobStack) - 4 }}</span>@endif</div>@endif
        @if($jobStack && $userSkills)<p class="explore-skill-note">{{ __(':value1/:value2 listed skills appear in your CV.', ['value1' => (count($matchedSkills)), 'value2' => (count($jobStack))]) }}</p>@endif
        <details class="explore-source-details"><summary>{{ __('Source & page check') }} <i class="ph ph-caret-down" aria-hidden="true"></i></summary><div><p><strong>{{ __('Source:') }}</strong> {{ $job->scraperSource?->name ?: $job->source_platform }}</p><p>{{ $job->last_validated_at ? __('Last HTTP check: ').$job->last_validated_at->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M Y · H:i').__(' WIB') : __('No HTTP check recorded yet.') }}</p><p>{{ __('Page availability is not a guarantee that applications are still open.') }}</p><p>{{ __('Collected :value1', ['value1' => ($job->created_at->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M Y'))]) }}</p></div></details>
    </div>
    <div class="explore-job-actions">
        @if($savedId)<a href="{{ route('jobs.show', $savedId) }}" wire:navigate class="explore-save-button is-saved"><i class="ph ph-check" aria-hidden="true"></i>{{ __('In your tracker') }}</a>@else<button type="button" wire:click="initiateTrackJob({{ $job->id }})" wire:loading.attr="disabled" wire:target="initiateTrackJob({{ $job->id }})" class="explore-save-button" aria-label="{{ __('Add :value1 at :value2 to tracker', ['value1' => ($job->title), 'value2' => ($job->company_name)]) }}"><i class="ph ph-bookmark-simple" aria-hidden="true"></i>{{ __('Add to tracker') }}</button>@endif
        @if($job->source_url)<a href="{{ $job->source_url }}" target="_blank" rel="noopener noreferrer" class="explore-open-button" aria-label="{{ __('Open source for :value1 at :value2', ['value1' => ($job->title), 'value2' => ($job->company_name)]) }}">{{ __('Open source') }} <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>@else<span class="explore-source-unavailable">{{ __('Source link unavailable') }}</span>@endif
    </div>
    <div class="explore-job-bottom"><button type="button" wire:click="previewJob({{ $job->id }})" aria-label="{{ __('View details for :value1 at :value2', ['value1' => ($job->title), 'value2' => ($job->company_name)]) }}">{{ __('View details') }} <i class="ph ph-arrow-right" aria-hidden="true"></i></button>@if($reported)<span>{{ __('Report submitted') }}</span>@else<button type="button" wire:click="initiateReportExpired({{ $job->id }})" class="explore-report-button" aria-label="{{ __('Report closed listing :value1 at :value2', ['value1' => ($job->title), 'value2' => ($job->company_name)]) }}">{{ __('Report listing') }}</button>@endif</div>
    @foreach(['track_success_', 'track_info_', 'report_success_'] as $flashKey)@if(session()->has($flashKey.$job->id))<p class="explore-card-feedback" role="status">{{ session($flashKey.$job->id) }}</p>@endif @endforeach
</article>
