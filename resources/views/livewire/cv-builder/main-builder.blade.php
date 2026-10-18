<div class="cv-editor-layout" x-data="cvWorkspace(@js(route('cv.import-pdf')), @js(route('cv.import-pdf.confirm')))" @cv-data-updated.window="previewVersion++">
    <aside class="cv-sidebar">
        <section class="cv-sidebar-intro"><p>{{ __('Your CV workspace') }}</p><h2>{{ __('Start with your story.') }}</h2><span>{{ __('Only include sections relevant to you. Experience is optional.') }}</span></section>
        <div class="cv-mobile-section-picker">
            <label for="cv-mobile-section">{{ __('CV sections') }}</label>
            <select id="cv-mobile-section" wire:change="selectSection($event.target.value)">
                <option value="details" @selected($section === 'details')>{{ __('Your details') }}</option>
                @foreach($sections as $item)<option value="{{ $item['key'] }}" @selected($section === $item['key'])>{{ $item['label'] }} ({{ $item['count'] }})</option>@endforeach
            </select>
        </div>
        <nav class="cv-section-nav" aria-label="{{ __('CV sections') }}">
            <button type="button" wire:click="selectSection('details')" @class(['is-selected' => $section === 'details']) @if($section === 'details') aria-current="page" @endif><span>{{ __('Your details') }}</span><span class="cv-section-count">{{ __('Profile') }}</span></button>
            @foreach($sections as $item)
                <button type="button" wire:click="selectSection('{{ $item['key'] }}')" @class(['is-selected' => $section === $item['key']]) @if($section === $item['key']) aria-current="page" @endif><span>{{ $item['label'] }}</span><span class="cv-section-count">{{ $item['count'] }}</span></button>
            @endforeach
        </nav>
        <div class="cv-sidebar-actions">
            <button type="button" @click="previewOpen = true; previewLoading = true">{{ __('Quick preview') }} <i class="ph ph-arrow-up-right" aria-hidden="true"></i></button>
            <button type="button" @click="importModalOpen = true; importError = ''">{{ __('Import an existing PDF') }} <i class="ph ph-file-arrow-up" aria-hidden="true"></i></button>
            <button type="button" @click="$dispatch('openPublishModal')">{{ __('Publish a portfolio site') }} <i class="ph ph-globe" aria-hidden="true"></i></button>
        </div>
        <p class="cv-sidebar-note">{{ __('CV and portfolio use your saved details. Check your contact information before sharing.') }}</p>
    </aside>
    <div class="cv-editor-content">
        <div class="cv-content-note"><span class="cv-content-dot"></span><p>{{ __('Save each section as you go. Your templates use the latest saved content.') }}</p><a href="{{ route('cv.generator') }}" wire:navigate>{{ __('Preview templates') }}</a></div>
        <section class="cv-form-panel" x-show="$wire.section === 'details'">
            <header class="cv-section-heading"><div><h2>{{ __('Your details') }}</h2><p>{{ __('Introduce yourself and give recruiters a way to reach you.') }}</p></div></header>
            <div class="cv-identity-strip"><img src="{{ $user->avatar_url }}" alt="" width="44" height="44"><div><strong>{{ $user->name }}</strong><span>{{ $user->email }}</span></div><a href="{{ route('profile.edit') }}" wire:navigate>{{ __('Edit account') }}</a></div>
            <form wire:submit="saveDetails" class="cv-details-form">
                @php $fields = ['headline' => ['Professional headline', 'e.g. Frontend developer', 'text'], 'phone_number' => ['Phone number', 'Your contact number', 'tel'], 'domicile' => ['Location', 'City, country', 'text'], 'linkedin_url' => ['LinkedIn URL', 'https://linkedin.com/in/your-name', 'url'], 'website_url' => ['Website or portfolio', 'https://your-website.com', 'url']]; @endphp
                <div class="cv-details-grid">
                    @foreach($fields as $field => [$label, $placeholder, $type])
                        <div @class(['cv-detail-field', 'cv-field-wide' => $field === 'headline'])><label for="cv-{{ $field }}">{{ __($label) }}</label><input id="cv-{{ $field }}" type="{{ $type }}" wire:model="{{ $field }}" placeholder="{{ $placeholder }}" maxlength="{{ $field === 'phone_number' ? 20 : 255 }}" aria-invalid="{{ $errors->has($field) ? 'true' : 'false' }}" @error($field) aria-describedby="cv-error-{{ $field }}" @enderror>@error($field)<p id="cv-error-{{ $field }}" class="cv-field-error" role="alert">{{ $message }}</p>@enderror</div>
                    @endforeach
                </div>
                <div class="cv-detail-field"><label for="cv-bio">{{ __('Professional summary') }}</label><p id="cv-bio-help">{{ __('A few sentences about your experience, strengths, and next career step.') }}</p><textarea id="cv-bio" rows="5" maxlength="1000" wire:model="bio" aria-describedby="cv-bio-help{{ $errors->has('bio') ? __(' cv-error-bio') : '' }}" placeholder="{{ __('Write a short introduction in your own words.') }}"></textarea>@error('bio')<p id="cv-error-bio" class="cv-field-error" role="alert">{{ $message }}</p>@enderror</div>
                <footer class="cv-form-footer"><span>{{ __('Optional fields. Changes also update your profile.') }}</span><button type="submit" class="cv-button cv-button-primary" wire:loading.attr="disabled" wire:target="saveDetails"><span wire:loading.remove wire:target="saveDetails">{{ __('Save details') }}</span><span wire:loading wire:target="saveDetails">{{ __('Saving...') }}</span></button></footer>
            </form>
        </section>
        @foreach($sections as $item)
            @php $component = match($item['key']) { 'experiences' => 'experience', 'education' => 'education', 'skills' => 'skills', 'projects' => 'project', 'organizations' => 'organization', 'achievements' => 'achievement' }; @endphp
            <section class="cv-form-panel cv-section-content" x-show="$wire.section === '{{ $item['key'] }}'" x-cloak>
                @livewire('cv-builder.'.$component.'-form', [], key('cv-section-'.$item['key']))
            </section>
        @endforeach
        <section class="cv-next-step"><div><h3>{{ __('Ready to see your draft?') }}</h3><p>{{ __('Choose a template when you have content to review. You can keep editing afterward.') }}</p></div><a href="{{ route('cv.generator') }}" class="cv-button cv-button-secondary" wire:navigate>{{ __('Choose a template') }}</a></section>
    </div>
    <template x-teleport="body">
        <div class="cv-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="cv-quick-title" tabindex="-1" x-dialog="previewOpen" x-show="previewOpen" x-cloak @click.self="previewOpen = false">
            <div class="cv-preview-dialog"><header class="cv-modal-header"><div><h2 id="cv-quick-title">{{ __('Your CV draft') }}</h2><p>{{ __('Minimal template · Saved content') }}</p></div><button type="button" class="cv-close" @click="previewOpen = false" aria-label="{{ __('Close CV preview') }}"><i class="ph ph-x" aria-hidden="true"></i></button></header><div class="cv-preview-canvas"><p x-show="previewLoading" class="cv-preview-loading" role="status">{{ __('Loading your draft...') }}</p><iframe title="{{ __('Your saved CV in the minimal template') }}" :src="previewOpen ? @js(route('cv-builder.preview.get', ['template' => 'minimal', 'canvas' => 1])) + '&v=' + previewVersion : 'about:blank'" @load="previewLoading = false"></iframe></div><footer class="cv-modal-footer"><span>{{ __('Check details before sharing.') }}</span><a href="{{ route('cv.generator') }}" class="cv-button cv-button-primary" wire:navigate>{{ __('Choose template & export') }}</a></footer></div>
        </div>
    </template>
    <template x-teleport="body">
        <div class="cv-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="cv-import-title" tabindex="-1" x-dialog="importModalOpen" x-show="importModalOpen" x-cloak @click.self="if (!importing) importModalOpen = false">
            <div class="cv-import-dialog">
                <header class="cv-modal-header"><div><h2 id="cv-import-title">{{ __('Import your CV') }}</h2><p>{{ __('Preview the extracted content before saving.') }}</p></div><button type="button" class="cv-close" @click="importModalOpen = false" :disabled="importing" aria-label="{{ __('Close CV import') }}"><i class="ph ph-x" aria-hidden="true"></i></button></header>
                <div class="cv-import-body" :aria-busy="importing">
                    <p>{{ __('Nothing changes when you upload a PDF. After you review and confirm, each section below with extracted entries will replace all existing entries in that section. Sections with no extracted entries stay unchanged.') }}</p>
                    <label class="cv-import-dropzone" x-show="!importSuccess" @dragover.prevent @drop.prevent="uploadPdf($event.dataTransfer.files[0])"><strong x-text="importing ? @js(__('Processing your request...')) : @js(__('Choose a PDF or drop it here'))"></strong><span>{{ __('PDF only · Up to 10 MB') }}</span><input type="file" accept="application/pdf,.pdf" :disabled="importing" @change="uploadPdf($event.target.files[0]); $event.target.value = ''"><span x-show="importing" role="status" x-text="importFilename"></span></label>
                    <section x-show="importPreview" class="cv-import-review" aria-labelledby="cv-import-review-title" x-cloak>
                        <h3 id="cv-import-review-title">{{ __('Review proposed replacements') }}</h3>
                        <p>{{ __('Check every field below. Details not found in the PDF remain blank; complete them in CV Builder after importing. If an education start date is missing, enter it below before saving. Close to cancel, or choose another PDF. This preview expires after 15 minutes.') }}</p>
                        <template x-for="section in importSections" :key="section.key">
                            <section class="cv-import-review-section">
                                <h4 x-text="section.label"></h4>
                                <p x-text="section.entries.length ? section.existing + @js(__(' existing entries will be replaced by ')) + section.entries.length + @js(__(' extracted entries.')) : @js(__('No entries extracted. Your ')) + section.existing + @js(__(' existing entries will be kept.'))"></p>
                                <template x-for="(entry, index) in section.entries" :key="index">
                                    <dl class="cv-import-entry">
                                        <template x-for="field in Object.entries(entry).filter(([key]) => key !== 'display_order')" :key="field[0]">
                                            <div><dt x-text="importFieldLabel(field[0])"></dt><dd x-text="typeof field[1] === 'boolean' ? (field[1] ? @js(__('Yes')) : (!entry.start_date && !entry.end_date ? @js(__('Not specified')) : @js(__('No')))) : (field[1] === '' || field[1] == null ? @js(__('Not specified')) : field[1])"></dd></div>
                                        </template>
                                        <template x-if="section.key === 'educations' && !entry.start_date">
                                            <div class="cv-detail-field">
                                                <label :for="'import-education-start-' + index">{{ __('Education start date (required)') }}</label>
                                                <p :id="'import-education-help-' + index" x-text="importPreview?.education_date_hints?.[index] ? @js(__('Date text in PDF: ')) + importPreview.education_date_hints[index] + @js(__('. Enter the full start date from your records.')) : @js(__('No start date was read. Enter it from your records.'))"></p>
                                                <input type="date" :id="'import-education-start-' + index" :aria-describedby="'import-education-help-' + index" x-model="educationStartDates[index]" :disabled="importing" required>
                                            </div>
                                        </template>
                                    </dl>
                                </template>
                            </section>
                        </template>
                    </section>
                    <p x-show="importError" x-text="importError" class="cv-field-error" role="alert"></p>
                    <p x-show="importSuccess" class="cv-import-success" role="status">{{ __('Import saved. Reload to review your updated content.') }}</p>
                </div>
                <footer class="cv-modal-footer">
                    <button type="button" class="cv-button cv-button-secondary" @click="importModalOpen = false" :disabled="importing">{{ __('Close') }}</button>
                    <button type="button" class="cv-button cv-button-primary" x-show="importPreview && !importSuccess" :disabled="importing" @click="await confirmImport(); if (importSuccess) $nextTick(() => $refs.importSavedReview.focus())"><span x-text="importing ? @js(__('Saving...')) : @js(__('Confirm replacement'))"></span></button>
                    <button type="button" class="cv-button cv-button-primary" x-show="importSuccess" x-ref="importSavedReview" @click="window.location.reload()">{{ __('Review imported content') }}</button>
                </footer>
            </div>
        </div>
    </template>
</div>
