<div class="tracker-list">
    @include('tracker.partials.filters', ['mode' => 'table'])
    <section class="tracker-list-panel" aria-label="Application list">
        <header class="tracker-results"><div><h2>{{ $showArchived ? 'Archived applications' : 'Your applications' }} <span>{{ $jobApplications->total() }}</span></h2><p>Keep your progress up to date, one opportunity at a time.</p></div><select wire:model.live="perPage" aria-label="Applications per page">@foreach([10, 30, 50, 100] as $size)<option value="{{ $size }}">{{ $size }} per page</option>@endforeach</select></header>
        <div wire:loading.delay class="tracker-loading" role="status">Updating your applications…</div>
        <div class="tracker-desktop-table" role="region" aria-label="Applications table" tabindex="0">
            <table>
                <caption class="sr-only">Your applications with separate status, recruitment stage, application date and interview schedule.</caption>
                <colgroup><col class="tracker-col-opportunity"><col class="tracker-col-source"><col class="tracker-col-status"><col class="tracker-col-stage"><col class="tracker-col-applied"><col class="tracker-col-interview"><col class="tracker-col-actions"></colgroup>
                <thead><tr>
                    <th scope="col" aria-sort="{{ $sortField === 'position' ? ($sortDirection === 'asc' ? 'ascending' : 'descending') : 'none' }}"><button type="button" wire:click="sortBy('position')">Opportunity <i class="ph {{ $sortField === 'position' ? ($sortDirection === 'asc' ? 'ph-arrow-up' : 'ph-arrow-down') : 'ph-arrows-down-up' }}" aria-hidden="true"></i></button></th>
                    <th scope="col" class="tracker-col-mid-center">Source & type</th>
                    <th scope="col" class="tracker-col-mid-center">Status</th>
                    <th scope="col" class="tracker-col-mid-center">Stage</th>
                    <th scope="col" class="tracker-col-mid-center" aria-sort="{{ $sortField === 'application_date' ? ($sortDirection === 'asc' ? 'ascending' : 'descending') : 'none' }}"><button type="button" wire:click="sortBy('application_date')">Applied <i class="ph {{ $sortField === 'application_date' ? ($sortDirection === 'asc' ? 'ph-arrow-up' : 'ph-arrow-down') : 'ph-arrows-down-up' }}" aria-hidden="true"></i></button></th>
                    <th scope="col" class="tracker-col-mid-center">Interview</th>
                    <th scope="col" class="tracker-col-mid-center tracker-actions-heading">Actions</th>
                </tr></thead>
                <tbody>@forelse($jobApplications as $job)
                    @php($tone = match($job->application_status) { 'Accepted' => 'accepted', 'Rejected' => 'rejected', default => 'process' })
                    <tr wire:key="table-row-{{ $job->id }}"><td><div class="tracker-opportunity"><span class="tracker-company-mark">{{ mb_substr($job->company_name, 0, 1) }}</span><div><a href="{{ route('jobs.show', $job) }}" wire:navigate>{{ $job->position }}</a><p>{{ $job->company_name }}@if($job->is_pinned)<i class="ph-fill ph-push-pin tracker-pinned-mark" aria-label="Pinned"></i>@endif</p><span>{{ $job->location }}</span></div></div></td><td class="tracker-col-mid-center"><x-platform-brand :name="$job->platform" class="platform-brand--table" /><span class="tracker-secondary">{{ $job->career_level ?: '—' }}</span></td><td class="tracker-col-mid-center"><span class="tracker-pill tracker-pill--{{ $tone }}">{{ $job->application_status }}</span></td><td class="tracker-col-mid-center"><span class="tracker-stage-name">{{ $job->recruitment_stage ?: 'Applied' }}</span>@if($job->isGhosted())@include('tracker.partials.follow-up-button')@endif</td><td class="tracker-col-mid-center"><time class="tracker-date" datetime="{{ $job->application_date?->toDateString() }}">{{ $job->application_date?->format('d M Y') ?: '—' }}</time></td><td class="tracker-col-mid-center">@if($job->interview_date)<time class="tracker-date" datetime="{{ $job->interview_date->toIso8601String() }}">{{ $job->interview_date->timezone('Asia/Jakarta')->format('d M Y') }}</time><span class="tracker-secondary">{{ $job->interview_date->timezone('Asia/Jakarta')->format('H:i') }} WIB</span>@else<span class="tracker-unscheduled">—<span class="sr-only"> No interview scheduled</span></span>@endif</td><td class="tracker-col-mid-center">@include('tracker.partials.actions', ['mode' => 'table'])</td></tr>
                @empty<tr><td colspan="7"><div class="tracker-empty"><h3>{{ $search || $statusFilter || $platformFilter || $recruitmentStageFilter ? 'No matches this time.' : ($showArchived ? 'Your archive is empty.' : 'Your next opportunity starts here.') }}</h3><p>Try different filters, or add an application to start tracking.</p><button type="button" onclick="openJobModal()" class="workspace-button workspace-button--primary">Add application</button></div></td></tr>@endforelse</tbody>
            </table>
        </div>
        <div class="tracker-mobile-list">@forelse($jobApplications as $job)@include('tracker.partials.application-card', ['mode' => 'table'])@empty<div class="tracker-empty"><h3>No applications to show.</h3><p>Try different filters or add your first application.</p><button type="button" onclick="openJobModal()" class="workspace-button workspace-button--primary">Add application</button></div>@endforelse</div>
        <footer class="tracker-pagination">{{ $jobApplications->links() }}</footer>
    </section>
    @include('tracker.partials.follow-up')
</div>
