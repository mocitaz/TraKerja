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
                    <a href="{{ route('admin.users') }}" wire:navigate style="color: inherit; text-decoration: none;">{{ __('Member Directory') }}</a> {{ __('· Member #:value1', ['value1' => ($user->id)]) }}
                </p>
                <h1>{{ $user->name }}</h1>
                <p>{{ __('Manage member credentials, feature entitlements, job applications, and activity telemetry.') }}</p>
            </div>
            <div class="profile-page-header-actions">
                <a href="{{ route('admin.users') }}" class="profile-back" wire:navigate>
                    <i class="ph ph-arrow-left" aria-hidden="true"></i> {{ __('Back to members') }}
                </a>
                <button type="button" wire:click="openSendEmailModal" class="profile-secondary">
                    {{ __('Send Email') }}
                </button>
                <button type="button" wire:click="$set('showPremiumConfirmModal', true)" class="profile-secondary">
                    {{ $user->is_premium ? __('Revoke Premium') : __('Grant Premium') }}
                </button>
                <button type="button" wire:click="confirmDelete" class="profile-danger-button">
                    {{ __('Delete') }}
                </button>
            </div>
        </header>

        <!-- Two Column Layout: Identity Sidebar & Content Tabs -->
        <div class="profile-layout">
            <!-- Left Sticky Sidebar: Identity Card -->
            <aside class="profile-identity-card">
                <div class="profile-avatar-frame">
                    <img src="{{ $user->avatar_url }}" alt="{{ __(':value1\'s profile image', ['value1' => ($user->name)]) }}" width="88" height="88">
                </div>
                <div class="profile-identity-name-row">
                    <h2>{{ $user->name }}</h2>
                    @if($user->hasVerifiedEmail())
                        <span class="profile-blue-check-badge" title="{{ __('Verified Member (Email confirmed)') }}">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="#0284c7" aria-hidden="true">
                                <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    @endif
                </div>
                <p class="profile-identity-email">{{ $user->email }}</p>
                <span class="profile-plan {{ $user->isAdmin() ? 'profile-plan--admin' : ($user->isPremium() ? 'profile-plan--premium' : 'profile-plan--free') }}">
                    <i class="ph {{ $user->isAdmin() ? 'ph-shield-check' : ($user->isPremium() ? 'ph-crown' : 'ph-user') }}" aria-hidden="true"></i>
                    {{ $user->isAdmin() ? __('Administrator') : ($user->isPremium() ? __('Premium member') : __('Free account')) }}
                </span>
                <button type="button" wire:click="openSendEmailModal" class="profile-secondary" style="width: 100%;">
                    <i class="ph ph-envelope-simple" aria-hidden="true" style="margin-right: 5px;"></i>
                    {{ __('Send direct email') }}
                </button>
                <dl class="profile-account-facts">
                    <div>
                        <dt>{{ __('Account ID') }}</dt>
                        <dd><span class="profile-mono-id">#{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span></dd>
                    </div>
                    <div>
                        <dt>{{ __('Member since') }}</dt>
                        <dd>{{ $user->created_at->locale(app()->getLocale())->translatedFormat('d M Y') }}</dd>
                    </div>
                    <div>
                        <dt>{{ __('Email status') }}</dt>
                        <dd>
                            @if($user->hasVerifiedEmail())
                                <span class="profile-verified-badge" title="{{ __('Verified on :value1', ['value1' => ($user->email_verified_at?->locale(app()->getLocale())->translatedFormat('d M Y'))]) }}">
                                    <svg viewBox="0 0 24 24" width="13" height="13" fill="#0284c7" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ __('Verified') }}</span>
                                </span>
                            @else
                                <span class="profile-unverified-badge">
                                    <i class="ph ph-warning-circle" aria-hidden="true"></i>
                                    <span>{{ __('Unverified') }}</span>
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt>{{ __('Auth method') }}</dt>
                        <dd class="profile-fact-auth">
                            @if($user->google_id)
                                <span class="profile-auth-logo-btn" title="{{ __('Google OAuth') }}" aria-label="{{ __('Google') }}">
                                    <svg viewBox="0 0 24 24" width="15" height="15">
                                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                    </svg>
                                </span>
                            @elseif($user->linkedin_id)
                                <span class="profile-auth-logo-btn" title="{{ __('LinkedIn OAuth') }}" aria-label="{{ __('LinkedIn') }}">
                                    <svg viewBox="0 0 24 24" width="15" height="15" fill="#0077B5">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.225 0h.003z"/>
                                    </svg>
                                </span>
                            @else
                                <span class="profile-auth-logo-btn" title="{{ __('Email & Password') }}" aria-label="{{ __('Email') }}">
                                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="#475569" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect width="20" height="16" x="2" y="4" rx="3"></rect>
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                    </svg>
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt>{{ __('Applications') }}</dt>
                        <dd>{{ __(':value1 tracked', ['value1' => (number_format($totalApplications))]) }}</dd>
                    </div>
                    <div>
                        <dt>{{ __('AI credits') }}</dt>
                        <dd>{{ __(':value1 credits', ['value1' => (number_format($user->ai_credits))]) }}</dd>
                    </div>
                    <div>
                        <dt>{{ __('Status') }}</dt>
                        <dd>
                            @if($user->last_activity_at && $user->last_activity_at->diffInMinutes(now()) < 15)
                                <span class="profile-live-status profile-live-status--active">
                                    <span class="profile-live-dot profile-live-dot--pulse"></span>
                                    <span>{{ __('Active now') }}</span>
                                </span>
                            @elseif($user->last_activity_at)
                                <span class="profile-live-status profile-live-status--recent">
                                    <span class="profile-live-dot"></span>
                                    <span>{{ __('Active') }}</span>
                                </span>
                            @else
                                <span class="profile-live-status profile-live-status--idle">
                                    <span class="profile-live-dot"></span>
                                    <span>{{ __('Idle') }}</span>
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt>{{ __('Last active') }}</dt>
                        <dd>{{ $user->last_activity_at ? $user->last_activity_at->diffForHumans() : __('No activity') }}</dd>
                    </div>
                </dl>
                @if($user->isAdmin())
                    <div class="profile-admin-access">
                        <strong>{{ __('Administrator privilege') }}</strong>
                        <p>{{ __('Full administrative access to the admin dashboard, user management directory, and platform telemetry.') }}</p>
                    </div>
                @endif
                <p class="profile-identity-note"><i class="ph ph-calendar-blank" aria-hidden="true" style="margin-right: 4px;"></i>{{ __('Registered on :value1', ['value1' => ($user->created_at->locale(app()->getLocale())->translatedFormat('d M Y'))]) }}</p>
            </aside>

            <!-- Right Column: Tabs & Stacked Panels -->
            <div class="profile-content">
                <!-- Pill Tabs Switcher -->
                <nav class="profile-tabs" role="tablist" aria-label="{{ __('Member management sections') }}">
                    <button type="button" role="tab" :aria-selected="(tab === 'account').toString()" @click="selectTab('account')">{{ __('Account') }}</button>
                    <button type="button" role="tab" :aria-selected="(tab === 'quotas').toString()" @click="selectTab('quotas')">{{ __('AI Quotas & Plan') }}</button>
                    <button type="button" role="tab" :aria-selected="(tab === 'tracker').toString()" @click="selectTab('tracker')">{{ __('Job Tracker (:value1)', ['value1' => ($totalApplications)]) }}</button>
                    <button type="button" role="tab" :aria-selected="(tab === 'activity').toString()" @click="selectTab('activity')">{{ __('Activity (:value1)', ['value1' => ($recentActivities->count())]) }}</button>
                </nav>

                <!-- TAB 1: Account & Credentials -->
                <div id="panel-account" role="tabpanel" x-show="tab === 'account'" class="profile-panel-stack">
                    <section class="profile-panel">
                        <header class="profile-section-header">
                            <div>
                                <h2>{{ __('Account credentials') }}</h2>
                                <p>{{ __('Modify member profile identity, primary authentication email, and tier permissions.') }}</p>
                            </div>
                        </header>
                        <form wire:submit="updateUser">
                            <div class="profile-field-grid">
                                <div class="profile-field">
                                    <label for="detail-user-name">{{ __('Full name') }}</label>
                                    <input id="detail-user-name" type="text" wire:model="name" required placeholder="{{ __('Member full name') }}">
                                    @error('name')<p class="profile-field-error">{{ $message }}</p>@enderror
                                </div>
                                <div class="profile-field">
                                    <label for="detail-user-email">{{ __('Email address') }}</label>
                                    <input id="detail-user-email" type="email" wire:model="email" required placeholder="member@email.com">
                                    @error('email')<p class="profile-field-error">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div style="margin-top: 6px; margin-bottom: 14px;">
                                <label class="profile-preference-row" for="pref-premium">
                                    <span>
                                        <strong>{{ __('Premium membership') }}</strong>
                                        <small>{{ __('Maximized AI feature quotas, full ATS resume audits, and unlimited job tracking privileges.') }}</small>
                                    </span>
                                    <input class="profile-switch" id="pref-premium" type="checkbox" role="switch" wire:model.live="is_premium">
                                </label>
                                <label class="profile-preference-row" for="pref-admin">
                                    <span>
                                        <strong>{{ __('Administrator privilege') }}</strong>
                                        <small>{{ __('Full administrative access to the admin dashboard, user management directory, and platform telemetry.') }}</small>
                                    </span>
                                    <input class="profile-switch" id="pref-admin" type="checkbox" role="switch" wire:model.live="is_admin">
                                </label>
                            </div>

                            <div class="profile-verification profile-notice-box" style="margin-bottom: 14px;">
                                <span>{{ __('Updating membership tier recalculates feature availability and quota limits across candidate tools in real-time.') }}</span>
                            </div>

                            <div class="profile-form-footer">
                                <span>{{ __('Account updates reflect immediately across all active user sessions.') }}</span>
                                <button type="submit" class="profile-primary" wire:loading.attr="disabled" wire:target="updateUser">
                                    <span wire:loading.remove wire:target="updateUser">{{ __('Save account details') }}</span>
                                    <span wire:loading wire:target="updateUser">{{ __('Saving…') }}</span>
                                </button>
                            </div>
                        </form>
                    </section>

                    <section class="profile-panel profile-danger">
                        <h2>{{ __('Delete member account') }}</h2>
                        <p>{{ __('This permanently removes this member\'s account and purges all their tracked job applications, resumes, and telemetry records from the database. This action cannot be reversed.') }}</p>
                        <button type="button" wire:click="confirmDelete" class="profile-danger-button">
                            {{ __('Permanently delete account') }}
                        </button>
                    </section>
                </div>

                <!-- TAB 2: AI Quotas & Plan -->
                <div id="panel-quotas" role="tabpanel" x-show="tab === 'quotas'" x-cloak class="profile-panel-stack">
                    <section class="profile-panel">
                        <header class="profile-section-header">
                            <div>
                                <h2>{{ __('AI feature quotas') }}</h2>
                                <p>{{ __('Adjust available usage credits for AI-powered career tools.') }}</p>
                            </div>
                            <button type="button" wire:click="resetAllAiQuotas" wire:confirm="Reset AI quotas for this member to plan defaults?" class="profile-secondary">
                                {{ __('Reset to defaults') }}
                            </button>
                        </header>
                        <form wire:submit="saveAiQuotas">
                            <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px;">
                                <div class="profile-field profile-quota-card">
                                    <label for="detail-ai-credits" style="font-size: 11.5px; margin-bottom: 6px;">{{ __('CV analysis credits') }}</label>
                                    <input id="detail-ai-credits" type="number" wire:model="ai_credits" min="0" step="1" required style="font-size: 18px; font-weight: 600;">
                                    @error('ai_credits')<p class="profile-field-error">{{ $message }}</p>@enderror
                                    <small style="display: block; font-size: 10.5px; margin-top: 6px; line-height: 1.4;">{{ __('Full resume ATS audits and recommendations') }}</small>
                                </div>

                                <div class="profile-field profile-quota-card">
                                    <label for="detail-cl-credits" style="font-size: 11.5px; margin-bottom: 6px;">{{ __('Cover letter credits') }}</label>
                                    <input id="detail-cl-credits" type="number" wire:model="cl_credits" min="0" step="1" required style="font-size: 18px; font-weight: 600;">
                                    @error('cl_credits')<p class="profile-field-error">{{ $message }}</p>@enderror
                                    <small style="display: block; font-size: 10.5px; margin-top: 6px; line-height: 1.4;">{{ __('Custom cover letter generations') }}</small>
                                </div>

                                <div class="profile-field profile-quota-card">
                                    <label for="detail-photo-credits" style="font-size: 11.5px; margin-bottom: 6px;">{{ __('AI photo credits') }}</label>
                                    <input id="detail-photo-credits" type="number" wire:model="photo_credits" min="0" step="1" required style="font-size: 18px; font-weight: 600;">
                                    @error('photo_credits')<p class="profile-field-error">{{ $message }}</p>@enderror
                                    <small style="display: block; font-size: 10.5px; margin-top: 6px; line-height: 1.4;">{{ __('Professional headshot photo requests') }}</small>
                                </div>
                            </div>

                            <div class="profile-verification profile-notice-box" style="margin-top: 14px; margin-bottom: 0;">
                                <span>{{ __('Trial status:') }} <strong>{{ $user->has_used_ai_analyzer_trial ? __('Free trial used') : __('Free trial available') }}</strong></span>
                                @if($user->ai_analyzer_trial_used_at)
                                    <span> {{ __('· Used on :value1', ['value1' => ($user->ai_analyzer_trial_used_at->locale(app()->getLocale())->translatedFormat('d M Y'))]) }}</span>
                                @endif
                            </div>

                            <div class="profile-form-footer" style="margin-top: 14px;">
                                <span>{{ __('Adjusted quotas update candidate workspace immediately.') }}</span>
                                <button type="submit" class="profile-primary" wire:loading.attr="disabled" wire:target="saveAiQuotas">
                                    <span wire:loading.remove wire:target="saveAiQuotas">{{ __('Save AI quotas') }}</span>
                                    <span wire:loading wire:target="saveAiQuotas">{{ __('Saving…') }}</span>
                                </button>
                            </div>
                        </form>
                    </section>

                    <section class="profile-panel">
                        <header class="profile-section-header">
                            <div>
                                <h2>{{ __('Membership & billing') }}</h2>
                                <p>{{ __('Current plan entitlement, payment status, and duration.') }}</p>
                            </div>
                            <button type="button" wire:click="$set('showPremiumConfirmModal', true)" class="profile-secondary">
                                {{ $user->is_premium ? __('Revoke premium access') : __('Grant premium access') }}
                            </button>
                        </header>
                        <dl class="profile-account-facts" style="border-top: none; margin-top: 0; padding-top: 0; display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px 24px;">
                            <div>
                                <dt>{{ __('Plan duration') }}</dt>
                                <dd>
                                    @if($user->premium_until)
                                        Active until {{ $user->premium_until->locale(app()->getLocale())->translatedFormat('d M Y') }}
                                    @elseif($user->isPremium())
                                        Lifetime active
                                    @else
                                        Free standard tier
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt>{{ __('Payment status') }}</dt>
                                <dd style="text-transform: uppercase;">{{ $user->payment_status ?: __('Free') }}</dd>
                            </div>
                            <div>
                                <dt>{{ __('Purchased at') }}</dt>
                                <dd>{{ $user->premium_purchased_at ? $user->premium_purchased_at->locale(app()->getLocale())->translatedFormat('d M Y') : '—' }}</dd>
                            </div>
                            <div>
                                <dt>{{ __('Grandfathered benefits') }}</dt>
                                <dd>{{ $user->grandfathered_benefits ? __('Active') : __('None') }}</dd>
                            </div>
                        </dl>
                    </section>
                </div>

                <!-- TAB 3: Job Tracker -->
                <div id="panel-tracker" role="tabpanel" x-show="tab === 'tracker'" x-cloak class="profile-panel-stack">
                    <section class="profile-panel">
                        <header class="profile-section-header">
                            <div>
                                <h2>{{ __('Application metrics') }}</h2>
                                <p>{{ __('Summary of candidate tracker statistics stored in database.') }}</p>
                            </div>
                        </header>
                        <div class="profile-stat-grid">
                            <div class="profile-stat-card">
                                <span>{{ __('Total applied') }}</span>
                                <strong>{{ number_format($totalApplications) }}</strong>
                            </div>
                            <div class="profile-stat-card">
                                <span>{{ __('Accepted') }}</span>
                                <strong class="stat-accepted">{{ number_format($recentApplications->where('application_status', 'Accepted')->count()) }}</strong>
                            </div>
                            <div class="profile-stat-card">
                                <span>{{ __('In progress') }}</span>
                                <strong class="stat-progress">{{ number_format($recentApplications->where('application_status', 'On Process')->count()) }}</strong>
                            </div>
                            <div class="profile-stat-card">
                                <span>{{ __('Declined') }}</span>
                                <strong class="stat-declined">{{ number_format($declinedApplications) }}</strong>
                            </div>
                        </div>
                    </section>

                    <section class="profile-panel">
                        <header class="profile-section-header">
                            <div>
                                <h2>{{ __('Tracked applications (:value1)', ['value1' => ($totalApplications)]) }}</h2>
                                <p>{{ __('Opportunities logged by this candidate in their personal tracker.') }}</p>
                            </div>
                        </header>
                        @if($recentApplications->isNotEmpty())
                            <div style="overflow-x: auto;">
                                <table class="profile-table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Opportunity') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th style="text-align: right;">{{ __('Logged date') }}</th>
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
                                                    <strong class="profile-table-title">{{ $job->position }}</strong>
                                                    <span class="profile-table-sub">{{ $job->company_name }}</span>
                                                </td>
                                                <td>
                                                    <span class="profile-chip profile-chip--{{ $tone }}">
                                                        {{ \App\Support\UiLabel::value($job->application_status) }}
                                                    </span>
                                                </td>
                                                <td class="profile-table-date">
                                                    {{ $job->application_date ? $job->application_date->locale(app()->getLocale())->translatedFormat('d M Y') : '—' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="profile-empty">{{ __('No job applications tracked by this member yet.') }}</p>
                        @endif
                    </section>
                </div>

                <!-- TAB 4: Activity Log -->
                <div id="panel-activity" role="tabpanel" x-show="tab === 'activity'" x-cloak class="profile-panel-stack">
                    <section class="profile-panel">
                        <header class="profile-section-header">
                            <div>
                                <h2>{{ __('Recent account activity') }}</h2>
                                <p>{{ __('Chronological audit record of user interactions and security events.') }}</p>
                            </div>
                            <a href="{{ route('admin.user-activities') }}?search={{ urlencode($user->email) }}" class="profile-secondary" wire:navigate>
                                {{ __('View full audit log') }}
                            </a>
                        </header>
                        @forelse($recentActivities as $act)
                            <div class="profile-activity-item" wire:key="act-{{ $act->id }}">
                                <span class="profile-activity-icon"><i class="ph ph-{{ $act->status === 'success' ? 'check' : 'info' }}" aria-hidden="true"></i></span>
                                <div>
                                    <strong>{{ ucwords(str_replace('_', ' ', $act->activity_type)) }}</strong>
                                    <p>{{ $act->description }}</p>
                                    <span>{{ $act->ip_address ?: __('System event') }}</span>
                                </div>
                                <time datetime="{{ $act->created_at->toIso8601String() }}">{{ $act->created_at->diffForHumans() }}</time>
                            </div>
                        @empty
                            <p class="profile-empty">{{ __('No recent activity logged for this member.') }}</p>
                        @endforelse
                    </section>

                    <section class="profile-panel">
                        <header class="profile-section-header">
                            <div>
                                <h2>{{ __('Direct communication') }}</h2>
                                <p>{{ __('Dispatch customized notifications or system emails to this member.') }}</p>
                            </div>
                            <button type="button" wire:click="openSendEmailModal" class="profile-primary">
                                {{ __('Send custom email') }}
                            </button>
                        </header>
                        <div class="profile-templates-list">
                            <p style="margin: 0 0 8px;">{{ __('Available pre-configured message templates:') }}</p>
                            <ul style="margin: 0; padding-left: 18px; display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 4px;">
                                <li>{{ __('Account verification & reminders') }}</li>
                                <li>{{ __('Feature announcements (AI CV, Studio)') }}</li>
                                <li>{{ __('Career momentum & monthly motivation') }}</li>
                                <li>{{ __('Premium membership notifications') }}</li>
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
                            <h2>{{ __('Send email to member') }}</h2>
                            <p>{{ __('Choose an automated email template to dispatch directly to user\'s inbox.') }}</p>
                        </div>
                        <button type="button" wire:click="closeSendEmailModal" class="profile-modal-close" aria-label="{{ __('Close dialog') }}">
                            <i class="ph ph-x" aria-hidden="true"></i>
                        </button>
                    </header>
                    <div class="profile-modal-recipient">
                        <strong>{{ $user->name }}</strong>
                        <span>{{ $user->email }}</span>
                    </div>
                    <form wire:submit="sendEmail">
                        <div class="profile-field">
                            <label for="member-email-type">{{ __('Message template') }}</label>
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
                                    <option value="{{ $type }}">{{ __($label) }}</option>
                                @endforeach
                            </select>
                            @error('emailType')<p class="profile-field-error">{{ $message }}</p>@enderror
                        </div>
                        <p class="profile-modal-hint" style="margin: 4px 0 16px;">{{ __('Email will be delivered immediately via the TraKerja mail system.') }}</p>
                        <div class="profile-form-footer">
                            <button type="button" wire:click="closeSendEmailModal" class="profile-secondary">{{ __('Cancel') }}</button>
                            <button type="submit" wire:loading.attr="disabled" wire:target="sendEmail" class="profile-primary">
                                <span wire:loading.remove wire:target="sendEmail">{{ __('Send email now') }}</span>
                                <span wire:loading wire:target="sendEmail">{{ __('Sending…') }}</span>
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
                            <h2>{{ $user->is_premium ? __('Revoke premium access?') : __('Grant premium access?') }}</h2>
                            <p>{{ __('Confirm the membership tier change for this user account.') }}</p>
                        </div>
                        <button type="button" wire:click="$set('showPremiumConfirmModal', false)" class="profile-modal-close" aria-label="{{ __('Close dialog') }}">
                            <i class="ph ph-x" aria-hidden="true"></i>
                        </button>
                    </header>
                    <div class="profile-modal-recipient profile-modal-recipient--warning">
                        <strong>{{ $user->name }}</strong>
                        <span>
                            {{ $user->is_premium
                                ? __('Premium access will be deactivated. Exclusive AI features and priority quotas will return to standard free tier.')
                                : __('Premium access will be activated for ') . config('pakasir.premium_duration_days', 365) . __(' days with maximized AI feature quotas.') }}
                        </span>
                    </div>
                    <div class="profile-form-footer">
                        <button type="button" wire:click="$set('showPremiumConfirmModal', false)" class="profile-secondary">{{ __('Cancel') }}</button>
                        <button type="button" wire:click="togglePremium" wire:loading.attr="disabled" wire:target="togglePremium" class="profile-primary">
                            <span wire:loading.remove wire:target="togglePremium">{{ $user->is_premium ? __('Yes, revoke premium') : __('Yes, grant premium') }}</span>
                            <span wire:loading wire:target="togglePremium">{{ __('Processing…') }}</span>
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
                            <h2 class="profile-modal-danger-title">{{ __('Permanently delete account?') }}</h2>
                            <p>{{ __('This action is irreversible and will delete all user data permanently.') }}</p>
                        </div>
                        <button type="button" wire:click="closeDeleteConfirm" class="profile-modal-close" aria-label="{{ __('Close dialog') }}">
                            <i class="ph ph-x" aria-hidden="true"></i>
                        </button>
                    </header>
                    <div class="profile-modal-recipient profile-modal-recipient--danger">
                        <strong>{{ $user->name }} ({{ $user->email }})</strong>
                        <span>{{ __('All account data, tracked job applications, activity records, and resumes will be permanently purged from the database.') }}</span>
                    </div>
                    <div class="profile-form-footer">
                        <button type="button" wire:click="closeDeleteConfirm" class="profile-secondary">{{ __('Cancel, keep account') }}</button>
                        <button type="button" wire:click="performDelete" wire:loading.attr="disabled" wire:target="performDelete" class="profile-danger-button">
                            <span wire:loading.remove wire:target="performDelete">{{ __('Permanently delete') }}</span>
                            <span wire:loading wire:target="performDelete">{{ __('Deleting…') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    @endif
</div>
