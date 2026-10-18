<x-app-layout>
    <div class="payment-workspace workspace-page-frame">
        <header class="pay-heading"><div><a class="pay-back" href="{{ route('membership.index') }}" wire:navigate>{{ __('← Membership & credits') }}</a><h1>{{ __('Payment history') }}</h1><p>{{ __('A clear record of your membership and credit purchases.') }}</p></div><a class="pay-button pay-button--primary" href="{{ auth()->user()->isPremium() ? route('payment.topup') : route('payment.premium') }}" wire:navigate>{{ auth()->user()->isPremium() ? __('Get more credits') : __('Explore Premium') }} ↗</a></header>
        <div class="pay-history-stats">@foreach(['total' => 'All orders', 'success' => 'Paid', 'pending' => 'Awaiting payment', 'failed' => 'Unsuccessful'] as $key => $label)<div><span><i class="ph {{ ['total' => 'ph-receipt', 'success' => 'ph-check-circle', 'pending' => 'ph-clock', 'failed' => 'ph-minus-circle'][$key] }}" aria-hidden="true"></i>{{ __($label) }}</span><strong>{{ number_format($stats[$key]) }}</strong></div>@endforeach</div>
        <section class="pay-panel pay-history-panel"><div class="pay-section-heading"><h2>{{ __('Your orders') }}</h2><span>{{ __(':count orders', ['count' => number_format($payments->total())]) }}</span></div>
            @if($payments->isEmpty())
                <div class="pay-empty"><span class="pay-empty-mark" aria-hidden="true"><i class="ph ph-receipt"></i></span><h3>{{ __('No payments yet') }}</h3><p>{{ __('Your Premium and credit purchases will appear here.') }}</p><a class="pay-button" href="{{ route('membership.index') }}" wire:navigate>{{ __('View membership & credits ↗') }}</a></div>
            @else
                <div class="pay-table-scroll" tabindex="0" role="region" aria-label="{{ __('Payment orders') }}"><table class="pay-table"><caption class="sr-only">{{ __('Your payment orders, amount, date and current status') }}</caption><thead><tr><th scope="col">{{ __('Package') }}</th><th scope="col">{{ __('Created') }}</th><th scope="col">{{ __('Amount') }}</th><th scope="col">{{ __('Status') }}</th><th scope="col">{{ __('Details') }}</th></tr></thead><tbody>
                    @foreach($payments as $payment)
                        @php($state = \App\Support\PaymentPresentation::status($payment))
                        <tr><td data-label="{{ __('Package') }}"><strong>{{ __(\App\Support\PaymentPresentation::package($payment)) }}</strong><small>{{ $payment->order_id }}</small></td><td data-label="{{ __('Created') }}">{{ $payment->created_at->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M Y') }}<small>{{ __(':value1 WIB', ['value1' => ($payment->created_at->timezone('Asia/Jakarta')->format('H:i'))]) }}</small></td><td class="pay-amount" data-label="{{ __('Amount') }}">{{ $payment->formatted_amount }}</td><td data-label="{{ __('Status') }}"><span class="pay-badge pay-badge--{{ strtolower($state) }}">{{ __(\App\Support\PaymentPresentation::label($state)) }}</span></td><td data-label="{{ __('Details') }}"><a class="pay-order-link" href="{{ route('payment.waiting', $payment->order_id) }}" wire:navigate aria-label="{{ __('View order :value1', ['value1' => ($payment->order_id)]) }}">{{ __('View order ↗') }}</a></td></tr>
                    @endforeach
                </tbody></table></div>
                @if($payments->hasPages())<div class="pay-pagination">{{ $payments->links() }}</div>@endif
            @endif
        </section>
        <footer class="pay-history-footer"><p>{{ __('Order status is confirmed with the payment provider. Open an order to check a pending payment.') }}</p><a class="pay-link" href="{{ route('support.index') }}" wire:navigate>{{ __('Contact support ↗') }}</a></footer>
    </div>
</x-app-layout>
