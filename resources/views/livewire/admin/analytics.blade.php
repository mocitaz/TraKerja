<div class="space-y-6">
    <-al Period Filter -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-primary-900">Analytics Dashboard</h2>
        <select wire:model.live="periodFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
            <option value="7">Last 7 days</option>
            <option value="30">Last 30 days</option>
            <option value="90">Last 90 days</option>
        </select>
    </div>

    <-al Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-primary-100 text-sm font-medium">Total Users</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($stats['totalUsers']) }}</p>
                    <p class="text-primary-100 text-xs mt-2">↗ {{ $stats['newUsersMonth'] }} this month</p>
                </div>
                <svg class="w-12 h-12 text-primary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-primary-100 text-sm font-medium">Premium Users</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($stats['premiumUsers']) }}</p>
                    <p class="text-primary-100 text-xs mt-2">{{ $stats['conversionRate'] }}% conversion</p>
                </div>
                <svg class="w-12 h-12 text-primary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Active Users</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($stats['activeUsers']) }}</p>
                    <p class="text-purple-100 text-xs mt-2">With CV data</p>
                </div>
                <svg class="w-12 h-12 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">CV Exports</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($stats['totalExports']) }}</p>
                    <p class="text-orange-100 text-xs mt-2">All time</p>
                </div>
                <svg class="w-12 h-12 text-orange-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path></svg>
            </div>
        </div>
    </div>

    <-al User Growth Chart -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-primary-900 mb-4">User Growth</h3>
        @if(!empty($userGrowth['labels']))
            <div class="h-64">
                <canvas id="userGrowthChart"></canvas>
            </div>
            @script
            <script>
                const ctx = document.getElementById('userGrowthChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($userGrowth['labels']),
                        datasets: [{
                            label: 'Total Users',
                            data: @json($userGrowth['total']),
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.3
                        }, {
                            label: 'Premium Users',
                            data: @json($userGrowth['premium']),
                            borderColor: 'rgb(16, 185, 129)',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>
            @endscript
        @else
            <div class="text-center py-12 text-primary-500">
                <svg class="mx-auto h-12 w-12 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                <p class="mt-2">No data available for selected period</p>
            </div>
        @endif
    </div>

    <-al Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <-al Recent Users -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-primary-900">Recent Users</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentUsers as $user)
                    <div class="px-6 py-4 hover:bg-primary-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 h-10 w-10 bg-primary-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-primary-900">{{ $user->name }}</p>
                                    <p class="text-xs text-primary-500">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="flex gap-1">
                                    @if($user->is_premium)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-primary-100 text-primary-800">Premium</span>
                                    @endif
                                    @if($user->is_admin)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">Admin</span>
                                    @endif
                                </div>
                                <p class="text-xs text-primary-500 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center text-primary-500">No users yet</div>
                @endforelse
            </div>
        </div>

        <-al Quick Stats -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-primary-900">Quick Stats</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                    <span class="text-sm text-primary-600">New users today</span>
                    <span class="text-lg font-semibold text-primary-900">{{ $stats['newUsersToday'] }}</span>
                </div>
                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                    <span class="text-sm text-primary-600">New users this week</span>
                    <span class="text-lg font-semibold text-primary-900">{{ $stats['newUsersWeek'] }}</span>
                </div>
                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                    <span class="text-sm text-primary-600">New users this month</span>
                    <span class="text-lg font-semibold text-primary-900">{{ $stats['newUsersMonth'] }}</span>
                </div>
                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                    <span class="text-sm text-primary-600">Premium conversion rate</span>
                    <span class="text-lg font-semibold text-primary-600">{{ $stats['conversionRate'] }}%</span>
                </div>
                <div class="flex items-center justify-between py-3">
                    <span class="text-sm text-primary-600">Total CV exports</span>
                    <span class="text-lg font-semibold text-primary-900">{{ number_format($stats['totalExports']) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush
