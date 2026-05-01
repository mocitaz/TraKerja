<div class="space-y-6">
    {{-- Stats Cards (Premium Bento Grid) --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-6">
        {{-- Total Users --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group hover:border-slate-300 transition-colors">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-slate-50 to-slate-100 rounded-full blur-2xl -z-10 group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Users</p>
                    <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $stats['total'] }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-600 shadow-inner">
                    <i class="ph-duotone ph-users-three text-2xl"></i>
                </div>
            </div>
        </div>

        {{-- Premium Users --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group hover:border-purple-200 transition-colors">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-purple-50 to-purple-100/50 rounded-full blur-2xl -z-10 group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[11px] font-bold text-purple-400 uppercase tracking-widest mb-1">Premium</p>
                    <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $stats['premium'] }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 shadow-inner">
                    <i class="ph-duotone ph-crown-simple text-2xl"></i>
                </div>
            </div>
        </div>

        {{-- Free Users --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group hover:border-emerald-200 transition-colors">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-emerald-50 to-emerald-100/50 rounded-full blur-2xl -z-10 group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[11px] font-bold text-emerald-400 uppercase tracking-widest mb-1">Free</p>
                    <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $stats['free'] }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-inner">
                    <i class="ph-duotone ph-user-circle text-2xl"></i>
                </div>
            </div>
        </div>

        {{-- Verified Users --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group hover:border-blue-200 transition-colors">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-blue-50 to-blue-100/50 rounded-full blur-2xl -z-10 group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[11px] font-bold text-blue-400 uppercase tracking-widest mb-1">Verified</p>
                    <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $stats['verified'] }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shadow-inner">
                    <i class="ph-duotone ph-seal-check text-2xl"></i>
                </div>
            </div>
        </div>

        {{-- AI Trial Used --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group hover:border-amber-200 transition-colors">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-amber-50 to-amber-100/50 rounded-full blur-2xl -z-10 group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[11px] font-bold text-amber-400 uppercase tracking-widest mb-1">AI Trial</p>
                    <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $stats['ai_analyzer_trial_used'] }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 shadow-inner">
                    <i class="ph-duotone ph-sparkle text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters & Search --}}
    {{-- Filters & Search --}}
    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center flex-shrink-0 text-indigo-600 shadow-inner">
                        <i class="ph-fill ph-funnel text-xl"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="text-lg font-extrabold text-slate-900 truncate">Filter & Search</h3>
                        <p class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Cari dan filter pengguna</p>
                    </div>
                </div>
                <button wire:click="openCreateAdminModal" class="w-full sm:w-auto px-4 py-2.5 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-colors flex items-center justify-center gap-2 text-sm font-bold shadow-sm shadow-primary-500/20">
                    <i class="ph-fill ph-user-plus text-lg"></i>
                    Add Admin
                </button>
            </div>
        </div>
        <div class="p-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ph ph-magnifying-glass text-slate-400"></i>
                        </div>
                        <input type="text" wire:model.live="search" placeholder="Cari nama atau email..." class="w-full pl-9 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all placeholder:text-slate-400">
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Premium Status</label>
                    <select wire:model.live="filterPremium" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                        <option value="all">Semua Pengguna</option>
                        <option value="premium">Premium Saja</option>
                        <option value="free">Gratis Saja</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">AI Analyzer Trial</label>
                    <select wire:model.live="filterAiAnalyzer" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                        <option value="all">Semua</option>
                        <option value="used">Sudah Pakai Trial</option>
                        <option value="not_used">Belum Pakai Trial</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Rows per page</label>
                    <select wire:model.live="perPage" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
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
    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100/80">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-4 lg:px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">User</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider hidden sm:table-cell">Email</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider hidden md:table-cell">Email Verified</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider hidden lg:table-cell">AI Trial</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider hidden lg:table-cell">Last Activity</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider hidden lg:table-cell">Role</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider hidden xl:table-cell">Joined</th>
                        <th class="px-4 lg:px-6 py-4 text-right text-[11px] font-bold text-slate-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4">
                                <div class="flex items-center min-w-0">
                                    @if($user->logo)
                                        <div class="flex-shrink-0 h-8 w-8 sm:h-10 sm:w-10 rounded-lg overflow-hidden ring-2 ring-gray-100">
                                            <img src="{{ Storage::url($user->logo) }}" 
                                                 alt="{{ $user->name }}" 
                                                 class="w-full h-full object-cover"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="w-full h-full bg-gradient-to-br from-primary-500 to-secondary-500 flex items-center justify-center" style="display: none;">
                                                <span class="text-white font-semibold text-xs sm:text-sm">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex-shrink-0 h-8 w-8 sm:h-10 sm:w-10 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center">
                                            <span class="text-white font-semibold text-xs sm:text-sm">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                    <div class="ml-4 min-w-0 flex-1">
                                        <div class="text-sm font-extrabold text-slate-900 truncate">{{ $user->name }}</div>
                                        <div class="text-[10px] font-bold text-slate-400">ID: #{{ $user->id }}</div>
                                        <div class="text-[10px] font-medium text-slate-500 sm:hidden truncate">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                                <div class="text-sm font-medium text-slate-600 truncate max-w-[150px]">{{ $user->email }}</div>
                            </td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                @if($user->hasVerifiedEmail())
                                    <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center w-fit gap-1.5">
                                        <i class="ph-fill ph-check-circle text-xs"></i>
                                        Verified
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md bg-amber-50 text-amber-600 border border-amber-100 flex items-center w-fit gap-1.5">
                                        <i class="ph-fill ph-warning-circle text-xs"></i>
                                        Unverified
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                @if($user->is_premium)
                                    <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md bg-purple-50 text-purple-600 border border-purple-100">
                                        Premium
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md bg-slate-100 text-slate-600 border border-slate-200">
                                        Free
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap hidden lg:table-cell">
                                @if($user->has_used_ai_analyzer_trial)
                                    <div class="flex flex-col gap-1">
                                        <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md bg-blue-50 text-blue-600 border border-blue-100 w-fit">
                                            Used
                                        </span>
                                        @if($user->ai_analyzer_trial_used_at)
                                            <span class="text-[10px] font-medium text-slate-400">{{ $user->ai_analyzer_trial_used_at->format('d M Y') }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md bg-slate-100 text-slate-500 border border-slate-200">
                                        Not Used
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap hidden lg:table-cell">
                                @if($user->last_activity_at)
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold {{ $user->last_activity_at->diffInMinutes(now()) < 5 ? 'text-emerald-600' : 'text-slate-700' }}">
                                            {{ $user->last_activity_at->diffForHumans() }}
                                        </span>
                                        @if($user->last_activity_at->diffInMinutes(now()) < 5)
                                            <span class="text-[9px] font-black text-emerald-500 uppercase tracking-tighter">Online Now</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-xs font-medium text-slate-400 italic">Never</span>
                                @endif
                            </td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap hidden lg:table-cell">
                                @if($user->is_admin || $user->role === 'admin')
                                    <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md bg-orange-50 text-orange-600 border border-orange-100">
                                        Admin
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md bg-indigo-50 text-indigo-600 border border-indigo-100">
                                        User
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap hidden xl:table-cell">
                                <div class="text-sm font-bold text-slate-900">{{ $user->created_at->format('d M Y') }}</div>
                                <div class="text-[10px] font-medium text-slate-500">{{ $user->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="openSendEmailModal({{ $user->id }})" class="text-emerald-600 bg-emerald-50 hover:bg-emerald-100 p-2 rounded-xl transition-colors border border-emerald-100" title="Send Email">
                                        <i class="ph-fill ph-paper-plane-tilt text-lg"></i>
                                    </button>

                                    {{-- Manual Premium Override Button --}}
                                    <button 
                                        wire:click="openPremiumConfirmModal({{ $user->id }})" 
                                        class="p-2 rounded-xl transition-all border {{ $user->is_premium ? 'text-purple-600 bg-purple-50 hover:bg-purple-100 border-purple-100' : 'text-slate-400 bg-slate-50 hover:bg-slate-100 border-slate-200 hover:text-purple-600' }}" 
                                        title="Manual Premium Override">
                                        <i class="ph-fill ph-crown text-lg"></i>
                                    </button>

                                    <button wire:click="editUser({{ $user->id }})" class="text-primary-600 bg-primary-50 hover:bg-primary-100 p-2 rounded-xl transition-colors border border-primary-100" title="View Details">
                                        <i class="ph-fill ph-eye text-lg"></i>
                                    </button>
                                    <button 
                                        onclick="confirmDeleteUser({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}')"
                                        class="text-red-600 bg-red-50 hover:bg-red-100 p-2 rounded-xl transition-colors border border-red-100" 
                                        title="Delete User">
                                        <i class="ph-fill ph-trash text-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                                        <i class="ph-fill ph-users text-3xl text-slate-300"></i>
                                    </div>
                                    <p class="text-lg font-extrabold text-slate-900 mb-1">Tidak Ada User Ditemukan</p>
                                    <p class="text-sm font-medium text-slate-500">Coba sesuaikan filter atau kata kunci pencarian Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-xs font-medium text-slate-500 text-center sm:text-left">
                Showing <span class="font-bold text-slate-900">{{ $users->firstItem() ?? 0 }}</span> to <span class="font-bold text-slate-900">{{ $users->lastItem() ?? $users->count() }}</span> of <span class="font-bold text-slate-900">{{ $users->total() }}</span> users
            </div>
            <div class="w-full sm:w-auto">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    {{-- Edit User Modal / User Details --}}
    @if($showEditModal)
        <div class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="closeModal"></div>
            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-[0_12px_40px_rgba(0,0,0,0.12)] border border-slate-100 transform transition-all z-10" wire:click.stop>
                    {{-- Modal Header --}}
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0 flex-1">
                                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0 text-blue-600 shadow-inner">
                                    <i class="ph-fill ph-user-circle text-xl"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-lg font-extrabold text-slate-900 truncate">User Details</h3>
                                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Lihat informasi pengguna</p>
                                </div>
                            </div>
                            <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600 bg-white hover:bg-slate-100 rounded-xl p-2 transition-all flex-shrink-0 border border-slate-100 shadow-sm">
                                <i class="ph-bold ph-x text-lg"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                        @php
                            $editingUser = $editingUserId ? \App\Models\User::find($editingUserId) : null;
                        @endphp
                        
                        {{-- Profile Photo --}}
                        @if($editingUser && $editingUser->logo)
                            <div class="flex justify-center mb-2">
                                <div class="w-24 h-24 rounded-2xl overflow-hidden ring-4 ring-slate-50 shadow-lg border border-slate-100">
                                    <img src="{{ Storage::url($editingUser->logo) }}" 
                                         alt="{{ $editName ?? 'User' }}" 
                                         class="w-full h-full object-cover"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="w-full h-full bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center" style="display: none;">
                                        <span class="text-white font-extrabold text-3xl">{{ strtoupper(substr($editName ?? 'U', 0, 1)) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        {{-- User Info Grid --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            {{-- Name --}}
                            <div>
                                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Nama</label>
                                <div class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900">
                                    {{ $editName ?? '-' }}
                                </div>
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Email</label>
                                <div class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 truncate">
                                    {{ $editEmail ?? '-' }}
                                </div>
                            </div>

                            {{-- Premium Status --}}
                            <div>
                                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Status Premium</label>
                                <div class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                                    @if($editIsPremium)
                                        <span class="inline-flex items-center gap-1.5 text-amber-600 font-bold text-sm">
                                            <i class="ph-fill ph-crown"></i>
                                            Premium
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-slate-500 font-bold text-sm">
                                            <i class="ph-bold ph-user"></i>
                                            Free
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Admin Status --}}
                            <div>
                                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Status Admin</label>
                                <div class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                                    @if($editIsAdmin)
                                        <span class="inline-flex items-center gap-1.5 text-primary-600 font-bold text-sm">
                                            <i class="ph-fill ph-shield-check"></i>
                                            Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-slate-500 font-bold text-sm">
                                            <i class="ph-bold ph-user"></i>
                                            User
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Total Lamaran --}}
                            <div>
                                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Total Lamaran</label>
                                <div class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                                    <span class="inline-flex items-center gap-1.5 text-slate-900 font-bold text-sm">
                                        <i class="ph-fill ph-briefcase text-slate-500"></i>
                                        {{ $editingUser ? $editingUser->getJobApplicationsCount() : 0 }} Lamaran
                                    </span>
                                </div>
                            </div>

                            {{-- Tanggal Bergabung --}}
                            <div>
                                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Bergabung Sejak</label>
                                <div class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                                    <span class="inline-flex items-center gap-1.5 text-slate-900 font-bold text-sm">
                                        <i class="ph-fill ph-calendar-blank text-slate-500"></i>
                                        {{ $editingUser && $editingUser->created_at ? $editingUser->created_at->format('d M Y') : '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        {{-- AI Analyzer Section --}}
                        <div class="pt-6 border-t border-slate-100">
                            <h4 class="text-sm font-extrabold text-slate-900 mb-4 flex items-center gap-2">
                                <div class="w-6 h-6 bg-blue-50 text-blue-600 rounded-md flex items-center justify-center">
                                    <i class="ph-fill ph-robot text-sm"></i>
                                </div>
                                AI Analyzer Usage
                            </h4>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                {{-- Free Trial Status --}}
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Free Trial</label>
                                    <div class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                                        @if($editingUser && $editingUser->has_used_ai_analyzer_trial)
                                            <span class="inline-flex items-center gap-1.5 text-emerald-600 font-bold text-sm">
                                                <i class="ph-fill ph-check-circle"></i>
                                                Sudah Dipakai
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 text-slate-500 font-bold text-sm">
                                                <i class="ph-bold ph-minus-circle"></i>
                                                Belum Dipakai
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                {{-- Usage Date --}}
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Tanggal Pakai</label>
                                    <div class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                                        @if($editingUser && $editingUser->ai_analyzer_trial_used_at)
                                            <div class="text-sm font-bold text-slate-900">
                                                {{ $editingUser->ai_analyzer_trial_used_at->format('d M Y') }}
                                            </div>
                                            <div class="text-[10px] text-slate-500 font-medium mt-0.5 uppercase tracking-wider">
                                                {{ $editingUser->ai_analyzer_trial_used_at->diffForHumans() }}
                                            </div>
                                        @else
                                            <span class="text-sm font-bold text-slate-400">-</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Monthly Usage Stats --}}
                            @if($editingUser)
                                <div class="mt-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100/50 rounded-xl">
                                    <div class="flex items-start gap-3">
                                        <i class="ph-fill ph-chart-line-up text-xl text-blue-500 mt-0.5"></i>
                                        <div class="flex-1">
                                            <p class="text-[11px] font-bold text-blue-400 uppercase tracking-wider">Usage Bulan Ini</p>
                                            <p class="text-sm font-bold text-blue-900 mt-1">
                                                <span class="text-lg text-blue-600 mr-1">{{ $editingUser->ai_analyzer_count_this_month ?? 0 }}x</span> digunakan
                                            </p>
                                            @if($editingUser->last_ai_analyzer_reset)
                                                <p class="text-[10px] font-medium text-blue-500 mt-1.5 uppercase tracking-wider">
                                                    Reset terakhir: {{ $editingUser->last_ai_analyzer_reset->format('d M Y') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Additional Info --}}
                        <div class="pt-6 border-t border-slate-100">
                            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 bg-white border border-slate-200 rounded-lg flex items-center justify-center flex-shrink-0 text-slate-400">
                                        <i class="ph-fill ph-info text-lg"></i>
                                    </div>
                                    <div class="text-sm">
                                        <p class="font-extrabold text-slate-900 mb-0.5">Informasi Read-Only</p>
                                        <p class="text-slate-500 text-xs font-medium leading-relaxed">Data pengguna ditampilkan dalam mode baca saja. Untuk mengubah data, gunakan fitur yang sesuai.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 rounded-b-2xl flex justify-end">
                        <button type="button" wire:click="closeModal" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 hover:text-slate-900 transition-all text-sm font-bold shadow-sm flex items-center gap-2">
                            <i class="ph-bold ph-x"></i>
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Create Admin Modal --}}
    @if($showCreateAdminModal)
        <div class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="closeCreateAdminModal"></div>
            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-[0_12px_40px_rgba(0,0,0,0.12)] border border-slate-100 transform transition-all z-10" wire:click.stop>
                    {{-- Modal Header --}}
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0 flex-1">
                                <div class="w-10 h-10 bg-primary-50 rounded-xl flex items-center justify-center flex-shrink-0 text-primary-600 shadow-inner">
                                    <i class="ph-fill ph-user-plus text-xl"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-lg font-extrabold text-slate-900 truncate">Tambah Admin Baru</h3>
                                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Buat akun administrator baru</p>
                                </div>
                            </div>
                            <button wire:click="closeCreateAdminModal" class="text-slate-400 hover:text-slate-600 bg-white hover:bg-slate-100 rounded-xl p-2 transition-all flex-shrink-0 border border-slate-100 shadow-sm">
                                <i class="ph-bold ph-x text-lg"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Modal Body --}}
                    <form wire:submit.prevent="createAdmin" class="p-6 space-y-5 max-h-[70vh] overflow-y-auto">
                        {{-- Name Field --}}
                        <div>
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Nama Lengkap *</label>
                            <input type="text" wire:model="newAdminName" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all placeholder:text-slate-400" placeholder="Masukkan nama lengkap admin">
                            @error('newAdminName') 
                                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1.5 font-medium">
                                    <i class="ph-fill ph-warning-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Email Field --}}
                        <div>
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Alamat Email *</label>
                            <input type="email" wire:model="newAdminEmail" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all placeholder:text-slate-400" placeholder="admin@example.com">
                            @error('newAdminEmail') 
                                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1.5 font-medium">
                                    <i class="ph-fill ph-warning-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Password Fields in Grid --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Password *</label>
                                <input type="password" wire:model="newAdminPassword" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all placeholder:text-slate-400" placeholder="Min. 8 karakter">
                                @error('newAdminPassword') 
                                    <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1.5 font-medium">
                                        <i class="ph-fill ph-warning-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Konfirmasi Password *</label>
                                <input type="password" wire:model="newAdminPasswordConfirmation" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all placeholder:text-slate-400" placeholder="Ulangi password">
                            </div>
                        </div>

                        {{-- Info Box --}}
                        <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0 text-indigo-600">
                                    <i class="ph-fill ph-info text-lg"></i>
                                </div>
                                <div>
                                    <p class="font-extrabold text-indigo-900 text-sm mb-0.5">Hak Akses Administrator</p>
                                    <p class="text-indigo-800 text-xs font-medium leading-relaxed">Admin baru otomatis mendapatkan akses penuh ke admin panel, status premium, dan email terverifikasi.</p>
                                </div>
                            </div>
                        </div>
                    </form>

                    {{-- Modal Footer --}}
                    <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 rounded-b-2xl flex justify-end gap-3">
                        <button type="button" wire:click="closeCreateAdminModal" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 hover:text-slate-900 transition-all text-sm font-bold shadow-sm">
                            Batal
                        </button>
                        <button type="submit" wire:click="createAdmin" class="px-5 py-2.5 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all text-sm font-bold shadow-sm shadow-primary-500/20 flex items-center gap-2">
                            <i class="ph-bold ph-plus"></i>
                            Buat Admin
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Send Email Modal --}}
    @if($showSendEmailModal)
        <div class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="closeSendEmailModal"></div>
            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-[0_12px_40px_rgba(0,0,0,0.12)] border border-slate-100 transform transition-all z-10" wire:click.stop>
                    {{-- Modal Header --}}
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0 flex-1">
                                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center flex-shrink-0 text-emerald-600 shadow-inner">
                                    <i class="ph-fill ph-paper-plane-tilt text-xl"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-lg font-extrabold text-slate-900 truncate">Kirim Email</h3>
                                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pilih tipe email yang akan dikirim</p>
                                </div>
                            </div>
                            <button wire:click="closeSendEmailModal" class="text-slate-400 hover:text-slate-600 bg-white hover:bg-slate-100 rounded-xl p-2 transition-all flex-shrink-0 border border-slate-100 shadow-sm">
                                <i class="ph-bold ph-x text-lg"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                        {{-- Target User Info --}}
                        <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center shadow-inner">
                                    <span class="text-white font-bold text-lg">{{ strtoupper(substr($emailTargetUserName ?? 'U', 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="text-base font-extrabold text-slate-900">{{ $emailTargetUserName ?? '-' }}</p>
                                    <p class="text-xs font-medium text-slate-500">{{ $emailTargetUserEmail ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Email Type Selection --}}
                        <div>
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-3">
                                Pilih Tipe Email
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                                        $borderClass = $isSelected ? 'border-primary-500 bg-primary-50/50 ring-1 ring-primary-500 shadow-sm' : 'border-slate-200 hover:border-primary-300 hover:bg-slate-50';
                                        $dotClass = $isSelected ? 'border-primary-500 bg-primary-500' : 'border-slate-300';
                                    @endphp
                                    <label class="relative flex cursor-pointer rounded-2xl border-2 {{ $borderClass }} p-4 transition-all {{ $type === 'monthly_motivation' ? 'md:col-span-2' : '' }}">
                                        <input type="radio" wire:model.live="emailType" value="{{ $type }}" class="sr-only">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3">
                                                <div class="flex-shrink-0">
                                                    <div class="h-5 w-5 rounded-full border-2 {{ $isSelected ? 'border-primary-500' : 'border-slate-300' }} flex items-center justify-center transition-colors">
                                                        @if($isSelected)
                                                            <div class="h-2 w-2 rounded-full bg-primary-500"></div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <p class="text-sm font-bold text-slate-900">{{ $info['label'] }}</p>
                                                    <p class="text-[11px] font-medium text-slate-500 mt-0.5">{{ $info['desc'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Warning --}}
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                            <div class="flex items-start gap-3">
                                <i class="ph-fill ph-warning-circle text-xl text-amber-600 mt-0.5"></i>
                                <div class="text-sm text-amber-800">
                                    <p class="font-bold">Perhatian!</p>
                                    <p class="mt-1 font-medium text-xs">Email akan dikirim langsung ke <strong class="font-bold text-amber-900">{{ $emailTargetUserEmail ?? '-' }}</strong>. Pastikan Anda sudah memilih tipe email yang benar.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 rounded-b-2xl flex justify-end gap-3">
                        <button type="button" wire:click="closeSendEmailModal" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 hover:text-slate-900 transition-all text-sm font-bold shadow-sm">
                            Batal
                        </button>
                        <button type="button" wire:click="sendEmail" class="px-5 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-all text-sm font-bold shadow-sm shadow-emerald-500/20 flex items-center gap-2">
                            <i class="ph-bold ph-paper-plane-tilt"></i>
                            Kirim Email
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Manual Premium Confirmation Modal --}}
    @if($showPremiumConfirmModal)
        <div class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="closePremiumConfirmModal"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl border border-slate-200 transition-all sm:w-full sm:max-w-sm animate-in zoom-in-95 duration-200">
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-xl {{ $confirmTargetUserIsPremium ? 'bg-slate-100 text-slate-400' : 'bg-purple-50 text-purple-600' }}">
                                <i class="ph-fill ph-crown text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Manual Premium Override</h3>
                                <p class="text-xs text-slate-500 font-medium">Ubah status akses pengguna secara manual.</p>
                            </div>
                        </div>

                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 mb-6">
                            <div class="text-sm font-bold text-slate-900">{{ $confirmTargetUserName }}</div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                                Current: {{ $confirmTargetUserIsPremium ? 'Premium' : 'Free Tier' }}
                            </div>
                        </div>

                        <div class="flex flex-col gap-2">
                            <button 
                                wire:click="toggleManualPremium" 
                                class="w-full py-2.5 {{ $confirmTargetUserIsPremium ? 'bg-slate-900' : 'bg-primary-600' }} text-white rounded-xl font-bold text-xs shadow-sm hover:opacity-90 transition-all">
                                {{ $confirmTargetUserIsPremium ? 'Downgrade to Free' : 'Upgrade to Premium' }}
                            </button>
                            <button 
                                wire:click="closePremiumConfirmModal" 
                                class="w-full py-2.5 bg-white text-slate-500 rounded-xl font-bold text-xs border border-slate-200 hover:bg-slate-50 transition-all">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    <div id="deleteUserModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeDeleteUserModal()"></div>
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-[0_12px_40px_rgba(0,0,0,0.12)] border border-slate-100 transition-all sm:my-8 sm:w-full sm:max-w-lg scale-95 opacity-0 duration-300" id="deleteUserModalContent">
                <div class="bg-white px-6 pb-6 pt-8 sm:p-8 sm:pb-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-16 w-16 flex-shrink-0 items-center justify-center rounded-full bg-red-50 sm:mx-0 sm:h-12 sm:w-12 border border-red-100">
                            <i class="ph-bold ph-trash text-2xl text-red-600"></i>
                        </div>
                        <div class="mt-4 text-center sm:ml-4 sm:mt-0 sm:text-left flex-1">
                            <h3 class="text-xl font-extrabold leading-6 text-slate-900" id="modal-title">Delete User</h3>
                            <div class="mt-2">
                                <p class="text-sm font-medium text-slate-500">Are you sure you want to delete this user? This action cannot be undone.</p>
                            </div>
                            
                            {{-- User Info --}}
                            <div class="mt-4 bg-slate-50 rounded-xl p-4 border border-slate-200">
                                <p class="text-base font-extrabold text-slate-900" id="deleteUserName"></p>
                                <p class="text-xs font-medium text-slate-500 mt-0.5" id="deleteUserEmail"></p>
                            </div>
                            
                            <p class="text-red-600 text-[11px] uppercase tracking-wider font-bold mt-4 flex items-center gap-1.5 justify-center sm:justify-start">
                                <i class="ph-fill ph-warning-circle text-sm"></i>
                                All user data will be permanently deleted.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50/50 px-6 py-4 border-t border-slate-100 sm:flex sm:flex-row-reverse gap-3">
                    <button type="button" onclick="confirmDeleteUserAction()" class="inline-flex w-full justify-center rounded-xl bg-red-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm shadow-red-500/20 hover:bg-red-700 transition-colors sm:w-auto flex items-center gap-2">
                        <i class="ph-bold ph-trash"></i>
                        Yes, Delete
                    </button>
                    <button type="button" onclick="closeDeleteUserModal()" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm border border-slate-200 hover:bg-slate-50 transition-colors sm:mt-0 sm:w-auto">
                        Cancel
                    </button>
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
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeDeleteUserModal() {
        const modal = document.getElementById('deleteUserModal');
        const content = document.getElementById('deleteUserModalContent');
        
        // Animate modal out
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
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
