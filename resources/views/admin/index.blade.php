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

        // Menggunakan inline style hex gradient agar 100% aman ter-render di produksi (CORS & Tailwind Dynamic Class Safe)
        if (!function_exists('getAvatarStyle')) {
            function getAvatarStyle($name) {
                $char = strtoupper(substr($name ?? 'A', 0, 1));
                $colors = [
                    'A' => ['#6366f1', '#a855f7'],
                    'B' => ['#a855f7', '#ec4899'],
                    'C' => ['#ec4899', '#f43f5e'],
                    'D' => ['#f43f5e', '#ef4444'],
                    'E' => ['#ef4444', '#f97316'],
                    'F' => ['#f97316', '#f59e0b'],
                    'G' => ['#f59e0b', '#eab308'],
                    'H' => ['#eab308', '#84cc16'],
                    'I' => ['#84cc16', '#22c55e'],
                    'J' => ['#22c55e', '#10b981'],
                    'K' => ['#10b981', '#14b8a6'],
                    'L' => ['#14b8a6', '#06b6d4'],
                    'M' => ['#06b6d4', '#0ea5e9'],
                    'N' => ['#0ea5e9', '#3b82f6'],
                    'O' => ['#3b82f6', '#6366f1'],
                    'P' => ['#6366f1', '#8b5cf6'],
                    'Q' => ['#8b5cf6', '#d946ef'],
                    'R' => ['#d946ef', '#ec4899'],
                    'S' => ['#ec4899', '#f43f5e'],
                    'T' => ['#f43f5e', '#f97316'],
                    'U' => ['#f97316', '#eab308'],
                    'V' => ['#eab308', '#22c55e'],
                    'W' => ['#22c55e', '#14b8a6'],
                    'X' => ['#14b8a6', '#0ea5e9'],
                    'Y' => ['#0ea5e9', '#6366f1'],
                    'Z' => ['#6366f1', '#ec4899'],
                ];
                $pair = $colors[$char] ?? ['#71717a', '#52525b'];
                return "background: linear-gradient(135deg, {$pair[0]} 0%, {$pair[1]} 100%);";
            }
        }
    @endphp

    <div class="w-full mx-auto space-y-5 pb-8 text-left select-none">

        <!-- Header Section (Sleek and Clean) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-zinc-200">
            <div>
                <h1 class="text-lg font-black text-zinc-900 tracking-tight flex items-center gap-1.5">
                    System Control Center <span class="text-[9px] font-mono font-bold text-zinc-450 bg-zinc-100 border border-zinc-200 px-1.5 py-0.2 rounded">v2.6</span>
                </h1>
                <p class="text-[10px] text-zinc-500 font-semibold mt-0.5">Global operational analytics and conversion monitoring.</p>
            </div>

            <!-- Header Quick Actions (Ramping) -->
            <div class="flex flex-wrap items-center gap-1.5">
                <a href="{{ route('admin.email-blast') }}"
                    class="inline-flex items-center gap-1.5 px-2.5 py-1.2 bg-white border border-zinc-200 hover:bg-zinc-50 text-zinc-700 font-bold text-[10px] rounded-lg transition shadow-3xs">
                    <i class="ph-bold ph-paper-plane-tilt text-indigo-650 text-xs"></i> Email Blast
                </a>
                <a href="{{ route('admin.users') }}"
                    class="inline-flex items-center gap-1.5 px-2.5 py-1.2 bg-white border border-zinc-200 hover:bg-zinc-50 text-zinc-700 font-bold text-[10px] rounded-lg transition shadow-3xs">
                    <i class="ph-bold ph-users-three text-indigo-650 text-xs"></i> Users List
                </a>
                <a href="{{ route('admin.payments') }}"
                    class="inline-flex items-center gap-1.5 px-2.5 py-1.2 bg-white border border-zinc-200 hover:bg-zinc-50 text-zinc-700 font-bold text-[10px] rounded-lg transition shadow-3xs">
                    <i class="ph-bold ph-credit-card text-indigo-650 text-xs"></i> Payments
                </a>
            </div>
        </div>

        <!-- High-Density Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Users -->
            <div class="bg-white rounded-xl border border-zinc-200 p-4 hover:border-zinc-300 transition duration-200 shadow-[0_4px_20px_rgba(0,0,0,0.008)] flex flex-col justify-between group relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest block mb-0.5">Total Users</span>
                        <h3 class="text-xl font-extrabold tracking-tight text-zinc-900 font-mono leading-none">
                            {{ number_format(\App\Models\User::where('role', '!=', 'admin')->count()) }}
                        </h3>
                    </div>
                    <div class="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-950 group-hover:text-white transition duration-200 shrink-0">
                        <i class="ph-bold ph-users text-sm"></i>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-4">
                    <span class="text-[9px] text-zinc-400 font-bold">Members</span>
                    <svg class="w-12 h-4 text-indigo-400/40" viewBox="0 0 100 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 25C15 25 20 5 35 5C50 5 55 20 70 20C85 20 90 10 100 8" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
            </div>

            <!-- Verified Users -->
            <div class="bg-white rounded-xl border border-zinc-200 p-4 hover:border-zinc-300 transition duration-200 shadow-[0_4px_20px_rgba(0,0,0,0.008)] flex flex-col justify-between group relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest block mb-0.5">Verified</span>
                        <h3 class="text-xl font-extrabold tracking-tight text-zinc-900 font-mono leading-none">
                            {{ number_format(\App\Models\User::where('role', '!=', 'admin')->whereNotNull('email_verified_at')->count()) }}
                        </h3>
                    </div>
                    <div class="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500 group-hover:bg-emerald-600 group-hover:text-white transition duration-200 shrink-0">
                        <i class="ph-bold ph-seal-check text-sm"></i>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-4">
                    <span class="text-[9px] text-zinc-400 font-bold">Identities</span>
                    <svg class="w-12 h-4 text-emerald-400/40" viewBox="0 0 100 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 15C10 10 20 25 30 20C40 15 50 5 60 10C70 15 80 5 90 8C95 10 100 2 100 2" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
            </div>

            <!-- Active Profiles -->
            <div class="bg-white rounded-xl border border-zinc-200 p-4 hover:border-zinc-300 transition duration-200 shadow-[0_4px_20px_rgba(0,0,0,0.008)] flex flex-col justify-between group relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest block mb-0.5">Active CV</span>
                        <h3 class="text-xl font-extrabold tracking-tight text-zinc-900 font-mono leading-none">
                            {{ number_format($activeUsers) }}
                        </h3>
                    </div>
                    <div class="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500 group-hover:bg-blue-600 group-hover:text-white transition duration-200 shrink-0">
                        <i class="ph-bold ph-user-circle-gear text-sm"></i>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-4">
                    <span class="text-[9px] text-zinc-400 font-bold">Configured</span>
                    <svg class="w-12 h-4 text-blue-400/40" viewBox="0 0 100 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 28C10 28 20 20 30 18C40 15 50 25 60 22C70 18 80 8 90 5C95 3 100 2 100 2" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
            </div>

            <!-- Total Applications -->
            <div class="bg-white rounded-xl border border-zinc-200 p-4 hover:border-zinc-300 transition duration-200 shadow-[0_4px_20px_rgba(0,0,0,0.008)] flex flex-col justify-between group relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest block mb-0.5">Applications</span>
                        <h3 class="text-xl font-extrabold tracking-tight text-zinc-900 font-mono leading-none">
                            {{ number_format($totalApps) }}
                        </h3>
                    </div>
                    <div class="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500 group-hover:bg-purple-650 group-hover:text-white transition duration-200 shrink-0">
                        <i class="ph-bold ph-briefcase text-sm"></i>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-4">
                    <span class="text-[9px] text-zinc-400 font-bold">Tracker Logs</span>
                    <svg class="w-12 h-4 text-purple-400/40" viewBox="0 0 100 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 20C15 20 25 15 35 15C45 15 55 25 65 20C75 15 85 8 100 5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Growth & Chart Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            
            <!-- Growth Overview Panel -->
            <div class="bg-white rounded-xl border border-zinc-200 p-4 shadow-[0_4px_20px_rgba(0,0,0,0.008)] flex flex-col justify-between">
                <div>
                    <div class="pb-2 border-b border-zinc-100 mb-3.5">
                        <h2 class="text-[10px] font-black text-zinc-800 tracking-tight uppercase">Growth Engine</h2>
                    </div>

                    <div class="space-y-3">
                        <!-- Today Signups -->
                        <div class="flex items-center justify-between text-[11px] font-bold text-zinc-650">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500">
                                    <i class="ph-bold ph-user-plus text-xs"></i>
                                </div>
                                <span>Today Signups</span>
                            </div>
                            <span class="font-mono text-zinc-900 text-xs font-black">{{ number_format($newUsersToday) }}</span>
                        </div>

                        <!-- Premium Pro Users -->
                        <div class="flex items-center justify-between text-[11px] font-bold text-zinc-650">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-650 flex items-center justify-center">
                                    <i class="ph-bold ph-crown text-xs"></i>
                                </div>
                                <span>Premium Pro Users</span>
                            </div>
                            <span class="font-mono text-indigo-600 text-xs font-black">{{ number_format($proUsers) }}</span>
                        </div>

                        <!-- Goal Success Rate -->
                        <div class="space-y-1.5 pt-3 border-t border-zinc-100">
                            <div class="flex items-center justify-between text-[11px] font-bold text-zinc-650">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-zinc-950 flex items-center justify-center text-white">
                                        <i class="ph-bold ph-sparkle text-xs text-amber-350"></i>
                                    </div>
                                    <span>Goal Success</span>
                                </div>
                                @php $goalPercent = $totalGoals > 0 ? round(($achievedGoals / $totalGoals) * 100) : 0; @endphp
                                <span class="font-mono text-zinc-900 text-xs font-black">{{ $goalPercent }}%</span>
                            </div>
                            <!-- Progress Bar -->
                            <div class="w-full bg-zinc-100 h-1.2 rounded-full overflow-hidden">
                                <div class="bg-zinc-950 h-full rounded-full transition-all duration-500" style="width: {{ $goalPercent }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-3 mt-4 border-t border-zinc-150 flex items-center justify-between text-[9px] font-bold text-zinc-400">
                    <span>Total Goals</span>
                    <span class="font-mono text-zinc-900 bg-zinc-50 border border-zinc-200 px-2 py-0.2 rounded">
                        {{ number_format($achievedGoals) }} / {{ number_format($totalGoals) }}
                    </span>
                </div>
            </div>

            <!-- Signups Line Chart -->
            <div class="lg:col-span-2 bg-white rounded-xl border border-zinc-200 p-4 shadow-[0_4px_20px_rgba(0,0,0,0.008)] flex flex-col">
                <div class="flex items-center justify-between pb-2 border-b border-zinc-100 mb-3.5">
                    <h2 class="text-[10px] font-black text-zinc-800 tracking-tight uppercase">Active Growth (Last 10 Days)</h2>
                    <span class="text-[8px] font-mono font-bold text-zinc-500 bg-zinc-100 border border-zinc-200 px-2 py-0.5 rounded-full">Signups Tracker</span>
                </div>
                <div class="h-[140px] relative w-full mt-1">
                    <canvas id="growthChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Application Funnel Pipeline -->
        <div class="bg-white rounded-xl border border-zinc-200 p-4 shadow-[0_4px_20px_rgba(0,0,0,0.008)]">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-2 border-b border-zinc-100 mb-4 gap-2">
                <div>
                    <h2 class="text-[10px] font-black text-zinc-800 tracking-tight uppercase">Recruitment Funnel Pipeline</h2>
                    <p class="text-[9px] text-zinc-450 font-bold uppercase tracking-wider mt-0.5">Platform user application metrics & conversion rates</p>
                </div>
                <span class="text-[9px] font-mono font-bold text-zinc-650 bg-zinc-50 border border-zinc-200 px-2 py-0.5 rounded-full inline-flex items-center gap-1.5 shrink-0">
                    <span class="w-1.2 h-1.2 rounded-full bg-indigo-500"></span>
                    {{ $overallSuccessRate }}% Success Rate
                </span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 items-center">
                <!-- Connected Stages Visual Layout -->
                <div class="lg:col-span-3 grid grid-cols-1 md:grid-cols-3 gap-3">
                    <!-- Stage 1 -->
                    <div class="bg-zinc-50/50 border border-zinc-200 rounded-lg p-3.5 flex flex-col justify-between min-h-[85px] hover:border-zinc-350 transition duration-200">
                        <div>
                            <div class="flex items-center gap-1.5 text-zinc-400 mb-0.5">
                                <i class="ph-bold ph-paper-plane-tilt text-[10px]"></i>
                                <span class="text-[8px] font-mono font-bold uppercase tracking-wider">Stage 1</span>
                            </div>
                            <h4 class="text-[10px] font-bold text-zinc-700">Total Applied</h4>
                        </div>
                        <div class="flex items-baseline justify-between mt-2">
                            <span class="text-lg font-black text-zinc-900 font-mono leading-none">{{ number_format($totalApps) }}</span>
                            <span class="text-[8px] font-mono font-bold text-zinc-455 bg-white border border-zinc-200 px-1 py-0.2 rounded shadow-3xs">100% Base</span>
                        </div>
                    </div>

                    <!-- Stage 2 -->
                    <div class="bg-amber-50/70 border border-amber-200 rounded-lg p-3.5 flex flex-col justify-between min-h-[85px] hover:border-amber-300 transition duration-200">
                        <div>
                            <div class="flex items-center gap-1.5 text-amber-600 mb-0.5">
                                <i class="ph-bold ph-chats-circle text-[10px]"></i>
                                <span class="text-[8px] font-mono font-bold uppercase tracking-wider">Stage 2</span>
                            </div>
                            <h4 class="text-[10px] font-bold text-amber-900">Interview Stage</h4>
                        </div>
                        <div class="flex items-baseline justify-between mt-2">
                            <span class="text-lg font-black text-amber-900 font-mono leading-none">{{ number_format($interviewApps) }}</span>
                            <span class="text-[8px] font-mono font-bold text-amber-700 bg-white border border-amber-200 px-1 py-0.2 rounded shadow-3xs">{{ $interviewRate }}% Rate</span>
                        </div>
                    </div>

                    <!-- Stage 3 -->
                    <div class="bg-emerald-50/70 border border-emerald-250/70 rounded-lg p-3.5 flex flex-col justify-between min-h-[85px] hover:border-emerald-350 transition duration-200">
                        <div>
                            <div class="flex items-center gap-1.5 text-emerald-600 mb-0.5">
                                <i class="ph-bold ph-handshake text-[10px]"></i>
                                <span class="text-[8px] font-mono font-bold uppercase tracking-wider">Stage 3</span>
                            </div>
                            <h4 class="text-[10px] font-bold text-emerald-950">Got Offer</h4>
                        </div>
                        <div class="flex items-baseline justify-between mt-2">
                            <span class="text-lg font-black text-emerald-855 font-mono leading-none">{{ number_format($offeredApps) }}</span>
                            <span class="text-[8px] font-mono font-bold text-emerald-700 bg-white border border-emerald-200 px-1 py-0.2 rounded shadow-3xs">{{ $offerRate }}% Rate</span>
                        </div>
                    </div>
                </div>

                <!-- Doughnut Metric Ring -->
                <div class="bg-zinc-50/30 border border-zinc-200 rounded-lg p-3 flex flex-col items-center justify-center">
                    <div class="relative w-[80px] aspect-square mb-1.5">
                        <canvas id="funnelDoughnutChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <span class="text-sm font-black text-zinc-950 leading-none">{{ $overallSuccessRate }}%</span>
                            <span class="text-[6px] font-mono font-bold text-zinc-400 uppercase tracking-widest mt-0.5">Success</span>
                        </div>
                    </div>
                    <!-- Small Legend -->
                    <div class="flex items-center justify-center gap-2">
                        <div class="flex items-center gap-0.5">
                            <div class="w-1.5 h-1.5 rounded-full bg-zinc-200"></div>
                            <span class="text-[7px] font-mono font-bold text-zinc-450 uppercase">Apply</span>
                        </div>
                        <div class="flex items-center gap-0.5">
                            <div class="w-1.5 h-1.5 rounded-full bg-amber-450"></div>
                            <span class="text-[7px] font-mono font-bold text-zinc-450 uppercase">Intvw</span>
                        </div>
                        <div class="flex items-center gap-0.5">
                            <div class="w-1.5 h-1.5 rounded-full bg-indigo-500"></div>
                            <span class="text-[7px] font-mono font-bold text-zinc-450 uppercase">Offer</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3-Column Recent Activity Lists -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            <!-- Column 1: Recent Applications -->
            <div class="bg-white rounded-xl border border-zinc-200 flex flex-col h-[390px] shadow-[0_4px_20px_rgba(0,0,0,0.008)]">
                <div class="px-4 py-3 border-b border-zinc-150 flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-2">
                        <i class="ph-bold ph-clock-counter-clockwise text-zinc-450"></i>
                        <span class="text-[9px] font-mono font-bold text-zinc-500 uppercase tracking-widest">Recent Applications</span>
                    </div>
                    <span class="text-[8px] font-mono font-bold text-zinc-555 bg-zinc-50 border border-zinc-200 px-1.5 py-0.2 rounded">Live</span>
                </div>
                <div class="divide-y divide-zinc-100 flex-1 overflow-y-auto custom-scrollbar">
                    @forelse($recentApplications as $application)
                        <div class="p-3 hover:bg-zinc-50/50 transition flex items-start gap-2.5">
                            @if($application->user && $application->user->logo)
                                <div class="w-7 h-7 rounded-lg overflow-hidden border border-zinc-200 flex-shrink-0 shadow-3xs">
                                    <img src="{{ $application->user->avatar_url }}" 
                                         alt="Profile" 
                                         class="h-full w-full object-cover">
                                </div>
                            @else
                                <div class="w-7 h-7 rounded-lg overflow-hidden border border-zinc-200 flex-shrink-0 flex items-center justify-center text-white text-[10px] font-black shadow-3xs"
                                     style="{{ getAvatarStyle($application->user->name ?? 'A') }}">
                                    <span class="pointer-events-none">{{ strtoupper(substr($application->user->name ?? 'A', 0, 1)) }}</span>
                                </div>
                            @endif

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-0.5">
                                    <p class="text-[11px] font-bold text-zinc-900 truncate pr-2 leading-tight">
                                        {{ $application->user->name ?? 'Unknown User' }}
                                    </p>
                                    <p class="text-[8px] text-zinc-400 font-bold font-mono">
                                        {{ $application->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <p class="text-[10px] text-zinc-500 truncate mb-1.5 leading-tight">
                                    <span class="font-bold text-zinc-755">{{ $application->company_name }}</span> &bull; {{ $application->position }}
                                </p>
                                
                                <!-- Status Badges -->
                                @if($application->status === 'applied')
                                    <span class="inline-block px-2 py-0.5 text-[8px] font-bold rounded-full bg-zinc-100 text-zinc-700 border border-zinc-200/50">Applied</span>
                                @elseif($application->status === 'interview')
                                    <span class="inline-block px-2 py-0.5 text-[8px] font-bold rounded-full bg-yellow-50 text-yellow-800 border border-yellow-250/20">Interview</span>
                                @elseif($application->status === 'accepted')
                                    <span class="inline-block px-2 py-0.5 text-[8px] font-bold rounded-full bg-emerald-50 text-emerald-800 border border-emerald-250/20">Accepted</span>
                                @elseif($application->status === 'rejected')
                                    <span class="inline-block px-2 py-0.5 text-[8px] font-bold rounded-full bg-rose-50 text-rose-800 border border-rose-250/20">Rejected</span>
                                @else
                                    <span class="inline-block px-2 py-0.5 text-[8px] font-bold rounded-full bg-zinc-50 text-zinc-600 border border-zinc-200">{{ $application->status }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center flex flex-col items-center justify-center h-full">
                            <i class="ph ph-tray text-base text-zinc-300 mb-1.5"></i>
                            <p class="text-[9px] text-zinc-400 font-medium">No applications yet</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Column 2: Most Active Users -->
            <div class="bg-white rounded-xl border border-zinc-200 flex flex-col h-[390px] shadow-[0_4px_20px_rgba(0,0,0,0.008)]">
                <div class="px-4 py-3 border-b border-zinc-155 flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-2">
                        <i class="ph-bold ph-trophy text-zinc-450"></i>
                        <span class="text-[9px] font-mono font-bold text-zinc-500 uppercase tracking-widest">Most Active Users</span>
                    </div>
                    <span class="text-[8px] font-mono font-bold text-zinc-555 bg-zinc-50 border border-zinc-200 px-1.5 py-0.2 rounded">Top 15</span>
                </div>
                <div class="divide-y divide-zinc-100 flex-1 overflow-y-auto custom-scrollbar">
                    @forelse($topAppliedUsers as $index => $user)
                        <div class="flex items-center gap-2.5 p-3 hover:bg-zinc-50/50 transition">
                            <div class="w-5 h-5 rounded-md bg-zinc-50 border border-zinc-200 flex items-center justify-center font-mono font-black text-[9px] shrink-0 {{ $index === 0 ? 'bg-amber-50 border-amber-200 text-amber-800' : ($index === 1 ? 'bg-zinc-100 text-zinc-650' : ($index === 2 ? 'bg-orange-50 border-orange-200 text-orange-850' : 'text-zinc-400')) }}">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0 flex items-center gap-2">
                                @if($user->logo)
                                    <div class="w-7 h-7 rounded-full overflow-hidden border border-zinc-200 flex-shrink-0 shadow-3xs">
                                        <img src="{{ $user->avatar_url }}" 
                                             alt="Profile" 
                                             class="h-full w-full object-cover">
                                    </div>
                                @else
                                    <div class="w-7 h-7 rounded-full overflow-hidden border border-zinc-200 flex-shrink-0 flex items-center justify-center text-white text-[10px] font-black shadow-3xs"
                                         style="{{ getAvatarStyle($user->name) }}">
                                        <span class="pointer-events-none">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="text-[11px] font-bold text-zinc-900 truncate leading-tight">{{ $user->name }}</p>
                                    <p class="text-[9px] text-zinc-455 truncate mt-0.2 leading-none">{{ $user->email }}</p>
                                </div>
                            </div>
                            <span class="text-[8px] font-mono font-bold text-zinc-600 bg-zinc-50 border border-zinc-200 px-1.5 py-0.2 rounded-md shrink-0">
                                {{ number_format($user->job_applications_count) }} apps
                            </span>
                        </div>
                    @empty
                        <div class="py-12 text-center flex flex-col items-center justify-center h-full">
                            <i class="ph ph-tray text-base text-zinc-300 mb-1.5"></i>
                            <p class="text-[9px] text-zinc-400 font-medium">No active users yet</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Column 3: Help Needed -->
            <div class="bg-white rounded-xl border border-zinc-200 flex flex-col h-[390px] shadow-[0_4px_20px_rgba(0,0,0,0.008)]">
                <div class="px-4 py-3 border-b border-zinc-150 flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-2">
                        <i class="ph-bold ph-warning-circle text-zinc-450"></i>
                        <span class="text-[9px] font-mono font-bold text-zinc-550 uppercase tracking-widest">Help Needed</span>
                    </div>
                    <span class="text-[8px] font-mono font-bold text-rose-700 bg-rose-50 border border-rose-200 px-1.5 py-0.2 rounded">Failed</span>
                </div>
                <div class="divide-y divide-zinc-100 flex-1 overflow-y-auto custom-scrollbar">
                    @forelse($topDeclinedUsers as $index => $user)
                        <div class="flex items-center gap-2.5 p-3 hover:bg-zinc-50/50 transition">
                            <div class="w-5 h-5 rounded-md bg-zinc-50 border border-zinc-200 flex items-center justify-center font-mono font-black text-[9px] shrink-0 {{ $index === 0 ? 'bg-rose-50 border-rose-200 text-rose-800' : 'text-zinc-400' }}">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0 flex items-center gap-2">
                                @if($user->logo)
                                    <div class="w-7 h-7 rounded-full overflow-hidden border border-zinc-200 flex-shrink-0 shadow-3xs">
                                        <img src="{{ $user->avatar_url }}" 
                                             alt="Profile" 
                                             class="h-full w-full object-cover">
                                    </div>
                                @else
                                    <div class="w-7 h-7 rounded-full overflow-hidden border border-zinc-200 flex-shrink-0 flex items-center justify-center text-white text-[10px] font-black shadow-3xs"
                                         style="{{ getAvatarStyle($user->name) }}">
                                        <span class="pointer-events-none">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="text-[11px] font-bold text-zinc-900 truncate leading-tight">{{ $user->name }}</p>
                                    <p class="text-[9px] text-zinc-455 truncate mt-0.2 leading-none">{{ $user->email }}</p>
                                </div>
                            </div>
                            <span class="text-[8px] font-mono font-bold text-rose-800 bg-rose-50 border border-rose-100 px-1.5 py-0.2 rounded-md shrink-0">
                                {{ number_format($user->declined_not_processed_count) }} fail
                            </span>
                        </div>
                    @empty
                        <div class="py-12 text-center flex flex-col items-center justify-center h-full">
                            <i class="ph ph-tray text-base text-zinc-300 mb-1.5"></i>
                            <p class="text-[9px] text-zinc-400 font-medium">All applications processed</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    <!-- Chart.js and Custom Clean Light Theme Initialization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Growth Chart (Light Premium Theme: thin lines, monospace ticks, linear gradient fill)
            const ctx = document.getElementById('growthChart')?.getContext('2d');
            if (ctx) {
                const gradient = ctx.createLinearGradient(0, 0, 0, 140);
                gradient.addColorStop(0, 'rgba(99, 102, 241, 0.10)');
                gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($chartLabels) !!},
                        datasets: [{
                            label: 'Signups',
                            data: {!! json_encode($chartData) !!},
                            borderColor: '#6366f1', // Indigo line
                            borderWidth: 2,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#6366f1',
                            pointBorderWidth: 2,
                            pointRadius: 3,
                            pointHoverRadius: 5,
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
                                ticks: { font: { family: "monospace", size: 8, weight: 'bold' }, color: '#71717a' }
                            },
                            y: {
                                grid: { color: 'rgba(228, 228, 231, 0.45)', drawBorder: false, borderDash: [4, 4] },
                                ticks: { font: { family: "monospace", size: 8, weight: 'bold' }, color: '#71717a', stepSize: 5 },
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Funnel Doughnut Chart
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