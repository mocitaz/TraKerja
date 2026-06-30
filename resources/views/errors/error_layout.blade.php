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
        .dot-grid {
            background-image: radial-gradient(#e2e8f0 1.2px, transparent 1.2px);
            background-size: 20px 20px;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased text-zinc-650 selection:bg-zinc-200 selection:text-zinc-800 flex items-center justify-center min-h-screen relative bg-[#fafafa]">

    <!-- Clean Dot Grid Background for a subtle tech vibe -->
    <div class="absolute inset-0 z-0 dot-grid opacity-60 pointer-events-none"></div>

    <div class="relative z-10 w-full max-w-[430px] px-6 py-12 flex flex-col">
        
        <!-- Header -->
        <div class="flex items-center space-x-2.5 mb-6">
            <div class="w-7 h-7 bg-white rounded-lg border border-zinc-200/80 flex items-center justify-center p-1.5 shadow-[0_1px_2px_rgba(0,0,0,0.03)]">
                <img src="{{ asset('images/icon.png') }}" alt="TraKerja Logo" class="w-full h-full object-contain">
            </div>
            <span class="text-xs font-bold text-zinc-800 tracking-tight">TraKerja</span>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl p-7 sm:p-8 border border-zinc-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.02),0_16px_36px_-6px_rgba(0,0,0,0.03)] relative overflow-hidden">
            
            <!-- Tech Terminal style command line -->
            <div class="flex items-center space-x-2 font-mono text-[10px] text-zinc-500 bg-zinc-50 border border-zinc-200/60 rounded-xl px-3.5 py-2.5 mb-6">
                <span class="text-emerald-500 font-bold select-none">$</span>
                <span class="flex-1">trakerja --error=@yield('code')</span>
                <span class="w-1.5 h-3 bg-zinc-400 animate-[pulse_1s_infinite]"></span>
            </div>

            <!-- Title & Icon row -->
            <div class="flex items-start justify-between gap-4 mb-3">
                <h1 class="text-xl font-bold text-zinc-800 tracking-tight leading-tight">
                    @yield('title')
                </h1>
                <div class="w-10 h-10 rounded-xl bg-zinc-50 border border-zinc-200/60 flex items-center justify-center text-zinc-400 shrink-0">
                    <i class="ph @yield('icon') text-xl"></i>
                </div>
            </div>
            
            <!-- Description -->
            <p class="text-[12.5px] text-zinc-500 font-medium leading-relaxed mb-6 text-left">
                @yield('description')
            </p>

            <div class="border-t border-zinc-100 my-5"></div>
            
            <!-- Command Palette Keyboard Navigation (Keren & Unik) -->
            <div class="space-y-2">
                <span class="block text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-3">Pintasan Navigasi</span>
                
                <!-- Home Action -->
                <a href="{{ url('/') }}" class="flex items-center justify-between px-4 py-3 bg-zinc-50 hover:bg-zinc-900 text-zinc-700 hover:text-white border border-zinc-200/80 rounded-xl transition-all duration-150 group">
                    <div class="flex items-center space-x-2.5">
                        <i class="ph ph-house text-zinc-400 group-hover:text-zinc-300 text-base"></i>
                        <span class="text-xs font-bold">Pergi ke Beranda</span>
                    </div>
                    <kbd class="font-mono text-[9px] font-bold text-zinc-400 group-hover:text-zinc-500 bg-white group-hover:bg-zinc-850 border border-zinc-250 group-hover:border-zinc-700 rounded px-1.5 py-0.5 shadow-[0_1px_1px_rgba(0,0,0,0.03)] select-none">H</kbd>
                </a>
                
                <!-- Back Action -->
                <button onclick="window.history.back()" class="w-full flex items-center justify-between px-4 py-3 bg-zinc-50 hover:bg-zinc-900 text-zinc-700 hover:text-white border border-zinc-200/80 rounded-xl transition-all duration-150 group">
                    <div class="flex items-center space-x-2.5">
                        <i class="ph ph-arrow-left text-zinc-400 group-hover:text-zinc-300 text-base"></i>
                        <span class="text-xs font-bold">Kembali ke Halaman Sebelumnya</span>
                    </div>
                    <kbd class="font-mono text-[9px] font-bold text-zinc-400 group-hover:text-zinc-500 bg-white group-hover:bg-zinc-850 border border-zinc-250 group-hover:border-zinc-700 rounded px-1.5 py-0.5 shadow-[0_1px_1px_rgba(0,0,0,0.03)] select-none">B</kbd>
                </button>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="mt-6 text-left px-1 flex justify-between items-center text-[9px] font-semibold text-zinc-400">
            <span>TraKerja &copy; {{ date('Y') }}</span>
            <span>Butuh bantuan? <a href="mailto:support@trakerja.com" class="text-zinc-500 hover:text-zinc-800 underline underline-offset-2 transition-colors">Hubungi kami</a></span>
        </div>
        
    </div>

    <!-- Keyboard Shortcuts Script (Goks!) -->
    <script>
        document.addEventListener('keydown', function(event) {
            // Ignore if user is typing in input fields
            if (event.target.tagName === 'INPUT' || event.target.tagName === 'TEXTAREA') {
                return;
            }
            
            const key = event.key.toLowerCase();
            if (key === 'h') {
                window.location.href = "{{ url('/') }}";
            } else if (key === 'b') {
                window.history.back();
            }
        });
    </script>

</body>
</html>
