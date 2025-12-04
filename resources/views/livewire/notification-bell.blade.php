<div>
    <div class="relative notification-bell-container">
        <!-- Notification Bell Button -->
        <button 
            wire:click="toggleNotifications"
            class="relative w-10 h-10 bg-white/90 backdrop-blur-sm rounded-full shadow-sm border border-gray-200 flex items-center justify-center hover:bg-white hover:shadow-md transition-all duration-200 group"
        >
            <svg class="w-5 h-5 text-gray-600 group-hover:text-purple-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            
            <!-- Notification Count Badge - Inside Bell -->
            @if(count($notifications) > 0)
                <span class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                    <span class="w-4 h-4 bg-gradient-to-r from-red-500 to-red-600 text-white text-[10px] rounded-full flex items-center justify-center font-bold shadow-md animate-pulse leading-none">
                        {{ count($notifications) > 9 ? '9+' : count($notifications) }}
                    </span>
                </span>
            @endif
        </button>

    <!-- Notifications Panel -->
    @if($showNotifications)
    <div 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 transform scale-95 translate-y-2"
        class="absolute right-0 top-12 w-80 max-h-96 overflow-hidden bg-white rounded-2xl shadow-2xl border border-gray-200 z-[100]"
    >
        <!-- Header -->
        <div class="p-4 border-b border-gray-200 bg-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Notifications</h3>
                        @if(count($notifications) > 0)
                            <p class="text-xs text-gray-500">{{ count($notifications) }} unread</p>
                        @endif
                    </div>
                </div>
                @if(count($notifications) > 0)
                    <button 
                        wire:click="clearAllNotifications"
                        class="px-2 py-1 text-xs font-medium text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                    >
                        Clear All
                    </button>
                @endif
            </div>
        </div>

        <!-- Notifications List -->
        <div class="max-h-80 overflow-y-auto custom-scrollbar">
            @if(count($notifications) > 0)
                @foreach($notifications as $notification)
                    <div 
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-x-4"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform translate-x-0"
                        x-transition:leave-end="opacity-0 transform translate-x-4"
                        class="p-4 border-b border-gray-100 hover:bg-gray-50 transition-all cursor-pointer group"
                    >
                        <div class="flex items-start gap-3">
                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                @if($notification['type'] === 'success')
                                    <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg shadow-green-500/20 group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                @elseif($notification['type'] === 'error')
                                    <div class="w-10 h-10 bg-gradient-to-br from-red-400 to-rose-500 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/20 group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </div>
                                @elseif($notification['type'] === 'warning')
                                    <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/20 group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-blue-500 rounded-xl flex items-center justify-center shadow-lg shadow-purple-500/20 group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-bold text-gray-900 group-hover:text-purple-600 transition-colors">{{ $notification['title'] }}</h4>
                                <p class="text-xs text-gray-600 mt-1 leading-relaxed">{{ $notification['message'] }}</p>
                                @if(isset($notification['timestamp']))
                                    <p class="text-xs text-gray-400 mt-1.5">{{ \Carbon\Carbon::parse($notification['timestamp'])->diffForHumans() }}</p>
                                @endif
                            </div>

                            <!-- Close Button -->
                            <button 
                                @click="show = false; setTimeout(() => $wire.removeNotification('{{ $notification['id'] }}'), 300)"
                                class="flex-shrink-0 w-6 h-6 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all flex items-center justify-center opacity-0 group-hover:opacity-100"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-100 to-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900 mb-1">All caught up!</h3>
                    <p class="text-xs text-gray-500">No new notifications</p>
                </div>
            @endif
        </div>
    </div>
        @endif
    </div>

    <style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #d983e4, #4e71c5);
        border-radius: 3px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #c973d4, #3d61b5);
    }
    </style>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-20 right-4 z-[100] space-y-3 pointer-events-none"></div>
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

    // Toast Notification Handler
    window.addEventListener('showToast', function(event) {
        const notification = event.detail;
        showToastNotification(notification);
    });

    // Listen to Livewire events
    Livewire.on('showToast', function(notification) {
        showToastNotification(notification);
    });
});

// Track shown toast IDs to prevent duplicates
const shownToastIds = new Set();

function showToastNotification(notification) {
    const container = document.getElementById('toast-container');
    if (!container) return;

    // Skip if no message
    if (!notification.message && !notification.title) {
        return;
    }

    const toastId = 'toast-' + (notification.id || Date.now());
    
    // Prevent duplicate toasts
    if (shownToastIds.has(toastId)) {
        return;
    }
    shownToastIds.add(toastId);

    const duration = 3000; // Fixed 3 seconds

    // Get message from notification (wajib ada)
    const message = notification.message || notification.title;
    if (!message) {
        return; // Skip if no message
    }

    // Determine color based on type
    let bgGradient = 'from-purple-500 to-blue-600';
    if (notification.type === 'success') {
        bgGradient = 'from-green-500 to-emerald-600';
    } else if (notification.type === 'error') {
        bgGradient = 'from-red-500 to-rose-600';
    } else if (notification.type === 'warning') {
        bgGradient = 'from-amber-500 to-orange-600';
    }

    // Toast notification dengan desain yang lebih baik
    const toast = document.createElement('div');
    toast.id = toastId;
    toast.className = `pointer-events-auto max-w-sm w-full bg-gradient-to-r ${bgGradient} rounded-lg shadow-xl transform transition-all duration-500 ease-out translate-x-full opacity-0 border-l-4 border-white/30`;
    toast.innerHTML = `
        <div class="px-4 py-3 flex items-center gap-3">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                    ${notification.type === 'success' ? 
                        '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>' :
                      notification.type === 'error' ?
                        '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>' :
                      notification.type === 'warning' ?
                        '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>' :
                        '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                    }
                </div>
            </div>
            <p class="text-sm font-semibold text-white flex-1">${message}</p>
        </div>
    `;

    container.appendChild(toast);

    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
        toast.classList.add('translate-x-0', 'opacity-100');
    }, 10);

    // Auto dismiss after 3 seconds
    setTimeout(() => {
        closeToast(toastId);
        // Remove from set after closing
        setTimeout(() => {
            shownToastIds.delete(toastId);
        }, 500);
    }, duration);
}

function closeToast(toastId) {
    const toast = document.getElementById(toastId);
    if (!toast) return;

    toast.classList.remove('translate-x-0', 'opacity-100');
    toast.classList.add('translate-x-full', 'opacity-0');

    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 500);
}
</script>
