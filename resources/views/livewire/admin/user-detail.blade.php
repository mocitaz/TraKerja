<div class="profile-workspace" 
     x-data="{ 
        tab: new URLSearchParams(window.location.search).get('tab') || 'account',
        selectTab(t) {
            this.tab = t;
            const url = new URL(window.location);
            url.searchParams.set('tab', t);
            window.history.replaceState({}, '', url);
        }
     }">
    <div class="profile-container">
        <!-- Page Header -->
        <header class="profile-page-header">
            <div>
                <p class="profile-eyebrow">
                    <a href="{{ route('admin.users') }}" wire:navigate style="color: inherit; text-decoration: none;">Member Directory</a> · Member #{{ $user->id }}
                </p>
                <h1>{{ $user->name }}</h1>
                <p>Manage member credentials, feature entitlements, job applications, and activity telemetry.</p>
            </div>
            <div class="profile-page-header-actions">
                <a href="{{ route('admin.users') }}" class="profile-back" wire:navigate>
                    <i class="ph ph-arrow-left" aria-hidden="true"></i> Back to members
                </a>
                <button type="button" wire:click="openSendEmailModal" class="profile-secondary">
                    Send Email
                </button>
                <button type="button" wire:click="$set('showPremiumConfirmModal', true)" class="profile-secondary">
                    {{ $user->is_premium ? 'Revoke Premium' : 'Grant Premium' }}
                </button>
                <button type="button" wire:click="confirmDelete" class="profile-danger-button">
                    Delete
                </button>
            </div>
        </header>

        <!-- Two Column Layout: Identity Sidebar & Content Tabs -->
        <div class="profile-layout">
            <!-- Left Sticky Sidebar: Identity Card -->
            <aside class="profile-identity-card">
                <div class="profile-avatar-frame">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}'s profile image" width="88" height="88">
                </div>
                <h2>{{ $user->name }}</h2>
                <p class="profile-identity-email">{{ $user->email }}</p>
                <span class="profile-plan">
                    <i class="ph {{ $user->isAdmin() ? 'ph-shield-check' : ($user->isPremium() ? 'ph-crown' : 'ph-user') }}" aria-hidden="true"></i>
                    {{ $user->isAdmin() ? 'Administrator' : ($user->isPremium() ? 'Premium member' : 'Free account') }}
                </span>
                <button type="button" wire:click="openSendEmailModal" class="profile-secondary" style="width: 100%;">
                    Send direct email
                </button>
                <dl class="profile-account-facts">
                    <div><dt>Account ID</dt><dd>#{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</dd></div>
                    <div><dt>Member since</dt><dd>{{ $user->created_at->format('d M Y') }}</dd></div>
                    <div><dt>Email status</dt><dd>{{ $user->hasVerifiedEmail() ? 'Verified' : 'Unverified' }}</dd></div>
                    <div><dt>Auth method</dt><dd>{{ $user->google_id ? 'Google' : ($user->linkedin_id ? 'LinkedIn' : 'Email & Password') }}</dd></div>
                    <div><dt>Applications</dt><dd>{{ number_format($totalApplications) }} tracked</dd></div>
                    <div><dt>AI credits</dt><dd>{{ number_format($user->ai_credits) }} credits</dd></div>
                    <div><dt>Status</dt><dd>{{ $user->last_activity_at && $user->last_activity_at->diffInMinutes(now()) < 15 ? 'Active now' : ($user->last_activity_at ? 'Active' : 'Idle') }}</dd></div>
                    <div><dt>Last active</dt><dd>{{ $user->last_activity_at ? $user->last_activity_at->diffForHumans() : 'No activity' }}</dd></div>
                </dl>
                @if($user->isAdmin())
                    <div class="profile-admin-access">
                        <strong>Administrator privilege</strong>
                        <p>Full administrative access to the admin dashboard, user management directory, and platform telemetry.</p>
                    </div>
                @endif
                <p class="profile-identity-note">Registered on {{ $user->created_at->format('d M Y') }}</p>
            </aside>

            <!-- Right Column: Tabs & Stacked Panels -->
            <div class="profile-content">
                <!-- Pill Tabs Switcher -->
                <nav class="profile-tabs" role="tablist" aria-label="Member management sections">
                    <button type="button" role="tab" :aria-selected="(tab === 'account').toString()" @click="selectTab('account')">Account</button>
                    <button type="button" role="tab" :aria-selected="(tab === 'quotas').toString()" @click="selectTab('quotas')">AI Quotas & Plan</button>
                    <button type="button" role="tab" :aria-selected="(tab === 'tracker').toString()" @click="selectTab('tracker')">Job Tracker ({{ $totalApplications }})</button>
                    <button type="button" role="tab" :aria-selected="(tab === 'activity').toString()" @click="selectTab('activity')">Activity ({{ $recentActivities->count() }})</button>
                </nav>

                <!-- TAB 1: Account & Credentials -->
                <div id="panel-account" role="tabpanel" x-show="tab === 'account'" class="profile-panel-stack">
                    <section class="profile-panel">
                        <header class="profile-section-header">
                            <div>
                                <h2>Account credentials</h2>
                                <p>Modify member profile identity, primary authentication email, and tier permissions.</p>
                            </div>
                        </header>
                        <form wire:submit="updateUser">
                            <div class="profile-field-grid">
                                <div class="profile-field">
                                    <label for="detail-user-name">Full name</label>
                                    <input id="detail-user-name" type="text" wire:model="name" required placeholder="Member full name">
                                    @error('name')<p class="profile-field-error">{{ $message }}</p>@enderror
                                </div>
                                <div class="profile-field">
                                    <label for="detail-user-email">Email address</label>
                                    <input id="detail-user-email" type="email" wire:model="email" required placeholder="member@email.com">
                                    @error('email')<p class="profile-field-error">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div style="margin-top: 6px; margin-bottom: 14px;">
                                <label class="profile-preference-row" for="pref-premium">
                                    <span>
                                        <strong>Premium membership</strong>
                                        <small>Maximized AI feature quotas, full ATS resume audits, and unlimited job tracking privileges.</small>
                                    </span>
                                    <input class="profile-switch" id="pref-premium" type="checkbox" wire:model.live="is_premium">
                                </label>
                                <label class="profile-preference-row" for="pref-admin">
                                    <span>
                                        <strong>Administrator privilege</strong>
                                        <small>Full administrative access to the admin dashboard, user management directory, and platform telemetry.</small>
                                    </span>
                                    <input class="profile-switch" id="pref-admin" type="checkbox" wire:model.live="is_admin">
                                </label>
                            </div>

                            <div class="profile-verification" style="background: #faf8fb; border-color: #ebdff5; color: #594481; margin-bottom: 14px;">
                                <span>Updating membership tier recalculates feature availability and quota limits across candidate tools in real-time.</span>
                            </div>

                            <div class="profile-form-footer">
                                <span>Account updates reflect immediately across all active user sessions.</span>
                                <button type="submit" class="profile-primary" wire:loading.attr="disabled" wire:target="updateUser">
                                    <span wire:loading.remove wire:target="updateUser">Save account details</span>
                                    <span wire:loading wire:target="updateUser">Saving…</span>
                                </button>
                            </div>
                        </form>
                    </section>

                    <section class="profile-panel profile-danger">
                        <h2>Delete member account</h2>
                        <p>This permanently removes this member's account and purges all their tracked job applications, resumes, and telemetry records from the database. This action cannot be reversed.</p>
                        <button type="button" wire:click="confirmDelete" class="profile-danger-button">
                            Permanently delete account
                        </button>
                    </section>
                </div>

                <!-- TAB 2: AI Quotas & Plan -->
                <div id="panel-quotas" role="tabpanel" x-show="tab === 'quotas'" x-cloak class="profile-panel-stack">
                    <section class="profile-panel">
                        <header class="profile-section-header">
                            <div>
                                <h2>AI feature quotas</h2>
                                <p>Adjust available usage credits for AI-powered career tools.</p>
                            </div>
                            <button type="button" wire:click="resetAllAiQuotas" wire:confirm="Reset AI quotas for this member to plan defaults?" class="profile-secondary">
                                Reset to defaults
                            </button>
                        </header>
                        <form wire:submit="saveAiQuotas">
                            <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px;">
                                <div class="profile-field" style="background: #f8fafb; border: 1px solid #edf0f4; border-radius: 12px; padding: 14px; margin-bottom: 0;">
                                    <label for="detail-ai-credits" style="font-size: 11.5px; margin-bottom: 6px;">CV analysis credits</label>
                                    <input id="detail-ai-credits" type="number" wire:model="ai_credits" min="0" step="1" required style="font-size: 18px; font-weight: 600;">
                                    @error('ai_credits')<p class="profile-field-error">{{ $message }}</p>@enderror
                                    <small style="display: block; font-size: 10.5px; color: #626573; margin-top: 6px; line-height: 1.4;">Full resume ATS audits and recommendations</small>
                                </div>

                                <div class="profile-field" style="background: #f8fafb; border: 1px solid #edf0f4; border-radius: 12px; padding: 14px; margin-bottom: 0;">
                                    <label for="detail-cl-credits" style="font-size: 11.5px; margin-bottom: 6px;">Cover letter credits</label>
                                    <input id="detail-cl-credits" type="number" wire:model="cl_credits" min="0" step="1" required style="font-size: 18px; font-weight: 600;">
                                    @error('cl_credits')<p class="profile-field-error">{{ $message }}</p>@enderror
                                    <small style="display: block; font-size: 10.5px; color: #626573; margin-top: 6px; line-height: 1.4;">Custom cover letter generations</small>
                                </div>

                                <div class="profile-field" style="background: #f8fafb; border: 1px solid #edf0f4; border-radius: 12px; padding: 14px; margin-bottom: 0;">
                                    <label for="detail-photo-credits" style="font-size: 11.5px; margin-bottom: 6px;">AI photo credits</label>
                                    <input id="detail-photo-credits" type="number" wire:model="photo_credits" min="0" step="1" required style="font-size: 18px; font-weight: 600;">
                                    @error('photo_credits')<p class="profile-field-error">{{ $message }}</p>@enderror
                                    <small style="display: block; font-size: 10.5px; color: #626573; margin-top: 6px; line-height: 1.4;">Professional headshot photo requests</small>
                                </div>
                            </div>

                            <div class="profile-verification" style="background: #faf8fb; border-color: #ebdff5; color: #594481; margin-top: 14px; margin-bottom: 0;">
                                <span>Trial status: <strong>{{ $user->has_used_ai_analyzer_trial ? 'Free trial used' : 'Free trial available' }}</strong></span>
                                @if($user->ai_analyzer_trial_used_at)
                                    <span> · Used on {{ $user->ai_analyzer_trial_used_at->format('d M Y') }}</span>
                                @endif
                            </div>

                            <div class="profile-form-footer" style="margin-top: 14px;">
                                <span>Adjusted quotas update candidate workspace immediately.</span>
                                <button type="submit" class="profile-primary" wire:loading.attr="disabled" wire:target="saveAiQuotas">
                                    <span wire:loading.remove wire:target="saveAiQuotas">Save AI quotas</span>
                                    <span wire:loading wire:target="saveAiQuotas">Saving…</span>
                                </button>
                            </div>
                        </form>
                    </section>

                    <section class="profile-panel">
                        <header class="profile-section-header">
                            <div>
                                <h2>Membership & billing</h2>
                                <p>Current plan entitlement, payment status, and duration.</p>
                            </div>
                            <button type="button" wire:click="$set('showPremiumConfirmModal', true)" class="profile-secondary">
                                {{ $user->is_premium ? 'Revoke premium access' : 'Grant premium access' }}
                            </button>
                        </header>
                        <dl class="profile-account-facts" style="border-top: none; margin-top: 0; padding-top: 0; display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px 24px;">
                            <div>
                                <dt>Plan duration</dt>
                                <dd>
                                    @if($user->premium_until)
                                        Active until {{ $user->premium_until->format('d M Y') }}
                                    @elseif($user->isPremium())
                                        Lifetime active
                                    @else
                                        Free standard tier
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt>Payment status</dt>
                                <dd style="text-transform: uppercase;">{{ $user->payment_status ?: 'Free' }}</dd>
                            </div>
                            <div>
                                <dt>Purchased at</dt>
                                <dd>{{ $user->premium_purchased_at ? $user->premium_purchased_at->format('d M Y') : '—' }}</dd>
                            </div>
                            <div>
                                <dt>Grandfathered benefits</dt>
                                <dd>{{ $user->grandfathered_benefits ? 'Active' : 'None' }}</dd>
                            </div>
                        </dl>
                    </section>
                </div>

                <!-- TAB 3: Job Tracker -->
                <div id="panel-tracker" role="tabpanel" x-show="tab === 'tracker'" x-cloak class="profile-panel-stack">
                    <section class="profile-panel">
                        <header class="profile-section-header">
                            <div>
                                <h2>Application metrics</h2>
                                <p>Summary of candidate tracker statistics stored in database.</p>
                            </div>
                        </header>
                        <div class="profile-stat-grid">
                            <div class="profile-stat-card">
                                <span>Total applied</span>
                                <strong>{{ number_format($totalApplications) }}</strong>
                            </div>
                            <div class="profile-stat-card">
                                <span>Accepted</span>
                                <strong style="color: #1e7e4e;">{{ number_format($recentApplications->where('application_status', 'Accepted')->count()) }}</strong>
                            </div>
                            <div class="profile-stat-card">
                                <span>In progress</span>
                                <strong style="color: #2579ca;">{{ number_format($recentApplications->where('application_status', 'On Process')->count()) }}</strong>
                            </div>
                            <div class="profile-stat-card">
                                <span>Declined</span>
                                <strong style="color: #a02745;">{{ number_format($declinedApplications) }}</strong>
                            </div>
                        </div>
                    </section>

                    <section class="profile-panel">
                        <header class="profile-section-header">
                            <div>
                                <h2>Tracked applications ({{ $totalApplications }})</h2>
                                <p>Opportunities logged by this candidate in their personal tracker.</p>
                            </div>
                        </header>
                        @if($recentApplications->isNotEmpty())
                            <div style="overflow-x: auto;">
                                <table class="profile-table">
                                    <thead>
                                        <tr>
                                            <th>Opportunity</th>
                                            <th>Status</th>
                                            <th style="text-align: right;">Logged date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentApplications as $job)
                                            @php
                                                $tone = match($job->application_status) {
                                                    'Accepted' => 'accepted',
                                                    'Rejected' => 'declined',
                                                    default => 'progress',
                                                };
                                            @endphp
                                            <tr wire:key="job-{{ $job->id }}">
                                                <td>
                                                    <strong style="display: block; font-size: 12px; font-weight: 600; color: #30313b;">{{ $job->position }}</strong>
                                                    <span style="display: block; font-size: 11px; color: #626573; margin-top: 1px;">{{ $job->company_name }}</span>
                                                </td>
                                                <td>
                                                    <span class="profile-chip profile-chip--{{ $tone }}">
                                                        {{ $job->application_status }}
                                                    </span>
                                                </td>
                                                <td style="text-align: right; font-size: 11px; color: #626573; white-space: nowrap;">
                                                    {{ $job->application_date ? $job->application_date->format('d M Y') : '—' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="profile-empty">No job applications tracked by this member yet.</p>
                        @endif
                    </section>
                </div>

                <!-- TAB 4: Activity Log -->
                <div id="panel-activity" role="tabpanel" x-show="tab === 'activity'" x-cloak class="profile-panel-stack">
                    <section class="profile-panel">
                        <header class="profile-section-header">
                            <div>
                                <h2>Recent account activity</h2>
                                <p>Chronological audit record of user interactions and security events.</p>
                            </div>
                            <a href="{{ route('admin.user-activities') }}?search={{ urlencode($user->email) }}" class="profile-secondary" wire:navigate>
                                View full audit log
                            </a>
                        </header>
                        @forelse($recentActivities as $act)
                            <div class="profile-activity-item" wire:key="act-{{ $act->id }}">
                                <span class="profile-activity-icon"><i class="ph ph-{{ $act->status === 'success' ? 'check' : 'info' }}" aria-hidden="true"></i></span>
                                <div>
                                    <strong>{{ ucwords(str_replace('_', ' ', $act->activity_type)) }}</strong>
                                    <p>{{ $act->description }}</p>
                                    <span>{{ $act->ip_address ?: 'System event' }}</span>
                                </div>
                                <time datetime="{{ $act->created_at->toIso8601String() }}">{{ $act->created_at->diffForHumans() }}</time>
                            </div>
                        @empty
                            <p class="profile-empty">No recent activity logged for this member.</p>
                        @endforelse
                    </section>

                    <section class="profile-panel">
                        <header class="profile-section-header">
                            <div>
                                <h2>Direct communication</h2>
                                <p>Dispatch customized notifications or system emails to this member.</p>
                            </div>
                            <button type="button" wire:click="openSendEmailModal" class="profile-primary">
                                Send custom email
                            </button>
                        </header>
                        <div style="font-size: 11.5px; color: #626573; line-height: 1.6;">
                            <p style="margin: 0 0 8px;">Available pre-configured message templates:</p>
                            <ul style="margin: 0; padding-left: 18px; display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 4px;">
                                <li>Account verification & reminders</li>
                                <li>Feature announcements (AI CV, Studio)</li>
                                <li>Career momentum & monthly motivation</li>
                                <li>Premium membership notifications</li>
                            </ul>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 1: Send Email -->
    @if($showSendEmailModal)
        <template x-teleport="body">
            <div x-data role="dialog" aria-modal="true" tabindex="-1" class="profile-modal-overlay" @keydown.escape.stop="$wire.closeSendEmailModal()">
                <div class="profile-modal-box" @click.outside="$wire.closeSendEmailModal()">
                    <header class="profile-modal-header">
                        <div>
                            <h2>Send email to member</h2>
                            <p>Choose an automated email template to dispatch directly to user's inbox.</p>
                        </div>
                        <button type="button" wire:click="closeSendEmailModal" class="profile-modal-close" aria-label="Close dialog">
                            <i class="ph ph-x" aria-hidden="true"></i>
                        </button>
                    </header>
                    <div class="profile-modal-recipient">
                        <strong>{{ $user->name }}</strong>
                        <span>{{ $user->email }}</span>
                    </div>
                    <form wire:submit="sendEmail">
                        <div class="profile-field">
                            <label for="member-email-type">Message template</label>
                            <select id="member-email-type" wire:model="emailType" class="profile-select">
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
                            @error('emailType')<p class="profile-field-error">{{ $message }}</p>@enderror
                        </div>
                        <p style="font-size: 11px; color: #626573; margin: 4px 0 16px;">Email will be delivered immediately via the TraKerja mail system.</p>
                        <div class="profile-form-footer">
                            <button type="button" wire:click="closeSendEmailModal" class="profile-secondary">Cancel</button>
                            <button type="submit" wire:loading.attr="disabled" wire:target="sendEmail" class="profile-primary">
                                <span wire:loading.remove wire:target="sendEmail">Send email now</span>
                                <span wire:loading wire:target="sendEmail">Sending…</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    @endif

    <!-- MODAL 2: Confirm Premium -->
    @if($showPremiumConfirmModal)
        <template x-teleport="body">
            <div x-data role="dialog" aria-modal="true" tabindex="-1" class="profile-modal-overlay" @keydown.escape.stop="$wire.set('showPremiumConfirmModal', false)">
                <div class="profile-modal-box" @click.outside="$wire.set('showPremiumConfirmModal', false)">
                    <header class="profile-modal-header">
                        <div>
                            <h2>{{ $user->is_premium ? 'Revoke premium access?' : 'Grant premium access?' }}</h2>
                            <p>Confirm the membership tier change for this user account.</p>
                        </div>
                        <button type="button" wire:click="$set('showPremiumConfirmModal', false)" class="profile-modal-close" aria-label="Close dialog">
                            <i class="ph ph-x" aria-hidden="true"></i>
                        </button>
                    </header>
                    <div class="profile-modal-recipient profile-modal-recipient--warning">
                        <strong>{{ $user->name }}</strong>
                        <span>
                            {{ $user->is_premium 
                                ? 'Premium access will be deactivated. Exclusive AI features and priority quotas will return to standard free tier.' 
                                : 'Premium access will be activated for ' . config('pakasir.premium_duration_days', 365) . ' days with maximized AI feature quotas.' }}
                        </span>
                    </div>
                    <div class="profile-form-footer">
                        <button type="button" wire:click="$set('showPremiumConfirmModal', false)" class="profile-secondary">Cancel</button>
                        <button type="button" wire:click="togglePremium" wire:loading.attr="disabled" wire:target="togglePremium" class="profile-primary">
                            <span wire:loading.remove wire:target="togglePremium">{{ $user->is_premium ? 'Yes, revoke premium' : 'Yes, grant premium' }}</span>
                            <span wire:loading wire:target="togglePremium">Processing…</span>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    @endif

    <!-- MODAL 3: Confirm Delete -->
    @if($showDeleteConfirmModal)
        <template x-teleport="body">
            <div x-data role="dialog" aria-modal="true" tabindex="-1" class="profile-modal-overlay" @keydown.escape.stop="$wire.closeDeleteConfirm()">
                <div class="profile-modal-box" @click.outside="$wire.closeDeleteConfirm()">
                    <header class="profile-modal-header">
                        <div>
                            <h2 style="color: #a02745;">Permanently delete account?</h2>
                            <p>This action is irreversible and will delete all user data permanently.</p>
                        </div>
                        <button type="button" wire:click="closeDeleteConfirm" class="profile-modal-close" aria-label="Close dialog">
                            <i class="ph ph-x" aria-hidden="true"></i>
                        </button>
                    </header>
                    <div class="profile-modal-recipient profile-modal-recipient--danger">
                        <strong>{{ $user->name }} ({{ $user->email }})</strong>
                        <span>All account data, tracked job applications, activity records, and resumes will be permanently purged from the database.</span>
                    </div>
                    <div class="profile-form-footer">
                        <button type="button" wire:click="closeDeleteConfirm" class="profile-secondary">Cancel, keep account</button>
                        <button type="button" wire:click="performDelete" wire:loading.attr="disabled" wire:target="performDelete" class="profile-danger-button">
                            <span wire:loading.remove wire:target="performDelete">Permanently delete</span>
                            <span wire:loading wire:target="performDelete">Deleting…</span>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    @endif
</div>
