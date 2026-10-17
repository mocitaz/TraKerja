@if(auth()->check() && !auth()->user()->isAdmin())
<div class="nara-widget" x-data="naraChat({
    chatUrl: @js(route('kawankerja.chat')),
    historyUrl: @js(route('kawankerja.history')),
    clearUrl: @js(route('kawankerja.clear')),
    csrfToken: @js(csrf_token()),
    routes: {
                'tracker': { url: '{{ route('tracker') }}', icon: 'ph-kanban' },
                'interviews': { url: '{{ route('interviews') }}', icon: 'ph-calendar' },
                'ai-studio': { url: '{{ route('ai-studio.index') }}', icon: 'ph-sparkle' },
                'cv-builder': { url: '{{ route('cv.builder') }}', icon: 'ph-file-text' },
                'explore': { url: '{{ route('jobs.explore') }}', icon: 'ph-compass' },
                'profile': { url: '{{ route('profile.edit') }}', icon: 'ph-user' },
            },
})" x-cloak>
    @include('components.nara-icons')
    <button type="button" class="nara-launcher" x-ref="launcher" x-show="!open" @click="open = true"
            aria-controls="nara-drawer" :aria-expanded="open.toString()" aria-label="{{ __('Open Nara career assistant') }}">
        <img src="{{ asset('images/brand/nara-avatar.svg') }}" width="32" height="32" alt="">
        <span>{{ __('Nara') }}</span>
        <svg class="nara-svg" aria-hidden="true"><use href="#nara-icon-ph-chat-circle"/></svg>
    </button>

    <dialog id="nara-drawer" class="nara-drawer" :class="{ 'nara-drawer--wide': expanded }" x-ref="drawer"
            aria-labelledby="nara-title" @cancel.prevent="open = false" @close="open = false"
            @click="if ($event.target === $el && $event.clientX < $el.getBoundingClientRect().left) open = false">
        <header class="nara-header">
            <div class="nara-identity">
                <img src="{{ asset('images/brand/nara-avatar.svg') }}" width="40" height="40" alt="">
                <div><h2 id="nara-title">{{ __('Nara') }} <span>{{ __('by TraKerja') }}</span></h2><p>{{ __('Your next move, made clearer.') }}</p></div>
            </div>
            <div class="nara-header-actions">
                <button type="button" class="nara-icon-btn nara-expand" @click="expanded = !expanded"
                        :aria-label="expanded ? @js(__('Narrow panel')) : @js(__('Widen panel'))" :title="expanded ? @js(__('Narrow panel')) : @js(__('Widen panel'))">
                    <svg class="nara-svg" aria-hidden="true"><use :href="expanded ? '#nara-icon-collapse' : '#nara-icon-expand'"/></svg>
                </button>
                <button type="button" class="nara-icon-btn" @click="confirmClear = !confirmClear" :disabled="busy || messages.length === 0"
                        aria-label="{{ __('Start a new conversation') }}" title="{{ __('New conversation') }}"><svg class="nara-svg" aria-hidden="true"><use href="#nara-icon-ph-note-pencil"/></svg></button>
                <button type="button" class="nara-icon-btn" @click="open = false" aria-label="{{ __('Close Nara') }}" title="{{ __('Close') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
                </button>
            </div>
        </header>

        <div class="nara-focus" role="group" aria-label="{{ __('Conversation focus') }}">
            <template x-for="item in modes" :key="item.key">
                <button type="button" @click="selectMode(item.key)" :aria-pressed="(mode === item.key).toString()" :disabled="busy"
                        :class="{ 'is-active': mode === item.key }"><span x-text="item.label"></span></button>
            </template>
        </div>

        <div class="nara-confirm" x-show="confirmClear" x-cloak role="group" aria-label="{{ __('Confirm new conversation') }}">
            <div class="nara-confirm-copy"><strong>{{ __('Start a new chat?') }}</strong><p>{{ __('The current conversation will be cleared.') }}</p></div>
            <div class="nara-confirm-actions"><button type="button" @click="confirmClear = false" :disabled="clearing">{{ __('Cancel') }}</button>
                <button type="button" class="nara-confirm-primary" @click="clearChat()" :disabled="busy" x-text="clearing ? @js(__('Clearing…')) : @js(__('New chat'))"></button></div>
        </div>

        <div class="nara-messages" x-ref="messages" @scroll="trackScroll()" :aria-busy="loading || historyLoading">
            <div class="nara-history-state" x-show="historyLoading" role="status"><span class="nara-spinner"></span>{{ __('Loading your conversation…') }}</div>
            <section class="nara-welcome" x-show="!historyLoading && !historyError && messages.length === 0">
                <p class="nara-welcome-greeting">{{ __('Hi, :value1.', ['value1' => (\Illuminate\Support\Str::before(auth()->user()->name, ' '))]) }}</p>
                <h3 x-text="currentMode.heading"></h3>
                <p class="nara-welcome-description" x-text="currentMode.description"></p>
                <div class="nara-starters">
                    <template x-for="(idea, index) in currentMode.starters" :key="mode + index">
                        <button type="button" @click="usePrompt(idea.prompt)">
                            <span><strong x-text="idea.title"></strong><small x-text="idea.description"></small></span>
                        </button>
                    </template>
                </div>
                <p class="nara-context-note">{{ __('Built around your applications and profile. You stay in control.') }}</p>
            </section>

            <div class="nara-history-error" x-show="historyError" role="alert"><p x-text="historyError"></p><button type="button" @click="loadHistory()">{{ __('Reload conversation') }}</button></div>

            <div class="nara-transcript" role="log" aria-label="{{ __('Conversation with Nara') }}" aria-live="polite" aria-relevant="additions">
                <template x-for="(msg, index) in messages" :key="index">
                    <article class="nara-message" :class="msg.role === 'user' ? 'nara-message--user' : 'nara-message--assistant'">
                        <div class="nara-message-byline" x-show="msg.role !== 'user'"><img src="{{ asset('images/brand/nara-avatar.svg') }}" width="24" height="24" alt=""><strong>{{ __('Nara') }}</strong><span>{{ __('Career partner') }}</span></div>
                        <div class="nara-message-body" x-html="formatText(msg.content, msg.role !== 'user')"></div>
                        <div class="nara-message-tools" x-show="msg.role !== 'user'">
                            <button type="button" @click="copyMessage(msg.content, index)" :aria-label="copied === index ? @js(__('Copied')) : @js(__('Copy response'))"><svg class="nara-svg" aria-hidden="true"><use href="#nara-icon-ph-copy"/></svg><span x-text="copied === index ? @js(__('Copied')) : @js(__('Copy response'))"></span></button>
                        </div>
                    </article>
                </template>
            </div>
            <div class="nara-thinking" x-show="loading" role="status"><span class="nara-spinner"></span><span x-text="currentMode.thinking"></span></div>
            <div class="nara-followups" x-show="!busy && !failedText && messages.length > 0 && !historyError">
                <span>{{ __('Keep going') }}</span>
                <template x-for="idea in currentMode.followups" :key="idea"><button type="button" @click="usePrompt(idea)" x-text="idea"></button></template>
            </div>
        </div>

        <button type="button" class="nara-jump" x-show="!atBottom && messages.length > 0" @click="scrollToBottom(true)"><svg class="nara-svg" aria-hidden="true"><use href="#nara-icon-ph-arrow-down"/></svg>{{ __('Latest message') }}</button>

        <footer class="nara-footer">
            <div class="nara-error" x-show="error" role="alert"><p x-text="error"></p><button type="button" x-show="failedText" @click="retry()" :disabled="busy">{{ __('Try again') }}</button><button type="button" x-show="failedText" @click="editFailed()" :disabled="busy">{{ __('Edit message') }}</button></div>
            <div class="nara-composer" :class="{ 'has-error': length > 1000 }">
                <textarea x-ref="composer" x-model="input" @input="resizeComposer()" rows="1" aria-label="{{ __('Message Nara') }}"
                          :placeholder="currentMode.placeholder" :disabled="historyLoading || clearing || !!historyError"
                          @keydown.enter="if (!$event.shiftKey && !$event.isComposing && $event.keyCode !== 229) { $event.preventDefault(); send(); }"></textarea>
                    <button type="button" class="nara-send" @click="send()" :disabled="busy || !input.trim() || length > 1000 || !!historyError || !!failedText" aria-label="{{ __('Send message') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 19V5m-6 6 6-6 6 6"/></svg>
                    </button>
            </div>
            <div class="nara-composer-hint">
                <span class="nara-keyboard-hint">{{ __('Enter to send · Shift+Enter for new line') }}</span>
                <span class="nara-character-count" x-show="length > 800" :class="{ 'nara-over-limit': length > 1000 }" x-text="length + ' / 1,000'" aria-live="polite"></span>
                <span x-show="length <= 800">{{ __('AI can make mistakes.') }}</span>
            </div>
        </footer>
    </dialog>
</div>
@endif
