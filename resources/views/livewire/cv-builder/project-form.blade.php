<div class="space-y-5">
    <!-- Header -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 bg-zinc-50 border border-zinc-200/50 rounded flex items-center justify-center text-zinc-500 shrink-0 shadow-3xs">
                <i class="ph ph-code text-base"></i>
            </div>
            <div>
                <h3 class="text-xs font-bold text-zinc-800 tracking-tight uppercase tracking-wider">Projects</h3>
                <p class="text-[10px] text-zinc-400 font-medium leading-none mt-0.5">Personal or professional projects</p>
            </div>
        </div>
        <button wire:click="openModal" 
                class="w-full md:w-auto px-4 py-1.5 bg-primary-50 text-zinc-800 border border-primary-200/60 rounded-md font-bold text-[10px] uppercase tracking-wider hover:bg-primary-100 transition-colors shadow-3xs flex items-center justify-center gap-1.5 focus:outline-none shrink-0">
            <i class="ph ph-plus text-xs"></i>
            Add Project
        </button>
    </div>

    <!-- Project List -->
    @if($projects->count() > 0)
        <div class="grid grid-cols-1 gap-3">
            @foreach($projects as $project)
                <div class="bg-white border border-zinc-200/60 rounded-lg p-4 hover:border-zinc-350 transition-colors group flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-3xs">
                    <div class="flex items-start gap-3 min-w-0">
                        <div class="w-9 h-9 bg-zinc-50 border border-zinc-200/50 rounded flex items-center justify-center text-zinc-400 group-hover:bg-zinc-100 group-hover:text-zinc-700 transition-all shrink-0">
                            <i class="ph ph-code-block text-base"></i>
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-xs font-bold text-zinc-800 tracking-tight truncate leading-none mb-1">{{ $project->project_name }}</h4>
                            <p class="text-[11px] font-semibold text-zinc-500 mb-1 leading-none">{{ $project->role }}</p>
                            <div class="flex flex-wrap items-center gap-x-2 gap-y-1 mt-1.5 leading-none">
                                <span class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest">
                                    {{ $project->start_date?->format('M Y') }} - {{ $project->is_ongoing ? 'PRESENT' : $project->end_date?->format('M Y') }}
                                </span>
                                @if($project->project_url)
                                    <a href="{{ $project->project_url }}" target="_blank" class="text-[9px] font-black text-primary-600 /* [BRAND_PRIMARY] */ uppercase tracking-wider flex items-center gap-1 hover:underline">
                                        <i class="ph ph-link"></i> VIEW PROJECT
                                    </a>
                                @endif
                            </div>
                            @if($project->technologies && is_array($project->technologies))
                                <div class="flex flex-wrap gap-1.5 mt-2.5">
                                    @foreach($project->technologies as $tech)
                                        <span class="px-1.5 py-0.5 bg-zinc-50 text-zinc-500 text-[8.5px] font-bold rounded border border-zinc-100 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:border-primary-100 transition-colors">
                                            {{ $tech }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 md:opacity-0 group-hover:opacity-100 transition-opacity">
                        <button wire:click="edit({{ $project->id }})" class="w-7 h-7 flex items-center justify-center bg-zinc-50 border border-zinc-200 text-zinc-600 rounded-md hover:bg-zinc-155 transition-colors focus:outline-none">
                            <i class="ph ph-pencil-simple"></i>
                        </button>
                        <button wire:click="confirmDelete({{ $project->id }})" class="w-7 h-7 flex items-center justify-center bg-rose-50 border border-rose-100/50 text-rose-600 rounded-md hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-colors focus:outline-none">
                            <i class="ph ph-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-zinc-50/15 border border-dashed border-zinc-200 rounded-lg p-10 text-center transition-colors hover:bg-zinc-50/30 group">
            <div class="w-10 h-10 bg-white border border-zinc-200 rounded-md flex items-center justify-center text-zinc-400 mx-auto mb-3 shadow-3xs group-hover:scale-105 transition-transform">
                <i class="ph ph-code text-xl"></i>
            </div>
            <p class="text-[11px] font-bold text-zinc-500 mb-3">No projects added yet</p>
            <button wire:click="openModal" class="text-[9px] font-black text-primary-600 /* [BRAND_PRIMARY] */ uppercase tracking-widest hover:text-primary-700">
                ADD YOUR FIRST PROJECT
            </button>
        </div>
    @endif

    @teleport('body')
    <div class="fixed inset-0 z-[9999] bg-zinc-950/40 backdrop-blur-xs overflow-y-auto {{ !$showModal ? 'hidden' : '' }}">
        <div class="flex min-h-full items-center justify-center p-4" wire:click.self="closeModal">
            <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full border border-zinc-200 overflow-hidden text-left" @click.stop>
                <!-- Modal Header: Clean White -->
                <div class="bg-white px-4 py-3 text-zinc-900 flex justify-between items-center border-b border-zinc-150/60 shrink-0">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200/60 flex items-center justify-center shadow-3xs">
                            <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-4 h-4 object-contain" onerror="this.src='{{ asset('favicon.png') }}'">
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <h3 class="text-xs font-bold text-zinc-800 tracking-tight">{{ $editMode ? 'Edit Project' : 'Add Project' }}</h3>
                                <span class="px-1.5 py-0.5 bg-primary-50 text-zinc-800 text-[8.5px] font-bold uppercase tracking-wider rounded border border-primary-100/60 leading-none">Projects</span>
                            </div>
                            <p class="text-zinc-400 text-[9px] font-medium mt-0.5">Career Growth Tracking</p>
                        </div>
                    </div>
                    <button type="button" wire:click="closeModal" class="w-7 h-7 flex items-center justify-center rounded hover:bg-zinc-50 transition-all text-zinc-400 hover:text-zinc-800 focus:outline-none">
                        <i class="ph ph-x text-sm"></i>
                    </button>
                </div>

                <!-- Body -->
                <form wire:submit.prevent="save" class="p-4 sm:p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                        <div class="md:col-span-2">
                            <label class="block text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-1.5">Project Name</label>
                            <div class="relative">
                                <input type="text" wire:model="project_name" class="w-full pl-9 pr-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-md text-xs text-zinc-700 focus:outline-none focus:bg-white focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 transition-colors" placeholder="e.g. E-commerce Platform, Mobile App">
                                <i class="ph ph-code-block absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 text-sm"></i>
                            </div>
                            @error('project_name') <p class="text-rose-500 text-[9px] mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-1.5">Your Role</label>
                            <div class="relative">
                                <input type="text" wire:model="role" class="w-full pl-9 pr-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-md text-xs text-zinc-700 focus:outline-none focus:bg-white focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 transition-colors" placeholder="e.g. Full Stack Developer, UI Designer">
                                <i class="ph ph-user-focus absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 text-sm"></i>
                            </div>
                            @error('role') <p class="text-rose-500 text-[9px] mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-1.5">Start Date</label>
                            <input type="date" wire:model="start_date" class="w-full px-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-md text-xs text-zinc-700 focus:outline-none focus:bg-white focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                            @error('start_date') <p class="text-rose-500 text-[9px] mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-1.5">End Date</label>
                            <input type="date" wire:model="end_date" :disabled="$wire.is_ongoing" class="w-full px-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-md text-xs text-zinc-700 focus:outline-none focus:bg-white focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 transition-colors disabled:opacity-40">
                        </div>
                        <div class="md:col-span-2">
                            <label class="flex items-center gap-2 p-2 bg-primary-50/20 border border-primary-100 rounded-md cursor-pointer hover:bg-primary-50/40 transition-colors">
                                <input type="checkbox" wire:model="is_ongoing" class="w-4 h-4 rounded text-primary-600 focus:ring-primary-500 border-zinc-300">
                                <span class="text-xs font-semibold text-zinc-755">This is an ongoing project</span>
                            </label>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-1.5">Project URL (Optional)</label>
                            <div class="relative">
                                <input type="url" wire:model="project_url" class="w-full pl-9 pr-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-md text-xs text-zinc-700 focus:outline-none focus:bg-white focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 transition-colors" placeholder="https://github.com/...">
                                <i class="ph ph-link absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 text-sm"></i>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-1.5">Technologies (Optional)</label>
                            <input type="text" wire:model="technologies" class="w-full px-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-md text-xs text-zinc-700 focus:outline-none focus:bg-white focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 transition-colors" placeholder="e.g. React, Node.js, Tailwind CSS">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-1.5">Description (Optional)</label>
                            <textarea wire:model="description" rows="3" class="w-full px-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-md text-xs text-zinc-700 focus:outline-none focus:bg-white focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 transition-colors leading-relaxed" placeholder="Describe the project goals and your contributions..."></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-zinc-150/60">
                        <button type="button" wire:click="closeModal" class="px-3.5 py-1.5 text-xs font-bold text-zinc-600 bg-zinc-100 hover:bg-zinc-200 rounded-md transition-colors focus:outline-none">Cancel</button>
                        <button type="submit" class="px-3.5 py-1.5 text-xs font-bold bg-primary-50 text-zinc-800 border border-primary-200/60 hover:bg-primary-100 rounded-md transition-colors shadow-3xs focus:outline-none flex items-center justify-center gap-1.5 active:scale-97">
                            <span wire:loading.remove wire:target="save">{{ $editMode ? 'Update' : 'Save Project' }}</span>
                            <span wire:loading wire:target="save" class="flex items-center gap-1"><i class="ph ph-spinner animate-spin text-xs"></i> Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endteleport
    {{-- Inline Delete Confirmation Modal --}}
    @teleport('body')
    <div class="fixed inset-0 z-[10000] bg-zinc-950/50 backdrop-blur-xs flex items-center justify-center p-4 {{ !$showDeleteConfirm ? 'hidden' : '' }}">
        <div class="bg-white rounded-xl shadow-xl max-w-sm w-full border border-zinc-200 overflow-hidden" @click.stop>
            <div class="p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-full bg-rose-50 border border-rose-100 flex items-center justify-center shrink-0">
                        <i class="ph ph-trash text-rose-600 text-base"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-zinc-800">Confirm Delete?</h4>
                        <p class="text-[10px] text-zinc-400 mt-0.5">This action cannot be undone.</p>
                    </div>
                </div>
                <p class="text-[11px] text-zinc-600 font-medium leading-relaxed mb-4">
                    Are you sure you want to delete this record?
                </p>
                <div class="flex justify-end gap-2 pt-3 border-t border-zinc-100">
                    <button type="button" wire:click="cancelDelete" class="px-3.5 py-1.5 text-[10px] font-bold text-zinc-600 bg-zinc-100 hover:bg-zinc-200 rounded-md transition-colors focus:outline-none">
                        Cancel
                    </button>
                    <button type="button" wire:click="delete" class="px-3.5 py-1.5 text-[10px] font-bold bg-rose-600 text-white hover:bg-rose-700 rounded-md transition-colors shadow-sm focus:outline-none flex items-center gap-1.5 active:scale-97">
                        <i class="ph ph-trash text-xs"></i>
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endteleport

</div>