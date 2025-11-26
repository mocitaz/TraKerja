<x-admin-layout>
    <div class="min-h-screen w-full bg-slate-100 px-4 py-6 sm:px-6 lg:px-8">
        <div class="space-y-6">
            <section class="rounded-2xl border border-slate-200 bg-white px-6 py-6 shadow-sm">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Payments Control Room</p>
                        <h1 class="text-2xl font-semibold text-slate-900">Payment Monitoring</h1>
                        <p class="text-sm text-slate-500">Pantau mutasi pembayaran, filter riwayat, dan kirim export secara langsung.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('admin.payments.export', request()->all()) }}"
                           class="inline-flex items-center rounded-full bg-gradient-to-r from-purple-100 via-purple-100 to-purple-200 px-4 py-2 text-sm font-semibold text-purple-900 shadow-lg shadow-purple-200/60 transition hover:-translate-y-0.5 focus:outline-none focus-visible:ring focus-visible:ring-purple-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Export CSV
                        </a>
                    </div>
                </div>

                <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <article class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 shadow-sm">
                        <p class="text-[10px] uppercase tracking-[0.3em] text-slate-400">Total Payments</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($stats['total']) }}</p>
                        <p class="text-[11px] text-slate-500">Semua transaksi</p>
                    </article>
                    <article class="rounded-2xl border border-slate-100 bg-white px-4 py-3 shadow-sm">
                        <p class="text-[10px] uppercase tracking-[0.3em] text-slate-400">Successful</p>
                        <p class="mt-2 text-2xl font-bold text-green-700">{{ number_format($stats['success']) }}</p>
                        <p class="text-[11px] text-slate-500">Transaksi sukses</p>
                    </article>
                    <article class="rounded-2xl border border-slate-100 bg-white px-4 py-3 shadow-sm">
                        <p class="text-[10px] uppercase tracking-[0.3em] text-slate-400">Pending</p>
                        <p class="mt-2 text-2xl font-bold text-yellow-700">{{ number_format($stats['pending']) }}</p>
                        <p class="text-[11px] text-slate-500">Menunggu konfirmasi</p>
                    </article>
                    <article class="rounded-2xl border border-slate-100 bg-white px-4 py-3 shadow-sm">
                        <p class="text-[10px] uppercase tracking-[0.3em] text-slate-400">Total Revenue</p>
                        <p class="mt-2 text-xl font-bold text-slate-900">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                        <p class="text-[11px] text-slate-500">Today: Rp {{ number_format($stats['today_revenue'], 0, ',', '.') }}</p>
                    </article>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white px-6 py-5 shadow-sm">
                <form method="GET" action="{{ route('admin.payments') }}" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1">Search</label>
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Order ID, User, Email..."
                                   class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1">Status</label>
                            <select name="status" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300 transition">
                                <option value="">All Status</option>
                                <option value="SUCCESS" {{ request('status') === 'SUCCESS' ? 'selected' : '' }}>Success</option>
                                <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>Pending</option>
                                <option value="WAITING" {{ request('status') === 'WAITING' ? 'selected' : '' }}>Waiting</option>
                                <option value="FAILED" {{ request('status') === 'FAILED' ? 'selected' : '' }}>Failed</option>
                                <option value="CANCELED" {{ request('status') === 'CANCELED' ? 'selected' : '' }}>Canceled</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1">From Date</label>
                            <input type="date"
                                   name="date_from"
                                   value="{{ request('date_from') }}"
                                   class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1">To Date</label>
                            <input type="date"
                                   name="date_to"
                                   value="{{ request('date_to') }}"
                                   class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300 transition">
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <button type="submit" class="rounded-full bg-purple-100 px-4 py-2 text-sm font-semibold text-purple-800 shadow transition hover:bg-purple-200">Apply Filters</button>
                        @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                            <a href="{{ route('admin.payments') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900">Clear Filters</a>
                        @endif
                    </div>
                </form>
            </section>

            @if($paymentMethods->count() > 0)
                <section class="rounded-2xl border border-slate-200 bg-white px-6 py-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-slate-900">Payment Methods Distribution</h3>
                        <span class="text-xs uppercase tracking-[0.4em] text-slate-400">Insight per channel</span>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
                        @foreach($paymentMethods as $method)
                            <article class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-sm space-y-1">
                                <p class="text-[10px] uppercase tracking-[0.3em] text-slate-400">{{ $method->payment_channel_code }}</p>
                                <p class="text-xl font-bold text-slate-900">{{ $method->count }}</p>
                                <p class="text-xs text-slate-600">Rp {{ number_format($method->revenue, 0, ',', '.') }}</p>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif

            <section class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Order ID</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">User</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Method</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse($payments as $payment)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-slate-900">{{ $payment->order_id }}</div>
                                        @if($payment->yukk_transaction_code)
                                            <div class="text-xs text-slate-500">{{ $payment->yukk_transaction_code }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm text-slate-900">{{ $payment->user->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-slate-500">{{ $payment->user->email ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm font-medium text-slate-900">{{ $payment->formatted_amount }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm text-slate-900">{{ $payment->payment_method }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @php
                                            $statusMap = [
                                                'SUCCESS' => 'bg-green-100 text-green-800',
                                                'PENDING' => 'bg-yellow-100 text-yellow-800',
                                                'WAITING' => 'bg-yellow-100 text-yellow-800',
                                                'FAILED' => 'bg-red-100 text-red-800',
                                                'CANCELED' => 'bg-red-100 text-red-800',
                                                'EXPIRED' => 'bg-red-100 text-red-800',
                                            ];
                                        @endphp
                                        <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full {{ $statusMap[$payment->status] ?? 'bg-slate-100 text-slate-700' }}">
                                            {{ ucfirst(strtolower($payment->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-500">
                                        <div>{{ $payment->created_at->format('d M Y') }}</div>
                                        <div class="text-xs">{{ $payment->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <button onclick="showPaymentDetail('{{ $payment->id }}')"
                                                    class="text-slate-600 hover:text-slate-900 text-xs font-semibold">
                                                View
                                            </button>
                                            @if(in_array($payment->status, ['PENDING', 'WAITING']) && $payment->yukk_transaction_code)
                                                <button onclick="cancelPayment('{{ $payment->id }}', '{{ $payment->order_id }}')"
                                                        class="text-red-600 hover:text-red-900 text-xs font-semibold">
                                                    Cancel
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-sm text-slate-500">
                                        No payments found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($payments->hasPages())
                    <div class="px-4 py-3 border-t border-slate-200">
                        {{ $payments->links() }}
                    </div>
                @endif
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white px-6 py-5 shadow-sm">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-4 py-3">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Total Payments</p>
                        <p class="text-xl font-bold text-slate-900">{{ number_format($stats['total']) }}</p>
                        <p class="text-xs text-slate-500">Semua status</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Total Revenue</p>
                        <p class="text-xl font-bold text-slate-900">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                        <p class="text-xs text-slate-500">Hari ini: Rp {{ number_format($stats['today_revenue'], 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Conversion</p>
                        <p class="text-xl font-bold text-slate-900">{{ $stats['total'] > 0 ? number_format(($stats['success'] / $stats['total']) * 100, 2) : 0 }}%</p>
                        <p class="text-xs text-slate-500">Success rate</p>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Payment Detail Modal -->
    <div id="paymentDetailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">
        <div class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white p-6 shadow-xl">
            <div class="flex items-center justify-between border-b border-slate-200 pb-4">
                <h3 class="text-lg font-semibold text-slate-900">Payment Details</h3>
                <button onclick="closePaymentDetail()" class="text-slate-400 hover:text-slate-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="paymentDetailContent" class="mt-6">
                <div class="text-center py-8">
                    <div class="mx-auto h-8 w-8 animate-spin rounded-full border-b-2 border-primary-600"></div>
                    <p class="mt-3 text-sm text-slate-500">Loading...</p>
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
                        <div class="mx-auto h-8 w-8 animate-spin rounded-full border-b-2 border-primary-600"></div>
                        <p class="mt-3 text-sm text-slate-500">Loading...</p>
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
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        <div>
                                            <p class="text-xs font-semibold uppercase text-slate-400">Order ID</p>
                                            <p class="text-sm font-semibold text-slate-900 mt-1">${payment.order_id}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold uppercase text-slate-400">Status</p>
                                            <p class="text-sm font-semibold text-slate-900 mt-1">${payment.status}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold uppercase text-slate-400">Amount</p>
                                            <p class="text-sm font-semibold text-slate-900 mt-1">Rp ${parseInt(payment.amount).toLocaleString('id-ID')}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold uppercase text-slate-400">Method</p>
                                            <p class="text-sm font-semibold text-slate-900 mt-1">${payment.payment_method || payment.payment_channel_code || 'N/A'}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold uppercase text-slate-400">User</p>
                                            <p class="text-sm font-semibold text-slate-900 mt-1">${user ? user.name : 'N/A'}</p>
                                            <p class="text-xs text-slate-500">${user ? user.email : 'N/A'}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold uppercase text-slate-400">Created At</p>
                                            <p class="text-sm font-semibold text-slate-900 mt-1">${new Date(payment.created_at).toLocaleString('id-ID')}</p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        ${payment.va_number ? `
                                            <div>
                                                <p class="text-xs font-semibold uppercase text-slate-400">VA Number</p>
                                                <p class="text-sm font-semibold text-slate-900 mt-1">${payment.va_number}</p>
                                            </div>
                                        ` : ''}
                                        ${payment.yukk_transaction_code ? `
                                            <div>
                                                <p class="text-xs font-semibold uppercase text-slate-400">YUKK Transaction Code</p>
                                                <p class="text-sm font-semibold text-slate-900 mt-1">${payment.yukk_transaction_code}</p>
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

            document.getElementById('paymentDetailModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closePaymentDetail();
                }
            });
        </script>
    @endpush
</x-admin-layout>
