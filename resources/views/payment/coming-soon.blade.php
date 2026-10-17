<x-app-layout>
    <div class="payment-workspace workspace-page-frame">
        <header class="pay-heading">
            <div>
                <a class="pay-back" href="{{ route('membership.index') }}" wire:navigate>{{ __('← Membership & credits') }}</a>
                <h1>{{ __('TraKerja Premium') }}</h1>
                <p>{{ __('Advanced career analytics, automated application tracking, and priority AI features.') }}</p>
            </div>
            <a class="pay-link" href="{{ route('tracker') }}" wire:navigate>{{ __('Back to tracker →') }}</a>
        </header>

        {{-- Hero Card --}}
        <section class="pay-coming-hero">
            <div class="pay-coming-hero-top">
                <div class="pay-coming-icon" aria-hidden="true">
                    <i class="ph ph-crown"></i>
                </div>
                <div class="pay-coming-hero-copy">
                    <span class="pay-tag">{{ __('COMING SOON') }}</span>
                    <h2>{{ __('TraKerja Premium is almost here.') }}</h2>
                    <p>{{ __('We\'re crafting a world-class upgrade experience — secure payments, flexible plans, and everything you need to accelerate your career.') }}</p>

                    <div class="pay-progress-wrap">
                        <div class="pay-progress-label">
                            <span>{{ __('Development progress') }}</span>
                            <span>{{ __('65% Complete') }}</span>
                        </div>
                        <div class="pay-progress-track">
                            <div class="pay-progress-bar"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pay-coming-hero-stats">
                <div class="pay-coming-hero-stat">
                    <strong>{{ __('Q2') }}</strong>
                    <span>{{ __('Target') }}</span>
                </div>
                <div class="pay-coming-hero-sep" aria-hidden="true"></div>
                <div class="pay-coming-hero-stat">
                    <strong>3</strong>
                    <span>{{ __('Phases') }}</span>
                </div>
                <div class="pay-coming-hero-sep" aria-hidden="true"></div>
                <div class="pay-coming-hero-stat">
                    <strong>6+</strong>
                    <span>{{ __('Features') }}</span>
                </div>
            </div>
        </section>

        {{-- 2-Column Symmetrical Grid --}}
        <div class="pay-checkout-grid">
            {{-- Left: Features Included --}}
            <section class="pay-panel">
                <div class="pay-offer-heading">
                    <h2>{{ __('What\'s included') }}</h2>
                    <span class="pay-tag pay-tag--quiet">{{ __('Feature set') }}</span>
                </div>
                <p class="pay-small" style="margin-top: 6px;">{{ __('Everything designed to give your job search an unfair advantage.') }}</p>

                @php
                    $features = [
                        ['ph ph-shield-check', '#38654d', '#e1ece8', 'Secure Gateway', 'Bank-level encrypted checkout'],
                        ['ph ph-credit-card', '#42729b', '#e8f1fb', 'Multi Payment', 'Virtual Accounts & QRIS options'],
                        ['ph ph-clock-counter-clockwise', '#6d4882', '#eedef5', 'Transaction History', 'Clear receipts & status tracking'],
                        ['ph ph-sparkle', '#a35f47', '#fbe8e1', 'AI Career Quota', 'Resume Analyzer & Cover Letters'],
                        ['ph ph-calendar-check', '#38654d', '#e1ece8', 'Automated Tracking', 'Reminders & calendar integration'],
                        ['ph ph-lightning', '#6d4882', '#eedef5', 'Early Access', 'First access to new platform tools'],
                    ];
                @endphp

                <div class="pay-coming-features">
                    @foreach($features as [$icon, $color, $bg, $title, $desc])
                        <div class="pay-coming-feature">
                            <div class="pay-coming-feature-icon" style="background: {{ $bg }}; color: {{ $color }};" aria-hidden="true">
                                <i class="{{ $icon }}"></i>
                            </div>
                            <div>
                                <strong>{{ $title }}</strong>
                                <span>{{ $desc }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- Right: Development Roadmap --}}
            <aside class="pay-panel">
                <div class="pay-offer-heading">
                    <h2>{{ __('Roadmap') }}</h2>
                    <span class="pay-tag pay-tag--quiet">{{ __('3 phases') }}</span>
                </div>
                <p class="pay-small" style="margin-top: 6px;">{{ __('Transparent development milestones.') }}</p>

                <div class="pay-coming-roadmap">
                    <div class="pay-roadmap-step is-done">
                        <div class="pay-roadmap-dot" aria-hidden="true">✓</div>
                        <div class="pay-roadmap-info">
                            <div>
                                <strong>{{ __('Planning & Architecture') }}</strong>
                                <p>{{ __('System design and integration specs') }}</p>
                            </div>
                            <span class="pay-badge pay-badge--success">{{ __('Done') }}</span>
                        </div>
                    </div>

                    <div class="pay-roadmap-step is-active">
                        <div class="pay-roadmap-dot" aria-hidden="true">2</div>
                        <div class="pay-roadmap-info">
                            <div>
                                <strong>{{ __('Payment Infrastructure') }}</strong>
                                <p>{{ __('Gateway webhooks & security audit') }}</p>
                            </div>
                            <span class="pay-badge pay-badge--pending">{{ __('Active') }}</span>
                        </div>
                    </div>

                    <div class="pay-roadmap-step">
                        <div class="pay-roadmap-dot" aria-hidden="true">3</div>
                        <div class="pay-roadmap-info">
                            <div>
                                <strong>{{ __('Testing & Launch') }}</strong>
                                <p>{{ __('Beta rollout and public availability') }}</p>
                            </div>
                            <span class="pay-badge">{{ __('Soon') }}</span>
                        </div>
                    </div>
                </div>
            </aside>
        </div>

        {{-- Bottom Notification Strip --}}
        <section class="pay-coming-notify">
            <div class="pay-coming-notify-copy">
                <div class="pay-coming-notify-icon" aria-hidden="true">
                    <i class="ph ph-bell-simple"></i>
                </div>
                <div>
                    <strong>{{ __('Stay in the loop') }}</strong>
                    <p>{{ __('You\'re registered with') }} <strong>{{ auth()->user()->email }}</strong>{{ __('. We\'ll send an early invite the moment Premium goes live.') }}</p>
                </div>
            </div>
            <span class="pay-badge pay-badge--success" style="font-size: 11px; padding: 6px 12px;">
                <i class="ph ph-check" style="margin-right: 4px;"></i> {{ __('You\'re on the list') }}
            </span>
        </section>
    </div>
</x-app-layout>
