<div class="space-y-4">
    
    {{-- Current Status Banner (Notion Cupertino) --}}
    <div class="rounded border border-zinc-200/60 bg-[#f7f7f5] p-4 flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-none text-left">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded bg-white border border-zinc-200/80 flex items-center justify-center shrink-0 text-zinc-500">
                <i class="ph {{ $monetizationEnabled ? 'ph-crown' : 'ph-gift' }} text-lg"></i>
            </div>
            <div>
                <div class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-mono font-bold uppercase tracking-wider mb-1 border {{ $monetizationEnabled ? 'bg-purple-50 text-purple-700 border-purple-150' : 'bg-emerald-50 text-emerald-700 border-emerald-150' }}">
                    <span class="w-1 h-1 rounded-full {{ $monetizationEnabled ? 'bg-purple-500' : 'bg-emerald-500' }} animate-pulse mr-1"></span>
                    Active Status
                </div>
                <h3 class="text-sm font-bold text-zinc-900 leading-none">
                    {{ $monetizationEnabled ? 'Premium Mode' : 'Free Mode' }}
                </h3>
                <p class="text-[11px] text-zinc-500 mt-1 max-w-sm">
                    @if($monetizationEnabled)
                        Pengguna premium mendapatkan akses penuh, sedangkan fitur premium terkunci untuk pengguna gratis.
                    @else
                        Semua fitur bebas digunakan oleh siapapun. Fokus pada pertumbuhan basis pengguna.
                    @endif
                </p>
            </div>
        </div>
        
        <div class="bg-white border border-zinc-200/80 rounded p-3 md:w-44 shrink-0">
            <p class="text-[8px] font-mono font-bold uppercase tracking-wide text-zinc-400 mb-0.5">Total Active Users</p>
            <p class="text-lg font-bold text-zinc-950 leading-tight">{{ number_format($totalUsers) }}</p>
            <div class="w-full bg-zinc-100 rounded-full h-1 mt-2 mb-1">
                <div class="{{ $monetizationEnabled ? 'bg-purple-650' : 'bg-emerald-600' }} h-1 rounded-full" style="width: {{ $totalUsers > 0 ? ($premiumUsers / $totalUsers * 100) : 0 }}%"></div>
            </div>
            <p class="text-[9px] font-mono font-bold text-zinc-500 mt-1">
                {{ number_format($premiumUsers) }} premium ({{ $totalUsers > 0 ? number_format($premiumUsers / $totalUsers * 100, 1) : 0 }}%)
            </p>
        </div>
    </div>
    
    {{-- Toggle Buttons (Bento Grid Style) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- FREE MODE Button --}}
        <button 
            type="button"
            wire:click="toggleMonetization(false)"
            class="group relative flex flex-col items-start p-4 rounded border text-left transition-colors focus:outline-none shadow-none {{ !$monetizationEnabled ? 'border-zinc-900 bg-[#f7f7f5]/40' : 'border-zinc-200/60 bg-white hover:bg-[#f7f7f5]/20' }}">
            
            <div class="flex items-center gap-3 mb-3 w-full">
                <div class="w-9 h-9 rounded bg-zinc-50 border border-zinc-200/40 text-zinc-550 flex items-center justify-center shrink-0">
                    <i class="ph ph-gift text-base"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-xs text-zinc-900 leading-none">Free Mode</h3>
                    <p class="text-[8px] font-mono font-bold uppercase tracking-wide mt-1 {{ !$monetizationEnabled ? 'text-emerald-600' : 'text-zinc-400' }}">Growth &amp; Acquisition</p>
                </div>
                @if(!$monetizationEnabled)
                    <div class="w-4 h-4 rounded-full bg-zinc-900 text-white flex items-center justify-center shrink-0">
                        <i class="ph ph-check text-[9px]"></i>
                    </div>
                @endif
            </div>
            
            <ul class="space-y-1.5 mb-4 flex-1 text-left">
                <li class="flex items-center gap-1.5 text-xs text-zinc-550">
                    <i class="ph ph-check-circle text-emerald-600"></i>
                    <span>Semua fitur gratis untuk semua pengguna</span>
                </li>
                <li class="flex items-center gap-1.5 text-xs text-zinc-550">
                    <i class="ph ph-check-circle text-emerald-600"></i>
                    <span>Tidak perlu langganan / pembayaran</span>
                </li>
                <li class="flex items-center gap-1.5 text-xs text-zinc-550">
                    <i class="ph ph-check-circle text-emerald-600"></i>
                    <span>Akses tak terbatas untuk alat AI &amp; resume</span>
                </li>
            </ul>
            
            <div class="w-full">
                @if(!$monetizationEnabled)
                    <div class="w-full py-1.5 bg-emerald-50 text-emerald-700 text-[10px] rounded border border-emerald-150 font-bold text-center uppercase tracking-wide">
                        Mode Aktif Saat Ini
                    </div>
                @else
                    <div class="w-full py-1.5 bg-white border border-zinc-250 text-zinc-500 text-[10px] rounded font-bold text-center group-hover:bg-zinc-50 transition-colors uppercase tracking-wide">
                        Klik untuk Mengaktifkan
                    </div>
                @endif
            </div>
        </button>
        
        {{-- PREMIUM MODE Button --}}
        <button 
            type="button"
            wire:click="toggleMonetization(true)"
            class="group relative flex flex-col items-start p-4 rounded border text-left transition-colors focus:outline-none shadow-none {{ $monetizationEnabled ? 'border-zinc-900 bg-[#f7f7f5]/40' : 'border-zinc-200/60 bg-white hover:bg-[#f7f7f5]/20' }}">
            
            <div class="flex items-center gap-3 mb-3 w-full">
                <div class="w-9 h-9 rounded bg-zinc-50 border border-zinc-200/40 text-zinc-550 flex items-center justify-center shrink-0">
                    <i class="ph ph-crown text-base"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-xs text-zinc-900 leading-none">Premium Mode</h3>
                    <p class="text-[8px] font-mono font-bold uppercase tracking-wide mt-1 {{ $monetizationEnabled ? 'text-purple-600' : 'text-zinc-400' }}">Revenue &amp; Monetization</p>
                </div>
                @if($monetizationEnabled)
                    <div class="w-4 h-4 rounded-full bg-zinc-900 text-white flex items-center justify-center shrink-0">
                        <i class="ph ph-check text-[9px]"></i>
                    </div>
                @endif
            </div>
            
            <ul class="space-y-1.5 mb-4 flex-1 text-left">
                <li class="flex items-center gap-1.5 text-xs text-zinc-550">
                    <i class="ph ph-check-circle text-purple-650"></i>
                    <span>Pengguna gratis memiliki batasan (Smart Limits)</span>
                </li>
                <li class="flex items-center gap-1.5 text-xs text-zinc-550">
                    <i class="ph ph-star text-purple-600"></i>
                    <span>Pengguna premium mendapat akses penuh eksklusif</span>
                </li>
                <li class="flex items-center gap-1.5 text-xs text-zinc-550">
                    <i class="ph ph-check-circle text-purple-650"></i>
                    <span>Mulai hasilkan pendapatan nyata</span>
                </li>
            </ul>
            
            <div class="w-full">
                @if($monetizationEnabled)
                    <div class="w-full py-1.5 bg-purple-50 text-purple-700 text-[10px] rounded border border-purple-150 font-bold text-center uppercase tracking-wide">
                        Mode Aktif Saat Ini
                    </div>
                @else
                    <div class="w-full py-1.5 bg-white border border-zinc-250 text-zinc-500 text-[10px] rounded font-bold text-center group-hover:bg-zinc-50 transition-colors uppercase tracking-wide">
                        Klik untuk Mengaktifkan
                    </div>
                @endif
            </div>
        </button>
    </div>
    
    {{-- Feature Access Matrix --}}
    <div class="bg-white rounded border border-zinc-200/60 p-4 shadow-none text-left">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 pb-2 border-b border-zinc-150/60">
            <div>
                <h3 class="text-xs font-bold text-zinc-900 flex items-center gap-1.5 leading-none">
                    <i class="ph ph-table text-zinc-400"></i> Feature Access Matrix
                </h3>
                <p class="text-[8px] font-mono font-bold text-zinc-400 mt-1 uppercase tracking-wide">Perbandingan hak akses fitur berdasarkan mode</p>
            </div>
            <div class="inline-flex items-center px-2 py-0.5 rounded border {{ $monetizationEnabled ? 'bg-purple-50 border-purple-150 text-purple-750' : 'bg-emerald-50 border-emerald-150 text-emerald-700' }} text-[8px] font-mono font-bold uppercase tracking-wider">
                <i class="ph {{ $monetizationEnabled ? 'ph-crown' : 'ph-gift' }} mr-1"></i>
                {{ $monetizationEnabled ? 'Premium Active' : 'Free Active' }}
            </div>
        </div>
        
        <div class="overflow-x-auto rounded border border-zinc-200/60">
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="bg-zinc-50/50 border-b border-zinc-200 text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider">
                        <th class="px-4 py-2.5 w-[40%]">Nama Fitur</th>
                        <th class="px-4 py-2.5 text-center border-l border-zinc-200 w-[30%]">Free Users</th>
                        <th class="px-4 py-2.5 text-center border-l border-zinc-200 w-[30%] bg-purple-50/20 text-purple-700">Premium Users</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-150/30 bg-white">
                    @foreach($featureMatrix as $feature => $access)
                        <tr class="hover:bg-[#f7f7f5]/40 transition-colors text-xs text-zinc-800">
                            <td class="px-4 py-2.5 font-semibold text-xs text-zinc-800">{{ $feature }}</td>
                            <td class="px-4 py-2.5 text-center border-l border-zinc-150/30">
                                @if($access['free_status'] === 'full')
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-mono font-bold bg-emerald-50 text-emerald-700 border border-emerald-150 leading-none">
                                        <i class="ph ph-check text-emerald-500"></i> {{ $access['free'] }}
                                    </span>
                                @elseif($access['free_status'] === 'limited')
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-mono font-bold bg-amber-50 text-amber-700 border border-amber-200 leading-none">
                                        <i class="ph ph-warning-circle text-amber-500"></i> {{ $access['free'] }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-mono font-bold bg-red-50 text-red-700 border border-red-150 leading-none">
                                        <i class="ph ph-lock text-red-500"></i> Locked
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-2.5 text-center border-l border-zinc-150/30 bg-purple-50/10">
                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-mono font-bold bg-purple-50 text-purple-750 border border-purple-150 leading-none">
                                    <i class="ph ph-star text-purple-500"></i> {{ $access['premium'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    {{-- Confirmation Modal --}}
    @if($showConfirmation)
        <div class="fixed inset-0 z-[100] overflow-y-auto animate-fade-in">
            <div class="fixed inset-0 bg-zinc-950/20 backdrop-blur-[2px] transition-opacity" wire:click="cancelToggle"></div>
            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative w-full max-w-sm bg-white rounded border border-zinc-200/60 overflow-hidden transform transition-all shadow-none text-left" onclick="event.stopPropagation()">
                    
                    {{-- Decorative Top Bar --}}
                    <div class="h-1.5 w-full {{ $actionType === 'enable' ? 'bg-purple-500' : 'bg-emerald-500' }}"></div>
                    
                    <div class="p-5 text-center">
                        <div class="w-12 h-12 mx-auto rounded bg-zinc-50 border border-zinc-200/40 flex items-center justify-center mb-4 text-zinc-500">
                            <i class="ph {{ $actionType === 'enable' ? 'ph-crown text-purple-650' : 'ph-gift text-emerald-600' }} text-2xl"></i>
                        </div>
                        
                        <h4 class="font-bold text-sm text-zinc-900 mb-1 leading-none">
                            {{ $actionType === 'enable' ? 'Aktifkan Premium Mode?' : 'Kembali ke Free Mode?' }}
                        </h4>
                        <p class="text-xs text-zinc-500 mb-4 leading-relaxed font-sans mt-2">
                            @if($actionType === 'enable')
                                Anda akan <strong class="text-purple-600">MENGAKTIFKAN</strong> monetisasi. Pengguna gratis akan kehilangan akses ke beberapa fitur premium.
                            @else
                                Anda akan <strong class="text-emerald-600">MENONAKTIFKAN</strong> monetisasi. Seluruh fitur akan terbuka 100% secara gratis untuk semua pengguna.
                            @endif
                        </p>
                        
                        <div class="text-left bg-zinc-50 rounded border border-zinc-200/80 p-3.5 mb-5 space-y-2">
                            <p class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Dampak Perubahan:</p>
                            <ul class="space-y-1.5">
                                <li class="flex items-center gap-1.5 text-xs text-zinc-700 font-semibold">
                                    <i class="ph ph-users-three text-zinc-450 text-sm"></i>
                                    Berlaku instan untuk {{ number_format($totalUsers) }} pengguna
                                </li>
                                @if($actionType === 'enable')
                                    <li class="flex items-center gap-1.5 text-xs text-zinc-700 font-semibold">
                                        <i class="ph ph-lock text-purple-500 text-sm"></i>
                                        Fitur AI &amp; kuota lamaran dibatasi untuk akun Free
                                    </li>
                                @else
                                    <li class="flex items-center gap-1.5 text-xs text-zinc-700 font-semibold">
                                        <i class="ph ph-lock-key-open text-emerald-500 text-sm"></i>
                                        Smart limits dan paywalls dihapus sepenuhnya
                                    </li>
                                @endif
                                <li class="flex items-center gap-1.5 text-xs text-zinc-700 font-semibold">
                                    <i class="ph ph-clock text-zinc-450 text-sm"></i>
                                    Perubahan ini akan dicatat ke dalam log audit
                                </li>
                            </ul>
                        </div>
                        
                        <div class="flex gap-2">
                            <button type="button"
                                wire:click="confirmToggle" 
                                class="flex-1 h-8 text-white rounded text-xs font-semibold transition-colors focus:outline-none shadow-none {{ $actionType === 'enable' ? 'bg-purple-650 hover:bg-purple-750' : 'bg-emerald-600 hover:bg-emerald-700' }}">
                                Ya, Konfirmasi
                            </button>
                            <button type="button"
                                wire:click="cancelToggle" 
                                class="flex-1 h-8 bg-white border border-zinc-250 hover:bg-zinc-50 text-zinc-650 rounded text-xs font-semibold transition-colors focus:outline-none shadow-none">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
</div>
