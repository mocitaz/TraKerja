@php
    // The review is shown in the member's language: a saved translation when there is one. Until it exists, the
    // labels follow the review's own language so the page never mixes two languages.
    $stored = (array) $selected->analysis_result;
    $uiLocale = app()->getLocale();
    $review = $stored['translations'][$uiLocale] ?? $stored;
    $reviewLocale = $review['language'] ?? $uiLocale;
    $needsTranslation = ! isset($stored['translations'][$uiLocale]) && $selected->exists && $selected->user_id === auth()->id()
        && ($reviewLocale !== $uiLocale || \App\Services\StudioAiService::isMixed($review, $uiLocale));
    $languageName = $uiLocale === 'id' ? 'Bahasa Indonesia' : 'English';
    $translateTexts = [
        'busy' => __('Translating this review into :language…', ['language' => $languageName]),
        'failed' => __('The translation could not be completed. Please try again later.'),
        'retry' => __('Try again'),
    ];
    if ($reviewLocale !== $uiLocale) {
        app()->setLocale($reviewLocale);
    }
    $verdicts = ['strong' => __('Ready to shortlist'), 'polish' => __('Polish before applying'), 'not_yet' => __('Not a fit yet for this role')];
    $dimensions = ['skills' => __('Skill match'), 'seniority' => __('Level & scope'), 'impact' => __('Measurable impact'), 'leadership' => __('Leadership & ownership'), 'clarity' => __('Clarity & ATS format')];
    $statuses = ['supported' => __('Supported'), 'partial' => __('Partial evidence'), 'not_found' => __('Not in CV')];
    $priorities = ['now' => __('Do now'), 'before_applying' => __('Before you apply'), 'long_term' => __('Longer term')];
    $priorityHints = ['now' => __('About 15 minutes'), 'before_applying' => __('Needs some work'), 'long_term' => __('Build over time')];
    $verdict = $verdicts[$review['verdict']] ?? $verdicts['polish'];
    $score = (int) $review['skor_kecocokan'];
    $role = trim((string) ($review['target_role'] ?? '')) ?: __('CV review');
    $impression = $review['first_impression'] ?? [];
    $findings = collect($review['findings'] ?? []);
    $musts = $findings->where('priority', 'must');
    $keywords = ['present' => $review['keywords']['present'] ?? [], 'missing' => $review['keywords']['missing'] ?? []];
    $keywordTotal = count($keywords['present']) + count($keywords['missing']);
    $actions = collect($review['actions'] ?? [])->groupBy('priority');
    $flags = $review['red_flags'] ?? [];
    $thin = in_array($review['jd_quality'] ?? 'full', ['thin', 'none'], true);
    $owner = $selected->user?->name ?? auth()->user()?->name;
    $date = $selected->created_at?->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d F Y');
    $level = fn (int $value) => $value >= 70 ? 'good' : ($value >= 45 ? 'fair' : 'low');
    $fileName = \Illuminate\Support\Str::limit(__('CV Review').' - '.(trim((string) ($review['target_role'] ?? '')) ?: pathinfo((string) $selected->resume_file_name, PATHINFO_FILENAME)).' - TraKerja', 120, '');

    // The whole review as plain text, for "Copy review".
    $plain = collect([
        __('CV Review').' · '.$role.' · '.$date,
        $verdict.' · '.$score.'/100',
        $review['verdict_reason'] ?? '',
        '',
        __('Score breakdown').':',
        ...collect($review['score_breakdown'] ?? [])->map(fn ($d) => '- '.($dimensions[$d['dimension']] ?? $d['dimension']).' '.$d['score'].'/100: '.$d['note']),
        '',
        __('What already sells').':',
        ...collect($review['strengths'] ?? [])->map(fn ($s) => '- '.$s['point'].' ('.$s['evidence'].')'),
        '',
        __('Action plan').':',
        ...collect($review['actions'] ?? [])->map(fn ($a) => '- ['.($priorities[$a['priority']] ?? $a['priority']).'] '.$a['action']),
        '',
        __('Suggested rewrites').':',
        ...collect($review['rewrites'] ?? [])->map(fn ($r) => '- '.__('Before').': '.$r['before']."\n  ".__('After').': '.$r['after']),
    ])->implode("\n");
    $section = 0;
@endphp
@if($needsTranslation)
    <div class="cvr-translate" role="status" x-data="{
            state: 'busy',
            async run() {
                this.state = 'busy';
                try {
                    const response = await fetch(@js(route('ai-analyzer.translate', $selected)), { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ?? '', Accept: 'application/json' }, credentials: 'same-origin' });
                    if (!response.ok) throw new Error();
                    window.location.reload();
                } catch { this.state = 'failed'; }
            },
        }" x-init="run()">
        <span class="cvr-spinner" x-show="state === 'busy'" aria-hidden="true"></span>
        <p x-show="state === 'busy'">{{ $translateTexts['busy'] }}</p>
        <p x-show="state === 'failed'" x-cloak>{{ $translateTexts['failed'] }} <button type="button" @click="run()">{{ $translateTexts['retry'] }}</button></p>
    </div>
@endif
<article class="cvr" lang="{{ $reviewLocale }}" x-data="{
        copied: null, title: null,
        copy(text, key) { navigator.clipboard?.writeText(text).then(() => { this.copied = key; setTimeout(() => { if (this.copied === key) this.copied = null }, 1600) }) },
        init() {
            window.addEventListener('beforeprint', () => { this.title = document.title; document.title = @js($fileName) });
            window.addEventListener('afterprint', () => { if (this.title) document.title = this.title });
        },
    }" aria-labelledby="cvr-title">
    <style>
        @page { size: A4; margin: 16mm 15mm 18mm; @bottom-left { content: "TraKerja · trakerja.com"; font: 7.5pt system-ui, sans-serif; color: #8a8794; } @bottom-right { content: "{{ __('Page') }} " counter(page) " / " counter(pages); font: 7.5pt system-ui, sans-serif; color: #8a8794; } }
    </style>

    {{-- Letterhead, printed only --}}
    <div class="cvr-letterhead" aria-hidden="true">
        <div class="cvr-brand"><img src="{{ asset('images/icon.png') }}" alt="" width="26" height="26"><span>TraKerja</span></div>
        <div class="cvr-letterhead-meta"><strong>{{ __('CV Review Report') }}</strong><span>{{ $date }}</span></div>
    </div>

    <header class="cvr-head">
        <div class="cvr-head-copy">
            <p class="cvr-eyebrow">{{ __('CV Review') }} <span aria-hidden="true">·</span> {{ $date }}</p>
            <h2 id="cvr-title">{{ $role }}</h2>
            <p class="cvr-meta">
                @if($owner)<span><i class="ph ph-user" aria-hidden="true"></i>{{ $owner }}</span>@endif
                <span><i class="ph ph-file-pdf" aria-hidden="true"></i>{{ $selected->resume_file_name }}</span>
            </p>
        </div>
        <div class="cvr-head-actions">
            <button type="button" @click="copy(@js($plain), 'all')"><i class="ph" :class="copied === 'all' ? 'ph-check' : 'ph-copy'" aria-hidden="true"></i><span x-text="copied === 'all' ? @js(__('Copied')) : @js(__('Copy review'))">{{ __('Copy review') }}</span></button>
            <button type="button" class="is-primary" @click="window.print()"><i class="ph ph-download-simple" aria-hidden="true"></i><span>{{ __('Download PDF') }}</span></button>
        </div>
    </header>

    {{-- Verdict and key numbers --}}
    <section class="cvr-hero" data-level="{{ $level($score) }}">
        <div class="cvr-score">
            <p class="cvr-score-num"><strong>{{ $score }}</strong><span>/100</span></p>
            <div class="cvr-meter" role="img" aria-label="{{ __('Estimated match :score out of 100', ['score' => $score]) }}"><i style="width: {{ $score }}%"></i></div>
            <p class="cvr-score-label">{{ __('Estimated match') }}</p>
        </div>
        <div class="cvr-verdict">
            <p class="cvr-verdict-label" data-verdict="{{ $review['verdict'] }}"><i aria-hidden="true"></i>{{ $verdict }}</p>
            <p class="cvr-lead">{{ $review['verdict_reason'] }}</p>
        </div>
    </section>
    <dl class="cvr-stats">
        <div><dt>{{ __('Must-haves met') }}</dt><dd>{{ $musts->where('status', 'supported')->count() }}<span>/{{ $musts->count() }}</span></dd></div>
        <div><dt>{{ __('Keywords in CV') }}</dt><dd>{{ count($keywords['present']) }}<span>/{{ $keywordTotal }}</span></dd></div>
        <div><dt>{{ __('Quick fixes') }}</dt><dd>{{ ($actions['now'] ?? collect())->count() }}</dd></div>
        <div><dt>{{ __('Recruiter flags') }}</dt><dd>{{ count($flags) }}</dd></div>
    </dl>
    @if($thin)
        <p class="cvr-note"><i class="ph ph-info" aria-hidden="true"></i><span><strong>{{ __('Based on a short job description.') }}</strong> {{ __('Requirements marked “assumed” are typical for this role, not from a real job post. Paste the full job description for a sharper review.') }}</span></p>
    @endif

    <div class="cvr-columns">
        <section class="cvr-block">
            <h3><span>{{ sprintf('%02d', ++$section) }}</span>{{ __('First impression') }}</h3>
            <p class="cvr-block-sub">{{ __('What a recruiter takes away in the first six seconds.') }}</p>
            <dl class="cvr-facts">
                <div><dt>{{ __('Reads as') }}</dt><dd>{{ $impression['reads_as'] ?? '' }}</dd></div>
                <div><dt>{{ __('Stands out') }}</dt><dd>{{ $impression['standout'] ?? '' }}</dd></div>
                @if(!empty($impression['mismatch']))<div class="is-gap"><dt>{{ __('Gap with the role') }}</dt><dd>{{ $impression['mismatch'] }}</dd></div>@endif
            </dl>
        </section>
        <section class="cvr-block">
            <h3><span>{{ sprintf('%02d', ++$section) }}</span>{{ __('Score breakdown') }}</h3>
            <p class="cvr-block-sub">{{ __('Weighted into the total: skills 30%, level 20%, impact 20%, leadership 15%, clarity 15%.') }}</p>
            <ul class="cvr-dimensions">
                @foreach($review['score_breakdown'] ?? [] as $dimension)
                    <li data-level="{{ $level((int) $dimension['score']) }}">
                        <div><span>{{ $dimensions[$dimension['dimension']] ?? $dimension['dimension'] }}</span><strong>{{ $dimension['score'] }}</strong></div>
                        <div class="cvr-meter is-small" aria-hidden="true"><i style="width: {{ (int) $dimension['score'] }}%"></i></div>
                        <p>{{ $dimension['note'] }}</p>
                    </li>
                @endforeach
            </ul>
        </section>
    </div>

    <div class="cvr-columns">
        <section class="cvr-block">
            <h3><span>{{ sprintf('%02d', ++$section) }}</span>{{ __('What already sells') }}</h3>
            <ul class="cvr-points is-good">
                @forelse($review['strengths'] ?? [] as $strength)
                    <li><i class="ph-bold ph-check" aria-hidden="true"></i><div><strong>{{ $strength['point'] }}</strong><p>{{ $strength['evidence'] }}</p></div></li>
                @empty<li class="is-empty">{{ __('No clear strengths for this role yet.') }}</li>@endforelse
            </ul>
        </section>
        <section class="cvr-block">
            <h3><span>{{ sprintf('%02d', ++$section) }}</span>{{ __('What a recruiter may flag') }}</h3>
            <ul class="cvr-points is-flag">
                @forelse($flags as $flag)
                    <li><i class="ph-bold ph-exclamation-mark" aria-hidden="true"></i><div><strong>{{ $flag['issue'] }}</strong><p>{{ $flag['fix'] }}</p></div></li>
                @empty<li class="is-empty">{{ __('Nothing a recruiter would hold against this CV.') }}</li>@endforelse
            </ul>
        </section>
    </div>

    <section class="cvr-block">
        <h3><span>{{ sprintf('%02d', ++$section) }}</span>{{ __('Action plan') }}</h3>
        <div class="cvr-plan">
            @foreach($priorities as $key => $label)
                @if($actions->has($key))
                    <div class="cvr-plan-row" data-priority="{{ $key }}">
                        <div class="cvr-plan-when"><strong>{{ $label }}</strong><span>{{ $priorityHints[$key] }}</span></div>
                        <ul>@foreach($actions[$key] as $action)<li><i aria-hidden="true"></i>{{ $action['action'] }}</li>@endforeach</ul>
                    </div>
                @endif
            @endforeach
        </div>
    </section>

    @if(!empty($review['rewrites']))
        <section class="cvr-block">
            <h3><span>{{ sprintf('%02d', ++$section) }}</span>{{ __('Suggested rewrites') }}</h3>
            <p class="cvr-block-sub">{{ __('Built only from your CV. Fill in anything in [brackets] with your real numbers before using it.') }}</p>
            <div class="cvr-rewrites">
                @foreach($review['rewrites'] as $i => $rewrite)
                    <div class="cvr-rewrite">
                        <div class="cvr-rewrite-before"><span>{{ __('Current') }}</span><p>{{ $rewrite['before'] }}</p></div>
                        <div class="cvr-rewrite-after">
                            <span>{{ __('Suggested') }}</span><p>{{ $rewrite['after'] }}</p>
                            @if($rewrite['why'])<small>{{ $rewrite['why'] }}</small>@endif
                            <button type="button" class="cvr-copy" @click="copy(@js($rewrite['after']), 'rw{{ $i }}')"><i class="ph" :class="copied === 'rw{{ $i }}' ? 'ph-check' : 'ph-copy'" aria-hidden="true"></i><span x-text="copied === 'rw{{ $i }}' ? @js(__('Copied')) : @js(__('Copy'))">{{ __('Copy') }}</span></button>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <section class="cvr-block">
        <h3><span>{{ sprintf('%02d', ++$section) }}</span>{{ __('Requirement by requirement') }}</h3>
        <p class="cvr-block-sub cvr-legend">
            <span data-status="supported"><i aria-hidden="true"></i>{{ $statuses['supported'] }} {{ $findings->where('status', 'supported')->count() }}</span>
            <span data-status="partial"><i aria-hidden="true"></i>{{ $statuses['partial'] }} {{ $findings->where('status', 'partial')->count() }}</span>
            <span data-status="not_found"><i aria-hidden="true"></i>{{ $statuses['not_found'] }} {{ $findings->where('status', 'not_found')->count() }}</span>
        </p>
        <ol class="cvr-reqs">
            @foreach($findings as $finding)
                <li class="cvr-req">
                    <div class="cvr-req-head">
                        <span class="cvr-status" data-status="{{ $finding['status'] }}"><i aria-hidden="true"></i>{{ $statuses[$finding['status']] ?? $finding['status'] }}</span>
                        <h4>{{ $finding['requirement'] }}</h4>
                        <span class="cvr-req-tags">{{ $finding['priority'] === 'must' ? __('Must-have') : __('Nice to have') }}@if($finding['source'] === 'inferred')<span aria-hidden="true"> · </span>{{ __('assumed') }}@endif</span>
                    </div>
                    <dl class="cvr-req-body">
                        <div><dt>{{ __('What your CV shows') }}</dt><dd>{{ $finding['evidence'] }}</dd></div>
                        <div><dt>{{ __('How to improve') }}</dt><dd>{{ $finding['recommendation'] }}</dd></div>
                    </dl>
                </li>
            @endforeach
        </ol>
    </section>

    @if($keywordTotal)
        <section class="cvr-block">
            <h3><span>{{ sprintf('%02d', ++$section) }}</span>{{ __('ATS keywords') }}</h3>
            <p class="cvr-block-sub">{{ __(':found of :total keywords for this role appear in your CV. Add missing ones only where they are true for you.', ['found' => count($keywords['present']), 'total' => $keywordTotal]) }}</p>
            <div class="cvr-keywords">
                @foreach($keywords['present'] as $keyword)<span class="is-present"><i class="ph-bold ph-check" aria-hidden="true"></i>{{ $keyword }}</span>@endforeach
                @foreach($keywords['missing'] as $keyword)<span class="is-missing"><i class="ph-bold ph-plus" aria-hidden="true"></i>{{ $keyword }}</span>@endforeach
            </div>
        </section>
    @endif

    @if(!empty($review['interview_questions']))
        <section class="cvr-block">
            <h3><span>{{ sprintf('%02d', ++$section) }}</span>{{ __('Questions to prepare for') }}</h3>
            <ol class="cvr-questions">
                @foreach($review['interview_questions'] as $question)
                    <li>
                        <h4>{{ $question['question'] }}</h4>
                        <p><span>{{ __('Why they ask') }}</span>{{ $question['why'] }}</p>
                        <p><span>{{ __('How to answer') }}</span>{{ $question['how_to_answer'] }}</p>
                    </li>
                @endforeach
            </ol>
        </section>
    @endif

    <footer class="cvr-foot">
        @if(!empty($review['limitations']))
            <p><strong>{{ __('Review limitations') }}.</strong> {{ implode(' ', $review['limitations']) }}</p>
        @endif
        <p>{{ __('A recruiter-style review of this CV against the supplied role. The score is an editorial estimate, not an ATS score or a hiring prediction.') }}</p>
        <p class="cvr-print-only">{{ __('Generated with TraKerja AI Studio') }} · trakerja.com</p>
    </footer>
</article>
@php app()->setLocale($uiLocale); @endphp
