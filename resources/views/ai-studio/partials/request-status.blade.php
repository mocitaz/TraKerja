@php
    $steps = match ($run->task) {
        'analyzer' => [__('Reading your CV'), __('Matching it against the job requirements'), __('Writing your review')],
        'photo' => [__('Checking your photo'), __('Processing the portrait'), __('Saving the result')],
        default => [__('Reading the role and your profile'), __('Writing your draft'), __('Checking the facts')],
    };
@endphp
<div class="aiw-convo">
    <div class="aiw-thread">
        <div class="aiw-msg aiw-msg--user">
            <div class="aiw-bubble">
                <span class="aiw-bubble-tool"><i class="ph {{ $icons[$run->task] }}" aria-hidden="true"></i>{{ $labels[$run->task] }}</span>
                <p>{{ $run->title }}</p>
            </div>
            <time class="aiw-msg-time">{{ $run->created_at->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M Y, H:i') }} WIB</time>
        </div>
        <div class="aiw-msg aiw-msg--ai" x-data="studioRunStatus(@js(route('ai-studio.status', $run->id)), @js($run->status), @js($run->credit_state))">
            <span class="aiw-avatar"><x-trakerja-ai-mark /><span class="sr-only">{{ $labels[$run->task] }}</span></span>
            <div class="aiw-answer">
                <template x-if="status !== 'failed'">
                    <div>
                        <p class="aiw-thinking"><span class="aiw-dots" aria-hidden="true"><i></i><i></i><i></i></span><span x-text="status === 'processing' ? @js(__('Working on it…')) : @js(__('In the queue…'))">{{ __('Working on it…') }}</span></p>
                        <ol class="aiw-steps" aria-label="{{ __('Request progress') }}">
                            <li class="is-done"><i class="ph-bold ph-check" aria-hidden="true"></i>{{ __('Request saved') }}</li>
                            @foreach($steps as $i => $step)
                                <li :class="status === 'processing' ? @js($i === 0 ? 'is-done' : ($i === 1 ? 'is-current' : '')) : ''"><i class="ph" :class="status === 'processing' && {{ $i }} === 0 ? 'ph-bold ph-check' : 'ph-circle'" aria-hidden="true"></i>{{ $step }}</li>
                            @endforeach
                        </ol>
                        <p class="aiw-hint">{{ __('Your request is saved. Processing continues when you leave this page; return from History to see the result.') }}</p>
                    </div>
                </template>
                <div x-show="status === 'failed'" x-cloak>
                    <p class="aiw-alert" x-text="message || @js(__((string) $run->error_message))">{{ __((string) $run->error_message) }}</p>
                    <form method="POST" action="{{ route('ai-studio.retry', $run->id) }}" x-data="{ retrying: false }" @submit="if(retrying) $event.preventDefault(); else retrying = true;">
                        @csrf<input type="hidden" name="request_key" value="{{ (string) \Illuminate\Support\Str::uuid() }}">
                        <button type="submit" class="aiw-button" :disabled="retrying"><i class="ph ph-arrow-clockwise" aria-hidden="true"></i>{{ $metered && $run->task !== 'outreach' ? __('Retry · 1 credit reserved') : __('Retry request') }}</button>
                    </form>
                </div>
                <p class="aiw-hint">{{ __('Credit status:') }} <strong x-text="credit === 'none' ? @js(__('No credit charged')) : credit">{{ $run->credit_state === 'none' ? __('No credit charged') : $run->credit_state }}</strong></p>
                <p class="aiw-alert" role="alert" x-show="error" x-cloak x-text="error"></p>
                <button type="button" class="aiw-link" x-show="error" x-cloak @click="poll()">{{ __('Check status again') }}</button>
            </div>
        </div>
    </div>
</div>
