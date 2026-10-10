@php
    $labels = $timelineData['labels'];
    $applicationValues = $timelineData['applications']->values()->all();
    $interviewValues = $timelineData['interviews']->values()->all();
    $ceiling = max(4, ceil(max([...$applicationValues, ...$interviewValues, 1]) / 4) * 4);
    $width = 720;
    $plotWidth = 648;
    $points = collect($labels)->map(fn ($label, $index) => [
        'label' => $label, 'applications' => $applicationValues[$index], 'interviews' => $interviewValues[$index],
        'x' => 45 + $index * $plotWidth / max(1, count($labels) - 1),
        'appY' => 205 - ($applicationValues[$index] / $ceiling) * 160,
        'interviewY' => 205 - ($interviewValues[$index] / $ceiling) * 160,
    ])->all();
    $applicationLine = implode(' ', array_map(fn ($point) => $point['x'].','.$point['appY'], $points));
    $interviewLine = implode(' ', array_map(fn ($point) => $point['x'].','.$point['interviewY'], $points));
    $activityTotal = array_sum($applicationValues);
    $hasActivity = $activityTotal + array_sum($interviewValues) > 0;
@endphp
<section class="workspace-card summary-activity summary-enter" aria-labelledby="summary-activity-title" x-data="{ active: null, points: @js($points) }">
    <div class="workspace-section-heading"><div><h2 id="summary-activity-title">Application activity</h2><p>{{ $timeFilter === 'all' ? 'Last 12 months' : ($timeFilter === 'weekly' ? 'Last 7 days' : 'Last 30 days') }} · by event date</p></div><span class="summary-activity-total"><strong>{{ $activityTotal }}</strong> applications</span></div>
    <div class="summary-chart-toolbar"><div class="summary-chart-legend"><span><i class="summary-dot summary-dot--pink" aria-hidden="true"></i>Applications</span><span><i class="summary-dot summary-dot--blue" aria-hidden="true"></i>Scheduled interviews</span></div><div class="summary-chart-readout" :class="{ 'is-active': active !== null }"><span x-text="active === null ? 'Explore a period' : points[active].label">Explore a period</span><strong x-text="active === null ? '—' : points[active].applications">—</strong><span>applications</span><strong x-text="active === null ? '—' : points[active].interviews">—</strong><span>interviews</span></div></div>
    @if($hasActivity)
        <div class="summary-chart" @mouseleave="active = null">
            <svg viewBox="0 0 720 245" role="group" aria-label="Applications and scheduled interviews over time">
                <defs><linearGradient id="summary-activity-fill" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#d990b9" stop-opacity=".25"/><stop offset="100%" stop-color="#d990b9" stop-opacity=".02"/></linearGradient></defs>
                @for($tick = 0; $tick <= 4; $tick++)
                    @php($y = 205 - $tick * 40)
                    <line x1="45" y1="{{ $y }}" x2="693" y2="{{ $y }}" class="summary-gridline" /><text x="31" y="{{ $y + 4 }}" text-anchor="end" class="summary-axis-text">{{ $ceiling / 4 * $tick }}</text>
                @endfor
                <polygon points="45,205 {{ $applicationLine }} 693,205" fill="url(#summary-activity-fill)" />
                <polyline points="{{ $applicationLine }}" pathLength="100" class="summary-series summary-series--applications" />
                <polyline points="{{ $interviewLine }}" pathLength="100" class="summary-series summary-series--interviews" />
                @foreach($points as $index => $point)
                    @if($index % ($timeFilter === 'monthly' ? 5 : ($timeFilter === 'weekly' ? 1 : 2)) === 0 || $index === count($points) - 1)<text x="{{ $point['x'] }}" y="232" text-anchor="middle" class="summary-axis-text">{{ $point['label'] }}</text>@endif
                    <g tabindex="0" role="button" aria-label="{{ $point['label'] }}: {{ $point['applications'] }} applications, {{ $point['interviews'] }} scheduled interviews" @mouseenter="active = {{ $index }}" @focus="active = {{ $index }}" @blur="active = null" @click="active = {{ $index }}" @keydown.enter.prevent="active = {{ $index }}" @keydown.space.prevent="active = {{ $index }}">
                        <rect x="{{ $point['x'] - min(17, $plotWidth / max(1, count($points) - 1) / 2) }}" y="30" width="{{ min(34, $plotWidth / max(1, count($points) - 1)) }}" height="180" fill="transparent" />
                        <line x1="{{ $point['x'] }}" y1="30" x2="{{ $point['x'] }}" y2="205" class="summary-crosshair" :class="{ 'is-visible': active === {{ $index }} }" />
                        <circle cx="{{ $point['x'] }}" cy="{{ $point['appY'] }}" r="4" class="summary-point summary-point--pink" :class="{ 'is-active': active === {{ $index }} }" />
                        <circle cx="{{ $point['x'] }}" cy="{{ $point['interviewY'] }}" r="3" class="summary-point summary-point--blue" :class="{ 'is-active': active === {{ $index }} }" />
                    </g>
                @endforeach
            </svg>
        </div>
    @else
        <div class="summary-chart-empty"><h3>No activity in this window.</h3><p>Add an application, or choose a different period to explore your progress.</p><a href="{{ route('tracker') }}" wire:navigate class="job-text-link">Open applications</a></div>
    @endif
    <div class="summary-chart-footer"><p>Applications use the date applied. Interviews use the scheduled date.</p><details class="summary-data-table"><summary>View chart data</summary><div><table><caption class="sr-only">Application activity data</caption><thead><tr><th scope="col">Period</th><th scope="col">Applications</th><th scope="col">Interviews</th></tr></thead><tbody>@foreach($points as $point)<tr><th scope="row">{{ $point['label'] }}</th><td>{{ $point['applications'] }}</td><td>{{ $point['interviews'] }}</td></tr>@endforeach</tbody></table></div></details></div>
</section>
