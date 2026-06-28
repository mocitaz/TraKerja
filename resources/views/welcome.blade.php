<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>TraKerja - Platform Manajemen & Pelacakan Lamaran Kerja #1 di Indonesia</title>
        <meta name="description" content="TraKerja adalah platform ATS & tracker lamaran kerja gratis. Pantau status lamaran, buat CV standar ATS, dan dapatkan insight analitik untuk karir impian Anda.">
        <meta name="keywords" content="loker, lowongan kerja, tracker lamaran kerja, ats checker, cv ats friendly, karir, hrd, job portal, trakerja, manajemen lamaran">
        <meta name="author" content="PT. Teknalogi Transformasi Digital">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="{{ url('/') }}">
        <meta name="theme-color" content="#ffffff">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url('/') }}">
        <meta property="og:title" content="TraKerja - Platform Manajemen & Pelacakan Lamaran Kerja">
        <meta property="og:description" content="Tingkatkan peluang lolos kerja dengan tracker cerdas, AI Cover Letter, dan analitik lengkap. Gratis untuk pencari kerja Indonesia.">
        <meta property="og:image" content="{{ asset('images/fitur-section.jpg') }}">

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ url('/') }}">
        <meta property="twitter:title" content="TraKerja - Aplikasi Pelacakan Karir & Pekerjaan">
        <meta property="twitter:description" content="Pantau status lamaran, buat CV standar ATS, dan analisis skor interview Anda.">
        <meta property="twitter:image" content="{{ asset('images/fitur-section.jpg') }}">

        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        <script src="https://unpkg.com/@phosphor-icons/web"></script>

        <style>
            * {
                scroll-behavior: smooth;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }
            body {
                font-family: 'Inter', sans-serif;
            }
        </style>
    </head>
<body class="bg-white text-zinc-800 antialiased selection:bg-zinc-100 selection:text-zinc-900">

    <!-- Navigation Header -->
    <nav class="fixed top-0 left-0 right-0 h-12 bg-white/70 backdrop-blur-md border-b border-zinc-200/50 z-50">
        <div class="max-w-5xl mx-auto px-4 h-full flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-1.5 group select-none">
                <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-5 h-5 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="w-5 h-5 bg-zinc-900 rounded flex items-center justify-center text-white text-[9px] font-bold" style="display: none;">T</div>
                <span class="text-xs font-bold tracking-tight text-zinc-900">TraKerja</span>
            </a>

            <!-- Menu Navigation -->
            <div class="hidden md:flex items-center gap-6 text-[11px] font-medium text-zinc-500">
                <a href="#fitur" class="hover:text-zinc-900 transition-colors">Fitur</a>
                <a href="#cara-kerja" class="hover:text-zinc-900 transition-colors">Cara Kerja</a>
                <a href="#pricing" class="hover:text-zinc-900 transition-colors">Harga</a>
                <a href="#faq" class="hover:text-zinc-900 transition-colors">FAQ</a>
            </div>

            <!-- Action Controls -->
            <div class="flex items-center gap-2">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/tracker') }}" class="h-7 px-3 bg-zinc-900 text-white rounded-md text-[11px] font-semibold hover:bg-zinc-800 transition-all flex items-center gap-1">
                            <i class="ph-bold ph-columns text-xs"></i>
                            <span>Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-[11px] font-semibold text-zinc-500 hover:text-zinc-900 px-2.5 py-1 transition-colors">
                            Login
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="h-7 px-3 bg-zinc-900 hover:bg-zinc-800 text-white rounded-md text-[11px] font-semibold shadow-3xs transition-all flex items-center">
                                Mulai Gratis
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section (Cupertino Layout) -->
    <section class="relative pt-24 pb-16 bg-white">
        <div class="max-w-3xl mx-auto px-4 text-center flex flex-col items-center">
            <!-- Badge -->
            <div class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-zinc-50 border border-zinc-200 text-zinc-650 font-mono text-[9px] rounded font-bold uppercase tracking-wider mb-6 select-none shadow-3xs">
                <span class="w-1.5 h-1.5 bg-[#4e71c5] rounded-full animate-pulse"></span>
                <span>#1 Job Tracking Platform Indonesia</span>
            </div>

            <!-- Big Apple Title -->
            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black text-zinc-900 tracking-tighter leading-[1.05] mb-6 max-w-2xl select-none">
                Kelola lamaran kerja lebih cerdas.
            </h1>

            <!-- Subheading -->
            <p class="text-sm sm:text-base text-zinc-500 max-w-xl font-medium antialiased leading-relaxed mb-8 select-none">
                Platform pelacakan lamaran kerja yang sederhana, efektif, dan gratis. Dipercaya oleh pencari kerja Indonesia untuk membangun karier impian.
            </p>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center justify-center gap-3 mb-16">
                @auth
                    <a href="{{ url('/tracker') }}" class="h-9 px-4.5 bg-zinc-900 hover:bg-zinc-800 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-1.5">
                        <i class="ph-bold ph-columns text-sm"></i>
                        <span>Buka Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('register') }}" class="h-9 px-4.5 bg-zinc-900 hover:bg-zinc-800 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-1.5">
                        <i class="ph-bold ph-lightning text-sm"></i>
                        <span>Mulai Gratis</span>
                    </a>
                    <a href="{{ route('login') }}" class="h-9 px-4.5 bg-white border border-zinc-250 hover:bg-zinc-50 text-zinc-700 hover:text-zinc-900 rounded-lg text-xs font-bold transition-all flex items-center">
                        Masuk ke Akun
                    </a>
                @endauth
            </div>

            <!-- Mockup Showcase (Symmetric Cupertino style wrapper) -->
            <div class="w-full max-w-4xl border border-zinc-200 rounded-xl bg-white p-1 shadow-3xs">
                <div class="relative overflow-hidden rounded-lg border border-zinc-250/80 bg-white aspect-[16/10]">
                    <img src="{{ asset('images/mu-trakerja.png') }}" alt="TraKerja Dashboard" class="w-full h-full object-cover block" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="absolute inset-0 bg-zinc-50/50 flex items-center justify-center" style="display: none;">
                        <span class="text-zinc-400 text-xs font-mono font-bold uppercase tracking-wider">Dashboard Mockup (mu-trakerja.png)</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Bento Grid Section -->
    <section id="fitur" class="py-20 bg-white border-t border-zinc-200/60">
        <div class="max-w-5xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center max-w-xl mx-auto mb-16">
                <p class="font-mono text-[9px] tracking-wider text-zinc-400 font-bold uppercase mb-2">Bento Features</p>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-zinc-900 tracking-tight mb-3">Semua kebutuhan karir dalam satu workspace.</h2>
                <p class="text-xs sm:text-sm text-zinc-500 font-medium">Lupakan spreadsheet kaku. Atur seluruh alur pencarian kerja Anda dengan instrumen premium yang terpadu.</p>
            </div>

            <!-- Bento Grid Layout -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Card 1: Kanban Board Tracker (Large - span 2) -->
                <div class="bg-white border border-zinc-200/70 rounded-xl p-6 hover:border-zinc-400 hover:shadow-xs transition-all duration-200 md:col-span-2 flex flex-col justify-between aspect-[16/10] md:aspect-auto">
                    <div class="mb-6">
                        <div class="w-7 h-7 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-800 mb-4 shadow-3xs">
                            <i class="ph-bold ph-kanban text-sm"></i>
                        </div>
                        <h3 class="text-sm font-bold text-zinc-900 mb-1">Kanban Board Tracker</h3>
                        <p class="text-xs text-zinc-500 leading-relaxed max-w-md">Lacak kemajuan setiap lowongan dari On Process, Interview, Offering, hingga Rejected. Pindahkan status secara instan dan rapi layaknya Notion database.</p>
                    </div>
                    <div class="border border-zinc-200/60 rounded overflow-hidden bg-zinc-50/50 p-0.5 shadow-3xs">
                        <img src="{{ asset('images/fitur-section.jpg') }}" alt="Tracker Preview" class="w-full h-24 object-cover rounded" onerror="this.style.display='none';">
                    </div>
                </div>

                <!-- Card 2: AI Cover Letter (Small - span 1) -->
                <div class="bg-white border border-zinc-200/70 rounded-xl p-6 hover:border-zinc-400 hover:shadow-xs transition-all duration-200 flex flex-col justify-between">
                    <div>
                        <div class="w-7 h-7 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-800 mb-4 shadow-3xs">
                            <i class="ph-bold ph-sparkle text-sm"></i>
                        </div>
                        <h3 class="text-sm font-bold text-zinc-900 mb-1">AI Resume & Cover Letter</h3>
                        <p class="text-xs text-zinc-500 leading-relaxed">Analisis CV Anda terhadap deskripsi pekerjaan secara instan. Buat surat lamaran kerja terpersonalisasi menggunakan kecerdasan buatan.</p>
                    </div>
                    <div class="mt-8 flex items-center gap-1.5 p-2 bg-zinc-50 border border-zinc-200/80 rounded text-[9.5px] font-semibold text-zinc-650 select-none w-max">
                        <i class="ph-bold ph-check-circle text-emerald-600 text-xs"></i>
                        <span>ATS Score: 92% (Optimal)</span>
                    </div>
                </div>

                <!-- Card 3: Chrome Auto-Fill Extension (Small - span 1) -->
                <div class="bg-white border border-zinc-200/70 rounded-xl p-6 hover:border-zinc-400 hover:shadow-xs transition-all duration-200 flex flex-col justify-between">
                    <div>
                        <div class="w-7 h-7 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-800 mb-4 shadow-3xs">
                            <i class="ph-bold ph-plug text-sm"></i>
                        </div>
                        <h3 class="text-sm font-bold text-zinc-900 mb-1">Auto-Fill Extension</h3>
                        <p class="text-xs text-zinc-500 leading-relaxed">Impor rincian pekerjaan langsung dari LinkedIn, JobStreet, Glints, Kalibrr, Dealls, dan Talentics hanya dengan satu kali klik.</p>
                    </div>
                    <div class="mt-8 flex items-center gap-1.5 px-2 py-0.5 border border-zinc-200 bg-zinc-50 rounded text-[9px] text-zinc-500 font-bold select-none w-max">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                        <span>Extension Connected</span>
                    </div>
                </div>

                <!-- Card 4: Insightful Analytics (Large - span 2) -->
                <div class="bg-white border border-zinc-200/70 rounded-xl p-6 hover:border-zinc-400 hover:shadow-xs transition-all duration-200 md:col-span-2 flex flex-col justify-between aspect-[16/10] md:aspect-auto">
                    <div class="mb-6">
                        <div class="w-7 h-7 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-800 mb-4 shadow-3xs">
                            <i class="ph-bold ph-chart-line-up text-sm"></i>
                        </div>
                        <h3 class="text-sm font-bold text-zinc-900 mb-1">Analytics & Salary Estimation</h3>
                        <p class="text-xs text-zinc-500 leading-relaxed max-w-md">Pantau conversion rate lamaran Anda. Dapatkan taksiran gaji kompetitif yang disesuaikan dengan jenis role serta kualifikasi UMK regional.</p>
                    </div>
                    <div class="grid grid-cols-3 gap-2 bg-zinc-50/50 border border-zinc-200/80 rounded-lg p-2.5 text-center select-none shadow-3xs">
                        <div>
                            <div class="text-sm font-extrabold text-zinc-900">84%</div>
                            <div class="text-[8px] text-zinc-400 font-bold uppercase tracking-wider">Applied</div>
                        </div>
                        <div>
                            <div class="text-sm font-extrabold text-zinc-900">12%</div>
                            <div class="text-[8px] text-[#4e71c5] font-bold uppercase tracking-wider">Interviews</div>
                        </div>
                        <div>
                            <div class="text-sm font-extrabold text-zinc-900">4%</div>
                            <div class="text-[8px] text-emerald-600 font-bold uppercase tracking-wider">Offers</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section (Notion Sidebar Stepper Style) -->
    <section id="cara-kerja" class="py-20 bg-white border-t border-zinc-200/60">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center max-w-xl mx-auto mb-16">
                <p class="font-mono text-[9px] tracking-wider text-zinc-400 font-bold uppercase mb-2">Workflow</p>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-zinc-900 tracking-tight mb-3">Bagaimana cara kerjanya?</h2>
                <p class="text-xs sm:text-sm text-zinc-500 font-medium">Alur kerja terpadu untuk merapikan seluruh manajemen lamaran kerja Anda.</p>
            </div>

            <!-- Stepper Content -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
                <!-- Steppers sidebar -->
                <div class="space-y-3">
                    <!-- Step 1 -->
                    <div onclick="toggleHowItWorks(1)" id="hiw-trigger-1" class="hiw-card cursor-pointer border-l-2 border-zinc-900 bg-zinc-50/60 p-4 rounded-r-lg transition-all duration-200 select-none">
                        <div class="flex items-center gap-3">
                            <span class="font-mono text-xs font-bold text-zinc-800" id="hiw-num-1">01</span>
                            <h3 class="text-xs font-bold text-zinc-900">Salin URL & Auto-Fill Data</h3>
                        </div>
                        <div id="hiw-content-1" class="mt-2 text-[11px] text-zinc-500 leading-relaxed pl-7">
                            Gunakan ekstensi web kami atau salin URL lamaran kerja eksternal. Sistem pengisi otomatis kami akan mengekstrak detail data nama posisi, instansi perusahaan, serta informasi lokasi kota.
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div onclick="toggleHowItWorks(2)" id="hiw-trigger-2" class="hiw-card cursor-pointer border-l border-zinc-200 hover:border-zinc-300 p-4 rounded-r-lg transition-all duration-200 select-none">
                        <div class="flex items-center gap-3">
                            <span class="font-mono text-xs font-bold text-zinc-400" id="hiw-num-2">02</span>
                            <h3 class="text-xs font-bold text-zinc-400">Lacak Siklus Lamaran</h3>
                        </div>
                        <div id="hiw-content-2" class="mt-2 text-[11px] text-zinc-500 leading-relaxed pl-7 hidden">
                            Pantau kemajuan rekrutmen lamaran Anda secara tersusun. Tambahkan jadwal wawancara, riwayat tes tertulis, serta data korespondensi dalam satu tempat terpusat.
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div onclick="toggleHowItWorks(3)" id="hiw-trigger-3" class="hiw-card cursor-pointer border-l border-zinc-200 hover:border-zinc-300 p-4 rounded-r-lg transition-all duration-200 select-none">
                        <div class="flex items-center gap-3">
                            <span class="font-mono text-xs font-bold text-zinc-400" id="hiw-num-3">03</span>
                            <h3 class="text-xs font-bold text-zinc-400">Optimasi CV via AI</h3>
                        </div>
                        <div id="hiw-content-3" class="mt-2 text-[11px] text-zinc-500 leading-relaxed pl-7 hidden">
                            Manfaatkan model kecerdasan buatan untuk mengecek skor kelolosan CV Anda. Buat draf surat lamaran kerja terpersonalisasi yang cocok dengan spesifikasi deskripsi lowongan.
                        </div>
                    </div>
                </div>

                <!-- Preview Board -->
                <div class="border border-zinc-200/80 bg-zinc-50/50 p-1 rounded-xl shadow-3xs">
                    <div class="relative overflow-hidden rounded-lg aspect-video bg-white">
                        <img id="how-it-works-img-1" src="{{ asset('images/fitur-section.jpg') }}" alt="Step 1 Preview" class="how-it-works-image w-full h-full object-cover transition-opacity duration-200 opacity-100">
                        <img id="how-it-works-img-2" src="{{ asset('images/fitur-section.jpg') }}" alt="Step 2 Preview" class="how-it-works-image w-full h-full object-cover transition-opacity duration-200 opacity-0 absolute inset-0 hidden">
                        <img id="how-it-works-img-3" src="{{ asset('images/fitur-section.jpg') }}" alt="Step 3 Preview" class="how-it-works-image w-full h-full object-cover transition-opacity duration-200 opacity-0 absolute inset-0 hidden">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 bg-white border-t border-zinc-200/60">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <!-- Header -->
            <div class="max-w-xl mx-auto mb-16">
                <p class="font-mono text-[9px] tracking-wider text-zinc-400 font-bold uppercase mb-2">Testimonials</p>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-zinc-900 tracking-tight mb-3">Apa Kata Pencari Kerja?</h2>
                <p class="text-xs sm:text-sm text-zinc-500 font-medium">Cerita sukses mereka yang berhasil mengamankan pekerjaan impian bersama TraKerja.</p>
            </div>

            <!-- Clean Static Quotes Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-left">
                <div class="bg-white border border-zinc-200/80 rounded-xl p-5 hover:border-zinc-350 transition-all shadow-3xs">
                    <p class="text-xs text-zinc-650 italic leading-relaxed mb-4">"Sebelumnya saya melacak puluhan lamaran kerja menggunakan Excel dan seringkali kehilangan catatan tindak lanjut. Dengan papan Kanban TraKerja, seluruh aktivitas lamaran kerja saya menjadi tersusun sangat rapi dan praktis."</p>
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-full bg-zinc-100 flex items-center justify-center font-bold text-zinc-700 text-[10px]">LF</div>
                        <div>
                            <h4 class="text-xs font-bold text-zinc-800">Luthfi Fauzi</h4>
                            <p class="text-[9px] text-zinc-400">Software Engineer</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white border border-zinc-200/80 rounded-xl p-5 hover:border-zinc-350 transition-all shadow-3xs">
                    <p class="text-xs text-zinc-650 italic leading-relaxed mb-4">"Fitur Chrome Auto-Fill Extension sangat membantu! Saya tidak perlu lagi mengetik manual setiap kali melihat lowongan bagus di LinkedIn atau JobStreet. Tombol Auto-Fill-nya sangat cepat dan responsif."</p>
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-full bg-zinc-100 flex items-center justify-center font-bold text-zinc-700 text-[10px]">RN</div>
                        <div>
                            <h4 class="text-xs font-bold text-zinc-800">Rian Nugraha</h4>
                            <p class="text-[9px] text-zinc-400">Data Analyst</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section (Apple Store Style Pricing Panels) -->
    <section id="pricing" class="py-20 bg-white border-t border-zinc-200/60">
        <div class="max-w-5xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center max-w-xl mx-auto mb-16">
                <p class="font-mono text-[9px] tracking-wider text-zinc-400 font-bold uppercase mb-2">Pricing Plans</p>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-zinc-900 tracking-tight mb-3">Pilih paket sesuai kebutuhan.</h2>
                <p class="text-xs sm:text-sm text-zinc-500 font-medium">Akses tracker gratis selamanya atau naikkan level persiapan dengan asisten AI terintegrasi.</p>
            </div>

            <!-- Pricing Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch">
                <!-- Free Plan -->
                <div class="bg-white border border-zinc-200 rounded-xl p-6 flex flex-col justify-between hover:border-zinc-300 transition-colors">
                    <div>
                        <div class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-2">Free Tracker</div>
                        <div class="flex items-baseline gap-1.5 mb-4">
                            <span class="text-3xl font-extrabold text-zinc-900">Rp 0</span>
                            <span class="text-[9px] font-mono text-zinc-450 font-bold uppercase">selamanya</span>
                        </div>
                        <p class="text-xs text-zinc-500 mb-6 leading-relaxed">Paket dasar untuk pelacakan lamaran kerja secara tertata dan rapi.</p>
                        <div class="border-t border-zinc-100 pt-6 space-y-3">
                            <div class="flex items-center gap-2 text-xs text-zinc-650">
                                <i class="ph-bold ph-check text-emerald-600 text-sm shrink-0"></i>
                                <span>Manajemen Papan Kanban</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-zinc-650">
                                <i class="ph-bold ph-check text-emerald-600 text-sm shrink-0"></i>
                                <span>Auto-Fill Ekstensi (LinkedIn & JobStreet)</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-zinc-650">
                                <i class="ph-bold ph-check text-emerald-600 text-sm shrink-0"></i>
                                <span>Statistik Dasar Lamaran</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('register') }}" class="mt-8 h-8 bg-zinc-50 hover:bg-zinc-100 text-zinc-700 border border-zinc-250 rounded-lg text-xs font-bold transition-all flex items-center justify-center">
                        Mulai Gratis
                    </a>
                </div>

                <!-- Premium Pro (Apple Store Outline Highlight) -->
                <div class="bg-white border-2 border-zinc-900 rounded-xl p-6 flex flex-col justify-between relative shadow-3xs">
                    <div class="absolute -top-2.5 left-1/2 -translate-x-1/2 px-2.5 py-0.5 bg-zinc-900 text-white text-[8px] font-mono font-bold uppercase tracking-wider rounded">
                        Pilihan Utama
                    </div>
                    <div>
                        <div class="text-[9px] font-mono font-bold text-[#4e71c5] uppercase tracking-wider mb-2 mt-1">Premium Pro</div>
                        <div class="flex items-baseline gap-1.5 mb-4">
                            <span class="text-3xl font-extrabold text-zinc-900">Rp 15k</span>
                            <span class="text-[9px] font-mono text-zinc-450 font-bold uppercase">/ bulan</span>
                        </div>
                        <p class="text-xs text-zinc-500 mb-6 leading-relaxed">Akses penuh fitur AI cerdas untuk memperbesar rasio kelolosan proses lamaran kerja.</p>
                        <div class="border-t border-zinc-100 pt-6 space-y-3">
                            <div class="flex items-center gap-2 text-xs text-zinc-650">
                                <i class="ph-bold ph-check text-zinc-900 text-sm shrink-0"></i>
                                <span>Seluruh Fitur Free Tracker</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-zinc-650">
                                <i class="ph-bold ph-check text-zinc-900 text-sm shrink-0"></i>
                                <span>AI Resume & Cover Letter Analyzer</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-zinc-650">
                                <i class="ph-bold ph-check text-zinc-900 text-sm shrink-0"></i>
                                <span>Smart Salary Estimation</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-zinc-650">
                                <i class="ph-bold ph-check text-zinc-900 text-sm shrink-0"></i>
                                <span>Custom Export CSV / Data Excel</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('register') }}" class="mt-8 h-8 bg-zinc-900 hover:bg-zinc-800 text-white rounded-lg text-xs font-bold transition-all flex items-center justify-center shadow-3xs">
                        Tingkatkan Karir
                    </a>
                </div>

                <!-- Custom / Team -->
                <div class="bg-white border border-zinc-200 rounded-xl p-6 flex flex-col justify-between hover:border-zinc-300 transition-colors">
                    <div>
                        <div class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-2">Corporate & Teams</div>
                        <div class="flex items-baseline gap-1.5 mb-4">
                            <span class="text-3xl font-extrabold text-zinc-900">Hubungi</span>
                        </div>
                        <p class="text-xs text-zinc-500 mb-6 leading-relaxed">Solusi khusus untuk agensi rekrutmen, portal loker, atau tim pencari kerja skala besar.</p>
                        <div class="border-t border-zinc-100 pt-6 space-y-3">
                            <div class="flex items-center gap-2 text-xs text-zinc-650">
                                <i class="ph-bold ph-check text-emerald-600 text-sm shrink-0"></i>
                                <span>Custom Integration API</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-zinc-650">
                                <i class="ph-bold ph-check text-emerald-600 text-sm shrink-0"></i>
                                <span>Dedicated Database Server</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-zinc-650">
                                <i class="ph-bold ph-check text-emerald-600 text-sm shrink-0"></i>
                                <span>Support SLA Prioritas 24/7</span>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="openContactWidget()" class="mt-8 h-8 bg-zinc-50 hover:bg-zinc-100 text-zinc-700 border border-zinc-250 rounded-lg text-xs font-bold transition-all flex items-center justify-center">
                        Hubungi Penjualan
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section (Borderless accordion lines) -->
    <section id="faq" class="py-20 bg-white border-t border-zinc-200/60">
        <div class="max-w-2xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center max-w-xl mx-auto mb-16">
                <p class="font-mono text-[9px] tracking-wider text-zinc-400 font-bold uppercase mb-2">FAQ</p>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-zinc-900 tracking-tight mb-3">Pertanyaan Umum</h2>
                <p class="text-xs sm:text-sm text-zinc-500 font-medium">Temukan tanggapan cepat untuk pertanyaan yang sering diajukan mengenai layanan kami.</p>
            </div>

            <!-- FAQ List -->
            <div class="divide-y divide-zinc-200">
                <!-- FAQ Item 1 -->
                <div class="faq-item py-4">
                    <button onclick="toggleFaq(1)" class="faq-trigger w-full flex items-center justify-between text-left text-xs font-bold text-zinc-800 hover:text-zinc-950 transition-colors focus:outline-none" aria-expanded="false">
                        <span>Apakah platform TraKerja benar-benar gratis?</span>
                        <i class="ph-bold ph-caret-down faq-icon text-zinc-450 transition-transform duration-200"></i>
                    </button>
                    <div id="faq-content-1" class="faq-content hidden mt-3 text-[11px] text-zinc-500 leading-relaxed pl-1">
                        Ya, fitur utama pelacakan lamaran kerja (Kanban Tracker) dan integrasi Ekstensi Chrome dapat digunakan 100% gratis selamanya. Kami menawarkan opsi paket Premium Pro berbayar bagi pengguna yang ingin menggunakan fungsionalitas asisten kecerdasan buatan (AI) terintegrasi.
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="faq-item py-4">
                    <button onclick="toggleFaq(2)" class="faq-trigger w-full flex items-center justify-between text-left text-xs font-bold text-zinc-800 hover:text-zinc-950 transition-colors focus:outline-none" aria-expanded="false">
                        <span>Bagaimana cara kerja Chrome Extension Auto-Fill?</span>
                        <i class="ph-bold ph-caret-down faq-icon text-zinc-450 transition-transform duration-200"></i>
                    </button>
                    <div id="faq-content-2" class="faq-content hidden mt-3 text-[11px] text-zinc-500 leading-relaxed pl-1">
                        Setelah memasang ekstensi resmi kami dari Chrome Web Store, Anda cukup membuka halaman lowongan kerja aktif (seperti LinkedIn, JobStreet, atau Glints) lalu mengeklik ikon ekstensi. Data detail lowongan akan diekstrak secara otomatis dan langsung diunggah ke papan lamaran Anda.
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="faq-item py-4">
                    <button onclick="toggleFaq(3)" class="faq-trigger w-full flex items-center justify-between text-left text-xs font-bold text-zinc-800 hover:text-zinc-950 transition-colors focus:outline-none" aria-expanded="false">
                        <span>Apakah data lamaran kerja saya aman di TraKerja?</span>
                        <i class="ph-bold ph-caret-down faq-icon text-zinc-450 transition-transform duration-200"></i>
                    </button>
                    <div id="faq-content-3" class="faq-content hidden mt-3 text-[11px] text-zinc-500 leading-relaxed pl-1">
                        Sangat aman. Seluruh data lamaran kerja, resume, dan dokumen pendukung Anda disimpan dengan protokol enkripsi standar industri dan tidak akan pernah dibagikan kepada pihak ketiga tanpa persetujuan eksplisit Anda.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Area (Low-profile Cupertino Watermark) -->
    <footer class="bg-white border-t border-zinc-200/60 py-12 text-zinc-400">
        <div class="max-w-5xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-6 text-[9.5px] font-mono tracking-wider font-bold uppercase select-none">
            <!-- Copyright & Watermark -->
            <div class="flex flex-col gap-1 items-center md:items-start text-center md:text-left">
                <span class="text-zinc-650">&copy; {{ date('Y') }} TraKerja. All Rights Reserved.</span>
                <span class="text-zinc-400 font-bold lowercase tracking-normal text-[9px] font-sans">Powered by PT. Teknalogi Transformasi Digital</span>
            </div>

            <!-- Quick Links -->
            <div class="flex items-center gap-6 text-[9px] font-mono">
                <a href="#fitur" class="hover:text-zinc-700 transition-colors">Fitur</a>
                <a href="#cara-kerja" class="hover:text-zinc-700 transition-colors">Cara Kerja</a>
                <a href="#pricing" class="hover:text-zinc-700 transition-colors">Harga</a>
                <a href="#faq" class="hover:text-zinc-700 transition-colors">FAQ</a>
            </div>
        </div>
    </footer>

    <!-- Floating Support Chat / Contact Widget -->
    <button type="button" id="contact-fab" onclick="openContactWidget()" class="fixed bottom-5 right-5 w-10 h-10 bg-zinc-900 hover:bg-zinc-800 rounded-full flex items-center justify-center text-white shadow-md active:scale-95 transition-all z-40 select-none">
        <i class="ph-bold ph-chat-circle text-base"></i>
    </button>

    <!-- Chat Widget Pane -->
    <div id="contact-widget" class="fixed bottom-18 right-5 w-72 bg-white border border-zinc-200 rounded-2xl shadow-xl z-40 scale-95 opacity-0 pointer-events-none transition-all duration-200 overflow-hidden">
        <!-- Header widget -->
        <div class="bg-zinc-900 p-4 text-white flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded bg-white/10 flex items-center justify-center">
                    <i class="ph-bold ph-headset text-xs"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold">TraKerja Support</h4>
                    <p class="text-[9px] opacity-75 font-mono uppercase tracking-wider font-bold">Respon cepat dalam beberapa jam</p>
                </div>
            </div>
            <button type="button" onclick="closeContactWidget()" class="text-white/80 hover:text-white transition-colors">
                <i class="ph-bold ph-x text-xs"></i>
            </button>
        </div>

        <!-- Screen 1: Options -->
        <div id="contact-screen-1" class="p-4 space-y-2">
            <p class="text-[8.5px] font-mono tracking-wider font-bold text-zinc-400 uppercase mb-2">Pilih Topik Hubungi Kami:</p>
            
            <button onclick="showContactTopic('email', 'Kemitraan & Kolaborasi Bisnis')" class="w-full p-2.5 bg-white border border-zinc-200 rounded-xl hover:border-zinc-350 transition-all text-left flex items-center gap-3">
                <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-700 shadow-3xs">
                    <i class="ph-bold ph-handshake text-xs"></i>
                </div>
                <div>
                    <h5 class="text-[10px] font-bold text-zinc-800">Kemitraan Bisnis</h5>
                    <p class="text-[8.5px] text-zinc-450 leading-none mt-0.5">Kolaborasi & integrasi sistem</p>
                </div>
            </button>

            <button onclick="showContactTopic('whatsapp', '')" class="w-full p-2.5 bg-white border border-zinc-200 rounded-xl hover:border-zinc-350 transition-all text-left flex items-center gap-3">
                <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center text-emerald-600 shadow-3xs">
                    <i class="ph-bold ph-whatsapp-logo text-xs"></i>
                </div>
                <div>
                    <h5 class="text-[10px] font-bold text-zinc-800">WhatsApp Support</h5>
                    <p class="text-[8.5px] text-zinc-450 leading-none mt-0.5">Dukungan teknis & integrasi</p>
                </div>
            </button>

            <button onclick="showContactTopic('email', 'Pertanyaan Umum & Bantuan')" class="w-full p-2.5 bg-white border border-zinc-200 rounded-xl hover:border-zinc-350 transition-all text-left flex items-center gap-3">
                <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-700 shadow-3xs">
                    <i class="ph-bold ph-envelope text-xs"></i>
                </div>
                <div>
                    <h5 class="text-[10px] font-bold text-zinc-800">Email Support</h5>
                    <p class="text-[8.5px] text-zinc-450 leading-none mt-0.5">Hubungi kami melalui pesan email</p>
                </div>
            </button>
        </div>

        <!-- Screen 2: Email Form -->
        <div id="contact-screen-2" class="hidden p-4 space-y-3">
            <button onclick="backToScreen1()" class="flex items-center gap-1 text-[8px] text-zinc-400 hover:text-zinc-650 font-bold font-mono uppercase tracking-wider mb-1">
                <i class="ph-bold ph-arrow-left"></i> Kembali
            </button>
            <form id="contact-form" onsubmit="submitContactForm(event)" class="space-y-3">
                <input type="hidden" id="contact-subject-hidden" name="subject" value="">
                <div>
                    <label class="block text-[8px] font-bold text-zinc-450 uppercase tracking-wider mb-1">Nama Lengkap</label>
                    <input type="text" name="name" id="contact-name" required class="block w-full px-2.5 h-[28px] border border-zinc-200 bg-zinc-50/20 rounded text-[10px] font-medium text-zinc-700 outline-none focus:bg-white focus:ring-1 focus:ring-zinc-900/10 focus:border-zinc-400 transition-all">
                </div>
                <div>
                    <label class="block text-[8px] font-bold text-zinc-450 uppercase tracking-wider mb-1">Alamat Email</label>
                    <input type="email" name="email" id="contact-email" required class="block w-full px-2.5 h-[28px] border border-zinc-200 bg-zinc-50/20 rounded text-[10px] font-medium text-zinc-700 outline-none focus:bg-white focus:ring-1 focus:ring-zinc-900/10 focus:border-zinc-400 transition-all">
                </div>
                <div>
                    <label class="block text-[8px] font-bold text-zinc-450 uppercase tracking-wider mb-1">Pesan Anda</label>
                    <textarea name="message" id="contact-message" required rows="3" class="block w-full p-2 border border-zinc-200 bg-zinc-50/20 rounded text-[10px] font-medium text-zinc-700 outline-none focus:bg-white focus:ring-1 focus:ring-zinc-900/10 focus:border-zinc-400 transition-all"></textarea>
                </div>
                <div id="contact-error" class="hidden text-[9px] font-semibold text-rose-600 bg-rose-50 border border-rose-100 p-2 rounded"></div>
                
                <button type="submit" id="contact-submit-btn" class="w-full h-8 bg-zinc-900 hover:bg-zinc-800 text-white rounded text-[10px] font-bold shadow-3xs transition-all flex items-center justify-center gap-1.5">
                    <span id="contact-btn-text">Kirim Pesan</span>
                    <i id="contact-btn-spinner" class="ph-bold ph-spinner animate-spin text-xs hidden"></i>
                </button>
            </form>
        </div>

        <!-- Screen 3: WhatsApp Redirection -->
        <div id="contact-screen-3" class="hidden p-6 text-center space-y-4">
            <button onclick="backToScreen1()" class="flex items-center gap-1 text-[8px] text-zinc-400 hover:text-zinc-650 font-bold font-mono uppercase tracking-wider mb-1">
                <i class="ph-bold ph-arrow-left"></i> Kembali
            </button>
            <div class="w-10 h-10 rounded bg-zinc-50 border border-zinc-205 flex items-center justify-center text-emerald-600 mx-auto shadow-3xs">
                <i class="ph-bold ph-whatsapp-logo text-lg"></i>
            </div>
            <div>
                <h5 class="text-xs font-bold text-zinc-800">WhatsApp Support</h5>
                <p class="text-[10px] text-zinc-450 leading-relaxed mt-1">Anda akan diarahkan ke kontak WhatsApp resmi PT. Teknalogi Transformasi Digital untuk bantuan teknis.</p>
            </div>
            <a href="https://wa.me/6282218000542" target="_blank" class="h-8 px-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-[10px] font-bold shadow-3xs transition-all flex items-center justify-center gap-1.5 mx-auto">
                <i class="ph-bold ph-whatsapp-logo text-xs"></i>
                <span>Hubungi via WhatsApp</span>
            </a>
        </div>

        <!-- Screen Success -->
        <div id="contact-screen-success" class="hidden p-6 text-center space-y-3">
            <div class="w-10 h-10 rounded bg-zinc-50 border border-zinc-205 flex items-center justify-center text-emerald-600 mx-auto shadow-3xs">
                <i class="ph-bold ph-check text-lg"></i>
            </div>
            <div>
                <h5 class="text-xs font-bold text-zinc-800">Pesan Terkirim!</h5>
                <p class="text-[10px] text-zinc-450 leading-relaxed mt-1">Terima kasih. Tim kami akan segera merespon laporan Anda melalui alamat email.</p>
            </div>
            <button type="button" onclick="backToScreen1()" class="h-7 px-4 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 rounded-md text-[10px] font-bold transition-all">
                Kembali
            </button>
        </div>
    </div>

    <!-- Javascript Core Functionality -->
    <script>
        // Toggle How it Works Stepper
        function toggleHowItWorks(step) {
            // Reset trigger styling
            document.querySelectorAll('.hiw-card').forEach(card => {
                card.className = "hiw-card cursor-pointer border-l border-zinc-200 hover:border-zinc-300 p-4 rounded-r-lg transition-all duration-200 select-none";
            });
            document.querySelectorAll('[id^="hiw-content-"]').forEach(content => {
                content.classList.add('hidden');
            });
            document.querySelectorAll('[id^="hiw-num-"]').forEach(num => {
                num.className = "font-mono text-xs font-bold text-zinc-400";
            });
            document.querySelectorAll('.how-it-works-image').forEach(img => {
                img.style.opacity = '0';
                setTimeout(() => img.classList.add('hidden'), 200);
            });

            // Set active stepper style
            const activeCard = document.getElementById('hiw-trigger-' + step);
            if (activeCard) {
                activeCard.className = "hiw-card cursor-pointer border-l-2 border-zinc-900 bg-zinc-50/60 p-4 rounded-r-lg transition-all duration-200 select-none";
            }
            const activeContent = document.getElementById('hiw-content-' + step);
            if (activeContent) {
                activeContent.classList.remove('hidden');
            }
            const activeNum = document.getElementById('hiw-num-' + step);
            if (activeNum) {
                activeNum.className = "font-mono text-xs font-bold text-zinc-900";
            }

            // Cross-fade steps images
            setTimeout(() => {
                const targetImg = document.getElementById('how-it-works-img-' + step);
                if (targetImg) {
                    targetImg.classList.remove('hidden');
                    setTimeout(() => { targetImg.style.opacity = '1'; }, 30);
                }
            }, 200);
        }

        // Toggle FAQ Accordion lines
        function toggleFaq(id) {
            const trigger = document.querySelector(`.faq-item:nth-child(${id}) .faq-trigger`);
            const content = document.getElementById(`faq-content-${id}`);
            const isExpanded = trigger.getAttribute('aria-expanded') === 'true';

            // Close all
            document.querySelectorAll('.faq-trigger').forEach(trig => {
                trig.setAttribute('aria-expanded', 'false');
            });
            document.querySelectorAll('.faq-content').forEach(cont => {
                cont.classList.add('hidden');
            });

            // Open selected
            if (!isExpanded) {
                trigger.setAttribute('aria-expanded', 'true');
                content.classList.remove('hidden');
            }
        }

        // Contact Support widget controls
        function openContactWidget() {
            const widget = document.getElementById('contact-widget');
            widget.classList.remove('scale-95', 'opacity-0', 'pointer-events-none');
            widget.classList.add('scale-100', 'opacity-100');
        }

        function closeContactWidget() {
            const widget = document.getElementById('contact-widget');
            widget.classList.add('scale-95', 'opacity-0', 'pointer-events-none');
            widget.classList.remove('scale-100', 'opacity-100');
        }

        function showContactTopic(type, subject) {
            hideAllScreens();
            if (type === 'whatsapp') {
                document.getElementById('contact-screen-3').classList.remove('hidden');
            } else {
                document.getElementById('contact-subject-hidden').value = subject;
                document.getElementById('contact-screen-2').classList.remove('hidden');
            }
        }

        function backToScreen1() {
            hideAllScreens();
            resetForm();
            document.getElementById('contact-screen-1').classList.remove('hidden');
        }

        function hideAllScreens() {
            ['contact-screen-1', 'contact-screen-2', 'contact-screen-3', 'contact-screen-success']
                .forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.classList.add('hidden');
                });
        }

        function resetForm() {
            const form = document.getElementById('contact-form');
            if (form) form.reset();
            const err = document.getElementById('contact-error');
            if (err) err.classList.add('hidden');
            setSubmitLoading(false);
        }

        function setSubmitLoading(loading) {
            const btn = document.getElementById('contact-submit-btn');
            const text = document.getElementById('contact-btn-text');
            const spinner = document.getElementById('contact-btn-spinner');
            if (btn && text && spinner) {
                btn.disabled = loading;
                text.textContent = loading ? 'Mengirim...' : 'Kirim Pesan';
                spinner.classList.toggle('hidden', !loading);
            }
        }

        async function submitContactForm(event) {
            event.preventDefault();
            const errorEl = document.getElementById('contact-error');
            if (errorEl) errorEl.classList.add('hidden');
            setSubmitLoading(true);

            const form = event.target;
            const formData = new FormData(form);

            try {
                const response = await fetch('{{ route("contact.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                });

                const data = await response.json();

                if (data.success) {
                    hideAllScreens();
                    const successScreen = document.getElementById('contact-screen-success');
                    if (successScreen) successScreen.classList.remove('hidden');
                } else {
                    const msg = data.message || 'Terjadi kesalahan. Silakan coba lagi.';
                    if (errorEl) {
                        errorEl.textContent = msg;
                        errorEl.classList.remove('hidden');
                    }
                }
            } catch (err) {
                if (errorEl) {
                    errorEl.textContent = 'Gagal mengirim pesan. Periksa koneksi internet Anda.';
                    errorEl.classList.remove('hidden');
                }
            } finally {
                setSubmitLoading(false);
            }
        }

        // Close widget when clicking outside
        document.addEventListener('click', function(e) {
            const widget = document.getElementById('contact-widget');
            const fab = document.getElementById('contact-fab');
            if (widget && fab && !widget.contains(e.target) && !fab.contains(e.target)) {
                const isOpen = !widget.classList.contains('pointer-events-none');
                if (isOpen) closeContactWidget();
            }
        });
    </script>
</body>
</html>