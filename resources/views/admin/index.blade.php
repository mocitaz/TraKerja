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
        $registrationDays = \App\Models\User::where('role', '!=', 'admin')
            ->where('created_at', '>=', \Carbon\Carbon::today('Asia/Jakarta')->subDays(59)->utc())
            ->get(['created_at'])
            ->countBy(fn ($user) => $user->created_at->timezone('Asia/Jakarta')->toDateString());
        for ($i = 59; $i >= 0; $i--) {
            $date = \Carbon\Carbon::today('Asia/Jakarta')->subDays($i);
            $chartLabels[] = $date->locale(app()->getLocale())->translatedFormat('d M');
            $chartData[] = $registrationDays->get($date->toDateString(), 0);
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

        $goalRate = $totalGoals > 0 ? round($achievedGoals / $totalGoals * 100) : 0;
    @endphp
    @php
        $recentSearch = $recentApplications->map(fn ($application) => mb_strtolower(implode(' ', [$application->user?->name, $application->user?->email, $application->position, $application->company_name, $application->application_status])))->values();
    @endphp
    <div class="ac-dashboard">
        <header class="ac-page-heading">
            <div><h1>{{ __('Good to see you, :value1.', ['value1' => explode(' ', Auth::user()->name)[0]]) }}</h1><p>{{ __('An overview of your members, applications, and platform activity.') }}</p></div>
            <time datetime="{{ now()->timezone('Asia/Jakarta')->toDateString() }}"><i class="ph ph-calendar-blank" aria-hidden="true"></i>{{ now()->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M Y') }}</time>
        </header>

        <section class="ac-stats" aria-label="{{ __('Platform totals') }}">
            <article><span class="ac-stat-icon"><i class="ph-fill ph-users" aria-hidden="true"></i></span><div><h2>{{ __('Total members') }}</h2><div class="ac-stat-value"><strong>{{ number_format($totalUsers) }}</strong><span>{{ __('+:value1 today', ['value1' => number_format($newUsersToday)]) }}</span></div></div></article>
            <article><span class="ac-stat-icon"><i class="ph-fill ph-credit-card" aria-hidden="true"></i></span><div><h2>{{ __('Active premium') }}</h2><div class="ac-stat-value"><strong>{{ number_format($proUsers) }}</strong><span class="ac-stat-note--neutral">{{ __('paid accounts') }}</span></div></div></article>
            <article><span class="ac-stat-icon"><i class="ph-fill ph-briefcase" aria-hidden="true"></i></span><div><h2>{{ __('Applications tracked') }}</h2><div class="ac-stat-value"><strong>{{ number_format($totalApps) }}</strong><span>{{ __(':value1% in selection', ['value1' => $interviewRate]) }}</span></div></div></article>
            <article><span class="ac-stat-icon"><i class="ph-fill ph-target" aria-hidden="true"></i></span><div><h2>{{ __('Career goals met') }}</h2><div class="ac-stat-value"><strong>{{ $goalRate }}<small>%</small></strong><span>{{ __(':value1 of :value2', ['value1' => number_format($achievedGoals), 'value2' => number_format($totalGoals)]) }}</span></div></div></article>
        </section>

        <div class="ac-dashboard-grid">
            <div class="ac-primary">
                <section class="ac-growth ac-growth-interactive" x-data="adminGrowth({ labels: @js($chartLabels), counts: @js($chartData), locale: @js(app()->getLocale()) })" aria-labelledby="ac-growth-title">
                    <div class="ac-section-heading"><div><h2 id="ac-growth-title">{{ __('Member growth') }}</h2><p>{{ __('See how your community is growing.') }}</p></div><div class="ac-growth-periods" role="group" aria-label="{{ __('Registration period') }}">
                        @foreach([7, 14, 30] as $days)<button type="button" @click="setPeriod({{ $days }})" :aria-pressed="(period === {{ $days }}).toString()">{{ __(':value1 days', ['value1' => $days]) }}</button>@endforeach
                    </div></div>
                    <div class="ac-growth-headline"><div class="ac-growth-total"><span>{{ __('New members') }}</span><strong x-text="total.toLocaleString(@js(app()->getLocale()))">{{ array_sum(array_slice($chartData, -14)) }}</strong><div class="ac-growth-comparison"><span x-show="change !== null" x-cloak :class="change < 0 ? 'is-down' : 'is-up'"><i class="ph-bold" :class="change < 0 ? 'ph-trend-down' : 'ph-trend-up'" aria-hidden="true"></i><b x-text="(change > 0 ? '+' : '') + change + '%'"></b></span><small x-text="previousTotal === 0 ? @js(__('No registrations in the previous period')) : @js(__('Compared with the previous period'))"></small></div></div><div class="ac-growth-mini-stats"><div><span>{{ __('Daily average') }}</span><strong x-text="average"></strong></div><div><span>{{ __('Best day') }}</span><strong x-text="peak"></strong><small x-text="peak ? dates[peakIndex] : '–'"></small></div></div></div>
                    <div class="ac-growth-selection"><span class="ac-growth-legend"><i aria-hidden="true"></i>{{ __('Member registrations') }}</span><p aria-live="polite"><span x-text="dates[selected]"></span><strong><span x-text="values[selected]"></span> {{ __('new members') }}</strong></p></div>
                    <div class="ac-growth-visual">
                        <div class="ac-growth-axis" aria-hidden="true"><template x-for="tick in ticks" :key="tick"><span x-text="tick"></span></template></div>
                        <div class="ac-growth-canvas">
                            <div class="ac-growth-tooltip" x-show="tooltipOpen" x-cloak :style="{left: tooltipLeft + '%'}" aria-hidden="true"><span x-text="dates[selected]"></span><strong><span x-text="values[selected]"></span> {{ __('new members') }}</strong></div>
                            <svg viewBox="0 0 624 244" preserveAspectRatio="none" role="img" aria-labelledby="ac-chart-title" @pointermove="inspect($event)" @pointerdown="inspect($event)" @pointerleave="tooltipOpen = false">
                                <title id="ac-chart-title">{{ __('Daily registrations. Use the date slider below to inspect each day.') }}</title>
                                <defs><linearGradient id="ac-growth-fill" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="currentColor" stop-opacity=".24"/><stop offset="100%" stop-color="currentColor" stop-opacity=".015"/></linearGradient></defs>
                                <path class="ac-growth-grid" d="M16 36H608M16 124H608M16 212H608"/>
                                <path class="ac-growth-fill" :d="area"/>
                                <path class="ac-growth-stroke" :d="line"/>
                                <line class="ac-growth-crosshair" :x1="x(selected)" :x2="x(selected)" y1="24" y2="212"/>
                                <circle class="ac-growth-marker-halo" :cx="x(selected)" :cy="y(values[selected])" r="11"/>
                                <circle class="ac-growth-marker" :cx="x(selected)" :cy="y(values[selected])" r="4.5"/>
                            </svg>
                            <div class="ac-growth-x-axis" aria-hidden="true"><template x-for="index in axisIndices" :key="index"><span x-text="dates[index]" :style="{left: (x(index) / 624 * 100) + '%'}"></span></template></div>
                        </div>
                    </div>
                    <div class="ac-growth-inspector"><button type="button" @click="step(-1)" :disabled="selected === 0" aria-label="{{ __('Previous day') }}"><i class="ph ph-caret-left" aria-hidden="true"></i></button><label><span class="sr-only">{{ __('Inspect registration date') }}</span><input type="range" min="0" :max="period - 1" x-model.number="selected" @input="tooltipOpen = true" @blur="tooltipOpen = false" :aria-valuetext="dates[selected] + ': ' + values[selected] + ' ' + @js(__('new members'))"></label><button type="button" @click="step(1)" :disabled="selected === period - 1" aria-label="{{ __('Next day') }}"><i class="ph ph-caret-right" aria-hidden="true"></i></button><span>{{ __('Drag to inspect') }}</span></div>
                    <div class="ac-growth-empty" x-cloak x-show="total === 0"><i class="ph ph-users" aria-hidden="true"></i>{{ __('No new members in this period. Try a wider date range.') }}</div>
                    <div class="ac-growth-bottom"><span>{{ __('Account creation date · Asia/Jakarta') }}</span><a href="{{ route('admin.analytics') }}">{{ __('View analytics') }}<i class="ph ph-arrow-up-right" aria-hidden="true"></i></a></div>
                </section>

                <section class="ac-applications" x-data="{ query: '', expanded: false, compact: window.innerWidth <= 540, records: @js($recentSearch), get matches() { return this.records.filter(record => record.includes(this.query.toLowerCase().trim())).length }, get shown() { return this.compact && !this.expanded && !this.query.trim() ? Math.min(5, this.matches) : this.matches } }" @resize.window.debounce.150ms="compact = window.innerWidth <= 540" aria-labelledby="ac-applications-title">
                    <div class="ac-section-heading"><div><h2 id="ac-applications-title">{{ __('Recent applications') }}</h2><p>{{ __('Latest :value1 applications across member workspaces', ['value1' => $recentApplications->count()]) }}</p></div><label class="ac-search"><i class="ph ph-magnifying-glass" aria-hidden="true"></i><span class="sr-only">{{ __('Search recent applications') }}</span><input type="search" x-model.debounce.150ms="query" placeholder="{{ __('Search applications') }}"></label></div>
                    <div id="ac-recent-list" class="ac-table-scroll" role="region" aria-label="{{ __('Recent applications') }}" tabindex="0">
                        <table class="ac-table" role="table"><thead><tr><th scope="col">{{ __('Applicant') }}</th><th scope="col">{{ __('Opportunity') }}</th><th scope="col">{{ __('Status') }}</th><th scope="col">{{ __('Logged') }}</th></tr></thead><tbody>
                            @forelse($recentApplications as $index => $application)
                                <tr class="ac-application-row" x-show="records[{{ $index }}].includes(query.toLowerCase().trim()) && (!compact || expanded || query.trim() || {{ $index }} < 5)">
                                    <td><div class="ac-person"><span class="ac-avatar"><span>{{ mb_strtoupper(mb_substr($application->user?->name ?? 'U', 0, 1)) }}</span>@if($application->user)<img src="{{ $application->user->avatar_url }}" alt="" width="34" height="34" loading="lazy" x-on:error="$el.remove()">@endif</span><div><strong>{{ $application->user?->name ?? __('Deleted user') }}</strong><small>{{ $application->user?->email ?? __('No email') }}</small></div></div></td>
                                    <td><strong>{{ $application->position }}</strong><small>{{ $application->company_name }}</small></td>
                                    <td><span class="ac-status ac-status--{{ match($application->application_status) { 'Accepted' => 'accepted', 'Rejected' => 'rejected', default => 'pending' } }}">{{ $application->application_status ?? __('On Process') }}</span></td>
                                    <td><time datetime="{{ $application->created_at->toIso8601String() }}" title="{{ $application->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }} WIB">{{ $application->created_at->diffForHumans() }}</time></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="ac-empty">{{ __('No applications recorded yet.') }}</td></tr>
                            @endforelse
                            @if($recentApplications->isNotEmpty())<tr x-cloak x-show="matches === 0"><td colspan="4" class="ac-empty">{{ __('No applications match your search.') }}</td></tr>@endif
                        </tbody></table>
                    </div>
                    <div class="ac-table-footer"><span aria-live="polite"><span x-text="shown">{{ $recentApplications->count() }}</span> {{ __('applications shown') }}</span><button type="button" class="ac-show-applications" x-cloak x-show="compact && records.length > 5 && !query.trim()" @click="expanded = !expanded" :aria-expanded="expanded.toString()" aria-controls="ac-recent-list" x-text="expanded ? @js(__('Show fewer')) : @js(__('Show all'))"></button><a href="{{ route('admin.user-activities') }}">{{ __('View activity log') }}</a></div>
                </section>
            </div>

            <div class="ac-secondary">
                <section class="ac-attention" aria-labelledby="ac-attention-title"><div class="ac-section-heading"><div><h2 id="ac-attention-title">{{ __('Needs attention') }}</h2><p>{{ __('Your administrative queue') }}</p></div><i class="ph ph-tray" aria-hidden="true"></i></div>
                    <a href="{{ route('admin.feedbacks.index') }}"><span class="ac-queue-icon"><i class="ph ph-chat-circle-text" aria-hidden="true"></i></span><span><strong>{{ __('Support tickets') }}</strong><small>{{ __('Waiting for a response') }}</small></span><b>{{ number_format($pendingTickets) }}</b><i class="ph ph-caret-right" aria-hidden="true"></i></a>
                    <a href="{{ route('admin.payments') }}"><span class="ac-queue-icon"><i class="ph ph-credit-card" aria-hidden="true"></i></span><span><strong>{{ __('Pending payments') }}</strong><small>{{ __('Awaiting payment completion') }}</small></span><b>{{ number_format($pendingPayments) }}</b><i class="ph ph-caret-right" aria-hidden="true"></i></a>
                </section>

                <section class="ac-pipeline" aria-labelledby="ac-pipeline-title"><div class="ac-section-heading"><div><h2 id="ac-pipeline-title">{{ __('Application pipeline') }}</h2><p>{{ __('Current recorded stages') }}</p></div></div>
                    <dl><div><dt>{{ __('In selection & testing') }}</dt><dd>{{ number_format($interviewApps) }}</dd></div><div><dt>{{ __('Offers & accepted') }}</dt><dd>{{ number_format($offeredApps) }}</dd></div><div><dt>{{ __('Career goals achieved') }}</dt><dd>{{ number_format($achievedGoals) }}</dd></div></dl>
                    <div class="ac-conversion"><span>{{ __('Applications with an offer') }}<strong>{{ $overallSuccessRate }}%</strong></span><div class="ac-progress" role="progressbar" aria-label="{{ __('Applications with an offer') }}" aria-valuenow="{{ $overallSuccessRate }}" aria-valuemin="0" aria-valuemax="100"><span style="width: {{ $overallSuccessRate }}%"></span></div></div><p class="ac-footnote">{{ __('Offers may also be counted in selection.') }}</p>
                </section>

                @foreach([
                    [__('Most active applicants'), __('Ranked by recorded applications'), $topAppliedUsers->take(5), 'job_applications_count', __('apps')],
                    [__('Candidates needing support'), __('Rejected or unprocessed applications'), $topRejectedUsers->take(5), 'declined_not_processed_count', __('unplaced')],
                ] as [$title, $description, $people, $countField, $countLabel])
                    <section class="ac-ranking" @if($countField === 'declined_not_processed_count') data-admin-rejection-ranking @endif><div class="ac-section-heading"><div><h2>{{ $title }}</h2><p>{{ $description }}</p></div><a href="{{ route('admin.users') }}" class="ac-icon-link" aria-label="{{ __('Open user management') }}"><i class="ph ph-arrow-up-right" aria-hidden="true"></i></a></div><ol>
                        @forelse($people as $index => $person)
                            <li><span class="ac-rank-number">{{ $index + 1 }}</span><span class="ac-avatar"><span>{{ mb_strtoupper(mb_substr($person->name, 0, 1)) }}</span><img src="{{ $person->avatar_url }}" alt="" width="34" height="34" loading="lazy" x-on:error="$el.remove()"></span><div><strong>{{ $person->name }}</strong><small>{{ $person->email }}</small></div><span class="ac-rank-count">{{ number_format($person->{$countField}) }}<small>{{ $countLabel }}</small></span></li>
                        @empty
                            <li class="ac-empty">{{ $countField === 'declined_not_processed_count' ? __('No rejected or unprocessed applications.') : __('No applicants yet.') }}</li>
                        @endforelse
                    </ol></section>
                @endforeach
            </div>
        </div>
    </div>
</x-admin-layout>
