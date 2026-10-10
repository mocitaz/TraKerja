<div class="space-y-4">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 text-left">

        {{-- Left: Platform Identity --}}
        <div class="lg:col-span-8 ops-legacy-panel bg-white rounded-2xl border border-[#e7e3e9] overflow-hidden flex flex-col ">
            <div class="px-5 py-4 border-b border-zinc-100 bg-zinc-50/20 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="ph-bold ph-identification-card text-zinc-500 text-sm"></i>
                    <h4 class="text-xs font-medium text-zinc-900 tracking-tight">Platform Identity</h4>
                </div>
            </div>

            <div class="p-5 flex-1">
                @if (session()->has('success_identity'))
                    <div class="mb-4 p-3.5 bg-emerald-50 border border-emerald-250 text-emerald-800 rounded-2xl flex items-center gap-2.5 ">
                        <i class="ph-bold ph-check-circle text-emerald-600"></i> {{ session('success_identity') }}
                    </div>
                @endif

                <form wire:submit.prevent="updateIdentity" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-[11px] font-sans font-medium text-zinc-500  ">App Name</label>
                            <input type="text" wire:model="appName"
                                   class="w-full px-3 h-9 bg-white border border-[#e7e3e9] rounded-lg text-xs font-semibold text-zinc-800 focus:outline-none focus:border-zinc-450 transition-colors ">
                            @error('appName') <span class="text-[11px] text-red-500 font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-[11px] font-sans font-medium text-zinc-500  ">Support Email</label>
                            <input type="email" wire:model="contactEmail"
                                   class="w-full px-3 h-9 bg-white border border-[#e7e3e9] rounded-lg text-xs font-semibold text-zinc-800 focus:outline-none focus:border-zinc-450 transition-colors ">
                            @error('contactEmail') <span class="text-[11px] text-red-500 font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="pt-4 border-t border-zinc-100 flex justify-end">
                        <button type="submit"
                                class="w-full sm:w-auto h-9 px-4 bg-zinc-900 text-white rounded-lg text-xs font-medium hover:bg-zinc-800 transition-colors flex items-center justify-center gap-1.5 focus:outline-none ">
                            <i class="ph-bold ph-floppy-disk text-xs" wire:loading.remove wire:target="updateIdentity"></i>
                            <i class="ph-bold ph-spinner animate-spin text-xs" wire:loading wire:target="updateIdentity"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right: Maintenance --}}
        <div class="lg:col-span-4 ops-legacy-panel bg-white rounded-2xl border {{ $maintenanceMode ? 'border-red-200 bg-red-50/10' : 'border-[#e7e3e9]' }} overflow-hidden flex flex-col ">
            <div class="px-5 py-4 border-b {{ $maintenanceMode ? 'border-red-150/60 bg-red-50/30' : 'border-zinc-100 bg-zinc-50/20' }} flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="ph-bold ph-warning-octagon {{ $maintenanceMode ? 'text-red-600' : 'text-zinc-500' }} text-sm"></i>
                    <h4 class="text-xs font-medium {{ $maintenanceMode ? 'text-red-700' : 'text-zinc-900' }} tracking-tight">Maintenance</h4>
                </div>
            </div>

            <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                @if (session()->has('success_maintenance') || session()->has('warning_maintenance'))
                    <div class="p-3 {{ session()->has('warning_maintenance') ? 'bg-red-50 border-red-200 text-red-800' : 'bg-emerald-50 border-emerald-250 text-emerald-800' }} border rounded-lg text-[11px] font-medium flex items-center gap-1.5 ">
                        <i class="ph-bold {{ session()->has('warning_maintenance') ? 'ph-warning-circle' : 'ph-check-circle' }}"></i>
                        {{ session('success_maintenance') ?? session('warning_maintenance') }}
                    </div>
                @endif

                <div class="space-y-4 flex-1 flex flex-col justify-between">
                    <p class="text-[11px] text-zinc-500 leading-relaxed font-sans font-medium">
                        Aktifkan mode ini untuk membatasi akses publik. Akun admin tetap bisa mengakses dashboard secara normal.
                    </p>

                    <div class="p-3.5 rounded-2xl border {{ $maintenanceMode ? 'border-red-100 bg-red-50/10' : 'border-[#e7e3e9] bg-zinc-50/30' }} flex items-center justify-between">
                        <span class="text-[11px] font-sans font-medium {{ $maintenanceMode ? 'text-red-650' : 'text-zinc-500' }}  ">
                            {{ $maintenanceMode ? 'Down Mode' : 'Live Mode' }}
                        </span>
                        <button
                            type="button"
                            wire:click="toggleMaintenance" role="switch" aria-label="Maintenance mode" aria-checked="{{ $maintenanceMode ? 'true' : 'false' }}"
                            class="relative inline-flex h-5.5 w-9.5 items-center rounded-full transition-colors focus:outline-none  {{ $maintenanceMode ? 'bg-red-500' : 'bg-zinc-350' }}">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200 {{ $maintenanceMode ? 'translate-x-4.5' : 'translate-x-1' }}"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Platform Limits Card --}}
        <div class="lg:col-span-12 ops-legacy-panel bg-white rounded-2xl border border-[#e7e3e9] overflow-hidden ">
            <div class="px-5 py-4 border-b border-zinc-100 bg-zinc-50/20 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="ph-bold ph-sliders-horizontal text-zinc-500 text-sm"></i>
                    <h4 class="text-xs font-medium text-zinc-900 tracking-tight">Batas Kuota &amp; Penggunaan (API Limits)</h4>
                </div>
            </div>

            <div class="p-5">
                @if (session()->has('success_limits'))
                    <div class="mb-4 p-3.5 bg-emerald-50 border border-emerald-250 text-emerald-800 rounded-2xl flex items-center gap-2.5 ">
                        <i class="ph-bold ph-check-circle text-emerald-600"></i> {{ session('success_limits') }}
                    </div>
                @endif

                <form wire:submit.prevent="updateLimits" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-[11px] font-sans font-medium text-zinc-500  ">AI Analyzer Limit (Free)</label>
                            <div class="relative flex items-center">
                                <input type="number" min="0" wire:model="aiLimitFree"
                                       class="w-full px-3 h-9 bg-white border border-[#e7e3e9] rounded-lg text-xs font-semibold text-zinc-800 focus:outline-none focus:border-zinc-450 transition-colors pr-16 ">
                                <span class="absolute right-3 text-[11px] font-sans font-medium text-zinc-500  tracking-wide">Analisis</span>
                            </div>
                            @error('aiLimitFree') <span class="text-[11px] text-red-500 font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-[11px] font-sans font-medium text-zinc-500  ">AI Analyzer Limit (Premium)</label>
                            <div class="relative flex items-center">
                                <input type="number" min="0" wire:model="aiLimitPremium"
                                       class="w-full px-3 h-9 bg-white border border-[#e7e3e9] rounded-lg text-xs font-semibold text-zinc-800 focus:outline-none focus:border-zinc-450 transition-colors pr-16 ">
                                <span class="absolute right-3 text-[11px] font-sans font-medium text-zinc-500  tracking-wide">Analisis</span>
                            </div>
                            @error('aiLimitPremium') <span class="text-[11px] text-red-500 font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-[11px] font-sans font-medium text-zinc-500  ">Max Job Applications (Free)</label>
                            <div class="relative flex items-center">
                                <input type="number" min="1" wire:model="jobLimitFree"
                                       class="w-full px-3 h-9 bg-white border border-[#e7e3e9] rounded-lg text-xs font-semibold text-zinc-800 focus:outline-none focus:border-zinc-450 transition-colors pr-16 ">
                                <span class="absolute right-3 text-[11px] font-sans font-medium text-zinc-500  tracking-wide">Lamaran</span>
                            </div>
                            @error('jobLimitFree') <span class="text-[11px] text-red-500 font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="pt-4 border-t border-zinc-100 flex justify-end">
                        <button type="submit"
                                class="w-full sm:w-auto h-9 px-4 bg-zinc-900 text-white rounded-lg text-xs font-medium hover:bg-zinc-800 transition-colors flex items-center justify-center gap-1.5 focus:outline-none ">
                            <i class="ph-bold ph-floppy-disk text-xs" wire:loading.remove wire:target="updateLimits"></i>
                            <i class="ph-bold ph-spinner animate-spin text-xs" wire:loading wire:target="updateLimits"></i>
                            Simpan Kuota API
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
