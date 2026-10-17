<x-app-layout>
    <div class="workspace-page automation-page" x-data="{ activeTab: '{{ session('import_summary') || $errors->has('csv_file') ? 'csv' : $activeTab }}', selectTab(tab) { this.activeTab = tab; const url = new URL(window.location); url.searchParams.set('tab', tab); window.history.replaceState({}, '', url); } }">
        <div class="workspace-page__container">
            <header class="workspace-page__header"><div><h1>{{ __('Automation & data') }}</h1><p>{{ __('Save opportunities faster and keep your application data organized.') }}</p></div><a href="{{ route('tracker') }}" wire:navigate class="workspace-button workspace-button--quiet">{{ __('View applications') }} <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a></header>
            <div class="workspace-switcher" aria-label="{{ __('Automation sections') }}">
                <button type="button" @click="selectTab('extension')" :aria-pressed="activeTab === 'extension'" :class="{ 'is-active': activeTab === 'extension' }" aria-controls="automation-extension">{{ __('Chrome extension') }}</button>
                <button type="button" @click="selectTab('csv')" :aria-pressed="activeTab === 'csv'" :class="{ 'is-active': activeTab === 'csv' }" aria-controls="automation-csv">{{ __('Import & export') }}</button>
            </div>
            <section id="automation-extension" x-show="activeTab === 'extension'" class="workspace-stack">
                <div class="automation-overview">
                    <div class="workspace-card automation-intro"><span class="workspace-status">{{ __('Chrome · Desktop') }}</span><h2>{{ __('From job listing') }}<br>{{ __('to your tracker.') }}</h2><p>{{ __('Save job details from supported sites straight to your tracker.') }}</p>
                        @if($hasAccess)<a href="{{ asset('downloads/trakerja-extension.zip') }}" download class="workspace-button workspace-button--primary">{{ __('Download extension') }} <i class="ph ph-download-simple" aria-hidden="true"></i></a>
                        @else<p class="workspace-hint">{{ __('The extension is available with Premium.') }}</p><a href="{{ route('payment.premium') }}" class="workspace-button workspace-button--primary">{{ __('Explore Premium') }}</a>@endif
                        <p class="workspace-hint">{{ __('Install manually in Chrome on a PC or laptop.') }}</p><p class="automation-mobile-note">{{ __('Using your phone? Open this page in Chrome on your computer to install the extension.') }}</p>
                    </div>
                    <div class="workspace-card workspace-card--glass automation-preview" aria-label="{{ __('Example of a saved job') }}">
                        <div class="automation-preview__top"><span>{{ __('TraKerja extension') }}</span><span class="workspace-status">{{ __('Example') }}</span></div>
                        <p class="workspace-eyebrow">{{ __('Your next opportunity') }}</p><h3>{{ __('Software Engineer') }}</h3><p>{{ __('Example company · Jakarta') }}</p><div class="automation-preview__field"><span>{{ __('Source') }}</span><strong>{{ __('LinkedIn') }}</strong></div><div class="automation-preview__field"><span>{{ __('Destination') }}</span><strong>{{ __('Your application tracker') }}</strong></div><div class="automation-preview__action"><i class="ph ph-check" aria-hidden="true"></i> {{ __('Save to TraKerja') }}</div>
                    </div>
                </div>
                <div class="workspace-page__grid automation-balanced-grid">
                    <section class="workspace-card automation-setup"><div class="workspace-card__heading"><h2>{{ __('Install the extension') }}</h2><p>{{ __('Four steps to get started in Chrome.') }}</p></div><ol class="workspace-steps automation-steps"><li><span>01</span><div><h3>{{ __('Extract the download') }}</h3><p>{{ __('Unzip the package and keep the extracted folder on your computer.') }}</p></div></li><li><span>02</span><div><h3>{{ __('Open Chrome extensions') }}</h3><p>Enter <code>chrome://extensions/</code><button type="button" class="automation-copy-address" x-data="{ copied: false, failed: false }" @click="navigator.clipboard.writeText('chrome://extensions/').then(() => { copied = true; failed = false; setTimeout(() => copied = false, 2000); }).catch(() => failed = true)" x-text="failed ? @js(__('Select and copy address')) : (copied ? @js(__('Copied')) : @js(__('Copy address')))">{{ __('Copy address') }}</button> {{ __('in the address bar.') }}</p></div></li><li><span>03</span><div><h3>{{ __('Enable Developer mode') }}</h3><p>{{ __('Turn on the switch in the top-right corner.') }}</p></div></li><li><span>04</span><div><h3>{{ __('Load the extension') }}</h3><p>{{ __('Choose Load unpacked and select the extracted folder. Open a supported job page to start.') }}</p></div></li></ol></section>
                    <aside class="workspace-card workspace-card--glass automation-side-panel"><section class="automation-side-section"><h2>{{ __('Your workspace') }}</h2><div class="automation-metrics"><div><strong>{{ $extensionSavedCount }}</strong><span>{{ __('Extension-tagged records') }}</span></div><div><strong>{{ $totalJobsCount }}</strong><span>{{ __('Total in your tracker') }}</span></div></div><p class="workspace-hint">{{ __('Counted from the Chrome Extension label in application notes; not a connection status.') }}</p></section><section class="automation-side-section"><h2>{{ __('Supported job sites') }}</h2><div class="workspace-chips">@foreach(['LinkedIn', 'Glints', 'JobStreet', 'Kalibrr', 'Dealls'] as $platform)<span><x-platform-brand :name="$platform" /></span>@endforeach</div><p>{{ __('Review captured details before saving. Support varies by job page.') }}</p></section></aside>
                </div>
            </section>
            <section id="automation-csv" x-show="activeTab === 'csv'" x-cloak class="workspace-stack">
                @if(session('import_summary'))
                    <div role="status" class="workspace-notice"><strong>{{ __('Import complete.') }}</strong> {{ __(':value1 rows imported; :value2 rows skipped.', ['value1' => (session('import_summary.imported_count')), 'value2' => (session('import_summary.failed_count'))]) }} <a href="{{ route('tracker') }}" wire:navigate>{{ __('View applications') }}</a>
                        @if(session('import_summary.errors'))<details><summary>{{ __('View skipped rows') }}</summary>@foreach(session('import_summary.errors', []) as $failure)<p class="workspace-error">{{ __('Row :value1: :value2', ['value1' => ($failure['row']), 'value2' => (implode('; ', $failure['errors']))]) }}</p>@endforeach</details>@endif
                    </div>
                @endif
                @if(!$hasAccess)
                    <div class="workspace-card workspace-empty"><p class="workspace-eyebrow">{{ __('Premium tools') }}</p><h2>{{ __('Bring your data with you.') }}</h2><p>{{ __('Import and export your application history with CSV tools.') }}</p><a href="{{ route('payment.premium') }}" class="workspace-button workspace-button--primary">{{ __('Explore Premium') }}</a></div>
                @else
                    <div class="workspace-page__grid automation-balanced-grid" x-data="csvImporter()">
                        <form action="{{ route('csv.import.process') }}" method="POST" enctype="multipart/form-data" id="csvImportForm" class="workspace-card automation-import-panel" @submit="if (!preview || preview.valid_count === 0 || isScanning || submitting) { $event.preventDefault(); } else { submitting = true; }">
                            @csrf
                            <div class="workspace-card__heading"><h2>{{ __('Import applications') }}</h2><p>{{ __('Upload a CSV, check the preview, and confirm your import.') }}</p><a class="automation-template-link" href="{{ route('csv.template') }}">{{ __('Download CSV template') }}</a></div>
                            <div class="automation-stepper" aria-label="{{ __('Import progress') }}"><span :class="{ 'is-current': currentStep === 1 }">1 <strong>{{ __('Choose file') }}</strong></span><span :class="{ 'is-current': currentStep === 2 }">2 <strong>{{ __('Review & import') }}</strong></span></div>
                            <input type="file" id="csv_file" name="csv_file" accept=".csv,.txt" class="sr-only" tabindex="-1" aria-label="{{ __('CSV file') }}" @change="startScan($event.target.files[0])">
                            <div x-show="currentStep === 1" class="automation-upload-stage">
                                <button type="button" id="drop-zone" class="automation-upload" :class="{ 'is-dragging': dragging }" @click="document.getElementById('csv_file').click()" @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false" @drop.prevent="dragging = false; if ($event.dataTransfer.files.length) { document.getElementById('csv_file').files = $event.dataTransfer.files; startScan($event.dataTransfer.files[0]); }" :disabled="isScanning">
                                    <span class="automation-upload__icon"><i class="ph ph-upload-simple" aria-hidden="true"></i></span><strong x-text="isScanning ? @js(__('Checking your file…')) : @js(__('Choose your CSV file'))"></strong><span x-text="isScanning ? fileName : @js(__('or click to browse · CSV or TXT · up to 2 MB'))"></span>
                                </button>
                                <p class="workspace-hint">{{ __('Need the right columns? Download the template to get started.') }}</p>
                            </div>
                            <p x-show="error" x-text="error" role="alert" class="workspace-error"></p>
                            @error('csv_file')<p class="workspace-error" role="alert">{{ $message }}</p>@enderror
                            <div x-show="currentStep === 2" x-cloak class="automation-review-stage">
                                <div class="automation-file"><div><strong x-text="fileName"></strong><p>{{ __('Preview shows up to 10 valid rows.') }}</p></div><button type="button" @click="currentStep = 1; preview = null" :disabled="submitting" class="workspace-button workspace-button--quiet">{{ __('Change file') }}</button></div>
                                <div class="automation-validation" aria-live="polite"><span><strong x-text="preview?.valid_count || 0"></strong> {{ __('valid rows') }}</span><span><strong x-text="preview?.failed_count || 0"></strong> {{ __('skipped rows') }}</span></div>
                                <div class="workspace-table-scroll" role="region" aria-label="{{ __('CSV preview') }}" tabindex="0"><table class="workspace-table"><thead><tr>@foreach(['Company', 'Position', 'Location', 'Platform', 'Status', 'Date'] as $column)<th scope="col">{{ $column }}</th>@endforeach</tr></thead><tbody><template x-for="(row, index) in (preview?.rows || [])" :key="index"><tr>@foreach(['company_name', 'position', 'location', 'platform', 'application_status', 'application_date'] as $column)<td x-text="String(row.{{ $column }} || '').{{ $column === 'application_date' ? __('slice(0, 10)') : __('toString()') }}"></td>@endforeach</tr></template></tbody></table></div>
                                <div class="automation-mobile-preview" aria-label="{{ __('CSV preview') }}"><template x-for="(row, index) in (preview?.rows || [])" :key="index"><article><strong x-text="row.position || @js(__('Position not recorded'))"></strong><span x-text="row.company_name || @js(__('Company not recorded'))"></span><dl><div><dt>{{ __('Location') }}</dt><dd x-text="row.location || '—'"></dd></div><div><dt>{{ __('Source') }}</dt><dd x-text="row.platform || '—'"></dd></div><div><dt>{{ __('Status') }}</dt><dd x-text="row.application_status || '—'"></dd></div><div><dt>{{ __('Applied') }}</dt><dd x-text="String(row.application_date || '').slice(0, 10) || '—'"></dd></div></dl></article></template></div>
                                <details x-show="(preview?.errors || []).length" class="automation-errors"><summary>{{ __('Review skipped rows') }}</summary><template x-for="item in (preview?.errors || [])" :key="item.row"><p class="workspace-error" x-text="@js(__('Row ')) + item.row + ': ' + item.errors.join('; ')"></p></template></details>
                                <p class="workspace-hint">{{ __('Only valid rows will be imported. Importing a file again adds new applications.') }}</p>
                                <div class="workspace-form__footer"><span class="workspace-hint">{{ __('Your file is ready to review.') }}</span><button type="submit" :disabled="!preview || preview.valid_count === 0 || isScanning || submitting" class="workspace-button workspace-button--primary" x-text="submitting ? @js(__('Importing…')) : @js(__('Import ')) + (preview?.valid_count || 0) + @js(__(' applications'))"></button></div>
                            </div>
                        </form>
                        <aside class="workspace-card workspace-card--glass automation-side-panel"><section class="automation-side-section"><h2>{{ __('Export & template') }}</h2><p>{{ __('Export your applications or start with a template that matches the importer.') }}</p><div class="automation-downloads"><a href="{{ route('csv.export') }}" class="workspace-button workspace-button--primary">{{ __('Export applications') }} <i class="ph ph-download-simple" aria-hidden="true"></i></a><a href="{{ route('csv.template') }}" class="workspace-button workspace-button--quiet">{{ __('Download template') }}</a></div><p class="workspace-hint">{{ __(':value1 applications currently in your tracker.', ['value1' => ($totalJobsCount)]) }}</p></section><section class="automation-side-section"><h2>{{ __('Before you upload') }}</h2><ul class="workspace-checklist"><li>{{ __('Use the template headers as provided.') }}</li><li>{{ __('Write dates as YYYY-MM-DD.') }}</li><li>{{ __('Use On Process, Accepted, or Rejected for status.') }}</li><li>{{ __('Keep the file size under 2 MB.') }}</li></ul><p class="workspace-hint">{{ __('The preview will explain any rows that need fixing.') }}</p></section></aside>
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
                        if (number === this.requestNumber) this.error = error.message || @js(__('Could not validate this file. Try again.'));
                    } finally {
                        if (number === this.requestNumber) this.isScanning = false;
                    }
                },
            };
        }
    </script>
</x-app-layout>
