<x-admin-layout>
    <style>
        .mesh-gradient-primary {
            background-color: #ffffff;
            background-image:
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.03) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(79, 70, 229, 0.03) 0px, transparent 50%);
        }
        .mesh-gradient-emerald {
            background-color: #ffffff;
            background-image:
                radial-gradient(at 0% 0%, rgba(16, 185, 129, 0.03) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(5, 150, 105, 0.03) 0px, transparent 50%);
        }
        .mesh-gradient-blue {
            background-color: #ffffff;
            background-image:
                radial-gradient(at 0% 0%, rgba(59, 130, 246, 0.03) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(37, 99, 235, 0.03) 0px, transparent 50%);
        }
        .mesh-gradient-amber {
            background-color: #ffffff;
            background-image:
                radial-gradient(at 0% 0%, rgba(245, 158, 11, 0.03) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(217, 119, 6, 0.03) 0px, transparent 50%);
        }
        .bento-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.8), 0 10px 20px -5px rgba(0, 0, 0, 0.03);
        }
        .bento-card:hover {
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.8), 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        }
        .bento-card-stat {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.8), 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        }
        .bento-card-stat:hover {
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.8), 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #e2e8f0;
            border-radius: 10px;
        }
    </style>

    <div class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-6 space-y-6 sm:space-y-8 pb-10">
        
        <!-- Welcome Header & Quick Actions -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-6 sm:mb-8">
            <div class="flex items-center gap-3 sm:gap-4 min-w-0 w-full md:w-auto">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white border border-slate-200/60 rounded-[1.25rem] sm:rounded-[1.5rem] flex items-center justify-center text-primary-600 shadow-sm shrink-0">
                    <i class="ph-duotone ph-rocket-launch text-xl sm:text-2xl"></i>
                </div>
                <div class="flex flex-col min-w-0">
                    <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight truncate">Admin Dashboard</h3>
                    <p class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mt-1 sm:mt-1.5 truncate">Overview & Insights</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="flex items-center gap-2 w-full md:w-auto overflow-x-auto pb-1 md:pb-0 custom-scrollbar shrink-0">
                <a href="{{ route('admin.email-blast') }}" class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 sm:py-2.5 bg-white border border-slate-200/60 hover:border-primary-300 hover:shadow-md text-slate-700 hover:text-primary-600 font-bold text-[10px] sm:text-xs rounded-xl sm:rounded-2xl transition-all whitespace-nowrap">
                    <i class="ph-bold ph-paper-plane-tilt"></i> Email Blast
                </a>
                <a href="{{ route('admin.users') }}" class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 sm:py-2.5 bg-white border border-slate-200/60 hover:border-emerald-300 hover:shadow-md text-slate-700 hover:text-emerald-600 font-bold text-[10px] sm:text-xs rounded-xl sm:rounded-2xl transition-all whitespace-nowrap">
                    <i class="ph-bold ph-users"></i> Users
                </a>
                <a href="{{ route('admin.payments') }}" class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 sm:py-2.5 bg-white border border-slate-200/60 hover:border-amber-300 hover:shadow-md text-slate-700 hover:text-amber-600 font-bold text-[10px] sm:text-xs rounded-xl sm:rounded-2xl transition-all whitespace-nowrap">
                    <i class="ph-bold ph-credit-card"></i> Payments
                </a>
            </div>
        </div>

        {{-- Bento Grid Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            {{-- Total Users Card --}}
            <div class="bento-card-stat mesh-gradient-primary rounded-[2rem] border border-slate-100 p-5 flex flex-col group relative overflow-hidden">
                <div class="h-10 flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-primary-50 rounded-xl flex items-center justify-center text-primary-600 transition-transform">
                        <i class="ph-fill ph-users-three text-xl"></i>
                    </div>
                    <span class="text-[9px] font-black text-primary-600 uppercase tracking-[1.5px] bg-primary-50/50 px-2 py-0.5 rounded-full shrink-0">Community</span>
                </div>
                <div class="flex flex-col">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1 leading-none">Total Users</p>
                    <p class="text-2xl font-black text-slate-900 tracking-tighter leading-none">{{ \App\Models\User::where('role', '!=', 'admin')->count() }}</p>
                </div>
            </div>

            {{-- Verified Users Card --}}
            <div class="bento-card-stat mesh-gradient-emerald rounded-[2rem] border border-slate-100 p-5 flex flex-col group relative overflow-hidden">
                <div class="h-10 flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 transition-transform">
                        <i class="ph-fill ph-check-circle text-xl"></i>
                    </div>
                    <span class="text-[9px] font-black text-emerald-600 uppercase tracking-[1.5px] bg-emerald-50/50 px-2 py-0.5 rounded-full shrink-0">Trusted</span>
                </div>
                <div class="flex flex-col">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1 leading-none">Verified Users</p>
                    <p class="text-2xl font-black text-slate-900 tracking-tighter leading-none">{{ \App\Models\User::where('role', '!=', 'admin')->whereNotNull('email_verified_at')->count() }}</p>
                </div>
            </div>

            {{-- Active Users Card --}}
            <div class="bento-card-stat mesh-gradient-blue rounded-[2rem] border border-slate-100 p-5 flex flex-col group relative overflow-hidden">
                <div class="h-10 flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 transition-transform">
                        <i class="ph-fill ph-user-focus text-xl"></i>
                    </div>
                    <span class="text-[9px] font-black text-blue-600 uppercase tracking-[1.5px] bg-blue-50/50 px-2 py-0.5 rounded-full shrink-0">Engaged</span>
                </div>
                <div class="flex flex-col">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1 leading-none">Active Profiles</p>
                    @php
                        $activeUsers = \App\Models\User::where('role', '!=', 'admin')
                            ->where(function($query) {
                                $query->whereHas('experiences')
                                    ->orWhereHas('educations')
                                    ->orWhereHas('skills');
                            })
                            ->count();
                    @endphp
                    <p class="text-2xl font-black text-slate-900 tracking-tighter leading-none">{{ $activeUsers }}</p>
                </div>
            </div>

            {{-- Job Applications Card --}}
            <div class="bento-card-stat mesh-gradient-amber rounded-[2rem] border border-slate-100 p-5 flex flex-col group relative overflow-hidden">
                <div class="h-10 flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 transition-transform">
                        <i class="ph-fill ph-briefcase text-xl"></i>
                    </div>
                    <span class="text-[9px] font-black text-amber-600 uppercase tracking-[1.5px] bg-amber-50/50 px-2 py-0.5 rounded-full shrink-0">Activity</span>
                </div>
                <div class="flex flex-col">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1 leading-none">Applications</p>
                    <p class="text-2xl font-black text-slate-900 tracking-tighter leading-none">{{ \App\Models\JobApplication::count() }}</p>
                </div>
            </div>
        </div>

        {{-- Platform Metrics (Full Width Row with Chart) --}}
        <div class="bg-white rounded-[2rem] sm:rounded-[2.5rem] border border-slate-200/60 bento-card overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-primary-50 rounded-xl sm:rounded-2xl flex items-center justify-center text-primary-600 shadow-inner shrink-0">
                        <i class="ph-duotone ph-chart-line-up text-xl sm:text-2xl"></i>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight truncate">Platform Growth</h3>
                        <p class="text-[9px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-0.5 sm:mt-1 truncate">Real-time statistics</p>
                    </div>
                </div>
            </div>
            
            <div class="p-4 sm:p-6 lg:p-8 flex flex-col lg:flex-row gap-6 sm:gap-8 lg:gap-12">
                <!-- Left Side: Stats -->
                <div class="lg:w-1/2 space-y-4 sm:space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @php
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
                        @endphp

                        <div class="p-4 rounded-[1.5rem] bg-white border border-slate-200/60 shadow-sm hover:border-primary-200 hover:shadow-md transition-all group/stat flex items-center gap-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-primary-50 flex items-center justify-center text-primary-600 group-hover/stat:scale-110 transition-transform">
                                <i class="ph-fill ph-user-plus text-xl sm:text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5 leading-none">Today Signups</p>
                                <p class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight leading-none">{{ number_format($newUsersToday) }}</p>
                            </div>
                        </div>

                        <div class="p-4 rounded-[1.5rem] bg-white border border-slate-200/60 shadow-sm hover:border-amber-200 hover:shadow-md transition-all group/stat flex items-center gap-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-amber-50 flex items-center justify-center text-amber-500 group-hover/stat:scale-110 transition-transform">
                                <i class="ph-fill ph-crown text-xl sm:text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5 leading-none">Pro Users</p>
                                <p class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight leading-none">{{ number_format($proUsers) }}</p>
                            </div>
                        </div>

                        <div class="sm:col-span-2 p-5 rounded-[1.5rem] bg-slate-900 text-white shadow-md group/premium overflow-hidden relative border border-slate-800">
                            <div class="absolute right-0 top-0 w-48 h-48 bg-primary-500/20 rounded-full blur-3xl -mr-24 -mt-24 pointer-events-none"></div>
                            <div class="absolute left-0 bottom-0 w-48 h-48 bg-purple-500/20 rounded-full blur-3xl -ml-24 -mb-24 pointer-events-none"></div>
                            <div class="flex items-center justify-between relative z-10">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl sm:rounded-2xl bg-white/10 flex items-center justify-center text-amber-400 border border-white/10 backdrop-blur-md">
                                        <i class="ph-fill ph-shooting-star text-2xl"></i>
                                    </div>
                                    <div class="flex flex-col justify-center">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 leading-none">Goal Success Rate</p>
                                        <div class="flex items-baseline gap-1.5">
                                            <p class="text-2xl sm:text-3xl font-black tracking-tight leading-none">{{ $totalGoals > 0 ? round(($achievedGoals / $totalGoals) * 100) : 0 }}%</p>
                                            <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase">Hits</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end justify-center">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 leading-none">Achieved</p>
                                    <p class="text-xl sm:text-2xl font-black text-emerald-400 leading-none">{{ number_format($achievedGoals) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Side: Chart -->
                <div class="flex-1 min-h-[250px] sm:min-h-[300px] lg:min-h-0 flex flex-col">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-[9px] font-black text-slate-900 uppercase tracking-widest">Signups Last 10 Days</h4>
                        <div class="flex gap-1.5 items-center">
                            <span class="w-2 h-2 rounded-full bg-primary-500 shadow-sm shadow-primary-500/50"></span>
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Growth</span>
                        </div>
                    </div>
                    <div class="flex-1 relative bg-slate-50/50 rounded-2xl sm:rounded-[1.5rem] p-3 sm:p-4 border border-slate-100">
                        <canvas id="growthChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Application Funnel Analysis --}}
        @php
            $totalApps = \App\Models\JobApplication::count();
            $interviewApps = \App\Models\JobApplication::whereIn('status', ['interview', 'accepted', 'rejected'])->where('recruitment_stage', '!=', 'Not Processed')->count(); // Estimate passed initial screening
            $offeredApps = \App\Models\JobApplication::whereIn('status', ['accepted'])->orWhere('recruitment_stage', 'Offering')->count();
            
            $interviewRate = $totalApps > 0 ? round(($interviewApps / $totalApps) * 100) : 0;
            $offerRate = $interviewApps > 0 ? round(($offeredApps / $interviewApps) * 100) : 0;
            $overallSuccessRate = $totalApps > 0 ? round(($offeredApps / $totalApps) * 100) : 0;
        @endphp
        
        <div class="bg-white rounded-[2rem] border border-slate-200/60 bento-card overflow-hidden mb-6 sm:mb-8">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-50 rounded-xl sm:rounded-2xl flex items-center justify-center text-purple-600 shadow-inner shrink-0">
                        <i class="ph-duotone ph-funnel text-xl sm:text-2xl"></i>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight truncate">Application Funnel</h3>
                        <p class="text-[9px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-0.5 sm:mt-1">Tingkat Keberhasilan Pengguna Platform</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 px-3 py-1.5 bg-emerald-50 border border-emerald-100 rounded-xl">
                    <i class="ph-fill ph-check-circle text-emerald-500"></i>
                    <span class="text-[10px] font-black text-emerald-700 uppercase tracking-widest">{{ $overallSuccessRate }}% Overall Success</span>
                </div>
            </div>
            
            <div class="p-4 sm:p-6 lg:p-8">
                <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-12">
                    
                    <!-- Left: Funnel Stats -->
                    <div class="w-full lg:w-1/2 flex flex-col gap-4">
                        <!-- Step 1: Applied -->
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-100 group hover:border-slate-300 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-slate-500 group-hover:scale-110 transition-transform">
                                    <i class="ph-fill ph-paper-plane-tilt text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-slate-900 uppercase tracking-wider">Total Applied</h4>
                                    <p class="text-[10px] font-bold text-slate-400">Total lamaran yang dikirim</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-black text-slate-900">{{ number_format($totalApps) }}</p>
                                <p class="text-[10px] font-bold text-slate-400">100%</p>
                            </div>
                        </div>

                        <!-- Step 2: Interview -->
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-amber-50/50 border border-amber-100 group hover:border-amber-200 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-amber-500 group-hover:scale-110 transition-transform">
                                    <i class="ph-fill ph-chats-circle text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-slate-900 uppercase tracking-wider">Interview Stage</h4>
                                    <p class="text-[10px] font-bold text-amber-600/70">{{ $interviewRate }}% Conversion Rate</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-black text-amber-600">{{ number_format($interviewApps) }}</p>
                                <p class="text-[10px] font-bold text-amber-500/70">Kandidat</p>
                            </div>
                        </div>

                        <!-- Step 3: Offered/Accepted -->
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-emerald-50/50 border border-emerald-100 group hover:border-emerald-200 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-emerald-500 group-hover:scale-110 transition-transform">
                                    <i class="ph-fill ph-handshake text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-slate-900 uppercase tracking-wider">Got Offer</h4>
                                    <p class="text-[10px] font-bold text-emerald-600/70">{{ $offerRate }}% from Interview</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-black text-emerald-600">{{ number_format($offeredApps) }}</p>
                                <p class="text-[10px] font-bold text-emerald-500/70">Diterima</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right: Doughnut Chart -->
                    <div class="w-full lg:w-1/2 flex flex-col items-center justify-center min-h-[250px] p-6 bg-slate-50/50 rounded-3xl border border-slate-100 relative">
                        <div class="absolute inset-0 bg-grid-slate-100/[0.04] bg-[length:16px_16px] rounded-3xl"></div>
                        
                        <div class="relative w-full max-w-[200px] aspect-square mb-6 mt-2">
                            <canvas id="funnelDoughnutChart"></canvas>
                            <!-- Center Text -->
                            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none mt-1">
                                <span class="text-3xl font-black text-slate-900 tracking-tighter">{{ $overallSuccessRate }}%</span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Success</span>
                            </div>
                        </div>

                        <!-- Custom HTML Legend -->
                        <div class="flex flex-wrap items-center justify-center gap-3 sm:gap-5 w-full relative z-10">
                            <div class="flex items-center gap-1.5">
                                <div class="w-2.5 h-2.5 rounded-full bg-slate-200"></div>
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Applied</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <div class="w-2.5 h-2.5 rounded-full bg-amber-400"></div>
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Interview</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <div class="w-2.5 h-2.5 rounded-full bg-emerald-400"></div>
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Offered</span>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        {{-- 3-Column Grid for Lists --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            
            {{-- Column 1: Recent Job Applications --}}
            <div class="bg-white rounded-[2rem] border border-slate-200/60 bento-card overflow-hidden flex flex-col h-[500px]">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 shadow-inner shrink-0">
                            <i class="ph-duotone ph-clock-counter-clockwise text-lg sm:text-xl"></i>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-sm sm:text-base font-black text-slate-900 tracking-tight truncate">Recent Applications</h3>
                            <p class="text-[8px] sm:text-[9px] font-bold text-slate-400 uppercase tracking-wider leading-none mt-1">Live updates</p>
                        </div>
                    </div>
                </div>
                <div class="divide-y divide-slate-100/80 flex-1 overflow-y-auto custom-scrollbar">
                    @php
                        $recentApplications = \App\Models\JobApplication::with('user')->latest()->get();
                    @endphp
                    @forelse($recentApplications as $application)
                        <div class="p-3 sm:p-4 hover:bg-slate-50/80 transition-colors flex items-start gap-3">
                            @if($application->user && $application->user->logo)
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full overflow-hidden border border-slate-200 flex-shrink-0">
                                    <img src="{{ $application->user->avatar_url }}" 
                                         alt="{{ $application->user->name }}" 
                                         class="w-full h-full object-cover"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="w-full h-full bg-gradient-to-br from-primary-500 to-secondary-500 flex items-center justify-center" style="display: none;">
                                        <span class="text-white font-semibold text-xs sm:text-sm">{{ strtoupper(substr($application->user->name, 0, 1)) }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-white font-semibold text-xs sm:text-sm">{{ strtoupper(substr($application->user->name ?? 'A', 0, 1)) }}</span>
                                </div>
                            @endif
                            
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-0.5">
                                    <p class="text-xs sm:text-sm font-bold text-slate-900 truncate pr-2">{{ $application->user->name ?? 'Unknown User' }}</p>
                                    <p class="text-[8px] sm:text-[9px] font-bold text-slate-400 whitespace-nowrap uppercase tracking-wider">{{ $application->created_at->diffForHumans() }}</p>
                                </div>
                                <p class="text-[10px] sm:text-[11px] font-medium text-slate-500 truncate mb-1.5">
                                    <span class="font-bold text-slate-700">{{ $application->company_name }}</span> &bull; {{ $application->position }}
                                </p>
                                @if($application->status === 'applied')
                                    <span class="inline-block px-1.5 py-0.5 text-[8px] sm:text-[9px] font-black uppercase tracking-[0.1em] rounded bg-blue-50 text-blue-600 border border-blue-100">Applied</span>
                                @elseif($application->status === 'interview')
                                    <span class="inline-block px-1.5 py-0.5 text-[8px] sm:text-[9px] font-black uppercase tracking-[0.1em] rounded bg-amber-50 text-amber-600 border border-amber-100">Interview</span>
                                @elseif($application->status === 'accepted')
                                    <span class="inline-block px-1.5 py-0.5 text-[8px] sm:text-[9px] font-black uppercase tracking-[0.1em] rounded bg-emerald-50 text-emerald-600 border border-emerald-100">Accepted</span>
                                @elseif($application->status === 'rejected')
                                    <span class="inline-block px-1.5 py-0.5 text-[8px] sm:text-[9px] font-black uppercase tracking-[0.1em] rounded bg-rose-50 text-rose-600 border border-rose-100">Rejected</span>
                                @else
                                    <span class="inline-block px-1.5 py-0.5 text-[8px] sm:text-[9px] font-black uppercase tracking-[0.1em] rounded bg-slate-100 text-slate-600 border border-slate-200">{{ $application->status }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center flex flex-col items-center justify-center h-full">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-slate-50 flex items-center justify-center mb-3 text-slate-400">
                                <i class="ph-fill ph-empty text-xl sm:text-2xl"></i>
                            </div>
                            <p class="text-[10px] sm:text-xs font-bold text-slate-500">No applications yet</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Column 2: Top 5 Most Applied Users --}}
            <div class="bg-white rounded-[2rem] border border-slate-200/60 bento-card overflow-hidden flex flex-col h-[500px]">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 shadow-inner shrink-0">
                            <i class="ph-duotone ph-trophy text-lg sm:text-xl"></i>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-sm sm:text-base font-black text-slate-900 tracking-tight truncate">Most Applied</h3>
                            <p class="text-[8px] sm:text-[9px] font-bold text-slate-400 uppercase tracking-wider leading-none mt-1">Top 20 Active Users</p>
                        </div>
                    </div>
                </div>
                <div class="p-2 sm:p-3 space-y-1 flex-1 overflow-y-auto custom-scrollbar">
                    @php
                        $topAppliedUsers = \App\Models\User::where('role', '!=', 'admin')
                            ->withCount('jobApplications')
                            ->orderBy('job_applications_count', 'desc')
                            ->take(20)
                            ->get();
                    @endphp
                    @forelse($topAppliedUsers as $index => $user)
                        <div class="flex items-center gap-2 sm:gap-3 p-2 sm:p-3 rounded-xl hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg flex items-center justify-center font-black text-[10px] sm:text-xs {{ $index === 0 ? 'bg-amber-100 text-amber-600 border border-amber-200 shadow-sm' : ($index === 1 ? 'bg-slate-200 text-slate-600 border border-slate-300 shadow-sm' : ($index === 2 ? 'bg-orange-100 text-orange-700 border border-orange-200 shadow-sm' : 'bg-slate-50 text-slate-400')) }}">
                                #{{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-sm font-bold text-slate-900 truncate">{{ $user->name }}</p>
                                <p class="text-[9px] sm:text-[10px] font-bold text-slate-400 truncate">{{ $user->email }}</p>
                            </div>
                            <div class="flex items-center gap-1.5 px-2 py-1 bg-blue-50 rounded-lg border border-blue-100 shrink-0">
                                <i class="ph-fill ph-briefcase text-blue-500 text-[10px] sm:text-xs"></i>
                                <span class="text-[10px] sm:text-xs font-black text-blue-700">{{ number_format($user->job_applications_count) }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center flex flex-col items-center justify-center h-full">
                            <p class="text-[10px] sm:text-xs font-bold text-slate-500">No data available</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Column 3: Top 5 Most Declined Users --}}
            <div class="bg-white rounded-[2rem] border border-slate-200/60 bento-card overflow-hidden flex flex-col h-[500px]">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-rose-50 rounded-xl flex items-center justify-center text-rose-600 shadow-inner shrink-0">
                            <i class="ph-duotone ph-warning-circle text-lg sm:text-xl"></i>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-sm sm:text-base font-black text-slate-900 tracking-tight truncate">Most Declined</h3>
                            <p class="text-[8px] sm:text-[9px] font-bold text-slate-400 uppercase tracking-wider leading-none mt-1">Top 20 Need Help</p>
                        </div>
                    </div>
                </div>
                <div class="p-2 sm:p-3 space-y-1 flex-1 overflow-y-auto custom-scrollbar">
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
                        <div class="flex items-center gap-2 sm:gap-3 p-2 sm:p-3 rounded-xl hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg flex items-center justify-center font-black text-[10px] sm:text-xs {{ $index === 0 ? 'bg-rose-100 text-rose-600 border border-rose-200 shadow-sm' : 'bg-slate-50 text-slate-400' }}">
                                #{{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-sm font-bold text-slate-900 truncate">{{ $user->name }}</p>
                                <p class="text-[9px] sm:text-[10px] font-bold text-slate-400 truncate">{{ $user->email }}</p>
                            </div>
                            <div class="flex items-center gap-1.5 px-2 py-1 bg-rose-50 rounded-lg border border-rose-100 shrink-0">
                                <i class="ph-fill ph-x-circle text-rose-500 text-[10px] sm:text-xs"></i>
                                <span class="text-[10px] sm:text-xs font-black text-rose-700">{{ number_format($user->declined_not_processed_count) }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center flex flex-col items-center justify-center h-full">
                            <p class="text-[10px] sm:text-xs font-bold text-slate-500">No data available</p>
                        </div>
                    @endforelse
                </div>
            </div>
            
        </div>
        
    </div>

    <!-- Chart.js and Initialization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('growthChart')?.getContext('2d');
            if(!ctx) return;
            
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
                            titleFont: { family: "'Inter', 'Plus Jakarta Sans', sans-serif", size: 12 },
                            bodyFont: { family: "'Inter', 'Plus Jakarta Sans', sans-serif", size: 13, weight: 'bold' },
                            padding: 10,
                            cornerRadius: 8,
                            displayColors: false
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: "'Inter', 'Plus Jakarta Sans', sans-serif", size: 10 }, color: '#94a3b8' }
                        },
                        y: {
                            grid: {
                                color: '#f1f5f9',
                                drawBorder: false,
                            },
                            ticks: { 
                                font: { family: "'Inter', 'Plus Jakarta Sans', sans-serif", size: 10 }, 
                                color: '#94a3b8',
                                stepSize: 10
                            },
                            beginAtZero: true
                        }
                    }
                }
            });

            // Funnel Doughnut Chart
            const funnelCtx = document.getElementById('funnelDoughnutChart')?.getContext('2d');
            if(funnelCtx) {
                new Chart(funnelCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Applied (No Interview)', 'Interviewing', 'Offered'],
                        datasets: [{
                            data: [
                                {{ max(0, $totalApps - $interviewApps) }}, 
                                {{ max(0, $interviewApps - $offeredApps) }}, 
                                {{ $offeredApps }}
                            ],
                            backgroundColor: ['#e2e8f0', '#fbbf24', '#34d399'],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        cutout: '78%',
                        layout: {
                            padding: 0
                        },
                        plugins: {
                            legend: { 
                                display: false
                            },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                titleFont: { family: "'Inter', 'Plus Jakarta Sans', sans-serif", size: 11 },
                                bodyFont: { family: "'Inter', 'Plus Jakarta Sans', sans-serif", size: 12, weight: 'bold' },
                                padding: 10,
                                cornerRadius: 8,
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
