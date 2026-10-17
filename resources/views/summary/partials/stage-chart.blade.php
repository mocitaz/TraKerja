@php
    $stageRows = collect($summary['stages'])->values();
    $stageTotal = $statuses['On Process'];
    $maximum = max(1, $stageRows->max('count') ?? 0);
@endphp
<section class="workspace-card summary-stage-snapshot summary-enter" aria-labelledby="summary-stages-title">
    <div class="workspace-section-heading"><div><h2 id="summary-stages-title">{{ __('Where are your applications now?') }}</h2><p>{{ __('Current stages across :value1 in-progress applications.', ['value1' => ($stageTotal)]) }}</p></div><a href="{{ route('tracker', ['statusFilter' => __('On Process')]) }}" wire:navigate class="job-text-link">{{ __('Open active tracker') }} <span aria-hidden="true">↗</span></a></div>
    @if($stageTotal > 0 && $stageRows->isNotEmpty())
        <ol class="summary-stage-breakdown" aria-label="{{ __('Current stage distribution') }}">
            @foreach($stageRows as $stage)
                @php($share = round($stage['count'] / max(1, $stageTotal) * 100, 1))
                <li><div><span>{{ $stage['name'] }}</span><strong>{{ $stage['count'] }}<small>{{ $share }}%</small></strong></div><span class="summary-stage-meter" aria-hidden="true"><span style="width: {{ $stage['count'] / $maximum * 100 }}%"></span></span></li>
            @endforeach
        </ol>
        <p class="workspace-hint">{{ __('Bars compare volume against the largest stage. Percentages use all in-progress records, including archived applications. These are current stages, not completed milestones.') }}</p>
    @else
        <div class="summary-analysis-empty"><h3>{{ __('No in-progress applications in this period.') }}</h3><p>{{ __('Stages will appear here when you have applications in progress.') }}</p></div>
    @endif
</section>
