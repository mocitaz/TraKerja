<x-app-layout>
    @php
        $durationLabel = match(true) {
            $premiumDuration >= 3650 => '10+ years',
            $premiumDuration === 365 => '1 year',
            $premiumDuration === 30 => '1 month',
            default => $premiumDuration.' days',
        };
        $planLabel = $active ? 'Premium active' : ($expired ? 'Premium expired' : 'Free account');
    @endphp
    <div class="workspace-page membership-page">
        <div class="workspace-page__container">
            <header class="workspace-page__header"><div><h1>Membership &amp; Credits</h1><p>Manage your plan, credits, and payments.</p></div><a href="{{ route('support.index') }}" wire:navigate class="membership-help">Billing support <span aria-hidden="true">↗</span></a></header>

            @unless($monetizationEnabled)
                <aside class="membership-access-note"><strong>Free access is enabled</strong><p>Premium features are currently available without a paid membership. Credit deductions are paused; balances below are your stored credits.</p></aside>
            @endunless

            @if($pendingPayment ?? null)
                <aside class="membership-pending-note"><div><strong>A payment is waiting for you</strong><p>Order {{ $pendingPayment->order_id }} · Rp {{ number_format($pendingPayment->amount, 0, ',', '.') }}. Your plan and credits update after payment verification.</p></div><a class="membership-continue" href="{{ route('payment.waiting', $pendingPayment->order_id) }}" wire:navigate>Continue payment</a></aside>
            @endif

            <section class="membership-plan-panel" aria-labelledby="membership-plan-title">
                <div class="membership-plan-copy"><span class="membership-eyebrow">CURRENT MEMBERSHIP</span><div class="membership-plan-heading"><h2 id="membership-plan-title">{{ $active ? 'TraKerja Premium' : 'TraKerja Free' }}</h2><span class="membership-status {{ $active ? 'membership-status--active' : ($expired ? 'membership-status--expired' : '') }}">{{ $planLabel }}</span></div>
                    <p>{{ !$monetizationEnabled ? 'Your workspace is available in free access mode.' : ($active ? 'Your Premium access is ready to use. Manage your balances and purchases below.' : ($expired ? 'Renew your membership to restore access to Premium features.' : 'Keep tracking your applications. Explore Premium for additional tools and insights.')) }}</p>
                    <div class="membership-plan-actions">
                        @if($monetizationEnabled && !$active)<a href="{{ route('payment.premium') }}" wire:navigate class="membership-button">{{ $expired ? 'Reactivate Premium' : 'Explore Premium' }}</a>
                        @elseif($active && $monetizationEnabled)<a href="#membership-addons" class="membership-button">Buy credits</a>
                        @else<a href="{{ route('dashboard') }}" wire:navigate class="membership-button membership-button--quiet">Open dashboard</a>@endif
                        <a href="#membership-history" class="membership-text-link">View payment history</a>
                    </div>
                </div>
                <dl class="membership-plan-details">
                    @if($active)<div><dt>Membership validity</dt><dd>{{ $user->premium_until ? $user->premium_until->timezone('Asia/Jakarta')->format('d M Y') : 'No expiry recorded' }}</dd>@if($user->premium_until)<small>Until {{ $user->premium_until->timezone('Asia/Jakarta')->format('H:i') }} WIB</small>@endif</div>
                        @if($user->premium_purchased_at)<div><dt>Purchased on</dt><dd>{{ \Carbon\Carbon::parse($user->premium_purchased_at)->timezone('Asia/Jakarta')->format('d M Y') }}</dd></div>@endif
                    @elseif($monetizationEnabled)<div><dt>Premium access</dt><dd>Rp {{ number_format($premiumPrice, 0, ',', '.') }} <small>/ {{ $durationLabel }}</small></dd><small>Review the package before checkout.</small></div><div><dt>Included tools</dt><dd class="membership-tools">Summary &amp; Analytics · CV Builder · Cover Letter AI</dd><small>Feature access and AI credits follow the selected package.</small></div>
                    @else<div><dt>Current access</dt><dd>Free access mode</dd><small>No purchase is required while this mode is enabled.</small></div>@endif
                </dl>
            </section>

            <section class="membership-balances" aria-labelledby="membership-balances-title">
                <header class="membership-section-heading"><div><h2 id="membership-balances-title">Credit balances</h2><p>{{ $monetizationEnabled ? 'Separate balances for each AI tool.' : 'Stored credits · no deductions in free access mode.' }}</p></div>@if($active && $monetizationEnabled)<a class="membership-text-link" href="#membership-addons">Add credits</a>@endif</header>
                <div class="membership-balance-grid">@foreach($creditBalances as $credit)<article class="membership-balance" data-tone="{{ $credit['tone'] }}"><h3>{{ $credit['label'] }}</h3><p><strong>{{ $credit['balance'] }}</strong><span>credits</span></p><span class="membership-balance-caption">{{ !$monetizationEnabled ? 'Stored balance' : ($active ? 'Available to use' : 'Requires active Premium to use') }}</span></article>@endforeach</div>
            </section>

            <section id="membership-addons" class="membership-addon" aria-labelledby="membership-addons-title">
                <div><span class="membership-eyebrow">CREDIT TOP-UP</span><h2 id="membership-addons-title">One pack. Three tools.</h2><p>Added to your existing balances. Membership validity stays the same.</p></div>
                <ul aria-label="Add-on credits">@foreach($creditBalances as $credit)<li><strong>+{{ $addonBenefits[$credit['field']] }}</strong><span>{{ $credit['label'] }}</span></li>@endforeach</ul>
                <div class="membership-addon-purchase">@if(!$monetizationEnabled)<strong class="membership-addon-free">No top-up needed</strong><p>Credits are not deducted in free access mode.</p>@else<strong>Rp {{ number_format($addonPrice, 0, ',', '.') }}</strong><span>per pack</span><a class="membership-button {{ !$active ? 'membership-button--quiet' : '' }}" href="{{ route($active ? 'payment.topup' : 'payment.premium') }}" wire:navigate>{{ $active ? 'Buy credits' : 'Explore Premium first' }}</a>@unless($active)<p>An active Premium membership is required.</p>@endunless @endif</div>
            </section>

            <section id="membership-history" class="membership-history" aria-labelledby="membership-history-title">
                <header class="membership-section-heading"><div><h2 id="membership-history-title">Payment history</h2><p>Your membership and credit purchases, including unfinished payments.</p></div><span class="membership-order-count">{{ $payments->total() }} {{ Str::plural('order', $payments->total()) }}</span></header>
                @if($payments->isEmpty())<div class="membership-empty-state"><h3>No payments yet.</h3><p>Your purchases will appear here. You can return to an unfinished payment from this list.</p></div>
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
    </div>
</x-app-layout>
