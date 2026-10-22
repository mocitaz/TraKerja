@php
    $timeline = $report['timeline'];
    $memberValues = $userRegistrationByDay['data'];
    $ceiling = max(4, ceil(max([...$timeline['data'], ...$memberValues, 1]) / 4) * 4);
    $step = 650 / count($timeline['labels']);
    $points = [];
    foreach($timeline['labels'] as $index=>$label) $points[] = ['label'=>$label,'date'=>$timeline['dates'][$index],'applications'=>$timeline['data'][$index],'members'=>$memberValues[$index],'x'=>48+$step*($index+.5),'appHeight'=>$timeline['data'][$index]/$ceiling*150,'memberY'=>174-$memberValues[$index]/$ceiling*150];
    $line = implode(' ', array_map(fn($p)=>$p['x'].','.$p['memberY'], $points));
@endphp
<section class="aa-panel aa-timeline" wire:key="analytics-timeline-{{ $reportRevision }}" x-data="adminAnalyticsChart(@js($points))">
    <header class="aa-panel-heading"><div><h2>{{ __('Growth & application activity') }}</h2><p>{{ $periodFilter === 'all' ? __('Daily records over the last 30 days. Summary totals above are all time.') : __('Daily records in the selected period.') }}</p></div></header>
    <div class="aa-chart-summary"><div><span>{{ __('Applications recorded') }}</span><strong>{{ number_format(array_sum($timeline['data'])) }}</strong></div><div><span>{{ __('New members') }}</span><strong>{{ number_format(array_sum($memberValues)) }}</strong></div><div class="aa-chart-legend" role="group" aria-label="{{ __('Visible chart series') }}"><button type="button" @click="applicationsVisible = !applicationsVisible" :aria-pressed="applicationsVisible"><i class="aa-application-key" aria-hidden="true"></i>{{ __('Applications') }}</button><button type="button" @click="membersVisible = !membersVisible" :aria-pressed="membersVisible"><i class="aa-member-key" aria-hidden="true"></i>{{ __('Members') }}</button></div></div>
    <div class="aa-chart" @mouseleave="hovered = null"><svg viewBox="0 0 720 214" role="img" aria-label="{{ __('Daily new members and application records') }}">
        @for($tick=0;$tick<=4;$tick++)<line x1="48" x2="698" y1="{{ 174-$tick*37.5 }}" y2="{{ 174-$tick*37.5 }}" class="aa-gridline"/><text x="31" y="{{ 178-$tick*37.5 }}" text-anchor="end" class="aa-axis">{{ $ceiling/4*$tick }}</text>@endfor
        <rect :x="points[hovered ?? selected].x - {{ $step/2 }}" width="{{ $step }}" y="16" height="158" rx="4" class="aa-selection"/>
        @foreach($points as $index=>$point)<rect x="{{ $point['x']-min(22,$step*.55)/2 }}" y="{{ 174-$point['appHeight'] }}" width="{{ min(22,$step*.55) }}" height="{{ $point['appHeight'] }}" rx="2" class="aa-chart-bar" x-show="applicationsVisible"/>
        @if($index % max(1,ceil(count($points)/5)) === 0 || $index === count($points)-1)<text x="{{ $point['x'] }}" y="202" class="aa-axis" text-anchor="middle">{{ $point['label'] }}</text>@endif
        @endforeach
        <polyline points="{{ $line }}" class="aa-member-line" x-show="membersVisible"/>
        <circle :cx="current.x" :cy="current.memberY" r="4" class="aa-member-point" x-show="membersVisible"/>
        @foreach($points as $index=>$point)<rect x="{{ $point['x']-$step/2 }}" y="16" width="{{ $step }}" height="160" fill="transparent" @mouseenter="hovered = {{ $index }}" @click="select({{ $index }})" class="aa-chart-hit"/>@endforeach
    </svg></div>
    <div class="aa-chart-inspector" aria-live="polite"><strong x-text="current.label"></strong><span><b x-text="current.applications"></b> {{ __('applications') }}</span><span><b x-text="current.members"></b> {{ __('new members') }}</span></div>
    <div class="aa-chart-controls"><button type="button" class="aa-chart-arrow" @click="select(selected - 1)" :disabled="selected === 0" aria-label="{{ __('Previous day') }}"><i class="ph ph-caret-left" aria-hidden="true"></i></button><input type="range" min="0" max="{{ count($points)-1 }}" x-model.number="selected" @input="hovered = null" aria-label="{{ __('Inspect daily activity') }}" :aria-valuetext="current.label + ': ' + current.applications + ' ' + @js(__('applications')) + ', ' + current.members + ' ' + @js(__('new members'))"><button type="button" class="aa-chart-arrow" @click="select(selected + 1)" :disabled="selected === points.length - 1" aria-label="{{ __('Next day') }}"><i class="ph ph-caret-right" aria-hidden="true"></i></button><button type="button" class="aa-text-link" @click="$wire.showDetails('applications','date',current.date)" aria-label="{{ __('Inspect day') }}" title="{{ __('Inspect day') }}"><span>{{ __('Inspect day') }}</span><i class="ph ph-arrow-up-right" aria-hidden="true"></i></button></div>
    <p class="aa-note">{{ __('Applications use record creation dates.') }}</p>
</section>
