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
            $errors->has('resume_file'), in_array(session('status'), ['resume-uploaded', 'resume-updated', 'resume-primary-updated', 'resume-deleted']) => 'resumes',
            request('tab') === 'resumes' => 'resumes',
            request('tab') === 'membership' => 'membership',
            default => 'about',
        };
        $statusMessages = [
            'profile-updated' => 'Account details saved.', 'personal-info-updated' => 'Profile details saved.',
            'avatar-updated' => 'Your new avatar is ready.', 'photo-updated' => 'Profile photo updated.',
            'photo-removed' => 'Photo removed. Your avatar is back.', 'password-updated' => 'Password updated. API tokens have been revoked.', 'token-revoked' => 'Device access revoked.', 'tokens-revoked' => 'All API device access revoked.',
            'notifications-updated' => 'Notification preferences saved.', 'verification-link-sent' => 'Verification email sent.',
            'resume-uploaded' => 'Resume uploaded to your vault.',
            'resume-updated' => 'Resume title updated.',
            'resume-primary-updated' => 'Primary resume updated.',
            'resume-deleted' => 'Resume removed from your vault.',
        ];
        $tabs = $isAdmin
            ? ['about' => 'Account', 'security' => 'Security', 'activity' => 'Activity']
            : ['about' => 'Account', 'resumes' => 'My Resumes', 'membership' => 'Membership & Credits', 'security' => 'Security', 'notifications' => 'Notifications', 'activity' => 'Activity'];
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
                <div><h1>{{ $isAdmin ? 'Admin profile' : 'Profile settings' }}</h1><p>{{ $isAdmin ? 'Manage your identity, profile image, and account security.' : 'Manage your profile, preferences, and account security.' }}</p></div>
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
                    <span class="profile-plan {{ $user->isAdmin() ? 'profile-plan--admin' : ($user->isPremium() ? 'profile-plan--premium' : 'profile-plan--free') }}"><i class="ph {{ $user->isAdmin() ? 'ph-shield-check' : ($user->isPremium() ? 'ph-crown' : 'ph-user') }}" aria-hidden="true"></i>{{ $user->isAdmin() ? 'Administrator' : ($user->isPremium() ? 'Premium member' : 'Free account') }}</span>
                    <button type="button" @click="tab = 'about'; $nextTick(() => { $refs.avatarSection.scrollIntoView({ block: 'start', behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' }); $refs.avatarSection.focus({ preventScroll: true }); })" class="profile-secondary"><i class="ph ph-palette" aria-hidden="true"></i>Change your look</button>
                    <dl class="profile-account-facts">
                        <div><dt>{{ $isAdmin ? 'Account created' : 'Member since' }}</dt><dd>{{ $user->created_at->format('M Y') }}</dd></div>
                        <div><dt>Email status</dt><dd>{{ $user->hasVerifiedEmail() ? 'Verified' : 'Not verified' }}</dd></div>
                        @if(!$isAdmin && isset($resumes))<div><dt>Saved resumes</dt><dd>{{ $resumes->count() }} {{ \Illuminate\Support\Str::plural('file', $resumes->count()) }}</dd></div>@endif
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
                                <label class="profile-preference-row" for="profile-auto-archive"><span><strong>Auto-archive rejected applications</strong><small>Move older rejected or unprocessed applications out of your active tracker after 14 days.</small></span><input class="profile-switch" id="profile-auto-archive" role="switch" name="auto_archive_rejected" type="checkbox" value="1" @checked(old('auto_archive_rejected', $user->auto_archive_rejected))></label>
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
                    @unless($isAdmin)
                    <div id="profile-panel-resumes" role="tabpanel" aria-labelledby="profile-tab-resumes" x-show="tab === 'resumes'" @if($initialTab !== 'resumes') x-cloak @endif class="profile-panel-stack">
                        <!-- Upload Resume Section -->
                        <section class="profile-panel">
                            <header class="profile-section-header">
                                <div>
                                    <h2>Save a resume</h2>
                                    <p>Store different versions of your CV (Tech, Corporate, English, or archives). PDF, DOC, or DOCX up to 10 MB.</p>
                                </div>
                            </header>

                            <form method="post" action="{{ route('user-resumes.store') }}" enctype="multipart/form-data" x-data="{ selectedFile: null }">
                                @csrf
                                <div class="profile-field-grid">
                                    <div class="profile-field">
                                        <label for="resume-title">Resume label or target role <small class="text-zinc-400 font-normal">(Optional)</small></label>
                                        <input type="text" id="resume-title" name="title" class="profile-input" placeholder="e.g. CV Frontend Engineer (English), CV BUMN" maxlength="150" value="{{ old('title') }}">
                                        <span class="profile-field-hint">Helps you distinguish between different versions or dates.</span>
                                    </div>
                                    <div class="profile-field">
                                        <label for="resume-file-input">Choose file <span class="text-rose-500">*</span></label>
                                        <label for="resume-file-input" class="profile-file-input">
                                            <i class="ph ph-upload-simple" aria-hidden="true"></i>
                                            <span x-text="selectedFile ? selectedFile : 'Select PDF, DOC, or DOCX file'">Select PDF, DOC, or DOCX file</span>
                                            <input id="resume-file-input" type="file" name="resume_file" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required @change="selectedFile = $event.target.files[0]?.name || null">
                                        </label>
                                        <span class="profile-field-hint">PDF, DOC, or DOCX up to 10 MB.</span>
                                        @error('resume_file')
                                            <p class="profile-field-error">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label class="profile-preference-row" for="resume-make-primary">
                                        <span>
                                            <strong>Set as primary resume</strong>
                                            <small>Mark this file as your default/go-to resume.</small>
                                        </span>
                                        <input class="profile-switch" id="resume-make-primary" role="switch" name="is_primary" type="checkbox" value="1" @checked(old('is_primary', isset($resumes) && $resumes->isEmpty()))>
                                    </label>
                                </div>

                                <div class="profile-form-footer">
                                    <span>Files are stored privately in your personal vault.</span>
                                    <button type="submit" class="profile-primary">
                                        <i class="ph ph-cloud-arrow-up text-base mr-1" aria-hidden="true"></i>
                                        <span>Upload to vault</span>
                                    </button>
                                </div>
                            </form>
                        </section>

                        <!-- Saved Resumes List Section -->
                        <section class="profile-panel">
                            <header class="profile-section-header">
                                <div>
                                    <h2>Your saved resumes ({{ isset($resumes) ? $resumes->count() : 0 }})</h2>
                                    <p>Your library of uploaded CV files. Preview, download, rename, or set your primary version.</p>
                                </div>
                            </header>

                            @if(!isset($resumes) || $resumes->isEmpty())
                                <div class="profile-empty py-10 text-center flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 rounded-2xl bg-zinc-100 dark:bg-[#282a2c] border border-transparent dark:border-white/10 flex items-center justify-center text-zinc-400 dark:text-[#94a3b8] mb-3">
                                        <i class="ph ph-files text-2xl" aria-hidden="true"></i>
                                    </div>
                                    <p class="font-semibold text-zinc-700 dark:text-[#f0f4f9] text-sm mb-1">No resumes saved yet</p>
                                    <p class="text-xs text-zinc-500 dark:text-[#94a3b8] max-w-sm">Use the form above to upload your first CV. You can save multiple versions for different roles or dates.</p>
                                </div>
                            @else
                                <div class="profile-resume-grid">
                                    @foreach($resumes as $resume)
                                        <div class="profile-resume-card {{ $resume->is_primary ? 'is-primary' : '' }}" x-data="{ editing: false, newTitle: '{{ addslashes($resume->title) }}' }">
                                            <div class="profile-resume-meta">
                                                <div class="profile-resume-icon-badge {{ $resume->is_pdf ? 'profile-resume-icon-badge--pdf' : 'profile-resume-icon-badge--doc' }}">
                                                    <span>{{ $resume->extension }}</span>
                                                </div>
                                                <div class="profile-resume-details">
                                                    <div class="profile-resume-title-row">
                                                        <!-- Normal Title Display -->
                                                        <h3 x-show="!editing" class="profile-resume-title">{{ $resume->title }}</h3>

                                                        <!-- Inline Rename Form -->
                                                        <form x-show="editing" x-cloak method="post" action="{{ route('user-resumes.update', $resume) }}" class="flex items-center gap-1.5" @submit="editing = false">
                                                            @csrf
                                                            @method('patch')
                                                            <input type="text" name="title" x-model="newTitle" class="text-xs px-2 py-1 border border-zinc-300 dark:border-white/10 rounded font-semibold text-zinc-800 dark:text-[#f0f4f9] bg-white dark:bg-[#282a2c] focus:outline-none focus:border-zinc-900 dark:focus:border-blue-400" required maxlength="150">
                                                            <button type="submit" class="p-1 text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300" title="Save name"><i class="ph ph-check text-sm"></i></button>
                                                            <button type="button" @click="editing = false; newTitle = '{{ addslashes($resume->title) }}'" class="p-1 text-zinc-400 dark:text-zinc-500 hover:text-zinc-600 dark:hover:text-zinc-300" title="Cancel"><i class="ph ph-x text-sm"></i></button>
                                                        </form>

                                                        @if($resume->is_primary)
                                                            <span class="profile-resume-primary-tag">
                                                                <i class="ph-fill ph-star" aria-hidden="true"></i>
                                                                <span>Primary</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <p class="profile-resume-subtitle">
                                                        <span>{{ $resume->file_name }}</span>
                                                        <span class="profile-resume-dot">•</span>
                                                        <span>{{ $resume->formatted_size }}</span>
                                                        <span class="profile-resume-dot">•</span>
                                                        <span>{{ $resume->created_at->format('d M Y') }}</span>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="profile-resume-actions">
                                                @if($resume->is_pdf)
                                                    <a href="{{ route('user-resumes.preview', $resume) }}" target="_blank" rel="noopener noreferrer" class="profile-icon-action" title="Preview PDF in new tab" aria-label="Preview resume">
                                                        <i class="ph ph-eye" aria-hidden="true"></i>
                                                    </a>
                                                @endif

                                                <a href="{{ route('user-resumes.download', $resume) }}" class="profile-icon-action" title="Download file" aria-label="Download resume">
                                                    <i class="ph ph-download-simple" aria-hidden="true"></i>
                                                </a>

                                                <button type="button" @click="editing = !editing" class="profile-icon-action" title="Rename label" aria-label="Rename resume">
                                                    <i class="ph ph-pencil-simple" aria-hidden="true"></i>
                                                </button>

                                                @unless($resume->is_primary)
                                                    <form method="post" action="{{ route('user-resumes.primary', $resume) }}" class="inline">
                                                        @csrf
                                                        <button type="submit" class="profile-icon-action profile-icon-action--primary-toggle" title="Set as primary resume" aria-label="Set as primary">
                                                            <i class="ph ph-star" aria-hidden="true"></i>
                                                        </button>
                                                    </form>
                                                @endunless

                                                <form method="post" action="{{ route('user-resumes.destroy', $resume) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this resume from your vault?');">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="profile-icon-action profile-icon-action--danger" title="Delete resume" aria-label="Delete resume">
                                                        <i class="ph ph-trash" aria-hidden="true"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </section>
                    </div>
                    @endunless
                    <div id="profile-panel-security" role="tabpanel" aria-labelledby="profile-tab-security" x-show="tab === 'security'" @if($initialTab !== 'security') x-cloak @endif class="profile-panel-stack">
                        <section class="profile-panel"><header class="profile-section-header"><div><h2>Password & security</h2><p>Keep access to your account secure.</p></div></header>@include('profile.partials.update-password-form')</section>
                        <section class="profile-panel">
                            <h2>Connected API devices</h2>
                            <p>Revoke access for Chrome extensions and API clients. This list does not include browser login sessions.</p>
                            @php $apiDevices = $user->tokens()->latest()->limit(50)->get(['id', 'name', 'created_at', 'last_used_at', 'expires_at']); @endphp
                            @forelse($apiDevices as $device)
                                <div class="profile-form-footer">
                                    <div><strong>{{ $device->name }}</strong><p>Last used: {{ $device->last_used_at?->diffForHumans() ?? 'Never' }} · Expires: {{ $device->expires_at?->format('d M Y') ?? 'No expiry' }}</p></div>
                                    <form method="post" action="{{ route('profile.tokens.destroy', $device->id) }}">@csrf @method('delete')<button type="submit" class="profile-secondary">Revoke access</button></form>
                                </div>
                            @empty
                                <p>No connected API devices.</p>
                            @endforelse
                            @if($apiDevices->isNotEmpty())
                                <p>Showing up to 50 recent devices.</p>
                                <form method="post" action="{{ route('profile.tokens.destroy-all') }}">@csrf @method('delete')<button type="submit" class="profile-secondary">Revoke all API devices</button></form>
                            @endif
                        </section>
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
                                    <label class="profile-preference-row" for="profile-{{ $name }}"><span><strong>{{ $label }}</strong><small>{{ $help }}</small></span><input id="profile-{{ $name }}" name="{{ $name }}" type="checkbox" role="switch" value="1" class="profile-switch" @checked(old($name, $user->{$name}))></label>
                                @endforeach
                                <div class="profile-form-footer"><span>You can change these anytime.</span><button type="submit" class="profile-primary">Save preferences</button></div>
                            </form>
                        @else
                            <div class="profile-locked"><i class="ph ph-lock" aria-hidden="true"></i><h3>Email notifications are a Premium feature</h3><p>Upgrade to manage goal and interview reminders.</p><a href="{{ route('payment.premium') }}" wire:navigate class="profile-primary">Explore Premium</a></div>
                        @endif
                    </section>
                    @endunless
                    @unless($isAdmin)
                    <section id="profile-panel-membership" role="tabpanel" aria-labelledby="profile-tab-membership" x-show="tab === 'membership'" @if($initialTab !== 'membership') x-cloak @endif class="profile-panel-stack">
                        @include('profile.partials.membership', $membership)
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
