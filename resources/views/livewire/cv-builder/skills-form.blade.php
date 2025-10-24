<div class="space-y-6" @keydown.escape.window="$wire.closeModal()">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Skills</h3>
            <p class="text-sm text-gray-600">Add your professional skills and expertise</p>
        </div>
        <button wire:click="openModal" 
                class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 hover:shadow-lg transition-all duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Skill
        </button>
    </div>

    <!-- Skills List -->
    @if($skills->count() > 0)
        @foreach($skills->groupBy('category') as $category => $categorySkills)
            <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                <h4 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 bg-primary-600 rounded-full animate-pulse"></span>
                    {{ $category }}
                </h4>
                <div class="space-y-2">
                    @foreach($categorySkills as $skill)
                        <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg hover:bg-primary-50 transition-colors duration-200 group">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-gray-900">{{ $skill->skill_name }}</span>
                                    <span class="mx-2 text-gray-400">•</span>
                                    <span class="text-sm text-gray-600">{{ $skill->proficiency }}</span>
                                    @if($skill->years_of_experience)
                                        <span class="mx-2 text-gray-400">•</span>
                                        <span class="text-sm text-gray-500">{{ $skill->years_of_experience }} years</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <button wire:click="edit({{ $skill->id }})" class="p-1.5 text-primary-600 hover:bg-white rounded transition-all duration-150">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button wire:click="delete({{ $skill->id }})" wire:confirm="Delete this skill?" class="p-1.5 text-red-600 hover:bg-white rounded transition-all duration-150">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @else
        <div class="bg-gradient-to-br from-gray-50 to-blue-50 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-primary-300 transition-colors duration-200">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="mt-2 text-sm text-gray-600">No skills added yet</p>
            <button wire:click="openModal" class="mt-3 text-sm text-primary-600 hover:text-primary-800 font-medium hover:underline transition">
                Add your first skill
            </button>
        </div>
    @endif

    <!-- Modal -->
    @if($showModal)
                <div class="fixed inset-0 z-50 overflow-y-auto" 
             x-data 
             x-init="document.body.style.overflow = 'hidden'">
            <!-- Backdrop with blur -->
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" wire:click="closeModal"></div>
            
            <!-- Modal Content -->
            <div class="relative bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col">
                <!-- Modal Header - Sticky -->
                <div class="border-b border-gray-200 px-6 py-4 bg-white rounded-t-lg">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">
                            @if($editMode) Edit Skill @else Add New Skill @endif
                        </h3>
                        <button type="button"
                                @click="closing = true; document.body.style.overflow = 'auto'; $wire.closeModal()" 
                                class="text-gray-400 hover:text-gray-600 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body - Scrollable -->
                <div class="p-6 overflow-y-auto">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Skill Name -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Skill Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       wire:model="skill_name" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm"
                                       placeholder="e.g. Python, Project Management">
                                @error('skill_name') 
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Category <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="category" 
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm appearance-none">
                                    <option value="">Select category</option>
                                    <option value="Technical Skills">Technical Skills</option>
                                    <option value="Soft Skills">Soft Skills</option>
                                    <option value="Languages">Languages</option>
                                    <option value="Tools & Technologies">Tools & Technologies</option>
                                    <option value="Design">Design</option>
                                    <option value="Management">Management</option>
                                    <option value="Other">Other</option>
                                </select>
                                @error('category') 
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Proficiency -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Proficiency <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="proficiency" 
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm appearance-none">
                                    <option value="">Select proficiency</option>
                                    <option value="Beginner">Beginner</option>
                                    <option value="Intermediate">Intermediate</option>
                                    <option value="Advanced">Advanced</option>
                                    <option value="Expert">Expert</option>
                                </select>
                                @error('proficiency') 
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Years of Experience -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Years of Experience (Optional)
                                </label>
                                <input type="number" 
                                       wire:model="years_of_experience" 
                                       min="0"
                                       step="0.5"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm"
                                       placeholder="e.g. 2.5">
                                @error('years_of_experience') 
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="border-t border-gray-200 px-6 py-4 bg-gray-50">
                                        <!-- Modal Footer -->
                    <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-gray-200">
                        <button type="button" 
                                @click="closing = true; document.body.style.overflow = 'auto'; $wire.closeModal()"
                                class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                            Cancel
                        </button>
                        <button type="button" 
                                wire:click="save" 
                                wire:loading.attr="disabled"
                                class="px-6 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-md transition-colors duration-200 disabled:opacity-50">
                            <span wire:loading.remove>@if($editMode) Update Skill @else Add Skill @endif</span>
                            <span wire:loading>Saving...</span>
                        </button>
                    </div>
                </div>
                </div>
            </div>
        </div>
    @endif


</div>
