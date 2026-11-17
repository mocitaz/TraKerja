<div class="oc-workspace gs-review">

    <header class="am-page-heading">
        <div>
            <h1>Golden Set Review</h1>
            <p>Bandingkan draf dari data terstruktur dengan saran AI, isi nilai yang benar, lalu tandai direview. Hanya label yang direview yang dipakai untuk mengukur akurasi mesin data.</p>
        </div>
        <a href="{{ route('admin.job-engine') }}" class="am-button"><i class="ph ph-gauge" aria-hidden="true"></i>Mesin Data Loker</a>
    </header>

    <section class="ac-stats oc-stats gs-stats" aria-label="Progres golden set">
        <article><span class="ac-stat-icon"><i class="ph ph-stack" aria-hidden="true"></i></span><span><span class="oc-stat-label">Total sampel</span><strong>{{ number_format($totals['all']) }}</strong></span></article>
        <article><span class="ac-stat-icon"><i class="ph ph-check-circle" aria-hidden="true"></i></span><span><span class="oc-stat-label">Sudah direview</span><strong>{{ number_format($totals['reviewed']) }}</strong></span></article>
        <article><span class="ac-stat-icon"><i class="ph ph-hourglass-medium" aria-hidden="true"></i></span><span><span class="oc-stat-label">Belum direview</span><strong>{{ number_format($totals['all'] - $totals['reviewed']) }}</strong></span></article>
        <article class="gs-goal"><span class="ac-stat-icon"><i class="ph ph-flag-checkered" aria-hidden="true"></i></span><span><span class="oc-stat-label">Syarat lulus Fase 3: 200 label</span><strong>{{ min(100, round($totals['reviewed'] / 2)) }}%</strong><span class="gs-bar" aria-hidden="true"><span style="width: {{ min(100, round($totals['reviewed'] / 2)) }}%"></span></span></span></article>
    </section>

    <nav class="oc-tabs" aria-label="Bagian golden set">
        <button type="button" wire:click="$set('tab', 'labels')" @if($tab === 'labels') aria-current="page" @endif><i class="ph ph-tag" aria-hidden="true"></i>Label lowongan</button>
        <button type="button" wire:click="$set('tab', 'duplicates')" @if($tab === 'duplicates') aria-current="page" @endif><i class="ph ph-copy" aria-hidden="true"></i>Kandidat duplikat ({{ count($duplicates) }})</button>
    </nav>
    <div wire:loading.delay role="status" class="am-loading">Memuat data…</div>

    @if ($totals['all'] === 0)
        <section class="oc-panel"><div class="oc-empty"><i class="ph ph-stack" aria-hidden="true"></i><h3>Golden set masih kosong</h3><p>Jalankan <code>php artisan jobs:golden-collect</code> di mesin lokal untuk mengambil sampel.</p></div></section>
    @elseif ($tab === 'duplicates')
        <div class="gs-dups">
            @forelse ($duplicates as $i => $pair)
                <article class="oc-panel gs-dup" wire:key="dup-{{ $i }}">
                    <div class="gs-dup-items">
                        @foreach ($pair['items'] as $item)
                            <div><span class="oc-badge">{{ $item['source'] }}</span><strong>{{ $item['label']['title'] }}</strong><small>{{ $item['label']['company'] }}</small><a class="oc-inline" href="{{ $item['url'] }}" target="_blank" rel="noopener">Buka sumber <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a></div>
                        @endforeach
                    </div>
                    <div class="gs-actions">
                        @if ($pair['reviewed'] ?? false)
                            <span class="oc-badge {{ $pair['is_duplicate'] ? 'oc-badge--healthy' : '' }}">{{ $pair['is_duplicate'] ? 'Duplikat' : 'Bukan duplikat' }}</span>
                        @endif
                        <button type="button" class="am-button" wire:click="markDuplicate({{ $i }}, false)">Bukan duplikat</button>
                        <button type="button" class="am-button am-button--primary" wire:click="markDuplicate({{ $i }}, true)">Ya, loker yang sama</button>
                    </div>
                </article>
            @empty
                <section class="oc-panel"><div class="oc-empty"><i class="ph ph-check-circle" aria-hidden="true"></i><h3>Tidak ada kandidat duplikat</h3><p>Pasangan yang perlu dinilai akan muncul di sini.</p></div></section>
            @endforelse
        </div>
    @else
        <div class="gs-layout">
            <aside class="oc-panel gs-side">
                <div class="gs-list-filters">
                    <label class="am-filter"><span>Sumber</span><select id="gs-source" wire:model.live="source">
                        <option value="">Semua sumber</option>
                        @foreach ($sources as $name => $count)
                            <option value="{{ $name }}">{{ $name }} ({{ $count['reviewed'] }}/{{ $count['total'] }})</option>
                        @endforeach
                    </select></label>
                    <label class="am-filter"><span>Status</span><select id="gs-status" wire:model.live="status">
                        <option value="pending">Belum direview</option>
                        <option value="reviewed">Sudah direview</option>
                        <option value="all">Semua</option>
                    </select></label>
                </div>
                <ul class="gs-list">
                    @forelse ($rows as $row)
                        <li wire:key="row-{{ $row['id'] }}">
                            <button type="button" wire:click="select('{{ $row['id'] }}')" aria-current="{{ $current && $current['id'] === $row['id'] ? 'true' : 'false' }}">
                                <span class="gs-dot {{ ($row['reviewed'] ?? false) ? 'is-done' : '' }}" aria-hidden="true"></span>
                                <span><strong>{{ $row['label']['title'] ?? '(tanpa judul)' }}</strong><small>{{ $row['label']['company'] ?? '-' }} · {{ $row['source'] }}</small></span>
                            </button>
                        </li>
                    @empty
                        <li class="oc-empty"><p>Tidak ada sampel untuk filter ini.</p></li>
                    @endforelse
                </ul>
            </aside>

            <section class="oc-panel gs-detail">
                @if (! $current)
                    <div class="oc-empty"><i class="ph ph-cursor-click" aria-hidden="true"></i><h3>Pilih sampel</h3><p>Pilih lowongan di daftar kiri untuk mulai mereview.</p></div>
                @else
                    <div class="gs-detail-head">
                        <div>
                            <h2>{{ $current['label']['title'] ?? '(tanpa judul)' }}</h2>
                            <p>{{ $current['source'] }} · {{ $current['id'] }} @if($current['reviewed'] ?? false) · <span class="oc-badge oc-badge--healthy">Direview</span>@endif</p>
                        </div>
                        <a class="am-button" href="{{ $current['url'] }}" target="_blank" rel="noopener"><i class="ph ph-arrow-square-out" aria-hidden="true"></i> Buka sumber asli</a>
                    </div>

                    @if ($description !== '')
                        <div class="gs-desc">{{ $description }}</div>
                    @endif

                    <div class="oc-table-wrap gs-table-wrap">
                        <table class="oc-table gs-table">
                            <thead><tr><th>Field</th><th>Saran AI (gpt-5-nano)</th><th>Nilai final</th></tr></thead>
                            <tbody>
                                @foreach ($fields as $field => $label)
                                    @php
                                        $suggested = $current['suggestion'][$field] ?? null;
                                        $hasSuggestion = array_key_exists($field, $current['suggestion'] ?? []);
                                        $suggestedText = is_bool($suggested) ? ($suggested ? 'ya' : 'tidak') : (string) ($suggested ?? '–');
                                        $currentText = (string) ($form[$field] ?? '');
                                        $isDiff = $hasSuggestion && $suggested !== null && $suggestedText !== $currentText
                                            && ! (is_bool($suggested) && $currentText === ($suggested ? '1' : '0'));
                                    @endphp
                                    <tr wire:key="f-{{ $current['id'] }}-{{ $field }}">
                                        <td><label for="gs-{{ $field }}">{{ $label }}</label></td>
                                        <td>
                                            @if ($hasSuggestion)
                                                <div class="gs-sugg {{ $isDiff ? 'is-diff' : '' }}">
                                                    <span title="{{ $suggestedText }}">{{ $suggestedText }}</span>
                                                    @if ($isDiff)<button type="button" wire:click="useSuggestion('{{ $field }}')">Pakai</button>@endif
                                                </div>
                                            @else
                                                <span class="gs-none">–</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if (in_array($field, ['is_internship', 'is_open'], true))
                                                <select id="gs-{{ $field }}" wire:model="form.{{ $field }}"><option value="">Tidak diketahui</option><option value="1">Ya</option><option value="0">Tidak</option></select>
                                            @elseif (isset($options[$field]))
                                                <select id="gs-{{ $field }}" wire:model="form.{{ $field }}"><option value="">–</option>@foreach ($options[$field] as $opt)<option value="{{ $opt }}">{{ $opt }}</option>@endforeach</select>
                                            @else
                                                <input id="gs-{{ $field }}" type="text" wire:model="form.{{ $field }}" @if(in_array($field, ['date_posted','valid_through'], true)) placeholder="YYYY-MM-DD" @endif>
                                            @endif
                                            @error("form.$field")<div class="gs-err">{{ $message }}</div>@enderror
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div>
                        <label for="gs-notes" class="gs-notes-label">Catatan review</label>
                        <textarea id="gs-notes" rows="2" wire:model="notes" placeholder="Misalnya: URL dipakai ulang, judul tidak cocok dengan slug"></textarea>
                    </div>

                    <div class="gs-actions">
                        @if ($flash)<span class="gs-flash" role="status">{{ $flash }}</span>@endif
                        <button type="button" class="am-button" wire:click="fillEmptyFromSuggestion">Isi yang kosong dari saran AI</button>
                        <button type="button" class="am-button" wire:click="skip">Lewati</button>
                        <button type="button" class="am-button am-button--primary" wire:click="save" wire:loading.attr="disabled" wire:target="save">Simpan &amp; berikutnya</button>
                    </div>
                @endif
            </section>
        </div>
    @endif
</div>
