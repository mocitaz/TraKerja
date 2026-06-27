<!-- Weekly Goal Progress Widget -->
<div class="bg-white rounded-lg border border-zinc-200/60 p-4 relative overflow-hidden" x-data="{ open: false }">
    <!-- Decorative Accent -->
    <div class="absolute -right-16 -top-16 w-32 h-32 bg-primary-50/50 rounded-full opacity-35 pointer-events-none"></div>
    
    <div class="relative z-10">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-xs font-bold text-zinc-850 tracking-tight uppercase tracking-wider">Weekly Targets</h3>
                <p class="text-[9px] text-zinc-400 font-medium mt-0.5">Consistent performance cadence</p>
            </div>
            <i class="ph ph-target text-primary-500 text-sm"></i>
        </div>

        @if(!$this->currentGoal)
            <div class="py-6 text-center">
                <p class="text-xs text-zinc-400 font-medium">No active goal set for this week.</p>
                <button type="button" wire:click="openGoalModal" class="mt-4 inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-primary-50 text-zinc-800 border border-primary-200/60 hover:bg-primary-100 text-xs font-bold rounded-md shadow-3xs transition-all active:scale-97">
                    <span>Set Weekly Target</span>
                    <i class="ph ph-arrow-right text-[10px]"></i>
                </button>
            </div>
        @else
            <div class="space-y-5">
                <!-- Applied Goal -->
                <div>
                    <div class="flex justify-between items-end mb-1.5">
                        <span class="text-xs font-semibold text-slate-600">Applications Submitted</span>
                        <span class="text-xs font-bold text-primary-700">{{ $this->actualApplied }} / {{ $targetAppliedWeekly }}</span>
                    </div>
                    <!-- Progress Bar -->
                    <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-650 rounded-full transition-all duration-550" style="width: {{ $this->appliedProgress }}%"></div>
                    </div>
                    <div class="flex justify-between items-center mt-1">
                        <span class="text-[9px] text-slate-400 font-semibold">Weekly Target</span>
                        <span class="text-[9px] font-bold text-slate-500 bg-slate-50 border border-slate-200/50 px-1.5 py-0.5 rounded">{{ $this->appliedProgress }}%</span>
                    </div>
                </div>

                <!-- Followup Goal -->
                <div>
                    <div class="flex justify-between items-end mb-1.5">
                        <span class="text-xs font-semibold text-slate-600">Follow-up Checks</span>
                        <span class="text-xs font-bold text-emerald-700">{{ $this->followUpCount }} / {{ $targetFollowupWeekly }}</span>
                    </div>
                    <!-- Progress Bar -->
                    <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full transition-all duration-550" style="width: {{ $this->followupProgress }}%"></div>
                    </div>
                    <div class="flex justify-between items-center mt-1">
                        <span class="text-[9px] text-slate-400 font-semibold">Weekly Target</span>
                        <span class="text-[9px] font-bold text-slate-500 bg-slate-50 border border-slate-200/50 px-1.5 py-0.5 rounded">{{ $this->followupProgress }}%</span>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-3.5 mt-2">
                    <!-- Streak Counter -->
                    <div class="flex items-center gap-3 bg-gradient-to-r from-amber-500/5 to-orange-500/5 p-2.5 rounded-xl border border-amber-100/50">
                        <div class="w-8 h-8 bg-gradient-to-r from-amber-500 to-orange-600 rounded-lg flex items-center justify-center text-white shadow-sm shrink-0">
                            <i class="ph ph-fire text-base"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-amber-850">{{ $this->currentStreak }} Day Streak!</h4>
                            <p class="text-[9px] text-amber-600/70 font-semibold tracking-wide mt-0.5">Keep updating your applications</p>
                        </div>
                    </div>
                </div>

                <button type="button" wire:click="openGoalModal" class="w-full justify-center mt-1 flex items-center gap-1.5 px-3 py-1.5 bg-primary-50 text-zinc-800 border border-primary-200/60 hover:bg-primary-100 rounded-md text-xs font-bold shadow-3xs transition-all active:scale-97">
                    <span>Adjust Targets</span>
                    <i class="ph ph-gear text-xs"></i>
                </button>
            </div>
        @endif
    </div>

    <!-- Configuration Modal using Teleport overlay -->
    <template x-teleport="body">
        <div x-show="$wire.showGoalModal" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-zinc-950/40 flex items-center justify-center z-[99999] p-4"
             style="display: none;">
            
            <div @click.away="$wire.showGoalModal = false"
                 x-show="$wire.showGoalModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-97 translateY(8px)"
                 x-transition:enter-end="opacity-100 scale-100 translateY(0)"
                 class="bg-white rounded-lg shadow-xl max-w-sm w-full border border-zinc-200 overflow-hidden flex flex-col">
                
                <!-- Modal Header -->
                <div class="bg-white px-4 py-3 text-zinc-900 flex justify-between items-center border-b border-zinc-150/60 shrink-0">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200/60 flex items-center justify-center shrink-0 shadow-3xs">
                            <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-4 h-4 object-contain" onerror="this.src='{{ asset('favicon.png') }}'">
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <h3 class="text-xs font-bold text-zinc-800 tracking-tight">Configure Goal</h3>
                                <span class="px-1.5 py-0.5 bg-primary-50 text-zinc-800 text-[8.5px] font-bold uppercase tracking-wider rounded border border-primary-100/60 leading-none">Goals</span>
                            </div>
                            <p class="text-zinc-400 text-[9px] font-medium mt-0.5">Career Growth Tracking</p>
                        </div>
                    </div>
                    <button type="button" @click="$wire.showGoalModal = false" class="w-7 h-7 flex items-center justify-center rounded hover:bg-zinc-50 transition-all text-zinc-400 hover:text-zinc-800 focus:outline-none">
                        <i class="ph ph-x text-xs"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-4 sm:p-5">
                    <form wire:submit.prevent="setWeeklyGoals" class="space-y-4">
                        <!-- Frequency switcher -->
                        <div class="space-y-1.5">
                            <label class="block text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-1.5 ml-1">Frequency Period</label>
                            <div class="flex p-0.5 bg-zinc-100 rounded-md">
                                <button type="button" wire:click="$set('goalPeriod', 'weekly')" 
                                        class="flex-1 py-1 text-[10px] font-bold uppercase rounded transition-all {{ $goalPeriod === 'weekly' ? 'bg-white text-zinc-800 shadow-3xs border border-zinc-200/40' : 'text-zinc-400 hover:text-zinc-700' }}">
                                    Weekly
                                </button>
                                <button type="button" wire:click="$set('goalPeriod', 'monthly')" 
                                        class="flex-1 py-1 text-[10px] font-bold uppercase rounded transition-all {{ $goalPeriod === 'monthly' ? 'bg-white text-zinc-800 shadow-3xs border border-zinc-200/40' : 'text-zinc-400 hover:text-zinc-700' }}">
                                    Monthly
                                </button>
                            </div>
                        </div>
                        
                        <!-- Inputs -->
                        <div class="grid grid-cols-2 gap-3.5">
                            <div class="space-y-1.5">
                                <label class="block text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-1.5 ml-1">Applications</label>
                                <div class="relative">
                                    <input type="number" wire:model="targetAppliedWeekly" 
                                           class="w-full pl-8 pr-3 py-1.5 bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold focus:outline-none focus:bg-white focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                                    <i class="ph ph-paper-plane-tilt absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 text-xs"></i>
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-1.5 ml-1">Follow-ups</label>
                                <div class="relative">
                                    <input type="number" wire:model="targetFollowupWeekly" 
                                           class="w-full pl-8 pr-3 py-1.5 bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold focus:outline-none focus:bg-white focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                                    <i class="ph ph-arrows-clockwise absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="pt-4 mt-5 border-t border-zinc-150/60 flex items-center gap-2.5">
                            <button type="button" @click="$wire.showGoalModal = false" class="px-3.5 py-1.5 text-xs font-semibold text-zinc-650 bg-zinc-50 border border-zinc-200 hover:bg-zinc-100 rounded-md transition-colors focus:outline-none">
                                Cancel
                            </button>
                            <button type="submit" class="flex-1 px-3.5 py-1.5 text-xs font-bold bg-primary-50 text-zinc-800 border border-primary-200/60 hover:bg-primary-100 rounded-md transition-colors shadow-3xs focus:outline-none flex items-center justify-center gap-1.5 active:scale-97">
                                <i class="ph ph-check-circle text-sm"></i>
                                <span>Save Settings</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
