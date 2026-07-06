<div class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-5 space-y-6 pb-12">
    
    <!-- Notion-style Minimal Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-zinc-100">
        <div class="flex items-center gap-2">
            <span class="text-xs font-mono font-medium text-zinc-400">Admin</span>
            <span class="text-zinc-300">/</span>
            <h1 class="text-xs font-bold font-mono tracking-wider uppercase text-zinc-900">User Management</h1>
        </div>
    </div>

    <!-- Notion-style Clean Stats Grid (5-Column) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        <!-- Total Users -->
        <div class="bg-white rounded border border-zinc-200/70 p-4 hover:border-zinc-300 transition-all flex flex-col justify-between group">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider block mb-1">Total Users</span>
                    <h3 class="text-xl font-bold tracking-tight text-zinc-900">{{ number_format($stats['total']) }}</h3>
                </div>
                <div class="w-6.5 h-6.5 rounded bg-[#f7f7f5] border border-zinc-150 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-100 transition-colors">
                    <i class="ph ph-users-three text-xs"></i>
                </div>
            </div>
            <p class="text-[10px] text-zinc-400 font-medium mt-3">All members registered</p>
        </div>

        <!-- Premium Users -->
        <div class="bg-white rounded border border-zinc-200/70 p-4 hover:border-zinc-300 transition-all flex flex-col justify-between group">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider block mb-1">Premium Users</span>
                    <h3 class="text-xl font-bold tracking-tight text-zinc-900">{{ number_format($stats['premium']) }}</h3>
                </div>
                <div class="w-6.5 h-6.5 rounded bg-[#f7f7f5] border border-zinc-150 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-100 transition-colors">
                    <i class="ph ph-crown text-xs"></i>
                </div>
            </div>
            <p class="text-[10px] text-zinc-400 font-medium mt-3">Active paid accounts</p>
        </div>

        <!-- Free Users -->
        <div class="bg-white rounded border border-zinc-200/70 p-4 hover:border-zinc-300 transition-all flex flex-col justify-between group">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider block mb-1">Free Users</span>
                    <h3 class="text-xl font-bold tracking-tight text-zinc-900">{{ number_format($stats['free']) }}</h3>
                </div>
                <div class="w-6.5 h-6.5 rounded bg-[#f7f7f5] border border-zinc-150 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-100 transition-colors">
                    <i class="ph ph-user text-xs"></i>
                </div>
            </div>
            <p class="text-[10px] text-zinc-400 font-medium mt-3">Standard basic tier</p>
        </div>

        <!-- Verified Users -->
        <div class="bg-white rounded border border-zinc-200/70 p-4 hover:border-zinc-300 transition-all flex flex-col justify-between group">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider block mb-1">Verified</span>
                    <h3 class="text-xl font-bold tracking-tight text-zinc-900">{{ number_format($stats['verified']) }}</h3>
                </div>
                <div class="w-6.5 h-6.5 rounded bg-[#f7f7f5] border border-zinc-150 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-100 transition-colors">
                    <i class="ph ph-check-circle text-xs"></i>
                </div>
            </div>
            <p class="text-[10px] text-zinc-400 font-medium mt-3">Email verified status</p>
        </div>

        <!-- AI Trial Used -->
        <div class="bg-white rounded border border-zinc-200/70 p-4 hover:border-zinc-300 transition-all flex flex-col justify-between group col-span-2 sm:col-span-1">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider block mb-1">AI Trials Used</span>
                    <h3 class="text-xl font-bold tracking-tight text-zinc-900">{{ number_format($stats['ai_analyzer_trial_used']) }}</h3>
                </div>
                <div class="w-6.5 h-6.5 rounded bg-[#f7f7f5] border border-zinc-150 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-100 transition-colors">
                    <i class="ph ph-sparkles text-xs"></i>
                </div>
            </div>
            <p class="text-[10px] text-zinc-400 font-medium mt-3">Resume analyses run</p>
        </div>
    </div>

    <!-- Filters & Actions Utilities Bar -->
    <div class="bg-[#f7f7f5] rounded border border-zinc-200/60 p-2 flex flex-col lg:flex-row gap-2.5 items-center justify-between shadow-none">
        <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto flex-1">
            <!-- Search -->
            <div class="relative w-full sm:max-w-xs">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-zinc-400">
                    <i class="ph ph-magnifying-glass text-xs"></i>
                </div>
                <input type="text" wire:model.live="search" placeholder="Cari nama atau email..." 
                       class="w-full h-8 pl-8 pr-3 bg-white border border-zinc-250 focus:border-zinc-400 rounded text-xs font-medium text-zinc-800 focus:outline-none focus:ring-0 transition-colors placeholder:text-zinc-450">
            </div>

            <!-- Premium Filter -->
            <div class="relative w-full sm:w-36">
                <select wire:model.live="filterPremium" 
                        class="w-full h-8 pl-2.5 pr-8 bg-white border border-zinc-250 focus:border-zinc-400 rounded text-xs font-medium text-zinc-800 focus:outline-none focus:ring-0 transition-colors bg-none appearance-none cursor-pointer">
                    <option value="all">Semua Status</option>
                    <option value="premium">Premium</option>
                    <option value="free">Free</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-zinc-400">
                    <i class="ph ph-caret-down text-[10px]"></i>
                </div>
            </div>

            <!-- AI Trial Filter -->
            <div class="relative w-full sm:w-40">
                <select wire:model.live="filterAiAnalyzer" 
                        class="w-full h-8 pl-2.5 pr-8 bg-white border border-zinc-250 focus:border-zinc-400 rounded text-xs font-medium text-zinc-800 focus:outline-none focus:ring-0 transition-colors bg-none appearance-none cursor-pointer">
                    <option value="all">Semua AI Trial</option>
                    <option value="used">Sudah Pakai</option>
                    <option value="not_used">Belum Pakai</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-zinc-400">
                    <i class="ph ph-caret-down text-[10px]"></i>
                </div>
            </div>
            
            <!-- Rows per page -->
            <div class="relative w-full sm:w-28 hidden md:block">
                <select wire:model.live="perPage" 
                        class="w-full h-8 pl-2.5 pr-8 bg-white border border-zinc-250 focus:border-zinc-400 rounded text-xs font-medium text-zinc-800 focus:outline-none focus:ring-0 transition-colors bg-none appearance-none cursor-pointer">
                    <option value="10">10 Baris</option>
                    <option value="20">20 Baris</option>
                    <option value="50">50 Baris</option>
                    <option value="100">100 Baris</option>
                    <option value="all">Semua</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-zinc-400">
                    <i class="ph ph-caret-down text-[10px]"></i>
                </div>
            </div>
        </div>

        <!-- Add Admin Button -->
        <div class="w-full lg:w-auto shrink-0 flex items-center">
            <button wire:click="openCreateAdminModal" 
                    class="w-full sm:w-auto h-8 px-4 bg-zinc-900 text-white hover:bg-zinc-800 rounded transition-colors flex items-center justify-center gap-1.5 text-xs font-semibold shadow-none">
                <i class="ph ph-user-plus text-xs"></i>
                Add Admin
            </button>
        </div>
    </div>

    <!-- Users Table (Notion Plain Database Visual) -->
    <div class="bg-white rounded border border-zinc-200/70 overflow-hidden flex flex-col relative min-h-[480px]">
        <div class="overflow-x-auto custom-scrollbar flex-1">
            <table class="min-w-full divide-y divide-zinc-150">
                <thead class="bg-[#f7f7f5]">
                    <tr>
                        <th class="px-4 py-2.5 text-left text-[9px] font-mono font-bold text-zinc-450 uppercase tracking-wider border-b border-zinc-200/50">User</th>
                        <th class="px-4 py-2.5 text-left text-[9px] font-mono font-bold text-zinc-450 uppercase tracking-wider border-b border-zinc-200/50 hidden sm:table-cell">Email</th>
                        <th class="px-4 py-2.5 text-left text-[9px] font-mono font-bold text-zinc-450 uppercase tracking-wider border-b border-zinc-200/50 hidden md:table-cell">Verification</th>
                        <th class="px-4 py-2.5 text-left text-[9px] font-mono font-bold text-zinc-450 uppercase tracking-wider border-b border-zinc-200/50">Status</th>
                        <th class="px-4 py-2.5 text-left text-[9px] font-mono font-bold text-zinc-450 uppercase tracking-wider border-b border-zinc-200/50 hidden xl:table-cell">Joined</th>
                        <th class="px-4 py-2.5 text-center text-[9px] font-mono font-bold text-zinc-450 uppercase tracking-wider border-b border-zinc-200/50 hidden lg:table-cell">Provider</th>
                        <th class="px-4 py-2.5 text-right text-[9px] font-mono font-bold text-zinc-450 uppercase tracking-wider border-b border-zinc-200/50">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-zinc-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-zinc-50/50 transition-colors">
                            <!-- User Info -->
                            <td class="px-4 py-3">
                                <div class="flex items-center min-w-0">
                                    @if($user->logo)
                                        <div class="flex-shrink-0 h-7 w-7 rounded overflow-hidden border border-zinc-200 shadow-none">
                                            <img src="{{ $user->avatar_url }}" 
                                                 alt="{{ $user->name }}" 
                                                 class="w-full h-full object-cover"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="w-full h-full bg-zinc-100 flex items-center justify-center" style="display: none;">
                                                <span class="text-zinc-600 font-bold text-[10px]">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex-shrink-0 h-7 w-7 bg-zinc-100 border border-zinc-200 rounded flex items-center justify-center text-zinc-650 text-[10px] font-bold">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="ml-3 min-w-0 flex-1">
                                        <div class="text-xs font-semibold text-zinc-900 truncate">{{ $user->name }}</div>
                                        <div class="text-[9px] font-mono text-zinc-400 mt-0.5">ID: #{{ $user->id }}</div>
                                        <div class="text-[10px] text-zinc-500 sm:hidden truncate mt-0.5">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <!-- Email -->
                            <td class="px-4 py-3 whitespace-nowrap hidden sm:table-cell">
                                <div class="text-xs text-zinc-600 truncate max-w-[180px]">{{ $user->email }}</div>
                            </td>
                            <!-- Verification Status -->
                            <td class="px-4 py-3 whitespace-nowrap hidden md:table-cell">
                                @if($user->hasVerifiedEmail())
                                    <span class="px-1.5 py-0.5 text-[8px] font-medium rounded bg-emerald-50 text-emerald-800 border border-emerald-150/40 inline-flex items-center">
                                        Verified
                                    </span>
                                @else
                                    <span class="px-1.5 py-0.5 text-[8px] font-medium rounded bg-amber-50 text-amber-800 border border-amber-150/40 inline-flex items-center">
                                        Unverified
                                    </span>
                                @endif
                            </td>
                            <!-- Account Tier Status -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($user->is_premium)
                                    <span class="px-1.5 py-0.5 text-[8px] font-medium rounded bg-purple-50 text-purple-800 border border-purple-150/40 inline-flex items-center">
                                        Premium
                                    </span>
                                @else
                                    <span class="px-1.5 py-0.5 text-[8px] font-medium rounded bg-zinc-100 text-zinc-700 border border-zinc-200/50 inline-flex items-center">
                                        Free
                                    </span>
                                @endif
                            </td>
                            <!-- Joined Date -->
                            <td class="px-4 py-3 whitespace-nowrap hidden xl:table-cell">
                                <div class="text-xs text-zinc-800">{{ $user->created_at->format('d M Y') }}</div>
                                <div class="text-[9px] text-zinc-400 font-mono mt-0.5">{{ $user->created_at->diffForHumans() }}</div>
                            </td>
                            <!-- Login Providers -->
                            <td class="px-4 py-3 whitespace-nowrap hidden lg:table-cell text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @if($user->password)
                                        <div class="w-5 h-5 flex items-center justify-center text-zinc-450 bg-[#f7f7f5] border border-zinc-200 rounded" title="Email/Password Login">
                                            <i class="ph ph-envelope text-xs"></i>
                                        </div>
                                    @endif

                                    @if($user->google_id)
                                        <div class="w-5 h-5 flex items-center justify-center text-zinc-500 bg-[#f7f7f5] border border-zinc-200 rounded font-mono font-bold text-[9px]" title="Google OAuth">
                                            G
                                        </div>
                                    @endif

                                    @if($user->linkedin_id)
                                        <div class="w-5 h-5 flex items-center justify-center text-zinc-500 bg-[#f7f7f5] border border-zinc-200 rounded font-mono font-bold text-[9px]" title="LinkedIn OAuth">
                                            L
                                        </div>
                                    @endif
                                    
                                    @if(!$user->password && !$user->google_id && !$user->linkedin_id)
                                        <div class="w-5 h-5 flex items-center justify-center text-zinc-300 bg-[#f7f7f5] border border-zinc-200 rounded" title="None">
                                            <i class="ph ph-question text-xs"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <!-- Action buttons -->
                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Send Email Action -->
                                    <button wire:click="openSendEmailModal({{ $user->id }})" 
                                            class="text-zinc-500 hover:text-zinc-800 hover:bg-zinc-50 bg-white border border-zinc-200 p-1 rounded transition-colors" 
                                            title="Send Email">
                                        <i class="ph ph-paper-plane-tilt text-xs"></i>
                                    </button>

                                    <!-- Premium Manual Switch Action -->
                                    <button wire:click="openPremiumConfirmModal({{ $user->id }})" 
                                            class="p-1 rounded border transition-colors {{ $user->is_premium ? 'text-purple-750 bg-purple-50/50 border-purple-200/70' : 'text-zinc-550 bg-white hover:text-zinc-800 border-zinc-200 hover:bg-zinc-50' }}" 
                                            title="Manual Premium Override">
                                        <i class="ph ph-crown text-xs"></i>
                                    </button>

                                    <!-- View Details Action -->
                                    <button wire:click="editUser({{ $user->id }})" 
                                            class="text-zinc-500 hover:text-zinc-800 hover:bg-zinc-50 bg-white border border-zinc-200 p-1 rounded transition-colors" 
                                            title="View Details">
                                        <i class="ph ph-eye text-xs"></i>
                                    </button>

                                    <!-- Delete Action -->
                                    <button onclick="confirmDeleteUser({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}')"
                                            class="text-red-650 hover:bg-red-50/50 border border-zinc-200 p-1 rounded transition-colors" 
                                            title="Delete User">
                                        <i class="ph ph-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="ph ph-users text-xl text-zinc-300 mb-2"></i>
                                    <p class="text-xs font-semibold text-zinc-900">Tidak Ada User Ditemukan</p>
                                    <p class="text-[10px] text-zinc-400 mt-0.5">Coba sesuaikan kata kunci pencarian Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Notion-style Table Pagination Footer -->
        <div class="px-4 py-2 bg-[#f7f7f5] border-t border-zinc-200/50 flex flex-col sm:flex-row items-center justify-between gap-3 shrink-0">
            <div class="text-[10px] text-zinc-450 font-medium">
                Menampilkan <span class="font-bold text-zinc-700">{{ $users->firstItem() ?? 0 }}</span> - <span class="font-bold text-zinc-700">{{ $users->lastItem() ?? $users->count() }}</span> dari <span class="font-bold text-zinc-700">{{ $users->total() }}</span> user
            </div>
            <div class="w-full sm:w-auto notion-pagination font-mono text-[10px]">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- Modal: View Details / Edit Quota -->
    @if($showEditModal)
        <template x-teleport="body">
            <div class="fixed inset-0 bg-zinc-950/20 backdrop-blur-[2px] z-50 flex items-center justify-center p-4" style="z-index: 9999;">
                <div class="bg-white rounded border border-zinc-200 shadow-sm max-w-md w-full max-h-[90vh] flex flex-col overflow-hidden">
                    
                    <!-- Modal Header -->
                    <div class="bg-white px-6 py-4 flex justify-between items-center border-b border-zinc-150 shrink-0">
                        <div class="flex items-center gap-2">
                            <i class="ph ph-user-circle text-zinc-400 text-lg"></i>
                            <div>
                                <h3 class="text-xs font-bold text-zinc-900 font-sans">User Details</h3>
                                <p class="text-[9px] text-zinc-400 mt-0.5">Lihat informasi pengguna</p>
                            </div>
                        </div>
                        <button wire:click="closeModal" class="w-5 h-5 flex items-center justify-center rounded hover:bg-zinc-100 text-zinc-400 hover:text-zinc-650 transition-colors">
                            <i class="ph ph-x text-sm"></i>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6 overflow-y-auto custom-scrollbar space-y-5">
                        @php
                            $editingUser = $editingUserId ? \App\Models\User::find($editingUserId) : null;
                        @endphp
                        
                        <!-- Mini Profile Header -->
                        @if($editingUser)
                            <div class="flex items-center gap-3 pb-4 border-b border-zinc-100">
                                <div class="w-12 h-12 rounded bg-zinc-100 border border-zinc-200 flex items-center justify-center text-zinc-700 font-bold text-sm overflow-hidden flex-shrink-0">
                                    <img src="{{ $editingUser->avatar_url }}" 
                                         alt="{{ $editName ?? 'User' }}" 
                                         class="w-full h-full object-cover"
                                         onerror="this.style.display='none';">
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-bold text-zinc-900 truncate leading-none mb-1.5">{{ $editName ?? '-' }}</h4>
                                    <p class="text-[10px] text-zinc-455 font-mono truncate leading-none">{{ $editEmail ?? '-' }}</p>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Simple Info Data Fields -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide mb-1">Status Premium</label>
                                <div class="h-8 px-2.5 bg-zinc-50 border border-zinc-200/80 rounded text-xs font-semibold flex items-center">
                                    @if($editIsPremium)
                                        <span class="text-purple-750 flex items-center gap-1"><i class="ph-fill ph-crown text-[10px]"></i> Premium</span>
                                    @else
                                        <span class="text-zinc-500">Free Tier</span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide mb-1">Status Admin</label>
                                <div class="h-8 px-2.5 bg-zinc-50 border border-zinc-200/80 rounded text-xs font-semibold flex items-center">
                                    @if($editIsAdmin)
                                        <span class="text-zinc-800 flex items-center gap-1"><i class="ph-fill ph-shield-check text-[10px]"></i> Admin</span>
                                    @else
                                        <span class="text-zinc-500">User</span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide mb-1">Total Lamaran</label>
                                <div class="h-8 px-2.5 bg-zinc-50 border border-zinc-200/80 rounded text-xs text-zinc-700 flex items-center">
                                    {{ $editingUser ? $editingUser->getJobApplicationsCount() : 0 }} lamaran
                                </div>
                            </div>

                            <div>
                                <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide mb-1">Bergabung Sejak</label>
                                <div class="h-8 px-2.5 bg-zinc-50 border border-zinc-200/80 rounded text-xs text-zinc-700 flex items-center">
                                    {{ $editingUser && $editingUser->created_at ? $editingUser->created_at->format('d M Y') : '-' }}
                                </div>
                            </div>
                        </div>
                        
                        <!-- AI Credits Quota Admin Management -->
                        <div class="pt-4 border-t border-zinc-150">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-[9px] font-mono font-bold text-zinc-455 uppercase tracking-wider flex items-center gap-1">
                                    <i class="ph ph-robot text-xs"></i> AI Quotas Management
                                </h4>
                                <button type="button" wire:click="resetAllAiQuotas" class="text-[8px] font-mono font-bold text-purple-750 uppercase tracking-wider hover:underline transition-all">
                                    Reset Ke Standar
                                </button>
                            </div>
                            
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide mb-1 text-center">Analyzer</label>
                                    <input type="number" wire:model="editAiCredits" class="w-full h-8 px-2 bg-zinc-50 border border-zinc-250 focus:border-zinc-400 focus:bg-white rounded text-xs text-zinc-800 font-mono text-center focus:outline-none focus:ring-0 transition-colors" min="0">
                                </div>
                                
                                <div>
                                    <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide mb-1 text-center">Cover Letter</label>
                                    <input type="number" wire:model="editClCredits" class="w-full h-8 px-2 bg-zinc-50 border border-zinc-250 focus:border-zinc-400 focus:bg-white rounded text-xs text-zinc-800 font-mono text-center focus:outline-none focus:ring-0 transition-colors" min="0">
                                </div>
                                
                                <div>
                                    <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide mb-1 text-center">AI Photo</label>
                                    <input type="number" wire:model="editPhotoCredits" class="w-full h-8 px-2 bg-zinc-50 border border-zinc-250 focus:border-zinc-400 focus:bg-white rounded text-xs text-zinc-800 font-mono text-center focus:outline-none focus:ring-0 transition-colors" min="0">
                                </div>
                            </div>
                            
                            @if($editingUser)
                                <div class="mt-4 flex justify-between items-center bg-zinc-50 border border-zinc-200 rounded p-3">
                                    <div class="text-[10px] text-zinc-500">
                                        Trial Analyzer Dipakai: 
                                        <span class="font-bold text-zinc-700 font-mono">
                                            {{ $editingUser->has_used_ai_analyzer_trial ? ($editingUser->ai_analyzer_trial_used_at ? $editingUser->ai_analyzer_trial_used_at->format('d M Y') : 'Ya') : 'Belum' }}
                                        </span>
                                    </div>
                                    <button type="button" wire:click="saveAiQuotas" class="h-7 px-3 bg-zinc-900 hover:bg-zinc-800 text-white rounded text-[10px] font-semibold transition-colors flex items-center justify-center">
                                        Simpan Quota
                                    </button>
                                </div>
                            @endif
                        </div>

                        <!-- Footer -->
                        <div class="pt-2 flex justify-end gap-2 border-t border-zinc-100">
                            <button type="button" wire:click="closeModal" class="h-8 px-4 border border-zinc-250 hover:bg-zinc-50 text-zinc-700 rounded text-xs font-semibold transition-colors flex items-center justify-center">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif

    <!-- Modal: Create New Admin -->
    @if($showCreateAdminModal)
        <template x-teleport="body">
            <div class="fixed inset-0 bg-zinc-950/20 backdrop-blur-[2px] z-50 flex items-center justify-center p-4" style="z-index: 9999;">
                <div class="bg-white rounded border border-zinc-200 shadow-sm max-w-md w-full max-h-[90vh] flex flex-col overflow-hidden">
                    
                    <!-- Modal Header -->
                    <div class="bg-white px-6 py-4 flex justify-between items-center border-b border-zinc-150 shrink-0">
                        <div class="flex items-center gap-2">
                            <i class="ph ph-user-plus text-zinc-400 text-lg"></i>
                            <div>
                                <h3 class="text-xs font-bold text-zinc-900">Tambah Admin Baru</h3>
                                <p class="text-[9px] text-zinc-400 mt-0.5">Administrator Access Setup</p>
                            </div>
                        </div>
                        <button wire:click="closeCreateAdminModal" class="w-5 h-5 flex items-center justify-center rounded hover:bg-zinc-100 text-zinc-400 hover:text-zinc-650 transition-colors">
                            <i class="ph ph-x text-sm"></i>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6 overflow-y-auto custom-scrollbar">
                        <form wire:submit.prevent="createAdmin" class="space-y-4">
                            <div>
                                <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide mb-1">Nama Lengkap *</label>
                                <input type="text" wire:model="newAdminName" 
                                       class="w-full h-8 px-3 bg-zinc-50 border border-zinc-250 focus:border-zinc-400 focus:bg-white rounded text-xs text-zinc-800 focus:outline-none focus:ring-0 transition-colors" placeholder="Nama lengkap admin">
                                @error('newAdminName') 
                                    <p class="text-red-650 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide mb-1">Alamat Email *</label>
                                <input type="email" wire:model="newAdminEmail" 
                                       class="w-full h-8 px-3 bg-zinc-50 border border-zinc-250 focus:border-zinc-400 focus:bg-white rounded text-xs text-zinc-800 focus:outline-none focus:ring-0 transition-colors" placeholder="admin@example.com">
                                @error('newAdminEmail') 
                                    <p class="text-red-650 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide mb-1">Password *</label>
                                    <input type="password" wire:model="newAdminPassword" 
                                           class="w-full h-8 px-3 bg-zinc-50 border border-zinc-250 focus:border-zinc-400 focus:bg-white rounded text-xs text-zinc-800 focus:outline-none focus:ring-0 transition-colors" placeholder="Min. 8 karakter">
                                    @error('newAdminPassword') 
                                        <p class="text-red-650 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide mb-1">Konfirmasi Password *</label>
                                    <input type="password" wire:model="newAdminPasswordConfirmation" 
                                           class="w-full h-8 px-3 bg-zinc-50 border border-zinc-250 focus:border-zinc-400 focus:bg-white rounded text-xs text-zinc-800 focus:outline-none focus:ring-0 transition-colors" placeholder="Ulangi password">
                                </div>
                            </div>

                            <div class="bg-zinc-50 border border-zinc-200 rounded p-3 text-[10px] text-zinc-500 leading-relaxed flex gap-2">
                                <i class="ph ph-info text-zinc-450 text-xs shrink-0 mt-0.5"></i>
                                <div>
                                    <span class="font-bold text-zinc-700 block mb-0.5">Akses Administrator</span>
                                    Admin baru otomatis mendapatkan akses penuh ke admin portal, status premium, dan status verifikasi email aktif.
                                </div>
                            </div>

                            <div class="pt-4 flex justify-end gap-2 border-t border-zinc-100">
                                <button type="button" wire:click="closeCreateAdminModal" class="h-8 px-4 border border-zinc-250 hover:bg-zinc-50 text-zinc-700 rounded text-xs font-semibold transition-colors flex items-center justify-center">
                                    Batal
                                </button>
                                <button type="submit" class="h-8 px-4 bg-zinc-900 hover:bg-zinc-800 text-white rounded text-xs font-semibold transition-colors flex items-center justify-center">
                                    Buat Admin
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    @endif

    <!-- Modal: Send Select Template Email -->
    @if($showSendEmailModal)
        <template x-teleport="body">
            <div class="fixed inset-0 bg-zinc-950/20 backdrop-blur-[2px] z-50 flex items-center justify-center p-4" style="z-index: 9999;">
                <div class="bg-white rounded border border-zinc-200 shadow-sm max-w-xl w-full max-h-[90vh] flex flex-col overflow-hidden">
                    
                    <!-- Modal Header -->
                    <div class="bg-white px-6 py-4 flex justify-between items-center border-b border-zinc-150 shrink-0">
                        <div class="flex items-center gap-2">
                            <i class="ph ph-paper-plane-tilt text-zinc-400 text-lg"></i>
                            <div>
                                <h3 class="text-xs font-bold text-zinc-900">Kirim Email</h3>
                                <p class="text-[9px] text-zinc-400 mt-0.5">Pilih tipe template email yang akan dikirim</p>
                            </div>
                        </div>
                        <button wire:click="closeSendEmailModal" class="w-5 h-5 flex items-center justify-center rounded hover:bg-zinc-100 text-zinc-400 hover:text-zinc-650 transition-colors">
                            <i class="ph ph-x text-sm"></i>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6 overflow-y-auto custom-scrollbar space-y-4">
                        <!-- Recipient preview card -->
                        <div class="bg-zinc-50 border border-zinc-200 rounded p-3 flex items-center gap-3">
                            <div class="w-8 h-8 bg-zinc-200 rounded flex items-center justify-center font-bold text-zinc-700 text-xs shrink-0">
                                {{ strtoupper(substr($emailTargetUserName ?? 'U', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-zinc-900 truncate leading-none mb-1.5">{{ $emailTargetUserName ?? '-' }}</p>
                                <p class="text-[10px] text-zinc-455 font-mono truncate leading-none">{{ $emailTargetUserEmail ?? '-' }}</p>
                            </div>
                        </div>

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
                                            <label class="relative flex cursor-pointer rounded border p-2.5 transition-all {{ $isSelected ? 'border-zinc-900 bg-zinc-50/50 shadow-none' : 'border-zinc-200/80 bg-white hover:bg-zinc-50/50' }}">
                                                <input type="radio" wire:model.live="emailType" value="{{ $type }}" class="sr-only">
                                                <div class="flex items-center gap-2.5 w-full">
                                                    <div class="w-7 h-7 rounded bg-zinc-100 border border-zinc-200/50 flex items-center justify-center text-zinc-500">
                                                        <i class="ph {{ $info['icon'] }} text-xs"></i>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-[10px] font-bold text-zinc-800 leading-none mb-1">{{ $info['label'] }}</p>
                                                        <p class="text-[8px] text-zinc-400 truncate leading-none">{{ $info['desc'] }}</p>
                                                    </div>
                                                    <div class="w-3.5 h-3.5 rounded-full border flex items-center justify-center shrink-0 {{ $isSelected ? 'border-zinc-900 bg-zinc-900' : 'border-zinc-300 bg-transparent' }}">
                                                        @if($isSelected)
                                                            <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Footer -->
                        <div class="pt-4 flex justify-end gap-2 border-t border-zinc-100">
                            <button type="button" wire:click="closeSendEmailModal" class="h-8 px-4 border border-zinc-250 hover:bg-zinc-50 text-zinc-700 rounded text-xs font-semibold transition-colors flex items-center justify-center">
                                Batal
                            </button>
                            <button type="button" wire:click="sendEmail" class="h-8 px-4 bg-zinc-900 hover:bg-zinc-800 text-white rounded text-xs font-semibold transition-colors flex items-center justify-center">
                                Kirim Email
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif

    <!-- Modal: Manual Premium Switch Override Confirm -->
    @if($showPremiumConfirmModal)
        <template x-teleport="body">
            <div class="fixed inset-0 bg-zinc-950/20 backdrop-blur-[2px] z-50 flex items-center justify-center p-4" style="z-index: 9999;">
                <div class="bg-white rounded border border-zinc-200 shadow-sm max-w-sm w-full relative">
                    
                    <!-- Header -->
                    <div class="bg-white px-6 py-4 flex justify-between items-center border-b border-zinc-150 shrink-0">
                        <div class="flex items-center gap-2">
                            <i class="ph ph-crown text-zinc-400 text-base"></i>
                            <div>
                                <h3 class="text-xs font-bold text-zinc-900 font-sans">Premium Override</h3>
                                <p class="text-[9px] text-zinc-400 mt-0.5">Manual Status Update</p>
                            </div>
                        </div>
                        <button wire:click="closePremiumConfirmModal" class="w-5 h-5 flex items-center justify-center rounded hover:bg-zinc-100 text-zinc-400 hover:text-zinc-650 transition-colors">
                            <i class="ph ph-x text-sm"></i>
                        </button>
                    </div>

                    <div class="p-6">
                        <div class="bg-zinc-50 border border-zinc-200 rounded p-3 mb-4">
                            <span class="text-xs font-bold text-zinc-900 block truncate mb-1.5">{{ $confirmTargetUserName }}</span>
                            <span class="text-[9px] text-zinc-500 block leading-none">
                                Status Saat Ini: 
                                <span class="font-bold {{ $confirmTargetUserIsPremium ? 'text-purple-750' : 'text-zinc-600' }}">
                                    {{ $confirmTargetUserIsPremium ? 'Premium' : 'Free Tier' }}
                                </span>
                            </span>
                        </div>

                        <div class="flex justify-end gap-2">
                            <button wire:click="closePremiumConfirmModal" 
                                    class="h-8 px-4 border border-zinc-250 hover:bg-zinc-50 text-zinc-700 rounded font-semibold text-xs transition-colors flex items-center justify-center">
                                Batal
                            </button>
                            <button wire:click="toggleManualPremium" 
                                    class="h-8 px-4 {{ $confirmTargetUserIsPremium ? 'bg-zinc-900 hover:bg-zinc-800' : 'bg-purple-700 hover:bg-purple-800' }} text-white rounded font-semibold text-xs transition-colors flex items-center justify-center">
                                {{ $confirmTargetUserIsPremium ? 'Downgrade ke Free' : 'Upgrade ke Premium' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif

    <!-- Modal: Delete User Confirm (Notion Flat Layout) -->
    <template x-teleport="body">
        <div id="deleteUserModal" class="fixed inset-0 bg-zinc-950/20 backdrop-blur-[2px] z-50 hidden items-center justify-center p-4" style="z-index: 9999;">
            <div class="absolute inset-0" onclick="closeDeleteUserModal()"></div>
            <div class="bg-white rounded border border-zinc-200 shadow-sm max-w-sm w-full relative z-10 transition-all scale-98 opacity-0 duration-150" id="deleteUserModalContent">
                
                <!-- Header -->
                <div class="bg-white px-6 py-4 flex justify-between items-center border-b border-zinc-150 shrink-0">
                    <div class="flex items-center gap-2">
                        <i class="ph ph-warning text-red-500 text-base"></i>
                        <div>
                            <h3 class="text-xs font-bold text-zinc-900 font-sans">Hapus User</h3>
                            <p class="text-[9px] text-zinc-400 mt-0.5">Aksi ini bersifat permanen</p>
                        </div>
                    </div>
                    <button onclick="closeDeleteUserModal()" class="w-5 h-5 flex items-center justify-center rounded hover:bg-zinc-100 text-zinc-400 hover:text-zinc-650 transition-colors">
                        <i class="ph ph-x text-sm"></i>
                    </button>
                </div>

                <div class="p-6">
                    <div class="bg-red-50/30 border border-red-100 rounded p-3 mb-4 text-left">
                        <span class="text-xs font-bold text-zinc-900 block truncate mb-1.5" id="deleteUserName"></span>
                        <span class="text-[10px] text-zinc-500 block font-mono leading-none" id="deleteUserEmail"></span>
                    </div>

                    <div class="bg-zinc-50 border border-zinc-200 rounded p-3 text-[10px] text-zinc-500 leading-relaxed mb-5 flex gap-2 text-left">
                        <i class="ph ph-info text-zinc-400 text-xs shrink-0 mt-0.5"></i>
                        <div>
                            Seluruh data lamaran kerja, riwayat aktivitas, dan quota AI pengguna ini akan dihapus secara permanen dari database.
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeDeleteUserModal()" 
                                class="h-8 px-4 border border-zinc-250 hover:bg-zinc-50 text-zinc-700 rounded font-semibold text-xs transition-colors flex items-center justify-center">
                            Batal
                        </button>
                        <button type="button" onclick="confirmDeleteUserAction()" 
                                class="h-8 px-4 bg-red-600 hover:bg-red-700 text-white rounded font-semibold text-xs transition-colors flex items-center justify-center">
                            Ya, Hapus User
                        </button>
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
            content.classList.remove('scale-98', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeDeleteUserModal() {
        const modal = document.getElementById('deleteUserModal');
        const content = document.getElementById('deleteUserModalContent');
        
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-98', 'opacity-0');
        
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
