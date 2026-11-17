@php
    $job = $previewJob;
    $active = $job->status === 'active';
    $match = $matchScores[$job->id] ?? null;
    $logo = $job->company?->logo_url;
    $bookmarked = in_array($job->id, $bookmarkedIds);
    $types = ['full_time' => __('Full-time'), 'part_time' => __('Part-time'), 'contract' => __('Contract'), 'internship' => __('Internship'), 'freelance' => __('Freelance')];
    $levels = ['intern' => __('Intern'), 'entry' => __('Entry level'), 'mid' => __('Mid level'), 'senior' => __('Senior level'), 'lead' => __('Lead'), 'manager' => __('Manager')];
    $education = ['sd' => 'SD', 'smp' => 'SMP', 'sma_smk' => 'SMA/SMK', 'd3' => 'D3', 's1' => 'S1', 's2' => 'S2', 'any' => __('Any level')];
    $date = fn ($d) => $d?->locale(app()->getLocale())->translatedFormat('d M Y');
    $facts = array_filter([
        ['ph-map-pin', __('Location'), $job->location],
        ['ph-briefcase', __('Work arrangement'), $job->work_type ? __($job->work_type) : null],
        ['ph-clock', __('Employment type'), $types[$job->employment_type] ?? null],
        ['ph-stairs', __('Level'), $levels[$job->seniority] ?? null],
        ['ph-money', __('Salary'), $job->salary_label],
        ['ph-graduation-cap', __('Minimum education'), $education[$job->education_min] ?? null],
        ['ph-calendar-plus', __('Posted'), $date($job->date_posted ?? $job->first_seen_at)],
        ['ph-calendar-x', __('Apply by'), $date($job->valid_through)],
    ], fn ($f) => filled($f[2]));
    $sources = $job->listings->filter(fn ($l) => preg_match('#^https?://#i', (string) $l->url))->unique('job_source_id')->values();
    $skills = array_values(array_filter($job->tech_stack ?? [], 'is_string'));
    $matched = array_map('mb_strtolower', $match['matched'] ?? []);
@endphp
<div class="jd-backdrop" x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.previewJobId" data-dialog-close="$wire.closePreview()" aria-labelledby="explore-preview-title" wire:key="explore-preview-{{ $job->id }}" @click.self="$wire.closePreview()">
    <aside class="jd-panel">
        <header class="jd-top">
            <span class="jd-top-label">{{ __('Job details') }}</span>
            <div class="jd-top-actions">
                <button type="button" wire:click="toggleBookmark({{ $job->id }})" wire:loading.attr="disabled" wire:target="toggleBookmark({{ $job->id }})" class="jd-icon {{ $bookmarked ? 'is-on' : '' }}" aria-pressed="{{ $bookmarked ? 'true' : 'false' }}" aria-label="{{ $bookmarked ? __('Remove from saved') : __('Save for later') }}" title="{{ $bookmarked ? __('Remove from saved') : __('Save for later') }}" @disabled(!$workspaceReady)><i class="ph {{ $bookmarked ? 'ph-bookmark-simple-fill' : 'ph-bookmark-simple' }}" aria-hidden="true"></i></button>
                <button type="button" wire:click="closePreview" class="jd-icon" aria-label="{{ __('Close role details') }}" title="{{ __('Close role details') }}"><i class="ph ph-x" aria-hidden="true"></i></button>
            </div>
        </header>

        <div class="jd-body">
            <section class="jd-hero">
                <span class="jd-logo jc-logo {{ $logo ? 'has-logo' : '' }}" aria-hidden="true"><i class="ph ph-buildings jc-initials"></i>@if($logo)<img src="{{ $logo }}" alt="" width="56" height="56" referrerpolicy="no-referrer" onerror="this.parentNode.classList.remove('has-logo'); this.remove()">@endif</span>
                <div class="jd-hero-text">
                    <h2 id="explore-preview-title">{{ $job->title }}</h2>
                    <p class="jd-company">@if($job->company?->slug)<a href="{{ route('perusahaan.show', $job->company->slug) }}" target="_blank" rel="noopener">{{ $job->company_name }}</a>@else{{ $job->company_name }}@endif</p>
                    <p class="jd-status">
                        @if($active)<span class="is-open"><i class="ph-fill ph-circle" aria-hidden="true"></i>{{ __('Still open') }}</span>@if($job->last_validated_at)<span>{{ __('checked :time', ['time' => $job->last_validated_at->locale(app()->getLocale())->diffForHumans()]) }}</span>@endif
                        @else<span class="is-closed">{{ __('Job closed') }}</span>@endif
                        <span>{{ __('via :platform', ['platform' => $job->source_platform]) }}</span>
                    </p>
                </div>
            </section>

            @if($facts)
                <dl class="jd-facts">
                    @foreach($facts as [$icon, $label, $value])
                        <div><dt><i class="ph {{ $icon }}" aria-hidden="true"></i>{{ $label }}</dt><dd @class(['is-salary' => $label === __('Salary')])>{{ $value }}</dd></div>
                    @endforeach
                </dl>
            @endif

            @if($match)
                <section class="jd-section jd-match is-{{ $match['label'] }}">
                    <div class="jd-match-head"><h3><i class="ph-fill ph-sparkle" aria-hidden="true"></i>{{ __(':score% match with your CV', ['score' => $match['score']]) }}</h3></div>
                    <div class="jd-meter" role="img" aria-label="{{ __(':score% match', ['score' => $match['score']]) }}"><span style="width: {{ max(2, $match['score']) }}%"></span></div>
                    @if($match['matched'])<p><strong>{{ __('In your CV') }}</strong> {{ implode(', ', $match['matched']) }}</p>@endif
                    @if($match['missing'])<p><strong>{{ __('Not in your CV yet') }}</strong> {{ implode(', ', $match['missing']) }}</p>@endif
                    @if($match['level_gap'])<p>{{ __('This role usually asks for more years of experience than your CV shows.') }}</p>@endif
                    <small>{{ __('The score compares your CV with the role description. It is a guide, not a hiring decision.') }}</small>
                </section>
            @endif

            <section class="jd-section">
                <h3>{{ __('About this role') }}</h3>
                <p class="jd-description">{{ $job->plain_description ?: __('No description was collected. Open the source for requirements and application instructions.') }}</p>
            </section>

            @if($skills)
                <section class="jd-section">
                    <h3>{{ __('Skills') }}</h3>
                    <div class="jd-skills">@foreach($skills as $skill)<span @class(['is-matched' => in_array(mb_strtolower($skill), $matched, true)])>@if(in_array(mb_strtolower($skill), $matched, true))<i class="ph ph-check" aria-hidden="true"></i>@endif{{ $skill }}</span>@endforeach</div>
                </section>
            @endif

            @if($sources->isNotEmpty())
                <section class="jd-section">
                    <h3>{{ __('Listed on') }}</h3>
                    <ul class="jd-sources">
                        @foreach($sources as $listing)
                            <li><a href="{{ $listing->url }}" target="_blank" rel="noopener noreferrer"><x-platform-brand :name="$listing->source?->name" :show-name="false" /><span class="jd-source-name">{{ $listing->source?->name }}</span><i class="ph ph-arrow-up-right" aria-hidden="true"></i></a></li>
                        @endforeach
                    </ul>
                </section>
            @endif

            <p class="jd-note"><i class="ph ph-info" aria-hidden="true"></i>{{ __('This is collected source content. Check the original listing for the latest requirements, availability, and application instructions.') }}</p>
        </div>

        <footer class="jd-foot">
            @if($previewApplicationId)<a href="{{ route('jobs.show', $previewApplicationId) }}" wire:navigate class="jd-secondary"><i class="ph ph-check-circle" aria-hidden="true"></i>{{ __('In your tracker') }}</a>@else<button type="button" @click="window.TraKerjaDialogs.close($el.closest('[role=dialog]')); $wire.initiateTrackJob({{ $job->id }})" class="jd-secondary">{{ __('Already applied?') }}</button>@endif
            @if($job->source_url && $active)<a href="{{ $job->source_url }}" target="_blank" rel="noopener noreferrer" class="jd-primary">{{ __('Apply on :platform', ['platform' => $job->source_platform]) }}<i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>@endif
        </footer>
    </aside>
</div>
