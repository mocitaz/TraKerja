<x-admin-layout>
    @php
        $monetizationEnabled = \App\Models\Setting::get('monetization_enabled', false);
        $premiumPrice = \App\Models\Setting::get('premium_price', 199000);
        $totalUsers = \App\Models\User::where('role', '!=', 'admin')->count();
        $freeUsers = \App\Models\User::where('role', '!=', 'admin')->where('is_premium', false)->count();
        $premiumUsers = \App\Models\User::where('role', '!=', 'admin')->where('is_premium', true)->count();
        $revenue = \App\Models\Payment::where('status', 'SUCCESS')->sum('amount'); // more accurate than $premiumUsers * $premiumPrice
    @endphp

    <div class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-6 space-y-6 sm:space-y-8 pb-10">
        
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
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 sm:mb-8">
            <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white border border-slate-200/60 rounded-[1.25rem] sm:rounded-[1.5rem] flex items-center justify-center text-primary-600 shadow-sm shrink-0">
                    <i class="ph-duotone ph-currency-circle-dollar text-xl sm:text-2xl"></i>
                </div>
                <div class="flex flex-col min-w-0">
                    <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight truncate">Monetization Control</h3>
                    <p class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mt-1 sm:mt-1.5 truncate">Atur strategi pendapatan & harga</p>
                </div>
            </div>
        </div>

        {{-- Executive Summary Stats (Bento Grid) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            
            {{-- Total Users --}}
            <div class="bg-white rounded-[2rem] p-6 border border-slate-200/60 bento-card-stat relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-blue-50 to-blue-100 rounded-full blur-2xl -z-10"></div>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Users</p>
                        <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($totalUsers) }}</h3>
                        <p class="text-[9px] font-bold text-slate-400 mt-1">Semua pengguna terdaftar</p>
                    </div>
                    <div class="w-10 h-10 rounded-[1.25rem] bg-blue-50 flex items-center justify-center text-blue-600 shadow-sm border border-blue-100">
                        <i class="ph-duotone ph-users-three text-xl"></i>
                    </div>
                </div>
            </div>

            {{-- Total Free --}}
            <div class="bg-white rounded-[2rem] p-6 border border-slate-200/60 bento-card-stat relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-full blur-2xl -z-10"></div>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest mb-1">Free Users</p>
                        <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($freeUsers) }}</h3>
                        <p class="text-[9px] font-bold text-emerald-500 mt-1">Pengguna gratisan</p>
                    </div>
                    <div class="w-10 h-10 rounded-[1.25rem] bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-sm border border-emerald-100">
                        <i class="ph-duotone ph-gift text-xl"></i>
                    </div>
                </div>
            </div>

            {{-- Premium Users --}}
            <div class="bg-white rounded-[2rem] p-6 border border-slate-200/60 bento-card-stat relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-purple-50 to-purple-100 rounded-full blur-2xl -z-10"></div>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-purple-400 uppercase tracking-widest mb-1">Premium Users</p>
                        <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($premiumUsers) }}</h3>
                        <p class="text-[9px] font-bold text-purple-500 mt-1">{{ $totalUsers > 0 ? number_format($premiumUsers / $totalUsers * 100, 1) : 0 }}% conversion rate</p>
                    </div>
                    <div class="w-10 h-10 rounded-[1.25rem] bg-purple-50 flex items-center justify-center text-purple-600 shadow-sm border border-purple-100">
                        <i class="ph-duotone ph-crown text-xl"></i>
                    </div>
                </div>
            </div>

            {{-- Total Revenue --}}
            <div class="bg-white rounded-[2rem] p-6 border border-slate-200/60 bento-card-stat relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-amber-50 to-amber-100 rounded-full blur-2xl -z-10"></div>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-amber-400 uppercase tracking-widest mb-1">Total Pendapatan</p>
                        <h3 class="text-xl lg:text-2xl font-black text-amber-600 tracking-tight mt-1">
                            @if($revenue >= 1000000)
                                Rp {{ number_format($revenue / 1000000, 1) }} Jt
                            @else
                                Rp {{ number_format($revenue, 0, ',', '.') }}
                            @endif
                        </h3>
                        <p class="text-[9px] font-bold text-amber-500 mt-1">Total pembayaran sukses</p>
                    </div>
                    <div class="w-10 h-10 rounded-[1.25rem] bg-amber-50 flex items-center justify-center text-amber-600 shadow-sm border border-amber-100">
                        <i class="ph-duotone ph-vault text-xl"></i>
                    </div>
                </div>
            </div>

        </div>

        {{-- Premium Pricing Configuration --}}
        <div class="bg-white rounded-[2rem] border border-slate-200/60 p-6 sm:p-8 bento-card">
            <div class="flex items-center gap-3 sm:gap-4 mb-6 sm:mb-8">
                <div class="w-12 h-12 bg-primary-50 rounded-[1.25rem] flex items-center justify-center flex-shrink-0 border border-primary-100 shadow-sm text-primary-600">
                    <i class="ph-duotone ph-tag text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">Premium Pricing Configuration</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Atur harga langganan untuk akses fitur premium</p>
                </div>
            </div>

            <form action="{{ route('admin.update-premium-price') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="max-w-md">
                    {{-- Price Input --}}
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">
                            Harga Premium Saat Ini (IDR)
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 font-black">
                                Rp
                            </div>
                            <input 
                                type="number" 
                                name="premium_price"
                                value="{{ $premiumPrice }}"
                                class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200/60 rounded-xl text-xl font-black text-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all shadow-sm"
                                min="0"
                                step="1000"
                                required>
                        </div>
                        <div class="mt-3 flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                            <p class="text-xs font-bold text-slate-500">Active Price: <span class="text-primary-600">Rp {{ number_format($premiumPrice, 0, ',', '.') }}</span></p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-slate-100">
                    <button 
                        type="submit"
                        class="w-full sm:w-auto px-8 py-3 bg-slate-900 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition-all shadow-sm active:scale-95 flex items-center justify-center gap-2">
                        <i class="ph-bold ph-floppy-disk"></i> Simpan Harga
                    </button>
                    <a href="{{ route('admin.index') }}" class="w-full sm:w-auto px-8 py-3 bg-white border border-slate-200/60 text-slate-700 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-50 transition-colors text-center flex items-center justify-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        {{-- Monetization Control (Livewire Component) --}}
        <div class="bg-white rounded-[2rem] border border-slate-200/60 overflow-hidden bento-card">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-50 rounded-[1.25rem] border border-purple-100 flex items-center justify-center flex-shrink-0 text-purple-600 shadow-sm">
                        <i class="ph-duotone ph-toggle-left text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900 tracking-tight">Monetization Control Panel</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Atur akses fitur dan mode monetisasi (Free vs Premium)</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                @livewire('admin.monetization-control')
            </div>
        </div>

    </div>
</x-admin-layout>
