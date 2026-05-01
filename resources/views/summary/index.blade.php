<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-2xl font-black text-slate-900 leading-tight tracking-tight">
                Analytics <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#d983e4] via-purple-600 to-[#4e71c5]">Summary</span>
            </h1>
            <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest mt-1">Comprehensive insights into your journey</p>
        </div>
    </x-slot>

    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    {{-- Move Chart.js to top to ensure it is loaded --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="bg-[#f8fafc] min-h-screen pb-20">
        <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            
            {{-- Top Bar with responsive behavior --}}
            <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-6 sm:mb-8">
                <div class="flex items-center gap-3 sm:gap-4 min-w-0 w-full md:w-auto">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white border border-slate-200/60 rounded-xl sm:rounded-2xl flex items-center justify-center text-indigo-600 shadow-sm shrink-0">
                        <i class="ph-duotone ph-chart-line-up text-xl sm:text-2xl"></i>
                    </div>
                    <div class="flex flex-col min-w-0">
                        <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight truncate">Analytics Summary</h3>
                        <p class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mt-1 sm:mt-1.5 truncate">Overview & Insights</p>
                    </div>
                </div>

                <div class="flex w-full md:w-auto p-1.5 bg-white border border-slate-200/60 rounded-xl sm:rounded-2xl shadow-sm backdrop-blur-md shrink-0">
                    <button onclick="updateTimeFilter('weekly')" 
                        class="flex-1 md:flex-none px-2 sm:px-4 md:px-6 py-2 text-[10px] sm:text-xs font-black rounded-lg sm:rounded-xl transition-all duration-300 time-filter-btn {{ $timeFilter === 'weekly' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-slate-400 hover:text-indigo-600 hover:bg-slate-50' }}" 
                        data-filter="weekly">Weekly</button>
                    <button onclick="updateTimeFilter('monthly')" 
                        class="flex-1 md:flex-none px-2 sm:px-4 md:px-6 py-2 text-[10px] sm:text-xs font-black rounded-lg sm:rounded-xl transition-all duration-300 time-filter-btn {{ $timeFilter === 'monthly' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-slate-400 hover:text-indigo-600 hover:bg-slate-50' }}" 
                        data-filter="monthly">Monthly</button>
                    <button onclick="updateTimeFilter('all')" 
                        class="flex-1 md:flex-none px-2 sm:px-4 md:px-6 py-2 text-[10px] sm:text-xs font-black rounded-lg sm:rounded-xl transition-all duration-300 time-filter-btn {{ $timeFilter === 'all' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-slate-400 hover:text-indigo-600 hover:bg-slate-50' }}" 
                        data-filter="all">All Time</button>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-2 xl:grid-cols-4 gap-3 sm:gap-4 mb-6 sm:mb-8">
                <!-- Compact On Process -->
                <div class="bg-white border border-slate-200/60 rounded-xl sm:rounded-2xl p-3 sm:p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between hover:border-blue-600/50 transition-all group shadow-sm min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 min-w-0">
                        <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
                            <i class="ph-duotone ph-briefcase text-lg sm:text-2xl"></i>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1 sm:mb-1.5">On Process</span>
                            <span class="text-xl sm:text-2xl font-black text-slate-900 leading-none">{{ $onProcessCount }}</span>
                        </div>
                    </div>
                </div>

                <!-- Compact Accepted -->
                <div class="bg-white border border-slate-200/60 rounded-xl sm:rounded-2xl p-3 sm:p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between hover:border-emerald-600/50 transition-all group shadow-sm min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 min-w-0">
                        <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
                            <i class="ph-duotone ph-check-circle text-lg sm:text-2xl"></i>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1 sm:mb-1.5">Accepted</span>
                            <span class="text-xl sm:text-2xl font-black text-slate-900 leading-none">{{ $offeringAcceptedCount }}</span>
                        </div>
                    </div>
                </div>

                <!-- Compact Declined -->
                <div class="bg-white border border-slate-200/60 rounded-xl sm:rounded-2xl p-3 sm:p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between hover:border-rose-600/50 transition-all group shadow-sm min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 min-w-0">
                        <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-2xl bg-rose-50 flex items-center justify-center text-rose-600 shrink-0">
                            <i class="ph-duotone ph-x-circle text-lg sm:text-2xl"></i>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1 sm:mb-1.5">Declined</span>
                            <span class="text-xl sm:text-2xl font-black text-slate-900 leading-none">{{ $declinedCount }}</span>
                        </div>
                    </div>
                </div>

                <!-- Compact Total -->
                <div class="bg-white border border-slate-200/60 rounded-xl sm:rounded-2xl p-3 sm:p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between hover:border-purple-600/50 transition-all group shadow-sm min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 min-w-0">
                        <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-2xl bg-purple-50 flex items-center justify-center text-purple-600 shrink-0">
                            <i class="ph-duotone ph-paper-plane-tilt text-lg sm:text-2xl"></i>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1 sm:mb-1.5">Total Apps</span>
                            <span class="text-xl sm:text-2xl font-black text-slate-900 leading-none">{{ $timelineData['applications']->sum() ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Productivity Highlights --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mb-6 sm:mb-8">
                <div class="bg-white border border-slate-200/60 rounded-xl sm:rounded-2xl p-3 sm:p-4 flex items-center justify-between hover:border-orange-600/50 transition-all group shadow-sm">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-2xl bg-orange-50 flex items-center justify-center text-orange-600">
                            <i class="ph-bold ph-fire text-xl sm:text-2xl"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1 sm:mb-1.5">Daily Streak</span>
                            <div class="flex items-baseline gap-1.5 sm:gap-2">
                                <span class="text-xl sm:text-2xl font-black text-slate-900 leading-none">{{ $dailyStreak['current_streak'] }}</span>
                                <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase">/ Best {{ $dailyStreak['best_streak'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-slate-200/60 rounded-xl sm:rounded-2xl p-3 sm:p-4 flex items-center justify-between hover:border-indigo-600/50 transition-all group shadow-sm">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                            <i class="ph-bold ph-target text-xl sm:text-2xl"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1 sm:mb-1.5">Weekly Goal</span>
                            <div class="flex items-baseline gap-1.5 sm:gap-2">
                                <span class="text-xl sm:text-2xl font-black text-slate-900 leading-none">{{ $weeklyProgress['this_week_applications'] }}</span>
                                <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase">/ {{ $weeklyProgress['weekly_goal'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-slate-200/60 rounded-xl sm:rounded-2xl p-3 sm:p-4 flex items-center justify-between hover:border-teal-600/50 transition-all group shadow-sm">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-2xl bg-teal-50 flex items-center justify-center text-teal-600">
                            <i class="ph-bold ph-pulse text-xl sm:text-2xl"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1 sm:mb-1.5">Avg Daily</span>
                            <div class="flex items-baseline gap-1.5 sm:gap-2">
                                <span class="text-xl sm:text-2xl font-black text-slate-900 leading-none">{{ $cadenceEffect['average_daily'] }}</span>
                                <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase">/ {{ $cadenceEffect['consistency_score'] }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6 sm:space-y-8">
                {{-- Timeline Chart --}}
                <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200/60 overflow-hidden">
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 bg-slate-50/50">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-indigo-50 rounded-xl sm:rounded-2xl flex items-center justify-center text-indigo-600 shadow-inner shrink-0">
                                <i class="ph-duotone ph-chart-line text-xl sm:text-2xl"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight truncate">Timeline Activity</h3>
                                <p class="text-[9px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-0.5 sm:mt-1 truncate">Application trends and interview scheduling</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6 overflow-x-auto custom-scrollbar">
                        <div class="relative h-64 sm:h-80 w-full min-w-[500px] sm:min-w-0">
                            <canvas id="timelineChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Application Funnel --}}
                <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200/60 overflow-hidden">
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 bg-slate-50/50">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-50 rounded-xl sm:rounded-2xl flex items-center justify-center text-purple-600 shadow-inner shrink-0">
                                <i class="ph-duotone ph-funnel text-xl sm:text-2xl"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight truncate">Application Funnel</h3>
                                <p class="text-[9px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-0.5 sm:mt-1 truncate">Recruitment stage progression mapping</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        @if(count($funnelData) > 0)
                            @php
                                $totalApps = array_sum(array_values($funnelData));
                                $statusColors = [
                                    'Applied' => 'bg-blue-500',
                                    'Interview' => 'bg-amber-500',
                                    'Accepted' => 'bg-emerald-500',
                                    'Rejected' => 'bg-rose-500',
                                    'Pending' => 'bg-slate-400'
                                ];
                                $flow = ['Applied', 'Interview', 'Accepted', 'Rejected', 'Pending'];
                                $prevCount = $totalApps;
                            @endphp
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-3 sm:gap-4">
                                @foreach($flow as $index => $label)
                                    @php
                                        $count = isset($funnelData[$label]) ? $funnelData[$label] : 0;
                                        $conversionRate = $prevCount > 0 ? round(($count / $prevCount) * 100) : 0;
                                    @endphp
                                    <div class="bg-slate-50/50 p-3 sm:p-4 rounded-xl sm:rounded-3xl border border-slate-100">
                                        <div class="flex items-center justify-between mb-3 sm:mb-4">
                                            <div class="w-8 h-8 sm:w-10 sm:h-10 {{ $statusColors[$label] ?? 'bg-indigo-500' }} rounded-lg sm:rounded-xl flex items-center justify-center text-white">
                                                <i class="ph-bold {{ $label === 'Applied' ? 'ph-paper-plane-tilt' : ($label === 'Interview' ? 'ph-chats-circle' : ($label === 'Accepted' ? 'ph-check-circle' : ($label === 'Rejected' ? 'ph-x-circle' : 'ph-clock'))) }} text-base sm:text-lg"></i>
                                            </div>
                                            <span class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase truncate ml-2">{{ $label }}</span>
                                        </div>
                                        <h4 class="text-2xl sm:text-3xl font-black text-slate-900">{{ number_format($count) }}</h4>
                                        <div class="w-full h-1 sm:h-1.5 bg-slate-200 rounded-full mt-3 sm:mt-4 overflow-hidden">
                                            <div class="h-full {{ $statusColors[$label] ?? 'bg-indigo-500' }}" style="width: {{ $conversionRate }}%"></div>
                                        </div>
                                    </div>
                                    @php $prevCount = $count > 0 ? $count : $prevCount; @endphp
                                @endforeach
                            </div>
                        @else
                            <div class="py-8 sm:py-12 text-center text-slate-400 font-bold text-sm">No data available</div>
                        @endif
                    </div>
                </div>

                {{-- Advanced Analytics Grid --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200/60 overflow-hidden">
                        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight truncate">Platform Effectiveness</h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div class="relative h-64 sm:h-80 w-full">
                                <canvas id="platformChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200/60 overflow-hidden">
                        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight truncate">Career Level</h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div class="relative h-64 sm:h-80 w-full">
                                <canvas id="careerLevelChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200/60 overflow-hidden">
                        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight truncate">Province Demographics</h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div class="relative h-64 sm:h-80 w-full">
                                <canvas id="provinceChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200/60 overflow-hidden">
                        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight truncate">City Demographics</h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div class="relative h-64 sm:h-80 w-full">
                                <canvas id="cityChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function updateTimeFilter(filter) {
            const url = new URL(window.location);
            url.searchParams.set('timeFilter', filter);
            window.location.href = url.toString();
        }

        function initAllCharts() {
            initTimelineChart();
            initPlatformChart();
            initCareerLevelChart();
            initProvinceChart();
            initCityChart();
        }

        function initTimelineChart() {
            const ctx = document.getElementById('timelineChart')?.getContext('2d');
            if (!ctx) return;
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($timelineData['labels'] ?? []),
                    datasets: [
                        { label: 'Applications', data: @json($timelineData['applications'] ?? []), borderColor: '#4f46e5', tension: 0.4, fill: true, backgroundColor: 'rgba(79, 70, 229, 0.05)' },
                        { label: 'Interviews', data: @json($timelineData['interviews'] ?? []), borderColor: '#10b981', tension: 0.4, fill: true, backgroundColor: 'rgba(16, 185, 129, 0.05)' }
                    ]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top' } } }
            });
        }

        function initPlatformChart() {
            const ctx = document.getElementById('platformChart')?.getContext('2d');
            if (!ctx) return;
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: @json(collect($platformEffectiveness)->pluck('platform')),
                    datasets: [{ 
                        data: @json(collect($platformEffectiveness)->pluck('total_applications')), 
                        backgroundColor: ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#f43f5e'] 
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, cutout: '75%' }
            });
        }

        function initCareerLevelChart() {
            const ctx = document.getElementById('careerLevelChart')?.getContext('2d');
            if (!ctx) return;
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json(collect($careerLevelAnalysis)->pluck('career_level')),
                    datasets: [{ 
                        label: 'Applications', 
                        data: @json(collect($careerLevelAnalysis)->pluck('total_applications')), 
                        backgroundColor: '#4f46e5', 
                        borderRadius: 12 
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });
        }

        function initProvinceChart() {
            const ctx = document.getElementById('provinceChart')?.getContext('2d');
            if (!ctx) return;
            const data = @json($locationAnalysis['provinces'] ?? []);
            new Chart(ctx, {
                type: 'polarArea',
                data: {
                    labels: Object.keys(data),
                    datasets: [{ 
                        data: Object.values(data), 
                        backgroundColor: ['rgba(79, 70, 229, 0.6)', 'rgba(16, 185, 129, 0.6)', 'rgba(245, 158, 11, 0.6)', 'rgba(239, 68, 68, 0.6)', 'rgba(139, 92, 246, 0.6)'] 
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });
        }

        function initCityChart() {
            const ctx = document.getElementById('cityChart')?.getContext('2d');
            if (!ctx) return;
            const data = @json($locationAnalysis['cities'] ?? []);
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: Object.keys(data),
                    datasets: [{ 
                        data: Object.values(data), 
                        backgroundColor: ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#f43f5e'] 
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });
        }

        document.addEventListener('DOMContentLoaded', initAllCharts);
    </script>
</x-app-layout>
