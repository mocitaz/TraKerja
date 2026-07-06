<div class="space-y-4">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 text-left">
        {{-- Database Backup Card --}}
        <div class="bg-white rounded border border-zinc-200/60 overflow-hidden flex flex-col h-full shadow-none">
            <div class="p-5 flex-1">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-8 h-8 rounded bg-zinc-50 border border-zinc-200/40 text-zinc-500 flex items-center justify-center shrink-0">
                        <i class="ph ph-cloud-arrow-down text-base"></i>
                    </div>
                    <div class="text-right">
                        <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide block">Last Backup</span>
                        <p class="text-[10px] font-mono font-bold text-zinc-800">Today, 08:30 AM</p>
                    </div>
                </div>

                <h4 class="text-xs font-bold text-zinc-900 mb-1 leading-none">Manual Database Backup</h4>
                <p class="text-xs text-zinc-500 leading-relaxed mb-4 mt-2">
                    Unduh salinan lengkap database TraKerja dalam format .SQL. Sangat disarankan untuk melakukan backup sebelum melakukan perubahan besar pada sistem.
                </p>

                <div class="flex items-center gap-3 mb-4 p-3 bg-zinc-50 rounded border border-zinc-200/80">
                    <div class="flex-1">
                        <p class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Database Size</p>
                        <p class="text-xs font-mono font-bold text-zinc-900 mt-0.5">{{ $dbSize }}</p>
                    </div>
                    <div class="h-8 w-px bg-zinc-200"></div>
                    <div class="flex-1 pl-3">
                        <p class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Last Backup</p>
                        <p class="text-xs font-bold text-zinc-700 mt-0.5">Today, 08:30 AM</p>
                    </div>
                </div>

                <a href="{{ route('admin.database.download') }}" 
                   class="inline-flex items-center justify-center gap-2 w-full h-8 bg-zinc-900 text-white rounded hover:bg-zinc-800 transition-colors font-semibold text-xs focus:outline-none shadow-none">
                    <i class="ph ph-download-simple text-sm"></i>
                    Download SQL Backup (.sql)
                </a>
            </div>
            <div class="px-5 py-2.5 bg-zinc-50 border-t border-zinc-150/60 flex items-center gap-1.5 text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">
                <i class="ph ph-shield-check text-xs"></i>
                ENCRYPTED AND SECURE TRANSFER
            </div>
        </div>

        {{-- Storage Cleaning Card --}}
        <div class="bg-white rounded border border-zinc-200/60 overflow-hidden flex flex-col h-full shadow-none">
            <div class="p-5 flex-1">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-8 h-8 rounded bg-zinc-50 border border-zinc-200/40 text-zinc-500 flex items-center justify-center shrink-0">
                        <i class="ph ph-broom text-base"></i>
                    </div>
                    @if($unusedFilesCount > 0)
                        <div class="animate-pulse">
                            <span class="px-2 py-0.5 bg-amber-50 text-amber-700 text-[8px] font-mono font-bold rounded border border-amber-250 uppercase tracking-wide">Action Needed</span>
                        </div>
                    @endif
                </div>

                <h4 class="text-xs font-bold text-zinc-900 mb-1 leading-none">Storage Maintenance</h4>
                <p class="text-xs text-zinc-500 leading-relaxed mb-4 mt-2">
                    Bersihkan file sementara, PDF CV yang tidak tertaut, dan cache aplikasi untuk mengoptimalkan ruang penyimpanan server Anda.
                </p>

                <div class="flex items-center gap-3 mb-4 p-3 bg-zinc-50 rounded border border-zinc-200/80">
                    <div class="flex-1">
                        <p class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Unused Files</p>
                        <p class="text-xs font-bold text-zinc-900 mt-0.5">{{ $unusedFilesCount }} Files Found</p>
                    </div>
                    <div class="h-8 w-px bg-zinc-200"></div>
                    <div class="flex-1 pl-3">
                        <p class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Server Path</p>
                        <p class="text-xs font-mono font-bold text-zinc-700 truncate mt-0.5">/storage/app/public</p>
                    </div>
                </div>

                <button type="button" wire:click="cleanStorage" wire:loading.attr="disabled" 
                        class="inline-flex items-center justify-center gap-2 w-full h-8 rounded transition-colors font-semibold text-xs focus:outline-none shadow-none disabled:opacity-50 {{ $unusedFilesCount > 0 ? 'bg-zinc-900 text-white hover:bg-zinc-800' : 'border border-zinc-250 bg-white hover:bg-zinc-50 text-zinc-700' }}">
                    <i class="ph ph-sparkle text-sm" wire:loading.remove wire:target="cleanStorage"></i>
                    <i class="ph ph-spinner animate-spin text-sm" wire:loading wire:target="cleanStorage"></i>
                    {{ $unusedFilesCount > 0 ? 'Clean Unused Files' : 'System Optimized' }}
                </button>
            </div>
            <div class="px-5 py-2.5 bg-zinc-50 border-t border-zinc-150/60 flex items-center justify-between text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">
                <span>Cache: Clear</span>
                <span class="w-1 h-1 bg-zinc-300 rounded-full"></span>
                <span>Logs: Rotated</span>
                <span class="w-1 h-1 bg-zinc-300 rounded-full"></span>
                <span>PDF: Managed</span>
            </div>
        </div>
    </div>
</div>
