<div class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-6 space-y-6 sm:space-y-8 pb-10">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 sm:mb-8">
        <div class="flex items-center gap-3 sm:gap-4 min-w-0">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white border border-slate-200/60 rounded-[1.25rem] sm:rounded-[1.5rem] flex items-center justify-center text-primary-600 shadow-sm shrink-0">
                <i class="ph-duotone ph-database text-xl sm:text-2xl"></i>
            </div>
            <div class="flex flex-col min-w-0">
                <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight truncate">Database & Maintenance</h3>
                <p class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mt-1 sm:mt-1.5 truncate">Backups & Storage Management</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 pt-2">
        {{-- Database Backup Card --}}
        <div class="bg-white rounded-[2rem] border border-slate-200/60 bento-card overflow-hidden flex flex-col h-full">
            <div class="p-8 flex-1">
                <div class="flex items-start justify-between mb-8">
                    <div class="w-14 h-14 rounded-[1.25rem] bg-blue-50 text-blue-600 flex items-center justify-center shadow-sm">
                        <i class="ph-duotone ph-cloud-arrow-down text-3xl"></i>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Last Backup</span>
                        <p class="text-xs font-bold text-slate-900">Today, 08:30 AM</p>
                    </div>
                </div>

                <h4 class="text-xl font-extrabold text-slate-900 mb-2">Manual Database Backup</h4>
                <p class="text-sm text-slate-500 font-medium leading-relaxed mb-6">
                    Unduh salinan lengkap database TraKerja dalam format .SQL. Sangat disarankan untuk melakukan backup sebelum melakukan perubahan besar pada sistem.
                </p>

                <div class="flex items-center gap-4 mb-8 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="flex-1">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Database Size</p>
                        <p class="text-xl font-black text-slate-900">{{ $dbSize }}</p>
                    </div>
                    <div class="h-10 w-px bg-slate-200"></div>
                    <div class="flex-1">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Last Backup</p>
                        <p class="text-sm font-bold text-slate-700">Today, 08:30 AM</p>
                    </div>
                </div>

                <a href="{{ route('admin.database.download') }}" class="inline-flex items-center justify-center gap-3 w-full py-4 bg-slate-900 text-white rounded-2xl font-bold text-sm hover:bg-slate-800 transition-all shadow-sm active:scale-95 group/btn">
                    <i class="ph-bold ph-download-simple text-lg transition-transform"></i>
                    Download SQL Backup (.sql)
                </a>
            </div>
            <div class="px-8 py-4 bg-slate-50 border-t border-slate-100 flex items-center gap-2 text-[10px] font-bold text-slate-400">
                <i class="ph-bold ph-shield-check"></i>
                ENCRYPTED AND SECURE TRANSFER
            </div>
        </div>

        {{-- Storage Cleaning Card --}}
        <div class="bg-white rounded-[2rem] border border-slate-200/60 bento-card overflow-hidden flex flex-col h-full">
            <div class="p-8 flex-1">
                <div class="flex items-start justify-between mb-8">
                    <div class="w-14 h-14 rounded-[1.25rem] bg-amber-50 text-amber-600 flex items-center justify-center shadow-sm">
                        <i class="ph-duotone ph-broom text-3xl"></i>
                    </div>
                    @if($unusedFilesCount > 0)
                    <div class="animate-bounce">
                        <span class="px-3 py-1 bg-amber-100 text-amber-700 text-[10px] font-black rounded-full border border-amber-200 uppercase tracking-widest">Action Needed</span>
                    </div>
                    @endif
                </div>

                <h4 class="text-xl font-extrabold text-slate-900 mb-2">Storage Maintenance</h4>
                <p class="text-sm text-slate-500 font-medium leading-relaxed mb-6">
                    Bersihkan file sementara, PDF CV yang tidak tertaut, dan cache aplikasi untuk mengoptimalkan ruang penyimpanan server Anda.
                </p>

                <div class="flex items-center gap-4 mb-8 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="flex-1">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Unused Files</p>
                        <p class="text-xl font-black text-slate-900">{{ $unusedFilesCount }} Files Found</p>
                    </div>
                    <div class="h-10 w-px bg-slate-200"></div>
                    <div class="flex-1">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Server Path</p>
                        <p class="text-sm font-bold text-slate-700 truncate">/storage/app/public</p>
                    </div>
                </div>

                <button wire:click="cleanStorage" wire:loading.attr="disabled" class="inline-flex items-center justify-center gap-3 w-full py-4 bg-amber-500 text-white rounded-2xl font-bold text-sm hover:bg-amber-600 transition-all shadow-sm active:scale-95 disabled:opacity-50">
                    <i class="ph-bold ph-sparkle text-lg" wire:loading.remove wire:target="cleanStorage"></i>
                    <i class="ph-bold ph-spinner animate-spin text-lg" wire:loading wire:target="cleanStorage"></i>
                    {{ $unusedFilesCount > 0 ? 'Clean Unused Files' : 'System Optimized' }}
                </button>
            </div>
            <div class="px-8 py-4 bg-slate-50 border-t border-slate-100 flex items-center">
                <div class="flex items-center gap-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    <span>Cache: Clear</span>
                    <span class="h-1 w-1 bg-slate-300 rounded-full"></span>
                    <span>Logs: Rotated</span>
                    <span class="h-1 w-1 bg-slate-300 rounded-full"></span>
                    <span>PDF: Managed</span>
                </div>
            </div>
        </div>
    </div>
</div>
