<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-primary-50 rounded-2xl flex items-center justify-center text-primary-600 shadow-inner">
                <i class="ph-duotone ph-users-three text-2xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Organizations</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mt-1.5">Clubs, communities, or volunteer work</p>
            </div>
        </div>
        <button wire:click="openModal" 
                class="w-full md:w-auto px-6 py-3 bg-primary-600 text-white rounded-2xl font-black text-xs uppercase tracking-[2px] hover:bg-primary-700 transition-all shadow-xl shadow-primary-100 flex items-center justify-center gap-2 active:scale-95">
            <i class="ph-bold ph-plus text-base"></i>
            ADD ORGANIZATION
        </button>
    </div>

    <!-- Organization List -->
    @if($organizations->count() > 0)
        <div class="grid grid-cols-1 gap-4">
            @foreach($organizations as $org)
                <div class="bg-white border border-slate-200/60 rounded-3xl p-5 hover:border-primary-600/30 transition-all group flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-start gap-5 min-w-0">
                        <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 transition-all shrink-0">
                            <i class="ph-bold ph-hand-heart text-xl"></i>
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-base font-black text-slate-900 tracking-tight truncate">{{ $org->organization_name }}</h4>
                            <p class="text-sm font-bold text-slate-500 mb-1">{{ $org->position }}</p>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                {{ $org->start_date?->format('M Y') }} - {{ $org->is_current ? 'PRESENT' : $org->end_date?->format('M Y') }}
                            </span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 md:opacity-0 group-hover:opacity-100 transition-all">
                        <div class="flex bg-slate-50 p-1 rounded-xl border border-slate-100">
                            <button wire:click="moveUp({{ $org->id }})" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-primary-600 rounded-lg transition-all">
                                <i class="ph-bold ph-caret-up"></i>
                            </button>
                            <button wire:click="moveDown({{ $org->id }})" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-primary-600 rounded-lg transition-all">
                                <i class="ph-bold ph-caret-down"></i>
                            </button>
                        </div>
                        <button wire:click="edit({{ $org->id }})" class="w-10 h-10 flex items-center justify-center bg-primary-50 text-primary-600 rounded-xl hover:bg-primary-600 hover:text-white transition-all">
                            <i class="ph-bold ph-pencil-simple"></i>
                        </button>
                        <button wire:click="delete({{ $org->id }})" wire:confirm="Delete this organization?" class="w-10 h-10 flex items-center justify-center bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition-all">
                            <i class="ph-bold ph-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-slate-50/50 border-2 border-dashed border-slate-200 rounded-[2rem] p-12 text-center transition-all hover:bg-slate-50 group">
            <div class="w-16 h-16 bg-white rounded-3xl flex items-center justify-center text-slate-300 mx-auto mb-6 shadow-sm group-hover:scale-110 transition-transform">
                <i class="ph-duotone ph-users-three text-4xl"></i>
            </div>
            <p class="text-sm font-bold text-slate-500 mb-4">No organizations added yet</p>
            <button wire:click="openModal" class="text-xs font-black text-primary-600 uppercase tracking-widest hover:text-primary-700">
                ADD YOUR FIRST ORGANIZATION
            </button>
        </div>
    @endif

    @teleport('body')
    <div class="fixed inset-0 z-[9999] bg-slate-900/60 backdrop-blur-xl overflow-y-auto {{ !$showModal ? 'hidden' : '' }}">
            <div class="flex min-h-full items-center justify-center p-4" wire:click.self="closeModal">
                <div class="relative bg-white rounded-[2rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.25)] max-w-lg w-full border border-slate-100 overflow-hidden">
                <div class="bg-white px-6 py-5 flex justify-between items-center border-b border-slate-100 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-primary-50 border border-primary-100 flex items-center justify-center">
                            <i class="ph-fill ph-users-three text-primary-600 text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900 tracking-tight">{{ $editMode ? 'Edit Organization' : 'Add Organization' }}</h3>
                            <p class="text-slate-400 text-[9px] font-bold uppercase tracking-widest mt-0.5">Clubs & Communities</p>
                        </div>
                    </div>
                    <button wire:click="closeModal" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-50 transition-all text-slate-400 hover:text-slate-900">
                        <i class="ph-bold ph-x text-base"></i>
                    </button>
                </div>
                <form wire:submit.prevent="save" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Organization Name</label>
                            <div class="relative">
                                <input type="text" wire:model="organization_name" class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-400 transition-all" placeholder="e.g. Student Council, Red Cross">
                                <i class="ph ph-users absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            </div>
                            @error('organization_name') <p class="text-rose-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Role</label>
                            <div class="relative">
                                <input type="text" wire:model="position" class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-400 transition-all" placeholder="e.g. President, Volunteer">
                                <i class="ph ph-user-focus absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            </div>
                            @error('position') <p class="text-rose-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Start Date</label>
                            <input type="date" wire:model="start_date" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-400 transition-all">
                            @error('start_date') <p class="text-rose-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">End Date</label>
                            <input type="date" wire:model="end_date" :disabled="$wire.is_current" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-400 transition-all disabled:opacity-40">
                        </div>
                        <div class="md:col-span-2">
                            <label class="flex items-center gap-2.5 p-3 bg-primary-50/60 border border-primary-100 rounded-xl cursor-pointer hover:bg-primary-50 transition-all">
                                <input type="checkbox" wire:model="is_current" class="w-4 h-4 rounded text-primary-600 focus:ring-primary-500 border-slate-300">
                                <span class="text-sm font-semibold text-slate-700">I am currently active here</span>
                            </label>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Description (Optional)</label>
                            <textarea wire:model="description" rows="3" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-400 transition-all leading-relaxed" placeholder="Describe your responsibilities and impact..."></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-5 pt-5 border-t border-slate-100">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all">Cancel</button>
                        <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-xl transition-all shadow-sm active:scale-95">
                            <span wire:loading.remove wire:target="save">{{ $editMode ? 'Update' : 'Save Organization' }}</span>
                            <span wire:loading wire:target="save" class="flex items-center gap-1.5"><i class="ph-bold ph-spinner animate-spin text-sm"></i> Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endteleport
</div>
