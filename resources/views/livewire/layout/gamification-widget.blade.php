<div x-data="{ showGamificationInfo: false }" class="w-full">
    @php
        $currentLvl = Auth::user()->level ?? 1;
        $currentXp = Auth::user()->xp ?? 0;
        $thresholds = \App\Models\User::LEVEL_THRESHOLDS ?? [1=>0,2=>100,3=>300,4=>600,5=>1000];
        $nextThreshold = $thresholds[$currentLvl + 1] ?? $currentXp;
        $prevThreshold = $thresholds[$currentLvl] ?? 0;

        $range = max(1, $nextThreshold - $prevThreshold);
        $progress = max(0, $currentXp - $prevThreshold);
        $maxLevel = array_key_last($thresholds);
        $percentage = ($currentLvl >= $maxLevel) ? 100 : min(100, ($progress / $range) * 100);
    @endphp

    <!-- Profile Dropdown Trigger Bar -->
    <div class="gamification-summary flex items-center justify-between w-full mt-2.5">
        <div class="flex items-center gap-2">
            <div class="gamification-level flex items-center justify-center px-2 py-0.5 bg-primary-50 dark:bg-[#1e1f20] text-zinc-800 dark:text-[#f0f4f9] border border-primary-200/60 dark:border-white/10 rounded-md text-[10px] font-bold tracking-tight shrink-0 shadow-3xs">
                {{ __('L:value1', ['value1' => ($currentLvl)]) }}
            </div>
            <div class="flex items-center gap-1 text-[11px] font-bold text-zinc-800 dark:text-[#f0f4f9]">
                <span>{{ $currentXp }}</span>
                <span class="text-[9px] font-bold text-zinc-400 dark:text-[#94a3b8] uppercase tracking-wider">{{ __('XP') }}</span>
                <i class="ph-fill ph-lightning text-amber-500 text-xs animate-pulse"></i>
            </div>
        </div>
        <button type="button" @click="showGamificationInfo = true" class="text-[10px] font-bold text-zinc-700 hover:text-zinc-950 dark:text-[#cbd5e1] dark:hover:text-[#f0f4f9] bg-zinc-100 hover:bg-zinc-200/65 dark:bg-[#282a2c] dark:hover:bg-[#333538] border border-zinc-200/60 dark:border-white/10 px-2.5 py-1 rounded-md transition-all flex items-center gap-1 shadow-3xs focus:outline-none cursor-pointer active:scale-97">
            <span>{{ __('Rewards') }}</span>
            <i class="ph-bold ph-caret-right text-[8px]"></i>
        </button>
    </div>

    <div class="gamification-progress hidden">
        <div class="gamification-progress-track" role="progressbar" aria-label="{{ __('Progress to next career level') }}" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ round($percentage) }}"><span style="width: {{ $percentage }}%;"></span></div>
        <span>{{ $currentLvl >= 5 ? __('Maximum level reached') : __('Next level at ').number_format($nextThreshold).__(' XP') }}</span>
    </div>

    <template x-teleport="body">
        <div x-show="showGamificationInfo" x-cloak class="account-dialog-overlay" @click.self="showGamificationInfo = false"
             x-transition:enter="account-dialog-fade" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="account-dialog-fade" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <section class="rewards-dialog rewards-dialog--workspace" role="dialog" aria-modal="true" aria-labelledby="rewards-title" aria-describedby="rewards-description" tabindex="-1" x-dialog="showGamificationInfo">
                <header class="account-dialog-heading"><div><h2 id="rewards-title">{{ __('TraKerja Rewards') }}</h2><p id="rewards-description">{{ __('See how your career activity adds up.') }}</p></div><button type="button" class="account-dialog-close" @click="showGamificationInfo = false" aria-label="{{ __('Close rewards') }}"><i class="ph ph-x" aria-hidden="true"></i></button></header>
                <div class="rewards-body">
                    <section class="rewards-overview" aria-label="{{ __('Your current level') }}">
                        <div class="rewards-overview-heading">
                            <div class="rewards-level-emblem" aria-hidden="true">{{ __('L:value1', ['value1' => $currentLvl]) }}</div>
                            <div class="rewards-level-copy"><span class="rewards-level-label">{{ __('Level :value1', ['value1' => $currentLvl]) }}</span><h3>{{ Auth::user()->level_title }}</h3></div>
                            <div class="rewards-xp-total"><strong>{{ number_format($currentXp) }}</strong><span>{{ __('total XP') }}</span></div>
                        </div>
                        <div class="rewards-progress-caption"><p>{{ $currentLvl >= $maxLevel ? __('Highest level unlocked') : number_format(max(0, $nextThreshold - $currentXp)).__(' XP to Level ').($currentLvl + 1) }}</p><span>{{ round($percentage) }}%</span></div>
                        <div class="rewards-progress-bar" role="progressbar" aria-label="{{ __('Progress to next career level') }}" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ round($percentage) }}"><span style="width: {{ $percentage }}%"></span></div>
                    </section>
                    <section class="rewards-path"><h3>{{ __('Your level journey') }}</h3><ol aria-label="{{ __('Career level milestones') }}">
                        @foreach($thresholds as $lvl => $xp)
                            <li class="{{ $currentLvl == $lvl ? 'is-current' : ($currentLvl > $lvl ? 'is-unlocked' : '') }}" @if($currentLvl == $lvl) aria-current="step" @endif><span class="rewards-milestone" aria-hidden="true">{{ $currentLvl > $lvl ? '✓' : 'L'.$lvl }}</span><span class="rewards-threshold">{{ __(':value1 XP', ['value1' => (number_format($xp))]) }}</span><span class="sr-only">{{ \App\Models\User::LEVEL_TITLES[$lvl] ?? 'Career level '.$lvl }}{{ $currentLvl >= $lvl ? __(', unlocked') : __(', not yet unlocked') }}</span></li>
                        @endforeach
                    </ol></section>
                    <section class="rewards-earning"><h3>{{ __('How you earn XP') }}</h3><p>{{ __('Progress comes from the updates you make in your tracker.') }}</p>
                        <div class="rewards-earn-row"><span class="rewards-earn-icon" aria-hidden="true"><i class="ph ph-plus"></i></span><div><strong>{{ __('Add an application') }}</strong><span>{{ __('Save a new item in your tracker') }}</span></div><span class="rewards-earn-xp">{{ __('+10 XP') }}</span></div>
                        <div class="rewards-earn-row"><span class="rewards-earn-icon" aria-hidden="true"><i class="ph ph-chats-circle"></i></span><div><strong>{{ __('Selection stage') }}</strong><span>{{ __('Move into an interview or assessment stage') }}</span></div><span class="rewards-earn-xp">{{ __('+50 XP') }}</span></div>
                        <div class="rewards-earn-row"><span class="rewards-earn-icon" aria-hidden="true"><i class="ph ph-check-circle"></i></span><div><strong>{{ __('Offering or accepted') }}</strong><span>{{ __('Record a positive outcome') }}</span></div><span class="rewards-earn-xp">{{ __('+100 XP') }}</span></div>
                    </section>
                </div>
                <footer class="rewards-footer"><p>{{ __('XP reflects your tracker activity.') }}</p><button type="button" class="account-dialog-button rewards-button--primary" @click="showGamificationInfo = false">{{ __('Got it') }}</button></footer>
            </section>
        </div>
    </template>
</div>
