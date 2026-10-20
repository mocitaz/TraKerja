@php
    $roleItems = $summary['roles']->take(5)->values()->all();
@endphp
<section class="insights-panel insights-roles" aria-labelledby="insights-roles-title" x-data="{ selected: 0, hovered: null, items: @js($roleItems) }" @keydown.escape="selected = 0; hovered = null">
    <header class="insights-panel-heading"><div><h2 id="insights-roles-title">{{ __('What roles are you pursuing?') }}</h2><p>{{ __('Your most-tracked positions') }}</p></div><span class="insights-count">{{ __('Roles') }} · {{ $summary['roles']->count() }}</span></header>
    @if($summary['roles']->isNotEmpty())
        <ol class="insights-role-list">
            @foreach($roleItems as $index => $role)
                <li><button type="button" class="insights-role-choice" @mouseenter="hovered = {{ $index }}" @mouseleave="hovered = null" @focus="hovered = {{ $index }}" @blur="hovered = null" @click="selected = {{ $index }}; hovered = {{ $index }}" :aria-pressed="selected === {{ $index }}" :class="{ 'is-inspected': (hovered ?? selected) === {{ $index }} }" @pointermove="if ($event.pointerType === 'mouse') { const r = $el.getBoundingClientRect(); $el.style.setProperty('--spot-x', ($event.clientX - r.left) + 'px'); $el.style.setProperty('--spot-y', ($event.clientY - r.top) + 'px') }"><x-role-avatar :position="$role['name']"/><span class="insights-role-copy"><span>{{ $role['name'] }}</span><i class="insights-ranking-track" aria-hidden="true"><span style="width: {{ $role['total'] / max(1, $summary['roles']->max('total')) * 100 }}%"></span></i></span><strong>{{ $role['total'] }}<small>{{ round($role['total'] / max(1, $applicationsCount) * 100, 1) }}%</small></strong></button></li>
            @endforeach
        </ol>
        <div class="insights-role-readout" aria-live="polite"><strong x-text="items[hovered ?? selected].name"></strong><span><b x-text="items[hovered ?? selected].interviews"></b> {{ __('Interview stages') }}</span><span><b x-text="items[hovered ?? selected].accepted"></b> {{ __('Accepted') }}</span></div>
        <details class="insights-data"><summary>{{ __('All roles') }}</summary>@include('summary.report.records', ['rows' => $summary['roles'], 'label' => 'Roles'])</details>
    @else
        <div class="insights-empty insights-empty--small"><h3>{{ __('No records in this period.') }}</h3><p>{{ __('Your saved applications will populate this analysis.') }}</p></div>
    @endif
</section>
