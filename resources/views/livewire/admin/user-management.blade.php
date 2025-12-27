<div class="space-y-4 sm:space-y-6 lg:space-y-8">
    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4 lg:gap-6">
        {{-- Total Users --}}
        <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-3 sm:p-4 lg:p-6">
            <div class="flex items-center justify-between">
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] sm:text-xs font-medium text-gray-600 mb-1 truncate">Total Users</p>
                    <p class="text-lg sm:text-xl lg:text-2xl font-bold text-[#212529]">{{ $stats['total'] }}</p>
                </div>
                <div class="w-6 h-6 sm:w-7 sm:h-7 lg:w-8 lg:h-8 bg-primary-600 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 lg:w-4 lg:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Premium Users --}}
        <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-3 sm:p-4 lg:p-6">
            <div class="flex items-center justify-between">
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] sm:text-xs font-medium text-gray-600 mb-1 truncate">Premium Users</p>
                    <p class="text-lg sm:text-xl lg:text-2xl font-bold text-[#212529]">{{ $stats['premium'] }}</p>
                </div>
                <div class="w-6 h-6 sm:w-7 sm:h-7 lg:w-8 lg:h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 lg:w-4 lg:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Free Users --}}
        <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-3 sm:p-4 lg:p-6">
            <div class="flex items-center justify-between">
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] sm:text-xs font-medium text-gray-600 mb-1 truncate">Free Users</p>
                    <p class="text-lg sm:text-xl lg:text-2xl font-bold text-[#212529]">{{ $stats['free'] }}</p>
                </div>
                <div class="w-6 h-6 sm:w-7 sm:h-7 lg:w-8 lg:h-8 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 lg:w-4 lg:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Verified Users --}}
        <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-3 sm:p-4 lg:p-6">
            <div class="flex items-center justify-between">
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] sm:text-xs font-medium text-gray-600 mb-1 truncate">Verified Users</p>
                    <p class="text-lg sm:text-xl lg:text-2xl font-bold text-[#212529]">{{ $stats['verified'] }}</p>
                </div>
                <div class="w-6 h-6 sm:w-7 sm:h-7 lg:w-8 lg:h-8 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 lg:w-4 lg:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- AI Analyzer Free Trial Users --}}
        <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-3 sm:p-4 lg:p-6">
            <div class="flex items-center justify-between">
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] sm:text-xs font-medium text-gray-600 mb-1 truncate">AI Trial Used</p>
                    <p class="text-lg sm:text-xl lg:text-2xl font-bold text-[#212529]">{{ $stats['ai_analyzer_trial_used'] }}</p>
                </div>
                <div class="w-6 h-6 sm:w-7 sm:h-7 lg:w-8 lg:h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 lg:w-4 lg:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters & Search --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="w-7 h-7 sm:w-8 sm:h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">Filter & Search</h3>
                        <p class="text-xs sm:text-sm text-gray-500">Cari dan filter pengguna</p>
                    </div>
                </div>
                <button wire:click="openCreateAdminModal" class="w-full sm:w-auto px-3 sm:px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center justify-center gap-2 text-xs sm:text-sm">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Admin
                </button>
            </div>
        </div>
        <div class="p-4 sm:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
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
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-left text-[10px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-left text-[10px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Email</th>
                        <th class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-left text-[10px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Email Verified</th>
                        <th class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-left text-[10px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-left text-[10px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">AI Trial</th>
                        <th class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-left text-[10px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Role</th>
                        <th class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-left text-[10px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider hidden xl:table-cell">Joined</th>
                        <th class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-right text-[10px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
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
                                    <div class="ml-2 sm:ml-4 min-w-0 flex-1">
                                        <div class="text-xs sm:text-sm font-semibold text-gray-900 truncate">{{ $user->name }}</div>
                                        <div class="text-[10px] sm:text-xs text-gray-500">ID: #{{ $user->id }}</div>
                                        <div class="text-[10px] sm:text-xs text-gray-500 sm:hidden truncate">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 whitespace-nowrap hidden sm:table-cell">
                                <div class="text-xs sm:text-sm text-gray-900 truncate max-w-[150px]">{{ $user->email }}</div>
                            </td>
                            <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 whitespace-nowrap hidden md:table-cell">
                                @if($user->hasVerifiedEmail())
                                    <span class="px-2 py-1 inline-flex items-center gap-1.5 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Verified
                                    </span>
                                @else
                                    <span class="px-2 py-1 inline-flex items-center gap-1.5 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M4.93 19.07A10 10 0 1119.07 4.93 10 10 0 014.93 19.07z" />
                                        </svg>
                                        Unverified
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 whitespace-nowrap">
                                @if($user->is_premium)
                                    <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 inline-flex text-[10px] sm:text-xs font-medium rounded-full bg-purple-100 text-purple-800">
                                        Premium
                                    </span>
                                @else
                                    <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 inline-flex text-[10px] sm:text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                        Free
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 whitespace-nowrap hidden lg:table-cell">
                                @if($user->has_used_ai_analyzer_trial)
                                    <div class="flex flex-col gap-1">
                                        <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 inline-flex items-center gap-1 text-[10px] sm:text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Used
                                        </span>
                                        @if($user->ai_analyzer_trial_used_at)
                                            <span class="text-[10px] sm:text-xs text-gray-500">{{ $user->ai_analyzer_trial_used_at->format('d M Y') }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 inline-flex text-[10px] sm:text-xs font-medium rounded-full bg-gray-100 text-gray-600">
                                        Not Used
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 whitespace-nowrap hidden lg:table-cell">
                                @if($user->is_admin || $user->role === 'admin')
                                    <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 inline-flex text-[10px] sm:text-xs font-medium rounded-full bg-orange-100 text-orange-800">
                                        Admin
                                    </span>
                                @else
                                    <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 inline-flex text-[10px] sm:text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                        User
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 whitespace-nowrap hidden xl:table-cell">
                                <div class="text-xs sm:text-sm text-gray-900">{{ $user->created_at->format('d M Y') }}</div>
                                <div class="text-[10px] sm:text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 whitespace-nowrap text-right text-xs sm:text-sm font-medium">
                                <div class="flex items-center justify-end gap-1 sm:gap-2">
                                    <button wire:click="openSendEmailModal({{ $user->id }})" class="text-green-600 hover:text-green-900 hover:bg-green-50 p-1.5 sm:p-2 rounded-lg transition-colors" title="Send Email">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </button>
                                    <button wire:click="editUser({{ $user->id }})" class="text-blue-600 hover:text-blue-900 hover:bg-blue-50 p-1.5 sm:p-2 rounded-lg transition-colors" title="View Details">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                    <button 
                                        onclick="confirmDeleteUser({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}')"
                                        class="text-red-600 hover:text-red-900 hover:bg-red-50 p-1.5 sm:p-2 rounded-lg transition-colors" 
                                        title="Delete User">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 sm:px-6 py-8 sm:py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3 sm:mb-4">
                                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-sm sm:text-base lg:text-lg font-medium text-gray-900 mb-1">Tidak Ada User Ditemukan</p>
                                    <p class="text-xs sm:text-sm text-gray-500">Coba ubah filter atau pencarian Anda</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-2 sm:gap-4">
            <div class="text-[10px] sm:text-xs text-gray-500 text-center sm:text-left">
                Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? $users->count() }} of {{ $users->total() }} users
            </div>
            <div class="w-full sm:w-auto">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    @if($showEditModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" wire:click="closeModal"></div>
            <div class="relative min-h-screen flex items-center justify-center p-3 sm:p-4">
            <div class="relative w-full max-w-lg bg-white rounded-lg shadow-xl border border-gray-200 transform transition-all" wire:click.stop>
                {{-- Modal Header --}}
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between gap-2 sm:gap-3">
                        <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">User Details</h3>
                                <p class="text-xs sm:text-sm text-gray-500">Lihat informasi pengguna</p>
                            </div>
                        </div>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg p-1.5 sm:p-2 transition-all flex-shrink-0">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Modal Body --}}
                <div class="p-4 sm:p-6 space-y-3 sm:space-y-4 max-h-[70vh] overflow-y-auto">
                    @php
                        $editingUser = $editingUserId ? \App\Models\User::find($editingUserId) : null;
                    @endphp
                    
                    {{-- Profile Photo --}}
                    @if($editingUser && $editingUser->logo)
                        <div class="flex justify-center mb-4">
                            <div class="w-24 h-24 rounded-xl overflow-hidden ring-4 ring-gray-100 shadow-lg">
                                <img src="{{ Storage::url($editingUser->logo) }}" 
                                     alt="{{ $editName ?? 'User' }}" 
                                     class="w-full h-full object-cover"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="w-full h-full bg-gradient-to-br from-primary-500 to-secondary-500 flex items-center justify-center" style="display: none;">
                                    <span class="text-white font-semibold text-2xl">{{ strtoupper(substr($editName ?? 'U', 0, 1)) }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    {{-- User Info Grid --}}
                    <div class="space-y-4">
                        {{-- Name --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">Nama</label>
                            <div class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 font-medium">
                                {{ $editName ?? '-' }}
                            </div>
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">Email</label>
                            <div class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 font-medium break-all">
                                {{ $editEmail ?? '-' }}
                            </div>
                        </div>

                        {{-- Status Grid --}}
                        <div class="grid grid-cols-2 gap-4">
                            {{-- Premium Status --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-2">Status Premium</label>
                                <div class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg">
                                    @if($editIsPremium)
                                        <span class="inline-flex items-center gap-1.5 text-purple-600 font-medium">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                            </svg>
                                            Premium
                                        </span>
                                    @else
                                        <span class="text-gray-600">Free</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Admin Status --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-2">Status Admin</label>
                                <div class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg">
                                    @if($editIsAdmin)
                                        <span class="inline-flex items-center gap-1.5 text-purple-600 font-medium">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                            </svg>
                                            Admin
                                        </span>
                                    @else
                                        <span class="text-gray-600">User</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        {{-- AI Analyzer Section --}}
                        <div class="pt-4 border-t border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                                AI Analyzer Usage
                            </h4>
                            
                            <div class="grid grid-cols-2 gap-4">
                                {{-- Free Trial Status --}}
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Free Trial</label>
                                    <div class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg">
                                        @if($editingUser && $editingUser->has_used_ai_analyzer_trial)
                                            <span class="inline-flex items-center gap-1.5 text-blue-600 font-medium text-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Sudah Dipakai
                                            </span>
                                        @else
                                            <span class="text-gray-600 text-sm">Belum Dipakai</span>
                                        @endif
                                    </div>
                                </div>
                                
                                {{-- Usage Date --}}
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Tanggal Pakai</label>
                                    <div class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg">
                                        @if($editingUser && $editingUser->ai_analyzer_trial_used_at)
                                            <span class="text-gray-900 font-medium text-sm">
                                                {{ $editingUser->ai_analyzer_trial_used_at->format('d M Y') }}
                                            </span>
                                            <div class="text-xs text-gray-500 mt-0.5">
                                                {{ $editingUser->ai_analyzer_trial_used_at->diffForHumans() }}
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-sm">-</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Monthly Usage Stats --}}
                            @if($editingUser)
                                <div class="mt-3 p-3 bg-blue-50 border border-blue-100 rounded-lg">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        <div class="flex-1">
                                            <p class="text-xs font-semibold text-blue-900">Usage Bulan Ini</p>
                                            <p class="text-sm text-blue-800 mt-1">
                                                <span class="font-bold">{{ $editingUser->ai_analyzer_count_this_month ?? 0 }}x</span> digunakan
                                            </p>
                                            @if($editingUser->last_ai_analyzer_reset)
                                                <p class="text-xs text-blue-700 mt-1">
                                                    Reset terakhir: {{ $editingUser->last_ai_analyzer_reset->format('d M Y') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Additional Info --}}
                    <div class="pt-4 border-t border-gray-200">
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="text-sm">
                                    <p class="font-semibold text-purple-900 mb-1">Informasi Read-Only</p>
                                    <p class="text-purple-800 text-xs leading-relaxed">Data pengguna ditampilkan dalam mode baca saja. Untuk mengubah data, gunakan fitur yang sesuai.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 rounded-b-lg flex justify-end">
                    <button 
                        type="button"
                        wire:click="closeModal"
                        class="w-full sm:w-auto px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-all text-sm sm:text-base font-medium">
                        Tutup
                    </button>
                </div>
            </div>
            </div>
        </div>
    @endif

    {{-- Create Admin Modal --}}
    @if($showCreateAdminModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" wire:click="closeCreateAdminModal"></div>
            <div class="relative min-h-screen flex items-center justify-center p-3 sm:p-4">
            <div class="relative w-full max-w-lg bg-white rounded-lg shadow-xl transform transition-all z-10" wire:click.stop>
                {{-- Modal Header --}}
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between gap-2 sm:gap-3">
                        <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">Tambah Admin Baru</h3>
                                <p class="text-xs sm:text-sm text-gray-500">Buat akun administrator baru</p>
                            </div>
                        </div>
                        <button wire:click="closeCreateAdminModal" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg p-1.5 sm:p-2 transition-all flex-shrink-0">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Modal Body --}}
                <form wire:submit.prevent="createAdmin" class="p-4 sm:p-6 space-y-3 sm:space-y-4 max-h-[70vh] overflow-y-auto">
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
                <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 rounded-b-lg flex flex-col sm:flex-row justify-end gap-2 sm:gap-3">
                    <button type="button" wire:click="closeCreateAdminModal" class="w-full sm:w-auto px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all text-sm sm:text-base font-medium">
                        Batal
                    </button>
                    <button type="submit" wire:click="createAdmin" class="w-full sm:w-auto px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all text-sm sm:text-base font-medium">
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
            <div class="relative min-h-screen flex items-center justify-center p-3 sm:p-4">
                <div class="relative w-full max-w-2xl bg-white rounded-lg shadow-xl border border-gray-200 transform transition-all" wire:click.stop>
                    {{-- Modal Header --}}
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between gap-2 sm:gap-3">
                            <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1">
                                <div class="w-7 h-7 sm:w-8 sm:h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">Kirim Email</h3>
                                    <p class="text-xs sm:text-sm text-gray-500">Pilih tipe email yang akan dikirim</p>
                                </div>
                            </div>
                            <button wire:click="closeSendEmailModal" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg p-1.5 sm:p-2 transition-all flex-shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-4 sm:p-6 space-y-4 sm:space-y-6 max-h-[70vh] overflow-y-auto">
                        {{-- Target User Info --}}
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
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
                                        $borderClass = $isSelected ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-green-300';
                                        $dotBorderClass = $isSelected ? 'border-green-500' : 'border-gray-300';
                                    @endphp
                                    <label class="relative flex cursor-pointer rounded-lg border-2 {{ $borderClass }} p-4 transition-colors {{ $type === 'monthly_motivation' ? 'md:col-span-2' : '' }}">
                                        <input type="radio" wire:model.live="emailType" value="{{ $type }}" class="sr-only">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3">
                                                <div class="flex-shrink-0">
                                                    <div class="h-5 w-5 rounded-full border-2 {{ $dotBorderClass }} flex items-center justify-center">
                                                        @if($isSelected)
                                                            <div class="h-2 w-2 rounded-full bg-green-500"></div>
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
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <div class="text-sm text-yellow-800">
                                    <p class="font-medium">Perhatian!</p>
                                    <p class="mt-1">Email akan dikirim langsung ke <strong>{{ $emailTargetUserEmail ?? '-' }}</strong>. Pastikan Anda sudah memilih tipe email yang benar.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 rounded-b-lg flex flex-col sm:flex-row justify-end gap-2 sm:gap-3">
                        <button 
                            type="button"
                            wire:click="closeSendEmailModal"
                            class="w-full sm:w-auto px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all text-sm sm:text-base font-medium">
                            Batal
                        </button>
                        <button 
                            type="button"
                            wire:click="sendEmail"
                            class="w-full sm:w-auto px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all text-sm sm:text-base font-medium flex items-center justify-center gap-2">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
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
