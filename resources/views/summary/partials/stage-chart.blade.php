@php
    $stageRows = collect($summary['stages'])->values();
    $stageTotal = $statuses['On Process'];
    $ceiling = max(5, ceil(($stageRows->max('count') ?? 0) / 5) * 5);
    $stageItems = $stageRows->map(fn ($stage) => $stage + ['share' => round($stage['count'] / max(1, $stageTotal) * 100, 1)])->all();
@endphp
<section class="workspace-card summary-stage-snapshot summary-enter" aria-labelledby="summary-stages-title" x-data="{ hover: null }">
    <div class="workspace-section-heading"><div><h2 id="summary-stages-title">Where are your applications now?</h2><p>Current stages across {{ $stageTotal }} in-progress applications.</p></div><a href="{{ route('tracker', ['statusFilter' => 'On Process']) }}" wire:navigate class="job-text-link">Open active tracker <span aria-hidden="true">↗</span></a></div>
    @if($stageTotal > 0 && count($stageItems))
        <div class="summary-stage-chart" tabindex="0" role="region" aria-label="Current stage distribution chart; scroll horizontally on smaller screens">
            <svg viewBox="0 0 1080 280" role="group" aria-label="Number of applications at each current stage">
                @for($tick = 0; $tick <= 5; $tick++)
                    @php
                        $y = 204 - $tick * 35;
                    @endphp
                    <line x1="42" x2="1065" y1="{{ $y }}" y2="{{ $y }}" class="summary-stage-gridline" />
                    <text x="28" y="{{ $y + 4 }}" text-anchor="end" class="summary-stage-axis">{{ $ceiling * $tick / 5 }}</text>
                @endfor
                @foreach($stageItems as $index => $stage)
                    @php
                        $slot = 1023 / count($stageItems);
                        $x = 42 + $slot * ($index + .5);
                        $height = $stage['count'] / $ceiling * 175;
                        $labelLines = explode("\n", wordwrap($stage['name'], 16, "\n"));
                    @endphp
                    <g class="summary-stage-column" tabindex="0" role="img" aria-label="{{ $stage['name'] }}: {{ $stage['count'] }} applications, {{ $stage['share'] }} percent of in-progress applications" :class="{ 'is-selected': hover === {{ $index }} }" @mouseenter="hover = {{ $index }}" @mouseleave="hover = null" @focus="hover = {{ $index }}" @blur="hover = null">
                        <rect x="{{ $x - $slot / 2 + 4 }}" y="9" width="{{ $slot - 8 }}" height="255" rx="12" class="summary-stage-column-hit" />
                        <rect x="{{ $x - 20 }}" y="{{ 204 - max(3, $height) }}" width="40" height="{{ max(3, $height) }}" rx="{{ $stage['count'] ? 9 : 1.5 }}" class="summary-stage-bar {{ $stage['count'] ? '' : 'is-empty' }}" />
                        <text x="{{ $x }}" y="{{ 194 - max(3, $height) }}" text-anchor="middle" class="summary-stage-value">{{ $stage['count'] }}</text>
                        <text x="{{ $x }}" y="228" text-anchor="middle" class="summary-stage-label">@foreach($labelLines as $line)<tspan x="{{ $x }}" dy="{{ $loop->first ? 0 : 15 }}">{{ $line }}</tspan>@endforeach</text>
                        <g class="summary-stage-tooltip" :class="{ 'is-visible': hover === {{ $index }} }" aria-hidden="true" transform="translate({{ min(890, max(42, $x - 88)) }}, 2)">
                            <rect width="175" height="46" rx="9" />
                            <text x="12" y="17" class="summary-stage-tooltip-name">{{ $stage['name'] }}</text>
                            <text x="12" y="34">{{ $stage['count'] }} applications · {{ $stage['share'] }}%</text>
                        </g>
                    </g>
                @endforeach
            </svg>
        </div>
        <p class="workspace-hint">Hover or focus a bar to explore. Counts show current stages, including archived records; they are not completed milestones. Tracker links show active records.</p>
    @else
        <div class="summary-analysis-empty"><h3>No in-progress applications in this period.</h3><p>Stages will appear here when you have applications in progress.</p></div>
    @endif
</section>
