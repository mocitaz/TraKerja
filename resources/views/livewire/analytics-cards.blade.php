<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <!-- On Process Card -->
    <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-600 mb-1">On Process</p>
                <p class="text-2xl font-bold text-[#212529]">{{ $onProcessCount }}</p>
            </div>
            <div class="w-8 h-8 bg-[#0056B3] rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Offering/Accepted Card -->
    <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-600 mb-1">Offering/Accepted</p>
                <p class="text-2xl font-bold text-gray-900">{{ $offeringAcceptedCount }}</p>
            </div>
            <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Declined Card -->
    <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-600 mb-1">Declined</p>
                <p class="text-2xl font-bold text-gray-900">{{ $declinedCount }}</p>
            </div>
            <div class="w-8 h-8 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
        </div>
    </div>
</div>
