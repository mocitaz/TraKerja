<div class="space-y-6">
    
    {{-- Current Status Banner --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 {{ $monetizationEnabled ? 'bg-purple-100' : 'bg-emerald-100' }} rounded-lg flex items-center justify-center">
                    <span class="text-2xl">{{ $monetizationEnabled ? '💎' : '🎁' }}</span>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ $monetizationEnabled ? 'Premium Mode Active' : 'Free Mode Active' }}
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        @if($monetizationEnabled)
                            Premium features require payment • Building revenue
                        @else
                            All features FREE for everyone • Growing user base
                        @endif
                    </p>
                </div>
            </div>
            
            <div class="text-right">
                <p class="text-sm text-gray-500">Active Users</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($totalUsers) }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    {{ number_format($premiumUsers) }} premium
                    @if($totalUsers > 0)
                        ({{ number_format($premiumUsers / $totalUsers * 100, 1) }}%)
                    @endif
                </p>
            </div>
        </div>
    </div>
    
    {{-- Toggle Buttons --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- FREE MODE Button --}}
        <button 
            wire:click="toggleMonetization(false)"
            class="group relative p-6 rounded-lg border text-left transition-all duration-200 {{ !$monetizationEnabled ? 'border-emerald-500 bg-emerald-50 shadow-sm' : 'border-gray-200 bg-white hover:border-emerald-300 hover:shadow-sm' }}">
            
            <div class="flex items-center gap-3 mb-3">
                <span class="text-2xl">🎁</span>
                <div>
                    <h3 class="font-semibold text-lg text-gray-900">FREE MODE</h3>
                    <p class="text-sm text-emerald-600 font-medium">All Features Unlocked</p>
                </div>
            </div>
            
            <ul class="space-y-1 mb-3">
                <li class="flex items-center gap-2 text-xs text-gray-600">
                    <span class="text-emerald-500">✓</span>
                    <span>All features FREE for everyone</span>
                </li>
                <li class="flex items-center gap-2 text-xs text-gray-600">
                    <span class="text-emerald-500">✓</span>
                    <span>No payment required</span>
                </li>
                <li class="flex items-center gap-2 text-xs text-gray-600">
                    <span class="text-emerald-500">✓</span>
                    <span>Unlimited access to all tools</span>
                </li>
            </ul>
            
            @if(!$monetizationEnabled)
                <div class="px-3 py-1.5 bg-emerald-600 text-white text-xs rounded font-medium text-center">
                    ✓ ACTIVE
                </div>
            @else
                <div class="px-3 py-1.5 bg-gray-100 text-gray-600 text-xs rounded text-center group-hover:bg-emerald-100 group-hover:text-emerald-700 transition">
                    Click to Activate
                </div>
            @endif
        </button>
        
        {{-- PREMIUM MODE Button --}}
        <button 
            wire:click="toggleMonetization(true)"
            class="group relative p-6 rounded-lg border text-left transition-all duration-200 {{ $monetizationEnabled ? 'border-purple-500 bg-purple-50 shadow-sm' : 'border-gray-200 bg-white hover:border-purple-300 hover:shadow-sm' }}">
            
            <div class="flex items-center gap-3 mb-3">
                <span class="text-2xl">💎</span>
                <div>
                    <h3 class="font-semibold text-lg text-gray-900">PREMIUM MODE</h3>
                    <p class="text-sm text-purple-600 font-medium">Monetization Active</p>
                </div>
            </div>
            
            <ul class="space-y-1 mb-3">
                <li class="flex items-center gap-2 text-xs text-gray-600">
                    <span class="text-purple-500">✓</span>
                    <span>Free tier with smart limits</span>
                </li>
                <li class="flex items-center gap-2 text-xs text-gray-600">
                    <span class="text-purple-500">💎</span>
                    <span>Premium tier with full access</span>
                </li>
                <li class="flex items-center gap-2 text-xs text-gray-600">
                    <span class="text-purple-500">✓</span>
                    <span>Generate revenue from users</span>
                </li>
            </ul>
            
            @if($monetizationEnabled)
                <div class="px-3 py-1.5 bg-purple-600 text-white text-xs rounded font-medium text-center">
                    ✓ ACTIVE
                </div>
            @else
                <div class="px-3 py-1.5 bg-gray-100 text-gray-600 text-xs rounded text-center group-hover:bg-purple-100 group-hover:text-purple-700 transition">
                    Click to Activate
                </div>
            @endif
        </button>
    </div>
    
    {{-- Confirmation Modal --}}
    @if($showConfirmation)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-lg w-full">
                <div class="text-center mb-6">
                    <div class="text-6xl mb-4">
                        @if($actionType === 'enable')
                            💎
                        @else
                            🎁
                        @endif
                    </div>
                    <h4 class="font-black text-2xl text-gray-800 mb-2">
                        @if($actionType === 'enable')
                            Enable Premium Mode?
                        @else
                            Disable Premium Mode?
                        @endif
                    </h4>
                    <p class="text-gray-600">
                        @if($actionType === 'enable')
                            You are about to <strong class="text-purple-600">ENABLE monetization</strong>.
                        @else
                            You are about to <strong class="text-emerald-600">DISABLE monetization</strong>.
                        @endif
                    </p>
                </div>
                
                <div class="p-5 {{ $actionType === 'enable' ? 'bg-purple-50 border-l-4 border-purple-500' : 'bg-emerald-50 border-l-4 border-emerald-500' }} rounded-lg mb-6">
                    <p class="text-sm font-bold text-gray-800 mb-2">This will affect:</p>
                    <ul class="text-sm space-y-1 text-gray-700">
                        <li>✓ All <strong>{{ number_format($totalUsers) }} users</strong> immediately</li>
                        <li>✓ Feature access will be updated</li>
                        <li>✓ Changes logged for audit</li>
                        @if($actionType === 'enable')
                            <li>✓ Premium features locked for free users</li>
                        @else
                            <li>✓ All features unlocked for everyone</li>
                        @endif
                    </ul>
                </div>
                
                <div class="flex gap-4">
                    <button 
                        wire:click="confirmToggle" 
                        class="flex-1 px-6 py-3 {{ $actionType === 'enable' ? 'bg-purple-600 hover:bg-purple-700' : 'bg-emerald-600 hover:bg-emerald-700' }} text-white rounded-xl font-bold transition-all transform hover:scale-105 shadow-lg">
                        ✓ Yes, Confirm
                    </button>
                    <button 
                        wire:click="cancelToggle" 
                        class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-300 transition-all">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    @endif
    
    {{-- Premium Pricing Settings --}}
    @if($monetizationEnabled)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                💰 Premium Pricing
            </h3>
            
            <div class="flex items-end gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Premium Price (IDR)
                    </label>
                    <input 
                        type="number" 
                        wire:model="premiumPrice"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                        min="0"
                        step="1000">
                </div>
                <button 
                    wire:click="updatePremiumPrice"
                    class="px-4 py-2 bg-purple-600 text-white rounded-lg font-medium hover:bg-purple-700 transition-all">
                    Update Price
                </button>
            </div>
            
            <p class="text-sm text-gray-600 mt-3">
                Current price: <strong class="text-purple-700">Rp {{ number_format($premiumPrice, 0, ',', '.') }}</strong>
            </p>
        </div>
    @endif
    
    {{-- Feature Access Matrix --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-900 flex items-center gap-2">
            📊 Feature Access Matrix
            <span class="text-sm font-normal text-gray-500">
                ({{ $monetizationEnabled ? 'Premium Mode Active' : 'Free Mode Active' }})
            </span>
        </h3>
        
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-900">
                        <th class="px-4 py-3 text-left font-semibold text-sm">Feature</th>
                        <th class="px-4 py-3 text-center font-semibold text-sm">🆓 Free Users</th>
                        <th class="px-4 py-3 text-center font-semibold text-sm">💎 Premium Users</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach($featureMatrix as $feature => $access)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $feature }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-block px-2 py-1 rounded text-xs font-medium
                                    {{ str_contains($access['free'], '✅') ? 'bg-emerald-100 text-emerald-800' : 
                                       (str_contains($access['free'], '⚠️') ? 'bg-yellow-100 text-yellow-800' : 
                                       'bg-red-100 text-red-800') }}">
                                    {{ $access['free'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-block px-2 py-1 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ $access['premium'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    {{-- User Statistics --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center gap-3 mb-2">
                <span class="text-2xl">👥</span>
                <p class="text-sm font-medium text-gray-600">Total Users</p>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalUsers) }}</p>
            <p class="text-xs text-gray-500 mt-1">All registered users</p>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center gap-3 mb-2">
                <span class="text-2xl">🆓</span>
                <p class="text-sm font-medium text-gray-600">Free Users</p>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($freeUsers) }}</p>
            <p class="text-xs text-gray-500 mt-1">
                {{ $totalUsers > 0 ? number_format($freeUsers / $totalUsers * 100, 1) : 0 }}% of total
            </p>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center gap-3 mb-2">
                <span class="text-2xl">💎</span>
                <p class="text-sm font-medium text-gray-600">Premium Users</p>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($premiumUsers) }}</p>
            <p class="text-xs text-gray-500 mt-1">
                {{ $totalUsers > 0 ? number_format($premiumUsers / $totalUsers * 100, 1) : 0 }}% conversion
            </p>
        </div>
    </div>
    
    {{-- Help Text --}}
    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <span class="text-lg">💡</span>
            </div>
            <div class="text-sm">
                <p class="font-semibold text-purple-900 mb-1">How it works</p>
                <p class="text-purple-800 text-xs leading-relaxed">
                    When <strong class="text-emerald-600">FREE MODE</strong> is active, all users get full access to every feature without payment. 
                    When <strong class="text-purple-600">PREMIUM MODE</strong> is enabled, free users get limited access while premium users (who paid) get full access. 
                    Changes take effect immediately and are logged for audit purposes.
                </p>
            </div>
        </div>
    </div>
</div>
