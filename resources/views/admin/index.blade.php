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

        // Optimized limit for high information density rendering
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

    <div class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-5 space-y-6 pb-12">

        <!-- Notion-style Minimal Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-zinc-100">
            <div class="flex items-center gap-2">
                <span class="text-xs font-mono font-medium text-zinc-400">Admin</span>
                <span class="text-zinc-300">/</span>
                <h1 class="text-xs font-bold font-mono tracking-wider uppercase text-zinc-900">Overview</h1>
            </div>

            <!-- Notion-style Action Links -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.email-blast') }}"
                    class="inline-flex items-center gap-1.5 px-3 py-1 bg-white border border-zinc-200 hover:bg-zinc-50 hover:border-zinc-300 text-zinc-700 font-medium text-xs rounded transition-all">
                    <i class="ph ph-paper-plane-tilt text-zinc-450 text-xs"></i> Email Blast
                </a>
                <a href="{{ route('admin.users') }}"
                    class="inline-flex items-center gap-1.5 px-3 py-1 bg-white border border-zinc-200 hover:bg-zinc-50 hover:border-zinc-300 text-zinc-700 font-medium text-xs rounded transition-all">
                    <i class="ph ph-users text-zinc-450 text-xs"></i> Users List
                </a>
                <a href="{{ route('admin.payments') }}"
                    class="inline-flex items-center gap-1.5 px-3 py-1 bg-white border border-zinc-200 hover:bg-zinc-50 hover:border-zinc-300 text-zinc-700 font-medium text-xs rounded transition-all">
                    <i class="ph ph-credit-card text-zinc-450 text-xs"></i> Payments
                </a>
            </div>
        </div>

        <!-- Notion-style Clean Stats Grid (4-Column) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Users -->
            <div class="bg-white rounded border border-zinc-200/70 p-4 hover:border-zinc-300 transition-all flex flex-col justify-between relative overflow-hidden group">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider block mb-1">Total Users</span>
                        <h3 class="text-2xl font-bold tracking-tight text-zinc-900">
                            {{ number_format(\App\Models\User::where('role', '!=', 'admin')->count()) }}
                        </h3>
                    </div>
                    <div class="w-7 h-7 rounded bg-zinc-50 border border-zinc-150 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-100 transition-colors">
                        <i class="ph ph-users text-sm"></i>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-4">
                    <span class="text-[10px] text-zinc-400 font-medium">Community Base</span>
                    <!-- Sparkline SVG -->
                    <svg class="w-14 h-5 text-purple-400/70" viewBox="0 0 100 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 25C15 25 20 5 35 5C50 5 55 20 70 20C85 20 90 10 100 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
            </div>

            <!-- Verified Users -->
            <div class="bg-white rounded border border-zinc-200/70 p-4 hover:border-zinc-300 transition-all flex flex-col justify-between relative overflow-hidden group">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider block mb-1">Verified Users</span>
                        <h3 class="text-2xl font-bold tracking-tight text-zinc-900">
                            {{ number_format(\App\Models\User::where('role', '!=', 'admin')->whereNotNull('email_verified_at')->count()) }}
                        </h3>
                    </div>
                    <div class="w-7 h-7 rounded bg-zinc-50 border border-zinc-150 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-100 transition-colors">
                        <i class="ph ph-check-circle text-sm"></i>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-4">
                    <span class="text-[10px] text-zinc-400 font-medium">Trusted Identity</span>
                    <!-- Sparkline SVG -->
                    <svg class="w-14 h-5 text-emerald-400/70" viewBox="0 0 100 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 15C10 10 20 25 30 20C40 15 50 5 60 10C70 15 80 5 90 8C95 10 100 2 100 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
            </div>

            <!-- Active Profiles -->
            <div class="bg-white rounded border border-zinc-200/70 p-4 hover:border-zinc-300 transition-all flex flex-col justify-between relative overflow-hidden group">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider block mb-1">Active Profiles</span>
                        <h3 class="text-2xl font-bold tracking-tight text-zinc-900">
                            {{ number_format($activeUsers) }}
                        </h3>
                    </div>
                    <div class="w-7 h-7 rounded bg-zinc-50 border border-zinc-150 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-100 transition-colors">
                        <i class="ph ph-user-focus text-sm"></i>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-4">
                    <span class="text-[10px] text-zinc-400 font-medium">CV Core Components</span>
                    <!-- Sparkline SVG -->
                    <svg class="w-14 h-5 text-blue-400/70" viewBox="0 0 100 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 28C10 28 20 20 30 18C40 15 50 25 60 22C70 18 80 8 90 5C95 3 100 2 100 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
            </div>

            <!-- Job Applications -->
            <div class="bg-white rounded border border-zinc-200/70 p-4 hover:border-zinc-300 transition-all flex flex-col justify-between relative overflow-hidden group">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider block mb-1">Applications</span>
                        <h3 class="text-2xl font-bold tracking-tight text-zinc-900">
                            {{ number_format($totalApps) }}
                        </h3>
                    </div>
                    <div class="w-7 h-7 rounded bg-zinc-50 border border-zinc-150 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-100 transition-colors">
                        <i class="ph ph-briefcase text-sm"></i>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-4">
                    <span class="text-[10px] text-zinc-400 font-medium">Platform Status Logs</span>
                    <!-- Sparkline SVG -->
                    <svg class="w-14 h-5 text-indigo-400/70" viewBox="0 0 100 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 20C15 20 25 15 35 15C45 15 55 25 65 20C75 15 85 8 100 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Growth & Chart Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Growth Overview Widget -->
            <div class="bg-white rounded border border-zinc-200/70 p-4 flex flex-col justify-between">
                <div>
                    <div class="pb-2 border-b border-zinc-100 mb-4">
                        <h2 class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Growth Metrics</h2>
                    </div>

                    <div class="space-y-4">
                        <!-- Today Signups -->
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-150 flex items-center justify-center text-zinc-500">
                                    <i class="ph ph-user-plus text-xs"></i>
                                </div>
                                <span class="text-zinc-500">Today Signups</span>
                            </div>
                            <span class="font-bold text-zinc-900">{{ number_format($newUsersToday) }}</span>
                        </div>

                        <!-- Premium Pro Users -->
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-150 flex items-center justify-center text-zinc-500">
                                    <i class="ph ph-crown text-xs"></i>
                                </div>
                                <span class="text-zinc-500">Premium Pro Users</span>
                            </div>
                            <span class="font-bold text-zinc-900">{{ number_format($proUsers) }}</span>
                        </div>

                        <!-- Goal Success Rate -->
                        <div class="space-y-2 pt-2 border-t border-zinc-100">
                            <div class="flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded bg-zinc-900 flex items-center justify-center text-white">
                                        <i class="ph ph-sparkle text-xs text-amber-300"></i>
                                    </div>
                                    <span class="font-medium text-zinc-700">Goal Success Rate</span>
                                </div>
                                @php $goalPercent = $totalGoals > 0 ? round(($achievedGoals / $totalGoals) * 100) : 0; @endphp
                                <span class="font-bold text-zinc-900">{{ $goalPercent }}%</span>
                            </div>
                            <!-- Minimal Progress Bar -->
                            <div class="w-full bg-zinc-100 h-1.5 rounded overflow-hidden">
                                <div class="bg-zinc-900 h-full rounded transition-all" style="width: {{ $goalPercent }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 mt-4 border-t border-zinc-100 flex items-center justify-between text-[10px]">
                    <span class="text-zinc-400">Total Goals Achieved</span>
                    <span class="font-mono font-medium text-zinc-500">
                        {{ number_format($achievedGoals) }} / {{ number_format($totalGoals) }}
                    </span>
                </div>
            </div>

            <!-- Signups Line Chart -->
            <div class="lg:col-span-2 bg-white rounded border border-zinc-200/70 p-4 flex flex-col">
                <div class="flex items-center justify-between pb-2 border-b border-zinc-100 mb-4">
                    <h2 class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Signups (Last 10 Days)</h2>
                    <span class="text-[9px] font-mono font-semibold text-zinc-500 bg-zinc-100 px-1.5 py-0.5 rounded">Active Growth</span>
                </div>
                <div class="h-[180px] relative w-full mt-2">
                    <canvas id="growthChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Notion-style Funnel Pipeline Visual -->
        <div class="bg-white rounded border border-zinc-200/70 p-5">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-5">
                <div>
                    <h2 class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Application Funnel</h2>
                    <p class="text-[10px] text-zinc-400 mt-0.5">Platform user recruitment pipeline analysis</p>
                </div>
                <span class="text-[10px] font-mono font-bold text-emerald-800 bg-emerald-50 border border-emerald-100/60 px-2 py-0.5 rounded">
                    {{ $overallSuccessRate }}% Overall Success
                </span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-center">
                <!-- Connected Stages Visual Layout -->
                <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-3">
                    <!-- Stage 1 -->
                    <div class="bg-zinc-50 border border-zinc-150 rounded p-3 flex flex-col justify-between min-h-[90px] hover:border-zinc-250 transition-colors">
                        <div>
                            <div class="flex items-center gap-1.5 text-zinc-400 mb-1">
                                <i class="ph ph-paper-plane-tilt text-xs"></i>
                                <span class="text-[9px] font-mono font-bold uppercase tracking-wider">Stage 1</span>
                            </div>
                            <h4 class="text-xs font-bold text-zinc-800">Total Applied</h4>
                        </div>
                        <div class="flex items-baseline justify-between mt-3">
                            <span class="text-lg font-bold text-zinc-900 font-mono">{{ number_format($totalApps) }}</span>
                            <span class="text-[9px] font-mono text-zinc-400">100% Base</span>
                        </div>
                    </div>

                    <!-- Stage 2 -->
                    <div class="bg-amber-50/30 border border-amber-200/50 rounded p-3 flex flex-col justify-between min-h-[90px] hover:border-amber-250 transition-colors relative">
                        <div>
                            <div class="flex items-center gap-1.5 text-amber-600/70 mb-1">
                                <i class="ph ph-chats-circle text-xs"></i>
                                <span class="text-[9px] font-mono font-bold uppercase tracking-wider">Stage 2</span>
                            </div>
                            <h4 class="text-xs font-bold text-amber-900">Interview Stage</h4>
                        </div>
                        <div class="flex items-baseline justify-between mt-3">
                            <span class="text-lg font-bold text-amber-800 font-mono">{{ number_format($interviewApps) }}</span>
                            <span class="text-[9px] font-mono font-bold text-amber-600 bg-amber-50 px-1 rounded">{{ $interviewRate }}% Rate</span>
                        </div>
                    </div>

                    <!-- Stage 3 -->
                    <div class="bg-emerald-50/30 border border-emerald-250/50 rounded p-3 flex flex-col justify-between min-h-[90px] hover:border-emerald-300 transition-colors">
                        <div>
                            <div class="flex items-center gap-1.5 text-emerald-600/70 mb-1">
                                <i class="ph ph-handshake text-xs"></i>
                                <span class="text-[9px] font-mono font-bold uppercase tracking-wider">Stage 3</span>
                            </div>
                            <h4 class="text-xs font-bold text-emerald-950">Got Offer</h4>
                        </div>
                        <div class="flex items-baseline justify-between mt-3">
                            <span class="text-lg font-bold text-emerald-800 font-mono">{{ number_format($offeredApps) }}</span>
                            <span class="text-[9px] font-mono font-bold text-emerald-600 bg-emerald-50 px-1 rounded">{{ $offerRate }}% Rate</span>
                        </div>
                    </div>
                </div>

                <!-- Doughnut Metric Ring -->
                <div class="bg-zinc-50/50 border border-zinc-150 rounded p-4 flex flex-col items-center justify-center">
                    <div class="relative w-[110px] aspect-square mb-3">
                        <canvas id="funnelDoughnutChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <span class="text-lg font-bold text-zinc-950 leading-none">{{ $overallSuccessRate }}%</span>
                            <span class="text-[7px] font-mono font-bold text-zinc-400 uppercase tracking-widest mt-1">Success</span>
                        </div>
                    </div>
                    <!-- Small Legend -->
                    <div class="flex items-center justify-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1.5 h-1.5 rounded-full bg-zinc-200"></div>
                            <span class="text-[8px] font-mono text-zinc-400 uppercase">Apply</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <div class="w-1.5 h-1.5 rounded-full bg-amber-400"></div>
                            <span class="text-[8px] font-mono text-zinc-400 uppercase">Intvw</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                            <span class="text-[8px] font-mono text-zinc-400 uppercase">Offer</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- High Information Density 3-Column Database Lists -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Column 1: Recent Applications -->
            <div class="bg-white rounded border border-zinc-200/70 flex flex-col h-[460px]">
                <div class="px-4 py-3 border-b border-zinc-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="ph ph-clock-counter-clockwise text-zinc-400 text-sm"></i>
                        <span class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Recent Applications</span>
                    </div>
                    <span class="text-[8px] font-mono font-bold text-zinc-400 bg-zinc-50 border border-zinc-200/60 px-1.5 py-0.5 rounded">Live</span>
                </div>
                <div class="divide-y divide-zinc-100 flex-1 overflow-y-auto custom-scrollbar">
                    @forelse($recentApplications as $application)
                        <div class="p-3 hover:bg-zinc-50/50 transition-colors flex items-start gap-2.5">
                            <div class="w-6 h-6 rounded bg-zinc-150 border border-zinc-200 flex items-center justify-center flex-shrink-0 text-zinc-650 text-[10px] font-bold">
                                {{ strtoupper(substr($application->user->name ?? 'A', 0, 1)) }}
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-0.5">
                                    <p class="text-xs font-semibold text-zinc-900 truncate pr-2">
                                        {{ $application->user->name ?? 'Unknown User' }}
                                    </p>
                                    <p class="text-[8px] text-zinc-400 font-mono">
                                        {{ $application->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <p class="text-[10px] text-zinc-500 truncate mb-1">
                                    <span class="font-semibold text-zinc-800">{{ $application->company_name }}</span> &bull; {{ $application->position }}
                                </p>
                                
                                <!-- Notion-style Badges -->
                                @if($application->status === 'applied')
                                    <span class="inline-block px-1.5 py-0.2 text-[8px] font-medium rounded bg-zinc-100 text-zinc-700 border border-zinc-200/50">Applied</span>
                                @elseif($application->status === 'interview')
                                    <span class="inline-block px-1.5 py-0.2 text-[8px] font-medium rounded bg-yellow-100 text-yellow-800 border border-yellow-250/20">Interview</span>
                                @elseif($application->status === 'accepted')
                                    <span class="inline-block px-1.5 py-0.2 text-[8px] font-medium rounded bg-emerald-100 text-emerald-800 border border-emerald-250/20">Accepted</span>
                                @elseif($application->status === 'rejected')
                                    <span class="inline-block px-1.5 py-0.2 text-[8px] font-medium rounded bg-rose-100 text-rose-850 border border-rose-250/20">Rejected</span>
                                @else
                                    <span class="inline-block px-1.5 py-0.2 text-[8px] font-medium rounded bg-zinc-100 text-zinc-600 border border-zinc-200">{{ $application->status }}</span>
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
            <div class="bg-white rounded border border-zinc-200/70 flex flex-col h-[460px]">
                <div class="px-4 py-3 border-b border-zinc-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="ph ph-trophy text-zinc-400 text-sm"></i>
                        <span class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Most Active</span>
                    </div>
                    <span class="text-[8px] font-mono font-bold text-zinc-400 bg-zinc-50 border border-zinc-200/60 px-1.5 py-0.5 rounded">Top 15</span>
                </div>
                <div class="divide-y divide-zinc-100 flex-1 overflow-y-auto custom-scrollbar">
                    @forelse($topAppliedUsers as $index => $user)
                        <div class="flex items-center gap-2.5 p-3 hover:bg-zinc-50/50 transition-colors">
                            <div class="w-5 h-5 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center font-mono font-bold text-[9px] {{ $index === 0 ? 'bg-amber-100/60 border-amber-200 text-amber-800' : ($index === 1 ? 'bg-zinc-200/70 text-zinc-700' : ($index === 2 ? 'bg-orange-100/50 border-orange-200 text-orange-800' : 'text-zinc-450')) }}">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-zinc-900 truncate">{{ $user->name }}</p>
                                <p class="text-[10px] text-zinc-400 truncate mt-0.5">{{ $user->email }}</p>
                            </div>
                            <span class="text-[9px] font-mono font-bold text-zinc-500 bg-zinc-50 border border-zinc-200 px-1.5 py-0.5 rounded">
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
            <div class="bg-white rounded border border-zinc-200/70 flex flex-col h-[460px]">
                <div class="px-4 py-3 border-b border-zinc-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="ph ph-warning-circle text-zinc-400 text-sm"></i>
                        <span class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Help Needed</span>
                    </div>
                    <span class="text-[8px] font-mono font-bold text-zinc-400 bg-zinc-50 border border-zinc-200/60 px-1.5 py-0.5 rounded">Declined</span>
                </div>
                <div class="divide-y divide-zinc-100 flex-1 overflow-y-auto custom-scrollbar">
                    @forelse($topDeclinedUsers as $index => $user)
                        <div class="flex items-center gap-2.5 p-3 hover:bg-zinc-50/50 transition-colors">
                            <div class="w-5 h-5 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center font-mono font-bold text-[9px] {{ $index === 0 ? 'bg-rose-100/50 border-rose-200 text-rose-800' : 'text-zinc-400' }}">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-zinc-900 truncate">{{ $user->name }}</p>
                                <p class="text-[10px] text-zinc-400 truncate mt-0.5">{{ $user->email }}</p>
                            </div>
                            <span class="text-[9px] font-mono font-bold text-rose-800 bg-rose-50 border border-rose-100 px-1.5 py-0.5 rounded">
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

    <!-- Chart.js and Custom Minimalist Theme Initialization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Growth Chart (Notion theme: thin lines, monospace ticks, no fill)
            const ctx = document.getElementById('growthChart')?.getContext('2d');
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($chartLabels) !!},
                        datasets: [{
                            label: 'Signups',
                            data: {!! json_encode($chartData) !!},
                            borderColor: '#18181b', // sleek dark zinc line
                            borderWidth: 1.5,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#18181b',
                            pointBorderWidth: 1.5,
                            pointRadius: 2.5,
                            pointHoverRadius: 4,
                            fill: false,
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
                                padding: 6,
                                cornerRadius: 4,
                                displayColors: false
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { font: { family: "monospace", size: 9 }, color: '#71717a' }
                            },
                            y: {
                                grid: { color: 'rgba(228, 228, 231, 0.5)', drawBorder: false, borderDash: [4, 4] },
                                ticks: { font: { family: "monospace", size: 9 }, color: '#71717a', stepSize: 5 },
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Funnel Doughnut Chart (thin, elegant minimalist loop)
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
                            backgroundColor: ['#e4e4e7', '#fbbf24', '#10b981'],
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
                                padding: 6,
                                cornerRadius: 4,
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