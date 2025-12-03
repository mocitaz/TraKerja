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

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50 overflow-hidden">
        <div class="h-screen flex overflow-hidden">
            <!-- Sidebar -->
            <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 lg:static lg:inset-0 flex-shrink-0">
                <div class="flex flex-col h-full">
                    <!-- Logo & Brand -->
                    <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 flex-shrink-0">
                        <div class="flex items-center space-x-2">
                            <img src="{{ asset('images/icon.png') }}" 
                                 alt="TraKerja Logo" 
                                 class="h-8 w-8"
                                 onerror="this.style.display='none';">
                            <span class="text-lg font-bold bg-gradient-to-r from-[#d983e4] to-[#4e71c5] bg-clip-text text-transparent">
                                Admin
                            </span>
                        </div>
                        <button id="sidebar-close" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex-1 px-3 py-4 space-y-1 overflow-hidden">
                        <a href="{{ route('admin.index') }}"
                           class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.index') ? 'bg-primary-100 text-primary-600' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <a href="{{ route('admin.users') }}"
                           class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.users') ? 'bg-primary-100 text-primary-600' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <span>Users</span>
                        </a>

                        <a href="{{ route('admin.analytics') }}"
                           class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.analytics') ? 'bg-primary-100 text-primary-600' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span>Analytics</span>
                        </a>

                        <a href="{{ route('admin.payments') }}"
                           class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.payments*') ? 'bg-primary-100 text-primary-600' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            <span>Payments</span>
                        </a>

                        <a href="{{ route('admin.monetization') }}"
                           class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.monetization') ? 'bg-primary-100 text-primary-600' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Monetization</span>
                        </a>

                        <a href="{{ route('admin.email-blast') }}"
                           class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.email-blast*') ? 'bg-primary-100 text-primary-600' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span>Email Blast</span>
                        </a>
                    </nav>

                    <!-- User Section -->
                    <div class="border-t border-gray-200 p-4 flex-shrink-0">
                        <div class="flex items-center space-x-3 mb-3">
                            @php
                                $user = Auth::user();
                            @endphp
                            @if($user->logo)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($user->logo) }}" 
                                     alt="Profile Photo" 
                                     class="h-10 w-10 rounded-full object-cover border-2 border-gray-200">
                            @else
                                <div class="h-10 w-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center border-2 border-gray-200">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $user->name ?? 'Admin' }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $user->email ?? 'admin@example.com' }}</p>
                            </div>
                        </div>
                        <button type="button" onclick="openLogoutModal()" class="w-full flex items-center justify-center space-x-2 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors border border-gray-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </div>
                </div>
            </aside>

            <!-- Sidebar Overlay (Mobile) -->
            <div id="sidebar-overlay" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 lg:hidden hidden"></div>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col lg:ml-0 min-w-0">
                <!-- Mobile Menu Button (Floating) -->
                <button id="sidebar-toggle" class="fixed top-4 left-4 z-50 lg:hidden p-3 bg-white rounded-lg shadow-lg hover:bg-gray-50 transition-colors border border-gray-200">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

            <!-- Page Content -->
                <main class="flex-1 overflow-y-auto h-screen">
                {{ $slot }}
            </main>
            </div>
        </div>

        <!-- Logout Confirmation Modal -->
        <div id="logoutModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-[100] flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95" id="logoutModalContent">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-red-100 to-orange-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Sign Out</h3>
                            <p class="text-sm text-gray-500">Yakin anda mau logout?</p>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="mb-6">
                        <p class="text-gray-700 text-sm leading-relaxed mb-3">
                            Anda akan keluar dari akun <span class="font-semibold text-purple-600">{{ Auth::user()->name }}</span>.
                        </p>
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-3 rounded-r">
                            <p class="text-blue-800 text-xs leading-relaxed">
                                <strong>ℹ️ Catatan:</strong> Saat logout, sesi Anda akan berakhir secara otomatis. Pastikan semua pekerjaan Anda sudah disimpan sebelum melanjutkan.
                            </p>
                        </div>
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
            // Sidebar Toggle
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const toggleBtn = document.getElementById('sidebar-toggle');
            const closeBtn = document.getElementById('sidebar-close');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }

            toggleBtn?.addEventListener('click', openSidebar);
            closeBtn?.addEventListener('click', closeSidebar);
            overlay?.addEventListener('click', closeSidebar);

            // Close sidebar on escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !overlay.classList.contains('hidden')) {
                    closeSidebar();
                }
            });

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

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !document.getElementById('logoutModal').classList.contains('hidden')) {
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
