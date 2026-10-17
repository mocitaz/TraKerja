<x-app-layout>
    <div class="workspace-page support-page" x-data="{ pendingDelete: null, deleting: false, activeTab: '{{ session('success_message') && !$errors->any() ? 'history' : 'submit' }}' }">
        <div class="workspace-page__container">
            <header class="workspace-page__header">
                <div><h1>{{ __('Support') }}</h1><p>{{ __('Get help, share feedback, and follow your requests.') }}</p></div>
                <a href="{{ route('dashboard') }}" wire:navigate class="workspace-button workspace-button--quiet">{{ __('Back to Home') }} <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>
            </header>
            @if(session('success_message'))
                <div class="workspace-notice" role="status">{{ session('success_message') }}</div>
            @endif
            <div class="workspace-switcher" aria-label="{{ __('Support sections') }}">
                <button type="button" @click="activeTab = 'submit'" :aria-pressed="activeTab === 'submit'" :class="{ 'is-active': activeTab === 'submit' }" aria-controls="support-submit">{{ __('New request') }}</button>
                <button type="button" @click="activeTab = 'history'" :aria-pressed="activeTab === 'history'" :class="{ 'is-active': activeTab === 'history' }" aria-controls="support-history">{{ __('Your requests') }} <span>{{ $tickets->count() }}</span></button>
            </div>
            <div id="support-submit" x-show="activeTab === 'submit'" class="workspace-page__grid">
                <section class="workspace-card">
                    <div class="workspace-card__heading"><h2>{{ __('How can we help?') }}</h2><p>{{ __('A little context helps us understand what you need.') }}</p></div>
                    <form action="{{ route('support.store') }}" method="POST" class="workspace-form" x-data="{ category: @js(old('category', '')), message: @js(old('message', '')), submitting: false }" @pageshow.window="submitting = false" @submit="if (submitting) { $event.preventDefault(); } else { submitting = true; }">
                        @csrf
                        <div><label for="category">{{ __('Category') }} <span>{{ __('Required') }}</span></label>
                            <select x-model="category" id="category" name="category" required @error('category') aria-invalid="true" aria-describedby="category-error" @enderror>
                                <option value="" disabled @selected(!old('category'))>{{ __('Choose a category') }}</option>
                                @foreach(['technical_issue' => 'Technical issue', 'payment_billing' => 'Payment & billing', 'feature_request' => 'Feature request', 'general_feedback' => 'General feedback'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('category') === $value)>{{ __($label) }}</option>
                                @endforeach
                            </select>
                            @error('category')<p class="workspace-error" id="category-error">{{ $message }}</p>@enderror
                        </div>
                        <p class="support-context-hint" x-show="category" x-cloak x-text="({technical_issue: @js(__('Include the page, steps to reproduce, and any error message.')), payment_billing: @js(__('Include your order reference and what happened. Do not include payment credentials.')), feature_request: @js(__('Describe the task you want to complete and how this feature would help.')), general_feedback: @js(__('Tell us what worked, what felt difficult, and what you would improve.'))})[category]"></p>
                        <div><label for="subject">{{ __('Subject') }} <span>{{ __('Required') }}</span></label><input id="subject" name="subject" type="text" value="{{ old('subject') }}" required maxlength="255" placeholder="{{ __('A short summary of your request') }}" @error('subject') aria-invalid="true" aria-describedby="subject-error" @enderror>
                            @error('subject')<p class="workspace-error" id="subject-error">{{ $message }}</p>@enderror
                        </div>
                        <div><label for="message">{{ __('Message') }} <span>{{ __('Required') }}</span></label><textarea x-model="message" id="message" name="message" rows="7" @error('message') aria-invalid="true" @enderror required minlength="10" maxlength="5000" placeholder="{{ __('What happened? Include the steps you tried and what you expected.') }}" aria-describedby="message-hint @error('message') message-error @enderror">{{ old('message') }}</textarea><p class="workspace-hint" id="message-hint">{{ __('Please leave out passwords and payment credentials.') }} <span class="support-character-count" x-text="message.length + ' / 5,000'">{{ strlen(old('message', '')) }} / 5,000</span></p>
                            @error('message')<p class="workspace-error" id="message-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="workspace-form__footer"><p>{{ __('Replies will appear in Your requests.') }}</p><button type="submit" class="workspace-button workspace-button--primary" :disabled="submitting" x-text="submitting ? @js(__('Sending…')) : @js(__('Send request'))">{{ __('Send request') }}</button></div>
                    </form>
                </section>
                <aside class="support-guide"><span class="support-guide-label">{{ __('BEFORE YOU SEND') }}</span><h2>{{ __('A little context goes a long way.') }}</h2><p>{{ __('Help the team understand your request without sharing private account details.') }}</p><details open><summary>{{ __('What to include') }}</summary><ul><li>{{ __('The page or feature you were using.') }}</li><li>{{ __('What you expected and what happened instead.') }}</li><li>{{ __('The steps you tried and any error message.') }}</li></ul></details><div class="support-guide-footer"><strong>{{ __('Where will I get a reply?') }}</strong><p>{{ __('Replies appear under Your requests. Open a request to read the team\'s response.') }}</p></div></aside>
            </div>
            <section id="support-history" x-show="activeTab === 'history'" x-cloak class="workspace-stack">
                <div class="workspace-section-heading"><div><h2>{{ __('Your requests') }}</h2><p>{{ __('Messages and replies, all in one place.') }}</p></div><button type="button" @click="activeTab = 'submit'" class="workspace-button workspace-button--primary">{{ __('New request') }}</button></div>
                @forelse($tickets as $ticket)
                    <details class="workspace-ticket" @if(session('support_ticket_id') === $ticket->id) open @endif>
                        <summary><div class="workspace-ticket__identity"><span class="workspace-status workspace-status--{{ $ticket->status }}">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span><h3>{{ $ticket->subject }}</h3><span class="support-ticket-preview">{{ Str::limit($ticket->admin_reply ?: $ticket->message, 120) }}</span><p>{{ $ticket->category_label }} <span>·</span> {{ __(':value1 WIB', ['value1' => ($ticket->created_at->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M Y, H:i'))]) }}</p></div><i class="ph ph-caret-down" aria-hidden="true"></i></summary>
                        <div class="workspace-ticket__body">
                            <div class="workspace-message"><div class="workspace-message__header"><img src="{{ Auth::user()->avatar_url }}" alt="" width="32" height="32"><strong>{{ __('You') }}</strong><time datetime="{{ $ticket->created_at->toIso8601String() }}">{{ $ticket->created_at->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M Y, H:i') }}</time></div><p>{{ $ticket->message }}</p></div>
                            @if($ticket->isReplied())
                                <div class="workspace-message workspace-message--reply"><div class="workspace-message__header"><strong>{{ __('TraKerja Support') }}</strong>@if($ticket->replied_at)<time datetime="{{ $ticket->replied_at->toIso8601String() }}">{{ $ticket->replied_at->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M Y, H:i') }}</time>@endif</div><p>{{ $ticket->admin_reply }}</p></div>
                            @else
                                <p class="workspace-hint">{{ __('No reply yet. Updates from the team will appear here.') }}</p>
                            @endif
                            <form action="{{ route('support.destroy', $ticket->id) }}" method="POST" id="support-delete-{{ $ticket->id }}">@csrf @method('DELETE')<button type="button" @click="pendingDelete = {{ $ticket->id }}" class="support-delete-link" aria-haspopup="dialog">{{ __('Delete request') }}</button></form>
                        </div>
                    </details>
                @empty
                    <div class="workspace-card workspace-empty"><p class="workspace-eyebrow">{{ __('A fresh start') }}</p><h2>{{ __('No requests yet.') }}</h2><p>{{ __('Send a message whenever you need help or want to share an idea.') }}</p><button type="button" @click="activeTab = 'submit'" class="workspace-button workspace-button--primary">{{ __('Create a request') }}</button></div>
                @endforelse
            </section>
        </div>
        <template x-teleport="body"><div x-show="pendingDelete !== null" x-cloak x-dialog="pendingDelete !== null" data-dialog-close="pendingDelete = null" class="account-dialog-overlay" role="dialog" aria-modal="true" aria-labelledby="support-delete-title" tabindex="-1"><section class="support-confirm-dialog"><h2 id="support-delete-title">{{ __('Delete this request?') }}</h2><p>{{ __('This removes the request and its messages from your support history.') }}</p><div><button type="button" @click="pendingDelete = null" :disabled="deleting" class="workspace-button workspace-button--quiet">{{ __('Keep request') }}</button><button type="button" :disabled="deleting" @click="deleting = true; document.getElementById('support-delete-' + pendingDelete).requestSubmit()" class="workspace-button workspace-button--danger" x-text="deleting ? @js(__('Deleting…')) : @js(__('Delete request'))">{{ __('Delete request') }}</button></div></section></div></template>
    </div>
</x-app-layout>
