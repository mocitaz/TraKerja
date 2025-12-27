<div>
    <div class="space-y-3">
        <!-- Compact Search and Filter Bar -->
        <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] overflow-hidden">
            <div class="p-3">
                <!-- Search Input -->
                <div class="mb-2.5">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input wire:model.live.debounce.300ms="search" 
                               type="text" 
                               placeholder="Search company, position, location..." 
                               class="block w-full pl-8 pr-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm">
                    </div>
                </div>

                <!-- Filters Row - Desktop Only -->
                <div class="hidden sm:grid grid-cols-4 gap-2.5 mb-2.5">
                    <!-- Status Filter -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                        <select wire:model.live="statusFilter" 
                                class="block w-full px-2.5 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm bg-white">
                            <option value="">All Status</option>
                            @foreach($statusOptions as $status)
                                <option value="{{ $status }}">{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Platform Filter -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Platform</label>
                        <select wire:model.live="platformFilter" 
                                class="block w-full px-2.5 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm bg-white">
                            <option value="">All Platforms</option>
                            @foreach($platformOptions as $platform)
                                <option value="{{ $platform }}">{{ $platform }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Recruitment Stage Filter -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Stage</label>
                        <select wire:model.live="recruitmentStageFilter" 
                                class="block w-full px-2.5 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm bg-white">
                            <option value="">All Stages</option>
                            @foreach($recruitmentStageOptions as $stage)
                                <option value="{{ $stage }}">{{ $stage }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Per Page - Compact -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Per Page</label>
                        <select wire:model.live="perPage" 
                                class="block w-full px-2.5 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm bg-white">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons - Compact Single Row -->
                <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-gray-100">
                    <!-- Mobile Filter Icon Button -->
                    <button onclick="openFilterModal()" 
                            class="sm:hidden relative p-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                        <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        @if($statusFilter || $platformFilter || $recruitmentStageFilter)
                            <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full flex items-center justify-center">
                                <span class="text-[8px] font-bold text-white">{{ ($statusFilter ? 1 : 0) + ($platformFilter ? 1 : 0) + ($recruitmentStageFilter ? 1 : 0) }}</span>
                            </span>
                        @endif
                    </button>
                    <button wire:click="clearFilters" 
                            class="hidden sm:inline-flex px-3 py-1.5 text-xs font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 border border-gray-200 rounded-lg transition-colors whitespace-nowrap">
                        Clear Filters
                    </button>
                    <button wire:click="toggleArchived" 
                            class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg transition-colors whitespace-nowrap {{ $showArchived ? 'bg-purple-600 text-white hover:bg-purple-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-200' }}">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                        <span class="hidden sm:inline">{{ $showArchived ? 'Show Active' : 'Show Archived' }}</span>
                        <span class="sm:hidden">{{ $showArchived ? 'Active' : 'Archived' }}</span>
                    </button>
                    @if($showArchived && $archivedCount > 0)
                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-gray-700 bg-gray-50 rounded-lg border border-gray-200 whitespace-nowrap">
                            <span class="font-bold text-purple-600">{{ $archivedCount }}</span>
                            <span class="hidden sm:inline">Archived</span>
                        </span>
                    @endif
                    @if($statusFilter || $platformFilter || $recruitmentStageFilter)
                        <span class="hidden sm:inline-flex ml-auto px-2 py-1 text-xs font-medium text-purple-600 bg-purple-50 rounded-lg">
                            {{ ($statusFilter ? 1 : 0) + ($platformFilter ? 1 : 0) + ($recruitmentStageFilter ? 1 : 0) }} active
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden sm:block bg-white rounded-lg shadow-sm border border-[#E9ECEF] overflow-x-auto">
        <div class="inline-block min-w-full">
        <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                            No
                        </th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider cursor-pointer hover:text-primary-600 transition-colors" 
                            wire:click="sortBy('company_name')">
                            <div class="flex items-center justify-center space-x-1">
                                <span>Company</span>
                                @if($sortField === 'company_name')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($sortDirection === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        @endif
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider cursor-pointer hover:text-primary-600 transition-colors" 
                            wire:click="sortBy('position')">
                            <div class="flex items-center justify-center space-x-1">
                                <span>Position</span>
                                @if($sortField === 'position')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($sortDirection === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        @endif
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider cursor-pointer hover:text-primary-600 transition-colors" 
                            wire:click="sortBy('location')">
                            <div class="flex items-center justify-center space-x-1">
                                <span>Location</span>
                                @if($sortField === 'location')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($sortDirection === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        @endif
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Platform
                        </th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                            App Status
                        </th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Stage
                        </th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Level
                        </th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider cursor-pointer hover:text-primary-600 transition-colors" 
                            wire:click="sortBy('application_date')">
                            <div class="flex items-center justify-center space-x-1">
                                <span>Applied</span>
                                @if($sortField === 'application_date')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($sortDirection === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        @endif
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider cursor-pointer hover:text-primary-600 transition-colors" 
                            wire:click="sortBy('interview_date')">
                            <div class="flex items-center justify-center space-x-1">
                                <span>Interview</span>
                                @if($sortField === 'interview_date')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($sortDirection === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        @endif
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($jobApplications as $index => $job)
                        <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100 transition-colors duration-200 group cursor-pointer {{ $job->is_pinned ? 'bg-purple-50/50' : '' }}" onclick="window.location.href='{{ route('jobs.show', $job) }}'">
                            <td class="px-4 py-2.5 whitespace-nowrap text-center text-sm font-medium text-gray-500">
                                {{ ($jobApplications->currentPage() - 1) * $jobApplications->perPage() + $index + 1 }}
                            </td>
                            <td class="px-4 py-2.5 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-2">
                                    @if($job->is_pinned)
                                        <svg class="w-4 h-4 text-purple-600 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M16 12V4h1c.55 0 1-.45 1-1s-.45-1-1-1H7c-.55 0-1 .45-1 1s.45 1 1 1h1v8c-1.66 0-3 1.34-3 3v1c0 .55.45 1 1 1h5v5c0 .55.45 1 1 1s1-.45 1-1v-5h5c.55 0 1-.45 1-1v-1c0-1.66-1.34-3-3-3z"/>
                                        </svg>
                                    @endif
                                    <div class="text-sm font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">
                                        {{ $job->company_name }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-2.5 whitespace-nowrap text-center">
                                <div class="text-sm font-medium text-gray-900">{{ $job->position }}</div>
                            </td>
                            <td class="px-4 py-2.5 whitespace-nowrap text-center text-sm text-gray-600">
                                {{ $job->location }}
                            </td>
                            <td class="px-4 py-2.5 whitespace-nowrap text-center text-sm text-gray-600">
                                {{ $job->platform }}
                            </td>
                            <td class="px-4 py-2.5 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold"
                                      style="background-color: {{ $this->getStatusColor($job->application_status) }}20; color: {{ $this->getStatusColor($job->application_status) }};">
                                    {{ $job->application_status ?? 'On Process' }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5 whitespace-nowrap text-center text-sm text-gray-600">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium"
                                      style="background-color: {{ $this->getStageColor($job->recruitment_stage ?? 'Applied') }}20; color: {{ $this->getStageColor($job->recruitment_stage ?? 'Applied') }};">
                                    {{ $job->recruitment_stage ?? 'Applied' }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5 whitespace-nowrap text-center text-sm text-gray-600">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium"
                                      style="background-color: {{ $this->getCareerLevelColor($job->career_level ?? 'Full Time') }}20; color: {{ $this->getCareerLevelColor($job->career_level ?? 'Full Time') }};">
                                    {{ $job->career_level ?? 'Full Time' }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5 whitespace-nowrap text-center text-xs text-gray-600">
                                {{ $job->application_date->setTimezone('Asia/Jakarta')->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-2.5 text-center text-xs text-gray-600">
                                @if($job->interview_date)
                                    {{ $job->interview_date->setTimezone('Asia/Jakarta')->format('M d, Y') }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-2.5 whitespace-nowrap text-center text-sm font-medium" onclick="event.stopPropagation();">
                                <div class="flex items-center justify-center gap-1">
                                    <!-- Pin Button -->
                                    <button wire:click="togglePin({{ $job->id }})" 
                                            class="p-2 rounded-lg transition-colors flex-shrink-0 @if($job->is_pinned) text-purple-600 bg-purple-100 hover:bg-purple-200 @else text-gray-400 hover:text-purple-600 hover:bg-purple-50 @endif"
                                            onclick="event.stopPropagation();"
                                            title="{{ $job->is_pinned ? 'Unpin' : 'Pin to Top' }}">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M16 12V4h1c.55 0 1-.45 1-1s-.45-1-1-1H7c-.55 0-1 .45-1 1s.45 1 1 1h1v8c-1.66 0-3 1.34-3 3v1c0 .55.45 1 1 1h5v5c0 .55.45 1 1 1s1-.45 1-1v-5h5c.55 0 1-.45 1-1v-1c0-1.66-1.34-3-3-3z"/>
                                        </svg>
                                    </button>
                                    
                                    <!-- Edit Button -->
                                    <button wire:click="edit({{ $job->id }})"
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors flex-shrink-0"
                                            onclick="event.stopPropagation(); event.preventDefault(); window.dispatchEvent(new CustomEvent('edit-job', { detail: { jobId: {{ $job->id }} } }));"
                                            title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    
                                    <!-- Delete Button -->
                                    <button type="button" 
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors flex-shrink-0"
                                            onclick="event.stopPropagation(); openDeleteModal({{ $job->id }}, '{{ addslashes($job->company_name) }}', '{{ addslashes($job->position) }}')"
                                            title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-6 py-24 text-center">
                                <div class="flex flex-col items-center justify-center min-h-[300px]">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No job applications found</h3>
                                    <p class="text-gray-500 mb-6">Try adjusting your search or add a new application</p>
                                    <button onclick="openJobModal()" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Add New Application
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </div>

        <!-- Mobile Table View (Compact) -->
        <div class="sm:hidden bg-white rounded-lg shadow-sm border border-[#E9ECEF] overflow-x-auto">
            <div class="inline-block min-w-full">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-2 py-2 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                Company
                            </th>
                            <th scope="col" class="px-2 py-2 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-2 py-2 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                Stage
                            </th>
                            <th scope="col" class="px-2 py-2 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($jobApplications as $index => $job)
                            <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100 transition-colors duration-200 group cursor-pointer {{ $job->is_pinned ? 'bg-purple-50/50' : '' }}" onclick="window.location.href='{{ route('jobs.show', $job) }}'">
                                <td class="px-2 py-2 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5">
                                        @if($job->is_pinned)
                                            <svg class="w-3 h-3 text-purple-600 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M16 12V4h1c.55 0 1-.45 1-1s-.45-1-1-1H7c-.55 0-1 .45-1 1s.45 1 1 1h1v8c-1.66 0-3 1.34-3 3v1c0 .55.45 1 1 1h5v5c0 .55.45 1 1 1s1-.45 1-1v-5h5c.55 0 1-.45 1-1v-1c0-1.66-1.34-3-3-3z"/>
                                            </svg>
                                        @endif
                                        <div class="min-w-0 flex-1">
                                            <div class="text-xs font-semibold text-gray-900 truncate group-hover:text-primary-600 transition-colors">
                                                {{ $job->company_name }}
                                            </div>
                                            <div class="text-xs text-gray-500 truncate">{{ $job->position }}</div>
                                            <div class="text-xs text-gray-400 truncate">{{ $job->location }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-2 py-2 whitespace-nowrap">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-bold"
                                          style="background-color: {{ $this->getStatusColor($job->application_status) }}20; color: {{ $this->getStatusColor($job->application_status) }};">
                                        {{ $job->application_status ?? 'On Process' }}
                                    </span>
                                </td>
                                <td class="px-2 py-2 whitespace-nowrap">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium"
                                          style="background-color: {{ $this->getStageColor($job->recruitment_stage ?? 'Applied') }}20; color: {{ $this->getStageColor($job->recruitment_stage ?? 'Applied') }};">
                                        {{ $job->recruitment_stage ?? 'Applied' }}
                                    </span>
                                </td>
                                <td class="px-2 py-2 whitespace-nowrap text-sm font-medium" onclick="event.stopPropagation();">
                                    <div class="flex items-center gap-0.5">
                                        <button wire:click="togglePin({{ $job->id }})" 
                                                class="p-1 rounded transition-colors flex-shrink-0 @if($job->is_pinned) text-purple-600 bg-purple-100 hover:bg-purple-200 @else text-gray-400 hover:text-purple-600 hover:bg-purple-50 @endif"
                                                onclick="event.stopPropagation();"
                                                title="{{ $job->is_pinned ? 'Unpin' : 'Pin' }}">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M16 12V4h1c.55 0 1-.45 1-1s-.45-1-1-1H7c-.55 0-1 .45-1 1s.45 1 1 1h1v8c-1.66 0-3 1.34-3 3v1c0 .55.45 1 1 1h5v5c0 .55.45 1 1 1s1-.45 1-1v-5h5c.55 0 1-.45 1-1v-1c0-1.66-1.34-3-3-3z"/>
                                            </svg>
                                        </button>
                                        <button wire:click="edit({{ $job->id }})"
                                                class="p-1 text-blue-600 hover:bg-blue-50 rounded transition-colors flex-shrink-0"
                                                onclick="event.stopPropagation(); event.preventDefault(); window.dispatchEvent(new CustomEvent('edit-job', { detail: { jobId: {{ $job->id }} } }));"
                                                title="Edit">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button type="button" 
                                                class="p-1 text-red-600 hover:bg-red-50 rounded transition-colors flex-shrink-0"
                                                onclick="event.stopPropagation(); openDeleteModal({{ $job->id }}, '{{ addslashes($job->company_name) }}', '{{ addslashes($job->position) }}')"
                                                title="Delete">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <h3 class="text-sm font-medium text-gray-900 mb-1">No job applications found</h3>
                                        <p class="text-xs text-gray-500 mb-4">Try adjusting your search</p>
                                        <button onclick="openJobModal()" class="inline-flex items-center px-3 py-1.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors text-xs">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Add New
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modern Pagination -->
        @if($jobApplications->hasPages())
            <div class="bg-gray-50/50 border-t border-gray-100">
                <div class="px-4 py-3 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <!-- Results Info -->
                    <div class="text-sm text-gray-600 whitespace-nowrap">
                        Showing {{ $jobApplications->firstItem() }} to {{ $jobApplications->lastItem() }} of {{ $jobApplications->total() }} results
                    </div>
                    
                    <!-- Pagination Links -->
                    <div class="flex-shrink-0">
                        {{ $jobApplications->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-[70] transition-all duration-300">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95" id="deleteModalContent">
                    <div class="p-6">
                        <!-- Icon -->
                        <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-red-100">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>

                        <!-- Title -->
                        <h3 class="text-xl font-bold text-gray-900 text-center mb-1">Konfirmasi Hapus</h3>
                        <p class="text-sm text-gray-500 text-center mb-4">Apakah Anda yakin ingin menghapus data ini?</p>

                        <!-- Message -->
                        <div class="text-center mb-6">
                            <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                                <p class="text-sm font-semibold text-gray-900" id="deleteJobCompany"></p>
                                <p class="text-xs text-gray-500" id="deleteJobPosition"></p>
                            </div>
                            <p class="text-red-600 text-sm mt-3">Aksi ini bersifat permanen dan tidak bisa dibatalkan.</p>
                        </div>

                        <!-- Buttons -->
                        <div class="flex space-x-3">
                            <button type="button" 
                                    onclick="closeDeleteModal()"
                                    class="flex-1 px-4 py-3 border border-gray-200 rounded-xl text-gray-700 font-medium hover:bg-gray-50 transition-colors duration-200">
                                Batal
                            </button>
                            <button type="button" 
                                    onclick="confirmDelete()"
                                    class="flex-1 px-4 py-3 rounded-xl font-semibold text-white bg-red-600 hover:bg-red-700 transition-all duration-200">
                                Ya, Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
let currentDeleteJobId = null;
let currentDeleteJobCompany = '';
let currentDeleteJobPosition = '';

// Ensure modal is attached to <body> so overlay covers sticky header
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('deleteModal');
    if (modal && modal.parentElement !== document.body) {
        document.body.appendChild(modal);
    }
});

function openDeleteModal(jobId, companyName, position = '') {
    currentDeleteJobId = jobId;
    currentDeleteJobCompany = companyName;
    currentDeleteJobPosition = position;
    
    // Update modal content
    document.getElementById('deleteJobCompany').textContent = companyName;
    document.getElementById('deleteJobPosition').textContent = position || 'Job Application';
    
    // Show modal
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('deleteModalContent');
    
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    
    // Animate modal in
    setTimeout(() => {
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');
    }, 10);
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('deleteModalContent');
    
    // Animate modal out
    modalContent.classList.remove('scale-100');
    modalContent.classList.add('scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        currentDeleteJobId = null;
        currentDeleteJobCompany = '';
        currentDeleteJobPosition = '';
    }, 300);
}

function confirmDelete() {
    if (currentDeleteJobId) {
        // Call Livewire delete method
        @this.call('delete', currentDeleteJobId);
        closeDeleteModal();
    }
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.id === 'deleteModal') {
        closeDeleteModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
        closeFilterModal();
    }
});

// Filter Modal Functions
function openFilterModal() {
    const modal = document.getElementById('filterModal');
    const modalContent = document.getElementById('filterModalContent');
    
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    
    setTimeout(() => {
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');
    }, 10);
}

function closeFilterModal() {
    const modal = document.getElementById('filterModal');
    const modalContent = document.getElementById('filterModalContent');
    
    modalContent.classList.remove('scale-100');
    modalContent.classList.add('scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }, 300);
}

function applyFilters() {
    const statusFilter = document.getElementById('modalStatusFilter').value;
    const platformFilter = document.getElementById('modalPlatformFilter').value;
    const recruitmentStageFilter = document.getElementById('modalRecruitmentStageFilter').value;
    const perPage = document.getElementById('modalPerPage').value;
    
    @this.set('statusFilter', statusFilter);
    @this.set('platformFilter', platformFilter);
    @this.set('recruitmentStageFilter', recruitmentStageFilter);
    @this.set('perPage', parseInt(perPage));
    
    closeFilterModal();
}

function clearFilterModal() {
    document.getElementById('modalStatusFilter').value = '';
    document.getElementById('modalPlatformFilter').value = '';
    document.getElementById('modalRecruitmentStageFilter').value = '';
    document.getElementById('modalPerPage').value = '20';
    
    @this.call('clearFilters');
    closeFilterModal();
}

// Close filter modal when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.id === 'filterModal') {
        closeFilterModal();
    }
});
</script>

        <!-- Mobile Filter Modal -->
        <div id="filterModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-[60] transition-all duration-300">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div id="filterModalContent" class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 max-h-[90vh] overflow-hidden flex flex-col">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-[#d983e4] to-[#4e71c5] p-4 sm:p-6 text-white flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            <h3 class="text-lg sm:text-xl font-bold">Filters</h3>
                        </div>
                        <button onclick="closeFilterModal()" class="p-2 hover:bg-white/10 rounded-xl transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Filter Content -->
                    <div class="p-4 sm:p-6 space-y-4 overflow-y-auto flex-1">
                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Status</label>
                            <select id="modalStatusFilter" 
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#d983e4]/50 focus:border-[#d983e4] text-sm bg-white">
                                <option value="">All Status</option>
                                @foreach($statusOptions as $status)
                                    <option value="{{ $status }}" {{ $statusFilter === $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Platform Filter -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Platform</label>
                            <select id="modalPlatformFilter" 
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#d983e4]/50 focus:border-[#d983e4] text-sm bg-white">
                                <option value="">All Platforms</option>
                                @foreach($platformOptions as $platform)
                                    <option value="{{ $platform }}" {{ $platformFilter === $platform ? 'selected' : '' }}>{{ $platform }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Recruitment Stage Filter -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Stage</label>
                            <select id="modalRecruitmentStageFilter" 
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#d983e4]/50 focus:border-[#d983e4] text-sm bg-white">
                                <option value="">All Stages</option>
                                @foreach($recruitmentStageOptions as $stage)
                                    <option value="{{ $stage }}" {{ $recruitmentStageFilter === $stage ? 'selected' : '' }}>{{ $stage }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Per Page -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Per Page</label>
                            <select id="modalPerPage" 
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#d983e4]/50 focus:border-[#d983e4] text-sm bg-white">
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Footer Actions -->
                    <div class="p-4 sm:p-6 border-t border-gray-200 bg-gray-50 space-y-2">
                        <button onclick="clearFilterModal()" 
                                class="w-full px-4 py-3 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                            Clear Filters
                        </button>
                        <button onclick="applyFilters()" 
                                class="w-full px-4 py-3 text-sm font-semibold text-white bg-gradient-to-r from-[#d983e4] to-[#4e71c5] rounded-xl hover:shadow-lg transition-all">
                            Apply Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>
</div>