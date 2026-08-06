<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Admin</title>

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
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css">
        <script src="https://unpkg.com/@phosphor-icons/web"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        <style>
            [x-cloak] { display: none !important; }
            
            .custom-scrollbar::-webkit-scrollbar {
                width: 4px;
                height: 4px;
            }
            .custom-scrollbar::-webkit-scrollbar-track {
                background: transparent;
            }
            .custom-scrollbar::-webkit-scrollbar-thumb {
                background-color: rgba(228, 228, 231, 0.6);
                border-radius: 10px;
            }

            /* Snappy premium view transition */
            @media (prefers-reduced-motion: no-preference) {
                ::view-transition-old(root),
                ::view-transition-new(root) {
                    animation-duration: 180ms;
                    animation-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased text-zinc-900 bg-[#fafafa]">
        
        <div class="min-h-screen flex" x-data="{ sidebarOpen: false }">
            
            <!-- LEFT SIDEBAR (Desktop) -->
            <aside class="hidden lg:flex flex-col w-[260px] bg-zinc-950 border-r border-zinc-800 text-zinc-400 h-screen sticky top-0 shrink-0 select-none">
                <!-- Sidebar Header -->
                <div class="h-16 flex items-center px-6 border-b border-zinc-900 shrink-0">
                    <a href="{{ route('admin.index') }}" class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-zinc-800 border border-zinc-700 flex items-center justify-center text-white shadow-sm">
                            <i class="ph-bold ph-shield-check text-base"></i>
                        </div>
                        <div class="flex flex-col leading-none">
                            <span class="text-xs font-black tracking-widest text-white uppercase">TraKerja</span>
                            <span class="text-[9px] font-bold text-zinc-500 uppercase tracking-widest mt-0.5">Admin Console</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Menu -->
                <nav class="flex-1 px-4 py-6 space-y-7 overflow-y-auto custom-scrollbar">
                    
                    <!-- Main Menu Section -->
                    <div class="space-y-1.5">
                        <span class="px-3 text-[9px] font-mono font-bold text-zinc-600 uppercase tracking-widest block mb-2">Core Dashboard</span>
                        
                        <a href="{{ route('admin.index') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('admin.index') ? 'bg-zinc-800 text-white' : 'hover:bg-zinc-900/50 hover:text-zinc-200' }}">
                            <i class="ph-bold ph-chart-pie text-base {{ request()->routeIs('admin.index') ? 'text-white' : 'text-zinc-650' }}"></i>
                            Overview
                        </a>

                        <a href="{{ route('admin.users') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('admin.users') ? 'bg-zinc-800 text-white' : 'hover:bg-zinc-900/50 hover:text-zinc-200' }}">
                            <i class="ph-bold ph-users text-base {{ request()->routeIs('admin.users') ? 'text-white' : 'text-zinc-650' }}"></i>
                            User Manager
                        </a>

                        <a href="{{ route('admin.user-activities') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('admin.user-activities') ? 'bg-zinc-800 text-white' : 'hover:bg-zinc-900/50 hover:text-zinc-200' }}">
                            <i class="ph-bold ph-activity text-base {{ request()->routeIs('admin.user-activities') ? 'text-white' : 'text-zinc-650' }}"></i>
                            Activity Logs
                        </a>
                    </div>

                    <!-- Business & Finance -->
                    <div class="space-y-1.5">
                        <span class="px-3 text-[9px] font-mono font-bold text-zinc-600 uppercase tracking-widest block mb-2">Monetization</span>

                        <a href="{{ route('admin.monetization') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('admin.monetization') ? 'bg-zinc-800 text-white' : 'hover:bg-zinc-900/50 hover:text-zinc-200' }}">
                            <i class="ph-bold ph-toggle-left text-base {{ request()->routeIs('admin.monetization') ? 'text-white' : 'text-zinc-650' }}"></i>
                            Mode Settings
                        </a>

                        <a href="{{ route('admin.payments') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('admin.payments') ? 'bg-zinc-800 text-white' : 'hover:bg-zinc-900/50 hover:text-zinc-200' }}">
                            <i class="ph-bold ph-credit-card text-base {{ request()->routeIs('admin.payments') ? 'text-white' : 'text-zinc-650' }}"></i>
                            Payments Center
                        </a>

                        <a href="{{ route('admin.analytics') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('admin.analytics') ? 'bg-zinc-800 text-white' : 'hover:bg-zinc-900/50 hover:text-zinc-200' }}">
                            <i class="ph-bold ph-trend-up text-base {{ request()->routeIs('admin.analytics') ? 'text-white' : 'text-zinc-650' }}"></i>
                            Revenue Report
                        </a>
                    </div>

                    <!-- Communications & Support -->
                    <div class="space-y-1.5">
                        <span class="px-3 text-[9px] font-mono font-bold text-zinc-600 uppercase tracking-widest block mb-2">Communications</span>

                        <a href="{{ route('admin.email-blast') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('admin.email-blast*') ? 'bg-zinc-800 text-white' : 'hover:bg-zinc-900/50 hover:text-zinc-200' }}">
                            <i class="ph-bold ph-paper-plane-tilt text-base {{ request()->routeIs('admin.email-blast*') ? 'text-white' : 'text-zinc-650' }}"></i>
                            Email Blast
                        </a>

                        <a href="{{ route('admin.feedbacks.index') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('admin.feedbacks*') ? 'bg-zinc-800 text-white' : 'hover:bg-zinc-900/50 hover:text-zinc-200' }}">
                            <i class="ph-bold ph-chat-circle-dots text-base {{ request()->routeIs('admin.feedbacks*') ? 'text-white' : 'text-zinc-650' }}"></i>
                            User Feedbacks
                        </a>
                    </div>

                    <!-- System Config -->
                    <div class="space-y-1.5">
                        <span class="px-3 text-[9px] font-mono font-bold text-zinc-600 uppercase tracking-widest block mb-2">System Config</span>

                        <a href="{{ route('admin.integration-hub') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('admin.integration-hub') ? 'bg-zinc-800 text-white' : 'hover:bg-zinc-900/50 hover:text-zinc-200' }}">
                            <i class="ph-bold ph-plugs text-base {{ request()->routeIs('admin.integration-hub') ? 'text-white' : 'text-zinc-650' }}"></i>
                            Integration Hub
                        </a>

                        <a href="{{ route('admin.database-maintenance') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('admin.database-maintenance') ? 'bg-zinc-800 text-white' : 'hover:bg-zinc-900/50 hover:text-zinc-200' }}">
                            <i class="ph-bold ph-database text-base {{ request()->routeIs('admin.database-maintenance') ? 'text-white' : 'text-zinc-650' }}"></i>
                            Database Maintenance
                        </a>

                        <a href="{{ route('admin.settings') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('admin.settings') ? 'bg-zinc-800 text-white' : 'hover:bg-zinc-900/50 hover:text-zinc-200' }}">
                            <i class="ph-bold ph-gear-six text-base {{ request()->routeIs('admin.settings') ? 'text-white' : 'text-zinc-650' }}"></i>
                            Global Settings
                        </a>
                    </div>
                </nav>

                <!-- Sidebar Footer (Signed in as) -->
                <div class="p-4 border-t border-zinc-900 bg-zinc-950/80">
                    <div class="flex items-center gap-3 p-2 bg-zinc-900/50 rounded-xl border border-zinc-800">
                        <div class="w-8 h-8 rounded-full bg-zinc-800 flex items-center justify-center font-black text-xs text-white">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-bold text-zinc-200 truncate leading-tight">{{ Auth::user()->name }}</p>
                            <p class="text-[9px] text-zinc-500 font-semibold tracking-wide uppercase mt-0.5">Administrator</p>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- MOBILE SIDEBAR -->
            <div class="fixed inset-0 z-50 lg:hidden" x-show="sidebarOpen" x-cloak>
                <div class="fixed inset-0 bg-zinc-950/40 backdrop-blur-xs" @click="sidebarOpen = false"></div>
                <div class="relative flex flex-col max-w-[280px] w-full bg-zinc-950 text-zinc-400 h-full border-r border-zinc-800 p-4">
                    <div class="flex items-center justify-between pb-4 border-b border-zinc-900 mb-6">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-zinc-800 flex items-center justify-center text-white">
                                <i class="ph-bold ph-shield-check text-base"></i>
                            </div>
                            <span class="text-xs font-black tracking-widest text-white uppercase">TraKerja Admin</span>
                        </div>
                        <button type="button" @click="sidebarOpen = false" class="w-8 h-8 rounded-full bg-zinc-900 border border-zinc-800 flex items-center justify-center text-zinc-400 hover:text-white">
                            <i class="ph ph-x"></i>
                        </button>
                    </div>

                    <nav class="flex-1 space-y-6 overflow-y-auto custom-scrollbar">
                        <div class="space-y-1">
                            <span class="text-[9px] font-bold text-zinc-600 uppercase tracking-widest block mb-2 px-3">Menu</span>
                            <a href="{{ route('admin.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('admin.index') ? 'bg-zinc-800 text-white' : 'hover:bg-zinc-900/50 hover:text-zinc-200' }}">
                                <i class="ph-bold ph-chart-pie text-base"></i> Overview
                            </a>
                            <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('admin.users') ? 'bg-zinc-800 text-white' : 'hover:bg-zinc-900/50 hover:text-zinc-200' }}">
                                <i class="ph-bold ph-users text-base"></i> Users
                            </a>
                            <a href="{{ route('admin.user-activities') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('admin.user-activities') ? 'bg-zinc-800 text-white' : 'hover:bg-zinc-900/50 hover:text-zinc-200' }}">
                                <i class="ph-bold ph-activity text-base"></i> Activities
                            </a>
                            <a href="{{ route('admin.monetization') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('admin.monetization') ? 'bg-zinc-800 text-white' : 'hover:bg-zinc-900/50 hover:text-zinc-200' }}">
                                <i class="ph-bold ph-toggle-left text-base"></i> Monetization
                            </a>
                            <a href="{{ route('admin.payments') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('admin.payments') ? 'bg-zinc-800 text-white' : 'hover:bg-zinc-900/50 hover:text-zinc-200' }}">
                                <i class="ph-bold ph-credit-card text-base"></i> Payments
                            </a>
                            <a href="{{ route('admin.email-blast') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('admin.email-blast*') ? 'bg-zinc-800 text-white' : 'hover:bg-zinc-900/50 hover:text-zinc-200' }}">
                                <i class="ph-bold ph-paper-plane-tilt text-base"></i> Email Blast
                            </a>
                            <a href="{{ route('admin.feedbacks.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('admin.feedbacks*') ? 'bg-zinc-800 text-white' : 'hover:bg-zinc-900/50 hover:text-zinc-200' }}">
                                <i class="ph-bold ph-chat-circle-dots text-base"></i> Feedbacks
                            </a>
                        </div>
                    </nav>

                    <div class="mt-auto border-t border-zinc-900 pt-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-red-400 hover:text-red-300 font-bold text-xs">
                                <i class="ph-bold ph-sign-out text-base"></i> Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- RIGHT MAIN CONTAINER -->
            <div class="flex-1 flex flex-col min-w-0">
                
                <!-- TOP BAR -->
                <header class="h-16 border-b border-zinc-200/80 bg-white/70 backdrop-blur-md sticky top-0 z-40 flex items-center justify-between px-6 shrink-0">
                    <div class="flex items-center gap-3">
                        <!-- Mobile Hamburger Button -->
                        <button type="button" @click="sidebarOpen = true" class="lg:hidden w-8 h-8 rounded border border-zinc-200 flex items-center justify-center text-zinc-550 hover:bg-zinc-50">
                            <i class="ph-bold ph-list text-lg"></i>
                        </button>
                        
                        <!-- Page Context Breadcrumb (Will be filled by page titles) -->
                        <div class="flex items-center gap-2">
                            <span class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-widest">Workspace</span>
                            <span class="text-zinc-350 text-xs">/</span>
                            <span class="text-[9px] font-bold text-zinc-900 uppercase tracking-widest bg-zinc-100 border border-zinc-200 px-1.5 py-0.5 rounded">Admin Mode</span>
                        </div>
                    </div>

                    <!-- Right Controls -->
                    <div class="flex items-center gap-4">
                        
                        <!-- System Status Pulse -->
                        <div class="flex items-center gap-2 px-2.5 py-1 bg-zinc-50 border border-zinc-200/80 rounded-md text-[10px] font-bold text-zinc-650">
                            <span class="relative flex h-1.5 w-1.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                            </span>
                            Live Engine
                        </div>

                        <!-- Quick Profile Dropdown -->
                        <div class="relative" x-data="{ userMenu: false }">
                            <button type="button" @click="userMenu = !userMenu" class="flex items-center gap-2 font-bold text-xs text-zinc-700 hover:text-zinc-900 transition-colors">
                                <div class="w-7 h-7 rounded-full bg-zinc-100 border border-zinc-200 flex items-center justify-center font-black text-zinc-800">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                                <i class="ph ph-caret-down text-[10px]"></i>
                            </button>
                            <div x-show="userMenu" @click.outside="userMenu = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white border border-zinc-200 rounded-lg shadow-sm py-1 z-50 text-left">
                                <a href="{{ route('admin.settings') }}" class="block px-4 py-2 text-xs font-bold text-zinc-700 hover:bg-zinc-50">Settings</a>
                                <div class="border-t border-zinc-150 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-xs font-bold text-red-650 hover:bg-red-50">Log Out</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </header>

                <!-- INNER CONTENT WRAPPER -->
                <main class="flex-1 p-6 md:p-8">
                    {{ $slot }}
                </main>
            </div>
            
        </div>

        <script>
            // View Transition API integration with Livewire
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
