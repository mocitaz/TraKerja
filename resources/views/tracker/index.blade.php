<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg border border-white/30">
                        <img src="{{ asset('storage/logos/icon.png') }}" 
                             alt="TraKerja Logo" 
                             class="w-6 h-6"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold bg-gradient-to-r from-[#0056B3] to-[#28A745] bg-clip-text text-transparent">
                            Tracker
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">Smart tracking untuk Job Seeker</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <div class="flex items-center space-x-2 bg-green-50 px-3 py-1.5 rounded-full">
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
            <!-- Live Analytics Cards -->
            @livewire('analytics-cards')


            <!-- Compact View Toggle -->
            <div class="mb-6">
                <div class="bg-white rounded-lg shadow-sm border border-[#E9ECEF] p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Job Applications</h3>
                            <p class="text-xs text-gray-500">Manage and track your applications</p>
                        </div>
                        <div class="flex bg-gray-100 rounded-lg p-0.5">
                            <button id="table-view" class="px-4 py-2 rounded-md font-medium bg-white text-blue-600 shadow-sm text-sm">
                                <div class="flex items-center space-x-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0V4a2 2 0 012-2h14a2 2 0 012 2v16a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                                    </svg>
                                    <span>Table</span>
                                </div>
                            </button>
                            <button id="kanban-view" class="px-4 py-2 rounded-md font-medium text-gray-600 hover:text-gray-900 text-sm">
                                <div class="flex items-center space-x-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h2a2 2 0 002-2z"></path>
                                    </svg>
                                    <span>Kanban</span>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="space-y-6">
                <!-- Kanban Board -->
                <div id="kanban-container" class="hidden">
                    @livewire('job-kanban-board')
                </div>

                <!-- Table View -->
                <div id="table-container">
                    @livewire('job-table-list')
                </div>
            </div>
        </div>

        <!-- Floating Action Button -->
        <div class="fixed bottom-6 right-6 z-40">
            <button onclick="openJobModal()" 
                    class="group relative bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 hover:from-blue-700 hover:via-blue-800 hover:to-indigo-800 text-white font-medium py-2.5 px-4 rounded-lg shadow-lg border border-white/20">
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="text-xs font-medium">Add Job</span>
                </div>
            </button>
        </div>
    </div>

    <!-- Modern Job Application Modal -->
    <div id="jobModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden" id="modalContent">
                <div class="bg-gradient-to-r from-[#1E40AF] to-[#60A5FA] p-6 text-white relative overflow-hidden">
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white rounded-full -translate-y-16 translate-x-16"></div>
                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white rounded-full translate-y-12 -translate-x-12"></div>
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-40 h-40 bg-white rounded-full opacity-5"></div>
                    </div>
                    
                    <div class="relative z-10 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm p-1 flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold" id="modalTitle">Add New Job Application</h3>
                                <p class="text-white/90 mt-1" id="modalSubtitle">Fill in the details below to track your application</p>
                            </div>
                        </div>
                        <button onclick="closeJobModal()" class="text-white/80 hover:text-white p-2 hover:bg-white/10 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-8 max-h-[70vh] overflow-y-auto">
                    <div id="job-form-container">
                        @livewire('job-application-form', key('job-application-form-' . (session('edit-job-id', 'new'))))
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // View Toggle with smooth animations
        document.getElementById('kanban-view').addEventListener('click', function() {
            document.getElementById('kanban-container').classList.remove('hidden');
            document.getElementById('table-container').classList.add('hidden');
            this.classList.add('bg-white', 'text-blue-600', 'shadow-sm');
            this.classList.remove('text-gray-600');
            document.getElementById('table-view').classList.add('text-gray-600');
            document.getElementById('table-view').classList.remove('bg-white', 'text-blue-600', 'shadow-sm');
        });

        document.getElementById('table-view').addEventListener('click', function() {
            document.getElementById('table-container').classList.remove('hidden');
            document.getElementById('kanban-container').classList.add('hidden');
            this.classList.add('bg-white', 'text-blue-600', 'shadow-sm');
            this.classList.remove('text-gray-600');
            document.getElementById('kanban-view').classList.add('text-gray-600');
            document.getElementById('kanban-view').classList.remove('bg-white', 'text-blue-600', 'shadow-sm');
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
                
                // Reset form for new job and clear session
                if (typeof Livewire !== 'undefined') {
                    // Clear the edit job session to force new component instance
                    Livewire.dispatch('clearEditJobSession');
                    Livewire.dispatch('resetFormForNewJob');
                }
            }
            
            console.log('Opening modal...');
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            
            // Animate modal in
            setTimeout(() => {
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
                console.log('Modal opened successfully');
                
                // Dispatch custom event for Alpine.js
                document.dispatchEvent(new CustomEvent('modal-opened', {
                    detail: { jobId: window.currentEditJobId }
                }));
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
            
            // Reset form when closing modal
            if (typeof Livewire !== 'undefined') {
                Livewire.dispatch('resetFormForNewJob');
            }
            
            // Close modal immediately without animation to prevent double effect
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            isClosingModal = false;
            console.log('Modal closed successfully');
        }

        // Listen for job saved event (for both new applications and edits)
        if (!window.jobSavedListener) {
            window.addEventListener('job-saved', function() {
                // Reset edit job ID when job is saved
                window.currentEditJobId = null;
                console.log('Reset edit job ID after job saved');
                closeJobModal();
            });
            window.jobSavedListener = true;
        }

        // Listen for close-modal event
        if (!window.closeModalListener) {
            window.addEventListener('close-modal', function() {
                console.log('Close modal event received');
                closeJobModal();
            });
            window.closeModalListener = true;
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
            
            // Set default state - Table view active by default
            const tableView = document.getElementById('table-view');
            const kanbanView = document.getElementById('kanban-view');
            const tableContainer = document.getElementById('table-container');
            const kanbanContainer = document.getElementById('kanban-container');
            
            // Set table view as active
            tableView.classList.add('bg-white', 'text-blue-600', 'shadow-sm');
            tableView.classList.remove('text-gray-600');
            tableContainer.classList.remove('hidden');
            
            // Set kanban view as inactive
            kanbanView.classList.add('text-gray-600');
            kanbanView.classList.remove('bg-white', 'text-blue-600', 'shadow-sm');
            kanbanContainer.classList.add('hidden');
            
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
        });

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
    </style>

</x-app-layout>
