<div class="space-y-5">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 text-left">

        {{-- Left: Platform Identity --}}
        <div class="lg:col-span-8 ast-panel">
            <div class="ast-panel-heading">
                <h3>
                    <i class="ph ph-identification-card" aria-hidden="true"></i>
                    Platform Identity
                </h3>
            </div>

            <div class="ast-panel-body">
                @if (session()->has('success_identity'))
                    <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-2.5 text-xs">
                        <i class="ph-bold ph-check-circle text-emerald-600 text-base"></i>
                        <span>{{ session('success_identity') }}</span>
                    </div>
                @endif

                <form wire:submit.prevent="updateIdentity" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="ast-form-group">
                            <label>Application Name</label>
                            <input type="text" wire:model="appName" placeholder="TraKerja">
                            @error('appName') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div class="ast-form-group">
                            <label>Support Contact Email</label>
                            <input type="email" wire:model="contactEmail" placeholder="support@trakerja.com">
                            @error('contactEmail') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="ast-btn ast-btn--primary">
                            <i class="ph ph-floppy-disk" wire:loading.remove wire:target="updateIdentity" aria-hidden="true"></i>
                            <i class="ph ph-spinner ph-spin" wire:loading wire:target="updateIdentity" aria-hidden="true"></i>
                            Save Platform Identity
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right: Maintenance Mode --}}
        <div class="lg:col-span-4 ast-panel">
            <div class="ast-panel-heading">
                <h3>
                    <i class="ph ph-warning-octagon {{ $maintenanceMode ? 'text-red-600' : '' }}" aria-hidden="true"></i>
                    Maintenance Mode
                </h3>
            </div>

            <div class="ast-panel-body justify-between">
                @if (session()->has('success_maintenance') || session()->has('warning_maintenance'))
                    <div class="p-3 {{ session()->has('warning_maintenance') ? 'bg-red-50 border-red-200 text-red-800' : 'bg-emerald-50 border-emerald-200 text-emerald-800' }} border rounded-xl text-xs flex items-center gap-2">
                        <i class="ph-bold {{ session()->has('warning_maintenance') ? 'ph-warning-circle' : 'ph-check-circle' }}"></i>
                        <span>{{ session('success_maintenance') ?? session('warning_maintenance') }}</span>
                    </div>
                @endif

                <p class="text-xs text-gray-500 leading-relaxed">
                    Activate maintenance mode to restrict public access during critical upgrades. Admin sessions remain fully functional.
                </p>

                <div class="ast-toggle-item mt-4">
                    <div>
                        <strong>{{ $maintenanceMode ? 'Maintenance Active' : 'System Live' }}</strong>
                        <p>{{ $maintenanceMode ? 'Public access is disabled' : 'All systems operational' }}</p>
                    </div>
                    <button type="button"
                            wire:click="toggleMaintenance" 
                            role="switch" 
                            aria-label="Maintenance mode" 
                            aria-checked="{{ $maintenanceMode ? 'true' : 'false' }}"
                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none {{ $maintenanceMode ? 'bg-red-600' : 'bg-gray-300' }}">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200 {{ $maintenanceMode ? 'translate-x-6' : 'translate-x-1' }}"></span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Platform Usage Limits (API Limits) --}}
        <div class="lg:col-span-12 ast-panel">
            <div class="ast-panel-heading">
                <h3>
                    <i class="ph ph-sliders-horizontal" aria-hidden="true"></i>
                    Quota & Platform Limits
                </h3>
            </div>

            <div class="ast-panel-body">
                @if (session()->has('success_limits'))
                    <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-2.5 text-xs">
                        <i class="ph-bold ph-check-circle text-emerald-600 text-base"></i>
                        <span>{{ session('success_limits') }}</span>
                    </div>
                @endif

                <form wire:submit.prevent="updateLimits" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div class="ast-form-group">
                            <label>AI Analyzer Limit (Free Tier)</label>
                            <input type="number" min="0" wire:model="aiLimitFree" placeholder="e.g. 1">
                            @error('aiLimitFree') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div class="ast-form-group">
                            <label>AI Analyzer Limit (Premium Tier)</label>
                            <input type="number" min="0" wire:model="aiLimitPremium" placeholder="e.g. 50">
                            @error('aiLimitPremium') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div class="ast-form-group">
                            <label>Max Job Applications (Free Tier)</label>
                            <input type="number" min="1" wire:model="jobLimitFree" placeholder="e.g. 50">
                            @error('jobLimitFree') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="ast-btn ast-btn--primary">
                            <i class="ph ph-floppy-disk" wire:loading.remove wire:target="updateLimits" aria-hidden="true"></i>
                            <i class="ph ph-spinner ph-spin" wire:loading wire:target="updateLimits" aria-hidden="true"></i>
                            Save Platform Quotas
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
