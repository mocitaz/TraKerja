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

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="TraKerja - Platform Manajemen & Pelacakan Lamaran Kerja">
    <meta property="og:description" content="Tingkatkan peluang lolos kerja dengan tracker cerdas, AI Cover Letter, dan analitik lengkap. Gratis untuk pencari kerja Indonesia.">
    <meta property="og:image" content="{{ asset('images/fitur-section.jpg') }}">

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url('/') }}">
    <meta property="twitter:title" content="TraKerja - Aplikasi Pelacakan Karir & Pekerjaan">
    <meta property="twitter:description" content="Pantau status lamaran, buat CV standar ATS, dan analisis skor interview Anda.">
    <meta property="twitter:image" content="{{ asset('images/fitur-section.jpg') }}">

    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('images/icon.png') }}?v=2">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="TraKerja">

    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "SoftwareApplication",
      "name": "TraKerja",
      "operatingSystem": "WebBrowser",
      "applicationCategory": "BusinessApplication",
      "offers": { "@@type": "Offer", "price": "0", "priceCurrency": "IDR" },
      "description": "TraKerja adalah platform ATS & tracker lamaran kerja gratis di Indonesia. Pantau status lamaran, buat CV standar ATS, dan dapatkan insight analitik untuk karir impian Anda.",
      "url": "{{ url('/') }}"
    }
    </script>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        /* ============ DESIGN TOKENS ============
           Base: near-white / near-black, Notion-like restraint.
           Accent: TraKerja's own pink + blue, used sparingly (chips, one CTA, icons)
           rather than as full-bleed gradients.
        */
        :root {
            --ink: rgba(15, 15, 15, 0.94);
            --ink-soft: rgba(15, 15, 15, 0.62);
            --ink-faint: rgba(15, 15, 15, 0.42);
            --line: rgba(15, 15, 15, 0.09);
            --bg-soft: #F7F6F4;
            --pink: #D983E4;
            --pink-deep: #A83DBD;
            --blue: #4E71C5;
            --blue-deep: #3A5CA8;
            --cta: #14142B;
        }

        * { scroll-behavior: smooth; }
        body {
            overflow-x: hidden;
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            color: var(--ink);
            -webkit-font-smoothing: antialiased;
        }

        .tk-display {
            letter-spacing: -0.03em;
            font-weight: 800;
        }
        .tk-display-lg { letter-spacing: -0.045em; font-weight: 800; }

        .tk-eyebrow {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--pink-deep);
        }

        /* Buttons */
        .btn-solid {
            background: var(--cta);
            color: #fff;
            transition: transform .15s ease, background .2s ease;
        }
        .btn-solid:hover { background: #000; transform: translateY(-1px); }

        .btn-ghost {
            background: #fff;
            border: 1px solid var(--line);
            color: var(--ink);
            transition: border-color .2s ease, transform .15s ease;
        }
        .btn-ghost:hover { border-color: rgba(15,15,15,0.25); transform: translateY(-1px); }

        .btn-pink {
            background: var(--pink);
            color: #fff;
            transition: transform .15s ease, background .2s ease;
        }
        .btn-pink:hover { background: var(--pink-deep); transform: translateY(-1px); }

        /* Status chip motif — the page's signature element.
           A job application only ever moves through these states;
           we reuse the chip shape everywhere something "moves" on TraKerja. */
        .stage-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 600;
            box-shadow: 0 8px 20px -8px rgba(0,0,0,0.18);
            white-space: nowrap;
        }
        .stage-chip .dot { width: 6px; height: 6px; border-radius: 999px; }
        .stage-chip.applied { background:#EFF1FF; color:#3D3F8F; }
        .stage-chip.applied .dot { background:#5B5FE0; }
        .stage-chip.interview { background:#FFF3D9; color:#8A5A00; }
        .stage-chip.interview .dot { background:#F2A70A; }
        .stage-chip.offer { background:#E4F6EA; color:#12683A; }
        .stage-chip.offer .dot { background:#1AAE39; }

        @keyframes chipFloat {
            0%, 100% { transform: translateY(0) rotate(var(--rot, 0deg)); }
            50% { transform: translateY(-10px) rotate(var(--rot, 0deg)); }
        }
        .chip-float { animation: chipFloat 5s ease-in-out infinite; }

        /* Minimal scroll reveal — one motion idea used consistently */
        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity .7s cubic-bezier(.2,.7,.2,1), transform .7s cubic-bezier(.2,.7,.2,1);
        }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .reveal-stagger.visible > * { opacity: 1; transform: translateY(0); }
        .reveal-stagger > * {
            opacity: 0; transform: translateY(18px);
            transition: opacity .6s ease, transform .6s ease;
        }
        .reveal-stagger.visible > *:nth-child(1){transition-delay:.05s}
        .reveal-stagger.visible > *:nth-child(2){transition-delay:.12s}
        .reveal-stagger.visible > *:nth-child(3){transition-delay:.19s}
        .reveal-stagger.visible > *:nth-child(4){transition-delay:.26s}

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: .01ms !important; transition-duration: .01ms !important; }
        }

        .card-flat {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 20px;
        }
        .card-flat:hover { border-color: rgba(15,15,15,0.16); }

        .showcase-heading {
            font-size: clamp(2rem, 4vw, 3.375rem);
            line-height: 1.04;
        }

        .marquee-track { animation: marquee 22s linear infinite; }
        @keyframes marquee { from { transform: translateX(0);} to { transform: translateX(-50%);} }
        .marquee-mask {
            -webkit-mask-image: linear-gradient(to right, transparent, black 8%, black 92%, transparent);
            mask-image: linear-gradient(to right, transparent, black 8%, black 92%, transparent);
        }
    </style>
</head>
<body class="antialiased bg-white">

    {{-- Welcome / announcement popup --}}
    <div id="welcome-popup" class="fixed inset-0 z-[100] flex items-center justify-center hidden opacity-0 transition-opacity duration-300 bg-black/60 backdrop-blur-sm px-4">
        <div class="relative bg-white rounded-2xl shadow-2xl overflow-hidden max-w-md md:max-w-lg w-full transform scale-95 transition-transform duration-300" id="welcome-popup-content">
            <button onclick="closeWelcomePopup()" class="absolute top-3 right-3 z-10 w-8 h-8 flex items-center justify-center bg-black/20 hover:bg-black/40 text-white rounded-full backdrop-blur-md transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <img src="{{ asset('images/msg.png') }}" alt="Welcome Message" class="w-full h-auto block" onerror="this.src='https://placehold.co/600x400/14142B/ffffff?text=Pengumuman'">
        </div>
    </div>

    {{-- ============ NAVBAR ============ --}}
    <nav class="fixed top-0 left-0 right-0 bg-white/85 backdrop-blur-xl border-b border-black/[0.06] z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-8 h-8" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="w-8 h-8 bg-[#14142B] rounded-lg items-center justify-center" style="display:none;"><span class="text-white font-bold text-sm">T</span></div>
                    <span class="text-lg font-extrabold tracking-tight">TraKerja</span>
                </a>

                <div class="hidden md:flex items-center gap-1 text-sm font-medium text-[color:var(--ink-soft)]">
                    <a href="#fitur" class="px-3 py-2 rounded-lg hover:bg-black/[0.04] hover:text-[color:var(--ink)] transition-colors">Fitur</a>
                    <a href="#pricing" class="px-3 py-2 rounded-lg hover:bg-black/[0.04] hover:text-[color:var(--ink)] transition-colors">Harga</a>
                    <a href="#testimonials" class="px-3 py-2 rounded-lg hover:bg-black/[0.04] hover:text-[color:var(--ink)] transition-colors">Testimoni</a>
                    <a href="#faq" class="px-3 py-2 rounded-lg hover:bg-black/[0.04] hover:text-[color:var(--ink)] transition-colors">FAQ</a>
                </div>

                <div class="flex items-center gap-2 sm:gap-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/tracker') }}" class="btn-solid px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="hidden sm:inline-flex btn-ghost px-4 py-2 rounded-lg text-sm font-semibold">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn-solid px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold">Daftar Gratis</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    {{-- ============ HERO ============ --}}
    <section class="relative pt-32 pb-20 sm:pt-40 sm:pb-28 bg-white overflow-hidden">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[var(--bg-soft)] border border-black/[0.06] text-xs font-semibold text-[color:var(--ink-soft)] mb-6 reveal">
                <span class="w-1.5 h-1.5 bg-[color:var(--pink)] rounded-full"></span>
                Platform tracker lamaran kerja #1 di Indonesia
            </div>

            <h1 class="tk-display-lg text-[2.75rem] sm:text-6xl md:text-7xl mb-6 reveal">
                Lamaran kerja,<br>
                akhirnya <span style="color:var(--pink-deep)">rapi</span>.
            </h1>

            <p class="text-base sm:text-lg text-[color:var(--ink-soft)] max-w-xl mx-auto mb-9 reveal">
                Satu dashboard untuk melacak setiap lamaran, membuat CV yang lolos ATS, dan tahu persis kapan harus follow-up.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 mb-10 reveal">
                @auth
                    <a href="{{ url('/tracker') }}" class="btn-solid w-full sm:w-auto px-6 py-3 rounded-xl font-bold text-sm">Buka Dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="btn-solid w-full sm:w-auto px-6 py-3 rounded-xl font-bold text-sm">Mulai Gratis — Tanpa Kartu Kredit</a>
                    <a href="{{ route('login') }}" class="btn-ghost w-full sm:w-auto px-6 py-3 rounded-xl font-bold text-sm">Login</a>
                @endauth
            </div>

            <p class="text-xs text-[color:var(--ink-faint)] font-medium reveal">
                &gt;200 pengguna aktif &nbsp;·&nbsp; &gt;700 lamaran tersimpan &nbsp;·&nbsp; 100% gratis untuk mulai
            </p>
        </div>

        {{-- Mockup card, Notion-style, with floating stage chips as the signature motif --}}
        <div class="relative max-w-4xl mx-auto mt-16 px-4 reveal">
            <div class="card-flat overflow-hidden shadow-[0_30px_80px_-30px_rgba(0,0,0,0.25)]">
                <img src="{{ asset('images/mu-trakerja.png') }}" alt="Dashboard TraKerja" class="w-full h-auto block" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="w-full aspect-video bg-[var(--bg-soft)] items-center justify-center" style="display:none;">
                    <p class="text-sm text-[color:var(--ink-faint)]">Tambahkan gambar di: public/images/mu-trakerja.png</p>
                </div>
            </div>

            <div class="stage-chip applied chip-float absolute -left-4 sm:-left-10 top-8 hidden sm:inline-flex" style="--rot:-6deg; animation-delay:0s;">
                <span class="dot"></span> Applied
            </div>
            <div class="stage-chip interview chip-float absolute -right-4 sm:-right-8 top-1/3 hidden sm:inline-flex" style="--rot:4deg; animation-delay:.6s;">
                <span class="dot"></span> Interview
            </div>
            <div class="stage-chip offer chip-float absolute -left-2 sm:-left-6 bottom-6 hidden sm:inline-flex" style="--rot:-3deg; animation-delay:1.2s;">
                <span class="dot"></span> Offer
            </div>
        </div>
    </section>

    {{-- ============ FEATURE OVERVIEW GRID ============ --}}
    <section id="fitur" class="py-20 sm:py-28 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mb-12 reveal">
                <p class="tk-eyebrow mb-3">Platform lengkap</p>
                <h2 class="tk-display showcase-heading">Semua yang dibutuhkan pencari kerja, dalam satu tempat.</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 reveal-stagger">
                <div class="card-flat p-6">
                    <div class="w-11 h-11 rounded-xl bg-[#EFF1FF] flex items-center justify-center mb-5">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none"><rect x="4" y="4" width="16" height="16" rx="2" stroke="#4E71C5" stroke-width="2"/><path d="M8 10V16M12 8V16M16 12V16" stroke="#4E71C5" stroke-width="2" stroke-linecap="round"/></svg>
                    </div>
                    <h3 class="font-bold text-base mb-1.5">Kanban Board</h3>
                    <p class="text-sm text-[color:var(--ink-soft)] leading-relaxed">Seret dan lepas setiap lamaran antar status — tanpa spreadsheet berantakan.</p>
                </div>
                <div class="card-flat p-6">
                    <div class="w-11 h-11 rounded-xl bg-[#FCEFFF] flex items-center justify-center mb-5">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" stroke="#D983E4" stroke-width="2" stroke-linejoin="round"/><path d="M9 13l2 2 4-4" stroke="#D983E4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <h3 class="font-bold text-base mb-1.5">AI CV Analyzer</h3>
                    <p class="text-sm text-[color:var(--ink-soft)] leading-relaxed">Skor instan dan saran perbaikan agar CV lolos filter ATS HRD.</p>
                </div>
                <div class="card-flat p-6">
                    <div class="w-11 h-11 rounded-xl bg-[#E4F6EA] flex items-center justify-center mb-5">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="#1AAE39" stroke-width="2"/><path d="M12 7v5l3.5 2" stroke="#1AAE39" stroke-width="2" stroke-linecap="round"/></svg>
                    </div>
                    <h3 class="font-bold text-base mb-1.5">Smart Reminder</h3>
                    <p class="text-sm text-[color:var(--ink-soft)] leading-relaxed">Pengingat follow-up otomatis supaya tidak ada lamaran yang terlewat.</p>
                </div>
                <div class="card-flat p-6">
                    <div class="w-11 h-11 rounded-xl bg-[#FFF3D9] flex items-center justify-center mb-5">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none"><path d="M4 19V9M12 19V5M20 19v-7" stroke="#8A5A00" stroke-width="2" stroke-linecap="round"/></svg>
                    </div>
                    <h3 class="font-bold text-base mb-1.5">Analytics</h3>
                    <p class="text-sm text-[color:var(--ink-soft)] leading-relaxed">Lihat platform mana yang paling sering memanggil interview.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ SHOWCASE — big cards, Notion article style ============ --}}
    <section class="py-4 sm:py-8 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Showcase 1 --}}
            <div class="grid lg:grid-cols-2 card-flat overflow-hidden reveal">
                <div class="p-8 sm:p-12 flex flex-col justify-center order-2 lg:order-1">
                    <p class="tk-eyebrow mb-3">Kanban Board</p>
                    <h3 class="tk-display text-2xl sm:text-3xl mb-3">Lihat seluruh pipeline lamaran sekali pandang.</h3>
                    <p class="text-sm sm:text-base text-[color:var(--ink-soft)] leading-relaxed mb-6">Setiap lamaran punya kartu sendiri. Geser dari Applied ke Interview ke Offer — status selalu jelas, tanpa perlu buka banyak tab email.</p>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 font-bold text-sm" style="color:var(--blue-deep)">
                        Coba Kanban Board
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
                <div class="order-1 lg:order-2 bg-[#EFF1FF] flex items-center justify-center p-8 min-h-[280px]">
                    <img src="{{ asset('images/fitur-section.jpg') }}" alt="Kanban Board TraKerja" class="max-w-full max-h-72 object-contain rounded-xl shadow-lg" onerror="this.style.display='none';">
                </div>
            </div>

            {{-- Showcase 2 --}}
            <div class="grid lg:grid-cols-2 card-flat overflow-hidden reveal">
                <div class="bg-[#FCEFFF] flex items-center justify-center p-8 min-h-[280px]">
                    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-sm">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-bold text-[color:var(--ink-faint)] uppercase tracking-wide">Skor ATS</span>
                            <span class="text-2xl font-extrabold" style="color:var(--pink-deep)">92<span class="text-sm text-[color:var(--ink-faint)]">/100</span></span>
                        </div>
                        <div class="h-2 w-full bg-black/[0.06] rounded-full overflow-hidden mb-4">
                            <div class="h-full rounded-full" style="width:92%; background:var(--pink);"></div>
                        </div>
                        <p class="text-xs text-[color:var(--ink-soft)] leading-relaxed">Kata kunci relevan terdeteksi. Format ramah ATS. Struktur pengalaman kerja sudah sesuai standar rekruter.</p>
                    </div>
                </div>
                <div class="p-8 sm:p-12 flex flex-col justify-center">
                    <p class="tk-eyebrow mb-3">AI CV Analyzer</p>
                    <h3 class="tk-display text-2xl sm:text-3xl mb-3">Tahu persis kenapa CV Anda belum lolos.</h3>
                    <p class="text-sm sm:text-base text-[color:var(--ink-soft)] leading-relaxed mb-6">AI Analyzer membaca CV seperti sistem ATS milik perusahaan, lalu memberi skor dan saran perbaikan yang konkret — bukan sekadar "perbaiki formatnya".</p>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 font-bold text-sm" style="color:var(--pink-deep)">
                        Analisis CV Anda
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>

            {{-- Showcase 3 --}}
            <div class="grid lg:grid-cols-2 card-flat overflow-hidden reveal">
                <div class="p-8 sm:p-12 flex flex-col justify-center order-2 lg:order-1">
                    <p class="tk-eyebrow mb-3">Goal Tracking</p>
                    <h3 class="tk-display text-2xl sm:text-3xl mb-3">Konsisten apply, bukan sekadar rajin sesaat.</h3>
                    <p class="text-sm sm:text-base text-[color:var(--ink-soft)] leading-relaxed mb-6">Tetapkan target lamaran mingguan. TraKerja mengingatkan progres Anda supaya pencarian kerja tetap berjalan, minggu demi minggu.</p>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 font-bold text-sm" style="color:var(--blue-deep)">
                        Pasang target Anda
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
                <div class="order-1 lg:order-2 bg-[#E4F6EA] flex items-center justify-center p-8 min-h-[280px]">
                    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-sm space-y-3">
                        <div class="flex items-center justify-between text-sm font-semibold"><span>Minggu ini</span><span style="color:#12683A">8 / 10 lamaran</span></div>
                        <div class="h-2 w-full bg-black/[0.06] rounded-full overflow-hidden"><div class="h-full rounded-full" style="width:80%; background:#1AAE39;"></div></div>
                        <div class="flex gap-2 pt-1">
                            <span class="stage-chip applied text-[11px] px-2.5 py-1"><span class="dot"></span>3 applied</span>
                            <span class="stage-chip interview text-[11px] px-2.5 py-1"><span class="dot"></span>2 interview</span>
                            <span class="stage-chip offer text-[11px] px-2.5 py-1"><span class="dot"></span>1 offer</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- ============ STATS ============ --}}
    <section class="py-20 sm:py-24 bg-[var(--bg-soft)]">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="tk-display showcase-heading text-center mb-14 reveal">Dipercaya pencari kerja di seluruh Indonesia.</h2>
            <div class="grid grid-cols-2 lg:grid-cols-4 divide-x divide-black/[0.08] reveal-stagger">
                <div class="text-center px-2">
                    <p class="text-4xl sm:text-5xl font-extrabold tracking-tight" style="color:var(--pink-deep)">&gt;200</p>
                    <p class="text-sm text-[color:var(--ink-soft)] mt-1">pengguna aktif</p>
                </div>
                <div class="text-center px-2">
                    <p class="text-4xl sm:text-5xl font-extrabold tracking-tight" style="color:var(--pink-deep)">&gt;700</p>
                    <p class="text-sm text-[color:var(--ink-soft)] mt-1">lamaran disimpan</p>
                </div>
                <div class="text-center px-2">
                    <p class="text-4xl sm:text-5xl font-extrabold tracking-tight" style="color:var(--pink-deep)">100%</p>
                    <p class="text-sm text-[color:var(--ink-soft)] mt-1">gratis untuk mulai</p>
                </div>
                <div class="text-center px-2">
                    <p class="text-4xl sm:text-5xl font-extrabold tracking-tight" style="color:var(--pink-deep)">24/7</p>
                    <p class="text-sm text-[color:var(--ink-soft)] mt-1">akses real-time</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ HOW IT WORKS (kept structurally, restyled) ============ --}}
    <section class="py-20 sm:py-28 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14 reveal">
                <p class="tk-eyebrow mb-3">Cara kerja TraKerja</p>
                <h2 class="tk-display showcase-heading">Mulai dalam 3 langkah.</h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 md:gap-12 items-center">
                <div class="lg:col-span-5 space-y-3 reveal">
                    <div class="how-it-works-item card-flat overflow-hidden" style="border-color:var(--pink); border-width:2px;" id="hiw-item-1">
                        <button class="how-it-works-btn w-full px-6 py-5 text-left flex items-center justify-between" data-step="1" onclick="toggleHowItWorks(this)">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-[#FCEFFF] flex items-center justify-center flex-shrink-0"><span class="font-bold text-sm" style="color:var(--pink-deep)">01</span></div>
                                <div>
                                    <h3 class="font-bold text-base">Daftar & atur profil</h3>
                                    <p class="text-sm text-[color:var(--ink-faint)]">Setup 2 menit, langsung pakai.</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 how-it-works-icon transition-transform flex-shrink-0 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="how-it-works-content px-6 pb-6">
                            <p class="text-sm text-[color:var(--ink-soft)] leading-relaxed">Buat akun gratis, isi biodata dan preferensi kerja sekali di <strong>My Profile</strong> — dipakai otomatis di seluruh fitur lain.</p>
                        </div>
                    </div>

                    <div class="how-it-works-item card-flat overflow-hidden" id="hiw-item-2">
                        <button class="how-it-works-btn w-full px-6 py-5 text-left flex items-center justify-between" data-step="2" onclick="toggleHowItWorks(this)">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-[var(--bg-soft)] flex items-center justify-center flex-shrink-0"><span class="font-bold text-sm text-[color:var(--ink-faint)]">02</span></div>
                                <div>
                                    <h3 class="font-bold text-base text-[color:var(--ink-soft)]">Kelola lamaran & dokumen</h3>
                                    <p class="text-sm text-[color:var(--ink-faint)]">Semua dalam satu dashboard.</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 how-it-works-icon text-[color:var(--ink-faint)] transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                        <div class="how-it-works-content hidden px-6 pb-6">
                            <p class="text-sm text-[color:var(--ink-soft)] leading-relaxed">Tambah lamaran ke Kanban Board, catat jadwal interview, dan buat CV profesional — semua terorganisir tanpa spreadsheet.</p>
                        </div>
                    </div>

                    <div class="how-it-works-item card-flat overflow-hidden" id="hiw-item-3">
                        <button class="how-it-works-btn w-full px-6 py-5 text-left flex items-center justify-between" data-step="3" onclick="toggleHowItWorks(this)">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-[var(--bg-soft)] flex items-center justify-center flex-shrink-0"><span class="font-bold text-sm text-[color:var(--ink-faint)]">03</span></div>
                                <div>
                                    <h3 class="font-bold text-base text-[color:var(--ink-soft)]">Analisis & optimalkan</h3>
                                    <p class="text-sm text-[color:var(--ink-faint)]">AI + data untuk hasil terbaik.</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 how-it-works-icon text-[color:var(--ink-faint)] transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                        <div class="how-it-works-content hidden px-6 pb-6">
                            <p class="text-sm text-[color:var(--ink-soft)] leading-relaxed">Pantau statistik di Summary, optimalkan CV dengan AI Analyzer, dan generate cover letter otomatis untuk tiap posisi.</p>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-7 relative hidden lg:flex flex-col items-center justify-center reveal">
                    <div class="relative w-full max-w-2xl mx-auto aspect-[16/10] card-flat overflow-hidden">
                        <img id="how-it-works-img-1" src="{{ asset('images/mu0.png') }}" alt="Daftar & atur profil" class="how-it-works-image absolute inset-0 w-full h-full object-contain transition-opacity duration-500" style="opacity:1;">
                        <img id="how-it-works-img-2" src="{{ asset('images/mu1.png') }}" alt="Kelola lamaran & dokumen" class="how-it-works-image absolute inset-0 w-full h-full object-contain transition-opacity duration-500 hidden">
                        <img id="how-it-works-img-3" src="{{ asset('images/mu2.png') }}" alt="Analisis & optimalkan" class="how-it-works-image absolute inset-0 w-full h-full object-contain transition-opacity duration-500 hidden">
                    </div>
                    <div class="flex items-center justify-center gap-3 mt-6">
                        <div class="w-5 h-2 rounded-full" style="background:var(--pink);" id="hiw-dot-1"></div>
                        <div class="w-2 h-2 rounded-full bg-black/[0.15]" id="hiw-dot-2"></div>
                        <div class="w-2 h-2 rounded-full bg-black/[0.15]" id="hiw-dot-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ PRICING ============ --}}
    <section class="py-20 sm:py-28 bg-[var(--bg-soft)]" id="pricing">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="card-flat flex flex-col sm:flex-row items-start sm:items-center gap-4 p-5 sm:p-6 mb-12 reveal">
                <div class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center" style="background:var(--pink);">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                </div>
                <div class="flex-1">
                    <span class="inline-flex items-center gap-1.5 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-1.5" style="background:var(--pink-deep);">Baru dirilis</span>
                    <p class="font-extrabold text-lg leading-tight">TraKerja Premium Lifetime</p>
                    <p class="text-sm text-[color:var(--ink-soft)]">Akses tanpa batas, sekali bayar. Harga promo peluncuran.</p>
                </div>
                <div class="flex-shrink-0 text-right">
                    <p class="text-xs text-[color:var(--ink-faint)] line-through">Rp 99.000</p>
                    <p class="text-2xl sm:text-3xl font-extrabold" style="color:var(--pink-deep)">Rp 19.999</p>
                </div>
            </div>

            <div class="text-center mb-12 reveal">
                <h2 class="tk-display showcase-heading">Pilih paket Anda.</h2>
                <p class="text-[color:var(--ink-soft)] mt-3 max-w-xl mx-auto">Mulai gratis selamanya, atau upgrade sekali bayar untuk membuka kekuatan penuh pelacakan karir.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 reveal-stagger">

                <div class="card-flat p-8">
                    <span class="font-bold text-[color:var(--ink-soft)] text-sm">Free Standar</span>
                    <p class="text-4xl font-extrabold mt-3 mb-1">Gratis</p>
                    <p class="text-sm text-[color:var(--ink-faint)] mb-6">Selamanya, tanpa syarat</p>
                    <div class="border-t border-black/[0.08] pt-5 space-y-3 mb-8 text-sm text-[color:var(--ink-soft)]">
                        <p>✓ Maksimal 25 job tracker</p>
                        <p>✓ 2 template CV standar</p>
                        <p>✓ 1 kredit AI Analyzer gratis</p>
                        <p>✓ 3 kredit Cover Letter Generator</p>
                        <p>✓ Dashboard & analitik standar</p>
                    </div>
                    <a href="{{ route('register') }}" class="btn-ghost block w-full text-center px-6 py-3 rounded-xl font-semibold">Mulai Gratis</a>
                </div>

                <div class="card-flat p-8 relative" style="border-color:var(--pink); border-width:2px;">
                    <span class="absolute -top-3.5 left-1/2 -translate-x-1/2 text-white text-xs font-bold px-4 py-1.5 rounded-full whitespace-nowrap" style="background:var(--pink-deep);">Paling populer</span>
                    <span class="font-bold text-sm mt-2 inline-block">Premium Pro</span>
                    <p class="text-4xl font-extrabold mt-3 mb-1">Rp 19.999</p>
                    <p class="text-sm mb-6" style="color:var(--pink-deep)">Sekali bayar, akses selamanya</p>
                    <div class="border-t border-black/[0.08] pt-5 space-y-3 mb-8 text-sm text-[color:var(--ink-soft)]">
                        <p class="font-semibold text-[color:var(--ink)]">✓ Unlimited job tracker</p>
                        <p>✓ 50+ template CV premium</p>
                        <p>✓ Bulk importer lamaran kerja</p>
                        <p>✓ Full analytics & dashboard</p>
                        <p>✓ 5 kredit bonus AI CV Analyzer</p>
                        <p>✓ 15 kredit Cover Letter Generator</p>
                    </div>
                    <a href="{{ route('payment.index') }}" class="btn-pink block w-full text-center px-6 py-3.5 rounded-xl font-bold">Beli Premium Sekarang</a>
                </div>

                <div class="card-flat p-8 relative">
                    <span class="absolute -top-3.5 left-6 text-white text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wider" style="background:var(--blue);">Add-on</span>
                    <span class="font-bold text-[color:var(--ink-soft)] text-sm mt-2 inline-block">AI Boost</span>
                    <p class="text-4xl font-extrabold mt-3 mb-1">Rp 15.000</p>
                    <p class="text-sm text-[color:var(--ink-faint)] mb-6">Per paket, beli kapan saja</p>
                    <div class="border-t border-black/[0.08] pt-5 space-y-3 mb-8 text-sm text-[color:var(--ink-soft)]">
                        <p>✓ +10 kredit AI CV Analyzer</p>
                        <p>✓ +15 kredit Cover Letter Generator</p>
                        <p>✓ Berlaku selamanya</p>
                    </div>
                    <a href="{{ route('payment.topup') }}" class="block w-full text-center px-6 py-3 rounded-xl font-bold border-2" style="border-color:var(--blue); color:var(--blue-deep);">Top-Up Sekarang</a>
                </div>
            </div>

            <div class="card-flat flex flex-col md:flex-row items-center justify-between gap-6 p-5 md:p-6 reveal">
                <div class="text-center md:text-left flex-shrink-0">
                    <h3 class="text-sm font-bold flex items-center justify-center md:justify-start gap-2">
                        <span class="relative flex h-2.5 w-2.5"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span></span>
                        Pembayaran instan & otomatis
                    </h3>
                    <p class="text-xs text-[color:var(--ink-faint)] mt-1">Aktivasi hitungan detik via QRIS & VA</p>
                </div>
                <div class="w-full md:w-auto flex-1 overflow-hidden marquee-mask relative h-10">
                    <div class="absolute flex w-[200%] gap-8 items-center marquee-track h-full">
                        @for ($i = 0; $i < 2; $i++)
                        <div class="flex items-center gap-8 px-3">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" alt="QRIS" class="h-6 object-contain grayscale">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" alt="BCA" class="h-5 object-contain grayscale">
                            <img src="{{ asset('images/bni.png') }}" onerror="this.src='https://upload.wikimedia.org/wikipedia/id/thumb/5/55/BNI_logo.svg/1200px-BNI_logo.svg.png'" alt="BNI" class="h-4 object-contain grayscale">
                            <img src="{{ asset('images/bri.png') }}" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/6/68/BANK_BRI_logo.svg/1200px-BANK_BRI_logo.svg.png'" alt="BRI" class="h-5 object-contain grayscale">
                        </div>
                        @endfor
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- ============ TESTIMONIALS ============ --}}
    <section class="py-20 sm:py-28 bg-white" id="testimonials">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 reveal">
                <p class="tk-eyebrow mb-3">Cerita pengguna</p>
                <h2 class="tk-display showcase-heading">Kata mereka yang sudah pakai TraKerja.</h2>
            </div>

            <div class="relative w-full overflow-hidden reveal">
                <div class="flex transition-transform duration-500 ease-in-out" id="testimonial-track">
                    <div class="w-full flex-shrink-0 px-1">
                        <div class="grid md:grid-cols-[280px_1fr] card-flat overflow-hidden">
                            <div class="bg-[#EFF1FF] relative min-h-[220px] md:min-h-[320px]">
                                <img src="{{ asset('images/sarah.png') }}" alt="Rendika" class="absolute inset-0 w-full h-full object-cover" onerror="this.src='https://placehold.co/400x600/EFF1FF/3D3F8F?text=Rendika'">
                            </div>
                            <div class="p-8 sm:p-10 flex flex-col justify-center">
                                <h3 class="text-2xl font-extrabold mb-1">Rendika</h3>
                                <p class="font-bold text-sm mb-4" style="color:var(--pink-deep)">Fresh Graduate</p>
                                <p class="text-[color:var(--ink-soft)] leading-relaxed">"TraKerja sangat membantu saya merapikan lamaran kerja yang berantakan jadi jauh lebih terorganisasi. Makin banyak yang diapply, peluang dipanggil pun makin besar!"</p>
                            </div>
                        </div>
                    </div>
                    <div class="w-full flex-shrink-0 px-1">
                        <div class="grid md:grid-cols-[280px_1fr] card-flat overflow-hidden">
                            <div class="bg-[#FCEFFF] relative min-h-[220px] md:min-h-[320px]">
                                <img src="{{ asset('images/ahmad.png') }}" alt="Andi" class="absolute inset-0 w-full h-full object-cover" onerror="this.src='https://placehold.co/400x600/FCEFFF/A83DBD?text=Andi'">
                            </div>
                            <div class="p-8 sm:p-10 flex flex-col justify-center">
                                <h3 class="text-2xl font-extrabold mb-1">Andi</h3>
                                <p class="font-bold text-sm mb-4" style="color:var(--pink-deep)">Career Switcher</p>
                                <p class="text-[color:var(--ink-soft)] leading-relaxed">"Platform yang sederhana tapi efektif. Analytics-nya membantu saya tahu platform mana yang paling sering memanggil interview. Semua data tersimpan aman."</p>
                            </div>
                        </div>
                    </div>
                    <div class="w-full flex-shrink-0 px-1">
                        <div class="grid md:grid-cols-[280px_1fr] card-flat overflow-hidden">
                            <div class="bg-[#E4F6EA] relative min-h-[220px] md:min-h-[320px]">
                                <img src="{{ asset('images/maya.png') }}" alt="Fakhri" class="absolute inset-0 w-full h-full object-cover" onerror="this.src='https://placehold.co/400x600/E4F6EA/12683A?text=Fakhri'">
                            </div>
                            <div class="p-8 sm:p-10 flex flex-col justify-center">
                                <h3 class="text-2xl font-extrabold mb-1">Fakhri</h3>
                                <p class="font-bold text-sm mb-4" style="color:var(--pink-deep)">Pencari Kerja</p>
                                <p class="text-[color:var(--ink-soft)] leading-relaxed">"Goal tracking-nya bagus banget! Membantu saya tetap konsisten apply kerja setiap minggu. Akhirnya dapat kerja juga!"</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end items-center mt-6 gap-6 reveal">
                <div class="font-bold text-sm tracking-widest" id="testi-counter" style="color:var(--pink-deep)">01 / 03</div>
                <div class="flex gap-2">
                    <button onclick="moveTestimonial(-1)" class="w-10 h-10 flex items-center justify-center rounded-full border border-black/[0.1] hover:bg-black/[0.04] transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg></button>
                    <button onclick="moveTestimonial(1)" class="w-10 h-10 flex items-center justify-center rounded-full border border-black/[0.1] hover:bg-black/[0.04] transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg></button>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ CTA BANNER ============ --}}
    <section class="py-4">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl p-8 sm:p-12 flex flex-col md:flex-row items-center justify-between gap-6 reveal" style="background:var(--cta);">
                <div class="text-center md:text-left">
                    <h2 class="tk-display text-2xl sm:text-3xl text-white mb-2">Siap mengorganisir pencarian kerja Anda?</h2>
                    <p class="text-white/70 text-sm">Mulai gratis dan rasakan perbedaannya hari ini.</p>
                </div>
                @guest
                    <div class="flex flex-col sm:flex-row gap-3 flex-shrink-0">
                        <a href="{{ route('register') }}" class="bg-white text-[color:var(--cta)] px-6 py-3 rounded-xl font-bold text-sm text-center">Daftar Gratis</a>
                        <a href="{{ route('login') }}" class="border border-white/30 text-white px-6 py-3 rounded-xl font-bold text-sm text-center">Login</a>
                    </div>
                @else
                    <a href="{{ url('/tracker') }}" class="bg-white text-[color:var(--cta)] px-6 py-3 rounded-xl font-bold text-sm text-center flex-shrink-0">Buka Dashboard</a>
                @endguest
            </div>
        </div>
    </section>

    {{-- ============ CONTACT + FAQ ============ --}}
    <section class="py-20 sm:py-28 bg-white" id="faq">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid md:grid-cols-2 gap-10 items-center mb-20 reveal">
                <div class="flex justify-center order-2 md:order-1">
                    <img src="{{ asset('images/support-team.png') }}" alt="Customer Support" class="w-full max-w-sm object-contain" onerror="this.src='https://ui-avatars.com/api/?name=Support&background=FCEFFF&color=A83DBD&size=400'">
                </div>
                <div class="text-center md:text-left order-1 md:order-2">
                    <h2 class="tk-display showcase-heading text-3xl mb-4">Pertanyaan? Kami selalu online.</h2>
                    <p class="text-[color:var(--ink-soft)] mb-6">Kami dapat membantu Anda mengenal TraKerja lebih baik.</p>
                    <a href="mailto:support@trakerja.com" class="inline-flex items-center gap-2 font-bold text-lg" style="color:var(--pink-deep)">
                        Tanya apapun
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>

            <div class="reveal">
                <h2 class="text-center tk-display text-2xl mb-10">FAQ</h2>
                <div class="space-y-0">
                    @php
                        $faqs = [
                            ['Apakah TraKerja gratis?', 'Ya! TraKerja menawarkan paket Gratis yang lengkap dengan Kanban Board, Smart Reminders, dan Goal Tracking untuk hingga 25 lamaran. Untuk kebutuhan lebih besar, upgrade ke Premium.'],
                            ['Bagaimana cara menggunakan TraKerja?', 'Daftar akun gratis, tambahkan lamaran kerja Anda, dan TraKerja otomatis mengorganisirnya dalam Kanban Board serta mengingatkan follow-up. Setup hanya 2 menit.'],
                            ['Data saya aman di TraKerja?', 'Keamanan data adalah prioritas kami. Semua data dienkripsi end-to-end dan dibackup otomatis.'],
                            ['Bisakah saya akses dari berbagai device?', 'Bisa. TraKerja berbasis cloud sehingga tersinkronisasi real-time di semua device dan browser Anda.'],
                            ['Apa keuntungan dibanding spreadsheet?', 'Kanban Board visual, Analytics Dashboard, Smart Reminders otomatis, Goal Tracking, dan sinkronisasi real-time — jauh lebih efisien daripada spreadsheet manual.'],
                        ];
                    @endphp
                    @foreach ($faqs as $faq)
                        <div class="border-b border-black/[0.08]">
                            <button class="faq-question w-full py-5 text-left flex items-center justify-between" onclick="toggleFaq(this)">
                                <span class="font-bold text-base">{{ $faq[0] }}</span>
                                <svg class="w-5 h-5 faq-icon transition-transform duration-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div class="faq-answer hidden pb-6 text-[color:var(--ink-soft)] text-sm leading-relaxed pr-8">{{ $faq[1] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ============ FOOTER — Notion-style multi-column ============ --}}
    <footer class="bg-[var(--bg-soft)] pt-16 pb-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 pb-12 border-b border-black/[0.08]">
                <div class="col-span-2 md:col-span-1">
                    <div class="flex items-center gap-2 mb-3">
                        <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-7 h-7" onerror="this.style.display='none';">
                        <span class="text-lg font-extrabold">TraKerja</span>
                    </div>
                    <p class="text-sm text-[color:var(--ink-soft)] leading-relaxed">Platform pelacakan lamaran kerja untuk pencari kerja Indonesia.</p>
                </div>
                <div>
                    <p class="tk-eyebrow mb-4 text-[color:var(--ink-faint)]">Produk</p>
                    <ul class="space-y-2.5 text-sm text-[color:var(--ink-soft)]">
                        <li><a href="#fitur" class="hover:text-[color:var(--ink)]">Fitur</a></li>
                        <li><a href="#pricing" class="hover:text-[color:var(--ink)]">Harga</a></li>
                        <li><a href="#testimonials" class="hover:text-[color:var(--ink)]">Testimoni</a></li>
                    </ul>
                </div>
                <div>
                    <p class="tk-eyebrow mb-4 text-[color:var(--ink-faint)]">Bantuan</p>
                    <ul class="space-y-2.5 text-sm text-[color:var(--ink-soft)]">
                        <li><a href="#faq" class="hover:text-[color:var(--ink)]">FAQ</a></li>
                        <li><a href="mailto:trakerja@teknalogi.id" class="hover:text-[color:var(--ink)]">Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <p class="tk-eyebrow mb-4 text-[color:var(--ink-faint)]">Perusahaan</p>
                    <ul class="space-y-2.5 text-sm text-[color:var(--ink-soft)]">
                        <li><a href="https://www.instagram.com/teknalogi.id/" target="_blank" rel="noopener" class="hover:text-[color:var(--ink)]">Instagram</a></li>
                        <li><a href="https://www.linkedin.com/company/teknalogi/" target="_blank" rel="noopener" class="hover:text-[color:var(--ink)]">LinkedIn</a></li>
                    </ul>
                </div>
            </div>
            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-[color:var(--ink-faint)]">
                <p>© 2025 TraKerja — PT Teknalogi Transformasi Digital</p>
                <p>Untuk pencari kerja di Indonesia</p>
            </div>
        </div>
    </footer>

    {{-- ============ CONTACT WIDGET ============ --}}
    <button id="contact-fab" onclick="openContactWidget()" class="fixed bottom-6 right-6 z-[90] w-14 h-14 rounded-full shadow-2xl flex items-center justify-center hover:scale-105 transition-all duration-300 group" style="background:var(--cta);" aria-label="Hubungi Kami">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
    </button>

    <div id="contact-widget" class="fixed bottom-24 right-6 z-[90] w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-black/[0.08] overflow-hidden transform scale-95 opacity-0 pointer-events-none transition-all duration-300 origin-bottom-right">
        <div class="px-5 py-4 flex items-center justify-between" style="background:var(--cta);">
            <span class="text-white font-bold text-sm">Hubungi kami</span>
            <button onclick="closeContactWidget()" class="text-white/70 hover:text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/></svg></button>
        </div>

        <div id="contact-screen-1" class="p-6">
            <h3 class="text-xl font-bold mb-1">Halo! 👋</h3>
            <p class="text-[color:var(--ink-soft)] text-sm mb-5">Ada yang bisa kami bantu?</p>
            <div class="space-y-0 divide-y divide-black/[0.06]">
                <button onclick="showContactTopic('form', 'Ingin berlangganan baru atau perpanjang?')" class="contact-topic-btn w-full text-left py-4 flex items-center justify-between font-medium text-sm hover:text-[color:var(--pink-deep)] transition-colors">Ingin berlangganan baru atau perpanjang?<svg class="w-4 h-4 text-[color:var(--ink-faint)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
                <button onclick="showContactTopic('form', 'Pertanyaan tentang paket berlangganan')" class="contact-topic-btn w-full text-left py-4 flex items-center justify-between font-medium text-sm hover:text-[color:var(--pink-deep)] transition-colors">Punya pertanyaan tentang paket?<svg class="w-4 h-4 text-[color:var(--ink-faint)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
                <button onclick="showContactTopic('whatsapp', '')" class="contact-topic-btn w-full text-left py-4 flex items-center justify-between font-medium text-sm hover:text-[color:var(--pink-deep)] transition-colors">Kerja sama atau partnership?<svg class="w-4 h-4 text-[color:var(--ink-faint)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
                <button onclick="showContactTopic('form', 'Ada masalah atau butuh bantuan')" class="contact-topic-btn w-full text-left py-4 flex items-center justify-between font-medium text-sm hover:text-[color:var(--pink-deep)] transition-colors">Ada masalah atau butuh bantuan?<svg class="w-4 h-4 text-[color:var(--ink-faint)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
            </div>
        </div>

        <div id="contact-screen-2" class="hidden p-6">
            <button onclick="backToScreen1()" class="flex items-center gap-1 text-[color:var(--ink-faint)] hover:text-[color:var(--ink)] text-sm mb-5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>Kembali</button>
            <form id="contact-form" onsubmit="submitContactForm(event)" class="space-y-3">
                @csrf
                <input type="hidden" id="contact-subject-hidden" name="subject" value="">
                <div><label class="block text-xs font-semibold text-[color:var(--ink-soft)] mb-1">Nama <span class="text-red-500">*</span></label><input type="text" name="name" id="contact-name" required placeholder="Nama lengkap Anda" class="w-full px-3 py-2.5 text-sm border border-black/[0.1] rounded-lg focus:ring-2 focus:outline-none" style="--tw-ring-color: var(--pink);"></div>
                <div><label class="block text-xs font-semibold text-[color:var(--ink-soft)] mb-1">Email <span class="text-red-500">*</span></label><input type="email" name="email" id="contact-email" required placeholder="email@contoh.com" class="w-full px-3 py-2.5 text-sm border border-black/[0.1] rounded-lg focus:ring-2 focus:outline-none" style="--tw-ring-color: var(--pink);"></div>
                <div><label class="block text-xs font-semibold text-[color:var(--ink-soft)] mb-1">Pesan <span class="text-red-500">*</span></label><textarea name="message" id="contact-message" required rows="4" placeholder="Tulis pesan Anda di sini..." class="w-full px-3 py-2.5 text-sm border border-black/[0.1] rounded-lg focus:ring-2 focus:outline-none resize-none" style="--tw-ring-color: var(--pink);"></textarea></div>
                <div id="contact-error" class="hidden text-xs text-red-600 bg-red-50 border border-red-100 px-3 py-2 rounded-lg"></div>
                <button type="submit" id="contact-submit-btn" class="btn-solid w-full py-3 rounded-xl font-bold text-sm flex items-center justify-center gap-2 disabled:opacity-60">
                    <span id="contact-btn-text">Kirim Pesan</span>
                    <svg id="contact-btn-spinner" class="hidden w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>
                </button>
            </form>
        </div>

        <div id="contact-screen-3" class="hidden p-6">
            <button onclick="backToScreen1()" class="flex items-center gap-1 text-[color:var(--ink-faint)] hover:text-[color:var(--ink)] text-sm mb-5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>Kembali</button>
            <p class="text-[color:var(--ink-soft)] text-sm mb-5 leading-relaxed">Hubungi tim kami langsung melalui WhatsApp.</p>
            <a href="https://wa.me/6281234567890?text=Halo%20TraKerja%2C%20saya%20ingin%20menjadwalkan%20demo" target="_blank" rel="noopener" class="flex items-center gap-3 w-full bg-[var(--bg-soft)] hover:bg-green-50 border border-black/[0.08] hover:border-green-200 rounded-xl px-4 py-4 transition-all group">
                <svg class="w-8 h-8 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                <span class="font-semibold text-sm group-hover:text-green-700">Chat dengan tim TraKerja</span>
            </a>
        </div>

        <div id="contact-screen-success" class="hidden p-8 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4"><svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>
            <h4 class="text-lg font-bold mb-2">Pesan Terkirim!</h4>
            <p class="text-sm text-[color:var(--ink-soft)] mb-5">Kami akan menghubungi Anda di email yang diberikan dalam 1x24 jam.</p>
            <button onclick="backToScreen1()" class="text-sm font-semibold" style="color:var(--pink-deep)">Kembali ke menu utama</button>
        </div>
    </div>

    @livewireScripts

    <script>
        // ---------- Scroll reveal (single, restrained animation system) ----------
        (function () {
            if (!('IntersectionObserver' in window)) {
                document.querySelectorAll('.reveal, .reveal-stagger').forEach(el => el.classList.add('visible'));
                return;
            }
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { rootMargin: '0px 0px -60px 0px', threshold: 0.08 });
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.reveal, .reveal-stagger').forEach(el => observer.observe(el));
            });
        })();

        // ---------- FAQ ----------
        function toggleFaq(button) {
            const answer = button.nextElementSibling;
            const icon = button.querySelector('.faq-icon');
            const isOpen = !answer.classList.contains('hidden');
            document.querySelectorAll('.faq-answer').forEach(item => item.classList.add('hidden'));
            document.querySelectorAll('.faq-icon').forEach(item => item.style.transform = 'rotate(0deg)');
            if (!isOpen) {
                answer.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            }
        }

        // ---------- How it works ----------
        function toggleHowItWorks(button) {
            const step = parseInt(button.getAttribute('data-step'));
            const allItems = document.querySelectorAll('.how-it-works-item');
            const allContents = document.querySelectorAll('.how-it-works-content');
            const allIcons = document.querySelectorAll('.how-it-works-icon');

            allItems.forEach((item, i) => {
                const n = i + 1;
                const content = allContents[i];
                const icon = allIcons[i];
                const dot = document.getElementById('hiw-dot-' + n);
                if (n === step) {
                    if (!content.classList.contains('hidden')) return;
                    item.style.borderColor = 'var(--pink)';
                    item.style.borderWidth = '2px';
                    content.classList.remove('hidden');
                    icon.style.transform = 'rotate(180deg)';
                    if (dot) { dot.style.width = '20px'; dot.style.background = 'var(--pink)'; }
                    changeHowItWorksImage(n);
                } else {
                    item.style.borderColor = '';
                    item.style.borderWidth = '';
                    content.classList.add('hidden');
                    icon.style.transform = 'rotate(0deg)';
                    if (dot) { dot.style.width = '8px'; dot.style.background = 'rgba(15,15,15,0.15)'; }
                }
            });
        }

        function changeHowItWorksImage(step) {
            document.querySelectorAll('.how-it-works-image').forEach(img => {
                img.style.opacity = '0';
                setTimeout(() => img.classList.add('hidden'), 250);
            });
            setTimeout(() => {
                const target = document.getElementById('how-it-works-img-' + step);
                if (target) {
                    target.classList.remove('hidden');
                    setTimeout(() => { target.style.opacity = '1'; }, 30);
                }
            }, 250);
        }

        // ---------- Testimonial carousel ----------
        let currentTesti = 0;
        const totalTesti = 3;
        function moveTestimonial(direction) {
            currentTesti = (currentTesti + direction + totalTesti) % totalTesti;
            const track = document.getElementById('testimonial-track');
            if (track) track.style.transform = `translateX(-${currentTesti * 100}%)`;
            const counter = document.getElementById('testi-counter');
            if (counter) counter.innerText = `0${currentTesti + 1} / 0${totalTesti}`;
        }

        // ---------- Welcome popup ----------
        document.addEventListener('DOMContentLoaded', function () {
            const popup = document.getElementById('welcome-popup');
            const popupContent = document.getElementById('welcome-popup-content');
            setTimeout(() => {
                popup.classList.remove('hidden');
                void popup.offsetWidth;
                popup.classList.remove('opacity-0');
                popupContent.classList.remove('scale-95');
                popupContent.classList.add('scale-100');
            }, 500);
            popup.addEventListener('click', function (e) { if (e.target === popup) closeWelcomePopup(); });
        });
        function closeWelcomePopup() {
            const popup = document.getElementById('welcome-popup');
            const popupContent = document.getElementById('welcome-popup-content');
            popup.classList.add('opacity-0');
            popupContent.classList.remove('scale-100');
            popupContent.classList.add('scale-95');
            setTimeout(() => popup.classList.add('hidden'), 300);
        }

        // ---------- Contact widget ----------
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
                .forEach(id => document.getElementById(id).classList.add('hidden'));
        }
        function resetForm() {
            const form = document.getElementById('contact-form');
            if (form) form.reset();
            document.getElementById('contact-error').classList.add('hidden');
            setSubmitLoading(false);
        }
        function setSubmitLoading(loading) {
            const btn = document.getElementById('contact-submit-btn');
            const text = document.getElementById('contact-btn-text');
            const spinner = document.getElementById('contact-btn-spinner');
            btn.disabled = loading;
            text.textContent = loading ? 'Mengirim...' : 'Kirim Pesan';
            spinner.classList.toggle('hidden', !loading);
        }
        async function submitContactForm(event) {
            event.preventDefault();
            const errorEl = document.getElementById('contact-error');
            errorEl.classList.add('hidden');
            setSubmitLoading(true);
            const form = event.target;
            const formData = new FormData(form);
            try {
                const response = await fetch('{{ route("contact.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                });
                const data = await response.json();
                if (data.success) {
                    hideAllScreens();
                    document.getElementById('contact-screen-success').classList.remove('hidden');
                } else {
                    errorEl.textContent = data.message || 'Terjadi kesalahan. Silakan coba lagi.';
                    errorEl.classList.remove('hidden');
                }
            } catch (err) {
                errorEl.textContent = 'Gagal mengirim pesan. Periksa koneksi internet Anda.';
                errorEl.classList.remove('hidden');
            } finally {
                setSubmitLoading(false);
            }
        }
        document.addEventListener('click', function (e) {
            const widget = document.getElementById('contact-widget');
            const fab = document.getElementById('contact-fab');
            if (widget && fab && !widget.contains(e.target) && !fab.contains(e.target)) {
                if (!widget.classList.contains('pointer-events-none')) closeContactWidget();
            }
        });

        // ---------- PWA service worker ----------
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(() => {});
            });
        }
    </script>
</body>
</html>
