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
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

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
                    <!-- Simulated Premium Notion-Style Dashboard Layout -->
                    <div class="w-full bg-[#fafafa] text-zinc-800 text-[10px] font-medium p-4 select-none relative overflow-hidden min-h-[380px]">
                        
                        <!-- Premium Notion-Inspired Page Header -->
                        <div class="flex items-start gap-2 border-b border-zinc-200/50 pb-3 mb-4">
                            <!-- Icon Logo -->
                            <div class="w-6.5 h-6.5 bg-zinc-100 border border-zinc-200/60 rounded flex items-center justify-center text-zinc-500 shrink-0 shadow-3xs mt-0.5">
                                <i class="ph ph-circles-four text-xs"></i>
                            </div>
                            
                            <!-- Main Header Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-1.5">
                                    <h1 class="text-[11px] font-bold text-zinc-800 tracking-tight">Dashboard Overview</h1>
                                    <span class="px-1 py-0.2 bg-emerald-50 text-emerald-700 text-[7px] font-bold uppercase rounded border border-emerald-100/65">Live</span>
                                </div>
                                <div class="flex items-center justify-between gap-2 mt-0.5">
                                    <p class="text-[8px] text-zinc-400">
                                        Welcome back, <span class="font-bold text-zinc-650">Luthfi</span>! Here is your career momentum and tracking progress.
                                    </p>
                                    <div class="flex items-center gap-1 text-[8.5px] font-semibold text-zinc-550 shrink-0">
                                        <i class="ph ph-calendar-blank text-zinc-450"></i>
                                        <span>Friday, 10 July 2026</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Stats Overview Cards -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mb-4">
                            <!-- On Process Card -->
                            <div class="bg-white rounded border border-zinc-200/60 p-2 flex items-center justify-between shadow-3xs">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="w-6 h-6 bg-blue-50/50 rounded flex items-center justify-center text-blue-600 shrink-0"><i class="ph ph-spinner-gap text-xs animate-spin-slow"></i></div>
                                    <div class="min-w-0">
                                        <p class="text-[5px] font-bold text-zinc-400 uppercase tracking-wider leading-none">On Process</p>
                                        <p class="text-[7px] text-zinc-500 font-semibold mt-0.5 leading-none">Active Apps</p>
                                    </div>
                                </div>
                                <p class="text-xs font-bold text-zinc-800 leading-none">12</p>
                            </div>

                            <!-- Success Card -->
                            <div class="bg-white rounded border border-zinc-200/60 p-2 flex items-center justify-between shadow-3xs">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="w-6 h-6 bg-emerald-50/50 rounded flex items-center justify-center text-emerald-600 shrink-0"><i class="ph ph-check-circle text-xs"></i></div>
                                    <div class="min-w-0">
                                        <p class="text-[5px] font-bold text-zinc-400 uppercase tracking-wider leading-none">Success</p>
                                        <p class="text-[7px] text-zinc-500 font-semibold mt-0.5 leading-none">Offers & Recs</p>
                                    </div>
                                </div>
                                <p class="text-xs font-bold text-zinc-800 leading-none">3</p>
                            </div>

                            <!-- Declined Card -->
                            <div class="bg-white rounded border border-zinc-200/60 p-2 flex items-center justify-between shadow-3xs">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="w-6 h-6 bg-rose-50/50 rounded flex items-center justify-center text-rose-600 shrink-0"><i class="ph ph-x-circle text-xs"></i></div>
                                    <div class="min-w-0">
                                        <p class="text-[5px] font-bold text-zinc-400 uppercase tracking-wider leading-none">Declined</p>
                                        <p class="text-[7px] text-zinc-500 font-semibold mt-0.5 leading-none">Rejected</p>
                                    </div>
                                </div>
                                <p class="text-xs font-bold text-zinc-800 leading-none">5</p>
                            </div>

                            <!-- Interviews Card -->
                            <div class="bg-white rounded border border-zinc-200/60 p-2 flex items-center justify-between shadow-3xs">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="w-6 h-6 bg-orange-50/50 rounded flex items-center justify-center text-orange-600 shrink-0"><i class="ph ph-calendar text-xs"></i></div>
                                    <div class="min-w-0">
                                        <p class="text-[5px] font-bold text-zinc-400 uppercase tracking-wider leading-none">Interviews</p>
                                        <p class="text-[7px] text-zinc-500 font-semibold mt-0.5 leading-none">Total Scheduled</p>
                                    </div>
                                </div>
                                <p class="text-xs font-bold text-zinc-800 leading-none">4</p>
                            </div>
                        </div>

                        <!-- Main Layout Splits -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                            <!-- Recent Applications -->
                            <div class="col-span-2 bg-white rounded border border-zinc-200/60 p-3 shadow-3xs flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <span class="font-bold text-zinc-800 text-[9px] uppercase tracking-wider">Recent Applications</span>
                                            <p class="text-[7px] text-zinc-400 mt-0.5">Your latest job submissions</p>
                                        </div>
                                        <span class="px-2 py-0.5 bg-white border border-zinc-200 rounded text-[7px] font-bold text-zinc-650 hover:bg-zinc-50 transition cursor-pointer">View Tracker →</span>
                                    </div>
                                    <div class="space-y-1.5">
                                        <div class="bg-zinc-50 border border-zinc-200/50 p-2 rounded flex items-center justify-between">
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-bold text-zinc-800 text-[10px]">Gojek</span>
                                                <span class="text-zinc-300 text-[8px]">•</span>
                                                <span class="text-zinc-550 font-semibold text-[9px]">Product Designer</span>
                                            </div>
                                            <div class="flex items-center gap-1.5">
                                                <span class="px-1.5 py-0.2 rounded text-[7px] font-bold uppercase bg-amber-50 text-amber-700 border border-amber-100">HR - Interview</span>
                                                <span class="px-1.5 py-0.2 rounded text-[7px] font-bold uppercase bg-blue-50 text-blue-700 border border-blue-100">On Process</span>
                                            </div>
                                        </div>
                                        <div class="bg-zinc-50 border border-zinc-200/50 p-2 rounded flex items-center justify-between">
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-bold text-zinc-800 text-[10px]">Tokopedia</span>
                                                <span class="text-zinc-300 text-[8px]">•</span>
                                                <span class="text-zinc-550 font-semibold text-[9px]">Frontend Engineer</span>
                                            </div>
                                            <div class="flex items-center gap-1.5">
                                                <span class="px-1.5 py-0.2 rounded text-[7px] font-bold uppercase bg-blue-50 text-blue-700 border border-blue-100">Applied</span>
                                                <span class="px-1.5 py-0.2 rounded text-[7px] font-bold uppercase bg-blue-50 text-blue-700 border border-blue-100">On Process</span>
                                            </div>
                                        </div>
                                        <div class="bg-zinc-50 border border-zinc-200/50 p-2 rounded flex items-center justify-between">
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-bold text-zinc-800 text-[10px]">Traveloka</span>
                                                <span class="text-zinc-300 text-[8px]">•</span>
                                                <span class="text-zinc-550 font-semibold text-[9px]">UI Designer</span>
                                            </div>
                                            <div class="flex items-center gap-1.5">
                                                <span class="px-1.5 py-0.2 rounded text-[7px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-100">Offering</span>
                                                <span class="px-1.5 py-0.2 rounded text-[7px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-100">Accepted</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Weekly Target -->
                            <div class="bg-white rounded border border-zinc-200/60 p-3 shadow-3xs flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-bold text-zinc-850 text-[9px] uppercase tracking-wider">Weekly Target</span>
                                    </div>
                                    <p class="text-[7.5px] text-zinc-400 font-medium leading-relaxed mb-3">Keep up your momentum this week!</p>
                                    <div class="space-y-2.5">
                                        <div class="flex justify-between text-[8px] font-bold text-zinc-650">
                                            <span>Progress</span>
                                            <span class="text-emerald-700 font-extrabold">8 / 10 applied</span>
                                        </div>
                                        <div class="h-1.5 w-full bg-zinc-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-emerald-500 rounded-full" style="width: 80%;"></div>
                                        </div>
                                        <div class="flex flex-wrap gap-1.5 pt-1.5 select-none">
                                            <span class="px-1 py-0.2 bg-blue-50 text-blue-700 border border-blue-100 text-[6.5px] font-bold rounded">3 applied</span>
                                            <span class="px-1 py-0.2 bg-amber-50 text-amber-700 border border-amber-100 text-[6.5px] font-bold rounded">2 interview</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Heatmap Section -->
                        <div class="bg-white rounded border border-zinc-200/60 p-3 shadow-3xs">
                            <div class="flex items-center justify-between mb-2.5">
                                <div>
                                    <span class="font-bold text-zinc-800 text-[9px] uppercase tracking-wider">Job Search Momentum</span>
                                    <p class="text-[7px] text-zinc-400 font-medium mt-0.5">Visualizing your daily application consistency over the last 12 months</p>
                                </div>
                                <div class="text-[7px] font-semibold bg-zinc-50 border border-zinc-200 px-1.5 py-0.5 rounded text-zinc-500">
                                    Avg: <strong class="text-zinc-700">1.2</strong> / day
                                </div>
                            </div>
                            
                            <!-- Heatmap Grid Mock -->
                            <div class="flex gap-[3px] overflow-hidden select-none">
                                <!-- 52 columns of static blocks representing 12 months -->
                                <div class="flex flex-col gap-[3px]">
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#216e39]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#40c463]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#30a14e]"></div>
                                </div>
                                <div class="flex flex-col gap-[3px]">
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#40c463]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#30a14e]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                </div>
                                <div class="flex flex-col gap-[3px]">
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#40c463]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#30a14e]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#216e39]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                </div>
                                <div class="flex flex-col gap-[3px]">
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#30a14e]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#216e39]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#40c463]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                </div>
                                <div class="flex flex-col gap-[3px]">
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#30a14e]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#216e39]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#40c463]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                </div>
                                <div class="flex flex-col gap-[3px]">
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#40c463]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#30a14e]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#216e39]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                </div>
                                <div class="flex flex-col gap-[3px]">
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#40c463]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#30a14e]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#216e39]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                </div>
                                <div class="flex flex-col gap-[3px]">
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#40c463]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#30a14e]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#216e39]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                </div>
                                <div class="flex flex-col gap-[3px]">
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#30a14e]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#216e39]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#40c463]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                </div>
                                <div class="flex flex-col gap-[3px]">
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#30a14e]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#216e39]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#40c463]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                </div>
                                <div class="flex flex-col gap-[3px]">
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#30a14e]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#216e39]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#40c463]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                </div>
                                <div class="flex flex-col gap-[3px]">
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#30a14e]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#216e39]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#40c463]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                </div>
                                <div class="flex flex-col gap-[3px]">
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#40c463]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#30a14e]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#216e39]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#30a14e]"></div>
                                </div>
                                <div class="flex flex-col gap-[3px]">
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#40c463]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#30a14e]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#216e39]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#30a14e]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                </div>
                                <div class="flex flex-col gap-[3px]">
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#216e39]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#40c463]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#30a14e]"></div>
                                </div>
                                <div class="flex flex-col gap-[3px]">
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#40c463]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#30a14e]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                </div>
                                <div class="flex flex-col gap-[3px]">
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#40c463]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#30a14e]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#216e39]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                </div>
                                <div class="flex flex-col gap-[3px]">
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#30a14e]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#216e39]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#40c463]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                </div>
                                <div class="flex flex-col gap-[3px]">
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#30a14e]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#216e39]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#40c463]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                </div>
                                <div class="flex flex-col gap-[3px]">
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#40c463]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#30a14e]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#216e39]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#30a14e]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                </div>
                                <div class="flex flex-col gap-[3px]">
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#40c463]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#30a14e]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#216e39]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                </div>
                                <div class="flex flex-col gap-[3px]">
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#40c463]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#30a14e]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#216e39]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                </div>
                                <div class="flex flex-col gap-[3px]">
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#30a14e]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#216e39]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#40c463]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                </div>
                                <div class="flex flex-col gap-[3px]">
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#30a14e]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#216e39]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#40c463]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                </div>
                                <div class="flex flex-col gap-[3px]">
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#30a14e]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#216e39]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#40c463]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                </div>
                                <div class="flex flex-col gap-[3px]">
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#30a14e]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#216e39]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#40c463]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-[#9be9a8]"></div>
                                    <div class="w-[7px] h-[7px] rounded-[1.5px] bg-zinc-100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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

            <!-- Notion-style Interactive Feature Grid (Left selectors, Right browser visual mockup) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch reveal mt-8">
                <!-- Left Column selectors (Notion List items) -->
                <div class="lg:col-span-4 flex flex-col justify-between space-y-2">
                    <div>
                        <span class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Custom Tools</span>
                        <h3 class="text-lg font-extrabold text-zinc-900 tracking-tight mt-1 mb-5">Automate and track your career growth.</h3>
                    </div>
                    
                    <div class="space-y-1 flex-1">
                        <!-- Kanban Board Selector -->
                        <div class="feature-selector-item p-3.5 rounded-xl border border-zinc-200/80 bg-zinc-50/50 hover:bg-zinc-50/90 cursor-pointer transition flex items-start gap-3 select-none" id="feat-item-1" onclick="switchFeature(1)">
                            <div class="w-7 h-7 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center shrink-0 text-sm">📋</div>
                            <div class="min-w-0">
                                <h4 class="font-extrabold text-zinc-800 text-xs leading-tight">Kanban Board Tracker</h4>
                                <p class="text-[10px] text-zinc-450 mt-0.5 leading-normal hidden md:block">Pantau seluruh status lamaran kerja terpusat.</p>
                            </div>
                        </div>

                        <!-- AI CV Analyzer Selector -->
                        <div class="feature-selector-item p-3.5 rounded-xl border border-zinc-200/10 bg-transparent hover:bg-zinc-50/50 cursor-pointer transition flex items-start gap-3 select-none" id="feat-item-2" onclick="switchFeature(2)">
                            <div class="w-7 h-7 rounded-lg bg-rose-50 border border-rose-100 flex items-center justify-center shrink-0 text-sm">🧠</div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-zinc-650 text-xs leading-tight">AI CV Analyzer</h4>
                                <p class="text-[10px] text-zinc-400 mt-0.5 leading-normal hidden md:block">Analisis skor ATS CV Anda instan.</p>
                            </div>
                        </div>

                        <!-- AI Cover Letter Selector -->
                        <div class="feature-selector-item p-3.5 rounded-xl border border-zinc-200/10 bg-transparent hover:bg-zinc-50/50 cursor-pointer transition flex items-start gap-3 select-none" id="feat-item-3" onclick="switchFeature(3)">
                            <div class="w-7 h-7 rounded-lg bg-amber-50 border border-amber-100 flex items-center justify-center shrink-0 text-sm">✨</div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-zinc-650 text-xs leading-tight">AI Cover Letter</h4>
                                <p class="text-[10px] text-zinc-400 mt-0.5 leading-normal hidden md:block">Buat surat lamaran kustom otomatis.</p>
                            </div>
                        </div>

                        <!-- Career Analytics Selector -->
                        <div class="feature-selector-item p-3.5 rounded-xl border border-zinc-200/10 bg-transparent hover:bg-zinc-50/50 cursor-pointer transition flex items-start gap-3 select-none" id="feat-item-4" onclick="switchFeature(4)">
                            <div class="w-7 h-7 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center shrink-0 text-sm">📈</div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-zinc-650 text-xs leading-tight">Career Analytics</h4>
                                <p class="text-[10px] text-zinc-400 mt-0.5 leading-normal hidden md:block">Pantau performa & grafik progress.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column (The visual preview card showing mockup matching active state) -->
                <div class="lg:col-span-8 flex flex-col justify-center">
                    <div class="w-full bg-[#f8fafc] border border-zinc-200 rounded-xl p-4 md:p-6 shadow-2xs relative min-h-[300px] overflow-hidden flex flex-col justify-between">
                        
                        <!-- Panel: Kanban Tracker Mock -->
                        <div id="feature-pane-1" class="feature-pane space-y-4">
                            <div class="flex items-center justify-between pb-2 border-b border-zinc-200/60">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                                    <span class="font-bold text-zinc-800 text-[10px] uppercase tracking-wider">Kanban Board Tracker</span>
                                </div>
                                <span class="text-[9px] text-zinc-400">Drag & Drop Cards</span>
                            </div>
                            
                            <div class="grid grid-cols-3 gap-3">
                                <!-- Col 1: Applied -->
                                <div class="bg-white border border-zinc-200 rounded-lg p-2.5 space-y-2">
                                    <div class="flex items-center justify-between"><span class="text-[8px] font-bold text-zinc-500 uppercase">Wishlist (2)</span></div>
                                    <div class="bg-zinc-50 border border-zinc-200/60 p-2 rounded shadow-3xs"><p class="font-bold text-zinc-800 text-[9px]">Shopee</p><p class="text-[7.5px] text-zinc-400">Product Manager</p></div>
                                    <div class="bg-zinc-50 border border-zinc-200/60 p-2 rounded shadow-3xs"><p class="font-bold text-zinc-800 text-[9px]">Gojek</p><p class="text-[7.5px] text-zinc-400">UX Researcher</p></div>
                                </div>
                                <!-- Col 2: In Process -->
                                <div class="bg-white border border-zinc-200 rounded-lg p-2.5 space-y-2">
                                    <div class="flex items-center justify-between"><span class="text-[8px] font-bold text-blue-600 uppercase">Applied (1)</span></div>
                                    <div class="bg-blue-50/30 border border-blue-100 p-2 rounded shadow-3xs"><p class="font-bold text-zinc-800 text-[9px]">Tokopedia</p><p class="text-[7.5px] text-blue-650 font-semibold">Frontend Engineer</p></div>
                                </div>
                                <!-- Col 3: Interview -->
                                <div class="bg-white border border-zinc-200 rounded-lg p-2.5 space-y-2">
                                    <div class="flex items-center justify-between"><span class="text-[8px] font-bold text-amber-600 uppercase">Interview (1)</span></div>
                                    <div class="bg-amber-50/30 border border-amber-100 p-2 rounded shadow-3xs border-l-2 border-l-amber-500"><p class="font-bold text-zinc-800 text-[9px]">Traveloka</p><p class="text-[7.5px] text-amber-650 font-semibold">UI Designer</p></div>
                                </div>
                            </div>
                        </div>

                        <!-- Panel: AI CV Analyzer -->
                        <div id="feature-pane-2" class="feature-pane hidden space-y-4">
                            <div class="flex items-center justify-between pb-2 border-b border-zinc-200/60">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                                    <span class="font-bold text-zinc-800 text-[10px] uppercase tracking-wider">AI CV Analyzer (ATS Audit)</span>
                                </div>
                                <span class="px-1.5 py-0.2 bg-emerald-50 text-emerald-700 text-[8px] font-bold rounded">92% Match</span>
                            </div>
                            <div class="bg-white border border-zinc-200 rounded-lg p-4 space-y-3">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h4 class="text-xs font-bold text-zinc-800">CV_Luthfi_Fauzi.pdf</h4>
                                        <p class="text-[8.5px] text-zinc-400">Scanned for Frontend Engineer position</p>
                                    </div>
                                    <div class="w-10 h-10 rounded-full border-4 border-emerald-500 flex items-center justify-center text-[10px] font-black text-emerald-600">85</div>
                                </div>
                                <div class="space-y-1.5 text-[8.5px] text-zinc-650">
                                    <div class="flex items-center gap-2"><span class="text-emerald-500">✓</span> <span>Mengandung keyword esensial: React, TailwindCSS, REST API.</span></div>
                                    <div class="flex items-center gap-2"><span class="text-amber-500">⚠</span> <span>Tambahkan pencapaian kuantitatif di bagian pengalaman kerja.</span></div>
                                </div>
                            </div>
                        </div>

                        <!-- Panel: AI Cover Letter -->
                        <div id="feature-pane-3" class="feature-pane hidden space-y-4">
                            <div class="flex items-center justify-between pb-2 border-b border-zinc-200/60">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                                    <span class="font-bold text-zinc-800 text-[10px] uppercase tracking-wider">AI Cover Letter Builder</span>
                                </div>
                                <span class="text-[8px] text-zinc-400">Generated in 1.4s</span>
                            </div>
                            <div class="bg-white border border-zinc-200 rounded-lg p-4 space-y-2 select-text font-serif text-[7.5px] leading-relaxed text-zinc-600 max-h-[140px] overflow-y-auto">
                                <p class="font-bold font-sans text-zinc-800">Kepada Yth. Tim Rekrutmen Gojek Indonesia,</p>
                                <p>Saya sangat antusias untuk mengajukan lamaran sebagai Product Designer. Dengan latar belakang saya di bidang desain sistem antarmuka minimalis dan pengujian kegunaan pengguna...</p>
                                <p>Melalui portofolio proyek-proyek saya sebelumnya, saya konsisten menerapkan standar visual modern yang mengedepankan efisiensi alur pengguna...</p>
                            </div>
                        </div>

                        <!-- Panel: Career Analytics -->
                        <div id="feature-pane-4" class="feature-pane hidden space-y-4">
                            <div class="flex items-center justify-between pb-2 border-b border-zinc-200/60">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                    <span class="font-bold text-zinc-800 text-[10px] uppercase tracking-wider">Career Analytics Dashboard</span>
                                </div>
                                <span class="text-[8px] text-zinc-400">Real-time stats</span>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-white border border-zinc-200 rounded-lg p-3 space-y-2">
                                    <span class="text-[8px] font-bold text-zinc-400 uppercase">Conversion Rate</span>
                                    <p class="text-lg font-black text-zinc-800">25.4%</p>
                                    <p class="text-[7.5px] text-emerald-600 font-bold">↑ 4.2% dari bulan lalu</p>
                                </div>
                                <div class="bg-white border border-zinc-200 rounded-lg p-3 space-y-2">
                                    <span class="text-[8px] font-bold text-zinc-400 uppercase">Top Hiring Channel</span>
                                    <p class="text-lg font-black text-zinc-800">LinkedIn</p>
                                    <p class="text-[7.5px] text-zinc-400 font-semibold">12 interview calls</p>
                                </div>
                            </div>
                        </div>
                    </div>
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

        // Feature Selector Switcher (Notion Style)
        function switchFeature(step) {
            const allItems = document.querySelectorAll('.feature-selector-item');
            const allPanes = document.querySelectorAll('.feature-pane');

            allItems.forEach((item, idx) => {
                const currentIdx = idx + 1;
                const h4 = item.querySelector('h4');
                const p = item.querySelector('p');
                if (currentIdx === step) {
                    item.classList.remove('border-zinc-200/10', 'bg-transparent');
                    item.classList.add('border-zinc-200/80', 'bg-zinc-50/50');
                    if (h4) h4.className = 'font-extrabold text-zinc-800 text-xs leading-tight';
                    if (p) p.className = 'text-[10px] text-zinc-450 mt-0.5 leading-normal hidden md:block';
                } else {
                    item.classList.remove('border-zinc-200/80', 'bg-zinc-50/50');
                    item.classList.add('border-zinc-200/10', 'bg-transparent');
                    if (h4) h4.className = 'font-bold text-zinc-650 text-xs leading-tight';
                    if (p) p.className = 'text-[10px] text-zinc-400 mt-0.5 leading-normal hidden md:block';
                }
            });

            allPanes.forEach((pane, idx) => {
                const currentIdx = idx + 1;
                if (currentIdx === step) {
                    pane.classList.remove('hidden');
                } else {
                    pane.classList.add('hidden');
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


