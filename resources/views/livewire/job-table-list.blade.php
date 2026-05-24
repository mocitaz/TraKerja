<div>
    <div class="space-y-4">
        <!-- Search & Filter Bar (Compact) -->
        <div class="flex flex-col lg:flex-row items-center justify-between gap-4 p-5 bg-white border border-slate-200/60 rounded-[2rem] shadow-[inset_0_1px_2px_rgba(255,255,255,0.8),0_4px_6px_-1px_rgba(0,0,0,0.02)]">
            <div class="relative w-full lg:w-80 group">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search applications..." class="block w-full pl-11 pr-4 py-2.5 bg-slate-50 border-none rounded-2xl text-[11px] font-black tracking-tight transition-all focus:ring-4 focus:ring-indigo-600/5">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <i class="ph ph-magnifying-glass text-sm"></i>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:flex sm:items-center gap-3 w-full lg:w-auto">
                <select wire:model.live="perPage" class="w-full sm:w-auto bg-slate-50 border-none rounded-xl text-[10px] font-black text-slate-600 focus:ring-4 focus:ring-indigo-600/5 py-2.5 pl-4 pr-8">
                    <option value="30">30 / page</option>
                    <option value="50">50 / page</option>
                    <option value="100">100 / page</option>
                    <option value="150">150 / page</option>
                </select>
                <select wire:model.live="statusFilter" class="w-full bg-slate-50 border-none rounded-xl text-[10px] font-black text-slate-600 focus:ring-4 focus:ring-indigo-600/5 py-2.5">
                    <option value="">STATUS</option>
                    @foreach($statusOptions as $status) <option value="{{ $status }}">{{ strtoupper($status) }}</option> @endforeach
                </select>
                <select wire:model.live="platformFilter" class="w-full bg-slate-50 border-none rounded-xl text-[10px] font-black text-slate-600 focus:ring-4 focus:ring-indigo-600/5 py-2.5">
                    <option value="">PLATFORM</option>
                    @foreach($platformOptions as $platform) <option value="{{ $platform }}">{{ strtoupper($platform) }}</option> @endforeach
                </select>
                <button wire:click="toggleArchived" class="col-span-2 sm:col-span-1 w-full sm:w-auto px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] border transition-all duration-300 {{ $showArchived ? 'bg-indigo-600 border-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-white border-slate-200 text-slate-400 hover:border-indigo-600' }}">
                    {{ $showArchived ? 'Active' : 'Archive' }}
                </button>
            </div>
        </div>

        <!-- Mobile Card View (Visible only on sm & md screens) -->
        <div class="block lg:hidden space-y-6">
            {{-- Skeleton Loading Cards (Mobile) --}}
            <div wire:loading.class.remove="hidden" class="hidden space-y-6">
                @for($i = 0; $i < 3; $i++)
                    <div class="bg-white border border-slate-200/60 rounded-[2.5rem] p-6 shadow-sm">
                        <div class="flex justify-between items-start gap-4 mb-5">
                            <div class="flex items-center gap-4 w-full">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 skeleton shrink-0"></div>
                                <div class="w-full space-y-2">
                                    <div class="h-4 w-3/4 bg-slate-100 rounded skeleton"></div>
                                    <div class="h-3 w-1/2 bg-slate-100 rounded skeleton"></div>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-5 p-4 bg-slate-50/50 rounded-3xl border border-slate-100/50">
                            <div class="h-8 w-full bg-slate-100 rounded-lg skeleton"></div>
                            <div class="h-8 w-full bg-slate-100 rounded-lg skeleton"></div>
                            <div class="h-8 w-full bg-slate-100 rounded-lg skeleton"></div>
                            <div class="h-8 w-full bg-slate-100 rounded-lg skeleton"></div>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                            <div class="h-6 w-24 bg-slate-100 rounded-xl skeleton"></div>
                            <div class="h-8 w-20 bg-slate-100 rounded-xl skeleton"></div>
                        </div>
                    </div>
                @endfor
            </div>

            <div wire:loading.remove class="space-y-6">
                @forelse($jobApplications as $index => $job)
                    <div class="bg-white border rounded-[2.5rem] p-6 shadow-[inset_0_1px_2px_rgba(255,255,255,0.8),0_10px_20px_-5px_rgba(0,0,0,0.03)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)] transition-all duration-500 cursor-pointer relative group {{ $job->isGhosted() ? 'border-amber-400 bg-amber-50/30' : 'border-slate-200/60 hover:border-indigo-300' }}" onclick="window.location.href='{{ route('jobs.show', $job) }}'">
                    
                    @if($job->isGhosted())
                        <div class="absolute -top-3 -right-3 w-8 h-8 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center shadow-lg border-4 border-white z-10" title="Lebih dari 14 hari tanpa kabar!">
                            <i class="ph-bold ph-warning-circle text-xs animate-pulse"></i>
                        </div>
                    @elseif($job->is_pinned)
                        <div class="absolute -top-3 -right-3 w-8 h-8 bg-amber-400 rounded-full flex items-center justify-center shadow-lg border-4 border-white z-10">
                            <i class="ph-fill ph-push-pin text-white text-xs"></i>
                        </div>
                    @endif

                    <!-- Card Header -->
                    <div class="flex justify-between items-start gap-4 mb-5">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-indigo-600 font-black text-xl shrink-0 group-hover:bg-indigo-50 group-hover:border-indigo-100 transition-colors shadow-sm">
                                {{ substr($job->company_name, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-[15px] font-black text-slate-900 leading-tight mb-1 truncate group-hover:text-indigo-600 transition-colors">{{ $job->company_name }}</h4>
                                <p class="text-[11px] font-bold text-slate-400 italic tracking-tight truncate">{{ $job->position }}</p>
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-2 shrink-0">
                            <span class="px-3 py-1.5 rounded-full text-[8px] font-black uppercase tracking-[0.1em] shadow-sm" style="background-color: {{ $this->getStatusColor($job->application_status) }}15; color: {{ $this->getStatusColor($job->application_status) }}; border: 1px solid {{ $this->getStatusColor($job->application_status) }}30;">
                                {{ $job->application_status }}
                            </span>
                        </div>
                    </div>

                    <!-- Card Details Grid -->
                    <div class="grid grid-cols-2 gap-y-4 gap-x-5 mb-5 p-4 bg-slate-50/50 rounded-3xl border border-slate-100/50 shadow-inner">
                        <div class="flex flex-col">
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Platform</span>
                            <span class="text-[11px] font-black text-slate-600 truncate uppercase">{{ $job->platform }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Stage</span>
                            <span class="text-[11px] font-black tracking-tight" style="color: {{ $this->getStageColor($job->recruitment_stage) }};">
                                {{ $job->recruitment_stage ?? 'Applied' }}
                            </span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Applied Date</span>
                            <span class="text-[11px] font-black text-slate-500 italic">{{ $job->application_date->format('d M Y') }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Interview</span>
                            @if($job->interview_date)
                                <span class="text-[11px] font-black text-indigo-600">{{ $job->interview_date->format('d/m H:i') }}</span>
                            @else
                                <span class="text-[11px] font-bold text-slate-300 italic">N/A</span>
                            @endif
                        </div>
                    </div>

                    <!-- Card Actions -->
                    <div class="flex justify-between items-center pt-4 border-t border-slate-100" onclick="event.stopPropagation();">
                        <div class="flex items-center gap-1.5 bg-slate-50 rounded-xl px-3 py-1.5 border border-slate-100 shadow-inner">
                            <i class="ph ph-map-pin text-slate-400 text-xs"></i>
                            <span class="text-[9px] font-black text-slate-500 uppercase tracking-tight truncate max-w-[120px]">{{ $job->location }}</span>
                        </div>
                        <div class="flex gap-2">
                            @if($job->isGhosted())
                                <button wire:click.stop="openFollowUpModal('{{ $job->id }}')" class="px-3 py-1 bg-rose-500 hover:bg-rose-600 text-white rounded-xl text-[9px] font-black uppercase tracking-widest transition-colors flex items-center gap-1.5 shadow-sm">
                                    <i class="ph-bold ph-paper-plane-tilt"></i>
                                    Tanya Kabar
                                </button>
                            @endif
                            <button wire:click.stop="edit({{ $job->id }})" class="w-8 h-8 flex items-center justify-center bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition-all duration-300">
                                <i class="ph ph-pencil-simple text-sm"></i>
                            </button>
                            <button wire:click="confirmDelete({{ $job->id }})" class="w-8 h-8 flex items-center justify-center bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition-all duration-300">
                                <i class="ph ph-trash text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white border border-slate-200/60 rounded-[2.5rem] p-16 text-center flex flex-col items-center justify-center shadow-inner border-dashed">
                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-4 text-slate-300">
                        <i class="ph-duotone ph-folder-open text-4xl"></i>
                    </div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">No Data Found...</span>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Desktop Full Info Compact Table (Visible only on lg screens) -->
        <div class="hidden lg:block bg-white border border-slate-200/60 rounded-[2.5rem] shadow-[inset_0_1px_2px_rgba(255,255,255,0.8),0_10px_20px_-5px_rgba(0,0,0,0.03)] overflow-hidden w-full">
            <div class="overflow-x-auto w-full custom-scrollbar">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="px-5 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">#</th>
                            <th class="px-5 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Company</th>
                            <th class="px-5 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Position</th>
                            <th class="px-5 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Location</th>
                            <th class="px-5 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Platform</th>
                            <th class="px-5 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Status</th>
                            <th class="px-5 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Stage</th>
                            <th class="px-5 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Level</th>
                            <th class="px-5 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Applied</th>
                            <th class="px-5 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Interview</th>
                            <th class="px-5 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap pr-8">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        {{-- Skeleton Loading Rows --}}
                        @for($i = 0; $i < 5; $i++)
                            <tr wire:loading.class.remove="hidden" class="hidden border-b border-slate-50">
                                <td class="px-3 py-4"><div class="h-4 w-4 bg-slate-100 rounded mx-auto skeleton"></div></td>
                                <td class="px-3 py-4"><div class="h-5 w-32 bg-slate-100 rounded skeleton"></div></td>
                                <td class="px-3 py-4"><div class="h-4 w-40 bg-slate-100 rounded skeleton"></div></td>
                                <td class="px-3 py-4"><div class="h-3 w-24 bg-slate-100 rounded skeleton"></div></td>
                                <td class="px-3 py-4 text-center"><div class="h-4 w-16 bg-slate-100 rounded-full mx-auto skeleton"></div></td>
                                <td class="px-3 py-4 text-center"><div class="h-5 w-20 bg-slate-100 rounded-full mx-auto skeleton"></div></td>
                                <td class="px-3 py-4 text-center"><div class="h-5 w-20 bg-slate-100 rounded-full mx-auto skeleton"></div></td>
                                <td class="px-3 py-4 text-center"><div class="h-5 w-16 bg-slate-100 rounded-full mx-auto skeleton"></div></td>
                                <td class="px-3 py-4 text-center"><div class="h-4 w-20 bg-slate-100 rounded mx-auto skeleton"></div></td>
                                <td class="px-3 py-4 text-center"><div class="h-4 w-16 bg-slate-100 rounded mx-auto skeleton"></div></td>
                                <td class="px-3 py-4 text-right"><div class="h-6 w-20 bg-slate-100 rounded-lg ml-auto skeleton"></div></td>
                            </tr>
                        @endfor

                        <tbody wire:loading.remove>
                            @forelse($jobApplications as $index => $job)
                            <tr class="transition-all cursor-pointer group {{ $job->isGhosted() ? 'bg-amber-50/30 hover:bg-amber-50/50 border-l-4 border-amber-400' : 'hover:bg-slate-50' }}" onclick="window.location.href='{{ route('jobs.show', $job) }}'">
                                <td class="px-3 py-3 text-center text-xs font-bold text-slate-400 whitespace-nowrap">{{ ($jobApplications->firstItem() + $index) }}</td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <span class="text-sm font-black text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $job->company_name }}</span>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <span class="text-sm font-bold text-slate-700">{{ $job->position }}</span>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <span class="text-xs font-medium text-slate-500">{{ $job->location }}</span>
                                </td>
                                <td class="px-3 py-3 text-center whitespace-nowrap">
                                    <span class="px-2 py-0.5 bg-slate-100 rounded text-[10px] font-black text-slate-500 uppercase">{{ $job->platform }}</span>
                                </td>
                                <td class="px-3 py-3 text-center whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase" style="background-color: {{ $this->getStatusColor($job->application_status) }}15; color: {{ $this->getStatusColor($job->application_status) }};">
                                        {{ $job->application_status }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 text-center whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase" style="background-color: {{ $this->getStageColor($job->recruitment_stage) }}15; color: {{ $this->getStageColor($job->recruitment_stage) }};">
                                        {{ $job->recruitment_stage ?? 'Applied' }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 text-center whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase" style="background-color: {{ $this->getCareerLevelColor($job->career_level) }}15; color: {{ $this->getCareerLevelColor($job->career_level) }}; border: 1px solid {{ $this->getCareerLevelColor($job->career_level) }}30;">
                                        {{ $job->career_level ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 text-center whitespace-nowrap">
                                    <span class="text-xs font-bold text-slate-500 uppercase italic">{{ $job->application_date->format('d M y') }}</span>
                                </td>
                                <td class="px-3 py-3 text-center whitespace-nowrap">
                                    @if($job->interview_date)
                                        <span class="text-xs font-black text-indigo-600">{{ $job->interview_date->format('d/m H:i') }}</span>
                                    @else
                                        <span class="text-xs text-slate-300 italic">-</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-right whitespace-nowrap" onclick="event.stopPropagation();">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if($job->isGhosted())
                                            <button wire:click.stop="openFollowUpModal('{{ $job->id }}')" class="mr-2 px-3 py-1.5 bg-rose-500 hover:bg-rose-600 text-white rounded-lg text-[9px] font-black uppercase tracking-widest transition-colors shadow-sm flex items-center gap-1.5">
                                                <i class="ph-bold ph-paper-plane-tilt"></i>
                                                Tanya Kabar
                                            </button>
                                        @endif
                                        <button wire:click.stop="togglePin({{ $job->id }})" class="p-1.5 text-slate-400 hover:text-amber-500 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="{{ $job->is_pinned ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                                        </button>
                                        <button wire:click="edit({{ $job->id }})" class="p-1.5 text-slate-400 hover:text-indigo-600 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <button wire:click="confirmDelete({{ $job->id }})" class="p-1.5 text-slate-400 hover:text-rose-600 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="11" class="py-10 text-center text-xs text-slate-400 italic">Data not found...</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $jobApplications->links() }}
        </div>
    </div>

    <!-- AI Follow Up Modal -->
    <template x-teleport="body">
        <div wire:ignore.self x-data="{ show: @entangle('showFollowUpModal') }" 
             x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 backdrop-blur-none"
             x-transition:enter-end="opacity-100 backdrop-blur-sm"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 backdrop-blur-sm"
             x-transition:leave-end="opacity-0 backdrop-blur-none"
             class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 p-4 sm:p-6" style="display: none;">
             
            <div x-show="show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 class="bg-white rounded-[2rem] shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col relative border border-slate-100" @click.stop>
                 
                <!-- Header -->
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-white sticky top-0 z-10">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center">
                            <i class="ph-fill ph-paper-plane-tilt text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-black text-slate-800">Draft Follow-Up Email</h2>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Generated by TraKerja AI</p>
                        </div>
                    </div>
                    <button wire:click="closeFollowUpModal" class="w-8 h-8 flex items-center justify-center bg-slate-50 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-full transition-colors">
                        <i class="ph-bold ph-x text-sm"></i>
                    </button>
                </div>

                <!-- Content -->
                <div class="p-6 overflow-y-auto bg-slate-50/50 flex-1">
                    <div wire:loading wire:target="generateFollowUp" class="w-full flex flex-col items-center justify-center py-12">
                        <i class="ph-bold ph-spinner animate-spin text-4xl text-indigo-500 mb-4"></i>
                        <p class="text-xs font-bold text-slate-600">AI sedang memikirkan kata-kata terbaik...</p>
                    </div>

                    <div wire:loading.remove wire:target="generateFollowUp">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Draft Email</label>
                        <textarea wire:model="followUpDraft" rows="12" class="block w-full px-5 py-4 bg-white border border-slate-200 rounded-2xl text-xs font-medium leading-relaxed outline-none resize-none transition-all focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 shadow-sm"></textarea>
                        
                        <div class="mt-4 flex items-start gap-2 text-indigo-600 bg-indigo-50/50 p-3 rounded-xl border border-indigo-100">
                            <i class="ph-fill ph-info text-base mt-0.5"></i>
                            <p class="text-[10px] font-medium leading-relaxed">Anda bisa mengedit teks di atas sebelum menyalinnya. Pastikan Anda mengganti placeholder (seperti nama HRD atau perusahaan) jika AI tidak mengetahuinya secara pasti.</p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 border-t border-slate-100 bg-white flex items-center justify-end gap-3 sticky bottom-0 z-10">
                    <button wire:click="closeFollowUpModal" class="px-6 py-2.5 rounded-xl text-[10px] font-black text-slate-400 hover:text-slate-600 uppercase tracking-widest transition-colors">Batal</button>
                    <button type="button" @click="navigator.clipboard.writeText($wire.followUpDraft); $dispatch('showNotification', {type: 'success', title: 'Disalin!', message: 'Draft email berhasil disalin ke clipboard'})" class="px-6 py-2.5 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                        <i class="ph-bold ph-copy"></i>
                        Salin Teks
                    </button>
                    <a :href="'mailto:hrd@company.com?subject=Application Status Update&body=' + encodeURIComponent($wire.followUpDraft)" target="_blank" class="px-6 py-2.5 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-indigo-600/20 flex items-center gap-2">
                        <i class="ph-bold ph-envelope-simple"></i>
                        Kirim Email
                    </a>
                </div>
            </div>
        </div>
    </template>
</div>