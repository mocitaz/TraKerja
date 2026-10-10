@props(['labels' => [], 'values' => [], 'name' => 'Records'])
@php
    $total = array_sum($values); $offset = 0; $colors = ['#779eb9', '#ad91c9', '#e69272', '#b95393', '#9daa87'];
@endphp
<div class="ai-donut">
    <svg viewBox="0 0 220 220" role="img" aria-label="{{ $total }} {{ $name }}"><circle cx="110" cy="110" r="80" fill="none" stroke="#edf0f2" stroke-width="19"/>
    @foreach($values as $i => $value)
        @php($share = $total ? $value/$total*100 : 0)
        <circle tabindex="0" class="ai-donut-segment" cx="110" cy="110" r="80" fill="none" stroke="{{ $colors[$i % count($colors)] }}" stroke-width="19" pathLength="100" stroke-dasharray="{{ $share }} {{ 100-$share }}" stroke-dashoffset="{{ -$offset }}" transform="rotate(-90 110 110)" aria-label="{{ $labels[$i] }}: {{ $value }} ({{ round($share,1) }}%)"><title>{{ $labels[$i] }} · {{ $value }} · {{ round($share,1) }}%</title></circle>
        @php($offset += $share)
    @endforeach
    <text x="110" y="108" class="ai-donut-total" text-anchor="middle">{{ number_format($total) }}</text><text x="110" y="130" text-anchor="middle">{{ $name }}</text></svg>
    <div class="ai-donut-legend">@foreach($labels as $i => $label)<div tabindex="0"><span><i style="background: {{ $colors[$i % count($colors)] }}"></i>{{ $label }}</span><strong>{{ number_format($values[$i] ?? 0) }}<small>{{ $total ? round(($values[$i] ?? 0)/$total*100,1) : 0 }}%</small></strong></div>@endforeach</div>
</div>
