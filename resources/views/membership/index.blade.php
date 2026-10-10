<x-app-layout>
    <div class="workspace-page membership-page">
        <div class="workspace-page__container">
            <header class="workspace-page__header">
                <div><p class="workspace-eyebrow">Your TraKerja account</p><h1>Membership &amp; Credits</h1><p>Your plan, your balance, and every purchase in one place.</p></div>
                <a href="{{ route('support.index') }}" wire:navigate class="workspace-button workspace-button--quiet">Billing help <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>
            </header>

            @if(!$monetizationEnabled)
                <div class="membership-free-access"><strong>Free access is enabled</strong><p>Premium features are currently available without a paid membership. Credit deductions are paused in this mode; the balances below are your stored credits.</p></div>
            @endif

            <div class="membership-top-grid">
                <section class="membership-plan membership-enter" aria-labelledby="membership-plan-title">
                    <div class="membership-plan-heading"><span>Current membership</span><span class="membership-plan-badge">{{ $active ? 'Active' : ($expired ? 'Expired' : 'Free') }}</span></div>
                    <h2 id="membership-plan-title">{{ $active ? 'Premium active' : ($expired ? 'Premium expired' : 'Free account') }}</h2>
                    <p>{{ $active ? 'Your Premium membership is active. Keep track of its expiry and your available credits here.' : ($expired ? 'Your Premium term has ended. Your recorded credits and payment history remain visible below.' : 'Your account is on the Free plan. This portal stays available whenever you need to check your membership.') }}</p>
                    <dl class="membership-plan-dates">
                        @if($active || $expired)
                            <div><dt>{{ $expired ? 'Expired on' : 'Valid until' }}</dt><dd>{{ $user->premium_until ? $user->premium_until->timezone('Asia/Jakarta')->format('d M Y · H:i').' WIB' : 'No expiry recorded' }}</dd></div>
                        @endif
                        @if($user->premium_purchased_at)<div><dt>Last Premium purchase</dt><dd>{{ \Carbon\Carbon::parse($user->premium_purchased_at)->timezone('Asia/Jakarta')->format('d M Y') }}</dd></div>@endif
                    </dl>
                    @if($monetizationEnabled && !$active)
                        <div class="membership-plan-upgrade"><span>Rp {{ number_format($premiumPrice, 0, ',', '.') }} <small>/ {{ $premiumDuration }} days</small></span><a href="{{ route('payment.premium') }}" wire:navigate class="membership-plan-button">{{ $expired ? 'Reactivate Premium' : 'Explore Premium' }} <i class="ph ph-arrow-right" aria-hidden="true"></i></a></div>
                    @elseif($active)
                        <a href="#membership-addons" class="membership-plan-button">{{ $monetizationEnabled ? 'Explore add-ons' : 'View your credits' }} <i class="ph ph-arrow-down" aria-hidden="true"></i></a>
                    @else
                        <a href="{{ route('dashboard') }}" wire:navigate class="membership-plan-button">Back to your workspace <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
                    @endif
                </section>

                <section class="workspace-card membership-balances membership-enter" aria-labelledby="membership-balances-title">
                    <div class="workspace-section-heading"><div><h2 id="membership-balances-title">Your credit balance</h2><p>{{ $monetizationEnabled ? 'Stored credits available for these tools.' : 'Stored balance · not deducted in free mode.' }}</p></div></div>
                    <div class="membership-credit-grid">
                        @foreach($creditBalances as $credit)
                            <div class="membership-credit membership-credit--{{ $credit['tone'] }}"><span>{{ $credit['label'] }}</span><strong>{{ $credit['balance'] }}</strong><span>credits</span></div>
                        @endforeach
                    </div>
                    <p class="workspace-hint">Each tool has its own balance. Add-on purchases are reflected after payment verification.</p>
                    @if($monetizationEnabled && !$active)<p class="membership-balance-note">Buying add-ons requires an active Premium membership. A credit balance does not change your membership status.</p>@endif
                </section>
            </div>

            <section id="membership-addons" class="workspace-card workspace-card--glass membership-addons membership-enter" aria-labelledby="membership-addons-title">
                <div class="workspace-section-heading"><div><h2 id="membership-addons-title">A little extra, when you need it.</h2><p>One add-on pack. Three separate credit balances.</p></div><span class="membership-pack-label">TraKerja Add-On Pack</span></div>
                <div class="membership-pack">
                    <div class="membership-pack-benefits">@foreach($creditBalances as $credit)<div><strong>+{{ $addonBenefits[$credit['field']] }}</strong><span>{{ $credit['label'] }}</span></div>@endforeach</div>
                    <div class="membership-pack-purchase"><span>Rp {{ number_format($addonPrice, 0, ',', '.') }} <small>per pack</small></span>
                        @if(!$monetizationEnabled)<p>No purchase needed while free access is enabled.</p>
                        @elseif($active)<a href="{{ route('payment.topup') }}" wire:navigate class="workspace-button workspace-button--primary">Get add-on pack <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
                        @else<p>Available after Premium activation.</p><a href="{{ route('payment.premium') }}" wire:navigate class="workspace-button workspace-button--quiet">View Premium</a>@endif
                    </div>
                </div>
                <p class="workspace-hint">Adds credits to your existing balances. It does not extend your Premium membership. Review the payment method and total at checkout.</p>
            </section>

            <section class="workspace-card membership-history membership-enter" aria-labelledby="membership-history-title">
                <div class="workspace-section-heading"><div><h2 id="membership-history-title">Payment history</h2><p>Your Premium and add-on orders, including unfinished payments.</p></div><span class="membership-history-count">{{ $payments->total() }} orders</span></div>
                @if($payments->isEmpty())
                    <div class="membership-empty"><h3>No payments yet.</h3><p>Your orders will appear here when you start a Premium or add-on purchase.</p></div>
                @else
                    <div class="membership-table-scroll"><table><thead><tr><th scope="col">Order</th><th scope="col">Date</th><th scope="col">Amount</th><th scope="col">Status</th><th scope="col"><span class="sr-only">Order action</span></th></tr></thead><tbody>
                        @foreach($payments as $payment)
                            @php
                                $package = $payment->notes ?? ($payment->metadata['package_type'] ?? 'premium');
                                $addon = in_array($package, ['addon_10', 'cl_addon_15'], true);
                                $waiting = in_array($payment->status, ['PENDING', 'WAITING'], true);
                                $orderExpired = $waiting && $payment->expired_at?->isPast();
                                $status = $orderExpired ? 'EXPIRED' : $payment->status;
                                $statusLabel = match($status) { 'SUCCESS' => 'Paid', 'PENDING', 'WAITING' => 'Awaiting payment', 'FAILED' => 'Failed', 'EXPIRED' => 'Expired', 'CANCELED', 'CANCELLED' => 'Cancelled', default => ucfirst(strtolower($status)) };
                            @endphp
                            <tr><td><strong>{{ $addon ? 'Add-On Pack' : 'Premium membership' }}</strong><span class="membership-order-id">{{ $payment->order_id }}</span></td><td>{{ $payment->created_at->timezone('Asia/Jakarta')->format('d M Y') }}</td><td class="membership-amount">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td><td><span class="membership-order-status membership-order-status--{{ strtolower($status) }}">{{ $statusLabel }}</span></td><td>@if($waiting && !$orderExpired)<a href="{{ route('payment.waiting', $payment->order_id) }}" wire:navigate class="membership-order-link">Resume payment <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>@elseif($payment->isSuccess())<a href="{{ route('payment.success', $payment->order_id) }}" wire:navigate class="membership-order-link" aria-label="View payment {{ $payment->order_id }}">View payment <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>@endif</td></tr>
                        @endforeach
                    </tbody></table></div>
                    @if($payments->hasPages())<div class="membership-pagination">{{ $payments->links() }}</div>@endif
                @endif
            </section>
            <footer class="membership-footer"><p>Membership status reflects your account record. A pending order becomes active only after payment verification.</p><a href="{{ route('support.index') }}" wire:navigate class="job-text-link">Need help with an order?</a></footer>
        </div>
    </div>
</x-app-layout>
