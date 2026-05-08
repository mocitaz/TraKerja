<div class="space-y-6">
    
    {{-- Current Status Banner --}}
    <div class="relative overflow-hidden rounded-2xl p-6 md:p-8 {{ $monetizationEnabled ? 'bg-gradient-to-br from-purple-900 to-slate-900 text-white shadow-xl shadow-purple-900/20' : 'bg-gradient-to-br from-emerald-900 to-slate-900 text-white shadow-xl shadow-emerald-900/20' }}">
        <!-- Background Ornaments -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full opacity-10 {{ $monetizationEnabled ? 'bg-purple-500' : 'bg-emerald-500' }} blur-3xl mix-blend-screen pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 rounded-full opacity-20 {{ $monetizationEnabled ? 'bg-fuchsia-500' : 'bg-teal-500' }} blur-2xl mix-blend-screen pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-inner backdrop-blur-md {{ $monetizationEnabled ? 'bg-white/10 text-purple-300' : 'bg-white/10 text-emerald-300' }} border border-white/10">
                    <i class="ph-duotone {{ $monetizationEnabled ? 'ph-crown' : 'ph-gift' }} text-4xl"></i>
                </div>
                <div>
                    <div class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest mb-2 border {{ $monetizationEnabled ? 'bg-purple-500/20 text-purple-200 border-purple-500/30' : 'bg-emerald-500/20 text-emerald-200 border-emerald-500/30' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $monetizationEnabled ? 'bg-purple-400' : 'bg-emerald-400' }} animate-pulse mr-1.5"></span>
                        Active Status
                    </div>
                    <h3 class="text-2xl md:text-3xl font-black tracking-tight">
                        {{ $monetizationEnabled ? 'Premium Mode' : 'Free Mode' }}
                    </h3>
                    <p class="text-sm font-medium mt-1 opacity-80 max-w-md">
                        @if($monetizationEnabled)
                            Pengguna premium mendapatkan akses penuh, sedangkan fitur premium terkunci untuk pengguna gratis.
                        @else
                            Semua fitur bebas digunakan oleh siapapun. Fokus pada pertumbuhan basis pengguna.
                        @endif
                    </p>
                </div>
            </div>
            
            <div class="text-left md:text-right bg-white/5 backdrop-blur-md border border-white/10 rounded-xl p-4 md:w-48">
                <p class="text-[10px] font-bold uppercase tracking-widest opacity-70 mb-1">Total Active Users</p>
                <p class="text-3xl font-black">{{ number_format($totalUsers) }}</p>
                <div class="w-full bg-white/10 rounded-full h-1.5 mt-3 mb-1.5">
                    <div class="{{ $monetizationEnabled ? 'bg-purple-400' : 'bg-emerald-400' }} h-1.5 rounded-full" style="width: {{ $totalUsers > 0 ? ($premiumUsers / $totalUsers * 100) : 0 }}%"></div>
                </div>
                <p class="text-[11px] font-medium opacity-80">
                    {{ number_format($premiumUsers) }} premium ({{ $totalUsers > 0 ? number_format($premiumUsers / $totalUsers * 100, 1) : 0 }}%)
                </p>
            </div>
        </div>
    </div>
    
    {{-- Toggle Buttons (Bento Grid Style) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
        {{-- FREE MODE Button --}}
        <button 
            wire:click="toggleMonetization(false)"
            class="group relative flex flex-col items-start p-6 rounded-2xl border-2 text-left transition-all duration-300 {{ !$monetizationEnabled ? 'border-emerald-500 bg-emerald-50/50 shadow-[0_8px_30px_rgba(16,185,129,0.12)]' : 'border-slate-100 bg-white hover:border-emerald-300 hover:shadow-lg' }}">
            
            <div class="flex items-center gap-4 mb-5 w-full">
                <div class="w-14 h-14 rounded-xl flex items-center justify-center flex-shrink-0 transition-colors {{ !$monetizationEnabled ? 'bg-emerald-500 text-white shadow-md' : 'bg-emerald-50 text-emerald-600 group-hover:bg-emerald-100' }}">
                    <i class="ph-duotone ph-gift text-3xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-extrabold text-xl text-slate-900">Free Mode</h3>
                    <p class="text-[11px] font-bold uppercase tracking-wider {{ !$monetizationEnabled ? 'text-emerald-600' : 'text-slate-400' }}">Growth & Acquisition</p>
                </div>
                @if(!$monetizationEnabled)
                    <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-sm">
                        <i class="ph-bold ph-check text-xs"></i>
                    </div>
                @endif
            </div>
            
            <ul class="space-y-2 mb-6 flex-1">
                <li class="flex items-start gap-2 text-sm text-slate-600 font-medium">
                    <i class="ph-bold ph-check-circle text-emerald-500 text-lg mt-0.5"></i>
                    <span>Semua fitur gratis untuk semua pengguna</span>
                </li>
                <li class="flex items-start gap-2 text-sm text-slate-600 font-medium">
                    <i class="ph-bold ph-check-circle text-emerald-500 text-lg mt-0.5"></i>
                    <span>Tidak perlu langganan / pembayaran</span>
                </li>
                <li class="flex items-start gap-2 text-sm text-slate-600 font-medium">
                    <i class="ph-bold ph-check-circle text-emerald-500 text-lg mt-0.5"></i>
                    <span>Akses tak terbatas untuk alat AI & resume</span>
                </li>
            </ul>
            
            <div class="w-full">
                @if(!$monetizationEnabled)
                    <div class="w-full py-2.5 bg-emerald-100 text-emerald-800 text-xs rounded-xl font-bold text-center border border-emerald-200 uppercase tracking-widest">
                        Mode Aktif Saat Ini
                    </div>
                @else
                    <div class="w-full py-2.5 bg-slate-50 border border-slate-200 text-slate-500 text-xs rounded-xl font-bold text-center group-hover:bg-emerald-50 group-hover:text-emerald-700 group-hover:border-emerald-200 transition-colors uppercase tracking-widest">
                        Klik untuk Mengaktifkan
                    </div>
                @endif
            </div>
        </button>
        
        {{-- PREMIUM MODE Button --}}
        <button 
            wire:click="toggleMonetization(true)"
            class="group relative flex flex-col items-start p-6 rounded-2xl border-2 text-left transition-all duration-300 {{ $monetizationEnabled ? 'border-purple-500 bg-purple-50/50 shadow-[0_8px_30px_rgba(168,85,247,0.12)]' : 'border-slate-100 bg-white hover:border-purple-300 hover:shadow-lg' }}">
            
            <div class="flex items-center gap-4 mb-5 w-full">
                <div class="w-14 h-14 rounded-xl flex items-center justify-center flex-shrink-0 transition-colors {{ $monetizationEnabled ? 'bg-purple-500 text-white shadow-md' : 'bg-purple-50 text-purple-600 group-hover:bg-purple-100' }}">
                    <i class="ph-duotone ph-crown text-3xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-extrabold text-xl text-slate-900">Premium Mode</h3>
                    <p class="text-[11px] font-bold uppercase tracking-wider {{ $monetizationEnabled ? 'text-purple-600' : 'text-slate-400' }}">Revenue & Monetization</p>
                </div>
                @if($monetizationEnabled)
                    <div class="w-6 h-6 rounded-full bg-purple-500 text-white flex items-center justify-center shadow-sm">
                        <i class="ph-bold ph-check text-xs"></i>
                    </div>
                @endif
            </div>
            
            <ul class="space-y-2 mb-6 flex-1">
                <li class="flex items-start gap-2 text-sm text-slate-600 font-medium">
                    <i class="ph-bold ph-check-circle text-purple-500 text-lg mt-0.5"></i>
                    <span>Pengguna gratis memiliki batasan (Smart Limits)</span>
                </li>
                <li class="flex items-start gap-2 text-sm text-slate-600 font-medium">
                    <i class="ph-bold ph-star text-purple-500 text-lg mt-0.5"></i>
                    <span>Pengguna premium mendapat akses penuh eksklusif</span>
                </li>
                <li class="flex items-start gap-2 text-sm text-slate-600 font-medium">
                    <i class="ph-bold ph-check-circle text-purple-500 text-lg mt-0.5"></i>
                    <span>Mulai hasilkan pendapatan (Revenue) nyata</span>
                </li>
            </ul>
            
            <div class="w-full">
                @if($monetizationEnabled)
                    <div class="w-full py-2.5 bg-purple-100 text-purple-800 text-xs rounded-xl font-bold text-center border border-purple-200 uppercase tracking-widest">
                        Mode Aktif Saat Ini
                    </div>
                @else
                    <div class="w-full py-2.5 bg-slate-50 border border-slate-200 text-slate-500 text-xs rounded-xl font-bold text-center group-hover:bg-purple-50 group-hover:text-purple-700 group-hover:border-purple-200 transition-colors uppercase tracking-widest">
                        Klik untuk Mengaktifkan
                    </div>
                @endif
            </div>
        </button>
    </div>
    
    {{-- Feature Access Matrix --}}
    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-6 md:p-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <i class="ph-duotone ph-table text-xl text-primary-500"></i> Feature Access Matrix
                </h3>
                <p class="text-sm font-medium text-slate-500 mt-1">Perbandingan hak akses fitur berdasarkan mode</p>
            </div>
            <div class="inline-flex items-center px-3 py-1.5 rounded-lg border {{ $monetizationEnabled ? 'bg-purple-50 border-purple-200 text-purple-700' : 'bg-emerald-50 border-emerald-200 text-emerald-700' }} text-xs font-bold uppercase tracking-widest">
                <i class="ph-bold {{ $monetizationEnabled ? 'ph-crown' : 'ph-gift' }} mr-1.5"></i>
                {{ $monetizationEnabled ? 'Premium Active' : 'Free Active' }}
            </div>
        </div>
        
        <div class="overflow-x-auto rounded-xl border border-slate-200">
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-5 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest w-[40%]">Nama Fitur</th>
                        <th class="px-5 py-4 text-center border-l border-slate-200 w-[30%]">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center mb-1">
                                    <i class="ph-bold ph-user text-sm"></i>
                                </div>
                                <span class="text-[11px] font-bold text-slate-600 uppercase tracking-widest">Free Users</span>
                            </div>
                        </th>
                        <th class="px-5 py-4 text-center border-l border-slate-200 w-[30%] bg-purple-50/50">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center mb-1 shadow-sm">
                                    <i class="ph-bold ph-crown text-sm"></i>
                                </div>
                                <span class="text-[11px] font-bold text-purple-700 uppercase tracking-widest">Premium Users</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @foreach($featureMatrix as $feature => $access)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-4 font-bold text-sm text-slate-800">{{ $feature }}</td>
                            <td class="px-5 py-4 text-center border-l border-slate-100">
                                @if($access['free_status'] === 'full')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        <i class="ph-bold ph-check text-emerald-500"></i> {{ $access['free'] }}
                                    </span>
                                @elseif($access['free_status'] === 'limited')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-100">
                                        <i class="ph-bold ph-warning-circle text-amber-500"></i> {{ $access['free'] }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-bold bg-red-50 text-red-700 border border-red-100">
                                        <i class="ph-bold ph-lock-key text-red-500"></i> Locked
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center border-l border-slate-100 bg-purple-50/10">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-100 shadow-sm">
                                    <i class="ph-bold ph-star text-purple-500"></i> {{ $access['premium'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    {{-- Confirmation Modal (Premium Design) --}}
    @if($showConfirmation)
        <div class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="cancelToggle"></div>
            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative w-full max-w-lg bg-white rounded-3xl shadow-[0_12px_40px_rgba(0,0,0,0.12)] border border-slate-100 overflow-hidden transform transition-all">
                    
                    {{-- Decorative Top Bar --}}
                    <div class="h-2 w-full {{ $actionType === 'enable' ? 'bg-gradient-to-r from-purple-500 to-purple-600' : 'bg-gradient-to-r from-emerald-500 to-emerald-600' }}"></div>
                    
                    <div class="p-8 text-center">
                        <div class="w-24 h-24 mx-auto rounded-full flex items-center justify-center mb-6 shadow-lg {{ $actionType === 'enable' ? 'bg-purple-100 text-purple-600 shadow-purple-500/20' : 'bg-emerald-100 text-emerald-600 shadow-emerald-500/20' }}">
                            <i class="ph-duotone {{ $actionType === 'enable' ? 'ph-crown' : 'ph-gift' }} text-5xl"></i>
                        </div>
                        
                        <h4 class="font-black text-2xl text-slate-900 mb-2">
                            {{ $actionType === 'enable' ? 'Aktifkan Premium Mode?' : 'Kembali ke Free Mode?' }}
                        </h4>
                        <p class="text-sm font-medium text-slate-500 mb-6">
                            @if($actionType === 'enable')
                                Anda akan <strong class="text-purple-600">MENGAKTIFKAN</strong> monetisasi. Pengguna gratis akan kehilangan akses ke beberapa fitur premium.
                            @else
                                Anda akan <strong class="text-emerald-600">MENONAKTIFKAN</strong> monetisasi. Seluruh fitur akan terbuka 100% secara gratis untuk semua pengguna.
                            @endif
                        </p>
                        
                        <div class="text-left bg-slate-50 rounded-2xl p-5 border border-slate-100 mb-8">
                            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-3">Dampak Perubahan:</p>
                            <ul class="space-y-2">
                                <li class="flex items-start gap-2 text-sm font-bold text-slate-700">
                                    <i class="ph-bold ph-users-three text-slate-400 mt-0.5"></i>
                                    Berlaku instan untuk {{ number_format($totalUsers) }} pengguna
                                </li>
                                @if($actionType === 'enable')
                                    <li class="flex items-start gap-2 text-sm font-bold text-slate-700">
                                        <i class="ph-bold ph-lock-key text-purple-500 mt-0.5"></i>
                                        Fitur AI & kuota lamaran dibatasi untuk akun Free
                                    </li>
                                @else
                                    <li class="flex items-start gap-2 text-sm font-bold text-slate-700">
                                        <i class="ph-bold ph-lock-key-open text-emerald-500 mt-0.5"></i>
                                        Smart limits dan paywalls dihapus sepenuhnya
                                    </li>
                                @endif
                                <li class="flex items-start gap-2 text-sm font-bold text-slate-700">
                                    <i class="ph-bold ph-clock text-slate-400 mt-0.5"></i>
                                    Perubahan ini akan dicatat ke dalam log audit
                                </li>
                            </ul>
                        </div>
                        
                        <div class="flex gap-3">
                            <button 
                                wire:click="cancelToggle" 
                                class="flex-1 px-5 py-3.5 bg-white border-2 border-slate-200 text-slate-700 rounded-xl font-bold hover:bg-slate-50 transition-colors">
                                Batal
                            </button>
                            <button 
                                wire:click="confirmToggle" 
                                class="flex-1 px-5 py-3.5 text-white rounded-xl font-bold shadow-lg transition-all focus:ring-4 focus:outline-none {{ $actionType === 'enable' ? 'bg-purple-600 hover:bg-purple-700 shadow-purple-500/30 focus:ring-purple-300' : 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-500/30 focus:ring-emerald-300' }}">
                                Ya, Konfirmasi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
</div>
