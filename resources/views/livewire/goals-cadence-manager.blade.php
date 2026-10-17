<div class="workspace-panel weekly-goals-panel" x-data="{}">
    @php
        $activeGoal = $this->currentGoal;
        $applied = $this->actualApplied;
        $followups = $this->followUpCount;
        $streak = $this->currentStreak;
        $goalMetrics = [
            ['label' => __('Applications'), 'actual' => $applied, 'target' => $targetAppliedWeekly, 'progress' => $activeGoal ? $this->appliedProgress : 0, 'color' => '#9473c4'],
            ['label' => __('Follow-ups'), 'actual' => $followups, 'target' => $targetFollowupWeekly, 'progress' => $activeGoal ? $this->followupProgress : 0, 'color' => '#45976c'],
        ];
    @endphp
    <div class="weekly-goals-header">
        <div>
            <h3>{{ $goalPeriod === 'monthly' ? __('Monthly goals') : __('Weekly goals') }}</h3>
            <p>{{ $activeGoal ? \Carbon\Carbon::parse($startDate)->locale(app()->getLocale())->translatedFormat('M j').' – '.\Carbon\Carbon::parse($endDate)->locale(app()->getLocale())->translatedFormat('M j') : __('A little progress, every week.') }}</p>
        </div>
        <button type="button" wire:click="openGoalModal" wire:loading.attr="disabled" wire:target="openGoalModal" class="weekly-goals-settings" aria-label="{{ __('Configure application and follow-up targets') }}" aria-haspopup="dialog">
            <i class="ph ph-sliders-horizontal" aria-hidden="true"></i>
        </button>
    </div>

    <div class="weekly-goals-metrics">
        @foreach($goalMetrics as $metric)
            <button type="button" wire:click="openGoalModal" wire:loading.attr="disabled" wire:target="openGoalModal" class="weekly-goal-metric" style="--goal-accent: {{ $metric['color'] }};" aria-label="{{ $metric['label'] }}: {{ $metric['actual'] }}{{ $activeGoal ? __(' of ').$metric['target'] : __(', target not set') }}. Configure targets" aria-haspopup="dialog">
                <span class="weekly-goal-ring">
                    <svg viewBox="0 0 64 64" aria-hidden="true" focusable="false">
                        <circle class="weekly-goal-ring-bg" cx="32" cy="32" r="27" fill="none" stroke="#e5e4eb" stroke-width="4" />
                        @if($metric['progress'] > 0)
                            <circle class="weekly-goal-arc" cx="32" cy="32" r="27" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" pathLength="100" stroke-dasharray="{{ $metric['progress'] }} 100" transform="rotate(-90 32 32)" />
                        @endif
                    </svg>
                    <strong>{{ $metric['actual'] }}</strong>
                </span>
                <span class="weekly-goal-detail">
                    <strong>{{ $metric['label'] }}</strong>
                    <span>{{ !$activeGoal ? __('Set a target') : ($metric['target'] == 0 ? __('No target set') : ($metric['actual'] >= $metric['target'] ? __('Target reached') : __('of ').$metric['target'].__(' this ').($goalPeriod === 'monthly' ? 'month' : 'week'))) }}</span>
                </span>
                <i class="ph {{ $activeGoal && $metric['target'] > 0 && $metric['actual'] >= $metric['target'] ? 'ph-check-circle' : 'ph-arrow-up-right' }} weekly-goal-action" aria-hidden="true"></i>
            </button>
        @endforeach
    </div>

    <div class="weekly-goals-footer">
        <div class="weekly-goal-streak {{ $streak > 0 ? 'is-active' : '' }}">
            <span class="weekly-goal-streak-icon"><i class="ph ph-fire" aria-hidden="true"></i></span>
            <div><strong>{{ __(':value1-day streak', ['value1' => ($streak)]) }}</strong><span>{{ $streak > 0 ? __('Keep the momentum.') : __('Start with one application.') }}</span></div>
        </div>
        <button type="button" wire:click="openGoalModal" wire:loading.attr="disabled" wire:target="openGoalModal" class="weekly-goals-edit" aria-haspopup="dialog">{{ $activeGoal ? __('Edit goals') : __('Set goals') }} <i class="ph ph-arrow-right" aria-hidden="true"></i></button>
    </div>

    <template x-teleport="body">
        <div role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showGoalModal"
             aria-labelledby="goal-dialog-title" aria-describedby="goal-dialog-description"
             x-show="$wire.showGoalModal" x-transition.opacity.duration.150ms
             class="goal-dialog-overlay" style="display: none;">
            <div class="goal-dialog" @click.away="$wire.showGoalModal = false">
                <header class="goal-dialog-header">
                    <div>
                        <h2 id="goal-dialog-title">{{ __('Configure goals') }}</h2>
                        <p id="goal-dialog-description">{{ __('Set a pace that works for you.') }}</p>
                    </div>
                    <button type="button" @click="$wire.showGoalModal = false" class="goal-dialog-close" aria-label="{{ __('Close goal settings') }}"><i class="ph ph-x" aria-hidden="true"></i></button>
                </header>
                <form wire:submit="setWeeklyGoals">
                    <div class="goal-dialog-body">
                        <div class="goal-period-label"><span>{{ __('Goal period') }}</span><span>{{ __('WIB') }}</span></div>
                        <div class="goal-period-switch" role="group" aria-label="{{ __('Goal period') }}">
                            @foreach(['weekly' => 'Weekly', 'monthly' => 'Monthly'] as $period => $label)
                                <button type="button" wire:click="$set('goalPeriod', '{{ $period }}')" aria-pressed="{{ $goalPeriod === $period ? 'true' : 'false' }}" class="{{ $goalPeriod === $period ? 'is-selected' : '' }}" wire:loading.attr="disabled" wire:target="goalPeriod,setWeeklyGoals">{{ __($label) }}</button>
                            @endforeach
                        </div>
                        <p class="goal-period-dates" aria-live="polite"><span><i class="ph ph-calendar-blank" aria-hidden="true"></i> {{ __(':value1 – :value2', ['value1' => (\Carbon\Carbon::parse($startDate)->locale(app()->getLocale())->translatedFormat('M j')), 'value2' => (\Carbon\Carbon::parse($endDate)->locale(app()->getLocale())->translatedFormat('M j, Y'))]) }}</span><span>{{ __('Current :value1', ['value1' => ($goalPeriod === 'monthly' ? 'month' : 'week')]) }}</span></p>
                        @error('goalPeriod') <p class="goal-field-error" role="alert">{{ $message }}</p> @enderror

                        <div class="goal-target-grid">
                            <div class="goal-target-field goal-target-applications">
                                <div class="goal-target-header">
                                    <div class="goal-target-icon goal-target-icon--app"><i class="ph-bold ph-paper-plane-tilt"></i></div>
                                    <div>
                                        <label for="goal-applications-target">{{ __('Applications') }}</label>
                                        <p id="goal-applications-help">{{ __('How many will you apply to?') }}</p>
                                    </div>
                                </div>
                                <div class="goal-target-input">
                                    <input id="goal-applications-target" type="number" min="1" step="1" required wire:model="targetAppliedWeekly" aria-describedby="goal-applications-help{{ $errors->has('targetAppliedWeekly') ? __(' goal-applications-error') : '' }}" aria-invalid="{{ $errors->has('targetAppliedWeekly') ? 'true' : 'false' }}">
                                    <span>/ {{ $goalPeriod === 'monthly' ? 'month' : 'week' }}</span>
                                </div>
                                @error('targetAppliedWeekly') <p id="goal-applications-error" class="goal-field-error" role="alert">{{ $message }}</p> @enderror
                            </div>
                            <div class="goal-target-field goal-target-followups">
                                <div class="goal-target-header">
                                    <div class="goal-target-icon goal-target-icon--follow"><i class="ph-bold ph-arrows-clockwise"></i></div>
                                    <div>
                                        <label for="goal-followups-target">{{ __('Follow-ups') }}</label>
                                        <p id="goal-followups-help">{{ __('Use 0 to leave this target off.') }}</p>
                                    </div>
                                </div>
                                <div class="goal-target-input">
                                    <input id="goal-followups-target" type="number" min="0" step="1" required wire:model="targetFollowupWeekly" aria-describedby="goal-followups-help{{ $errors->has('targetFollowupWeekly') ? __(' goal-followups-error') : '' }}" aria-invalid="{{ $errors->has('targetFollowupWeekly') ? 'true' : 'false' }}">
                                    <span>/ {{ $goalPeriod === 'monthly' ? 'month' : 'week' }}</span>
                                </div>
                                @error('targetFollowupWeekly') <p id="goal-followups-error" class="goal-field-error" role="alert">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="goal-dialog-hint">
                            <i class="ph ph-info" aria-hidden="true"></i>
                            <span>{{ __('You can adjust your targets anytime. Saving updates the goals for this period.') }}</span>
                        </div>
                        @error('startDate') <p class="goal-field-error" role="alert">{{ $message }}</p> @enderror
                        @error('endDate') <p class="goal-field-error" role="alert">{{ $message }}</p> @enderror
                    </div>
                    <footer class="goal-dialog-footer">
                        <button type="button" @click="$wire.showGoalModal = false" class="goal-dialog-cancel">{{ __('Cancel') }}</button>
                        <button type="submit" class="goal-dialog-save" wire:loading.attr="disabled" wire:target="setWeeklyGoals,goalPeriod">
                            <span wire:loading.remove wire:target="setWeeklyGoals">{{ __('Save goals') }}</span>
                            <span wire:loading wire:target="setWeeklyGoals" role="status">{{ __('Saving...') }}</span>
                        </button>
                    </footer>
                </form>
            </div>
        </div>
    </template>
</div>
