<div class="bg-white rounded-xl shadow-lg p-8">
    <h2 class="text-3xl font-bold mb-6 flex items-center gap-3 bg-gradient-to-r from-purple-600 to-purple-800 bg-clip-text text-transparent">
        💎 Monetization Control
    </h2>
    
    {{-- Current Status Banner --}}
    <div class="p-6 rounded-xl shadow-md mb-8 {{ $monetizationEnabled ? 'bg-gradient-to-r from-purple-500 to-purple-700' : 'bg-gradient-to-r from-emerald-500 to-emerald-700' }}">
        <div class="flex items-center justify-between">
            <div class="text-white">
                <p class="text-sm font-medium opacity-90">Current Status</p>
                <h3 class="text-4xl font-black mt-1 flex items-center gap-3">
                    @if($monetizationEnabled)
                        💎 PREMIUM MODE
                    @else
                        🎁 FREE MODE
                    @endif
                </h3>
                <p class="text-sm mt-3 opacity-90 font-medium">
                    @if($monetizationEnabled)
                        Premium features require payment • Building revenue
                    @else
                        All features FREE for everyone • Growing user base
                    @endif
                </p>
            </div>
            
            <div class="text-right text-white">
                <p class="text-sm opacity-90">Active Users</p>
                <p class="text-4xl font-black">{{ number_format($totalUsers) }}</p>
                <p class="text-xs opacity-80 mt-1">
                    {{ number_format($premiumUsers) }} premium
                    @if($totalUsers > 0)
                        ({{ number_format($premiumUsers / $totalUsers * 100, 1) }}%)
                    @endif
                </p>
            </div>
        </div>
    </div>
    
    {{-- Toggle Buttons --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        {{-- FREE MODE Button --}}
        <button 
            wire:click="toggleMonetization(false)"
            class="group relative p-8 rounded-xl border-3 text-left transition-all duration-300 transform hover:scale-105 {{ !$monetizationEnabled ? 'border-emerald-500 bg-gradient-to-br from-emerald-50 to-emerald-100 shadow-2xl scale-105' : 'border-gray-300 bg-white hover:border-emerald-300 hover:shadow-xl' }}">
            
            <div class="flex items-center gap-3 mb-4">
                <span class="text-5xl">🎁</span>
                <div>
                    <h3 class="font-black text-2xl text-gray-800">FREE MODE</h3>
                    <p class="text-sm text-emerald-600 font-semibold">All Features Unlocked</p>
                </div>
            </div>
            
            <ul class="space-y-2 mb-4">
                <li class="flex items-center gap-2 text-sm text-gray-700">
                    <span class="text-emerald-500">✅</span>
                    <span>All features FREE for everyone</span>
                </li>
                <li class="flex items-center gap-2 text-sm text-gray-700">
                    <span class="text-emerald-500">✅</span>
                    <span>No payment required</span>
                </li>
                <li class="flex items-center gap-2 text-sm text-gray-700">
                    <span class="text-emerald-500">✅</span>
                    <span>Unlimited access to all tools</span>
                </li>
                <li class="flex items-center gap-2 text-sm text-gray-700">
                    <span class="text-emerald-500">✅</span>
                    <span>Perfect for user acquisition</span>
                </li>
            </ul>
            
            @if(!$monetizationEnabled)
                <div class="mt-4 px-4 py-2 bg-emerald-600 text-white text-sm rounded-lg text-center font-bold shadow-lg">
                    ✓ CURRENTLY ACTIVE
                </div>
            @else
                <div class="mt-4 px-4 py-2 bg-gray-100 text-gray-600 text-sm rounded-lg text-center font-semibold group-hover:bg-emerald-100 group-hover:text-emerald-700 transition">
                    Click to Activate FREE Mode
                </div>
            @endif
        </button>
        
        {{-- PREMIUM MODE Button --}}
        <button 
            wire:click="toggleMonetization(true)"
            class="group relative p-8 rounded-xl border-3 text-left transition-all duration-300 transform hover:scale-105 {{ $monetizationEnabled ? 'border-purple-500 bg-gradient-to-br from-purple-50 to-purple-100 shadow-2xl scale-105' : 'border-gray-300 bg-white hover:border-purple-300 hover:shadow-xl' }}">
            
            <div class="flex items-center gap-3 mb-4">
                <span class="text-5xl">💎</span>
                <div>
                    <h3 class="font-black text-2xl text-gray-800">PREMIUM MODE</h3>
                    <p class="text-sm text-purple-600 font-semibold">Monetization Active</p>
                </div>
            </div>
            
            <ul class="space-y-2 mb-4">
                <li class="flex items-center gap-2 text-sm text-gray-700">
                    <span class="text-purple-500">✅</span>
                    <span>Free tier with smart limits</span>
                </li>
                <li class="flex items-center gap-2 text-sm text-gray-700">
                    <span class="text-purple-500">💎</span>
                    <span>Premium tier with full access</span>
                </li>
                <li class="flex items-center gap-2 text-sm text-gray-700">
                    <span class="text-purple-500">✅</span>
                    <span>Generate revenue from users</span>
                </li>
                <li class="flex items-center gap-2 text-sm text-gray-700">
                    <span class="text-purple-500">✅</span>
                    <span>Sustainable business model</span>
                </li>
            </ul>
            
            @if($monetizationEnabled)
                <div class="mt-4 px-4 py-2 bg-purple-600 text-white text-sm rounded-lg text-center font-bold shadow-lg">
                    ✓ CURRENTLY ACTIVE
                </div>
            @else
                <div class="mt-4 px-4 py-2 bg-gray-100 text-gray-600 text-sm rounded-lg text-center font-semibold group-hover:bg-purple-100 group-hover:text-purple-700 transition">
                    Click to Activate PREMIUM Mode
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
        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl p-6 mb-8 border-2 border-purple-200">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                💰 Premium Pricing
            </h3>
            
            <div class="flex items-end gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Premium Price (IDR)
                    </label>
                    <input 
                        type="number" 
                        wire:model="premiumPrice"
                        class="w-full px-4 py-3 border-2 border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-lg font-bold"
                        min="0"
                        step="1000">
                </div>
                <button 
                    wire:click="updatePremiumPrice"
                    class="px-6 py-3 bg-purple-600 text-white rounded-lg font-bold hover:bg-purple-700 transition-all transform hover:scale-105 shadow-md">
                    💾 Update Price
                </button>
            </div>
            
            <p class="text-sm text-gray-600 mt-3">
                Current price: <strong class="text-purple-700">Rp {{ number_format($premiumPrice, 0, ',', '.') }}</strong>
            </p>
        </div>
    @endif
    
    {{-- Feature Access Matrix --}}
    <div class="bg-gray-50 rounded-xl p-6 mb-8">
        <h3 class="text-2xl font-bold mb-6 text-gray-800 flex items-center gap-2">
            📊 Feature Access Matrix
            <span class="text-sm font-normal text-gray-500">
                ({{ $monetizationEnabled ? 'Premium Mode Active' : 'Free Mode Active' }})
            </span>
        </h3>
        
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-700 to-gray-800 text-white">
                        <th class="p-4 text-left font-bold rounded-tl-lg">Feature</th>
                        <th class="p-4 text-center font-bold">🆓 Free Users</th>
                        <th class="p-4 text-center font-bold rounded-tr-lg">💎 Premium Users</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach($featureMatrix as $feature => $access)
                        <tr class="border-b border-gray-200 hover:bg-purple-50 transition">
                            <td class="p-4 font-semibold text-gray-800">{{ $feature }}</td>
                            <td class="p-4 text-center">
                                <span class="inline-block px-4 py-2 rounded-lg text-sm font-bold
                                    {{ str_contains($access['free'], '✅') ? 'bg-emerald-100 text-emerald-800' : 
                                       (str_contains($access['free'], '⚠️') ? 'bg-yellow-100 text-yellow-800' : 
                                       'bg-red-100 text-red-800') }}">
                                    {{ $access['free'] }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <span class="inline-block px-4 py-2 rounded-lg text-sm font-bold bg-purple-100 text-purple-800">
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
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border-2 border-blue-200 shadow-md">
            <div class="flex items-center gap-3 mb-2">
                <span class="text-3xl">👥</span>
                <p class="text-sm font-semibold text-blue-700">Total Users</p>
            </div>
            <p class="text-4xl font-black text-blue-900">{{ number_format($totalUsers) }}</p>
            <p class="text-xs text-blue-600 mt-1">All registered users</p>
        </div>
        
        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl p-6 border-2 border-emerald-200 shadow-md">
            <div class="flex items-center gap-3 mb-2">
                <span class="text-3xl">🆓</span>
                <p class="text-sm font-semibold text-emerald-700">Free Users</p>
            </div>
            <p class="text-4xl font-black text-emerald-900">{{ number_format($freeUsers) }}</p>
            <p class="text-xs text-emerald-600 mt-1">
                {{ $totalUsers > 0 ? number_format($freeUsers / $totalUsers * 100, 1) : 0 }}% of total
            </p>
        </div>
        
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border-2 border-purple-200 shadow-md">
            <div class="flex items-center gap-3 mb-2">
                <span class="text-3xl">💎</span>
                <p class="text-sm font-semibold text-purple-700">Premium Users</p>
            </div>
            <p class="text-4xl font-black text-purple-900">{{ number_format($premiumUsers) }}</p>
            <p class="text-xs text-purple-600 mt-1">
                {{ $totalUsers > 0 ? number_format($premiumUsers / $totalUsers * 100, 1) : 0 }}% conversion
            </p>
        </div>
    </div>
    
    {{-- Help Text --}}
    <div class="mt-8 p-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 rounded-lg">
        <p class="text-sm text-gray-800 flex items-start gap-2">
            <span class="text-xl">💡</span>
            <span>
                <strong>How it works:</strong> When <strong class="text-emerald-600">FREE MODE</strong> is active, all users get full access to every feature without payment. 
                When <strong class="text-purple-600">PREMIUM MODE</strong> is enabled, free users get limited access while premium users (who paid) get full access. 
                Changes take effect immediately and are logged for audit purposes.
            </span>
        </p>
    </div>
</div>
