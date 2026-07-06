<div class="space-y-4">
    {{-- Bento-style Settings Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 text-left">
        
        {{-- Left: Platform Identity --}}
        <div class="lg:col-span-8 bg-white rounded border border-zinc-200/60 overflow-hidden flex flex-col shadow-none">
            <div class="px-4 py-3 border-b border-zinc-150/60 bg-zinc-50/20 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="ph ph-identification-card text-zinc-400 text-sm"></i>
                    <h4 class="text-xs font-bold text-zinc-900 tracking-tight">Platform Identity</h4>
                </div>
            </div>

            <div class="p-4 flex-1">
                @if (session()->has('success_identity'))
                    <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded text-xs font-semibold flex items-center gap-2 shadow-none">
                        <i class="ph ph-check-circle text-emerald-600"></i> {{ session('success_identity') }}
                    </div>
                @endif
                
                <form wire:submit.prevent="updateIdentity" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">App Name</label>
                            <input type="text" wire:model="appName" 
                                   class="w-full px-3 h-8 bg-white border border-zinc-250 rounded text-xs text-zinc-900 focus:outline-none focus:border-zinc-400 transition-colors">
                            @error('appName') <span class="text-[10px] text-red-500 font-medium">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="space-y-1">
                            <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Support Email</label>
                            <input type="email" wire:model="contactEmail" 
                                   class="w-full px-3 h-8 bg-white border border-zinc-250 rounded text-xs text-zinc-900 focus:outline-none focus:border-zinc-400 transition-colors">
                            @error('contactEmail') <span class="text-[10px] text-red-500 font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="pt-3 border-t border-zinc-150/60 flex justify-end">
                        <button type="submit" 
                                class="w-full sm:w-auto h-8 px-4 bg-zinc-900 text-white rounded text-xs font-semibold hover:bg-zinc-800 transition-colors flex items-center justify-center gap-1.5 focus:outline-none shadow-none">
                            <i class="ph ph-floppy-disk text-xs" wire:loading.remove wire:target="updateIdentity"></i>
                            <i class="ph ph-spinner animate-spin text-xs" wire:loading wire:target="updateIdentity"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right: Maintenance --}}
        <div class="lg:col-span-4 bg-white rounded border {{ $maintenanceMode ? 'border-red-200 bg-red-50/10' : 'border-zinc-200/60' }} overflow-hidden flex flex-col shadow-none">
            <div class="px-4 py-3 border-b {{ $maintenanceMode ? 'border-red-150/60 bg-red-50/30' : 'border-zinc-150/60 bg-zinc-50/20' }} flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="ph ph-warning-octagon {{ $maintenanceMode ? 'text-red-600' : 'text-zinc-400' }} text-sm"></i>
                    <h4 class="text-xs font-bold {{ $maintenanceMode ? 'text-red-700' : 'text-zinc-900' }} tracking-tight">Maintenance</h4>
                </div>
            </div>

            <div class="p-4 flex-1 flex flex-col justify-between space-y-4">
                @if (session()->has('success_maintenance') || session()->has('warning_maintenance'))
                    <div class="p-2.5 {{ session()->has('warning_maintenance') ? 'bg-red-50 border-red-200 text-red-800' : 'bg-emerald-50 border-emerald-250 text-emerald-800' }} border rounded text-[10px] font-bold flex items-center gap-1.5 shadow-none">
                        <i class="ph {{ session()->has('warning_maintenance') ? 'ph-warning-circle' : 'ph-check-circle' }}"></i>
                        {{ session('success_maintenance') ?? session('warning_maintenance') }}
                    </div>
                @endif

                <div class="space-y-4 flex-1 flex flex-col justify-between">
                    <p class="text-[11px] text-zinc-500 leading-relaxed font-sans">
                        Aktifkan mode ini untuk membatasi akses publik. Akun admin tetap bisa mengakses dashboard secara normal.
                    </p>

                    <div class="p-3 rounded border {{ $maintenanceMode ? 'border-red-100/50 bg-red-50/10' : 'border-zinc-200 bg-zinc-50' }} flex items-center justify-between">
                        <span class="text-[8px] font-mono font-bold {{ $maintenanceMode ? 'text-red-650' : 'text-zinc-450' }} uppercase tracking-wide">
                            {{ $maintenanceMode ? 'Down Mode' : 'Live Mode' }}
                        </span>
                        <button 
                            type="button"
                            wire:click="toggleMaintenance" 
                            class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors focus:outline-none shadow-none {{ $maintenanceMode ? 'bg-red-500' : 'bg-zinc-300' }}">
                            <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition-transform duration-200 {{ $maintenanceMode ? 'translate-x-4' : 'translate-x-1' }}"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Platform Limits Card --}}
        <div class="lg:col-span-12 bg-white rounded border border-zinc-200/60 overflow-hidden shadow-none">
            <div class="px-4 py-3 border-b border-zinc-150/60 bg-zinc-50/20 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="ph ph-sliders text-zinc-400 text-sm"></i>
                    <h4 class="text-xs font-bold text-zinc-900 tracking-tight">Batas Kuota &amp; Penggunaan (API Limits)</h4>
                </div>
            </div>

            <div class="p-4">
                @if (session()->has('success_limits'))
                    <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded text-xs font-semibold flex items-center gap-2 shadow-none">
                        <i class="ph ph-check-circle text-emerald-600"></i> {{ session('success_limits') }}
                    </div>
                @endif

                <form wire:submit.prevent="updateLimits" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="space-y-1">
                            <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">AI Analyzer Limit (Free)</label>
                            <div class="relative flex items-center">
                                <input type="number" min="0" wire:model="aiLimitFree" 
                                       class="w-full px-3 h-8 bg-white border border-zinc-250 rounded text-xs text-zinc-900 focus:outline-none focus:border-zinc-400 transition-colors pr-14">
                                <span class="absolute right-3 text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Analisis</span>
                            </div>
                            @error('aiLimitFree') <span class="text-[10px] text-red-500 font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">AI Analyzer Limit (Premium)</label>
                            <div class="relative flex items-center">
                                <input type="number" min="0" wire:model="aiLimitPremium" 
                                       class="w-full px-3 h-8 bg-white border border-zinc-250 rounded text-xs text-zinc-900 focus:outline-none focus:border-zinc-400 transition-colors pr-14">
                                <span class="absolute right-3 text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Analisis</span>
                            </div>
                            @error('aiLimitPremium') <span class="text-[10px] text-red-500 font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Max Job Applications (Free)</label>
                            <div class="relative flex items-center">
                                <input type="number" min="1" wire:model="jobLimitFree" 
                                       class="w-full px-3 h-8 bg-white border border-zinc-250 rounded text-xs text-zinc-900 focus:outline-none focus:border-zinc-400 transition-colors pr-14">
                                <span class="absolute right-3 text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Lamaran</span>
                            </div>
                            @error('jobLimitFree') <span class="text-[10px] text-red-500 font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="pt-3 border-t border-zinc-150/60 flex justify-end">
                        <button type="submit" 
                                class="w-full sm:w-auto h-8 px-4 bg-zinc-900 text-white rounded text-xs font-semibold hover:bg-zinc-800 transition-colors flex items-center justify-center gap-1.5 focus:outline-none shadow-none">
                            <i class="ph ph-floppy-disk text-xs" wire:loading.remove wire:target="updateLimits"></i>
                            <i class="ph ph-spinner animate-spin text-xs" wire:loading wire:target="updateLimits"></i>
                            Simpan Kuota API
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
