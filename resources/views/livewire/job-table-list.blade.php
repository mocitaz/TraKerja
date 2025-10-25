<div>
    <div class="space-y-6">
        <!-- Compact Search and Filter Bar -->
        <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-4">
            <div class="space-y-3">
                <!-- First Row: Search and Items Per Page -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <!-- Search Input -->
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input wire:model.live="search" 
                                   type="text" 
                                   placeholder="Search by company, position, location..." 
                                   class="block w-full pl-9 pr-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm">
                        </div>
                    </div>

                    <!-- Items Per Page -->
                    <div class="sm:w-32">
                        <select wire:model.live="perPage" 
                                class="block w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm">
                            <option value="10">10 per page</option>
                            <option value="20">20 per page</option>
                            <option value="50">50 per page</option>
                            <option value="100">100 per page</option>
                        </select>
                    </div>
                </div>

                <!-- Second Row: Additional Filters -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <!-- Platform Filter -->
                    <div class="sm:w-48">
                        <select wire:model.live="platformFilter" 
                                class="block w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm">
                            <option value="">All Platforms</option>
                            @foreach($platformOptions as $platform)
                                <option value="{{ $platform }}">{{ $platform }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Career Level Filter -->
                    <div class="sm:w-48">
                        <select wire:model.live="careerLevelFilter" 
                                class="block w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm">
                            <option value="">All Career Levels</option>
                            @foreach($careerLevelOptions as $level)
                                <option value="{{ $level }}">{{ $level }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Recruitment Stage Filter -->
                    <div class="sm:w-48">
                        <select wire:model.live="recruitmentStageFilter" 
                                class="block w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm">
                            <option value="">All Stages</option>
                            @foreach($recruitmentStageOptions as $stage)
                                <option value="{{ $stage }}">{{ $stage }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="sm:w-48">
                        <select wire:model.live="statusFilter" 
                                class="block w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm">
                            <option value="">All App Status</option>
                            @foreach($statusOptions as $status)
                                <option value="{{ $status }}">{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Clear Filters Button -->
                    <div class="sm:w-32">
                        <button wire:click="clearFilters" 
                                class="w-full px-3 py-2 text-sm text-gray-600 hover:text-gray-800 hover:bg-gray-50 border border-gray-200 rounded-lg transition-colors">
                            Clear Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>

    <!-- Compact Table -->
    <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] overflow-hidden">
        <div class="overflow-x-auto">
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
                        <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100 transition-colors duration-200 group cursor-pointer" onclick="window.location.href='{{ route('jobs.show', $job) }}'">
                            <td class="px-4 py-3 whitespace-nowrap text-center text-sm font-medium text-gray-500">
                                {{ ($jobApplications->currentPage() - 1) * $jobApplications->perPage() + $index + 1 }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <div>
                                    <div class="text-sm font-bold text-gray-900 group-hover:text-primary-600 transition-colors">
                                        {{ $job->company_name }}
                                    </div>
                                    @if($job->platform_link)
                                        <a href="{{ $job->platform_link }}" target="_blank" class="text-xs text-primary-600 hover:text-primary-800 flex items-center justify-center mt-1">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                            View Job
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <div class="text-sm font-medium text-gray-900">{{ $job->position }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $job->location }}
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                                    </svg>
                                    {{ $job->platform }}
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold"
                                      style="background-color: {{ $this->getStatusColor($job->application_status) }}20; color: {{ $this->getStatusColor($job->application_status) }};">
                                    {{ $job->application_status ?? 'On Process' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center text-sm text-gray-600">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                    {{ $job->recruitment_stage ?? 'Applied' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center text-sm text-gray-600">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $job->career_level ?? 'Full Time' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center text-sm text-gray-600">
                                <div class="flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $job->application_date->setTimezone('Asia/Jakarta')->format('M d, Y') }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center text-sm">
                                @if($job->interview_date)
                                    <div class="flex flex-col items-center space-y-3">
                                        <!-- Date -->
                                        <div class="flex items-center text-purple-600 font-semibold text-sm">
                                            <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ $job->interview_date->setTimezone('Asia/Jakarta')->format('M d, Y') }}
                                        </div>
                                        
                                        <!-- Interview Type -->
                                        @if($job->interview_type)
                                            <div class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-purple-50 text-purple-700 border border-purple-200">
                                                @if($job->interview_type === 'Phone')
                                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                    </svg>
                                                @elseif($job->interview_type === 'Video')
                                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                    </svg>
                                                @elseif($job->interview_type === 'In-person')
                                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                    </svg>
                                                @elseif($job->interview_type === 'Panel')
                                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>
                                                @endif
                                                {{ $job->interview_type }}
                                            </div>
                                        @endif
                                        
                                        <!-- Location -->
                                        @if($job->interview_location)
                                            <div class="flex items-center text-xs text-purple-600 bg-purple-50 px-3 py-1.5 rounded-lg border border-purple-200 max-w-[150px]">
                                                <svg class="w-3 h-3 mr-1.5 text-purple-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                <span class="truncate">{{ Str::limit($job->interview_location, 20) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="flex flex-col items-center space-y-2">
                                        <span class="text-gray-400 text-xs font-medium">No interview</span>
                                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center text-sm font-medium" onclick="event.stopPropagation();">
                                <div class="flex items-center justify-center space-x-2">
                                    <button wire:click="edit({{ $job->id }})" 
                                            class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-200 group"
                                            onclick="event.stopPropagation(); event.preventDefault(); console.log('Edit button clicked for job:', {{ $job->id }}); window.dispatchEvent(new CustomEvent('edit-job', { detail: { jobId: {{ $job->id }} } }));">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button type="button" 
                                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200 group"
                                            onclick="event.stopPropagation(); openDeleteModal({{ $job->id }}, '{{ addslashes($job->company_name) }}', '{{ addslashes($job->position) }}')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-24 text-center">
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

        <!-- Modern Pagination -->
        @if($jobApplications->hasPages())
            <div class="bg-gray-50/50 px-4 py-3 border-t border-gray-100">
                {{ $jobApplications->links() }}
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
    }
});
</script>