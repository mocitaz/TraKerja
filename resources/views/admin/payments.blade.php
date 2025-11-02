<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-white/20 backdrop-blur-sm border border-white/30 flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg sm:text-2xl font-bold bg-gradient-to-r from-[#d983e4] to-[#4e71c5] bg-clip-text text-transparent">
                        Payment Monitoring
                    </h2>
                    <p class="text-xs sm:text-sm text-white/80 mt-0.5">Monitor dan kelola pembayaran pengguna</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.payments.export', request()->all()) }}" 
                   class="px-3 py-1.5 bg-white/20 backdrop-blur-sm rounded-lg border border-white/30 text-white text-xs sm:text-sm font-medium hover:bg-white/30 transition-all duration-200 flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    <span>Export CSV</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500 mb-1">Total Payments</p>
                            <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm border border-green-200 bg-green-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-green-600 mb-1">Successful</p>
                            <p class="text-2xl sm:text-3xl font-bold text-green-700">{{ number_format($stats['success']) }}</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm border border-yellow-200 bg-yellow-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-yellow-600 mb-1">Pending</p>
                            <p class="text-2xl sm:text-3xl font-bold text-yellow-700">{{ number_format($stats['pending']) }}</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500 mb-1">Total Revenue</p>
                            <p class="text-lg sm:text-xl font-bold text-gray-900">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500 mt-1">Today: Rp {{ number_format($stats['today_revenue'], 0, ',', '.') }}</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                <form method="GET" action="{{ route('admin.payments') }}" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Search</label>
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Order ID, User, Email..."
                                   class="w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                                <option value="">All Status</option>
                                <option value="SUCCESS" {{ request('status') === 'SUCCESS' ? 'selected' : '' }}>Success</option>
                                <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>Pending</option>
                                <option value="WAITING" {{ request('status') === 'WAITING' ? 'selected' : '' }}>Waiting</option>
                                <option value="FAILED" {{ request('status') === 'FAILED' ? 'selected' : '' }}>Failed</option>
                                <option value="CANCELED" {{ request('status') === 'CANCELED' ? 'selected' : '' }}>Canceled</option>
                            </select>
                        </div>

                        <!-- Date From -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">From Date</label>
                            <input type="date" 
                                   name="date_from" 
                                   value="{{ request('date_from') }}"
                                   class="w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>

                        <!-- Date To -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">To Date</label>
                            <input type="date" 
                                   name="date_to" 
                                   value="{{ request('date_to') }}"
                                   class="w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit" 
                                class="px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                            Apply Filters
                        </button>
                        @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                            <a href="{{ route('admin.payments') }}" 
                               class="text-sm text-gray-600 hover:text-gray-900">
                                Clear Filters
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Payment Methods Distribution -->
            @if($paymentMethods->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Payment Methods Distribution</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @foreach($paymentMethods as $method)
                        <div class="border border-gray-200 rounded-lg p-3">
                            <p class="text-xs text-gray-500 mb-1">{{ $method->payment_channel_code }}</p>
                            <p class="text-lg font-bold text-gray-900">{{ $method->count }}</p>
                            <p class="text-xs text-gray-500">Rp {{ number_format($method->revenue, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Payments Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($payments as $payment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $payment->order_id }}</div>
                                        @if($payment->yukk_transaction_code)
                                            <div class="text-xs text-gray-500">{{ $payment->yukk_transaction_code }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $payment->user->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">{{ $payment->user->email ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $payment->formatted_amount }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $payment->payment_method }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @if($payment->status === 'SUCCESS')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Success</span>
                                        @elseif(in_array($payment->status, ['PENDING', 'WAITING']))
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                        @elseif(in_array($payment->status, ['FAILED', 'CANCELED', 'EXPIRED']))
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">{{ $payment->status }}</span>
                                        @else
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ $payment->status }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                        <div>{{ $payment->created_at->format('d M Y') }}</div>
                                        <div class="text-xs">{{ $payment->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <button onclick="showPaymentDetail('{{ $payment->id }}')" 
                                                    class="text-primary-600 hover:text-primary-900 text-xs">
                                                View
                                            </button>
                                            @if(in_array($payment->status, ['PENDING', 'WAITING']) && $payment->yukk_transaction_code)
                                                <button onclick="cancelPayment('{{ $payment->id }}', '{{ $payment->order_id }}')" 
                                                        class="text-red-600 hover:text-red-900 text-xs">
                                                    Cancel
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500">
                                        No payments found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($payments->hasPages())
                    <div class="px-4 py-3 border-t border-gray-200">
                        {{ $payments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Payment Detail Modal -->
    <div id="paymentDetailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Payment Details</h3>
                <button onclick="closePaymentDetail()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="paymentDetailContent" class="p-6">
                <!-- Content loaded via AJAX -->
                <div class="text-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600 mx-auto"></div>
                    <p class="text-sm text-gray-500 mt-2">Loading...</p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function showPaymentDetail(paymentId) {
            const modal = document.getElementById('paymentDetailModal');
            const content = document.getElementById('paymentDetailContent');
            
            modal.classList.remove('hidden');
            content.innerHTML = `
                <div class="text-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600 mx-auto"></div>
                    <p class="text-sm text-gray-500 mt-2">Loading...</p>
                </div>
            `;

            fetch(`/admin/payments/${paymentId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const payment = data.payment;
                        const user = data.user;
                        
                        content.innerHTML = `
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-xs font-medium text-gray-500">Order ID</label>
                                        <p class="text-sm font-medium text-gray-900 mt-1">${payment.order_id}</p>
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-gray-500">Status</label>
                                        <p class="text-sm font-medium text-gray-900 mt-1">${payment.status}</p>
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-gray-500">Amount</label>
                                        <p class="text-sm font-medium text-gray-900 mt-1">Rp ${parseInt(payment.amount).toLocaleString('id-ID')}</p>
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-gray-500">Payment Method</label>
                                        <p class="text-sm font-medium text-gray-900 mt-1">${payment.payment_method || payment.payment_channel_code || 'N/A'}</p>
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-gray-500">User</label>
                                        <p class="text-sm font-medium text-gray-900 mt-1">${user ? user.name : 'N/A'}</p>
                                        <p class="text-xs text-gray-500">${user ? user.email : 'N/A'}</p>
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-gray-500">Created At</label>
                                        <p class="text-sm font-medium text-gray-900 mt-1">${new Date(payment.created_at).toLocaleString('id-ID')}</p>
                                    </div>
                                    ${payment.va_number ? `
                                    <div>
                                        <label class="text-xs font-medium text-gray-500">VA Number</label>
                                        <p class="text-sm font-medium text-gray-900 mt-1">${payment.va_number}</p>
                                    </div>
                                    ` : ''}
                                    ${payment.yukk_transaction_code ? `
                                    <div>
                                        <label class="text-xs font-medium text-gray-500">YUKK Transaction Code</label>
                                        <p class="text-sm font-medium text-gray-900 mt-1">${payment.yukk_transaction_code}</p>
                                    </div>
                                    ` : ''}
                                </div>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    content.innerHTML = `
                        <div class="text-center py-8">
                            <p class="text-sm text-red-600">Error loading payment details</p>
                        </div>
                    `;
                });
        }

        function closePaymentDetail() {
            document.getElementById('paymentDetailModal').classList.add('hidden');
        }

        function cancelPayment(paymentId, orderId) {
            if (!confirm(`Yakin ingin membatalkan payment dengan Order ID: ${orderId}?`)) {
                return;
            }

            fetch(`/admin/payments/${paymentId}/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Payment berhasil dibatalkan');
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Gagal membatalkan payment'));
                }
            })
            .catch(error => {
                alert('Error: ' + error.message);
            });
        }

        // Close modal when clicking outside
        document.getElementById('paymentDetailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePaymentDetail();
            }
        });
    </script>
    @endpush
</x-admin-layout>
