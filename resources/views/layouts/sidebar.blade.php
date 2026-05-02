<aside 
    :class="{ 
        'w-20': !$store.sidebar.open, 
        'w-72': $store.sidebar.open,
        'translate-x-0': mobileSidebarOpen,
        '-translate-x-full': !mobileSidebarOpen 
    }"
    class="fixed inset-y-0 left-0 z-[60] bg-white/70 backdrop-blur-xl border-r border-white/20 transform transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] lg:translate-x-0 lg:static flex-shrink-0 flex flex-col shadow-[1px_0_10px_rgba(0,0,0,0.01)]"
>
    <!-- Desktop Collapse Button -->
    <button @click="$store.sidebar.toggle()" 
            class="absolute -right-3.5 top-20 bg-white border border-slate-200 text-slate-500 rounded-full w-7 h-7 hidden lg:flex items-center justify-center hover:bg-slate-50 hover:text-primary-600 transition-all duration-300 z-50 shadow-sm focus:outline-none">
        <i class="ph ph-caret-left text-[11px] transition-transform duration-300" :class="$store.sidebar.open ? '' : 'rotate-180'"></i>
    </button>

    <!-- Logo & Brand -->
    <div class="flex items-center h-[72px] px-6 border-b border-slate-100 flex-shrink-0 relative overflow-hidden bg-white/50 backdrop-blur-sm">
        <div class="flex items-center space-x-3 relative z-10 w-full" :class="$store.sidebar.open ? '' : 'justify-center space-x-0'">
            <div class="relative flex-shrink-0 bg-primary-50 p-1.5 rounded-xl border border-primary-100 transition-all duration-300">
                <img src="{{ asset('images/icon.png') }}" 
                     alt="TraKerja Logo" 
                     class="h-7 w-7 object-contain"
                     onerror="this.style.display='none';">
            </div>
            <div x-show="$store.sidebar.open" x-transition.opacity.duration.300ms class="flex flex-col truncate">
                <span class="text-lg font-extrabold text-slate-800 tracking-tight leading-none mb-0.5">
                    TraKerja
                </span>
                <span class="text-[9px] font-bold text-primary-500 uppercase tracking-widest">
                    Job Tracker
                </span>
            </div>
        </div>
        
        <button @click="mobileSidebarOpen = false" class="lg:hidden absolute right-4 p-2 text-slate-400 hover:text-slate-700 bg-slate-50 rounded-lg transition-colors z-20">
            <i class="ph ph-x text-lg"></i>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto sidebar-scroll">
        <!-- Section Label -->
        <div x-show="$store.sidebar.open" x-transition.opacity.duration.300ms class="px-2 mb-3 mt-2">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tracker</span>
        </div>

        @php
        $navLinks = [
            ['route' => 'tracker',   'routeIs' => 'tracker',   'icon' => 'ph-layout',     'label' => 'Track Progress'],
            ['route' => 'summary',   'routeIs' => 'summary',   'icon' => 'ph-chart-bar',  'label' => 'Summary'],
            ['route' => 'goals',     'routeIs' => 'goals',     'icon' => 'ph-target',     'label' => 'Goals'],
        ];
        @endphp

        @foreach($navLinks as $link)
        @php $isActive = request()->routeIs($link['routeIs']); @endphp
        <a href="{{ route($link['route']) }}" wire:navigate
           class="group relative flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 overflow-hidden {{ $isActive ? 'bg-primary-50 text-primary-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}"
           :class="$store.sidebar.open ? '' : 'justify-center'">
            @if($isActive)
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-primary-600 rounded-r-full"></div>
            @endif
            <div class="flex items-center justify-center flex-shrink-0 relative z-10" :class="$store.sidebar.open ? 'w-8' : ''">
                <i class="text-[1.25rem] transition-transform duration-300 group-hover:scale-110 {{ $isActive ? 'ph-fill '.$link['icon'].' text-primary-600' : 'ph '.$link['icon'] }}"></i>
            </div>
            <span x-show="$store.sidebar.open" x-transition.opacity.duration.300ms class="ml-3 truncate">{{ $link['label'] }}</span>
            <!-- Tooltip -->
            <div x-show="!$store.sidebar.open" class="absolute left-14 px-3 py-1.5 bg-slate-800 text-white text-xs font-bold rounded-md shadow-xl opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity duration-300 z-50 whitespace-nowrap">
                {{ $link['label'] }}
                <div class="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent border-r-slate-800"></div>
            </div>
        </a>
        @endforeach

        <div class="py-3"><div class="border-t border-slate-100"></div></div>

        <div x-show="$store.sidebar.open" x-transition.opacity.duration.300ms class="px-2 mb-3">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Management</span>
        </div>

        @php
        $mgmtLinks = [
            ['route' => 'interviews',        'routeIs' => 'interviews',    'icon' => 'ph-calendar',   'label' => 'Interviews'],
            ['route' => 'cv.builder',        'routeIs' => 'cv.*',          'icon' => 'ph-file-text',  'label' => 'CV Builder'],
            ['route' => 'ai-analyzer.index', 'routeIs' => 'ai-analyzer.*', 'icon' => 'ph-sparkle',    'label' => 'AI Analyzer'],
        ];
        @endphp

        @foreach($mgmtLinks as $link)
        @php $isActive = request()->routeIs($link['routeIs']); @endphp
        <a href="{{ route($link['route']) }}" wire:navigate
           class="group relative flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 overflow-hidden {{ $isActive ? 'bg-primary-50 text-primary-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}"
           :class="$store.sidebar.open ? '' : 'justify-center'">
            @if($isActive)
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-primary-600 rounded-r-full"></div>
            @endif
            <div class="flex items-center justify-center flex-shrink-0 relative z-10" :class="$store.sidebar.open ? 'w-8' : ''">
                <i class="text-[1.25rem] transition-transform duration-300 group-hover:scale-110 {{ $isActive ? 'ph-fill '.$link['icon'].' text-primary-600' : 'ph '.$link['icon'] }}"></i>
            </div>
            <span x-show="$store.sidebar.open" x-transition.opacity.duration.300ms class="ml-3 truncate">{{ $link['label'] }}</span>
            <div x-show="!$store.sidebar.open" class="absolute left-14 px-3 py-1.5 bg-slate-800 text-white text-xs font-bold rounded-md shadow-xl opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity duration-300 z-50 whitespace-nowrap">
                {{ $link['label'] }}
                <div class="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent border-r-slate-800"></div>
            </div>
        </a>
        @endforeach

        <div class="py-3"><div class="border-t border-slate-100"></div></div>

        <div x-show="$store.sidebar.open" x-transition.opacity.duration.300ms class="px-2 mb-3">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">System</span>
        </div>

        @php
        $sysLinks = [
            ['route' => 'profile.edit', 'routeIs' => 'profile.edit', 'icon' => 'ph-user-circle', 'label' => 'My Profile'],
            ['route' => 'csv.import',   'routeIs' => 'csv.*',        'icon' => 'ph-file-csv',    'label' => 'CSV Tools'],
        ];
        @endphp

        @foreach($sysLinks as $link)
        @php $isActive = request()->routeIs($link['routeIs']); @endphp
        <a href="{{ route($link['route']) }}" wire:navigate
           class="group relative flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 overflow-hidden {{ $isActive ? 'bg-primary-50 text-primary-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}"
           :class="$store.sidebar.open ? '' : 'justify-center'">
            @if($isActive)
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-primary-600 rounded-r-full"></div>
            @endif
            <div class="flex items-center justify-center flex-shrink-0 relative z-10" :class="$store.sidebar.open ? 'w-8' : ''">
                <i class="text-[1.25rem] transition-transform duration-300 group-hover:scale-110 {{ $isActive ? 'ph-fill '.$link['icon'].' text-primary-600' : 'ph '.$link['icon'] }}"></i>
            </div>
            <span x-show="$store.sidebar.open" x-transition.opacity.duration.300ms class="ml-3 truncate">{{ $link['label'] }}</span>
            <div x-show="!$store.sidebar.open" class="absolute left-14 px-3 py-1.5 bg-slate-800 text-white text-xs font-bold rounded-md shadow-xl opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity duration-300 z-50 whitespace-nowrap">
                {{ $link['label'] }}
                <div class="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent border-r-slate-800"></div>
            </div>
        </a>
        @endforeach

        @if(Auth::user()->is_admin)
        <div class="py-3"><div class="border-t border-slate-100"></div></div>
        @php $isActive = request()->routeIs('admin.*'); @endphp
        <a href="{{ route('admin.index') }}" wire:navigate
           class="group relative flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 overflow-hidden {{ $isActive ? 'bg-primary-50 text-primary-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}"
           :class="$store.sidebar.open ? '' : 'justify-center'">
            <div class="flex items-center justify-center flex-shrink-0 relative z-10" :class="$store.sidebar.open ? 'w-8' : ''">
                <i class="text-[1.25rem] group-hover:scale-110 transition-transform {{ $isActive ? 'ph-fill ph-shield-check text-primary-600' : 'ph ph-shield-check' }}"></i>
            </div>
            <span x-show="$store.sidebar.open" x-transition.opacity.duration.300ms class="ml-3 truncate">Admin Panel</span>
            <div x-show="!$store.sidebar.open" class="absolute left-14 px-3 py-1.5 bg-slate-800 text-white text-xs font-bold rounded-md shadow-xl opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity duration-300 z-50 whitespace-nowrap">
                Admin Panel
                <div class="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent border-r-slate-800"></div>
            </div>
        </a>
        @endif
    </nav>

    <!-- Bottom Profile Summary -->
    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
        <div class="flex items-center bg-white rounded-xl p-2 cursor-pointer hover:shadow-sm border border-slate-200/60 transition-all duration-200"
             :class="$store.sidebar.open ? '' : 'justify-center'">
            @php $user = Auth::user(); @endphp
            @if($user && $user->logo)
                <img src="{{ \Illuminate\Support\Facades\Storage::url($user->logo) }}" 
                     alt="Profile" 
                     class="h-8 w-8 rounded-lg object-cover ring-2 ring-white flex-shrink-0 shadow-sm">
            @else
                <div class="h-8 w-8 bg-primary-100 rounded-lg flex items-center justify-center ring-2 ring-white flex-shrink-0 shadow-sm">
                    <span class="text-primary-700 font-bold text-xs">{{ substr($user->name ?? 'U', 0, 1) }}</span>
                </div>
            @endif
            
            <div x-show="$store.sidebar.open" x-transition.opacity.duration.300ms class="ml-3 min-w-0 flex-1">
                <p class="text-sm font-bold text-slate-800 truncate">{{ $user->name ?? 'User' }}</p>
                <p class="text-[10px] font-semibold text-slate-400 truncate uppercase tracking-widest mt-0.5">{{ $user->is_premium ? 'Premium' : 'Free Plan' }}</p>
            </div>
        </div>
        <form x-show="$store.sidebar.open" x-transition.opacity.duration.300ms method="POST" action="{{ route('logout') }}" class="mt-2" id="logout-form-sidebar">
            @csrf
            <button type="button" onclick="confirmLogout('logout-form-sidebar')" class="w-full flex items-center gap-2 px-3 py-2 text-xs font-bold text-rose-500 hover:bg-rose-50 rounded-xl transition-colors text-left">
                <i class="ph ph-sign-out text-base"></i>
                Sign Out
            </button>
        </form>
    </div>
</aside>
