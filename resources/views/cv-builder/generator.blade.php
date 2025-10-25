<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg border border-white/30">
                        <img src="{{ asset('images/icon.png') }}"
                             alt="TraKerja Logo"
                             class="w-5 h-5 sm:w-6 sm:h-6"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg sm:text-2xl font-bold bg-gradient-to-r from-[#d983e4] to-[#4e71c5] bg-clip-text text-transparent">
                            CV Generator
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5 hidden sm:block">Generate your professional CV</p>
                    </div>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                <div class="flex items-center space-x-2">
                    <div class="flex items-center space-x-2 bg-green-50 px-2 sm:px-3 py-1 sm:py-1.5 rounded-full">
                        <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-purple-500 rounded-full"></div>
                        <span class="text-xs font-medium text-purple-700">Live</span>
                    </div>
                    <div class="text-xs text-gray-400">
                        {{ now()->format('M d, Y') }}
                    </div>
                </div>
                <a href="{{ route('cv.builder') }}" class="px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm text-gray-600 hover:text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
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
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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

                    <!-- Professional Template (LOCKED) -->
                    <div class="border-2 border-gray-300 rounded-lg overflow-hidden opacity-60">
                        <div class="bg-gray-100 p-4 border-b border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-semibold text-lg">Professional</h4>
                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full font-medium">
                                    LOCKED
                                </span>
                            </div>
                            <p class="text-sm text-gray-600">Classic design with elegant typography</p>
                        </div>
                        <div class="p-4 bg-gray-50 h-48 flex items-center justify-center">
                            <div class="text-center text-gray-400">
                                <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <p class="text-sm">Locked</p>
                            </div>
                        </div>
                        <div class="p-4 bg-white">
                            <button disabled class="w-full px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                </svg>
                                Template Locked
                            </button>
                        </div>
                    </div>

                    <!-- Creative Template (LOCKED) -->
                    <div class="border-2 border-gray-300 rounded-lg overflow-hidden opacity-60">
                        <div class="bg-gray-100 p-4 border-b border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-semibold text-lg">Creative</h4>
                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full font-medium">
                                    LOCKED
                                </span>
                            </div>
                            <p class="text-sm text-gray-600">Modern design with creative layout</p>
                        </div>
                        <div class="p-4 bg-gray-50 h-48 flex items-center justify-center">
                            <div class="text-center text-gray-400">
                                <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <p class="text-sm">Locked</p>
                            </div>
                        </div>
                        <div class="p-4 bg-white">
                            <button disabled class="w-full px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                </svg>
                                Template Locked
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Premium Upsell -->
                @php $userTemplateCount = auth()->user() ? auth()->user()->getCvTemplatesCount() : 1; @endphp
                @if($userTemplateCount < 5)
                    <div class="mt-6 bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-lg p-6">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-purple-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <div class="flex-1">
                                <h4 class="font-semibold text-purple-900 mb-2">
                                    @if(\App\Models\Setting::isMonetizationEnabled())
                                        Upgrade to Premium
                                    @else
                                        More Templates Coming Soon!
                                    @endif
                                </h4>
                                @if(\App\Models\Setting::isMonetizationEnabled())
                                    <p class="text-sm text-purple-700 mb-3">
                                        You currently have access to <strong>{{ $userTemplateCount }} template(s)</strong>. 
                                        Upgrade to premium for all 5 professional templates!
                                    </p>
                                    <ul class="text-sm text-purple-800 space-y-1 mb-4">
                                        <li>✓ Access to all 5 templates (Minimal, Professional, Creative, Modern, Elegant)</li>
                                        <li>✓ Unlimited CV exports</li>
                                        <li>✓ No watermarks</li>
                                        <li>✓ Priority support</li>
                                    </ul>
                                    <p class="text-sm text-purple-700 font-medium">
                                        Contact admin for premium upgrade - Rp {{ number_format(\App\Models\Setting::get('premium_price', 199000), 0, ',', '.') }}
                                    </p>
                                @else
                                    <p class="text-sm text-purple-800">
                                        🎉 <strong>FREE MODE is currently active!</strong> You have access to <strong>{{ $userTemplateCount }} templates</strong> for free. 
                                        When admin enables monetization, premium users will get exclusive access to additional templates.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
