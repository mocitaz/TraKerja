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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="workspace-shell admin-workspace">
    <a href="#admin-main" class="admin-skip-link">Skip to content</a>
    <header class="workspace-navigation admin-navigation" x-data="{ profileOpen: false, mobileOpen: false }">
        <nav class="workspace-topbar" aria-label="Admin navigation">
            <a href="{{ route('admin.index') }}" class="workspace-brand admin-brand" aria-label="TraKerja admin home">
                <img src="{{ asset('images/icon.png') }}" alt="" width="21" height="21">
                <span class="admin-brand-title">TraKerja</span>
                <span class="admin-brand-divider" aria-hidden="true">|</span>
                <span class="admin-brand-text">Admin</span>
            </a>
            <div class="workspace-desktop-links">
                @foreach($mainLinks as [$route, $label])
                    <a href="{{ route($route) }}" class="workspace-nav-link" @if(request()->routeIs($route)) aria-current="page" @endif>{{ $label }}</a>
                @endforeach
            </div>
            <div class="workspace-account-actions">
                <button type="button" class="workspace-mobile-toggle" x-ref="mobileButton" @click="mobileOpen = !mobileOpen; profileOpen = false" :aria-expanded="mobileOpen.toString()" aria-controls="admin-mobile-links"><i class="ph ph-list" aria-hidden="true"></i><span>Menu</span></button>
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
                        <div class="admin-profile-footer"><a href="{{ route('dashboard') }}" class="workspace-menu-link"><i class="ph ph-arrow-square-out" aria-hidden="true"></i><span>User workspace</span></a><a href="{{ route('profile.edit') }}" class="workspace-menu-link"><i class="ph ph-user-circle" aria-hidden="true"></i><span>Profile settings</span></a><button type="button" class="workspace-menu-link admin-signout" @click="profileOpen = false" onclick="openLogoutModal()"><i class="ph ph-sign-out" aria-hidden="true"></i><span>Sign out</span></button></div>
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
        <div id="logoutModal" data-dialog-native="closeLogoutModal" role="dialog" aria-modal="true" aria-label="Confirm logout" tabindex="-1" class="fixed inset-0 bg-zinc-950/20 backdrop-blur-[1px] hidden z-[100] flex items-center justify-center p-4">
            <div class="bg-white rounded-xl max-w-xs w-full transform transition-all duration-150 scale-98 border border-zinc-200 shadow-md" id="logoutModalContent">
                <div class="p-5 text-center">
                    <h3 class="text-sm font-bold text-zinc-900">Sign Out</h3>
                    <p class="text-xs text-zinc-500 mt-1 leading-relaxed">Yakin anda mau keluar dari akun?</p>
                    
                    <div class="mt-5 flex gap-2">
                        <a href="{{ route('logout.force') }}" onclick="prepareLogout()"
                           class="flex-1 h-8 flex items-center justify-center bg-red-650 hover:bg-red-700 text-white rounded-lg text-xs font-semibold transition-colors focus:outline-none shadow-none text-center">
                            Ya, Logout
                        </a>
                        <button type="button" onclick="closeLogoutModal()" 
                                class="flex-1 h-8 flex items-center justify-center bg-white border border-zinc-250 text-zinc-650 hover:bg-zinc-50 rounded-lg text-xs font-semibold transition-colors focus:outline-none shadow-none">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Logout Modal Helpers
            function openLogoutModal() {
                const modal = document.getElementById('logoutModal');
                const modalContent = document.getElementById('logoutModalContent');
                
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
                
                setTimeout(() => {
                    modalContent.classList.remove('scale-98');
                    modalContent.classList.add('scale-100');
                }, 10);
            }

            function closeLogoutModal() {
                const modal = document.getElementById('logoutModal');
                const modalContent = document.getElementById('logoutModalContent');
                
                modalContent.classList.remove('scale-100');
                modalContent.classList.add('scale-98');
                
                setTimeout(() => {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }, 150);
            }

            document.getElementById('logoutModal')?.addEventListener('click', function(e) {
                if (e.target.id === 'logoutModal') {
                    closeLogoutModal();
                }
            });

            function prepareLogout() {
                try { closeLogoutModal(); } catch (_) {}
                const originalConfirm = window.confirm;
                window.confirm = function() { return false; };
                setTimeout(function() { window.confirm = originalConfirm; }, 8000);
            }
        </script>
        
    @include('components.toast-notification')
    @livewireScripts
</body>
</html>
