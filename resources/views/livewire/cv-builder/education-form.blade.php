<div class="cv-entry-form space-y-5">
    <!-- Header -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 bg-zinc-50 border border-zinc-200/50 rounded flex items-center justify-center text-zinc-500 shrink-0 shadow-3xs">
                <i class="ph ph-graduation-cap text-base"></i>
            </div>
            <div>
                <h3 class="text-[13px] font-bold text-zinc-800 tracking-tight uppercase tracking-wider">{{ __('Education') }}</h3>
                <p class="text-[11px] text-zinc-400 font-medium leading-none mt-0.5">{{ __('Your academic background') }}</p>
            </div>
        </div>
        <button wire:click="openModal"
                class="w-full md:w-auto px-4 py-1.5 bg-primary-50 text-zinc-800 border border-primary-200/60 rounded-md font-bold text-[11px] uppercase tracking-wider hover:bg-primary-100 transition-colors shadow-3xs flex items-center justify-center gap-1.5 focus:outline-none shrink-0">
            <i class="ph ph-plus text-[13px]"></i>
            {{ __('Add Education') }}
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
                            <h4 class="text-[13px] font-bold text-zinc-800 tracking-tight truncate leading-none mb-1">{{ $education->institution_name }}</h4>
                            <p class="text-[11px] font-semibold text-zinc-500 mb-1 leading-none">{{ __(':value1 in :value2', ['value1' => ($education->degree), 'value2' => ($education->major)]) }}</p>
                            <div class="flex flex-wrap items-center gap-x-2 gap-y-1 mt-1.5 leading-none">
                                <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-widest">
                                    {{ $education->start_date?->format('Y') }} - {{ $education->is_current ? __('PRESENT') : $education->end_date?->format('Y') }}
                                </span>
                                @if($education->gpa)
                                    <span class="text-[11px] font-black text-emerald-600 uppercase tracking-wider bg-emerald-50 px-1.5 py-0.5 rounded">{{ __('GPA: :value1', ['value1' => ($education->gpa)]) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 md:opacity-100 group-hover:opacity-100 transition-opacity">
                        <div class="flex bg-zinc-50 p-0.5 rounded border border-zinc-200/60 shadow-3xs">
                            <button aria-label="{{ __('Move entry up') }}" wire:click="moveUp({{ $education->id }})" class="w-6 h-6 flex items-center justify-center text-zinc-400 hover:text-zinc-700 rounded transition-colors focus:outline-none">
                                <i class="ph ph-caret-up"></i>
                            </button>
                            <button aria-label="{{ __('Move entry down') }}" wire:click="moveDown({{ $education->id }})" class="w-6 h-6 flex items-center justify-center text-zinc-400 hover:text-zinc-700 rounded transition-colors focus:outline-none">
                                <i class="ph ph-caret-down"></i>
                            </button>
                        </div>
                        <button aria-label="{{ __('Edit entry') }}" wire:click="edit({{ $education->id }})" class="w-7 h-7 flex items-center justify-center bg-zinc-50 border border-zinc-200 text-zinc-600 rounded-md hover:bg-zinc-155 transition-colors focus:outline-none">
                            <i class="ph ph-pencil-simple"></i>
                        </button>
                        <button aria-label="{{ __('Delete entry') }}" wire:click="confirmDelete({{ $education->id }})" class="w-7 h-7 flex items-center justify-center bg-rose-50 border border-rose-100/50 text-rose-600 rounded-md hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-colors focus:outline-none">
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
            <p class="text-[11px] font-bold text-zinc-500 mb-3">{{ __('No education added yet') }}</p>
            <button wire:click="openModal" class="text-[11px] font-black text-primary-600 /* [BRAND_PRIMARY] */ uppercase tracking-widest hover:text-primary-700">
                {{ __('ADD YOUR FIRST EDUCATION') }}
            </button>
        </div>
    @endif

    <!-- Clean Modal (Tracker & Dashboard Style) -->
    @teleport('body')
    <div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showModal" data-dialog-close="$wire.closeModal()" aria-label="{{ __('Edit CV education') }}" class="cv-entry-dialog {{ !$showModal ? 'hidden' : '' }}" wire:click.self="closeModal">
        <div class="cv-dialog-panel" @click.stop>
            <header class="cv-dialog-header">
                <h2>{{ $editMode ? __('Edit Education') : __('Add Education') }}</h2>
                <button type="button" aria-label="{{ __('Close modal') }}" wire:click="closeModal" class="cv-dialog-close">
                    <i class="ph ph-x" aria-hidden="true"></i>
                </button>
            </header>

            <form wire:submit.prevent="save">
                <div class="cv-dialog-body">
                    <div class="cv-dialog-grid">
                        <div class="cv-dialog-field cv-dialog-col-full">
                            <label for="edu-inst-name">{{ __('School / University') }}</label>
                            <input id="edu-inst-name" type="text" wire:model="institution_name" placeholder="{{ __('e.g. University of Indonesia') }}">
                            @error('institution_name') <p class="cv-dialog-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="cv-dialog-field">
                            <label for="edu-degree">{{ __('Degree') }}</label>
                            <input id="edu-degree" type="text" wire:model="degree" placeholder="{{ __('e.g. Bachelor\'s Degree') }}">
                            @error('degree') <p class="cv-dialog-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="cv-dialog-field">
                            <label for="edu-major">{{ __('Field of study') }}</label>
                            <input id="edu-major" type="text" wire:model="major" placeholder="{{ __('e.g. Computer Science') }}">
                            @error('major') <p class="cv-dialog-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="cv-dialog-field">
                            <label for="edu-start-date">{{ __('Start date') }}</label>
                            <input id="edu-start-date" type="date" wire:model="start_date">
                            @error('start_date') <p class="cv-dialog-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="cv-dialog-field">
                            <label for="edu-end-date">{{ __('End date') }}</label>
                            <input id="edu-end-date" type="date" wire:model="end_date" :disabled="$wire.is_current" class="disabled:opacity-40">
                        </div>

                        <div class="cv-dialog-field cv-dialog-col-full">
                            <label for="edu-gpa">{{ __('GPA (Optional)') }}</label>
                            <input id="edu-gpa" type="text" wire:model="gpa" placeholder="{{ __('e.g. 3.8/4.0') }}">
                        </div>

                        <div class="cv-dialog-col-full">
                            <label class="cv-dialog-checkbox-card">
                                <input type="checkbox" wire:model="is_current">
                                <span>{{ __('I currently study here') }}</span>
                            </label>
                        </div>

                        <div class="cv-dialog-field cv-dialog-col-full">
                            <label for="edu-description">{{ __('Description (Optional)') }}</label>
                            <textarea id="edu-description" wire:model="description" rows="3" placeholder="{{ __('Relevant coursework, honors, or activities...') }}"></textarea>
                        </div>
                    </div>
                </div>

                <footer class="cv-dialog-footer">
                    <button type="button" wire:click="closeModal" class="cv-dialog-btn-cancel">{{ __('Cancel') }}</button>
                    <button type="submit" class="cv-dialog-btn-submit" wire:loading.attr="disabled" wire:target="save">
                        <span wire:loading.remove wire:target="save">{{ $editMode ? __('Update') : __('Save Education') }}</span>
                        <span wire:loading wire:target="save" class="flex items-center gap-1.5"><i class="ph ph-spinner animate-spin"></i> {{ __('Saving...') }}</span>
                    </button>
                </footer>
            </form>
        </div>
    </div>
    @endteleport

    {{-- Delete Confirmation Modal --}}
    @teleport('body')
    <div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showDeleteConfirm" data-dialog-close="$wire.cancelDelete()" aria-label="{{ __('Delete CV education') }}" class="cv-entry-dialog {{ !$showDeleteConfirm ? 'hidden' : '' }}" wire:click.self="cancelDelete">
        <div class="cv-delete-panel" @click.stop>
            <div class="cv-delete-icon">
                <i class="ph ph-trash" aria-hidden="true"></i>
            </div>
            <h3>{{ __('Confirm Delete?') }}</h3>
            <p>{{ __('Are you sure you want to delete this education record? This action cannot be undone.') }}</p>
            <div class="cv-delete-actions">
                <button type="button" wire:click="cancelDelete" class="cv-dialog-btn-cancel">{{ __('Cancel') }}</button>
                <button type="button" wire:click="delete" class="cv-dialog-btn-delete">
                    <i class="ph ph-trash" aria-hidden="true"></i> {{ __('Delete') }}
                </button>
            </div>
        </div>
    </div>
    @endteleport
</div>
