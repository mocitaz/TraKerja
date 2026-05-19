<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-50 to-indigo-50 flex items-center justify-center border border-primary-100/50 shadow-inner relative overflow-hidden group">
                    <i class="ph-duotone ph-puzzle-piece text-xl text-primary-600 relative z-10 group-hover:scale-110 transition-transform"></i>
                </div>
                <div>
                    <h2 class="font-extrabold text-xl text-slate-800 tracking-tight">Chrome Extension</h2>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-0.5">Automation Tools</p>
                </div>
            </div>
            
            <a href="{{ asset('downloads/trakerja-extension.zip') }}" download class="hidden sm:flex items-center gap-2 px-5 py-2.5 bg-slate-900 text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/20 active:scale-95 group">
                <i class="ph-bold ph-download-simple group-hover:-translate-y-0.5 transition-transform"></i>
                Download .ZIP
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-12">
        
        <!-- PREMIUM HERO SECTION -->
        <div class="relative bg-slate-900 rounded-[2.5rem] md:rounded-[3rem] border border-slate-800 shadow-2xl overflow-hidden">
            <!-- Dynamic Background Effects -->
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 brightness-100 contrast-150 mix-blend-overlay"></div>
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary-500/30 rounded-full blur-[100px] pointer-events-none"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-indigo-500/30 rounded-full blur-[100px] pointer-events-none"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[400px] bg-emerald-500/10 rounded-[100%] blur-[120px] pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row items-center gap-12 lg:gap-8 p-10 md:p-16 lg:p-20">
                
                <!-- Left: Typography & CTA -->
                <div class="flex-1 text-center lg:text-left flex flex-col items-center lg:items-start">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/20 text-slate-300 text-[10px] font-black uppercase tracking-widest mb-8 backdrop-blur-md">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_8px_rgba(52,211,153,0.8)]"></span>
                        Stable Release 1.0.0
                    </div>
                    
                    <h1 class="text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-black text-white tracking-tight leading-[1.05] mb-6">
                        Simpan Loker <br class="hidden lg:block"/>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 via-indigo-400 to-purple-400">Dalam 1 Klik.</span>
                    </h1>
                    
                    <p class="text-base md:text-lg text-slate-400 font-medium leading-relaxed max-w-lg mb-10">
                        Otomatiskan pemindahan data lowongan kerja dari LinkedIn, Glints, JobStreet, Dealls, dan Kalibrr langsung ke Kanban Board Anda tanpa copy-paste.
                    </p>
                    
                    <div class="flex flex-col items-center lg:items-start w-full sm:w-auto">
                        <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
                            <a href="{{ asset('downloads/trakerja-extension.zip') }}" download class="w-full sm:w-auto flex items-center justify-center gap-3 px-8 py-4 bg-white text-slate-900 rounded-2xl font-bold transition-all shadow-[0_0_40px_rgba(255,255,255,0.1)] hover:shadow-[0_0_60px_rgba(255,255,255,0.2)] hover:scale-105 active:scale-95 group">
                                <i class="ph-bold ph-download-simple text-xl group-hover:-translate-y-0.5 transition-transform"></i>
                                Download Extension
                            </a>
                        </div>
                        <div class="mt-5 inline-flex items-center gap-2 text-slate-400 text-[11px] font-bold bg-white/5 px-3 py-1.5 rounded-lg border border-white/10">
                            <i class="ph-fill ph-desktop text-emerald-400 text-sm"></i>
                            Hanya didukung untuk PC / Laptop (Tidak bisa di HP)
                        </div>
                    </div>
                </div>

                <!-- Right: Visual CSS Mockup (No Image Needed) -->
                <div class="w-full lg:w-[45%] flex justify-center lg:justify-end relative perspective-1000">
                    <div class="relative w-full max-w-[320px] bg-slate-800/80 backdrop-blur-2xl rounded-3xl border border-white/10 shadow-[0_20px_60px_rgba(0,0,0,0.5)] p-1 rotate-y-[-10deg] rotate-x-[5deg] hover:rotate-0 transition-transform duration-700 ease-out">
                        <!-- Browser Header -->
                        <div class="h-10 bg-slate-900/50 rounded-t-[1.4rem] border-b border-white/5 flex items-center px-4 gap-2">
                            <div class="flex gap-1.5">
                                <div class="w-2.5 h-2.5 rounded-full bg-rose-500/80"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-amber-500/80"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500/80"></div>
                            </div>
                            <div class="flex-1 mx-4 h-5 bg-white/5 rounded-md flex items-center px-3 border border-white/5">
                                <i class="ph-fill ph-lock-key text-[10px] text-slate-500 mr-2"></i>
                                <div class="h-1.5 w-16 bg-slate-600/50 rounded-full"></div>
                            </div>
                            <i class="ph-fill ph-puzzle-piece text-primary-400 text-sm"></i>
                        </div>
                        <!-- Extension Body -->
                        <div class="p-6 space-y-5 bg-gradient-to-b from-slate-800 to-slate-900 rounded-b-[1.4rem]">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-primary-500/20 flex items-center justify-center border border-primary-500/30">
                                    <i class="ph-fill ph-briefcase text-primary-400 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-white font-bold text-sm">TraKerja Extension</h3>
                                    <p class="text-xs text-emerald-400 font-medium flex items-center gap-1 mt-0.5">
                                        <i class="ph-fill ph-check-circle"></i> Data Terbaca
                                    </p>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="space-y-1.5">
                                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Posisi</div>
                                    <div class="h-9 bg-white/5 border border-white/10 rounded-xl flex items-center px-3">
                                        <span class="text-sm text-white font-medium">Software Engineer</span>
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Perusahaan</div>
                                    <div class="h-9 bg-white/5 border border-white/10 rounded-xl flex items-center px-3">
                                        <span class="text-sm text-white font-medium">Tech Corp Inc.</span>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-2">
                                <div class="h-11 bg-primary-500 hover:bg-primary-400 rounded-xl flex items-center justify-center gap-2 cursor-pointer transition-colors shadow-lg shadow-primary-500/20">
                                    <i class="ph-bold ph-floppy-disk text-white text-lg"></i>
                                    <span class="text-sm text-white font-bold">Simpan ke Board</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Floating Badge -->
                        <div class="absolute -right-6 -bottom-6 bg-emerald-500 text-white px-4 py-3 rounded-2xl shadow-xl flex items-center gap-3 animate-bounce-slow border border-emerald-400">
                            <i class="ph-bold ph-magic-wand text-xl"></i>
                            <div class="text-left">
                                <p class="text-[9px] font-black uppercase tracking-widest text-emerald-200">AI Powered</p>
                                <p class="text-xs font-bold">Smart Scraper</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Logo Strip -->
            <div class="border-t border-white/10 bg-slate-900/50 backdrop-blur-md px-8 py-6 rounded-b-[2.5rem] md:rounded-b-[3rem] flex flex-wrap justify-center md:justify-between items-center gap-8 relative z-10">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] w-full md:w-auto text-center">Supported Platforms</p>
                <div class="flex flex-wrap justify-center items-center gap-8 md:gap-12 flex-1">
                    @foreach([
                        ['id' => 'linkedin.com', 'name' => 'LinkedIn'],
                        ['id' => 'glints.com', 'name' => 'Glints'],
                        ['id' => 'jobstreet.co.id', 'name' => 'JobStreet'],
                        ['id' => 'kalibrr.com', 'name' => 'Kalibrr'],
                        ['id' => 'usedeall.com', 'name' => 'Dealls'],
                        ['id' => 'talentics.id', 'name' => 'Talentics']
                    ] as $platform)
                    <div class="flex items-center gap-2 opacity-50 hover:opacity-100 transition-opacity cursor-default tooltip-target" data-tooltip="{{ $platform['name'] }}">
                        <img src="https://www.google.com/s2/favicons?domain={{ $platform['id'] }}&sz=64" class="w-5 h-5 object-contain grayscale hover:grayscale-0 transition-all" alt="{{ $platform['name'] }}" />
                        <span class="text-sm font-bold text-slate-300 hidden sm:block">{{ $platform['name'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- TWO COLUMNS: Info & Setup -->
        <div class="grid lg:grid-cols-2 gap-8">
            
            <!-- Features Left -->
            <div class="space-y-8">
                <!-- Smart AI -->
                <div class="bg-white rounded-[2rem] border border-slate-200/60 shadow-sm p-8 group hover:shadow-xl hover:shadow-primary-500/5 transition-all duration-300 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-8 opacity-5 group-hover:scale-150 transition-transform duration-700 pointer-events-none">
                        <i class="ph-fill ph-brain text-8xl text-primary-500"></i>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-primary-50 border border-primary-100 flex items-center justify-center mb-6">
                        <i class="ph-duotone ph-brain text-3xl text-primary-600"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800 mb-3 tracking-tight">Smart AI Scraper</h3>
                    <p class="text-slate-500 font-medium leading-relaxed">
                        Membaca Judul Posisi, Nama Perusahaan, dan Lokasi dengan akurasi tinggi. Dirancang untuk kebal terhadap perubahan UI mendadak (A/B testing) dari platform rekrutmen.
                    </p>
                </div>

                <!-- Usage Guide -->
                <div class="bg-white rounded-[2rem] border border-slate-200/60 shadow-sm p-8">
                    <h3 class="text-lg font-black text-slate-800 tracking-tight mb-6 flex items-center gap-2">
                        <i class="ph-fill ph-rocket-launch text-emerald-500"></i> Cara Pakai (3 Detik)
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center font-black text-slate-400 shadow-sm">1</div>
                            <p class="text-sm font-bold text-slate-700">Buka halaman detail lowongan</p>
                        </div>
                        <div class="flex items-center gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center font-black text-slate-400 shadow-sm">2</div>
                            <p class="text-sm font-bold text-slate-700">Klik ikon ekstensi TraKerja</p>
                        </div>
                        <div class="flex items-center gap-4 bg-primary-50 p-4 rounded-2xl border border-primary-100">
                            <div class="w-8 h-8 rounded-full bg-primary-600 flex items-center justify-center font-black text-white shadow-sm shadow-primary-500/30">3</div>
                            <p class="text-sm font-bold text-primary-800">Klik Simpan ke Board!</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Setup Manual Right -->
            <div class="bg-slate-50 rounded-[2rem] border border-slate-200/60 shadow-inner p-8 md:p-10 flex flex-col">
                <div class="mb-8">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-100 border border-amber-200 text-amber-700 text-[10px] font-black uppercase tracking-widest mb-4">
                        <i class="ph-bold ph-wrench"></i> Setup Manual
                    </div>
                    <h3 class="text-2xl font-black text-slate-800 tracking-tight">Instalasi via Developer Mode</h3>
                    <p class="text-slate-500 text-sm mt-2 font-medium">Ikuti 4 langkah mudah ini untuk memasang ekstensi secara offline di browser Google Chrome Anda.</p>
                </div>

                <div class="space-y-6 flex-1 relative before:absolute before:inset-0 before:ml-[1.1rem] before:-translate-x-px before:h-full before:w-0.5 before:bg-gradient-to-b before:from-slate-200 before:to-transparent">
                    
                    <!-- Step 1 -->
                    <div class="relative flex items-start gap-5">
                        <div class="w-9 h-9 rounded-full bg-white border-2 border-slate-200 shadow-sm flex items-center justify-center shrink-0 z-10 text-xs font-black text-slate-400">01</div>
                        <div class="pt-1.5">
                            <h4 class="text-sm font-bold text-slate-800">Ekstrak ZIP</h4>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">Download file ZIP, lalu ekstrak ke dalam sebuah folder permanen.</p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative flex items-start gap-5">
                        <div class="w-9 h-9 rounded-full bg-white border-2 border-slate-200 shadow-sm flex items-center justify-center shrink-0 z-10 text-xs font-black text-slate-400">02</div>
                        <div class="pt-1.5">
                            <h4 class="text-sm font-bold text-slate-800">Buka Extensions</h4>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">Buka URL <code class="bg-slate-200 text-slate-700 px-1.5 py-0.5 rounded font-mono select-all">chrome://extensions/</code> di browser.</p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative flex items-start gap-5">
                        <div class="w-9 h-9 rounded-full bg-white border-2 border-slate-200 shadow-sm flex items-center justify-center shrink-0 z-10 text-xs font-black text-slate-400">03</div>
                        <div class="pt-1.5">
                            <h4 class="text-sm font-bold text-slate-800">Aktifkan Dev Mode</h4>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">Nyalakan toggle <strong>Developer mode</strong> di sudut kanan atas.</p>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="relative flex items-start gap-5">
                        <div class="w-9 h-9 rounded-full bg-white border-2 border-emerald-400 shadow-sm shadow-emerald-500/20 flex items-center justify-center shrink-0 z-10 text-xs font-black text-emerald-500">
                            <i class="ph-bold ph-check"></i>
                        </div>
                        <div class="pt-1.5">
                            <h4 class="text-sm font-bold text-slate-800">Load Unpacked</h4>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">Klik <strong>Load unpacked</strong>, pilih folder hasil ekstrak, dan selesai!</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 bg-slate-900 text-slate-400 text-[11px] p-4 rounded-2xl flex gap-3 border border-slate-800">
                    <i class="ph-fill ph-push-pin text-primary-400 text-lg shrink-0"></i>
                    <p>Jangan lupa klik ikon puzzle di address bar Chrome, lalu <strong>Pin TraKerja</strong> agar aplikasinya selalu terlihat dan mudah diakses.</p>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
