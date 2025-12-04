<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 mb-6 sm:mb-8">
    <!-- On Process Card -->
    <div class="group relative bg-white/80 backdrop-blur-sm rounded-xl shadow-md border border-gray-200/50 p-4 sm:p-5 hover:shadow-xl hover:scale-[1.02] transition-all duration-300 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-primary-500/5 to-purple-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="relative flex items-center justify-between">
            <div class="flex-1">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">On Process</p>
                <p class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-primary-600 to-purple-600 bg-clip-text text-transparent">{{ $onProcessCount }}</p>
                <p class="text-xs text-gray-500 mt-1">Active applications</p>
            </div>
            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-primary-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/30 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Offering/Accepted Card -->
    <div class="group relative bg-white/80 backdrop-blur-sm rounded-xl shadow-md border border-gray-200/50 p-4 sm:p-5 hover:shadow-xl hover:scale-[1.02] transition-all duration-300 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-green-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="relative flex items-center justify-between">
            <div class="flex-1">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Offering/Accepted</p>
                <p class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-emerald-600 to-green-600 bg-clip-text text-transparent">{{ $offeringAcceptedCount }}</p>
                <p class="text-xs text-gray-500 mt-1">Successful applications</p>
            </div>
            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Declined Card -->
    <div class="group relative bg-white/80 backdrop-blur-sm rounded-xl shadow-md border border-gray-200/50 p-4 sm:p-5 hover:shadow-xl hover:scale-[1.02] transition-all duration-300 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-red-500/5 to-rose-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="relative flex items-center justify-between">
            <div class="flex-1">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Declined</p>
                <p class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-red-600 to-rose-600 bg-clip-text text-transparent">{{ $declinedCount }}</p>
                <p class="text-xs text-gray-500 mt-1">Unsuccessful applications</p>
            </div>
            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/30 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
        </div>
    </div>
</div>
