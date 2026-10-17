<x-app-layout>
    @php
        $labels = ['analyzer' => __('Analyze CV'), 'cover-letter' => __('Cover letter'), 'photo' => __('Photo studio'), 'outreach' => __('Recruiter outreach')];
        $initialTask = old('_studio_task', $task);
        if (!isset($labels[$initialTask])) $initialTask = $task;
        $config = ['task' => $initialTask, 'endpoints' => ['analyzer' => route('ai-analyzer.analyze'), 'cover-letter' => route('cover-letters.generate'), 'photo' => route('ai-photo.process'), 'outreach' => route('ai-studio.outreach')], 'access' => $access, 'draft' => old(), 'requestKey' => (string) \Illuminate\Support\Str::uuid(), 'applications' => $applications];
    @endphp
    <main class="workspace-page ai-portal" x-data="aiStudioPortal(@js($config))">
        <div class="workspace-page__container">
            <div class="ai-portal-grid">
                <aside class="ai-history" aria-label="{{ __('Conversation history') }}">
                    <div class="ai-history-top">
                        <a href="{{ route('ai-studio.index') }}" class="ai-new-chat">
                            <i class="ph ph-plus" aria-hidden="true"></i>
                            <span>{{ __('New task') }}</span>
                        </a>

                    </div>

                    <div id="ai-history-list" class="ai-history-list" :class="{'is-open': historyOpen}">
                        <h2 class="ai-history-heading">{{ __('Recent requests') }}</h2>
                        <div class="ai-history-items">
                            @forelse($history as $item)
                                <a href="{{ $item['url'] }}"
                                   class="ai-history-item"
                                   @if($selected && $task === $item['task'] && $selected->id === $item['id']) aria-current="page" @endif>
                                    <strong class="ai-history-item-title">{{ $item['title'] ?: __('Untitled request') }}</strong>
                                    <span class="ai-history-item-sub">
                                        <span class="ai-history-kind">{{ $labels[$item['task']] }}</span>
                                        <span class="ai-history-dot" aria-hidden="true">·</span>
                                        <time datetime="{{ $item['date']->toIso8601String() }}">{{ $item['date']->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M, H:i') }}</time>
                                    </span>
                                </a>
                            @empty
                                <p class="ai-history-empty">{{ __('No requests yet. Create your first review or draft to get started.') }}</p>
                            @endforelse
                        </div>
                        @if($history->hasPages())
                            <nav class="ai-history-pagination" aria-label="{{ __('History pages') }}">
                                @if($history->previousPageUrl())
                                    <a href="{{ $history->previousPageUrl() }}" class="ai-history-page-btn">{{ __('← Newer') }}</a>
                                @endif
                                <span class="ai-history-page-indicator">{{ $history->currentPage() }} / {{ $history->lastPage() }}</span>
                                @if($history->nextPageUrl())
                                    <a href="{{ $history->nextPageUrl() }}" class="ai-history-page-btn">{{ __('Older →') }}</a>
                                @endif
                            </nav>
                        @endif
                    </div>

                    <div class="ai-history-bottom">
                        <a href="{{ route('cv.builder') }}">{{ __('CV profile') }} <span aria-hidden="true">↗</span></a>
                        <a href="{{ route('profile.edit', ['tab' => 'membership']) }}">{{ __('Membership & credits') }} <span aria-hidden="true">↗</span></a>
                    </div>
                </aside>
                <section class="ai-chat" aria-label="{{ __('Career conversation') }}">
                    <header class="ai-chat-header">
                        <div class="ai-chat-identity"><x-trakerja-ai-mark /><h1>{{ __('AI Studio') }}</h1></div>
                        <div class="ai-header-actions"><button type="button" class="ai-history-toggle" @click="historyOpen = !historyOpen" :aria-expanded="historyOpen.toString()" aria-controls="ai-history-list">{{ __('History') }}</button>@if($selected || $run)<a href="{{ route('ai-studio.index', ['task' => $task]) }}" class="ai-new-request-link">{{ __('New task') }} <span aria-hidden="true">＋</span></a>@else<span class="ai-header-note">{{ __('Your career workspace') }}</span>@endif</div>
                    </header>
                    @if($selected || $run)
                        <div class="ai-conversation ai-conversation--result">
                            @if($errors->any())<div role="alert" class="ai-composer-error"><strong>{{ __('Please check your request.') }}</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
                            @if($run) @include('ai-studio.partials.request-status') @else @include('ai-studio.partials.result') @endif
                        </div>
                        <footer class="ai-result-footer"><span>{{ __('Your requests are saved in History.') }}</span><a href="{{ route('ai-studio.index', ['task' => $task]) }}">{{ __('Create another :value1', ['value1' => ($task === 'analyzer' ? 'review' : ($task === 'photo' ? 'photo' : 'draft'))]) }}</a></footer>
                    @else
                        <div class="ai-start-workspace">
                            <header class="ai-welcome"><p>{{ __('TraKerja AI') }}</p><h2 x-text="taskInfo.title">{{ __('Make your next application stronger.') }}</h2><p x-text="taskInfo.description">{{ __('Compare your CV, write a letter, or prepare your next message.') }}</p></header>
                            <nav class="ai-task-tabs" aria-label="{{ __('Choose a task') }}">
                                @foreach($labels as $value => $label)<button type="button" @click="choose(@js($value))" :aria-pressed="(task === @js($value)).toString()" :disabled="busy">{{ __($label) }}</button>@endforeach
                            </nav>
                            @include('ai-studio.partials.composer')
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </main>
</x-app-layout>
