<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Terms of Service - TraKerja</title>
    <meta name="description" content="TraKerja Terms of Service - Our terms of service built on transparency and integrity.">
    
    <!-- Fonts Bunny: Inter + Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,850,900|plus-jakarta-sans:700,800" rel="stylesheet" />
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body {
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            background-color: #FFFFFF;
            color: #1A1A1A;
        }
        .notion-h1 {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            letter-spacing: -0.03em;
            font-weight: 800;
            line-height: 1.1;
        }
        .bento-card {
            border: 1px solid #E3E2E0;
            border-radius: 12px;
            background: #FFFFFF;
            transition: all 0.2s ease;
        }
        .bento-card:hover {
            border-color: rgba(26, 26, 26, 0.2);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
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
                    <a href="{{ url('/') }}#fitur" class="px-3 py-1.5 rounded hover:bg-zinc-100 hover:text-zinc-900 transition-colors">Features</a>
                    <a href="{{ url('/') }}#pricing" class="px-3 py-1.5 rounded hover:bg-zinc-100 hover:text-zinc-900 transition-colors">Pricing</a>
                    <a href="{{ url('/') }}#testimonials" class="px-3 py-1.5 rounded hover:bg-zinc-100 hover:text-zinc-900 transition-colors">Testimonials</a>
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
    <section class="relative pt-28 pb-16 sm:pt-36 sm:pb-20 overflow-hidden border-b border-zinc-100 bg-white">
        <div class="max-w-5xl mx-auto px-4 text-center">
            <!-- Notion-style line-art branding icons -->
            <div class="flex justify-center -space-x-3 mb-6">
                <!-- Scale Icon (Blue circle) -->
                <div class="w-11 h-11 rounded-full border-2 border-[#0066cc] bg-white flex items-center justify-center shadow-3xs shrink-0 select-none z-20">
                    <i class="ph-bold ph-scales text-zinc-800 text-lg"></i>
                </div>
                <!-- File Icon (White circle, black border) -->
                <div class="w-11 h-11 rounded-full border-2 border-zinc-950 bg-white flex items-center justify-center shadow-3xs shrink-0 select-none z-10">
                    <i class="ph-bold ph-file-text text-zinc-800 text-lg"></i>
                </div>
            </div>

            <h1 class="notion-h1 text-4xl sm:text-5xl text-zinc-950 mb-6 leading-[1.1] tracking-tight">
                Terms of Service
            </h1>

            <p class="text-sm sm:text-base text-zinc-500 max-w-xl mx-auto mb-8 font-medium leading-relaxed">
                Transparency and trust are the foundation of TraKerja. We are committed to protecting your rights and privacy.
            </p>

            <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-zinc-50 border border-zinc-200 rounded-full text-[10px] font-bold text-zinc-400 uppercase tracking-wider">
                <span>Last updated: {{ date('d F Y') }}</span>
            </div>
        </div>
    </section>

    {{-- ============ CONTENT ============ --}}
    <section class="py-16 bg-white">
        <div class="max-w-3xl mx-auto px-6">
            <div class="space-y-12 text-xs font-semibold text-zinc-650 leading-relaxed">
                
                <!-- 1. Acceptance of Terms -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-800 shrink-0 shadow-3xs">
                            <i class="ph ph-handshake text-base"></i>
                        </div>
                        <h2 class="text-sm font-black text-zinc-900 uppercase tracking-wider">
                            1. Acceptance of Terms
                        </h2>
                    </div>
                    <p class="pl-11">
                        By accessing, registering, or using the TraKerja platform ("Service"), you express your agreement to be bound by these Terms of Service. If you do not agree to comply with any part of these terms, please do not continue using this platform.
                    </p>
                </div>

                <!-- 2. Description of Service -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-800 shrink-0 shadow-3xs">
                            <i class="ph ph-circles-four text-base"></i>
                        </div>
                        <h2 class="text-sm font-black text-zinc-900 uppercase tracking-wider">
                            2. Description of Service
                        </h2>
                    </div>
                    <div class="pl-11 space-y-2">
                        <p>
                            TraKerja provides a digital workspace for job seekers in Indonesia to:
                        </p>
                        <ul class="list-disc pl-5 space-y-1 text-zinc-500">
                            <li>Manage lists and track statuses of job applications.</li>
                            <li>Analyze ATS-friendly CV matching metrics using Artificial Intelligence (AI).</li>
                            <li>Create custom cover letters automatically.</li>
                            <li>Monitor job search performance through visual analytics.</li>
                        </ul>
                    </div>
                </div>

                <!-- 3. User Accounts -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-800 shrink-0 shadow-3xs">
                            <i class="ph ph-user-circle text-base"></i>
                        </div>
                        <h2 class="text-sm font-black text-zinc-900 uppercase tracking-wider">
                            3. User Accounts
                        </h2>
                    </div>
                    <p class="pl-11">
                        You are fully responsible for maintaining the security of your account credentials (password and email). You agree to provide accurate information and immediately notify us of any security breaches or unauthorized account usage.
                    </p>
                </div>

                <!-- 4. Acceptable Use -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-800 shrink-0 shadow-3xs">
                            <i class="ph ph-prohibit text-base"></i>
                        </div>
                        <h2 class="text-sm font-black text-zinc-900 uppercase tracking-wider">
                            4. Acceptable Use
                        </h2>
                    </div>
                    <div class="pl-11 space-y-2">
                        <p>You agree not to:</p>
                        <ul class="list-disc pl-5 space-y-1 text-zinc-500">
                            <li>Use the platform for any illegal or fraudulent purposes.</li>
                            <li>Attempt to damage, disrupt, or hack our server infrastructure.</li>
                            <li>Transmit viruses, malware, or spam through the platform services.</li>
                        </ul>
                    </div>
                </div>

                <!-- 5. Security & Privacy Guarantees -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-800 shrink-0 shadow-3xs">
                            <i class="ph ph-shield-check text-base"></i>
                        </div>
                        <h2 class="text-sm font-black text-zinc-900 uppercase tracking-wider">
                            5. Security & Privacy Guarantees
                        </h2>
                    </div>
                    <div class="pl-11 space-y-4">
                        <p>
                            We are committed to keeping your personal data secure. All your job application tracker logs are protected with modern, military-grade encryption (AES-256) and we strictly follow a zero data selling policy.
                        </p>
                        <div class="p-4 bg-zinc-50 border border-zinc-200 rounded-lg shadow-3xs">
                            <p class="text-zinc-850">
                                <strong>Legal Compliance:</strong> These terms are governed by and construed in accordance with the laws of the Republic of Indonesia. Any legal disputes shall be settled under the exclusive jurisdiction of the Indonesian courts.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 6. Contact Us -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-800 shrink-0 shadow-3xs">
                            <i class="ph ph-chat-centered-text text-base"></i>
                        </div>
                        <h2 class="text-sm font-black text-zinc-900 uppercase tracking-wider">
                            6. Contact Us
                        </h2>
                    </div>
                    <p class="pl-11">
                        If you have any questions regarding these Terms of Service, please contact our support team via our official email address at <a href="mailto:trakerja@teknalogi.id" class="text-zinc-800 underline underline-offset-2 hover:text-zinc-900 font-bold">trakerja@teknalogi.id</a>.
                    </p>
                </div>

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
                        <li><a href="{{ url('/') }}#fitur" class="hover:text-zinc-800 transition-colors">Features</a></li>
                        <li><a href="{{ url('/') }}#pricing" class="hover:text-zinc-800 transition-colors">Pricing Plans</a></li>
                        <li><a href="{{ url('/') }}#testimonials" class="hover:text-zinc-800 transition-colors">Testimonials</a></li>
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
</body>
</html>

