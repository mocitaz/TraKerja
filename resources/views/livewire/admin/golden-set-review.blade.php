<div class="gs-review">
    <style>
        .gs-review { display: grid; gap: 16px; }
        .gs-head { display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 12px; }
        .gs-head h1 { margin: 0; font-size: 22px; font-weight: 700; }
        .gs-head p { margin: 4px 0 0; color: #6b6880; font-size: 13px; max-width: 70ch; }
        .gs-progress { display: flex; align-items: center; gap: 10px; font-size: 13px; color: #4a4759; }
        .gs-bar { width: 180px; height: 8px; border-radius: 99px; background: #ebe9f1; overflow: hidden; }
        .gs-bar span { display: block; height: 100%; background: #2b1d4a; }
        .gs-tabs { display: flex; gap: 6px; }
        .gs-tabs button { border: 1px solid #e2e0ea; background: #fff; border-radius: 99px; padding: 7px 14px; font-size: 13px; font-weight: 600; color: #4a4759; cursor: pointer; }
        .gs-tabs button[aria-pressed="true"] { background: #2b1d4a; border-color: #2b1d4a; color: #fff; }
        .gs-layout { display: grid; grid-template-columns: 300px minmax(0, 1fr); gap: 16px; align-items: start; }
        .gs-panel { background: #fff; border: 1px solid #ebe9f1; border-radius: 14px; }
        .gs-list-filters { display: grid; gap: 8px; padding: 12px; border-bottom: 1px solid #ebe9f1; }
        .gs-review select, .gs-review input[type=text], .gs-review textarea { width: 100%; border: 1px solid #dcd9e6; border-radius: 9px; padding: 7px 9px; font-size: 13px; background: #fff; color: #16141f; }
        .gs-list { max-height: 70vh; overflow-y: auto; list-style: none; margin: 0; padding: 6px; }
        .gs-list button { width: 100%; text-align: left; border: 0; background: transparent; border-radius: 9px; padding: 8px 10px; cursor: pointer; display: grid; grid-template-columns: 10px minmax(0, 1fr); gap: 8px; align-items: start; }
        .gs-list button:hover { background: #f6f5f9; }
        .gs-list button[aria-current="true"] { background: #ecebfb; }
        .gs-dot { width: 8px; height: 8px; border-radius: 50%; margin-top: 5px; background: #d9a441; }
        .gs-dot.is-done { background: #17845a; }
        .gs-list strong { display: block; font-size: 13px; font-weight: 600; color: #16141f; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .gs-list small { display: block; font-size: 12px; color: #8a8798; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .gs-detail { padding: 18px; display: grid; gap: 14px; }
        .gs-detail-head { display: flex; flex-wrap: wrap; justify-content: space-between; gap: 10px; align-items: flex-start; }
        .gs-detail-head h2 { margin: 0; font-size: 18px; font-weight: 700; }
        .gs-detail-head p { margin: 3px 0 0; color: #6b6880; font-size: 13px; }
        .gs-desc { max-height: 240px; overflow-y: auto; white-space: pre-line; font-size: 13px; line-height: 1.55; color: #3d3a4c; background: #f8f7fb; border-radius: 10px; padding: 12px 14px; }
        .gs-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .gs-table th { text-align: left; font-size: 11px; letter-spacing: .06em; text-transform: uppercase; color: #8a8798; padding: 6px 8px; border-bottom: 1px solid #ebe9f1; }
        .gs-table td { padding: 6px 8px; border-bottom: 1px solid #f1eff6; vertical-align: middle; }
        .gs-table td:first-child { font-weight: 600; color: #3d3a4c; white-space: nowrap; width: 150px; }
        .gs-table td:last-child { width: 45%; }
        .gs-sugg { display: flex; align-items: center; gap: 6px; color: #5d5a70; min-width: 0; }
        .gs-sugg span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 200px; }
        .gs-sugg button { border: 1px solid #dcd9e6; background: #fff; border-radius: 7px; padding: 2px 8px; font-size: 12px; cursor: pointer; flex-shrink: 0; }
        .gs-sugg.is-diff span { color: #a3168b; font-weight: 600; }
        .gs-err { color: #c23a3a; font-size: 12px; margin-top: 3px; }
        .gs-actions { display: flex; flex-wrap: wrap; gap: 8px; justify-content: flex-end; }
        .gs-flash { color: #17845a; font-size: 13px; }
        .gs-empty { padding: 32px; text-align: center; color: #6b6880; }
        .gs-dups { display: grid; gap: 12px; }
        .gs-dup { padding: 14px 16px; display: grid; gap: 10px; }
        .gs-dup-items { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 10px; }
        .gs-dup-items div { background: #f8f7fb; border-radius: 10px; padding: 10px 12px; font-size: 13px; }
        @media (max-width: 900px) { .gs-layout { grid-template-columns: minmax(0, 1fr); } .gs-list { max-height: 260px; } }
    </style>

    <header class="gs-head">
        <div>
            <h1>Golden Set Review</h1>
            <p>Bandingkan draf dari data terstruktur dengan saran AI, isi nilai yang benar, lalu tandai direview. Hanya label yang direview yang dipakai untuk mengukur mesin data.</p>
        </div>
        <div class="gs-progress">
            <div class="gs-bar" aria-hidden="true"><span style="width: {{ $totals['all'] ? round($totals['reviewed'] / $totals['all'] * 100) : 0 }}%"></span></div>
            <span>{{ $totals['reviewed'] }} / {{ $totals['all'] }} direview</span>
        </div>
    </header>

    <div class="gs-tabs" role="group" aria-label="Bagian">
        <button type="button" wire:click="$set('tab', 'labels')" aria-pressed="{{ $tab === 'labels' ? 'true' : 'false' }}">Label lowongan</button>
        <button type="button" wire:click="$set('tab', 'duplicates')" aria-pressed="{{ $tab === 'duplicates' ? 'true' : 'false' }}">Kandidat duplikat ({{ count($duplicates) }})</button>
    </div>

    @if ($totals['all'] === 0)
        <div class="gs-panel gs-empty">Golden set masih kosong. Jalankan <code>php artisan jobs:golden-collect</code> di mesin lokal.</div>
    @elseif ($tab === 'duplicates')
        <div class="gs-dups">
            @forelse ($duplicates as $i => $pair)
                <article class="gs-panel gs-dup" wire:key="dup-{{ $i }}">
                    <div class="gs-dup-items">
                        @foreach ($pair['items'] as $item)
                            <div><strong>{{ $item['label']['title'] }}</strong><br>{{ $item['label']['company'] }} · {{ $item['source'] }}<br><a href="{{ $item['url'] }}" target="_blank" rel="noopener">Buka sumber</a></div>
                        @endforeach
                    </div>
                    <div class="gs-actions">
                        @if ($pair['reviewed'] ?? false)
                            <span class="am-badge {{ $pair['is_duplicate'] ? 'am-badge--success' : '' }}">{{ $pair['is_duplicate'] ? 'Duplikat' : 'Bukan duplikat' }}</span>
                        @endif
                        <button type="button" class="am-button" wire:click="markDuplicate({{ $i }}, false)">Bukan duplikat</button>
                        <button type="button" class="am-button am-button--primary" wire:click="markDuplicate({{ $i }}, true)">Ya, loker yang sama</button>
                    </div>
                </article>
            @empty
                <div class="gs-panel gs-empty">Tidak ada kandidat duplikat.</div>
            @endforelse
        </div>
    @else
        <div class="gs-layout">
            <aside class="gs-panel">
                <div class="gs-list-filters">
                    <label class="sr-only" for="gs-source">Sumber</label>
                    <select id="gs-source" wire:model.live="source">
                        <option value="">Semua sumber</option>
                        @foreach ($sources as $name => $count)
                            <option value="{{ $name }}">{{ $name }} ({{ $count['reviewed'] }}/{{ $count['total'] }})</option>
                        @endforeach
                    </select>
                    <label class="sr-only" for="gs-status">Status</label>
                    <select id="gs-status" wire:model.live="status">
                        <option value="pending">Belum direview</option>
                        <option value="reviewed">Sudah direview</option>
                        <option value="all">Semua</option>
                    </select>
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
                        <li class="gs-empty">Tidak ada sampel untuk filter ini.</li>
                    @endforelse
                </ul>
            </aside>

            <section class="gs-panel gs-detail">
                @if (! $current)
                    <div class="gs-empty">Pilih sampel di sebelah kiri.</div>
                @else
                    <div class="gs-detail-head">
                        <div>
                            <h2>{{ $current['label']['title'] ?? '(tanpa judul)' }}</h2>
                            <p>{{ $current['source'] }} · {{ $current['id'] }} @if($current['reviewed'] ?? false) · <span class="am-badge am-badge--success">Direview</span>@endif</p>
                        </div>
                        <a class="am-button" href="{{ $current['url'] }}" target="_blank" rel="noopener"><i class="ph ph-arrow-square-out" aria-hidden="true"></i> Buka sumber asli</a>
                    </div>

                    @if ($description !== '')
                        <div class="gs-desc">{{ $description }}</div>
                    @endif

                    <div style="overflow-x:auto">
                        <table class="gs-table">
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
                                                <span style="color:#b3b0c2">–</span>
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
                        <label for="gs-notes" style="font-size:13px;font-weight:600">Catatan review</label>
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
