<div class="je">
    <style>
        .je { display: grid; gap: 16px; }
        .je h1 { margin: 0; font-size: 22px; font-weight: 700; }
        .je-sub { margin: 4px 0 0; color: #6b6880; font-size: 13px; max-width: 75ch; }
        .je-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px; }
        .je-stat { background: #fff; border: 1px solid #ebe9f1; border-radius: 12px; padding: 12px 14px; }
        .je-stat b { display: block; font-size: 22px; font-weight: 700; color: #16141f; font-variant-numeric: tabular-nums; }
        .je-stat span { font-size: 12px; color: #6b6880; }
        .je-tabs { display: flex; flex-wrap: wrap; gap: 6px; }
        .je-tabs button { border: 1px solid #e2e0ea; background: #fff; border-radius: 99px; padding: 7px 14px; font-size: 13px; font-weight: 600; color: #4a4759; cursor: pointer; }
        .je-tabs button[aria-pressed="true"] { background: #2b1d4a; border-color: #2b1d4a; color: #fff; }
        .je-panel { background: #fff; border: 1px solid #ebe9f1; border-radius: 14px; overflow-x: auto; }
        .je table { width: 100%; border-collapse: collapse; font-size: 13px; min-width: 820px; }
        .je th { text-align: left; font-size: 11px; letter-spacing: .06em; text-transform: uppercase; color: #8a8798; padding: 10px 12px; border-bottom: 1px solid #ebe9f1; background: #faf9fc; }
        .je td { padding: 10px 12px; border-bottom: 1px solid #f1eff6; vertical-align: top; }
        .je-pill { display: inline-block; font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 99px; background: #f1eff6; color: #4a4759; white-space: nowrap; }
        .je-ok { background: #e3f5ec; color: #17845a; } .je-warn { background: #fdf1dc; color: #a66400; } .je-bad { background: #fbe6e6; color: #c23a3a; }
        .je-muted { color: #8a8798; font-size: 12px; }
        .je-actions { display: flex; flex-wrap: wrap; gap: 6px; }
        .je-pair { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; padding: 14px 16px; border-bottom: 1px solid #f1eff6; }
        .je-pair > div { background: #f8f7fb; border-radius: 10px; padding: 10px 12px; font-size: 13px; min-width: 0; }
        .je-pair .je-actions { grid-column: 1 / -1; justify-content: flex-end; align-items: center; }
        .je-flash { color: #17845a; font-size: 13px; }
        .je-empty { padding: 28px; text-align: center; color: #6b6880; }
        .je textarea, .je input[type=text] { width: 100%; border: 1px solid #dcd9e6; border-radius: 8px; padding: 6px 8px; font-size: 12px; }
        @media (max-width: 720px) { .je-pair { grid-template-columns: 1fr; } }
    </style>

    <header>
        <h1>Mesin Data Loker</h1>
        <p class="je-sub">Kesehatan sumber, jadwal adaptif, tinjauan duplikat, dan QA mingguan. Jadwal otomatis {{ $stats['schedule'] ? 'aktif' : 'nonaktif (JOB_ENGINE_SCHEDULE_ENABLED=false)' }}.</p>
    </header>

    <div class="je-stats">
        <div class="je-stat"><b>{{ number_format($stats['active']) }}</b><span>Loker aktif</span></div>
        <div class="je-stat"><b>{{ number_format($stats['suspect']) }}</b><span>Dicurigai tutup</span></div>
        <div class="je-stat"><b>{{ number_format($stats['closed_24h']) }}</b><span>Ditutup 24 jam terakhir</span></div>
        <div class="je-stat"><b>{{ number_format($stats['pending_pairs']) }}</b><span>Duplikat menunggu tinjauan</span></div>
        <div class="je-stat"><b>{{ number_format($stats['scam']) }}</b><span>Dicurigai penipuan</span></div>
        <div class="je-stat"><b>${{ number_format($stats['ai_today'], 3) }}</b><span>Biaya AI hari ini (batas ${{ number_format($stats['ai_budget'], 2) }})</span></div>
    </div>

    <div class="je-tabs" role="group" aria-label="Bagian">
        <button type="button" wire:click="$set('tab', 'sources')" aria-pressed="{{ $tab === 'sources' ? 'true' : 'false' }}">Sumber</button>
        <button type="button" wire:click="$set('tab', 'pairs')" aria-pressed="{{ $tab === 'pairs' ? 'true' : 'false' }}">Tinjauan duplikat ({{ $stats['pending_pairs'] }})</button>
        <button type="button" wire:click="$set('tab', 'qa')" aria-pressed="{{ $tab === 'qa' ? 'true' : 'false' }}">QA mingguan</button>
    </div>
    @if ($flash)<p class="je-flash" role="status">{{ $flash }}</p>@endif

    @if ($tab === 'sources')
        <div class="je-panel">
            <table>
                <thead><tr><th>Sumber</th><th>Kesehatan</th><th>Run terakhir</th><th>Jadwal</th><th>Loker aktif</th><th></th></tr></thead>
                <tbody>
                    @foreach ($sources as $source)
                        @php($run = $lastRuns[$source->id] ?? null)
                        <tr wire:key="src-{{ $source->id }}">
                            <td><strong>{{ $source->name }}</strong><div class="je-muted">{{ $source->slug }} · tingkat {{ $source->tier }} · {{ $source->kind }}</div></td>
                            <td>
                                @if (! $source->is_active)<span class="je-pill">Nonaktif</span>
                                @else<span class="je-pill {{ ['ok' => 'je-ok', 'degraded' => 'je-warn', 'failing' => 'je-bad'][$source->health] ?? '' }}">{{ ['ok' => 'Sehat', 'degraded' => 'Menurun', 'failing' => 'Gagal', 'paused' => 'Dijeda'][$source->health] ?? $source->health }}</span>@endif
                                @if ($source->health_reason)<div class="je-muted">{{ $source->health_reason }}</div>@endif
                            </td>
                            <td>
                                @if ($run)
                                    {{ $run->found }} ditemukan, {{ $run->new_listings }} baru
                                    <div class="je-muted">{{ $run->started_at?->timezone('Asia/Jakarta')->format('d M H:i') }} · {{ $run->status }} · {{ $run->requests }} request</div>
                                @else <span class="je-muted">Belum pernah</span> @endif
                            </td>
                            <td>tiap {{ round($source->discovery_interval_minutes / 60, 1) }} jam<div class="je-muted">{{ $source->next_discovery_at ? 'berikutnya '.$source->next_discovery_at->timezone('Asia/Jakarta')->format('d M H:i') : 'segera' }}</div></td>
                            <td>{{ number_format($activeCounts[$source->id] ?? 0) }}</td>
                            <td><div class="je-actions">
                                <button type="button" class="am-button" wire:click="runNow({{ $source->id }})" @disabled(! $source->is_active)>Jalankan</button>
                                <button type="button" class="am-button" wire:click="toggle({{ $source->id }})">{{ $source->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                            </div></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($verifyRun)<p class="je-muted">Verifikasi terakhir {{ $verifyRun->started_at->timezone('Asia/Jakarta')->format('d M H:i') }}: {{ $verifyRun->found }} dicek, {{ $verifyRun->updated_listings }} berubah status, {{ $verifyRun->failed_items }} terblokir.</p>@endif

    @elseif ($tab === 'pairs')
        <div class="je-panel">
            @forelse ($pairs as $pair)
                <div class="je-pair" wire:key="pair-{{ $pair['review']->id }}">
                    <div>
                        <span class="je-pill">{{ $pair['listing']->source?->name }}</span>
                        <div style="font-weight:600;margin-top:6px">{{ $pair['listing']->vacancy->title }}</div>
                        <div class="je-muted">{{ $pair['listing']->vacancy->company?->name }} · {{ $pair['listing']->vacancy->region?->name ?? $pair['listing']->vacancy->location_text ?? 'lokasi tidak diketahui' }}</div>
                        <a href="{{ $pair['listing']->url }}" target="_blank" rel="noopener" class="je-muted">Buka sumber</a>
                    </div>
                    <div>
                        <span class="je-pill">{{ $pair['candidate']->listings->pluck('source.name')->filter()->unique()->implode(', ') }}</span>
                        <div style="font-weight:600;margin-top:6px">{{ $pair['candidate']->title }}</div>
                        <div class="je-muted">{{ $pair['candidate']->company?->name }} · {{ $pair['candidate']->region?->name ?? $pair['candidate']->location_text ?? 'lokasi tidak diketahui' }}</div>
                        @if ($pair['candidate']->listings->first())<a href="{{ $pair['candidate']->listings->first()->url }}" target="_blank" rel="noopener" class="je-muted">Buka sumber</a>@endif
                    </div>
                    <div class="je-actions">
                        <span class="je-muted">Skor judul {{ number_format($pair['detail']['title'] ?? $pair['review']->score, 2) }}@if(isset($pair['detail']['description'])) · deskripsi {{ number_format($pair['detail']['description'], 2) }}@endif</span>
                        <button type="button" class="am-button" wire:click="resolvePair({{ $pair['review']->id }}, false)">Pisahkan</button>
                        <button type="button" class="am-button am-button--primary" wire:click="resolvePair({{ $pair['review']->id }}, true)">Gabung, loker yang sama</button>
                    </div>
                </div>
            @empty
                <div class="je-empty">Tidak ada pasangan yang menunggu tinjauan.</div>
            @endforelse
        </div>

    @else
        <div class="je-actions" style="justify-content:space-between;align-items:center">
            <span class="je-muted">Sampel minggu {{ $sample }}: {{ $qa->whereNotNull('verdict')->count() }} / {{ $qa->count() }} diperiksa. Buka sumbernya, lalu nilai apakah data dan status di TraKerja benar.</span>
            <button type="button" class="am-button am-button--primary" wire:click="drawQaSample">Ambil sampel minggu ini</button>
        </div>
        <div class="je-panel">
            <table>
                <thead><tr><th>Loker</th><th>Status</th><th>Data</th><th>Penilaian</th></tr></thead>
                <tbody>
                    @forelse ($qa as $check)
                        @php($v = $qaVacancies[$check->vacancy_id] ?? null)
                        @continue(! $v)
                        <tr wire:key="qa-{{ $check->id }}">
                            <td><strong>{{ $v->title }}</strong><div class="je-muted">{{ $v->company?->name }}</div>
                                @foreach ($v->listings as $l)<a href="{{ $l->url }}" target="_blank" rel="noopener" class="je-muted">{{ $l->source?->name }}</a> @endforeach</td>
                            <td><span class="je-pill {{ $v->status === 'active' ? 'je-ok' : 'je-bad' }}">{{ $v->status }}</span>@if($v->closed_reason)<div class="je-muted">{{ $v->closed_reason }}</div>@endif</td>
                            <td class="je-muted">{{ $v->region?->name ?? $v->location_text ?? '–' }} · {{ $v->employment_type ?? '–' }} · {{ $v->seniority ?? '–' }}<br>
                                @if ($v->salary_min){{ $v->salary_currency }} {{ number_format($v->salary_min, 0, ',', '.') }}@if($v->salary_max) – {{ number_format($v->salary_max, 0, ',', '.') }}@endif / {{ $v->salary_period }}@else gaji – @endif</td>
                            <td style="min-width:260px">
                                @if ($check->verdict)
                                    <span class="je-pill {{ $check->verdict === 'correct' ? 'je-ok' : 'je-warn' }}">{{ $verdicts[$check->verdict] }}</span>@if($check->note)<div class="je-muted">{{ $check->note }}</div>@endif
                                @else
                                    <input type="text" wire:model="qaNotes.{{ $check->id }}" placeholder="Catatan (opsional)" aria-label="Catatan untuk {{ $v->title }}">
                                    <div class="je-actions" style="margin-top:6px">
                                        @foreach ($verdicts as $key => $label)<button type="button" class="am-button" wire:click="judge({{ $check->id }}, '{{ $key }}')">{{ $key === 'correct' ? 'Benar' : \Illuminate\Support\Str::before($label, ' (') }}</button>@endforeach
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="je-empty">Belum ada sampel minggu ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>
