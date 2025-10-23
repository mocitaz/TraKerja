<div class="space-y-6" @keydown.escape.window="$wire.closeModal()">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Projects</h3>
            <p class="text-sm text-gray-600">Showcase your portfolio and side projects</p>
        </div>
        <button wire:click="openModal" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 hover:shadow-lg transition-all duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add Project
        </button>
    </div>

    @if($projects->count() > 0)
        <div class="space-y-4">
            @foreach($projects as $project)
                <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-primary-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                <h4 class="font-semibold text-gray-900">{{ $project->project_name }}</h4>
                            </div>
                            <p class="text-sm text-gray-700 mt-1">{{ $project->role }}</p>
                            <p class="text-sm text-gray-500">{{ $project->start_date?->format('M Y') }} - {{ $project->is_ongoing ? 'Ongoing' : $project->end_date?->format('M Y') }}</p>
                            @if($project->technologies)<p class="text-xs text-primary-600 mt-1">{{ $project->technologies }}</p>@endif
                        </div>
                        <div class="flex items-center gap-2 ml-4">
                            <button wire:click="moveUp({{ $project->id }})" class="p-1 text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg></button>
                            <button wire:click="moveDown({{ $project->id }})" class="p-1 text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></button>
                            <button wire:click="edit({{ $project->id }})" class="p-2 text-primary-600 hover:bg-primary-50 rounded"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                            <button wire:click="delete({{ $project->id }})" wire:confirm="Delete this project?" class="p-2 text-red-600 hover:bg-red-50 rounded"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-gradient-to-br from-gray-50 to-purple-50 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-purple-300 transition-colors duration-200">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
            <p class="mt-2 text-sm text-gray-600">No projects added yet</p>
            <button wire:click="openModal" class="mt-3 text-sm text-primary-600 hover:text-primary-800 font-medium hover:underline transition">Add your first project</button>
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
                            <h3 class="text-xl font-bold text-gray-900">{{ $editMode ? 'Edit Project' : 'Add New Project' }}</h3>
                            <p class="text-sm text-gray-600 mt-1">Personal or professional projects</p>
                        </div>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>
                </div>

                <!-- Modal Body - Scrollable -->
                <form wire:submit.prevent="save" class="px-6 py-6 overflow-y-auto flex-1">
                    <div class="space-y-6">
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Project Name <span class="text-red-500">*</span></label><input type="text" wire:model="project_name" class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" placeholder="e.g., E-commerce Platform" autofocus>@error('project_name') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror</div>
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Your Role <span class="text-red-500">*</span></label><input type="text" wire:model="role" class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" placeholder="e.g., Full Stack Developer">@error('role') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror</div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div><label class="block text-sm font-medium text-gray-700 mb-1">Start Date <span class="text-red-500">*</span></label><input type="date" wire:model="start_date" class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">@error('start_date') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror</div>
                            <div><label class="block text-sm font-medium text-gray-700 mb-1">End Date</label><input type="date" wire:model="end_date" :disabled="$wire.is_ongoing" class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm"></div>
                        </div>
                        <div class="flex items-center bg-primary-50 border border-primary-200 rounded-lg p-4"><input type="checkbox" wire:model="is_ongoing" id="is_ongoing" class="h-5 w-5 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"><label for="is_ongoing" class="ml-3 block text-sm font-medium text-gray-900">This is an ongoing project</label></div>
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Project URL (Optional)</label><input type="url" wire:model="project_url" class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" placeholder="https://..."></div>
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Technologies Used (Optional)</label><input type="text" wire:model="technologies" class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" placeholder="e.g., React, Node.js, MongoDB"><p class="mt-1.5 text-xs text-gray-500">Separate multiple technologies with commas</p></div>
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Description (Optional)</label><textarea wire:model="description" rows="4" class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" placeholder="Describe the project, your contributions, and outcomes..."></textarea></div>
                    </div>
                    <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-gray-200">
                        <button type="button" wire:click="closeModal" class="px-6 py-2.5 bg-white border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-medium">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:scale-105"><span wire:loading.remove wire:target="save">{{ $editMode ? 'Update' : 'Save' }} Project</span><span wire:loading wire:target="save" class="flex items-center gap-2"><svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Saving...</span></button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    @endif


</div>