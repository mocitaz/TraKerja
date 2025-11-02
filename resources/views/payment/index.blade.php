<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg border border-white/30">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg sm:text-2xl font-bold bg-gradient-to-r from-[#d983e4] to-[#4e71c5] bg-clip-text text-transparent">
                            Upgrade to Premium
                        </h2>
                        <p class="text-xs sm:text-sm text-white/80 mt-0.5">Pilih metode pembayaran</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                <div class="flex items-center space-x-2 px-3 py-1.5 bg-white/20 backdrop-blur-sm rounded-lg border border-white/30">
                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                    <span class="text-white text-xs sm:text-sm font-medium">Live</span>
                    <span class="text-white/70 text-xs">•</span>
                    <span class="text-white/90 text-xs">{{ now()->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <!-- Premium Benefits Card - Modern & Attractive -->
            <div class="relative bg-gradient-to-br from-[#d983e4] via-[#8b5cf6] to-[#4e71c5] rounded-2xl p-6 mb-6 shadow-2xl overflow-hidden">
                <!-- Animated gradient background -->
                <div class="absolute inset-0 opacity-30">
                    <div class="absolute top-0 -left-4 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-2xl animate-blob"></div>
                    <div class="absolute top-0 -right-4 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-2xl animate-blob animation-delay-2000"></div>
                    <div class="absolute -bottom-8 left-20 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-2xl animate-blob animation-delay-4000"></div>
                </div>

                <!-- Decorative elements -->
                <div class="absolute top-4 right-4 opacity-10">
                    <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                </div>

                <div class="relative">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-white">TraKerja Premium</h3>
                                <p class="text-sm text-white/90">Unlock your career potential</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-xs font-bold rounded-full border border-white/30">
                            ✨ LIFETIME ACCESS
                        </span>
                    </div>

                    <!-- Features Grid -->
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                            <div class="flex items-center space-x-2 mb-1">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-.5a1.5 1.5 0 000 3h.5a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-.5a1.5 1.5 0 00-3 0v.5a1 1 0 01-1 1H6a1 1 0 01-1-1v-3a1 1 0 00-1-1h-.5a1.5 1.5 0 010-3H4a1 1 0 001-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5z"></path>
                                </svg>
                                <span class="text-white font-semibold text-sm">AI-Powered</span>
                            </div>
                            <p class="text-white/80 text-xs">Smart CV analysis with GPT-4</p>
                        </div>

                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                            <div class="flex items-center space-x-2 mb-1">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                </svg>
                                <span class="text-white font-semibold text-sm">Pro Templates</span>
                            </div>
                            <p class="text-white/80 text-xs">Premium ATS-friendly designs</p>
                        </div>

                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                            <div class="flex items-center space-x-2 mb-1">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-white font-semibold text-sm">Unlimited Exports</span>
                            </div>
                            <p class="text-white/80 text-xs">Download as PDF anytime</p>
                        </div>

                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                            <div class="flex items-center space-x-2 mb-1">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-2 0c0 .993-.241 1.929-.668 2.754l-1.524-1.525a3.997 3.997 0 00.078-2.183l1.562-1.562C15.802 8.249 16 9.1 16 10zm-5.165 3.913l1.58 1.58A5.98 5.98 0 0110 16a5.976 5.976 0 01-2.516-.552l1.562-1.562a4.006 4.006 0 001.789.027zm-4.677-2.796a4.002 4.002 0 01-.041-2.08l-.08.08-1.53-1.533A5.98 5.98 0 004 10c0 .954.223 1.856.619 2.657l1.54-1.54zm1.088-6.45A5.974 5.974 0 0110 4c.954 0 1.856.223 2.657.619l-1.54 1.54a4.002 4.002 0 00-2.346.033L7.246 4.668zM12 10a2 2 0 11-4 0 2 2 0 014 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-white font-semibold text-sm">Priority Support</span>
                            </div>
                            <p class="text-white/80 text-xs">Fast response from our team</p>
                        </div>
                    </div>

                    <!-- Price & CTA -->
                    <div class="flex items-center justify-between bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                        <div>
                            <p class="text-white/80 text-xs mb-1">One-time payment, forever yours</p>
                            <div class="flex items-baseline space-x-2">
                                <span class="text-4xl font-black text-white">Rp 15.000</span>
                                <span class="text-white/60 text-sm line-through">Rp 100.000</span>
                            </div>
                        </div>
                        <div class="flex flex-col items-end space-y-1">
                            <span class="px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full animate-pulse">
                                🔥 Special Launch Price
                            </span>
                            <span class="text-white/70 text-xs">Limited time offer</span>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                @keyframes blob {
                    0%, 100% { transform: translate(0px, 0px) scale(1); }
                    33% { transform: translate(30px, -50px) scale(1.1); }
                    66% { transform: translate(-20px, 20px) scale(0.9); }
                }
                .animate-blob {
                    animation: blob 7s infinite;
                }
                .animation-delay-2000 {
                    animation-delay: 2s;
                }
                .animation-delay-4000 {
                    animation-delay: 4s;
                }
            </style>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Terjadi kesalahan:</h3>
                            <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Payment Methods -->
            <div class="bg-white rounded-2xl shadow-sm p-6 sm:p-8">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Pilih Metode Pembayaran</h4>
                
                <form action="{{ route('payment.checkout') }}" method="POST" id="paymentForm">
                    @csrf
                    
                    <div class="space-y-6">
                        @foreach($groupedChannels as $categoryCode => $channels)
                            @php
                                $categoryName = $channels->first()['category']['name'] ?? $categoryCode;
                            @endphp
                            
                            <div>
                                <h5 class="text-sm font-medium text-gray-700 mb-3">{{ $categoryName }}</h5>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    @foreach($channels as $channel)
                                        <label class="relative cursor-pointer">
                                            <input type="radio" 
                                                   name="payment_channel_code" 
                                                   value="{{ $channel['code'] }}" 
                                                   class="peer sr-only" 
                                                   required>
                                            <div class="border-2 border-gray-200 rounded-xl p-4 hover:border-primary-500 peer-checked:border-primary-500 peer-checked:bg-primary-50 transition-all duration-200">
                                                @if($channel['image_url'])
                                                    <img src="{{ $channel['image_url'] }}" 
                                                         alt="{{ $channel['name'] }}" 
                                                         class="h-8 mx-auto mb-2 object-contain">
                                                @endif
                                                <p class="text-xs text-center font-medium text-gray-700">{{ $channel['name'] }}</p>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6 sm:mt-8">
                        <button type="submit" 
                                id="submitBtn"
                                class="w-full bg-gradient-to-r from-primary-500 to-primary-700 hover:from-primary-600 hover:to-primary-800 text-white font-semibold py-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-5 h-5" id="submitIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <span id="submitText">Bayar Sekarang</span>
                        </button>
                    </div>
                </form>
            </div>

            <script>
                document.getElementById('paymentForm').addEventListener('submit', function(e) {
                    const selectedPayment = document.querySelector('input[name="payment_channel_code"]:checked');
                    
                    if (!selectedPayment) {
                        e.preventDefault();
                        alert('Silakan pilih metode pembayaran terlebih dahulu!');
                        return false;
                    }
                    
                    // Show loading state
                    const btn = document.getElementById('submitBtn');
                    const btnText = document.getElementById('submitText');
                    const btnIcon = document.getElementById('submitIcon');
                    
                    btn.disabled = true;
                    btnIcon.classList.add('animate-spin');
                    btnText.textContent = 'Memproses...';
                });
            </script>

            <!-- Security Note -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500 flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Pembayaran aman dan terenkripsi melalui YUKK Payment Gateway</span>
                </p>
            </div>
        </div>
    </div>
</x-app-layout>

