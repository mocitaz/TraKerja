<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg border border-white/30">
                        <img src="{{ asset('images/icon.png') }}"
                             alt="TraKerja Logo"
                             class="w-6 h-6"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-purple-800 bg-clip-text text-transparent">
                            CV Generator
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">Generate your professional CV</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <div class="flex items-center space-x-2 bg-green-50 px-3 py-1.5 rounded-full">
                    <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                    <span class="text-xs font-medium text-purple-700">Live</span>
                </div>
                <div class="text-xs text-gray-400">
                    {{ now()->format('M d, Y') }}
                </div>
                <a href="{{ route('cv.builder') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    ← Back to CV Builder
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- CV Preview Component -->
            @livewire('cv-generator.cv-preview')
            
            <!-- Export Info - Unlimited for All Users -->
            <div class="bg-emerald-50 border-l-4 border-emerald-400 p-4 mb-6 rounded-r-lg shadow-sm">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-emerald-400 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="text-sm text-emerald-800">
                            <strong>Unlimited CV Exports</strong> - Generate and download your CV as many times as you need!
                        </p>
                    </div>
                </div>
            </div>

            <!-- Template Selection -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Select Template</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Minimal Template (FREE) -->
                    <div class="border-2 border-primary-500 rounded-lg overflow-hidden">
                        <div class="bg-primary-50 p-4 border-b border-primary-200">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-semibold text-lg">Minimal</h4>
                                <span class="px-2 py-1 bg-emerald-100 text-emerald-800 text-xs rounded-full font-medium">FREE</span>
                            </div>
                            <p class="text-sm text-gray-600">Simple ATS-friendly layout, perfect for all users</p>
                        </div>
                        <div class="p-4 bg-gray-50 h-48 flex items-center justify-center">
                            <div class="text-center text-gray-400">
                                <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-sm">Preview</p>
                            </div>
                        </div>
                        <div class="p-4 bg-white">
                            <button 
                                    onclick="Livewire.dispatch('openPreview', { template: 'minimal' })"
                                    class="w-full bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Preview & Export CV
                            </button>
                        </div>
                    </div>

                    <!-- Professional Template (PREMIUM) -->
                    <div class="border-2 {{ auth()->user()->is_premium ? 'border-yellow-400' : 'border-gray-300' }} rounded-lg overflow-hidden {{ auth()->user()->is_premium ? '' : 'opacity-60' }}">
                        <div class="{{ auth()->user()->is_premium ? 'bg-yellow-50' : 'bg-gray-100' }} p-4 border-b {{ auth()->user()->is_premium ? 'border-yellow-200' : 'border-gray-200' }}">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-semibold text-lg">Professional</h4>
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full font-medium">PREMIUM</span>
                            </div>
                            <p class="text-sm text-gray-600">Classic design with elegant typography</p>
                        </div>
                        <div class="p-4 bg-gray-50 h-48 flex items-center justify-center">
                            <div class="text-center text-gray-400">
                                <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-sm">Preview</p>
                            </div>
                        </div>
                        <div class="p-4 bg-white">
                            @if(auth()->user()->is_premium)
                                <button onclick="Livewire.dispatch('openPreview', { template: 'professional' })"
                                        class="w-full bg-gradient-to-r from-yellow-600 to-yellow-700 hover:from-yellow-700 hover:to-yellow-800 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Preview & Export CV
                                </button>
                            @else
                                <button disabled class="w-full px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Premium Only
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Creative Template (PREMIUM) -->
                    <div class="border-2 {{ auth()->user()->is_premium ? 'border-yellow-400' : 'border-gray-300' }} rounded-lg overflow-hidden {{ auth()->user()->is_premium ? '' : 'opacity-60' }}">
                        <div class="{{ auth()->user()->is_premium ? 'bg-yellow-50' : 'bg-gray-100' }} p-4 border-b {{ auth()->user()->is_premium ? 'border-yellow-200' : 'border-gray-200' }}">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-semibold text-lg">Creative</h4>
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full font-medium">PREMIUM</span>
                            </div>
                            <p class="text-sm text-gray-600">Modern design with creative layout</p>
                        </div>
                        <div class="p-4 bg-gray-50 h-48 flex items-center justify-center">
                            <div class="text-center text-gray-400">
                                <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                                </svg>
                                <p class="text-sm">Preview</p>
                            </div>
                        </div>
                        <div class="p-4 bg-white">
                            @if(auth()->user()->is_premium)
                                <button onclick="Livewire.dispatch('openPreview', { template: 'creative' })"
                                        class="w-full bg-gradient-to-r from-yellow-600 to-yellow-700 hover:from-yellow-700 hover:to-yellow-800 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Preview & Export CV
                                </button>
                            @else
                                <button disabled class="w-full px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Premium Only
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Premium Upsell -->
                @if(!auth()->user()->is_premium)
                    <div class="mt-6 bg-gradient-to-r from-yellow-50 to-yellow-100 border border-yellow-200 rounded-lg p-6">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <div class="flex-1">
                                <h4 class="font-semibold text-yellow-900 mb-2">Upgrade to Premium</h4>
                                <ul class="text-sm text-yellow-800 space-y-1 mb-4">
                                    <li>✓ Access to all 3 templates (Minimal, Professional, Creative)</li>
                                    <li>✓ No watermarks on exported CVs</li>
                                    <li>✓ Priority support</li>
                                </ul>
                                <p class="text-sm text-yellow-700 font-medium">
                                    Contact admin for premium upgrade
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
