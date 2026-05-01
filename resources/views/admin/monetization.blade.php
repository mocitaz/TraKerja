<x-admin-layout>
    @php
        $monetizationEnabled = \App\Models\Setting::get('monetization_enabled', false);
        $premiumPrice = \App\Models\Setting::get('premium_price', 199000);
        $totalUsers = \App\Models\User::where('role', '!=', 'admin')->count();
        $freeUsers = \App\Models\User::where('role', '!=', 'admin')->where('is_premium', false)->count();
        $premiumUsers = \App\Models\User::where('role', '!=', 'admin')->where('is_premium', true)->count();
        $revenue = \App\Models\Payment::where('status', 'SUCCESS')->sum('amount'); // more accurate than $premiumUsers * $premiumPrice
    @endphp

    <div class="space-y-6">
        
        {{-- Success Message --}}
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex items-center gap-3 text-emerald-800 shadow-sm">
                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="ph-bold ph-check-circle text-emerald-600 text-xl"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold">{{ session('success') }}</h4>
                </div>
            </div>
        @endif
        
        {{-- Header --}}
        <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-50 rounded-xl flex items-center justify-center flex-shrink-0 text-primary-600 shadow-inner">
                        <i class="ph-duotone ph-currency-circle-dollar text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900 truncate">Monetization Control</h3>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Atur strategi pendapatan & harga</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Executive Summary Stats (Bento Grid) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            
            {{-- Total Users --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group hover:border-slate-300 transition-colors">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-blue-50 to-blue-100 rounded-full blur-2xl -z-10 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Users</p>
                        <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($totalUsers) }}</h3>
                        <p class="text-[10px] font-bold text-slate-400 mt-1">Semua pengguna terdaftar</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shadow-inner">
                        <i class="ph-duotone ph-users-three text-2xl"></i>
                    </div>
                </div>
            </div>

            {{-- Total Free --}}
            <div class="bg-white rounded-2xl p-5 border border-emerald-100/50 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group hover:border-emerald-200 transition-colors">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-full blur-2xl -z-10 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-emerald-400 uppercase tracking-widest mb-1">Free Users</p>
                        <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($freeUsers) }}</h3>
                        <p class="text-[10px] font-bold text-emerald-500 mt-1">Pengguna gratisan</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-inner">
                        <i class="ph-duotone ph-gift text-2xl"></i>
                    </div>
                </div>
            </div>

            {{-- Premium Users --}}
            <div class="bg-white rounded-2xl p-5 border border-purple-100/50 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group hover:border-purple-200 transition-colors">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-purple-50 to-purple-100 rounded-full blur-2xl -z-10 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-purple-400 uppercase tracking-widest mb-1">Premium Users</p>
                        <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($premiumUsers) }}</h3>
                        <p class="text-[10px] font-bold text-purple-500 mt-1">{{ $totalUsers > 0 ? number_format($premiumUsers / $totalUsers * 100, 1) : 0 }}% conversion rate</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 shadow-inner">
                        <i class="ph-duotone ph-crown text-2xl"></i>
                    </div>
                </div>
            </div>

            {{-- Total Revenue --}}
            <div class="bg-white rounded-2xl p-5 border border-amber-100/50 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group hover:border-amber-200 transition-colors">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-amber-50 to-amber-100 rounded-full blur-2xl -z-10 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-amber-500 uppercase tracking-widest mb-1">Total Revenue</p>
                        <h3 class="text-xl lg:text-2xl font-black text-amber-600 tracking-tight mt-1">Rp {{ number_format($revenue / 1000000, 1) }}M</h3>
                        <p class="text-[10px] font-bold text-amber-500 mt-1">Dari premium subs</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 shadow-inner">
                        <i class="ph-duotone ph-vault text-2xl"></i>
                    </div>
                </div>
            </div>

        </div>

        {{-- Premium Pricing Configuration --}}
        <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center flex-shrink-0 shadow-inner">
                    <i class="ph-duotone ph-tag text-2xl text-primary-600"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Premium Pricing Configuration</h3>
                    <p class="text-sm font-medium text-slate-500">Atur harga langganan untuk akses fitur premium</p>
                </div>
            </div>

            <form action="{{ route('admin.update-premium-price') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Price Input --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-3">
                            Harga Premium Saat Ini (IDR)
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 font-black">
                                Rp
                            </div>
                            <input 
                                type="number" 
                                name="premium_price"
                                value="{{ $premiumPrice }}"
                                class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xl font-black text-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all shadow-sm group-hover:border-slate-300"
                                min="0"
                                step="1000"
                                required>
                        </div>
                        <div class="mt-3 flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                            <p class="text-xs font-bold text-slate-500">Active Price: <span class="text-primary-600">Rp {{ number_format($premiumPrice, 0, ',', '.') }}</span></p>
                        </div>
                    </div>

                    {{-- Suggested Pricing --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-3">
                            Rekomendasi Harga
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <button type="button" onclick="document.querySelector('input[name=premium_price]').value = 99000" class="flex flex-col items-center justify-center p-3 bg-white border border-slate-200 rounded-xl hover:bg-primary-50 hover:border-primary-200 hover:text-primary-700 transition-all group cursor-pointer shadow-sm">
                                <span class="text-lg font-black text-slate-800 group-hover:text-primary-700">99k</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase mt-1">Budget</span>
                            </button>
                            <button type="button" onclick="document.querySelector('input[name=premium_price]').value = 199000" class="flex flex-col items-center justify-center p-3 bg-white border border-primary-500 rounded-xl hover:bg-primary-50 transition-all group cursor-pointer shadow-md relative overflow-hidden">
                                <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-primary-400 to-primary-600"></div>
                                <span class="text-lg font-black text-primary-700">199k</span>
                                <span class="text-[10px] font-bold text-primary-500 uppercase mt-1">Recommended</span>
                            </button>
                            <button type="button" onclick="document.querySelector('input[name=premium_price]').value = 299000" class="flex flex-col items-center justify-center p-3 bg-white border border-slate-200 rounded-xl hover:bg-purple-50 hover:border-purple-200 hover:text-purple-700 transition-all group cursor-pointer shadow-sm">
                                <span class="text-lg font-black text-slate-800 group-hover:text-purple-700">299k</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase mt-1">Premium</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-slate-100">
                    <button 
                        type="submit"
                        class="w-full sm:w-auto px-8 py-3 bg-slate-900 text-white rounded-xl text-sm font-bold hover:bg-slate-800 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <i class="ph-bold ph-floppy-disk"></i> Simpan Harga
                    </button>
                    <a href="{{ route('admin.index') }}" class="w-full sm:w-auto px-8 py-3 bg-white border border-slate-200 text-slate-700 rounded-xl text-sm font-bold hover:bg-slate-50 transition-colors text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        {{-- Monetization Control (Livewire Component) --}}
        <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0 text-purple-600">
                        <i class="ph-duotone ph-toggle-left text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Monetization Control Panel</h3>
                        <p class="text-[11px] font-medium text-slate-500">Atur akses fitur dan mode monetisasi (Free vs Premium)</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                @livewire('admin.monetization-control')
            </div>
        </div>

    </div>
</x-admin-layout>
