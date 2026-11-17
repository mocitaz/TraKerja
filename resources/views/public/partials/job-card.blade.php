@php
    $modes = ['onsite' => 'Onsite', 'hybrid' => 'Hybrid', 'remote' => 'Remote'];
    $types = ['full_time' => 'Full-time', 'part_time' => 'Part-time', 'contract' => 'Contract', 'internship' => 'Internship', 'freelance' => 'Freelance'];
@endphp
<a class="pj-card" href="{{ parse_url($item->public_path, PHP_URL_PATH) }}">
    <span class="pj-card-head">
        <span class="pj-logo">@if ($item->company?->logo_url)<img src="{{ $item->company->logo_url }}" alt="" loading="lazy" referrerpolicy="no-referrer" onerror="this.remove()">@endif<i class="ph ph-buildings" aria-hidden="true"></i></span>
        <span class="pj-card-title"><strong>{{ $item->title }}</strong><small>{{ $item->company?->name ?? 'Perusahaan' }}</small></span>
    </span>
    <span class="pj-card-meta">
        <span><i class="ph ph-map-pin" aria-hidden="true"></i>{{ $item->location ?: ($item->work_mode === 'remote' ? 'Remote' : 'Lokasi belum disebut') }}</span>
        @if ($item->salary_label)<span class="is-salary"><i class="ph ph-money" aria-hidden="true"></i>{{ $item->salaryLabel('id') }}</span>@endif
    </span>
    <span class="pj-card-foot">
        @foreach (array_filter([$modes[$item->work_mode] ?? null, $types[$item->employment_type] ?? null]) as $chip)<span class="pj-chip">{{ $chip }}</span>@endforeach
        <span class="pj-card-open">Still open</span>
    </span>
</a>
