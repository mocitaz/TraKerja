@props(['title' => 'Admin Dashboard'])

@php
    $totalUsers = \App\Models\User::where('role', '!=', 'admin')->count();
    $verifiedUsers = \App\Models\User::where('role', '!=', 'admin')->whereNotNull('email_verified_at')->count();
    $activeUsers = \App\Models\User::where('role', '!=', 'admin')
        ->where(function ($query) {
            $query->whereHas('experiences')
                ->orWhereHas('educations')
                ->orWhereHas('skills');
        })
        ->count();
    $totalApplications = \App\Models\JobApplication::count();
    $verifiedRatio = $totalUsers > 0 ? round(($verifiedUsers / $totalUsers) * 100, 1) : 0;
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

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans text-gray-900 antialiased bg-slate-100 overflow-hidden">
        <div class="h-screen flex overflow-hidden">
            <!-- Sidebar -->
            <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-white text-slate-900 shadow-2xl backdrop-blur-xl transform transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 lg:static lg:inset-0 flex-shrink-0 border border-slate-200">
                <div class="flex flex-col h-full">
                    <!-- Logo & Brand -->
                    <div class="flex flex-col gap-3 px-5 pb-5 pt-5 border-b border-slate-200 flex-shrink-0">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-2xl bg-white/10 flex items-center justify-center ring-1 ring-white/30">
                                    <img src="{{ asset('images/icon.png') }}" alt="TraKerja Logo" class="h-6 w-6" onerror="this.style.display='none';">
                                </div>
                            <div>
                                <p class="text-sm font-semibold tracking-[0.3em] uppercase text-slate-800">TraKerja</p>
                                <p class="text-xl font-bold text-slate-900 leading-tight">Admin</p>
                            </div>
                        </div>
                            <button id="sidebar-close" class="lg:hidden p-2 rounded-lg bg-white/10 hover:bg-white/20 transition-colors">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        </div>
                        <p class="text-[11px] uppercase tracking-[0.3em] text-slate-500">
                            Kontrol, insight, notifikasi real-time
                        </p>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-xs text-slate-600">
                            <p class="font-semibold text-slate-800">Control Room</p>
                            <p class="text-[11px] text-slate-500">Pantau metrik utama di satu tempat.</p>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex-1 px-3 py-4 flex flex-col gap-3 overflow-hidden">
                        @php
                            $navLinks = [
                                ['route'=>'admin.index','label'=>'Dashboard','icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                                ['route'=>'admin.users','label'=>'Users','icon'=>'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                                ['route'=>'admin.analytics','label'=>'Analytics','icon'=>'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                                ['route'=>'admin.payments','label'=>'Payments','icon'=>'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v10a3 3 0 003 3z'],
                                ['route'=>'admin.monetization','label'=>'Monetization','icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                                ['route'=>'admin.email-blast','label'=>'Email Blast','icon'=>'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                            ];
                        @endphp
                        @foreach($navLinks as $link)
                            @php $isActive = request()->routeIs($link['route'].'*'); @endphp
                            <a href="{{ route($link['route']) }}"
                               class="group flex items-center gap-3 rounded-2xl border px-4 py-3 text-sm font-medium transition-all duration-200 {{ $isActive ? 'border-transparent bg-gradient-to-r from-purple-50/70 via-purple-100/70 to-purple-50/70 text-purple-900 shadow-lg shadow-purple-100' : 'border-slate-200 text-slate-600 hover:border-purple-200 hover:text-purple-900' }}">
                                <span class="h-2 w-2 rounded-full bg-purple-500 {{ $isActive ? 'opacity-100' : 'opacity-20 group-hover:opacity-90' }}"></span>
                                <span class="flex-1 truncate">{{ $link['label'] }}</span>
                                <svg class="w-5 h-5 text-current" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}"/>
                            </svg>
                            </a>
                        @endforeach
                    </nav>


                    <!-- User Section -->
                    <div class="border-t border-slate-200 p-4 flex-shrink-0">
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
                        <a href="{{ route('profile.edit') }}" class="mt-3 inline-flex w-full items-center justify-center rounded-xl border border-purple-200 px-3 py-2 text-sm font-semibold text-purple-800 hover:bg-purple-50 transition">
                            <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0118 19.864M12 14l-6.16-3.422A12.083 12.083 0 006 19.864"></path>
                            </svg>
                            Edit Profile
                        </a>
                        <button type="button" onclick="openLogoutModal()" class="mt-3 w-full inline-flex items-center justify-center space-x-2 px-3 py-2 rounded-xl bg-red-600 text-white text-sm font-semibold shadow-sm shadow-red-500/40 transition hover:bg-red-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-red-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </div>
                </div>
            </aside>

            <!-- Sidebar Overlay (Mobile) -->
                <div id="sidebar-overlay" class="fixed inset-0 bg-gray-900 bg-opacity-35 z-40 lg:hidden hidden"></div>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col lg:ml-0 min-w-0">
                <!-- Mobile Menu Button (Floating) -->
                <button id="sidebar-toggle" class="fixed top-4 left-4 z-50 lg:hidden p-3 bg-white rounded-xl shadow-2xl hover:bg-gray-50 transition-colors border border-gray-200">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <main class="flex-1 overflow-y-auto">
                    <div class="relative px-4 sm:px-6 lg:px-8 py-6">
                        <div class="max-w-6xl mx-auto space-y-5">
                            <div class="rounded-[32px] border border-slate-100 bg-white shadow-lg p-6 sm:p-8">
                                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                    <div class="space-y-1">
                                        <p class="text-[10px] uppercase tracking-[0.3em] text-slate-400">TraKerja Control Room</p>
                                        <div class="flex items-baseline gap-2">
                                            <h1 class="text-2xl font-semibold text-slate-900">{{ $title }}</h1>
                                            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-semibold uppercase tracking-[0.3em] text-slate-500">Live</span>
                                        </div>
                                        <p class="text-xs sm:text-sm text-slate-500 max-w-2xl">
                                            Pantau metrik penting tanpa padding yang berlebihan – cepat, langsung dan responsif.
                                        </p>
                                    </div>
                                    <div class="mt-5 rounded-2xl border border-slate-100 bg-white/80 p-4 shadow-sm">
                                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                                            <article class="text-center rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                                                <div class="flex h-full flex-col items-center justify-center space-y-2 text-center">
                                                    <p class="text-[10px] uppercase tracking-[0.4em] text-slate-400">Total Users</p>
                                                    <p class="text-2xl font-bold text-slate-900">{{ number_format($totalUsers) }}</p>
                                                    <p class="text-[11px] text-slate-500">{{ number_format($verifiedUsers) }} verified ({{ $verifiedRatio }}%)</p>
                                                </div>
                                            </article>
                                            <article class="text-center rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                                                <div class="flex h-full flex-col items-center justify-center space-y-2 text-center">
                                                    <p class="text-[10px] uppercase tracking-[0.4em] text-slate-400">Verified Users</p>
                                                    <p class="text-2xl font-bold text-slate-900">{{ number_format($verifiedUsers) }}</p>
                                                    <p class="text-[11px] text-slate-500">Akun siap diverifikasi/disetujui.</p>
                                                </div>
                                            </article>
                                            <article class="text-center rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                                                <div class="flex h-full flex-col items-center justify-center space-y-2 text-center">
                                                    <p class="text-[10px] uppercase tracking-[0.4em] text-slate-400">Active Users</p>
                                                    <p class="text-2xl font-bold text-slate-900">{{ number_format($activeUsers) }}</p>
                                                    <p class="text-[11px] text-slate-500">Profil lengkap pengalaman/skill.</p>
                                                </div>
                                            </article>
                                            <article class="text-center rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                                                <div class="flex h-full flex-col items-center justify-center space-y-2 text-center">
                                                    <p class="text-[10px] uppercase tracking-[0.4em] text-slate-400">App.</p>
                                                    <p class="text-2xl font-bold text-slate-900">{{ number_format($totalApplications) }}</p>
                                                    <p class="text-[11px] text-slate-500">Status siap diproses.</p>
                                                </div>
                                            </article>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap gap-2 text-xs font-semibold">
                                        <a href="{{ route('admin.analytics') }}"
                                           class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-purple-100 via-purple-100 to-purple-200 px-4 py-2 text-purple-800 shadow-sm shadow-purple-200 transition hover:-translate-y-0.5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-purple-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m4-12h-6l-2 2H5v12h14V7z"></path>
                                            </svg>
                                            Analitik
                                        </a>
                                        <a href="{{ route('admin.email-blast') }}"
                                           class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-purple-100 via-purple-100 to-purple-200 px-4 py-2 text-purple-800 shadow-sm shadow-purple-200 transition hover:border-purple-300">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            Email
                                        </a>
                                    </div>
                                </div>

                            </div>
                            <div class="relative z-10 space-y-6">
                                <div class="h-px w-full bg-slate-100"></div>
                                <section class="space-y-6">
                {{ $slot }}
                                </section>
                            </div>
                        </div>
                    </div>
            </main>
            </div>
        </div>

        <!-- Logout Confirmation Modal -->
        <div id="logoutModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-[100] flex items-center justify-center p-4">
            <div class="relative w-full max-w-md rounded-3xl border border-slate-200 bg-white/95 shadow-2xl p-1" id="logoutModalContent">
                <div class="rounded-3xl bg-white shadow-inner px-6 py-6">
                    <!-- Header -->
                    <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="h-12 w-12 rounded-2xl bg-purple-50 flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </div>
                        <div>
                                <p class="text-xs uppercase tracking-[0.4em] text-slate-400">TraKerja Control Room</p>
                                <h3 class="text-xl font-semibold text-slate-900">Sign Out</h3>
                            </div>
                        </div>
                        <button class="rounded-full p-2 text-slate-500 hover:text-slate-700 transition" onclick="closeLogoutModal()" aria-label="Close">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Body -->
                    <div class="space-y-4 pt-4">
                        <p class="text-sm text-slate-700">
                            Anda akan keluar dari akun <span class="font-semibold text-purple-600">{{ Auth::user()->name }}</span>. Pastikan semua pekerjaan sudah disimpan sebelum melanjutkan.
                        </p>
                        <div class="rounded-2xl border border-purple-100 bg-purple-50 px-4 py-3 text-sm text-purple-800 flex items-start gap-2">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-xs text-purple-900 leading-relaxed">
                                Catatan: Saat logout, sesi akan berakhir otomatis. Pastikan tidak ada aktivitas yang belum tersimpan.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="mt-6 flex flex-wrap gap-3 justify-end">
                        <button onclick="closeLogoutModal()" 
                                class="px-4 py-2 rounded-2xl border border-purple-200 text-purple-800 text-sm font-semibold hover:border-purple-300 transition">
                            Batal
                        </button>
                        <a href="{{ route('logout.force') }}" onclick="prepareLogout()"
                           class="px-5 py-2 rounded-2xl bg-red-600 text-white text-sm font-semibold shadow-sm shadow-red-500/40 transition hover:bg-red-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-red-200 text-center">
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
