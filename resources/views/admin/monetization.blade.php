<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 rounded-lg bg-white/20 backdrop-blur-sm border border-white/30 flex items-center justify-center">
                    <img src="{{ asset('images/icon.png') }}" 
                         alt="TraKerja Logo" 
                         class="w-6 h-6"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold bg-gradient-to-r from-[#d983e4] to-[#4e71c5] bg-clip-text text-transparent">
                        Premium Pricing & Plans
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">Configure premium subscription pricing and benefits</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
        
        {{-- Success Message --}}
        @if(session('success'))
            <div class="p-4 bg-emerald-100 border-2 border-emerald-500 rounded-xl flex items-center gap-3 text-emerald-800">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Current Status Banner --}}
        @php
            $monetizationEnabled = \App\Models\Setting::get('monetization_enabled', false);
            $premiumPrice = \App\Models\Setting::get('premium_price', 199000);
            $totalUsers = \App\Models\User::where('role', '!=', 'admin')->count();
            $freeUsers = \App\Models\User::where('role', '!=', 'admin')->where('is_premium', false)->count();
            $premiumUsers = \App\Models\User::where('role', '!=', 'admin')->where('is_premium', true)->count();
            $revenue = $premiumUsers * $premiumPrice;
        @endphp

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 {{ $monetizationEnabled ? 'bg-purple-100' : 'bg-emerald-100' }} rounded-lg flex items-center justify-center">
                            <span class="text-lg">{{ $monetizationEnabled ? '💎' : '🎁' }}</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Monetization Status</h3>
                            <p class="text-sm text-gray-500">
                                @if($monetizationEnabled)
                                    Premium features are monetized • Current price: Rp {{ number_format($premiumPrice, 0, ',', '.') }}
                                @else
                                    All features are free for everyone • No payment required
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 text-sm font-medium {{ $monetizationEnabled ? 'bg-purple-100 text-purple-800' : 'bg-emerald-100 text-emerald-800' }} rounded-full">
                            @if($monetizationEnabled)
                                PREMIUM MODE ACTIVE
                            @else
                                FREE MODE ACTIVE
                            @endif
                        </span>
                        <a href="{{ route('admin.payments') }}" class="px-4 py-2 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white rounded-lg transition-colors text-sm font-medium flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            Payment Monitoring
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Revenue Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-600 mb-1">Total Users</p>
                        <p class="text-2xl font-bold text-[#212529]">{{ number_format($totalUsers) }}</p>
                        <p class="text-xs text-gray-500 mt-1">All registered</p>
                    </div>
                    <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-600 mb-1">Free Users</p>
                        <p class="text-2xl font-bold text-[#212529]">{{ number_format($freeUsers) }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $totalUsers > 0 ? number_format($freeUsers / $totalUsers * 100, 1) : 0 }}% of total</p>
                    </div>
                    <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-600 mb-1">Premium Users</p>
                        <p class="text-2xl font-bold text-[#212529]">{{ number_format($premiumUsers) }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $totalUsers > 0 ? number_format($premiumUsers / $totalUsers * 100, 1) : 0 }}% conversion</p>
                    </div>
                    <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl p-8 border-2 border-amber-200 shadow-md">
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-3xl">�</span>
                    <p class="text-sm font-semibold text-amber-700">Total Revenue</p>
                </div>
                <p class="text-3xl font-black text-amber-900">Rp {{ number_format($revenue / 1000000, 1) }}M</p>
                <p class="text-xs text-amber-600 mt-1">From premium subs</p>
            </div>
        </div>

        {{-- Premium Pricing Configuration --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Premium Pricing</h3>
                    <p class="text-sm text-gray-500">Set the subscription price for premium features</p>
                </div>
            </div>

            <form action="{{ route('admin.update-premium-price') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Premium Price (IDR)
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">Rp</span>
                            <input 
                                type="number" 
                                name="premium_price"
                                value="{{ $premiumPrice }}"
                                class="w-full pl-12 pr-4 py-3 border-2 border-purple-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-lg font-bold"
                                min="0"
                                step="1000"
                                required>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Current: <strong class="text-purple-700">Rp {{ number_format($premiumPrice, 0, ',', '.') }}</strong></p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Suggested Pricing
                        </label>
                        <div class="space-y-2">
                            <button type="button" onclick="document.querySelector('input[name=premium_price]').value = 99000" class="w-full text-left px-4 py-2 bg-gray-50 hover:bg-purple-50 border border-gray-200 rounded-lg transition-colors">
                                <span class="font-semibold">Rp 99.000</span> <span class="text-xs text-gray-500">- Budget friendly</span>
                            </button>
                            <button type="button" onclick="document.querySelector('input[name=premium_price]').value = 199000" class="w-full text-left px-4 py-2 bg-gray-50 hover:bg-purple-50 border border-gray-200 rounded-lg transition-colors">
                                <span class="font-semibold">Rp 199.000</span> <span class="text-xs text-gray-500">- Recommended</span>
                            </button>
                            <button type="button" onclick="document.querySelector('input[name=premium_price]').value = 299000" class="w-full text-left px-4 py-2 bg-gray-50 hover:bg-purple-50 border border-gray-200 rounded-lg transition-colors">
                                <span class="font-semibold">Rp 299.000</span> <span class="text-xs text-gray-500">- Premium tier</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button 
                        type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-xl font-bold hover:from-purple-700 hover:to-purple-800 transition-all transform hover:scale-105 shadow-lg">
                        💾 Update Premium Price
                    </button>
                    <a href="{{ route('admin.index') }}" class="px-8 py-3 bg-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-300 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        {{-- Monetization Control --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">Monetization Control</h3>
                        <p class="text-sm text-gray-500">Manage pricing phases and premium features</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                @livewire('admin.monetization-control')
            </div>
        </div>

    </div>
</x-admin-layout>
