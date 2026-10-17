@php
    use App\Support\PaymentPresentation;
    $state = PaymentPresentation::status($payment);
    $pending = in_array($state, ['PENDING', 'WAITING'], true);
    $paid = $state === 'SUCCESS';
    $resumeUrl = PaymentPresentation::resumeUrl($payment);
    $expiry = $payment->expired_at ?? $payment->va_expired_at;
    $grants = $paid ? \App\Models\PaymentGrant::where('payment_id', $payment->id)->where('benefit', '!=', 'legacy_settlement')->get() : collect();
    $title = match($state) { 'SUCCESS' => 'Payment received', 'FAILED' => 'Payment unsuccessful', 'CANCELED' => 'Payment canceled', 'EXPIRED' => 'Payment expired', 'PENDING', 'WAITING' => 'Awaiting payment', default => 'Payment status unavailable' };
    $description = match($state) {
        'SUCCESS' => 'Your payment has been verified. View your membership and credit balances below.',
        'FAILED' => 'This order is marked as failed. Review its details before starting another payment.',
        'CANCELED' => 'This order has been canceled. You can start a new checkout when you’re ready.',
        'EXPIRED' => 'The payment window for this order has ended. Start a new checkout to continue.',
        'PENDING', 'WAITING' => 'Complete your payment on Pakasir. We’ll check for confirmation here.',
        default => 'We can’t display a confirmed status for this order. Contact support with the order number.',
    };
@endphp
<div class="payment-workspace workspace-page-frame" @if($pending) x-data="paymentWaitingStatus({
    statusUrl: @js(route('payment.check-status', $payment->order_id)),
    successUrl: @js(route('payment.success', $payment->order_id)),
    failedUrl: @js(route('payment.failed', $payment->order_id)),
    initialStatus: @js($payment->status)
})" @endif>
    <header class="pay-heading"><div><a class="pay-back" href="{{ route('payment.history') }}" wire:navigate>{{ __('← Payment history') }}</a><h1>{{ __('Payment details') }}</h1><p>{{ __('Your order, its status and what happens next.') }}</p></div><a class="pay-link" href="{{ route('membership.index') }}" wire:navigate>{{ __('Membership & credits ↗') }}</a></header>
    <div class="pay-status-grid">
        <section class="pay-panel pay-state pay-state--{{ strtolower($state) }}">
            <span class="pay-state-icon" aria-hidden="true"><i class="ph {{ $paid ? 'ph-check' : ($pending ? 'ph-hourglass-medium' : 'ph-minus') }}"></i></span>
            <span class="pay-tag">{{ PaymentPresentation::label($state) }}</span><h2>{{ $title }}</h2><p>{{ $description }}</p>
            <ol class="pay-timeline" aria-label="{{ __('Payment progress') }}">
                <li class="is-done"><span aria-hidden="true">✓</span><div><strong>{{ __('Order created') }}</strong><small>{{ __(':value1 WIB', ['value1' => ($payment->created_at->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M Y · H:i'))]) }}</small></div></li>
                <li class="{{ $paid ? 'is-done' : '' }}"><span aria-hidden="true">{{ $paid ? '✓' : '2' }}</span><div><strong>{{ $paid ? __('Provider verified') : ($pending ? __('Waiting for provider confirmation') : PaymentPresentation::label($state)) }}</strong><small>{{ $payment->paid_at && $paid ? $payment->paid_at->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M Y · H:i').__(' WIB') : ($pending ? __('Checked automatically while this page is open') : __('Recorded order status')) }}</small></div></li>
                <li class="{{ $paid ? 'is-done' : '' }}"><span aria-hidden="true">{{ $paid ? '✓' : '3' }}</span><div><strong>{{ $paid ? __('View your benefits') : __('Membership or credits') }}</strong><small>{{ $paid ? __('Your current balances are in your membership portal') : __('Available after successful verification') }}</small></div></li>
            </ol>
            @if($pending)
                <div class="pay-notice pay-notice--error" x-cloak x-show="error"><p role="alert" x-text="error"></p><p>{{ __('A problem checking status does not mean your payment failed.') }}</p></div>
                <div class="pay-check-status" role="status" aria-live="polite"><span class="pay-live-dot" aria-hidden="true"></span><span x-text="busy ? @js(__('Checking payment…')) : (error ? @js(__('Status check paused. Try again below.')) : @js(__('Checking automatically')))">{{ __('Checking automatically') }}</span></div>
            @endif
            <div class="pay-actions">
                @if($paid)<a class="pay-button pay-button--primary" href="{{ route('membership.index') }}" wire:navigate>{{ __('View membership & credits ↗') }}</a>
                @elseif($pending)
                    @if($resumeUrl)<a class="pay-button pay-button--primary" href="{{ $resumeUrl }}" target="_blank" rel="noopener noreferrer">{{ __('Resume payment ↗') }}</a>@endif
                    <button class="pay-button" type="button" id="checkStatusBtn" @click="poll()" :disabled="busy || stopped"><span x-text="busy ? @js(__('Checking…')) : (error ? @js(__('Try again')) : @js(__('Check status')))">{{ __('Try again') }}</span></button>
                @else<a class="pay-button pay-button--primary" href="{{ auth()->user()->isPremium() ? route('payment.topup') : route('payment.premium') }}" wire:navigate>{{ __('Start a new checkout ↗') }}</a>@endif
            </div>
        </section>
        <aside class="pay-panel pay-receipt"><div class="pay-receipt-heading"><h2>{{ __('Order summary') }}</h2><span class="pay-badge pay-badge--{{ strtolower($state) }}">{{ PaymentPresentation::label($state) }}</span></div>
            <h3>{{ PaymentPresentation::package($payment) }}</h3><div class="pay-receipt-total">{{ $payment->formatted_amount }}</div>
            <dl class="pay-facts"><div><dt>{{ __('Order number') }}</dt><dd class="pay-mono">{{ $payment->order_id }}</dd></div><div><dt>{{ __('Payment method') }}</dt><dd>{{ PaymentPresentation::method($payment) }}</dd></div><div><dt>{{ __('Account') }}</dt><dd>{{ $payment->customer_email }}</dd></div>
                @if($payment->paid_at && $paid)<div><dt>{{ __('Paid on') }}</dt><dd>{{ __(':value1 WIB', ['value1' => ($payment->paid_at->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M Y, H:i'))]) }}</dd></div>@endif
                @if($expiry && !$paid)<div><dt>{{ __('Payment deadline') }}</dt><dd>{{ __(':value1 WIB', ['value1' => ($expiry->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M Y, H:i'))]) }}</dd></div>@endif
            </dl>
            @if($pending && $payment->va_number)<div class="pay-va"><span>{{ __('Virtual account number') }}</span><div><strong>{{ $payment->va_number }}</strong><button class="pay-button" type="button" @click="copyVA(@js($payment->va_number))" aria-label="{{ __('Copy virtual account number') }}">{{ __('Copy') }}</button></div><p x-cloak x-show="copyMessage" x-text="copyMessage" role="status"></p></div>@endif
            @if($grants->isNotEmpty())<div class="pay-granted"><h4>{{ __('Recorded benefits') }}</h4><ul>@foreach($grants as $grant)<li><span aria-hidden="true">✓</span>{{ PaymentPresentation::benefit($grant->benefit, $grant->quantity) }}</li>@endforeach</ul></div>@endif
            <div class="pay-receipt-help"><strong>{{ __('Need a hand?') }}</strong><p>{{ __('Include your order number when contacting support.') }}</p><a class="pay-link" href="{{ route('support.index') }}" wire:navigate>{{ __('Contact support ↗') }}</a></div>
        </aside>
    </div>
</div>
