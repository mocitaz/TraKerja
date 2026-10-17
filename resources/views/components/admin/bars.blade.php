@props(['labels' => [], 'values' => [], 'color' => '#729bb5', 'name' => 'Records'])
@php($max = max([1, ...array_map('intval', $values)]))
<div class="ai-bars" style="--chart-color: {{ $color }}">
    @forelse($labels as $i => $label)<div class="ai-bar-item" tabindex="0" aria-label="{{ $label ?: __('Not recorded') }}: {{ $values[$i] ?? 0 }} {{ $name }}"><div><span>{{ $label ?: __('Not recorded') }}</span><strong>{{ number_format($values[$i] ?? 0) }}</strong></div><div class="ai-bar-track"><span style="width: {{ ($values[$i] ?? 0)/$max*100 }}%"></span></div><span class="ai-bar-tip">{{ number_format($values[$i] ?? 0) }} {{ $name }}</span></div>
    @empty<div class="ai-empty"><h3>{{ __('No data yet') }}</h3><p>{{ __('Recorded activity will appear here.') }}</p></div>@endforelse
</div>
