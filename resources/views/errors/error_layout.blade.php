<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code') - @yield('title') | TraKerja</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('images/icon.png') }}?v=2">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Outfit:400,500,600,700,800|Plus+Jakarta+Sans:300,400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- App assets (Tailwind CSS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0b0c10;
        }
        h1, .font-display {
            font-family: 'Outfit', sans-serif;
        }
        .grain {
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
        }
        .animate-float-slow {
            animation: floatSlow 8s ease-in-out infinite;
        }
        .animate-float-delayed {
            animation: floatSlow 8s ease-in-out infinite;
            animation-delay: 3s;
        }
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased text-zinc-300 selection:bg-indigo-500 selection:text-white flex items-center justify-center min-h-screen relative overflow-hidden bg-[#07080d]">

    <!-- Ambient Glowing Gradients -->
    <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
        <!-- Top glow -->
        <div class="absolute -top-[20%] left-1/2 -translate-x-1/2 w-[600px] sm:w-[900px] h-[400px] bg-indigo-600/15 rounded-full blur-[120px] animate-pulse" style="animation-duration: 8s;"></div>
        <!-- Bottom glow -->
        <div class="absolute -bottom-[20%] left-1/2 -translate-x-1/2 w-[500px] sm:w-[700px] h-[350px] bg-violet-600/15 rounded-full blur-[100px] animate-pulse" style="animation-duration: 10s;"></div>
        <!-- Floating neon blur particles -->
        <div class="absolute top-[25%] left-[15%] w-72 h-72 bg-purple-500/5 rounded-full blur-[60px] animate-float-slow"></div>
        <div class="absolute bottom-[25%] right-[15%] w-80 h-80 bg-indigo-500/5 rounded-full blur-[70px] animate-float-delayed"></div>
        <!-- Noise Overlay -->
        <div class="absolute inset-0 grain opacity-[0.025] mix-blend-overlay"></div>
    </div>

    <div class="relative z-10 w-full max-w-[460px] px-6 py-12 flex flex-col items-center">
        
        <!-- Logo -->
        <div class="mb-8 relative group">
            <div class="absolute inset-0 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-2xl blur-md opacity-25 group-hover:opacity-40 transition-opacity duration-300"></div>
            <div class="w-16 h-16 bg-zinc-900/90 backdrop-blur-md rounded-2xl border border-zinc-800/80 flex items-center justify-center p-3.5 relative overflow-hidden transition-transform duration-300 group-hover:scale-105">
                <img src="{{ asset('images/icon.png') }}" alt="TraKerja Logo" class="w-full h-full object-contain">
            </div>
        </div>

        <!-- Main Glassmorphic Container -->
        <div class="w-full bg-zinc-950/40 backdrop-blur-2xl rounded-[2.25rem] p-8 sm:p-10 border border-zinc-800/60 shadow-[0_24px_70px_-10px_rgba(0,0,0,0.7)] text-center relative overflow-hidden group">
            <!-- Top indicator line -->
            <div class="absolute top-0 inset-x-0 h-[2px] bg-gradient-to-r from-transparent via-indigo-500/80 to-transparent"></div>
            
            <!-- Error Icon & Visual Circle -->
            <div class="mb-6 inline-flex items-center justify-center relative">
                <div class="absolute inset-0 bg-indigo-500/10 rounded-full blur-md animate-ping" style="animation-duration: 3s;"></div>
                <div class="w-20 h-20 rounded-2xl bg-zinc-900/90 border border-zinc-800 flex items-center justify-center text-indigo-400 shadow-inner relative z-10">
                    <i class="ph-fill @yield('icon') text-4xl"></i>
                </div>
            </div>
            
            <!-- Error Code & Badge -->
            <div class="mb-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-[10px] font-extrabold uppercase tracking-widest text-indigo-400 mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-pulse"></span>
                    Error @yield('code')
                </span>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">
                    @yield('title')
                </h1>
            </div>
            
            <!-- Description -->
            <p class="text-[13px] text-zinc-400 font-medium leading-relaxed mb-8 px-2">
                @yield('description')
            </p>
            
            <!-- Actions Buttons -->
            <div class="space-y-3">
                <a href="{{ url('/') }}" class="w-full flex items-center justify-center gap-2.5 px-6 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl transition-all duration-200 font-bold text-sm shadow-lg shadow-indigo-600/15 active:scale-[0.98]">
                    <i class="ph-bold ph-house text-base"></i>
                    Kembali ke Beranda
                </a>
                <button onclick="window.history.back()" class="w-full flex items-center justify-center gap-2.5 px-6 py-3.5 bg-zinc-900 hover:bg-zinc-850 border border-zinc-800 hover:border-zinc-700 text-zinc-300 hover:text-white rounded-xl transition-all duration-200 font-bold text-sm active:scale-[0.98]">
                    <i class="ph-bold ph-arrow-left text-base"></i>
                    Kembali Halaman Sebelumnya
                </button>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="mt-10 text-center flex flex-col items-center gap-2">
            <span class="text-[9px] font-bold text-zinc-500 uppercase tracking-widest">TraKerja &copy; {{ date('Y') }}</span>
            <span class="text-[11px] font-semibold text-zinc-500">Butuh bantuan? <a href="mailto:support@trakerja.com" class="text-indigo-400 hover:text-indigo-300 transition-colors underline decoration-indigo-400/30 underline-offset-4">Hubungi Admin</a></span>
        </div>
        
    </div>

</body>
</html>
