<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 shadow-inner">
                <i class="ph-duotone ph-star text-2xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Skills</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mt-1.5">Your core competencies</p>
            </div>
        </div>
        <button wire:click="openModal" 
                class="w-full md:w-auto px-6 py-3 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-[2px] hover:bg-slate-800 transition-all shadow-xl shadow-slate-200 flex items-center justify-center gap-2 active:scale-95">
            <i class="ph-bold ph-plus text-base"></i>
            ADD SKILL
        </button>
    </div>

    <!-- Skills List -->
    @if($skills->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($skills as $skill)
                <div class="bg-white border border-slate-200/60 rounded-3xl p-5 hover:border-indigo-600/30 transition-all group relative overflow-hidden">
                    <div class="flex items-center justify-between gap-4 mb-4">
                        <h4 class="text-base font-black text-slate-900 tracking-tight truncate">{{ $skill->name }}</h4>
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-all">
                            <button wire:click="edit({{ $skill->id }})" class="w-8 h-8 flex items-center justify-center bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition-all">
                                <i class="ph-bold ph-pencil-simple text-sm"></i>
                            </button>
                            <button wire:click="delete({{ $skill->id }})" wire:confirm="Delete this skill?" class="w-8 h-8 flex items-center justify-center bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-600 hover:text-white transition-all">
                                <i class="ph-bold ph-trash text-sm"></i>
                            </button>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest bg-indigo-50 px-2 py-0.5 rounded-lg">{{ $skill->level }}</span>
                            @if($skill->category)
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $skill->category }}</span>
                            @endif
                        </div>
                        <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                            @php
                                $width = match($skill->level) {
                                    'Beginner' => '25%',
                                    'Intermediate' => '50%',
                                    'Advanced' => '75%',
                                    'Expert' => '100%',
                                    default => '50%'
                                };
                            @endphp
                            <div class="bg-indigo-600 h-full rounded-full" style="width: {{ $width }}"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-slate-50/50 border-2 border-dashed border-slate-200 rounded-[2rem] p-12 text-center transition-all hover:bg-slate-50 group">
            <div class="w-16 h-16 bg-white rounded-3xl flex items-center justify-center text-slate-300 mx-auto mb-6 shadow-sm group-hover:scale-110 transition-transform">
                <i class="ph-duotone ph-star text-4xl"></i>
            </div>
            <p class="text-sm font-bold text-slate-500 mb-4">No skills added yet</p>
            <button wire:click="openModal" class="text-xs font-black text-indigo-600 uppercase tracking-widest hover:text-indigo-700">
                ADD YOUR FIRST SKILL
            </button>
        </div>
    @endif

    @teleport('body')
    <div class="fixed inset-0 z-[9999] bg-slate-900/60 backdrop-blur-xl overflow-y-auto {{ !$showModal ? 'hidden' : '' }}">
            <div class="flex min-h-full items-center justify-center p-4" wire:click.self="closeModal">
                <div class="relative bg-white rounded-[2rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.25)] max-w-md w-full border border-slate-100 overflow-hidden">
                <div class="bg-white px-6 py-5 flex justify-between items-center border-b border-slate-100 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center">
                            <i class="ph-fill ph-star text-indigo-600 text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900 tracking-tight">{{ $editMode ? 'Edit Skill' : 'Add Skill' }}</h3>
                            <p class="text-slate-400 text-[9px] font-bold uppercase tracking-widest mt-0.5">Core Competencies</p>
                        </div>
                    </div>
                    <button wire:click="closeModal" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-50 transition-all text-slate-400 hover:text-slate-900">
                        <i class="ph-bold ph-x text-base"></i>
                    </button>
                </div>
                <form wire:submit.prevent="save" class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Skill Name</label>
                            <div class="relative">
                                <input type="text" wire:model="name" class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-all" placeholder="e.g. Laravel, UI Design, Marketing">
                                <i class="ph ph-lightning absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            </div>
                            @error('name') <p class="text-rose-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Proficiency Level</label>
                            <select wire:model="level" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-all">
                                <option value="">Select Level</option>
                                <option value="Beginner">Beginner</option>
                                <option value="Intermediate">Intermediate</option>
                                <option value="Advanced">Advanced</option>
                                <option value="Expert">Expert</option>
                            </select>
                            @error('level') <p class="text-rose-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Category (Optional)</label>
                            <input type="text" wire:model="category" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-all" placeholder="e.g. Programming, Language, Soft Skill">
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-5 pt-5 border-t border-slate-100">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all">Cancel</button>
                        <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-all shadow-sm active:scale-95">
                            <span wire:loading.remove wire:target="save">{{ $editMode ? 'Update' : 'Save Skill' }}</span>
                            <span wire:loading wire:target="save" class="flex items-center gap-1.5"><i class="ph-bold ph-spinner animate-spin text-sm"></i> Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endteleport
</div>
