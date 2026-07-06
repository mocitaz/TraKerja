<x-admin-layout>
    <div class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-5 space-y-4 pb-10 text-left">
        
        <!-- Sticky Global Sub-Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3.5 border-b border-zinc-150/60">
            <div class="flex items-center gap-1.5 min-w-0">
                <span class="text-xs font-mono font-bold text-zinc-400 uppercase tracking-wider">Admin Portal</span>
                <span class="text-zinc-300 text-xs">/</span>
                <h1 class="text-xs font-mono font-bold text-zinc-800 uppercase tracking-wider">Payments Center</h1>
            </div>
            <div>
                <a href="{{ route('admin.payments.export', request()->all()) }}" 
                   class="inline-flex items-center justify-center h-8 px-3.5 bg-zinc-900 hover:bg-zinc-800 text-white rounded text-[10px] font-bold uppercase tracking-wide transition-colors focus:outline-none shadow-none">
                    <i class="ph ph-download-simple text-sm"></i>
                    Export CSV
                </a>
            </div>
        </div>

        {{-- Statistics Grid (Bento Grid) --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Total Payments --}}
            <div class="border border-zinc-200/60 rounded bg-white p-4 shadow-none flex flex-col justify-between h-[82px] hover:bg-[#f7f7f5]/40 transition-colors">
                <div class="flex items-center justify-between w-full">
                    <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Total Transaksi</span>
                    <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200/40 text-zinc-500 flex items-center justify-center shrink-0">
                        <i class="ph ph-shopping-cart text-xs"></i>
                    </div>
                </div>
                <div class="flex items-baseline justify-between mt-1">
                    <p class="text-xl font-bold tracking-tight text-zinc-900 leading-none">{{ number_format($stats['total']) }}</p>
                    <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wide leading-none">Orders</p>
                </div>
            </div>

            {{-- Successful --}}
            <div class="border border-zinc-200/60 rounded bg-white p-4 shadow-none flex flex-col justify-between h-[82px] hover:bg-[#f7f7f5]/40 transition-colors">
                <div class="flex items-center justify-between w-full">
                    <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Berhasil</span>
                    <div class="w-6 h-6 rounded bg-emerald-50 border border-emerald-100/45 text-emerald-650 flex items-center justify-center shrink-0">
                        <i class="ph ph-check-circle text-xs"></i>
                    </div>
                </div>
                <div class="flex items-baseline justify-between mt-1">
                    <p class="text-xl font-bold tracking-tight text-emerald-600 leading-none">{{ number_format($stats['success']) }}</p>
                    <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wide leading-none">Paid</p>
                </div>
            </div>

            {{-- Pending --}}
            <div class="border border-zinc-200/60 rounded bg-white p-4 shadow-none flex flex-col justify-between h-[82px] hover:bg-[#f7f7f5]/40 transition-colors">
                <div class="flex items-center justify-between w-full">
                    <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Pending</span>
                    <div class="w-6 h-6 rounded bg-amber-50 border border-amber-100/45 text-amber-650 flex items-center justify-center shrink-0">
                        <i class="ph ph-clock-counter-clockwise text-xs"></i>
                    </div>
                </div>
                <div class="flex items-baseline justify-between mt-1">
                    <p class="text-xl font-bold tracking-tight text-amber-600 leading-none">{{ number_format($stats['pending']) }}</p>
                    <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wide leading-none">Unpaid</p>
                </div>
            </div>

            {{-- Total Revenue --}}
            <div class="border border-zinc-200/60 rounded bg-white p-4 shadow-none flex flex-col justify-between h-[82px] hover:bg-[#f7f7f5]/40 transition-colors">
                <div class="flex items-center justify-between w-full">
                    <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Total Revenue</span>
                    <div class="w-6 h-6 rounded bg-purple-50 border border-purple-100/45 text-purple-650 flex items-center justify-center shrink-0">
                        <i class="ph ph-money text-xs"></i>
                    </div>
                </div>
                <div class="flex items-baseline justify-between mt-1">
                    <p class="text-xl font-bold tracking-tight text-zinc-900 leading-none">
                        @if($stats['total_revenue'] >= 1000000)
                            Rp {{ number_format($stats['total_revenue'] / 1000000, 1) }} Jt
                        @else
                            Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}
                        @endif
                    </p>
                    <p class="text-[9px] font-mono font-bold text-purple-600 uppercase tracking-wide leading-none">Revenue</p>
                </div>
            </div>
        </div>

        {{-- Bento Grid: Filter & Methods --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            
            {{-- Filter Section (2/3 width) --}}
            <div class="lg:col-span-2 bg-white rounded border border-zinc-200/60 p-4 shadow-none">
                <div class="flex items-center justify-between mb-3.5 pb-2 border-b border-zinc-150/60">
                    <h3 class="text-xs font-bold text-zinc-900 tracking-tight flex items-center gap-1.5">
                        <i class="ph ph-funnel text-zinc-400 text-sm"></i>
                        Filter Transaksi
                    </h3>
                    @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                        <a href="{{ route('admin.payments') }}" class="text-[9px] font-mono font-bold text-zinc-400 hover:text-zinc-700 transition-colors flex items-center gap-1 uppercase tracking-wider">
                            <i class="ph ph-x-circle text-xs"></i> Clear Filters
                        </a>
                    @endif
                </div>

                <form method="GET" action="{{ route('admin.payments') }}" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3.5">
                        {{-- Search --}}
                        <div class="col-span-1 sm:col-span-2 md:col-span-1">
                            <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide mb-1.5">Search</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-zinc-450 text-xs">
                                    <i class="ph ph-magnifying-glass"></i>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Order ID / User..." 
                                       class="w-full pl-8 pr-3 h-8 bg-white border border-zinc-250 rounded text-xs text-zinc-900 focus:outline-none focus:border-zinc-400 transition-colors">
                            </div>
                        </div>

                        {{-- Status Filter --}}
                        <div>
                            <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide mb-1.5">Status</label>
                            <div class="relative">
                                <select name="status" 
                                        class="w-full appearance-none bg-none pl-3 pr-8 h-8 bg-white border border-zinc-250 rounded text-xs text-zinc-900 focus:outline-none focus:border-zinc-400 transition-colors cursor-pointer">
                                    <option value="">Semua Status</option>
                                    <option value="SUCCESS" {{ request('status') === 'SUCCESS' ? 'selected' : '' }}>Success</option>
                                    <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>Pending</option>
                                    <option value="WAITING" {{ request('status') === 'WAITING' ? 'selected' : '' }}>Waiting</option>
                                    <option value="FAILED" {{ request('status') === 'FAILED' ? 'selected' : '' }}>Failed</option>
                                    <option value="CANCELED" {{ request('status') === 'CANCELED' ? 'selected' : '' }}>Canceled</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-2.5 pointer-events-none text-zinc-400">
                                    <i class="ph ph-caret-down text-[10px]"></i>
                                </div>
                            </div>
                        </div>

                        {{-- Date From --}}
                        <div>
                            <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide mb-1.5">Tanggal Awal</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                                   class="w-full px-2.5 h-8 bg-white border border-zinc-250 rounded text-xs text-zinc-900 focus:outline-none focus:border-zinc-400 transition-colors">
                        </div>

                        {{-- Date To --}}
                        <div>
                            <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide mb-1.5">Tanggal Akhir</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                                   class="w-full px-2.5 h-8 bg-white border border-zinc-250 rounded text-xs text-zinc-900 focus:outline-none focus:border-zinc-400 transition-colors">
                        </div>
                    </div>

                    <div class="flex justify-end pt-3 border-t border-zinc-150/60">
                        <button type="submit" 
                                class="w-full sm:w-auto h-8 px-4 bg-zinc-900 hover:bg-zinc-800 text-white rounded text-xs font-semibold transition-colors flex items-center justify-center gap-1.5 focus:outline-none shadow-none">
                            <i class="ph ph-funnel text-xs"></i> Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>

            {{-- Payment Methods (1/3 width) --}}
            <div class="lg:col-span-1 bg-white rounded border border-zinc-200/60 p-4 flex flex-col shadow-none">
                <h3 class="text-xs font-bold text-zinc-900 mb-4 tracking-tight flex items-center gap-1.5">
                    <i class="ph ph-chart-pie-slice text-zinc-400 text-sm"></i>
                    Distribusi Metode
                </h3>
                
                @if($paymentMethods->count() > 0)
                    <div class="grid grid-cols-2 gap-2.5 flex-1">
                        @foreach($paymentMethods->take(4) as $method)
                            <div class="p-2.5 bg-zinc-50 border border-zinc-200/80 rounded relative overflow-hidden group hover:bg-[#f7f7f5]/40 transition-colors">
                                <p class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wider truncate mb-0.5">{{ $method->payment_channel_code }}</p>
                                <p class="text-base font-semibold text-zinc-950 leading-tight">{{ $method->count }}</p>
                                <p class="text-[9px] font-mono font-bold text-zinc-500 mt-1 truncate">Rp {{ number_format($method->revenue, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex-1 flex flex-col items-center justify-center text-center p-4 border border-dashed border-zinc-200 rounded">
                        <div class="w-8 h-8 bg-zinc-50 rounded border border-zinc-200/50 flex items-center justify-center mb-2">
                            <i class="ph ph-wallet text-sm text-zinc-300"></i>
                        </div>
                        <p class="text-xs font-bold text-zinc-800 mb-0.5">Belum Ada Data</p>
                        <p class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide mt-0.5">Metode kosong</p>
                    </div>
                @endif
            </div>

        </div>

        {{-- Payments Data Table --}}
        <div class="bg-white rounded border border-zinc-200/60 overflow-hidden shadow-none mt-4">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-zinc-50/50 border-b border-zinc-150/60">
                            <th class="py-3 px-4 text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider w-[20%]">Order ID</th>
                            <th class="py-3 px-4 text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider w-[25%] hidden sm:table-cell">User Info</th>
                            <th class="py-3 px-4 text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider w-[15%]">Nominal</th>
                            <th class="py-3 px-4 text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider w-[10%] hidden md:table-cell">Metode</th>
                            <th class="py-3 px-4 text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider w-[10%]">Status</th>
                            <th class="py-3 px-4 text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider w-[15%] hidden lg:table-cell">Waktu</th>
                            <th class="py-3 px-4 text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider text-right w-[5%]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-150/30 text-xs text-zinc-800">
                        @forelse($payments as $payment)
                            <tr class="hover:bg-[#f7f7f5]/40 transition-colors group">
                                <td class="py-2.5 px-4 text-left">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded bg-zinc-50 border border-zinc-200/40 flex items-center justify-center shrink-0 text-zinc-550 group-hover:bg-zinc-900 group-hover:text-white transition-colors">
                                            <i class="ph ph-receipt text-xs"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-semibold text-zinc-950 truncate max-w-[120px] lg:max-w-none font-mono leading-none">{{ $payment->order_id }}</p>
                                            @if($payment->yukk_transaction_code)
                                                <p class="text-[9px] font-mono font-bold text-zinc-400 truncate max-w-[120px] lg:max-w-none mt-1 leading-none">{{ $payment->yukk_transaction_code }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2.5 px-4 hidden sm:table-cell text-left">
                                    <p class="text-xs font-semibold text-zinc-950 truncate max-w-[150px] leading-none">{{ $payment->user->name ?? 'N/A' }}</p>
                                    <p class="text-[10px] text-zinc-400 truncate max-w-[150px] font-mono mt-1 leading-none">{{ $payment->user->email ?? 'N/A' }}</p>
                                </td>
                                <td class="py-2.5 px-4 text-left">
                                    <p class="text-xs font-semibold text-zinc-950 leading-none">{{ $payment->formatted_amount }}</p>
                                    <p class="text-[10px] text-zinc-450 sm:hidden truncate max-w-[100px] mt-1 leading-none">{{ $payment->user->name ?? 'N/A' }}</p>
                                </td>
                                <td class="py-2.5 px-4 hidden md:table-cell text-left">
                                    <div class="inline-flex items-center px-1.5 py-0.5 bg-zinc-50 text-zinc-650 text-[8px] font-mono font-bold uppercase tracking-wider rounded border border-zinc-200/60 leading-none">
                                        {{ $payment->payment_method ?: 'N/A' }}
                                    </div>
                                </td>
                                <td class="py-2.5 px-4 text-left">
                                    @if($payment->status === 'SUCCESS')
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-mono font-bold bg-emerald-50 text-emerald-700 border border-emerald-150 uppercase tracking-wider">
                                            Success
                                        </span>
                                    @elseif(in_array($payment->status, ['PENDING', 'WAITING']))
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-mono font-bold bg-amber-50 text-amber-700 border border-amber-200 uppercase tracking-wider animate-pulse">
                                            Pending
                                        </span>
                                    @elseif(in_array($payment->status, ['FAILED', 'CANCELED', 'EXPIRED']))
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-mono font-bold bg-red-50 text-red-700 border border-red-200 uppercase tracking-wider">
                                            {{ $payment->status }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-mono font-bold bg-zinc-100 text-zinc-600 border border-zinc-200 uppercase tracking-wider">
                                            {{ $payment->status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-2.5 px-4 hidden lg:table-cell text-left">
                                    <p class="text-xs font-semibold text-zinc-700 leading-none">{{ $payment->created_at->format('d M Y') }}</p>
                                    <p class="text-[10px] text-zinc-400 mt-1 leading-none font-mono">{{ $payment->created_at->format('H:i') }} WIB</p>
                                </td>
                                <td class="py-2.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button type="button" onclick="showPaymentDetail('{{ $payment->id }}')" 
                                                class="w-7 h-7 rounded bg-white border border-zinc-250 text-zinc-500 flex items-center justify-center hover:bg-zinc-50 hover:text-zinc-800 transition-colors focus:outline-none shadow-none"
                                                title="View Detail">
                                            <i class="ph ph-eye"></i>
                                        </button>
                                        @if(in_array($payment->status, ['PENDING', 'WAITING']) && $payment->yukk_transaction_code)
                                            <button type="button" onclick="cancelPayment('{{ $payment->id }}', '{{ $payment->order_id }}')" 
                                                    class="w-7 h-7 rounded bg-white border border-zinc-250 text-zinc-500 flex items-center justify-center hover:bg-red-50 hover:text-red-650 hover:border-red-250 transition-colors focus:outline-none shadow-none"
                                                    title="Cancel Payment">
                                                <i class="ph ph-x"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-zinc-450">
                                    <i class="ph ph-receipt text-2xl text-zinc-300 mb-2 block mx-auto"></i>
                                    <h4 class="text-xs font-bold text-zinc-800 mb-0.5">Tidak ada transaksi</h4>
                                    <p class="text-[9px] text-zinc-400 max-w-sm mx-auto font-sans">Belum ada data pembayaran yang sesuai dengan filter yang dipilih.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($payments->hasPages())
                <div class="p-3 border-t border-zinc-150/60 notion-pagination">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>

    </div>

    {{-- Payment Detail Modal --}}
    <div id="paymentDetailModal" class="fixed inset-0 z-[100] overflow-y-auto hidden">
        <div class="fixed inset-0 bg-zinc-950/20 backdrop-blur-[2px] transition-opacity" onclick="closePaymentDetail()"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative w-full max-w-md bg-white rounded border border-zinc-200/60 transform transition-all shadow-none text-left" onclick="event.stopPropagation()">
                
                {{-- Modal Header --}}
                <div class="px-4 py-3 border-b border-zinc-150/60 flex items-center justify-between bg-zinc-50/20 rounded-t">
                    <div class="flex items-center gap-2">
                        <i class="ph ph-receipt text-zinc-400 text-sm"></i>
                        <div>
                            <h3 class="text-xs font-bold text-zinc-900 leading-none">Payment Details</h3>
                            <p class="text-[8px] font-mono font-bold text-zinc-400 mt-1 uppercase tracking-wide">Rincian Transaksi</p>
                        </div>
                    </div>
                    <button type="button" onclick="closePaymentDetail()" class="w-6 h-6 flex items-center justify-center rounded hover:bg-zinc-100 text-zinc-400 hover:text-zinc-800 transition-colors focus:outline-none shadow-none">
                        <i class="ph ph-x text-sm"></i>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div id="paymentDetailContent" class="p-4">
                    <div class="text-center py-8 text-zinc-400">
                        <i class="ph ph-spinner animate-spin text-lg mb-1 block mx-auto"></i>
                        <p class="text-[9px] font-mono font-bold uppercase tracking-wider">Memuat Rincian...</p>
                    </div>
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
            document.body.classList.add('overflow-hidden');
            
            content.innerHTML = `
                <div class="text-center py-8 text-zinc-400">
                    <i class="ph ph-spinner animate-spin text-lg mb-1 block mx-auto"></i>
                    <p class="text-[9px] font-mono font-bold uppercase tracking-wider">Memuat Rincian...</p>
                </div>
            `;

            fetch(`/admin/payments/${paymentId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const payment = data.payment;
                        const user = data.user;
                        
                        let statusBadge = '';
                        if(payment.status === 'SUCCESS') {
                            statusBadge = `<span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-mono font-bold bg-emerald-50 text-emerald-700 border border-emerald-150 uppercase tracking-wider">SUCCESS</span>`;
                        } else if(['PENDING', 'WAITING'].includes(payment.status)) {
                            statusBadge = `<span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-mono font-bold bg-amber-50 text-amber-700 border border-amber-200 uppercase tracking-wider animate-pulse">PENDING</span>`;
                        } else {
                            statusBadge = `<span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-mono font-bold bg-red-50 text-red-700 border border-red-200 uppercase tracking-wider">${payment.status}</span>`;
                        }

                        content.innerHTML = `
                            <div class="space-y-4">
                                <div class="bg-zinc-50 rounded border border-zinc-200/80 p-3.5 flex items-center justify-between">
                                    <div>
                                        <p class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Nominal Transaksi</p>
                                        <p class="text-lg font-bold text-zinc-950 mt-0.5">Rp ${parseInt(payment.amount).toLocaleString('id-ID')}</p>
                                    </div>
                                    <div class="text-right flex flex-col items-end">
                                        ${statusBadge}
                                        <p class="text-[10px] text-zinc-400 mt-1.5 font-mono">${new Date(payment.created_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' })}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                    <div class="p-3 bg-white rounded border border-zinc-200/80">
                                        <p class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide mb-0.5">Order ID</p>
                                        <p class="text-xs font-semibold text-zinc-900 font-mono">${payment.order_id}</p>
                                    </div>
                                    <div class="p-3 bg-white rounded border border-zinc-200/80">
                                        <p class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide mb-0.5">Payment Method</p>
                                        <p class="text-xs font-semibold text-zinc-900 uppercase font-mono">${payment.payment_method || payment.payment_channel_code || 'N/A'}</p>
                                    </div>
                                </div>

                                <div class="p-3 bg-white rounded border border-zinc-200/80">
                                    <p class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide mb-2">User Information</p>
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-full bg-zinc-50 border border-zinc-200/40 flex items-center justify-center text-zinc-450 text-xs shrink-0">
                                            <i class="ph ph-user"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-semibold text-zinc-955 truncate">${user ? user.name : 'Unknown User'}</p>
                                            <p class="text-[10px] text-zinc-400 truncate font-mono mt-0.5">${user ? user.email : 'No email'}</p>
                                        </div>
                                    </div>
                                </div>

                                ${(payment.va_number || payment.yukk_transaction_code) ? `
                                <div class="p-3 bg-white rounded border border-zinc-200/80">
                                    <p class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide mb-2">Payment Gateway Data</p>
                                    <div class="space-y-2">
                                        ${payment.va_number ? `
                                        <div>
                                            <p class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">VA Number</p>
                                            <p class="text-[10px] font-bold text-zinc-800 font-mono bg-zinc-50 px-1.5 py-0.5 rounded border border-zinc-200/60 inline-block mt-1">${payment.va_number}</p>
                                        </div>
                                        ` : ''}
                                        ${payment.yukk_transaction_code ? `
                                        <div>
                                            <p class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">YUKK Code</p>
                                            <p class="text-[10px] font-bold text-zinc-800 font-mono bg-zinc-50 px-1.5 py-0.5 rounded border border-zinc-200/60 inline-block mt-1">${payment.yukk_transaction_code}</p>
                                        </div>
                                        ` : ''}
                                    </div>
                                </div>
                                ` : ''}
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    content.innerHTML = `
                        <div class="text-center py-8 text-zinc-500">
                            <div class="w-8 h-8 bg-red-50 text-red-500 rounded border border-red-200 flex items-center justify-center mx-auto mb-2">
                                <i class="ph ph-warning-circle text-base"></i>
                            </div>
                            <p class="text-xs font-bold text-zinc-900">Gagal Memuat</p>
                            <p class="text-[10px] text-zinc-400 mt-0.5">Terjadi kesalahan sistem</p>
                        </div>
                    `;
                });
        }

        function closePaymentDetail() {
            document.getElementById('paymentDetailModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // Fix Escape key to close modal
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closePaymentDetail();
            }
        });

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
    </script>
    @endpush
</x-admin-layout>
