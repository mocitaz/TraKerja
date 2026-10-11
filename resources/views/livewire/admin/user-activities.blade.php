<div class="admin-users-workspace">
    <!-- Header -->
    <section class="admin-welcome">
        <div>
            <span class="admin-eyebrow">Workspace history</span>
            <h1>Activity Log</h1>
            <p>Review authentication events, application changes, and feature usage in one timeline.</p>
        </div>
        <button type="button" wire:click="$set('showSettingsModal', true)" class="au-button">
            <i class="ph ph-sliders-horizontal" aria-hidden="true"></i>
            Log Settings
        </button>
    </section>

    <!-- Activity Directory Panel -->
    <section class="au-directory" aria-labelledby="activity-heading">
        <div class="au-directory-heading">
            <div>
                <h2 id="activity-heading">
                    Recorded Activity <span>{{ number_format($activities->total()) }}</span>
                </h2>
                <p>Latest events first · {{ config('app.timezone') }}</p>
            </div>
            <span wire:loading wire:target="search,filterType,filterStatus,perPage,resetFilters,gotoPage,nextPage,previousPage" class="au-loading">
                <i class="ph ph-spinner ph-spin"></i> Updating activity…
            </span>
        </div>

        <!-- Filters Toolbar -->
        <div class="au-filters">
            <label class="au-search">
                <span class="sr-only">Search activity</span>
                <i class="ph ph-magnifying-glass" aria-hidden="true"></i>
                <input type="search" placeholder="Search members or activity description…" wire:model.live.debounce.300ms="search">
            </label>
            <label>
                <span class="sr-only">Activity type</span>
                <select wire:model.live="filterType">
                    <option value="all">All Events</option>
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
                <span class="sr-only">Activity status</span>
                <select wire:model.live="filterStatus">
                    <option value="all">All Statuses</option>
                    <option value="success">Success</option>
                    <option value="failed">Failed</option>
                    <option value="pending">Pending</option>
                </select>
            </label>
            <label class="au-page-size">
                <span class="sr-only">Rows per page</span>
                <select wire:model.live="perPage">
                    @foreach([15, 30, 50, 100] as $size)
                        <option value="{{ $size }}">{{ $size }} rows</option>
                    @endforeach
                </select>
            </label>
            @if($search || $filterType !== 'all' || $filterStatus !== 'all')
                <button type="button" wire:click="resetFilters" class="au-clear">
                    Reset filters <i class="ph ph-x" aria-hidden="true"></i>
                </button>
            @endif
        </div>

        <!-- Table matching /tracker density, style, font, and borders -->
        <div class="au-table-scroll" tabindex="0" aria-label="Activity Table">
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
                        <th scope="col">Person</th>
                        <th scope="col">Event & Action</th>
                        <th scope="col">Recorded At</th>
                        <th scope="col">Status</th>
                        <th scope="col" style="text-align: right;">Details</th>
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
                                    <div class="au-avatar-wrapper">
                                        @if($activity->user)
                                            <img src="{{ $activity->user->avatar_url }}" alt="{{ $activity->user->name }}" class="au-avatar-img" loading="lazy">
                                        @else
                                            <div class="au-avatar-img" style="display: grid; place-items: center; color: #887494; font-weight: 700; font-size: 13px;">
                                                <i class="ph ph-shield-check"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="au-member-info">
                                        <strong class="au-member-name">{{ $activity->user?->name ?? 'System / Guest' }}</strong>
                                        <span class="au-member-email">{{ $activity->user?->email ?? 'No linked account' }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Column 2: Event & Description -->
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 3px;">
                                    <div style="display: flex; align-items: center; gap: 6px;">
                                        <span class="au-chip au-chip--applied" style="font-size: 10px; padding: 2px 7px;">
                                            <i class="ph {{ $eventIcon }}"></i>
                                            {{ ucwords(str_replace('_', ' ', $activity->activity_type)) }}
                                        </span>
                                    </div>
                                    <p style="margin: 2px 0 0; font-size: 11px; line-height: 1.4; color: #5c5668; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $activity->description }}
                                    </p>
                                </div>
                            </td>

                            <!-- Column 3: Recorded At -->
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 2px;">
                                    <span style="font-size: 11px; font-weight: 600; color: #34313e; font-variant-numeric: tabular-nums;">
                                        {{ $activity->created_at->diffForHumans() }}
                                    </span>
                                    <span style="font-size: 10px; color: #767082;">
                                        {{ $activity->created_at->format('d M Y, H:i') }}
                                    </span>
                                </div>
                            </td>

                            <!-- Column 4: Status -->
                            <td>
                                @if($statusTone === 'success')
                                    <span class="au-plan-badge" style="background: #ecfdf5; color: #065f46; border: 1px solid #d1fae5;">
                                        <i class="ph ph-check-circle"></i> Success
                                    </span>
                                @elseif($statusTone === 'failed')
                                    <span class="au-plan-badge" style="background: #fbe8e1; color: #a35f47; border: 1px solid #f6d8cd;">
                                        <i class="ph ph-x-circle"></i> Failed
                                    </span>
                                @elseif($statusTone === 'pending')
                                    <span class="au-plan-badge" style="background: #fef3c7; color: #b45309; border: 1px solid #fde68a;">
                                        <i class="ph ph-clock"></i> Pending
                                    </span>
                                @else
                                    <span class="au-plan-badge au-plan-badge--free">
                                        {{ ucfirst($activity->status ?? 'Logged') }}
                                    </span>
                                @endif
                            </td>

                            <!-- Column 5: Details Action -->
                            <td>
                                <div style="display: flex; justify-content: flex-end;">
                                    <button type="button" class="au-manage" wire:click="showActivity({{ $activity->id }})" aria-label="View {{ str_replace('_',' ',$activity->activity_type) }} details">
                                        Details <i class="ph ph-arrow-up-right" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="au-empty">
                                    <div class="au-empty-icon"><i class="ph ph-activity"></i></div>
                                    <h3>No activity records found</h3>
                                    <p>{{ $search || $filterType !== 'all' || $filterStatus !== 'all' ? 'Try adjusting your search query or reset your active filters.' : 'New activity will appear here automatically when members use TraKerja.' }}</p>
                                    @if($search || $filterType !== 'all' || $filterStatus !== 'all')
                                        <button type="button" wire:click="resetFilters" class="au-button" style="margin-top: 12px;">
                                            Reset All Filters
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
            <span>Showing <strong>{{ $activities->firstItem() ?? 0 }}–{{ $activities->lastItem() ?? 0 }}</strong> of {{ number_format($activities->total()) }} events</span>
            <div>{{ $activities->links() }}</div>
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
                            <h2 id="activity-detail-title">Event Details</h2>
                            <p>{{ $selectedActivity->created_at->format('d M Y · H:i:s') }} · {{ config('app.timezone') }}</p>
                        </div>
                        <button type="button" wire:click="closeActivity" class="au-dialog-close" aria-label="Close event details">
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
                                    {{ $selectedActivity->user->isPremium() ? 'Premium' : 'Free' }}
                                </span>
                            @else
                                <div class="au-avatar-img" style="width: 42px; height: 42px; display: grid; place-items: center; color: #887494; font-size: 18px;">
                                    <i class="ph ph-shield-check"></i>
                                </div>
                                <div>
                                    <strong>System / Guest</strong>
                                    <span>No linked user account</span>
                                </div>
                            @endif
                        </div>

                        <!-- Details Grid -->
                        <dl class="au-account-details">
                            <div class="au-detail-tile">
                                <dt>Event Type</dt>
                                <dd>{{ ucwords(str_replace('_', ' ', $selectedActivity->activity_type)) }}</dd>
                            </div>
                            <div class="au-detail-tile">
                                <dt>Status</dt>
                                <dd style="text-transform: capitalize;">{{ $selectedActivity->status ?? 'Logged' }}</dd>
                            </div>
                            <div class="au-detail-tile">
                                <dt>IP Address</dt>
                                <dd>{{ $selectedActivity->ip_address ?: 'Not recorded' }}</dd>
                            </div>
                            <div class="au-detail-tile">
                                <dt>Recorded Time</dt>
                                <dd>{{ $selectedActivity->created_at->format('H:i:s T') }}</dd>
                            </div>
                        </dl>

                        <!-- Description Box -->
                        <div class="au-form-section" style="padding-top: 10px; border-top: 1px solid #edf0f4;">
                            <h3 style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.3px; color: #7b7487; margin-bottom: 6px;">Event Description</h3>
                            <div style="padding: 12px 14px; background: #fbf9fc; border: 1px solid #ede7f2; border-radius: 12px; font-size: 12.5px; line-height: 1.6; color: #37303f;">
                                {{ $selectedActivity->description }}
                            </div>
                        </div>

                        <!-- Technical Specs -->
                        <details class="au-form-section" style="padding-top: 10px; border-top: 1px solid #edf0f4;" x-data="{ copied: false }">
                            <summary style="cursor: pointer; font-size: 12px; font-weight: 600; color: #6d587c; padding: 4px 0;">
                                Technical Details (User Agent & Metadata)
                            </summary>
                            
                            @if($selectedActivity->user_agent)
                                <div style="margin-top: 10px;">
                                    <span style="font-size: 10.5px; font-weight: 600; color: #767082; text-transform: uppercase;">User Agent:</span>
                                    <p style="font-size: 11px; color: #565363; line-height: 1.5; margin: 4px 0; background: #f8f9fa; padding: 8px 10px; border-radius: 8px; border: 1px solid #eef0f4; word-break: break-all;">
                                        {{ $selectedActivity->user_agent }}
                                    </p>
                                </div>
                            @endif

                            @if($selectedActivity->metadata)
                                <div style="margin-top: 10px; position: relative;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                        <span style="font-size: 10.5px; font-weight: 600; color: #767082; text-transform: uppercase;">Payload Metadata:</span>
                                        <button type="button" @click="navigator.clipboard.writeText(JSON.stringify({{ json_encode($selectedActivity->metadata) }}, null, 2)); copied = true; setTimeout(() => copied = false, 2000)" class="au-clear" style="padding: 2px 8px; font-size: 10.5px;">
                                            <i class="ph" :class="copied ? 'ph-check' : 'ph-copy'"></i>
                                            <span x-text="copied ? 'Copied!' : 'Copy JSON'"></span>
                                        </button>
                                    </div>
                                    <pre style="margin: 0; font-size: 11px; background: #2f273b; color: #f4effa; padding: 12px; border-radius: 10px; overflow-x: auto; max-height: 220px; line-height: 1.5;">{{ json_encode($selectedActivity->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            @endif
                        </details>

                        <div class="au-dialog-actions" style="margin-top: 8px;">
                            <button type="button" wire:click="closeActivity" class="au-button">Close</button>
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
                            <h2 id="log-settings-title">Activity Retention Settings</h2>
                            <p>Configure how long audit trail records are retained in database.</p>
                        </div>
                        <button type="button" wire:click="$set('showSettingsModal', false)" class="au-dialog-close" aria-label="Close log settings">
                            <i class="ph ph-x" aria-hidden="true"></i>
                        </button>
                    </header>
                    <div class="au-dialog-body">
                        <form wire:submit="saveSettings" class="au-form">
                            <label for="log-retention">
                                Retention Period (Days)
                                <input id="log-retention" type="number" wire:model="pruneDays" min="1" max="3650" required placeholder="e.g. 90">
                                @error('pruneDays')<span class="au-field-error">{{ $message }}</span>@enderror
                            </label>
                            <p class="au-form-hint">Automated pruning cleans records older than this retention threshold. Saving does not purge active records immediately.</p>
                            <div class="au-form-actions">
                                <button type="button" wire:click="$set('showSettingsModal', false)" class="au-button">Cancel</button>
                                <button type="submit" class="au-button au-button--primary" wire:loading.attr="disabled" wire:target="saveSettings">
                                    <span wire:loading.remove wire:target="saveSettings">Save Retention</span>
                                    <span wire:loading wire:target="saveSettings">Saving…</span>
                                </button>
                            </div>
                        </form>

                        <section class="au-account-actions" style="margin-top: 8px;">
                            <h3 style="color: #be123c;">Manual Cleanup</h3>
                            <p class="au-form-hint" style="margin-bottom: 12px;">Manual purging immediately and permanently deletes activity records.</p>
                            <div class="au-account-action-buttons">
                                <button type="button" wire:click="cleanNow" wire:confirm="Permanently delete logs older than {{ $pruneDays }} days? This cannot be undone." class="au-button" wire:loading.attr="disabled" wire:target="cleanNow">
                                    <i class="ph ph-trash"></i>
                                    Delete Older Logs (>{{ $pruneDays }}d)
                                </button>
                                <button type="button" wire:click="cleanAll" wire:confirm="Permanently delete ALL activity logs? This action is irreversible." class="au-button au-button--danger" wire:loading.attr="disabled" wire:target="cleanAll">
                                    <i class="ph ph-warning"></i>
                                    Delete All Logs
                                </button>
                            </div>
                        </section>
                    </div>
                </section>
            </div>
        </template>
    @endif
</div>
