<x-app-layout>

    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-purple-50/20 to-blue-50/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            <!-- Header Section -->
            <div class="mb-5 sm:mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                        <div>
                        <h1 class="text-xl sm:text-2xl font-bold bg-gradient-to-r from-primary-600 via-purple-600 to-indigo-600 bg-clip-text text-transparent mb-0.5">
                            Analytics Dashboard
                        </h1>
                        <p class="text-xs sm:text-sm text-gray-600">Comprehensive insights into your job application journey</p>
                        </div>
                    <!-- Time Filter Controls -->
                    <div class="flex items-center gap-2 bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-gray-200/50 p-1.5">
                            <button onclick="updateTimeFilter('weekly')" 
                                class="px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg font-medium text-xs sm:text-sm transition-all duration-200 time-filter-btn {{ $timeFilter === 'weekly' ? 'bg-gradient-to-r from-primary-500 to-purple-600 text-white shadow-md shadow-primary-500/30' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}" 
                                    data-filter="weekly">
                                Weekly
                            </button>
                            <button onclick="updateTimeFilter('monthly')" 
                                class="px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg font-medium text-xs sm:text-sm transition-all duration-200 time-filter-btn {{ $timeFilter === 'monthly' ? 'bg-gradient-to-r from-primary-500 to-purple-600 text-white shadow-md shadow-primary-500/30' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}" 
                                    data-filter="monthly">
                                Monthly
                            </button>
                            <button onclick="updateTimeFilter('all')" 
                                class="px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg font-medium text-xs sm:text-sm transition-all duration-200 time-filter-btn {{ $timeFilter === 'all' ? 'bg-gradient-to-r from-primary-500 to-purple-600 text-white shadow-md shadow-primary-500/30' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}" 
                                    data-filter="all">
                                All Time
                            </button>
                    </div>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 mb-6 sm:mb-8">
                <!-- On Process Card -->
                <div class="group relative bg-white/80 backdrop-blur-sm rounded-xl shadow-md border border-gray-200/50 p-4 sm:p-5 hover:shadow-xl hover:scale-[1.02] transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-500/5 to-purple-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">On Process</p>
                            <p class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-primary-600 to-purple-600 bg-clip-text text-transparent">{{ $onProcessCount }}</p>
                            <p class="text-xs text-gray-500 mt-1">Active applications</p>
                        </div>
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-primary-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/30 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Offering/Accepted Card -->
                <div class="group relative bg-white/80 backdrop-blur-sm rounded-xl shadow-md border border-gray-200/50 p-4 sm:p-5 hover:shadow-xl hover:scale-[1.02] transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-green-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Offering/Accepted</p>
                            <p class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-emerald-600 to-green-600 bg-clip-text text-transparent">{{ $offeringAcceptedCount }}</p>
                            <p class="text-xs text-gray-500 mt-1">Successful applications</p>
                        </div>
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Declined Card -->
                <div class="group relative bg-white/80 backdrop-blur-sm rounded-xl shadow-md border border-gray-200/50 p-4 sm:p-5 hover:shadow-xl hover:scale-[1.02] transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-red-500/5 to-rose-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Declined</p>
                            <p class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-red-600 to-rose-600 bg-clip-text text-transparent">{{ $declinedCount }}</p>
                            <p class="text-xs text-gray-500 mt-1">Unsuccessful applications</p>
                        </div>
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/30 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Productivity Features Section -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 mb-6 sm:mb-8 items-stretch">
                <!-- Daily Streak -->
                <div class="group relative bg-white/80 backdrop-blur-sm rounded-xl shadow-md border border-gray-200/50 p-4 sm:p-5 h-full flex flex-col hover:shadow-xl hover:scale-[1.02] transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-orange-500/5 to-amber-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-gray-900">Daily Streak</h3>
                            <p class="text-xs sm:text-sm text-gray-500">Consecutive days</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-orange-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-orange-500/30 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="relative text-center flex-1">
                        <div class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-orange-600 to-amber-600 bg-clip-text text-transparent mb-1">{{ $dailyStreak['current_streak'] }}</div>
                        <div class="text-xs sm:text-sm text-gray-500 mb-3">Current Streak</div>
                        <div class="text-lg sm:text-xl font-semibold text-orange-600 mb-1">{{ $dailyStreak['best_streak'] }}</div>
                        <div class="text-xs sm:text-sm text-gray-500">Best Streak</div>
                    </div>
                    @if($dailyStreak['is_active'])
                        <div class="relative mt-auto p-2.5 sm:p-3 bg-gradient-to-r from-emerald-50 to-green-50 rounded-lg border border-emerald-200/50">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-xs sm:text-sm text-emerald-700 font-medium">Keep it up! 🔥</span>
                            </div>
                        </div>
                    @else
                        <div class="relative mt-auto p-2.5 sm:p-3 bg-gray-50 rounded-lg border border-gray-200/50">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-xs sm:text-sm text-gray-600">Start your streak!</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- This Week's Progress -->
                <div class="group relative bg-white/80 backdrop-blur-sm rounded-xl shadow-md border border-gray-200/50 p-4 sm:p-5 h-full flex flex-col hover:shadow-xl hover:scale-[1.02] transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-500/5 to-purple-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-gray-900">Weekly Progress</h3>
                            <p class="text-xs sm:text-sm text-gray-500">Application goals</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-primary-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/30 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="relative text-center mb-4 flex-1">
                        <div class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-primary-600 to-purple-600 bg-clip-text text-transparent mb-1">{{ $weeklyProgress['this_week_applications'] }}</div>
                        <div class="text-xs sm:text-sm text-gray-500 mb-3">This Week</div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2 overflow-hidden">
                            <div class="bg-gradient-to-r from-primary-500 to-purple-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $weeklyProgress['progress_percentage'] }}%"></div>
                        </div>
                        <div class="text-xs sm:text-sm text-gray-600 font-medium">{{ $weeklyProgress['progress_percentage'] }}% of {{ $weeklyProgress['weekly_goal'] }} goal</div>
                    </div>
                    @if($weeklyProgress['is_on_track'])
                        <div class="relative mt-auto p-2.5 sm:p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200/50">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-xs sm:text-sm text-green-700 font-medium">On track! 🎯</span>
                            </div>
                        </div>
                    @else
                        <div class="relative mt-auto p-2.5 sm:p-3 bg-gradient-to-r from-yellow-50 to-amber-50 rounded-lg border border-yellow-200/50">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-xs sm:text-sm text-yellow-700 font-medium">Keep pushing! 💪</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- The Cadence Effect -->
                <div class="group relative bg-white/80 backdrop-blur-sm rounded-xl shadow-md border border-gray-200/50 p-4 sm:p-5 h-full flex flex-col hover:shadow-xl hover:scale-[1.02] transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-blue-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-gray-900">Cadence Effect</h3>
                            <p class="text-xs sm:text-sm text-gray-500">Frequency patterns</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-purple-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="relative text-center mb-4 flex-1">
                        <div class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">{{ $cadenceEffect['average_daily'] }}</div>
                        <div class="text-xs sm:text-sm text-gray-500 mb-3">Avg Daily</div>
                        <div class="text-xl sm:text-2xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent mb-1">{{ $cadenceEffect['consistency_score'] }}%</div>
                        <div class="text-xs sm:text-sm text-gray-500">Consistency</div>
                    </div>
                    @if($cadenceEffect['consistency_score'] >= 70)
                        <div class="relative mt-auto p-2.5 sm:p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200/50">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-xs sm:text-sm text-green-700 font-medium">Excellent! 📈</span>
                            </div>
                        </div>
                    @elseif($cadenceEffect['consistency_score'] >= 40)
                        <div class="relative mt-auto p-2.5 sm:p-3 bg-gradient-to-r from-yellow-50 to-amber-50 rounded-lg border border-yellow-200/50">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-xs sm:text-sm text-yellow-700 font-medium">Good progress! 📊</span>
                            </div>
                        </div>
                    @else
                        <div class="relative mt-auto p-2.5 sm:p-3 bg-gradient-to-r from-red-50 to-rose-50 rounded-lg border border-red-200/50">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-xs sm:text-sm text-red-700 font-medium">Step up! 🚀</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Advanced Analytics Section -->
            <div class="space-y-5 sm:space-y-6">
                <!-- Chart 1: Timeline Activity -->
                <div class="group relative bg-white/80 backdrop-blur-sm rounded-xl shadow-md border border-gray-200/50 p-4 sm:p-6 hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-500/3 to-purple-600/3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative flex items-center justify-between mb-4 sm:mb-5">
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 flex items-center gap-2">
                                <span class="w-1 h-6 bg-gradient-to-b from-primary-500 to-purple-600 rounded-full"></span>
                                Timeline Activity
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-500 mt-1">Application trends and interview scheduling</p>
                        </div>
                    </div>
                    <div class="relative h-64 sm:h-72 lg:h-80">
                        <canvas id="timelineChart"></canvas>
                    </div>
                </div>

                <!-- Chart 2: Conversion Funnel -->
                <div class="group relative bg-white/80 backdrop-blur-sm rounded-xl shadow-md border border-gray-200/50 p-4 sm:p-6 hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/3 to-green-600/3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative flex items-center justify-between mb-4 sm:mb-5">
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 flex items-center gap-2">
                                <span class="w-1 h-6 bg-gradient-to-b from-emerald-500 to-green-600 rounded-full"></span>
                                Conversion Funnel
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-500 mt-1">Recruitment stage progression</p>
                        </div>
                    </div>
                    <div class="relative h-64 sm:h-72 lg:h-80">
                        <canvas id="funnelChart"></canvas>
                    </div>
                </div>

                <!-- Chart 3: Status Distribution -->
                <div class="group relative bg-white/80 backdrop-blur-sm rounded-xl shadow-md border border-gray-200/50 p-4 sm:p-6 hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/3 to-indigo-600/3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative flex items-center justify-between mb-4 sm:mb-5">
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 flex items-center gap-2">
                                <span class="w-1 h-6 bg-gradient-to-b from-blue-500 to-indigo-600 rounded-full"></span>
                                Status Distribution
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-500 mt-1">Final application status breakdown</p>
                        </div>
                    </div>
                    <div class="relative h-64 sm:h-72 lg:h-80">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>

                <!-- Breakdown Analysis Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5 lg:gap-6">
                    <!-- Platform Effectiveness -->
                    <div class="group relative bg-white/80 backdrop-blur-sm rounded-xl shadow-md border border-gray-200/50 p-4 sm:p-6 hover:shadow-xl transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-purple-500/3 to-pink-600/3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative flex items-center justify-between mb-4 sm:mb-5">
                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 flex items-center gap-2">
                                    <span class="w-1 h-6 bg-gradient-to-b from-purple-500 to-pink-600 rounded-full"></span>
                                    Platform Effectiveness
                                </h3>
                                <p class="text-xs sm:text-sm text-gray-500 mt-1">Conversion rates by platform</p>
                            </div>
                        </div>
                        <div class="relative h-56 sm:h-64 lg:h-72">
                            <canvas id="platformChart"></canvas>
                        </div>
                    </div>

                    <!-- Career Level Analysis -->
                    <div class="group relative bg-white/80 backdrop-blur-sm rounded-xl shadow-md border border-gray-200/50 p-4 sm:p-6 hover:shadow-xl transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/3 to-blue-600/3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative flex items-center justify-between mb-4 sm:mb-5">
                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 flex items-center gap-2">
                                    <span class="w-1 h-6 bg-gradient-to-b from-cyan-500 to-blue-600 rounded-full"></span>
                                    Career Level Analysis
                                </h3>
                                <p class="text-xs sm:text-sm text-gray-500 mt-1">Response rates by level</p>
                            </div>
                        </div>
                        <div class="relative h-56 sm:h-64 lg:h-72">
                            <canvas id="careerLevelChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Position Analysis -->
                <div class="group relative bg-white/80 backdrop-blur-sm rounded-xl shadow-md border border-gray-200/50 p-4 sm:p-6 hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/3 to-purple-600/3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative flex items-center justify-between mb-4 sm:mb-5">
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 flex items-center gap-2">
                                <span class="w-1 h-6 bg-gradient-to-b from-indigo-500 to-purple-600 rounded-full"></span>
                                Position Analysis
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-500 mt-1">Most applied vs successful positions</p>
                        </div>
                    </div>
                    <div class="relative h-64 sm:h-72 lg:h-80">
                        <canvas id="positionChart"></canvas>
                    </div>
                </div>

                <!-- Top Companies Table -->
                <div class="group relative bg-white/80 backdrop-blur-sm rounded-xl shadow-md border border-gray-200/50 p-4 sm:p-6 hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-500/3 to-orange-600/3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative flex items-center justify-between mb-4 sm:mb-5">
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 flex items-center gap-2">
                                <span class="w-1 h-6 bg-gradient-to-b from-amber-500 to-orange-600 rounded-full"></span>
                                Top Companies
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-500 mt-1">Most applied companies</p>
                        </div>
                    </div>
                    <div class="relative overflow-x-auto rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100/50">
                                <tr>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Company</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Applications</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">First</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Last</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($topCompanies as $company)
                                    <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                                        <td class="px-4 sm:px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $company->company_name }}
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 whitespace-nowrap text-sm text-gray-600">
                                            <span class="px-2 py-1 bg-primary-100 text-primary-700 rounded-md font-medium">{{ $company->applications }}</span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 whitespace-nowrap text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($company->first_application)->format('M d, Y') }}
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 whitespace-nowrap text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($company->last_application)->format('M d, Y') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">
                                            No applications yet
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Location Analysis Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5">
                    <!-- Province Distribution Chart -->
                    <div class="group relative bg-white/80 backdrop-blur-sm rounded-xl shadow-md border border-gray-200/50 p-4 sm:p-6 hover:shadow-xl transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-teal-500/3 to-cyan-600/3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative flex items-center justify-between mb-4 sm:mb-5">
                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 flex items-center gap-2">
                                    <span class="w-1 h-6 bg-gradient-to-b from-teal-500 to-cyan-600 rounded-full"></span>
                                    Province Distribution
                                </h3>
                                <p class="text-xs sm:text-sm text-gray-500 mt-1">Applications by province</p>
                            </div>
                        </div>
                        <div class="relative h-72 sm:h-80">
                            <canvas id="provinceChart"></canvas>
                        </div>
                    </div>

                    <!-- City Distribution Chart -->
                    <div class="group relative bg-white/80 backdrop-blur-sm rounded-xl shadow-md border border-gray-200/50 p-4 sm:p-6 hover:shadow-xl transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-rose-500/3 to-pink-600/3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative flex items-center justify-between mb-4 sm:mb-5">
                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 flex items-center gap-2">
                                    <span class="w-1 h-6 bg-gradient-to-b from-rose-500 to-pink-600 rounded-full"></span>
                                    City Distribution
                                </h3>
                                <p class="text-xs sm:text-sm text-gray-500 mt-1">Applications by city</p>
                            </div>
                        </div>
                        <div class="relative h-72 sm:h-80">
                            <canvas id="cityChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Location Success Rate Table -->
                <div class="group relative bg-white/80 backdrop-blur-sm rounded-xl shadow-md border border-gray-200/50 p-4 sm:p-6 hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-violet-500/3 to-purple-600/3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative flex items-center justify-between mb-4 sm:mb-5">
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 flex items-center gap-2">
                                <span class="w-1 h-6 bg-gradient-to-b from-violet-500 to-purple-600 rounded-full"></span>
                                Location Success Rate
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-500 mt-1">Success rate by province</p>
                        </div>
                    </div>
                    <div class="relative overflow-x-auto rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100/50">
                                <tr>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Province</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Accepted</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Success Rate</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($locationAnalysis['success_rates'] as $province => $data)
                                    <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                                        <td class="px-4 sm:px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $province }}
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 whitespace-nowrap text-sm text-gray-600">
                                            {{ $data['total'] }}
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 whitespace-nowrap text-sm text-gray-600">
                                            <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-md font-medium">{{ $data['accepted'] }}</span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <div class="w-20 bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                                    <div class="bg-gradient-to-r from-emerald-500 to-green-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $data['success_rate'] }}%"></div>
                                                </div>
                                                <span class="text-sm font-semibold text-gray-900 min-w-[3rem]">{{ $data['success_rate'] }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">
                                            No location data available
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Advanced Visualization Section -->
                <div class="space-y-5 sm:space-y-6 mt-6 sm:mt-8">
                    <!-- Comparison Chart: This Month vs Last Month -->
                    <div class="group relative bg-white/80 backdrop-blur-sm rounded-xl shadow-md border border-gray-200/50 p-4 sm:p-6 hover:shadow-xl transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/3 to-indigo-600/3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative flex items-center justify-between mb-4 sm:mb-5">
                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 flex items-center gap-2">
                                    <span class="w-1 h-6 bg-gradient-to-b from-blue-500 to-indigo-600 rounded-full"></span>
                                    Month Comparison
                                </h3>
                                <p class="text-xs sm:text-sm text-gray-500 mt-1">This month vs last month</p>
            </div>
                            <button onclick="toggleComparisonView()" class="px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-primary-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                <span id="comparisonViewText">Switch to Line</span>
                            </button>
                        </div>
                        <div class="relative h-72 sm:h-80">
                            <canvas id="comparisonChart"></canvas>
        </div>
    </div>

                    <!-- Trend Analysis with Prediction -->
                    <div class="group relative bg-white/80 backdrop-blur-sm rounded-xl shadow-md border border-gray-200/50 p-4 sm:p-6 hover:shadow-xl transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-green-500/3 to-emerald-600/3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative flex items-center justify-between mb-4 sm:mb-5">
                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 flex items-center gap-2">
                                    <span class="w-1 h-6 bg-gradient-to-b from-green-500 to-emerald-600 rounded-full"></span>
                                    Trend Analysis & Prediction
                                </h3>
                                <p class="text-xs sm:text-sm text-gray-500 mt-1">6-month trend with prediction</p>
                            </div>
                            <span class="px-3 py-1.5 text-xs font-semibold rounded-lg {{ $trendData['prediction']['trend'] === 'increasing' ? 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 border border-green-200' : ($trendData['prediction']['trend'] === 'decreasing' ? 'bg-gradient-to-r from-red-100 to-rose-100 text-red-700 border border-red-200' : 'bg-gray-100 text-gray-700 border border-gray-200') }}">
                                {{ ucfirst($trendData['prediction']['trend']) }} ({{ $trendData['prediction']['trend_percentage'] }}%)
                            </span>
                        </div>
                        <div class="relative h-72 sm:h-80">
                            <canvas id="trendChart"></canvas>
                        </div>
                        <div class="relative mt-4 p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200/50">
                            <p class="text-xs sm:text-sm text-blue-800 font-medium">
                                <strong>Prediction:</strong> Next month expected: <strong class="text-blue-900">{{ $trendData['prediction']['next_month'] }}</strong> applications
                            </p>
                        </div>
                    </div>

                    <!-- Sankey Diagram: Flow from Applied to Accepted -->
                    <div class="group relative bg-white/80 backdrop-blur-sm rounded-xl shadow-md border border-gray-200/50 p-4 sm:p-6 hover:shadow-xl transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-purple-500/3 to-pink-600/3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative flex items-center justify-between mb-4 sm:mb-5">
                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 flex items-center gap-2">
                                    <span class="w-1 h-6 bg-gradient-to-b from-purple-500 to-pink-600 rounded-full"></span>
                                    Application Flow
                                </h3>
                                <p class="text-xs sm:text-sm text-gray-500 mt-1">Sankey diagram: Applied to Accepted</p>
                            </div>
                        </div>
                        <div class="relative h-80 sm:h-96 overflow-x-auto rounded-lg">
                            <div id="sankeyChart" style="min-width: 100%; height: 100%;"></div>
                        </div>
                    </div>

                    <!-- Gantt Chart: Application Timeline -->
                    <div class="group relative bg-white/80 backdrop-blur-sm rounded-xl shadow-md border border-gray-200/50 p-4 sm:p-6 hover:shadow-xl transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-amber-500/3 to-orange-600/3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative flex items-center justify-between mb-4 sm:mb-5">
                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 flex items-center gap-2">
                                    <span class="w-1 h-6 bg-gradient-to-b from-amber-500 to-orange-600 rounded-full"></span>
                                    Application Timeline
                                </h3>
                                <p class="text-xs sm:text-sm text-gray-500 mt-1">Gantt chart timeline</p>
                            </div>
                        </div>
                        <div class="relative h-80 sm:h-96 overflow-x-auto rounded-lg">
                            <div id="ganttChart"></div>
                        </div>
                    </div>

                    <!-- Heatmap: Activity per Day/Week -->
                    <div class="group relative bg-white/80 backdrop-blur-sm rounded-xl shadow-md border border-gray-200/50 p-4 sm:p-6 hover:shadow-xl transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/3 to-teal-600/3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative flex items-center justify-between mb-4 sm:mb-5">
                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 flex items-center gap-2">
                                    <span class="w-1 h-6 bg-gradient-to-b from-cyan-500 to-teal-600 rounded-full"></span>
                                    Activity Heatmap
                                </h3>
                                <p class="text-xs sm:text-sm text-gray-500 mt-1">Application activity over time</p>
                            </div>
                            <button onclick="toggleHeatmapView()" class="px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-primary-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                <span id="heatmapViewText">Weekly View</span>
                            </button>
                        </div>
                        <div class="relative h-80 sm:h-96 rounded-lg">
                            <div id="heatmapChart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN with Zoom Plugin -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    
    <!-- ApexCharts for Advanced Charts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
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
            initComparisonChart();
            initTrendChart();
            initSankeyChart();
            initGanttChart();
            initHeatmapChart();
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
                        },
                        zoom: {
                            zoom: {
                                wheel: {
                                    enabled: true
                                },
                                pinch: {
                                    enabled: true
                                },
                                mode: 'x'
                            },
                            pan: {
                                enabled: true,
                                mode: 'x'
                            }
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

        // Comparison Chart
        let comparisonChartView = 'bar';
        function initComparisonChart() {
            const ctx = document.getElementById('comparisonChart').getContext('2d');
            
            if (charts.comparison) {
                charts.comparison.destroy();
            }
            
            const comparisonData = @json($comparisonData);
            const labels = ['Total', 'On Process', 'Accepted', 'Declined', 'Interviews'];
            
            charts.comparison = new Chart(ctx, {
                type: comparisonChartView,
                data: {
                    labels: labels,
                    datasets: [{
                        label: comparisonData.labels.this_month,
                        data: [
                            comparisonData.this_month.total,
                            comparisonData.this_month.on_process,
                            comparisonData.this_month.accepted,
                            comparisonData.this_month.declined,
                            comparisonData.this_month.interviews
                        ],
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2
                    }, {
                        label: comparisonData.labels.last_month,
                        data: [
                            comparisonData.last_month.total,
                            comparisonData.last_month.on_process,
                            comparisonData.last_month.accepted,
                            comparisonData.last_month.declined,
                            comparisonData.last_month.interviews
                        ],
                        backgroundColor: 'rgba(156, 163, 175, 0.8)',
                        borderColor: 'rgba(156, 163, 175, 1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        zoom: {
                            zoom: {
                                wheel: {
                                    enabled: true
                                },
                                pinch: {
                                    enabled: true
                                },
                                mode: 'x'
                            },
                            pan: {
                                enabled: true,
                                mode: 'x'
                            }
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
        
        function toggleComparisonView() {
            comparisonChartView = comparisonChartView === 'bar' ? 'line' : 'bar';
            document.getElementById('comparisonViewText').textContent = comparisonChartView === 'bar' ? 'Switch to Line' : 'Switch to Bar';
            initComparisonChart();
        }

        // Trend Chart with Prediction
        function initTrendChart() {
            const ctx = document.getElementById('trendChart').getContext('2d');
            
            if (charts.trend) {
                charts.trend.destroy();
            }
            
            const trendData = @json($trendData);
            const months = trendData.months.map(m => m.month);
            const totals = trendData.months.map(m => m.total);
            const accepted = trendData.months.map(m => m.accepted);
            
            // Add prediction point
            months.push('Next Month');
            totals.push(trendData.prediction.next_month);
            accepted.push(null);
            
            charts.trend = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Total Applications',
                        data: totals,
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }, {
                        label: 'Accepted',
                        data: accepted,
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 4,
                        borderDash: [5, 5]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        zoom: {
                            zoom: {
                                wheel: {
                                    enabled: true
                                },
                                pinch: {
                                    enabled: true
                                },
                                mode: 'x'
                            },
                            pan: {
                                enabled: true,
                                mode: 'x'
                            }
                        },
                        annotation: {
                            annotations: {
                                prediction: {
                                    type: 'line',
                                    xMin: months.length - 1,
                                    xMax: months.length - 1,
                                    borderColor: '#F59E0B',
                                    borderWidth: 2,
                                    borderDash: [5, 5],
                                    label: {
                                        content: 'Prediction',
                                        enabled: true,
                                        position: 'end'
                                    }
                                }
                            }
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

        // Sankey Diagram - Using ApexCharts Flow Diagram
        function initSankeyChart() {
            const sankeyData = @json($sankeyData);
            const container = document.getElementById('sankeyChart');
            container.innerHTML = '';
            
            if (!sankeyData.nodes || sankeyData.nodes.length === 0) {
                container.innerHTML = '<div style="text-align: center; padding: 50px;">No data available for Sankey diagram</div>';
                return;
            }
            
            // Prepare data for ApexCharts flow
            const series = [];
            sankeyData.links.forEach(link => {
                if (link.value > 0) {
                    series.push({
                        from: sankeyData.nodes[link.source].name,
                        to: sankeyData.nodes[link.target].name,
                        weight: link.value
                    });
                }
            });
            
            // Create flow visualization using ApexCharts
            const sankeyChart = new ApexCharts(container, {
                chart: {
                    type: 'treemap',
                    height: 400,
                    toolbar: {
                        show: true
                    }
                },
                series: [{
                    data: sankeyData.nodes.map((node, index) => ({
                        x: node.name,
                        y: sankeyData.stage_counts[index] || 0
                    }))
                }],
                colors: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#06B6D4', '#EC4899', '#6366F1', '#84CC16'],
                dataLabels: {
                    enabled: true,
                    formatter: function(val, opts) {
                        return opts.w.globals.series[opts.seriesIndex][opts.dataPointIndex] + ' apps';
                    }
                }
            });
            
            sankeyChart.render();
        }

        // Gantt Chart
        function initGanttChart() {
            const ganttData = @json($ganttData);
            const container = document.getElementById('ganttChart');
            container.innerHTML = '';
            
            if (ganttData.length === 0) {
                container.innerHTML = '<div style="text-align: center; padding: 50px;">No data available for Gantt chart</div>';
                return;
            }
            
            // Prepare data for ApexCharts timeline
            const series = ganttData.map(item => ({
                x: item.company + ' - ' + item.position,
                y: [
                    new Date(item.start).getTime(),
                    new Date(item.end).getTime()
                ],
                fillColor: item.color
            }));
            
            const ganttChart = new ApexCharts(container, {
                chart: {
                    type: 'rangeBar',
                    height: Math.max(400, ganttData.length * 30),
                    toolbar: {
                        show: true,
                        tools: {
                            zoom: true,
                            zoomin: true,
                            zoomout: true,
                            pan: true,
                            reset: true
                        }
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        barHeight: '80%',
                        rangeBarGroupRows: true
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        const a = new Date(val[0]);
                        const b = new Date(val[1]);
                        const diffTime = Math.abs(b - a);
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                        return diffDays + ' days';
                    }
                },
                series: [{
                    name: 'Application Timeline',
                    data: series
                }],
                xaxis: {
                    type: 'datetime'
                },
                yaxis: {
                    show: true
                },
                grid: {
                    row: {
                        colors: ['#f3f4f6', '#ffffff'],
                        opacity: 0.5
                    }
                }
            });
            
            ganttChart.render();
        }

        // Heatmap Chart
        let heatmapView = 'daily';
        function initHeatmapChart() {
            const heatmapData = @json($heatmapData);
            const container = document.getElementById('heatmapChart');
            container.innerHTML = '';
            
            if (heatmapView === 'daily') {
                // Daily heatmap using ApexCharts
                const dailyData = heatmapData.daily.slice(-365); // Last year
                const series = dailyData.map(item => ({
                    x: item.date,
                    y: item.value
                }));
                
                const heatmapChart = new ApexCharts(container, {
                    chart: {
                        type: 'heatmap',
                        height: 400,
                        toolbar: {
                            show: true
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    colors: ['#10B981'],
                    series: [{
                        name: 'Applications',
                        data: series
                    }],
                    xaxis: {
                        type: 'datetime'
                    },
                    plotOptions: {
                        heatmap: {
                            shadeIntensity: 0.5,
                            colorScale: {
                                ranges: [
                                    { from: 0, to: 0, color: '#f3f4f6', name: '0' },
                                    { from: 1, to: 1, color: '#dbeafe', name: '1' },
                                    { from: 2, to: 2, color: '#93c5fd', name: '2' },
                                    { from: 3, to: 3, color: '#3b82f6', name: '3+' }
                                ]
                            }
                        }
                    }
                });
                
                heatmapChart.render();
            } else {
                // Weekly heatmap
                const weeklyData = heatmapData.weekly;
                const series = weeklyData.map(item => ({
                    x: item.week,
                    y: item.total
                }));
                
                const heatmapChart = new ApexCharts(container, {
                    chart: {
                        type: 'heatmap',
                        height: 400
                    },
                    dataLabels: {
                        enabled: true
                    },
                    colors: ['#10B981'],
                    series: [{
                        name: 'Applications per Week',
                        data: series
                    }],
                    plotOptions: {
                        heatmap: {
                            shadeIntensity: 0.5,
                            colorScale: {
                                ranges: [
                                    { from: 0, to: 0, color: '#f3f4f6' },
                                    { from: 1, to: 5, color: '#dbeafe' },
                                    { from: 6, to: 10, color: '#93c5fd' },
                                    { from: 11, to: 20, color: '#3b82f6' },
                                    { from: 21, to: 999, color: '#1e40af' }
                                ]
                            }
                        }
                    }
                });
                
                heatmapChart.render();
            }
        }
        
        function toggleHeatmapView() {
            heatmapView = heatmapView === 'daily' ? 'weekly' : 'daily';
            document.getElementById('heatmapViewText').textContent = heatmapView === 'daily' ? 'Weekly View' : 'Daily View';
            initHeatmapChart();
        }

        // Initialize charts on page load
        document.addEventListener('DOMContentLoaded', function() {
            initAllCharts();
        });
    </script>
</x-app-layout>
