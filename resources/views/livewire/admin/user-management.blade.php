<div class="am-directory">
    <header class="am-page-heading">
        <div><h1>{{ __('Members') }}</h1><p>{{ __('Manage member access, review activity, and keep accounts up to date.') }}</p></div>
        <div class="am-heading-actions">
            <button type="button" wire:click="exportUsers" wire:loading.attr="disabled" wire:target="exportUsers" class="am-button"><i class="ph ph-download-simple" aria-hidden="true"></i><span wire:loading.remove wire:target="exportUsers">{{ __('Export CSV') }}</span><span wire:loading wire:target="exportUsers">{{ __('Preparing…') }}</span></button>
            <button type="button" wire:click="openCreateAdminModal" class="am-button am-button--primary"><i class="ph ph-user-plus" aria-hidden="true"></i>{{ __('Add Administrator') }}</button>
        </div>
    </header>
    <section class="ac-stats am-stats" aria-label="{{ __('Member overview') }}">
        @foreach([
            [__('Total members'), $stats['total'], __('Excluding administrators'), 'users'],
            [__('Premium enabled'), $stats['premium'], __('Accounts assigned premium'), 'credit-card'],
            [__('Email verified'), $stats['verified'], __('Confirmed email addresses'), 'seal-check'],
            [__('AI trial used'), $stats['ai_analyzer_trial_used'], __('Members who tried CV analysis'), 'file-text'],
        ] as [$label, $value, $hint, $icon])
            <article><span class="ac-stat-icon"><i class="ph-fill ph-{{ $icon }}" aria-hidden="true"></i></span><div><h2>{{ $label }}</h2><div class="ac-stat-value"><strong>{{ number_format($value) }}</strong><span class="ac-stat-note--neutral">{{ $hint }}</span></div></div></article>
        @endforeach
    </section>
    <section class="am-directory-panel" aria-labelledby="am-directory-title">
        <div class="am-directory-heading"><div><h2 id="am-directory-title">{{ __('Member directory') }}<span class="am-count">{{ number_format($users->total()) }}</span></h2><p>{{ __('Regular member accounts. Administrators are excluded from this list.') }}</p></div><span class="am-loading" role="status" wire:loading wire:target="search,filterPremium,filterAiAnalyzer,perPage,gotoPage,nextPage,previousPage"><i class="ph ph-spinner ph-spin" aria-hidden="true"></i>{{ __('Updating data…') }}</span></div>
        <div class="am-toolbar">
            <label class="am-search"><i class="ph ph-magnifying-glass" aria-hidden="true"></i><span class="sr-only">{{ __('Search members') }}</span><input type="search" wire:model.live.debounce.300ms="search" placeholder="{{ __('Search by name or email') }}"></label>
            <label class="am-filter"><span>{{ __('Membership') }}</span><select wire:model.live="filterPremium"><option value="all">{{ __('All Plans') }}</option><option value="premium">{{ __('Premium enabled') }}</option><option value="free">{{ __('Premium disabled') }}</option></select></label>
            <label class="am-filter"><span>{{ __('CV analysis trial') }}</span><select wire:model.live="filterAiAnalyzer"><option value="all">{{ __('All AI Status') }}</option><option value="used">{{ __('AI Trial Used') }}</option><option value="not_used">{{ __('AI Trial Unused') }}</option></select></label>
            @if($search !== '' || $filterPremium !== 'all' || $filterAiAnalyzer !== 'all')<button type="button" wire:click="resetFilters" class="am-reset"><i class="ph ph-x" aria-hidden="true"></i>{{ __('Reset filters') }}</button>@endif
        </div>
        <div class="am-table-wrap" role="region" tabindex="0" aria-label="{{ __('Member directory') }}" wire:loading.class="am-is-loading" wire:target="search,filterPremium,filterAiAnalyzer,perPage,gotoPage,nextPage,previousPage">
            <table class="am-table" role="table"><thead><tr><th scope="col">{{ __('Member') }}</th><th scope="col">{{ __('Membership') }}</th><th scope="col">{{ __('Applications') }}</th><th scope="col">{{ __('Email status') }}</th><th scope="col">{{ __('Last active') }}</th><th scope="col"><span class="sr-only">{{ __('Actions') }}</span></th></tr></thead><tbody>
                @forelse($users as $member)
                    <tr wire:key="member-{{ $member->id }}" class="am-member-row">
                        <td><a class="am-person" href="{{ route('admin.users.show', $member) }}" wire:navigate><span class="am-avatar"><span>{{ mb_strtoupper(mb_substr($member->name, 0, 1)) }}</span><img src="{{ $member->avatar_url }}" alt="" width="38" height="38" loading="lazy" x-on:error="$el.remove()"></span><span><strong>{{ $member->name }}</strong><small>{{ $member->email }}</small></span></a></td>
                        <td data-label="{{ __('Membership') }}"><span class="am-badge {{ $member->isPremium() ? 'am-badge--blue' : '' }}">{{ $member->isPremium() ? __('Premium') : __('Free') }}</span><small>{{ $member->is_premium && !$member->isPremium() ? __('Premium inactive') : ($member->premium_until && $member->isPremium() ? __('Until :value1', ['value1' => $member->premium_until->locale(app()->getLocale())->translatedFormat('d M Y')]) : ($member->isPremium() ? __('Active access') : __('Standard access'))) }}</small></td>
                        <td data-label="{{ __('Applications') }}"><strong class="am-table-number">{{ number_format($member->applied_count) }}</strong><small>{{ __(':value1 declined', ['value1' => number_format($member->declined_count)]) }}</small></td>
                        <td data-label="{{ __('Email status') }}"><span class="am-verification {{ $member->hasVerifiedEmail() ? 'is-verified' : '' }}"><i class="ph ph-{{ $member->hasVerifiedEmail() ? 'seal-check' : 'clock' }}" aria-hidden="true"></i>{{ $member->hasVerifiedEmail() ? __('Verified') : __('Unverified') }}</span><small>{{ __('Joined :value1', ['value1' => $member->created_at->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M Y')]) }}</small></td>
                        <td data-label="{{ __('Last active') }}"><span class="am-last-active">{{ $member->last_activity_at ? $member->last_activity_at->diffForHumans() : __('No activity') }}</span><small>{{ __('Member #:value1', ['value1' => $member->id]) }}</small></td>
                        <td class="am-row-tools"><div class="am-actions" x-data="{ open: false, left: 0, top: 0 }"><a href="{{ route('admin.users.show', $member) }}" wire:navigate class="am-view" aria-label="{{ __('Manage :value1', ['value1' => $member->name]) }}">{{ __('View profile') }}<i class="ph ph-caret-right" aria-hidden="true"></i></a><button type="button" class="am-icon-button" x-ref="more" :aria-expanded="open.toString()" aria-controls="member-actions-{{ $member->id }}" aria-label="{{ __('Action menu for :value1', ['value1' => $member->name]) }}" @click="const rect = $el.getBoundingClientRect(); left = Math.max(12, Math.min(rect.right - 224, window.innerWidth - 236)); top = Math.max(12, Math.min(rect.bottom + 8, window.innerHeight - 290)); open = !open; if(open) $nextTick(() => $refs.firstAction.focus())"><i class="ph ph-dots-three" aria-hidden="true"></i></button>
                            <template x-teleport="body"><div id="member-actions-{{ $member->id }}" class="am-quick-menu" role="region" aria-label="{{ __('Quick actions for :value1', ['value1' => $member->name]) }}" x-cloak x-show="open" :style="{left: left + 'px', top: top + 'px'}" @click.outside="open = false" @keydown.escape.stop="open = false; $refs.more.focus()" @scroll.window="open = false" @resize.window="open = false"><p>{{ $member->name }}</p><a href="{{ route('admin.users.show', $member) }}" wire:navigate x-ref="firstAction"><i class="ph ph-user-circle" aria-hidden="true"></i>{{ __('View profile') }}</a><button type="button" @click="open = false; $refs.more.focus()" wire:click="openPremiumConfirmModal({{ $member->id }})"><i class="ph ph-credit-card" aria-hidden="true"></i>{{ $member->is_premium ? __('Revoke Premium Access') : __('Grant Premium Access') }}</button><button type="button" @click="open = false; $refs.more.focus()" wire:click="openSendEmailModal({{ $member->id }})"><i class="ph ph-envelope-simple" aria-hidden="true"></i>{{ __('Send Email') }}</button><button type="button" @click="open = false; $refs.more.focus()" wire:click="confirmDeleteUserModal({{ $member->id }})" class="am-danger-text"><i class="ph ph-trash" aria-hidden="true"></i>{{ __('Delete Account') }}</button></div></template>
                        </div></td>
                    </tr>
                @empty
                    <tr><td colspan="6"><div class="am-empty"><i class="ph ph-users" aria-hidden="true"></i><h3>{{ __('No members found') }}</h3><p>{{ __('Try another search or reset your filters.') }}</p>@if($search !== '' || $filterPremium !== 'all' || $filterAiAnalyzer !== 'all')<button type="button" wire:click="resetFilters" class="am-button">{{ __('Reset filters') }}</button>@endif</div></td></tr>
                @endforelse
            </tbody></table>
        </div>
        <footer class="am-directory-footer"><div><span>{{ __('Showing') }} <strong>{{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }}</strong> {{ __('of :value1 members', ['value1' => number_format($users->total())]) }}</span><label><span class="sr-only">{{ __('Rows per page') }}</span><select wire:model.live="perPage">@foreach([10, 20, 50, 100] as $size)<option value="{{ $size }}">{{ __(':value1 rows', ['value1' => $size]) }}</option>@endforeach</select></label></div><div class="am-pagination">{{ $users->links() }}</div></footer>
    </section>
    @include('livewire.admin.partials.member-dialogs')
</div>
