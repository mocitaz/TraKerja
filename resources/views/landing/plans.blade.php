@php
    $monetization = \App\Models\Setting::isMonetizationEnabled();
    $premiumPrice = max(0, (int) \App\Models\Setting::get('premium_price', 9999));
    $addon = \App\Support\PaymentPackages::ADDON_BENEFITS;
    $plans = [
        [
            'type' => 'free', 'name' => __('landing.plans.free'), 'intro' => __('landing.plans.free_intro'), 'price' => 0,
            'basis' => __('landing.plans.free_basis'), 'action' => __('landing.plans.free_action'), 'url' => $workspaceUrl, 'available' => true,
            'features' => [
                $monetization ? __('landing.plans.applications', ['count' => \App\Models\Setting::getLimit('job_applications')]) : __('landing.plans.premium_features')[0],
                $monetization ? __('landing.plans.templates', ['count' => \App\Models\Setting::getLimit('cv_templates')]) : __('landing.plans.premium_features')[1],
                __('landing.plans.calendar'), __('landing.plans.portfolio'),
            ],
            'terms' => __('landing.plans.free_terms'),
        ],
        [
            'type' => 'premium', 'name' => __('landing.plans.premium'), 'intro' => __('landing.plans.premium_intro'), 'price' => $premiumPrice,
            'basis' => __('landing.plans.premium_basis'), 'action' => __('landing.plans.premium_action'), 'url' => route('payment.index'), 'available' => $monetization,
            'features' => __('landing.plans.premium_features'),
            'terms' => __('landing.plans.lifetime').' · '.__('landing.plans.credit_note'),
        ],
        [
            'type' => 'credits', 'name' => __('landing.plans.credits'), 'intro' => __('landing.plans.credits_intro'), 'price' => \App\Support\PaymentPackages::ADDON_PRICE,
            'basis' => __('landing.plans.credits_basis'), 'action' => __('landing.plans.addon_action'), 'url' => route('payment.topup'), 'available' => $monetization,
            'features' => [__('landing.plans.reviews', ['count' => $addon['ai_credits']]), __('landing.plans.letters', ['count' => $addon['cl_credits']]), __('landing.plans.photos', ['count' => $addon['photo_credits']])],
            'terms' => __('landing.plans.credits_terms'),
        ],
    ];
@endphp
<section class="lp-plans-section" id="pricing" data-reveal><div class="lp-wrap lp-section">
    <div class="lp-section-top"><div><p class="lp-eyebrow">{{ __('landing.plans.eyebrow') }}</p>@include('landing.heading', ['copy' => 'plans.title'])</div><p class="lp-section-intro">{{ __('landing.plans.intro') }}</p></div>
    @unless($monetization)<p class="lp-plan-notice">{{ __('landing.plans.paused') }}</p>@endunless
    <div class="lp-plan-grid" @if($monetization) data-paid-plan @endif>
        @foreach($plans as $plan)
            <article class="lp-plan lp-plan--{{ $plan['type'] }}">
                <div class="lp-plan-head">
                    <h3>{{ $plan['name'] }}</h3>
                    <div class="lp-plan-rate"><strong class="lp-plan-price">Rp {{ number_format($plan['price'], 0, ',', '.') }}</strong><span>{{ $plan['basis'] }}</span></div>
                    <p class="lp-plan-intro">{{ $plan['intro'] }}</p>
                </div>
                <div class="lp-plan-body">
                    <ul>@foreach($plan['features'] as $feature)<li>@include('landing.icon', ['name' => 'check'])<span>{{ $feature }}</span></li>@endforeach</ul>
                    @if($plan['available'])
                        <a class="lp-button {{ $plan['type'] === 'premium' ? 'lp-button--dark' : 'lp-button--outline' }}" href="{{ $plan['url'] }}">{{ $plan['action'] }} @include('landing.icon', ['name' => 'arrow'])</a>
                    @else
                        <p class="lp-plan-availability">{{ __('landing.plans.unavailable') }}</p>
                    @endif
                    <p class="lp-plan-terms">{{ $plan['terms'] }}</p>
                </div>
            </article>
        @endforeach
    </div>
    @guest<a class="lp-plans-login" href="{{ route('login') }}">{{ __('landing.plans.account') }}</a>@endguest
</div></section>
