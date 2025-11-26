<x-admin-layout>

    <div class="space-y-6">
        @php
            $totalUsers = \App\Models\User::where('role', '!=', 'admin')->count();
            $verifiedUsers = \App\Models\User::where('role', '!=', 'admin')->whereNotNull('email_verified_at')->count();
            $activeUsers = \App\Models\User::where('role', '!=', 'admin')
                ->where(function($query) {
                    $query->whereHas('experiences')
                        ->orWhereHas('educations')
                        ->orWhereHas('skills');
                })
                ->count();
            $totalApplications = \App\Models\JobApplication::count();
            $verifiedRatio = $totalUsers > 0 ? round(($verifiedUsers / $totalUsers) * 100) : 0;
            $recentApplications = \App\Models\JobApplication::with('user')->latest()->take(5)->get();
            $totalGoals = \App\Models\UserGoal::count();
            $achievedGoals = \App\Models\UserGoal::where('is_achieved', true)->count();
            $totalExports = \App\Models\User::sum('cv_exports_this_month') ?? 0;
            $newUsersToday = \App\Models\User::where('role', '!=', 'admin')->whereDate('created_at', \Carbon\Carbon::today('Asia/Jakarta'))->count();
            $newUsersWeek = \App\Models\User::where('role', '!=', 'admin')->where('created_at', '>=', \Carbon\Carbon::now('Asia/Jakarta')->subWeek())->count();
        @endphp

        <section class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <article class="rounded-2xl border border-slate-100 bg-white px-4 py-3 shadow-sm">
                <p class="text-[10px] uppercase tracking-[0.4em] text-slate-400">Total Users</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($totalUsers) }}</p>
                <p class="mt-1 text-[11px] text-slate-500">{{ number_format($verifiedUsers) }} verified ({{ $verifiedRatio }}%)</p>
            </article>

            <article class="rounded-2xl border border-slate-100 bg-white px-4 py-3 shadow-sm">
                <p class="text-[10px] uppercase tracking-[0.4em] text-slate-400">Verified Users</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($verifiedUsers) }}</p>
                <p class="mt-1 text-[11px] text-slate-500">Akun siap diverifikasi/disetujui.</p>
            </article>

            <article class="rounded-2xl border border-slate-100 bg-white px-4 py-3 shadow-sm">
                <p class="text-[10px] uppercase tracking-[0.4em] text-slate-400">Active Users</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($activeUsers) }}</p>
                <p class="mt-1 text-[11px] text-slate-500">Profil lengkap pengalaman/skill.</p>
            </article>

            <article class="rounded-2xl border border-slate-100 bg-white px-4 py-3 shadow-sm">
                <p class="text-[10px] uppercase tracking-[0.4em] text-slate-400">Applications</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($totalApplications) }}</p>
                <p class="mt-1 text-[11px] text-slate-500">Status siap diproses.</p>
            </article>
        </section>

        <section class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-2xl border border-slate-100 bg-white shadow-sm">
                <div class="px-5 py-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-100 text-slate-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Recent Job Applications</h3>
                            <p class="text-sm text-slate-500">Latest 5 applications</p>
                        </div>
                    </div>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($recentApplications as $application)
                        <div class="px-5 py-4 hover:bg-slate-50 transition-colors">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-900 truncate">{{ $application->position }}</p>
                                    <p class="text-xs text-slate-500 mt-1">{{ $application->company }}</p>
                                    <p class="text-[11px] text-slate-400 mt-1">Anonim • {{ $application->created_at->diffForHumans() }}</p>
                                </div>
                                <div>
                                    @php
                                        $statusClasses = [
                                            'applied' => 'bg-blue-100 text-blue-800',
                                            'interview' => 'bg-yellow-100 text-yellow-800',
                                            'accepted' => 'bg-emerald-100 text-emerald-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                        ];
                                        $statusLabel = $application->status;
                                        $statusClass = $statusClasses[$statusLabel] ?? 'bg-slate-100 text-slate-700';
                                    @endphp
                                    <span class="px-2 py-1 text-[11px] font-semibold rounded-full {{ $statusClass }}">
                                        {{ ucfirst($statusLabel) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-10 text-center text-sm text-slate-500">
                            No applications yet
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white shadow-sm">
                <div class="px-5 py-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">System Overview</h3>
                            <p class="text-sm text-slate-500">Key metrics at a glance</p>
                        </div>
                    </div>
                </div>
                <div class="p-5 space-y-4">
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3">
                            <p class="text-[11px] uppercase tracking-[0.3em] text-slate-400">New Users Today</p>
                            <p class="mt-2 text-xl font-semibold text-slate-900">{{ $newUsersToday }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3">
                            <p class="text-[11px] uppercase tracking-[0.3em] text-slate-400">New Users This Week</p>
                            <p class="mt-2 text-xl font-semibold text-slate-900">{{ $newUsersWeek }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3">
                            <p class="text-[11px] uppercase tracking-[0.3em] text-slate-400">CV Exports</p>
                            <p class="mt-2 text-xl font-semibold text-slate-900">{{ number_format($totalExports) }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3">
                            <p class="text-[11px] uppercase tracking-[0.3em] text-slate-400">Goals Achievement</p>
                            <p class="mt-2 text-xl font-semibold text-slate-900">{{ $totalGoals > 0 ? round(($achievedGoals / $totalGoals) * 100, 1) : 0 }}%</p>
                        </div>
                    </div>
                    <div class="border-t border-slate-100 pt-4 text-sm text-slate-600 space-y-2">
                        <div class="flex items-center justify-between">
                            <span>Total Goals Created</span>
                            <span class="font-semibold text-slate-900">{{ number_format($totalGoals) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Goals Achieved</span>
                            <span class="font-semibold text-emerald-600">{{ number_format($achievedGoals) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-admin-layout>
