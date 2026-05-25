<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-2xl font-black text-slate-900 leading-tight tracking-tight">
                Analytics <span
                    class="bg-clip-text text-transparent bg-gradient-to-r from-[#d983e4] via-purple-600 to-[#4e71c5]">Summary</span>
            </h1>
            <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest mt-1">Comprehensive insights into
                your journey</p>
        </div>
    </x-slot>

    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    {{-- Load Chart.js at the bottom or with defer to ensure DOM is ready --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Use a flag to prevent multiple initializations if needed
        var chartsInitialized = false;

        function initAllCharts() {
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
                chartsInitialized = true;
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
                        borderRadius: 6
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
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
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
                        borderRadius: 8
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
                    datasets: [{ data: @json(collect($platformEffectiveness)->pluck('total_applications')), backgroundColor: ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#f43f5e'] }]
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
                        backgroundColor: [
                            '#4f46e5', // Indigo
                            '#10b981', // Emerald
                            '#f59e0b', // Amber
                            '#ef4444', // Rose
                            '#8b5cf6', // Violet
                            '#06b6d4', // Cyan
                        ],
                        borderRadius: 12
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
                                padding: 20,
                                usePointStyle: true,
                                font: { size: 10, weight: '600' }
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
            if (typeof Chart !== 'undefined') {
                initAllCharts();
            }
        });

        // Support for Livewire wire:navigate
        document.addEventListener('livewire:navigated', () => {
            if (typeof Chart !== 'undefined') {
                initAllCharts();
            }
        });
    </script>

    <style>
        .mesh-gradient-blue {
            background-color: #ffffff;
            background-image:
                radial-gradient(at 0% 0%, rgba(59, 130, 246, 0.03) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(37, 99, 235, 0.03) 0px, transparent 50%);
        }

        .mesh-gradient-emerald {
            background-color: #ffffff;
            background-image:
                radial-gradient(at 0% 0%, rgba(16, 185, 129, 0.03) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(5, 150, 105, 0.03) 0px, transparent 50%);
        }

        .mesh-gradient-rose {
            background-color: #ffffff;
            background-image:
                radial-gradient(at 0% 0%, rgba(244, 63, 94, 0.03) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(225, 29, 72, 0.03) 0px, transparent 50%);
        }

        .mesh-gradient-orange {
            background-color: #ffffff;
            background-image:
                radial-gradient(at 0% 0%, rgba(245, 158, 11, 0.03) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(217, 119, 6, 0.03) 0px, transparent 50%);
        }

        .mesh-gradient-purple {
            background-color: #ffffff;
            background-image:
                radial-gradient(at 0% 0%, rgba(139, 92, 246, 0.03) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(124, 58, 237, 0.03) 0px, transparent 50%);
        }

        .bento-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.8), 0 10px 20px -5px rgba(0, 0, 0, 0.03);
        }

        .bento-card:hover {
            transform: translateY(-4px) scale(1.01);
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.8), 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        }

        .bento-card-stat {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.8), 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        }
        .bento-card-stat:hover {
            transform: translateY(-4px);
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.8), 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        }
    </style>

    @php
        $user = auth()->user();
        $hasAccess = \App\Models\Setting::isMonetizationEnabled() ? $user->isPremium() : true;
    @endphp

    <div class="bg-[#f8fafc] {{ !$hasAccess ? 'h-[calc(100vh-73px)] overflow-hidden flex items-center justify-center' : 'min-h-screen pb-20' }}">
        <div class="max-w-[1300px] w-full mx-auto px-4 sm:px-6 lg:px-8 {{ !$hasAccess ? '' : 'pt-8' }}">

            {{-- Gamification: Career Profile Card --}}
            <div class="mb-6">
                <div class="bg-white border border-slate-200/60 rounded-[1.5rem] p-5 sm:p-6 shadow-sm hover:border-primary-500/30 hover:shadow-md transition-all duration-300 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-primary-50 rounded-xl flex items-center justify-center border border-primary-100 shrink-0 relative group">
                            <i class="ph-fill ph-trophy text-3xl text-primary-500 group-hover:scale-110 transition-transform"></i>
                            <div class="absolute -top-1.5 -right-1.5 bg-primary-600 text-white text-[9px] font-black px-1.5 py-0.5 rounded-md border border-white shadow-sm">
                                L{{ $user->level ?? 1 }}
                            </div>
                        </div>
                        <div>
                            <h2 class="text-lg font-black text-slate-900 tracking-tight">{{ $user->level_title ?? 'Job Seeker' }}</h2>
                            <p class="text-[10px] font-bold text-slate-500 mt-0.5 uppercase tracking-widest">Keep moving stages to earn XP</p>
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
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Progress</span>
                            <span class="text-xs font-black text-slate-900">{{ $currentXp }} <span class="text-[10px] text-slate-400 font-bold">/ {{ $nextThreshold }} XP</span></span>
                        </div>
                        <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-primary-500 rounded-full transition-all duration-1000 relative" style="width: {{ $percentage }}%"></div>
                        </div>
                        @if($currentLvl < 5)
                        <p class="text-[9px] text-right font-bold text-primary-500 mt-1.5">{{ $xpNeeded }} XP to next level <i class="ph-bold ph-arrow-right"></i></p>
                        @else
                        <p class="text-[9px] text-right font-bold text-primary-500 mt-1.5 flex justify-end items-center gap-1">Maximum Level Reached! <i class="ph-fill ph-crown text-primary-400 text-[10px]"></i></p>
                        @endif
                    </div>
                </div>
            </div>

            @if(!$hasAccess)
                <div class="max-w-xl mx-auto">
                    <div class="bg-white rounded-[2rem] border border-slate-200/60 shadow-sm overflow-hidden relative text-center p-8 sm:p-10">
                        <div class="absolute top-0 right-0 w-48 h-48 bg-indigo-50 rounded-full blur-[60px] -mr-24 -mt-24 opacity-60"></div>
                        <div class="absolute bottom-0 left-0 w-48 h-48 bg-purple-50 rounded-full blur-[60px] -ml-24 -mb-24 opacity-60"></div>
                        
                        <div class="relative z-10 flex flex-col items-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-2xl shadow-lg shadow-indigo-200/50 flex items-center justify-center text-white mb-6 border border-white/20">
                                <i class="ph-fill ph-chart-line-up text-3xl"></i>
                            </div>
                            
                            <h2 class="text-xl font-black text-slate-900 tracking-tight mb-3">Advanced Analytics</h2>
                            <p class="text-xs font-medium text-slate-500 leading-relaxed max-w-sm mb-8">Unlock comprehensive insights into your job hunt journey. Funnel rates, velocity trends, and data-driven recommendations are exclusively for Premium members.</p>
                            
                            <a href="{{ route('payment.premium') }}" class="px-8 py-3.5 bg-slate-900 text-white rounded-xl font-black text-[10px] uppercase tracking-[0.15em] hover:bg-slate-800 transition-all shadow-md active:scale-95 flex items-center gap-2">
                                Upgrade to Premium
                                <i class="ph-bold ph-arrow-right"></i>
                            </a>
                            
                            <div class="mt-8 pt-6 border-t border-slate-100 flex flex-wrap gap-4 sm:gap-6 justify-center w-full">
                                <div class="text-center">
                                    <i class="ph-duotone ph-funnel text-lg text-primary-500 mb-1.5"></i>
                                    <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest">Funnels</p>
                                </div>
                                <div class="text-center">
                                    <i class="ph-duotone ph-trend-up text-emerald-500 mb-1.5"></i>
                                    <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest">Velocity</p>
                                </div>
                                <div class="text-center">
                                    <i class="ph-duotone ph-globe-hemisphere-west text-blue-500 mb-1.5"></i>
                                    <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest">Platforms</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
            {{-- Top Bar with responsive behavior --}}
            <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-6 sm:mb-8">
                <div class="flex items-center gap-3 sm:gap-4 min-w-0 w-full md:w-auto">
                    <div
                        class="w-10 h-10 sm:w-12 sm:h-12 bg-white border border-slate-200/60 rounded-[1.25rem] sm:rounded-[1.5rem] flex items-center justify-center text-primary-600 shadow-sm shrink-0">
                        <i class="ph-duotone ph-chart-line-up text-xl sm:text-2xl"></i>
                    </div>
                    <div class="flex flex-col min-w-0">
                        <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight truncate">Analytics
                            Summary</h3>
                        <p
                            class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mt-1 sm:mt-1.5 truncate">
                            Overview & Insights</p>
                    </div>
                </div>

                <div
                    class="flex w-full md:w-auto p-1.5 bg-white border border-slate-200/60 rounded-[1.25rem] sm:rounded-[1.5rem] shadow-sm backdrop-blur-md shrink-0">
                    <button onclick="updateTimeFilter('weekly')"
                        class="flex-1 md:flex-none px-2 sm:px-4 md:px-6 py-2 text-[10px] sm:text-xs font-black rounded-lg sm:rounded-xl transition-all duration-300 time-filter-btn {{ $timeFilter === 'weekly' ? 'bg-primary-600 text-white shadow-lg shadow-primary-100' : 'text-slate-400 hover:text-primary-600 hover:bg-slate-50' }}"
                        data-filter="weekly">Weekly</button>
                    <button onclick="updateTimeFilter('monthly')"
                        class="flex-1 md:flex-none px-2 sm:px-4 md:px-6 py-2 text-[10px] sm:text-xs font-black rounded-lg sm:rounded-xl transition-all duration-300 time-filter-btn {{ $timeFilter === 'monthly' ? 'bg-primary-600 text-white shadow-lg shadow-primary-100' : 'text-slate-400 hover:text-primary-600 hover:bg-slate-50' }}"
                        data-filter="monthly">Monthly</button>
                    <button onclick="updateTimeFilter('all')"
                        class="flex-1 md:flex-none px-2 sm:px-4 md:px-6 py-2 text-[10px] sm:text-xs font-black rounded-lg sm:rounded-xl transition-all duration-300 time-filter-btn {{ $timeFilter === 'all' ? 'bg-primary-600 text-white shadow-lg shadow-primary-100' : 'text-slate-400 hover:text-primary-600 hover:bg-slate-50' }}"
                        data-filter="all">All Time</button>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="mb-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    @php
                        $onProcess = $statusDistribution->where('application_status', 'On Process')->first()->count ?? 0;
                        $rejectedCount = $funnelData['Rejected'] ?? 0;
                    @endphp

                    <!-- On Process Card -->
                    <div class="bento-card-stat mesh-gradient-blue rounded-[2rem] border border-slate-100 p-5 flex flex-col group relative overflow-hidden">
                        <div class="h-10 flex items-center justify-between mb-4">
                            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 transition-transform group-hover:scale-110">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <span class="text-[9px] font-black text-blue-600 uppercase tracking-[1.5px] bg-blue-50/50 px-2 py-0.5 rounded-full shrink-0">Active</span>
                        </div>
                        <div class="flex flex-col">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1 leading-none">On Process</p>
                            <p class="text-2xl font-black text-slate-900 tracking-tighter leading-none">{{ $onProcess }}</p>
                        </div>
                    </div>

                    <!-- Offering/Accepted Card -->
                    <div class="bento-card-stat mesh-gradient-emerald rounded-[2rem] border border-slate-100 p-5 flex flex-col group relative overflow-hidden">
                        <div class="h-10 flex items-center justify-between mb-4">
                            <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 transition-transform group-hover:scale-110">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-[9px] font-black text-emerald-600 uppercase tracking-[1.5px] bg-emerald-50/50 px-2 py-0.5 rounded-full shrink-0">Hired</span>
                        </div>
                        <div class="flex flex-col">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1 leading-none">Accepted</p>
                            <p class="text-2xl font-black text-slate-900 tracking-tighter leading-none">{{ $offeringAcceptedCount }}</p>
                        </div>
                    </div>

                    <!-- Declined Card -->
                    <div class="bento-card-stat mesh-gradient-rose rounded-[2rem] border border-slate-100 p-5 flex flex-col group relative overflow-hidden">
                        <div class="h-10 flex items-center justify-between mb-4">
                            <div class="w-10 h-10 bg-rose-50 rounded-xl flex items-center justify-center text-rose-600 transition-transform group-hover:scale-110">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <span class="text-[9px] font-black text-rose-600 uppercase tracking-[1.5px] bg-rose-50/50 px-2 py-0.5 rounded-full shrink-0">Closed</span>
                        </div>
                        <div class="flex flex-col">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1 leading-none">Rejected</p>
                            <p class="text-2xl font-black text-slate-900 tracking-tighter leading-none">{{ $rejectedCount }}</p>
                        </div>
                    </div>

                    <!-- Total Interviews Card -->
                    <div class="bento-card-stat mesh-gradient-orange rounded-[2rem] border border-slate-100 p-5 flex flex-col group relative overflow-hidden">
                        <div class="h-10 flex items-center justify-between mb-4">
                            <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600 transition-transform group-hover:scale-110">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <span class="text-[9px] font-black text-orange-600 uppercase tracking-[1.5px] bg-orange-50/50 px-2 py-0.5 rounded-full shrink-0">Meetings</span>
                        </div>
                        <div class="flex flex-col">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1 leading-none">Interviews</p>
                            <p class="text-2xl font-black text-slate-900 tracking-tighter leading-none">{{ $interviews }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Productivity Highlights --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mb-6 sm:mb-8">
                <div
                    class="bg-white border border-slate-200/60 rounded-xl sm:rounded-2xl p-3 sm:p-4 flex items-center justify-between hover:border-orange-600/50 transition-all group shadow-sm">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-2xl bg-orange-50 flex items-center justify-center text-orange-600">
                            <i class="ph-bold ph-fire text-xl sm:text-2xl"></i>
                        </div>
                        <div class="flex flex-col">
                            <span
                                class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1 sm:mb-1.5">Daily
                                Streak</span>
                            <div class="flex items-baseline gap-1.5 sm:gap-2">
                                <span
                                    class="text-xl sm:text-2xl font-black text-slate-900 leading-none">{{ $dailyStreak['current_streak'] }}</span>
                                <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase">/ Best
                                    {{ $dailyStreak['best_streak'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white border border-slate-200/60 rounded-xl sm:rounded-2xl p-3 sm:p-4 flex items-center justify-between hover:border-primary-600/50 transition-all group shadow-sm">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-2xl bg-primary-50 flex items-center justify-center text-primary-600">
                            <i class="ph-bold ph-target text-xl sm:text-2xl"></i>
                        </div>
                        <div class="flex flex-col">
                            <span
                                class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1 sm:mb-1.5">Weekly
                                Goal</span>
                            <div class="flex items-baseline gap-1.5 sm:gap-2">
                                <span
                                    class="text-xl sm:text-2xl font-black text-slate-900 leading-none">{{ $weeklyProgress['this_week_applications'] }}</span>
                                <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase">/
                                    {{ $weeklyProgress['weekly_goal'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white border border-slate-200/60 rounded-xl sm:rounded-2xl p-3 sm:p-4 flex items-center justify-between hover:border-teal-600/50 transition-all group shadow-sm">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-2xl bg-teal-50 flex items-center justify-center text-teal-600">
                            <i class="ph-bold ph-pulse text-xl sm:text-2xl"></i>
                        </div>
                        <div class="flex flex-col">
                            <span
                                class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1 sm:mb-1.5">Avg
                                Daily</span>
                            <div class="flex items-baseline gap-1.5 sm:gap-2">
                                <span
                                    class="text-xl sm:text-2xl font-black text-slate-900 leading-none">{{ $cadenceEffect['average_daily'] }}</span>
                                <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase">/
                                    {{ $cadenceEffect['consistency_score'] }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6 sm:space-y-8">
                {{-- Timeline Chart --}}
                <div
                    class="bg-white rounded-[2rem] sm:rounded-[2.5rem] shadow-sm border border-slate-200/60 overflow-hidden bento-card">
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 bg-slate-50/50">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div
                                class="w-10 h-10 sm:w-12 sm:h-12 bg-primary-50 rounded-xl sm:rounded-2xl flex items-center justify-center text-primary-600 shadow-inner shrink-0">
                                <i class="ph-duotone ph-chart-line text-xl sm:text-2xl"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight truncate">
                                    Timeline Activity</h3>
                                <p
                                    class="text-[9px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-0.5 sm:mt-1 truncate">
                                    Application trends and interview scheduling</p>
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
                <div
                    class="bg-white rounded-[2rem] sm:rounded-[2.5rem] shadow-sm border border-slate-200/60 overflow-hidden bento-card">
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 bg-slate-50/50">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div
                                class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-50 rounded-xl sm:rounded-2xl flex items-center justify-center text-purple-600 shadow-inner shrink-0">
                                <i class="ph-duotone ph-funnel text-xl sm:text-2xl"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight truncate">
                                    Application Funnel</h3>
                                <p
                                    class="text-[9px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-0.5 sm:mt-1 truncate">
                                    Recruitment stage progression mapping</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
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
                                    'Pending' => 'bg-slate-400'
                                ];
                                $flow = ['Applied', 'Interview', 'Accepted', 'Rejected', 'Pending'];
                            @endphp
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-3 sm:gap-4">
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
                                    <div class="bg-slate-50/50 p-3 sm:p-4 rounded-xl sm:rounded-3xl border border-slate-100">
                                        <div class="flex items-center justify-between mb-3 sm:mb-4">
                                            <div
                                                class="w-8 h-8 sm:w-10 sm:h-10 {{ $statusColors[$label] ?? 'bg-indigo-500' }} rounded-lg sm:rounded-xl flex items-center justify-center text-white">
                                                <i
                                                    class="ph-bold {{ $label === 'Applied' ? 'ph-paper-plane-tilt' : ($label === 'Interview' ? 'ph-chats-circle' : ($label === 'Accepted' ? 'ph-check-circle' : ($label === 'Rejected' ? 'ph-x-circle' : 'ph-clock'))) }} text-base sm:text-lg"></i>
                                            </div>
                                            <span
                                                class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase truncate ml-2">{{ $label }}</span>
                                        </div>
                                        <h4 class="text-2xl sm:text-3xl font-black text-slate-900">{{ number_format($count) }}
                                        </h4>
                                        <div class="w-full h-1 sm:h-1.5 bg-slate-200 rounded-full mt-3 sm:mt-4 overflow-hidden">
                                            <div class="h-full {{ $statusColors[$label] ?? 'bg-indigo-500' }}"
                                                style="width: {{ min(100, $conversionRate) }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="py-8 sm:py-12 text-center text-slate-400 font-bold text-sm">No data available</div>
                        @endif
                    </div>
                </div>

                {{-- Productivity Insights (Day of Week & Velocity) --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 pb-2">
                    <div
                        class="bg-white rounded-[2rem] shadow-sm border border-slate-200/60 overflow-hidden bento-card">
                        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 bg-slate-50/50">
                            <div class="flex items-center gap-3 sm:gap-4">
                                <div
                                    class="w-8 h-8 sm:w-10 sm:h-10 bg-indigo-50 rounded-lg sm:rounded-xl flex items-center justify-center text-indigo-600 shadow-inner shrink-0">
                                    <i class="ph-duotone ph-calendar-check text-lg sm:text-xl"></i>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-sm sm:text-base font-black text-slate-900 tracking-tight truncate">
                                        Day-of-the-Week Activity</h3>
                                    <p
                                        class="text-[8px] sm:text-[9px] font-bold text-slate-400 uppercase tracking-wider leading-none mt-1">
                                        Productivity distribution</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div class="relative h-64 sm:h-72 w-full">
                                <canvas id="dayOfWeekChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-[2rem] shadow-sm border border-slate-200/60 overflow-hidden bento-card">
                        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 bg-slate-50/50">
                            <div class="flex items-center gap-3 sm:gap-4">
                                <div
                                    class="w-8 h-8 sm:w-10 sm:h-10 bg-emerald-50 rounded-lg sm:rounded-xl flex items-center justify-center text-emerald-600 shadow-inner shrink-0">
                                    <i class="ph-duotone ph-speedometer text-lg sm:text-xl"></i>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-sm sm:text-base font-black text-slate-900 tracking-tight truncate">
                                        Application Velocity</h3>
                                    <p
                                        class="text-[8px] sm:text-[9px] font-bold text-slate-400 uppercase tracking-wider leading-none mt-1">
                                        Weekly speed trend</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div class="relative h-64 sm:h-72 w-full">
                                <canvas id="velocityChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- Advanced Analytics Grid --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                    <div
                        class="bg-white rounded-[2rem] shadow-sm border border-slate-200/60 overflow-hidden bento-card">
                        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 bg-slate-50/50">
                            <div class="flex items-center gap-3 sm:gap-4">
                                <div
                                    class="w-8 h-8 sm:w-10 sm:h-10 bg-indigo-50 rounded-lg sm:rounded-xl flex items-center justify-center text-indigo-600 shadow-inner shrink-0">
                                    <i class="ph-duotone ph-globe text-lg sm:text-xl"></i>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-sm sm:text-base font-black text-slate-900 tracking-tight truncate">
                                        Platform Effectiveness</h3>
                                    <p
                                        class="text-[8px] sm:text-[9px] font-bold text-slate-400 uppercase tracking-wider leading-none mt-1">
                                        Source distribution</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div class="relative h-64 sm:h-80 w-full">
                                <canvas id="platformChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-white rounded-[2rem] shadow-sm border border-slate-200/60 overflow-hidden bento-card">
                        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 bg-slate-50/50">
                            <div class="flex items-center gap-3 sm:gap-4">
                                <div
                                    class="w-8 h-8 sm:w-10 sm:h-10 bg-amber-50 rounded-lg sm:rounded-xl flex items-center justify-center text-amber-600 shadow-inner shrink-0">
                                    <i class="ph-duotone ph-stairs text-lg sm:text-xl"></i>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-sm sm:text-base font-black text-slate-900 tracking-tight truncate">
                                        Career Level</h3>
                                    <p
                                        class="text-[8px] sm:text-[9px] font-bold text-slate-400 uppercase tracking-wider leading-none mt-1">
                                        Job type analysis</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div class="relative h-64 sm:h-80 w-full">
                                <canvas id="careerLevelChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                    <div
                        class="bg-white rounded-[2rem] shadow-sm border border-slate-200/60 overflow-hidden bento-card">
                        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 bg-slate-50/50">
                            <div class="flex items-center gap-3 sm:gap-4">
                                <div
                                    class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-50 rounded-lg sm:rounded-xl flex items-center justify-center text-blue-600 shadow-inner shrink-0">
                                    <i class="ph-duotone ph-map-trifold text-lg sm:text-xl"></i>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-sm sm:text-base font-black text-slate-900 tracking-tight truncate">
                                        Province Demographics</h3>
                                    <p
                                        class="text-[8px] sm:text-[9px] font-bold text-slate-400 uppercase tracking-wider leading-none mt-1">
                                        Regional distribution</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div class="relative h-64 sm:h-80 w-full">
                                <canvas id="provinceChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-white rounded-[2rem] shadow-sm border border-slate-200/60 overflow-hidden bento-card">
                        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 bg-slate-50/50">
                            <div class="flex items-center gap-3 sm:gap-4">
                                <div
                                    class="w-8 h-8 sm:w-10 sm:h-10 bg-emerald-50 rounded-lg sm:rounded-xl flex items-center justify-center text-emerald-600 shadow-inner shrink-0">
                                    <i class="ph-duotone ph-buildings text-lg sm:text-xl"></i>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-sm sm:text-base font-black text-slate-900 tracking-tight truncate">
                                        City Demographics</h3>
                                    <p
                                        class="text-[8px] sm:text-[9px] font-bold text-slate-400 uppercase tracking-wider leading-none mt-1">
                                        Urban analysis</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div class="relative h-64 sm:h-80 w-full">
                                <canvas id="cityChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                    {{-- Top Companies Leaderboard --}}
                    <div
                        class="bg-white rounded-[2rem] shadow-sm border border-slate-200/60 overflow-hidden bento-card">
                        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 bg-slate-50/50">
                            <div class="flex items-center gap-3 sm:gap-4">
                                <div
                                    class="w-8 h-8 sm:w-10 sm:h-10 bg-rose-50 rounded-lg sm:rounded-xl flex items-center justify-center text-rose-600 shadow-inner shrink-0">
                                    <i class="ph-duotone ph-crown text-lg sm:text-xl"></i>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-sm sm:text-base font-black text-slate-900 tracking-tight truncate">
                                        Top Hiring Companies</h3>
                                    <p
                                        class="text-[8px] sm:text-[9px] font-bold text-slate-400 uppercase tracking-wider leading-none mt-1">
                                        Hiring leaderboard</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div class="space-y-4">
                                @forelse($topCompanies as $index => $company)
                                    <div
                                        class="flex items-center justify-between p-3 rounded-2xl bg-slate-50/50 border border-slate-100 group hover:bg-white hover:shadow-md hover:border-primary-100 transition-all duration-300">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-xs shrink-0 {{ ['bg-amber-100 text-amber-700', 'bg-slate-100 text-slate-700', 'bg-orange-100 text-orange-700'][$index] ?? 'bg-blue-50 text-blue-700' }}">
                                                {{ substr($company['company_name'], 0, 2) }}
                                            </div>
                                            <div class="flex flex-col min-w-0">
                                                <span
                                                    class="text-sm font-black text-slate-800 truncate">{{ $company['company_name'] }}</span>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <span
                                                        class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $company['applications'] }}
                                                        Apps</span>
                                                    @if($company['accepted'] > 0)
                                                        <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                                        <span
                                                            class="text-[10px] font-black text-emerald-600 uppercase">Offering</span>
                                                    @elseif($company['interviews'] > 0)
                                                        <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                                        <span
                                                            class="text-[10px] font-black text-amber-600 uppercase">Interviewing</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex flex-col items-end">
                                            <span
                                                class="text-xs font-black text-slate-900">{{ round(($company['applications'] / $applicationsCount) * 100) }}%</span>
                                            <span
                                                class="text-[8px] font-bold text-slate-400 uppercase tracking-tighter">Share</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="py-12 text-center text-slate-400 font-bold text-sm">No data available</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Popular Roles Chart --}}
                    <div
                        class="bg-white rounded-[2rem] shadow-sm border border-slate-200/60 overflow-hidden bento-card">
                        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 bg-slate-50/50">
                            <div class="flex items-center gap-3 sm:gap-4">
                                <div
                                    class="w-8 h-8 sm:w-10 sm:h-10 bg-violet-50 rounded-lg sm:rounded-xl flex items-center justify-center text-violet-600 shadow-inner shrink-0">
                                    <i class="ph-duotone ph-briefcase-metal text-lg sm:text-xl"></i>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-sm sm:text-base font-black text-slate-900 tracking-tight truncate">
                                        Popular Roles</h3>
                                    <p
                                        class="text-[8px] sm:text-[9px] font-bold text-slate-400 uppercase tracking-wider leading-none mt-1">
                                        Target position analysis</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div class="relative h-64 sm:h-80 w-full">
                                <canvas id="rolesChart"></canvas>
                            </div>
                        </div>
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