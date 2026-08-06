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

    <div class="w-full mx-auto space-y-8 pb-16 text-left select-none">

        <!-- Header Terminal Style -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-5 border-b border-zinc-900">
            <div>
                <h1 class="text-2xl font-black text-white tracking-tight flex items-center gap-2">
                    System Control Center <span class="text-xs font-mono font-bold text-zinc-550 bg-zinc-900 border border-zinc-800 px-2 py-0.5 rounded-md">v2.4</span>
                </h1>
                <p class="text-xs text-zinc-500 font-semibold mt-1">Operational status, growth analytics, and global control matrix.</p>
            </div>

            <!-- Quick Terminal Actions -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.email-blast') }}"
                    class="inline-flex items-center gap-2 px-3.5 py-2 bg-zinc-900 hover:bg-zinc-850 border border-zinc-800 text-zinc-300 hover:text-white font-bold text-xs rounded-xl transition shadow-xl">
                    <i class="ph-bold ph-paper-plane-tilt text-indigo-400 text-sm"></i> Email Blast
                </a>
                <a href="{{ route('admin.users') }}"
                    class="inline-flex items-center gap-2 px-3.5 py-2 bg-zinc-900 hover:bg-zinc-850 border border-zinc-800 text-zinc-300 hover:text-white font-bold text-xs rounded-xl transition shadow-xl">
                    <i class="ph-bold ph-users-three text-indigo-400 text-sm"></i> Users List
                </a>
                <a href="{{ route('admin.payments') }}"
                    class="inline-flex items-center gap-2 px-3.5 py-2 bg-zinc-900 hover:bg-zinc-850 border border-zinc-800 text-zinc-300 hover:text-white font-bold text-xs rounded-xl transition shadow-xl">
                    <i class="ph-bold ph-credit-card text-indigo-400 text-sm"></i> Payments
                </a>
            </div>
        </div>

        <!-- Obsidian Glassmorphic Bento Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Total Users -->
            <div class="bg-zinc-900/25 border border-zinc-800/80 p-6 rounded-2xl hover:border-zinc-700/80 hover:bg-zinc-900/40 transition duration-300 flex flex-col justify-between group relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[9px] font-mono font-bold text-zinc-500 uppercase tracking-widest block mb-1">Total Users</span>
                        <h3 class="text-3xl font-black tracking-tight text-white font-mono leading-none">
                            {{ number_format(\App\Models\User::where('role', '!=', 'admin')->count()) }}
                        </h3>
                    </div>
                    <div class="w-9 h-9 rounded-xl bg-zinc-900 border border-zinc-800 flex items-center justify-center text-zinc-400 group-hover:text-indigo-400 transition-colors shrink-0">
                        <i class="ph-bold ph-users text-lg"></i>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-6">
                    <span class="text-[10px] text-zinc-500 font-bold">Registered Members</span>
                    <svg class="w-16 h-6 text-indigo-500/20 group-hover:text-indigo-500/40 transition-colors" viewBox="0 0 100 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 25C15 25 20 5 35 5C50 5 55 20 70 20C85 20 90 10 100 8" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
            </div>

            <!-- Verified Users -->
            <div class="bg-zinc-900/25 border border-zinc-800/80 p-6 rounded-2xl hover:border-zinc-700/80 hover:bg-zinc-900/40 transition duration-300 flex flex-col justify-between group relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[9px] font-mono font-bold text-zinc-500 uppercase tracking-widest block mb-1">Verified Users</span>
                        <h3 class="text-3xl font-black tracking-tight text-white font-mono leading-none">
                            {{ number_format(\App\Models\User::where('role', '!=', 'admin')->whereNotNull('email_verified_at')->count()) }}
                        </h3>
                    </div>
                    <div class="w-9 h-9 rounded-xl bg-zinc-900 border border-zinc-800 flex items-center justify-center text-zinc-400 group-hover:text-emerald-400 transition-colors shrink-0">
                        <i class="ph-bold ph-seal-check text-lg"></i>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-6">
                    <span class="text-[10px] text-zinc-500 font-bold">Trusted Identities</span>
                    <svg class="w-16 h-6 text-emerald-500/20 group-hover:text-emerald-500/40 transition-colors" viewBox="0 0 100 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 15C10 10 20 25 30 20C40 15 50 5 60 10C70 15 80 5 90 8C95 10 100 2 100 2" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
            </div>

            <!-- Active Profiles -->
            <div class="bg-zinc-900/25 border border-zinc-800/80 p-6 rounded-2xl hover:border-zinc-700/80 hover:bg-zinc-900/40 transition duration-300 flex flex-col justify-between group relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[9px] font-mono font-bold text-zinc-500 uppercase tracking-widest block mb-1">Active Profiles</span>
                        <h3 class="text-3xl font-black tracking-tight text-white font-mono leading-none">
                            {{ number_format($activeUsers) }}
                        </h3>
                    </div>
                    <div class="w-9 h-9 rounded-xl bg-zinc-900 border border-zinc-800 flex items-center justify-center text-zinc-400 group-hover:text-blue-400 transition-colors shrink-0">
                        <i class="ph-bold ph-user-circle-gear text-lg"></i>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-6">
                    <span class="text-[10px] text-zinc-500 font-bold">Resumes Filled</span>
                    <svg class="w-16 h-6 text-blue-500/20 group-hover:text-blue-500/40 transition-colors" viewBox="0 0 100 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 28C10 28 20 20 30 18C40 15 50 25 60 22C70 18 80 8 90 5C95 3 100 2 100 2" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
            </div>

            <!-- Total Applications -->
            <div class="bg-zinc-900/25 border border-zinc-800/80 p-6 rounded-2xl hover:border-zinc-700/80 hover:bg-zinc-900/40 transition duration-300 flex flex-col justify-between group relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[9px] font-mono font-bold text-zinc-500 uppercase tracking-widest block mb-1">Applications</span>
                        <h3 class="text-3xl font-black tracking-tight text-white font-mono leading-none">
                            {{ number_format($totalApps) }}
                        </h3>
                    </div>
                    <div class="w-9 h-9 rounded-xl bg-zinc-900 border border-zinc-800 flex items-center justify-center text-zinc-400 group-hover:text-purple-400 transition-colors shrink-0">
                        <i class="ph-bold ph-briefcase text-lg"></i>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-6">
                    <span class="text-[10px] text-zinc-500 font-bold">Applications Tracked</span>
                    <svg class="w-16 h-6 text-purple-500/20 group-hover:text-purple-500/40 transition-colors" viewBox="0 0 100 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 20C15 20 25 15 35 15C45 15 55 25 65 20C75 15 85 8 100 5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Growth & Chart Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Growth Overview Panel -->
            <div class="bg-zinc-900/25 border border-zinc-800/80 rounded-2xl p-6 flex flex-col justify-between">
                <div>
                    <div class="pb-3 border-b border-zinc-850 mb-5">
                        <h2 class="text-sm font-black text-white tracking-tight">Growth Core</h2>
                    </div>

                    <div class="space-y-4.5">
                        <!-- Today Signups -->
                        <div class="flex items-center justify-between text-xs font-bold text-zinc-400">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-zinc-900 border border-zinc-800 flex items-center justify-center text-zinc-400">
                                    <i class="ph-bold ph-user-plus text-base"></i>
                                </div>
                                <span>Today Signups</span>
                            </div>
                            <span class="font-mono text-white text-sm font-black">{{ number_format($newUsersToday) }}</span>
                        </div>

                        <!-- Premium Pro Users -->
                        <div class="flex items-center justify-between text-xs font-bold text-zinc-400">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center">
                                    <i class="ph-bold ph-crown text-base"></i>
                                </div>
                                <span>Premium Pro Users</span>
                            </div>
                            <span class="font-mono text-indigo-400 text-sm font-black">{{ number_format($proUsers) }}</span>
                        </div>

                        <!-- Goal Success Rate -->
                        <div class="space-y-2 pt-4 border-t border-zinc-850">
                            <div class="flex items-center justify-between text-xs font-bold text-zinc-400">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-white/10 border border-white/20 flex items-center justify-center text-white">
                                        <i class="ph-bold ph-sparkle text-base text-amber-300"></i>
                                    </div>
                                    <span>Goal Success Rate</span>
                                </div>
                                @php $goalPercent = $totalGoals > 0 ? round(($achievedGoals / $totalGoals) * 100) : 0; @endphp
                                <span class="font-mono text-white text-sm font-black">{{ $goalPercent }}%</span>
                            </div>
                            <!-- Progress Bar -->
                            <div class="w-full bg-zinc-900 h-2 rounded-full overflow-hidden border border-zinc-800">
                                <div class="bg-indigo-500 h-full rounded-full transition-all" style="width: {{ $goalPercent }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 mt-6 border-t border-zinc-850 flex items-center justify-between text-[10px] font-bold text-zinc-500">
                    <span>Total Goals Achieved</span>
                    <span class="font-mono text-white bg-zinc-900 border border-zinc-800 px-2.5 py-0.5 rounded-lg">
                        {{ number_format($achievedGoals) }} / {{ number_format($totalGoals) }}
                    </span>
                </div>
            </div>

            <!-- Signups Line Chart -->
            <div class="lg:col-span-2 bg-zinc-900/25 border border-zinc-800/80 rounded-2xl p-6 flex flex-col">
                <div class="flex items-center justify-between pb-3 border-b border-zinc-850 mb-5">
                    <h2 class="text-sm font-black text-white tracking-tight">Active Growth (Last 10 Days)</h2>
                    <span class="text-[9px] font-mono font-bold text-zinc-400 bg-zinc-900 border border-zinc-800 px-2.5 py-0.5 rounded-full">Signups Engine</span>
                </div>
                <div class="h-[190px] relative w-full mt-2">
                    <canvas id="growthChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Application Funnel Pipeline (Premium Obsidian Style) -->
        <div class="bg-zinc-900/25 border border-zinc-800/80 rounded-2xl p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-zinc-850 mb-5 gap-2">
                <div>
                    <h2 class="text-sm font-black text-white tracking-tight">Recruitment Funnel Pipeline</h2>
                    <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-wider mt-0.5">Platform user application metrics & conversion rates</p>
                </div>
                <span class="text-[10px] font-mono font-black text-indigo-400 bg-indigo-500/10 border border-indigo-500/20 px-3 py-0.8 rounded-full inline-flex items-center gap-1.5 shrink-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                    {{ $overallSuccessRate }}% Success Rate
                </span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-center">
                <!-- Connected Stages Visual Layout -->
                <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Stage 1 -->
                    <div class="bg-zinc-900/15 border border-zinc-800/60 rounded-xl p-5 flex flex-col justify-between min-h-[105px] hover:border-zinc-700 transition duration-300">
                        <div>
                            <div class="flex items-center gap-1.5 text-zinc-500 mb-1">
                                <i class="ph-bold ph-paper-plane-tilt text-xs"></i>
                                <span class="text-[9px] font-mono font-bold uppercase tracking-wider">Stage 1</span>
                            </div>
                            <h4 class="text-xs font-bold text-zinc-400">Total Applied</h4>
                        </div>
                        <div class="flex items-baseline justify-between mt-3">
                            <span class="text-xl font-black text-white font-mono">{{ number_format($totalApps) }}</span>
                            <span class="text-[9px] font-mono font-bold text-zinc-500 bg-zinc-900 border border-zinc-800 px-1.5 py-0.2 rounded">100% Base</span>
                        </div>
                    </div>

                    <!-- Stage 2 -->
                    <div class="bg-amber-500/5 border border-amber-500/20 rounded-xl p-5 flex flex-col justify-between min-h-[105px] hover:border-amber-500/40 transition duration-300">
                        <div>
                            <div class="flex items-center gap-1.5 text-amber-400 mb-1">
                                <i class="ph-bold ph-chats-circle text-xs"></i>
                                <span class="text-[9px] font-mono font-bold uppercase tracking-wider">Stage 2</span>
                            </div>
                            <h4 class="text-xs font-bold text-amber-300/90">Interview Stage</h4>
                        </div>
                        <div class="flex items-baseline justify-between mt-3">
                            <span class="text-xl font-black text-amber-400 font-mono">{{ number_format($interviewApps) }}</span>
                            <span class="text-[9px] font-mono font-bold text-amber-400 bg-amber-500/10 border border-amber-500/20 px-1.5 py-0.2 rounded">{{ $interviewRate }}% Rate</span>
                        </div>
                    </div>

                    <!-- Stage 3 -->
                    <div class="bg-indigo-500/5 border border-indigo-500/20 rounded-xl p-5 flex flex-col justify-between min-h-[105px] hover:border-indigo-500/40 transition duration-300">
                        <div>
                            <div class="flex items-center gap-1.5 text-indigo-400 mb-1">
                                <i class="ph-bold ph-handshake text-xs"></i>
                                <span class="text-[9px] font-mono font-bold uppercase tracking-wider">Stage 3</span>
                            </div>
                            <h4 class="text-xs font-bold text-indigo-300/90">Got Offer</h4>
                        </div>
                        <div class="flex items-baseline justify-between mt-3">
                            <span class="text-xl font-black text-indigo-400 font-mono">{{ number_format($offeredApps) }}</span>
                            <span class="text-[9px] font-mono font-bold text-indigo-400 bg-indigo-500/10 border border-indigo-500/20 px-1.5 py-0.2 rounded">{{ $offerRate }}% Rate</span>
                        </div>
                    </div>
                </div>

                <!-- Doughnut Metric Ring -->
                <div class="bg-zinc-900/15 border border-zinc-800/60 rounded-xl p-5 flex flex-col items-center justify-center">
                    <div class="relative w-[110px] aspect-square mb-3">
                        <canvas id="funnelDoughnutChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <span class="text-lg font-black text-white leading-none">{{ $overallSuccessRate }}%</span>
                            <span class="text-[7px] font-mono font-bold text-zinc-500 uppercase tracking-widest mt-1">Success</span>
                        </div>
                    </div>
                    <!-- Small Legend -->
                    <div class="flex items-center justify-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1.5 h-1.5 rounded-full bg-zinc-800"></div>
                            <span class="text-[8px] font-mono font-bold text-zinc-550 uppercase">Apply</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <div class="w-1.5 h-1.5 rounded-full bg-amber-400"></div>
                            <span class="text-[8px] font-mono font-bold text-zinc-550 uppercase">Intvw</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <div class="w-1.5 h-1.5 rounded-full bg-indigo-500"></div>
                            <span class="text-[8px] font-mono font-bold text-zinc-550 uppercase">Offer</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3-Column Recent Activity Lists -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Column 1: Recent Applications -->
            <div class="bg-zinc-900/25 border border-zinc-800/80 flex flex-col h-[460px] rounded-2xl shadow-xl">
                <div class="px-5 py-4 border-b border-zinc-850 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <i class="ph-bold ph-clock-counter-clockwise text-zinc-500"></i>
                        <span class="text-[10px] font-mono font-bold text-zinc-450 uppercase tracking-widest">Recent Applications</span>
                    </div>
                    <span class="text-[8px] font-mono font-bold text-zinc-400 bg-zinc-900 border border-zinc-800 px-2 py-0.5 rounded-full">Live</span>
                </div>
                <div class="divide-y divide-zinc-900 flex-1 overflow-y-auto custom-scrollbar">
                    @forelse($recentApplications as $application)
                        <div class="p-4 hover:bg-zinc-900/10 transition flex items-start gap-3">
                            <div class="w-7 h-7 rounded-lg bg-zinc-900 border border-zinc-800 flex items-center justify-center flex-shrink-0 text-zinc-300 text-xs font-black">
                                {{ strtoupper(substr($application->user->name ?? 'A', 0, 1)) }}
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-0.5">
                                    <p class="text-xs font-bold text-zinc-200 truncate pr-2">
                                        {{ $application->user->name ?? 'Unknown User' }}
                                    </p>
                                    <p class="text-[8px] text-zinc-550 font-bold font-mono">
                                        {{ $application->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <p class="text-[10px] text-zinc-500 truncate mb-1.5">
                                    <span class="font-bold text-zinc-300">{{ $application->company_name }}</span> &bull; {{ $application->position }}
                                </p>
                                
                                <!-- Status Badges -->
                                @if($application->status === 'applied')
                                    <span class="inline-block px-1.5 py-0.2 text-[8px] font-bold rounded bg-zinc-900 text-zinc-400 border border-zinc-800">Applied</span>
                                @elseif($application->status === 'interview')
                                    <span class="inline-block px-1.5 py-0.2 text-[8px] font-bold rounded bg-amber-500/10 text-amber-400 border border-amber-500/20">Interview</span>
                                @elseif($application->status === 'accepted')
                                    <span class="inline-block px-1.5 py-0.2 text-[8px] font-bold rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Accepted</span>
                                @elseif($application->status === 'rejected')
                                    <span class="inline-block px-1.5 py-0.2 text-[8px] font-bold rounded bg-rose-500/10 text-rose-400 border border-rose-500/20">Rejected</span>
                                @else
                                    <span class="inline-block px-1.5 py-0.2 text-[8px] font-bold rounded bg-zinc-900 text-zinc-400 border border-zinc-800">{{ $application->status }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center flex flex-col items-center justify-center h-full">
                            <i class="ph ph-tray text-lg text-zinc-550 mb-2"></i>
                            <p class="text-[10px] text-zinc-500 font-medium">No applications yet</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Column 2: Most Applied Users -->
            <div class="bg-zinc-900/25 border border-zinc-800/80 flex flex-col h-[460px] rounded-2xl shadow-xl">
                <div class="px-5 py-4 border-b border-zinc-850 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <i class="ph-bold ph-trophy text-zinc-500"></i>
                        <span class="text-[10px] font-mono font-bold text-zinc-450 uppercase tracking-widest">Most Active Users</span>
                    </div>
                    <span class="text-[8px] font-mono font-bold text-zinc-400 bg-zinc-900 border border-zinc-800 px-2 py-0.5 rounded-full">Top 15</span>
                </div>
                <div class="divide-y divide-zinc-900 flex-1 overflow-y-auto custom-scrollbar">
                    @forelse($topAppliedUsers as $index => $user)
                        <div class="flex items-center gap-2.5 p-4 hover:bg-zinc-900/10 transition">
                            <div class="w-6 h-6 rounded bg-zinc-900 border border-zinc-800 flex items-center justify-center font-mono font-black text-[9px] shrink-0 {{ $index === 0 ? 'bg-amber-500/10 border-amber-500/20 text-amber-400' : ($index === 1 ? 'bg-zinc-850 text-zinc-400' : ($index === 2 ? 'bg-orange-500/10 border-orange-500/20 text-orange-400' : 'text-zinc-500')) }}">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-zinc-200 truncate leading-tight">{{ $user->name }}</p>
                                <p class="text-[10px] text-zinc-500 truncate mt-0.5 leading-none">{{ $user->email }}</p>
                            </div>
                            <span class="text-[9px] font-mono font-bold text-zinc-400 bg-zinc-900 border border-zinc-800 px-2 py-0.5 rounded-full shrink-0">
                                {{ number_format($user->job_applications_count) }} apps
                            </span>
                        </div>
                    @empty
                        <div class="py-12 text-center flex flex-col items-center justify-center h-full">
                            <i class="ph ph-tray text-lg text-zinc-550 mb-2"></i>
                            <p class="text-[10px] text-zinc-500 font-medium">No active users yet</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Column 3: Users Needing Help -->
            <div class="bg-zinc-900/25 border border-zinc-800/80 flex flex-col h-[460px] rounded-2xl shadow-xl">
                <div class="px-5 py-4 border-b border-zinc-850 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <i class="ph-bold ph-warning-circle text-zinc-500"></i>
                        <span class="text-[10px] font-mono font-bold text-zinc-450 uppercase tracking-widest">Help Needed</span>
                    </div>
                    <span class="text-[8px] font-mono font-bold text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2 py-0.5 rounded-full">Failed</span>
                </div>
                <div class="divide-y divide-zinc-900 flex-1 overflow-y-auto custom-scrollbar">
                    @forelse($topDeclinedUsers as $index => $user)
                        <div class="flex items-center gap-2.5 p-4 hover:bg-zinc-900/10 transition">
                            <div class="w-6 h-6 rounded bg-zinc-900 border border-zinc-800 flex items-center justify-center font-mono font-black text-[9px] shrink-0 {{ $index === 0 ? 'bg-rose-500/10 border-rose-500/20 text-rose-400' : 'text-zinc-500' }}">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-zinc-200 truncate leading-tight">{{ $user->name }}</p>
                                <p class="text-[10px] text-zinc-500 truncate mt-0.5 leading-none">{{ $user->email }}</p>
                            </div>
                            <span class="text-[9px] font-mono font-bold text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2 py-0.5 rounded-full shrink-0">
                                {{ number_format($user->declined_not_processed_count) }} fail
                            </span>
                        </div>
                    @empty
                        <div class="py-12 text-center flex flex-col items-center justify-center h-full">
                            <i class="ph ph-tray text-lg text-zinc-550 mb-2"></i>
                            <p class="text-[10px] text-zinc-500 font-medium">All applications processed</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    <!-- Chart.js and Custom Obsidian Neon Theme Initialization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Growth Chart (Obsidian Theme: Glowing Indigo Line, Minimal Grid)
            const ctx = document.getElementById('growthChart')?.getContext('2d');
            if (ctx) {
                // Indigo Glow Gradient
                const gradient = ctx.createLinearGradient(0, 0, 0, 180);
                gradient.addColorStop(0, 'rgba(99, 102, 241, 0.22)');
                gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($chartLabels) !!},
                        datasets: [{
                            label: 'Signups',
                            data: {!! json_encode($chartData) !!},
                            borderColor: '#818cf8', // Bright Indigo Neon
                            borderWidth: 3,
                            pointBackgroundColor: '#09090b',
                            pointBorderColor: '#818cf8',
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
                                backgroundColor: '#09090b',
                                borderColor: '#3f3f46',
                                borderWidth: 1,
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
                                ticks: { font: { family: "monospace", size: 9, weight: 'bold' }, color: '#52525b' }
                            },
                            y: {
                                grid: { color: 'rgba(255, 255, 255, 0.05)', drawBorder: false, borderDash: [4, 4] },
                                ticks: { font: { family: "monospace", size: 9, weight: 'bold' }, color: '#52525b', stepSize: 5 },
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Funnel Doughnut Chart (Obsidian Theme)
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
                            backgroundColor: ['#27272a', '#fbbf24', '#6366f1'],
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
                                backgroundColor: '#09090b',
                                borderColor: '#3f3f46',
                                borderWidth: 1,
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