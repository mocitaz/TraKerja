<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">📅 Interview Calendar</h1>
        <p class="text-sm text-gray-600 mt-1">Manage and track all your scheduled interviews</p>
    </div>

    <!-- Controls -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <!-- View Toggle -->
            <div class="flex items-center space-x-2">
                <button wire:click="toggleViewMode" 
                        class="flex items-center space-x-2 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    @if($viewMode === 'month')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        <span>List View</span>
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Calendar View</span>
                    @endif
                </button>

                <!-- Filter by type -->
                <select wire:model.live="filterType" 
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                    <option value="all">All Interviews</option>
                    <option value="HR - Interview">👔 HR Interview</option>
                    <option value="User - Interview">👤 User Interview</option>
                </select>
            </div>

            <!-- Month Navigation -->
            <div class="flex items-center space-x-4">
                <button wire:click="previousMonth" 
                        class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>

                <h2 class="text-lg font-semibold text-gray-900 min-w-[150px] text-center">{{ $monthName }}</h2>

                <button wire:click="nextMonth" 
                        class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                <button wire:click="goToToday" 
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium text-gray-700 transition-colors">
                    Today
                </button>
            </div>
        </div>
    </div>

    @if($viewMode === 'month')
        <!-- Calendar View -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <!-- Day Headers -->
            <div class="grid grid-cols-7 bg-gray-50 border-b border-gray-200">
                @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                    <div class="px-2 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        {{ $day }}
                    </div>
                @endforeach
            </div>

            <!-- Calendar Grid -->
            <div class="grid grid-cols-7 divide-x divide-gray-200">
                @foreach($calendarDays as $day)
                    <div class="min-h-[120px] p-2 border-b border-gray-200 {{ $day['isCurrentMonth'] ? 'bg-white' : 'bg-gray-50' }} {{ $day['isToday'] ? 'bg-primary-50' : '' }}">
                        <!-- Date Number -->
                        <div class="flex justify-between items-start mb-1">
                            <span class="text-sm font-medium {{ $day['isCurrentMonth'] ? 'text-gray-900' : 'text-gray-400' }} {{ $day['isToday'] ? 'bg-primary-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs' : '' }}">
                                {{ $day['date']->format('j') }}
                            </span>
                        </div>

                        <!-- Interviews on this day -->
                        @if(count($day['interviews']) > 0)
                            <div class="space-y-1">
                                @foreach($day['interviews'] as $interview)
                                    <div wire:click="viewInterviewDetails({{ $interview['id'] }})" 
                                         class="p-1.5 rounded text-xs cursor-pointer hover:opacity-80 transition-opacity
                                                {{ $interview['recruitment_stage'] === 'HR - Interview' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $interview['recruitment_stage'] === 'User - Interview' ? 'bg-green-100 text-green-800' : '' }}">
                                        <div class="font-medium truncate">{{ $interview['company_name'] }}</div>
                                        <div class="text-xs opacity-75">
                                            {{ $interview['recruitment_stage'] === 'HR - Interview' ? '👔 HR' : '👤 User' }} - 
                                            {{ \Carbon\Carbon::parse($interview['interview_date'])->timezone('Asia/Jakarta')->format('H:i') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <!-- List View -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 divide-y divide-gray-200">
            @if(count($this->allInterviewsList) > 0)
                @foreach($this->allInterviewsList as $interview)
                    <div wire:click="viewInterviewDetails({{ $interview->id }})" 
                         class="p-4 hover:bg-gray-50 cursor-pointer transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3">
                                    <!-- Interview Stage Badge -->
                                    <span class="px-2 py-1 rounded text-xs font-medium
                                                 {{ $interview->recruitment_stage === 'HR - Interview' ? 'bg-blue-100 text-blue-800' : '' }}
                                                 {{ $interview->recruitment_stage === 'User - Interview' ? 'bg-green-100 text-green-800' : '' }}">
                                        {{ $interview->recruitment_stage === 'HR - Interview' ? '👔 HR Interview' : '👤 User Interview' }}
                                    </span>

                                    <h3 class="text-lg font-semibold text-gray-900">{{ $interview->company_name }}</h3>
                                </div>

                                <p class="text-sm text-gray-600 mt-1">{{ $interview->position }}</p>

                                <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                                    <div class="flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>{{ $interview->interview_date->format('D, d M Y') }}</span>
                                    </div>
                                    <div class="flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>{{ $interview->interview_date->format('H:i') }}</span>
                                    </div>
                                    @if($interview->interview_type)
                                        <div class="flex items-center space-x-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                            <span>{{ $interview->interview_type }}</span>
                                        </div>
                                    @endif
                                    @if($interview->interview_location)
                                        <div class="flex items-center space-x-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span class="truncate max-w-xs">{{ $interview->interview_location }}</span>
                                        </div>
                                    @endif
                                </div>

                                @if($interview->interview_notes)
                                    <p class="text-sm text-gray-600 mt-2 italic">{{ Str::limit($interview->interview_notes, 100) }}</p>
                                @endif
                            </div>

                            <!-- Time Until Interview -->
                            <div class="text-right ml-4">
                                @if($interview->interview_date->isFuture())
                                    <span class="text-xs font-medium text-primary-600">
                                        {{ $interview->interview_date->diffForHumans() }}
                                    </span>
                                @else
                                    <span class="text-xs font-medium text-gray-400">
                                        Past
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Upcoming Interviews</h3>
                    <p class="text-sm text-gray-600">Schedule interviews by updating your job applications to interview stage</p>
                </div>
            @endif
        </div>
    @endif

    <!-- Upcoming Interviews Sidebar (Only in Calendar View) -->
    @if($viewMode === 'month' && count($this->upcomingInterviews) > 0)
        <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">🔔 Upcoming Interviews</h3>
            <div class="space-y-3">
                @foreach($this->upcomingInterviews as $interview)
                    <div wire:click="viewInterviewDetails({{ $interview->id }})" 
                         class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $interview->company_name }}</h4>
                                <p class="text-xs text-gray-600 mt-1">{{ $interview->position }}</p>
                                <div class="flex items-center space-x-2 mt-2 text-xs text-gray-500">
                                    <span>{{ $interview->interview_date->format('d M, H:i') }}</span>
                                    <span class="text-gray-400">•</span>
                                    <span class="px-2 py-0.5 rounded text-xs
                                                 {{ $interview->recruitment_stage === 'HR - Interview' ? 'bg-blue-100 text-blue-700' : '' }}
                                                 {{ $interview->recruitment_stage === 'User - Interview' ? 'bg-green-100 text-green-700' : '' }}">
                                        {{ $interview->recruitment_stage === 'HR - Interview' ? '👔 HR' : '👤 User' }}
                                    </span>
                                    @if($interview->interview_type)
                                        <span class="text-gray-400">•</span>
                                        <span>{{ $interview->interview_type }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right text-xs font-medium text-primary-600">
                                {{ $interview->interview_date->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Interview Detail Modal -->
    @if($showModal && $selectedInterview)
        <div class="fixed inset-0 z-50 overflow-y-auto" 
             x-data="{ closing: false }" 
             x-init="document.body.style.overflow = 'hidden'"
             @keydown.escape.window="closing = true; document.body.style.overflow = 'auto'; $wire.closeModal()">
            
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity" 
                 @click="closing = true; document.body.style.overflow = 'auto'; $wire.closeModal()"></div>
            
            <!-- Modal Container -->
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl">
                    
                    <!-- Modal Header -->
                    <div class="sticky top-0 z-10 bg-gradient-to-r from-primary-600 to-primary-700 px-6 py-4 rounded-t-2xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white">Interview Details</h3>
                                    <p class="text-white/80 text-sm">{{ $selectedInterview->company_name }}</p>
                                </div>
                            </div>
                            <button type="button"
                                    @click="closing = true; document.body.style.overflow = 'auto'; $wire.closeModal()" 
                                    class="text-white/80 hover:text-white transition p-2 hover:bg-white/10 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Content -->
                    <div class="p-6 space-y-5">
                        <!-- Interview Stage Badge -->
                        <div class="flex items-center space-x-3">
                            <span class="px-4 py-2 rounded-lg text-sm font-semibold
                                         {{ $selectedInterview->recruitment_stage === 'HR - Interview' ? 'bg-blue-100 text-blue-800' : '' }}
                                         {{ $selectedInterview->recruitment_stage === 'User - Interview' ? 'bg-green-100 text-green-800' : '' }}">
                                {{ $selectedInterview->recruitment_stage === 'HR - Interview' ? '👔 HR Interview' : '👤 User Interview' }}
                            </span>
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs font-medium">
                                {{ $selectedInterview->application_status }}
                            </span>
                        </div>

                        <!-- Position -->
                        <div>
                            <label class="text-sm font-medium text-gray-500">Position</label>
                            <p class="text-lg font-semibold text-gray-900 mt-1">{{ $selectedInterview->position }}</p>
                        </div>

                        <!-- Date & Time -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center space-x-2 text-gray-500 mb-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-xs font-medium">Date</span>
                                </div>
                                <p class="text-sm font-semibold text-gray-900">{{ $selectedInterview->interview_date->format('l, d F Y') }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center space-x-2 text-gray-500 mb-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-xs font-medium">Time (WIB)</span>
                                </div>
                                <p class="text-sm font-semibold text-gray-900">{{ $selectedInterview->interview_date->format('H:i') }}</p>
                            </div>
                        </div>

                        <!-- Time Until Interview -->
                        @if($selectedInterview->interview_date->isFuture())
                            <div class="bg-primary-50 border border-primary-200 rounded-lg p-4">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm font-semibold text-primary-900">{{ $selectedInterview->interview_date->diffForHumans() }}</span>
                                </div>
                            </div>
                        @else
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-600">Interview completed</span>
                                </div>
                            </div>
                        @endif

                        <!-- Interview Type -->
                        @if($selectedInterview->interview_type)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Interview Type</label>
                                <p class="text-base font-medium text-gray-900 mt-1">{{ $selectedInterview->interview_type }}</p>
                            </div>
                        @endif

                        <!-- Location -->
                        @if($selectedInterview->interview_location)
                            <div>
                                <label class="text-sm font-medium text-gray-500 flex items-center space-x-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>Location / Link</span>
                                </label>
                                <p class="text-sm text-gray-900 mt-1 break-all">{{ $selectedInterview->interview_location }}</p>
                            </div>
                        @endif

                        <!-- Notes -->
                        @if($selectedInterview->interview_notes)
                            <div>
                                <label class="text-sm font-medium text-gray-500 flex items-center space-x-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <span>Notes</span>
                                </label>
                                <div class="mt-1 bg-gray-50 p-3 rounded-lg">
                                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $selectedInterview->interview_notes }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Job Location -->
                        <div class="pt-4 border-t border-gray-200">
                            <label class="text-sm font-medium text-gray-500">Job Location</label>
                            <p class="text-sm text-gray-900 mt-1">{{ $selectedInterview->location }}</p>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="sticky bottom-0 bg-gray-50 border-t border-gray-200 px-6 py-4 rounded-b-2xl flex items-center justify-end gap-3">
                        <button type="button"
                                @click="closing = true; document.body.style.overflow = 'auto'; $wire.closeModal()"
                                class="px-5 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-medium">
                            Close
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif
</div>
