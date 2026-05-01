<x-admin-layout>
    <div class="space-y-6 sm:space-y-8 pb-10">
        
        <!-- Welcome Header & Quick Actions -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Dashboard Overview</h1>
                <p class="text-sm font-medium text-slate-500 mt-1">Here's what's happening with TraKerja today.</p>
            </div>
            <div></div>
        </div>

        {{-- Bento Grid Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            {{-- Total Users Card --}}
            <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group hover:border-primary-100 transition-colors">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-primary-50 to-primary-100/50 rounded-full blur-2xl -z-10 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Users</p>
                        <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ \App\Models\User::where('role', '!=', 'admin')->count() }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center text-primary-600 shadow-inner">
                        <i class="ph-fill ph-users-three text-xl"></i>
                    </div>
                </div>
            </div>

            {{-- Verified Users Card --}}
            <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group hover:border-emerald-100 transition-colors">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-emerald-50 to-emerald-100/50 rounded-full blur-2xl -z-10 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Verified Users</p>
                        <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ \App\Models\User::where('role', '!=', 'admin')->whereNotNull('email_verified_at')->count() }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-inner">
                        <i class="ph-fill ph-check-circle text-xl"></i>
                    </div>
                </div>
            </div>

            {{-- Active Users Card --}}
            <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group hover:border-blue-100 transition-colors">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-blue-50 to-blue-100/50 rounded-full blur-2xl -z-10 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Active Profiles</p>
                        @php
                            $activeUsers = \App\Models\User::where('role', '!=', 'admin')
                                ->where(function($query) {
                                    $query->whereHas('experiences')
                                        ->orWhereHas('educations')
                                        ->orWhereHas('skills');
                                })
                                ->count();
                        @endphp
                        <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $activeUsers }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shadow-inner">
                        <i class="ph-fill ph-user-focus text-xl"></i>
                    </div>
                </div>
            </div>

            {{-- Job Applications Card --}}
            <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group hover:border-amber-100 transition-colors">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-amber-50 to-amber-100/50 rounded-full blur-2xl -z-10 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Applications</p>
                        <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ \App\Models\JobApplication::count() }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 shadow-inner">
                        <i class="ph-fill ph-briefcase text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- NEW: Platform Metrics (Full Width Row with Chart) --}}
        <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden group">
            <div class="p-6 sm:p-8 flex flex-col lg:flex-row gap-8 sm:gap-12">
                <!-- Left Side: Stats -->
                <div class="lg:w-1/2 space-y-8">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary-50 rounded-2xl flex items-center justify-center text-primary-600 shadow-inner">
                            <i class="ph-duotone ph-chart-line-up text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-900 tracking-tight">Platform Growth</h3>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Real-time statistics</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        @php
                            $totalGoals = \App\Models\UserGoal::count();
                            $achievedGoals = \App\Models\UserGoal::where('is_achieved', true)->count();
                            $totalExports = \App\Models\User::sum('cv_exports_this_month') ?? 0;
                            $newUsersToday = \App\Models\User::where('role', '!=', 'admin')->whereDate('created_at', \Carbon\Carbon::today('Asia/Jakarta'))->count();
                            
                            // Real data for Chart: New Signups over the last 7 days
                            $chartLabels = [];
                            $chartData = [];
                            for ($i = 9; $i >= 0; $i--) {
                                $date = \Carbon\Carbon::today('Asia/Jakarta')->subDays($i);
                                $chartLabels[] = $date->format('D');
                                $chartData[] = \App\Models\User::where('role', '!=', 'admin')
                                    ->whereDate('created_at', $date)
                                    ->count();
                            }
                        @endphp

                        <div class="p-5 rounded-3xl bg-slate-50/50 border border-slate-100 hover:border-primary-200 hover:bg-white hover:shadow-xl transition-all group/stat">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-white shadow-sm flex items-center justify-center text-primary-500 group-hover/stat:scale-110 transition-transform">
                                    <i class="ph-fill ph-user-plus text-2xl"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Today</p>
                                    <p class="text-2xl font-black text-slate-900 tracking-tight">{{ number_format($newUsersToday) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-5 rounded-3xl bg-slate-50/50 border border-slate-100 hover:border-emerald-200 hover:bg-white hover:shadow-xl transition-all group/stat">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-white shadow-sm flex items-center justify-center text-emerald-500 group-hover/stat:scale-110 transition-transform">
                                    <i class="ph-fill ph-file-pdf text-2xl"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Exports</p>
                                    <p class="text-2xl font-black text-slate-900 tracking-tight">{{ number_format($totalExports) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="sm:col-span-2 p-5 rounded-3xl bg-gradient-to-br from-slate-900 to-slate-800 text-white hover:shadow-2xl hover:shadow-slate-500/20 transition-all group/premium overflow-hidden relative">
                            <div class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-full blur-3xl -mr-16 -mt-16"></div>
                            <div class="flex items-center justify-between relative z-10">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-amber-400">
                                        <i class="ph-fill ph-shooting-star text-2xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Success Rate</p>
                                        <p class="text-2xl font-black tracking-tight">{{ $totalGoals > 0 ? round(($achievedGoals / $totalGoals) * 100) : 0 }}% <span class="text-xs font-bold text-slate-400 ml-1">Goals Hit</span></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5 text-right">Achieved</p>
                                    <p class="text-xl font-black text-emerald-400">{{ number_format($achievedGoals) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Side: Chart -->
                <div class="flex-1 min-h-[300px] lg:min-h-0 flex flex-col">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest">Signups Last 10 Days</h4>
                        <div class="flex gap-2">
                            <span class="w-3 h-3 rounded-full bg-primary-500"></span>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">User Growth</span>
                        </div>
                    </div>
                    <div class="flex-1 relative">
                        <canvas id="growthChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3-Column Grid for Lists --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Column 1: Recent Job Applications --}}
            <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden flex flex-col">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-2">
                        <i class="ph-fill ph-clock-counter-clockwise text-indigo-500 text-lg"></i>
                        <h3 class="text-sm font-extrabold text-slate-900">Recent Applications</h3>
                    </div>
                </div>
                <div class="divide-y divide-slate-100/80 flex-1 overflow-y-auto max-h-[600px]">
                    @php
                        $recentApplications = \App\Models\JobApplication::with('user')->latest()->get();
                    @endphp
                    @forelse($recentApplications as $application)
                        <div class="p-4 hover:bg-slate-50/50 transition-colors flex items-start gap-3">
                            @if($application->user && $application->user->logo)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($application->user->logo) }}" class="w-8 h-8 rounded-full object-cover border border-slate-200 shadow-sm flex-shrink-0" alt="User">
                            @else
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-slate-100 to-slate-200 border border-slate-200 flex items-center justify-center text-slate-500 font-bold shadow-sm flex-shrink-0 text-xs">
                                    {{ substr($application->user->name ?? 'A', 0, 1) }}
                                </div>
                            @endif
                            
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-0.5">
                                    <p class="text-sm font-bold text-slate-900 truncate pr-2">{{ $application->user->name ?? 'Unknown User' }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 whitespace-nowrap">{{ $application->created_at->diffForHumans() }}</p>
                                </div>
                                <p class="text-[11px] font-medium text-slate-600 truncate mb-1">
                                    <span class="font-bold text-slate-700">{{ $application->company_name }}</span> &bull; {{ $application->position }}
                                </p>
                                @if($application->status === 'applied')
                                    <span class="inline-block px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider rounded bg-blue-50 text-blue-600 border border-blue-100">Applied</span>
                                @elseif($application->status === 'interview')
                                    <span class="inline-block px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider rounded bg-amber-50 text-amber-600 border border-amber-100">Interview</span>
                                @elseif($application->status === 'accepted')
                                    <span class="inline-block px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider rounded bg-emerald-50 text-emerald-600 border border-emerald-100">Accepted</span>
                                @elseif($application->status === 'rejected')
                                    <span class="inline-block px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider rounded bg-red-50 text-red-600 border border-red-100">Rejected</span>
                                @else
                                    <span class="inline-block px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider rounded bg-slate-100 text-slate-600 border border-slate-200">{{ $application->status }}</span>
                                @endif

                                @if($application->notes)
                                    <div class="mt-3 p-3 bg-slate-50 rounded-xl border border-slate-100 overflow-hidden max-h-32 text-[10px]">
                                        {!! format_cv_text($application->notes) !!}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center">
                            <div class="inline-flex w-10 h-10 rounded-full bg-slate-50 items-center justify-center mb-2">
                                <i class="ph ph-empty text-xl text-slate-400"></i>
                            </div>
                            <p class="text-xs font-semibold text-slate-600">No applications yet</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Column 2: Top 5 Most Applied Users --}}
            <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden flex flex-col">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2">
                    <i class="ph-fill ph-trophy text-blue-500 text-lg"></i>
                    <h3 class="text-sm font-extrabold text-slate-900">Top 20 Most Applied</h3>
                </div>
                <div class="p-2 space-y-1 flex-1 overflow-y-auto max-h-[600px]">
                    @php
                        $topAppliedUsers = \App\Models\User::where('role', '!=', 'admin')
                            ->withCount('jobApplications')
                            ->orderBy('job_applications_count', 'desc')
                            ->take(20)
                            ->get();
                    @endphp
                    @forelse($topAppliedUsers as $index => $user)
                        <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition-colors">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center font-extrabold text-xs {{ $index === 0 ? 'bg-amber-100 text-amber-600' : ($index === 1 ? 'bg-slate-200 text-slate-600' : ($index === 2 ? 'bg-orange-100 text-orange-700' : 'bg-slate-50 text-slate-400')) }}">
                                #{{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-slate-900 truncate">{{ $user->name }}</p>
                                <p class="text-[10px] font-medium text-slate-500 truncate">{{ $user->email }}</p>
                            </div>
                            <div class="flex items-center gap-1 px-2.5 py-1 bg-blue-50 rounded-lg border border-blue-100">
                                <i class="ph-fill ph-briefcase text-blue-500 text-xs"></i>
                                <span class="text-xs font-extrabold text-blue-700">{{ number_format($user->job_applications_count) }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-xs font-medium text-slate-500">No data available</div>
                    @endforelse
                </div>
            </div>

            {{-- Column 3: Top 5 Most Declined Users --}}
            <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden flex flex-col">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2">
                    <i class="ph-fill ph-warning-circle text-red-500 text-lg"></i>
                    <h3 class="text-sm font-extrabold text-slate-900">Top 20 Declined</h3>
                </div>
                <div class="p-2 space-y-1 flex-1 overflow-y-auto max-h-[600px]">
                    @php
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
                            ->take(20)
                            ->get();
                    @endphp
                    @forelse($topDeclinedUsers as $index => $user)
                        <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition-colors">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center font-extrabold text-xs {{ $index === 0 ? 'bg-red-100 text-red-600' : 'bg-slate-50 text-slate-400' }}">
                                #{{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-slate-900 truncate">{{ $user->name }}</p>
                                <p class="text-[10px] font-medium text-slate-500 truncate">{{ $user->email }}</p>
                            </div>
                            <div class="flex items-center gap-1 px-2.5 py-1 bg-red-50 rounded-lg border border-red-100">
                                <i class="ph-fill ph-x-circle text-red-500 text-xs"></i>
                                <span class="text-xs font-extrabold text-red-700">{{ number_format($user->declined_not_processed_count) }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-xs font-medium text-slate-500">No data available</div>
                    @endforelse
                </div>
            </div>
            
        </div>
        
    </div>

    <!-- Chart.js and Initialization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('growthChart').getContext('2d');
            
            // Premium gradient for the line chart
            let gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(99, 102, 241, 0.5)'); // primary-500
            gradient.addColorStop(1, 'rgba(99, 102, 241, 0.0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'New Signups',
                        data: {!! json_encode($chartData) !!},
                        borderColor: '#6366f1', // primary-500
                        backgroundColor: gradient,
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#6366f1',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleFont: { family: "'Plus Jakarta Sans', sans-serif", size: 13 },
                            bodyFont: { family: "'Plus Jakarta Sans', sans-serif", size: 14, weight: 'bold' },
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: false
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: "'Plus Jakarta Sans', sans-serif" }, color: '#94a3b8' }
                        },
                        y: {
                            grid: {
                                color: '#f1f5f9',
                                drawBorder: false,
                            },
                            ticks: { 
                                font: { family: "'Plus Jakarta Sans', sans-serif" }, 
                                color: '#94a3b8',
                                stepSize: 10
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</x-admin-layout>
