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
            <div class="hidden sm:flex items-center space-x-2">
                <div class="hidden sm:flex items-center space-x-2 bg-green-50 px-2 sm:px-3 py-1 sm:py-1.5 rounded-full">
                    <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-primary-500 rounded-full animate-pulse"></div>
                    <span class="text-xs font-medium text-primary-700">Live</span>
                </div>
                <div class="text-xs text-gray-400 hidden sm:block">
                    {{ now()->format('M d, Y') }}
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Back Button -->
            <div class="mb-4 sm:mb-6">
                <a href="{{ route('cv.builder') }}" class="inline-flex items-center gap-2 px-3 sm:px-4 py-2 text-xs sm:text-sm text-gray-600 hover:text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to CV Builder
                </a>
            </div>
            
            <!-- CV Preview Component -->
            @livewire('cv-generator.cv-preview')
            
            <!-- Export Info - Clean and Subtle -->
            <div class="bg-white border border-gray-200 rounded-lg p-3 sm:p-4 mb-4 sm:mb-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 text-xs sm:text-sm">All Templates Available</h4>
                            <p class="text-xs text-gray-600">Unlimited exports • Professional designs • ATS-friendly</p>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded hidden sm:block">
                        Free Mode
                    </div>
                </div>
            </div>

            <!-- Template Selection -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">Select Template</h3>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1">Choose from our professional CV templates</p>
                </div>
                <div class="p-4 sm:p-6">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                    <!-- Minimal Template -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-200">
                        <div class="p-3 sm:p-4 border-b border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-semibold text-gray-900 text-sm sm:text-base">Minimal</h4>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full font-medium">Free</span>
                            </div>
                            <p class="text-xs sm:text-sm text-gray-600">Clean, ATS-friendly design</p>
                        </div>
                        <div class="p-3 sm:p-4 bg-gray-50 h-24 sm:h-32 flex items-center justify-center">
                            <div class="text-center text-gray-400">
                                <svg class="w-8 h-8 sm:w-12 sm:h-12 mx-auto mb-1 sm:mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-xs">Preview</p>
                            </div>
                        </div>
                        <div class="p-3 sm:p-4 bg-white">
                            <form method="POST" action="{{ route('cv-builder.preview') }}" target="_blank" class="w-full">
                                @csrf
                                <input type="hidden" name="template" value="minimal">
                                <button type="submit"
                                    class="w-full bg-purple-600 hover:bg-purple-700 text-white px-3 sm:px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center gap-2 text-xs sm:text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Preview
                            </button>
                        </div>
                    </div>

                    <!-- Professional Template -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-200">
                        <div class="p-3 sm:p-4 border-b border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-semibold text-gray-900 text-sm sm:text-base">Professional</h4>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full font-medium">Free</span>
                            </div>
                            <p class="text-xs sm:text-sm text-gray-600">Modern, corporate design</p>
                        </div>
                        <div class="p-3 sm:p-4 bg-gray-50 h-24 sm:h-32 flex items-center justify-center">
                            <div class="text-center text-gray-400">
                                <svg class="w-8 h-8 sm:w-12 sm:h-12 mx-auto mb-1 sm:mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-xs">Preview</p>
                            </div>
                        </div>
                        <div class="p-3 sm:p-4 bg-white">
                            <form method="POST" action="{{ route('cv-builder.preview') }}" target="_blank" class="w-full">
                                @csrf
                                <input type="hidden" name="template" value="professional">
                                <button type="submit"
                                    class="w-full bg-purple-600 hover:bg-purple-700 text-white px-3 sm:px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center gap-2 text-xs sm:text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Preview
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Creative Template -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-200">
                        <div class="p-3 sm:p-4 border-b border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-semibold text-gray-900 text-sm sm:text-base">Creative</h4>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full font-medium">Free</span>
                            </div>
                            <p class="text-xs sm:text-sm text-gray-600">Modern design with creative layout</p>
                        </div>
                        <div class="p-3 sm:p-4 bg-gray-50 h-24 sm:h-32 flex items-center justify-center">
                            <div class="text-center text-gray-400">
                                <svg class="w-8 h-8 sm:w-12 sm:h-12 mx-auto mb-1 sm:mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                                </svg>
                                <p class="text-xs">Preview</p>
                            </div>
                        </div>
                        <div class="p-3 sm:p-4 bg-white">
                            <form method="POST" action="{{ route('cv-builder.preview') }}" target="_blank" class="w-full">
                                @csrf
                                <input type="hidden" name="template" value="creative">
                                <button type="submit"
                                    class="w-full bg-purple-600 hover:bg-purple-700 text-white px-3 sm:px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center gap-2 text-xs sm:text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Preview
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Elegant Template -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-200">
                        <div class="p-3 sm:p-4 border-b border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-semibold text-gray-900 text-sm sm:text-base">Elegant</h4>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full font-medium">Free</span>
                            </div>
                            <p class="text-xs sm:text-sm text-gray-600">Sophisticated two-column layout</p>
                        </div>
                        <div class="p-3 sm:p-4 bg-gray-50 h-24 sm:h-32 flex items-center justify-center">
                            <div class="text-center text-gray-400">
                                <svg class="w-8 h-8 sm:w-12 sm:h-12 mx-auto mb-1 sm:mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <p class="text-xs">Preview</p>
                            </div>
                        </div>
                        <div class="p-3 sm:p-4 bg-white">
                            <form method="POST" action="{{ route('cv-builder.preview') }}" target="_blank" class="w-full">
                                @csrf
                                <input type="hidden" name="template" value="elegant">
                                <button type="submit"
                                    class="w-full bg-purple-600 hover:bg-purple-700 text-white px-3 sm:px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center gap-2 text-xs sm:text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Preview
                                </button>
                            </form>
                        </div>
                    </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
