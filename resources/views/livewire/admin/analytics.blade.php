<div class="space-y-6 sm:space-y-8 w-full">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 sm:mb-8">
            <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white border border-slate-200/60 rounded-[1.25rem] sm:rounded-[1.5rem] flex items-center justify-center text-primary-600 shadow-sm shrink-0">
                    <i class="ph-duotone ph-chart-line-up text-xl sm:text-2xl"></i>
                </div>
                <div class="flex flex-col min-w-0">
                    <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight truncate">Analytics Dashboard</h3>
                    <p class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mt-1 sm:mt-1.5 truncate">Tinjauan performa platform</p>
                </div>
            </div>
            
            <div class="w-full md:w-auto flex items-center gap-3">
                <div class="relative w-full sm:w-auto group">
                    <select id="periodFilter" name="periodFilter" wire:model.live="periodFilter" class="appearance-none pl-4 pr-10 py-2.5 sm:py-3 border border-slate-200/60 rounded-xl sm:rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-bold text-slate-700 bg-white cursor-pointer text-xs sm:text-sm w-full sm:w-48 shadow-sm">
                        <option value="all">Semua Waktu</option>
                        <option value="7">7 Hari Terakhir</option>
                        <option value="30">30 Hari Terakhir</option>
                        <option value="90">90 Hari Terakhir</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 sm:pr-4 pointer-events-none text-slate-400 group-focus-within:text-primary-500 transition-colors">
                        <i class="ph-bold ph-caret-down text-sm"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Grid (Bento Grid) --}}
        {{-- Stats Grid (Bento Grid) --}}
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6">
            {{-- Total Users --}}
            <div class="bento-card-stat mesh-gradient-primary rounded-[2rem] border border-slate-100 p-5 flex flex-col group relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] sm:text-xs font-black text-slate-400 uppercase tracking-widest mb-1 sm:mb-2">Total Users</p>
                        <h3 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ number_format($stats['totalUsers']) }}</h3>
                        <p class="text-[10px] font-bold text-slate-400 mt-1">
                            @if($periodFilter === 'all')
                                Seluruh Data
                            @else
                                Dalam {{ $stats['periodDays'] }} hari
                            @endif
                        </p>
                    </div>
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-[1rem] sm:rounded-[1.25rem] bg-indigo-50/80 border border-indigo-100 flex items-center justify-center text-indigo-600 shadow-sm shrink-0">
                        <i class="ph-duotone ph-users-three text-xl sm:text-2xl"></i>
                    </div>
                </div>
            </div>

            {{-- Premium Users --}}
            <div class="bento-card-stat mesh-gradient-amber rounded-[2rem] border border-slate-100 p-5 flex flex-col group relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] sm:text-xs font-black text-amber-500 uppercase tracking-widest mb-1 sm:mb-2">Premium Users</p>
                        <h3 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ number_format($stats['premiumUsers']) }}</h3>
                        <p class="text-[10px] font-bold text-amber-500/70 mt-1">
                            @if($periodFilter === 'all')
                                Seluruh Data
                            @else
                                Dalam {{ $stats['periodDays'] }} hari
                            @endif
                        </p>
                    </div>
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-[1rem] sm:rounded-[1.25rem] bg-amber-50/80 border border-amber-100 flex items-center justify-center text-amber-600 shadow-sm shrink-0">
                        <i class="ph-duotone ph-crown-simple text-xl sm:text-2xl"></i>
                    </div>
                </div>
            </div>

            {{-- Active Users --}}
            <div class="bento-card-stat mesh-gradient-pink rounded-[2rem] border border-slate-100 p-5 flex flex-col group relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] sm:text-xs font-black text-pink-500 uppercase tracking-widest mb-1 sm:mb-2">Active Users</p>
                        <h3 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ number_format($stats['activeUsers']) }}</h3>
                        <p class="text-[10px] font-bold text-pink-500/70 mt-1">
                            @if($periodFilter === 'all')
                                Seluruh Waktu
                            @else
                                Dalam {{ $stats['periodDays'] }} hari
                            @endif
                        </p>
                    </div>
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-[1rem] sm:rounded-[1.25rem] bg-pink-50/80 border border-pink-100 flex items-center justify-center text-pink-600 shadow-sm shrink-0">
                        <i class="ph-duotone ph-lightning text-xl sm:text-2xl"></i>
                    </div>
                </div>
            </div>

            {{-- CV Exports --}}
            <div class="bento-card-stat mesh-gradient-emerald rounded-[2rem] border border-slate-100 p-5 flex flex-col group relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] sm:text-xs font-black text-emerald-500 uppercase tracking-widest mb-1 sm:mb-2">CV Exports</p>
                        <h3 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ number_format($stats['totalExports']) }}</h3>
                        <p class="text-[10px] font-bold text-emerald-500/70 mt-1">Total Downloads</p>
                    </div>
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-[1rem] sm:rounded-[1.25rem] bg-emerald-50/80 border border-emerald-100 flex items-center justify-center text-emerald-600 shadow-sm shrink-0">
                        <i class="ph-duotone ph-download-simple text-xl sm:text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Stats Highlights --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
            {{-- User Growth Insights --}}
            <div class="bento-card-stat bg-white rounded-[2rem] p-6 border border-slate-200/60 flex flex-col relative overflow-hidden">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center shadow-sm shrink-0">
                        <i class="ph-bold ph-users-three text-lg"></i>
                    </div>
                    <h4 class="text-xs font-black uppercase tracking-widest text-slate-500">User Growth</h4>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Today</p>
                        <p class="text-2xl font-black text-slate-900 tracking-tight">{{ number_format($stats['newUsersToday']) }}</p>
                    </div>
                    <div class="border-l border-slate-100 pl-4">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Week/Month</p>
                        <p class="text-base font-black text-slate-700">{{ number_format($stats['newUsersWeek']) }} <span class="text-slate-300 mx-1">/</span> {{ number_format($stats['newUsersMonth']) }}</p>
                    </div>
                </div>
            </div>

            {{-- Application Metrics --}}
            <div class="bento-card-stat bg-white rounded-[2rem] p-6 border border-slate-200/60 flex flex-col relative overflow-hidden">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center shadow-sm shrink-0">
                        <i class="ph-bold ph-briefcase text-lg"></i>
                    </div>
                    <h4 class="text-xs font-black uppercase tracking-widest text-slate-500">App Metrics</h4>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Apps</p>
                        <p class="text-2xl font-black text-slate-900 tracking-tight">{{ number_format($stats['totalJobApplications'] ?? 0) }}</p>
                    </div>
                    <div class="border-l border-slate-100 pl-4">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Goals Rate</p>
                        <p class="text-base font-black text-emerald-600">{{ $stats['goalsAchievementRate'] ?? 0 }}%</p>
                        <p class="text-[9px] font-bold text-slate-400 mt-0.5">{{ number_format($stats['totalGoals'] ?? 0) }} goals</p>
                    </div>
                </div>
            </div>

            {{-- Financial Performance --}}
            <div class="bento-card-stat bg-white rounded-[2rem] p-6 border border-slate-200/60 flex flex-col relative overflow-hidden">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center shadow-sm shrink-0">
                        <i class="ph-bold ph-wallet text-lg"></i>
                    </div>
                    <h4 class="text-xs font-black uppercase tracking-widest text-slate-500">Financial</h4>
                </div>
                <div class="grid grid-cols-1 gap-3">
                    <div class="flex items-center justify-between">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Revenue</p>
                        <p class="text-lg font-black text-slate-900">Rp{{ number_format($stats['totalRevenue'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Avg/User</p>
                        <p class="text-sm font-black text-amber-600">
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
        <div class="bg-white rounded-[2rem] sm:rounded-[2.5rem] border border-slate-200/60 bento-card overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-indigo-50 border border-indigo-100 rounded-[1rem] sm:rounded-[1.25rem] flex items-center justify-center text-indigo-600 shadow-sm shrink-0">
                        <i class="ph-duotone ph-trend-up text-xl sm:text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900 tracking-tight">User Growth Trend</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Pertumbuhan pengguna dari waktu ke waktu</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="h-80 w-full relative">
                    <canvas id="userGrowthChart"></canvas>
                </div>
            </div>
        </div>

        {{-- 2-Column Grid Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            {{-- Applications Over Time --}}
            <div class="bg-white rounded-[2rem] border border-slate-200/60 bento-card overflow-hidden flex flex-col">
                <div class="px-6 py-5 border-b border-slate-100 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 border border-blue-100 rounded-[1rem] sm:rounded-[1.25rem] flex items-center justify-center text-blue-600 shadow-sm shrink-0">
                            <i class="ph-duotone ph-briefcase text-xl sm:text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900 tracking-tight">Applications Timeline</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Aktivitas pelamaran kerja harian</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 flex-1">
                    <div class="h-64 w-full relative">
                        <canvas id="jobApplicationsOverTimeChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Registrations by Day --}}
            <div class="bg-white rounded-[2rem] border border-slate-200/60 bento-card overflow-hidden flex flex-col">
                <div class="px-6 py-5 border-b border-slate-100 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-emerald-50 border border-emerald-100 rounded-[1rem] sm:rounded-[1.25rem] flex items-center justify-center text-emerald-600 shadow-sm shrink-0">
                            <i class="ph-duotone ph-user-plus text-xl sm:text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900 tracking-tight">Registrations Timeline</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Pendaftaran pengguna harian</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 flex-1">
                    <div class="h-64 w-full relative">
                        <canvas id="userRegistrationByDayChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3-Column Grid Donut Charts --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
            {{-- Premium vs Free --}}
            <div class="bg-white rounded-[2rem] border border-slate-200/60 bento-card overflow-hidden flex flex-col">
                <div class="px-6 py-5 border-b border-slate-100 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-50 rounded-xl border border-amber-100 flex items-center justify-center text-amber-600 shadow-sm shrink-0">
                            <i class="ph-duotone ph-crown text-xl"></i>
                        </div>
                        <h3 class="text-sm font-black text-slate-900 tracking-tight truncate">Premium Ratio</h3>
                    </div>
                </div>
                <div class="p-6 flex-1 flex flex-col items-center justify-center">
                    <div class="h-48 w-full relative">
                        <canvas id="premiumVsFreeChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Goals Achievement --}}
            <div class="bg-white rounded-[2rem] border border-slate-200/60 bento-card overflow-hidden flex flex-col">
                <div class="px-6 py-5 border-b border-slate-100 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-teal-50 rounded-xl border border-teal-100 flex items-center justify-center text-teal-600 shadow-sm shrink-0">
                            <i class="ph-duotone ph-target text-xl"></i>
                        </div>
                        <h3 class="text-sm font-black text-slate-900 tracking-tight truncate">Goals Success</h3>
                    </div>
                </div>
                <div class="p-6 flex-1 flex flex-col items-center justify-center">
                    <div class="h-48 w-full relative">
                        <canvas id="goalsAchievementChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Verified vs Unverified --}}
            <div class="bg-white rounded-[2rem] border border-slate-200/60 bento-card overflow-hidden flex flex-col">
                <div class="px-6 py-5 border-b border-slate-100 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-50 rounded-xl border border-indigo-100 flex items-center justify-center text-indigo-600 shadow-sm shrink-0">
                            <i class="ph-duotone ph-seal-check text-xl"></i>
                        </div>
                        <h3 class="text-sm font-black text-slate-900 tracking-tight truncate">Verification Ratio</h3>
                    </div>
                </div>
                <div class="p-6 flex-1 flex flex-col items-center justify-center">
                    <div class="h-48 w-full relative">
                        <canvas id="verifiedVsUnverifiedChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Full Width Application Funnel --}}
        <div class="bg-white rounded-[2rem] border border-slate-200/60 bento-card overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-50 border border-purple-100 rounded-[1rem] sm:rounded-[1.25rem] flex items-center justify-center text-purple-600 shadow-sm shrink-0">
                        <i class="ph-duotone ph-funnel text-xl sm:text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900 tracking-tight">Application Funnel</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Visualisasi alur konversi lamaran kerja dari awal sampai akhir</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
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
                            'Applied' => 'bg-blue-500',
                            'Interview' => 'bg-amber-500',
                            'Accepted' => 'bg-emerald-500',
                            'Rejected' => 'bg-rose-500',
                            'Pending' => 'bg-slate-400'
                        ];
                        $flow = ['Applied', 'Interview', 'Accepted', 'Rejected', 'Pending'];
                    @endphp
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
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
                            
                            <div class="relative bg-white p-5 rounded-2xl border {{ $isCritical ? 'border-rose-200 bg-rose-50/20' : 'border-slate-100' }} shadow-sm transition-all duration-300">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-10 h-10 {{ $statusColors[$label] }} rounded-xl flex items-center justify-center text-white shadow-sm">
                                        <i class="ph-bold {{ $label === 'Applied' ? 'ph-paper-plane-tilt' : ($label === 'Interview' ? 'ph-chats-circle' : ($label === 'Accepted' ? 'ph-check-circle' : ($label === 'Rejected' ? 'ph-x-circle' : 'ph-clock'))) }} text-lg"></i>
                                    </div>
                                    <span class="text-[10px] font-black {{ $isCritical ? 'text-rose-600' : 'text-slate-400' }} uppercase tracking-widest">{{ $label }}</span>
                                </div>

                                <div class="mb-3">
                                    <h4 class="text-2xl font-black text-slate-900 tracking-tight">{{ number_format($count) }}</h4>
                                    <div class="flex items-center justify-between text-[10px] font-bold mt-1">
                                        <span class="text-slate-400 uppercase tracking-wider">Rate</span>
                                        <span class="{{ $isCritical ? 'text-rose-600' : 'text-emerald-600' }}">{{ $conversionRate }}%</span>
                                    </div>
                                </div>

                                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full {{ $statusColors[$label] }} transition-all duration-1000" style="width: {{ $conversionRate }}%"></div>
                                </div>

                                @if($isCritical)
                                    <div class="mt-3 pt-3 border-t border-rose-100 flex items-start gap-2">
                                        <i class="ph-fill ph-warning-octagon text-rose-500 text-xs mt-0.5"></i>
                                        <p class="text-[9px] font-bold text-rose-700 leading-tight">
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
                    <div class="py-10 flex flex-col items-center justify-center text-slate-400 border border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                        <i class="ph-duotone ph-folder-open text-4xl mb-3 text-slate-300"></i>
                        <p class="text-sm font-bold">Belum ada data funnel tersedia</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- 2-Column Grid Bar/Pie Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            {{-- Top Companies Row --}}
            <div class="bg-white rounded-[2rem] border border-slate-200/60 bento-card overflow-hidden flex flex-col">
                <div class="px-6 py-5 border-b border-slate-100 shrink-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-primary-50 border border-primary-100 rounded-xl flex items-center justify-center text-primary-600 shadow-sm shrink-0">
                                <i class="ph-duotone ph-buildings text-xl"></i>
                            </div>
                            <h3 class="text-sm font-black text-slate-900 tracking-tight">Top Targeted Companies</h3>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-3 flex-1 overflow-y-auto custom-scrollbar">
                    @forelse($topCompanies as $company)
                        <div class="flex items-center justify-between p-4 rounded-[1rem] bg-slate-50/50 border border-slate-100 group transition-colors">
                            <span class="text-xs font-bold text-slate-700 truncate pr-4">{{ $company['company_name'] }}</span>
                            <span class="px-3 py-1.5 bg-white border border-slate-200 text-slate-600 rounded-lg text-[10px] font-black shadow-sm shrink-0">{{ $company['count'] }} Lamaran</span>
                        </div>
                    @empty
                        <div class="py-10 text-center flex flex-col items-center justify-center border border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                            <i class="ph-duotone ph-folder-open text-3xl mb-2 text-slate-300"></i>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Belum ada data perusahaan</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Top Positions Row --}}
            <div class="bg-white rounded-[2rem] border border-slate-200/60 bento-card overflow-hidden flex flex-col">
                <div class="px-6 py-5 border-b border-slate-100 shrink-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-indigo-50 border border-indigo-100 rounded-xl flex items-center justify-center text-indigo-600 shadow-sm shrink-0">
                                <i class="ph-duotone ph-briefcase text-xl"></i>
                            </div>
                            <h3 class="text-sm font-black text-slate-900 tracking-tight">Most Wanted Positions</h3>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-3 flex-1 overflow-y-auto custom-scrollbar">
                    @forelse($topPositions as $pos)
                        <div class="flex items-center justify-between p-4 rounded-[1rem] bg-slate-50/50 border border-slate-100 group transition-colors">
                            <span class="text-xs font-bold text-slate-700 truncate pr-4">{{ $pos['position'] }}</span>
                            <span class="px-3 py-1.5 bg-white border border-slate-200 text-slate-600 rounded-lg text-[10px] font-black shadow-sm shrink-0">{{ $pos['count'] }} Peminat</span>
                        </div>
                    @empty
                        <div class="py-10 text-center flex flex-col items-center justify-center border border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                            <i class="ph-duotone ph-folder-open text-3xl mb-2 text-slate-300"></i>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Belum ada data posisi</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Applications By Platform --}}
        <div class="bg-white rounded-[2rem] border border-slate-200/60 bento-card overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-rose-50 border border-rose-100 rounded-[1rem] sm:rounded-[1.25rem] flex items-center justify-center text-rose-600 shadow-sm shrink-0">
                        <i class="ph-duotone ph-chart-bar text-xl sm:text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900 tracking-tight">Platform Popularity</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Sumber lamaran paling banyak digunakan</p>
                    </div>
                </div>
            </div>
            <div class="p-6 flex-1">
                @if(count($jobApplicationsByPlatform['labels'] ?? []) > 0)
                    <div class="h-64 w-full relative">
                        <canvas id="jobApplicationsByPlatformChart"></canvas>
                    </div>
                @else
                    <div class="h-64 w-full flex flex-col items-center justify-center text-slate-400 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                        <i class="ph-duotone ph-folder-open text-4xl mb-3 text-slate-300"></i>
                        <p class="text-sm font-bold">Belum ada data platform</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════ --}}
        {{-- SECTION 1 & 4: Leaderboard XP + Peak Activity (2-col) --}}
        {{-- ═══════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">

            {{-- XP Leaderboard (compact) --}}
            <div class="bg-white rounded-[2rem] border border-slate-200/60 bento-card overflow-hidden flex flex-col">
                <div class="px-5 py-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-slate-50 border border-slate-200 rounded-[0.875rem] flex items-center justify-center text-slate-500 shadow-sm shrink-0">
                            <i class="ph-duotone ph-trophy text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900 tracking-tight">XP Leaderboard</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Top 5 pengguna berdasarkan XP</p>
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
                                    // Soft premium rank badges
                                    $rankBg = match($player['rank']) {
                                        1 => 'bg-amber-50 text-amber-600 border border-amber-200/50',
                                        2 => 'bg-slate-100 text-slate-700 border border-slate-200/50',
                                        3 => 'bg-orange-50 text-orange-700 border border-orange-200/50',
                                        default => 'bg-slate-50 text-slate-400 border border-slate-100'
                                    };
                                    $barColor = match($player['rank']) {
                                        1 => 'bg-amber-400',
                                        2 => 'bg-slate-400',
                                        3 => 'bg-orange-400',
                                        default => 'bg-slate-350'
                                    };
                                @endphp
                                <div class="flex items-center gap-3 px-3 py-2 rounded-xl bg-slate-50/30 border border-slate-100/70 hover:bg-slate-50 hover:shadow-sm transition-all duration-200">
                                    {{-- Rank --}}
                                    <div class="w-6 h-6 rounded-lg {{ $rankBg }} flex items-center justify-center text-[10px] font-black shrink-0">
                                        {{ $player['rank'] }}
                                    </div>

                                    {{-- Avatar --}}
                                    @if(!empty($player['logo']))
                                        <img src="{{ $player['avatar_url'] }}" alt="{{ $player['name'] }}" class="w-7 h-7 rounded-full object-cover border border-slate-200 shrink-0">
                                    @else
                                        <div class="w-7 h-7 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 shrink-0 shadow-sm">
                                            <i class="ph-fill ph-user text-[11px]"></i>
                                        </div>
                                    @endif

                                    {{-- Info --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-2">
                                            <div class="min-w-0">
                                                <p class="text-xs font-black text-slate-850 truncate leading-tight">{{ $player['name'] }}</p>
                                                <p class="text-[9px] font-bold text-slate-400 leading-none mt-1">{{ $player['title'] }} · Lvl {{ $player['level'] }}</p>
                                            </div>
                                            <span class="text-xs font-black text-slate-700 shrink-0">{{ number_format($player['xp']) }} <span class="text-[9px] font-bold text-slate-400">XP</span></span>
                                        </div>
                                        <div class="mt-2 w-full bg-slate-100 rounded-full h-1 overflow-hidden">
                                            <div class="h-full {{ $barColor }} rounded-full" style="width: {{ $barWidth }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-10 flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-2xl">
                            <i class="ph-duotone ph-trophy text-3xl text-slate-300 mb-2"></i>
                            <p class="text-xs font-bold text-slate-400">Belum ada data leaderboard</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Peak Activity Hours (compact) --}}
            <div class="bg-white rounded-[2rem] border border-slate-200/60 bento-card overflow-hidden flex flex-col">
                <div class="px-5 py-4 border-b border-slate-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-slate-50 border border-slate-200 rounded-[0.875rem] flex items-center justify-center text-slate-500 shadow-sm shrink-0">
                                <i class="ph-duotone ph-clock text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-900 tracking-tight">Peak Activity Hours</h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Jam paling aktif pengguna di platform</p>
                            </div>
                        </div>
                        @if($peakActivityHours['peak_hour'] ?? false)
                            <div class="px-2.5 py-1 bg-slate-50 border border-slate-100 rounded-xl text-right shrink-0">
                                <p class="text-[10px] font-black text-slate-700 leading-tight">Peak: {{ $peakActivityHours['peak_hour'] }}</p>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">{{ $peakActivityHours['peak_count'] }} Acts</p>
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

        {{-- ═══════════════════════════════════════════════════════════ --}}
        {{-- SECTION 2: AI Usage Stats + Support Ticket Resolution (2 col) --}}
        {{-- ═══════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">

            {{-- AI Usage --}}
            <div class="bg-white rounded-[2rem] border border-slate-200/60 bento-card overflow-hidden flex flex-col">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-violet-50 border border-violet-100 rounded-[1.25rem] flex items-center justify-center text-violet-600 shadow-sm shrink-0">
                            <i class="ph-duotone ph-robot text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900 tracking-tight">AI Feature Usage</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Pemakaian fitur AI platform</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 flex-1 space-y-6">
                    {{-- 4 AI stat cards --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="p-3 bg-violet-50/30 border border-violet-100 rounded-[1.25rem] text-center transition-all hover:bg-violet-50 hover:shadow-sm">
                            <div class="w-7 h-7 mx-auto mb-2 bg-violet-100/60 text-violet-650 rounded-lg flex items-center justify-center shadow-inner">
                                <i class="ph-fill ph-file-search text-sm"></i>
                            </div>
                            <p class="text-[9px] font-black text-violet-500 uppercase tracking-widest mb-1">Analyzer</p>
                            <p class="text-xl font-black text-slate-900 leading-none">{{ number_format($aiUsageStats['ai_analyzer'] ?? 0) }}</p>
                            <p class="text-[8px] font-bold text-slate-400 mt-1 uppercase tracking-wider">Scans</p>
                        </div>
                        <div class="p-3 bg-emerald-50/30 border border-emerald-100 rounded-[1.25rem] text-center transition-all hover:bg-emerald-50 hover:shadow-sm">
                            <div class="w-7 h-7 mx-auto mb-2 bg-emerald-100/60 text-emerald-650 rounded-lg flex items-center justify-center shadow-inner">
                                <i class="ph-fill ph-envelope-simple-open text-sm"></i>
                            </div>
                            <p class="text-[9px] font-black text-emerald-500 uppercase tracking-widest mb-1">Cover Letter</p>
                            <p class="text-xl font-black text-slate-900 leading-none">{{ number_format($coverLetterStats['total'] ?? 0) }}</p>
                            <p class="text-[8px] font-bold text-slate-400 mt-1 uppercase tracking-wider">Created</p>
                        </div>
                        <div class="p-3 bg-pink-50/30 border border-pink-100 rounded-[1.25rem] text-center transition-all hover:bg-pink-50 hover:shadow-sm">
                            <div class="w-7 h-7 mx-auto mb-2 bg-pink-100/60 text-pink-650 rounded-lg flex items-center justify-center shadow-inner">
                                <i class="ph-fill ph-camera text-sm"></i>
                            </div>
                            <p class="text-[9px] font-black text-pink-500 uppercase tracking-widest mb-1">AI Photo</p>
                            <p class="text-xl font-black text-slate-900 leading-none">{{ number_format($aiUsageStats['ai_photos'] ?? 0) }}</p>
                            <p class="text-[8px] font-bold text-slate-400 mt-1 uppercase tracking-wider">Photos</p>
                        </div>
                        <div class="p-3 bg-indigo-50/30 border border-indigo-100 rounded-[1.25rem] text-center transition-all hover:bg-indigo-50 hover:shadow-sm">
                            <div class="w-7 h-7 mx-auto mb-2 bg-indigo-100/60 text-indigo-650 rounded-lg flex items-center justify-center shadow-inner">
                                <i class="ph-fill ph-identification-card text-sm"></i>
                            </div>
                            <p class="text-[9px] font-black text-indigo-500 uppercase tracking-widest mb-1">CV Gen</p>
                            <p class="text-xl font-black text-slate-900 leading-none">{{ number_format($aiUsageStats['ai_cv_gen'] ?? 0) }}</p>
                            <p class="text-[8px] font-bold text-slate-400 mt-1 uppercase tracking-wider">Month</p>
                        </div>
                    </div>
                    {{-- Trend Mini Charts --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">AI Analyzer — 7 Hari Terakhir</p>
                            <div class="h-28 w-full relative">
                                <canvas id="aiUsageTrendChart"></canvas>
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">AI Cover Letter — 14 Hari Terakhir</p>
                            <div class="h-28 w-full relative">
                                <canvas id="coverLetterTrendChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Support Ticket Resolution --}}
            <div class="bg-white rounded-[2rem] border border-slate-200/60 bento-card overflow-hidden flex flex-col">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-teal-50 border border-teal-100 rounded-[1.25rem] flex items-center justify-center text-teal-600 shadow-sm shrink-0">
                            <i class="ph-duotone ph-headset text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900 tracking-tight">Support Ticket Resolution</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Status & performa penyelesaian tiket</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 flex-1 space-y-5">
                    {{-- Stats Row --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3 bg-slate-50 border border-slate-100 rounded-[1.25rem] flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-slate-100/80 border border-slate-200/50 text-slate-500 flex items-center justify-center shrink-0 shadow-inner">
                                <i class="ph-fill ph-ticket text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Total Tiket</p>
                                <p class="text-lg font-black text-slate-900 leading-none">{{ number_format($supportTicketStats['total'] ?? 0) }}</p>
                            </div>
                        </div>
                        <div class="p-3 bg-emerald-50/40 border border-emerald-100 rounded-[1.25rem] flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100/60 border border-emerald-200/20 text-emerald-600 flex items-center justify-center shrink-0 shadow-inner">
                                <i class="ph-fill ph-timer text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-emerald-500 uppercase tracking-widest leading-none mb-1">Avg Respon</p>
                                <p class="text-lg font-black text-slate-900 leading-none">{{ $supportTicketStats['avg_resolution_hrs'] ?? 0 }}<span class="text-xs font-bold text-slate-400">h</span></p>
                            </div>
                        </div>
                    </div>
                    {{-- Status Bar --}}
                    <div class="space-y-2">
                        @php
                            $tTotal = $supportTicketStats['total'] ?? 1;
                            $tTotal = max($tTotal, 1);
                        @endphp
                        <div class="flex items-center justify-between bg-slate-50/30 px-3 py-2 rounded-xl border border-slate-100/60">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                                <span class="text-[11px] font-bold text-slate-600">Pending</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-24 sm:w-32 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-amber-400 rounded-full" style="width: {{ round(($supportTicketStats['pending'] ?? 0) / $tTotal * 100) }}%"></div>
                                </div>
                                <span class="text-[11px] font-black text-slate-900 w-5 text-right">{{ $supportTicketStats['pending'] ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between bg-slate-50/30 px-3 py-2 rounded-xl border border-slate-100/60">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-blue-400"></div>
                                <span class="text-[11px] font-bold text-slate-600">Replied</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-24 sm:w-32 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-400 rounded-full" style="width: {{ round(($supportTicketStats['replied'] ?? 0) / $tTotal * 100) }}%"></div>
                                </div>
                                <span class="text-[11px] font-black text-slate-900 w-5 text-right">{{ $supportTicketStats['replied'] ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between bg-slate-50/30 px-3 py-2 rounded-xl border border-slate-100/60">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                <span class="text-[11px] font-bold text-slate-650">Resolved</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-24 sm:w-32 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-emerald-500 rounded-full" style="width: {{ round(($supportTicketStats['resolved'] ?? 0) / $tTotal * 100) }}%"></div>
                                </div>
                                <span class="text-[11px] font-black text-slate-900 w-5 text-right">{{ $supportTicketStats['resolved'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    {{-- By Category --}}
                    @if(count($supportTicketStats['by_category'] ?? []) > 0)
                        <div class="pt-4 border-t border-slate-100/80">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2.5">By Category</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($supportTicketStats['by_category'] as $cat)
                                    <span class="px-2.5 py-1 bg-slate-50 border border-slate-150 text-slate-650 text-[10px] font-bold rounded-lg uppercase tracking-wider shadow-sm transition-all hover:bg-slate-100 hover:text-slate-900">
                                        {{ str_replace('_', ' ', $cat['category']) }} ({{ $cat['count'] }})
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════ --}}
        {{-- SECTION 5: User Retention Cohort + CV Template (2 col) --}}
        {{-- ═══════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">

            {{-- Retention Cohort --}}
            <div class="bg-white rounded-[2rem] border border-slate-200/60 bento-card overflow-hidden flex flex-col">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-50 border border-indigo-100 rounded-[1.25rem] flex items-center justify-center text-indigo-600 shadow-sm shrink-0">
                            <i class="ph-duotone ph-arrow-counter-clockwise text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900 tracking-tight">User Retention Cohort</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Berapa % user kembali bulan berikutnya</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 flex-1 space-y-3">
                    @forelse($retentionCohort as $cohort)
                        <div class="flex items-center gap-4 p-2 hover:bg-slate-50/50 rounded-xl border border-transparent hover:border-slate-100 transition-all duration-200">
                            <div class="w-20 shrink-0 bg-slate-50 border border-slate-200/40 text-slate-600 px-2 py-1 rounded-md text-[9px] font-black uppercase text-center shadow-sm">
                                {{ $cohort['month'] }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="text-[10px] font-bold text-slate-400">{{ $cohort['retained'] }}/{{ $cohort['total'] }} retained</p>
                                    <p class="text-[10px] font-black {{ $cohort['rate'] >= 50 ? 'text-emerald-600' : ($cohort['rate'] >= 25 ? 'text-amber-600' : 'text-red-500') }}">{{ $cohort['rate'] }}%</p>
                                </div>
                                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    @php $retColor = $cohort['rate'] >= 50 ? 'bg-emerald-400' : ($cohort['rate'] >= 25 ? 'bg-amber-400' : 'bg-red-400'); @endphp
                                    <div class="h-full {{ $retColor }} rounded-full transition-all duration-700" style="width: {{ $cohort['rate'] }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-2xl">
                            <i class="ph-duotone ph-chart-pie-slice text-3xl text-slate-300 mb-2"></i>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Belum ada data retensi</p>
                        </div>
                    @endforelse
                    <div class="pt-3 border-t border-slate-100">
                        <p class="text-[9px] font-bold text-slate-400">* % user yang mendaftar bulan X dan kembali aktif di bulan X+1</p>
                    </div>
                </div>
            </div>

            {{-- CV Template Popularity --}}
            <div class="bg-white rounded-[2rem] border border-slate-200/60 bento-card overflow-hidden flex flex-col">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-rose-50 border border-rose-100 rounded-[1.25rem] flex items-center justify-center text-rose-600 shadow-sm shrink-0">
                            <i class="ph-duotone ph-file-doc text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900 tracking-tight">CV Template Popularity</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Template mana yang paling sering dipilih</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 flex-1">
                    @if(count($cvTemplatePopularity['templates'] ?? []) > 0)
                        @php $maxUsage = max(array_column($cvTemplatePopularity['templates'], 'usage_count') ?: [1]); @endphp
                        <div class="space-y-2">
                            @foreach($cvTemplatePopularity['templates'] as $i => $tpl)
                                @php
                                    $tplColors = ['bg-rose-400','bg-orange-400','bg-amber-400','bg-emerald-400','bg-blue-400','bg-purple-400'];
                                    $tplColor = $tplColors[$i] ?? 'bg-slate-400';
                                    $tplWidth = $maxUsage > 0 ? round(($tpl['usage_count'] / $maxUsage) * 100) : 0;
                                    $textColor = str_replace('bg-', 'text-', $tplColor);
                                @endphp
                                <div class="flex items-center gap-3 p-2 hover:bg-slate-50/50 rounded-xl border border-transparent hover:border-slate-100/60 transition-all duration-200">
                                    <div class="w-7 h-7 rounded-lg {{ $tplColor }} bg-opacity-10 {{ $textColor }} flex items-center justify-center shrink-0 shadow-sm border border-slate-100/20">
                                        <i class="ph-fill ph-file-text text-sm"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-1">
                                            <p class="text-xs font-bold text-slate-800 truncate capitalize">{{ str_replace(['_', '-'], ' ', $tpl['template_key']) }}</p>
                                            <p class="text-xs font-black text-slate-900 shrink-0 ml-2">{{ $tpl['usage_count'] }}</p>
                                        </div>
                                        <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full {{ $tplColor }} rounded-full" style="width: {{ $tplWidth }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total CV Dibuat</p>
                            <p class="text-sm font-black text-slate-900">{{ number_format($cvTemplatePopularity['total'] ?? 0) }}</p>
                        </div>
                    @else
                        <div class="h-full flex flex-col items-center justify-center py-12 border-2 border-dashed border-slate-200 rounded-2xl">
                            <i class="ph-duotone ph-file-doc text-4xl text-slate-300 mb-3"></i>
                            <p class="text-sm font-bold text-slate-600 mb-1">Belum ada data template</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">User belum membuat CV template</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<!-- Custom Chart Initialization -->
<script>
    // Common Chart.js Defaults for Bento Grid Aesthetic
    Chart.defaults.font.family = "'Inter', 'Segoe UI', 'Roboto', 'Helvetica Neue', sans-serif";
    Chart.defaults.color = '#94a3b8'; // slate-400
    Chart.defaults.scale.grid.color = '#f1f5f9'; // slate-100
    Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(15, 23, 42, 0.9)'; // slate-900
    Chart.defaults.plugins.tooltip.titleColor = '#ffffff';
    Chart.defaults.plugins.tooltip.bodyColor = '#e2e8f0'; // slate-200
    Chart.defaults.plugins.tooltip.padding = 12;
    Chart.defaults.plugins.tooltip.cornerRadius = 8;
    Chart.defaults.plugins.tooltip.displayColors = true;
    Chart.defaults.plugins.tooltip.boxPadding = 6;
    
    // Store instances
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
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, colorStart);
        gradient.addColorStop(1, colorEnd);
        return gradient;
    }

    function initCharts() {
        // Destroy existing
        Object.keys(charts).forEach(key => {
            if (charts[key]) charts[key].destroy();
        });

        // Common Chart Options for High-End Look
        const lineOptions = {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: { 
                legend: { 
                    display: false 
                },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleFont: { size: 13, weight: 'bold' },
                    bodyFont: { size: 12 },
                    padding: 12,
                    displayColors: true,
                    boxPadding: 6,
                    borderColor: 'rgba(255,255,255,0.1)',
                    borderWidth: 1
                }
            },
            scales: {
                x: { 
                    grid: { display: false },
                    ticks: { font: { size: 10, weight: 'bold' }, color: '#94a3b8' }
                },
                y: { 
                    beginAtZero: true, 
                    border: { display: false, dash: [4, 4] },
                    grid: { color: '#f1f5f9' },
                    ticks: { font: { size: 10, weight: 'bold' }, color: '#94a3b8', padding: 10 }
                }
            }
        };

        // 1. User Growth (Line) - Dual Tone
        const ugCanvas = document.getElementById('userGrowthChart');
        if (ugCanvas) {
            const ctx = ugCanvas.getContext('2d');
            const data = @json($userGrowth);
            charts.userGrowth = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels || [],
                    datasets: [
                        {
                            label: 'Total Users',
                            data: data.total || [],
                            borderColor: '#6366f1', // Indigo 500
                            backgroundColor: createGradient(ctx, 'rgba(99, 102, 241, 0.15)', 'rgba(99, 102, 241, 0)'),
                            borderWidth: 4,
                            tension: 0.45,
                            fill: true,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#6366f1',
                            pointBorderWidth: 3,
                            pointRadius: 0,
                            pointHoverRadius: 6,
                            pointHoverBackgroundColor: '#6366f1',
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 3,
                        }
                    ]
                },
                options: lineOptions
            });
        }

        // 2. Job Apps Over Time (Line) - Cyan
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
                        borderColor: '#06b6d4', // Cyan 500
                        backgroundColor: createGradient(ctx, 'rgba(6, 182, 212, 0.15)', 'rgba(6, 182, 212, 0)'),
                        borderWidth: 4,
                        tension: 0.45,
                        fill: true,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: '#06b6d4',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 3,
                    }]
                },
                options: lineOptions
            });
        }

        // 3. User Registrations By Day (Bar) - Emerald
        const urCanvas = document.getElementById('userRegistrationByDayChart');
        if (urCanvas) {
            const ctx = urCanvas.getContext('2d');
            const data = @json($userRegistrationByDay);
            charts.userRegDay = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels || [],
                    datasets: [{
                        label: 'Signups',
                        data: data.data || [],
                        backgroundColor: createGradient(ctx, '#10b981', '#34d399'), // Emerald 500 to 400
                        borderRadius: 8,
                        barThickness: 20,
                    }]
                },
                options: {
                    ...lineOptions,
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 10, weight: 'bold' }, color: '#94a3b8' } },
                        y: { beginAtZero: true, grid: { display: false }, ticks: { display: false } }
                    }
                }
            });
        }

        // Donut Chart Premium Options
        const donutOptions = {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '82%',
            plugins: {
                legend: { 
                    position: 'bottom', 
                    labels: { 
                        usePointStyle: true, 
                        pointStyle: 'circle',
                        padding: 20, 
                        font: { size: 11, weight: 'bold' },
                        color: '#64748b'
                    } 
                },
                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 12,
                    cornerRadius: 8,
                }
            },
            elements: { arc: { borderWidth: 4, borderColor: '#ffffff', borderRadius: 10 } }
        };

        // 4. Premium vs Free
        const pfCanvas = document.getElementById('premiumVsFreeChart');
        if (pfCanvas) {
            const data = @json($premiumVsFree);
            charts.premiumVsFree = new Chart(pfCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: data.labels || [],
                    datasets: [{
                        data: data.data || [],
                        backgroundColor: ['#f59e0b', '#f1f5f9'] 
                    }]
                },
                options: donutOptions
            });
        }

        // 5. Goals Achievement
        const gaCanvas = document.getElementById('goalsAchievementChart');
        if (gaCanvas) {
            const data = @json($goalsAchievement);
            charts.goalsAchieve = new Chart(gaCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: data.labels || [],
                    datasets: [{
                        data: data.data || [],
                        backgroundColor: ['#14b8a6', '#f1f5f9']
                    }]
                },
                options: donutOptions
            });
        }

        // 6. Verified vs Unverified
        const vuCanvas = document.getElementById('verifiedVsUnverifiedChart');
        if (vuCanvas) {
            const data = @json($verifiedVsUnverified);
            charts.verifiedUnverified = new Chart(vuCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: data.labels || [],
                    datasets: [{
                        data: data.data || [],
                        backgroundColor: ['#6366f1', '#f1f5f9']
                    }]
                },
                options: donutOptions
            });
        }

        // 7. Apps by Platform (Bar) - Rose
        const jpCanvas = document.getElementById('jobApplicationsByPlatformChart');
        if (jpCanvas) {
            const ctx = jpCanvas.getContext('2d');
            const data = @json($jobApplicationsByPlatform);
            charts.jobAppsPlatform = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels || [],
                    datasets: [{
                        label: 'Applications',
                        data: data.data || [],
                        backgroundColor: createGradient(ctx, '#f43f5e', '#fb7185'),
                        borderRadius: 6,
                        barThickness: 12,
                    }]
                },
                options: {
                    ...lineOptions,
                    indexAxis: 'y',
                    scales: {
                        x: { grid: { display: false }, ticks: { display: false } },
                        y: { grid: { display: false }, border: { display: false }, ticks: { font: { size: 10, weight: 'bold' } } }
                    }
                }
            });
        }

        // 8. AI Usage Trend (Sparkline bar)
        const aiCanvas = document.getElementById('aiUsageTrendChart');
        if (aiCanvas) {
            const ctx = aiCanvas.getContext('2d');
            const data = @json($aiUsageStats);
            charts.aiUsageTrend = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.trend_labels || [],
                    datasets: [{
                        data: data.trend || [],
                        backgroundColor: createGradient(ctx, 'rgba(139,92,246,0.8)', 'rgba(139,92,246,0.3)'),
                        borderRadius: 6,
                        barThickness: 14,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: { backgroundColor: '#0f172a', padding: 8, cornerRadius: 6 } },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 9, weight: 'bold' }, color: '#94a3b8' } },
                        y: { display: false, beginAtZero: true }
                    }
                }
            });
        }

        // 9. Cover Letter Trend (Line chart with labels)
        const clCanvas = document.getElementById('coverLetterTrendChart');
        if (clCanvas) {
            const ctx = clCanvas.getContext('2d');
            const data = @json($coverLetterStats);
            const clLabels = data.trend_labels || [];
            const clData = data.trend || [];
            // Show only every other label to avoid crowding
            charts.coverLetterTrend = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: clLabels,
                    datasets: [{
                        data: clData,
                        borderColor: '#10b981',
                        backgroundColor: createGradient(ctx, 'rgba(16,185,129,0.18)', 'rgba(16,185,129,0.01)'),
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointRadius: clData.map(v => v > 0 ? 3 : 0),
                        pointHoverRadius: 5,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            padding: 8,
                            cornerRadius: 6,
                            callbacks: {
                                label: (item) => item.raw + ' cover letter'
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 8, weight: 'bold' },
                                color: '#94a3b8',
                                maxRotation: 0,
                                callback: (val, idx) => idx % 2 === 0 ? clLabels[idx] : ''
                            },
                            border: { display: false }
                        },
                        y: {
                            display: true,
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                font: { size: 8, weight: 'bold' },
                                color: '#94a3b8',
                                precision: 0,
                                maxTicksLimit: 4
                            },
                            border: { display: false }
                        }
                    }
                }
            });
        }

        // 10. Peak Activity Hours (Bar chart - muted heatmap style)
        const paCanvas = document.getElementById('peakActivityChart');
        if (paCanvas) {
            const ctx = paCanvas.getContext('2d');
            const data = @json($peakActivityHours);
            const maxVal = Math.max(...(data.data || [1]));
            const bgColors = (data.data || []).map(v => {
                const intensity = maxVal > 0 ? v / maxVal : 0;
                // Muted slate-blue gradient
                return `rgba(100, 116, 139, ${0.12 + intensity * 0.65})`;
            });
            charts.peakActivity = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels || [],
                    datasets: [{
                        label: 'Aktivitas',
                        data: data.data || [],
                        backgroundColor: bgColors,
                        borderRadius: 3,
                        barThickness: 'flex',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            padding: 10,
                            cornerRadius: 8,
                            callbacks: { title: (items) => items[0].label, label: (item) => item.raw + ' aktivitas' }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 8, weight: 'bold' },
                                color: '#94a3b8',
                                maxRotation: 0,
                                callback: (val, idx) => idx % 4 === 0 ? (data.labels || [])[idx] : ''
                            },
                            border: { display: false }
                        },
                        y: { display: false, beginAtZero: true }
                    }
                }
            });
        }
    }

    // Initialize on load and on Livewire updates
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
