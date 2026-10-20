@php
    $featuredCompanies = $summary['companies']->sortByDesc('total')->take(5)->values();
@endphp
<section class="insights-panel insights-companies" aria-labelledby="insights-companies-title">
    <header class="insights-panel-heading"><div><h2 id="insights-companies-title">{{ __('Companies on your radar') }}</h2><p>{{ __('Most applications') }}</p></div><span class="insights-count">{{ __('Companies') }} · {{ $summary['companies']->count() }}</span></header>
    @if($summary['companies']->isNotEmpty())
        <ol class="insights-company-list">
            @foreach($featuredCompanies as $index => $company)
                @php
                    $companyWords = preg_split('/\s+/u', preg_replace('/^(?:(?:PT|CV)\.?\s+)+/iu', '', trim($company['name'])), -1, PREG_SPLIT_NO_EMPTY);
                    $companyInitials = mb_strtoupper(count($companyWords) > 1 ? mb_substr($companyWords[0], 0, 1).mb_substr($companyWords[1], 0, 1) : mb_substr($companyWords[0] ?? $company['name'], 0, 2));
                @endphp
                <li>
                    <div class="insights-company-choice">
                        <span class="insights-company-initial" aria-hidden="true">{{ $companyInitials }}</span>
                        <span class="insights-company-copy"><strong>{{ $company['name'] }}</strong></span>
                        <strong class="insights-company-total">{{ $company['total'] }}</strong>
                    </div>
                </li>
            @endforeach
        </ol>
        <details class="insights-data"><summary>{{ __('All :value1', ['value1' => strtolower(__('Companies'))]) }}</summary>
            @include('summary.report.records', ['rows' => $summary['companies'], 'label' => 'Companies'])
            <p class="insights-note">{{ __('Interview-stage counts use the current recorded HR / User Interview stage. Archived records remain part of this analysis.') }}</p>
        </details>
    @else
        <div class="insights-empty insights-empty--small"><h3>{{ __('No records in this period.') }}</h3><p>{{ __('Your saved applications will populate this analysis.') }}</p></div>
    @endif
</section>
