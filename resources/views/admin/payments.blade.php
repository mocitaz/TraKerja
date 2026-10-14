<x-admin-layout>
<div class="admin-operations admin-payments-workspace" x-data="adminPaymentsWorkspace()">

    {{-- ==========================================================================
         PAGE HEADER
         ========================================================================== --}}
    <section class="ap-welcome">
        <div>
            <h1>Payments & Billing</h1>
            <p>Review member transactions, gateway payment channels, status breakdown, and recorded revenue.</p>
        </div>
        <div class="ap-header-actions">
            <a class="ap-btn ap-btn--quiet" href="{{ route('admin.payments.export', request()->only(['search','status','date_from','date_to'])) }}">
                <i class="ph ph-download-simple" aria-hidden="true"></i>
                <span>Export CSV</span>
            </a>
        </div>
    </section>

    {{-- ==========================================================================
         OVERVIEW METRICS STRIP (Biru, Ungu, Orange)
         ========================================================================== --}}
    <section class="ap-metric-strip" aria-label="Payments summary metrics">
        {{-- Card 1: Biru - Total Transactions --}}
        <div class="ap-metric-card ap-metric-card--blue">
            <div class="ap-metric-head">
                <span class="ap-metric-label">
                    <span class="ap-metric-icon"><i class="ph ph-receipt" aria-hidden="true"></i></span>
                    Transactions
                </span>
            </div>
            <strong class="ap-metric-value">{{ number_format($stats['total']) }}</strong>
            <span class="ap-metric-sub">All recorded payments</span>
        </div>

        {{-- Card 2: Ungu - Successful Payments --}}
        <div class="ap-metric-card ap-metric-card--purple">
            <div class="ap-metric-head">
                <span class="ap-metric-label">
                    <span class="ap-metric-icon"><i class="ph ph-check-circle" aria-hidden="true"></i></span>
                    Successful
                </span>
            </div>
            <strong class="ap-metric-value">{{ number_format($stats['success']) }}</strong>
            <span class="ap-metric-sub">Completed payments</span>
        </div>

        {{-- Card 3: Orange - Pending & Waiting --}}
        <div class="ap-metric-card ap-metric-card--orange">
            <div class="ap-metric-head">
                <span class="ap-metric-label">
                    <span class="ap-metric-icon"><i class="ph ph-clock-countdown" aria-hidden="true"></i></span>
                    Pending & Waiting
                </span>
            </div>
            <strong class="ap-metric-value">{{ number_format($stats['pending']) }}</strong>
            <span class="ap-metric-sub">Awaiting member payment</span>
        </div>

        {{-- Card 4: Signature Ungu - Total Gross Revenue --}}
        <div class="ap-metric-card ap-metric-card--revenue">
            <div class="ap-metric-head">
                <span class="ap-metric-label">
                    <span class="ap-metric-icon"><i class="ph ph-wallet" aria-hidden="true"></i></span>
                    Gross Revenue
                </span>
            </div>
            <strong class="ap-metric-value">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</strong>
            <span class="ap-metric-sub">Successful transactions only</span>
        </div>
    </section>

    {{-- ==========================================================================
         FILTER TOOLBAR
         ========================================================================== --}}
    <form class="ap-filter-bar" method="GET" action="{{ route('admin.payments') }}">
        <div class="ap-search-wrap">
            <i class="ph ph-magnifying-glass" aria-hidden="true"></i>
            <input type="search" 
                   name="search" 
                   class="ap-search-input"
                   value="{{ request('search') }}" 
                   placeholder="Search Order ID, member name, or email…" 
                   maxlength="100">
        </div>

        <select name="status" class="ap-filter-select" aria-label="Filter by payment status">
            <option value="">All statuses</option>
            @foreach(['SUCCESS','PENDING','WAITING','FAILED','CANCELED','EXPIRED'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>
                    {{ ucfirst(strtolower($status)) }}
                </option>
            @endforeach
        </select>

        <div class="ap-date-group">
            <span class="ap-date-label">From</span>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="ap-date-input" aria-label="Filter from date">
        </div>

        <div class="ap-date-group">
            <span class="ap-date-label">To</span>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="ap-date-input" aria-label="Filter to date">
        </div>

        <button type="submit" class="ap-btn ap-btn--primary">
            <i class="ph ph-funnel" aria-hidden="true"></i>
            <span>Filter</span>
        </button>

        @if(request()->hasAny(['search','status','date_from','date_to']))
            <a class="ap-btn ap-btn--quiet" href="{{ route('admin.payments') }}">
                <i class="ph ph-x" aria-hidden="true"></i>
                <span>Clear</span>
            </a>
        @endif
    </form>

    {{-- ==========================================================================
         PAYMENTS TRANSACTIONS TABLE
         ========================================================================== --}}
    <section class="ap-panel" aria-labelledby="payments-table-heading">
        <div class="ap-panel-heading">
            <div>
                <h2 id="payments-table-heading">
                    Transactions Directory <span>{{ number_format($payments->total()) }}</span>
                </h2>
                <p>Real-time payment logs and payment gateway synchronization</p>
            </div>
        </div>

        <div class="ap-table-scroll" tabindex="0" aria-label="Transactions Directory Table">
            <table class="ap-table">
                <colgroup>
                    <col style="width: 24%;">
                    <col style="width: 24%;">
                    <col style="width: 16%;">
                    <col style="width: 14%;">
                    <col style="width: 14%;">
                    <col style="width: 8%;">
                </colgroup>
                <thead>
                    <tr>
                        <th scope="col">Order ID & Method</th>
                        <th scope="col">Member</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Status</th>
                        <th scope="col">Created</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            {{-- Order ID & Channel --}}
                            <td>
                                <strong class="ap-order-id">{{ $payment->order_id }}</strong>
                                <span class="ap-order-method">
                                    {{ $payment->payment_method ?: $payment->payment_channel_code ?: 'No method selected' }}
                                </span>
                            </td>

                            {{-- Member Info --}}
                            <td>
                                <div class="ap-member-cell">
                                    <strong class="ap-member-name">{{ $payment->user?->name ?? 'Deleted member' }}</strong>
                                    <span class="ap-member-email">{{ $payment->user?->email ?? 'No email on record' }}</span>
                                </div>
                            </td>

                            {{-- Amount --}}
                            <td>
                                <span class="ap-amount">{{ $payment->formatted_amount }}</span>
                            </td>

                            {{-- Status Badge (Biru/Ungu/Orange/Merah) --}}
                            <td>
                                @php
                                    $badgeClass = match($payment->status) {
                                        'SUCCESS' => 'ap-status--success',
                                        'PENDING', 'WAITING' => 'ap-status--pending',
                                        'FAILED', 'CANCELED', 'EXPIRED' => 'ap-status--danger',
                                        default => 'ap-status--neutral',
                                    };
                                @endphp
                                <span class="ap-status {{ $badgeClass }}">
                                    @if($payment->status === 'SUCCESS')
                                        <i class="ph-fill ph-check-circle" aria-hidden="true"></i>
                                    @elseif(in_array($payment->status, ['PENDING','WAITING']))
                                        <i class="ph-fill ph-clock" aria-hidden="true"></i>
                                    @else
                                        <i class="ph-fill ph-x-circle" aria-hidden="true"></i>
                                    @endif
                                    {{ ucfirst(strtolower($payment->status)) }}
                                </span>
                            </td>

                            {{-- Created Date --}}
                            <td>
                                <time class="ap-time" datetime="{{ $payment->created_at->toIso8601String() }}">
                                    {{ $payment->created_at->format('d M Y') }}
                                    <small>{{ $payment->created_at->format('H:i') }} WIB</small>
                                </time>
                            </td>

                            {{-- Manage / Review Modal --}}
                            <td>
                                <button type="button" 
                                        class="ap-btn ap-btn--quiet ap-btn--sm" 
                                        @click="view({{ $payment->id }})">
                                    Review
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="ap-empty">
                                    <i class="ph ph-receipt" aria-hidden="true"></i>
                                    <h3>{{ request()->hasAny(['search','status','date_from','date_to']) ? 'No matching payments' : 'No payments yet' }}</h3>
                                    <p>Member transactions will appear here. Try adjusting filters if you’re looking for a specific order.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Panel Footer & Pagination --}}
        <footer class="ap-panel-footer">
            <span>Showing {{ $payments->firstItem() ?? 0 }} to {{ $payments->lastItem() ?? 0 }} of {{ number_format($payments->total()) }} matching transactions</span>
            <div>
                {{ $payments->links() }}
            </div>
        </footer>
    </section>

    {{-- ==========================================================================
         PAYMENT METHODS DISTRIBUTION
         ========================================================================== --}}
    <section class="ap-panel" aria-labelledby="payment-methods-heading">
        <div class="ap-panel-heading">
            <div>
                <h2 id="payment-methods-heading">Payment Channels Distribution</h2>
                <p>Successful transactions grouped by payment gateway channel</p>
            </div>
        </div>
        <div class="ap-methods-grid">
            @forelse($paymentMethods as $method)
                <div class="ap-method-card">
                    <div class="ap-method-top">
                        <span class="ap-method-code">{{ $method->payment_channel_code ?: 'Unspecified channel' }}</span>
                        <span class="ap-method-count">{{ number_format($method->count) }} payments</span>
                    </div>
                    <span class="ap-method-revenue">Rp {{ number_format($method->revenue, 0, ',', '.') }}</span>
                </div>
            @empty
                <p class="text-xs text-gray-500 p-4">No successful transactions recorded yet.</p>
            @endforelse
        </div>
    </section>

    {{-- ==========================================================================
         ALPINE MODAL: REVIEW PAYMENT DETAILS
         ========================================================================== --}}
    <template x-teleport="body">
        <div x-show="open" 
             x-cloak 
             x-dialog="open" 
             data-dialog-close="!busy && (open = false)" 
             class="fixed inset-0 z-[100] flex items-center justify-center p-4" 
             role="dialog" 
             aria-modal="true" 
             aria-labelledby="payment-modal-title" 
             tabindex="-1">
            
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-zinc-950/40 backdrop-blur-sm transition-opacity" 
                 @click="!busy && (open=false)"></div>

            {{-- Modal Content Card --}}
            <section class="ap-modal-card relative max-w-lg max-h-[88dvh] overflow-y-auto">
                <header class="ap-modal-header">
                    <h2 id="payment-modal-title" x-text="confirming ? 'Cancel this payment?' : 'Transaction Details'"></h2>
                    <button type="button" 
                            class="ap-btn ap-btn--quiet ap-btn--sm" 
                            @click="open=false" 
                            :disabled="busy" 
                            aria-label="Close dialog">
                        <i class="ph ph-x" aria-hidden="true"></i>
                    </button>
                </header>

                <p x-show="loading" class="text-xs text-gray-500 py-4 flex items-center gap-2" role="status">
                    <i class="ph ph-spinner ph-spin text-base"></i> Loading transaction details…
                </p>

                <p x-show="error" x-text="error" class="p-3 mb-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs" role="alert"></p>

                <template x-if="payment && !loading">
                    <div>
                        {{-- Amount Highlight Card --}}
                        <div class="ap-modal-amount-box">
                            <div>
                                <span class="text-xs text-gray-500 block">Total Amount</span>
                                <strong x-text="money(payment.amount)"></strong>
                            </div>
                            <span class="ap-status" 
                                  :class="{
                                      'ap-status--success': payment.status === 'SUCCESS',
                                      'ap-status--pending': ['PENDING','WAITING'].includes(payment.status),
                                      'ap-status--danger': ['FAILED','CANCELED','EXPIRED'].includes(payment.status)
                                  }"
                                  x-text="payment.status"></span>
                        </div>

                        {{-- Details List --}}
                        <dl class="ap-modal-dl">
                            <div>
                                <dt>Order ID</dt>
                                <dd x-text="payment.order_id"></dd>
                            </div>
                            <div>
                                <dt>Member</dt>
                                <dd>
                                    <span x-text="user?.name || 'Deleted member'"></span>
                                    <small x-text="user?.email || '—'"></small>
                                </dd>
                            </div>
                            <div>
                                <dt>Payment Channel</dt>
                                <dd x-text="payment.payment_method || payment.payment_channel_code || 'Not selected'"></dd>
                            </div>
                            <div>
                                <dt>Created At</dt>
                                <dd x-text="date(payment.created_at)"></dd>
                            </div>
                            <div x-show="payment.paid_at">
                                <dt>Paid At</dt>
                                <dd x-text="date(payment.paid_at)"></dd>
                            </div>
                            <div x-show="payment.va_number">
                                <dt>Virtual Account</dt>
                                <dd x-text="payment.va_number"></dd>
                            </div>
                            <div x-show="payment.yukk_transaction_code">
                                <dt>Gateway Code</dt>
                                <dd x-text="payment.yukk_transaction_code"></dd>
                            </div>
                        </dl>

                        {{-- Cancellation Warning if Confirming --}}
                        <div x-show="confirming" class="ap-modal-notice">
                            <strong>Notice:</strong> This action will request transaction cancellation directly from the payment gateway. Verify order details before confirming.
                        </div>

                        {{-- Footer Actions --}}
                        <footer class="ap-modal-footer">
                            <button type="button" 
                                    class="ap-btn ap-btn--quiet" 
                                    @click="confirming ? confirming=false : open=false" 
                                    :disabled="busy" 
                                    x-text="confirming ? 'Keep payment' : 'Close'"></button>
                            
                            <template x-if="['PENDING','WAITING'].includes(payment.status) && payment.yukk_transaction_code">
                                <button type="button" 
                                        class="ap-btn ap-btn--danger" 
                                        @click="confirming ? cancel() : confirming=true" 
                                        :disabled="busy">
                                    <span x-show="!busy" x-text="confirming ? 'Confirm cancellation' : 'Cancel payment'"></span>
                                    <span x-show="busy" class="flex items-center gap-1.5"><i class="ph ph-spinner ph-spin"></i> Canceling…</span>
                                </button>
                            </template>
                        </footer>
                    </div>
                </template>
            </section>
        </div>
    </template>

</div>
</x-admin-layout>
