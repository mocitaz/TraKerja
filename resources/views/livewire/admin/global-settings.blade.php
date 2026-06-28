<div class="space-y-5">
    {{-- Bento-style Settings Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        
        {{-- Left: Platform Identity --}}
        <div class="lg:col-span-8 bg-white rounded-lg border border-zinc-200/80 overflow-hidden flex flex-col">
            <div class="px-4 py-3 border-b border-zinc-150 bg-zinc-50/50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="ph-bold ph-identification-card text-zinc-400 text-sm"></i>
                    <h4 class="text-xs font-bold text-zinc-900 tracking-tight">Platform Identity</h4>
                </div>
            </div>

            <div class="p-4 flex-1">
                @if (session()->has('success_identity'))
                    <div class="mb-4 p-3 bg-emerald-50 border border-emerald-250 text-emerald-800 rounded-md text-xs font-semibold flex items-center gap-2">
                        <i class="ph-bold ph-check-circle"></i> {{ session('success_identity') }}
                    </div>
                @endif
                
                <form wire:submit.prevent="updateIdentity" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="block text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider">App Name</label>
                            <input type="text" wire:model="appName" 
                                   class="w-full px-3 py-1.5 bg-zinc-50 border border-zinc-200 rounded-md text-xs text-zinc-900 focus:bg-white focus:ring-1 focus:ring-primary-600 focus:border-primary-600 transition-all">
                            @error('appName') <span class="text-[10px] text-red-500 font-medium">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="space-y-1">
                            <label class="block text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Support Email</label>
                            <input type="email" wire:model="contactEmail" 
                                   class="w-full px-3 py-1.5 bg-zinc-50 border border-zinc-200 rounded-md text-xs text-zinc-900 focus:bg-white focus:ring-1 focus:ring-primary-600 focus:border-primary-600 transition-all">
                            @error('contactEmail') <span class="text-[10px] text-red-500 font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="pt-4 border-t border-zinc-150 flex justify-end">
                        <button type="submit" 
                                class="w-full sm:w-auto px-4 py-1.5 bg-zinc-900 text-white rounded-md text-xs font-semibold hover:bg-zinc-800 transition-all flex items-center justify-center gap-1.5">
                            <i class="ph-bold ph-floppy-disk" wire:loading.remove wire:target="updateIdentity"></i>
                            <i class="ph-bold ph-spinner animate-spin" wire:loading wire:target="updateIdentity"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right: Maintenance --}}
        <div class="lg:col-span-4 bg-white rounded-lg border {{ $maintenanceMode ? 'border-red-200 bg-red-50/10' : 'border-zinc-200/80' }} overflow-hidden flex flex-col">
            <div class="px-4 py-3 border-b {{ $maintenanceMode ? 'border-red-150 bg-red-50/50' : 'border-zinc-150 bg-zinc-50/50' }} flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="ph-bold ph-warning-octagon {{ $maintenanceMode ? 'text-red-550' : 'text-zinc-400' }} text-sm"></i>
                    <h4 class="text-xs font-bold {{ $maintenanceMode ? 'text-red-700' : 'text-zinc-900' }} tracking-tight">Maintenance</h4>
                </div>
            </div>

            <div class="p-4 flex-1 flex flex-col justify-between space-y-4">
                @if (session()->has('success_maintenance') || session()->has('warning_maintenance'))
                    <div class="p-2.5 {{ session()->has('warning_maintenance') ? 'bg-red-50 border-red-200 text-red-800' : 'bg-emerald-50 border-emerald-250 text-emerald-800' }} border rounded-md text-[11px] font-semibold flex items-center gap-1.5">
                        <i class="ph-bold {{ session()->has('warning_maintenance') ? 'ph-warning-circle' : 'ph-check-circle' }}"></i>
                        {{ session('success_maintenance') ?? session('warning_maintenance') }}
                    </div>
                @endif

                <div class="space-y-4 flex-1">
                    <p class="text-[11px] text-zinc-500 leading-relaxed">
                        Aktifkan mode ini untuk membatasi akses publik. Akun admin tetap bisa mengakses dashboard secara normal.
                    </p>

                    <div class="p-3 rounded-md border {{ $maintenanceMode ? 'border-red-100 bg-red-50/30' : 'border-zinc-200 bg-zinc-50' }} flex items-center justify-between">
                        <span class="text-[9px] font-mono font-bold {{ $maintenanceMode ? 'text-red-650' : 'text-zinc-450' }} uppercase tracking-wider">
                            {{ $maintenanceMode ? 'Down Mode' : 'Live Mode' }}
                        </span>
                        <button 
                            wire:click="toggleMaintenance" 
                            class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors focus:outline-none {{ $maintenanceMode ? 'bg-red-500' : 'bg-zinc-300' }}">
                            <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition-transform duration-200 {{ $maintenanceMode ? 'translate-x-4' : 'translate-x-1' }}"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Platform Limits Card --}}
        <div class="lg:col-span-12 bg-white rounded-lg border border-zinc-200/80 overflow-hidden">
            <div class="px-4 py-3 border-b border-zinc-150 bg-zinc-50/50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="ph-bold ph-sliders text-zinc-400 text-sm"></i>
                    <h4 class="text-xs font-bold text-zinc-900 tracking-tight">Batas Kuota &amp; Penggunaan (API Limits)</h4>
                </div>
            </div>

            <div class="p-4">
                @if (session()->has('success_limits'))
                    <div class="mb-4 p-3 bg-emerald-50 border border-emerald-250 text-emerald-800 rounded-md text-xs font-semibold flex items-center gap-2">
                        <i class="ph-bold ph-check-circle"></i> {{ session('success_limits') }}
                    </div>
                @endif

                <form wire:submit.prevent="updateLimits" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="space-y-1">
                            <label class="block text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider">AI Analyzer Limit (Free)</label>
                            <div class="relative flex items-center">
                                <input type="number" min="0" wire:model="aiLimitFree" 
                                       class="w-full px-3 py-1.5 bg-zinc-50 border border-zinc-200 rounded-md text-xs text-zinc-900 focus:bg-white focus:ring-1 focus:ring-primary-600 focus:border-primary-600 transition-all pr-14">
                                <span class="absolute right-3 text-[10px] font-mono text-zinc-400">Analisis</span>
                            </div>
                            @error('aiLimitFree') <span class="text-[10px] text-red-500 font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider">AI Analyzer Limit (Premium)</label>
                            <div class="relative flex items-center">
                                <input type="number" min="0" wire:model="aiLimitPremium" 
                                       class="w-full px-3 py-1.5 bg-zinc-50 border border-zinc-200 rounded-md text-xs text-zinc-900 focus:bg-white focus:ring-1 focus:ring-primary-600 focus:border-primary-600 transition-all pr-14">
                                <span class="absolute right-3 text-[10px] font-mono text-zinc-400">Analisis</span>
                            </div>
                            @error('aiLimitPremium') <span class="text-[10px] text-red-500 font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Max Job Applications (Free)</label>
                            <div class="relative flex items-center">
                                <input type="number" min="1" wire:model="jobLimitFree" 
                                       class="w-full px-3 py-1.5 bg-zinc-50 border border-zinc-200 rounded-md text-xs text-zinc-900 focus:bg-white focus:ring-1 focus:ring-primary-600 focus:border-primary-600 transition-all pr-14">
                                <span class="absolute right-3 text-[10px] font-mono text-zinc-400">Lamaran</span>
                            </div>
                            @error('jobLimitFree') <span class="text-[10px] text-red-500 font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="pt-4 border-t border-zinc-150 flex justify-end">
                        <button type="submit" 
                                class="w-full sm:w-auto px-4 py-1.5 bg-zinc-900 text-white rounded-md text-xs font-semibold hover:bg-zinc-800 transition-all flex items-center justify-center gap-1.5">
                            <i class="ph-bold ph-floppy-disk" wire:loading.remove wire:target="updateLimits"></i>
                            <i class="ph-bold ph-spinner animate-spin" wire:loading wire:target="updateLimits"></i>
                            Simpan Kuota API
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
