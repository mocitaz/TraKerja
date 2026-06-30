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
    <link href="https://fonts.googleapis.com/css?family=Plus+Jakarta+Sans:300,400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- App assets (Tailwind CSS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #fafafa;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased text-zinc-650 selection:bg-zinc-200 selection:text-zinc-800 flex items-center justify-center min-h-screen relative bg-[#fafafa]">

    <div class="w-full max-w-[420px] px-6 py-12 flex flex-col">
        
        <!-- Logo Header (Notion-style, clean & compact) -->
        <div class="flex items-center space-x-2.5 mb-6">
            <div class="w-7 h-7 bg-white rounded-lg border border-zinc-200/80 flex items-center justify-center p-1.5 shadow-[0_1px_2px_rgba(0,0,0,0.03)]">
                <img src="{{ asset('images/icon.png') }}" alt="TraKerja Logo" class="w-full h-full object-contain">
            </div>
            <span class="text-xs font-bold text-zinc-800 tracking-tight">TraKerja</span>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl p-7 sm:p-8 border border-zinc-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.02),0_12px_24px_-4px_rgba(0,0,0,0.03)] relative overflow-hidden">
            
            <!-- Icon -->
            <div class="mb-5 flex items-center justify-start">
                <div class="w-10 h-10 rounded-xl bg-zinc-50 border border-zinc-200/60 flex items-center justify-center text-zinc-400">
                    <i class="ph @yield('icon') text-xl"></i>
                </div>
            </div>

            <!-- Error Code & Title -->
            <div class="mb-3 text-left">
                <span class="inline-block font-mono text-[9px] font-bold text-zinc-400 bg-zinc-100 border border-zinc-200/60 rounded px-1.5 py-0.5 tracking-wider uppercase mb-2.5">
                    ERROR @yield('code')
                </span>
                <h1 class="text-lg font-bold text-zinc-800 tracking-tight leading-tight">
                    @yield('title')
                </h1>
            </div>
            
            <!-- Description -->
            <p class="text-[12px] text-zinc-500 font-medium leading-relaxed mb-6 text-left">
                @yield('description')
            </p>
            
            <!-- Actions Buttons (Notion style, clean stacked or side-by-side) -->
            <div class="flex flex-col sm:flex-row gap-2">
                <a href="{{ url('/') }}" class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-zinc-900 hover:bg-zinc-800 text-white rounded-xl transition-all duration-150 font-bold text-xs shadow-xs active:scale-[0.98]">
                    <i class="ph-bold ph-house text-xs"></i>
                    Ke Beranda
                </a>
                <button onclick="window.history.back()" class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-white hover:bg-zinc-50 border border-zinc-200 text-zinc-600 hover:text-zinc-800 rounded-xl transition-all duration-150 font-bold text-xs active:scale-[0.98]">
                    <i class="ph-bold ph-arrow-left text-xs"></i>
                    Kembali
                </button>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="mt-6 text-left px-1 flex justify-between items-center text-[9px] font-semibold text-zinc-400">
            <span>TraKerja &copy; {{ date('Y') }}</span>
            <span>Butuh bantuan? <a href="mailto:support@trakerja.com" class="text-zinc-500 hover:text-zinc-800 underline underline-offset-2 transition-colors">Hubungi kami</a></span>
        </div>
        
    </div>

</body>
</html>
