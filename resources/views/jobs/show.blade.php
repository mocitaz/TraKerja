<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg border border-white/30">
                        <img src="{{ asset('images/icon.png') }}" 
                             alt="TraKerja Logo" 
                             class="w-6 h-6"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-purple-800 bg-clip-text text-transparent">
                            Job Detail
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">Comprehensive information for this application</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('tracker') }}" class="px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors duration-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Tracker
                </a>
                <div class="flex items-center space-x-2 bg-green-50 px-3 py-1.5 rounded-full">
                    <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                    <span class="text-xs font-medium text-purple-700">Live</span>
                </div>
                <div class="text-xs text-gray-400">
                    {{ now()->format('M d, Y') }}
                </div>
            </div>
        </div>
    </x-slot>

<div class="min-h-screen bg-gray-50">
<div class="max-w-5xl mx-auto px-4 py-6 relative z-10">
    
    <!-- Job Info Header Container -->
    <div class="relative overflow-hidden bg-gradient-to-br from-purple-600 via-purple-700 to-indigo-800 shadow-2xl rounded-2xl p-5 mb-6 hover:shadow-3xl transition-all duration-500 group">
        <!-- Animated background elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br from-pink-400/30 to-purple-400/30 rounded-full blur-2xl animate-pulse"></div>
            <div class="absolute top-1/2 -left-20 w-32 h-32 bg-gradient-to-br from-blue-400/25 to-cyan-400/25 rounded-full blur-xl animate-pulse" style="animation-delay: 1s;"></div>
            <div class="absolute bottom-0 right-1/3 w-24 h-24 bg-gradient-to-br from-indigo-400/20 to-purple-400/20 rounded-full blur-lg animate-pulse" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/4 left-1/3 w-20 h-20 bg-gradient-to-br from-rose-400/15 to-pink-400/15 rounded-full blur-md animate-pulse" style="animation-delay: 0.5s;"></div>
        </div>
        
        <!-- Shimmer effect -->
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -skew-x-12 transform translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></div>
        
        <!-- Content -->
        <div class="relative z-10 text-left">
            <div class="flex items-center mb-1">
                <div class="w-6 h-6 bg-white/20 rounded-md flex items-center justify-center mr-2">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <p class="text-2xl font-black text-white drop-shadow-lg tracking-tight">{{ $job->position }}</p>
            </div>
            <div class="flex items-center">
                <div class="w-6 h-6 bg-white/20 rounded-md flex items-center justify-center mr-2">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <p class="text-lg text-purple-100 drop-shadow-md">{{ $job->company_name }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6">
        <div class="space-y-6">
            <!-- Main Job Details Card -->
            <div class="bg-white/80 backdrop-blur-sm border border-white/20 shadow-xl rounded-3xl p-8 hover:shadow-2xl transition-all duration-300 relative overflow-hidden">
                <!-- Subtle background pattern -->
                <div class="absolute inset-0 bg-gradient-to-br from-purple-50/30 to-blue-50/30 opacity-50"></div>
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-100/20 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                <div class="relative z-10">
                @php
                    $statusColor = [
                        'On Process' => '#9333ea', // purple-600
                        'Declined' => '#dc2626',   // red-600
                        'Accepted' => '#10b981',   // emerald-500
                    ][$job->application_status ?? 'On Process'] ?? '#2563eb';
                    $stageColor = [
                        'Applied' => '#9333ea',          // purple-600
                        'Follow Up' => '#0ea5e9',        // sky-600
                        'Assessment Test' => '#7c3aed',  // violet-600
                        'Psychotest' => '#9333ea',       // purple-600
                        'HR - Interview' => '#f59e0b',   // amber-500
                        'User - Interview' => '#f97316', // orange-500
                        'LGD' => '#1d4ed8',              // blue-700
                        'Presentation Round' => '#10b981', // emerald-500
                        'Offering' => '#059669',         // emerald-600
                        'Not Processed' => '#6b7280',    // gray-500
                    ][$job->recruitment_stage ?? 'Applied'] ?? '#6b7280';
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="group">
                        <div class="flex items-center mb-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <p class="text-xs text-gray-500 font-medium">Location</p>
                        </div>
                        <p class="text-lg font-bold text-gray-900 group-hover:text-purple-700 transition-colors duration-200">{{ $job->location }}</p>
                    </div>
                    <div class="group">
                        <div class="flex items-center mb-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                                </svg>
                            </div>
                            <p class="text-xs text-gray-500 font-medium">Platform</p>
                        </div>
                        <p class="text-lg font-bold text-gray-900 group-hover:text-purple-700 transition-colors duration-200">{{ $job->platform }}</p>
                    </div>
                    <div class="group">
                        <div class="flex items-center mb-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-xs text-gray-500 font-medium">Application Status</p>
                        </div>
                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold shadow-sm hover:shadow-md transition-all duration-200" style="background-color: {{ $statusColor }}15; color: {{ $statusColor }}; border: 1px solid {{ $statusColor }}30;">
                            {{ $job->application_status ?? '-' }}
                        </span>
                    </div>
                    <div class="group">
                        <div class="flex items-center mb-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <p class="text-xs text-gray-500 font-medium">Recruitment Stage</p>
                        </div>
                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold shadow-sm hover:shadow-md transition-all duration-200" style="background-color: {{ $stageColor }}15; color: {{ $stageColor }}; border: 1px solid {{ $stageColor }}30;">
                            {{ $job->recruitment_stage ?? '-' }}
                        </span>
                    </div>
                    <div class="group">
                        <div class="flex items-center mb-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <p class="text-xs text-gray-500 font-medium">Career Level</p>
                        </div>
                        <p class="text-lg font-bold text-gray-900 group-hover:text-purple-700 transition-colors duration-200">{{ $job->career_level ?? '-' }}</p>
                    </div>
                    <div class="group">
                        <div class="flex items-center mb-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <p class="text-xs text-gray-500 font-medium">Applied Date</p>
                        </div>
                        <p class="text-lg font-bold text-gray-900 group-hover:text-purple-700 transition-colors duration-200">{{ optional($job->application_date)->format('Y-m-d') }}</p>
                    </div>
                </div>
                </div>
            </div>

            <!-- Notes and Platform Link Card -->
            <div class="bg-white/80 backdrop-blur-sm border border-white/20 shadow-xl rounded-3xl p-8 hover:shadow-2xl transition-all duration-300 relative overflow-hidden">
                <!-- Subtle background pattern -->
                <div class="absolute inset-0 bg-gradient-to-br from-blue-50/30 to-purple-50/30 opacity-50"></div>
                <div class="absolute top-0 left-0 w-24 h-24 bg-gradient-to-br from-blue-100/20 to-transparent rounded-full -translate-y-12 -translate-x-12"></div>
                <div class="relative z-10">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500 font-medium">Notes</p>
                    </div>
                    <div class="bg-gray-50/50 rounded-xl p-4 mb-6">
                        <p class="text-sm text-gray-800 whitespace-pre-line leading-relaxed">{{ $job->notes ?: 'No notes added yet' }}</p>
                    </div>
                    
                    <!-- Platform Link Section -->
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 3h7m0 0v7m0-7L10 14"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500 font-medium">Platform Link</p>
                    </div>
                    @if($job->platform_link)
                        <a href="{{ $job->platform_link }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-xl hover:from-purple-700 hover:to-purple-800 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            Open Job Posting
                        </a>
                    @else
                        <div class="bg-gray-100/50 rounded-xl p-4">
                            <span class="text-sm text-gray-500 italic">No platform link available</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Timestamp Info - Enhanced -->
        <div class="bg-white/60 backdrop-blur-sm border border-white/20 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-center space-x-8 text-sm text-gray-600">
                <div class="flex items-center">
                    <div class="w-6 h-6 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center mr-2">
                        <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="font-medium">Created {{ $job->created_at->format('d F Y, H:i') }}</span>
                </div>
                @if($job->updated_at != $job->created_at)
                    <div class="flex items-center">
                        <div class="w-6 h-6 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center mr-2">
                            <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <span class="font-medium">Updated {{ $job->updated_at->format('d F Y, H:i') }}</span>
                    </div>
                @endif
            </div>
        </div>
        
    </div>
</div>
</div>
</x-app-layout>


