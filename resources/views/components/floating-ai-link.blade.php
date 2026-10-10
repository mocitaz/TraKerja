@if(auth()->check() && !auth()->user()->isAdmin() && !request()->routeIs('dashboard', 'ai-studio.*', 'ai-analyzer.*', 'ai-photo.*') && !request()->is('admin/*'))
    <div class="floating-ai-prompt">
        <a href="{{ route('ai-studio.index') }}" wire:navigate title="TraKerja AI" aria-label="Prepare with TraKerja AI"><x-trakerja-ai-mark /><span>Prepare with AI</span></a>
    </div>
@endif
