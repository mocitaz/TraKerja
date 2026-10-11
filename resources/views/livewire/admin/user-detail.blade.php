<div class="workspace-page au-detail-page" 
     x-data="{ 
        activeTab: new URLSearchParams(window.location.search).get('tab') || 'account',
        selectTab(tab) {
            this.activeTab = tab;
            const url = new URL(window.location);
            url.searchParams.set('tab', tab);
            window.history.replaceState({}, '', url);
        }
     }">

    {{-- =====================================================================
         HEADER: Matched to /automation?tab=csv & User Dashboard
         ===================================================================== --}}
    <header class="workspace-page__header au-detail-header">
        <div>
            <p class="workspace-eyebrow">
                <a href="{{ route('admin.users') }}" wire:navigate class="hover:underline">Member Directory</a> · Member #{{ $user->id }}
            </p>
            <div class="au-detail-heading-row">
                <h1>{{ $user->name }}</h1>
                <span class="au-hero-id">#ID-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                @if($user->isAdmin())
                    <span class="au-plan-badge au-plan-badge--premium">Admin</span>
                @endif
                <span class="au-plan-badge {{ $user->isPremium() ? 'au-plan-badge--premium' : 'au-plan-badge--free' }}">
                    {{ $user->isPremium() ? 'Premium' : 'Free' }}
                </span>
            </div>
            <p>Manage member credentials, feature entitlements, job applications, and activity telemetry.</p>
        </div>

        <div class="au-detail-top-actions">
            <a href="{{ route('admin.users') }}" class="au-button" wire:navigate>
                <i class="ph ph-arrow-left" aria-hidden="true"></i> Back to members
            </a>
            <button type="button" wire:click="openSendEmailModal" class="au-button">
                <i class="ph ph-paper-plane-tilt" aria-hidden="true"></i> Send Email
            </button>
            <button type="button" wire:click="$set('showPremiumConfirmModal', true)" class="au-button">
                <i class="ph ph-crown" aria-hidden="true"></i>
                {{ $user->is_premium ? 'Revoke Premium' : 'Grant Premium' }}
            </button>
            <button type="button" wire:click="confirmDelete" class="au-account-delete">
                <i class="ph ph-trash" aria-hidden="true"></i> Delete
            </button>
        </div>
    </header>

    {{-- =====================================================================
         TAB SWITCHER: Exact .workspace-switcher from /automation?tab=csv
         ===================================================================== --}}
    <div class="workspace-switcher" role="tablist" aria-label="Member management sections">
        <button type="button" 
                @click="selectTab('account')" 
                :class="{ 'is-active': activeTab === 'account' }" 
                role="tab" 
                :aria-selected="(activeTab === 'account').toString()">
            <i class="ph-bold ph-user-circle"></i>
            <span>Account & Access</span>
        </button>
        <button type="button" 
                @click="selectTab('quotas')" 
                :class="{ 'is-active': activeTab === 'quotas' }" 
                role="tab" 
                :aria-selected="(activeTab === 'quotas').toString()">
            <i class="ph-bold ph-cpu"></i>
            <span>AI Quotas & Plan</span>
        </button>
        <button type="button" 
                @click="selectTab('tracker')" 
                :class="{ 'is-active': activeTab === 'tracker' }" 
                role="tab" 
                :aria-selected="(activeTab === 'tracker').toString()">
            <i class="ph-bold ph-briefcase"></i>
            <span>Job Tracker</span>
            <span class="au-tab-count">{{ $totalApplications }}</span>
        </button>
        <button type="button" 
                @click="selectTab('activity')" 
                :class="{ 'is-active': activeTab === 'activity' }" 
                role="tab" 
                :aria-selected="(activeTab === 'activity').toString()">
            <i class="ph-bold ph-clock-counter-clockwise"></i>
            <span>Activity Log</span>
            <span class="au-tab-count">{{ $recentActivities->count() }}</span>
        </button>
    </div>

    {{-- =====================================================================
         TAB 1: ACCOUNT & ACCESS (activeTab === 'account')
         ===================================================================== --}}
    <section id="tab-account" x-show="activeTab === 'account'" class="workspace-stack">
        <div class="workspace-page__grid automation-balanced-grid">
            {{-- Left Column: Account Form --}}
            <div class="workspace-card">
                <div class="workspace-card__heading">
                    <h2>Account & Access Credentials</h2>
                    <p>Modify member profile identity, primary email, and system roles.</p>
                </div>

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

                    <div class="au-checkbox-row" style="margin-top: 6px;">
                        <label class="au-checkbox-label">
                            <input type="checkbox" wire:model="is_premium">
                            <span>Premium Membership Access</span>
                        </label>
                        <label class="au-checkbox-label">
                            <input type="checkbox" wire:model="is_admin">
                            <span>Administrator Role</span>
                        </label>
                    </div>

                    <p class="au-form-hint">
                        <i class="ph ph-info"></i> Modifying membership tier will automatically update feature access and reset usage limits.
                    </p>

                    <div class="au-form-actions" style="margin-top: 14px;">
                        <button type="submit" class="au-button au-button--primary" wire:loading.attr="disabled" wire:target="updateUser">
                            <span wire:loading.remove wire:target="updateUser">Save Account Information</span>
                            <span wire:loading wire:target="updateUser">Saving…</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Right Column: Member Identity & Quick Summary --}}
            <aside class="workspace-card workspace-card--glass automation-side-panel">
                <section class="automation-side-section">
                    <h2>Account Identity</h2>
                    
                    <div class="au-identity-profile">
                        <div class="au-identity-avatar-wrap">
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="au-identity-avatar" loading="lazy">
                        </div>
                        <div class="au-identity-info">
                            <strong>{{ $user->name }}</strong>
                            <p>{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="au-identity-meta-list">
                        <div class="au-meta-row">
                            <span>Verification</span>
                            @if($user->hasVerifiedEmail())
                                <span class="au-tag au-tag--verified">
                                    <i class="ph-bold ph-check-circle"></i> Email Verified
                                </span>
                            @else
                                <span class="au-tag" style="background: #fff1f2; color: #be123c; border: 1px solid #ffe4e6;">
                                    <i class="ph-bold ph-warning-circle"></i> Unverified
                                </span>
                            @endif
                        </div>

                        <div class="au-meta-row">
                            <span>Joined Date</span>
                            <strong>{{ $user->created_at->format('d M Y') }}</strong>
                        </div>

                        <div class="au-meta-row">
                            <span>OAuth Login</span>
                            <div class="flex items-center gap-1.5">
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
                                @if(!$user->google_id && !$user->linkedin_id)
                                    <span class="text-zinc-500 text-xs">Standard Password</span>
                                @endif
                            </div>
                        </div>

                        <div class="au-meta-row">
                            <span>Activity Status</span>
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
                </section>

                <section class="automation-side-section" style="border-top: 1px solid #edf0f4; padding-top: 16px;">
                    <h2>Danger Zone</h2>
                    <p style="font-size: 11px; color: #887494; margin-bottom: 12px;">Irreversible administrative actions for this user.</p>
                    <button type="button" wire:click="confirmDelete" class="au-button au-button--danger" style="width: 100%;">
                        <i class="ph ph-trash"></i> Permanently Delete Account
                    </button>
                </section>
            </aside>
        </div>
    </section>

    {{-- =====================================================================
         TAB 2: AI QUOTAS & PLAN (activeTab === 'quotas')
         ===================================================================== --}}
    <section id="tab-quotas" x-show="activeTab === 'quotas'" x-cloak class="workspace-stack">
        <div class="workspace-page__grid automation-balanced-grid">
            {{-- Left Column: AI Quotas Form --}}
            <div class="workspace-card">
                <div class="workspace-card__heading" style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <h2>AI Feature Credits & Generation Quotas</h2>
                        <p>Adjust available usage credits for AI-powered career tools.</p>
                    </div>
                    <button type="button" class="au-clear" wire:click="resetAllAiQuotas" wire:confirm="Reset AI quotas for this member to plan defaults?">
                        <i class="ph ph-arrow-counter-clockwise"></i> Reset to Default
                    </button>
                </div>

                <form wire:submit="saveAiQuotas" class="au-form">
                    <div class="au-quota-grid">
                        <div class="au-quota-tile">
                            <div class="au-quota-tile-header">
                                <i class="ph-bold ph-sparkle text-[#8e4476]"></i>
                                <strong>CV Analysis</strong>
                            </div>
                            <label for="detail-ai-credits">
                                Available Credits
                                <input id="detail-ai-credits" type="number" wire:model="ai_credits" min="0" step="1" required>
                                @error('ai_credits')<span class="au-field-error">{{ $message }}</span>@enderror
                            </label>
                            <small>Full resume ATS audits and recommendations</small>
                        </div>

                        <div class="au-quota-tile">
                            <div class="au-quota-tile-header">
                                <i class="ph-bold ph-envelope-simple-open text-[#7751a0]"></i>
                                <strong>Cover Letter</strong>
                            </div>
                            <label for="detail-cl-credits">
                                Available Credits
                                <input id="detail-cl-credits" type="number" wire:model="cl_credits" min="0" step="1" required>
                                @error('cl_credits')<span class="au-field-error">{{ $message }}</span>@enderror
                            </label>
                            <small>Custom cover letter generations</small>
                        </div>

                        <div class="au-quota-tile">
                            <div class="au-quota-tile-header">
                                <i class="ph-bold ph-camera text-[#34749b]"></i>
                                <strong>AI Photo Studio</strong>
                            </div>
                            <label for="detail-photo-credits">
                                Available Credits
                                <input id="detail-photo-credits" type="number" wire:model="photo_credits" min="0" step="1" required>
                                @error('photo_credits')<span class="au-field-error">{{ $message }}</span>@enderror
                            </label>
                            <small>Professional headshot photo requests</small>
                        </div>
                    </div>

                    <div style="padding: 12px 16px; background: #faf7fc; border: 1px solid #ede5f3; border-radius: 12px; font-size: 11.5px; color: #6b5c77; margin-top: 14px;">
                        <div class="flex items-center gap-2">
                            <i class="ph-bold ph-clock text-[#7751a0]"></i>
                            <span>Trial Status: <strong>{{ $user->has_used_ai_analyzer_trial ? 'Free Trial Used' : 'Free Trial Available' }}</strong></span>
                            @if($user->ai_analyzer_trial_used_at)
                                <span class="text-zinc-500">· used on {{ $user->ai_analyzer_trial_used_at->format('d M Y') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="au-form-actions" style="margin-top: 14px;">
                        <button type="submit" class="au-button au-button--primary" wire:loading.attr="disabled" wire:target="saveAiQuotas">
                            <span wire:loading.remove wire:target="saveAiQuotas">Save AI Quotas</span>
                            <span wire:loading wire:target="saveAiQuotas">Saving…</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Right Column: Membership & Billing Status --}}
            <aside class="workspace-card workspace-card--glass automation-side-panel">
                <section class="automation-side-section">
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <h2>Membership & Billing</h2>
                        <span class="au-plan-badge {{ $user->isPremium() ? 'au-plan-badge--premium' : 'au-plan-badge--free' }}">
                            {{ $user->isPremium() ? 'Premium' : 'Free' }}
                        </span>
                    </div>

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

                    <div style="margin-top: 16px;">
                        <button type="button" wire:click="$set('showPremiumConfirmModal', true)" class="au-button" style="width: 100%;">
                            <i class="ph ph-crown"></i>
                            {{ $user->is_premium ? 'Revoke Premium Access' : 'Grant Premium Access' }}
                        </button>
                    </div>
                </section>
            </aside>
        </div>
    </section>

    {{-- =====================================================================
         TAB 3: JOB APPLICATIONS (activeTab === 'tracker')
         ===================================================================== --}}
    <section id="tab-tracker" x-show="activeTab === 'tracker'" x-cloak class="workspace-stack">
        <div class="workspace-page__grid automation-balanced-grid">
            {{-- Left Column: Applications Table (matched to /tracker) --}}
            <div class="workspace-card">
                <div class="workspace-card__heading">
                    <h2>Tracked Job Applications ({{ $totalApplications }})</h2>
                    <p>Opportunities logged by this candidate in their personal tracker.</p>
                </div>

                <div class="au-table-container">
                    <table class="au-tracker-table">
                        <thead>
                            <tr>
                                <th>Opportunity</th>
                                <th>Status</th>
                                <th class="text-right">Logged</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentApplications as $job)
                                @php
                                    $tone = match($job->application_status) {
                                        'Accepted' => 'accepted',
                                        'Rejected' => 'declined',
                                        default => 'progress',
                                    };
                                @endphp
                                <tr wire:key="job-{{ $job->id }}">
                                    <td>
                                        <div class="flex items-center gap-2.5">
                                            <div class="tracker-company-mark">
                                                {{ mb_substr($job->company_name, 0, 1) }}
                                            </div>
                                            <div class="min-w-0">
                                                <strong class="text-xs text-[#35264f] block truncate">
                                                    {{ $job->position }}
                                                </strong>
                                                <span class="text-[11px] text-[#767082] block truncate">
                                                    {{ $job->company_name }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="admin-status-chip admin-status-chip--{{ $tone }}">
                                            {{ $job->application_status }}
                                        </span>
                                    </td>
                                    <td class="text-right text-[11px] text-[#767082] whitespace-nowrap">
                                        {{ $job->application_date ? $job->application_date->format('d M Y') : '—' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-8 text-zinc-500">
                                        No job applications tracked by this member yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Right Column: Application Performance Summary --}}
            <aside class="workspace-card workspace-card--glass automation-side-panel">
                <section class="automation-side-section">
                    <h2>Application Metrics</h2>
                    
                    <div class="au-stat-summary-grid">
                        <div class="au-stat-box">
                            <span>Total Applied</span>
                            <strong>{{ number_format($totalApplications) }}</strong>
                        </div>
                        <div class="au-stat-box">
                            <span>Accepted</span>
                            <strong style="color: #7751a0;">{{ number_format($recentApplications->where('application_status', 'Accepted')->count()) }}</strong>
                        </div>
                        <div class="au-stat-box">
                            <span>In Progress</span>
                            <strong style="color: #34749b;">{{ number_format($recentApplications->where('application_status', 'On Process')->count()) }}</strong>
                        </div>
                        <div class="au-stat-box">
                            <span>Declined</span>
                            <strong style="color: #b94b34;">{{ number_format($declinedApplications) }}</strong>
                        </div>
                    </div>

                    <p class="workspace-hint" style="margin-top: 14px;">
                        Data represents records stored directly in candidate's career tracker workspace.
                    </p>
                </section>
            </aside>
        </div>
    </section>

    {{-- =====================================================================
         TAB 4: ACTIVITY LOG & AUDIT (activeTab === 'activity')
         ===================================================================== --}}
    <section id="tab-activity" x-show="activeTab === 'activity'" x-cloak class="workspace-stack">
        <div class="workspace-page__grid automation-balanced-grid">
            {{-- Left Column: Activity Log Table --}}
            <div class="workspace-card">
                <div class="workspace-card__heading" style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <h2>Recent Activity Timeline ({{ $recentActivities->count() }})</h2>
                        <p>Chronological audit record of user interactions and security events.</p>
                    </div>
                    <a href="{{ route('admin.user-activities') }}?search={{ urlencode($user->email) }}" class="au-clear" wire:navigate>
                        View all <i class="ph ph-arrow-up-right"></i>
                    </a>
                </div>

                <div class="au-table-container">
                    <table class="au-tracker-table">
                        <thead>
                            <tr>
                                <th>Event</th>
                                <th>Status</th>
                                <th class="text-right">When</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentActivities as $act)
                                <tr wire:key="act-{{ $act->id }}">
                                    <td>
                                        <strong class="text-xs text-[#35264f] block">
                                            {{ ucwords(str_replace('_', ' ', $act->activity_type)) }}
                                        </strong>
                                        <span class="text-[11px] text-[#767082] block truncate max-w-[280px]">
                                            {{ $act->description }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="au-tag" style="font-size: 10px; text-transform: capitalize; background: #f5f0f8; color: #695279; border: 1px solid #ebdfee;">
                                            {{ $act->status }}
                                        </span>
                                    </td>
                                    <td class="text-right text-[11px] text-[#767082] whitespace-nowrap">
                                        {{ $act->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-8 text-zinc-500">
                                        No recent activity logged for this member.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Right Column: Direct Outreach & Communications --}}
            <aside class="workspace-card workspace-card--glass automation-side-panel">
                <section class="automation-side-section">
                    <h2>Direct Communication</h2>
                    <p style="font-size: 12px; color: #767082; margin: 4px 0 14px;">Dispatch customized notification or support emails to this member.</p>

                    <button type="button" wire:click="openSendEmailModal" class="au-button au-button--primary" style="width: 100%;">
                        <i class="ph ph-paper-plane-tilt"></i> Send Custom Email
                    </button>

                    <div style="margin-top: 16px; border-top: 1px solid #edf0f4; padding-top: 14px;">
                        <h3 style="font-size: 12px; font-weight: 700; color: #35264f; margin-bottom: 8px;">Available Email Templates:</h3>
                        <ul class="workspace-checklist" style="font-size: 11px;">
                            <li>Account Verification & Reminders</li>
                            <li>Feature Announcements (AI CV, Photo)</li>
                            <li>Career Momentum & Monthly Motivation</li>
                            <li>Premium Membership Notifications</li>
                        </ul>
                    </div>
                </section>
            </aside>
        </div>
    </section>

    {{-- =====================================================================
         MODAL 1: Send Email ($showSendEmailModal)
         ===================================================================== --}}
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

    {{-- =====================================================================
         MODAL 2: Confirm Premium ($showPremiumConfirmModal)
         ===================================================================== --}}
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

    {{-- =====================================================================
         MODAL 3: Confirm Delete ($showDeleteConfirmModal)
         ===================================================================== --}}
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
