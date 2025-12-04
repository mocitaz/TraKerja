<footer class="relative bg-gradient-to-r from-white via-purple-50/30 to-blue-50/30 backdrop-blur-xl border-t border-gray-200/50 mt-auto overflow-hidden">
    <!-- Decorative Pattern Overlay -->
    <div class="absolute inset-0 opacity-5 pointer-events-none">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, rgba(139, 92, 246, 0.3) 1px, transparent 0); background-size: 24px 24px;"></div>
    </div>
    <!-- Gradient Top Border -->
    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-purple-300/50 to-transparent"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-5">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-3 sm:gap-4">
            <!-- Brand -->
            <div class="flex items-center gap-2.5 group">
                <div class="relative">
                    <img src="{{ asset('images/icon.png') }}" 
                         alt="TraKerja Logo" 
                         class="h-7 w-7 sm:h-8 sm:w-8 transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 drop-shadow-sm"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="h-7 w-7 sm:h-8 sm:w-8 bg-gradient-to-br from-[#d983e4] to-[#4e71c5] rounded-xl flex items-center justify-center transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-lg shadow-purple-500/30" style="display: none;">
                        <span class="text-white font-bold text-sm sm:text-base">T</span>
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-400/20 to-blue-400/20 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 blur-sm"></div>
                </div>
                <span class="text-base sm:text-lg font-bold bg-gradient-to-r from-[#d983e4] via-purple-600 to-[#4e71c5] bg-clip-text text-transparent group-hover:from-[#4e71c5] group-hover:via-[#d983e4] group-hover:to-purple-600 transition-all duration-300">
                    TraKerja
                </span>
            </div>

            <!-- Copyright -->
            <div class="text-xs sm:text-sm text-gray-600 text-center sm:text-right">
                <span class="font-medium">© 2025 TraKerja.</span>
                <a href="https://www.instagram.com/teknalogi.id/" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   class="text-gray-600 hover:text-purple-600 font-medium transition-colors duration-200 hover:underline">
                    PT Teknalogi Transformasi Digital
                </a>
                <span class="text-gray-500">. All rights reserved.</span>
            </div>
        </div>
    </div>
</footer>




