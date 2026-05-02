<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-2xl font-black text-slate-900 leading-tight tracking-tight">
                Interview <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#d983e4] via-primary-600 to-[#4e71c5]">Calendar</span>
            </h1>
            <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest mt-1">Manage and track your interview schedule</p>
        </div>
    </x-slot>

    <div class="bg-[#f8fafc] min-h-screen pb-20">
        <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            @livewire('interview-calendar')
        </div>
    </div>

    {{-- Modal pushed to @stack('modals') in app.blade.php — rendered directly inside <body>
         so fixed inset-0 covers the full viewport including sidebar and topbar. --}}
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
        style="display:none"
    >
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-xl" @click="closeModal()"></div>

        {{-- Modal Card --}}
        <div
            x-show="show"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 scale-95 translateY(10px)"
            x-transition:enter-end="opacity-100 scale-100 translateY(0)"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative bg-white rounded-[2rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.2)] max-w-lg w-full max-h-[90vh] flex flex-col overflow-hidden border border-slate-100 z-10"
            style="display:none"
            @click.stop
        >
            {{-- Modal Header: Clean White with Icon --}}
            <div class="bg-white px-6 py-5 text-slate-900 flex justify-between items-center border-b border-slate-100 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center shadow-sm">
                        <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-6 h-6 object-contain">
                    </div>
                    <div>
                        <h3 class="text-sm font-black tracking-tight" x-text="iv.company_name + ' Interview'">Interview Details</h3>
                        <p class="text-slate-400 text-[8px] font-bold uppercase tracking-widest mt-0.5">Career Growth Tracking</p>
                    </div>
                </div>
                <button @click="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-50 transition-all text-slate-400 hover:text-slate-900">
                    <i class="ph-bold ph-x text-base"></i>
                </button>
            </div>

            {{-- Modal Content --}}
            <div class="p-6 bg-white overflow-y-auto custom-scrollbar space-y-5">
                {{-- Quick Info Badges --}}
                <div class="flex flex-wrap gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest border"
                          :class="{
                            'bg-blue-50 text-blue-700 border-blue-100': ['HR - Interview', 'Psychotest'].includes(iv.recruitment_stage),
                            'bg-emerald-50 text-emerald-700 border-emerald-100': ['User - Interview', 'Presentation Round'].includes(iv.recruitment_stage),
                            'bg-purple-50 text-purple-700 border-purple-100': ['Assessment Test', 'LGD'].includes(iv.recruitment_stage),
                          }">
                        <i class="ph-bold text-[10px]" :class="iv.recruitment_stage === 'HR - Interview' ? 'ph-users' : 'ph-user-focus'"></i>
                        <span x-text="iv.recruitment_stage"></span>
                    </span>
                    <span class="px-3 py-1.5 bg-slate-100 text-slate-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-slate-200"
                          x-text="iv.application_status"></span>
                    <template x-if="iv.is_future">
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-50 text-amber-700 rounded-xl text-[10px] font-black uppercase tracking-widest border border-amber-100">
                            <i class="ph-bold ph-hourglass-high text-[10px]"></i>
                            <span x-text="iv.diff_humans"></span>
                        </span>
                    </template>
                </div>

                {{-- Detail Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 transition-all hover:bg-white hover:shadow-sm">
                        <div class="flex items-center gap-1.5 text-slate-400 mb-2">
                            <i class="ph-bold ph-calendar-check text-sm"></i>
                            <span class="text-[9px] font-black uppercase tracking-widest">Schedule</span>
                        </div>
                        <p class="text-sm font-black text-slate-900" x-text="iv.date_formatted"></p>
                        <p class="text-base font-black text-primary-600 mt-0.5" x-text="(iv.time_formatted || '') + ' WIB'"></p>
                    </div>
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 transition-all hover:bg-white hover:shadow-sm">
                        <div class="flex items-center gap-1.5 text-slate-400 mb-2">
                            <i class="ph-bold text-sm"
                               :class="['Phone','Video'].includes(iv.interview_type) ? 'ph-video-camera' : 'ph-map-pin'"></i>
                            <span class="text-[9px] font-black uppercase tracking-widest">Type / Link</span>
                        </div>
                        <p class="text-sm font-black text-slate-900" x-text="iv.interview_type || 'Unspecified'"></p>
                        <template x-if="iv.interview_location && iv.interview_location.startsWith('http')">
                            <a :href="iv.interview_location" target="_blank" class="text-xs font-bold text-primary-600 hover:underline truncate block mt-0.5">Open Link ↗</a>
                        </template>
                        <template x-if="iv.interview_location && !iv.interview_location.startsWith('http')">
                            <p class="text-xs font-bold text-slate-500 truncate mt-0.5" x-text="iv.interview_location"></p>
                        </template>
                        <template x-if="!iv.interview_location">
                            <p class="text-xs text-slate-400 mt-0.5 italic">No location added</p>
                        </template>
                    </div>
                </div>

                {{-- Notes Section --}}
                <template x-if="iv.interview_notes">
                    <div class="bg-primary-50/50 border border-primary-100 rounded-2xl p-4">
                        <div class="flex items-center gap-1.5 text-primary-600 mb-2">
                            <i class="ph-bold ph-note-pencil text-sm"></i>
                            <span class="text-[9px] font-black uppercase tracking-widest">Interview Notes</span>
                        </div>
                        <p class="text-[11px] text-slate-700 font-medium leading-relaxed whitespace-pre-line" x-text="iv.interview_notes"></p>
                    </div>
                </template>
            </div>

            {{-- Footer: Clean with TraKerja Branding --}}
            <div class="px-7 py-5 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between gap-3">
                <div class="flex items-center gap-1.5">
                    <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-4 h-4 grayscale opacity-50">
                    <span class="text-[10px] font-black text-slate-400 tracking-widest uppercase">TraKerja Tracker</span>
                </div>
                <button @click="closeModal()"
                        class="px-8 py-2.5 bg-primary-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-primary-700 transition-all shadow-lg active:scale-95">
                    Dismiss
                </button>
            </div>
        </div>
    </div>

    {{-- Bridge: wire window event → Alpine component --}}
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('open-interview-modal', (event) => {
                const root = document.getElementById('interview-modal-root');
                if (!root) return;
                
                // Livewire 3 passes data directly or in an array
                const data = event.interview || (Array.isArray(event) ? event[0].interview : event);
                const alpine = Alpine.$data(root);
                
                if (alpine && data) {
                    alpine.openModal(data);
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
