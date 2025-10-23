<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-primary-900 dark:text-white">⚙️ App Settings</h1>
        <p class="text-primary-600 dark:text-primary-400 mt-2">Manage pricing, feature flags, and limits</p>
    </div>

    {{-- Quick Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-primary-600 dark:text-primary-400">Premium Price</p>
                    <p class="text-2xl font-bold text-primary-900 dark:text-white mt-1">
                        Rp {{ number_format($premiumPrice) }}
                    </p>
                </div>
                <div class="bg-primary-100 dark:bg-primary-900 rounded-full p-3">
                    <svg class="w-6 h-6 text-primary-600 dark:text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            @if($hasDiscount)
                <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-primary-600 dark:text-primary-400">
                        Discounted: Rp {{ number_format($discountedPrice) }}
                    </p>
                </div>
            @endif
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-primary-600 dark:text-primary-400">Active Settings</p>
                    <p class="text-2xl font-bold text-primary-900 dark:text-white mt-1">
                        {{ $settings->flatten()->where('is_active', true)->count() }}
                    </p>
                </div>
                <div class="bg-primary-100 dark:bg-primary-900 rounded-full p-3">
                    <svg class="w-6 h-6 text-primary-600 dark:text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-primary-600 dark:text-primary-400">Total Settings</p>
                    <p class="text-2xl font-bold text-primary-900 dark:text-white mt-1">
                        {{ $settings->flatten()->count() }}
                    </p>
                </div>
                <div class="bg-purple-100 dark:bg-purple-900 rounded-full p-3">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
        <nav class="-mb-px flex space-x-8">
            <button wire:click="setTab('pricing')" 
                    class="@if($activeTab === 'pricing') border-primary-500 text-primary-600 @else border-transparent text-primary-500 hover:text-primary-700 hover:border-primary-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                💰 Pricing
            </button>
            <button wire:click="setTab('features')" 
                    class="@if($activeTab === 'features') border-primary-500 text-primary-600 @else border-transparent text-primary-500 hover:text-primary-700 hover:border-primary-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                🎨 Features
            </button>
            <button wire:click="setTab('limits')" 
                    class="@if($activeTab === 'limits') border-primary-500 text-primary-600 @else border-transparent text-primary-500 hover:text-primary-700 hover:border-primary-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                🔒 Limits
            </button>
            <button wire:click="setTab('general')" 
                    class="@if($activeTab === 'general') border-primary-500 text-primary-600 @else border-transparent text-primary-500 hover:text-primary-700 hover:border-primary-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                ⚙️ General
            </button>
        </nav>
    </div>

    {{-- Settings Table --}}
    @if(isset($settings[$activeTab]))
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-primary-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-500 dark:text-primary-400 uppercase tracking-wider">Setting</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-500 dark:text-primary-400 uppercase tracking-wider">Value</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-500 dark:text-primary-400 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-500 dark:text-primary-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-primary-500 dark:text-primary-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($settings[$activeTab] as $setting)
                        <tr class="hover:bg-primary-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-primary-900 dark:text-white">
                                    {{ $setting->key }}
                                </div>
                                <div class="text-sm text-primary-500 dark:text-primary-400">
                                    {{ $setting->description }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-primary-900 dark:text-white font-mono">
                                    @if($setting->type === 'boolean')
                                        <span class="px-2 py-1 rounded {{ $setting->value ? 'bg-primary-100 text-primary-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $setting->value ? 'True' : 'False' }}
                                        </span>
                                    @elseif($setting->type === 'number')
                                        {{ number_format($setting->value) }}
                                    @else
                                        {{ $setting->value ?: '(empty)' }}
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-primary-500 dark:text-primary-400">
                                {{ ucfirst($setting->type) }}
                            </td>
                            <td class="px-6 py-4">
                                <button wire:click="toggleActive({{ $setting->id }})"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $setting->is_active ? 'bg-primary-100 text-primary-800' : 'bg-primary-50 text-primary-800' }}">
                                    {{ $setting->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <button wire:click="editSetting({{ $setting->id }})"
                                        class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 mr-3">
                                    Edit
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
            <p class="text-primary-500 dark:text-primary-400">No settings found for this category</p>
        </div>
    @endif

    {{-- Quick Actions for Pricing --}}
    @if($activeTab === 'pricing')
        <div class="mt-6 bg-primary-50 dark:bg-primary-900/20 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-primary-900 dark:text-white mb-4">💡 Quick Price Presets</h3>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                <button wire:click="quickUpdatePrice(99000)" 
                        class="bg-white dark:bg-gray-800 hover:bg-primary-50 dark:hover:bg-gray-700 text-primary-900 dark:text-white font-semibold py-3 px-4 rounded-lg border border-gray-300 dark:border-gray-600 transition">
                    Rp 99K
                </button>
                <button wire:click="quickUpdatePrice(149000)" 
                        class="bg-white dark:bg-gray-800 hover:bg-primary-50 dark:hover:bg-gray-700 text-primary-900 dark:text-white font-semibold py-3 px-4 rounded-lg border border-gray-300 dark:border-gray-600 transition">
                    Rp 149K
                </button>
                <button wire:click="quickUpdatePrice(199000)" 
                        class="bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-4 rounded-lg transition">
                    Rp 199K ⭐
                </button>
                <button wire:click="quickUpdatePrice(249000)" 
                        class="bg-white dark:bg-gray-800 hover:bg-primary-50 dark:hover:bg-gray-700 text-primary-900 dark:text-white font-semibold py-3 px-4 rounded-lg border border-gray-300 dark:border-gray-600 transition">
                    Rp 249K
                </button>
                <button wire:click="quickUpdatePrice(299000)" 
                        class="bg-white dark:bg-gray-800 hover:bg-primary-50 dark:hover:bg-gray-700 text-primary-900 dark:text-white font-semibold py-3 px-4 rounded-lg border border-gray-300 dark:border-gray-600 transition">
                    Rp 299K
                </button>
            </div>
        </div>
    @endif

    {{-- Edit Modal --}}
    @if($showEditModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-primary-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-primary-900 dark:text-white mb-4">
                            Edit Setting: {{ $selectedSetting->key ?? '' }}
                        </h3>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-primary-700 dark:text-gray-300 mb-2">
                                {{ $selectedSetting->description ?? '' }}
                            </label>

                            @if($selectedSetting && $selectedSetting->type === 'boolean')
                                <select wire:model="editValue" 
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    <option value="0">False</option>
                                    <option value="1">True</option>
                                </select>
                            @elseif($selectedSetting && isset($selectedSetting->metadata['options']))
                                <select wire:model="editValue" 
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @foreach($selectedSetting->metadata['options'] as $option)
                                        <option value="{{ $option }}">{{ ucfirst($option) }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="text" 
                                       wire:model="editValue"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            @endif

                            @if($selectedSetting && $selectedSetting->type === 'number' && isset($selectedSetting->metadata['min']))
                                <p class="mt-1 text-sm text-primary-500 dark:text-primary-400">
                                    Valid range: {{ $selectedSetting->metadata['min'] }} - {{ $selectedSetting->metadata['max'] }}
                                </p>
                            @endif

                            @error('editValue')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        @if($errorMessage)
                            <div class="mb-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded">
                                {{ $errorMessage }}
                            </div>
                        @endif
                    </div>

                    <div class="bg-primary-50 dark:bg-gray-900 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="saveSetting"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Save Changes
                        </button>
                        <button wire:click="closeModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-primary-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
