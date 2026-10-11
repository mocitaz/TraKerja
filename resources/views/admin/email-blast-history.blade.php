<x-admin-layout>
@php
    $audiences = [
        'all' => 'All members',
        'verified' => 'Verified',
        'unverified' => 'Unverified',
        'premium' => 'Premium',
        'free' => 'Free',
        'new' => 'New members',
    ];
@endphp

<div class="email-workspace" x-data="{ open: false, failures: [], campaign: '' }">
    <!-- Header Section -->
    <section class="admin-welcome eb-header">
        <div>
            <span class="admin-eyebrow">Community messages</span>
            <h1>Campaign history</h1>
            <p>A clear record of your email campaigns and sending results.</p>
        </div>
        <div class="eb-header-actions">
            <a href="{{ route('admin.email-blast') }}" class="au-button au-button--primary">
                <i class="ph ph-plus" aria-hidden="true"></i>
                <span>Compose email</span>
            </a>
        </div>
    </section>

    <!-- Compact Symmetrical Metrics Grid -->
    <section class="eb-history-metrics" aria-label="Campaign totals">
        <div class="eb-metric-card">
            <span class="eb-metric-label">Campaigns</span>
            <strong class="eb-metric-value">{{ number_format($totalCampaigns) }}</strong>
            <small class="eb-metric-sub">All recorded campaigns</small>
        </div>
        <div class="eb-metric-card">
            <span class="eb-metric-label">Recipients targeted</span>
            <strong class="eb-metric-value eb-metric-value--purple">{{ number_format($totalTarget) }}</strong>
            <small class="eb-metric-sub">Total recipients reached</small>
        </div>
        <div class="eb-metric-card">
            <span class="eb-metric-label">Sent successfully</span>
            <strong class="eb-metric-value eb-metric-value--success">{{ number_format($totalSuccess) }}</strong>
            <small class="eb-metric-sub">Confirmed SMTP deliveries</small>
        </div>
        <div class="eb-metric-card">
            <span class="eb-metric-label">Failed / unconfirmed</span>
            <strong class="eb-metric-value {{ $totalFailed ? 'eb-metric-value--danger' : 'eb-metric-value--muted' }}">{{ number_format($totalFailed) }}</strong>
            <small class="eb-metric-sub">Delivery errors logged</small>
        </div>
    </section>

    <!-- Campaigns Table Card -->
    <section class="ai-panel eb-card">
        <header class="ai-panel-heading eb-card-heading">
            <div>
                <h2>
                    <span>Campaign logs</span>
                    <span class="eb-count-pill">{{ number_format($logs->total()) }}</span>
                </h2>
                <p>Latest campaigns listed first with send-time delivery metrics.</p>
            </div>
        </header>

        <!-- Filter Bar -->
        <form action="{{ route('admin.email-blast.history') }}" method="GET" class="au-filters eb-history-filters">
            <label class="au-search">
                <span class="sr-only">Search template</span>
                <i class="ph ph-magnifying-glass" aria-hidden="true"></i>
                <input type="search" name="search" placeholder="Search campaign template…" value="{{ $search }}">
            </label>
            <label>
                <span class="sr-only">Audience</span>
                <select name="audience">
                    <option value="all-segments">All audiences</option>
                    @foreach($audiences as $key => $label)
                        <option value="{{ $key }}" @selected($audience === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <button class="au-button" type="submit">Filter</button>
            @if($search || $audience !== 'all-segments')
                <a href="{{ route('admin.email-blast.history') }}" class="au-clear eb-clear-purple">Clear filters</a>
            @endif
        </form>

        <!-- Table Scroll Container -->
        <div class="ai-log-scroll" tabindex="0" aria-label="Campaign history table">
            <table class="ai-log-table eb-history-table">
                <thead>
                    <tr>
                        <th scope="col">Campaign</th>
                        <th scope="col">Audience</th>
                        <th scope="col">Sent / target</th>
                        <th scope="col">Failed / unconfirmed</th>
                        <th scope="col">When</th>
                        <th scope="col" class="text-right"><span class="sr-only">Details</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>
                                <strong class="ai-event-name">{{ ucwords(str_replace('_', ' ', $log->email_type)) }}</strong>
                            </td>
                            <td>
                                <span class="eb-audience-tag">{{ $audiences[$log->target_audience] ?? ucwords($log->target_audience) }}</span>
                            </td>
                            <td>
                                <span class="eb-sent-number">{{ number_format($log->success_count) }}</span>
                                <span class="eb-target-number"> / {{ number_format($log->total_target) }}</span>
                            </td>
                            <td>
                                @if($log->failed_count)
                                    <span class="eb-failed-number">{{ number_format($log->failed_count) }}</span>
                                @else
                                    <span class="eb-target-number">—</span>
                                @endif
                            </td>
                            <td>
                                <time datetime="{{ $log->created_at->toIso8601String() }}">
                                    <span>{{ $log->created_at->format('d M Y') }}</span>
                                    <small>{{ $log->created_at->format('H:i') }} WIB</small>
                                </time>
                            </td>
                            <td class="text-right">
                                @if($log->failed_count && $log->failed_details)
                                    <button type="button" class="au-more eb-failure-btn" data-failures="{{ json_encode($log->failed_details) }}" data-campaign="{{ ucwords(str_replace('_',' ',$log->email_type)) }}" @click="failures = JSON.parse($el.dataset.failures); campaign = $el.dataset.campaign; open = true" aria-label="View failures for {{ str_replace('_',' ',$log->email_type) }}">
                                        <i class="ph ph-warning-circle" aria-hidden="true"></i>
                                    </button>
                                @else
                                    <span class="eb-target-number">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="ai-empty">
                                    <h3>{{ $search || $audience !== 'all-segments' ? 'No matching campaigns' : 'No campaigns yet' }}</h3>
                                    <p>{{ $search || $audience !== 'all-segments' ? 'Try another search or clear your filters.' : 'Completed email campaigns will appear here.' }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <footer class="au-directory-footer">
            <span>Showing {{ $logs->firstItem() ?? 0 }}–{{ $logs->lastItem() ?? 0 }} of {{ number_format($logs->total()) }} campaigns</span>
            <div>{{ $logs->links() }}</div>
        </footer>
    </section>

    <!-- Failures Details Modal -->
    <template x-teleport="body">
        <div x-show="open" x-cloak x-dialog="open" data-dialog-close="open = false" role="dialog" aria-modal="true" tabindex="-1" aria-labelledby="campaign-failures-title" class="au-dialog-overlay">
            <section class="au-dialog eb-dialog">
                <header class="au-dialog-heading">
                    <div>
                        <h2 id="campaign-failures-title">Delivery details</h2>
                        <p x-text="campaign + ' · failed or unconfirmed sends'"></p>
                    </div>
                    <button type="button" @click="open = false" class="au-dialog-close" aria-label="Close delivery details">
                        <i class="ph ph-x" aria-hidden="true"></i>
                    </button>
                </header>
                <div class="au-dialog-body">
                    <p class="eb-confirm-copy">Review each result before sending another email.</p>
                    <div class="eb-recipient-list">
                        <template x-for="(failure,index) in failures" :key="index">
                            <div class="eb-recipient-item eb-recipient-item--failed">
                                <strong x-text="failure.name || 'Member'"></strong>
                                <span x-text="failure.email"></span>
                                <p class="eb-failure-message" x-text="failure.error || 'No error details were recorded.'"></p>
                            </div>
                        </template>
                    </div>
                    <div class="au-dialog-actions">
                        <button type="button" @click="open = false" class="au-button">Close</button>
                    </div>
                </div>
            </section>
        </div>
    </template>
</div>
</x-admin-layout>
