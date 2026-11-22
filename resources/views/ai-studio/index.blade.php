<x-app-layout>
    @php
        $labels = ['analyzer' => __('Analyze CV'), 'cover-letter' => __('Cover letter'), 'photo' => __('Photo studio'), 'outreach' => __('Recruiter outreach')];
        $icons = ['analyzer' => 'ph-file-magnifying-glass', 'cover-letter' => 'ph-envelope-simple-open', 'photo' => 'ph-user-focus', 'outreach' => 'ph-paper-plane-tilt'];
        $initialTask = old('_studio_task', $task);
        if (!isset($labels[$initialTask])) $initialTask = $task;
        $draft = old() ?: ($prefill ?? []);
        $config = ['task' => $initialTask, 'endpoints' => ['analyzer' => route('ai-analyzer.analyze'), 'cover-letter' => route('cover-letters.generate'), 'photo' => route('ai-photo.process'), 'outreach' => route('ai-studio.outreach')], 'access' => $access, 'draft' => $draft, 'requestKey' => (string) \Illuminate\Support\Str::uuid(), 'applications' => $applications, 'savedResumes' => $savedResumes->map(fn ($r) => ['id' => $r->id, 'name' => $r->title ?: $r->file_name])->values(), 'reviseUrl' => route('ai-studio.revise'), 'hasArtifact' => (bool) ($selected && !$run)];
        $firstName = \Illuminate\Support\Str::before(trim($studioUser->name), ' ');
        $now = now()->timezone('Asia/Jakarta');
        $bucket = function ($date) use ($now) {
            $date = $date->copy()->timezone('Asia/Jakarta');
            return match (true) {
                $date->isSameDay($now) => __('Today'),
                $date->isSameDay($now->copy()->subDay()) => __('Yesterday'),
                $date->gte($now->copy()->subDays(7)->startOfDay()) => __('Previous 7 days'),
                $date->gte($now->copy()->subDays(30)->startOfDay()) => __('Previous 30 days'),
                default => $date->locale(app()->getLocale())->translatedFormat('F Y'),
            };
        };
        $groups = collect($history->items())->groupBy(fn ($item) => $bucket($item['date']));
        $threadTitle = match (true) {
            (bool) $run => $run->title,
            $selected && $task === 'analyzer' => trim((string) ($selected->analysis_result['target_role'] ?? '')) ?: $selected->resume_file_name,
            $selected && $task === 'photo' => $selected->original_photo_name,
            (bool) $selected => trim($selected->company_name.' · '.$selected->job_title, ' ·'),
            default => __('New conversation'),
        };
        $credits = collect($balances)->map(fn ($b) => $b === 'unlimited' ? null : (int) $b);
    @endphp
    <main class="workspace-page ai-portal aiw" x-data="aiStudioPortal(@js($config))" @keydown.escape.window="railOpen = false">
        <div class="workspace-page__container">
        <div class="aiw-shell" :class="{ 'is-rail-open': railOpen, 'is-rail-collapsed': railCollapsed }">
            <div class="aiw-scrim" x-show="railOpen" x-transition.opacity @click="railOpen = false" aria-hidden="true"></div>

            {{-- Sidebar: new chat, tools, searchable history grouped by day, credits --}}
            <aside class="aiw-rail" id="aiw-rail" aria-label="{{ __('Conversation history') }}">
                <div class="aiw-rail-head">
                    <span class="aiw-rail-brand"><x-trakerja-ai-mark /><span>TraKerja AI</span></span>
                    <button type="button" class="aiw-icon-btn aiw-collapse" @click="toggleRail()" :aria-label="railCollapsed ? @js(__('Expand sidebar')) : @js(__('Collapse sidebar'))" :title="railCollapsed ? @js(__('Expand sidebar')) : @js(__('Collapse sidebar'))"><i class="ph" :class="railCollapsed ? 'ph-sidebar-simple' : 'ph-caret-double-left'" aria-hidden="true"></i></button>
                </div>
                <div class="aiw-rail-top">
                    <a href="{{ route('ai-studio.index') }}" class="aiw-new" title="{{ __('New conversation') }}"><i class="ph-bold ph-plus" aria-hidden="true"></i><span>{{ __('New conversation') }}</span></a>
                    <label class="aiw-search"><i class="ph ph-magnifying-glass" aria-hidden="true"></i><span class="sr-only">{{ __('Search history') }}</span><input type="search" x-model="search" placeholder="{{ __('Search history') }}" autocomplete="off"></label>
                </div>
                <nav class="aiw-tools" aria-label="{{ __('Tools') }}">
                    @foreach($labels as $value => $label)
                        <a href="{{ route('ai-studio.index', ['task' => $value]) }}" data-tool="{{ $value }}" title="{{ $label }}" @if(!$selected && !$run && $task === $value) aria-current="page" @endif><span class="aiw-tool-icon"><i class="ph {{ $icons[$value] }}" aria-hidden="true"></i></span><span class="aiw-tool-name">{{ $label }}</span></a>
                    @endforeach
                </nav>
                <div class="aiw-history">
                    <h2 class="aiw-rail-label">{{ __('Recent requests') }}</h2>
                    @forelse($groups as $groupLabel => $items)
                        <section class="aiw-history-group" x-show="groupVisible($el)">
                            <h3>{{ $groupLabel }}</h3>
                            @foreach($items as $item)
                                @php $isCurrent = ($selected && !$run && $task === $item['task'] && $selected->id === $item['id'] && !$item['is_run']) || ($run && $item['is_run'] && $run->id === $item['id']); @endphp
                                <a href="{{ $item['url'] }}" class="aiw-history-item" data-search="{{ mb_strtolower($item['title'].' '.$labels[$item['task']]) }}" x-show="matches($el)" @if($isCurrent) aria-current="page" @endif>
                                    <i class="ph {{ $icons[$item['task']] }}" aria-hidden="true"></i>
                                    <span class="aiw-history-text"><strong>{{ $item['title'] ?: __('Untitled request') }}</strong><small>{{ $labels[$item['task']] }}</small></span>
                                    <time class="aiw-history-time" datetime="{{ $item['date']->toIso8601String() }}">{{ $item['date']->timezone('Asia/Jakarta')->isToday() ? $item['date']->timezone('Asia/Jakarta')->format('H:i') : $item['date']->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M') }}</time>
                                    @if($item['status'] === 'failed')<span class="aiw-badge is-failed">{{ __('Failed') }}</span>
                                    @elseif(in_array($item['status'], ['queued', 'processing'], true))<span class="aiw-badge is-busy" title="{{ __('Processing') }}"><span class="aiw-pulse" aria-hidden="true"></span><span class="sr-only">{{ __('Processing') }}</span></span>@endif
                                </a>
                            @endforeach
                        </section>
                    @empty
                        <p class="aiw-history-empty">{{ __('No requests yet. Create your first review or draft to get started.') }}</p>
                    @endforelse
                    <p class="aiw-history-empty" x-show="search && noMatches" x-cloak>{{ __('No matching requests.') }}</p>
                    @if($history->hasPages())
                        <nav class="aiw-history-pages" aria-label="{{ __('History pages') }}">
                            @if($history->previousPageUrl())<a href="{{ $history->previousPageUrl() }}">{{ __('← Newer') }}</a>@else<span></span>@endif
                            <span>{{ $history->currentPage() }} / {{ $history->lastPage() }}</span>
                            @if($history->nextPageUrl())<a href="{{ $history->nextPageUrl() }}">{{ __('Older →') }}</a>@else<span></span>@endif
                        </nav>
                    @endif
                </div>
                <div class="aiw-rail-foot">
                    @if($metered)
                        <a href="{{ route('profile.edit', ['tab' => 'membership']) }}" class="aiw-credits" title="{{ __('Membership & credits') }}">
                            <span class="aiw-rail-label">{{ __('Credits') }}</span>
                            <span class="aiw-credit-row">
                                <span><i class="ph {{ $icons['analyzer'] }}" aria-hidden="true"></i>{{ $credits['analyzer'] ?? '∞' }}<span class="sr-only"> {{ $labels['analyzer'] }}</span></span>
                                <span><i class="ph {{ $icons['cover-letter'] }}" aria-hidden="true"></i>{{ $credits['cover-letter'] ?? '∞' }}<span class="sr-only"> {{ $labels['cover-letter'] }}</span></span>
                                <span><i class="ph {{ $icons['photo'] }}" aria-hidden="true"></i>{{ $credits['photo'] ?? '∞' }}<span class="sr-only"> {{ $labels['photo'] }}</span></span>
                            </span>
                        </a>
                    @endif
                    <a href="{{ route('cv.builder') }}" class="aiw-foot-link"><i class="ph ph-identification-card" aria-hidden="true"></i>{{ __('CV profile') }}</a>
                    <a href="{{ route('profile.edit', ['tab' => 'membership']) }}" class="aiw-foot-link"><i class="ph ph-crown-simple" aria-hidden="true"></i>{{ __('Membership & credits') }}</a>
                </div>
            </aside>

            <section class="aiw-main" aria-label="{{ __('Career conversation') }}">
                <header class="aiw-topbar">
                    <button type="button" class="aiw-icon-btn aiw-rail-toggle" @click="railOpen = !railOpen" :aria-expanded="railOpen.toString()" aria-controls="aiw-rail" aria-label="{{ __('History') }}"><i class="ph ph-sidebar-simple" aria-hidden="true"></i></button>
                    <div class="aiw-topbar-title">
                        <x-trakerja-ai-mark />
                        <h1>{{ $threadTitle }}</h1>
                        @if($selected || $run)<span class="aiw-chip"><i class="ph {{ $icons[$task] }}" aria-hidden="true"></i>{{ $labels[$task] }}</span>@endif
                    </div>
                    @if($selected && !$run)<button type="button" class="aiw-icon-btn aiw-panel-toggle" :class="{ 'is-on': artifactOpen }" @click="artifactOpen ? closeArtifact() : showArtifact()" :aria-pressed="artifactOpen.toString()" aria-controls="aiw-artifact" :aria-label="artifactOpen ? @js(__('Close panel')) : @js(__('Open document'))" :title="artifactOpen ? @js(__('Close panel')) : @js(__('Open document'))"><i class="ph ph-sidebar-simple aiw-flip" aria-hidden="true"></i></button>@endif
                    <a href="{{ route('ai-studio.index') }}" class="aiw-icon-btn" aria-label="{{ __('New conversation') }}" title="{{ __('New conversation') }}"><i class="ph ph-note-pencil" aria-hidden="true"></i></a>
                </header>

                @if($selected || $run)
                    @if($errors->any())<div role="alert" class="aiw-alert"><strong>{{ __('Please check your request.') }}</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
                    @if($run)
                        @include('ai-studio.partials.request-status')
                    @else
                        @include('ai-studio.partials.result')
                    @endif
                @else
                    <div class="aiw-start">
                        <div class="aiw-hello">
                            <span class="aiw-hello-mark"><x-trakerja-ai-mark /></span>
                            <h2>{{ __('What can I help you with today, :name?', ['name' => $firstName]) }}</h2>
                            <p x-text="taskInfo.description">{{ __('Compare your CV, write a letter, or prepare your next message.') }}</p>
                        </div>
                        @include('ai-studio.partials.composer')
                        <div class="aiw-tool-cards" role="group" aria-label="{{ __('Choose a task') }}">
                            @foreach($labels as $value => $label)
                                <button type="button" class="aiw-tool-card" @click="choose(@js($value)); focusComposer()" :aria-pressed="(task === @js($value)).toString()" :disabled="busy">
                                    <i class="ph {{ $icons[$value] }}" aria-hidden="true"></i>
                                    <strong>{{ $label }}</strong>
                                    <span>{{ ['analyzer' => __('Compare your CV with a job and see what to fix.'), 'cover-letter' => __('A letter written for this role, from your profile.'), 'photo' => __('A clean portrait for your CV and LinkedIn.'), 'outreach' => __('Introduce yourself, follow up or say thank you.')][$value] }}</span>
                                </button>
                            @endforeach
                        </div>
                        @if($applications->isNotEmpty())
                            <div class="aiw-quick">
                                <span>{{ __('Continue with an application') }}</span>
                                <div>
                                    @foreach($applications->take(4) as $application)
                                        <button type="button" @click="useApplication({{ $application->id }})" :disabled="busy"><i class="ph ph-briefcase" aria-hidden="true"></i>{{ $application->position }} · {{ $application->company_name }}</button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </section>
        </div>
        </div>
    </main>
</x-app-layout>
