<x-admin-layout>
    @php
        $features = [
            ['label' => __('Overall Satisfaction'),     'field' => 'avgQ1Overall',     'val' => $avgQ1Overall,     'icon' => 'sparkle'],
            ['label' => __('Ease of Navigation'),        'field' => 'avgQ2Navigation',  'val' => $avgQ2Navigation,  'icon' => 'compass'],
            ['label' => __('Page Speed & Performance'),  'field' => 'avgQ3Speed',       'val' => $avgQ3Speed,       'icon' => 'lightning'],
            ['label' => __('ATS CV Builder'),            'field' => 'avgQ4CvBuilder',   'val' => $avgQ4CvBuilder,   'icon' => 'file-text'],
            ['label' => __('AI Resume Analyzer'),        'field' => 'avgQ5AiAnalyzer',  'val' => $avgQ5AiAnalyzer,  'icon' => 'robot'],
            ['label' => __('Application Tracker'),       'field' => 'avgQ6JobTracker',  'val' => $avgQ6JobTracker,  'icon' => 'kanban'],
            ['label' => __('Cover Letter Generator'),    'field' => 'avgQ7CoverLetter', 'val' => $avgQ7CoverLetter, 'icon' => 'envelope-simple'],
            ['label' => __('Interview Scheduling'),      'field' => 'avgQ8Interviews',  'val' => $avgQ8Interviews,  'icon' => 'calendar-check'],
            ['label' => __('Premium Membership Value'),  'field' => 'avgQ9Premium',     'val' => $avgQ9Premium,     'icon' => 'crown'],
            ['label' => __('Likelihood to recommend'),   'field' => 'avgQ10Recommend',  'val' => $avgQ10Recommend,  'icon' => 'thumbs-up'],
            ['label' => __('Visual UI Design'),          'field' => 'avgQ11Design',     'val' => $avgQ11Design,     'icon' => 'paint-brush-broad'],
            ['label' => __('CV Templates Quality'),      'field' => 'avgQ12CvTemplates','val' => $avgQ12CvTemplates,'icon' => 'layout'],
        ];

        $positiveCount = ($distributions[5] ?? 0) + ($distributions[4] ?? 0);
        $positiveRate = $totalRespondents > 0 ? round(($positiveCount / $totalRespondents) * 100) : 0;
    @endphp

    <div class="admin-operations admin-survey-workspace">

        {{-- ==========================================================================
             PAGE HEADER (Harmonized with User Management & Summary)
             ========================================================================== --}}
        <section class="as-welcome">
            <div>
                <h1>{{ __('User Satisfaction Survey') }}</h1>
                <p>{{ __('Monitor member sentiment, track 12-point feature satisfaction metrics, and inspect qualitative feedback.') }}</p>
            </div>

            {{-- Survey Status Switcher --}}
            <div class="as-header-actions">
                <form method="POST" action="{{ route('admin.survey.toggle') }}" class="as-toggle-form">
                    @csrf
                    <input type="hidden" name="enabled" value="{{ $surveyEnabled ? 0 : 1 }}">
                    <div class="as-toggle-status {{ $surveyEnabled ? 'is-active' : 'is-inactive' }}">
                        <span class="as-toggle-dot"></span>
                        <div class="as-toggle-copy">
                            <strong>{{ $surveyEnabled ? __('Survey Active') : __('Survey Paused') }}</strong>
                            <span>{{ $surveyEnabled ? __('Prompts shown to 3+ day accounts') : __('Invitations currently paused') }}</span>
                        </div>
                    </div>
                    <button type="submit" class="as-btn {{ $surveyEnabled ? 'as-btn--quiet' : 'as-btn--primary' }}">
                        <i class="ph ph-{{ $surveyEnabled ? 'pause' : 'play' }}" aria-hidden="true"></i>
                        {{ $surveyEnabled ? __('Pause Survey') : __('Enable Survey') }}
                    </button>
                </form>
            </div>
        </section>

        {{-- ==========================================================================
             ALERT NOTICES
             ========================================================================== --}}
        @if(session('success'))
            <div class="as-notice" role="status">
                <i class="ph ph-check-circle" aria-hidden="true"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- ==========================================================================
             OVERVIEW METRIC STRIP (Executive Scorecard)
             ========================================================================== --}}
        <section class="as-metric-strip" aria-label="{{ __('Survey executive telemetry') }}">
            {{-- Total Responses --}}
            <div class="as-metric-card as-metric-card--total">
                <div class="as-metric-head">
                    <span class="as-metric-label">
                        <i class="ph ph-users" aria-hidden="true"></i>
                        {{ __('Total Responses') }}
                    </span>
                </div>
                <strong>{{ number_format($totalRespondents) }}</strong>
                <small>{{ __('Submitted by registered members') }}</small>
            </div>

            {{-- Overall CSAT --}}
            <div class="as-metric-card as-metric-card--csat">
                <div class="as-metric-head">
                    <span class="as-metric-label">
                        <i class="ph ph-star-fill text-amber-500" aria-hidden="true"></i>
                        {{ __('Overall Satisfaction') }}
                    </span>
                </div>
                <strong>{{ $totalRespondents ? $avgQ1Overall : '—' }}<em>/ 5.0</em></strong>
                <small>{{ __('Platform-wide CSAT benchmark') }}</small>
            </div>

            {{-- Ease of Navigation --}}
            <div class="as-metric-card as-metric-card--nav">
                <div class="as-metric-head">
                    <span class="as-metric-label">
                        <i class="ph ph-compass" aria-hidden="true"></i>
                        {{ __('Ease of Navigation') }}
                    </span>
                </div>
                <strong>{{ $totalRespondents ? $avgQ2Navigation : '—' }}<em>/ 5.0</em></strong>
                <small>{{ __('Finding features & intuitive UX') }}</small>
            </div>

            {{-- Visual Design --}}
            <div class="as-metric-card as-metric-card--design">
                <div class="as-metric-head">
                    <span class="as-metric-label">
                        <i class="ph ph-paint-brush-broad" aria-hidden="true"></i>
                        {{ __('Visual UI Design') }}
                    </span>
                </div>
                <strong>{{ $totalRespondents ? $avgQ11Design : '—' }}<em>/ 5.0</em></strong>
                <small>{{ __('Look and feel of TraKerja') }}</small>
            </div>
        </section>

        {{-- ==========================================================================
             ANALYTICAL 2-COLUMN GRID (Features vs Distribution)
             ========================================================================== --}}
        <div class="as-analytics-grid">

            {{-- Feature Experience Scores (12 Metrics) --}}
            <section class="as-panel" aria-labelledby="features-breakdown-heading">
                <div class="as-panel-heading">
                    <div>
                        <h2 id="features-breakdown-heading">
                            {{ __('Experience by Feature') }} <span>{{ __('12 Metrics') }}</span>
                        </h2>
                        <p>{{ __('Average score from 1.0 to 5.0 across each platform module') }}</p>
                    </div>
                </div>

                <div class="as-scores-grid">
                    @foreach($features as $item)
                        @php
                            $scoreFloat = (float) $item['val'];
                            $pct = $totalRespondents > 0 ? min(100, max(0, ($scoreFloat / 5) * 100)) : 0;
                        @endphp
                        <div class="as-score-item" title="{{ $item['label'] }}: {{ $totalRespondents ? $item['val'] . __(' out of 5.0') : __('No data yet') }}">
                            <div class="as-score-meta">
                                <span class="as-score-title">
                                    <i class="ph ph-{{ $item['icon'] }}" aria-hidden="true"></i>
                                    {{ $item['label'] }}
                                </span>
                                <span class="as-score-val">
                                    {{ $totalRespondents ? $item['val'] : '—' }}
                                    <em>/ 5.0</em>
                                </span>
                            </div>
                            <div class="as-track" role="progressbar" aria-valuenow="{{ $scoreFloat }}" aria-valuemin="1" aria-valuemax="5">
                                <span class="as-fill" style="width: {{ $pct }}%;"></span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- Overall Rating Distribution (1–5 Stars) --}}
            <section class="as-panel" aria-labelledby="distribution-heading">
                <div class="as-panel-heading">
                    <div>
                        <h2 id="distribution-heading">
                            {{ __('Overall CSAT Distribution') }}
                        </h2>
                        <p>{{ __('How members rated platform experience on Q1') }}</p>
                    </div>
                </div>

                <div class="as-dist-content">
                    <div class="as-dist-rows">
                        @for($score = 5; $score >= 1; $score--)
                            @php
                                $cnt = $distributions[$score] ?? 0;
                                $distPct = $totalRespondents > 0 ? round(($cnt / $totalRespondents) * 100) : 0;
                            @endphp
                            <div class="as-dist-row" title="{{ __(':value1 respondent(s) rated :value2 out of 5 (:value3%)', ['value1' => ($cnt), 'value2' => ($score), 'value3' => ($distPct)]) }}">
                                <span class="as-dist-stars">
                                    {{ $score }} <i class="ph-fill ph-star" aria-hidden="true"></i>
                                </span>
                                <div class="as-dist-track" role="progressbar" aria-valuenow="{{ $distPct }}" aria-valuemin="0" aria-valuemax="100">
                                    <span class="as-dist-fill" style="width: {{ $distPct }}%;"></span>
                                </div>
                                <span class="as-dist-count">{{ $cnt }} <small class="text-xs text-gray-400">({{ $distPct }}%)</small></span>
                            </div>
                        @endfor
                    </div>

                    {{-- Positive Sentiment Summary --}}
                    <div class="as-sentiment-card">
                        <div class="as-sentiment-icon">
                            <i class="ph-fill ph-heart" aria-hidden="true"></i>
                        </div>
                        <div class="as-sentiment-copy">
                            <strong>{{ __(':value1% Positive Sentiment', ['value1' => ($positiveRate)]) }}</strong>
                            <span>{{ __(':value1 of :value2 respondents gave 4 or 5 stars on overall experience.', ['value1' => (number_format($positiveCount)), 'value2' => (number_format($totalRespondents))]) }}</span>
                        </div>
                    </div>
                </div>
            </section>

        </div>

        {{-- ==========================================================================
             MEMBER RESPONSES DIRECTORY & FEEDBACK TABLE
             ========================================================================== --}}
        <section class="as-panel" aria-labelledby="responses-table-heading">
            <div class="as-panel-heading">
                <div>
                    <h2 id="responses-table-heading">
                        {{ __('Member Survey Responses') }} <span>{{ number_format($responses->total()) }}</span>
                    </h2>
                    <p>{{ __('Open any response to view the full 12-question scorecard and written comments') }}</p>
                </div>
            </div>

            <div class="as-table-scroll" tabindex="0" aria-label="{{ __('Member Survey Responses Table') }}">
                <table class="as-table">
                    <colgroup>
                        <col style="width: 25%;">
                        <col style="width: 14%;">
                        <col style="width: 33%;">
                        <col style="width: 15%;">
                        <col style="width: 13%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th scope="col">{{ __('Member') }}</th>
                            <th scope="col">{{ __('Overall Score') }}</th>
                            <th scope="col">{{ __('Written Feedback') }}</th>
                            <th scope="col">{{ __('Submitted') }}</th>
                            <th scope="col">{{ __('Scorecard') }}</th>
                        </tr>
                    </thead>
                    @forelse($responses as $response)
                        <tbody x-data="{ openScorecard: false }" wire:key="survey-res-{{ $response->id }}" class="as-response-group">
                            <tr class="as-row-main" :class="{ 'is-expanded': openScorecard }">
                                {{-- Member Info --}}
                                <td>
                                    <div class="as-member-cell">
                                        <img src="{{ $response->user?->avatar_url ?? \App\Support\AvatarCatalog::url('amber') }}"
                                             alt=""
                                             class="as-avatar"
                                             loading="lazy">
                                        <div>
                                            <div class="as-member-name">{{ $response->user?->name ?? 'Deleted member' }}</div>
                                            <div class="as-member-email">{{ $response->user?->email ?? 'No email on record' }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Overall Score Badge --}}
                                <td>
                                    <span class="as-rating-badge">
                                        <i class="ph-fill ph-star" aria-hidden="true"></i>
                                        {{ number_format($response->q1_overall, 1) }}
                                        <small>/ 5.0</small>
                                    </span>
                                </td>

                                {{-- Written Feedback --}}
                                <td>
                                    @if($response->feedback)
                                        <div class="as-feedback-quote" title="{{ $response->feedback }}">
                                            “{{ $response->feedback }}”
                                        </div>
                                    @else
                                        <span class="as-feedback-empty">{{ __('No written feedback provided') }}</span>
                                    @endif
                                </td>

                                {{-- Submitted Date --}}
                                <td>
                                    <time datetime="{{ $response->created_at->toIso8601String() }}">
                                        <strong>{{ $response->created_at->locale(app()->getLocale())->translatedFormat('d M Y') }}</strong>
                                        <div class="text-xs text-gray-500 dark:text-slate-400">{{ __(':value1 WIB', ['value1' => ($response->created_at->format('H:i'))]) }}</div>
                                    </time>
                                </td>

                                {{-- Action / Toggle Scorecard --}}
                                <td>
                                    <button type="button"
                                            class="as-btn as-btn--quiet as-btn--sm"
                                            @click="openScorecard = !openScorecard">
                                        <span x-text="openScorecard ? @js(__('Close')) : @js(__('View all 12'))"></span>
                                        <i class="ph ph-caret-down transition-transform duration-200" :class="{ 'rotate-180': openScorecard }" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </tr>

                            {{-- Expandable Scorecard Row across full 5 columns --}}
                            <tr x-show="openScorecard" x-cloak class="as-scorecard-row">
                                <td colspan="5" class="as-scorecard-cell">
                                    <div class="as-scorecard-box">
                                        <div class="as-scorecard-heading">
                                            <div class="as-scorecard-heading-left">
                                                <h4>{{ __('12-Question Breakdown') }}</h4>
                                                <span class="as-scorecard-member">· {{ $response->user?->name ?? 'Member' }}</span>
                                            </div>
                                            <div class="as-scorecard-heading-right">
                                                <span class="as-scorecard-time">{{ __(':value1 WIB', ['value1' => ($response->created_at->locale(app()->getLocale())->translatedFormat('d M Y, H:i'))]) }}</span>
                                                <span class="as-rating-badge">
                                                    <i class="ph-fill ph-star" aria-hidden="true"></i>
                                                    {{ number_format($response->q1_overall, 1) }}
                                                    <small>/ 5.0</small>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="as-scorecard-grid">
                                            <div class="as-scorecard-item"><span>{{ __('1. Overall:') }}</span> <strong>{{ $response->q1_overall ?? '—' }} / 5</strong></div>
                                            <div class="as-scorecard-item"><span>{{ __('2. Navigation:') }}</span> <strong>{{ $response->q2_navigation ?? '—' }} / 5</strong></div>
                                            <div class="as-scorecard-item"><span>{{ __('3. Speed:') }}</span> <strong>{{ $response->q3_speed ?? '—' }} / 5</strong></div>
                                            <div class="as-scorecard-item"><span>{{ __('4. CV Builder:') }}</span> <strong>{{ $response->q4_cv_builder ?? '—' }} / 5</strong></div>
                                            <div class="as-scorecard-item"><span>{{ __('5. AI Analyzer:') }}</span> <strong>{{ $response->q5_ai_analyzer ?? '—' }} / 5</strong></div>
                                            <div class="as-scorecard-item"><span>{{ __('6. Tracker:') }}</span> <strong>{{ $response->q6_job_tracker ?? '—' }} / 5</strong></div>
                                            <div class="as-scorecard-item"><span>{{ __('7. Cover Letter:') }}</span> <strong>{{ $response->q7_cover_letter ?? '—' }} / 5</strong></div>
                                            <div class="as-scorecard-item"><span>{{ __('8. Interviews:') }}</span> <strong>{{ $response->q8_interviews ?? '—' }} / 5</strong></div>
                                            <div class="as-scorecard-item"><span>{{ __('9. Premium Value:') }}</span> <strong>{{ $response->q9_premium ?? '—' }} / 5</strong></div>
                                            <div class="as-scorecard-item"><span>{{ __('10. Likelihood to recommend:') }}</span> <strong>{{ $response->q10_recommend ?? '—' }} / 5</strong></div>
                                            <div class="as-scorecard-item"><span>{{ __('11. Visual Design:') }}</span> <strong>{{ $response->q11_design ?? '—' }} / 5</strong></div>
                                            <div class="as-scorecard-item"><span>{{ __('12. CV Templates:') }}</span> <strong>{{ $response->q12_cv_templates ?? '—' }} / 5</strong></div>
                                        </div>

                                        @if($response->feedback)
                                            <div class="as-scorecard-text">
                                                <strong>{{ __('Written Feedback:') }}</strong>
                                                <p>“{{ $response->feedback }}”</p>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    @empty
                        <tbody>
                            <tr>
                                <td colspan="5">
                                    <div class="as-empty">
                                        <div class="as-empty-icon">
                                            <i class="ph ph-clipboard-text" aria-hidden="true"></i>
                                        </div>
                                        <h3>{{ __('No survey responses yet') }}</h3>
                                        <p>{{ __('When members complete their satisfaction survey, their scorecards and feedback will appear here in real time.') }}</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    @endforelse
                </table>
            </div>

            {{-- Panel Footer --}}
            <div class="as-panel-footer">
                <span>{{ __('Showing :value1 to :value2 of :value3 responses', ['value1' => ($responses->firstItem() ?? 0), 'value2' => ($responses->lastItem() ?? 0), 'value3' => (number_format($responses->total()))]) }}</span>
                <div>
                    {{ $responses->links() }}
                </div>
            </div>
        </section>

    </div>
</x-admin-layout>
