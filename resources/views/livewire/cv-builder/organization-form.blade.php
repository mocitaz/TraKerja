<div class="cv-entry-form space-y-5">
    <!-- Header -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 bg-zinc-50 border border-zinc-200/50 rounded flex items-center justify-center text-zinc-500 shrink-0 shadow-3xs">
                <i class="ph ph-users-three text-base"></i>
            </div>
            <div>
                <h3 class="text-[13px] font-bold text-zinc-800 tracking-tight uppercase tracking-wider">Organizations</h3>
                <p class="text-[11px] text-zinc-400 font-medium leading-none mt-0.5">Clubs, communities, or volunteer work</p>
            </div>
        </div>
        <button wire:click="openModal"
                class="w-full md:w-auto px-4 py-1.5 bg-primary-50 text-zinc-800 border border-primary-200/60 rounded-md font-bold text-[11px] uppercase tracking-wider hover:bg-primary-100 transition-colors shadow-3xs flex items-center justify-center gap-1.5 focus:outline-none shrink-0">
            <i class="ph ph-plus text-[13px]"></i>
            Add Organization
        </button>
    </div>

    <!-- Organization List -->
    @if($organizations->count() > 0)
        <div class="grid grid-cols-1 gap-3">
            @foreach($organizations as $org)
                <div class="bg-white border border-zinc-200/60 rounded-lg p-4 hover:border-zinc-350 transition-colors group flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-3xs">
                    <div class="flex items-start gap-3 min-w-0">
                        <div class="w-9 h-9 bg-zinc-50 border border-zinc-200/50 rounded flex items-center justify-center text-zinc-400 group-hover:bg-zinc-100 group-hover:text-zinc-700 transition-all shrink-0">
                            <i class="ph ph-hand-heart text-base"></i>
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-[13px] font-bold text-zinc-800 tracking-tight truncate leading-none mb-1">{{ $org->organization_name }}</h4>
                            <p class="text-[11px] font-semibold text-zinc-500 mb-1 leading-none">{{ $org->position }}</p>
                            <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-widest mt-1.5 block leading-none">
                                {{ $org->start_date?->format('M Y') }} - {{ $org->is_current ? 'PRESENT' : $org->end_date?->format('M Y') }}
                            </span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 md:opacity-100 group-hover:opacity-100 transition-opacity">
                        <div class="flex bg-zinc-50 p-0.5 rounded border border-zinc-200/60 shadow-3xs">
                            <button aria-label="Move entry up" wire:click="moveUp({{ $org->id }})" class="w-6 h-6 flex items-center justify-center text-zinc-400 hover:text-zinc-700 rounded transition-colors focus:outline-none">
                                <i class="ph ph-caret-up"></i>
                            </button>
                            <button aria-label="Move entry down" wire:click="moveDown({{ $org->id }})" class="w-6 h-6 flex items-center justify-center text-zinc-400 hover:text-zinc-700 rounded transition-colors focus:outline-none">
                                <i class="ph ph-caret-down"></i>
                            </button>
                        </div>
                        <button aria-label="Edit entry" wire:click="edit({{ $org->id }})" class="w-7 h-7 flex items-center justify-center bg-zinc-50 border border-zinc-200 text-zinc-600 rounded-md hover:bg-zinc-155 transition-colors focus:outline-none">
                            <i class="ph ph-pencil-simple"></i>
                        </button>
                        <button aria-label="Delete entry" wire:click="confirmDelete({{ $org->id }})" class="w-7 h-7 flex items-center justify-center bg-rose-50 border border-rose-100/50 text-rose-600 rounded-md hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-colors focus:outline-none">
                            <i class="ph ph-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-zinc-50/15 border border-dashed border-zinc-200 rounded-lg p-10 text-center transition-colors hover:bg-zinc-50/30 group">
            <div class="w-10 h-10 bg-white border border-zinc-200 rounded-md flex items-center justify-center text-zinc-400 mx-auto mb-3 shadow-3xs group-hover:scale-105 transition-transform">
                <i class="ph ph-users-three text-xl"></i>
            </div>
            <p class="text-[11px] font-bold text-zinc-500 mb-3">No organizations added yet</p>
            <button wire:click="openModal" class="text-[11px] font-black text-primary-600 /* [BRAND_PRIMARY] */ uppercase tracking-widest hover:text-primary-700">
                ADD YOUR FIRST ORGANIZATION
            </button>
        </div>
    @endif

    <!-- Clean Modal (Tracker & Dashboard Style) -->
    @teleport('body')
    <div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showModal" data-dialog-close="$wire.closeModal()" aria-label="Edit CV organization" class="cv-entry-dialog {{ !$showModal ? 'hidden' : '' }}" wire:click.self="closeModal">
        <div class="cv-dialog-panel" @click.stop>
            <header class="cv-dialog-header">
                <h2>{{ $editMode ? 'Edit Organization' : 'Add Organization' }}</h2>
                <button type="button" aria-label="Close modal" wire:click="closeModal" class="cv-dialog-close">
                    <i class="ph ph-x" aria-hidden="true"></i>
                </button>
            </header>

            <form wire:submit.prevent="save">
                <div class="cv-dialog-body">
                    <div class="cv-dialog-grid">
                        <div class="cv-dialog-field cv-dialog-col-full">
                            <label for="org-name">Organization name</label>
                            <input id="org-name" type="text" wire:model="organization_name" placeholder="e.g. Student Council, Red Cross">
                            @error('organization_name') <p class="cv-dialog-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="cv-dialog-field cv-dialog-col-full">
                            <label for="org-role">Role</label>
                            <input id="org-role" type="text" wire:model="position" placeholder="e.g. President, Volunteer">
                            @error('position') <p class="cv-dialog-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="cv-dialog-field">
                            <label for="org-start-date">Start date</label>
                            <input id="org-start-date" type="date" wire:model="start_date">
                            @error('start_date') <p class="cv-dialog-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="cv-dialog-field">
                            <label for="org-end-date">End date</label>
                            <input id="org-end-date" type="date" wire:model="end_date" :disabled="$wire.is_current" class="disabled:opacity-40">
                        </div>

                        <div class="cv-dialog-col-full">
                            <label class="cv-dialog-checkbox-card">
                                <input type="checkbox" wire:model="is_current">
                                <span>I am currently active here</span>
                            </label>
                        </div>

                        <div class="cv-dialog-field cv-dialog-col-full">
                            <label for="org-description">Description (Optional)</label>
                            <textarea id="org-description" wire:model="description" rows="3" placeholder="Describe your responsibilities and impact..."></textarea>
                        </div>
                    </div>
                </div>

                <footer class="cv-dialog-footer">
                    <button type="button" wire:click="closeModal" class="cv-dialog-btn-cancel">Cancel</button>
                    <button type="submit" class="cv-dialog-btn-submit" wire:loading.attr="disabled" wire:target="save">
                        <span wire:loading.remove wire:target="save">{{ $editMode ? 'Update' : 'Save Organization' }}</span>
                        <span wire:loading wire:target="save" class="flex items-center gap-1.5"><i class="ph ph-spinner animate-spin"></i> Saving...</span>
                    </button>
                </footer>
            </form>
        </div>
    </div>
    @endteleport

    {{-- Delete Confirmation Modal --}}
    @teleport('body')
    <div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showDeleteConfirm" data-dialog-close="$wire.cancelDelete()" aria-label="Delete CV organization" class="cv-entry-dialog {{ !$showDeleteConfirm ? 'hidden' : '' }}" wire:click.self="cancelDelete">
        <div class="cv-delete-panel" @click.stop>
            <div class="cv-delete-icon">
                <i class="ph ph-trash" aria-hidden="true"></i>
            </div>
            <h3>Confirm Delete?</h3>
            <p>Are you sure you want to delete this organization record? This action cannot be undone.</p>
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
