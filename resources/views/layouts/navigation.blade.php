<nav class="bg-white/95 backdrop-blur-md border-b border-gray-200/50 shadow-sm sticky top-0 z-40">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Left Section: Brand -->
            <div class="flex items-center">
                <!-- Brand Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('tracker') }}" class="group">
                        <span class="text-xl font-bold bg-gradient-to-r from-[#d983e4] to-[#4e71c5] bg-clip-text text-transparent">
                            TraKerja
                        </span>
                    </a>
                </div>
            </div>

            <!-- Center Section: Navigation Links -->
            <div class="flex space-x-1">
                @if(!auth()->user()->isAdmin() && auth()->user()->role !== 'admin')
                    {{-- Regular User Navigation --}}
                    <a href="{{ route('tracker') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('tracker') ? 'bg-primary-100 text-primary-600 shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">
                        <span>Tracker</span>
                    </a>
                    <a href="{{ route('summary') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('summary') ? 'bg-primary-100 text-primary-600 shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">
                        <span>Summary</span>
                    </a>
                    <a href="{{ route('goals') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('goals') ? 'bg-primary-100 text-primary-600 shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">
                        <span>Goals</span>
                    </a>
                    <a href="{{ route('interviews') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('interviews') ? 'bg-primary-100 text-primary-600 shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Interviews</span>
                    </a>
                    <a href="{{ route('cv.builder') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('cv.*') ? 'bg-primary-100 text-primary-600 shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">
                        <span>CV Builder</span>
                        @if(is_premium(auth()->user()))
                            <span class="ml-1 px-1.5 py-0.5 text-xs bg-primary-100 text-primary-700 rounded font-semibold">PRO</span>
                        @endif
                    </a>
                @else
                    {{-- Admin Navigation --}}
                    <a href="{{ route('admin.index') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.index') ? 'bg-primary-100 text-primary-600 shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.users') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.users') ? 'bg-primary-100 text-primary-600 shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span>Users</span>
                    </a>
                    <a href="{{ route('admin.payments') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.payments') ? 'bg-primary-100 text-primary-600 shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        <span>Payments</span>
                    </a>
                    <a href="{{ route('admin.analytics') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.analytics') ? 'bg-primary-100 text-primary-600 shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span>Analytics</span>
                    </a>
                @endif
            </div>

            <!-- Right Section: User Menu -->
            <div class="flex items-center space-x-4">
                <!-- User Profile -->
                <div class="flex items-center space-x-3">
                    @if(Auth::user()->logo)
                        <div class="relative">
                            <img src="{{ Storage::url(Auth::user()->logo) }}" 
                                 alt="Profile Photo" 
                                 class="h-10 w-10 rounded-full object-cover ring-2 ring-white shadow-lg hover:shadow-xl transition-all duration-200">
                        </div>
                    @else
                        <div class="relative">
                            <div class="h-10 w-10 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-200 ring-2 ring-white">
                                <span class="text-white font-bold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        </div>
                    @endif
                    <div class="hidden sm:block">
                        <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <!-- User Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 hover:bg-gray-50 p-2">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="py-1">
                            <x-dropdown-link :href="route('profile.edit')" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            {{-- DISABLED: Export CSV feature temporarily disabled --}}
                            @if(false && !auth()->user()->isAdmin() && auth()->user()->role !== 'admin')
                                <!-- Export CSV (Only for regular users) -->
                                <a href="{{ route('export.job-applications.csv') }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors duration-200"
                                   onclick="showExportNotification()">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Export ke CSV
                                </a>
                            @endif

                            <!-- Authentication -->
                            <button onclick="openLogoutModal()" 
                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                {{ __('Log Out') }}
                            </button>
                        </div>
                    </x-slot>
                </x-dropdown>
                
                <!-- Notification Bell -->
                <livewire:notification-bell />
            </div>
        </div>
    </div>
</nav>

<!-- Logout Confirmation Modal -->
<div id="logoutModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-[100] flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95" id="logoutModalContent">
        <div class="p-6">
            <!-- Header -->
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Logout</h3>
                    <p class="text-sm text-gray-500">Apakah Anda yakin ingin keluar?</p>
                </div>
            </div>
            
            <!-- Content -->
            <div class="mb-6">
                <p class="text-gray-600 text-sm">Apakah Anda yakin ingin keluar?</p>
            </div>
            
            <!-- Actions -->
            <div class="flex space-x-3">
                <button onclick="closeLogoutModal()" 
                        class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                    Batal
                </button>
                <a href="{{ route('logout.force') }}" onclick="prepareLogout()"
                   class="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 text-center">
                    Ya, Logout
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function openLogoutModal() {
    const modal = document.getElementById('logoutModal');
    const modalContent = document.getElementById('logoutModalContent');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Animate modal
    setTimeout(() => {
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');
    }, 10);
}

function closeLogoutModal() {
    const modal = document.getElementById('logoutModal');
    const modalContent = document.getElementById('logoutModalContent');
    
    modalContent.classList.remove('scale-100');
    modalContent.classList.add('scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 200);
}

// Close modal when clicking outside
document.getElementById('logoutModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLogoutModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeLogoutModal();
    }
});

// Export notification function
function showExportNotification() {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-white border border-gray-200 rounded-xl shadow-lg z-50 transform translate-x-full transition-all duration-300 backdrop-blur-sm';
    notification.innerHTML = `
        <div class="flex items-center px-4 py-3 space-x-3">
            <div class="w-8 h-8 bg-gradient-to-r from-primary-500 to-primary-700 rounded-full flex items-center justify-center">
                <svg class="w-4 h-4 text-white animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-900">Exporting CSV</p>
                <p class="text-xs text-gray-500">Download will start shortly...</p>
            </div>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
        notification.classList.add('translate-x-0');
    }, 100);
    
    // Remove after 2.5 seconds
    setTimeout(() => {
        notification.classList.remove('translate-x-0');
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 2500);
}

// Simple logout handling
document.addEventListener('DOMContentLoaded', function() {
    // Close modal when clicking logout link
    const logoutLink = document.querySelector('a[href*="logout-force"]');
    if (logoutLink) {
        logoutLink.addEventListener('click', function() {
            closeLogoutModal();
        });
    }
});

// Suppress browser confirm triggered by Livewire's 419 during logout only
function prepareLogout() {
    try { closeLogoutModal(); } catch (_) {}
    // Temporarily override confirm so any in-flight Livewire 419 won't show dialog
    const originalConfirm = window.confirm;
    window.confirm = function() { return false; };
    // Restore confirm after a few seconds (page likely navigated already)
    setTimeout(function() { window.confirm = originalConfirm; }, 8000);
}
</script>
