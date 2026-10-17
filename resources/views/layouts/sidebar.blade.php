<aside id="primary-sidebar"
    :class="{
        'w-12': !$store.sidebar.open && !mobileSidebarOpen,
        'w-48': $store.sidebar.open || mobileSidebarOpen,
        'translate-x-0': mobileSidebarOpen,
        '-translate-x-full': !mobileSidebarOpen
    }"
    class="fixed inset-y-0 left-0 z-[60] bg-[#fafafa] border-r border-zinc-200/50 transform transition-[width,transform] duration-300 ease-[cubic-bezier(0.25,1,0.5,1)] lg:translate-x-0 lg:static flex-shrink-0 flex flex-col"
>
    <style>
        #primary-sidebar :is(a, button):focus-visible {
            outline: 2px solid #18181b;
            outline-offset: 2px;
        }
    </style>
    <!-- Logo & Brand Header -->
    <div class="flex items-center h-16 px-4 border-b border-zinc-200/50 flex-shrink-0 relative bg-[#fafafa]">
        <div class="flex items-center space-x-2 relative z-10 w-full" :class="($store.sidebar.open || mobileSidebarOpen) ? '' : 'justify-center space-x-0'">
            <div class="relative flex-shrink-0 flex items-center justify-center">
                <img src="{{ asset('images/icon.png') }}"
                     alt="{{ __('TraKerja Logo') }}"
                     class="h-5 w-5 object-contain"
                     onerror="this.style.display='none';">
            </div>
            <div x-cloak x-show="$store.sidebar.open || mobileSidebarOpen" x-transition.opacity.duration.200ms class="flex flex-col truncate whitespace-nowrap">
                <span class="text-xs font-bold text-ui-strong tracking-tight leading-none">
                    {{ __('TraKerja') }}
                </span>
                <span class="text-xs font-black text-ui-muted uppercase tracking-widest leading-none mt-0.5">
                    {{ __('Pencatat Lamaran') }}
                </span>
            </div>
        </div>

        <button type="button" aria-label="{{ __('Tutup navigasi') }}" aria-controls="primary-sidebar" aria-expanded="false" :aria-expanded="mobileSidebarOpen.toString()" @click="mobileSidebarOpen = false" class="lg:hidden absolute right-3 p-1 text-ui-muted hover:text-ui-strong bg-zinc-100 rounded-lg transition-colors z-20">
            <i aria-hidden="true" class="ph ph-x text-sm"></i>
        </button>
    </div>

    <!-- Navigation (Changes overflow dynamically to allow tooltips when collapsed) -->
    <nav aria-label="{{ __('Navigasi utama') }}" class="flex-1 px-2.5 py-3 space-y-1 sidebar-scroll"
         :class="($store.sidebar.open || mobileSidebarOpen) ? 'overflow-y-auto' : 'overflow-visible'">

        <!-- Section Label -->
        <div x-cloak x-show="$store.sidebar.open || mobileSidebarOpen" x-transition.opacity.duration.200ms class="px-2 mt-1 mb-1 whitespace-nowrap">
            <span class="text-xs font-bold text-ui-muted uppercase tracking-widest">{{ __('Lamaran') }}</span>
        </div>

        @php
        $navLinks = [
            ['route' => 'dashboard', 'routeIs' => 'dashboard', 'icon' => 'ph-circles-four', 'label' => __('Dasbor')],
            ['route' => 'tracker',   'routeIs' => 'tracker',   'icon' => 'ph-layout',     'label' => __('Pantau Lamaran')],
            ['route' => 'summary',   'routeIs' => 'summary',   'icon' => 'ph-chart-bar',  'label' => __('Ringkasan')],
            ['route' => 'jobs.explore', 'routeIs' => 'jobs.explore', 'icon' => 'ph-magnifying-glass', 'label' => __('Cari Lowongan')],
        ];
        @endphp

        @foreach($navLinks as $link)
        @php $isActive = request()->routeIs($link['routeIs']); @endphp
        <a href="{{ route($link['route']) }}" aria-label="{{ __($link['label']) }}" @if($isActive) aria-current="page" @endif wire:navigate
           x-data="{ hovered: false, focused: false }" @mouseenter="hovered = true" @mouseleave="hovered = false" @focusin="focused = true" @focusout="focused = false"
           class="group relative flex items-center rounded-md text-sm font-medium transition-all duration-150 {{ $isActive ? 'bg-primary-50 text-ui-strong border border-primary-200/60 shadow-3xs font-bold' : 'text-ui-muted hover:bg-primary-50/50 hover:text-ui-strong hover:border-primary-100/30 border border-transparent' }}"
           :class="($store.sidebar.open || mobileSidebarOpen) ? 'px-2.5 py-1.5 w-full' : 'p-2 w-9 h-9 justify-center mx-auto'">
            <div class="flex items-center justify-center flex-shrink-0 relative z-10" :class="($store.sidebar.open || mobileSidebarOpen) ? 'w-5' : ''">
                <i aria-hidden="true" class="text-base transition-all duration-150 group-hover:scale-110 {{ $isActive ? 'ph-fill '.$link['icon'].' text-ui-strong' : 'ph '.$link['icon'].' group-hover:text-ui-strong' }}"></i>
            </div>
            <span x-cloak x-show="$store.sidebar.open || mobileSidebarOpen" x-transition.opacity.duration.200ms class="ml-2.5 truncate whitespace-nowrap">{{ __($link['label']) }}</span>
            <!-- Tooltip (Alpine.js driven, safe from overflow clipping) -->
            <div x-cloak aria-hidden="true" x-show="!$store.sidebar.open && !mobileSidebarOpen && (hovered || focused)" x-transition.opacity.duration.100ms
                 class="absolute px-2.5 py-1 bg-zinc-800 border border-zinc-700 text-white text-xs font-bold uppercase tracking-wider rounded-md shadow-lg z-50 whitespace-nowrap" style="left: 46px;">
                {{ __($link['label']) }}
            </div>
        </a>
        @endforeach

        <div class="py-1.5"><div class="border-t border-zinc-200/60"></div></div>

        <div x-cloak x-show="$store.sidebar.open || mobileSidebarOpen" x-transition.opacity.duration.200ms class="px-2 mb-1 whitespace-nowrap">
            <span class="text-xs font-bold text-ui-muted uppercase tracking-widest">{{ __('Pengelolaan') }}</span>
        </div>

        @php
        $mgmtLinks = [
            ['route' => 'interviews',        'routeIs' => 'interviews',    'icon' => 'ph-calendar',        'label' => __('Wawancara')],
            ['route' => 'cv.builder',        'routeIs' => 'cv.*',          'icon' => 'ph-file-text',       'label' => __('Pembuat CV')],
            ['route' => 'ai-studio.index',   'routeIs' => 'ai-studio.*',   'icon' => 'ph-sparkle',         'label' => __('Studio AI')],
        ];
        @endphp

        @foreach($mgmtLinks as $link)
        @php $isActive = request()->routeIs($link['routeIs']); @endphp
        <a href="{{ route($link['route']) }}" aria-label="{{ __($link['label']) }}" @if($isActive) aria-current="page" @endif wire:navigate
           x-data="{ hovered: false, focused: false }" @mouseenter="hovered = true" @mouseleave="hovered = false" @focusin="focused = true" @focusout="focused = false"
           class="group relative flex items-center rounded-md text-sm font-medium transition-all duration-150 {{ $isActive ? 'bg-primary-50 text-ui-strong border border-primary-200/60 shadow-3xs font-bold' : 'text-ui-muted hover:bg-primary-50/50 hover:text-ui-strong hover:border-primary-100/30 border border-transparent' }}"
           :class="($store.sidebar.open || mobileSidebarOpen) ? 'px-2.5 py-1.5 w-full' : 'p-2 w-9 h-9 justify-center mx-auto'">
            <div class="flex items-center justify-center flex-shrink-0 relative z-10" :class="($store.sidebar.open || mobileSidebarOpen) ? 'w-5' : ''">
                <i aria-hidden="true" class="text-base transition-all duration-150 group-hover:scale-110 {{ $isActive ? 'ph-fill '.$link['icon'].' text-ui-strong' : 'ph '.$link['icon'].' group-hover:text-ui-strong' }}"></i>
            </div>
            <span x-cloak x-show="$store.sidebar.open || mobileSidebarOpen" x-transition.opacity.duration.200ms class="ml-2.5 truncate whitespace-nowrap">{{ __($link['label']) }}</span>
            <!-- Tooltip (Alpine.js driven, safe from overflow clipping) -->
            <div x-cloak aria-hidden="true" x-show="!$store.sidebar.open && !mobileSidebarOpen && (hovered || focused)" x-transition.opacity.duration.100ms
                 class="absolute px-2.5 py-1 bg-zinc-800 border border-zinc-700 text-white text-xs font-bold uppercase tracking-wider rounded-md shadow-lg z-50 whitespace-nowrap" style="left: 46px;">
                {{ __($link['label']) }}
            </div>
        </a>
        @endforeach

        @if(Auth::user()->is_admin)
        <div class="py-1.5"><div class="border-t border-zinc-200/60"></div></div>
        @php $isActive = request()->routeIs('admin.*'); @endphp
        <a href="{{ route('admin.index') }}" aria-label="{{ __('Panel Admin') }}" @if($isActive) aria-current="page" @endif wire:navigate
           x-data="{ hovered: false, focused: false }" @mouseenter="hovered = true" @mouseleave="hovered = false" @focusin="focused = true" @focusout="focused = false"
           class="group relative flex items-center rounded-md text-sm font-medium transition-all duration-150 {{ $isActive ? 'bg-primary-50 text-ui-strong border border-primary-200/60 shadow-3xs font-bold' : 'text-ui-muted hover:bg-primary-50/50 hover:text-ui-strong hover:border-primary-100/30 border border-transparent' }}"
           :class="($store.sidebar.open || mobileSidebarOpen) ? 'px-2.5 py-1.5 w-full' : 'p-2 w-9 h-9 justify-center mx-auto'">
            <div class="flex items-center justify-center flex-shrink-0 relative z-10" :class="($store.sidebar.open || mobileSidebarOpen) ? 'w-5' : ''">
                <i aria-hidden="true" class="text-base transition-all duration-150 group-hover:scale-110 {{ $isActive ? 'ph-fill ph-shield-check text-ui-strong' : 'ph ph-shield-check group-hover:text-ui-strong' }}"></i>
            </div>
            <span x-cloak x-show="$store.sidebar.open || mobileSidebarOpen" x-transition.opacity.duration.200ms class="ml-2.5 truncate whitespace-nowrap">{{ __('Panel Admin') }}</span>
            <!-- Tooltip (Alpine.js driven, safe from overflow clipping) -->
            <div x-cloak aria-hidden="true" x-show="!$store.sidebar.open && !mobileSidebarOpen && (hovered || focused)" x-transition.opacity.duration.100ms
                 class="absolute px-2.5 py-1 bg-zinc-800 border border-zinc-700 text-white text-xs font-bold uppercase tracking-wider rounded-md shadow-lg z-50 whitespace-nowrap" style="left: 46px;">
                {{ __('Panel Admin') }}
            </div>
        </a>
        @endif
    </nav>

    <!-- Bottom Profile Summary -->
    <div class="p-3 border-t border-zinc-200/50 shrink-0 space-y-2 bg-[#fafafa]">
        <!-- User Settings Action Card -->
        @php $user = Auth::user(); @endphp
        <div x-data="{ hovered: false, focused: false }" @mouseenter="hovered = true" @mouseleave="hovered = false" @focusin="focused = true" @focusout="focused = false"
           class="group relative flex items-center justify-between p-1.5 hover:bg-zinc-200/65 rounded-lg transition-all duration-200"
           :class="($store.sidebar.open || mobileSidebarOpen) ? 'w-full' : 'w-9 h-9 justify-center mx-auto'">
            <a href="{{ route('profile.edit') }}" wire:navigate aria-label="{{ __('Pengaturan profil') }}" class="flex items-center space-x-2 min-w-0 rounded">
                @if($user && $user->logo)
                    <img src="{{ $user->avatar_url }}"
                         alt="{{ __('Foto profil') }}"
                         class="h-6 w-6 rounded-full object-cover border border-zinc-200 shadow-xs shrink-0">
                @else
                    <div class="h-6 w-6 bg-zinc-100 border border-zinc-200 rounded-full flex items-center justify-center shrink-0">
                        <span class="text-zinc-700 font-bold text-xs">{{ substr($user->name ?? 'U', 0, 1) }}</span>
                    </div>
                @endif

                <div x-cloak x-show="$store.sidebar.open || mobileSidebarOpen" x-transition.opacity.duration.200ms class="min-w-0 text-left whitespace-nowrap">
                    <p class="text-xs font-bold text-ui-strong truncate leading-tight">{{ $user->name ?? 'Pengguna' }}</p>
                    <p class="text-xs font-semibold text-ui-muted truncate leading-none mt-0.5">{{ $user->isPremium() ? __('Anggota Premium') : __('Akun Gratis') }}</p>
                </div>
            </a>

            <!-- Logout button inside user card -->
            <form x-cloak x-show="$store.sidebar.open || mobileSidebarOpen" x-transition.opacity.duration.200ms method="POST" action="{{ route('logout') }}" id="logout-form-sidebar" class="shrink-0" onclick="event.stopPropagation();">
                @csrf
                <button type="button" aria-label="{{ __('Keluar') }}" onclick="confirmLogout('logout-form-sidebar')" class="w-5.5 h-5.5 rounded hover:bg-rose-50 text-ui-muted hover:text-rose-600 flex items-center justify-center transition-colors">
                    <i aria-hidden="true" class="ph ph-sign-out text-xs"></i>
                </button>
            </form>

            <!-- Tooltip for Collapsed Sidebar Profile -->
            <div x-cloak aria-hidden="true" x-show="!$store.sidebar.open && !mobileSidebarOpen && (hovered || focused)" x-transition.opacity.duration.100ms
                 class="absolute px-2.5 py-1 bg-zinc-800 border border-zinc-700 text-white text-xs font-bold uppercase tracking-wider rounded-md shadow-lg z-50 whitespace-nowrap" style="left: 46px;">
                {{ __('Pengaturan profil') }}
            </div>
        </div>

        <!-- Collapse Action Row -->
        <div class="pt-2 border-t border-zinc-200/40 flex items-center" :class="($store.sidebar.open || mobileSidebarOpen) ? 'justify-between px-1.5' : 'justify-center'">
            <button type="button" aria-label="{{ __('Ubah navigasi') }}" :aria-label="(window.innerWidth >= 1024 ? $store.sidebar.open : mobileSidebarOpen) ? @js(__('Ciutkan navigasi')) : @js(__('Perluas navigasi'))" aria-controls="primary-sidebar" aria-expanded="false" :aria-expanded="(window.innerWidth >= 1024 ? $store.sidebar.open : mobileSidebarOpen).toString()" @click="window.innerWidth >= 1024 ? $store.sidebar.toggle() : mobileSidebarOpen = !mobileSidebarOpen"
                    class="w-6.5 h-6.5 rounded hover:bg-zinc-200/50 text-ui-muted hover:text-ui-strong flex items-center justify-center transition-all focus:outline-none">
                <i aria-hidden="true" class="ph text-xs" :class="($store.sidebar.open || mobileSidebarOpen) ? 'ph-caret-left' : 'ph-caret-right'"></i>
            </button>
            <span x-cloak x-show="$store.sidebar.open || mobileSidebarOpen" class="text-xs font-bold text-ui-muted uppercase tracking-wider leading-none select-none">{{ __('Ciutkan') }}</span>
        </div>
    </div>
</aside>
