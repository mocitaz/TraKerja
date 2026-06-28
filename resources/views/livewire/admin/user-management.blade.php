<div class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-5 space-y-5 pb-10">
    
    <!-- Sticky Global Sub-Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-zinc-200/80">
        <div class="flex items-center gap-2.5 min-w-0">
            <span class="text-xs font-mono font-medium text-zinc-400">Admin</span>
            <span class="text-zinc-300">/</span>
            <h1 class="text-sm font-semibold tracking-tight text-zinc-900">Users</h1>
        </div>
    </div>

    {{-- Stats Cards (Notion Premium Grid) --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        {{-- Total Users --}}
        <div class="bg-white rounded-lg border border-zinc-200/80 p-3.5 hover:bg-zinc-50/50 transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Total Users</span>
                <i class="ph-bold ph-users-three text-zinc-400 text-sm"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold tracking-tight text-zinc-900">{{ number_format($stats['total']) }}</h3>
                <p class="text-[10px] text-zinc-400 mt-0.5">All registered members</p>
            </div>
        </div>

        {{-- Premium Users --}}
        <div class="bg-white rounded-lg border border-zinc-200/80 p-3.5 hover:bg-zinc-50/50 transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Premium Users</span>
                <i class="ph-bold ph-crown text-zinc-400 text-sm"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold tracking-tight text-zinc-900">{{ number_format($stats['premium']) }}</h3>
                <p class="text-[10px] text-zinc-400 mt-0.5">Active paid access</p>
            </div>
        </div>

        {{-- Free Users --}}
        <div class="bg-white rounded-lg border border-zinc-200/80 p-3.5 hover:bg-zinc-50/50 transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Free Users</span>
                <i class="ph-bold ph-user text-zinc-400 text-sm"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold tracking-tight text-zinc-900">{{ number_format($stats['free']) }}</h3>
                <p class="text-[10px] text-zinc-400 mt-0.5">Standard basic tier</p>
            </div>
        </div>

        {{-- Verified Users --}}
        <div class="bg-white rounded-lg border border-zinc-200/80 p-3.5 hover:bg-zinc-50/50 transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Verified Users</span>
                <i class="ph-bold ph-check-circle text-zinc-400 text-sm"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold tracking-tight text-zinc-900">{{ number_format($stats['verified']) }}</h3>
                <p class="text-[10px] text-zinc-400 mt-0.5">Email verified</p>
            </div>
        </div>

        {{-- AI Trial Used --}}
        <div class="bg-white rounded-lg border border-zinc-200/80 p-3.5 hover:bg-zinc-50/50 transition-all flex flex-col justify-between col-span-2 sm:col-span-1">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider">AI Trial Used</span>
                <i class="ph-bold ph-sparkles text-zinc-400 text-sm"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold tracking-tight text-zinc-900">{{ number_format($stats['ai_analyzer_trial_used']) }}</h3>
                <p class="text-[10px] text-zinc-400 mt-0.5">Analyzer executions</p>
            </div>
        </div>
    </div>

    {{-- Filters & Search (Ramping & Sleek) --}}
    <div class="bg-white rounded-lg border border-zinc-200/80 p-3 flex flex-col lg:flex-row gap-3 items-center justify-between">
        <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto flex-1">
            <!-- Search -->
            <div class="relative w-full sm:max-w-xs">
                <input type="text" wire:model.live="search" placeholder="Cari nama atau email..." 
                       class="w-full px-3 py-1.5 bg-zinc-50 border border-zinc-200 rounded-md text-xs font-medium text-zinc-800 focus:bg-white focus:ring-1 focus:ring-primary-600 focus:border-primary-600 transition-all placeholder:text-zinc-400">
            </div>

            <!-- Premium Filter -->
            <div class="relative w-full sm:w-40">
                <select wire:model.live="filterPremium" 
                        class="w-full pl-3 pr-8 py-1.5 bg-zinc-50 border border-zinc-200 rounded-md text-xs font-medium text-zinc-800 focus:bg-white focus:ring-1 focus:ring-primary-600 focus:border-primary-600 transition-all appearance-none cursor-pointer">
                    <option value="all">Semua Status</option>
                    <option value="premium">Premium</option>
                    <option value="free">Free</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none">
                    <i class="ph-bold ph-caret-down text-zinc-400 text-xs"></i>
                </div>
            </div>

            <!-- AI Trial Filter -->
            <div class="relative w-full sm:w-44">
                <select wire:model.live="filterAiAnalyzer" 
                        class="w-full pl-3 pr-8 py-1.5 bg-zinc-50 border border-zinc-200 rounded-md text-xs font-medium text-zinc-800 focus:bg-white focus:ring-1 focus:ring-primary-600 focus:border-primary-600 transition-all appearance-none cursor-pointer">
                    <option value="all">Semua AI Trial</option>
                    <option value="used">Sudah Pakai</option>
                    <option value="not_used">Belum Pakai</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none">
                    <i class="ph-bold ph-caret-down text-zinc-400 text-xs"></i>
                </div>
            </div>
            
            <!-- Rows per page -->
            <div class="relative w-full sm:w-28 hidden md:block">
                <select wire:model.live="perPage" 
                        class="w-full pl-3 pr-8 py-1.5 bg-zinc-50 border border-zinc-200 rounded-md text-xs font-medium text-zinc-800 focus:bg-white focus:ring-1 focus:ring-primary-600 focus:border-primary-600 transition-all appearance-none cursor-pointer">
                    <option value="10">10 Rows</option>
                    <option value="20">20 Rows</option>
                    <option value="50">50 Rows</option>
                    <option value="100">100 Rows</option>
                    <option value="all">All</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none">
                    <i class="ph-bold ph-caret-down text-zinc-400 text-xs"></i>
                </div>
            </div>
        </div>

        <!-- Add Admin Action -->
        <div class="flex items-center gap-2 w-full lg:w-auto shrink-0">
            <button wire:click="openCreateAdminModal" 
                    class="w-full sm:w-auto px-4 py-1.5 bg-zinc-900 text-white hover:bg-zinc-800 rounded-md transition-colors flex items-center justify-center gap-1.5 text-xs font-medium">
                <i class="ph-bold ph-user-plus text-sm"></i>
                Add Admin
            </button>
        </div>
    </div>

    {{-- Users Table (Notion Plain Visual) --}}
    <div class="bg-white rounded-lg border border-zinc-200/80 overflow-hidden flex flex-col relative min-h-[480px]">
        <div class="overflow-x-auto custom-scrollbar flex-1">
            <table class="min-w-full divide-y divide-zinc-150">
                <thead class="bg-zinc-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider">User</th>
                        <th class="px-4 py-3 text-left text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider hidden sm:table-cell">Email</th>
                        <th class="px-4 py-3 text-left text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider hidden md:table-cell">Verification</th>
                        <th class="px-4 py-3 text-left text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider hidden xl:table-cell">Joined</th>
                        <th class="px-4 py-3 text-center text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider hidden lg:table-cell">Provider</th>
                        <th class="px-4 py-3 text-right text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-zinc-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-zinc-50/50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center min-w-0">
                                    @if($user->logo)
                                        <div class="flex-shrink-0 h-8 w-8 rounded-lg overflow-hidden border border-zinc-200">
                                            <img src="{{ $user->avatar_url }}" 
                                                 alt="{{ $user->name }}" 
                                                 class="w-full h-full object-cover"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="w-full h-full bg-zinc-100 flex items-center justify-center" style="display: none;">
                                                <span class="text-zinc-600 font-semibold text-xs">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex-shrink-0 h-8 w-8 bg-zinc-100 border border-zinc-200 rounded-lg flex items-center justify-center">
                                            <span class="text-zinc-600 font-semibold text-xs">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                    <div class="ml-3 min-w-0 flex-1">
                                        <div class="text-xs font-semibold text-zinc-900 truncate">{{ $user->name }}</div>
                                        <div class="text-[9px] font-mono text-zinc-400 mt-0.5">ID: #{{ $user->id }}</div>
                                        <div class="text-[10px] text-zinc-500 sm:hidden truncate mt-0.5">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap hidden sm:table-cell">
                                <div class="text-xs text-zinc-600 truncate max-w-[180px]">{{ $user->email }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap hidden md:table-cell">
                                @if($user->hasVerifiedEmail())
                                    <span class="px-2 py-0.5 text-[9px] font-mono font-bold uppercase rounded-md bg-emerald-50 text-emerald-600 border border-emerald-100 inline-flex items-center gap-1">
                                        Verified
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 text-[9px] font-mono font-bold uppercase rounded-md bg-amber-50 text-amber-600 border border-amber-100 inline-flex items-center gap-1">
                                        Unverified
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($user->is_premium)
                                    <span class="px-2 py-0.5 text-[9px] font-mono font-bold uppercase rounded-md bg-purple-50 text-primary-650 border border-purple-100">
                                        Premium
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 text-[9px] font-mono font-bold uppercase rounded-md bg-zinc-100 text-zinc-600 border border-zinc-200">
                                        Free
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap hidden xl:table-cell">
                                <div class="text-xs text-zinc-800">{{ $user->created_at->format('d M Y') }}</div>
                                <div class="text-[9px] text-zinc-400 mt-0.5">{{ $user->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap hidden lg:table-cell text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @if($user->password)
                                        <div class="w-5 h-5 flex items-center justify-center text-zinc-400 bg-zinc-50 border border-zinc-200 rounded" title="Email / Password">
                                            <i class="ph-bold ph-envelope text-xs"></i>
                                        </div>
                                    @endif

                                    @if($user->google_id)
                                        <div class="w-5 h-5 flex items-center justify-center text-zinc-400 bg-zinc-50 border border-zinc-200 rounded" title="Google OAuth">
                                            <span class="text-[9px] font-mono font-bold text-zinc-500">G</span>
                                        </div>
                                    @endif

                                    @if($user->linkedin_id)
                                        <div class="w-5 h-5 flex items-center justify-center text-zinc-400 bg-zinc-50 border border-zinc-200 rounded" title="LinkedIn OAuth">
                                            <span class="text-[9px] font-mono font-bold text-zinc-500">L</span>
                                        </div>
                                    @endif
                                    
                                    @if(!$user->password && !$user->google_id && !$user->linkedin_id)
                                        <div class="w-5 h-5 flex items-center justify-center text-zinc-300 bg-zinc-50 border border-zinc-200 rounded" title="None">
                                            <i class="ph-bold ph-question text-xs"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <!-- Send Email Action -->
                                    <button wire:click="openSendEmailModal({{ $user->id }})" 
                                            class="text-zinc-500 hover:text-zinc-900 bg-white border border-zinc-200 p-1 rounded-md transition-colors" 
                                            title="Send Email">
                                        <i class="ph-bold ph-paper-plane-tilt text-xs"></i>
                                    </button>

                                    <!-- Premium Manual Switch Action -->
                                    <button wire:click="openPremiumConfirmModal({{ $user->id }})" 
                                            class="p-1 rounded-md border transition-colors {{ $user->is_premium ? 'text-primary-650 bg-purple-50/50 border-purple-200' : 'text-zinc-400 bg-white hover:text-zinc-900 border-zinc-200' }}" 
                                            title="Manual Premium Override">
                                        <i class="ph-bold ph-crown text-xs"></i>
                                    </button>

                                    <!-- View Details Action -->
                                    <button wire:click="editUser({{ $user->id }})" 
                                            class="text-zinc-500 hover:text-zinc-900 bg-white border border-zinc-200 p-1 rounded-md transition-colors" 
                                            title="View Details">
                                        <i class="ph-bold ph-eye text-xs"></i>
                                    </button>

                                    <!-- Delete Action -->
                                    <button onclick="confirmDeleteUser({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}')"
                                            class="text-red-600 hover:bg-red-50 border border-zinc-200 p-1 rounded-md transition-colors" 
                                            title="Delete User">
                                        <i class="ph-bold ph-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="ph-bold ph-users text-2xl text-zinc-300 mb-2"></i>
                                    <p class="text-xs font-semibold text-zinc-900">Tidak Ada User Ditemukan</p>
                                    <p class="text-[10px] text-zinc-400 mt-0.5">Coba sesuaikan kata kunci pencarian Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination Block --}}
        <div class="px-4 py-3 bg-zinc-50 border-t border-zinc-200/80 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="text-[10px] text-zinc-400">
                Showing <span class="font-bold text-zinc-700">{{ $users->firstItem() ?? 0 }}</span> to <span class="font-bold text-zinc-700">{{ $users->lastItem() ?? $users->count() }}</span> of <span class="font-bold text-zinc-700">{{ $users->total() }}</span> users
            </div>
            <div class="w-full sm:w-auto">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    {{-- Modal: Edit User Details --}}
    @if($showEditModal)
        <template x-teleport="body">
            <div class="fixed inset-0 bg-zinc-950/40 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="z-index: 9999;">
                <div class="bg-white rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] flex flex-col overflow-hidden border border-zinc-200">
                    
                    {{-- Modal Header --}}
                    <div class="bg-white px-5 py-4 flex justify-between items-center border-b border-zinc-150 shrink-0">
                        <div class="flex items-center gap-2">
                            <i class="ph-bold ph-user-circle text-zinc-400 text-lg"></i>
                            <div>
                                <h3 class="text-xs font-bold text-zinc-900">User Details</h3>
                                <p class="text-[9px] text-zinc-400 mt-0.5">Lihat informasi pengguna</p>
                            </div>
                        </div>
                        <button wire:click="closeModal" class="w-6 h-6 flex items-center justify-center rounded hover:bg-zinc-100 text-zinc-400 hover:text-zinc-800 transition-colors">
                            <i class="ph-bold ph-x text-sm"></i>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-5 overflow-y-auto custom-scrollbar space-y-5">
                        @php
                            $editingUser = $editingUserId ? \App\Models\User::find($editingUserId) : null;
                        @endphp
                        
                        {{-- Photo --}}
                        @if($editingUser)
                            <div class="flex justify-center mb-1">
                                <div class="w-14 h-14 rounded-lg overflow-hidden border border-zinc-200">
                                    <img src="{{ $editingUser->avatar_url }}" 
                                         alt="{{ $editName ?? 'User' }}" 
                                         class="w-full h-full object-cover">
                                </div>
                            </div>
                        @endif
                        
                        {{-- Info Grid --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Nama</label>
                                <div class="px-3 py-1.5 bg-zinc-50 border border-zinc-200 rounded text-xs text-zinc-800 font-medium">
                                    {{ $editName ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Email</label>
                                <div class="px-3 py-1.5 bg-zinc-50 border border-zinc-200 rounded text-xs text-zinc-800 font-medium truncate">
                                    {{ $editEmail ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Status Premium</label>
                                <div class="px-3 py-1.5 bg-zinc-50 border border-zinc-200 rounded text-xs font-semibold">
                                    @if($editIsPremium)
                                        <span class="text-purple-700">Premium</span>
                                    @else
                                        <span class="text-zinc-500">Free Tier</span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <label class="block text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Status Admin</label>
                                <div class="px-3 py-1.5 bg-zinc-50 border border-zinc-200 rounded text-xs font-semibold">
                                    @if($editIsAdmin)
                                        <span class="text-primary-650">Admin</span>
                                    @else
                                        <span class="text-zinc-500">User</span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <label class="block text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Total Lamaran</label>
                                <div class="px-3 py-1.5 bg-zinc-50 border border-zinc-200 rounded text-xs text-zinc-800">
                                    {{ $editingUser ? $editingUser->getJobApplicationsCount() : 0 }} Lamaran
                                </div>
                            </div>

                            <div>
                                <label class="block text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Bergabung Sejak</label>
                                <div class="px-3 py-1.5 bg-zinc-50 border border-zinc-200 rounded text-xs text-zinc-800">
                                    {{ $editingUser && $editingUser->created_at ? $editingUser->created_at->format('d M Y') : '-' }}
                                </div>
                            </div>
                        </div>
                        
                        {{-- AI Quotas --}}
                        <div class="pt-4 border-t border-zinc-150">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider flex items-center gap-1.5">
                                    <i class="ph-bold ph-robot"></i> AI Quotas Management
                                </h4>
                                <button type="button" wire:click="resetAllAiQuotas" class="text-[9px] font-mono font-bold text-primary-650 uppercase tracking-wider hover:underline transition-all">
                                    Reset ke Standar
                                </button>
                            </div>
                            
                            <div class="grid grid-cols-3 gap-2.5">
                                <div>
                                    <label class="block text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Analyzer</label>
                                    <input type="number" wire:model="editAiCredits" class="w-full px-2 py-1 bg-zinc-50 border border-zinc-200 rounded text-xs text-zinc-900 focus:bg-white focus:ring-1 focus:ring-primary-600 focus:border-primary-600 transition-all text-center" min="0">
                                </div>
                                
                                <div>
                                    <label class="block text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Cover Letter</label>
                                    <input type="number" wire:model="editClCredits" class="w-full px-2 py-1 bg-zinc-50 border border-zinc-200 rounded text-xs text-zinc-900 focus:bg-white focus:ring-1 focus:ring-primary-600 focus:border-primary-600 transition-all text-center" min="0">
                                </div>
                                
                                <div>
                                    <label class="block text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">AI Photo</label>
                                    <input type="number" wire:model="editPhotoCredits" class="w-full px-2 py-1 bg-zinc-50 border border-zinc-200 rounded text-xs text-zinc-900 focus:bg-white focus:ring-1 focus:ring-primary-600 focus:border-primary-600 transition-all text-center" min="0">
                                </div>
                            </div>
                            
                            @if($editingUser)
                            <div class="mt-3 flex justify-between items-center bg-zinc-50 border border-zinc-200 rounded p-2.5">
                                <div class="text-[10px] text-zinc-500">
                                    Trial Analyzer Dipakai: 
                                    <span class="font-bold text-zinc-700">{{ $editingUser->has_used_ai_analyzer_trial ? ($editingUser->ai_analyzer_trial_used_at ? $editingUser->ai_analyzer_trial_used_at->format('d M Y') : 'Ya') : 'Belum' }}</span>
                                </div>
                                <button type="button" wire:click="saveAiQuotas" class="px-2.5 py-1 bg-zinc-900 text-white rounded text-[10px] font-bold hover:bg-zinc-800 transition-colors shadow-sm">
                                    Simpan Quota
                                </button>
                            </div>
                            @endif
                        </div>

                        {{-- Footer Buttons --}}
                        <div class="pt-2 flex justify-end gap-2">
                            <button type="button" wire:click="closeModal" class="px-4 py-1.5 bg-zinc-100 text-zinc-600 hover:bg-zinc-200 rounded-md text-xs font-semibold transition-colors">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif

    {{-- Modal: Create Admin --}}
    @if($showCreateAdminModal)
        <template x-teleport="body">
            <div class="fixed inset-0 bg-zinc-950/40 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="z-index: 9999;">
                <div class="bg-white rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] flex flex-col overflow-hidden border border-zinc-200">
                    
                    {{-- Modal Header --}}
                    <div class="bg-white px-5 py-4 flex justify-between items-center border-b border-zinc-150 shrink-0">
                        <div class="flex items-center gap-2">
                            <i class="ph-bold ph-user-plus text-zinc-400 text-lg"></i>
                            <div>
                                <h3 class="text-xs font-bold text-zinc-900">Tambah Admin Baru</h3>
                                <p class="text-[9px] text-zinc-400 mt-0.5">Administrator Access</p>
                            </div>
                        </div>
                        <button wire:click="closeCreateAdminModal" class="w-6 h-6 flex items-center justify-center rounded hover:bg-zinc-100 text-zinc-400 hover:text-zinc-800 transition-colors">
                            <i class="ph-bold ph-x text-sm"></i>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-5 overflow-y-auto custom-scrollbar">
                        <form wire:submit.prevent="createAdmin" class="space-y-4">
                            <div>
                                <label class="block text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Nama Lengkap *</label>
                                <input type="text" wire:model="newAdminName" 
                                       class="w-full px-3 py-1.5 bg-zinc-50 border border-zinc-200 rounded-md text-xs text-zinc-900 focus:bg-white focus:ring-1 focus:ring-primary-600 focus:border-primary-600 transition-all" placeholder="Nama lengkap admin">
                                @error('newAdminName') 
                                    <p class="text-red-500 text-[10px] mt-1 flex items-center gap-1 font-bold">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Alamat Email *</label>
                                <input type="email" wire:model="newAdminEmail" 
                                       class="w-full px-3 py-1.5 bg-zinc-50 border border-zinc-200 rounded-md text-xs text-zinc-900 focus:bg-white focus:ring-1 focus:ring-primary-600 focus:border-primary-600 transition-all" placeholder="admin@example.com">
                                @error('newAdminEmail') 
                                    <p class="text-red-500 text-[10px] mt-1 flex items-center gap-1 font-bold">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Password *</label>
                                    <input type="password" wire:model="newAdminPassword" 
                                           class="w-full px-3 py-1.5 bg-zinc-50 border border-zinc-200 rounded-md text-xs text-zinc-900 focus:bg-white focus:ring-1 focus:ring-primary-600 focus:border-primary-600 transition-all" placeholder="Min. 8 karakter">
                                    @error('newAdminPassword') 
                                        <p class="text-red-500 text-[10px] mt-1 flex items-center gap-1 font-bold">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Konfirmasi Password *</label>
                                    <input type="password" wire:model="newAdminPasswordConfirmation" 
                                           class="w-full px-3 py-1.5 bg-zinc-50 border border-zinc-200 rounded-md text-xs text-zinc-900 focus:bg-white focus:ring-1 focus:ring-primary-600 focus:border-primary-600 transition-all" placeholder="Ulangi password">
                                </div>
                            </div>

                            <div class="bg-zinc-50 border border-zinc-200 rounded p-3 text-[11px] text-zinc-500 leading-relaxed">
                                <span class="font-bold text-zinc-700 block mb-0.5">Hak Akses Administrator</span>
                                Admin baru otomatis mendapatkan akses penuh ke admin panel, status premium, dan verifikasi email aktif.
                            </div>

                            <div class="pt-2 flex justify-end gap-2">
                                <button type="button" wire:click="closeCreateAdminModal" class="px-4 py-1.5 bg-zinc-100 text-zinc-600 hover:bg-zinc-200 rounded-md text-xs font-semibold transition-colors">
                                    Batal
                                </button>
                                <button type="submit" class="px-4 py-1.5 bg-zinc-900 text-white hover:bg-zinc-800 rounded-md text-xs font-semibold transition-colors">
                                    Buat Admin
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    @endif

    {{-- Modal: Send Email --}}
    @if($showSendEmailModal)
        <template x-teleport="body">
            <div class="fixed inset-0 bg-zinc-950/40 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="z-index: 9999;">
                <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden border border-zinc-200">
                    
                    {{-- Modal Header --}}
                    <div class="bg-white px-5 py-4 flex justify-between items-center border-b border-zinc-150 shrink-0">
                        <div class="flex items-center gap-2">
                            <i class="ph-bold ph-paper-plane-tilt text-zinc-400 text-lg"></i>
                            <div>
                                <h3 class="text-xs font-bold text-zinc-900">Kirim Email</h3>
                                <p class="text-[9px] text-zinc-400 mt-0.5">Pilih tipe email yang akan dikirim</p>
                            </div>
                        </div>
                        <button wire:click="closeSendEmailModal" class="w-6 h-6 flex items-center justify-center rounded hover:bg-zinc-100 text-zinc-400 hover:text-zinc-800 transition-colors">
                            <i class="ph-bold ph-x text-sm"></i>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-5 overflow-y-auto custom-scrollbar space-y-4">
                        {{-- Recipient info --}}
                        <div class="bg-zinc-50 border border-zinc-250 rounded p-3 flex items-center gap-3">
                            <div class="w-8 h-8 bg-zinc-200 rounded-full flex items-center justify-center font-bold text-zinc-700 text-xs shrink-0">
                                {{ strtoupper(substr($emailTargetUserName ?? 'U', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-zinc-900 truncate">{{ $emailTargetUserName ?? '-' }}</p>
                                <p class="text-[10px] text-zinc-400 mt-0.5 font-mono">{{ $emailTargetUserEmail ?? '-' }}</p>
                            </div>
                        </div>

                        {{-- Selection grid --}}
                        @php
                            $emailGroups = [
                                'Onboarding & Verifikasi' => [
                                    'welcome'               => ['label' => 'Welcome',           'desc' => 'Email selamat datang',        'icon' => 'ph-hand-waving'],
                                    'verification'          => ['label' => 'Verification',      'desc' => 'Verifikasi alamat email',     'icon' => 'ph-envelope-simple'],
                                    'verification_reminder' => ['label' => 'Verif. Reminder',   'desc' => 'Pengingat belum verifikasi',  'icon' => 'ph-clock-countdown'],
                                ],
                                'Engagement & Motivasi' => [
                                    'job_reminder'          => ['label' => 'Job Reminder',      'desc' => 'Ajakan catat lamaran',        'icon' => 'ph-briefcase'],
                                    'monthly_motivation'    => ['label' => 'Motivasi Bulanan',  'desc' => 'Semangat bulan baru',         'icon' => 'ph-calendar-check'],
                                    're_engagement'         => ['label' => 'Re-engagement',     'desc' => 'Ajakan kembali aktif',        'icon' => 'ph-arrow-counter-clockwise'],
                                    'idul_adha'             => ['label' => 'Idul Adha',         'desc' => 'Ucapan Idul Adha 1447 H',     'icon' => 'ph-moon-stars'],
                                ],
                                'Pembaruan & Fitur' => [
                                    'product_update'        => ['label' => 'Product Update',    'desc' => 'Pengumuman fitur terbaru',    'icon' => 'ph-megaphone'],
                                    'new_vibe'              => ['label' => 'Suasana Baru',      'desc' => 'Pengumuman layout minimalis', 'icon' => 'ph-sparkles'],
                                    'ai_analyzer'           => ['label' => 'AI Analyzer',       'desc' => 'Pengumuman trial gratis',     'icon' => 'ph-robot'],
                                    'ai_photo'              => ['label' => 'AI Photo Studio',   'desc' => 'Pengumuman rilis AI Photo',   'icon' => 'ph-camera-plus'],
                                    'follow_up_feature'     => ['label' => 'AI Follow Up',      'desc' => 'Pengumuman fitur Follow Up',  'icon' => 'ph-paper-plane-tilt'],
                                ],
                                'Status & Info Lainnya' => [
                                    'premium_granted'       => ['label' => 'Premium Granted',   'desc' => 'Notifikasi akses premium',    'icon' => 'ph-crown'],
                                    'hiring_season'         => ['label' => 'Hiring Season',     'desc' => 'Alert musim rekrutmen',       'icon' => 'ph-buildings'],
                                ]
                            ];
                        @endphp
                        
                        <div class="space-y-4">
                            @foreach($emailGroups as $groupName => $types)
                                <div>
                                    <h4 class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-2">{{ $groupName }}</h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                        @foreach($types as $type => $info)
                                            @php $isSelected = $emailType === $type; @endphp
                                            <label class="relative flex cursor-pointer rounded-md border p-2.5 transition-all {{ $isSelected ? 'border-primary-600 bg-zinc-50' : 'border-zinc-200 hover:bg-zinc-50' }}">
                                                <input type="radio" wire:model.live="emailType" value="{{ $type }}" class="sr-only">
                                                <div class="flex items-center gap-2.5 w-full">
                                                    <div class="w-6 h-6 rounded bg-zinc-100 flex items-center justify-center text-zinc-600">
                                                        <i class="ph-bold {{ $info['icon'] }} text-sm"></i>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-[11px] font-semibold text-zinc-900 leading-none">{{ $info['label'] }}</p>
                                                        <p class="text-[9px] text-zinc-400 mt-1 truncate font-mono uppercase tracking-wider">{{ $info['desc'] }}</p>
                                                    </div>
                                                    <div class="w-3.5 h-3.5 rounded-full border flex items-center justify-center {{ $isSelected ? 'border-primary-600 bg-primary-600' : 'border-zinc-300 bg-transparent' }}">
                                                        @if($isSelected)
                                                            <div class="w-1 h-1 bg-white rounded-full"></div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Footer Buttons --}}
                        <div class="pt-2 flex justify-end gap-2">
                            <button type="button" wire:click="closeSendEmailModal" class="px-4 py-1.5 bg-zinc-100 text-zinc-600 hover:bg-zinc-200 rounded-md text-xs font-semibold transition-colors">
                                Batal
                            </button>
                            <button type="button" wire:click="sendEmail" class="px-4 py-1.5 bg-zinc-900 text-white hover:bg-zinc-800 rounded-md text-xs font-semibold transition-colors">
                                Kirim Email
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif

    {{-- Modal: Manual Premium Override Confirm --}}
    @if($showPremiumConfirmModal)
        <template x-teleport="body">
            <div class="fixed inset-0 bg-zinc-950/40 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="z-index: 9999;">
                <div class="bg-white rounded-lg shadow-xl max-w-sm w-full border border-zinc-200 relative">
                    <div class="p-5">
                        <div class="flex items-center gap-2 mb-4">
                            <i class="ph-bold ph-crown text-zinc-400 text-lg"></i>
                            <div>
                                <h3 class="text-xs font-bold text-zinc-900">Premium Override</h3>
                                <p class="text-[9px] text-zinc-400 mt-0.5">Manual Status Update</p>
                            </div>
                        </div>

                        <div class="bg-zinc-50 border border-zinc-200 rounded p-3 mb-4">
                            <span class="text-xs font-semibold text-zinc-900 block truncate">{{ $confirmTargetUserName }}</span>
                            <span class="text-[10px] text-zinc-400 mt-0.5 block">Current: <span class="font-bold {{ $confirmTargetUserIsPremium ? 'text-purple-600' : 'text-zinc-600' }}">{{ $confirmTargetUserIsPremium ? 'Premium' : 'Free Tier' }}</span></span>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <button wire:click="toggleManualPremium" 
                                    class="w-full py-1.5 {{ $confirmTargetUserIsPremium ? 'bg-zinc-900' : 'bg-primary-600' }} text-white rounded font-semibold text-xs transition-colors">
                                {{ $confirmTargetUserIsPremium ? 'Downgrade to Free' : 'Upgrade to Premium' }}
                            </button>
                            <button wire:click="closePremiumConfirmModal" 
                                    class="w-full py-1.5 bg-zinc-100 text-zinc-600 hover:bg-zinc-200 rounded font-semibold text-xs transition-colors">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif

    {{-- Modal: Delete User Confirm --}}
    <template x-teleport="body">
        <div id="deleteUserModal" class="fixed inset-0 bg-zinc-950/40 backdrop-blur-sm z-50 hidden items-center justify-center p-4" style="z-index: 9999;">
            <div class="absolute inset-0" onclick="closeDeleteUserModal()"></div>
            <div class="bg-white rounded-lg shadow-xl max-w-sm w-full border border-zinc-200 relative z-10 transition-all scale-95 opacity-0 duration-150" id="deleteUserModalContent">
                
                <div class="p-5">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-10 h-10 bg-red-50 rounded-lg border border-red-200 flex items-center justify-center text-red-500 mb-3">
                            <i class="ph-bold ph-trash text-lg"></i>
                        </div>
                        
                        <h3 class="text-xs font-bold text-zinc-900">Delete User</h3>
                        <p class="text-[9px] text-zinc-400 uppercase font-mono tracking-wider mt-0.5 mb-3">Aksi ini tidak dapat dibatalkan</p>
                        
                        <div class="w-full bg-zinc-50 border border-zinc-200 rounded p-3 mb-4 text-left">
                            <p class="text-xs font-semibold text-zinc-900" id="deleteUserName"></p>
                            <p class="text-[10px] text-zinc-400 truncate mt-0.5" id="deleteUserEmail"></p>
                        </div>
                        
                        <div class="flex flex-col gap-1.5 w-full">
                            <button type="button" onclick="confirmDeleteUserAction()" class="w-full py-1.5 bg-red-600 hover:bg-red-700 text-white rounded font-semibold text-xs transition-colors">
                                Yes, Delete
                            </button>
                            <button type="button" onclick="closeDeleteUserModal()" class="w-full py-1.5 bg-zinc-100 text-zinc-600 hover:bg-zinc-200 rounded font-semibold text-xs transition-colors">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

</div>

<script>
    let deleteUserId = null;

    function confirmDeleteUser(userId, userName, userEmail) {
        deleteUserId = userId;
        
        document.getElementById('deleteUserName').textContent = userName;
        document.getElementById('deleteUserEmail').textContent = userEmail;
        
        const modal = document.getElementById('deleteUserModal');
        const content = document.getElementById('deleteUserModalContent');
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
        
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeDeleteUserModal() {
        const modal = document.getElementById('deleteUserModal');
        const content = document.getElementById('deleteUserModalContent');
        
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
            deleteUserId = null;
        }, 150);
    }

    function confirmDeleteUserAction() {
        if (deleteUserId) {
            @this.call('deleteUser', deleteUserId);
            closeDeleteUserModal();
        }
    }

    document.addEventListener('click', function(e) {
        if (e.target.id === 'deleteUserModal') {
            closeDeleteUserModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('deleteUserModal').classList.contains('hidden')) {
            closeDeleteUserModal();
        }
    });
</script>
</div>
