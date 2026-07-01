<x-app-layout>
    <x-slot name="header">
        <!-- Ignored layout slot, header is handled inline inside template container for premium consistency -->
    </x-slot>

    <div class="bg-[#fafafa] min-h-screen pb-16">
        <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            
            <!-- Premium Notion-Inspired Page Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-zinc-200/50 pb-4 mb-5">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-zinc-100 border border-zinc-200/60 rounded-lg flex items-center justify-center text-zinc-500 shrink-0 shadow-2xs">
                        <i class="ph ph-layout text-base"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-sm font-bold text-zinc-800 tracking-tight">Track Progress</h1>
                            <span class="px-1.5 py-0.5 bg-primary-50 text-zinc-800 /* [BRAND_PRIMARY] */ text-[9px] font-black uppercase tracking-wider rounded border border-primary-100/60 animate-none">Tracker</span>
                        </div>
                        <p class="text-[11px] text-zinc-400 mt-0.5">Manage and update your active job applications across all recruitment stages.</p>
                    </div>
                </div>
            </div>

            <!-- Action Bar: Switcher & Add Button -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 mb-5">
                <!-- View Switcher (Simple, no effects) -->
                <div class="flex w-full sm:w-auto p-1 bg-white border border-zinc-200/60 rounded-lg shrink-0 shadow-3xs">
                    <button onclick="switchView('table')" id="table-view-btn" class="flex-1 sm:flex-none justify-center flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-md bg-zinc-900 text-white transition-colors focus:outline-none">
                        <i class="ph ph-list text-sm"></i>
                        <span>List</span>
                    </button>
                    <button onclick="switchView('kanban')" id="kanban-view-btn" class="flex-1 sm:flex-none justify-center flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-zinc-550 rounded-md hover:bg-zinc-50 transition-colors focus:outline-none">
                        <i class="ph ph-kanban text-sm"></i>
                        <span>Kanban</span>
                    </button>
                </div>

                <!-- Add Opportunity Button -->
                 <button onclick="openJobModal()" class="w-full sm:w-auto flex items-center justify-center gap-1 px-3 h-[30px] bg-primary-50 hover:bg-primary-100 text-zinc-800 border border-primary-200/60 text-[11px] font-bold rounded-md shadow-3xs transition-all duration-150 active:scale-97 hover:shadow-2xs shrink-0 focus:outline-none">
                    <i class="ph ph-plus text-xs"></i>
                    <span>Add New Application</span>
                </button>
            </div>

            <!-- Content Area -->
            <div class="relative">
                <div id="list-view-container">
                    @livewire('job-table-list')
                </div>

                <div id="kanban-view-container" class="hidden">
                    @livewire('job-kanban-board')
                </div>
            </div>
        </div>
    </div>

    @push('modals')
    <!-- Ultra Compact Floating Modal -->
    <div id="jobModal" class="fixed inset-0 bg-zinc-950/40 backdrop-blur-xs hidden z-[99999] flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] flex flex-col overflow-hidden border border-zinc-200 transform transition-all">
            <!-- Modal Header: Clean White -->
            <div class="bg-white px-4 py-3 text-zinc-900 flex justify-between items-center border-b border-zinc-150/60 shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200/60 flex items-center justify-center shadow-3xs">
                        <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-4 h-4 object-contain" onerror="this.src='{{ asset('favicon.png') }}'">
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <h3 class="text-xs font-bold text-zinc-800 tracking-tight" id="modalTitle">New Opportunity</h3>
                            <span id="modalBadge" class="px-1.5 py-0.5 bg-primary-50 text-zinc-800 text-[8.5px] font-bold uppercase tracking-wider rounded border border-primary-100/60 leading-none">Tracking</span>
                        </div>
                        <p class="text-zinc-400 text-[9px] font-medium mt-0.5">Career Growth Tracking</p>
                    </div>
                </div>
                <button onclick="closeJobModal()" class="w-7 h-7 flex items-center justify-center rounded hover:bg-zinc-50 transition-all text-zinc-400 hover:text-zinc-800">
                    <i class="ph ph-x text-sm"></i>
                </button>
            </div>
            
            <div class="p-4 bg-white overflow-y-auto custom-scrollbar">
                @livewire('job-application-form')
            </div>
        </div>
    </div>
    @endpush

    @push('styles')
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e4e4e7;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #d4d4d8;
        }
    </style>
    @endpush

    <script>
        window.addEventListener('edit-job', event => {
            document.getElementById('modalTitle').innerText = 'Edit Opportunity';
            document.getElementById('modalBadge').innerText = 'Edit';
            window.openJobModal(true);
        });

        window.openJobModal = function(isEdit = false, defaultStage = null) {
            const modal = document.getElementById('jobModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            if (!isEdit) {
                Livewire.dispatch('resetFormForNewJob', { defaultStage: defaultStage });
            }
        }

        window.closeJobModal = function() {
            const modal = document.getElementById('jobModal');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            // Reset modal title and badge
            document.getElementById('modalTitle').innerText = 'New Opportunity';
            document.getElementById('modalBadge').innerText = 'Tracking';
            // Reset form when closing for fresh state on "Add"
            Livewire.dispatch('resetFormForNewJob');
        }

        function switchView(type) {
            const listCont = document.getElementById('list-view-container');
            const kanbanCont = document.getElementById('kanban-view-container');
            const listBtn = document.getElementById('table-view-btn');
            const kanbanBtn = document.getElementById('kanban-view-btn');

            if (type === 'table') {
                listCont.classList.remove('hidden');
                kanbanCont.classList.add('hidden');
                
                listBtn.className = "flex-1 sm:flex-none justify-center flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-md bg-zinc-900 text-white transition-colors focus:outline-none";
                kanbanBtn.className = "flex-1 sm:flex-none justify-center flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-zinc-550 rounded-md hover:bg-zinc-50 transition-colors focus:outline-none";
            } else {
                kanbanCont.classList.remove('hidden');
                listCont.classList.add('hidden');
                
                kanbanBtn.className = "flex-1 sm:flex-none justify-center flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-md bg-zinc-900 text-white transition-colors focus:outline-none";
                listBtn.className = "flex-1 sm:flex-none justify-center flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-zinc-550 rounded-md hover:bg-zinc-50 transition-colors focus:outline-none";
            }
        }

        // Livewire Event Listeners
        document.addEventListener('livewire:init', () => {
            Livewire.on('close-modal', () => {
                window.closeJobModal();
            });
        });

        // Auto-open edit modal if parameter exists in URL
        window.addEventListener('load', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const editId = urlParams.get('edit');
            if (editId) {
                setTimeout(() => {
                    Livewire.dispatch('edit-job', { jobId: editId });
                }, 500);
            }
        });
    </script>
</x-app-layout>
