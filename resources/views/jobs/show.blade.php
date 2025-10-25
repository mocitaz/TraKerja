<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 backdrop-blur-sm border border-white/30 flex items-center justify-center">
                        <img src="{{ asset('images/icon.png') }}"
                             alt="TraKerja Logo"
                             class="w-6 h-6"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold bg-gradient-to-r from-[#d983e4] to-[#4e71c5] bg-clip-text text-transparent">
                            Job Detail
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">Comprehensive information for this application</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
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
                <div class="text-xs text-gray-500 font-medium">
                    {{ now()->format('M d, Y') }}
                </div>
            </div>
        </div>
    </x-slot>

<div class="min-h-screen bg-gray-50">
<div class="max-w-4xl mx-auto px-4 py-8">
    
    <!-- Job Info Header Container -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ $job->position }}</h1>
                <p class="text-gray-600">{{ $job->company_name }}</p>
            </div>
        </div>
    </div>

    <!-- Application Details Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Application Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Application Status -->
            <div>
                <div class="flex items-center mb-2">
                    <div class="w-6 h-6 bg-green-100 rounded-lg flex items-center justify-center mr-2">
                        <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-500">Application Status</span>
                </div>
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-green-50 text-green-700 border border-green-200">
                    {{ $job->application_status ?? 'On Process' }}
                </span>
            </div>

            <!-- Recruitment Stage -->
            <div>
                <div class="flex items-center mb-2">
                    <div class="w-6 h-6 bg-orange-100 rounded-lg flex items-center justify-center mr-2">
                        <svg class="w-3 h-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-500">Recruitment Stage</span>
                </div>
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-orange-50 text-orange-700 border border-orange-200">
                    {{ $job->recruitment_stage ?? 'Applied' }}
                </span>
            </div>

            <!-- Career Level -->
            <div>
                <div class="flex items-center mb-2">
                    <div class="w-6 h-6 bg-blue-100 rounded-lg flex items-center justify-center mr-2">
                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-500">Career Level</span>
                </div>
                <p class="text-sm font-semibold text-gray-900">{{ $job->career_level ?? 'Full Time' }}</p>
            </div>

            <!-- Applied Date -->
            <div>
                <div class="flex items-center mb-2">
                    <div class="w-6 h-6 bg-purple-100 rounded-lg flex items-center justify-center mr-2">
                        <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-500">Applied Date</span>
                </div>
                <p class="text-sm font-semibold text-gray-900">{{ optional($job->application_date)->format('M d, Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Additional Information Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Additional Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Location -->
            <div>
                <div class="flex items-center mb-2">
                    <div class="w-6 h-6 bg-gray-100 rounded-lg flex items-center justify-center mr-2">
                        <svg class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-500">Location</span>
                </div>
                <p class="text-sm font-semibold text-gray-900">{{ $job->location }}</p>
            </div>

            <!-- Platform -->
            <div>
                <div class="flex items-center mb-2">
                    <div class="w-6 h-6 bg-indigo-100 rounded-lg flex items-center justify-center mr-2">
                        <svg class="w-3 h-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-500">Platform</span>
                </div>
                <p class="text-sm font-semibold text-gray-900">{{ $job->platform }}</p>
            </div>

            <!-- Notes -->
            <div>
                <div class="flex items-center mb-2">
                    <div class="w-6 h-6 bg-blue-100 rounded-lg flex items-center justify-center mr-2">
                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-500">Notes</span>
                </div>
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-sm text-gray-800 whitespace-pre-line leading-relaxed">{{ $job->notes ?: 'No notes added yet' }}</p>
                </div>
            </div>

            <!-- Platform Link -->
            <div>
                <div class="flex items-center mb-2">
                    <div class="w-6 h-6 bg-purple-100 rounded-lg flex items-center justify-center mr-2">
                        <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 3h7m0 0v7m0-7L10 14"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-500">Platform Link</span>
                </div>
                @if($job->platform_link)
                    <a href="{{ $job->platform_link }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors duration-200 shadow-sm hover:shadow-md text-sm">
                        <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        Open Job Posting
                    </a>
                @else
                    <div class="bg-gray-50 rounded-lg p-3">
                        <span class="text-xs text-gray-500 italic">No platform link available</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Timestamp Info -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row items-center justify-center space-y-2 md:space-y-0 md:space-x-8 text-sm text-gray-600">
            <div class="flex items-center">
                <div class="w-6 h-6 bg-gray-100 rounded-lg flex items-center justify-center mr-2">
                    <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="font-medium">Created {{ $job->created_at->format('d F Y, H:i') }}</span>
            </div>
            @if($job->updated_at != $job->created_at)
                <div class="flex items-center">
                    <div class="w-6 h-6 bg-gray-100 rounded-lg flex items-center justify-center mr-2">
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
</x-app-layout>


