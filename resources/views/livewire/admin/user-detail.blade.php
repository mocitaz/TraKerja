<div class="admin-users-workspace au-detail-page">
    <!-- Top Bar Navigation -->
    <div class="au-detail-topbar">
        <a href="{{ route('admin.users') }}" class="au-button" wire:navigate>
            <i class="ph ph-arrow-left" aria-hidden="true"></i> Back to members
        </a>
        <div class="au-detail-top-actions">
            <button type="button" wire:click="openSendEmailModal" class="au-button">
                <i class="ph ph-paper-plane-tilt" aria-hidden="true"></i> Send Email
            </button>
            <button type="button" wire:click="$set('showPremiumConfirmModal', true)" class="au-button">
                <i class="ph ph-crown" aria-hidden="true"></i>
                {{ $user->is_premium ? 'Revoke Premium' : 'Grant Premium' }}
            </button>
            <button type="button" wire:click="confirmDelete" class="au-account-delete">
                <i class="ph ph-trash" aria-hidden="true"></i> Delete Member
            </button>
        </div>
    </div>

    <!-- Member Profile Hero Card -->
    <section class="au-hero-card" aria-label="Member profile summary">
        <div class="au-hero-main">
            <div class="au-hero-avatar-wrap">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="au-hero-avatar" loading="lazy">
            </div>
            <div class="au-hero-info">
                <div class="au-hero-title-row">
                    <h1>{{ $user->name }}</h1>
                    <span class="au-hero-id">#ID-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                    @if($user->isAdmin())
                        <span class="au-plan-badge au-plan-badge--premium">Admin</span>
                    @endif
                    <span class="au-plan-badge {{ $user->isPremium() ? 'au-plan-badge--premium' : 'au-plan-badge--free' }}">
                        {{ $user->isPremium() ? 'Premium Member' : 'Free Tier' }}
                    </span>
                </div>
                <p class="au-hero-email">{{ $user->email }}</p>
                <div class="au-hero-meta">
                    <span>
                        <i class="ph ph-calendar"></i> Joined {{ $user->created_at->format('d M Y') }}
                    </span>
                    @if($user->hasVerifiedEmail())
                        <span class="au-tag au-tag--verified">
                            <i class="ph ph-check-circle"></i> Email Verified
                        </span>
                    @else
                        <span class="au-tag" style="background: #fff1f2; color: #be123c; border: 1px solid #ffe4e6;">
                            <i class="ph ph-warning-circle"></i> Unverified Email
                        </span>
                    @endif
                    @if($user->google_id)
                        <span class="au-tag au-tag--google">
                            <i class="ph ph-google-logo"></i> Google
                        </span>
                    @endif
                    @if($user->linkedin_id)
                        <span class="au-tag au-tag--linkedin">
                            <i class="ph ph-linkedin-logo"></i> LinkedIn
                        </span>
                    @endif
                    <span class="au-hero-activity">
                        @if($user->last_activity_at && $user->last_activity_at->diffInMinutes(now()) < 15)
                            <span class="au-dot au-dot--active"></span> Active now
                        @elseif($user->last_activity_at)
                            <span class="au-dot au-dot--recent"></span> Active {{ $user->last_activity_at->diffForHumans() }}
                        @else
                            <span class="au-dot au-dot--idle"></span> No activity recorded
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Quick Metrics Row -->
        <div class="au-hero-metrics">
            <div class="au-hero-stat">
                <small>Jobs Applied</small>
                <strong>{{ number_format($totalApplications) }}</strong>
            </div>
            <div class="au-hero-stat">
                <small>Declined</small>
                <strong style="color: {{ $declinedApplications > 0 ? '#be123c' : '#5b5568' }};">{{ number_format($declinedApplications) }}</strong>
            </div>
            <div class="au-hero-stat">
                <small>CV Analyze Credits</small>
                <strong>{{ number_format($user->ai_credits ?? 0) }}</strong>
            </div>
            <div class="au-hero-stat">
                <small>Cover Letter Credits</small>
                <strong>{{ number_format($user->cl_credits ?? 0) }}</strong>
            </div>
            <div class="au-hero-stat">
                <small>Photo Studio Credits</small>
                <strong>{{ number_format($user->photo_credits ?? 0) }}</strong>
            </div>
        </div>
    </section>

    <!-- Main Content 2-Column Grid -->
    <div class="au-detail-grid">
        <!-- Left Column: Forms -->
        <div class="au-detail-column">
            <!-- Card 1: Account Information & Roles -->
            <section class="au-detail-card" aria-labelledby="card-account-heading">
                <header class="au-detail-card-header">
                    <div>
                        <h2 id="card-account-heading">Account & Access Details</h2>
                        <p>Modify member profile credentials and administrative permissions.</p>
                    </div>
                </header>
                <form wire:submit="updateUser" class="au-form">
                    <label for="detail-user-name">
                        Full Name
                        <input id="detail-user-name" type="text" wire:model="name" required placeholder="Member full name…">
                        @error('name')<span class="au-field-error">{{ $message }}</span>@enderror
                    </label>

                    <label for="detail-user-email">
                        Email Address
                        <input id="detail-user-email" type="email" wire:model="email" required placeholder="member@email.com">
                        @error('email')<span class="au-field-error">{{ $message }}</span>@enderror
                    </label>

                    <div class="au-checkbox-row" style="margin-top: 4px;">
                        <label class="au-checkbox-label">
                            <input type="checkbox" wire:model="is_premium">
                            Premium Membership Access
                        </label>
                        <label class="au-checkbox-label">
                            <input type="checkbox" wire:model="is_admin">
                            Administrator Role
                        </label>
                    </div>
                    <p class="au-form-hint">Changing membership tier will automatically update feature access and reset quotas.</p>

                    <div class="au-form-actions" style="margin-top: 10px;">
                        <button type="submit" class="au-button au-button--primary" wire:loading.attr="disabled" wire:target="updateUser">
                            <span wire:loading.remove wire:target="updateUser">Save Account Information</span>
                            <span wire:loading wire:target="updateUser">Saving…</span>
                        </button>
                    </div>
                </form>
            </section>

            <!-- Card 2: AI Quota Credits -->
            <section class="au-detail-card" aria-labelledby="card-ai-heading">
                <header class="au-detail-card-header">
                    <div>
                        <h2 id="card-ai-heading">AI Feature Quotas</h2>
                        <p>Adjust available usage credits for AI-powered career tools.</p>
                    </div>
                    <button type="button" class="au-clear" wire:click="resetAllAiQuotas" wire:confirm="Reset AI quotas for this member to plan defaults?">
                        <i class="ph ph-arrow-counter-clockwise"></i> Reset to Default
                    </button>
                </header>
                <form wire:submit="saveAiQuotas" class="au-form">
                    <div class="au-quota-grid">
                        <div class="au-quota-tile">
                            <label for="detail-ai-credits">
                                Analyze CV
                                <input id="detail-ai-credits" type="number" wire:model="ai_credits" min="0" step="1" required>
                                @error('ai_credits')<span class="au-field-error">{{ $message }}</span>@enderror
                            </label>
                        </div>
                        <div class="au-quota-tile">
                            <label for="detail-cl-credits">
                                Cover Letter
                                <input id="detail-cl-credits" type="number" wire:model="cl_credits" min="0" step="1" required>
                                @error('cl_credits')<span class="au-field-error">{{ $message }}</span>@enderror
                            </label>
                        </div>
                        <div class="au-quota-tile">
                            <label for="detail-photo-credits">
                                AI Photo Studio
                                <input id="detail-photo-credits" type="number" wire:model="photo_credits" min="0" step="1" required>
                                @error('photo_credits')<span class="au-field-error">{{ $message }}</span>@enderror
                            </label>
                        </div>
                    </div>

                    <div style="padding: 10px 14px; background: #faf7fc; border: 1px solid #ede5f3; border-radius: 10px; font-size: 11.5px; color: #6b5c77;">
                        Trial Status: <strong>{{ $user->has_used_ai_analyzer_trial ? 'Free Trial Used' : 'Free Trial Available' }}</strong>
                        @if($user->ai_analyzer_trial_used_at)
                            <span>· used on {{ $user->ai_analyzer_trial_used_at->format('d M Y') }}</span>
                        @endif
                    </div>

                    <div class="au-form-actions" style="margin-top: 6px;">
                        <button type="submit" class="au-button au-button--primary" wire:loading.attr="disabled" wire:target="saveAiQuotas">
                            <span wire:loading.remove wire:target="saveAiQuotas">Save AI Quotas</span>
                            <span wire:loading wire:target="saveAiQuotas">Saving…</span>
                        </button>
                    </div>
                </form>
            </section>
        </div>

        <!-- Right Column: Data, Jobs & Activities -->
        <div class="au-detail-column">
            <!-- Card 3: Membership Details -->
            <section class="au-detail-card" aria-labelledby="card-membership-heading">
                <header class="au-detail-card-header">
                    <div>
                        <h2 id="card-membership-heading">Membership & Billing</h2>
                        <p>Current plan duration and billing state.</p>
                    </div>
                    <span class="au-plan-badge {{ $user->isPremium() ? 'au-plan-badge--premium' : 'au-plan-badge--free' }}">
                        {{ $user->isPremium() ? 'Premium' : 'Free' }}
                    </span>
                </header>
                <dl class="au-account-details">
                    <div class="au-detail-tile">
                        <dt>Plan Duration</dt>
                        <dd>
                            @if($user->premium_until)
                                Active until {{ $user->premium_until->format('d M Y') }}
                            @elseif($user->isPremium())
                                Lifetime Active
                            @else
                                Free Standard Tier
                            @endif
                        </dd>
                    </div>
                    <div class="au-detail-tile">
                        <dt>Payment Status</dt>
                        <dd style="text-transform: uppercase;">{{ $user->payment_status ?: 'Free' }}</dd>
                    </div>
                    <div class="au-detail-tile">
                        <dt>Purchased At</dt>
                        <dd>{{ $user->premium_purchased_at ? $user->premium_purchased_at->format('d M Y') : '—' }}</dd>
                    </div>
                    <div class="au-detail-tile">
                        <dt>Grandfathered Benefits</dt>
                        <dd>{{ $user->grandfathered_benefits ? 'Active' : 'None' }}</dd>
                    </div>
                </dl>
            </section>

            <!-- Card 4: Recent Job Applications (from /tracker) -->
            <section class="au-detail-card" aria-labelledby="card-jobs-heading">
                <header class="au-detail-card-header">
                    <div>
                        <h2 id="card-jobs-heading">
                            Recent Job Applications <span>{{ $totalApplications }}</span>
                        </h2>
                        <p>Latest opportunities tracked by this member.</p>
                    </div>
                </header>
                <div class="au-table-scroll" style="max-height: 320px;">
                    <table class="au-table" style="min-width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col" style="padding-left: 14px;">Opportunity</th>
                                <th scope="col">Status</th>
                                <th scope="col" style="text-align: right; padding-right: 14px;">Applied</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentApplications as $job)
                                @php
                                    $tone = match($job->application_status) {
                                        'Accepted' => 'accepted',
                                        'Rejected' => 'rejected',
                                        default => 'process',
                                    };
                                @endphp
                                <tr wire:key="job-{{ $job->id }}">
                                    <td style="padding-left: 14px;">
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <div class="tracker-company-mark" style="width: 26px; height: 26px; border-radius: 8px; font-size: 11px;">
                                                {{ mb_substr($job->company_name, 0, 1) }}
                                            </div>
                                            <div style="min-width: 0;">
                                                <strong style="font-size: 12px; color: #34313e; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                    {{ $job->position }}
                                                </strong>
                                                <span style="font-size: 10.5px; color: #67616f; display: block;">
                                                    {{ $job->company_name }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="tracker-pill tracker-pill--{{ $tone }}">
                                            {{ $job->application_status }}
                                        </span>
                                    </td>
                                    <td style="text-align: right; padding-right: 14px; font-size: 11px; color: #767082; white-space: nowrap;">
                                        {{ $job->application_date ? $job->application_date->format('d M Y') : '—' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align: center; padding: 28px 14px; color: #8c7e96; font-size: 11.5px;">
                                        No job applications tracked by this member yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Card 5: Recent Activity History -->
            <section class="au-detail-card" aria-labelledby="card-activity-heading">
                <header class="au-detail-card-header">
                    <div>
                        <h2 id="card-activity-heading">Recent Activity Log</h2>
                        <p>Timeline of recent authentication and feature interactions.</p>
                    </div>
                    <a href="{{ route('admin.user-activities') }}?search={{ urlencode($user->email) }}" class="au-clear" wire:navigate style="font-size: 10.5px;">
                        View all <i class="ph ph-arrow-up-right"></i>
                    </a>
                </header>
                <div class="au-table-scroll" style="max-height: 280px;">
                    <table class="au-table" style="min-width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col" style="padding-left: 14px;">Event</th>
                                <th scope="col">Status</th>
                                <th scope="col" style="text-align: right; padding-right: 14px;">When</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentActivities as $act)
                                <tr wire:key="act-{{ $act->id }}">
                                    <td style="padding-left: 14px;">
                                        <strong style="font-size: 11.5px; color: #34313e; display: block;">
                                            {{ ucwords(str_replace('_', ' ', $act->activity_type)) }}
                                        </strong>
                                        <span style="font-size: 10.5px; color: #67616f; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 220px;">
                                            {{ $act->description }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="au-tag" style="font-size: 9.5px; text-transform: capitalize; background: #f5f0f8; color: #695279; border: 1px solid #ebdfee;">
                                            {{ $act->status }}
                                        </span>
                                    </td>
                                    <td style="text-align: right; padding-right: 14px; font-size: 10.5px; color: #767082; white-space: nowrap;">
                                        {{ $act->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align: center; padding: 24px 14px; color: #8c7e96; font-size: 11.5px;">
                                        No recent activity logged for this member.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    <!-- =====================================================================
         MODAL 1: Send Email ($showSendEmailModal)
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
                            <strong>{{ $user->name }}</strong>
                            <span>{{ $user->email }}</span>
                        </div>
                        <form wire:submit="sendEmail" class="au-form">
                            <label for="member-email-type">
                                Message Template
                                <select id="member-email-type" wire:model="emailType">
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
         MODAL 2: Confirm Premium ($showPremiumConfirmModal)
         ===================================================================== -->
    @if($showPremiumConfirmModal)
        <template x-teleport="body">
            <div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showPremiumConfirmModal" data-dialog-close="$wire.set('showPremiumConfirmModal', false)" aria-labelledby="showPremiumConfirmModal-title" class="au-dialog-overlay">
                <section class="au-dialog au-dialog--small">
                    <header class="au-dialog-heading">
                        <div>
                            <h2 id="showPremiumConfirmModal-title">
                                {{ $user->is_premium ? 'Revoke Premium Access?' : 'Grant Premium Access?' }}
                            </h2>
                            <p>Confirm the membership tier change for this user account.</p>
                        </div>
                        <button type="button" wire:click="$set('showPremiumConfirmModal', false)" class="au-dialog-close" aria-label="Close dialog">
                            <i class="ph ph-x" aria-hidden="true"></i>
                        </button>
                    </header>
                    <div class="au-dialog-body">
                        <div class="au-recipient au-recipient--warning">
                            <strong>{{ $user->name }}</strong>
                            <span>
                                {{ $user->is_premium 
                                    ? 'Premium access will be deactivated. Exclusive AI features and priority quotas will return to standard free tier.' 
                                    : 'Premium access will be activated for ' . config('pakasir.premium_duration_days', 365) . ' days with maximized AI feature quotas.' }}
                            </span>
                        </div>
                        <div class="au-dialog-actions">
                            <button type="button" wire:click="$set('showPremiumConfirmModal', false)" class="au-button">Cancel</button>
                            <button type="button" wire:click="togglePremium" wire:loading.attr="disabled" wire:target="togglePremium" class="au-button au-button--primary">
                                <span wire:loading.remove wire:target="togglePremium">{{ $user->is_premium ? 'Yes, Revoke Premium' : 'Yes, Grant Premium' }}</span>
                                <span wire:loading wire:target="togglePremium">Processing…</span>
                            </button>
                        </div>
                    </div>
                </section>
            </div>
        </template>
    @endif

    <!-- =====================================================================
         MODAL 3: Confirm Delete ($showDeleteConfirmModal)
         ===================================================================== -->
    @if($showDeleteConfirmModal)
        <template x-teleport="body">
            <div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showDeleteConfirmModal" data-dialog-close="$wire.closeDeleteConfirm()" aria-labelledby="showDeleteConfirmModal-title" class="au-dialog-overlay">
                <section class="au-dialog au-dialog--small">
                    <header class="au-dialog-heading">
                        <div>
                            <h2 id="showDeleteConfirmModal-title" style="color: #ef4444;">Permanently Delete Account?</h2>
                            <p>This action is irreversible and will delete all user data permanently.</p>
                        </div>
                        <button type="button" wire:click="closeDeleteConfirm" class="au-dialog-close" aria-label="Close dialog">
                            <i class="ph ph-x" aria-hidden="true"></i>
                        </button>
                    </header>
                    <div class="au-dialog-body">
                        <div class="au-recipient au-recipient--danger">
                            <strong>{{ $user->name }} ({{ $user->email }})</strong>
                            <span>All account data, tracked job applications, activity records, and resumes will be permanently purged from the database.</span>
                        </div>
                        <div class="au-dialog-actions">
                            <button type="button" wire:click="closeDeleteConfirm" class="au-button">Cancel, Keep Account</button>
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
