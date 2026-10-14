<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex flex-col">
                <h1 class="text-2xl font-black text-slate-900 dark:text-[#f0f4f9] leading-tight tracking-tight">
                    Photo <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#d983e4] via-primary-600 to-[#4e71c5] dark:from-[#d8b4fe] dark:via-purple-400 dark:to-indigo-400">History</span>
                </h1>
                <p class="text-[11px] text-slate-500 dark:text-[#94a3b8] font-bold uppercase tracking-widest mt-1">View your past AI generated photos</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('ai-photo.index') }}" class="group inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 dark:bg-[#a855f7] text-white border border-transparent rounded-xl font-black text-[11px] uppercase tracking-widest shadow-lg shadow-primary-200 dark:shadow-none hover:bg-primary-700 dark:hover:bg-[#9333ea] transition-all active:scale-95">
                    <i class="ph-bold ph-plus text-base group-hover:scale-125 transition-transform"></i>
                    Buat Baru
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
        html.dark .mesh-gradient-ai {
            background-color: #1e1f20;
            background-image:
                radial-gradient(at 0% 0%, rgba(168, 85, 247, 0.1) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(99, 102, 241, 0.1) 0px, transparent 50%);
        }

        .bento-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.8), 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        }
        html.dark .bento-card {
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.05), 0 4px 16px rgba(0, 0, 0, 0.3);
        }
    </style>

    <div class="bg-[#f8fafc] dark:bg-[#131314] min-h-screen pb-20">
        <div class="workspace-page-frame max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 pt-8">

            @if($photos->isEmpty())
                <div class="mesh-gradient-ai border border-slate-200/70 dark:border-white/10 rounded-[2.5rem] p-12 text-center bento-card">
                    <div class="w-24 h-24 bg-slate-50 dark:bg-[#282a2c] border border-slate-100 dark:border-white/10 rounded-3xl mx-auto flex items-center justify-center mb-6 shadow-sm">
                        <i class="ph-bold ph-images text-5xl text-slate-300 dark:text-zinc-600"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 dark:text-[#f0f4f9] tracking-tight">Belum ada riwayat foto</h3>
                    <p class="text-xs font-bold text-slate-500 dark:text-[#94a3b8] mt-2 uppercase tracking-widest">Mulai buat foto profil profesional atau hapus background dengan AI.</p>
                    <div class="mt-8">
                        <a href="{{ route('ai-photo.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 dark:bg-[#a855f7] hover:dark:bg-[#9333ea] text-white rounded-xl font-black text-xs uppercase tracking-widest shadow-lg shadow-slate-200 dark:shadow-none hover:bg-slate-800 transition-all active:scale-95">
                            <i class="ph-bold ph-magic-wand text-sm"></i>
                            Mulai Edit Foto
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($photos as $photo)
                        <div class="group bg-white dark:bg-[#1e1f20] border border-slate-200/70 dark:border-white/10 rounded-[2rem] overflow-hidden bento-card hover:border-primary-300 dark:hover:border-purple-500/40 hover:shadow-xl hover:shadow-primary-100/50 dark:hover:shadow-none flex flex-col">
                            {{-- Image Container --}}
                            <div class="relative bg-slate-100 dark:bg-[#18191a] overflow-hidden shrink-0">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                                <img src="{{ $photo->result_url }}" alt="AI Photo Result" class="w-full h-56 object-cover object-top transition-transform duration-700 group-hover:scale-105">
                                
                                {{-- Service Tag Overlay --}}
                                <div class="absolute top-4 left-4 z-20">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/90 dark:bg-[#282a2c]/90 backdrop-blur-sm border border-white dark:border-white/10 rounded-lg text-[9px] font-black {{ $photo->type === 'enhance' ? 'text-fuchsia-600 dark:text-fuchsia-400' : 'text-sky-600 dark:text-sky-400' }} uppercase tracking-widest shadow-sm">
                                        <i class="ph-bold {{ $photo->type === 'enhance' ? 'ph-magic-wand' : 'ph-eraser' }} text-xs"></i>
                                        {{ str_replace('_', ' ', $photo->type) }}
                                    </span>
                                </div>
                            </div>
                            
                            {{-- Content --}}
                            <div class="p-5 flex flex-col flex-grow">
                                <div class="flex items-center gap-2 mb-3">
                                    <i class="ph-fill ph-clock text-slate-400 dark:text-zinc-500 text-sm"></i>
                                    <p class="text-[10px] font-black text-slate-500 dark:text-[#94a3b8] uppercase tracking-widest">
                                        {{ $photo->created_at->format('d M Y, H:i') }}
                                    </p>
                                </div>
                                
                                <div class="mt-auto grid grid-cols-2 gap-3 pt-2">
                                    <a href="{{ route('ai-photo.show', $photo->id) }}" class="flex items-center justify-center gap-1.5 text-[10px] font-black text-slate-600 dark:text-[#f0f4f9] bg-slate-50 dark:bg-[#282a2c] border border-slate-200 dark:border-white/10 hover:bg-slate-100 hover:dark:bg-[#333538] hover:text-slate-900 py-2.5 rounded-xl transition-all uppercase tracking-widest">
                                        <i class="ph-bold ph-eye text-sm"></i>
                                        Detail
                                    </a>
                                    <a href="{{ $photo->result_url }}" download class="flex items-center justify-center gap-1.5 text-[10px] font-black text-primary-600 dark:text-purple-300 bg-primary-50 dark:bg-purple-950/40 border border-primary-100 dark:border-purple-500/20 hover:bg-primary-600 hover:dark:bg-[#a855f7] hover:text-white hover:border-transparent py-2.5 rounded-xl transition-all uppercase tracking-widest">
                                        <i class="ph-bold ph-download-simple text-sm"></i>
                                        Unduh
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-10">
                    {{ $photos->links() }}
                </div>
            @endif
            
        </div>
    </div>
</x-app-layout>
