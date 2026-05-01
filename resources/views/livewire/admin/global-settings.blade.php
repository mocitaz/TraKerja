<div class="pb-10">
    {{-- Bento-style Settings Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 pt-2">
        
        {{-- Left: Platform Identity --}}
        <div class="lg:col-span-8 bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden h-full flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <i class="ph-duotone ph-identification-card text-lg text-primary-500"></i>
                    Platform Identity
                </h4>
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
        <div class="lg:col-span-4 bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border {{ $maintenanceMode ? 'border-red-200' : 'border-slate-100' }} overflow-hidden h-full flex flex-col">
            <div class="px-6 py-5 border-b {{ $maintenanceMode ? 'border-red-100 bg-red-50' : 'border-slate-100 bg-slate-50/50' }}">
                <h4 class="text-sm font-bold {{ $maintenanceMode ? 'text-red-700' : 'text-slate-900' }} uppercase tracking-wider flex items-center gap-2">
                    <i class="ph-duotone ph-warning-octagon text-lg"></i>
                    Maintenance
                </h4>
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

        {{-- Bottom: Quick Links to other System Pages --}}
        <div class="lg:col-span-12 grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <a href="{{ route('admin.integration-hub') }}" class="flex items-center justify-between p-6 bg-white rounded-2xl shadow-sm border border-slate-100 group hover:border-primary-100 hover:shadow-md transition-all">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="ph-duotone ph-plugs text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900">Integration Hub</h4>
                        <p class="text-xs text-slate-500">API Keys & Webhooks</p>
                    </div>
                </div>
                <i class="ph-bold ph-arrow-right text-slate-300 group-hover:text-primary-500 group-hover:translate-x-1 transition-all"></i>
            </a>

            <a href="{{ route('admin.database-maintenance') }}" class="flex items-center justify-between p-6 bg-white rounded-2xl shadow-sm border border-slate-100 group hover:border-amber-100 hover:shadow-md transition-all">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="ph-duotone ph-database text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900">Database & Storage</h4>
                        <p class="text-xs text-slate-500">Backups & Maintenance</p>
                    </div>
                </div>
                <i class="ph-bold ph-arrow-right text-slate-300 group-hover:text-amber-500 group-hover:translate-x-1 transition-all"></i>
            </a>
        </div>
    </div>
</div>

    </div>
</div>
