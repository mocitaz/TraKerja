<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TraKerja - Platform Pelacakan & Manajemen Lamaran Kerja ala Notion</title>
    <meta name="description" content="TraKerja adalah platform ATS & tracker lamaran kerja gratis. Pantau status lamaran, buat CV standar ATS, dan dapatkan insight analitik untuk karir impian Anda.">
    <meta name="keywords" content="loker, lowongan kerja, tracker lamaran kerja, ats checker, cv ats friendly, karir, hrd, job portal, trakerja, manajemen lamaran">
    <meta name="author" content="PT. Teknalogi Transformasi Digital">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/') }}">
    <meta name="theme-color" content="#ffffff">

    <!-- OpenGraph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="TraKerja - Platform Manajemen & Pelacakan Lamaran Kerja">
    <meta property="og:description" content="Tingkatkan peluang lolos kerja dengan tracker cerdas, AI Cover Letter, dan analitik lengkap. Gratis untuk pencari kerja Indonesia.">
    <meta property="og:image" content="{{ asset('images/fitur-section.jpg') }}">

    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('images/icon.png') }}?v=2">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    
    <!-- Fonts Bunny: Inter + Plus Jakarta Sans + JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,850,900|plus-jakarta-sans:700,800|jetbrains-mono:400,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        /* Notion Colors */
        :root {
            --notion-bg: #FFFFFF;
            --notion-text: #1A1A1A;
            --notion-text-muted: #5F5F5F;
            --notion-border: #E3E2E0;
            --notion-yellow: #FFF9E6;
            --notion-yellow-border: #FBE49C;
            --notion-blue: #EBF3FA;
            --notion-blue-border: #BCD9ED;
            --notion-red: #FDEBEC;
            --notion-red-border: #FACACA;
            --notion-green: #EDF3EC;
            --notion-green-border: #D1E4D1;
        }

        body {
            background-color: var(--notion-bg);
            color: var(--notion-text);
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .notion-h1 {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            letter-spacing: -0.03em;
            font-weight: 800;
            line-height: 1.1;
        }

        .notion-mono {
            font-family: 'JetBrains Mono', monospace;
        }

        /* Bento Grid Card */
        .bento-card {
            border: 1px solid var(--notion-border);
            border-radius: 12px;
            background: #FFFFFF;
            transition: all 0.2s ease;
        }
        .bento-card:hover {
            border-color: rgba(26, 26, 26, 0.2);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        }

        /* Status Pill */
        .notion-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 600;
            border: 1px solid transparent;
        }
        .notion-pill.applied { background: var(--notion-blue); border-color: var(--notion-blue-border); color: #215984; }
        .notion-pill.interview { background: var(--notion-yellow); border-color: var(--notion-yellow-border); color: #8F6B00; }
        .notion-pill.offer { background: var(--notion-green); border-color: var(--notion-green-border); color: #2E6B3C; }

        /* Smooth reveal animation */
        .reveal {
            opacity: 0;
            transform: translateY(16px);
            transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="antialiased bg-white">



    {{-- ============ NAVIGATION ============ --}}
    <nav class="fixed top-0 left-0 right-0 bg-white/80 backdrop-blur-md border-b border-zinc-200/60 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-14">
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-7 h-7" onerror="this.style.display='none';">
                    <span class="text-sm font-extrabold tracking-tight">TraKerja</span>
                    <span class="px-1.5 py-0.5 bg-zinc-100 text-zinc-650 text-[9px] font-bold uppercase rounded border border-zinc-200">Career OS</span>
                </a>

                <div class="hidden md:flex items-center gap-1 text-xs font-semibold text-zinc-500">
                    <a href="#fitur" class="px-3 py-1.5 rounded hover:bg-zinc-100 hover:text-zinc-900 transition-colors">Features</a>
                    <a href="#pricing" class="px-3 py-1.5 rounded hover:bg-zinc-100 hover:text-zinc-900 transition-colors">Pricing</a>
                    <a href="#testimonials" class="px-3 py-1.5 rounded hover:bg-zinc-100 hover:text-zinc-900 transition-colors">Testimonials</a>
                    <a href="#faq" class="px-3 py-1.5 rounded hover:bg-zinc-100 hover:text-zinc-900 transition-colors">FAQ</a>
                </div>

                <div class="flex items-center gap-2">
                    @auth
                        <a href="{{ url('/tracker') }}" class="px-3.5 py-1.5 bg-zinc-900 text-white rounded-lg text-xs font-bold hover:bg-zinc-800 transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-3 py-1.5 text-zinc-600 rounded-lg text-xs font-bold hover:bg-zinc-100 transition">Log in</a>
                        <a href="{{ route('register') }}" class="px-3.5 py-1.5 bg-zinc-900 text-white rounded-lg text-xs font-bold hover:bg-zinc-800 transition shadow-xs">Get Started</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- ============ HERO ============ --}}
    <section class="relative pt-24 pb-16 sm:pt-32 sm:pb-24 overflow-hidden border-b border-zinc-100">
        <div class="max-w-4xl mx-auto px-4 text-center">
            
            <!-- Hand-drawn avatars pile concept -->
            <div class="flex justify-center -space-x-2.5 mb-6 reveal">
                <div class="w-9 h-9 rounded-full border border-zinc-300 bg-blue-100 flex items-center justify-center text-sm shadow-2xs">🕵️‍♂️</div>
                <div class="w-9 h-9 rounded-full border border-zinc-300 bg-amber-100 flex items-center justify-center text-sm shadow-2xs">💼</div>
                <div class="w-9 h-9 rounded-full border border-zinc-300 bg-emerald-100 flex items-center justify-center text-sm shadow-2xs">🎯</div>
                <div class="w-9 h-9 rounded-full border border-zinc-300 bg-rose-100 flex items-center justify-center text-sm shadow-2xs">🧠</div>
            </div>

            <h1 class="notion-h1 text-4xl sm:text-6xl text-zinc-900 mb-6 leading-tight reveal">
                Where job seekers & careers <br>
                <span class="relative inline-block px-3.5 py-1 bg-amber-50 rounded-full border border-amber-250 text-zinc-800 select-none">
                    Grow together.
                </span>
            </h1>

            <p class="text-sm sm:text-base text-zinc-500 max-w-xl mx-auto mb-8 font-medium leading-relaxed reveal">
                Satu workspace minimalis ala Notion untuk mengelola seluruh lamaran kerja Anda, membuat CV standar ATS, dan mengoptimasi peluang karir menggunakan AI.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-2.5 mb-10 reveal">
                @auth
                    <a href="{{ url('/tracker') }}" class="px-5 py-2.5 bg-zinc-900 text-white rounded-lg text-sm font-bold hover:bg-zinc-800 transition shadow-xs w-full sm:w-auto">Buka Dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="px-5 py-2.5 bg-zinc-900 text-white rounded-lg text-sm font-bold hover:bg-zinc-800 transition shadow-xs w-full sm:w-auto">Get TraKerja Free</a>
                    <a href="#fitur" class="px-5 py-2.5 bg-white text-zinc-600 border border-zinc-200 rounded-lg text-sm font-bold hover:bg-zinc-50 transition w-full sm:w-auto">Explore Features</a>
                @endauth
            </div>

            <p class="text-[11px] text-zinc-400 font-semibold uppercase tracking-wider reveal">
                ⚡️ 100% Free to Start • No Credit Card Required
            </p>
        </div>

        <!-- Dashboard mockup container -->
        <div class="relative max-w-4xl mx-auto mt-14 px-4 reveal">
            <div class="bento-card overflow-hidden shadow-xl border-zinc-200/80 bg-zinc-50/50 p-2">
                <div class="border border-zinc-200/70 rounded-lg overflow-hidden bg-white shadow-2xs">
                    <!-- Browser-like window header -->
                    <div class="bg-zinc-50 border-b border-zinc-200 px-4 py-2.5 flex items-center gap-2">
                        <div class="flex gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-400"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                        </div>
                        <div class="mx-auto bg-white border border-zinc-200/60 rounded-md text-[10px] text-zinc-400 font-bold px-12 py-0.5 select-none truncate max-w-xs md:max-w-md">
                            trakerja.com/dashboard
                        </div>
                    </div>
                    <div class="w-full aspect-video bg-zinc-50 flex items-center justify-center">
                    </div>
                </div>
            </div>

            <!-- Floating Stage Chips from Previous Version -->
            <div class="notion-pill applied absolute -left-4 sm:-left-10 top-8 hidden sm:inline-flex shadow-md border border-blue-200/85" style="transform: rotate(-6deg);">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Applied
            </div>
            <div class="notion-pill interview absolute -right-4 sm:-right-8 top-1/3 hidden sm:inline-flex shadow-md border border-amber-200/85" style="transform: rotate(4deg);">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Interview
            </div>
            <div class="notion-pill offer absolute -left-2 sm:-left-6 bottom-6 hidden sm:inline-flex shadow-md border border-emerald-200/85" style="transform: rotate(-3deg);">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Offer
            </div>
        </div>
    </section>

    {{-- ============ FEATURES GRID ============ --}}
    <section id="fitur" class="py-20 sm:py-24 border-b border-zinc-100">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="max-w-xl mb-12 reveal">
                <span class="px-2 py-0.5 bg-blue-50 text-blue-800 text-[9px] font-extrabold uppercase rounded border border-blue-100">One Tool, All Careers</span>
                <h2 class="notion-h1 text-3xl sm:text-4xl text-zinc-900 mt-3">Semua yang Anda butuhkan untuk memenangkan persaingan kerja.</h2>
            </div>

            <!-- Bento Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 reveal">
                
                <div class="bento-card p-6 flex flex-col md:col-span-2">
                    <div class="w-9 h-9 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-lg mb-5">📋</div>
                    <h3 class="font-bold text-zinc-800 text-sm mb-1.5">Kanban Board Tracker</h3>
                    <p class="text-xs text-zinc-500 leading-relaxed mb-4 flex-1">Pantau seluruh siklus lamaran Anda mulai dari tahap pencarian, applied, interview, hingga offer. Cukup drag and drop untuk merapikan progres.</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="notion-pill applied"><span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Applied</span>
                        <span class="notion-pill interview"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Interview</span>
                        <span class="notion-pill offer"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Offer</span>
                    </div>
                </div>

                <div class="bento-card p-6 flex flex-col">
                    <div class="w-9 h-9 rounded-lg bg-rose-50 border border-rose-100 flex items-center justify-center text-lg mb-5">🧠</div>
                    <h3 class="font-bold text-zinc-800 text-sm mb-1.5">AI CV Analyzer</h3>
                    <p class="text-xs text-zinc-500 leading-relaxed">Analisis CV Anda layaknya software ATS HRD. Dapatkan skor instan, identifikasi kata kunci yang hilang, dan optimalkan CV Anda agar lolos rekrutmen.</p>
                </div>

                <div class="bento-card p-6 flex flex-col">
                    <div class="w-9 h-9 rounded-lg bg-amber-50 border border-amber-100 flex items-center justify-center text-lg mb-5">✨</div>
                    <h3 class="font-bold text-zinc-800 text-sm mb-1.5">AI Cover Letter</h3>
                    <p class="text-xs text-zinc-500 leading-relaxed">Buat surat lamaran kerja kustom berkualitas tinggi hanya dalam hitungan detik. Disesuaikan khusus dengan deskripsi pekerjaan yang dilamar.</p>
                </div>

                <div class="bento-card p-6 flex flex-col md:col-span-2">
                    <div class="w-9 h-9 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center text-lg mb-5">📈</div>
                    <h3 class="font-bold text-zinc-800 text-sm mb-1.5">Career Analytics</h3>
                    <p class="text-xs text-zinc-500 leading-relaxed">Dapatkan visualisasi analitik lengkap dari riwayat pencarian kerja Anda. Ketahui platform mana yang paling efektif mendatangkan panggilan interview.</p>
                </div>

            </div>
        </div>
    </section>

    {{-- ============ HOW IT WORKS (Notion interactive switcher) ============ --}}
    <section class="py-20 sm:py-24 bg-zinc-50/50 border-b border-zinc-100">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-14 reveal">
                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-800 text-[9px] font-extrabold uppercase rounded border border-emerald-100">Workflow</span>
                <h2 class="notion-h1 text-3xl sm:text-4xl text-zinc-900 mt-3">Mulai dalam 3 langkah praktis.</h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <!-- Selectors -->
                <div class="lg:col-span-5 space-y-3.5 reveal">
                    <div class="how-it-works-item bento-card overflow-hidden cursor-pointer" style="border-color:var(--notion-yellow-border); background:var(--notion-yellow);" id="hiw-item-1" onclick="toggleHowItWorks(1)">
                        <div class="p-5 flex items-start gap-4">
                            <div class="w-8 h-8 rounded-lg bg-white border border-zinc-200/60 flex items-center justify-center shrink-0 font-bold text-xs">1</div>
                            <div>
                                <h3 class="font-bold text-zinc-800 text-sm">Daftar & Atur Profil Karir</h3>
                                <p class="text-xs text-zinc-500 mt-1 leading-relaxed">Lengkapi profil Anda sekali. Informasi ini akan digunakan secara otomatis untuk kustomisasi dokumen lamaran.</p>
                            </div>
                        </div>
                    </div>

                    <div class="how-it-works-item bento-card overflow-hidden cursor-pointer" id="hiw-item-2" onclick="toggleHowItWorks(2)">
                        <div class="p-5 flex items-start gap-4">
                            <div class="w-8 h-8 rounded-lg bg-white border border-zinc-200/60 flex items-center justify-center shrink-0 font-bold text-xs">2</div>
                            <div>
                                <h3 class="font-bold text-zinc-800 text-sm">Track Lamaran Kerja</h3>
                                <p class="text-xs text-zinc-500 mt-1 leading-relaxed">Tambah lamaran langsung atau via PDF. Pantau riwayat status wawancara & tawaran kerja dengan mudah.</p>
                            </div>
                        </div>
                    </div>

                    <div class="how-it-works-item bento-card overflow-hidden cursor-pointer" id="hiw-item-3" onclick="toggleHowItWorks(3)">
                        <div class="p-5 flex items-start gap-4">
                            <div class="w-8 h-8 rounded-lg bg-white border border-zinc-200/60 flex items-center justify-center shrink-0 font-bold text-xs">3</div>
                            <div>
                                <h3 class="font-bold text-zinc-800 text-sm">Optimasi & Raih Karir Impian</h3>
                                <p class="text-xs text-zinc-500 mt-1 leading-relaxed">Gunakan analitik karir, AI CV Analyzer, dan Cover Letter Generator untuk terus melesat maju.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview Display -->
                <div class="lg:col-span-7 relative flex flex-col items-center justify-center reveal">
                    <div class="relative w-full aspect-[16/10] bento-card border-zinc-200 bg-white p-2">
                        <div class="w-full h-full border border-zinc-200/60 rounded-lg overflow-hidden bg-zinc-50/50 relative">
                            <img id="how-it-works-img-1" src="{{ asset('images/mu0.png') }}" alt="Daftar & atur profil" class="how-it-works-image absolute inset-0 w-full h-full object-contain transition-opacity duration-300" style="opacity:1;">
                            <img id="how-it-works-img-2" src="{{ asset('images/mu1.png') }}" alt="Kelola lamaran" class="how-it-works-image absolute inset-0 w-full h-full object-contain transition-opacity duration-300 hidden opacity-0">
                            <img id="how-it-works-img-3" src="{{ asset('images/mu2.png') }}" alt="Analisis & optimalkan" class="how-it-works-image absolute inset-0 w-full h-full object-contain transition-opacity duration-300 hidden opacity-0">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ PRICING ============ --}}
    <section class="py-20 sm:py-24 border-b border-zinc-100" id="pricing">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-14 reveal">
                <span class="px-2 py-0.5 bg-rose-50 text-rose-800 text-[9px] font-extrabold uppercase rounded border border-rose-100">Simple Pricing</span>
                <h2 class="notion-h1 text-3xl sm:text-4xl text-zinc-900 mt-3">Skema harga transparan & hemat.</h2>
            </div>

            <!-- Pricing Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-stretch reveal">
                
                <!-- Free Tier -->
                <div class="bento-card p-8 flex flex-col">
                    <h3 class="font-bold text-zinc-500 text-xs uppercase tracking-wider">Free Standar</h3>
                    <p class="text-4xl font-extrabold text-zinc-900 mt-4 mb-1">Gratis</p>
                    <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-6">Selamanya</p>
                    
                    <div class="border-t border-zinc-200/70 pt-6 space-y-3.5 flex-1 mb-8 text-xs font-medium text-zinc-600">
                        <div class="flex items-center gap-2"><span>✓</span> <span>Maksimal 25 job tracker</span></div>
                        <div class="flex items-center gap-2"><span>✓</span> <span>2 template CV standar</span></div>
                        <div class="flex items-center gap-2"><span>✓</span> <span>1 kredit AI Analyzer gratis</span></div>
                        <div class="flex items-center gap-2"><span>✓</span> <span>3 kredit Cover Letter Generator</span></div>
                        <div class="flex items-center gap-2"><span>✓</span> <span>Dashboard & analitik standar</span></div>
                    </div>

                    <a href="{{ route('register') }}" class="w-full py-2 bg-zinc-50 hover:bg-zinc-100 text-zinc-800 border border-zinc-200/80 rounded-lg text-xs font-bold transition text-center">Mulai Gratis</a>
                </div>

                <!-- Premium Tier -->
                <div class="bento-card p-8 flex flex-col relative border-zinc-900/40 shadow-xs" style="background:#FCFCFC;">
                    <span class="absolute -top-3 left-6 bg-zinc-900 text-white text-[9px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Best Value</span>
                    <h3 class="font-bold text-zinc-900 text-xs uppercase tracking-wider mt-1">Premium Pro</h3>
                    <p class="text-4xl font-extrabold text-zinc-900 mt-4 mb-1">Rp 19.999</p>
                    <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest mb-6">Sekali bayar, akses selamanya</p>

                    <div class="border-t border-zinc-200/70 pt-6 space-y-3.5 flex-1 mb-8 text-xs font-medium text-zinc-600">
                        <div class="flex items-center gap-2 text-zinc-900 font-bold"><span>✓</span> <span>Unlimited job tracker</span></div>
                        <div class="flex items-center gap-2"><span>✓</span> <span>50+ template CV premium</span></div>
                        <div class="flex items-center gap-2"><span>✓</span> <span>Bulk importer lamaran kerja</span></div>
                        <div class="flex items-center gap-2"><span>✓</span> <span>Full analytics & dashboard</span></div>
                        <div class="flex items-center gap-2"><span>✓</span> <span>5 kredit bonus AI CV Analyzer</span></div>
                        <div class="flex items-center gap-2"><span>✓</span> <span>15 kredit Cover Letter Generator</span></div>
                    </div>

                    <a href="{{ route('payment.index') }}" class="w-full py-2 bg-zinc-900 hover:bg-zinc-800 text-white rounded-lg text-xs font-bold transition text-center shadow-sm">Beli Premium Pro</a>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ TESTIMONIALS ============ --}}
    <section class="py-20 sm:py-24 border-b border-zinc-100" id="testimonials">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-14 reveal">
                <span class="px-2 py-0.5 bg-blue-50 text-blue-800 text-[9px] font-extrabold uppercase rounded border border-blue-100">Testimonials</span>
                <h2 class="notion-h1 text-3xl sm:text-4xl text-zinc-900 mt-3">Telah membantu pencari kerja sukses.</h2>
            </div>

            <!-- Single large minimalist testimonial layout -->
            <div class="relative w-full overflow-hidden reveal">
                <div class="flex transition-transform duration-500 ease-in-out" id="testimonial-track">
                    
                    <div class="w-full flex-shrink-0 px-1">
                        <div class="bento-card p-8 md:p-10 flex flex-col md:flex-row gap-6 items-center">
                            <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center text-2xl shrink-0">🎓</div>
                            <div>
                                <p class="text-sm font-semibold text-zinc-700 italic leading-relaxed">"TraKerja merubah spreadsheet lamaran kerja saya yang sangat berantakan menjadi visual Kanban board yang super clean. Benar-benar game-changer bagi fresh graduate!"</p>
                                <div class="mt-4 flex items-center gap-2">
                                    <h4 class="text-xs font-bold text-zinc-800">Rendika</h4>
                                    <span class="text-[10px] text-zinc-400 font-bold">• Fresh Graduate</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full flex-shrink-0 px-1">
                        <div class="bento-card p-8 md:p-10 flex flex-col md:flex-row gap-6 items-center">
                            <div class="w-16 h-16 rounded-full bg-amber-100 flex items-center justify-center text-2xl shrink-0">🚀</div>
                            <div>
                                <p class="text-sm font-semibold text-zinc-700 italic leading-relaxed">"Dashboard-nya sangat intuitif dan minimalis. Analitik lamaran kerjanya mempermudah saya mengetahui status perkembangan lamaran kerja saya secara real-time."</p>
                                <div class="mt-4 flex items-center gap-2">
                                    <h4 class="text-xs font-bold text-zinc-800">Andi</h4>
                                    <span class="text-[10px] text-zinc-400 font-bold">• Career Switcher</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Navigation Controls -->
            <div class="flex justify-between items-center mt-6 reveal">
                <span class="notion-mono text-[10px] font-bold text-zinc-400" id="testi-counter">01 / 02</span>
                <div class="flex gap-2">
                    <button onclick="moveTestimonial(-1)" class="w-8 h-8 flex items-center justify-center rounded-lg border border-zinc-200 hover:bg-zinc-50 transition"><i class="ph ph-caret-left font-bold"></i></button>
                    <button onclick="moveTestimonial(1)" class="w-8 h-8 flex items-center justify-center rounded-lg border border-zinc-200 hover:bg-zinc-50 transition"><i class="ph ph-caret-right font-bold"></i></button>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ FAQ ============ --}}
    <section class="py-20 sm:py-24" id="faq">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">
            <h2 class="text-center notion-h1 text-2xl sm:text-3xl text-zinc-900 mb-10 reveal">Frequently Asked Questions</h2>
            
            <div class="divide-y divide-zinc-200/80 border-t border-b border-zinc-200/80 reveal">
                @php
                    $faqs = [
                        ['Apakah TraKerja benar-benar gratis?', 'Ya! TraKerja menyediakan paket gratis selamanya yang mencakup fitur utama seperti Kanban board, My Profile, dan monitoring lamaran. Jika Anda membutuhkan workspace tanpa batas dengan template premium, Anda dapat memilih paket Premium Pro.'],
                        ['Bagaimana cara kerja AI CV Analyzer?', 'AI CV Analyzer kami bekerja dengan mensimulasikan pembacaan ATS HRD profesional. AI memindai CV Anda, mencocokkannya dengan kriteria ATS standar, dan memberikan feedback konkret untuk optimasi.'],
                        ['Apakah data pribadi saya aman?', 'Sangat aman. Privasi dan keamanan data Anda adalah prioritas kami. Seluruh dokumen dan informasi lamaran disimpan secara privat dan hanya dapat diakses oleh Anda.'],
                    ];
                @endphp
                @foreach ($faqs as $faq)
                    <div class="py-4">
                        <button class="w-full text-left py-2 flex justify-between items-center group focus:outline-none" onclick="toggleFaq(this)">
                            <span class="text-sm font-bold text-zinc-800 group-hover:text-zinc-600 transition">{{ $faq[0] }}</span>
                            <span class="faq-icon transition-transform duration-200 text-zinc-400 shrink-0 ml-4"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg></span>
                        </button>
                        <div class="faq-answer hidden mt-2 text-xs font-semibold text-zinc-500 leading-relaxed pr-8">{{ $faq[1] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ FOOTER ============ --}}
    <footer class="bg-zinc-50/50 border-t border-zinc-200/60 py-12 text-xs text-zinc-500">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-6 h-6">
                <span class="font-extrabold text-zinc-800">TraKerja</span>
            </div>
            <p>© 2026 PT Teknalogi Transformasi Digital. Built for career growth.</p>
        </div>
    </footer>

    @livewireScripts

    <script>
        // Reveal on scroll
        (function () {
            if (!('IntersectionObserver' in window)) {
                document.querySelectorAll('.reveal').forEach(el => el.classList.add('visible'));
                return;
            }
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.05 });
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
            });
        })();

        // FAQ Toggle
        function toggleFaq(button) {
            const answer = button.nextElementSibling;
            const icon = button.querySelector('.faq-icon');
            const isOpen = !answer.classList.contains('hidden');
            
            document.querySelectorAll('.faq-answer').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.faq-icon').forEach(el => el.style.transform = 'rotate(0deg)');

            if (!isOpen) {
                answer.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            }
        }

        // How it works tabs switcher
        function toggleHowItWorks(step) {
            const allItems = document.querySelectorAll('.how-it-works-item');
            const allImages = document.querySelectorAll('.how-it-works-image');

            allItems.forEach((item, idx) => {
                const currentIdx = idx + 1;
                if (currentIdx === step) {
                    item.style.borderColor = 'var(--notion-yellow-border)';
                    item.style.background = 'var(--notion-yellow)';
                } else {
                    item.style.borderColor = '';
                    item.style.background = '';
                }
            });

            allImages.forEach((img, idx) => {
                const currentIdx = idx + 1;
                if (currentIdx === step) {
                    img.classList.remove('hidden');
                    setTimeout(() => img.style.opacity = '1', 50);
                } else {
                    img.style.opacity = '0';
                    setTimeout(() => img.classList.add('hidden'), 200);
                }
            });
        }

        // Testimonial Carousel
        let currentTesti = 0;
        const totalTesti = 2;
        function moveTestimonial(direction) {
            currentTesti = (currentTesti + direction + totalTesti) % totalTesti;
            const track = document.getElementById('testimonial-track');
            if (track) track.style.transform = `translateX(-${currentTesti * 100}%)`;
            const counter = document.getElementById('testi-counter');
            if (counter) counter.innerText = `0${currentTesti + 1} / 0${totalTesti}`;
        }


    </script>
</body>
</html>
