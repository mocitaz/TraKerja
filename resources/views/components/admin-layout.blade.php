@php($user = Auth::user())
@php($sidebarName = \Illuminate\Support\Str::before(trim($user->name), ' '))
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TraKerja | Admin</title>
    <link rel="icon" href="{{ asset('images/icon.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        try { document.documentElement.classList.toggle('dark', localStorage.getItem('theme') === 'dark'); } catch (e) {}
        try { document.documentElement.classList.toggle('admin-sidebar-collapsed', localStorage.getItem('admin_sidebar_collapsed') === 'true'); } catch (e) {}
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @include('partials.locale-config')
</head>
<body class="workspace-shell admin-workspace admin-console" x-data="{ sidebarOpen: false, menuReturnFocus: null, sidebarCollapsed: document.documentElement.classList.contains('admin-sidebar-collapsed'), toggleSidebar() { this.sidebarCollapsed = !this.sidebarCollapsed; try { localStorage.setItem('admin_sidebar_collapsed', String(this.sidebarCollapsed)) } catch (e) {} } }" @keydown.escape.window="if (sidebarOpen) { sidebarOpen = false; (menuReturnFocus || $refs.menuButton).focus({ preventScroll: true }) }" @resize.window.debounce.100ms="if (window.innerWidth >= 1100) sidebarOpen = false" x-effect="document.body.classList.toggle('admin-menu-open', sidebarOpen); document.documentElement.classList.toggle('admin-sidebar-collapsed', sidebarCollapsed)">
    <a href="#admin-main" class="admin-skip-link">{{ __('Skip to content') }}</a>
    <div class="ac-mobile-heading">
        <a href="{{ route('admin.index') }}" class="ac-mobile-brand workspace-brand"><img src="{{ asset('images/icon.png') }}" alt="" width="23" height="23">TraKerja <span>| {{ __('Admin') }}</span></a>
        <button type="button" class="ac-menu-button" x-ref="menuButton" @click="menuReturnFocus = $el; sidebarOpen = !sidebarOpen; if (sidebarOpen) $nextTick(() => requestAnimationFrame(() => $refs.closeMenu.focus({ preventScroll: true })))" :aria-expanded="sidebarOpen.toString()" aria-controls="admin-sidebar"><i class="ph ph-list" aria-hidden="true"></i>{{ __('Menu') }}</button>
    </div>
    <button class="ac-backdrop" type="button" x-cloak x-show="sidebarOpen" @click="sidebarOpen = false; (menuReturnFocus || $refs.menuButton).focus({ preventScroll: true })" aria-label="{{ __('Close menu') }}"></button>
    <aside id="admin-sidebar" class="ac-sidebar" :role="sidebarOpen ? 'dialog' : 'complementary'" :aria-modal="sidebarOpen ? 'true' : null" aria-label="{{ __('Admin navigation') }}" :class="{ 'is-open': sidebarOpen }" @keydown.tab="if (window.innerWidth < 1100 && sidebarOpen) { const controls = [...$el.querySelectorAll('a, button, input')].filter(el => el.getClientRects().length && !el.disabled); const first = controls[0]; const last = controls[controls.length - 1]; if ($event.shiftKey && document.activeElement === first) { $event.preventDefault(); last.focus() } else if (!$event.shiftKey && document.activeElement === last) { $event.preventDefault(); first.focus() } }">
        <button type="button" class="ac-sidebar-toggle" @click="toggleSidebar()" :aria-expanded="(!sidebarCollapsed).toString()" aria-controls="admin-sidebar" :aria-label="sidebarCollapsed ? @js(__('Expand sidebar')) : @js(__('Collapse sidebar'))" :title="sidebarCollapsed ? @js(__('Expand sidebar')) : @js(__('Collapse sidebar'))"><i class="ph-bold ph-caret-left" aria-hidden="true"></i></button>
        <div class="ac-sidebar-brand">
            <a href="{{ route('admin.index') }}" class="workspace-brand" aria-label="{{ __('TraKerja admin home') }}"><img src="{{ asset('images/icon.png') }}" alt="" width="21" height="21"><span>{{ __('TraKerja') }}<small class="ac-brand-context">| {{ __('Admin') }}</small></span></a>
            <button type="button" x-ref="closeMenu" class="ac-close-menu" @click="sidebarOpen = false; (menuReturnFocus || $refs.menuButton).focus({ preventScroll: true })" aria-label="{{ __('Close menu') }}"><i class="ph ph-x" aria-hidden="true"></i></button>
        </div>
        @include('layouts.admin-sidebar')
        <div class="ac-sidebar-account">
            <a href="{{ route('profile.edit') }}" class="ac-account" aria-label="{{ __('Profile settings') }}" title="{{ $user->name }} · {{ __('Profile settings') }}" @if(request()->routeIs('profile.edit')) aria-current="page" @endif><img src="{{ $user->avatar_url }}" alt="" width="38" height="38"><span><strong>{{ $sidebarName }}</strong></span></a>
            <form method="POST" action="{{ route('logout') }}" id="admin-logout-form">@csrf<button type="button" class="ac-signout" onclick="confirmLogout('admin-logout-form')" aria-label="{{ __('Sign out') }}" title="{{ __('Sign out') }}"><i class="ph-fill ph-sign-out" aria-hidden="true"></i><span>{{ __('Sign out') }}</span></button></form>
        </div>
    </aside>
    <div class="ac-main-column">
        <main id="admin-main" class="admin-page-frame" tabindex="-1">
            @if(session('success'))<div class="admin-session-note" role="status">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="admin-session-note admin-session-note--error" role="alert">{{ session('error') }}</div>@endif
            {{ $slot }}
        </main>
        <footer class="ac-footer"><span>© {{ now()->year }} TraKerja</span><span>{{ __('Administration') }}<span aria-hidden="true"> / </span><a href="{{ route('legal.privacy') }}">{{ __('Privacy policy') }}</a></span></footer>
    </div>
    <nav class="ac-mobile-dock" aria-label="{{ __('Primary admin navigation') }}">
        <a href="{{ route('admin.index') }}" @if(request()->routeIs('admin.index')) aria-current="page" @endif><i class="ph-fill ph-squares-four" aria-hidden="true"></i><span>{{ __('Dashboard') }}</span></a>
        <a href="{{ route('admin.users') }}" @if(request()->routeIs('admin.users*')) aria-current="page" @endif><i class="ph-fill ph-users" aria-hidden="true"></i><span>{{ __('Users') }}</span></a>
        <a href="{{ route('admin.payments') }}" @if(request()->routeIs('admin.payments*')) aria-current="page" @endif><i class="ph-fill ph-credit-card" aria-hidden="true"></i><span>{{ __('Payments') }}</span></a>
        <button type="button" @click="menuReturnFocus = $el; sidebarOpen = !sidebarOpen; if (sidebarOpen) $nextTick(() => requestAnimationFrame(() => $refs.closeMenu.focus({ preventScroll: true })))" :aria-expanded="sidebarOpen.toString()" aria-controls="admin-sidebar"><i class="ph ph-list" aria-hidden="true"></i><span>{{ __('Menu') }}</span></button>
    </nav>
    @stack('scripts')
    <div id="confirmation-modal" data-dialog-native="closeConfirmModal" role="dialog" aria-modal="true" aria-labelledby="confirm-title" aria-describedby="confirm-message" tabindex="-1" class="account-dialog-overlay hidden" onclick="if (event.target === this) closeConfirmModal()">
        <section class="account-confirm-panel">
            <button type="button" class="account-dialog-close" onclick="closeConfirmModal()" aria-label="{{ __('Close confirmation') }}"><i class="ph ph-x" aria-hidden="true"></i></button>
            <div class="account-confirm-visual" aria-hidden="true"><i class="ph ph-sign-out account-confirm-logout-icon"></i><i class="ph ph-warning account-confirm-action-icon"></i></div>
            <h2 id="confirm-title">{{ __('Confirm action') }}</h2>
            <p id="confirm-message">{{ __('Review this action before continuing.') }}</p>
            <p id="confirm-error" role="alert" hidden></p>
            <div class="account-confirm-actions">
                <button type="button" id="confirm-cancel" class="account-dialog-button" onclick="closeConfirmModal()">{{ __('Stay here') }}</button>
                <button type="button" id="confirm-button" class="account-dialog-button account-dialog-button--primary">{{ __('Confirm') }}</button>
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
            document.getElementById('confirmation-modal')?.classList.add('hidden');
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
            try { await callback(); closeConfirmModal(true); }
            catch (error) {
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
            openConfirmModal(@js(__('Sign out of TraKerja?')), @js(__('You’ll need to sign in again to return to your workspace.')), @js(__('Sign out')), async () => {
                const form = document.getElementById(formId) || document.getElementById('admin-logout-form');
                if (!form) return;
                try { await window.prepareTraKerjaLogout?.(); } catch (error) { console.error(error); }
                form.submit();
            }, { variant: 'logout' });
        }
        function openLogoutModal() { confirmLogout('admin-logout-form'); }
        function closeLogoutModal() { closeConfirmModal(); }
    </script>
    @include('components.toast-notification')
    @livewireScripts
</body>
</html>
