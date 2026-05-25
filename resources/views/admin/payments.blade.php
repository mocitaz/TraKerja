<x-admin-layout>
    <div class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-6 space-y-6 sm:space-y-8 pb-10">
        
        {{-- Header & Export --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 sm:mb-8">
            <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white border border-slate-200/60 rounded-[1.25rem] sm:rounded-[1.5rem] flex items-center justify-center text-primary-600 shadow-sm shrink-0">
                    <i class="ph-duotone ph-receipt text-xl sm:text-2xl"></i>
                </div>
                <div class="flex flex-col min-w-0">
                    <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight truncate">Payments Center</h3>
                    <p class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mt-1 sm:mt-1.5 truncate">Pantau seluruh transaksi</p>
                </div>
            </div>
            <div class="relative w-full sm:w-auto">
                <a href="{{ route('admin.payments.export', request()->all()) }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 bg-slate-800 text-white rounded-xl text-sm font-bold hover:bg-slate-900 transition-all shadow-sm active:scale-95 focus:ring-2 focus:ring-slate-400 focus:outline-none">
                    <i class="ph-bold ph-download-simple mr-2"></i>
                    <span class="hidden sm:inline">Export CSV</span>
                    <span class="sm:hidden">Export</span>
                </a>
            </div>
        </div>

        {{-- Statistics Grid (Bento Grid) --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            {{-- Total Payments --}}
            <div class="bg-white rounded-[2rem] p-6 border border-slate-200/60 bento-card-stat relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-blue-50 to-blue-100 rounded-full blur-2xl -z-10"></div>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Transaksi</p>
                        <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($stats['total']) }}</h3>
                        <p class="text-[9px] font-bold text-slate-400 mt-1">Keseluruhan order</p>
                    </div>
                    <div class="w-10 h-10 rounded-[1.25rem] bg-blue-50 flex items-center justify-center text-blue-600 shadow-sm border border-blue-100">
                        <i class="ph-duotone ph-shopping-cart text-xl"></i>
                    </div>
                </div>
            </div>

            {{-- Successful --}}
            <div class="bg-white rounded-[2rem] p-6 border border-slate-200/60 bento-card-stat relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-full blur-2xl -z-10"></div>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest mb-1">Berhasil</p>
                        <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($stats['success']) }}</h3>
                        <p class="text-[9px] font-bold text-emerald-500 mt-1">Pembayaran diterima</p>
                    </div>
                    <div class="w-10 h-10 rounded-[1.25rem] bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-sm border border-emerald-100">
                        <i class="ph-duotone ph-check-circle text-xl"></i>
                    </div>
                </div>
            </div>

            {{-- Pending --}}
            <div class="bg-white rounded-[2rem] p-6 border border-slate-200/60 bento-card-stat relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-amber-50 to-amber-100 rounded-full blur-2xl -z-10"></div>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-amber-400 uppercase tracking-widest mb-1">Pending</p>
                        <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($stats['pending']) }}</h3>
                        <p class="text-[9px] font-bold text-amber-500 mt-1">Menunggu pembayaran</p>
                    </div>
                    <div class="w-10 h-10 rounded-[1.25rem] bg-amber-50 flex items-center justify-center text-amber-600 shadow-sm border border-amber-100">
                        <i class="ph-duotone ph-clock-counter-clockwise text-xl"></i>
                    </div>
                </div>
            </div>

            {{-- Total Revenue --}}
            <div class="bg-white rounded-[2rem] p-6 border border-slate-200/60 bento-card-stat relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-purple-50 to-purple-100 rounded-full blur-2xl -z-10"></div>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Revenue</p>
                        <h3 class="text-xl lg:text-2xl font-black text-slate-900 tracking-tight mt-1">
                            @if($stats['total_revenue'] >= 1000000)
                                Rp {{ number_format($stats['total_revenue'] / 1000000, 1) }} Jt
                            @else
                                Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}
                            @endif
                        </h3>
                        <p class="text-[9px] font-bold text-purple-500 mt-1">Hari ini: Rp {{ number_format($stats['today_revenue'], 0, ',', '.') }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-[1.25rem] bg-purple-50 flex items-center justify-center text-purple-600 shadow-sm border border-purple-100">
                        <i class="ph-duotone ph-money text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bento Grid: Filter & Methods --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            
            {{-- Filter Section (2/3 width) --}}
            <div class="lg:col-span-2 bg-white rounded-[2rem] border border-slate-200/60 p-6 bento-card">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-sm font-black text-slate-900 tracking-tight">Filter Transaksi</h3>
                    @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                        <a href="{{ route('admin.payments') }}" class="text-[10px] font-bold text-slate-400 hover:text-slate-800 transition-colors flex items-center gap-1 uppercase tracking-widest">
                            <i class="ph-bold ph-x-circle text-sm"></i> Clear Filters
                        </a>
                    @endif
                </div>

                <form method="GET" action="{{ route('admin.payments') }}" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        {{-- Search --}}
                        <div class="col-span-1 sm:col-span-2 md:col-span-1">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Search</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <i class="ph-bold ph-magnifying-glass"></i>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Order ID / User..." class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-colors font-bold text-slate-700 shadow-sm">
                            </div>
                        </div>

                        {{-- Status Filter --}}
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Status</label>
                            <div class="relative group">
                                <select name="status" class="w-full appearance-none pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-colors font-bold text-slate-700 shadow-sm cursor-pointer">
                                    <option value="">Semua Status</option>
                                    <option value="SUCCESS" {{ request('status') === 'SUCCESS' ? 'selected' : '' }}>Success</option>
                                    <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>Pending</option>
                                    <option value="WAITING" {{ request('status') === 'WAITING' ? 'selected' : '' }}>Waiting</option>
                                    <option value="FAILED" {{ request('status') === 'FAILED' ? 'selected' : '' }}>Failed</option>
                                    <option value="CANCELED" {{ request('status') === 'CANCELED' ? 'selected' : '' }}>Canceled</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400 transition-colors">
                                    <i class="ph-bold ph-caret-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        {{-- Date From --}}
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Tanggal Awal</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-colors font-bold text-slate-700 shadow-sm">
                        </div>

                        {{-- Date To --}}
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Tanggal Akhir</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-colors font-bold text-slate-700 shadow-sm">
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-slate-900 text-white font-black text-xs rounded-xl hover:bg-slate-800 shadow-sm active:scale-95 transition-all flex items-center justify-center gap-2 uppercase tracking-widest">
                            <i class="ph-bold ph-funnel"></i> Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>

            {{-- Payment Methods (1/3 width) --}}
            <div class="lg:col-span-1 bg-white rounded-[2rem] border border-slate-200/60 p-6 flex flex-col bento-card">
                <h3 class="text-sm font-black text-slate-900 mb-5 tracking-tight">Distribusi Metode</h3>
                
                @if($paymentMethods->count() > 0)
                    <div class="grid grid-cols-2 gap-3 flex-1">
                        @foreach($paymentMethods->take(4) as $method)
                            <div class="p-3 bg-slate-50 border border-slate-200/60 rounded-[1rem] relative overflow-hidden group hover:bg-slate-100 transition-colors shadow-sm">
                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1 truncate">{{ $method->payment_channel_code }}</p>
                                <p class="text-lg font-black text-slate-900">{{ $method->count }}</p>
                                <p class="text-[10px] font-medium text-slate-400 mt-1 truncate">Rp {{ number_format($method->revenue, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex-1 flex flex-col items-center justify-center text-center p-6 border-2 border-dashed border-slate-200/60 rounded-[1rem]">
                        <div class="w-12 h-12 bg-slate-50 rounded-[1.25rem] flex items-center justify-center mb-3 border border-slate-100">
                            <i class="ph-duotone ph-wallet text-xl text-slate-400"></i>
                        </div>
                        <p class="text-sm font-black text-slate-600 mb-1">Belum Ada Data</p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Metode pembayaran kosong</p>
                    </div>
                @endif
            </div>

        </div>

        {{-- Payments Data Table --}}
        <div class="bg-white rounded-[2rem] border border-slate-200/60 overflow-hidden bento-card">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="py-4 px-5 text-[11px] font-bold text-slate-400 uppercase tracking-widest w-[20%]">Order ID</th>
                            <th class="py-4 px-5 text-[11px] font-bold text-slate-400 uppercase tracking-widest w-[25%] hidden sm:table-cell">User Info</th>
                            <th class="py-4 px-5 text-[11px] font-bold text-slate-400 uppercase tracking-widest w-[15%]">Nominal</th>
                            <th class="py-4 px-5 text-[11px] font-bold text-slate-400 uppercase tracking-widest w-[10%] hidden md:table-cell">Metode</th>
                            <th class="py-4 px-5 text-[11px] font-bold text-slate-400 uppercase tracking-widest w-[10%]">Status</th>
                            <th class="py-4 px-5 text-[11px] font-bold text-slate-400 uppercase tracking-widest w-[15%] hidden lg:table-cell">Waktu</th>
                            <th class="py-4 px-5 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right w-[5%]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($payments as $payment)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="py-3 px-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0 text-slate-500 group-hover:bg-primary-50 group-hover:text-primary-600 transition-colors">
                                            <i class="ph-bold ph-receipt text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-900 truncate max-w-[120px] lg:max-w-none">{{ $payment->order_id }}</p>
                                            @if($payment->yukk_transaction_code)
                                                <p class="text-[10px] font-medium text-slate-400 truncate max-w-[120px] lg:max-w-none">{{ $payment->yukk_transaction_code }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-5 hidden sm:table-cell">
                                    <p class="text-sm font-bold text-slate-900 truncate max-w-[150px]">{{ $payment->user->name ?? 'N/A' }}</p>
                                    <p class="text-[11px] text-slate-500 truncate max-w-[150px]">{{ $payment->user->email ?? 'N/A' }}</p>
                                </td>
                                <td class="py-3 px-5">
                                    <p class="text-sm font-bold text-slate-900">{{ $payment->formatted_amount }}</p>
                                    <p class="text-[10px] text-slate-400 sm:hidden truncate max-w-[100px] mt-0.5">{{ $payment->user->name ?? 'N/A' }}</p>
                                </td>
                                <td class="py-3 px-5 hidden md:table-cell">
                                    <div class="inline-flex items-center px-2 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-wider rounded-md">
                                        {{ $payment->payment_method ?: 'N/A' }}
                                    </div>
                                </td>
                                <td class="py-3 px-5">
                                    @if($payment->status === 'SUCCESS')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-widest">
                                            Success
                                        </span>
                                    @elseif(in_array($payment->status, ['PENDING', 'WAITING']))
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-100 uppercase tracking-widest">
                                            Pending
                                        </span>
                                    @elseif(in_array($payment->status, ['FAILED', 'CANCELED', 'EXPIRED']))
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-red-50 text-red-600 border border-red-100 uppercase tracking-widest">
                                            {{ $payment->status }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200 uppercase tracking-widest">
                                            {{ $payment->status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-5 hidden lg:table-cell">
                                    <p class="text-[13px] font-medium text-slate-700">{{ $payment->created_at->format('d M Y') }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $payment->created_at->format('H:i') }}</p>
                                </td>
                                <td class="py-3 px-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button onclick="showPaymentDetail('{{ $payment->id }}')" 
                                                class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-primary-50 hover:text-primary-600 hover:border-primary-100 transition-colors shadow-sm"
                                                title="View Detail">
                                            <i class="ph-bold ph-eye"></i>
                                        </button>
                                        @if(in_array($payment->status, ['PENDING', 'WAITING']) && $payment->yukk_transaction_code)
                                            <button onclick="cancelPayment('{{ $payment->id }}', '{{ $payment->order_id }}')" 
                                                    class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-red-50 hover:text-red-600 hover:border-red-100 transition-colors shadow-sm"
                                                    title="Cancel Payment">
                                                <i class="ph-bold ph-x"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-16 text-center">
                                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                        <i class="ph-duotone ph-receipt text-3xl text-slate-400"></i>
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-900 mb-1">Tidak ada transaksi</h4>
                                    <p class="text-xs text-slate-500 max-w-sm mx-auto">Belum ada data pembayaran yang sesuai dengan filter yang dipilih.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($payments->hasPages())
                <div class="p-5 border-t border-slate-100 bg-slate-50/30">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>

    </div>

    {{-- Payment Detail Modal (Premium UI) --}}
    <div id="paymentDetailModal" class="fixed inset-0 z-[100] overflow-y-auto hidden">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closePaymentDetail()"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative w-full max-w-2xl bg-white rounded-[2rem] shadow-[0_12px_40px_rgba(0,0,0,0.12)] border border-slate-200/60 transform transition-all" onclick="event.stopPropagation()">
                {{-- Modal Header --}}
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 rounded-t-[2rem]">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary-50 rounded-[1.25rem] border border-primary-100 flex items-center justify-center text-primary-600 shadow-sm">
                            <i class="ph-bold ph-receipt text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900">Payment Details</h3>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Rincian Transaksi</p>
                        </div>
                    </div>
                    <button type="button" onclick="closePaymentDetail()" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                        <i class="ph-bold ph-x text-lg"></i>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div id="paymentDetailContent" class="p-6">
                    <!-- Content loaded via AJAX -->
                    <div class="text-center py-12">
                        <div class="inline-block animate-spin w-8 h-8 border-4 border-slate-200 border-t-primary-600 rounded-full mb-3"></div>
                        <p class="text-sm font-bold text-slate-500">Memuat Rincian...</p>
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
                <div class="text-center py-12">
                    <div class="inline-block animate-spin w-8 h-8 border-4 border-slate-200 border-t-primary-600 rounded-full mb-3"></div>
                    <p class="text-sm font-bold text-slate-500">Memuat Rincian...</p>
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
                            statusBadge = `<span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-widest">SUCCESS</span>`;
                        } else if(['PENDING', 'WAITING'].includes(payment.status)) {
                            statusBadge = `<span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-100 uppercase tracking-widest">PENDING</span>`;
                        } else {
                            statusBadge = `<span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-red-50 text-red-600 border border-red-100 uppercase tracking-widest">${payment.status}</span>`;
                        }

                        content.innerHTML = `
                            <div class="space-y-6">
                                <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 flex items-center justify-between">
                                    <div>
                                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nominal Transaksi</p>
                                        <p class="text-2xl font-black text-slate-900">Rp ${parseInt(payment.amount).toLocaleString('id-ID')}</p>
                                    </div>
                                    <div class="text-right">
                                        ${statusBadge}
                                        <p class="text-[11px] font-medium text-slate-400 mt-2">${new Date(payment.created_at).toLocaleString('id-ID', { dateStyle: 'long', timeStyle: 'short' })}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="p-4 rounded-xl border border-slate-100 bg-white shadow-sm">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Order ID</p>
                                        <p class="text-sm font-bold text-slate-900">${payment.order_id}</p>
                                    </div>
                                    <div class="p-4 rounded-xl border border-slate-100 bg-white shadow-sm">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Payment Method</p>
                                        <p class="text-sm font-bold text-slate-900">${payment.payment_method || payment.payment_channel_code || 'N/A'}</p>
                                    </div>
                                </div>

                                <div class="p-4 rounded-xl border border-slate-100 bg-white shadow-sm">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">User Information</p>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                            <i class="ph-bold ph-user"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-900">${user ? user.name : 'Unknown User'}</p>
                                            <p class="text-xs text-slate-500">${user ? user.email : 'No email'}</p>
                                        </div>
                                    </div>
                                </div>

                                ${(payment.va_number || payment.yukk_transaction_code) ? `
                                <div class="p-4 rounded-xl border border-slate-100 bg-white shadow-sm">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Payment Gateway Data</p>
                                    <div class="grid grid-cols-1 gap-3">
                                        ${payment.va_number ? `
                                        <div>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">VA Number</p>
                                            <p class="text-sm font-bold text-slate-900 font-mono bg-slate-50 px-2 py-1 rounded inline-block">${payment.va_number}</p>
                                        </div>
                                        ` : ''}
                                        ${payment.yukk_transaction_code ? `
                                        <div>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">YUKK Code</p>
                                            <p class="text-sm font-bold text-slate-900 font-mono bg-slate-50 px-2 py-1 rounded inline-block">${payment.yukk_transaction_code}</p>
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
                        <div class="text-center py-12">
                            <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-red-500 mx-auto mb-3">
                                <i class="ph-bold ph-warning-circle text-2xl"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-900">Gagal Memuat</p>
                            <p class="text-xs text-slate-500 mt-1">Terjadi kesalahan sistem</p>
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
