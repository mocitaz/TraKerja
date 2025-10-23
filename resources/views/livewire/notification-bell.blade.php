<div class="relative notification-bell-container">
    <!-- Notification Bell Button -->
    <button 
        wire:click="toggleNotifications"
        class="relative w-10 h-10 bg-white/90 backdrop-blur-sm rounded-full shadow-sm border border-gray-200 flex items-center justify-center hover:bg-white transition-all duration-200 group"
    >
        <svg class="w-5 h-5 text-gray-600 group-hover:text-[#0056B3] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        
        <!-- Notification Count Badge -->
        @if(count($notifications) > 0)
            <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-medium">
                {{ count($notifications) }}
            </span>
        @endif
    </button>

    <!-- Notifications Panel -->
    @if($showNotifications)
    <div 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        class="absolute right-0 top-12 w-80 max-h-96 overflow-y-auto bg-white/95 backdrop-blur-sm rounded-xl shadow-xl border border-gray-200 z-50"
    >
        <!-- Header -->
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Notifications</h3>
                @if(count($notifications) > 0)
                    <button 
                        wire:click="clearAllNotifications"
                        class="text-xs text-gray-500 hover:text-red-500 transition-colors"
                    >
                        Clear All
                    </button>
                @endif
            </div>
        </div>

        <!-- Notifications List -->
        <div class="max-h-80 overflow-y-auto">
            @if(count($notifications) > 0)
                @foreach($notifications as $notification)
                    <div 
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-x-full"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform translate-x-0"
                        x-transition:leave-end="opacity-0 transform translate-x-full"
                        class="p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors"
                    >
                        <div class="flex items-start space-x-3">
                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                @if($notification['type'] === 'success')
                                    <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                @elseif($notification['type'] === 'error')
                                    <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center">
                                        <svg class="w-3 h-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </div>
                                @elseif($notification['type'] === 'warning')
                                    <div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center">
                                        <svg class="w-3 h-3 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-6 h-6 bg-primary-100 rounded-full flex items-center justify-center">
                                        <svg class="w-3 h-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-gray-900">{{ $notification['title'] }}</h4>
                                <p class="text-sm text-gray-600 mt-1">{{ $notification['message'] }}</p>
                            </div>

                            <!-- Close Button -->
                            <button 
                                @click="show = false; setTimeout(() => $wire.removeNotification('{{ $notification['id'] }}'), 300)"
                                class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="p-8 text-center">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-medium text-gray-900 mb-1">No notifications</h3>
                    <p class="text-xs text-gray-500">You're all caught up!</p>
                </div>
            @endif
        </div>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Close notification panel when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.notification-bell-container')) {
            // Check if panel is open and close it
            const panel = document.querySelector('.notification-bell-container .absolute');
            if (panel && panel.style.display !== 'none') {
                // Trigger Livewire method to close panel
                Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id')).call('closePanel');
            }
        }
    });
});
</script>
