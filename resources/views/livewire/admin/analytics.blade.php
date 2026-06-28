<div class="space-y-5 w-full">
    <!-- Sticky Global Sub-Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-zinc-200/80">
        <div class="flex items-center gap-2.5 min-w-0">
            <span class="text-xs font-mono font-medium text-zinc-400">Admin</span>
            <span class="text-zinc-300">/</span>
            <h1 class="text-sm font-semibold tracking-tight text-zinc-900">Analytics</h1>
        </div>
        
        <div class="w-full sm:w-auto flex items-center gap-2">
            <div class="relative w-full sm:w-auto group">
                <select id="periodFilter" name="periodFilter" wire:model.live="periodFilter" 
                        class="appearance-none pl-3 pr-8 py-1.5 border border-zinc-200 rounded-md focus:ring-1 focus:ring-primary-600 focus:border-primary-600 transition-all font-semibold text-zinc-800 bg-zinc-50 cursor-pointer text-xs w-full sm:w-48 shadow-none">
                    <option value="all">Semua Waktu</option>
                    <option value="7">7 Hari Terakhir</option>
                    <option value="30">30 Hari Terakhir</option>
                    <option value="90">90 Hari Terakhir</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-2.5 pointer-events-none text-zinc-400">
                    <i class="ph-bold ph-caret-down text-xs"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Grid (Notion Premium Grid) --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">
        {{-- Total Users --}}
        <div class="bg-white rounded-lg border border-zinc-200/80 p-4 flex flex-col justify-between hover:bg-zinc-50/50 transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Total Users</p>
                    <h3 class="text-lg font-semibold tracking-tight text-zinc-900">{{ number_format($stats['totalUsers']) }}</h3>
                    <p class="text-[10px] text-zinc-400 mt-1">
                        @if($periodFilter === 'all')
                            Seluruh Data
                        @else
                            Dalam {{ $stats['periodDays'] }} hari
                        @endif
                    </p>
                </div>
                <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200/80 flex items-center justify-center text-zinc-650 shrink-0">
                    <i class="ph-bold ph-users-three text-base"></i>
                </div>
            </div>
        </div>

        {{-- Premium Users --}}
        <div class="bg-white rounded-lg border border-zinc-200/80 p-4 flex flex-col justify-between hover:bg-zinc-50/50 transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Premium Users</p>
                    <h3 class="text-lg font-semibold tracking-tight text-zinc-900">{{ number_format($stats['premiumUsers']) }}</h3>
                    <p class="text-[10px] text-zinc-400 mt-1">
                        @if($periodFilter === 'all')
                            Seluruh Data
                        @else
                            Dalam {{ $stats['periodDays'] }} hari
                        @endif
                    </p>
                </div>
                <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200/80 flex items-center justify-center text-zinc-650 shrink-0">
                    <i class="ph-bold ph-crown text-base"></i>
                </div>
            </div>
        </div>

        {{-- Active Users --}}
        <div class="bg-white rounded-lg border border-zinc-200/80 p-4 flex flex-col justify-between hover:bg-zinc-50/50 transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Active Users</p>
                    <h3 class="text-lg font-semibold tracking-tight text-zinc-900">{{ number_format($stats['activeUsers']) }}</h3>
                    <p class="text-[10px] text-zinc-400 mt-1">
                        @if($periodFilter === 'all')
                            Seluruh Waktu
                        @else
                            Dalam {{ $stats['periodDays'] }} hari
                        @endif
                    </p>
                </div>
                <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200/80 flex items-center justify-center text-zinc-650 shrink-0">
                    <i class="ph-bold ph-lightning text-base"></i>
                </div>
            </div>
        </div>

        {{-- CV Exports --}}
        <div class="bg-white rounded-lg border border-zinc-200/80 p-4 flex flex-col justify-between hover:bg-zinc-50/50 transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">CV Exports</p>
                    <h3 class="text-lg font-semibold tracking-tight text-zinc-900">{{ number_format($stats['totalExports']) }}</h3>
                    <p class="text-[10px] text-zinc-400 mt-1">Total Downloads</p>
                </div>
                <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200/80 flex items-center justify-center text-zinc-650 shrink-0">
                    <i class="ph-bold ph-download-simple text-base"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Stats Highlights --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- User Growth Insights --}}
        <div class="bg-white rounded-lg border border-zinc-200/80 p-4 flex flex-col justify-between">
            <div class="flex items-center gap-2 mb-4">
                <i class="ph-bold ph-users-three text-zinc-400 text-sm"></i>
                <h4 class="text-[9px] font-mono font-bold uppercase tracking-wider text-zinc-400">User Growth</h4>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Today</p>
                    <p class="text-lg font-semibold tracking-tight text-zinc-900">{{ number_format($stats['newUsersToday']) }}</p>
                </div>
                <div class="border-l border-zinc-150 pl-4">
                    <p class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Week / Month</p>
                    <p class="text-xs font-semibold text-zinc-700 mt-1">{{ number_format($stats['newUsersWeek']) }} <span class="text-zinc-300 mx-1">/</span> {{ number_format($stats['newUsersMonth']) }}</p>
                </div>
            </div>
        </div>

        {{-- Application Metrics --}}
        <div class="bg-white rounded-lg border border-zinc-200/80 p-4 flex flex-col justify-between">
            <div class="flex items-center gap-2 mb-4">
                <i class="ph-bold ph-briefcase text-zinc-400 text-sm"></i>
                <h4 class="text-[9px] font-mono font-bold uppercase tracking-wider text-zinc-400">App Metrics</h4>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Total Apps</p>
                    <p class="text-lg font-semibold tracking-tight text-zinc-900">{{ number_format($stats['totalJobApplications'] ?? 0) }}</p>
                </div>
                <div class="border-l border-zinc-150 pl-4">
                    <p class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Goals Rate</p>
                    <p class="text-xs font-semibold text-emerald-600 mt-1">{{ $stats['goalsAchievementRate'] ?? 0 }}%</p>
                    <p class="text-[9px] text-zinc-400">{{ number_format($stats['totalGoals'] ?? 0) }} goals</p>
                </div>
            </div>
        </div>

        {{-- Financial Performance --}}
        <div class="bg-white rounded-lg border border-zinc-200/80 p-4 flex flex-col justify-between">
            <div class="flex items-center gap-2 mb-4">
                <i class="ph-bold ph-wallet text-zinc-400 text-sm"></i>
                <h4 class="text-[9px] font-mono font-bold uppercase tracking-wider text-zinc-400">Financial</h4>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Revenue</p>
                    <p class="text-lg font-semibold tracking-tight text-zinc-900">Rp{{ number_format($stats['totalRevenue'] ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="border-l border-zinc-150 pl-4">
                    <p class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Avg / User</p>
                    <p class="text-xs font-semibold text-zinc-700 mt-1">
                        @if(($stats['premiumUsers'] ?? 0) > 0)
                            Rp{{ number_format(($stats['totalRevenue'] ?? 0) / $stats['premiumUsers'], 0, ',', '.') }}
                        @else
                            Rp 0
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Hero Chart: User Growth --}}
    <div class="bg-white rounded-lg border border-zinc-200/80 overflow-hidden">
        <div class="px-4 py-3 border-b border-zinc-150 bg-zinc-50/50">
            <div class="flex items-center gap-2">
                <i class="ph-bold ph-trend-up text-zinc-400 text-sm"></i>
                <div>
                    <h3 class="text-xs font-bold text-zinc-900">User Growth Trend</h3>
                    <p class="text-[9px] text-zinc-400 mt-0.5">Pertumbuhan pengguna dari waktu ke waktu</p>
                </div>
            </div>
        </div>
        <div class="p-4">
            <div class="h-80 w-full relative">
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>
    </div>

    {{-- 2-Column Grid Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- Applications Over Time --}}
        <div class="bg-white rounded-lg border border-zinc-200/80 overflow-hidden flex flex-col">
            <div class="px-4 py-3 border-b border-zinc-150 bg-zinc-50/50">
                <div class="flex items-center gap-2">
                    <i class="ph-bold ph-briefcase text-zinc-400 text-sm"></i>
                    <div>
                        <h3 class="text-xs font-bold text-zinc-900">Applications Timeline</h3>
                        <p class="text-[9px] text-zinc-400 mt-0.5">Aktivitas pelamaran kerja harian</p>
                    </div>
                </div>
            </div>
            <div class="p-4 flex-1">
                <div class="h-64 w-full relative">
                    <canvas id="jobApplicationsOverTimeChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Registrations by Day --}}
        <div class="bg-white rounded-lg border border-zinc-200/80 overflow-hidden flex flex-col">
            <div class="px-4 py-3 border-b border-zinc-150 bg-zinc-50/50">
                <div class="flex items-center gap-2">
                    <i class="ph-bold ph-user-plus text-zinc-400 text-sm"></i>
                    <div>
                        <h3 class="text-xs font-bold text-zinc-900">Registrations Timeline</h3>
                        <p class="text-[9px] text-zinc-400 mt-0.5">Pendaftaran pengguna harian</p>
                    </div>
                </div>
            </div>
            <div class="p-4 flex-1">
                <div class="h-64 w-full relative">
                    <canvas id="userRegistrationByDayChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- 3-Column Grid Donut Charts --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Premium vs Free --}}
        <div class="bg-white rounded-lg border border-zinc-200/80 overflow-hidden flex flex-col">
            <div class="px-4 py-3 border-b border-zinc-150 bg-zinc-50/50">
                <div class="flex items-center gap-2">
                    <i class="ph-bold ph-crown text-zinc-400 text-sm"></i>
                    <h3 class="text-xs font-bold text-zinc-900 truncate">Premium Ratio</h3>
                </div>
            </div>
            <div class="p-4 flex-1 flex flex-col items-center justify-center">
                <div class="h-44 w-full relative">
                    <canvas id="premiumVsFreeChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Goals Achievement --}}
        <div class="bg-white rounded-lg border border-zinc-200/80 overflow-hidden flex flex-col">
            <div class="px-4 py-3 border-b border-zinc-150 bg-zinc-50/50">
                <div class="flex items-center gap-2">
                    <i class="ph-bold ph-target text-zinc-400 text-sm"></i>
                    <h3 class="text-xs font-bold text-zinc-900 truncate">Goals Success</h3>
                </div>
            </div>
            <div class="p-4 flex-1 flex flex-col items-center justify-center">
                <div class="h-44 w-full relative">
                    <canvas id="goalsAchievementChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Verified vs Unverified --}}
        <div class="bg-white rounded-lg border border-zinc-200/80 overflow-hidden flex flex-col">
            <div class="px-4 py-3 border-b border-zinc-150 bg-zinc-50/50">
                <div class="flex items-center gap-2">
                    <i class="ph-bold ph-seal-check text-zinc-400 text-sm"></i>
                    <h3 class="text-xs font-bold text-zinc-900 truncate">Verification Ratio</h3>
                </div>
            </div>
            <div class="p-4 flex-1 flex flex-col items-center justify-center">
                <div class="h-44 w-full relative">
                    <canvas id="verifiedVsUnverifiedChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Full Width Application Funnel --}}
    <div class="bg-white rounded-lg border border-zinc-200/80 overflow-hidden flex flex-col">
        <div class="px-4 py-3 border-b border-zinc-150 bg-zinc-50/50">
            <div class="flex items-center gap-2">
                <i class="ph-bold ph-funnel text-zinc-400 text-sm"></i>
                <div>
                    <h3 class="text-xs font-bold text-zinc-900">Application Funnel</h3>
                    <p class="text-[9px] text-zinc-400 mt-0.5">Visualisasi alur konversi lamaran kerja dari awal sampai akhir</p>
                </div>
            </div>
        </div>
        <div class="p-4">
            @if(count($jobApplicationsByStatus['labels'] ?? []) > 0)
                @php
                    $appliedIdx = array_search('Applied', $jobApplicationsByStatus['labels']);
                    $totalApps = $appliedIdx !== false ? $jobApplicationsByStatus['data'][$appliedIdx] : 0;
                    
                    $interviewIdx = array_search('Interview', $jobApplicationsByStatus['labels']);
                    $interviewCount = $interviewIdx !== false ? $jobApplicationsByStatus['data'][$interviewIdx] : 0;
                    
                    $acceptedIdx = array_search('Accepted', $jobApplicationsByStatus['labels']);
                    $acceptedCount = $acceptedIdx !== false ? $jobApplicationsByStatus['data'][$acceptedIdx] : 0;
                    
                    $rejectedIdx = array_search('Rejected', $jobApplicationsByStatus['labels']);
                    $rejectedCount = $rejectedIdx !== false ? $jobApplicationsByStatus['data'][$rejectedIdx] : 0;
                    
                    $pendingIdx = array_search('Pending', $jobApplicationsByStatus['labels']);
                    $pendingCount = $pendingIdx !== false ? $jobApplicationsByStatus['data'][$pendingIdx] : 0;

                    $statusColors = [
                        'Applied' => 'bg-zinc-800',
                        'Interview' => 'bg-amber-500',
                        'Accepted' => 'bg-emerald-500',
                        'Rejected' => 'bg-red-500',
                        'Pending' => 'bg-zinc-400'
                    ];
                    $flow = ['Applied', 'Interview', 'Accepted', 'Rejected', 'Pending'];
                @endphp
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3.5">
                    @foreach($flow as $index => $label)
                        @php
                            if ($label === 'Applied') {
                                $count = $totalApps;
                                $conversionRate = $totalApps > 0 ? 100 : 0;
                                $isCritical = false;
                            } elseif ($label === 'Interview') {
                                $count = $interviewCount;
                                $conversionRate = $totalApps > 0 ? round(($interviewCount / $totalApps) * 100) : 0;
                                $isCritical = $totalApps > 0 && (100 - $conversionRate) > 70;
                            } elseif ($label === 'Accepted') {
                                $count = $acceptedCount;
                                $conversionRate = $interviewCount > 0 ? round(($acceptedCount / $interviewCount) * 100) : 0;
                                $isCritical = $interviewCount > 0 && (100 - $conversionRate) > 70;
                            } elseif ($label === 'Rejected') {
                                $count = $rejectedCount;
                                $conversionRate = $totalApps > 0 ? round(($rejectedCount / $totalApps) * 100) : 0;
                                $isCritical = $totalApps > 0 && $conversionRate > 70;
                            } else {
                                $count = $pendingCount;
                                $conversionRate = $totalApps > 0 ? round(($pendingCount / $totalApps) * 100) : 0;
                                $isCritical = false;
                            }
                        @endphp
                        
                        <div class="bg-white p-4 rounded-lg border {{ $isCritical ? 'border-red-200 bg-red-50/20' : 'border-zinc-200/80' }} transition-colors">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-7 h-7 {{ $statusColors[$label] }} rounded flex items-center justify-center text-white">
                                    <i class="ph-bold {{ $label === 'Applied' ? 'ph-paper-plane-tilt' : ($label === 'Interview' ? 'ph-chats-circle' : ($label === 'Accepted' ? 'ph-check-circle' : ($label === 'Rejected' ? 'ph-x-circle' : 'ph-clock'))) }} text-xs"></i>
                                </div>
                                <span class="text-[9px] font-mono font-bold {{ $isCritical ? 'text-red-600' : 'text-zinc-400' }} uppercase tracking-wider">{{ $label }}</span>
                            </div>

                            <div class="mb-2">
                                <h4 class="text-lg font-semibold tracking-tight text-zinc-900">{{ number_format($count) }}</h4>
                                <div class="flex items-center justify-between text-[9px] font-mono font-bold mt-0.5">
                                    <span class="text-zinc-450 uppercase tracking-wider">Rate</span>
                                    <span class="{{ $isCritical ? 'text-red-600' : 'text-emerald-600' }}">{{ $conversionRate }}%</span>
                                </div>
                            </div>

                            <div class="w-full h-1 bg-zinc-100 rounded-full overflow-hidden">
                                <div class="h-full {{ $statusColors[$label] }} transition-all" style="width: {{ $conversionRate }}%"></div>
                            </div>

                            @if($isCritical)
                                <div class="mt-2.5 pt-2.5 border-t border-red-100 flex items-start gap-1">
                                    <i class="ph-fill ph-warning-octagon text-red-500 text-xs mt-0.5"></i>
                                    <p class="text-[9px] font-bold text-red-700 leading-tight">
                                        @if($label === 'Rejected')
                                            Tingkat penolakan tinggi!
                                        @else
                                            Drop-off tinggi. Butuh bimbingan!
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-10 flex flex-col items-center justify-center text-zinc-400 border border-dashed border-zinc-200 rounded-lg bg-zinc-50/50">
                    <i class="ph-bold ph-folder-open text-2xl mb-2 text-zinc-300"></i>
                    <p class="text-xs font-semibold text-zinc-900">Belum ada data funnel tersedia</p>
                </div>
            @endif
        </div>
    </div>

    {{-- 2-Column Grid Targeted Companies / Positions --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- Top Companies Row --}}
        <div class="bg-white rounded-lg border border-zinc-200/80 overflow-hidden flex flex-col">
            <div class="px-4 py-3 border-b border-zinc-150 bg-zinc-50/50">
                <div class="flex items-center gap-2">
                    <i class="ph-bold ph-buildings text-zinc-400 text-sm"></i>
                    <h3 class="text-xs font-bold text-zinc-900 tracking-tight">Top Targeted Companies</h3>
                </div>
            </div>
            <div class="p-4 space-y-2 flex-1 overflow-y-auto custom-scrollbar">
                @forelse($topCompanies as $company)
                    <div class="flex items-center justify-between p-3 rounded-lg bg-zinc-50/50 border border-zinc-200/80 hover:bg-zinc-50 transition-colors">
                        <span class="text-xs font-medium text-zinc-700 truncate pr-4">{{ $company['company_name'] }}</span>
                        <span class="px-2.5 py-1 bg-white border border-zinc-200 text-zinc-600 rounded text-[9px] font-mono font-bold shrink-0">{{ $company['count'] }} Lamaran</span>
                    </div>
                @empty
                    <div class="py-10 text-center flex flex-col items-center justify-center border border-dashed border-zinc-200 rounded-lg bg-zinc-50/50">
                        <i class="ph-bold ph-folder-open text-2xl mb-1 text-zinc-300"></i>
                        <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Belum ada data perusahaan</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Top Positions Row --}}
        <div class="bg-white rounded-lg border border-zinc-200/80 overflow-hidden flex flex-col">
            <div class="px-4 py-3 border-b border-zinc-150 bg-zinc-50/50">
                <div class="flex items-center gap-2">
                    <i class="ph-bold ph-briefcase text-zinc-400 text-sm"></i>
                    <h3 class="text-xs font-bold text-zinc-900 tracking-tight">Most Wanted Positions</h3>
                </div>
            </div>
            <div class="p-4 space-y-2 flex-1 overflow-y-auto custom-scrollbar">
                @forelse($topPositions as $pos)
                    <div class="flex items-center justify-between p-3 rounded-lg bg-zinc-50/50 border border-zinc-200/80 hover:bg-zinc-50 transition-colors">
                        <span class="text-xs font-medium text-zinc-700 truncate pr-4">{{ $pos['position'] }}</span>
                        <span class="px-2.5 py-1 bg-white border border-zinc-200 text-zinc-600 rounded text-[9px] font-mono font-bold shrink-0">{{ $pos['count'] }} Peminat</span>
                    </div>
                @empty
                    <div class="py-10 text-center flex flex-col items-center justify-center border border-dashed border-zinc-200 rounded-lg bg-zinc-50/50">
                        <i class="ph-bold ph-folder-open text-2xl mb-1 text-zinc-300"></i>
                        <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Belum ada data posisi</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Applications By Platform --}}
    <div class="bg-white rounded-lg border border-zinc-200/80 overflow-hidden flex flex-col">
        <div class="px-4 py-3 border-b border-zinc-150 bg-zinc-50/50">
            <div class="flex items-center gap-2">
                <i class="ph-bold ph-chart-bar text-zinc-400 text-sm"></i>
                <div>
                    <h3 class="text-xs font-bold text-zinc-900">Platform Popularity</h3>
                    <p class="text-[9px] text-zinc-400 mt-0.5">Sumber lamaran paling banyak digunakan</p>
                </div>
            </div>
        </div>
        <div class="p-4 flex-1">
            @if(count($jobApplicationsByPlatform['labels'] ?? []) > 0)
                <div class="h-64 w-full relative">
                    <canvas id="jobApplicationsByPlatformChart"></canvas>
                </div>
            @else
                <div class="h-64 w-full flex flex-col items-center justify-center text-zinc-400 bg-zinc-50/50 rounded-lg border border-dashed border-zinc-200">
                    <i class="ph-bold ph-folder-open text-3xl mb-2 text-zinc-300"></i>
                    <p class="text-xs font-semibold text-zinc-900">Belum ada data platform</p>
                </div>
            @endif
        </div>
    </div>

    {{-- XP Leaderboard + Peak Activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- XP Leaderboard --}}
        <div class="bg-white rounded-lg border border-zinc-200/80 overflow-hidden flex flex-col">
            <div class="px-4 py-3 border-b border-zinc-150 bg-zinc-50/50">
                <div class="flex items-center gap-2">
                    <i class="ph-bold ph-trophy text-zinc-400 text-sm"></i>
                    <div>
                        <h3 class="text-xs font-bold text-zinc-900">XP Leaderboard</h3>
                        <p class="text-[9px] text-zinc-400 mt-0.5">Top 5 pengguna berdasarkan XP</p>
                    </div>
                </div>
            </div>
            <div class="p-4 flex-1">
                @if(count($gamificationLeaderboard) > 0)
                    <div class="space-y-2">
                        @foreach($gamificationLeaderboard as $player)
                            @php
                                $maxXp = $gamificationLeaderboard[0]['xp'] > 0 ? $gamificationLeaderboard[0]['xp'] : 1;
                                $barWidth = min(100, round(($player['xp'] / $maxXp) * 100));
                                $rankColors = match($player['rank']) {
                                    1 => 'bg-amber-50 text-amber-600 border border-amber-200/50',
                                    2 => 'bg-zinc-100 text-zinc-700 border border-zinc-200/50',
                                    3 => 'bg-orange-50 text-orange-700 border border-orange-200/50',
                                    default => 'bg-zinc-50 text-zinc-400 border border-zinc-100'
                                };
                                $barColor = match($player['rank']) {
                                    1 => 'bg-amber-400',
                                    2 => 'bg-zinc-400',
                                    3 => 'bg-orange-400',
                                    default => 'bg-zinc-300'
                                };
                            @endphp
                            <div class="flex items-center gap-3 px-3 py-2 rounded bg-zinc-50/50 border border-zinc-200/80 hover:bg-zinc-50 transition-colors">
                                <div class="w-6 h-6 rounded {{ $rankColors }} flex items-center justify-center text-[10px] font-mono font-bold shrink-0">
                                    {{ $player['rank'] }}
                                </div>

                                @if(!empty($player['logo']))
                                    <img src="{{ $player['avatar_url'] }}" alt="{{ $player['name'] }}" class="w-6.5 h-6.5 rounded-full object-cover border border-zinc-200 shrink-0">
                                @else
                                    <div class="w-6.5 h-6.5 rounded-full bg-zinc-150 flex items-center justify-center text-zinc-500 shrink-0">
                                        <i class="ph-bold ph-user text-xs"></i>
                                    </div>
                                @endif

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            <p class="text-xs font-semibold text-zinc-900 truncate leading-none">{{ $player['name'] }}</p>
                                            <p class="text-[9px] text-zinc-400 mt-1 font-mono uppercase tracking-wider">{{ $player['title'] }} · Lvl {{ $player['level'] }}</p>
                                        </div>
                                        <span class="text-xs font-semibold text-zinc-800 shrink-0">{{ number_format($player['xp']) }} <span class="text-[9px] font-mono font-bold text-zinc-400">XP</span></span>
                                    </div>
                                    <div class="mt-2 w-full bg-zinc-200/50 rounded-full h-1 overflow-hidden">
                                        <div class="h-full {{ $barColor }} rounded-full" style="width: {{ $barWidth }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-10 flex flex-col items-center justify-center border border-dashed border-zinc-200 rounded-lg">
                        <i class="ph-bold ph-trophy text-2xl text-zinc-300 mb-1"></i>
                        <p class="text-xs font-semibold text-zinc-900">Belum ada data leaderboard</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Peak Activity Hours --}}
        <div class="bg-white rounded-lg border border-zinc-200/80 overflow-hidden flex flex-col">
            <div class="px-4 py-3 border-b border-zinc-150 bg-zinc-50/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="ph-bold ph-clock text-zinc-400 text-sm"></i>
                        <div>
                            <h3 class="text-xs font-bold text-zinc-900">Peak Activity Hours</h3>
                            <p class="text-[9px] text-zinc-400 mt-0.5">Jam paling aktif pengguna di platform</p>
                        </div>
                    </div>
                    @if($peakActivityHours['peak_hour'] ?? false)
                        <div class="px-2 py-0.5 bg-zinc-100 border border-zinc-200 rounded text-right shrink-0">
                            <p class="text-[9px] font-mono font-bold text-zinc-700 leading-none">Peak: {{ $peakActivityHours['peak_hour'] }}</p>
                            <p class="text-[8px] font-mono text-zinc-400 mt-0.5 uppercase tracking-wider">{{ $peakActivityHours['peak_count'] }} Acts</p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="p-4 flex-1 flex flex-col">
                <div class="flex-1 min-h-[220px] w-full relative">
                    <canvas id="peakActivityChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- AI Usage Stats + Support Tickets --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- AI Usage --}}
        <div class="bg-white rounded-lg border border-zinc-200/80 overflow-hidden flex flex-col">
            <div class="px-4 py-3 border-b border-zinc-150 bg-zinc-50/50">
                <div class="flex items-center gap-2">
                    <i class="ph-bold ph-robot text-zinc-400 text-sm"></i>
                    <div>
                        <h3 class="text-xs font-bold text-zinc-900">AI Feature Usage</h3>
                        <p class="text-[9px] text-zinc-400 mt-0.5">Pemakaian fitur AI platform</p>
                    </div>
                </div>
            </div>
            <div class="p-4 flex-1 space-y-5">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <div class="p-3 bg-zinc-50 border border-zinc-200 rounded text-center">
                        <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Analyzer</p>
                        <p class="text-base font-semibold text-zinc-900 leading-none">{{ number_format($aiUsageStats['ai_analyzer'] ?? 0) }}</p>
                        <p class="text-[8px] font-mono text-zinc-400 mt-1 uppercase tracking-wider">Scans</p>
                    </div>
                    <div class="p-3 bg-zinc-50 border border-zinc-200 rounded text-center">
                        <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Cover Letter</p>
                        <p class="text-base font-semibold text-zinc-900 leading-none">{{ number_format($coverLetterStats['total'] ?? 0) }}</p>
                        <p class="text-[8px] font-mono text-zinc-400 mt-1 uppercase tracking-wider">Created</p>
                    </div>
                    <div class="p-3 bg-zinc-50 border border-zinc-200 rounded text-center">
                        <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">AI Photo</p>
                        <p class="text-base font-semibold text-zinc-900 leading-none">{{ number_format($aiUsageStats['ai_photos'] ?? 0) }}</p>
                        <p class="text-[8px] font-mono text-zinc-400 mt-1 uppercase tracking-wider">Photos</p>
                    </div>
                    <div class="p-3 bg-zinc-50 border border-zinc-200 rounded text-center">
                        <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">CV Gen</p>
                        <p class="text-base font-semibold text-zinc-900 leading-none">{{ number_format($aiUsageStats['ai_cv_gen'] ?? 0) }}</p>
                        <p class="text-[8px] font-mono text-zinc-400 mt-1 uppercase tracking-wider">Month</p>
                    </div>
                </div>
                
                {{-- Mini Trend Charts --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div>
                        <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-2">AI Analyzer — 7 Hari</p>
                        <div class="h-28 w-full relative">
                            <canvas id="aiUsageTrendChart"></canvas>
                        </div>
                    </div>
                    <div>
                        <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-2">AI Cover Letter — 14 Hari</p>
                        <div class="h-28 w-full relative">
                            <canvas id="coverLetterTrendChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Support Ticket Resolution --}}
        <div class="bg-white rounded-lg border border-zinc-200/80 overflow-hidden flex flex-col">
            <div class="px-4 py-3 border-b border-zinc-150 bg-zinc-50/50">
                <div class="flex items-center gap-2">
                    <i class="ph-bold ph-headset text-zinc-400 text-sm"></i>
                    <div>
                        <h3 class="text-xs font-bold text-zinc-900">Support Ticket Resolution</h3>
                        <p class="text-[9px] text-zinc-400 mt-0.5">Status & performa penyelesaian tiket</p>
                    </div>
                </div>
            </div>
            <div class="p-4 flex-1 space-y-4">
                <div class="grid grid-cols-2 gap-2">
                    <div class="p-3 bg-zinc-50 border border-zinc-200 rounded flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded bg-zinc-200/60 text-zinc-650 flex items-center justify-center shrink-0">
                            <i class="ph-bold ph-ticket text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider leading-none mb-1">Total Tiket</p>
                            <p class="text-base font-semibold text-zinc-900 leading-none">{{ number_format($supportTicketStats['total'] ?? 0) }}</p>
                        </div>
                    </div>
                    <div class="p-3 bg-zinc-50 border border-zinc-200 rounded flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded bg-zinc-200/60 text-zinc-650 flex items-center justify-center shrink-0">
                            <i class="ph-bold ph-timer text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider leading-none mb-1">Avg Respon</p>
                            <p class="text-base font-semibold text-zinc-900 leading-none">{{ $supportTicketStats['avg_resolution_hrs'] ?? 0 }}<span class="text-[10px] text-zinc-400 ml-0.5">h</span></p>
                        </div>
                    </div>
                </div>
                
                {{-- Status Bar List --}}
                <div class="space-y-1.5">
                    @php
                        $tTotal = $supportTicketStats['total'] ?? 1;
                        $tTotal = max($tTotal, 1);
                    @endphp
                    <div class="flex items-center justify-between bg-zinc-50/50 px-3 py-1.5 rounded border border-zinc-200">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                            <span class="text-[10px] font-semibold text-zinc-600">Pending</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-24 sm:w-32 h-1.5 bg-zinc-150 rounded-full overflow-hidden">
                                <div class="h-full bg-amber-400 rounded-full" style="width: {{ round(($supportTicketStats['pending'] ?? 0) / $tTotal * 100) }}%"></div>
                            </div>
                            <span class="text-[10px] font-mono font-bold text-zinc-950 w-5 text-right">{{ $supportTicketStats['pending'] ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between bg-zinc-50/50 px-3 py-1.5 rounded border border-zinc-200">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-blue-400"></div>
                            <span class="text-[10px] font-semibold text-zinc-600">Replied</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-24 sm:w-32 h-1.5 bg-zinc-150 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-400 rounded-full" style="width: {{ round(($supportTicketStats['replied'] ?? 0) / $tTotal * 100) }}%"></div>
                            </div>
                            <span class="text-[10px] font-mono font-bold text-zinc-950 w-5 text-right">{{ $supportTicketStats['replied'] ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between bg-zinc-50/50 px-3 py-1.5 rounded border border-zinc-200">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                            <span class="text-[10px] font-semibold text-zinc-650">Resolved</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-24 sm:w-32 h-1.5 bg-zinc-150 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-500 rounded-full" style="width: {{ round(($supportTicketStats['resolved'] ?? 0) / $tTotal * 100) }}%"></div>
                            </div>
                            <span class="text-[10px] font-mono font-bold text-zinc-950 w-5 text-right">{{ $supportTicketStats['resolved'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
                
                @if(count($supportTicketStats['by_category'] ?? []) > 0)
                    <div class="pt-3 border-t border-zinc-150">
                        <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-2">By Category</p>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($supportTicketStats['by_category'] as $cat)
                                <span class="px-2 py-0.5 bg-zinc-50 border border-zinc-200 text-zinc-650 text-[9px] font-semibold rounded">
                                    {{ str_replace('_', ' ', $cat['category']) }} ({{ $cat['count'] }})
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Retention + Template Popularity --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- Retention Cohort --}}
        <div class="bg-white rounded-lg border border-zinc-200/80 overflow-hidden flex flex-col">
            <div class="px-4 py-3 border-b border-zinc-150 bg-zinc-50/50">
                <div class="flex items-center gap-2">
                    <i class="ph-bold ph-arrow-counter-clockwise text-zinc-400 text-sm"></i>
                    <div>
                        <h3 class="text-xs font-bold text-zinc-900">User Retention Cohort</h3>
                        <p class="text-[9px] text-zinc-400 mt-0.5">Berapa % user kembali bulan berikutnya</p>
                    </div>
                </div>
            </div>
            <div class="p-4 flex-1 space-y-2">
                @forelse($retentionCohort as $cohort)
                    <div class="flex items-center gap-3 p-1.5 hover:bg-zinc-50 rounded transition-colors">
                        <div class="w-16 shrink-0 bg-zinc-100 border border-zinc-200/60 text-zinc-600 py-0.5 rounded text-[9px] font-mono font-bold uppercase text-center">
                            {{ $cohort['month'] }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-0.5">
                                <p class="text-[9px] text-zinc-400 font-mono">{{ $cohort['retained'] }}/{{ $cohort['total'] }} retained</p>
                                <p class="text-[9px] font-mono font-bold {{ $cohort['rate'] >= 50 ? 'text-emerald-600' : ($cohort['rate'] >= 25 ? 'text-amber-600' : 'text-red-500') }}">{{ $cohort['rate'] }}%</p>
                            </div>
                            <div class="w-full h-1 bg-zinc-100 rounded-full overflow-hidden">
                                @php $retColor = $cohort['rate'] >= 50 ? 'bg-emerald-400' : ($cohort['rate'] >= 25 ? 'bg-amber-400' : 'bg-red-450'); @endphp
                                <div class="h-full {{ $retColor }} rounded-full" style="width: {{ $cohort['rate'] }}%"></div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-10 flex flex-col items-center justify-center border border-dashed border-zinc-200 rounded-lg">
                        <i class="ph-bold ph-chart-pie-slice text-2xl text-zinc-300 mb-1"></i>
                        <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Belum ada data retensi</p>
                    </div>
                @endforelse
                <div class="pt-2.5 border-t border-zinc-150">
                    <p class="text-[9px] text-zinc-400">* % user yang mendaftar bulan X dan kembali aktif di bulan X+1</p>
                </div>
            </div>
        </div>

        {{-- CV Template Popularity --}}
        <div class="bg-white rounded-lg border border-zinc-200/80 overflow-hidden flex flex-col">
            <div class="px-4 py-3 border-b border-zinc-150 bg-zinc-50/50">
                <div class="flex items-center gap-2">
                    <i class="ph-bold ph-file-doc text-zinc-400 text-sm"></i>
                    <div>
                        <h3 class="text-xs font-bold text-zinc-900">CV Template Popularity</h3>
                        <p class="text-[9px] text-zinc-400 mt-0.5">Template mana yang paling sering dipilih</p>
                    </div>
                </div>
            </div>
            <div class="p-4 flex-1">
                @if(count($cvTemplatePopularity['templates'] ?? []) > 0)
                    @php $maxUsage = max(array_column($cvTemplatePopularity['templates'], 'usage_count') ?: [1]); @endphp
                    <div class="space-y-2">
                        @foreach($cvTemplatePopularity['templates'] as $i => $tpl)
                            @php
                                $tplColors = ['bg-rose-450','bg-orange-400','bg-amber-400','bg-emerald-400','bg-blue-400','bg-purple-400'];
                                $tplColor = $tplColors[$i] ?? 'bg-zinc-400';
                                $tplWidth = $maxUsage > 0 ? round(($tpl['usage_count'] / $maxUsage) * 100) : 0;
                            @endphp
                            <div class="flex items-center gap-3 p-1.5 hover:bg-zinc-50 rounded transition-colors">
                                <div class="w-6 h-6 rounded {{ $tplColor }} bg-opacity-10 {{ str_replace('bg-', 'text-', $tplColor) }} flex items-center justify-center shrink-0">
                                    <i class="ph-bold ph-file-text text-xs"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-0.5">
                                        <p class="text-xs font-semibold text-zinc-800 truncate capitalize">{{ str_replace(['_', '-'], ' ', $tpl['template_key']) }}</p>
                                        <p class="text-xs font-bold text-zinc-900 shrink-0 ml-2">{{ $tpl['usage_count'] }}</p>
                                    </div>
                                    <div class="w-full h-1 bg-zinc-100 rounded-full overflow-hidden">
                                        <div class="h-full {{ $tplColor }} rounded-full" style="width: {{ $tplWidth }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 pt-3 border-t border-zinc-150 flex items-center justify-between">
                        <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Total CV Dibuat</p>
                        <p class="text-xs font-bold text-zinc-950">{{ number_format($cvTemplatePopularity['total'] ?? 0) }}</p>
                    </div>
                @else
                    <div class="h-full flex flex-col items-center justify-center py-10 border border-dashed border-zinc-200 rounded-lg">
                        <i class="ph-bold ph-file-doc text-2xl text-zinc-300 mb-2"></i>
                        <p class="text-xs font-semibold text-zinc-900">Belum ada data template</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

    <!-- Custom Chart Initialization -->
    <script>
        Chart.defaults.font.family = "'Inter', 'Segoe UI', 'Roboto', sans-serif";
        Chart.defaults.color = '#71717a'; // zinc-400
        Chart.defaults.scale.grid.color = '#f4f4f5'; // zinc-100
        Chart.defaults.plugins.tooltip.backgroundColor = '#18181b'; // zinc-900
        Chart.defaults.plugins.tooltip.titleColor = '#ffffff';
        Chart.defaults.plugins.tooltip.bodyColor = '#f4f4f5';
        Chart.defaults.plugins.tooltip.padding = 10;
        Chart.defaults.plugins.tooltip.cornerRadius = 6;
        
        let charts = {
            userGrowth: null,
            jobAppsTime: null,
            userRegDay: null,
            jobAppsStatus: null,
            jobAppsPlatform: null,
            premiumVsFree: null,
            goalsAchieve: null,
            verifiedUnverified: null,
            aiUsageTrend: null,
            coverLetterTrend: null,
            peakActivity: null,
        };

        function createGradient(ctx, colorStart, colorEnd) {
            if (!ctx) return colorStart;
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, colorStart);
            gradient.addColorStop(1, colorEnd);
            return gradient;
        }

        function initCharts() {
            Object.keys(charts).forEach(key => {
                if (charts[key]) charts[key].destroy();
            });

            const lineOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#18181b',
                        titleFont: { size: 11, weight: 'bold' },
                        bodyFont: { size: 10 },
                        padding: 8,
                        displayColors: true,
                        boxPadding: 4,
                        borderColor: '#e4e4e7',
                        borderWidth: 1
                    }
                },
                scales: {
                    x: { 
                        grid: { display: false },
                        ticks: { font: { size: 9, weight: 'bold' }, color: '#71717a' },
                        border: { display: false }
                    },
                    y: { 
                        beginAtZero: true, 
                        grid: { color: '#f4f4f5' },
                        ticks: { font: { size: 9, weight: 'bold' }, color: '#71717a', padding: 8 },
                        border: { display: false }
                    }
                }
            };

            // 1. User Growth (Line)
            const ugCanvas = document.getElementById('userGrowthChart');
            if (ugCanvas) {
                const ctx = ugCanvas.getContext('2d');
                const data = @json($userGrowth);
                charts.userGrowth = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels || [],
                        datasets: [{
                            label: 'Total Users',
                            data: data.total || [],
                            borderColor: '#18181b', // Dark Gray
                            backgroundColor: createGradient(ctx, 'rgba(24,24,27,0.06)', 'rgba(24,24,27,0)'),
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 0,
                            pointHoverRadius: 4,
                            pointHoverBackgroundColor: '#18181b',
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 2,
                        }]
                    },
                    options: lineOptions
                });
            }

            // 2. Job Apps Over Time
            const jtCanvas = document.getElementById('jobApplicationsOverTimeChart');
            if (jtCanvas) {
                const ctx = jtCanvas.getContext('2d');
                const data = @json($jobApplicationsOverTime);
                charts.jobAppsTime = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels || [],
                        datasets: [{
                            label: 'Applications',
                            data: data.data || [],
                            borderColor: '#3b82f6', // Blue
                            backgroundColor: createGradient(ctx, 'rgba(59,130,246,0.06)', 'rgba(59,130,246,0)'),
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 0,
                            pointHoverRadius: 4,
                            pointHoverBackgroundColor: '#3b82f6',
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 2,
                        }]
                    },
                    options: lineOptions
                });
            }

            // 3. Registrations by Day
            const urCanvas = document.getElementById('userRegistrationByDayChart');
            if (urCanvas) {
                const ctx = urCanvas.getContext('2d');
                const data = @json($userRegistrationByDay);
                charts.userRegDay = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels || [],
                        datasets: [{
                            label: 'Registrations',
                            data: data.data || [],
                            borderColor: '#10b981', // Emerald
                            backgroundColor: createGradient(ctx, 'rgba(16,185,129,0.06)', 'rgba(16,185,129,0)'),
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 0,
                            pointHoverRadius: 4,
                            pointHoverBackgroundColor: '#10b981',
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 2,
                        }]
                    },
                    options: lineOptions
                });
            }

            // 4. Premium vs Free (Doughnut)
            const pfCanvas = document.getElementById('premiumVsFreeChart');
            if (pfCanvas) {
                const data = @json($stats);
                charts.premiumVsFree = new Chart(pfCanvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Premium', 'Free'],
                        datasets: [{
                            data: [data.premiumUsers || 0, data.freeUsers || 0],
                            backgroundColor: ['#a855f7', '#e4e4e7'],
                            borderWidth: 1,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '75%',
                        plugins: {
                            legend: { display: false },
                            tooltip: { backgroundColor: '#18181b', padding: 8, cornerRadius: 4 }
                        }
                    }
                });
            }

            // 5. Goals Achievement (Doughnut)
            const gaCanvas = document.getElementById('goalsAchievementChart');
            if (gaCanvas) {
                const data = @json($stats);
                const rate = data.goalsAchievementRate || 0;
                charts.goalsAchieve = new Chart(gaCanvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Achieved', 'Remaining'],
                        datasets: [{
                            data: [rate, 100 - rate],
                            backgroundColor: ['#10b981', '#e4e4e7'],
                            borderWidth: 1,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '75%',
                        plugins: {
                            legend: { display: false },
                            tooltip: { backgroundColor: '#18181b', padding: 8, cornerRadius: 4 }
                        }
                    }
                });
            }

            // 6. Verified Ratio (Doughnut)
            const vuCanvas = document.getElementById('verifiedVsUnverifiedChart');
            if (vuCanvas) {
                const data = @json($stats);
                const total = data.totalUsers || 1;
                const ver = data.verifiedUsers || 0;
                charts.verifiedUnverified = new Chart(vuCanvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Verified', 'Unverified'],
                        datasets: [{
                            data: [ver, total - ver],
                            backgroundColor: ['#3b82f6', '#e4e4e7'],
                            borderWidth: 1,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '75%',
                        plugins: {
                            legend: { display: false },
                            tooltip: { backgroundColor: '#18181b', padding: 8, cornerRadius: 4 }
                        }
                    }
                });
            }

            // 7. Platform Popularity (Bar)
            const jpCanvas = document.getElementById('jobApplicationsByPlatformChart');
            if (jpCanvas) {
                const ctx = jpCanvas.getContext('2d');
                const data = @json($jobApplicationsByPlatform);
                charts.jobAppsPlatform = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels || [],
                        datasets: [{
                            data: data.data || [],
                            backgroundColor: '#3b82f6',
                            borderRadius: 4,
                            barThickness: 16
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { grid: { display: false }, ticks: { font: { size: 9, weight: 'bold' }, color: '#71717a' }, border: { display: false } },
                            y: { grid: { color: '#f4f4f5' }, ticks: { font: { size: 9, weight: 'bold' }, color: '#71717a' }, border: { display: false } }
                        }
                    }
                });
            }

            // 8. AI Usage Trend
            const aiCanvas = document.getElementById('aiUsageTrendChart');
            if (aiCanvas) {
                const ctx = aiCanvas.getContext('2d');
                const data = @json($aiUsageStats);
                charts.aiUsageTrend = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.trend_labels || [],
                        datasets: [{
                            data: data.trend || [],
                            borderColor: '#a855f7',
                            backgroundColor: createGradient(ctx, 'rgba(168,85,247,0.06)', 'rgba(168,85,247,0)'),
                            borderWidth: 1.5,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { grid: { display: false }, ticks: { display: false }, border: { display: false } },
                            y: { grid: { display: false }, ticks: { display: false }, border: { display: false } }
                        }
                    }
                });
            }

            // 9. Cover Letter Trend
            const clCanvas = document.getElementById('coverLetterTrendChart');
            if (clCanvas) {
                const ctx = clCanvas.getContext('2d');
                const data = @json($coverLetterStats);
                charts.coverLetterTrend = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.trend_labels || [],
                        datasets: [{
                            data: data.trend || [],
                            borderColor: '#10b981',
                            backgroundColor: createGradient(ctx, 'rgba(16,185,129,0.06)', 'rgba(16,185,129,0)'),
                            borderWidth: 1.5,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { grid: { display: false }, ticks: { display: false }, border: { display: false } },
                            y: { grid: { display: false }, ticks: { display: false }, border: { display: false } }
                        }
                    }
                });
            }

            // 10. Peak Activity Hours (Muted Gray Bar Heatmap)
            const paCanvas = document.getElementById('peakActivityChart');
            if (paCanvas) {
                const ctx = paCanvas.getContext('2d');
                const data = @json($peakActivityHours);
                const rawData = data.data || [];
                const maxVal = Math.max(...rawData, 1);
                const bgColors = rawData.map(v => {
                    const intensity = v / maxVal;
                    return `rgba(39, 39, 42, ${0.1 + intensity * 0.7})`; // Zinc-800 heatmap intensity
                });
                charts.peakActivity = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels || [],
                        datasets: [{
                            data: rawData,
                            backgroundColor: bgColors,
                            borderRadius: 2,
                            barThickness: 'flex'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: {
                                    font: { size: 8, weight: 'bold' },
                                    color: '#71717a',
                                    maxRotation: 0,
                                    callback: (val, idx) => idx % 4 === 0 ? (data.labels || [])[idx] : ''
                                },
                                border: { display: false }
                            },
                            y: { display: false }
                        }
                    }
                });
            }
        }

        document.addEventListener('livewire:init', () => {
            Livewire.on('chartUpdated', () => setTimeout(initCharts, 100));
            Livewire.on('$refresh', () => setTimeout(initCharts, 100));
        });

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => setTimeout(initCharts, 100));
        } else {
            setTimeout(initCharts, 100);
        }
    </script>
</div>
