@php
    $premiumUser = clone auth()->user();
    $premiumUser->forceFill(['is_premium' => true, 'payment_status' => 'paid', 'premium_until' => null]);
    $freeAccess = !\App\Models\Setting::isMonetizationEnabled();
    $credits = $isAddon ? \App\Support\PaymentPackages::ADDON_BENEFITS : [
        'ai_credits' => \App\Models\Setting::getLimit('ai_analyzer', $premiumUser),
        'cl_credits' => \App\Models\Setting::getLimit('cover_letter', $premiumUser),
        'photo_credits' => \App\Models\Setting::getLimit('ai_photo', $premiumUser),
    ];
@endphp
<div class="payment-workspace workspace-page-frame">
    <header class="pay-heading">
        <div><a class="pay-back" href="{{ route('membership.index') }}" wire:navigate>← Membership & credits</a>
            <h1>{{ $isAddon ? 'A little more room to create.' : 'Make room for your next move.' }}</h1>
            <p>{{ $isAddon ? 'One credit pack. More ways to prepare for your next opportunity.' : 'Your career tools, together in one membership.' }}</p></div>
        <a class="pay-link" href="{{ route('payment.history') }}" wire:navigate>Payment history ↗</a>
    </header>
    @if($errors->any())
        <div class="pay-notice pay-notice--error" role="alert"><strong>We couldn’t start checkout.</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    @if($freeAccess)
        <div class="pay-notice">Free access is currently enabled. Premium features are available without a paid membership.</div>
    @endif
    <form class="pay-checkout-grid" method="POST" action="{{ route('payment.checkout') }}" x-data="paymentCheckout(@js(old('payment_channel_code', '')))" @submit="submit($event)">
        @csrf
        <input type="hidden" name="package_type" value="{{ $packageType ?? 'premium' }}">
        <div class="pay-main">
            <section class="pay-panel pay-offer">
                <div class="pay-offer-heading">@if($isAddon)<span class="pay-tag">ADD-ON</span>@endif<span class="pay-tag pay-tag--quiet">One-time payment</span></div>
                <h2>{{ $isAddon ? 'Credit pack' : 'TraKerja Premium' }}</h2>
                <p>{{ $isAddon ? 'Added to your existing balances after payment is verified.' : 'Track your applications and prepare with your CV and AI tools.' }}</p>
                @if($isAddon)
                    <div class="pay-credit-grid">
                        @foreach($credits as $key => $quantity)<div><strong>{{ $quantity }}</strong><span>{{ ['ai_credits' => 'AI credits', 'cl_credits' => 'cover letters', 'photo_credits' => 'photo credits'][$key] }}</span></div>@endforeach
                    </div>
                    <p class="pay-small">The pack includes all three benefits, whichever tool you start with.</p>
                @else
                    <div class="pay-features">
                        @foreach(['One place to track your applications', 'CV templates and export tools', 'AI analysis, cover letters and photo tools', 'Weekly goals and career insights'] as $feature)<div><span aria-hidden="true">✓</span>{{ $feature }}</div>@endforeach
                    </div>
                    @if(!$freeAccess)
                        <div class="pay-starting"><span>Starting credits</span><div>@foreach($credits as $key => $quantity)<span>{{ $quantity === 'unlimited' ? 'Unlimited '.['ai_credits' => 'AI usage', 'cl_credits' => 'cover letters', 'photo_credits' => 'photo usage'][$key] : \App\Support\PaymentPresentation::benefit($key, (int)$quantity) }}</span>@endforeach</div></div>
                        <p class="pay-small">Activation sets your starting credit balances. Additional credits are available separately.</p>
                    @endif
                @endif
            </section>
            <section class="pay-panel">
                <fieldset class="pay-methods"><legend>How would you like to pay?</legend><p>Payment instructions open on Pakasir after you continue.</p>
                    <label class="pay-method"><input type="radio" name="payment_channel_code" value="qris" x-model="method" required @checked(old('payment_channel_code') === 'qris')><span class="pay-method-mark" aria-hidden="true"><i class="ph ph-qr-code"></i></span><span><strong>QRIS</strong><small>Scan with your banking or e-wallet app</small></span><span class="pay-radio" aria-hidden="true"></span></label>
                    <label class="pay-method"><input type="radio" name="payment_channel_code" value="bank_transfer" x-model="method" required @checked(old('payment_channel_code') === 'bank_transfer')><span class="pay-method-mark" aria-hidden="true"><i class="ph ph-bank"></i></span><span><strong>Bank transfer</strong><small>Choose your bank on Pakasir</small></span><span class="pay-radio" aria-hidden="true"></span></label>
                </fieldset>
            </section>
        </div>
        <aside class="pay-panel pay-summary">
            <h2>Order summary</h2><div class="pay-summary-product"><strong>{{ $isAddon ? 'Credit pack' : 'TraKerja Premium' }}</strong><span>{{ $isAddon ? 'Added credits · one purchase' : ($days >= 3650 ? 'Lifetime' : number_format($days).' days of membership') }}</span></div>
            <dl class="pay-facts"><div><dt>Account</dt><dd>{{ auth()->user()->email }}</dd></div><div><dt>Payment</dt><dd x-text="method === 'qris' ? 'QRIS' : (method ? 'Bank transfer' : 'Select a method')">Select a method</dd></div></dl>
            @if($isAddon)<ul class="pay-bundle">@foreach($credits as $key => $quantity)<li>{{ \App\Support\PaymentPresentation::benefit($key, $quantity) }}</li>@endforeach</ul>@endif
            <div class="pay-total"><span>Total</span><strong>Rp {{ number_format($price, 0, ',', '.') }}</strong></div>
            <button class="pay-button pay-button--primary" type="submit" :disabled="submitting"><span x-text="submitting ? 'Opening checkout…' : 'Continue to payment'">Continue to payment</span><span aria-hidden="true">↗</span></button>
            <p class="pay-small">{{ $isAddon ? 'Credits are added after provider verification.' : 'Membership starts after provider verification. This purchase does not renew automatically.' }}</p>
            <a class="pay-summary-help" href="{{ route('support.index') }}" wire:navigate>Have a question? Contact support</a>
        </aside>
    </form>
</div>
