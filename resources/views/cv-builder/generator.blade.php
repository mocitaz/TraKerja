<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('CV Generator') }}
            </h2>
            <a href="{{ route('cv-builder.index') }}" class="text-gray-600 hover:text-gray-900">
                ← Back to CV Builder
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Export Limits Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">CV Export</h3>
                        @if(!auth()->user()->is_premium)
                            <p class="text-sm text-gray-600 mt-1">
                                You have used <strong>{{ auth()->user()->cv_exports_this_month ?? 0 }}</strong> out of <strong>5</strong> free exports this month.
                            </p>
                        @else
                            <p class="text-sm text-green-600 mt-1">
                                <i class="fas fa-check-circle"></i> Unlimited exports available with Premium
                            </p>
                        @endif
                    </div>
                    @if(!auth()->user()->is_premium && (auth()->user()->cv_exports_this_month ?? 0) >= 5)
                        <a href="{{ route('pricing') }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                            Upgrade to Premium
                        </a>
                    @endif
                </div>
            </div>

            <!-- Template Selection -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Select Template</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Modern Template -->
                    <div class="border-2 border-primary-500 rounded-lg overflow-hidden">
                        <div class="bg-primary-50 p-4 border-b border-primary-200">
                            <h4 class="font-semibold text-lg">Modern</h4>
                            <p class="text-sm text-gray-600">Clean and professional design with blue accents</p>
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
                            <form action="{{ route('cv.export') }}" method="POST">
                                @csrf
                                <input type="hidden" name="template" value="modern">
                                <button type="submit" 
                                        @if(!auth()->user()->is_premium && (auth()->user()->cv_exports_this_month ?? 0) >= 5) disabled @endif
                                        class="w-full px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 disabled:bg-gray-400 disabled:cursor-not-allowed">
                                    <i class="fas fa-download mr-2"></i>Export PDF
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Professional Template (Coming Soon) -->
                    <div class="border-2 border-gray-300 rounded-lg overflow-hidden opacity-60">
                        <div class="bg-gray-100 p-4 border-b border-gray-200">
                            <h4 class="font-semibold text-lg">Professional</h4>
                            <p class="text-sm text-gray-600">Classic design with serif fonts</p>
                            <span class="inline-block mt-2 px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Coming Soon</span>
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
                            <button disabled class="w-full px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed">
                                Coming Soon
                            </button>
                        </div>
                    </div>

                    <!-- Minimal Template (Coming Soon) -->
                    <div class="border-2 border-gray-300 rounded-lg overflow-hidden opacity-60">
                        <div class="bg-gray-100 p-4 border-b border-gray-200">
                            <h4 class="font-semibold text-lg">Minimal</h4>
                            <p class="text-sm text-gray-600">ATS-friendly simple layout</p>
                            <span class="inline-block mt-2 px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Coming Soon</span>
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
                            <button disabled class="w-full px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed">
                                Coming Soon
                            </button>
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
                                    <li>✓ Unlimited CV exports</li>
                                    <li>✓ Access to all templates (3+ premium designs)</li>
                                    <li>✓ No watermarks on exported CVs</li>
                                    <li>✓ Priority support</li>
                                </ul>
                                <a href="{{ route('pricing') }}" class="inline-block px-6 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                                    View Pricing
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
