<x-app-layout>

    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-purple-50/20 to-blue-50/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            <!-- Header Section -->
            <div class="mb-6 sm:mb-8">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-primary-600 via-purple-600 to-indigo-600 bg-clip-text text-transparent mb-1">
                        Job Applications
                    </h1>
                    <p class="text-sm text-gray-600 font-medium">Manage and track your applications</p>
                </div>
            </div>

            <!-- Live Analytics Cards -->
            @livewire('analytics-cards')

            <!-- View Toggle -->
            <div class="mb-6 sm:mb-8">
                <div class="group relative bg-white/80 backdrop-blur-sm rounded-xl shadow-md border border-gray-200/50 p-4 sm:p-5 hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-500/3 to-purple-600/3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 flex items-center gap-2">
                                <span class="w-1 h-6 bg-gradient-to-b from-primary-500 to-purple-600 rounded-full"></span>
                                View Mode
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-500 mt-1">Switch between table and kanban view</p>
                        </div>
                        <div class="flex items-center gap-2 bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-gray-200/50 p-1.5">
                            <button id="table-view" class="px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg font-medium text-xs sm:text-sm transition-all duration-200 bg-gradient-to-r from-primary-500 to-purple-600 text-white shadow-md shadow-primary-500/30">
                                <div class="flex items-center space-x-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0V4a2 2 0 012-2h14a2 2 0 012 2v16a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                                    </svg>
                                    <span>Table</span>
                                </div>
                            </button>
                            <button id="kanban-view" class="px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg font-medium text-xs sm:text-sm transition-all duration-200 text-gray-600 hover:text-primary-600 hover:bg-gray-50">
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
            <div class="space-y-5 sm:space-y-6">
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
                    class="group relative bg-gradient-to-r from-primary-500 via-purple-600 to-indigo-600 hover:from-primary-600 hover:via-purple-700 hover:to-indigo-700 text-white font-semibold py-3 px-5 rounded-xl shadow-xl shadow-primary-500/30 hover:shadow-2xl hover:shadow-primary-500/40 border border-white/20 hover:scale-105 active:scale-95 transition-all duration-300">
                <div class="flex items-center space-x-2">
                    <div class="w-5 h-5 bg-white/20 rounded-lg flex items-center justify-center group-hover:rotate-90 transition-transform duration-300">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold">Add Job</span>
                </div>
                <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700 rounded-xl"></div>
            </button>
        </div>
    </div>

    <!-- Modern Job Application Modal -->
    <div id="jobModal" class="fixed inset-0 bg-black/60 backdrop-blur-md hidden z-50 animate-fade-in">
        <div class="flex items-center justify-center min-h-screen p-3 sm:p-4">
            <div class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl max-w-4xl w-full max-h-[95vh] sm:max-h-[90vh] overflow-hidden transform transition-all" id="modalContent">
                <!-- Header -->
                <div class="bg-gradient-to-r from-[#d983e4] via-[#c973d4] to-[#4e71c5] p-4 sm:p-6 text-white relative overflow-hidden">
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute top-0 right-0 w-24 h-24 sm:w-32 sm:h-32 bg-white rounded-full -translate-y-12 translate-x-12 sm:-translate-y-16 sm:translate-x-16"></div>
                        <div class="absolute bottom-0 left-0 w-20 h-20 sm:w-24 sm:h-24 bg-white rounded-full translate-y-10 -translate-x-10 sm:translate-y-12 sm:-translate-x-12"></div>
                    </div>
                    
                    <div class="relative z-10 flex items-center justify-between gap-3 sm:gap-4">
                        <div class="flex items-center gap-3 sm:gap-4 min-w-0 flex-1">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-white/20 backdrop-blur-sm p-2 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-lg sm:text-xl md:text-2xl font-bold truncate" id="modalTitle">Add New Job Application</h3>
                                <p class="text-white/90 mt-0.5 sm:mt-1 text-xs sm:text-sm truncate" id="modalSubtitle">Fill in the details below to track your application</p>
                            </div>
                        </div>
                        <button onclick="closeJobModal()" class="text-white/80 hover:text-white p-1.5 sm:p-2 hover:bg-white/10 rounded-xl transition-all flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <!-- Form Content -->
                <div class="p-4 sm:p-6 md:p-8 max-h-[calc(95vh-120px)] sm:max-h-[calc(90vh-140px)] overflow-y-auto custom-scrollbar">
                    <div id="job-form-container">
                        @livewire('job-application-form', key('job-application-form-' . (session('edit-job-id', 'new'))))
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Verification Success Modal -->
    <div id="verificationSuccessModal" class="fixed inset-0 bg-black/60 backdrop-blur-md hidden z-[100] animate-fade-in">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full transform transition-all duration-500 scale-95 opacity-0 relative overflow-hidden" id="verificationModalContent">
                <!-- Animated Background Gradient -->
                <div class="absolute inset-0 bg-gradient-to-br from-purple-50 via-blue-50 to-pink-50 opacity-50"></div>
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-200/30 to-transparent rounded-full blur-2xl"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-blue-200/30 to-transparent rounded-full blur-xl"></div>
                
                <div class="relative p-6 text-center">
                    <!-- Success Icon with Animation -->
                    <div class="relative mb-4">
                        <div class="flex items-center justify-center w-16 h-16 mx-auto rounded-full bg-gradient-to-br from-emerald-400 via-green-500 to-teal-600 shadow-xl shadow-green-500/30 animate-bounce-in">
                            <svg class="w-8 h-8 text-white animate-checkmark" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                        </svg>
                        </div>
                        <!-- Ripple Effect -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-16 h-16 rounded-full bg-green-400/20 animate-ping"></div>
                        </div>
                    </div>

                    <!-- Title with Gradient -->
                    <h3 class="text-xl font-bold bg-gradient-to-r from-gray-900 via-purple-600 to-blue-600 bg-clip-text text-transparent mb-1">
                        Email Verified! ✨
                    </h3>
                    <p class="text-xs text-gray-500 mb-4">Welcome to TraKerja 🎉</p>

                    <!-- Compact Features Grid -->
                    <div class="bg-gradient-to-br from-purple-50/80 to-blue-50/80 backdrop-blur-sm rounded-xl p-3 mb-4 border border-purple-100/50">
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="flex items-center gap-1.5 text-gray-700">
                                <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-medium">Unlimited Tracking</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-gray-700">
                                <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-medium">AI Resume Analysis</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-gray-700">
                                <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-medium">CV Builder</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-gray-700">
                                <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-medium">Interview Calendar</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Button with Hover Effect -->
                    <button onclick="closeVerificationModal()" 
                            class="group w-full px-5 py-2.5 rounded-xl font-semibold text-sm text-white bg-gradient-to-r from-purple-600 via-blue-600 to-purple-600 hover:from-purple-700 hover:via-blue-700 hover:to-purple-700 transition-all duration-300 shadow-lg shadow-purple-500/30 hover:shadow-xl hover:shadow-purple-500/40 hover:scale-[1.02] active:scale-[0.98] relative overflow-hidden">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            <span>Let's Get Started!</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></div>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes bounce-in {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        @keyframes checkmark {
            0% {
                stroke-dasharray: 0 50;
                stroke-dashoffset: 0;
            }
            100% {
                stroke-dasharray: 50 0;
                stroke-dashoffset: 0;
            }
        }
        
        @keyframes fade-in {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        .animate-bounce-in {
            animation: bounce-in 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
        }
        
        .animate-checkmark {
            stroke-dasharray: 50;
            stroke-dashoffset: 50;
            animation: checkmark 0.8s ease-out 0.3s forwards;
        }
        
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    </style>

    <script>
        // Check for verification success on page load
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('verified') === '1') {
                showVerificationSuccessModal();
                // Remove the query parameter from URL without page reload
                const newUrl = window.location.pathname;
                window.history.replaceState({}, '', newUrl);
            }
        });

        function showVerificationSuccessModal() {
            const modal = document.getElementById('verificationSuccessModal');
            const content = document.getElementById('verificationModalContent');
            
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            
            // Animate modal in with fade and scale
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
            
            // Create confetti effect
            createConfetti();
        }
        
        function createConfetti() {
            const colors = ['#a855f7', '#3b82f6', '#10b981', '#f59e0b', '#ef4444'];
            const confettiCount = 30;
            
            for (let i = 0; i < confettiCount; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.style.position = 'fixed';
                    confetti.style.left = Math.random() * 100 + '%';
                    confetti.style.top = '-10px';
                    confetti.style.width = '8px';
                    confetti.style.height = '8px';
                    confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.borderRadius = '50%';
                    confetti.style.pointerEvents = 'none';
                    confetti.style.zIndex = '9999';
                    confetti.style.opacity = '0.8';
                    confetti.style.transform = `rotate(${Math.random() * 360}deg)`;
                    
                    document.body.appendChild(confetti);
                    
                    const animation = confetti.animate([
                        { transform: 'translateY(0) rotate(0deg)', opacity: 0.8 },
                        { transform: `translateY(${window.innerHeight + 100}px) rotate(720deg)`, opacity: 0 }
                    ], {
                        duration: 2000 + Math.random() * 1000,
                        easing: 'cubic-bezier(0.5, 0, 0.5, 1)'
                    });
                    
                    animation.onfinish = () => confetti.remove();
                }, i * 50);
            }
        }

        function closeVerificationModal() {
            const modal = document.getElementById('verificationSuccessModal');
            const content = document.getElementById('verificationModalContent');
            
            // Animate modal out with fade and scale
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }, 300);
        }
    </script>

    <script>
        // View Toggle with smooth animations
        document.getElementById('kanban-view').addEventListener('click', function() {
            document.getElementById('kanban-container').classList.remove('hidden');
            document.getElementById('table-container').classList.add('hidden');
            this.classList.add('bg-gradient-to-r', 'from-primary-500', 'to-purple-600', 'text-white', 'shadow-md', 'shadow-primary-500/30');
            this.classList.remove('text-gray-600', 'hover:text-primary-600', 'hover:bg-gray-50');
            document.getElementById('table-view').classList.add('text-gray-600', 'hover:text-primary-600', 'hover:bg-gray-50');
            document.getElementById('table-view').classList.remove('bg-gradient-to-r', 'from-primary-500', 'to-purple-600', 'text-white', 'shadow-md', 'shadow-primary-500/30');
        });

        document.getElementById('table-view').addEventListener('click', function() {
            document.getElementById('table-container').classList.remove('hidden');
            document.getElementById('kanban-container').classList.add('hidden');
            this.classList.add('bg-gradient-to-r', 'from-primary-500', 'to-purple-600', 'text-white', 'shadow-md', 'shadow-primary-500/30');
            this.classList.remove('text-gray-600', 'hover:text-primary-600', 'hover:bg-gray-50');
            document.getElementById('kanban-view').classList.add('text-gray-600', 'hover:text-primary-600', 'hover:bg-gray-50');
            document.getElementById('kanban-view').classList.remove('bg-gradient-to-r', 'from-primary-500', 'to-purple-600', 'text-white', 'shadow-md', 'shadow-primary-500/30');
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
            tableView.classList.add('bg-gradient-to-r', 'from-primary-500', 'to-purple-600', 'text-white', 'shadow-md', 'shadow-primary-500/30');
            tableView.classList.remove('text-gray-600', 'hover:text-primary-600', 'hover:bg-gray-50');
            tableContainer.classList.remove('hidden');
            
            // Set kanban view as inactive
            kanbanView.classList.add('text-gray-600', 'hover:text-primary-600', 'hover:bg-gray-50');
            kanbanView.classList.remove('bg-gradient-to-r', 'from-primary-500', 'to-purple-600', 'text-white', 'shadow-md', 'shadow-primary-500/30');
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

        /* Custom Scrollbar for Modal */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #d983e4, #4e71c5);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #c973d4, #3d5fa3);
        }

        /* Fade in animation */
        @keyframes fade-in {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }

        /* Input focus enhancement */
        input:focus, select:focus, textarea:focus {
            border-color: #d983e4 !important;
            box-shadow: 0 0 0 3px rgba(217, 131, 228, 0.1) !important;
        }
    </style>

</x-app-layout>
