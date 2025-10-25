<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-primary-600 to-secondary-500 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-primary-900">Admin Dashboard</h2>
                    <p class="text-sm text-primary-600">Monitor dan kelola aplikasi TraKerja</p>
                </div>
            </div>
            <div class="flex items-center gap-2 text-sm text-primary-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ now()->format('d M Y, H:i') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Enhanced Stats Cards with Gradients --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Total Users Card --}}
                <div class="group relative overflow-hidden bg-gradient-to-br from-primary-500 to-primary-700 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:scale-105">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-white/80 text-sm font-medium">Total Users</p>
                                <p class="text-4xl font-black text-white">{{ \App\Models\User::where('role', '!=', 'admin')->count() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-white/90 text-xs">
                            <span>Registered</span>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                <span class="font-semibold">+{{ \App\Models\User::where('role', '!=', 'admin')->whereDate('created_at', today())->count() }} today</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Premium Users Card --}}
                <div class="group relative overflow-hidden bg-gradient-to-br from-secondary-500 to-purple-700 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:scale-105">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-white/80 text-sm font-medium">Premium Users</p>
                                <p class="text-4xl font-black text-white">{{ \App\Models\User::where('role', '!=', 'admin')->where('is_premium', true)->count() }}</p>
                            </div>
                        </div>
                        @php
                            $totalUsers = \App\Models\User::where('role', '!=', 'admin')->count();
                            $premiumUsers = \App\Models\User::where('role', '!=', 'admin')->where('is_premium', true)->count();
                            $conversionRate = $totalUsers > 0 ? round(($premiumUsers / $totalUsers) * 100, 1) : 0;
                        @endphp
                        <div class="flex items-center justify-between text-white/90 text-xs">
                            <span>Conversion Rate</span>
                            <span class="font-semibold">{{ $conversionRate }}%</span>
                        </div>
                    </div>
                </div>

                {{-- Current Phase Card --}}
                <div class="group relative overflow-hidden bg-gradient-to-br from-accent-500 to-pink-600 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:scale-105">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center text-2xl">
                                {{ phase_emoji() }}
                            </div>
                            <div class="text-right">
                                <p class="text-white/80 text-sm font-medium">Current Phase</p>
                                <p class="text-4xl font-black text-white">Phase {{ current_phase() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-white/90 text-xs">
                            <span>{{ phase_name(current_phase()) }}</span>
                            <span class="px-2 py-1 bg-white/20 rounded-full font-semibold">Active</span>
                        </div>
                    </div>
                </div>

                {{-- Job Applications Card --}}
                <div class="group relative overflow-hidden bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:scale-105">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-white/80 text-sm font-medium">Applications</p>
                                <p class="text-4xl font-black text-white">{{ \App\Models\JobApplication::count() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-white/90 text-xs">
                            <span>Total Tracked</span>
                            <span class="font-semibold">All Users</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('admin.users') }}" class="group bg-white rounded-xl p-4 shadow hover:shadow-lg transition-all duration-300 hover:scale-105 border-2 border-transparent hover:border-primary-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center group-hover:bg-primary-200 transition-colors">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Manage</p>
                            <p class="text-sm font-bold text-gray-900">Users</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.payments') }}" class="group bg-white rounded-xl p-4 shadow hover:shadow-lg transition-all duration-300 hover:scale-105 border-2 border-transparent hover:border-secondary-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-secondary-100 rounded-lg flex items-center justify-center group-hover:bg-secondary-200 transition-colors">
                            <svg class="w-5 h-5 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Monitor</p>
                            <p class="text-sm font-bold text-gray-900">Payments</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.monetization') }}" class="group bg-white rounded-xl p-4 shadow hover:shadow-lg transition-all duration-300 hover:scale-105 border-2 border-transparent hover:border-purple-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Control</p>
                            <p class="text-sm font-bold text-gray-900">Monetization</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.analytics') }}" class="group bg-white rounded-xl p-4 shadow hover:shadow-lg transition-all duration-300 hover:scale-105 border-2 border-transparent hover:border-accent-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-accent-100 rounded-lg flex items-center justify-center group-hover:bg-accent-200 transition-colors">
                            <svg class="w-5 h-5 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">View</p>
                            <p class="text-sm font-bold text-gray-900">Analytics</p>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Monetization Control --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-primary-600 to-secondary-500 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Monetization Control</h3>
                            <p class="text-white/80 text-sm">Manage pricing phases and premium features</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @livewire('admin.monetization-control')
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>
