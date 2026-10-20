<x-app-layout>
    <div class="workspace-page support-page"
         x-data="{
            pendingDelete: null, deleting: false,
            activeTab: @js(session('success_message') && !$errors->any() ? 'history' : 'submit'),
            selectTab(next, focus = false) {
                this.activeTab = next;
                if (focus) this.$nextTick(() => {
                    this.$refs[next].focus({ preventScroll: true });
                    this.$refs.content.scrollIntoView({ block: 'start', behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' });
                });
            },
            keyTab(event) {
                const keys = ['ArrowLeft', 'ArrowRight', 'Home', 'End'];
                if (!keys.includes(event.key)) return;
                event.preventDefault();
                this.activeTab = event.key === 'Home' ? 'submit' : event.key === 'End' ? 'history' : this.activeTab === 'submit' ? 'history' : 'submit';
                this.$nextTick(() => this.$refs['tab_' + this.activeTab].focus());
            }
         }">
        <div class="workspace-page__container">
            <div class="support-shell">
            <header class="support-hero">
                <div class="support-hero-copy">
                    <p class="support-eyebrow">{{ __('Support') }}</p>
                    <h1>{{ __('Let’s figure it out,') }} <span>{{ __('together.') }}</span></h1>
                    <p class="support-intro">{{ __('A question, a snag, or an idea. Tell us what’s on your mind.') }}</p>
                </div>
                <figure class="support-portrait">
                    <img src="{{ asset('images/support/trakerja-support-cutout.png') }}" alt="{{ __('Portrait wearing a black TraKerja T-shirt') }}" width="1254" height="1254" decoding="async" draggable="false">
                </figure>
            </header>

            <div class="support-main">
            @if(session('success_message'))
                <div class="support-notice" role="status">{{ __(session('success_message')) }}</div>
            @endif
            @if($errors->any())
                <div class="support-error-summary" role="alert">
                    <p>{{ __('Please check the highlighted fields and try again.') }}</p>
                    <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <div id="support-content" x-ref="content" class="support-content">
                <nav class="support-tabs" role="tablist" aria-label="{{ __('Support sections') }}" @keydown="keyTab($event)">
                    <button type="button" role="tab" id="support-tab-submit" x-ref="tab_submit" :aria-selected="(activeTab === 'submit').toString()" :tabindex="activeTab === 'submit' ? 0 : -1" :class="{ 'is-active': activeTab === 'submit' }" @click="selectTab('submit')" aria-controls="support-submit">{{ __('New request') }}</button>
                    <button type="button" role="tab" id="support-tab-history" x-ref="tab_history" :aria-selected="(activeTab === 'history').toString()" :tabindex="activeTab === 'history' ? 0 : -1" :class="{ 'is-active': activeTab === 'history' }" @click="selectTab('history')" aria-controls="support-history">{{ __('Your requests') }} <span>{{ $tickets->count() }}</span></button>
                </nav>

                <div class="support-panels">
                <section id="support-submit" x-ref="submit" role="tabpanel" aria-labelledby="support-tab-submit" tabindex="-1" :class="{ 'support-panel--inactive': activeTab !== 'submit' }" :inert="activeTab !== 'submit'" :aria-hidden="(activeTab !== 'submit').toString()" class="support-submit">
                    <div class="support-section-intro">
                        <h2>{{ __('What can we help with?') }}</h2>
                        <p>{{ __('A little context helps us understand what you need.') }}</p>
                    </div>
                    <form action="{{ route('support.store') }}" method="POST" class="support-form"
                          x-data="{ category: @js(old('category', '')), message: @js(old('message', '')), submitting: false }"
                          @pageshow.window="submitting = false"
                          @submit="if (submitting) { $event.preventDefault(); } else { submitting = true; }">
                        @csrf
                        <div class="support-field">
                            <label for="category">{{ __('Category') }} <span>{{ __('Required') }}</span></label>
                            <select x-model="category" id="category" name="category" required @error('category') aria-invalid="true" aria-describedby="category-error" @enderror>
                                <option value="" disabled @selected(!old('category'))>{{ __('Choose a category') }}</option>
                                @foreach(['technical_issue' => 'Technical issue', 'payment_billing' => 'Payment & billing', 'feature_request' => 'Feature request', 'general_feedback' => 'General feedback'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('category') === $value)>{{ __($label) }}</option>
                                @endforeach
                            </select>
                            @error('category')<p class="support-field-error" id="category-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="support-field">
                            <label for="subject">{{ __('Subject') }} <span>{{ __('Required') }}</span></label>
                            <input id="subject" name="subject" type="text" value="{{ old('subject') }}" required maxlength="255" placeholder="{{ __('A short summary of your request') }}" @error('subject') aria-invalid="true" aria-describedby="subject-error" @enderror>
                            @error('subject')<p class="support-field-error" id="subject-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="support-field">
                            <label for="message">{{ __('Message') }} <span>{{ __('Required') }}</span></label>
                            <textarea x-model="message" id="message" name="message" rows="3" required minlength="10" maxlength="5000" placeholder="{{ __('What happened? Include the steps you tried and what you expected.') }}" aria-describedby="message-hint @error('message') message-error @enderror" @error('message') aria-invalid="true" @enderror>{{ old('message') }}</textarea>
                            <div class="support-field-meta"><p id="message-hint">{{ __('Please leave out passwords and payment credentials.') }}</p><span x-text="message.length + ' / 5,000'">{{ mb_strlen(old('message', '')) }} / 5,000</span></div>
                            @error('message')<p class="support-field-error" id="message-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="support-form-footer">
                            <p>{{ __('Replies will appear in Your requests.') }}</p>
                            <button type="submit" class="support-primary" :disabled="submitting"><span x-text="submitting ? @js(__('Sending…')) : @js(__('Send request'))">{{ __('Send request') }}</span><i class="ph ph-arrow-up-right" aria-hidden="true" x-show="!submitting"></i></button>
                        </div>
                    </form>
                </section>

                <section id="support-history" x-ref="history" role="tabpanel" aria-labelledby="support-tab-history" tabindex="-1" :class="{ 'support-panel--inactive': activeTab !== 'history' }" :inert="activeTab !== 'history'" :aria-hidden="(activeTab !== 'history').toString()" x-cloak class="support-history">
                    <div class="support-history-heading"><div><h2>{{ __('Your requests') }}</h2><p>{{ __('Messages and replies, all in one place.') }}</p></div><button type="button" @click="selectTab('submit', true)" class="support-text-link">{{ __('New request') }} <i class="ph ph-plus" aria-hidden="true"></i></button></div>
                    <div class="support-ticket-list">
                    @forelse($tickets as $ticket)
                        <details class="support-ticket" @if(session('support_ticket_id') === $ticket->id) open @endif>
                            <summary>
                                <div class="support-ticket-identity"><span class="support-ticket-category">{{ __($ticket->category_label) }}</span><h3>{{ $ticket->subject }}</h3><p>{{ Str::limit($ticket->admin_reply ?: $ticket->message, 110) }}</p></div>
                                <div class="support-ticket-meta"><span class="support-status support-status--{{ $ticket->status }}">{{ __(ucfirst(str_replace('_', ' ', $ticket->status))) }}</span><time datetime="{{ $ticket->created_at->toIso8601String() }}">{{ $ticket->created_at->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M Y') }}</time></div>
                                <i class="ph ph-caret-down" aria-hidden="true"></i>
                            </summary>
                            <div class="support-ticket-body">
                                <div class="support-message"><div class="support-message-heading"><img src="{{ Auth::user()->avatar_url }}" alt="" width="28" height="28"><strong>{{ __('You') }}</strong><time datetime="{{ $ticket->created_at->toIso8601String() }}">{{ $ticket->created_at->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M Y, H:i') }} WIB</time></div><p>{{ $ticket->message }}</p></div>
                                @if($ticket->isReplied())
                                    <div class="support-message support-message--reply"><div class="support-message-heading"><strong>{{ __('TraKerja Support') }}</strong>@if($ticket->replied_at)<time datetime="{{ $ticket->replied_at->toIso8601String() }}">{{ $ticket->replied_at->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M Y, H:i') }} WIB</time>@endif</div><p>{{ $ticket->admin_reply }}</p></div>
                                @else
                                    <p class="support-waiting">{{ __('No reply yet. Updates from the team will appear here.') }}</p>
                                @endif
                                <form action="{{ route('support.destroy', $ticket->id) }}" method="POST" id="support-delete-{{ $ticket->id }}">@csrf @method('DELETE')<button type="button" @click="pendingDelete = {{ $ticket->id }}" class="support-delete-link" aria-haspopup="dialog" aria-controls="support-delete-dialog">{{ __('Delete request') }}</button></form>
                            </div>
                        </details>
                    @empty
                        <div class="support-empty"><h3>{{ __('No requests yet.') }}</h3><p>{{ __('Send a message whenever you need help or want to share an idea.') }}</p><button type="button" @click="selectTab('submit', true)" class="support-text-link">{{ __('Create a request') }} <i class="ph ph-arrow-right" aria-hidden="true"></i></button></div>
                    @endforelse
                    </div>
                </section>
                </div>
            </div>
            </div>
            </div>
        </div>
        <template x-teleport="body">
            <div id="support-delete-dialog" x-show="pendingDelete !== null" x-cloak x-dialog="pendingDelete !== null" data-dialog-close="pendingDelete = null" class="account-dialog-overlay" role="dialog" aria-modal="true" aria-labelledby="support-delete-title" tabindex="-1">
                <section class="support-confirm-dialog"><h2 id="support-delete-title">{{ __('Delete this request?') }}</h2><p>{{ __('This removes the request and its messages from your support history.') }}</p><div><button type="button" @click="pendingDelete = null" :disabled="deleting" class="workspace-button workspace-button--quiet">{{ __('Keep request') }}</button><button type="button" :disabled="deleting" @click="deleting = true; document.getElementById('support-delete-' + pendingDelete).requestSubmit()" class="workspace-button workspace-button--danger" x-text="deleting ? @js(__('Deleting…')) : @js(__('Delete request'))">{{ __('Delete request') }}</button></div></section>
            </div>
        </template>
    </div>
</x-app-layout>
