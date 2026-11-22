@php
    $when = $selected->created_at->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M Y, H:i');
    $stored = $task === 'analyzer' ? (array) $selected->analysis_result : [];
    $review = $stored['translations'][app()->getLocale()] ?? $stored;
    $isV3 = ($stored['schema_version'] ?? null) === 3;
    $verdicts = ['strong' => __('Ready to shortlist'), 'polish' => __('Polish before applying'), 'not_yet' => __('Not a fit yet for this role')];
    $docTitle = $task === 'outreach' ? __('Your outreach draft') : __('Your cover letter');
    $versionIndex = $versions->search(fn ($v) => $v->id === $selected->id);
    $nara = fn (string $prompt) => "window.dispatchEvent(new CustomEvent('open-nara', { detail: { prompt: ".json_encode($prompt, JSON_HEX_APOS | JSON_HEX_QUOT)." } }))";
@endphp
<div class="aiw-convo" :class="[artifactOpen ? 'has-artifact' : 'is-artifact-closed', artifactWide && artifactOpen ? 'is-artifact-wide' : '']">
    <div class="aiw-thread">
        {{-- What the member asked --}}
        <div class="aiw-msg aiw-msg--user">
            <div class="aiw-bubble">
                <span class="aiw-bubble-tool"><i class="ph {{ $icons[$task] }}" aria-hidden="true"></i>{{ $labels[$task] }}</span>
                @if($task === 'analyzer')
                    <p><i class="ph ph-file-pdf" aria-hidden="true"></i> {{ $selected->resume_file_name }}</p>
                    @if($selected->job_description)<details><summary>{{ __('View job description') }}</summary><p class="aiw-bubble-jd">{{ $selected->job_description }}</p></details>@endif
                @elseif($task === 'photo')
                    <p><i class="ph ph-image" aria-hidden="true"></i> {{ $selected->original_photo_name }} · {{ $selected->type === 'remove_bg' ? __('Remove background') : __('Enhance portrait') }}</p>
                @else
                    <p>{{ $selected->job_title }} · {{ $selected->company_name }}</p>
                    @if($selected->revision_note ?? null)<p class="aiw-bubble-note"><i class="ph ph-arrow-bend-down-right" aria-hidden="true"></i> {{ $selected->revision_note }}</p>@endif
                    @if($selected->job_description ?? null)<details><summary>{{ __('View job description') }}</summary><p class="aiw-bubble-jd">{{ $selected->job_description }}</p></details>@endif
                @endif
            </div>
            <time class="aiw-msg-time">{{ $when }} WIB</time>
        </div>

        {{-- The answer --}}
        <div class="aiw-msg aiw-msg--ai">
            <span class="aiw-avatar"><x-trakerja-ai-mark /><span class="sr-only">{{ $task === 'outreach' && !$generatedWithAi ? __('TraKerja · Message template') : ($selected->exists ? __('TraKerja AI · Saved result') : __('TraKerja AI · Unsaved result')) }}</span></span>
            <div class="aiw-answer">
                @if(!$selected->exists)<p class="aiw-alert">{{ __('The analysis completed, but could not be saved. Keep a copy of this page; this result will not appear in your history.') }}</p>@endif

                @if($task === 'analyzer')
                    @php
                        // Until the review is translated, its summary stays in the review's language (never mixed).
                        $uiLocale = app()->getLocale();
                        $reviewLocale = $review['language'] ?? $uiLocale;
                        if ($isV3 && $reviewLocale !== $uiLocale) { app()->setLocale($reviewLocale); }
                        $verdicts = ['strong' => __('Ready to shortlist'), 'polish' => __('Polish before applying'), 'not_yet' => __('Not a fit yet for this role')];
                    @endphp
                    @if($isV3)
                        <p>{{ __('Your CV review is ready. Here is the short version; the full report is on the right.') }}</p>
                        <button type="button" class="aiw-artifact-card" @click="showArtifact()">
                            <span class="aiw-artifact-score"><strong>{{ (int) $review['skor_kecocokan'] }}</strong><small>/100</small></span>
                            <span class="aiw-artifact-copy"><strong>{{ $verdicts[$review['verdict']] ?? '' }}</strong><span>{{ \Illuminate\Support\Str::limit($review['verdict_reason'] ?? '', 110) }}</span></span>
                            <i class="ph ph-arrow-up-right" aria-hidden="true"></i>
                        </button>
                        @php $firstSteps = collect($review['actions'] ?? [])->where('priority', 'now')->take(2); @endphp
                        @if($firstSteps->isNotEmpty())
                            <p class="aiw-answer-label">{{ __('Start with these:') }}</p>
                            <ol class="aiw-answer-list">@foreach($firstSteps as $step)<li>{{ $step['action'] }}</li>@endforeach</ol>
                        @endif
                    @else
                        <p>{{ __('Your resume review') }}. {{ __('Focus on the most relevant gaps, then review the evidence behind each recommendation.') }}</p>
                    @endif
                    @php app()->setLocale($uiLocale); @endphp
                @elseif(in_array($task, ['cover-letter', 'outreach'], true))
                    <p>{{ $task === 'outreach' ? __('Your message for :company is ready.', ['company' => $selected->company_name]) : __('Your cover letter for :company is ready.', ['company' => $selected->company_name]) }} {{ __('Edit it right in the document, or ask for a revision below.') }}</p>
                    <button type="button" class="aiw-doc-card" @click="showArtifact()" :aria-pressed="artifactOpen.toString()">
                        <span class="aiw-doc-card-icon"><i class="ph {{ $icons[$task] }}" aria-hidden="true"></i></span>
                        <span class="aiw-artifact-copy"><strong>{{ $docTitle }}</strong><span>{{ $selected->company_name }} · {{ $selected->job_title }}@if($versions->count() > 1) · {{ __('Version :n', ['n' => $versionIndex + 1]) }}@endif</span></span>
                        <i class="ph" :class="artifactOpen ? 'ph-arrow-right' : 'ph-arrow-up-right'" aria-hidden="true"></i>
                    </button>
                    @if($task === 'outreach' && !$generatedWithAi)<p class="aiw-alert">{{ __('A saved template. Edit it to reflect your experience before sending.') }}</p>@endif
                    @if($versions->count() > 1)
                        <nav class="aiw-versions" aria-label="{{ __('Versions') }}">
                            <span>{{ __('Version :n of :total', ['n' => $versionIndex + 1, 'total' => $versions->count()]) }}</span>
                            @foreach($versions as $i => $version)
                                <a href="{{ route('ai-studio.index', ['task' => $task, 'id' => $version->id]) }}" @if($version->id === $selected->id) aria-current="page" @endif title="{{ $version->revision_note ?: __('First draft') }}">{{ $i + 1 }}</a>
                            @endforeach
                        </nav>
                    @endif
                @else
                    <p>{{ $selected->status === 'completed' ? __('Your photo is ready.') : __('Your photo request is saved. Processing continues when you leave this page.') }}</p>
                    <button type="button" class="aiw-doc-card" @click="showArtifact()" :aria-pressed="artifactOpen.toString()">
                        <span class="aiw-doc-card-icon"><i class="ph {{ $icons[$task] }}" aria-hidden="true"></i></span>
                        <span class="aiw-artifact-copy"><strong>{{ __('Photo studio') }}</strong><span>{{ $selected->original_photo_name }}</span></span>
                        <i class="ph" :class="artifactOpen ? 'ph-arrow-right' : 'ph-arrow-up-right'" aria-hidden="true"></i>
                    </button>
                @endif

                {{-- Revise in the chat (letters and messages) --}}
                @if(in_array($task, ['cover-letter', 'outreach'], true) && $selected->exists)
                    <form class="aiw-revise" @submit.prevent="revise(@js($task), {{ $selected->id }}, reviseText)">
                        <p class="aiw-answer-label">{{ __('Ask for a revision') }} <span>· {{ trans_choice('{0} No free revisions left|{1} 1 free revision left|[2,*] :count free revisions left', $revisionsLeft, ['count' => $revisionsLeft]) }}</span></p>
                        @if($revisionsLeft > 0)
                            <div class="aiw-chips">
                                @foreach([__('Make it shorter'), __('More formal'), __('Warmer and more personal'), __('Highlight my strongest achievement')] as $quick)
                                    <button type="button" @click="revise(@js($task), {{ $selected->id }}, @js($quick))" :disabled="revising">{{ $quick }}</button>
                                @endforeach
                            </div>
                            <div class="aiw-revise-box">
                                <input type="text" x-model="reviseText" maxlength="300" placeholder="{{ __('Or describe the change, e.g. mention my React projects') }}" :disabled="revising" aria-label="{{ __('Ask for a revision') }}">
                                <button type="submit" class="aiw-send is-small" :disabled="revising || reviseText.trim().length < 3" aria-label="{{ __('Revise') }}"><i class="ph-bold ph-arrow-up" x-show="!revising" aria-hidden="true"></i><span class="aiw-spinner" x-show="revising" x-cloak aria-hidden="true"></span></button>
                            </div>
                            <p class="aiw-thinking" x-show="revising" x-cloak><span class="aiw-dots" aria-hidden="true"><i></i><i></i><i></i></span>{{ __('Revising your draft…') }}</p>
                            <p class="aiw-alert" role="alert" x-show="reviseError" x-cloak x-text="reviseError"></p>
                        @endif
                    </form>
                @endif

                {{-- Next steps: one tool leads to the next --}}
                @if($selected->exists)
                    <div class="aiw-followups">
                        <span>{{ __('Next steps') }}</span>
                        <div class="aiw-chips">
                            @if($task === 'analyzer')
                                <a href="{{ route('ai-studio.index', ['task' => 'cover-letter', 'from' => 'analyzer:'.$selected->id]) }}"><i class="ph ph-envelope-simple-open" aria-hidden="true"></i>{{ __('Write a cover letter from this review') }}</a>
                                <a href="{{ route('ai-studio.index', ['task' => 'outreach', 'from' => 'analyzer:'.$selected->id]) }}"><i class="ph ph-paper-plane-tilt" aria-hidden="true"></i>{{ __('Message the recruiter') }}</a>
                                <button type="button" onclick="{{ $nara(__('Help me practise interview questions for this role: :role', ['role' => $review['target_role'] ?? $selected->resume_file_name])) }}"><i class="ph ph-microphone" aria-hidden="true"></i>{{ __('Practise the interview with Nara') }}</button>
                                <a href="{{ route('ai-studio.index', ['task' => 'analyzer']) }}"><i class="ph ph-plus" aria-hidden="true"></i>{{ __('Create another review') }}</a>
                            @elseif($task === 'cover-letter')
                                <a href="{{ route('ai-studio.index', ['task' => 'outreach', 'from' => 'cover-letter:'.$selected->id]) }}"><i class="ph ph-paper-plane-tilt" aria-hidden="true"></i>{{ __('Message the recruiter') }}</a>
                                <a href="{{ route('ai-studio.index', ['task' => 'analyzer', 'from' => 'cover-letter:'.$selected->id]) }}"><i class="ph ph-file-magnifying-glass" aria-hidden="true"></i>{{ __('Check my CV for this role') }}</a>
                                <a href="{{ route('ai-studio.index', ['task' => 'cover-letter']) }}"><i class="ph ph-plus" aria-hidden="true"></i>{{ __('Create another draft') }}</a>
                            @elseif($task === 'outreach')
                                <a href="{{ route('ai-studio.index', ['task' => 'cover-letter', 'from' => 'outreach:'.$selected->id]) }}"><i class="ph ph-envelope-simple-open" aria-hidden="true"></i>{{ __('Write a cover letter') }}</a>
                                <a href="{{ route('ai-studio.index', ['task' => 'outreach']) }}"><i class="ph ph-plus" aria-hidden="true"></i>{{ __('Create another draft') }}</a>
                            @else
                                <a href="{{ route('ai-studio.index', ['task' => 'photo']) }}"><i class="ph ph-plus" aria-hidden="true"></i>{{ __('Create another photo') }}</a>
                                <a href="{{ route('profile.edit') }}"><i class="ph ph-user-circle" aria-hidden="true"></i>{{ __('Use it on my profile') }}</a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- The artifact: the document itself --}}
    <aside class="aiw-artifact" id="aiw-artifact" x-ref="artifact" x-show="artifactOpen" aria-label="{{ __('Result') }}">
        <div class="aiw-artifact-bar">
            <span><i class="ph {{ $icons[$task] }}" aria-hidden="true"></i>{{ $labels[$task] }}</span>
            <span class="aiw-artifact-bar-actions">
                <button type="button" class="aiw-icon-btn aiw-wide-toggle" @click="artifactWide = !artifactWide" :aria-pressed="artifactWide.toString()" :aria-label="artifactWide ? @js(__('Show the conversation')) : @js(__('Focus on the document'))" :title="artifactWide ? @js(__('Show the conversation')) : @js(__('Focus on the document'))"><i class="ph" :class="artifactWide ? 'ph-arrows-in-simple' : 'ph-arrows-out-simple'" aria-hidden="true"></i></button>
                <button type="button" class="aiw-icon-btn" @click="closeArtifact()" aria-label="{{ __('Close panel') }}" title="{{ __('Close panel') }}"><i class="ph ph-x" aria-hidden="true"></i></button>
            </span>
        </div>
        @if($task === 'analyzer')
            @if($isV3)
                @include('ai-studio.partials.resume-review-v3')
            @elseif(($selected->analysis_result['schema_version'] ?? null) === 2)
                <div class="aiw-doc"><h2 class="aiw-doc-title">{{ __('Your resume review') }}</h2>@include('ai-studio.partials.resume-review')</div>
            @elseif(!empty($selected->analysis_result))
                <div class="aiw-doc"><h2 class="aiw-doc-title">{{ __('Your resume review') }}</h2><div class="ai-analysis-data">@include('ai-studio.partials.analysis-data', ['data' => $selected->analysis_result, 'depth' => 0])</div></div>
            @else
                <div class="aiw-doc"><p class="aiw-alert">{{ __('This saved response has no analysis details. Start a new request below.') }}</p></div>
            @endif
        @elseif(in_array($task, ['cover-letter', 'outreach'], true))
            @php $filename = ($task === 'outreach' ? 'Outreach_' : 'Cover_Letter_').\Illuminate\Support\Str::slug($selected->company_name.'_'.$selected->job_title, '_').'.txt'; @endphp
            <article class="aiw-doc aiw-letter" x-data="coverLetterDetail(@js($filename))">
                <header class="aiw-doc-head">
                    <div>
                        <p class="aiw-doc-eyebrow">{{ $task === 'outreach' ? $selected->channel : __('Cover letter') }}@if($versions->count() > 1) · {{ __('Version :n', ['n' => $versionIndex + 1]) }}@endif</p>
                        <h2 class="aiw-doc-title">{{ $selected->company_name }} · {{ $selected->job_title }}</h2>
                        <p class="aiw-doc-meta">{{ $docTitle }} · @if($task === 'outreach'){{ $selected->recruiter_name ?: __('Hiring team') }}@else{{ in_array(strtolower($selected->language ?? ''), ['id', 'indonesian']) ? __('Indonesian') : __('English') }}@endif</p>
                    </div>
                    <div class="aiw-doc-actions">
                        <button type="button" @click="copy()" :disabled="copying"><i class="ph" :class="copied ? 'ph-check' : 'ph-copy'" aria-hidden="true"></i><span x-text="copied ? @js(__('Copied')) : @js($task === 'outreach' ? __('Copy draft') : __('Copy letter'))">{{ __('Copy letter') }}</span></button>
                        <button type="button" @click="download()"><i class="ph ph-download-simple" aria-hidden="true"></i><span>TXT</span></button>
                        @if($task === 'cover-letter')<button type="button" class="is-primary" @click="window.print()"><i class="ph ph-file-pdf" aria-hidden="true"></i><span>{{ __('Download PDF') }}</span></button>@endif
                        <button type="button" x-show="copyFailed" x-cloak @click="selectText()">{{ __('Select text') }}</button>
                    </div>
                </header>
                <div class="aiw-letter-paper">
                    <div class="aiw-letter-text" x-ref="content" id="letter-content" contenteditable="plaintext-only" role="textbox" aria-multiline="true" tabindex="0" aria-label="{{ $task === 'outreach' ? __('Outreach draft') : __('Cover letter text') }}">{{ $selected->content }}</div>
                </div>
                <p class="aiw-hint">{{ __('Edit the draft above before copying or downloading. Edits apply to this page only.') }}</p>
                <p class="aiw-hint" role="status" aria-live="polite" x-text="notice"></p>
            </article>
        @elseif($task === 'photo')
            @php
                $imageUrl = is_string($selected->result_url) && filter_var($selected->result_url, FILTER_VALIDATE_URL) && in_array(strtolower(parse_url($selected->result_url, PHP_URL_SCHEME) ?? ''), ['https','http'], true) ? $selected->result_url : null;
                $canRetry = $selected->status === 'failed' && $selected->source_path && \Illuminate\Support\Facades\Storage::disk('local')->exists($selected->source_path);
            @endphp
            <div class="aiw-doc aiw-photo" x-data="photoRequestStatus(@js(route('ai-photo.status', $selected->id)), @js($selected->status))">
                <header class="aiw-doc-head">
                    <div><p class="aiw-doc-eyebrow">{{ __('Photo studio') }}</p><h2 class="aiw-doc-title">{{ $selected->status === 'completed' ? __('Your photo is ready') : __('Your photo request') }}</h2><p class="aiw-doc-meta"><span x-text="({ queued: @js(__('Queued')), processing: @js(__('Processing')), completed: @js(__('Completed')), failed: @js(__('Failed')) })[status] || status">{{ __(ucfirst($selected->status)) }}</span>@if($selected->style_used || $selected->background_used) · {{ $selected->style_used ? str_replace('_', ' ', $selected->style_used) : '' }}{{ $selected->style_used && $selected->background_used ? ', ' : '' }}{{ $selected->background_used ? str_replace('_', ' ', $selected->background_used) : '' }}@endif</p></div>
                    @if($selected->status === 'completed' && $imageUrl)<div class="aiw-doc-actions"><a class="is-primary" href="{{ $imageUrl }}" download target="_blank" rel="noopener noreferrer"><i class="ph ph-download-simple" aria-hidden="true"></i>{{ __('Download photo ↗') }}</a></div>@endif
                </header>
                @if($selected->status === 'completed' && $imageUrl)
                    <div class="aiw-photo-stage"><img src="{{ $imageUrl }}" alt="{{ __('Processed result for :value1', ['value1' => ($selected->original_photo_name)]) }}" loading="lazy"></div>
                    <p class="aiw-hint">{{ __('The image opens from the provider. If it opens in a tab, use your browser’s save image option.') }}</p>
                @elseif($selected->status === 'completed')<p class="aiw-alert">{{ __('The saved image link is unavailable. You can create another request below.') }}</p>
                @elseif($selected->status === 'failed')<div class="aiw-alert" role="status">{{ $selected->error_message ? __($selected->error_message) : __('Processing failed. Please try again.') }}</div>
                @else<div class="aiw-photo-waiting"><span class="aiw-dots" aria-hidden="true"><i></i><i></i><i></i></span><strong>{{ $selected->status === 'queued' ? __('Your photo is in the queue.') : __('Your portrait is being processed.') }}</strong><p>{{ __('This request is saved. You can return from your history while processing continues.') }}</p></div>
                @endif
                @if($selected->photo_analysis)<details class="aiw-details"><summary>{{ __('Photo analysis') }}</summary><div class="ai-analysis-data">@include('ai-studio.partials.analysis-data', ['data' => $selected->photo_analysis, 'depth' => 0])</div></details>@endif
                <p class="aiw-hint">{{ __('Credit status:') }} <strong>{{ $selected->credit_state === 'none' ? __('No credit charged') : $selected->credit_state }}</strong></p>
                <p class="aiw-alert" role="alert" x-show="error" x-cloak x-text="error"></p><button type="button" class="aiw-link" x-show="error" x-cloak @click="poll()">{{ __('Check again') }}</button>
                @if($canRetry)<form method="POST" action="{{ route('ai-photo.retry', $selected->id) }}" x-data="{ retrying: false }" @submit="if(retrying) { $event.preventDefault(); } else { retrying = true; }">@csrf<input type="hidden" name="request_key" value="{{ (string) \Illuminate\Support\Str::uuid() }}"><button class="aiw-link" type="submit" :disabled="retrying" x-text="retrying ? @js(__('Saving request…')) : @js($metered ? __('Try again · 1 credit') : __('Try again'))">{{ __('Try again') }}</button></form>@endif
            </div>
        @endif
    </aside>
</div>
