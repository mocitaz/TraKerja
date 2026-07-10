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
    <meta property="og:image" content="{{ asset('images/icon.png') }}">

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
                <a href="{{ url('/') }}" class="flex items-center gap-2 text-xs font-bold text-zinc-800 tracking-tight select-none">
                    <img src="{{ asset('images/icon.png') }}" alt="TraKerja Logo" class="w-5 h-5 object-contain" onerror="this.style.display='none';">
                    <span>TraKerja</span>
                </a>

                <div class="hidden md:flex items-center gap-1 text-xs font-semibold text-zinc-500">
                    <a href="#fitur" class="px-3 py-1.5 rounded hover:bg-zinc-100 hover:text-zinc-900 transition-colors">Features</a>
                    <a href="#pricing" class="px-3 py-1.5 rounded hover:bg-zinc-100 hover:text-zinc-900 transition-colors">Pricing</a>
                    <a href="#testimonials" class="px-3 py-1.5 rounded hover:bg-zinc-100 hover:text-zinc-900 transition-colors">Testimonials</a>
                    <a href="#faq" class="px-3 py-1.5 rounded hover:bg-zinc-100 hover:text-zinc-900 transition-colors">FAQ</a>
                </div>

                <div class="flex items-center gap-2">
                    @auth
                        <a href="{{ url('/tracker') }}" class="px-3.5 py-1.5 bg-[#0066cc] text-white rounded-lg text-xs font-bold hover:bg-[#0052a3] transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-3 py-1.5 text-zinc-600 rounded-lg text-xs font-bold hover:bg-zinc-100 transition">Log in</a>
                        <a href="{{ route('register') }}" class="px-3.5 py-1.5 bg-[#0066cc] text-white rounded-lg text-xs font-bold hover:bg-[#0052a3] transition shadow-xs">Get Started</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- ============ HERO ============ --}}
    <section class="relative pt-28 pb-20 sm:pt-36 sm:pb-28 overflow-hidden border-b border-zinc-100 bg-white">
        <div class="max-w-5xl mx-auto px-4 text-center">
            
            <!-- Notion-style line-art branding icons -->
            <div class="flex justify-center -space-x-3 mb-8 reveal">
                <!-- User/Detect (Blue circle) -->
                <div class="w-11 h-11 rounded-full border-2 border-[#0066cc] bg-white flex items-center justify-center shadow-3xs shrink-0 select-none z-30">
                    <svg class="w-5.5 h-5.5 text-zinc-800" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
                <!-- Portfolio/Briefcase (White circle, black border) -->
                <div class="w-11 h-11 rounded-full border-2 border-zinc-950 bg-white flex items-center justify-center shadow-3xs shrink-0 select-none z-20">
                    <svg class="w-5.5 h-5.5 text-zinc-800" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                    </svg>
                </div>
                <!-- Target (Red circle) -->
                <div class="w-11 h-11 rounded-full border-2 border-red-500 bg-white flex items-center justify-center shadow-3xs shrink-0 select-none z-10">
                    <svg class="w-5.5 h-5.5 text-zinc-800" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <circle cx="12" cy="12" r="6"></circle>
                        <circle cx="12" cy="12" r="2"></circle>
                    </svg>
                </div>
                <!-- Idea/Brain (Amber circle) -->
                <div class="w-11 h-11 rounded-full border-2 border-amber-500 bg-white flex items-center justify-center shadow-3xs shrink-0 select-none z-0">
                    <svg class="w-5.5 h-5.5 text-zinc-800" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9.663 17h4.673M12 3v1m6.364 1.364l-.707.707M21 12h-1M4 12H3m3.364-5.636l-.707-.707M12 21v-1m0-12a4 4 0 1 0 0 8h.01"></path>
                    </svg>
                </div>
            </div>

            <h1 class="notion-h1 text-5xl sm:text-7xl text-zinc-950 mb-8 leading-[1.1] tracking-tight reveal">
                Where job seekers & careers <br class="hidden sm:inline">
                <span class="inline-block mt-5 px-6 py-2.5 bg-amber-50 rounded-full border border-amber-200 text-zinc-900 select-none shadow-3xs">
                    Grow together.
                </span>
            </h1>

            <p class="text-base sm:text-lg text-zinc-500 max-w-2xl mx-auto mb-10 font-medium leading-relaxed reveal">
                A unified, minimalist workspace to manage your entire job application pipeline, build ATS-optimized resumes, and accelerate your career using AI.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3.5 mb-12 reveal">
                @auth
                    <a href="{{ url('/tracker') }}" class="px-6 py-3 bg-[#0066cc] hover:bg-[#0052a3] text-white rounded-lg text-base font-bold transition shadow-xs w-full sm:w-auto">Open Dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="px-6 py-3 bg-[#0066cc] hover:bg-[#0052a3] text-white rounded-lg text-base font-bold transition shadow-xs w-full sm:w-auto">Get TraKerja Free</a>
                    <a href="#fitur" class="px-6 py-3 bg-blue-50/40 text-[#0066cc] border border-blue-100/70 rounded-lg text-base font-bold hover:bg-blue-50/80 transition w-full sm:w-auto">Explore Features</a>
                @endauth
            </div>

            <p class="text-xs text-zinc-400 font-bold uppercase tracking-wider reveal">
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
                                        Welcome back, <span class="font-bold text-zinc-650">amib</span>! Here is your career momentum and tracking progress.
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
                <h2 class="notion-h1 text-3xl sm:text-4xl text-zinc-900 mt-3">Everything you need to win your dream career.</h2>
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
                        <div class="feature-selector-item p-3.5 rounded-xl border border-zinc-200/80 bg-zinc-50/50 hover:bg-zinc-50/90 cursor-pointer transition flex items-start gap-3.5 select-none" id="feat-item-1" onclick="switchFeature(1)">
                            <div class="w-9 h-9 rounded-full border border-zinc-950 bg-white flex items-center justify-center shrink-0 shadow-3xs text-zinc-900">
                                <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-extrabold text-zinc-800 text-xs leading-tight">Kanban Board Tracker</h4>
                                <p class="text-[10px] text-zinc-450 mt-0.5 leading-normal hidden md:block">Monitor all application statuses in one centralized board.</p>
                            </div>
                        </div>

                        <!-- AI CV Analyzer Selector -->
                        <div class="feature-selector-item p-3.5 rounded-xl border border-zinc-200/10 bg-transparent hover:bg-zinc-50/50 cursor-pointer transition flex items-start gap-3.5 select-none" id="feat-item-2" onclick="switchFeature(2)">
                            <div class="w-9 h-9 rounded-full border border-blue-600 bg-white flex items-center justify-center shrink-0 shadow-3xs text-zinc-900">
                                <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-zinc-650 text-xs leading-tight">AI CV Analyzer</h4>
                                <p class="text-[10px] text-zinc-400 mt-0.5 leading-normal hidden md:block">Audit and check your resume ATS compatibility score instantly.</p>
                            </div>
                        </div>

                        <!-- AI Cover Letter Selector -->
                        <div class="feature-selector-item p-3.5 rounded-xl border border-zinc-200/10 bg-transparent hover:bg-zinc-50/50 cursor-pointer transition flex items-start gap-3.5 select-none" id="feat-item-3" onclick="switchFeature(3)">
                            <div class="w-9 h-9 rounded-full border border-red-500 bg-white flex items-center justify-center shrink-0 shadow-3xs text-zinc-900">
                                <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-zinc-650 text-xs leading-tight">AI Cover Letter</h4>
                                <p class="text-[10px] text-zinc-400 mt-0.5 leading-normal hidden md:block">Generate tailored, custom cover letters automatically.</p>
                            </div>
                        </div>

                        <!-- Career Analytics Selector -->
                        <div class="feature-selector-item p-3.5 rounded-xl border border-zinc-200/10 bg-transparent hover:bg-zinc-50/50 cursor-pointer transition flex items-start gap-3.5 select-none" id="feat-item-4" onclick="switchFeature(4)">
                            <div class="w-9 h-9 rounded-full border border-amber-500 bg-white flex items-center justify-center shrink-0 shadow-3xs text-zinc-900">
                                <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 3v18h18"></path>
                                    <path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"></path>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-zinc-650 text-xs leading-tight">Career Analytics</h4>
                                <p class="text-[10px] text-zinc-400 mt-0.5 leading-normal hidden md:block">Track interview conversion rates and job search statistics.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column (The visual preview card showing mockup matching active state) -->
                <div class="lg:col-span-8 flex flex-col justify-center">
                    <div class="w-full bg-[#f8fafc] border border-zinc-200 rounded-xl p-4 md:p-6 shadow-2xs relative min-h-[300px] overflow-hidden flex flex-col justify-between">
                        
                        <!-- Panel: Kanban Tracker Mock -->
                        <div id="feature-pane-1" class="feature-pane space-y-4 h-full flex flex-col justify-between">
                            <div class="flex items-center justify-between pb-2 border-b border-zinc-200/60 select-none shrink-0">
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 bg-blue-500 rounded-full"></div>
                                    <span class="font-extrabold text-zinc-800 text-[10px] uppercase tracking-wider">Kanban Board Tracker</span>
                                </div>
                                <span class="text-[8.5px] text-zinc-400 font-bold flex items-center gap-1">
                                    <i class="ph ph-hand-grabbing text-xs"></i> Drag & Drop Cards
                                </span>
                            </div>
                            
                            <!-- 3-Column Kanban Board matching livewire/job-kanban-board.blade.php -->
                            <div class="overflow-x-auto -mx-4 px-4 scrollbar-thin">
                                <div class="grid grid-cols-3 gap-4 select-none items-stretch flex-1 mt-2.5 min-w-[640px] md:min-w-0">
                                <!-- Col 1: WISHLIST -->
                                <div class="flex flex-col bg-zinc-50/40 rounded-lg p-2.5 border border-zinc-200/50 justify-between min-h-[220px]">
                                    <div>
                                        <div class="flex items-center justify-between mb-2.5 px-0.5">
                                            <div class="flex items-center gap-1.5">
                                                <div class="w-1.5 h-1.5 rounded-full bg-zinc-400"></div>
                                                <h3 class="text-[9px] font-bold text-zinc-650 uppercase tracking-widest">WISHLIST</h3>
                                            </div>
                                            <span class="text-[8px] font-bold bg-white text-zinc-400 px-1.5 py-0.2 rounded border border-zinc-200/60 shadow-3xs">2</span>
                                        </div>
                                        
                                        <div class="space-y-2">
                                            <!-- Shopee Card -->
                                            <div class="group bg-white rounded-md p-2.5 border border-zinc-200 shadow-3xs hover:border-zinc-300 transition-all cursor-grab">
                                                <div class="flex items-start justify-between gap-2 mb-2">
                                                    <div class="flex items-center gap-2 min-w-0">
                                                        <div class="w-6.5 h-6.5 rounded bg-zinc-50 border border-zinc-200/50 flex items-center justify-center text-zinc-500 font-bold text-[9px] shrink-0 shadow-3xs">S</div>
                                                        <div class="min-w-0">
                                                            <h4 class="text-[10px] font-bold text-zinc-800 leading-tight truncate">Shopee</h4>
                                                            <p class="text-[8.5px] font-semibold text-zinc-500 mt-0.5 truncate">Product Manager</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center justify-between pt-2 border-t border-zinc-150/80">
                                                    <div class="flex items-center gap-1">
                                                        <span class="w-4 h-4 flex items-center justify-center text-zinc-400 hover:text-zinc-600 border border-zinc-200 rounded text-[9px]"><i class="ph ph-pencil-simple"></i></span>
                                                        <span class="w-4 h-4 flex items-center justify-center text-zinc-400 hover:text-rose-600 border border-zinc-200 rounded text-[9px]"><i class="ph ph-trash"></i></span>
                                                    </div>
                                                    <div class="flex items-center gap-1 px-1.5 py-0.5 bg-zinc-50 rounded border border-zinc-200 text-[8px] text-zinc-500 font-bold">
                                                        <i class="ph ph-calendar"></i>
                                                        <span>10 Jul</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Gojek Card -->
                                            <div class="group bg-white rounded-md p-2.5 border border-zinc-200 shadow-3xs hover:border-zinc-300 transition-all cursor-grab">
                                                <div class="flex items-start justify-between gap-2 mb-2">
                                                    <div class="flex items-center gap-2 min-w-0">
                                                        <div class="w-6.5 h-6.5 rounded bg-zinc-50 border border-zinc-200/50 flex items-center justify-center text-zinc-500 font-bold text-[9px] shrink-0 shadow-3xs">G</div>
                                                        <div class="min-w-0">
                                                            <h4 class="text-[10px] font-bold text-zinc-800 leading-tight truncate">Gojek</h4>
                                                            <p class="text-[8.5px] font-semibold text-zinc-500 mt-0.5 truncate">UX Researcher</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center justify-between pt-2 border-t border-zinc-150/80">
                                                    <div class="flex items-center gap-1">
                                                        <span class="w-4 h-4 flex items-center justify-center text-zinc-400 hover:text-zinc-600 border border-zinc-200 rounded text-[9px]"><i class="ph ph-pencil-simple"></i></span>
                                                        <span class="w-4 h-4 flex items-center justify-center text-zinc-400 hover:text-rose-600 border border-zinc-200 rounded text-[9px]"><i class="ph ph-trash"></i></span>
                                                    </div>
                                                    <div class="flex items-center gap-1 px-1.5 py-0.5 bg-zinc-50 rounded border border-zinc-200 text-[8px] text-zinc-500 font-bold">
                                                        <i class="ph ph-calendar"></i>
                                                        <span>09 Jul</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Col 2: APPLIED -->
                                <div class="flex flex-col bg-zinc-50/40 rounded-lg p-2.5 border border-zinc-200/50 justify-between min-h-[220px]">
                                    <div>
                                        <div class="flex items-center justify-between mb-2.5 px-0.5">
                                            <div class="flex items-center gap-1.5">
                                                <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div>
                                                <h3 class="text-[9px] font-bold text-zinc-650 uppercase tracking-widest">APPLIED</h3>
                                            </div>
                                            <span class="text-[8px] font-bold bg-white text-zinc-400 px-1.5 py-0.2 rounded border border-zinc-200/60 shadow-3xs">1</span>
                                        </div>
                                        
                                        <div class="space-y-2">
                                            <!-- Tokopedia Card -->
                                            <div class="group bg-white rounded-md p-2.5 border border-zinc-200 shadow-3xs hover:border-zinc-300 transition-all cursor-grab border-l-2 border-l-blue-500">
                                                <div class="flex items-start justify-between gap-2 mb-2">
                                                    <div class="flex items-center gap-2 min-w-0">
                                                        <div class="w-6.5 h-6.5 rounded bg-zinc-50 border border-zinc-200/50 flex items-center justify-center text-zinc-500 font-bold text-[9px] shrink-0 shadow-3xs">T</div>
                                                        <div class="min-w-0">
                                                            <h4 class="text-[10px] font-bold text-zinc-800 leading-tight truncate">Tokopedia</h4>
                                                            <p class="text-[8.5px] font-semibold text-zinc-500 mt-0.5 truncate">Frontend Engineer</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center justify-between pt-2 border-t border-zinc-150/80">
                                                    <div class="flex items-center gap-1">
                                                        <span class="w-4 h-4 flex items-center justify-center text-zinc-400 hover:text-zinc-600 border border-zinc-200 rounded text-[9px]"><i class="ph ph-pencil-simple"></i></span>
                                                        <span class="w-4 h-4 flex items-center justify-center text-zinc-400 hover:text-rose-600 border border-zinc-200 rounded text-[9px]"><i class="ph ph-trash"></i></span>
                                                    </div>
                                                    <div class="flex items-center gap-1 px-1.5 py-0.5 bg-zinc-50 rounded border border-zinc-200 text-[8px] text-zinc-500 font-bold">
                                                        <i class="ph ph-calendar"></i>
                                                        <span>08 Jul</span>
                                                    </div>
                                                </div>
                                                <div class="mt-2 flex items-center">
                                                    <span class="px-1.5 py-0.5 rounded text-[8px] font-bold uppercase bg-blue-50 text-blue-600 border border-blue-100">Applied</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Col 3: INTERVIEW -->
                                <div class="flex flex-col bg-zinc-50/40 rounded-lg p-2.5 border border-zinc-200/50 justify-between min-h-[220px]">
                                    <div>
                                        <div class="flex items-center justify-between mb-2.5 px-0.5">
                                            <div class="flex items-center gap-1.5">
                                                <div class="w-1.5 h-1.5 rounded-full bg-amber-500"></div>
                                                <h3 class="text-[9px] font-bold text-zinc-650 uppercase tracking-widest">INTERVIEW</h3>
                                            </div>
                                            <span class="text-[8px] font-bold bg-white text-zinc-400 px-1.5 py-0.2 rounded border border-zinc-200/60 shadow-3xs">1</span>
                                        </div>
                                        
                                        <div class="space-y-2">
                                            <!-- Traveloka Card -->
                                            <div class="group bg-white rounded-md p-2.5 border border-zinc-200 shadow-3xs hover:border-zinc-300 transition-all cursor-grab border-l-2 border-l-amber-500">
                                                <div class="flex items-start justify-between gap-2 mb-2">
                                                    <div class="flex items-center gap-2 min-w-0">
                                                        <div class="w-6.5 h-6.5 rounded bg-zinc-50 border border-zinc-200/50 flex items-center justify-center text-zinc-500 font-bold text-[9px] shrink-0 shadow-3xs">T</div>
                                                        <div class="min-w-0">
                                                            <h4 class="text-[10px] font-bold text-zinc-800 leading-tight truncate">Traveloka</h4>
                                                            <p class="text-[8.5px] font-semibold text-zinc-500 mt-0.5 truncate">UI Designer</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center justify-between pt-2 border-t border-zinc-150/80">
                                                    <div class="flex items-center gap-1">
                                                        <span class="w-4 h-4 flex items-center justify-center text-zinc-400 hover:text-zinc-600 border border-zinc-200 rounded text-[9px]"><i class="ph ph-pencil-simple"></i></span>
                                                        <span class="w-4 h-4 flex items-center justify-center text-zinc-400 hover:text-rose-600 border border-zinc-200 rounded text-[9px]"><i class="ph ph-trash"></i></span>
                                                    </div>
                                                    <div class="flex items-center gap-1 px-1.5 py-0.5 bg-zinc-50 rounded border border-zinc-200 text-[8px] text-zinc-500 font-bold">
                                                        <i class="ph ph-calendar"></i>
                                                        <span>07 Jul</span>
                                                    </div>
                                                </div>
                                                <div class="mt-2 flex items-center">
                                                    <span class="px-1.5 py-0.5 rounded text-[8px] font-bold uppercase bg-amber-50 text-amber-600 border border-amber-100">HR - Interview</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Panel: AI CV Analyzer -->
                        <div id="feature-pane-2" class="feature-pane hidden space-y-4 h-full flex flex-col justify-between">
                            <div class="flex items-center justify-between pb-2 border-b border-zinc-200/60 select-none shrink-0">
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 bg-purple-500 rounded-full"></div>
                                    <span class="font-extrabold text-zinc-800 text-[10px] uppercase tracking-wider">AI CV Analyzer (ATS Audit)</span>
                                </div>
                                <span class="px-1.5 py-0.5 bg-purple-50 text-purple-700 text-[7px] font-extrabold rounded border border-purple-100/60 uppercase">Active Thread</span>
                            </div>
                            
                            <!-- Conversational Response Box resembling ai-analyzer/result.blade.php -->
                            <div class="bg-white border border-zinc-200 rounded-lg p-3.5 shadow-3xs flex-1 mt-2.5 space-y-3 flex flex-col justify-between">
                                <div class="flex items-center gap-2 text-[8px] text-zinc-400 font-mono">
                                    <span class="font-bold text-purple-650 flex items-center gap-1"><i class="ph ph-sparkle text-xs"></i> TraKerja AI</span>
                                    <span>•</span>
                                    <span>Just Now</span>
                                </div>
                                
                                <p class="text-[9px] text-zinc-550 leading-relaxed">Berikut adalah ringkasan skor dan executive summary kecocokan profil Anda:</p>
                                
                                <!-- Executive Summary Bento Layout -->
                                <div class="grid grid-cols-4 gap-3 bg-zinc-50/50 p-2 border border-zinc-150 rounded-lg">
                                    <!-- Score Circle -->
                                    <div class="col-span-1 bg-white border border-zinc-200 rounded-md p-1.5 flex flex-col items-center justify-center select-none">
                                        <div class="relative w-10 h-10 mb-1">
                                            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                                                <circle class="text-zinc-100" stroke-width="10" stroke="currentColor" fill="none" r="40" cx="50" cy="50" />
                                                <circle class="text-zinc-900" stroke-width="10" stroke-dasharray="251.2" stroke-dashoffset="37.68" stroke-linecap="round" stroke="currentColor" fill="none" r="40" cx="50" cy="50" />
                                            </svg>
                                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                                <span class="text-[9px] font-black text-zinc-900 leading-none">85<span class="text-[6px] font-bold text-zinc-500">%</span></span>
                                            </div>
                                        </div>
                                        <span class="px-1 py-0.2 bg-emerald-50 text-emerald-700 text-[6px] font-bold uppercase rounded border border-emerald-100">Strong</span>
                                    </div>

                                    <!-- Executive Summary Text -->
                                    <div class="col-span-3 bg-zinc-900 text-white rounded-md p-2 flex flex-col justify-center">
                                        <div class="flex items-center gap-1 mb-1">
                                            <i class="ph ph-lightning text-amber-400 text-[10px]"></i>
                                            <span class="text-[6px] font-bold text-zinc-400 uppercase tracking-wider">Executive Summary</span>
                                        </div>
                                        <p class="text-[8px] font-medium text-zinc-200 leading-normal italic">
                                            "Your technical background is impressive. Focus on quantifying your impact with metrics..."
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Panel: AI Cover Letter -->
                        <div id="feature-pane-3" class="feature-pane hidden space-y-4 h-full flex flex-col justify-between">
                            <div class="flex items-center justify-between pb-2 border-b border-zinc-200/60 select-none shrink-0">
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 bg-amber-500 rounded-full"></div>
                                    <span class="font-extrabold text-zinc-800 text-[10px] uppercase tracking-wider">AI Cover Letter Builder</span>
                                </div>
                                <span class="text-[8px] text-zinc-400 font-semibold">Generated in 1.4s</span>
                            </div>
                            
                            <div class="bg-white border border-zinc-200 rounded-lg p-3.5 shadow-3xs flex-1 mt-2.5 flex flex-col justify-between">
                                <div class="border-b border-zinc-150/60 pb-2 mb-2 flex items-center justify-between">
                                    <div>
                                        <h4 class="text-[10px] font-bold text-zinc-800 leading-none">Cover Letter - Gojek</h4>
                                        <p class="text-[8px] text-zinc-450 mt-1 font-semibold flex items-center gap-1"><i class="ph ph-buildings"></i> PT GoTo Gojek Tokopedia</p>
                                    </div>
                                    <span class="px-1.5 py-0.2 bg-zinc-50 border border-zinc-200 rounded text-[7.5px] font-bold text-zinc-500">Professional Tone</span>
                                </div>
                                <div class="bg-zinc-50/50 border border-zinc-200 rounded p-2.5 select-text font-serif text-[7.5px] leading-relaxed text-zinc-650 max-h-[105px] overflow-y-auto">
                                    <p class="font-bold font-sans text-zinc-800 mb-1">Kepada Yth. Tim Rekrutmen Gojek Indonesia,</p>
                                    <p class="mb-1">Saya sangat antusias untuk mengajukan lamaran sebagai Frontend Engineer. Dengan latar belakang saya di bidang pengembangan antarmuka web minimalis...</p>
                                    <p>Saya terbiasa berkolaborasi erat dengan tim produk menggunakan React dan TailwindCSS guna mewujudkan fungsionalitas UI yang konsisten dan responsif...</p>
                                </div>
                            </div>
                        </div>

                        <!-- Panel: Career Analytics -->
                        <div id="feature-pane-4" class="feature-pane hidden space-y-3.5 h-full flex flex-col justify-between">
                            <div class="flex items-center justify-between pb-2 border-b border-zinc-200/60 select-none shrink-0">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-5 h-5 bg-zinc-100 border border-zinc-200/60 rounded flex items-center justify-center text-zinc-500 shrink-0">
                                        <i class="ph ph-chart-line-up text-xs"></i>
                                    </div>
                                    <div>
                                        <span class="font-extrabold text-zinc-800 text-[10px] uppercase tracking-wider">Analytics Summary</span>
                                    </div>
                                </div>
                                <span class="px-1.5 py-0.5 bg-primary-50 text-zinc-800 text-[8px] font-bold rounded border border-primary-100/60 uppercase">Overview</span>
                            </div>
                            
                            <!-- Main Layout split matching summary/index.blade.php -->
                            <div class="flex-1 mt-2 select-none overflow-y-auto max-h-[190px] space-y-3 pr-1">
                                <!-- Top Stats Cards -->
                                <div class="grid grid-cols-3 gap-2">
                                    <div class="bg-white rounded border border-zinc-200/60 p-2 flex items-center justify-between shadow-3xs">
                                        <div class="min-w-0">
                                            <p class="text-[7px] font-bold text-zinc-400 uppercase tracking-widest leading-none truncate">Active Apps</p>
                                            <p class="text-[12px] font-black text-zinc-800 mt-1">12</p>
                                        </div>
                                    </div>
                                    <div class="bg-white rounded border border-zinc-200/60 p-2 flex items-center justify-between shadow-3xs">
                                        <div class="min-w-0">
                                            <p class="text-[7px] font-bold text-zinc-400 uppercase tracking-widest leading-none truncate">Offers</p>
                                            <p class="text-[12px] font-black text-zinc-800 mt-1">3</p>
                                        </div>
                                    </div>
                                    <div class="bg-white rounded border border-zinc-200/60 p-2 flex items-center justify-between shadow-3xs">
                                        <div class="min-w-0">
                                            <p class="text-[7px] font-bold text-zinc-400 uppercase tracking-widest leading-none truncate">Interviews</p>
                                            <p class="text-[12px] font-black text-zinc-800 mt-1">6</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Highlights Row (Streak & Weekly Goal) -->
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="bg-white border border-zinc-200/60 rounded p-2 flex items-center gap-2 shadow-3xs">
                                        <div class="w-6 h-6 bg-orange-50/50 rounded flex items-center justify-center text-orange-600 shrink-0"><i class="ph ph-fire text-xs"></i></div>
                                        <div class="flex flex-col">
                                            <span class="text-[6.5px] font-bold text-zinc-400 uppercase tracking-widest leading-none mb-0.5">Daily Streak</span>
                                            <span class="text-[10px] font-bold text-zinc-800 leading-none">5 <span class="text-[6.5px] font-bold text-zinc-400">/ Best 14</span></span>
                                        </div>
                                    </div>
                                    <div class="bg-white border border-zinc-200/60 rounded p-2 flex items-center gap-2 shadow-3xs">
                                        <div class="w-6 h-6 bg-primary-50/50 rounded flex items-center justify-center text-primary-650 shrink-0"><i class="ph ph-target text-xs"></i></div>
                                        <div class="flex flex-col">
                                            <span class="text-[6.5px] font-bold text-zinc-400 uppercase tracking-widest leading-none mb-0.5">Weekly Goal</span>
                                            <span class="text-[10px] font-bold text-zinc-800 leading-none">4 <span class="text-[6.5px] font-bold text-zinc-400">/ 10</span></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Simulated Timeline Chart Area -->
                                <div class="bg-white border border-zinc-200/60 rounded p-2 shadow-3xs">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-[7.5px] font-bold text-zinc-500 uppercase tracking-wider flex items-center gap-1"><i class="ph ph-chart-line text-[10px]"></i> Timeline Activity</span>
                                        <span class="text-[6.5px] text-zinc-400 font-semibold">weekly trend</span>
                                    </div>
                                    <!-- Simple static mockup line graph SVG -->
                                    <div class="h-14 flex items-end justify-between px-1 relative">
                                        <svg class="absolute inset-0 w-full h-full" viewBox="0 0 100 30" preserveAspectRatio="none">
                                            <!-- Chart Line -->
                                            <path d="M 5,25 Q 25,5 45,18 T 85,10 T 100,5" fill="none" stroke="#4f46e5" stroke-width="1.5"></path>
                                            <path d="M 5,25 Q 25,5 45,18 T 85,10 T 100,5 L 100,30 L 5,30 Z" fill="rgba(79, 70, 229, 0.04)"></path>
                                            
                                            <path d="M 5,28 Q 30,15 55,25 T 85,22 T 100,18" fill="none" stroke="#10b981" stroke-width="1.5"></path>
                                            <path d="M 5,28 Q 30,15 55,25 T 85,22 T 100,18 L 100,30 L 5,30 Z" fill="rgba(16, 185, 129, 0.02)"></path>
                                        </svg>
                                        <div class="w-full flex justify-between text-[6.5px] text-zinc-400 font-bold mt-auto pt-14 border-t border-zinc-100 z-10 select-none">
                                            <span>W1</span><span>W2</span><span>W3</span><span>W4</span><span>W5</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ HOW IT WORKS (Notion interactive switcher) ============ --}}
    <section class="py-20 sm:py-24 bg-white border-b border-zinc-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-14 reveal">
                <h2 class="notion-h1 text-3xl sm:text-4xl text-zinc-900">Start in 3 simple steps.</h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                <!-- Selectors -->
                <div class="lg:col-span-5 space-y-4 reveal">
                    <div class="how-it-works-item bento-card overflow-hidden cursor-pointer border border-zinc-200/80 bg-zinc-50/50 hover:bg-zinc-50 transition-all duration-350" id="hiw-item-1" onclick="toggleHowItWorks(1)">
                        <div class="p-5 flex items-start gap-4">
                            <!-- Notion Settings/Gear icon -->
                            <div class="w-9 h-9 rounded-full border border-blue-600 bg-white flex items-center justify-center shrink-0 shadow-3xs text-zinc-900">
                                <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-zinc-900 text-xs tracking-tight">Register & Set Career Profile</h3>
                                <p class="text-[10px] font-semibold text-zinc-500 mt-1 leading-relaxed">Fill out your profile once. This information is automatically used to customize application documents.</p>
                            </div>
                        </div>
                    </div>

                    <div class="how-it-works-item bento-card overflow-hidden cursor-pointer border border-zinc-200/40 bg-white hover:bg-zinc-50 transition-all duration-350" id="hiw-item-2" onclick="toggleHowItWorks(2)">
                        <div class="p-5 flex items-start gap-4">
                            <!-- Notion Calendar icon -->
                            <div class="w-9 h-9 rounded-full border border-red-500 bg-white flex items-center justify-center shrink-0 shadow-3xs text-zinc-900">
                                <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-zinc-700 text-xs tracking-tight">Track Job Applications</h3>
                                <p class="text-[10px] font-semibold text-zinc-400 mt-1 leading-relaxed">Add applications manually or via PDF upload. Monitor interview status and job offers easily.</p>
                            </div>
                        </div>
                    </div>

                    <div class="how-it-works-item bento-card overflow-hidden cursor-pointer border border-zinc-200/40 bg-white hover:bg-zinc-50 transition-all duration-350" id="hiw-item-3" onclick="toggleHowItWorks(3)">
                        <div class="p-5 flex items-start gap-4">
                            <!-- Notion Shield/Verified check icon -->
                            <div class="w-9 h-9 rounded-full border border-amber-500 bg-white flex items-center justify-center shrink-0 shadow-3xs text-zinc-900">
                                <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                    <path d="m9 11 2 2 4-4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-zinc-700 text-xs tracking-tight">Optimize & Win Your Dream Job</h3>
                                <p class="text-[10px] font-semibold text-zinc-400 mt-1 leading-relaxed">Use career analytics, AI CV Analyzer, and Cover Letter Generator to accelerate your path.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview Display (Apple Macbook Mockup) -->
                <div class="lg:col-span-7 relative flex flex-col items-center justify-center reveal py-4">
                    <div class="relative w-full max-w-[580px] mx-auto pl-4 lg:pl-10">
                        <!-- MacBook Screen/Lid Frame -->
                        <div class="relative rounded-t-2xl border-4 border-zinc-950 bg-zinc-950 p-[5px] shadow-2xl aspect-[16/10] overflow-hidden">
                            <!-- Internal Screen Container -->
                            <div class="relative w-full h-full rounded-t-xl overflow-hidden bg-white border border-zinc-900">
                                <img id="how-it-works-img-1" src="{{ asset('images/sslogin.png') }}" alt="Register & setup profile" class="how-it-works-image absolute inset-0 w-full h-full object-cover object-top rounded-t-xl transition-opacity duration-300" style="opacity:1;">
                                <img id="how-it-works-img-2" src="{{ asset('images/sstrack.png') }}" alt="Manage applications" class="how-it-works-image absolute inset-0 w-full h-full object-cover object-top rounded-t-xl transition-opacity duration-300 hidden opacity-0">
                                <img id="how-it-works-img-3" src="{{ asset('images/ssoptimasi.png') }}" alt="Analyze & optimize" class="how-it-works-image absolute inset-0 w-full h-full object-cover object-top rounded-t-xl transition-opacity duration-300 hidden opacity-0">
                            </div>
                        </div>
                        <!-- MacBook Base/Keyboard Lip -->
                        <div class="relative -mt-0.5 w-[114%] -ml-[7%] h-3 bg-zinc-300 border-t border-zinc-200 rounded-b-xl shadow-xl flex items-center justify-center z-10">
                            <!-- Display Opening Notch -->
                            <div class="w-16 h-1.5 bg-zinc-450 rounded-b-md -mt-1 shadow-inner"></div>
                        </div>
                        <!-- MacBook Shadow/Base feet -->
                        <div class="w-[84%] mx-auto h-2 bg-zinc-950/20 rounded-full blur-sm"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ PRICING ============ --}}
    <section class="py-20 sm:py-24 bg-zinc-50/50 border-b border-zinc-100" id="pricing">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-14 reveal">
                <h2 class="notion-h1 text-3xl sm:text-4xl text-zinc-900 mt-3">Simple pricing. No hidden fees.</h2>
            </div>

            <!-- Pricing Grid (3 columns) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch reveal">
                
                <!-- Free Tier -->
                <div class="bento-card p-6 flex flex-col justify-between transition-all bg-white hover:border-zinc-300">
                    <div>
                        <div class="flex items-center justify-between">
                            <h3 class="font-extrabold text-zinc-400 text-[10px] uppercase tracking-widest">Free Standard</h3>
                            <span class="px-2 py-0.5 bg-zinc-100 text-zinc-600 text-[8px] font-bold rounded uppercase">Free</span>
                        </div>
                        <p class="text-3xl font-black text-zinc-950 mt-4 mb-1">Free</p>
                        <p class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-6">Lifetime Access</p>
                        
                        <div class="border-t border-zinc-200/70 pt-6 space-y-3.5 text-xs font-semibold text-zinc-650">
                            <div class="flex items-center gap-2"><i class="ph ph-check text-emerald-500 text-sm font-bold"></i> <span>Up to 25 job trackers</span></div>
                            <div class="flex items-center gap-2"><i class="ph ph-check text-emerald-500 text-sm font-bold"></i> <span>2 standard ATS templates</span></div>
                            <div class="flex items-center gap-2"><i class="ph ph-check text-emerald-500 text-sm font-bold"></i> <span>1 free AI Resume credit</span></div>
                            <div class="flex items-center gap-2"><i class="ph ph-check text-emerald-500 text-sm font-bold"></i> <span>3 Cover Letter credits</span></div>
                        </div>
                    </div>

                    <a href="{{ route('register') }}" class="w-full mt-8 py-2.5 bg-zinc-50 hover:bg-zinc-100 text-zinc-800 border border-zinc-250 rounded-lg text-xs font-bold transition text-center shadow-3xs">Get Started Free</a>
                </div>

                <!-- Premium Tier -->
                <div class="bento-card p-6 flex flex-col justify-between relative border-zinc-350 shadow-xs hover:border-zinc-900/40 transition-all bg-[#fafafa]">
                    <span class="absolute -top-3 left-6 bg-zinc-900 text-white text-[8px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider select-none">Best Value</span>
                    <div>
                        <div class="flex items-center justify-between mt-1">
                            <h3 class="font-extrabold text-zinc-900 text-[10px] uppercase tracking-widest">Premium Pro</h3>
                            <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[8px] font-bold rounded border border-emerald-100 uppercase">Pro</span>
                        </div>
                        <p class="text-3xl font-black text-zinc-950 mt-4 mb-1">Rp 19.999</p>
                        <p class="text-[9px] font-bold text-emerald-600 uppercase tracking-widest mb-6">One-time, lifetime access</p>

                        <div class="border-t border-zinc-200/70 pt-6 space-y-3.5 text-xs font-semibold text-zinc-700">
                            <div class="flex items-center gap-2 font-bold"><i class="ph ph-check text-emerald-500 text-sm font-bold"></i> <span>Unlimited job trackers</span></div>
                            <div class="flex items-center gap-2 font-bold"><i class="ph ph-check text-emerald-500 text-sm font-bold"></i> <span>50+ premium templates</span></div>
                            <div class="flex items-center gap-2"><i class="ph ph-check text-emerald-500 text-sm font-bold"></i> <span>Bulk job importer (.csv)</span></div>
                            <div class="flex items-center gap-2"><i class="ph ph-check text-emerald-500 text-sm font-bold"></i> <span>Full advanced dashboard</span></div>
                        </div>
                    </div>

                    <a href="{{ route('payment.index') }}" class="w-full mt-8 py-2.5 bg-[#0066cc] hover:bg-[#0052a3] text-white rounded-lg text-xs font-bold transition text-center shadow-sm">Upgrade to Premium Pro</a>
                </div>

                <!-- Add-On Pack -->
                <div class="bento-card p-6 flex flex-col justify-between transition-all bg-white hover:border-zinc-300 border-zinc-200">
                    <div>
                        <div class="flex items-center justify-between">
                            <h3 class="font-extrabold text-zinc-400 text-[10px] uppercase tracking-widest">Top Up Add-On</h3>
                            <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-[8px] font-bold rounded uppercase border border-blue-100">Add-On</span>
                        </div>
                        <p class="text-3xl font-black text-zinc-950 mt-4 mb-1">Rp 14.999</p>
                        <p class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-6">Kredit Langsung Aktif</p>
                        
                        <div class="border-t border-zinc-200/70 pt-6 space-y-3.5 text-xs font-semibold text-zinc-650">
                            <div class="flex items-center gap-2"><i class="ph ph-check text-emerald-500 text-sm font-bold"></i> <span>10 Kredit AI Analyzer</span></div>
                            <div class="flex items-center gap-2"><i class="ph ph-check text-emerald-500 text-sm font-bold"></i> <span>15 Kredit Cover Letter</span></div>
                            <div class="flex items-center gap-2"><i class="ph ph-check text-emerald-500 text-sm font-bold"></i> <span>5 Kredit AI Photo Studio</span></div>
                            <div class="flex items-center gap-2"><i class="ph ph-check text-emerald-500 text-sm font-bold"></i> <span>Kredit Permanen (No Expired)</span></div>
                        </div>
                    </div>

                    <a href="{{ route('payment.topup') }}" class="w-full mt-8 py-2.5 bg-white hover:bg-zinc-50 text-zinc-700 border border-zinc-250 rounded-lg text-xs font-bold transition text-center shadow-3xs">Buy Add-On Pack</a>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ TESTIMONIALS ============ --}}
    <section class="py-20 sm:py-24 bg-white border-b border-zinc-100" id="testimonials">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="mb-12 reveal">
                <h2 class="notion-h1 text-3xl sm:text-4xl text-zinc-950 font-black tracking-tight">Trusted by candidates who land.</h2>
            </div>

            <!-- Responsive Testimonial Carousel -->
            <div class="relative">
                <!-- Overflow container (Scrollable on mobile, Grid on desktop) -->
                <div class="flex md:grid md:grid-cols-3 gap-6 overflow-x-auto md:overflow-x-visible snap-x snap-mandatory scroll-smooth -mx-4 px-4 md:mx-0 md:px-0 scrollbar-none" id="testimonial-carousel">
                    
                    <!-- Card 1: Rendika Azhar (Red Theme) -->
                    <div class="relative rounded-2xl overflow-hidden aspect-[4/3] md:aspect-[3/4] flex flex-col justify-end p-8 group transition-all duration-300 hover:shadow-lg shadow-md shrink-0 w-[85vw] md:w-auto snap-center">
                        <!-- Background image with red overlay -->
                        <img src="{{ asset('images/Rendika Azhar.png') }}" alt="Rendika Azhar" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-red-950 via-red-900/60 to-red-900/40 mix-blend-multiply"></div>
                        <div class="absolute inset-0 bg-red-800/20 mix-blend-color"></div>
                        
                        <!-- Overlay Content -->
                        <div class="relative z-10 text-white flex flex-col justify-between h-full pointer-events-none">
                            <div class="flex items-center gap-1 opacity-90">
                                <span class="font-extrabold text-[10px] tracking-wider uppercase font-sans">FRESH GRADUATE</span>
                            </div>
                            
                            <div class="space-y-3 mt-auto">
                                <p class="text-xs font-semibold leading-relaxed text-zinc-100">
                                    "TraKerja transformed my chaotic job application spreadsheet into a super clean, visual Kanban tracker. It's an absolute game-changer for organizing my career search!"
                                </p>
                                <p class="text-[10px] font-bold text-zinc-300">
                                    Rendika Azhar, Junior UI Designer
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Andi Ahyaul Wajdi (Blue Theme) -->
                    <div class="relative rounded-2xl overflow-hidden aspect-[4/3] md:aspect-[3/4] flex flex-col justify-end p-8 group transition-all duration-300 hover:shadow-lg shadow-md shrink-0 w-[85vw] md:w-auto snap-center">
                        <!-- Background image with blue overlay -->
                        <img src="{{ asset('images/iyal.png') }}" alt="Andi Ahyaul Wajdi" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-blue-950 via-blue-900/60 to-blue-900/40 mix-blend-multiply"></div>
                        <div class="absolute inset-0 bg-blue-800/20 mix-blend-color"></div>
                        
                        <!-- Overlay Content -->
                        <div class="relative z-10 text-white flex flex-col justify-between h-full pointer-events-none">
                            <div class="flex items-center gap-1 opacity-90">
                                <span class="font-extrabold text-[10px] tracking-wider uppercase font-sans">CAREER SWITCHER</span>
                            </div>
                            
                            <div class="space-y-3 mt-auto">
                                <p class="text-xs font-semibold leading-relaxed text-zinc-100">
                                    "The dashboard is incredibly clean and intuitive. Having real-time stage progression metrics and automated AI insights made my entire application process transparent."
                                </p>
                                <p class="text-[10px] font-bold text-zinc-300">
                                    Andi Ahyaul Wajdi, Frontend Developer
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Derva Anargya (Amber Theme) -->
                    <div class="relative rounded-2xl overflow-hidden aspect-[4/3] md:aspect-[3/4] flex flex-col justify-end p-8 group transition-all duration-300 hover:shadow-lg shadow-md shrink-0 w-[85vw] md:w-auto snap-center">
                        <!-- Background image with amber overlay -->
                        <img src="{{ asset('images/derva.png') }}" alt="Derva Anargya" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-amber-950 via-amber-900/60 to-amber-900/40 mix-blend-multiply"></div>
                        <div class="absolute inset-0 bg-amber-800/20 mix-blend-color"></div>
                        
                        <!-- Overlay Content -->
                        <div class="relative z-10 text-white flex flex-col justify-between h-full pointer-events-none">
                            <div class="flex items-center gap-1 opacity-90">
                                <span class="font-extrabold text-[10px] tracking-wider uppercase font-sans">PRODUCT LEADER</span>
                            </div>
                            
                            <div class="space-y-3 mt-auto">
                                <p class="text-xs font-semibold leading-relaxed text-zinc-100">
                                    "The Chrome Extension is spectacular. Saving jobs from LinkedIn directly into my Kanban board in one click saves me hours of manual data entry every single week."
                                </p>
                                <p class="text-[10px] font-bold text-zinc-300">
                                    Derva Anargya, Product Manager
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Carousel Indicators (Mobile Only) -->
                <div class="flex md:hidden items-center justify-center gap-2 mt-6">
                    <button class="w-2.5 h-2.5 rounded-full bg-zinc-900 transition-colors" id="test-dot-1" onclick="scrollTestimonial(0)"></button>
                    <button class="w-2.5 h-2.5 rounded-full bg-zinc-200 transition-colors" id="test-dot-2" onclick="scrollTestimonial(1)"></button>
                    <button class="w-2.5 h-2.5 rounded-full bg-zinc-200 transition-colors" id="test-dot-3" onclick="scrollTestimonial(2)"></button>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ FAQ ============ --}}
    <section class="py-20 sm:py-24 bg-zinc-50/50 border-b border-zinc-100" id="faq">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">
            <h2 class="text-center notion-h1 text-2xl sm:text-3xl text-zinc-900 mb-10 reveal">Frequently Asked Questions</h2>
            
            <div class="space-y-3.5 reveal">
                @php
                    $faqs = [
                        [
                            'Is TraKerja really free?', 
                            'Yes! TraKerja offers a free-forever tier that includes all core features like the Kanban board tracker, profile dashboard, and basic activity metrics. If you need unlimited job tracking, advanced analytics, and AI Studio features, you can upgrade to Premium Pro at any time.'
                        ],
                        [
                            'How does the AI CV Analyzer work?', 
                            'Our AI CV Analyzer simulates the behavior of professional ATS (Applicant Tracking Systems) and recruiter screening algorithms. It audits your resume structure, measures keyword density match for your target role, and provides actionable recommendations to optimize your CV.'
                        ],
                        [
                            'Is my personal data secure?', 
                            'Absolutely. We prioritize your privacy and data security. All uploaded documents, profiles, and application history data are encrypted and strictly private. We never share or sell your data to third parties.'
                        ],
                        [
                            'What payment options are available for Premium Pro?',
                            'We support multiple secure payment channels including major Credit Cards, E-Wallets (GoPay, OVO, Dana), and Virtual Account Bank Transfers (Mandiri, BCA, BNI, BRI) powered by Midtrans.'
                        ],
                        [
                            'Does TraKerja support browser extensions?',
                            'Yes! TraKerja offers a dedicated Chrome Extension that allows you to scrape and save job opportunities from LinkedIn, Jobstreet, and other hiring portals directly into your Kanban board with a single click.'
                        ],
                        [
                            'Can I import existing application data?',
                            'Definitely. Premium Pro users can download our standardized spreadsheet template and upload all their past job application records in bulk using Excel (.xlsx) or CSV formats instantly.'
                        ],
                    ];
                @endphp
                @foreach ($faqs as $faq)
                    <div class="bento-card bg-[#fafafa] hover:bg-white border border-zinc-200 transition-all rounded-lg overflow-hidden">
                        <button class="w-full text-left p-4 flex justify-between items-center group focus:outline-none" onclick="toggleFaq(this)">
                            <span class="text-xs font-bold text-zinc-800 group-hover:text-zinc-950 transition flex items-center gap-2">
                                <i class="ph ph-question text-zinc-400 text-sm"></i>
                                {{ $faq[0] }}
                            </span>
                            <span class="faq-icon transition-transform duration-200 text-zinc-400 shrink-0 ml-4">
                                <i class="ph ph-caret-down text-sm font-bold"></i>
                            </span>
                        </button>
                        <div class="faq-answer hidden px-4 pb-4 text-[10.5px] font-semibold text-zinc-500 leading-relaxed border-t border-zinc-100 pt-3 bg-white">
                            {{ $faq[1] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ CTA BANNER ============ --}}
    <section class="py-28 bg-white border-b border-zinc-100 select-none">
        <div class="max-w-2xl mx-auto px-4 text-center reveal">
            <h2 class="text-4xl sm:text-5xl font-black text-zinc-950 tracking-tight leading-none mb-4">Get started today.</h2>
            <p class="text-sm sm:text-base font-semibold text-zinc-400 mb-8 leading-relaxed max-w-lg mx-auto">Build ATS-friendly CVs, track applications, and optimize your path with AI.</p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-6 py-3 bg-[#0066cc] hover:bg-[#0052a3] text-white text-xs sm:text-sm font-bold rounded-lg transition-colors shadow-sm text-center">Get TraKerja free</a>
                <a href="#fitur" class="w-full sm:w-auto px-6 py-3 bg-white border border-zinc-250 text-zinc-700 hover:bg-zinc-50 text-xs sm:text-sm font-bold rounded-lg transition-colors shadow-3xs text-center">Explore features</a>
            </div>
        </div>
    </section>

    {{-- ============ FOOTER ============ --}}
    <footer class="bg-zinc-50/60 border-t border-zinc-200/50 py-16 text-[11px] font-semibold text-zinc-500">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-10 pb-12 border-b border-zinc-200/50">
                
                <!-- Brand Column -->
                <div class="md:col-span-4 space-y-4">
                    <div class="flex items-center gap-2 text-xs font-bold text-zinc-800 tracking-tight select-none">
                        <img src="{{ asset('images/icon.png') }}" alt="TraKerja Logo" class="w-5 h-5 object-contain">
                        <span>TraKerja</span>
                    </div>
                    <p class="text-zinc-400 font-medium leading-relaxed max-w-xs">
                        A unified, minimalist workspace to manage your entire job application pipeline, build ATS-friendly CVs, and accelerate your career using AI.
                    </p>
                </div>

                <!-- Product Links Column -->
                <div class="md:col-span-2 space-y-3">
                    <h4 class="text-[9px] font-black text-zinc-400 uppercase tracking-wider">Product</h4>
                    <ul class="space-y-2">
                        <li><a href="#fitur" class="hover:text-zinc-800 transition-colors">Features</a></li>
                        <li><a href="#pricing" class="hover:text-zinc-800 transition-colors">Pricing Plans</a></li>
                        <li><a href="#testimonials" class="hover:text-zinc-800 transition-colors">Testimonials</a></li>
                    </ul>
                </div>

                <!-- Legal/Resources Column -->
                <div class="md:col-span-3 space-y-3">
                    <h4 class="text-[9px] font-black text-zinc-400 uppercase tracking-wider">Legal & Info</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('legal.privacy') }}" class="hover:text-zinc-800 transition-colors">Privacy Policy</a></li>
                        <li><a href="{{ route('legal.terms') }}" class="hover:text-zinc-800 transition-colors">Terms of Service</a></li>
                        <li><span class="text-zinc-400 font-medium">PT Teknalogi Transformasi Digital</span></li>
                    </ul>
                </div>

                <!-- Contact & Social Column -->
                <div class="md:col-span-3 space-y-3">
                    <h4 class="text-[9px] font-black text-zinc-400 uppercase tracking-wider">Contact & Socials</h4>
                    <ul class="space-y-2.5">
                        <li class="flex items-center gap-2">
                            <i class="ph-bold ph-envelope text-zinc-700 text-xs"></i>
                            <a href="mailto:trakerja@teknalogi.id" class="hover:text-zinc-800 transition-colors">trakerja@teknalogi.id</a>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="ph-bold ph-instagram-logo text-zinc-700 text-xs"></i>
                            <a href="https://instagram.com/jointrakerja" target="_blank" rel="noopener noreferrer" class="hover:text-zinc-800 transition-colors">@jointrakerja</a>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="ph-bold ph-globe text-zinc-700 text-xs"></i>
                            <span class="text-zinc-400 font-medium">teknalogi.id</span>
                        </li>
                    </ul>
                </div>

            </div>

            <!-- Bottom Copyright -->
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-zinc-400 font-medium">
                <p>© 2026 PT Teknalogi Transformasi Digital. All rights reserved.</p>
            </div>
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
                const h3 = item.querySelector('h3');
                const p = item.querySelector('p');
                if (currentIdx === step) {
                    item.classList.remove('border-zinc-200/40', 'bg-white');
                    item.classList.add('border-zinc-200/80', 'bg-zinc-50/50');
                    if (h3) h3.className = 'font-extrabold text-zinc-900 text-xs tracking-tight';
                    if (p) p.className = 'text-[10px] font-semibold text-zinc-500 mt-1 leading-relaxed';
                } else {
                    item.classList.remove('border-zinc-200/80', 'bg-zinc-50/50');
                    item.classList.add('border-zinc-200/40', 'bg-white');
                    if (h3) h3.className = 'font-bold text-zinc-700 text-xs tracking-tight';
                    if (p) p.className = 'text-[10px] font-semibold text-zinc-400 mt-1 leading-relaxed';
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

        // Testimonial Scroll Dots mapping
        function scrollTestimonial(index) {
            const container = document.getElementById('testimonial-carousel');
            if (!container) return;
            const cardWidth = container.querySelector('.shrink-0').offsetWidth;
            const gap = 24; // gap-6 equivalent
            container.scrollTo({
                left: index * (cardWidth + gap),
                behavior: 'smooth'
            });
        }

        // Auto update testimonial dots active styling
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('testimonial-carousel');
            if (container) {
                container.addEventListener('scroll', () => {
                    const cardWidth = container.querySelector('.shrink-0').offsetWidth;
                    const gap = 24;
                    const index = Math.round(container.scrollLeft / (cardWidth + gap));
                    
                    for (let i = 1; i <= 3; i++) {
                        const dot = document.getElementById(`test-dot-${i}`);
                        if (dot) {
                            if (i === (index + 1)) {
                                dot.classList.remove('bg-zinc-200');
                                dot.classList.add('bg-zinc-900');
                            } else {
                                dot.classList.remove('bg-zinc-900');
                                dot.classList.add('bg-zinc-200');
                            }
                        }
                    }
                });
            }

            // Glassmorphism Navbar Scroll Effect
            const nav = document.querySelector('nav');
            if (nav) {
                window.addEventListener('scroll', () => {
                    if (window.scrollY > 15) {
                        nav.classList.add('shadow-[0_2px_20px_-10px_rgba(0,0,0,0.05)]', 'border-zinc-200/80');
                        nav.classList.remove('border-zinc-200/60');
                    } else {
                        nav.classList.remove('shadow-[0_2px_20px_-10px_rgba(0,0,0,0.05)]', 'border-zinc-200/80');
                        nav.classList.add('border-zinc-200/60');
                    }
                });
            }

            // Feature Grid Auto Play Slideshow
            let activeFeature = 1;
            const featureInterval = setInterval(() => {
                activeFeature = activeFeature % 4 + 1;
                switchFeature(activeFeature);
            }, 6000);

            // Pause auto play when user manually selects a feature
            window.switchFeature = (step) => {
                clearInterval(featureInterval);
                activeFeature = step;
                
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
            };
        });
    </script>
</body>
</html>


