@php
    $navigationLinks = [
        ['route' => 'dashboard', 'match' => 'dashboard', 'label' => 'Home'],
        ['route' => 'tracker', 'match' => ['tracker', 'jobs.show'], 'label' => 'Applications'],
        ['route' => 'jobs.explore', 'match' => 'jobs.explore', 'label' => 'Explore Jobs'],
        ['route' => 'summary', 'match' => 'summary', 'label' => 'Summary'],
    ];
    $user = Auth::user();
@endphp
<header id="workspace-navigation" class="workspace-navigation" x-data="{ profileOpen: false, mobileOpen: false }">
    <nav class="workspace-topbar" aria-label="Main navigation">
        <a href="{{ route('dashboard') }}" wire:navigate class="workspace-brand" aria-label="TraKerja home">
            <img src="{{ asset('images/icon.png') }}" alt="" width="28" height="28"><span>TraKerja</span>
        </a>
        <div class="workspace-desktop-links">
            @foreach($navigationLinks as $link)
                <a href="{{ route($link['route']) }}" wire:navigate @if(request()->routeIs(...(array) $link['match'])) aria-current="page" @endif class="workspace-nav-link">{{ $link['label'] }}</a>
            @endforeach
        </div>
        <div class="workspace-account-actions">
            <button type="button" x-ref="mobileButton" @click="mobileOpen = !mobileOpen; profileOpen = false" aria-controls="workspace-mobile-menu" aria-expanded="false" :aria-expanded="mobileOpen.toString()" class="workspace-mobile-toggle">
                <i class="ph" :class="mobileOpen ? 'ph-x' : 'ph-list'" aria-hidden="true"></i><span>Menu</span>
            </button>
            <div class="relative" @click.outside="profileOpen = false" @keydown.escape.stop="profileOpen = false; $refs.profileButton.focus()">
                <button type="button" x-ref="profileButton" @click="profileOpen = !profileOpen; mobileOpen = false" aria-label="Account menu for {{ $user->name }}" aria-controls="workspace-profile" aria-expanded="false" :aria-expanded="profileOpen.toString()" class="workspace-profile-trigger">
                    <img src="{{ $user->avatar_url }}" alt="" class="workspace-avatar">
                    <span class="workspace-account-copy"><strong>{{ $user->name }}</strong><span>{{ $user->email }}</span></span>
                    <i class="ph ph-caret-down workspace-profile-chevron" :class="{ 'is-open': profileOpen }" aria-hidden="true"></i>
                </button>
                <div id="workspace-profile" x-cloak x-show="profileOpen" x-transition:enter="workspace-profile-transition" x-transition:enter-start="workspace-profile-hidden" x-transition:enter-end="workspace-profile-visible" x-transition:leave="workspace-profile-transition" x-transition:leave-start="workspace-profile-visible" x-transition:leave-end="workspace-profile-hidden" class="workspace-dropdown workspace-profile-menu" role="region" aria-label="Account settings">
                    <div class="workspace-profile-summary">
                        <img src="{{ $user->avatar_url }}" alt="" class="workspace-profile-avatar">
                        <div class="workspace-profile-identity"><div class="workspace-profile-identity-heading"><strong>{{ $user->name }}</strong><span class="workspace-account-badge"><i class="ph {{ $user->isAdmin() ? 'ph-shield-check' : ($user->isPremium() ? 'ph-crown' : 'ph-user') }}" aria-hidden="true"></i>{{ $user->isAdmin() ? 'Admin' : ($user->isPremium() ? 'Premium' : 'Free') }}</span></div><span>{{ $user->email }}</span></div>
                    </div>
                    <div class="workspace-rewards"><livewire:layout.gamification-widget /></div>
                    <div class="workspace-profile-links">
                    <a href="{{ route('profile.edit') }}" wire:navigate @click="profileOpen = false" class="workspace-menu-link"><span class="workspace-menu-icon"><i class="ph ph-user-circle" aria-hidden="true"></i></span><span>Profile settings</span><i class="ph ph-caret-right workspace-menu-chevron" aria-hidden="true"></i></a>
                    @if(!$user->isAdmin())
                        <a href="{{ route('membership.index') }}" wire:navigate @click="profileOpen = false" class="workspace-menu-link"><span class="workspace-menu-icon"><i class="ph ph-crown" aria-hidden="true"></i></span><span>Membership &amp; Credits</span><i class="ph ph-caret-right workspace-menu-chevron" aria-hidden="true"></i></a>
                    @endif
                    <a href="{{ route('automation.index') }}" wire:navigate @click="profileOpen = false" class="workspace-menu-link"><span class="workspace-menu-icon"><i class="ph ph-sliders" aria-hidden="true"></i></span><span>Automation & CSV</span><i class="ph ph-caret-right workspace-menu-chevron" aria-hidden="true"></i></a>
                    <a href="{{ route('support.index') }}" wire:navigate @click="profileOpen = false" class="workspace-menu-link"><span class="workspace-menu-icon"><i class="ph ph-headset" aria-hidden="true"></i></span><span>Support</span><i class="ph ph-caret-right workspace-menu-chevron" aria-hidden="true"></i></a>
                    @if($user->isAdmin())
                        <a href="{{ route('admin.index') }}" wire:navigate @click="profileOpen = false" class="workspace-menu-link"><span class="workspace-menu-icon"><i class="ph ph-shield-check" aria-hidden="true"></i></span><span>Admin panel</span><i class="ph ph-caret-right workspace-menu-chevron" aria-hidden="true"></i></a>
                    @endif
                    </div>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form" class="workspace-logout">
                        @csrf
                        <button type="button" onclick="confirmLogout('logout-form')" class="workspace-menu-link"><span class="workspace-menu-icon"><i class="ph ph-sign-out" aria-hidden="true"></i></span><span>Sign out</span></button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    <nav id="workspace-mobile-menu" x-cloak x-show="mobileOpen" x-transition.opacity.duration.150ms class="workspace-mobile-menu" aria-label="Mobile navigation" @click.outside="mobileOpen = false" @keydown.escape.stop="mobileOpen = false; $refs.mobileButton.focus()">
        @foreach($navigationLinks as $link)
            <a href="{{ route($link['route']) }}" wire:navigate @click="mobileOpen = false" @if(request()->routeIs(...(array) $link['match'])) aria-current="page" @endif class="workspace-menu-link">{{ $link['label'] }}</a>
        @endforeach
    </nav>
</header>
<nav class="workspace-bottom-navigation" aria-label="Mobile primary navigation">
    @foreach($navigationLinks as $link)
        @php $isCurrent = request()->routeIs(...(array) $link['match']); @endphp
        <a href="{{ route($link['route']) }}" wire:navigate @if($isCurrent) aria-current="page" @endif>
            <i class="{{ $isCurrent ? 'ph-fill' : 'ph' }} {{ ['dashboard'=>'ph-house', 'tracker'=>'ph-briefcase', 'jobs.explore'=>'ph-magnifying-glass', 'summary'=>'ph-chart-pie-slice'][$link['route']] }}" aria-hidden="true"></i>
            <span>{{ $link['label'] }}</span>
        </a>
    @endforeach
</nav>
