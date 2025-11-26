<div class="space-y-6">
    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
        <article class="rounded-2xl border border-slate-100 bg-white px-4 py-4 shadow-sm">
            <p class="text-[10px] uppercase tracking-[0.4em] text-slate-400">Total Users</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ $stats['total'] }}</p>
            <p class="mt-1 text-[11px] text-slate-500">Total pengguna non-admin</p>
        </article>
        <article class="rounded-2xl border border-slate-100 bg-white px-4 py-4 shadow-sm">
            <p class="text-[10px] uppercase tracking-[0.4em] text-slate-400">Premium Users</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ $stats['premium'] }}</p>
            <p class="mt-1 text-[11px] text-slate-500">Pengguna dengan akses premium</p>
        </article>
        <article class="rounded-2xl border border-slate-100 bg-white px-4 py-4 shadow-sm">
            <p class="text-[10px] uppercase tracking-[0.4em] text-slate-400">Free Users</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ $stats['free'] }}</p>
            <p class="mt-1 text-[11px] text-slate-500">Pengguna gratis</p>
        </article>
        <article class="rounded-2xl border border-slate-100 bg-white px-4 py-4 shadow-sm">
            <p class="text-[10px] uppercase tracking-[0.4em] text-slate-400">Verified Users</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ $stats['verified'] }}</p>
            <p class="mt-1 text-[11px] text-slate-500">Email terverifikasi</p>
        </article>
        <article class="rounded-2xl border border-slate-100 bg-white px-4 py-4 shadow-sm">
            <p class="text-[10px] uppercase tracking-[0.4em] text-slate-400">AI Trial Used</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ $stats['ai_analyzer_trial_used'] }}</p>
            <p class="mt-1 text-[11px] text-slate-500">Pengguna yang sudah coba AI Analyzer</p>
        </article>
    </div>

    {{-- Filters & Search --}}
    <div class="rounded-2xl border border-slate-100 bg-white shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Filter & Search</h3>
                        <p class="text-sm text-gray-500">Cari dan filter pengguna</p>
                    </div>
                </div>
                <button wire:click="openCreateAdminModal"
                        class="px-4 py-2 rounded-full bg-gradient-to-r from-purple-100 via-purple-100 to-purple-200 text-purple-900 shadow-sm shadow-purple-200/60 transition hover:-translate-y-0.5 flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Admin
                </button>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" wire:model.live="search" placeholder="Cari nama atau email..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Premium Status</label>
                    <select wire:model.live="filterPremium" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                        <option value="all">Semua Pengguna</option>
                        <option value="premium">Premium Saja</option>
                        <option value="free">Gratis Saja</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">AI Analyzer Trial</label>
                    <select wire:model.live="filterAiAnalyzer" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                        <option value="all">Semua</option>
                        <option value="used">Sudah Pakai Trial</option>
                        <option value="not_used">Belum Pakai Trial</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rows per page</label>
                    <select wire:model.live="perPage" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="all">All</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Users Table --}}
    <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="px-6 py-5 border-b border-slate-100 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Users</p>
                <h2 class="text-lg font-semibold text-slate-900">Daftar pengguna</h2>
            </div>
            <div class="text-xs text-slate-500">
                Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? $users->count() }} of {{ $users->total() }} users
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full table-fixed text-sm text-slate-600">
                <thead class="bg-slate-50 text-[0.65rem] uppercase tracking-[0.4em] text-slate-400">
                    <tr>
                        <th class="px-5 py-3 text-left w-[220px]">User</th>
                        <th class="px-5 py-3 text-left w-[230px]">Email</th>
                        <th class="px-5 py-3 text-left w-[120px]">Email Verified</th>
                        <th class="px-5 py-3 text-left w-[100px]">Status</th>
                        <th class="px-5 py-3 text-left w-[110px]">AI Trial</th>
                        <th class="px-5 py-3 text-left w-[100px]">Role</th>
                        <th class="px-5 py-3 text-left w-[140px]">Joined</th>
                <th class="px-5 py-3 text-right" aria-label="Actions">
                    <span class="sr-only">Actions</span>
                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/>
                    </svg>
                </th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($users as $user)
                        <tr class="border-b border-slate-100 transition hover:bg-slate-50">
                            <td class="px-5 py-4 w-[220px]">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 rounded-xl overflow-hidden bg-gradient-to-br from-primary-500 to-secondary-500 h-10 w-10 flex items-center justify-center ring-1 ring-slate-100">
                                    @if($user->logo)
                                            <img src="{{ Storage::url($user->logo) }}" alt="{{ $user->name }}" class="h-10 w-10 object-cover">
                                    @else
                                            <span class="text-white font-semibold text-sm">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        @endif
                                        </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-slate-900 truncate">{{ $user->name }}</p>
                                        <p class="text-[11px] text-slate-400 truncate">ID: #{{ $user->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 w-[230px]">
                                <p class="text-sm text-slate-900 truncate">{{ $user->email }}</p>
                            </td>
                            <td class="px-5 py-4">
                                @if($user->hasVerifiedEmail())
                                    <span class="rounded-full bg-emerald-100 px-3 py-1.5 text-[11px] font-semibold text-emerald-700 inline-flex items-center gap-1">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Verified
                                    </span>
                                @else
                                    <span class="rounded-full bg-amber-100 px-3 py-1.5 text-[11px] font-semibold text-amber-700 inline-flex items-center gap-1">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M4.93 19.07A10 10 0 1119.07 4.93"></path>
                                        </svg>
                                        Unverified
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <span class="rounded-full px-3 py-1.5 text-[11px] font-semibold {{ $user->is_premium ? 'bg-purple-100 text-purple-700' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $user->is_premium ? 'Premium' : 'Free' }}
                                    </span>
                            </td>
                            <td class="px-5 py-4">
                                @if($user->has_used_ai_analyzer_trial)
                                    <span class="rounded-full bg-blue-100 px-3 py-1.5 text-[11px] font-semibold text-blue-700 inline-flex items-center gap-1">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Used
                                        </span>
                                @else
                                    <span class="rounded-full bg-slate-100 px-3 py-1.5 text-[11px] font-semibold text-slate-600">
                                        No
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <span class="rounded-full px-3 py-1.5 text-[11px] font-semibold {{ $user->is_admin || $user->role === 'admin' ? 'bg-orange-100 text-orange-600' : 'bg-indigo-100 text-indigo-600' }}">
                                    {{ $user->is_admin || $user->role === 'admin' ? 'Admin' : 'User' }}
                                    </span>
                            </td>
                            <td class="px-5 py-4">
                                <p class="text-sm text-slate-900">{{ $user->created_at->format('d M Y') }}</p>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <button wire:click="openSendEmailModal({{ $user->id }})" class="rounded-full bg-emerald-50 p-2 text-emerald-700 hover:bg-emerald-100 transition" aria-label="Send email">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </button>
                                    <button wire:click="editUser({{ $user->id }})" class="rounded-full bg-slate-50 p-2 text-slate-700 hover:bg-slate-100 transition" aria-label="View details">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                    <button onclick="confirmDeleteUser({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}')" class="rounded-full bg-red-50 p-2 text-red-700 hover:bg-red-100 transition" aria-label="Delete user">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
                                        <svg class="h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-base font-semibold text-slate-900">Tidak Ada User Ditemukan</p>
                                    <p class="text-sm text-slate-500">Coba ubah filter atau pencarian Anda</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div class="text-xs text-slate-500">
                    Navigate through the list to find the right user.
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
    </div>
    </section>

    {{-- Edit User Modal --}}
    @if($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4">
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeModal"></div>
            <div class="relative w-full max-w-3xl rounded-3xl border border-slate-200 bg-white shadow-2xl">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                        <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-purple-50 text-purple-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                            <div>
                            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">User Details</p>
                            <h3 class="text-lg font-semibold text-slate-900">Lihat informasi pengguna</h3>
                        </div>
                    </div>
                    <button wire:click="closeModal" class="text-slate-500 hover:text-slate-900 rounded-full p-2 transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                </div>

                <div class="px-6 py-6 space-y-6">
                    @php
                        $editingUser = $editingUserId ? \App\Models\User::find($editingUserId) : null;
                    @endphp
                    
                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Profile</p>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4 flex flex-col items-center gap-3">
                                <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-br from-primary-500 to-secondary-500 text-white text-2xl font-semibold">
                    @if($editingUser && $editingUser->logo)
                                        <img src="{{ Storage::url($editingUser->logo) }}" alt="{{ $editName ?? 'User' }}" class="h-20 w-20 rounded-2xl object-cover">
                                    @else
                                        {{ strtoupper(substr($editName ?? 'U', 0, 1)) }}
                                    @endif
                                </div>
                                <div class="text-center">
                                    <p class="font-semibold text-slate-900">{{ $editName ?? '-' }}</p>
                                    <p class="text-[11px] text-slate-400">ID: #{{ $editingUser?->id ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Contact</p>
                            <div class="rounded-2xl border border-slate-100 bg-white p-4 space-y-1">
                                <label class="text-[11px] text-slate-400">Email</label>
                                <p class="text-sm font-semibold text-slate-900">{{ $editEmail ?? '-' }}</p>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-white p-4 space-y-1">
                                <label class="text-[11px] text-slate-400">Joined</label>
                                <p class="text-sm font-semibold text-slate-900">{{ $editingUser?->created_at?->format('d M Y') ?? '-' }}</p>
                                <p class="text-[11px] text-slate-400">{{ $editingUser?->created_at?->diffForHumans() ?? '-' }}</p>
                            </div>
                                </div>
                            </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <div class="rounded-2xl border border-slate-100 bg-white p-4 text-center">
                            <p class="text-[11px] uppercase tracking-[0.3em] text-slate-400 mb-2">Premium</p>
                            <span class="text-sm font-semibold {{ $editIsPremium ? 'text-purple-700' : 'text-slate-600' }}">
                                {{ $editIsPremium ? 'Premium' : 'Free' }}
                                        </span>
                                </div>
                        <div class="rounded-2xl border border-slate-100 bg-white p-4 text-center">
                            <p class="text-[11px] uppercase tracking-[0.3em] text-slate-400 mb-2">Admin</p>
                            <span class="text-sm font-semibold {{ $editIsAdmin ? 'text-orange-600' : 'text-slate-600' }}">
                                {{ $editIsAdmin ? 'Admin' : 'User' }}
                            </span>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-white p-4 text-center">
                            <p class="text-[11px] uppercase tracking-[0.3em] text-slate-400 mb-2">AI Trial</p>
                            <span class="text-sm font-semibold {{ $editingUser && $editingUser->has_used_ai_analyzer_trial ? 'text-blue-700' : 'text-slate-600' }}">
                                {{ ($editingUser && $editingUser->has_used_ai_analyzer_trial) ? 'Used' : 'No' }}
                                            </span>
                                    </div>
                                </div>
                                
                    <div class="rounded-2xl border border-slate-100 bg-blue-50 p-4 text-sm text-blue-700">
                        <div class="font-semibold text-xs uppercase tracking-[0.3em] text-blue-500 mb-2">AI Analyzer</div>
                                        @if($editingUser && $editingUser->ai_analyzer_trial_used_at)
                            <p class="text-sm font-semibold text-blue-900">Trial used on {{ $editingUser->ai_analyzer_trial_used_at->format('d M Y') }}</p>
                                        @else
                            <p class="text-sm font-semibold text-blue-900">Not used yet</p>
                                        @endif
                        <div class="text-xs text-blue-700 mt-2">
                            <strong>{{ $editingUser->ai_analyzer_count_this_month ?? 0 }}x</strong> digunakan bulan ini
                        </div>
                        @if($editingUser && $editingUser->last_ai_analyzer_reset)
                            <p class="text-[11px] text-blue-700 mt-1">Reset terakhir: {{ $editingUser->last_ai_analyzer_reset->format('d M Y') }}</p>
                        @endif
                    </div>

                    <div class="rounded-2xl border border-purple-200 bg-purple-50 p-4 text-sm text-purple-800">
                            <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 flex items-center justify-center rounded-2xl bg-purple-100 text-purple-600">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                    <p class="font-semibold text-purple-900 mb-1">Informasi Read-Only</p>
                                <p class="text-xs text-purple-800 leading-relaxed">Data pengguna ditampilkan dalam mode baca saja. Gunakan fitur lain apabila ingin mengedit.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-100 px-6 py-4">
                    <button wire:click="closeModal" class="rounded-2xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:border-slate-300 transition">
                        Tutup
                    </button>
            </div>
            </div>
        </div>
    @endif

    {{-- Create Admin Modal --}}
    @if($showCreateAdminModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" wire:click="closeCreateAdminModal"></div>
            <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white rounded-lg shadow-xl transform transition-all z-10" wire:click.stop>
                {{-- Modal Header --}}
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Tambah Admin Baru</h3>
                                <p class="text-sm text-gray-500">Buat akun administrator baru</p>
                            </div>
                        </div>
                        <button wire:click="closeCreateAdminModal" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg p-2 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Modal Body --}}
                <form wire:submit.prevent="createAdmin" class="p-6 space-y-4">
                    {{-- Name Field --}}
                    <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap *
                            </label>
                            <input type="text" wire:model="newAdminName" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all" placeholder="Masukkan nama lengkap admin">
                            @error('newAdminName') 
                                <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Email Field --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Alamat Email *
                            </label>
                            <input type="email" wire:model="newAdminEmail" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all" placeholder="admin@example.com">
                            @error('newAdminEmail') 
                                <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    {{-- Password Fields in Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Password Field --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Password *
                            </label>
                            <input type="password" wire:model="newAdminPassword" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all" placeholder="Min. 8 karakter">
                            @error('newAdminPassword') 
                                <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Confirm Password Field --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Konfirmasi Password *
                            </label>
                            <input type="password" wire:model="newAdminPasswordConfirmation" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all" placeholder="Ulangi password">
                        </div>
                    </div>

                    {{-- Info Box --}}
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="h-4 w-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-sm">
                                <p class="font-semibold text-purple-900 mb-1">Hak Akses Administrator</p>
                                <p class="text-purple-800 text-xs leading-relaxed">Admin baru akan otomatis mendapatkan akses penuh ke admin panel, status premium, dan email terverifikasi.</p>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- Modal Footer --}}
                <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex justify-end gap-3">
                    <button type="button" wire:click="closeCreateAdminModal" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all font-medium">
                        Batal
                    </button>
                    <button type="submit" wire:click="createAdmin" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all font-medium">
                        Buat Admin
                    </button>
                </div>
            </div>
            </div>
        </div>
    @endif

    {{-- Send Email Modal --}}
    @if($showSendEmailModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" wire:click="closeSendEmailModal"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl border border-slate-200 transform transition-all" wire:click.stop>
                    {{-- Modal Header --}}
                    <div class="px-6 py-5 border-b border-slate-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-purple-50 rounded-2xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Kirim Email</h3>
                                    <p class="text-sm text-gray-500">Pilih tipe email yang akan dikirim</p>
                                </div>
                            </div>
                            <button wire:click="closeSendEmailModal" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg p-2 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-6 space-y-6">
                        {{-- Target User Info --}}
                            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center">
                                    <span class="text-white font-semibold text-sm">{{ strtoupper(substr($emailTargetUserName ?? 'U', 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $emailTargetUserName ?? '-' }}</p>
                                    <p class="text-xs text-gray-500">{{ $emailTargetUserEmail ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Email Type Selection --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Pilih Tipe Email
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @php
                                    $emailTypes = [
                                        'welcome' => ['label' => 'Welcome Email', 'desc' => 'Email selamat datang'],
                                        'verification' => ['label' => 'Verification Email', 'desc' => 'Email verifikasi akun'],
                                        'ai_analyzer' => ['label' => 'AI Analyzer', 'desc' => 'Pengumuman trial gratis'],
                                        'job_reminder' => ['label' => 'Job Reminder', 'desc' => 'Ajakan catat lamaran'],
                                        'monthly_motivation' => ['label' => 'Monthly Motivation', 'desc' => 'Bulan baru semangat baru'],
                                    ];
                                @endphp
                                
                                @foreach($emailTypes as $type => $info)
                                    @php
                                        $isSelected = $emailType === $type;
                                        $borderClass = $isSelected ? 'border-purple-500 bg-purple-50 shadow-sm' : 'border-slate-200 hover:border-purple-300 bg-white';
                                        $dotBorderClass = $isSelected ? 'border-purple-500' : 'border-slate-300';
                                    @endphp
                                    <label class="relative flex cursor-pointer rounded-2xl border-2 {{ $borderClass }} p-4 transition-colors {{ $type === 'monthly_motivation' ? 'md:col-span-2' : '' }}">
                                        <input type="radio" wire:model.live="emailType" value="{{ $type }}" class="sr-only">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3">
                                                <div class="flex-shrink-0">
                                                    <div class="h-5 w-5 rounded-full border-2 {{ $dotBorderClass }} flex items-center justify-center">
                                                        @if($isSelected)
                                                            <div class="h-2 w-2 rounded-full bg-purple-500"></div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium text-gray-900">{{ $info['label'] }}</p>
                                                    <p class="text-xs text-gray-500 mt-1">{{ $info['desc'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Warning --}}
                        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-yellow-100 text-yellow-600">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                                <div class="text-sm text-yellow-900">
                                    <p class="font-semibold">Perhatian!</p>
                                    <p>Email akan dikirim langsung ke <strong>{{ $emailTargetUserEmail ?? '-' }}</strong>. Pastikan Anda sudah memilih tipe email yang benar.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="px-6 py-5 bg-slate-50 rounded-b-3xl flex flex-wrap justify-end gap-3 border-t border-slate-100">
                        <button 
                            type="button"
                            wire:click="closeSendEmailModal"
                            class="px-4 py-2 border border-purple-200 text-purple-800 rounded-2xl hover:border-purple-300 transition-all font-medium">
                            Batal
                        </button>
                        <button 
                            type="button"
                            wire:click="sendEmail"
                            class="px-5 py-2 rounded-2xl bg-gradient-to-r from-purple-100 via-purple-100 to-purple-200 text-purple-900 font-semibold shadow-sm shadow-purple-200/60 transition hover:-translate-y-0.5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-purple-100">
                            Kirim Email
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    <div id="deleteUserModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-[100]">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95" id="deleteUserModalContent">
                <div class="p-6">
                    <!-- Icon -->
                    <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-red-100">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>

                    <!-- Title -->
                    <h3 class="text-xl font-bold text-gray-900 text-center mb-1">Delete User</h3>
                    <p class="text-sm text-gray-500 text-center mb-4">Are you sure you want to delete this user?</p>

                    <!-- User Info -->
                    <div class="text-center mb-6">
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <p class="text-sm font-semibold text-gray-900" id="deleteUserName"></p>
                            <p class="text-xs text-gray-500 mt-1" id="deleteUserEmail"></p>
                        </div>
                        <p class="text-red-600 text-sm mt-4 font-medium">⚠️ This action cannot be undone!</p>
                        <p class="text-gray-500 text-xs mt-1">All user data including job applications will be permanently deleted.</p>
                    </div>

                    <!-- Buttons -->
                    <div class="flex space-x-3">
                        <button type="button" 
                                onclick="closeDeleteUserModal()"
                                class="flex-1 px-4 py-3 border border-gray-200 rounded-xl text-gray-700 font-medium hover:bg-gray-50 transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="button" 
                                onclick="confirmDeleteUserAction()"
                                class="flex-1 px-4 py-3 rounded-xl font-semibold text-white bg-red-600 hover:bg-red-700 transition-all duration-200">
                            Yes, Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let deleteUserId = null;

    function confirmDeleteUser(userId, userName, userEmail) {
        deleteUserId = userId;
        
        // Update modal content
        document.getElementById('deleteUserName').textContent = userName;
        document.getElementById('deleteUserEmail').textContent = userEmail;
        
        // Show modal
        const modal = document.getElementById('deleteUserModal');
        const content = document.getElementById('deleteUserModalContent');
        
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        
        // Animate modal in
        setTimeout(() => {
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }, 10);
    }

    function closeDeleteUserModal() {
        const modal = document.getElementById('deleteUserModal');
        const content = document.getElementById('deleteUserModalContent');
        
        // Animate modal out
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            deleteUserId = null;
        }, 200);
    }

    function confirmDeleteUserAction() {
        if (deleteUserId) {
            // Call Livewire method
            @this.call('deleteUser', deleteUserId);
            closeDeleteUserModal();
        }
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.id === 'deleteUserModal') {
            closeDeleteUserModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('deleteUserModal').classList.contains('hidden')) {
            closeDeleteUserModal();
        }
    });
</script>
</div>
