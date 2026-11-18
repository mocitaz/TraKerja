@php
    $naraPages = [
        'admin.index' => 'dashboard', 'admin.operations-center' => 'operations', 'admin.job-engine' => 'job-engine', 'admin.vacancies' => 'vacancies',
        'admin.golden-set' => 'golden-set', 'admin.promotions' => 'promotions', 'admin.users' => 'users', 'admin.users.show' => 'users',
        'admin.user-activities' => 'user-activities', 'admin.payments' => 'payments', 'admin.payments.show' => 'payments', 'admin.analytics' => 'analytics',
        'admin.feedbacks.index' => 'feedbacks', 'admin.survey.index' => 'survey', 'admin.email-blast' => 'email-blast', 'admin.settings' => 'settings',
    ];
    $naraPage = $naraPages[request()->route()?->getName()] ?? null;
    $naraPageLabel = $naraPage ? __(\App\Services\AdminNara\AdminNaraService::PAGES[$naraPage][0]) : null;
    $naraConfig = [
        'page' => $naraPage,
        'historyUrl' => route('admin.nara.history'), 'streamUrl' => route('admin.nara.stream'), 'clearUrl' => route('admin.nara.clear'),
        'approveUrl' => route('admin.nara.approve', '__ID__'), 'rejectUrl' => route('admin.nara.reject', '__ID__'),
        'errorText' => __('Something went wrong. Please try again.'),
    ];
    $avatar = asset('images/brand/nara-avatar.svg');
@endphp
<div class="nara-widget nara-widget--admin" x-data="adminNara(@js($naraConfig))" x-cloak @keydown.window.ctrl.slash.prevent="toggle()">
    @include('components.nara-icons')
    <button type="button" class="nara-launcher" x-ref="launcher" x-show="!open" @click="toggle()"
            aria-controls="admin-nara-drawer" :aria-expanded="open.toString()" aria-label="{{ __('Open Nara Admin') }}">
        <img src="{{ $avatar }}" width="44" height="44" alt="">
        <span class="nara-admin-launcher-badge" aria-hidden="true">{{ __('Admin') }}</span>
        <span class="nara-launcher-hint" aria-hidden="true">{{ __('Ask Nara Admin') }} · Ctrl + /</span>
    </button>

    <dialog id="admin-nara-drawer" class="nara-drawer nara-drawer--admin" :class="{ 'nara-drawer--wide': expanded }" x-ref="drawer"
            aria-labelledby="admin-nara-title" @cancel.prevent="open = false" @close="open = false"
            @click="if ($event.target === $el && $event.clientX < $el.getBoundingClientRect().left) open = false">
        <header class="nara-header">
            <div class="nara-identity">
                <img src="{{ $avatar }}" width="40" height="40" alt="">
                <div>
                    <h2 id="admin-nara-title">Nara <span class="nara-admin-badge">{{ __('Admin') }}</span></h2>
                    <p>{{ __('Your operations analyst, on live data.') }}</p>
                </div>
            </div>
            <div class="nara-header-actions">
                <button type="button" class="nara-icon-btn nara-expand" @click="expanded = !expanded"
                        :aria-label="expanded ? @js(__('Narrow panel')) : @js(__('Widen panel'))" :title="expanded ? @js(__('Narrow panel')) : @js(__('Widen panel'))">
                    <svg class="nara-svg" aria-hidden="true"><use :href="expanded ? '#nara-icon-collapse' : '#nara-icon-expand'"/></svg>
                </button>
                <button type="button" class="nara-icon-btn" @click="confirmClear = !confirmClear" :disabled="busy || messages.length === 0"
                        aria-label="{{ __('Start a new conversation') }}" title="{{ __('New conversation') }}"><svg class="nara-svg" aria-hidden="true"><use href="#nara-icon-ph-note-pencil"/></svg></button>
                <button type="button" class="nara-icon-btn" @click="open = false" aria-label="{{ __('Close') }}" title="{{ __('Close') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
                </button>
            </div>
        </header>
        <div class="nara-confirm" x-show="confirmClear" x-cloak role="group" aria-label="{{ __('Confirm new conversation') }}">
            <div class="nara-confirm-copy"><strong>{{ __('Start a new chat?') }}</strong><p>{{ __('The current conversation will be cleared.') }}</p></div>
            <div class="nara-confirm-actions"><button type="button" @click="confirmClear = false">{{ __('Cancel') }}</button>
                <button type="button" class="nara-confirm-primary" @click="clear()" :disabled="busy">{{ __('New chat') }}</button></div>
        </div>

        <div class="nara-messages" x-ref="scroller" :aria-busy="busy.toString()">
            <section class="nara-welcome nara-admin-welcome" x-show="!messages.length">
                <div class="nara-admin-orb" aria-hidden="true"><img src="{{ $avatar }}" width="56" height="56" alt=""></div>
                <p class="nara-welcome-greeting">{{ __('Hi, :value1.', ['value1' => \Illuminate\Support\Str::before(auth()->user()->name, ' ')]) }}</p>
                <h3>{{ __('What do you want to know about TraKerja today?') }}</h3>
                <p class="nara-welcome-description">{{ __('I read live payments, tickets, job sources, users and AI usage, and I can prepare changes for you to approve.') }}</p>
                @if($naraPageLabel)
                    <p class="nara-admin-context"><i class="ph ph-map-pin-simple" aria-hidden="true"></i>{{ __('Looking at: :page', ['page' => $naraPageLabel]) }}</p>
                @endif
                <div class="nara-starters">
                    <template x-for="(idea, index) in starters" :key="index">
                        <button type="button" @click="send(idea.text)">
                            <i class="ph nara-starter-icon" :class="idea.icon" aria-hidden="true"></i>
                            <span><strong x-text="idea.text"></strong><small x-text="idea.hint"></small></span>
                        </button>
                    </template>
                </div>
                <p class="nara-context-note"><i class="ph ph-shield-check" aria-hidden="true"></i> {{ __('Read-only by default. Changes run only after you approve them.') }}</p>
            </section>

            <div class="nara-transcript" role="log" aria-label="{{ __('Conversation with Nara Admin') }}" aria-live="polite" aria-relevant="additions">
                <template x-for="(message, index) in messages" :key="index">
                    <article class="nara-message" :class="message.role === 'user' ? 'nara-message--user' : 'nara-message--assistant'">
                        <div class="nara-message-byline" x-show="message.role !== 'user'"><img src="{{ $avatar }}" width="28" height="28" alt=""><strong>Nara Admin</strong></div>
                        <template x-if="message.role === 'user'"><div class="nara-message-body"><p x-text="message.content"></p></div></template>
                        <template x-if="message.role !== 'user'">
                            <div class="nara-message-body">
                                <template x-for="(segment, s) in segments(message)" :key="s">
                                    <div class="nara-admin-segment">
                                        <template x-if="segment.type === 'html'"><div x-html="segment.html"></div></template>
                                        <template x-if="segment.type === 'action' && actions[segment.id]">
                                            <section class="nara-approval" :class="'is-' + actions[segment.id].status" :aria-busy="(!!actions[segment.id].busy).toString()">
                                                <header>
                                                    <span class="nara-approval-icon" aria-hidden="true"><i class="ph" :class="{ pending: 'ph-shield-warning', running: 'ph-hourglass-medium', executed: 'ph-check-circle', failed: 'ph-warning-circle', rejected: 'ph-x-circle', expired: 'ph-clock-countdown' }[actions[segment.id].status] || 'ph-shield-warning'"></i></span>
                                                    <span class="nara-approval-title">
                                                        <small x-text="({pending: @js(__('Needs your approval')), running: @js(__('Running…')), executed: @js(__('Done')), failed: @js(__('Failed')), rejected: @js(__('Rejected')), expired: @js(__('Expired'))})[actions[segment.id].status]"></small>
                                                        <strong x-text="actions[segment.id].summary"></strong>
                                                    </span>
                                                </header>
                                                <dl class="nara-approval-details">
                                                    <template x-for="line in actions[segment.id].details" :key="line">
                                                        <div><dt x-text="line.split(': ')[0]"></dt><dd x-text="line.split(': ').slice(1).join(': ')"></dd></div>
                                                    </template>
                                                </dl>
                                                <p class="nara-approval-result" x-show="actions[segment.id].message" x-text="actions[segment.id].message"></p>
                                                <footer x-show="actions[segment.id].status === 'pending'">
                                                    <button type="button" class="nara-approval-reject" :disabled="!!actions[segment.id].busy" @click="decide(segment.id, false)">{{ __('Reject') }}</button>
                                                    <button type="button" class="nara-approval-approve" :disabled="!!actions[segment.id].busy" @click="decide(segment.id, true)">
                                                        <i class="ph-bold ph-check" aria-hidden="true"></i><span x-text="actions[segment.id].busy ? @js(__('Running…')) : @js(__('Approve'))"></span>
                                                    </button>
                                                </footer>
                                            </section>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <div class="nara-message-tools" x-show="message.role !== 'user' && !(busy && index === messages.length - 1)">
                            <button type="button" @click="copy(message, index)" :aria-label="copied === index ? @js(__('Copied')) : @js(__('Copy response'))" :title="copied === index ? @js(__('Copied')) : @js(__('Copy response'))">
                                <i class="ph" :class="copied === index ? 'ph-check' : 'ph-copy'" aria-hidden="true"></i><span x-text="copied === index ? @js(__('Copied')) : @js(__('Copy response'))"></span>
                            </button>
                        </div>
                    </article>
                </template>
            </div>

            <div class="nara-thinking" :hidden="!waiting" role="status"><img src="{{ $avatar }}" width="28" height="28" alt=""><span class="nara-dots" aria-hidden="true"><i></i><i></i><i></i></span><span class="nara-thinking-text" x-text="status || @js(__('Reading the data…'))"></span></div>
            <div class="nara-followups" x-show="!busy && chips(messages[lastModelIndex] ?? {}).length > 0 && lastModelIndex === messages.length - 1">
                <span>{{ __('Keep going') }}</span>
                <template x-for="chip in chips(messages[lastModelIndex] ?? {})" :key="chip"><button type="button" @click="send(chip)" x-text="chip"></button></template>
            </div>
        </div>

        <footer class="nara-footer">
            <div class="nara-error" x-show="error" role="alert"><p x-text="error"></p></div>
            <div class="nara-composer" :class="{ 'has-error': input.length > 1500 }">
                <textarea x-ref="input" x-model="input" @input="resize()" rows="1" maxlength="1500" aria-label="{{ __('Message Nara Admin') }}"
                          placeholder="{{ __('Ask about payments, tickets, sources, users…') }}" @keydown="onKey($event)"></textarea>
                <button type="button" class="nara-send" x-show="!busy" @click="send()" :disabled="!input.trim()" aria-label="{{ __('Send message') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 19V5m-6 6 6-6 6 6"/></svg>
                </button>
                <button type="button" class="nara-send nara-stop" x-show="busy" x-cloak @click="stop()" aria-label="{{ __('Stop') }}" title="{{ __('Stop') }}"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><rect x="7" y="7" width="10" height="10" rx="2"/></svg></button>
            </div>
            <div class="nara-composer-hint">
                <span class="nara-keyboard-hint">{{ __('Enter to send · Shift+Enter for new line') }}</span>
                <span>{{ __('Numbers come from live data. Check before acting.') }}</span>
            </div>
        </footer>
    </dialog>
</div>
