<div class="admin-insights-workspace" x-data="{ section: 'overview' }">
    {{-- Header with Summary Periods Segmented Control --}}
    <section class="admin-welcome">
        <div>
            <span class="admin-eyebrow">
                <i class="ph-bold ph-chart-polar"></i> Platform Intelligence
            </span>
            <h1>Analytics</h1>
            <p>Deep-dive into platform performance, growth momentum, and user engagements.</p>
        </div>
        
        <nav class="summary-periods" aria-label="Reporting window">
            <button type="button" 
                    wire:click="setPeriod('all')" 
                    class="{{ $periodFilter === 'all' ? 'is-active' : '' }}">
                All time
            </button>
            <button type="button" 
                    wire:click="setPeriod('7')" 
                    class="{{ $periodFilter === '7' ? 'is-active' : '' }}">
                7 days
            </button>
            <button type="button" 
                    wire:click="setPeriod('30')" 
                    class="{{ $periodFilter === '30' ? 'is-active' : '' }}">
                30 days
            </button>
            <button type="button" 
                    wire:click="setPeriod('90')" 
                    class="{{ $periodFilter === '90' ? 'is-active' : '' }}">
                90 days
            </button>
        </nav>
    </section>

    {{-- Executive Platform Overview Strip (matches /summary design system) --}}
    <section class="summary-overview" aria-label="Executive platform totals">
        <div class="summary-stat summary-stat--members">
            <span>New members</span>
            <strong>{{ number_format($stats['totalUsers']) }}</strong>
            <p>{{ $periodFilter === 'all' ? 'All time registrations' : 'Joined in last '.$stats['periodDays'].' days' }}</p>
        </div>
        
        <div class="summary-stat summary-stat--applications">
            <span>Applications tracked</span>
            <strong>{{ number_format($stats['totalJobApplications']) }}</strong>
            <p>{{ $periodFilter === 'all' ? 'All tracker records' : 'Recorded in last '.$stats['periodDays'].' days' }}</p>
        </div>
        
        <div class="summary-stat summary-stat--revenue">
            <span>Platform revenue</span>
            <strong>Rp {{ number_format($stats['totalRevenue'], 0, ',', '.') }}</strong>
            <p>{{ number_format($stats['totalPayments']) }} successful transactions</p>
        </div>
        
        <div class="summary-stat summary-stat--exports">
            <span>Exports & documents</span>
            <strong>{{ number_format($stats['totalExports']) }}</strong>
            <p>{{ number_format($stats['activeUsers']) }} members with active data</p>
        </div>

        <footer class="summary-stats-footer">
            <p>
                <i class="ph ph-clock-countdown"></i> Cached up to 10 min for instant query speed · Timestamps in WIB (Asia/Jakarta)
            </p>
            <div class="summary-footer-meta">
                <span wire:loading.remove wire:target="periodFilter, setPeriod" class="summary-status-pill">
                    <span class="summary-status-dot"></span> Telemetry live
                </span>
                <span wire:loading wire:target="periodFilter, setPeriod" class="summary-updating-pill">
                    <i class="ph ph-spinner ph-spin"></i> Updating metrics…
                </span>
            </div>
        </footer>
    </section>

    {{-- Platform Highlights Glass Box (matches /summary .summary-insights) --}}
    <section class="summary-insights workspace-card--glass">
        <div class="summary-insights-header">
            <div class="summary-insights-title">
                <i class="ph-fill ph-sparkle text-[#8e4476]"></i>
                <h2>Platform Highlights</h2>
            </div>
            <span class="summary-insights-badge">Live signals</span>
        </div>
        <div class="summary-insights-grid">
            <article>
                <span>Registration Velocity</span>
                <strong>{{ number_format($stats['newUsersWeek']) }} <small>new this week</small></strong>
                <p>{{ number_format($stats['newUsersToday']) }} signed up today · Monthly run-rate: {{ number_format($stats['newUsersMonth']) }}</p>
            </article>
            <article>
                <span>Membership Entitlement</span>
                <strong>{{ number_format($stats['premiumUsers']) }} <small>Premium ({{ $stats['totalUsers'] > 0 ? round($stats['premiumUsers'] / max(1, $stats['totalUsers']) * 100, 1) : 0 }}%)</small></strong>
                <p>{{ number_format($stats['freeUsers']) }} on standard free workspace accounts.</p>
            </article>
            <article>
                <span>Weekly Goals Achievement</span>
                <strong>{{ $stats['goalsAchievementRate'] }}% <small>Success rate</small></strong>
                <p>{{ number_format($stats['achievedGoals']) }} of {{ number_format($stats['totalGoals']) }} career targets achieved by members.</p>
            </article>
        </div>
    </section>

    {{-- Navigation Tabs (matches .summary-analysis-tabs) --}}
    <div class="summary-analysis-tabs" role="tablist" aria-label="Analytics sections">
        <button type="button" 
                id="insights-tab-overview" 
                role="tab" 
                :aria-selected="(section === 'overview').toString()" 
                :class="{ 'is-active': section === 'overview' }" 
                aria-controls="insights-overview" 
                @click="section = 'overview'">
            <i class="ph-bold ph-chart-line-up"></i>
            <span>Overview</span>
        </button>
        <button type="button" 
                id="insights-tab-usage" 
                role="tab" 
                :aria-selected="(section === 'usage').toString()" 
                :class="{ 'is-active': section === 'usage' }" 
                aria-controls="insights-usage" 
                @click="section = 'usage'">
            <i class="ph-bold ph-cpu"></i>
            <span>Feature Usage</span>
        </button>
        <button type="button" 
                id="insights-tab-community" 
                role="tab" 
                :aria-selected="(section === 'community').toString()" 
                :class="{ 'is-active': section === 'community' }" 
                aria-controls="insights-community" 
                @click="section = 'community'">
            <i class="ph-bold ph-users-three"></i>
            <span>Community & Retention</span>
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
                        <h2>Member Growth</h2>
                        <p>Total registered members · {{ $periodFilter === 'all' ? 'last 30 days' : 'last '.$periodFilter.' days' }}</p>
                    </div>
                    <span class="ai-count">Cumulative</span>
                </header>
                <div class="ai-panel-body">
                    <x-admin.trend :labels="$userGrowth['labels']" :values="$userGrowth['total']" name="members" color="#8e4476" />
                </div>
            </section>

            {{-- Current Membership Donut --}}
            <section class="ai-panel ai-panel--glass">
                <header class="ai-panel-heading">
                    <div>
                        <h2>Current Membership</h2>
                        <p>Active entitlement status across all accounts</p>
                    </div>
                    <span class="ai-count">Entitlement split</span>
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
                        <h2>Applications Recorded</h2>
                        <p>By record creation date · {{ $periodFilter === 'all' ? 'last 30 days' : 'last '.$periodFilter.' days' }}</p>
                    </div>
                    <span class="ai-count">Daily velocity</span>
                </header>
                <div class="ai-panel-body">
                    <x-admin.trend :labels="$jobApplicationsOverTime['labels']" :values="$jobApplicationsOverTime['data']" name="applications" color="#34749b" />
                </div>
            </section>

            {{-- New Registrations --}}
            <section class="ai-panel">
                <header class="ai-panel-heading">
                    <div>
                        <h2>New Registrations</h2>
                        <p>Daily sign-ups · {{ $periodFilter === 'all' ? 'last 30 days' : 'last '.$periodFilter.' days' }}</p>
                    </div>
                    <span class="ai-count">Daily signups</span>
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
                        <h2>Application Signals</h2>
                        <p>Recorded stages and outcomes across candidate pipelines</p>
                    </div>
                    <span class="ai-count">Stage distribution</span>
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
                        <h2>Where People Apply</h2>
                        <p>Top ten recorded recruitment platforms & portals</p>
                    </div>
                    <span class="ai-count">Top platforms</span>
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
                        <h2>Target Companies</h2>
                        <p>Most recorded employers in member trackers</p>
                    </div>
                    <span class="ai-count">Top employers</span>
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
                        <h2>Target Roles</h2>
                        <p>Most recorded job titles across member trackers</p>
                    </div>
                    <span class="ai-count">Top job roles</span>
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
        
        {{-- AI Workspace Feature Telemetry Strip --}}
        <section class="ai-panel ai-panel--glass">
            <header class="ai-panel-heading">
                <div>
                    <h2>AI Intelligence & Generation Telemetry</h2>
                    <p>Recorded AI requests in the reporting window, regardless of completion status.</p>
                </div>
                <span class="ai-count">Intelligence suite</span>
            </header>
            <div class="ai-usage-metrics">
                <div class="ai-usage-card">
                    <div class="ai-usage-card__icon text-[#8e4476]">
                        <i class="ph-bold ph-sparkle"></i>
                    </div>
                    <div>
                        <span>CV Analyses</span>
                        <strong>{{ number_format($aiUsageStats['ai_analyzer']) }}</strong>
                    </div>
                </div>
                <div class="ai-usage-card">
                    <div class="ai-usage-card__icon text-[#34749b]">
                        <i class="ph-bold ph-camera"></i>
                    </div>
                    <div>
                        <span>Photo Requests</span>
                        <strong>{{ number_format($aiUsageStats['ai_photos']) }}</strong>
                    </div>
                </div>
                <div class="ai-usage-card">
                    <div class="ai-usage-card__icon text-[#7751a0]">
                        <i class="ph-bold ph-envelope-simple-open"></i>
                    </div>
                    <div>
                        <span>Cover Letters</span>
                        <strong>{{ number_format($coverLetterStats['total']) }}</strong>
                    </div>
                </div>
                <div class="ai-usage-card">
                    <div class="ai-usage-card__icon text-[#ad573f]">
                        <i class="ph-bold ph-file-text"></i>
                    </div>
                    <div>
                        <span>CV Generations (Month)</span>
                        <strong>{{ number_format($aiUsageStats['ai_cv_gen']) }}</strong>
                    </div>
                </div>
            </div>
        </section>

        <div class="ai-grid">
            {{-- CV Analysis Activity --}}
            <section class="ai-panel">
                <header class="ai-panel-heading">
                    <div>
                        <h2>CV Analysis Activity</h2>
                        <p>Resume analyzer audits · Last 7 days</p>
                    </div>
                    <span class="ai-count">Resume audits</span>
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
                        <h2>Cover Letter Activity</h2>
                        <p>Last 14 days · {{ number_format($coverLetterStats['unique_users']) }} unique authors ({{ $coverLetterStats['adoption_rate'] }}% member adoption)</p>
                    </div>
                    <span class="ai-count">Letter generation</span>
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
                        <h2>Saved CV Templates</h2>
                        <p>{{ number_format($cvTemplatePopularity['total']) }} saved records · reporting window</p>
                    </div>
                    <span class="ai-count">Design adoption</span>
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
                        <h2>Weekly Goal Outcomes</h2>
                        <p>All saved goals tracked across user workspaces · all time</p>
                    </div>
                    <span class="ai-count">Milestones</span>
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
                    <h2>When Activity Happens</h2>
                    <p>Logged platform events by stored timestamp hour (WIB) · reporting window</p>
                </div>
                <span class="ai-count">{{ $peakActivityHours['peak_count'] ? 'Peak: '.$peakActivityHours['peak_hour'] : 'No activity logged' }}</span>
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
                        <h2>Email Verification</h2>
                        <p>All registered members · current verification state</p>
                    </div>
                    <span class="ai-count">Account validity</span>
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
                        <h2>Support Requests</h2>
                        <p>Reporting window · {{ number_format($supportTicketStats['total']) }} total tickets</p>
                    </div>
                    <span class="ai-count">Helpdesk queue</span>
                </header>
                <div class="ai-panel-body">
                    <x-admin.bars :labels="['Pending','Replied','Completed']" 
                                  :values="[$supportTicketStats['pending'], $supportTicketStats['replied'], $supportTicketStats['resolved']]" 
                                  name="tickets" 
                                  color="#7751a0" />
                    <p class="ai-footnote">
                        <i class="ph ph-info"></i> {{ $supportTicketStats['avg_resolution_hrs'] }} hours average to first recorded reply. {{ number_format($stats['activeUsers']) }} members registered in this window have populated profile experience, education, or skills.
                    </p>
                </div>
            </section>
        </div>

        {{-- Return Visits by Signup Month (Retention Cohort Table styled like /tracker) --}}
        <section class="ai-panel">
            <header class="ai-panel-heading">
                <div>
                    <h2>Return Visits by Signup Month</h2>
                    <p>Members with a logged event in the following calendar month · last 6 signup cohorts</p>
                </div>
                <span class="ai-count">Cohort retention</span>
            </header>
            <div class="au-table-container">
                <table class="au-tracker-table">
                    <thead>
                        <tr>
                            <th>Signup Month</th>
                            <th class="text-right">Cohort Size</th>
                            <th class="text-right">Returned M+1</th>
                            <th>Next-Month Retention</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($retentionCohort as $cohort)
                            @php($mature = \Carbon\Carbon::parse($cohort['month'])->addMonth()->endOfMonth()->isPast())
                            <tr>
                                <td class="font-semibold text-[#35264f]">
                                    <div class="flex items-center gap-2">
                                        <i class="ph-bold ph-calendar text-[#7751a0]"></i>
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
                                            {{ !$mature ? 'Window in progress' : 'No sign-ups' }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-6 text-zinc-500">
                                    No cohort records available yet.
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
                    <h2>Community Progress</h2>
                    <p>Top members by earned experience points (XP) · all time</p>
                </div>
                <span class="ai-count">Top achievers</span>
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
                                <span class="ai-level-badge">Level {{ $member['level'] }}</span>
                                <span class="ai-member-title">{{ $member['title'] }}</span>
                            </div>
                        </div>

                        <div class="ai-member-xp">
                            <strong>{{ number_format($member['xp']) }}</strong>
                            <small>XP</small>
                        </div>
                    </div>
                @empty
                    <div class="ai-empty">
                        <i class="ph-bold ph-trophy text-3xl text-zinc-400 mb-2"></i>
                        <h3>No members ranked yet</h3>
                        <p>Community milestones will appear as members earn XP in their workspaces.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</div>
