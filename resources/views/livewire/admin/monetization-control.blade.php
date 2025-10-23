<div class="bg-white rounded-lg shadow-lg p-6">
    <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
        💰 Monetization Phase Control
    </h2>
    
    {{-- Current Phase Display --}}
    <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-primary-700">Current Phase:</h3>
                <p class="text-4xl font-bold text-primary-600 flex items-center gap-2 mt-1">
                    {{ $phaseEmoji }} Phase {{ $currentPhase }}
                </p>
                <p class="text-sm text-primary-600 mt-2 font-medium">
                    @if($currentPhase == 1)
                        🟢 All features FREE for everyone - User acquisition mode
                    @elseif($currentPhase == 2)
                        🟡 Soft premium launch with early bird discounts
                    @else
                        🔵 Full premium monetization with free tier limits
                    @endif
                </p>
            </div>
            
            <div class="text-right">
                <p class="text-sm text-primary-600">Active Users</p>
                <p class="text-3xl font-bold text-primary-800">{{ number_format($totalUsers) }}</p>
                <p class="text-xs text-primary-500 mt-1">
                    {{ number_format($premiumUsers) }} premium ({{ $totalUsers > 0 ? number_format($premiumUsers / $totalUsers * 100, 1) : 0 }}%)
                </p>
            </div>
        </div>
    </div>
    
    {{-- Phase Selection Buttons --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        {{-- Phase 1 --}}
        <button 
            wire:click="setPhase(1)"
            class="p-6 rounded-lg border-2 text-left transition-all duration-200 {{ $currentPhase == 1 ? 'border-primary-500 bg-primary-50 shadow-lg scale-105' : 'border-gray-300 hover:border-primary-300 hover:shadow-md' }}">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-3xl">🟢</span>
                <h3 class="font-bold text-lg">Phase 1</h3>
            </div>
            <p class="text-sm text-primary-600 mb-3 font-medium">Launch - Free All</p>
            <ul class="text-xs space-y-1 text-primary-700">
                <li>✅ All features FREE</li>
                <li>✅ No limitations</li>
                <li>✅ User acquisition focus</li>
                <li>✅ Build user base</li>
            </ul>
            @if($currentPhase == 1)
                <div class="mt-3 px-3 py-1 bg-primary-600 text-white text-xs rounded text-center font-semibold">
                    ✓ ACTIVE NOW
                </div>
            @endif
        </button>
        
        {{-- Phase 2 --}}
        <button 
            wire:click="setPhase(2)"
            class="p-6 rounded-lg border-2 text-left transition-all duration-200 {{ $currentPhase == 2 ? 'border-secondary-500 bg-secondary-50 shadow-lg scale-105' : 'border-gray-300 hover:border-secondary-300 hover:shadow-md' }}">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-3xl">🟡</span>
                <h3 class="font-bold text-lg">Phase 2</h3>
            </div>
            <p class="text-sm text-primary-600 mb-3 font-medium">Soft Premium Launch</p>
            <ul class="text-xs space-y-1 text-primary-700">
                <li>✅ Core features FREE</li>
                <li>💎 Advanced features PREMIUM</li>
                <li>🎁 Early bird discount active</li>
                <li>🚀 Begin monetization</li>
            </ul>
            @if($currentPhase == 2)
                <div class="mt-3 px-3 py-1 bg-yellow-600 text-white text-xs rounded text-center font-semibold">
                    ✓ ACTIVE NOW
                </div>
            @endif
        </button>
        
        {{-- Phase 3 --}}
        <button 
            wire:click="setPhase(3)"
            class="p-6 rounded-lg border-2 text-left transition-all duration-200 {{ $currentPhase == 3 ? 'border-primary-500 bg-primary-50 shadow-lg scale-105' : 'border-gray-300 hover:border-primary-300 hover:shadow-md' }}">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-3xl">🔵</span>
                <h3 class="font-bold text-lg">Phase 3</h3>
            </div>
            <p class="text-sm text-primary-600 mb-3 font-medium">Full Premium</p>
            <ul class="text-xs space-y-1 text-primary-700">
                <li>✅ FREE tier with smart limits</li>
                <li>💎 PREMIUM unlimited access</li>
                <li>🎁 Grandfather existing users</li>
                <li>💰 Maximize revenue</li>
            </ul>
            @if($currentPhase == 3)
                <div class="mt-3 px-3 py-1 bg-primary-600 text-white text-xs rounded text-center font-semibold">
                    ✓ ACTIVE NOW
                </div>
            @endif
        </button>
    </div>
    
    {{-- Confirmation Modal --}}
    @if($showConfirmation)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-2xl p-6 max-w-md w-full mx-4">
                <h4 class="font-bold text-xl text-yellow-800 mb-3 flex items-center gap-2">
                    ⚠️ Confirm Phase Change
                </h4>
                <p class="text-primary-700 mb-4">
                    You are about to change from <strong class="text-primary-600">Phase {{ $currentPhase }}</strong> 
                    to <strong class="text-primary-600">Phase {{ $newPhase }}</strong>.
                </p>
                <div class="p-4 bg-secondary-50 border-l-4 border-secondary-500 rounded mb-4">
                    <p class="text-sm text-yellow-800 font-medium">
                        This will <strong>immediately affect</strong> feature access for all {{ number_format($totalUsers) }} users!
                    </p>
                </div>
                <div class="flex gap-3">
                    <button 
                        wire:click="confirmSetPhase" 
                        class="flex-1 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-green-700 font-semibold transition">
                        ✓ Yes, Change Phase
                    </button>
                    <button 
                        wire:click="cancelSetPhase" 
                        class="flex-1 px-4 py-2 bg-gray-300 text-primary-700 rounded-lg hover:bg-gray-400 font-semibold transition">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    @endif
    
    {{-- Feature Access Matrix --}}
    <div class="mt-8">
        <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
            📊 Feature Access Matrix - Phase {{ $currentPhase }}
        </h3>
        
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
                    <tr>
                        <th class="border border-gray-300 p-3 text-left font-bold">Feature</th>
                        <th class="border border-gray-300 p-3 text-center font-bold">🆓 Free Users</th>
                        <th class="border border-gray-300 p-3 text-center font-bold">💎 Premium Users</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($featureMatrix as $feature => $access)
                        <tr class="hover:bg-primary-50 transition">
                            <td class="border border-gray-300 p-3 font-medium">{{ $feature }}</td>
                            <td class="border border-gray-300 p-3 text-center">
                                @if($access['free'] === true || $access['free'] === 'unlimited')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-primary-100 text-primary-800">
                                        ✅ {{ $access['free'] === 'unlimited' ? 'Unlimited' : 'Yes' }}
                                    </span>
                                @elseif(is_numeric($access['free']))
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                        ⚠️ Limit: {{ $access['free'] }}{{ $feature === 'CV Templates' ? '' : '/month' }}
                                    </span>
                                @elseif($access['free'] === 'No')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-primary-100 text-primary-800">
                                        ✅ No Watermark
                                    </span>
                                @elseif($access['free'] === 'Yes')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        ❌ Has Watermark
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        ❌ No Access
                                    </span>
                                @endif
                            </td>
                            <td class="border border-gray-300 p-3 text-center">
                                @if($access['premium'] === true || $access['premium'] === 'unlimited')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-primary-100 text-primary-800">
                                        ✅ {{ $access['premium'] === 'unlimited' ? 'Unlimited' : 'Yes' }}
                                    </span>
                                @elseif($access['premium'] === 'No')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-primary-100 text-primary-800">
                                        ✅ No Watermark
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-primary-100 text-primary-800">
                                        ✅ Full Access
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    {{-- User Statistics --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
        <div class="p-5 bg-primary-50 rounded-lg border border-gray-200">
            <p class="text-sm text-primary-600 font-medium">Total Users</p>
            <p class="text-3xl font-bold text-primary-800 mt-1">{{ number_format($totalUsers) }}</p>
            <p class="text-xs text-primary-500 mt-1">All registered users</p>
        </div>
        <div class="p-5 bg-primary-50 rounded-lg border border-green-200">
            <p class="text-sm text-green-700 font-medium">Free Users</p>
            <p class="text-3xl font-bold text-primary-600 mt-1">{{ number_format($freeUsers) }}</p>
            <p class="text-xs text-primary-600 mt-1">
                {{ $totalUsers > 0 ? number_format($freeUsers / $totalUsers * 100, 1) : 0 }}% of total
            </p>
        </div>
        <div class="p-5 bg-primary-50 rounded-lg border border-primary-200">
            <p class="text-sm text-primary-700 font-medium">Premium Users</p>
            <p class="text-3xl font-bold text-primary-600 mt-1">{{ number_format($premiumUsers) }}</p>
            <p class="text-xs text-primary-600 mt-1">
                {{ $totalUsers > 0 ? number_format($premiumUsers / $totalUsers * 100, 1) : 0 }}% conversion rate
            </p>
        </div>
    </div>
    
    {{-- Help Text --}}
    <div class="mt-6 p-4 bg-primary-50 border-l-4 border-primary-500 rounded">
        <p class="text-sm text-primary-800">
            <strong>💡 Tip:</strong> Phase changes take effect immediately. Users will see updated feature access on their next page load. 
            All changes are logged for audit purposes.
        </p>
    </div>
</div>
