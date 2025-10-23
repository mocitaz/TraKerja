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
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <a href="{{ route('admin.index') }}" class="text-xl font-bold text-primary-800">
                                    <i class="fas fa-shield-alt text-primary-600 mr-2"></i>
                                    {{ config('app.name', 'TraKerja') }} Admin
                                </a>
                            </div>
                        </div>

                        <!-- Right Side -->
                        <div class="flex items-center space-x-4">
                            <!-- Back to App -->
                            <a href="{{ route('tracker') }}" class="text-primary-600 hover:text-primary-900">
                                <i class="fas fa-arrow-left mr-2"></i>Back to App
                            </a>

                            <!-- User Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = ! open" class="flex items-center text-sm font-medium text-primary-500 hover:text-primary-700 focus:outline-none transition duration-150 ease-in-out">
                                    <div>{{ Auth::user()->name }}</div>
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
                                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-primary-700 hover:bg-primary-50">
                                            <i class="fas fa-user-circle mr-2"></i>Profile
                                        </a>
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
                <!-- Sidebar -->
                <aside class="w-64 bg-white h-screen sticky top-0 border-r border-gray-200">
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

                        <a href="{{ route('admin.payments') }}" 
                           class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.payments*') ? 'bg-primary-50 text-primary-900' : 'text-primary-600 hover:bg-primary-50 hover:text-primary-900' }}">
                            <i class="fas fa-credit-card mr-4 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.payments*') ? 'text-primary-600' : 'text-primary-400' }}"></i>
                            Payments
                        </a>

                        <a href="{{ route('admin.monetization') }}" 
                           class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.monetization') ? 'bg-primary-50 text-primary-900' : 'text-primary-600 hover:bg-primary-50 hover:text-primary-900' }}">
                            <i class="fas fa-dollar-sign mr-4 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.monetization') ? 'text-primary-600' : 'text-primary-400' }}"></i>
                            Monetization
                        </a>

                        <a href="{{ route('admin.settings') }}" 
                           class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.settings') ? 'bg-primary-50 text-primary-900' : 'text-primary-600 hover:bg-primary-50 hover:text-primary-900' }}">
                            <i class="fas fa-cog mr-4 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.settings') ? 'text-primary-600' : 'text-primary-400' }}"></i>
                            Settings
                        </a>

                        <a href="{{ route('admin.analytics') }}" 
                           class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.analytics') ? 'bg-primary-50 text-primary-900' : 'text-primary-600 hover:bg-primary-50 hover:text-primary-900' }}">
                            <i class="fas fa-chart-line mr-4 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.analytics') ? 'text-primary-600' : 'text-primary-400' }}"></i>
                            Analytics
                        </a>
                    </nav>

                    <!-- Phase Indicator -->
                    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 bg-primary-50">
                        <div class="text-xs text-primary-500">Current Phase</div>
                        <div class="mt-1 flex items-center">
                            <span class="text-2xl mr-2">{{ phase_emoji(current_phase()) }}</span>
                            <div>
                                <div class="font-semibold text-sm">{{ phase_name(current_phase()) }}</div>
                                <div class="text-xs text-primary-500">Phase {{ current_phase() }}</div>
                            </div>
                        </div>
                    </div>
                </aside>

                <!-- Main Content -->
                <main class="flex-1">
                    <!-- Page Heading -->
                    @if (isset($header))
                        <header class="bg-white shadow">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endif

                    <!-- Page Content -->
                    <div class="py-12">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            {{ $slot }}
                        </div>
                    </div>
                </main>
            </div>
        </div>

        @livewireScripts
    </body>
</html>