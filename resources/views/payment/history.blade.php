<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg border border-white/30">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg sm:text-2xl font-bold bg-gradient-to-r from-[#d983e4] to-[#4e71c5] bg-clip-text text-transparent">
                            Payment History
                        </h2>
                        <p class="text-xs sm:text-sm text-white/80 mt-0.5">Riwayat pembayaran Anda</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('payment.index') }}" 
                   class="px-3 py-1.5 bg-white/20 backdrop-blur-sm rounded-lg border border-white/30 text-white text-xs sm:text-sm font-medium hover:bg-white/30 transition-all duration-200">
                    Upgrade Premium
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl p-4 shadow-sm">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
                    <div class="text-xs text-gray-500 mt-1">Total Payments</div>
                </div>
                <div class="bg-green-50 rounded-xl p-4 shadow-sm border border-green-100">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['success'] }}</div>
                    <div class="text-xs text-green-600 mt-1">Successful</div>
                </div>
                <div class="bg-yellow-50 rounded-xl p-4 shadow-sm border border-yellow-100">
                    <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
                    <div class="text-xs text-yellow-600 mt-1">Pending</div>
                </div>
                <div class="bg-red-50 rounded-xl p-4 shadow-sm border border-red-100">
                    <div class="text-2xl font-bold text-red-600">{{ $stats['failed'] }}</div>
                    <div class="text-xs text-red-600 mt-1">Failed</div>
                </div>
            </div>

            <!-- Payment List -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Riwayat Pembayaran</h3>
                </div>

                @if($payments->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($payments as $payment)
                            <div class="p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h4 class="font-semibold text-gray-900">{{ $payment->payment_method }}</h4>
                                            @if($payment->status === 'SUCCESS')
                                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Berhasil</span>
                                            @elseif(in_array($payment->status, ['PENDING', 'WAITING']))
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">Menunggu</span>
                                            @elseif(in_array($payment->status, ['FAILED', 'CANCELED']))
                                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">{{ $payment->status === 'CANCELED' ? 'Dibatalkan' : 'Gagal' }}</span>
                                            @else
                                                <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">{{ $payment->status }}</span>
                                            @endif
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-sm text-gray-600">
                                            <div>
                                                <span class="font-medium">Order ID:</span> {{ $payment->order_id }}
                                            </div>
                                            <div>
                                                <span class="font-medium">Jumlah:</span> {{ $payment->formatted_amount }}
                                            </div>
                                            <div>
                                                <span class="font-medium">Tanggal:</span> {{ $payment->created_at->format('d M Y, H:i') }}
                                            </div>
                                        </div>
                                        @if($payment->va_number)
                                            <div class="mt-2 text-sm text-gray-600">
                                                <span class="font-medium">VA Number:</span> {{ $payment->va_number }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-2 ml-4">
                                        @if($payment->status === 'WAITING' || $payment->status === 'PENDING')
                                            <a href="{{ route('payment.waiting', $payment->order_id) }}" 
                                               class="px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                                                Lanjutkan
                                            </a>
                                        @elseif($payment->status === 'SUCCESS')
                                            <a href="{{ route('payment.success', $payment->order_id) }}" 
                                               class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                                Lihat Detail
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="p-6 border-t border-gray-200">
                        {{ $payments->links() }}
                    </div>
                @else
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada riwayat pembayaran</h3>
                        <p class="text-gray-500 mb-6">Mulai upgrade ke Premium untuk mengaktifkan semua fitur!</p>
                        <a href="{{ route('payment.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary-500 to-primary-700 text-white font-semibold rounded-lg hover:from-primary-600 hover:to-primary-800 transition-all duration-200">
                            Upgrade Premium Sekarang
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
