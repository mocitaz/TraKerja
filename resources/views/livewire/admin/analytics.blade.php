<div class="space-y-6">
        
        {{-- Header & Period Filter --}}
        <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary-50 rounded-xl flex items-center justify-center flex-shrink-0 text-primary-600 shadow-inner">
                            <i class="ph-duotone ph-chart-line-up text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-900 truncate">Analytics Dashboard</h3>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tinjauan performa platform</p>
                        </div>
                    </div>
                    <div class="relative w-full sm:w-auto group">
                        <select id="periodFilter" name="periodFilter" wire:model.live="periodFilter" class="appearance-none pl-4 pr-10 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all font-bold text-slate-700 bg-white cursor-pointer text-sm w-full sm:w-48 shadow-sm group-hover:border-primary-300">
                            <option value="all">Semua Waktu</option>
                            <option value="7">7 Hari Terakhir</option>
                            <option value="30">30 Hari Terakhir</option>
                            <option value="90">90 Hari Terakhir</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400 group-hover:text-primary-500 transition-colors">
                            <i class="ph-bold ph-caret-down"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Grid (Bento Grid) --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            {{-- Total Users --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group hover:border-slate-300 transition-colors">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-slate-50 to-slate-100 rounded-full blur-2xl -z-10 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Users</p>
                        <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($stats['totalUsers']) }}</h3>
                        <p class="text-[10px] font-bold text-slate-400 mt-1">
                            @if($periodFilter === 'all')
                                Seluruh Data
                            @else
                                Dalam {{ $stats['periodDays'] }} hari
                            @endif
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-600 shadow-inner">
                        <i class="ph-duotone ph-users-three text-2xl"></i>
                    </div>
                </div>
            </div>

            {{-- Premium Users --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group hover:border-amber-200 transition-colors">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-amber-50 to-amber-100/50 rounded-full blur-2xl -z-10 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-amber-400 uppercase tracking-widest mb-1">Premium Users</p>
                        <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($stats['premiumUsers']) }}</h3>
                        <p class="text-[10px] font-bold text-amber-400 mt-1">
                            @if($periodFilter === 'all')
                                Seluruh Data
                            @else
                                Dalam {{ $stats['periodDays'] }} hari
                            @endif
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 shadow-inner">
                        <i class="ph-duotone ph-crown-simple text-2xl"></i>
                    </div>
                </div>
            </div>

            {{-- Active Users --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group hover:border-pink-200 transition-colors">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-pink-50 to-pink-100/50 rounded-full blur-2xl -z-10 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-pink-400 uppercase tracking-widest mb-1">Active Users</p>
                        <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($stats['activeUsers']) }}</h3>
                        <p class="text-[10px] font-bold text-pink-400 mt-1">
                            @if($periodFilter === 'all')
                                Seluruh Waktu
                            @else
                                Dalam {{ $stats['periodDays'] }} hari
                            @endif
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-pink-50 flex items-center justify-center text-pink-600 shadow-inner">
                        <i class="ph-duotone ph-lightning text-2xl"></i>
                    </div>
                </div>
            </div>

            {{-- CV Exports --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group hover:border-emerald-200 transition-colors">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-emerald-50 to-emerald-100/50 rounded-full blur-2xl -z-10 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-emerald-400 uppercase tracking-widest mb-1">CV Exports</p>
                        <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($stats['totalExports']) }}</h3>
                        <p class="text-[10px] font-bold text-emerald-500 mt-1">Total Downloads</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-inner">
                        <i class="ph-duotone ph-download-simple text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Stats Highlights --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            {{-- User Growth Insights --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm relative overflow-hidden group">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <i class="ph-bold ph-users-three"></i>
                    </div>
                    <h4 class="text-[11px] font-black uppercase tracking-widest text-slate-400">User Growth</h4>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Today</p>
                        <p class="text-2xl font-black text-slate-900">{{ number_format($stats['newUsersToday']) }}</p>
                    </div>
                    <div class="border-l border-slate-50 pl-4">
                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Week/Month</p>
                        <p class="text-sm font-black text-slate-700">{{ number_format($stats['newUsersWeek']) }} <span class="text-slate-300 mx-1">/</span> {{ number_format($stats['newUsersMonth']) }}</p>
                    </div>
                </div>
            </div>

            {{-- Application Metrics --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm relative overflow-hidden group">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="ph-bold ph-briefcase"></i>
                    </div>
                    <h4 class="text-[11px] font-black uppercase tracking-widest text-slate-400">App Metrics</h4>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Total Apps</p>
                        <p class="text-2xl font-black text-slate-900">{{ number_format($stats['totalJobApplications'] ?? 0) }}</p>
                    </div>
                    <div class="border-l border-slate-50 pl-4">
                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Goals Rate</p>
                        <p class="text-sm font-black text-emerald-600">{{ $stats['goalsAchievementRate'] ?? 0 }}%</p>
                        <p class="text-[9px] font-bold text-slate-400 uppercase mt-0.5">{{ number_format($stats['totalGoals'] ?? 0) }} goals</p>
                    </div>
                </div>
            </div>

            {{-- Financial Performance --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm relative overflow-hidden group">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                        <i class="ph-bold ph-wallet"></i>
                    </div>
                    <h4 class="text-[11px] font-black uppercase tracking-widest text-slate-400">Financial</h4>
                </div>
                <div class="grid grid-cols-1 gap-2">
                    <div class="flex items-center justify-between">
                        <p class="text-[10px] font-bold text-slate-400 uppercase">Revenue</p>
                        <p class="text-lg font-black text-slate-900">Rp{{ number_format($stats['totalRevenue'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex items-center justify-between pt-2 border-t border-slate-50">
                        <p class="text-[10px] font-bold text-slate-400 uppercase">Avg/User</p>
                        <p class="text-xs font-black text-amber-600">
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
        <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 shadow-inner">
                        <i class="ph-duotone ph-trend-up text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900">User Growth Trend</h3>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pertumbuhan pengguna dari waktu ke waktu</p>
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
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Applications Over Time --}}
            <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 shadow-inner">
                            <i class="ph-duotone ph-briefcase text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-900">Applications Timeline</h3>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Aktivitas pelamaran kerja harian</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="h-64 w-full relative">
                        <canvas id="jobApplicationsOverTimeChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Registrations by Day --}}
            <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 shadow-inner">
                            <i class="ph-duotone ph-user-plus text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-900">Registrations Timeline</h3>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pendaftaran pengguna harian</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="h-64 w-full relative">
                        <canvas id="userRegistrationByDayChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3-Column Grid Donut Charts --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Premium vs Free --}}
            <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden flex flex-col">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center text-amber-600 shadow-inner">
                            <i class="ph-duotone ph-crown text-lg"></i>
                        </div>
                        <h3 class="text-sm font-extrabold text-slate-900 truncate">Premium Ratio</h3>
                    </div>
                </div>
                <div class="p-5 flex-1 flex flex-col items-center justify-center">
                    <div class="h-48 w-full relative">
                        <canvas id="premiumVsFreeChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Goals Achievement --}}
            <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden flex flex-col">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-teal-50 rounded-lg flex items-center justify-center text-teal-600 shadow-inner">
                            <i class="ph-duotone ph-target text-lg"></i>
                        </div>
                        <h3 class="text-sm font-extrabold text-slate-900 truncate">Goals Success</h3>
                    </div>
                </div>
                <div class="p-5 flex-1 flex flex-col items-center justify-center">
                    <div class="h-48 w-full relative">
                        <canvas id="goalsAchievementChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Verified vs Unverified --}}
            <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden flex flex-col">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600 shadow-inner">
                            <i class="ph-duotone ph-seal-check text-lg"></i>
                        </div>
                        <h3 class="text-sm font-extrabold text-slate-900 truncate">Verification Ratio</h3>
                    </div>
                </div>
                <div class="p-5 flex-1 flex flex-col items-center justify-center">
                    <div class="h-48 w-full relative">
                        <canvas id="verifiedVsUnverifiedChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Full Width Application Funnel --}}
        <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 shadow-inner">
                        <i class="ph-duotone ph-funnel text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900">Application Funnel</h3>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Visualisasi alur konversi lamaran kerja dari awal sampai akhir</p>
                    </div>
                </div>
            </div>
            <div class="p-4">
                @if(count($jobApplicationsByStatus['labels'] ?? []) > 0)
                    @php
                        $totalApps = array_sum($jobApplicationsByStatus['data'] ?? [0]);
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
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                        @foreach($flow as $index => $label)
                            @php
                                $idx = array_search($label, $jobApplicationsByStatus['labels']);
                                $count = $idx !== false ? $jobApplicationsByStatus['data'][$idx] : 0;
                                $conversionRate = $prevCount > 0 ? round(($count / $prevCount) * 100) : 0;
                                $isCritical = $index > 0 && (100 - $conversionRate) > 70;
                            @endphp
                            
                            <div class="relative group bg-white p-4 rounded-2xl border {{ $isCritical ? 'border-rose-200 bg-rose-50/20' : 'border-slate-100' }} shadow-sm hover:shadow-md transition-all duration-300">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-8 h-8 {{ $statusColors[$label] }} rounded-xl flex items-center justify-center text-white shadow-sm">
                                        <i class="ph-bold {{ $label === 'Applied' ? 'ph-paper-plane-tilt' : ($label === 'Interview' ? 'ph-chats-circle' : ($label === 'Accepted' ? 'ph-check-circle' : ($label === 'Rejected' ? 'ph-x-circle' : 'ph-clock'))) }} text-sm"></i>
                                    </div>
                                    <span class="text-[9px] font-black {{ $isCritical ? 'text-rose-600' : 'text-slate-400' }} uppercase tracking-widest">{{ $label }}</span>
                                </div>

                                <div class="mb-2">
                                    <h4 class="text-xl font-black text-slate-900 tracking-tight">{{ number_format($count) }}</h4>
                                    <div class="flex items-center justify-between text-[9px] font-bold mt-1">
                                        <span class="text-slate-400 uppercase">Rate</span>
                                        <span class="{{ $isCritical ? 'text-rose-600' : 'text-emerald-600' }}">{{ $conversionRate }}%</span>
                                    </div>
                                </div>

                                <div class="w-full h-1 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full {{ $statusColors[$label] }} transition-all duration-1000" style="width: {{ $conversionRate }}%"></div>
                                </div>

                                @if($isCritical)
                                    <div class="mt-2 pt-2 border-t border-rose-100 flex items-start gap-1.5">
                                        <i class="ph-fill ph-warning-octagon text-rose-500 text-[10px] mt-0.5"></i>
                                        <p class="text-[8px] font-bold text-rose-700 leading-tight">Drop-off tinggi. Butuh bimbingan!</p>
                                    </div>
                                @endif
                            </div>
                            @php $prevCount = $count; @endphp
                        @endforeach
                    </div>
                @else
                    <div class="py-10 flex flex-col items-center justify-center text-slate-400">
                        <i class="ph-duotone ph-folder-open text-4xl mb-2 opacity-20"></i>
                        <p class="text-sm font-bold">Belum ada data funnel tersedia</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- 2-Column Grid Bar/Pie Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Top Companies Row --}}
            <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="ph-fill ph-buildings text-primary-500"></i>
                        <h3 class="text-sm font-extrabold text-slate-900">Top Targeted Companies</h3>
                    </div>
                </div>
                <div class="p-4 space-y-3">
                    @forelse($topCompanies as $company)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/50 border border-slate-100 group hover:border-primary-200 transition-colors">
                            <span class="text-xs font-bold text-slate-700 truncate pr-4">{{ $company['company_name'] }}</span>
                            <span class="px-2 py-1 bg-primary-50 text-primary-600 rounded-lg text-[10px] font-black group-hover:bg-primary-500 group-hover:text-white transition-colors">{{ $company['count'] }} Lamaran</span>
                        </div>
                    @empty
                        <div class="py-8 text-center">
                            <p class="text-xs font-bold text-slate-400 italic">Belum ada data perusahaan pada periode ini</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Top Positions Row --}}
            <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="ph-fill ph-briefcase text-indigo-500"></i>
                        <h3 class="text-sm font-extrabold text-slate-900">Most Wanted Positions</h3>
                    </div>
                </div>
                <div class="p-4 space-y-3">
                    @forelse($topPositions as $pos)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/50 border border-slate-100 group hover:border-indigo-200 transition-colors">
                            <span class="text-xs font-bold text-slate-700 truncate pr-4">{{ $pos['position'] }}</span>
                            <span class="px-2 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-[10px] font-black group-hover:bg-indigo-500 group-hover:text-white transition-colors">{{ $pos['count'] }} Peminat</span>
                        </div>
                    @empty
                        <div class="py-8 text-center">
                            <p class="text-xs font-bold text-slate-400 italic">Belum ada data posisi pada periode ini</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Applications By Platform --}}
        <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-rose-50 rounded-xl flex items-center justify-center text-rose-600 shadow-inner">
                        <i class="ph-duotone ph-chart-bar text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900">Platform Popularity</h3>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Sumber lamaran paling banyak digunakan</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                @if(count($jobApplicationsByPlatform['labels'] ?? []) > 0)
                    <div class="h-64 w-full relative">
                        <canvas id="jobApplicationsByPlatformChart"></canvas>
                    </div>
                @else
                    <div class="h-64 w-full flex flex-col items-center justify-center text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                        <i class="ph-duotone ph-folder-open text-4xl mb-2 text-slate-300"></i>
                        <p class="text-sm font-bold">Belum ada data platform</p>
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
        verifiedUnverified: null
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
