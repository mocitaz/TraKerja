<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Education</h3>
            <p class="text-sm text-gray-600">Add your educational background</p>
        </div>
        <button wire:click="openModal" 
                class="w-full sm:w-auto px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Education
        </button>
    </div>

    <!-- Education List -->
    @if($educations->count() > 0)
        <div class="space-y-4">
            @foreach($educations as $education)
                <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">{{ $education->degree }}</h4>
                            <p class="text-sm text-gray-700">{{ $education->institution_name }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $education->major }} • 
                                {{ $education->start_date?->format('Y') }} - 
                                {{ $education->is_current ? 'Present' : $education->end_date?->format('Y') }}
                                @if($education->gpa)
                                    • GPA: {{ $education->gpa }}
                                @endif
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-2 ml-4">
                            <button wire:click="moveUp({{ $education->id }})" class="p-1 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>
                            <button wire:click="moveDown({{ $education->id }})" class="p-1 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <button wire:click="edit({{ $education->id }})" class="p-2 text-primary-600 hover:bg-primary-50 rounded">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button wire:click="delete({{ $education->id }})" wire:confirm="Delete this education?" class="p-2 text-red-600 hover:bg-red-50 rounded">
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
            </svg>
            <p class="mt-2 text-sm text-gray-600">No education added yet</p>
            <button wire:click="openModal" class="mt-3 text-sm text-primary-600 hover:text-primary-800 font-medium">
                Add your first education
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
                        <!-- Backdrop -->
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity" 
                 wire:click="closeModal" 
                 @click="document.body.style.overflow = 'auto'"></div>
            
            <!-- Modal Content -->
            <div class="relative bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col">
                <!-- Modal Header - Sticky -->
                <div class="border-b border-gray-200 px-6 py-4 bg-white rounded-t-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $editMode ? 'Edit Education' : 'Add New Education' }}</h3>
                            <p class="text-sm text-gray-500 mt-1">Fill in your educational information</p>
                        </div>
                        <button type="button"
                                @click="closing = true; document.body.style.overflow = 'auto'; $wire.closeModal()" 
                                class="text-gray-400 hover:text-gray-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body - Scrollable -->
                <form wire:submit.prevent="save" class="px-6 py-6 overflow-y-auto flex-1">
                    <div class="space-y-6">
                        <!-- Institution Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Institution Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   wire:model="institution_name" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" 
                                   placeholder="e.g., University of Indonesia">
                            @error('institution_name') 
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Degree and Major -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Degree <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="degree" 
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                                    <option value="">Select Degree</option>
                                    @foreach($degreeTypes as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('degree') 
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Field of Study / Major <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       wire:model="major" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" 
                                       placeholder="e.g., Computer Science">
                                @error('major') 
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- GPA and Location -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">GPA (Optional)</label>
                                <input type="number" 
                                       step="0.01" 
                                       min="0" 
                                       max="4" 
                                       wire:model="gpa" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" 
                                       placeholder="e.g., 3.85">
                                @error('gpa') 
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

                        <!-- Currently Studying -->
                        <div class="flex items-center bg-primary-50 border border-primary-200 rounded-lg p-4">
                            <input type="checkbox" 
                                   wire:model="is_current" 
                                   id="is_current" 
                                   class="h-5 w-5 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                            <label for="is_current" class="ml-3 block text-sm font-medium text-gray-900">
                                I currently study here
                            </label>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description (Optional)</label>
                            <div class="relative">
                                <textarea wire:model="description" 
                                          rows="5" 
                                          class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm font-mono"
                                          style="line-height: 1.6; padding-left: 2.5rem; background-image: repeating-linear-gradient(transparent, transparent 1.6rem, #f3f4f6 1.6rem, #f3f4f6 calc(1.6rem + 1px)); background-size: 100% 1.6rem; background-attachment: local;"
                                          placeholder="Participants of POINTS (Path of Informatics Research) 2021&#10;Participants of GOER Faculty of Science and Mathematics 2021&#10;Active member of Computer Science Student Association"></textarea>
                                <div class="absolute left-3 top-2 bottom-2 flex flex-col justify-start pointer-events-none" style="line-height: 1.6;">
                                    <div class="text-gray-400 text-sm" style="height: 1.6rem; line-height: 1.6rem;">•</div>
                                    <div class="text-gray-400 text-sm" style="height: 1.6rem; line-height: 1.6rem;">•</div>
                                    <div class="text-gray-400 text-sm" style="height: 1.6rem; line-height: 1.6rem;">•</div>
                                    <div class="text-gray-400 text-sm" style="height: 1.6rem; line-height: 1.6rem;">•</div>
                                    <div class="text-gray-400 text-sm" style="height: 1.6rem; line-height: 1.6rem;">•</div>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">💡 Write one achievement/activity per line. Each line will appear as a bullet point in your CV.</p>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-gray-200">
                        <button type="button" 
                                @click="closing = true; document.body.style.overflow = 'auto'; $wire.closeModal()"
                                class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-6 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-medium shadow-sm">
                            <span wire:loading.remove wire:target="save">
                                {{ $editMode ? 'Update Education' : 'Save Education' }}
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
