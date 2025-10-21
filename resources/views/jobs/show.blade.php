<x-app-layout>
<div class="max-w-5xl mx-auto px-4 py-6">
    <div class="mb-6">
        <div>
            <a href="{{ route('tracker') }}" class="text-sm text-gray-500 hover:text-gray-700">← Back to Tracker</a>
            <h1 class="text-2xl font-bold text-gray-900 mt-2">Job Detail</h1>
            <p class="text-sm text-gray-500">Comprehensive information for this application</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6">
        <div class="space-y-6">
            <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6">
                @php
                    $statusColor = [
                        'On Process' => '#2563eb', // blue-600
                        'Declined' => '#dc2626',   // red-600
                        'Accepted' => '#16a34a',   // green-600
                    ][$job->application_status ?? 'On Process'] ?? '#2563eb';
                    $stageColor = [
                        'Applied' => '#2563eb',          // blue-600
                        'Follow Up' => '#0ea5e9',        // sky-600
                        'Assessment Test' => '#7c3aed',  // violet-600
                        'Psychotest' => '#9333ea',       // purple-600
                        'HR - Interview' => '#f59e0b',   // amber-500
                        'User - Interview' => '#f97316', // orange-500
                        'LGD' => '#1d4ed8',              // blue-700
                        'Presentation Round' => '#22c55e', // green-500
                        'Offering' => '#16a34a',         // green-600
                        'Not Processed' => '#6b7280',    // gray-500
                    ][$job->recruitment_stage ?? 'Applied'] ?? '#6b7280';
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="flex items-center mb-1">
                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <p class="text-xs text-gray-500">Company</p>
                        </div>
                        <p class="text-base font-semibold text-gray-900">{{ $job->company_name }}</p>
                    </div>
                    <div>
                        <div class="flex items-center mb-1">
                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                            </svg>
                            <p class="text-xs text-gray-500">Position</p>
                        </div>
                        <p class="text-base font-semibold text-gray-900">{{ $job->position }}</p>
                    </div>
                    <div>
                        <div class="flex items-center mb-1">
                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <p class="text-xs text-gray-500">Location</p>
                        </div>
                        <p class="text-base font-semibold text-gray-900">{{ $job->location }}</p>
                    </div>
                    <div>
                        <div class="flex items-center mb-1">
                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                            </svg>
                            <p class="text-xs text-gray-500">Platform</p>
                        </div>
                        <p class="text-base font-semibold text-gray-900">{{ $job->platform }}</p>
                    </div>
                    <div>
                        <div class="flex items-center mb-1">
                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-xs text-gray-500">Application Status</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold" style="background-color: {{ $statusColor }}20; color: {{ $statusColor }};">
                            {{ $job->application_status ?? '-' }}
                        </span>
                    </div>
                    <div>
                        <div class="flex items-center mb-1">
                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <p class="text-xs text-gray-500">Recruitment Stage</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium" style="background-color: {{ $stageColor }}20; color: {{ $stageColor }};">
                            {{ $job->recruitment_stage ?? '-' }}
                        </span>
                    </div>
                    <div>
                        <div class="flex items-center mb-1">
                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                            </svg>
                            <p class="text-xs text-gray-500">Career Level</p>
                        </div>
                        <p class="text-base font-semibold text-gray-900">{{ $job->career_level ?? '-' }}</p>
                    </div>
                    <div>
                        <div class="flex items-center mb-1">
                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-xs text-gray-500">Applied Date</p>
                        </div>
                        <p class="text-base font-semibold text-gray-900">{{ optional($job->application_date)->format('Y-m-d') }}</p>
                    </div>
                </div>

            
            </div>

            <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6">
                <div class="flex items-center mb-2">
                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <p class="text-xs text-gray-500">Notes</p>
                </div>
                <p class="text-sm text-gray-800 whitespace-pre-line">{{ $job->notes ?: 'No notes' }}</p>
                
                <!-- Platform Link Section -->
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center mb-2">
                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 3h7m0 0v7m0-7L10 14"></path>
                        </svg>
                        <p class="text-xs text-gray-500">Platform Link</p>
                    </div>
                    @if($job->platform_link)
                        <a href="{{ $job->platform_link }}" target="_blank" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            Open Job Posting
                        </a>
                    @else
                        <span class="text-sm text-gray-400">-</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Timestamp Info - Small and Clean -->
        <div class="bg-gray-50 border border-gray-100 rounded-lg p-4">
            <div class="flex items-center space-x-6 text-xs text-gray-500">
                <div class="flex items-center">
                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Created {{ $job->created_at->format('d F Y, H:i') }}</span>
                </div>
                @if($job->updated_at != $job->created_at)
                    <div class="flex items-center">
                        <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span>Updated {{ $job->updated_at->format('d F Y, H:i') }}</span>
                    </div>
                @endif
            </div>
        </div>
        
    </div>
 </div>
</x-app-layout>


