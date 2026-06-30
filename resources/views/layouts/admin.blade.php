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
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="TraKerja">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        <style>
            .mesh-gradient-primary {
                background-color: #ffffff;
                background-image:
                    radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.03) 0px, transparent 50%),
                    radial-gradient(at 100% 100%, rgba(79, 70, 229, 0.03) 0px, transparent 50%);
            }
            .mesh-gradient-emerald {
                background-color: #ffffff;
                background-image:
                    radial-gradient(at 0% 0%, rgba(16, 185, 129, 0.03) 0px, transparent 50%),
                    radial-gradient(at 100% 100%, rgba(5, 150, 105, 0.03) 0px, transparent 50%);
            }
            .mesh-gradient-blue {
                background-color: #ffffff;
                background-image:
                    radial-gradient(at 0% 0%, rgba(59, 130, 246, 0.03) 0px, transparent 50%),
                    radial-gradient(at 100% 100%, rgba(37, 99, 235, 0.03) 0px, transparent 50%);
            }
            .mesh-gradient-amber {
                background-color: #ffffff;
                background-image:
                    radial-gradient(at 0% 0%, rgba(245, 158, 11, 0.03) 0px, transparent 50%),
                    radial-gradient(at 100% 100%, rgba(217, 119, 6, 0.03) 0px, transparent 50%);
            }
            .mesh-gradient-purple {
                background-color: #ffffff;
                background-image:
                    radial-gradient(at 0% 0%, rgba(168, 85, 247, 0.03) 0px, transparent 50%),
                    radial-gradient(at 100% 100%, rgba(147, 51, 234, 0.03) 0px, transparent 50%);
            }
            .bento-card {
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.8), 0 10px 20px -5px rgba(0, 0, 0, 0.03);
            }
            .bento-card:hover {
                transform: translateY(-4px) scale(1.01);
                box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.8), 0 20px 25px -5px rgba(0, 0, 0, 0.05);
            }
            .bento-card-stat {
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.8), 0 4px 6px -1px rgba(0, 0, 0, 0.02);
            }
            .bento-card-stat:hover {
                transform: translateY(-4px);
                box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.8), 0 20px 25px -5px rgba(0, 0, 0, 0.05);
            }
            .custom-scrollbar::-webkit-scrollbar {
                width: 4px;
            }
            .custom-scrollbar::-webkit-scrollbar-track {
                background: transparent;
            }
            .custom-scrollbar::-webkit-scrollbar-thumb {
                background-color: #e2e8f0;
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
    <body class="font-sans antialiased text-gray-900 bg-gray-50">
        <div class="min-h-screen flex flex-col">
            <!-- Top Navigation -->
            <nav class="bg-white border-b border-gray-200 sticky top-0 z-40 shadow-sm">
                <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <!-- Mobile menu button -->
                            <button type="button" 
                                    class="lg:hidden mobile-sidebar-button inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500 mr-2"
                                    aria-controls="mobile-sidebar" 
                                    aria-expanded="false">
                                <span class="sr-only">Open sidebar</span>
                                <!-- Hamburger icon -->
                                <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                                <!-- Close icon -->
                                <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <a href="{{ route('admin.index') }}" class="text-lg font-bold text-primary-800 flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-primary-600 flex items-center justify-center text-white shadow-sm">
                                        <i class="fas fa-shield-alt text-sm"></i>
                                    </div>
                                    <span class="hidden sm:inline">{{ config('app.name', 'TraKerja') }} <span class="text-gray-400 font-medium">| Admin</span></span>
                                </a>
                            </div>

                            <!-- Desktop Menu Links -->
                            <div class="hidden lg:flex lg:items-center lg:ml-8 lg:space-x-1">
                                <a href="{{ route('admin.index') }}" 
                                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 {{ request()->routeIs('admin.index') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                                    <i class="fas fa-tachometer-alt {{ request()->routeIs('admin.index') ? 'text-primary-600' : 'text-gray-400' }}"></i>
                                    Dashboard
                                </a>

                                <a href="{{ route('admin.users') }}" 
                                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 {{ request()->routeIs('admin.users') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                                    <i class="fas fa-users {{ request()->routeIs('admin.users') ? 'text-primary-600' : 'text-gray-400' }}"></i>
                                    Pengguna
                                </a>
                                <a href="{{ route('admin.user-activities') }}" 
                                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 {{ request()->routeIs('admin.user-activities') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                                    <i class="fas fa-history {{ request()->routeIs('admin.user-activities') ? 'text-primary-600' : 'text-gray-400' }}"></i>
                                    Aktivitas
                                </a>

                                <a href="{{ route('admin.monetization') }}" 
                                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 {{ request()->routeIs('admin.monetization') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                                    <i class="fas fa-dollar-sign {{ request()->routeIs('admin.monetization') ? 'text-primary-600' : 'text-gray-400' }}"></i>
                                    Monetization
                                </a>

                                <a href="{{ route('admin.analytics') }}" 
                                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 {{ request()->routeIs('admin.analytics') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                                    <i class="fas fa-chart-line {{ request()->routeIs('admin.analytics') ? 'text-primary-600' : 'text-gray-400' }}"></i>
                                    Analytics
                                </a>

                                <a href="{{ route('admin.email-blast') }}" 
                                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 {{ request()->routeIs('admin.email-blast*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                                    <i class="fas fa-envelope {{ request()->routeIs('admin.email-blast*') ? 'text-primary-600' : 'text-gray-400' }}"></i>
                                    Email Blast
                                </a>

                                <a href="{{ route('admin.feedbacks.index') }}" 
                                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 {{ request()->routeIs('admin.feedbacks*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                                    <i class="fas fa-comments {{ request()->routeIs('admin.feedbacks*') ? 'text-primary-600' : 'text-gray-400' }}"></i>
                                    User Feedback
                                </a>

                                <a href="{{ route('admin.integration-hub') }}" 
                                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 {{ request()->routeIs('admin.integration-hub') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                                    <i class="ph-duotone ph-plugs {{ request()->routeIs('admin.integration-hub') ? 'text-primary-600' : 'text-gray-400' }}"></i>
                                    Integration
                                </a>

                                <a href="{{ route('admin.database-maintenance') }}" 
                                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 {{ request()->routeIs('admin.database-maintenance') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                                    <i class="ph-duotone ph-database {{ request()->routeIs('admin.database-maintenance') ? 'text-primary-600' : 'text-gray-400' }}"></i>
                                    Database
                                </a>
                                
                                <a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings') ? 'bg-slate-900 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} px-3 py-2 rounded-xl text-sm font-bold transition-all flex items-center group">
                                    <i class="ph-duotone ph-gear-six text-lg mr-2 {{ request()->routeIs('admin.settings') ? 'text-primary-400' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
                                    Settings
                                </a>
                            </div>
                        </div>

                        <!-- Right Side -->
                        <div class="flex items-center space-x-4">
                            <!-- Phase Indicator Desktop -->
                            <div class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-xs font-medium text-gray-600">
                                <span class="relative flex h-2 w-2">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                </span>
                                Operational
                            </div>

                            <!-- User Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = ! open" class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-gray-50 text-sm font-medium text-gray-700 focus:outline-none transition-colors">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-100 to-primary-50 border border-primary-200 text-primary-700 flex items-center justify-center font-bold shadow-sm">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <div class="hidden sm:block truncate max-w-[120px]">{{ Auth::user()->name }}</div>
                                    <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
                                </button>

                                <div x-show="open"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
                                     x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                                     x-transition:leave-end="transform opacity-0 scale-95 translate-y-2"
                                     @click.outside="open = false"
                                     class="absolute right-0 z-50 mt-2 w-56 rounded-xl shadow-lg border border-gray-100 bg-white py-1"
                                     style="display: none;">
                                    
                                    <div class="px-4 py-3 border-b border-gray-100 mb-1">
                                        <p class="text-xs text-gray-500 font-medium">Signed in as</p>
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->email }}</p>
                                    </div>

                                    <div class="px-1 py-1">
                                        <a href="{{ route('admin.settings') }}" class="block px-3 py-2 text-sm text-slate-600 rounded-lg hover:bg-slate-50 hover:text-slate-900 transition-colors font-medium flex items-center gap-2">
                                            <i class="ph-bold ph-gear-six"></i> Global Settings
                                        </a>

                                        <a href="{{ route('admin.integration-hub') }}" class="block px-3 py-2 text-sm text-slate-600 rounded-lg hover:bg-slate-50 hover:text-slate-900 transition-colors font-medium flex items-center gap-2">
                                            <i class="ph-bold ph-plugs"></i> Integration Hub
                                        </a>

                                        <a href="{{ route('admin.database-maintenance') }}" class="block px-3 py-2 text-sm text-slate-600 rounded-lg hover:bg-slate-50 hover:text-slate-900 transition-colors font-medium flex items-center gap-2">
                                            <i class="ph-bold ph-database"></i> Database & Storage
                                        </a>

                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <a href="{{ route('logout') }}" 
                                               onclick="event.preventDefault(); this.closest('form').submit();"
                                               class="block w-full text-left px-3 py-2 text-sm text-red-600 rounded-lg hover:bg-red-50 hover:text-red-700 transition-colors font-medium flex items-center gap-2">
                                                <i class="fas fa-sign-out-alt"></i> Log Out
                                            </a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Mobile Sidebar -->
            <div class="lg:hidden fixed inset-0 z-50 hidden" id="mobile-sidebar">
                <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="closeMobileSidebar()"></div>
                <div class="relative flex-1 flex flex-col max-w-[280px] w-full bg-white h-full shadow-2xl border-r border-gray-200">
                    <div class="absolute top-0 right-0 -mr-12 pt-2">
                        <button type="button" 
                                class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none bg-gray-800/20 backdrop-blur-sm hover:bg-gray-800/40"
                                onclick="closeMobileSidebar()">
                            <span class="sr-only">Close sidebar</span>
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="flex-shrink-0 flex items-center px-5 h-16 border-b border-gray-100">
                        <a href="{{ route('admin.index') }}" class="text-lg font-bold text-primary-800 flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-primary-600 flex items-center justify-center text-white">
                                <i class="fas fa-shield-alt text-sm"></i>
                            </div>
                            {{ config('app.name', 'TraKerja') }} Admin
                        </a>
                    </div>
                    
                    <div class="flex-1 h-0 pt-4 pb-4 overflow-y-auto">
                        <div class="px-5 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-widest">
                            Main Menu
                        </div>
                        <nav class="px-3 space-y-1">
                            <a href="{{ route('admin.index') }}" 
                               class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.index') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                <i class="fas fa-tachometer-alt mr-3 flex-shrink-0 w-5 text-center {{ request()->routeIs('admin.index') ? 'text-primary-600' : 'text-gray-400' }}"></i>
                                Dashboard
                            </a>

                            <a href="{{ route('admin.users') }}" 
                               class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.users') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                <i class="fas fa-users mr-3 flex-shrink-0 w-5 text-center {{ request()->routeIs('admin.users') ? 'text-primary-600' : 'text-gray-400' }}"></i>
                                Pengguna
                            </a>
                            <a href="{{ route('admin.user-activities') }}" 
                               class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.user-activities') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                <i class="fas fa-history mr-3 flex-shrink-0 w-5 text-center {{ request()->routeIs('admin.user-activities') ? 'text-primary-600' : 'text-gray-400' }}"></i>
                                Aktivitas
                            </a>

                            <a href="{{ route('admin.monetization') }}" 
                               class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.monetization') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                <i class="fas fa-dollar-sign mr-3 flex-shrink-0 w-5 text-center {{ request()->routeIs('admin.monetization') ? 'text-primary-600' : 'text-gray-400' }}"></i>
                                Monetization
                            </a>

                            <a href="{{ route('admin.analytics') }}" 
                               class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.analytics') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                <i class="fas fa-chart-line mr-3 flex-shrink-0 w-5 text-center {{ request()->routeIs('admin.analytics') ? 'text-primary-600' : 'text-gray-400' }}"></i>
                                Analytics
                            </a>

                            <a href="{{ route('admin.email-blast') }}" 
                               class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.email-blast*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                <i class="fas fa-envelope mr-3 flex-shrink-0 w-5 text-center {{ request()->routeIs('admin.email-blast*') ? 'text-primary-600' : 'text-gray-400' }}"></i>
                                Email Blast
                            </a>

                            <a href="{{ route('admin.feedbacks.index') }}" 
                               class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.feedbacks*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                <i class="fas fa-comments mr-3 flex-shrink-0 w-5 text-center {{ request()->routeIs('admin.feedbacks*') ? 'text-primary-600' : 'text-gray-400' }}"></i>
                                User Feedback
                            </a>

                            <div class="mt-8 mb-2 px-3">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">System Management</span>
                            </div>

                            <a href="{{ route('admin.integration-hub') }}" 
                               class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.integration-hub') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                <i class="ph-bold ph-plugs mr-3 flex-shrink-0 w-5 text-center text-lg {{ request()->routeIs('admin.integration-hub') ? 'text-primary-600' : 'text-gray-400' }}"></i>
                                Integration Hub
                            </a>

                            <a href="{{ route('admin.database-maintenance') }}" 
                               class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.database-maintenance') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                <i class="ph-bold ph-database mr-3 flex-shrink-0 w-5 text-center text-lg {{ request()->routeIs('admin.database-maintenance') ? 'text-primary-600' : 'text-gray-400' }}"></i>
                                Database & Storage
                            </a>
                            
                            <a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} block px-4 py-3 rounded-xl text-base font-bold transition-all flex items-center group">
                                <i class="ph-duotone ph-gear-six text-xl mr-3 {{ request()->routeIs('admin.settings') ? 'text-primary-400' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
                                Settings
                            </a>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-1 w-full relative bg-slate-50">
                {{ $slot }}
            </main>
        </div>

        <script>
        function openMobileSidebar() {
            const sidebar = document.getElementById('mobile-sidebar');
            const button = document.querySelector('.mobile-sidebar-button');
            sidebar.classList.remove('hidden');
            button.setAttribute('aria-expanded', 'true');
        }

        function closeMobileSidebar() {
            const sidebar = document.getElementById('mobile-sidebar');
            const button = document.querySelector('.mobile-sidebar-button');
            sidebar.classList.add('hidden');
            button.setAttribute('aria-expanded', 'false');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const mobileSidebarButton = document.querySelector('.mobile-sidebar-button');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            if (mobileSidebarButton && mobileSidebar) {
                mobileSidebarButton.addEventListener('click', function() {
                    const isExpanded = mobileSidebarButton.getAttribute('aria-expanded') === 'true';
                    if (isExpanded) {
                        closeMobileSidebar();
                    } else {
                        openMobileSidebar();
                    }
                });
            }
        });

        // View Transition API integration with Livewire
        document.addEventListener('livewire:navigating', (ev) => {
            if (!document.startViewTransition) return;

            ev.detail.beforeDomUpdate((navigation) => {
                return new Promise((resolve) => {
                    document.startViewTransition(async () => {
                        resolve();
                        await navigation.complete;
                    });
                });
            });
        });

        // Register PWA Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('PWA Service Worker registered!'))
                    .catch(err => console.log('PWA Service Worker failed:', err));
            });
        }
        </script>

        @livewireScripts
    </body>
</html>
