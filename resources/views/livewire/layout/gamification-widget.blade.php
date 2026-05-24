<div x-data="{ showGamificationInfo: false }">
    @php
        $currentLvl = Auth::user()->level ?? 1;
        $currentXp = Auth::user()->xp ?? 0;
        $thresholds = \App\Models\User::LEVEL_THRESHOLDS ?? [1=>0,2=>100,3=>300,4=>600,5=>1000];
        $nextThreshold = $thresholds[$currentLvl + 1] ?? $currentXp;
        $prevThreshold = $thresholds[$currentLvl] ?? 0;
        
        $range = max(1, $nextThreshold - $prevThreshold);
        $progress = max(0, $currentXp - $prevThreshold);
        $percentage = ($currentLvl >= 5) ? 100 : min(100, ($progress / $range) * 100);
    @endphp

    <!-- Top Bar Pill (Clickable Trigger) -->
    <button type="button" @click="showGamificationInfo = true" class="hidden sm:flex items-center gap-2 mr-2 bg-white border border-slate-200/80 shadow-sm px-1.5 py-1.5 rounded-full hover:border-primary-300 hover:shadow-md hover:shadow-primary-500/10 transition-all duration-300 group cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary-500/30" title="Click to view Level & XP info">
        
        <div class="flex items-center justify-center w-7 h-7 bg-primary-500 rounded-full shadow-inner text-white font-black text-[10px] border border-primary-100/50">
            L{{ $currentLvl }}
        </div>
        
        <div class="flex flex-col justify-center pr-2 text-left">
            <div class="flex justify-between items-center w-20 mb-0.5">
                <span class="text-[9px] font-black text-slate-800">{{ $currentXp }} <span class="text-slate-400 font-bold">XP</span></span>
                <i class="ph-fill ph-lightning text-primary-500 text-[10px] group-hover:animate-pulse"></i>
            </div>
            <div class="w-20 h-1.5 bg-slate-100 rounded-full overflow-hidden shadow-inner">
                <div class="h-full bg-primary-500 rounded-full transition-all duration-1000 relative" style="width: {{ $percentage }}%">
                    <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                </div>
            </div>
        </div>
    </button>

    <!-- Gamification Info Modal -->
    <template x-teleport="body">
        <div x-show="showGamificationInfo" x-cloak class="fixed inset-0 z-[10000] flex items-center justify-center p-4 sm:p-6">
        <!-- Backdrop -->
        <div x-show="showGamificationInfo" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" 
             @click="showGamificationInfo = false"></div>
             
        <!-- Modal Panel -->
        <div x-show="showGamificationInfo"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md max-h-[90vh] overflow-hidden flex flex-col relative border border-slate-100 text-left z-10">
             
             <!-- Header -->
             <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-white sticky top-0 z-10">
                 <div class="flex items-center gap-3">
                     <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 shadow-sm flex items-center justify-center p-2">
                         <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-full h-full object-contain">
                     </div>
                     <div>
                         <h2 class="text-base font-black text-slate-800">TraKerja Rewards</h2>
                         <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Gamification & Leveling</p>
                     </div>
                 </div>
                 <button type="button" @click="showGamificationInfo = false" class="w-8 h-8 flex items-center justify-center bg-slate-50 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-full transition-colors focus:outline-none">
                     <i class="ph-bold ph-x text-sm"></i>
                 </button>
             </div>

             <!-- Content -->
             <div class="p-6 overflow-y-auto bg-slate-50/50 flex-1">
                 
                 <!-- User Level Overview (Compact) -->
                 <div class="flex items-center gap-5 mb-6 bg-white p-5 rounded-[1.5rem] border border-slate-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
                     <div class="w-16 h-16 bg-gradient-to-br from-primary-50 to-indigo-50 rounded-2xl border border-primary-100/50 flex items-center justify-center relative shrink-0">
                         <i class="ph-fill ph-trophy text-3xl text-primary-500"></i>
                         <div class="absolute -top-2 -right-2 bg-primary-600 text-white text-[9px] font-black px-2 py-0.5 rounded-full border-2 border-white shadow-sm">
                             L{{ $currentLvl }}
                         </div>
                     </div>

                     <div class="flex-1 min-w-0">
                         <h2 class="text-lg font-black text-slate-900 leading-tight truncate">{{ Auth::user()->level_title ?? 'Job Seeker' }}</h2>
                         <p class="text-[10px] font-bold text-slate-500 mt-1 uppercase tracking-wide">
                             <span class="text-primary-600">{{ $currentXp }} XP</span> / {{ $currentLvl >= 5 ? 'MAX' : $nextThreshold . ' XP' }}
                         </p>
                         <!-- Progress Bar -->
                         <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden mt-2.5">
                             <div class="h-full bg-primary-500 rounded-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                         </div>
                     </div>
                 </div>

                 <!-- How to Earn XP -->
                 <div class="mb-6">
                     <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-1.5 ml-1">
                         <i class="ph-bold ph-lightning"></i> How to Earn XP
                     </h3>
                     <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)] overflow-hidden">
                         <div class="flex items-center justify-between p-3.5 border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                             <div class="flex items-center gap-3">
                                 <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                     <i class="ph-bold ph-plus text-base"></i>
                                 </div>
                                 <div>
                                     <p class="text-xs font-bold text-slate-800">Add Application</p>
                                 </div>
                             </div>
                             <span class="text-[10px] font-black text-indigo-600 bg-indigo-50 px-2 py-1 rounded-lg border border-indigo-100">+10 XP</span>
                         </div>
                         <div class="flex items-center justify-between p-3.5 border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                             <div class="flex items-center gap-3">
                                 <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                                     <i class="ph-bold ph-users text-base"></i>
                                 </div>
                                 <div>
                                     <p class="text-xs font-bold text-slate-800">Reach Interview</p>
                                 </div>
                             </div>
                             <span class="text-[10px] font-black text-purple-600 bg-purple-50 px-2 py-1 rounded-lg border border-purple-100">+50 XP</span>
                         </div>
                         <div class="flex items-center justify-between p-3.5 hover:bg-slate-50/50 transition-colors">
                             <div class="flex items-center gap-3">
                                 <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                     <i class="ph-bold ph-handshake text-base"></i>
                                 </div>
                                 <div>
                                     <p class="text-xs font-bold text-slate-800">Get an Offer</p>
                                 </div>
                             </div>
                             <span class="text-[10px] font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg border border-emerald-100">+100 XP</span>
                         </div>
                     </div>
                 </div>

                 <!-- Progression Path -->
                 <div class="pb-2">
                     <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-1.5 ml-1">
                         <i class="ph-bold ph-path"></i> Level Thresholds
                     </h3>
                     <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)] relative overflow-hidden">
                         <div class="relative px-2">
                             <!-- Background Line -->
                             <div class="absolute top-[14px] left-4 right-4 h-1 bg-slate-100 rounded-full"></div>
                             <!-- Active Line -->
                             <div class="absolute top-[14px] left-4 h-1 bg-primary-500 rounded-full transition-all" style="width: {{ min(100, (($currentLvl - 1) / 4) * 100) }}%"></div>
                             
                             <div class="relative flex justify-between">
                                 @foreach(\App\Models\User::LEVEL_THRESHOLDS as $lvl => $xp)
                                     @php
                                        $isPassed = $currentLvl > $lvl;
                                        $isActive = $currentLvl == $lvl;
                                     @endphp
                                     <div class="flex flex-col items-center group relative">
                                         <div class="w-8 h-8 rounded-full flex items-center justify-center text-[10px] font-black transition-all duration-300 relative z-10 
                                             {{ $isActive ? 'bg-primary-600 text-white ring-4 ring-primary-50 shadow-md scale-110' : 
                                                ($isPassed ? 'bg-primary-500 text-white' : 'bg-white border border-slate-200 text-slate-300') }}">
                                             @if($isPassed)
                                                 <i class="ph-bold ph-check text-[11px]"></i>
                                             @else
                                                 L{{ $lvl }}
                                             @endif
                                         </div>
                                         <span class="absolute -bottom-5 text-[9px] font-bold tracking-wide {{ $isActive ? 'text-primary-600' : 'text-slate-400' }}">{{ $xp }}</span>
                                     </div>
                                 @endforeach
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
        </div></template>
</div>
