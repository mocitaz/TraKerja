<x-app-layout>
    @php($initialSection = session('import_summary') || $errors->has('csv_file') ? 'csv' : $activeTab)
    <div class="workspace-page automation-page automation-studio" data-initial-section="{{ $initialSection }}" x-data="{}" x-init="$nextTick(() => { if ($el.dataset.initialSection === 'csv') document.getElementById('automation-csv').scrollIntoView({ block: 'start' }) })">
        <div class="workspace-page-frame">
            <header class="auto-page-header"><div><h1>{{ __('Automation & data') }}</h1><p>{{ __('Save opportunities faster and keep your application data organized.') }}</p></div><a class="auto-text-link" href="{{ route('tracker') }}" wire:navigate>{{ __('View applications') }}<i class="ph ph-arrow-up-right" aria-hidden="true"></i></a></header>

            <section class="auto-hero" id="automation-extension" aria-labelledby="auto-extension-title">
                <div class="auto-hero-copy">
                    <span class="auto-kicker"><i class="ph ph-browser" aria-hidden="true"></i>{{ __('Chrome extension') }}<span>·</span>{{ __('Desktop') }}</span>
                    <h2 id="auto-extension-title">{{ __('From job listing') }}<br><span>{{ __('to your tracker.') }}</span></h2>
                    <p>{{ __('Save job details from supported sites straight to your tracker.') }}</p>
                    <div class="auto-actions">
                        @if($hasAccess)<a href="{{ asset('downloads/trakerja-extension.zip') }}" download class="auto-button auto-button--primary"><i class="ph ph-download-simple" aria-hidden="true"></i>{{ __('Download extension') }}</a>
                        @else<a href="{{ route('payment.premium') }}" class="auto-button auto-button--primary">{{ __('Explore Premium') }}</a>@endif
                        <a href="#automation-setup" class="auto-text-link">{{ __('Installation guide') }}<i class="ph ph-arrow-down" aria-hidden="true"></i></a>
                    </div>
                    <p class="auto-hint">{{ $hasAccess ? __('Install manually in Chrome on a PC or laptop.') : __('The extension is available with Premium.') }}</p>
                    <p class="auto-phone-note">{{ __('Using your phone? Open this page in Chrome on your computer to install the extension.') }}</p>
                </div>
                <figure class="auto-example" aria-label="{{ __('Example of a saved job') }}">
                    <figcaption><span><i class="ph ph-browser" aria-hidden="true"></i>{{ __('TraKerja extension') }}</span><span>{{ __('Example') }}</span></figcaption>
                    <div class="auto-example-job"><x-role-avatar position="Software Engineer"/><div><h3>{{ __('Software Engineer') }}</h3><p>{{ __('Example company · Jakarta') }}</p></div></div>
                    <dl><div><dt>{{ __('Source') }}</dt><dd><x-platform-brand name="LinkedIn" /></dd></div><div><dt>{{ __('Destination') }}</dt><dd>{{ __('Your application tracker') }}</dd></div></dl>
                    <p class="auto-example-note"><i class="ph ph-pencil-simple-line" aria-hidden="true"></i>{{ __('Review captured details before saving.') }}</p>
                </figure>
            </section>

            <div class="auto-platforms"><p>{{ __('Supported job sites') }}</p><div>@foreach(['LinkedIn', 'Glints', 'JobStreet', 'Kalibrr', 'Dealls'] as $platform)<x-platform-brand :name="$platform"/>@endforeach</div><small>{{ __('Support varies by job page.') }}</small></div>

            <section class="auto-setup" id="automation-setup" aria-labelledby="auto-setup-title">
                <header class="auto-section-heading"><div><h2 id="auto-setup-title">{{ __('Install the extension') }}</h2><p>{{ __('Four steps to get started in Chrome.') }}</p></div><span>{{ __('Chrome · Desktop') }}</span></header>
                <ol class="auto-steps">
                    <li><span>01</span><h3>{{ __('Extract the download') }}</h3><p>{{ __('Unzip the package and keep the extracted folder on your computer.') }}</p><div class="auto-step-visual" aria-hidden="true"><i class="ph ph-file-zip"></i><i class="ph ph-arrow-right"></i><i class="ph ph-folder-open"></i></div></li>
                    <li x-data="{ copied: false, failed: false }"><span>02</span><h3>{{ __('Open Chrome extensions') }}</h3><p>{{ __('Paste this address into Chrome:') }}</p><div class="auto-address"><code>chrome://extensions/</code><button type="button" @click="navigator.clipboard.writeText('chrome://extensions/').then(() => { copied = true; failed = false; setTimeout(() => copied = false, 2000) }).catch(() => failed = true)" :aria-label="copied ? @js(__('Copied')) : @js(__('Copy address'))"><i class="ph" :class="copied ? 'ph-check' : 'ph-copy'" aria-hidden="true"></i></button></div><p class="auto-copy-feedback" aria-live="polite" x-text="failed ? @js(__('Select and copy address')) : (copied ? @js(__('Copied')) : '')"></p></li>
                    <li><span>03</span><h3>{{ __('Enable Developer mode') }}</h3><p>{{ __('Turn on the switch in the top-right corner.') }}</p><div class="auto-step-visual" aria-hidden="true"><span class="auto-mini-switch"></span><span>Developer mode</span></div></li>
                    <li><span>04</span><h3>{{ __('Load the extension') }}</h3><p>{{ __('Choose Load unpacked and select the extracted folder. Open a supported job page to start.') }}</p><div class="auto-step-visual" aria-hidden="true"><i class="ph ph-folder-plus"></i><span>Load unpacked</span></div></li>
                </ol>
                <div class="auto-usage"><span><strong>{{ $extensionSavedCount }}</strong>{{ __('Extension-tagged records') }}</span><span><strong>{{ $totalJobsCount }}</strong>{{ __('Total in your tracker') }}</span><p>{{ __('Counted from the Chrome Extension label in application notes; not a connection status.') }}</p></div>
            </section>

            <section class="auto-data" id="automation-csv" aria-labelledby="auto-data-title">
                <header class="auto-section-heading"><div><h2 id="auto-data-title">{{ __('Import & export') }}</h2><p>{{ __('Bring your application history with you.') }}</p></div><span>CSV</span></header>
                @if(session('import_summary'))
                    <div role="status" class="auto-import-notice"><strong>{{ __('Import complete.') }}</strong> {{ __(':value1 rows imported; :value2 rows skipped.', ['value1' => session('import_summary.imported_count'), 'value2' => session('import_summary.failed_count')]) }} <a href="{{ route('tracker') }}" wire:navigate>{{ __('View applications') }}</a>
                        @if(session('import_summary.errors'))<details><summary>{{ __('View skipped rows') }}</summary>@foreach(session('import_summary.errors', []) as $failure)<p>{{ __('Row :value1: :value2', ['value1' => $failure['row'], 'value2' => implode('; ', $failure['errors'])]) }}</p>@endforeach</details>@endif
                    </div>
                @endif
                @if(!$hasAccess)
                    <div class="auto-premium"><div><h3>{{ __('Bring your data with you.') }}</h3><p>{{ __('Import and export your application history with CSV tools.') }}</p></div><a href="{{ route('payment.premium') }}" class="auto-button auto-button--primary">{{ __('Explore Premium') }}</a></div>
                @else
                    <div class="auto-data-body" x-data="csvImporter()">
                        <form action="{{ route('csv.import.process') }}" method="POST" enctype="multipart/form-data" id="csvImportForm" class="auto-import" @submit="if (!preview || preview.valid_count === 0 || isScanning || submitting) { $event.preventDefault() } else { submitting = true }">
                            @csrf
                            <header class="auto-form-heading"><div><h3>{{ __('Import applications') }}</h3><p>{{ __('Upload a CSV, check the preview, and confirm your import.') }}</p></div><a class="auto-text-link" href="{{ route('csv.template') }}">{{ __('Download template') }}<i class="ph ph-download-simple" aria-hidden="true"></i></a></header>
                            <div class="auto-import-progress" aria-label="{{ __('Import progress') }}"><span :class="{ 'is-current': currentStep === 1 }" :aria-current="currentStep === 1 ? 'step' : null">1 <strong>{{ __('Choose file') }}</strong></span><i aria-hidden="true"></i><span :class="{ 'is-current': currentStep === 2 }" :aria-current="currentStep === 2 ? 'step' : null">2 <strong>{{ __('Review & import') }}</strong></span></div>
                            <input type="file" id="csv_file" name="csv_file" accept=".csv,.txt" class="sr-only" tabindex="-1" aria-label="{{ __('CSV file') }}" @change="startScan($event.target.files[0])">
                            <div x-show="currentStep === 1">
                                <button type="button" id="drop-zone" class="auto-upload" :class="{ 'is-dragging': dragging }" @click="document.getElementById('csv_file').click()" @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false" @drop.prevent="dragging = false; if ($event.dataTransfer.files.length) { document.getElementById('csv_file').files = $event.dataTransfer.files; startScan($event.dataTransfer.files[0]) }" :disabled="isScanning" :aria-busy="isScanning"><i class="ph ph-upload-simple" aria-hidden="true"></i><strong x-text="isScanning ? @js(__('Checking your file…')) : @js(__('Choose your CSV file'))">{{ __('Choose your CSV file') }}</strong><span x-text="isScanning ? fileName : @js(__('CSV or TXT · up to 2 MB'))">{{ __('CSV or TXT · up to 2 MB') }}</span></button>
                                <p class="auto-hint">{{ __('Need the right columns? Download the template to get started.') }}</p>
                            </div>
                            <p x-show="error" x-text="error" role="alert" class="auto-error"></p>
@error('csv_file')<p class="auto-error" role="alert">{{ $message }}</p>@enderror
                            <div x-show="currentStep === 2" x-cloak class="auto-review">
                                <div class="auto-file"><div><strong x-text="fileName"></strong><p>{{ __('Preview shows up to 10 valid rows.') }}</p></div><button type="button" @click="currentStep = 1; preview = null; document.getElementById('csv_file').value = ''" :disabled="submitting" class="auto-text-link">{{ __('Change file') }}</button></div>
                                <div class="auto-validation" aria-live="polite"><span><strong x-text="preview?.valid_count || 0"></strong> {{ __('valid rows') }}</span><span><strong x-text="preview?.failed_count || 0"></strong> {{ __('skipped rows') }}</span></div>
<div class="workspace-table-scroll auto-preview-table" role="region" aria-label="{{ __('CSV preview') }}" tabindex="0"><table class="workspace-table"><thead><tr>@foreach(['Company', 'Position', 'Location', 'Platform', 'Status', 'Date'] as $column)<th scope="col">{{ __($column) }}</th>@endforeach</tr></thead><tbody><template x-for="(row, index) in (preview?.rows || [])" :key="index"><tr>@foreach(['company_name', 'position', 'location', 'platform', 'application_status', 'application_date'] as $column)<td x-text="String(row.{{ $column }} || '').{{ $column === 'application_date' ? 'slice(0, 10)' : 'toString()' }}"></td>@endforeach</tr></template></tbody></table></div>
                                <div class="auto-mobile-preview" aria-label="{{ __('CSV preview') }}"><template x-for="(row, index) in (preview?.rows || [])" :key="index"><article><strong x-text="row.position || @js(__('Position not recorded'))"></strong><span x-text="row.company_name || @js(__('Company not recorded'))"></span><dl><div><dt>{{ __('Location') }}</dt><dd x-text="row.location || '—'"></dd></div><div><dt>{{ __('Source') }}</dt><dd x-text="row.platform || '—'"></dd></div><div><dt>{{ __('Status') }}</dt><dd x-text="row.application_status || '—'"></dd></div><div><dt>{{ __('Applied') }}</dt><dd x-text="String(row.application_date || '').slice(0, 10) || '—'"></dd></div></dl></article></template></div>
                                <details x-show="(preview?.errors || []).length" class="auto-errors"><summary>{{ __('Review skipped rows') }}</summary><template x-for="item in (preview?.errors || [])" :key="item.row"><p class="auto-error" x-text="@js(__('Row ')) + item.row + ': ' + item.errors.join('; ')"></p></template></details>
                                <p class="auto-hint">{{ __('Only valid rows will be imported. Importing a file again adds new applications.') }}</p>
                                <div class="auto-form-footer"><span>{{ __('Your file is ready to review.') }}</span><button type="submit" :disabled="!preview || preview.valid_count === 0 || isScanning || submitting" class="auto-button auto-button--primary" x-text="submitting ? @js(__('Importing…')) : @js(__('Import ')) + (preview?.valid_count || 0) + @js(__(' applications'))">{{ __('Review & import') }}</button></div>
                            </div>
                        </form>
                        <aside class="auto-export"><i class="ph ph-download-simple" aria-hidden="true"></i><h3>{{ __('Export applications') }}</h3><p>{{ __('Download a CSV copy of your application history.') }}</p><a href="{{ route('csv.export') }}" class="auto-button auto-button--quiet">{{ __('Export applications') }}<i class="ph ph-arrow-down" aria-hidden="true"></i></a><span class="auto-export-count">{{ __(':value1 applications currently in your tracker.', ['value1' => $totalJobsCount]) }}</span><details><summary>{{ __('Before you upload') }}</summary><ul><li>{{ __('Use the template headers as provided.') }}</li><li>{{ __('Write dates as YYYY-MM-DD.') }}</li><li>{{ __('Use On Process, Accepted, or Rejected for status.') }}</li><li>{{ __('Keep the file size under 2 MB.') }}</li></ul></details></aside>
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
                    this.preview = null; this.error = ''; this.currentStep = 1; this.isScanning = false; this.fileName = '';
                    if (!file) return;
                    this.fileName = file.name; this.isScanning = true;
                    try {
                        const response = await fetch(@json(route('csv.import.preview')), {
                            method: 'POST', credentials: 'same-origin', headers: {'Accept': 'application/json'},
                            body: new FormData(document.getElementById('csvImportForm')),
                        });
                        const result = await response.json();
                        if (number !== this.requestNumber) return;
                        if (!response.ok) throw new Error(result.errors?.csv_file?.join(' ') || result.message || @js(__('Could not validate this file. Try again.')));
                        this.preview = result; this.currentStep = 2;
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
