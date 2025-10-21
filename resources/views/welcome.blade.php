<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TraKerja - Professional Job Application Management Platform</title>
    <meta name="description" content="Transform your job search with TraKerja. The most sophisticated job application tracking platform designed for ambitious professionals in Indonesia.">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
            @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
            <style>
        .gradient-text {
            background: linear-gradient(135deg, #0056B3 0%, #1e40af 50%, #28A745 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-gradient {
            background: linear-gradient(135deg, #0056B3 0%, #1e40af 50%, #28A745 100%);
        }
        .floating-animation {
            animation: float 8s ease-in-out infinite;
        }
        .pulse-animation {
            animation: pulse 4s ease-in-out infinite;
        }
        .glow-effect {
            box-shadow: 0 0 40px rgba(0, 86, 179, 0.3);
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(2deg); }
        }
        @keyframes pulse {
            0%, 100% { opacity: 0.8; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.05); }
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .premium-shadow {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
            </style>
    </head>
<body class="font-sans antialiased bg-white">
    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-md border-b border-gray-200/50 shadow-sm sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">
                <!-- Left Section: Brand -->
                <div class="flex items-center">
                    <div class="shrink-0 flex items-center">
                        <a href="{{ url('/') }}" class="group">
                            <span class="text-xl font-bold bg-gradient-to-r from-[#0056B3] to-[#28A745] bg-clip-text text-transparent">
                                TraKerja
                            </span>
                        </a>
                    </div>
                </div>

                <!-- Right Section: Auth Links -->
                <div class="flex items-center space-x-3">
            @if (Route::has('login'))
                    @auth
                            <a href="{{ url('/tracker') }}" 
                               class="bg-gradient-to-r from-[#0056B3] to-[#28A745] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:shadow-lg transition-all duration-200">
                            Dashboard
                        </a>
                    @else
                            <a href="{{ route('login') }}" 
                               class="text-gray-600 hover:text-[#0056B3] text-sm font-medium transition-colors duration-200">
                                Login
                            </a>
                        @if (Route::has('register'))
                                <a href="{{ route('register') }}" 
                                   class="bg-gradient-to-r from-[#0056B3] to-[#28A745] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:shadow-lg transition-all duration-200">
                                    Daftar
                            </a>
                        @endif
                    @endauth
                    @endif
                </div>
            </div>
        </div>
                </nav>

    <!-- Hero Section -->
    <section class="relative py-20 overflow-hidden bg-gradient-to-br from-gray-50 to-white">
        <!-- Subtle Background Elements -->
        <div class="absolute top-20 right-20 w-32 h-32 bg-[#0056B3]/5 rounded-full mix-blend-multiply filter blur-xl floating-animation"></div>
        <div class="absolute bottom-20 left-20 w-24 h-24 bg-[#28A745]/5 rounded-full mix-blend-multiply filter blur-xl floating-animation" style="animation-delay: 3s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-40 h-40 bg-gradient-to-r from-[#0056B3]/3 to-[#28A745]/3 rounded-full mix-blend-multiply filter blur-2xl pulse-animation"></div>
        
        <div class="relative z-10 max-w-4xl mx-auto px-4 text-center">
            <!-- Badge -->
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-[#0056B3]/10 border border-[#0056B3]/20 text-sm font-medium text-[#0056B3] mb-8">
                <span class="w-2 h-2 bg-[#28A745] rounded-full mr-2"></span>
                Smart Job Application Tracking
            </div>
            
            <!-- Main Heading -->
            <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                Kelola Proses Rekrutmen dengan <span class="gradient-text">TraKerja</span>
            </h1>
            
            <!-- Subheading -->
            <p class="text-xl text-gray-600 mb-10 max-w-2xl mx-auto">
                Platform tracking job application yang simple dan efektif untuk job seeker Indonesia
            </p>
            
            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                @auth
                    <a href="{{ url('/tracker') }}" 
                       class="bg-gradient-to-r from-[#0056B3] to-[#28A745] text-white px-8 py-3 rounded-lg font-semibold hover:shadow-lg transition-all duration-200">
                        Buka Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" 
                       class="bg-gradient-to-r from-[#0056B3] to-[#28A745] text-white px-8 py-3 rounded-lg font-semibold hover:shadow-lg transition-all duration-200">
                        Mulai Gratis
                    </a>
                    <a href="{{ route('login') }}" 
                       class="border-2 border-[#0056B3] text-[#0056B3] px-8 py-3 rounded-lg font-semibold hover:bg-[#0056B3] hover:text-white transition-all duration-200">
                        Login
                    </a>
                @endauth
            </div>
            
            <!-- Trust Indicators -->
            <div class="flex items-center justify-center space-x-8 text-sm text-gray-500">
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-[#28A745] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Gratis Selamanya
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-[#28A745] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Setup 2 Menit
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-[#28A745] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Data Aman & Privat
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    Mulai dari yang Kecil, Tumbuh Bersama
                </h2>
                <p class="text-lg text-gray-600">
                    Platform baru yang dibangun khusus untuk job seeker Indonesia
                </p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-[#0056B3] mb-2">100+</div>
                    <div class="text-gray-600 font-medium">Pengguna Aktif</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-[#28A745] mb-2">500+</div>
                    <div class="text-gray-600 font-medium">Aplikasi Dilacak</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-[#0056B3] mb-2">1.5x</div>
                    <div class="text-gray-600 font-medium">Lebih Cepat Dapat Kerja</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-5xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    Fitur-Fitur TraKerja
                </h2>
                <p class="text-lg text-gray-600">
                    Tools sederhana tapi powerful untuk mengelola pencarian kerja Anda
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-[#0056B3] rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h2a2 2 0 002-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Kanban Board</h3>
                    <p class="text-gray-600 text-sm">Kelola aplikasi kerja dengan drag & drop yang mudah. Lihat progress lamaran Anda dalam satu tampilan.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-[#28A745] rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Analytics Dashboard</h3>
                    <p class="text-gray-600 text-sm">Lihat statistik aplikasi Anda. Platform mana yang paling efektif dan posisi apa yang paling sering dipanggil interview.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-[#0056B3] rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Smart Reminders</h3>
                    <p class="text-gray-600 text-sm">Dapatkan notifikasi untuk jadwal interview, deadline tugas, dan waktu yang tepat untuk follow-up.</p>
                </div>
                
                <!-- Feature 4 -->
                <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-[#28A745] rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Goal Tracking</h3>
                    <p class="text-gray-600 text-sm">Set target mingguan untuk aplikasi kerja dan pantau progress Anda. Tetap termotivasi dengan streak counter.</p>
                </div>
                
                <!-- Feature 5 -->
                <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-[#0056B3] rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Real-time Sync</h3>
                    <p class="text-gray-600 text-sm">Akses data Anda di mana saja. Update otomatis di semua device dan browser Anda.</p>
                </div>
                
                <!-- Feature 6 -->
                <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-[#28A745] rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Data Aman</h3>
                    <p class="text-gray-600 text-sm">Data Anda aman dengan enkripsi dan backup otomatis. Privasi Anda adalah prioritas kami.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    Kata Pengguna TraKerja
                </h2>
                <p class="text-lg text-gray-600">
                    Feedback dari job seeker yang sudah menggunakan TraKerja
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl p-6 shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-[#0056B3] rounded-full flex items-center justify-center text-white font-bold">
                            S
                        </div>
                        <div class="ml-3">
                            <div class="font-semibold text-gray-900">Sarah</div>
                            <div class="text-sm text-gray-500">Fresh Graduate</div>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm">"TraKerja membantu saya mengorganisir lamaran kerja dengan baik. Sekarang saya tahu mana yang sudah di-apply dan mana yang perlu di-follow up."</p>
                </div>
                
                <div class="bg-white rounded-xl p-6 shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-[#28A745] rounded-full flex items-center justify-center text-white font-bold">
                            A
                        </div>
                        <div class="ml-3">
                            <div class="font-semibold text-gray-900">Ahmad</div>
                            <div class="text-sm text-gray-500">Career Switcher</div>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm">"Platform yang simple tapi efektif. Analytics-nya membantu saya tahu platform mana yang paling sering memanggil interview."</p>
                </div>
                
                <div class="bg-white rounded-xl p-6 shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-[#0056B3] rounded-full flex items-center justify-center text-white font-bold">
                            M
                        </div>
                        <div class="ml-3">
                            <div class="font-semibold text-gray-900">Maya</div>
                            <div class="text-sm text-gray-500">Job Seeker</div>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm">"Goal tracking-nya bagus banget! Membantu saya tetap konsisten apply kerja setiap minggu. Akhirnya dapat kerja juga!"</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 hero-gradient">
        <div class="max-w-3xl mx-auto text-center px-4">
            <h2 class="text-3xl font-bold text-white mb-6">
                Siap Mengorganisir Pencarian Kerja Anda?
            </h2>
            <p class="text-lg text-white/90 mb-8">
                Bergabunglah dengan job seeker Indonesia yang sudah menggunakan TraKerja. 
                Mulai gratis dan rasakan perbedaannya.
            </p>
            
            @guest
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" 
                       class="bg-white text-[#0056B3] px-8 py-4 rounded-xl font-semibold text-lg hover:shadow-lg transition-all duration-300">
                        Daftar Gratis Sekarang
                    </a>
                    <a href="{{ route('login') }}" 
                       class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white hover:text-[#0056B3] transition-all duration-300">
                        Login
                    </a>
                </div>
            @else
                <a href="{{ url('/tracker') }}" 
                   class="bg-white text-[#0056B3] px-8 py-4 rounded-xl font-semibold text-lg hover:shadow-lg transition-all duration-300 inline-block">
                    Buka Dashboard
                </a>
            @endguest
            
            <div class="mt-6 text-white/70 text-sm">
                Gratis selamanya • Setup dalam 2 menit • Data aman & privat
            </div>
        </div>
    </section>

    <!-- Footer -->
    <x-footer />

    @livewireScripts
    </body>
</html>