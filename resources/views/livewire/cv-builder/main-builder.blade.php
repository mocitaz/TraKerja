<div class="cv-editor-layout" x-data="cvWorkspace(@js(route('cv.import-pdf')), @js(route('cv.import-pdf.confirm')))" @cv-data-updated.window="previewVersion++">
    <aside class="cv-sidebar">
        <section class="cv-sidebar-intro"><p>Your CV workspace</p><h2>Start with your story.</h2><span>Only include sections relevant to you. Experience is optional.</span></section>
        <nav class="cv-section-nav" aria-label="CV sections">
            <button type="button" wire:click="selectSection('details')" @class(['is-selected' => $section === 'details']) @if($section === 'details') aria-current="page" @endif><span>Your details</span><span class="cv-section-count">Profile</span></button>
            @foreach($sections as $item)
                <button type="button" wire:click="selectSection('{{ $item['key'] }}')" @class(['is-selected' => $section === $item['key']]) @if($section === $item['key']) aria-current="page" @endif><span>{{ $item['label'] }}</span><span class="cv-section-count">{{ $item['count'] }}</span></button>
            @endforeach
        </nav>
        <div class="cv-sidebar-actions">
            <button type="button" @click="previewOpen = true; previewLoading = true">Quick preview <i class="ph ph-arrow-up-right" aria-hidden="true"></i></button>
            <button type="button" @click="importModalOpen = true; importError = ''">Import an existing PDF <i class="ph ph-file-arrow-up" aria-hidden="true"></i></button>
            <button type="button" @click="$dispatch('openPublishModal')">Publish a portfolio site <i class="ph ph-globe" aria-hidden="true"></i></button>
        </div>
        <p class="cv-sidebar-note">CV and portfolio use your saved details. Check your contact information before sharing.</p>
    </aside>
    <div class="cv-editor-content">
        <div class="cv-content-note"><span class="cv-content-dot"></span><p>Save each section as you go. Your templates use the latest saved content.</p><a href="{{ route('cv.generator') }}" wire:navigate>Preview templates</a></div>
        <section class="cv-form-panel" x-show="$wire.section === 'details'">
            <header class="cv-section-heading"><div><h2>Your details</h2><p>Introduce yourself and give recruiters a way to reach you.</p></div></header>
            <div class="cv-identity-strip"><img src="{{ $user->avatar_url }}" alt="" width="44" height="44"><div><strong>{{ $user->name }}</strong><span>{{ $user->email }}</span></div><a href="{{ route('profile.edit') }}" wire:navigate>Edit account</a></div>
            <form wire:submit="saveDetails" class="cv-details-form">
                @php $fields = ['headline' => ['Professional headline', 'e.g. Frontend developer', 'text'], 'phone_number' => ['Phone number', 'Your contact number', 'tel'], 'domicile' => ['Location', 'City, country', 'text'], 'linkedin_url' => ['LinkedIn URL', 'https://linkedin.com/in/your-name', 'url'], 'website_url' => ['Website or portfolio', 'https://your-website.com', 'url']]; @endphp
                <div class="cv-details-grid">
                    @foreach($fields as $field => [$label, $placeholder, $type])
                        <div @class(['cv-detail-field', 'cv-field-wide' => $field === 'headline'])><label for="cv-{{ $field }}">{{ $label }}</label><input id="cv-{{ $field }}" type="{{ $type }}" wire:model="{{ $field }}" placeholder="{{ $placeholder }}" maxlength="{{ $field === 'phone_number' ? 20 : 255 }}" aria-invalid="{{ $errors->has($field) ? 'true' : 'false' }}" @error($field) aria-describedby="cv-error-{{ $field }}" @enderror>@error($field)<p id="cv-error-{{ $field }}" class="cv-field-error" role="alert">{{ $message }}</p>@enderror</div>
                    @endforeach
                </div>
                <div class="cv-detail-field"><label for="cv-bio">Professional summary</label><p id="cv-bio-help">A few sentences about your experience, strengths, and next career step.</p><textarea id="cv-bio" rows="5" maxlength="1000" wire:model="bio" aria-describedby="cv-bio-help{{ $errors->has('bio') ? ' cv-error-bio' : '' }}" placeholder="Write a short introduction in your own words."></textarea>@error('bio')<p id="cv-error-bio" class="cv-field-error" role="alert">{{ $message }}</p>@enderror</div>
                <footer class="cv-form-footer"><span>Optional fields. Changes also update your profile.</span><button type="submit" class="cv-button cv-button-primary" wire:loading.attr="disabled" wire:target="saveDetails"><span wire:loading.remove wire:target="saveDetails">Save details</span><span wire:loading wire:target="saveDetails">Saving...</span></button></footer>
            </form>
        </section>
        @foreach($sections as $item)
            @php $component = match($item['key']) { 'experiences' => 'experience', 'education' => 'education', 'skills' => 'skills', 'projects' => 'project', 'organizations' => 'organization', 'achievements' => 'achievement' }; @endphp
            <section class="cv-form-panel cv-section-content" x-show="$wire.section === '{{ $item['key'] }}'" x-cloak>
                @livewire('cv-builder.'.$component.'-form', [], key('cv-section-'.$item['key']))
            </section>
        @endforeach
        <section class="cv-next-step"><div><h3>Ready to see your draft?</h3><p>Choose a template when you have content to review. You can keep editing afterward.</p></div><a href="{{ route('cv.generator') }}" class="cv-button cv-button-secondary" wire:navigate>Choose a template</a></section>
    </div>
    <template x-teleport="body">
        <div class="cv-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="cv-quick-title" tabindex="-1" x-dialog="previewOpen" x-show="previewOpen" x-cloak @click.self="previewOpen = false">
            <div class="cv-preview-dialog"><header class="cv-modal-header"><div><h2 id="cv-quick-title">Your CV draft</h2><p>Minimal template · Saved content</p></div><button type="button" class="cv-close" @click="previewOpen = false" aria-label="Close CV preview"><i class="ph ph-x" aria-hidden="true"></i></button></header><div class="cv-preview-canvas"><p x-show="previewLoading" class="cv-preview-loading" role="status">Loading your draft...</p><iframe title="Your saved CV in the minimal template" :src="previewOpen ? @js(route('cv-builder.preview.get', ['template' => 'minimal', 'canvas' => 1])) + '&v=' + previewVersion : 'about:blank'" @load="previewLoading = false"></iframe></div><footer class="cv-modal-footer"><span>Check details before sharing.</span><a href="{{ route('cv.generator') }}" class="cv-button cv-button-primary" wire:navigate>Choose template & export</a></footer></div>
        </div>
    </template>
    <template x-teleport="body">
        <div class="cv-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="cv-import-title" tabindex="-1" x-dialog="importModalOpen" x-show="importModalOpen" x-cloak @click.self="if (!importing) importModalOpen = false">
            <div class="cv-import-dialog">
                <header class="cv-modal-header"><div><h2 id="cv-import-title">Import your CV</h2><p>Preview the extracted content before saving.</p></div><button type="button" class="cv-close" @click="importModalOpen = false" :disabled="importing" aria-label="Close CV import"><i class="ph ph-x" aria-hidden="true"></i></button></header>
                <div class="cv-import-body" :aria-busy="importing">
                    <p>Nothing changes when you upload a PDF. After you review and confirm, each section below with extracted entries will replace all existing entries in that section. Sections with no extracted entries stay unchanged.</p>
                    <label class="cv-import-dropzone" x-show="!importSuccess" @dragover.prevent @drop.prevent="uploadPdf($event.dataTransfer.files[0])"><strong x-text="importing ? 'Processing your request...' : 'Choose a PDF or drop it here'"></strong><span>PDF only · Up to 10 MB</span><input type="file" accept="application/pdf,.pdf" :disabled="importing" @change="uploadPdf($event.target.files[0]); $event.target.value = ''"><span x-show="importing" role="status" x-text="importFilename"></span></label>
                    <section x-show="importPreview" class="cv-import-review" aria-labelledby="cv-import-review-title" x-cloak>
                        <h3 id="cv-import-review-title">Review proposed replacements</h3>
                        <p>Check every field below. Details not found in the PDF remain blank; complete them in CV Builder after importing. If an education start date is missing, enter it below before saving. Close to cancel, or choose another PDF. This preview expires after 15 minutes.</p>
                        <template x-for="section in importSections" :key="section.key">
                            <section class="cv-import-review-section">
                                <h4 x-text="section.label"></h4>
                                <p x-text="section.entries.length ? section.existing + ' existing entries will be replaced by ' + section.entries.length + ' extracted entries.' : 'No entries extracted. Your ' + section.existing + ' existing entries will be kept.'"></p>
                                <template x-for="(entry, index) in section.entries" :key="index">
                                    <dl class="cv-import-entry">
                                        <template x-for="field in Object.entries(entry).filter(([key]) => key !== 'display_order')" :key="field[0]">
                                            <div><dt x-text="importFieldLabel(field[0])"></dt><dd x-text="typeof field[1] === 'boolean' ? (field[1] ? 'Yes' : (!entry.start_date && !entry.end_date ? 'Not specified' : 'No')) : (field[1] === '' || field[1] == null ? 'Not specified' : field[1])"></dd></div>
                                        </template>
                                        <template x-if="section.key === 'educations' && !entry.start_date">
                                            <div class="cv-detail-field">
                                                <label :for="'import-education-start-' + index">Education start date (required)</label>
                                                <p :id="'import-education-help-' + index" x-text="importPreview?.education_date_hints?.[index] ? 'Date text in PDF: ' + importPreview.education_date_hints[index] + '. Enter the full start date from your records.' : 'No start date was read. Enter it from your records.'"></p>
                                                <input type="date" :id="'import-education-start-' + index" :aria-describedby="'import-education-help-' + index" x-model="educationStartDates[index]" :disabled="importing" required>
                                            </div>
                                        </template>
                                    </dl>
                                </template>
                            </section>
                        </template>
                    </section>
                    <p x-show="importError" x-text="importError" class="cv-field-error" role="alert"></p>
                    <p x-show="importSuccess" class="cv-import-success" role="status">Import saved. Reload to review your updated content.</p>
                </div>
                <footer class="cv-modal-footer">
                    <button type="button" class="cv-button cv-button-secondary" @click="importModalOpen = false" :disabled="importing">Close</button>
                    <button type="button" class="cv-button cv-button-primary" x-show="importPreview && !importSuccess" :disabled="importing" @click="await confirmImport(); if (importSuccess) $nextTick(() => $refs.importSavedReview.focus())"><span x-text="importing ? 'Saving...' : 'Confirm replacement'"></span></button>
                    <button type="button" class="cv-button cv-button-primary" x-show="importSuccess" x-ref="importSavedReview" @click="window.location.reload()">Review imported content</button>
                </footer>
            </div>
        </div>
    </template>
</div>
