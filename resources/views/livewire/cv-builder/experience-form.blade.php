<div class="cv-entry-form space-y-5">
    <!-- Header: Compact & Premium -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 bg-zinc-50 border border-zinc-200/50 rounded flex items-center justify-center text-zinc-500 shrink-0 shadow-3xs">
                <i class="ph ph-briefcase text-base"></i>
            </div>
            <div>
                <h3 class="text-[13px] font-bold text-zinc-800 tracking-tight uppercase tracking-wider">Work Experience</h3>
                <p class="text-[11px] text-zinc-400 font-medium leading-none mt-0.5">Add your professional work history</p>
            </div>
        </div>
        <button wire:click="openModal"
                class="w-full md:w-auto px-4 py-1.5 bg-primary-50 text-zinc-800 border border-primary-200/60 rounded-md font-bold text-[11px] uppercase tracking-wider hover:bg-primary-100 transition-colors shadow-3xs flex items-center justify-center gap-1.5 focus:outline-none shrink-0">
            <i class="ph ph-plus text-[13px]"></i>
            Add Experience
        </button>
    </div>

    <!-- Experience List: Modern Cards -->
    @if($experiences->count() > 0)
        <div class="grid grid-cols-1 gap-3">
            @foreach($experiences as $experience)
                <div class="bg-white border border-zinc-200/60 rounded-lg p-4 hover:border-zinc-350 transition-colors group flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-3xs">
                    <div class="flex items-start gap-3 min-w-0">
                        <div class="w-9 h-9 bg-zinc-50 border border-zinc-200/50 rounded flex items-center justify-center text-zinc-400 group-hover:bg-zinc-100 group-hover:text-zinc-700 transition-all shrink-0">
                            <i class="ph ph-buildings text-base"></i>
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-[13px] font-bold text-zinc-800 tracking-tight truncate leading-none mb-1">{{ $experience->position }}</h4>
                            <p class="text-[11px] font-semibold text-zinc-500 mb-1 leading-none">{{ $experience->company_name }}</p>
                            <div class="flex flex-wrap items-center gap-x-2 gap-y-1 mt-1.5 leading-none">
                                <span class="text-[11px] font-black text-primary-600 /* [BRAND_PRIMARY] */ uppercase tracking-wider bg-primary-50 px-1.5 py-0.5 rounded">{{ $experience->employment_type }}</span>
                                <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-widest">
                                    {{ $experience->start_date?->format('M Y') }} - {{ $experience->is_current ? 'PRESENT' : $experience->end_date?->format('M Y') }}
                                </span>
                                @if($experience->location)
                                    <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-widest flex items-center gap-1">
                                        <i class="ph ph-map-pin"></i> {{ $experience->location }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 md:opacity-100 group-hover:opacity-100 transition-opacity">
                        <div class="flex bg-zinc-50 p-0.5 rounded border border-zinc-200/60 shadow-3xs">
                            <button aria-label="Move entry up" wire:click="moveUp({{ $experience->id }})" class="w-6 h-6 flex items-center justify-center text-zinc-400 hover:text-zinc-700 rounded transition-colors focus:outline-none">
                                <i class="ph ph-caret-up"></i>
                            </button>
                            <button aria-label="Move entry down" wire:click="moveDown({{ $experience->id }})" class="w-6 h-6 flex items-center justify-center text-zinc-400 hover:text-zinc-700 rounded transition-colors focus:outline-none">
                                <i class="ph ph-caret-down"></i>
                            </button>
                        </div>
                        <button aria-label="Edit entry" wire:click="edit({{ $experience->id }})" class="w-7 h-7 flex items-center justify-center bg-zinc-50 border border-zinc-200 text-zinc-600 rounded-md hover:bg-zinc-150 transition-colors focus:outline-none">
                            <i class="ph ph-pencil-simple"></i>
                        </button>
                        <button aria-label="Delete entry" wire:click="confirmDelete({{ $experience->id }})" class="w-7 h-7 flex items-center justify-center bg-rose-50 border border-rose-100/50 text-rose-600 rounded-md hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-colors focus:outline-none">
                            <i class="ph ph-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-zinc-50/15 border border-dashed border-zinc-200 rounded-lg p-10 text-center transition-colors hover:bg-zinc-50/30 group">
            <div class="w-10 h-10 bg-white border border-zinc-200 rounded-md flex items-center justify-center text-zinc-400 mx-auto mb-3 shadow-3xs group-hover:scale-105 transition-transform">
                <i class="ph ph-briefcase text-xl"></i>
            </div>
            <p class="text-[11px] font-bold text-zinc-500 mb-3">No work experience added yet</p>
            <button wire:click="openModal" class="text-[11px] font-black text-primary-600 /* [BRAND_PRIMARY] */ uppercase tracking-widest hover:text-primary-700">
                ADD YOUR FIRST EXPERIENCE
            </button>
        </div>
    @endif

    <!-- Clean Modal (Tracker & Dashboard Style) -->
    @teleport('body')
    <div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showModal" data-dialog-close="$wire.closeModal()" aria-label="Edit CV experience" class="cv-entry-dialog {{ !$showModal ? 'hidden' : '' }}" wire:click.self="closeModal">
        <div class="cv-dialog-panel" @click.stop>
            <header class="cv-dialog-header">
                <h2>{{ $editMode ? 'Edit Experience' : 'Add Experience' }}</h2>
                <button type="button" aria-label="Close modal" wire:click="closeModal" class="cv-dialog-close">
                    <i class="ph ph-x" aria-hidden="true"></i>
                </button>
            </header>

            <form wire:submit.prevent="save">
                <div class="cv-dialog-body">
                    <div class="cv-dialog-grid">
                        <div class="cv-dialog-field cv-dialog-col-full">
                            <label for="exp-company-name">Company name</label>
                            <input id="exp-company-name" type="text" wire:model="company_name" placeholder="e.g. Google, Microsoft">
                            @error('company_name') <p class="cv-dialog-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="cv-dialog-field cv-dialog-col-full">
                            <label for="exp-position">Job title</label>
                            <input id="exp-position" type="text" wire:model="position" placeholder="e.g. Senior Software Engineer">
                            @error('position') <p class="cv-dialog-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="cv-dialog-field">
                            <label for="exp-emp-type">Employment type</label>
                            <select id="exp-emp-type" wire:model="employment_type">
                                <option value="">Select type</option>
                                @foreach($employmentTypes as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                            @error('employment_type') <p class="cv-dialog-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="cv-dialog-field">
                            <label for="exp-location">Location</label>
                            <input id="exp-location" type="text" wire:model="location" placeholder="e.g. Jakarta, Indonesia">
                        </div>

                        <div class="cv-dialog-field">
                            <label for="exp-start-date">Start date</label>
                            <input id="exp-start-date" type="date" wire:model="start_date">
                            @error('start_date') <p class="cv-dialog-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="cv-dialog-field">
                            <label for="exp-end-date">End date</label>
                            <input id="exp-end-date" type="date" wire:model="end_date" :disabled="$wire.is_current" class="disabled:opacity-40">
                        </div>

                        <div class="cv-dialog-col-full">
                            <label class="cv-dialog-checkbox-card">
                                <input type="checkbox" wire:model="is_current">
                                <span>I currently work here</span>
                            </label>
                        </div>

                        <div class="cv-dialog-field cv-dialog-col-full">
                            <label for="exp-description">Job description</label>
                            <textarea id="exp-description" wire:model="description" rows="3" placeholder="• Describe your responsibilities..."></textarea>
                            <p class="cv-dialog-hint">💡 One bullet point per line</p>
                        </div>
                    </div>
                </div>

                <footer class="cv-dialog-footer">
                    <button type="button" wire:click="closeModal" class="cv-dialog-btn-cancel">Cancel</button>
                    <button type="submit" class="cv-dialog-btn-submit" wire:loading.attr="disabled" wire:target="save">
                        <span wire:loading.remove wire:target="save">{{ $editMode ? 'Update' : 'Save Experience' }}</span>
                        <span wire:loading wire:target="save" class="flex items-center gap-1.5"><i class="ph ph-spinner animate-spin"></i> Saving...</span>
                    </button>
                </footer>
            </form>
        </div>
    </div>
    @endteleport

    {{-- Delete Confirmation Modal --}}
    @teleport('body')
    <div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showDeleteConfirm" data-dialog-close="$wire.cancelDelete()" aria-label="Delete CV experience" class="cv-entry-dialog {{ !$showDeleteConfirm ? 'hidden' : '' }}" wire:click.self="cancelDelete">
        <div class="cv-delete-panel" @click.stop>
            <div class="cv-delete-icon">
                <i class="ph ph-trash" aria-hidden="true"></i>
            </div>
            <h3>Confirm Delete?</h3>
            <p>Are you sure you want to delete this experience? This action cannot be undone.</p>
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
