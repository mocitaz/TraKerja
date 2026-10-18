<div class="admin-insights-workspace" x-data="{ section: 'overview' }">
    {{-- Header with Summary Periods Segmented Control --}}
    <section class="admin-welcome">
        <div>
            <h1>{{ __('Analytics') }}</h1>
            <p>{{ __('Deep-dive into platform performance, growth momentum, and user engagements.') }}</p>
        </div>

        <nav class="summary-periods" aria-label="{{ __('Reporting window') }}">
            <button type="button"
                    wire:click="setPeriod('all')"
                    class="{{ $periodFilter === 'all' ? 'is-active' : '' }}">
                {{ __('All time') }}
            </button>
            <button type="button"
                    wire:click="setPeriod('7')"
                    class="{{ $periodFilter === '7' ? 'is-active' : '' }}">
                {{ __('7 days') }}
            </button>
            <button type="button"
                    wire:click="setPeriod('30')"
                    class="{{ $periodFilter === '30' ? 'is-active' : '' }}">
                {{ __('30 days') }}
            </button>
            <button type="button"
                    wire:click="setPeriod('90')"
                    class="{{ $periodFilter === '90' ? 'is-active' : '' }}">
                {{ __('90 days') }}
            </button>
        </nav>
    </section>

    {{-- Executive Platform Overview Strip (matches /summary design system) --}}
    <section class="summary-overview" aria-label="{{ __('Executive platform totals') }}">
        <div class="summary-stat summary-stat--members">
            <span>{{ __('New members') }}</span>
            <strong>{{ number_format($stats['totalUsers']) }}</strong>
            <p>{{ $periodFilter === 'all' ? __('All time registrations') : __('Joined in last ').$stats['periodDays'].__(' days') }}</p>
        </div>

        <div class="summary-stat summary-stat--applications">
            <span>{{ __('Applications tracked') }}</span>
            <strong>{{ number_format($stats['totalJobApplications']) }}</strong>
            <p>{{ $periodFilter === 'all' ? __('All tracker records') : __('Recorded in last ').$stats['periodDays'].__(' days') }}</p>
        </div>

        <div class="summary-stat summary-stat--revenue">
            <span>{{ __('Platform revenue') }}</span>
            <strong>{{ __('Rp :value1', ['value1' => (number_format($stats['totalRevenue'], 0, ',', '.'))]) }}</strong>
            <p>{{ __(':value1 successful transactions', ['value1' => (number_format($stats['totalPayments']))]) }}</p>
        </div>

        <div class="summary-stat summary-stat--exports">
            <span>{{ __('Exports & documents') }}</span>
            <strong>{{ number_format($stats['totalExports']) }}</strong>
            <p>{{ __(':value1 members with active data', ['value1' => (number_format($stats['activeUsers']))]) }}</p>
        </div>

        <footer class="summary-stats-footer">
            <p>
                <i class="ph ph-clock-countdown"></i> {{ __('Cached up to 10 min for instant query speed · Timestamps in WIB (Asia/Jakarta)') }}
            </p>
            <div class="summary-footer-meta" wire:loading wire:target="periodFilter, setPeriod">
                <span class="summary-updating-pill">
                    <i class="ph ph-spinner ph-spin"></i> {{ __('Updating metrics…') }}
                </span>
            </div>
        </footer>
    </section>

    {{-- Platform Highlights (matches Campaign summary orange card) --}}
    <section class="summary-insights">
        <div class="summary-insights-header">
            <div class="summary-insights-title">
                <h2>{{ __('Platform Highlights') }}</h2>
            </div>
        </div>
        <div class="summary-insights-grid">
            <article>
                <span>{{ __('Registration Velocity') }}</span>
                <strong>{{ number_format($stats['newUsersWeek']) }} <small>{{ __('new this week') }}</small></strong>
                <p>{{ __(':value1 signed up today · Monthly run-rate: :value2', ['value1' => (number_format($stats['newUsersToday'])), 'value2' => (number_format($stats['newUsersMonth']))]) }}</p>
            </article>
            <article>
                <span>{{ __('Membership Entitlement') }}</span>
                <strong>{{ number_format($stats['premiumUsers']) }} <small>{{ __('Premium (:value1%)', ['value1' => ($stats['totalUsers'] > 0 ? round($stats['premiumUsers'] / max(1, $stats['totalUsers']) * 100, 1) : 0)]) }}</small></strong>
                <p>{{ __(':value1 on standard free workspace accounts.', ['value1' => (number_format($stats['freeUsers']))]) }}</p>
            </article>
            <article>
                <span>{{ __('Weekly Goals Achievement') }}</span>
                <strong>{{ $stats['goalsAchievementRate'] }}% <small>{{ __('Success rate') }}</small></strong>
                <p>{{ __(':value1 of :value2 career targets achieved by members.', ['value1' => (number_format($stats['achievedGoals'])), 'value2' => (number_format($stats['totalGoals']))]) }}</p>
            </article>
        </div>
    </section>

    {{-- Navigation Tabs (matches .summary-analysis-tabs) --}}
    <div class="summary-analysis-tabs" role="tablist" aria-label="{{ __('Analytics sections') }}">
        <button type="button"
                id="insights-tab-overview"
                role="tab"
                :aria-selected="(section === 'overview').toString()"
                :class="{ 'is-active': section === 'overview' }"
                aria-controls="insights-overview"
                @click="section = 'overview'">
            <i class="ph-bold ph-chart-line-up"></i>
            <span>{{ __('Overview') }}</span>
        </button>
        <button type="button"
                id="insights-tab-usage"
                role="tab"
                :aria-selected="(section === 'usage').toString()"
                :class="{ 'is-active': section === 'usage' }"
                aria-controls="insights-usage"
                @click="section = 'usage'">
            <i class="ph-bold ph-cpu"></i>
            <span>{{ __('Feature Usage') }}</span>
        </button>
        <button type="button"
                id="insights-tab-community"
                role="tab"
                :aria-selected="(section === 'community').toString()"
                :class="{ 'is-active': section === 'community' }"
                aria-controls="insights-community"
                @click="section = 'community'">
            <i class="ph-bold ph-users-three"></i>
            <span>{{ __('Community & Retention') }}</span>
        </button>
    </div>

    {{-- TAB 1: OVERVIEW --}}
    <div id="insights-overview"
         role="tabpanel"
         aria-labelledby="insights-tab-overview"
         x-show="section === 'overview'"
         x-transition.opacity.duration.150ms
         class="ai-section">

        <div class="ai-grid ai-grid--wide">
            {{-- Member Growth --}}
            <section class="ai-panel">
                <header class="ai-panel-heading">
                    <div>
                        <h2>{{ __('Member Growth') }}</h2>
                        <p>{{ __('Total registered members · :value1', ['value1' => ($periodFilter === 'all' ? 'last 30 days' : 'last '.$periodFilter.' days')]) }}</p>
                    </div>
                    <span class="ai-count">{{ __('Cumulative') }}</span>
                </header>
                <div class="ai-panel-body">
                    <x-admin.trend :labels="$userGrowth['labels']" :values="$userGrowth['total']" name="members" color="#8e4476" />
                </div>
            </section>

            {{-- Current Membership Donut --}}
            <section class="ai-panel ai-panel--glass">
                <header class="ai-panel-heading">
                    <div>
                        <h2>{{ __('Current Membership') }}</h2>
                        <p>{{ __('Active entitlement status across all accounts') }}</p>
                    </div>
                    <span class="ai-count">{{ __('Entitlement split') }}</span>
                </header>
                <div class="ai-panel-body">
                    <x-admin.donut :labels="$premiumVsFree['labels']" :values="$premiumVsFree['data']" name="members" />
                </div>
            </section>
        </div>

        <div class="ai-grid">
            {{-- Applications Recorded --}}
            <section class="ai-panel">
                <header class="ai-panel-heading">
                    <div>
                        <h2>{{ __('Applications Recorded') }}</h2>
                        <p>{{ __('By record creation date · :value1', ['value1' => ($periodFilter === 'all' ? 'last 30 days' : 'last '.$periodFilter.' days')]) }}</p>
                    </div>
                    <span class="ai-count">{{ __('Daily velocity') }}</span>
                </header>
                <div class="ai-panel-body">
                    <x-admin.trend :labels="$jobApplicationsOverTime['labels']" :values="$jobApplicationsOverTime['data']" name="applications" color="#34749b" />
                </div>
            </section>

            {{-- New Registrations --}}
            <section class="ai-panel">
                <header class="ai-panel-heading">
                    <div>
                        <h2>{{ __('New Registrations') }}</h2>
                        <p>{{ __('Daily sign-ups · :value1', ['value1' => ($periodFilter === 'all' ? 'last 30 days' : 'last '.$periodFilter.' days')]) }}</p>
                    </div>
                    <span class="ai-count">{{ __('Daily signups') }}</span>
                </header>
                <div class="ai-panel-body">
                    <x-admin.trend :labels="$userRegistrationByDay['labels']" :values="$userRegistrationByDay['data']" name="registrations" color="#7751a0" />
                </div>
            </section>
        </div>

        <div class="ai-grid">
            {{-- Application Signals --}}
            <section class="ai-panel">
                <header class="ai-panel-heading">
                    <div>
                        <h2>{{ __('Application Signals') }}</h2>
                        <p>{{ __('Recorded stages and outcomes across candidate pipelines') }}</p>
                    </div>
                    <span class="ai-count">{{ __('Stage distribution') }}</span>
                </header>
                <div class="ai-panel-body">
                    <x-admin.bars :labels="['All applications','Interview or assessment','Accepted','Rejected','Other current statuses']"
                                  :values="$jobApplicationsByStatus['data']"
                                  name="applications"
                                  color="#7751a0" />
                </div>
            </section>

            {{-- Where People Apply --}}
            <section class="ai-panel">
                <header class="ai-panel-heading">
                    <div>
                        <h2>{{ __('Where People Apply') }}</h2>
                        <p>{{ __('Top ten recorded recruitment platforms & portals') }}</p>
                    </div>
                    <span class="ai-count">{{ __('Top platforms') }}</span>
                </header>
                <div class="ai-panel-body">
                    <x-admin.bars :labels="$jobApplicationsByPlatform['labels']"
                                  :values="$jobApplicationsByPlatform['data']"
                                  name="applications"
                                  color="#34749b" />
                </div>
            </section>
        </div>

        <div class="ai-grid">
            {{-- Target Companies --}}
            <section class="ai-panel">
                <header class="ai-panel-heading">
                    <div>
                        <h2>{{ __('Target Companies') }}</h2>
                        <p>{{ __('Most recorded employers in member trackers') }}</p>
                    </div>
                    <span class="ai-count">{{ __('Top employers') }}</span>
                </header>
                <div class="ai-panel-body">
                    <x-admin.bars :labels="array_column($topCompanies, 'company_name')"
                                  :values="array_column($topCompanies, 'count')"
                                  name="applications"
                                  color="#8e4476" />
                </div>
            </section>

            {{-- Target Roles --}}
            <section class="ai-panel">
                <header class="ai-panel-heading">
                    <div>
                        <h2>{{ __('Target Roles') }}</h2>
                        <p>{{ __('Most recorded job titles across member trackers') }}</p>
                    </div>
                    <span class="ai-count">{{ __('Top job roles') }}</span>
                </header>
                <div class="ai-panel-body">
                    <x-admin.bars :labels="array_column($topPositions, 'position')"
                                  :values="array_column($topPositions, 'count')"
                                  name="applications"
                                  color="#ad573f" />
                </div>
            </section>
        </div>
    </div>

    {{-- TAB 2: FEATURE USAGE --}}
    <div id="insights-usage"
         role="tabpanel"
         aria-labelledby="insights-tab-usage"
         x-show="section === 'usage'"
         x-cloak
         x-transition.opacity.duration.150ms
         class="ai-section">

        {{-- AI Workspace Feature Telemetry Strip (Lavender/Purple Theme matching Continue your CV #e5e0f5) --}}
        <section class="ai-panel ai-panel--purple">
            <header class="ai-panel-heading">
                <div>
                    <h2>{{ __('AI Intelligence & Generation Telemetry') }}</h2>
                    <p>{{ __('Recorded AI requests in the reporting window, regardless of completion status.') }}</p>
                </div>
                <span class="ai-count">{{ __('Intelligence suite') }}</span>
            </header>
            <div class="ai-usage-metrics">
                <div class="ai-usage-card">
                    <span>{{ __('CV Analyses') }}</span>
                    <strong>{{ number_format($aiUsageStats['ai_analyzer']) }}</strong>
                </div>
                <div class="ai-usage-card">
                    <span>{{ __('Photo Requests') }}</span>
                    <strong>{{ number_format($aiUsageStats['ai_photos']) }}</strong>
                </div>
                <div class="ai-usage-card">
                    <span>{{ __('Cover Letters') }}</span>
                    <strong>{{ number_format($coverLetterStats['total']) }}</strong>
                </div>
                <div class="ai-usage-card">
                    <span>{{ __('CV Generations (Month)') }}</span>
                    <strong>{{ number_format($aiUsageStats['ai_cv_gen']) }}</strong>
                </div>
            </div>
        </section>

        <div class="ai-grid">
            {{-- CV Analysis Activity --}}
            <section class="ai-panel">
                <header class="ai-panel-heading">
                    <div>
                        <h2>{{ __('CV Analysis Activity') }}</h2>
                        <p>{{ __('Resume analyzer audits · Last 7 days') }}</p>
                    </div>
                    <span class="ai-count">{{ __('Resume audits') }}</span>
                </header>
                <div class="ai-panel-body">
                    <x-admin.trend :labels="$aiUsageStats['trend_labels']"
                                  :values="$aiUsageStats['trend']"
                                  name="analyses"
                                  color="#8e4476" />
                </div>
            </section>

            {{-- Cover Letter Activity --}}
            <section class="ai-panel">
                <header class="ai-panel-heading">
                    <div>
                        <h2>{{ __('Cover Letter Activity') }}</h2>
                        <p>{{ __('Last 14 days · :value1 unique authors (:value2% member adoption)', ['value1' => (number_format($coverLetterStats['unique_users'])), 'value2' => ($coverLetterStats['adoption_rate'])]) }}</p>
                    </div>
                    <span class="ai-count">{{ __('Letter generation') }}</span>
                </header>
                <div class="ai-panel-body">
                    <x-admin.trend :labels="$coverLetterStats['trend_labels']"
                                  :values="$coverLetterStats['trend']"
                                  name="letters"
                                  color="#7751a0" />
                </div>
            </section>
        </div>

        <div class="ai-grid">
            {{-- Saved CV Templates --}}
            <section class="ai-panel">
                <header class="ai-panel-heading">
                    <div>
                        <h2>{{ __('Saved CV Templates') }}</h2>
                        <p>{{ __(':value1 saved records · reporting window', ['value1' => (number_format($cvTemplatePopularity['total']))]) }}</p>
                    </div>
                    <span class="ai-count">{{ __('Design adoption') }}</span>
                </header>
                <div class="ai-panel-body">
                    <x-admin.bars :labels="array_column($cvTemplatePopularity['templates'], 'template_key')"
                                  :values="array_column($cvTemplatePopularity['templates'], 'usage_count')"
                                  name="saved templates"
                                  color="#34749b" />
                </div>
            </section>

            {{-- Weekly Goal Outcomes --}}
            <section class="ai-panel">
                <header class="ai-panel-heading">
                    <div>
                        <h2>{{ __('Weekly Goal Outcomes') }}</h2>
                        <p>{{ __('All saved goals tracked across user workspaces · all time') }}</p>
                    </div>
                    <span class="ai-count">{{ __('Milestones') }}</span>
                </header>
                <div class="ai-panel-body">
                    <x-admin.donut :labels="$goalsAchievement['labels']"
                                   :values="$goalsAchievement['data']"
                                   name="goals" />
                </div>
            </section>
        </div>

        {{-- When Activity Happens --}}
        <section class="ai-panel">
            <header class="ai-panel-heading">
                <div>
                    <h2>{{ __('When Activity Happens') }}</h2>
                    <p>{{ __('Logged platform events by stored timestamp hour (WIB) · reporting window') }}</p>
                </div>
                <span class="ai-count">{{ $peakActivityHours['peak_count'] ? __('Peak: ').$peakActivityHours['peak_hour'] : __('No activity logged') }}</span>
            </header>
            <div class="ai-panel-body">
                <x-admin.trend :labels="$peakActivityHours['labels']"
                              :values="$peakActivityHours['data']"
                              name="events"
                              unit="hours"
                              color="#ad573f" />
            </div>
        </section>
    </div>

    {{-- TAB 3: COMMUNITY & RETENTION --}}
    <div id="insights-community"
         role="tabpanel"
         aria-labelledby="insights-tab-community"
         x-show="section === 'community'"
         x-cloak
         x-transition.opacity.duration.150ms
         class="ai-section">

        <div class="ai-grid">
            {{-- Email Verification --}}
            <section class="ai-panel">
                <header class="ai-panel-heading">
                    <div>
                        <h2>{{ __('Email Verification') }}</h2>
                        <p>{{ __('All registered members · current verification state') }}</p>
                    </div>
                    <span class="ai-count">{{ __('Account validity') }}</span>
                </header>
                <div class="ai-panel-body">
                    <x-admin.donut :labels="$verifiedVsUnverified['labels']"
                                   :values="$verifiedVsUnverified['data']"
                                   name="members" />
                </div>
            </section>

            {{-- Support Requests --}}
            <section class="ai-panel">
                <header class="ai-panel-heading">
                    <div>
                        <h2>{{ __('Support Requests') }}</h2>
                        <p>{{ __('Reporting window · :value1 total tickets', ['value1' => (number_format($supportTicketStats['total']))]) }}</p>
                    </div>
                    <span class="ai-count">{{ __('Helpdesk queue') }}</span>
                </header>
                <div class="ai-panel-body">
                    <x-admin.bars :labels="['Pending','Replied','Completed']"
                                  :values="[$supportTicketStats['pending'], $supportTicketStats['replied'], $supportTicketStats['resolved']]"
                                  name="tickets"
                                  color="#7751a0" />
                    <p class="ai-footnote">
                        <i class="ph ph-info"></i> {{ __(':value1 hours average to first recorded reply. :value2 members registered in this window have populated profile experience, education, or skills.', ['value1' => ($supportTicketStats['avg_resolution_hrs']), 'value2' => (number_format($stats['activeUsers']))]) }}
                    </p>
                </div>
            </section>
        </div>

        {{-- Return Visits by Signup Month (Retention Cohort Table styled like /tracker) --}}
        <section class="ai-panel">
            <header class="ai-panel-heading">
                <div>
                    <h2>{{ __('Return Visits by Signup Month') }}</h2>
                    <p>{{ __('Members with a logged event in the following calendar month · last 6 signup cohorts') }}</p>
                </div>
                <span class="ai-count">{{ __('Cohort retention') }}</span>
            </header>
            <div class="au-table-container">
                <table class="au-tracker-table">
                    <thead>
                        <tr>
                            <th>{{ __('Signup Month') }}</th>
                            <th class="text-right">{{ __('Cohort Size') }}</th>
                            <th class="text-right">{{ __('Returned M+1') }}</th>
                            <th>{{ __('Next-Month Retention') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($retentionCohort as $cohort)
                            @php($mature = $cohort['mature'])
                            <tr>
                                <td class="font-semibold text-[#35264f] dark:text-[#f0f4f9]">
                                    <div class="flex items-center gap-2">
                                        <i class="ph-bold ph-calendar text-[#7751a0] dark:text-[#c084fc]"></i>
                                        <span>{{ $cohort['month'] }}</span>
                                    </div>
                                </td>
                                <td class="text-right font-medium">
                                    {{ number_format($cohort['total']) }}
                                </td>
                                <td class="text-right font-medium">
                                    {{ number_format($cohort['retained']) }}
                                </td>
                                <td>
                                    @if($mature && $cohort['total'])
                                        <div class="ai-cohort-rate">
                                            <div class="ai-cohort-track">
                                                <span style="width: {{ $cohort['rate'] }}%"></span>
                                            </div>
                                            <span class="ai-cohort-pill">
                                                {{ $cohort['rate'] }}%
                                            </span>
                                        </div>
                                    @else
                                        <span class="ai-cohort-pending">
                                            {{ !$mature ? __('Window in progress') : __('No sign-ups') }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-6 text-zinc-500">
                                    {{ __('No cohort records available yet.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Community Progress / Gamification Leaderboard --}}
        <section class="ai-panel">
            <header class="ai-panel-heading">
                <div>
                    <h2>{{ __('Community Progress') }}</h2>
                    <p>{{ __('Top members by earned experience points (XP) · all time') }}</p>
                </div>
                <span class="ai-count">{{ __('Top achievers') }}</span>
            </header>
            <div class="ai-leaderboard-list">
                @forelse($gamificationLeaderboard as $member)
                    <div class="ai-leaderboard-item">
                        <div class="ai-rank-badge ai-rank-badge--{{ $member['rank'] <= 3 ? $member['rank'] : 'default' }}">
                            @if($member['rank'] === 1)
                                <i class="ph-fill ph-crown"></i>
                            @else
                                {{ $member['rank'] }}
                            @endif
                        </div>

                        <div class="ai-member-avatar">
                            @if(!empty($member['avatar_url']))
                                <img src="{{ $member['avatar_url'] }}" alt="{{ $member['name'] }}" loading="lazy">
                            @else
                                <span>{{ strtoupper(substr($member['name'], 0, 1)) }}</span>
                            @endif
                        </div>

                        <div class="ai-member-info">
                            <strong>{{ $member['name'] }}</strong>
                            <div class="ai-member-sub">
                                <span class="ai-level-badge">{{ __('Level :value1', ['value1' => ($member['level'])]) }}</span>
                                <span class="ai-member-title">{{ $member['title'] }}</span>
                            </div>
                        </div>

                        <div class="ai-member-xp">
                            <strong>{{ number_format($member['xp']) }}</strong>
                            <small>{{ __('XP') }}</small>
                        </div>
                    </div>
                @empty
                    <div class="ai-empty">
                        <i class="ph-bold ph-trophy text-3xl text-zinc-400 mb-2"></i>
                        <h3>{{ __('No members ranked yet') }}</h3>
                        <p>{{ __('Community milestones will appear as members earn XP in their workspaces.') }}</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</div>
