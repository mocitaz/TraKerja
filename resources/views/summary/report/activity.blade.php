@php
    $activityLabels = $timelineData['labels'];
    $applicationValues = $timelineData['applications']->values()->all();
    $interviewValues = $timelineData['interviews']->values()->all();
    $ceiling = max(4, ceil(max([...$applicationValues, ...$interviewValues, 1]) / 4) * 4);
    $step = 660 / max(1, count($activityLabels));
    $barWidth = min(30, $step * .55);
    $points = collect($activityLabels)->map(fn($label, $index) => [
        'label' => $label, 'applications' => $applicationValues[$index], 'interviews' => $interviewValues[$index],
        'x' => 46 + $step * ($index + .5),
        'height' => $applicationValues[$index] / $ceiling * 192,
        'interviewY' => 224 - $interviewValues[$index] / $ceiling * 192,
    ])->all();
    $interviewLine = implode(' ', array_map(fn($point) => $point['x'].','.$point['interviewY'], $points));
    $hasActivity = array_sum($applicationValues) + array_sum($interviewValues) > 0;
@endphp
<section class="insights-panel insights-activity" aria-labelledby="summary-activity-title" x-data="{ active: null, selected: {{ count($points) - 1 }}, showApplications: true, showInterviews: true, points: @js($points) }">
    <header class="insights-panel-heading"><div><h2 id="summary-activity-title">{{ __('How is your search moving?') }}</h2><p>{{ __($timeFilter === 'all' ? 'Last 12 months' : $periodLabel) }} · {{ __('by event date') }}</p></div></header>
    <div class="insights-activity-top"><div class="insights-chart-totals"><div><span>{{ __('Applications') }}</span><strong>{{ array_sum($applicationValues) }}</strong></div><div><span>{{ __('Scheduled interviews') }}</span><strong>{{ array_sum($interviewValues) }}</strong></div></div><div class="insights-chart-legend" role="group" aria-label="{{ __('Visible chart series') }}"><button type="button" @click="showApplications = !showApplications" :aria-pressed="showApplications"><i class="insights-fill-purple" aria-hidden="true"></i>{{ __('Applications') }}</button><button type="button" @click="showInterviews = !showInterviews" :aria-pressed="showInterviews"><i class="insights-fill-blue" aria-hidden="true"></i>{{ __('Interviews') }}</button></div></div>
    @if($hasActivity)
        <div class="insights-plot-scroll" @mouseleave="active = null">
            <svg class="insights-activity-plot" viewBox="0 0 730 270" role="group" aria-label="{{ __('Applications and scheduled interviews over time') }}">
                @for($tick = 0; $tick <= 4; $tick++)
                    @php
                        $tickY = 224 - $tick * 48;
                    @endphp
                    <line x1="46" y1="{{ $tickY }}" x2="706" y2="{{ $tickY }}" class="insights-gridline"/><text x="31" y="{{ $tickY + 4 }}" text-anchor="end" class="insights-axis">{{ $ceiling / 4 * $tick }}</text>
                @endfor
                <line x-cloak x-show="active !== null" :x1="points[active ?? selected].x" :x2="points[active ?? selected].x" y1="24" y2="224" class="insights-plot-crosshair"/>
                <g x-show="showInterviews"><polyline points="{{ $interviewLine }}" class="insights-interview-line"/></g>
                @foreach($points as $index => $point)
                    <g tabindex="0" role="button" :aria-pressed="selected === {{ $index }}" :class="{ 'is-inspected': (active ?? selected) === {{ $index }} }" aria-label="{{ __(':value1: :value2 applications, :value3 scheduled interviews', ['value1' => $point['label'], 'value2' => $point['applications'], 'value3' => $point['interviews']]) }}" @mouseenter="active = {{ $index }}" @focus="active = {{ $index }}" @blur="active = null" @click="selected = {{ $index }}" @keydown.escape.prevent="active = null" @keydown.enter.prevent="selected = {{ $index }}" @keydown.space.prevent="selected = {{ $index }}" class="insights-plot-period">
                        <rect x="{{ $point['x'] - $step / 2 + 2 }}" y="24" width="{{ $step - 4 }}" height="204" rx="7" class="insights-plot-selection" :class="{ 'is-active': (active ?? selected) === {{ $index }} }"/>
                        <g x-show="showApplications" class="insights-application-bars">
                            @php
                                $blockCount = (int) ceil($point['height'] / 24);
                                $blockHeight = $blockCount ? ($point['height'] - max(0, $blockCount - 1) * 4) / $blockCount : 0;
                            @endphp
                            @for($block = 0; $block < $blockCount; $block++)
                                <rect x="{{ $point['x'] - $barWidth / 2 }}" y="{{ 224 - $block * ($blockHeight + 4) - $blockHeight }}" width="{{ $barWidth }}" height="{{ $blockHeight }}" rx="{{ min(5, $blockHeight / 2) }}"/>
                            @endfor
                        </g>
                        <circle x-show="showInterviews" cx="{{ $point['x'] }}" cy="{{ $point['interviewY'] }}" r="4.5" class="insights-interview-point"/>
                    </g>
                    @if($index % ($timeFilter === 'monthly' ? 5 : ($timeFilter === 'weekly' ? 1 : 2)) === 0 || $index === count($points) - 1)<text x="{{ $point['x'] }}" y="251" text-anchor="middle" class="insights-axis">{{ $point['label'] }}</text>@endif
                @endforeach
                <g x-cloak x-show="active !== null" class="insights-plot-tooltip" :transform="'translate(' + Math.max(48, Math.min(548, points[active ?? selected].x - 79)) + ' 4)'" aria-hidden="true">
                    <rect width="158" height="68" rx="9"/>
                    <text x="12" y="18" class="insights-tooltip-date" x-text="points[active ?? selected].label"></text>
                    <rect x="12" y="30" width="6" height="6" rx="2" class="insights-tooltip-purple"/>
                    <text x="24" y="36">{{ __('Applications') }}</text><text x="146" y="36" text-anchor="end" class="insights-tooltip-number" x-text="points[active ?? selected].applications"></text>
                    <rect x="12" y="48" width="6" height="6" rx="2" class="insights-tooltip-blue"/>
                    <text x="24" y="54">{{ __('Interviews') }}</text><text x="146" y="54" text-anchor="end" class="insights-tooltip-number" x-text="points[active ?? selected].interviews"></text>
                </g>
            </svg>
        </div>
        <div class="insights-activity-readout" aria-live="polite"><strong x-text="points[active ?? selected].label"></strong><span><i class="insights-fill-purple" aria-hidden="true"></i><b x-text="points[active ?? selected].applications"></b> {{ __('applications') }}</span><span><i class="insights-fill-blue" aria-hidden="true"></i><b x-text="points[active ?? selected].interviews"></b> {{ __('interviews') }}</span></div>
    @else
        <div class="insights-empty"><i class="ph ph-chart-bar" aria-hidden="true"></i><h3>{{ __('No activity in this window.') }}</h3><p>{{ __('Add an application, or choose a different period to explore your progress.') }}</p><a class="insights-link" href="{{ route('tracker') }}" wire:navigate>{{ __('Open applications') }}</a></div>
    @endif
    <details class="insights-data"><summary>{{ __('View chart data') }}</summary><div class="insights-table-scroll"><table><caption class="sr-only">{{ __('Application activity data') }}</caption><thead><tr><th>{{ __('Period') }}</th><th>{{ __('Applications') }}</th><th>{{ __('Interviews') }}</th></tr></thead><tbody>@foreach($points as $point)<tr><th scope="row">{{ $point['label'] }}</th><td>{{ $point['applications'] }}</td><td>{{ $point['interviews'] }}</td></tr>@endforeach</tbody></table></div></details>
    <p class="insights-note">{{ __('Applications use the date applied. Interviews use the scheduled date.') }}</p>
</section>
