<x-app-layout>
    @php
        $hasAccess = !\App\Models\Setting::isMonetizationEnabled() || auth()->user()->isPremium();
        $statuses = $summary['statuses'];
        $periodLabel = match ($timeFilter) { 'weekly' => 'Last 7 days', 'monthly' => 'Last 30 days', default => 'All time'};
        $statusRows = [
            ['name' => 'In progress', 'key' => 'On Process', 'tone' => 'blue'],
            ['name' => 'Accepted', 'key' => 'Accepted', 'tone' => 'lavender'],
            ['name' => 'Rejected', 'key' => 'Rejected', 'tone' => 'peach'],
        ];
        $acceptedShare = $applicationsCount ? round($statuses['Accepted'] / $applicationsCount * 100, 1) : 0;
        $topSource = $summary['sources']->first();
    @endphp
    <div class="workspace-page summary-page {{ !$hasAccess ? 'summary-page--gate' : '' }}">
        <div class="workspace-page__container">
            @if($hasAccess)
                <header class="workspace-page__header">
                    <div>
                        <h1>Summary</h1>
                        <p>Understand your progress and where your opportunities come from.</p>
                    </div>
                    <nav class="summary-periods" aria-label="Summary period">
                        @foreach(['weekly' => '7 days', 'monthly' => '30 days', 'all' => 'All time'] as $value => $label)
                            <a href="{{ route('summary', ['timeFilter' => $value]) }}" wire:navigate
                                class="{{ $timeFilter === $value ? 'is-active' : '' }}" @if($timeFilter === $value)
                                aria-current="page" @endif>{{ $label }}</a>
                        @endforeach
                    </nav>
                </header>
            @endif
            @if(!$hasAccess)
                <section class="summary-premium summary-enter" aria-labelledby="summary-premium-title">
                    <div class="summary-premium-copy">
                        <h2 id="summary-premium-title">A clearer view<br class="summary-desktop-break"> of your job
                            search<span class="summary-heading-dot">.</span></h2>
                        <p class="summary-premium-intro">Track your velocity and see where<br class="summary-desktop-break">
                            your best opportunities come from.</p>
                        <p class="summary-premium-desc">Summary brings together your weekly application momentum, interview
                            conversion stages, and top recruitment sources into one connected intelligence view — so you can
                            spend energy where offers actually happen.</p>

                        <div class="summary-premium-actions">
                            <a href="{{ route('payment.premium') }}" class="summary-action-primary">
                                <span>Explore Premium</span>
                            </a>
                            <a href="{{ route('tracker') }}" wire:navigate class="summary-action-secondary">
                                <span>Back to applications</span>
                                <span aria-hidden="true">→</span>
                            </a>
                        </div>

                        <p class="summary-premium-footnote">
                            <i class="ph-fill ph-shield-check" aria-hidden="true"></i>
                            <span>Instant access · Included with your TraKerja Premium membership.</span>
                        </p>
                    </div>

                    <div class="summary-premium-art" aria-hidden="true">
                        <div class="summary-art-caption">A clearer view · Connected together</div>
                        <svg class="summary-art-connections" viewBox="0 0 380 380" fill="none">
                            <path class="summary-orbit-path summary-orbit-path--one"
                                d="M45 280C20 180 90 80 200 90C300 100 350 200 300 300" stroke="#b4cce2" stroke-width="1.6"
                                stroke-dasharray="5 7" />
                            <path class="summary-orbit-path summary-orbit-path--two" d="M90 260C140 340 250 340 290 270"
                                stroke="#cfbfe0" stroke-width="1.6" stroke-dasharray="5 7" />
                        </svg>

                        <div class="summary-art-avatar">
                            <img src="{{ asset('images/avatars/sky.svg') }}" alt="" width="112" height="112">
                        </div>

                        <div class="summary-art-speech">
                            <span class="summary-speech-dot"></span>
                            <span>See where your offers happen!</span>
                            <i class="ph-fill ph-sparkle summary-speech-spark" aria-hidden="true"></i>
                        </div>

                        <div class="summary-art-float summary-float-outcomes">
                            <div class="summary-float-ring-wrap">
                                <svg viewBox="0 0 36 36" class="summary-donut-svg" aria-hidden="true">
                                    <circle cx="18" cy="18" r="14" fill="none" stroke="#e8edf2" stroke-width="4.5" />
                                    <circle cx="18" cy="18" r="14" fill="none" stroke="#2579ca" stroke-width="4.5"
                                        stroke-dasharray="45 100" stroke-linecap="round" />
                                    <circle cx="18" cy="18" r="14" fill="none" stroke="#a382c7" stroke-width="4.5"
                                        stroke-dasharray="28 100" stroke-dashoffset="-47" stroke-linecap="round" />
                                </svg>
                                <div class="summary-float-ring-text">
                                    <strong>3 Offers</strong>
                                    <small>78% in progress</small>
                                </div>
                            </div>
                        </div>

                        <div class="summary-art-float summary-float-source">
                            <div class="summary-source-icon">
                                <i class="ph-fill ph-briefcase" aria-hidden="true"></i>
                            </div>
                            <div class="summary-source-info">
                                <span>Top Source</span>
                                <strong>LinkedIn (85%)</strong>
                            </div>
                            <i class="ph ph-arrow-up-right summary-source-arrow" aria-hidden="true"></i>
                        </div>

                        <div class="summary-art-float summary-float-bars-card">
                            <div class="summary-spark-bars" aria-hidden="true">
                                <span class="summary-spark-bar" style="height: 10px"></span>
                                <span class="summary-spark-bar" style="height: 16px"></span>
                                <span class="summary-spark-bar" style="height: 13px"></span>
                                <span class="summary-spark-bar" style="height: 22px"></span>
                                <span class="summary-spark-bar summary-spark-bar--highlight" style="height: 26px"></span>
                            </div>
                            <div class="summary-bars-info">
                                <strong>18 Applied</strong>
                                <small>+35% velocity</small>
                            </div>
                        </div>

                        <span class="summary-art-spark summary-spark-one">✧</span>
                        <span class="summary-art-spark summary-spark-two">✦</span>

                        <div class="summary-art-footer">
                            <span>Every application, stage &amp; outcome.</span>
                            <strong>All connected in one view.</strong>
                        </div>
                    </div>
                </section>
            @else
            <div class="summary-scope">
                <p>{{ $periodLabel }}@if($dateRange['start']) · {{ $dateRange['start']->format('d M') }} –
                {{ $dateRange['end']->format('d M Y') }}@endif</p><span>Includes archived applications · grouped by
                    date applied</span>
            </div>
            <section class="summary-overview summary-overview--split summary-enter" aria-label="Application totals">
                <div class="summary-stat-group summary-stat-group--activity">
                    <div class="summary-stat summary-stat--total"><span>Total
                            applications</span><strong>{{ $applicationsCount }}</strong>
                        <p>{{ $periodLabel }}</p>
                    </div>
                    <div class="summary-stat summary-stat--progress"><span>In
                            progress</span><strong>{{ $statuses['On Process'] }}</strong>
                        <p>{{ $applicationsCount ? round($statuses['On Process'] / $applicationsCount * 100, 1) : 0 }}%
                            of applications</p>
                    </div>
                </div>
                <div class="summary-stat-group summary-stat-group--outcomes">
                    <div class="summary-stat summary-stat--offers"><span>Offers &
                            accepted</span><strong>{{ $summary['offers'] }}</strong>
                        <p>Offering stage or accepted</p>
                    </div>
                    <div class="summary-stat summary-stat--rejected">
                        <span>Rejected</span><strong>{{ $statuses['Rejected'] }}</strong>
                        <p>{{ $applicationsCount ? round($statuses['Rejected'] / $applicationsCount * 100, 1) : 0 }}% of
                            applications</p>
                    </div>
                </div>
                <footer class="summary-stats-footer">
                    <p>{{ $summary['archived'] }} archived records included · Offers may also be in progress.</p><a
                        href="{{ route('tracker') }}" wire:navigate>View applications <i class="ph ph-arrow-up-right"
                            aria-hidden="true"></i></a>
                </footer>
            </section>

            <div class="summary-chart-grid">
                @include('summary.partials.activity-chart')
                @php($outcomeRows = array_map(fn($row) => ['name' => $row['name'], 'share' => $applicationsCount ? round($statuses[$row['key']] / $applicationsCount * 100, 1) : 0], $statusRows))
                <section class="workspace-card summary-outcomes summary-enter" aria-labelledby="summary-outcomes-title"
                    x-data="{ selected: 1, hovered: null, rows: @js($outcomeRows) }">
                    <div class="workspace-section-heading">
                        <div>
                            <h2 id="summary-outcomes-title">Where things stand</h2>
                            <p>Status distribution · {{ strtolower($periodLabel) }}</p>
                        </div>
                    </div>
                    <div class="summary-donut" id="summary-outcome-chart">
                        <svg viewBox="0 0 200 200" role="img"
                            aria-label="{{ $statuses['On Process'] }} in progress, {{ $statuses['Accepted'] }} accepted, {{ $statuses['Rejected'] }} rejected">
                            <circle cx="100" cy="100" r="78" fill="none" stroke="#edf0f4" stroke-width="18" />
                            @php($offset = 0)
                            @foreach($statusRows as $index => $row)
                            @php($share = $statuses[$row['key']] / max(1, $applicationsCount) * 100)
                            <circle cx="100" cy="100" r="78" pathLength="100" fill="none" stroke-width="18"
                                class="summary-outcome-ring summary-ring--{{ $row['tone'] }}"
                                :class="{ 'is-highlighted': (hovered ?? selected) === {{ $index }}, 'is-muted': (hovered ?? selected) !== {{ $index }} }"
                                stroke-dasharray="{{ $share }} {{ 100 - $share }}" stroke-dashoffset="{{ -$offset }}"
                                transform="rotate(-90 100 100)" />
                            @php($offset += $share)
                            @endforeach
                        </svg>
                        <div><strong x-text="rows[hovered ?? selected].share + '%'">{{ $acceptedShare }}%</strong><span
                                x-text="rows[hovered ?? selected].name">Accepted</span></div>
                    </div>
                    <dl class="summary-status-legend">
                        @foreach($statusRows as $index => $row)
                            <div :class="{ 'is-active': (hovered ?? selected) === {{ $index }} }"
                                @mouseenter="hovered = {{ $index }}" @mouseleave="hovered = null">
                                <dt><button type="button" @click="selected = {{ $index }}" @focus="hovered = {{ $index }}"
                                        @blur="hovered = null" :aria-pressed="selected === {{ $index }}"
                                        aria-controls="summary-outcome-chart"><i
                                            class="summary-dot summary-dot--{{ $row['tone'] }}"
                                            aria-hidden="true"></i>{{ $row['name'] }}</button></dt>
                                <dd><strong>{{ $statuses[$row['key']] }}</strong><span>{{ $applicationsCount ? round($statuses[$row['key']] / $applicationsCount * 100, 1) : 0 }}%</span>
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                    <p class="workspace-hint">Select a status to explore. Offers are shown separately above.</p>
                </section>
            </div>

            <section class="summary-insights workspace-card--glass summary-enter"
                aria-labelledby="summary-insights-title">
                <h2 id="summary-insights-title">A closer look</h2>
                <div>
                    <article><span>Most-used
                            source</span><strong>{{ $topSource['name'] ?? 'Not recorded yet' }}</strong>
                        <p>{{ $topSource ? $topSource['total'] . ' applications in this period.' : 'Your application sources will appear here.' }}
                        </p>
                    </article>
                    <article><span>Accepted outcomes</span><strong>{{ $statuses['Accepted'] }} <small>of
                                {{ $applicationsCount }}</small></strong>
                        <p>{{ $acceptedShare }}% have Accepted status.</p>
                    </article>
                    <article><span>Recorded interview
                            stages</span>@php($interviewStageCount = $summary['sources']->sum('interviews'))<strong>{{ $interviewStageCount }}</strong>
                        <p>Currently recorded as HR or User Interview. This is not a historical conversion rate.</p>
                    </article>
                </div>
            </section>

            <section class="workspace-card summary-analysis summary-enter" aria-labelledby="summary-analysis-title"
                x-data="{ tab: 'sources' }">
                <div class="workspace-section-heading">
                    <div>
                        <h2 id="summary-analysis-title">Explore your applications</h2>
                        <p>Compare volume and recorded outcomes within {{ strtolower($periodLabel) }}.</p>
                    </div><span class="summary-analysis-count">{{ $applicationsCount }} records</span>
                </div>
                <div class="summary-analysis-tabs" role="group" aria-label="Analysis category">
                    @foreach(['sources' => 'Sources', 'roles' => 'Roles', 'locations' => 'Locations', 'companies' => 'Companies', 'employment' => 'Employment'] as $key => $label)<button
                        type="button" @click="tab = '{{ $key }}'" :class="{ 'is-active': tab === '{{ $key }}' }"
                        :aria-pressed="tab === '{{ $key }}'"
                    aria-controls="summary-panel-{{ $key }}">{{ $label }}</button>@endforeach</div>
                @foreach(['sources' => 'Sources', 'roles' => 'Roles', 'locations' => 'Locations', 'companies' => 'Companies', 'employment' => 'Employment types'] as $key => $label)
                    <div id="summary-panel-{{ $key }}" x-show="tab === '{{ $key }}'" @if($key !== 'sources') x-cloak @endif
                        aria-label="{{ $label }} analysis">
                        @include('summary.partials.explorer', ['rows' => $summary[$key], 'type' => $key, 'label' => $label])
                    </div>
                @endforeach
                <p class="workspace-hint">Interview-stage counts use the current recorded HR / User Interview stage.
                    Archived records remain part of this analysis.</p>
            </section>

            @include('summary.partials.stage-chart')
            <footer class="summary-footer">
                <p>Keep your stages and outcomes up to date for a more useful summary.</p><a
                    href="{{ route('tracker') }}" wire:navigate class="workspace-button workspace-button--primary">Go to
                    applications</a>
            </footer>
            @endif
        </div>
    </div>
</x-app-layout>