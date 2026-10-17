@php
    $navigationLinks = [
        ['route' => 'dashboard', 'match' => 'dashboard', 'label' => __('Home')],
        ['route' => 'tracker', 'match' => ['tracker', 'jobs.show'], 'label' => __('Applications')],
        ['route' => 'jobs.explore', 'match' => 'jobs.explore', 'label' => __('Explore Jobs')],
        ['route' => 'summary', 'match' => 'summary', 'label' => __('Summary')],
    ];
    $user = Auth::user();
@endphp
<header id="workspace-navigation" class="workspace-navigation" x-data="{ profileOpen: false, mobileOpen: false }">
    <nav class="workspace-topbar" aria-label="{{ __('Main navigation') }}">
        <a href="{{ route('dashboard') }}" wire:navigate class="workspace-brand" aria-label="{{ __('TraKerja home') }}">
            <img src="{{ asset('images/icon.png') }}" alt="" width="28" height="28"><span>{{ __('TraKerja') }}</span>
        </a>
        <div class="workspace-desktop-links">
            @foreach($navigationLinks as $link)
                <a href="{{ route($link['route']) }}" wire:navigate @if(request()->routeIs(...(array) $link['match'])) aria-current="page" @endif class="workspace-nav-link">{{ __($link['label']) }}</a>
            @endforeach
        </div>
        <div class="workspace-account-actions">
            <button type="button" x-ref="mobileButton" @click="mobileOpen = !mobileOpen; profileOpen = false" aria-controls="workspace-mobile-menu" aria-expanded="false" :aria-expanded="mobileOpen.toString()" class="workspace-mobile-toggle">
                <i class="ph" :class="mobileOpen ? 'ph-x' : 'ph-list'" aria-hidden="true"></i><span>{{ __('Menu') }}</span>
            </button>
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
                    aria-label="{{ __('Toggle dark/light mode') }}"
                    title="{{ __('Toggle dark/light mode') }}">
                <i class="ph-bold ph-sun theme-icon-sun" aria-hidden="true"></i>
                <i class="ph-bold ph-moon theme-icon-moon" aria-hidden="true"></i>
            </button>
            <div class="relative" @click.outside="profileOpen = false" @keydown.escape.stop="profileOpen = false; $refs.profileButton.focus()">
                <button type="button" x-ref="profileButton" @click="profileOpen = !profileOpen; mobileOpen = false" aria-label="{{ __('Account menu for :value1', ['value1' => ($user->name)]) }}" aria-controls="workspace-profile" aria-expanded="false" :aria-expanded="profileOpen.toString()" class="workspace-profile-trigger">
                    <img src="{{ $user->avatar_url }}" alt="" class="workspace-avatar">
                    <span class="workspace-account-copy"><strong>{{ $user->name }}</strong><span>{{ $user->email }}</span></span>
                    <i class="ph ph-caret-down workspace-profile-chevron" :class="{ 'is-open': profileOpen }" aria-hidden="true"></i>
                </button>
                <div id="workspace-profile" x-cloak x-show="profileOpen" x-transition:enter="workspace-profile-transition" x-transition:enter-start="workspace-profile-hidden" x-transition:enter-end="workspace-profile-visible" x-transition:leave="workspace-profile-transition" x-transition:leave-start="workspace-profile-visible" x-transition:leave-end="workspace-profile-hidden" class="workspace-dropdown workspace-profile-menu" role="region" aria-label="{{ __('Account settings') }}">
                    <div class="workspace-profile-summary">
                        <img src="{{ $user->avatar_url }}" alt="" class="workspace-profile-avatar">
                        <div class="workspace-profile-identity"><div class="workspace-profile-identity-heading"><strong>{{ $user->name }}</strong>@if($user->isAdmin())
                            <span class="workspace-account-badge"><i class="ph ph-shield-check" aria-hidden="true"></i>{{ __('Admin') }}</span>
                        @else
                            <a href="{{ route('profile.edit', ['tab' => 'membership']) }}" wire:navigate @click="profileOpen = false" class="workspace-account-badge" aria-label="{{ __('Membership & credits') }}"><i class="ph {{ $user->isPremium() ? 'ph-crown' : 'ph-user' }}" aria-hidden="true"></i>{{ $user->isPremium() ? __('Premium') : __('Free') }}</a>
                        @endif</div><span>{{ $user->email }}</span></div>
                    </div>
                    <div class="workspace-rewards"><livewire:layout.gamification-widget /></div>
                    <x-language-switch location="account-menu" />
                    <div class="workspace-profile-links">
                    <a href="{{ route('profile.edit') }}" wire:navigate @click="profileOpen = false" class="workspace-menu-link"><span class="workspace-menu-icon"><i class="ph ph-user-circle" aria-hidden="true"></i></span><span>{{ __('Profile settings') }}</span><i class="ph ph-caret-right workspace-menu-chevron" aria-hidden="true"></i></a>
                    <a href="{{ route('automation.index') }}" wire:navigate @click="profileOpen = false" class="workspace-menu-link"><span class="workspace-menu-icon"><i class="ph ph-sliders" aria-hidden="true"></i></span><span>{{ __('Automation & CSV') }}</span><i class="ph ph-caret-right workspace-menu-chevron" aria-hidden="true"></i></a>
                    <a href="{{ route('support.index') }}" wire:navigate @click="profileOpen = false" class="workspace-menu-link"><span class="workspace-menu-icon"><i class="ph ph-headset" aria-hidden="true"></i></span><span>{{ __('Support') }}</span><i class="ph ph-caret-right workspace-menu-chevron" aria-hidden="true"></i></a>
                    @if($user->isAdmin())
                        <a href="{{ route('admin.index') }}" wire:navigate @click="profileOpen = false" class="workspace-menu-link"><span class="workspace-menu-icon"><i class="ph ph-shield-check" aria-hidden="true"></i></span><span>{{ __('Admin panel') }}</span><i class="ph ph-caret-right workspace-menu-chevron" aria-hidden="true"></i></a>
                    @endif
                    </div>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form" class="workspace-logout">
                        @csrf
                        <button type="button" onclick="confirmLogout('logout-form')" class="workspace-menu-link"><span class="workspace-menu-icon"><i class="ph ph-sign-out" aria-hidden="true"></i></span><span>{{ __('Sign out') }}</span></button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    <nav id="workspace-mobile-menu" x-cloak x-show="mobileOpen" x-transition.opacity.duration.150ms class="workspace-mobile-menu" aria-label="{{ __('Mobile navigation') }}" @click.outside="mobileOpen = false" @keydown.escape.stop="mobileOpen = false; $refs.mobileButton.focus()">
        @foreach($navigationLinks as $link)
            <a href="{{ route($link['route']) }}" wire:navigate @click="mobileOpen = false" @if(request()->routeIs(...(array) $link['match'])) aria-current="page" @endif class="workspace-menu-link">{{ __($link['label']) }}</a>
        @endforeach
    </nav>
</header>
<nav class="workspace-bottom-navigation" aria-label="{{ __('Mobile primary navigation') }}">
    @foreach($navigationLinks as $link)
        @php $isCurrent = request()->routeIs(...(array) $link['match']); @endphp
        <a href="{{ route($link['route']) }}" wire:navigate @if($isCurrent) aria-current="page" @endif>
            <i class="{{ $isCurrent ? 'ph-fill' : 'ph' }} {{ ['dashboard'=>'ph-house', 'tracker'=>'ph-briefcase', 'jobs.explore'=>'ph-magnifying-glass', 'summary'=>'ph-chart-pie-slice'][$link['route']] }}" aria-hidden="true"></i>
            <span>{{ __($link['label']) }}</span>
        </a>
    @endforeach
</nav>
