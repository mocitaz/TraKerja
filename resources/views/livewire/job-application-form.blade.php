<div class="space-y-5 sm:space-y-6">

    @if (session()->has('message'))
        <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-3 sm:p-4 shadow-sm">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-emerald-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm sm:text-base font-medium text-green-800">{{ session('message') }}</p>
            </div>
        </div>
    @endif

    <form wire:submit="save" class="space-y-5 sm:space-y-6">
        <!-- Section: Basic Information -->
        <div class="border-b border-gray-200 pb-4 mb-5">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#d983e4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                Basic Information
            </h3>
        </div>

        <!-- Row 1: Company & Position -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
            <!-- Company Name -->
            <div>
                <label for="company_name" class="block text-sm font-semibold text-gray-900 mb-2">
                    Company Name <span class="text-red-500">*</span>
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
                           class="block w-full pl-9 pr-3 py-2.5 sm:py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#d983e4]/50 focus:border-[#d983e4] text-sm sm:text-base touch-manipulation transition-all bg-white hover:border-gray-400"
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
                <label for="position" class="block text-sm font-semibold text-gray-900 mb-2">
                    Position <span class="text-red-500">*</span>
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
                           class="block w-full pl-9 pr-3 py-2.5 sm:py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#d983e4]/50 focus:border-[#d983e4] text-sm sm:text-base touch-manipulation transition-all bg-white hover:border-gray-400"
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
        <div class="pt-2">
            <label class="block text-sm font-semibold text-gray-900 mb-2">
                Location <span class="text-red-500">*</span>
            </label>
            
            <!-- Regular Location Selection (hidden when Remote, Seluruh Indonesia, or International is selected) -->
            @if(!$isRemote && !$isSeluruhIndonesia && !$isInternational)
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
                            class="block w-full pl-9 pr-8 py-2.5 sm:py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#d983e4]/50 focus:border-[#d983e4] text-sm sm:text-base touch-manipulation appearance-none transition-all bg-white hover:border-gray-400">
                        <option value="" disabled>Select Province</option>
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
                            class="block w-full pl-9 pr-8 py-2.5 sm:py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#d983e4]/50 focus:border-[#d983e4] text-sm sm:text-base touch-manipulation appearance-none transition-all bg-white hover:border-gray-400 {{ empty($selectedProvince) ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ empty($selectedProvince) ? 'disabled' : '' }}>
                        <option value="" disabled>Select City</option>
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
            
            <!-- Special Location Options (disabled when province and city selected) -->
            <div class="mt-3 flex flex-wrap gap-4">
                <label class="flex items-center {{ ($selectedProvince && $selectedCity) ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <input type="checkbox" 
                           wire:model.live="isRemote" 
                           {{ ($selectedProvince && $selectedCity) ? 'disabled' : '' }}
                           class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 {{ ($selectedProvince && $selectedCity) ? 'cursor-not-allowed' : '' }}">
                    <span class="ml-2 text-sm text-gray-700 {{ ($selectedProvince && $selectedCity) ? 'text-gray-400' : '' }}">Remote Work</span>
                </label>
                <label class="flex items-center {{ ($selectedProvince && $selectedCity) ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <input type="checkbox" 
                           wire:model.live="isSeluruhIndonesia" 
                           {{ ($selectedProvince && $selectedCity) ? 'disabled' : '' }}
                           class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 {{ ($selectedProvince && $selectedCity) ? 'cursor-not-allowed' : '' }}">
                    <span class="ml-2 text-sm text-gray-700 {{ ($selectedProvince && $selectedCity) ? 'text-gray-400' : '' }}">Seluruh Indonesia</span>
                </label>
                <label class="flex items-center {{ ($selectedProvince && $selectedCity) ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <input type="checkbox" 
                           wire:model.live="isInternational" 
                           {{ ($selectedProvince && $selectedCity) ? 'disabled' : '' }}
                           class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 {{ ($selectedProvince && $selectedCity) ? 'cursor-not-allowed' : '' }}">
                    <span class="ml-2 text-sm text-gray-700 {{ ($selectedProvince && $selectedCity) ? 'text-gray-400' : '' }}">International</span>
                </label>
            </div>
            
            @if($selectedProvince && $selectedCity)
                <div class="mt-2 text-xs text-gray-500 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Remote Work, Seluruh Indonesia, and International are not available because a specific location has been selected
                </div>
            @endif
            
            <!-- International Location Selection -->
            @if($isInternational)
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Country Selection -->
                    <div>
                        <label for="selectedCountry" class="block text-sm font-medium text-gray-700 mb-1">
                            Country <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <select wire:model.live="selectedCountry" 
                                    class="block w-full pl-9 pr-8 py-3 sm:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm sm:text-base touch-manipulation appearance-none">
                                <option value="" disabled>Select Country</option>
                                @foreach($countries as $country => $cities)
                                    <option value="{{ $country }}">{{ $country }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        @error('selectedCountry') 
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- City Selection -->
                    <div>
                        <label for="selectedInternationalCity" class="block text-sm font-medium text-gray-700 mb-1">
                            City <span class="text-gray-500 text-sm font-normal">(Optional)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <select wire:model.live="selectedInternationalCity" 
                                    class="block w-full pl-9 pr-8 py-3 sm:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm sm:text-base touch-manipulation appearance-none"
                                    {{ empty($selectedCountry) ? 'disabled' : '' }}>
                                <option value="" disabled>Select City</option>
                                @if(!empty($selectedCountry) && isset($countries[$selectedCountry]))
                                    @foreach($countries[$selectedCountry] as $city)
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
                        @error('selectedInternationalCity') 
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            @endif
            
            <input type="hidden" wire:model="location" value="{{ $location }}">
            @error('selectedProvince') 
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            @error('location') 
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Section: Application Details -->
        <div class="border-t border-gray-200 pt-5 mt-5">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#4e71c5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Application Details
            </h3>
        </div>

        <!-- Row 3: Platform -->
        <div class="grid grid-cols-1 gap-4 sm:gap-5">
            <!-- Platform -->
            <div>
                <label for="platform" class="block text-sm font-semibold text-gray-900 mb-2">
                    Platform <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                        </svg>
                    </div>
                    <select wire:model="platform" 
                            class="block w-full pl-9 pr-8 py-3 sm:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm sm:text-base touch-manipulation appearance-none">
                        <option value="" disabled>Select Platform</option>
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
                
                <!-- Most Used Platforms Quick Select -->
                @if(count($this->mostUsedPlatforms) > 0)
                    <div class="mt-3">
                        <p class="text-xs text-gray-500 mb-2 font-medium">Most used platforms:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($this->mostUsedPlatforms as $usedPlatform)
                                <button type="button" 
                                        wire:click="selectPlatformSuggestion('{{ $usedPlatform }}')"
                                        class="px-3 py-1.5 text-xs bg-gray-100 hover:bg-primary-100 hover:text-primary-700 text-gray-700 rounded-lg transition-colors duration-200 border border-gray-200 hover:border-primary-300">
                                    {{ $usedPlatform }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Platform Other Input (conditional) -->
        @if($platform === 'Other')
        <div>
            <label for="platformOther" class="block text-sm font-medium text-gray-700 mb-1">
                Custom Platform <span class="text-red-500">*</span>
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
                       class="block w-full pl-9 pr-3 py-3 sm:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm sm:text-base touch-manipulation"
                       placeholder="Enter custom platform name"
                       value="{{ $platformOther }}">
            </div>
            @error('platformOther') 
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        @endif

        <!-- Row 4: Application Details -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
            <!-- Application Status -->
            <div>
                <label for="application_status" class="block text-sm font-semibold text-gray-900 mb-2">
                    Application Status <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <select wire:model="application_status" 
                            class="block w-full pl-9 pr-8 py-3 sm:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm sm:text-base touch-manipulation appearance-none">
                        <option value="" disabled>Select Application Status</option>
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
                <label for="recruitment_stage" class="block text-sm font-semibold text-gray-900 mb-2">
                    Recruitment Stage <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <select wire:model="recruitment_stage" 
                            class="block w-full pl-9 pr-8 py-3 sm:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm sm:text-base touch-manipulation appearance-none">
                        <option value="" disabled>Select Recruitment Stage</option>
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
                <label for="career_level" class="block text-sm font-semibold text-gray-900 mb-2">
                    Career Level <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <select wire:model="career_level" 
                            class="block w-full pl-9 pr-8 py-3 sm:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm sm:text-base touch-manipulation appearance-none">
                        <option value="" disabled>Select Career Level</option>
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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
            <!-- Platform Link -->
            <div>
                <label for="platform_link" class="block text-sm font-semibold text-gray-900 mb-2">
                    Platform Link <span class="text-gray-500 text-xs font-normal">(Optional)</span>
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
                           class="block w-full pl-9 pr-3 py-3 sm:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm sm:text-base touch-manipulation"
                           placeholder="https://example.com/job-posting"
                           value="{{ $platform_link }}">
                </div>
                @error('platform_link') 
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Application Date -->
            <div>
                <label for="application_date" class="block text-sm font-semibold text-gray-900 mb-2">
                    Application Date <span class="text-red-500">*</span>
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
                           class="block w-full pl-9 pr-3 py-3 sm:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm sm:text-base touch-manipulation"
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
        <div class="border-t border-gray-200 pt-5 mt-5">
            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 border-l-4 border-[#d983e4] rounded-lg p-4 sm:p-5 space-y-4 sm:space-y-5">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-5 h-5 text-[#d983e4] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">Interview Details</h3>
                    <span class="text-xs text-gray-600 italic hidden sm:inline">(will appear in Interview Calendar)</span>
                </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                <!-- Interview Date & Time -->
                <div>
                    <label for="interview_date" class="block text-sm font-semibold text-gray-900 mb-2">
                        Interview Date & Time <span class="text-red-500">*</span> <span class="text-xs text-gray-500 font-normal">(WIB)</span>
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
                               class="block w-full pl-9 pr-3 py-3 sm:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm sm:text-base touch-manipulation"
                               value="{{ $interview_date }}">
                    </div>
                    @error('interview_date') 
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Interview Type -->
                <div>
                    <label for="interview_type" class="block text-sm font-semibold text-gray-900 mb-2">
                        Interview Type <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <select wire:model="interview_type" 
                                class="block w-full pl-9 pr-8 py-3 sm:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm sm:text-base touch-manipulation appearance-none">
                            <option value="">Select Type</option>
                            <option value="Phone">Phone</option>
                            <option value="Video">Video</option>
                            <option value="In-person">In-person</option>
                            <option value="Panel">Panel</option>
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
                <label for="interview_location" class="block text-sm font-semibold text-gray-900 mb-2">
                    Interview Location / Link <span class="text-gray-500 text-xs font-normal">(Optional)</span>
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
                           class="block w-full pl-9 pr-3 py-3 sm:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm sm:text-base touch-manipulation"
                           placeholder="e.g., Zoom link, office address, phone number"
                           value="{{ $interview_location }}">
                </div>
                @error('interview_location') 
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Interview Notes -->
            <div>
                <label for="interview_notes" class="block text-sm font-semibold text-gray-900 mb-2">
                    Interview Notes <span class="text-gray-500 text-xs font-normal">(Optional)</span>
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
                              class="block w-full pl-9 pr-3 py-3 sm:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm sm:text-base touch-manipulation resize-none"
                              placeholder="Preparation checklist, topics to discuss, dress code, etc...">{{ $interview_notes }}</textarea>
                </div>
                @error('interview_notes') 
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        @endif

        <!-- Row 6: Notes -->
        <div class="border-t border-gray-200 pt-5 mt-5">
            <label for="notes" class="block text-sm font-semibold text-gray-900 mb-2">
                Additional Notes <span class="text-gray-500 text-xs font-normal">(Optional)</span>
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
                          class="block w-full pl-9 pr-3 py-3 sm:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm sm:text-base touch-manipulation resize-none"
                          placeholder="Add any additional notes about this application...">{{ $notes }}</textarea>
            </div>
            @error('notes') 
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-3 pt-6 border-t border-gray-200 mt-6">
            @if(!$isEditing)
                <button type="button" 
                        wire:click="resetForm" 
                        class="w-full sm:w-auto px-6 py-3 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all duration-200 touch-manipulation shadow-sm hover:shadow">
                    Reset
                </button>
            @endif
            <button type="submit" 
                    class="w-full sm:w-auto px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-[#d983e4] to-[#4e71c5] hover:from-[#c973d4] hover:to-[#3d5fa3] rounded-xl transition-all duration-200 touch-manipulation shadow-lg hover:shadow-xl transform hover:scale-[1.02]">
                <div class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

