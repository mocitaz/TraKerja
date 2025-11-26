<x-admin-layout>
    @php
        $monetizationEnabled = \App\Models\Setting::get('monetization_enabled', false);
        $premiumPrice = \App\Models\Setting::get('premium_price', 199000);
        $totalUsers = \App\Models\User::where('role', '!=', 'admin')->count();
        $freeUsers = \App\Models\User::where('role', '!=', 'admin')->where('is_premium', false)->count();
        $premiumUsers = \App\Models\User::where('role', '!=', 'admin')->where('is_premium', true)->count();
        $revenue = $premiumUsers * $premiumPrice;
    @endphp

    <div class="min-h-screen w-full bg-slate-100 px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl space-y-6">
            @if(session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <section class="rounded-2xl border border-slate-200 bg-white px-6 py-6 shadow-sm">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="space-y-2">
                        <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Monetization Status</p>
                        <h1 class="text-2xl font-semibold text-slate-900">Control Room</h1>
                        <p class="text-sm text-slate-500">
                            @if($monetizationEnabled)
                                Premium features are monetized • Current price Rp {{ number_format($premiumPrice, 0, ',', '.') }}
                            @else
                                All features are free for everyone • No payment required
                            @endif
                        </p>
                        <div class="flex items-center gap-2">
                            <span class="rounded-full px-3 py-1 text-xs font-semibold uppercase {{ $monetizationEnabled ? 'bg-purple-100 text-purple-800' : 'bg-emerald-100 text-emerald-800' }}">
                                {{ $monetizationEnabled ? 'PREMIUM MODE ACTIVE' : 'FREE MODE ACTIVE' }}
                            </span>
                        <a href="{{ route('admin.payments') }}" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-purple-100 via-purple-100 to-purple-200 px-4 py-2 text-sm font-semibold text-purple-900 shadow-sm shadow-purple-200/60 transition hover:-translate-y-0.5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-purple-100">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                Payment Monitoring
                            </a>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-4">
                        <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/80 text-slate-900">
                                @if($monetizationEnabled)
                                    <svg class="h-5 w-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 3l3 7-5 4 6 1 3 7 3-7 6-1-5-4 3-7-6 1-3-7-3 7z" />
                                    </svg>
                                @else
                                    <svg class="h-5 w-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4 6-6 4 4" />
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Mode</p>
                                <p class="font-semibold text-slate-900">{{ $monetizationEnabled ? 'Premium' : 'Free' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/80 text-slate-900">
                                <svg class="h-5 w-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Users</p>
                                <p class="font-semibold text-slate-900">{{ number_format($totalUsers) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 grid gap-3 md:grid-cols-2 lg:grid-cols-4">
                    <article class="rounded-2xl border border-slate-100 bg-white px-4 py-3 shadow-sm">
                        <p class="text-[10px] uppercase tracking-[0.4em] text-slate-400">Total Users</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($totalUsers) }}</p>
                        <p class="text-[11px] text-slate-500">All registered</p>
                    </article>
                    <article class="rounded-2xl border border-slate-100 bg-white px-4 py-3 shadow-sm">
                        <p class="text-[10px] uppercase tracking-[0.4em] text-slate-400">Free Users</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($freeUsers) }}</p>
                        <p class="text-[11px] text-slate-500">{{ $totalUsers > 0 ? number_format($freeUsers / $totalUsers * 100, 1) : 0 }}% of total</p>
                    </article>
                    <article class="rounded-2xl border border-slate-100 bg-white px-4 py-3 shadow-sm">
                        <p class="text-[10px] uppercase tracking-[0.4em] text-slate-400">Premium Users</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($premiumUsers) }}</p>
                        <p class="text-[11px] text-slate-500">{{ $totalUsers > 0 ? number_format($premiumUsers / $totalUsers * 100, 1) : 0 }}% conversion</p>
                    </article>
                    <article class="rounded-2xl border border-slate-100 bg-amber-50 px-4 py-3 shadow-sm">
                        <p class="text-[10px] uppercase tracking-[0.4em] text-amber-600">Total Revenue</p>
                        <p class="mt-2 text-2xl font-bold text-amber-900">Rp {{ number_format($revenue / 1000000, 1) }}M</p>
                        <p class="text-[11px] text-amber-600">From premium subs</p>
                    </article>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white px-6 py-6 shadow-sm">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div class="space-y-1">
                        <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Premium Pricing</p>
                        <h2 class="text-xl font-semibold text-slate-900">Set subscription price</h2>
                        <p class="text-sm text-slate-500">Recommend a fair premium tier.</p>
                    </div>
                    <div class="rounded-full bg-purple-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.3em] text-purple-800">
                        Current: Rp {{ number_format($premiumPrice, 0, ',', '.') }}
                    </div>
                </div>
                <form action="{{ route('admin.update-premium-price') }}" method="POST" class="mt-6 space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Premium Price (IDR)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 font-semibold">Rp</span>
                                <input type="number" name="premium_price" value="{{ $premiumPrice }}"
                                       class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-12 pr-4 text-lg font-bold text-slate-900 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-300"
                                       min="0" step="1000" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Suggested Pricing</label>
                            <div class="grid gap-2">
                                <button type="button" onclick="document.querySelector('input[name=premium_price]').value = 99000" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-900 transition hover:border-purple-300">
                                    <span class="font-semibold">Rp 99.000</span> • Budget friendly
                                </button>
                                <button type="button" onclick="document.querySelector('input[name=premium_price]').value = 199000" class="w-full rounded-2xl border border-slate-200 bg-purple-50 px-4 py-2 text-sm text-slate-900 transition hover:border-purple-300">
                                    <span class="font-semibold">Rp 199.000</span> • Recommended
                                </button>
                                <button type="button" onclick="document.querySelector('input[name=premium_price]').value = 299000" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-900 transition hover:border-purple-300">
                                    <span class="font-semibold">Rp 299.000</span> • Premium tier
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <button type="submit" class="rounded-2xl bg-gradient-to-r from-purple-100 via-purple-100 to-purple-200 px-6 py-3 text-sm font-semibold text-purple-900 shadow-sm shadow-purple-200/60 transition hover:-translate-y-0.5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-purple-100">
                            Update Premium Price
                        </button>
                        <a href="{{ route('admin.index') }}" class="rounded-2xl border border-purple-200 px-6 py-3 text-sm font-semibold text-purple-800 transition hover:border-purple-300">
                            Cancel
                        </a>
                    </div>
                </form>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white px-6 py-6 shadow-sm">
                <div class="grid gap-6 lg:grid-cols-3">
                    <article class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-5 text-sm">
                        <p class="text-xs uppercase tracking-[0.4em] text-slate-400 mb-2">Free Mode Active</p>
                        <ul class="space-y-2 text-slate-600">
                            <li class="flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-purple-600"></span>
                                All features FREE for everyone
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-purple-600"></span>
                                No payment required
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-purple-600"></span>
                                Unlimited access to all tools
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-purple-600"></span>
                                Currently ACTIVE
                            </li>
                        </ul>
                    </article>
                    <article class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-5 text-sm">
                        <p class="text-xs uppercase tracking-[0.4em] text-slate-400 mb-2">Premium Mode</p>
                        <ul class="space-y-2 text-slate-600">
                            <li class="flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-purple-600"></span>
                                Monetization active when enabled
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-purple-600"></span>
                                Free tier with smart limits
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-purple-600"></span>
                                Premium tier with full access
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-purple-600"></span>
                                Generate revenue from users
                            </li>
                        </ul>
                    </article>
                    <article class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-5 text-sm space-y-2">
                        <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Totals</p>
                        <p class="text-2xl font-bold text-slate-900">{{ number_format($totalUsers) }}</p>
                        <div class="grid grid-cols-2 gap-2 text-xs text-slate-600">
                            <div>
                                <p class="font-semibold text-slate-900">{{ number_format($freeUsers) }}</p>
                                <p>Free users</p>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900">{{ number_format($premiumUsers) }}</p>
                                <p>Premium users</p>
                            </div>
                        </div>
                    </article>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white px-6 py-6 shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-purple-50 text-purple-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Monetization Control</p>
                        <h3 class="text-lg font-semibold text-slate-900">Manage pricing phases</h3>
                        <p class="text-sm text-slate-500">Livewire-monitored workflow</p>
                    </div>
                </div>
                <div class="mt-5">
                    @livewire('admin.monetization-control')
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white px-6 py-6 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-100 text-slate-900">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13h4l2 2 4-4 4 4 2-2h4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900">Feature Access Matrix (Free Mode Active)</h3>
                </div>
                <p class="text-sm text-slate-500 mb-4">All features unlocked for every user.</p>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div>
                        <h4 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-3">Free Mode Active</h4>
                        <ul class="text-sm space-y-2 text-slate-600">
                            <li class="flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-purple-600"></span>
                                All features FREE for everyone
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-purple-600"></span>
                                No payment required
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-purple-600"></span>
                                Unlimited access to all tools
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-purple-600"></span>
                                Currently ACTIVE
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-3">Premium Mode</h4>
                        <ul class="text-sm space-y-2 text-slate-600">
                            <li class="flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-purple-600"></span>
                                Monetization Active
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-purple-600"></span>
                                Free tier with smart limits
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-purple-600"></span>
                                Premium tier with full access
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-purple-600"></span>
                                Generate revenue from users
                            </li>
                        </ul>
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-admin-layout>

