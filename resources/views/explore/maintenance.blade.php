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

            {{-- Right Column: Copied directly from Welcome Back (community-art) --}}
            <div class="community-art" aria-hidden="true">
                <div class="community-art-caption">A place to find your people.</div>
                <svg class="community-connections" viewBox="0 0 400 400" fill="none"><path d="M65 310C32 224 110 100 222 111C320 120 360 213 316 314" stroke="#bd91af" stroke-width="1.5" stroke-dasharray="5 8"/><path d="M103 284C151 360 269 358 309 290" stroke="#bd91af" stroke-width="1.5" stroke-dasharray="5 8"/></svg>
                <div class="community-avatar community-avatar--peach"><img src="{{ asset('images/avatars/peach.svg') }}" alt="" width="120" height="120"></div>
                <div class="community-avatar community-avatar--lilac"><img src="{{ asset('images/avatars/lilac.svg') }}" alt="" width="94" height="94"></div>
                <div class="community-avatar community-avatar--sky"><img src="{{ asset('images/avatars/sky.svg') }}" alt="" width="100" height="100"></div>
                <div class="community-speech community-speech--hello">Hey, welcome in! <span>✦</span></div>
                <div class="community-speech community-speech--share"><span class="community-speech-dot"></span>New paths. Shared together.</div>
                <div class="community-plane"><svg viewBox="0 0 90 90" fill="none"><path d="M12 38L77 15L57 77L39 52L12 38Z" fill="#fffafc" stroke="#994577" stroke-width="2" stroke-linejoin="round"/><path d="M39 52L77 15L30 45" stroke="#994577" stroke-width="2" stroke-linejoin="round"/><path d="M39 52L38 70L48 63" stroke="#994577" stroke-width="2" stroke-linejoin="round"/><path d="M7 61L19 58M14 75L28 69" stroke="#b65b90" stroke-width="2" stroke-linecap="round"/></svg></div>
                <span class="community-spark community-spark--one">✧</span><span class="community-spark community-spark--two">✦</span>
                <div class="community-art-footer"><span>Different journeys.</span><strong>One community.</strong></div>
            </div>
        </section>
    </div>
</div>
