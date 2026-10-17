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
                <h3 id="application-activity-title">{{ __('Application activity') }}</h3>
                <p>{{ __(':value1 applications · last :value2 months. Click a day to explore.', ['value1' => ($totalHeatmapApps), 'value2' => ($months)]) }}</p>
            </div>
            <div class="dashboard-activity-average">
                <span>{{ __('Daily average') }}</span>
                <strong>{{ number_format($dailyAverage, 2) }}</strong>
            </div>
        </header>

        <div class="activity-range" role="group" aria-label="{{ __('Activity date range') }}">
        @foreach([3,6,12] as $range)<button type="button" wire:click="setRange({{ $range }})" aria-pressed="{{ $months===$range ? 'true' : 'false' }}" class="{{ $months===$range ? 'is-active' : '' }}">{{ __(':value1 months', ['value1' => ($range)]) }}</button>@endforeach
        <span wire:loading wire:target="setRange">{{ __('Updating…') }}</span>
    </div>
    @php
            $lastMonth = '';
            $lastMonthColIndex = -10;
            $dayOrder = [1, 2, 3, 4, 5, 6, 0];
            $heatmapColors = ['var(--heatmap-c0)', 'var(--heatmap-c1)', 'var(--heatmap-c2)', 'var(--heatmap-c3)', 'var(--heatmap-c4)'];
        @endphp
        <div class="dashboard-heatmap-scroll custom-scrollbar" tabindex="0" role="region" aria-label="{{ __('Daily application activity over the last :value1 months', ['value1' => ($months)]) }}">
            <div class="dashboard-heatmap" style="--heatmap-weeks: {{ count($heatmapWeeks) }};">
                <div class="dashboard-heatmap-days" aria-hidden="true">
                    <span>{{ __('Mon') }}</span><span></span><span>{{ __('Wed') }}</span><span></span><span>{{ __('Fri') }}</span><span></span><span>{{ __('Sun') }}</span>
                </div>
                <div class="dashboard-heatmap-weeks">
                    @foreach($heatmapWeeks as $weekKey => $days)
                        @php
                            $currentMonth = \Carbon\Carbon::parse($weekKey)->locale(app()->getLocale())->translatedFormat('M');
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
                                    <button type="button" class="dashboard-heatmap-cell" wire:key="activity-day-{{ $day['date'] }}" wire:click="selectDate('{{ $day['date'] }}')" @click="activityTip = null" aria-label="{{ __(':value1 :value2 on :value3', ['value1' => ($count), 'value2' => (Str::plural('application', $count)), 'value3' => ($day['formattedDate'])]) }}" data-date="{{ $day['formattedDate'] }}" data-count="{{ $count }}" @mouseenter="showActivityTip($event)" @mouseleave="hideActivityTip()" @focus="showActivityTip($event)" @blur="hideActivityTip()" style="background: {{ $heatmapColors[min(4, $count)] }};"></button>
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
                <button type="button" wire:click="moveMonth(-1)" aria-label="{{ __('Previous activity month') }}" @disabled(!$canPreviousMonth) wire:loading.attr="disabled" wire:target="moveMonth"><i class="ph ph-caret-left" aria-hidden="true"></i></button>
                <button type="button" wire:click="moveMonth(1)" aria-label="{{ __('Next activity month') }}" @disabled(!$canNextMonth) wire:loading.attr="disabled" wire:target="moveMonth"><i class="ph ph-caret-right" aria-hidden="true"></i></button>
            </div></header>
            <div class="activity-month-grid" aria-label="{{ __(':value1 application calendar', ['value1' => ($monthLabel)]) }}">
                @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $weekday)<span class="activity-weekday">{{ $weekday }}</span>@endforeach
                @foreach($calendarDays as $day)
                    @if($day['inMonth'])
                    <button type="button" wire:key="mobile-activity-{{ $day['date'] }}" wire:click="selectDate('{{ $day['date'] }}')" @disabled(!$day['enabled']) aria-label="{{ __(':value1 :value2 on :value3', ['value1' => ($day['count']), 'value2' => (Str::plural('application', $day['count'])), 'value3' => ($day['date'])]) }}" @if($day['today']) aria-current="date" @endif class="activity-month-day" data-activity-level="{{ min(4, $day['count']) }}" title="{{ \Carbon\Carbon::parse($day['date'])->locale(app()->getLocale())->translatedFormat('j M Y') }} · {{ $day['count'] }} {{ Str::plural('application', $day['count']) }}" style="--day-color: {{ $heatmapColors[min(4, $day['count'])] }};"><span aria-hidden="true">{{ $day['day'] }}</span></button>
                    @else<span class="activity-month-spacer" aria-hidden="true"></span>@endif
                @endforeach
            </div>
            <p>{{ __('Tap a square to explore that day.') }}</p>
        </div>

        <footer class="dashboard-activity-footer">
            <a href="{{ route('tracker') }}" wire:navigate>{{ __('View applications') }} <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>
            <div class="dashboard-heatmap-legend" aria-label="{{ __('Color intensity: fewer to more daily applications') }}">
                <span>{{ __('Less') }}</span>
                @foreach($heatmapColors as $color)
                    <span class="dashboard-heatmap-key" style="background: {{ $color }};" aria-hidden="true"></span>
                @endforeach
                <span>{{ __('More') }}</span>
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
            <header><div><span>{{ __('Application activity') }}</span><h2 id="activity-day-title">{{ $selectedLabel }}</h2><p>{{ __(':value1 :value2 · archived records included', ['value1' => ($dayApplications->total()), 'value2' => (Str::plural('application',$dayApplications->total()))]) }}</p></div><button type="button" wire:click="$set('showDay', false)" aria-label="{{ __('Close day details') }}"><i class="ph ph-x" aria-hidden="true"></i></button></header>
            <div class="activity-day-list">
                @forelse($dayApplications as $application)<a href="{{ route('jobs.show',$application) }}" wire:navigate wire:key="activity-application-{{ $application->id }}"><div><strong>{{ $application->position }}</strong><span>{{ $application->company_name }}</span><small>{{ \App\Support\UiLabel::value($application->recruitment_stage) }} · {{ \App\Support\UiLabel::value($application->application_status) }}{{ $application->is_archived ? __(' · Archived') : '' }}</small></div><i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>@empty<div class="activity-day-empty"><h3>{{ __('No applications on this date') }}</h3><p>{{ __('Choose another day to explore your activity.') }}</p></div>@endforelse
            </div>
            <footer>{{ $dayApplications->links() }}<button type="button" wire:click="$set('showDay', false)">{{ __('Back to calendar') }}</button></footer>
        </section>
    </div>
    @endif
</section>
