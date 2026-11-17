@extends('public.layout', [
    'title' => $filtered ? 'Loker '.trim($q.' '.$lokasi.($remote ? ' remote' : '')) : 'Lowongan kerja terbaru di Indonesia',
    'description' => 'Lowongan kerja yang masih buka dari berbagai platform dan situs karier perusahaan, dicek berkala oleh TraKerja. Lamar langsung di sumber aslinya.',
    'canonical' => url(route('loker.index', absolute: false)),
    'noindex' => $filtered,
])

@section('content')
    <section class="pj-search-hero">
        <h1>{{ $filtered ? 'Hasil pencarian loker' : 'Lowongan kerja yang masih buka' }}</h1>
        <p>Dari berbagai platform dan situs karier perusahaan. Duplikat digabung, dan loker dicek berkala apakah masih buka.</p>
        <form class="pj-search" method="get" action="{{ route('loker.index') }}" role="search">
            <label><i class="ph ph-magnifying-glass" aria-hidden="true"></i><span class="sr-only">Posisi atau perusahaan</span><input type="search" name="q" value="{{ $q }}" placeholder="Posisi atau perusahaan"></label>
            <label><i class="ph ph-map-pin" aria-hidden="true"></i><span class="sr-only">Lokasi</span><input type="text" name="lokasi" value="{{ $lokasi }}" placeholder="Kota atau provinsi"></label>
            <label class="pj-check"><input type="checkbox" name="remote" value="1" @checked($remote)> Remote</label>
            <button class="pj-btn pj-btn--primary" type="submit">Cari</button>
        </form>
    </section>

    <div class="pj-results-head">
        <p>{{ number_format($vacancies->total(), 0, ',', '.') }} loker{{ $filtered ? ' cocok' : ' masih buka' }}</p>
        @if($filtered)<a href="{{ route('loker.index') }}">Hapus filter</a>@endif
    </div>

    @if($vacancies->isEmpty())
        <div class="pj-panel pj-empty"><i class="ph ph-magnifying-glass" aria-hidden="true"></i><h2>Belum ada loker yang cocok</h2><p>Coba kata kunci lain atau hapus filter lokasi.</p></div>
    @else
        <div class="pj-cards">@foreach($vacancies as $item)@include('public.partials.job-card')@endforeach</div>
        <div class="pj-pagination">{{ $vacancies->links() }}</div>
    @endif
@endsection
