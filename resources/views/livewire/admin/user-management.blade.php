@php
    if (!function_exists('getAvatarStyleLocal')) {
        function getAvatarStyleLocal($name) {
            $char = strtoupper(substr($name ?? 'A', 0, 1));
            $colors = [
                'A' => ['#4f46e5', '#3b82f6'],
                'B' => ['#3b82f6', '#0ea5e9'],
                'C' => ['#0ea5e9', '#06b6d4'],
                'D' => ['#06b6d4', '#14b8a6'],
                'E' => ['#14b8a6', '#10b981'],
                'F' => ['#10b981', '#22c55e'],
                'G' => ['#22c55e', '#84cc16'],
                'H' => ['#84cc16', '#eab308'],
                'I' => ['#eab308', '#f97316'],
                'J' => ['#f97316', '#ef4444'],
                'K' => ['#ef4444', '#f43f5e'],
                'L' => ['#f43f5e', '#0ea5e9'],
                'M' => ['#52525b', '#27272a'],
                'N' => ['#71717a', '#3f3f46'],
                'O' => ['#4f46e5', '#0ea5e9'],
                'P' => ['#14b8a6', '#22c55e'],
                'Q' => ['#f97316', '#ef4444'],
                'R' => ['#eab308', '#f97316'],
                'S' => ['#0ea5e9', '#3b82f6'],
                'T' => ['#10b981', '#14b8a6'],
                'U' => ['#06b6d4', '#3b82f6'],
                'V' => ['#f43f5e', '#f97316'],
                'W' => ['#22c55e', '#0ea5e9'],
                'X' => ['#52525b', '#71717a'],
                'Y' => ['#4f46e5', '#14b8a6'],
                'Z' => ['#ef4444', '#f97316'],
            ];
            $pair = $colors[$char] ?? ['#71717a', '#52525b'];
            return "background: linear-gradient(135deg, {$pair[0]} 0%, {$pair[1]} 100%);";
        }
    }
@endphp
<div class="space-y-6 pb-12">
    
    <!-- Header Section (Sleek and Clean) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-zinc-200">
        <div>
            <h1 class="text-lg font-black text-zinc-900 tracking-tight flex items-center gap-1.5">
                User Management <span class="text-[9px] font-mono font-bold text-zinc-450 bg-zinc-100 border border-zinc-200 px-1.5 py-0.2 rounded">Global Users</span>
            </h1>
            <p class="text-[10px] text-zinc-500 font-semibold mt-0.5">View and manage registered accounts, premium subscriptions, and operational roles.</p>
        </div>
    </div>

    <!-- Notion-style Clean Stats Grid (5-Column Premium Bento) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        <!-- Total Users -->
        <div class="bg-white rounded-xl border border-zinc-200 p-4 hover:border-zinc-300 transition-all flex flex-col justify-between group shadow-[0_4px_20px_rgba(0,0,0,0.008)]">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest block mb-0.5">Total Users</span>
                    <h3 class="text-xl font-extrabold tracking-tight text-zinc-900 font-mono leading-none">{{ number_format($stats['total']) }}</h3>
                </div>
                <div class="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-950 group-hover:text-white transition duration-200 shrink-0">
                    <i class="ph ph-users-three text-xs"></i>
                </div>
            </div>
            <p class="text-[10px] text-zinc-400 font-bold mt-4">All members registered</p>
        </div>

        <!-- Premium Users -->
        <div class="bg-white rounded-xl border border-zinc-200 p-4 hover:border-zinc-300 transition-all flex flex-col justify-between group shadow-[0_4px_20px_rgba(0,0,0,0.008)]">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest block mb-0.5">Premium Users</span>
                    <h3 class="text-xl font-extrabold tracking-tight text-zinc-900 font-mono leading-none">{{ number_format($stats['premium']) }}</h3>
                </div>
                <div class="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-950 group-hover:text-white transition duration-200 shrink-0">
                    <i class="ph ph-crown text-xs"></i>
                </div>
            </div>
            <p class="text-[10px] text-zinc-400 font-bold mt-4">Active paid accounts</p>
        </div>

        <!-- Free Users -->
        <div class="bg-white rounded-xl border border-zinc-200 p-4 hover:border-zinc-300 transition-all flex flex-col justify-between group shadow-[0_4px_20px_rgba(0,0,0,0.008)]">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest block mb-0.5">Free Users</span>
                    <h3 class="text-xl font-extrabold tracking-tight text-zinc-900 font-mono leading-none">{{ number_format($stats['free']) }}</h3>
                </div>
                <div class="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-950 group-hover:text-white transition duration-200 shrink-0">
                    <i class="ph ph-user text-xs"></i>
                </div>
            </div>
            <p class="text-[10px] text-zinc-400 font-bold mt-4">Standard basic tier</p>
        </div>

        <!-- Verified Users -->
        <div class="bg-white rounded-xl border border-zinc-200 p-4 hover:border-zinc-300 transition-all flex flex-col justify-between group shadow-[0_4px_20px_rgba(0,0,0,0.008)]">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest block mb-0.5">Verified</span>
                    <h3 class="text-xl font-extrabold tracking-tight text-zinc-900 font-mono leading-none">{{ number_format($stats['verified']) }}</h3>
                </div>
                <div class="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-950 group-hover:text-white transition duration-200 shrink-0">
                    <i class="ph ph-check-circle text-xs"></i>
                </div>
            </div>
            <p class="text-[10px] text-zinc-400 font-bold mt-4">Email verified status</p>
        </div>

        <!-- AI Trial Used -->
        <div class="bg-white rounded-xl border border-zinc-200 p-4 hover:border-zinc-300 transition-all flex flex-col justify-between group col-span-2 sm:col-span-1 shadow-[0_4px_20px_rgba(0,0,0,0.008)]">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest block mb-0.5">AI Trials Used</span>
                    <h3 class="text-xl font-extrabold tracking-tight text-zinc-900 font-mono leading-none">{{ number_format($stats['ai_analyzer_trial_used']) }}</h3>
                </div>
                <div class="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-950 group-hover:text-white transition duration-200 shrink-0">
                    <i class="ph-bold ph-lightning text-xs"></i>
                </div>
            </div>
            <p class="text-[10px] text-zinc-400 font-bold mt-4">Resume analyses run</p>
        </div>
    </div>

    <!-- Filters & Actions Utilities Bar -->
    <div class="bg-zinc-50 border border-zinc-200 p-2.5 rounded-xl flex flex-col lg:flex-row gap-2.5 items-center justify-between shadow-[0_2px_8px_rgba(0,0,0,0.01)]">
        <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto flex-1">
            <!-- Search -->
            <div class="relative w-full sm:max-w-xs">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-400">
                    <i class="ph ph-magnifying-glass text-xs"></i>
                </div>
                <input type="text" wire:model.live="search" placeholder="Cari nama atau email..." 
                       class="w-full h-8 pl-8.5 pr-3 bg-white border border-zinc-200 focus:border-zinc-400 rounded-lg text-xs font-semibold text-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-900/5 transition-all placeholder:text-zinc-400">
            </div>

            <!-- Premium Filter -->
            <div class="relative w-full sm:w-36">
                <select wire:model.live="filterPremium" 
                        class="w-full h-8 pl-3 pr-8 bg-white border border-zinc-200 focus:border-zinc-400 rounded-lg text-xs font-semibold text-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-900/5 transition-all bg-none appearance-none cursor-pointer">
                    <option value="all">Semua Status</option>
                    <option value="premium">Premium</option>
                    <option value="free">Free</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-zinc-400">
                    <i class="ph ph-caret-down text-[10px]"></i>
                </div>
            </div>

            <!-- AI Trial Filter -->
            <div class="relative w-full sm:w-40">
                <select wire:model.live="filterAiAnalyzer" 
                        class="w-full h-8 pl-3 pr-8 bg-white border border-zinc-200 focus:border-zinc-400 rounded-lg text-xs font-semibold text-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-900/5 transition-all bg-none appearance-none cursor-pointer">
                    <option value="all">Semua AI Trial</option>
                    <option value="used">Sudah Pakai</option>
                    <option value="not_used">Belum Pakai</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-zinc-400">
                    <i class="ph ph-caret-down text-[10px]"></i>
                </div>
            </div>
            
            <!-- Rows per page -->
            <div class="relative w-full sm:w-28 hidden md:block">
                <select wire:model.live="perPage" 
                        class="w-full h-8 pl-3 pr-8 bg-white border border-zinc-200 focus:border-zinc-400 rounded-lg text-xs font-semibold text-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-900/5 transition-all bg-none appearance-none cursor-pointer">
                    <option value="10">10 Baris</option>
                    <option value="20">20 Baris</option>
                    <option value="50">50 Baris</option>
                    <option value="100">100 Baris</option>
                    <option value="all">Semua</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-zinc-400">
                    <i class="ph ph-caret-down text-[10px]"></i>
                </div>
            </div>
        </div>

        <!-- Add Admin Button -->
        <div class="w-full lg:w-auto shrink-0 flex items-center">
            <button wire:click="openCreateAdminModal" 
                    class="w-full sm:w-auto h-8 px-4 bg-zinc-900 text-white hover:bg-zinc-800 rounded-lg transition-all flex items-center justify-center gap-1.5 text-xs font-bold shadow-3xs hover:shadow-2xs">
                <i class="ph ph-user-plus text-xs"></i>
                Add Admin
            </button>
        </div>
    </div>

    <!-- Users Table (Premium Plain Database Visual) -->
    <div class="bg-white rounded-xl border border-zinc-200 overflow-hidden flex flex-col relative min-h-[480px] shadow-[0_4px_20px_rgba(0,0,0,0.008)]">
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
                                        <div class="flex-shrink-0 h-7 w-7 rounded-lg overflow-hidden border border-zinc-200 shadow-3xs">
                                            <img src="{{ $user->avatar_url }}" 
                                                 alt="{{ $user->name }}" 
                                                 class="w-full h-full object-cover">
                                        </div>
                                    @else
                                        <div class="flex-shrink-0 h-7 w-7 rounded-lg overflow-hidden border border-zinc-200 shadow-3xs flex items-center justify-center text-white text-[10px] font-black"
                                             style="{{ getAvatarStyleLocal($user->name) }}">
                                            <span class="pointer-events-none">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
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
                                    <span class="px-1.5 py-0.5 text-[8px] font-bold rounded bg-amber-50 text-amber-700 border border-amber-200/60 inline-flex items-center gap-0.5 shadow-3xs">
                                        <i class="ph-bold ph-crown text-[8px]"></i> Premium
                                    </span>
                                @else
                                    <span class="px-1.5 py-0.5 text-[8px] font-semibold rounded bg-zinc-100 text-zinc-650 border border-zinc-200/50 inline-flex items-center">
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
                                        <div class="w-5 h-5 flex items-center justify-center text-zinc-450 bg-zinc-50 border border-zinc-150 rounded-md" title="Email/Password Login">
                                            <i class="ph ph-envelope text-xs"></i>
                                        </div>
                                    @endif

                                    @if($user->google_id)
                                        <div class="w-5 h-5 flex items-center justify-center text-zinc-500 bg-zinc-50 border border-zinc-150 rounded-md font-mono font-bold text-[9px]" title="Google OAuth">
                                            G
                                        </div>
                                    @endif

                                    @if($user->linkedin_id)
                                        <div class="w-5 h-5 flex items-center justify-center text-zinc-500 bg-zinc-50 border border-zinc-150 rounded-md font-mono font-bold text-[9px]" title="LinkedIn OAuth">
                                            L
                                        </div>
                                    @endif
                                    
                                    @if(!$user->password && !$user->google_id && !$user->linkedin_id)
                                        <div class="w-5 h-5 flex items-center justify-center text-zinc-300 bg-zinc-50 border border-zinc-150 rounded-md" title="None">
                                            <i class="ph ph-question text-xs"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <!-- Action buttons -->
                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <!-- Send Email Action -->
                                    <button wire:click="openSendEmailModal({{ $user->id }})" 
                                            class="w-7 h-7 flex items-center justify-center text-zinc-500 hover:text-zinc-950 hover:bg-zinc-100 rounded-lg transition-all" 
                                            title="Send Email">
                                        <i class="ph-bold ph-paper-plane-tilt text-[11px]"></i>
                                    </button>

                                    <!-- Premium Manual Switch Action -->
                                    <button wire:click="openPremiumConfirmModal({{ $user->id }})" 
                                            class="w-7 h-7 flex items-center justify-center rounded-lg transition-all {{ $user->is_premium ? 'text-amber-600 bg-amber-50 hover:bg-amber-100' : 'text-zinc-500 hover:text-zinc-950 hover:bg-zinc-100' }}" 
                                            title="Manual Premium Override">
                                        <i class="ph-bold ph-crown text-[11px]"></i>
                                    </button>

                                    <!-- View Details Action -->
                                    <button wire:click="editUser({{ $user->id }})" 
                                            class="w-7 h-7 flex items-center justify-center text-zinc-500 hover:text-zinc-955 hover:bg-zinc-100 rounded-lg transition-all" 
                                            title="View Details">
                                        <i class="ph-bold ph-eye text-[11px]"></i>
                                    </button>

                                    <!-- Delete Action -->
                                    <button onclick="confirmDeleteUser({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}')"
                                            class="w-7 h-7 flex items-center justify-center text-red-650 hover:text-red-700 hover:bg-red-50 rounded-lg transition-all" 
                                            title="Delete User">
                                        <i class="ph-bold ph-trash text-[11px]"></i>
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
        <div class="px-4 py-3 bg-zinc-50 border-t border-zinc-200 flex flex-col sm:flex-row items-center justify-between gap-3 shrink-0">
            <div class="text-[10px] text-zinc-450 font-bold">
                Menampilkan <span class="font-extrabold text-zinc-800">{{ $users->firstItem() ?? 0 }}</span> - <span class="font-extrabold text-zinc-800">{{ $users->lastItem() ?? $users->count() }}</span> dari <span class="font-extrabold text-zinc-800">{{ $users->total() }}</span> user
            </div>
            <div class="w-full sm:w-auto notion-pagination font-mono text-[10px]">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- Modal: View Details / Edit Quota -->
    @if($showEditModal)
        <template x-teleport="body">
            <div class="fixed inset-0 bg-zinc-955/35 backdrop-blur-[3px] z-50 flex items-center justify-center p-4" style="z-index: 9999;">
                <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-xl max-w-[460px] w-full max-h-[90vh] flex flex-col overflow-hidden">
                    
                    <!-- Modal Header -->
                    <div class="bg-white rounded-t-2xl px-6 sm:px-7 py-5 flex justify-between items-center border-b border-zinc-100 shrink-0">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500 shrink-0 shadow-3xs">
                                <i class="ph-bold ph-user-focus text-sm"></i>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-zinc-900 font-sans">User Details</h3>
                                <p class="text-[9px] text-zinc-400 font-semibold mt-0.5">Lihat informasi detail akun pengguna</p>
                            </div>
                        </div>
                        <button wire:click="closeModal" class="w-6 h-6 flex items-center justify-center rounded-lg hover:bg-zinc-100 text-zinc-400 hover:text-zinc-650 transition-all">
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
                            <div class="flex items-center gap-4 pb-4 border-b border-zinc-100">
                                @if($editingUser->logo)
                                    <div class="w-12 h-12 rounded-full border border-zinc-200 overflow-hidden flex-shrink-0 shadow-3xs">
                                        <img src="{{ $editingUser->avatar_url }}" 
                                             alt="{{ $editName ?? 'User' }}" 
                                             class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="w-12 h-12 rounded-full border border-zinc-200 overflow-hidden flex-shrink-0 flex items-center justify-center text-white font-black text-sm shadow-3xs"
                                         style="{{ getAvatarStyleLocal($editName) }}">
                                        <span class="pointer-events-none">{{ strtoupper(substr($editName ?? 'U', 0, 1)) }}</span>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <h4 class="text-sm font-extrabold text-zinc-900 truncate leading-tight mb-1">{{ $editName ?? '-' }}</h4>
                                    <p class="text-xs text-zinc-500 font-semibold truncate leading-none">{{ $editEmail ?? '-' }}</p>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Simple Info Data Fields (Notion Style Grid) -->
                        <div class="grid grid-cols-2 gap-x-6 gap-y-4 py-1">
                            <div class="flex flex-col gap-1">
                                <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider font-mono">Status Premium</span>
                                <div class="text-xs font-bold text-zinc-800 flex items-center h-5">
                                    @if($editIsPremium)
                                        <span class="text-amber-600 flex items-center gap-1"><i class="ph-bold ph-crown"></i> Premium</span>
                                    @else
                                        <span class="text-zinc-550 font-medium">Free Tier</span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex flex-col gap-1">
                                <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider font-mono">Status Admin</span>
                                <div class="text-xs font-bold text-zinc-800 flex items-center h-5">
                                    @if($editIsAdmin)
                                        <span class="text-zinc-800 flex items-center gap-1"><i class="ph-bold ph-shield-check"></i> Admin</span>
                                    @else
                                        <span class="text-zinc-550 font-medium">User</span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex flex-col gap-1">
                                <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider font-mono">Total Lamaran</span>
                                <div class="text-xs font-bold text-zinc-800 flex items-center h-5">
                                    {{ $editingUser ? $editingUser->getJobApplicationsCount() : 0 }} lamaran
                                </div>
                            </div>

                            <div class="flex flex-col gap-1">
                                <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider font-mono">Bergabung Sejak</span>
                                <div class="text-xs font-bold text-zinc-800 flex items-center h-5">
                                    {{ $editingUser && $editingUser->created_at ? $editingUser->created_at->format('d M Y') : '-' }}
                                </div>
                            </div>
                        </div>
                        
                        <!-- AI Credits Quota Admin Management -->
                        <div class="pt-4.5 border-t border-zinc-100 space-y-3.5">
                            <div class="flex items-center justify-between">
                                <h4 class="text-[10px] font-mono font-bold text-zinc-450 uppercase tracking-wider flex items-center gap-1.5">
                                    <i class="ph-bold ph-cpu text-xs"></i> AI Quotas Management
                                </h4>
                                <button type="button" wire:click="resetAllAiQuotas" class="text-[9px] font-mono font-bold text-zinc-500 hover:text-zinc-900 uppercase tracking-wider hover:underline transition-all">
                                    Reset Ke Standar
                                </button>
                            </div>
                            
                            <!-- Unified Quota Cards List -->
                            <div class="border border-zinc-200 rounded-2xl overflow-hidden bg-white shadow-3xs">
                                <!-- Analyzer -->
                                <div class="flex items-center justify-between p-3.5 border-b border-zinc-100">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-150 flex items-center justify-center text-zinc-500 shrink-0">
                                            <i class="ph-bold ph-chart-bar text-sm"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <span class="text-xs font-bold text-zinc-850 block leading-tight">Analyzer Credits</span>
                                            <span class="text-[10px] text-zinc-400 font-bold block mt-0.5">Analisis kecocokan resume & lowongan</span>
                                        </div>
                                    </div>
                                    <input type="number" wire:model="editAiCredits" class="w-16 h-8 px-2.5 bg-zinc-50 border border-zinc-200 focus:border-zinc-400 focus:bg-white rounded-lg text-xs font-bold text-zinc-800 text-center focus:outline-none focus:ring-2 focus:ring-zinc-900/5 transition-all shadow-3xs" min="0">
                                </div>

                                <!-- Cover Letter -->
                                <div class="flex items-center justify-between p-3.5 border-b border-zinc-100">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-150 flex items-center justify-center text-zinc-500 shrink-0">
                                            <i class="ph-bold ph-file-text text-sm"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <span class="text-xs font-bold text-zinc-850 block leading-tight">Cover Letter Credits</span>
                                            <span class="text-[10px] text-zinc-400 font-bold block mt-0.5">Pembuatan surat lamaran kerja AI</span>
                                        </div>
                                    </div>
                                    <input type="number" wire:model="editClCredits" class="w-16 h-8 px-2.5 bg-zinc-50 border border-zinc-200 focus:border-zinc-400 focus:bg-white rounded-lg text-xs font-bold text-zinc-800 text-center focus:outline-none focus:ring-2 focus:ring-zinc-900/5 transition-all shadow-3xs" min="0">
                                </div>

                                <!-- AI Photo -->
                                <div class="flex items-center justify-between p-3.5">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-150 flex items-center justify-center text-zinc-500 shrink-0">
                                            <i class="ph-bold ph-camera text-sm"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <span class="text-xs font-bold text-zinc-850 block leading-tight">AI Photo Credits</span>
                                            <span class="text-[10px] text-zinc-400 font-bold block mt-0.5">Pembuatan pas foto formal studio AI</span>
                                        </div>
                                    </div>
                                    <input type="number" wire:model="editPhotoCredits" class="w-16 h-8 px-2.5 bg-zinc-50 border border-zinc-200 focus:border-zinc-400 focus:bg-white rounded-lg text-xs font-bold text-zinc-800 text-center focus:outline-none focus:ring-2 focus:ring-zinc-900/5 transition-all shadow-3xs" min="0">
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="pt-4 flex justify-between items-center border-t border-zinc-100">
                            <div class="text-[11px] text-zinc-500 font-semibold flex items-center gap-1.5 min-w-0">
                                <i class="ph ph-info text-zinc-400 text-xs shrink-0"></i>
                                <span class="truncate">
                                    Trial Analyzer: 
                                    <strong class="text-zinc-700">
                                        {{ $editingUser && $editingUser->has_used_ai_analyzer_trial ? 'Dipakai' : 'Belum' }}
                                    </strong>
                                </span>
                            </div>
                            <div class="flex gap-2 shrink-0">
                                <button type="button" wire:click="closeModal" class="h-9 px-4 bg-zinc-100 hover:bg-zinc-200/80 text-zinc-700 rounded-xl text-xs font-bold transition-all flex items-center justify-center">
                                    Tutup
                                </button>
                                <button type="button" wire:click="saveAiQuotas" class="h-9 px-4 bg-zinc-955 hover:bg-zinc-900 text-white rounded-xl text-xs font-bold shadow-3xs hover:shadow-2xs transition-all flex items-center justify-center">
                                    Simpan Quota
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif

    <!-- Modal: Create New Admin -->
    @if($showCreateAdminModal)
        <template x-teleport="body">
            <div class="fixed inset-0 bg-zinc-955/35 backdrop-blur-[3px] z-50 flex items-center justify-center p-4" style="z-index: 9999;">
                <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-xl max-w-md w-full max-h-[90vh] flex flex-col overflow-hidden">
                    
                    <!-- Modal Header -->
                    <div class="bg-white rounded-t-2xl px-6 sm:px-7 py-5 flex justify-between items-center border-b border-zinc-100 shrink-0">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500 shrink-0 shadow-3xs">
                                <i class="ph-bold ph-user-plus text-sm"></i>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-zinc-900">Tambah Admin Baru</h3>
                                <p class="text-[9px] text-zinc-400 font-semibold mt-0.5">Administrator Access Setup</p>
                            </div>
                        </div>
                        <button wire:click="closeCreateAdminModal" class="w-6 h-6 flex items-center justify-center rounded-lg hover:bg-zinc-100 text-zinc-400 hover:text-zinc-650 transition-all">
                            <i class="ph ph-x text-sm"></i>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6 overflow-y-auto custom-scrollbar">
                        <form wire:submit.prevent="createAdmin" class="space-y-4">
                            <div>
                                <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Nama Lengkap *</label>
                                <input type="text" wire:model="newAdminName" 
                                       class="w-full h-8 px-3 bg-zinc-50 border border-zinc-200 focus:border-zinc-400 focus:bg-white rounded-lg text-xs text-zinc-850 font-semibold focus:outline-none focus:ring-2 focus:ring-zinc-900/5 transition-all shadow-3xs placeholder:text-zinc-400" placeholder="Nama lengkap admin">
                                @error('newAdminName') 
                                    <p class="text-red-650 text-[10px] mt-1 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Alamat Email *</label>
                                <input type="email" wire:model="newAdminEmail" 
                                       class="w-full h-8 px-3 bg-zinc-50 border border-zinc-200 focus:border-zinc-400 focus:bg-white rounded-lg text-xs text-zinc-850 font-semibold focus:outline-none focus:ring-2 focus:ring-zinc-900/5 transition-all shadow-3xs placeholder:text-zinc-400" placeholder="admin@example.com">
                                @error('newAdminEmail') 
                                    <p class="text-red-650 text-[10px] mt-1 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Password *</label>
                                    <input type="password" wire:model="newAdminPassword" 
                                           class="w-full h-8 px-3 bg-zinc-50 border border-zinc-200 focus:border-zinc-400 focus:bg-white rounded-lg text-xs text-zinc-850 font-semibold focus:outline-none focus:ring-2 focus:ring-zinc-900/5 transition-all shadow-3xs placeholder:text-zinc-400" placeholder="Min. 8 karakter">
                                    @error('newAdminPassword') 
                                        <p class="text-red-650 text-[10px] mt-1 font-bold">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Konfirmasi Password *</label>
                                    <input type="password" wire:model="newAdminPasswordConfirmation" 
                                           class="w-full h-8 px-3 bg-zinc-50 border border-zinc-200 focus:border-zinc-400 focus:bg-white rounded-lg text-xs text-zinc-850 font-semibold focus:outline-none focus:ring-2 focus:ring-zinc-900/5 transition-all shadow-3xs placeholder:text-zinc-400" placeholder="Ulangi password">
                                </div>
                            </div>

                            <div class="bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-[10px] text-zinc-500 leading-relaxed flex gap-2 shadow-3xs">
                                <i class="ph ph-info text-zinc-450 text-xs shrink-0 mt-0.5"></i>
                                <div>
                                    <span class="font-bold text-zinc-700 block mb-0.5">Akses Administrator</span>
                                    Admin baru otomatis mendapatkan akses penuh ke admin portal, status premium, dan status verifikasi email aktif.
                                </div>
                            </div>

                            <div class="pt-4 flex justify-end gap-2 border-t border-zinc-100">
                                <button type="button" wire:click="closeCreateAdminModal" class="h-8 px-4 border border-zinc-200 hover:bg-zinc-50 text-zinc-700 rounded-lg text-xs font-bold shadow-3xs hover:shadow-2xs transition-all flex items-center justify-center">
                                    Batal
                                </button>
                                <button type="submit" class="h-8 px-4 bg-zinc-900 hover:bg-zinc-800 text-white rounded-lg text-xs font-bold shadow-3xs hover:shadow-2xs transition-all flex items-center justify-center">
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
            <div class="fixed inset-0 bg-zinc-955/35 backdrop-blur-[3px] z-50 flex items-center justify-center p-4" style="z-index: 9999;">
                <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-xl max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden">
                    
                    <!-- Modal Header -->
                    <div class="bg-white rounded-t-2xl px-6 sm:px-7 py-5 flex justify-between items-center border-b border-zinc-100 shrink-0">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500 shrink-0 shadow-3xs">
                                <i class="ph-bold ph-paper-plane-tilt text-sm"></i>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-zinc-900">Kirim Email</h3>
                                <p class="text-[9px] text-zinc-400 font-semibold mt-0.5">Pilih tipe template email yang akan dikirim</p>
                            </div>
                        </div>
                        <button wire:click="closeSendEmailModal" class="w-6 h-6 flex items-center justify-center rounded-lg hover:bg-zinc-100 text-zinc-400 hover:text-zinc-650 transition-all">
                            <i class="ph ph-x text-sm"></i>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6 overflow-y-auto custom-scrollbar space-y-4">
                        <!-- Recipient preview card -->
                        <div class="bg-zinc-50 border border-zinc-200 rounded-xl p-3 flex items-center gap-3 shadow-3xs">
                            <div class="w-8 h-8 rounded-full border border-zinc-200 bg-zinc-100 flex items-center justify-center font-bold text-zinc-700 text-xs shrink-0">
                                {{ strtoupper(substr($emailTargetUserName ?? 'U', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-zinc-900 truncate leading-none mb-1.5">{{ $emailTargetUserName ?? '-' }}</p>
                                <p class="text-[10px] text-zinc-500 font-mono truncate leading-none">{{ $emailTargetUserEmail ?? '-' }}</p>
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
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                        @foreach($types as $type => $info)
                                            @php $isSelected = $emailType === $type; @endphp
                                            <label class="relative flex h-14 cursor-pointer rounded-xl border p-2.5 items-center transition-all duration-150 {{ $isSelected ? 'border-zinc-950 bg-zinc-50/50 shadow-[0_0_0_1px_rgba(9,9,11,1)]' : 'border-zinc-200 bg-white hover:bg-zinc-50/50' }}">
                                                <input type="radio" wire:model.live="emailType" value="{{ $type }}" class="sr-only">
                                                <div class="flex items-center gap-2.5 w-full">
                                                    <div class="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-550 shrink-0 shadow-3xs">
                                                        <i class="ph {{ $info['icon'] }} text-xs"></i>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-xs font-bold text-zinc-900 leading-none mb-1.5">{{ $info['label'] }}</p>
                                                        <p class="text-[10px] text-zinc-500 font-semibold truncate leading-none">{{ $info['desc'] }}</p>
                                                    </div>
                                                    <div class="w-3.5 h-3.5 rounded-full border flex items-center justify-center shrink-0 {{ $isSelected ? 'border-zinc-900 bg-zinc-900' : 'border-zinc-300 bg-transparent' }} transition-colors duration-150">
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
                            <button type="button" wire:click="closeSendEmailModal" class="h-9 px-4 bg-zinc-100 hover:bg-zinc-200/80 text-zinc-700 rounded-xl text-xs font-bold transition-all flex items-center justify-center">
                                Batal
                            </button>
                            <button type="button" wire:click="sendEmail" class="h-9 px-4 bg-zinc-955 hover:bg-zinc-900 text-white rounded-xl text-xs font-bold shadow-3xs hover:shadow-2xs transition-all flex items-center justify-center">
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
            <div class="fixed inset-0 bg-zinc-955/35 backdrop-blur-[3px] z-50 flex items-center justify-center p-4" style="z-index: 9999;">
                <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-xl max-w-sm w-full relative overflow-hidden">
                    
                    <!-- Modal Header -->
                    <div class="bg-white rounded-t-2xl px-6 sm:px-7 py-5 flex justify-between items-center border-b border-zinc-100 shrink-0">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 shadow-3xs">
                                <i class="ph-bold ph-crown text-sm"></i>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-zinc-900 font-sans">Premium Override</h3>
                                <p class="text-[9px] text-zinc-400 font-semibold mt-0.5">Manual Status Update</p>
                            </div>
                        </div>
                        <button wire:click="closePremiumConfirmModal" class="w-6 h-6 flex items-center justify-center rounded-lg hover:bg-zinc-100 text-zinc-400 hover:text-zinc-650 transition-all">
                            <i class="ph ph-x text-sm"></i>
                        </button>
                    </div>

                    <div class="p-6">
                        <div class="bg-zinc-50 border border-zinc-200 rounded-xl p-4 mb-4 shadow-3xs">
                            <span class="text-sm font-extrabold text-zinc-900 block truncate mb-1.5">{{ $confirmTargetUserName }}</span>
                            <span class="text-xs text-zinc-500 block leading-none">
                                Status Saat Ini: 
                                <span class="font-extrabold {{ $confirmTargetUserIsPremium ? 'text-amber-600' : 'text-zinc-700' }}">
                                    {{ $confirmTargetUserIsPremium ? 'Premium' : 'Free Tier' }}
                                </span>
                            </span>
                        </div>

                        <div class="flex justify-end gap-2 pt-2">
                            <button wire:click="closePremiumConfirmModal" 
                                    class="h-9 px-4 bg-zinc-100 hover:bg-zinc-200/80 text-zinc-700 rounded-xl text-xs font-bold transition-all flex items-center justify-center">
                                Batal
                            </button>
                            <button wire:click="toggleManualPremium" 
                                    class="h-9 px-4 {{ $confirmTargetUserIsPremium ? 'bg-zinc-955 hover:bg-zinc-900' : 'bg-amber-600 hover:bg-amber-700' }} text-white rounded-xl text-xs font-bold shadow-3xs hover:shadow-2xs transition-all flex items-center justify-center">
                                {{ $confirmTargetUserIsPremium ? 'Downgrade ke Free' : 'Upgrade ke Premium' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif

    <!-- Modal: Delete User Confirm -->
    <template x-teleport="body">
        <div id="deleteUserModal" class="fixed inset-0 bg-zinc-955/35 backdrop-blur-[3px] z-50 hidden items-center justify-center p-4" style="z-index: 9999;">
            <div class="absolute inset-0" onclick="closeDeleteUserModal()"></div>
            <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-xl max-w-sm w-full relative z-10 transition-all scale-98 opacity-0 duration-150 overflow-hidden" id="deleteUserModalContent">
                
                <!-- Modal Header -->
                <div class="bg-white rounded-t-2xl px-6 sm:px-7 py-5 flex justify-between items-center border-b border-zinc-100 shrink-0">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-red-50 border border-red-100 flex items-center justify-center text-red-600 shadow-3xs">
                            <i class="ph-bold ph-trash text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-zinc-900 font-sans">Hapus User</h3>
                            <p class="text-[9px] text-zinc-400 font-semibold mt-0.5">Aksi ini bersifat permanen</p>
                        </div>
                    </div>
                    <button onclick="closeDeleteUserModal()" class="w-6 h-6 flex items-center justify-center rounded-lg hover:bg-zinc-100 text-zinc-400 hover:text-zinc-650 transition-all">
                        <i class="ph ph-x text-sm"></i>
                    </button>
                </div>

                <div class="p-6">
                    <div class="bg-red-50/30 border border-red-100 rounded-xl p-4 mb-4 text-left shadow-3xs">
                        <span class="text-sm font-extrabold text-zinc-900 block truncate mb-1.5" id="deleteUserName"></span>
                        <span class="text-xs text-zinc-550 block font-medium leading-none" id="deleteUserEmail"></span>
                    </div>

                    <div class="bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-[11px] text-zinc-600 leading-relaxed mb-5 flex gap-2 text-left shadow-3xs">
                        <i class="ph ph-info text-zinc-450 text-xs shrink-0 mt-0.5"></i>
                        <div>
                            Seluruh data lamaran kerja, riwayat aktivitas, dan quota AI pengguna ini akan dihapus secara permanen dari database.
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeDeleteUserModal()" 
                                class="h-9 px-4 bg-zinc-100 hover:bg-zinc-200/80 text-zinc-700 rounded-xl text-xs font-bold transition-all flex items-center justify-center">
                            Batal
                        </button>
                        <button type="button" onclick="confirmDeleteUserAction()" 
                                class="h-9 px-4 bg-red-650 hover:bg-red-750 text-white rounded-xl text-xs font-bold shadow-3xs hover:shadow-2xs transition-all flex items-center justify-center">
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
