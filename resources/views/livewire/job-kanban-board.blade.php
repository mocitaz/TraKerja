<div>
    <div class="space-y-4">
        <!-- Search & Filter Bar (Identical to Table) -->
        <div class="flex flex-col lg:flex-row items-center justify-between gap-3 p-3 bg-white border border-zinc-200/60 rounded-lg shadow-3xs">
            <div class="relative w-full lg:w-72 group">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search in kanban..." 
                       class="block w-full pl-9 pr-4 h-[30px] bg-zinc-50/50 border border-zinc-200 rounded-md text-[11px] font-semibold tracking-tight transition-all focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white outline-none">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-400">
                    <i class="ph ph-magnifying-glass text-sm"></i>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:flex sm:items-center gap-2 w-full lg:w-auto">
                <select wire:model.live="platformFilter" class="w-full bg-zinc-50/50 border border-zinc-200 rounded-md text-[11px] font-semibold text-zinc-600 h-[30px] py-0 pl-2.5 pr-8 focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white outline-none cursor-pointer">
                    <option value="">PLATFORM</option>
                    @foreach($platformOptions as $platform) <option value="{{ $platform }}">{{ strtoupper($platform) }}</option> @endforeach
                </select>
                <button wire:click="toggleArchived" class="w-full sm:w-auto px-3 h-[30px] rounded-md text-[11px] font-bold border transition-all duration-150 active:scale-97 hover:shadow-2xs focus:outline-none flex items-center justify-center gap-1 {{ $showArchived ? 'bg-primary-50 hover:bg-primary-100 text-zinc-800 border border-primary-200/60 shadow-3xs' : 'bg-white border-zinc-200 text-zinc-500 hover:bg-zinc-50 hover:text-zinc-800 shadow-3xs' }}">
                    <i class="ph ph-archive text-[13px]"></i>
                    <span>{{ $showArchived ? 'Active' : 'Archive' }}</span>
                </button>
            </div>
        </div>

        <!-- Clean 3-Column Kanban Board -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 w-full items-start">
            @foreach($statuses as $status)
                <div class="flex flex-col bg-zinc-50/40 rounded-lg p-3 border border-zinc-200/50"
                     ondragover="allowDrop(event)" 
                     ondrop="drop(event, '{{ $status->name }}')"
                     data-status-container="{{ $status->name }}">
                    
                    <!-- Column Header -->
                    <div class="flex items-center justify-between mb-3 px-1">
                        <div class="flex items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $status->color_code }}; shadow: 0 0 6px {{ $status->color_code }}30;"></div>
                            <h3 class="text-[10px] font-bold text-zinc-600 uppercase tracking-widest">{{ $status->name }}</h3>
                        </div>
                        <span class="text-[9px] font-bold bg-white text-zinc-400 px-1.5 py-0.2 rounded border border-zinc-200/60 shadow-3xs">
                            {{ $status->jobApplications->count() }}
                        </span>
                    </div>

                    <!-- Job Cards Container -->
                    <div class="space-y-2.5 min-h-[200px]">
                        {{-- Skeleton Loading Cards --}}
                        <div wire:loading.class.remove="hidden" class="hidden space-y-2.5">
                            @for($i = 0; $i < 2; $i++)
                                <div class="bg-white rounded-md p-3 border border-zinc-200 shadow-3xs">
                                    <div class="flex items-center gap-2.5 mb-2.5">
                                        <div class="w-7 h-7 rounded bg-zinc-100 skeleton"></div>
                                        <div class="space-y-1.5 flex-1">
                                            <div class="h-2.5 w-3/4 bg-zinc-100 rounded skeleton"></div>
                                            <div class="h-2 w-1/2 bg-zinc-100 rounded skeleton"></div>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>

                        <div wire:loading.remove class="space-y-2.5">
                            @forelse($status->jobApplications as $job)
                            <div class="group bg-white rounded-md p-3 border border-zinc-200/65 shadow-3xs hover:border-zinc-300 hover:shadow-2xs transition-all cursor-grab active:cursor-grabbing {{ $job->is_pinned ? 'ring-1 ring-primary-500/20 border-primary-400' : ($job->isGhosted() ? 'border-amber-400 bg-amber-50/10' : 'border-zinc-200/65') }}"
                                 draggable="true"
                                 id="job-card-{{ $job->id }}"
                                 ondragstart="drag(event, '{{ $job->id }}')"
                                 onclick="window.location.href='{{ route('jobs.show', $job) }}'">
                                
                                <div class="flex items-start justify-between gap-2 mb-2.5">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-7 h-7 rounded-md bg-zinc-50 border border-zinc-200/50 flex items-center justify-center text-zinc-500 font-bold text-[10px] shrink-0 uppercase shadow-3xs group-hover:bg-zinc-100 transition-colors">
                                            {{ substr($job->company_name, 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="text-[11px] font-bold text-zinc-800 group-hover:text-primary-600 transition-colors leading-tight truncate">{{ $job->company_name }}</h4>
                                            <p class="text-[9px] font-semibold text-zinc-500 mt-0.5 truncate">{{ $job->position }}</p>
                                        </div>
                                    </div>
                                    @if($job->isGhosted())
                                        <div class="w-5 h-5 bg-rose-50 rounded flex items-center justify-center text-rose-500 border border-rose-100 shrink-0" title="Lebih dari 14 hari tanpa kabar!">
                                            <i class="ph ph-warning-circle text-xs"></i>
                                        </div>
                                    @elseif($job->is_pinned)
                                        <div class="w-5 h-5 bg-amber-50 rounded flex items-center justify-center text-amber-500 border border-amber-100 shrink-0">
                                            <i class="ph-fill ph-push-pin text-[10px]"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex items-center justify-between pt-2.5 border-t border-zinc-200/60" onclick="event.stopPropagation();">
                                    <div class="flex items-center gap-1">
                                        <button wire:click.stop="edit('{{ $job->id }}')" class="p-1 text-zinc-400 hover:text-indigo-600 hover:bg-zinc-50 border border-zinc-200 rounded transition-colors">
                                            <i class="ph ph-pencil-simple text-xs"></i>
                                        </button>
                                        <button wire:click.stop="confirmDelete('{{ $job->id }}')" class="p-1 text-zinc-400 hover:text-rose-600 hover:bg-rose-50 border border-zinc-200 rounded transition-colors">
                                            <i class="ph ph-trash text-xs"></i>
                                        </button>
                                    </div>
                                    <div class="flex items-center gap-1 px-1.5 py-0.5 bg-zinc-50 rounded border border-zinc-200">
                                        <i class="ph ph-calendar text-zinc-400 text-[10px]"></i>
                                        <span class="text-[9px] font-bold text-zinc-500 uppercase leading-none">{{ $job->application_date->format('d M') }}</span>
                                    </div>
                                </div>
                                
                                @if($job->recruitment_stage)
                                    <div class="mt-2.5 flex items-center justify-between gap-1">
                                        <span class="px-1.5 py-0.2 rounded text-[9px] font-bold uppercase"
                                              style="background-color: {{ $this->getStageColor($job->recruitment_stage) }}12; color: {{ $this->getStageColor($job->recruitment_stage) }}; border: 1px solid {{ $this->getStageColor($job->recruitment_stage) }}20;">
                                            {{ $job->recruitment_stage }}
                                        </span>

                                        @if($job->isGhosted())
                                            <button wire:click.stop="generateFollowUp({{ $job->id }})"
                                                    class="px-2 py-0.5 bg-rose-500 hover:bg-rose-600 text-white rounded text-[9px] font-bold uppercase tracking-wider transition-colors shadow-xs shrink-0">
                                                <span>Tanya Kabar</span>
                                            </button>
                                        @endif
                                    </div>
                                @elseif($job->isGhosted())
                                    <div class="mt-2.5 flex justify-end">
                                        <button wire:click.stop="generateFollowUp({{ $job->id }})"
                                                class="px-2 py-0.5 bg-rose-500 hover:bg-rose-600 text-white rounded text-[9px] font-bold uppercase tracking-wider transition-colors shadow-xs shrink-0">
                                            <span>Tanya Kabar</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                             @empty
                             <div class="flex flex-col items-center justify-center py-8 bg-white/30 rounded-lg border border-dashed border-zinc-200/60 transition-all duration-150">
                                 <div class="w-8 h-8 bg-zinc-100/60 rounded-md flex items-center justify-center text-zinc-400 mb-2 shadow-3xs">
                                     <i class="ph ph-folder text-sm"></i>
                                 </div>
                                 <span class="text-[9.5px] font-bold text-zinc-400 uppercase tracking-wider">No Applications</span>
                             </div>
                             @endforelse
                        </div> <!-- end wire:loading.remove -->
                    </div> <!-- end Job Cards Container -->
                </div> <!-- end column wrapper -->
            @endforeach
        </div>
    </div>

    <!-- Drag & Drop Scripts -->
    <script>
        function allowDrop(ev) {
            ev.preventDefault();
            const container = ev.target.closest('[data-status-container]');
            if (container) container.classList.add('bg-primary-50/30', 'border-primary-200/80');
        }

        function drag(ev, jobId) {
            ev.dataTransfer.setData("jobId", jobId);
            ev.target.classList.add('opacity-40');
        }

        document.addEventListener('dragend', function(ev) {
            ev.target.classList.remove('opacity-40');
        });

        function drop(ev, newStatus) {
            ev.preventDefault();
            const jobId = ev.dataTransfer.getData("jobId");
            const container = ev.target.closest('[data-status-container]');
            if (container) container.classList.remove('bg-primary-50/30', 'border-primary-200/80');
            @this.call('updateStatus', jobId, newStatus);
        }

        document.addEventListener('dragleave', function(ev) {
            const container = ev.target.closest('[data-status-container]');
            if (container) container.classList.remove('bg-primary-50/30', 'border-primary-200/80');
        });
    </script>

    <!-- AI Follow Up Modal -->
    <template x-teleport="body">
        <div wire:ignore.self x-data="{ show: @entangle('showFollowUpModal') }" 
             x-show="show" 
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[99999] flex items-center justify-center bg-zinc-950/40 p-4" style="display: none;">
             
            <div x-show="show"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-98 translateY(6px)"
                 x-transition:enter-end="opacity-100 scale-100 translateY(0)"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 scale-100 translateY(0)"
                 x-transition:leave-end="opacity-0 scale-98 translateY(6px)"
                 class="bg-white rounded-lg shadow-xl w-full max-w-xl max-h-[90vh] overflow-hidden border border-zinc-200 flex flex-col relative" @click.stop>
                 
                <!-- Header -->
                <div class="px-4 py-3.5 border-b border-zinc-150/60 flex items-center justify-between bg-white sticky top-0 z-10 shrink-0">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded bg-zinc-50 border border-zinc-200/60 flex items-center justify-center p-1">
                            <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <h2 class="text-xs font-bold text-zinc-800">Draft Follow-Up Email</h2>
                            <p class="text-[8px] font-bold text-zinc-400 uppercase tracking-widest mt-0.5">Generated by TraKerja AI</p>
                        </div>
                    </div>
                    <button wire:click="closeFollowUpModal" class="w-7 h-7 flex items-center justify-center bg-zinc-50 text-zinc-400 hover:text-rose-500 hover:bg-rose-50 rounded transition-colors">
                        <i class="ph ph-x text-sm"></i>
                    </button>
                </div>

                <!-- Content -->
                <div class="p-4 overflow-y-auto bg-zinc-50/30 flex-1">
                    <div>
                        <label class="block text-[8px] font-bold text-zinc-400 uppercase tracking-widest mb-1 ml-0.5">Draft Email</label>
                        <textarea wire:model="followUpDraft" rows="10" class="block w-full px-3 py-2 bg-white border border-zinc-200 rounded-md text-xs font-medium leading-relaxed outline-none resize-none transition-all focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 shadow-3xs"></textarea>
                        
                        <div class="mt-3 flex items-start gap-2 text-primary-700 bg-primary-50/30 p-3 rounded-md border border-primary-100/60">
                            <i class="ph ph-info text-base shrink-0"></i>
                            <p class="text-[10px] font-medium leading-relaxed">Anda bisa mengedit teks di atas sebelum menyalinnya. Pastikan Anda mengganti placeholder (seperti nama HRD atau perusahaan) jika AI tidak mengetahuinya secara pasti.</p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-4 py-3 border-t border-zinc-150/60 bg-white flex items-center justify-end gap-2 sticky bottom-0 z-10 shrink-0">
                    <button wire:click="closeFollowUpModal" class="px-3.5 py-1.5 rounded-md text-[9px] font-bold text-zinc-400 hover:text-zinc-650 uppercase tracking-wider transition-colors">Batal</button>
                    <button type="button" @click="navigator.clipboard.writeText($wire.followUpDraft); window.showToast('success', 'Disalin!', 'Draft email berhasil disalin ke clipboard')" class="px-3 py-1.5 bg-zinc-100 hover:bg-zinc-200 text-zinc-600 text-[9px] font-bold uppercase tracking-wider transition-colors flex items-center gap-1.5 rounded-md">
                        <i class="ph ph-copy text-xs"></i>
                        <span>Salin Teks</span>
                    </button>
                    <a :href="'mailto:hrd@company.com?subject=Application Status Update&body=' + encodeURIComponent($wire.followUpDraft)" target="_blank" class="px-3 py-1.5 bg-primary-600 hover:bg-primary-700 text-white text-[9px] font-bold uppercase tracking-wider transition-colors flex items-center gap-1.5 rounded-md shadow-sm">
                        <i class="ph ph-envelope-simple text-xs"></i>
                        <span>Kirim Email</span>
                    </a>
                </div>
            </div>
        </div>
    </template>
</div>