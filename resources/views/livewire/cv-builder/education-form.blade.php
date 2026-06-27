<div class="space-y-5">
    <!-- Header -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 bg-zinc-50 border border-zinc-200/50 rounded flex items-center justify-center text-zinc-500 shrink-0 shadow-3xs">
                <i class="ph ph-graduation-cap text-base"></i>
            </div>
            <div>
                <h3 class="text-xs font-bold text-zinc-800 tracking-tight uppercase tracking-wider">Education</h3>
                <p class="text-[10px] text-zinc-400 font-medium leading-none mt-0.5">Your academic background</p>
            </div>
        </div>
        <button wire:click="openModal" 
                class="w-full md:w-auto px-4 py-1.5 bg-primary-50 text-zinc-800 border border-primary-200/60 rounded-md font-bold text-[10px] uppercase tracking-wider hover:bg-primary-100 transition-colors shadow-3xs flex items-center justify-center gap-1.5 focus:outline-none shrink-0">
            <i class="ph ph-plus text-xs"></i>
            Add Education
        </button>
    </div>

    <!-- Education List -->
    @if($educations->count() > 0)
        <div class="grid grid-cols-1 gap-3">
            @foreach($educations as $education)
                <div class="bg-white border border-zinc-200/60 rounded-lg p-4 hover:border-zinc-350 transition-colors group flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-3xs">
                    <div class="flex items-start gap-3 min-w-0">
                        <div class="w-9 h-9 bg-zinc-50 border border-zinc-200/50 rounded flex items-center justify-center text-zinc-400 group-hover:bg-zinc-100 group-hover:text-zinc-700 transition-all shrink-0">
                            <i class="ph ph-student text-base"></i>
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-xs font-bold text-zinc-800 tracking-tight truncate leading-none mb-1">{{ $education->institution_name }}</h4>
                            <p class="text-[11px] font-semibold text-zinc-500 mb-1 leading-none">{{ $education->degree }} in {{ $education->major }}</p>
                            <div class="flex flex-wrap items-center gap-x-2 gap-y-1 mt-1.5 leading-none">
                                <span class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest">
                                    {{ $education->start_date?->format('Y') }} - {{ $education->is_current ? 'PRESENT' : $education->end_date?->format('Y') }}
                                </span>
                                @if($education->gpa)
                                    <span class="text-[9px] font-black text-emerald-600 uppercase tracking-wider bg-emerald-50 px-1.5 py-0.5 rounded">GPA: {{ $education->gpa }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 md:opacity-0 group-hover:opacity-100 transition-opacity">
                        <div class="flex bg-zinc-50 p-0.5 rounded border border-zinc-200/60 shadow-3xs">
                            <button wire:click="moveUp({{ $education->id }})" class="w-6 h-6 flex items-center justify-center text-zinc-400 hover:text-zinc-700 rounded transition-colors focus:outline-none">
                                <i class="ph ph-caret-up"></i>
                            </button>
                            <button wire:click="moveDown({{ $education->id }})" class="w-6 h-6 flex items-center justify-center text-zinc-400 hover:text-zinc-700 rounded transition-colors focus:outline-none">
                                <i class="ph ph-caret-down"></i>
                            </button>
                        </div>
                        <button wire:click="edit({{ $education->id }})" class="w-7 h-7 flex items-center justify-center bg-zinc-50 border border-zinc-200 text-zinc-600 rounded-md hover:bg-zinc-155 transition-colors focus:outline-none">
                            <i class="ph ph-pencil-simple"></i>
                        </button>
                        <button wire:click="confirmDelete({{ $education->id }})" class="w-7 h-7 flex items-center justify-center bg-rose-50 border border-rose-100/50 text-rose-600 rounded-md hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-colors focus:outline-none">
                            <i class="ph ph-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-zinc-50/15 border border-dashed border-zinc-200 rounded-lg p-10 text-center transition-colors hover:bg-zinc-50/30 group">
            <div class="w-10 h-10 bg-white border border-zinc-200 rounded-md flex items-center justify-center text-zinc-400 mx-auto mb-3 shadow-3xs group-hover:scale-105 transition-transform">
                <i class="ph ph-student text-xl"></i>
            </div>
            <p class="text-[11px] font-bold text-zinc-500 mb-3">No education added yet</p>
            <button wire:click="openModal" class="text-[9px] font-black text-primary-600 /* [BRAND_PRIMARY] */ uppercase tracking-widest hover:text-primary-700">
                ADD YOUR FIRST EDUCATION
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
                                <h3 class="text-xs font-bold text-zinc-800 tracking-tight">{{ $editMode ? 'Edit Education' : 'Add Education' }}</h3>
                                <span class="px-1.5 py-0.5 bg-primary-50 text-zinc-800 text-[8.5px] font-bold uppercase tracking-wider rounded border border-primary-100/60 leading-none">Education</span>
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
                            <label class="block text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-1.5">School / University</label>
                            <div class="relative">
                                <input type="text" wire:model="institution_name" class="w-full pl-9 pr-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-md text-xs text-zinc-700 focus:outline-none focus:bg-white focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 transition-colors" placeholder="e.g. University of Indonesia">
                                <i class="ph ph-student absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 text-sm"></i>
                            </div>
                            @error('institution_name') <p class="text-rose-500 text-[9px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-1.5">Degree</label>
                            <input type="text" wire:model="degree" class="w-full px-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-md text-xs text-zinc-700 focus:outline-none focus:bg-white focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 transition-colors" placeholder="e.g. Bachelor's Degree">
                            @error('degree') <p class="text-rose-500 text-[9px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-1.5">Field of Study</label>
                            <input type="text" wire:model="major" class="w-full px-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-md text-xs text-zinc-700 focus:outline-none focus:bg-white focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 transition-colors" placeholder="e.g. Computer Science">
                            @error('major') <p class="text-rose-500 text-[9px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-1.5">Start Date</label>
                            <input type="date" wire:model="start_date" class="w-full px-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-md text-xs text-zinc-700 focus:outline-none focus:bg-white focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                            @error('start_date') <p class="text-rose-500 text-[9px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-1.5">End Date</label>
                            <input type="date" wire:model="end_date" :disabled="$wire.is_current" class="w-full px-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-md text-xs text-zinc-700 focus:outline-none focus:bg-white focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 transition-colors disabled:opacity-40">
                        </div>

                        <div>
                            <label class="block text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-1.5">GPA (Optional)</label>
                            <input type="text" wire:model="gpa" class="w-full px-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-md text-xs text-zinc-700 focus:outline-none focus:bg-white focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 transition-colors" placeholder="e.g. 3.8/4.0">
                        </div>

                        <div class="md:col-span-2">
                            <label class="flex items-center gap-2 p-2 bg-primary-50/20 border border-primary-100 rounded-md cursor-pointer hover:bg-primary-50/40 transition-colors">
                                <input type="checkbox" wire:model="is_current" class="w-4 h-4 rounded text-primary-600 focus:ring-primary-500 border-zinc-300">
                                <span class="text-xs font-semibold text-zinc-755">I currently study here</span>
                            </label>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-1.5">Description (Optional)</label>
                            <textarea wire:model="description" rows="3" class="w-full px-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-md text-xs text-zinc-700 focus:outline-none focus:bg-white focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 transition-colors leading-relaxed" placeholder="Relevant coursework, honors, or activities..."></textarea>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-zinc-150/60">
                        <button type="button" wire:click="closeModal" class="px-3.5 py-1.5 text-xs font-bold text-zinc-600 bg-zinc-100 hover:bg-zinc-200 rounded-md transition-colors focus:outline-none">Cancel</button>
                        <button type="submit" class="px-3.5 py-1.5 text-xs font-bold bg-primary-50 text-zinc-800 border border-primary-200/60 hover:bg-primary-100 rounded-md transition-colors shadow-3xs focus:outline-none flex items-center justify-center gap-1.5 active:scale-97">
                            <span wire:loading.remove wire:target="save">{{ $editMode ? 'Update' : 'Save Education' }}</span>
                            <span wire:loading wire:target="save" class="flex items-center gap-1"><i class="ph ph-spinner animate-spin text-xs"></i> Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endteleport
</div>
