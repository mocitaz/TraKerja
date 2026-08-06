@php
    if (!function_exists('getAvatarStyleLocal')) {
        function getAvatarStyleLocal($name) {
            $char = strtoupper(substr($name ?? 'A', 0, 1));
            $colors = [
                'A' => ['#6366f1', '#a855f7'],
                'B' => ['#a855f7', '#ec4899'],
                'C' => ['#ec4899', '#f43f5e'],
                'D' => ['#f43f5e', '#ef4444'],
                'E' => ['#ef4444', '#f97316'],
                'F' => ['#f97316', '#f59e0b'],
                'G' => ['#f59e0b', '#eab308'],
                'H' => ['#eab308', '#84cc16'],
                'I' => ['#84cc16', '#22c55e'],
                'J' => ['#22c55e', '#10b981'],
                'K' => ['#10b981', '#14b8a6'],
                'L' => ['#14b8a6', '#06b6d4'],
                'M' => ['#06b6d4', '#0ea5e9'],
                'N' => ['#0ea5e9', '#3b82f6'],
                'O' => ['#3b82f6', '#6366f1'],
                'P' => ['#6366f1', '#8b5cf6'],
                'Q' => ['#8b5cf6', '#d946ef'],
                'R' => ['#d946ef', '#ec4899'],
                'S' => ['#ec4899', '#f43f5e'],
                'T' => ['#f43f5e', '#f97316'],
                'U' => ['#f97316', '#eab308'],
                'V' => ['#eab308', '#22c55e'],
                'W' => ['#22c55e', '#14b8a6'],
                'X' => ['#14b8a6', '#0ea5e9'],
                'Y' => ['#0ea5e9', '#6366f1'],
                'Z' => ['#6366f1', '#ec4899'],
            ];
            $pair = $colors[$char] ?? ['#71717a', '#52525b'];
            return "background: linear-gradient(135deg, {$pair[0]} 0%, {$pair[1]} 100%);";
        }
    }
@endphp
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
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
        
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
    <body class="text-zinc-800 antialiased bg-[#f6f6f9] overflow-hidden selection:bg-zinc-900 selection:text-white"
          x-data="{ 
              sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
              mobileSidebarOpen: false
          }"
          x-init="$watch('sidebarCollapsed', val => localStorage.setItem('sidebarCollapsed', val))"
          @keydown.escape="mobileSidebarOpen = false">
          
        <div class="h-screen flex overflow-hidden">
            <!-- Sidebar (Stripe/Linear Warm-Gray Minimalist Style) -->
            <aside :class="{ 
                        'w-16': sidebarCollapsed, 
                        'w-64': !sidebarCollapsed,
                        'translate-x-0': mobileSidebarOpen,
                        '-translate-x-full': !mobileSidebarOpen 
                   }"
                   class="fixed inset-y-0 left-0 z-[100] bg-[#f9f9fb] border-r border-zinc-200/80 transform transition-all duration-200 ease-in-out lg:translate-x-0 lg:static flex-shrink-0 flex flex-col select-none">
                
                <!-- Brand Header -->
                <div class="flex items-center h-16 px-5 border-b border-zinc-200/50 flex-shrink-0 relative">
                    <div class="flex items-center space-x-3 relative z-10 w-full" :class="sidebarCollapsed ? 'justify-center space-x-0' : ''">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center bg-zinc-900 flex-shrink-0 shadow-sm">
                            <img src="{{ asset('images/icon.png') }}" 
                                 alt="TraKerja" 
                                 class="h-5 w-5 object-contain filter invert"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <span class="text-white font-extrabold text-xs hidden" style="display: none;">T</span>
                        </div>
                        <div x-show="!sidebarCollapsed" class="flex flex-col truncate">
                            <span class="text-xs font-black text-zinc-900 tracking-tight leading-none mb-0.5">
                                TraKerja
                            </span>
                            <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest leading-none">
                                Workspace
                            </span>
                        </div>
                    </div>
                    
                    <button @click="mobileSidebarOpen = false" class="lg:hidden absolute right-2.5 p-1.5 text-zinc-400 hover:text-zinc-650 bg-zinc-100 rounded-lg transition z-20">
                        <i class="ph ph-x text-sm"></i>
                    </button>
                </div>

                <!-- Navigation List -->
                <nav class="flex-1 px-3 py-6 space-y-1.5 overflow-y-auto sidebar-scroll">
                    <!-- Section: System -->
                    <div x-show="!sidebarCollapsed" class="px-3 mb-2 mt-1">
                        <span class="text-[9px] font-sans font-bold text-zinc-400 uppercase tracking-widest block mb-1">Main Menu</span>
                    </div>

                    <!-- Dashboard -->
                    <a href="{{ route('admin.index') }}"
                       class="group relative flex items-center px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.index') ? 'bg-zinc-900 text-white shadow-3xs' : 'text-zinc-550 hover:bg-zinc-200/40 hover:text-zinc-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10 w-4.5 group-hover:text-zinc-950">
                            <i class="text-base {{ request()->routeIs('admin.index') ? 'ph-bold ph-squares-four text-white' : 'ph ph-squares-four text-zinc-450 group-hover:text-zinc-900' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" class="ml-2.5 truncate tracking-tight">Dashboard</span>
                        
                        <div x-show="sidebarCollapsed" class="absolute left-13 px-2 py-1 bg-zinc-900 text-white text-[9px] font-bold rounded shadow-md pointer-events-none z-50 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                            Dashboard
                        </div>
                    </a>

                    <!-- User Management -->
                    <a href="{{ route('admin.users') }}"
                       class="group relative flex items-center px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.users') ? 'bg-zinc-900 text-white shadow-3xs' : 'text-zinc-550 hover:bg-zinc-200/40 hover:text-zinc-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10 w-4.5 group-hover:text-zinc-950">
                            <i class="text-base {{ request()->routeIs('admin.users') ? 'ph-bold ph-users-three text-white' : 'ph ph-users-three text-zinc-450 group-hover:text-zinc-900' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" class="ml-2.5 truncate tracking-tight">User Management</span>
                        
                        <div x-show="sidebarCollapsed" class="absolute left-13 px-2 py-1 bg-zinc-900 text-white text-[9px] font-bold rounded shadow-md pointer-events-none z-50 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                            Users
                        </div>
                    </a>

                    <!-- Activity Logs -->
                    <a href="{{ route('admin.user-activities') }}"
                       class="group relative flex items-center px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.user-activities') ? 'bg-zinc-900 text-white shadow-3xs' : 'text-zinc-550 hover:bg-zinc-200/40 hover:text-zinc-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10 w-4.5 group-hover:text-zinc-950">
                            <i class="text-base {{ request()->routeIs('admin.user-activities') ? 'ph-bold ph-clock-counter-clockwise text-white' : 'ph ph-clock-counter-clockwise text-zinc-450 group-hover:text-zinc-900' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" class="ml-2.5 truncate tracking-tight">Activity Log</span>
                        
                        <div x-show="sidebarCollapsed" class="absolute left-13 px-2 py-1 bg-zinc-900 text-white text-[9px] font-bold rounded shadow-md pointer-events-none z-50 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                            Activity Log
                        </div>
                    </a>

                    <!-- Analytics -->
                    <a href="{{ route('admin.analytics') }}"
                       class="group relative flex items-center px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.analytics') ? 'bg-zinc-900 text-white shadow-3xs' : 'text-zinc-550 hover:bg-zinc-200/40 hover:text-zinc-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10 w-4.5 group-hover:text-zinc-950">
                            <i class="text-base {{ request()->routeIs('admin.analytics') ? 'ph-bold ph-chart-pie-slice text-white' : 'ph ph-chart-pie-slice text-zinc-450 group-hover:text-zinc-900' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" class="ml-2.5 truncate tracking-tight">Analytics</span>
                        
                        <div x-show="sidebarCollapsed" class="absolute left-13 px-2 py-1 bg-zinc-900 text-white text-[9px] font-bold rounded shadow-md pointer-events-none z-50 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                            Analytics
                        </div>
                    </a>

                    <!-- Divider -->
                    <div class="py-3">
                        <div class="border-t border-zinc-200/60 mx-2"></div>
                    </div>

                    <div x-show="!sidebarCollapsed" class="px-3 mb-2 mt-2">
                        <span class="text-[9px] font-sans font-bold text-zinc-400 uppercase tracking-widest block mb-1">Outreach</span>
                    </div>

                    <!-- Email Blast -->
                    <a href="{{ route('admin.email-blast') }}"
                       class="group relative flex items-center px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.email-blast*') ? 'bg-zinc-900 text-white shadow-3xs' : 'text-zinc-550 hover:bg-zinc-200/40 hover:text-zinc-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10 w-4.5 group-hover:text-zinc-950">
                            <i class="text-base {{ request()->routeIs('admin.email-blast*') ? 'ph-bold ph-paper-plane-tilt text-white' : 'ph ph-paper-plane-tilt text-zinc-450 group-hover:text-zinc-900' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" class="ml-2.5 truncate tracking-tight">Email Blast</span>
                        
                        <div x-show="sidebarCollapsed" class="absolute left-13 px-2 py-1 bg-zinc-900 text-white text-[9px] font-bold rounded shadow-md pointer-events-none z-50 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                            Email Blast
                        </div>
                    </a>

                    <!-- Feedbacks -->
                    <a href="{{ route('admin.feedbacks.index') }}"
                       class="group relative flex items-center px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.feedbacks*') ? 'bg-zinc-900 text-white shadow-3xs' : 'text-zinc-550 hover:bg-zinc-200/40 hover:text-zinc-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10 w-4.5 group-hover:text-zinc-950">
                            <i class="text-base {{ request()->routeIs('admin.feedbacks*') ? 'ph-bold ph-chat-circle-dots text-white' : 'ph ph-chat-circle-dots text-zinc-450 group-hover:text-zinc-900' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" class="ml-2.5 truncate tracking-tight">User Feedback</span>
                        
                        <div x-show="sidebarCollapsed" class="absolute left-13 px-2 py-1 bg-zinc-900 text-white text-[9px] font-bold rounded shadow-md pointer-events-none z-50 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                            User Feedback
                        </div>
                    </a>

                    <!-- User Survey -->
                    <a href="{{ route('admin.survey.index') }}"
                       class="group relative flex items-center px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.survey.index') ? 'bg-zinc-900 text-white shadow-3xs' : 'text-zinc-550 hover:bg-zinc-200/40 hover:text-zinc-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10 w-4.5 group-hover:text-zinc-950">
                            <i class="text-base {{ request()->routeIs('admin.survey.index') ? 'ph-bold ph-heart text-white' : 'ph ph-heart text-zinc-450 group-hover:text-zinc-900' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" class="ml-2.5 truncate tracking-tight">User Survey</span>
                        
                        <div x-show="sidebarCollapsed" class="absolute left-13 px-2 py-1 bg-zinc-900 text-white text-[9px] font-bold rounded shadow-md pointer-events-none z-50 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                            User Survey
                        </div>
                    </a>

                    <!-- Divider -->
                    <div class="py-3">
                        <div class="border-t border-zinc-200/60 mx-2"></div>
                    </div>

                    <div x-show="!sidebarCollapsed" class="px-3 mb-2 mt-2">
                        <span class="text-[9px] font-sans font-bold text-zinc-400 uppercase tracking-widest block mb-1">System Config</span>
                    </div>

                    <!-- Scraper Engine -->
                    <a href="{{ route('admin.scraper') }}"
                       class="group relative flex items-center px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.scraper') ? 'bg-zinc-900 text-white shadow-3xs' : 'text-zinc-555 hover:bg-zinc-200/40 hover:text-zinc-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10 w-4.5 group-hover:text-zinc-950">
                            <i class="text-base {{ request()->routeIs('admin.scraper') ? 'ph-bold ph-terminal-window text-white' : 'ph ph-terminal-window text-zinc-450 group-hover:text-zinc-900' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" class="ml-2.5 truncate tracking-tight">Scraper Engine</span>
                        
                        <div x-show="sidebarCollapsed" class="absolute left-13 px-2 py-1 bg-zinc-900 text-white text-[9px] font-bold rounded shadow-md pointer-events-none z-50 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                            Scraper Engine
                        </div>
                    </a>

                    <!-- Settings -->
                    <a href="{{ route('admin.settings') }}"
                       class="group relative flex items-center px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.settings') ? 'bg-zinc-900 text-white shadow-3xs' : 'text-zinc-555 hover:bg-zinc-200/40 hover:text-zinc-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10 w-4.5 group-hover:text-zinc-950">
                            <i class="text-base {{ request()->routeIs('admin.settings') ? 'ph-bold ph-gear-six text-white' : 'ph ph-gear-six text-zinc-450 group-hover:text-zinc-900' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" class="ml-2.5 truncate tracking-tight">Settings</span>
                        
                        <div x-show="sidebarCollapsed" class="absolute left-13 px-2 py-1 bg-zinc-900 text-white text-[9px] font-bold rounded shadow-md pointer-events-none z-50 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                            Settings
                        </div>
                    </a>
                </nav>
                
                <!-- Sidebar Footer profile (Floating Card Style) -->
                <div class="p-3 bg-[#fafafa]/50 border-t border-zinc-200/60 flex-shrink-0">
                    <div class="flex items-center rounded-xl p-2.5 bg-white border border-zinc-200 shadow-[0_2px_8px_rgba(0,0,0,0.02)] transition-colors"
                         :class="sidebarCollapsed ? 'justify-center p-1.5' : ''">
                        <div class="h-7 w-7 rounded-full overflow-hidden border border-zinc-200 flex-shrink-0 flex items-center justify-center text-white text-[10px] font-black relative"
                             style="{{ getAvatarStyleLocal($user?->name ?? 'A') }}">
                            @if($user && $user->logo)
                                <img src="{{ $user->avatar_url }}" 
                                     alt="Profile" 
                                     class="h-full w-full object-cover">
                            @endif
                            <span class="absolute pointer-events-none">{{ strtoupper(substr($user?->name ?? 'A', 0, 1)) }}</span>
                        </div>
                        
                        <div x-show="!sidebarCollapsed" class="ml-2.5 min-w-0 flex-1">
                            <p class="text-xs font-black text-zinc-900 truncate leading-tight">{{ $user?->name ?? 'Admin' }}</p>
                            <p class="text-[8px] font-mono font-bold text-zinc-400 truncate uppercase tracking-widest leading-none mt-0.8">Super Admin</p>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Sidebar Overlay (Mobile) -->
            <div x-show="mobileSidebarOpen" 
                 x-transition.opacity
                 @click="mobileSidebarOpen = false"
                 class="fixed inset-0 bg-zinc-900/20 backdrop-blur-[1px] z-[90] lg:hidden" style="display: none;"></div>

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
                <header class="bg-white/90 backdrop-blur-md border-b border-zinc-200/50 h-16 flex items-center justify-between px-6 z-[50] flex-shrink-0 sticky top-0">
                    <div class="flex items-center space-x-3.5">
                        <!-- Toggle Sidebar Button inside Topbar (Clean and Integrated) -->
                        <button @click="sidebarCollapsed = !sidebarCollapsed" 
                                class="hidden lg:flex items-center justify-center w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200 text-zinc-500 hover:bg-zinc-100 hover:text-zinc-900 transition-colors shadow-3xs">
                            <i class="ph ph-sidebar-simple text-sm"></i>
                        </button>
                        
                        <!-- Mobile Hamburger Button -->
                        <button @click="mobileSidebarOpen = true" 
                                class="lg:hidden flex items-center justify-center w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200 text-zinc-555 hover:bg-zinc-100 transition-colors shadow-3xs">
                            <i class="ph ph-list text-sm"></i>
                        </button>
                        
                        <!-- Path Breadcrumbs -->
                        <div class="hidden sm:flex items-center space-x-1.5 text-xs text-zinc-550 font-bold">
                            <span class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-widest leading-none">Admin Portal</span>
                            <span class="text-zinc-300 leading-none">/</span>
                            <span class="text-[9px] font-mono font-bold text-zinc-800 uppercase tracking-widest leading-none">{{ $pageTitle }}</span>
                        </div>
                        <h2 class="text-xs font-bold text-zinc-800 sm:hidden tracking-tight">{{ $pageTitle }}</h2>
                    </div>

                    <!-- Right Controls -->
                    <div class="flex items-center space-x-4">
                        <!-- Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = ! open" class="h-9 flex items-center gap-2 focus:outline-none bg-white hover:bg-zinc-50 px-3 rounded-xl border border-zinc-200/80 transition-all text-xs font-bold text-zinc-700 shadow-3xs">
                                <div class="w-6.5 h-6.5 rounded-full overflow-hidden border border-zinc-200 shrink-0 flex items-center justify-center text-white text-[9px] font-black relative"
                                     style="{{ getAvatarStyleLocal($user?->name ?? 'A') }}">
                                    @if($user && $user->logo)
                                        <img src="{{ $user->avatar_url }}" 
                                             alt="Profile" 
                                             class="h-full w-full object-cover">
                                    @endif
                                    <span class="absolute pointer-events-none">{{ strtoupper(substr($user?->name ?? 'A', 0, 1)) }}</span>
                                </div>
                                <span class="hidden sm:block truncate max-w-[110px]">{{ $user?->name ?? 'Admin' }}</span>
                                <i class="ph ph-caret-down text-[10px] text-zinc-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
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
                                 class="absolute right-0 top-full mt-2 w-52 bg-white rounded-xl border border-zinc-200 shadow-md py-1.5 focus:outline-none z-[110]"
                                 style="display: none;">
                                
                                <div class="px-4 py-2 border-b border-zinc-100 bg-zinc-50/50 mb-1.5 text-left">
                                    <p class="text-[8px] font-mono font-bold text-zinc-450 uppercase tracking-widest leading-none mb-1">Signed in as</p>
                                    <p class="text-xs font-bold text-zinc-750 truncate leading-none">{{ $user->email ?? 'admin@example.com' }}</p>
                                </div>
                                
                                <a href="{{ route('admin.payments') }}" class="flex items-center px-4 py-2 text-xs text-zinc-650 hover:bg-zinc-50 hover:text-zinc-950 rounded-lg mx-1.5 transition-colors font-bold">
                                    <i class="w-5 text-sm ph ph-credit-card text-zinc-450"></i>
                                    Payments Center
                                </a>
                                <a href="{{ route('admin.monetization') }}" class="flex items-center px-4 py-2 text-xs text-zinc-650 hover:bg-zinc-50 hover:text-zinc-950 rounded-lg mx-1.5 transition-colors font-bold">
                                    <i class="w-5 text-sm ph ph-coins text-zinc-450"></i>
                                    Monetization Control
                                </a>
                                
                                <div class="border-t border-zinc-100 my-1"></div>
                                
                                <button type="button" onclick="openLogoutModal()" class="w-full text-left flex items-center px-4 py-2 text-xs font-black text-red-650 hover:bg-red-50 hover:text-red-700 rounded-lg mx-1.5 transition-colors">
                                    <i class="ph ph-sign-out w-5 text-sm text-red-400"></i>
                                    Sign out
                                </button>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Workspace Scrollport -->
                <main class="flex-1 overflow-y-auto bg-[#f6f6f9]">
                    <div class="px-5 sm:px-8 py-8 max-w-[1400px] w-full mx-auto">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        <!-- Logout Modal -->
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
