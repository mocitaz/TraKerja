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
                    We’ve temporarily paused Explore to engineer a brand new discovery engine. Think smarter AI recommendations calibrated directly to your profile, verified live company openings, and direct recruiter drops with zero ghost listings.
                </p>

                <div class="explore-callout-box">
                    <div class="explore-callout-icon">
                        <i class="ph-fill ph-check-circle" aria-hidden="true"></i>
                    </div>
                    <div class="explore-callout-text">
                        <strong>Your main workspace is 100% active</strong>
                        <span>Your Job Tracker, ATS Simulator, CV Builder, and AI Studio are running at full speed. Keep applying and stack your wins!</span>
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

            {{-- Right Column: Animated Visual Radar Art --}}
            <div class="explore-maintenance-art" aria-hidden="true">
                <div class="explore-art-caption">A smarter radar · Rebuilt for your career</div>

                {{-- Orbit SVG Background --}}
                <svg class="explore-art-orbits" viewBox="0 0 380 380" fill="none">
                    <ellipse cx="190" cy="190" rx="145" ry="145" stroke="#b8cce4" stroke-width="1.5" stroke-dasharray="6 8" class="explore-orbit-outer" />
                    <ellipse cx="190" cy="190" rx="95" ry="95" stroke="#d5c7e8" stroke-width="1.5" stroke-dasharray="5 7" class="explore-orbit-inner" />
                    <path d="M45 285C20 185 90 85 200 95C300 105 350 205 300 305" stroke="#a9c2e0" stroke-width="1.6" stroke-dasharray="5 7" class="explore-orbit-arc" />
                </svg>

                {{-- Center Radar Avatar with Pulsing Ping --}}
                <div class="explore-art-center">
                    <div class="explore-center-glow"></div>
                    <div class="explore-center-pulse"></div>
                    <div class="explore-center-avatar">
                        <img src="{{ asset('images/avatars/sky.svg') }}" alt="" width="108" height="108">
                    </div>
                </div>

                {{-- Floating Badge 1: Top Speech Bubble --}}
                <div class="explore-float-badge explore-float--speech">
                    <span class="explore-speech-dot"></span>
                    <span>Cooking fresh company drops!</span>
                    <i class="ph-fill ph-sparkle explore-speech-spark" aria-hidden="true"></i>
                </div>

                {{-- Floating Badge 2: Right Top Pill --}}
                <div class="explore-float-badge explore-float--match">
                    <div class="explore-badge-icon explore-badge-icon--purple">
                        <i class="ph-fill ph-lightning" aria-hidden="true"></i>
                    </div>
                    <div class="explore-badge-info">
                        <strong>AI Match V2</strong>
                        <small>10x sharper signal</small>
                    </div>
                </div>

                {{-- Floating Badge 3: Left Bottom Pill --}}
                <div class="explore-float-badge explore-float--verified">
                    <div class="explore-badge-icon explore-badge-icon--blue">
                        <i class="ph-fill ph-shield-check" aria-hidden="true"></i>
                    </div>
                    <div class="explore-badge-info">
                        <strong>Live Verified</strong>
                        <small>Zero ghost jobs</small>
                    </div>
                </div>

                {{-- Floating Badge 4: Right Bottom Pill --}}
                <div class="explore-float-badge explore-float--pipeline">
                    <div class="explore-badge-icon explore-badge-icon--emerald">
                        <i class="ph-fill ph-radar" aria-hidden="true"></i>
                    </div>
                    <div class="explore-badge-info">
                        <strong>Direct Drops</strong>
                        <small>Fast recruiter lane</small>
                    </div>
                </div>

                {{-- Decorative Sparks --}}
                <span class="explore-spark explore-spark--one">✦</span>
                <span class="explore-spark explore-spark--two">✧</span>
                <span class="explore-spark explore-spark--three">✦</span>

                {{-- Bottom Caption --}}
                <div class="explore-art-footer">
                    <span>Less scrolling. More interviewing.</span>
                    <strong>The new Explore is on the way.</strong>
                </div>
            </div>
        </section>
    </div>
</div>
