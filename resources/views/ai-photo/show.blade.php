<x-app-layout>
    <div class="bg-[#fafafa] min-h-screen pb-16">
        <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            
            <!-- Premium Notion-Inspired Page Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-slate-200/60 pb-5 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-tr from-primary-50 to-primary-100/50 rounded-xl flex items-center justify-center text-primary-600 shrink-0 border border-primary-100/50 shadow-inner">
                        <i class="ph ph-camera text-lg"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-base font-bold text-slate-800 tracking-tight">Photo Result</h1>
                            <span class="px-2 py-0.5 bg-primary-50 text-primary-600 text-[9px] font-black uppercase tracking-wider rounded-md border border-primary-100/60">AI Studio</span>
                        </div>
                        <p class="text-xs text-slate-455 mt-0.5">Your professionally enhanced photo is ready.</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('ai-photo.index') }}" class="px-3.5 py-1.5 bg-white text-slate-700 border border-slate-200 hover:bg-slate-50 text-xs font-semibold rounded-lg shadow-sm transition-colors flex items-center gap-1.5">
                        <i class="ph ph-arrow-left text-sm"></i>
                        <span>Back to Studio</span>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                {{-- ── Left Content (Image Display) (7 Cols) ── --}}
                <div class="lg:col-span-7 space-y-6">
                    <div class="bg-white border border-slate-200/70 rounded-xl p-5 shadow-sm flex flex-col items-center">
                        {{-- Image Display --}}
                        <div class="relative w-full rounded-lg overflow-hidden bg-[#fcfcfc] border border-slate-150 flex items-center justify-center min-h-[400px]">
                            {{-- Dot pattern for transparent images --}}
                            <div class="absolute inset-0 opacity-[0.03]" 
                                 style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 20px 20px;">
                            </div>
                            <img src="{{ $aiPhoto->result_url }}" alt="AI Photo Result" class="relative z-10 max-w-full h-auto max-h-[600px] object-contain transition-transform duration-300 group-hover:scale-101">
                            
                            {{-- Badge Overlay --}}
                            <div class="absolute top-4 right-4 z-20 pointer-events-none">
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-white border border-slate-200 rounded-lg text-[9px] font-bold {{ $aiPhoto->type === 'enhance' ? 'text-fuchsia-600' : 'text-sky-600' }} uppercase tracking-wider shadow-sm">
                                    <i class="ph {{ $aiPhoto->type === 'enhance' ? 'ph-magic-wand' : 'ph-eraser' }} text-xs"></i>
                                    {{ str_replace('_', ' ', $aiPhoto->type) }}
                                </span>
                            </div>
                        </div>
                        
                        {{-- Action Buttons --}}
                        <div class="flex flex-col sm:flex-row gap-3 w-full mt-5">
                            <a href="{{ $aiPhoto->result_url }}" download class="flex-1 flex items-center justify-center gap-2 px-4 py-2 bg-slate-900 text-white rounded-lg font-semibold text-xs hover:bg-slate-800 transition-colors shadow-sm">
                                <i class="ph ph-download-simple text-sm"></i>
                                <span>Unduh Resolusi Tinggi</span>
                            </a>
                            <a href="{{ $aiPhoto->result_url }}" target="_blank" class="flex-1 flex items-center justify-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg font-semibold text-xs hover:bg-slate-50 transition-colors shadow-sm">
                                <i class="ph ph-arrow-up-right text-sm"></i>
                                <span>Buka di Tab Baru</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ── Right Sidebar (Metadata) (5 Cols) ── --}}
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white border border-slate-200/70 rounded-xl overflow-hidden shadow-sm">
                        <div class="px-5 py-4 border-b border-slate-150/60 flex items-center gap-3 bg-slate-50/50">
                            <div class="w-8 h-8 bg-white border border-slate-200 rounded shadow-sm text-slate-700 flex items-center justify-center shrink-0">
                                <i class="ph ph-list-bullets text-base"></i>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-slate-850 tracking-tight">Detail Gambar</h3>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Metadata Information</p>
                            </div>
                        </div>

                        <div class="p-4">
                            <div class="grid grid-cols-2 gap-3">
                                {{-- Detail Item 1 --}}
                                <div class="bg-slate-50 border border-slate-150 rounded-lg p-3">
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tipe Layanan</p>
                                    <div class="flex items-center gap-1.5">
                                        <i class="ph {{ $aiPhoto->type === 'enhance' ? 'ph-magic-wand text-fuchsia-500' : 'ph-eraser text-sky-500' }} text-base"></i>
                                        <p class="text-xs font-bold text-slate-850 capitalize leading-none">{{ str_replace('_', ' ', $aiPhoto->type) }}</p>
                                    </div>
                                </div>
                                
                                {{-- Detail Item 2 --}}
                                <div class="bg-slate-50 border border-slate-150 rounded-lg p-3">
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Dibuat Pada</p>
                                    <div class="flex items-center gap-1.5">
                                        <i class="ph ph-calendar-blank text-slate-400 text-base"></i>
                                        <div class="leading-none">
                                            <p class="text-xs font-bold text-slate-850">{{ $aiPhoto->created_at->format('d M') }}</p>
                                            <p class="text-[9px] font-semibold text-slate-455 mt-0.5">{{ $aiPhoto->created_at->format('H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($aiPhoto->type === 'enhance')
                                <div class="bg-slate-50 border border-slate-150 rounded-lg p-3">
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Style</p>
                                    <div class="flex items-center gap-1.5">
                                        <i class="ph ph-t-shirt text-fuchsia-400 text-base"></i>
                                        <p class="text-xs font-bold text-slate-850 capitalize leading-none">{{ str_replace('_', ' ', $aiPhoto->style_used) }}</p>
                                    </div>
                                </div>
                                <div class="bg-slate-50 border border-slate-150 rounded-lg p-3">
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Mode</p>
                                    <div class="flex items-center gap-1.5">
                                        <i class="ph ph-user-focus text-indigo-400 text-base"></i>
                                        <p class="text-xs font-bold text-slate-850 capitalize leading-none">{{ $aiPhoto->mode }}</p>
                                    </div>
                                </div>
                                @endif
                                
                                <div class="col-span-2 bg-slate-50 border border-slate-150 rounded-lg p-3">
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Background Settings</p>
                                    <div class="flex items-center gap-1.5">
                                        <i class="ph ph-image text-emerald-400 text-base"></i>
                                        <p class="text-xs font-bold text-slate-850 capitalize leading-none">{{ str_replace('_', ' ', $aiPhoto->background_used) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Success/Pro Tip Card --}}
                    <div class="bg-white border border-slate-200/70 rounded-xl p-5 shadow-sm relative overflow-hidden">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-slate-50 border border-slate-200 rounded flex items-center justify-center text-slate-700 shrink-0">
                                <i class="ph ph-sparkle text-base"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-850 mb-1 leading-tight flex items-center gap-1.5">
                                    <span>Foto Siap Digunakan!</span>
                                    <span>✨</span>
                                </h4>
                                <p class="text-[11px] text-slate-500 font-medium leading-relaxed">
                                    Hasil mahakarya AI ini sudah tersimpan permanen di riwayat akun. Anda bebas mengunduhnya kapan saja <span class="text-slate-800 font-bold px-1 py-0.5 bg-slate-100 rounded border border-slate-200">tanpa mengurangi kredit</span> lagi.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
