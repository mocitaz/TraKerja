<div>
    <div class="space-y-4">
        <!-- Search & Filter Bar (Compact, Notion-style) -->
        <div class="flex flex-col lg:flex-row items-center justify-between gap-3 p-3 bg-white border border-zinc-200/60 rounded-lg shadow-3xs">
            <div class="relative w-full lg:w-72 group">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search applications..." 
                       class="block w-full pl-9 pr-4 h-[30px] bg-zinc-50/50 border border-zinc-200 rounded-md text-[11px] font-semibold tracking-tight transition-all focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white outline-none">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-400">
                    <i class="ph ph-magnifying-glass text-sm"></i>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:flex sm:items-center gap-2 w-full lg:w-auto">
                <select wire:model.live="perPage" class="w-full sm:w-auto bg-zinc-50/50 border border-zinc-200 rounded-md text-[11px] font-semibold text-zinc-600 h-[30px] py-0 pl-2.5 pr-8 focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white outline-none cursor-pointer">
                    <option value="30">30 / page</option>
                    <option value="50">50 / page</option>
                    <option value="100">100 / page</option>
                    <option value="150">150 / page</option>
                </select>
                <select wire:model.live="statusFilter" class="w-full bg-zinc-50/50 border border-zinc-200 rounded-md text-[11px] font-semibold text-zinc-600 h-[30px] py-0 pl-2.5 pr-8 focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white outline-none cursor-pointer">
                    <option value="">STATUS</option>
                    @foreach($statusOptions as $status) <option value="{{ $status }}">{{ strtoupper($status) }}</option> @endforeach
                </select>
                <select wire:model.live="platformFilter" class="w-full bg-zinc-50/50 border border-zinc-200 rounded-md text-[11px] font-semibold text-zinc-600 h-[30px] py-0 pl-2.5 pr-8 focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white outline-none cursor-pointer">
                    <option value="">PLATFORM</option>
                    @foreach($platformOptions as $platform) <option value="{{ $platform }}">{{ strtoupper($platform) }}</option> @endforeach
                </select>
                <button wire:click="toggleArchived" class="col-span-2 sm:col-span-1 w-full sm:w-auto px-3 h-[30px] rounded-md text-[11px] font-bold border transition-all duration-150 active:scale-97 hover:shadow-2xs focus:outline-none flex items-center justify-center gap-1 {{ $showArchived ? 'bg-primary-50 hover:bg-primary-100 text-zinc-800 border-primary-200/60 shadow-3xs' : 'bg-white border-zinc-200 text-zinc-500 hover:bg-zinc-50 hover:text-zinc-800 shadow-3xs' }}">
                    <i class="ph ph-archive text-[13px]"></i>
                    <span>{{ $showArchived ? 'Active' : 'Archive' }}</span>
                </button>
            </div>
        </div>

        <!-- Mobile Card View (Visible only on sm & md screens) -->
        <div class="block lg:hidden space-y-3">
            {{-- Skeleton Loading Cards (Mobile) --}}
            <div wire:loading.class.remove="hidden" class="hidden space-y-3">
                @for($i = 0; $i < 3; $i++)
                    <div class="bg-white border border-zinc-200/60 rounded-lg p-3.5 shadow-3xs">
                        <div class="flex justify-between items-start gap-4 mb-4">
                            <div class="flex items-center gap-3 w-full">
                                <div class="w-8 h-8 rounded bg-zinc-100 skeleton shrink-0"></div>
                                <div class="w-full space-y-2">
                                    <div class="h-3 w-3/4 bg-zinc-100 rounded skeleton"></div>
                                    <div class="h-2.5 w-1/2 bg-zinc-100 rounded skeleton"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>

            <div wire:loading.remove class="space-y-3">
                @forelse($jobApplications as $index => $job)
                    <div class="bg-white border rounded-lg p-3.5 shadow-3xs hover:border-zinc-300 transition-all cursor-pointer relative group {{ $job->isGhosted() ? 'border-amber-400 bg-amber-50/10' : 'border-zinc-200/60' }}" onclick="window.location.href='{{ route('jobs.show', $job) }}'">
                    
                        @if($job->isGhosted())
                            <div class="absolute -top-2 -right-2 w-6 h-6 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center shadow-xs border border-rose-100 z-10" title="Lebih dari 14 hari tanpa kabar!">
                                <i class="ph ph-warning-circle text-xs"></i>
                            </div>
                        @elseif($job->is_pinned)
                            <div class="absolute -top-2 -right-2 w-6 h-6 bg-amber-400 rounded-full flex items-center justify-center shadow-xs border border-amber-300 z-10">
                                <i class="ph-fill ph-push-pin text-white text-[9px]"></i>
                            </div>
                        @endif

                        <!-- Card Header -->
                        <div class="flex justify-between items-start gap-3 mb-3">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-8 h-8 bg-zinc-50 rounded border border-zinc-200 flex items-center justify-center text-zinc-500 font-bold text-xs shrink-0 uppercase">
                                    {{ substr($job->company_name, 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-bold text-zinc-800 leading-tight mb-0.5 truncate group-hover:text-primary-600 transition-colors">{{ $job->company_name }}</h4>
                                    <p class="text-[10px] font-semibold text-zinc-450 truncate">{{ $job->position }}</p>
                                </div>
                            </div>
                            <span class="px-1.5 py-0.2 rounded text-[9px] font-bold uppercase shrink-0" style="background-color: {{ $this->getStatusColor($job->application_status) }}12; color: {{ $this->getStatusColor($job->application_status) }}; border: 1px solid {{ $this->getStatusColor($job->application_status) }}20;">
                                {{ $job->application_status }}
                            </span>
                        </div>

                        <!-- Card Details Grid -->
                        <div class="grid grid-cols-2 gap-2 mb-3 p-2.5 bg-zinc-50/40 rounded border border-zinc-150/60">
                            <div class="flex flex-col">
                                <span class="text-[8px] font-bold text-zinc-400 uppercase tracking-wider mb-0.5">Platform</span>
                                <span class="text-[10px] font-bold text-zinc-650 truncate uppercase">{{ $job->platform }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[8px] font-bold text-zinc-400 uppercase tracking-wider mb-0.5">Stage</span>
                                <span class="text-[10px] font-bold" style="color: {{ $this->getStageColor($job->recruitment_stage) }};">
                                    {{ $job->recruitment_stage ?? 'Applied' }}
                                </span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[8px] font-bold text-zinc-400 uppercase tracking-wider mb-0.5">Applied Date</span>
                                <span class="text-[10px] font-semibold text-zinc-500">{{ $job->application_date->format('d M Y') }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[8px] font-bold text-zinc-400 uppercase tracking-wider mb-0.5">Interview</span>
                                @if($job->interview_date)
                                    <span class="text-[10px] font-bold text-primary-600">{{ $job->interview_date->format('d/m H:i') }}</span>
                                @else
                                    <span class="text-[10px] font-semibold text-zinc-350 italic">N/A</span>
                                @endif
                            </div>
                        </div>

                        <!-- Card Actions -->
                        <div class="flex justify-between items-center pt-2.5 border-t border-zinc-150/60" onclick="event.stopPropagation();">
                            <div class="flex items-center gap-1 bg-zinc-50 rounded px-2 py-0.5 border border-zinc-200">
                                <i class="ph ph-map-pin text-zinc-400 text-[10px]"></i>
                                <span class="text-[9px] font-bold text-zinc-500 uppercase truncate max-w-[120px]">{{ $job->location }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                @if($job->isGhosted())
                                    <button wire:click.stop="generateFollowUp({{ $job->id }})"
                                            class="px-2 py-1 bg-rose-500 hover:bg-rose-600 text-white rounded text-[9px] font-bold uppercase tracking-wider transition-colors flex items-center gap-1 shadow-xs">
                                        <i class="ph ph-paper-plane-tilt"></i>
                                        <span>Tanya Kabar</span>
                                    </button>
                                @endif
                                <button wire:click.stop="edit({{ $job->id }})" class="w-6.5 h-6.5 flex items-center justify-center text-zinc-400 hover:text-indigo-600 hover:bg-zinc-50 rounded border border-zinc-200 transition-colors">
                                    <i class="ph ph-pencil-simple text-xs"></i>
                                </button>
                                <button wire:click="confirmDelete({{ $job->id }})" class="w-6.5 h-6.5 flex items-center justify-center text-zinc-400 hover:text-rose-600 hover:bg-rose-50 rounded border border-zinc-200 transition-colors">
                                    <i class="ph ph-trash text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white border border-zinc-200/60 rounded-lg p-8 text-center flex flex-col items-center justify-center border-dashed">
                        <div class="w-10 h-10 bg-zinc-50 rounded-md flex items-center justify-center mb-2.5 text-zinc-350">
                            <i class="ph ph-folder-open text-xl"></i>
                        </div>
                        <span class="text-xs font-semibold text-zinc-450 italic">No applications found...</span>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Desktop Full Info Compact Table (Visible only on lg screens) -->
        <div class="hidden lg:block bg-white border border-zinc-200/60 rounded-lg shadow-3xs overflow-hidden w-full">
            <div class="overflow-x-auto w-full custom-scrollbar">
                <table class="min-w-full divide-y divide-zinc-150/40">
                    <thead class="bg-zinc-55/30">
                        <tr>
                            <th class="px-3.5 py-2 text-center text-[10px] font-bold text-zinc-450 uppercase tracking-wider whitespace-nowrap">#</th>
                            <th class="px-3.5 py-2 text-left text-[10px] font-bold text-zinc-450 uppercase tracking-wider whitespace-nowrap">Company</th>
                            <th class="px-3.5 py-2 text-left text-[10px] font-bold text-zinc-450 uppercase tracking-wider whitespace-nowrap">Position</th>
                            <th class="px-3.5 py-2 text-left text-[10px] font-bold text-zinc-450 uppercase tracking-wider whitespace-nowrap">Location</th>
                            <th class="px-3.5 py-2 text-center text-[10px] font-bold text-zinc-450 uppercase tracking-wider whitespace-nowrap">Platform</th>
                            <th class="px-3.5 py-2 text-center text-[10px] font-bold text-zinc-450 uppercase tracking-wider whitespace-nowrap">Status</th>
                            <th class="px-3.5 py-2 text-center text-[10px] font-bold text-zinc-450 uppercase tracking-wider whitespace-nowrap">Stage</th>
                            <th class="px-3.5 py-2 text-center text-[10px] font-bold text-zinc-450 uppercase tracking-wider whitespace-nowrap">Level</th>
                            <th class="px-3.5 py-2 text-center text-[10px] font-bold text-zinc-450 uppercase tracking-wider whitespace-nowrap">Applied</th>
                            <th class="px-3.5 py-2 text-center text-[10px] font-bold text-zinc-450 uppercase tracking-wider whitespace-nowrap">Interview</th>
                            <th class="px-3.5 py-2 text-right text-[10px] font-bold text-zinc-450 uppercase tracking-wider whitespace-nowrap pr-5">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-150/40 bg-white">
                        {{-- Skeleton Loading Rows --}}
                        @for($i = 0; $i < 4; $i++)
                            <tr wire:loading.class.remove="hidden" class="hidden">
                                <td class="px-3 py-2"><div class="h-3 w-4 bg-zinc-100 rounded mx-auto skeleton"></div></td>
                                <td class="px-3 py-2"><div class="h-4 w-24 bg-zinc-100 rounded skeleton"></div></td>
                                <td class="px-3 py-2"><div class="h-4 w-32 bg-zinc-100 rounded skeleton"></div></td>
                                <td class="px-3 py-2"><div class="h-3 w-16 bg-zinc-100 rounded skeleton"></div></td>
                                <td class="px-3 py-2 text-center"><div class="h-4 w-12 bg-zinc-100 rounded mx-auto skeleton"></div></td>
                                <td class="px-3 py-2 text-center"><div class="h-4 w-14 bg-zinc-100 rounded mx-auto skeleton"></div></td>
                                <td class="px-3 py-2 text-center"><div class="h-4 w-14 bg-zinc-100 rounded mx-auto skeleton"></div></td>
                                <td class="px-3 py-2 text-center"><div class="h-4 w-10 bg-zinc-100 rounded mx-auto skeleton"></div></td>
                                <td class="px-3 py-2 text-center"><div class="h-3.5 w-14 bg-zinc-100 rounded mx-auto skeleton"></div></td>
                                <td class="px-3 py-2 text-center"><div class="h-3.5 w-10 bg-zinc-100 rounded mx-auto skeleton"></div></td>
                                <td class="px-3 py-2 text-right"><div class="h-5 w-14 bg-zinc-100 rounded ml-auto skeleton"></div></td>
                            </tr>
                        @endfor

                        <tbody wire:loading.remove class="divide-y divide-zinc-150/40">
                            @forelse($jobApplications as $index => $job)
                            <tr class="transition-colors cursor-pointer group {{ $job->isGhosted() ? 'bg-amber-50/15 hover:bg-amber-50/25' : 'hover:bg-zinc-50/45' }}" onclick="window.location.href='{{ route('jobs.show', $job) }}'">
                                <td class="px-3.5 py-2 text-center text-xs font-semibold text-zinc-400 whitespace-nowrap">{{ ($jobApplications->firstItem() + $index) }}</td>
                                <td class="px-3.5 py-2 whitespace-nowrap">
                                    <span class="text-xs font-bold text-zinc-800 group-hover:text-primary-600 transition-colors">{{ $job->company_name }}</span>
                                </td>
                                <td class="px-3.5 py-2 whitespace-nowrap">
                                    <span class="text-xs font-medium text-zinc-650">{{ $job->position }}</span>
                                </td>
                                <td class="px-3.5 py-2 whitespace-nowrap">
                                    <span class="text-[11px] font-medium text-zinc-450">{{ $job->location }}</span>
                                </td>
                                <td class="px-3.5 py-2 text-center whitespace-nowrap">
                                    <span class="px-1.5 py-0.2 bg-zinc-100 rounded text-[9px] font-bold text-zinc-500 uppercase">{{ $job->platform }}</span>
                                </td>
                                <td class="px-3.5 py-2 text-center whitespace-nowrap">
                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-bold uppercase" style="background-color: {{ $this->getStatusColor($job->application_status) }}12; color: {{ $this->getStatusColor($job->application_status) }}; border: 1px solid {{ $this->getStatusColor($job->application_status) }}20;">
                                        {{ $job->application_status }}
                                    </span>
                                </td>
                                <td class="px-3.5 py-2 text-center whitespace-nowrap">
                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-bold uppercase" style="background-color: {{ $this->getStageColor($job->recruitment_stage) }}12; color: {{ $this->getStageColor($job->recruitment_stage) }}; border: 1px solid {{ $this->getStageColor($job->recruitment_stage) }}20;">
                                        {{ $job->recruitment_stage ?? 'Applied' }}
                                    </span>
                                </td>
                                <td class="px-3.5 py-2 text-center whitespace-nowrap">
                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-bold uppercase" style="background-color: {{ $this->getCareerLevelColor($job->career_level) }}12; color: {{ $this->getCareerLevelColor($job->career_level) }}; border: 1px solid {{ $this->getCareerLevelColor($job->career_level) }}20;">
                                        {{ $job->career_level ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-3.5 py-2 text-center whitespace-nowrap">
                                    <span class="text-xs font-semibold text-zinc-500 uppercase">{{ $job->application_date->format('d M y') }}</span>
                                </td>
                                <td class="px-3.5 py-2 text-center whitespace-nowrap">
                                    @if($job->interview_date)
                                        <span class="text-xs font-bold text-primary-600">{{ $job->interview_date->format('d/m H:i') }}</span>
                                    @else
                                        <span class="text-xs text-zinc-300 italic">-</span>
                                    @endif
                                </td>
                                <td class="px-3.5 py-2 text-right whitespace-nowrap" onclick="event.stopPropagation();">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if($job->isGhosted())
                                            <button wire:click.stop="generateFollowUp({{ $job->id }})"
                                                    class="px-2 py-1 bg-rose-500 hover:bg-rose-600 text-white rounded text-[9px] font-bold uppercase tracking-wider transition-colors flex items-center gap-1 shadow-xs">
                                                <i class="ph ph-paper-plane-tilt"></i>
                                                <span>Tanya Kabar</span>
                                            </button>
                                        @endif
                                        <button wire:click.stop="togglePin({{ $job->id }})" class="p-1 text-zinc-400 hover:text-amber-500 transition-colors">
                                            <i class="ph{{ $job->is_pinned ? '-fill text-amber-400' : ' ph-push-pin' }} text-xs"></i>
                                        </button>
                                        <button wire:click="edit({{ $job->id }})" class="p-1 text-zinc-400 hover:text-indigo-600 transition-colors">
                                            <i class="ph ph-pencil-simple text-xs"></i>
                                        </button>
                                        <button wire:click="confirmDelete({{ $job->id }})" class="p-1 text-zinc-400 hover:text-rose-600 transition-colors">
                                            <i class="ph ph-trash text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="11" class="py-8 text-center text-xs text-zinc-400 italic">Data not found...</td></tr>
                            @endforelse
                        </tbody>
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