<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4 mb-6 sm:mb-8 items-stretch">
    <!-- On Process Card -->
    <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-3 sm:p-4 h-full flex flex-col justify-between">
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
                <p class="text-xs font-medium text-gray-600 mb-1">On Process</p>
                <p class="text-2xl sm:text-3xl font-bold text-[#212529] leading-tight">{{ $onProcessCount }}</p>
            </div>
            <div class="w-7 h-7 sm:w-8 sm:h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Offering/Accepted Card -->
    <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-3 sm:p-4 h-full flex flex-col justify-between">
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
                <p class="text-xs font-medium text-gray-600 mb-1">Offering/Accepted</p>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900 leading-tight">{{ $offeringAcceptedCount }}</p>
            </div>
            <div class="w-7 h-7 sm:w-8 sm:h-8 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Declined Card -->
    <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-3 sm:p-4 h-full flex flex-col justify-between">
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
                <p class="text-xs font-medium text-gray-600 mb-1">Declined</p>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900 leading-tight">{{ $declinedCount }}</p>
            </div>
            <div class="w-7 h-7 sm:w-8 sm:h-8 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
        </div>
    </div>
</div>
