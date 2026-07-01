<x-app-layout>
    <div class="bg-[#fafafa] min-h-screen pb-16">
        <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            <!-- Premium Notion-Inspired Page Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-zinc-200/50 pb-4 mb-5">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-zinc-100 border border-zinc-200/60 rounded-lg flex items-center justify-center text-zinc-500 shrink-0 shadow-2xs">
                        <i class="ph ph-calendar text-base"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-sm font-bold text-zinc-800 tracking-tight">Interview Calendar</h1>
                            <span class="px-1.5 py-0.5 bg-primary-50 text-zinc-800 text-[9px] font-black uppercase tracking-wider rounded border border-primary-100/60 animate-none">Schedule</span>
                        </div>
                        <p class="text-[11px] text-zinc-400 mt-0.5">Strategic schedule management for upcoming recruitment stages.</p>
                    </div>
                </div>
            </div>

            @livewire('interview-calendar')
        </div>
    </div>

    {{-- Modal pushed to @stack('modals') in app.blade.php — rendered directly inside <body> --}}
    @push('modals')
    <div
        id="interview-modal-root"
        x-data="{
            show: false,
            iv: {},
            openModal(data) { this.iv = data; this.show = true; },
            closeModal() { this.show = false; }
        }"
        x-show="show"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
        style="display: none;"
        x-cloak
    >
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-zinc-950/40 backdrop-blur-xs" @click="closeModal()"></div>

        {{-- Modal Card --}}
        <div
            x-show="show"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 scale-98 translateY(6px)"
            x-transition:enter-end="opacity-100 scale-100 translateY(0)"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 scale-100 translateY(0)"
            x-transition:leave-end="opacity-0 scale-98 translateY(6px)"
            class="relative bg-white rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] flex flex-col overflow-hidden border border-zinc-200 z-10 text-left"
            @click.stop
        >
            {{-- Modal Header --}}
            <div class="bg-white px-4 py-3.5 text-zinc-900 flex justify-between items-center border-b border-zinc-200/60 shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded bg-zinc-50 border border-zinc-200/60 flex items-center justify-center shadow-3xs">
                        <i class="ph ph-calendar text-zinc-500 text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-zinc-800 tracking-tight" x-text="iv.company_name + ' Interview'">Interview Details</h3>
                        <p class="text-[8px] text-zinc-400 font-bold uppercase tracking-widest mt-0.5">Recruitment Schedule</p>
                    </div>
                </div>
                <button @click="closeModal()" class="w-7 h-7 flex items-center justify-center rounded hover:bg-zinc-50 transition-colors text-zinc-400 hover:text-zinc-800">
                    <i class="ph ph-x text-sm"></i>
                </button>
            </div>

            {{-- Modal Content --}}
            <div class="p-4 bg-white overflow-y-auto custom-scrollbar space-y-4">
                {{-- Quick Info Badges --}}
                <div class="flex flex-wrap gap-1.5">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-zinc-50 border border-zinc-200 rounded text-[9px] font-semibold text-zinc-600"
                          :class="{
                            'bg-blue-50/70 text-blue-700 border-blue-100/50': ['HR - Interview', 'Psychotest'].includes(iv.recruitment_stage),
                            'bg-emerald-50/70 text-emerald-700 border-emerald-100/50': ['User - Interview', 'Presentation Round'].includes(iv.recruitment_stage),
                            'bg-purple-50/70 text-purple-700 border-purple-100/50': ['Assessment Test', 'LGD'].includes(iv.recruitment_stage),
                          }">
                        <i class="ph text-[10px]" :class="iv.recruitment_stage === 'HR - Interview' ? 'ph-users' : 'ph-user-focus'"></i>
                        <span x-text="iv.recruitment_stage"></span>
                    </span>
                    <span class="px-2 py-0.5 bg-zinc-100 text-zinc-600 rounded text-[9px] font-semibold border border-zinc-200"
                          x-text="iv.application_status"></span>
                    <template x-if="iv.is_future">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-amber-50 text-amber-700 rounded text-[9px] font-semibold border border-amber-100">
                            <i class="ph ph-clock text-[9px]"></i>
                            <span x-text="iv.diff_humans"></span>
                        </span>
                    </template>
                </div>

                {{-- Detail Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="bg-zinc-50/30 border border-zinc-200/50 rounded-md p-3">
                        <div class="flex items-center gap-1.5 text-zinc-400 mb-1">
                            <i class="ph ph-calendar-check text-xs"></i>
                            <span class="text-[8px] font-bold uppercase tracking-wider">Schedule</span>
                        </div>
                        <p class="text-xs font-bold text-zinc-800" x-text="iv.date_formatted"></p>
                        <p class="text-sm font-bold text-primary-650 /* [BRAND_PRIMARY] */ mt-0.5" x-text="(iv.time_formatted || '') + ' WIB'"></p>
                    </div>
                    <div class="bg-zinc-50/30 border border-zinc-200/50 rounded-md p-3">
                        <div class="flex items-center gap-1.5 text-zinc-400 mb-1">
                            <i class="ph text-xs" :class="['Phone','Video'].includes(iv.interview_type) ? 'ph-video-camera' : 'ph-map-pin'"></i>
                            <span class="text-[8px] font-bold uppercase tracking-wider">Type / Location</span>
                        </div>
                        <p class="text-xs font-bold text-zinc-800" x-text="iv.interview_type || 'Unspecified'"></p>
                        <template x-if="iv.interview_location && iv.interview_location.startsWith('http')">
                            <a :href="iv.interview_location" target="_blank" class="text-[10px] font-bold text-primary-600 /* [BRAND_PRIMARY] */ hover:underline truncate block mt-0.5">Open Link ↗</a>
                        </template>
                        <template x-if="iv.interview_location && !iv.interview_location.startsWith('http')">
                            <p class="text-[10px] font-semibold text-zinc-500 truncate mt-0.5" x-text="iv.interview_location"></p>
                        </template>
                        <template x-if="!iv.interview_location">
                            <p class="text-[10px] text-zinc-400 mt-0.5 italic leading-none">No link or address added</p>
                        </template>
                    </div>
                </div>

                {{-- Notes Section --}}
                <template x-if="iv.interview_notes">
                    <div class="bg-primary-50/30 border border-primary-100 rounded-md p-3">
                        <div class="flex items-center gap-1.5 text-primary-600 /* [BRAND_PRIMARY] */ mb-1">
                            <i class="ph ph-note-pencil text-xs"></i>
                            <span class="text-[8px] font-bold uppercase tracking-wider">Interview Notes</span>
                        </div>
                        <p class="text-[11px] text-zinc-700 font-medium leading-relaxed whitespace-pre-line" x-text="iv.interview_notes"></p>
                    </div>
                </template>
            </div>

            {{-- Footer --}}
            <div class="px-4 py-3 bg-zinc-50/30 border-t border-zinc-200/60 flex items-center justify-between gap-3 shrink-0">
                <div class="flex items-center gap-1">
                    <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-3 h-3 grayscale opacity-40">
                    <span class="text-[8px] font-bold text-zinc-400 tracking-wider uppercase">TraKerja Calendar</span>
                </div>
                <button @click="closeModal()"
                        class="px-4 py-1.5 bg-zinc-900 text-white rounded-md font-bold text-[10px] uppercase tracking-wider hover:bg-zinc-800 transition-colors focus:outline-none">
                    Close
                </button>
            </div>
        </div>
    </div>

    {{-- Bridge: wire window event → Alpine component --}}
    <script>
        if (typeof window.interviewModalInitialized === 'undefined') {
            window.interviewModalInitialized = true;
            
            const handleOpenModal = (event) => {
                const root = document.getElementById('interview-modal-root');
                if (!root) return;
                
                let data = null;
                // Check all possible wrapper variations from Livewire dispatch
                if (event.detail && event.detail.interview) {
                    data = event.detail.interview;
                } else if (event.interview) {
                    data = event.interview;
                } else if (Array.isArray(event) && event[0] && event[0].interview) {
                    data = event[0].interview;
                } else if (event.detail) {
                    data = event.detail;
                } else if (typeof event === 'object' && !event.detail) {
                    data = event;
                }
                
                if (data) {
                    // Safe access to Alpine data
                    if (window.Alpine) {
                        const alpine = Alpine.$data(root);
                        if (alpine) {
                            alpine.openModal(data);
                        }
                    }
                }
            };
            
            // Listen to browser-dispatched custom events
            window.addEventListener('open-interview-modal', handleOpenModal);
            
            // Also listen to Livewire events for extra reliability
            document.addEventListener('livewire:init', () => {
                Livewire.on('open-interview-modal', handleOpenModal);
            });
        }
    </script>
    @endpush
</x-app-layout>
