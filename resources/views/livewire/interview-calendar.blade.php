<div class="w-full">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Controls Bar: Compact & Premium -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-6 mb-8">
        <!-- Left: Month Navigation -->
        <div class="flex items-center gap-4 bg-white border border-slate-200/60 p-2 rounded-[1.5rem] shadow-[inset_0_1px_2px_rgba(255,255,255,0.8),0_4px_6px_-1px_rgba(0,0,0,0.02)]">
            <button wire:click="previousMonth" class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-slate-50 text-slate-500 transition-all active:scale-90">
                <i class="ph-bold ph-caret-left"></i>
            </button>
            <h2 class="text-base font-black text-slate-900 min-w-[150px] text-center tracking-tight">{{ $monthName }}</h2>
            <button wire:click="nextMonth" class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-slate-50 text-slate-500 transition-all active:scale-90">
                <i class="ph-bold ph-caret-right"></i>
            </button>
            <div class="w-px h-6 bg-slate-100 mx-1"></div>
            <button wire:click="goToToday" class="px-6 py-2 text-[10px] font-black text-primary-600 hover:bg-primary-50 rounded-xl transition-all uppercase tracking-widest">
                TODAY
            </button>
        </div>

        <!-- Right: View Toggle & Filter -->
        <div class="flex items-center gap-4">
            <!-- View Mode Switcher -->
            <div class="flex p-1.5 bg-white border border-slate-200/60 rounded-[1.5rem] shadow-sm">
                <button wire:click="$set('viewMode', 'month')" 
                    class="w-11 h-11 flex items-center justify-center rounded-xl transition-all duration-500 {{ $viewMode === 'month' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200 scale-105' : 'text-slate-400 hover:text-primary-600 hover:bg-slate-50' }}">
                    <i class="ph-bold ph-calendar-blank text-lg"></i>
                </button>
                <button wire:click="$set('viewMode', 'list')" 
                    class="w-11 h-11 flex items-center justify-center rounded-xl transition-all duration-500 {{ $viewMode === 'list' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200 scale-105' : 'text-slate-400 hover:text-primary-600 hover:bg-slate-50' }}">
                    <i class="ph-bold ph-list-bullets text-lg"></i>
                </button>
            </div>

            <!-- Filter Type -->
            <div class="relative group">
                <select wire:model.live="filterType" 
                        class="appearance-none pl-12 pr-12 py-3.5 bg-white border border-slate-200/60 rounded-[1.5rem] text-[10px] font-black text-slate-600 focus:ring-4 focus:ring-primary-500/5 focus:border-primary-500 transition-all cursor-pointer shadow-sm min-w-[200px] uppercase tracking-widest">
                    <option value="all">ALL INTERVIEWS</option>
                    <option value="HR - Interview">HR INTERVIEW</option>
                    <option value="User - Interview">USER INTERVIEW</option>
                </select>
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-primary-600">
                    <i class="ph-fill ph-funnel text-base"></i>
                </div>
                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 group-hover:translate-y-[-40%] transition-transform">
                    <i class="ph-bold ph-caret-down text-xs"></i>
                </div>
            </div>
        </div>
    </div>

    @if($viewMode === 'month')
        <!-- Calendar View: Grid Modern -->
        <div class="bg-white rounded-[2.5rem] shadow-[inset_0_1px_2px_rgba(255,255,255,0.8),0_10px_30px_-5px_rgba(0,0,0,0.04)] border border-slate-200/60 overflow-hidden">
            <!-- Day Headers -->
            <div class="grid grid-cols-7 bg-slate-50/50 border-b border-slate-100">
                @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                    <div class="px-2 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-[2px]">
                        {{ $day }}
                    </div>
                @endforeach
            </div>

            <!-- Calendar Grid -->
            <div class="grid grid-cols-7 divide-x divide-slate-100">
                @foreach($calendarDays as $day)
                    <div class="min-h-[140px] p-3 border-b border-slate-100 transition-colors {{ $day['isCurrentMonth'] ? 'bg-white' : 'bg-slate-50/30 opacity-40' }} {{ $day['isToday'] ? 'bg-primary-50/30' : '' }} group">
                        <!-- Date Number -->
                        <div class="flex justify-between items-start mb-3">
                            <span class="inline-flex items-center justify-center w-8 h-8 text-sm font-black transition-all rounded-xl 
                                {{ $day['isToday'] ? 'bg-primary-600 text-white shadow-lg shadow-primary-100' : ($day['isCurrentMonth'] ? 'text-slate-900 group-hover:text-primary-600' : 'text-slate-300') }}">
                                {{ $day['date']->format('j') }}
                            </span>
                        </div>

                        <!-- Interviews on this day -->
                        <div class="space-y-1.5">
                            @foreach($day['interviews'] as $interview)
                                    <button wire:click="viewInterviewDetails({{ $interview['id'] }})" 
                                         class="w-full text-left p-2 rounded-xl text-[10px] font-black transition-all hover:scale-[1.02] active:scale-95 shadow-sm border
                                                {{ in_array($interview['recruitment_stage'], ['HR - Interview', 'Psychotest']) ? 'bg-blue-50 text-blue-700 border-blue-100 hover:bg-blue-100' : '' }}
                                                {{ in_array($interview['recruitment_stage'], ['User - Interview', 'Presentation Round']) ? 'bg-emerald-50 text-emerald-700 border-emerald-100 hover:bg-emerald-100' : '' }}
                                                {{ in_array($interview['recruitment_stage'], ['Assessment Test', 'LGD']) ? 'bg-purple-50 text-purple-700 border-purple-100 hover:bg-purple-100' : '' }}">
                                    <div class="truncate uppercase tracking-tight">{{ $interview['company_name'] }}</div>
                                    <div class="mt-0.5 opacity-60 flex items-center gap-1">
                                        <i class="ph-bold ph-clock"></i>
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
        <div class="space-y-4">
            @forelse($this->allInterviewsList as $interview)
                <div wire:click="viewInterviewDetails({{ $interview->id }})" 
                     class="bg-white p-5 rounded-2xl border border-slate-200/60 hover:border-primary-600/30 hover:shadow-md transition-all cursor-pointer group flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 
                             {{ $interview->recruitment_stage === 'HR - Interview' ? 'bg-blue-50 text-blue-600' : 'bg-emerald-50 text-emerald-600' }}">
                            <i class="ph-duotone {{ $interview->recruitment_stage === 'HR - Interview' ? 'ph-users' : 'ph-user-focus' }} text-2xl"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h3 class="text-lg font-black text-slate-900 tracking-tight">{{ $interview->company_name }}</h3>
                                <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border
                                             {{ in_array($interview->recruitment_stage, ['HR - Interview', 'Psychotest']) ? 'bg-blue-100 text-blue-700 border-blue-200' : '' }}
                                             {{ in_array($interview->recruitment_stage, ['User - Interview', 'Presentation Round']) ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : '' }}
                                             {{ in_array($interview->recruitment_stage, ['Assessment Test', 'LGD']) ? 'bg-purple-100 text-purple-700 border-purple-200' : '' }}">
                                    {{ $interview->recruitment_stage }}
                                </span>
                            </div>
                            <p class="text-sm font-bold text-slate-500">{{ $interview->position }}</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-6">
                        <div class="flex flex-col text-right">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Schedule</span>
                            <span class="text-sm font-black text-slate-700">{{ $interview->interview_date->format('D, d M • H:i') }}</span>
                        </div>
                        <div class="flex flex-col text-right min-w-[120px]">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Countdown</span>
                            @if($interview->interview_date->isFuture())
                                <span class="text-sm font-black text-primary-600">{{ $interview->interview_date->diffForHumans() }}</span>
                            @else
                                <span class="text-sm font-black text-slate-400 italic">Past Event</span>
                            @endif
                        </div>
                        <i class="ph-bold ph-arrow-right text-slate-300 group-hover:text-primary-600 group-hover:translate-x-1 transition-all"></i>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-3xl p-20 text-center border border-slate-200/60 shadow-sm">
                    <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center text-slate-300 mx-auto mb-6">
                        <i class="ph-duotone ph-calendar-blank text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-2">No Interviews Found</h3>
                    <p class="text-slate-500 font-medium">Update your job applications to "Interview" stage to see them here.</p>
                </div>
            @endforelse
        </div>
    @endif
    {{-- Modal is rendered in calendar.blade.php as pure Alpine, outside this component --}}
</div>
