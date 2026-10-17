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
            <h1>{{ __('Campaign history') }}</h1>
            <p>{{ __('A clear record of your email campaigns and sending results.') }}</p>
        </div>
        <div class="eb-header-actions">
            <a href="{{ route('admin.email-blast') }}" class="au-button au-button--primary">
                <i class="ph ph-plus" aria-hidden="true"></i>
                <span>{{ __('Compose email') }}</span>
            </a>
        </div>
    </section>

    <!-- Compact Symmetrical Metrics Grid -->
    <section class="eb-history-metrics" aria-label="{{ __('Campaign totals') }}">
        <div class="eb-metric-card">
            <span class="eb-metric-label">{{ __('Campaigns') }}</span>
            <strong class="eb-metric-value">{{ number_format($totalCampaigns) }}</strong>
            <small class="eb-metric-sub">{{ __('All recorded campaigns') }}</small>
        </div>
        <div class="eb-metric-card">
            <span class="eb-metric-label">{{ __('Recipients targeted') }}</span>
            <strong class="eb-metric-value eb-metric-value--purple">{{ number_format($totalTarget) }}</strong>
            <small class="eb-metric-sub">{{ __('Total recipients reached') }}</small>
        </div>
        <div class="eb-metric-card">
            <span class="eb-metric-label">{{ __('Sent successfully') }}</span>
            <strong class="eb-metric-value eb-metric-value--success">{{ number_format($totalSuccess) }}</strong>
            <small class="eb-metric-sub">{{ __('Confirmed SMTP deliveries') }}</small>
        </div>
        <div class="eb-metric-card">
            <span class="eb-metric-label">{{ __('Failed / unconfirmed') }}</span>
            <strong class="eb-metric-value {{ $totalFailed ? 'eb-metric-value--danger' : 'eb-metric-value--muted' }}">{{ number_format($totalFailed) }}</strong>
            <small class="eb-metric-sub">{{ __('Delivery errors logged') }}</small>
        </div>
    </section>

    <!-- Campaigns Table Card -->
    <section class="ai-panel eb-card">
        <header class="ai-panel-heading eb-card-heading">
            <div>
                <h2>
                    <span>{{ __('Campaign logs') }}</span>
                    <span class="eb-count-pill">{{ number_format($logs->total()) }}</span>
                </h2>
                <p>{{ __('Latest campaigns listed first with send-time delivery metrics.') }}</p>
            </div>
        </header>

        <!-- Filter Bar -->
        <form action="{{ route('admin.email-blast.history') }}" method="GET" class="au-filters eb-history-filters">
            <label class="au-search">
                <span class="sr-only">{{ __('Search template') }}</span>
                <i class="ph ph-magnifying-glass" aria-hidden="true"></i>
                <input type="search" name="search" placeholder="{{ __('Search campaign template…') }}" value="{{ $search }}">
            </label>
            <label>
                <span class="sr-only">{{ __('Audience') }}</span>
                <select name="audience">
                    <option value="all-segments">{{ __('All audiences') }}</option>
                    @foreach($audiences as $key => $label)
                        <option value="{{ $key }}" @selected($audience === $key)>{{ __($label) }}</option>
                    @endforeach
                </select>
            </label>
            <button class="au-button" type="submit">{{ __('Filter') }}</button>
            @if($search || $audience !== 'all-segments')
                <a href="{{ route('admin.email-blast.history') }}" class="au-clear eb-clear-purple">{{ __('Clear filters') }}</a>
            @endif
        </form>

        <!-- Table Scroll Container -->
        <div class="ai-log-scroll" tabindex="0" aria-label="{{ __('Campaign history table') }}">
            <table class="ai-log-table eb-history-table">
                <thead>
                    <tr>
                        <th scope="col">{{ __('Campaign') }}</th>
                        <th scope="col">{{ __('Audience') }}</th>
                        <th scope="col">{{ __('Sent / target') }}</th>
                        <th scope="col">{{ __('Failed / unconfirmed') }}</th>
                        <th scope="col">{{ __('When') }}</th>
                        <th scope="col" class="text-right"><span class="sr-only">{{ __('Details') }}</span></th>
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
                                    <span>{{ $log->created_at->locale(app()->getLocale())->translatedFormat('d M Y') }}</span>
                                    <small>{{ __(':value1 WIB', ['value1' => ($log->created_at->format('H:i'))]) }}</small>
                                </time>
                            </td>
                            <td class="text-right">
                                @if($log->failed_count && $log->failed_details)
                                    <button type="button" class="au-more eb-failure-btn" data-failures="{{ json_encode($log->failed_details) }}" data-campaign="{{ ucwords(str_replace('_',' ',$log->email_type)) }}" @click="failures = JSON.parse($el.dataset.failures); campaign = $el.dataset.campaign; open = true" aria-label="{{ __('View failures for :value1', ['value1' => (str_replace('_',' ',$log->email_type))]) }}">
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
                                    <h3>{{ $search || $audience !== 'all-segments' ? __('No matching campaigns') : __('No campaigns yet') }}</h3>
                                    <p>{{ $search || $audience !== 'all-segments' ? __('Try another search or clear your filters.') : __('Completed email campaigns will appear here.') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <footer class="au-directory-footer">
            <span>{{ __('Showing :value1–:value2 of :value3 campaigns', ['value1' => ($logs->firstItem() ?? 0), 'value2' => ($logs->lastItem() ?? 0), 'value3' => (number_format($logs->total()))]) }}</span>
            <div>{{ $logs->links() }}</div>
        </footer>
    </section>

    <!-- Failures Details Modal -->
    <template x-teleport="body">
        <div x-show="open" x-cloak x-dialog="open" data-dialog-close="open = false" role="dialog" aria-modal="true" tabindex="-1" aria-labelledby="campaign-failures-title" class="au-dialog-overlay">
            <section class="au-dialog eb-dialog">
                <header class="au-dialog-heading">
                    <div>
                        <h2 id="campaign-failures-title">{{ __('Delivery details') }}</h2>
                        <p x-text="campaign + @js(__(' · failed or unconfirmed sends'))"></p>
                    </div>
                    <button type="button" @click="open = false" class="au-dialog-close" aria-label="{{ __('Close delivery details') }}">
                        <i class="ph ph-x" aria-hidden="true"></i>
                    </button>
                </header>
                <div class="au-dialog-body">
                    <p class="eb-confirm-copy">{{ __('Review each result before sending another email.') }}</p>
                    <div class="eb-recipient-list">
                        <template x-for="(failure,index) in failures" :key="index">
                            <div class="eb-recipient-item eb-recipient-item--failed">
                                <strong x-text="failure.name || @js(__('Member'))"></strong>
                                <span x-text="failure.email"></span>
                                <p class="eb-failure-message" x-text="failure.error || @js(__('No error details were recorded.'))"></p>
                            </div>
                        </template>
                    </div>
                    <div class="au-dialog-actions">
                        <button type="button" @click="open = false" class="au-button">{{ __('Close') }}</button>
                    </div>
                </div>
            </section>
        </div>
    </template>
</div>
</x-admin-layout>
