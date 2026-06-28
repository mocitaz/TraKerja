<x-admin-layout>
    @php
        $monetizationEnabled = \App\Models\Setting::get('monetization_enabled', false);
        $premiumPrice = \App\Models\Setting::get('premium_price', 199000);
        $totalUsers = \App\Models\User::where('role', '!=', 'admin')->count();
        $freeUsers = \App\Models\User::where('role', '!=', 'admin')->where('is_premium', false)->count();
        $premiumUsers = \App\Models\User::where('role', '!=', 'admin')->where('is_premium', true)->count();
        $revenue = \App\Models\Payment::where('status', 'SUCCESS')->sum('amount');
    @endphp

    <div class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-5 space-y-5 pb-10">
        
        {{-- Success Message --}}
        @if(session('success'))
            <div class="p-3.5 bg-emerald-50 border border-emerald-250 text-emerald-800 rounded-md flex items-center gap-2.5">
                <i class="ph-bold ph-check-circle text-base text-emerald-650 shrink-0"></i>
                <p class="text-xs font-semibold">{{ session('success') }}</p>
            </div>
        @endif
        
        <!-- Sticky Global Sub-Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-zinc-200/80">
            <div class="flex items-center gap-2.5 min-w-0">
                <span class="text-xs font-mono font-medium text-zinc-400">Admin</span>
                <span class="text-zinc-300">/</span>
                <h1 class="text-sm font-semibold tracking-tight text-zinc-900">Monetization Control</h1>
            </div>
        </div>

        {{-- Executive Summary Stats (Bento Grid) --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Total Users --}}
            <div class="bg-white rounded-lg p-4 border border-zinc-200/80 relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Total Users</p>
                        <h3 class="text-lg font-semibold tracking-tight text-zinc-900">{{ number_format($totalUsers) }}</h3>
                        <p class="text-[10px] text-zinc-400 mt-1">Semua user terdaftar</p>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-650 shrink-0">
                        <i class="ph-bold ph-users text-base"></i>
                    </div>
                </div>
            </div>

            {{-- Total Free --}}
            <div class="bg-white rounded-lg p-4 border border-zinc-200/80 relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Free Users</p>
                        <h3 class="text-lg font-semibold tracking-tight text-zinc-900">{{ number_format($freeUsers) }}</h3>
                        <p class="text-[10px] text-zinc-400 mt-1">Pengguna gratisan</p>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-650 shrink-0">
                        <i class="ph-bold ph-gift text-base"></i>
                    </div>
                </div>
            </div>

            {{-- Premium Users --}}
            <div class="bg-white rounded-lg p-4 border border-zinc-200/80 relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Premium Users</p>
                        <h3 class="text-lg font-semibold tracking-tight text-purple-600">{{ number_format($premiumUsers) }}</h3>
                        <p class="text-[10px] text-zinc-400 mt-1">{{ $totalUsers > 0 ? number_format($premiumUsers / $totalUsers * 100, 1) : 0 }}% conversion rate</p>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-purple-600 shrink-0">
                        <i class="ph-bold ph-crown text-base"></i>
                    </div>
                </div>
            </div>

            {{-- Total Revenue --}}
            <div class="bg-white rounded-lg p-4 border border-zinc-200/80 relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Total Pendapatan</p>
                        <h3 class="text-lg font-bold text-zinc-900 tracking-tight mt-0.5">
                            @if($revenue >= 1000000)
                                Rp {{ number_format($revenue / 1000000, 1) }} Jt
                            @else
                                Rp {{ number_format($revenue, 0, ',', '.') }}
                            @endif
                        </h3>
                        <p class="text-[10px] text-zinc-400 mt-1">Pembayaran sukses</p>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-650 shrink-0">
                        <i class="ph-bold ph-vault text-base"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Premium Pricing Configuration --}}
        <div class="bg-white rounded-lg border border-zinc-200/80 p-4">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 bg-zinc-50 rounded border border-zinc-200 flex items-center justify-center shrink-0 text-zinc-650">
                    <i class="ph-bold ph-tag text-base"></i>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-zinc-900 tracking-tight">Premium Pricing Configuration</h3>
                    <p class="text-[10px] text-zinc-400 mt-0.5">Atur harga langganan untuk akses fitur premium</p>
                </div>
            </div>

            <form action="{{ route('admin.update-premium-price') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div class="max-w-xs">
                    {{-- Price Input --}}
                    <div>
                        <label class="block text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1.5">
                            Harga Premium Saat Ini (IDR)
                        </label>
                        <div class="relative flex items-center">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-400 font-bold text-xs">
                                Rp
                            </div>
                            <input 
                                type="number" 
                                name="premium_price"
                                value="{{ $premiumPrice }}"
                                class="w-full pl-8 pr-3 py-1.5 bg-zinc-50 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-900 focus:bg-white focus:ring-1 focus:ring-primary-600 focus:border-primary-600 transition-all"
                                min="0"
                                step="1000"
                                required>
                        </div>
                        <div class="mt-2.5 flex items-center gap-1.5">
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                            <p class="text-[11px] font-medium text-zinc-500">Harga aktif: <span class="text-primary-650 font-semibold font-mono">Rp {{ number_format($premiumPrice, 0, ',', '.') }}</span></p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-2 pt-4 border-t border-zinc-150">
                    <button type="submit"
                            class="px-4 py-1.5 bg-zinc-900 hover:bg-zinc-800 text-white rounded-md text-xs font-semibold transition-colors flex items-center justify-center gap-1.5">
                        <i class="ph-bold ph-floppy-disk text-xs"></i> Simpan Harga
                    </button>
                    <a href="{{ route('admin.index') }}" 
                       class="px-4 py-1.5 bg-white border border-zinc-250 text-zinc-700 hover:bg-zinc-50 rounded-md text-xs font-semibold transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        {{-- Monetization Control (Livewire Component) --}}
        <div class="bg-white rounded-lg border border-zinc-200/80 overflow-hidden">
            <div class="px-4 py-3 border-b border-zinc-150 bg-zinc-50/50">
                <div class="flex items-center gap-2">
                    <i class="ph-bold ph-toggle-left text-zinc-400 text-base"></i>
                    <div>
                        <h3 class="text-xs font-bold text-zinc-900 tracking-tight">Monetization Control Panel</h3>
                        <p class="text-[9px] text-zinc-400 mt-0.5">Atur akses fitur dan mode monetisasi (Free vs Premium)</p>
                    </div>
                </div>
            </div>
            <div class="p-4 bg-white">
                @livewire('admin.monetization-control')
            </div>
        </div>

    </div>
</x-admin-layout>
