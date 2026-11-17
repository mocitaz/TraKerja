@php
    $statusBadge = ['active' => ['healthy', __('Open')], 'suspect' => ['medium', __('Suspected closed')], 'closed' => ['critical', __('Close')], 'archived' => ['unchecked', __('Archived')]];
    $reasons = \App\Livewire\Admin\VacancyManager::REASONS;
    $jkt = fn ($d) => $d?->copy()->timezone('Asia/Jakarta');
@endphp
<div class="oc-workspace je-workspace vm-workspace">
    <header class="am-page-heading">
        <div>
            <h1>{{ __('Manage Jobs') }}</h1>
            <p>{{ __('Keep the job catalog tidy: review odd jobs, archive, re-check, merge duplicates and rate QA samples. Changes show up in Explore right away.') }}</p>
        </div>
        <div class="je-row-actions">
            <a href="{{ route('admin.job-engine') }}" class="am-button"><i class="ph ph-gauge" aria-hidden="true"></i>{{ __('Engine Monitor') }}</a>
            <a href="{{ route('admin.promotions') }}" class="am-button am-button--primary"><i class="ph ph-megaphone" aria-hidden="true"></i>{{ __('Social Promotion') }}</a>
        </div>
    </header>

    <section class="ac-stats oc-stats vm-stats" aria-label="{{ __('Catalog summary') }}">
        @foreach([
            ['all', 'briefcase', __('Open jobs'), $counts['open']],
            ['reported', 'flag', __('Reported by users'), $counts['reported']],
            ['scam', 'shield-warning', __('Suspected scams'), $counts['scam']],
            ['pairs', 'copy', __('Pending duplicates'), $counts['pairs']],
            ['archived', 'archive', __('Archived'), $counts['archived']],
        ] as [$key, $icon, $label, $value])
            <button type="button" wire:click="{{ in_array($key, ['all', 'pairs']) ? "selectTab('{$key}')" : ($key === 'archived' ? "\$set('status', 'archived'); \$set('tab', 'all')" : "\$set('flag', '{$key}'); \$set('tab', 'review')") }}" aria-label="{{ __($label) }} · {{ __('view') }}">
                <span class="ac-stat-icon"><i class="ph ph-{{ $icon }}" aria-hidden="true"></i></span>
                <span><span class="oc-stat-label">{{ __($label) }}</span><strong>{{ number_format($value) }}</strong></span>
            </button>
        @endforeach
    </section>

    <nav class="oc-tabs je-tabs" aria-label="{{ __('Job management sections') }}">
        @foreach(['review' => [__('Needs review'), 'list-checks'], 'all' => [__('All jobs'), 'briefcase'], 'pairs' => [__('Duplicates').' ('.$counts['pairs'].')', 'copy'], 'qa' => [__('Weekly QA'), 'check-square-offset']] as $key => [$label, $icon])
            <button type="button" wire:click="selectTab('{{ $key }}')" @if($tab === $key) aria-current="page" @endif><i class="ph ph-{{ $icon }}" aria-hidden="true"></i>{{ __($label) }}</button>
        @endforeach
    </nav>
    <div wire:loading.delay role="status" class="am-loading">{{ __('Loading data…') }}</div>
    @if ($flash)<p class="oc-notice" role="status">{{ $flash }}</p>@endif

    @if (in_array($tab, ['review', 'all'], true))
        <form class="oc-filters je-filters {{ $tab === 'review' ? 'vm-filters--review' : '' }}" wire:submit.prevent>
            @if ($tab === 'all')
                <label class="am-search"><i class="ph ph-magnifying-glass" aria-hidden="true"></i><span class="sr-only">{{ __('Search') }}</span><input type="search" wire:model.live.debounce.300ms="search" placeholder="{{ __('Title, company, #ID or public ID') }}"></label>
                <label class="am-filter"><span>Status</span><select wire:model.live="status"><option value="open">{{ __('Open + suspected') }}</option><option value="active">{{ __('Open') }}</option><option value="suspect">{{ __('Suspected closed') }}</option><option value="closed">{{ __('Close') }}</option><option value="archived">{{ __('Archived') }}</option><option value="all">{{ __('All') }}</option></select></label>
                <label class="am-filter"><span>{{ __('Source') }}</span><select wire:model.live="source"><option value="">{{ __('All sources') }}</option>@foreach ($sources as $src)<option value="{{ $src->id }}">{{ $src->name }}</option>@endforeach</select></label>
            @endif
            <label class="am-filter"><span>{{ $tab === 'review' ? __('Reason') : __('Needs review') }}</span><select wire:model.live="flag"><option value="">{{ __('All') }}</option>@foreach ($reasons as $key => [$label]) @if ($tab === 'review' || in_array($key, ['reported', 'scam', 'region', 'raw'], true))<option value="{{ $key }}">{{ __($label) }}</option>@endif @endforeach</select></label>
        </form>

        @if ($selected)
            <div class="vm-bulk" role="region" aria-label="{{ __('Bulk actions') }}">
                <strong>{{ __(':count selected', ['count' => count($selected)]) }}</strong>
                <button type="button" class="am-button" wire:click="bulk('checked')"><i class="ph ph-check" aria-hidden="true"></i>{{ __('Mark as checked') }}</button>
                <button type="button" class="am-button" wire:click="bulk('recheck')"><i class="ph ph-arrows-clockwise" aria-hidden="true"></i>{{ __('Re-check') }}</button>
                <button type="button" class="am-button" wire:click="bulk('scam')" wire:confirm="{{ __('Mark :count jobs as suspected scams?', ['count' => count($selected)]) }}"><i class="ph ph-shield-warning" aria-hidden="true"></i>{{ __('Mark as scam') }}</button>
                <button type="button" class="am-button" wire:click="bulk('archive')" wire:confirm="{{ __('Archive :count jobs?', ['count' => count($selected)]) }}"><i class="ph ph-archive" aria-hidden="true"></i>{{ __('Archive') }}</button>
                <button type="button" class="oc-inline" wire:click="$set('selected', [])">{{ __('Cancel') }}</button>
            </div>
        @endif

        <section class="oc-panel">
            <header class="oc-panel-heading">
                <div>
                    <h2>{{ $tab === 'review' ? __('Needs review') : __('All jobs') }}</h2>
                    <p>{{ $tab === 'review' ? __('Jobs that look odd to the automatic rules, user reports first. Mark as checked once correct; the job will not appear again unless reported again.') : __('Archiving hides a job from Explore, public pages and the feed. An archived job stays hidden even if its source still lists it.') }}</p>
                </div>
                @if ($tab === 'all')<button type="button" class="am-button" wire:click="archiveOldClosed" wire:confirm="{{ __('Archive all jobs closed for more than 30 days?') }}"><i class="ph ph-archive" aria-hidden="true"></i>{{ __('Archive closed > 30 days') }}</button>@else<span>{{ __(':count jobs', ['count' => $rows->total()]) }}</span>@endif
            </header>
            <div class="oc-table-wrap"><table class="oc-table je-vacancy-table vm-table">
                <thead><tr><th class="vm-check"><span class="sr-only">{{ __('Select') }}</span></th><th>{{ __('Job') }}</th><th>{{ $tab === 'review' ? __('Reason') : __('Source') }}</th><th>{{ __('Location') }}</th><th>Status</th><th><span class="sr-only">{{ __('Actions') }}</span></th></tr></thead>
                <tbody>
                    @forelse ($rows as $v)
                        @php [$tone, $label] = $statusBadge[$v->status] ?? ['unchecked', $v->status]; @endphp
                        <tr wire:key="vm-{{ $tab }}-{{ $v->id }}" @class(['is-selected' => in_array((string) $v->id, $selected, true)])>
                            <td class="vm-check"><input type="checkbox" value="{{ $v->id }}" wire:model.live="selected" aria-label="{{ __('Select :title', ['title' => $v->title]) }}"></td>
                            <td><div class="je-vacancy">
                                <span class="je-logo">@if ($v->company?->logo_url)<img src="{{ $v->company->logo_url }}" alt="" width="32" height="32" loading="lazy" referrerpolicy="no-referrer" onerror="this.remove()">@endif<i class="ph ph-buildings" aria-hidden="true"></i></span>
                                <button type="button" class="oc-record-link" wire:click="open({{ $v->id }})"><strong>{{ $v->title }}</strong><small>{{ $v->company?->name ?? __('Unknown company') }} · #{{ $v->id }}@if($v->salary_label) · {{ $v->salary_label }}@endif</small></button>
                            </div></td>
                            <td>
                                @if ($tab === 'review')
                                    <div class="vm-reasons">@foreach ($v->review_reasons as $r)<span class="oc-badge {{ in_array($r, ['reported', 'scam']) ? 'oc-badge--critical' : '' }}"><i class="ph ph-{{ $reasons[$r][1] }}" aria-hidden="true"></i>{{ __($reasons[$r][0]) }}</span>@endforeach</div>
                                @else
                                    <div class="je-source-list">@foreach ($v->listings->unique('job_source_id') as $l)<a href="{{ $l->url }}" target="_blank" rel="noopener noreferrer" title="{{ __('Open on :source', ['source' => $l->source?->name]) }}"><x-platform-brand :name="$l->source?->name" :show-name="false" /></a>@endforeach</div>
                                @endif
                            </td>
                            <td>{{ $v->region?->name ?? ($v->location_text ? \Illuminate\Support\Str::limit($v->location_text, 26) : '–') }}</td>
                            <td><span class="oc-badge oc-badge--{{ $tone }}">{{ $label }}</span>@if ($v->scam_flag === 'suspected' && $tab === 'all')<span class="oc-badge oc-badge--critical">{{ __('Scam?') }}</span>@endif</td>
                            <td><div class="je-row-actions">
                                @if ($v->status !== 'archived')
                                    @if ($tab === 'review')<button type="button" class="am-button" wire:click="markChecked({{ $v->id }})"><i class="ph ph-check" aria-hidden="true"></i>{{ __('Checked') }}</button>@endif
                                    <button type="button" class="am-button am-button--icon" wire:click="recheck({{ $v->id }})" title="{{ __('Re-check now') }}" aria-label="{{ __('Re-check :title', ['title' => $v->title]) }}"><i class="ph ph-arrows-clockwise" aria-hidden="true"></i></button>
                                    <a class="am-button am-button--icon" href="{{ route('admin.promotions', ['vacancy' => $v->id]) }}" title="{{ __('Create social promotion') }}" aria-label="{{ __('Promote :title', ['title' => $v->title]) }}"><i class="ph ph-megaphone" aria-hidden="true"></i></a>
                                    <button type="button" class="am-button am-button--icon" wire:click="archive({{ $v->id }})" wire:confirm="{{ __('Archive this job? It disappears from Explore and public pages.') }}" title="{{ __('Archive') }}" aria-label="{{ __('Archive :title', ['title' => $v->title]) }}"><i class="ph ph-archive" aria-hidden="true"></i></button>
                                @else
                                    <button type="button" class="am-button" wire:click="restore({{ $v->id }})"><i class="ph ph-arrow-counter-clockwise" aria-hidden="true"></i>{{ __('Restore') }}</button>
                                @endif
                                <button type="button" class="am-button am-button--icon" wire:click="open({{ $v->id }})" title="{{ __('Details') }}" aria-label="{{ __('Details of :title', ['title' => $v->title]) }}"><i class="ph ph-sidebar-simple" aria-hidden="true"></i></button>
                            </div></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="oc-empty"><i class="ph ph-check-circle" aria-hidden="true"></i><h3>{{ $tab === 'review' ? __('Nothing to review') : __('No jobs') }}</h3><p>{{ $tab === 'review' ? __('All jobs pass the automatic rules.') : __('Try another keyword or filter.') }}</p></td></tr>
                    @endforelse
                </tbody>
            </table></div>
            @include('admin.operations.pagination', ['records' => $rows])
        </section>
    {{-- ================= Duplicate review ================= --}}
    @elseif ($tab === 'pairs')
        <section class="oc-panel">
            <header class="oc-panel-heading"><div><h2>{{ __('Duplicate review') }}</h2><p>{{ __('Pairs too similar to decide automatically. Merge if both are the same job; a merge can be undone with jobs:unmerge.') }}</p></div><span>{{ __(':count pairs', ['count' => $pairs->count()]) }}</span></header>
            @forelse ($pairs as $pair)
                <article class="je-pair" wire:key="pair-{{ $pair['review']->id }}">
                    @foreach ([[$pair['listing']->vacancy, $pair['listing']->source?->name, $pair['listing']->url], [$pair['candidate'], $pair['candidate']->listings->pluck('source.name')->filter()->unique()->implode(', '), $pair['candidate']->listings->first()?->url]] as [$item, $where, $link])
                        <div class="je-pair-side">
                            <span class="oc-badge">{{ $where }}</span>
                            <strong>{{ $item->title }}</strong>
                            <small>{{ $item->company?->name }} · {{ $item->region?->name ?? $item->location_text ?? __('unknown location') }}</small>
                            @if ($link)<a class="oc-inline" href="{{ $link }}" target="_blank" rel="noopener">{{ __('Open source') }} <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>@endif
                        </div>
                    @endforeach
                    <footer>
                        <span>{{ __('Title similarity') }} {{ number_format($pair['detail']['title'] ?? $pair['review']->score, 2) }}@if(isset($pair['detail']['description'])) · {{ __('description') }} {{ number_format($pair['detail']['description'], 2) }}@endif</span>
                        <div class="je-row-actions">
                            <button type="button" class="am-button" wire:click="resolvePair({{ $pair['review']->id }}, false)">{{ __('Split') }}</button>
                            <button type="button" class="am-button am-button--primary" wire:click="resolvePair({{ $pair['review']->id }}, true)">{{ __('Merge, same job') }}</button>
                        </div>
                    </footer>
                </article>
            @empty
                <div class="oc-empty"><i class="ph ph-check-circle" aria-hidden="true"></i><h3>{{ __('No pending pairs') }}</h3><p>{{ __('New uncertain duplicates will appear here.') }}</p></div>
            @endforelse
        </section>

    {{-- ================= Weekly QA ================= --}}
    @elseif ($tab === 'qa')
        <section class="oc-panel">
            <header class="oc-panel-heading"><div><h2>{{ __('Weekly QA') }} · {{ $sample }}</h2><p>{{ __(':done of :total checked. Open the source, then judge whether the data and status in TraKerja are correct.', ['done' => $qa->whereNotNull('verdict')->count(), 'total' => $qa->count()]) }}</p></div>
                <button type="button" class="am-button am-button--primary" wire:click="drawQaSample"><i class="ph ph-shuffle" aria-hidden="true"></i>{{ __('Draw this week’s sample') }}</button></header>
            <div class="oc-table-wrap"><table class="oc-table">
                <thead><tr><th>{{ __('Job') }}</th><th>Status</th><th>{{ __(__('Data')) }}</th><th>{{ __('Rating') }}</th></tr></thead>
                <tbody>
                    @forelse ($qa as $check)
                        @php $v = $qaVacancies[$check->vacancy_id] ?? null; @endphp
                        @continue(! $v)
                        @php [$tone, $label] = $statusBadge[$v->status] ?? ['unchecked', $v->status]; @endphp
                        <tr wire:key="qa-{{ $check->id }}">
                            <td><span class="oc-record-link"><strong>{{ $v->title }}</strong><small>{{ $v->company?->name }}</small></span>
                                <div class="je-source-list">@foreach ($v->listings as $l)<a href="{{ $l->url }}" target="_blank" rel="noopener" title="{{ $l->source?->name }}"><x-platform-brand :name="$l->source?->name" :show-name="false" /></a>@endforeach</div></td>
                            <td><span class="oc-badge oc-badge--{{ $tone }}">{{ $label }}</span>@if($v->closed_reason)<small class="je-sub">{{ $v->closed_reason }}</small>@endif</td>
                            <td>{{ $v->region?->name ?? $v->location_text ?? '–' }}<small class="je-sub">{{ $v->employment_type ?? '–' }} · {{ $v->seniority ?? '–' }} · {{ $v->salary_label ?? __('salary –') }}</small></td>
                            <td class="je-qa-cell">
                                @if ($check->verdict)
                                    <span class="oc-badge oc-badge--{{ $check->verdict === 'correct' ? 'healthy' : 'medium' }}">{{ __($verdicts[$check->verdict]) }}</span>@if($check->note)<small class="je-sub">{{ $check->note }}</small>@endif
                                @else
                                    <label class="am-search je-note"><input type="text" wire:model="qaNotes.{{ $check->id }}" placeholder="{{ __('Note (optional)') }}" aria-label="{{ __('Note for :title', ['title' => $v->title]) }}"></label>
                                    <div class="je-row-actions">@foreach ($verdicts as $key => $vlabel)<button type="button" class="am-button {{ $key === 'correct' ? 'am-button--primary' : '' }}" wire:click="judge({{ $check->id }}, '{{ $key }}')">{{ $key === 'correct' ? __('Correct') : \Illuminate\Support\Str::before(__($vlabel), ' (') }}</button>@endforeach</div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="oc-empty"><i class="ph ph-check-square-offset" aria-hidden="true"></i><h3>{{ __('No sample this week yet') }}</h3><p>{{ __('Draw a sample of 20 open jobs and 5 recently closed jobs.') }}</p></td></tr>
                    @endforelse
                </tbody>
            </table></div>
        </section>

    @endif

    {{-- ================= Detail panel ================= --}}
    @if ($detail)
        @php
            $v = $detail['vacancy'];
            [$tone, $label] = $statusBadge[$v->status] ?? ['unchecked', $v->status];
        @endphp
        <div class="vm-drawer-backdrop" wire:click.self="closeDetail" x-data x-on:keydown.escape.window="$wire.closeDetail()">
            <aside class="vm-drawer" role="dialog" aria-modal="true" aria-labelledby="vm-detail-title">
                <header class="vm-drawer-head">
                    <span class="je-logo vm-drawer-logo">@if ($v->company?->logo_url)<img src="{{ $v->company->logo_url }}" alt="" referrerpolicy="no-referrer" onerror="this.remove()">@endif<i class="ph ph-buildings" aria-hidden="true"></i></span>
                    <div><h2 id="vm-detail-title">{{ $v->title }}</h2><p>{{ $v->company?->name }} · #{{ $v->id }} · <span class="oc-badge oc-badge--{{ $tone }}">{{ $label }}</span></p></div>
                    <button type="button" class="am-button am-button--icon" wire:click="closeDetail" aria-label="{{ __('Close') }}"><i class="ph ph-x" aria-hidden="true"></i></button>
                </header>
                <div class="vm-drawer-body">
                    @if ($detail['reasons'])<div class="vm-reasons">@foreach ($detail['reasons'] as $r)<span class="oc-badge {{ in_array($r, ['reported', 'scam']) ? 'oc-badge--critical' : '' }}"><i class="ph ph-{{ $reasons[$r][1] }}" aria-hidden="true"></i>{{ __($reasons[$r][0]) }}</span>@endforeach</div>@endif
                    <dl class="vm-facts">
                        <div><dt>{{ __('Location') }}</dt><dd>{{ $v->region?->name ?? $v->location_text ?? '–' }}</dd></div>
                        <div><dt>{{ __('Salary') }}</dt><dd>{{ $v->salary_label ?? '–' }}</dd></div>
                        <div><dt>{{ __('Sector') }}</dt><dd>{{ $v->sector?->name ?? '–' }}</dd></div>
                        <div><dt>{{ __('Level · type') }}</dt><dd>{{ $v->seniority ?? '–' }} · {{ $v->employment_type ?? '–' }}</dd></div>
                        <div><dt>{{ __('First seen') }}</dt><dd>{{ $jkt($v->first_seen_at)?->format('d M Y H:i') ?? '–' }}</dd></div>
                        <div><dt>{{ __('Last checked') }}</dt><dd>{{ $jkt($v->last_verified_at)?->format('d M Y H:i') ?? '–' }}</dd></div>
                    </dl>
                    <section><h3>{{ __('Listings') }} ({{ $v->listings->count() }})</h3>
                        <ul class="vm-list">@foreach ($v->listings as $l)<li><x-platform-brand :name="$l->source?->name" :show-name="false" /><a href="{{ $l->url }}" target="_blank" rel="noopener noreferrer">{{ $l->source?->name }}</a><span class="oc-badge oc-badge--{{ $statusBadge[$l->status][0] ?? 'unchecked' }}">{{ __($statusBadge[$l->status][1] ?? $l->status) }}</span><small>{{ $l->match_method }}</small></li>@endforeach</ul>
                    </section>
                    <section><h3>{{ __('Check history') }}</h3>
                        @forelse ($detail['checks'] as $c)
                            <p class="vm-line"><span class="oc-badge oc-badge--{{ ['open' => 'healthy', 'closed' => 'critical', 'suspect' => 'medium'][$c->verdict] ?? 'unchecked' }}">{{ $c->verdict }}</span> HTTP {{ $c->http_status ?? '–' }} · {{ \Illuminate\Support\Carbon::parse($c->checked_at)->timezone('Asia/Jakarta')->format('d M H:i') }} @if($c->signals && $c->signals !== '[]')<small>{{ implode(', ', (array) json_decode($c->signals, true)) }}</small>@endif</p>
                        @empty<p class="vm-muted">{{ __('Never checked by the verifier.') }}</p>@endforelse
                    </section>
                    @if ($detail['reports']->isNotEmpty())
                        <section><h3>{{ __('User reports') }}</h3>@foreach ($detail['reports'] as $r)<p class="vm-line">{{ $r->reason }} · {{ \Illuminate\Support\Carbon::parse($r->created_at)->timezone('Asia/Jakarta')->format('d M H:i') }} @if($r->resolved_at)<span class="oc-badge oc-badge--healthy">{{ __('resolved') }}</span>@endif</p>@endforeach</section>
                    @endif
                    @if ($detail['merged']->isNotEmpty())
                        <section><h3>{{ __('Merged into this job') }}</h3>@foreach ($detail['merged'] as $m)<p class="vm-line"><span>{{ $m->title }} (#{{ $m->id }})</span><button type="button" class="oc-inline" wire:click="unmerge({{ $m->id }})" wire:confirm="{{ __('Split #:id back out?', ['id' => $m->id]) }}">{{ __('Split') }}</button></p>@endforeach</section>
                    @endif
                    @if ($detail['promotions']->isNotEmpty())
                        <section><h3>{{ __('Promotion') }}</h3>@foreach ($detail['promotions'] as $p)<p class="vm-line">{{ $p->headline }} · {{ $p->status === 'posted' ? __('posted') : __('draft') }}</p>@endforeach</section>
                    @endif
                </div>
                <footer class="vm-drawer-foot">
                    <a class="am-button" href="{{ parse_url($v->public_path, PHP_URL_PATH) }}" target="_blank" rel="noopener"><i class="ph ph-arrow-square-out" aria-hidden="true"></i>{{ __('Public page') }}</a>
                    @if ($v->status !== 'archived')
                        <button type="button" class="am-button" wire:click="toggleScam({{ $v->id }})"><i class="ph {{ $v->scam_flag === 'suspected' ? 'ph-shield-check' : 'ph-shield-warning' }}" aria-hidden="true"></i>{{ $v->scam_flag === 'suspected' ? __('Not a scam') : __('Mark as scam') }}</button>
                        <a class="am-button" href="{{ route('admin.promotions', ['vacancy' => $v->id]) }}"><i class="ph ph-megaphone" aria-hidden="true"></i>{{ __('Promote') }}</a>
                        <button type="button" class="am-button am-button--primary" wire:click="markChecked({{ $v->id }})"><i class="ph ph-check" aria-hidden="true"></i>{{ __('Checked') }}</button>
                    @else
                        <button type="button" class="am-button am-button--primary" wire:click="restore({{ $v->id }})">{{ __('Restore') }}</button>
                    @endif
                </footer>
            </aside>
        </div>
    @endif
</div>
