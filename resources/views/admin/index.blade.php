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
        $proUsers = \App\Models\User::where('is_premium', true)->where('payment_status', 'paid')->count();
        $newUsersToday = \App\Models\User::where('role', '!=', 'admin')->whereDate('created_at', \Carbon\Carbon::today('Asia/Jakarta'))->count();

        $chartLabels = [];
        $chartData = [];
        for ($i = 9; $i >= 0; $i--) {
            $date = \Carbon\Carbon::today('Asia/Jakarta')->subDays($i);
            $chartLabels[] = $date->format('D');
            $chartData[] = \App\Models\User::where('role', '!=', 'admin')
                ->whereDate('created_at', $date)
                ->count();
        }

        $totalApps = \App\Models\JobApplication::count();
        $interviewApps = \App\Models\JobApplication::whereIn('status', ['interview', 'accepted', 'rejected'])->where('recruitment_stage', '!=', 'Not Processed')->count();
        $offeredApps = \App\Models\JobApplication::whereIn('status', ['accepted'])->orWhere('recruitment_stage', 'Offering')->count();

        $interviewRate = $totalApps > 0 ? round(($interviewApps / $totalApps) * 100) : 0;
        $offerRate = $interviewApps > 0 ? round(($offeredApps / $interviewApps) * 100) : 0;
        $overallSuccessRate = $totalApps > 0 ? round(($offeredApps / $totalApps) * 100) : 0;

        $recentApplications = \App\Models\JobApplication::with('user')->latest()->take(15)->get();

        $topAppliedUsers = \App\Models\User::where('role', '!=', 'admin')
            ->withCount('jobApplications')
            ->orderBy('job_applications_count', 'desc')
            ->take(15)
            ->get();

        $topDeclinedUsers = \App\Models\User::where('role', '!=', 'admin')
            ->select('users.*')
            ->selectSub(function ($query) {
                $query->selectRaw('COUNT(*)')
                    ->from('job_applications')
                    ->whereColumn('job_applications.user_id', 'users.id')
                    ->where(function ($q) {
                        $q->where('application_status', 'Declined')
                            ->orWhere('recruitment_stage', 'Not Processed');
                    });
            }, 'declined_not_processed_count')
            ->havingRaw('declined_not_processed_count > 0')
            ->orderBy('declined_not_processed_count', 'desc')
            ->take(15)
            ->get();
    @endphp

    <div class="w-full mx-auto space-y-8 pb-16 text-left">

        <!-- Modern Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-5 border-b border-zinc-200/80">
            <div>
                <h1 class="text-2xl font-black text-zinc-950 tracking-tight">System Overview</h1>
                <p class="text-xs text-zinc-400 font-semibold mt-0.5">Monitor system performance, user growth, and core activities.</p>
            </div>

            <!-- Sleek Action Controls -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.email-blast') }}"
                    class="inline-flex items-center gap-2 px-3.5 py-2 bg-white border border-zinc-200 hover:bg-zinc-50 text-zinc-750 font-bold text-xs rounded-xl transition shadow-3xs">
                    <i class="ph-bold ph-paper-plane-tilt text-zinc-400 text-sm"></i> Email Blast
                </a>
                <a href="{{ route('admin.users') }}"
                    class="inline-flex items-center gap-2 px-3.5 py-2 bg-white border border-zinc-200 hover:bg-zinc-50 text-zinc-750 font-bold text-xs rounded-xl transition shadow-3xs">
                    <i class="ph-bold ph-users-three text-zinc-400 text-sm"></i> Users List
                </a>
                <a href="{{ route('admin.payments') }}"
                    class="inline-flex items-center gap-2 px-3.5 py-2 bg-white border border-zinc-200 hover:bg-zinc-50 text-zinc-750 font-bold text-xs rounded-xl transition shadow-3xs">
                    <i class="ph-bold ph-credit-card text-zinc-400 text-sm"></i> Payments
                </a>
            </div>
        </div>

        <!-- Metric Bento Cards (High Aesthetics & Micro-Animations) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Total Users -->
            <div class="bg-white rounded-2xl border border-zinc-200/80 p-6 shadow-[0_8px_30px_rgb(0,0,0,0.015)] hover:shadow-[0_15px_30px_rgba(0,0,0,0.03)] hover:-translate-y-1 transition duration-300 flex flex-col justify-between group relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[9px] font-mono font-bold text-zinc-450 uppercase tracking-widest block mb-1">Total Users</span>
                        <h3 class="text-3xl font-black tracking-tight text-zinc-950 font-mono">
                            {{ number_format(\App\Models\User::where('role', '!=', 'admin')->count()) }}
                        </h3>
                    </div>
                    <div class="w-9 h-9 rounded-xl bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-100 transition-colors shrink-0">
                        <i class="ph-bold ph-users text-lg"></i>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-6">
                    <span class="text-[10px] text-zinc-450 font-bold">Community Base</span>
                    <svg class="w-16 h-6 text-indigo-400/40" viewBox="0 0 100 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 25C15 25 20 5 35 5C50 5 55 20 70 20C85 20 90 10 100 8" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
            </div>

            <!-- Verified Users -->
            <div class="bg-white rounded-2xl border border-zinc-200/80 p-6 shadow-[0_8px_30px_rgb(0,0,0,0.015)] hover:shadow-[0_15px_30px_rgba(0,0,0,0.03)] hover:-translate-y-1 transition duration-300 flex flex-col justify-between group relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[9px] font-mono font-bold text-zinc-450 uppercase tracking-widest block mb-1">Verified Users</span>
                        <h3 class="text-3xl font-black tracking-tight text-zinc-950 font-mono">
                            {{ number_format(\App\Models\User::where('role', '!=', 'admin')->whereNotNull('email_verified_at')->count()) }}
                        </h3>
                    </div>
                    <div class="w-9 h-9 rounded-xl bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-100 transition-colors shrink-0">
                        <i class="ph-bold ph-seal-check text-lg"></i>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-6">
                    <span class="text-[10px] text-zinc-450 font-bold">Trusted Accounts</span>
                    <svg class="w-16 h-6 text-emerald-400/40" viewBox="0 0 100 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 15C10 10 20 25 30 20C40 15 50 5 60 10C70 15 80 5 90 8C95 10 100 2 100 2" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
            </div>

            <!-- Active Profiles -->
            <div class="bg-white rounded-2xl border border-zinc-200/80 p-6 shadow-[0_8px_30px_rgb(0,0,0,0.015)] hover:shadow-[0_15px_30px_rgba(0,0,0,0.03)] hover:-translate-y-1 transition duration-300 flex flex-col justify-between group relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[9px] font-mono font-bold text-zinc-450 uppercase tracking-widest block mb-1">Active Profiles</span>
                        <h3 class="text-3xl font-black tracking-tight text-zinc-950 font-mono">
                            {{ number_format($activeUsers) }}
                        </h3>
                    </div>
                    <div class="w-9 h-9 rounded-xl bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-100 transition-colors shrink-0">
                        <i class="ph-bold ph-user-circle-gear text-lg"></i>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-6">
                    <span class="text-[10px] text-zinc-450 font-bold">CV Configured</span>
                    <svg class="w-16 h-6 text-blue-400/40" viewBox="0 0 100 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 28C10 28 20 20 30 18C40 15 50 25 60 22C70 18 80 8 90 5C95 3 100 2 100 2" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
            </div>

            <!-- Total Applications -->
            <div class="bg-white rounded-2xl border border-zinc-200/80 p-6 shadow-[0_8px_30px_rgb(0,0,0,0.015)] hover:shadow-[0_15px_30px_rgba(0,0,0,0.03)] hover:-translate-y-1 transition duration-300 flex flex-col justify-between group relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[9px] font-mono font-bold text-zinc-450 uppercase tracking-widest block mb-1">Applications</span>
                        <h3 class="text-3xl font-black tracking-tight text-zinc-950 font-mono">
                            {{ number_format($totalApps) }}
                        </h3>
                    </div>
                    <div class="w-9 h-9 rounded-xl bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-100 transition-colors shrink-0">
                        <i class="ph-bold ph-briefcase text-lg"></i>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-6">
                    <span class="text-[10px] text-zinc-450 font-bold">Tracker Logs</span>
                    <svg class="w-16 h-6 text-purple-400/40" viewBox="0 0 100 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 20C15 20 25 15 35 15C45 15 55 25 65 20C75 15 85 8 100 5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Growth & Chart Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Growth Overview Panel -->
            <div class="bg-white rounded-2xl border border-zinc-200/80 p-6 shadow-[0_8px_30px_rgb(0,0,0,0.015)] flex flex-col justify-between">
                <div>
                    <div class="pb-3 border-b border-zinc-100 mb-5">
                        <h2 class="text-sm font-black text-zinc-900 tracking-tight">Growth Metrics</h2>
                    </div>

                    <div class="space-y-4">
                        <!-- Today Signups -->
                        <div class="flex items-center justify-between text-xs font-bold text-zinc-600">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500">
                                    <i class="ph-bold ph-user-plus text-base"></i>
                                </div>
                                <span>Today Signups</span>
                            </div>
                            <span class="font-mono text-zinc-900 text-sm font-black">{{ number_format($newUsersToday) }}</span>
                        </div>

                        <!-- Premium Pro Users -->
                        <div class="flex items-center justify-between text-xs font-bold text-zinc-600">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-violet-50 border border-violet-100 text-violet-600 flex items-center justify-center">
                                    <i class="ph-bold ph-crown text-base"></i>
                                </div>
                                <span>Premium Pro Users</span>
                            </div>
                            <span class="font-mono text-violet-600 text-sm font-black">{{ number_format($proUsers) }}</span>
                        </div>

                        <!-- Goal Success Rate -->
                        <div class="space-y-2 pt-4 border-t border-zinc-100">
                            <div class="flex items-center justify-between text-xs font-bold text-zinc-600">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-zinc-950 flex items-center justify-center text-white">
                                        <i class="ph-bold ph-sparkle text-base text-amber-300"></i>
                                    </div>
                                    <span>Goal Success Rate</span>
                                </div>
                                @php $goalPercent = $totalGoals > 0 ? round(($achievedGoals / $totalGoals) * 100) : 0; @endphp
                                <span class="font-mono text-zinc-950 text-sm font-black">{{ $goalPercent }}%</span>
                            </div>
                            <!-- Progress Bar -->
                            <div class="w-full bg-zinc-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-zinc-950 h-full rounded-full transition-all" style="width: {{ $goalPercent }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 mt-6 border-t border-zinc-150 flex items-center justify-between text-[10px] font-bold text-zinc-400">
                    <span>Total Goals Achieved</span>
                    <span class="font-mono text-zinc-900 bg-zinc-50 border border-zinc-200 px-2 py-0.5 rounded-lg">
                        {{ number_format($achievedGoals) }} / {{ number_format($totalGoals) }}
                    </span>
                </div>
            </div>

            <!-- Signups Line Chart -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-zinc-200/80 p-6 shadow-[0_8px_30px_rgb(0,0,0,0.015)] flex flex-col">
                <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-5">
                    <h2 class="text-sm font-black text-zinc-900 tracking-tight">Active Growth (Last 10 Days)</h2>
                    <span class="text-[9px] font-mono font-bold text-zinc-500 bg-zinc-100 border border-zinc-200 px-2.5 py-0.5 rounded-full">Signups Tracker</span>
                </div>
                <div class="h-[190px] relative w-full mt-2">
                    <canvas id="growthChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Application Funnel Pipeline (Premium Cohesive Colors) -->
        <div class="bg-white rounded-2xl border border-zinc-200/80 p-6 shadow-[0_8px_30px_rgb(0,0,0,0.015)]">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-zinc-100 mb-5 gap-2">
                <div>
                    <h2 class="text-sm font-black text-zinc-900 tracking-tight">Recruitment Funnel Pipeline</h2>
                    <p class="text-[10px] text-zinc-450 font-bold uppercase tracking-wider mt-0.5">Platform user application metrics & conversion rates</p>
                </div>
                <span class="text-[10px] font-mono font-black text-zinc-700 bg-zinc-50 border border-zinc-200 px-3 py-0.8 rounded-full inline-flex items-center gap-1.5 shrink-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                    {{ $overallSuccessRate }}% Success Rate
                </span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-center">
                <!-- Connected Stages Visual Layout -->
                <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Stage 1 -->
                    <div class="bg-zinc-50/50 border border-zinc-200/80 rounded-xl p-5 flex flex-col justify-between min-h-[105px] hover:border-zinc-300 transition duration-350">
                        <div>
                            <div class="flex items-center gap-1.5 text-zinc-450 mb-1">
                                <i class="ph-bold ph-paper-plane-tilt text-xs"></i>
                                <span class="text-[9px] font-mono font-bold uppercase tracking-wider">Stage 1</span>
                            </div>
                            <h4 class="text-xs font-bold text-zinc-750">Total Applied</h4>
                        </div>
                        <div class="flex items-baseline justify-between mt-3">
                            <span class="text-xl font-black text-zinc-950 font-mono">{{ number_format($totalApps) }}</span>
                            <span class="text-[9px] font-mono font-bold text-zinc-400 bg-white border border-zinc-200 px-1.5 py-0.2 rounded">100% Base</span>
                        </div>
                    </div>

                    <!-- Stage 2 -->
                    <div class="bg-zinc-50/50 border border-zinc-200/80 rounded-xl p-5 flex flex-col justify-between min-h-[105px] hover:border-zinc-300 transition duration-350">
                        <div>
                            <div class="flex items-center gap-1.5 text-zinc-450 mb-1">
                                <i class="ph-bold ph-chats-circle text-xs"></i>
                                <span class="text-[9px] font-mono font-bold uppercase tracking-wider">Stage 2</span>
                            </div>
                            <h4 class="text-xs font-bold text-zinc-750">Interview Stage</h4>
                        </div>
                        <div class="flex items-baseline justify-between mt-3">
                            <span class="text-xl font-black text-zinc-950 font-mono">{{ number_format($interviewApps) }}</span>
                            <span class="text-[9px] font-mono font-bold text-zinc-600 bg-white border border-zinc-200 px-1.5 py-0.2 rounded">{{ $interviewRate }}% Rate</span>
                        </div>
                    </div>

                    <!-- Stage 3 -->
                    <div class="bg-indigo-50/10 border border-indigo-150 rounded-xl p-5 flex flex-col justify-between min-h-[105px] hover:border-indigo-250 transition duration-350">
                        <div>
                            <div class="flex items-center gap-1.5 text-indigo-500 mb-1">
                                <i class="ph-bold ph-handshake text-xs"></i>
                                <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-indigo-400">Stage 3</span>
                            </div>
                            <h4 class="text-xs font-bold text-indigo-950">Got Offer</h4>
                        </div>
                        <div class="flex items-baseline justify-between mt-3">
                            <span class="text-xl font-black text-indigo-900 font-mono">{{ number_format($offeredApps) }}</span>
                            <span class="text-[9px] font-mono font-black text-indigo-700 bg-indigo-50 border border-indigo-150 px-1.5 py-0.2 rounded">{{ $offerRate }}% Rate</span>
                        </div>
                    </div>
                </div>

                <!-- Doughnut Metric Ring -->
                <div class="bg-zinc-50/30 border border-zinc-200/80 rounded-xl p-5 flex flex-col items-center justify-center">
                    <div class="relative w-[110px] aspect-square mb-3">
                        <canvas id="funnelDoughnutChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <span class="text-lg font-black text-zinc-950 leading-none">{{ $overallSuccessRate }}%</span>
                            <span class="text-[7px] font-mono font-bold text-zinc-400 uppercase tracking-widest mt-1">Success</span>
                        </div>
                    </div>
                    <!-- Small Legend -->
                    <div class="flex items-center justify-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1.5 h-1.5 rounded-full bg-zinc-200"></div>
                            <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase">Apply</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <div class="w-1.5 h-1.5 rounded-full bg-amber-400"></div>
                            <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase">Intvw</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <div class="w-1.5 h-1.5 rounded-full bg-indigo-500"></div>
                            <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase">Offer</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3-Column Recent Activity Lists -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Column 1: Recent Applications -->
            <div class="bg-white rounded-2xl border border-zinc-200/80 flex flex-col h-[460px] shadow-[0_8px_30px_rgb(0,0,0,0.015)]">
                <div class="px-5 py-4 border-b border-zinc-150 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <i class="ph-bold ph-clock-counter-clockwise text-zinc-450"></i>
                        <span class="text-[10px] font-mono font-bold text-zinc-450 uppercase tracking-widest">Recent Applications</span>
                    </div>
                    <span class="text-[8px] font-mono font-bold text-zinc-500 bg-zinc-100 border border-zinc-200 px-2 py-0.5 rounded-full">Live</span>
                </div>
                <div class="divide-y divide-zinc-100 flex-1 overflow-y-auto custom-scrollbar">
                    @forelse($recentApplications as $application)
                        <div class="p-4 hover:bg-zinc-50/50 transition flex items-start gap-3">
                            <div class="w-7 h-7 rounded-lg bg-zinc-100 border border-zinc-200 flex items-center justify-center flex-shrink-0 text-zinc-700 text-xs font-black">
                                {{ strtoupper(substr($application->user->name ?? 'A', 0, 1)) }}
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-0.5">
                                    <p class="text-xs font-bold text-zinc-950 truncate pr-2">
                                        {{ $application->user->name ?? 'Unknown User' }}
                                    </p>
                                    <p class="text-[8px] text-zinc-400 font-bold font-mono">
                                        {{ $application->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <p class="text-[10px] text-zinc-500 truncate mb-1.5">
                                    <span class="font-bold text-zinc-800">{{ $application->company_name }}</span> &bull; {{ $application->position }}
                                </p>
                                
                                <!-- Status Badges -->
                                @if($application->status === 'applied')
                                    <span class="inline-block px-1.5 py-0.2 text-[8px] font-bold rounded bg-zinc-100 text-zinc-700 border border-zinc-200/50">Applied</span>
                                @elseif($application->status === 'interview')
                                    <span class="inline-block px-1.5 py-0.2 text-[8px] font-bold rounded bg-yellow-50 text-yellow-800 border border-yellow-250/20">Interview</span>
                                @elseif($application->status === 'accepted')
                                    <span class="inline-block px-1.5 py-0.2 text-[8px] font-bold rounded bg-emerald-50 text-emerald-800 border border-emerald-250/20">Accepted</span>
                                @elseif($application->status === 'rejected')
                                    <span class="inline-block px-1.5 py-0.2 text-[8px] font-bold rounded bg-rose-50 text-rose-800 border border-rose-250/20">Rejected</span>
                                @else
                                    <span class="inline-block px-1.5 py-0.2 text-[8px] font-bold rounded bg-zinc-50 text-zinc-650 border border-zinc-200">{{ $application->status }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center flex flex-col items-center justify-center h-full">
                            <i class="ph ph-tray text-lg text-zinc-300 mb-2"></i>
                            <p class="text-[10px] text-zinc-400 font-medium">No applications yet</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Column 2: Most Applied Users -->
            <div class="bg-white rounded-2xl border border-zinc-200/80 flex flex-col h-[460px] shadow-[0_8px_30px_rgb(0,0,0,0.015)]">
                <div class="px-5 py-4 border-b border-zinc-150 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <i class="ph-bold ph-trophy text-zinc-450"></i>
                        <span class="text-[10px] font-mono font-bold text-zinc-450 uppercase tracking-widest">Most Active Users</span>
                    </div>
                    <span class="text-[8px] font-mono font-bold text-zinc-500 bg-zinc-100 border border-zinc-200 px-2 py-0.5 rounded-full">Top 15</span>
                </div>
                <div class="divide-y divide-zinc-100 flex-1 overflow-y-auto custom-scrollbar">
                    @forelse($topAppliedUsers as $index => $user)
                        <div class="flex items-center gap-2.5 p-4 hover:bg-zinc-50/50 transition">
                            <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center font-mono font-black text-[9px] shrink-0 {{ $index === 0 ? 'bg-amber-50 border-amber-200 text-amber-700' : ($index === 1 ? 'bg-zinc-100 text-zinc-650' : ($index === 2 ? 'bg-orange-50 border-orange-200 text-orange-700' : 'text-zinc-400')) }}">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-zinc-950 truncate leading-tight">{{ $user->name }}</p>
                                <p class="text-[10px] text-zinc-450 truncate mt-0.5 leading-none">{{ $user->email }}</p>
                            </div>
                            <span class="text-[9px] font-mono font-bold text-zinc-600 bg-zinc-50 border border-zinc-200 px-2 py-0.5 rounded-full shrink-0">
                                {{ number_format($user->job_applications_count) }} apps
                            </span>
                        </div>
                    @empty
                        <div class="py-12 text-center flex flex-col items-center justify-center h-full">
                            <i class="ph ph-tray text-lg text-zinc-300 mb-2"></i>
                            <p class="text-[10px] text-zinc-400 font-medium">No active users yet</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Column 3: Users Needing Help -->
            <div class="bg-white rounded-2xl border border-zinc-200/80 flex flex-col h-[460px] shadow-[0_8px_30px_rgb(0,0,0,0.015)]">
                <div class="px-5 py-4 border-b border-zinc-150 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <i class="ph-bold ph-warning-circle text-zinc-450"></i>
                        <span class="text-[10px] font-mono font-bold text-zinc-450 uppercase tracking-widest">Help Needed</span>
                    </div>
                    <span class="text-[8px] font-mono font-bold text-rose-700 bg-rose-50 border border-rose-250 px-2 py-0.5 rounded-full">Failed</span>
                </div>
                <div class="divide-y divide-zinc-100 flex-1 overflow-y-auto custom-scrollbar">
                    @forelse($topDeclinedUsers as $index => $user)
                        <div class="flex items-center gap-2.5 p-4 hover:bg-zinc-50/50 transition">
                            <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center font-mono font-black text-[9px] shrink-0 {{ $index === 0 ? 'bg-rose-50 border-rose-200 text-rose-700' : 'text-zinc-400' }}">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-zinc-950 truncate leading-tight">{{ $user->name }}</p>
                                <p class="text-[10px] text-zinc-450 truncate mt-0.5 leading-none">{{ $user->email }}</p>
                            </div>
                            <span class="text-[9px] font-mono font-bold text-rose-750 bg-rose-50 border border-rose-100 px-2 py-0.5 rounded-full shrink-0">
                                {{ number_format($user->declined_not_processed_count) }} fail
                            </span>
                        </div>
                    @empty
                        <div class="py-12 text-center flex flex-col items-center justify-center h-full">
                            <i class="ph ph-tray text-lg text-zinc-300 mb-2"></i>
                            <p class="text-[10px] text-zinc-400 font-medium">All applications processed</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    <!-- Chart.js and Custom Premium Theme Initialization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Growth Chart (Notion Premium: thin lines, monospace ticks, linear gradient fill)
            const ctx = document.getElementById('growthChart')?.getContext('2d');
            if (ctx) {
                // Create vertical gradient for area fill
                const gradient = ctx.createLinearGradient(0, 0, 0, 180);
                gradient.addColorStop(0, 'rgba(99, 102, 241, 0.15)');
                gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($chartLabels) !!},
                        datasets: [{
                            label: 'Signups',
                            data: {!! json_encode($chartData) !!},
                            borderColor: '#6366f1', // Indigo signature line
                            borderWidth: 2.5,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#6366f1',
                            pointBorderWidth: 2.5,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            fill: true,
                            backgroundColor: gradient,
                            tension: 0.35
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#18181b',
                                titleFont: { family: "system-ui, sans-serif", size: 10, weight: 'normal' },
                                bodyFont: { family: "system-ui, sans-serif", size: 11, weight: 'bold' },
                                padding: 8,
                                cornerRadius: 6,
                                displayColors: false
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { font: { family: "monospace", size: 9, weight: 'bold' }, color: '#71717a' }
                            },
                            y: {
                                grid: { color: 'rgba(228, 228, 231, 0.45)', drawBorder: false, borderDash: [4, 4] },
                                ticks: { font: { family: "monospace", size: 9, weight: 'bold' }, color: '#71717a', stepSize: 5 },
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Funnel Doughnut Chart (thin, elegant minimalist loop with indigo action color)
            const funnelCtx = document.getElementById('funnelDoughnutChart')?.getContext('2d');
            if (funnelCtx) {
                new Chart(funnelCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Applied', 'Interview', 'Offered'],
                        datasets: [{
                            data: [
                                {{ max(0, $totalApps - $interviewApps) }},
                                {{ max(0, $interviewApps - $offeredApps) }},
                                {{ $offeredApps }}
                            ],
                            backgroundColor: ['#e4e4e7', '#fbbf24', '#6366f1'],
                            borderWidth: 0,
                            hoverOffset: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        cutout: '84%',
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#18181b',
                                titleFont: { family: "system-ui, sans-serif", size: 10 },
                                bodyFont: { family: "system-ui, sans-serif", size: 11, weight: 'bold' },
                                padding: 8,
                                cornerRadius: 6,
                                displayColors: true,
                                callbacks: {
                                    label: function (context) {
                                        let label = context.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed !== null) {
                                            label += context.parsed + ' users';
                                        }
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-admin-layout>