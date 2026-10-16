@php
    $stageRows = collect($summary['stages'])->values();
    $stageTotal = $statuses['On Process'];
    $maximum = max(1, $stageRows->max('count') ?? 0);
@endphp
<section class="workspace-card summary-stage-snapshot summary-enter" aria-labelledby="summary-stages-title">
    <div class="workspace-section-heading"><div><h2 id="summary-stages-title">Where are your applications now?</h2><p>Current stages across {{ $stageTotal }} in-progress applications.</p></div><a href="{{ route('tracker', ['statusFilter' => 'On Process']) }}" wire:navigate class="job-text-link">Open active tracker <span aria-hidden="true">↗</span></a></div>
    @if($stageTotal > 0 && $stageRows->isNotEmpty())
        <ol class="summary-stage-breakdown" aria-label="Current stage distribution">
            @foreach($stageRows as $stage)
                @php($share = round($stage['count'] / max(1, $stageTotal) * 100, 1))
                <li><div><span>{{ $stage['name'] }}</span><strong>{{ $stage['count'] }}<small>{{ $share }}%</small></strong></div><span class="summary-stage-meter" aria-hidden="true"><span style="width: {{ $stage['count'] / $maximum * 100 }}%"></span></span></li>
            @endforeach
        </ol>
        <p class="workspace-hint">Bars compare volume against the largest stage. Percentages use all in-progress records, including archived applications. These are current stages, not completed milestones.</p>
    @else
        <div class="summary-analysis-empty"><h3>No in-progress applications in this period.</h3><p>Stages will appear here when you have applications in progress.</p></div>
    @endif
</section>
