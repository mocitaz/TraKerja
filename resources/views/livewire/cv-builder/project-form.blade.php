<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-primary-50 rounded-2xl flex items-center justify-center text-primary-600 shadow-inner">
                <i class="ph-duotone ph-code text-2xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Projects</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mt-1.5">Personal or professional projects</p>
            </div>
        </div>
        <button wire:click="openModal" 
                class="w-full md:w-auto px-6 py-3 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-[2px] hover:bg-slate-800 transition-all shadow-xl shadow-slate-200 flex items-center justify-center gap-2 active:scale-95">
            <i class="ph-bold ph-plus text-base"></i>
            ADD PROJECT
        </button>
    </div>

    <!-- Project List -->
    @if($projects->count() > 0)
        <div class="grid grid-cols-1 gap-4">
            @foreach($projects as $project)
                <div class="bg-white border border-slate-200/60 rounded-3xl p-5 hover:border-primary-600/30 transition-all group flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-start gap-5 min-w-0">
                        <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 transition-all shrink-0">
                            <i class="ph-bold ph-code-block text-xl"></i>
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-base font-black text-slate-900 tracking-tight truncate">{{ $project->project_name }}</h4>
                            <p class="text-sm font-bold text-slate-500 mb-1">{{ $project->role }}</p>
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    {{ $project->start_date?->format('M Y') }} - {{ $project->is_ongoing ? 'PRESENT' : $project->end_date?->format('M Y') }}
                                </span>
                                @if($project->project_url)
                                    <a href="{{ $project->project_url }}" target="_blank" class="text-[10px] font-black text-primary-600 uppercase tracking-widest flex items-center gap-1 hover:underline">
                                        <i class="ph-bold ph-link"></i> VIEW PROJECT
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 md:opacity-0 group-hover:opacity-100 transition-all">
                        <button wire:click="edit({{ $project->id }})" class="w-10 h-10 flex items-center justify-center bg-primary-50 text-primary-600 rounded-xl hover:bg-primary-600 hover:text-white transition-all">
                            <i class="ph-bold ph-pencil-simple"></i>
                        </button>
                        <button wire:click="delete({{ $project->id }})" wire:confirm="Delete this project?" class="w-10 h-10 flex items-center justify-center bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition-all">
                            <i class="ph-bold ph-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-slate-50/50 border-2 border-dashed border-slate-200 rounded-[2rem] p-12 text-center transition-all hover:bg-slate-50 group">
            <div class="w-16 h-16 bg-white rounded-3xl flex items-center justify-center text-slate-300 mx-auto mb-6 shadow-sm group-hover:scale-110 transition-transform">
                <i class="ph-duotone ph-code text-4xl"></i>
            </div>
            <p class="text-sm font-bold text-slate-500 mb-4">No projects added yet</p>
            <button wire:click="openModal" class="text-xs font-black text-primary-600 uppercase tracking-widest hover:text-primary-700">
                ADD YOUR FIRST PROJECT
            </button>
        </div>
    @endif

    @teleport('body')
    <div class="fixed inset-0 z-[9999] bg-slate-900/60 backdrop-blur-xl overflow-y-auto {{ !$showModal ? 'hidden' : '' }}">
            <div class="flex min-h-full items-center justify-center p-4" wire:click.self="closeModal">
                <div class="relative bg-white rounded-[2rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.25)] max-w-lg w-full border border-slate-100 overflow-hidden">
                <div class="bg-white px-6 py-5 flex justify-between items-center border-b border-slate-100 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center">
                            <i class="ph-fill ph-code text-emerald-600 text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900 tracking-tight">{{ $editMode ? 'Edit Project' : 'Add Project' }}</h3>
                            <p class="text-slate-400 text-[9px] font-bold uppercase tracking-widest mt-0.5">Personal & Professional Work</p>
                        </div>
                    </div>
                    <button wire:click="closeModal" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-50 transition-all text-slate-400 hover:text-slate-900">
                        <i class="ph-bold ph-x text-base"></i>
                    </button>
                </div>
                <form wire:submit.prevent="save" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Project Name</label>
                            <div class="relative">
                                <input type="text" wire:model="project_name" class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-400 transition-all" placeholder="e.g. E-commerce Platform, Mobile App">
                                <i class="ph ph-code-block absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            </div>
                            @error('project_name') <p class="text-rose-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Your Role</label>
                            <div class="relative">
                                <input type="text" wire:model="role" class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-400 transition-all" placeholder="e.g. Full Stack Developer, UI Designer">
                                <i class="ph ph-user-focus absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            </div>
                            @error('role') <p class="text-rose-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Start Date</label>
                            <input type="date" wire:model="start_date" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-400 transition-all">
                            @error('start_date') <p class="text-rose-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">End Date</label>
                            <input type="date" wire:model="end_date" :disabled="$wire.is_ongoing" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-400 transition-all disabled:opacity-40">
                        </div>
                        <div class="md:col-span-2">
                            <label class="flex items-center gap-2.5 p-3 bg-primary-50/60 border border-primary-100 rounded-xl cursor-pointer hover:bg-primary-50 transition-all">
                                <input type="checkbox" wire:model="is_ongoing" class="w-4 h-4 rounded text-primary-600 focus:ring-primary-500 border-slate-300">
                                <span class="text-sm font-semibold text-slate-700">This is an ongoing project</span>
                            </label>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Project URL (Optional)</label>
                            <div class="relative">
                                <input type="url" wire:model="project_url" class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-400 transition-all" placeholder="https://github.com/...">
                                <i class="ph ph-link absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Technologies (Optional)</label>
                            <input type="text" wire:model="technologies" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-400 transition-all" placeholder="e.g. React, Node.js, Tailwind CSS">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Description (Optional)</label>
                            <textarea wire:model="description" rows="3" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-400 transition-all leading-relaxed" placeholder="Describe the project goals and your contributions..."></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-5 pt-5 border-t border-slate-100">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all">Cancel</button>
                        <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-xl transition-all shadow-sm active:scale-95">
                            <span wire:loading.remove wire:target="save">{{ $editMode ? 'Update' : 'Save Project' }}</span>
                            <span wire:loading wire:target="save" class="flex items-center gap-1.5"><i class="ph-bold ph-spinner animate-spin text-sm"></i> Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endteleport
</div>