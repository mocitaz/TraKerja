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
                elseif(request()->routeIs('survey.show'))   $pageTitle = 'User Survey & Feedback';
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
                <button @click="open = !open" class="flex items-center gap-2.5 px-2 py-1 rounded-lg hover:bg-zinc-200/50 transition-colors cursor-pointer focus:outline-none max-w-[200px] sm:max-w-[240px]">
                    <div class="w-7 h-7 rounded-full overflow-hidden border border-zinc-200 shrink-0 flex items-center justify-center bg-zinc-50">
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
                     class="absolute right-0 top-full mt-2 w-64 bg-white/95 backdrop-blur-md rounded-xl shadow-[0_20px_50px_rgba(0,0,0,0.08),0_1px_2px_rgba(0,0,0,0.02)] border border-zinc-200/50 z-50 text-left"
                     style="display: none;">

                    <!-- Rewards & Gamification Section -->
                    <div class="px-3.5 pt-3.5 pb-3 bg-zinc-50/60 border-b border-zinc-200/60 rounded-t-xl">
                        <div class="flex items-center justify-between">
                            <span class="text-[9px] font-bold text-zinc-400 uppercase tracking-wider leading-none">Account Rewards</span>
                            <span class="text-[8px] font-extrabold uppercase tracking-wider text-primary-650 bg-primary-50 px-1 py-0.2 rounded border border-primary-100/60 select-none">XP Boost</span>
                        </div>
                        <div class="mt-2.5">
                            <livewire:layout.gamification-widget />
                        </div>
                    </div>

                    <!-- Options list -->
                    <div class="p-1.5 space-y-0.5">
                        <!-- My Profile Link -->
                        <a href="{{ route('profile.edit') }}" wire:navigate class="group flex items-center justify-between px-2.5 py-2 rounded-lg text-xs font-semibold text-zinc-650 hover:text-zinc-950 hover:bg-zinc-50 transition-all duration-150">
                            <div class="flex items-center gap-2">
                                <i class="ph ph-user-circle text-base text-zinc-400 group-hover:text-zinc-600 transition-colors"></i>
                                <span>My Profile</span>
                            </div>
                            <i class="ph ph-caret-right text-[10px] text-zinc-300 opacity-0 group-hover:opacity-100 transition-all duration-150 transform group-hover:translate-x-0.5"></i>
                        </a>

                        <!-- Membership Option -->
                        @if(!Auth::user()->isPremium())
                        <a href="{{ route('payment.premium') }}" wire:navigate class="group flex items-center justify-between px-2.5 py-2 rounded-lg text-xs font-semibold text-primary-600 hover:text-primary-800 hover:bg-primary-50/30 transition-all duration-150">
                            <div class="flex items-center gap-2">
                                <i class="ph ph-crown text-sm text-primary-500"></i>
                                <span>Upgrade Premium</span>
                            </div>
                            <span class="text-[8px] font-black uppercase bg-primary-100 text-primary-850 px-1 py-0.2 rounded-sm select-none">Pro</span>
                        </a>
                        @else
                        <div class="flex items-center justify-between px-2.5 py-2 rounded-lg text-xs font-semibold text-amber-700 bg-amber-50/20 border border-amber-100/40 select-none">
                            <div class="flex items-center gap-2">
                                <i class="ph-fill ph-crown text-sm text-amber-500 animate-pulse"></i>
                                <span>Pro Member</span>
                            </div>
                            <span class="text-[8.5px] font-black uppercase bg-amber-100 text-amber-800 px-1.5 py-0.2 rounded-sm select-none">Pro</span>
                        </div>
                        @endif

                        <!-- Automation Link -->
                        <a href="{{ route('automation.index') }}" wire:navigate class="group flex items-center justify-between px-2.5 py-2 rounded-lg text-xs font-semibold text-zinc-650 hover:text-zinc-950 hover:bg-zinc-50 transition-all duration-150">
                            <div class="flex items-center gap-2">
                                <i class="ph ph-sliders text-base text-zinc-400 group-hover:text-zinc-600 transition-colors"></i>
                                <span>Automation</span>
                            </div>
                            <i class="ph ph-caret-right text-[10px] text-zinc-300 opacity-0 group-hover:opacity-100 transition-all duration-150 transform group-hover:translate-x-0.5"></i>
                        </a>

                        <!-- Support Link -->
                        <a href="{{ route('support.index') }}" wire:navigate class="group flex items-center justify-between px-2.5 py-2 rounded-lg text-xs font-semibold text-zinc-650 hover:text-zinc-950 hover:bg-zinc-50 transition-all duration-150">
                            <div class="flex items-center gap-2">
                                <i class="ph ph-headset text-base text-zinc-400 group-hover:text-zinc-600 transition-colors"></i>
                                <span>Support</span>
                            </div>
                            <i class="ph ph-caret-right text-[10px] text-zinc-300 opacity-0 group-hover:opacity-100 transition-all duration-150 transform group-hover:translate-x-0.5"></i>
                        </a>
                    </div>

                    <div class="h-[1px] bg-zinc-100 mx-2 my-1"></div>

                    <!-- Log Out Action -->
                    <div class="p-1.5 pt-0">
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <button type="button" onclick="confirmLogout()" class="w-full group flex items-center justify-between px-2.5 py-2 rounded-lg text-xs font-semibold text-rose-600 hover:bg-rose-50/50 hover:text-rose-700 transition-all duration-150">
                                <div class="flex items-center gap-2">
                                    <i class="ph ph-sign-out text-base text-rose-500"></i>
                                    <span>Sign Out</span>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
