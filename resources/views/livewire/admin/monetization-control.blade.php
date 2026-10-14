<div class="space-y-5">

    {{-- ==========================================================================
         FEATURE ACCESS MODE SWITCHER
         ========================================================================== --}}
    <section class="am-panel" aria-labelledby="mode-panel-heading">
        <div class="am-panel-heading">
            <div>
                <h2 id="mode-panel-heading">Feature Access Mode</h2>
                <p>Changes apply globally to all member sessions upon confirmation</p>
            </div>
        </div>

        <div class="am-mode-grid">
            @foreach([
                false => [
                    'Open access',
                    'All platform features are available to every member without premium restrictions or quota gates.',
                    'ph-lock-open',
                    '#2563eb'
                ],
                true => [
                    'Premium access',
                    'Free accounts use standard trial quotas; premium accounts receive full unlimited plan benefits.',
                    'ph-crown-simple',
                    '#7751a0'
                ]
            ] as $enabled => $mode)
                @php
                    $isActive = (bool)$monetizationEnabled === (bool)$enabled;
                @endphp
                <div class="am-mode-card {{ $isActive ? 'is-active' : '' }}">
                    <div>
                        <div class="am-mode-top">
                            <span class="am-mode-badge {{ $isActive ? 'am-mode-badge--active' : 'am-mode-badge--inactive' }}">
                                @if($isActive)
                                    <i class="ph-fill ph-check-circle" aria-hidden="true"></i> Current active mode
                                @else
                                    <i class="ph ph-circle" aria-hidden="true"></i> Inactive mode
                                @endif
                            </span>
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-lg {{ $isActive ? 'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400' : 'bg-gray-100 dark:bg-zinc-800 text-gray-500 dark:text-slate-400' }}">
                                <i class="ph {{ $mode[2] }}" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="am-mode-info">
                            <h3>{{ $mode[0] }}</h3>
                            <p>{{ $mode[1] }}</p>
                        </div>
                    </div>

                    <div>
                        <button type="button" 
                                class="am-btn {{ $isActive ? 'am-btn--quiet' : 'am-btn--primary' }} w-full justify-center" 
                                wire:click="toggleMonetization({{ $enabled ? 'true' : 'false' }})" 
                                @disabled($isActive) 
                                wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="toggleMonetization({{ $enabled ? 'true' : 'false' }})">
                                {{ $isActive ? 'Active' : 'Use this mode' }}
                            </span>
                            <span wire:loading wire:target="toggleMonetization({{ $enabled ? 'true' : 'false' }})" class="flex items-center gap-1.5">
                                <i class="ph ph-spinner ph-spin"></i> Switching…
                            </span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ==========================================================================
         FEATURE ACCESS MATRIX
         ========================================================================== --}}
    <section class="am-panel" aria-labelledby="matrix-panel-heading">
        <div class="am-panel-heading">
            <div>
                <h2 id="matrix-panel-heading">Feature Access Matrix</h2>
                <p>Detailed permission entitlements between Free and Premium accounts under current mode</p>
            </div>
        </div>

        <div class="am-table-scroll" tabindex="0" aria-label="Feature Access Matrix Table">
            <table class="am-matrix-table">
                <colgroup>
                    <col style="width: 40%;">
                    <col style="width: 30%;">
                    <col style="width: 30%;">
                </colgroup>
                <thead>
                    <tr>
                        <th scope="col">Feature Capability</th>
                        <th scope="col">Free Members</th>
                        <th scope="col">Premium Members</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($featureMatrix as $feature => $access)
                        <tr>
                            <td>
                                <span class="am-feature-name">{{ $feature }}</span>
                            </td>
                            <td>
                                <span class="am-badge {{ $access['free_status'] === 'full' ? 'am-badge--full' : 'am-badge--limited' }}">
                                    @if($access['free_status'] === 'full')
                                        <i class="ph-fill ph-check-circle" aria-hidden="true"></i>
                                    @else
                                        <i class="ph-fill ph-clock" aria-hidden="true"></i>
                                    @endif
                                    {{ $access['free'] }}
                                </span>
                            </td>
                            <td>
                                <span class="am-badge am-badge--premium">
                                    <i class="ph-fill ph-crown-simple" aria-hidden="true"></i>
                                    {{ $access['premium'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    {{-- ==========================================================================
         CONFIRMATION DIALOG MODAL
         ========================================================================== --}}
    @if($showConfirmation)
        <div x-data 
             role="dialog" 
             aria-modal="true" 
             tabindex="-1" 
             x-dialog="$wire.showConfirmation" 
             data-dialog-close="$wire.cancelToggle()" 
             aria-labelledby="mode-confirm-title" 
             class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-zinc-950/40 backdrop-blur-sm transition-opacity" 
                 wire:click="cancelToggle"></div>

            {{-- Modal Card --}}
            <section class="am-modal-card relative max-w-md">
                <h2 id="mode-confirm-title">
                    {{ $actionType === 'enable' ? 'Switch to premium access?' : 'Switch to open access?' }}
                </h2>
                <p>
                    {{ $actionType === 'enable' 
                        ? 'Free members will use trial limits for premium features. Premium members keep their plan access.' 
                        : 'Premium restrictions will be removed, making features available to all members.' }}
                </p>

                <div class="am-modal-impact">
                    <span>Members affected by this change</span>
                    <strong>{{ number_format($totalUsers) }} accounts</strong>
                </div>

                <footer class="am-modal-footer">
                    <button type="button" 
                            class="am-btn am-btn--quiet" 
                            wire:click="cancelToggle">
                        Cancel
                    </button>
                    <button type="button" 
                            class="am-btn am-btn--primary" 
                            wire:click="confirmToggle" 
                            wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="confirmToggle">Confirm change</span>
                        <span wire:loading wire:target="confirmToggle" class="flex items-center gap-1.5">
                            <i class="ph ph-spinner ph-spin"></i> Saving…
                        </span>
                    </button>
                </footer>
            </section>
        </div>
    @endif

</div>
