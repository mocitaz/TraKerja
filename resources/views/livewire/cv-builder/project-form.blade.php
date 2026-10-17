<div class="cv-entry-form space-y-5">
    <!-- Header -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 bg-zinc-50 border border-zinc-200/50 rounded flex items-center justify-center text-zinc-500 shrink-0 shadow-3xs">
                <i class="ph ph-code text-base"></i>
            </div>
            <div>
                <h3 class="text-[13px] font-bold text-zinc-800 tracking-tight uppercase tracking-wider">{{ __('Projects') }}</h3>
                <p class="text-[11px] text-zinc-400 font-medium leading-none mt-0.5">{{ __('Personal or professional projects') }}</p>
            </div>
        </div>
        <button wire:click="openModal"
                class="w-full md:w-auto px-4 py-1.5 bg-primary-50 text-zinc-800 border border-primary-200/60 rounded-md font-bold text-[11px] uppercase tracking-wider hover:bg-primary-100 transition-colors shadow-3xs flex items-center justify-center gap-1.5 focus:outline-none shrink-0">
            <i class="ph ph-plus text-[13px]"></i>
            {{ __('Add Project') }}
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
                            <h4 class="text-[13px] font-bold text-zinc-800 tracking-tight truncate leading-none mb-1">{{ $project->project_name }}</h4>
                            <p class="text-[11px] font-semibold text-zinc-500 mb-1 leading-none">{{ $project->role }}</p>
                            <div class="flex flex-wrap items-center gap-x-2 gap-y-1 mt-1.5 leading-none">
                                <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-widest">
                                    {{ $project->start_date?->locale(app()->getLocale())->translatedFormat('M Y') }} - {{ $project->is_ongoing ? __('PRESENT') : $project->end_date?->locale(app()->getLocale())->translatedFormat('M Y') }}
                                </span>
                                @if($project->project_url)
                                    <a href="{{ $project->project_url }}" target="_blank" class="text-[11px] font-black text-primary-600 /* [BRAND_PRIMARY] */ uppercase tracking-wider flex items-center gap-1 hover:underline">
                                        <i class="ph ph-link"></i> {{ __('VIEW PROJECT') }}
                                    </a>
                                @endif
                            </div>
                            @if($project->technologies && is_array($project->technologies))
                                <div class="flex flex-wrap gap-1.5 mt-2.5">
                                    @foreach($project->technologies as $tech)
                                        <span class="px-1.5 py-0.5 bg-zinc-50 text-zinc-500 text-[11px] font-bold rounded border border-zinc-100 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:border-primary-100 transition-colors">
                                            {{ $tech }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 md:opacity-100 group-hover:opacity-100 transition-opacity">
                        <button aria-label="{{ __('Edit entry') }}" wire:click="edit({{ $project->id }})" class="w-7 h-7 flex items-center justify-center bg-zinc-50 border border-zinc-200 text-zinc-600 rounded-md hover:bg-zinc-155 transition-colors focus:outline-none">
                            <i class="ph ph-pencil-simple"></i>
                        </button>
                        <button aria-label="{{ __('Delete entry') }}" wire:click="confirmDelete({{ $project->id }})" class="w-7 h-7 flex items-center justify-center bg-rose-50 border border-rose-100/50 text-rose-600 rounded-md hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-colors focus:outline-none">
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
            <p class="text-[11px] font-bold text-zinc-500 mb-3">{{ __('No projects added yet') }}</p>
            <button wire:click="openModal" class="text-[11px] font-black text-primary-600 /* [BRAND_PRIMARY] */ uppercase tracking-widest hover:text-primary-700">
                {{ __('ADD YOUR FIRST PROJECT') }}
            </button>
        </div>
    @endif

    <!-- Clean Modal (Tracker & Dashboard Style) -->
    @teleport('body')
    <div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showModal" data-dialog-close="$wire.closeModal()" aria-label="{{ __('Edit CV project') }}" class="cv-entry-dialog {{ !$showModal ? 'hidden' : '' }}" wire:click.self="closeModal">
        <div class="cv-dialog-panel" @click.stop>
            <header class="cv-dialog-header">
                <h2>{{ $editMode ? __('Edit Project') : __('Add Project') }}</h2>
                <button type="button" aria-label="{{ __('Close modal') }}" wire:click="closeModal" class="cv-dialog-close">
                    <i class="ph ph-x" aria-hidden="true"></i>
                </button>
            </header>

            <form wire:submit.prevent="save">
                <div class="cv-dialog-body">
                    <div class="cv-dialog-grid">
                        <div class="cv-dialog-field cv-dialog-col-full">
                            <label for="proj-name">{{ __('Project name') }}</label>
                            <input id="proj-name" type="text" wire:model="project_name" placeholder="{{ __('e.g. E-commerce Platform, Mobile App') }}">
                            @error('project_name') <p class="cv-dialog-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="cv-dialog-field cv-dialog-col-full">
                            <label for="proj-role">{{ __('Your role') }}</label>
                            <input id="proj-role" type="text" wire:model="role" placeholder="{{ __('e.g. Full Stack Developer, UI Designer') }}">
                            @error('role') <p class="cv-dialog-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="cv-dialog-field">
                            <label for="proj-start-date">{{ __('Start date') }}</label>
                            <input id="proj-start-date" type="date" wire:model="start_date">
                            @error('start_date') <p class="cv-dialog-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="cv-dialog-field">
                            <label for="proj-end-date">{{ __('End date') }}</label>
                            <input id="proj-end-date" type="date" wire:model="end_date" :disabled="$wire.is_ongoing" class="disabled:opacity-40">
                        </div>

                        <div class="cv-dialog-col-full">
                            <label class="cv-dialog-checkbox-card">
                                <input type="checkbox" wire:model="is_ongoing">
                                <span>{{ __('This is an ongoing project') }}</span>
                            </label>
                        </div>

                        <div class="cv-dialog-field cv-dialog-col-full">
                            <label for="proj-url">{{ __('Project URL (Optional)') }}</label>
                            <input id="proj-url" type="url" wire:model="project_url" placeholder="https://github.com/...">
                        </div>

                        <div class="cv-dialog-field cv-dialog-col-full">
                            <label for="proj-technologies">{{ __('Technologies (Optional)') }}</label>
                            <input id="proj-technologies" type="text" wire:model="technologies" placeholder="{{ __('e.g. React, Node.js, Tailwind CSS') }}">
                        </div>

                        <div class="cv-dialog-field cv-dialog-col-full">
                            <label for="proj-description">{{ __('Description (Optional)') }}</label>
                            <textarea id="proj-description" wire:model="description" rows="3" placeholder="{{ __('Describe the project goals and your contributions...') }}"></textarea>
                        </div>
                    </div>
                </div>

                <footer class="cv-dialog-footer">
                    <button type="button" wire:click="closeModal" class="cv-dialog-btn-cancel">{{ __('Cancel') }}</button>
                    <button type="submit" class="cv-dialog-btn-submit" wire:loading.attr="disabled" wire:target="save">
                        <span wire:loading.remove wire:target="save">{{ $editMode ? __('Update') : __('Save Project') }}</span>
                        <span wire:loading wire:target="save" class="flex items-center gap-1.5"><i class="ph ph-spinner animate-spin"></i> {{ __('Saving...') }}</span>
                    </button>
                </footer>
            </form>
        </div>
    </div>
    @endteleport

    {{-- Delete Confirmation Modal --}}
    @teleport('body')
    <div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showDeleteConfirm" data-dialog-close="$wire.cancelDelete()" aria-label="{{ __('Delete CV project') }}" class="cv-entry-dialog {{ !$showDeleteConfirm ? 'hidden' : '' }}" wire:click.self="cancelDelete">
        <div class="cv-delete-panel" @click.stop>
            <div class="cv-delete-icon">
                <i class="ph ph-trash" aria-hidden="true"></i>
            </div>
            <h3>{{ __('Confirm Delete?') }}</h3>
            <p>{{ __('Are you sure you want to delete this project? This action cannot be undone.') }}</p>
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
