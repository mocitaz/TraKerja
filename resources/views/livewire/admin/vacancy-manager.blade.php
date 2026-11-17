@php
    $statusBadge = ['active' => ['healthy', 'Buka'], 'suspect' => ['medium', 'Dicurigai tutup'], 'closed' => ['critical', 'Tutup'], 'archived' => ['unchecked', 'Diarsipkan']];
    $reasons = \App\Livewire\Admin\VacancyManager::REASONS;
    $jkt = fn ($d) => $d?->copy()->timezone('Asia/Jakarta');
@endphp
<div class="oc-workspace je-workspace vm-workspace">
    <header class="am-page-heading">
        <div>
            <h1>Kelola Loker</h1>
            <p>Rapikan katalog loker: tinjau yang janggal, arsipkan, cek ulang, gabung duplikat, dan nilai sampel QA. Perubahan langsung terlihat di Explore.</p>
        </div>
        <div class="je-row-actions">
            <a href="{{ route('admin.job-engine') }}" class="am-button"><i class="ph ph-gauge" aria-hidden="true"></i>Pantau Mesin</a>
            <a href="{{ route('admin.promotions') }}" class="am-button am-button--primary"><i class="ph ph-megaphone" aria-hidden="true"></i>Promosi Threads</a>
        </div>
    </header>

    <section class="ac-stats oc-stats vm-stats" aria-label="Ringkasan katalog">
        @foreach([
            ['all', 'briefcase', 'Loker buka', $counts['open']],
            ['reported', 'flag', 'Dilaporkan pengguna', $counts['reported']],
            ['scam', 'shield-warning', 'Dicurigai penipuan', $counts['scam']],
            ['pairs', 'copy', 'Duplikat menunggu', $counts['pairs']],
            ['archived', 'archive', 'Diarsipkan', $counts['archived']],
        ] as [$key, $icon, $label, $value])
            <button type="button" wire:click="{{ in_array($key, ['all', 'pairs']) ? "selectTab('{$key}')" : ($key === 'archived' ? "\$set('status', 'archived'); \$set('tab', 'all')" : "\$set('flag', '{$key}'); \$set('tab', 'review')") }}" aria-label="{{ $label }} · lihat">
                <span class="ac-stat-icon"><i class="ph ph-{{ $icon }}" aria-hidden="true"></i></span>
                <span><span class="oc-stat-label">{{ $label }}</span><strong>{{ number_format($value) }}</strong></span>
            </button>
        @endforeach
    </section>

    <nav class="oc-tabs je-tabs" aria-label="Bagian kelola loker">
        @foreach(['review' => ['Perlu dicek', 'list-checks'], 'all' => ['Semua loker', 'briefcase'], 'pairs' => ['Duplikat ('.$counts['pairs'].')', 'copy'], 'qa' => ['QA mingguan', 'check-square-offset']] as $key => [$label, $icon])
            <button type="button" wire:click="selectTab('{{ $key }}')" @if($tab === $key) aria-current="page" @endif><i class="ph ph-{{ $icon }}" aria-hidden="true"></i>{{ $label }}</button>
        @endforeach
    </nav>
    <div wire:loading.delay role="status" class="am-loading">Memuat data…</div>
    @if ($flash)<p class="oc-notice" role="status">{{ $flash }}</p>@endif

    @if (in_array($tab, ['review', 'all'], true))
        <form class="oc-filters je-filters {{ $tab === 'review' ? 'vm-filters--review' : '' }}" wire:submit.prevent>
            @if ($tab === 'all')
                <label class="am-search"><i class="ph ph-magnifying-glass" aria-hidden="true"></i><span class="sr-only">Cari</span><input type="search" wire:model.live.debounce.300ms="search" placeholder="Judul, perusahaan, #ID, atau ID publik"></label>
                <label class="am-filter"><span>Status</span><select wire:model.live="status"><option value="open">Buka + dicurigai</option><option value="active">Buka</option><option value="suspect">Dicurigai tutup</option><option value="closed">Tutup</option><option value="archived">Diarsipkan</option><option value="all">Semua</option></select></label>
                <label class="am-filter"><span>Sumber</span><select wire:model.live="source"><option value="">Semua sumber</option>@foreach ($sources as $src)<option value="{{ $src->id }}">{{ $src->name }}</option>@endforeach</select></label>
            @endif
            <label class="am-filter"><span>{{ $tab === 'review' ? 'Alasan' : 'Perlu dicek' }}</span><select wire:model.live="flag"><option value="">Semua</option>@foreach ($reasons as $key => [$label]) @if ($tab === 'review' || in_array($key, ['reported', 'scam', 'region', 'raw'], true))<option value="{{ $key }}">{{ $label }}</option>@endif @endforeach</select></label>
        </form>

        @if ($selected)
            <div class="vm-bulk" role="region" aria-label="Aksi massal">
                <strong>{{ count($selected) }} dipilih</strong>
                <button type="button" class="am-button" wire:click="bulk('checked')"><i class="ph ph-check" aria-hidden="true"></i>Tandai sudah dicek</button>
                <button type="button" class="am-button" wire:click="bulk('recheck')"><i class="ph ph-arrows-clockwise" aria-hidden="true"></i>Cek ulang</button>
                <button type="button" class="am-button" wire:click="bulk('scam')" wire:confirm="Tandai {{ count($selected) }} loker sebagai dicurigai penipuan?"><i class="ph ph-shield-warning" aria-hidden="true"></i>Tandai penipuan</button>
                <button type="button" class="am-button" wire:click="bulk('archive')" wire:confirm="Arsipkan {{ count($selected) }} loker?"><i class="ph ph-archive" aria-hidden="true"></i>Arsipkan</button>
                <button type="button" class="oc-inline" wire:click="$set('selected', [])">Batal</button>
            </div>
        @endif

        <section class="oc-panel">
            <header class="oc-panel-heading">
                <div>
                    <h2>{{ $tab === 'review' ? 'Perlu dicek' : 'Semua loker' }}</h2>
                    <p>{{ $tab === 'review' ? 'Loker yang janggal menurut aturan otomatis, laporan pengguna paling atas. Tandai sudah dicek bila sudah benar; loker tidak muncul lagi kecuali dilaporkan ulang.' : 'Arsipkan menyembunyikan loker dari Explore, halaman publik, dan feed. Loker arsip tidak hidup lagi walau sumbernya masih memuatnya.' }}</p>
                </div>
                @if ($tab === 'all')<button type="button" class="am-button" wire:click="archiveOldClosed" wire:confirm="Arsipkan semua loker yang sudah tutup lebih dari 30 hari?"><i class="ph ph-archive" aria-hidden="true"></i>Arsipkan tutup &gt; 30 hari</button>@else<span>{{ $rows->total() }} loker</span>@endif
            </header>
            <div class="oc-table-wrap"><table class="oc-table je-vacancy-table vm-table">
                <thead><tr><th class="vm-check"><span class="sr-only">Pilih</span></th><th>Loker</th><th>{{ $tab === 'review' ? 'Alasan' : 'Sumber' }}</th><th>Lokasi</th><th>Status</th><th><span class="sr-only">Aksi</span></th></tr></thead>
                <tbody>
                    @forelse ($rows as $v)
                        @php [$tone, $label] = $statusBadge[$v->status] ?? ['unchecked', $v->status]; @endphp
                        <tr wire:key="vm-{{ $tab }}-{{ $v->id }}" @class(['is-selected' => in_array((string) $v->id, $selected, true)])>
                            <td class="vm-check"><input type="checkbox" value="{{ $v->id }}" wire:model.live="selected" aria-label="Pilih {{ $v->title }}"></td>
                            <td><div class="je-vacancy">
                                <span class="je-logo">@if ($v->company?->logo_url)<img src="{{ $v->company->logo_url }}" alt="" width="32" height="32" loading="lazy" referrerpolicy="no-referrer" onerror="this.remove()">@endif<i class="ph ph-buildings" aria-hidden="true"></i></span>
                                <button type="button" class="oc-record-link" wire:click="open({{ $v->id }})"><strong>{{ $v->title }}</strong><small>{{ $v->company?->name ?? 'Perusahaan tidak diketahui' }} · #{{ $v->id }}@if($v->salary_label) · {{ $v->salary_label }}@endif</small></button>
                            </div></td>
                            <td>
                                @if ($tab === 'review')
                                    <div class="vm-reasons">@foreach ($v->review_reasons as $r)<span class="oc-badge {{ in_array($r, ['reported', 'scam']) ? 'oc-badge--critical' : '' }}"><i class="ph ph-{{ $reasons[$r][1] }}" aria-hidden="true"></i>{{ $reasons[$r][0] }}</span>@endforeach</div>
                                @else
                                    <div class="je-source-list">@foreach ($v->listings->unique('job_source_id') as $l)<a href="{{ $l->url }}" target="_blank" rel="noopener noreferrer" title="Buka di {{ $l->source?->name }}"><x-platform-brand :name="$l->source?->name" :show-name="false" /></a>@endforeach</div>
                                @endif
                            </td>
                            <td>{{ $v->region?->name ?? ($v->location_text ? \Illuminate\Support\Str::limit($v->location_text, 26) : '–') }}</td>
                            <td><span class="oc-badge oc-badge--{{ $tone }}">{{ $label }}</span>@if ($v->scam_flag === 'suspected' && $tab === 'all')<span class="oc-badge oc-badge--critical">Penipuan?</span>@endif</td>
                            <td><div class="je-row-actions">
                                @if ($v->status !== 'archived')
                                    @if ($tab === 'review')<button type="button" class="am-button" wire:click="markChecked({{ $v->id }})"><i class="ph ph-check" aria-hidden="true"></i>Sudah dicek</button>@endif
                                    <button type="button" class="am-button am-button--icon" wire:click="recheck({{ $v->id }})" title="Cek ulang sekarang" aria-label="Cek ulang {{ $v->title }}"><i class="ph ph-arrows-clockwise" aria-hidden="true"></i></button>
                                    <a class="am-button am-button--icon" href="{{ route('admin.promotions', ['vacancy' => $v->id]) }}" title="Buat promosi Threads" aria-label="Promosikan {{ $v->title }}"><i class="ph ph-megaphone" aria-hidden="true"></i></a>
                                    <button type="button" class="am-button am-button--icon" wire:click="archive({{ $v->id }})" wire:confirm="Arsipkan loker ini? Loker hilang dari Explore dan halaman publik." title="Arsipkan" aria-label="Arsipkan {{ $v->title }}"><i class="ph ph-archive" aria-hidden="true"></i></button>
                                @else
                                    <button type="button" class="am-button" wire:click="restore({{ $v->id }})"><i class="ph ph-arrow-counter-clockwise" aria-hidden="true"></i>Pulihkan</button>
                                @endif
                                <button type="button" class="am-button am-button--icon" wire:click="open({{ $v->id }})" title="Detail" aria-label="Detail {{ $v->title }}"><i class="ph ph-sidebar-simple" aria-hidden="true"></i></button>
                            </div></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="oc-empty"><i class="ph ph-check-circle" aria-hidden="true"></i><h3>{{ $tab === 'review' ? 'Tidak ada yang perlu dicek' : 'Tidak ada loker' }}</h3><p>{{ $tab === 'review' ? 'Semua loker lolos aturan otomatis.' : 'Coba kata kunci atau filter lain.' }}</p></td></tr>
                    @endforelse
                </tbody>
            </table></div>
            @include('admin.operations.pagination', ['records' => $rows])
        </section>
    {{-- ================= Duplicate review ================= --}}
    @elseif ($tab === 'pairs')
        <section class="oc-panel">
            <header class="oc-panel-heading"><div><h2>Tinjauan duplikat</h2><p>Pasangan yang terlalu mirip untuk diputuskan otomatis. Gabung bila keduanya lowongan yang sama; gabungan bisa dibatalkan dengan jobs:unmerge.</p></div><span>{{ $pairs->count() }} pasangan</span></header>
            @forelse ($pairs as $pair)
                <article class="je-pair" wire:key="pair-{{ $pair['review']->id }}">
                    @foreach ([[$pair['listing']->vacancy, $pair['listing']->source?->name, $pair['listing']->url], [$pair['candidate'], $pair['candidate']->listings->pluck('source.name')->filter()->unique()->implode(', '), $pair['candidate']->listings->first()?->url]] as [$item, $where, $link])
                        <div class="je-pair-side">
                            <span class="oc-badge">{{ $where }}</span>
                            <strong>{{ $item->title }}</strong>
                            <small>{{ $item->company?->name }} · {{ $item->region?->name ?? $item->location_text ?? 'lokasi tidak diketahui' }}</small>
                            @if ($link)<a class="oc-inline" href="{{ $link }}" target="_blank" rel="noopener">Buka sumber <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>@endif
                        </div>
                    @endforeach
                    <footer>
                        <span>Kemiripan judul {{ number_format($pair['detail']['title'] ?? $pair['review']->score, 2) }}@if(isset($pair['detail']['description'])) · deskripsi {{ number_format($pair['detail']['description'], 2) }}@endif</span>
                        <div class="je-row-actions">
                            <button type="button" class="am-button" wire:click="resolvePair({{ $pair['review']->id }}, false)">Pisahkan</button>
                            <button type="button" class="am-button am-button--primary" wire:click="resolvePair({{ $pair['review']->id }}, true)">Gabung, loker yang sama</button>
                        </div>
                    </footer>
                </article>
            @empty
                <div class="oc-empty"><i class="ph ph-check-circle" aria-hidden="true"></i><h3>Tidak ada pasangan tertunda</h3><p>Duplikat baru yang ragu-ragu akan muncul di sini.</p></div>
            @endforelse
        </section>

    {{-- ================= Weekly QA ================= --}}
    @elseif ($tab === 'qa')
        <section class="oc-panel">
            <header class="oc-panel-heading"><div><h2>QA mingguan · {{ $sample }}</h2><p>{{ $qa->whereNotNull('verdict')->count() }} dari {{ $qa->count() }} diperiksa. Buka sumbernya, lalu nilai apakah data dan status di TraKerja benar.</p></div>
                <button type="button" class="am-button am-button--primary" wire:click="drawQaSample"><i class="ph ph-shuffle" aria-hidden="true"></i>Ambil sampel minggu ini</button></header>
            <div class="oc-table-wrap"><table class="oc-table">
                <thead><tr><th>Loker</th><th>Status</th><th>Data</th><th>Penilaian</th></tr></thead>
                <tbody>
                    @forelse ($qa as $check)
                        @php $v = $qaVacancies[$check->vacancy_id] ?? null; @endphp
                        @continue(! $v)
                        @php [$tone, $label] = $statusBadge[$v->status] ?? ['unchecked', $v->status]; @endphp
                        <tr wire:key="qa-{{ $check->id }}">
                            <td><span class="oc-record-link"><strong>{{ $v->title }}</strong><small>{{ $v->company?->name }}</small></span>
                                <div class="je-source-list">@foreach ($v->listings as $l)<a href="{{ $l->url }}" target="_blank" rel="noopener" title="{{ $l->source?->name }}"><x-platform-brand :name="$l->source?->name" :show-name="false" /></a>@endforeach</div></td>
                            <td><span class="oc-badge oc-badge--{{ $tone }}">{{ $label }}</span>@if($v->closed_reason)<small class="je-sub">{{ $v->closed_reason }}</small>@endif</td>
                            <td>{{ $v->region?->name ?? $v->location_text ?? '–' }}<small class="je-sub">{{ $v->employment_type ?? '–' }} · {{ $v->seniority ?? '–' }} · {{ $v->salary_label ?? 'gaji –' }}</small></td>
                            <td class="je-qa-cell">
                                @if ($check->verdict)
                                    <span class="oc-badge oc-badge--{{ $check->verdict === 'correct' ? 'healthy' : 'medium' }}">{{ $verdicts[$check->verdict] }}</span>@if($check->note)<small class="je-sub">{{ $check->note }}</small>@endif
                                @else
                                    <label class="am-search je-note"><input type="text" wire:model="qaNotes.{{ $check->id }}" placeholder="Catatan (opsional)" aria-label="Catatan untuk {{ $v->title }}"></label>
                                    <div class="je-row-actions">@foreach ($verdicts as $key => $vlabel)<button type="button" class="am-button {{ $key === 'correct' ? 'am-button--primary' : '' }}" wire:click="judge({{ $check->id }}, '{{ $key }}')">{{ $key === 'correct' ? 'Benar' : \Illuminate\Support\Str::before($vlabel, ' (') }}</button>@endforeach</div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="oc-empty"><i class="ph ph-check-square-offset" aria-hidden="true"></i><h3>Belum ada sampel minggu ini</h3><p>Ambil sampel 20 loker buka dan 5 loker yang baru ditutup.</p></td></tr>
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
                    <button type="button" class="am-button am-button--icon" wire:click="closeDetail" aria-label="Tutup"><i class="ph ph-x" aria-hidden="true"></i></button>
                </header>
                <div class="vm-drawer-body">
                    @if ($detail['reasons'])<div class="vm-reasons">@foreach ($detail['reasons'] as $r)<span class="oc-badge {{ in_array($r, ['reported', 'scam']) ? 'oc-badge--critical' : '' }}"><i class="ph ph-{{ $reasons[$r][1] }}" aria-hidden="true"></i>{{ $reasons[$r][0] }}</span>@endforeach</div>@endif
                    <dl class="vm-facts">
                        <div><dt>Lokasi</dt><dd>{{ $v->region?->name ?? $v->location_text ?? '–' }}</dd></div>
                        <div><dt>Gaji</dt><dd>{{ $v->salary_label ?? '–' }}</dd></div>
                        <div><dt>Sektor</dt><dd>{{ $v->sector?->name ?? '–' }}</dd></div>
                        <div><dt>Level · tipe</dt><dd>{{ $v->seniority ?? '–' }} · {{ $v->employment_type ?? '–' }}</dd></div>
                        <div><dt>Pertama dilihat</dt><dd>{{ $jkt($v->first_seen_at)?->format('d M Y H:i') ?? '–' }}</dd></div>
                        <div><dt>Terakhir dicek</dt><dd>{{ $jkt($v->last_verified_at)?->format('d M Y H:i') ?? '–' }}</dd></div>
                    </dl>
                    <section><h3>Listing ({{ $v->listings->count() }})</h3>
                        <ul class="vm-list">@foreach ($v->listings as $l)<li><x-platform-brand :name="$l->source?->name" :show-name="false" /><a href="{{ $l->url }}" target="_blank" rel="noopener noreferrer">{{ $l->source?->name }}</a><span class="oc-badge oc-badge--{{ $statusBadge[$l->status][0] ?? 'unchecked' }}">{{ $statusBadge[$l->status][1] ?? $l->status }}</span><small>{{ $l->match_method }}</small></li>@endforeach</ul>
                    </section>
                    <section><h3>Riwayat cek</h3>
                        @forelse ($detail['checks'] as $c)
                            <p class="vm-line"><span class="oc-badge oc-badge--{{ ['open' => 'healthy', 'closed' => 'critical', 'suspect' => 'medium'][$c->verdict] ?? 'unchecked' }}">{{ $c->verdict }}</span> HTTP {{ $c->http_status ?? '–' }} · {{ \Illuminate\Support\Carbon::parse($c->checked_at)->timezone('Asia/Jakarta')->format('d M H:i') }} @if($c->signals && $c->signals !== '[]')<small>{{ implode(', ', (array) json_decode($c->signals, true)) }}</small>@endif</p>
                        @empty<p class="vm-muted">Belum pernah dicek verifier.</p>@endforelse
                    </section>
                    @if ($detail['reports']->isNotEmpty())
                        <section><h3>Laporan pengguna</h3>@foreach ($detail['reports'] as $r)<p class="vm-line">{{ $r->reason }} · {{ \Illuminate\Support\Carbon::parse($r->created_at)->timezone('Asia/Jakarta')->format('d M H:i') }} @if($r->resolved_at)<span class="oc-badge oc-badge--healthy">selesai</span>@endif</p>@endforeach</section>
                    @endif
                    @if ($detail['merged']->isNotEmpty())
                        <section><h3>Digabung ke loker ini</h3>@foreach ($detail['merged'] as $m)<p class="vm-line"><span>{{ $m->title }} (#{{ $m->id }})</span><button type="button" class="oc-inline" wire:click="unmerge({{ $m->id }})" wire:confirm="Pisahkan kembali #{{ $m->id }}?">Pisahkan</button></p>@endforeach</section>
                    @endif
                    @if ($detail['promotions']->isNotEmpty())
                        <section><h3>Promosi</h3>@foreach ($detail['promotions'] as $p)<p class="vm-line">{{ $p->headline }} · {{ $p->status === 'posted' ? 'sudah diposting' : 'draf' }}</p>@endforeach</section>
                    @endif
                </div>
                <footer class="vm-drawer-foot">
                    <a class="am-button" href="{{ parse_url($v->public_path, PHP_URL_PATH) }}" target="_blank" rel="noopener"><i class="ph ph-arrow-square-out" aria-hidden="true"></i>Halaman publik</a>
                    @if ($v->status !== 'archived')
                        <button type="button" class="am-button" wire:click="toggleScam({{ $v->id }})"><i class="ph {{ $v->scam_flag === 'suspected' ? 'ph-shield-check' : 'ph-shield-warning' }}" aria-hidden="true"></i>{{ $v->scam_flag === 'suspected' ? 'Bukan penipuan' : 'Tandai penipuan' }}</button>
                        <a class="am-button" href="{{ route('admin.promotions', ['vacancy' => $v->id]) }}"><i class="ph ph-megaphone" aria-hidden="true"></i>Promosikan</a>
                        <button type="button" class="am-button am-button--primary" wire:click="markChecked({{ $v->id }})"><i class="ph ph-check" aria-hidden="true"></i>Sudah dicek</button>
                    @else
                        <button type="button" class="am-button am-button--primary" wire:click="restore({{ $v->id }})">Pulihkan</button>
                    @endif
                </footer>
            </aside>
        </div>
    @endif
</div>
