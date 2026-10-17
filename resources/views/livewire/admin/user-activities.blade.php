<div class="admin-users-workspace">
    <!-- Header -->
    <section class="admin-welcome">
        <div>
            <h1>{{ __('Activity Log') }}</h1>
            <p>{{ __('Review authentication events, application changes, and feature usage in one timeline.') }}</p>
        </div>
        <button type="button" wire:click="$set('showSettingsModal', true)" class="au-button">
            <i class="ph ph-sliders-horizontal" aria-hidden="true"></i>
            {{ __('Log Settings') }}
        </button>
    </section>

    <!-- Activity Directory Panel -->
    <section class="au-directory" aria-labelledby="activity-heading">
        <div class="au-directory-heading">
            <div>
                <h2 id="activity-heading">
                    {{ __('Recorded Activity') }} <span>{{ number_format($activities->total()) }}</span>
                </h2>
                <p>{{ __('Latest events first · :value1', ['value1' => (config('app.timezone'))]) }}</p>
            </div>
            <span wire:loading wire:target="search,filterType,filterStatus,perPage,resetFilters,gotoPage,nextPage,previousPage" class="au-loading">
                <i class="ph ph-spinner ph-spin"></i> {{ __('Updating activity…') }}
            </span>
        </div>

        <!-- Filters Toolbar -->
        <div class="au-filters">
            <label class="au-search">
                <span class="sr-only">{{ __('Search activity') }}</span>
                <i class="ph ph-magnifying-glass" aria-hidden="true"></i>
                <input type="search" placeholder="{{ __('Search members or activity description…') }}" wire:model.live.debounce.300ms="search">
            </label>
            <label>
                <span class="sr-only">{{ __('Activity type') }}</span>
                <select wire:model.live="filterType">
                    <option value="all">{{ __('All Events') }}</option>
                    @foreach($groupedTypes as $category => $types)
                        <optgroup label="{{ $category }}">
                            @foreach($types as $type)
                                <option value="{{ $type }}">{{ ucwords(str_replace('_',' ',$type)) }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </label>
            <label>
                <span class="sr-only">{{ __('Activity status') }}</span>
                <select wire:model.live="filterStatus">
                    <option value="all">{{ __('All Statuses') }}</option>
                    <option value="success">{{ __('Success') }}</option>
                    <option value="failed">{{ __('Failed') }}</option>
                    <option value="pending">{{ __('Pending') }}</option>
                </select>
            </label>
            <label class="au-page-size">
                <span class="sr-only">{{ __('Rows per page') }}</span>
                <select wire:model.live="perPage">
                    @foreach([15, 30, 50, 100] as $size)
                        <option value="{{ $size }}">{{ __(':value1 rows', ['value1' => ($size)]) }}</option>
                    @endforeach
                </select>
            </label>
            @if($search || $filterType !== 'all' || $filterStatus !== 'all')
                <button type="button" wire:click="resetFilters" class="au-clear">
                    {{ __('Reset filters') }} <i class="ph ph-x" aria-hidden="true"></i>
                </button>
            @endif
        </div>

        <!-- Table matching /tracker density, style, font, and borders -->
        <div class="au-table-scroll" tabindex="0" aria-label="{{ __('Activity Table') }}">
            <table class="au-table">
                <colgroup>
                    <col style="width: 25%;">
                    <col style="width: 37%;">
                    <col style="width: 14%;">
                    <col style="width: 12%;">
                    <col style="width: 12%;">
                </colgroup>
                <thead>
                    <tr>
                        <th scope="col">{{ __('Person') }}</th>
                        <th scope="col" class="text-center" style="text-align: center;">{{ __('Event & Action') }}</th>
                        <th scope="col" class="text-center" style="text-align: center;">{{ __('Recorded At') }}</th>
                        <th scope="col" class="text-center" style="text-align: center;">{{ __('Status') }}</th>
                        <th scope="col" class="text-center" style="text-align: center;">{{ __('Details') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                        @php
                            $eventIcon = match(true) {
                                str_contains($activity->activity_type, 'login') || str_contains($activity->activity_type, 'password') => 'ph-key',
                                str_contains($activity->activity_type, 'register') => 'ph-user-plus',
                                str_contains($activity->activity_type, 'job') || str_contains($activity->activity_type, 'interview') => 'ph-briefcase',
                                str_contains($activity->activity_type, 'cv') => 'ph-file-text',
                                str_contains($activity->activity_type, 'ai') || str_contains($activity->activity_type, 'photo') => 'ph-sparkle',
                                str_contains($activity->activity_type, 'premium') || str_contains($activity->activity_type, 'top_up') => 'ph-crown',
                                default => 'ph-activity',
                            };
                            $statusTone = match(strtolower($activity->status ?? '')) {
                                'success' => 'success',
                                'failed', 'error' => 'failed',
                                'pending' => 'pending',
                                default => 'other',
                            };
                        @endphp
                        <tr wire:key="activity-{{ $activity->id }}">
                            <!-- Column 1: Person -->
                            <td>
                                <div class="au-member">
                                    @if($activity->user)
                                        <a href="{{ route('admin.users.show', $activity->user) }}" wire:navigate class="au-avatar-wrapper au-member-avatar-link" title="{{ __('View :value1 profile', ['value1' => ($activity->user->name)]) }}">
                                            <img src="{{ $activity->user->avatar_url }}" alt="{{ $activity->user->name }}" class="au-avatar-img" loading="lazy">
                                        </a>
                                        <div class="au-member-info">
                                            <a href="{{ route('admin.users.show', $activity->user) }}" wire:navigate class="au-member-name au-member-name-link" title="{{ __('View :value1 profile', ['value1' => ($activity->user->name)]) }}">
                                                {{ $activity->user->name }}
                                            </a>
                                            <span class="au-member-email">{{ $activity->user->email }}</span>
                                        </div>
                                    @else
                                        <div class="au-avatar-wrapper">
                                            <div class="au-avatar-img au-system-avatar-icon">
                                                <i class="ph ph-shield-check"></i>
                                            </div>
                                        </div>
                                        <div class="au-member-info">
                                            <strong class="au-member-name">{{ __('System / Guest') }}</strong>
                                            <span class="au-member-email">{{ __('No linked account') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Column 2: Event & Description (Inline / Sebaris) -->
                            <td class="text-center" style="text-align: center; vertical-align: middle;">
                                <div class="au-activity-event-cell">
                                    <span class="au-chip au-chip--applied au-activity-chip">
                                        <i class="ph {{ $eventIcon }}"></i>
                                        {{ ucwords(str_replace('_', ' ', $activity->activity_type)) }}
                                    </span>
                                    <span class="au-activity-desc">
                                        {{ $activity->description }}
                                    </span>
                                </div>
                            </td>

                            <!-- Column 3: Recorded At (Center) -->
                            <td class="text-center" style="text-align: center; vertical-align: middle;">
                                <div class="au-activity-time-cell">
                                    <span class="au-activity-time-human">
                                        {{ $activity->created_at->diffForHumans() }}
                                    </span>
                                    <span class="au-activity-time-exact">
                                        {{ $activity->created_at->locale(app()->getLocale())->translatedFormat('d M Y, H:i') }}
                                    </span>
                                </div>
                            </td>

                            <!-- Column 4: Status -->
                            <td class="text-center" style="text-align: center; vertical-align: middle;">
                                <div style="display: flex; justify-content: center; align-items: center;">
                                    @if($statusTone === 'success')
                                        <span class="au-plan-badge au-badge-status--success" style="margin: 0;">
                                            <i class="ph ph-check-circle"></i> {{ __('Success') }}
                                        </span>
                                    @elseif($statusTone === 'failed')
                                        <span class="au-plan-badge au-badge-status--failed" style="margin: 0;">
                                            <i class="ph ph-x-circle"></i> {{ __('Failed') }}
                                        </span>
                                    @elseif($statusTone === 'pending')
                                        <span class="au-plan-badge au-badge-status--pending" style="margin: 0;">
                                            <i class="ph ph-clock"></i> {{ __('Pending') }}
                                        </span>
                                    @else
                                        <span class="au-plan-badge au-plan-badge--free" style="margin: 0;">
                                            {{ ucfirst($activity->status ?? 'Logged') }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Column 5: Details Action -->
                            <td class="text-center" style="text-align: center; vertical-align: middle;">
                                <div style="display: flex; justify-content: center; align-items: center;">
                                    <button type="button" class="au-manage" wire:click="showActivity({{ $activity->id }})" aria-label="{{ __('View :value1 details', ['value1' => (str_replace('_',' ',$activity->activity_type))]) }}">
                                        {{ __('Details') }} <i class="ph ph-arrow-up-right" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="au-empty">
                                    <div class="au-empty-icon"><i class="ph ph-activity"></i></div>
                                    <h3>{{ __('No activity records found') }}</h3>
                                    <p>{{ $search || $filterType !== 'all' || $filterStatus !== 'all' ? __('Try adjusting your search query or reset your active filters.') : __('New activity will appear here automatically when members use TraKerja.') }}</p>
                                    @if($search || $filterType !== 'all' || $filterStatus !== 'all')
                                        <button type="button" wire:click="resetFilters" class="au-button" style="margin-top: 12px;">
                                            {{ __('Reset All Filters') }}
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <footer class="au-directory-footer">
            <span>{{ __('Showing') }} <strong>{{ $activities->firstItem() ?? 0 }}–{{ $activities->lastItem() ?? 0 }}</strong> {{ __('of :value1 events', ['value1' => (number_format($activities->total()))]) }}</span>
            <div class="au-pagination-wrap">{{ $activities->links() }}</div>
        </footer>
    </section>

    <!-- =====================================================================
         MODAL 1: Activity Details ($selectedActivity)
         ===================================================================== -->
    @if($selectedActivity)
        <template x-teleport="body">
            <div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.selectedActivityId" data-dialog-close="$wire.closeActivity()" aria-labelledby="activity-detail-title" class="au-dialog-overlay">
                <section class="au-dialog">
                    <header class="au-dialog-heading">
                        <div>
                            <h2 id="activity-detail-title">{{ __('Event Details') }}</h2>
                            <p>{{ $selectedActivity->created_at->locale(app()->getLocale())->translatedFormat('d M Y · H:i:s') }} · {{ config('app.timezone') }}</p>
                        </div>
                        <button type="button" wire:click="closeActivity" class="au-dialog-close" aria-label="{{ __('Close event details') }}">
                            <i class="ph ph-x" aria-hidden="true"></i>
                        </button>
                    </header>
                    <div class="au-dialog-body">
                        <!-- Person Banner -->
                        <div class="au-dialog-person">
                            @if($selectedActivity->user)
                                <img src="{{ $selectedActivity->user->avatar_url }}" alt="{{ $selectedActivity->user->name }}">
                                <div>
                                    <strong>{{ $selectedActivity->user->name }}</strong>
                                    <span>{{ $selectedActivity->user->email }}</span>
                                </div>
                                <span class="au-plan-badge {{ $selectedActivity->user->isPremium() ? 'au-plan-badge--premium' : 'au-plan-badge--free' }}">
                                    {{ $selectedActivity->user->isPremium() ? __('Premium') : __('Free') }}
                                </span>
                            @else
                                <div class="au-avatar-img au-system-avatar-icon au-system-avatar-icon--lg">
                                    <i class="ph ph-shield-check"></i>
                                </div>
                                <div>
                                    <strong>{{ __('System / Guest') }}</strong>
                                    <span>{{ __('No linked user account') }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Details Grid -->
                        <dl class="au-account-details">
                            <div class="au-detail-tile">
                                <dt>{{ __('Event Type') }}</dt>
                                <dd>{{ ucwords(str_replace('_', ' ', $selectedActivity->activity_type)) }}</dd>
                            </div>
                            <div class="au-detail-tile">
                                <dt>{{ __('Status') }}</dt>
                                <dd style="text-transform: capitalize;">{{ $selectedActivity->status ?? 'Logged' }}</dd>
                            </div>
                            <div class="au-detail-tile">
                                <dt>{{ __('IP Address') }}</dt>
                                <dd>{{ $selectedActivity->ip_address ?: __('Not recorded') }}</dd>
                            </div>
                            <div class="au-detail-tile">
                                <dt>{{ __('Recorded Time') }}</dt>
                                <dd>{{ $selectedActivity->created_at->format('H:i:s T') }}</dd>
                            </div>
                        </dl>

                        <!-- Description Box -->
                        <div class="au-form-section au-event-desc-section">
                            <h3 class="au-event-desc-heading">{{ __('Event Description') }}</h3>
                            <div class="au-event-desc-box">
                                {{ $selectedActivity->description }}
                            </div>
                        </div>

                        <!-- Technical Specs -->
                        <details class="au-form-section au-tech-details-section" x-data="{ copied: false }">
                            <summary class="au-tech-details-summary">
                                {{ __('Technical Details (User Agent & Metadata)') }}
                            </summary>

                            @if($selectedActivity->user_agent)
                                <div style="margin-top: 10px;">
                                    <span class="au-tech-label">{{ __('User Agent:') }}</span>
                                    <p class="au-user-agent-box">
                                        {{ $selectedActivity->user_agent }}
                                    </p>
                                </div>
                            @endif

                            @if($selectedActivity->metadata)
                                <div style="margin-top: 10px; position: relative;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                        <span class="au-tech-label">{{ __('Payload Metadata:') }}</span>
                                        <button type="button" @click="navigator.clipboard.writeText(JSON.stringify({{ json_encode($selectedActivity->metadata) }}, null, 2)); copied = true; setTimeout(() => copied = false, 2000)" class="au-clear" style="padding: 2px 8px; font-size: 10.5px;">
                                            <i class="ph" :class="copied ? 'ph-check' : 'ph-copy'"></i>
                                            <span x-text="copied ? @js(__('Copied!')) : @js(__('Copy JSON'))"></span>
                                        </button>
                                    </div>
                                    <pre class="au-json-payload-pre">{{ json_encode($selectedActivity->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            @endif
                        </details>

                        <div class="au-dialog-actions" style="margin-top: 8px;">
                            <button type="button" wire:click="closeActivity" class="au-button">{{ __('Close') }}</button>
                        </div>
                    </div>
                </section>
            </div>
        </template>
    @endif

    <!-- =====================================================================
         MODAL 2: Log Retention Settings ($showSettingsModal)
         ===================================================================== -->
    @if($showSettingsModal)
        <template x-teleport="body">
            <div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showSettingsModal" data-dialog-close="$wire.set('showSettingsModal', false)" aria-labelledby="log-settings-title" class="au-dialog-overlay">
                <section class="au-dialog au-dialog--small">
                    <header class="au-dialog-heading">
                        <div>
                            <h2 id="log-settings-title">{{ __('Activity Retention Settings') }}</h2>
                            <p>{{ __('Configure how long audit trail records are retained in database.') }}</p>
                        </div>
                        <button type="button" wire:click="$set('showSettingsModal', false)" class="au-dialog-close" aria-label="{{ __('Close log settings') }}">
                            <i class="ph ph-x" aria-hidden="true"></i>
                        </button>
                    </header>
                    <div class="au-dialog-body">
                        <form wire:submit="saveSettings" class="au-form">
                            <label for="log-retention">
                                {{ __('Retention Period (Days)') }}
                                <input id="log-retention" type="number" wire:model="pruneDays" min="1" max="3650" required placeholder="{{ __('e.g. 90') }}">
                                @error('pruneDays')<span class="au-field-error">{{ $message }}</span>@enderror
                            </label>
                            <p class="au-form-hint">{{ __('Automated pruning cleans records older than this retention threshold. Saving does not purge active records immediately.') }}</p>
                            <div class="au-form-actions">
                                <button type="button" wire:click="$set('showSettingsModal', false)" class="au-button">{{ __('Cancel') }}</button>
                                <button type="submit" class="au-button au-button--primary" wire:loading.attr="disabled" wire:target="saveSettings">
                                    <span wire:loading.remove wire:target="saveSettings">{{ __('Save Retention') }}</span>
                                    <span wire:loading wire:target="saveSettings">{{ __('Saving…') }}</span>
                                </button>
                            </div>
                        </form>

                        <section class="au-account-actions" style="margin-top: 8px;">
                            <h3 class="au-danger-heading">{{ __('Manual Cleanup') }}</h3>
                            <p class="au-form-hint" style="margin-bottom: 12px;">{{ __('Manual purging immediately and permanently deletes activity records.') }}</p>
                            <div class="au-account-action-buttons">
                                <button type="button" wire:click="cleanNow" wire:confirm="Permanently delete logs older than {{ $pruneDays }} days? This cannot be undone." class="au-button" wire:loading.attr="disabled" wire:target="cleanNow">
                                    <i class="ph ph-trash"></i>
                                    {{ __('Delete Older Logs (more than :value1 days)', ['value1' => ($pruneDays)]) }}
                                </button>
                                <button type="button" wire:click="cleanAll" wire:confirm="Permanently delete ALL activity logs? This action is irreversible." class="au-button au-button--danger" wire:loading.attr="disabled" wire:target="cleanAll">
                                    <i class="ph ph-warning"></i>
                                    {{ __('Delete All Logs') }}
                                </button>
                            </div>
                        </section>
                    </div>
                </section>
            </div>
        </template>
    @endif
</div>
