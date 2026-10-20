@php
    $stageRows = $summary['stages']->values();
    $stageMaximum = max(1, $stageRows->max('count') ?? 0);
    $unrecordedStages = max(0, $statuses['On Process'] - $stageRows->sum('count'));
    $stageIcons = ['clipboard-text', 'arrow-bend-up-right', 'exam', 'brain', 'chats', 'user-sound', 'users-three', 'presentation-chart', 'handshake', 'pause-circle'];
    $stageDetails = $stageRows->map(fn ($stage) => [
        'name' => __($stage['name']), 'count' => $stage['count'],
        'share' => round($stage['count'] / max(1, $statuses['On Process']) * 100, 1),
    ])->all();
    $leadingStage = max(0, $stageRows->search(fn ($stage) => $stage['count'] === $stageRows->max('count')) ?: 0);
@endphp
<section class="insights-panel insights-stages" aria-labelledby="insights-stages-title" x-data="{ selected: {{ $leadingStage }}, hovered: null, stages: @js($stageDetails), get inspected() { return this.hovered ?? this.selected } }" @keydown.escape="hovered = null; selected = {{ $leadingStage }}">
    <header class="insights-panel-heading"><div><h2 id="insights-stages-title">{{ __('Where are your applications now?') }}</h2><p>{{ __('Current stages across :value1 in-progress applications.', ['value1' => $statuses['On Process']]) }}</p></div><a class="insights-link" href="{{ route('tracker', ['statusFilter' => 'On Process']) }}" wire:navigate>{{ __('Open active tracker') }}<i class="ph ph-arrow-up-right" aria-hidden="true"></i></a></header>
    @if($statuses['On Process'])
        <div class="insights-stage-spotlight" aria-live="polite" aria-atomic="true">
            <div><span>{{ __('Current stage') }}</span><strong x-text="stages[inspected].name">{{ $stageDetails[$leadingStage]['name'] ?? '' }}</strong></div>
            <div class="insights-stage-spotlight-count"><strong x-text="stages[inspected].count">{{ $stageDetails[$leadingStage]['count'] ?? 0 }}</strong><span>{{ __('applications') }}<small x-text="stages[inspected].share + '%'"></small></span></div>
        </div>
        <div class="insights-stage-distribution" aria-hidden="true">
            @foreach($stageDetails as $index => $stage)@if($stage['count'])<i style="flex-grow: {{ $stage['count'] }}" :class="{ 'is-inspected': inspected === {{ $index }} }"></i>@endif@endforeach
            @if($unrecordedStages)<i class="is-unrecorded" style="flex-grow: {{ $unrecordedStages }}"></i>@endif
        </div>
        <ol class="insights-stage-grid">
            @foreach($stageRows as $index => $stage)
                <li><button type="button" @class(['insights-stage-choice', 'is-zero' => !$stage['count']]) :class="{ 'is-inspected': inspected === {{ $index }} }" :aria-pressed="selected === {{ $index }}" @mouseenter="hovered = {{ $index }}" @mouseleave="hovered = null" @focus="hovered = {{ $index }}" @blur="hovered = null" @click="selected = {{ $index }}" @pointermove="if ($event.pointerType === 'mouse') { const r = $el.getBoundingClientRect(); $el.style.setProperty('--spot-x', ($event.clientX - r.left) + 'px'); $el.style.setProperty('--spot-y', ($event.clientY - r.top) + 'px') }">
                    <span class="insights-stage-card-top"><i class="ph ph-{{ $stageIcons[$index] ?? 'clipboard-text' }}" aria-hidden="true"></i></span>
                    <span class="insights-stage-card-name">{{ __($stage['name']) }}</span>
                    <span class="insights-stage-card-count"><strong>{{ $stage['count'] }}</strong><small>{{ $stageDetails[$index]['share'] }}%</small></span>
                    <span class="insights-stage-meter" aria-hidden="true"><i style="width: {{ $stage['count'] / $stageMaximum * 100 }}%"></i></span>
                </button></li>
            @endforeach
        </ol>
        @if($unrecordedStages)<p class="insights-note">{{ __(':count in-progress records do not have a recognized stage.', ['count' => $unrecordedStages]) }}</p>@endif
        <p class="insights-note">{{ __('These are current stages, not completed milestones. Percentages include archived records in progress.') }}</p>
    @else
        <div class="insights-empty insights-empty--small"><h3>{{ __('No in-progress applications in this period.') }}</h3><p>{{ __('Stages will appear here when you have applications in progress.') }}</p></div>
    @endif
</section>
