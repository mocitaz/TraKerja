@extends('public.layout', [
    'title' => $vacancy ? 'Lowongan sudah tidak tersedia: '.$vacancy->title : 'Lowongan tidak ditemukan',
    'description' => 'Lowongan ini sudah tidak tersedia di TraKerja. Lihat lowongan serupa yang masih buka.',
    'noindex' => true,
])

@section('content')
    <section class="pj-search-hero pj-gone">
        <span class="pj-gone-icon"><i class="ph {{ $notFound ? 'ph-magnifying-glass' : 'ph-archive' }}" aria-hidden="true"></i></span>
        <h1>{{ $notFound ? 'Lowongan tidak ditemukan' : 'Lowongan ini sudah tidak tersedia' }}</h1>
        @if ($vacancy)
            <p class="pj-gone-job"><strong>{{ $vacancy->title }}</strong>@if ($vacancy->company) · {{ $vacancy->company->name }}@endif</p>
            <p>Lowongan ini sudah ditutup atau ditarik dari sumbernya, jadi tidak bisa dilamar lagi. Berikut lowongan serupa yang masih buka.</p>
        @else
            <p>{{ $notFound ? 'Tautannya mungkin salah atau lowongannya sudah dihapus.' : 'Lowongan ini tidak lagi ditampilkan di TraKerja.' }} Coba cari lowongan lain yang masih buka.</p>
        @endif
        <form class="pj-search" method="get" action="{{ route('loker.index') }}" role="search">
            <label><i class="ph ph-magnifying-glass" aria-hidden="true"></i><span class="sr-only">Posisi atau perusahaan</span><input type="search" name="q" value="{{ $vacancy ? trim(\Illuminate\Support\Str::limit(preg_split('/\s*(\(|\s-\s|,|\|)/u', $vacancy->title)[0], 40, '')) : '' }}" placeholder="Posisi atau perusahaan"></label>
            <label><i class="ph ph-map-pin" aria-hidden="true"></i><span class="sr-only">Lokasi</span><input type="text" name="lokasi" value="" placeholder="Kota atau provinsi"></label>
            <label class="pj-check"><input type="checkbox" name="remote" value="1"> Remote</label>
            <button class="pj-btn pj-btn--primary" type="submit">Cari</button>
        </form>
    </section>

    @if ($similar->isNotEmpty())
        <section class="pj-related">
            <div class="pj-related-head"><h2>{{ $vacancy ? 'Lowongan serupa yang masih buka' : 'Lowongan terbaru yang masih buka' }}</h2><a href="{{ route('loker.index') }}">Lihat semua <i class="ph ph-arrow-right" aria-hidden="true"></i></a></div>
            <div class="pj-cards">@foreach ($similar as $item)@include('public.partials.job-card')@endforeach</div>
        </section>
    @endif
@endsection
