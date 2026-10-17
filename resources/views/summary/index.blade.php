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
        $analysisTones = ['sources' => 'sage', 'roles' => 'blue', 'locations' => 'sage', 'companies' => 'plum', 'employment' => 'clay'];
    @endphp
    <div class="workspace-page summary-page {{ !$hasAccess ? 'summary-page--gate' : 'summary-page--workspace' }}">
        <div class="workspace-page__container">
            @if($hasAccess)
                <header class="workspace-page__header">
                    <div>
                        <h1>{{ __('Summary') }}</h1>
                        <p>{{ __('Understand your progress and where your opportunities come from.') }}</p>
                    </div>
                    <nav class="summary-periods" aria-label="{{ __('Summary period') }}">
                        @foreach(['weekly' => '7 days', 'monthly' => '30 days', 'all' => 'All time'] as $value => $label)
                            <a href="{{ route('summary', ['timeFilter' => $value]) }}" wire:navigate
                                class="{{ $timeFilter === $value ? 'is-active' : '' }}" @if($timeFilter === $value)
                                aria-current="page" @endif>{{ __($label) }}</a>
                        @endforeach
                    </nav>
                </header>
            @endif
            @if(!$hasAccess)
                <section class="summary-premium summary-enter" aria-labelledby="summary-premium-title">
                    <div class="summary-premium-copy">
                        <h2 id="summary-premium-title">{{ __('A clearer view') }}<br class="summary-desktop-break"> {{ __('of your job search') }}<span class="summary-heading-dot">.</span></h2>
                        <p class="summary-premium-intro">{{ __('Track your velocity and see where') }}<br class="summary-desktop-break">
                            {{ __('your best opportunities come from.') }}</p>
                        <p class="summary-premium-desc">{{ __('Summary brings together your weekly application momentum, interview conversion stages, and top recruitment sources into one connected intelligence view — so you can spend energy where offers actually happen.') }}</p>

                        <div class="summary-premium-actions">
                            <a href="{{ route('payment.premium') }}" class="summary-action-primary">
                                <span>{{ __('Explore Premium') }}</span>
                            </a>
                            <a href="{{ route('tracker') }}" wire:navigate class="summary-action-secondary">
                                <span>{{ __('Back to applications') }}</span>
                                <span aria-hidden="true">→</span>
                            </a>
                        </div>

                        <p class="summary-premium-footnote">
                            <i class="ph-fill ph-shield-check" aria-hidden="true"></i>
                            <span>{{ __('Instant access · Included with your TraKerja Premium membership.') }}</span>
                        </p>
                    </div>

                    <div class="summary-premium-art" aria-hidden="true">
                        <div class="summary-art-caption">{{ __('A clearer view · Connected together') }}</div>
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
                            <span>{{ __('See where your offers happen!') }}</span>
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
                                    <strong>{{ __('3 Offers') }}</strong>
                                    <small>{{ __('78% in progress') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="summary-art-float summary-float-source">
                            <div class="summary-source-icon">
                                <i class="ph-fill ph-briefcase" aria-hidden="true"></i>
                            </div>
                            <div class="summary-source-info">
                                <span>{{ __('Top Source') }}</span>
                                <strong>{{ __('LinkedIn (85%)') }}</strong>
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
                                <strong>{{ __('18 Applied') }}</strong>
                                <small>{{ __('+35% velocity') }}</small>
                            </div>
                        </div>

                        <span class="summary-art-spark summary-spark-one">✧</span>
                        <span class="summary-art-spark summary-spark-two">✦</span>

                        <div class="summary-art-footer">
                            <span>{{ __('Every application, stage & outcome.') }}</span>
                            <strong>{{ __('All connected in one view.') }}</strong>
                        </div>
                    </div>
                </section>
            @else
            <div class="summary-scope">
                <p>{{ __($periodLabel) }}@if($dateRange['start']) · {{ $dateRange['start']->locale(app()->getLocale())->translatedFormat('d M') }} –
                {{ $dateRange['end']->locale(app()->getLocale())->translatedFormat('d M Y') }}@endif</p><span>{{ __('Includes archived applications · grouped by date applied') }}</span>
            </div>
            <section class="summary-report-overview summary-enter" aria-label="{{ __('Application totals') }}">
                <div class="summary-report-total">
                    <span class="summary-report-eyebrow">{{ __('YOUR APPLICATION OVERVIEW') }}</span>
                    <div><strong>{{ $applicationsCount }}</strong><span>{{ __('total') }}<br>{{ __('applications') }}</span></div>
                    <p>{{ __(':value1 · :value2 archived records included', ['value1' => ($periodLabel), 'value2' => ($summary['archived'])]) }}</p>
                    <a href="{{ route('tracker') }}" wire:navigate>{{ __('View your applications') }} <span aria-hidden="true">↗</span></a>
                </div>
                <dl class="summary-report-stats">
                    <div data-tone="blue"><dt><i aria-hidden="true"></i>{{ __('In progress') }}</dt><dd><strong>{{ $statuses['On Process'] }}</strong><span>{{ __(':value1% of applications', ['value1' => ($applicationsCount ? round($statuses['On Process'] / $applicationsCount * 100, 1) : 0)]) }}</span></dd></div>
                    <div data-tone="sage"><dt><i aria-hidden="true"></i>{{ __('Offers & accepted') }}</dt><dd><strong>{{ $summary['offers'] }}</strong><span>{{ __('Offering stage or accepted') }}</span></dd></div>
                    <div data-tone="clay"><dt><i aria-hidden="true"></i>{{ __('Rejected') }}</dt><dd><strong>{{ $statuses['Rejected'] }}</strong><span>{{ __(':value1% of applications', ['value1' => ($applicationsCount ? round($statuses['Rejected'] / $applicationsCount * 100, 1) : 0)]) }}</span></dd></div>
                </dl>
                <p class="summary-report-footnote">{{ __('Offers can overlap with in-progress applications. Status reflects your current records.') }}</p>
            </section>

            <div class="summary-chart-grid summary-report-charts">
                @include('summary.partials.activity-chart')
                @php($outcomeRows = array_map(fn($row) => ['name' => $row['name'], 'share' => $applicationsCount ? round($statuses[$row['key']] / $applicationsCount * 100, 1) : 0], $statusRows))
                <section class="workspace-card summary-outcomes summary-enter" aria-labelledby="summary-outcomes-title"
                    x-data="{ selected: 1, hovered: null, rows: @js($outcomeRows) }">
                    <div class="workspace-section-heading">
                        <div>
                            <h2 id="summary-outcomes-title">{{ __('Where things stand') }}</h2>
                            <p>{{ __('Status distribution · :value1', ['value1' => (strtolower($periodLabel))]) }}</p>
                        </div>
                    </div>
                    <div class="summary-donut" id="summary-outcome-chart">
                        <svg viewBox="0 0 200 200" role="img"
                            aria-label="{{ __(':value1 in progress, :value2 accepted, :value3 rejected', ['value1' => ($statuses['On Process']), 'value2' => ($statuses['Accepted']), 'value3' => ($statuses['Rejected'])]) }}">
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
                                x-text="rows[hovered ?? selected].name">{{ __('Accepted') }}</span></div>
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
                    <p class="workspace-hint">{{ __('Select a status to explore. Offers are shown separately above.') }}</p>
                </section>
            </div>

            <section class="summary-insights summary-enter"
                aria-labelledby="summary-insights-title">
                <h2 id="summary-insights-title">{{ __('A closer look') }}</h2>
                <div>
                    <article><span>{{ __('Most-used source') }}</span><strong>{{ $topSource['name'] ?? 'Not recorded yet' }}</strong>
                        <p>{{ $topSource ? $topSource['total'] . __(' applications · ') . round($topSource['total'] / max(1, $applicationsCount) * 100, 1) . __('% of your selected records. Volume does not measure source quality.') : __('Your application sources will appear here.') }}
                        </p>
                    </article>
                    <article><span>{{ __('Accepted outcomes') }}</span><strong>{{ $statuses['Accepted'] }} <small>{{ __('of :value1', ['value1' => ($applicationsCount)]) }}</small></strong>
                        <p>{{ __(':value1% have Accepted status.', ['value1' => ($acceptedShare)]) }}</p>
                    </article>
                    <article><span>{{ __('Recorded interview stages') }}</span>@php($interviewStageCount = $summary['sources']->sum('interviews'))<strong>{{ $interviewStageCount }}</strong>
                        <p>{{ __('Currently recorded as HR or User Interview. This is not a historical conversion rate.') }}</p>
                    </article>
                </div>
            </section>

            <section class="workspace-card summary-analysis summary-enter" aria-labelledby="summary-analysis-title"
                x-data="{ tab: 'sources' }">
                <div class="workspace-section-heading">
                    <div>
                        <h2 id="summary-analysis-title">{{ __('Explore your applications') }}</h2>
                        <p>{{ __('Compare volume and recorded outcomes within :value1.', ['value1' => (strtolower($periodLabel))]) }}</p>
                    </div><span class="summary-analysis-count">{{ __(':value1 records', ['value1' => ($applicationsCount)]) }}</span>
                </div>
                <div class="summary-analysis-workbench"><div class="summary-analysis-tabs" role="group" aria-label="{{ __('Analysis category') }}">
                    @foreach(['sources' => 'Sources', 'roles' => 'Roles', 'locations' => 'Locations', 'companies' => 'Companies', 'employment' => 'Employment'] as $key => $label)<button
                        data-tone="{{ $analysisTones[$key] }}" type="button" @click="tab = '{{ $key }}'" :class="{ 'is-active': tab === '{{ $key }}' }"
                        :aria-pressed="tab === '{{ $key }}'"
                    aria-controls="summary-panel-{{ $key }}"><span>{{ __($label) }}</span><small>{{ $summary[$key]->count() }}</small></button>@endforeach</div>
                <div class="summary-analysis-content">
                @foreach(['sources' => 'Sources', 'roles' => 'Roles', 'locations' => 'Locations', 'companies' => 'Companies', 'employment' => 'Employment types'] as $key => $label)
                    <div id="summary-panel-{{ $key }}" class="summary-analysis-panel" data-tone="{{ $analysisTones[$key] }}" x-show="tab === '{{ $key }}'" @if($key !== 'sources') x-cloak @endif
                        aria-label="{{ __($label) }} analysis">
                        @include('summary.partials.explorer', ['rows' => $summary[$key], 'type' => $key, 'label' => $label])
                    </div>
                @endforeach
                </div></div>
                <p class="workspace-hint">{{ __('Interview-stage counts use the current recorded HR / User Interview stage. Archived records remain part of this analysis.') }}</p>
            </section>

            @include('summary.partials.stage-chart')
            <footer class="summary-footer">
                <p>{{ __('Keep your stages and outcomes up to date for a more useful summary.') }}</p><a
                    href="{{ route('tracker') }}" wire:navigate class="workspace-button workspace-button--primary">{{ __('Go to applications') }}</a>
            </footer>
            @endif
        </div>
    </div>
</x-app-layout>