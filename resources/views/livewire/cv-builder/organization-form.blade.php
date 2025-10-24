<div class="space-y-6" @keydown.escape.window="$wire.closeModal()">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Organizations</h3>
            <p class="text-sm text-gray-600">Add organizations and volunteer work</p>
        </div>
        <button wire:click="openModal" 
                class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 hover:shadow-lg transition-all duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Organization
        </button>
    </div>

    @if($organizations->count() > 0)
        <div class="space-y-4">
            @foreach($organizations as $org)
                <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">{{ $org->position }}</h4>
                            <p class="text-sm text-gray-700">{{ $org->organization_name }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $org->start_date?->format('M Y') }} - 
                                {{ $org->is_current ? 'Present' : $org->end_date?->format('M Y') }}
                                @if($org->location) • {{ $org->location }} @endif
                            </p>
                            @if($org->description)
                                <p class="text-sm text-gray-600 mt-2">{{ Str::limit($org->description, 150) }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 ml-4">
                            <button wire:click="moveUp({{ $org->id }})" class="p-1 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>
                            <button wire:click="moveDown({{ $org->id }})" class="p-1 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <button wire:click="edit({{ $org->id }})" class="p-2 text-primary-600 hover:bg-primary-50 rounded">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button wire:click="delete({{ $org->id }})" wire:confirm="Delete this organization?" class="p-2 text-red-600 hover:bg-red-50 rounded">
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
        <div class="bg-gradient-to-br from-gray-50 to-blue-50 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-primary-300 transition-colors duration-200">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <p class="mt-2 text-sm text-gray-600">No organizations added yet</p>
            <button wire:click="openModal" class="mt-3 text-sm text-primary-600 hover:text-primary-800 font-medium hover:underline transition">
                Add your first organization
            </button>
        </div>
    @endif

    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" 
             x-data 
             x-init="document.body.style.overflow = 'hidden'" 
             x-destroy="document.body.style.overflow = 'auto'">
            <!-- Backdrop with blur -->
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" 
                 wire:click="closeModal" 
                 @click="document.body.style.overflow = 'auto'"></div>
            
            <!-- Modal Content -->
            <div class="relative bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col">
                <!-- Modal Header - Sticky -->
                <div class="border-b border-gray-200 px-6 py-4 bg-white rounded-t-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $editMode ? 'Edit Organization' : 'Add New Organization' }}</h3>
                            <p class="text-sm text-gray-600 mt-1">Add clubs, communities, or volunteer work</p>
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
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Organization Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="organization_name" class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" placeholder="e.g., Google Developer Student Club" autofocus>
                            @error('organization_name') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Your Position / Role <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="position" class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" placeholder="e.g., President, Volunteer">
                            @error('position') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Location (Optional)</label>
                            <input type="text" wire:model="location" class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" placeholder="e.g., Jakarta, Indonesia">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Start Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" wire:model="start_date" class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                                @error('start_date') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                                <input type="date" wire:model="end_date" :disabled="$wire.is_current" class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                            </div>
                        </div>

                        <div class="flex items-center bg-primary-50 border border-primary-200 rounded-lg p-4">
                            <input type="checkbox" wire:model="is_current" id="is_current_org" class="h-5 w-5 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                            <label for="is_current_org" class="ml-3 block text-sm font-medium text-gray-900">
                                I currently participate in this organization
                            </label>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description (Optional)</label>
                            <div class="relative">
                                <textarea wire:model="description" 
                                          rows="5" 
                                          class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm font-mono"
                                          style="line-height: 1.6; padding-left: 2.5rem; background-image: repeating-linear-gradient(transparent, transparent 1.6rem, #f3f4f6 1.6rem, #f3f4f6 calc(1.6rem + 1px)); background-size: 100% 1.6rem; background-attachment: local;"
                                          placeholder="Led team of 10 members in organizing annual conference&#10;Managed budget of $5,000 and secured 3 sponsors&#10;Coordinated with stakeholders and vendors"></textarea>
                                <div class="absolute left-3 top-2 bottom-2 flex flex-col justify-start pointer-events-none" style="line-height: 1.6;">
                                    <div class="text-gray-400 text-sm" style="height: 1.6rem; line-height: 1.6rem;">•</div>
                                    <div class="text-gray-400 text-sm" style="height: 1.6rem; line-height: 1.6rem;">•</div>
                                    <div class="text-gray-400 text-sm" style="height: 1.6rem; line-height: 1.6rem;">•</div>
                                    <div class="text-gray-400 text-sm" style="height: 1.6rem; line-height: 1.6rem;">•</div>
                                    <div class="text-gray-400 text-sm" style="height: 1.6rem; line-height: 1.6rem;">•</div>
                                </div>
                            </div>
                            <p class="mt-1.5 text-xs text-gray-500">💡 Write one responsibility/achievement per line. Each line will appear as a bullet point in your CV.</p>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-gray-200">
                        <button type="button" 
                                @click="closing = true; document.body.style.overflow = 'auto'; $wire.closeModal()" 
                                class="px-6 py-2.5 bg-white border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-medium">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:scale-105">
                            <span wire:loading.remove wire:target="save">{{ $editMode ? 'Update' : 'Save' }} Organization</span>
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
        </div>
    @endif


</div>
