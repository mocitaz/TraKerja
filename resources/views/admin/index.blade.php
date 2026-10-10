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

        $recentApplications = \App\Models\JobApplication::with('user')->latest()->take(15)->get();

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
    <div class="admin-dashboard">
        <section class="admin-welcome">
            <div><span class="admin-eyebrow">Admin workspace</span><h1>A clear view of TraKerja.</h1><p>People, applications, and the work that keeps things moving.</p></div>
            <a href="{{ route('admin.analytics') }}" class="admin-button">Explore analytics <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>
        </section>
        <section class="admin-metrics" aria-label="Platform overview">
            @foreach([
                ['Users', $totalUsers, $newUsersToday.' joined today', 'admin.users', 'pink'],
                ['CV contributors', $activeUsers, 'Added experience, education or skills', 'admin.users', 'blue'],
                ['Active premium', $proUsers, 'Paid accounts with active entitlement', 'admin.monetization', 'purple'],
                ['Applications', $totalApps, 'All recorded applications, including archive', 'admin.analytics', 'orange'],
            ] as [$label, $value, $detail, $route, $color])
                <a href="{{ route($route) }}" class="admin-metric"><span>{{ $label }}<i class="ph ph-arrow-up-right" aria-hidden="true"></i></span><strong class="admin-number--{{ $color }}">{{ number_format($value) }}</strong><small>{{ $detail }}</small></a>
            @endforeach
        </section>
        <div class="admin-chart-grid">
            <section class="admin-panel admin-growth">
                <div class="admin-panel-heading"><div><h2>User growth</h2><p>New user registrations · last 10 days · WIB</p></div><span class="admin-count-pill">{{ array_sum($chartData) }} {{ \Illuminate\Support\Str::plural('signup', array_sum($chartData)) }}</span></div>
                <div class="admin-growth-chart" role="group" aria-label="Daily user registrations">
                    @foreach($chartData as $index => $count)
                        <div class="admin-growth-column"><div class="admin-growth-track"><button type="button" class="admin-growth-bar" style="--bar-height: {{ max(3, $count / $chartMax * 100) }}%" aria-label="{{ $chartLabels[$index] }}: {{ $count }} new users"><span class="admin-chart-tooltip">{{ $chartLabels[$index] }} · {{ $count }} signups</span><span class="admin-growth-value">{{ $count }}</span></button></div><span>{{ $chartLabels[$index] }}</span></div>
                    @endforeach
                </div>
                <div class="admin-panel-foot"><span>Registrations use the account creation date.</span><a href="{{ route('admin.users') }}">View users <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a></div>
            </section>
            <section class="admin-panel admin-glass">
                <div class="admin-panel-heading"><div><h2>Application pulse</h2><p>Current records across the platform.</p></div></div>
                <div class="admin-pulse-list">
                    @foreach([['In selection', $interviewApps, 'blue', 'Recorded at test, interview or offering stages'], ['Offers & accepted', $offeredApps, 'purple', 'Offering stage or Accepted status'], ['Goals achieved', $achievedGoals, 'pink', $achievedGoals.' of '.$totalGoals.' recorded goals']] as [$label, $count, $color, $detail])
                        <div class="admin-pulse-row"><div><span>{{ $label }}</span><small>{{ $detail }}</small></div><strong class="admin-number--{{ $color }}">{{ number_format($count) }}</strong></div>
                    @endforeach
                </div>
                <div class="admin-goal-progress"><span>Goal completion <strong>{{ $goalRate }}%</strong></span><div role="progressbar" aria-label="Goal completion" aria-valuenow="{{ $goalRate }}" aria-valuemin="0" aria-valuemax="100"><span style="width: {{ $goalRate }}%"></span></div></div>
                <p class="admin-caption">Offers may also be counted in selection. These are current stages, not completed milestones.</p>
            </section>
        </div>
        <section class="admin-review-strip" aria-label="Items to review">
            <div><span class="admin-status-dot" aria-hidden="true"></span><strong>On your radar</strong><span>A few things to keep an eye on.</span></div>
            <a href="{{ route('admin.feedbacks.index') }}">Pending feedback <strong>{{ $pendingTickets }}</strong><i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>
            <a href="{{ route('admin.payments') }}">Pending payments <strong>{{ $pendingPayments }}</strong><i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>
        </section>
        <div class="admin-lower-grid">
            <section class="admin-panel admin-recent">
                <div class="admin-panel-heading"><div><h2>Recent applications</h2><p>The latest activity from your community.</p></div><a href="{{ route('admin.analytics') }}" class="admin-text-link">Analytics <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a></div>
                <div class="admin-table-scroll"><table class="admin-recent-table"><thead><tr><th scope="col">Applicant</th><th scope="col">Opportunity</th><th scope="col">Status</th><th scope="col">Added</th></tr></thead><tbody>
                    @forelse($recentApplications->take(8) as $application)
                        <tr><td><div class="admin-person"><img src="{{ $application->user?->avatar_url ?? asset('images/avatars/sky.svg') }}" alt="" loading="lazy"><span>{{ $application->user->name ?? 'Deleted user' }}</span></div></td><td><strong>{{ $application->position }}</strong><small>{{ $application->company_name }}</small></td><td><span class="admin-status-badge" data-status="{{ $application->application_status }}">{{ $application->application_status }}</span></td><td><time datetime="{{ $application->created_at->toIso8601String() }}" title="{{ $application->created_at->format('d M Y H:i') }}">{{ $application->created_at->diffForHumans() }}</time></td></tr>
                    @empty<tr><td colspan="4" class="admin-empty">No applications recorded yet.</td></tr>@endforelse
                </tbody></table></div>
            </section>
            <div class="admin-ranking-stack">
                <section class="admin-panel admin-glass">
                    <div class="admin-panel-heading"><div><h2>Most active applicants</h2><p>Ranked by recorded applications.</p></div><a href="{{ route('admin.users') }}" aria-label="Open user management" class="admin-round-link"><i class="ph ph-arrow-up-right" aria-hidden="true"></i></a></div>
                    <ol class="admin-ranking-list">@forelse($topAppliedUsers as $index => $person)<li><span class="admin-rank">{{ $index + 1 }}</span><img src="{{ $person->avatar_url }}" alt="" loading="lazy"><div><strong>{{ $person->name }}</strong><small>{{ $person->email }}</small></div><span class="admin-rank-count">{{ number_format($person->job_applications_count) }} <small>apps</small></span></li>@empty<li class="admin-empty">No applicants yet.</li>@endforelse</ol>
                </section>
                <section class="admin-panel admin-glass" data-admin-rejection-ranking>
                    <div class="admin-panel-heading"><div><h2>Help Needed</h2><p>Rejected or not processed · current records.</p></div><a href="{{ route('admin.users') }}" aria-label="Review users" class="admin-round-link"><i class="ph ph-arrow-up-right" aria-hidden="true"></i></a></div>
                    <ol class="admin-ranking-list">@forelse($topRejectedUsers as $index => $person)<li><span class="admin-rank">{{ $index + 1 }}</span><img src="{{ $person->avatar_url }}" alt="" loading="lazy"><div><strong>{{ $person->name }}</strong><small>{{ $person->email }}</small></div><span class="admin-rank-count admin-number--orange">{{ number_format($person->declined_not_processed_count) }} <small>fail</small></span></li>@empty<li class="admin-empty">No rejected or unprocessed applications.</li>@endforelse</ol>
                </section>
            </div>
        </div>
    </div>
</x-admin-layout>
