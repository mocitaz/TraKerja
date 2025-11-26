<div class="min-h-screen w-full bg-slate-100 px-4 py-6 sm:px-6 lg:px-8">
    <div class="space-y-6">
    @php
        $hasUserGrowthChart = array_sum($userGrowth['total'] ?? []) > 0 || array_sum($userGrowth['premium'] ?? []) > 0;
        $hasJobApplicationsOverTime = array_sum($jobApplicationsOverTime['data'] ?? []) > 0;
        $hasJobApplicationsByStatus = array_sum($jobApplicationsByStatus['data'] ?? []) > 0;
        $hasJobApplicationsByPlatform = array_sum($jobApplicationsByPlatform['data'] ?? []) > 0;
        $hasPremiumVsFree = array_sum($premiumVsFree['data'] ?? []) > 0;
        $hasGoalsAchievement = array_sum($goalsAchievement['data'] ?? []) > 0;
        $hasVerifiedVsUnverified = array_sum($verifiedVsUnverified['data'] ?? []) > 0;
    @endphp
    <section class="rounded-2xl border border-slate-100 bg-white shadow-sm px-6 py-5 sm:px-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Filter Periode</p>
                    <h3 class="text-lg font-semibold text-slate-900">Filter Periode</h3>
                    <p class="text-sm text-slate-500">Pilih rentang waktu untuk analisis data</p>
                </div>
            </div>
            <div class="relative">
                <select id="periodFilter" name="periodFilter" wire:model.live="periodFilter" class="appearance-none rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-900 shadow-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-400">
                    <option value="7">7 Hari Terakhir</option>
                    <option value="30">30 Hari Terakhir</option>
                    <option value="90">90 Hari Terakhir</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
        <article class="rounded-2xl border border-slate-100 bg-white px-4 py-4 shadow-sm">
            <p class="text-[10px] uppercase tracking-[0.4em] text-slate-400">Total Users</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($stats['totalUsers']) }}</p>
            <p class="text-[11px] text-slate-500">Dalam {{ $stats['periodDays'] }} hari</p>
        </article>

        <article class="rounded-2xl border border-slate-100 bg-white px-4 py-4 shadow-sm">
            <p class="text-[10px] uppercase tracking-[0.4em] text-slate-400">Premium Users</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($stats['premiumUsers']) }}</p>
            <p class="text-[11px] text-slate-500">{{ $stats['conversionRate'] }}% conversion</p>
        </article>

        <article class="rounded-2xl border border-slate-100 bg-white px-4 py-4 shadow-sm">
            <p class="text-[10px] uppercase tracking-[0.4em] text-slate-400">Active Users</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($stats['activeUsers']) }}</p>
            <p class="text-[11px] text-slate-500">Dengan Data CV</p>
        </article>

        <article class="rounded-2xl border border-slate-100 bg-white px-4 py-4 shadow-sm">
            <p class="text-[10px] uppercase tracking-[0.4em] text-slate-400">CV Exports</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($stats['totalExports']) }}</p>
            <p class="text-[11px] text-slate-500">Total Downloads</p>
        </article>
    </section>

    <section class="grid gap-6 lg:grid-cols-2">
        <article class="rounded-2xl border border-slate-100 bg-white shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-50 text-slate-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-slate-400">User Growth</p>
                    <h3 class="text-lg font-semibold text-slate-900">User Growth</h3>
                    <p class="text-sm text-slate-500">Grafik pertumbuhan pengguna</p>
                </div>
            </div>
            <div class="p-6">
                <div class="relative">
                    <div class="h-80">
                        <canvas id="userGrowthChart" width="400" height="200"></canvas>
                    </div>
                    @unless($hasUserGrowthChart)
                        <div class="absolute inset-0 flex flex-col items-center justify-center rounded-2xl bg-white/80 text-sm font-semibold text-slate-500">
                            Tidak ada data pertumbuhan pengguna
                        </div>
                    @endunless
                </div>
                <div id="chart-fallback" class="text-center py-12 hidden">
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-slate-500">Loading chart...</p>
                </div>
            </div>
        </article>

        <article class="rounded-2xl border border-slate-100 bg-white shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-purple-50 text-purple-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Applications over time</p>
                    <h3 class="text-lg font-semibold text-slate-900">Job Applications Over Time</h3>
                    <p class="text-sm text-slate-500">Lamaran kerja per hari</p>
                </div>
            </div>
            <div class="p-6">
                <div class="relative">
                    <div class="h-64">
                        <canvas id="jobApplicationsOverTimeChart"></canvas>
                    </div>
                    @unless($hasJobApplicationsOverTime)
                        <div class="absolute inset-0 flex flex-col items-center justify-center rounded-2xl bg-white/80 text-sm font-semibold text-slate-500">
                            Belum ada lamaran untuk rentang waktu ini
                        </div>
                    @endunless
                </div>
            </div>
        </article>
    </section>

    <section class="grid gap-6 lg:grid-cols-2">
        <article class="rounded-2xl border border-slate-100 bg-white shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-50 text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m0 0l-4-4-2 2-2-2-4 4 2 2-2 2 4 4 2-2 4 4z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Applications by status</p>
                    <h3 class="text-lg font-semibold text-slate-900">Applications By Status</h3>
                    <p class="text-sm text-slate-500">Distribusi status lamaran</p>
                </div>
            </div>
            <div class="p-6">
                <div class="relative">
                    <div class="h-64">
                        <canvas id="jobApplicationsByStatusChart"></canvas>
                    </div>
                    @unless($hasJobApplicationsByStatus)
                        <div class="absolute inset-0 flex flex-col items-center justify-center rounded-2xl bg-white/80 text-sm font-semibold text-slate-500">
                            Belum ada data status lamaran
                        </div>
                    @endunless
                </div>
            </div>
        </article>

        <article class="rounded-2xl border border-slate-100 bg-white shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Applications by platform</p>
                    <h3 class="text-lg font-semibold text-slate-900">Applications By Platform</h3>
                    <p class="text-sm text-slate-500">Platform paling populer</p>
                </div>
            </div>
            <div class="p-6">
                <div class="relative">
                    <div class="h-64">
                        <canvas id="jobApplicationsByPlatformChart"></canvas>
                    </div>
                    @unless($hasJobApplicationsByPlatform)
                        <div class="absolute inset-0 flex flex-col items-center justify-center rounded-2xl bg-white/80 text-sm font-semibold text-slate-500">
                            Tidak ada aktivitas platform
                        </div>
                    @endunless
                </div>
            </div>
        </article>
    </section>

    <section class="grid gap-6 lg:grid-cols-3">
        <article class="rounded-2xl border border-slate-100 bg-white shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-pink-50 text-pink-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Premium vs Free</p>
                    <h3 class="text-lg font-semibold text-slate-900">Premium vs Free Users</h3>
                    <p class="text-sm text-slate-500">Distribusi user premium dan free</p>
                </div>
            </div>
            <div class="p-6">
                <div class="relative">
                    <div class="h-64">
                        <canvas id="premiumVsFreeChart"></canvas>
                    </div>
                    @unless($hasPremiumVsFree)
                        <div class="absolute inset-0 flex flex-col items-center justify-center rounded-2xl bg-white/80 text-sm font-semibold text-slate-500">
                            Tidak ada pembagian premium/free
                        </div>
                    @endunless
                </div>
            </div>
        </article>

        <article class="rounded-2xl border border-slate-100 bg-white shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-teal-50 text-teal-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Goals Achievement</p>
                    <h3 class="text-lg font-semibold text-slate-900">Goals Achievement</h3>
                    <p class="text-sm text-slate-500">Goal yang dicapai vs pending</p>
                </div>
            </div>
            <div class="p-6">
                <div class="relative">
                    <div class="h-64">
                        <canvas id="goalsAchievementChart"></canvas>
                    </div>
                    @unless($hasGoalsAchievement)
                        <div class="absolute inset-0 flex flex-col items-center justify-center rounded-2xl bg-white/80 text-sm font-semibold text-slate-500">
                            Goals belum tercatat
                        </div>
                    @endunless
                </div>
            </div>
        </article>

        <article class="rounded-2xl border border-slate-100 bg-white shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Verified vs Unverified</p>
                    <h3 class="text-lg font-semibold text-slate-900">Verified vs Unverified Users</h3>
                    <p class="text-sm text-slate-500">Perbandingan user terverifikasi dan tidak</p>
                </div>
            </div>
            <div class="p-6">
                <div class="relative">
                    <div class="h-64">
                        <canvas id="verifiedVsUnverifiedChart"></canvas>
                    </div>
                    @unless($hasVerifiedVsUnverified)
                        <div class="absolute inset-0 flex flex-col items-center justify-center rounded-2xl bg-white/80 text-sm font-semibold text-slate-500">
                            Belum ada data verifikasi
                        </div>
                    @endunless
                </div>
            </div>
        </article>
    </section>

    <section class="rounded-2xl border border-slate-100 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Quick Stats</p>
                    <h3 class="text-lg font-semibold text-slate-900">Quick Stats</h3>
                    <p class="text-sm text-slate-500">Statistik lengkap dalam {{ $stats['periodDays'] }} hari terakhir</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-4 space-y-2">
                    <h4 class="text-xs uppercase tracking-[0.3em] text-slate-500">User Statistics</h4>
                    <div class="flex items-center justify-between text-sm text-slate-600">
                        <span>Pengguna baru hari ini</span>
                        <span class="font-semibold text-slate-900">{{ $stats['newUsersToday'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-slate-600">
                        <span>Pengguna baru minggu ini</span>
                        <span class="font-semibold text-slate-900">{{ $stats['newUsersWeek'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-slate-600">
                        <span>Pengguna baru bulan ini</span>
                        <span class="font-semibold text-slate-900">{{ $stats['newUsersMonth'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-slate-600">
                        <span>Conversion rate premium</span>
                        <span class="font-semibold text-emerald-600">{{ $stats['conversionRate'] }}%</span>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-4 space-y-2">
                    <h4 class="text-xs uppercase tracking-[0.3em] text-slate-500">Application Statistics</h4>
                    <div class="flex items-center justify-between text-sm text-slate-600">
                        <span>Total Job Applications</span>
                        <span class="font-semibold text-slate-900">{{ number_format($stats['totalJobApplications'] ?? 0) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-slate-600">
                        <span>Total CV Exports</span>
                        <span class="font-semibold text-slate-900">{{ number_format($stats['totalExports']) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-slate-600">
                        <span>Active Users</span>
                        <span class="font-semibold text-slate-900">{{ number_format($stats['activeUsers']) }}</span>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-4 space-y-2">
                    <h4 class="text-xs uppercase tracking-[0.3em] text-slate-500">Goals & Payments</h4>
                    <div class="flex items-center justify-between text-sm text-slate-600">
                        <span>Total Goals</span>
                        <span class="font-semibold text-slate-900">{{ number_format($stats['totalGoals'] ?? 0) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-slate-600">
                        <span>Achieved Goals</span>
                        <span class="font-semibold text-slate-900">{{ number_format($stats['achievedGoals'] ?? 0) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-slate-600">
                        <span>Achievement Rate</span>
                        <span class="font-semibold text-slate-900">{{ $stats['goalsAchievementRate'] ?? 0 }}%</span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-slate-600">
                        <span>Total Payments</span>
                        <span class="font-semibold text-slate-900">{{ number_format($stats['totalPayments'] ?? 0) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-slate-600">
                        <span>Total Revenue</span>
                        <span class="font-semibold text-slate-900">Rp {{ number_format($stats['totalRevenue'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<!-- Chart Scripts -->
<script>
    let chart = null;
    let jobApplicationsOverTimeChart = null;
    let jobApplicationsByStatusChart = null;
    let jobApplicationsByPlatformChart = null;
    let premiumVsFreeChart = null;
    let goalsAchievementChart = null;
    let verifiedVsUnverifiedChart = null;

    function createChart() {
        const canvas = document.getElementById('userGrowthChart');
        if (!canvas || typeof Chart === 'undefined') return;

        const userGrowthData = @json($userGrowth);
        const labels = userGrowthData.labels || [];
        const totalData = userGrowthData.total || [];
        const premiumData = userGrowthData.premium || [];

        try {
            if (chart) {
                chart.destroy();
            }

            chart = new Chart(canvas, {
                type: 'line',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Total Users',
                            data: totalData,
                            borderColor: 'rgb(34, 197, 94)',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                        },
                        {
                            label: 'Premium Users',
                            data: premiumData,
                            borderColor: 'rgb(168, 85, 247)',
                            backgroundColor: 'rgba(168, 85, 247, 0.1)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' },
                    },
                    scales: {
                        y: { beginAtZero: true },
                    },
                },
            });
        } catch (error) {
            console.error('Chart creation error:', error);
        }
    }

    function createJobApplicationsOverTimeChart() {
        const canvas = document.getElementById('jobApplicationsOverTimeChart');
        if (!canvas || typeof Chart === 'undefined') return;
        const data = @json($jobApplicationsOverTime);

        if (jobApplicationsOverTimeChart) {
            jobApplicationsOverTimeChart.destroy();
        }

        jobApplicationsOverTimeChart = new Chart(canvas, {
            type: 'line',
            data: {
                labels: data.labels || [],
                datasets: [
                    {
                        label: 'Job Applications',
                        data: data.data || [],
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } },
            },
        });
    }

    function createJobApplicationsByStatusChart() {
        const canvas = document.getElementById('jobApplicationsByStatusChart');
        if (!canvas || typeof Chart === 'undefined') return;
        const data = @json($jobApplicationsByStatus);

        if (jobApplicationsByStatusChart) {
            jobApplicationsByStatusChart.destroy();
        }

        jobApplicationsByStatusChart = new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: data.labels || [],
                datasets: [{ data: data.data || [], backgroundColor: data.colors || [] }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
            },
        });
    }

    function createJobApplicationsByPlatformChart() {
        const canvas = document.getElementById('jobApplicationsByPlatformChart');
        if (!canvas || typeof Chart === 'undefined') return;
        const data = @json($jobApplicationsByPlatform);

        if (jobApplicationsByPlatformChart) {
            jobApplicationsByPlatformChart.destroy();
        }

        jobApplicationsByPlatformChart = new Chart(canvas, {
            type: 'bar',
            data: {
                labels: data.labels || [],
                datasets: [
                    {
                        label: 'Applications',
                        data: data.data || [],
                        backgroundColor: 'rgba(99, 102, 241, 0.8)',
                        borderColor: 'rgb(99, 102, 241)',
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } },
            },
        });
    }

    function createPremiumVsFreeChart() {
        const canvas = document.getElementById('premiumVsFreeChart');
        if (!canvas || typeof Chart === 'undefined') return;
        const data = @json($premiumVsFree);

        if (premiumVsFreeChart) {
            premiumVsFreeChart.destroy();
        }

        premiumVsFreeChart = new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: data.labels || [],
                datasets: [{ data: data.data || [], backgroundColor: data.colors || [] }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
            },
        });
    }

    function createGoalsAchievementChart() {
        const canvas = document.getElementById('goalsAchievementChart');
        if (!canvas || typeof Chart === 'undefined') return;
        const data = @json($goalsAchievement);

        if (goalsAchievementChart) {
            goalsAchievementChart.destroy();
        }

        goalsAchievementChart = new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: data.labels || [],
                datasets: [{ data: data.data || [], backgroundColor: data.colors || [] }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
            },
        });
    }

    function createVerifiedVsUnverifiedChart() {
        const canvas = document.getElementById('verifiedVsUnverifiedChart');
        if (!canvas || typeof Chart === 'undefined') return;
        const data = @json($verifiedVsUnverified);

        if (verifiedVsUnverifiedChart) {
            verifiedVsUnverifiedChart.destroy();
        }

        verifiedVsUnverifiedChart = new Chart(canvas, {
            type: 'pie',
            data: {
                labels: data.labels || [],
                datasets: [
                    {
                        data: data.data || [],
                        backgroundColor: data.colors || ['#10b981', '#ef4444'],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return `${label}: ${value} (${percentage}%)`;
                            },
                        },
                    },
                },
            },
        });
    }

    function createAllCharts() {
        createChart();
        createJobApplicationsOverTimeChart();
        createJobApplicationsByStatusChart();
        createJobApplicationsByPlatformChart();
        createPremiumVsFreeChart();
        createGoalsAchievementChart();
        createVerifiedVsUnverifiedChart();
    }

    document.addEventListener('livewire:init', () => {
        Livewire.on('chartUpdated', () => {
            setTimeout(() => createAllCharts(), 100);
        });

        Livewire.on('$refresh', () => {
            setTimeout(() => createAllCharts(), 100);
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => createAllCharts(), 300);
    });
</script>
