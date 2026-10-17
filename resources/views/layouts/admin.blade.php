<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __(':value1 - Admin', ['value1' => (config('app.name', 'Laravel'))]) }}</title>

        <!-- Favicon -->
        <link class="favicon" rel="icon" type="image/png" href="{{ asset('images/icon.png') }}?v=2">
        <link rel="apple-touch-icon" href="{{ asset('images/icon.png') }}?v=2">

        <!-- PWA Meta & Manifest -->
        <link rel="manifest" href="{{ asset('site.webmanifest') }}">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="TraKerja">

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700,800,900&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css">
        <script src="https://unpkg.com/@phosphor-icons/web"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        <style>
            [x-cloak] { display: none !important; }

            body {
                font-family: 'Outfit', sans-serif;
                background-color: #030303;
                background-image:
                    radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.05) 0px, transparent 50%),
                    radial-gradient(at 100% 100%, rgba(168, 85, 247, 0.05) 0px, transparent 50%),
                    radial-gradient(circle at 50% 50%, rgba(9, 9, 11, 0.5), #030303);
            }

            /* Custom Premium Scrollbar for Dark Mode */
            .custom-scrollbar::-webkit-scrollbar {
                width: 5px;
                height: 5px;
            }
            .custom-scrollbar::-webkit-scrollbar-track {
                background: transparent;
            }
            .custom-scrollbar::-webkit-scrollbar-thumb {
                background-color: rgba(63, 63, 70, 0.8);
                border-radius: 10px;
            }

            /* Glowing navigation effect */
            .nav-active-glow {
                background: linear-gradient(90deg, rgba(99, 102, 241, 0.15) 0%, rgba(168, 85, 247, 0.02) 100%);
                border-left: 3px solid #6366f1;
            }

            /* Snappy premium view transition */
            @media (prefers-reduced-motion: no-preference) {
                ::view-transition-old(root),
                ::view-transition-new(root) {
                    animation-duration: 200ms;
                    animation-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
                }
            }
        </style>
    </head>
    <body class="antialiased text-zinc-300">

        <div class="min-h-screen flex" x-data="{ sidebarOpen: false }">

            <!-- LEFT SIDEBAR (Desktop Obsidian Theme) -->
            <aside class="hidden lg:flex flex-col w-[270px] bg-[#08080a]/90 backdrop-blur-xl border-r border-zinc-900 text-zinc-400 h-screen sticky top-0 shrink-0 select-none">
                <!-- Sidebar Header -->
                <div class="h-16 flex items-center px-6 border-b border-zinc-900/80 shrink-0">
                    <a href="{{ route('admin.index') }}" class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 p-[1px] shadow-lg shadow-indigo-500/10">
                            <div class="w-full h-full bg-zinc-950 rounded-[11px] flex items-center justify-center text-white">
                                <i class="ph-bold ph-lightning text-sm text-indigo-400"></i>
                            </div>
                        </div>
                        <div class="flex flex-col leading-none">
                            <span class="text-sm font-extrabold tracking-wider text-white">{{ __('TraKerja') }}</span>
                            <span class="text-[9px] font-bold text-zinc-500 uppercase tracking-widest mt-0.5">{{ __('Control Terminal') }}</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Menu -->
                <nav class="flex-1 px-4 py-6 space-y-8 overflow-y-auto custom-scrollbar">

                    <!-- Section: Core -->
                    <div class="space-y-1">
                        <span class="px-3 text-[9px] font-bold text-zinc-650 uppercase tracking-widest block mb-2.5">{{ __('System') }}</span>

                        <a href="{{ route('admin.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.index') ? 'nav-active-glow text-white' : 'hover:bg-zinc-900/30 hover:text-zinc-200' }}">
                            <i class="ph-bold ph-grid-four text-base {{ request()->routeIs('admin.index') ? 'text-indigo-400' : 'text-zinc-600' }}"></i>
                            {{ __('Dashboard') }}
                        </a>

                        <a href="{{ route('admin.users') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.users') ? 'nav-active-glow text-white' : 'hover:bg-zinc-900/30 hover:text-zinc-200' }}">
                            <i class="ph-bold ph-users-three text-base {{ request()->routeIs('admin.users') ? 'text-indigo-400' : 'text-zinc-600' }}"></i>
                            {{ __('User Management') }}
                        </a>

                        <a href="{{ route('admin.user-activities') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.user-activities') ? 'nav-active-glow text-white' : 'hover:bg-zinc-900/30 hover:text-zinc-200' }}">
                            <i class="ph-bold ph-activity text-base {{ request()->routeIs('admin.user-activities') ? 'text-indigo-400' : 'text-zinc-600' }}"></i>
                            {{ __('System Audit Logs') }}
                        </a>
                    </div>

                    <!-- Section: Finance -->
                    <div class="space-y-1">
                        <span class="px-3 text-[9px] font-bold text-zinc-650 uppercase tracking-widest block mb-2.5">{{ __('Monetize') }}</span>

                        <a href="{{ route('admin.monetization') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.monetization') ? 'nav-active-glow text-white' : 'hover:bg-zinc-900/30 hover:text-zinc-200' }}">
                            <i class="ph-bold ph-sparkles text-base {{ request()->routeIs('admin.monetization') ? 'text-indigo-400' : 'text-zinc-600' }}"></i>
                            {{ __('Monetize Modes') }}
                        </a>

                        <a href="{{ route('admin.payments') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.payments') ? 'nav-active-glow text-white' : 'hover:bg-zinc-900/30 hover:text-zinc-200' }}">
                            <i class="ph-bold ph-credit-card text-base {{ request()->routeIs('admin.payments') ? 'text-indigo-400' : 'text-zinc-600' }}"></i>
                            {{ __('Transaction Center') }}
                        </a>

                        <a href="{{ route('admin.analytics') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.analytics') ? 'nav-active-glow text-white' : 'hover:bg-zinc-900/30 hover:text-zinc-200' }}">
                            <i class="ph-bold ph-chart-line-up text-base {{ request()->routeIs('admin.analytics') ? 'text-indigo-400' : 'text-zinc-600' }}"></i>
                            {{ __('Revenue Matrix') }}
                        </a>
                    </div>

                    <!-- Section: Support -->
                    <div class="space-y-1">
                        <span class="px-3 text-[9px] font-bold text-zinc-650 uppercase tracking-widest block mb-2.5">{{ __('Communications') }}</span>

                        <a href="{{ route('admin.email-blast') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.email-blast*') ? 'nav-active-glow text-white' : 'hover:bg-zinc-900/30 hover:text-zinc-200' }}">
                            <i class="ph-bold ph-paper-plane-tilt text-base {{ request()->routeIs('admin.email-blast*') ? 'text-indigo-400' : 'text-zinc-600' }}"></i>
                            {{ __('Broadcast Blast') }}
                        </a>

                        <a href="{{ route('admin.feedbacks.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.feedbacks*') ? 'nav-active-glow text-white' : 'hover:bg-zinc-900/30 hover:text-zinc-200' }}">
                            <i class="ph-bold ph-chat-centered-text text-base {{ request()->routeIs('admin.feedbacks*') ? 'text-indigo-400' : 'text-zinc-600' }}"></i>
                            {{ __('User Feedbacks') }}
                        </a>
                    </div>

                    <!-- Section: Infrastructure -->
                    <div class="space-y-1">
                        <span class="px-3 text-[9px] font-bold text-zinc-650 uppercase tracking-widest block mb-2.5">{{ __('Infrastructure') }}</span>

                        <a href="{{ route('admin.integration-hub') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.integration-hub') ? 'nav-active-glow text-white' : 'hover:bg-zinc-900/30 hover:text-zinc-200' }}">
                            <i class="ph-bold ph-plugs-connected text-base {{ request()->routeIs('admin.integration-hub') ? 'text-indigo-400' : 'text-zinc-600' }}"></i>
                            {{ __('Integrations') }}
                        </a>

                        <a href="{{ route('admin.database-maintenance') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.database-maintenance') ? 'nav-active-glow text-white' : 'hover:bg-zinc-900/30 hover:text-zinc-200' }}">
                            <i class="ph-bold ph-database text-base {{ request()->routeIs('admin.database-maintenance') ? 'text-indigo-400' : 'text-zinc-600' }}"></i>
                            {{ __('Database Engine') }}
                        </a>

                        <a href="{{ route('admin.settings') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.settings') ? 'nav-active-glow text-white' : 'hover:bg-zinc-900/30 hover:text-zinc-200' }}">
                            <i class="ph-bold ph-sliders text-base {{ request()->routeIs('admin.settings') ? 'text-indigo-400' : 'text-zinc-600' }}"></i>
                            {{ __('Terminal Settings') }}
                        </a>
                    </div>
                </nav>

                <!-- Sidebar Footer -->
                <div class="p-4 border-t border-zinc-900 bg-[#060608]">
                    <div class="flex items-center gap-3 p-2 bg-zinc-900/30 rounded-xl border border-zinc-800/60">
                        <div class="w-8 h-8 rounded-lg bg-zinc-800 flex items-center justify-center font-bold text-xs text-white">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-bold text-zinc-100 truncate leading-tight">{{ Auth::user()->name }}</p>
                            <p class="text-[8px] text-zinc-500 font-bold tracking-widest uppercase mt-0.5">{{ __('Admin Profile') }}</p>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- MOBILE SIDEBAR -->
            <div class="fixed inset-0 z-50 lg:hidden" x-show="sidebarOpen" x-cloak>
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="sidebarOpen = false"></div>
                <div class="relative flex flex-col max-w-[280px] w-full bg-[#08080a] text-zinc-400 h-full border-r border-zinc-900 p-4">
                    <div class="flex items-center justify-between pb-4 border-b border-zinc-900 mb-6">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-zinc-800 flex items-center justify-center text-white">
                                <i class="ph-bold ph-lightning text-base text-indigo-400"></i>
                            </div>
                            <span class="text-xs font-black tracking-widest text-white uppercase">{{ __('TraKerja Admin') }}</span>
                        </div>
                        <button type="button" @click="sidebarOpen = false" class="w-8 h-8 rounded-full bg-zinc-900 border border-zinc-800 flex items-center justify-center text-zinc-400 hover:text-white">
                            <i class="ph ph-x"></i>
                        </button>
                    </div>

                    <nav class="flex-1 space-y-6 overflow-y-auto custom-scrollbar">
                        <div class="space-y-1">
                            <span class="text-[9px] font-bold text-zinc-600 uppercase tracking-widest block mb-2 px-3">{{ __('Menu') }}</span>
                            <a href="{{ route('admin.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('admin.index') ? 'bg-zinc-850 text-white' : 'hover:bg-zinc-900/50' }}">
                                <i class="ph-bold ph-grid-four text-base"></i> {{ __('Overview') }}
                            </a>
                            <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('admin.users') ? 'bg-zinc-850 text-white' : 'hover:bg-zinc-900/50' }}">
                                <i class="ph-bold ph-users text-base"></i> {{ __('Users') }}
                            </a>
                            <a href="{{ route('admin.monetization') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('admin.monetization') ? 'bg-zinc-850 text-white' : 'hover:bg-zinc-900/50' }}">
                                <i class="ph-bold ph-sparkles text-base"></i> {{ __('Monetization') }}
                            </a>
                            <a href="{{ route('admin.payments') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('admin.payments') ? 'bg-zinc-850 text-white' : 'hover:bg-zinc-900/50' }}">
                                <i class="ph-bold ph-credit-card text-base"></i> {{ __('Payments') }}
                            </a>
                        </div>
                    </nav>

                    <div class="mt-auto border-t border-zinc-900 pt-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-red-400 hover:text-red-300 font-bold text-xs">
                                <i class="ph-bold ph-sign-out text-base"></i> {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- RIGHT MAIN CONTAINER (Glow Obsidian Style) -->
            <div class="flex-1 flex flex-col min-w-0 bg-[#050507]">

                <!-- TOP BAR -->
                <header class="h-16 border-b border-zinc-900/80 bg-[#050507]/60 backdrop-blur-md sticky top-0 z-40 flex items-center justify-between px-6 shrink-0 select-none">
                    <div class="flex items-center gap-3">
                        <button type="button" @click="sidebarOpen = true" class="lg:hidden w-8 h-8 rounded-lg border border-zinc-800 flex items-center justify-center text-zinc-400 hover:bg-zinc-900">
                            <i class="ph-bold ph-list text-lg"></i>
                        </button>

                        <div class="flex items-center gap-2">
                            <span class="text-[9px] font-mono font-bold text-zinc-500 uppercase tracking-widest">{{ __('Environment') }}</span>
                            <span class="text-zinc-700 text-xs">/</span>
                            <span class="text-[9px] font-bold text-indigo-400 bg-indigo-500/10 border border-indigo-500/20 px-2 py-0.5 rounded-full uppercase tracking-wider">{{ __('Terminal Active') }}</span>
                        </div>
                    </div>

                    <!-- Right Controls -->
                    <div class="flex items-center gap-4">

                        <!-- User Profile Dropdown -->
                        <div class="relative" x-data="{ userMenu: false }">
                            <button type="button" @click="userMenu = !userMenu" class="flex items-center gap-2 font-bold text-xs text-zinc-300 hover:text-white transition">
                                <div class="w-7 h-7 rounded-full bg-zinc-900 border border-zinc-800 flex items-center justify-center font-bold text-zinc-300">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                                <i class="ph ph-caret-down text-[10px] text-zinc-550"></i>
                            </button>
                            <div x-show="userMenu" @click.outside="userMenu = false" x-cloak class="absolute right-0 mt-2 w-48 bg-zinc-950 border border-zinc-900 rounded-xl shadow-xl py-1.5 z-50 text-left">
                                <a href="{{ route('admin.settings') }}" class="block px-4 py-2 text-xs font-bold text-zinc-300 hover:bg-zinc-900">{{ __('Settings') }}</a>
                                <div class="border-t border-zinc-900 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-xs font-bold text-red-400 hover:bg-red-950/30">{{ __('Log Out') }}</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </header>

                <!-- INNER CONTENT WRAPPER -->
                <main class="flex-1 p-6 md:p-8 overflow-y-auto custom-scrollbar">
                    {{ $slot }}
                </main>
            </div>

        </div>

        <script>
            document.addEventListener('livewire:navigating', (ev) => {
                if (!document.startViewTransition || !ev.detail || typeof ev.detail.beforeDomUpdate !== 'function') return;

                ev.detail.beforeDomUpdate((navigation) => {
                    return new Promise((resolve) => {
                        document.startViewTransition(async () => {
                            resolve();
                            await navigation.complete;
                        });
                    });
                });
            });
        </script>

        @livewireScripts
    </body>
</html>
