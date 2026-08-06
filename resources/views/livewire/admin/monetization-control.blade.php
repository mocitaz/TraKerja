<div class="space-y-4">
    
    {{-- Current Status Banner (Notion Cupertino) --}}
    <div class="rounded-xl border border-zinc-200 bg-[#f7f7f5]/40 p-5 flex flex-col md:flex-row md:items-center justify-between gap-5 shadow-3xs text-left">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-white border border-zinc-200 flex items-center justify-center shrink-0 text-zinc-500 shadow-3xs">
                <i class="ph-bold {{ $monetizationEnabled ? 'ph-crown' : 'ph-gift' }} text-lg"></i>
            </div>
            <div>
                <div class="inline-flex items-center px-2 py-0.5 rounded-lg text-[8px] font-mono font-bold uppercase tracking-widest mb-1.5 border {{ $monetizationEnabled ? 'bg-zinc-900 text-white border-zinc-950' : 'bg-emerald-50 text-emerald-700 border-emerald-250' }} shadow-3xs">
                    <span class="w-1 h-1 rounded-full {{ $monetizationEnabled ? 'bg-white' : 'bg-emerald-500' }} {{ $monetizationEnabled ? '' : 'animate-pulse' }} mr-1.5"></span>
                    Active Status
                </div>
                <h3 class="text-sm font-black text-zinc-900 leading-none">
                    {{ $monetizationEnabled ? 'Premium Mode' : 'Free Mode' }}
                </h3>
                <p class="text-[11px] text-zinc-500 mt-1.5 max-w-sm font-medium">
                    @if($monetizationEnabled)
                        Pengguna premium mendapatkan akses penuh, sedangkan fitur premium terkunci untuk pengguna gratis.
                    @else
                        Semua fitur bebas digunakan oleh siapapun. Fokus pada pertumbuhan basis pengguna.
                    @endif
                </p>
            </div>
        </div>
        
        <div class="md:w-52 shrink-0 text-left md:border-l border-zinc-200 md:pl-6 pt-4 md:pt-0">
            <p class="text-[8px] font-mono font-bold uppercase tracking-widest text-zinc-400 mb-0.5">Total Active Users</p>
            <div class="flex items-baseline gap-1 mt-0.5">
                <span class="text-lg font-extrabold text-zinc-955 font-mono leading-none">{{ number_format($totalUsers) }}</span>
                <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest">Members</span>
            </div>
            <div class="w-full bg-zinc-200/80 rounded-full h-1 mt-2.5 mb-1.5">
                <div class="{{ $monetizationEnabled ? 'bg-zinc-950' : 'bg-emerald-500' }} h-1 rounded-full" style="width: {{ $totalUsers > 0 ? ($premiumUsers / $totalUsers * 100) : 0 }}%"></div>
            </div>
            <p class="text-[9px] font-mono font-bold text-zinc-500 font-bold leading-none">
                {{ number_format($premiumUsers) }} premium ({{ $totalUsers > 0 ? number_format($premiumUsers / $totalUsers * 100, 1) : 0 }}%)
            </p>
        </div>
    </div>
    
    {{-- Toggle Buttons (Bento Grid Style) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-stretch">
        {{-- FREE MODE Card --}}
        <div class="group relative flex flex-col justify-between p-5 rounded-xl border text-left transition-all duration-200 shadow-3xs {{ !$monetizationEnabled ? 'border-zinc-900 bg-[#f7f7f5]/60 ring-1 ring-zinc-900/5' : 'border-zinc-200 bg-white hover:border-zinc-350' }}">
            <div>
                <div class="flex items-center gap-3 mb-4 w-full border-b border-zinc-100 pb-3">
                    <div class="w-9 h-9 rounded-lg bg-zinc-50 border border-zinc-200 text-zinc-550 flex items-center justify-center shrink-0 shadow-3xs">
                        <i class="ph-bold ph-gift text-base"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-extrabold text-xs text-zinc-900 leading-none">Free Mode</h3>
                        <p class="text-[8px] font-mono font-bold uppercase tracking-widest mt-1 {{ !$monetizationEnabled ? 'text-emerald-600' : 'text-zinc-400' }}">Growth &amp; Acquisition</p>
                    </div>
                    @if(!$monetizationEnabled)
                        <div class="w-4.5 h-4.5 rounded-full bg-zinc-950 text-white flex items-center justify-center shrink-0 shadow-3xs">
                            <i class="ph-bold ph-check text-[9px]"></i>
                        </div>
                    @endif
                </div>
                
                <ul class="space-y-2 mb-6 text-left">
                    <li class="flex items-start gap-2 text-xs text-zinc-650 font-medium">
                        <i class="ph-bold ph-check-circle text-emerald-600 mt-0.5 shrink-0"></i>
                        <span>Semua fitur gratis untuk semua pengguna</span>
                    </li>
                    <li class="flex items-start gap-2 text-xs text-zinc-650 font-medium">
                        <i class="ph-bold ph-check-circle text-emerald-600 mt-0.5 shrink-0"></i>
                        <span>Tidak perlu langganan / pembayaran</span>
                    </li>
                    <li class="flex items-start gap-2 text-xs text-zinc-650 font-medium">
                        <i class="ph-bold ph-check-circle text-emerald-600 mt-0.5 shrink-0"></i>
                        <span>Akses tak terbatas untuk alat AI &amp; resume</span>
                    </li>
                </ul>
            </div>
            
            <div class="w-full mt-auto">
                @if(!$monetizationEnabled)
                    <div class="w-full py-2.5 bg-emerald-50 text-emerald-700 text-[10px] rounded-lg border border-emerald-250 font-bold text-center uppercase tracking-wider shadow-3xs select-none">
                        Mode Aktif Saat Ini
                    </div>
                @else
                    <button type="button" 
                            wire:click="toggleMonetization(false)"
                            class="w-full py-2.5 bg-white border border-zinc-200 hover:border-zinc-350 hover:bg-zinc-50 text-zinc-550 text-[10px] rounded-lg font-bold text-center transition-all uppercase tracking-wider shadow-3xs focus:outline-none">
                        Klik untuk Mengaktifkan
                    </button>
                @endif
            </div>
        </div>
        
        {{-- PREMIUM MODE Card --}}
        <div class="group relative flex flex-col justify-between p-5 rounded-xl border text-left transition-all duration-200 shadow-3xs {{ $monetizationEnabled ? 'border-zinc-900 bg-[#f7f7f5]/60 ring-1 ring-zinc-900/5' : 'border-zinc-200 bg-white hover:border-zinc-350' }}">
            <div>
                <div class="flex items-center gap-3 mb-4 w-full border-b border-zinc-100 pb-3">
                    <div class="w-9 h-9 rounded-lg bg-zinc-50 border border-zinc-200 text-zinc-550 flex items-center justify-center shrink-0 shadow-3xs">
                        <i class="ph-bold ph-crown text-base"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-extrabold text-xs text-zinc-900 leading-none">Premium Mode</h3>
                        <p class="text-[8px] font-mono font-bold uppercase tracking-widest mt-1 {{ $monetizationEnabled ? 'text-zinc-900 font-extrabold' : 'text-zinc-400' }}">Revenue &amp; Monetization</p>
                    </div>
                    @if($monetizationEnabled)
                        <div class="w-4.5 h-4.5 rounded-full bg-zinc-950 text-white flex items-center justify-center shrink-0 shadow-3xs">
                            <i class="ph-bold ph-check text-[9px]"></i>
                        </div>
                    @endif
                </div>
                
                <ul class="space-y-2 mb-6 text-left">
                    <li class="flex items-start gap-2 text-xs text-zinc-650 font-medium">
                        <i class="ph-bold ph-check-circle text-zinc-900 mt-0.5 shrink-0"></i>
                        <span>Pengguna gratis memiliki batasan (Smart Limits)</span>
                    </li>
                    <li class="flex items-start gap-2 text-xs text-zinc-650 font-medium">
                        <i class="ph-bold ph-star text-zinc-900 mt-0.5 shrink-0"></i>
                        <span>Pengguna premium mendapat akses penuh eksklusif</span>
                    </li>
                    <li class="flex items-start gap-2 text-xs text-zinc-650 font-medium">
                        <i class="ph-bold ph-check-circle text-zinc-900 mt-0.5 shrink-0"></i>
                        <span>Mulai hasilkan pendapatan nyata</span>
                    </li>
                </ul>
            </div>
            
            <div class="w-full mt-auto">
                @if($monetizationEnabled)
                    <div class="w-full py-2.5 bg-zinc-950 text-white text-[10px] rounded-lg border border-zinc-950 font-bold text-center uppercase tracking-wider shadow-3xs select-none">
                        Mode Aktif Saat Ini
                    </div>
                @else
                    <button type="button"
                            wire:click="toggleMonetization(true)"
                            class="w-full py-2.5 bg-white border border-zinc-200 hover:border-zinc-350 hover:bg-zinc-50 text-zinc-550 text-[10px] rounded-lg font-bold text-center transition-all uppercase tracking-wider shadow-3xs focus:outline-none">
                        Klik untuk Mengaktifkan
                    </button>
                @endif
            </div>
        </div>
    </div>
    
    {{-- Feature Access Matrix --}}
    <div class="bg-white rounded-xl border border-zinc-200 p-5 shadow-3xs text-left">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 pb-2 border-b border-zinc-200">
            <div>
                <h3 class="text-xs font-bold text-zinc-900 flex items-center gap-1.5 leading-none">
                    <i class="ph-bold ph-table text-zinc-455"></i> Feature Access Matrix
                </h3>
                <p class="text-[8px] font-mono font-bold text-zinc-400 mt-1.5 uppercase tracking-widest">Perbandingan hak akses fitur berdasarkan mode</p>
            </div>
            <div class="inline-flex items-center px-2.5 py-0.5 rounded-lg border {{ $monetizationEnabled ? 'bg-zinc-900 border-zinc-950 text-white' : 'bg-emerald-50 border-emerald-250 text-emerald-700' }} text-[8px] font-mono font-bold uppercase tracking-widest shadow-3xs">
                <i class="ph-bold {{ $monetizationEnabled ? 'ph-crown' : 'ph-gift' }} mr-1.5"></i>
                {{ $monetizationEnabled ? 'Premium Active' : 'Free Active' }}
            </div>
        </div>
        
        <div class="overflow-hidden rounded-xl border border-zinc-200 shadow-3xs">
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="bg-zinc-50/50 border-b border-zinc-200 text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider">
                        <th class="px-4 py-3 w-[40%]">Nama Fitur</th>
                        <th class="px-4 py-3 text-center border-l border-zinc-200 w-[30%]">Free Users</th>
                        <th class="px-4 py-3 text-center border-l border-zinc-200 w-[30%] bg-zinc-50/20 text-zinc-800">Premium Users</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 bg-white">
                    @foreach($featureMatrix as $feature => $access)
                        <tr class="hover:bg-[#f7f7f5]/40 transition-colors text-xs text-zinc-700 font-semibold">
                            <td class="px-4 py-3 text-zinc-800">{{ $feature }}</td>
                            <td class="px-4 py-3 text-center border-l border-zinc-100">
                                @if($access['free_status'] === 'full')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[9px] font-mono font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 leading-none">
                                        <i class="ph-bold ph-check text-emerald-555"></i> {{ $access['free'] }}
                                    </span>
                                @elseif($access['free_status'] === 'limited')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[9px] font-mono font-bold bg-amber-50 text-amber-700 border border-amber-200 leading-none">
                                        <i class="ph-bold ph-warning-circle text-amber-555"></i> {{ $access['free'] }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[9px] font-mono font-bold bg-red-50 text-red-700 border border-red-200 leading-none">
                                        <i class="ph-bold ph-lock text-red-555"></i> Locked
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center border-l border-zinc-100 bg-zinc-50/10">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[9px] font-mono font-bold bg-zinc-950 text-white leading-none shadow-3xs">
                                    <i class="ph-bold ph-star text-white"></i> {{ $access['premium'] }}
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
                <div class="relative w-full max-w-sm bg-white rounded-xl border border-zinc-200 overflow-hidden transform transition-all shadow-3xs text-left" onclick="event.stopPropagation()">
                    
                    {{-- Decorative Top Bar --}}
                    <div class="h-1.5 w-full {{ $actionType === 'enable' ? 'bg-zinc-950' : 'bg-emerald-500' }}"></div>
                    
                    <div class="p-5 text-center">
                        <div class="w-12 h-12 mx-auto rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center mb-4 text-zinc-550 shadow-3xs">
                            <i class="ph-bold {{ $actionType === 'enable' ? 'ph-crown text-zinc-800' : 'ph-gift text-emerald-600' }} text-2xl"></i>
                        </div>
                        
                        <h4 class="font-extrabold text-sm text-zinc-900 mb-1 leading-none">
                            {{ $actionType === 'enable' ? 'Aktifkan Premium Mode?' : 'Kembali ke Free Mode?' }}
                        </h4>
                        <p class="text-xs text-zinc-500 mb-4 leading-relaxed font-sans mt-2 font-medium">
                            @if($actionType === 'enable')
                                Anda akan <strong class="text-zinc-950 font-bold">MENGAKTIFKAN</strong> monetisasi. Pengguna gratis akan kehilangan akses ke beberapa fitur premium.
                            @else
                                Anda akan <strong class="text-emerald-600 font-bold">MENONAKTIFKAN</strong> monetisasi. Seluruh fitur akan terbuka 100% secara gratis untuk semua pengguna.
                            @endif
                        </p>
                        
                        <div class="text-left bg-zinc-50 rounded-xl border border-zinc-200 p-3.5 mb-5 space-y-2 shadow-3xs">
                            <p class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest">Dampak Perubahan:</p>
                            <ul class="space-y-1.5">
                                <li class="flex items-center gap-1.5 text-xs text-zinc-700 font-bold">
                                    <i class="ph-bold ph-users-three text-zinc-500 text-sm"></i>
                                    Berlaku instan untuk {{ number_format($totalUsers) }} pengguna
                                </li>
                                @if($actionType === 'enable')
                                    <li class="flex items-center gap-1.5 text-xs text-zinc-700 font-bold">
                                        <i class="ph-bold ph-lock text-zinc-900 text-sm"></i>
                                        Fitur AI &amp; kuota lamaran dibatasi untuk akun Free
                                    </li>
                                @else
                                    <li class="flex items-center gap-1.5 text-xs text-zinc-700 font-bold">
                                        <i class="ph-bold ph-lock-key-open text-emerald-500 text-sm"></i>
                                        Smart limits dan paywalls dihapus sepenuhnya
                                    </li>
                                @endif
                                <li class="flex items-center gap-1.5 text-xs text-zinc-700 font-bold">
                                    <i class="ph-bold ph-clock text-zinc-500 text-sm"></i>
                                    Perubahan ini akan dicatat ke dalam log audit
                                </li>
                            </ul>
                        </div>
                        
                        <div class="flex gap-2">
                            <button type="button"
                                wire:click="confirmToggle" 
                                class="flex-1 h-9 text-white rounded-lg text-xs font-bold transition-colors focus:outline-none shadow-3xs {{ $actionType === 'enable' ? 'bg-zinc-950 hover:bg-zinc-900' : 'bg-emerald-600 hover:bg-emerald-700' }}">
                                Ya, Konfirmasi
                            </button>
                            <button type="button"
                                wire:click="cancelToggle" 
                                class="flex-1 h-9 bg-white border border-zinc-200 hover:bg-zinc-50 text-zinc-650 rounded-lg text-xs font-bold transition-colors focus:outline-none shadow-3xs">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
</div>
