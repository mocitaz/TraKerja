<x-app-layout>
    <x-slot name="header">
        <!-- Ignored layout slot, header is handled inline inside template container for premium consistency -->
    </x-slot>

    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    {{-- Load Chart.js with onload trigger to handle async loads under wire:navigate --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js" onload="if(typeof initAllCharts === 'function') initAllCharts()"></script>

    <script>
        var chartsInitialized = false;

        function initAllCharts() {
            if (chartsInitialized) return;
            
            // Check if DOM canvas element exists before initializing
            if (!document.getElementById('timelineChart')) {
                return;
            }
            
            chartsInitialized = true;

            // Wait a tiny bit to ensure DOM elements are fully rendered by Livewire/Alpine
            setTimeout(() => {
                initTimelineChart();
                initPlatformChart();
                initCareerLevelChart();
                initProvinceChart();
                initCityChart();
                initRolesChart();
                initDayOfWeekChart();
                initVelocityChart();
            }, 50);
        }

        function initDayOfWeekChart() {
            const ctx = document.getElementById('dayOfWeekChart')?.getContext('2d');
            if (!ctx) return;
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json(collect($dayOfWeekActivity)->pluck('day')),
                    datasets: [{
                        label: 'Applications',
                        data: @json(collect($dayOfWeekActivity)->pluck('count')),
                        backgroundColor: '#6366f1',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
                }
            });
        }

        function initVelocityChart() {
            const ctx = document.getElementById('velocityChart')?.getContext('2d');
            if (!ctx) return;
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json(collect($velocityData)->pluck('week')),
                    datasets: [{
                        label: 'Apps/Week',
                        data: @json(collect($velocityData)->pluck('count')),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.05)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3,
                        pointBackgroundColor: '#10b981'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
                }
            });
        }

        // Apply a modern color scheme to the bar charts
        function initRolesChart() {
            const ctx = document.getElementById('rolesChart')?.getContext('2d');
            if (!ctx) return;
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json(collect($positionAnalysis)->pluck('position')),
                    datasets: [{
                        label: 'Applications',
                        data: @json(collect($positionAnalysis)->pluck('total_applications')),
                        backgroundColor: [
                            '#4f46e5', // Indigo
                            '#10b981', // Emerald
                            '#f59e0b', // Amber
                            '#ef4444', // Rose
                            '#8b5cf6', // Violet
                            '#06b6d4', // Cyan
                            '#f43f5e', // Pink
                            '#84cc16', // Lime
                            '#3b82f6', // Blue
                            '#6366f1'  // Indigo 500
                        ],
                        borderRadius: 4
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y: { grid: { display: false } }
                    }
                }
            });
        }

        function initTimelineChart() {
            const ctx = document.getElementById('timelineChart')?.getContext('2d');
            if (!ctx) return;
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($timelineData['labels'] ?? []),
                    datasets: [
                        { label: 'Applications', data: @json($timelineData['applications'] ?? []), borderColor: '#4f46e5', tension: 0.4, fill: true, backgroundColor: 'rgba(79, 70, 229, 0.02)', pointRadius: 2 },
                        { label: 'Interviews', data: @json($timelineData['interviews'] ?? []), borderColor: '#10b981', tension: 0.4, fill: true, backgroundColor: 'rgba(16, 185, 129, 0.02)', pointRadius: 2 }
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
                    datasets: [{ data: @json(collect($platformEffectiveness)->pluck('total_applications')), backgroundColor: ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#f43f5e'] }]
                },
                options: { responsive: true, maintainAspectRatio: false, cutout: '80%' }
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
                        backgroundColor: [
                            '#4f46e5', // Indigo
                            '#10b981', // Emerald
                            '#f59e0b', // Amber
                            '#ef4444', // Rose
                            '#8b5cf6', // Violet
                            '#06b6d4', // Cyan
                        ],
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }

        function initProvinceChart() {
            const ctx = document.getElementById('provinceChart')?.getContext('2d');
            if (!ctx) return;
            const prov = @json($locationAnalysis['provinces'] ?? []);
            new Chart(ctx, {
                type: 'pie',
                data: { labels: Object.keys(prov), datasets: [{ data: Object.values(prov), backgroundColor: ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#f43f5e'] }] },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                padding: 12,
                                usePointStyle: true,
                                font: { size: 9, weight: '600' }
                            }
                        }
                    }
                }
            });
        }

        function initCityChart() {
            const ctx = document.getElementById('cityChart')?.getContext('2d');
            if (!ctx) return;
            const cities = @json($locationAnalysis['cities'] ?? []);
            new Chart(ctx, {
                type: 'pie',
                data: { labels: Object.keys(cities), datasets: [{ data: Object.values(cities), backgroundColor: ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#f43f5e'] }] },
                options: { responsive: true, maintainAspectRatio: false }
            });
        }

        // Support for standard page refresh
        document.addEventListener('DOMContentLoaded', () => {
            chartsInitialized = false;
            if (typeof Chart !== 'undefined') {
                initAllCharts();
            }
        });

        // Support for Livewire wire:navigate
        document.addEventListener('livewire:navigated', () => {
            chartsInitialized = false;
            if (typeof Chart !== 'undefined') {
                initAllCharts();
            }
        });
    </script>

    @php
        $user = auth()->user();
        $hasAccess = \App\Models\Setting::isMonetizationEnabled() ? $user->isPremium() : true;
    @endphp

    <div class="bg-[#fafafa] {{ !$hasAccess ? 'h-[calc(100vh-73px)] overflow-hidden flex items-center justify-center' : 'min-h-screen pb-16' }}">
        <div class="max-w-[1300px] w-full mx-auto px-4 sm:px-6 lg:px-8 {{ !$hasAccess ? '' : 'pt-6' }}">

            @if(!$hasAccess)
                <!-- Premium Wall Panel -->
                <div class="max-w-md mx-auto">
                    <div class="bg-white rounded-lg border border-zinc-200/60 overflow-hidden text-center p-6 sm:p-8 shadow-3xs relative">
                        <div class="relative z-10 flex flex-col items-center">
                            <div class="w-12 h-12 bg-primary-50 rounded-lg flex items-center justify-center text-primary-600 /* [BRAND_PRIMARY] */ mb-4 border border-primary-100/60">
                                <i class="ph-fill ph-chart-line-up text-2xl"></i>
                            </div>
                            
                            <h2 class="text-sm font-bold text-zinc-800 tracking-tight mb-2">Advanced Analytics</h2>
                            <p class="text-[11px] font-medium text-zinc-500 leading-relaxed max-w-xs mb-6">Unlock comprehensive insights into your job hunt journey. Funnel rates, velocity trends, and data-driven recommendations are exclusively for Premium members.</p>
                            
                            <a href="{{ route('payment.premium') }}" class="w-full py-2 bg-zinc-900 text-white rounded-md font-bold text-[10px] uppercase tracking-wider hover:bg-zinc-800 transition-colors shadow-3xs flex items-center justify-center gap-1.5 focus:outline-none">
                                <span>Upgrade to Premium</span>
                                <i class="ph ph-arrow-right text-xs"></i>
                            </a>
                            
                            <div class="mt-6 pt-5 border-t border-zinc-100 flex gap-6 justify-center w-full">
                                <div class="text-center">
                                    <i class="ph ph-funnel text-base text-primary-500 mb-0.5"></i>
                                    <p class="text-[8px] font-bold text-zinc-400 uppercase tracking-widest leading-none">Funnels</p>
                                </div>
                                <div class="text-center">
                                    <i class="ph ph-trend-up text-base text-emerald-500 mb-0.5"></i>
                                    <p class="text-[8px] font-bold text-zinc-400 uppercase tracking-widest leading-none">Velocity</p>
                                </div>
                                <div class="text-center">
                                    <i class="ph ph-globe text-base text-blue-500 mb-0.5"></i>
                                    <p class="text-[8px] font-bold text-zinc-400 uppercase tracking-widest leading-none">Platforms</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
            <!-- Premium Notion-Inspired Page Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-zinc-200/50 pb-4 mb-5">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-zinc-100 border border-zinc-200/60 rounded-lg flex items-center justify-center text-zinc-500 shrink-0 shadow-2xs">
                        <i class="ph ph-chart-line-up text-base"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-sm font-bold text-zinc-800 tracking-tight">Analytics Summary</h1>
                            <span class="px-1.5 py-0.5 bg-primary-50 text-zinc-800 text-[9px] font-black uppercase tracking-wider rounded border border-primary-100/60">Overview</span>
                        </div>
                        <p class="text-[11px] text-zinc-400 mt-0.5">Comprehensive, data-driven insights into your job search trajectory.</p>
                    </div>
                </div>

                <!-- Time Filter switcher (Simple, no effects) -->
                <div class="flex w-full md:w-auto p-0.5 bg-zinc-100 rounded-md shrink-0 shadow-3xs">
                    <button onclick="updateTimeFilter('weekly')"
                        class="flex-1 md:flex-none px-3.5 py-1 text-xs font-semibold rounded-md {{ $timeFilter === 'weekly' ? 'bg-primary-50 text-zinc-800 border border-primary-200/60 shadow-3xs font-bold' : 'text-zinc-500 hover:text-zinc-800 border border-transparent' }} transition-all focus:outline-none"
                        data-filter="weekly">Weekly</button>
                    <button onclick="updateTimeFilter('monthly')"
                        class="flex-1 md:flex-none px-3.5 py-1 text-xs font-semibold rounded-md {{ $timeFilter === 'monthly' ? 'bg-primary-50 text-zinc-800 border border-primary-200/60 shadow-3xs font-bold' : 'text-zinc-500 hover:text-zinc-800 border border-transparent' }} transition-all focus:outline-none"
                        data-filter="monthly">Monthly</button>
                    <button onclick="updateTimeFilter('all')"
                        class="flex-1 md:flex-none px-3.5 py-1 text-xs font-semibold rounded-md {{ $timeFilter === 'all' ? 'bg-primary-50 text-zinc-800 border border-primary-200/60 shadow-3xs font-bold' : 'text-zinc-500 hover:text-zinc-800 border border-transparent' }} transition-all focus:outline-none"
                        data-filter="all">All Time</button>
                </div>
            </div>

            <!-- Stats Overview Cards (Notion simple layout) -->
            @php
                $onProcess = $statusDistribution->where('application_status', 'On Process')->first()->count ?? 0;
                $rejectedCount = $funnelData['Rejected'] ?? 0;
            @endphp
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-5">
                <!-- On Process Card -->
                <div class="bg-white rounded-lg border border-zinc-200/60 p-3 sm:p-3.5 flex items-center justify-between transition-colors hover:border-blue-400 group shadow-3xs">
                    <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 bg-blue-50/50 rounded-md flex items-center justify-center text-blue-600 shrink-0">
                            <i class="ph ph-spinner-gap text-sm sm:text-base animate-spin-slow"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[8px] sm:text-[9px] font-bold text-zinc-400 uppercase tracking-widest leading-none truncate">On Process</p>
                            <p class="text-[9px] sm:text-[10px] text-zinc-500 font-semibold mt-0.5 leading-none truncate">Active Apps</p>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-lg sm:text-xl font-bold text-zinc-800 tracking-tight leading-none">{{ $onProcess }}</p>
                    </div>
                </div>

                <!-- Offering/Accepted Card -->
                <div class="bg-white rounded-lg border border-zinc-200/60 p-3 sm:p-3.5 flex items-center justify-between transition-colors hover:border-emerald-400 group shadow-3xs">
                    <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 bg-emerald-50/50 rounded-md flex items-center justify-center text-emerald-600 shrink-0">
                            <i class="ph ph-check-circle text-sm sm:text-base"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[8px] sm:text-[9px] font-bold text-zinc-400 uppercase tracking-widest leading-none truncate">Success</p>
                            <p class="text-[9px] sm:text-[10px] text-zinc-500 font-semibold mt-0.5 leading-none truncate">Offers & Recs</p>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-lg sm:text-xl font-bold text-zinc-800 tracking-tight leading-none">{{ $offeringAcceptedCount }}</p>
                    </div>
                </div>

                <!-- Declined Card -->
                <div class="bg-white rounded-lg border border-zinc-200/60 p-3 sm:p-3.5 flex items-center justify-between transition-colors hover:border-rose-400 group shadow-3xs">
                    <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 bg-rose-50/50 rounded-md flex items-center justify-center text-rose-600 shrink-0">
                            <i class="ph ph-x-circle text-sm sm:text-base"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[8px] sm:text-[9px] font-bold text-zinc-400 uppercase tracking-widest leading-none truncate">Declined</p>
                            <p class="text-[9px] sm:text-[10px] text-zinc-500 font-semibold mt-0.5 leading-none truncate">Rejected</p>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-lg sm:text-xl font-bold text-zinc-800 tracking-tight leading-none">{{ $rejectedCount }}</p>
                    </div>
                </div>

                <!-- Total Interviews Card -->
                <div class="bg-white rounded-lg border border-zinc-200/60 p-3 sm:p-3.5 flex items-center justify-between transition-colors hover:border-orange-400 group shadow-3xs">
                    <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 bg-orange-50/50 rounded-md flex items-center justify-center text-orange-600 shrink-0">
                            <i class="ph ph-calendar text-sm sm:text-base"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[8px] sm:text-[9px] font-bold text-zinc-400 uppercase tracking-widest leading-none truncate">Interviews</p>
                            <p class="text-[9px] sm:text-[10px] text-zinc-500 font-semibold mt-0.5 leading-none truncate">Total Scheduled</p>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-lg sm:text-xl font-bold text-zinc-800 tracking-tight leading-none">{{ $interviews }}</p>
                    </div>
                </div>
            </div>

            {{-- Productivity Highlights --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mb-5">
                <!-- Streak Card -->
                <div class="bg-white border border-zinc-200/60 rounded-lg p-3 flex items-center justify-between hover:border-orange-400 transition-colors shadow-3xs">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 bg-orange-50/50 rounded bg-orange-50/50 flex items-center justify-center text-orange-600 shrink-0">
                            <i class="ph ph-fire text-base"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[8px] font-bold text-zinc-400 uppercase tracking-widest leading-none mb-1">Daily Streak</span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-base font-bold text-zinc-800 leading-none">{{ $dailyStreak['current_streak'] }}</span>
                                <span class="text-[8px] font-bold text-zinc-500 uppercase leading-none">/ Best {{ $dailyStreak['best_streak'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Goal Card -->
                <div class="bg-white border border-zinc-200/60 rounded-lg p-3 flex items-center justify-between hover:border-primary-400 transition-colors shadow-3xs">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded bg-primary-50/50 flex items-center justify-center text-primary-600 /* [BRAND_PRIMARY] */ shrink-0">
                            <i class="ph ph-target text-base"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[8px] font-bold text-zinc-400 uppercase tracking-widest leading-none mb-1">Weekly Goal</span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-base font-bold text-zinc-800 leading-none">{{ $weeklyProgress['this_week_applications'] }}</span>
                                <span class="text-[8px] font-bold text-zinc-500 uppercase leading-none">/ {{ $weeklyProgress['weekly_goal'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Avg Daily Card -->
                <div class="bg-white border border-zinc-200/60 rounded-lg p-3 flex items-center justify-between hover:border-teal-400 transition-colors shadow-3xs">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded bg-teal-50/50 flex items-center justify-center text-teal-600 shrink-0">
                            <i class="ph ph-pulse text-base"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[8px] font-bold text-zinc-400 uppercase tracking-widest leading-none mb-1">Avg Daily</span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-base font-bold text-zinc-800 leading-none">{{ $cadenceEffect['average_daily'] }}</span>
                                <span class="text-[8px] font-bold text-zinc-500 uppercase leading-none">/ {{ $cadenceEffect['consistency_score'] }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-5">
                {{-- Timeline Chart --}}
                <div class="bg-white rounded-lg border border-zinc-200/60 overflow-hidden shadow-3xs">
                    <div class="px-4 py-3 border-b border-zinc-150/60 bg-zinc-50/20">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-zinc-50 border border-zinc-200/50 rounded flex items-center justify-center text-zinc-500 shrink-0 shadow-3xs">
                                <i class="ph ph-chart-line text-base"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-xs font-bold text-zinc-800 tracking-tight uppercase tracking-wider truncate">Timeline Activity</h3>
                                <p class="text-[10px] text-zinc-400 font-medium leading-none mt-0.5 truncate">Application trends and interview scheduling</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 overflow-x-auto custom-scrollbar">
                        <div class="relative h-64 w-full min-w-[500px] sm:min-w-0">
                            <canvas id="timelineChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Application Funnel --}}
                <div class="bg-white rounded-lg border border-zinc-200/60 overflow-hidden shadow-3xs">
                    <div class="px-4 py-3 border-b border-zinc-200/60 bg-zinc-50/20">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-zinc-50 border border-zinc-200/50 rounded flex items-center justify-center text-zinc-500 shrink-0 shadow-3xs">
                                <i class="ph ph-funnel text-base"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-xs font-bold text-zinc-800 tracking-tight uppercase tracking-wider truncate">Application Funnel</h3>
                                <p class="text-[10px] text-zinc-400 font-medium leading-none mt-0.5 truncate">Recruitment stage progression mapping</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 sm:p-5">
                        @if(isset($funnelData) && count($funnelData) > 0)
                            @php
                                $totalApps = $funnelData['Applied'] ?? 0;
                                $interviewCount = $funnelData['Interview'] ?? 0;
                                $acceptedCount = $funnelData['Accepted'] ?? 0;
                                $rejectedCount = $funnelData['Rejected'] ?? 0;
                                $pendingCount = $funnelData['Pending'] ?? 0;
                                
                                $statusColors = [
                                    'Applied' => 'bg-blue-500',
                                    'Interview' => 'bg-amber-500',
                                    'Accepted' => 'bg-emerald-500',
                                    'Rejected' => 'bg-rose-500',
                                    'Pending' => 'bg-zinc-400'
                                ];
                                $flow = ['Applied', 'Interview', 'Accepted', 'Rejected', 'Pending'];
                            @endphp
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                                @foreach($flow as $index => $label)
                                    @php
                                        if ($label === 'Applied') {
                                            $count = $totalApps;
                                            $conversionRate = $totalApps > 0 ? 100 : 0;
                                        } elseif ($label === 'Interview') {
                                            $count = $interviewCount;
                                            $conversionRate = $totalApps > 0 ? round(($interviewCount / $totalApps) * 100) : 0;
                                        } elseif ($label === 'Accepted') {
                                            $count = $acceptedCount;
                                            $conversionRate = $interviewCount > 0 ? round(($acceptedCount / $interviewCount) * 100) : 0;
                                        } elseif ($label === 'Rejected') {
                                            $count = $rejectedCount;
                                            $conversionRate = $totalApps > 0 ? round(($rejectedCount / $totalApps) * 100) : 0;
                                        } else {
                                            $count = $pendingCount;
                                            $conversionRate = $totalApps > 0 ? round(($pendingCount / $totalApps) * 100) : 0;
                                        }
                                    @endphp
                                    <div class="bg-zinc-50/30 p-3 rounded border border-zinc-200/50 {{ $label === 'Pending' ? 'col-span-2 md:col-span-1' : '' }}">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="w-6 h-6 {{ $statusColors[$label] ?? 'bg-indigo-500' }} rounded flex items-center justify-center text-white shrink-0">
                                                <i class="ph {{ $label === 'Applied' ? 'ph-paper-plane-tilt' : ($label === 'Interview' ? 'ph-chats-circle' : ($label === 'Accepted' ? 'ph-check-circle' : ($label === 'Rejected' ? 'ph-x-circle' : 'ph-clock'))) }} text-xs"></i>
                                            </div>
                                            <span class="text-[9px] font-bold text-zinc-400 uppercase tracking-wider truncate ml-2">{{ $label }}</span>
                                        </div>
                                        <h4 class="text-base font-bold text-zinc-800">{{ number_format($count) }}</h4>
                                        <div class="w-full h-1 bg-zinc-200 rounded-full mt-2.5 overflow-hidden">
                                            <div class="h-full {{ $statusColors[$label] ?? 'bg-indigo-500' }}"
                                                style="width: {{ min(100, $conversionRate) }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="py-6 text-center text-zinc-400 font-bold text-xs italic">No data available</div>
                        @endif
                    </div>
                </div>

                {{-- Productivity Insights (Day of Week & Velocity) --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                    <div class="bg-white rounded-lg border border-zinc-200/60 overflow-hidden shadow-3xs">
                        <div class="px-4 py-3 border-b border-zinc-200/60 bg-zinc-50/20">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-zinc-50 border border-zinc-200/50 rounded flex items-center justify-center text-zinc-500 shrink-0 shadow-3xs">
                                    <i class="ph ph-calendar-check text-base"></i>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-xs font-bold text-zinc-800 tracking-tight uppercase tracking-wider truncate">Day-of-the-Week Activity</h3>
                                    <p class="text-[10px] text-zinc-400 font-medium leading-none mt-0.5 truncate">Productivity distribution</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="relative h-60 w-full">
                                <canvas id="dayOfWeekChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg border border-zinc-200/60 overflow-hidden shadow-3xs">
                        <div class="px-4 py-3 border-b border-zinc-200/60 bg-zinc-50/20">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-zinc-50 border border-zinc-200/50 rounded flex items-center justify-center text-zinc-500 shrink-0 shadow-3xs">
                                    <i class="ph ph-speedometer text-base"></i>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-xs font-bold text-zinc-800 tracking-tight uppercase tracking-wider truncate">Application Velocity</h3>
                                    <p class="text-[10px] text-zinc-400 font-medium leading-none mt-0.5 truncate">Weekly speed trend</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="relative h-60 w-full">
                                <canvas id="velocityChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- Advanced Analytics Grid --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                    <div class="bg-white rounded-lg border border-zinc-200/60 overflow-hidden shadow-3xs">
                        <div class="px-4 py-3 border-b border-zinc-200/60 bg-zinc-50/20">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-zinc-50 border border-zinc-200/50 rounded flex items-center justify-center text-zinc-500 shrink-0 shadow-3xs">
                                    <i class="ph ph-globe text-base"></i>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-xs font-bold text-zinc-800 tracking-tight uppercase tracking-wider truncate">Platform Effectiveness</h3>
                                    <p class="text-[10px] text-zinc-400 font-medium leading-none mt-0.5 truncate">Source distribution</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="relative h-64 w-full">
                                <canvas id="platformChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg border border-zinc-200/60 overflow-hidden shadow-3xs">
                        <div class="px-4 py-3 border-b border-zinc-200/60 bg-zinc-50/20">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-zinc-50 border border-zinc-200/50 rounded flex items-center justify-center text-zinc-500 shrink-0 shadow-3xs">
                                    <i class="ph ph-stairs text-base"></i>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-xs font-bold text-zinc-800 tracking-tight uppercase tracking-wider truncate">Career Level</h3>
                                    <p class="text-[10px] text-zinc-400 font-medium leading-none mt-0.5 truncate">Job type analysis</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="relative h-64 w-full">
                                <canvas id="careerLevelChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                    <div class="bg-white rounded-lg border border-zinc-200/60 overflow-hidden shadow-3xs">
                        <div class="px-4 py-3 border-b border-zinc-200/60 bg-zinc-50/20">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-zinc-50 border border-zinc-200/50 rounded flex items-center justify-center text-zinc-500 shrink-0 shadow-3xs">
                                    <i class="ph ph-map-trifold text-base"></i>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-xs font-bold text-zinc-800 tracking-tight uppercase tracking-wider truncate">Province Demographics</h3>
                                    <p class="text-[10px] text-zinc-400 font-medium leading-none mt-0.5 truncate">Regional distribution</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="relative h-64 w-full">
                                <canvas id="provinceChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg border border-zinc-200/60 overflow-hidden shadow-3xs">
                        <div class="px-4 py-3 border-b border-zinc-200/60 bg-zinc-50/20">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-zinc-50 border border-zinc-200/50 rounded flex items-center justify-center text-zinc-500 shrink-0 shadow-3xs">
                                    <i class="ph ph-buildings text-base"></i>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-xs font-bold text-zinc-800 tracking-tight uppercase tracking-wider truncate">City Demographics</h3>
                                    <p class="text-[10px] text-zinc-400 font-medium leading-none mt-0.5 truncate">Urban analysis</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="relative h-64 w-full">
                                <canvas id="cityChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                    {{-- Top Companies Leaderboard --}}
                    <div class="bg-white rounded-lg border border-zinc-200/60 overflow-hidden shadow-3xs">
                        <div class="px-4 py-3 border-b border-zinc-200/60 bg-zinc-50/20">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-zinc-50 border border-zinc-200/50 rounded flex items-center justify-center text-zinc-500 shrink-0 shadow-3xs">
                                    <i class="ph ph-crown text-base"></i>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-xs font-bold text-zinc-800 tracking-tight uppercase tracking-wider truncate">Top Hiring Companies</h3>
                                    <p class="text-[10px] text-zinc-400 font-medium leading-none mt-0.5 truncate">Hiring leaderboard</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="space-y-2.5">
                                @forelse($topCompanies as $index => $company)
                                    <div class="flex items-center justify-between p-2.5 rounded-md bg-zinc-50/30 border border-zinc-200/50 group hover:bg-white hover:border-zinc-300 transition-colors">
                                        <div class="flex items-center gap-3">
                                            <div class="w-7 h-7 rounded bg-zinc-100 flex items-center justify-center font-bold text-[10px] shrink-0 text-zinc-600 uppercase">
                                                {{ substr($company['company_name'], 0, 2) }}
                                            </div>
                                            <div class="flex flex-col min-w-0">
                                                <span class="text-xs font-semibold text-zinc-800 truncate leading-none">{{ $company['company_name'] }}</span>
                                                <div class="flex items-center gap-1.5 mt-1 leading-none">
                                                    <span class="text-[9px] font-bold text-zinc-400 uppercase tracking-wider leading-none">{{ $company['applications'] }} Apps</span>
                                                    @if($company['accepted'] > 0)
                                                        <span class="w-1 h-1 bg-zinc-300 rounded-full"></span>
                                                        <span class="text-[9px] font-black text-emerald-600 uppercase leading-none">Offering</span>
                                                    @elseif($company['interviews'] > 0)
                                                        <span class="w-1 h-1 bg-zinc-300 rounded-full"></span>
                                                        <span class="text-[9px] font-black text-amber-600 uppercase leading-none">Interviewing</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex flex-col items-end">
                                            <span class="text-xs font-bold text-zinc-800">{{ round(($company['applications'] / $applicationsCount) * 100) }}%</span>
                                            <span class="text-[8px] font-bold text-zinc-400 uppercase tracking-wide mt-0.5">Share</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="py-6 text-center text-zinc-400 font-bold text-xs italic">No data available</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
 
                    {{-- Popular Roles Chart --}}
                    <div class="bg-white rounded-lg border border-zinc-200/60 overflow-hidden shadow-3xs">
                        <div class="px-4 py-3 border-b border-zinc-200/60 bg-zinc-50/20">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-zinc-50 border border-zinc-200/50 rounded flex items-center justify-center text-zinc-500 shrink-0 shadow-3xs">
                                    <i class="ph ph-briefcase text-base"></i>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-xs font-bold text-zinc-800 tracking-tight uppercase tracking-wider truncate">Popular Roles</h3>
                                    <p class="text-[10px] text-zinc-400 font-medium leading-none mt-0.5 truncate">Target position analysis</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="relative h-64 w-full">
                                <canvas id="rolesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
 
            
            {{-- Gamification: Career Profile Card (Notion-style bottom layout) --}}
            <div class="mt-8 border-t border-zinc-200/50 pt-5">
                <div class="bg-white border border-zinc-200/60 rounded-lg p-4 shadow-3xs flex flex-col md:flex-row items-center justify-between gap-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary-50 rounded-lg flex items-center justify-center border border-primary-100/60 shrink-0 relative group">
                            <i class="ph-fill ph-trophy text-xl text-primary-650"></i>
                            <div class="absolute -top-1.5 -right-1.5 bg-primary-50 text-primary-650 text-[7.5px] font-black px-1.5 py-0.2 rounded border border-primary-200 shadow-3xs">
                                L{{ $user->level ?? 1 }}
                            </div>
                        </div>
                        <div>
                            <h2 class="text-xs font-bold text-zinc-800 tracking-tight">{{ $user->level_title ?? 'Job Seeker' }}</h2>
                            <p class="text-[9px] font-bold text-zinc-400 mt-0.5 uppercase tracking-wider leading-none">Keep moving stages to earn XP</p>
                        </div>
                    </div>
 
                    <div class="w-full md:w-1/3 flex flex-col">
                        @php
                            $currentLvl = $user->level ?? 1;
                            $currentXp = $user->xp ?? 0;
                            $thresholds = \App\Models\User::LEVEL_THRESHOLDS ?? [1=>0,2=>100,3=>300,4=>600,5=>1000];
                            $nextThreshold = $thresholds[$currentLvl + 1] ?? $currentXp;
                            $prevThreshold = $thresholds[$currentLvl] ?? 0;
                            
                            $range = max(1, $nextThreshold - $prevThreshold);
                            $progress = max(0, $currentXp - $prevThreshold);
                            $percentage = ($currentLvl >= 5) ? 100 : min(100, ($progress / $range) * 100);
                            $xpNeeded = max(0, $nextThreshold - $currentXp);
                        @endphp
                        <div class="flex justify-between items-end mb-1">
                            <span class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest leading-none">Progress</span>
                            <span class="text-xs font-bold text-zinc-700 leading-none">{{ $currentXp }} <span class="text-[9px] text-zinc-400 font-semibold">/ {{ $nextThreshold }} XP</span></span>
                        </div>
                        <div class="w-full h-1 bg-zinc-200 rounded-full overflow-hidden">
                            <div class="h-full bg-primary-650 rounded-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                        </div>
                        @if($currentLvl < 5)
                        <p class="text-[9px] text-right font-bold text-primary-650 /* [BRAND_PRIMARY] */ mt-1 leading-none">{{ $xpNeeded }} XP to next level <i class="ph ph-arrow-right text-[8px]"></i></p>
                        @else
                        <p class="text-[9px] text-right font-bold text-primary-650 /* [BRAND_PRIMARY] */ mt-1 flex justify-end items-center gap-1 leading-none">Maximum Level Reached! <i class="ph-fill ph-crown text-primary-400 text-[10px]"></i></p>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        function updateTimeFilter(filter) {
            const url = new URL(window.location);
            url.searchParams.set('timeFilter', filter);
            window.location.href = url.toString();
        }
    </script>
</x-app-layout>