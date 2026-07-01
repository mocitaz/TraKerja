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
                     class="absolute right-0 top-full mt-2.5 w-72 bg-white rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.12),0_1px_3px_rgba(0,0,0,0.02)] border border-zinc-200/80 z-50 text-left overflow-hidden"
                     style="display: none;">

                    <!-- User Profile & Status -->
                    <div class="px-4.5 py-4 bg-zinc-50/50 border-b border-zinc-150/50 flex items-center gap-3">
                        <!-- Avatar with gradient ring -->
                        <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-primary-500/10 shrink-0 flex items-center justify-center bg-zinc-100/80 shadow-sm relative">
                            @if(Auth::user()->logo)
                                <img src="{{ Auth::user()->avatar_url }}" alt="Profile Photo" class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full bg-gradient-to-br from-zinc-100 to-zinc-200/60 flex items-center justify-center">
                                    <span class="text-zinc-700 font-extrabold text-xs select-none">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-xs font-bold text-zinc-900 truncate leading-snug">{{ Auth::user()->name }}</h4>
                            <p class="text-[10px] font-medium text-zinc-400 truncate leading-none mt-0.5">{{ Auth::user()->email }}</p>
                        </div>
                    </div>

                    <!-- Rewards & Gamification Section -->
                    <div class="px-4.5 py-3.5 border-b border-zinc-150/40 bg-zinc-50/20">
                        <div class="flex items-center justify-between">
                            <span class="text-[9.5px] font-black text-zinc-400 uppercase tracking-widest leading-none">Account Rewards</span>
                            <span class="text-[8px] font-bold uppercase tracking-wider text-primary-650 bg-primary-50 px-1.5 py-0.2 rounded border border-primary-200/40 select-none">XP Boost</span>
                        </div>
                        <div class="mt-2.5">
                            <livewire:layout.gamification-widget />
                        </div>
                    </div>

                    <!-- Options list -->
                    <div class="p-2 space-y-1">
                        <!-- My Profile Link -->
                        <a href="{{ route('profile.edit') }}" wire:navigate class="group flex items-center justify-between px-3 py-2.5 text-[12px] font-semibold text-zinc-700 hover:text-zinc-950 hover:bg-zinc-50 border border-transparent hover:border-zinc-200/40 rounded-xl transition-all duration-150">
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-lg bg-zinc-100 group-hover:bg-zinc-200/50 flex items-center justify-center transition-colors">
                                    <i class="ph ph-user text-sm text-zinc-500 group-hover:text-zinc-700 transition-colors"></i>
                                </div>
                                <span>My Profile</span>
                            </div>
                            <i class="ph ph-caret-right text-[10px] text-zinc-400 opacity-0 group-hover:opacity-100 transition-all duration-150 transform group-hover:translate-x-0.5"></i>
                        </a>

                        <!-- Membership Card / Option -->
                        @if(!Auth::user()->isPremium())
                        <a href="{{ route('payment.premium') }}" wire:navigate class="group flex items-center justify-between px-3 py-2.5 text-[12px] font-bold text-primary-750 bg-primary-50/45 hover:bg-primary-50 border border-primary-200/40 hover:border-primary-200/80 rounded-xl transition-all duration-150">
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-lg bg-primary-100 group-hover:bg-primary-200/60 flex items-center justify-center transition-colors">
                                    <i class="ph-fill ph-crown text-sm text-primary-550"></i>
                                </div>
                                <span>Upgrade Premium</span>
                            </div>
                            <span class="text-[8.5px] font-bold uppercase bg-primary-100 text-primary-850 px-2 py-0.5 rounded-md shadow-3xs select-none">Pro</span>
                        </a>
                        @else
                        <div class="flex items-center justify-between px-3 py-2.5 text-[12px] font-bold text-amber-850 bg-amber-50/50 border border-amber-200/50 rounded-xl select-none">
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-lg bg-amber-100/60 flex items-center justify-center">
                                    <i class="ph-fill ph-crown text-sm text-amber-500 animate-pulse"></i>
                                </div>
                                <span>Pro Member</span>
                            </div>
                            <span class="text-[8.5px] font-black uppercase bg-amber-100 text-amber-900 px-2 py-0.5 rounded-md shadow-3xs">Pro</span>
                        </div>
                        @endif

                        <!-- Automation Link -->
                        <a href="{{ route('automation.index') }}" wire:navigate class="group flex items-center justify-between px-3 py-2.5 text-[12px] font-semibold text-zinc-700 hover:text-zinc-950 hover:bg-zinc-50 border border-transparent hover:border-zinc-200/40 rounded-xl transition-all duration-150">
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-lg bg-zinc-100 group-hover:bg-zinc-200/50 flex items-center justify-center transition-colors">
                                    <i class="ph ph-sliders text-sm text-zinc-500 group-hover:text-zinc-700 transition-colors"></i>
                                </div>
                                <span>Automation</span>
                            </div>
                            <i class="ph ph-caret-right text-[10px] text-zinc-400 opacity-0 group-hover:opacity-100 transition-all duration-150 transform group-hover:translate-x-0.5"></i>
                        </a>

                        <!-- Support Link -->
                        <a href="{{ route('support.index') }}" wire:navigate class="group flex items-center justify-between px-3 py-2.5 text-[12px] font-semibold text-zinc-700 hover:text-zinc-950 hover:bg-zinc-50 border border-transparent hover:border-zinc-200/40 rounded-xl transition-all duration-150">
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-lg bg-zinc-100 group-hover:bg-zinc-200/50 flex items-center justify-center transition-colors">
                                    <i class="ph ph-headset text-sm text-zinc-500 group-hover:text-zinc-700 transition-colors"></i>
                                </div>
                                <span>Support</span>
                            </div>
                            <i class="ph ph-caret-right text-[10px] text-zinc-400 opacity-0 group-hover:opacity-100 transition-all duration-150 transform group-hover:translate-x-0.5"></i>
                        </a>
                    </div>

                    <div class="h-[1px] bg-zinc-150/45 mx-3 my-1.5"></div>

                    <!-- Log Out Action -->
                    <div class="p-2 pt-0">
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <button type="button" onclick="confirmLogout()" class="w-full group flex items-center justify-between px-3 py-2.5 text-[12px] font-bold text-rose-600 hover:bg-rose-50/50 hover:text-rose-700 rounded-xl border border-transparent transition-all duration-150">
                                <div class="flex items-center gap-3">
                                    <div class="w-6 h-6 rounded-lg bg-rose-50/60 group-hover:bg-rose-100/50 flex items-center justify-center transition-colors">
                                        <i class="ph ph-sign-out text-sm text-rose-500"></i>
                                    </div>
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
