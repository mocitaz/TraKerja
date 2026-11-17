@extends('public.layout', [
    'title' => 'Lowongan kerja '.$company->name,
    'description' => \Illuminate\Support\Str::limit("{$vacancies->total()} lowongan yang masih buka di {$company->name}, dikumpulkan dari berbagai platform dan dicek berkala oleh TraKerja.", 155),
    'canonical' => url(route('perusahaan.show', $company->slug, false)),
    'noindex' => $vacancies->total() === 0,
])

@section('content')
    <nav class="pj-crumbs" aria-label="Breadcrumb"><a href="{{ route('loker.index') }}">Cari loker</a><i class="ph ph-caret-right" aria-hidden="true"></i><span aria-current="page">{{ $company->name }}</span></nav>

    <header class="pj-panel pj-company-head">
        <span class="pj-logo pj-logo--lg">@if ($company->logo_url)<img src="{{ $company->logo_url }}" alt="" referrerpolicy="no-referrer" onerror="this.remove()">@endif<i class="ph ph-buildings" aria-hidden="true"></i></span>
        <div>
            <h1>{{ $company->name }}</h1>
            <p>{{ number_format($vacancies->total(), 0, ',', '.') }} lowongan masih buka @if($company->domain)· <a href="https://{{ $company->domain }}" target="_blank" rel="noopener nofollow">{{ $company->domain }}</a>@endif</p>
        </div>
    </header>

    @if($vacancies->isEmpty())
        <div class="pj-panel pj-empty"><i class="ph ph-briefcase" aria-hidden="true"></i><h2>Belum ada lowongan yang buka</h2><p>Coba cek lagi nanti, atau cari loker lain.</p><a class="pj-btn pj-btn--primary" href="{{ route('loker.index') }}">Cari loker</a></div>
    @else
        <div class="pj-cards">@foreach($vacancies as $item)@include('public.partials.job-card')@endforeach</div>
        <div class="pj-pagination">{{ $vacancies->links() }}</div>
    @endif
@endsection
