<div class="cv-entry-form space-y-5">
    <!-- Header -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 bg-zinc-50 border border-zinc-200/50 rounded flex items-center justify-center text-zinc-500 shrink-0 shadow-3xs">
                <i class="ph ph-star text-base"></i>
            </div>
            <div>
                <h3 class="text-[13px] font-bold text-zinc-800 tracking-tight uppercase tracking-wider">Skills</h3>
                <p class="text-[11px] text-zinc-400 font-medium leading-none mt-0.5">Your core competencies</p>
            </div>
        </div>
        <button wire:click="openModal"
                class="w-full md:w-auto px-4 py-1.5 bg-primary-50 text-zinc-800 border border-primary-200/60 rounded-md font-bold text-[11px] uppercase tracking-wider hover:bg-primary-100 transition-colors shadow-3xs flex items-center justify-center gap-1.5 focus:outline-none shrink-0">
            <i class="ph ph-plus text-[13px]"></i>
            Add Skill
        </button>
    </div>

    <!-- Skills List -->
    @if($skills->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($skills as $skill)
                <div class="bg-white border border-zinc-200/60 rounded-lg p-3.5 hover:border-zinc-350 transition-colors group relative overflow-hidden shadow-3xs">
                    <div class="flex items-center justify-between gap-3 mb-3">
                        <h4 class="text-[13px] font-bold text-zinc-800 tracking-tight truncate leading-none">{{ $skill->skill_name }}</h4>
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button aria-label="Edit entry" wire:click="edit({{ $skill->id }})" class="w-6 h-6 flex items-center justify-center bg-zinc-50 border border-zinc-200 text-zinc-650 rounded hover:bg-zinc-150 transition-colors focus:outline-none">
                                <i class="ph ph-pencil-simple text-[13px]"></i>
                            </button>
                            <button aria-label="Delete entry" wire:click="confirmDelete({{ $skill->id }})" class="w-6 h-6 flex items-center justify-center bg-rose-50 border border-rose-100/50 text-rose-600 rounded hover:bg-rose-600 hover:text-white transition-colors focus:outline-none">
                                <i class="ph ph-trash text-[13px]"></i>
                            </button>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center leading-none">
                            <span class="text-[11px] font-black text-primary-650 /* [BRAND_PRIMARY] */ uppercase tracking-wider bg-primary-50 px-1.5 py-0.5 rounded">{{ $skill->proficiency }}</span>
                            @if($skill->category)
                                <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-widest">{{ $skill->category }}</span>
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
            <button wire:click="openModal" class="text-[11px] font-black text-primary-600 /* [BRAND_PRIMARY] */ uppercase tracking-widest hover:text-primary-700">
                ADD YOUR FIRST SKILL
            </button>
        </div>
    @endif

    <!-- Clean Modal (Tracker & Dashboard Style) -->
    @teleport('body')
    <div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showModal" data-dialog-close="$wire.closeModal()" aria-label="Edit CV skills" class="cv-entry-dialog {{ !$showModal ? 'hidden' : '' }}" wire:click.self="closeModal">
        <div class="cv-dialog-panel cv-dialog-panel--sm" @click.stop>
            <header class="cv-dialog-header">
                <h2>{{ $editMode ? 'Edit Skill' : 'Add Skill' }}</h2>
                <button type="button" aria-label="Close modal" wire:click="closeModal" class="cv-dialog-close">
                    <i class="ph ph-x" aria-hidden="true"></i>
                </button>
            </header>

            <form wire:submit.prevent="save">
                <div class="cv-dialog-body">
                    <div class="cv-dialog-field">
                        <label for="skill-name">Skill name</label>
                        <input id="skill-name" type="text" wire:model="skill_name" placeholder="e.g. Laravel, UI Design, Marketing">
                        @error('skill_name') <p class="cv-dialog-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="cv-dialog-field">
                        <label for="skill-proficiency">Proficiency level</label>
                        <select id="skill-proficiency" wire:model="proficiency">
                            <option value="">Select level</option>
                            <option value="Beginner">Beginner</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Advanced">Advanced</option>
                            <option value="Expert">Expert</option>
                        </select>
                        @error('proficiency') <p class="cv-dialog-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="cv-dialog-field">
                        <label for="skill-category">Category (Optional)</label>
                        <input id="skill-category" type="text" wire:model="category" placeholder="e.g. Programming, Language, Soft Skill">
                    </div>
                </div>

                <footer class="cv-dialog-footer">
                    <button type="button" wire:click="closeModal" class="cv-dialog-btn-cancel">Cancel</button>
                    <button type="submit" class="cv-dialog-btn-submit" wire:loading.attr="disabled" wire:target="save">
                        <span wire:loading.remove wire:target="save">{{ $editMode ? 'Update' : 'Save Skill' }}</span>
                        <span wire:loading wire:target="save" class="flex items-center gap-1.5"><i class="ph ph-spinner animate-spin"></i> Saving...</span>
                    </button>
                </footer>
            </form>
        </div>
    </div>
    @endteleport

    {{-- Delete Confirmation Modal --}}
    @teleport('body')
    <div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showDeleteConfirm" data-dialog-close="$wire.cancelDelete()" aria-label="Delete CV skills" class="cv-entry-dialog {{ !$showDeleteConfirm ? 'hidden' : '' }}" wire:click.self="cancelDelete">
        <div class="cv-delete-panel" @click.stop>
            <div class="cv-delete-icon">
                <i class="ph ph-trash" aria-hidden="true"></i>
            </div>
            <h3>Confirm Delete?</h3>
            <p>Are you sure you want to delete this skill? This action cannot be undone.</p>
            <div class="cv-delete-actions">
                <button type="button" wire:click="cancelDelete" class="cv-dialog-btn-cancel">Cancel</button>
                <button type="button" wire:click="delete" class="cv-dialog-btn-delete">
                    <i class="ph ph-trash" aria-hidden="true"></i> Delete
                </button>
            </div>
        </div>
    </div>
    @endteleport

</div>
