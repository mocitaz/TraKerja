@php
    $user = Auth::user();
    $mainLinks = [
        ['admin.index', 'Dashboard'], ['admin.users', 'User Management'],
        ['admin.user-activities', 'Activity Log'], ['admin.analytics', 'Analytics'],
    ];
    $profileLinks = [
        ['admin.email-blast', 'Email Blast', 'paper-plane-tilt'],
        ['admin.feedbacks.index', 'User Feedback', 'chat-circle-dots'],
        ['admin.survey.index', 'User Survey', 'clipboard-text'],
        ['admin.scraper', 'Scraper engine', 'globe'],
        ['admin.settings', 'Settings', 'sliders'],
        ['admin.payments', 'Payments', 'credit-card'],
        ['admin.monetization', 'Monetization', 'crown'],
    ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TraKerja — Admin workspace</title>
    <link rel="icon" href="{{ asset('images/icon.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Anti-FOUC & Theme Initialization Script -->
    <script>
        (function() {
            try {
                if (!localStorage.getItem('trakerja_theme_force_light_v6')) {
                    localStorage.setItem('theme', 'light');
                    document.cookie = 'theme=light; path=/; max-age=31536000; SameSite=Lax';
                    localStorage.setItem('trakerja_theme_force_light_v6', '1');
                }
                var theme = localStorage.getItem('theme');
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                    document.cookie = 'theme=dark; path=/; max-age=31536000; SameSite=Lax';
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                    document.cookie = 'theme=light; path=/; max-age=31536000; SameSite=Lax';
                }
            } catch (e) {}
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="workspace-shell admin-workspace bg-[#fafafa] dark:bg-[#131314] text-slate-900 dark:text-[#f0f4f9] transition-colors duration-200">
    <a href="#admin-main" class="admin-skip-link">Skip to content</a>
    <header class="workspace-navigation admin-navigation" x-data="{ profileOpen: false, mobileOpen: false }">
        <nav class="workspace-topbar" aria-label="Admin navigation">
            <a href="{{ route('admin.index') }}" class="workspace-brand admin-brand" aria-label="TraKerja admin home">
                <img src="{{ asset('images/icon.png') }}" alt="" width="21" height="21">
                <span class="admin-brand-title">TraKerja</span>
                <span class="admin-brand-divider" aria-hidden="true">\</span>
                <span class="admin-brand-text">admin</span>
            </a>
            <div class="workspace-desktop-links">
                @foreach($mainLinks as [$route, $label])
                    <a href="{{ route($route) }}" class="workspace-nav-link" @if(request()->routeIs($route)) aria-current="page" @endif>{{ $label }}</a>
                @endforeach
            </div>
            <div class="workspace-account-actions">
                <button type="button" class="workspace-mobile-toggle" x-ref="mobileButton" @click="mobileOpen = !mobileOpen; profileOpen = false" :aria-expanded="mobileOpen.toString()" aria-controls="admin-mobile-links"><i class="ph ph-list" aria-hidden="true"></i><span>Menu</span></button>
                <!-- Theme Toggle Button -->
                <button type="button"
                        x-data="{
                            toggle() {
                                if (typeof window.toggleTheme === 'function') {
                                    window.toggleTheme();
                                } else {
                                    var isDark = document.documentElement.classList.toggle('dark');
                                    var theme = isDark ? 'dark' : 'light';
                                    try { localStorage.setItem('theme', theme); } catch (e) {}
                                    document.cookie = 'theme=' + theme + '; path=/; max-age=31536000; SameSite=Lax';
                                    window.dispatchEvent(new CustomEvent('theme-changed', { detail: { isDark: isDark } }));
                                }
                            }
                        }"
                        @click="toggle()"
                        class="theme-toggle-btn"
                        aria-label="Toggle dark/light mode"
                        title="Toggle dark/light mode">
                    <i class="ph-bold ph-sun theme-icon-sun" aria-hidden="true"></i>
                    <i class="ph-bold ph-moon theme-icon-moon" aria-hidden="true"></i>
                </button>
                <div class="relative" @click.outside="profileOpen = false" @keydown.escape.stop="profileOpen = false; $refs.profileButton.focus()">
                    <button type="button" x-ref="profileButton" @click="profileOpen = !profileOpen; mobileOpen = false" class="workspace-profile-trigger" aria-label="Admin account menu" aria-controls="admin-profile-menu" :aria-expanded="profileOpen.toString()">
                        <img src="{{ $user->avatar_url }}" alt="" class="workspace-avatar">
                        <span class="workspace-account-copy"><strong>{{ $user->name }}</strong><span>{{ $user->email }}</span></span>
                        <i class="ph ph-caret-down workspace-profile-chevron" :class="{ 'is-open': profileOpen }" aria-hidden="true"></i>
                    </button>
                    <div id="admin-profile-menu" class="workspace-dropdown workspace-profile-menu admin-profile-menu" x-cloak x-show="profileOpen" x-transition.opacity.duration.150ms role="region" aria-label="Admin account and tools">
                        <div class="workspace-profile-summary"><img src="{{ $user->avatar_url }}" alt="" class="workspace-profile-avatar"><div class="workspace-profile-identity"><div class="workspace-profile-identity-heading"><strong>{{ $user->name }}</strong><span class="workspace-account-badge">Admin</span></div><span>{{ $user->email }}</span></div></div>
                        <div class="workspace-profile-links">
                            @foreach($profileLinks as [$route, $label, $icon])
                                <a href="{{ route($route) }}" class="workspace-menu-link" @if(request()->routeIs($route)) aria-current="page" @endif><span class="workspace-menu-icon"><i class="ph ph-{{ $icon }}" aria-hidden="true"></i></span><span>{{ $label }}</span><i class="ph ph-caret-right workspace-menu-chevron" aria-hidden="true"></i></a>
                            @endforeach
                        </div>
                        <div class="admin-profile-footer"><a href="{{ route('dashboard') }}" class="workspace-menu-link"><i class="ph ph-arrow-square-out" aria-hidden="true"></i><span>User workspace</span></a><a href="{{ route('profile.edit') }}" class="workspace-menu-link"><i class="ph ph-user-circle" aria-hidden="true"></i><span>Profile settings</span></a><form method="POST" action="{{ route('logout') }}" id="admin-logout-form" class="hidden">@csrf</form><button type="button" class="workspace-menu-link admin-signout" @click="profileOpen = false" onclick="confirmLogout('admin-logout-form')"><i class="ph ph-sign-out" aria-hidden="true"></i><span>Sign out</span></button></div>
                    </div>
                </div>
            </div>
        </nav>
        <nav id="admin-mobile-links" class="workspace-mobile-menu" x-cloak x-show="mobileOpen" @click.outside="mobileOpen = false" @keydown.escape.stop="mobileOpen = false; $refs.mobileButton.focus()" aria-label="Mobile admin navigation">
            @foreach($mainLinks as [$route, $label])<a href="{{ route($route) }}" class="workspace-menu-link" @if(request()->routeIs($route)) aria-current="page" @endif>{{ $label }}</a>@endforeach
        </nav>
    </header>
    <main id="admin-main" class="admin-page-frame" tabindex="-1">
        @if(session('success'))<div class="admin-session-note" role="status">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="admin-session-note admin-session-note--error" role="alert">{{ session('error') }}</div>@endif
        {{ $slot }}
    </main>
    <x-workspace-footer />
    @stack('scripts')
    <div id="confirmation-modal" data-dialog-native="closeConfirmModal" role="dialog" aria-modal="true" aria-labelledby="confirm-title" aria-describedby="confirm-message" tabindex="-1" class="account-dialog-overlay hidden" onclick="if (event.target === this) closeConfirmModal()">
        <section class="account-confirm-panel">
            <button type="button" class="account-dialog-close" onclick="closeConfirmModal()" aria-label="Close confirmation"><i class="ph ph-x" aria-hidden="true"></i></button>
            <div class="account-confirm-visual" aria-hidden="true"><i class="ph ph-sign-out account-confirm-logout-icon"></i><i class="ph ph-warning account-confirm-action-icon"></i></div>
            <h2 id="confirm-title">Confirm action</h2>
            <p id="confirm-message">Review this action before continuing.</p>
            <p id="confirm-error" role="alert" hidden></p>
            <div class="account-confirm-actions">
                <button type="button" id="confirm-cancel" class="account-dialog-button" onclick="closeConfirmModal()">Stay here</button>
                <button type="button" id="confirm-button" class="account-dialog-button account-dialog-button--primary">Confirm</button>
            </div>
        </section>
    </div>

    <script>
        var activeConfirmCallback = activeConfirmCallback || null;
        var confirmBusy = false;

        function openConfirmModal(title, message, btnText, callback, options = {}) {
            if (confirmBusy) return;
            const modal = document.getElementById('confirmation-modal');
            if (!modal) return;
            modal.dataset.variant = options.variant || 'action';
            document.getElementById('confirm-title').innerText = title;
            document.getElementById('confirm-message').innerText = message;
            document.getElementById('confirm-button').innerText = btnText;
            document.getElementById('confirm-cancel').innerText = options.variant === 'logout' ? 'Stay here' : 'Cancel';
            document.getElementById('confirm-error').hidden = true;
            activeConfirmCallback = callback;
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeConfirmModal(force = false) {
            if (confirmBusy && !force) return;
            const modal = document.getElementById('confirmation-modal');
            if (modal) modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            activeConfirmCallback = null;
        }

        document.getElementById('confirm-button').onclick = async function() {
            if (confirmBusy || !activeConfirmCallback) return;
            const callback = activeConfirmCallback;
            const modal = document.getElementById('confirmation-modal');
            const buttons = [...modal.querySelectorAll('button')];
            const label = this.innerText;
            confirmBusy = true;
            buttons.forEach(button => { button.disabled = true; });
            this.innerText = modal.dataset.variant === 'logout' ? 'Signing out…' : 'Working…';
            try {
                await callback();
                closeConfirmModal(true);
            } catch (error) {
                const notice = document.getElementById('confirm-error');
                notice.innerText = 'We couldn’t complete this action. Please try again.';
                notice.hidden = false;
            } finally {
                confirmBusy = false;
                buttons.forEach(button => { button.disabled = false; });
                this.innerText = label;
            }
        };

        async function confirmLogout(formId = 'admin-logout-form') {
            const submitLogout = async () => {
                const form = document.getElementById(formId) || document.getElementById('admin-logout-form');
                if (!form) return;
                try {
                    await window.prepareTraKerjaLogout?.();
                } catch (error) {
                    console.error(error);
                }
                form.submit();
            };
            if (typeof window.openConfirmModal === 'function') {
                window.openConfirmModal(
                    'Sign out of TraKerja?',
                    'You’ll need to sign in again to return to your workspace.',
                    'Sign out',
                    submitLogout,
                    { variant: 'logout' }
                );
            } else if (confirm('Are you sure you want to sign out?')) {
                await submitLogout();
            }
        }

        function openLogoutModal() {
            confirmLogout('admin-logout-form');
        }
        function closeLogoutModal() {
            closeConfirmModal();
        }
    </script>
        
    @include('components.toast-notification')
    @livewireScripts
</body>
</html>
