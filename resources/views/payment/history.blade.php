<x-app-layout>
    <div class="payment-workspace workspace-page-frame">
        <header class="pay-heading"><div><a class="pay-back" href="{{ route('membership.index') }}" wire:navigate>← Membership & credits</a><h1>Payment history</h1><p>A clear record of your membership and credit purchases.</p></div><a class="pay-button pay-button--primary" href="{{ auth()->user()->isPremium() ? route('payment.topup') : route('payment.premium') }}" wire:navigate>{{ auth()->user()->isPremium() ? 'Get more credits' : 'Explore Premium' }} ↗</a></header>
        <div class="pay-history-stats">@foreach(['total' => 'All orders', 'success' => 'Paid', 'pending' => 'Awaiting payment', 'failed' => 'Unsuccessful'] as $key => $label)<div><span>{{ $label }}</span><strong>{{ number_format($stats[$key]) }}</strong></div>@endforeach</div>
        <section class="pay-panel pay-history-panel"><div class="pay-section-heading"><h2>Your orders</h2><span>{{ $payments->total() }} {{ $payments->total() === 1 ? 'order' : 'orders' }}</span></div>
            @if($payments->isEmpty())
                <div class="pay-empty"><span class="pay-tag">YOUR PAYMENT RECORD</span><h3>No payments yet</h3><p>Your Premium and credit purchases will appear here.</p><a class="pay-button" href="{{ route('membership.index') }}" wire:navigate>View membership & credits ↗</a></div>
            @else
                <div class="pay-table-scroll" tabindex="0" role="region" aria-label="Payment orders"><table class="pay-table"><caption class="sr-only">Your payment orders, amount, date and current status</caption><thead><tr><th scope="col">Purchase</th><th scope="col">Created</th><th scope="col">Amount</th><th scope="col">Status</th><th scope="col">Details</th></tr></thead><tbody>
                    @foreach($payments as $payment)
                        @php($state = \App\Support\PaymentPresentation::status($payment))
                        <tr><td><strong>{{ \App\Support\PaymentPresentation::package($payment) }}</strong><small>{{ $payment->order_id }}</small></td><td>{{ $payment->created_at->timezone('Asia/Jakarta')->format('d M Y') }}<small>{{ $payment->created_at->timezone('Asia/Jakarta')->format('H:i') }} WIB</small></td><td class="pay-amount">{{ $payment->formatted_amount }}</td><td><span class="pay-badge pay-badge--{{ strtolower($state) }}">{{ \App\Support\PaymentPresentation::label($state) }}</span></td><td><a class="pay-order-link" href="{{ route('payment.waiting', $payment->order_id) }}" wire:navigate aria-label="View order {{ $payment->order_id }}">View order ↗</a></td></tr>
                    @endforeach
                </tbody></table></div>
                @if($payments->hasPages())<div class="pay-pagination">{{ $payments->links() }}</div>@endif
            @endif
        </section>
        <footer class="pay-history-footer"><p>Order status is confirmed with the payment provider. Open an order to check a pending payment.</p><a class="pay-link" href="{{ route('support.index') }}" wire:navigate>Contact support ↗</a></footer>
    </div>
</x-app-layout>
