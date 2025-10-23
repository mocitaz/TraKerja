<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-primary-800 leading-tight flex items-center gap-2">
            <span>🛠️</span>
            <span>{{ __('Admin Dashboard') }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Quick Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-primary-600 mb-1">Total Users</p>
                            <p class="text-3xl font-bold text-primary-900">{{ \App\Models\User::count() }}</p>
                        </div>
                        <div class="text-4xl">👥</div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-primary-600 mb-1">Premium Users</p>
                            <p class="text-3xl font-bold text-primary-600">{{ \App\Models\User::where('is_premium', true)->count() }}</p>
                        </div>
                        <div class="text-4xl">💎</div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-primary-600 mb-1">Current Phase</p>
                            <p class="text-3xl font-bold text-secondary-600">{{ current_phase() }}</p>
                        </div>
                        <div class="text-4xl">{{ phase_emoji() }}</div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-primary-600 mb-1">Job Applications</p>
                            <p class="text-3xl font-bold text-primary-600">{{ \App\Models\JobApplication::count() }}</p>
                        </div>
                        <div class="text-4xl">📊</div>
                    </div>
                </div>
            </div>

            {{-- Monetization Control Component --}}
            <div class="mb-6">
                @livewire('admin.monetization-control')
            </div>

            {{-- Phase-based User Stats --}}
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <span>📈</span>
                    <span>User Registration by Phase</span>
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @php
                        $phase1Users = \App\Models\User::where('registered_phase', 1)->count();
                        $phase2Users = \App\Models\User::where('registered_phase', 2)->count();
                        $phase3Users = \App\Models\User::where('registered_phase', 3)->count();
                        $totalUsers = \App\Models\User::count();
                    @endphp

                    <div class="p-6 bg-primary-50 rounded-lg border-2 border-primary-200">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-2xl">🟢</span>
                            <h4 class="font-bold text-lg">Phase 1 Users</h4>
                        </div>
                        <p class="text-sm text-primary-600 mb-2">Early Adopters</p>
                        <p class="text-3xl font-bold text-primary-600">{{ number_format($phase1Users) }}</p>
                        @if($totalUsers > 0)
                            <p class="text-xs text-primary-500 mt-1">{{ number_format($phase1Users / $totalUsers * 100, 1) }}% of total</p>
                        @endif
                        <p class="text-xs text-primary-600 mt-2">
                            🎁 Have: 3 CV templates FREE + 50% discount
                        </p>
                    </div>

                    <div class="p-6 bg-secondary-50 rounded-lg border-2 border-secondary-200">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-2xl">🟡</span>
                            <h4 class="font-bold text-lg">Phase 2 Users</h4>
                        </div>
                        <p class="text-sm text-primary-600 mb-2">Soft Premium Era</p>
                        <p class="text-3xl font-bold text-secondary-600">{{ number_format($phase2Users) }}</p>
                        @if($totalUsers > 0)
                            <p class="text-xs text-primary-500 mt-1">{{ number_format($phase2Users / $totalUsers * 100, 1) }}% of total</p>
                        @endif
                        <p class="text-xs text-primary-600 mt-2">
                            1 CV template FREE, upgrade for 5
                        </p>
                    </div>

                    <div class="p-6 bg-primary-50 rounded-lg border-2 border-primary-200">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-2xl">🔵</span>
                            <h4 class="font-bold text-lg">Phase 3 Users</h4>
                        </div>
                        <p class="text-sm text-primary-600 mb-2">Full Premium Era</p>
                        <p class="text-3xl font-bold text-primary-600">{{ number_format($phase3Users) }}</p>
                        @if($totalUsers > 0)
                            <p class="text-xs text-primary-500 mt-1">{{ number_format($phase3Users / $totalUsers * 100, 1) }}% of total</p>
                        @endif
                        <p class="text-xs text-primary-600 mt-2">
                            1 template + 3 exports/month FREE
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
