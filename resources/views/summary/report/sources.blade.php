@php
    $sourcePalette = ['#7961ac', '#477cb1', '#4e8175', '#b37257', '#945e89', '#716959', '#605d94', '#81828b'];
    $sourceItems = $summary['sources']->take(7)->values()->map(fn($row, $index) => $row + ['color' => $sourcePalette[$index], 'share' => round($row['total'] / max(1, $applicationsCount) * 100, 1)])->all();
    if ($summary['sources']->count() > 7) {
        $otherSources = $summary['sources']->slice(7);
        $sourceItems[] = ['name' => __('Other Sources'), 'total' => $otherSources->sum('total'), 'interviews' => $otherSources->sum('interviews'), 'accepted' => $otherSources->sum('accepted'), 'color' => $sourcePalette[7], 'share' => round($otherSources->sum('total') / max(1, $applicationsCount) * 100, 1)];
    }
@endphp
<section id="summary-panel-sources" class="insights-panel insights-sources" aria-labelledby="insights-sources-title" x-data="{ selected: 0, hovered: null, chartHover: false, tipX: 120, tipY: 120, items: @js($sourceItems) }" @keydown.escape="hovered = null; chartHover = false; selected = 0">
    <header class="insights-panel-heading"><div><h2 id="insights-sources-title">{{ __('Which sources do you use?') }}</h2><p>{{ __('Compare where you apply and the outcomes recorded there.') }}</p></div><span class="insights-count">{{ __('Sources') }} · {{ $summary['sources']->count() }}</span></header>
    @if(count($sourceItems))
        <div class="insights-source-layout">
            <div class="insights-source-overview" aria-live="polite"><div><span x-text="items[hovered ?? selected].name"></span><strong x-text="items[hovered ?? selected].share + '%'"></strong></div><span><b x-text="items[hovered ?? selected].total"></b> {{ __('applications') }}</span></div>
            <div class="insights-source-ribbon" aria-hidden="true">@foreach($sourceItems as $index => $source)<i data-share="{{ $source['total'] / max(1, $applicationsCount) * 100 }}" style="flex-grow: {{ $source['total'] }}; background: {{ $source['color'] }}" :class="{ 'is-inspected': (hovered ?? selected) === {{ $index }} }"></i>@endforeach</div>
            <div class="insights-source-ranking" role="group" aria-label="{{ __('Sources breakdown') }}">
                @foreach($sourceItems as $index => $source)
                    <button type="button" style="--source-color: {{ $source['color'] }}" @click="selected = {{ $index }}; hovered = {{ $index }}; chartHover = false" @mouseenter="hovered = {{ $index }}" @mouseleave="hovered = null" @focus="hovered = {{ $index }}; chartHover = false" @keydown="hovered = {{ $index }}; chartHover = false" @blur="hovered = null" :aria-pressed="selected === {{ $index }}" :class="{ 'is-selected': (hovered ?? selected) === {{ $index }} }" @pointermove="if ($event.pointerType === 'mouse') { const r = $el.getBoundingClientRect(); $el.style.setProperty('--spot-x', ($event.clientX - r.left) + 'px'); $el.style.setProperty('--spot-y', ($event.clientY - r.top) + 'px') }" aria-label="{{ __(':value1: :value2 applications, :value3 percent', ['value1' => $source['name'], 'value2' => $source['total'], 'value3' => $source['share']]) }}">
                        <x-platform-brand :name="$source['name']"/><strong>{{ $source['total'] }}</strong><span>{{ $source['share'] }}%</span><i class="insights-ranking-track" aria-hidden="true"><span style="width: {{ $source['share'] }}%; background: {{ $source['color'] }}"></span></i>
                    </button>
                @endforeach
            </div>
            <div class="insights-source-outcomes" aria-live="polite" aria-atomic="true"><span><strong x-text="items[hovered ?? selected].interviews"></strong>{{ __('Interview stages') }}</span><span><strong x-text="items[hovered ?? selected].accepted"></strong>{{ __('Accepted') }}</span></div>
        </div>
        <details class="insights-data insights-data--wide"><summary>{{ __('All sources') }}</summary>@include('summary.report.records', ['rows' => $summary['sources'], 'label' => 'Sources'])<p class="insights-note">{{ __('Application volume does not measure source quality. Interview counts reflect current HR or User Interview stages.') }}</p></details>
    @else
        <div class="insights-empty insights-empty--small"><h3>{{ __('Not recorded yet') }}</h3><p>{{ __('Your application sources will appear here.') }}</p></div>
    @endif
</section>
