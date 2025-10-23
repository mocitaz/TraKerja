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
                    <option value="all">All Types</option>
                    <option value="Phone">Phone</option>
                    <option value="Video">Video</option>
                    <option value="In-person">In-person</option>
                    <option value="Panel">Panel</option>
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
                                    <div wire:click="editInterview({{ $interview['id'] }})" 
                                         class="p-1.5 rounded text-xs cursor-pointer hover:opacity-80 transition-opacity
                                                {{ $interview['interview_type'] === 'Phone' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $interview['interview_type'] === 'Video' ? 'bg-primary-100 text-primary-800' : '' }}
                                                {{ $interview['interview_type'] === 'In-person' ? 'bg-purple-100 text-purple-800' : '' }}
                                                {{ $interview['interview_type'] === 'Panel' ? 'bg-orange-100 text-orange-800' : '' }}">
                                        <div class="font-medium truncate">{{ $interview['company_name'] }}</div>
                                        <div class="text-xs opacity-75">{{ \Carbon\Carbon::parse($interview['interview_date'])->format('H:i') }}</div>
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
            @if(count($upcomingInterviews) > 0)
                @foreach($upcomingInterviews as $interview)
                    <div wire:click="editInterview({{ $interview->id }})" 
                         class="p-4 hover:bg-gray-50 cursor-pointer transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3">
                                    <!-- Interview Type Badge -->
                                    <span class="px-2 py-1 rounded text-xs font-medium
                                                 {{ $interview->interview_type === 'Phone' ? 'bg-green-100 text-green-800' : '' }}
                                                 {{ $interview->interview_type === 'Video' ? 'bg-primary-100 text-primary-800' : '' }}
                                                 {{ $interview->interview_type === 'In-person' ? 'bg-purple-100 text-purple-800' : '' }}
                                                 {{ $interview->interview_type === 'Panel' ? 'bg-orange-100 text-orange-800' : '' }}">
                                        {{ $interview->interview_type }}
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
    @if($viewMode === 'month' && count($upcomingInterviews) > 0)
        <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">🔔 Upcoming Interviews</h3>
            <div class="space-y-3">
                @foreach($upcomingInterviews as $interview)
                    <div wire:click="editInterview({{ $interview->id }})" 
                         class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $interview->company_name }}</h4>
                                <p class="text-xs text-gray-600 mt-1">{{ $interview->position }}</p>
                                <div class="flex items-center space-x-2 mt-2 text-xs text-gray-500">
                                    <span>{{ $interview->interview_date->format('d M, H:i') }}</span>
                                    <span class="text-gray-400">•</span>
                                    <span class="px-2 py-0.5 rounded text-xs
                                                 {{ $interview->interview_type === 'Phone' ? 'bg-green-100 text-green-700' : '' }}
                                                 {{ $interview->interview_type === 'Video' ? 'bg-primary-100 text-primary-700' : '' }}
                                                 {{ $interview->interview_type === 'In-person' ? 'bg-purple-100 text-purple-700' : '' }}
                                                 {{ $interview->interview_type === 'Panel' ? 'bg-orange-100 text-orange-700' : '' }}">
                                        {{ $interview->interview_type }}
                                    </span>
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
</div>
