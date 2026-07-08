<div class="space-y-5">
    <!-- Header -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 bg-zinc-50 border border-zinc-200/50 rounded flex items-center justify-center text-zinc-500 shrink-0 shadow-3xs">
                <i class="ph ph-star text-base"></i>
            </div>
            <div>
                <h3 class="text-xs font-bold text-zinc-800 tracking-tight uppercase tracking-wider">Skills</h3>
                <p class="text-[10px] text-zinc-400 font-medium leading-none mt-0.5">Your core competencies</p>
            </div>
        </div>
        <button wire:click="openModal" 
                class="w-full md:w-auto px-4 py-1.5 bg-primary-50 text-zinc-800 border border-primary-200/60 rounded-md font-bold text-[10px] uppercase tracking-wider hover:bg-primary-100 transition-colors shadow-3xs flex items-center justify-center gap-1.5 focus:outline-none shrink-0">
            <i class="ph ph-plus text-xs"></i>
            Add Skill
        </button>
    </div>

    <!-- Skills List -->
    @if($skills->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($skills as $skill)
                <div class="bg-white border border-zinc-200/60 rounded-lg p-3.5 hover:border-zinc-350 transition-colors group relative overflow-hidden shadow-3xs">
                    <div class="flex items-center justify-between gap-3 mb-3">
                        <h4 class="text-xs font-bold text-zinc-800 tracking-tight truncate leading-none">{{ $skill->skill_name }}</h4>
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button wire:click="edit({{ $skill->id }})" class="w-6 h-6 flex items-center justify-center bg-zinc-50 border border-zinc-200 text-zinc-650 rounded hover:bg-zinc-150 transition-colors focus:outline-none">
                                <i class="ph ph-pencil-simple text-xs"></i>
                            </button>
                            <button wire:click="confirmDelete({{ $skill->id }})" class="w-6 h-6 flex items-center justify-center bg-rose-50 border border-rose-100/50 text-rose-600 rounded hover:bg-rose-600 hover:text-white transition-colors focus:outline-none">
                                <i class="ph ph-trash text-xs"></i>
                            </button>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center leading-none">
                            <span class="text-[9px] font-black text-primary-650 /* [BRAND_PRIMARY] */ uppercase tracking-wider bg-primary-50 px-1.5 py-0.5 rounded">{{ $skill->proficiency }}</span>
                            @if($skill->category)
                                <span class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest">{{ $skill->category }}</span>
                            @endif
                        </div>
                        <div class="w-full bg-zinc-100 h-1 rounded-full overflow-hidden">
                            @php
                                $width = match($skill->proficiency) {
                                    'Beginner' => '25%',
                                    'Intermediate' => '50%',
                                    'Advanced' => '75%',
                                    'Expert' => '100%',
                                    default => '50%'
                                };
                            @endphp
                            <div class="bg-primary-500 /* [BRAND_PRIMARY] */ h-full rounded-full" style="width: {{ $width }}"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-zinc-50/15 border border-dashed border-zinc-200 rounded-lg p-10 text-center transition-colors hover:bg-zinc-50/30 group">
            <div class="w-10 h-10 bg-white border border-zinc-200 rounded-md flex items-center justify-center text-zinc-400 mx-auto mb-3 shadow-3xs group-hover:scale-105 transition-transform">
                <i class="ph ph-star text-xl"></i>
            </div>
            <p class="text-[11px] font-bold text-zinc-500 mb-3">No skills added yet</p>
            <button wire:click="openModal" class="text-[9px] font-black text-primary-600 /* [BRAND_PRIMARY] */ uppercase tracking-widest hover:text-primary-700">
                ADD YOUR FIRST SKILL
            </button>
        </div>
    @endif

    @teleport('body')
    <div class="fixed inset-0 z-[9999] bg-zinc-950/40 backdrop-blur-xs overflow-y-auto {{ !$showModal ? 'hidden' : '' }}">
        <div class="flex min-h-full items-center justify-center p-4" wire:click.self="closeModal">
            <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full border border-zinc-200 overflow-hidden text-left" @click.stop>
                <!-- Modal Header: Clean White -->
                <div class="bg-white px-4 py-3 text-zinc-900 flex justify-between items-center border-b border-zinc-150/60 shrink-0">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200/60 flex items-center justify-center shadow-3xs">
                            <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-4 h-4 object-contain" onerror="this.src='{{ asset('favicon.png') }}'">
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <h3 class="text-xs font-bold text-zinc-800 tracking-tight">{{ $editMode ? 'Edit Skill' : 'Add Skill' }}</h3>
                                <span class="px-1.5 py-0.5 bg-primary-50 text-zinc-800 text-[8.5px] font-bold uppercase tracking-wider rounded border border-primary-100/60 leading-none">Skills</span>
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
                    <div class="space-y-3.5">
                        <div>
                            <label class="block text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-1.5">Skill Name</label>
                            <div class="relative">
                                <input type="text" wire:model="skill_name" class="w-full pl-9 pr-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-md text-xs text-zinc-700 focus:outline-none focus:bg-white focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 transition-colors" placeholder="e.g. Laravel, UI Design, Marketing">
                                <i class="ph ph-lightning absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 text-sm"></i>
                            </div>
                            @error('skill_name') <p class="text-rose-500 text-[9px] mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-1.5">Proficiency Level</label>
                            <select wire:model="proficiency" class="w-full px-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-md text-xs text-zinc-700 focus:outline-none focus:bg-white focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 transition-colors appearance-none cursor-pointer">
                                <option value="">Select Level</option>
                                <option value="Beginner">Beginner</option>
                                <option value="Intermediate">Intermediate</option>
                                <option value="Advanced">Advanced</option>
                                <option value="Expert">Expert</option>
                            </select>
                            @error('proficiency') <p class="text-rose-500 text-[9px] mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-1.5">Category (Optional)</label>
                            <input type="text" wire:model="category" class="w-full px-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-md text-xs text-zinc-700 focus:outline-none focus:bg-white focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 transition-colors" placeholder="e.g. Programming, Language, Soft Skill">
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-zinc-150/60">
                        <button type="button" wire:click="closeModal" class="px-3.5 py-1.5 text-xs font-bold text-zinc-600 bg-zinc-100 hover:bg-zinc-200 rounded-md transition-colors focus:outline-none">Cancel</button>
                        <button type="submit" class="px-3.5 py-1.5 text-xs font-bold bg-primary-50 text-zinc-800 border border-primary-200/60 hover:bg-primary-100 rounded-md transition-colors shadow-3xs focus:outline-none flex items-center justify-center gap-1.5 active:scale-97">
                            <span wire:loading.remove wire:target="save">{{ $editMode ? 'Update' : 'Save Skill' }}</span>
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
