<x-app-layout>
    @php
        $state = $export->expires_at->isPast() ? 'expired' : $export->status;
        $states = [
            'queued' => ['In the queue', 'Your export is queued', 'Your request is saved. A background worker will prepare your CSV file.'],
            'processing' => ['Processing', 'Preparing your CSV', 'Your data is being exported. You can leave this page and return to check the result.'],
            'ready' => ['Ready to download', 'Your CSV is ready', 'Download your file before it expires. It is available only to your account.'],
            'failed' => ['Failed', 'Export failed', 'We couldn’t prepare this file. Return to the export page to create a new request.'],
            'expired' => ['Expired', 'This export has expired', 'The download window has ended. Create a new export to get an up-to-date file.'],
        ];
        [$label, $title, $description] = $states[$state] ?? ['Unavailable', 'Export unavailable', 'Return to the export page to create a new request.'];
        $returnUrl = $export->kind === 'users' ? route('admin.users') : route('automation.index', ['tab' => 'csv']);
        $active = in_array($state, ['queued', 'processing'], true);
    @endphp
    <main class="workspace-page export-workspace">
        <div class="workspace-page__container">
            <header class="workspace-page__header"><div><p class="workspace-eyebrow">{{ __('Automation · Data export') }}</p><h1>{{ __('Your data, ready to go.') }}</h1><p>{{ __('Track your request and download a private CSV copy.') }}</p></div><a class="workspace-button workspace-button--quiet" href="{{ $returnUrl }}">{{ __('Back to exports ↗') }}</a></header>
            <div class="export-grid">
                <section class="workspace-card export-status" aria-labelledby="export-title" data-export-state="{{ $state }}">
                    <div class="export-status__top"><span class="export-status__badge export-status__badge--{{ $state }}">{{ __($label) }}</span><span class="export-format">{{ __('CSV') }} <span aria-hidden="true">↗</span></span></div>
                    <div role="status" aria-live="polite"><h2 id="export-title">{{ __($title) }}</h2><p>{{ __($description) }}</p></div>
                    @if(in_array($state, ['queued', 'processing', 'ready'], true))
                        <ol class="export-steps" aria-label="{{ __('Export progress') }}">
                            @foreach(['queued' => 'Requested', 'processing' => 'Preparing', 'ready' => 'Ready'] as $step => $stepLabel)
                                @php $done = array_search($step, ['queued', 'processing', 'ready']) <= array_search($state, ['queued', 'processing', 'ready']); @endphp
                                <li class="{{ $done ? 'is-reached' : '' }}" @if($step === $state) aria-current="step" @endif><span aria-hidden="true">{{ $loop->iteration }}</span>{{ __($stepLabel) }}</li>
                            @endforeach
                        </ol>
                    @else
                        <div class="export-resolution"><p>{{ $state === 'expired' ? __('A new export has its own download window.') : __('You can request a new file without changing your application data.') }}</p></div>
                    @endif
                    <div class="export-actions">
                        @if($state === 'ready')
                            <a class="workspace-button workspace-button--primary" href="{{ route('data-exports.download', $export->id) }}">{{ __('Download CSV · :value1 rows ↓', ['value1' => (number_format($export->row_count))]) }}</a>
                        @elseif($active)
                            <a class="workspace-button workspace-button--primary" href="{{ route('data-exports.show', $export->id) }}">{{ __('Check status ↻') }}</a><span>{{ __('Status updates when you refresh.') }}</span>
                        @else
                            <a class="workspace-button workspace-button--primary" href="{{ $returnUrl }}">{{ __('Create a new export ↗') }}</a>
                        @endif
                    </div>
                </section>
                <aside class="workspace-card workspace-card--glass export-details" aria-label="{{ __('Export details') }}">
                    <h2>{{ __('Request details') }}</h2>
                    <dl><div><dt>{{ __('Data') }}</dt><dd>{{ $export->kind === 'users' ? __('User records') : __('Job applications') }}</dd></div><div><dt>{{ __('Format') }}</dt><dd>{{ __('CSV · UTF-8') }}</dd></div><div><dt>{{ __('Requested') }}</dt><dd>{{ __(':value1 WIB', ['value1' => ($export->created_at->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M Y, H:i'))]) }}</dd></div><div><dt>{{ $state === 'expired' ? __('Expired at') : __('Available until') }}</dt><dd>{{ __(':value1 WIB', ['value1' => ($export->expires_at->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M Y, H:i'))]) }}</dd></div>@if($state === 'ready')<div><dt>{{ __('Exported rows') }}</dt><dd>{{ number_format($export->row_count) }}</dd></div>@endif</dl>
                    <p>{{ __('Data is read when the worker processes your request. Downloads expire 24 hours after the request is created.') }}</p>
                </aside>
            </div>
        </div>
    </main>
</x-app-layout>
