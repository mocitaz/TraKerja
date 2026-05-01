<nav class="bg-white/80 backdrop-blur-xl border-b border-slate-200/60 h-[72px] flex items-center justify-between px-4 sm:px-8 z-40 flex-shrink-0 sticky top-0">
    {{-- Left: Mobile Hamburger + Page Breadcrumb --}}
    <div class="flex items-center">
        {{-- Mobile Menu Toggle (mobile only - desktop uses sidebar's own collapse button) --}}
        <button @click="$store.sidebar.toggle()" class="lg:hidden p-2.5 mr-3 rounded-xl bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 hover:text-primary-600 transition-colors shadow-sm">
            <i class="ph ph-list text-lg"></i>
        </button>

        {{-- Dynamic Breadcrumb --}}
        @php
            $pageTitle = 'Dashboard';
            if(request()->routeIs('tracker'))           $pageTitle = 'Track Progress';
            elseif(request()->routeIs('summary'))       $pageTitle = 'Summary';
            elseif(request()->routeIs('goals'))         $pageTitle = 'Goals';
            elseif(request()->routeIs('interviews'))    $pageTitle = 'Interviews';
            elseif(request()->routeIs('cv.*'))          $pageTitle = 'CV Builder';
            elseif(request()->routeIs('ai-analyzer.*'))  $pageTitle = 'AI Analyzer';
            elseif(request()->routeIs('profile.edit')) $pageTitle = 'My Profile';
            elseif(request()->routeIs('csv.*'))         $pageTitle = 'CSV Tools';
        @endphp
        <div class="hidden sm:flex items-center space-x-2.5 text-sm font-bold">
            <span class="text-slate-400">TraKerja</span>
            <i class="ph-fill ph-caret-right text-slate-300 text-[10px]"></i>
            <span class="text-primary-600 tracking-tight bg-primary-50 px-2.5 py-1 rounded-md">{{ $pageTitle }}</span>
        </div>
        <h2 class="text-lg font-extrabold text-slate-800 sm:hidden tracking-tight">{{ $pageTitle }}</h2>
    </div>

    {{-- Right: User Profile Dropdown --}}
    <div class="flex items-center space-x-4">
        {{-- Desktop User Dropdown --}}
        <div class="hidden lg:block relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center space-x-3 focus:outline-none bg-white border border-slate-200/60 pl-1.5 pr-3 py-1.5 rounded-full hover:shadow-sm hover:border-primary-200 transition-all duration-300 group">
                @if(Auth::user()->logo)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url(Auth::user()->logo) }}" 
                         alt="Profile Photo" 
                         class="h-8 w-8 rounded-full object-cover">
                @else
                    <div class="h-8 w-8 bg-primary-100 rounded-full flex items-center justify-center shadow-inner">
                        <span class="text-primary-700 font-bold text-xs">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                @endif
                <span class="font-bold text-sm text-slate-700 hidden sm:block group-hover:text-primary-600 transition-colors">{{ explode(' ', Auth::user()->name)[0] }}</span>
                <i class="ph ph-caret-down text-[10px] text-slate-400 hidden sm:block transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
            </button>

            {{-- Dropdown Menu --}}
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                 @click.away="open = false"
                 class="absolute right-0 top-full mt-3 w-60 bg-white rounded-2xl shadow-[0_12px_40px_rgba(0,0,0,0.08)] border border-slate-100 py-2 z-50"
                 style="display: none;">

                <div class="px-5 py-3 border-b border-slate-100 bg-slate-50/50 mb-1">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Signed in as</p>
                    <p class="text-sm font-bold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
                </div>

                <a href="{{ route('profile.edit') }}" wire:navigate class="flex items-center px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-primary-600 hover:bg-slate-50 transition-colors">
                    <i class="ph ph-user-circle w-6 text-lg text-slate-400"></i>
                    My Profile
                </a>
                <a href="{{ route('csv.import') }}" wire:navigate class="flex items-center px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-primary-600 hover:bg-slate-50 transition-colors">
                    <i class="ph ph-file-csv w-6 text-lg text-slate-400"></i>
                    CSV Tools
                </a>

                <div class="border-t border-slate-100 my-1"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left flex items-center px-5 py-2.5 text-sm font-bold text-red-600 hover:bg-red-50 transition-colors">
                        <i class="ph ph-sign-out w-6 text-lg"></i>
                        Sign out
                    </button>
                </form>
            </div>
        </div>

        {{-- Mobile: Avatar Button --}}
        <div class="lg:hidden">
            <button @click="$store.sidebar.toggle()" class="w-10 h-10 rounded-full overflow-hidden flex items-center justify-center border-2 border-slate-200 active:scale-95 transition-all">
                @if(Auth::user()->logo)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url(Auth::user()->logo) }}" alt="Profile" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-primary-100 flex items-center justify-center">
                        <span class="text-primary-700 text-xs font-black">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                @endif
            </button>
        </div>
    </div>
</nav>
