@php
    $icons = ['threads' => 'threads-logo', 'instagram' => 'instagram-logo', 'linkedin' => 'linkedin-logo', 'x' => 'x-logo', 'whatsapp' => 'whatsapp-logo'];
    $placeholders = ['threads' => 'https://www.threads.net/@trakerja/post/…', 'instagram' => 'https://www.instagram.com/p/…', 'linkedin' => 'https://www.linkedin.com/posts/…', 'x' => 'https://x.com/trakerja/status/…', 'whatsapp' => 'Tautan channel/grup (opsional)'];
    $tips = [
        'threads' => 'Maks 500 karakter, link bisa diklik. Unggah pamflet Feed.',
        'instagram' => 'Link di caption tidak bisa diklik, jadi caption mengarahkan ke link di bio. Pasang link loker di bio atau pakai Story + stiker link.',
        'linkedin' => 'Nada profesional tanpa bahasa gaul. Unggah pamflet Feed.',
        'x' => 'Maks 280 karakter; setiap link dihitung 23 karakter.',
        'whatsapp' => 'Untuk Channel/grup WhatsApp dan Telegram. *Teks* jadi tebal. Kirim pamflet sebagai gambar lalu tempel caption.',
    ];
@endphp
<div class="oc-workspace je-workspace pm-workspace">
    <header class="am-page-heading">
        <div>
            <h1>Promosi Media Sosial</h1>
            <p>Pilih loker yang masih buka, buat caption untuk Threads, Instagram, LinkedIn, X, dan WhatsApp/Telegram dengan AI, lalu unduh pamflet Feed atau Story.</p>
        </div>
        <a href="{{ route('admin.vacancies') }}" class="am-button"><i class="ph ph-briefcase" aria-hidden="true"></i>Kelola Loker</a>
    </header>
    <div wire:loading.delay wire:target="generate,save,togglePosted,choose" role="status" class="am-loading">Memproses…</div>
    @if ($flash)<p class="oc-notice" role="status">{{ $flash }}</p>@endif

    <div class="pm-layout">
        <div class="pm-main">
            {{-- Pick a vacancy --}}
            <section class="oc-panel">
                <header class="oc-panel-heading"><div><h2>1. Pilih loker</h2><p>Hanya loker yang masih buka. Saran: terbaru, ada gaji atau logo, belum dipromosikan 14 hari terakhir.</p></div></header>
                <div class="pm-pick">
                    @if ($vacancy)
                        <div class="pm-selected" wire:key="selected-{{ $vacancy->id }}">
                            <span class="je-logo">@if ($vacancy->company?->logo_url)<img src="{{ $vacancy->company->logo_url }}" alt="" referrerpolicy="no-referrer" onerror="this.remove()">@endif<i class="ph ph-buildings" aria-hidden="true"></i></span>
                            <span><small>Loker terpilih</small><strong>{{ $vacancy->title }}</strong><em>{{ $vacancy->company?->name }}{{ $vacancy->location ? ' · '.$vacancy->location : '' }}{{ $vacancy->salary_label ? ' · '.$vacancy->salary_label : '' }}</em></span>
                            <a class="am-button am-button--icon" href="{{ $vacancy->public_path }}" target="_blank" rel="noopener" aria-label="Buka halaman loker"><i class="ph ph-arrow-square-out" aria-hidden="true"></i></a>
                            <button type="button" class="am-button" wire:click="clearVacancy">Ganti</button>
                        </div>
                    @endif
                    <label class="am-search"><i class="ph ph-magnifying-glass" aria-hidden="true"></i><span class="sr-only">Cari loker</span><input type="search" wire:model.live.debounce.250ms="search" placeholder="{{ $vacancy ? 'Cari loker lain' : 'Cari posisi atau perusahaan' }}" autocomplete="off"></label>
                    @php $options = trim($search) !== '' ? $results : $suggestions; @endphp
                    @if (trim($search) === '' && $options->isNotEmpty())<p class="pm-caption">{{ $vacancy ? 'Saran lain' : 'Saran' }}</p>@endif
                    <ul class="pm-options" wire:loading.class="is-busy" wire:target="search,choose">
                        @foreach ($options as $item)
                            <li wire:key="opt-{{ $search }}-{{ $item->id }}"><button type="button" wire:click="choose({{ $item->id }})" @class(['is-current' => $vacancyId === $item->id])>
                                <span class="je-logo">@if ($item->company?->logo_url)<img src="{{ $item->company->logo_url }}" alt="" referrerpolicy="no-referrer" loading="lazy" onerror="this.remove()">@endif<i class="ph ph-buildings" aria-hidden="true"></i></span>
                                <span><strong>{{ $item->title }}</strong><small>{{ $item->company?->name }}{{ $item->location ? ' · '.$item->location : '' }}{{ $item->salary_label ? ' · '.$item->salary_label : '' }}</small></span>
                            </button></li>
                        @endforeach
                        @if (mb_strlen(trim($search)) === 1)<li class="pm-none">Ketik minimal 2 huruf.</li>
                        @elseif (trim($search) !== '' && $results->isEmpty())<li class="pm-none">Tidak ada loker buka yang cocok.</li>@endif
                    </ul>
                </div>
            </section>

            @if ($vacancy)
                {{-- Copy editor --}}
                <section class="oc-panel" id="pm-editor">
                    <header class="oc-panel-heading">
                        <div><h2>2. Judul &amp; caption</h2><p>Satu klik AI menulis caption untuk 5 platform sesuai gayanya. AI hanya memakai fakta dari data loker; tetap periksa sebelum posting.</p></div>
                        <button type="button" class="am-button am-button--primary" wire:click="generate" wire:loading.attr="disabled" wire:target="generate"><i class="ph ph-sparkle" aria-hidden="true"></i><span wire:loading.remove wire:target="generate">Buat dengan AI</span><span wire:loading wire:target="generate">Menulis…</span></button>
                    </header>
                    <div class="pm-form">
                        <label class="pm-field"><span>Judul di pamflet</span><input type="text" wire:model.live.debounce.400ms="headline" maxlength="80">@error('headline')<p class="am-field-error">{{ $message }}</p>@enderror</label>

                        <div class="pm-platforms" role="tablist" aria-label="Platform">
                            @foreach ($platforms as $key => [$label])
                                <button type="button" role="tab" aria-selected="{{ $platform === $key ? 'true' : 'false' }}" wire:click="setPlatform('{{ $key }}')" @class(['is-current' => $platform === $key])>
                                    <i class="ph ph-{{ $icons[$key] }}" aria-hidden="true"></i>{{ $label }}
                                    @if (isset($postedOn[$key]))<i class="ph-fill ph-check-circle pm-posted-dot" aria-label="sudah diposting"></i>@endif
                                </button>
                            @endforeach
                        </div>

                        <label class="pm-field" wire:key="caption-{{ $platform }}"><span>Caption {{ $platforms[$platform][0] }} <em class="{{ $captionLength > $limit ? 'is-over' : '' }}">{{ number_format($captionLength, 0, ',', '.') }}/{{ number_format($limit, 0, ',', '.') }}</em></span>
                            <textarea rows="{{ $platform === 'x' ? 5 : 10 }}" wire:model.live.debounce.400ms="captions.{{ $platform }}"></textarea>
                            <p class="pm-tip"><i class="ph ph-info" aria-hidden="true"></i>{{ $tips[$platform] }}</p>
                            @error('captions.'.$platform)<p class="am-field-error">{{ $message }}</p>@enderror
                        </label>
                        <div class="pm-actions">
                            <button type="button" class="am-button" wire:click="save"><i class="ph ph-floppy-disk" aria-hidden="true"></i>Simpan draf</button>
                            <button type="button" class="am-button am-button--primary" x-data x-on:click="navigator.clipboard.writeText($wire.captions[$wire.platform] || '').then(() => { $el.querySelector('span').textContent = 'Tersalin ✓'; setTimeout(() => $el.querySelector('span').textContent = 'Salin caption', 1600) })"><i class="ph ph-copy" aria-hidden="true"></i><span>Salin caption</span></button>
                        </div>
                    </div>
                </section>

                {{-- Mark posted --}}
                <section class="oc-panel">
                    <header class="oc-panel-heading"><div><h2>3. Sudah diposting di {{ $platforms[$platform][0] }}?</h2><p>Tandai per platform. Tempel tautan post (opsional) supaya riwayat promosi rapi.</p></div></header>
                    <div class="pm-posted" wire:key="posted-{{ $platform }}">
                        <label class="am-search"><i class="ph ph-link" aria-hidden="true"></i><span class="sr-only">Tautan post {{ $platforms[$platform][0] }}</span><input type="url" wire:model="postUrls.{{ $platform }}" placeholder="{{ $placeholders[$platform] }}" @disabled(isset($postedOn[$platform]))></label>
                        @if (isset($postedOn[$platform]))
                            <button type="button" class="am-button" wire:click="togglePosted"><i class="ph ph-arrow-counter-clockwise" aria-hidden="true"></i>Batalkan tanda</button>
                        @else
                            <button type="button" class="am-button am-button--primary" wire:click="togglePosted"><i class="ph ph-check-circle" aria-hidden="true"></i>Tandai sudah diposting</button>
                        @endif
                    </div>
                    @error('postUrls.'.$platform)<p class="am-field-error pm-pad">{{ $message }}</p>@enderror
                    <ul class="pm-status">
                        @foreach ($platforms as $key => [$label])
                            <li @class(['is-done' => isset($postedOn[$key])])><i class="ph{{ isset($postedOn[$key]) ? '-fill' : '' }} ph-{{ $icons[$key] }}" aria-hidden="true"></i>{{ $label }}<small>{{ isset($postedOn[$key]) ? \Illuminate\Support\Carbon::parse($postedOn[$key])->locale('id')->diffForHumans() : 'Belum' }}</small></li>
                        @endforeach
                    </ul>
                </section>
            @endif

            {{-- History --}}
            <section class="oc-panel">
                <header class="oc-panel-heading"><div><h2>Riwayat promosi</h2><p>Draf dan post terbaru.</p></div></header>
                <div class="oc-table-wrap"><table class="oc-table">
                    <thead><tr><th>Loker</th><th>Platform</th><th>Diperbarui</th><th><span class="sr-only">Aksi</span></th></tr></thead>
                    <tbody>
                        @forelse ($history as $p)
                            <tr wire:key="hist-{{ $p->id }}">
                                <td><span class="oc-record-link"><strong>{{ $p->headline }}</strong><small>{{ $p->vacancy_title }}</small></span></td>
                                @php $on = array_keys((array) json_decode((string) $p->posted_on, true)) ?: ($p->status === 'posted' ? ['threads'] : []); @endphp
                                <td>@if ($on)<span class="pm-history-platforms">@foreach ($on as $key)<i class="ph ph-{{ $icons[$key] ?? 'share-network' }}" title="{{ $platforms[$key][0] ?? $key }}" aria-label="{{ $platforms[$key][0] ?? $key }}"></i>@endforeach</span>@else<span class="oc-badge oc-badge--unchecked">Draf</span>@endif</td>
                                <td><time>{{ \Illuminate\Support\Carbon::parse($p->updated_at)->locale('id')->diffForHumans() }}</time></td>
                                <td><div class="je-row-actions">
                                    @if ($p->post_url)<a class="am-button am-button--icon" href="{{ $p->post_url }}" target="_blank" rel="noopener" aria-label="Buka post"><i class="ph ph-arrow-square-out" aria-hidden="true"></i></a>@endif
                                    <button type="button" class="am-button" wire:click="choose({{ $p->vacancy_id }})">Buka</button>
                                </div></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="oc-empty"><i class="ph ph-megaphone" aria-hidden="true"></i><h3>Belum ada promosi</h3><p>Pilih loker di atas untuk membuat yang pertama.</p></td></tr>
                        @endforelse
                    </tbody>
                </table></div>
            </section>
        </div>

        {{-- Live pamphlet preview --}}
        <aside class="pm-preview">
            <section class="oc-panel">
                <header class="oc-panel-heading">
                    <div><h2>Pratinjau pamflet</h2><p>{{ $format === 'story' ? '1080 × 1920 px (9:16) untuk Instagram/WhatsApp Story.' : '1080 × 1350 px (4:5) untuk feed Threads, Instagram, LinkedIn, dan X.' }}</p></div>
                </header>
                <div class="pm-format oc-tabs" role="tablist" aria-label="Format pamflet">
                    <button type="button" wire:click="setFormat('feed')" aria-current="{{ $format === 'feed' ? 'page' : 'false' }}"><i class="ph ph-rectangle" aria-hidden="true"></i>Feed 4:5</button>
                    <button type="button" wire:click="setFormat('story')" aria-current="{{ $format === 'story' ? 'page' : 'false' }}"><i class="ph ph-device-mobile" aria-hidden="true"></i>Story 9:16</button>
                </div>
                @if ($pamphlet)
                    <div @class(['pm-canvas', 'is-story' => $format === 'story']) wire:key="pamphlet-{{ md5(json_encode($pamphlet)) }}" x-data="{ data: @js($pamphlet) }" x-init="window.TraKerjaPromo?.render($refs.canvas, data)">
                        <canvas x-ref="canvas" width="1080" height="{{ $format === 'story' ? 1920 : 1350 }}" role="img" aria-label="Pratinjau pamflet {{ $pamphlet['headline'] }}"></canvas>
                        <button type="button" class="am-button am-button--primary pm-download" x-on:click="window.TraKerjaPromo.download($refs.canvas, data.filename)"><i class="ph ph-download-simple" aria-hidden="true"></i>Unduh PNG</button>
                    </div>
                @else
                    <div class="oc-empty"><i class="ph ph-image" aria-hidden="true"></i><h3>Belum ada loker dipilih</h3><p>Pratinjau muncul setelah kamu memilih loker.</p></div>
                @endif
            </section>
        </aside>
    </div>
</div>
