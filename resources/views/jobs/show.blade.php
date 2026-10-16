<x-app-layout>
    @php
        $editUrl = route('tracker', ['edit' => $job->id]);
        $tone = match($job->application_status) { 'Accepted' => 'accepted', 'Rejected' => 'rejected', default => 'process' };
        $stages = [
            ['label' => 'Applied', 'values' => ['Applied', 'Follow Up']],
            ['label' => 'Assessment', 'values' => ['Assessment Test', 'Psychotest']],
            ['label' => 'Interview', 'values' => ['HR - Interview', 'User - Interview', 'LGD', 'Presentation Round']],
            ['label' => 'Offering', 'values' => ['Offering']],
            ['label' => 'Outcome', 'values' => ['Not Processed']],
        ];
        $current = 0;
        foreach ($stages as $index => $stage) {
            if (in_array($job->recruitment_stage, $stage['values'], true)) $current = $index;
        }
        if (in_array($job->application_status, ['Accepted', 'Rejected'], true)) $current = 4;
        $currentLabel = $current === 4 ? ($job->application_status === 'On Process' ? 'Not Processed' : $job->application_status) : ($job->recruitment_stage ?: 'Applied');
        $sourceUrl = filter_var($job->platform_link, FILTER_VALIDATE_URL) && in_array(strtolower(parse_url($job->platform_link ?? '', PHP_URL_SCHEME) ?? ''), ['http', 'https'], true) ? $job->platform_link : null;
        $interview = $job->interview_date?->timezone('Asia/Jakarta');
    @endphp
    <div class="workspace-page job-detail-page" x-data="jobDetailTabs()">
        <div class="workspace-page__container">
            <nav class="job-detail-nav" aria-label="Application navigation"><a href="{{ route('tracker') }}" wire:navigate class="job-back"><i class="ph ph-arrow-left" aria-hidden="true"></i>All applications</a></nav>
            @if(session('success'))<div class="workspace-notice" role="status">{{ session('success') }}</div>@endif
            @if($job->is_archived)<div class="job-archived-notice" role="status">This application is archived. Restore it to show it in your active tracker.</div>@endif

            <header class="job-heading job-enter" x-data="dashboardWelcome()" @pointermove="move($event)" @pointerleave="reset()">
                <div class="job-heading-orbit" aria-hidden="true"></div>
                <div class="job-heading-main">
                    <div class="job-heading-copy">
                        <div class="job-heading-labels"><span class="job-heading-eyebrow">APPLICATION WORKSPACE</span><span class="tracker-pill tracker-pill--{{ $tone }}">{{ $job->application_status }}</span>@if($job->application_status === 'On Process')<span class="job-current-stage">{{ $currentLabel }}</span>@endif @if($job->is_pinned)<span class="job-pinned"><i class="ph-fill ph-push-pin" aria-hidden="true"></i>Pinned</span>@endif</div>
                        <p class="job-heading-company">{{ $job->company_name }}</p>
                        <h1>{{ $job->position }}</h1>
                        <div class="job-heading-meta"><x-platform-brand :name="$job->platform" class="platform-brand--compact" /><span>Applied {{ $job->application_date?->format('d M Y') ?: 'date not recorded' }}</span></div>
                    </div>
                </div>
                <div class="job-dossier-scene" aria-hidden="true">
                    <div class="job-dossier-shadow"></div>
                    <div class="job-dossier-stack">
                        <div class="job-dossier-sheet job-dossier-sheet--back"></div>
                        <div class="job-dossier-sheet job-dossier-sheet--middle"></div>
                        <div class="job-dossier-sheet job-dossier-sheet--front">
                            <div class="job-dossier-top"><span>TraKerja</span></div>
                            <svg class="job-dossier-symbol" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M13 7h15l8 8v26H13V7Z"/><path d="M28 7v9h8M19 23h11M19 29h11M19 35h6"/></svg>
                            <span class="job-dossier-label">{{ $job->application_status === 'On Process' ? 'Current stage' : 'Recorded outcome' }}</span>
                            <strong>{{ $currentLabel }}</strong>
                            <div class="job-dossier-foot"><span class="job-dossier-status"></span><span>{{ $job->is_archived ? 'Archived application' : 'Your application, organized.' }}</span></div>
                        </div>
                    </div>
                </div>
                <div class="job-heading-bottom">
                <div class="job-heading-actions" role="group" aria-label="Application actions">
                    <a href="{{ $editUrl }}" wire:navigate class="workspace-button workspace-button--primary"><i class="ph ph-pencil-simple" aria-hidden="true"></i>Edit application</a>
                    @if($sourceUrl)
                        <a href="{{ $sourceUrl }}" target="_blank" rel="noopener noreferrer" class="workspace-button workspace-button--quiet">Open listing <i class="ph ph-arrow-up-right" aria-hidden="true"></i><span class="sr-only">(opens in a new tab)</span></a>
                    @endif
                    <form action="{{ route('jobs.toggle-archive', $job) }}" method="POST"
                        x-data="{ company: @js($job->company_name), archived: @js((bool) $job->is_archived) }"
                        @submit.prevent="if (archived) { $el.submit(); } else { window.openConfirmModal('Archive application?', 'Move ' + company + ' to the archive? You can restore it anytime.', 'Archive', () => $el.submit(), { variant: 'archive' }); }">
                        @csrf
                        <button type="submit" class="job-archive-button"><i class="ph {{ $job->is_archived ? 'ph-archive-out' : 'ph-archive' }}" aria-hidden="true"></i>{{ $job->is_archived ? 'Restore application' : 'Archive' }}</button>
                    </form>
                </div>
                </div>
                <button type="button" class="job-motion-toggle" @click="toggleMotion()" x-show="!reduced" :aria-pressed="paused.toString()" x-text="paused ? 'Enable motion' : 'Pause motion'">Pause motion</button>
            </header>

            <div class="job-workspace-grid">
                <section class="job-workspace-main job-enter" aria-label="Application workspace">
                    <div class="job-workspace-tabs" role="tablist" aria-label="Application sections" @keydown="keyTab($event)">@foreach(['overview' => 'Overview', 'notes' => 'Notes', 'record' => 'Record'] as $key => $label)<button type="button" @click="selectTab('{{ $key }}')" id="job-tab-{{ $key }}" x-ref="tab_{{ $key }}" role="tab" :tabindex="tab === '{{ $key }}' ? 0 : -1" :class="{ 'is-active': tab === '{{ $key }}' }" :aria-selected="(tab === '{{ $key }}').toString()" aria-controls="job-panel-{{ $key }}">{{ $label }}@if($key === 'notes' && ($job->notes || $job->interview_notes))<span class="job-tab-dot" aria-label="Notes recorded"></span>@endif</button>@endforeach</div>
                    <div class="job-panel-viewport" x-ref="panels">
                    <div class="job-panel" id="job-panel-overview" role="tabpanel" aria-labelledby="job-tab-overview" x-ref="panel_overview" tabindex="-1" x-show="tab === 'overview'" aria-label="Application overview">
                        <section class="job-progress" aria-labelledby="job-progress-heading"><div class="job-section-heading"><h2 id="job-progress-heading">Application progress</h2><a href="{{ $editUrl }}" wire:navigate class="job-text-link">Update stage <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a></div>
                            <ol class="job-stage-track" aria-label="Stage overview" tabindex="0">@foreach($stages as $index => $stage)<li class="{{ $index === $current ? 'is-current' : '' }}" @if($index === $current) aria-current="step" @endif><span class="job-stage-dot" aria-hidden="true">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span><span>{{ $stage['label'] }}</span></li>@endforeach</ol><p class="job-stage-caption">Only the recorded stage is highlighted. Previous steps are not a verified activity history.</p>
                            <livewire:job-follow-up :job-id="$job->id" :key="'detail-follow-up-'.$job->id" />
                        </section>
                        <section class="job-overview-facts" aria-labelledby="job-facts-heading"><h2 id="job-facts-heading">Application details</h2><dl><div><dt>Location</dt><dd>{{ $job->location ?: 'Not recorded' }}</dd></div><div><dt>Employment</dt><dd>{{ $job->career_level ?: 'Not recorded' }}</dd></div></dl>@if(!$sourceUrl)<p class="job-missing-source">No valid original listing link recorded. Add one when editing this application.</p>@endif</section>
                        <section class="job-notes-preview" aria-labelledby="job-notes-preview-heading"><div class="job-section-heading"><h2 id="job-notes-preview-heading">Notes at a glance</h2><button type="button" @click="selectTab('notes', true)" class="job-text-link">{{ $job->notes ? 'Read notes' : 'Add notes' }} <i class="ph ph-arrow-right" aria-hidden="true"></i></button></div>@if($job->notes)<p>{{ \Illuminate\Support\Str::limit($job->notes, 230) }}</p>@else<p class="job-notes-placeholder">Keep recruiter conversations, role requirements, and your next questions here.</p>@endif</section>
                    </div>

                    <div class="job-panel" id="job-panel-notes" role="tabpanel" aria-labelledby="job-tab-notes" x-ref="panel_notes" x-show="tab === 'notes'" x-cloak aria-label="Application notes" tabindex="-1"><div class="job-section-heading"><div><h2>Your notes</h2><p>Your conversations, requirements, and questions.</p></div><a href="{{ $editUrl }}" wire:navigate class="job-text-link">{{ $job->notes ? 'Edit notes' : 'Add notes' }}</a></div>@if($job->notes)<div class="job-notes-content">{{ $job->notes }}</div>@else<div class="job-notes-empty"><p>No notes recorded yet.</p><a href="{{ $editUrl }}" wire:navigate class="job-text-link">Write your first note <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a></div>@endif @if($job->interview_notes)<section class="job-interview-notes"><h3>Interview notes</h3><p>{{ $job->interview_notes }}</p></section>@endif</div>

                    <div class="job-panel" id="job-panel-record" role="tabpanel" aria-labelledby="job-tab-record" x-ref="panel_record" tabindex="-1" x-show="tab === 'record'" x-cloak aria-label="Application record"><div class="job-section-heading"><div><h2>Application record</h2><p>Dates and preferences for this application.</p></div><span class="job-record-badge">{{ $job->is_archived ? 'Archived' : 'Active tracker' }}</span></div><dl class="job-record-dates"><div><dt>Applied</dt><dd>{{ $job->application_date?->format('d M Y') ?: 'Not recorded' }}</dd></div><div><dt>Created</dt><dd>{{ $job->created_at?->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</dd></div><div><dt>Last edited</dt><dd>{{ $job->updated_at?->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</dd></div>@if($job->is_archived && $job->archived_at)<div><dt>Archived</dt><dd>{{ $job->archived_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</dd></div>@endif</dl><div class="job-archive-policy"><div><strong>Auto-archive {{ auth()->user()->auto_archive_rejected ? 'on' : 'off' }}</strong><p>Rejected / Not Processed applications move to the archive after 14 days without changes when enabled.</p></div><a href="{{ route('profile.edit') }}" wire:navigate class="job-text-link">Preferences</a></div><div class="job-delete-row"><div><h3>Delete this application</h3><p>Permanently removes this record and deducts 10 XP.</p></div><form id="delete-job-form" action="{{ route('jobs.destroy', $job) }}" method="POST" x-data="{ company: @js($job->company_name) }" @submit.prevent="window.openConfirmModal('Delete application?', 'The application for ' + company + ' will be permanently deleted. 10 XP will be deducted.', 'Delete application', () => $el.submit())">@csrf @method('DELETE')<button type="submit" class="job-delete-link"><i class="ph ph-trash" aria-hidden="true"></i>Delete application</button></form></div></div>
                    </div>
                </section>

                <aside class="job-workspace-aside" aria-label="Interview and company resources">
                    <section class="job-interview-card job-enter {{ !$interview ? 'job-interview-card--empty' : '' }}" aria-labelledby="job-interview-heading"><div class="job-section-heading"><h2 id="job-interview-heading">{{ $interview ? ($interview->isPast() ? 'Recorded interview' : 'Upcoming interview') : 'Interview' }}</h2>@if($interview)<a href="{{ route('interviews') }}" wire:navigate class="job-text-link">Calendar</a>@endif</div>
                        @if($interview)<div class="job-interview-when"><div class="job-interview-date"><strong>{{ $interview->format('d') }}</strong><span>{{ $interview->format('M') }}</span></div><div><p>{{ $interview->format('D, d M Y') }}</p><strong>{{ $interview->format('H:i') }} <small>WIB</small></strong><span>{{ $job->interview_type ?: 'Type not recorded' }}</span></div></div>@if($job->interview_location)<div class="job-interview-location"><span>Location / meeting link</span><p>{{ $job->interview_location }}</p>@if(filter_var($job->interview_location, FILTER_VALIDATE_URL) && in_array(strtolower(parse_url($job->interview_location, PHP_URL_SCHEME) ?? ''), ['http', 'https'], true))<a href="{{ $job->interview_location }}" target="_blank" rel="noopener noreferrer" class="job-text-link">Open meeting <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>@endif</div>@endif<a href="{{ $editUrl }}" wire:navigate class="job-text-link">Edit interview details <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>@if($interview->isFuture() && auth()->user()->canAccessEmailNotifications())<form class="job-reminder-form" x-data="{ sending: false, message: '', async send() { if (this.sending) return; this.sending = true; this.message = ''; try { const response = await fetch($el.action, { method: 'POST', credentials: 'same-origin', headers: { 'X-CSRF-TOKEN': $el.querySelector('input[name=_token]').value, 'Accept': 'application/json' } }); const body = await response.json(); if (!response.ok || !body.success) throw new Error(body.message || 'Could not send the reminder. Try again.'); this.message = body.message; } catch (error) { this.message = error.message || 'Could not send the reminder. Try again.'; } finally { this.sending = false; } } }" @submit.prevent="send()" method="POST" action="{{ route('jobs.send-interview-reminder', $job) }}">@csrf<button type="submit" :disabled="sending" class="job-text-link"><span x-text="sending ? 'Sending reminder…' : 'Email me a reminder'">Email me a reminder</span></button><p x-show="message" x-cloak x-text="message" role="status"></p></form>@endif
                        @else<p class="job-interview-empty">No interview scheduled yet.</p><a href="{{ $editUrl }}" wire:navigate class="job-text-link">Schedule interview <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>@endif
                    </section>
                    <section class="job-research-card job-enter" aria-labelledby="job-research-heading"><div class="job-section-heading"><h2 id="job-research-heading">Company research</h2></div><p>Company profiles &amp; reviews.</p><div class="job-research-links"><a href="https://www.linkedin.com/search/results/companies/?keywords={{ urlencode($job->company_name) }}" target="_blank" rel="noopener noreferrer" aria-label="Research company on LinkedIn (opens in a new tab)" title="LinkedIn"><x-platform-brand name="LinkedIn" class="platform-brand--compact" /><i class="ph ph-arrow-up-right" aria-hidden="true"></i></a><a href="https://www.glassdoor.com/Search/results.htm?keyword={{ urlencode($job->company_name) }}" target="_blank" rel="noopener noreferrer" aria-label="Research company on Glassdoor (opens in a new tab)" title="Glassdoor"><x-platform-brand name="Glassdoor" class="platform-brand--compact" /><i class="ph ph-arrow-up-right" aria-hidden="true"></i></a></div>@include('jobs.partials.salary-reference')</section>
                </aside>
            </div>
            <footer class="job-detail-footer"><p>Keep this application up to date.</p><a href="{{ route('tracker') }}" wire:navigate class="job-text-link">Back to tracker <i class="ph ph-arrow-right" aria-hidden="true"></i></a></footer>
        </div>
    </div>
</x-app-layout>
