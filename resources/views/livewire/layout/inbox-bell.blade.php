<div class="workspace-inbox" @keydown.escape.window="$wire.open && $wire.toggle()" @click.outside="$wire.open && $wire.toggle()">
    <button type="button" wire:click="toggle" class="workspace-inbox-button" aria-expanded="{{ $open ? 'true' : 'false' }}" aria-controls="workspace-inbox-panel"
            aria-label="{{ $unread ? trans_choice('{1} Notifications, 1 unread|[2,*] Notifications, :count unread', $unread, ['count' => $unread]) : __('Notifications') }}">
        <i class="ph ph-bell" aria-hidden="true"></i>
        @if($unread)<span class="workspace-inbox-count" aria-hidden="true">{{ $unread > 9 ? '9+' : $unread }}</span>@endif
    </button>
    @if($open)
        <div id="workspace-inbox-panel" class="ib-panel" role="region" aria-label="{{ __('Notifications') }}">
            <div class="ib-head">
                <strong>{{ __('Notifications') }}@if($unread)<span class="ib-count">{{ $unread }}</span>@endif</strong>
                @if($unread)<button type="button" wire:click="markAllRead"><i class="ph ph-checks" aria-hidden="true"></i>{{ __('Mark all as read') }}</button>@endif
            </div>
            <div class="ib-list">
                @forelse($items->groupBy(fn ($n) => $n->created_at->isToday() ? 'today' : 'earlier') as $group => $notes)
                    <p class="ib-group">{{ $group === 'today' ? __('Today') : __('Earlier') }}</p>
                    @foreach($notes as $item)
                        @php($view = \App\Livewire\Layout\InboxBell::present($item))
                        <button type="button" wire:key="inbox-{{ $item->id }}" wire:click="visit('{{ $item->id }}')" class="ib-item {{ $item->read_at ? '' : 'is-unread' }}">
                            <span class="ib-icon ib-tone-{{ $view['tone'] }}"><i class="ph {{ $view['icon'] }}" aria-hidden="true"></i></span>
                            <span class="ib-body">
                                <strong>{{ $view['title'] }}</strong>
                                @if($view['text'])<small>{{ $view['text'] }}</small>@endif
                                <time datetime="{{ $item->created_at->toIso8601String() }}">{{ $item->created_at->locale(app()->getLocale())->diffForHumans() }}</time>
                            </span>
                            @unless($item->read_at)<span class="ib-dot" aria-label="{{ __('Unread') }}"></span>@endunless
                        </button>
                    @endforeach
                @empty
                    <div class="ib-empty"><i class="ph ph-bell-simple" aria-hidden="true"></i><p>{{ __('No notifications yet. Turn on alerts for a saved search in Explore to hear about new jobs.') }}</p></div>
                @endforelse
            </div>
        </div>
    @endif
</div>
