@php
    $healthBadge = ['ok' => ['healthy', 'Sehat'], 'degraded' => ['medium', 'Menurun'], 'failing' => ['critical', 'Gagal'], 'paused' => ['unchecked', 'Dijeda']];
    $statusBadge = ['active' => ['healthy', 'Buka'], 'suspect' => ['medium', 'Dicurigai tutup'], 'closed' => ['critical', 'Tutup'], 'archived' => ['unchecked', 'Diarsipkan']];
    $runBadge = ['success' => ['healthy', 'Sukses'], 'partial' => ['medium', 'Sebagian'], 'failed' => ['critical', 'Gagal'], 'running' => ['monitoring', 'Berjalan']];
    $jkt = fn ($d) => $d?->copy()->timezone('Asia/Jakarta');
@endphp
<div class="oc-workspace je-workspace">
    <header class="am-page-heading">
        <div>
            <h1>Pantau Mesin</h1>
            <p>Mesin Data Loker: kesehatan scraper, antrean, biaya AI, dan kualitas data. Jadwal otomatis {{ $stats['schedule'] ? 'aktif' : 'nonaktif' }}.</p>
        </div>
        <div class="je-row-actions">
            <a href="{{ route('admin.vacancies') }}" class="am-button"><i class="ph ph-briefcase" aria-hidden="true"></i>Kelola Loker</a>
            <a href="{{ route('jobs.explore') }}" class="am-button" target="_blank" rel="noopener"><i class="ph ph-arrow-square-out" aria-hidden="true"></i>Lihat Explore</a>
        </div>
    </header>

    <section class="ac-stats oc-stats je-stats" aria-label="Ringkasan mesin">
        @foreach([
            [route('admin.vacancies', ['tab' => 'all', 'status' => 'active']), 'briefcase', 'Loker buka', number_format($stats['active'])],
            [route('admin.vacancies', ['tab' => 'all']), 'sparkle', 'Baru 24 jam', number_format($stats['new_24h'])],
            [route('admin.vacancies', ['tab' => 'all', 'status' => 'suspect']), 'warning-circle', 'Dicurigai tutup', number_format($stats['suspect'])],
            [route('admin.vacancies', ['tab' => 'pairs']), 'copy', 'Duplikat menunggu', number_format($stats['pending_pairs'])],
            [route('admin.vacancies', ['tab' => 'review', 'flag' => 'scam']), 'shield-warning', 'Dicurigai penipuan', number_format($stats['scam'])],
            [null, 'coins', 'AI hari ini', '$'.number_format($stats['ai_today'], 3)],
        ] as [$href, $icon, $label, $value])
            @if ($href)<a href="{{ $href }}" class="je-stat-link">@else<div class="je-stat-link">@endif
                <span class="ac-stat-icon"><i class="ph ph-{{ $icon }}" aria-hidden="true"></i></span>
                <span><span class="oc-stat-label">{{ $label }}</span><strong>{{ $value }}</strong></span>
            @if ($href)</a>@else</div>@endif
        @endforeach
    </section>

    <nav class="oc-tabs je-tabs" aria-label="Bagian mesin data">
        @foreach(['overview' => ['Ringkasan', 'gauge'], 'sources' => ['Sumber', 'plugs-connected'], 'runs' => ['Riwayat run', 'clock-counter-clockwise'], 'feed' => ['Feed API', 'broadcast']] as $key => [$label, $icon])
            <button type="button" wire:click="selectTab('{{ $key }}')" @if($tab === $key) aria-current="page" @endif><i class="ph ph-{{ $icon }}" aria-hidden="true"></i>{{ $label }}</button>
        @endforeach
    </nav>
    <div wire:loading.delay role="status" class="am-loading">Memuat data…</div>
    @if ($flash)<p class="oc-notice" role="status">{{ $flash }}</p>@endif

    {{-- ================= Overview ================= --}}
    @if ($tab === 'overview')
        <div class="je-grid">
            <section class="oc-panel je-span-2">
                <header class="oc-panel-heading"><div><h2>Perlu perhatian</h2><p>Hal yang membutuhkan tindakan admin, diurutkan dari yang paling mendesak.</p></div>
                    <div class="je-row-actions">
                        <button type="button" class="am-button" wire:click="verifyNow"><i class="ph ph-shield-check" aria-hidden="true"></i>Verifikasi sekarang</button>
                        <button type="button" class="am-button" wire:click="enrichPending"><i class="ph ph-sparkle" aria-hidden="true"></i>Rapikan yang belum</button>
                        <button type="button" class="am-button" wire:click="retryFailed" @disabled(! $overview['failedJobs'])><i class="ph ph-arrow-clockwise" aria-hidden="true"></i>Coba ulang job gagal</button>
                    </div></header>
                <ul class="je-alerts">
                    @forelse (collect($overview['alerts'])->sortBy(fn ($a) => ['bad' => 0, 'warn' => 1, 'info' => 2][$a[0]]) as [$level, $title, $detail])
                        <li class="je-alert je-alert--{{ $level }}"><i class="ph {{ ['bad' => 'ph-warning-octagon', 'warn' => 'ph-warning', 'info' => 'ph-info'][$level] }}" aria-hidden="true"></i><div><strong>{{ $title }}</strong><p>{{ $detail }}</p></div></li>
                    @empty
                        <li class="oc-empty"><i class="ph ph-check-circle" aria-hidden="true"></i><h3>Semua berjalan normal</h3><p>Tidak ada sumber bermasalah, antrean gagal, atau tinjauan yang tertunda.</p></li>
                    @endforelse
                </ul>
            </section>

            <section class="oc-panel">
                <header class="oc-panel-heading"><div><h2>Kualitas data</h2><p>Dari {{ number_format($stats['active']) }} loker yang tampil di Explore.</p></div></header>
                <div class="je-meters">
                    @foreach ($overview['coverage'] as $label => $ratio)
                        <div><span>{{ $label }}</span><strong>{{ round($ratio * 100) }}%</strong><div class="je-meter"><span style="width: {{ max(2, round($ratio * 100)) }}%"></span></div></div>
                    @endforeach
                </div>
            </section>

            <section class="oc-panel">
                <header class="oc-panel-heading"><div><h2>Biaya AI 7 hari</h2><p>Batas harian ${{ number_format($overview['budget'], 2) }}. Enrichment berhenti otomatis di batas.</p></div><span>${{ number_format($overview['spend']->sum('usd'), 3) }}</span></header>
                @php $max = max(0.0001, $overview['spend']->max('usd'), $overview['budget']); @endphp
                <div class="je-bars" role="img" aria-label="Biaya AI per hari selama 7 hari">
                    @foreach ($overview['spend'] as $day)
                        <div title="{{ \Illuminate\Support\Carbon::parse($day['day'])->locale('id')->translatedFormat('d M') }}: ${{ number_format($day['usd'], 4) }} · {{ $day['calls'] }} panggilan"><span style="height: {{ max(3, round($day['usd'] / $max * 100)) }}%"></span><small>{{ \Illuminate\Support\Carbon::parse($day['day'])->locale('id')->translatedFormat('D') }}</small></div>
                    @endforeach
                </div>
            </section>

            <section class="oc-panel je-span-2">
                <header class="oc-panel-heading"><div><h2>Run scraper terbaru</h2><p>Setiap discovery dan verifikasi tercatat di sini.</p></div><button type="button" class="oc-inline" wire:click="selectTab('runs')">Semua riwayat <i class="ph ph-arrow-right" aria-hidden="true"></i></button></header>
                <div class="oc-table-wrap"><table class="oc-table">
                    <thead><tr><th>Sumber</th><th>Jenis</th><th>Status</th><th>Hasil</th><th>Waktu</th></tr></thead>
                    <tbody>
                        @forelse ($overview['recentRuns'] as $run)
                            @php [$tone, $label] = $runBadge[$run->status] ?? ['unchecked', $run->status]; @endphp
                            <tr wire:key="ov-run-{{ $run->id }}">
                                <td><div class="je-source"><x-platform-brand :name="$run->source?->name" :show-name="false" /><strong>{{ $run->source?->name ?? 'Semua sumber' }}</strong></div></td>
                                <td>{{ $run->run_type === 'verify' ? 'Verifikasi' : 'Discovery' }}</td>
                                <td><span class="oc-badge oc-badge--{{ $tone }}">{{ $label }}</span></td>
                                <td>{{ $run->found }} dibaca · <b>{{ $run->new_listings }}</b> baru · {{ $run->updated_listings }} berubah @if($run->failed_items)<span class="je-bad-text">· {{ $run->failed_items }} gagal</span>@endif</td>
                                <td><time>{{ $jkt($run->started_at)?->locale('id')->diffForHumans() }}</time></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="oc-empty"><i class="ph ph-clock" aria-hidden="true"></i><h3>Belum ada run</h3><p>Jalankan sumber dari tab Sumber, atau php artisan jobs:discover.</p></td></tr>
                        @endforelse
                    </tbody>
                </table></div>
            </section>

            <section class="oc-panel je-span-2">
                <header class="oc-panel-heading"><div><h2>Antrean worker</h2><p>Job yang menunggu diproses. Angka yang terus naik berarti worker tidak berjalan.</p></div></header>
                <div class="je-queues">
                    @foreach ($overview['queues'] as $queue => $count)
                        <div><span>{{ ['ingest' => 'Ingest (scraping)', 'ai' => 'AI (enrichment)', 'default' => 'Default (notifikasi, ekspor)'][$queue] }}</span><strong>{{ number_format($count) }}</strong><code>queue:work --queue={{ $queue }}</code></div>
                    @endforeach
                    <div class="{{ $overview['failedJobs'] ? 'is-bad' : '' }}"><span>Gagal 24 jam</span><strong>{{ number_format($overview['failedJobs']) }}</strong><code>queue:failed</code></div>
                </div>
            </section>
        </div>

    {{-- ================= Sources ================= --}}
    @elseif ($tab === 'sources')
        <section class="oc-panel">
            <header class="oc-panel-heading"><div><h2>Sumber loker</h2><p>Jadwal discovery menyesuaikan diri: sumber yang ramai dicek lebih sering. Run manual masuk antrean ingest.</p></div><span>{{ $sources->where('is_active', true)->count() }} aktif</span></header>
            <div class="oc-table-wrap"><table class="oc-table">
                <thead><tr><th>Sumber</th><th>Kesehatan</th><th>Run terakhir</th><th>Jadwal</th><th>Loker aktif</th><th><span class="sr-only">Aksi</span></th></tr></thead>
                <tbody>
                    @foreach ($sources as $src)
                        @php $run = $lastRuns[$src->id] ?? null; @endphp
                        @php [$tone, $label] = $src->is_active ? ($healthBadge[$src->health] ?? ['unchecked', $src->health]) : ['unchecked', 'Nonaktif']; @endphp
                        <tr wire:key="src-{{ $src->id }}">
                            <td><div class="je-source"><x-platform-brand :name="$src->name" :show-name="false" /><span class="oc-record-link"><strong>{{ $src->name }}</strong><small>{{ $src->slug }} · tingkat {{ $src->tier }} · {{ $src->kind }}</small></span></div></td>
                            <td><span class="oc-badge oc-badge--{{ $tone }}">{{ $label }}</span>@if ($src->health_reason)<small class="je-sub">{{ $src->health_reason }}</small>@endif</td>
                            <td>@if ($run){{ $run->found }} dibaca · {{ $run->new_listings }} baru<small class="je-sub">{{ $jkt($run->started_at)?->format('d M H:i') }} · {{ $run->requests }} request</small>@else<span class="je-sub">Belum pernah</span>@endif</td>
                            <td>tiap {{ rtrim(rtrim(number_format($src->discovery_interval_minutes / 60, 1, ',', ''), '0'), ',') }} jam<small class="je-sub">{{ $src->next_discovery_at ? 'berikutnya '.$jkt($src->next_discovery_at)->format('d M H:i') : 'segera' }}</small></td>
                            <td>{{ number_format($activeCounts[$src->id] ?? 0) }}</td>
                            <td><div class="je-row-actions">
                                <button type="button" class="am-button" wire:click="runNow({{ $src->id }})" @disabled(! $src->is_active)><i class="ph ph-play" aria-hidden="true"></i>Jalankan</button>
                                <button type="button" class="am-button" wire:click="toggle({{ $src->id }})">{{ $src->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                            </div></td>
                        </tr>
                    @endforeach
                </tbody>
            </table></div>
            @if ($verifyRun)<p class="je-footnote"><i class="ph ph-info" aria-hidden="true"></i>Verifikasi terakhir {{ $jkt($verifyRun->started_at)->format('d M H:i') }}: {{ $verifyRun->found }} dicek, {{ $verifyRun->updated_listings }} berubah status, {{ $verifyRun->failed_items }} terblokir.</p>@endif
        </section>

    {{-- ================= Runs ================= --}}
    @elseif ($tab === 'runs')
        <form class="oc-filters je-filters je-filters--runs" wire:submit.prevent>
            <label class="am-filter"><span>Sumber</span><select wire:model.live="runSource"><option value="">Semua sumber</option>@foreach ($sources as $src)<option value="{{ $src->id }}">{{ $src->name }}</option>@endforeach</select></label>
            <label class="am-filter"><span>Status</span><select wire:model.live="runStatus"><option value="">Semua status</option><option value="success">Sukses</option><option value="partial">Sebagian</option><option value="failed">Gagal</option><option value="running">Berjalan</option></select></label>
        </form>
        <section class="oc-panel">
            <header class="oc-panel-heading"><div><h2>Riwayat run</h2><p>Setiap discovery dan verifikasi: berapa yang dibaca, baru, berubah, gagal, dan berapa lama.</p></div><span>{{ $runs->total() }} run</span></header>
            <div class="oc-table-wrap"><table class="oc-table">
                <thead><tr><th>Sumber</th><th>Status</th><th>Dibaca</th><th>Baru</th><th>Berubah</th><th>Gagal</th><th>Durasi</th><th>Mulai</th></tr></thead>
                <tbody>
                    @forelse ($runs as $run)
                        @php [$tone, $label] = $runBadge[$run->status] ?? ['unchecked', $run->status]; @endphp
                        <tr wire:key="run-{{ $run->id }}">
                            <td><div class="je-source"><x-platform-brand :name="$run->source?->name" :show-name="false" /><span class="oc-record-link"><strong>{{ $run->source?->name ?? 'Semua sumber' }}</strong><small>{{ $run->run_type === 'verify' ? 'Verifikasi' : 'Discovery' }} · {{ $run->trigger }} · #{{ $run->id }}</small></span></div>
                                @if ($run->error_summary)<small class="je-error">{{ \Illuminate\Support\Str::limit($run->error_summary, 160) }}</small>@endif</td>
                            <td><span class="oc-badge oc-badge--{{ $tone }}">{{ $label }}</span></td>
                            <td>{{ number_format($run->found) }}</td>
                            <td><b>{{ number_format($run->new_listings) }}</b></td>
                            <td>{{ number_format($run->updated_listings) }}</td>
                            <td class="{{ $run->failed_items ? 'je-bad-text' : '' }}">{{ number_format($run->failed_items) }}</td>
                            <td>{{ $run->finished_at ? $run->started_at->diffInSeconds($run->finished_at).' dtk' : '–' }}</td>
                            <td><time>{{ $jkt($run->started_at)?->format('d M H:i') }}</time></td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="oc-empty"><i class="ph ph-clock" aria-hidden="true"></i><h3>Belum ada run</h3><p>Ubah filter, atau jalankan sumber dari tab Sumber.</p></td></tr>
                    @endforelse
                </tbody>
            </table></div>
            @include('admin.operations.pagination', ['records' => $runs])
        </section>

    {{-- ================= Feed API ================= --}}
    @elseif ($tab === 'feed')
        <section class="oc-panel">
            <header class="oc-panel-heading"><div><h2>Klien Feed API</h2><p>Kunci baca-saja untuk klien seperti bot asep. Endpoint <code>{{ url('/api/v1/vacancies') }}</code> · panduan di <code>docs/feed-api.md</code>.</p></div></header>
            <form class="je-feed-form" wire:submit="issueFeedKey">
                <label class="am-search"><i class="ph ph-key" aria-hidden="true"></i><span class="sr-only">Nama klien</span><input id="feed-name" wire:model="feedName" maxlength="80" placeholder="Nama klien, mis. asep" required></label>
                <button type="submit" class="am-button am-button--primary"><i class="ph ph-plus" aria-hidden="true"></i>Buat kunci</button>
                @error('feedName')<p class="am-field-error" role="alert">{{ $message }}</p>@enderror
            </form>
            @if ($issuedKey)<p class="je-key"><i class="ph ph-warning" aria-hidden="true"></i><span>Kunci baru, salin sekarang (tidak ditampilkan lagi):</span><code>{{ $issuedKey }}</code></p>@endif
            <div class="oc-table-wrap"><table class="oc-table">
                <thead><tr><th>Klien</th><th>Kunci</th><th>Batas</th><th>Request</th><th>Terakhir dipakai</th><th><span class="sr-only">Aksi</span></th></tr></thead>
                <tbody>
                    @forelse ($feedClients as $client)
                        <tr wire:key="feed-{{ $client->id }}">
                            <td><span class="oc-record-link"><strong>{{ $client->name }}</strong><small>{{ $client->is_active ? 'aktif' : 'nonaktif' }}</small></span></td>
                            <td><code>{{ $client->key_prefix }}…</code></td>
                            <td>{{ $client->per_minute }}/menit</td>
                            <td>{{ number_format($client->requests, 0, ',', '.') }}</td>
                            <td><time>{{ $client->last_used_at?->locale('id')->diffForHumans() ?? '–' }}</time></td>
                            <td>@if ($client->is_active)<button type="button" class="am-button" wire:click="revokeFeedClient({{ $client->id }})" wire:confirm="Nonaktifkan kunci {{ $client->name }}? Klien yang memakainya akan berhenti mendapat data.">Nonaktifkan</button>@endif</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="oc-empty"><i class="ph ph-broadcast" aria-hidden="true"></i><h3>Belum ada klien</h3><p>Buat kunci untuk klien pertama di atas.</p></td></tr>
                    @endforelse
                </tbody>
            </table></div>
        </section>
    @endif
</div>
