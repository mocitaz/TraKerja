<section wire:poll.visible.30s class="dashboard-activity dashboard-activity-with-goals" aria-labelledby="application-activity-title"
    x-data="{
        activityTip: null, tipX: 0, tipY: 0, tipTimer: null,
        showActivityTip(event) {
            clearTimeout(this.tipTimer);
            const cell = event.currentTarget;
            const bounds = cell.getBoundingClientRect();
            this.activityTip = { date: cell.dataset.date, count: Number(cell.dataset.count) };
            this.tipX = Math.min(window.innerWidth - 104, Math.max(104, bounds.left + bounds.width / 2));
            this.tipY = bounds.top > 84 ? bounds.top - 10 : bounds.bottom + 82;
        },
        hideActivityTip() {
            clearTimeout(this.tipTimer);
            this.tipTimer = setTimeout(() => { this.activityTip = null; }, 150);
        }
    }"
    @keydown.escape.window="activityTip = null" @resize.window="activityTip = null" @scroll.window.capture="activityTip = null">
    <div class="workspace-panel dashboard-activity-panel">
        <header class="dashboard-activity-header">
            <div>
                <h3 id="application-activity-title">Application activity</h3>
                <p>{{ $totalHeatmapApps }} applications · last {{ $months }} months. Click a day to explore.</p>
            </div>
            <div class="dashboard-activity-average">
                <span>Daily average</span>
                <strong>{{ number_format($dailyAverage, 2) }}</strong>
            </div>
        </header>

        <div class="activity-range" role="group" aria-label="Activity date range">
        @foreach([3,6,12] as $range)<button type="button" wire:click="setRange({{ $range }})" aria-pressed="{{ $months===$range ? 'true' : 'false' }}" class="{{ $months===$range ? 'is-active' : '' }}">{{ $range }} months</button>@endforeach
        <span wire:loading wire:target="setRange">Updating…</span>
    </div>
    @php
            $lastMonth = '';
            $lastMonthColIndex = -10;
            $dayOrder = [1, 2, 3, 4, 5, 6, 0];
            $heatmapColors = ['#e9eef0', '#f5d0fe', '#e879f9', '#c026d3', '#86198f'];
        @endphp
        <div class="dashboard-heatmap-scroll custom-scrollbar" tabindex="0" role="region" aria-label="Daily application activity over the last {{ $months }} months">
            <div class="dashboard-heatmap" style="--heatmap-weeks: {{ count($heatmapWeeks) }};">
                <div class="dashboard-heatmap-days" aria-hidden="true">
                    <span>Mon</span><span></span><span>Wed</span><span></span><span>Fri</span><span></span><span>Sun</span>
                </div>
                <div class="dashboard-heatmap-weeks">
                    @foreach($heatmapWeeks as $weekKey => $days)
                        @php
                            $currentMonth = \Carbon\Carbon::parse($weekKey)->format('M');
                            $monthName = '';
                            if ($currentMonth !== $lastMonth && ($loop->index - $lastMonthColIndex) >= 3) {
                                $monthName = $currentMonth;
                                $lastMonthColIndex = $loop->index;
                            }
                            $lastMonth = $currentMonth;
                        @endphp
                        <div class="dashboard-heatmap-week">
                            @if($monthName)
                                <span class="dashboard-heatmap-month" aria-hidden="true">{{ $monthName }}</span>
                            @endif
                            @foreach($dayOrder as $dayIndex)
                                @php
                                    $day = $days[$dayIndex] ?? null;
                                    $count = $day['count'] ?? 0;
                                @endphp
                                @if($day)
                                    <button type="button" class="dashboard-heatmap-cell" wire:key="activity-day-{{ $day['date'] }}" wire:click="selectDate('{{ $day['date'] }}')" @click="activityTip = null" aria-label="{{ $count }} {{ Str::plural('application', $count) }} on {{ $day['formattedDate'] }}" data-date="{{ $day['formattedDate'] }}" data-count="{{ $count }}" @mouseenter="showActivityTip($event)" @mouseleave="hideActivityTip()" @focus="showActivityTip($event)" @blur="hideActivityTip()" style="background: {{ $heatmapColors[min(4, $count)] }};"></button>
                                @else
                                    <span class="dashboard-heatmap-cell" aria-hidden="true"></span>
                                @endif
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="activity-mobile-calendar">
            <header><h4 aria-live="polite">{{ $monthLabel }}</h4><div>
                <button type="button" wire:click="moveMonth(-1)" aria-label="Previous activity month" @disabled(!$canPreviousMonth) wire:loading.attr="disabled" wire:target="moveMonth"><i class="ph ph-caret-left" aria-hidden="true"></i></button>
                <button type="button" wire:click="moveMonth(1)" aria-label="Next activity month" @disabled(!$canNextMonth) wire:loading.attr="disabled" wire:target="moveMonth"><i class="ph ph-caret-right" aria-hidden="true"></i></button>
            </div></header>
            <div class="activity-month-grid" aria-label="{{ $monthLabel }} application calendar">
                @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $weekday)<span class="activity-weekday">{{ $weekday }}</span>@endforeach
                @foreach($calendarDays as $day)
                    @if($day['inMonth'])
                    <button type="button" wire:key="mobile-activity-{{ $day['date'] }}" wire:click="selectDate('{{ $day['date'] }}')" @disabled(!$day['enabled']) aria-label="{{ $day['count'] }} {{ Str::plural('application', $day['count']) }} on {{ $day['date'] }}" @if($day['today']) aria-current="date" @endif class="activity-month-day" title="{{ \Carbon\Carbon::parse($day['date'])->format('j M Y') }} · {{ $day['count'] }} {{ Str::plural('application', $day['count']) }}" style="--day-color: {{ $heatmapColors[min(4, $day['count'])] }};"><span aria-hidden="true"></span></button>
                    @else<span class="activity-month-spacer" aria-hidden="true"></span>@endif
                @endforeach
            </div>
            <p>Tap a square to explore that day.</p>
        </div>

        <footer class="dashboard-activity-footer">
            <a href="{{ route('tracker') }}" wire:navigate>View applications <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>
            <div class="dashboard-heatmap-legend" aria-label="Color intensity: fewer to more daily applications">
                <span>Less</span>
                @foreach($heatmapColors as $color)
                    <span class="dashboard-heatmap-key" style="background: {{ $color }};" aria-hidden="true"></span>
                @endforeach
                <span>More</span>
            </div>
        </footer>
    </div>
    <template x-teleport="body">
        <div x-cloak x-show="activityTip" class="dashboard-activity-tooltip" :style="{ left: tipX + 'px', top: tipY + 'px' }" @mouseenter="clearTimeout(tipTimer)" @mouseleave="hideActivityTip()" aria-hidden="true">
            <span class="dashboard-activity-tooltip-date" x-text="activityTip?.date"></span>
            <span class="dashboard-activity-tooltip-count"><span class="dashboard-activity-tooltip-dot"></span><strong x-text="activityTip?.count"></strong><span x-text="activityTip?.count === 1 ? 'application' : 'applications'"></span></span>
        </div>
    </template>
    @if($showDay)
    <div x-data role="dialog" aria-modal="true" aria-labelledby="activity-day-title" tabindex="-1" x-dialog="$wire.showDay" data-dialog-close="$wire.set('showDay', false)" class="activity-day-overlay">
        <div class="activity-day-backdrop" wire:click="$set('showDay', false)"></div>
        <section class="activity-day-dialog">
            <header><div><span>Application activity</span><h2 id="activity-day-title">{{ $selectedLabel }}</h2><p>{{ $dayApplications->total() }} {{ Str::plural('application',$dayApplications->total()) }} · archived records included</p></div><button type="button" wire:click="$set('showDay', false)" aria-label="Close day details"><i class="ph ph-x" aria-hidden="true"></i></button></header>
            <div class="activity-day-list">
                @forelse($dayApplications as $application)<a href="{{ route('jobs.show',$application) }}" wire:navigate wire:key="activity-application-{{ $application->id }}"><div><strong>{{ $application->position }}</strong><span>{{ $application->company_name }}</span><small>{{ $application->recruitment_stage }} · {{ $application->application_status }}{{ $application->is_archived ? ' · Archived' : '' }}</small></div><i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>@empty<div class="activity-day-empty"><h3>No applications on this date</h3><p>Choose another day to explore your activity.</p></div>@endforelse
            </div>
            <footer>{{ $dayApplications->links() }}<button type="button" wire:click="$set('showDay', false)">Back to calendar</button></footer>
        </section>
    </div>
    @endif
</section>
