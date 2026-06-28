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
            .bento-card {
                background: #ffffff;
                transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .bento-card:hover {
                border-color: #a78bfa;
                transform: translateY(-2px);
                box-shadow: 0 16px 36px -12px rgba(139, 92, 246, 0.08);
            }
            .glow-backdrop {
                background: radial-gradient(circle at center, rgba(167, 139, 250, 0.09) 0%, transparent 65%);
            }
            .faq-trigger[aria-expanded="true"] .faq-icon {
                transform: rotate(180deg);
            }
        </style>
    </head>
<body class="bg-white text-zinc-800 antialiased selection:bg-primary-50 selection:text-primary-950">

    <!-- Navigation Header -->
    <nav class="fixed top-0 left-0 right-0 h-12 bg-white/70 backdrop-blur-md border-b border-zinc-200/50 z-50">
        <div class="max-w-5xl mx-auto px-4 h-full flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-2 group select-none">
                <div class="w-5 h-5 bg-zinc-900 group-hover:bg-primary-600 rounded flex items-center justify-center text-white text-[10px] font-black transition-colors">T</div>
                <span class="text-xs font-bold tracking-tight text-zinc-900 group-hover:text-primary-600 transition-colors">TraKerja</span>
            </a>

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
                            <a href="{{ route('register') }}" class="h-7 px-3 bg-primary-600 hover:bg-primary-700 text-white rounded-md text-[11px] font-semibold transition-all flex items-center">
                                Mulai Gratis
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section (Apple-style Centered Layout) -->
    <section class="relative pt-24 pb-20 bg-white overflow-hidden border-b border-zinc-200/50">
        <!-- Subtle Purple Radial Backlight Glow (Apple Style) -->
        <div class="absolute inset-0 glow-backdrop pointer-events-none z-0"></div>

        <div class="relative z-10 max-w-4xl mx-auto px-4 text-center flex flex-col items-center">
            <!-- Badge -->
            <div class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-primary-50 border border-primary-200/60 text-primary-750 font-mono text-[9px] rounded font-bold uppercase tracking-wider mb-6 select-none shadow-3xs">
                <span class="w-1.5 h-1.5 bg-primary-500 rounded-full animate-pulse"></span>
                <span>#1 Career Tracking Platform Indonesia</span>
            </div>

            <!-- Big Apple Title -->
            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black text-zinc-900 tracking-tighter leading-[1.05] mb-6 max-w-3xl select-none">
                Kelola lamaran kerja<br><span class="text-primary-600">jauh lebih cerdas.</span>
            </h1>

            <!-- Subheading -->
            <p class="text-sm sm:text-base text-zinc-550 max-w-xl font-medium antialiased leading-relaxed mb-8 select-none">
                Platform pelacakan lamaran kerja yang sederhana, efektif, dan 100% gratis. Buat CV standar ATS, dapatkan estimasi gaji kompetitif, dan otomatisasi input lamaran Anda.
            </p>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center justify-center gap-3 mb-16">
                @auth
                    <a href="{{ url('/tracker') }}" class="h-9 px-5 bg-zinc-900 hover:bg-zinc-800 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm active:scale-97">
                        <i class="ph-bold ph-columns text-sm"></i>
                        <span>Buka Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('register') }}" class="h-9 px-5 bg-zinc-900 hover:bg-zinc-800 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm active:scale-97">
                        <i class="ph-bold ph-lightning text-sm"></i>
                        <span>Mulai Gratis Sekarang</span>
                    </a>
                    <a href="{{ route('login') }}" class="h-9 px-5 bg-white border border-zinc-250 hover:bg-zinc-50 text-zinc-700 hover:text-zinc-900 rounded-lg text-xs font-bold transition-all flex items-center active:scale-97">
                        Masuk ke Akun
                    </a>
                @endauth
            </div>

            <!-- Showcase Interactive Browser Mockup (1px Border Notion Style) -->
            <div class="w-full max-w-4xl border border-zinc-200/80 rounded-xl bg-white p-1.5 shadow-xs">
                <div class="relative overflow-hidden rounded-lg border border-zinc-200 bg-zinc-50 flex flex-col aspect-[16/10] select-none text-left">
                    <!-- Title Bar -->
                    <div class="bg-white border-b border-zinc-200 h-9 px-4 flex items-center justify-between shrink-0">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-zinc-200"></span>
                            <span class="w-2 h-2 rounded-full bg-zinc-200"></span>
                            <span class="w-2 h-2 rounded-full bg-zinc-200"></span>
                        </div>
                        <!-- Tab switcher -->
                        <div class="flex p-0.5 bg-zinc-100 border border-zinc-200 rounded-md text-[9px] font-bold text-zinc-500 gap-1 shadow-inner">
                            <button onclick="switchMockupTab('tracker')" id="mockup-tab-btn-tracker" class="px-3.5 py-1 bg-white border border-zinc-200 rounded text-zinc-800 shadow-3xs font-black transition-all">Kanban Board</button>
                            <button onclick="switchMockupTab('ai')" id="mockup-tab-btn-ai" class="px-3.5 py-1 hover:bg-white/40 rounded transition-all">AI Match Scanner</button>
                            <button onclick="switchMockupTab('extension')" id="mockup-tab-btn-extension" class="px-3.5 py-1 hover:bg-white/40 rounded transition-all">Auto-Fill Panel</button>
                        </div>
                        <div class="w-12"></div>
                    </div>

                    <!-- Mockup Tab 1: Kanban Board View -->
                    <div id="mockup-tab-tracker" class="flex-1 flex overflow-hidden">
                        <!-- Sidebar -->
                        <div class="w-36 bg-white border-r border-zinc-200 p-3.5 hidden sm:flex flex-col justify-between shrink-0">
                            <div class="space-y-3.5">
                                <div class="flex items-center gap-1.5 text-[8.5px] font-mono text-zinc-400 font-bold uppercase tracking-wider">Workspace</div>
                                <div class="space-y-1">
                                    <div class="flex items-center gap-1.5 p-1.5 bg-primary-50 border border-primary-100 text-primary-700 rounded text-[10px] font-bold">
                                        <i class="ph-bold ph-columns text-xs"></i>
                                        <span>Job Tracker</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 p-1.5 text-zinc-500 rounded text-[10px] font-semibold hover:bg-zinc-50">
                                        <i class="ph-bold ph-sparkle text-xs"></i>
                                        <span>AI Studio</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 p-1.5 text-zinc-500 rounded text-[10px] font-semibold hover:bg-zinc-50">
                                        <i class="ph-bold ph-chart-line-up text-xs"></i>
                                        <span>Analytics</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5 text-[8.5px] text-zinc-400 font-mono">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                <span>Free Tier Plan</span>
                            </div>
                        </div>

                        <!-- Main Kanban Board -->
                        <div class="flex-1 p-4 overflow-x-auto flex gap-4 bg-[#fafafa]">
                            <!-- Column 1: Applied -->
                            <div class="w-52 shrink-0 flex flex-col gap-2">
                                <div class="flex items-center justify-between text-[9px] font-bold text-zinc-400 font-mono uppercase tracking-wider mb-1">
                                    <span>Applied</span>
                                    <span class="px-1.5 bg-zinc-200 rounded text-zinc-600">2</span>
                                </div>
                                <!-- Card 1 -->
                                <div class="bg-white border border-zinc-200 rounded-lg p-3 hover:border-primary-400 transition-all shadow-3xs">
                                    <h4 class="text-[11px] font-bold text-zinc-900">Frontend Developer</h4>
                                    <p class="text-[9.5px] text-zinc-450 mt-0.5 font-medium">Tokopedia</p>
                                    <div class="flex flex-wrap gap-1 mt-2.5">
                                        <span class="px-1.5 py-0.2 bg-zinc-50 border border-zinc-200 text-zinc-550 text-[8px] font-semibold rounded">Full-time</span>
                                        <span class="px-1.5 py-0.2 bg-primary-50 border border-primary-100 text-primary-600 text-[8px] font-bold rounded">Jakarta</span>
                                    </div>
                                </div>
                                <!-- Card 2 -->
                                <div class="bg-white border border-zinc-200 rounded-lg p-3 hover:border-primary-400 transition-all shadow-3xs">
                                    <h4 class="text-[11px] font-bold text-zinc-900">Software Engineer</h4>
                                    <p class="text-[9.5px] text-zinc-455 mt-0.5 font-medium">GoTo Financial</p>
                                    <div class="flex flex-wrap gap-1 mt-2.5">
                                        <span class="px-1.5 py-0.2 bg-zinc-50 border border-zinc-200 text-zinc-550 text-[8px] font-semibold rounded">Remote</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Column 2: Interviewing -->
                            <div class="w-52 shrink-0 flex flex-col gap-2">
                                <div class="flex items-center justify-between text-[9px] font-bold text-zinc-400 font-mono uppercase tracking-wider mb-1">
                                    <span>Interviewing</span>
                                    <span class="px-1.5 bg-zinc-200 rounded text-zinc-650">1</span>
                                </div>
                                <!-- Card 1 -->
                                <div class="bg-white border-l-2 border-l-primary-500 border border-zinc-200 rounded-lg p-3 hover:border-primary-400 transition-all shadow-3xs">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-[11px] font-bold text-zinc-900">Product Manager</h4>
                                        <span class="w-1.5 h-1.5 bg-primary-500 rounded-full animate-pulse"></span>
                                    </div>
                                    <p class="text-[9.5px] text-zinc-455 mt-0.5 font-medium">Traveloka</p>
                                    <div class="flex flex-wrap gap-1 mt-2.5">
                                        <span class="px-1.5 py-0.2 bg-zinc-50 border border-zinc-200 text-zinc-550 text-[8px] font-semibold rounded">Full-time</span>
                                        <span class="px-1.5 py-0.2 bg-primary-50 border border-primary-100 text-primary-600 text-[8px] font-bold rounded">Interview HR</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Column 3: Offering -->
                            <div class="w-52 shrink-0 flex flex-col gap-2">
                                <div class="flex items-center justify-between text-[9px] font-bold text-zinc-400 font-mono uppercase tracking-wider mb-1">
                                    <span>Offering</span>
                                    <span class="px-1.5 bg-zinc-200 rounded text-zinc-600">1</span>
                                </div>
                                <!-- Card 1 -->
                                <div class="bg-white border-l-2 border-l-emerald-500 border border-zinc-200 rounded-lg p-3 hover:border-emerald-450 transition-all shadow-3xs">
                                    <h4 class="text-[11px] font-bold text-zinc-900">Backend Lead</h4>
                                    <p class="text-[9.5px] text-zinc-455 mt-0.5 font-medium">Shopee</p>
                                    <div class="flex flex-wrap gap-1 mt-2.5">
                                        <span class="px-1.5 py-0.2 bg-emerald-50 border border-emerald-100 text-emerald-600 text-[8px] font-bold rounded">Offered</span>
                                        <span class="px-1.5 py-0.2 bg-zinc-50 border border-zinc-200 text-zinc-550 text-[8px] font-semibold rounded">Rp 22jt/mo</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mockup Tab 2: AI Studio View -->
                    <div id="mockup-tab-ai" class="flex-1 flex overflow-hidden hidden p-4 gap-4 bg-[#fafafa]">
                        <div class="flex-1 border border-zinc-200 bg-white rounded-lg p-4 flex flex-col justify-between shadow-3xs">
                            <div class="space-y-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded bg-primary-50 border border-primary-100 flex items-center justify-center text-primary-650 shrink-0">
                                        <i class="ph-bold ph-file-pdf text-sm"></i>
                                    </div>
                                    <div>
                                        <h5 class="text-[10px] font-bold text-zinc-800 truncate">cv_luthfi_fauzi.pdf</h5>
                                        <p class="text-[8px] font-mono text-zinc-400 font-bold uppercase">Size: 1.2 MB</p>
                                    </div>
                                </div>
                                <div class="border-t border-zinc-100 my-2"></div>
                                <div class="space-y-1.5">
                                    <h6 class="text-[8.5px] font-mono text-zinc-400 font-bold uppercase">Matched Keywords:</h6>
                                    <div class="flex flex-wrap gap-1">
                                        <span class="px-1.5 py-0.2 bg-zinc-50 border border-zinc-200 text-zinc-700 text-[8px] font-medium rounded">Laravel</span>
                                        <span class="px-1.5 py-0.2 bg-zinc-50 border border-zinc-200 text-zinc-700 text-[8px] font-medium rounded">PHP 8.2</span>
                                        <span class="px-1.5 py-0.2 bg-zinc-50 border border-zinc-200 text-zinc-700 text-[8px] font-medium rounded">Tailwind CSS</span>
                                        <span class="px-1.5 py-0.2 bg-zinc-50 border border-zinc-200 text-zinc-700 text-[8px] font-medium rounded">RESTful API</span>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2 bg-emerald-50 border border-emerald-100 rounded text-[9.5px] font-bold text-emerald-700 flex items-center gap-1.5 shadow-3xs">
                                <i class="ph-bold ph-check-circle text-xs"></i>
                                <span>ATS Score: 92% (Optimal Match)</span>
                            </div>
                        </div>

                        <div class="w-56 border border-zinc-200 bg-white rounded-lg p-4 flex flex-col justify-between shrink-0 shadow-3xs">
                            <div class="space-y-3">
                                <div class="text-[8.5px] font-mono text-zinc-400 font-bold uppercase tracking-wider">AI Suggestions</div>
                                <div class="space-y-2">
                                    <div class="p-2 bg-zinc-50 border border-zinc-200 rounded text-[9px] text-zinc-500 leading-normal">
                                        <span class="font-bold text-zinc-700 block">Missing Key:</span> Tambahkan sertifikasi Docker untuk kecocokan 100%.
                                    </div>
                                    <div class="p-2 bg-zinc-50 border border-zinc-200 rounded text-[9px] text-zinc-500 leading-normal">
                                        <span class="font-bold text-zinc-700 block">Action:</span> Buat Cover Letter dengan template "Sleek Professional".
                                    </div>
                                </div>
                            </div>
                            <button class="h-6 bg-zinc-900 text-white rounded text-[9px] font-bold w-full hover:bg-zinc-800 transition-colors shadow-3xs">Generate Cover Letter</button>
                        </div>
                    </div>

                    <!-- Mockup Tab 3: Chrome Extension Panel View -->
                    <div id="mockup-tab-extension" class="flex-1 flex items-center justify-center hidden p-4 bg-[#fafafa]">
                        <div class="w-72 bg-white border border-zinc-200 rounded-xl shadow-sm overflow-hidden flex flex-col">
                            <div class="bg-zinc-950 p-2.5 text-white flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-4 h-4 rounded bg-primary-500 flex items-center justify-center text-[8px] font-bold">T</div>
                                    <span class="text-[9.5px] font-bold">TraKerja Extension</span>
                                </div>
                                <span class="text-[8px] font-mono text-zinc-400 uppercase tracking-wider font-bold">Active</span>
                            </div>
                            <div class="p-4 space-y-3">
                                <div class="space-y-1">
                                    <div class="text-[8px] font-mono text-zinc-400 font-bold uppercase">Scraped Target</div>
                                    <div class="p-2 bg-zinc-50 border border-zinc-200 rounded text-[9px] text-zinc-700 font-bold truncate">https://www.linkedin.com/jobs/view/39201938/</div>
                                </div>
                                <div class="space-y-1.5">
                                    <div class="text-[8px] font-mono text-zinc-400 font-bold uppercase">Data Preview</div>
                                    <div class="grid grid-cols-2 gap-2 text-[9px] text-zinc-500 font-medium">
                                        <div class="p-1.5 bg-zinc-50 border border-zinc-150 rounded">Position: <span class="font-bold text-zinc-700 block">Sales Mikro</span></div>
                                        <div class="p-1.5 bg-zinc-50 border border-zinc-150 rounded">Company: <span class="font-bold text-zinc-700 block">Bank DKI</span></div>
                                    </div>
                                </div>
                                <button type="button" class="w-full h-7 bg-primary-650 hover:bg-primary-700 text-white rounded text-[10px] font-bold shadow-3xs transition-all flex items-center justify-center gap-1 mt-1">
                                    <i class="ph-bold ph-arrow-circle-down text-xs animate-bounce"></i>
                                    <span>Auto-Fill to Board</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Platform Support Favicon Strip (Unified Clean Layout) -->
    <div class="py-6 border-b border-zinc-200/50 bg-[#fafafa]">
        <div class="max-w-5xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <span class="font-mono text-[9px] font-bold text-zinc-400 uppercase tracking-widest">Supported Portals</span>
            <div class="flex flex-wrap items-center gap-6">
                <!-- LinkedIn -->
                <a href="https://linkedin.com" target="_blank" class="opacity-45 hover:opacity-100 transition-opacity">
                    <img src="https://www.google.com/s2/favicons?domain=linkedin.com&sz=64" alt="LinkedIn" class="w-3.5 h-3.5 filter grayscale hover:grayscale-0 transition-all">
                </a>
                <!-- Glints -->
                <a href="https://glints.com" target="_blank" class="opacity-45 hover:opacity-100 transition-opacity">
                    <img src="https://www.google.com/s2/favicons?domain=glints.com&sz=64" alt="Glints" class="w-3.5 h-3.5 filter grayscale hover:grayscale-0 transition-all">
                </a>
                <!-- JobStreet -->
                <a href="https://jobstreet.co.id" target="_blank" class="opacity-45 hover:opacity-100 transition-opacity">
                    <img src="https://www.google.com/s2/favicons?domain=jobstreet.co.id&sz=64" alt="JobStreet" class="w-3.5 h-3.5 filter grayscale hover:grayscale-0 transition-all">
                </a>
                <!-- Kalibrr -->
                <a href="https://kalibrr.com" target="_blank" class="opacity-45 hover:opacity-100 transition-opacity">
                    <img src="https://www.google.com/s2/favicons?domain=kalibrr.com&sz=64" alt="Kalibrr" class="w-3.5 h-3.5 filter grayscale hover:grayscale-0 transition-all">
                </a>
                <!-- Dealls -->
                <a href="https://dealls.com" target="_blank" class="opacity-45 hover:opacity-100 transition-opacity">
                    <img src="https://www.google.com/s2/favicons?domain=dealls.com&sz=64" alt="Dealls" class="w-3.5 h-3.5 filter grayscale hover:grayscale-0 transition-all">
                </a>
                <!-- Talentics -->
                <a href="https://talentics.id" target="_blank" class="opacity-45 hover:opacity-100 transition-opacity">
                    <img src="https://www.google.com/s2/favicons?domain=talentics.id&sz=64" alt="Talentics" class="w-3.5 h-3.5 filter grayscale hover:grayscale-0 transition-all">
                </a>
            </div>
        </div>
    </div>

    <!-- Bento Grid Section -->
    <section id="fitur" class="py-20 bg-white">
        <div class="max-w-5xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center max-w-xl mx-auto mb-16">
                <p class="font-mono text-[9px] tracking-wider text-primary-600 font-bold uppercase mb-2">Bento Features</p>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-zinc-900 tracking-tight mb-3">Semua kebutuhan karir dalam satu workspace.</h2>
                <p class="text-xs sm:text-sm text-zinc-500 font-medium">Lupakan spreadsheet kaku. Atur seluruh alur pencarian kerja Anda dengan instrumen premium yang terpadu.</p>
            </div>

            <!-- Bento Grid Layout -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Card 1: Kanban Board Tracker (Large - span 2) -->
                <div class="bento-card bg-white border border-zinc-200 rounded-xl p-6 flex flex-col justify-between aspect-[16/10] md:aspect-auto">
                    <div class="mb-6">
                        <div class="w-7 h-7 rounded bg-primary-50 border border-primary-200/50 flex items-center justify-center text-primary-600 mb-4 shadow-3xs">
                            <i class="ph-bold ph-kanban text-sm"></i>
                        </div>
                        <h3 class="text-sm font-bold text-zinc-900 mb-1">Kanban Board Tracker</h3>
                        <p class="text-xs text-zinc-500 leading-relaxed max-w-md">Lacak kemajuan setiap lowongan dari On Process, Interview, Offering, hingga Rejected. Pindahkan status secara instan dan rapi layaknya Notion database.</p>
                    </div>
                    <!-- Minimal CSS-mockup layout -->
                    <div class="border border-zinc-150 rounded bg-[#fafafa] p-2 flex gap-2 overflow-hidden shadow-3xs select-none">
                        <div class="flex-1 bg-white border border-zinc-200 rounded p-1.5 text-[9px] font-bold shadow-3xs">
                            <div class="w-1.5 h-1.5 bg-[#4e71c5] rounded-full mb-1"></div>
                            <span class="text-zinc-800">Applied</span>
                        </div>
                        <div class="flex-1 bg-white border border-zinc-200 rounded p-1.5 text-[9px] font-bold shadow-3xs border-l-2 border-l-primary-500">
                            <div class="w-1.5 h-1.5 bg-primary-500 rounded-full mb-1"></div>
                            <span class="text-zinc-800">Interview</span>
                        </div>
                        <div class="flex-1 bg-white border border-zinc-200 rounded p-1.5 text-[9px] font-bold shadow-3xs border-l-2 border-l-emerald-500">
                            <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full mb-1"></div>
                            <span class="text-zinc-800">Offering</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2: AI Cover Letter (Small - span 1) -->
                <div class="bento-card bg-white border border-zinc-200 rounded-xl p-6 flex flex-col justify-between">
                    <div>
                        <div class="w-7 h-7 rounded bg-primary-50 border border-primary-200/50 flex items-center justify-center text-primary-600 mb-4 shadow-3xs">
                            <i class="ph-bold ph-sparkle text-sm"></i>
                        </div>
                        <h3 class="text-sm font-bold text-zinc-900 mb-1">AI Resume Analyzer</h3>
                        <p class="text-xs text-zinc-500 leading-relaxed">Analisis CV Anda terhadap deskripsi pekerjaan secara instan. Buat surat lamaran kerja terpersonalisasi menggunakan kecerdasan buatan.</p>
                    </div>
                    <div class="mt-8 flex items-center gap-1.5 p-2 bg-primary-50/50 border border-primary-200/60 rounded text-[9.5px] font-bold text-primary-750 select-none w-max shadow-3xs">
                        <i class="ph-bold ph-check-circle text-primary-600 text-xs"></i>
                        <span>ATS Score: 92% (Optimal)</span>
                    </div>
                </div>

                <!-- Card 3: Chrome Auto-Fill Extension (Small - span 1) -->
                <div class="bento-card bg-white border border-zinc-200 rounded-xl p-6 flex flex-col justify-between">
                    <div>
                        <div class="w-7 h-7 rounded bg-primary-50 border border-primary-200/50 flex items-center justify-center text-primary-600 mb-4 shadow-3xs">
                            <i class="ph-bold ph-plug text-sm"></i>
                        </div>
                        <h3 class="text-sm font-bold text-zinc-900 mb-1">Auto-Fill Extension</h3>
                        <p class="text-xs text-zinc-500 leading-relaxed">Impor rincian pekerjaan langsung dari LinkedIn, JobStreet, Glints, Kalibrr, Dealls, dan Talentics hanya dengan satu kali klik.</p>
                    </div>
                    <div class="mt-8 flex items-center gap-1.5 px-2 py-0.5 border border-zinc-200 bg-zinc-50 rounded text-[9px] text-zinc-500 font-bold select-none w-max shadow-3xs">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                        <span>Extension Connected</span>
                    </div>
                </div>

                <!-- Card 4: Insightful Analytics (Large - span 2) -->
                <div class="bento-card bg-white border border-zinc-200 rounded-xl p-6 flex flex-col justify-between aspect-[16/10] md:aspect-auto">
                    <div class="mb-6">
                        <div class="w-7 h-7 rounded bg-primary-50 border border-primary-200/50 flex items-center justify-center text-primary-600 mb-4 shadow-3xs">
                            <i class="ph-bold ph-chart-line-up text-sm"></i>
                        </div>
                        <h3 class="text-sm font-bold text-zinc-900 mb-1">Analytics & Salary Estimation</h3>
                        <p class="text-xs text-zinc-500 leading-relaxed max-w-md">Pantau conversion rate lamaran Anda. Dapatkan taksiran gaji kompetitif yang disesuaikan dengan jenis role serta kualifikasi UMK regional.</p>
                    </div>
                    <div class="grid grid-cols-3 gap-2 bg-[#fafafa] border border-zinc-200 rounded-lg p-2.5 text-center select-none shadow-3xs">
                        <div>
                            <div class="text-sm font-extrabold text-zinc-900">84%</div>
                            <div class="text-[8px] text-zinc-400 font-bold uppercase tracking-wider">Applied</div>
                        </div>
                        <div>
                            <div class="text-sm font-extrabold text-zinc-900 text-primary-650">12%</div>
                            <div class="text-[8px] text-primary-500 font-bold uppercase tracking-wider">Interviews</div>
                        </div>
                        <div>
                            <div class="text-sm font-extrabold text-zinc-900 text-emerald-650">4%</div>
                            <div class="text-[8px] text-emerald-500 font-bold uppercase tracking-wider">Offers</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section (Notion Sidebar Stepper Style) -->
    <section id="cara-kerja" class="py-20 bg-[#fafafa] border-t border-zinc-200/60">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center max-w-xl mx-auto mb-16">
                <p class="font-mono text-[9px] tracking-wider text-primary-600 font-bold uppercase mb-2">Workflow</p>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-zinc-900 tracking-tight mb-3">Bagaimana cara kerjanya?</h2>
                <p class="text-xs sm:text-sm text-zinc-500 font-medium">Alur kerja terpadu untuk merapikan seluruh manajemen lamaran kerja Anda.</p>
            </div>

            <!-- Stepper Content -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
                <!-- Steppers sidebar -->
                <div class="space-y-3">
                    <!-- Step 1 -->
                    <div onclick="toggleHowItWorks(1)" id="hiw-trigger-1" class="hiw-card cursor-pointer border-l-2 border-primary-500 bg-white p-4 rounded-r-lg transition-all duration-200 select-none shadow-3xs">
                        <div class="flex items-center gap-3">
                            <span class="font-mono text-xs font-bold text-primary-650" id="hiw-num-1">01</span>
                            <h3 class="text-xs font-bold text-zinc-900">Salin URL & Auto-Fill Data</h3>
                        </div>
                        <div id="hiw-content-1" class="mt-2 text-[11px] text-zinc-500 leading-relaxed pl-7">
                            Gunakan ekstensi web kami atau salin URL lamaran kerja eksternal. Sistem pengisi otomatis kami akan mengekstrak detail data nama posisi, instansi perusahaan, serta informasi lokasi kota.
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div onclick="toggleHowItWorks(2)" id="hiw-trigger-2" class="hiw-card cursor-pointer border-l border-zinc-200 hover:border-zinc-300 bg-white/40 p-4 rounded-r-lg transition-all duration-200 select-none">
                        <div class="flex items-center gap-3">
                            <span class="font-mono text-xs font-bold text-zinc-400" id="hiw-num-2">02</span>
                            <h3 class="text-xs font-bold text-zinc-450">Lacak Siklus Lamaran</h3>
                        </div>
                        <div id="hiw-content-2" class="mt-2 text-[11px] text-zinc-500 leading-relaxed pl-7 hidden">
                            Pantau kemajuan rekrutmen lamaran Anda secara tersusun. Tambahkan jadwal wawancara, riwayat tes tertulis, serta data korespondensi dalam satu tempat terpusat.
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div onclick="toggleHowItWorks(3)" id="hiw-trigger-3" class="hiw-card cursor-pointer border-l border-zinc-200 hover:border-zinc-300 bg-white/40 p-4 rounded-r-lg transition-all duration-200 select-none">
                        <div class="flex items-center gap-3">
                            <span class="font-mono text-xs font-bold text-zinc-400" id="hiw-num-3">03</span>
                            <h3 class="text-xs font-bold text-zinc-450">Optimasi CV via AI</h3>
                        </div>
                        <div id="hiw-content-3" class="mt-2 text-[11px] text-zinc-500 leading-relaxed pl-7 hidden">
                            Manfaatkan model kecerdasan buatan untuk mengecek skor kelolosan CV Anda. Buat draf surat lamaran kerja terpersonalisasi yang cocok dengan spesifikasi deskripsi lowongan.
                        </div>
                    </div>
                </div>

                <!-- Preview Board (CSS Mockup Window - NO External Images) -->
                <div class="border border-zinc-200 bg-white p-1.5 rounded-xl shadow-3xs">
                    <div class="relative overflow-hidden rounded-lg aspect-video bg-white border border-zinc-200 shadow-3xs">
                        <!-- Step 1 Live View -->
                        <div id="how-it-works-img-1" class="how-it-works-image w-full h-full p-4 flex flex-col justify-between transition-opacity duration-200 opacity-100">
                            <div class="space-y-2">
                                <span class="px-2 py-0.5 bg-primary-50 border border-primary-200 text-primary-700 text-[8px] font-bold rounded">Extension Active</span>
                                <div class="p-2 border border-zinc-200 rounded text-[9px] font-medium text-zinc-700 bg-[#fafafa] truncate">https://www.linkedin.com/jobs/view/39201938/</div>
                            </div>
                            <div class="h-7 bg-primary-600 text-white rounded text-[9px] font-bold flex items-center justify-center gap-1 shadow-3xs cursor-default">
                                <i class="ph-bold ph-magic-wand"></i>
                                <span>Auto-Fill Now</span>
                            </div>
                        </div>

                        <!-- Step 2 Live View -->
                        <div id="how-it-works-img-2" class="how-it-works-image w-full h-full p-4 flex flex-col justify-between transition-opacity duration-200 opacity-0 absolute inset-0 hidden">
                            <div class="space-y-2">
                                <span class="px-2 py-0.5 bg-primary-50 border border-primary-200 text-primary-700 text-[8px] font-bold rounded">Pipeline Status</span>
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-primary-500 animate-pulse"></span>
                                    <span class="text-[10px] font-bold text-zinc-800">Interview Scheduled</span>
                                </div>
                            </div>
                            <div class="p-2 bg-[#fafafa] border border-zinc-200 rounded text-[9px] text-zinc-500 font-semibold leading-normal">
                                <span class="font-bold text-zinc-700 block">Jadwal Interview HR:</span> 12 Juli 2026, 10.00 WIB
                            </div>
                        </div>

                        <!-- Step 3 Live View -->
                        <div id="how-it-works-img-3" class="how-it-works-image w-full h-full p-4 flex flex-col justify-between transition-opacity duration-200 opacity-0 absolute inset-0 hidden">
                            <div class="space-y-2">
                                <span class="px-2 py-0.5 bg-emerald-50 border border-emerald-100 text-emerald-600 text-[8px] font-bold rounded">ATS Keyword Scanner</span>
                                <div class="w-full h-2 bg-zinc-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-emerald-500 rounded-full" style="width: 92%"></div>
                                </div>
                            </div>
                            <div class="p-2 bg-[#fafafa] border border-zinc-200 rounded text-[9px] text-zinc-550 font-bold leading-normal">
                                Score CV: 92/100 (Optimal Match)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section (Borderless accordion lines) -->
    <section id="faq" class="py-20 bg-white border-t border-zinc-200/60">
        <div class="max-w-2xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center max-w-xl mx-auto mb-16">
                <p class="font-mono text-[9px] tracking-wider text-primary-600 font-bold uppercase mb-2">FAQ</p>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-zinc-900 tracking-tight mb-3">Pertanyaan Umum</h2>
                <p class="text-xs sm:text-sm text-zinc-500 font-medium">Temukan tanggapan cepat untuk pertanyaan yang sering diajukan mengenai layanan kami.</p>
            </div>

            <!-- FAQ List -->
            <div class="divide-y divide-zinc-200">
                <!-- FAQ Item 1 -->
                <div class="faq-item py-4">
                    <button onclick="toggleFaq(1)" class="faq-trigger w-full flex items-center justify-between text-left text-xs font-bold text-zinc-800 hover:text-zinc-950 transition-colors focus:outline-none" aria-expanded="false">
                        <span>Apakah platform TraKerja benar-benar gratis?</span>
                        <i class="ph-bold ph-caret-down faq-icon text-zinc-400 transition-transform duration-200"></i>
                    </button>
                    <div id="faq-content-1" class="faq-content hidden mt-3 text-[11px] text-zinc-550 leading-relaxed pl-1">
                        Ya, seluruh fitur utama pelacakan lamaran kerja (Kanban Tracker) dan integrasi Ekstensi Chrome dapat digunakan 100% gratis selamanya. Kami menawarkan opsi integrasi premium bagi pengguna korporat yang membutuhkan dedicated server.
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="faq-item py-4">
                    <button onclick="toggleFaq(2)" class="faq-trigger w-full flex items-center justify-between text-left text-xs font-bold text-zinc-800 hover:text-zinc-950 transition-colors focus:outline-none" aria-expanded="false">
                        <span>Bagaimana cara kerja Chrome Extension Auto-Fill?</span>
                        <i class="ph-bold ph-caret-down faq-icon text-zinc-400 transition-transform duration-200"></i>
                    </button>
                    <div id="faq-content-2" class="faq-content hidden mt-3 text-[11px] text-zinc-555 leading-relaxed pl-1">
                        Setelah memasang ekstensi resmi kami dari Chrome Web Store, Anda cukup membuka halaman lowongan kerja aktif (seperti LinkedIn, JobStreet, atau Glints) lalu mengeklik ikon ekstensi. Data detail lowongan akan diekstrak secara otomatis dan langsung diunggah ke papan lamaran Anda.
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="faq-item py-4">
                    <button onclick="toggleFaq(3)" class="faq-trigger w-full flex items-center justify-between text-left text-xs font-bold text-zinc-800 hover:text-zinc-950 transition-colors focus:outline-none" aria-expanded="false">
                        <span>Apakah data lamaran kerja saya aman di TraKerja?</span>
                        <i class="ph-bold ph-caret-down faq-icon text-zinc-400 transition-transform duration-200"></i>
                    </button>
                    <div id="faq-content-3" class="faq-content hidden mt-3 text-[11px] text-zinc-555 leading-relaxed pl-1">
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
                    <p class="text-[8.5px] text-zinc-455 leading-none mt-0.5">Kolaborasi & integrasi sistem</p>
                </div>
            </button>

            <button onclick="showContactTopic('whatsapp', '')" class="w-full p-2.5 bg-white border border-zinc-200 rounded-xl hover:border-zinc-350 transition-all text-left flex items-center gap-3">
                <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center text-emerald-600 shadow-3xs">
                    <i class="ph-bold ph-whatsapp-logo text-xs"></i>
                </div>
                <div>
                    <h5 class="text-[10px] font-bold text-zinc-800">WhatsApp Support</h5>
                    <p class="text-[8.5px] text-zinc-455 leading-none mt-0.5">Dukungan teknis & integrasi</p>
                </div>
            </button>

            <button onclick="showContactTopic('email', 'Pertanyaan Umum & Bantuan')" class="w-full p-2.5 bg-white border border-zinc-200 rounded-xl hover:border-zinc-350 transition-all text-left flex items-center gap-3">
                <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-700 shadow-3xs">
                    <i class="ph-bold ph-envelope text-xs"></i>
                </div>
                <div>
                    <h5 class="text-[10px] font-bold text-zinc-800">Email Support</h5>
                    <p class="text-[8.5px] text-zinc-455 leading-none mt-0.5">Hubungi kami melalui pesan email</p>
                </div>
            </button>
        </div>

        <!-- Screen 2: Email Form -->
        <div id="contact-screen-2" class="hidden p-4 space-y-3">
            <button onclick="backToScreen1()" class="flex items-center gap-1 text-[8px] text-zinc-400 hover:text-zinc-655 font-bold font-mono uppercase tracking-wider mb-1">
                <i class="ph-bold ph-arrow-left"></i> Kembali
            </button>
            <form id="contact-form" onsubmit="submitContactForm(event)" class="space-y-3">
                <input type="hidden" id="contact-subject-hidden" name="subject" value="">
                <div>
                    <label class="block text-[8px] font-bold text-zinc-455 uppercase tracking-wider mb-1">Nama Lengkap</label>
                    <input type="text" name="name" id="contact-name" required class="block w-full px-2.5 h-[28px] border border-zinc-200 bg-zinc-50/20 rounded text-[10px] font-medium text-zinc-700 outline-none focus:bg-white focus:ring-1 focus:ring-zinc-900/10 focus:border-zinc-400 transition-all">
                </div>
                <div>
                    <label class="block text-[8px] font-bold text-zinc-455 uppercase tracking-wider mb-1">Alamat Email</label>
                    <input type="email" name="email" id="contact-email" required class="block w-full px-2.5 h-[28px] border border-zinc-200 bg-zinc-50/20 rounded text-[10px] font-medium text-zinc-700 outline-none focus:bg-white focus:ring-1 focus:ring-zinc-900/10 focus:border-zinc-400 transition-all">
                </div>
                <div>
                    <label class="block text-[8px] font-bold text-zinc-455 uppercase tracking-wider mb-1">Pesan Anda</label>
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
            <button onclick="backToScreen1()" class="flex items-center gap-1 text-[8px] text-zinc-400 hover:text-zinc-655 font-bold font-mono uppercase tracking-wider mb-1">
                <i class="ph-bold ph-arrow-left"></i> Kembali
            </button>
            <div class="w-10 h-10 rounded bg-zinc-50 border border-zinc-205 flex items-center justify-center text-emerald-600 mx-auto shadow-3xs">
                <i class="ph-bold ph-whatsapp-logo text-lg"></i>
            </div>
            <div>
                <h5 class="text-xs font-bold text-zinc-800">WhatsApp Support</h5>
                <p class="text-[10px] text-zinc-455 leading-relaxed mt-1">Anda akan diarahkan ke kontak WhatsApp resmi PT. Teknalogi Transformasi Digital untuk bantuan teknis.</p>
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
                <p class="text-[10px] text-zinc-455 leading-relaxed mt-1">Terima kasih. Tim kami akan segera merespon laporan Anda melalui alamat email.</p>
            </div>
            <button type="button" onclick="backToScreen1()" class="h-7 px-4 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 rounded-md text-[10px] font-bold transition-all">
                Kembali
            </button>
        </div>
    </div>

    <!-- Javascript Core Functionality -->
    <script>
        // Switch Showcase Mockup tabs
        function switchMockupTab(tab) {
            // Hide all tabs
            ['tracker', 'ai', 'extension'].forEach(t => {
                const el = document.getElementById('mockup-tab-' + t);
                const btn = document.getElementById('mockup-tab-btn-' + t);
                if (el) el.classList.add('hidden');
                if (btn) {
                    btn.className = "px-3.5 py-1 hover:bg-white/40 rounded transition-all";
                }
            });

            // Show selected
            const activeEl = document.getElementById('mockup-tab-' + tab);
            const activeBtn = document.getElementById('mockup-tab-btn-' + tab);
            if (activeEl) activeEl.classList.remove('hidden');
            if (activeBtn) {
                activeBtn.className = "px-3.5 py-1 bg-white border border-zinc-200 rounded text-zinc-800 shadow-3xs font-black transition-all";
            }
        }

        // Toggle How it Works Stepper
        function toggleHowItWorks(step) {
            // Reset trigger styling
            document.querySelectorAll('.hiw-card').forEach(card => {
                card.className = "hiw-card cursor-pointer border-l border-zinc-200 hover:border-zinc-350 p-4 rounded-r-lg transition-all duration-200 bg-white/45 select-none";
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
                activeCard.className = "hiw-card cursor-pointer border-l-2 border-primary-500 bg-white p-4 rounded-r-lg transition-all duration-200 select-none shadow-3xs";
            }
            const activeContent = document.getElementById('hiw-content-' + step);
            if (activeContent) {
                activeContent.classList.remove('hidden');
            }
            const activeNum = document.getElementById('hiw-num-' + step);
            if (activeNum) {
                activeNum.className = "font-mono text-xs font-bold text-primary-650";
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

        // Switch topic
        function showContactTopic(type, subject) {
            hideAllScreens();
            if (type === 'whatsapp') {
                document.getElementById('contact-screen-3').classList.remove('hidden');
            } else {
                document.getElementById('contact-subject-hidden').value = subject;
                document.getElementById('contact-screen-2').classList.remove('hidden');
            }
        }

        // Back to Options
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