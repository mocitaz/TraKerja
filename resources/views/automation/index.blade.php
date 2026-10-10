<x-app-layout>
    <div class="workspace-page automation-page" x-data="{ activeTab: '{{ session('import_summary') || $errors->has('csv_file') ? 'csv' : $activeTab }}', selectTab(tab) { this.activeTab = tab; const url = new URL(window.location); url.searchParams.set('tab', tab); window.history.replaceState({}, '', url); } }">
        <div class="workspace-page__container">
            <header class="workspace-page__header"><div><p class="workspace-eyebrow">Workspace tools</p><h1>Automation & data</h1><p>Save opportunities faster and keep your application data organized.</p></div><a href="{{ route('tracker') }}" wire:navigate class="workspace-button workspace-button--quiet">View applications <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a></header>
            <div class="workspace-switcher" aria-label="Automation sections">
                <button type="button" @click="selectTab('extension')" :aria-pressed="activeTab === 'extension'" :class="{ 'is-active': activeTab === 'extension' }" aria-controls="automation-extension">Chrome extension</button>
                <button type="button" @click="selectTab('csv')" :aria-pressed="activeTab === 'csv'" :class="{ 'is-active': activeTab === 'csv' }" aria-controls="automation-csv">Import & export</button>
            </div>
            <section id="automation-extension" x-show="activeTab === 'extension'" class="workspace-stack">
                <div class="automation-overview">
                    <div class="workspace-card automation-intro"><span class="workspace-status">Chrome · Desktop</span><h2>Save a role.<br>Skip the copy-paste.</h2><p>Save job details from supported sites straight to your tracker.</p>
                        @if($hasAccess)<a href="{{ asset('downloads/trakerja-extension.zip') }}" download class="workspace-button workspace-button--primary">Download extension <i class="ph ph-download-simple" aria-hidden="true"></i></a>
                        @else<p class="workspace-hint">The extension is available with Premium.</p><a href="{{ route('payment.premium') }}" class="workspace-button workspace-button--primary">Explore Premium</a>@endif
                        <p class="workspace-hint">Install manually in Chrome on a PC or laptop.</p>
                    </div>
                    <div class="workspace-card workspace-card--glass automation-preview" aria-label="Example of a saved job">
                        <div class="automation-preview__top"><span>TraKerja extension</span><span class="workspace-status">Example</span></div>
                        <p class="workspace-eyebrow">Your next opportunity</p><h3>Software Engineer</h3><p>Example company · Jakarta</p><div class="automation-preview__field"><span>Source</span><strong>LinkedIn</strong></div><div class="automation-preview__field"><span>Destination</span><strong>Your application tracker</strong></div><div class="automation-preview__action"><i class="ph ph-check" aria-hidden="true"></i> Save to TraKerja</div>
                    </div>
                </div>
                <div class="workspace-page__grid automation-balanced-grid">
                    <section class="workspace-card automation-setup"><div class="workspace-card__heading"><h2>Install the extension</h2><p>Four steps to get started in Chrome.</p></div><ol class="workspace-steps automation-steps"><li><span>01</span><div><h3>Extract the download</h3><p>Unzip the package and keep the extracted folder on your computer.</p></div></li><li><span>02</span><div><h3>Open Chrome extensions</h3><p>Enter <code>chrome://extensions/</code> in the address bar.</p></div></li><li><span>03</span><div><h3>Enable Developer mode</h3><p>Turn on the switch in the top-right corner.</p></div></li><li><span>04</span><div><h3>Load the extension</h3><p>Choose Load unpacked and select the extracted folder. Open a supported job page to start.</p></div></li></ol></section>
                    <aside class="workspace-card workspace-card--glass automation-side-panel"><section class="automation-side-section"><h2>Your workspace</h2><div class="automation-metrics"><div><strong>{{ $extensionSavedCount }}</strong><span>Saved with the extension</span></div><div><strong>{{ $totalJobsCount }}</strong><span>Total in your tracker</span></div></div></section><section class="automation-side-section"><h2>Supported job sites</h2><div class="workspace-chips">@foreach(['LinkedIn', 'Glints', 'JobStreet', 'Kalibrr', 'Dealls'] as $platform)<span>{{ $platform }}</span>@endforeach</div><p>Review captured details before saving. Support varies by job page.</p></section></aside>
                </div>
            </section>
            <section id="automation-csv" x-show="activeTab === 'csv'" x-cloak class="workspace-stack">
                @if(session('import_summary'))
                    <div role="status" class="workspace-notice"><strong>Import complete.</strong> {{ session('import_summary.imported_count') }} rows imported; {{ session('import_summary.failed_count') }} rows skipped.
                        @if(session('import_summary.errors'))<details><summary>View skipped rows</summary>@foreach(session('import_summary.errors', []) as $failure)<p class="workspace-error">Row {{ $failure['row'] }}: {{ implode('; ', $failure['errors']) }}</p>@endforeach</details>@endif
                    </div>
                @endif
                @if(!$hasAccess)
                    <div class="workspace-card workspace-empty"><p class="workspace-eyebrow">Premium tools</p><h2>Bring your data with you.</h2><p>Import and export your application history with CSV tools.</p><a href="{{ route('payment.premium') }}" class="workspace-button workspace-button--primary">Explore Premium</a></div>
                @else
                    <div class="workspace-page__grid automation-balanced-grid" x-data="csvImporter()">
                        <form action="{{ route('csv.import.process') }}" method="POST" enctype="multipart/form-data" id="csvImportForm" class="workspace-card automation-import-panel" @submit="if (!preview || preview.valid_count === 0 || isScanning || submitting) { $event.preventDefault(); } else { submitting = true; }">
                            @csrf
                            <div class="workspace-card__heading"><h2>Import applications</h2><p>Upload a CSV, check the preview, and confirm your import.</p></div>
                            <div class="automation-stepper" aria-label="Import progress"><span :class="{ 'is-current': currentStep === 1 }">1 <strong>Choose file</strong></span><span :class="{ 'is-current': currentStep === 2 }">2 <strong>Review & import</strong></span></div>
                            <input type="file" id="csv_file" name="csv_file" accept=".csv,.txt" class="sr-only" tabindex="-1" aria-label="CSV file" @change="startScan($event.target.files[0])">
                            <div x-show="currentStep === 1" class="automation-upload-stage">
                                <button type="button" id="drop-zone" class="automation-upload" :class="{ 'is-dragging': dragging }" @click="document.getElementById('csv_file').click()" @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false" @drop.prevent="dragging = false; if ($event.dataTransfer.files.length) { document.getElementById('csv_file').files = $event.dataTransfer.files; startScan($event.dataTransfer.files[0]); }" :disabled="isScanning">
                                    <span class="automation-upload__icon"><i class="ph ph-upload-simple" aria-hidden="true"></i></span><strong x-text="isScanning ? 'Checking your file…' : 'Drop your CSV here'"></strong><span x-text="isScanning ? fileName : 'or click to browse · CSV or TXT · up to 2 MB'"></span>
                                </button>
                                <p class="workspace-hint">Need the right columns? Download the template to get started.</p>
                            </div>
                            <p x-show="error" x-text="error" role="alert" class="workspace-error"></p>
                            @error('csv_file')<p class="workspace-error" role="alert">{{ $message }}</p>@enderror
                            <div x-show="currentStep === 2" x-cloak class="automation-review-stage">
                                <div class="automation-file"><div><strong x-text="fileName"></strong><p>Preview shows up to 10 valid rows.</p></div><button type="button" @click="currentStep = 1; preview = null" :disabled="submitting" class="workspace-button workspace-button--quiet">Change file</button></div>
                                <div class="automation-validation" aria-live="polite"><span><strong x-text="preview?.valid_count || 0"></strong> valid rows</span><span><strong x-text="preview?.failed_count || 0"></strong> skipped rows</span></div>
                                <div class="workspace-table-scroll" role="region" aria-label="CSV preview" tabindex="0"><table class="workspace-table"><thead><tr>@foreach(['Company', 'Position', 'Location', 'Platform', 'Status', 'Date'] as $column)<th scope="col">{{ $column }}</th>@endforeach</tr></thead><tbody><template x-for="(row, index) in (preview?.rows || [])" :key="index"><tr>@foreach(['company_name', 'position', 'location', 'platform', 'application_status', 'application_date'] as $column)<td x-text="String(row.{{ $column }} || '').{{ $column === 'application_date' ? 'slice(0, 10)' : 'toString()' }}"></td>@endforeach</tr></template></tbody></table></div>
                                <details x-show="(preview?.errors || []).length" class="automation-errors"><summary>Review skipped rows</summary><template x-for="item in (preview?.errors || [])" :key="item.row"><p class="workspace-error" x-text="'Row ' + item.row + ': ' + item.errors.join('; ')"></p></template></details>
                                <p class="workspace-hint">Only valid rows will be imported. Importing a file again adds new applications.</p>
                                <div class="workspace-form__footer"><span class="workspace-hint">Your file is ready to review.</span><button type="submit" :disabled="!preview || preview.valid_count === 0 || isScanning || submitting" class="workspace-button workspace-button--primary" x-text="submitting ? 'Importing…' : 'Import ' + (preview?.valid_count || 0) + ' applications'"></button></div>
                            </div>
                        </form>
                        <aside class="workspace-card workspace-card--glass automation-side-panel"><section class="automation-side-section"><h2>Export & template</h2><p>Export your applications or start with a template that matches the importer.</p><div class="automation-downloads"><a href="{{ route('csv.export') }}" class="workspace-button workspace-button--primary">Export applications <i class="ph ph-download-simple" aria-hidden="true"></i></a><a href="{{ route('csv.template') }}" class="workspace-button workspace-button--quiet">Download template</a></div><p class="workspace-hint">{{ $totalJobsCount }} applications currently in your tracker.</p></section><section class="automation-side-section"><h2>Before you upload</h2><ul class="workspace-checklist"><li>Use the template headers as provided.</li><li>Write dates as YYYY-MM-DD.</li><li>Use On Process, Accepted, or Rejected for status.</li><li>Keep the file size under 2 MB.</li></ul><p class="workspace-hint">The preview will explain any rows that need fixing.</p></section></aside>
                    </div>
                @endif
            </section>
        </div>
    </div>
    <script>
        function csvImporter() {
            return {
                currentStep: 1, fileName: '', isScanning: false, preview: null, error: '', requestNumber: 0, dragging: false, submitting: false,
                async startScan(file) {
                    const number = ++this.requestNumber;
                    this.preview = null;
                    this.error = '';
                    this.currentStep = 1;
                    this.isScanning = false;
                    this.fileName = '';
                    if (!file) return;
                    this.fileName = file.name;
                    this.isScanning = true;
                    try {
                        const response = await fetch(@json(route('csv.import.preview')), {
                            method: 'POST', credentials: 'same-origin', headers: {'Accept': 'application/json'},
                            body: new FormData(document.getElementById('csvImportForm')),
                        });
                        const result = await response.json();
                        if (number !== this.requestNumber) return;
                        if (!response.ok) throw new Error(result.errors?.csv_file?.join(' ') || result.message || 'Could not validate this file.');
                        this.preview = result;
                        this.currentStep = 2;
                    } catch (error) {
                        if (number === this.requestNumber) this.error = error.message || 'Could not validate this file. Try again.';
                    } finally {
                        if (number === this.requestNumber) this.isScanning = false;
                    }
                },
            };
        }
    </script>
</x-app-layout>
