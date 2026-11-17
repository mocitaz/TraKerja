@php
    $company = $vacancy->company;
    $place = $vacancy->location ?: ($vacancy->work_mode === 'remote' ? 'Remote' : null);
    $types = ['full_time' => 'Full-time', 'part_time' => 'Part-time', 'contract' => 'Contract', 'internship' => 'Internship', 'freelance' => 'Freelance'];
    $modes = ['onsite' => 'Onsite', 'hybrid' => 'Hybrid', 'remote' => 'Remote'];
    $levels = ['intern' => 'Internship', 'entry' => 'Entry level', 'mid' => 'Mid level', 'senior' => 'Senior level', 'lead' => 'Lead', 'manager' => 'Manager'];
    $education = ['sd' => 'SD', 'smp' => 'SMP', 'sma_smk' => 'SMA/SMK', 'd3' => 'D3', 's1' => 'S1', 's2' => 'S2', 'any' => 'Semua jenjang'];
    $date = fn ($d) => $d?->locale('id')->translatedFormat('d M Y');
    $checked = $vacancy->last_validated_at;
    $title = $vacancy->title.' – '.$company?->name.($place ? ' · '.$place : '');
    $description = \Illuminate\Support\Str::limit(trim(($company?->name ? "Lowongan {$vacancy->title} di {$company->name}" : $vacancy->title).($place ? " ({$place})" : '').'. '.$vacancy->summary), 155);
    $facts = array_filter([
        ['ph-map-pin', 'Lokasi', $place],
        ['ph-briefcase', 'Sistem kerja', $modes[$vacancy->work_mode] ?? null],
        ['ph-clock', 'Tipe pekerjaan', $types[$vacancy->employment_type] ?? null],
        ['ph-stairs', 'Level', $levels[$vacancy->seniority] ?? null],
        ['ph-money', 'Gaji', $vacancy->salaryLabel('id')],
        ['ph-graduation-cap', 'Pendidikan minimal', $education[$vacancy->education_min] ?? null],
        ['ph-calendar-plus', 'Diposting', $date($vacancy->date_posted ?? $vacancy->first_seen_at)],
        ['ph-calendar-x', 'Lamar sebelum', $date($vacancy->valid_through)],
    ], fn ($f) => filled($f[2]));
    $guest = ! auth()->check();
    $sources = $vacancy->listings->filter(fn ($l) => preg_match('#^https?://#i', (string) $l->url))->unique('job_source_id')->values();
@endphp
@extends('public.layout', ['title' => $title, 'description' => $description, 'canonical' => url(parse_url($vacancy->public_path, PHP_URL_PATH)), 'noindex' => ! $open])

@if($jsonLd)
    @push('head')
        <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
    @endpush
@endif

@section('content')
    @php
        $chips = array_values(array_filter([$place, $modes[$vacancy->work_mode] ?? null, $types[$vacancy->employment_type] ?? null, $levels[$vacancy->seniority] ?? null]));
    @endphp
    <section class="ex-hero pj-banner" aria-labelledby="pj-title">
        <div class="ex-hero-copy">
            <nav class="pj-crumbs" aria-label="Breadcrumb">
                <a href="{{ route('loker.index') }}">Cari loker</a><i class="ph ph-caret-right" aria-hidden="true"></i>
                @if($company)<a href="{{ route('perusahaan.show', $company->slug) }}">{{ \Illuminate\Support\Str::limit($company->name, 32) }}</a>@endif
            </nav>
            <div class="pj-banner-company">
                <span class="pj-logo pj-logo--lg">@if ($company?->logo_url)<img src="{{ $company->logo_url }}" alt="" referrerpolicy="no-referrer" onerror="this.remove()">@endif<i class="ph ph-buildings" aria-hidden="true"></i></span>
                <span><strong>@if($company)<a href="{{ route('perusahaan.show', $company->slug) }}">{{ $company->name }}</a>@endif</strong>@if($vacancy->sector)<small>{{ \Illuminate\Support\Str::after($vacancy->sector->name, 'Sektor ') }}</small>@endif</span>
            </div>
            <h1 id="pj-title">{{ $vacancy->title }}</h1>
            <p class="pj-status">
                @if($open)<span class="is-open">Masih buka</span>@if($checked)<span>Dicek {{ $checked->locale('id')->diffForHumans() }}</span>@endif
                @else<span class="is-closed">Sudah ditutup</span>@endif
                <span>via {{ $vacancy->source_platform }}</span>
            </p>
            @if($chips)<div class="pj-banner-chips">@foreach($chips as $chip)<span>{{ $chip }}</span>@endforeach</div>@endif
            <div class="pj-banner-actions">
                @if($open && $vacancy->source_url)<a class="pj-btn pj-btn--primary" href="{{ $vacancy->source_url }}" target="_blank" rel="noopener nofollow">Lamar di {{ $vacancy->source_platform }}<i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>@endif
                <button type="button" class="pj-btn" x-data x-on:click="navigator.clipboard.writeText(@js(url(parse_url($vacancy->public_path, PHP_URL_PATH)))).then(() => { $el.querySelector('span').textContent = 'Tautan tersalin' })"><i class="ph ph-link" aria-hidden="true"></i><span>Salin tautan</span></button>
            </div>
        </div>
        <div class="ex-hero-art" aria-hidden="true">
            <img class="ex-hero-person" src="{{ asset('images/landing/hero-portrait-white-v4.webp') }}" alt="" width="1254" height="1254" decoding="async">
            <div class="ex-float ex-float--job pj-float">
                <span class="ex-float-logo"><i class="ph {{ $open ? 'ph-seal-check' : 'ph-x-circle' }}"></i></span>
                <span><small>{{ $open ? 'Dicek berkala oleh TraKerja' : 'Status lowongan' }}</small><strong>{{ $open ? ($vacancy->valid_through ? 'Lamar sebelum '.$date($vacancy->valid_through) : 'Masih menerima lamaran') : 'Sudah ditutup' }}</strong></span>
            </div>
        </div>
        <div class="ex-ribbon ex-ribbon--back" aria-hidden="true"></div>
        <div class="ex-ribbon" aria-hidden="true"></div>
    </section>

    <div class="pj-grid">
        <article class="pj-panel pj-detail">
            @if(! $open)<p class="pj-closed-note"><i class="ph ph-info" aria-hidden="true"></i>Lowongan ini sudah tidak menerima lamaran menurut sumbernya. Lihat lowongan serupa di bawah.</p>@endif

            @if($facts)
                <section class="pj-section">
                    <h2>Detail lowongan</h2>
                    <dl class="pj-facts">
                        @foreach($facts as [$icon, $label, $value])
                            <div><dt><i class="ph {{ $icon }}" aria-hidden="true"></i>{{ $label }}</dt><dd @class(['is-salary' => $label === 'Gaji'])>{{ $value }}</dd></div>
                        @endforeach
                    </dl>
                </section>
            @endif

            <section class="pj-section">
                <h2>Tentang pekerjaan ini</h2>
                <p class="pj-summary">{{ $vacancy->summary ?: 'Ringkasan belum tersedia. Buka sumber aslinya untuk deskripsi lengkap dan cara melamar.' }}</p>
            </section>

            @if($vacancy->skills)
                <section class="pj-section">
                    <h2>Keterampilan</h2>
                    <div class="pj-skills">@foreach($vacancy->skills as $skill)<span>{{ $skill }}</span>@endforeach</div>
                </section>
            @endif

            <p class="pj-note"><i class="ph ph-info" aria-hidden="true"></i>Ringkasan ini disusun TraKerja dari sumber aslinya. Persyaratan dan cara melamar yang berlaku adalah yang tertulis di sumber.</p>
        </article>

        <aside class="pj-side">
            <section class="pj-panel pj-apply">
                <h2>Cara melamar</h2>
                <p>Lamaran dikirim langsung di situs sumbernya. TraKerja tidak memungut biaya apa pun dari pelamar.</p>
                @if($open && $vacancy->source_url)
                    <a class="pj-btn pj-btn--primary" href="{{ $vacancy->source_url }}" target="_blank" rel="noopener nofollow">Lamar di {{ $vacancy->source_platform }}<i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>
                @endif
                @if($sources->isNotEmpty())
                    <div class="pj-side-sources">
                        <span>Tersedia di</span>
                        <ul class="pj-sources">
                            @foreach($sources as $listing)
                                <li><a href="{{ $listing->url }}" target="_blank" rel="noopener nofollow"><x-platform-brand :name="$listing->source?->name" :show-name="false" /><span class="pj-source-name">{{ $listing->source?->name }}</span><i class="ph ph-arrow-up-right" aria-hidden="true"></i></a></li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </section>
        </aside>
    </div>

    @if($open && $vacancy->source_url)
        <div class="pj-mobile-apply"><a class="pj-btn pj-btn--primary" href="{{ $vacancy->source_url }}" target="_blank" rel="noopener nofollow">Lamar di {{ $vacancy->source_platform }}<i class="ph ph-arrow-up-right" aria-hidden="true"></i></a></div>
    @endif

    @if($related->isNotEmpty())
        <section class="pj-related">
            <div class="pj-related-head"><h2>Lowongan lain yang masih buka</h2><a href="{{ route('loker.index') }}">Lihat semua <i class="ph ph-arrow-right" aria-hidden="true"></i></a></div>
            <div class="pj-cards">@foreach($related as $item)@include('public.partials.job-card')@endforeach</div>
        </section>
    @endif
    @if($guest)
        @php session()->put('url.intended', url()->current()); @endphp
        <div class="pj-gate" x-data="{ open: false, init() { let seen = false; try { seen = !! localStorage.getItem('tk-login-gate-seen') } catch (e) {} if (! seen) setTimeout(() => { this.open = true }, 1200) }, close() { this.open = false; try { localStorage.setItem('tk-login-gate-seen', '1') } catch (e) {} } }" x-on:keydown.escape.window="close()" x-show="open" x-cloak style="display:none" role="dialog" aria-modal="true" aria-labelledby="pj-gate-title">
            <div class="pj-gate-backdrop" x-on:click="close()"></div>
            <div class="pj-gate-card">
                <button type="button" class="pj-gate-close" x-on:click="close()" aria-label="Tutup"><i class="ph ph-x" aria-hidden="true"></i></button>

                <div class="pj-gate-body">
                    <img class="pj-gate-logo" src="{{ asset('images/icon.png') }}" alt="" width="40" height="40">
                    <h2 id="pj-gate-title">Masuk ke TraKerja</h2>
                    <p class="pj-gate-sub">Simpan lowongan, lacak status lamaran, dan dapatkan rekomendasi yang sesuai dengan CV kamu. Gratis.</p>
                    <a class="pj-gate-google" href="{{ route('auth.google') }}" x-on:click="close()"><svg width="18" height="18" viewBox="0 0 48 48" aria-hidden="true"><path fill="#EA4335" d="M24 9.5c3.5 0 6.6 1.2 9.1 3.6l6.8-6.8C35.9 2.4 30.4 0 24 0 14.6 0 6.5 5.4 2.6 13.2l7.9 6.1C12.4 13.6 17.7 9.5 24 9.5z"/><path fill="#4285F4" d="M46.5 24.5c0-1.6-.1-3.1-.4-4.5H24v9h12.7c-.6 3-2.3 5.5-4.8 7.2l7.6 5.9c4.4-4.1 7-10.1 7-17.6z"/><path fill="#FBBC05" d="M10.5 28.7a14.5 14.5 0 0 1 0-9.4l-7.9-6.1a24 24 0 0 0 0 21.6l7.9-6.1z"/><path fill="#34A853" d="M24 48c6.5 0 11.9-2.1 15.9-5.8l-7.6-5.9c-2.1 1.4-4.9 2.3-8.3 2.3-6.3 0-11.6-4.1-13.5-9.8l-7.9 6.1C6.5 42.6 14.6 48 24 48z"/></svg><span>Lanjutkan dengan Google</span></a>
                    <div class="pj-gate-divider"><span>atau</span></div>
                    <div class="pj-gate-row">
                        <a class="pj-gate-btn pj-gate-btn--dark" href="{{ route('register') }}" x-on:click="close()">Daftar gratis</a>
                        <a class="pj-gate-btn" href="{{ route('login') }}" x-on:click="close()">Login</a>
                    </div>
                    <button type="button" class="pj-gate-skip" x-on:click="close()">Nanti saja, lanjut lihat loker</button>
                </div>
            </div>
        </div>
    @endif
@endsection
