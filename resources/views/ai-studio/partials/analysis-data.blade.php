@if(is_array($data))
    @if($depth >= 8)<pre>{{ json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
    @elseif(array_is_list($data))
        <ul class="ai-analysis-list">@foreach($data as $value)<li>@include('ai-studio.partials.analysis-data', ['data' => $value, 'depth' => $depth + 1])</li>@endforeach</ul>
    @else
        @foreach($data as $key => $value)
            <section class="ai-analysis-section"><h3>{{ ['skor_kecocokan' => __('Match score'), 'rencana_aksi' => __('Suggested next steps'), 'teks_revisi' => __('Suggested wording'), 'alasan_perubahan' => __('Why this change'), 'ringkasan' => __('Summary'), 'pengalaman' => __('Experience'), 'pendidikan' => __('Education'), 'keahlian' => __('Skills')][$key] ?? \Illuminate\Support\Str::headline((string) $key) }}</h3>@include('ai-studio.partials.analysis-data', ['data' => $value, 'depth' => $depth + 1])</section>
        @endforeach
    @endif
@elseif(is_bool($data))<p>{{ $data ? __('Yes') : __('No') }}</p>
@elseif(is_scalar($data))<p>{{ (string) $data }}</p>
@else<p>{{ __('No value provided.') }}</p>
@endif
