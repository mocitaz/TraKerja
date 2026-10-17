@php($review = $selected->analysis_result)
<div class="ai-review-summary">
    <div><span>{{ __('Estimated match') }}</span><strong>{{ $review['skor_kecocokan'] }}<small>/100</small></strong></div>
    <p>{{ $review['ringkasan'] }}</p>
</div>
<p class="ai-field-hint">{{ __('An editorial comparison with the supplied requirements, not an ATS score or hiring prediction.') }}</p>
<section class="ai-review-section"><h3>{{ __('Recommended next steps') }}</h3><ol>@foreach($review['rencana_aksi'] as $action)<li>{{ $action }}</li>@endforeach</ol></section>
<section class="ai-review-section"><h3>{{ __('Requirement by requirement') }}</h3>
    @foreach($review['findings'] as $finding)
        <article class="ai-review-finding"><header><h4>{{ $finding['requirement'] }}</h4><span data-evidence="{{ $finding['status'] }}">{{ ['supported' => __('Supported'), 'partial' => __('Partial evidence'), 'not_found' => __('Not in CV')][$finding['status']] }}</span></header><dl><div><dt>{{ __('CV evidence') }}</dt><dd>{{ $finding['evidence'] }}</dd></div><div><dt>{{ __('Suggested improvement') }}</dt><dd>{{ $finding['recommendation'] }}</dd></div></dl></article>
    @endforeach
</section>
@if($review['limitations'])<details class="ai-context-review"><summary>{{ __('Review limitations') }}</summary><ul>@foreach($review['limitations'] as $limitation)<li>{{ $limitation }}</li>@endforeach</ul></details>@endif
