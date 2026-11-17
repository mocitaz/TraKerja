@php
    $healthBadge = ['ok' => ['healthy', __('Healthy')], 'degraded' => ['medium', __('Degraded')], 'failing' => ['critical', __('Failed')], 'paused' => ['unchecked', __('Paused')]];
    $statusBadge = ['active' => ['healthy', __('Open')], 'suspect' => ['medium', __('Suspected closed')], 'closed' => ['critical', __('Closed')], 'archived' => ['unchecked', __('Archived')]];
    $runBadge = ['success' => ['healthy', __('Success')], 'partial' => ['medium', __('Partial')], 'failed' => ['critical', __('Failed')], 'running' => ['monitoring', __('Running')]];
    $jkt = fn ($d) => $d?->copy()->timezone('Asia/Jakarta');
@endphp
<div class="oc-workspace je-workspace">
    <header class="am-page-heading">
        <div>
            <h1>{{ __('Engine Monitor') }}</h1>
            <p>{{ __('Job Data Engine: scraper health, queues, AI cost and data quality. Automatic schedule is :state.', ['state' => $stats['schedule'] ? __('on') : __('off')]) }}</p>
        </div>
        <div class="je-row-actions">
            <a href="{{ route('admin.vacancies') }}" class="am-button"><i class="ph ph-briefcase" aria-hidden="true"></i>{{ __('Manage Jobs') }}</a>
            <a href="{{ route('jobs.explore') }}" class="am-button" target="_blank" rel="noopener"><i class="ph ph-arrow-square-out" aria-hidden="true"></i>{{ __('View Explore') }}</a>
        </div>
    </header>

    <section class="ac-stats oc-stats je-stats" aria-label="{{ __('Engine summary') }}">
        @foreach([
            [route('admin.vacancies', ['tab' => 'all', 'status' => 'active']), 'briefcase', __('Open jobs'), number_format($stats['active'])],
            [route('admin.vacancies', ['tab' => 'all']), 'sparkle', __('New in 24h'), number_format($stats['new_24h'])],
            [route('admin.vacancies', ['tab' => 'all', 'status' => 'suspect']), 'warning-circle', __('Suspected closed'), number_format($stats['suspect'])],
            [route('admin.vacancies', ['tab' => 'pairs']), 'copy', __('Pending duplicates'), number_format($stats['pending_pairs'])],
            [route('admin.vacancies', ['tab' => 'review', 'flag' => 'scam']), 'shield-warning', __('Suspected scams'), number_format($stats['scam'])],
            [null, 'coins', __('AI today'), '$'.number_format($stats['ai_today'], 3)],
        ] as [$href, $icon, $label, $value])
            @if ($href)<a href="{{ $href }}" class="je-stat-link">@else<div class="je-stat-link">@endif
                <span class="ac-stat-icon"><i class="ph ph-{{ $icon }}" aria-hidden="true"></i></span>
                <span><span class="oc-stat-label">{{ $label }}</span><strong>{{ $value }}</strong></span>
            @if ($href)</a>@else</div>@endif
        @endforeach
    </section>

    <nav class="oc-tabs je-tabs" aria-label="{{ __('Engine sections') }}">
        @foreach(['overview' => [__('Overview'), 'gauge'], 'sources' => [__('Sources'), 'plugs-connected'], 'runs' => [__('Run history'), 'clock-counter-clockwise'], 'feed' => ['Feed API', 'broadcast']] as $key => [$label, $icon])
            <button type="button" wire:click="selectTab('{{ $key }}')" @if($tab === $key) aria-current="page" @endif><i class="ph ph-{{ $icon }}" aria-hidden="true"></i>{{ $label }}</button>
        @endforeach
    </nav>
    <div wire:loading.delay role="status" class="am-loading">{{ __('Loading data…') }}</div>
    @if ($flash)<p class="oc-notice" role="status">{{ $flash }}</p>@endif

    {{-- ================= Overview ================= --}}
    @if ($tab === 'overview')
        <div class="je-grid">
            <section class="oc-panel je-span-2">
                <header class="oc-panel-heading"><div><h2>{{ __('Needs attention') }}</h2><p>{{ __('Items that need admin action, most urgent first.') }}</p></div>
                    <div class="je-row-actions">
                        <button type="button" class="am-button" wire:click="verifyNow"><i class="ph ph-shield-check" aria-hidden="true"></i>{{ __('Verify now') }}</button>
                        <button type="button" class="am-button" wire:click="enrichPending"><i class="ph ph-sparkle" aria-hidden="true"></i>{{ __('Enrich pending') }}</button>
                        <button type="button" class="am-button" wire:click="retryFailed" @disabled(! $overview['failedJobs'])><i class="ph ph-arrow-clockwise" aria-hidden="true"></i>{{ __('Retry failed jobs') }}</button>
                    </div></header>
                <ul class="je-alerts">
                    @forelse (collect($overview['alerts'])->sortBy(fn ($a) => ['bad' => 0, 'warn' => 1, 'info' => 2][$a[0]]) as [$level, $title, $detail])
                        <li class="je-alert je-alert--{{ $level }}"><i class="ph {{ ['bad' => 'ph-warning-octagon', 'warn' => 'ph-warning', 'info' => 'ph-info'][$level] }}" aria-hidden="true"></i><div><strong>{{ $title }}</strong><p>{{ $detail }}</p></div></li>
                    @empty
                        <li class="oc-empty"><i class="ph ph-check-circle" aria-hidden="true"></i><h3>{{ __('Everything is running normally') }}</h3><p>{{ __('No failing sources, failed queue jobs or pending reviews.') }}</p></li>
                    @endforelse
                </ul>
            </section>

            <section class="oc-panel">
                <header class="oc-panel-heading"><div><h2>{{ __('Data quality') }}</h2><p>{{ __('Out of :count jobs shown in Explore.', ['count' => number_format($stats['active'])]) }}</p></div></header>
                <div class="je-meters">
                    @foreach ($overview['coverage'] as $label => $ratio)
                        <div><span>{{ $label }}</span><strong>{{ round($ratio * 100) }}%</strong><div class="je-meter"><span style="width: {{ max(2, round($ratio * 100)) }}%"></span></div></div>
                    @endforeach
                </div>
            </section>

            <section class="oc-panel">
                <header class="oc-panel-heading"><div><h2>{{ __('AI cost, 7 days') }}</h2><p>{{ __('Daily limit $:budget. Enrichment stops automatically at the limit.', ['budget' => number_format($overview['budget'], 2)]) }}</p></div><span>${{ number_format($overview['spend']->sum('usd'), 3) }}</span></header>
                @php $max = max(0.0001, $overview['spend']->max('usd'), $overview['budget']); @endphp
                <div class="je-bars" role="img" aria-label="{{ __('AI cost per day over 7 days') }}">
                    @foreach ($overview['spend'] as $day)
                        <div title="{{ \Illuminate\Support\Carbon::parse($day['day'])->locale(app()->getLocale())->translatedFormat('d M') }}: ${{ number_format($day['usd'], 4) }} · {{ __(':count calls', ['count' => $day['calls']]) }}"><span style="height: {{ max(3, round($day['usd'] / $max * 100)) }}%"></span><small>{{ \Illuminate\Support\Carbon::parse($day['day'])->locale(app()->getLocale())->translatedFormat('D') }}</small></div>
                    @endforeach
                </div>
            </section>

            <section class="oc-panel je-span-2">
                <header class="oc-panel-heading"><div><h2>{{ __('Latest scraper runs') }}</h2><p>{{ __('Every discovery and verification is recorded here.') }}</p></div><button type="button" class="oc-inline" wire:click="selectTab('runs')">{{ __('All history') }} <i class="ph ph-arrow-right" aria-hidden="true"></i></button></header>
                <div class="oc-table-wrap"><table class="oc-table">
                    <thead><tr><th>{{ __('Sources') }}</th><th>{{ __('Type') }}</th><th>Status</th><th>{{ __('Result') }}</th><th>{{ __('Time') }}</th></tr></thead>
                    <tbody>
                        @forelse ($overview['recentRuns'] as $run)
                            @php [$tone, $label] = $runBadge[$run->status] ?? ['unchecked', $run->status]; @endphp
                            <tr wire:key="ov-run-{{ $run->id }}">
                                <td><div class="je-source"><x-platform-brand :name="$run->source?->name" :show-name="false" /><strong>{{ $run->source?->name ?? __('All sources') }}</strong></div></td>
                                <td>{{ $run->run_type === 'verify' ? __('Verification') : 'Discovery' }}</td>
                                <td><span class="oc-badge oc-badge--{{ $tone }}">{{ $label }}</span></td>
                                <td>{{ __(':count read', ['count' => $run->found]) }} · <b>{{ $run->new_listings }}</b> {{ __('new') }} · {{ __(':count changed', ['count' => $run->updated_listings]) }} @if($run->failed_items)<span class="je-bad-text">· {{ __(':count failed', ['count' => $run->failed_items]) }}</span>@endif</td>
                                <td><time>{{ $jkt($run->started_at)?->locale(app()->getLocale())->diffForHumans() }}</time></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="oc-empty"><i class="ph ph-clock" aria-hidden="true"></i><h3>{{ __('No runs yet') }}</h3><p>{{ __('Run a source from the Sources tab, or php artisan jobs:discover.') }}</p></td></tr>
                        @endforelse
                    </tbody>
                </table></div>
            </section>

            <section class="oc-panel je-span-2">
                <header class="oc-panel-heading"><div><h2>{{ __('Worker queues') }}</h2><p>{{ __('Jobs waiting to be processed. A number that keeps growing means the worker is not running.') }}</p></div></header>
                <div class="je-queues">
                    @foreach ($overview['queues'] as $queue => $count)
                        <div><span>{{ ['ingest' => 'Ingest (scraping)', 'ai' => 'AI (enrichment)', 'default' => __('Default (notifications, exports)')][$queue] }}</span><strong>{{ number_format($count) }}</strong><code>queue:work --queue={{ $queue }}</code></div>
                    @endforeach
                    <div class="{{ $overview['failedJobs'] ? 'is-bad' : '' }}"><span>{{ __('Failed in 24h') }}</span><strong>{{ number_format($overview['failedJobs']) }}</strong><code>queue:failed</code></div>
                </div>
            </section>
        </div>

    {{-- ================= Sources ================= --}}
    @elseif ($tab === 'sources')
        <section class="oc-panel">
            <header class="oc-panel-heading"><div><h2>{{ __('Job sources') }}</h2><p>{{ __('Discovery schedule adapts: busy sources are checked more often. Manual runs go to the ingest queue.') }}</p></div><span>{{ __(':count active', ['count' => $sources->where('is_active', true)->count()]) }}</span></header>
            <div class="oc-table-wrap"><table class="oc-table">
                <thead><tr><th>{{ __('Sources') }}</th><th>{{ __('Health') }}</th><th>{{ __('Last run') }}</th><th>{{ __('Schedule') }}</th><th>{{ __('Active jobs') }}</th><th><span class="sr-only">{{ __('Actions') }}</span></th></tr></thead>
                <tbody>
                    @foreach ($sources as $src)
                        @php $run = $lastRuns[$src->id] ?? null; @endphp
                        @php [$tone, $label] = $src->is_active ? ($healthBadge[$src->health] ?? ['unchecked', $src->health]) : ['unchecked', __('Inactive')]; @endphp
                        <tr wire:key="src-{{ $src->id }}">
                            <td><div class="je-source"><x-platform-brand :name="$src->name" :show-name="false" /><span class="oc-record-link"><strong>{{ $src->name }}</strong><small>{{ $src->slug }} · {{ __('tier :tier', ['tier' => $src->tier]) }} · {{ $src->kind }}</small></span></div></td>
                            <td><span class="oc-badge oc-badge--{{ $tone }}">{{ $label }}</span>@if ($src->health_reason)<small class="je-sub">{{ $src->health_reason }}</small>@endif</td>
                            <td>@if ($run){{ __(':count read', ['count' => $run->found]) }} · {{ __(':count new', ['count' => $run->new_listings]) }}<small class="je-sub">{{ $jkt($run->started_at)?->format('d M H:i') }} · {{ $run->requests }} request</small>@else<span class="je-sub">{{ __('Never') }}</span>@endif</td>
                            <td>{{ __('every :hours h', ['hours' => rtrim(rtrim(number_format($src->discovery_interval_minutes / 60, 1, ',', ''), '0'), ',')]) }}<small class="je-sub">{{ $src->next_discovery_at ? __('next :time', ['time' => $jkt($src->next_discovery_at)->format('d M H:i')]) : __('soon') }}</small></td>
                            <td>{{ number_format($activeCounts[$src->id] ?? 0) }}</td>
                            <td><div class="je-row-actions">
                                <button type="button" class="am-button" wire:click="runNow({{ $src->id }})" @disabled(! $src->is_active)><i class="ph ph-play" aria-hidden="true"></i>{{ __('Run') }}</button>
                                <button type="button" class="am-button" wire:click="toggle({{ $src->id }})">{{ $src->is_active ? __('Deactivate') : __('Activate') }}</button>
                            </div></td>
                        </tr>
                    @endforeach
                </tbody>
            </table></div>
            @if ($verifyRun)<p class="je-footnote"><i class="ph ph-info" aria-hidden="true"></i>{{ __('Last verification :time: :checked checked, :changed changed status, :blocked blocked.', ['time' => $jkt($verifyRun->started_at)->format('d M H:i'), 'checked' => $verifyRun->found, 'changed' => $verifyRun->updated_listings, 'blocked' => $verifyRun->failed_items]) }}</p>@endif
        </section>

    {{-- ================= Runs ================= --}}
    @elseif ($tab === 'runs')
        <form class="oc-filters je-filters je-filters--runs" wire:submit.prevent>
            <label class="am-filter"><span>{{ __('Sources') }}</span><select wire:model.live="runSource"><option value="">{{ __('All sources') }}</option>@foreach ($sources as $src)<option value="{{ $src->id }}">{{ $src->name }}</option>@endforeach</select></label>
            <label class="am-filter"><span>Status</span><select wire:model.live="runStatus"><option value="">{{ __('All statuses') }}</option><option value="success">{{ __('Success') }}</option><option value="partial">{{ __('Partial') }}</option><option value="failed">{{ __('Failed') }}</option><option value="running">{{ __('Running') }}</option></select></label>
        </form>
        <section class="oc-panel">
            <header class="oc-panel-heading"><div><h2>{{ __('Run history') }}</h2><p>{{ __('Every discovery and verification: how many were read, new, changed, failed, and how long it took.') }}</p></div><span>{{ __(':count runs', ['count' => $runs->total()]) }}</span></header>
            <div class="oc-table-wrap"><table class="oc-table">
                <thead><tr><th>{{ __('Sources') }}</th><th>Status</th><th>{{ __('Read') }}</th><th>{{ __('New') }}</th><th>{{ __('Changed') }}</th><th>{{ __('Failed') }}</th><th>{{ __('Duration') }}</th><th>{{ __('Started') }}</th></tr></thead>
                <tbody>
                    @forelse ($runs as $run)
                        @php [$tone, $label] = $runBadge[$run->status] ?? ['unchecked', $run->status]; @endphp
                        <tr wire:key="run-{{ $run->id }}">
                            <td><div class="je-source"><x-platform-brand :name="$run->source?->name" :show-name="false" /><span class="oc-record-link"><strong>{{ $run->source?->name ?? __('All sources') }}</strong><small>{{ $run->run_type === 'verify' ? __('Verification') : 'Discovery' }} · {{ $run->trigger }} · #{{ $run->id }}</small></span></div>
                                @if ($run->error_summary)<small class="je-error">{{ \Illuminate\Support\Str::limit($run->error_summary, 160) }}</small>@endif</td>
                            <td><span class="oc-badge oc-badge--{{ $tone }}">{{ $label }}</span></td>
                            <td>{{ number_format($run->found) }}</td>
                            <td><b>{{ number_format($run->new_listings) }}</b></td>
                            <td>{{ number_format($run->updated_listings) }}</td>
                            <td class="{{ $run->failed_items ? 'je-bad-text' : '' }}">{{ number_format($run->failed_items) }}</td>
                            <td>{{ $run->finished_at ? $run->started_at->diffInSeconds($run->finished_at).' '.__('sec') : '–' }}</td>
                            <td><time>{{ $jkt($run->started_at)?->format('d M H:i') }}</time></td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="oc-empty"><i class="ph ph-clock" aria-hidden="true"></i><h3>{{ __('No runs yet') }}</h3><p>{{ __('Change the filters, or run a source from the Sources tab.') }}</p></td></tr>
                    @endforelse
                </tbody>
            </table></div>
            @include('admin.operations.pagination', ['records' => $runs])
        </section>

    {{-- ================= Feed API ================= --}}
    @elseif ($tab === 'feed')
        <section class="oc-panel">
            <header class="oc-panel-heading"><div><h2>{{ __('Feed API clients') }}</h2><p>{{ __('Read-only keys for clients such as the asep bot.') }} {{ __('Endpoint') }} <code>{{ url('/api/v1/vacancies') }}</code> · {{ __('guide in') }} <code>docs/feed-api.md</code>.</p></div></header>
            <form class="je-feed-form" wire:submit="issueFeedKey">
                <label class="am-search"><i class="ph ph-key" aria-hidden="true"></i><span class="sr-only">{{ __('Client name') }}</span><input id="feed-name" wire:model="feedName" maxlength="80" placeholder="{{ __('Client name, e.g. asep') }}" required></label>
                <button type="submit" class="am-button am-button--primary"><i class="ph ph-plus" aria-hidden="true"></i>{{ __('Create key') }}</button>
                @error('feedName')<p class="am-field-error" role="alert">{{ $message }}</p>@enderror
            </form>
            @if ($issuedKey)<p class="je-key"><i class="ph ph-warning" aria-hidden="true"></i><span>{{ __('New key, copy it now (it will not be shown again):') }}</span><code>{{ $issuedKey }}</code></p>@endif
            <div class="oc-table-wrap"><table class="oc-table">
                <thead><tr><th>{{ __('Client') }}</th><th>{{ __('Key') }}</th><th>{{ __('Limit') }}</th><th>{{ __('Requests') }}</th><th>{{ __('Last used') }}</th><th><span class="sr-only">{{ __('Actions') }}</span></th></tr></thead>
                <tbody>
                    @forelse ($feedClients as $client)
                        <tr wire:key="feed-{{ $client->id }}">
                            <td><span class="oc-record-link"><strong>{{ $client->name }}</strong><small>{{ $client->is_active ? __('active') : __('inactive') }}</small></span></td>
                            <td><code>{{ $client->key_prefix }}…</code></td>
                            <td>{{ $client->per_minute }}/{{ __('min') }}</td>
                            <td>{{ number_format($client->requests, 0, ',', '.') }}</td>
                            <td><time>{{ $client->last_used_at?->locale(app()->getLocale())->diffForHumans() ?? '–' }}</time></td>
                            <td>@if ($client->is_active)<button type="button" class="am-button" wire:click="revokeFeedClient({{ $client->id }})" wire:confirm="{{ __('Deactivate the key for :name? Clients using it will stop receiving data.', ['name' => $client->name]) }}">{{ __('Deactivate') }}</button>@endif</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="oc-empty"><i class="ph ph-broadcast" aria-hidden="true"></i><h3>{{ __('No clients yet') }}</h3><p>{{ __('Create a key for the first client above.') }}</p></td></tr>
                    @endforelse
                </tbody>
            </table></div>
        </section>
    @endif
</div>
