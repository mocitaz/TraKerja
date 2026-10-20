@php
    $employmentPalette = ['#7961ac', '#477cb1', '#4e8175', '#b37257', '#945e89', '#716959'];
    $employmentItems = $summary['employment']->values()->map(fn ($type) => $type + ['share' => round($type['total'] / max(1, $applicationsCount) * 100, 1)])->all();
@endphp
<section class="insights-panel insights-employment" aria-labelledby="insights-employment-title" x-data="{ selected: 0, hovered: null, items: @js($employmentItems) }" @keydown.escape="selected = 0; hovered = null">
    <header class="insights-panel-heading"><div><h2 id="insights-employment-title">{{ __('How do you want to work?') }}</h2><p>{{ __('Employment types in your records') }}</p></div></header>
    @if($summary['employment']->isNotEmpty())
        <div class="insights-work-preview">
        <div class="insights-work-focus" aria-live="polite"><strong x-text="items[hovered ?? selected].name"></strong><span x-text="items[hovered ?? selected].share + '%'"></span></div>
        <div class="insights-work-strip" role="group" aria-label="{{ __('Employment type distribution') }}">@foreach($employmentItems as $index => $type)<button type="button" style="flex-grow: {{ $type['total'] }}; background: {{ $employmentPalette[$index % count($employmentPalette)] }}" @mouseenter="hovered = {{ $index }}" @mouseleave="hovered = null" @focus="hovered = {{ $index }}" @blur="hovered = null" @click="selected = {{ $index }}; hovered = {{ $index }}" :aria-pressed="selected === {{ $index }}" :class="{ 'is-muted': (hovered ?? selected) !== {{ $index }} }" aria-label="{{ $type['name'] }}: {{ $type['total'] }} {{ __('applications') }}, {{ $type['share'] }}%"></button>@endforeach</div>
        <div class="insights-employment-grid">
            @foreach($employmentItems as $index => $type)<button type="button" @mouseenter="hovered = {{ $index }}" @mouseleave="hovered = null" @focus="hovered = {{ $index }}" @blur="hovered = null" @click="selected = {{ $index }}; hovered = {{ $index }}" :aria-pressed="selected === {{ $index }}" :class="{ 'is-inspected': (hovered ?? selected) === {{ $index }} }"><span><i style="background: {{ $employmentPalette[$index % count($employmentPalette)] }}" aria-hidden="true"></i>{{ $type['name'] }}</span><strong>{{ $type['total'] }}<small>{{ $type['share'] }}%</small></strong></button>@endforeach
        </div>
        </div>
        <p class="insights-note">{{ __('Segment widths show each type’s share of your applications.') }}</p>
        <details class="insights-data"><summary>{{ __('All employment types') }}</summary>@include('summary.report.records', ['rows' => $summary['employment'], 'label' => 'Employment types'])</details>
    @else
        <div class="insights-empty insights-empty--small"><h3>{{ __('No records in this period.') }}</h3><p>{{ __('Your saved applications will populate this analysis.') }}</p></div>
    @endif
</section>
