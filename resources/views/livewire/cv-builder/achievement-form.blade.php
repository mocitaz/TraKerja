<div class="space-y-6" @keydown.escape.window="$wire.closeModal()">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Achievements & Certifications</h3>
            <p class="text-sm text-gray-600">Add your certificates, awards, and accomplishments</p>
        </div>
        <button wire:click="openModal" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 hover:shadow-lg transition-all duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add Achievement
        </button>
    </div>

    @if($achievements->count() > 0)
        <div class="space-y-4">
            @foreach($achievements as $achievement)
                <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                <h4 class="font-semibold text-gray-900">{{ $achievement->title }}</h4>
                            </div>
                            <p class="text-sm text-gray-700 mt-1">{{ $achievement->issuer }}</p>
                            <p class="text-sm text-gray-500">{{ $achievement->issue_date?->format('M Y') }}</p>
                        </div>
                        <div class="flex items-center gap-2 ml-4">
                            <button wire:click="moveUp({{ $achievement->id }})" class="p-1 text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg></button>
                            <button wire:click="moveDown({{ $achievement->id }})" class="p-1 text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></button>
                            <button wire:click="edit({{ $achievement->id }})" class="p-2 text-primary-600 hover:bg-primary-50 rounded"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                            <button wire:click="delete({{ $achievement->id }})" wire:confirm="Delete this achievement?" class="p-2 text-red-600 hover:bg-red-50 rounded"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-gradient-to-br from-gray-50 to-yellow-50 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-yellow-300 transition-colors duration-200">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
            <p class="mt-2 text-sm text-gray-600">No achievements added yet</p>
            <button wire:click="openModal" class="mt-3 text-sm text-primary-600 hover:text-primary-800 font-medium hover:underline transition">Add your first achievement</button>
        </div>
    @endif

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
                            <h3 class="text-xl font-bold text-gray-900">{{ $editMode ? 'Edit Achievement' : 'Add New Achievement' }}</h3>
                            <p class="text-sm text-gray-600 mt-1">Certificates, awards, and recognition</p>
                        </div>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>
                </div>

                <!-- Modal Body - Scrollable -->
                <form wire:submit.prevent="save" class="px-6 py-6 overflow-y-auto flex-1">
                    <div class="space-y-6">
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label><input type="text" wire:model="title" class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" placeholder="e.g., AWS Certified Solutions Architect" autofocus>@error('title') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror</div>
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Issuer / Organization <span class="text-red-500">*</span></label><input type="text" wire:model="issuer" class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" placeholder="e.g., Amazon Web Services">@error('issuer') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror</div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div><label class="block text-sm font-medium text-gray-700 mb-1">Issue Date <span class="text-red-500">*</span></label><input type="date" wire:model="issue_date" class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">@error('issue_date') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror</div>
                            <div><label class="block text-sm font-medium text-gray-700 mb-1">Credential ID (Optional)</label><input type="text" wire:model="credential_id" class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" placeholder="e.g., ABC123XYZ"></div>
                        </div>
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Credential URL (Optional)</label><input type="url" wire:model="credential_url" class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" placeholder="https://..."></div>
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Description (Optional)</label><textarea wire:model="description" rows="3" class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" placeholder="Additional details..."></textarea></div>
                    </div>
                    <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-gray-200">
                        <button type="button" wire:click="closeModal" class="px-6 py-2.5 bg-white border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-medium">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-lg hover:from-yellow-600 hover:to-orange-600 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:scale-105"><span wire:loading.remove wire:target="save">{{ $editMode ? 'Update' : 'Save' }} Achievement</span><span wire:loading wire:target="save" class="flex items-center gap-2"><svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Saving...</span></button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    @endif


</div>