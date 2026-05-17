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
        
        <!-- Phosphor Icons (Premium Modern Icons) -->
        <script src="https://unpkg.com/@phosphor-icons/web"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            
            /* Custom Scrollbar */
            ::-webkit-scrollbar { width: 6px; height: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
            ::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
            
            .sidebar-scroll::-webkit-scrollbar { width: 4px; }
            .sidebar-scroll::-webkit-scrollbar-thumb { background: #f1f5f9; }
            .sidebar-scroll:hover::-webkit-scrollbar-thumb { background: #e2e8f0; }
        </style>
    </head>
    <body class="text-slate-800 antialiased bg-[#F8FAFC] overflow-hidden selection:bg-primary-500 selection:text-white"
          x-data="{ 
              sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
              mobileSidebarOpen: false
          }"
          x-init="$watch('sidebarCollapsed', val => localStorage.setItem('sidebarCollapsed', val))"
          @keydown.escape="mobileSidebarOpen = false">
          
        <div class="h-screen flex overflow-hidden">
            <!-- Sidebar -->
            <aside :class="{ 
                        'w-20': sidebarCollapsed, 
                        'w-72': !sidebarCollapsed,
                        'translate-x-0': mobileSidebarOpen,
                        '-translate-x-full': !mobileSidebarOpen 
                   }"
                   class="fixed inset-y-0 left-0 z-[60] bg-white border-r border-slate-200/80 transform transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] lg:translate-x-0 lg:static flex-shrink-0 flex flex-col shadow-[4px_0_24px_rgba(0,0,0,0.02)]">
                
                <!-- Desktop Collapse Button -->
                <button @click="sidebarCollapsed = !sidebarCollapsed" 
                        class="absolute -right-3.5 top-20 bg-white border border-slate-200 text-slate-500 rounded-full w-7 h-7 hidden lg:flex items-center justify-center hover:bg-slate-50 hover:text-primary-600 transition-all duration-300 z-50 shadow-sm focus:outline-none">
                    <i class="ph ph-caret-left text-[11px] transition-transform duration-300" :class="sidebarCollapsed ? 'rotate-180' : ''"></i>
                </button>

                <!-- Logo & Brand -->
                <div class="flex items-center h-[72px] px-6 border-b border-slate-100 flex-shrink-0 relative overflow-hidden bg-white/50 backdrop-blur-sm">
                    <div class="flex items-center space-x-3 relative z-10 w-full" :class="sidebarCollapsed ? 'justify-center space-x-0' : ''">
                        <div class="relative flex-shrink-0 bg-primary-50 p-1.5 rounded-xl border border-primary-100 transition-all duration-300">
                            <img src="{{ asset('images/icon.png') }}" 
                                 alt="TraKerja Logo" 
                                 class="h-7 w-7 object-contain"
                                 onerror="this.style.display='none';">
                        </div>
                        <div x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="flex flex-col truncate">
                            <span class="text-lg font-extrabold text-slate-800 tracking-tight leading-none mb-0.5">
                                TraKerja
                            </span>
                            <span class="text-[9px] font-bold text-primary-500 uppercase tracking-widest">
                                Admin Portal
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
                    <div x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="px-2 mb-3 mt-2">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Main Menu</span>
                    </div>

                    <a href="{{ route('admin.index') }}"
                       class="group relative flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 overflow-hidden {{ request()->routeIs('admin.index') ? 'bg-primary-50 text-primary-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        @if(request()->routeIs('admin.index'))
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-primary-600 rounded-r-full"></div>
                        @endif
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10" :class="sidebarCollapsed ? '' : 'w-8'">
                            <i class="text-[1.25rem] transition-transform duration-300 group-hover:scale-110 {{ request()->routeIs('admin.index') ? 'ph-fill ph-squares-four text-primary-600' : 'ph ph-squares-four' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="ml-3 truncate">Dashboard</span>
                        
                        <!-- Tooltip -->
                        <div x-show="sidebarCollapsed" class="absolute left-14 px-3 py-1.5 bg-slate-800 text-white text-xs font-bold rounded-md shadow-xl opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity duration-300 z-50 whitespace-nowrap">
                            Dashboard
                            <div class="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent border-r-slate-800"></div>
                        </div>
                    </a>

                    <a href="{{ route('admin.users') }}"
                       class="group relative flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 overflow-hidden {{ request()->routeIs('admin.users') ? 'bg-primary-50 text-primary-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        @if(request()->routeIs('admin.users'))
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-primary-600 rounded-r-full"></div>
                        @endif
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10" :class="sidebarCollapsed ? '' : 'w-8'">
                            <i class="text-[1.25rem] transition-transform duration-300 group-hover:scale-110 {{ request()->routeIs('admin.users') ? 'ph-fill ph-users-three text-primary-600' : 'ph ph-users-three' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="ml-3 truncate">User Management</span>
                        
                        <!-- Tooltip -->
                        <div x-show="sidebarCollapsed" class="absolute left-14 px-3 py-1.5 bg-slate-800 text-white text-xs font-bold rounded-md shadow-xl opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity duration-300 z-50 whitespace-nowrap">
                            Users
                            <div class="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent border-r-slate-800"></div>
                        </div>
                    </a>

                    <a href="{{ route('admin.analytics') }}"
                       class="group relative flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 overflow-hidden {{ request()->routeIs('admin.analytics') ? 'bg-primary-50 text-primary-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        @if(request()->routeIs('admin.analytics'))
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-primary-600 rounded-r-full"></div>
                        @endif
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10" :class="sidebarCollapsed ? '' : 'w-8'">
                            <i class="text-[1.25rem] transition-transform duration-300 group-hover:scale-110 {{ request()->routeIs('admin.analytics') ? 'ph-fill ph-chart-pie-slice text-primary-600' : 'ph ph-chart-pie-slice' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="ml-3 truncate">Analytics</span>
                        
                        <!-- Tooltip -->
                        <div x-show="sidebarCollapsed" class="absolute left-14 px-3 py-1.5 bg-slate-800 text-white text-xs font-bold rounded-md shadow-xl opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity duration-300 z-50 whitespace-nowrap">
                            Analytics
                            <div class="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent border-r-slate-800"></div>
                        </div>
                    </a>

                    <!-- Section Divider -->
                    <div class="py-3">
                        <div class="border-t border-slate-100"></div>
                    </div>

                    <div x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="px-2 mb-3">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Outreach</span>
                    </div>

                    <a href="{{ route('admin.email-blast') }}"
                       class="group relative flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 overflow-hidden {{ request()->routeIs('admin.email-blast*') ? 'bg-primary-50 text-primary-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        @if(request()->routeIs('admin.email-blast*'))
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-primary-600 rounded-r-full"></div>
                        @endif
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10" :class="sidebarCollapsed ? '' : 'w-8'">
                            <i class="text-[1.25rem] transition-transform duration-300 group-hover:scale-110 {{ request()->routeIs('admin.email-blast*') ? 'ph-fill ph-paper-plane-tilt text-primary-600' : 'ph ph-paper-plane-tilt' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="ml-3 truncate">Email Blast</span>
                        
                        <!-- Tooltip -->
                        <div x-show="sidebarCollapsed" class="absolute left-14 px-3 py-1.5 bg-slate-800 text-white text-xs font-bold rounded-md shadow-xl opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity duration-300 z-50 whitespace-nowrap">
                            Email Blast
                            <div class="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent border-r-slate-800"></div>
                        </div>
                    </a>

                    <a href="{{ route('admin.feedbacks.index') }}"
                       class="group relative flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 overflow-hidden {{ request()->routeIs('admin.feedbacks*') ? 'bg-primary-50 text-primary-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        @if(request()->routeIs('admin.feedbacks*'))
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-primary-600 rounded-r-full"></div>
                        @endif
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10" :class="sidebarCollapsed ? '' : 'w-8'">
                            <i class="text-[1.25rem] transition-transform duration-300 group-hover:scale-110 {{ request()->routeIs('admin.feedbacks*') ? 'ph-fill ph-chat-circle-dots text-primary-600' : 'ph ph-chat-circle-dots' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="ml-3 truncate">User Feedback</span>
                        
                        <!-- Tooltip -->
                        <div x-show="sidebarCollapsed" class="absolute left-14 px-3 py-1.5 bg-slate-800 text-white text-xs font-bold rounded-md shadow-xl opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity duration-300 z-50 whitespace-nowrap">
                            User Feedback
                            <div class="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent border-r-slate-800"></div>
                        </div>
                    </a>

                    <!-- System Section -->
                    <div class="py-3">
                        <div class="border-t border-slate-100"></div>
                    </div>

                    <div x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="px-2 mb-3">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">System</span>
                    </div>

                    <a href="{{ route('admin.integration-hub') }}"
                       class="group relative flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 overflow-hidden {{ request()->routeIs('admin.integration-hub') ? 'bg-primary-50 text-primary-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        @if(request()->routeIs('admin.integration-hub'))
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-primary-600 rounded-r-full"></div>
                        @endif
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10" :class="sidebarCollapsed ? '' : 'w-8'">
                            <i class="text-[1.25rem] transition-transform duration-300 group-hover:scale-110 {{ request()->routeIs('admin.integration-hub') ? 'ph-fill ph-plugs text-primary-600' : 'ph ph-plugs' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="ml-3 truncate">Integration</span>
                        
                        <!-- Tooltip -->
                        <div x-show="sidebarCollapsed" class="absolute left-14 px-3 py-1.5 bg-slate-800 text-white text-xs font-bold rounded-md shadow-xl opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity duration-300 z-50 whitespace-nowrap">
                            Integration
                            <div class="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent border-r-slate-800"></div>
                        </div>
                    </a>

                    <a href="{{ route('admin.database-maintenance') }}"
                       class="group relative flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 overflow-hidden {{ request()->routeIs('admin.database-maintenance') ? 'bg-primary-50 text-primary-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        @if(request()->routeIs('admin.database-maintenance'))
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-primary-600 rounded-r-full"></div>
                        @endif
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10" :class="sidebarCollapsed ? '' : 'w-8'">
                            <i class="text-[1.25rem] transition-transform duration-300 group-hover:scale-110 {{ request()->routeIs('admin.database-maintenance') ? 'ph-fill ph-database text-primary-600' : 'ph ph-database' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="ml-3 truncate">Database</span>
                        
                        <!-- Tooltip -->
                        <div x-show="sidebarCollapsed" class="absolute left-14 px-3 py-1.5 bg-slate-800 text-white text-xs font-bold rounded-md shadow-xl opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity duration-300 z-50 whitespace-nowrap">
                            Database
                            <div class="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent border-r-slate-800"></div>
                        </div>
                    </a>

                    <a href="{{ route('admin.settings') }}"
                       class="group relative flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 overflow-hidden {{ request()->routeIs('admin.settings') ? 'bg-primary-50 text-primary-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        @if(request()->routeIs('admin.settings'))
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-primary-600 rounded-r-full"></div>
                        @endif
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10" :class="sidebarCollapsed ? '' : 'w-8'">
                            <i class="text-[1.25rem] transition-transform duration-300 group-hover:scale-110 {{ request()->routeIs('admin.settings') ? 'ph-fill ph-gear-six text-primary-600' : 'ph ph-gear-six' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="ml-3 truncate">Settings</span>
                        
                        <!-- Tooltip -->
                        <div x-show="sidebarCollapsed" class="absolute left-14 px-3 py-1.5 bg-slate-800 text-white text-xs font-bold rounded-md shadow-xl opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity duration-300 z-50 whitespace-nowrap">
                            Settings
                            <div class="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent border-r-slate-800"></div>
                        </div>
                    </a>
                </nav>
                
                <!-- Bottom Profile Summary -->
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    <div class="flex items-center bg-white rounded-xl p-2 cursor-pointer hover:shadow-sm border border-slate-200/60 transition-all duration-200"
                         :class="sidebarCollapsed ? 'justify-center' : ''">
                        @php $user = Auth::user(); @endphp
                        @if($user && $user->logo)
                            <img src="{{ $user->avatar_url }}" 
                                 alt="Profile" 
                                 class="h-8 w-8 rounded-lg object-cover ring-2 ring-white flex-shrink-0 shadow-sm">
                        @else
                            <div class="h-8 w-8 bg-primary-100 rounded-lg flex items-center justify-center ring-2 ring-white flex-shrink-0 shadow-sm">
                                <span class="text-primary-700 font-bold text-xs">{{ substr($user->name ?? 'A', 0, 1) }}</span>
                            </div>
                        @endif
                        
                        <div x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="ml-3 min-w-0 flex-1">
                            <p class="text-sm font-bold text-slate-800 truncate">{{ $user->name ?? 'Admin' }}</p>
                            <p class="text-[10px] font-semibold text-slate-400 truncate uppercase tracking-widest mt-0.5">Super Admin</p>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Sidebar Overlay (Mobile) -->
            <div x-show="mobileSidebarOpen" 
                 x-transition.opacity.duration.300ms
                 @click="mobileSidebarOpen = false"
                 class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 lg:hidden" style="display: none;"></div>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 relative">
                @php
                    $pageTitle = 'Dashboard';
                    if(request()->routeIs('admin.users')) $pageTitle = 'User Management';
                    elseif(request()->routeIs('admin.analytics')) $pageTitle = 'Analytics';
                    elseif(request()->routeIs('admin.email-blast*')) $pageTitle = 'Email Blast';
                    elseif(request()->routeIs('admin.feedbacks*')) $pageTitle = 'User Feedback';
                    elseif(request()->routeIs('admin.payments*')) $pageTitle = 'Payments';
                    elseif(request()->routeIs('admin.monetization')) $pageTitle = 'Monetization';
                    elseif(request()->routeIs('admin.integration-hub')) $pageTitle = 'Integration Hub';
                    elseif(request()->routeIs('admin.database-maintenance')) $pageTitle = 'Database & Storage';
                @endphp
                
                <!-- Top Bar -->
                <header class="bg-white/80 backdrop-blur-xl border-b border-slate-200/60 h-[72px] flex items-center justify-between px-4 sm:px-8 z-40 flex-shrink-0 sticky top-0">
                    <div class="flex items-center">
                        <button @click="mobileSidebarOpen = true" class="lg:hidden p-2.5 mr-3 rounded-xl bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 hover:text-primary-600 transition-colors shadow-sm">
                            <i class="ph ph-list text-lg"></i>
                        </button>
                        <!-- Dynamic Page Title / Breadcrumb -->
                        <div class="hidden sm:flex items-center space-x-2.5 text-sm font-bold">
                            <span class="text-slate-400">Admin Portal</span>
                            <i class="ph-fill ph-caret-right text-slate-300 text-[10px]"></i>
                            <span class="text-primary-600 tracking-tight bg-primary-50 px-2.5 py-1 rounded-md">{{ $pageTitle }}</span>
                        </div>
                        <h2 class="text-lg font-extrabold text-slate-800 sm:hidden tracking-tight">{{ $pageTitle }}</h2>
                    </div>

                    <!-- Right Side Actions -->
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="relative p-2.5 text-slate-400 hover:text-primary-600 transition-colors rounded-full hover:bg-primary-50 focus:outline-none">
                            <i class="ph ph-bell text-xl"></i>
                            <span class="absolute top-2 right-2.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                        </button>
                        
                        <div class="h-8 w-px bg-slate-200"></div>

                        <!-- User Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = ! open" class="flex items-center space-x-3 focus:outline-none bg-white border border-slate-200/60 pl-1.5 pr-3 py-1.5 rounded-full hover:shadow-sm hover:border-primary-200 transition-all duration-300 group">
                                @if($user && $user->logo)
                                    <img src="{{ $user->avatar_url }}" 
                                         alt="Profile Photo" 
                                         class="h-8 w-8 rounded-full object-cover">
                                @else
                                    <div class="h-8 w-8 bg-primary-100 rounded-full flex items-center justify-center shadow-inner">
                                        <span class="text-primary-700 font-bold text-xs">{{ substr($user->name ?? 'A', 0, 1) }}</span>
                                    </div>
                                @endif
                                <span class="font-bold text-sm text-slate-700 hidden sm:block group-hover:text-primary-600 transition-colors">{{ $user->name ?? 'Admin' }}</span>
                                <i class="ph ph-caret-down text-[10px] text-slate-400 hidden sm:block transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                                 @click.away="open = false"
                                 class="absolute right-0 top-full mt-3 w-64 bg-white rounded-2xl shadow-[0_12px_40px_rgba(0,0,0,0.08)] border border-slate-100 py-2 focus:outline-none z-50"
                                 style="display: none;">
                                
                                <div class="px-5 py-3 border-b border-slate-100 bg-slate-50/50 mb-1">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Signed in as</p>
                                    <p class="text-sm font-bold text-slate-800 truncate">{{ $user->email ?? 'admin@example.com' }}</p>
                                </div>
                                
                                <a href="{{ route('admin.payments') }}" class="flex items-center px-5 py-2.5 text-sm font-semibold transition-colors {{ request()->routeIs('admin.payments*') ? 'text-primary-600 bg-primary-50/50' : 'text-slate-600 hover:text-primary-600 hover:bg-slate-50' }}">
                                    <i class="w-6 text-lg {{ request()->routeIs('admin.payments*') ? 'ph-fill ph-credit-card text-primary-500' : 'ph ph-credit-card text-slate-400 group-hover:text-primary-500' }} transition-colors"></i>
                                    Payments
                                </a>
                                <a href="{{ route('admin.monetization') }}" class="flex items-center px-5 py-2.5 text-sm font-semibold transition-colors {{ request()->routeIs('admin.monetization') ? 'text-primary-600 bg-primary-50/50' : 'text-slate-600 hover:text-primary-600 hover:bg-slate-50' }}">
                                    <i class="w-6 text-lg {{ request()->routeIs('admin.monetization') ? 'ph-fill ph-coins text-primary-500' : 'ph ph-coins text-slate-400 group-hover:text-primary-500' }} transition-colors"></i>
                                    Monetization
                                </a>
                                
                                <div class="border-t border-slate-100 my-1"></div>

                                <a href="{{ route('admin.integration-hub') }}" class="flex items-center px-5 py-2.5 text-sm font-semibold transition-colors {{ request()->routeIs('admin.integration-hub') ? 'text-primary-600 bg-primary-50/50' : 'text-slate-600 hover:text-primary-600 hover:bg-slate-50' }}">
                                    <i class="w-6 text-lg {{ request()->routeIs('admin.integration-hub') ? 'ph-fill ph-plugs text-primary-500' : 'ph ph-plugs text-slate-400 group-hover:text-primary-500' }} transition-colors"></i>
                                    Integration Hub
                                </a>
                                <a href="{{ route('admin.database-maintenance') }}" class="flex items-center px-5 py-2.5 text-sm font-semibold transition-colors {{ request()->routeIs('admin.database-maintenance') ? 'text-primary-600 bg-primary-50/50' : 'text-slate-600 hover:text-primary-600 hover:bg-slate-50' }}">
                                    <i class="w-6 text-lg {{ request()->routeIs('admin.database-maintenance') ? 'ph-fill ph-database text-primary-500' : 'ph ph-database text-slate-400 group-hover:text-primary-500' }} transition-colors"></i>
                                    Database & Storage
                                </a>
                                
                                <div class="border-t border-slate-100 my-1"></div>
                                
                                <button type="button" onclick="openLogoutModal()" class="w-full text-left flex items-center px-5 py-2.5 text-sm font-bold text-red-600 hover:bg-red-50 transition-colors">
                                    <i class="ph ph-sign-out w-6 text-lg"></i>
                                    Sign out
                                </button>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto">
                    <div class="px-4 sm:px-8 py-5">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        <!-- Logout Confirmation Modal -->
        <div id="logoutModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden z-[100] flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 border border-slate-100" id="logoutModalContent">
                <div class="p-8">
                    <!-- Header -->
                    <div class="flex flex-col items-center text-center mb-6">
                        <div class="w-16 h-16 bg-red-50 border-4 border-white shadow-sm rounded-full flex items-center justify-center mb-4">
                            <i class="ph-fill ph-sign-out text-2xl text-red-500"></i>
                        </div>
                        <h3 class="text-2xl font-extrabold text-slate-900 mb-1">Sign Out</h3>
                        <p class="text-sm font-medium text-slate-500">Yakin anda mau keluar dari akun?</p>
                    </div>
                    
                    <!-- Content -->
                    <div class="mb-8 text-center">
                        <p class="text-slate-600 text-sm leading-relaxed">
                            Sesi anda sebagai <span class="font-bold text-slate-900">{{ Auth::user()->name ?? 'Admin' }}</span> akan diakhiri. Pastikan semua perubahan sudah disimpan.
                        </p>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex space-x-3">
                        <button onclick="closeLogoutModal()" 
                                class="flex-1 px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-700 hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-200 transition-all duration-200 shadow-sm">
                            Batal
                        </button>
                        <a href="{{ route('logout.force') }}" onclick="prepareLogout()"
                           class="flex-1 px-4 py-3 bg-red-600 text-white rounded-xl text-sm font-bold hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 text-center shadow-sm hover:shadow-md">
                            Ya, Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Logout Modal Functions
            function openLogoutModal() {
                const modal = document.getElementById('logoutModal');
                const modalContent = document.getElementById('logoutModalContent');
                
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
                
                setTimeout(() => {
                    modalContent.classList.remove('scale-95');
                    modalContent.classList.add('scale-100');
                }, 10);
            }

            function closeLogoutModal() {
                const modal = document.getElementById('logoutModal');
                const modalContent = document.getElementById('logoutModalContent');
                
                modalContent.classList.remove('scale-100');
                modalContent.classList.add('scale-95');
                
                setTimeout(() => {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }, 200);
            }

            // Close modal when clicking outside
            document.getElementById('logoutModal')?.addEventListener('click', function(e) {
                if (e.target.id === 'logoutModal') {
                    closeLogoutModal();
                }
            });

            // Suppress browser confirm triggered by Livewire's 419 during logout only
            function prepareLogout() {
                try { closeLogoutModal(); } catch (_) {}
                const originalConfirm = window.confirm;
                window.confirm = function() { return false; };
                setTimeout(function() { window.confirm = originalConfirm; }, 8000);
            }
        </script>
        
        @livewireScripts
    </body>
</html>
