@if(is_array($data))
    @if($depth >= 8)<pre>{{ json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
    @elseif(array_is_list($data))
        <ul class="ai-analysis-list">@foreach($data as $value)<li>@include('ai-studio.partials.analysis-data', ['data' => $value, 'depth' => $depth + 1])</li>@endforeach</ul>
    @else
        @foreach($data as $key => $value)
            <section class="ai-analysis-section"><h3>{{ ['skor_kecocokan' => 'Match score', 'rencana_aksi' => 'Suggested next steps', 'teks_revisi' => 'Suggested wording', 'alasan_perubahan' => 'Why this change', 'ringkasan' => 'Summary', 'pengalaman' => 'Experience', 'pendidikan' => 'Education', 'keahlian' => 'Skills'][$key] ?? \Illuminate\Support\Str::headline((string) $key) }}</h3>@include('ai-studio.partials.analysis-data', ['data' => $value, 'depth' => $depth + 1])</section>
        @endforeach
    @endif
@elseif(is_bool($data))<p>{{ $data ? 'Yes' : 'No' }}</p>
@elseif(is_scalar($data))<p>{{ (string) $data }}</p>
@else<p>No value provided.</p>
@endif
