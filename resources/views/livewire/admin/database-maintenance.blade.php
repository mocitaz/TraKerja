<div class="space-y-4">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 text-left">
        {{-- Database Backup Card --}}
        <div class="bg-white rounded-xl border border-zinc-200 overflow-hidden flex flex-col h-full shadow-3xs">
            <div class="p-5 flex-1">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-8 h-8 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center shrink-0 shadow-3xs">
                        <i class="ph-bold ph-cloud-arrow-down text-zinc-500 text-base"></i>
                    </div>
                    <div class="text-right">
                        <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest block">Last Backup</span>
                        <p class="text-[10px] font-mono font-bold text-zinc-800">Today, 08:30 AM</p>
                    </div>
                </div>

                <h4 class="text-xs font-bold text-zinc-900 mb-1 leading-none">Manual Database Backup</h4>
                <p class="text-xs text-zinc-500 leading-relaxed mb-4 mt-2">
                    Unduh salinan lengkap database TraKerja dalam format .SQL. Sangat disarankan untuk melakukan backup sebelum melakukan perubahan besar pada sistem.
                </p>

                <div class="flex items-center gap-3 mb-4 p-3.5 bg-zinc-50 rounded-xl border border-zinc-200 shadow-3xs">
                    <div class="flex-1">
                        <p class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest">Database Size</p>
                        <p class="text-xs font-mono font-bold text-zinc-900 mt-0.5">{{ $dbSize }}</p>
                    </div>
                    <div class="h-9 w-px bg-zinc-250"></div>
                    <div class="flex-1 pl-3">
                        <p class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest">Last Backup</p>
                        <p class="text-xs font-bold text-zinc-700 mt-0.5">Today, 08:30 AM</p>
                    </div>
                </div>

                <a href="{{ route('admin.database.download') }}" 
                   class="inline-flex items-center justify-center gap-2 w-full h-9 bg-zinc-900 text-white rounded-lg hover:bg-zinc-800 transition-colors font-bold text-xs focus:outline-none shadow-3xs">
                    <i class="ph-bold ph-download-simple text-sm"></i>
                    Download SQL Backup (.sql)
                </a>
            </div>
            <div class="px-5 py-3.5 bg-zinc-50/50 border-t border-zinc-100 flex items-center gap-1.5 text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wider">
                <i class="ph-bold ph-shield-check text-xs text-zinc-500"></i>
                ENCRYPTED AND SECURE TRANSFER
            </div>
        </div>

        {{-- Storage Cleaning Card --}}
        <div class="bg-white rounded-xl border border-zinc-200 overflow-hidden flex flex-col h-full shadow-3xs">
            <div class="p-5 flex-1">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-8 h-8 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center shrink-0 shadow-3xs">
                        <i class="ph-bold ph-broom text-zinc-500 text-base"></i>
                    </div>
                    @if($unusedFilesCount > 0)
                        <div class="animate-pulse">
                            <span class="px-2 py-0.5 bg-amber-50 text-amber-700 text-[8px] font-mono font-bold rounded-lg border border-amber-250 uppercase tracking-wider">Action Needed</span>
                        </div>
                    @endif
                </div>

                <h4 class="text-xs font-bold text-zinc-900 mb-1 leading-none">Storage Maintenance</h4>
                <p class="text-xs text-zinc-500 leading-relaxed mb-4 mt-2">
                    Bersihkan file sementara, PDF CV yang tidak tertaut, dan cache aplikasi untuk mengoptimalkan ruang penyimpanan server Anda.
                </p>

                <div class="flex items-center gap-3 mb-4 p-3.5 bg-zinc-50 rounded-xl border border-zinc-200 shadow-3xs">
                    <div class="flex-1">
                        <p class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest">Unused Files</p>
                        <p class="text-xs font-bold text-zinc-900 mt-0.5">{{ $unusedFilesCount }} Files Found</p>
                    </div>
                    <div class="h-9 w-px bg-zinc-250"></div>
                    <div class="flex-1 pl-3">
                        <p class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest">Server Path</p>
                        <p class="text-xs font-mono font-bold text-zinc-700 truncate mt-0.5">/storage/app/public</p>
                    </div>
                </div>

                <button type="button" wire:click="cleanStorage" wire:loading.attr="disabled" 
                        class="inline-flex items-center justify-center gap-2 w-full h-9 rounded-lg transition-colors font-bold text-xs focus:outline-none shadow-3xs disabled:opacity-50 {{ $unusedFilesCount > 0 ? 'bg-zinc-900 text-white hover:bg-zinc-800' : 'border border-zinc-200 bg-white hover:bg-zinc-50 text-zinc-700' }}">
                    <i class="ph-bold ph-sparkles text-sm" wire:loading.remove wire:target="cleanStorage"></i>
                    <i class="ph-bold ph-spinner animate-spin text-sm" wire:loading wire:target="cleanStorage"></i>
                    {{ $unusedFilesCount > 0 ? 'Clean Unused Files' : 'System Optimized' }}
                </button>
            </div>
            <div class="px-5 py-3.5 bg-zinc-50/50 border-t border-zinc-100 flex items-center justify-between text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wider">
                <span>Cache: Clear</span>
                <span class="w-1 h-1 bg-zinc-300 rounded-full"></span>
                <span>Logs: Rotated</span>
                <span class="w-1 h-1 bg-zinc-300 rounded-full"></span>
                <span>PDF: Managed</span>
            </div>
        </div>
    </div>
</div>
