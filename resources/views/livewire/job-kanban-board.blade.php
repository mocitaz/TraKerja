<div>
    <div class="space-y-4">
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
                                <div class="group bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200 border border-gray-200 cursor-move"
                                     draggable="true"
                                     data-job-id="{{ $job->id }}"
                                     ondragstart="dragStart(event, {{ $job->id }})"
                                     ondragend="dragEnd(event)">
                                    
                                    <!-- Job Card Content -->
                                    <div class="p-2 sm:p-3">
                                        <!-- Company & Position -->
                                        <div class="mb-2">
                                            <h4 class="font-semibold text-gray-900 text-xs sm:text-sm truncate group-hover:text-primary-600 transition-colors">
                                                {{ $job->company_name }}
                                            </h4>
                                            <p class="text-xs text-gray-600 mt-0.5 truncate">{{ $job->position }}</p>
                                        </div>

                                        <!-- Location & Platform -->
                                        <div class="space-y-1.5 mb-2">
                                            <div class="flex items-center text-xs text-gray-500">
                                                <svg class="w-3 h-3 mr-1.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                <span class="truncate">{{ $job->location }}</span>
                                            </div>
                                            <div class="flex items-center text-xs text-gray-500">
                                                <svg class="w-3 h-3 mr-1.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                                                </svg>
                                                <span class="truncate">{{ $job->platform }}</span>
                                            </div>
                                        </div>

                                        <!-- Recruitment Stage & Date -->
                                        <div class="flex items-center justify-between">
                                            <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                </svg>
                                                {{ $job->recruitment_stage ?? 'Applied' }}
                                            </div>
                                            <div class="flex items-center text-xs text-gray-500">
                                                <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span>{{ $job->application_date->format('M d') }}</span>
                                            </div>
                                        </div>

                                        <!-- Notes Preview -->
                                        @if($job->notes)
                                            <div class="mt-2 pt-2 border-t border-gray-100">
                                                <p class="text-xs text-gray-600 line-clamp-2">{{ Str::limit($job->notes, 50) }}</p>
                                            </div>
                                        @endif
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