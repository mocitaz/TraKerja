<article class="ai-message ai-message--response ai-run-panel" x-data="studioRunStatus(@js(route('ai-studio.status', $run->id)), @js($run->status), @js($run->credit_state))">
    <div class="ai-message-label"><x-trakerja-ai-mark /> {{ $labels[$run->task] }}</div>
    <div class="ai-result-title"><h2>{{ $run->title }}</h2><span x-text="labels[status]">{{ ucfirst($run->status) }}</span></div>
    <p class="ai-run-description" x-show="status !== 'failed'">Your request is saved. Processing continues when you leave this page; return from History to see the result.</p>
    <ol x-show="status !== 'failed'" class="ai-run-steps" aria-label="Request progress"><li>Request saved</li><li :class="{'is-current': status === 'processing'}">{{ $run->task === 'analyzer' ? 'Reviewing CV and requirements' : 'Writing your draft' }}</li><li>Validating and saving result</li></ol>
    <p class="ai-output-notice" x-show="status === 'failed'" x-cloak x-text="message || @js($run->error_message)"></p>
    <p class="ai-field-hint">Credit status: <strong x-text="credit === 'none' ? 'No credit charged' : credit">{{ $run->credit_state === 'none' ? 'No credit charged' : $run->credit_state }}</strong></p>
    <p class="ai-composer-error" role="alert" x-show="error" x-cloak x-text="error"></p>
    <button type="button" class="ai-text-action" x-show="error" x-cloak @click="poll()">Check status again</button>
    <form method="POST" action="{{ route('ai-studio.retry', $run->id) }}" x-show="status === 'failed'" x-cloak x-data="{ retrying: false }" @submit="if(retrying) $event.preventDefault(); else retrying = true;">
        @csrf<input type="hidden" name="request_key" value="{{ (string) \Illuminate\Support\Str::uuid() }}"><button type="submit" class="ai-text-action" :disabled="retrying">{{ $metered && $run->task !== 'outreach' ? 'Retry · 1 credit reserved' : 'Retry request' }}</button>
    </form>
</article>
