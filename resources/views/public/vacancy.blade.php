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
        ['ph-money', 'Gaji', $vacancy->salary_label],
        ['ph-graduation-cap', 'Pendidikan minimal', $education[$vacancy->education_min] ?? null],
        ['ph-calendar-plus', 'Diposting', $date($vacancy->date_posted ?? $vacancy->first_seen_at)],
        ['ph-calendar-x', 'Lamar sebelum', $date($vacancy->valid_through)],
    ], fn ($f) => filled($f[2]));
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
                @if($open)<span class="is-open">Still open</span>@if($checked)<span>Checked {{ $checked->locale('en')->diffForHumans() }}</span>@endif
                @else<span class="is-closed">Closed</span>@endif
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
@endsection
