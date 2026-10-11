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

            @php
                $durationLabel = match(true) {
                    $premiumDuration >= 3650 => 'lifetime access',
                    $premiumDuration === 365 => '1 year',
                    $premiumDuration === 30 => '1 month',
                    default => $premiumDuration . ' days'
                };
            @endphp

            {{-- Hero Container (Single Blue #d0e3f3 Card: Plan + Stored Balances) --}}
            <article class="next-move-card next-move-interview membership-account-card" aria-labelledby="membership-plan-title">
                <div class="membership-account-grid">
                    {{-- Left Column: Membership Plan --}}
                    <div class="membership-account-col membership-account-col--plan">
                        <div class="next-move-card-top">
                            <span>Membership plan</span>
                            @if(!$active && $monetizationEnabled)
                                <a href="{{ route('payment.premium') }}" wire:navigate aria-label="Explore Premium">Upgrade <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>
                            @endif
                        </div>
                        <h3 id="membership-plan-title">{{ $active ? 'Premium active' : ($expired ? 'Premium expired' : 'Free account') }}</h3>
                        <div class="next-move-content">
                            <p class="next-move-description">
                                {{ $active 
                                    ? 'Your account is active with full access to intelligent career analytics, tracking automation, and priority AI features.' 
                                    : ($expired 
                                        ? 'Your Premium access has expired. Renew your plan to restore full access to career intelligence and tracking automation.' 
                                        : 'Upgrade to TraKerja Premium to unlock advanced career analytics, automated application tracking, and full AI quota.') 
                                }}
                            </p>

                            @if(!$active)
                                <ul class="next-move-checklist" aria-label="Premium features">
                                    <li class="is-added"><i class="ph ph-check-circle" aria-hidden="true"></i><span>Summary &amp; Analytics</span></li>
                                    <li class="is-added"><i class="ph ph-check-circle" aria-hidden="true"></i><span>Cover Letter AI</span></li>
                                    <li class="is-added"><i class="ph ph-check-circle" aria-hidden="true"></i><span>CV Builder AI</span></li>
                                    <li class="is-added"><i class="ph ph-check-circle" aria-hidden="true"></i><span>Automated tracking</span></li>
                                </ul>
                            @else
                                <div class="next-move-appointment">
                                    <time>
                                        <strong>{{ $user->premium_until ? $user->premium_until->format('d') : '∞' }}</strong>
                                        <span>{{ $user->premium_until ? $user->premium_until->format('M') : 'Plan' }}</span>
                                    </time>
                                    <div>
                                        <strong>{{ $user->premium_until && $user->premium_until->diffInDays(now()) > 3650 ? 'Lifetime Premium' : 'Active Premium' }}</strong>
                                        <span>Valid until {{ $user->premium_until ? $user->premium_until->timezone('Asia/Jakarta')->format('d M Y') . ' · ' . $user->premium_until->timezone('Asia/Jakarta')->format('H:i') . ' WIB' : 'No expiry recorded' }}</span>
                                        @if($user->premium_purchased_at)
                                            <span>Purchased {{ \Carbon\Carbon::parse($user->premium_purchased_at)->timezone('Asia/Jakarta')->format('d M Y') }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if($monetizationEnabled && !$active)
                            <a class="next-move-action" href="{{ route('payment.premium') }}" wire:navigate>
                                <span>{{ $expired ? 'Reactivate Premium' : 'Explore Premium' }} · Rp {{ number_format($premiumPrice, 0, ',', '.') }} / {{ $durationLabel }}</span>
                                <i class="ph ph-arrow-right" aria-hidden="true"></i>
                            </a>
                            <p class="next-move-footnote">Instant activation · Official secure checkout via QRIS &amp; e-Wallet</p>
                        @elseif($active)
                            <a class="next-move-action" href="#membership-addons">
                                <span>{{ $monetizationEnabled ? 'Explore add-on packs' : 'View credit balance' }}</span>
                                <i class="ph ph-arrow-down" aria-hidden="true"></i>
                            </a>
                            <p class="next-move-footnote">Verified active Premium membership · All features unlocked</p>
                        @else
                            <a class="next-move-action" href="{{ route('dashboard') }}" wire:navigate>
                                <span>Back to dashboard</span>
                                <i class="ph ph-arrow-right" aria-hidden="true"></i>
                            </a>
                            <p class="next-move-footnote">Free access mode is currently active</p>
                        @endif
                    </div>

                    {{-- Right Column: Stored Balances --}}
                    <div class="membership-account-col membership-account-col--balance">
                        <div class="next-move-card-top">
                            <span>Stored balance</span>
                            <i class="ph ph-wallet" aria-hidden="true"></i>
                        </div>
                        <h3 id="membership-balances-title">Your credit balance</h3>
                        <div class="next-move-content">
                            <p class="next-move-description">{{ $monetizationEnabled ? 'Stored credits available for your career intelligence tools.' : 'Stored balance · not deducted in free mode.' }}</p>
                            <div class="membership-credit-grid">
                                @foreach($creditBalances as $credit)
                                    <div class="membership-credit-box">
                                        <span class="membership-credit-label">{{ $credit['label'] }}</span>
                                        <strong class="membership-credit-val">{{ $credit['balance'] }}</strong>
                                        <span class="membership-credit-unit">credits</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <a class="next-move-action" href="#membership-addons">
                            <span>Refill credit balance</span>
                            <i class="ph ph-arrow-down" aria-hidden="true"></i>
                        </a>
                        <p class="next-move-footnote">Each tool has its own balance · Add-on purchases reflected instantly</p>
                    </div>
                </div>
            </article>

            <div class="membership-bottom-grid">
                {{-- Card 3: Add-on pack (Lavender #e5e0f5, like Continue your CV) --}}
                <article id="membership-addons" class="next-move-card next-move-cv membership-card-addons" aria-labelledby="membership-addons-title">
                    <div class="next-move-card-top">
                        <span>Add-on booster</span>
                        <i class="ph ph-package" aria-hidden="true"></i>
                    </div>
                    <h3 id="membership-addons-title">A little extra, when you need it</h3>
                    <div class="next-move-content">
                        <p class="next-move-description">One add-on pack refills three separate credit balances.</p>
                        <div class="membership-pack-grid">
                            @foreach($creditBalances as $credit)
                                <div class="membership-credit-box">
                                    <strong class="membership-credit-val">+{{ $addonBenefits[$credit['field']] }}</strong>
                                    <span class="membership-credit-label">{{ $credit['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if(!$monetizationEnabled)
                        <span class="next-move-action">
                            <span>Free access is enabled</span>
                            <i class="ph ph-check" aria-hidden="true"></i>
                        </span>
                        <p class="next-move-footnote">Free access is enabled · Credits are not deducted</p>
                    @elseif($active)
                        <a class="next-move-action" href="{{ route('payment.topup') }}" wire:navigate>
                            <span>Get add-on pack · Rp {{ number_format($addonPrice, 0, ',', '.') }}</span>
                            <i class="ph ph-arrow-right" aria-hidden="true"></i>
                        </a>
                        <p class="next-move-footnote">Adds credits to existing balances · Does not extend Premium duration</p>
                    @else
                        <a class="next-move-action" href="{{ route('payment.premium') }}" wire:navigate>
                            <span>Available after Premium · Rp {{ number_format($addonPrice, 0, ',', '.') }}</span>
                            <i class="ph ph-arrow-right" aria-hidden="true"></i>
                        </a>
                        <p class="next-move-footnote">Requires an active Premium membership to purchase and use</p>
                    @endif
                </article>

                {{-- Card 4: Payment History (Peach/Orange #f5ddd2 container, harmonized structure) --}}
                <article class="next-move-card next-move-followup membership-card-history" aria-labelledby="membership-history-title">
                    <div class="next-move-card-top">
                        <span>Payment records</span>
                        <span class="membership-history-count">{{ $payments->total() }} {{ Str::plural('order', $payments->total()) }}</span>
                    </div>
                    <h3 id="membership-history-title">Payment history</h3>
                    <div class="next-move-content">
                        <p class="next-move-description">Your Premium and add-on orders, including unfinished payments.</p>

                        @if($payments->isEmpty())
                            <div class="membership-empty">
                                <div class="membership-empty-icon"><i class="ph ph-receipt" aria-hidden="true"></i></div>
                                <h4>No payments yet.</h4>
                                <p>Your orders and receipts will appear here once you make a purchase.</p>
                            </div>
                        @else
                            <div class="membership-orders-scroll">
                                <div class="membership-orders-list">
                                    @foreach($payments as $payment)
                                        @php
                                            $package = $payment->notes ?? ($payment->metadata['package_type'] ?? 'premium');
                                            $addon = in_array($package, ['addon_10', 'cl_addon_15'], true);
                                            $waiting = in_array($payment->status, ['PENDING', 'WAITING'], true);
                                            $orderExpired = $waiting && $payment->expired_at?->isPast();
                                            $status = $orderExpired ? 'EXPIRED' : $payment->status;
                                            $statusLabel = match($status) { 
                                                'SUCCESS' => 'Paid', 
                                                'PENDING', 'WAITING' => 'Awaiting payment', 
                                                'FAILED' => 'Failed', 
                                                'EXPIRED' => 'Expired', 
                                                'CANCELED', 'CANCELLED' => 'Cancelled', 
                                                default => ucfirst(strtolower($status)) 
                                            };
                                        @endphp
                                        <div class="membership-order-item">
                                            <div class="membership-order-left">
                                                <div class="membership-order-info">
                                                    <strong>{{ $addon ? 'TraKerja Add-On Pack' : 'Premium membership' }}</strong>
                                                    <span class="membership-order-meta">
                                                        <span>{{ $payment->created_at->timezone('Asia/Jakarta')->format('d M Y') }}</span>
                                                        <span>·</span>
                                                        <span class="membership-order-id-code">{{ Str::limit($payment->order_id, 16) }}</span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="membership-order-right">
                                                <div class="membership-order-pricing-col">
                                                    <span class="membership-order-amount">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                                    <span class="membership-order-status membership-order-status--{{ strtolower($status) }}">{{ $statusLabel }}</span>
                                                </div>
                                                @if($waiting && !$orderExpired)
                                                    <a href="{{ route('payment.waiting', $payment->order_id) }}" wire:navigate class="membership-order-btn-resume" title="Resume payment">
                                                        <span>Resume</span>
                                                        <i class="ph ph-arrow-right" aria-hidden="true"></i>
                                                    </a>
                                                @elseif($payment->isSuccess())
                                                    <a href="{{ route('payment.success', $payment->order_id) }}" wire:navigate class="membership-order-btn-view" aria-label="View payment {{ $payment->order_id }}" title="View receipt">
                                                        <i class="ph ph-arrow-up-right" aria-hidden="true"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @if($payments->hasPages())
                                <div class="membership-pagination">{{ $payments->links() }}</div>
                            @endif
                        @endif
                    </div>
                    <p class="next-move-footnote">Orders update automatically after payment verification</p>
                </article>
            </div>

            <footer class="membership-footer"><p>Membership status reflects your account record. A pending order becomes active only after payment verification.</p><a href="{{ route('support.index') }}" wire:navigate class="job-text-link">Need help with an order?</a></footer>
        </div>
    </div>
</x-app-layout>
