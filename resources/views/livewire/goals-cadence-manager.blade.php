<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-green-700 font-medium">{{ session('message') }}</span>
            </div>
        </div>
    @endif

    <!-- Goals Ending Soon Alert -->
    @if($this->goalsEndingSoon)
        <div class="mb-6 bg-gradient-to-r from-orange-50 to-red-50 border border-orange-200 rounded-lg p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-lg font-bold text-orange-800">⚠️ Goals Ending Soon!</h3>
                    <div class="mt-2 text-orange-700">
                        <p class="text-sm">
                            Only <span class="font-bold">{{ $this->daysLeft }} day(s)</span> left in your current goal period!
                        </p>
                        <p class="text-sm mt-1">
                            You've completed <span class="font-bold">{{ $this->appliedProgress }}%</span> of your application target.
                            @if($this->appliedProgress < 50)
                                <span class="font-semibold">Time to accelerate!</span>
                            @elseif($this->appliedProgress < 80)
                                <span class="font-semibold">You're close, keep pushing!</span>
                            @endif
                        </p>
                    </div>
                    <div class="mt-4">
                        <button 
                            wire:click="openGoalModal"
                            class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition-colors"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Set New Goals
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- No Goals Set Message -->
    @if(!$this->hasGoals)
        <div class="mb-6 bg-primary-50 border border-primary-200 rounded-lg p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-lg font-bold text-primary-800">🎯 Ready to Set Your Goals?</h3>
                    <div class="mt-2 text-primary-700">
                        <p class="text-sm">
                            Set weekly or monthly targets to track your job application progress and stay motivated!
                        </p>
                        <p class="text-sm mt-1">
                            Goals help you maintain consistency and measure your success over time.
                        </p>
                    </div>
                    <div class="mt-4">
                        <button 
                            wire:click="openGoalModal"
                            class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Set Your Goals
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content - Focus on Progress -->
    <div class="space-y-8">
        <!-- Progress Overview Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- This Week's Progress -->
            <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-[#0056B3] to-[#003D82] rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">{{ $goalPeriod === 'monthly' ? 'This Month\'s Progress' : 'This Week\'s Progress' }}</h2>
                            <p class="text-sm text-gray-600">Target vs Actual Performance</p>
                        </div>
                    </div>
                    <button 
                        wire:click="openGoalModal"
                        class="px-4 py-2 bg-gradient-to-r from-[#0056B3] to-[#003D82] text-white text-sm font-medium rounded-lg hover:from-[#003D82] hover:to-[#002A5C] transition-all duration-200 flex items-center space-x-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>Set Goals</span>
                    </button>
                </div>

                <!-- Applied Progress -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Applications</span>
                        <span class="text-sm font-bold text-gray-900">{{ $this->actualApplied }} / {{ $targetAppliedWeekly }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div 
                            class="bg-gradient-to-r from-[#0056B3] to-[#003D82] h-3 rounded-full transition-all duration-500"
                            style="width: {{ min(100, $this->appliedProgress) }}%"
                        ></div>
                    </div>
                    <div class="flex justify-between items-center mt-1">
                        <span class="text-xs text-gray-500">{{ $this->appliedProgress }}% achieved</span>
                        @if($this->appliedProgress >= 100)
                            <span class="text-xs font-medium text-green-600">🎯 Target achieved!</span>
                        @elseif($this->appliedProgress >= 80)
                            <span class="text-xs font-medium text-yellow-600">🔥 Almost there!</span>
                        @else
                            <span class="text-xs font-medium text-gray-500">Keep going!</span>
                        @endif
                    </div>
                </div>

                <!-- Follow-up Progress -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Follow-ups</span>
                        <span class="text-sm font-bold text-gray-900">{{ $this->followUpCount }} / {{ $targetFollowupWeekly }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div 
                            class="bg-gradient-to-r from-[#28A745] to-[#1E7E34] h-3 rounded-full transition-all duration-500"
                            style="width: {{ min(100, $this->followupProgress) }}%"
                        ></div>
                    </div>
                    <div class="flex justify-between items-center mt-1">
                        <span class="text-xs text-gray-500">{{ $this->followupProgress }}% achieved</span>
                        @if($this->followupProgress >= 100)
                            <span class="text-xs font-medium text-green-600">🎯 Target achieved!</span>
                        @elseif($this->followupProgress >= 80)
                            <span class="text-xs font-medium text-yellow-600">🔥 Almost there!</span>
                        @else
                            <span class="text-xs font-medium text-gray-500">Keep going!</span>
                        @endif
                    </div>
                </div>

                <!-- Interviews Count -->
                <div class="bg-primary-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-primary-900">Interviews {{ $goalPeriod === 'monthly' ? 'This Month' : 'This Week' }}</p>
                            <p class="text-2xl font-bold text-primary-600">{{ $this->actualInterviews }}</p>
                        </div>
                        <div class="w-8 h-8 bg-[#0056B3] rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daily Streak -->
            <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-[#FF6B35] to-[#E55A2B] rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Daily Streak</h3>
                            <p class="text-sm text-gray-600">Consecutive days meeting daily target</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-gray-500 mb-1">Daily Target</div>
                        <div class="text-sm font-semibold text-gray-700">{{ max(1, ceil($targetAppliedWeekly / 5)) }} apps/day</div>
                    </div>
                </div>
                
                <!-- Streak Display -->
                <div class="text-center mb-6">
                    <div class="text-5xl font-bold text-[#FF6B35] mb-2">{{ $this->currentStreak }}</div>
                    <p class="text-sm text-gray-600 mb-3">days in a row</p>
                    
                    <!-- Achievement Badge -->
                    @if($this->currentStreak >= 7)
                        <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-100 to-orange-100 border border-yellow-200 rounded-full">
                            <span class="text-yellow-800 text-sm font-medium">On Fire!</span>
                        </div>
                    @elseif($this->currentStreak >= 3)
                        <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-100 to-blue-100 border border-primary-200 rounded-full">
                            <span class="text-primary-800 text-sm font-medium">Good Streak!</span>
                        </div>
                    @elseif($this->currentStreak >= 1)
                        <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-gray-100 to-gray-100 border border-gray-200 rounded-full">
                            <span class="text-gray-800 text-sm font-medium">Keep Going!</span>
                        </div>
                    @else
                        <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-100 to-red-100 border border-red-200 rounded-full">
                            <span class="text-red-800 text-sm font-medium">Start Your Streak!</span>
                        </div>
                    @endif
                </div>

                <!-- Progress Bar -->
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs text-gray-500">Streak Progress</span>
                        <span class="text-xs text-gray-500">{{ $this->currentStreak }}/30 days</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div 
                            class="bg-gradient-to-r from-[#FF6B35] to-[#E55A2B] h-2 rounded-full transition-all duration-500"
                            style="width: {{ min(100, ($this->currentStreak / 30) * 100) }}%"
                        ></div>
                    </div>
                </div>

                <!-- Streak Stats -->
                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                    <div class="text-center">
                        <div class="text-lg font-bold text-gray-900">{{ $this->currentStreak }}</div>
                        <div class="text-xs text-gray-500">Current Streak</div>
                    </div>
                    <div class="text-center">
                        <div class="text-lg font-bold text-gray-900">{{ max(1, ceil($targetAppliedWeekly / 5)) }}</div>
                        <div class="text-xs text-gray-500">Daily Target</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cadence Effect Insights -->
        <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-[#6F42C1] to-[#5A2D91] rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">The Cadence Effect</h2>
                    <p class="text-sm text-gray-600">Analysis of discipline correlation with conversion rate</p>
                </div>
            </div>

            @if($this->cadenceEffect['insufficient_data'])
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Insufficient Data</h3>
                    <p class="text-gray-600">Need at least 2 weeks of data for cadence effect analysis</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Target Met Rate -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $this->cadenceEffect['target_met_rate'] }}%</h3>
                        <p class="text-sm text-gray-600">Conversion Rate<br><span class="font-medium">Target Met</span></p>
                    </div>

                    <!-- Target Missed Rate -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $this->cadenceEffect['target_missed_rate'] }}%</h3>
                        <p class="text-sm text-gray-600">Conversion Rate<br><span class="font-medium">Target Missed</span></p>
                    </div>

                    <!-- Difference -->
                    <div class="text-center">
                        <div class="w-16 h-16 {{ $this->cadenceEffect['difference'] >= 0 ? 'bg-primary-100' : 'bg-orange-100' }} rounded-full flex items-center justify-center mx-auto mb-3">
                            @if($this->cadenceEffect['difference'] >= 0)
                                <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                </svg>
                            @else
                                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                                </svg>
                            @endif
                        </div>
                        <h3 class="text-lg font-bold {{ $this->cadenceEffect['difference'] >= 0 ? 'text-primary-600' : 'text-orange-600' }}">
                            {{ $this->cadenceEffect['difference'] >= 0 ? '+' : '' }}{{ $this->cadenceEffect['difference'] }}%
                        </h3>
                        <p class="text-sm text-gray-600">Difference<br><span class="font-medium">Cadence Effect</span></p>
                    </div>
                </div>

                <!-- Insight Text -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    @if($this->cadenceEffect['difference'] > 5)
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <div>
                                <h4 class="font-medium text-green-800">Excellent Cadence Discipline!</h4>
                                <p class="text-sm text-green-700 mt-1">Your target discipline significantly improves conversion rate. Keep maintaining this momentum!</p>
                            </div>
                        </div>
                    @elseif($this->cadenceEffect['difference'] > 0)
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-primary-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h4 class="font-medium text-primary-800">Positive Cadence Effect</h4>
                                <p class="text-sm text-primary-700 mt-1">Target discipline has a positive impact on conversion rate. Increase consistency for better results.</p>
                            </div>
                        </div>
                    @else
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-orange-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <div>
                                <h4 class="font-medium text-orange-800">Room for Improvement</h4>
                                <p class="text-sm text-orange-700 mt-1">Missed targets impact conversion rate. Focus on consistency to improve results.</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Set Goals Modal -->
    @if($showGoalModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-data="{ show: @entangle('showGoalModal') }" x-show="show">
            <div 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="bg-white rounded-xl shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto"
            >
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-[#0056B3] to-[#003D82] rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Set {{ ucfirst($goalPeriod) }} Goals</h2>
                            <p class="text-sm text-gray-600">Define your {{ $goalPeriod }} application and follow-up targets</p>
                        </div>
                    </div>
                    <button 
                        wire:click="closeGoalModal"
                        class="text-gray-400 hover:text-gray-600 transition-colors"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="p-6">
                    <form wire:submit.prevent="setWeeklyGoals" class="space-y-6">
                        <!-- Goal Period Toggle -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Goal Period
                            </label>
                            <div class="flex space-x-4">
                                <label class="flex items-center">
                                    <input 
                                        type="radio" 
                                        wire:model="goalPeriod" 
                                        value="weekly"
                                        class="w-4 h-4 text-[#0056B3] border-gray-300 focus:ring-[#0056B3]"
                                    >
                                    <span class="ml-2 text-sm text-gray-700">Weekly</span>
                                </label>
                                <label class="flex items-center">
                                    <input 
                                        type="radio" 
                                        wire:model="goalPeriod" 
                                        value="monthly"
                                        class="w-4 h-4 text-[#0056B3] border-gray-300 focus:ring-[#0056B3]"
                                    >
                                    <span class="ml-2 text-sm text-gray-700">Monthly</span>
                                </label>
                            </div>
                        </div>

                        <!-- Target Applied -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $goalPeriod === 'monthly' ? 'Monthly' : 'Weekly' }} Application Target
                            </label>
                            <div class="grid grid-cols-5 gap-2">
                                @if($goalPeriod === 'weekly')
                                    @foreach([3, 5, 7, 10, 15] as $target)
                                        <button 
                                            type="button"
                                            wire:click="$set('targetAppliedWeekly', {{ $target }})"
                                            class="px-3 py-2 text-sm font-medium rounded-lg border transition-all duration-200 {{ $targetAppliedWeekly == $target ? 'bg-[#0056B3] text-white border-[#0056B3]' : 'bg-white text-gray-700 border-gray-300 hover:border-[#0056B3] hover:text-[#0056B3]' }}"
                                        >
                                            {{ $target }}
                                        </button>
                                    @endforeach
                                @else
                                            @foreach([50, 100, 200, 500, 1000] as $target)
                                        <button 
                                            type="button"
                                            wire:click="$set('targetAppliedWeekly', {{ $target }})"
                                            class="px-3 py-2 text-sm font-medium rounded-lg border transition-all duration-200 {{ $targetAppliedWeekly == $target ? 'bg-[#0056B3] text-white border-[#0056B3]' : 'bg-white text-gray-700 border-gray-300 hover:border-[#0056B3] hover:text-[#0056B3]' }}"
                                        >
                                            {{ $target }}
                                        </button>
                                    @endforeach
                                @endif
                            </div>
                            <div class="mt-2">
                                <input 
                                    type="number" 
                                    wire:model="targetAppliedWeekly"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0056B3] focus:border-transparent"
                                    placeholder="Custom target (minimum 1)"
                                    min="1"
                                >
                            </div>
                            @error('targetAppliedWeekly') 
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                            @enderror
                        </div>

                        <!-- Target Follow-up -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $goalPeriod === 'monthly' ? 'Monthly' : 'Weekly' }} Follow-up Target
                            </label>
                            <div class="grid grid-cols-5 gap-2">
                                @if($goalPeriod === 'weekly')
                                    @foreach([1, 2, 3, 5, 7] as $target)
                                        <button 
                                            type="button"
                                            wire:click="$set('targetFollowupWeekly', {{ $target }})"
                                            class="px-3 py-2 text-sm font-medium rounded-lg border transition-all duration-200 {{ $targetFollowupWeekly == $target ? 'bg-[#28A745] text-white border-[#28A745]' : 'bg-white text-gray-700 border-gray-300 hover:border-[#28A745] hover:text-[#28A745]' }}"
                                        >
                                            {{ $target }}
                                        </button>
                                    @endforeach
                                @else
                                            @foreach([25, 50, 100, 200, 500] as $target)
                                        <button 
                                            type="button"
                                            wire:click="$set('targetFollowupWeekly', {{ $target }})"
                                            class="px-3 py-2 text-sm font-medium rounded-lg border transition-all duration-200 {{ $targetFollowupWeekly == $target ? 'bg-[#28A745] text-white border-[#28A745]' : 'bg-white text-gray-700 border-gray-300 hover:border-[#28A745] hover:text-[#28A745]' }}"
                                        >
                                            {{ $target }}
                                        </button>
                                    @endforeach
                                @endif
                            </div>
                            <div class="mt-2">
                                <input 
                                    type="number" 
                                    wire:model="targetFollowupWeekly"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#28A745] focus:border-transparent"
                                    placeholder="Custom target (minimum 0)"
                                    min="0"
                                >
                            </div>
                            @error('targetFollowupWeekly') 
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                            @enderror
                        </div>

                        <!-- Date Range -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Start Date
                                </label>
                                <input 
                                    type="date" 
                                    wire:model="startDate"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0056B3] focus:border-transparent"
                                >
                                @error('startDate') 
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    End Date
                                </label>
                                <input 
                                    type="date" 
                                    wire:model="endDate"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0056B3] focus:border-transparent"
                                >
                                @error('endDate') 
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-3 pt-4">
                            <button 
                                type="submit"
                                class="flex-1 bg-gradient-to-r from-[#0056B3] to-[#003D82] text-white py-3 px-6 rounded-lg font-medium hover:from-[#003D82] hover:to-[#002A5C] transition-all duration-200 flex items-center justify-center"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Save {{ ucfirst($goalPeriod) }} Goals
                            </button>
                            <button 
                                type="button"
                                wire:click="closeGoalModal"
                                class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-all duration-200"
                            >
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
