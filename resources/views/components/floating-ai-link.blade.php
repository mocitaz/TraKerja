@if(auth()->check() && !auth()->user()->isAdmin() && !request()->routeIs('ai-studio.*', 'ai-analyzer.*', 'ai-photo.*') && !request()->is('admin/*'))
    <div class="floating-ai-prompt"
         x-data="{
             isVisible() {
                 const p = window.location.pathname;
                 return !p.startsWith('/admin') && !p.startsWith('/ai-');
             }
         }"
         x-show="isVisible()"
         x-on:livewire:navigated.window="$nextTick(() => { $el.style.display = isVisible() ? '' : 'none'; })"
         x-cloak>
        <a href="{{ route('ai-studio.index') }}" wire:navigate title="{{ __('TraKerja AI') }}" aria-label="{{ __('Prepare with TraKerja AI') }}"><x-trakerja-ai-mark /><span>{{ __('Prepare with AI') }}</span></a>
    </div>
@endif
