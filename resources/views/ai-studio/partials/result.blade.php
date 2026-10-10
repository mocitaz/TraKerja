<div class="ai-message ai-message--request">
    <div class="ai-message-label">You · {{ $selected->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</div>
    <div class="ai-request-bubble"><strong>{{ $labels[$task] }}</strong>
        @if($task === 'analyzer')<p>{{ $selected->resume_file_name }}</p><details><summary>View job description</summary><p>{{ $selected->job_description }}</p></details>
        @elseif($task === 'photo')<p>{{ $selected->original_photo_name }}</p><span>{{ $selected->type === 'remove_bg' ? 'Remove background' : 'Enhance portrait' }}</span>
        @else<p>{{ $selected->job_title }} · {{ $selected->company_name }}</p>@if($selected->job_description)<details><summary>View job description</summary><p>{{ $selected->job_description }}</p></details>@endif
        @endif
    </div>
</div>
<article class="ai-message ai-message--response" aria-label="Saved result">
    <div class="ai-message-label"><x-trakerja-ai-mark /> {{ $task === 'outreach' ? 'TraKerja · Message template' : ($selected->exists ? 'TraKerja AI · Saved result' : 'TraKerja AI · Unsaved result') }}</div>
    @if($task === 'analyzer')
        @if(!$selected->exists)<p class="ai-output-notice">The analysis completed, but could not be saved. Keep a copy of this page; this result will not appear in your history.</p>@endif
        <h2>Your resume review</h2><p class="ai-result-intro">The analysis below is the saved service response. It is guidance, not a hiring decision.</p>
        @if(!empty($selected->analysis_result))
            <div class="ai-analysis-data">@include('ai-studio.partials.analysis-data', ['data' => $selected->analysis_result, 'depth' => 0])</div>
        @else<p class="ai-output-notice">This saved response has no analysis details. Start a new request below.</p>@endif
    @elseif(in_array($task, ['cover-letter', 'outreach'], true))
        @php $filename = ($task === 'outreach' ? 'Outreach_' : 'Cover_Letter_').\Illuminate\Support\Str::slug($selected->company_name.'_'.$selected->job_title, '_').'.txt'; @endphp
        <div x-data="coverLetterDetail(@js($filename))" class="ai-letter-result">
            <div class="ai-result-title"><h2>{{ $task === 'outreach' ? 'Your outreach draft' : 'Your cover letter' }}</h2><span>{{ $task === 'outreach' ? $selected->channel : (in_array(strtolower($selected->language ?? ''), ['id','indonesian']) ? 'Indonesian' : 'English') }}</span></div>
            @if($task === 'outreach')<p class="ai-output-notice">A saved template. Edit it to reflect your experience before sending.</p>@endif
            <div class="ai-letter-text" x-ref="content" id="letter-content" tabindex="0" aria-label="{{ $task === 'outreach' ? 'Outreach draft' : 'Cover letter text' }}">{{ $selected->content }}</div>
            <div class="ai-result-actions"><button type="button" @click="copy()" :disabled="copying"><span x-text="copied ? 'Copied' : @js($task === 'outreach' ? 'Copy draft' : 'Copy letter')">Copy letter</span></button><button type="button" @click="download()">Download TXT ↓</button>@if($task === 'cover-letter')<button type="button" @click="window.print()">Print letter ↗</button>@endif<button type="button" x-show="copyFailed" x-cloak @click="selectText()">Select text</button></div><p class="ai-inline-feedback" role="status" aria-live="polite" x-text="notice"></p>
        </div>
    @elseif($task === 'photo')
        @php
            $imageUrl = is_string($selected->result_url) && filter_var($selected->result_url, FILTER_VALIDATE_URL) && in_array(strtolower(parse_url($selected->result_url, PHP_URL_SCHEME) ?? ''), ['https','http'], true) ? $selected->result_url : null;
            $canRetry = $selected->status === 'failed' && $selected->source_path && \Illuminate\Support\Facades\Storage::disk('local')->exists($selected->source_path);
        @endphp
        <div class="ai-photo-result" x-data="photoRequestStatus(@js(route('ai-photo.status', $selected->id)), @js($selected->status))">
            <div class="ai-result-title"><h2>{{ $selected->status === 'completed' ? 'Your photo is ready' : 'Your photo request' }}</h2><span class="ai-photo-state" x-text="labels[status]">{{ ucfirst($selected->status) }}</span></div>
            @if($selected->status === 'completed' && $imageUrl)
                <div class="ai-photo-preview"><img src="{{ $imageUrl }}" alt="Processed result for {{ $selected->original_photo_name }}" loading="lazy"></div><div class="ai-result-actions"><a href="{{ $imageUrl }}" download target="_blank" rel="noopener noreferrer">Download photo ↗</a></div><p class="ai-field-hint">The image opens from the provider. If it opens in a tab, use your browser’s save image option.</p>
            @elseif($selected->status === 'completed')<p class="ai-output-notice">The saved image link is unavailable. You can create another request below.</p>
            @elseif($selected->status === 'failed')<div class="ai-output-notice" role="status">{{ $selected->error_message ?: 'Processing failed. Please try again.' }}</div>
            @else<div class="ai-photo-waiting"><span class="ai-pending-dot" aria-hidden="true"></span><strong>{{ $selected->status === 'queued' ? 'Your photo is in the queue.' : 'Your portrait is being processed.' }}</strong><p>This request is saved. You can return from your history while processing continues.</p></div>
            @endif
            @if($selected->style_used || $selected->background_used)<p class="ai-field-hint">{{ $selected->style_used ? 'Style: '.str_replace('_', ' ', $selected->style_used).' · ' : '' }}{{ $selected->background_used ? 'Background: '.str_replace('_', ' ', $selected->background_used) : '' }}</p>@endif
            @if($selected->photo_analysis)<details class="ai-composer-options"><summary>Photo analysis</summary><div class="ai-analysis-data">@include('ai-studio.partials.analysis-data', ['data' => $selected->photo_analysis, 'depth' => 0])</div></details>@endif
            <p class="ai-photo-credit">Credit status: <strong>{{ $selected->credit_state === 'none' ? 'No credit charged' : $selected->credit_state }}</strong></p>
            <p class="ai-composer-error" role="alert" x-show="error" x-cloak x-text="error"></p><button type="button" class="ai-text-action" x-show="error" x-cloak @click="poll()">Check again</button>
            @if($canRetry)<form method="POST" action="{{ route('ai-photo.retry', $selected->id) }}" x-data="{ retrying: false }" @submit="if(retrying) { $event.preventDefault(); } else { retrying = true; }">@csrf<input type="hidden" name="request_key" value="{{ (string) \Illuminate\Support\Str::uuid() }}"><button class="ai-text-action" type="submit" :disabled="retrying" x-text="retrying ? 'Saving request…' : @js($metered ? 'Try again · 1 credit' : 'Try again')">Try again</button></form>@endif
        </div>
    @endif
</article>
<div class="ai-thread-divider"><span>Start another request below</span></div>
