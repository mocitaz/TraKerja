<x-dynamic-component :component="$user->isAdmin() ? 'admin-layout' : 'app-layout'">
    @php
        $isAdmin = $user->isAdmin();
        $profile = $user->profile;
        $avatarId = \App\Support\AvatarCatalog::resolve($user->avatar_id, (int) $user->id);
        $previousChoice = old('avatar_id', $avatarId);
        $avatarChoice = is_string($previousChoice) && isset(\App\Support\AvatarCatalog::all()[$previousChoice]) ? $previousChoice : $avatarId;
        $initialTab = match (true) {
            $errors->updatePassword->any(), $errors->userDeletion->any(), session('status') === 'password-updated' => 'security',
            $errors->has('avatar_id'), $errors->has('logo'), in_array(session('status'), ['avatar-updated', 'photo-updated', 'photo-removed']) => 'about',
            session('status') === 'notifications-updated', session('error') === 'upgrade-required' => 'notifications',
            default => 'about',
        };
        $statusMessages = [
            'profile-updated' => 'Account details saved.', 'personal-info-updated' => 'Profile details saved.',
            'avatar-updated' => 'Your new avatar is ready.', 'photo-updated' => 'Profile photo updated.',
            'photo-removed' => 'Photo removed. Your avatar is back.', 'password-updated' => 'Password updated.',
            'notifications-updated' => 'Notification preferences saved.', 'verification-link-sent' => 'Verification email sent.',
        ];
        $tabs = $isAdmin
            ? ['about' => 'Account', 'security' => 'Security', 'activity' => 'Activity']
            : ['about' => 'Account', 'security' => 'Security', 'notifications' => 'Notifications', 'activity' => 'Activity'];
        if (!isset($tabs[$initialTab])) $initialTab = 'about';
    @endphp
    <div class="profile-workspace" x-data="{
        tab: @js($initialTab), avatarChoice: @js($avatarChoice),
        moveTab(event, direction) {
            const tabs = [...this.$refs.profileTabs.querySelectorAll('[role=tab]')];
            const index = tabs.indexOf(event.target);
            const next = tabs[(index + direction + tabs.length) % tabs.length];
            next.click(); next.focus();
        }
    }">
        <div class="profile-container">
            <header class="profile-page-header">
                <div><p class="profile-eyebrow">{{ $isAdmin ? 'Admin account' : 'Your account' }}</p><h1>{{ $isAdmin ? 'Admin profile' : 'Profile settings' }}</h1><p>{{ $isAdmin ? 'Manage your identity, profile image, and account security.' : 'Manage your profile, preferences, and account security.' }}</p></div>
                <a href="{{ route($isAdmin ? 'admin.index' : 'dashboard') }}" class="profile-back"><i class="ph ph-arrow-left" aria-hidden="true"></i>{{ $isAdmin ? 'Back to admin dashboard' : 'Back to home' }}</a>
            </header>
            @if(isset($statusMessages[session('status')]))
                <div class="profile-notice" role="status"><i class="ph ph-check-circle" aria-hidden="true"></i>{{ $statusMessages[session('status')] }}</div>
            @endif
            <div class="profile-layout">
                <aside class="profile-identity-card">
                    <div class="profile-avatar-frame"><img src="{{ $user->avatar_url }}" alt="{{ $user->name }}'s profile image" width="88" height="88"></div>
                    <h2>{{ $user->name }}</h2>
                    <p class="profile-identity-email">{{ $user->email }}</p>
                    @if(!$isAdmin && $profile?->headline)<p class="profile-headline">{{ $profile->headline }}</p>@endif
                    <span class="profile-plan"><i class="ph {{ $user->isAdmin() ? 'ph-shield-check' : ($user->isPremium() ? 'ph-crown' : 'ph-user') }}" aria-hidden="true"></i>{{ $user->isAdmin() ? 'Administrator' : ($user->isPremium() ? 'Premium member' : 'Free account') }}</span>
                    <button type="button" @click="tab = 'about'; $nextTick(() => { $refs.avatarSection.scrollIntoView({ block: 'start', behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' }); $refs.avatarSection.focus({ preventScroll: true }); })" class="profile-secondary"><i class="ph ph-palette" aria-hidden="true"></i>Change your look</button>
                    <dl class="profile-account-facts">
                        <div><dt>{{ $isAdmin ? 'Account created' : 'Member since' }}</dt><dd>{{ $user->created_at->format('M Y') }}</dd></div>
                        <div><dt>Email status</dt><dd>{{ $user->hasVerifiedEmail() ? 'Verified' : 'Not verified' }}</dd></div>
                        @if(!$isAdmin && $profile?->domicile)<div><dt>Location</dt><dd>{{ $profile->domicile }}</dd></div>@endif
                    </dl>
                    @if($isAdmin)<div class="profile-admin-access"><strong>Administrator access</strong><p>Manage members, platform settings, and operational tools from the admin workspace.</p></div>@endif
                    <p class="profile-identity-note">Your photo or avatar appears across your workspace.</p>
                </aside>
                <div class="profile-content">
                    <nav class="profile-tabs" x-ref="profileTabs" role="tablist" aria-label="Profile settings" @keydown.right.prevent="moveTab($event, 1)" @keydown.left.prevent="moveTab($event, -1)" @keydown.home.prevent="$refs.profileTabs.firstElementChild.click(); $refs.profileTabs.firstElementChild.focus()" @keydown.end.prevent="$refs.profileTabs.lastElementChild.click(); $refs.profileTabs.lastElementChild.focus()">
                        @foreach($tabs as $id => $label)
                            <button type="button" id="profile-tab-{{ $id }}" role="tab" aria-controls="profile-panel-{{ $id }}" :aria-selected="(tab === '{{ $id }}').toString()" :tabindex="tab === '{{ $id }}' ? 0 : -1" @click="tab = '{{ $id }}'">{{ $label }}</button>
                        @endforeach
                    </nav>
                    <div id="profile-panel-about" role="tabpanel" aria-labelledby="profile-tab-about" x-show="tab === 'about'" @if($initialTab !== 'about') x-cloak @endif class="profile-panel-stack">
                        <section class="profile-panel">
                            <header class="profile-section-header"><div><h2>Account details</h2><p>{{ $isAdmin ? 'The name and email used for your admin account.' : 'Your name, email, and tracker preferences.' }}</p></div></header>
                            <form method="post" action="{{ route('profile.update') }}">
                                @csrf @method('patch')
                                <div class="profile-field-grid">
                                    <x-profile-field name="name" label="Full name" :value="$user->name" autocomplete="name" required maxlength="255" />
                                    <x-profile-field name="email" label="Email address" :value="$user->email" type="email" autocomplete="email" required maxlength="255" />
                                </div>
                                @if(!$user->hasVerifiedEmail())
                                    <div class="profile-verification"><span>Your email is not verified.</span><button type="submit" form="profile-send-verification" class="profile-text-button">Send verification email</button></div>
                                @endif
                                @unless($isAdmin)
                                <label class="profile-preference-row" for="profile-auto-archive"><span><strong>Auto-archive rejected applications</strong><small>Move older rejected or unprocessed applications out of your active tracker after 14 days.</small></span><input class="profile-switch" id="profile-auto-archive" name="auto_archive_rejected" type="checkbox" value="1" @checked(old('auto_archive_rejected', $user->auto_archive_rejected))></label>
                                @endunless
                                <div class="profile-form-footer"><span>Changing your email requires verification again.</span><button type="submit" class="profile-primary">Save account</button></div>
                            </form>
                            <form id="profile-send-verification" action="{{ route('verification.send') }}" method="post">@csrf</form>
                        </section>
                    <section id="profile-avatar-section" x-ref="avatarSection" tabindex="-1" aria-labelledby="profile-avatar-heading" class="profile-panel" style="scroll-margin-top: 110px;">
                        <header class="profile-section-header"><div><h2 id="profile-avatar-heading">Your profile image</h2><p>Choose an illustrated avatar or use a photo of your own.</p></div></header>
                        <form method="post" action="{{ route('profile.avatar.update') }}">
                            @csrf @method('patch')
                            <div class="profile-avatar-preview"><img :src="@js(asset('images/avatars')).replace(/\/$/, '') + '/' + avatarChoice + '.svg'" alt="Selected avatar preview" width="54" height="54"><div><h3>Choose an avatar</h3><p>Pick the illustration you want to use.</p><span>Choosing an avatar replaces your uploaded photo.</span></div></div>
                            <fieldset class="profile-avatar-options"><legend class="sr-only">Available avatars</legend>
                                @foreach(\App\Support\AvatarCatalog::all() as $id => $label)
                                    <label class="profile-avatar-option" :class="{ 'is-selected': avatarChoice === '{{ $id }}' }"><input type="radio" name="avatar_id" value="{{ $id }}" x-model="avatarChoice" @checked($avatarChoice === $id)><img src="{{ \App\Support\AvatarCatalog::url($id) }}" alt="" width="48" height="48"><span class="sr-only">{{ $label }}</span><i class="ph-fill ph-check-circle" x-show="avatarChoice === '{{ $id }}'" aria-hidden="true"></i></label>
                                @endforeach
                            </fieldset>
                            @error('avatar_id')<p class="profile-field-error">{{ $message }}</p>@enderror
                            <div class="profile-form-footer"><span>Available to every account.</span><button type="submit" class="profile-primary">Use this avatar</button></div>
                        </form>
                        <div class="profile-photo-section">
                            <div><h3>Prefer a photo?</h3><p>JPG, PNG, or GIF. Up to 2 MB.</p></div>
                            <form method="post" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data" x-data="{ fileName: '' }">
                                @csrf
                                <label for="profile-photo-file" class="profile-file-input"><i class="ph ph-upload-simple" aria-hidden="true"></i><span x-text="fileName || 'Choose a photo'">Choose a photo</span><input id="profile-photo-file" type="file" name="logo" accept="image/jpeg,image/png,image/gif" required @change="fileName = $event.target.files[0]?.name || ''"></label>
                                @error('logo')<p class="profile-field-error">{{ $message }}</p>@enderror
                                <button type="submit" class="profile-secondary">Upload photo</button>
                            </form>
                            @if($user->logo)<form method="post" action="{{ route('profile.photo.destroy') }}">@csrf @method('delete')<button type="submit" class="profile-text-button">Remove photo & use avatar</button></form>@endif
                        </div>
                    </section>
                    </div>
                    <div id="profile-panel-security" role="tabpanel" aria-labelledby="profile-tab-security" x-show="tab === 'security'" @if($initialTab !== 'security') x-cloak @endif class="profile-panel-stack">
                        <section class="profile-panel"><header class="profile-section-header"><div><h2>Password & security</h2><p>Keep access to your account secure.</p></div></header>@include('profile.partials.update-password-form')</section>
                        <section class="profile-panel profile-danger"><h2>Delete account</h2><p>This permanently removes your account and its associated data. Save any files you need before continuing.</p><button type="button" @click="$dispatch('open-modal', 'confirm-user-deletion')" class="profile-danger-button" aria-haspopup="dialog">Delete my account</button></section>
                    </div>
                    @unless($isAdmin)
                    <section id="profile-panel-notifications" role="tabpanel" aria-labelledby="profile-tab-notifications" x-show="tab === 'notifications'" @if($initialTab !== 'notifications') x-cloak @endif class="profile-panel">
                        <header class="profile-section-header"><div><h2>Email notifications</h2><p>Choose what arrives in your inbox.</p></div></header>
                        @if($user->canAccessEmailNotifications())
                            <form method="post" action="{{ route('profile.notifications.update') }}">@csrf @method('patch')
                                @foreach([
                                    'email_notifications_enabled' => ['Email notifications', 'Turn automated email notifications on or off.'],
                                    'notify_goal_reminders' => ['Goal reminders', 'Receive reminders about your career goals.'],
                                    'notify_interview_reminders' => ['Interview reminders', 'Receive reminders before scheduled interviews.'],
                                    'notify_goal_achieved' => ['Goal achievements', 'Hear when you reach a career goal.'],
                                ] as $name => [$label, $help])
                                    <label class="profile-preference-row" for="profile-{{ $name }}"><span><strong>{{ $label }}</strong><small>{{ $help }}</small></span><input id="profile-{{ $name }}" name="{{ $name }}" type="checkbox" value="1" class="profile-switch" @checked(old($name, $user->{$name}))></label>
                                @endforeach
                                <div class="profile-form-footer"><span>You can change these anytime.</span><button type="submit" class="profile-primary">Save preferences</button></div>
                            </form>
                        @else
                            <div class="profile-locked"><i class="ph ph-lock" aria-hidden="true"></i><h3>Email notifications are a Premium feature</h3><p>Upgrade to manage goal and interview reminders.</p><a href="{{ route('payment.premium') }}" wire:navigate class="profile-primary">Explore Premium</a></div>
                        @endif
                    </section>
                    @endunless
                    <section id="profile-panel-activity" role="tabpanel" aria-labelledby="profile-tab-activity" x-show="tab === 'activity'" @if($initialTab !== 'activity') x-cloak @endif class="profile-panel">
                        <header class="profile-section-header"><div><h2>Recent account activity</h2><p>Your ten most recent account events.</p></div></header>
                        @forelse($activities as $activity)
                            <div class="profile-activity-item"><span class="profile-activity-icon"><i class="ph ph-{{ $activity->status === 'success' ? 'check' : 'info' }}" aria-hidden="true"></i></span><div><strong>{{ ucwords(str_replace('_', ' ', $activity->activity_type)) }}</strong><p>{{ $activity->description }}</p><span>{{ $activity->ip_address ?: 'System event' }}</span></div><time datetime="{{ $activity->created_at->toIso8601String() }}">{{ $activity->created_at->timezone('Asia/Jakarta')->format('d M, H:i') }}</time></div>
                        @empty<p class="profile-empty">No account activity recorded yet.</p>@endforelse
                    </section>
                </div>
            </div>
        </div>
    </div>
    @push('modals')
        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" maxWidth="md" label="Delete account">
            <div class="profile-delete-dialog"><h2>Delete your account?</h2><p>Your account and its associated data will be permanently removed. Enter your password to confirm.</p><form action="{{ route('profile.destroy') }}" method="post">@csrf @method('delete')<div class="profile-field"><label for="profile-delete-password">Current password</label><input type="password" id="profile-delete-password" name="password" autocomplete="current-password" required>@error('password', 'userDeletion')<p class="profile-field-error">{{ $message }}</p>@enderror</div><div class="profile-form-footer"><button type="button" @click="$dispatch('close')" class="profile-secondary">Cancel</button><button type="submit" class="profile-danger-button">Delete account</button></div></form></div>
        </x-modal>
    @endpush
</x-dynamic-component>
