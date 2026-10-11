<x-app-layout>
    @php
        $labels = ['analyzer' => 'Analyze CV', 'cover-letter' => 'Cover letter', 'photo' => 'Photo studio', 'outreach' => 'Recruiter outreach'];
        $initialTask = old('_studio_task', $task);
        if (!isset($labels[$initialTask])) $initialTask = $task;
        $config = ['task' => $initialTask, 'endpoints' => ['analyzer' => route('ai-analyzer.analyze'), 'cover-letter' => route('cover-letters.generate'), 'photo' => route('ai-photo.process'), 'outreach' => route('ai-studio.outreach')], 'access' => $access, 'draft' => old()];
    @endphp
    <main class="workspace-page ai-portal" x-data="aiStudioPortal(@js($config))">
        <div class="workspace-page__container">
            <div class="ai-portal-grid">
                <aside class="ai-history" aria-label="Conversation history">
                    <div class="ai-history-top">
                        <a href="{{ route('ai-studio.index') }}" class="ai-new-chat">
                            <i class="ph ph-plus" aria-hidden="true"></i>
                            <span>New request</span>
                        </a>
                        <button type="button" class="ai-history-toggle" @click="historyOpen = !historyOpen" :aria-expanded="historyOpen.toString()" aria-controls="ai-history-list">
                            <i class="ph ph-clock-counter-clockwise" aria-hidden="true"></i>
                            <span>History</span>
                        </button>
                    </div>

                    <div id="ai-history-list" class="ai-history-list" :class="{'is-open': historyOpen}">
                        <h2 class="ai-history-heading">Recent conversations</h2>
                        <div class="ai-history-items">
                            @forelse($history as $item)
                                <a href="{{ $item['url'] }}" 
                                   class="ai-history-item"
                                   @if($selected && $task === $item['task'] && $selected->id === $item['id']) aria-current="page" @endif>
                                    <strong class="ai-history-item-title">{{ $item['title'] ?: 'Untitled request' }}</strong>
                                    <span class="ai-history-item-sub">
                                        <span class="ai-history-kind">{{ $labels[$item['task']] }}</span>
                                        <span class="ai-history-dot" aria-hidden="true">·</span>
                                        <time datetime="{{ $item['date']->toIso8601String() }}">{{ $item['date']->timezone('Asia/Jakarta')->format('d M, H:i') }}</time>
                                    </span>
                                </a>
                            @empty
                                <p class="ai-history-empty">Your saved results will appear here after your first request.</p>
                            @endforelse
                        </div>
                        @if($history->hasPages())
                            <nav class="ai-history-pagination" aria-label="History pages">
                                @if($history->previousPageUrl())
                                    <a href="{{ $history->previousPageUrl() }}" class="ai-history-page-btn">← Newer</a>
                                @endif
                                <span class="ai-history-page-indicator">{{ $history->currentPage() }} / {{ $history->lastPage() }}</span>
                                @if($history->nextPageUrl())
                                    <a href="{{ $history->nextPageUrl() }}" class="ai-history-page-btn">Older →</a>
                                @endif
                            </nav>
                        @endif
                    </div>

                    <div class="ai-history-bottom">
                        <a class="ai-profile-context" href="{{ route('cv.builder') }}">
                            <div class="ai-profile-context-header">
                                <span>Make it personal</span>
                                <strong>Update your CV profile ↗</strong>
                            </div>
                            <p>Cover letters use the information you’ve added.</p>
                        </a>

                        <a href="{{ route('membership.index') }}" class="ai-plan-link">
                            <span>{{ $metered ? 'Credits & membership' : 'Unlimited usage' }}</span>
                            <span aria-hidden="true">↗</span>
                        </a>

                        @php($currentUser = $studioUser ?? auth()->user())
                        @if($currentUser)
                            <a href="{{ route('profile.edit') }}" class="ai-user-profile" aria-label="Account profile">
                                <img src="{{ $currentUser->avatar_url }}" alt="" class="ai-user-avatar">
                                <div class="ai-user-info">
                                    <strong class="ai-user-name">{{ $currentUser->name }}</strong>
                                    <span class="ai-user-sub">Profile &amp; Settings</span>
                                </div>
                                <i class="ph ph-caret-right ai-user-arrow" aria-hidden="true"></i>
                            </a>
                        @endif
                    </div>
                </aside>
                <section class="ai-chat" aria-label="Career conversation">
                    <header class="ai-chat-header"><div class="ai-chat-identity"><x-trakerja-ai-mark /><h1>TraKerja AI</h1></div><a class="ai-chat-membership" href="{{ route('membership.index') }}">{{ $metered ? 'Credits' : 'Unlimited' }} ↗</a></header>
                    <div class="ai-conversation">
                        @if($selected)
                            @include('ai-studio.partials.result')
                        @else
                            <div class="ai-welcome" x-show="!busy && !draftText && !turns.length"><x-trakerja-ai-mark class="trakerja-ai-mark--welcome" /><p class="ai-welcome-eyebrow">A little help for your next big step.</p><h2>What are we working on?</h2><p>Choose a task. Add the details below.<br>We’ll keep your results together.</p>
                                <div class="ai-starters"><button type="button" @click="choose('analyzer')"><span aria-hidden="true">01</span><strong>Find my CV gaps</strong><p>Compare your resume with a role.</p></button><button type="button" @click="choose('cover-letter')"><span aria-hidden="true">02</span><strong>Write my introduction</strong><p>A cover letter for your next application.</p></button><button type="button" @click="choose('photo')"><span aria-hidden="true">03</span><strong>Refresh my portrait</strong><p>Enhance a photo or remove its background.</p></button><button type="button" @click="choose('outreach')"><span aria-hidden="true">04</span><strong>Reach out to a recruiter</strong><p>Start with an editable message template.</p></button></div>
                            </div>
                        @endif
                        <div class="ai-pending" x-show="busy" x-cloak role="status" aria-live="polite"><span class="ai-pending-dot" aria-hidden="true"></span><div><strong x-text="task === 'photo' ? 'Saving your photo request…' : task === 'outreach' ? 'Preparing your template…' : 'Working on your request…'"></strong><p>Please keep this page open until your request is saved.</p></div></div>
                        <article class="ai-message ai-message--response" x-show="draftText" x-cloak><div class="ai-message-label">Template fallback · Not saved</div><p class="ai-output-notice">The AI service was unavailable. This is a local template, not a provider-generated result. No credit was deducted.</p><div class="ai-letter-text" x-text="draftText" x-ref="fallback"></div><button type="button" class="ai-text-action" @click="copyDraft()">Copy template</button></article>
                    </div>
                    @include('ai-studio.partials.composer')
                </section>
            </div>
        </div>
    </main>
</x-app-layout>
