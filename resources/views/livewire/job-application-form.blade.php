<div class="space-y-4">

    @if (session()->has('message'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-3">
            <div class="flex items-center">
                <svg class="h-4 w-4 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
            </div>
        </div>
    @endif

    <form wire:submit="save" class="space-y-4">
        <!-- Row 1: Company & Position -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Company Name -->
            <div>
                <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">
                    Company Name *
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <input wire:model.live="company_name" 
                           type="text" 
                           id="company_name" 
                           class="block w-full pl-9 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm"
                           placeholder="Enter company name"
                           value="{{ $company_name }}"
                           autocomplete="off">
                    
                    <!-- Company Suggestions Dropdown -->
                    @if($showCompanySuggestions && count($companySuggestions) > 0)
                        <div class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto">
                            @foreach($companySuggestions as $suggestion)
                                <div wire:click="selectCompanySuggestion('{{ $suggestion }}')" 
                                     class="px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm border-b border-gray-100 last:border-b-0">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        <span class="text-gray-700">{{ $suggestion }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                @error('company_name') 
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Position -->
            <div>
                <label for="position" class="block text-sm font-medium text-gray-700 mb-1">
                    Position *
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <input wire:model.live="position" 
                           type="text" 
                           id="position" 
                           class="block w-full pl-9 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm"
                           placeholder="Enter position title"
                           value="{{ $position }}"
                           autocomplete="off">
                    
                    <!-- Position Suggestions Dropdown -->
                    @if($showPositionSuggestions && count($positionSuggestions) > 0)
                        <div class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto">
                            @foreach($positionSuggestions as $suggestion)
                                <div wire:click="selectPositionSuggestion('{{ $suggestion }}')" 
                                     class="px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm border-b border-gray-100 last:border-b-0">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span class="text-gray-700">{{ $suggestion }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                @error('position') 
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Row 2: Location -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Location *
            </label>
            
            <!-- Regular Location Selection (hidden when Remote or Seluruh Indonesia is selected) -->
            @if(!$isRemote && !$isSeluruhIndonesia)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Province Dropdown -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <select wire:model="selectedProvince" 
                            class="block w-full pl-9 pr-8 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm appearance-none">
                        <option value="">Select Province</option>
                        @foreach($provinces as $province => $cities)
                            <option value="{{ $province }}">{{ $province }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>

                <!-- City Dropdown -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <select wire:model="selectedCity" 
                            class="block w-full pl-9 pr-8 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm appearance-none {{ empty($selectedProvince) ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ empty($selectedProvince) ? 'disabled' : '' }}>
                        <option value="">Select City</option>
                        @if(!empty($selectedProvince) && isset($provinces[$selectedProvince]))
                            @foreach($provinces[$selectedProvince] as $city)
                                <option value="{{ $city }}">{{ $city }}</option>
                            @endforeach
                        @endif
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Special Location Options (always visible) -->
            <div class="mt-3 flex flex-wrap gap-4">
                <label class="flex items-center">
                    <input type="checkbox" wire:model.live="isRemote" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                    <span class="ml-2 text-sm text-gray-700">Remote Work</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" wire:model.live="isSeluruhIndonesia" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                    <span class="ml-2 text-sm text-gray-700">Seluruh Indonesia</span>
                </label>
            </div>
            
            <input type="hidden" wire:model="location" value="{{ $location }}">
            @error('selectedProvince') 
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            @error('location') 
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Row 3: Platform -->
        <div class="grid grid-cols-1 gap-4">
            <!-- Platform -->
            <div>
                <label for="platform" class="block text-sm font-medium text-gray-700 mb-1">
                    Platform *
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                        </svg>
                    </div>
                    <select wire:model="platform" 
                            class="block w-full pl-9 pr-8 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm appearance-none">
                        @foreach($platformOptions as $option)
                            @if($option !== 'Other')
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endif
                        @endforeach
                        <option value="Other">Other</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                @error('platform') 
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Platform Other Input (conditional) -->
        @if($platform === 'Other')
        <div>
            <label for="platformOther" class="block text-sm font-medium text-gray-700 mb-1">
                Custom Platform *
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <input wire:model.live="platformOther" 
                       type="text" 
                       id="platformOther" 
                       class="block w-full pl-9 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm"
                       placeholder="Enter custom platform name"
                       value="{{ $platformOther }}">
            </div>
            @error('platformOther') 
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        @endif

        <!-- Row 4: Application Details -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Application Status -->
            <div>
                <label for="application_status" class="block text-sm font-medium text-gray-700 mb-1">
                    Application Status *
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <select wire:model="application_status" 
                            class="block w-full pl-9 pr-8 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm appearance-none">
                        @foreach($applicationStatusOptions as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                @error('application_status') 
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Recruitment Stage -->
            <div>
                <label for="recruitment_stage" class="block text-sm font-medium text-gray-700 mb-1">
                    Recruitment Stage *
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <select wire:model="recruitment_stage" 
                            class="block w-full pl-9 pr-8 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm appearance-none">
                        @foreach($recruitmentStageOptions as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                @error('recruitment_stage') 
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Career Level -->
            <div>
                <label for="career_level" class="block text-sm font-medium text-gray-700 mb-1">
                    Career Level *
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <select wire:model="career_level" 
                            class="block w-full pl-9 pr-8 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm appearance-none">
                        @foreach($careerLevelOptions as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                @error('career_level') 
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Row 5: Platform Link & Application Date -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Platform Link -->
            <div>
                <label for="platform_link" class="block text-sm font-medium text-gray-700 mb-1">
                    Platform Link
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                    </div>
                    <input wire:model.live="platform_link" 
                           type="url" 
                           id="platform_link" 
                           class="block w-full pl-9 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm"
                           placeholder="https://example.com/job-posting"
                           value="{{ $platform_link }}">
                </div>
                @error('platform_link') 
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Application Date -->
            <div>
                <label for="application_date" class="block text-sm font-medium text-gray-700 mb-1">
                    Application Date * <span class="text-xs text-gray-500">(WIB)</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <input wire:model.live="application_date" 
                           type="date" 
                           id="application_date" 
                           class="block w-full pl-9 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm"
                           value="{{ $application_date }}"
                           max="{{ now()->format('Y-m-d') }}">
                </div>
                @error('application_date') 
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>


        <!-- Interview Details Section (Only visible when recruitment_stage = HR or User Interview) -->
        @if(in_array($recruitment_stage, ['HR - Interview', 'User - Interview']))
        <div class="bg-purple-50 border-2 border-purple-200 rounded-lg p-4 space-y-4">
            <div class="flex items-center space-x-2 mb-2">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-sm font-semibold text-purple-900">Interview Details</h3>
                <span class="text-xs text-purple-600 italic">(will appear in Interview Calendar)</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Interview Date & Time -->
                <div>
                    <label for="interview_date" class="block text-sm font-medium text-gray-700 mb-1">
                        Interview Date & Time * <span class="text-xs text-gray-500">(WIB)</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <input wire:model.live="interview_date" 
                               type="datetime-local" 
                               id="interview_date" 
                               class="block w-full pl-9 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm"
                               value="{{ $interview_date }}">
                    </div>
                    @error('interview_date') 
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Interview Type -->
                <div>
                    <label for="interview_type" class="block text-sm font-medium text-gray-700 mb-1">
                        Interview Type *
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <select wire:model="interview_type" 
                                class="block w-full pl-9 pr-8 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm appearance-none">
                            <option value="">Select Type</option>
                            <option value="Phone">📞 Phone</option>
                            <option value="Video">📹 Video</option>
                            <option value="In-person">🏢 In-person</option>
                            <option value="Panel">👥 Panel</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                    @error('interview_type') 
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Interview Location -->
            <div>
                <label for="interview_location" class="block text-sm font-medium text-gray-700 mb-1">
                    Interview Location / Link
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <input wire:model.live="interview_location" 
                           type="text" 
                           id="interview_location" 
                           class="block w-full pl-9 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm"
                           placeholder="e.g., Zoom link, office address, phone number"
                           value="{{ $interview_location }}">
                </div>
                @error('interview_location') 
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Interview Notes -->
            <div>
                <label for="interview_notes" class="block text-sm font-medium text-gray-700 mb-1">
                    Interview Notes
                </label>
                <div class="relative">
                    <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <textarea wire:model.live="interview_notes" 
                              id="interview_notes" 
                              rows="2"
                              class="block w-full pl-9 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm resize-none"
                              placeholder="Preparation checklist, topics to discuss, dress code, etc...">{{ $interview_notes }}</textarea>
                </div>
                @error('interview_notes') 
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        @endif

        <!-- Row 6: Notes -->
        <div>
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                Notes
            </label>
            <div class="relative">
                <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <textarea wire:model.live="notes" 
                          id="notes" 
                          rows="3"
                          class="block w-full pl-9 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm resize-none"
                          placeholder="Add any additional notes about this application...">{{ $notes }}</textarea>
            </div>
            @error('notes') 
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end space-x-3 pt-4">
            <button type="button" 
                    wire:click="resetForm" 
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors duration-200">
                Reset
            </button>
            <button type="submit" 
                    class="px-6 py-2 text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 rounded-md transition-colors duration-200">
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ $isEditing ? 'Update Application' : 'Add Application' }}</span>
                </div>
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(event) {
        const companyInput = document.getElementById('company_name');
        const positionInput = document.getElementById('position');
        
        if (!companyInput.contains(event.target)) {
            @this.call('hideSuggestions');
        }
        
        if (!positionInput.contains(event.target)) {
            @this.call('hideSuggestions');
        }
    });
    
    // Handle escape key to hide suggestions
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            @this.call('hideSuggestions');
        }
    });
});
</script>

