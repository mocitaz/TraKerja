<div class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-6 space-y-6 sm:space-y-8 pb-10">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 sm:mb-8">
        <div class="flex items-center gap-3 sm:gap-4 min-w-0">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white border border-slate-200/60 rounded-[1.25rem] sm:rounded-[1.5rem] flex items-center justify-center text-primary-600 shadow-sm shrink-0">
                <i class="ph-duotone ph-gear text-xl sm:text-2xl"></i>
            </div>
            <div class="flex flex-col min-w-0">
                <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight truncate">Global Settings</h3>
                <p class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mt-1 sm:mt-1.5 truncate">Platform Configuration & Identity</p>
            </div>
        </div>
    </div>

    {{-- Bento-style Settings Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6 pt-2">
        
        {{-- Left: Platform Identity --}}
        <div class="lg:col-span-8 bg-white rounded-[2rem] border border-slate-200/60 bento-card overflow-hidden h-full flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-50 rounded-xl border border-primary-100 flex items-center justify-center text-primary-600 shadow-sm shrink-0">
                        <i class="ph-duotone ph-identification-card text-xl"></i>
                    </div>
                    <h4 class="text-sm font-black text-slate-900 tracking-tight truncate">Platform Identity</h4>
                </div>
            </div>

            <div class="p-6 flex-1">
                @if (session()->has('success_identity'))
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl text-sm font-bold flex items-center gap-2">
                        <i class="ph-bold ph-check-circle"></i> {{ session('success_identity') }}
                    </div>
                @endif
                
                <form wire:submit.prevent="updateIdentity" class="h-full flex flex-col">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-500 ml-1">App Name</label>
                            <input type="text" wire:model="appName" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary-500 focus:bg-white transition-all">
                            @error('appName') <span class="text-[10px] text-red-500 ml-1 font-medium">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-500 ml-1">Support Email</label>
                            <input type="email" wire:model="contactEmail" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary-500 focus:bg-white transition-all">
                            @error('contactEmail') <span class="text-[10px] text-red-500 ml-1 font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-auto pt-6 border-t border-slate-50">
                        <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-slate-900 text-white rounded-xl text-sm font-bold hover:bg-slate-800 transition-all shadow-lg shadow-slate-200 flex items-center justify-center gap-2">
                            <i class="ph-bold ph-floppy-disk" wire:loading.remove wire:target="updateIdentity"></i>
                            <i class="ph-bold ph-spinner animate-spin" wire:loading wire:target="updateIdentity"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right: Maintenance --}}
        <div class="lg:col-span-4 bg-white rounded-[2rem] border {{ $maintenanceMode ? 'border-red-200 bg-red-50/10' : 'border-slate-200/60' }} bento-card overflow-hidden h-full flex flex-col">
            <div class="px-6 py-5 border-b {{ $maintenanceMode ? 'border-red-100 bg-red-50' : 'border-slate-100' }} shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 {{ $maintenanceMode ? 'bg-red-50 border-red-100 text-red-600' : 'bg-slate-50 border-slate-200 text-slate-600' }} rounded-xl border flex items-center justify-center shadow-sm shrink-0">
                        <i class="ph-duotone ph-warning-octagon text-xl"></i>
                    </div>
                    <h4 class="text-sm font-black {{ $maintenanceMode ? 'text-red-700' : 'text-slate-900' }} tracking-tight truncate">Maintenance</h4>
                </div>
            </div>

            <div class="p-6 flex-1 flex flex-col">
                @if (session()->has('success_maintenance') || session()->has('warning_maintenance'))
                    <div class="mb-6 p-3 {{ session()->has('warning_maintenance') ? 'bg-red-50 border-red-100 text-red-700' : 'bg-emerald-50 border-emerald-100 text-emerald-700' }} border rounded-xl text-xs font-bold flex items-center gap-2">
                        <i class="ph-bold {{ session()->has('warning_maintenance') ? 'ph-warning-circle' : 'ph-check-circle' }}"></i>
                        {{ session('success_maintenance') ?? session('warning_maintenance') }}
                    </div>
                @endif

                <div class="space-y-6 flex-1">
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">
                        Aktifkan mode ini untuk membatasi akses publik. Admin tetap bisa mengakses Dashboard.
                    </p>

                    <div class="p-4 rounded-xl border {{ $maintenanceMode ? 'border-red-100 bg-red-50/50' : 'border-slate-100 bg-slate-50' }} flex items-center justify-between">
                        <span class="text-xs font-black {{ $maintenanceMode ? 'text-red-600' : 'text-slate-500' }} uppercase tracking-widest">
                            {{ $maintenanceMode ? 'Down' : 'Live' }}
                        </span>
                        <button 
                            wire:click="toggleMaintenance" 
                            class="relative inline-flex h-7 w-12 items-center rounded-full transition-colors focus:outline-none {{ $maintenanceMode ? 'bg-red-500' : 'bg-slate-300' }}">
                            <span class="inline-block h-5 w-5 transform rounded-full bg-white transition-transform duration-200 {{ $maintenanceMode ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Platform Limits Card --}}
        <div class="lg:col-span-12 bg-white rounded-[2rem] border border-slate-200/60 bento-card overflow-hidden mt-6">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-50 rounded-xl border border-amber-100 flex items-center justify-center text-amber-600 shadow-sm shrink-0">
                        <i class="ph-duotone ph-sliders text-xl"></i>
                    </div>
                    <h4 class="text-sm font-black text-slate-900 tracking-tight truncate">Batas Kuota &amp; Penggunaan (API Limits)</h4>
                </div>
            </div>

            <div class="p-6">
                @if (session()->has('success_limits'))
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl text-sm font-bold flex items-center gap-2">
                        <i class="ph-bold ph-check-circle"></i> {{ session('success_limits') }}
                    </div>
                @endif

                <form wire:submit.prevent="updateLimits">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-500 ml-1">AI Analyzer Limit (Free User)</label>
                            <div class="relative flex items-center">
                                <input type="number" min="0" wire:model="aiLimitFree" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary-500 focus:bg-white transition-all">
                                <span class="absolute right-4 text-xs font-bold text-slate-400">Analisis</span>
                            </div>
                            @error('aiLimitFree') <span class="text-[10px] text-red-500 ml-1 font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-500 ml-1">AI Analyzer Limit (Premium User)</label>
                            <div class="relative flex items-center">
                                <input type="number" min="0" wire:model="aiLimitPremium" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary-500 focus:bg-white transition-all">
                                <span class="absolute right-4 text-xs font-bold text-slate-400">Analisis</span>
                            </div>
                            @error('aiLimitPremium') <span class="text-[10px] text-red-500 ml-1 font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-500 ml-1">Max Job Applications (Free User)</label>
                            <div class="relative flex items-center">
                                <input type="number" min="1" wire:model="jobLimitFree" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary-500 focus:bg-white transition-all">
                                <span class="absolute right-4 text-xs font-bold text-slate-400">Lamaran</span>
                            </div>
                            @error('jobLimitFree') <span class="text-[10px] text-red-500 ml-1 font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-50 flex justify-end">
                        <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-slate-900 text-white rounded-xl text-sm font-bold hover:bg-slate-800 transition-all shadow-lg shadow-slate-200 flex items-center justify-center gap-2">
                            <i class="ph-bold ph-floppy-disk" wire:loading.remove wire:target="updateLimits"></i>
                            <i class="ph-bold ph-spinner animate-spin" wire:loading wire:target="updateLimits"></i>
                            Simpan Kuota API
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Bottom: Quick Links to other System Pages --}}
        <div class="lg:col-span-12 grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mt-6">
            <a href="{{ route('admin.integration-hub') }}" class="flex items-center justify-between p-6 bg-white rounded-[2rem] bento-card-stat border border-slate-200/60 group transition-all">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-[1.25rem] bg-primary-50 text-primary-600 flex items-center justify-center border border-primary-100 shadow-sm">
                        <i class="ph-duotone ph-plugs text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-slate-900">Integration Hub</h4>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">API Keys & Webhooks</p>
                    </div>
                </div>
                <i class="ph-bold ph-arrow-right text-slate-300 group-hover:text-primary-500 transition-colors"></i>
            </a>

            <a href="{{ route('admin.database-maintenance') }}" class="flex items-center justify-between p-6 bg-white rounded-[2rem] bento-card-stat border border-slate-200/60 group transition-all">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-[1.25rem] bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100 shadow-sm">
                        <i class="ph-duotone ph-database text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-slate-900">Database & Storage</h4>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Backups & Maintenance</p>
                    </div>
                </div>
                <i class="ph-bold ph-arrow-right text-slate-300 group-hover:text-amber-500 transition-colors"></i>
            </a>
        </div>
    </div>
</div>

    </div>
</div>
