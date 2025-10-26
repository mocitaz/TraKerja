<nav class="bg-white/95 backdrop-blur-md border-b border-gray-200/50 shadow-sm sticky top-0 z-40">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Left Section: Mobile Menu Button + Brand -->
            <div class="flex items-center">
                <!-- Mobile Menu Button -->
                <div class="md:hidden mr-3">
                    <button type="button" 
                            class="mobile-menu-button group inline-flex items-center justify-center p-2 rounded-xl text-gray-600 hover:text-primary-600 hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all duration-200"
                            aria-controls="mobile-menu" 
                            aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <!-- Hamburger icon -->
                        <svg class="block h-6 w-6 transition-transform duration-200 group-hover:scale-110" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <!-- Close icon -->
                        <svg class="hidden h-6 w-6 transition-transform duration-200 group-hover:scale-110" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Brand Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('tracker') }}" class="group">
                        <span class="text-lg sm:text-xl font-bold bg-gradient-to-r from-[#d983e4] to-[#4e71c5] bg-clip-text text-transparent">
                            TraKerja
                        </span>
                    </a>
                </div>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex space-x-1">
                @if(!auth()->user()->isAdmin() && auth()->user()->role !== 'admin')
                    {{-- Regular User Navigation --}}
                    <a href="{{ route('tracker') }}"
                       class="px-3 lg:px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('tracker') ? 'bg-primary-100 text-primary-600 shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">
                        <span>Tracker</span>
                    </a>
                    <a href="{{ route('summary') }}"
                       class="px-3 lg:px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('summary') ? 'bg-primary-100 text-primary-600 shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">
                        <span>Summary</span>
                    </a>
                    <a href="{{ route('goals') }}"
                       class="px-3 lg:px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('goals') ? 'bg-primary-100 text-primary-600 shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">
                        <span>Goals</span>
                    </a>
                    <a href="{{ route('interviews') }}"
                       class="px-3 lg:px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('interviews') ? 'bg-primary-100 text-primary-600 shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">
                        <span>Interviews</span>
                    </a>
                    <a href="{{ route('cv.builder') }}"
                       class="px-3 lg:px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('cv.*') ? 'bg-primary-100 text-primary-600 shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">
                        <span class="hidden lg:inline">CV Builder</span>
                        <span class="lg:hidden">CV</span>
                        @if(auth()->user() && auth()->user()->is_premium && auth()->user()->payment_status === 'paid')
                            <span class="ml-1 px-1.5 py-0.5 text-xs bg-primary-100 text-primary-700 rounded font-semibold">PRO</span>
                        @endif
                    </a>
                @else
                    {{-- Admin Navigation --}}
                    <a href="{{ route('admin.index') }}"
                       class="px-3 lg:px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.index') ? 'bg-primary-100 text-primary-600 shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">
                        <span class="hidden lg:inline">Dashboard</span>
                        <span class="lg:hidden">Home</span>
                    </a>
                    <a href="{{ route('admin.users') }}"
                       class="px-3 lg:px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.users') ? 'bg-primary-100 text-primary-600 shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">
                        <span>Users</span>
                    </a>
                    <a href="{{ route('admin.payments') }}"
                       class="px-3 lg:px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.payments') ? 'bg-primary-100 text-primary-600 shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">
                        <span class="hidden lg:inline">Payments</span>
                        <span class="lg:hidden">Pay</span>
                    </a>
                    <a href="{{ route('admin.analytics') }}"
                       class="px-3 lg:px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.analytics') ? 'bg-primary-100 text-primary-600 shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">
                        <span>Analytics</span>
                    </a>
                    <a href="{{ route('admin.monetization') }}"
                       class="px-3 lg:px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.monetization') ? 'bg-primary-100 text-primary-600 shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">
                        <span class="hidden lg:inline">Monetization</span>
                        <span class="lg:hidden">Money</span>
                    </a>
                @endif
            </div>

            <!-- Right Section: User Menu -->
            <div class="flex items-center space-x-2 sm:space-x-4">
                <!-- User Profile -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    @if(Auth::user()->logo)
                        <div class="relative">
                            <img src="{{ Storage::url(Auth::user()->logo) }}" 
                                 alt="Profile Photo" 
                                 class="h-8 w-8 sm:h-10 sm:w-10 rounded-full object-cover ring-2 ring-white shadow-lg hover:shadow-xl transition-all duration-200">
                        </div>
                    @else
                        <div class="relative">
                            <div class="h-8 w-8 sm:h-10 sm:w-10 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-200 ring-2 ring-white">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                    @endif
                    <div class="hidden sm:block">
                        <p class="text-sm font-semibold text-gray-900 truncate max-w-32">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate max-w-32">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <!-- User Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 hover:bg-gray-50 p-1 sm:p-2">
                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="py-1">
                            <x-dropdown-link :href="route('profile.edit')" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            {{-- DISABLED: Export CSV feature temporarily disabled --}}
                            @if(false && !auth()->user()->isAdmin() && auth()->user()->role !== 'admin')
                                <!-- Export CSV (Only for regular users) -->
                                <a href="{{ route('export.job-applications.csv') }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors duration-200"
                                   onclick="showExportNotification()">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Export ke CSV
                                </a>
                            @endif

                            <!-- Authentication -->
                            <button onclick="openLogoutModal()" 
                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                {{ __('Log Out') }}
                            </button>
                        </div>
                    </x-slot>
                </x-dropdown>
                
                <!-- Notification Bell -->
                <livewire:notification-bell />
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div class="md:hidden hidden transition-all duration-300 ease-out" id="mobile-menu" style="transform: translateY(-10px); opacity: 0;">
            <div class="px-4 pt-4 pb-6 space-y-2 bg-gradient-to-br from-white to-gray-50 border-t border-gray-200/60 shadow-lg">
                @if(!auth()->user()->isAdmin() && auth()->user()->role !== 'admin')
                    {{-- Regular User Mobile Navigation --}}
                    <a href="{{ route('tracker') }}"
                       class="group block px-4 py-3 rounded-xl text-base font-medium transition-all duration-200 {{ request()->routeIs('tracker') ? 'bg-gradient-to-r from-primary-500 to-primary-600 text-white shadow-lg shadow-primary-500/25' : 'text-gray-700 hover:text-primary-600 hover:bg-primary-50 hover:shadow-md' }}">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg {{ request()->routeIs('tracker') ? 'bg-white/20' : 'bg-primary-100' }} flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                                <svg class="w-5 h-5 {{ request()->routeIs('tracker') ? 'text-white' : 'text-primary-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold">Tracker</div>
                                <div class="text-sm {{ request()->routeIs('tracker') ? 'text-white/80' : 'text-gray-500' }}">Job applications</div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('summary') }}"
                       class="group block px-4 py-3 rounded-xl text-base font-medium transition-all duration-200 {{ request()->routeIs('summary') ? 'bg-gradient-to-r from-primary-500 to-primary-600 text-white shadow-lg shadow-primary-500/25' : 'text-gray-700 hover:text-primary-600 hover:bg-primary-50 hover:shadow-md' }}">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg {{ request()->routeIs('summary') ? 'bg-white/20' : 'bg-primary-100' }} flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                                <svg class="w-5 h-5 {{ request()->routeIs('summary') ? 'text-white' : 'text-primary-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold">Summary</div>
                                <div class="text-sm {{ request()->routeIs('summary') ? 'text-white/80' : 'text-gray-500' }}">Analytics & insights</div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('goals') }}"
                       class="group block px-4 py-3 rounded-xl text-base font-medium transition-all duration-200 {{ request()->routeIs('goals') ? 'bg-gradient-to-r from-primary-500 to-primary-600 text-white shadow-lg shadow-primary-500/25' : 'text-gray-700 hover:text-primary-600 hover:bg-primary-50 hover:shadow-md' }}">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg {{ request()->routeIs('goals') ? 'bg-white/20' : 'bg-primary-100' }} flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                                <svg class="w-5 h-5 {{ request()->routeIs('goals') ? 'text-white' : 'text-primary-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold">Goals</div>
                                <div class="text-sm {{ request()->routeIs('goals') ? 'text-white/80' : 'text-gray-500' }}">Track your progress</div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('interviews') }}"
                       class="group block px-4 py-3 rounded-xl text-base font-medium transition-all duration-200 {{ request()->routeIs('interviews') ? 'bg-gradient-to-r from-primary-500 to-primary-600 text-white shadow-lg shadow-primary-500/25' : 'text-gray-700 hover:text-primary-600 hover:bg-primary-50 hover:shadow-md' }}">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg {{ request()->routeIs('interviews') ? 'bg-white/20' : 'bg-primary-100' }} flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                                <svg class="w-5 h-5 {{ request()->routeIs('interviews') ? 'text-white' : 'text-primary-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold">Interviews</div>
                                <div class="text-sm {{ request()->routeIs('interviews') ? 'text-white/80' : 'text-gray-500' }}">Schedule & manage</div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('cv.builder') }}"
                       class="group block px-4 py-3 rounded-xl text-base font-medium transition-all duration-200 {{ request()->routeIs('cv.*') ? 'bg-gradient-to-r from-primary-500 to-primary-600 text-white shadow-lg shadow-primary-500/25' : 'text-gray-700 hover:text-primary-600 hover:bg-primary-50 hover:shadow-md' }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10 rounded-lg {{ request()->routeIs('cv.*') ? 'bg-white/20' : 'bg-primary-100' }} flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                                    <svg class="w-5 h-5 {{ request()->routeIs('cv.*') ? 'text-white' : 'text-primary-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold">CV Builder</div>
                                    <div class="text-sm {{ request()->routeIs('cv.*') ? 'text-white/80' : 'text-gray-500' }}">Create professional CV</div>
                                </div>
                            </div>
                            @if(auth()->user() && auth()->user()->is_premium && auth()->user()->payment_status === 'paid')
                                <span class="px-2 py-1 text-xs {{ request()->routeIs('cv.*') ? 'bg-white/20 text-white' : 'bg-primary-100 text-primary-700' }} rounded-full font-semibold">PRO</span>
                            @endif
                        </div>
                    </a>
                    
                    <!-- Mobile User Profile Section -->
                    <div class="mt-6 pt-4 border-t border-gray-200/60">
                        <div class="flex items-center px-4 py-3 bg-white/50 rounded-xl">
                            <div class="flex-shrink-0">
                                @if(Auth::user()->profile_photo_path)
                                    <img class="h-10 w-10 rounded-full object-cover ring-2 ring-white shadow-md" 
                                         src="{{ Auth::user()->profile_photo_url }}" 
                                         alt="{{ Auth::user()->name }}">
                                @else
                                    <div class="h-10 w-10 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full flex items-center justify-center shadow-md ring-2 ring-white">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-3 flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="{{ route('profile.edit') }}" 
                                   class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Mobile Action Buttons -->
                        <div class="mt-3 grid grid-cols-2 gap-2">
                            <a href="{{ route('profile.edit') }}" 
                               class="flex items-center justify-center px-4 py-2 bg-white/50 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Profile
                            </a>
                            <button onclick="openLogoutModal()" 
                                    class="flex items-center justify-center px-4 py-2 bg-white/50 text-gray-700 rounded-lg hover:bg-red-50 hover:text-red-600 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Logout
                            </button>
                        </div>
                    </div>
                @else
                    {{-- Admin Mobile Navigation --}}
                    <a href="{{ route('admin.index') }}"
                       class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.index') ? 'bg-primary-100 text-primary-600' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            </svg>
                            Dashboard
                        </div>
                    </a>
                    <a href="{{ route('admin.users') }}"
                       class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.users') ? 'bg-primary-100 text-primary-600' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            Users
                        </div>
                    </a>
                    <a href="{{ route('admin.payments') }}"
                       class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.payments') ? 'bg-primary-100 text-primary-600' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            Payments
                        </div>
                    </a>
                    <a href="{{ route('admin.analytics') }}"
                       class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.analytics') ? 'bg-primary-100 text-primary-600' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Analytics
                        </div>
                    </a>
                    <a href="{{ route('admin.monetization') }}"
                       class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.monetization') ? 'bg-primary-100 text-primary-600' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Monetization
                        </div>
                    </a>
                @endif
            </div>
        </div>
    </div>
</nav>

<!-- Logout Confirmation Modal -->
<div id="logoutModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-[100] flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95" id="logoutModalContent">
        <div class="p-6">
            <!-- Header -->
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Logout</h3>
                    <p class="text-sm text-gray-500">Apakah Anda yakin ingin keluar?</p>
                </div>
            </div>
            
            <!-- Content -->
            <div class="mb-6">
                <p class="text-gray-600 text-sm">Apakah Anda yakin ingin keluar?</p>
            </div>
            
            <!-- Actions -->
            <div class="flex space-x-3">
                <button onclick="closeLogoutModal()" 
                        class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                    Batal
                </button>
                <a href="{{ route('logout.force') }}" onclick="prepareLogout()"
                   class="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 text-center">
                    Ya, Logout
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Mobile menu functionality
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.querySelector('.mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const hamburgerIcon = mobileMenuButton?.querySelector('.block');
    const closeIcon = mobileMenuButton?.querySelector('.hidden');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            const isExpanded = mobileMenuButton.getAttribute('aria-expanded') === 'true';
            
            // Toggle aria-expanded
            mobileMenuButton.setAttribute('aria-expanded', !isExpanded);
            
            // Toggle mobile menu visibility with smooth animation
            if (isExpanded) {
                // Close menu
                mobileMenu.style.transform = 'translateY(-10px)';
                mobileMenu.style.opacity = '0';
                setTimeout(() => {
                    mobileMenu.classList.add('hidden');
                }, 200);
            } else {
                // Open menu
                mobileMenu.classList.remove('hidden');
                setTimeout(() => {
                    mobileMenu.style.transform = 'translateY(0)';
                    mobileMenu.style.opacity = '1';
                }, 10);
            }
            
            // Toggle icons
            if (hamburgerIcon && closeIcon) {
                hamburgerIcon.classList.toggle('block');
                hamburgerIcon.classList.toggle('hidden');
                closeIcon.classList.toggle('block');
                closeIcon.classList.toggle('hidden');
            }
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                if (mobileMenuButton.getAttribute('aria-expanded') === 'true') {
                    mobileMenuButton.click();
                }
            }
        });
        
        // Close menu on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && mobileMenuButton.getAttribute('aria-expanded') === 'true') {
                mobileMenuButton.click();
            }
        });
    }
});

function openLogoutModal() {
    const modal = document.getElementById('logoutModal');
    const modalContent = document.getElementById('logoutModalContent');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Animate modal
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
        modal.classList.remove('flex');
    }, 200);
}

// Close modal when clicking outside
document.getElementById('logoutModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLogoutModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeLogoutModal();
    }
});

// Export notification function
function showExportNotification() {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-white border border-gray-200 rounded-xl shadow-lg z-50 transform translate-x-full transition-all duration-300 backdrop-blur-sm';
    notification.innerHTML = `
        <div class="flex items-center px-4 py-3 space-x-3">
            <div class="w-8 h-8 bg-gradient-to-r from-primary-500 to-primary-700 rounded-full flex items-center justify-center">
                <svg class="w-4 h-4 text-white animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-900">Exporting CSV</p>
                <p class="text-xs text-gray-500">Download will start shortly...</p>
            </div>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
        notification.classList.add('translate-x-0');
    }, 100);
    
    // Remove after 2.5 seconds
    setTimeout(() => {
        notification.classList.remove('translate-x-0');
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 2500);
}

// Simple logout handling
document.addEventListener('DOMContentLoaded', function() {
    // Close modal when clicking logout link
    const logoutLink = document.querySelector('a[href*="logout-force"]');
    if (logoutLink) {
        logoutLink.addEventListener('click', function() {
            closeLogoutModal();
        });
    }
});

// Suppress browser confirm triggered by Livewire's 419 during logout only
function prepareLogout() {
    try { closeLogoutModal(); } catch (_) {}
    // Temporarily override confirm so any in-flight Livewire 419 won't show dialog
    const originalConfirm = window.confirm;
    window.confirm = function() { return false; };
    // Restore confirm after a few seconds (page likely navigated already)
    setTimeout(function() { window.confirm = originalConfirm; }, 8000);
}
</script>
