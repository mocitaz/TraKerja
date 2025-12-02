<div class="py-4 sm:py-6 lg:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6 lg:space-y-8">
        {{-- Period Filter --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-4">
                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="w-7 h-7 sm:w-8 sm:h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">Filter Periode</h3>
                        <p class="text-xs sm:text-sm text-gray-500">Pilih rentang waktu untuk analisis data</p>
                    </div>
                </div>
                <div class="relative w-full sm:w-auto">
                    <select id="periodFilter" name="periodFilter" wire:model.live="periodFilter" class="appearance-none pl-3 sm:pl-4 pr-8 sm:pr-10 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all font-medium bg-white cursor-pointer text-xs sm:text-sm w-full sm:w-40">
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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] sm:text-xs font-medium text-gray-600 mb-1 truncate">Total Users</p>
                        <p class="text-xl sm:text-2xl font-bold text-[#212529]">{{ number_format($stats['totalUsers']) }}</p>
                        <p class="text-[10px] sm:text-xs text-gray-500 mt-1">Dalam {{ $stats['periodDays'] }} hari</p>
                    </div>
                    <div class="w-6 h-6 sm:w-7 sm:h-7 lg:w-8 lg:h-8 bg-primary-600 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 lg:w-4 lg:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] sm:text-xs font-medium text-gray-600 mb-1 truncate">Premium Users</p>
                        <p class="text-xl sm:text-2xl font-bold text-[#212529]">{{ number_format($stats['premiumUsers']) }}</p>
                        <p class="text-[10px] sm:text-xs text-gray-500 mt-1">{{ $stats['conversionRate'] }}% conversion</p>
                    </div>
                    <div class="w-6 h-6 sm:w-7 sm:h-7 lg:w-8 lg:h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 lg:w-4 lg:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] sm:text-xs font-medium text-gray-600 mb-1 truncate">Active Users</p>
                        <p class="text-xl sm:text-2xl font-bold text-[#212529]">{{ number_format($stats['activeUsers']) }}</p>
                        <p class="text-[10px] sm:text-xs text-gray-500 mt-1">Dengan Data CV</p>
                    </div>
                    <div class="w-6 h-6 sm:w-7 sm:h-7 lg:w-8 lg:h-8 bg-gradient-to-br from-pink-500 to-pink-600 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 lg:w-4 lg:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] sm:text-xs font-medium text-gray-600 mb-1 truncate">CV Exports</p>
                        <p class="text-xl sm:text-2xl font-bold text-[#212529]">{{ number_format($stats['totalExports']) }}</p>
                        <p class="text-[10px] sm:text-xs text-gray-500 mt-1">Total Downloads</p>
                    </div>
                    <div class="w-6 h-6 sm:w-7 sm:h-7 lg:w-8 lg:h-8 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 lg:w-4 lg:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- User Growth Chart --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="w-7 h-7 sm:w-8 sm:h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">User Growth</h3>
                        <p class="text-xs sm:text-sm text-gray-500">Grafik pertumbuhan pengguna</p>
                    </div>
                </div>
            </div>
            <div class="p-4 sm:p-6">
                <div class="h-64 sm:h-80">
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
        let chart = null;
        
        function createChart() {
            const canvas = document.getElementById('userGrowthChart');
            if (!canvas || typeof Chart === 'undefined') return;
            
            const userGrowthData = @json($userGrowth);
            const labels = userGrowthData.labels || [];
            const totalData = userGrowthData.total || [];
            const premiumData = userGrowthData.premium || [];
            
            try {
                // Destroy existing chart if it exists
                if (chart) {
                    chart.destroy();
                }
                
                chart = new Chart(canvas, {
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
                
                console.log('Chart created/updated successfully');
            } catch (error) {
                console.error('Chart creation error:', error);
            }
        }
        
        // Listen for Livewire events
        document.addEventListener('livewire:init', () => {
            console.log('Livewire initialized');
            
            Livewire.on('chartUpdated', () => {
                console.log('Chart update event received');
                createChart();
            });
            
            // Also listen for Livewire updates
            Livewire.on('$refresh', () => {
                console.log('Livewire refresh event received');
                createChart();
            });
        });
        
        // Initial chart creation
        createChart();
        
        // Job Applications Over Time Chart
        let jobApplicationsOverTimeChart = null;
        function createJobApplicationsOverTimeChart() {
            const canvas = document.getElementById('jobApplicationsOverTimeChart');
            if (!canvas || typeof Chart === 'undefined') return;
            
            const data = @json($jobApplicationsOverTime);
            if (jobApplicationsOverTimeChart) jobApplicationsOverTimeChart.destroy();
            
            jobApplicationsOverTimeChart = new Chart(canvas, {
                type: 'line',
                data: {
                    labels: data.labels || [],
                    datasets: [{
                        label: 'Job Applications',
                        data: data.data || [],
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }
        
        // Job Applications By Status Chart
        let jobApplicationsByStatusChart = null;
        function createJobApplicationsByStatusChart() {
            const canvas = document.getElementById('jobApplicationsByStatusChart');
            if (!canvas || typeof Chart === 'undefined') return;
            
            const data = @json($jobApplicationsByStatus);
            if (jobApplicationsByStatusChart) jobApplicationsByStatusChart.destroy();
            
            jobApplicationsByStatusChart = new Chart(canvas, {
                type: 'doughnut',
                data: {
                    labels: data.labels || [],
                    datasets: [{
                        data: data.data || [],
                        backgroundColor: data.colors || []
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        }
        
        // Job Applications By Platform Chart
        let jobApplicationsByPlatformChart = null;
        function createJobApplicationsByPlatformChart() {
            const canvas = document.getElementById('jobApplicationsByPlatformChart');
            if (!canvas || typeof Chart === 'undefined') return;
            
            const data = @json($jobApplicationsByPlatform);
            if (jobApplicationsByPlatformChart) jobApplicationsByPlatformChart.destroy();
            
            jobApplicationsByPlatformChart = new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: data.labels || [],
                    datasets: [{
                        label: 'Applications',
                        data: data.data || [],
                        backgroundColor: 'rgba(99, 102, 241, 0.8)',
                        borderColor: 'rgb(99, 102, 241)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }
        
        // Premium vs Free Chart
        let premiumVsFreeChart = null;
        function createPremiumVsFreeChart() {
            const canvas = document.getElementById('premiumVsFreeChart');
            if (!canvas || typeof Chart === 'undefined') return;
            
            const data = @json($premiumVsFree);
            if (premiumVsFreeChart) premiumVsFreeChart.destroy();
            
            premiumVsFreeChart = new Chart(canvas, {
                type: 'doughnut',
                data: {
                    labels: data.labels || [],
                    datasets: [{
                        data: data.data || [],
                        backgroundColor: data.colors || []
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        }
        
        // Goals Achievement Chart
        let goalsAchievementChart = null;
        function createGoalsAchievementChart() {
            const canvas = document.getElementById('goalsAchievementChart');
            if (!canvas || typeof Chart === 'undefined') return;
            
            const data = @json($goalsAchievement);
            if (goalsAchievementChart) goalsAchievementChart.destroy();
            
            goalsAchievementChart = new Chart(canvas, {
                type: 'doughnut',
                data: {
                    labels: data.labels || [],
                    datasets: [{
                        data: data.data || [],
                        backgroundColor: data.colors || []
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        }
        
        // Verified vs Unverified Chart
        let verifiedVsUnverifiedChart = null;
        function createVerifiedVsUnverifiedChart() {
            const canvas = document.getElementById('verifiedVsUnverifiedChart');
            if (!canvas || typeof Chart === 'undefined') return;
            
            const data = @json($verifiedVsUnverified);
            if (verifiedVsUnverifiedChart) verifiedVsUnverifiedChart.destroy();
            
            verifiedVsUnverifiedChart = new Chart(canvas, {
                type: 'pie',
                data: {
                    labels: data.labels || [],
                    datasets: [{
                        data: data.data || [],
                        backgroundColor: data.colors || ['#10b981', '#ef4444'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Create all charts function
        function createAllCharts() {
            createChart();
            createJobApplicationsOverTimeChart();
            createJobApplicationsByStatusChart();
            createJobApplicationsByPlatformChart();
            createPremiumVsFreeChart();
            createGoalsAchievementChart();
            createVerifiedVsUnverifiedChart();
        }
        
        // Make it globally available
        window.createAllCharts = createAllCharts;
        
        // Listen for Livewire events
        document.addEventListener('livewire:init', () => {
            Livewire.on('chartUpdated', () => {
                setTimeout(() => {
                    createAllCharts();
                }, 100);
            });
            Livewire.on('$refresh', () => {
                setTimeout(() => {
                    createAllCharts();
                }, 100);
            });
        });
        
        // Initial chart creation after DOM ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                setTimeout(() => createAllCharts(), 300);
            });
        } else {
            setTimeout(() => createAllCharts(), 300);
        }
        </script>

        {{-- Charts Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Job Applications Over Time --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Job Applications Over Time</h3>
                            <p class="text-sm text-gray-500">Lamaran kerja per hari</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="h-64">
                        <canvas id="jobApplicationsOverTimeChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Job Applications By Status --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">Applications By Status</h3>
                            <p class="text-xs sm:text-sm text-gray-500">Distribusi status lamaran</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="h-56 sm:h-64">
                        <canvas id="jobApplicationsByStatusChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Job Applications By Platform --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">Applications By Platform</h3>
                            <p class="text-xs sm:text-sm text-gray-500">Platform paling populer</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="h-56 sm:h-64">
                        <canvas id="jobApplicationsByPlatformChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Premium vs Free --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 bg-pink-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">Premium vs Free Users</h3>
                            <p class="text-xs sm:text-sm text-gray-500">Distribusi user premium dan free</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="h-56 sm:h-64">
                        <canvas id="premiumVsFreeChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Goals Achievement --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 bg-teal-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">Goals Achievement</h3>
                            <p class="text-xs sm:text-sm text-gray-500">Goal yang dicapai vs pending</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="h-56 sm:h-64">
                        <canvas id="goalsAchievementChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Verified vs Unverified Users --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">Verified vs Unverified Users</h3>
                            <p class="text-xs sm:text-sm text-gray-500">Perbandingan user terverifikasi dan tidak terverifikasi</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="h-56 sm:h-64">
                        <canvas id="verifiedVsUnverifiedChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Stats --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="w-7 h-7 sm:w-8 sm:h-8 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">Quick Stats</h3>
                        <p class="text-xs sm:text-sm text-gray-500">Statistik lengkap dalam {{ $stats['periodDays'] }} hari terakhir</p>
                    </div>
                </div>
            </div>
            <div class="p-4 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    {{-- User Stats --}}
                    <div class="space-y-3">
                        <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">User Statistics</h4>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">Pengguna baru hari ini</span>
                            <span class="text-base font-bold text-primary-600">{{ $stats['newUsersToday'] }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">Pengguna baru minggu ini</span>
                            <span class="text-base font-bold text-primary-600">{{ $stats['newUsersWeek'] }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">Pengguna baru bulan ini</span>
                            <span class="text-base font-bold text-primary-600">{{ $stats['newUsersMonth'] }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-sm text-gray-600">Conversion rate premium</span>
                            <span class="text-base font-bold text-secondary-600">{{ $stats['conversionRate'] }}%</span>
                        </div>
                    </div>

                    {{-- Application Stats --}}
                    <div class="space-y-3">
                        <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Application Statistics</h4>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">Total Job Applications</span>
                            <span class="text-base font-bold text-blue-600">{{ number_format($stats['totalJobApplications'] ?? 0) }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">Total CV Exports</span>
                            <span class="text-base font-bold text-emerald-600">{{ number_format($stats['totalExports']) }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-sm text-gray-600">Active Users</span>
                            <span class="text-base font-bold text-pink-600">{{ number_format($stats['activeUsers']) }}</span>
                        </div>
                    </div>

                    {{-- Goals & Payment Stats --}}
                    <div class="space-y-3">
                        <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Goals & Payments</h4>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">Total Goals</span>
                            <span class="text-base font-bold text-indigo-600">{{ number_format($stats['totalGoals'] ?? 0) }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">Achieved Goals</span>
                            <span class="text-base font-bold text-teal-600">{{ number_format($stats['achievedGoals'] ?? 0) }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">Achievement Rate</span>
                            <span class="text-base font-bold text-teal-600">{{ $stats['goalsAchievementRate'] ?? 0 }}%</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">Total Payments</span>
                            <span class="text-base font-bold text-amber-600">{{ number_format($stats['totalPayments'] ?? 0) }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-sm text-gray-600">Total Revenue</span>
                            <span class="text-base font-bold text-green-600">Rp {{ number_format($stats['totalRevenue'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

