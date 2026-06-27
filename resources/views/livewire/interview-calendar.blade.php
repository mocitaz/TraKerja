<div class="w-full">
    <!-- Controls Bar: Compact & Premium -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-3 mb-5">
        <!-- Left: Month Navigation -->
        <div class="flex items-center gap-1 bg-white border border-zinc-200/60 p-1 rounded-md shadow-3xs">
            <button wire:click="previousMonth" class="w-7 h-7 flex items-center justify-center rounded hover:bg-zinc-50 text-zinc-650 transition-colors focus:outline-none">
                <i class="ph ph-caret-left"></i>
            </button>
            <h2 class="text-xs font-bold text-zinc-800 min-w-[120px] text-center tracking-tight">{{ $monthName }}</h2>
            <button wire:click="nextMonth" class="w-7 h-7 flex items-center justify-center rounded hover:bg-zinc-50 text-zinc-650 transition-colors focus:outline-none">
                <i class="ph ph-caret-right"></i>
            </button>
            <div class="w-px h-4 bg-zinc-200 mx-1"></div>
            <button wire:click="goToToday" class="px-3.5 py-1.5 text-[9px] font-bold text-primary-600 hover:bg-zinc-50 rounded transition-colors uppercase tracking-wider focus:outline-none">
                Today
            </button>
        </div>

        <!-- Right: View Toggle & Filter -->
        <div class="flex items-center gap-2.5 w-full md:w-auto">
            <!-- View Mode Switcher -->
            <div class="flex p-0.5 bg-zinc-100 rounded-md shrink-0 shadow-3xs">
                <button wire:click="$set('viewMode', 'month')" 
                    class="px-3.5 py-1 text-xs font-semibold rounded-md {{ $viewMode === 'month' ? 'bg-primary-50 text-zinc-800 border border-primary-200/60 shadow-3xs font-bold' : 'text-zinc-500 hover:text-zinc-800 border border-transparent' }} transition-all focus:outline-none">
                    Month
                </button>
                <button wire:click="$set('viewMode', 'list')" 
                    class="px-3.5 py-1 text-xs font-semibold rounded-md {{ $viewMode === 'list' ? 'bg-primary-50 text-zinc-800 border border-primary-200/60 shadow-3xs font-bold' : 'text-zinc-500 hover:text-zinc-800 border border-transparent' }} transition-all focus:outline-none">
                    List
                </button>
            </div>

            <!-- Filter Type -->
            <div class="relative min-w-[160px] flex-1 md:flex-initial">
                <select wire:model.live="filterType" 
                        class="w-full pl-9 pr-8 py-1.5 bg-white border border-zinc-200 rounded-md text-xs font-semibold text-zinc-600 focus:outline-none focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 transition-colors cursor-pointer appearance-none">
                    <option value="all">All Interviews</option>
                    <option value="HR - Interview">HR Interview</option>
                    <option value="User - Interview">User Interview</option>
                </select>
                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 pointer-events-none">
                    <i class="ph ph-funnel text-xs"></i>
                </div>
                <div class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 pointer-events-none">
                    <i class="ph ph-caret-down text-[10px]"></i>
                </div>
            </div>
        </div>
    </div>

    @if($viewMode === 'month')
        <!-- Calendar View: Grid Modern -->
        <div class="bg-white rounded-lg border border-zinc-200/60 overflow-hidden shadow-3xs">
            <!-- Day Headers -->
            <div class="grid grid-cols-7 bg-zinc-55/30 border-b border-zinc-150/65">
                @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                    <div class="px-2 py-2 text-center text-[9px] font-bold text-zinc-450 uppercase tracking-widest">
                        {{ $day }}
                    </div>
                @endforeach
            </div>

            <!-- Calendar Grid -->
            <div class="grid grid-cols-7 divide-x divide-y divide-zinc-150/50 -mt-px -ml-px">
                @foreach($calendarDays as $day)
                    <div class="min-h-[80px] p-1.5 border-b border-zinc-100/50 transition-colors {{ $day['isCurrentMonth'] ? 'bg-white' : 'bg-zinc-50/15 opacity-50' }} {{ $day['isToday'] ? 'bg-primary-50/15' : '' }} group">
                        <!-- Date Number -->
                        <div class="flex justify-between items-start mb-1">
                            <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold rounded 
                                {{ $day['isToday'] ? 'bg-primary-600 text-white shadow-3xs' : ($day['isCurrentMonth'] ? 'text-zinc-800' : 'text-zinc-400') }}">
                                {{ $day['date']->format('j') }}
                            </span>
                        </div>

                        <!-- Interviews on this day -->
                        <div class="space-y-1">
                            @foreach($day['interviews'] as $interview)
                                <button wire:click="viewInterviewDetails({{ $interview['id'] }})" 
                                         class="w-full text-left px-1.5 py-0.5 rounded text-[8.5px] font-bold border transition-colors focus:outline-none
                                                {{ in_array($interview['recruitment_stage'], ['HR - Interview', 'Psychotest']) ? 'bg-blue-50/70 text-blue-700 border-blue-100 hover:bg-blue-100/50' : '' }}
                                                {{ in_array($interview['recruitment_stage'], ['User - Interview', 'Presentation Round']) ? 'bg-emerald-50/70 text-emerald-700 border-emerald-100 hover:bg-emerald-100/50' : '' }}
                                                {{ in_array($interview['recruitment_stage'], ['Assessment Test', 'LGD']) ? 'bg-purple-50/70 text-purple-700 border-purple-100 hover:bg-purple-100/50' : '' }}">
                                    <div class="truncate font-bold tracking-tight">{{ $interview['company_name'] }}</div>
                                    <div class="mt-0.5 opacity-70 flex items-center gap-0.5 text-[7.5px] font-bold">
                                        <i class="ph ph-clock"></i>
                                        {{ \Carbon\Carbon::parse($interview['interview_date'])->timezone('Asia/Jakarta')->format('H:i') }}
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <!-- List View: Modern Stack -->
        <div class="space-y-2.5">
            @forelse($this->allInterviewsList as $interview)
                <div wire:click="viewInterviewDetails({{ $interview->id }})" 
                     class="bg-white p-3 rounded-lg border border-zinc-200/60 hover:border-zinc-350 hover:shadow-3xs transition-colors cursor-pointer group flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded flex items-center justify-center shrink-0 border
                             {{ $interview->recruitment_stage === 'HR - Interview' ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-emerald-50 text-emerald-600 border-emerald-100' }}">
                            <i class="ph {{ $interview->recruitment_stage === 'HR - Interview' ? 'ph-users' : 'ph-user-focus' }} text-base"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-0.5">
                                <h3 class="text-xs font-bold text-zinc-800 tracking-tight">{{ $interview->company_name }}</h3>
                                <span class="px-1.5 py-0.2 rounded text-[8.5px] font-bold border
                                             {{ in_array($interview->recruitment_stage, ['HR - Interview', 'Psychotest']) ? 'bg-blue-50 text-blue-700 border-blue-100/50' : '' }}
                                             {{ in_array($interview->recruitment_stage, ['User - Interview', 'Presentation Round']) ? 'bg-emerald-50 text-emerald-700 border-emerald-100/50' : '' }}
                                             {{ in_array($interview->recruitment_stage, ['Assessment Test', 'LGD']) ? 'bg-purple-50 text-purple-700 border-purple-100/50' : '' }}">
                                    {{ $interview->recruitment_stage }}
                                </span>
                            </div>
                            <p class="text-[11px] text-zinc-500 font-medium leading-none">{{ $interview->position }}</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-5">
                        <div class="flex flex-col md:items-end">
                            <span class="text-[8px] font-bold text-zinc-400 uppercase tracking-widest leading-none mb-1">Schedule</span>
                            <span class="text-[11px] font-semibold text-zinc-700 leading-none">{{ $interview->interview_date->format('D, d M • H:i') }}</span>
                        </div>
                        <div class="flex flex-col md:items-end min-w-[100px]">
                            <span class="text-[8px] font-bold text-zinc-400 uppercase tracking-widest leading-none mb-1">Countdown</span>
                            @if($interview->interview_date->isFuture())
                                <span class="text-[11px] font-bold text-primary-650 /* [BRAND_PRIMARY] */ leading-none">{{ $interview->interview_date->diffForHumans() }}</span>
                            @else
                                <span class="text-[11px] text-zinc-450 italic leading-none">Past Event</span>
                            @endif
                        </div>
                        <i class="ph ph-arrow-right text-zinc-350 group-hover:text-zinc-700 group-hover:translate-x-0.5 transition-transform"></i>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg p-12 text-center border border-zinc-200/60 shadow-3xs border-dashed">
                    <div class="w-10 h-10 bg-zinc-50 border border-zinc-150 rounded-md flex items-center justify-center text-zinc-400 mx-auto mb-3">
                        <i class="ph ph-calendar text-xl"></i>
                    </div>
                    <h3 class="text-xs font-bold text-zinc-800 mb-1">No Interviews Found</h3>
                    <p class="text-[10px] text-zinc-500 font-medium">Update your job applications to "Interview" stage to see them here.</p>
                </div>
            @endforelse
        </div>
    @endif
</div>
