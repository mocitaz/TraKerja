    @if($showEditModal)
        <template x-teleport="body">
            <div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showEditModal" data-dialog-close="$wire.closeModal()" aria-labelledby="showEditModal-title" class="au-dialog-overlay am-member-modal">
                <section class="au-dialog">
                    <header class="au-dialog-heading">
                        <div>
                            <h2 id="showEditModal-title">{{ __('Manage account') }}</h2>
                            <p>{{ __('Profile details, membership privileges, and AI credit quotas.') }}</p>
                        </div>
                        <button type="button" wire:click="closeModal" class="au-dialog-close" aria-label="{{ __('Close dialog') }}">
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
                                        {{ $editingUser->isAdmin() ? __('Admin') : __('Member') }}
                                    </span>
                                    <span class="au-plan-badge {{ $editingUser->isPremium() ? 'au-plan-badge--premium' : 'au-plan-badge--free' }}">
                                        {{ $editingUser->isPremium() ? __('Premium') : __('Free') }}
                                    </span>
                                </div>
                            </div>
                        @endif

                        <form wire:submit="updateUser" class="au-form au-form-section">
                            <h3>{{ __('Account & Access Information') }}</h3>
                            <label for="edit-member-name">
                                {{ __('Full Name') }}
                                <input id="edit-member-name" type="text" wire:model="editName" required placeholder="{{ __('Member full name…') }}">
                                @error('editName')<span class="au-field-error">{{ $message }}</span>@enderror
                            </label>
                            <label for="edit-member-email">
                                {{ __('Email Address') }}
                                <input id="edit-member-email" type="email" wire:model="editEmail" required placeholder="member@email.com">
                                @error('editEmail')<span class="au-field-error">{{ $message }}</span>@enderror
                            </label>
                            <div class="au-form-actions">
                                <button type="submit" class="au-button au-button--primary" wire:loading.attr="disabled" wire:target="updateUser">
                                    <span wire:loading.remove wire:target="updateUser">{{ __('Save Account Changes') }}</span>
                                    <span wire:loading wire:target="updateUser">{{ __('Saving…') }}</span>
                                </button>
                            </div>
                        </form>

                        <form wire:submit="saveAiQuotas" class="au-form au-form-section" style="margin-top: 18px;">
                            <h3>{{ __('AI Feature Quota Credits') }}</h3>
                            <div class="au-quota-grid">
                                <div class="au-quota-tile">
                                    <label for="credit-editAiCredits">
                                        {{ __('Analyze CV') }}
                                        <input id="credit-editAiCredits" type="number" wire:model="editAiCredits" min="0" step="1" required>
                                        @error('editAiCredits')<span class="au-field-error">{{ $message }}</span>@enderror
                                    </label>
                                </div>
                                <div class="au-quota-tile">
                                    <label for="credit-editClCredits">
                                        {{ __('Cover Letter') }}
                                        <input id="credit-editClCredits" type="number" wire:model="editClCredits" min="0" step="1" required>
                                        @error('editClCredits')<span class="au-field-error">{{ $message }}</span>@enderror
                                    </label>
                                </div>
                                <div class="au-quota-tile">
                                    <label for="credit-editPhotoCredits">
                                        {{ __('AI Photo Studio') }}
                                        <input id="credit-editPhotoCredits" type="number" wire:model="editPhotoCredits" min="0" step="1" required>
                                        @error('editPhotoCredits')<span class="au-field-error">{{ $message }}</span>@enderror
                                    </label>
                                </div>
                            </div>
                            <div class="au-form-actions">
                                <button type="submit" class="au-button au-button--primary" wire:loading.attr="disabled" wire:target="saveAiQuotas">
                                    <span wire:loading.remove wire:target="saveAiQuotas">{{ __('Save AI Quotas') }}</span>
                                    <span wire:loading wire:target="saveAiQuotas">{{ __('Saving…') }}</span>
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
            <div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showCreateAdminModal" data-dialog-close="$wire.closeCreateAdminModal()" aria-labelledby="showCreateAdminModal-title" class="au-dialog-overlay am-member-modal">
                <section class="au-dialog">
                    <header class="au-dialog-heading">
                        <div>
                            <h2 id="showCreateAdminModal-title">{{ __('Add Administrator') }}</h2>
                            <p>{{ __('Create a new administrator account with full platform management privileges.') }}</p>
                        </div>
                        <button type="button" wire:click="closeCreateAdminModal" class="au-dialog-close" aria-label="{{ __('Close dialog') }}">
                            <i class="ph ph-x" aria-hidden="true"></i>
                        </button>
                    </header>
                    <div class="au-dialog-body">
                        <form wire:submit="createAdmin" class="au-form">
                            <label for="admin-name">
                                {{ __('Full Name') }}
                                <input id="admin-name" type="text" wire:model="newAdminName" required placeholder="{{ __('Administrator full name…') }}">
                                @error('newAdminName')<span class="au-field-error">{{ $message }}</span>@enderror
                            </label>
                            <label for="admin-email">
                                {{ __('Email Address') }}
                                <input id="admin-email" type="email" wire:model="newAdminEmail" required placeholder="admin@trakerja.com">
                                @error('newAdminEmail')<span class="au-field-error">{{ $message }}</span>@enderror
                            </label>
                            <label for="admin-password">
                                {{ __('Password') }}
                                <input id="admin-password" type="password" wire:model="newAdminPassword" required minlength="8" autocomplete="new-password" placeholder="{{ __('At least 8 characters…') }}">
                                @error('newAdminPassword')<span class="au-field-error">{{ $message }}</span>@enderror
                            </label>
                            <label for="admin-password-confirmation">
                                {{ __('Confirm Password') }}
                                <input id="admin-password-confirmation" type="password" wire:model="newAdminPasswordConfirmation" required minlength="8" autocomplete="new-password" placeholder="{{ __('Re-type password…') }}">
                                @error('newAdminPasswordConfirmation')<span class="au-field-error">{{ $message }}</span>@enderror
                            </label>

                            <div class="au-dialog-actions">
                                <button type="button" wire:click="closeCreateAdminModal" class="au-button">{{ __('Cancel') }}</button>
                                <button type="submit" wire:loading.attr="disabled" wire:target="createAdmin" class="au-button au-button--primary">
                                    <span wire:loading.remove wire:target="createAdmin">{{ __('Create Administrator') }}</span>
                                    <span wire:loading wire:target="createAdmin">{{ __('Creating…') }}</span>
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
            <div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showSendEmailModal" data-dialog-close="$wire.closeSendEmailModal()" aria-labelledby="showSendEmailModal-title" class="au-dialog-overlay am-member-modal">
                <section class="au-dialog au-dialog--small">
                    <header class="au-dialog-heading">
                        <div>
                            <h2 id="showSendEmailModal-title">{{ __('Send Email to Member') }}</h2>
                            <p>{{ __('Choose an automated email template to dispatch directly to the user\'s inbox.') }}</p>
                        </div>
                        <button type="button" wire:click="closeSendEmailModal" class="au-dialog-close" aria-label="{{ __('Close dialog') }}">
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
                                {{ __('Message Template') }}
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
                                        <option value="{{ $type }}">{{ __($label) }}</option>
                                    @endforeach
                                </select>
                                @error('emailType')<span class="au-field-error">{{ $message }}</span>@enderror
                            </label>
                            <p class="au-form-hint">{{ __('Email will be delivered immediately via the TraKerja mail system to the recipient above.') }}</p>
                            <div class="au-dialog-actions">
                                <button type="button" wire:click="closeSendEmailModal" class="au-button">{{ __('Cancel') }}</button>
                                <button type="submit" wire:loading.attr="disabled" wire:target="sendEmail" class="au-button au-button--primary">
                                    <span wire:loading.remove wire:target="sendEmail">{{ __('Send Email Now') }}</span>
                                    <span wire:loading wire:target="sendEmail">{{ __('Sending…') }}</span>
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
            <div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showPremiumConfirmModal" data-dialog-close="$wire.closePremiumConfirmModal()" aria-labelledby="showPremiumConfirmModal-title" class="au-dialog-overlay am-member-modal">
                <section class="au-dialog au-dialog--small">
                    <header class="au-dialog-heading">
                        <div>
                            <h2 id="showPremiumConfirmModal-title">
                                {{ $confirmTargetUserIsPremium ? __('Revoke premium access?') : __('Grant premium access?') }}
                            </h2>
                            <p>{{ __('Confirm the membership tier change for this user account.') }}</p>
                        </div>
                        <button type="button" wire:click="closePremiumConfirmModal" class="au-dialog-close" aria-label="{{ __('Close dialog') }}">
                            <i class="ph ph-x" aria-hidden="true"></i>
                        </button>
                    </header>
                    <div class="au-dialog-body">
                        <div class="au-recipient au-recipient--warning">
                            <strong>{{ $confirmTargetUserName }}</strong>
                            <span>
                                {{ $confirmTargetUserIsPremium
                                    ? __('Premium access will be deactivated. Exclusive AI features and priority quotas will return to standard free tier.')
                                    : __('Premium access will be activated for ') . config('pakasir.premium_duration_days', 365) . __(' days with maximized AI feature quotas.') }}
                            </span>
                        </div>
                        <div class="au-dialog-actions">
                            <button type="button" wire:click="closePremiumConfirmModal" class="au-button">{{ __('Cancel') }}</button>
                            <button type="button" wire:click="toggleManualPremium" wire:loading.attr="disabled" wire:target="toggleManualPremium" class="au-button au-button--primary">
                                <span wire:loading.remove wire:target="toggleManualPremium">{{ $confirmTargetUserIsPremium ? __('Yes, Revoke Premium') : __('Yes, Grant Premium') }}</span>
                                <span wire:loading wire:target="toggleManualPremium">{{ __('Processing…') }}</span>
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
            <div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showDeleteConfirmModal" data-dialog-close="$wire.closeDeleteConfirmModal()" aria-labelledby="showDeleteConfirmModal-title" class="au-dialog-overlay am-member-modal">
                <section class="au-dialog au-dialog--small">
                    <header class="au-dialog-heading">
                        <div>
                            <h2 id="showDeleteConfirmModal-title">{{ __('Delete this account?') }}</h2>
                            <p>{{ __('This action is irreversible and will delete all user data permanently.') }}</p>
                        </div>
                        <button type="button" wire:click="closeDeleteConfirmModal" class="au-dialog-close" aria-label="{{ __('Close dialog') }}">
                            <i class="ph ph-x" aria-hidden="true"></i>
                        </button>
                    </header>
                    <div class="au-dialog-body">
                        <div class="au-recipient au-recipient--danger">
                            <strong>{{ $deleteTargetUserName }}</strong>
                            <span>{{ __('All account data, tracked job applications, activity records, and resumes will be permanently purged from the database.') }}</span>
                        </div>
                        <div class="au-dialog-actions">
                            <button type="button" wire:click="closeDeleteConfirmModal" class="au-button">{{ __('Cancel, Keep Account') }}</button>
                            <button type="button" wire:click="performDelete" wire:loading.attr="disabled" wire:target="performDelete" class="au-button au-button--danger">
                                <span wire:loading.remove wire:target="performDelete">{{ __('Permanently Delete') }}</span>
                                <span wire:loading wire:target="performDelete">{{ __('Deleting…') }}</span>
                            </button>
                        </div>
                    </div>
                </section>
            </div>
        </template>
    @endif
