<!DOCTYPE html>
<html lang="{ str_replace('_', '-', app()->getLocale()) }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Halaman Tidak Ditemukan | TraKerja</title>
    <link rel="icon" type="image/png" href="{ asset('favicon.png') }">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-slate-900 bg-slate-50 selection:bg-primary-500 selection:text-white">

    <!-- Ambient Backgrounds -->
    <div class="fixed inset-0 w-full h-full -z-10 overflow-hidden pointer-events-none">
        <div class="absolute top-[-20%] left-[-10%] w-[50%] h-[50%] rounded-full bg-indigo-500/10 blur-[120px]"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[50%] h-[50%] rounded-full bg-primary-500/10 blur-[120px]"></div>
    </div>

    <div class="relative min-h-screen flex flex-col items-center justify-center p-4 sm:p-8">
        
        <div class="w-full max-w-2xl">
            <!-- Premium Glass Card -->
            <div class="bg-white/80 backdrop-blur-xl rounded-[2.5rem] p-8 sm:p-14 shadow-[0_8px_40px_rgb(0,0,0,0.04)] border border-white text-center relative overflow-hidden group/card transition-all hover:shadow-[0_8px_40px_rgb(0,0,0,0.08)] hover:border-slate-100">
                
                <!-- Decorative Top Edge Glow -->
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-3/4 h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>
                
                <!-- Icon Container -->
                <div class="w-28 h-28 sm:w-36 sm:h-36 mx-auto bg-slate-50 rounded-full flex items-center justify-center mb-10 border border-slate-100 shadow-inner relative group/icon">
                    <!-- Ripple Effect on Hover -->
                    <div class="absolute inset-0 bg-amber-500/5 rounded-full scale-0 group-hover/icon:scale-[1.8] transition-transform duration-700 ease-out"></div>
                    <i class="ph-duotone ph-ghost text-[4rem] sm:text-[5rem] text-slate-400 relative z-10 group-hover/icon:text-amber-500 transition-colors duration-300"></i>
                </div>
                
                <!-- Error Badge -->
                <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-slate-50 border border-slate-200 shadow-sm mb-6 hover:bg-slate-100 transition-colors cursor-default">
                    <div class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></div>
                    <span class="text-xs font-black text-slate-600 tracking-widest uppercase">ERROR 404</span>
                </div>
                
                <!-- Typography -->
                <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-4">
                    Halaman Tidak Ditemukan
                </h1>
                
                <p class="text-slate-500 font-medium leading-relaxed max-w-lg mx-auto mb-10 sm:text-lg">
                    Maaf, sepertinya rute yang Anda tuju telah dipindahkan, dihapus, atau mungkin tidak pernah ada di server kami.
                </p>
                
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <button onclick="window.history.back()" class="w-full sm:w-auto px-8 py-4 bg-white border-2 border-slate-200 text-slate-700 rounded-2xl hover:bg-slate-50 hover:border-slate-300 hover:text-slate-900 transition-all font-bold text-sm shadow-sm flex items-center justify-center gap-3">
                        <i class="ph-bold ph-arrow-left text-lg"></i>
                        Kembali Sebelumnya
                    </button>
                    <a href="{ url('/') }" class="w-full sm:w-auto px-8 py-4 bg-slate-900 text-white rounded-2xl hover:bg-slate-800 transition-all font-bold text-sm shadow-xl shadow-slate-900/20 flex items-center justify-center gap-3 hover:-translate-y-1 active:translate-y-0">
                        <i class="ph-bold ph-house text-lg"></i>
                        Beranda Utama
                    </a>
                </div>
            </div>
            
            <!-- Footer Branding -->
            <div class="mt-12 flex flex-col items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
                    <span class="font-extrabold text-xl">T</span>
                </div>
                <div class="text-center space-y-1">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest">TraKerja &copy; { date('Y') }</p>
                    <p class="text-xs font-bold text-slate-400">Pusat Bantuan: <a href="mailto:support@trakerja.id" class="text-primary-500 hover:text-primary-600 transition-colors">support@trakerja.id</a></p>
                </div>
            </div>
            
        </div>
    </div>
</body>
</html>
