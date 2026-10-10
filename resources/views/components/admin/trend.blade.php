@props(['labels' => [], 'values' => [], 'color' => '#b85191', 'name' => 'Records', 'unit' => 'days'])
@php
    $max = max(2, (int) ceil(max([1, ...array_map('intval', $values)]) / 2) * 2);
    $count = count($values);
    $points = [];
    foreach($values as $i => $value) { $points[] = [40 + $i * 720 / max(1, $count - 1), 205 - (int) $value / $max * 170]; }
    $line = implode(' ', array_map(fn ($p) => implode(',', $p), $points));
@endphp
<div class="ai-trend" x-data="{ tip: '' }" style="--chart-color: {{ $color }}">
    @if(array_sum($values) === 0)<p class="ai-chart-empty">No {{ strtolower($name) }} recorded in this window.</p>@endif
    <div class="ai-trend-tooltip" x-show="tip" x-cloak x-text="tip" aria-live="polite"></div>
    <svg viewBox="0 0 800 250" role="img" aria-label="{{ $name }} across {{ count($labels) }} {{ $unit }}">
        @foreach([0, .5, 1] as $step)<line x1="40" x2="760" y1="{{ 205-$step*170 }}" y2="{{ 205-$step*170 }}" class="ai-chart-grid"/><text x="28" y="{{ 210-$step*170 }}" text-anchor="end">{{ round($max*$step) }}</text>@endforeach
        @if($count)<polygon points="40,205 {{ $line }} {{ end($points)[0] }},205" fill="{{ $color }}" opacity=".09"/><polyline points="{{ $line }}" fill="none" stroke="{{ $color }}" stroke-width="2.5" stroke-linejoin="round"/>
        @foreach($points as $i => $point)<circle class="ai-chart-point" tabindex="0" data-tip="{{ $labels[$i] }} · {{ number_format($values[$i]) }} {{ $name }}" @mouseenter="tip = $el.dataset.tip" @mouseleave="tip = ''" @focus="tip = $el.dataset.tip" @blur="tip = ''" cx="{{ $point[0] }}" cy="{{ $point[1] }}" r="4" fill="{{ $color }}" stroke="white" stroke-width="2" aria-label="{{ $labels[$i] }}: {{ $values[$i] }} {{ $name }}"><title>{{ $labels[$i] }} · {{ number_format($values[$i]) }} {{ $name }}</title></circle>@endforeach
        @foreach(array_unique([0, (int) floor(($count-1)/2), $count-1]) as $i)<text x="{{ $points[$i][0] }}" y="237" text-anchor="middle">{{ $labels[$i] }}</text>@endforeach @endif
    </svg>
    <details class="ai-data"><summary>View chart data</summary><div class="ai-data-scroll"><table><thead><tr><th>{{ $unit === 'hours' ? 'Hour' : 'Date' }}</th><th>{{ $name }}</th></tr></thead><tbody>@foreach($labels as $i => $label)<tr><td>{{ $label }}</td><td>{{ number_format($values[$i] ?? 0) }}</td></tr>@endforeach</tbody></table></div></details>
</div>
