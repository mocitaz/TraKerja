<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Work Experience</h3>
            <p class="text-sm text-gray-600">Add your professional work history</p>
        </div>
        <button wire:click="openModal" 
                class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Experience
        </button>
    </div>

    <!-- Experience List -->
    @if($experiences->count() > 0)
        <div class="space-y-4">
            @foreach($experiences as $experience)
                <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">{{ $experience->position }}</h4>
                            <p class="text-sm text-gray-700">{{ $experience->company_name }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $experience->employment_type }} • 
                                {{ $experience->start_date?->format('M Y') }} - 
                                {{ $experience->is_current ? 'Present' : $experience->end_date?->format('M Y') }}
                                @if($experience->location)
                                    • {{ $experience->location }}
                                @endif
                            </p>
                            @if($experience->description)
                                <p class="text-sm text-gray-600 mt-2">{{ Str::limit($experience->description, 150) }}</p>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-2 ml-4">
                            <button wire:click="moveUp({{ $experience->id }})" class="p-1 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>
                            <button wire:click="moveDown({{ $experience->id }})" class="p-1 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <button wire:click="edit({{ $experience->id }})" class="p-2 text-primary-600 hover:bg-primary-50 rounded">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button wire:click="delete({{ $experience->id }})" wire:confirm="Delete this experience?" class="p-2 text-red-600 hover:bg-red-50 rounded">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0h5a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V8a2 2 0 012-2h5"></path>
            </svg>
            <p class="mt-2 text-sm text-gray-600">No work experience added yet</p>
            <button wire:click="openModal" class="mt-3 text-sm text-primary-600 hover:text-primary-800 font-medium">
                Add your first experience
            </button>
        </div>
    @endif

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" 
             x-data 
             x-init="document.body.style.overflow = 'hidden'" 
             x-destroy="document.body.style.overflow = 'auto'">
            <!-- Backdrop with blur -->
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" wire:click="closeModal"></div>
            
            <!-- Modal Content -->
            <div class="relative bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col">
                <!-- Modal Header - Sticky -->
                <div class="border-b border-gray-200 px-6 py-4 bg-white rounded-t-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $editMode ? 'Edit Experience' : 'Add New Experience' }}</h3>
                            <p class="text-sm text-gray-500 mt-1">Fill in your work experience details</p>
                        </div>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body - Scrollable -->
                <form wire:submit.prevent="save" class="px-6 py-6 overflow-y-auto flex-1">
                    <div class="space-y-6">
                        <!-- Company Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Company Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   wire:model="company_name" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" 
                                   placeholder="e.g., Google, Microsoft">
                            @error('company_name') 
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Position -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Position / Job Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   wire:model="position" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" 
                                   placeholder="e.g., Senior Software Engineer">
                            @error('position') 
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Employment Type and Location -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Employment Type <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="employment_type" 
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                                    <option value="">Select Type</option>
                                    @foreach($employmentTypes as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('employment_type') 
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Location (Optional)</label>
                                <input type="text" 
                                       wire:model="location" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" 
                                       placeholder="e.g., Jakarta, Indonesia">
                            </div>
                        </div>

                        <!-- Start and End Date -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Start Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       wire:model="start_date" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                                @error('start_date') 
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                                <input type="date" 
                                       wire:model="end_date" 
                                       :disabled="$wire.is_current" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                            </div>
                        </div>

                        <!-- Currently Working -->
                        <div class="flex items-center bg-primary-50 border border-primary-200 rounded-lg p-4">
                            <input type="checkbox" 
                                   wire:model="is_current" 
                                   id="is_current_exp" 
                                   class="h-5 w-5 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                            <label for="is_current_exp" class="ml-3 block text-sm font-medium text-gray-900">
                                I currently work here
                            </label>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description (Optional)</label>
                            <textarea wire:model="description" 
                                      rows="4" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" 
                                      placeholder="Describe your responsibilities and achievements..."></textarea>
                            <p class="mt-1 text-xs text-gray-500">Highlight your key responsibilities, achievements, and impact</p>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-gray-200">
                        <button type="button" 
                                wire:click="closeModal" 
                                class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-6 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-medium shadow-sm">
                            <span wire:loading.remove wire:target="save">
                                {{ $editMode ? 'Update Experience' : 'Save Experience' }}
                            </span>
                            <span wire:loading wire:target="save" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Saving...
                            </span>
                        </button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    @endif


</div>
