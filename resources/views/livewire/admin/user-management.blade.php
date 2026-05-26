<div class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-6 space-y-6 sm:space-y-8 pb-10">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-6 sm:mb-8">
        <div class="flex items-center gap-3 sm:gap-4 min-w-0 w-full md:w-auto">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white border border-slate-200/60 rounded-[1.25rem] sm:rounded-[1.5rem] flex items-center justify-center text-primary-600 shadow-sm shrink-0">
                <i class="ph-duotone ph-users-three text-xl sm:text-2xl"></i>
            </div>
            <div class="flex flex-col min-w-0">
                <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight truncate">User Management</h3>
                <p class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mt-1 sm:mt-1.5 truncate">Members & Permissions</p>
            </div>
        </div>
    </div>

    {{-- Stats Cards (Premium Bento Grid) --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-6">
        {{-- Total Users --}}
        <div class="bento-card-stat mesh-gradient-primary rounded-[2rem] border border-slate-100 p-4 sm:p-5 flex flex-col justify-between group relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-primary-50 rounded-xl flex items-center justify-center text-primary-600 transition-transform">
                    <i class="ph-fill ph-users-three text-xl"></i>
                </div>
                <span class="text-[9px] font-black text-primary-600 uppercase tracking-[1.5px] bg-primary-50/50 px-2 py-0.5 rounded-full shrink-0">ALL</span>
            </div>
            <div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Total Users</p>
                <p class="text-2xl font-black text-slate-900 tracking-tighter">{{ $stats['total'] }}</p>
            </div>
        </div>

        {{-- Premium Users --}}
        <div class="bento-card-stat mesh-gradient-purple rounded-[2rem] border border-slate-100 p-4 sm:p-5 flex flex-col justify-between group relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 transition-transform">
                    <i class="ph-fill ph-crown text-xl"></i>
                </div>
                <span class="text-[9px] font-black text-purple-600 uppercase tracking-[1.5px] bg-purple-50/50 px-2 py-0.5 rounded-full shrink-0">PRO</span>
            </div>
            <div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Premium</p>
                <p class="text-2xl font-black text-slate-900 tracking-tighter">{{ $stats['premium'] }}</p>
            </div>
        </div>

        {{-- Free Users --}}
        <div class="bento-card-stat mesh-gradient-emerald rounded-[2rem] border border-slate-100 p-4 sm:p-5 flex flex-col justify-between group relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 transition-transform">
                    <i class="ph-fill ph-user-circle text-xl"></i>
                </div>
                <span class="text-[9px] font-black text-emerald-600 uppercase tracking-[1.5px] bg-emerald-50/50 px-2 py-0.5 rounded-full shrink-0">BASIC</span>
            </div>
            <div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Free</p>
                <p class="text-2xl font-black text-slate-900 tracking-tighter">{{ $stats['free'] }}</p>
            </div>
        </div>

        {{-- Verified Users --}}
        <div class="bento-card-stat mesh-gradient-blue rounded-[2rem] border border-slate-100 p-4 sm:p-5 flex flex-col justify-between group relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 transition-transform">
                    <i class="ph-fill ph-seal-check text-xl"></i>
                </div>
                <span class="text-[9px] font-black text-blue-600 uppercase tracking-[1.5px] bg-blue-50/50 px-2 py-0.5 rounded-full shrink-0">SAFE</span>
            </div>
            <div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Verified</p>
                <p class="text-2xl font-black text-slate-900 tracking-tighter">{{ $stats['verified'] }}</p>
            </div>
        </div>

        {{-- AI Trial Used --}}
        <div class="bento-card-stat mesh-gradient-amber rounded-[2rem] border border-slate-100 p-4 sm:p-5 flex flex-col justify-between group relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-500 transition-transform">
                    <i class="ph-fill ph-sparkle text-xl"></i>
                </div>
                <span class="text-[9px] font-black text-amber-500 uppercase tracking-[1.5px] bg-amber-50/50 px-2 py-0.5 rounded-full shrink-0">AI</span>
            </div>
            <div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">AI Trial</p>
                <p class="text-2xl font-black text-slate-900 tracking-tighter">{{ $stats['ai_analyzer_trial_used'] }}</p>
            </div>
        </div>
    </div>

    {{-- Filters & Search --}}
    <div class="bento-card bg-white rounded-[2rem] border border-slate-100 p-3 sm:p-4 flex flex-col lg:flex-row gap-3 items-center justify-between mb-4 mt-6">
        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto flex-1">
            <!-- Search -->
            <div class="relative w-full sm:max-w-xs">
                <input type="text" wire:model.live="search" placeholder="Cari nama atau email..." class="w-full px-4 py-2.5 bg-slate-50 hover:bg-slate-100 border-none rounded-xl text-sm font-bold text-slate-700 focus:bg-white focus:ring-2 focus:ring-primary-500/20 transition-all placeholder:text-slate-400 placeholder:font-medium">
            </div>

            <!-- Premium Filter -->
            <div class="relative w-full sm:w-40">
                <select wire:model.live="filterPremium" class="w-full pl-4 pr-8 py-2.5 bg-slate-50 hover:bg-slate-100 border-none rounded-xl text-sm font-bold text-slate-700 focus:bg-white focus:ring-2 focus:ring-primary-500/20 transition-all appearance-none cursor-pointer">
                    <option value="all">Semua Status</option>
                    <option value="premium">Premium</option>
                    <option value="free">Free</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                    <i class="ph-bold ph-caret-down text-slate-400 text-xs"></i>
                </div>
            </div>

            <!-- AI Trial Filter -->
            <div class="relative w-full sm:w-44">
                <select wire:model.live="filterAiAnalyzer" class="w-full pl-4 pr-8 py-2.5 bg-slate-50 hover:bg-slate-100 border-none rounded-xl text-sm font-bold text-slate-700 focus:bg-white focus:ring-2 focus:ring-primary-500/20 transition-all appearance-none cursor-pointer">
                    <option value="all">Semua AI Trial</option>
                    <option value="used">Sudah Pakai</option>
                    <option value="not_used">Belum Pakai</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                    <i class="ph-bold ph-caret-down text-slate-400 text-xs"></i>
                </div>
            </div>
            
            <!-- Rows per page -->
            <div class="relative w-full sm:w-28 hidden md:block">
                <select wire:model.live="perPage" class="w-full pl-4 pr-8 py-2.5 bg-slate-50 hover:bg-slate-100 border-none rounded-xl text-sm font-bold text-slate-700 focus:bg-white focus:ring-2 focus:ring-primary-500/20 transition-all appearance-none cursor-pointer">
                    <option value="10">10 Rows</option>
                    <option value="20">20 Rows</option>
                    <option value="50">50 Rows</option>
                    <option value="100">100 Rows</option>
                    <option value="all">All</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                    <i class="ph-bold ph-caret-down text-slate-400 text-xs"></i>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-3 w-full lg:w-auto shrink-0">
            <button wire:click="openCreateAdminModal" class="w-full sm:w-auto px-5 py-2.5 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-colors flex items-center justify-center gap-2 text-sm font-bold shadow-sm shadow-primary-500/20 group">
                <i class="ph-bold ph-user-plus text-lg transition-transform"></i>
                Add Admin
            </button>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="bento-card bg-white rounded-[2rem] border border-slate-100 overflow-hidden flex flex-col relative min-h-[500px]">
        <div class="overflow-x-auto custom-scrollbar flex-1">
            <table class="min-w-full divide-y divide-slate-100/80">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-4 lg:px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">User</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider hidden sm:table-cell">Email</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider hidden md:table-cell">Email Verified</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider hidden xl:table-cell">Joined</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider hidden lg:table-cell">Provider</th>
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
                                            <img src="{{ $user->avatar_url }}" 
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
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap hidden xl:table-cell">
                                <div class="text-sm font-bold text-slate-900">{{ $user->created_at->format('d M Y') }}</div>
                                <div class="text-[10px] font-medium text-slate-500">{{ $user->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap hidden lg:table-cell text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    @if($user->password)
                                        <div class="flex justify-center" title="Email / Password">
                                            <div class="w-6 h-6 flex items-center justify-center text-slate-400 bg-slate-100 rounded-full border border-slate-200">
                                                <i class="ph-fill ph-envelope-simple text-sm"></i>
                                            </div>
                                        </div>
                                    @endif

                                    @if($user->google_id)
                                        <div class="flex justify-center" title="Linked to Google">
                                            <svg class="w-6 h-6" viewBox="0 0 24 24">
                                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                            </svg>
                                        </div>
                                    @endif

                                    @if($user->linkedin_id)
                                        <div class="flex justify-center" title="Linked to LinkedIn">
                                            <svg class="w-6 h-6" fill="#0A66C2" viewBox="0 0 24 24">
                                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    @if(!$user->password && !$user->google_id && !$user->linkedin_id)
                                        <div class="flex justify-center" title="Unknown Provider">
                                            <div class="w-6 h-6 flex items-center justify-center text-slate-300 bg-slate-50 rounded-full border border-slate-100">
                                                <i class="ph-fill ph-question text-sm"></i>
                                            </div>
                                        </div>
                                    @endif
                                </div>
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
                            <td colspan="9" class="px-6 py-12 text-center">
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
        <template x-teleport="body">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xl z-50 flex items-center justify-center p-4 transition-all duration-300" style="z-index: 9999;">
                <div class="bg-white rounded-[2rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.2)] max-w-lg w-full max-h-[90vh] flex flex-col overflow-hidden border border-slate-100 transform transition-all">
                    
                    {{-- Modal Header: Clean White --}}
                    <div class="bg-white px-6 py-5 text-slate-900 flex justify-between items-center border-b border-slate-100 shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center shadow-sm text-blue-600">
                                <i class="ph-fill ph-user-circle text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-black tracking-tight">User Details</h3>
                                <p class="text-slate-400 text-[8px] font-bold uppercase tracking-widest mt-0.5">Lihat informasi pengguna</p>
                            </div>
                        </div>
                        <button wire:click="closeModal" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-50 transition-all text-slate-400 hover:text-slate-900">
                            <i class="ph-bold ph-x text-base"></i>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-6 bg-white overflow-y-auto custom-scrollbar space-y-6">
                        @php
                            $editingUser = $editingUserId ? \App\Models\User::find($editingUserId) : null;
                        @endphp
                        
                        {{-- Profile Photo --}}
                        @if($editingUser)
                            <div class="flex justify-center mb-2">
                                <div class="w-20 h-20 rounded-2xl overflow-hidden ring-4 ring-slate-50 shadow-sm border border-slate-100">
                                    <img src="{{ $editingUser->avatar_url }}" 
                                         alt="{{ $editName ?? 'User' }}" 
                                         class="w-full h-full object-cover">
                                </div>
                            </div>
                        @endif
                        
                        {{-- User Info Grid --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Name --}}
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Nama</label>
                                <div class="px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold text-slate-900">
                                    {{ $editName ?? '-' }}
                                </div>
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Email</label>
                                <div class="px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold text-slate-900 truncate">
                                    {{ $editEmail ?? '-' }}
                                </div>
                            </div>

                            {{-- Premium Status --}}
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Status Premium</label>
                                <div class="px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl">
                                    @if($editIsPremium)
                                        <span class="inline-flex items-center gap-1.5 text-amber-600 font-bold text-xs">
                                            <i class="ph-fill ph-crown"></i>
                                            Premium
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-slate-500 font-bold text-xs">
                                            <i class="ph-bold ph-user"></i>
                                            Free
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Admin Status --}}
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Status Admin</label>
                                <div class="px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl">
                                    @if($editIsAdmin)
                                        <span class="inline-flex items-center gap-1.5 text-primary-600 font-bold text-xs">
                                            <i class="ph-fill ph-shield-check"></i>
                                            Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-slate-500 font-bold text-xs">
                                            <i class="ph-bold ph-user"></i>
                                            User
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Total Lamaran --}}
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Total Lamaran</label>
                                <div class="px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl">
                                    <span class="inline-flex items-center gap-1.5 text-slate-900 font-bold text-xs">
                                        <i class="ph-fill ph-briefcase text-slate-500"></i>
                                        {{ $editingUser ? $editingUser->getJobApplicationsCount() : 0 }} Lamaran
                                    </span>
                                </div>
                            </div>

                            {{-- Tanggal Bergabung --}}
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Bergabung Sejak</label>
                                <div class="px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl">
                                    <span class="inline-flex items-center gap-1.5 text-slate-900 font-bold text-xs">
                                        <i class="ph-fill ph-calendar-blank text-slate-500"></i>
                                        {{ $editingUser && $editingUser->created_at ? $editingUser->created_at->format('d M Y') : '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        {{-- AI Features Section --}}
                        <div class="pt-5 border-t border-slate-100">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-[11px] font-black text-slate-900 uppercase tracking-widest flex items-center gap-2">
                                    <i class="ph-fill ph-robot text-blue-500"></i>
                                    AI Quotas Management
                                </h4>
                                <button type="button" wire:click="resetAllAiQuotas" class="text-[9px] font-bold text-blue-600 uppercase tracking-widest hover:text-blue-700 transition-colors flex items-center gap-1">
                                    <i class="ph-bold ph-arrows-clockwise"></i> Reset ke Standar
                                </button>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                {{-- AI Analyzer --}}
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 flex items-center gap-1">
                                        <i class="ph-fill ph-magnifying-glass-plus text-slate-300"></i> Analyzer
                                    </label>
                                    <input type="number" wire:model="editAiCredits" class="w-full px-3 py-2 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-center" min="0">
                                </div>
                                
                                {{-- Cover Letters --}}
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 flex items-center gap-1">
                                        <i class="ph-fill ph-envelope-simple-open text-slate-300"></i> Cover Letter
                                    </label>
                                    <input type="number" wire:model="editClCredits" class="w-full px-3 py-2 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-center" min="0">
                                </div>
                                
                                {{-- AI Photo --}}
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 flex items-center gap-1">
                                        <i class="ph-fill ph-camera text-slate-300"></i> AI Photo
                                    </label>
                                    <input type="number" wire:model="editPhotoCredits" class="w-full px-3 py-2 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-center" min="0">
                                </div>
                            </div>
                            
                            @if($editingUser)
                            <div class="mt-3 flex justify-between items-center bg-blue-50/50 rounded-xl p-3 border border-blue-100/50">
                                <div class="text-[10px] font-medium text-slate-500">
                                    Trial Analyzer Dipakai: 
                                    @if($editingUser->has_used_ai_analyzer_trial)
                                        <span class="font-bold text-slate-700">{{ $editingUser->ai_analyzer_trial_used_at ? $editingUser->ai_analyzer_trial_used_at->format('d M Y') : 'Ya' }}</span>
                                    @else
                                        <span class="font-bold text-slate-700">Belum</span>
                                    @endif
                                </div>
                                <button type="button" wire:click="saveAiQuotas" class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs font-bold hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-1.5">
                                    <i class="ph-bold ph-floppy-disk"></i> Simpan Quota
                                </button>
                            </div>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="pt-2 flex justify-end gap-3">
                            <button type="button" wire:click="closeModal" class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 transition-colors text-sm font-bold">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif

    {{-- Create Admin Modal --}}
    @if($showCreateAdminModal)
        <template x-teleport="body">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xl z-50 flex items-center justify-center p-4 transition-all duration-300" style="z-index: 9999;">
                <div class="bg-white rounded-[2rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.2)] max-w-lg w-full max-h-[90vh] flex flex-col overflow-hidden border border-slate-100 transform transition-all">
                    
                    {{-- Modal Header: Clean White --}}
                    <div class="bg-white px-6 py-5 text-slate-900 flex justify-between items-center border-b border-slate-100 shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center shadow-sm text-primary-600">
                                <i class="ph-bold ph-user-plus text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-black tracking-tight">Tambah Admin Baru</h3>
                                <p class="text-slate-400 text-[8px] font-bold uppercase tracking-widest mt-0.5">Administrator Access</p>
                            </div>
                        </div>
                        <button wire:click="closeCreateAdminModal" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-50 transition-all text-slate-400 hover:text-slate-900">
                            <i class="ph-bold ph-x text-base"></i>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-6 bg-white overflow-y-auto custom-scrollbar">
                        <form wire:submit.prevent="createAdmin" class="space-y-5">
                            {{-- Name Field --}}
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Nama Lengkap *</label>
                                <input type="text" wire:model="newAdminName" class="w-full px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all placeholder:text-slate-400" placeholder="Masukkan nama lengkap admin">
                                @error('newAdminName') 
                                    <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1.5 font-bold">
                                        <i class="ph-fill ph-warning-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Email Field --}}
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Alamat Email *</label>
                                <input type="email" wire:model="newAdminEmail" class="w-full px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all placeholder:text-slate-400" placeholder="admin@example.com">
                                @error('newAdminEmail') 
                                    <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1.5 font-bold">
                                        <i class="ph-fill ph-warning-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Password Fields in Grid --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Password *</label>
                                    <input type="password" wire:model="newAdminPassword" class="w-full px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all placeholder:text-slate-400" placeholder="Min. 8 karakter">
                                    @error('newAdminPassword') 
                                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1.5 font-bold">
                                            <i class="ph-fill ph-warning-circle"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Konfirmasi Password *</label>
                                    <input type="password" wire:model="newAdminPasswordConfirmation" class="w-full px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all placeholder:text-slate-400" placeholder="Ulangi password">
                                </div>
                            </div>

                            {{-- Info Box --}}
                            <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0 text-indigo-600">
                                        <i class="ph-fill ph-info text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="font-black text-indigo-900 text-[11px] uppercase tracking-widest mb-0.5">Hak Akses Administrator</p>
                                        <p class="text-indigo-800 text-xs font-medium leading-relaxed">Admin baru otomatis mendapatkan akses penuh ke admin panel, status premium, dan email terverifikasi.</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="pt-2 flex justify-end gap-3">
                                <button type="button" wire:click="closeCreateAdminModal" class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 transition-colors text-sm font-bold">
                                    Batal
                                </button>
                                <button type="submit" class="px-5 py-2.5 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-colors text-sm font-bold flex items-center gap-2">
                                    <i class="ph-bold ph-plus"></i>
                                    Buat Admin
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    @endif

    {{-- Send Email Modal --}}
    @if($showSendEmailModal)
        <template x-teleport="body">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xl z-50 flex items-center justify-center p-4 transition-all duration-300" style="z-index: 9999;">
                <div class="bg-white rounded-[2rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.2)] max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden border border-slate-100 transform transition-all">
                    
                    {{-- Modal Header: Clean White --}}
                    <div class="bg-white px-6 py-5 text-slate-900 flex justify-between items-center border-b border-slate-100 shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center shadow-sm text-emerald-600">
                                <i class="ph-bold ph-paper-plane-tilt text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-black tracking-tight">Kirim Email</h3>
                                <p class="text-slate-400 text-[8px] font-bold uppercase tracking-widest mt-0.5">Pilih tipe email yang akan dikirim</p>
                            </div>
                        </div>
                        <button wire:click="closeSendEmailModal" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-50 transition-all text-slate-400 hover:text-slate-900">
                            <i class="ph-bold ph-x text-base"></i>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-6 bg-white overflow-y-auto custom-scrollbar space-y-6">
                        {{-- Target User Info --}}
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center shadow-inner">
                                    <span class="text-white font-black text-sm">{{ strtoupper(substr($emailTargetUserName ?? 'U', 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-900">{{ $emailTargetUserName ?? '-' }}</p>
                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-0.5">{{ $emailTargetUserEmail ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Email Type Selection --}}
                        <div>
                            @php
                                $colorSchemes = [
                                    'blue'    => ['icon' => 'bg-blue-50 text-blue-600', 'hover' => 'hover:border-blue-200', 'selected' => 'border-blue-500 bg-blue-50/40 ring-1 ring-blue-500', 'dot' => 'bg-blue-500', 'dotBorder' => 'border-blue-500'],
                                    'emerald' => ['icon' => 'bg-emerald-50 text-emerald-600', 'hover' => 'hover:border-emerald-200', 'selected' => 'border-emerald-500 bg-emerald-50/40 ring-1 ring-emerald-500', 'dot' => 'bg-emerald-500', 'dotBorder' => 'border-emerald-500'],
                                    'amber'   => ['icon' => 'bg-amber-50 text-amber-600', 'hover' => 'hover:border-amber-200', 'selected' => 'border-amber-500 bg-amber-50/40 ring-1 ring-amber-500', 'dot' => 'bg-amber-500', 'dotBorder' => 'border-amber-500'],
                                    'indigo'  => ['icon' => 'bg-indigo-50 text-indigo-600', 'hover' => 'hover:border-indigo-200', 'selected' => 'border-indigo-500 bg-indigo-50/40 ring-1 ring-indigo-500', 'dot' => 'bg-indigo-500', 'dotBorder' => 'border-indigo-500'],
                                    'rose'    => ['icon' => 'bg-rose-50 text-rose-600', 'hover' => 'hover:border-rose-200', 'selected' => 'border-rose-500 bg-rose-50/40 ring-1 ring-rose-500', 'dot' => 'bg-rose-500', 'dotBorder' => 'border-rose-500'],
                                    'violet'  => ['icon' => 'bg-violet-50 text-violet-600', 'hover' => 'hover:border-violet-200', 'selected' => 'border-violet-500 bg-violet-50/40 ring-1 ring-violet-500', 'dot' => 'bg-violet-500', 'dotBorder' => 'border-violet-500'],
                                    'purple'  => ['icon' => 'bg-purple-50 text-purple-600', 'hover' => 'hover:border-purple-200', 'selected' => 'border-purple-500 bg-purple-50/40 ring-1 ring-purple-500', 'dot' => 'bg-purple-500', 'dotBorder' => 'border-purple-500'],
                                    'sky'     => ['icon' => 'bg-sky-50 text-sky-600', 'hover' => 'hover:border-sky-200', 'selected' => 'border-sky-500 bg-sky-50/40 ring-1 ring-sky-500', 'dot' => 'bg-sky-500', 'dotBorder' => 'border-sky-500'],
                                    'orange'  => ['icon' => 'bg-orange-50 text-orange-600', 'hover' => 'hover:border-orange-200', 'selected' => 'border-orange-500 bg-orange-50/40 ring-1 ring-orange-500', 'dot' => 'bg-orange-500', 'dotBorder' => 'border-orange-500'],
                                    'teal'    => ['icon' => 'bg-teal-50 text-teal-600', 'hover' => 'hover:border-teal-200', 'selected' => 'border-teal-500 bg-teal-50/40 ring-1 ring-teal-500', 'dot' => 'bg-teal-500', 'dotBorder' => 'border-teal-500'],
                                    'pink'    => ['icon' => 'bg-pink-50 text-pink-600', 'hover' => 'hover:border-pink-200', 'selected' => 'border-pink-500 bg-pink-50/40 ring-1 ring-pink-500', 'dot' => 'bg-pink-500', 'dotBorder' => 'border-pink-500'],
                                ];

                                $emailGroups = [
                                    'Onboarding & Verifikasi' => [
                                        'welcome'               => ['label' => 'Welcome',           'desc' => 'Email selamat datang',        'icon' => 'ph-hand-waving',           'color' => 'blue'],
                                        'verification'          => ['label' => 'Verification',      'desc' => 'Verifikasi alamat email',     'icon' => 'ph-envelope-simple',       'color' => 'emerald'],
                                        'verification_reminder' => ['label' => 'Verif. Reminder',   'desc' => 'Pengingat belum verifikasi',  'icon' => 'ph-clock-countdown',       'color' => 'amber'],
                                    ],
                                    'Engagement & Motivasi' => [
                                        'job_reminder'          => ['label' => 'Job Reminder',      'desc' => 'Ajakan catat lamaran',        'icon' => 'ph-briefcase',             'color' => 'indigo'],
                                        'monthly_motivation'    => ['label' => 'Motivasi Bulanan',  'desc' => 'Semangat bulan baru',         'icon' => 'ph-calendar-check',        'color' => 'rose'],
                                        're_engagement'         => ['label' => 'Re-engagement',     'desc' => 'Ajakan kembali aktif',        'icon' => 'ph-arrow-counter-clockwise', 'color' => 'violet'],
                                        'idul_adha'             => ['label' => 'Idul Adha',         'desc' => 'Ucapan Idul Adha 1447 H',     'icon' => 'ph-moon-stars',            'color' => 'emerald'],
                                    ],
                                    'Pembaruan & Fitur' => [
                                        'product_update'        => ['label' => 'Product Update',    'desc' => 'Pengumuman fitur terbaru',    'icon' => 'ph-megaphone',             'color' => 'blue'],
                                        'ai_analyzer'           => ['label' => 'AI Analyzer',       'desc' => 'Pengumuman trial gratis',     'icon' => 'ph-robot',                 'color' => 'sky'],
                                        'ai_photo'              => ['label' => 'AI Photo Studio',   'desc' => 'Pengumuman rilis AI Photo',   'icon' => 'ph-camera-plus',           'color' => 'purple'],
                                        'follow_up_feature'     => ['label' => 'AI Follow Up',      'desc' => 'Pengumuman fitur Follow Up',  'icon' => 'ph-paper-plane-tilt',      'color' => 'pink'],
                                    ],
                                    'Status & Info Lainnya' => [
                                        'premium_granted'       => ['label' => 'Premium Granted',   'desc' => 'Notifikasi akses premium',    'icon' => 'ph-crown',                 'color' => 'amber'],
                                        'hiring_season'         => ['label' => 'Hiring Season',     'desc' => 'Alert musim rekrutmen',       'icon' => 'ph-buildings',             'color' => 'teal'],
                                    ]
                                ];
                            @endphp
                            
                            <div class="space-y-6">
                                @foreach($emailGroups as $groupName => $types)
                                    <div>
                                        <h4 class="text-[9px] font-black text-slate-400 uppercase tracking-[1.5px] mb-3">{{ $groupName }}</h4>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                            @foreach($types as $type => $info)
                                                @php
                                                    $isSelected = $emailType === $type;
                                                    $c = $colorSchemes[$info['color']];
                                                    $cardClass = $isSelected
                                                        ? $c['selected']
                                                        : 'border-slate-100 hover:bg-slate-50 ' . $c['hover'];
                                                    $iconBg = $c['icon'];
                                                    $dotBorder = $isSelected ? $c['dotBorder'] : 'border-slate-200';
                                                    $dotBg = $isSelected ? $c['dot'] : '';
                                                @endphp
                                                <label class="relative flex cursor-pointer rounded-[1rem] border-2 {{ $cardClass }} p-3 transition-all duration-300 ease-out group overflow-hidden">
                                                    <input type="radio" wire:model.live="emailType" value="{{ $type }}" class="sr-only">
                                                    <div class="flex items-center gap-3 w-full relative z-10">
                                                        {{-- Icon --}}
                                                        <div class="flex-shrink-0 w-8 h-8 rounded-lg {{ $iconBg }} flex items-center justify-center transition-transform duration-300 shadow-sm">
                                                            <i class="ph-bold {{ $info['icon'] }} text-lg"></i>
                                                        </div>
                                                        {{-- Text --}}
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-[11px] font-black text-slate-900 leading-tight transition-colors">{{ $info['label'] }}</p>
                                                            <p class="text-[9px] font-bold text-slate-500 mt-0.5 leading-relaxed truncate uppercase tracking-widest">{{ $info['desc'] }}</p>
                                                        </div>
                                                        {{-- Indicator --}}
                                                        <div class="flex-shrink-0">
                                                            <div class="h-4 w-4 rounded-full border-2 {{ $dotBorder }} flex items-center justify-center transition-all duration-300 {{ $isSelected ? $dotBg : 'bg-transparent' }}">
                                                                @if($isSelected)
                                                                    <i class="ph-bold ph-check text-white text-[8px]"></i>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="pt-2 flex justify-end gap-3">
                            <button type="button" wire:click="closeSendEmailModal" class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 transition-colors text-sm font-bold">
                                Batal
                            </button>
                            <button type="button" wire:click="sendEmail" class="px-5 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors text-sm font-bold flex items-center gap-2">
                                <i class="ph-bold ph-paper-plane-tilt"></i>
                                Kirim Email
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif

    {{-- Manual Premium Confirmation Modal --}}
    @if($showPremiumConfirmModal)
        <template x-teleport="body">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xl z-50 flex items-center justify-center p-4 transition-all duration-300" style="z-index: 9999;">
                <div class="bg-white rounded-[2rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.2)] max-w-sm w-full flex flex-col overflow-hidden border border-slate-100 transform transition-all animate-in zoom-in-95 duration-200">
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-xl {{ $confirmTargetUserIsPremium ? 'bg-slate-50 text-slate-400' : 'bg-purple-50 text-purple-600' }} border border-slate-100 shadow-sm">
                                <i class="ph-bold ph-crown text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-900 tracking-tight">Premium Override</h3>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Manual Status Update</p>
                            </div>
                        </div>

                        <div class="bg-slate-50 rounded-[1rem] p-4 border border-slate-100 mb-6">
                            <div class="text-[11px] font-black text-slate-900">{{ $confirmTargetUserName }}</div>
                            <div class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-1">
                                Current: <span class="{{ $confirmTargetUserIsPremium ? 'text-purple-600' : 'text-slate-500' }}">{{ $confirmTargetUserIsPremium ? 'Premium' : 'Free Tier' }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2">
                            <button 
                                wire:click="toggleManualPremium" 
                                class="w-full py-2.5 {{ $confirmTargetUserIsPremium ? 'bg-slate-900' : 'bg-primary-600' }} text-white rounded-xl font-bold text-xs shadow-sm hover:opacity-90 transition-all flex justify-center items-center gap-2">
                                <i class="ph-bold {{ $confirmTargetUserIsPremium ? 'ph-arrow-down' : 'ph-arrow-up' }}"></i>
                                {{ $confirmTargetUserIsPremium ? 'Downgrade to Free' : 'Upgrade to Premium' }}
                            </button>
                            <button 
                                wire:click="closePremiumConfirmModal" 
                                class="w-full py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs hover:bg-slate-200 transition-colors">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif

    {{-- Delete Confirmation Modal --}}
    <template x-teleport="body">
        <div id="deleteUserModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xl z-50 hidden items-center justify-center p-4 transition-all duration-300" style="z-index: 9999;">
            <div class="absolute inset-0" onclick="closeDeleteUserModal()"></div>
            <div class="bg-white rounded-[2rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.2)] max-w-sm w-full flex flex-col overflow-hidden border border-slate-100 transform transition-all scale-95 opacity-0 relative z-10" id="deleteUserModalContent">
                
                <div class="p-6">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center text-red-500 mb-4 border border-red-100 shadow-sm">
                            <i class="ph-bold ph-trash text-2xl"></i>
                        </div>
                        
                        <h3 class="text-sm font-black text-slate-900 tracking-tight" id="modal-title">Delete User</h3>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1 mb-4">Aksi ini tidak dapat dibatalkan</p>
                        
                        {{-- User Info --}}
                        <div class="w-full bg-slate-50 rounded-[1rem] p-4 border border-slate-100 mb-5 text-left">
                            <p class="text-[11px] font-black text-slate-900" id="deleteUserName"></p>
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-0.5 truncate" id="deleteUserEmail"></p>
                        </div>
                        
                        <div class="flex flex-col gap-2 w-full">
                            <button type="button" onclick="confirmDeleteUserAction()" class="w-full py-2.5 bg-red-600 text-white rounded-xl font-bold text-xs shadow-sm shadow-red-500/20 hover:bg-red-700 transition-colors flex justify-center items-center gap-2">
                                <i class="ph-bold ph-trash"></i>
                                Yes, Delete
                            </button>
                            <button type="button" onclick="closeDeleteUserModal()" class="w-full py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs hover:bg-slate-200 transition-colors">
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
        
        // Update modal content
        document.getElementById('deleteUserName').textContent = userName;
        document.getElementById('deleteUserEmail').textContent = userEmail;
        
        // Show modal
        const modal = document.getElementById('deleteUserModal');
        const content = document.getElementById('deleteUserModalContent');
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
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
            modal.classList.remove('flex');
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
