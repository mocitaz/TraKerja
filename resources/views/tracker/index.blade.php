<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-2xl font-black text-slate-900 leading-tight tracking-tight">
                Job <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#d983e4] via-purple-600 to-[#4e71c5]">Tracker</span>
            </h1>
            <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest mt-1">Manage your career evolution</p>
        </div>
    </x-slot>

    <div class="bg-[#f8fafc] min-h-screen pb-20">
        <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            
            <!-- Analytics Section -->
            <div class="mb-6">
                @livewire('analytics-cards')
            </div>

            <!-- Action Bar: Switcher & Add Button (Back to Original Place) -->
            <div class="flex flex-col md:flex-row items-center justify-between gap-4 md:gap-6 mb-6">
                <div class="flex w-full md:w-auto p-1.5 bg-white border border-slate-200/60 rounded-2xl shadow-sm backdrop-blur-md">
                    <button onclick="switchView('table')" id="table-view-btn" class="flex-1 md:flex-none justify-center flex items-center gap-2 px-8 py-2.5 text-sm font-black rounded-xl transition-all duration-300 bg-primary-600 text-white shadow-lg shadow-primary-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                        <span>List</span>
                    </button>
                    <button onclick="switchView('kanban')" id="kanban-view-btn" class="flex-1 md:flex-none justify-center flex items-center gap-2 px-8 py-2.5 text-sm font-bold text-slate-400 rounded-xl hover:text-primary-600 hover:bg-slate-50 transition-all duration-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h2a2 2 0 002-2z"></path></svg>
                        <span>Kanban</span>
                    </button>
                </div>

                <button onclick="openJobModal()" class="w-full md:w-auto magnetic-btn group relative flex items-center justify-center gap-2 px-8 py-3 bg-slate-900 text-white text-sm font-black rounded-2xl hover:bg-slate-800 transition-all shadow-xl shadow-slate-200 active:scale-95 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-600/20 to-violet-600/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <svg class="w-5 h-5 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    <span class="relative z-10">Add New Application</span>
                </button>
            </div>


            <!-- Content Area -->
            <div class="relative">
                <div id="list-view-container" class="transition-all duration-500 transform">
                    @livewire('job-table-list')
                </div>

                <div id="kanban-view-container" class="hidden transition-all duration-500 transform">
                    @livewire('job-kanban-board')
                </div>
            </div>
        </div>
    </div>

    @push('modals')
    <!-- Ultra Compact Floating Modal -->
    <div id="jobModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xl hidden z-[9999] flex items-center justify-center p-4 transition-all duration-300">
        <div class="bg-white rounded-[2rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.2)] max-w-lg w-full max-h-[90vh] flex flex-col overflow-hidden border border-slate-100 transform transition-all animate-modal-in">
            <!-- Modal Header: Clean White -->
            <div class="bg-white px-6 py-5 text-slate-900 flex justify-between items-center border-b border-slate-100 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center shadow-sm">
                        <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-6 h-6 object-contain">
                    </div>
                    <div>
                        <h3 class="text-sm font-black tracking-tight" id="modalTitle">New Opportunity</h3>
                        <p class="text-slate-400 text-[8px] font-bold uppercase tracking-widest mt-0.5">Career Growth Tracking</p>
                    </div>
                </div>
                <button onclick="closeJobModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-50 transition-all text-slate-400 hover:text-slate-900">
                    <i class="ph-bold ph-x text-base"></i>
                </button>
            </div>
            
            <div class="p-6 bg-white overflow-y-auto custom-scrollbar">
                @livewire('job-application-form')
            </div>
        </div>
    </div>

    <style>
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.95) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .animate-modal-in {
            animation: modalIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
    @endpush

    <script>
        window.addEventListener('edit-job', event => {
            document.getElementById('modalTitle').innerText = 'Edit Opportunity';
            window.openJobModal();
        });

        window.openJobModal = function() {
            const modal = document.getElementById('jobModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        window.closeJobModal = function() {
            const modal = document.getElementById('jobModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            // Optional: reset form when closing if you want fresh state for next "Add"
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
                listBtn.classList.add('bg-primary-600', 'text-white', 'shadow-lg');
                listBtn.classList.remove('text-slate-400');
                kanbanBtn.classList.remove('bg-primary-600', 'text-white', 'shadow-lg');
                kanbanBtn.classList.add('text-slate-400');
            } else {
                kanbanCont.classList.remove('hidden');
                listCont.classList.add('hidden');
                kanbanBtn.classList.add('bg-primary-600', 'text-white', 'shadow-lg');
                kanbanBtn.classList.remove('text-slate-400');
                listBtn.classList.remove('bg-primary-600', 'text-white', 'shadow-lg');
                listBtn.classList.add('text-slate-400');
            }

            // Trigger Transition Animation
            window.dispatchEvent(new CustomEvent('view-switched', { detail: { type: type } }));
        }

        // Auto-open edit modal if parameter exists in URL
        window.addEventListener('load', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const editId = urlParams.get('edit');
            if (editId) {
                // Wait a bit for Livewire to be ready
                setTimeout(() => {
                    Livewire.dispatch('edit-job', { jobId: editId });
                }, 500);
            }
        });
    </script>

</x-app-layout>
