<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex flex-col">
                <h1 class="text-2xl font-black text-slate-900 leading-tight tracking-tight">
                    Photo <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#d983e4] via-primary-600 to-[#4e71c5]">Result</span>
                </h1>
                <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest mt-1">Your professionally enhanced photo is ready</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('ai-photo.index') }}" class="group inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl font-black text-[10px] text-slate-600 uppercase tracking-widest shadow-sm hover:bg-slate-50 hover:text-slate-900 transition-all">
                    <i class="ph-bold ph-arrow-left text-sm group-hover:-translate-x-1 transition-transform"></i>
                    Back to Studio
                </a>
            </div>
        </div>
    </x-slot>

    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        .mesh-gradient-ai {
            background-color: #ffffff;
            background-image:
                radial-gradient(at 0% 0%, rgba(217, 131, 228, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(78, 113, 197, 0.05) 0px, transparent 50%);
        }

        .bento-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.8), 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        }
        
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>

    <div class="bg-[#f8fafc] min-h-screen pb-20">
        <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {{-- ── Left Content (Image Display) (7 Cols) ── --}}
                <div class="lg:col-span-7 space-y-6">
                    <div class="mesh-gradient-ai border border-slate-200/70 rounded-[2.5rem] p-6 sm:p-8 bento-card flex flex-col items-center">
                        {{-- Image Display --}}
                        <div class="relative w-full rounded-[2rem] overflow-hidden bg-white ring-1 ring-slate-900/5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] group flex items-center justify-center min-h-[400px]">
                            {{-- Dot pattern for transparent images --}}
                            <div class="absolute inset-0 opacity-[0.03]" 
                                 style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 20px 20px;">
                            </div>
                            <img src="{{ $aiPhoto->result_url }}" alt="AI Photo Result" class="relative z-10 max-w-full h-auto max-h-[600px] object-contain drop-shadow-2xl transition-transform duration-700 ease-out group-hover:scale-[1.03]">
                            
                            {{-- Badge Overlay --}}
                            <div class="absolute top-5 right-5 z-20 pointer-events-none">
                                <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-white/90 backdrop-blur-md border border-white/50 rounded-2xl text-[10px] font-black {{ $aiPhoto->type === 'enhance' ? 'text-fuchsia-600' : 'text-sky-600' }} uppercase tracking-widest shadow-[0_8px_30px_rgb(0,0,0,0.08)]">
                                    <i class="ph-bold {{ $aiPhoto->type === 'enhance' ? 'ph-magic-wand' : 'ph-eraser' }} text-sm"></i>
                                    {{ str_replace('_', ' ', $aiPhoto->type) }}
                                </span>
                            </div>
                        </div>
                        
                        {{-- Action Buttons --}}
                        <div class="flex flex-col sm:flex-row gap-4 w-full mt-8">
                            <a href="{{ $aiPhoto->result_url }}" download class="flex-1 flex items-center justify-center gap-2 px-6 py-4 bg-slate-900 text-white rounded-2xl font-black text-[11px] uppercase tracking-widest hover:bg-slate-800 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group ring-1 ring-slate-900/5 shadow-md">
                                <i class="ph-bold ph-download-simple text-lg group-hover:-translate-y-0.5 transition-transform"></i>
                                Unduh Resolusi Tinggi
                            </a>
                            <a href="{{ $aiPhoto->result_url }}" target="_blank" class="flex-1 flex items-center justify-center gap-2 px-6 py-4 bg-white border border-slate-200 text-slate-700 rounded-2xl font-black text-[11px] uppercase tracking-widest hover:border-slate-300 hover:bg-slate-50 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group shadow-sm">
                                <i class="ph-bold ph-arrow-up-right text-lg group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"></i>
                                Buka di Tab Baru
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ── Right Sidebar (Metadata) (5 Cols) ── --}}
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white border border-slate-200/70 rounded-[2.5rem] overflow-hidden shadow-sm bento-card">
                        <div class="px-7 py-6 border-b border-slate-100 flex items-center gap-4 bg-slate-50/50">
                            <div class="w-12 h-12 bg-white border border-slate-100 shadow-sm text-indigo-600 rounded-2xl flex items-center justify-center">
                                <i class="ph-duotone ph-list-magnifying-glass text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-black text-slate-900 tracking-tight">Detail Gambar</h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Metadata Information</p>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-2 gap-4">
                                {{-- Detail Item 1 --}}
                                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 hover:border-slate-200 hover:shadow-sm transition-all group">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 group-hover:text-primary-500 transition-colors">Tipe Layanan</p>
                                    <div class="flex items-center gap-2">
                                        <i class="ph-duotone {{ $aiPhoto->type === 'enhance' ? 'ph-magic-wand text-fuchsia-500' : 'ph-eraser text-sky-500' }} text-xl"></i>
                                        <p class="text-sm font-black text-slate-900 capitalize leading-none">{{ str_replace('_', ' ', $aiPhoto->type) }}</p>
                                    </div>
                                </div>
                                
                                {{-- Detail Item 2 --}}
                                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 hover:border-slate-200 hover:shadow-sm transition-all group">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 group-hover:text-primary-500 transition-colors">Dibuat Pada</p>
                                    <div class="flex items-center gap-2">
                                        <i class="ph-duotone ph-calendar-blank text-slate-400 text-xl"></i>
                                        <div class="leading-none">
                                            <p class="text-sm font-black text-slate-900">{{ $aiPhoto->created_at->format('d M') }}</p>
                                            <p class="text-[10px] font-bold text-slate-500">{{ $aiPhoto->created_at->format('H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($aiPhoto->type === 'enhance')
                                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 hover:border-slate-200 hover:shadow-sm transition-all group">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 group-hover:text-fuchsia-500 transition-colors">Style</p>
                                    <div class="flex items-center gap-2">
                                        <i class="ph-duotone ph-t-shirt text-fuchsia-400 text-xl"></i>
                                        <p class="text-sm font-black text-slate-900 capitalize leading-none">{{ str_replace('_', ' ', $aiPhoto->style_used) }}</p>
                                    </div>
                                </div>
                                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 hover:border-slate-200 hover:shadow-sm transition-all group">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 group-hover:text-indigo-500 transition-colors">Mode</p>
                                    <div class="flex items-center gap-2">
                                        <i class="ph-duotone ph-user-focus text-indigo-400 text-xl"></i>
                                        <p class="text-sm font-black text-slate-900 capitalize leading-none">{{ $aiPhoto->mode }}</p>
                                    </div>
                                </div>
                                @endif
                                
                                <div class="col-span-2 bg-slate-50 border border-slate-100 rounded-2xl p-4 hover:border-slate-200 hover:shadow-sm transition-all group">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 group-hover:text-emerald-500 transition-colors">Background Settings</p>
                                    <div class="flex items-center gap-2">
                                        <i class="ph-duotone ph-image-square text-emerald-400 text-xl"></i>
                                        <p class="text-sm font-black text-slate-900 capitalize leading-none">{{ str_replace('_', ' ', $aiPhoto->background_used) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Success/Pro Tip Card (Eye-catching) --}}
                    <div class="relative rounded-[2.5rem] p-8 overflow-hidden shadow-2xl shadow-fuchsia-500/20 bento-card mt-8 group">
                        {{-- Vivid Gradient Background --}}
                        <div class="absolute inset-0 bg-gradient-to-br from-violet-600 via-fuchsia-600 to-indigo-600"></div>
                        
                        {{-- Floating Glowing Orbs --}}
                        <div class="absolute -top-20 -right-20 w-56 h-56 bg-fuchsia-400 rounded-full mix-blend-screen filter blur-[48px] opacity-70 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
                        <div class="absolute -bottom-20 -left-20 w-56 h-56 bg-sky-400 rounded-full mix-blend-screen filter blur-[48px] opacity-70 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
                        
                        {{-- Sparkle Pattern Overlay --}}
                        <div class="absolute inset-0 opacity-[0.1] pointer-events-none" style="background-image: radial-gradient(circle, #fff 1.5px, transparent 1.5px); background-size: 20px 20px;"></div>
                        
                        <div class="relative z-10 flex flex-col sm:flex-row items-center sm:items-start gap-5 text-center sm:text-left">
                            <div class="w-16 h-16 bg-white/20 backdrop-blur-xl rounded-3xl flex items-center justify-center flex-shrink-0 border border-white/40 shadow-[0_8px_32px_rgba(255,255,255,0.25)] transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 ease-out">
                                <i class="ph-duotone ph-sparkle text-3xl text-white drop-shadow-md"></i>
                            </div>
                            <div class="mt-1">
                                <h4 class="text-xl font-black text-white tracking-tight mb-2 leading-tight drop-shadow-sm flex items-center justify-center sm:justify-start gap-2">
                                    Foto Siap Digunakan! 
                                    <span class="inline-block origin-bottom animate-[bounce_2s_infinite]">✨</span>
                                </h4>
                                <p class="text-[13px] text-white/90 font-medium leading-relaxed drop-shadow-sm max-w-sm">
                                    Hasil mahakarya AI ini sudah tersimpan permanen di riwayat akun. Anda bebas mengunduhnya kapan saja <span class="text-yellow-300 font-black px-1.5 py-0.5 bg-black/20 rounded-md shadow-inner">tanpa mengurangi kredit</span> lagi.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
