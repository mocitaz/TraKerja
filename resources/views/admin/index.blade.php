<x-admin-layout>

    <div class="py-4 sm:py-6 lg:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6 lg:space-y-8">

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                {{-- Total Users Card --}}
                <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-gray-600 mb-1 truncate">Total Users</p>
                            <p class="text-xl sm:text-2xl font-bold text-[#212529]">{{ \App\Models\User::where('role', '!=', 'admin')->count() }}</p>
                        </div>
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-primary-600 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Verified Users Card --}}
                <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-gray-600 mb-1 truncate">Verified Users</p>
                            <p class="text-xl sm:text-2xl font-bold text-[#212529]">{{ \App\Models\User::where('role', '!=', 'admin')->whereNotNull('email_verified_at')->count() }}</p>
                        </div>
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Active Users Card --}}
                <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-gray-600 mb-1 truncate">Active Users</p>
                            @php
                                $activeUsers = \App\Models\User::where('role', '!=', 'admin')
                                    ->where(function($query) {
                                        $query->whereHas('experiences')
                                            ->orWhereHas('educations')
                                            ->orWhereHas('skills');
                                    })
                                    ->count();
                            @endphp
                            <p class="text-xl sm:text-2xl font-bold text-[#212529]">{{ $activeUsers }}</p>
                        </div>
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Job Applications Card --}}
                <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-gray-600 mb-1 truncate">Applications</p>
                            <p class="text-xl sm:text-2xl font-bold text-[#212529]">{{ \App\Models\JobApplication::count() }}</p>
                        </div>
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Activity --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Recent Job Applications --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">Recent Job Applications</h3>
                                <p class="text-xs sm:text-sm text-gray-500">Latest 5 applications</p>
                            </div>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @php
                            $recentApplications = \App\Models\JobApplication::with('user')->latest()->take(5)->get();
                        @endphp
                        @forelse($recentApplications as $application)
                            <div class="px-4 sm:px-6 py-3 sm:py-4 hover:bg-gray-50 transition-colors">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-4">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $application->position }}</p>
                                        <p class="text-xs text-gray-500 mt-1 truncate">{{ $application->company }}</p>
                                        <p class="text-xs text-gray-400 mt-1">Anonim • {{ $application->created_at->diffForHumans() }}</p>
                                    </div>
                                    <div class="flex-shrink-0 sm:ml-4">
                                        @if($application->status === 'applied')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Applied</span>
                                        @elseif($application->status === 'interview')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Interview</span>
                                        @elseif($application->status === 'accepted')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Accepted</span>
                                        @elseif($application->status === 'rejected')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($application->status) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-4 sm:px-6 py-8 sm:py-12 text-center">
                                <div class="flex justify-center mb-3">
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-sm sm:text-base text-gray-600 font-medium">No applications yet</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- System Overview --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">System Overview</h3>
                                <p class="text-xs sm:text-sm text-gray-500">Key metrics at a glance</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6 space-y-3 sm:space-y-4">
                        @php
                            $totalGoals = \App\Models\UserGoal::count();
                            $achievedGoals = \App\Models\UserGoal::where('is_achieved', true)->count();
                            $totalExports = \App\Models\User::sum('cv_exports_this_month') ?? 0;
                            $newUsersToday = \App\Models\User::where('role', '!=', 'admin')->whereDate('created_at', \Carbon\Carbon::today('Asia/Jakarta'))->count();
                            $newUsersWeek = \App\Models\User::where('role', '!=', 'admin')->where('created_at', '>=', \Carbon\Carbon::now('Asia/Jakarta')->subWeek())->count();
                        @endphp
                        <div class="grid grid-cols-2 gap-2 sm:gap-4">
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-3 sm:p-4">
                                <p class="text-[10px] sm:text-xs font-medium text-blue-600 mb-1">New Users Today</p>
                                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-blue-900">{{ $newUsersToday }}</p>
                            </div>
                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-3 sm:p-4">
                                <p class="text-[10px] sm:text-xs font-medium text-purple-600 mb-1">New Users This Week</p>
                                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-purple-900">{{ $newUsersWeek }}</p>
                            </div>
                            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-lg p-3 sm:p-4">
                                <p class="text-[10px] sm:text-xs font-medium text-emerald-600 mb-1">Total CV Exports</p>
                                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-emerald-900">{{ number_format($totalExports) }}</p>
                            </div>
                            <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg p-3 sm:p-4">
                                <p class="text-[10px] sm:text-xs font-medium text-amber-600 mb-1">Goals Achievement</p>
                                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-amber-900">{{ $totalGoals > 0 ? round(($achievedGoals / $totalGoals) * 100, 1) : 0 }}%</p>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Total Goals Created</span>
                                <span class="font-semibold text-gray-900">{{ number_format($totalGoals) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm mt-2">
                                <span class="text-gray-600">Goals Achieved</span>
                                <span class="font-semibold text-green-600">{{ number_format($achievedGoals) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top Users Analytics --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                {{-- Top 5 Most Applied Users --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">Top 5 Most Applied</h3>
                                <p class="text-xs sm:text-sm text-gray-500">Users dengan aplikasi terbanyak</p>
                            </div>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @php
                            $topAppliedUsers = \App\Models\User::where('role', '!=', 'admin')
                                ->withCount('jobApplications')
                                ->orderBy('job_applications_count', 'desc')
                                ->take(5)
                                ->get();
                            
                            function maskName($name) {
                                if (empty($name)) return 'Axxxxx';
                                $firstChar = mb_substr($name, 0, 1);
                                return $firstChar . str_repeat('x', min(5, max(3, mb_strlen($name) - 1)));
                            }
                        @endphp
                        @forelse($topAppliedUsers as $index => $user)
                            <div class="px-4 sm:px-6 py-3 sm:py-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between gap-3 sm:gap-4">
                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                        <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white font-bold text-sm sm:text-base">
                                            {{ $index + 1 }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm sm:text-base font-semibold text-gray-900 truncate">{{ maskName($user->name) }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ $user->email ? maskName(explode('@', $user->email)[0]) . '@xxxxx' : 'Email tidak tersedia' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 text-right">
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-100 rounded-lg">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <span class="text-sm sm:text-base font-bold text-blue-700">{{ number_format($user->job_applications_count) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-4 sm:px-6 py-8 sm:py-12 text-center">
                                <div class="flex justify-center mb-3">
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-sm sm:text-base text-gray-600 font-medium">Belum ada data</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Top 5 Most Declined/Not Processed Users --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gradient-to-r from-red-50 to-orange-50">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 bg-gradient-to-br from-red-500 to-orange-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">Top 5 Declined/Not Processed</h3>
                                <p class="text-xs sm:text-sm text-gray-500">Users dengan penolakan terbanyak</p>
                            </div>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @php
                            $topDeclinedUsers = \App\Models\User::where('role', '!=', 'admin')
                                ->select('users.*')
                                ->selectSub(function($query) {
                                    $query->selectRaw('COUNT(*)')
                                        ->from('job_applications')
                                        ->whereColumn('job_applications.user_id', 'users.id')
                                        ->where(function($q) {
                                            $q->where('application_status', 'Declined')
                                              ->orWhere('recruitment_stage', 'Not Processed');
                                        });
                                }, 'declined_not_processed_count')
                                ->havingRaw('declined_not_processed_count > 0')
                                ->orderBy('declined_not_processed_count', 'desc')
                                ->take(5)
                                ->get();
                        @endphp
                        @forelse($topDeclinedUsers as $index => $user)
                            <div class="px-4 sm:px-6 py-3 sm:py-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between gap-3 sm:gap-4">
                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                        <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gradient-to-br from-red-400 to-orange-500 flex items-center justify-center text-white font-bold text-sm sm:text-base">
                                            {{ $index + 1 }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm sm:text-base font-semibold text-gray-900 truncate">{{ maskName($user->name) }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ $user->email ? maskName(explode('@', $user->email)[0]) . '@xxxxx' : 'Email tidak tersedia' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 text-right">
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-100 rounded-lg">
                                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            <span class="text-sm sm:text-base font-bold text-red-700">{{ number_format($user->declined_not_processed_count) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-4 sm:px-6 py-8 sm:py-12 text-center">
                                <div class="flex justify-center mb-3">
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-sm sm:text-base text-gray-600 font-medium">Belum ada data</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>
