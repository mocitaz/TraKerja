@php
    $durationLabel = match(true) {
        $premiumDuration >= 3650 => __('10+ years'),
        $premiumDuration === 365 => __('1 year'),
        $premiumDuration === 30 => __('1 month'),
        default => __(':count days', ['count'=>$premiumDuration]),
    };
    $planLabel = $active ? 'Premium active' : ($expired ? 'Premium expired' : 'Free account');
@endphp
<div class="membership-page profile-membership">
    @if($pendingPayment ?? null)
        <aside class="profile-panel profile-billing-pending">
            <div><strong>{{ __('A payment is waiting for you') }}</strong><p>{{ __('Rp :value1 · :value2', ['value1' => (number_format($pendingPayment->amount, 0, ',', '.')), 'value2' => ($pendingPayment->order_id)]) }}</p></div>
            <a class="profile-secondary" href="{{ route('payment.waiting', $pendingPayment->order_id) }}" wire:navigate>{{ __('Continue payment') }}</a>
        </aside>
    @endif

    <section class="profile-panel" aria-labelledby="membership-plan-title">
        <header class="profile-section-header">
            <div><h2 id="membership-plan-title">{{ __('Current plan') }}</h2><p>{{ __('Manage your membership and access.') }}</p></div>
            <span class="profile-billing-status">{{ __($planLabel) }}</span>
        </header>
        <dl class="profile-billing-details">
            <div><dt>{{ __('Plan') }}</dt><dd>{{ $active ? __('TraKerja Premium') : __('TraKerja Free') }}</dd></div>
            @if($active)
                <div><dt>{{ __('Valid until') }}</dt><dd>{{ $user->premium_until ? $user->premium_until->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M Y') : __('No expiry recorded') }}</dd></div>
                @if($user->premium_purchased_at)<div><dt>{{ __('Purchased on') }}</dt><dd>{{ \Carbon\Carbon::parse($user->premium_purchased_at)->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M Y') }}</dd></div>@endif
            @elseif($monetizationEnabled)
                <div><dt>{{ __('Premium membership') }}</dt><dd>{{ __('Rp :value1 / :value2', ['value1' => (number_format($premiumPrice, 0, ',', '.')), 'value2' => ($durationLabel)]) }}</dd></div>
            @endif
        </dl>
        @unless($monetizationEnabled)
            <div class="profile-billing-note"><strong>{{ __('Free access is enabled') }}</strong><p>{{ __('Premium features are available without a paid plan. Your stored credits are not deducted.') }}</p></div>
        @endunless
        @if($monetizationEnabled)
            <div class="profile-form-footer">
                <span>{{ $active ? __('Your Premium access is ready to use.') : ($expired ? __('Renew to restore your Premium access.') : __('Review the included tools and credits before checkout.')) }}</span>
                <a href="{{ $active ? '#membership-addons' : route('payment.premium') }}" @unless($active) wire:navigate @endunless class="profile-primary">{{ $active ? __('Buy credits') : ($expired ? __('Reactivate Premium') : __('Explore Premium')) }}</a>
            </div>
        @endif
    </section>

    <section class="profile-panel" aria-labelledby="membership-balances-title">
        <header class="profile-section-header"><div><h2 id="membership-balances-title">{{ __('AI credits') }}</h2><p>{{ $monetizationEnabled ? __('Each tool has its own credit balance.') : __('Stored balances · no deductions in free access mode.') }}</p></div></header>
        <dl class="profile-credit-list">
            @foreach($creditBalances as $credit)
                <div><dt>{{ __($credit['label']) }}</dt><dd><strong>{{ $credit['balance'] }}</strong><span>{{ __('credits') }}</span></dd></div>
            @endforeach
        </dl>
        @if($monetizationEnabled)
            <div id="membership-addons" class="profile-credit-topup">
                <div><h3>{{ __('Credit top-up') }}</h3><p>@foreach($creditBalances as $credit){{ $addonBenefits[$credit['field']] }} {{ __($credit['label']) }}{{ !$loop->last ? ' · ' : '' }}@endforeach</p><small>{{ __('Added to your balance. Membership validity stays the same.') }}</small></div>
                <div class="profile-credit-purchase"><strong>{{ __('Rp :value1', ['value1' => (number_format($addonPrice, 0, ',', '.'))]) }}</strong><a class="profile-secondary" href="{{ route($active ? 'payment.topup' : 'payment.premium') }}" wire:navigate>{{ $active ? __('Buy credits') : __('Explore Premium first') }}</a></div>
                @unless($active)<p class="profile-field-hint">{{ __('An active Premium membership is required to use credits.') }}</p>@endunless
            </div>
        @endif
    </section>

            <section id="membership-history" class="membership-history" aria-labelledby="membership-history-title">
                <header class="profile-section-header"><div><h2 id="membership-history-title">{{ __('Payment history') }}</h2><p>{{ __('Your membership and credit purchases.') }}</p></div><span class="membership-order-count">{{ __($payments->total() === 1 ? ':count order' : ':count orders', ['count'=>$payments->total()]) }}</span></header>
                @if($payments->isEmpty())<div class="membership-empty-state"><h3>{{ __('No payments yet.') }}</h3><p>{{ __('Your membership and credit purchases will appear here.') }}</p></div>
                @else<div class="membership-transactions"><table><caption class="sr-only">{{ __('Your payment history') }}</caption><thead><tr><th scope="col">{{ __('Purchase') }}</th><th scope="col">{{ __('Date') }}</th><th scope="col">{{ __('Amount') }}</th><th scope="col">{{ __('Status') }}</th><th scope="col"><span class="sr-only">{{ __('Action') }}</span></th></tr></thead><tbody>
                    @foreach($payments as $payment)
                        @php
                            $package = $payment->notes ?? ($payment->metadata['package_type'] ?? 'premium');
                            $addon = in_array($package, ['addon_10', 'cl_addon_15'], true);
                            $waiting = in_array($payment->status, ['PENDING', 'WAITING'], true);
                            $orderExpired = $waiting && $payment->expired_at?->isPast();
                            $status = $orderExpired ? 'EXPIRED' : $payment->status;
                            $statusLabel = match($status) { 'SUCCESS' => 'Paid', 'PENDING', 'WAITING' => 'Awaiting payment', 'FAILED' => 'Failed', 'EXPIRED' => 'Expired', 'CANCELED', 'CANCELLED' => 'Cancelled', default => ucfirst(strtolower($status)) };
                        @endphp
                        <tr><th scope="row" class="membership-transaction-product"><strong>{{ $addon ? __('TraKerja Add-On Pack') : __('Premium membership') }}</strong><span>{{ $payment->order_id }}</span></th><td class="membership-transaction-date"><time datetime="{{ $payment->created_at->toIso8601String() }}">{{ $payment->created_at->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M Y') }}</time></td><td class="membership-transaction-amount">{{ __('Rp :value1', ['value1' => (number_format($payment->amount, 0, ',', '.'))]) }}</td><td class="membership-transaction-status"><span class="membership-payment-status membership-payment-status--{{ strtolower($status) }}">{{ __($statusLabel) }}</span></td><td class="membership-transaction-action">@if($waiting && !$orderExpired)<a href="{{ route('payment.waiting', $payment->order_id) }}" wire:navigate class="membership-continue" aria-label="{{ __('Continue payment :value1', ['value1' => ($payment->order_id)]) }}">{{ __('Continue payment') }}</a>@elseif($payment->isSuccess())<a href="{{ route('payment.success', $payment->order_id) }}" wire:navigate class="membership-text-link" aria-label="{{ __('View receipt :value1', ['value1' => ($payment->order_id)]) }}">{{ __('View receipt') }}</a>@else<span class="sr-only">{{ __('No payment action available') }}</span>@endif</td></tr>
                    @endforeach
                </tbody></table></div>@if($payments->hasPages())<div class="membership-pagination">{{ $payments->links() }}</div>@endif @endif
                <footer class="membership-history-note">{{ __('Membership and credits update after payment verification.') }}</footer>
            </section>
            <footer class="membership-footer"><p>{{ __('Need help with a payment or your account?') }}</p><a href="{{ route('support.index') }}" wire:navigate class="membership-text-link">{{ __('Contact support') }}</a></footer>

</div>
