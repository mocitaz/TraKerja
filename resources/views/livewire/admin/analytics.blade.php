<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
        {{-- Period Filter --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                <div>
                        <h3 class="text-lg font-semibold text-gray-900">Filter Periode</h3>
                        <p class="text-sm text-gray-500">Pilih rentang waktu untuk analisis data</p>
                    </div>
                </div>
                <div class="relative">
                    <select id="periodFilter" name="periodFilter" wire:model.live="periodFilter" class="appearance-none pl-4 pr-10 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all font-medium bg-white cursor-pointer text-sm w-40">
                        <option value="7">7 Hari Terakhir</option>
                        <option value="30">30 Hari Terakhir</option>
                        <option value="90">90 Hari Terakhir</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-600 mb-1">Total Users</p>
                        <p class="text-2xl font-bold text-[#212529]">{{ number_format($stats['totalUsers']) }}</p>
                        <p class="text-xs text-gray-500 mt-1">Dalam {{ $stats['periodDays'] }} hari</p>
                    </div>
                    <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-600 mb-1">Premium Users</p>
                        <p class="text-2xl font-bold text-[#212529]">{{ number_format($stats['premiumUsers']) }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $stats['conversionRate'] }}% conversion</p>
                    </div>
                    <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-600 mb-1">Active Users</p>
                        <p class="text-2xl font-bold text-[#212529]">{{ number_format($stats['activeUsers']) }}</p>
                        <p class="text-xs text-gray-500 mt-1">Dengan Data CV</p>
                    </div>
                    <div class="w-8 h-8 bg-gradient-to-br from-pink-500 to-pink-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-600 mb-1">CV Exports</p>
                        <p class="text-2xl font-bold text-[#212529]">{{ number_format($stats['totalExports']) }}</p>
                        <p class="text-xs text-gray-500 mt-1">Total Downloads</p>
                    </div>
                    <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- User Growth Chart --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">User Growth</h3>
                        <p class="text-sm text-gray-500">Grafik pertumbuhan pengguna</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="h-80">
                    <canvas id="userGrowthChart" width="400" height="200"></canvas>
                </div>
                <!-- Fallback content -->
                <div id="chart-fallback" class="text-center py-16" style="display: none;">
                        <div class="flex justify-center mb-4">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                    <p class="text-gray-600 font-medium">Loading chart...</p>
                    </div>
            </div>
                    </div>
                    
        <!-- Chart.js CDN -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
        
        <!-- Chart Script -->
        <script>
        let chartCreated = false;
        
        function createChart() {
            if (chartCreated) return;
            
            const canvas = document.getElementById('userGrowthChart');
            if (!canvas || typeof Chart === 'undefined') return;
            
            const userGrowthData = @json($userGrowth);
            const labels = userGrowthData.labels || [];
            const totalData = userGrowthData.total || [];
            const premiumData = userGrowthData.premium || [];
            
            try {
                new Chart(canvas, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Users',
                            data: totalData,
                            borderColor: 'rgb(34, 197, 94)',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true
                        }, {
                            label: 'Premium Users',
                            data: premiumData,
                            borderColor: 'rgb(168, 85, 247)',
                            backgroundColor: 'rgba(168, 85, 247, 0.1)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
                
                chartCreated = true;
            } catch (error) {
                console.error('Chart creation error:', error);
            }
        }
        
        createChart();
        </script>

        {{-- Recent Activity --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Recent Users --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Recent Users</h3>
                            <p class="text-sm text-gray-500">{{ $stats['periodDays'] }} hari terakhir</p>
                        </div>
                    </div>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($recentUsers as $user)
                        <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-primary-600 to-secondary-500 rounded-full flex items-center justify-center">
                                        <span class="text-white font-semibold">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="flex gap-1 justify-end">
                                        @if($user->is_premium)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-secondary-100 text-secondary-800">Premium</span>
                                        @endif
                                        @if($user->is_admin)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-accent-100 text-accent-800">Admin</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center">
                            <div class="flex justify-center mb-3">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-gray-600 font-medium">Belum ada pengguna</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Quick Stats</h3>
                            <p class="text-sm text-gray-500">Statistik cepat</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-sm text-gray-600 font-medium">Pengguna baru hari ini</span>
                        <span class="text-lg font-bold text-primary-600">{{ $stats['newUsersToday'] }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-sm text-gray-600 font-medium">Pengguna baru minggu ini</span>
                        <span class="text-lg font-bold text-primary-600">{{ $stats['newUsersWeek'] }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-sm text-gray-600 font-medium">Pengguna baru bulan ini</span>
                        <span class="text-lg font-bold text-primary-600">{{ $stats['newUsersMonth'] }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-sm text-gray-600 font-medium">Conversion rate premium</span>
                        <span class="text-lg font-bold text-secondary-600">{{ $stats['conversionRate'] }}%</span>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <span class="text-sm text-gray-600 font-medium">Total CV exports</span>
                        <span class="text-lg font-bold text-emerald-600">{{ number_format($stats['totalExports']) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

