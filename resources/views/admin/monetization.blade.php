<x-admin-layout>
    @php
        $monetizationEnabled = \App\Models\Setting::get('monetization_enabled', false);
        $premiumPrice = \App\Models\Setting::get('premium_price', 199000);
        $totalUsers = \App\Models\User::where('role', '!=', 'admin')->count();
        $freeUsers = \App\Models\User::where('role', '!=', 'admin')->where('is_premium', false)->count();
        $premiumUsers = \App\Models\User::where('role', '!=', 'admin')->where('is_premium', true)->count();
        $revenue = \App\Models\Payment::where('status', 'SUCCESS')->sum('amount');
    @endphp

    <div class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-5 space-y-4 pb-10 text-left">
        
        {{-- Success Message --}}
        @if(session('success'))
            <div class="p-3 bg-emerald-50 border border-emerald-250 text-emerald-800 rounded flex items-center gap-2.5 shadow-none">
                <i class="ph ph-check-circle text-base text-emerald-650 shrink-0"></i>
                <p class="text-xs font-semibold">{{ session('success') }}</p>
            </div>
        @endif
        
        <!-- Sticky Global Sub-Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3.5 border-b border-zinc-150/60">
            <div class="flex items-center gap-1.5 min-w-0">
                <span class="text-xs font-mono font-bold text-zinc-400 uppercase tracking-wider">Admin Portal</span>
                <span class="text-zinc-300 text-xs">/</span>
                <h1 class="text-xs font-mono font-bold text-zinc-800 uppercase tracking-wider">Monetization Control</h1>
            </div>
        </div>

        {{-- Executive Summary Stats (Bento Grid) --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Total Users --}}
            <div class="border border-zinc-200/60 rounded bg-white p-4 shadow-none flex flex-col justify-between h-[82px] hover:bg-[#f7f7f5]/40 transition-colors">
                <div class="flex items-center justify-between w-full">
                    <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Total Users</span>
                    <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200/40 text-zinc-500 flex items-center justify-center shrink-0">
                        <i class="ph ph-users text-xs"></i>
                    </div>
                </div>
                <div class="flex items-baseline justify-between mt-1">
                    <p class="text-xl font-bold tracking-tight text-zinc-900 leading-none">{{ number_format($totalUsers) }}</p>
                    <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wide leading-none">Registered</p>
                </div>
            </div>

            {{-- Total Free --}}
            <div class="border border-zinc-200/60 rounded bg-white p-4 shadow-none flex flex-col justify-between h-[82px] hover:bg-[#f7f7f5]/40 transition-colors">
                <div class="flex items-center justify-between w-full">
                    <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Free Users</span>
                    <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200/40 text-zinc-500 flex items-center justify-center shrink-0">
                        <i class="ph ph-gift text-xs"></i>
                    </div>
                </div>
                <div class="flex items-baseline justify-between mt-1">
                    <p class="text-xl font-bold tracking-tight text-zinc-900 leading-none">{{ number_format($freeUsers) }}</p>
                    <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wide leading-none">Free</p>
                </div>
            </div>

            {{-- Premium Users --}}
            <div class="border border-zinc-200/60 rounded bg-white p-4 shadow-none flex flex-col justify-between h-[82px] hover:bg-[#f7f7f5]/40 transition-colors">
                <div class="flex items-center justify-between w-full">
                    <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Premium Users</span>
                    <div class="w-6 h-6 rounded bg-purple-50 border border-purple-100/45 text-purple-650 flex items-center justify-center shrink-0">
                        <i class="ph ph-crown text-xs"></i>
                    </div>
                </div>
                <div class="flex items-baseline justify-between mt-1">
                    <p class="text-xl font-bold tracking-tight text-purple-600 leading-none">{{ number_format($premiumUsers) }}</p>
                    <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wide leading-none">Premium</p>
                </div>
            </div>

            {{-- Total Revenue --}}
            <div class="border border-zinc-200/60 rounded bg-white p-4 shadow-none flex flex-col justify-between h-[82px] hover:bg-[#f7f7f5]/40 transition-colors">
                <div class="flex items-center justify-between w-full">
                    <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Total Pendapatan</span>
                    <div class="w-6 h-6 rounded bg-emerald-50 border border-emerald-100/45 text-emerald-650 flex items-center justify-center shrink-0">
                        <i class="ph ph-vault text-xs"></i>
                    </div>
                </div>
                <div class="flex items-baseline justify-between mt-1">
                    <p class="text-xl font-bold tracking-tight text-zinc-900 leading-none">
                        @if($revenue >= 1000000)
                            Rp {{ number_format($revenue / 1000000, 1) }} Jt
                        @else
                            Rp {{ number_format($revenue, 0, ',', '.') }}
                        @endif
                    </p>
                    <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wide leading-none">Revenue</p>
                </div>
            </div>
        </div>

        {{-- Premium Pricing Configuration --}}
        <div class="bg-white rounded border border-zinc-200/60 p-4 shadow-none">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 bg-zinc-50 border border-zinc-200/40 rounded flex items-center justify-center shrink-0 text-zinc-500">
                    <i class="ph ph-tag text-base"></i>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-zinc-900 leading-none">Premium Pricing Configuration</h3>
                    <p class="text-[8px] font-mono font-bold text-zinc-400 mt-1 uppercase tracking-wide">Atur harga langganan untuk akses fitur premium</p>
                </div>
            </div>

            <form action="{{ route('admin.update-premium-price') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div class="max-w-xs">
                    {{-- Price Input --}}
                    <div>
                        <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide mb-1.5">
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
                                class="w-full pl-8 pr-3 h-8 bg-white border border-zinc-250 rounded text-xs font-semibold text-zinc-900 focus:outline-none focus:border-zinc-400 transition-colors"
                                min="0"
                                step="1000"
                                required>
                        </div>
                        <div class="mt-2.5 flex items-center gap-1.5">
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                            <p class="text-[11px] font-semibold text-zinc-500 font-sans">Harga aktif: <span class="text-purple-650 font-bold font-mono">Rp {{ number_format($premiumPrice, 0, ',', '.') }}</span></p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-2 pt-3 border-t border-zinc-150/60 mt-4">
                    <button type="submit"
                            class="h-8 px-4 bg-zinc-900 hover:bg-zinc-800 text-white rounded text-xs font-semibold transition-colors flex items-center justify-center gap-1.5 focus:outline-none shadow-none">
                        <i class="ph ph-floppy-disk text-xs"></i> Simpan Harga
                    </button>
                    <a href="{{ route('admin.index') }}" 
                       class="h-8 px-4 bg-white border border-zinc-250 text-zinc-700 hover:bg-zinc-50 rounded text-xs font-semibold transition-colors flex items-center justify-center focus:outline-none shadow-none">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        {{-- Monetization Control (Livewire Component) --}}
        <div class="bg-white rounded border border-zinc-200/60 overflow-hidden shadow-none">
            <div class="px-4 py-3 border-b border-zinc-150/60 bg-zinc-50/20">
                <div class="flex items-center gap-2">
                    <i class="ph ph-toggle-left text-zinc-400 text-base"></i>
                    <div>
                        <h3 class="text-xs font-bold text-zinc-900 leading-none">Monetization Control Panel</h3>
                        <p class="text-[8px] font-mono font-bold text-zinc-400 mt-1 uppercase tracking-wide">Atur akses fitur dan mode monetisasi (Free vs Premium)</p>
                    </div>
                </div>
            </div>
            <div class="p-4 bg-white">
                @livewire('admin.monetization-control')
            </div>
        </div>

    </div>
</x-admin-layout>
