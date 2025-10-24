<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        {{-- Period Filter --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-primary-900">Filter Periode</h3>
                    <p class="text-sm text-gray-600 mt-1">Pilih rentang waktu untuk analisis data</p>
                </div>
                <select wire:model.live="periodFilter" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all font-medium">
                    <option value="7">7 Hari Terakhir</option>
                    <option value="30">30 Hari Terakhir</option>
                    <option value="90">90 Hari Terakhir</option>
                </select>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="group relative overflow-hidden bg-gradient-to-br from-primary-500 to-primary-700 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:scale-105">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div class="text-right">
                            <p class="text-white/80 text-sm font-medium">Total Users</p>
                            <p class="text-4xl font-black text-white">{{ number_format($stats['totalUsers']) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-white/90 text-xs">
                        <span>Registered</span>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            <span class="font-semibold">Dalam {{ $stats['periodDays'] }} hari</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="group relative overflow-hidden bg-gradient-to-br from-secondary-500 to-purple-700 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:scale-105">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                        </div>
                        <div class="text-right">
                            <p class="text-white/80 text-sm font-medium">Premium Users</p>
                            <p class="text-4xl font-black text-white">{{ number_format($stats['premiumUsers']) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-white/90 text-xs">
                        <span>Conversion Rate</span>
                        <span class="font-semibold">{{ $stats['conversionRate'] }}% ({{ $stats['periodDays'] }} hari)</span>
                    </div>
                </div>
            </div>

            <div class="group relative overflow-hidden bg-gradient-to-br from-accent-500 to-pink-600 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:scale-105">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div class="text-right">
                            <p class="text-white/80 text-sm font-medium">Active Users</p>
                            <p class="text-4xl font-black text-white">{{ number_format($stats['activeUsers']) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-white/90 text-xs">
                        <span>Dengan Data CV</span>
                        <span class="font-semibold">{{ $stats['periodDays'] }} hari terakhir</span>
                    </div>
                </div>
            </div>

            <div class="group relative overflow-hidden bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:scale-105">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                            </svg>
                        </div>
                        <div class="text-right">
                            <p class="text-white/80 text-sm font-medium">CV Exports</p>
                            <p class="text-4xl font-black text-white">{{ number_format($stats['totalExports']) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-white/90 text-xs">
                        <span>Total Downloads</span>
                        <span class="font-semibold">All Time</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- User Growth Chart --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-primary-600 to-secondary-500 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">User Growth</h3>
                        <p class="text-white/80 text-sm">Grafik pertumbuhan pengguna</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                @if(!empty($userGrowth['labels']) && count($userGrowth['labels']) > 0)
                    <div class="h-64">
                        <canvas id="userGrowthChart"></canvas>
                    </div>
                    
                    @push('scripts')
                    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            let userGrowthChart = null;
                            
                            function updateChart() {
                                const ctx = document.getElementById('userGrowthChart');
                                if (!ctx) return;
                                
                                // Destroy existing chart
                                if (userGrowthChart) {
                                    userGrowthChart.destroy();
                                }
                                
                                // Wait for Chart.js to load
                                if (typeof Chart === 'undefined') {
                                    setTimeout(updateChart, 100);
                                    return;
                                }
                                
                                userGrowthChart = new Chart(ctx, {
                                    type: 'line',
                                    data: {
                                        labels: @json($userGrowth['labels']),
                                        datasets: [{
                                            label: 'Total Users',
                                            data: @json($userGrowth['total']),
                                            borderColor: 'rgb(139, 92, 246)',
                                            backgroundColor: 'rgba(139, 92, 246, 0.1)',
                                            borderWidth: 3,
                                            tension: 0.4,
                                            fill: true
                                        }, {
                                            label: 'Premium Users',
                                            data: @json($userGrowth['premium']),
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
                                        interaction: {
                                            intersect: false,
                                            mode: 'index'
                                        },
                                        plugins: {
                                            legend: {
                                                position: 'top',
                                                labels: {
                                                    usePointStyle: true,
                                                    padding: 15,
                                                    font: {
                                                        size: 12,
                                                        weight: 'bold'
                                                    }
                                                }
                                            },
                                            tooltip: {
                                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                                padding: 12,
                                                titleFont: {
                                                    size: 14
                                                },
                                                bodyFont: {
                                                    size: 13
                                                }
                                            }
                                        },
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                ticks: {
                                                    stepSize: 1,
                                                    font: {
                                                        size: 11
                                                    }
                                                },
                                                grid: {
                                                    color: 'rgba(0, 0, 0, 0.05)'
                                                }
                                            },
                                            x: {
                                                ticks: {
                                                    font: {
                                                        size: 11
                                                    }
                                                },
                                                grid: {
                                                    display: false
                                                }
                                            }
                                        }
                                    }
                                });
                            }
                            
                            // Initial chart render
                            updateChart();
                            
                            // Listen for Livewire updates
                            Livewire.on('chartUpdated', () => {
                                setTimeout(updateChart, 100);
                            });
                        });
                    </script>
                    @endpush
                @else
                    <div class="text-center py-16">
                        <div class="flex justify-center mb-4">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-gray-600 font-medium">Tidak ada data untuk periode yang dipilih</p>
                        <p class="text-gray-500 text-sm mt-2">Silakan pilih rentang waktu yang berbeda</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Recent Users --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-accent-600 to-pink-500 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Recent Users</h3>
                            <p class="text-white/80 text-sm">{{ $stats['periodDays'] }} hari terakhir</p>
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
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Quick Stats</h3>
                            <p class="text-white/80 text-sm">Statistik cepat</p>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush
