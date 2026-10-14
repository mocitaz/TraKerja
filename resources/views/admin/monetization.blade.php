<x-admin-layout>
@php
    $premiumPrice = \App\Models\Setting::get('premium_price', 199000);
    $members = \App\Models\User::where('role', '!=', 'admin');
    $totalUsers = (clone $members)->count();
    $premiumUsers = (clone $members)->where('is_premium', true)->count();
    $freeUsers = $totalUsers - $premiumUsers;
    $revenue = \App\Models\Payment::where('status', 'SUCCESS')->sum('amount');
@endphp

<div class="admin-operations admin-monetization-workspace">

    {{-- ==========================================================================
         PAGE HEADER
         ========================================================================== --}}
    <section class="am-welcome">
        <div>
            <h1>Monetization & Plans</h1>
            <p>Set subscription pricing, switch member access modes, and review feature entitlements.</p>
        </div>
        <div class="am-header-actions">
            <a class="am-btn am-btn--quiet" href="{{ route('admin.payments') }}">
                <i class="ph ph-receipt" aria-hidden="true"></i>
                <span>Payment history</span>
            </a>
        </div>
    </section>

    {{-- ==========================================================================
         FLASH ALERTS
         ========================================================================== --}}
    @if(session('success'))
        <div class="am-notice" role="status">
            <i class="ph-fill ph-check-circle" aria-hidden="true"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- ==========================================================================
         METRICS STRIP (Biru, Ungu, Orange)
         ========================================================================== --}}
    <section class="am-metric-strip" aria-label="Monetization overview metrics">
        {{-- Card 1: Biru - Total Members --}}
        <div class="am-metric-card am-metric-card--blue">
            <div class="am-metric-head">
                <span class="am-metric-label">
                    <span class="am-metric-icon"><i class="ph ph-users" aria-hidden="true"></i></span>
                    Members
                </span>
            </div>
            <strong class="am-metric-value">{{ number_format($totalUsers) }}</strong>
            <span class="am-metric-sub">Non-admin accounts</span>
        </div>

        {{-- Card 2: Ungu - Premium Assigned --}}
        <div class="am-metric-card am-metric-card--purple">
            <div class="am-metric-head">
                <span class="am-metric-label">
                    <span class="am-metric-icon"><i class="ph ph-crown-simple" aria-hidden="true"></i></span>
                    Premium assigned
                </span>
            </div>
            <strong class="am-metric-value">{{ number_format($premiumUsers) }}</strong>
            <span class="am-metric-sub">Active premium accounts</span>
        </div>

        {{-- Card 3: Orange - Free Assigned --}}
        <div class="am-metric-card am-metric-card--orange">
            <div class="am-metric-head">
                <span class="am-metric-label">
                    <span class="am-metric-icon"><i class="ph ph-user" aria-hidden="true"></i></span>
                    Free assigned
                </span>
            </div>
            <strong class="am-metric-value">{{ number_format($freeUsers) }}</strong>
            <span class="am-metric-sub">Standard & trial accounts</span>
        </div>

        {{-- Card 4: Signature Ungu - Recorded Revenue --}}
        <div class="am-metric-card am-metric-card--revenue">
            <div class="am-metric-head">
                <span class="am-metric-label">
                    <span class="am-metric-icon"><i class="ph ph-wallet" aria-hidden="true"></i></span>
                    Checkout Revenue
                </span>
            </div>
            <strong class="am-metric-value">Rp {{ number_format($revenue, 0, ',', '.') }}</strong>
            <span class="am-metric-sub">Successful payments only</span>
        </div>
    </section>

    {{-- ==========================================================================
         PREMIUM PRICING CARD
         ========================================================================== --}}
    <section class="am-panel" aria-labelledby="pricing-panel-heading">
        <div class="am-panel-heading">
            <div>
                <h2 id="pricing-panel-heading">Premium Subscription Pricing</h2>
                <p>Manage the checkout price for member upgrades and premium lifetime packages</p>
            </div>
        </div>
        <div class="am-pricing-content">
            <div class="am-pricing-info">
                <span class="am-pricing-current">Active Checkout Price</span>
                <div class="am-pricing-badge">
                    <span>Rp</span> {{ number_format($premiumPrice, 0, ',', '.') }}
                </div>
            </div>

            <form method="POST" action="{{ route('admin.update-premium-price') }}" class="am-pricing-form">
                @csrf
                @method('PUT')
                <div class="am-input-group">
                    <span class="am-input-prefix">Rp</span>
                    <input type="number" 
                           name="premium_price" 
                           class="am-input-field" 
                           value="{{ old('premium_price', $premiumPrice) }}" 
                           min="0" 
                           step="1000" 
                           placeholder="199000" 
                           required 
                           aria-label="Price in Indonesian Rupiah">
                </div>
                <button type="submit" class="am-btn am-btn--primary">
                    <i class="ph ph-floppy-disk" aria-hidden="true"></i>
                    <span>Save price</span>
                </button>
                @error('premium_price')
                    <p role="alert" class="text-red-600 text-xs w-full mt-1">{{ $message }}</p>
                @enderror
            </form>
        </div>
    </section>

    {{-- ==========================================================================
         LIVEWIRE: MONETIZATION ACCESS CONTROL & MATRIX
         ========================================================================== --}}
    @livewire('admin.monetization-control')

</div>
</x-admin-layout>
