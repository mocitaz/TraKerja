@php
    $naraPages = [
        'admin.index' => 'dashboard', 'admin.operations-center' => 'operations', 'admin.job-engine' => 'job-engine', 'admin.vacancies' => 'vacancies',
        'admin.golden-set' => 'golden-set', 'admin.promotions' => 'promotions', 'admin.users' => 'users', 'admin.users.show' => 'users',
        'admin.user-activities' => 'user-activities', 'admin.payments' => 'payments', 'admin.payments.show' => 'payments', 'admin.analytics' => 'analytics',
        'admin.feedbacks.index' => 'feedbacks', 'admin.survey.index' => 'survey', 'admin.email-blast' => 'email-blast', 'admin.settings' => 'settings',
    ];
    $naraPage = $naraPages[request()->route()?->getName()] ?? null;
    $naraConfig = [
        'page' => $naraPage,
        'historyUrl' => route('admin.nara.history'), 'streamUrl' => route('admin.nara.stream'), 'clearUrl' => route('admin.nara.clear'),
        'approveUrl' => route('admin.nara.approve', '__ID__'), 'rejectUrl' => route('admin.nara.reject', '__ID__'),
        'errorText' => __('Something went wrong. Please try again.'),
    ];
@endphp
<div class="admin-nara" x-data="adminNara(@js($naraConfig))" x-cloak @keydown.window.ctrl.slash.prevent="toggle()" @keydown.escape.window="open = false">
    <button type="button" class="admin-nara__fab" @click="toggle()" :aria-expanded="open" aria-controls="admin-nara-panel" title="{{ __('Nara Admin') }} (Ctrl + /)">
        <i class="ph-fill ph-sparkle" aria-hidden="true"></i><span>Nara</span>
    </button>
    <div class="admin-nara__backdrop" @click="open = false" hidden :hidden="!open"></div>
    <aside id="admin-nara-panel" class="admin-nara__panel" :class="open && 'is-open'" role="dialog" aria-label="{{ __('Nara Admin') }}" :aria-hidden="!open">
        <header class="admin-nara__head">
            <div><strong>Nara Admin</strong><small>{{ __('Ask about any admin data') }}</small></div>
            <div class="admin-nara__head-actions">
                <button type="button" @click="clear()" :disabled="busy || !messages.length" title="{{ __('New conversation') }}" aria-label="{{ __('New conversation') }}"><i class="ph ph-arrows-counter-clockwise" aria-hidden="true"></i></button>
                <button type="button" @click="open = false" title="{{ __('Close') }}" aria-label="{{ __('Close') }}"><i class="ph ph-x" aria-hidden="true"></i></button>
            </div>
        </header>
        <div class="admin-nara__scroll" x-ref="scroller">
            <template x-if="!messages.length">
                <div class="admin-nara__empty">
                    <p>{{ __('I read your live data (payments, tickets, job sources, users, AI usage) and can prepare changes for your approval.') }}</p>
                    <div class="admin-nara__chips">
                        <template x-for="chip in starters" :key="chip"><button type="button" @click="send(chip)" x-text="chip"></button></template>
                    </div>
                </div>
            </template>
            <template x-for="(message, index) in messages" :key="index">
                <div class="admin-nara__message" :class="message.role === 'user' ? 'is-user' : 'is-nara'">
                    <template x-if="message.role === 'user'"><p x-text="message.content"></p></template>
                    <template x-if="message.role === 'model'">
                        <div class="admin-nara__body">
                            <template x-for="(segment, s) in segments(message)" :key="s">
                                <div>
                                    <div x-show="segment.type === 'html'" x-html="segment.html"></div>
                                    <template x-if="segment.type === 'action' && actions[segment.id]">
                                        <div class="admin-nara__action" :class="'is-' + actions[segment.id].status">
                                            <strong x-text="actions[segment.id].summary"></strong>
                                            <ul><template x-for="line in actions[segment.id].details" :key="line"><li x-text="line"></li></template></ul>
                                            <div class="admin-nara__action-buttons" x-show="actions[segment.id].status === 'pending'">
                                                <button type="button" class="is-approve" :disabled="!!actions[segment.id].busy" @click="decide(segment.id, true)">{{ __('Approve') }}</button>
                                                <button type="button" :disabled="!!actions[segment.id].busy" @click="decide(segment.id, false)">{{ __('Reject') }}</button>
                                            </div>
                                            <p class="admin-nara__action-state" x-show="actions[segment.id].status !== 'pending'">
                                                <span x-text="({executed: @js(__('Done')), failed: @js(__('Failed')), rejected: @js(__('Rejected')), expired: @js(__('Expired')), running: @js(__('Running…'))})[actions[segment.id].status]"></span>
                                                <span x-show="actions[segment.id].message" x-text="actions[segment.id].message"></span>
                                            </p>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <div class="admin-nara__chips" x-show="index === lastModelIndex && !busy">
                                <template x-for="chip in chips(message)" :key="chip"><button type="button" @click="send(chip)" x-text="chip"></button></template>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
            <p class="admin-nara__thinking" :hidden="!waiting" x-text="status || @js(__('Thinking…'))"></p>
            <p class="admin-nara__error" x-show="error" x-text="error" role="alert"></p>
        </div>
        <form class="admin-nara__form" @submit.prevent="send()">
            <textarea x-ref="input" x-model="input" rows="1" maxlength="1500" @keydown="onKey($event)" placeholder="{{ __('Ask Nara Admin…') }}" aria-label="{{ __('Message') }}"></textarea>
            <button type="button" class="is-stop" x-show="busy" @click="stop()" aria-label="{{ __('Stop') }}"><i class="ph-fill ph-stop" aria-hidden="true"></i></button>
            <button type="submit" x-show="!busy" :disabled="!input.trim()" aria-label="{{ __('Send') }}"><i class="ph-bold ph-arrow-up" aria-hidden="true"></i></button>
        </form>
    </aside>
</div>
