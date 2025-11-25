<x-app-layout>
<style>
    @keyframes shimmer {
        0% { background-position: -1000px 0; }
        100% { background-position: 1000px 0; }
    }
    .glass-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px) saturate(180%);
        -webkit-backdrop-filter: blur(10px) saturate(180%);
        border: 1px solid rgba(229, 231, 235, 0.8);
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
    }
    .glass-card:hover {
        background: rgba(255, 255, 255, 1);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
</style>

<div class="min-h-screen bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        
        <!-- Compact Hero Card with Glass Effect -->
        <div class="relative overflow-hidden rounded-2xl mb-6 glass-card shadow-sm">
            <div class="relative p-5 sm:p-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-start sm:items-center gap-4 flex-1 min-w-0">
                        <!-- Company Icon with Glass -->
                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-gray-50 border border-gray-200 flex items-center justify-center flex-shrink-0">
                            <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2 leading-tight">{{ $job->position }}</h1>
                            <div class="flex flex-wrap items-center gap-3">
                                <p class="text-base sm:text-lg font-semibold text-gray-700">{{ $job->company_name }}</p>
                                @if($job->location)
                                <div class="flex items-center text-gray-500 text-sm">
                                    <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="truncate">{{ $job->location }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($job->platform_link)
                    <a href="{{ $job->platform_link }}" target="_blank" 
                       class="inline-flex items-center justify-center px-5 py-2.5 bg-gray-200 text-gray-700 rounded-xl font-medium hover:bg-gray-300 transition-all duration-200 shadow-sm hover:shadow-md text-sm whitespace-nowrap">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        View Job
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Compact Stats Grid with Glass Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
            <!-- Status -->
            <div class="glass-card rounded-xl p-4 transition-all duration-300">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-lg bg-green-50 border border-green-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status</p>
                </div>
                <p class="text-sm font-semibold text-gray-900 truncate">{{ $job->application_status ?? 'On Process' }}</p>
            </div>

            <!-- Stage -->
            <div class="glass-card rounded-xl p-4 transition-all duration-300">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-lg bg-orange-50 border border-orange-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Stage</p>
                </div>
                <p class="text-sm font-semibold text-gray-900 truncate">{{ $job->recruitment_stage ?? 'Applied' }}</p>
            </div>

            <!-- Level -->
            <div class="glass-card rounded-xl p-4 transition-all duration-300">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Level</p>
                </div>
                <p class="text-sm font-semibold text-gray-900 truncate">{{ $job->career_level ?? 'Full Time' }}</p>
            </div>

            <!-- Applied Date -->
            <div class="glass-card rounded-xl p-4 transition-all duration-300">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-200 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Applied</p>
                </div>
                <p class="text-sm font-semibold text-gray-900">{{ optional($job->application_date)->format('M d, Y') }}</p>
            </div>
        </div>

        <!-- Interview Card with Glass Effect -->
        @if($job->interview_date)
        <div class="glass-card rounded-2xl p-5 sm:p-6 mb-6 bg-blue-50/50 border border-blue-100">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-4 flex-1">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 border border-blue-200 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Interview Scheduled</h3>
                        <div class="space-y-2">
                            <div class="flex items-center text-gray-700">
                                <svg class="w-4 h-4 mr-2.5 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="font-medium text-sm">{{ $job->interview_date->setTimezone('Asia/Jakarta')->format('l, d F Y') }}</span>
                            </div>
                            <div class="flex items-center text-gray-700">
                                <svg class="w-4 h-4 mr-2.5 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-medium text-sm">{{ $job->interview_date->setTimezone('Asia/Jakarta')->format('H:i') }} WIB</span>
                            </div>
                            @if($job->interview_type)
                            <div class="flex items-center text-gray-700">
                                <svg class="w-4 h-4 mr-2.5 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                                </svg>
                                <span class="font-medium text-sm">{{ $job->interview_type }}</span>
                            </div>
                            @endif
                            @if($job->interview_location)
                            <div class="flex items-center text-gray-700">
                                <svg class="w-4 h-4 mr-2.5 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="font-medium text-sm">{{ $job->interview_location }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @if($job->interview_date->isFuture())
                <div class="text-right flex-shrink-0">
                    <p class="text-xs text-gray-500 mb-1">In</p>
                    <p class="text-lg font-semibold text-gray-700">{{ $job->interview_date->diffForHumans(null, true) }}</p>
                </div>
                @endif
            </div>
            @if($job->interview_notes)
            <div class="mt-4 pt-4 border-t border-gray-200">
                <p class="text-sm text-gray-600 leading-relaxed">{{ $job->interview_notes }}</p>
            </div>
            @endif
        </div>
        @endif

        <!-- Compact Info Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-5 mb-6">
            <!-- Platform Card -->
            <div class="lg:col-span-2 glass-card rounded-xl p-5 transition-all duration-300">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 uppercase tracking-wider">Platform</h3>
                </div>
                <p class="text-lg font-semibold text-gray-900 mb-4">{{ $job->platform }}</p>
                @if($job->platform_link)
                <a href="{{ $job->platform_link }}" target="_blank" 
                   class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all duration-200 text-sm font-medium shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    Open Job Posting
                </a>
                @endif
            </div>

            <!-- Notes Card -->
            <div class="glass-card rounded-xl p-5 transition-all duration-300">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 uppercase tracking-wider">Notes</h3>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 min-h-[100px] border border-gray-200">
                    @if($job->notes)
                        <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $job->notes }}</p>
                    @else
                        <p class="text-sm text-gray-400 italic">No notes added yet</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Compact Footer -->
        <div class="glass-card rounded-xl p-4">
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-8 text-xs text-gray-600">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 bg-white/60 backdrop-blur-sm rounded-lg flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="font-semibold">Created {{ $job->created_at->format('M d, Y • H:i') }}</span>
                </div>
                @if($job->updated_at != $job->created_at)
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 bg-white/60 backdrop-blur-sm rounded-lg flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <span class="font-semibold">Updated {{ $job->updated_at->format('M d, Y • H:i') }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
</x-app-layout>














