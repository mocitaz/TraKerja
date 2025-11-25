<x-app-layout>

    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Time Filter Controls -->
            <div class="mb-6 sm:mb-8">
                <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-3 sm:p-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-gray-900">Time Period Filter</h3>
                            <p class="text-xs text-gray-500">Select time range for analytics</p>
                        </div>
                        <div class="flex flex-wrap gap-1.5 bg-gray-100 rounded-lg p-0.5">
                            <button onclick="updateTimeFilter('weekly')" 
                                    class="px-3 sm:px-4 py-1.5 sm:py-2 rounded-md font-medium text-xs sm:text-sm transition-all duration-200 time-filter-btn {{ $timeFilter === 'weekly' ? 'bg-white text-primary-600 shadow-sm' : 'text-gray-600' }}" 
                                    data-filter="weekly">
                                Weekly
                            </button>
                            <button onclick="updateTimeFilter('monthly')" 
                                    class="px-3 sm:px-4 py-1.5 sm:py-2 rounded-md font-medium text-xs sm:text-sm transition-all duration-200 time-filter-btn {{ $timeFilter === 'monthly' ? 'bg-white text-primary-600 shadow-sm' : 'text-gray-600' }}" 
                                    data-filter="monthly">
                                Monthly
                            </button>
                            <button onclick="updateTimeFilter('all')" 
                                    class="px-3 sm:px-4 py-1.5 sm:py-2 rounded-md font-medium text-xs sm:text-sm transition-all duration-200 time-filter-btn {{ $timeFilter === 'all' ? 'bg-white text-primary-600 shadow-sm' : 'text-gray-600' }}" 
                                    data-filter="all">
                                All Time
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4 mb-6 sm:mb-8">
                <!-- On Process Card -->
                <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-3 sm:p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-600 mb-1">On Process</p>
                            <p class="text-xl sm:text-2xl font-bold text-[#212529]">{{ $onProcessCount }}</p>
                        </div>
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg flex items-center justify-center">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Offering/Accepted Card -->
                <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-3 sm:p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-600 mb-1">Offering/Accepted</p>
                            <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $offeringAcceptedCount }}</p>
                        </div>
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Declined Card -->
                <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-3 sm:p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-600 mb-1">Declined</p>
                            <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $declinedCount }}</p>
                        </div>
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Productivity Features Section -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8 items-stretch">
                <!-- Daily Streak -->
                <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-4 sm:p-6 h-full flex flex-col">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Daily Streak</h3>
                            <p class="text-sm text-gray-500">Consecutive days of applications</p>
                        </div>
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-center flex-1">
                        <div class="text-3xl font-bold text-gray-900 mb-2">{{ $dailyStreak['current_streak'] }}</div>
                        <div class="text-sm text-gray-500 mb-4">Current Streak</div>
                        <div class="text-lg font-semibold text-orange-600 mb-2">{{ $dailyStreak['best_streak'] }}</div>
                        <div class="text-sm text-gray-500">Best Streak Ever</div>
                    </div>
                    @if($dailyStreak['is_active'])
                        <div class="mt-auto p-3 bg-emerald-50 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm text-emerald-700 font-medium">Keep it up! 🔥</span>
                            </div>
                        </div>
                    @else
                        <div class="mt-auto p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm text-gray-600">Start your streak today!</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- This Week's Progress -->
                <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-4 sm:p-6 h-full flex flex-col">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">This Week's Progress</h3>
                            <p class="text-sm text-gray-500">Weekly application goals</p>
                        </div>
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-center mb-4 flex-1">
                        <div class="text-3xl font-bold text-gray-900 mb-2">{{ $weeklyProgress['this_week_applications'] }}</div>
                        <div class="text-sm text-gray-500 mb-2">This Week</div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                            <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $weeklyProgress['progress_percentage'] }}%"></div>
                        </div>
                        <div class="text-sm text-gray-600">{{ $weeklyProgress['progress_percentage'] }}% of weekly goal ({{ $weeklyProgress['weekly_goal'] }})</div>
                    </div>
                    @if($weeklyProgress['is_on_track'])
                        <div class="mt-auto p-3 bg-green-50 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm text-green-700 font-medium">On track! 🎯</span>
                            </div>
                        </div>
                    @else
                        <div class="mt-auto p-3 bg-yellow-50 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm text-yellow-700 font-medium">Keep pushing! 💪</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- The Cadence Effect -->
                <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-4 sm:p-6 h-full flex flex-col">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">The Cadence Effect</h3>
                            <p class="text-sm text-gray-500">Application frequency patterns</p>
                        </div>
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-center mb-4 flex-1">
                        <div class="text-2xl font-bold text-gray-900 mb-2">{{ $cadenceEffect['average_daily'] }}</div>
                        <div class="text-sm text-gray-500 mb-2">Avg Daily</div>
                        <div class="text-lg font-semibold text-purple-600 mb-2">{{ $cadenceEffect['consistency_score'] }}%</div>
                        <div class="text-sm text-gray-500">Consistency Score</div>
                    </div>
                    @if($cadenceEffect['consistency_score'] >= 70)
                        <div class="mt-auto p-3 bg-green-50 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm text-green-700 font-medium">Excellent consistency! 📈</span>
                            </div>
                        </div>
                    @elseif($cadenceEffect['consistency_score'] >= 40)
                        <div class="mt-auto p-3 bg-yellow-50 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm text-yellow-700 font-medium">Good progress! 📊</span>
                            </div>
                        </div>
                    @else
                        <div class="mt-auto p-3 bg-red-50 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm text-red-700 font-medium">Time to step up! 🚀</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Advanced Analytics Section -->
            <div class="space-y-8">
                <!-- Chart 1: Timeline Activity -->
                <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-4 sm:p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Timeline Activity</h3>
                            <p class="text-sm text-gray-500">Application trends and interview scheduling over time</p>
                        </div>
                    </div>
                    <div class="h-64 sm:h-80">
                        <canvas id="timelineChart"></canvas>
                    </div>
                </div>

                <!-- Chart 2: Conversion Funnel -->
                <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-4 sm:p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Conversion Funnel</h3>
                            <p class="text-sm text-gray-500">Recruitment stage progression and bottlenecks</p>
                        </div>
                    </div>
                    <div class="h-64 sm:h-80">
                        <canvas id="funnelChart"></canvas>
                    </div>
                </div>

                <!-- Chart 3: Status Distribution -->
                <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-4 sm:p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Status Distribution</h3>
                            <p class="text-sm text-gray-500">Final application status breakdown</p>
                        </div>
                    </div>
                    <div class="h-64 sm:h-80">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>

                <!-- Breakdown Analysis Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                    <!-- Platform Effectiveness -->
                    <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-4 sm:p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Platform Effectiveness</h3>
                                <p class="text-sm text-gray-500">Conversion rates by platform</p>
                            </div>
                        </div>
                        <div class="h-56 sm:h-64" style="position: relative; width: 100%; background-color: #f9fafb;">
                            <canvas id="platformChart" style="display: block; width: 100%; height: 100%; background-color: white; border: 1px solid #e5e7eb;"></canvas>
                        </div>
                    </div>

                    <!-- Career Level Analysis -->
                    <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-4 sm:p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Career Level Analysis</h3>
                                <p class="text-sm text-gray-500">Response rates by career level</p>
                            </div>
                        </div>
                        <div class="h-56 sm:h-64" style="position: relative; width: 100%; background-color: #f9fafb;">
                            <canvas id="careerLevelChart" style="display: block; width: 100%; height: 100%; background-color: white; border: 1px solid #e5e7eb;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Position Analysis -->
                <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-4 sm:p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Position Analysis</h3>
                            <p class="text-sm text-gray-500">Most applied vs most successful positions</p>
                        </div>
                    </div>
                    <div class="h-64 sm:h-80">
                        <canvas id="positionChart"></canvas>
                    </div>
                </div>

                <!-- Top Companies Table -->
                <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Top Companies</h3>
                            <p class="text-sm text-gray-500">Most applied companies</p>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applications</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">First Application</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Application</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($topCompanies as $company)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $company->company_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $company->applications }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($company->first_application)->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($company->last_application)->format('M d, Y') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No applications yet
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Location Analysis Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Province Distribution Chart -->
                    <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Province Distribution</h3>
                                <p class="text-sm text-gray-500">Applications by province</p>
                            </div>
                        </div>
                        <div class="h-80">
                            <canvas id="provinceChart"></canvas>
                        </div>
                    </div>

                    <!-- City Distribution Chart -->
                    <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">City Distribution</h3>
                                <p class="text-sm text-gray-500">Applications by city</p>
                            </div>
                        </div>
                        <div class="h-80">
                            <canvas id="cityChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Location Success Rate Table -->
                <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-6 mt-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Location Success Rate</h3>
                            <p class="text-sm text-gray-500">Success rate by province (Accepted vs Total)</p>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Province</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Applications</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Accepted</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Success Rate</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($locationAnalysis['success_rates'] as $province => $data)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $province }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $data['total'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $data['accepted'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $data['success_rate'] }}%"></div>
                                                </div>
                                                <span class="text-sm font-medium text-gray-900">{{ $data['success_rate'] }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No location data available
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Time Filter Management
        function updateTimeFilter(filter) {
            // Update button states
            document.querySelectorAll('.time-filter-btn').forEach(btn => {
                btn.classList.remove('bg-white', 'text-primary-600', 'shadow-sm');
                btn.classList.add('text-gray-600');
            });
            
            document.querySelector(`[data-filter="${filter}"]`).classList.add('bg-white', 'text-primary-600', 'shadow-sm');
            document.querySelector(`[data-filter="${filter}"]`).classList.remove('text-gray-600');
            
            // Reload page with new filter
            const url = new URL(window.location);
            url.searchParams.set('timeFilter', filter);
            window.location.href = url.toString();
        }

        // Chart instances
        let charts = {};

        // Initialize all charts
        function initAllCharts() {
            initTimelineChart();
            initFunnelChart();
            initStatusChart();
            initPlatformChart();
            initCareerLevelChart();
            initPositionChart();
            initProvinceChart();
            initCityChart();
        }

        // Timeline Chart
        function initTimelineChart() {
            const ctx = document.getElementById('timelineChart').getContext('2d');
            
            if (charts.timeline) {
                charts.timeline.destroy();
            }
            
            charts.timeline = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($timelineData['labels'] ?? []),
                    datasets: [{
                        label: 'Applications',
                        data: @json($timelineData['applications']->toArray() ?? []),
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4
                    }, {
                        label: 'Interviews',
                        data: @json($timelineData['interviews']->toArray() ?? []),
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4
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
        }

        // Funnel Chart
        function initFunnelChart() {
            const ctx = document.getElementById('funnelChart').getContext('2d');
            
            if (charts.funnel) {
                charts.funnel.destroy();
            }
            
            charts.funnel = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json(array_keys($funnelData)),
                    datasets: [{
                        label: 'Applications',
                        data: @json(array_values($funnelData)),
                        backgroundColor: [
                            '#3B82F6', '#8B5CF6', '#06B6D4', '#10B981', 
                            '#F59E0B', '#EF4444', '#EC4899', '#6366F1', '#84CC16'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Status Chart
        function initStatusChart() {
            const ctx = document.getElementById('statusChart').getContext('2d');
            
            if (charts.status) {
                charts.status.destroy();
            }
            
            charts.status = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: @json($statusDistribution->pluck('application_status')->toArray()),
                    datasets: [{
                        data: @json($statusDistribution->pluck('count')->toArray()),
                        backgroundColor: ['#3B82F6', '#EF4444', '#10B981']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // Platform Chart
        function initPlatformChart() {
            const ctx = document.getElementById('platformChart').getContext('2d');
            
            if (charts.platform) {
                charts.platform.destroy();
            }
            
            const platformData = @json($platformEffectiveness);
            
            // Handle empty data
            if (platformData.length === 0) {
                charts.platform = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['No Data'],
                        datasets: [{
                            data: [1],
                            backgroundColor: ['#E5E7EB'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
                return;
            }
            
            // Create beautiful chart
            charts.platform = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: platformData.map(item => item.platform),
                    datasets: [{
                        label: 'Total Applications',
                        data: platformData.map(item => item.total_applications),
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(139, 92, 246, 0.8)'
                        ],
                        borderColor: [
                            'rgba(59, 130, 246, 1)',
                            'rgba(16, 185, 129, 1)',
                            'rgba(245, 158, 11, 1)',
                            'rgba(239, 68, 68, 1)',
                            'rgba(139, 92, 246, 1)'
                        ],
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: false,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            padding: 12
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                stepSize: 1,
                                color: '#6B7280',
                                font: {
                                    size: 12
                                }
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#374151',
                                font: {
                                    size: 13,
                                    weight: '500'
                                }
                            }
                        }
                    }
                }
            });
        }

        // Career Level Chart
        function initCareerLevelChart() {
            const ctx = document.getElementById('careerLevelChart').getContext('2d');
            
            if (charts.careerLevel) {
                charts.careerLevel.destroy();
            }
            
            const careerData = @json($careerLevelAnalysis);
            
            // Handle empty data
            if (careerData.length === 0) {
                charts.careerLevel = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['No Data'],
                        datasets: [{
                            data: [1],
                            backgroundColor: ['#E5E7EB'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
                return;
            }
            
            // Create beautiful chart
            charts.careerLevel = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: careerData.map(item => item.career_level),
                    datasets: [{
                        label: 'Total Applications',
                        data: careerData.map(item => item.total_applications),
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(139, 92, 246, 0.8)'
                        ],
                        borderColor: [
                            'rgba(16, 185, 129, 1)',
                            'rgba(59, 130, 246, 1)',
                            'rgba(245, 158, 11, 1)',
                            'rgba(239, 68, 68, 1)',
                            'rgba(139, 92, 246, 1)'
                        ],
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: false,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            padding: 12
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                stepSize: 1,
                                color: '#6B7280',
                                font: {
                                    size: 12
                                }
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#374151',
                                font: {
                                    size: 13,
                                    weight: '500'
                                }
                            }
                        }
                    }
                }
            });
        }

        // Position Chart
        function initPositionChart() {
            const ctx = document.getElementById('positionChart').getContext('2d');
            
            if (charts.position) {
                charts.position.destroy();
            }
            
            const positionData = @json($positionAnalysis);
            
            charts.position = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: positionData.map(item => item.position),
                    datasets: [{
                        label: 'Total Applications',
                        data: positionData.map(item => item.total_applications),
                        backgroundColor: '#3B82F6'
                    }, {
                        label: 'Interviews',
                        data: positionData.map(item => item.interviews),
                        backgroundColor: '#10B981'
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
        }

        // Province Distribution Chart
        function initProvinceChart() {
            const ctx = document.getElementById('provinceChart').getContext('2d');
            
            if (charts.province) {
                charts.province.destroy();
            }
            
            const provinceData = @json($locationAnalysis['provinces']);
            
            charts.province = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(provinceData),
                    datasets: [{
                        data: Object.values(provinceData),
                        backgroundColor: [
                            '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
                            '#06B6D4', '#84CC16', '#F97316', '#EC4899', '#6366F1'
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        }

        // City Distribution Chart
        function initCityChart() {
            const ctx = document.getElementById('cityChart').getContext('2d');
            
            if (charts.city) {
                charts.city.destroy();
            }
            
            const cityData = @json($locationAnalysis['cities']);
            
            charts.city = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: Object.keys(cityData),
                    datasets: [{
                        label: 'Applications',
                        data: Object.values(cityData),
                        backgroundColor: '#3B82F6',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Refresh all charts
        function refreshAllCharts() {
            // This would be called when Livewire updates the data
            // For now, we'll reinitialize all charts
            initAllCharts();
        }

        // Initialize charts on page load
        document.addEventListener('DOMContentLoaded', function() {
            initAllCharts();
        });
    </script>
</x-app-layout>
