<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} | Portfolio</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --ink: #0f172a;          /* Slate-900 */
            --ink-muted: #475569;    /* Slate-600 */
            --ink-faint: #94a3b8;    /* Slate-400 */
            --paper: #f8fafc;        /* Slate-50 */
            --paper-2: #f1f5f9;      /* Slate-100 */
            --paper-3: #e2e8f0;      /* Slate-200 */
            --accent: #6366f1;       /* TraKerja Indigo */
            --accent-dark: #4f46e5;  /* Deep Indigo */
            --rule: #e2e8f0;         /* Slate-200 */
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background-color: var(--paper);
            color: var(--ink);
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            line-height: 1.7;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        /* ── Typography ── */
        .serif {
            font-family: 'DM Serif Display', serif;
        }

        h1.display {
            font-family: 'DM Serif Display', serif;
            font-size: clamp(52px, 7vw, 88px);
            line-height: 1.02;
            letter-spacing: -0.02em;
            color: var(--ink);
        }

        .eyebrow {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--ink-faint);
        }

        /* ── Noise overlay ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
            background-repeat: repeat;
            background-size: 256px;
            pointer-events: none;
            z-index: 1000;
            opacity: 0.6;
        }

        /* ── Cursor ── */
        .cursor {
            width: 8px;
            height: 8px;
            background: var(--accent);
            border-radius: 50%;
            position: fixed;
            pointer-events: none;
            z-index: 9999;
            transition: transform 0.15s ease;
            transform: translate(-50%, -50%);
        }

        .cursor-ring {
            width: 36px;
            height: 36px;
            border: 1px solid var(--accent);
            border-radius: 50%;
            position: fixed;
            pointer-events: none;
            z-index: 9998;
            transform: translate(-50%, -50%);
            transition: all 0.08s ease;
            opacity: 0.5;
        }

        /* ── Side rule nav ── */
        .side-nav {
            position: fixed;
            right: 40px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 20px;
            z-index: 100;
        }

        .side-nav a {
            display: block;
            width: 2px;
            height: 32px;
            background: var(--rule);
            transition: all 0.3s ease;
            position: relative;
        }

        .side-nav a.active,
        .side-nav a:hover {
            background: var(--accent);
            height: 48px;
        }

        .side-nav a::after {
            content: attr(data-label);
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--ink-faint);
            white-space: nowrap;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .side-nav a:hover::after {
            opacity: 1;
        }

        @media (max-width: 900px) {
            .side-nav {
                display: none;
            }
        }

        /* ── Sections ── */
        .page-wrap {
            max-width: 960px;
            margin: 0 auto;
            padding: 0 40px;
        }

        section {
            padding: 100px 0;
        }

        .section-row {
            display: grid;
            grid-template-columns: 160px 1fr;
            gap: 0 80px;
            align-items: start;
        }

        @media (max-width: 720px) {
            .section-row {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            section {
                padding: 64px 0;
            }

            .page-wrap {
                padding: 0 24px;
            }
        }

        /* ── Hairline divider ── */
        .hr {
            border: none;
            border-top: 1px solid var(--rule);
        }

        /* ── Experience entries ── */
        .exp-item {
            padding-bottom: 56px;
            border-bottom: 1px solid var(--rule);
            margin-bottom: 56px;
            position: relative;
        }

        .exp-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .exp-item::before {
            content: '';
            position: absolute;
            left: -28px;
            top: 8px;
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--accent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .exp-item:hover::before {
            opacity: 1;
        }

        /* ── Skills / Tags ── */
        .tag-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .tag {
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.06em;
            padding: 6px 14px;
            border: 1px solid var(--rule);
            border-radius: 2px;
            color: var(--ink-muted);
            background: transparent;
            transition: all 0.25s ease;
        }

        .tag:hover {
            border-color: var(--accent);
            color: var(--ink);
            background: var(--paper-2);
        }

        /* ── Project cards ── */
        .project-card {
            padding: 40px;
            border: 1px solid var(--rule);
            border-radius: 4px;
            background: var(--paper);
            transition: all 0.35s ease;
            position: relative;
            overflow: hidden;
        }

        .project-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent), var(--accent-dark));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s ease;
        }

        .project-card:hover {
            border-color: var(--paper-3);
            box-shadow: 0 20px 60px -20px rgba(0, 0, 0, 0.12);
            transform: translateY(-3px);
        }

        .project-card:hover::after {
            transform: scaleX(1);
        }

        /* ── Profile image ── */
        .avatar-wrap {
            width: 88px;
            height: 88px;
            border-radius: 4px;
            overflow: hidden;
            border: 1px solid var(--rule);
            filter: grayscale(100%);
            transition: filter 0.6s ease;
            flex-shrink: 0;
        }

        .avatar-wrap:hover {
            filter: grayscale(0%);
        }

        /* ── Animated entrance ── */
        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-delay-1 {
            transition-delay: 0.1s;
        }

        .reveal-delay-2 {
            transition-delay: 0.2s;
        }

        .reveal-delay-3 {
            transition-delay: 0.3s;
        }

        .reveal-delay-4 {
            transition-delay: 0.4s;
        }

        /* ── Hero extras ── */
        .hero-rule {
            width: 40px;
            height: 2px;
            background: var(--accent);
            display: inline-block;
            vertical-align: middle;
            margin-right: 12px;
        }

        .scroll-indicator {
            position: absolute;
            bottom: 48px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            opacity: 0.35;
            animation: bounce 2.4s ease infinite;
        }

        .scroll-indicator span {
            display: block;
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            writing-mode: vertical-rl;
        }

        .scroll-arrow {
            width: 1px;
            height: 48px;
            background: var(--ink);
            position: relative;
        }

        .scroll-arrow::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: -3px;
            width: 7px;
            height: 7px;
            border-right: 1px solid var(--ink);
            border-bottom: 1px solid var(--ink);
            transform: rotate(45deg) translateY(-3px);
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateX(-50%) translateY(0);
            }

            50% {
                transform: translateX(-50%) translateY(8px);
            }
        }

        /* ── Social links ── */
        .social-link {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--ink-faint);
            text-decoration: none;
            transition: color 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .social-link:hover {
            color: var(--ink);
        }

        .social-link::before {
            content: '';
            display: block;
            width: 20px;
            height: 1px;
            background: currentColor;
        }

        /* ── Education ── */
        .edu-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            padding: 20px 0;
            border-bottom: 1px solid var(--rule);
            gap: 24px;
        }

        .edu-row:first-child {
            border-top: 1px solid var(--rule);
        }

        /* ── Footer ── */
        footer {
            padding: 60px 0;
            border-top: 1px solid var(--rule);
        }

        /* ── Contact CTA ── */
        .cta-btn {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 18px 36px;
            background: var(--ink);
            color: var(--paper);
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            text-decoration: none;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .cta-btn:hover {
            background: var(--accent-dark);
            transform: translateY(-2px);
        }

        .cta-btn svg {
            width: 16px;
            height: 16px;
            transition: transform 0.3s ease;
        }

        .cta-btn:hover svg {
            transform: translateX(4px);
        }

        /* ── Marquee decoration ── */
        .marquee-wrap {
            overflow: hidden;
            border-top: 1px solid var(--rule);
            border-bottom: 1px solid var(--rule);
            padding: 18px 0;
            background: var(--paper-2);
        }

        .marquee-inner {
            display: flex;
            gap: 0;
            animation: marquee 22s linear infinite;
            white-space: nowrap;
        }

        .marquee-inner span {
            font-family: 'DM Serif Display', serif;
            font-style: italic;
            font-size: 13px;
            color: var(--ink-faint);
            padding: 0 40px;
        }

        .marquee-inner span strong {
            font-style: normal;
            color: var(--accent);
            font-family: 'DM Sans', sans-serif;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            margin: 0 20px;
        }

        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }
    </style>
</head>

<body>

    {{-- Custom cursor --}}
    <div class="cursor" id="cursor"></div>
    <div class="cursor-ring" id="cursor-ring"></div>

    {{-- Side navigation --}}
    <nav class="side-nav" aria-label="Page sections">
        <a href="#about" data-label="About"></a>
        <a href="#experience" data-label="Experience"></a>
        <a href="#expertise" data-label="Expertise"></a>
        @if($user->projects->count() > 0)
            <a href="#projects" data-label="Projects"></a>
        @endif
        <a href="#education" data-label="Education"></a>
        <a href="#contact" data-label="Contact"></a>
    </nav>

    {{-- ── HERO ── --}}
    <section id="about"
        style="min-height: 100vh; display: flex; flex-direction: column; justify-content: center; position: relative; padding: 120px 0 100px;">
        <div class="page-wrap">

            {{-- Top bar --}}
            <div
                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 80px; padding-bottom: 32px; border-bottom: 1px solid var(--rule);">
                <span class="eyebrow">Professional Portfolio</span>
                <div style="display: flex; gap: 24px; align-items: center;">
                    @foreach($user->profile->social_links as $link)
                        <a href="{{ $link['url'] }}" target="_blank" class="social-link">{{ ucfirst($link['icon']) }}</a>
                    @endforeach
                </div>
            </div>

            {{-- Main hero content --}}
            <div style="display: grid; grid-template-columns: 1fr auto; gap: 60px; align-items: end;">
                <div>
                    <p class="reveal" style="margin-bottom: 24px;">
                        <span class="hero-rule"></span>
                        <span class="eyebrow">{{ $user->profile->domicile ?? 'Indonesia' }}</span>
                    </p>
                    <h1 class="display reveal reveal-delay-1">{{ $user->name }}</h1>
                    <p class="reveal reveal-delay-2"
                        style="margin-top: 36px; font-size: 18px; font-weight: 300; color: var(--ink-muted); max-width: 520px; line-height: 1.65;">
                        {{ $user->profile->headline ?? 'Professional Identity' }}
                    </p>
                    <div class="reveal reveal-delay-3"
                        style="margin-top: 48px; display: flex; gap: 24px; align-items: center; flex-wrap: wrap;">
                        <a href="mailto:{{ $user->email }}" class="cta-btn">
                            Get in touch
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M17 8l4 4-4 4M3 12h18" />
                            </svg>
                        </a>
                        <span style="font-size: 12px; color: var(--ink-faint);">{{ $user->email }}</span>
                    </div>
                </div>

                <div class="reveal reveal-delay-2">
                    <div class="avatar-wrap" style="width: 104px; height: 130px;">
                        @if($user->logo)
                            <img src="{{ asset('storage/' . $user->logo) }}" alt="{{ $user->name }}"
                                style="width:100%; height:100%; object-fit: cover;">
                        @else
                            <div
                                style="width:100%; height:100%; background: var(--paper-3); display:flex; align-items:center; justify-content:center; font-family:'DM Serif Display',serif; font-size:36px; color: var(--ink-faint);">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="scroll-indicator">
            <div class="scroll-arrow"></div>
            <span>Scroll</span>
        </div>
    </section>

    {{-- ── MARQUEE ── --}}
    <div class="marquee-wrap" aria-hidden="true">
        <div class="marquee-inner">
            @for($i = 0; $i < 2; $i++)
                <span>Professional Excellence <strong>✦</strong></span>
                <span>{{ $user->profile->headline ?? 'Creative Problem Solver' }} <strong>✦</strong></span>
                <span>Available for Opportunities <strong>✦</strong></span>
                <span>{{ $user->experiences->count() }}+ Roles <strong>✦</strong></span>
                <span>{{ $user->skills->count() }} Core Skills <strong>✦</strong></span>
                <span>Based in {{ $user->profile->domicile ?? 'Indonesia' }} <strong>✦</strong></span>
            @endfor
        </div>
    </div>

    {{-- ── BACKGROUND / BIO ── --}}
    <section style="padding: 100px 0;">
        <div class="page-wrap">
            <div class="section-row reveal">
                <div>
                    <span class="eyebrow">Background</span>
                </div>
                <div>
                    <p style="font-size: 19px; font-weight: 300; line-height: 1.75; color: var(--ink-muted);">
                        {!! nl2br(e($user->profile->bio ?? 'Dedicated to professional excellence and delivering impactful results across every endeavor.')) !!}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <hr class="hr" style="margin: 0 40px;">

    {{-- ── EXPERIENCE ── --}}
    <section id="experience" style="padding: 100px 0;">
        <div class="page-wrap">
            <div class="section-row">
                <div class="reveal">
                    <span class="eyebrow">Experience</span>
                </div>
                <div style="padding-left: 28px; border-left: 1px solid var(--rule);">
                    @foreach($user->experiences as $exp)
                        <div class="exp-item reveal">
                            <div
                                style="display: flex; justify-content: space-between; align-items: baseline; gap: 24px; flex-wrap: wrap; margin-bottom: 8px;">
                                <h3 style="font-family: 'DM Serif Display', serif; font-size: 22px; color: var(--ink);">
                                    {{ $exp->position }}</h3>
                                <span
                                    style="font-size: 10px; font-weight: 600; letter-spacing: 0.15em; text-transform: uppercase; color: var(--accent); white-space: nowrap;">
                                    {{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} —
                                    {{ $exp->is_current ? 'Present' : \Carbon\Carbon::parse($exp->end_date)->format('M Y') }}
                                </span>
                            </div>
                            <p
                                style="font-size: 12px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; color: var(--ink-faint); margin-bottom: 20px;">
                                {{ $exp->company_name }}</p>
                            <p style="font-size: 14px; color: var(--ink-muted); line-height: 1.8; font-weight: 300;">
                                {{ $exp->description }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <hr class="hr" style="margin: 0 40px;">

    {{-- ── EXPERTISE / SKILLS ── --}}
    <section id="expertise" style="padding: 100px 0;">
        <div class="page-wrap">
            <div class="section-row reveal">
                <div>
                    <span class="eyebrow">Expertise</span>
                </div>
                <div>
                    <div class="tag-grid">
                        @foreach($user->skills as $skill)
                            <span class="tag">{{ $skill->skill_name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── PROJECTS ── --}}
    @if($user->projects->count() > 0)
        <hr class="hr" style="margin: 0 40px;">
        <section id="projects" style="padding: 100px 0;">
            <div class="page-wrap">
                <div style="margin-bottom: 56px;" class="reveal">
                    <span class="eyebrow">Selected Projects</span>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
                    @foreach($user->projects as $project)
                        <div class="project-card reveal">
                            <p
                                style="font-size: 10px; font-weight: 600; letter-spacing: 0.18em; text-transform: uppercase; color: var(--accent); margin-bottom: 16px;">
                                {{ $project->role }}</p>
                            <h4
                                style="font-family: 'DM Serif Display', serif; font-size: 22px; line-height: 1.2; color: var(--ink); margin-bottom: 20px;">
                                {{ $project->project_name }}</h4>
                            <p style="font-size: 13px; color: var(--ink-muted); line-height: 1.8; font-weight: 300;">
                                {{ $project->description }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <hr class="hr" style="margin: 0 40px;">

    {{-- ── EDUCATION ── --}}
    <section id="education" style="padding: 100px 0;">
        <div class="page-wrap">
            <div class="section-row reveal">
                <div>
                    <span class="eyebrow">Education</span>
                </div>
                <div>
                    @foreach($user->educations as $edu)
                        <div class="edu-row">
                            <div>
                                <p style="font-size: 15px; font-weight: 500; color: var(--ink);">
                                    {{ $edu->institution_name }}</p>
                                <p style="font-size: 12px; font-weight: 300; color: var(--ink-muted); margin-top: 2px;">
                                    {{ $edu->field_of_study ?? '' }}</p>
                            </div>
                            <span
                                style="font-size: 11px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; color: var(--ink-faint); white-space: nowrap; flex-shrink: 0;">{{ $edu->degree }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ── CONTACT CTA ── --}}
    <section id="contact" style="padding: 120px 0; background: var(--ink);">
        <div class="page-wrap" style="text-align: center;">
            <p class="reveal"
                style="font-size: 10px; font-weight: 600; letter-spacing: 0.2em; text-transform: uppercase; color: var(--accent); margin-bottom: 28px;">
                Open to new opportunities</p>
            <h2 class="display reveal reveal-delay-1"
                style="color: var(--paper); margin-bottom: 24px; font-size: clamp(40px, 5vw, 64px);">Let's
                work<br><em>together</em></h2>
            <p class="reveal reveal-delay-2"
                style="font-size: 16px; font-weight: 300; color: var(--ink-faint); max-width: 440px; margin: 0 auto 56px; line-height: 1.7;">
                Ready to bring expertise and dedication to your next project. Reach out and let's create something
                meaningful.</p>
            <a href="mailto:{{ $user->email }}" class="cta-btn reveal reveal-delay-3"
                style="background: var(--accent); margin: 0 auto;">
                {{ $user->email }}
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M17 8l4 4-4 4M3 12h18" />
                </svg>
            </a>
        </div>
    </section>

    {{-- ── FOOTER ── --}}
    <footer>
        <div class="page-wrap"
            style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <p
                style="font-size: 10px; font-weight: 600; letter-spacing: 0.2em; text-transform: uppercase; color: var(--ink-faint);">
                &copy; {{ date('Y') }} {{ $user->name }}
            </p>
            <div style="display: flex; align-items: center; gap: 10px; opacity: 0.4; transition: opacity 0.3s ease;"
                onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.4">
                <img src="{{ asset('images/icon.png') }}" style="width: 14px; height: 14px; object-fit: contain;">
                <span
                    style="font-size: 9px; font-weight: 700; letter-spacing: 0.25em; text-transform: uppercase; color: var(--ink);">TraKerja
                    Sites</span>
            </div>
        </div>
    </footer>

    <script>
        /* ── Custom cursor ── */
        const cursor = document.getElementById('cursor');
        const ring = document.getElementById('cursor-ring');
        let mx = 0, my = 0, rx = 0, ry = 0;

        document.addEventListener('mousemove', e => {
            mx = e.clientX; my = e.clientY;
            cursor.style.left = mx + 'px';
            cursor.style.top = my + 'px';
        });

        (function animRing() {
            rx += (mx - rx) * 0.12;
            ry += (my - ry) * .12;
            ring.style.left = rx + 'px';
            ring.style.top = ry + 'px';
            requestAnimationFrame(animRing);
        })();

        document.querySelectorAll('a, button, .tag, .project-card').forEach(el => {
            el.addEventListener('mouseenter', () => { cursor.style.transform = 'translate(-50%,-50%) scale(2.5)'; ring.style.transform = 'translate(-50%,-50%) scale(1.6)'; ring.style.opacity = '0.2'; });
            el.addEventListener('mouseleave', () => { cursor.style.transform = 'translate(-50%,-50%) scale(1)'; ring.style.transform = 'translate(-50%,-50%) scale(1)'; ring.style.opacity = '0.5'; });
        });

        /* ── Scroll reveal ── */
        const observer = new IntersectionObserver(entries => {
            entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); observer.unobserve(e.target); } });
        }, { threshold: 0.12 });
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

        /* ── Active side nav ── */
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.side-nav a');
        const secObserver = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    navLinks.forEach(l => l.classList.remove('active'));
                    const link = document.querySelector(`.side-nav a[href="#${e.target.id}"]`);
                    if (link) link.classList.add('active');
                }
            });
        }, { rootMargin: '-40% 0px -55% 0px' });
        sections.forEach(s => secObserver.observe(s));
    </script>
</body>

</html>