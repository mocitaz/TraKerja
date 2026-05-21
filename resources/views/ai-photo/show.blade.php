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
                        <div class="relative w-full rounded-[2rem] overflow-hidden bg-slate-100 border border-slate-200 shadow-inner group flex items-center justify-center min-h-[400px]">
                            {{-- Checkboard pattern for transparent images --}}
                            <div class="absolute inset-0 opacity-20 pointer-events-none" 
                                 style="background-image: linear-gradient(45deg, #cbd5e1 25%, transparent 25%), linear-gradient(-45deg, #cbd5e1 25%, transparent 25%), linear-gradient(45deg, transparent 75%, #cbd5e1 75%), linear-gradient(-45deg, transparent 75%, #cbd5e1 75%); background-size: 20px 20px; background-position: 0 0, 0 10px, 10px -10px, -10px 0px;">
                            </div>
                            <img src="{{ $aiPhoto->result_url }}" alt="AI Photo Result" class="relative z-10 max-w-full h-auto max-h-[600px] object-contain drop-shadow-xl transition-transform duration-700 group-hover:scale-[1.02]">
                            
                            {{-- Badge Overlay --}}
                            <div class="absolute top-4 right-4 z-20 pointer-events-none">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/90 backdrop-blur-sm border border-white rounded-xl text-[10px] font-black {{ $aiPhoto->type === 'enhance' ? 'text-fuchsia-600' : 'text-sky-600' }} uppercase tracking-widest shadow-lg">
                                    <i class="ph-bold {{ $aiPhoto->type === 'enhance' ? 'ph-magic-wand' : 'ph-eraser' }} text-sm"></i>
                                    {{ str_replace('_', ' ', $aiPhoto->type) }}
                                </span>
                            </div>
                        </div>
                        
                        {{-- Action Buttons --}}
                        <div class="flex flex-col sm:flex-row gap-4 w-full mt-6">
                            <a href="{{ $aiPhoto->result_url }}" download class="flex-1 flex items-center justify-center gap-2 px-6 py-4 bg-primary-600 text-white rounded-[1.25rem] font-black text-xs uppercase tracking-widest hover:bg-primary-700 transition-all shadow-xl shadow-primary-200 active:scale-95 group">
                                <i class="ph-bold ph-download-simple text-lg group-hover:-translate-y-1 transition-transform"></i>
                                Unduh Foto Tinggi
                            </a>
                            <a href="{{ $aiPhoto->result_url }}" target="_blank" class="flex-1 flex items-center justify-center gap-2 px-6 py-4 bg-white border border-slate-200 text-slate-700 rounded-[1.25rem] font-black text-xs uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm active:scale-95 group">
                                <i class="ph-bold ph-arrow-up-right text-lg group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"></i>
                                Buka di Tab Baru
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ── Right Sidebar (Metadata) (5 Cols) ── --}}
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white border border-slate-200/70 rounded-[2.5rem] overflow-hidden shadow-sm bento-card">
                        <div class="px-7 py-6 border-b border-slate-100 flex items-center gap-3">
                            <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
                                <i class="ph-bold ph-info text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-black text-slate-900 tracking-tight">Detail Gambar</h3>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-0.5">Metadata Information</p>
                            </div>
                        </div>

                        <div class="p-7 space-y-6">
                            <div class="grid grid-cols-2 gap-y-6 gap-x-4">
                                <div class="space-y-1">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Tipe Layanan</p>
                                    <p class="text-sm font-black text-slate-800 capitalize">{{ str_replace('_', ' ', $aiPhoto->type) }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Dibuat Pada</p>
                                    <p class="text-sm font-black text-slate-800">{{ $aiPhoto->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                
                                @if($aiPhoto->type === 'enhance')
                                <div class="space-y-1">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Style</p>
                                    <div class="flex items-center gap-1.5 text-sm font-black text-slate-800 capitalize">
                                        <i class="ph-fill ph-t-shirt text-slate-400"></i>
                                        {{ str_replace('_', ' ', $aiPhoto->style_used) }}
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Mode</p>
                                    <p class="text-sm font-black text-slate-800 capitalize">{{ $aiPhoto->mode }}</p>
                                </div>
                                @endif
                                
                                <div class="col-span-2 space-y-1">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Background Settings</p>
                                    <div class="flex items-center gap-1.5 text-sm font-black text-slate-800 capitalize">
                                        <i class="ph-fill ph-image-square text-slate-400"></i>
                                        {{ str_replace('_', ' ', $aiPhoto->background_used) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($aiPhoto->photo_analysis && is_array($aiPhoto->photo_analysis))
                    <div class="bg-slate-900 border border-slate-800 rounded-[2.5rem] overflow-hidden shadow-2xl shadow-slate-900/20 bento-card text-white relative">
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
                        
                        <div class="relative z-10">
                            <div class="px-7 py-6 border-b border-white/10 flex items-center gap-3">
                                <div class="w-10 h-10 bg-white/10 text-indigo-300 border border-white/10 rounded-xl flex items-center justify-center">
                                    <i class="ph-bold ph-cpu text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-black text-white tracking-tight">AI Diagnostics</h3>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-0.5">Raw JSON Output</p>
                                </div>
                            </div>
                            <div class="p-7">
                                <div class="bg-black/50 border border-white/5 p-4 rounded-2xl overflow-x-auto">
                                    <pre class="text-[11px] font-mono text-emerald-400 leading-relaxed">{{ json_encode($aiPhoto->photo_analysis, JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
