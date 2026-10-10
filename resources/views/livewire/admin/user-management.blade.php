<div class="admin-users-workspace">
    <section class="admin-welcome">
        <div><span class="admin-eyebrow">People & access</span><h1>User Management</h1><p>Find a member, review their account, and manage access in one place.</p></div>
        <div class="au-header-actions"><button type="button" wire:click="exportUsers" wire:loading.attr="disabled" wire:target="exportUsers" class="au-button"><i class="ph ph-download-simple" aria-hidden="true"></i><span wire:loading.remove wire:target="exportUsers">Export users</span><span wire:loading wire:target="exportUsers">Preparing…</span></button><button type="button" wire:click="openCreateAdminModal" class="au-button au-button--primary"><i class="ph ph-plus" aria-hidden="true"></i>Add admin</button></div>
    </section>
    <section class="au-directory" aria-labelledby="member-directory-heading">
        <div class="au-directory-heading"><div><h2 id="member-directory-heading">Member directory <span>{{ number_format($users->total()) }}</span></h2><p>Regular user accounts · filtered results</p></div><span class="au-loading" wire:loading wire:target="search,filterPremium,filterAiAnalyzer,perPage,gotoPage,nextPage,previousPage">Updating results…</span></div>
        <div class="au-filters">
            <label class="au-search"><span class="sr-only">Search members by name or email</span><i class="ph ph-magnifying-glass" aria-hidden="true"></i><input type="search" wire:model.live.debounce.300ms="search" placeholder="Search name or email…"></label>
            <label><span class="sr-only">Premium assignment</span><select wire:model.live="filterPremium"><option value="all">All plans</option><option value="premium">Premium assigned</option><option value="free">Free assigned</option></select></label>
            <label><span class="sr-only">AI analyzer trial</span><select wire:model.live="filterAiAnalyzer"><option value="all">All AI trials</option><option value="used">Trial used</option><option value="not_used">Trial not used</option></select></label>
            <label class="au-page-size"><span class="sr-only">Rows per page</span><select wire:model.live="perPage">@foreach([10,20,50,100] as $size)<option value="{{ $size }}">{{ $size }} rows</option>@endforeach</select></label>
            @if($search !== '' || $filterPremium !== 'all' || $filterAiAnalyzer !== 'all')<button type="button" wire:click="resetFilters" class="au-clear">Clear filters <i class="ph ph-x" aria-hidden="true"></i></button>@endif
        </div>
        <div class="au-table-scroll" tabindex="0" aria-label="Member directory table">
            <table class="au-table au-table--simple"><thead><tr><th scope="col">Member</th><th scope="col" class="au-actions-heading">Manage access</th></tr></thead><tbody>
                @forelse($users as $user)
                    <tr wire:key="member-{{ $user->id }}">
                        <td><div class="au-member"><img src="{{ $user->avatar_url }}" alt="" loading="lazy"><div><strong>{{ $user->name }}</strong><span>{{ $user->email }}</span></div></div></td>
                        <td><div class="au-row-actions" x-data="{ open: false, left: 0, top: 0 }">
                            <button type="button" wire:click="editUser({{ $user->id }})" class="au-manage" aria-label="Manage {{ $user->name }}">Manage <i class="ph ph-arrow-up-right" aria-hidden="true"></i></button>
                            <button type="button" class="au-more" x-ref="more" @click="const rect = $el.getBoundingClientRect(); left = Math.max(12, Math.min(rect.right - 220, window.innerWidth - 232)); top = Math.max(12, Math.min(rect.bottom + 8, window.innerHeight - 200)); open = !open; if(open) $nextTick(() => $refs.firstAction.focus())" :aria-expanded="open.toString()" aria-controls="member-actions-{{ $user->id }}" aria-label="More actions for {{ $user->name }}"><i class="ph ph-dots-three" aria-hidden="true"></i></button>
                            <template x-teleport="body"><div id="member-actions-{{ $user->id }}" class="au-action-menu" x-cloak x-show="open" x-transition.opacity.duration.120ms :style="`left: ${left}px; top: ${top}px`" @click.outside="open = false" @keydown.escape.stop="open = false; $refs.more.focus()" @scroll.window="open = false" @resize.window="open = false" role="region" aria-label="Actions for {{ $user->name }}">
                                <p>{{ $user->name }}</p>
                                <button type="button" x-ref="firstAction" @click="open = false" wire:click="openPremiumConfirmModal({{ $user->id }})"><i class="ph ph-crown" aria-hidden="true"></i>{{ $user->is_premium ? 'Remove premium' : 'Grant premium' }}</button>
                                <button type="button" @click="open = false" wire:click="openSendEmailModal({{ $user->id }})"><i class="ph ph-envelope" aria-hidden="true"></i>Send email</button>
                                <button type="button" @click="open = false" wire:click="confirmDeleteUserModal({{ $user->id }})" class="au-danger"><i class="ph ph-trash" aria-hidden="true"></i>Delete account</button>
                            </div></template>
                        </div></td>
                    </tr>
                @empty<tr><td colspan="2"><div class="au-empty"><h3>No members found</h3><p>{{ $search !== '' || $filterPremium !== 'all' || $filterAiAnalyzer !== 'all' ? 'Try another search or clear your filters.' : 'New registrations will appear here.' }}</p>@if($search !== '' || $filterPremium !== 'all' || $filterAiAnalyzer !== 'all')<button type="button" wire:click="resetFilters" class="au-button">Clear filters</button>@endif</div></td></tr>@endforelse
            </tbody></table>
        </div>
        <footer class="au-directory-footer"><span>Showing <strong>{{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }}</strong> of {{ number_format($users->total()) }} members</span><div>{{ $users->links() }}</div></footer>
    </section>
    @if($showEditModal)
        <template x-teleport="body"><div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showEditModal" data-dialog-close="$wire.closeModal()" aria-labelledby="showEditModal-title" class="au-dialog-overlay"><section class="au-dialog">
            <header class="au-dialog-heading"><div><h2 id="showEditModal-title">Manage account</h2><p>Profile, access, and AI credits.</p></div><button type="button" wire:click="closeModal" class="au-dialog-close" aria-label="Close dialog"><i class="ph ph-x" aria-hidden="true"></i></button></header>
            <div class="au-dialog-body">
    @php($editingUser = $editingUserId ? \App\Models\User::find($editingUserId) : null)
    @if($editingUser)<div class="au-dialog-person"><img src="{{ $editingUser->avatar_url }}" alt=""><div><strong>{{ $editingUser->name }}</strong><span>{{ $editingUser->email }}</span></div><span class="au-badge">{{ $editingUser->isAdmin() ? 'Admin' : 'Member' }}</span></div>@endif
    @if($editingUser)
    <dl class="au-account-details">
        <div><dt>Account</dt><dd>{{ $editingUser->isPremium() ? 'Premium' : ($editingUser->is_premium ? 'Inactive premium' : 'Free') }}</dd></div>
        <div><dt>Email status</dt><dd>{{ $editingUser->hasVerifiedEmail() ? 'Verified' : 'Unverified' }}</dd></div>
        <div><dt>Joined</dt><dd>{{ $editingUser->created_at->format('d M Y') }}</dd></div>
        <div><dt>Premium until</dt><dd>{{ $editingUser->premium_until?->format('d M Y') ?? ($editingUser->isPremium() ? 'No expiry recorded' : '—') }}</dd></div>
        <div><dt>Sign-in</dt><dd>{{ collect([$editingUser->password ? 'Email' : null, $editingUser->google_id ? 'Google' : null, $editingUser->linkedin_id ? 'LinkedIn' : null])->filter()->implode(' · ') ?: 'Not recorded' }}</dd></div>
    </dl>
    @endif
    <form wire:submit="updateUser" class="au-form">
        <h3>Profile & access</h3>
        <label for="edit-member-name">Full name<input id="edit-member-name" type="text" wire:model="editName" required>@error('editName')<span class="au-field-error">{{ $message }}</span>@enderror</label>
        <label for="edit-member-email">Email address<input id="edit-member-email" type="email" wire:model="editEmail" required>@error('editEmail')<span class="au-field-error">{{ $message }}</span>@enderror</label>
        <div class="au-checkbox-row"><label><input type="checkbox" wire:model="editIsPremium">Premium assigned</label><label><input type="checkbox" wire:model="editIsAdmin">Administrator</label></div>
        <p class="au-form-hint">Changing premium access updates the payment status and membership duration.</p>
        <div class="au-form-actions"><button type="submit" class="au-button au-button--primary" wire:loading.attr="disabled" wire:target="updateUser">Save account</button></div>
    </form>
    <form wire:submit="saveAiQuotas" class="au-form au-form--credits"><div class="au-form-section-heading"><h3>AI credits</h3><button type="button" class="au-text-button" wire:click="resetAllAiQuotas" wire:confirm="Reset this member's credits and analyzer trial to the plan defaults?">Reset to plan defaults</button></div>
        <div class="au-quota-grid">@foreach(['editAiCredits' => 'Analyze CV', 'editClCredits' => 'Cover letter', 'editPhotoCredits' => 'Photo studio'] as $model => $label)<label for="credit-{{ $model }}">{{ $label }}<input id="credit-{{ $model }}" type="number" wire:model="{{ $model }}" min="0" step="1" required>@error($model)<span class="au-field-error">{{ $message }}</span>@enderror</label>@endforeach</div>
        <p class="au-form-hint">Analyzer trial: {{ $editingUser?->has_used_ai_analyzer_trial ? 'used' : 'not used' }}. Credit changes are saved separately.</p>
        <div class="au-form-actions"><button type="submit" class="au-button au-button--primary" wire:loading.attr="disabled" wire:target="saveAiQuotas">Save credits</button></div>
    </form>
    @if($editingUser)
    <section class="au-account-actions" aria-label="Account actions"><h3>Account actions</h3><div>
        <button type="button" wire:click="openPremiumConfirmModal({{ $editingUser->id }})" class="au-button">{{ $editingUser->is_premium ? 'Remove premium' : 'Grant premium' }}</button>
        <button type="button" wire:click="openSendEmailModal({{ $editingUser->id }})" class="au-button">Send email</button>
        <button type="button" wire:click="confirmDeleteUserModal({{ $editingUser->id }})" class="au-text-button au-account-delete">Delete account</button>
    </div></section>
    @endif
</div>
        </section></div></template>
    @endif
    @if($showCreateAdminModal)
        <template x-teleport="body"><div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showCreateAdminModal" data-dialog-close="$wire.closeCreateAdminModal()" aria-labelledby="showCreateAdminModal-title" class="au-dialog-overlay"><section class="au-dialog">
            <header class="au-dialog-heading"><div><h2 id="showCreateAdminModal-title">Add administrator</h2><p>Create an account with access to the admin workspace.</p></div><button type="button" wire:click="closeCreateAdminModal" class="au-dialog-close" aria-label="Close dialog"><i class="ph ph-x" aria-hidden="true"></i></button></header>
            <div class="au-dialog-body">
    <form wire:submit="createAdmin" class="au-form">
        @foreach(['newAdminName' => ['Name', 'text'], 'newAdminEmail' => ['Email address', 'email'], 'newAdminPassword' => ['Password', 'password'], 'newAdminPasswordConfirmation' => ['Confirm password', 'password']] as $model => [$label, $type])<label for="admin-{{ $model }}">{{ $label }}<input id="admin-{{ $model }}" type="{{ $type }}" wire:model="{{ $model }}" required @if($type === 'password') autocomplete="new-password" minlength="8" @endif>@error($model)<span class="au-field-error">{{ $message }}</span>@enderror</label>@endforeach
        <div class="au-dialog-actions"><button type="button" wire:click="closeCreateAdminModal" class="au-button">Cancel</button><button type="submit" wire:loading.attr="disabled" wire:target="createAdmin" class="au-button au-button--primary">Create admin</button></div>
    </form>
</div>
        </section></div></template>
    @endif
    @if($showSendEmailModal)
        <template x-teleport="body"><div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showSendEmailModal" data-dialog-close="$wire.closeSendEmailModal()" aria-labelledby="showSendEmailModal-title" class="au-dialog-overlay"><section class="au-dialog au-dialog--small">
            <header class="au-dialog-heading"><div><h2 id="showSendEmailModal-title">Send an email</h2><p>Choose a message for this member. Nothing is sent until you confirm.</p></div><button type="button" wire:click="closeSendEmailModal" class="au-dialog-close" aria-label="Close dialog"><i class="ph ph-x" aria-hidden="true"></i></button></header>
            <div class="au-dialog-body">
    <div class="au-recipient"><strong>{{ $emailTargetUserName }}</strong><span>{{ $emailTargetUserEmail }}</span></div>
    <form wire:submit="sendEmail" class="au-form"><label for="member-email-type">Message template<select id="member-email-type" wire:model.live="emailType">
        @foreach(['welcome'=>'Welcome', 'verification'=>'Email verification', 'verification_reminder'=>'Verification reminder', 'job_reminder'=>'Application reminder', 'monthly_motivation'=>'Monthly motivation', 're_engagement'=>'Re-engagement', 'product_update'=>'Product update', 'new_vibe'=>'Workspace announcement', 'ai_analyzer'=>'AI analyzer announcement', 'ai_photo'=>'AI photo announcement', 'follow_up_feature'=>'Follow-up announcement', 'premium_granted'=>'Premium access granted', 'hiring_season'=>'Hiring season', 'idul_adha'=>'Idul Adha', 'waisak'=>'Waisak', 'pancasila'=>'Pancasila Day', 'sumpah_pemuda'=>'Youth Pledge Day', 'pahlawan'=>'Heroes Day', 'guru_nasional'=>'Teachers Day', 'hari_ibu'=>'Mother’s Day', 'natal'=>'Christmas', 'kemerdekaan_ri'=>'Independence Day', 'maulid_nabi'=>'Maulid Nabi', 'tahun_baru_islam'=>'Islamic New Year'] as $type => $label)<option value="{{ $type }}">{{ $label }}</option>@endforeach
    </select>@error('emailType')<span class="au-field-error">{{ $message }}</span>@enderror</label>
    <p class="au-form-hint">The selected template will be sent to the email address above.</p><div class="au-dialog-actions"><button type="button" wire:click="closeSendEmailModal" class="au-button">Cancel</button><button type="submit" wire:loading.attr="disabled" wire:target="sendEmail" class="au-button au-button--primary"><span wire:loading.remove wire:target="sendEmail">Send email</span><span wire:loading wire:target="sendEmail">Sending…</span></button></div></form>
</div>
        </section></div></template>
    @endif
    @if($showPremiumConfirmModal)
        <template x-teleport="body"><div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showPremiumConfirmModal" data-dialog-close="$wire.closePremiumConfirmModal()" aria-labelledby="showPremiumConfirmModal-title" class="au-dialog-overlay"><section class="au-dialog au-dialog--small">
            <header class="au-dialog-heading"><div><h2 id="showPremiumConfirmModal-title">{{ $confirmTargetUserIsPremium ? 'Remove premium access?' : 'Grant premium access?' }}</h2><p>Review this membership change.</p></div><button type="button" wire:click="closePremiumConfirmModal" class="au-dialog-close" aria-label="Close dialog"><i class="ph ph-x" aria-hidden="true"></i></button></header>
            <div class="au-dialog-body">
    <div class="au-recipient"><strong>{{ $confirmTargetUserName }}</strong><span>{{ $confirmTargetUserIsPremium ? 'Premium will be removed and plan credits recalculated.' : 'Premium will be activated for '.config('pakasir.premium_duration_days', 365).' days and plan credits recalculated.' }}</span></div>
    <div class="au-dialog-actions"><button type="button" wire:click="closePremiumConfirmModal" class="au-button">Cancel</button><button type="button" wire:click="toggleManualPremium" wire:loading.attr="disabled" wire:target="toggleManualPremium" class="au-button au-button--primary">{{ $confirmTargetUserIsPremium ? 'Remove premium' : 'Grant premium' }}</button></div>
</div>
        </section></div></template>
    @endif
    @if($showDeleteConfirmModal)
        <template x-teleport="body"><div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showDeleteConfirmModal" data-dialog-close="$wire.closeDeleteConfirmModal()" aria-labelledby="showDeleteConfirmModal-title" class="au-dialog-overlay"><section class="au-dialog au-dialog--small">
            <header class="au-dialog-heading"><div><h2 id="showDeleteConfirmModal-title">Delete this account?</h2><p>This action cannot be undone.</p></div><button type="button" wire:click="closeDeleteConfirmModal" class="au-dialog-close" aria-label="Close dialog"><i class="ph ph-x" aria-hidden="true"></i></button></header>
            <div class="au-dialog-body">
    <div class="au-recipient au-recipient--danger"><strong>{{ $deleteTargetUserName }}</strong><span>The account and its related records will be permanently deleted.</span></div>
    <div class="au-dialog-actions"><button type="button" wire:click="closeDeleteConfirmModal" class="au-button">Keep account</button><button type="button" wire:click="performDelete" wire:loading.attr="disabled" wire:target="performDelete" class="au-button au-button--danger">Delete account</button></div>
</div>
        </section></div></template>
    @endif
</div>
