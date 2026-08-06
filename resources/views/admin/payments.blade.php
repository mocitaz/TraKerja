<x-admin-layout>
    <div class="space-y-6 pb-12 w-full text-left">
        
        <!-- Header Section (Sleek and Clean) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-zinc-200">
            <div>
                <h1 class="text-lg font-black text-zinc-900 tracking-tight flex items-center gap-1.5">
                    Payments Center <span class="text-[9px] font-mono font-bold text-zinc-450 bg-zinc-100 border border-zinc-200 px-1.5 py-0.2 rounded">Transactions Feed</span>
                </h1>
                <p class="text-[10px] text-zinc-500 font-semibold mt-0.5">Monitor transaction logs, search billing orders, and export revenue stats.</p>
            </div>
            <div>
                <a href="{{ route('admin.payments.export', request()->all()) }}" 
                   class="inline-flex items-center justify-center h-9 px-4 bg-zinc-900 hover:bg-zinc-800 text-white rounded-lg text-xs font-bold transition-colors shadow-3xs">
                    <i class="ph-bold ph-download-simple text-xs mr-1.5"></i>
                    Export CSV
                </a>
            </div>
        </div>

        {{-- Statistics Grid (Bento Grid) --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Total Payments --}}
            <div class="border border-zinc-200 rounded-xl bg-white p-4.5 shadow-3xs flex flex-col justify-between h-[82px] hover:border-zinc-300 transition-colors group">
                <div class="flex items-center justify-between w-full">
                    <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest">Total Transaksi</span>
                    <div class="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-950 group-hover:text-white transition duration-200 shrink-0 shadow-3xs">
                        <i class="ph-bold ph-shopping-cart text-xs"></i>
                    </div>
                </div>
                <div class="flex items-baseline justify-between mt-1">
                    <p class="text-xl font-extrabold tracking-tight text-zinc-900 leading-none font-mono">{{ number_format($stats['total']) }}</p>
                    <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wide leading-none">Orders</p>
                </div>
            </div>

            {{-- Successful --}}
            <div class="border border-zinc-200 rounded-xl bg-white p-4.5 shadow-3xs flex flex-col justify-between h-[82px] hover:border-zinc-300 transition-colors group">
                <div class="flex items-center justify-between w-full">
                    <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest">Berhasil</span>
                    <div class="w-7 h-7 rounded-lg bg-emerald-50 border border-emerald-250 text-emerald-650 flex items-center justify-center shrink-0 shadow-3xs">
                        <i class="ph-bold ph-check-circle text-xs"></i>
                    </div>
                </div>
                <div class="flex items-baseline justify-between mt-1">
                    <p class="text-xl font-extrabold tracking-tight text-emerald-600 leading-none font-mono">{{ number_format($stats['success']) }}</p>
                    <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wide leading-none">Paid</p>
                </div>
            </div>

            {{-- Pending --}}
            <div class="border border-zinc-200 rounded-xl bg-white p-4.5 shadow-3xs flex flex-col justify-between h-[82px] hover:border-zinc-300 transition-colors group">
                <div class="flex items-center justify-between w-full">
                    <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest">Pending</span>
                    <div class="w-7 h-7 rounded-lg bg-amber-50 border border-amber-200 text-amber-650 flex items-center justify-center shrink-0 shadow-3xs">
                        <i class="ph-bold ph-clock-counter-clockwise text-xs"></i>
                    </div>
                </div>
                <div class="flex items-baseline justify-between mt-1">
                    <p class="text-xl font-extrabold tracking-tight text-amber-600 leading-none font-mono">{{ number_format($stats['pending']) }}</p>
                    <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wide leading-none">Unpaid</p>
                </div>
            </div>

            {{-- Total Revenue --}}
            <div class="border border-zinc-200 rounded-xl bg-white p-4.5 shadow-3xs flex flex-col justify-between h-[82px] hover:border-zinc-300 transition-colors group">
                <div class="flex items-center justify-between w-full">
                    <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest">Total Revenue</span>
                    <div class="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-950 group-hover:text-white transition duration-200 shrink-0 shadow-3xs">
                        <i class="ph-bold ph-money text-xs"></i>
                    </div>
                </div>
                <div class="flex items-baseline justify-between mt-1">
                    <p class="text-xl font-extrabold tracking-tight text-zinc-900 leading-none font-mono">
                        @if($stats['total_revenue'] >= 1000000)
                            Rp {{ number_format($stats['total_revenue'] / 1000000, 1) }} Jt
                        @else
                            Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}
                        @endif
                    </p>
                    <p class="text-[9px] font-mono font-bold text-zinc-450 uppercase tracking-wide leading-none">Revenue</p>
                </div>
            </div>
        </div>

        {{-- Bento Grid: Filter & Methods --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            
            {{-- Filter Section (2/3 width) --}}
            <div class="lg:col-span-2 bg-[#f7f7f5]/40 rounded-xl border border-zinc-200 p-4.5 shadow-3xs">
                <div class="flex items-center justify-between mb-3 pb-2 border-b border-zinc-200">
                    <h3 class="text-xs font-bold text-zinc-900 tracking-tight flex items-center gap-1.5">
                        <i class="ph-bold ph-funnel text-zinc-450 text-sm"></i>
                        Filter Transaksi
                    </h3>
                    @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                        <a href="{{ route('admin.payments') }}" class="text-[9px] font-mono font-bold text-zinc-400 hover:text-zinc-700 transition-colors flex items-center gap-1 uppercase tracking-wider">
                            <i class="ph-bold ph-x-circle text-xs"></i> Clear Filters
                        </a>
                    @endif
                </div>

                <form method="GET" action="{{ route('admin.payments') }}" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3.5">
                        {{-- Search --}}
                        <div class="col-span-1 sm:col-span-2 md:col-span-1">
                            <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest mb-1.5">Search</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-450 text-xs">
                                    <i class="ph-bold ph-magnifying-glass"></i>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Order ID / User..." 
                                       class="w-full pl-9 pr-3 h-9 bg-white border border-zinc-200 rounded-lg text-xs font-semibold text-zinc-800 focus:outline-none focus:border-zinc-450 transition-colors shadow-3xs">
                            </div>
                        </div>

                        {{-- Status Filter --}}
                        <div>
                            <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest mb-1.5">Status</label>
                            <div class="relative">
                                <select name="status" 
                                        class="w-full appearance-none bg-none pl-3 pr-8 h-9 bg-white border border-zinc-200 rounded-lg text-xs font-semibold text-zinc-800 focus:outline-none focus:border-zinc-450 transition-colors cursor-pointer shadow-3xs">
                                    <option value="">Semua Status</option>
                                    <option value="SUCCESS" {{ request('status') === 'SUCCESS' ? 'selected' : '' }}>Success</option>
                                    <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>Pending</option>
                                    <option value="WAITING" {{ request('status') === 'WAITING' ? 'selected' : '' }}>Waiting</option>
                                    <option value="FAILED" {{ request('status') === 'FAILED' ? 'selected' : '' }}>Failed</option>
                                    <option value="CANCELED" {{ request('status') === 'CANCELED' ? 'selected' : '' }}>Canceled</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-2.5 pointer-events-none text-zinc-400">
                                    <i class="ph-bold ph-caret-down text-[10px]"></i>
                                </div>
                            </div>
                        </div>

                        {{-- Date From --}}
                        <div>
                            <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest mb-1.5">Tanggal Awal</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                                   class="w-full px-3 h-9 bg-white border border-zinc-200 rounded-lg text-xs font-semibold text-zinc-850 focus:outline-none focus:border-zinc-450 transition-colors shadow-3xs">
                        </div>

                        {{-- Date To --}}
                        <div>
                            <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest mb-1.5">Tanggal Akhir</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                                   class="w-full px-3 h-9 bg-white border border-zinc-200 rounded-lg text-xs font-semibold text-zinc-855 focus:outline-none focus:border-zinc-455 transition-colors shadow-3xs">
                        </div>
                    </div>

                    <div class="flex justify-end pt-3 border-t border-zinc-200">
                        <button type="submit" 
                                class="w-full sm:w-auto h-9 px-4 bg-zinc-900 hover:bg-zinc-800 text-white rounded-lg text-xs font-bold transition-colors flex items-center justify-center gap-1.5 focus:outline-none shadow-3xs">
                            <i class="ph-bold ph-funnel text-xs"></i> Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>

            {{-- Payment Methods (1/3 width) --}}
            <div class="lg:col-span-1 bg-white rounded-xl border border-zinc-200 p-4.5 flex flex-col shadow-3xs text-left">
                <h3 class="text-xs font-bold text-zinc-900 mb-4 tracking-tight flex items-center gap-1.5">
                    <i class="ph-bold ph-chart-pie-slice text-zinc-455 text-sm"></i>
                    Distribusi Metode
                </h3>
                
                @if($paymentMethods->count() > 0)
                    <div class="grid grid-cols-2 gap-2.5 flex-1">
                        @foreach($paymentMethods->take(4) as $method)
                            <div class="p-3 bg-zinc-50 border border-zinc-200 rounded-xl relative overflow-hidden group hover:bg-[#f7f7f5]/80 transition-all shadow-3xs">
                                <p class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wider truncate mb-0.5 font-bold">{{ $method->payment_channel_code }}</p>
                                <p class="text-base font-extrabold text-zinc-950 leading-tight font-mono">{{ $method->count }}</p>
                                <p class="text-[9px] font-mono font-bold text-zinc-500 mt-1 truncate">Rp {{ number_format($method->revenue, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex-1 flex flex-col items-center justify-center text-center p-4 border border-dashed border-zinc-200 rounded-xl">
                        <div class="w-8 h-8 bg-zinc-50 rounded-lg border border-zinc-200 flex items-center justify-center mb-2 shadow-3xs">
                            <i class="ph-bold ph-wallet text-sm text-zinc-400"></i>
                        </div>
                        <p class="text-xs font-bold text-zinc-800 mb-0.5">Belum Ada Data</p>
                        <p class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wider mt-0.5">Metode kosong</p>
                    </div>
                @endif
            </div>

        </div>

        {{-- Payments Data Table --}}
        <div class="bg-white rounded-xl border border-zinc-200 overflow-hidden shadow-3xs mt-4">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-zinc-50/50 border-b border-zinc-100">
                            <th class="py-3 px-4 text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider w-[20%]">Order ID</th>
                            <th class="py-3 px-4 text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider w-[25%] hidden sm:table-cell">User Info</th>
                            <th class="py-3 px-4 text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider w-[15%]">Nominal</th>
                            <th class="py-3 px-4 text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider w-[10%] hidden md:table-cell">Metode</th>
                            <th class="py-3 px-4 text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider w-[10%]">Status</th>
                            <th class="py-3 px-4 text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider w-[15%] hidden lg:table-cell">Waktu</th>
                            <th class="py-3 px-4 text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider text-right w-[5%]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 text-xs text-zinc-800">
                        @forelse($payments as $payment)
                            <tr class="hover:bg-[#f7f7f5]/80 transition-colors group text-xs text-zinc-700 font-medium">
                                <td class="py-2.5 px-4 text-left">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7.5 h-7.5 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center shrink-0 text-zinc-500 group-hover:bg-zinc-950 group-hover:text-white transition-colors shadow-3xs">
                                            <i class="ph-bold ph-receipt text-xs"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-zinc-955 truncate max-w-[120px] lg:max-w-none font-mono leading-none">{{ $payment->order_id }}</p>
                                            @if($payment->yukk_transaction_code)
                                                <p class="text-[9px] font-mono font-bold text-zinc-400 truncate max-w-[120px] lg:max-w-none mt-1 leading-none">{{ $payment->yukk_transaction_code }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2.5 px-4 hidden sm:table-cell text-left">
                                    <p class="text-xs font-bold text-zinc-955 truncate max-w-[150px] leading-none">{{ $payment->user->name ?? 'N/A' }}</p>
                                    <p class="text-[10px] text-zinc-400 truncate max-w-[150px] font-mono mt-1 leading-none">{{ $payment->user->email ?? 'N/A' }}</p>
                                </td>
                                <td class="py-2.5 px-4 text-left">
                                    <p class="text-xs font-bold text-zinc-955 leading-none">{{ $payment->formatted_amount }}</p>
                                    <p class="text-[10px] text-zinc-450 sm:hidden truncate max-w-[100px] mt-1 leading-none">{{ $payment->user->name ?? 'N/A' }}</p>
                                </td>
                                <td class="py-2.5 px-4 hidden md:table-cell text-left">
                                    <div class="inline-flex items-center px-1.5 py-0.5 bg-zinc-50 text-zinc-650 text-[8px] font-mono font-bold uppercase tracking-wider rounded-lg border border-zinc-200 leading-none">
                                        {{ $payment->payment_method ?: 'N/A' }}
                                    </div>
                                </td>
                                <td class="py-2.5 px-4 text-left">
                                    @if($payment->status === 'SUCCESS')
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-lg text-[8px] font-mono font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wider">
                                            Success
                                        </span>
                                    @elseif(in_array($payment->status, ['PENDING', 'WAITING']))
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-lg text-[8px] font-mono font-bold bg-amber-50 text-amber-700 border border-amber-200 uppercase tracking-wider animate-pulse">
                                            Pending
                                        </span>
                                    @elseif(in_array($payment->status, ['FAILED', 'CANCELED', 'EXPIRED']))
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-lg text-[8px] font-mono font-bold bg-red-50 text-red-700 border border-red-200 uppercase tracking-wider">
                                            {{ $payment->status }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-lg text-[8px] font-mono font-bold bg-zinc-100 text-zinc-600 border border-zinc-200 uppercase tracking-wider">
                                            {{ $payment->status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-2.5 px-4 hidden lg:table-cell text-left">
                                    <p class="text-xs font-bold text-zinc-700 leading-none">{{ $payment->created_at->format('d M Y') }}</p>
                                    <p class="text-[10px] text-zinc-450 mt-1 leading-none font-mono">{{ $payment->created_at->format('H:i') }} WIB</p>
                                </td>
                                <td class="py-2.5 px-4 text-right font-bold">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button type="button" onclick="showPaymentDetail('{{ $payment->id }}')" 
                                                class="w-7.5 h-7.5 rounded-lg bg-white border border-zinc-200 text-zinc-500 flex items-center justify-center hover:bg-zinc-50 hover:text-zinc-800 transition-colors focus:outline-none shadow-3xs"
                                                title="View Detail">
                                            <i class="ph-bold ph-eye text-xs"></i>
                                        </button>
                                        @if(in_array($payment->status, ['PENDING', 'WAITING']) && $payment->yukk_transaction_code)
                                            <button type="button" onclick="cancelPayment('{{ $payment->id }}', '{{ $payment->order_id }}')" 
                                                    class="w-7.5 h-7.5 rounded-lg bg-white border border-zinc-200 text-zinc-500 flex items-center justify-center hover:bg-red-50 hover:text-red-650 hover:border-red-200 transition-colors focus:outline-none shadow-3xs"
                                                    title="Cancel Payment">
                                                <i class="ph-bold ph-x text-xs"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-zinc-450">
                                    <i class="ph-bold ph-receipt text-2xl text-zinc-300 mb-2 block mx-auto animate-pulse"></i>
                                    <h4 class="text-xs font-bold text-zinc-800 mb-0.5">Tidak ada transaksi</h4>
                                    <p class="text-[10px] text-zinc-450 max-w-sm mx-auto font-medium">Belum ada data pembayaran yang sesuai dengan filter yang dipilih.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($payments->hasPages())
                <div class="p-3 border-t border-zinc-100 notion-pagination">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>

    </div>

    {{-- Payment Detail Modal --}}
    <div id="paymentDetailModal" class="fixed inset-0 z-[100] overflow-y-auto hidden">
        <div class="fixed inset-0 bg-zinc-950/20 backdrop-blur-[2px] transition-opacity" onclick="closePaymentDetail()"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative w-full max-w-md bg-white rounded-xl border border-zinc-200 transform transition-all shadow-3xs text-left" onclick="event.stopPropagation()">
                
                {{-- Modal Header --}}
                <div class="px-5 py-4 border-b border-zinc-100 flex items-center justify-between bg-zinc-50/20 rounded-t-xl shrink-0">
                    <div class="flex items-center gap-2">
                        <i class="ph-bold ph-receipt text-zinc-450 text-sm"></i>
                        <div>
                            <h3 class="text-xs font-bold text-zinc-900 leading-none">Payment Details</h3>
                            <p class="text-[8px] font-mono font-bold text-zinc-400 mt-1.5 uppercase tracking-widest">Rincian Transaksi</p>
                        </div>
                    </div>
                    <button type="button" onclick="closePaymentDetail()" class="w-6 h-6 flex items-center justify-center rounded hover:bg-zinc-100 text-zinc-400 hover:text-zinc-800 transition-colors focus:outline-none shadow-none">
                        <i class="ph-bold ph-x text-xs"></i>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div id="paymentDetailContent" class="p-5">
                    <div class="text-center py-8 text-zinc-400">
                        <i class="ph-bold ph-spinner animate-spin text-lg mb-1.5 block mx-auto"></i>
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
                    <i class="ph-bold ph-spinner animate-spin text-lg mb-1.5 block mx-auto"></i>
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
                            statusBadge = `<span class="inline-flex items-center px-1.5 py-0.5 rounded-lg text-[8px] font-mono font-bold bg-emerald-50 text-emerald-700 border border-emerald-150 uppercase tracking-wider">SUCCESS</span>`;
                        } else if(['PENDING', 'WAITING'].includes(payment.status)) {
                            statusBadge = `<span class="inline-flex items-center px-1.5 py-0.5 rounded-lg text-[8px] font-mono font-bold bg-amber-50 text-amber-700 border border-amber-200 uppercase tracking-wider animate-pulse">PENDING</span>`;
                        } else {
                            statusBadge = `<span class="inline-flex items-center px-1.5 py-0.5 rounded-lg text-[8px] font-mono font-bold bg-red-50 text-red-700 border border-red-200 uppercase tracking-wider">${payment.status}</span>`;
                        }

                        content.innerHTML = `
                            <div class="space-y-4">
                                <div class="bg-zinc-50 rounded-xl border border-zinc-200 p-3.5 flex items-center justify-between shadow-3xs">
                                    <div>
                                        <p class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Nominal Transaksi</p>
                                        <p class="text-lg font-extrabold text-zinc-950 mt-0.5 font-mono">Rp ${parseInt(payment.amount).toLocaleString('id-ID')}</p>
                                    </div>
                                    <div class="text-right flex flex-col items-end">
                                        ${statusBadge}
                                        <p class="text-[9px] text-zinc-400 mt-1.5 font-mono font-semibold">${new Date(payment.created_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' })}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                    <div class="p-3.5 bg-white rounded-xl border border-zinc-200 shadow-3xs">
                                        <p class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-0.5">Order ID</p>
                                        <p class="text-xs font-bold text-zinc-900 font-mono">${payment.order_id}</p>
                                    </div>
                                    <div class="p-3.5 bg-white rounded-xl border border-zinc-200 shadow-3xs">
                                        <p class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-0.5">Payment Method</p>
                                        <p class="text-xs font-bold text-zinc-900 uppercase font-mono">${payment.payment_method || payment.payment_channel_code || 'N/A'}</p>
                                    </div>
                                </div>

                                <div class="p-3.5 bg-white rounded-xl border border-zinc-200 shadow-3xs">
                                    <p class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-2">User Information</p>
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7.5 h-7.5 rounded-full bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500 text-xs shrink-0 shadow-3xs">
                                            <i class="ph-bold ph-user"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-zinc-955 truncate">${user ? user.name : 'Unknown User'}</p>
                                            <p class="text-[10px] text-zinc-400 truncate font-mono mt-0.5">${user ? user.email : 'No email'}</p>
                                        </div>
                                    </div>
                                </div>

                                ${(payment.va_number || payment.yukk_transaction_code) ? `
                                <div class="p-3.5 bg-white rounded-xl border border-zinc-200 shadow-3xs">
                                    <p class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-2">Payment Gateway Data</p>
                                    <div class="space-y-2">
                                        ${payment.va_number ? `
                                        <div>
                                            <p class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wider">VA Number</p>
                                            <p class="text-[10px] font-bold text-zinc-800 font-mono bg-zinc-50 px-1.5 py-0.5 rounded border border-zinc-200 inline-block mt-1">${payment.va_number}</p>
                                        </div>
                                        ` : ''}
                                        ${payment.yukk_transaction_code ? `
                                        <div>
                                            <p class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wider">YUKK Code</p>
                                            <p class="text-[10px] font-bold text-zinc-800 font-mono bg-zinc-50 px-1.5 py-0.5 rounded border border-zinc-200 inline-block mt-1">${payment.yukk_transaction_code}</p>
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
                            <div class="w-8 h-8 bg-red-50 text-red-500 rounded-lg border border-red-200 flex items-center justify-center mx-auto mb-2 shadow-3xs">
                                <i class="ph-bold ph-warning-circle text-base"></i>
                            </div>
                            <p class="text-xs font-bold text-zinc-900 font-sans">Gagal Memuat</p>
                            <p class="text-[10px] text-zinc-400 mt-0.5 font-semibold">Terjadi kesalahan sistem</p>
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
