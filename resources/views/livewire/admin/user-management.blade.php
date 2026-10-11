<div class="admin-users-workspace">
    <!-- Header -->
    <section class="admin-welcome">
        <div>
            <span class="admin-eyebrow">People & access</span>
            <h1>User Management</h1>
            <p>Review and manage members, track job applications, login activity, and permissions in one workspace.</p>
        </div>
        <div class="au-header-actions">
            <button type="button" wire:click="exportUsers" wire:loading.attr="disabled" wire:target="exportUsers" class="au-button">
                <i class="ph ph-download-simple" aria-hidden="true"></i>
                <span wire:loading.remove wire:target="exportUsers">Export CSV</span>
                <span wire:loading wire:target="exportUsers">Preparing…</span>
            </button>
            <button type="button" wire:click="openCreateAdminModal" class="au-button au-button--primary">
                <i class="ph ph-user-plus" aria-hidden="true"></i>
                Add Administrator
            </button>
        </div>
    </section>

    <!-- Member Directory Table Container -->
    <section class="au-directory" aria-labelledby="member-directory-heading">
        <div class="au-directory-heading">
            <div>
                <h2 id="member-directory-heading">
                    Members Directory <span>{{ number_format($users->total()) }}</span>
                </h2>
                <p>All registered TraKerja user accounts with application and activity logs</p>
            </div>
            <span class="au-loading" wire:loading wire:target="search,filterPremium,filterAiAnalyzer,perPage,gotoPage,nextPage,previousPage">
                <i class="ph ph-spinner ph-spin"></i> Updating data…
            </span>
        </div>

        <!-- Filters Toolbar -->
        <div class="au-filters">
            <label class="au-search">
                <span class="sr-only">Search members</span>
                <i class="ph ph-magnifying-glass" aria-hidden="true"></i>
                <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search members by name, email, or ID…">
            </label>
            <label>
                <span class="sr-only">Plan filter</span>
                <select wire:model.live="filterPremium">
                    <option value="all">All Plans</option>
                    <option value="premium">Premium Members</option>
                    <option value="free">Free Members</option>
                </select>
            </label>
            <label>
                <span class="sr-only">AI trial filter</span>
                <select wire:model.live="filterAiAnalyzer">
                    <option value="all">All AI Status</option>
                    <option value="used">AI Trial Used</option>
                    <option value="not_used">AI Trial Unused</option>
                </select>
            </label>
            <label class="au-page-size">
                <span class="sr-only">Rows per page</span>
                <select wire:model.live="perPage">
                    @foreach([10, 20, 50, 100] as $size)
                        <option value="{{ $size }}">{{ $size }} rows</option>
                    @endforeach
                </select>
            </label>
            @if($search !== '' || $filterPremium !== 'all' || $filterAiAnalyzer !== 'all')
                <button type="button" wire:click="resetFilters" class="au-clear">
                    Reset filters <i class="ph ph-x" aria-hidden="true"></i>
                </button>
            @endif
        </div>

        <!-- Table -->
        <div class="au-table-scroll" tabindex="0" aria-label="Members Directory Table">
            <table class="au-table">
                <colgroup>
                    <col style="width: 28%;">
                    <col style="width: 15%;">
                    <col style="width: 23%;">
                    <col style="width: 18%;">
                    <col style="width: 16%;">
                </colgroup>
                <thead>
                    <tr>
                        <th scope="col">Member</th>
                        <th scope="col">Membership Plan</th>
                        <th scope="col">Job Applications</th>
                        <th scope="col">Last Active</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr wire:key="member-{{ $user->id }}">
                            <!-- Column 1: Member Info -->
                            <td>
                                <div class="au-member">
                                    <div class="au-avatar-wrapper">
                                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="au-avatar-img" loading="lazy">
                                    </div>
                                    <div class="au-member-info">
                                        <strong class="au-member-name">{{ $user->name }}</strong>
                                        <span class="au-member-email">{{ $user->email }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Column 2: Plan & Status -->
                            <td>
                                <div class="au-plan-container">
                                    @if($user->isPremium())
                                        <span class="au-plan-badge au-plan-badge--premium">
                                            <i class="ph ph-crown-simple"></i> Premium
                                        </span>
                                        @if($user->premium_until)
                                            <span class="au-plan-sub">Until {{ $user->premium_until->format('d M Y') }}</span>
                                        @else
                                            <span class="au-plan-sub">Active access</span>
                                        @endif
                                    @else
                                        <span class="au-plan-badge au-plan-badge--free">
                                            <i class="ph ph-user"></i> Free
                                        </span>
                                        @if($user->has_used_ai_analyzer_trial)
                                            <span class="au-plan-sub">AI trial used</span>
                                        @endif
                                    @endif
                                </div>
                            </td>

                            <!-- Column 3: Applications Activity (Applied & Declined) -->
                            <td>
                                <div class="au-app-stats">
                                    <span class="au-chip au-chip--applied" title="Total jobs applied">
                                        <i class="ph ph-paper-plane-tilt"></i>
                                        <strong>{{ number_format($user->applied_count) }}</strong> applied
                                    </span>
                                    <span class="au-chip {{ $user->declined_count > 0 ? 'au-chip--declined' : 'au-chip--empty' }}" title="Total applications declined">
                                        <i class="ph ph-x-circle"></i>
                                        <strong>{{ number_format($user->declined_count) }}</strong> declined
                                    </span>
                                </div>
                            </td>

                            <!-- Column 4: Last Activity / Login -->
                            <td>
                                <div class="au-activity">
                                    @if($user->last_activity_at && $user->last_activity_at->diffInMinutes(now()) < 15)
                                        <div class="au-activity-top">
                                            <span class="au-dot au-dot--active"></span>
                                            <strong>Active now</strong>
                                        </div>
                                        <small>Just now</small>
                                    @elseif($user->last_activity_at && $user->last_activity_at->diffInHours(now()) < 24)
                                        <div class="au-activity-top">
                                            <span class="au-dot au-dot--recent"></span>
                                            <span>{{ $user->last_activity_at->diffForHumans() }}</span>
                                        </div>
                                        <small>{{ $user->last_activity_at->format('H:i') }}</small>
                                    @elseif($user->last_activity_at)
                                        <div class="au-activity-top">
                                            <span class="au-dot au-dot--recent"></span>
                                            <span>{{ $user->last_activity_at->diffForHumans() }}</span>
                                        </div>
                                        <small>{{ $user->last_activity_at->format('d M Y') }}</small>
                                    @else
                                        <div class="au-activity-top">
                                            <span class="au-dot au-dot--idle"></span>
                                            <span style="color: #94a3b8;">No login recorded</span>
                                        </div>
                                        <small>Joined {{ $user->created_at->format('d M Y') }}</small>
                                    @endif
                                </div>
                            </td>

                            <!-- Column 5: Manage Access Actions -->
                            <td>
                                <div class="au-row-actions" x-data="{ open: false, left: 0, top: 0 }">
                                    <a href="{{ route('admin.users.show', $user) }}" class="au-manage" aria-label="Manage {{ $user->name }}" wire:navigate>
                                        Manage <i class="ph ph-arrow-up-right" aria-hidden="true"></i>
                                    </a>
                                    <button type="button" class="au-more" x-ref="more"
                                        @click="const rect = $el.getBoundingClientRect(); left = Math.max(12, Math.min(rect.right - 220, window.innerWidth - 232)); top = Math.max(12, Math.min(rect.bottom + 8, window.innerHeight - 200)); open = !open; if(open) $nextTick(() => $refs.firstAction.focus())"
                                        :aria-expanded="open.toString()" aria-controls="member-actions-{{ $user->id }}" aria-label="Action menu for {{ $user->name }}">
                                        <i class="ph ph-dots-three" aria-hidden="true"></i>
                                    </button>

                                    <!-- Dropdown Quick Actions -->
                                    <template x-teleport="body">
                                        <div id="member-actions-{{ $user->id }}" class="au-action-menu" x-cloak x-show="open"
                                            x-transition.opacity.duration.120ms :style="`left: ${left}px; top: ${top}px`"
                                            @click.outside="open = false" @keydown.escape.stop="open = false; $refs.more.focus()"
                                            @scroll.window="open = false" @resize.window="open = false" role="region" aria-label="Quick actions for {{ $user->name }}">
                                            <p>{{ $user->name }}</p>
                                            <a href="{{ route('admin.users.show', $user) }}" wire:navigate x-ref="firstAction" class="au-action-menu-link">
                                                <i class="ph ph-user-circle" aria-hidden="true"></i>
                                                View & Manage Profile
                                            </a>
                                            <button type="button" @click="open = false" wire:click="openPremiumConfirmModal({{ $user->id }})">
                                                <i class="ph ph-crown" aria-hidden="true"></i>
                                                {{ $user->is_premium ? 'Revoke Premium Access' : 'Grant Premium Access' }}
                                            </button>
                                            <button type="button" @click="open = false" wire:click="openSendEmailModal({{ $user->id }})">
                                                <i class="ph ph-paper-plane-tilt" aria-hidden="true"></i>
                                                Send Email
                                            </button>
                                            <button type="button" @click="open = false" wire:click="confirmDeleteUserModal({{ $user->id }})" class="au-danger">
                                                <i class="ph ph-trash" aria-hidden="true"></i>
                                                Delete Account
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="au-empty">
                                    <div class="au-empty-icon"><i class="ph ph-users"></i></div>
                                    <h3>No members found</h3>
                                    <p>{{ $search !== '' || $filterPremium !== 'all' || $filterAiAnalyzer !== 'all' ? 'Try adjusting your search query or reset your active filters.' : 'New members will appear here once they register.' }}</p>
                                    @if($search !== '' || $filterPremium !== 'all' || $filterAiAnalyzer !== 'all')
                                        <button type="button" wire:click="resetFilters" class="au-button" style="margin-top: 14px;">
                                            Reset All Filters
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <footer class="au-directory-footer">
            <span>Showing <strong>{{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }}</strong> of {{ number_format($users->total()) }} members</span>
            <div>{{ $users->links() }}</div>
        </footer>
    </section>

    <!-- =====================================================================
         MODAL 1: Manage Account ($showEditModal)
         ===================================================================== -->
    @if($showEditModal)
        <template x-teleport="body">
            <div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showEditModal" data-dialog-close="$wire.closeModal()" aria-labelledby="showEditModal-title" class="au-dialog-overlay">
                <section class="au-dialog">
                    <header class="au-dialog-heading">
                        <div>
                            <h2 id="showEditModal-title">Manage account</h2>
                            <p>Profile details, membership privileges, and AI credit quotas.</p>
                        </div>
                        <button type="button" wire:click="closeModal" class="au-dialog-close" aria-label="Close dialog">
                            <i class="ph ph-x" aria-hidden="true"></i>
                        </button>
                    </header>
                    <div class="au-dialog-body">
                        @php($editingUser = $editingUserId ? \App\Models\User::find($editingUserId) : null)
                        @if($editingUser)
                            <div class="au-dialog-person">
                                <img src="{{ $editingUser->avatar_url }}" alt="{{ $editingUser->name }}">
                                <div>
                                    <strong>{{ $editingUser->name }}</strong>
                                    <span>{{ $editingUser->email }}</span>
                                </div>
                                <div style="display: flex; gap: 6px;">
                                    <span class="au-plan-badge {{ $editingUser->isAdmin() ? 'au-plan-badge--premium' : 'au-plan-badge--free' }}">
                                        {{ $editingUser->isAdmin() ? 'Admin' : 'Member' }}
                                    </span>
                                    <span class="au-plan-badge {{ $editingUser->isPremium() ? 'au-plan-badge--premium' : 'au-plan-badge--free' }}">
                                        {{ $editingUser->isPremium() ? 'Premium' : 'Free' }}
                                    </span>
                                </div>
                            </div>
                        @endif

                        <form wire:submit="updateUser" class="au-form au-form-section">
                            <h3>Account & Access Information</h3>
                            <label for="edit-member-name">
                                Full Name
                                <input id="edit-member-name" type="text" wire:model="editName" required placeholder="Member full name…">
                                @error('editName')<span class="au-field-error">{{ $message }}</span>@enderror
                            </label>
                            <label for="edit-member-email">
                                Email Address
                                <input id="edit-member-email" type="email" wire:model="editEmail" required placeholder="member@email.com">
                                @error('editEmail')<span class="au-field-error">{{ $message }}</span>@enderror
                            </label>
                            <div class="au-form-actions">
                                <button type="submit" class="au-button au-button--primary" wire:loading.attr="disabled" wire:target="updateUser">
                                    <span wire:loading.remove wire:target="updateUser">Save Account Changes</span>
                                    <span wire:loading wire:target="updateUser">Saving…</span>
                                </button>
                            </div>
                        </form>

                        <form wire:submit="saveAiQuotas" class="au-form au-form-section" style="margin-top: 18px;">
                            <h3>AI Feature Quota Credits</h3>
                            <div class="au-quota-grid">
                                <div class="au-quota-tile">
                                    <label for="credit-editAiCredits">
                                        Analyze CV
                                        <input id="credit-editAiCredits" type="number" wire:model="editAiCredits" min="0" step="1" required>
                                        @error('editAiCredits')<span class="au-field-error">{{ $message }}</span>@enderror
                                    </label>
                                </div>
                                <div class="au-quota-tile">
                                    <label for="credit-editClCredits">
                                        Cover Letter
                                        <input id="credit-editClCredits" type="number" wire:model="editClCredits" min="0" step="1" required>
                                        @error('editClCredits')<span class="au-field-error">{{ $message }}</span>@enderror
                                    </label>
                                </div>
                                <div class="au-quota-tile">
                                    <label for="credit-editPhotoCredits">
                                        AI Photo Studio
                                        <input id="credit-editPhotoCredits" type="number" wire:model="editPhotoCredits" min="0" step="1" required>
                                        @error('editPhotoCredits')<span class="au-field-error">{{ $message }}</span>@enderror
                                    </label>
                                </div>
                            </div>
                            <div class="au-form-actions">
                                <button type="submit" class="au-button au-button--primary" wire:loading.attr="disabled" wire:target="saveAiQuotas">
                                    <span wire:loading.remove wire:target="saveAiQuotas">Save AI Quotas</span>
                                    <span wire:loading wire:target="saveAiQuotas">Saving…</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </template>
    @endif

    <!-- =====================================================================
         MODAL 2: Add Administrator ($showCreateAdminModal)
         ===================================================================== -->
    @if($showCreateAdminModal)
        <template x-teleport="body">
            <div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showCreateAdminModal" data-dialog-close="$wire.closeCreateAdminModal()" aria-labelledby="showCreateAdminModal-title" class="au-dialog-overlay">
                <section class="au-dialog">
                    <header class="au-dialog-heading">
                        <div>
                            <h2 id="showCreateAdminModal-title">Add Administrator</h2>
                            <p>Create a new administrator account with full platform management privileges.</p>
                        </div>
                        <button type="button" wire:click="closeCreateAdminModal" class="au-dialog-close" aria-label="Close dialog">
                            <i class="ph ph-x" aria-hidden="true"></i>
                        </button>
                    </header>
                    <div class="au-dialog-body">
                        <form wire:submit="createAdmin" class="au-form">
                            <label for="admin-name">
                                Full Name
                                <input id="admin-name" type="text" wire:model="newAdminName" required placeholder="Administrator full name…">
                                @error('newAdminName')<span class="au-field-error">{{ $message }}</span>@enderror
                            </label>
                            <label for="admin-email">
                                Email Address
                                <input id="admin-email" type="email" wire:model="newAdminEmail" required placeholder="admin@trakerja.com">
                                @error('newAdminEmail')<span class="au-field-error">{{ $message }}</span>@enderror
                            </label>
                            <label for="admin-password">
                                Password
                                <input id="admin-password" type="password" wire:model="newAdminPassword" required minlength="8" autocomplete="new-password" placeholder="At least 8 characters…">
                                @error('newAdminPassword')<span class="au-field-error">{{ $message }}</span>@enderror
                            </label>
                            <label for="admin-password-confirmation">
                                Confirm Password
                                <input id="admin-password-confirmation" type="password" wire:model="newAdminPasswordConfirmation" required minlength="8" autocomplete="new-password" placeholder="Re-type password…">
                                @error('newAdminPasswordConfirmation')<span class="au-field-error">{{ $message }}</span>@enderror
                            </label>

                            <div class="au-dialog-actions">
                                <button type="button" wire:click="closeCreateAdminModal" class="au-button">Cancel</button>
                                <button type="submit" wire:loading.attr="disabled" wire:target="createAdmin" class="au-button au-button--primary">
                                    <span wire:loading.remove wire:target="createAdmin">Create Administrator</span>
                                    <span wire:loading wire:target="createAdmin">Creating…</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </template>
    @endif

    <!-- =====================================================================
         MODAL 3: Send Email ($showSendEmailModal)
         ===================================================================== -->
    @if($showSendEmailModal)
        <template x-teleport="body">
            <div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showSendEmailModal" data-dialog-close="$wire.closeSendEmailModal()" aria-labelledby="showSendEmailModal-title" class="au-dialog-overlay">
                <section class="au-dialog au-dialog--small">
                    <header class="au-dialog-heading">
                        <div>
                            <h2 id="showSendEmailModal-title">Send Email to Member</h2>
                            <p>Choose an automated email template to dispatch directly to the user's inbox.</p>
                        </div>
                        <button type="button" wire:click="closeSendEmailModal" class="au-dialog-close" aria-label="Close dialog">
                            <i class="ph ph-x" aria-hidden="true"></i>
                        </button>
                    </header>
                    <div class="au-dialog-body">
                        <div class="au-recipient">
                            <strong>{{ $emailTargetUserName }}</strong>
                            <span>{{ $emailTargetUserEmail }}</span>
                        </div>
                        <form wire:submit="sendEmail" class="au-form">
                            <label for="member-email-type">
                                Message Template
                                <select id="member-email-type" wire:model.live="emailType">
                                    @foreach([
                                        'welcome'=>'Welcome Email',
                                        'verification'=>'Account Email Verification',
                                        'verification_reminder'=>'Email Verification Reminder',
                                        'job_reminder'=>'Job Application Reminder',
                                        'monthly_motivation'=>'Monthly Career Motivation',
                                        're_engagement'=>'Re-engagement Notice',
                                        'product_update'=>'TraKerja Feature Update',
                                        'new_vibe'=>'Platform Experience Announcement',
                                        'ai_analyzer'=>'AI CV Analyzer Announcement',
                                        'ai_photo'=>'AI Photo Studio Announcement',
                                        'follow_up_feature'=>'Application Follow-up Alert',
                                        'premium_granted'=>'Premium Access Notification',
                                        'hiring_season'=>'Hiring Season Alert',
                                        'kemerdekaan_ri'=>'Independence Day Special',
                                        'pahlawan'=>'National Heroes Day',
                                        'hari_ibu'=>'Mother\'s Day Greeting',
                                        'natal'=>'Christmas Greeting',
                                        'tahun_baru_islam'=>'Islamic New Year Greeting'
                                    ] as $type => $label)
                                        <option value="{{ $type }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('emailType')<span class="au-field-error">{{ $message }}</span>@enderror
                            </label>
                            <p class="au-form-hint">Email will be delivered immediately via the TraKerja mail system to the recipient above.</p>
                            <div class="au-dialog-actions">
                                <button type="button" wire:click="closeSendEmailModal" class="au-button">Cancel</button>
                                <button type="submit" wire:loading.attr="disabled" wire:target="sendEmail" class="au-button au-button--primary">
                                    <span wire:loading.remove wire:target="sendEmail">Send Email Now</span>
                                    <span wire:loading wire:target="sendEmail">Sending…</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </template>
    @endif

    <!-- =====================================================================
         MODAL 4: Confirm Premium ($showPremiumConfirmModal)
         ===================================================================== -->
    @if($showPremiumConfirmModal)
        <template x-teleport="body">
            <div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showPremiumConfirmModal" data-dialog-close="$wire.closePremiumConfirmModal()" aria-labelledby="showPremiumConfirmModal-title" class="au-dialog-overlay">
                <section class="au-dialog au-dialog--small">
                    <header class="au-dialog-heading">
                        <div>
                            <h2 id="showPremiumConfirmModal-title">
                                {{ $confirmTargetUserIsPremium ? 'Revoke premium access?' : 'Grant premium access?' }}
                            </h2>
                            <p>Confirm the membership tier change for this user account.</p>
                        </div>
                        <button type="button" wire:click="closePremiumConfirmModal" class="au-dialog-close" aria-label="Close dialog">
                            <i class="ph ph-x" aria-hidden="true"></i>
                        </button>
                    </header>
                    <div class="au-dialog-body">
                        <div class="au-recipient au-recipient--warning">
                            <strong>{{ $confirmTargetUserName }}</strong>
                            <span>
                                {{ $confirmTargetUserIsPremium 
                                    ? 'Premium access will be deactivated. Exclusive AI features and priority quotas will return to standard free tier.' 
                                    : 'Premium access will be activated for ' . config('pakasir.premium_duration_days', 365) . ' days with maximized AI feature quotas.' }}
                            </span>
                        </div>
                        <div class="au-dialog-actions">
                            <button type="button" wire:click="closePremiumConfirmModal" class="au-button">Cancel</button>
                            <button type="button" wire:click="toggleManualPremium" wire:loading.attr="disabled" wire:target="toggleManualPremium" class="au-button au-button--primary">
                                <span wire:loading.remove wire:target="toggleManualPremium">{{ $confirmTargetUserIsPremium ? 'Yes, Revoke Premium' : 'Yes, Grant Premium' }}</span>
                                <span wire:loading wire:target="toggleManualPremium">Processing…</span>
                            </button>
                        </div>
                    </div>
                </section>
            </div>
        </template>
    @endif

    <!-- =====================================================================
         MODAL 5: Confirm Delete ($showDeleteConfirmModal)
         ===================================================================== -->
    @if($showDeleteConfirmModal)
        <template x-teleport="body">
            <div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showDeleteConfirmModal" data-dialog-close="$wire.closeDeleteConfirmModal()" aria-labelledby="showDeleteConfirmModal-title" class="au-dialog-overlay">
                <section class="au-dialog au-dialog--small">
                    <header class="au-dialog-heading">
                        <div>
                            <h2 id="showDeleteConfirmModal-title" style="color: #ef4444;">Delete this account?</h2>
                            <p>This action is irreversible and will delete all user data permanently.</p>
                        </div>
                        <button type="button" wire:click="closeDeleteConfirmModal" class="au-dialog-close" aria-label="Close dialog">
                            <i class="ph ph-x" aria-hidden="true"></i>
                        </button>
                    </header>
                    <div class="au-dialog-body">
                        <div class="au-recipient au-recipient--danger">
                            <strong>{{ $deleteTargetUserName }}</strong>
                            <span>All account data, tracked job applications, activity records, and resumes will be permanently purged from the database.</span>
                        </div>
                        <div class="au-dialog-actions">
                            <button type="button" wire:click="closeDeleteConfirmModal" class="au-button">Cancel, Keep Account</button>
                            <button type="button" wire:click="performDelete" wire:loading.attr="disabled" wire:target="performDelete" class="au-button au-button--danger">
                                <span wire:loading.remove wire:target="performDelete">Permanently Delete</span>
                                <span wire:loading wire:target="performDelete">Deleting…</span>
                            </button>
                        </div>
                    </div>
                </section>
            </div>
        </template>
    @endif
</div>
