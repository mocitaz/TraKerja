@php
    $durationLabel = match(true) {
        $premiumDuration >= 3650 => '10+ years',
        $premiumDuration === 365 => '1 year',
        $premiumDuration === 30 => '1 month',
        default => $premiumDuration.' days',
    };
    $planLabel = $active ? 'Premium active' : ($expired ? 'Premium expired' : 'Free account');
@endphp
<div class="membership-page profile-membership">
    @if($pendingPayment ?? null)
        <aside class="profile-panel profile-billing-pending">
            <div><strong>A payment is waiting for you</strong><p>Rp {{ number_format($pendingPayment->amount, 0, ',', '.') }} · {{ $pendingPayment->order_id }}</p></div>
            <a class="profile-secondary" href="{{ route('payment.waiting', $pendingPayment->order_id) }}" wire:navigate>Continue payment</a>
        </aside>
    @endif

    <section class="profile-panel" aria-labelledby="membership-plan-title">
        <header class="profile-section-header">
            <div><h2 id="membership-plan-title">Current plan</h2><p>Manage your membership and access.</p></div>
            <span class="profile-billing-status">{{ $planLabel }}</span>
        </header>
        <dl class="profile-billing-details">
            <div><dt>Plan</dt><dd>{{ $active ? 'TraKerja Premium' : 'TraKerja Free' }}</dd></div>
            @if($active)
                <div><dt>Valid until</dt><dd>{{ $user->premium_until ? $user->premium_until->timezone('Asia/Jakarta')->format('d M Y') : 'No expiry recorded' }}</dd></div>
                @if($user->premium_purchased_at)<div><dt>Purchased on</dt><dd>{{ \Carbon\Carbon::parse($user->premium_purchased_at)->timezone('Asia/Jakarta')->format('d M Y') }}</dd></div>@endif
            @elseif($monetizationEnabled)
                <div><dt>Premium membership</dt><dd>Rp {{ number_format($premiumPrice, 0, ',', '.') }} / {{ $durationLabel }}</dd></div>
            @endif
        </dl>
        @unless($monetizationEnabled)
            <div class="profile-billing-note"><strong>Free access is enabled</strong><p>Premium features are available without a paid plan. Your stored credits are not deducted.</p></div>
        @endunless
        @if($monetizationEnabled)
            <div class="profile-form-footer">
                <span>{{ $active ? 'Your Premium access is ready to use.' : ($expired ? 'Renew to restore your Premium access.' : 'Review the included tools and credits before checkout.') }}</span>
                <a href="{{ $active ? '#membership-addons' : route('payment.premium') }}" @unless($active) wire:navigate @endunless class="profile-primary">{{ $active ? 'Buy credits' : ($expired ? 'Reactivate Premium' : 'Explore Premium') }}</a>
            </div>
        @endif
    </section>

    <section class="profile-panel" aria-labelledby="membership-balances-title">
        <header class="profile-section-header"><div><h2 id="membership-balances-title">AI credits</h2><p>{{ $monetizationEnabled ? 'Each tool has its own credit balance.' : 'Stored balances · no deductions in free access mode.' }}</p></div></header>
        <dl class="profile-credit-list">
            @foreach($creditBalances as $credit)
                <div><dt>{{ $credit['label'] }}</dt><dd><strong>{{ $credit['balance'] }}</strong><span>credits</span></dd></div>
            @endforeach
        </dl>
        @if($monetizationEnabled)
            <div id="membership-addons" class="profile-credit-topup">
                <div><h3>Credit top-up</h3><p>@foreach($creditBalances as $credit){{ $addonBenefits[$credit['field']] }} {{ $credit['label'] }}{{ !$loop->last ? ' · ' : '' }}@endforeach</p><small>Added to your balance. Membership validity stays the same.</small></div>
                <div class="profile-credit-purchase"><strong>Rp {{ number_format($addonPrice, 0, ',', '.') }}</strong><a class="profile-secondary" href="{{ route($active ? 'payment.topup' : 'payment.premium') }}" wire:navigate>{{ $active ? 'Buy credits' : 'Explore Premium first' }}</a></div>
                @unless($active)<p class="profile-field-hint">An active Premium membership is required to use credits.</p>@endunless
            </div>
        @endif
    </section>

            <section id="membership-history" class="membership-history" aria-labelledby="membership-history-title">
                <header class="profile-section-header"><div><h2 id="membership-history-title">Payment history</h2><p>Your membership and credit purchases.</p></div><span class="membership-order-count">{{ $payments->total() }} {{ Str::plural('order', $payments->total()) }}</span></header>
                @if($payments->isEmpty())<div class="membership-empty-state"><h3>No payments yet.</h3><p>Your membership and credit purchases will appear here.</p></div>
                @else<div class="membership-transactions"><table><caption class="sr-only">Your payment history</caption><thead><tr><th scope="col">Purchase</th><th scope="col">Date</th><th scope="col">Amount</th><th scope="col">Status</th><th scope="col"><span class="sr-only">Action</span></th></tr></thead><tbody>
                    @foreach($payments as $payment)
                        @php
                            $package = $payment->notes ?? ($payment->metadata['package_type'] ?? 'premium');
                            $addon = in_array($package, ['addon_10', 'cl_addon_15'], true);
                            $waiting = in_array($payment->status, ['PENDING', 'WAITING'], true);
                            $orderExpired = $waiting && $payment->expired_at?->isPast();
                            $status = $orderExpired ? 'EXPIRED' : $payment->status;
                            $statusLabel = match($status) { 'SUCCESS' => 'Paid', 'PENDING', 'WAITING' => 'Awaiting payment', 'FAILED' => 'Failed', 'EXPIRED' => 'Expired', 'CANCELED', 'CANCELLED' => 'Cancelled', default => ucfirst(strtolower($status)) };
                        @endphp
                        <tr><th scope="row" class="membership-transaction-product"><strong>{{ $addon ? 'TraKerja Add-On Pack' : 'Premium membership' }}</strong><span>{{ $payment->order_id }}</span></th><td class="membership-transaction-date"><time datetime="{{ $payment->created_at->toIso8601String() }}">{{ $payment->created_at->timezone('Asia/Jakarta')->format('d M Y') }}</time></td><td class="membership-transaction-amount">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td><td class="membership-transaction-status"><span class="membership-payment-status membership-payment-status--{{ strtolower($status) }}">{{ $statusLabel }}</span></td><td class="membership-transaction-action">@if($waiting && !$orderExpired)<a href="{{ route('payment.waiting', $payment->order_id) }}" wire:navigate class="membership-continue" aria-label="Continue payment {{ $payment->order_id }}">Continue payment</a>@elseif($payment->isSuccess())<a href="{{ route('payment.success', $payment->order_id) }}" wire:navigate class="membership-text-link" aria-label="View receipt {{ $payment->order_id }}">View receipt</a>@else<span class="sr-only">No payment action available</span>@endif</td></tr>
                    @endforeach
                </tbody></table></div>@if($payments->hasPages())<div class="membership-pagination">{{ $payments->links() }}</div>@endif @endif
                <footer class="membership-history-note">Membership and credits update after payment verification.</footer>
            </section>
            <footer class="membership-footer"><p>Need help with a payment or your account?</p><a href="{{ route('support.index') }}" wire:navigate class="membership-text-link">Contact support</a></footer>

</div>
