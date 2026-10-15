<x-admin-layout>
    @php
        $activeUsers = \App\Models\User::where('role', '!=', 'admin')
            ->where(function ($query) {
                $query->whereHas('experiences')
                    ->orWhereHas('educations')
                    ->orWhereHas('skills');
            })
            ->count();

        $totalGoals = \App\Models\UserGoal::count();
        $achievedGoals = \App\Models\UserGoal::where('is_achieved', true)->count();
        $proUsers = \App\Models\User::where('role', '!=', 'admin')->where('is_premium', true)->where('payment_status', 'paid')
            ->where(fn ($q) => $q->whereNull('premium_until')->orWhere('premium_until', '>', now()))->count();
        $totalUsers = \App\Models\User::where('role', '!=', 'admin')->count();
        $pendingTickets = \App\Models\SupportTicket::where('status', 'pending')->count();
        $pendingPayments = \App\Models\Payment::whereIn('status', ['PENDING', 'WAITING'])->count();
        $newUsersToday = \App\Models\User::where('role', '!=', 'admin')->whereDate('created_at', \Carbon\Carbon::today('Asia/Jakarta'))->count();

        $chartLabels = [];
        $chartData = [];
        for ($i = 9; $i >= 0; $i--) {
            $date = \Carbon\Carbon::today('Asia/Jakarta')->subDays($i);
            $chartLabels[] = $date->format('d M');
            $chartData[] = \App\Models\User::where('role', '!=', 'admin')
                ->whereDate('created_at', $date)
                ->count();
        }

        $totalApps = \App\Models\JobApplication::count();
        $interviewApps = \App\Models\JobApplication::whereIn('recruitment_stage', ['Assessment Test', 'Psychotest', 'HR - Interview', 'User - Interview', 'LGD', 'Presentation Round', 'Offering'])->count();
        $offeredApps = \App\Models\JobApplication::where('application_status', 'Accepted')->orWhere('recruitment_stage', 'Offering')->count();

        $interviewRate = $totalApps > 0 ? round(($interviewApps / $totalApps) * 100) : 0;
        $offerRate = $interviewApps > 0 ? round(($offeredApps / $interviewApps) * 100) : 0;
        $overallSuccessRate = $totalApps > 0 ? round(($offeredApps / $totalApps) * 100) : 0;

        $recentApplications = \App\Models\JobApplication::with('user')->latest()->take(20)->get();

        $topAppliedUsers = \App\Models\User::where('role', '!=', 'admin')
            ->withCount('jobApplications')
            ->orderBy('job_applications_count', 'desc')
            ->take(15)
            ->get();

        $topRejectedUsers = \App\Models\User::where('role', '!=', 'admin')
            ->select('users.*')
            ->selectSub(function ($query) {
                $query->selectRaw('COUNT(*)')
                    ->from('job_applications')
                    ->whereColumn('job_applications.user_id', 'users.id')
                    ->where(function ($q) {
                        $q->where('application_status', 'Rejected')
                            ->orWhere('recruitment_stage', 'Not Processed');
                    });
            }, 'declined_not_processed_count')
            ->whereHas('jobApplications', function ($query) {
                $query->where('application_status', 'Rejected')
                    ->orWhere('recruitment_stage', 'Not Processed');
            })
            ->orderBy('declined_not_processed_count', 'desc')
            ->take(15)
            ->get();

        $chartMax = max(1, max($chartData));
        $goalRate = $totalGoals > 0 ? round($achievedGoals / $totalGoals * 100) : 0;
    @endphp

    <div class="admin-dashboard" x-data="{ 
        welcomeModalOpen: {{ session('show_admin_welcome_popup') ? 'true' : 'false' }},
        closeModal() {
            this.welcomeModalOpen = false;
        },
        openModal() {
            this.welcomeModalOpen = true;
        }
    }">

        {{-- ==========================================================================
             ADMIN WELCOME MODAL POPUP (Exact 1:1 match with user community welcome modal)
             ========================================================================== --}}
        <template x-teleport="body">
            <div class="community-overlay" 
                 x-show="welcomeModalOpen" 
                 x-cloak 
                 @click.self="closeModal()" 
                 @keydown.escape.window="closeModal()"
                 style="position: fixed; inset: 0; width: 100vw; height: 100vh; z-index: 999999; background: rgba(20, 16, 32, 0.58); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);"
                 x-transition:enter="community-fade" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="community-fade" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0">
                <section class="community-welcome" role="dialog" aria-modal="true" aria-labelledby="admin-modal-title" tabindex="-1">
                    <button type="button" class="community-close" @click="closeModal()" aria-label="Close welcome message">
                        <i class="ph ph-x" aria-hidden="true"></i>
                    </button>
                    <div class="community-copy">
                        <h2 id="admin-modal-title">Welcome back<span class="community-heading-dot">.</span></h2>
                        <p class="community-intro">Platform control and live telemetry<br class="community-desktop-break"> ready for your workspace.</p>
                        <p class="community-description">Review real-time member registrations, application tracker flows, and live platform signals directly from your command center.</p>
                        <div class="community-actions">
                            <button type="button" class="community-join" @click="closeModal()">
                                <span>Open Dashboard</span>
                                <i class="ph ph-arrow-right" aria-hidden="true"></i>
                            </button>
                            <a class="community-dashboard" href="{{ route('admin.analytics') }}" wire:navigate>
                                <span>Explore Analytics</span>
                                <span aria-hidden="true">→</span>
                            </a>
                        </div>
                        <p class="community-footnote">TraKerja Control Architecture · All telemetry connected in real-time.</p>
                    </div>
                    <div class="community-art" aria-hidden="true">
                        <div class="community-art-caption">TraKerja Control Terminal</div>
                        <svg class="community-connections" viewBox="0 0 400 400" fill="none">
                            <path d="M65 310C32 224 110 100 222 111C320 120 360 213 316 314" stroke="#bd91af" stroke-width="1.5" stroke-dasharray="5 8"/>
                            <path d="M103 284C151 360 269 358 309 290" stroke="#bd91af" stroke-width="1.5" stroke-dasharray="5 8"/>
                        </svg>
                        <div class="community-avatar community-avatar--peach"><img src="{{ asset('images/avatars/peach.svg') }}" alt="" width="120" height="120"></div>
                        <div class="community-avatar community-avatar--lilac"><img src="{{ asset('images/avatars/lilac.svg') }}" alt="" width="94" height="94"></div>
                        <div class="community-avatar community-avatar--sky"><img src="{{ asset('images/avatars/sky.svg') }}" alt="" width="100" height="100"></div>
                        <div class="community-speech community-speech--hello">Hey, welcome in! <span>✦</span></div>
                        <div class="community-speech community-speech--share"><span class="community-speech-dot"></span>Control active. Real-time telemetry.</div>
                        <div class="community-plane">
                            <svg viewBox="0 0 90 90" fill="none">
                                <path d="M12 38L77 15L57 77L39 52L12 38Z" fill="#fffafc" stroke="#994577" stroke-width="2" stroke-linejoin="round"/>
                                <path d="M39 52L77 15L30 45" stroke="#994577" stroke-width="2" stroke-linejoin="round"/>
                                <path d="M39 52L38 70L48 63" stroke="#994577" stroke-width="2" stroke-linejoin="round"/>
                                <path d="M7 61L19 58M14 75L28 69" stroke="#b65b90" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <span class="community-spark community-spark--one">✧</span><span class="community-spark community-spark--two">✦</span>
                        <div class="community-art-footer">
                            <span>Platform intelligence.</span>
                            <strong>All connected in real-time.</strong>
                        </div>
                    </div>
                </section>
            </div>
        </template>

        {{-- ==========================================================================
             HERO WELCOME BANNER (Exact 1:1 match with user dashboard.welcome)
             ========================================================================== --}}
        <section class="admin-hero-welcome" aria-labelledby="admin-hero-greeting">
            <div class="dashboard-welcome-copy admin-hero-copy">
                <time datetime="{{ now()->timezone('Asia/Jakarta')->toDateString() }}">{{ now()->timezone('Asia/Jakarta')->locale('en')->translatedFormat('l, F j, Y') }}</time>
                <h1 id="admin-hero-greeting">Good to see you, {{ explode(' ', Auth::user()->name)[0] }}.</h1>
                <p>Platform telemetry, member growth momentum, and candidate application pulse at a glance.</p>
                <a href="{{ route('admin.control') }}" class="control-entry"><i class="ph ph-strategy" aria-hidden="true"></i><span>Open Control</span><span aria-hidden="true">↗</span></a>
            </div>
        </section>

        {{-- ==========================================================================
             EXECUTIVE OVERVIEW STRIP (matches /summary design system)
             ========================================================================== --}}
        <section class="summary-overview" aria-label="Platform telemetry totals">
            <div class="summary-stat summary-stat--members">
                <span>Total members</span>
                <strong>{{ number_format($totalUsers) }}</strong>
                <p>+{{ number_format($newUsersToday) }} joined today · {{ number_format($activeUsers) }} active profiles</p>
            </div>
            
            <div class="summary-stat summary-stat--revenue">
                <span>Active premium</span>
                <strong>{{ number_format($proUsers) }}</strong>
                <p>Paid accounts with active entitlement status</p>
            </div>
            
            <div class="summary-stat summary-stat--applications">
                <span>Applications tracked</span>
                <strong>{{ number_format($totalApps) }}</strong>
                <p>{{ $interviewRate }}% conversion to interview/assessment</p>
            </div>
            
            <div class="summary-stat summary-stat--exports">
                <span>Career goals met</span>
                <strong>{{ $goalRate }}%</strong>
                <p>{{ number_format($achievedGoals) }} of {{ number_format($totalGoals) }} milestones completed</p>
            </div>

            <footer class="summary-stats-footer">
                <p>
                    <i class="ph ph-clock-countdown"></i> Real-time database telemetry · Live WIB clock
                </p>
                <div class="summary-footer-meta">
                    <a href="{{ route('admin.analytics') }}" class="summary-analytics-link">
                        <span>Deep-dive insights</span>
                        <i class="ph ph-arrow-up-right"></i>
                    </a>
                </div>
            </footer>
        </section>

        {{-- ==========================================================================
             OPERATIONAL RADAR ALERT STRIP
             ========================================================================== --}}
        <section class="admin-radar-strip" aria-label="Items on radar">
            <div class="admin-radar-lead">
                <span class="admin-radar-dot"></span>
                <strong>On your radar</strong>
                <span>Items requiring administrative attention</span>
            </div>
            <div class="admin-radar-items">
                <a href="{{ route('admin.feedbacks.index') }}" class="admin-radar-chip {{ $pendingTickets > 0 ? 'is-alert' : '' }}">
                    <i class="ph ph-chat-circle-text"></i>
                    <span>Support tickets</span>
                    <strong>{{ $pendingTickets }}</strong>
                    <i class="ph ph-arrow-up-right"></i>
                </a>
                <a href="{{ route('admin.payments') }}" class="admin-radar-chip {{ $pendingPayments > 0 ? 'is-alert' : '' }}">
                    <i class="ph ph-credit-card"></i>
                    <span>Pending payments</span>
                    <strong>{{ $pendingPayments }}</strong>
                    <i class="ph ph-arrow-up-right"></i>
                </a>
                <div class="admin-radar-progress">
                    <span class="admin-radar-label">Goal Completion: <strong>{{ $goalRate }}%</strong></span>
                    <div class="admin-radar-track" role="progressbar" aria-valuenow="{{ $goalRate }}" aria-valuemin="0" aria-valuemax="100">
                        <span style="width: {{ $goalRate }}%"></span>
                    </div>
                </div>
            </div>
        </section>

        {{-- ==========================================================================
             CHARTS & PIPELINE GRID (Spacious, Roomy & High Fidelity)
             ========================================================================== --}}
        <div class="admin-chart-grid">
            {{-- User Growth Velocity --}}
            <section class="admin-panel admin-growth">
                <div class="admin-panel-heading">
                    <div>
                        <h2>User Growth Velocity</h2>
                        <p>Daily account registrations · Last 10 days (Asia/Jakarta)</p>
                    </div>
                    <span class="admin-count-pill">{{ array_sum($chartData) }} {{ \Illuminate\Support\Str::plural('signup', array_sum($chartData)) }}</span>
                </div>
                
                <div class="admin-growth-chart" role="group" aria-label="Daily user registrations">
                    @foreach($chartData as $index => $count)
                        <div class="admin-growth-column">
                            <div class="admin-growth-track">
                                <button type="button" 
                                        class="admin-growth-bar" 
                                        style="--bar-height: {{ max(4, $count / $chartMax * 100) }}%" 
                                        aria-label="{{ $chartLabels[$index] }}: {{ $count }} new users">
                                    <span class="admin-chart-tooltip">{{ $chartLabels[$index] }} · {{ $count }} signups</span>
                                    <span class="admin-growth-value">{{ $count }}</span>
                                </button>
                            </div>
                            <span>{{ $chartLabels[$index] }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="admin-panel-foot">
                    <span>Registrations use the account creation date.</span>
                    <a href="{{ route('admin.users') }}">
                        <span>View user directory</span>
                        <i class="ph ph-arrow-up-right" aria-hidden="true"></i>
                    </a>
                </div>
            </section>

            {{-- Application Pipeline Pulse (Styled with Dashboard "Prepare for your interview" Palette #d0e3f3) --}}
            <section class="admin-panel admin-pulse-interview">
                <div class="admin-panel-heading">
                    <div>
                        <h2>Application Pipeline Pulse</h2>
                        <p>Current candidate conversion stages across all trackers</p>
                    </div>
                    <span class="admin-count-pill">{{ number_format($totalApps) }} total</span>
                </div>

                <div class="admin-pulse-list">
                    <div class="admin-pulse-row">
                        <div>
                            <span>In Selection & Testing</span>
                            <small>Assessment, HR, User interview, or testing stages</small>
                        </div>
                        <strong class="admin-number--blue">{{ number_format($interviewApps) }}</strong>
                    </div>

                    <div class="admin-pulse-row">
                        <div>
                            <span>Offers & Accepted</span>
                            <small>Offering stage or final Accepted status</small>
                        </div>
                        <strong class="admin-number--pink">{{ number_format($offeredApps) }}</strong>
                    </div>

                    <div class="admin-pulse-row">
                        <div>
                            <span>Career Goals Achieved</span>
                            <small>{{ $achievedGoals }} of {{ $totalGoals }} recorded milestones completed</small>
                        </div>
                        <strong class="admin-number--purple">{{ number_format($achievedGoals) }}</strong>
                    </div>
                </div>

                <div class="admin-goal-progress">
                    <div class="admin-goal-progress-header">
                        <span>Overall Conversion Rate</span>
                        <strong>{{ $overallSuccessRate }}%</strong>
                    </div>
                    <div class="admin-multi-track" role="progressbar" aria-label="Pipeline conversion rate" aria-valuenow="{{ $overallSuccessRate }}" aria-valuemin="0" aria-valuemax="100">
                        <span style="width: {{ $overallSuccessRate }}%"></span>
                    </div>
                </div>

                <p class="admin-caption">
                    <i class="ph ph-info"></i> Offers may also be counted in selection. These represent current recorded pipeline stages.
                </p>
            </section>
        </div>

        {{-- ==========================================================================
             LOWER GRID: RECENT APPLICATIONS & LEADERBOARD (Matched to /tracker density)
             ========================================================================== --}}
        <div class="admin-lower-grid">
            {{-- Left: Recent Applications Table (Full width layout matched to /tracker) --}}
            <section class="admin-panel admin-recent">
                <div class="admin-panel-heading">
                    <div>
                        <h2>Recent Applications</h2>
                        <p>Latest opportunities logged across candidate workspaces</p>
                    </div>
                    <a href="{{ route('admin.analytics') }}" class="admin-text-link">
                        <span>Open analytics</span>
                        <i class="ph ph-arrow-up-right" aria-hidden="true"></i>
                    </a>
                </div>

                <div class="au-table-container">
                    <table class="au-tracker-table admin-recent-table">
                        <thead>
                            <tr>
                                <th class="col-applicant">Applicant</th>
                                <th class="col-opportunity">Opportunity</th>
                                <th class="col-status text-center">Status</th>
                                <th class="col-logged text-right">Logged</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentApplications as $application)
                                <tr>
                                    <td class="col-applicant">
                                        <div class="admin-person">
                                            <div class="admin-avatar-mark">
                                                @if(!empty($application->user?->avatar_url))
                                                    <img src="{{ $application->user->avatar_url }}" alt="" loading="lazy">
                                                @else
                                                    <span>{{ strtoupper(substr($application->user->name ?? 'U', 0, 1)) }}</span>
                                                @endif
                                            </div>
                                            <div>
                                                <strong>{{ $application->user->name ?? 'Deleted user' }}</strong>
                                                <small>{{ $application->user->email ?? 'No email' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="col-opportunity">
                                        <div class="admin-opportunity">
                                            <strong>{{ $application->position }}</strong>
                                            <small>{{ $application->company_name }}</small>
                                        </div>
                                    </td>
                                    <td class="col-status text-center">
                                        @php
                                            $status = $application->application_status ?? 'On Process';
                                            $chipTone = match($status) {
                                                'Accepted' => 'accepted',
                                                'Rejected' => 'declined',
                                                default => 'progress'
                                            };
                                        @endphp
                                        <span class="admin-status-chip admin-status-chip--{{ $chipTone }}">
                                            {{ $status }}
                                        </span>
                                    </td>
                                    <td class="col-logged text-right">
                                        <time datetime="{{ $application->created_at->toIso8601String() }}" 
                                              title="{{ $application->created_at->format('d M Y H:i') }}"
                                              class="admin-table-time">
                                            <i class="ph ph-clock"></i>
                                            {{ $application->created_at->diffForHumans() }}
                                        </time>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-8 text-zinc-500">
                                        No applications recorded yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Right: Ranking Columns (Most Active & Candidates Needing Support) --}}
            <div class="admin-ranking-stack">
                {{-- Most Active Applicants --}}
                <section class="admin-panel admin-glass">
                    <div class="admin-panel-heading">
                        <div>
                            <h2>Most Active Applicants</h2>
                            <p>Ranked by recorded applications in tracker</p>
                        </div>
                        <a href="{{ route('admin.users') }}" aria-label="Open user management" class="admin-round-link">
                            <i class="ph ph-arrow-up-right" aria-hidden="true"></i>
                        </a>
                    </div>

                    <ol class="admin-ranking-list">
                        @forelse($topAppliedUsers->take(5) as $index => $person)
                            <li>
                                <div class="admin-rank-badge admin-rank-badge--{{ $index < 3 ? $index + 1 : 'default' }}">
                                    @if($index === 0)
                                        <i class="ph-fill ph-crown"></i>
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </div>
                                <div class="admin-rank-avatar">
                                    @if(!empty($person->avatar_url))
                                        <img src="{{ $person->avatar_url }}" alt="" loading="lazy">
                                    @else
                                        <span>{{ strtoupper(substr($person->name, 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div class="admin-rank-user">
                                    <strong>{{ $person->name }}</strong>
                                    <small>{{ $person->email }}</small>
                                </div>
                                <span class="admin-rank-count">
                                    {{ number_format($person->job_applications_count) }} <small>apps</small>
                                </span>
                            </li>
                        @empty
                            <li class="admin-empty">No applicants yet.</li>
                        @endforelse
                    </ol>
                </section>

                {{-- Candidates Needing Support (High Rejections / Unprocessed) --}}
                <section class="admin-panel admin-glass" data-admin-rejection-ranking>
                    <div class="admin-panel-heading">
                        <div>
                            <h2>Candidates Needing Support</h2>
                            <p>High rejection or unprocessed applications</p>
                        </div>
                        <a href="{{ route('admin.users') }}" aria-label="Review users" class="admin-round-link">
                            <i class="ph ph-arrow-up-right" aria-hidden="true"></i>
                        </a>
                    </div>

                    <ol class="admin-ranking-list">
                        @forelse($topRejectedUsers->take(5) as $index => $person)
                            <li>
                                <div class="admin-rank-badge admin-rank-badge--alert">
                                    {{ $index + 1 }}
                                </div>
                                <div class="admin-rank-avatar">
                                    @if(!empty($person->avatar_url))
                                        <img src="{{ $person->avatar_url }}" alt="" loading="lazy">
                                    @else
                                        <span>{{ strtoupper(substr($person->name, 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div class="admin-rank-user">
                                    <strong>{{ $person->name }}</strong>
                                    <small>{{ $person->email }}</small>
                                </div>
                                <span class="admin-rank-count admin-rank-count--alert">
                                    {{ number_format($person->declined_not_processed_count) }} <small>unplaced</small>
                                </span>
                            </li>
                        @empty
                            <li class="admin-empty">No rejected or unprocessed applications.</li>
                        @endforelse
                    </ol>
                </section>
            </div>
        </div>
    </div>
</x-admin-layout>
