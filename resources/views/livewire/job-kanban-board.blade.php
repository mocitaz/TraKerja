<div>
    <div class="space-y-6">
        <!-- Search & Filter Bar (Identical to Table) -->
        <div class="flex flex-col lg:flex-row items-center justify-between gap-4 p-4 bg-white border border-slate-200 rounded-2xl shadow-sm">
            <div class="relative w-full lg:w-80 group">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search in kanban..." class="block w-full pl-10 pr-4 py-2 bg-slate-50 border-none rounded-xl text-xs font-bold transition-all focus:ring-4 focus:ring-indigo-600/5">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:flex sm:items-center gap-2 w-full lg:w-auto">
                <select wire:model.live="platformFilter" class="w-full bg-slate-50 border-none rounded-lg text-[10px] font-black text-slate-600 focus:ring-4 focus:ring-indigo-600/5">
                    <option value="">PLATFORM</option>
                    @foreach($platformOptions as $platform) <option value="{{ $platform }}">{{ strtoupper($platform) }}</option> @endforeach
                </select>
                <button wire:click="toggleArchived" class="w-full sm:w-auto px-4 py-2.5 sm:py-2 rounded-lg text-[10px] font-black uppercase tracking-widest border transition-all {{ $showArchived ? 'bg-indigo-600 border-indigo-600 text-white shadow-lg' : 'bg-white border-slate-200 text-slate-500 hover:border-indigo-600' }}">
                    {{ $showArchived ? 'Active' : 'Archive' }}
                </button>
            </div>
        </div>

        <!-- Clean 3-Column Kanban Board -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full items-start">
            @foreach($statuses as $status)
                <div class="flex flex-col bg-slate-50/50 rounded-[2.5rem] p-5 border border-slate-200/60 shadow-[inset_0_1px_2px_rgba(255,255,255,0.8),0_4px_6px_-1px_rgba(0,0,0,0.02)]"
                     ondragover="allowDrop(event)" 
                     ondrop="drop(event, '{{ $status->name }}')"
                     data-status-container="{{ $status->name }}">
                    
                    <!-- Column Header -->
                    <div class="flex items-center justify-between mb-6 px-3">
                        <div class="flex items-center gap-3">
                            <div class="w-2.5 h-2.5 rounded-full shadow-lg" style="background-color: {{ $status->color_code }}; shadow: 0 0 12px {{ $status->color_code }}60;"></div>
                            <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.25em]">{{ $status->name }}</h3>
                        </div>
                        <span class="text-[9px] font-black bg-white text-slate-400 px-3 py-1.5 rounded-full border border-slate-100 shadow-sm">
                            {{ $status->jobApplications->count() }}
                        </span>
                    </div>

                    <!-- Job Cards Container -->
                    <div class="space-y-4 min-h-[200px]">
                        {{-- Skeleton Loading Cards --}}
                        <div wire:loading.class.remove="hidden" class="hidden space-y-4">
                            @for($i = 0; $i < 2; $i++)
                                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-10 h-10 rounded-2xl bg-slate-100 skeleton"></div>
                                        <div class="space-y-2 flex-1">
                                            <div class="h-3 w-3/4 bg-slate-100 rounded skeleton"></div>
                                            <div class="h-2 w-1/2 bg-slate-100 rounded skeleton"></div>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center pt-4 border-t border-slate-50">
                                        <div class="h-5 w-16 bg-slate-100 rounded skeleton"></div>
                                        <div class="h-3 w-10 bg-slate-100 rounded skeleton"></div>
                                    </div>
                                </div>
                            @endfor
                        </div>

                        <div wire:loading.remove class="space-y-4">
                            @forelse($status->jobApplications as $job)
                            <div class="group bg-white rounded-3xl p-5 border border-slate-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)] hover:border-indigo-400 transition-all duration-500 cursor-grab active:cursor-grabbing hover:-translate-y-1 {{ $job->is_pinned ? 'ring-2 ring-indigo-500/10 border-indigo-400' : '' }}"
                                 draggable="true"
                                 id="job-card-{{ $job->id }}"
                                 ondragstart="drag(event, '{{ $job->id }}')"
                                 onclick="window.location.href='{{ route('jobs.show', $job) }}'">
                                
                                <div class="flex items-start justify-between gap-3 mb-4">
                                    <div class="flex items-center gap-4 min-w-0">
                                        <div class="w-10 h-10 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-indigo-600 font-black text-sm shrink-0 shadow-sm group-hover:bg-indigo-50 transition-colors">
                                            {{ substr($job->company_name, 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="text-sm font-black text-slate-900 group-hover:text-indigo-600 transition-colors leading-tight truncate">{{ $job->company_name }}</h4>
                                            <p class="text-[10px] font-bold text-slate-400 italic mt-1 truncate tracking-tight">{{ $job->position }}</p>
                                        </div>
                                    </div>
                                    @if($job->is_pinned)
                                        <div class="w-6 h-6 bg-amber-50 rounded-lg flex items-center justify-center text-amber-500">
                                            <i class="ph-fill ph-push-pin text-sm"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                                    <div class="flex items-center gap-2">
                                        <button wire:click.stop="edit('{{ $job->id }}')" class="w-8 h-8 flex items-center justify-center bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition-all duration-300">
                                            <i class="ph ph-pencil-simple text-sm"></i>
                                        </button>
                                        <button wire:click.stop="confirmDelete('{{ $job->id }}')" class="w-8 h-8 flex items-center justify-center bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition-all duration-300">
                                            <i class="ph ph-trash text-sm"></i>
                                        </button>
                                    </div>
                                    <div class="flex items-center gap-2 px-3 py-1.5 bg-slate-50 rounded-xl">
                                        <i class="ph ph-calendar text-slate-400 text-xs"></i>
                                        <span class="text-[9px] font-black text-slate-500 italic uppercase tracking-tighter">{{ $job->application_date->format('d M') }}</span>
                                    </div>
                                </div>
                                
                                @if($job->recruitment_stage)
                                    <div class="mt-4 flex">
                                        <span class="px-4 py-1.5 rounded-full text-[8px] font-black uppercase tracking-[0.1em] shadow-sm"
                                              style="background-color: {{ $this->getStageColor($job->recruitment_stage) }}10; color: {{ $this->getStageColor($job->recruitment_stage) }}; border: 1px solid {{ $this->getStageColor($job->recruitment_stage) }}20;">
                                            {{ $job->recruitment_stage }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center py-16 bg-white/40 rounded-3xl border-2 border-dashed border-slate-200/50">
                                <div class="w-12 h-12 bg-slate-100/50 rounded-2xl flex items-center justify-center text-slate-300 mb-3">
                                    <i class="ph-duotone ph-folder-open text-2xl"></i>
                                </div>
                                <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest italic">No Applications</span>
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
            if (container) container.classList.add('bg-indigo-50/50', 'border-indigo-200');
        }

        function drag(ev, jobId) {
            ev.dataTransfer.setData("jobId", jobId);
            ev.target.classList.add('opacity-40', 'scale-95');
        }

        function drop(ev, newStatus) {
            ev.preventDefault();
            const jobId = ev.dataTransfer.getData("jobId");
            const container = ev.target.closest('[data-status-container]');
            if (container) container.classList.remove('bg-indigo-50/50', 'border-indigo-200');
            @this.call('updateStatus', jobId, newStatus);
        }

        document.addEventListener('dragleave', function(ev) {
            const container = ev.target.closest('[data-status-container]');
            if (container) container.classList.remove('bg-indigo-50/50', 'border-indigo-200');
        });
    </script>
</div>