<div x-data="{ showGamificationInfo: false }" class="w-full">
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

    <!-- Profile Dropdown Trigger Bar -->
    <div class="flex items-center justify-between w-full">
        <div class="flex items-center gap-1.5">
            <div class="flex items-center justify-center px-1.5 py-0.5 bg-primary-50 text-zinc-800 border border-primary-200/60 rounded text-[9px] font-bold tracking-tight shrink-0 shadow-3xs">
                L{{ $currentLvl }}
            </div>
            <div class="flex items-center gap-1 text-[10px] font-bold text-zinc-800">
                <span>{{ $currentXp }}</span>
                <span class="text-[8px] font-semibold text-zinc-400 uppercase">XP</span>
                <i class="ph-fill ph-lightning text-amber-500 text-[10px]"></i>
            </div>
        </div>
        <button type="button" @click="showGamificationInfo = true" class="text-[9.5px] font-bold text-zinc-650 hover:text-zinc-950 bg-zinc-100/80 hover:bg-zinc-100 px-2 py-0.5 rounded transition-colors flex items-center gap-1 focus:outline-none cursor-pointer">
            <span>Rewards</span>
            <i class="ph-bold ph-caret-right text-[8px]"></i>
        </button>
    </div>

    <!-- Gamification Info Modal -->
    <template x-teleport="body">
        <div x-show="showGamificationInfo" x-cloak class="fixed inset-0 z-[10000] flex items-center justify-center p-4">
            <!-- Backdrop -->
            <div x-show="showGamificationInfo" 
                 x-transition:enter="ease-out duration-250"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-zinc-950/40 backdrop-blur-xs" 
                 @click="showGamificationInfo = false"></div>
                 
            <!-- Modal Panel (Notion-Style Compact) -->
            <div x-show="showGamificationInfo"
                 x-transition:enter="ease-out duration-250"
                 x-transition:enter-start="opacity-0 translate-y-2 scale-98"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-2 scale-98"
                 class="bg-white rounded-lg shadow-xl w-full max-w-sm max-h-[85vh] overflow-hidden flex flex-col relative border border-zinc-200/80 text-left z-10">
                 
                 <!-- Header -->
                 <div class="px-4 py-3 border-b border-zinc-100 flex items-center justify-between bg-white sticky top-0 z-10">
                     <div class="flex items-center gap-2.5">
                         <div class="w-8 h-8 rounded-lg bg-primary-50 border border-primary-100/60 flex items-center justify-center p-1.5 shadow-3xs">
                             <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-full h-full object-contain">
                         </div>
                         <div>
                             <h2 class="text-xs font-bold text-zinc-800 tracking-tight leading-none">TraKerja Rewards</h2>
                             <p class="text-[9px] font-semibold text-zinc-400 uppercase tracking-wider mt-1 leading-none">Gamification & Leveling</p>
                         </div>
                     </div>
                     <button type="button" @click="showGamificationInfo = false" class="w-6 h-6 flex items-center justify-center bg-zinc-50 hover:bg-zinc-100 text-zinc-400 hover:text-zinc-700 rounded-md transition-colors focus:outline-none">
                         <i class="ph-bold ph-x text-xs"></i>
                     </button>
                 </div>

                 <!-- Content Body -->
                 <div class="p-4 overflow-y-auto bg-[#fafafa]/50 flex-1 space-y-4">
                     
                     <!-- User Level Overview Card -->
                     <div class="bg-white p-3.5 rounded-lg border border-zinc-200/60 shadow-3xs flex items-center gap-3.5">
                         <div class="w-10 h-10 bg-primary-50 rounded-lg border border-primary-200/60 flex items-center justify-center relative shrink-0">
                             <i class="ph-fill ph-trophy text-lg text-zinc-800"></i>
                             <div class="absolute -top-1.5 -right-1.5 bg-primary-50 text-zinc-800 text-[8px] font-bold px-1.5 py-0.2 rounded border border-primary-200/60 shadow-3xs">
                                 L{{ $currentLvl }}
                             </div>
                         </div>

                         <div class="flex-1 min-w-0">
                             <h3 class="text-xs font-bold text-zinc-800 leading-tight truncate">{{ Auth::user()->level_title ?? 'Job Seeker' }}</h3>
                             <p class="text-[9.5px] font-semibold text-zinc-400 mt-1 uppercase tracking-wider leading-none">
                                 <span class="text-zinc-800 font-bold">{{ $currentXp }} XP</span> / {{ $currentLvl >= 5 ? 'MAX' : $nextThreshold . ' XP' }}
                             </p>
                             <!-- Progress Bar -->
                             <div class="w-full h-1.5 bg-zinc-100 rounded-full overflow-hidden mt-2 p-0.2">
                                 <div class="h-full bg-zinc-800 rounded-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                             </div>
                         </div>
                     </div>

                     <!-- How to Earn XP -->
                     <div>
                         <h4 class="text-[9.5px] font-bold text-zinc-400 uppercase tracking-widest mb-2 flex items-center gap-1.5 ml-0.5 leading-none">
                             <i class="ph-bold ph-lightning text-amber-500"></i> How to Earn XP
                         </h4>
                         <div class="bg-white rounded-lg border border-zinc-200/60 shadow-3xs overflow-hidden divide-y divide-zinc-100">
                             <div class="flex items-center justify-between p-2.5 hover:bg-zinc-50/50 transition-colors">
                                 <div class="flex items-center gap-2.5">
                                     <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200 text-zinc-500 flex items-center justify-center">
                                         <i class="ph-bold ph-plus text-xs"></i>
                                     </div>
                                     <span class="text-xs font-semibold text-zinc-700">Add Application</span>
                                 </div>
                                 <span class="text-[9px] font-bold text-zinc-800 bg-primary-50 border border-primary-200/60 px-2 py-0.5 rounded-md uppercase tracking-wider">+10 XP</span>
                             </div>
                             <div class="flex items-center justify-between p-2.5 hover:bg-zinc-50/50 transition-colors">
                                 <div class="flex items-center gap-2.5">
                                     <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200 text-zinc-500 flex items-center justify-center">
                                         <i class="ph-bold ph-users text-xs"></i>
                                     </div>
                                     <span class="text-xs font-semibold text-zinc-700">Reach Interview</span>
                                 </div>
                                 <span class="text-[9px] font-bold text-zinc-800 bg-primary-50 border border-primary-200/60 px-2 py-0.5 rounded-md uppercase tracking-wider">+50 XP</span>
                             </div>
                             <div class="flex items-center justify-between p-2.5 hover:bg-zinc-50/50 transition-colors">
                                 <div class="flex items-center gap-2.5">
                                     <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200 text-zinc-500 flex items-center justify-center">
                                         <i class="ph-bold ph-handshake text-xs"></i>
                                     </div>
                                     <span class="text-xs font-semibold text-zinc-700">Get an Offer</span>
                                 </div>
                                 <span class="text-[9px] font-bold text-emerald-800 bg-emerald-50 border border-emerald-200/60 px-2 py-0.5 rounded-md uppercase tracking-wider">+100 XP</span>
                             </div>
                         </div>
                     </div>

                     <!-- Progression Path -->
                     <div>
                         <h4 class="text-[9.5px] font-bold text-zinc-400 uppercase tracking-widest mb-2 flex items-center gap-1.5 ml-0.5 leading-none">
                             <i class="ph-bold ph-path"></i> Level Thresholds
                         </h4>
                         <div class="bg-white p-3.5 rounded-lg border border-zinc-200/60 shadow-3xs relative">
                             <div class="relative">
                                 <!-- Connector Lines -->
                                 <div class="absolute top-[14px] left-[14px] right-[14px] h-0.5 bg-zinc-100 rounded-full pointer-events-none">
                                     <div class="h-full bg-zinc-800 rounded-full transition-all duration-500" style="width: {{ (($currentLvl - 1) / 4) * 100 }}%"></div>
                                 </div>
                                 
                                 <div class="relative flex justify-between">
                                     @foreach(\App\Models\User::LEVEL_THRESHOLDS as $lvl => $xp)
                                         @php
                                            $isPassed = $currentLvl > $lvl;
                                            $isActive = $currentLvl == $lvl;
                                         @endphp
                                         <div class="flex flex-col items-center group relative">
                                             <div class="w-7 h-7 rounded-full flex items-center justify-center text-[9px] font-bold transition-all duration-300 relative z-10 
                                                 {{ $isActive ? 'bg-primary-50 text-zinc-800 border border-primary-200/80 ring-2 ring-primary-100/60 shadow-3xs scale-105 font-extrabold' : 
                                                    ($isPassed ? 'bg-primary-50 text-zinc-800 border border-primary-200/60' : 'bg-white border border-zinc-200 text-zinc-400') }}">
                                                 @if($isPassed)
                                                     <i class="ph-bold ph-check text-[10px]"></i>
                                                 @else
                                                     L{{ $lvl }}
                                                 @endif
                                             </div>
                                             <span class="text-[8.5px] font-semibold tracking-wide mt-2 {{ $isActive ? 'text-zinc-800 font-bold' : 'text-zinc-400' }}">{{ $xp }}</span>
                                         </div>
                                     @endforeach
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
            </div>
        </div>
    </template>
</div>
