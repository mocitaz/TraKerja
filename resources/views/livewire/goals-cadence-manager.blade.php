<div class="min-h-[80vh]">
    <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            {{-- Performance Hub - The Core Bento --}}
            <div class="lg:col-span-8 bg-white rounded-[2.5rem] border border-slate-200/60 shadow-sm overflow-hidden flex flex-col relative group">
                <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-2xl bg-primary-600 flex items-center justify-center text-white shadow-lg shadow-primary-100">
                            <i class="ph-fill ph-target text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-black text-slate-900 tracking-tight">Performance Cadence</h2>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Real-time target tracking</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-4 py-1.5 bg-primary-50 text-primary-600 rounded-full text-[9px] font-black uppercase tracking-widest border border-primary-100">{{ $goalPeriod }}</span>
                        <button wire:click="openGoalModal" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-400 hover:text-primary-600 hover:bg-white hover:shadow-sm transition-all active:scale-95">
                            <i class="ph-bold ph-gear-six text-lg"></i>
                        </button>
                    </div>
                </div>
                
                <div class="p-8 sm:p-10 grid grid-cols-1 md:grid-cols-2 gap-12 relative z-10">
                    {{-- Applications --}}
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 bg-primary-600 rounded-full"></div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Applications</p>
                            </div>
                            <span class="text-xs font-black text-primary-600">{{ min(100, $this->appliedProgress) }}%</span>
                        </div>
                        <div class="flex items-baseline gap-3">
                            <span class="text-5xl font-black text-slate-900 tracking-tighter leading-none italic">{{ $this->actualApplied }}</span>
                            <span class="text-slate-300 font-black text-lg">/ {{ $targetAppliedWeekly }}</span>
                        </div>
                        <div class="h-3 w-full bg-slate-100 rounded-full overflow-hidden p-0.5">
                            <div class="h-full bg-primary-600 rounded-full transition-all duration-1000 shadow-[0_0_12px_rgba(165,112,240,0.3)]" style="width: {{ min(100, $this->appliedProgress) }}%"></div>
                        </div>
                    </div>

                    {{-- Follow-ups --}}
                    <div class="space-y-6 border-l border-slate-100 md:pl-12">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 bg-primary-600 rounded-full"></div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Follow-ups</p>
                            </div>
                            <span class="text-xs font-black text-primary-600">{{ min(100, $this->followupProgress) }}%</span>
                        </div>
                        <div class="flex items-baseline gap-3">
                            <span class="text-5xl font-black text-slate-900 tracking-tighter leading-none italic">{{ $this->followUpCount }}</span>
                            <span class="text-slate-300 font-black text-lg">/ {{ $targetFollowupWeekly }}</span>
                        </div>
                        <div class="h-3 w-full bg-slate-100 rounded-full overflow-hidden p-0.5">
                            <div class="h-full bg-primary-600 rounded-full transition-all duration-1000 shadow-[0_0_12px_rgba(165,112,240,0.3)]" style="width: {{ min(100, $this->followupProgress) }}%"></div>
                        </div>
                    </div>
                </div>
                
                {{-- Subtle Background Glow --}}
                <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-primary-50 rounded-full blur-3xl opacity-50"></div>
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
            <div class="lg:col-span-4 bg-primary-600 rounded-[2.5rem] p-10 shadow-xl shadow-primary-100 flex flex-col justify-between relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-white backdrop-blur-md mb-8 border border-white/10">
                        <i class="ph-bold ph-calendar-check text-2xl"></i>
                    </div>
                    <p class="text-[10px] font-black text-primary-200 uppercase tracking-[3px] mb-2">Interviews Secured</p>
                    <p class="text-6xl font-black text-white tracking-tighter leading-none italic">{{ $this->actualInterviews }}</p>
                </div>
                
                {{-- Progress mini --}}
                <div class="relative z-10 mt-10">
                    <p class="text-[9px] font-black text-primary-300 uppercase tracking-widest mb-2 italic">Active momentum protocol</p>
                    <div class="h-1 w-full bg-white/10 rounded-full overflow-hidden">
                        <div class="h-full bg-white rounded-full transition-all duration-1000" style="width: {{ min(100, ($this->actualInterviews / 5) * 100) }}%"></div>
                    </div>
                </div>

                {{-- Decorative background --}}
                <div class="absolute -right-12 -bottom-12 w-48 h-48 bg-white/10 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-700"></div>
            </div>

            {{-- Consistency Bento - Information Dense --}}
            <div class="lg:col-span-8 bg-slate-900 rounded-[2.5rem] p-10 flex flex-col md:flex-row items-center justify-between gap-10 relative overflow-hidden group">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,rgba(165,112,240,0.15),transparent)]"></div>
                
                <div class="relative z-10 space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-primary-400">
                            <i class="ph-fill ph-chart-line-up text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-white tracking-tight">Consistency Tier</h3>
                            <p class="text-[10px] font-bold text-primary-400 uppercase tracking-widest mt-1">High Velocity Mode Enabled</p>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-slate-400 leading-relaxed max-w-sm">
                        You're currently in the <span class="text-primary-400 font-bold">Top 5%</span> of active job seekers. Maintain your daily cadence to unlock premium insights.
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
        <template x-teleport="body">
            <div x-show="$wire.showGoalModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-xl flex items-center justify-center z-[9999] p-4"
                 style="display: none;">
                
                <div @click.away="$wire.showGoalModal = false"
                     x-show="$wire.showGoalModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95 translateY(10px)"
                     x-transition:enter-end="opacity-100 scale-100 translateY(0)"
                     class="bg-white rounded-[2.5rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.2)] max-w-lg w-full overflow-hidden border border-slate-100 flex flex-col">
                    
                    {{-- Modal Header --}}
                    <div class="bg-white px-8 py-6 flex justify-between items-center border-b border-slate-50 shrink-0">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center shadow-sm">
                                <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-6 h-6 object-contain">
                            </div>
                            <div>
                                <h3 class="text-base font-black text-slate-900 tracking-tight">Configure Cycle</h3>
                                <p class="text-slate-400 text-[9px] font-bold uppercase tracking-widest mt-0.5">Goal Cadence Setting</p>
                            </div>
                        </div>
                        <button @click="$wire.showGoalModal = false" class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-slate-50 transition-all text-slate-400 hover:text-slate-900">
                            <i class="ph-bold ph-x text-lg"></i>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-8 sm:p-10 overflow-y-auto max-h-[70vh]">
                        <form wire:submit.prevent="setWeeklyGoals" class="space-y-10">
                            {{-- Cycle Switcher --}}
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Frequency</label>
                                <div class="flex p-1.5 bg-slate-100 rounded-[1.8rem]">
                                    <button type="button" wire:click="$set('goalPeriod', 'weekly')" 
                                            class="flex-1 py-3.5 text-[10px] font-black uppercase rounded-[1.4rem] transition-all {{ $goalPeriod === 'weekly' ? 'bg-white text-primary-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">
                                        Weekly Cycle
                                    </button>
                                    <button type="button" wire:click="$set('goalPeriod', 'monthly')" 
                                            class="flex-1 py-3.5 text-[10px] font-black uppercase rounded-[1.4rem] transition-all {{ $goalPeriod === 'monthly' ? 'bg-white text-primary-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">
                                        Monthly Cycle
                                    </button>
                                </div>
                            </div>
                            
                            {{-- Input Fields --}}
                            <div class="grid grid-cols-2 gap-8">
                                <div class="space-y-4">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Applications</label>
                                    <div class="relative group">
                                        <div class="absolute left-5 top-1/2 -translate-y-1/2 w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center transition-colors group-focus-within:bg-primary-50">
                                            <i class="ph-bold ph-paper-plane-tilt text-slate-400 group-focus-within:text-primary-500"></i>
                                        </div>
                                        <input type="number" wire:model="targetAppliedWeekly" 
                                               class="w-full pl-16 pr-6 py-5 bg-slate-50 border border-slate-200 rounded-[1.5rem] font-black text-slate-900 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 focus:bg-white transition-all text-2xl tracking-tighter outline-none">
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Follow-ups</label>
                                    <div class="relative group">
                                        <div class="absolute left-5 top-1/2 -translate-y-1/2 w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center transition-colors group-focus-within:bg-primary-50">
                                            <i class="ph-bold ph-arrows-clockwise text-slate-400 group-focus-within:text-primary-500"></i>
                                        </div>
                                        <input type="number" wire:model="targetFollowupWeekly" 
                                               class="w-full pl-16 pr-6 py-5 bg-slate-50 border border-slate-200 rounded-[1.5rem] font-black text-slate-900 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 focus:bg-white transition-all text-2xl tracking-tighter outline-none">
                                    </div>
                                </div>
                            </div>

                            {{-- Footer Actions --}}
                            <div class="pt-6 border-t border-slate-50 flex items-center gap-4">
                                <button type="button" @click="$wire.showGoalModal = false" class="flex-1 py-4 bg-slate-100 text-slate-500 rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-slate-200 transition-all">
                                    Cancel
                                </button>
                                <button type="submit" class="flex-[2] py-4 bg-primary-600 text-white rounded-2xl font-black uppercase tracking-[2px] text-[10px] hover:bg-primary-700 transition-all shadow-xl shadow-primary-100 active:scale-95 flex items-center justify-center gap-3">
                                    <i class="ph-bold ph-check-circle text-base"></i>
                                    Save New Cycle
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <style>
        @keyframes modal-enter { from { opacity: 0; transform: scale(0.95) translateY(10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
        .animate-modal-enter { animation: modal-enter 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
    </style>
</div>
