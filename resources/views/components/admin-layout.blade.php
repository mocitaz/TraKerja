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
            
            /* Custom Scrollbar */
            ::-webkit-scrollbar { width: 4px; height: 4px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #e4e4e7; border-radius: 4px; }
            ::-webkit-scrollbar-thumb:hover { background: #d4d4d8; }
            
            .sidebar-scroll::-webkit-scrollbar { width: 3px; }
            .sidebar-scroll::-webkit-scrollbar-thumb { background: #f4f4f5; }
            .sidebar-scroll:hover::-webkit-scrollbar-thumb { background: #e4e4e7; }
        </style>
    </head>
    <body class="text-zinc-800 antialiased bg-zinc-50/50 overflow-hidden selection:bg-zinc-900 selection:text-white"
          x-data="{ 
              sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
              mobileSidebarOpen: false
          }"
          x-init="$watch('sidebarCollapsed', val => localStorage.setItem('sidebarCollapsed', val))"
          @keydown.escape="mobileSidebarOpen = false">
          
        <div class="h-screen flex overflow-hidden">
            <!-- Sidebar -->
            <aside :class="{ 
                        'w-16': sidebarCollapsed, 
                        'w-64': !sidebarCollapsed,
                        'translate-x-0': mobileSidebarOpen,
                        '-translate-x-full': !mobileSidebarOpen 
                   }"
                   class="fixed inset-y-0 left-0 z-[60] bg-white border-r border-zinc-200/80 transform transition-all duration-200 ease-in-out lg:translate-x-0 lg:static flex-shrink-0 flex flex-col">
                
                <!-- Desktop Collapse Button -->
                <button @click="sidebarCollapsed = !sidebarCollapsed" 
                        class="absolute -right-3 top-16 bg-white border border-zinc-200 text-zinc-400 hover:text-zinc-800 rounded-full w-6 h-6 hidden lg:flex items-center justify-center transition-colors z-50 shadow-sm">
                    <i class="ph-bold ph-caret-left text-[10px]" :class="sidebarCollapsed ? 'rotate-180' : ''"></i>
                </button>

                <!-- Logo & Brand -->
                <div class="flex items-center h-12 px-4 border-b border-zinc-200/80 flex-shrink-0 relative bg-white">
                    <div class="flex items-center space-x-2 relative z-10 w-full" :class="sidebarCollapsed ? 'justify-center space-x-0' : ''">
                        <div class="relative flex-shrink-0 p-1 bg-zinc-50 rounded border border-zinc-200">
                            <img src="{{ asset('images/icon.png') }}" 
                                 alt="TraKerja Logo" 
                                 class="h-5 w-5 object-contain"
                                 onerror="this.style.display='none';">
                        </div>
                        <div x-show="!sidebarCollapsed" class="flex flex-col truncate">
                            <span class="text-xs font-bold text-zinc-900 tracking-tight leading-none mb-0.5">
                                TraKerja
                            </span>
                            <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest leading-none">
                                Admin Portal
                            </span>
                        </div>
                    </div>
                    
                    <button @click="mobileSidebarOpen = false" class="lg:hidden absolute right-3 p-1 text-zinc-400 hover:text-zinc-700 bg-zinc-50 rounded transition-colors z-20">
                        <i class="ph ph-x text-base"></i>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto sidebar-scroll">
                    <!-- Section Label -->
                    <div x-show="!sidebarCollapsed" class="px-2 mb-2">
                        <span class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Main Menu</span>
                    </div>

                    <!-- Dashboard -->
                    <a href="{{ route('admin.index') }}"
                       class="group relative flex items-center px-2 py-1.5 rounded-md text-xs font-medium transition-colors {{ request()->routeIs('admin.index') ? 'bg-zinc-100 text-zinc-900 font-semibold' : 'text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10 w-5">
                            <i class="text-base {{ request()->routeIs('admin.index') ? 'ph-bold ph-squares-four text-zinc-900' : 'ph ph-squares-four' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" class="ml-2.5 truncate tracking-tight">Dashboard</span>
                        
                        <!-- Tooltip -->
                        <div x-show="sidebarCollapsed" class="absolute left-12 px-2 py-1 bg-zinc-800 text-white text-[10px] font-bold rounded shadow-md pointer-events-none z-50 whitespace-nowrap">
                            Dashboard
                        </div>
                    </a>

                    <!-- Users -->
                    <a href="{{ route('admin.users') }}"
                       class="group relative flex items-center px-2 py-1.5 rounded-md text-xs font-medium transition-colors {{ request()->routeIs('admin.users') ? 'bg-zinc-100 text-zinc-900 font-semibold' : 'text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10 w-5">
                            <i class="text-base {{ request()->routeIs('admin.users') ? 'ph-bold ph-users-three text-zinc-900' : 'ph ph-users-three' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" class="ml-2.5 truncate tracking-tight">User Management</span>
                        
                        <!-- Tooltip -->
                        <div x-show="sidebarCollapsed" class="absolute left-12 px-2 py-1 bg-zinc-800 text-white text-[10px] font-bold rounded shadow-md pointer-events-none z-50 whitespace-nowrap">
                            Users
                        </div>
                    </a>

                    <!-- Activity Logs -->
                    <a href="{{ route('admin.user-activities') }}"
                       class="group relative flex items-center px-2 py-1.5 rounded-md text-xs font-medium transition-colors {{ request()->routeIs('admin.user-activities') ? 'bg-zinc-100 text-zinc-900 font-semibold' : 'text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10 w-5">
                            <i class="text-base {{ request()->routeIs('admin.user-activities') ? 'ph-bold ph-clock-counter-clockwise text-zinc-900' : 'ph ph-clock-counter-clockwise' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" class="ml-2.5 truncate tracking-tight">Activity Log</span>
                        
                        <!-- Tooltip -->
                        <div x-show="sidebarCollapsed" class="absolute left-12 px-2 py-1 bg-zinc-800 text-white text-[10px] font-bold rounded shadow-md pointer-events-none z-50 whitespace-nowrap">
                            Activity Log
                        </div>
                    </a>

                    <!-- Analytics -->
                    <a href="{{ route('admin.analytics') }}"
                       class="group relative flex items-center px-2 py-1.5 rounded-md text-xs font-medium transition-colors {{ request()->routeIs('admin.analytics') ? 'bg-zinc-100 text-zinc-900 font-semibold' : 'text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10 w-5">
                            <i class="text-base {{ request()->routeIs('admin.analytics') ? 'ph-bold ph-chart-pie-slice text-zinc-900' : 'ph ph-chart-pie-slice' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" class="ml-2.5 truncate tracking-tight">Analytics</span>
                        
                        <!-- Tooltip -->
                        <div x-show="sidebarCollapsed" class="absolute left-12 px-2 py-1 bg-zinc-800 text-white text-[10px] font-bold rounded shadow-md pointer-events-none z-50 whitespace-nowrap">
                            Analytics
                        </div>
                    </a>

                    <!-- Section Divider -->
                    <div class="py-2">
                        <div class="border-t border-zinc-150"></div>
                    </div>

                    <div x-show="!sidebarCollapsed" class="px-2 mb-2">
                        <span class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Outreach</span>
                    </div>

                    <!-- Email Blast -->
                    <a href="{{ route('admin.email-blast') }}"
                       class="group relative flex items-center px-2 py-1.5 rounded-md text-xs font-medium transition-colors {{ request()->routeIs('admin.email-blast*') ? 'bg-zinc-100 text-zinc-900 font-semibold' : 'text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10 w-5">
                            <i class="text-base {{ request()->routeIs('admin.email-blast*') ? 'ph-bold ph-paper-plane-tilt text-zinc-900' : 'ph ph-paper-plane-tilt' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" class="ml-2.5 truncate tracking-tight">Email Blast</span>
                        
                        <!-- Tooltip -->
                        <div x-show="sidebarCollapsed" class="absolute left-12 px-2 py-1 bg-zinc-800 text-white text-[10px] font-bold rounded shadow-md pointer-events-none z-50 whitespace-nowrap">
                            Email Blast
                        </div>
                    </a>

                    <!-- Feedbacks -->
                    <a href="{{ route('admin.feedbacks.index') }}"
                       class="group relative flex items-center px-2 py-1.5 rounded-md text-xs font-medium transition-colors {{ request()->routeIs('admin.feedbacks*') ? 'bg-zinc-100 text-zinc-900 font-semibold' : 'text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10 w-5">
                            <i class="text-base {{ request()->routeIs('admin.feedbacks*') ? 'ph-bold ph-chat-circle-dots text-zinc-900' : 'ph ph-chat-circle-dots' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" class="ml-2.5 truncate tracking-tight">User Feedback</span>
                        
                        <!-- Tooltip -->
                        <div x-show="sidebarCollapsed" class="absolute left-12 px-2 py-1 bg-zinc-800 text-white text-[10px] font-bold rounded shadow-md pointer-events-none z-50 whitespace-nowrap">
                            User Feedback
                        </div>
                    </a>

                    <!-- Section Divider -->
                    <div class="py-2">
                        <div class="border-t border-zinc-150"></div>
                    </div>

                    <div x-show="!sidebarCollapsed" class="px-2 mb-2">
                        <span class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider">System</span>
                    </div>

                    <!-- Settings -->
                    <a href="{{ route('admin.settings') }}"
                       class="group relative flex items-center px-2 py-1.5 rounded-md text-xs font-medium transition-colors {{ request()->routeIs('admin.settings') ? 'bg-zinc-100 text-zinc-900 font-semibold' : 'text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900' }}"
                       :class="sidebarCollapsed ? 'justify-center' : ''">
                        <div class="flex items-center justify-center flex-shrink-0 relative z-10 w-5">
                            <i class="text-base {{ request()->routeIs('admin.settings') ? 'ph-bold ph-gear-six text-zinc-900' : 'ph ph-gear-six' }}"></i>
                        </div>
                        <span x-show="!sidebarCollapsed" class="ml-2.5 truncate tracking-tight">Settings</span>
                        
                        <!-- Tooltip -->
                        <div x-show="sidebarCollapsed" class="absolute left-12 px-2 py-1 bg-zinc-800 text-white text-[10px] font-bold rounded shadow-md pointer-events-none z-50 whitespace-nowrap">
                            Settings
                        </div>
                    </a>
                </nav>
                
                <!-- Bottom Profile Summary -->
                <div class="p-3 border-t border-zinc-150 bg-zinc-50/50">
                    <div class="flex items-center bg-white rounded border border-zinc-200 p-2 cursor-pointer transition-all duration-200"
                         :class="sidebarCollapsed ? 'justify-center' : ''">
                        @php $user = Auth::user(); @endphp
                        @if($user && $user->logo)
                            <img src="{{ $user->avatar_url }}" 
                                 alt="Profile" 
                                 class="h-6 w-6 rounded object-cover flex-shrink-0">
                        @else
                            <div class="h-6 w-6 bg-zinc-100 rounded flex items-center justify-center flex-shrink-0 border border-zinc-200">
                                <span class="text-zinc-700 font-bold text-xs">{{ substr($user->name ?? 'A', 0, 1) }}</span>
                            </div>
                        @endif
                        
                        <div x-show="!sidebarCollapsed" class="ml-2 min-w-0 flex-1">
                            <p class="text-xs font-bold text-zinc-800 truncate">{{ $user->name ?? 'Admin' }}</p>
                            <p class="text-[9px] font-mono font-bold text-zinc-400 truncate uppercase tracking-widest mt-0.5">Super Admin</p>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Sidebar Overlay (Mobile) -->
            <div x-show="mobileSidebarOpen" 
                 x-transition.opacity
                 @click="mobileSidebarOpen = false"
                 class="fixed inset-0 bg-zinc-950/40 backdrop-blur-sm z-50 lg:hidden" style="display: none;"></div>

            <!-- Main Content Area -->
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
                @endphp
                
                <!-- Sticky Global Header (Cupertino-Notion) -->
                <header class="bg-white/80 backdrop-blur-md border-b border-zinc-200/80 h-12 flex items-center justify-between px-4 z-45 flex-shrink-0 sticky top-0">
                    <div class="flex items-center">
                        <button @click="mobileSidebarOpen = true" class="lg:hidden p-1.5 mr-2.5 rounded bg-white border border-zinc-200 text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900 transition-colors">
                            <i class="ph ph-list text-base"></i>
                        </button>
                        <!-- Dynamic Page Title / Breadcrumb -->
                        <div class="hidden sm:flex items-center space-x-2 text-xs font-semibold text-zinc-500">
                            <span class="tracking-tight">Admin Portal</span>
                            <div class="w-[1px] h-3.5 bg-zinc-250 mx-1"></div>
                            <span class="text-zinc-900 tracking-tight font-bold">{{ $pageTitle }}</span>
                        </div>
                        <h2 class="text-sm font-bold text-zinc-900 sm:hidden tracking-tight">{{ $pageTitle }}</h2>
                    </div>

                    <!-- Right Side Actions -->
                    <div class="flex items-center">
                        <!-- User Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = ! open" class="flex items-center space-x-2 focus:outline-none bg-white border border-zinc-200 pl-1 pr-2 py-1 rounded-md hover:bg-zinc-50 transition-all group">
                                @if($user && $user->logo)
                                    <img src="{{ $user->avatar_url }}" 
                                         alt="Profile Photo" 
                                         class="h-6 w-6 rounded object-cover">
                                @else
                                    <div class="h-6 w-6 bg-zinc-100 border border-zinc-200 rounded flex items-center justify-center">
                                        <span class="text-zinc-700 font-bold text-[10px]">{{ substr($user->name ?? 'A', 0, 1) }}</span>
                                    </div>
                                @endif
                                <span class="font-bold text-xs text-zinc-700 hidden sm:block group-hover:text-zinc-900 transition-colors">{{ $user->name ?? 'Admin' }}</span>
                                <i class="ph ph-caret-down text-[9px] text-zinc-400 hidden sm:block transition-transform" :class="open ? 'rotate-180' : ''"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 @click.away="open = false"
                                 class="absolute right-0 top-full mt-2 w-56 bg-white rounded-md border border-zinc-200 py-1.5 focus:outline-none z-50 shadow-md"
                                 style="display: none;">
                                
                                <div class="px-4 py-2 border-b border-zinc-150 bg-zinc-50/50 mb-1">
                                    <p class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-0.5">Signed in as</p>
                                    <p class="text-xs font-bold text-zinc-800 truncate">{{ $user->email ?? 'admin@example.com' }}</p>
                                </div>
                                
                                <a href="{{ route('admin.payments') }}" class="flex items-center px-4 py-1.5 text-xs font-semibold text-zinc-650 hover:text-zinc-900 hover:bg-zinc-50 transition-colors">
                                    <i class="w-5 text-sm ph ph-credit-card text-zinc-400"></i>
                                    Payments Center
                                </a>
                                <a href="{{ route('admin.monetization') }}" class="flex items-center px-4 py-1.5 text-xs font-semibold text-zinc-650 hover:text-zinc-900 hover:bg-zinc-50 transition-colors">
                                    <i class="w-5 text-sm ph ph-coins text-zinc-400"></i>
                                    Monetization Control
                                </a>
                                
                                <div class="border-t border-zinc-150 my-1"></div>
                                
                                <button type="button" onclick="openLogoutModal()" class="w-full text-left flex items-center px-4 py-1.5 text-xs font-bold text-red-650 hover:bg-red-50/50 transition-colors">
                                    <i class="ph ph-sign-out w-5 text-sm"></i>
                                    Sign out
                                </button>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto">
                    <div class="px-4 sm:px-6 py-4">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        <!-- Logout Confirmation Modal -->
        <div id="logoutModal" class="fixed inset-0 bg-zinc-950/40 backdrop-blur-sm hidden z-[100] flex items-center justify-center p-4">
            <div class="bg-white rounded-lg max-w-xs w-full transform transition-all duration-200 scale-95 border border-zinc-200" id="logoutModalContent">
                <div class="p-5 text-center">
                    <div class="w-10 h-10 bg-red-50 border border-red-200 rounded flex items-center justify-center mx-auto mb-3">
                        <i class="ph-bold ph-sign-out text-lg text-red-500"></i>
                    </div>
                    <h3 class="text-sm font-bold text-zinc-900">Sign Out</h3>
                    <p class="text-xs text-zinc-500 leading-relaxed mt-1">Yakin anda mau keluar dari akun?</p>
                    
                    <div class="mt-4 flex gap-2">
                        <a href="{{ route('logout.force') }}" onclick="prepareLogout()"
                           class="flex-1 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded text-xs font-semibold text-center transition-colors">
                            Ya, Logout
                        </a>
                        <button onclick="closeLogoutModal()" 
                                class="flex-1 py-1.5 bg-zinc-100 hover:bg-zinc-200 text-zinc-650 rounded text-xs font-semibold transition-colors">
                            Batal
                        </button>
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
        
        @include('components.toast-notification')
        @livewireScripts
    </body>
</html>
