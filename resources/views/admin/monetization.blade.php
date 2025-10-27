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
                        <a href="{{ route('admin.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors text-sm font-medium">
                            Go to Dashboard
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

        {{-- Premium Benefits Overview --}}
        <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Premium Benefits</h3>
                    <p class="text-sm text-gray-500">What premium users get for Rp {{ number_format($premiumPrice, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 bg-purple-50 border-2 border-purple-200 rounded-xl">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">📄</span>
                        <div>
                            <h4 class="font-bold text-gray-800 mb-1">All 5 CV Templates</h4>
                            <p class="text-sm text-gray-600">Access to all professional CV templates (Minimal, Professional, Creative, Elegant)</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-purple-50 border-2 border-purple-200 rounded-xl">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">📤</span>
                        <div>
                            <h4 class="font-bold text-gray-800 mb-1">Unlimited CV Exports</h4>
                            <p class="text-sm text-gray-600">Export your CV as many times as needed, no monthly limits</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-purple-50 border-2 border-purple-200 rounded-xl">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">💼</span>
                        <div>
                            <h4 class="font-bold text-gray-800 mb-1">Unlimited Job Applications</h4>
                            <p class="text-sm text-gray-600">Track unlimited job applications in your dashboard</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-purple-50 border-2 border-purple-200 rounded-xl">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">📊</span>
                        <div>
                            <h4 class="font-bold text-gray-800 mb-1">Advanced Analytics</h4>
                            <p class="text-sm text-gray-600">Get detailed insights and statistics about your job search progress</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Free vs Premium Comparison --}}
        <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
            <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Plan Comparison</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Free Plan --}}
                <div class="border-2 border-gray-300 rounded-2xl p-6">
                    <div class="text-center mb-6">
                        <span class="text-4xl">🆓</span>
                        <h4 class="text-2xl font-black text-gray-800 mt-2">Free Plan</h4>
                        <p class="text-3xl font-black text-gray-400 mt-2">Rp 0</p>
                        <p class="text-sm text-gray-500">Forever free</p>
                    </div>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-2">
                            <span class="text-emerald-500">✓</span>
                            <span class="text-sm">Job Application Tracker</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-emerald-500">✓</span>
                            <span class="text-sm">CV Builder Access</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-yellow-500">⚠️</span>
                            <span class="text-sm"><strong>1 CV Template</strong> (Minimal only)</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-yellow-500">⚠️</span>
                            <span class="text-sm"><strong>5 Exports/Month</strong></span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-yellow-500">⚠️</span>
                            <span class="text-sm"><strong>Max 20</strong> Job Applications</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-yellow-500">⚠️</span>
                            <span class="text-sm"><strong>Basic</strong> Analytics</span>
                        </li>
                    </ul>
                </div>

                {{-- Premium Plan --}}
                <div class="border-4 border-purple-500 rounded-2xl p-6 relative bg-gradient-to-br from-purple-50 to-pink-50">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-xs font-bold rounded-full">
                        RECOMMENDED
                    </div>
                    <div class="text-center mb-6">
                        <span class="text-4xl">💎</span>
                        <h4 class="text-2xl font-black text-purple-800 mt-2">Premium Plan</h4>
                        <p class="text-3xl font-black text-purple-700 mt-2">Rp {{ number_format($premiumPrice, 0, ',', '.') }}</p>
                        <p class="text-sm text-purple-600">One-time payment</p>
                    </div>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-2">
                            <span class="text-purple-500">✓</span>
                            <span class="text-sm font-semibold">Everything in Free, plus:</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-purple-500">💎</span>
                            <span class="text-sm"><strong>All 5 CV Templates</strong></span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-purple-500">💎</span>
                            <span class="text-sm"><strong>Unlimited</strong> CV Exports</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-purple-500">💎</span>
                            <span class="text-sm"><strong>Unlimited</strong> Job Applications</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-purple-500">💎</span>
                            <span class="text-sm"><strong>Advanced</strong> Analytics</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-purple-500">💎</span>
                            <span class="text-sm"><strong>Priority</strong> Support</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>
