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
        <div><a class="pay-back" href="{{ route('membership.index') }}" wire:navigate>{{ __('← Membership & credits') }}</a>
            <h1>{{ $isAddon ? __('Add more credits') : __('Upgrade to Premium') }}</h1>
            <p>{{ $isAddon ? __('More preparation, in one credit pack.') : __('Your next move, with more room to prepare.') }}</p></div>
        <a class="pay-link" href="{{ route('payment.history') }}" wire:navigate>{{ __('Payment history ↗') }}</a>
    </header>
    @if($errors->any())
        <div class="pay-notice pay-notice--error" role="alert"><strong>{{ __('We couldn’t start checkout.') }}</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    @if($freeAccess)
        <div class="pay-notice">{{ __('Free access is currently enabled. Premium features are available without a paid membership.') }}</div>
    @endif
    <form class="pay-checkout-grid" method="POST" action="{{ route('payment.checkout') }}" x-data="paymentCheckout(@js(old('payment_channel_code', '')))" @submit="submit($event)" :aria-busy="submitting">
        @csrf
        <input type="hidden" name="package_type" value="{{ $packageType ?? 'premium' }}">
        <div class="pay-main">
            <section class="pay-panel pay-offer">
                <div class="pay-offer-heading"><span class="pay-eyebrow">{{ $isAddon ? __('YOUR CREDIT BOOST') : __('YOUR CAREER TOOLKIT') }}</span><span class="pay-tag pay-tag--quiet">{{ __('One-time payment') }}</span></div>
                <h2>{{ $isAddon ? __('Credit pack') : __('TraKerja Premium') }}</h2>
                <p>{{ $isAddon ? __('Added to your existing balances after payment is verified.') : __('Track your applications and prepare with your CV and AI tools.') }}</p>
                <div class="pay-mobile-price"><strong>{{ __('Rp :value1', ['value1' => number_format($price, 0, ',', '.')]) }}</strong><span>{{ $isAddon ? __('One-time payment') : ($days >= 3650 ? __('Lifetime') : number_format($days).__(' days of membership')) }}</span></div>
                @if($isAddon)
                    <div class="pay-credit-grid" aria-label="{{ __('Included credits') }}">
                        @foreach($credits as $key => $quantity)<div role="group" aria-label="{{ \App\Support\PaymentPresentation::benefit($key, $quantity) }}"><strong>{{ $quantity }}</strong><span>{{ ['ai_credits' => __('AI credits'), 'cl_credits' => __('cover letters'), 'photo_credits' => __('photo credits')][$key] }}</span></div>@endforeach
                    </div>

                @else
                    <div class="pay-features">
                        @foreach(['One place to track your applications', 'CV templates and export tools', 'AI analysis, cover letters and photo tools', 'Weekly goals and career insights'] as $feature)<div><span aria-hidden="true">✓</span>{{ __($feature) }}</div>@endforeach
                    </div>
                    @if(!$freeAccess)
                        <div class="pay-starting"><span>{{ __('Starting credits') }}</span><div>@foreach($credits as $key => $quantity)<span>{{ $quantity === 'unlimited' ? __('Unlimited ').['ai_credits' => __('AI usage'), 'cl_credits' => __('cover letters'), 'photo_credits' => __('photo usage')][$key] : \App\Support\PaymentPresentation::benefit($key, (int)$quantity) }}</span>@endforeach</div></div>
                        <p class="pay-small">{{ __('Activation sets your starting credit balances. Additional credits are available separately.') }}</p>
                    @endif
                @endif
            </section>
            <section class="pay-panel">
                <fieldset class="pay-methods"><legend>{{ __('Payment method') }}</legend><p>{{ __('Choose a method. Complete payment on Pakasir.') }}</p>
                    <label class="pay-method"><input type="radio" name="payment_channel_code" value="qris" x-model="method" required @checked(old('payment_channel_code') === 'qris')><span class="pay-method-mark" aria-hidden="true"><i class="ph ph-qr-code"></i></span><span><strong>{{ __('QRIS') }}</strong><small>{{ __('Scan with your banking or e-wallet app') }}</small></span><span class="pay-radio" aria-hidden="true"></span></label>
                    <label class="pay-method"><input type="radio" name="payment_channel_code" value="bank_transfer" x-model="method" required @checked(old('payment_channel_code') === 'bank_transfer')><span class="pay-method-mark" aria-hidden="true"><i class="ph ph-bank"></i></span><span><strong>{{ __('Bank transfer') }}</strong><small>{{ __('Choose your bank on Pakasir') }}</small></span><span class="pay-radio" aria-hidden="true"></span></label>
                </fieldset>
            </section>
        </div>
        <aside class="pay-panel pay-summary">
            <h2>{{ __('Order summary') }}</h2><div class="pay-summary-product"><strong>{{ $isAddon ? __('Credit pack') : __('TraKerja Premium') }}</strong><span>{{ $isAddon ? __('Added credits · one purchase') : ($days >= 3650 ? __('Lifetime') : number_format($days).__(' days of membership')) }}</span></div>
            <dl class="pay-facts"><div><dt>{{ __('Account') }}</dt><dd>{{ auth()->user()->email }}</dd></div><div><dt>{{ __('Payment') }}</dt><dd x-text="method === 'qris' ? @js(__('QRIS')) : (method ? @js(__('Bank transfer')) : @js(__('Select a method')))">{{ __('Select a method') }}</dd></div></dl>

            <div class="pay-total"><span>{{ __('Total') }}</span><strong>{{ __('Rp :value1', ['value1' => (number_format($price, 0, ',', '.'))]) }}</strong></div>
            <button class="pay-button pay-button--primary" type="submit" :disabled="submitting"><span x-text="submitting ? @js(__('Opening checkout…')) : @js(__('Continue to payment'))">{{ __('Continue to payment') }}</span><span aria-hidden="true">↗</span></button>
            <p class="pay-small">{{ $isAddon ? __('Credits are added after provider verification.') : __('Membership starts after provider verification. This purchase does not renew automatically.') }}</p>
            <div class="pay-provider"><i class="ph ph-arrow-square-out" aria-hidden="true"></i>{{ __('Checkout by Pakasir') }}</div>
            <a class="pay-summary-help" href="{{ route('support.index') }}" wire:navigate>{{ __('Have a question? Contact support') }}</a>
        </aside>
    </form>
</div>
