<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between space-y-3 sm:space-y-0">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg border border-white/30">
                        <img src="{{ asset('images/icon.png') }}" 
                             alt="TraKerja Logo" 
                             class="w-5 h-5 sm:w-6 sm:h-6"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg sm:text-2xl font-bold bg-gradient-to-r from-[#d983e4] to-[#4e71c5] bg-clip-text text-transparent">
                            <span class="hidden sm:inline">TraKerja Tracker</span>
                            <span class="sm:hidden">Tracker</span>
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5 hidden sm:block">Smart tracking untuk Job Seeker</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <div class="flex items-center space-x-2 bg-green-50 px-2 sm:px-3 py-1 sm:py-1.5 rounded-full">
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    <span class="text-xs font-medium text-green-700">Live</span>
                </div>
                <div class="text-xs text-gray-400">
                    {{ now()->format('M d, Y') }}
                </div>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- New Analytics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6 sm:mb-8">
                <!-- On Process Card -->
                <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-3 sm:p-4">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-gray-600 mb-1 truncate">On Process</p>
                            <p class="text-xl sm:text-2xl font-bold text-[#212529]">{{ $onProcessCount }}</p>
                        </div>
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-primary-600 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Offering/Accepted Card -->
                <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-3 sm:p-4">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-gray-600 mb-1 truncate">Offering/Accepted</p>
                            <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $offeringAcceptedCount }}</p>
                        </div>
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Declined Card -->
                <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-3 sm:p-4">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-gray-600 mb-1 truncate">Declined</p>
                            <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $declinedCount }}</p>
                        </div>
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Interviews Card -->
                <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-3 sm:p-4">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-gray-600 mb-1 truncate">Total Interviews</p>
                            <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $totalInterviewsCount }}</p>
                        </div>
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Compact View Toggle -->
            <div class="mb-4 sm:mb-6">
                <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-3 sm:p-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between space-y-3 sm:space-y-0">
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-gray-900">Job Applications</h3>
                            <p class="text-xs text-gray-500 hidden sm:block">Manage and track your applications</p>
                        </div>
                        <div class="flex bg-gray-100 rounded-lg p-0.5 w-full sm:w-auto">
                            <button id="kanban-view" class="flex-1 sm:flex-none px-3 sm:px-4 py-2 rounded-md font-medium bg-white text-primary-600 shadow-sm text-sm">
                                <div class="flex items-center justify-center space-x-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h2a2 2 0 002-2z"></path>
                                    </svg>
                                    <span class="hidden sm:inline">Kanban</span>
                                    <span class="sm:hidden">Board</span>
                                </div>
                            </button>
                            <button id="table-view" class="flex-1 sm:flex-none px-3 sm:px-4 py-2 rounded-md font-medium text-gray-600 hover:text-gray-900 text-sm">
                                <div class="flex items-center justify-center space-x-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0V4a2 2 0 012-2h14a2 2 0 012 2v16a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                                    </svg>
                                    <span>Table</span>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="space-y-6">
                <!-- Kanban Board -->
                <div id="kanban-container">
                    @livewire('job-kanban-board')
                </div>

                <!-- Table View -->
                <div id="table-container" class="hidden">
                    @livewire('job-table-list')
                </div>
            </div>
        </div>

        <!-- Floating Action Button -->
        <div class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-40">
            <button onclick="openJobModal()" 
                    class="group relative bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 hover:from-blue-700 hover:via-blue-800 hover:to-indigo-800 text-white font-medium py-2 sm:py-2.5 px-3 sm:px-4 rounded-lg shadow-lg border border-white/20">
                <div class="flex items-center space-x-1 sm:space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="text-xs font-medium hidden sm:inline">Add Job</span>
                    <span class="text-xs font-medium sm:hidden">Add</span>
                </div>
            </button>
        </div>
    </div>

    <!-- Modern Job Application Modal -->
    <div id="jobModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-2 sm:p-4">
            <div class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl max-w-4xl w-full max-h-[95vh] sm:max-h-[90vh] overflow-hidden" id="modalContent">
                <div class="bg-gradient-to-r from-[#d983e4] to-[#4e71c5] p-4 sm:p-6 text-white relative overflow-hidden">
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute top-0 right-0 w-16 sm:w-32 h-16 sm:h-32 bg-white rounded-full -translate-y-8 sm:-translate-y-16 translate-x-8 sm:translate-x-16"></div>
                        <div class="absolute bottom-0 left-0 w-12 sm:w-24 h-12 sm:h-24 bg-white rounded-full translate-y-6 sm:translate-y-12 -translate-x-6 sm:-translate-x-12"></div>
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-20 sm:w-40 h-20 sm:h-40 bg-white rounded-full opacity-5"></div>
                    </div>
                    
                    <div class="relative z-10 flex items-center justify-between">
                        <div class="flex items-center space-x-2 sm:space-x-4 min-w-0 flex-1">
                            <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-xl bg-white/20 backdrop-blur-sm p-1 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-lg sm:text-2xl font-bold truncate" id="modalTitle">Add New Job Application</h3>
                                <p class="text-white/90 mt-1 text-sm sm:text-base hidden sm:block" id="modalSubtitle">Fill in the details below to track your application</p>
                            </div>
                        </div>
                        <button onclick="closeJobModal()" class="text-white/80 hover:text-white p-1 sm:p-2 hover:bg-white/10 rounded-xl flex-shrink-0 ml-2">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-4 sm:p-8 max-h-[75vh] sm:max-h-[70vh] overflow-y-auto">
                    <div id="job-form-container">
                        @livewire('job-application-form', key('job-application-form-' . (session('edit-job-id', 0))))
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Notification system for form actions
        document.addEventListener('livewire:init', () => {
            Livewire.on('showNotification', (event) => {
                console.log('Notification received:', event);
                showFormNotification(event.type, event.title, event.message, event.duration || 3000);
            });
        });

        // Test function for notifications
        window.testNotification = function() {
            showFormNotification('success', 'Test Notification', 'This is a test notification', 3000);
        };

        function showFormNotification(type, title, message, duration) {
            console.log('Creating notification:', {type, title, message, duration});
            // Create notification element - same style as CSV export notification
            const notification = document.createElement('div');
            
            // Set notification styles based on type
            let bgColor, borderColor, iconColor, icon;
            
            switch(type) {
                case 'success':
                    bgColor = 'bg-white';
                    borderColor = 'border-green-200';
                    iconColor = 'text-green-600';
                    icon = `<div class="w-8 h-8 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>`;
                    break;
                case 'error':
                    bgColor = 'bg-white';
                    borderColor = 'border-red-200';
                    iconColor = 'text-red-600';
                    icon = `<div class="w-8 h-8 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>`;
                    break;
                case 'info':
                    bgColor = 'bg-white';
                    borderColor = 'border-primary-200';
                    iconColor = 'text-primary-600';
                    icon = `<div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </div>`;
                    break;
                default:
                    bgColor = 'bg-white';
                    borderColor = 'border-gray-200';
                    iconColor = 'text-gray-600';
                    icon = `<div class="w-8 h-8 bg-gradient-to-r from-gray-500 to-gray-600 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>`;
            }
            
            notification.className = `fixed top-4 right-4 ${bgColor} ${borderColor} border rounded-xl shadow-lg z-50 transform translate-x-full transition-all duration-300 backdrop-blur-sm`;
            notification.innerHTML = `
                <div class="flex items-center px-4 py-3 space-x-3">
                    ${icon}
                    <div>
                        <p class="text-sm font-medium text-gray-900">${title}</p>
                        <p class="text-xs text-gray-500">${message}</p>
                    </div>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
                notification.classList.add('translate-x-0');
            }, 100);
            
            // Auto remove after duration
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    notification.classList.remove('translate-x-0');
                    notification.classList.add('translate-x-full');
                    setTimeout(() => {
                        if (document.body.contains(notification)) {
                            document.body.removeChild(notification);
                        }
                    }, 300);
                }
            }, duration);
        }

        // View Toggle with smooth animations
        document.getElementById('kanban-view').addEventListener('click', function() {
            document.getElementById('kanban-container').classList.remove('hidden');
            document.getElementById('table-container').classList.add('hidden');
            this.classList.add('bg-white', 'text-primary-600', 'shadow-sm');
            this.classList.remove('text-gray-600');
            document.getElementById('table-view').classList.add('text-gray-600');
            document.getElementById('table-view').classList.remove('bg-white', 'text-primary-600', 'shadow-sm');
        });

        document.getElementById('table-view').addEventListener('click', function() {
            document.getElementById('table-container').classList.remove('hidden');
            document.getElementById('kanban-container').classList.add('hidden');
            this.classList.add('bg-white', 'text-primary-600', 'shadow-sm');
            this.classList.remove('text-gray-600');
            document.getElementById('kanban-view').classList.add('text-gray-600');
            document.getElementById('kanban-view').classList.remove('bg-white', 'text-primary-600', 'shadow-sm');
        });

        // Enhanced Modal Functions
        function openJobModal() {
            console.log('openJobModal called');
            const modal = document.getElementById('jobModal');
            const content = document.getElementById('modalContent');
            
            if (!modal) {
                console.error('Modal element not found!');
                return;
            }
            
            // Reset modal title to default (for add new)
            const modalTitle = document.getElementById('modalTitle');
            const modalSubtitle = document.getElementById('modalSubtitle');
            if (modalTitle && !window.currentEditJobId) {
                modalTitle.textContent = 'Add New Job Application';
                modalSubtitle.textContent = 'Fill in the details below to track your application';
            }
            
            console.log('Opening modal...');
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            
            
            // Animate modal in
            setTimeout(() => {
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
                console.log('Modal opened successfully');
            }, 10);
        }

        // Debounce close modal to prevent multiple calls
        let isClosingModal = false;
        
        function closeJobModal() {
            console.log('closeJobModal called from:', new Error().stack);
            
            if (isClosingModal) {
                console.log('Modal already closing, ignoring duplicate call');
                return;
            }
            
            isClosingModal = true;
            console.log('Closing job modal...');
            
            const modal = document.getElementById('jobModal');
            const content = document.getElementById('modalContent');
            
            // Reset edit job ID
            window.currentEditJobId = null;
            console.log('Reset edit job ID');
            
            // Close modal immediately without animation to prevent double effect
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            isClosingModal = false;
            console.log('Modal closed successfully');
        }

        // Close modal when clicking outside - MOVED TO DOMContentLoaded

        // Listen for job saved event (only for new applications)
        if (!window.jobSavedListener) {
            window.addEventListener('job-saved', function() {
                closeJobModal();
            });
            window.jobSavedListener = true;
        }

        // Listen for job updated event (for edits - don't close modal)
        if (!window.jobUpdatedListener) {
            window.addEventListener('job-updated', function() {
                console.log('Job updated successfully');
                // Don't close modal for edits, just show success message
            });
            window.jobUpdatedListener = true;
        }

        // Listen for edit job event - SIMPLIFIED VERSION
        if (!window.editJobListener) {
            window.addEventListener('edit-job', function(event) {
                console.log('Edit job event received:', event);
                console.log('Job ID:', event.detail?.jobId);
                
                // Update modal title for edit
                const modalTitle = document.getElementById('modalTitle');
                const modalSubtitle = document.getElementById('modalSubtitle');
                if (modalTitle) modalTitle.textContent = 'Edit Job Application';
                if (modalSubtitle) modalSubtitle.textContent = 'Update the details of your job application';
                
                // Store job ID for the form component
                if (event.detail?.jobId) {
                    window.currentEditJobId = event.detail.jobId;
                    console.log('Stored job ID:', window.currentEditJobId);
                    
                    // Open modal first
                    openJobModal();
                    
                    // Dispatch to Livewire component - SIMPLE VERSION
                    setTimeout(() => {
                        console.log('Dispatching editJob to Livewire component...');
                        if (typeof Livewire !== 'undefined') {
                            Livewire.dispatch('editJob', { jobId: parseInt(window.currentEditJobId) });
                            console.log('Dispatched editJob successfully');
                        }
                    }, 300);
                }
            });
            window.editJobListener = true;
        }

        // Setup drag and drop only once
        let dragDropSetup = false;
        
        function initDragAndDrop() {
            if (!dragDropSetup) {
                setupDragAndDrop();
                dragDropSetup = true;
            }
        }

        // Listen for Livewire updates to re-setup drag and drop
        document.addEventListener('livewire:updated', function() {
            dragDropSetup = false;
            setTimeout(initDragAndDrop, 100);
        });

        // Single DOMContentLoaded event listener
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Page loaded, initializing...');
            
            // Setup drag and drop
            setTimeout(initDragAndDrop, 100);
            
            // Add stagger animation to cards
            const cards = document.querySelectorAll('.group');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 100}ms`;
            });

            // Add smooth scrolling for table
            const table = document.querySelector('table');
            if (table) {
                table.style.scrollBehavior = 'smooth';
            }
            
            // Ensure delete modal is hidden
            const modal = document.getElementById('deleteModal');
            if (modal) {
                modal.classList.add('hidden');
                isModalOpen = false;
                window.isDeleting = false;
                currentDeleteJobId = null;
                currentDeleteJobCompany = '';
                currentDeleteJobPosition = '';
                console.log('Modal state reset on page load');
            }
        });

        // Modal outside click handler - REMOVED (causing issues)

        // Function to setup drag and drop
        function setupDragAndDrop() {
            console.log('Setting up drag and drop...');
            
            // Setup drag and drop for Kanban columns
            const columns = document.querySelectorAll('[data-status]');
            console.log('Found columns:', columns.length);
            columns.forEach(column => {
                // Remove existing listeners
                column.ondragover = null;
                column.ondrop = null;
                column.ondragleave = null;
                
                // Add new listeners
                column.ondragover = allowDrop;
                column.ondrop = function(event) {
                    event.preventDefault();
                    const statusName = this.dataset.status;
                    drop(event, statusName);
                };
                column.ondragleave = dragLeave;
            });

            // Setup drag and drop for job cards
            const jobCards = document.querySelectorAll('[draggable="true"]');
            console.log('Found job cards:', jobCards.length);
            jobCards.forEach(card => {
                card.ondragstart = function(event) {
                    const jobId = this.getAttribute('data-job-id');
                    if (jobId) {
                        dragStart(event, jobId);
                    }
                };
                card.ondragend = dragEnd;
            });
        }

        // Handle drop with proper event handling
        function handleDrop(event) {
            const statusName = event.currentTarget.dataset.status;
            drop(event, statusName);
        }

        // Global variables for modals
        let jobToDelete = null;

        // Delete modal functions
        function openDeleteModal(jobId, companyName) {
            jobToDelete = jobId;
            document.getElementById('deleteModal').querySelector('p').textContent = 
                `Are you sure you want to delete the job application for "${companyName}"? This action cannot be undone.`;
            openModal('deleteModal');
        }

        function confirmAction() {
            if (jobToDelete) {
                Livewire.dispatch('delete', { jobId: jobToDelete });
                closeModal('deleteModal');
                jobToDelete = null;
            }
        }

        // Drag and drop functions for Kanban
        function dragStart(event, jobId) {
            console.log('Drag started for job:', jobId);
            event.dataTransfer.setData('text/plain', jobId);
            event.target.style.opacity = '0.5';
            event.target.style.transform = 'rotate(5deg)';
        }

        function dragEnd(event) {
            console.log('Drag ended');
            event.target.style.opacity = '1';
            event.target.style.transform = 'rotate(0deg)';
        }

        function allowDrop(event) {
            event.preventDefault();
            event.dataTransfer.dropEffect = 'move';
            event.currentTarget.style.background = 'rgba(59, 130, 246, 0.1)';
            event.currentTarget.style.border = '2px dashed #3B82F6';
        }

        function dragLeave(event) {
            event.currentTarget.style.background = '';
            event.currentTarget.style.border = 'none';
        }

        function drop(event, statusName) {
            event.preventDefault();
            event.currentTarget.style.background = '';
            event.currentTarget.style.border = 'none';
            
            const jobId = event.dataTransfer.getData('text/plain');
            console.log('Dropped job:', jobId, 'to status:', statusName);
            
            if (jobId && statusName) {
                // Try multiple ways to dispatch the event
                try {
                    // Method 1: Direct dispatch
                    Livewire.dispatch('updateStatus', { jobId: parseInt(jobId), newStatus: statusName });
                    console.log('Dispatched updateStatus event (method 1)');
                    
                    // Method 2: Dispatch to specific component
                    setTimeout(() => {
                        Livewire.dispatch('updateStatus', { jobId: parseInt(jobId), newStatus: statusName }, 'job-kanban-board');
                        console.log('Dispatched updateStatus event (method 2)');
                    }, 100);
                    
                } catch (error) {
                    console.error('Error dispatching event:', error);
                }
            }
        }

        // Add some dynamic effects - MOVED TO SINGLE DOMContentLoaded

        // Delete Modal Functions
        let currentDeleteJobId = null;
        let currentDeleteJobCompany = '';
        let currentDeleteJobPosition = '';
        let isModalOpen = false;

        function openDeleteModal(jobId, companyName, position = '') {
            console.log('openDeleteModal called with:', { jobId, companyName, position });
            console.log('Current modal state:', { isModalOpen, currentDeleteJobId });
            
            // Prevent multiple calls
            if (isModalOpen) {
                console.log('Modal already open, ignoring call');
                return;
            }
            
            isModalOpen = true;
            currentDeleteJobId = jobId;
            currentDeleteJobCompany = companyName;
            currentDeleteJobPosition = position;
            
            const modal = document.getElementById('deleteModal');
            const modalContent = document.getElementById('deleteModalContent');
            
            console.log('Modal element:', modal);
            console.log('Modal content element:', modalContent);
            
            if (!modal) {
                console.error('Delete modal not found!');
                return;
            }
            
            // Update modal content
            const companyEl = document.getElementById('deleteJobCompany');
            const positionEl = document.getElementById('deleteJobPosition');
            
            console.log('Company element:', companyEl);
            console.log('Position element:', positionEl);
            
            if (companyEl) companyEl.textContent = companyName;
            if (positionEl) positionEl.textContent = position || 'Job Application';
            
            console.log('Opening delete modal...');
            console.log('Modal classes before:', modal.className);
            
            // Show modal by removing hidden class
            modal.classList.remove('hidden');
            
            console.log('Modal classes after:', modal.className);
            console.log('Modal computed style:', window.getComputedStyle(modal).display);
            console.log('Modal visibility:', window.getComputedStyle(modal).visibility);
            console.log('Modal opacity:', window.getComputedStyle(modal).opacity);
            console.log('Modal z-index:', window.getComputedStyle(modal).zIndex);
            console.log('Modal position:', window.getComputedStyle(modal).position);
            console.log('Modal top:', window.getComputedStyle(modal).top);
            console.log('Modal left:', window.getComputedStyle(modal).left);
            console.log('Modal width:', window.getComputedStyle(modal).width);
            console.log('Modal height:', window.getComputedStyle(modal).height);
            
            // Animate modal
            setTimeout(() => {
                if (modalContent) {
                    modalContent.classList.remove('scale-95');
                    modalContent.classList.add('scale-100');
                }
            }, 10);
        }

        function closeDeleteModal() {
            console.log('closeDeleteModal called');
            const modal = document.getElementById('deleteModal');
            const modalContent = document.getElementById('deleteModalContent');
            
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                isModalOpen = false;
                window.isDeleting = false; // Reset global flag too
                currentDeleteJobId = null;
                currentDeleteJobCompany = '';
                currentDeleteJobPosition = '';
                console.log('Modal closed, flags reset');
            }, 200);
        }

        function confirmDelete() {
            console.log('confirmDelete called with jobId:', currentDeleteJobId);
            if (currentDeleteJobId) {
                try {
                    console.log('Starting delete process...');
                    
                    // Try direct Livewire call first
                    console.log('Trying direct Livewire call...');
                    const tableComponent = Livewire.find('job-table-list');
                    if (tableComponent) {
                        console.log('Table component found, calling deleteJob...');
                        tableComponent.call('deleteJob', currentDeleteJobId);
                        console.log('Direct delete call successful');
                    } else {
                        console.log('Table component not found, trying dispatch...');
                        // Fallback to dispatch
                        Livewire.dispatch('confirm-delete', { jobId: currentDeleteJobId }, 'job-table-list');
                    }
                    
                    // Close modal after a short delay
                    setTimeout(() => {
                        closeDeleteModal();
                    }, 500);
                } catch (error) {
                    console.error('Error in delete process:', error);
                    closeDeleteModal();
                }
            } else {
                console.log('No job ID, closing modal');
                closeDeleteModal();
            }
        }
        
        // Direct delete function without modal
        window.directDelete = function(jobId) {
            console.log('Direct delete called for job:', jobId);
            try {
                Livewire.dispatch('confirm-delete', { jobId: jobId }, 'job-table-list');
                console.log('Direct delete dispatched to job-table-list');
            } catch (error) {
                console.error('Error in direct delete:', error);
            }
        };
        
        // Test delete with specific job ID
        window.testDeleteSpecific = function() {
            const jobCards = document.querySelectorAll('[data-job-id]');
            if (jobCards.length > 0) {
                const firstJob = jobCards[0];
                const jobId = firstJob.getAttribute('data-job-id');
                console.log('Testing delete for job ID:', jobId);
                directDelete(jobId);
            } else {
                console.log('No job cards found for testing');
            }
        };
        
        // Simple test delete function
        window.simpleDelete = function(jobId) {
            console.log('Simple delete for job:', jobId);
            try {
                // Try direct Livewire call
                const component = Livewire.find('job-table-list');
                if (component) {
                    component.call('deleteJob', jobId);
                    console.log('Delete called successfully');
                } else {
                    console.log('Component not found');
                }
            } catch (error) {
                console.error('Error calling delete:', error);
            }
        };
        
        // Test delete with current job
        window.testDeleteCurrent = function() {
            console.log('Testing delete with current job ID:', currentDeleteJobId);
            if (currentDeleteJobId) {
                simpleDelete(currentDeleteJobId);
            } else {
                console.log('No current job ID');
            }
        };
        
        // Test Livewire connection
        window.testLivewire = function() {
            console.log('Testing Livewire connection...');
            console.log('Livewire object:', window.Livewire);
            console.log('Livewire dispatch function:', typeof window.Livewire?.dispatch);
            try {
                Livewire.dispatch('test-event', { message: 'Hello Livewire' });
                console.log('Livewire dispatch test successful');
            } catch (error) {
                console.error('Livewire dispatch test failed:', error);
            }
        };
        
        // Quick test functions for debugging
        window.quickTest = function() {
            console.log('=== QUICK TEST START ===');
            console.log('1. Testing modal visibility...');
            const modal = document.getElementById('deleteModal');
            console.log('Modal element:', modal);
            console.log('Modal display style:', modal?.style.display);
            console.log('Modal computed display:', window.getComputedStyle(modal).display);
            
            console.log('2. Testing Livewire...');
            console.log('Livewire available:', typeof window.Livewire);
            console.log('Livewire dispatch:', typeof window.Livewire?.dispatch);
            
            console.log('3. Testing job data...');
            const jobCards = document.querySelectorAll('[data-job-id]');
            console.log('Job cards found:', jobCards.length);
            jobCards.forEach((card, index) => {
                const jobId = card.getAttribute('data-job-id');
                console.log(`Job ${index + 1}: ID ${jobId}`);
            });
            
            console.log('=== QUICK TEST END ===');
        };
        
        // Test modal with real job data
        window.testModalWithRealData = function() {
            const jobCards = document.querySelectorAll('[data-job-id]');
            if (jobCards.length > 0) {
                const firstJob = jobCards[0];
                const jobId = firstJob.getAttribute('data-job-id');
                const companyName = firstJob.querySelector('[data-company-name]')?.textContent || 'Test Company';
                const position = firstJob.querySelector('[data-position]')?.textContent || 'Test Position';
                
                console.log('Testing modal with real job data:', { jobId, companyName, position });
                openDeleteModal(jobId, companyName, position);
            } else {
                console.log('No job cards found for testing');
            }
        };
        
        // Force hide modal function
        window.forceHideModal = function() {
            console.log('Force hiding modal...');
            const modal = document.getElementById('deleteModal');
            if (modal) {
                modal.classList.add('hidden');
                isModalOpen = false;
                window.isDeleting = false;
                currentDeleteJobId = null;
                currentDeleteJobCompany = '';
                currentDeleteJobPosition = '';
                console.log('Modal force hidden');
            }
        };

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            const deleteModal = document.getElementById('deleteModal');
            if (deleteModal && e.target === deleteModal) {
                console.log('Modal clicked outside, closing...');
                closeDeleteModal();
            }
        });
        
        // Test function to manually show modal
        window.testModal = function() {
            console.log('Testing modal...');
            openDeleteModal(999, 'Test Company', 'Test Position');
        };
        
        // Reset function to manually reset flags
        window.resetModalFlags = function() {
            console.log('Resetting modal flags...');
            isModalOpen = false;
            window.isDeleting = false;
            currentDeleteJobId = null;
            currentDeleteJobCompany = '';
            currentDeleteJobPosition = '';
            console.log('Flags reset');
        };
        
        // Test delete function
        window.testDelete = function(jobId) {
            console.log('Testing delete for job:', jobId);
            Livewire.dispatch('confirm-delete', { jobId: jobId });
        };
        
        // Force show modal function
        window.forceShowModal = function() {
            const modal = document.getElementById('deleteModal');
            if (modal) {
                modal.style.display = 'flex';
                modal.style.visibility = 'visible';
                modal.style.opacity = '1';
                modal.style.zIndex = '9999';
                modal.style.position = 'fixed';
                modal.style.top = '0';
                modal.style.left = '0';
                modal.style.right = '0';
                modal.style.bottom = '0';
                modal.style.background = 'rgba(0, 0, 0, 0.5)';
                modal.style.alignItems = 'center';
                modal.style.justifyContent = 'center';
                modal.style.padding = '1rem';
                console.log('Modal forced to show');
            }
        };
        
        // Simple test modal function
        window.simpleTestModal = function() {
            console.log('Simple test modal...');
            const modal = document.getElementById('deleteModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.style.display = 'block';
                modal.style.position = 'fixed';
                modal.style.top = '50%';
                modal.style.left = '50%';
                modal.style.transform = 'translate(-50%, -50%)';
                modal.style.zIndex = '9999';
                modal.style.background = 'rgba(0, 0, 0, 0.8)';
                modal.style.width = '100vw';
                modal.style.height = '100vh';
                console.log('Simple modal should be visible now');
            }
        };

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
            }
        });
        
        // Ensure modal is hidden on page load - MOVED TO SINGLE DOMContentLoaded
        
        // Also reset on window load as backup
        window.addEventListener('load', function() {
            console.log('Window loaded, ensuring modal is hidden...');
            const modal = document.getElementById('deleteModal');
            if (modal) {
                modal.classList.add('hidden');
                isModalOpen = false;
                window.isDeleting = false;
                currentDeleteJobId = null;
                currentDeleteJobCompany = '';
                currentDeleteJobPosition = '';
                console.log('Modal state reset on window load');
            }
        });
    </script>

    <style>
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Animations removed for static design */
        
        .shadow-3xl {
            box-shadow: 0 35px 60px -12px rgba(0, 0, 0, 0.25);
        }
        
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Perfect centering for delete modal */
        #deleteModal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 9999;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        
        #deleteModal.hidden {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
            z-index: -1 !important;
        }
        
        /* Force hide modal on page load */
        #deleteModal {
            display: none;
        }
        
        #deleteModal:not(.hidden) {
            display: flex;
        }
        
        #deleteModalContent {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            max-width: 28rem;
            width: 100%;
            transform: scale(0.95);
        }
        
        #deleteModalContent.scale-100 {
            transform: scale(1);
        }
    </style>

</x-app-layout>