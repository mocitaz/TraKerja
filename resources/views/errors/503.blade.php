<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>503 - Maintenance | TraKerja</title>
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-slate-900 bg-slate-50 selection:bg-primary-500 selection:text-white flex items-center justify-center min-h-screen relative overflow-hidden">

    <!-- Ambient Purple Gradient Background -->
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-primary-500/10 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-indigo-500/10 rounded-full blur-[100px]"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.03]"></div>
    </div>

    <div class="relative z-10 w-full max-w-[400px] px-5 py-12">
        
        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <div class="w-14 h-14 bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.06)] border border-slate-100 flex items-center justify-center p-3 relative overflow-hidden group">
                <div class="absolute inset-0 bg-primary-50/50 scale-0 group-hover:scale-100 transition-transform duration-300 rounded-2xl"></div>
                <img src="{{ asset('images/icon.png') }}" alt="TraKerja Logo" class="w-full h-full object-contain relative z-10">
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white/80 backdrop-blur-2xl rounded-[2rem] p-8 shadow-[0_8px_40px_rgb(0,0,0,0.04)] border border-white text-center relative hover:shadow-[0_8px_40px_rgb(0,0,0,0.08)] transition-all duration-300">
            
            <!-- Glow Accent -->
            <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-transparent via-blue-500 to-transparent opacity-50"></div>
            
            <!-- Error Icon -->
            <div class="mb-5 inline-flex items-center justify-center w-16 h-16 rounded-[1.25rem] bg-blue-50 text-blue-600 border border-blue-100 shadow-inner">
                <i class="ph-fill ph-wrench text-3xl"></i>
            </div>
            
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mb-2">
                Maintenance
            </h1>
            
            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-50 border border-slate-200 mb-4">
                <div class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></div>
                <span class="text-[10px] font-black text-slate-500 tracking-widest uppercase">Error 503</span>
            </div>
            
            <p class="text-[13px] text-slate-500 font-medium leading-relaxed mb-8 px-2">
                Sistem TraKerja sedang dalam pemeliharaan atau lonjakan traffic. Kami akan segera kembali.
            </p>
            
            <!-- Actions -->
            <div class="space-y-3">
                <a href="{{ url('/') }}" class="w-full flex items-center justify-center gap-2 px-6 py-3.5 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all font-bold text-sm shadow-md shadow-primary-500/20 active:scale-[0.98]">
                    <i class="ph-bold ph-house text-base"></i>
                    Ke Beranda Utama
                </a>
                <button onclick="window.history.back()" class="w-full flex items-center justify-center gap-2 px-6 py-3.5 bg-slate-50 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-100 hover:text-slate-900 hover:border-slate-300 transition-all font-bold text-sm shadow-sm active:scale-[0.98]">
                    <i class="ph-bold ph-arrow-left text-base"></i>
                    Kembali Sebelumnya
                </button>
            </div>
        </div>
        
        <!-- Footer Info -->
        <div class="mt-8 text-center flex flex-col items-center gap-1">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">TraKerja &copy; {{ date('Y') }}</p>
            <p class="text-[11px] font-bold text-slate-400">Butuh bantuan? <a href="mailto:trakerja@teknalogi.id" class="text-primary-500 hover:text-primary-600 transition-colors">Hubungi Support</a></p>
        </div>
        
    </div>
</body>
</html>
