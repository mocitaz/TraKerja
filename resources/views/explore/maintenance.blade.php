<div class="workspace-page explore-maintenance-page">
    <div class="workspace-page__container explore-maintenance-container">
        <section class="explore-maintenance-card" aria-labelledby="maintenance-title">
            {{-- Left Column: Copy & Actions --}}
            <div class="explore-maintenance-copy">
                <div class="explore-status-pill">
                    <span class="explore-status-ping"></span>
                    <i class="ph-fill ph-sparkle" aria-hidden="true"></i>
                    <span>NEXT-GEN EXPLORE · IN THE LAB</span>
                </div>

                <h1 id="maintenance-title">
                    Something massive is cooking<span class="explore-title-dot">.</span>
                </h1>

                <p class="explore-lead">
                    We’re rebuilding job discovery from the ground up — zero noise, instant signal, and opportunities that actually hit.
                </p>

                <p class="explore-desc">
                    We’ve temporarily paused Explore to engineer a brand new discovery engine. Think precision-matched roles calibrated directly to your experience, verified live company openings, and direct recruiter drops with zero ghost listings.
                </p>

                <div class="explore-callout-box">
                    <div class="explore-callout-icon">
                        <i class="ph-fill ph-check-circle" aria-hidden="true"></i>
                    </div>
                    <div class="explore-callout-text">
                        <strong>Your main workspace is 100% active</strong>
                        <span>Your Job Tracker, ATS Simulator, CV Builder, and Application Tools are running at full speed. Keep applying and stack your wins!</span>
                    </div>
                </div>

                <div class="explore-cta-actions">
                    <a href="{{ route('dashboard') }}" wire:navigate class="explore-btn-primary">
                        <span>Back to Dashboard</span>
                        <i class="ph ph-arrow-right" aria-hidden="true"></i>
                    </a>
                    <a href="{{ route('tracker') }}" wire:navigate class="explore-btn-secondary">
                        <i class="ph ph-kanban" aria-hidden="true"></i>
                        <span>Open Job Tracker</span>
                    </a>
                </div>

                <p class="explore-footnote">
                    <i class="ph-fill ph-clock-countdown" aria-hidden="true"></i>
                    <span>Stay tuned. Dropping sooner than you think.</span>
                </p>
            </div>

            {{-- Right Column: Curated Job Feed Stack (Sleek Linear/Stripe Style) --}}
            <div class="explore-maintenance-art" aria-hidden="true">
                <div class="explore-art-caption">The next-gen discovery feed · In active build</div>

                {{-- Dynamic Flowing Connections SVG Background --}}
                <svg class="explore-art-orbits" viewBox="0 0 380 380" fill="none">
                    <path d="M40 280C15 170 95 65 210 75C310 85 365 190 315 295" stroke="#b4cce2" stroke-width="1.6" stroke-dasharray="5 7" class="explore-orbit-arc" />
                    <path d="M75 250C130 340 250 345 305 265" stroke="#cfbfe0" stroke-width="1.6" stroke-dasharray="5 7" class="explore-orbit-inner" />
                </svg>

                {{-- 3D Layered Job Card Stack --}}
                <div class="explore-feed-stack">
                    {{-- Background Card 2 (Bottom Peek) --}}
                    <div class="explore-feed-card explore-feed-card--back" aria-hidden="true">
                        <div class="explore-card-mini-head">
                            <span class="explore-co-badge explore-co-badge--emerald">GT</span>
                            <span class="explore-card-mini-title">Product Lead · GoTo Group</span>
                            <span class="explore-card-mini-tag">IDR 26M – 35M</span>
                        </div>
                    </div>

                    {{-- Background Card 1 (Middle Peek) --}}
                    <div class="explore-feed-card explore-feed-card--mid" aria-hidden="true">
                        <div class="explore-card-mini-head">
                            <span class="explore-co-badge explore-co-badge--purple">TV</span>
                            <span class="explore-card-mini-title">Staff Backend Engineer · Traveloka</span>
                            <span class="explore-card-mini-tag">Remote</span>
                        </div>
                    </div>

                    {{-- Foreground Hero Card (Front) --}}
                    <div class="explore-feed-card explore-feed-card--front">
                        <div class="explore-card-glow"></div>
                        <div class="explore-card-top">
                            <div class="explore-co-identity">
                                <div class="explore-co-logo">
                                    <i class="ph-fill ph-buildings" aria-hidden="true"></i>
                                </div>
                                <div class="explore-co-info">
                                    <div class="explore-co-name">
                                        <strong>Tech Unicorn</strong>
                                        <i class="ph-fill ph-seal-check" title="Verified Employer" aria-hidden="true"></i>
                                    </div>
                                    <span>Jakarta · Hybrid</span>
                                </div>
                            </div>
                            <div class="explore-card-live-pill">
                                <span class="explore-live-dot"></span>
                                <span>Fresh Drop</span>
                            </div>
                        </div>

                        <div class="explore-card-role">
                            Senior Frontend Engineer
                        </div>

                        <div class="explore-card-tags">
                            <span class="explore-tag explore-tag--salary">
                                <i class="ph-fill ph-money" aria-hidden="true"></i>
                                IDR 22M – 32M / mo
                            </span>
                            <span class="explore-tag explore-tag--dept">
                                <i class="ph ph-laptop" aria-hidden="true"></i>
                                Engineering
                            </span>
                        </div>

                        <div class="explore-card-footer-strip">
                            <div class="explore-verified-status">
                                <i class="ph-fill ph-shield-check" aria-hidden="true"></i>
                                <span>100% verified opening · Direct hiring lane</span>
                            </div>
                            <span class="explore-ping-time">Just now</span>
                        </div>
                    </div>
                </div>

                {{-- Floating Badges around the Card Stack (Sleek Linear/Stripe style) --}}
                {{-- Badge 1: Top Left Speech Bubble --}}
                <div class="explore-float-badge explore-float--speech">
                    <span class="explore-speech-dot"></span>
                    <span>Curating top company drops!</span>
                    <i class="ph-fill ph-sparkle explore-speech-spark" aria-hidden="true"></i>
                </div>

                {{-- Badge 2: Right Top Pill --}}
                <div class="explore-float-badge explore-float--match">
                    <div class="explore-badge-icon explore-badge-icon--blue">
                        <i class="ph-fill ph-shield-check" aria-hidden="true"></i>
                    </div>
                    <div class="explore-badge-info">
                        <strong>Live Verified</strong>
                        <small>Zero ghost postings</small>
                    </div>
                </div>

                {{-- Badge 3: Left Bottom Pill --}}
                <div class="explore-float-badge explore-float--verified">
                    <div class="explore-badge-icon explore-badge-icon--emerald">
                        <i class="ph-fill ph-currency-circle-dollar" aria-hidden="true"></i>
                    </div>
                    <div class="explore-badge-info">
                        <strong>Upfront Salary</strong>
                        <small>Transparent ranges</small>
                    </div>
                </div>

                {{-- Badge 4: Right Bottom Pill --}}
                <div class="explore-float-badge explore-float--pipeline">
                    <div class="explore-badge-icon explore-badge-icon--purple">
                        <i class="ph-fill ph-paper-plane-tilt" aria-hidden="true"></i>
                    </div>
                    <div class="explore-badge-info">
                        <strong>Direct Recruiter</strong>
                        <small>Skip third-party spam</small>
                    </div>
                </div>

                {{-- Decorative Sparks (like in welcome.blade.php) --}}
                <span class="explore-spark explore-spark--one">✦</span>
                <span class="explore-spark explore-spark--two">✧</span>
                <span class="explore-spark explore-spark--three">✦</span>

                {{-- Bottom Caption --}}
                <div class="explore-art-footer">
                    <span>Less scrolling. More interviewing.</span>
                    <strong>Engineered for real offers.</strong>
                </div>
            </div>
        </section>
    </div>
</div>
