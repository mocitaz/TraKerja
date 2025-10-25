<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
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
                        Payment Monitoring
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">Monitor dan kelola pembayaran pengguna</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                <div class="text-center">
                    <div class="w-12 h-12 mx-auto mb-4 bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Coming Soon</h3>
                    <p class="text-sm text-gray-600">Payment Monitoring feature is under development</p>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
