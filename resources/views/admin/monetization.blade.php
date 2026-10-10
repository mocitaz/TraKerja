<x-admin-layout>
@php
    $premiumPrice = \App\Models\Setting::get('premium_price',199000);
    $members = \App\Models\User::where('role','!=','admin');
    $totalUsers = (clone $members)->count();
    $premiumUsers = (clone $members)->where('is_premium',true)->count();
    $freeUsers = $totalUsers-$premiumUsers;
    $revenue = \App\Models\Payment::where('status','SUCCESS')->sum('amount');
@endphp
<div class="admin-operations">
    <header class="ops-page-heading"><div><h1>Monetization</h1><p>Set the price and choose how members access TraKerja features.</p></div><a class="ops-button ops-button--quiet" href="{{ route('admin.payments') }}">Payment history</a></header>
    @if(session('success'))<div class="ops-notice" role="status">{{ session('success') }}</div>@endif
    <div class="ops-metric-strip"><div><span>Members</span><strong class="admin-number--blue">{{ number_format($totalUsers) }}</strong><small>Non-admin accounts</small></div><div><span>Premium assigned</span><strong class="admin-number--purple">{{ number_format($premiumUsers) }}</strong><small>Includes expired assignments</small></div><div><span>Free assigned</span><strong class="admin-number--pink">{{ number_format($freeUsers) }}</strong><small>Accounts without premium assigned</small></div><div><span>Successful payments</span><strong class="ops-money admin-number--orange">Rp {{ number_format($revenue,0,',','.') }}</strong><small>Total recorded revenue</small></div></div>
    <section class="ops-panel ops-price-panel"><div class="ops-section-heading"><h2>Premium pricing</h2><p>Current checkout price: <strong>Rp {{ number_format($premiumPrice,0,',','.') }}</strong></p></div><form method="POST" action="{{ route('admin.update-premium-price') }}" class="ops-inline-form">@csrf @method('PUT')<label>Price in IDR<input type="number" name="premium_price" value="{{ old('premium_price',$premiumPrice) }}" min="0" step="1000" required></label><button type="submit" class="ops-button">Save price</button>@error('premium_price')<p role="alert" class="text-red-600 text-xs">{{ $message }}</p>@enderror</form></section>
    @livewire('admin.monetization-control')
</div>
</x-admin-layout>
