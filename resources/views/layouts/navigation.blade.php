<nav class="bg-[#fafafa]/80 backdrop-blur-md border-b border-zinc-200/50 h-16 flex items-center justify-center z-40 flex-shrink-0 sticky top-0">
    <div class="max-w-[1300px] w-full mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
        {{-- Left: Mobile Hamburger + Page Breadcrumb --}}
        <div class="flex items-center">
            {{-- Mobile Menu Toggle (mobile/tablet only) --}}
            <button @click="mobileSidebarOpen = !mobileSidebarOpen" class="lg:hidden p-1.5 mr-3 bg-white border border-slate-200 text-slate-500 rounded-lg transition-colors focus:outline-none">
                <i class="ph ph-list text-sm"></i>
            </button>

            {{-- Dynamic Breadcrumb --}}
            @php
                $pageTitle = 'Dashboard';
                if(request()->routeIs('tracker'))           $pageTitle = 'Track Progress';
                elseif(request()->routeIs('summary'))       $pageTitle = 'Summary';
                elseif(request()->routeIs('goals'))         $pageTitle = 'Goals';
                elseif(request()->routeIs('interviews'))    $pageTitle = 'Interviews';
                elseif(request()->routeIs('cv.*'))          $pageTitle = 'CV Builder';
                elseif(request()->routeIs('ai-studio.*'))   $pageTitle = 'AI Studio';
                elseif(request()->routeIs('profile.edit'))  $pageTitle = 'My Profile';
                elseif(request()->routeIs('automation.*') || request()->routeIs('csv.*')) $pageTitle = 'Automation';
                elseif(request()->routeIs('support.*'))     $pageTitle = 'Support';
            @endphp
            <div class="hidden sm:flex items-center space-x-1.5 text-xs font-semibold text-zinc-400 select-none">
                <span class="hover:text-zinc-700 transition-colors">TraKerja</span>
                <span class="text-zinc-300">/</span>
                <span class="text-zinc-800 font-bold capitalize">{{ $pageTitle }}</span>
            </div>
            <h2 class="text-xs font-bold text-slate-800 sm:hidden tracking-tight">{{ $pageTitle }}</h2>
        </div>

        {{-- Right: User Profile Dropdown --}}
        <div class="flex items-center space-x-3.5">
            {{-- Desktop User Dropdown --}}
            <div class="relative shrink-0" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center gap-2 px-2 py-1 rounded-lg border border-zinc-200/80 hover:border-zinc-300 hover:bg-zinc-50/60 transition-all cursor-pointer bg-white shadow-3xs focus:outline-none max-w-[200px] sm:max-w-[240px]">
                    <div class="w-7 h-7 rounded-full overflow-hidden border border-zinc-200 shrink-0 flex items-center justify-center bg-white">
                        @if(Auth::user()->logo)
                            <img src="{{ Auth::user()->avatar_url }}" 
                                 alt="Profile Photo" 
                                 class="h-full w-full object-cover">
                        @else
                            <div class="h-full w-full bg-zinc-100 flex items-center justify-center">
                                <span class="text-zinc-700 font-bold text-[10px]">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="hidden sm:flex flex-col text-left truncate min-w-0 flex-1">
                        <span class="text-[11px] font-bold text-zinc-800 leading-none truncate">{{ Auth::user()->name }}</span>
                        <span class="text-[9px] font-medium text-zinc-400 leading-none truncate mt-1">{{ Auth::user()->email }}</span>
                    </div>

                    <i class="ph-bold ph-caret-down text-zinc-400 text-[10px] shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                </button>

                {{-- Dropdown Menu (Redesigned with Premium Tailwind UI / Linear aesthetics) --}}
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-0.5"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-0.5"
                     @click.away="open = false"
                     class="absolute right-0 top-full mt-2 w-56 bg-white rounded-xl shadow-[0_12px_32px_rgba(0,0,0,0.1)] border border-zinc-200/80 p-1 z-50 text-left"
                     style="display: none;">

                    <!-- Account Info Header & Gamification Level -->
                    <div class="px-3 py-2 border-b border-zinc-100 mb-1">
                        <div class="sm:hidden mb-2 pb-2 border-b border-zinc-100/80">
                            <p class="text-[8px] font-bold text-zinc-400 uppercase tracking-widest leading-none">Signed in as</p>
                            <p class="text-[11px] font-bold text-zinc-800 truncate leading-none mt-1">{{ Auth::user()->name }}</p>
                            <p class="text-[9px] font-medium text-zinc-400 truncate leading-none mt-0.5">{{ Auth::user()->email }}</p>
                        </div>
                        <p class="text-[8px] font-bold text-zinc-400 uppercase tracking-widest leading-none">Account Rewards</p>
                        
                        {{-- Embedded Gamification Level Widget --}}
                        <div class="mt-2">
                            <livewire:layout.gamification-widget />
                        </div>
                    </div>

                    <!-- Options list -->
                    <div class="space-y-0.5">
                        <a href="{{ route('profile.edit') }}" wire:navigate class="group flex items-center gap-2 px-3 py-1.5 text-[11px] font-semibold text-zinc-650 hover:text-zinc-950 hover:bg-zinc-100/50 rounded-lg transition-colors">
                            <i class="ph ph-user-circle text-base text-zinc-400 group-hover:text-zinc-700 transition-colors"></i>
                            <span>My Profile</span>
                        </a>

                        @if(!Auth::user()->isPremium())
                        <a href="{{ route('payment.premium') }}" wire:navigate class="group flex items-center gap-2 px-3 py-1.5 text-[11px] font-semibold text-primary-600 hover:bg-primary-50/50 rounded-lg transition-colors">
                            <i class="ph ph-crown text-base text-primary-500"></i>
                            <span>Upgrade Premium</span>
                        </a>
                        @else
                        <div class="flex items-center justify-between px-3 py-1.5 text-[11px] font-semibold text-amber-600 bg-amber-50/40 rounded-lg">
                            <div class="flex items-center gap-2">
                                <i class="ph ph-crown text-base text-amber-500"></i>
                                <span>Pro Member</span>
                            </div>
                            <span class="text-[8px] font-black uppercase bg-amber-100 text-amber-800 px-1 py-0.2 rounded-sm select-none">Pro</span>
                        </div>
                        @endif

                        <a href="{{ route('automation.index') }}" wire:navigate class="group flex items-center gap-2 px-3 py-1.5 text-[11px] font-semibold text-zinc-650 hover:text-zinc-950 hover:bg-zinc-100/50 rounded-lg transition-colors">
                            <i class="ph ph-sliders text-base text-zinc-400 group-hover:text-zinc-700 transition-colors"></i>
                            <span>Automation</span>
                        </a>

                        <a href="{{ route('support.index') }}" wire:navigate class="group flex items-center gap-2 px-3 py-1.5 text-[11px] font-semibold text-zinc-650 hover:text-zinc-950 hover:bg-zinc-100/50 rounded-lg transition-colors">
                            <i class="ph ph-headset text-base text-zinc-400 group-hover:text-zinc-700 transition-colors"></i>
                            <span>Support</span>
                        </a>
                    </div>

                    <div class="border-t border-zinc-100 my-1"></div>

                    <!-- Log Out Action -->
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <button type="button" onclick="confirmLogout()" class="w-full text-left group flex items-center gap-2 px-3 py-1.5 text-[11.5px] font-semibold text-rose-600 hover:bg-rose-50 hover:text-rose-700 rounded-lg transition-colors">
                            <i class="ph ph-sign-out text-base text-rose-500"></i>
                            <span>Sign Out</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
