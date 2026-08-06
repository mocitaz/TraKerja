<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TraKerja') }} - Admin</title>
        
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        
        <!-- Phosphor Icons -->
        <script src="https://unpkg.com/@phosphor-icons/web"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            
            /* Clean Notion-style Custom Scrollbar */
            ::-webkit-scrollbar { width: 5px; height: 5px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #e4e4e7; border-radius: 9px; }
            ::-webkit-scrollbar-thumb:hover { background: #d4d4d8; }
            
            .sidebar-scroll::-webkit-scrollbar { width: 3px; }
            .sidebar-scroll::-webkit-scrollbar-thumb { background: transparent; }
            .sidebar-scroll:hover::-webkit-scrollbar-thumb { background: #e4e4e7; }

            /* Livewire Navigation Progress Bar Custom Style */
            #nprogress .bar {
                background: #6366f1 !important;
                height: 2px !important;
            }
            #nprogress .peg {
                box-shadow: 0 0 10px #6366f1, 0 0 5px #6366f1 !important;
            }
            #nprogress .spinner-icon {
                border-top-color: #6366f1 !important;
                border-left-color: #6366f1 !important;
            }

            /* Clean Notion-style Table Pagination Overrides */
            .notion-pagination p {
                display: none !important;
            }
            
            .notion-pagination span.relative.z-0.inline-flex,
            .notion-pagination span[aria-current="page"],
            .notion-pagination span[aria-disabled="true"],
            .notion-pagination nav span:not([aria-current="page"]):not([aria-disabled="true"]),
            .notion-pagination nav > div:first-child {
                border: none !important;
                background: transparent !important;
                background-color: transparent !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .notion-pagination a,
            .notion-pagination span[aria-current="page"] > span,
            .notion-pagination span[aria-disabled="true"] > span {
                background: #ffffff !important;
                background-color: #ffffff !important;
                color: #3f3f46 !important;
                border: 1px solid #e4e4e7 !important;
                padding: 0 !important;
                margin: 0 2.5px !important;
                font-size: 11px !important;
                font-family: monospace !important;
                font-weight: 550 !important;
                border-radius: 4px !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                min-width: 28px !important;
                height: 28px !important;
                box-shadow: none !important;
                text-shadow: none !important;
                transition: all 0.15s !important;
            }

            .notion-pagination span[aria-current="page"] > span {
                background: #efefed !important;
                background-color: #efefed !important;
                color: #18181b !important;
                font-weight: 700 !important;
                border-color: #d4d4d8 !important;
            }

            .notion-pagination a:hover {
                background: #f4f4f5 !important;
                background-color: #f4f4f5 !important;
                color: #18181b !important;
                border-color: #d4d4d8 !important;
            }

            .notion-pagination span[aria-disabled="true"] > span {
                opacity: 0.45 !important;
                background: #ffffff !important;
                background-color: #ffffff !important;
                color: #a1a1aa !important;
                cursor: not-allowed !important;
                border-color: #e4e4e7 !important;
            }
        </style>
    </head>
    <body class="text-zinc-800 antialiased bg-[#fafafa] overflow-hidden selection:bg-zinc-900 selection:text-white"
          x-data="{ 
              sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
              mobileSidebarOpen: false
          }"
          x-init="$watch('sidebarCollapsed', val => localStorage.setItem('sidebarCollapsed', val))"
          @keydown.escape="mobileSidebarOpen = false">
          
        <div class="h-screen flex overflow-hidden">
            <!-- Sidebar (Notion-iOS Style: Pure white with light borders and clean indicators) -->
            <aside :class="{ 
                        'w-16': sidebarCollapsed, 
                        'w-60': !sidebarCollapsed,
                        'translate-x-0': mobileSidebarOpen,
                        '-translate-x-full': !mobileSidebarOpen 
                   }"
                   class="fixed inset-y-0 left-0 z-[100] bg-white border-r border-zinc-200/80 transform transition-all duration-150 ease-in-out lg:translate-x-0 lg:static flex-shrink-0 flex flex-col select-none">
                
                <!-- Collapse Arrow Toggle -->
                <button @click="sidebarCollapsed = !sidebarCollapsed" 
                        class="absolute -right-2.5 top-10 bg-white border border-zinc-200 text-zinc-400 hover:text-zinc-700 rounded-full w-5 h-5 hidden lg:flex items-center justify-center transition shadow-3xs z-50">
                    <i class="ph ph-caret-left text-[9px]" :class="sidebarCollapsed ? 'rotate-180' : ''"></i>
                </button>

                <!-- Workspace / Brand Header -->
                <div class="flex items-center h-14 px-4 border-b border-zinc-150 flex-shrink-0 relative">
                    <div class="flex items-center space-x-2.5 relative z-10 w-full" :class="sidebarCollapsed ? 'justify-center space-x-0' : ''">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center bg-indigo-50 border border-indigo-100 flex-shrink-0 shadow-3xs">
                            <img src="{{ asset('images/icon.png') }}" 
                                 alt="TraKerja" 
                                 class="h-4.5 w-4.5 object-contain"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <span class="text-indigo-600 font-extrabold text-xs hidden" style="display: none;">T</span>
                        </div>
                        <div x-show="!sidebarCollapsed" class="flex flex-col truncate">
                            <span class="text-xs font-black text-zinc-900 tracking-tight leading-none mb-0.5">
                                TraKerja
                            </span>
                            <span class="text-[8px] font-mono font-bold text-zinc-450 uppercase tracking-widest leading-none">
                                Workspace
                            </span>
                        </div>
                    </div>
                    
                    <button @click="mobileSidebarOpen = false" class="lg:hidden absolute right-2.5 p-1 text-zinc-400 hover:text-zinc-650 bg-zinc-100 rounded transition z-20">
                        <i class="ph ph-x text-sm"></i>
                    </button>
                </div>

                <!-- Navigation List -->
                <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto sidebar-scroll">
                    <!-- Section: System -->
                    <div x-show="!sidebarCollapsed" class="px-2.5 mb-1.5 mt-1">
                        <span class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-widest">Main Menu</span>
                    </div>

                    <!-- Dashboard -->
                    <a href="{{ route('admin.index') }}"
                       class="group relative flex items-center px-2.5 py-2 rounded-lg text-xs font-bold transition-colors {{ request()->routeIs('admin.index') ? 'bg-zinc-100 text-zinc-900 font-black' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10 w-4.5 text-zinc-450 group-hover:text-zinc-900">
                            <i class="text-base {{ request()->routeIs('admin.index') ? 'ph-bold ph-squares-four text-zinc-800' : 'ph ph-squares-four' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" class="ml-2.5 truncate tracking-tight">Dashboard</span>
                        
                        <div x-show="sidebarCollapsed" class="absolute left-11 px-2 py-1 bg-zinc-900 text-white text-[9px] font-bold rounded shadow-md pointer-events-none z-50 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                            Dashboard
                        </div>
                    </a>

                    <!-- User Management -->
                    <a href="{{ route('admin.users') }}"
                       class="group relative flex items-center px-2.5 py-2 rounded-lg text-xs font-bold transition-colors {{ request()->routeIs('admin.users') ? 'bg-zinc-100 text-zinc-900 font-black' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10 w-4.5 text-zinc-450 group-hover:text-zinc-900">
                            <i class="text-base {{ request()->routeIs('admin.users') ? 'ph-bold ph-users-three text-zinc-800' : 'ph ph-users-three' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" class="ml-2.5 truncate tracking-tight">User Management</span>
                        
                        <div x-show="sidebarCollapsed" class="absolute left-11 px-2 py-1 bg-zinc-900 text-white text-[9px] font-bold rounded shadow-md pointer-events-none z-50 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                            Users
                        </div>
                    </a>

                    <!-- Activity Logs -->
                    <a href="{{ route('admin.user-activities') }}"
                       class="group relative flex items-center px-2.5 py-2 rounded-lg text-xs font-bold transition-colors {{ request()->routeIs('admin.user-activities') ? 'bg-zinc-100 text-zinc-900 font-black' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10 w-4.5 text-zinc-450 group-hover:text-zinc-900">
                            <i class="text-base {{ request()->routeIs('admin.user-activities') ? 'ph-bold ph-clock-counter-clockwise text-zinc-800' : 'ph ph-clock-counter-clockwise' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" class="ml-2.5 truncate tracking-tight">Activity Log</span>
                        
                        <div x-show="sidebarCollapsed" class="absolute left-11 px-2 py-1 bg-zinc-900 text-white text-[9px] font-bold rounded shadow-md pointer-events-none z-50 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                            Activity Log
                        </div>
                    </a>

                    <!-- Analytics -->
                    <a href="{{ route('admin.analytics') }}"
                       class="group relative flex items-center px-2.5 py-2 rounded-lg text-xs font-bold transition-colors {{ request()->routeIs('admin.analytics') ? 'bg-zinc-100 text-zinc-900 font-black' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10 w-4.5 text-zinc-450 group-hover:text-zinc-900">
                            <i class="text-base {{ request()->routeIs('admin.analytics') ? 'ph-bold ph-chart-pie-slice text-zinc-800' : 'ph ph-chart-pie-slice' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" class="ml-2.5 truncate tracking-tight">Analytics</span>
                        
                        <div x-show="sidebarCollapsed" class="absolute left-11 px-2 py-1 bg-zinc-900 text-white text-[9px] font-bold rounded shadow-md pointer-events-none z-50 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                            Analytics
                        </div>
                    </a>

                    <!-- Divider -->
                    <div class="py-2">
                        <div class="border-t border-zinc-200/60"></div>
                    </div>

                    <div x-show="!sidebarCollapsed" class="px-2.5 mb-1.5">
                        <span class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-widest">Outreach</span>
                    </div>

                    <!-- Email Blast -->
                    <a href="{{ route('admin.email-blast') }}"
                       class="group relative flex items-center px-2.5 py-2 rounded-lg text-xs font-bold transition-colors {{ request()->routeIs('admin.email-blast*') ? 'bg-zinc-100 text-zinc-900 font-black' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10 w-4.5 text-zinc-450 group-hover:text-zinc-900">
                            <i class="text-base {{ request()->routeIs('admin.email-blast*') ? 'ph-bold ph-paper-plane-tilt text-zinc-800' : 'ph ph-paper-plane-tilt' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" class="ml-2.5 truncate tracking-tight">Email Blast</span>
                        
                        <div x-show="sidebarCollapsed" class="absolute left-11 px-2 py-1 bg-zinc-900 text-white text-[9px] font-bold rounded shadow-md pointer-events-none z-50 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                            Email Blast
                        </div>
                    </a>

                    <!-- Feedbacks -->
                    <a href="{{ route('admin.feedbacks.index') }}"
                       class="group relative flex items-center px-2.5 py-2 rounded-lg text-xs font-bold transition-colors {{ request()->routeIs('admin.feedbacks*') ? 'bg-zinc-100 text-zinc-900 font-black' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10 w-4.5 text-zinc-450 group-hover:text-zinc-900">
                            <i class="text-base {{ request()->routeIs('admin.feedbacks*') ? 'ph-bold ph-chat-circle-dots text-zinc-800' : 'ph ph-chat-circle-dots' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" class="ml-2.5 truncate tracking-tight">User Feedback</span>
                        
                        <div x-show="sidebarCollapsed" class="absolute left-11 px-2 py-1 bg-zinc-900 text-white text-[9px] font-bold rounded shadow-md pointer-events-none z-50 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                            User Feedback
                        </div>
                    </a>

                    <!-- User Survey -->
                    <a href="{{ route('admin.survey.index') }}"
                       class="group relative flex items-center px-2.5 py-2 rounded-lg text-xs font-bold transition-colors {{ request()->routeIs('admin.survey.index') ? 'bg-zinc-100 text-zinc-900 font-black' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10 w-4.5 text-zinc-450 group-hover:text-zinc-900">
                            <i class="text-base {{ request()->routeIs('admin.survey.index') ? 'ph-bold ph-heart text-zinc-800' : 'ph ph-heart' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" class="ml-2.5 truncate tracking-tight">User Survey</span>
                        
                        <div x-show="sidebarCollapsed" class="absolute left-11 px-2 py-1 bg-zinc-900 text-white text-[9px] font-bold rounded shadow-md pointer-events-none z-50 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                            User Survey
                        </div>
                    </a>

                    <!-- Divider -->
                    <div class="py-2">
                        <div class="border-t border-zinc-200/60"></div>
                    </div>

                    <div x-show="!sidebarCollapsed" class="px-2.5 mb-1.5">
                        <span class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-widest">System Config</span>
                    </div>

                    <!-- Scraper Engine -->
                    <a href="{{ route('admin.scraper') }}"
                       class="group relative flex items-center px-2.5 py-2 rounded-lg text-xs font-bold transition-colors {{ request()->routeIs('admin.scraper') ? 'bg-zinc-100 text-zinc-900 font-black' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10 w-4.5 text-zinc-450 group-hover:text-zinc-900">
                            <i class="text-base {{ request()->routeIs('admin.scraper') ? 'ph-bold ph-terminal-window text-zinc-800' : 'ph ph-terminal-window' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" class="ml-2.5 truncate tracking-tight">Scraper Engine</span>
                        
                        <div x-show="sidebarCollapsed" class="absolute left-11 px-2 py-1 bg-zinc-900 text-white text-[9px] font-bold rounded shadow-md pointer-events-none z-50 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                            Scraper Engine
                        </div>
                    </a>

                    <!-- Settings -->
                    <a href="{{ route('admin.settings') }}"
                       class="group relative flex items-center px-2.5 py-2 rounded-lg text-xs font-bold transition-colors {{ request()->routeIs('admin.settings') ? 'bg-zinc-100 text-zinc-900 font-black' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10 w-4.5 text-zinc-450 group-hover:text-zinc-900">
                            <i class="text-base {{ request()->routeIs('admin.settings') ? 'ph-bold ph-gear-six text-zinc-800' : 'ph ph-gear-six' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" class="ml-2.5 truncate tracking-tight">Settings</span>
                        
                        <div x-show="sidebarCollapsed" class="absolute left-11 px-2 py-1 bg-zinc-900 text-white text-[9px] font-bold rounded shadow-md pointer-events-none z-50 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                            Settings
                        </div>
                    </a>
                </nav>
                
                <!-- Sidebar Footer profile -->
                <div class="p-2.5 border-t border-zinc-150 bg-zinc-50/50">
                    <div class="flex items-center rounded-lg p-2 cursor-pointer hover:bg-zinc-100 transition-colors"
                         :class="sidebarCollapsed ? 'justify-center' : ''">
                        @php $user = Auth::user(); @endphp
                        @if($user && $user->logo)
                            <img src="{{ $user->avatar_url }}" 
                                 alt="Profile" 
                                 class="h-6 w-6 rounded object-cover flex-shrink-0 border border-zinc-200">
                        @else
                            <div class="h-6 w-6 bg-zinc-100 rounded-md flex items-center justify-center flex-shrink-0 border border-zinc-200 text-zinc-700 font-black text-[10px]">
                                {{ substr($user->name ?? 'A', 0, 1) }}
                            </div>
                        @endif
                        
                        <div x-show="!sidebarCollapsed" class="ml-2 min-w-0 flex-1">
                            <p class="text-xs font-bold text-zinc-800 truncate leading-tight">{{ $user->name ?? 'Admin' }}</p>
                            <p class="text-[8px] font-mono font-bold text-zinc-405 truncate uppercase tracking-widest leading-none mt-0.5">Super Admin</p>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Sidebar Overlay (Mobile) -->
            <div x-show="mobileSidebarOpen" 
                 x-transition.opacity
                 @click="mobileSidebarOpen = false"
                 class="fixed inset-0 bg-zinc-900/25 backdrop-blur-[1px] z-[90] lg:hidden" style="display: none;"></div>

            <!-- Workspace Scrollport Container -->
            <div class="flex-1 flex flex-col min-w-0 relative">
                @php
                    $pageTitle = 'Dashboard';
                    if(request()->routeIs('admin.users')) $pageTitle = 'User Management';
                    elseif(request()->routeIs('admin.user-activities')) $pageTitle = 'Activity Log';
                    elseif(request()->routeIs('admin.analytics')) $pageTitle = 'Analytics';
                    elseif(request()->routeIs('admin.email-blast*')) $pageTitle = 'Email Blast';
                    elseif(request()->routeIs('admin.feedbacks*')) $pageTitle = 'User Feedback';
                    elseif(request()->routeIs('admin.payments*')) $pageTitle = 'Payments';
                    elseif(request()->routeIs('admin.monetization')) $pageTitle = 'Monetization';
                    elseif(request()->routeIs('admin.integration-hub')) $pageTitle = 'Integration Hub';
                    elseif(request()->routeIs('admin.database-maintenance')) $pageTitle = 'Database & Storage';
                    elseif(request()->routeIs('admin.survey.index')) $pageTitle = 'User Survey & Feedback';
                @endphp
                
                <!-- Notion Top bar -->
                <header class="bg-white/80 backdrop-blur-md border-b border-zinc-200/60 h-14 flex items-center justify-between px-5 z-[50] flex-shrink-0 sticky top-0">
                    <div class="flex items-center">
                        <button @click="mobileSidebarOpen = true" class="lg:hidden w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200 text-zinc-500 hover:bg-zinc-100 transition-colors flex items-center justify-center mr-3">
                            <i class="ph ph-list text-sm"></i>
                        </button>
                        
                        <!-- Path Breadcrumbs -->
                        <div class="hidden sm:flex items-center space-x-1.5 text-xs text-zinc-500 font-bold">
                            <span class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-widest">Admin Portal</span>
                            <span class="text-zinc-300">/</span>
                            <span class="text-[10px] font-mono font-bold text-zinc-800 uppercase tracking-widest">{{ $pageTitle }}</span>
                        </div>
                        <h2 class="text-xs font-bold text-zinc-800 sm:hidden tracking-tight">{{ $pageTitle }}</h2>
                    </div>

                    <!-- Right Controls -->
                    <div class="flex items-center space-x-3.5">
                        
                        <!-- System Operational Node -->
                        <div class="flex items-center gap-2 px-3 py-1 bg-zinc-50 border border-zinc-200 rounded-lg text-[10px] font-bold text-zinc-650">
                            <span class="relative flex h-1.5 w-1.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                            </span>
                            Live Engine
                        </div>

                        <!-- Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = ! open" class="h-8 flex items-center gap-1.5 focus:outline-none bg-white hover:bg-zinc-50 px-2.5 rounded-lg border border-zinc-200 transition-all text-xs font-bold text-zinc-700 shadow-none">
                                @if($user && $user->logo)
                                    <img src="{{ $user->avatar_url }}" 
                                         alt="Profile" 
                                         class="h-5.5 w-5.5 rounded object-cover shrink-0 border border-zinc-200">
                                @else
                                    <div class="h-5.5 w-5.5 bg-zinc-100 border border-zinc-250 rounded-md flex items-center justify-center text-zinc-700 font-black text-[9px] shrink-0">
                                        {{ substr($user->name ?? 'A', 0, 1) }}
                                    </div>
                                @endif
                                <span class="hidden sm:block truncate max-w-[100px]">{{ $user->name ?? 'Admin' }}</span>
                                <i class="ph ph-caret-down text-[9px] text-zinc-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-75"
                                 x-transition:enter-start="opacity-0 scale-98"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-98"
                                 @click.away="open = false"
                                 class="absolute right-0 top-full mt-1.5 w-52 bg-white rounded-xl border border-zinc-200 shadow-md py-1.5 focus:outline-none z-[110]"
                                 style="display: none;">
                                
                                <div class="px-3.5 py-2 border-b border-zinc-100 bg-zinc-50/50 mb-1.5 text-left">
                                    <p class="text-[8px] font-mono font-bold text-zinc-450 uppercase tracking-widest leading-none mb-1">Signed in as</p>
                                    <p class="text-xs font-bold text-zinc-750 truncate leading-none">{{ $user->email ?? 'admin@example.com' }}</p>
                                </div>
                                
                                <a href="{{ route('admin.payments') }}" class="flex items-center px-3.5 py-1.8 text-xs text-zinc-650 hover:bg-zinc-50 hover:text-zinc-950 rounded-lg mx-1.5 transition-colors font-bold">
                                    <i class="w-5 text-sm ph ph-credit-card text-zinc-400"></i>
                                    Payments Center
                                </a>
                                <a href="{{ route('admin.monetization') }}" class="flex items-center px-3.5 py-1.8 text-xs text-zinc-650 hover:bg-zinc-50 hover:text-zinc-950 rounded-lg mx-1.5 transition-colors font-bold">
                                    <i class="w-5 text-sm ph ph-coins text-zinc-400"></i>
                                    Monetization Control
                                </a>
                                
                                <div class="border-t border-zinc-100 my-1"></div>
                                
                                <button type="button" onclick="openLogoutModal()" class="w-full text-left flex items-center px-3.5 py-1.8 text-xs font-black text-red-600 hover:bg-red-50 hover:text-red-700 rounded-lg mx-1.5 transition-colors">
                                    <i class="ph ph-sign-out w-5 text-sm text-red-400"></i>
                                    Sign out
                                </button>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Workspace Scrollport -->
                <main class="flex-1 overflow-y-auto bg-[#fafafa]">
                    <div class="px-4 sm:px-6 py-6 max-w-[1400px] w-full mx-auto">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        <!-- Notion Logout Modal -->
        <div id="logoutModal" class="fixed inset-0 bg-zinc-950/20 backdrop-blur-[1px] hidden z-[100] flex items-center justify-center p-4">
            <div class="bg-white rounded-xl max-w-xs w-full transform transition-all duration-150 scale-98 border border-zinc-200 shadow-md" id="logoutModalContent">
                <div class="p-5 text-center">
                    <h3 class="text-sm font-bold text-zinc-900">Sign Out</h3>
                    <p class="text-xs text-zinc-500 mt-1 leading-relaxed">Yakin anda mau keluar dari akun?</p>
                    
                    <div class="mt-5 flex gap-2">
                        <a href="{{ route('logout.force') }}" onclick="prepareLogout()"
                           class="flex-1 h-8 flex items-center justify-center bg-red-650 hover:bg-red-700 text-white rounded-lg text-xs font-semibold transition-colors focus:outline-none shadow-none text-center">
                            Ya, Logout
                        </a>
                        <button type="button" onclick="closeLogoutModal()" 
                                class="flex-1 h-8 flex items-center justify-center bg-white border border-zinc-250 text-zinc-650 hover:bg-zinc-50 rounded-lg text-xs font-semibold transition-colors focus:outline-none shadow-none">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Logout Modal Helpers
            function openLogoutModal() {
                const modal = document.getElementById('logoutModal');
                const modalContent = document.getElementById('logoutModalContent');
                
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
                
                setTimeout(() => {
                    modalContent.classList.remove('scale-98');
                    modalContent.classList.add('scale-100');
                }, 10);
            }

            function closeLogoutModal() {
                const modal = document.getElementById('logoutModal');
                const modalContent = document.getElementById('logoutModalContent');
                
                modalContent.classList.remove('scale-100');
                modalContent.classList.add('scale-98');
                
                setTimeout(() => {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }, 150);
            }

            document.getElementById('logoutModal')?.addEventListener('click', function(e) {
                if (e.target.id === 'logoutModal') {
                    closeLogoutModal();
                }
            });

            function prepareLogout() {
                try { closeLogoutModal(); } catch (_) {}
                const originalConfirm = window.confirm;
                window.confirm = function() { return false; };
                setTimeout(function() { window.confirm = originalConfirm; }, 8000);
            }
        </script>
        
        @include('components.toast-notification')
        @livewireScripts
    </body>
</html>
