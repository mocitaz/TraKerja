<x-admin-layout>
    @php
        $monetizationEnabled = \App\Models\Setting::get('monetization_enabled', false);
        $premiumPrice = \App\Models\Setting::get('premium_price', 199000);
        $totalUsers = \App\Models\User::where('role', '!=', 'admin')->count();
        $freeUsers = \App\Models\User::where('role', '!=', 'admin')->where('is_premium', false)->count();
        $premiumUsers = \App\Models\User::where('role', '!=', 'admin')->where('is_premium', true)->count();
        $revenue = \App\Models\Payment::where('status', 'SUCCESS')->sum('amount');
    @endphp

    <div class="space-y-6 pb-12 w-full text-left">
        
        {{-- Success Message --}}
        @if(session('success'))
            <div class="p-3.5 bg-emerald-50 border border-emerald-250 text-emerald-800 rounded-xl flex items-center gap-2.5 shadow-3xs">
                <i class="ph-bold ph-check-circle text-base text-emerald-650 shrink-0"></i>
                <p class="text-xs font-bold">{{ session('success') }}</p>
            </div>
        @endif
        
        <!-- Header Section (Sleek and Clean) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-zinc-200">
            <div>
                <h1 class="text-lg font-black text-zinc-900 tracking-tight flex items-center gap-1.5">
                    Monetization Control <span class="text-[9px] font-mono font-bold text-zinc-450 bg-zinc-100 border border-zinc-200 px-1.5 py-0.2 rounded">Revenue Hub</span>
                </h1>
                <p class="text-[10px] text-zinc-500 font-semibold mt-0.5">Configure premium features, pricing parameters, subscription toggles, and user tiers.</p>
            </div>
        </div>

        {{-- Executive Summary Stats (Bento Grid) --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Total Users --}}
            <div class="border border-zinc-200 rounded-xl bg-white p-5 hover:border-zinc-300 transition-all flex flex-col justify-between group shadow-3xs text-left">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[8px] font-mono font-bold text-zinc-450 uppercase tracking-widest block mb-1">Total Users</span>
                        <h3 class="text-xl font-extrabold tracking-tight text-zinc-900 font-mono leading-none mt-1">{{ number_format($totalUsers) }}</h3>
                    </div>
                    <div class="w-7.5 h-7.5 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-950 group-hover:text-white transition duration-200 shrink-0 shadow-3xs">
                        <i class="ph-bold ph-users text-xs"></i>
                    </div>
                </div>
                <p class="text-[9px] font-mono font-bold text-zinc-400 mt-5 uppercase tracking-widest">All members registered</p>
            </div>

            {{-- Total Free --}}
            <div class="border border-zinc-200 rounded-xl bg-white p-5 hover:border-zinc-300 transition-all flex flex-col justify-between group shadow-3xs text-left">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[8px] font-mono font-bold text-zinc-455 uppercase tracking-widest block mb-1">Free Users</span>
                        <h3 class="text-xl font-extrabold tracking-tight text-zinc-900 font-mono leading-none mt-1">{{ number_format($freeUsers) }}</h3>
                    </div>
                    <div class="w-7.5 h-7.5 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-950 group-hover:text-white transition duration-200 shrink-0 shadow-3xs">
                        <i class="ph-bold ph-gift text-xs"></i>
                    </div>
                </div>
                <p class="text-[9px] font-mono font-bold text-zinc-400 mt-5 uppercase tracking-widest">Standard basic tier</p>
            </div>

            {{-- Premium Users --}}
            <div class="border border-zinc-200 rounded-xl bg-white p-5 hover:border-zinc-300 transition-all flex flex-col justify-between group shadow-3xs text-left">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[8px] font-mono font-bold text-zinc-455 uppercase tracking-widest block mb-1">Premium Users</span>
                        <h3 class="text-xl font-extrabold tracking-tight text-zinc-800 font-mono leading-none mt-1">{{ number_format($premiumUsers) }}</h3>
                    </div>
                    <div class="w-7.5 h-7.5 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-950 group-hover:text-white transition duration-200 shrink-0 shadow-3xs">
                        <i class="ph-bold ph-crown text-xs"></i>
                    </div>
                </div>
                <p class="text-[9px] font-mono font-bold text-zinc-400 mt-5 uppercase tracking-widest">Active paid accounts</p>
            </div>

            {{-- Total Revenue --}}
            <div class="border border-zinc-200 rounded-xl bg-white p-5 hover:border-zinc-300 transition-all flex flex-col justify-between group shadow-3xs text-left">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[8px] font-mono font-bold text-zinc-455 uppercase tracking-widest block mb-1">Total Pendapatan</span>
                        <h3 class="text-xl font-extrabold tracking-tight text-zinc-900 font-mono leading-none mt-1">
                            @if($revenue >= 1000000)
                                Rp {{ number_format($revenue / 1000000, 1) }} Jt
                            @else
                                Rp {{ number_format($revenue, 0, ',', '.') }}
                            @endif
                        </h3>
                    </div>
                    <div class="w-7.5 h-7.5 rounded-lg bg-emerald-50 border border-emerald-250 text-emerald-650 flex items-center justify-center shrink-0 shadow-3xs">
                        <i class="ph-bold ph-vault text-xs"></i>
                    </div>
                </div>
                <p class="text-[9px] font-mono font-bold text-zinc-400 mt-5 uppercase tracking-widest">Gross revenue stats</p>
            </div>
        </div>

        {{-- Premium Pricing Configuration --}}
        <div class="bg-[#f7f7f5]/40 rounded-xl border border-zinc-200 p-5 shadow-3xs">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 bg-zinc-50 border border-zinc-200 rounded flex items-center justify-center shrink-0 text-zinc-500 shadow-3xs">
                    <i class="ph-bold ph-tag text-base"></i>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-zinc-900 leading-none">Premium Pricing Configuration</h3>
                    <p class="text-[8px] font-mono font-bold text-zinc-400 mt-1 uppercase tracking-wider">Atur harga langganan untuk akses fitur premium</p>
                </div>
            </div>

            <form action="{{ route('admin.update-premium-price') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div class="max-w-xs">
                    {{-- Price Input --}}
                    <div>
                        <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest mb-1.5">
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
                                class="w-full pl-8 pr-3 h-9 bg-white border border-zinc-200 rounded-lg text-xs font-semibold text-zinc-800 focus:outline-none focus:border-zinc-400 transition-colors shadow-3xs"
                                min="0"
                                step="1000"
                                required>
                        </div>
                        <div class="mt-2.5 flex items-center gap-1.5">
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                            <p class="text-[11px] font-semibold text-zinc-500 font-sans">Harga aktif: <span class="text-zinc-900 font-extrabold font-mono">Rp {{ number_format($premiumPrice, 0, ',', '.') }}</span></p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-2 pt-3 border-t border-zinc-200 mt-4">
                    <button type="submit"
                            class="h-9 px-4 bg-zinc-900 hover:bg-zinc-800 text-white rounded-lg text-xs font-bold transition-colors flex items-center justify-center gap-1.5 focus:outline-none shadow-3xs">
                        <i class="ph-bold ph-floppy-disk text-xs"></i> Simpan Harga
                    </button>
                    <a href="{{ route('admin.index') }}" 
                       class="h-9 px-4 bg-white border border-zinc-200 text-zinc-700 hover:bg-zinc-50 rounded-lg text-xs font-bold transition-colors flex items-center justify-center focus:outline-none shadow-3xs">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        {{-- Monetization Control (Livewire Component) --}}
        <div class="bg-white rounded-xl border border-zinc-200 overflow-hidden shadow-3xs">
            <div class="px-5 py-4 border-b border-zinc-100 bg-zinc-50/20">
                <div class="flex items-center gap-2">
                    <i class="ph-bold ph-toggle-left text-zinc-455 text-base"></i>
                    <div>
                        <h3 class="text-xs font-bold text-zinc-900 leading-none">Monetization Control Panel</h3>
                        <p class="text-[8px] font-mono font-bold text-zinc-400 mt-1 uppercase tracking-wider">Atur akses fitur dan mode monetisasi (Free vs Premium)</p>
                    </div>
                </div>
            </div>
            <div class="p-5 bg-white">
                @livewire('admin.monetization-control')
            </div>
        </div>

    </div>
</x-admin-layout>
