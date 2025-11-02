<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3 sm:space-x-4">
            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg border border-white/30">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-lg sm:text-2xl font-bold bg-gradient-to-r from-[#d983e4] to-[#4e71c5] bg-clip-text text-transparent">
                    Menunggu Pembayaran
                </h2>
                <p class="text-xs sm:text-sm text-white/80 mt-0.5">Silakan selesaikan pembayaran Anda</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Waiting Card -->
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <!-- Waiting Animation -->
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6 relative">
                        <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="absolute inset-0 border-4 border-yellow-400 rounded-full animate-ping opacity-75"></div>
                    </div>

                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Menunggu Pembayaran</h3>
                    <p class="text-gray-600 mb-2">Silakan selesaikan pembayaran Anda untuk melanjutkan.</p>
                    
                    <!-- Auto-refresh status -->
                    <div class="inline-flex items-center space-x-2 text-sm text-gray-500 bg-gray-100 px-3 py-1.5 rounded-full">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span>Status akan diperbarui otomatis</span>
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="bg-gray-50 rounded-xl p-6 mb-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Detail Pembayaran</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Order ID:</span>
                            <span class="font-medium text-gray-900">{{ $payment->order_id }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Metode Pembayaran:</span>
                            <span class="font-medium text-gray-900">{{ $payment->payment_method }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Jumlah:</span>
                            <span class="font-medium text-gray-900 text-lg">{{ $payment->formatted_amount }}</span>
                        </div>
                        <div class="flex justify-between text-sm items-center">
                            <span class="text-gray-600">Status:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800" id="paymentStatus">
                                <svg class="w-3 h-3 mr-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Menunggu
                            </span>
                        </div>
                        
                        @if($payment->va_number)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="bg-white rounded-lg p-4 border-2 border-primary-300">
                                <p class="text-xs text-gray-600 mb-1">Nomor Virtual Account:</p>
                                <div class="flex items-center justify-between">
                                    <p class="text-xl font-bold text-gray-900 tracking-wider" id="vaNumber">{{ $payment->va_number }}</p>
                                    <button onclick="copyVA()" class="text-primary-600 hover:text-primary-700 p-2 hover:bg-primary-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @if($payment->va_expired_at)
                            <p class="text-xs text-red-600 mt-2 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                Berlaku hingga: {{ $payment->va_expired_at->format('d M Y, H:i') }} WIB
                            </p>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Instructions -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Cara Pembayaran
                    </h4>
                    <ol class="space-y-2 text-sm text-gray-700 list-decimal list-inside">
                        @if($payment->va_number)
                            <li>Buka aplikasi mobile banking atau ATM Anda</li>
                            <li>Pilih menu Transfer atau Pembayaran</li>
                            <li>Masukkan nomor Virtual Account: <span class="font-semibold">{{ $payment->va_number }}</span></li>
                            <li>Masukkan jumlah: <span class="font-semibold">{{ $payment->formatted_amount }}</span></li>
                            <li>Konfirmasi dan selesaikan pembayaran</li>
                            <li>Halaman ini akan otomatis diperbarui setelah pembayaran berhasil</li>
                        @else
                            <li>Selesaikan pembayaran melalui halaman pembayaran yang telah dibuka</li>
                            <li>Ikuti instruksi yang diberikan pada halaman tersebut</li>
                            <li>Halaman ini akan otomatis diperbarui setelah pembayaran berhasil</li>
                        @endif
                    </ol>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    @if($payment->redirect_url)
                    <a href="{{ $payment->redirect_url }}" 
                       target="_blank"
                       class="flex-1 bg-gradient-to-r from-primary-500 to-primary-700 hover:from-primary-600 hover:to-primary-800 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        <span>Bayar Sekarang</span>
                    </a>
                    @endif
                    
                    <button onclick="checkStatus()" 
                            id="checkStatusBtn"
                            class="flex-1 border-2 border-primary-500 text-primary-600 hover:bg-primary-50 font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span>Cek Status</span>
                    </button>
                </div>

                <!-- Back Link -->
                <div class="mt-6 text-center">
                    <a href="{{ route('tracker') }}" class="text-sm text-gray-500 hover:text-gray-700">
                        ← Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-check payment status every 10 seconds
        let statusCheckInterval;
        
        function checkStatus() {
            const btn = document.getElementById('checkStatusBtn');
            const btnText = btn.querySelector('span');
            const btnIcon = btn.querySelector('svg');
            
            // Disable button and show loading
            btn.disabled = true;
            btnIcon.classList.add('animate-spin');
            btnText.textContent = 'Memeriksa...';
            
            fetch('{{ route("payment.check-status", $payment->order_id) }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const status = data.status;
                        
                        // Redirect based on status
                        if (status === 'SUCCESS') {
                            window.location.href = '{{ route("payment.success", $payment->order_id) }}';
                        } else if (status === 'FAILED' || status === 'CANCELED') {
                            window.location.href = '{{ route("payment.failed", $payment->order_id) }}';
                        } else {
                            // Still waiting, update status badge
                            updateStatusBadge(status);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error checking status:', error);
                })
                .finally(() => {
                    // Re-enable button
                    btn.disabled = false;
                    btnIcon.classList.remove('animate-spin');
                    btnText.textContent = 'Cek Status';
                });
        }
        
        function updateStatusBadge(status) {
            const badge = document.getElementById('paymentStatus');
            let badgeClass = 'bg-yellow-100 text-yellow-800';
            let badgeText = 'Menunggu';
            
            if (status === 'SUCCESS') {
                badgeClass = 'bg-green-100 text-green-800';
                badgeText = 'Berhasil';
            } else if (status === 'FAILED') {
                badgeClass = 'bg-red-100 text-red-800';
                badgeText = 'Gagal';
            } else if (status === 'CANCELED') {
                badgeClass = 'bg-gray-100 text-gray-800';
                badgeText = 'Dibatalkan';
            }
            
            badge.className = `inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${badgeClass}`;
            badge.innerHTML = `
                <svg class="w-3 h-3 mr-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                ${badgeText}
            `;
        }
        
        function copyVA() {
            const vaNumber = document.getElementById('vaNumber').textContent;
            navigator.clipboard.writeText(vaNumber).then(() => {
                // Show success message
                alert('Nomor VA berhasil disalin!');
            }).catch(err => {
                console.error('Failed to copy:', err);
            });
        }
        
        // Start auto-checking when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Check immediately
            setTimeout(checkStatus, 2000);
            
            // Then check every 10 seconds
            statusCheckInterval = setInterval(checkStatus, 10000);
        });
        
        // Clear interval when leaving page
        window.addEventListener('beforeunload', function() {
            if (statusCheckInterval) {
                clearInterval(statusCheckInterval);
            }
        });
    </script>
    @endpush
</x-app-layout>

