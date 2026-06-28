<x-admin-layout>
    @php
        $activeUsers = \App\Models\User::where('role', '!=', 'admin')
            ->where(function($query) {
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
            ->selectSub(function($query) {
                $query->selectRaw('COUNT(*)')
                    ->from('job_applications')
                    ->whereColumn('job_applications.user_id', 'users.id')
                    ->where(function($q) {
                        $q->where('application_status', 'Declined')
                          ->orWhere('recruitment_stage', 'Not Processed');
                    });
            }, 'declined_not_processed_count')
            ->havingRaw('declined_not_processed_count > 0')
            ->orderBy('declined_not_processed_count', 'desc')
            ->take(15)
            ->get();
    @endphp

    <div class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-5 space-y-5 pb-10">
        
        <!-- Sticky Global Sub-Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-zinc-200/80">
            <div class="flex items-center gap-2.5 min-w-0">
                <span class="text-xs font-mono font-medium text-zinc-400">Admin</span>
                <span class="text-zinc-300">/</span>
                <h1 class="text-sm font-semibold tracking-tight text-zinc-900">Overview</h1>
            </div>

            <!-- Quick Action Links -->
            <div class="flex flex-wrap items-center gap-1.5">
                <a href="{{ route('admin.email-blast') }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-white border border-zinc-200 hover:border-zinc-300 hover:bg-zinc-50 text-zinc-700 font-medium text-xs rounded-md transition-all">
                    <i class="ph-bold ph-paper-plane-tilt text-xs text-zinc-400"></i> Email Blast
                </a>
                <a href="{{ route('admin.users') }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-white border border-zinc-200 hover:border-zinc-300 hover:bg-zinc-50 text-zinc-700 font-medium text-xs rounded-md transition-all">
                    <i class="ph-bold ph-users text-xs text-zinc-400"></i> Users List
                </a>
                <a href="{{ route('admin.payments') }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-white border border-zinc-200 hover:border-zinc-300 hover:bg-zinc-50 text-zinc-700 font-medium text-xs rounded-md transition-all">
                    <i class="ph-bold ph-credit-card text-xs text-zinc-400"></i> Payments
                </a>
            </div>
        </div>

        {{-- 4-Column Flat Bento Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Total Users --}}
            <div class="bg-white rounded-lg border border-zinc-200/80 p-3.5 hover:bg-zinc-50/50 hover:border-zinc-350 transition-all flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Total Users</span>
                    <i class="ph-bold ph-users text-zinc-400 text-sm"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold tracking-tight text-zinc-900">{{ number_format(\App\Models\User::where('role', '!=', 'admin')->count()) }}</h3>
                    <p class="text-[10px] text-zinc-400 mt-0.5">Community base</p>
                </div>
            </div>

            {{-- Verified Users --}}
            <div class="bg-white rounded-lg border border-zinc-200/80 p-3.5 hover:bg-zinc-50/50 hover:border-zinc-350 transition-all flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Verified Users</span>
                    <i class="ph-bold ph-check-circle text-zinc-400 text-sm"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold tracking-tight text-zinc-900">{{ number_format(\App\Models\User::where('role', '!=', 'admin')->whereNotNull('email_verified_at')->count()) }}</h3>
                    <p class="text-[10px] text-zinc-400 mt-0.5">Trusted identity</p>
                </div>
            </div>

            {{-- Active Profiles --}}
            <div class="bg-white rounded-lg border border-zinc-200/80 p-3.5 hover:bg-zinc-50/50 hover:border-zinc-350 transition-all flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Active Profiles</span>
                    <i class="ph-bold ph-user-focus text-zinc-400 text-sm"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold tracking-tight text-zinc-900">{{ number_format($activeUsers) }}</h3>
                    <p class="text-[10px] text-zinc-400 mt-0.5">With CV components</p>
                </div>
            </div>

            {{-- Job Applications --}}
            <div class="bg-white rounded-lg border border-zinc-200/80 p-3.5 hover:bg-zinc-50/50 hover:border-zinc-350 transition-all flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Applications</span>
                    <i class="ph-bold ph-briefcase text-zinc-400 text-sm"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold tracking-tight text-zinc-900">{{ number_format($totalApps) }}</h3>
                    <p class="text-[10px] text-zinc-400 mt-0.5">Submitted status logs</p>
                </div>
            </div>
        </div>

        {{-- Growth Section (Chart + Mini Stats) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Left Side: Stats List -->
            <div class="bg-white rounded-lg border border-zinc-200/80 p-4 space-y-4">
                <div class="pb-3 border-b border-zinc-100">
                    <h2 class="text-xs font-mono font-bold text-zinc-400 uppercase tracking-wider">Growth Overview</h2>
                </div>

                <div class="space-y-3.5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-md bg-zinc-50 border border-zinc-100 flex items-center justify-center text-zinc-500">
                                <i class="ph-bold ph-user-plus text-xs"></i>
                            </div>
                            <span class="text-xs text-zinc-500">Today Signups</span>
                        </div>
                        <span class="text-xs font-semibold text-zinc-900">{{ number_format($newUsersToday) }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-md bg-zinc-50 border border-zinc-100 flex items-center justify-center text-zinc-500">
                                <i class="ph-bold ph-crown text-xs"></i>
                            </div>
                            <span class="text-xs text-zinc-500">Premium Pro Users</span>
                        </div>
                        <span class="text-xs font-semibold text-zinc-900">{{ number_format($proUsers) }}</span>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-zinc-100">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-md bg-zinc-900 flex items-center justify-center text-white">
                                <i class="ph-bold ph-shooting-star text-xs text-amber-400"></i>
                            </div>
                            <span class="text-xs font-medium text-zinc-700">Goal Success Rate</span>
                        </div>
                        <span class="text-xs font-semibold text-zinc-900">{{ $totalGoals > 0 ? round(($achievedGoals / $totalGoals) * 100) : 0 }}%</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-[10px] text-zinc-400 pl-8">Total Goals Achieved</span>
                        <span class="text-xs font-mono text-zinc-500">{{ number_format($achievedGoals) }} / {{ number_format($totalGoals) }}</span>
                    </div>
                </div>
            </div>

            <!-- Right Side: Chart Canvas -->
            <div class="lg:col-span-2 bg-white rounded-lg border border-zinc-200/80 p-4 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-xs font-mono font-bold text-zinc-400 uppercase tracking-wider">Signups - Last 10 Days</h2>
                    <span class="text-[10px] font-mono text-primary-600 bg-primary-50 px-1.5 py-0.5 rounded">Active Growth</span>
                </div>
                <div class="h-[180px] relative w-full">
                    <canvas id="growthChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Funnel Analysis Section --}}
        <div class="bg-white rounded-lg border border-zinc-200/80 p-4">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-4">
                <div>
                    <h2 class="text-xs font-mono font-bold text-zinc-400 uppercase tracking-wider">Application Funnel</h2>
                    <p class="text-[10px] text-zinc-500 mt-0.5">Platform user recruitment pipeline analysis</p>
                </div>
                <span class="text-xs font-mono font-bold text-emerald-700 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded-md">
                    {{ $overallSuccessRate }}% Overall Success Rate
                </span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-center">
                <!-- Funnel Stages -->
                <div class="space-y-2.5">
                    <!-- Stage 1 -->
                    <div class="flex items-center justify-between p-2.5 rounded-md bg-zinc-50 border border-zinc-200/60 hover:bg-zinc-100/50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-full bg-white border border-zinc-200 flex items-center justify-center text-zinc-500">
                                <i class="ph-bold ph-paper-plane-tilt text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-semibold text-zinc-900">Total Applied</h4>
                                <p class="text-[10px] text-zinc-400">Total job applications registered</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-semibold text-zinc-900">{{ number_format($totalApps) }}</span>
                            <p class="text-[9px] text-zinc-400">100%</p>
                        </div>
                    </div>

                    <!-- Stage 2 -->
                    <div class="flex items-center justify-between p-2.5 rounded-md bg-amber-50/50 border border-amber-200/60 hover:bg-amber-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-full bg-white border border-amber-200 flex items-center justify-center text-amber-500">
                                <i class="ph-bold ph-chats-circle text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-semibold text-amber-800">Interview Stage</h4>
                                <p class="text-[10px] text-amber-600/70">{{ $interviewRate }}% Screen Transition</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-semibold text-amber-700">{{ number_format($interviewApps) }}</span>
                            <p class="text-[9px] text-amber-500/70">Candidates</p>
                        </div>
                    </div>

                    <!-- Stage 3 -->
                    <div class="flex items-center justify-between p-2.5 rounded-md bg-emerald-50/50 border border-emerald-200/60 hover:bg-emerald-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-full bg-white border border-emerald-200 flex items-center justify-center text-emerald-500">
                                <i class="ph-bold ph-handshake text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-semibold text-emerald-800">Got Offer</h4>
                                <p class="text-[10px] text-emerald-600/70">{{ $offerRate }}% Transition from Interview</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-semibold text-emerald-700">{{ number_format($offeredApps) }}</span>
                            <p class="text-[9px] text-emerald-500/70">Accepted</p>
                        </div>
                    </div>
                </div>

                <!-- Doughnut Visualization -->
                <div class="bg-zinc-50/50 border border-zinc-200/60 rounded-lg p-4 flex flex-col items-center justify-center">
                    <div class="relative w-[130px] aspect-square mb-4">
                        <canvas id="funnelDoughnutChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <span class="text-xl font-bold text-zinc-900 leading-none">{{ $overallSuccessRate }}%</span>
                            <span class="text-[8px] font-mono text-zinc-400 uppercase tracking-wider mt-0.5">Success</span>
                        </div>
                    </div>

                    <!-- Legend -->
                    <div class="flex flex-wrap items-center justify-center gap-4">
                        <div class="flex items-center gap-1.5">
                            <div class="w-2 h-2 rounded-full bg-zinc-200"></div>
                            <span class="text-[9px] font-mono text-zinc-500 uppercase tracking-wider">Applied</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                            <span class="text-[9px] font-mono text-zinc-500 uppercase tracking-wider">Interview</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                            <span class="text-[9px] font-mono text-zinc-500 uppercase tracking-wider">Offered</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- High Information Density 3-Column List Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            
            {{-- Column 1: Recent Applications --}}
            <div class="bg-white rounded-lg border border-zinc-200/80 flex flex-col h-[460px]">
                <div class="px-4 py-3 border-b border-zinc-200/80 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="ph-bold ph-clock-counter-clockwise text-zinc-400 text-sm"></i>
                        <span class="text-xs font-mono font-bold text-zinc-400 uppercase tracking-wider">Recent Applications</span>
                    </div>
                    <span class="text-[9px] font-mono text-zinc-400">Live</span>
                </div>
                <div class="divide-y divide-zinc-100 flex-1 overflow-y-auto custom-scrollbar">
                    @forelse($recentApplications as $application)
                        <div class="p-3 hover:bg-zinc-50/50 transition-colors flex items-start gap-2.5">
                            @if($application->user && $application->user->logo)
                                <div class="w-7 h-7 rounded-full overflow-hidden border border-zinc-200 flex-shrink-0">
                                    <img src="{{ $application->user->avatar_url }}" 
                                         alt="{{ $application->user->name }}" 
                                         class="w-full h-full object-cover"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="w-full h-full bg-zinc-100 flex items-center justify-center" style="display: none;">
                                        <span class="text-zinc-600 font-semibold text-[10px]">{{ strtoupper(substr($application->user->name, 0, 1)) }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="w-7 h-7 bg-zinc-100 border border-zinc-250 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-zinc-600 font-semibold text-[10px]">{{ strtoupper(substr($application->user->name ?? 'A', 0, 1)) }}</span>
                                </div>
                            @endif
                            
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-0.5">
                                    <p class="text-xs font-semibold text-zinc-950 truncate pr-2">{{ $application->user->name ?? 'Unknown User' }}</p>
                                    <p class="text-[9px] text-zinc-400 whitespace-nowrap">{{ $application->created_at->diffForHumans() }}</p>
                                </div>
                                <p class="text-[10px] text-zinc-500 truncate mb-1">
                                    <span class="font-bold text-zinc-800">{{ $application->company_name }}</span> &bull; {{ $application->position }}
                                </p>
                                @if($application->status === 'applied')
                                    <span class="inline-block px-1 py-0.2 text-[8px] font-mono font-bold uppercase rounded bg-blue-50 text-blue-600 border border-blue-100">Applied</span>
                                @elseif($application->status === 'interview')
                                    <span class="inline-block px-1 py-0.2 text-[8px] font-mono font-bold uppercase rounded bg-amber-50 text-amber-600 border border-amber-100">Interview</span>
                                @elseif($application->status === 'accepted')
                                    <span class="inline-block px-1 py-0.2 text-[8px] font-mono font-bold uppercase rounded bg-emerald-50 text-emerald-600 border border-emerald-100">Accepted</span>
                                @elseif($application->status === 'rejected')
                                    <span class="inline-block px-1 py-0.2 text-[8px] font-mono font-bold uppercase rounded bg-rose-50 text-rose-600 border border-rose-100">Rejected</span>
                                @else
                                    <span class="inline-block px-1 py-0.2 text-[8px] font-mono font-bold uppercase rounded bg-zinc-100 text-zinc-600 border border-zinc-200">{{ $application->status }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center flex flex-col items-center justify-center h-full">
                            <i class="ph-bold ph-tray text-lg text-zinc-300 mb-2"></i>
                            <p class="text-[10px] text-zinc-400 font-medium">No applications yet</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Column 2: Most Applied Users --}}
            <div class="bg-white rounded-lg border border-zinc-200/80 flex flex-col h-[460px]">
                <div class="px-4 py-3 border-b border-zinc-200/80 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="ph-bold ph-trophy text-zinc-400 text-sm"></i>
                        <span class="text-xs font-mono font-bold text-zinc-400 uppercase tracking-wider">Most Active</span>
                    </div>
                    <span class="text-[9px] font-mono text-zinc-400 font-medium">Top 15</span>
                </div>
                <div class="divide-y divide-zinc-100 flex-1 overflow-y-auto custom-scrollbar">
                    @forelse($topAppliedUsers as $index => $user)
                        <div class="flex items-center gap-3 p-3 hover:bg-zinc-50/50 transition-colors">
                            <div class="w-6 h-6 rounded bg-zinc-100 flex items-center justify-center font-mono font-bold text-[10px] {{ $index === 0 ? 'bg-amber-100 text-amber-700' : ($index === 1 ? 'bg-zinc-200 text-zinc-700' : ($index === 2 ? 'bg-orange-100 text-orange-700' : 'text-zinc-500')) }}">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-zinc-950 truncate">{{ $user->name }}</p>
                                <p class="text-[10px] text-zinc-400 truncate">{{ $user->email }}</p>
                            </div>
                            <div class="flex items-center gap-1 px-1.5 py-0.5 bg-zinc-100 border border-zinc-200/80 rounded-md shrink-0">
                                <span class="text-[10px] font-mono font-bold text-zinc-700">{{ number_format($user->job_applications_count) }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center flex flex-col items-center justify-center h-full">
                            <i class="ph-bold ph-tray text-lg text-zinc-300 mb-2"></i>
                            <p class="text-[10px] text-zinc-400 font-medium">No active users</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Column 3: Users Needing Help --}}
            <div class="bg-white rounded-lg border border-zinc-200/80 flex flex-col h-[460px]">
                <div class="px-4 py-3 border-b border-zinc-200/80 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="ph-bold ph-warning-circle text-zinc-400 text-sm"></i>
                        <span class="text-xs font-mono font-bold text-zinc-400 uppercase tracking-wider">Help Needed</span>
                    </div>
                    <span class="text-[9px] font-mono text-zinc-400 font-medium">Declined</span>
                </div>
                <div class="divide-y divide-zinc-100 flex-1 overflow-y-auto custom-scrollbar">
                    @forelse($topDeclinedUsers as $index => $user)
                        <div class="flex items-center gap-3 p-3 hover:bg-zinc-50/50 transition-colors">
                            <div class="w-6 h-6 rounded bg-zinc-100 flex items-center justify-center font-mono font-bold text-[10px] {{ $index === 0 ? 'bg-rose-100 text-rose-700' : 'text-zinc-500' }}">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-zinc-950 truncate">{{ $user->name }}</p>
                                <p class="text-[10px] text-zinc-400 truncate">{{ $user->email }}</p>
                            </div>
                            <div class="flex items-center gap-1 px-1.5 py-0.5 bg-rose-50 border border-rose-100 rounded-md shrink-0">
                                <span class="text-[10px] font-mono font-bold text-rose-700">{{ number_format($user->declined_not_processed_count) }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center flex flex-col items-center justify-center h-full">
                            <i class="ph-bold ph-tray text-lg text-zinc-300 mb-2"></i>
                            <p class="text-[10px] text-zinc-400 font-medium">No users need help</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    <!-- Chart.js and Custom Minimalist Theme Initialization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Growth Chart
            const ctx = document.getElementById('growthChart')?.getContext('2d');
            if(ctx) {
                // Soft gradient for clean layout
                let gradient = ctx.createLinearGradient(0, 0, 0, 180);
                gradient.addColorStop(0, 'rgba(165, 112, 240, 0.15)'); // primary-500
                gradient.addColorStop(1, 'rgba(165, 112, 240, 0.00)');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($chartLabels) !!},
                        datasets: [{
                            label: 'Signups',
                            data: {!! json_encode($chartData) !!},
                            borderColor: '#a570f0', // primary-500
                            backgroundColor: gradient,
                            borderWidth: 1.5,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#a570f0',
                            pointBorderWidth: 1.5,
                            pointRadius: 3,
                            pointHoverRadius: 4,
                            fill: true,
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
                                ticks: { font: { family: "monospace", size: 9 }, color: '#a1a1aa' }
                            },
                            y: {
                                grid: { color: '#f4f4f5', drawBorder: false },
                                ticks: { font: { family: "monospace", size: 9 }, color: '#a1a1aa', stepSize: 5 },
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Funnel Doughnut Chart
            const funnelCtx = document.getElementById('funnelDoughnutChart')?.getContext('2d');
            if(funnelCtx) {
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
                        cutout: '80%',
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
                                    label: function(context) {
                                        let label = context.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed !== null) {
                                            label += context.parsed + ' Users';
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
