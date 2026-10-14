<div class="space-y-5">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 text-left">

        {{-- Database Backup Card --}}
        <div class="ast-panel">
            <div class="ast-panel-heading">
                <h3>
                    <i class="ph ph-cloud-arrow-down" aria-hidden="true"></i>
                    Manual Database Backup
                </h3>
                <span class="text-xs text-gray-500 dark:text-slate-400 font-medium">Daily snapshots active</span>
            </div>

            <div class="ast-panel-body justify-between">
                <p class="text-xs text-gray-500 dark:text-slate-400 leading-relaxed">
                    Download a full SQL dump of the TraKerja production database. It is strongly recommended to export a backup before performing major system upgrades or migrations.
                </p>

                <div class="grid grid-cols-2 gap-3 p-3.5 bg-gray-50 dark:bg-[#18191a] rounded-xl border border-gray-200 dark:border-white/10">
                    <div>
                        <span class="text-xs text-gray-500 dark:text-slate-400 font-medium block">Database Size</span>
                        <strong class="text-sm text-gray-800 dark:text-[#f0f4f9] font-semibold mt-0.5 block">{{ $dbSize }}</strong>
                    </div>
                    <div class="border-l border-gray-200 dark:border-white/10 pl-3">
                        <span class="text-xs text-gray-500 dark:text-slate-400 font-medium block">Last Backup</span>
                        <strong class="text-sm text-gray-800 dark:text-[#f0f4f9] font-semibold mt-0.5 block">Automated Daily</strong>
                    </div>
                </div>

                <div class="pt-2">
                    <a href="{{ route('admin.database.download') }}" class="ast-btn ast-btn--primary w-full justify-center">
                        <i class="ph ph-download-simple" aria-hidden="true"></i>
                        Download SQL Backup (.sql)
                    </a>
                </div>
            </div>

            <div class="px-5 py-3 bg-gray-50/60 dark:bg-[#18191a] border-t border-gray-100 dark:border-white/10 flex items-center gap-2 text-xs text-gray-500 dark:text-slate-400">
                <i class="ph-bold ph-shield-check text-emerald-600" aria-hidden="true"></i>
                <span>Encrypted & secure database export tunnel</span>
            </div>
        </div>

        {{-- Storage Maintenance Card --}}
        <div class="ast-panel">
            <div class="ast-panel-heading">
                <h3>
                    <i class="ph ph-broom" aria-hidden="true"></i>
                    Storage Maintenance & Cleanup
                </h3>
                @if($unusedFilesCount > 0)
                    <span class="px-2 py-0.5 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-500/30 text-amber-700 dark:text-amber-300 text-xs font-semibold rounded-lg">
                        Action Needed
                    </span>
                @else
                    <span class="px-2 py-0.5 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-500/30 text-emerald-700 dark:text-emerald-300 text-xs font-semibold rounded-lg">
                        Optimized
                    </span>
                @endif
            </div>

            <div class="ast-panel-body justify-between">
                <p class="text-xs text-gray-500 dark:text-slate-400 leading-relaxed">
                    Purge orphaned files, unlinked CV PDF exports, and temporary image uploads to optimize server disk space and reduce storage footprint.
                </p>

                <div class="grid grid-cols-2 gap-3 p-3.5 bg-gray-50 dark:bg-[#18191a] rounded-xl border border-gray-200 dark:border-white/10">
                    <div>
                        <span class="text-xs text-gray-500 dark:text-slate-400 font-medium block">Orphaned Files</span>
                        <strong class="text-sm {{ $unusedFilesCount > 0 ? 'text-amber-600' : 'text-emerald-600' }} font-semibold mt-0.5 block">
                            {{ $unusedFilesCount }} Files Found
                        </strong>
                    </div>
                    <div class="border-l border-gray-200 dark:border-white/10 pl-3">
                        <span class="text-xs text-gray-500 dark:text-slate-400 font-medium block">Public Storage</span>
                        <strong class="text-sm text-gray-800 dark:text-[#f0f4f9] font-semibold mt-0.5 block truncate">/storage/public</strong>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="button" 
                            wire:click="cleanStorage" 
                            wire:loading.attr="disabled"
                            class="ast-btn {{ $unusedFilesCount > 0 ? 'ast-btn--primary' : 'ast-btn--quiet' }} w-full justify-center">
                        <i class="ph ph-sparkles" wire:loading.remove wire:target="cleanStorage" aria-hidden="true"></i>
                        <i class="ph ph-spinner ph-spin" wire:loading wire:target="cleanStorage" aria-hidden="true"></i>
                        {{ $unusedFilesCount > 0 ? 'Clean Unused Files' : 'System Optimized' }}
                    </button>
                </div>
            </div>

            <div class="px-5 py-3 bg-gray-50/60 dark:bg-[#18191a] border-t border-gray-100 dark:border-white/10 flex items-center justify-between text-xs text-gray-500 dark:text-slate-400">
                <span>App Cache: Clear</span>
                <span>•</span>
                <span>Logs: Rotated</span>
                <span>•</span>
                <span>PDF Storage: Managed</span>
            </div>
        </div>

    </div>
</div>
