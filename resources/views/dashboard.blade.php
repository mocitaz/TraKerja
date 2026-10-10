<x-app-layout>
    <div class="dashboard-workspace">
        <div class="dashboard-container">
            
            @include('dashboard.welcome')

            <style>
                .custom-scrollbar::-webkit-scrollbar {
                    width: 4px;
                }
                .custom-scrollbar::-webkit-scrollbar-track {
                    background: transparent;
                }
                .custom-scrollbar::-webkit-scrollbar-thumb {
                    background: #e4e4e7;
                    border-radius: 4px;
                }
                .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                    background: #d4d4d8;
                }
            </style>

            <!-- Dashboard Layout Main Area -->
            <!-- Recent applications and overview -->
            <div class="dashboard-main-layout">
            <livewire:dashboard-next-moves />
            <div class="dashboard-layout-row">
                <!-- Left: Recent Applications (col-span 2) -->
                <div class="dashboard-recent">
                    <div class="workspace-panel h-full flex flex-col justify-between">
                        <div>
                            <header class="dashboard-recent-header">
                                <h3>Recent applications</h3>
                                <a href="{{ route('tracker') }}" wire:navigate>View all <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>
                            </header>

                            @if($recentApplications->isEmpty())
                                <div class="flex flex-col items-center justify-center py-8 text-center bg-zinc-50/30 rounded-lg border border-dashed border-zinc-200/80">
                                    <div class="w-10 h-10 bg-zinc-100 rounded-md flex items-center justify-center text-zinc-400 mb-2.5">
                                        <i class="ph ph-briefcase text-lg"></i>
                                    </div>
                                    <h4 class="text-sm font-semibold text-zinc-700">No applications yet</h4>
                                    <p class="text-xs text-ui-muted mt-1 max-w-[260px]">Add your first application or explore jobs to get started.</p>
                                    <button onclick="openJobModal()" class="mt-3 flex items-center gap-1.5 px-2.5 py-1 bg-zinc-900 hover:bg-zinc-800 text-white text-[10px] font-bold rounded-md transition-all active:scale-95">
                                        <i class="ph ph-plus text-xs"></i>
                                        <span>Add application</span>
                                    </button>
                                </div>
                            @else
                                <ul class="dashboard-application-cards" aria-label="Recent applications">
                                    @foreach($recentApplications as $job)
                                        <li>
                                            <a href="{{ route('jobs.show', $job) }}" wire:navigate class="dashboard-application-card" aria-label="{{ $job->position }} at {{ $job->company_name }}: application details">
                                                <span class="dashboard-application-status">{{ $job->application_status }}</span>
                                                <h4>{{ $job->position }}</h4>
                                                <p class="dashboard-application-company">{{ $job->company_name }}</p>
                                                <span class="dashboard-application-date">{{ $job->application_date ? 'Applied '.$job->application_date->format('d M Y') : 'Application date not set' }}</span>
                                                <div class="dashboard-application-footer">
                                                    <div>
                                                        <span class="dashboard-application-stage-label">Current stage</span>
                                                        <strong class="dashboard-application-stage">{{ $job->recruitment_stage ?: 'Not set' }}</strong>
                                                    </div>
                                                    <i class="ph ph-arrow-up-right" aria-hidden="true"></i>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>

                <section class="dashboard-overview" aria-labelledby="application-overview-title">
                    @php
                        $overviewMetrics = [
                            ['label' => 'In progress', 'count' => $onProcessCount, 'note' => 'Active applications', 'color' => '#6bade0', 'radius' => 61],
                            ['label' => 'Offers & accepted', 'count' => $offeringAcceptedCount, 'note' => 'Offers and positive outcomes', 'color' => '#a98bd2', 'radius' => 82],
                            ['label' => 'Rejected', 'count' => $declinedCount, 'note' => 'Including archived applications', 'color' => '#e78766', 'radius' => 40],
                        ];
                    @endphp
                    <div class="dashboard-overview-header">
                        <h3 id="application-overview-title">Application overview</h3>
                        <span class="dashboard-overview-total"><span>Total applications</span><strong>{{ $totalTrackedApplications }}</strong></span>
                    </div>
                    <div class="dashboard-overview-body">
                        <dl class="dashboard-overview-metrics">
                            @foreach($overviewMetrics as $metric)
                                <div>
                                    <dt><span style="background: {{ $metric['color'] }}" aria-hidden="true"></span>{{ $metric['label'] }}</dt>
                                    <dd><strong>{{ $metric['count'] }}</strong><span>{{ $metric['note'] }}</span></dd>
                                </div>
                            @endforeach
                        </dl>
                        <svg class="dashboard-overview-rings" viewBox="0 0 200 200" aria-hidden="true" focusable="false">
                            @foreach($overviewMetrics as $metric)
                                <circle cx="100" cy="100" r="{{ $metric['radius'] }}" fill="none" stroke="#d0dce5" stroke-width="12" />
                                @if($metric['count'] > 0 && $totalTrackedApplications > 0)
                                    <circle cx="100" cy="100" r="{{ $metric['radius'] }}" fill="none" stroke="{{ $metric['color'] }}" stroke-width="12" stroke-linecap="round" pathLength="100" stroke-dasharray="{{ min(100, $metric['count'] / $totalTrackedApplications * 100) }} 100" transform="rotate(-90 100 100)" />
                                @endif
                            @endforeach
                            <text x="100" y="98" text-anchor="middle" class="overview-ring-total">{{ $totalTrackedApplications }}</text>
                            <text x="100" y="114" text-anchor="middle" class="overview-ring-label">tracked</text>
                        </svg>
                    </div>
                    <p class="dashboard-overview-note">Each ring is measured against all applications. Offers can also be in progress.</p>
                </section>
            </div>

            <!-- Application activity and weekly goals -->
            <div class="dashboard-layout-row">
                <livewire:dashboard-activity />

            </div>

            <div class="dashboard-goals dashboard-next-goals"><livewire:goals-cadence-manager /></div>
            </div>
        </div>
    </div>

    @push('modals')
    <x-job-application-modal />
    @endpush

    @push('styles')
    <style>
        @keyframes modalIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        .animate-modal-in {
            animation: modalIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
    @endpush


</x-app-layout>
