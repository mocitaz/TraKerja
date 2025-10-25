<x-admin-layout>
        <x-slot name="header">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between space-y-3 sm:space-y-0">
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-white/20 backdrop-blur-sm border border-white/30 flex items-center justify-center">
                        <img src="{{ asset('images/icon.png') }}" 
                             alt="TraKerja Logo" 
                             class="w-5 h-5 sm:w-6 sm:h-6"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg sm:text-2xl font-bold bg-gradient-to-r from-[#d983e4] to-[#4e71c5] bg-clip-text text-transparent">
                            <span class="hidden sm:inline">Admin Dashboard</span>
                            <span class="sm:hidden">Admin</span>
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5 hidden sm:block">Monitor dan kelola aplikasi TraKerja</p>
                    </div>
                </div>
            </div>
        </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                {{-- Total Users Card --}}
                <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-gray-600 mb-1 truncate">Total Users</p>
                            <p class="text-xl sm:text-2xl font-bold text-[#212529]">{{ \App\Models\User::where('role', '!=', 'admin')->count() }}</p>
                        </div>
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-primary-600 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Premium Users Card --}}
                <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-gray-600 mb-1 truncate">Premium Users</p>
                            <p class="text-xl sm:text-2xl font-bold text-[#212529]">{{ \App\Models\User::where('role', '!=', 'admin')->where('is_premium', true)->count() }}</p>
                        </div>
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Current Phase Card --}}
                <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-gray-600 mb-1 truncate">Current Phase</p>
                            <p class="text-xl sm:text-2xl font-bold text-[#212529]">Phase 1</p>
                        </div>
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-pink-500 to-pink-600 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Job Applications Card --}}
                <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-gray-600 mb-1 truncate">Applications</p>
                            <p class="text-xl sm:text-2xl font-bold text-[#212529]">{{ \App\Models\JobApplication::count() }}</p>
                        </div>
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <a href="{{ route('admin.users') }}" class="group bg-white rounded-lg p-3 sm:p-4 shadow-sm hover:shadow-md transition-all duration-200 border border-gray-200 hover:border-purple-300">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors flex-shrink-0">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-gray-700 truncate">Manage Users</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.payments') }}" class="group bg-white rounded-lg p-3 sm:p-4 shadow-sm hover:shadow-md transition-all duration-200 border border-gray-200 hover:border-purple-300">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors flex-shrink-0">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-gray-700 truncate">Monitor Payments</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.monetization') }}" class="group bg-white rounded-lg p-3 sm:p-4 shadow-sm hover:shadow-md transition-all duration-200 border border-gray-200 hover:border-purple-300">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors flex-shrink-0">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-gray-700 truncate">Control Monetization</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.analytics') }}" class="group bg-white rounded-lg p-3 sm:p-4 shadow-sm hover:shadow-md transition-all duration-200 border border-gray-200 hover:border-purple-300">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors flex-shrink-0">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-gray-700 truncate">View Analytics</p>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Monetization Control --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900">Monetization Control</h3>
                            <p class="text-xs sm:text-sm text-gray-500 hidden sm:block">Manage pricing phases and premium features</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 sm:p-6">
                    @livewire('admin.monetization-control')
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>
