<div class="min-h-[80vh]">
    <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            {{-- Performance Hub - The Core Bento --}}
            <div class="lg:col-span-8 bg-white rounded-[2.5rem] border border-slate-200/60 shadow-sm overflow-hidden flex flex-col relative group">
                <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-100">
                            <i class="ph-fill ph-target text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-black text-slate-900 tracking-tight">Performance Cadence</h2>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Real-time target tracking</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-4 py-1.5 bg-indigo-50 text-indigo-600 rounded-full text-[9px] font-black uppercase tracking-widest border border-indigo-100">{{ $goalPeriod }}</span>
                        <button wire:click="openGoalModal" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-400 hover:text-indigo-600 hover:bg-white hover:shadow-sm transition-all active:scale-95">
                            <i class="ph-bold ph-gear-six text-lg"></i>
                        </button>
                    </div>
                </div>
                
                <div class="p-8 sm:p-10 grid grid-cols-1 md:grid-cols-2 gap-12 relative z-10">
                    {{-- Applications --}}
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 bg-indigo-600 rounded-full"></div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Applications</p>
                            </div>
                            <span class="text-xs font-black text-indigo-600">{{ min(100, $this->appliedProgress) }}%</span>
                        </div>
                        <div class="flex items-baseline gap-3">
                            <span class="text-5xl font-black text-slate-900 tracking-tighter leading-none italic">{{ $this->actualApplied }}</span>
                            <span class="text-slate-300 font-black text-lg">/ {{ $targetAppliedWeekly }}</span>
                        </div>
                        <div class="h-3 w-full bg-slate-100 rounded-full overflow-hidden p-0.5">
                            <div class="h-full bg-indigo-600 rounded-full transition-all duration-1000 shadow-[0_0_12px_rgba(79,70,229,0.3)]" style="width: {{ min(100, $this->appliedProgress) }}%"></div>
                        </div>
                    </div>

                    {{-- Follow-ups --}}
                    <div class="space-y-6 border-l border-slate-100 md:pl-12">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 bg-violet-600 rounded-full"></div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Follow-ups</p>
                            </div>
                            <span class="text-xs font-black text-violet-600">{{ min(100, $this->followupProgress) }}%</span>
                        </div>
                        <div class="flex items-baseline gap-3">
                            <span class="text-5xl font-black text-slate-900 tracking-tighter leading-none italic">{{ $this->followUpCount }}</span>
                            <span class="text-slate-300 font-black text-lg">/ {{ $targetFollowupWeekly }}</span>
                        </div>
                        <div class="h-3 w-full bg-slate-100 rounded-full overflow-hidden p-0.5">
                            <div class="h-full bg-violet-600 rounded-full transition-all duration-1000 shadow-[0_0_12px_rgba(139,92,246,0.3)]" style="width: {{ min(100, $this->followupProgress) }}%"></div>
                        </div>
                    </div>
                </div>
                
                {{-- Subtle Background Glow --}}
                <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-indigo-50 rounded-full blur-3xl opacity-50"></div>
            </div>

            {{-- Streak Bento --}}
            <div class="lg:col-span-4 bg-white rounded-[2.5rem] border border-slate-200/60 shadow-sm p-8 flex flex-col items-center justify-center relative overflow-hidden group">
                <div class="relative z-10 flex flex-col items-center gap-6">
                    <div class="w-20 h-20 rounded-[2rem] bg-orange-50 flex items-center justify-center relative shadow-inner group-hover:scale-110 transition-transform duration-500">
                        <i class="ph-fill ph-fire text-4xl text-orange-500"></i>
                        {{-- Ring Animation --}}
                        <div class="absolute inset-0 rounded-[2rem] border-2 border-orange-500/20 animate-ping"></div>
                    </div>
                    <div class="text-center space-y-2">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[2px]">Current Streak</p>
                        <p class="text-5xl font-black text-slate-900 tracking-tighter leading-none italic">
                            {{ $this->currentStreak }} <span class="text-xs text-slate-300 uppercase not-italic tracking-widest ml-1">Days</span>
                        </p>
                    </div>
                </div>
                <div class="absolute inset-0 bg-gradient-to-br from-orange-500/5 to-transparent"></div>
            </div>

            {{-- Interviews Bento - High Impact --}}
            <div class="lg:col-span-4 bg-indigo-600 rounded-[2.5rem] p-10 shadow-xl shadow-indigo-100 flex flex-col justify-between relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-white backdrop-blur-md mb-8 border border-white/10">
                        <i class="ph-bold ph-calendar-check text-2xl"></i>
                    </div>
                    <p class="text-[10px] font-black text-indigo-200 uppercase tracking-[3px] mb-2">Interviews Secured</p>
                    <p class="text-6xl font-black text-white tracking-tighter leading-none italic">{{ $this->actualInterviews }}</p>
                </div>
                
                {{-- Progress mini --}}
                <div class="relative z-10 mt-10">
                    <p class="text-[9px] font-black text-indigo-300 uppercase tracking-widest mb-2 italic">Active momentum protocol</p>
                    <div class="h-1 w-full bg-white/10 rounded-full overflow-hidden">
                        <div class="h-full bg-white rounded-full transition-all duration-1000" style="width: {{ min(100, ($this->actualInterviews / 5) * 100) }}%"></div>
                    </div>
                </div>

                {{-- Decorative background --}}
                <div class="absolute -right-12 -bottom-12 w-48 h-48 bg-white/10 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-700"></div>
            </div>

            {{-- Consistency Bento - Information Dense --}}
            <div class="lg:col-span-8 bg-slate-900 rounded-[2.5rem] p-10 flex flex-col md:flex-row items-center justify-between gap-10 relative overflow-hidden group">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,rgba(79,70,229,0.15),transparent)]"></div>
                
                <div class="relative z-10 space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-indigo-400">
                            <i class="ph-fill ph-chart-line-up text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-white tracking-tight">Consistency Tier</h3>
                            <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mt-1">High Velocity Mode Enabled</p>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-slate-400 leading-relaxed max-w-sm">
                        You're currently in the <span class="text-indigo-400 font-bold">Top 5%</span> of active job seekers. Maintain your daily cadence to unlock premium insights.
                    </p>
                </div>

                <div class="relative z-10 flex items-center gap-12 bg-white/5 border border-white/10 p-8 rounded-[2rem] backdrop-blur-sm">
                    <div class="text-center">
                        <p class="text-3xl font-black text-white tracking-tighter italic leading-none">{{ min(100, round(($this->currentStreak / 30) * 100)) }}%</p>
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mt-3">Accuracy</p>
                    </div>
                    <div class="h-12 w-px bg-white/10"></div>
                    <div class="text-center">
                        <p class="text-3xl font-black text-white tracking-tighter italic leading-none">30</p>
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mt-3">Next Goal</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Configuration Modal --}}
        @if($showGoalModal)
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xl flex items-center justify-center z-[100] p-4" x-data="{ show: @entangle('showGoalModal') }" x-show="show">
                <div class="bg-white rounded-[3rem] shadow-2xl max-w-lg w-full overflow-hidden animate-modal-enter border border-slate-200/60">
                    <div class="p-8 border-b border-slate-50 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600">
                                <i class="ph-bold ph-path text-xl"></i>
                            </div>
                            <h2 class="text-lg font-black text-slate-900 tracking-tight">CONFIGURE CYCLE</h2>
                        </div>
                        <button wire:click="closeGoalModal" class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-slate-100 transition-all text-slate-400">
                            <i class="ph-bold ph-x text-lg"></i>
                        </button>
                    </div>
                    <div class="p-8 sm:p-10">
                        <form wire:submit.prevent="setWeeklyGoals" class="space-y-8">
                            <div class="flex p-1.5 bg-slate-100 rounded-[1.5rem]">
                                <button type="button" wire:click="$set('goalPeriod', 'weekly')" class="flex-1 py-3 text-[10px] font-black uppercase rounded-[1.1rem] transition-all {{ $goalPeriod === 'weekly' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">Weekly Cycle</button>
                                <button type="button" wire:click="$set('goalPeriod', 'monthly')" class="flex-1 py-3 text-[10px] font-black uppercase rounded-[1.1rem] transition-all {{ $goalPeriod === 'monthly' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">Monthly Cycle</button>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-8">
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Applications</label>
                                    <div class="relative">
                                        <i class="ph-bold ph-paper-plane-tilt absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="number" wire:model="targetAppliedWeekly" class="w-full pl-12 pr-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-2xl tracking-tighter outline-none">
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Follow-ups</label>
                                    <div class="relative">
                                        <i class="ph-bold ph-arrows-clockwise absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="number" wire:model="targetFollowupWeekly" class="w-full pl-12 pr-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-2xl tracking-tighter outline-none">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-5 bg-slate-900 text-white rounded-[1.5rem] font-black uppercase tracking-[2px] text-[10px] hover:bg-indigo-600 transition-all shadow-xl shadow-slate-200 active:scale-95 flex items-center justify-center gap-3">
                                <i class="ph-bold ph-check"></i>
                                APPLY NEW CYCLE
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
        @keyframes modal-enter { from { opacity: 0; transform: scale(0.95) translateY(10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
        .animate-modal-enter { animation: modal-enter 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
    </style>
</div>
