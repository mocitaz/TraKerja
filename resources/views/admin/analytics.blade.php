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
                        Analytics & Reports
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">Monitor aplikasi dan statistik pengguna</p>
                </div>
            </div>
        </div>
    </x-slot>

    @livewire('admin.analytics')
</x-admin-layout>
