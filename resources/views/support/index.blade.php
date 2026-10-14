<x-app-layout>
    <div class="workspace-page support-page" x-data="{ activeTab: '{{ session('success_message') && !$errors->any() ? 'history' : 'submit' }}' }">
        <div class="workspace-page__container">
            <header class="workspace-page__header">
                <div><h1>Support</h1><p>Get help, share feedback, and follow your requests.</p></div>
                <a href="{{ route('dashboard') }}" wire:navigate class="workspace-button workspace-button--quiet">Back to Home <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>
            </header>
            @if(session('success_message'))
                <div class="workspace-notice" role="status">{{ session('success_message') }}</div>
            @endif
            <div class="workspace-switcher" aria-label="Support sections">
                <button type="button" @click="activeTab = 'submit'" :aria-pressed="activeTab === 'submit'" :class="{ 'is-active': activeTab === 'submit' }" aria-controls="support-submit">New request</button>
                <button type="button" @click="activeTab = 'history'" :aria-pressed="activeTab === 'history'" :class="{ 'is-active': activeTab === 'history' }" aria-controls="support-history">Your requests <span>{{ $tickets->count() }}</span></button>
            </div>
            <div id="support-submit" x-show="activeTab === 'submit'" class="workspace-page__grid">
                <section class="workspace-card">
                    <div class="workspace-card__heading"><h2>How can we help?</h2><p>A little context helps us understand what you need.</p></div>
                    <form action="{{ route('support.store') }}" method="POST" class="workspace-form">
                        @csrf
                        <div><label for="category">Category <span>Required</span></label>
                            <select id="category" name="category" required @error('category') aria-invalid="true" aria-describedby="category-error" @enderror>
                                <option value="" disabled @selected(!old('category'))>Choose a category</option>
                                @foreach(['technical_issue' => 'Technical issue', 'payment_billing' => 'Payment & billing', 'feature_request' => 'Feature request', 'general_feedback' => 'General feedback'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('category') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('category')<p class="workspace-error" id="category-error">{{ $message }}</p>@enderror
                        </div>
                        <div><label for="subject">Subject <span>Required</span></label><input id="subject" name="subject" type="text" value="{{ old('subject') }}" required maxlength="255" placeholder="A short summary of your request" @error('subject') aria-invalid="true" aria-describedby="subject-error" @enderror>
                            @error('subject')<p class="workspace-error" id="subject-error">{{ $message }}</p>@enderror
                        </div>
                        <div><label for="message">Message <span>Required</span></label><textarea id="message" name="message" rows="7" required minlength="10" maxlength="5000" placeholder="What happened? Include the steps you tried and what you expected." aria-describedby="message-hint @error('message') message-error @enderror">{{ old('message') }}</textarea><p class="workspace-hint" id="message-hint">10–5,000 characters. Please leave out passwords and payment credentials.</p>
                            @error('message')<p class="workspace-error" id="message-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="workspace-form__footer"><p>Replies will appear in Your requests.</p><button type="submit" class="workspace-button workspace-button--primary">Send request <i class="ph ph-arrow-right" aria-hidden="true"></i></button></div>
                    </form>
                </section>
                <aside class="workspace-stack">
                    <section class="workspace-card workspace-card--glass"><p class="workspace-eyebrow">A useful request</p><h2>Help us get the full picture.</h2><ol class="workspace-steps"><li><span>01</span><div><h3>Tell us where</h3><p>Include the page or feature you were using.</p></div></li><li><span>02</span><div><h3>Describe what happened</h3><p>Share the steps and any error message you saw.</p></div></li><li><span>03</span><div><h3>Check back for a reply</h3><p>Open Your requests to see updates from the team.</p></div></li></ol></section>
                    <section class="workspace-card workspace-card--lavender"><h2>Have an idea?</h2><p>Choose Feature request to tell us what would make your job search easier.</p><button type="button" class="workspace-button workspace-button--quiet" @click="document.getElementById('category').value = 'feature_request'; document.getElementById('subject').focus()">Share an idea <i class="ph ph-arrow-up-right" aria-hidden="true"></i></button></section>
                </aside>
            </div>
            <section id="support-history" x-show="activeTab === 'history'" x-cloak class="workspace-stack">
                <div class="workspace-section-heading"><div><h2>Your requests</h2><p>Messages and replies, all in one place.</p></div><button type="button" @click="activeTab = 'submit'" class="workspace-button workspace-button--primary">New request</button></div>
                @forelse($tickets as $ticket)
                    <details class="workspace-ticket">
                        <summary><div class="workspace-ticket__identity"><span class="workspace-status workspace-status--{{ $ticket->status }}">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span><h3>{{ $ticket->subject }}</h3><p>{{ $ticket->category_label }} <span>·</span> {{ $ticket->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</p></div><i class="ph ph-caret-down" aria-hidden="true"></i></summary>
                        <div class="workspace-ticket__body">
                            <div class="workspace-message"><div class="workspace-message__header"><img src="{{ Auth::user()->avatar_url }}" alt="" width="32" height="32"><strong>You</strong><time datetime="{{ $ticket->created_at->toIso8601String() }}">{{ $ticket->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}</time></div><p>{{ $ticket->message }}</p></div>
                            @if($ticket->isReplied())
                                <div class="workspace-message workspace-message--reply"><div class="workspace-message__header"><strong>TraKerja Support</strong>@if($ticket->replied_at)<time datetime="{{ $ticket->replied_at->toIso8601String() }}">{{ $ticket->replied_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}</time>@endif</div><p>{{ $ticket->admin_reply }}</p></div>
                            @else
                                <p class="workspace-hint">No reply yet. Updates from the team will appear here.</p>
                            @endif
                            <form action="{{ route('support.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Delete this request and its messages?');">@csrf @method('DELETE')<button type="submit" class="workspace-button workspace-button--danger">Delete request</button></form>
                        </div>
                    </details>
                @empty
                    <div class="workspace-card workspace-empty"><p class="workspace-eyebrow">A fresh start</p><h2>No requests yet.</h2><p>Send a message whenever you need help or want to share an idea.</p><button type="button" @click="activeTab = 'submit'" class="workspace-button workspace-button--primary">Create a request</button></div>
                @endforelse
            </section>
        </div>
    </div>
</x-app-layout>
