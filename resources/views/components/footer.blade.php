<footer class="bg-white border-t border-gray-200 py-6 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <!-- Brand -->
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/icon.png') }}" 
                     alt="TraKerja Logo" 
                     class="h-8 w-8"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="h-8 w-8 bg-gradient-to-br from-purple-600 to-purple-800 rounded-lg flex items-center justify-center" style="display: none;">
                    <span class="text-white font-bold text-sm">T</span>
                </div>
                <span class="text-base font-semibold bg-gradient-to-r from-purple-600 to-purple-800 bg-clip-text text-transparent">TraKerja</span>
            </div>

            <!-- Center Message -->
            <div class="text-sm text-gray-600 text-center">
                Made for job seekers in Indonesia
            </div>

            <!-- Copyright -->
            <div class="text-sm text-gray-600 text-center md:text-right">
                © {{ date('Y') }} TraKerja. All rights reserved.
            </div>
        </div>
    </div>
</footer>




