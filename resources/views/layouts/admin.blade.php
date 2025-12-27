<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Admin</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-primary-50">
            <!-- Top Navigation -->
            <nav class="bg-white border-b border-gray-200">
                <div class="mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <!-- Mobile menu button -->
                            <button type="button" 
                                    class="lg:hidden mobile-sidebar-button inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500"
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
                            <div class="shrink-0 flex items-center ml-2 lg:ml-0">
                                <a href="{{ route('admin.index') }}" class="text-lg sm:text-xl font-bold text-primary-800">
                                    <i class="fas fa-shield-alt text-primary-600 mr-2"></i>
                                    <span class="hidden sm:inline">{{ config('app.name', 'TraKerja') }} Admin</span>
                                    <span class="sm:hidden">Admin</span>
                                </a>
                            </div>
                        </div>

                        <!-- Right Side -->
                        <div class="flex items-center space-x-2 sm:space-x-4">
                            <!-- User Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = ! open" class="flex items-center text-sm font-medium text-primary-500 hover:text-primary-700 focus:outline-none transition duration-150 ease-in-out">
                                    <div class="hidden sm:block">{{ Auth::user()->name }}</div>
                                    <div class="sm:hidden">{{ substr(Auth::user()->name, 0, 1) }}</div>
                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>

                                <div x-show="open"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     @click.outside="open = false"
                                     class="absolute right-0 z-50 mt-2 w-48 rounded-md shadow-lg origin-top-right bg-white ring-1 ring-black ring-opacity-5"
                                     style="display: none;">
                                    <div class="py-1">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <a href="{{ route('logout') }}" 
                                               onclick="event.preventDefault(); this.closest('form').submit();"
                                               class="block w-full text-left px-4 py-2 text-sm text-primary-700 hover:bg-primary-50">
                                                <i class="fas fa-sign-out-alt mr-2"></i>Log Out
                                            </a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <div class="flex">
                <!-- Desktop Sidebar -->
                <aside class="hidden lg:block w-64 bg-white h-screen sticky top-0 border-r border-gray-200">
                    <nav class="mt-5 px-2">
                        <a href="{{ route('admin.index') }}" 
                           class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.index') ? 'bg-primary-50 text-primary-900' : 'text-primary-600 hover:bg-primary-50 hover:text-primary-900' }}">
                            <i class="fas fa-tachometer-alt mr-4 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.index') ? 'text-primary-600' : 'text-primary-400' }}"></i>
                            Dashboard
                        </a>

                        <a href="{{ route('admin.users') }}" 
                           class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.users*') ? 'bg-primary-50 text-primary-900' : 'text-primary-600 hover:bg-primary-50 hover:text-primary-900' }}">
                            <i class="fas fa-users mr-4 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.users*') ? 'text-primary-600' : 'text-primary-400' }}"></i>
                            Users
                        </a>

                        <a href="{{ route('admin.monetization') }}" 
                           class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.monetization') ? 'bg-primary-50 text-primary-900' : 'text-primary-600 hover:bg-primary-50 hover:text-primary-900' }}">
                            <i class="fas fa-dollar-sign mr-4 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.monetization') ? 'text-primary-600' : 'text-primary-400' }}"></i>
                            Monetization
                        </a>

                        <a href="{{ route('admin.analytics') }}" 
                           class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.analytics') ? 'bg-primary-50 text-primary-900' : 'text-primary-600 hover:bg-primary-50 hover:text-primary-900' }}">
                            <i class="fas fa-chart-line mr-4 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.analytics') ? 'text-primary-600' : 'text-primary-400' }}"></i>
                            Analytics
                        </a>

                        <a href="{{ route('admin.email-blast') }}" 
                           class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.email-blast*') ? 'bg-primary-50 text-primary-900' : 'text-primary-600 hover:bg-primary-50 hover:text-primary-900' }}">
                            <i class="fas fa-envelope mr-4 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.email-blast*') ? 'text-primary-600' : 'text-primary-400' }}"></i>
                            Email Blast
                        </a>
                    </nav>

                    <!-- Phase Indicator -->
                    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 bg-primary-50">
                        <div class="text-xs text-primary-500">Current Phase</div>
                        <div class="mt-1 flex items-center">
                            <span class="text-2xl mr-2">🚀</span>
                            <div>
                                <div class="font-semibold text-sm">Launch Phase</div>
                                <div class="text-xs text-primary-500">Phase 1</div>
                            </div>
                        </div>
                    </div>
                </aside>

                <!-- Mobile Sidebar -->
                <div class="lg:hidden fixed inset-0 z-50 hidden" id="mobile-sidebar">
                    <div class="fixed inset-0 bg-gray-600 bg-opacity-75" onclick="closeMobileSidebar()"></div>
                    <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
                        <div class="absolute top-0 right-0 -mr-12 pt-2">
                            <button type="button" 
                                    class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                                    onclick="closeMobileSidebar()">
                                <span class="sr-only">Close sidebar</span>
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                            <div class="flex-shrink-0 flex items-center px-4">
                                <a href="{{ route('admin.index') }}" class="text-xl font-bold text-primary-800">
                                    <i class="fas fa-shield-alt text-primary-600 mr-2"></i>
                                    {{ config('app.name', 'TraKerja') }} Admin
                                </a>
                            </div>
                            <nav class="mt-5 px-2 space-y-1">
                                <a href="{{ route('admin.index') }}" 
                                   class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.index') ? 'bg-primary-50 text-primary-900' : 'text-primary-600 hover:bg-primary-50 hover:text-primary-900' }}">
                                    <i class="fas fa-tachometer-alt mr-4 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.index') ? 'text-primary-600' : 'text-primary-400' }}"></i>
                                    Dashboard
                                </a>

                                <a href="{{ route('admin.users') }}" 
                                   class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.users*') ? 'bg-primary-50 text-primary-900' : 'text-primary-600 hover:bg-primary-50 hover:text-primary-900' }}">
                                    <i class="fas fa-users mr-4 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.users*') ? 'text-primary-600' : 'text-primary-400' }}"></i>
                                    Users
                                </a>

                                <a href="{{ route('admin.monetization') }}" 
                                   class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.monetization') ? 'bg-primary-50 text-primary-900' : 'text-primary-600 hover:bg-primary-50 hover:text-primary-900' }}">
                                    <i class="fas fa-dollar-sign mr-4 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.monetization') ? 'text-primary-600' : 'text-primary-400' }}"></i>
                                    Monetization
                                </a>

                                <a href="{{ route('admin.analytics') }}" 
                                   class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.analytics') ? 'bg-primary-50 text-primary-900' : 'text-primary-600 hover:bg-primary-50 hover:text-primary-900' }}">
                                    <i class="fas fa-chart-line mr-4 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.analytics') ? 'text-primary-600' : 'text-primary-400' }}"></i>
                                    Analytics
                                </a>

                                <a href="{{ route('admin.email-blast') }}" 
                                   class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.email-blast*') ? 'bg-primary-50 text-primary-900' : 'text-primary-600 hover:bg-primary-50 hover:text-primary-900' }}">
                                    <i class="fas fa-envelope mr-4 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.email-blast*') ? 'text-primary-600' : 'text-primary-400' }}"></i>
                                    Email Blast
                                </a>
                            </nav>
                        </div>
                        <!-- Phase Indicator for mobile -->
                        <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                            <div class="flex-shrink-0 w-full group block">
                                <div class="text-xs text-primary-500">Current Phase</div>
                                <div class="mt-1 flex items-center">
                                    <span class="text-2xl mr-2">🚀</span>
                                    <div>
                                        <div class="font-semibold text-sm">Launch Phase</div>
                                        <div class="text-xs text-primary-500">Phase 1</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                    <!-- Page Content -->
                    <div class="py-6 sm:py-12">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            {{ $slot }}
                        </div>
                    </div>
                </main>
            </div>
        </div>

        <script>
        // Mobile sidebar functionality
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
        </script>

        @livewireScripts
        
        {{-- Fallback: Load Livewire from CDN if local file fails --}}
        <script>
        // Wait a bit for Livewire to load, then check
        setTimeout(function() {
            if (typeof window.Livewire === 'undefined') {
                console.warn('Livewire not loaded from local assets, loading from CDN...');
                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/livewire@3/dist/livewire.min.js';
                script.onerror = function() {
                    console.error('Failed to load Livewire from CDN');
                };
                script.onload = function() {
                    console.log('Livewire loaded from CDN successfully');
                    // Re-initialize Livewire after CDN load
                    if (window.Livewire && typeof window.Livewire.start === 'function') {
                        window.Livewire.start();
                    }
                };
                document.head.appendChild(script);
            }
        }, 1000);
        </script>
    </body>
</html>
