<div>
    <div class="space-y-3">
        <!-- Compact Search and Filter Bar for Kanban -->
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

                <!-- Filters Row - Compact 2 columns -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 mb-2.5">
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
                </div>

                <!-- Action Buttons - Compact -->
                <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-gray-100">
                    <button wire:click="clearFilters" 
                            class="px-3 py-1.5 text-xs font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 border border-gray-200 rounded-lg transition-colors whitespace-nowrap">
                        Clear Filters
                    </button>
                    <button wire:click="toggleArchived" 
                            class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg transition-colors whitespace-nowrap {{ $showArchived ? 'bg-purple-600 text-white hover:bg-purple-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-200' }}">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                        <span>{{ $showArchived ? 'Show Active' : 'Show Archived' }}</span>
                    </button>
                    @if($showArchived && $archivedCount > 0)
                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-gray-700 bg-gray-50 rounded-lg border border-gray-200 whitespace-nowrap">
                            <span class="font-bold text-purple-600">{{ $archivedCount }}</span>
                            <span>Archived</span>
                        </span>
                    @endif
                    @if($platformFilter || $recruitmentStageFilter)
                        <span class="px-2 py-1 text-xs font-medium text-purple-600 bg-purple-50 rounded-lg">
                            {{ ($platformFilter ? 1 : 0) + ($recruitmentStageFilter ? 1 : 0) }} active
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Modern Kanban Board -->
        <div class="overflow-x-auto -mx-4 px-4 sm:mx-0 sm:px-0">
            <div class="flex sm:grid sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 snap-x snap-mandatory">
            @forelse($statuses as $status)
                <div class="w-full min-w-[260px] snap-start sm:min-w-0">
                    <!-- Status Column Header -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 h-full flex flex-col">
                        <div class="p-3 sm:p-4 border-b border-gray-100" style="background: linear-gradient(135deg, {{ $status->color_code }}08, {{ $status->color_code }}03);">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2 min-w-0 flex-1">
                                    <div class="w-2 h-2 rounded-full flex-shrink-0" style="background-color: {{ $status->color_code }};"></div>
                                    <h3 class="text-xs sm:text-sm font-bold text-gray-800 truncate">
                                        {{ $status->name }}
                                    </h3>
                                </div>
                                <span class="inline-flex items-center px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700 flex-shrink-0 ml-2">
                                    {{ $status->jobApplications->count() }}
                                </span>
                            </div>
                        </div>

                        <!-- Job Cards Container -->
                        <div class="p-2 sm:p-3 space-y-2 sm:space-y-3 flex-1 min-h-40 sm:min-h-48" 
                             data-status="{{ $status->name }}" 
                             ondrop="drop(event, '{{ $status->name }}')" 
                             ondragover="allowDrop(event)"
                             ondragleave="dragLeave(event)"
                             style="background: linear-gradient(135deg, {{ $status->color_code }}03, transparent);">
                            
                            @forelse($status->jobApplications as $job)
                                <div class="group bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200 border border-gray-200 {{ $job->is_pinned ? 'ring-2 ring-purple-400 border-purple-400' : '' }}"
                                     draggable="true"
                                     data-job-id="{{ $job->id }}"
                                     ondragstart="dragStart(event, {{ $job->id }})"
                                     ondragend="dragEnd(event)">
                                    
                                    <!-- Job Card Content -->
                                    <div class="p-2 sm:p-3">
                                        <!-- Header with Company, Position & Dropdown -->
                                        <div class="flex items-start justify-between mb-2 gap-2">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-1.5 mb-0.5">
                                                    @if($job->is_pinned)
                                                        <svg class="w-3.5 h-3.5 text-purple-600 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M16 12V4h1c.55 0 1-.45 1-1s-.45-1-1-1H7c-.55 0-1 .45-1 1s.45 1 1 1h1v8c-1.66 0-3 1.34-3 3v1c0 .55.45 1 1 1h5v5c0 .55.45 1 1 1s1-.45 1-1v-5h5c.55 0 1-.45 1-1v-1c0-1.66-1.34-3-3-3z"/>
                                                        </svg>
                                                    @endif
                                                    <h4 class="font-semibold text-gray-900 text-xs sm:text-sm truncate group-hover:text-primary-600 transition-colors cursor-pointer">
                                                        {{ $job->company_name }}
                                                    </h4>
                                                </div>
                                                <p class="text-xs text-gray-600 truncate">{{ $job->position }}</p>
                                            </div>
                                            
                                            <!-- Action Buttons -->
                                            <div class="inline-flex items-center gap-0.5 flex-shrink-0">
                                                <!-- Pin Button -->
                                                <button wire:click="togglePin({{ $job->id }})" 
                                                        class="p-1 rounded transition-colors @if($job->is_pinned) text-purple-600 bg-purple-50 hover:bg-purple-100 @else text-gray-400 hover:text-purple-600 hover:bg-purple-50 @endif"
                                                        onclick="event.stopPropagation();"
                                                        title="{{ $job->is_pinned ? 'Unpin' : 'Pin' }}">
                                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M16 12V4h1c.55 0 1-.45 1-1s-.45-1-1-1H7c-.55 0-1 .45-1 1s.45 1 1 1h1v8c-1.66 0-3 1.34-3 3v1c0 .55.45 1 1 1h5v5c0 .55.45 1 1 1s1-.45 1-1v-5h5c.55 0 1-.45 1-1v-1c0-1.66-1.34-3-3-3z"/>
                                                    </svg>
                                                </button>
                                                
                                                <!-- View Button -->
                                                <a href="{{ route('jobs.show', $job) }}"
                                                   class="p-1 text-blue-600 hover:bg-blue-50 rounded transition-colors"
                                                   title="View">
                                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>

                                        <!-- Location & Platform - Compact -->
                                        <div class="space-y-1 mb-2 text-xs text-gray-500">
                                            <div class="truncate">{{ $job->location }}</div>
                                            <div class="truncate">{{ $job->platform }}</div>
                                        </div>

                                        <!-- Recruitment Stage & Date -->
                                        <div class="flex items-center justify-between">
                                            <div class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium"
                                                 style="background-color: {{ $this->getStageColor($job->recruitment_stage ?? 'Applied') }}20; color: {{ $this->getStageColor($job->recruitment_stage ?? 'Applied') }};">
                                                {{ $job->recruitment_stage ?? 'Applied' }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $job->application_date->format('M d') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <div class="w-8 h-8 mx-auto mb-3 rounded-full bg-gray-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-xs text-gray-500 mb-3">No applications yet</p>
                                    <button onclick="openJobModal()" 
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-primary-600 bg-primary-50 hover:bg-primary-100 rounded-lg transition-colors duration-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Add First
                                    </button>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Job Applications Yet</h3>
                    <p class="text-gray-500 mb-4">Start tracking your job applications by adding your first one.</p>
                    <button onclick="openJobModal()" 
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Your First Application
                    </button>
                </div>
            @endforelse
            </div>
        </div>

    </div>
</div>
</div>