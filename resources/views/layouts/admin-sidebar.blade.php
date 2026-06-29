@php
    $isMobileContext = $isMobile ?? false;
@endphp
<div 
    @if($isMobileContext)
        x-data="{ sidebarOpen: true }"
    @endif
    @if(!$isMobileContext)
        :class="$store.sidebar.open ? 'w-64' : 'w-20'" 
        class="hidden lg:flex flex-col bg-white text-slate-600 min-h-screen fixed left-0 top-0 z-50 border-r border-slate-200 transition-all duration-300 ease-in-out group/sidebar"
    @else
        class="flex flex-col bg-white text-slate-600 min-h-screen w-full relative z-50 border-r border-slate-200"
    @endif
>
    <!-- Logo Area -->
    <a href="{{ route('admin.index') }}" wire:navigate class="h-20 flex items-center px-6 border-b border-slate-100 overflow-hidden shrink-0 group/logo">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-200 transition-transform group-hover/logo:scale-110 duration-300">
                <i class="ph-fill ph-shield-check text-lg"></i>
            </div>
            <span x-show="$store.sidebar.open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-[-10px]" x-transition:enter-end="opacity-100 translate-x-0" class="text-xl font-bold tracking-tight">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-slate-600">
                    TraKerja <span class="text-[10px] text-slate-400 font-black uppercase tracking-widest ml-1">Admin</span>
                </span>
            </span>
        </div>
    </a>

    <!-- Navigation Groups -->
    <div class="flex-1 px-3 py-6 space-y-8 overflow-y-auto overflow-x-hidden custom-scrollbar">
        <!-- Main Admin -->
        <div>
            <div x-show="$store.sidebar.open" class="px-3 mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-[2px]">Overview</div>
            <nav class="space-y-1">
                <x-sidebar-link :href="route('admin.index')" :active="request()->routeIs('admin.index')" icon="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z">
                    Dashboard
                </x-sidebar-link>
                <x-sidebar-link :href="route('admin.users')" :active="request()->routeIs('admin.users*')" icon="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z">
                    Users List
                </x-sidebar-link>
                <x-sidebar-link :href="route('admin.monetization')" :active="request()->routeIs('admin.monetization')" icon="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    Monetization
                </x-sidebar-link>
            </nav>
        </div>

        <!-- Operations -->
        <div>
            <div x-show="$store.sidebar.open" class="px-3 mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-[2px]">Operations</div>
            <nav class="space-y-1">
                <x-sidebar-link :href="route('admin.analytics')" :active="request()->routeIs('admin.analytics')" icon="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z">
                    Analytics
                </x-sidebar-link>
                <x-sidebar-link :href="route('admin.email-blast')" :active="request()->routeIs('admin.email-blast*')" icon="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75">
                    Email Blast
                </x-sidebar-link>
                <x-sidebar-link :href="route('admin.feedbacks.index')" :active="request()->routeIs('admin.feedbacks*')" icon="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                    User Feedback
                </x-sidebar-link>
            </nav>
        </div>

        <!-- Infrastructure -->
        <div>
            <div x-show="$store.sidebar.open" class="px-3 mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-[2px]">Infrastructure</div>
            <nav class="space-y-1">
                <x-sidebar-link :href="route('admin.integration-hub')" :active="request()->routeIs('admin.integration-hub')" icon="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z">
                    Integrations
                </x-sidebar-link>
                <x-sidebar-link :href="route('admin.database-maintenance')" :active="request()->routeIs('admin.database-maintenance')" icon="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0v3.75">
                    Maintenance
                </x-sidebar-link>
                <x-sidebar-link :href="route('admin.scraper')" :active="request()->routeIs('admin.scraper')" icon="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5">
                    Scraper Engine
                </x-sidebar-link>
                <x-sidebar-link :href="route('admin.settings')" :active="request()->routeIs('admin.settings')" icon="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 0 1 0 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 0 1 0-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281Z">
                    Global Settings
                </x-sidebar-link>
            </nav>
        </div>
    </div>

    <!-- User & Exit Area -->
    <div class="p-4 border-t border-gray-100 shrink-0 space-y-3">
        <a href="{{ route('tracker') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 bg-slate-50 text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all border border-slate-100 group/exit">
            <i class="ph-bold ph-arrow-square-out text-lg group-hover/exit:scale-110 transition-transform"></i>
            <span x-show="$store.sidebar.open" class="text-[10px] font-black uppercase tracking-widest">Back to Dashboard</span>
        </a>

        <div class="flex items-center gap-3 px-3 py-2 bg-slate-900 rounded-xl overflow-hidden shadow-lg shadow-slate-200">
            <div class="w-8 h-8 rounded-lg bg-indigo-500 flex items-center justify-center shrink-0">
                <span class="text-xs font-bold text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
            </div>
            <div x-show="$store.sidebar.open" class="flex-1 min-w-0">
                <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-slate-400 truncate">System Administrator</p>
            </div>
        </div>
    </div>
</div>
