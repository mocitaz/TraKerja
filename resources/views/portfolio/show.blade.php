<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} | Portfolio</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: "#f5f3ff",
                            100: "#ede9fe",
                            200: "#ddd6fe",
                            300: "#c4b5fd",
                            400: "#a78bfa",
                            500: "#a570f0",
                            600: "#a570f0",
                            650: "#9333ea",
                            700: "#9333ea",
                            800: "#7c3aed",
                            900: "#6d28d9",
                            950: "#4c1d95"
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace']
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        
        body {
            background-color: var(--theme-bg, #fafafa);
            background-image: radial-gradient(var(--theme-dot, rgba(0,0,0,0.02)) 1px, transparent 0);
            background-size: 24px 24px;
        }

        /* Smooth page transition elements */
        .reveal {
            opacity: 0;
            transform: translateY(12px);
            transition: opacity 0.5s cubic-bezier(0.16, 1, 0.3, 1), transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Theme: Minimal Slate (default) */
        :root {
            --theme-bg: #fafafa;
            --theme-dot: rgba(0,0,0,0.02);
            --theme-card: #ffffff;
            --theme-border: #e4e4e7;
            --theme-text: #18181b;
            --theme-text-muted: #71717a;
            --theme-accent: #7c3aed;
            --theme-accent-bg: #f5f3ff;
        }

        @php $theme = $user->portfolio_theme ?? 'slate'; @endphp
    </style>

    @php $theme = $user->portfolio_theme ?? 'slate'; @endphp

    @if($theme === 'dark')
    <style>
        :root {
            --theme-bg: #09090b;
            --theme-dot: rgba(255,255,255,0.03);
            --theme-card: #18181b;
            --theme-border: #27272a;
            --theme-text: #fafafa;
            --theme-text-muted: #a1a1aa;
            --theme-accent: #a78bfa;
            --theme-accent-bg: #1c1030;
        }
        body { color: #fafafa; }
        header { background: rgba(9,9,11,0.85) !important; border-color: #27272a !important; }
        header span.text-zinc-800 { color: #fafafa !important; }
        header .bg-white { background: #18181b !important; }
        .bg-white { background-color: #18181b !important; }
        .border-zinc-200, .border-zinc-150\/60 { border-color: #27272a !important; }
        .text-zinc-800, .text-zinc-700 { color: #f4f4f5 !important; }
        .text-zinc-500, .text-zinc-400 { color: #a1a1aa !important; }
        .bg-zinc-50, .bg-zinc-100 { background-color: #27272a !important; }
        .text-zinc-900 { color: #fafafa !important; }
    </style>
    @elseif($theme === 'emerald')
    <style>
        :root {
            --theme-bg: #f0fdf4;
            --theme-dot: rgba(16,185,129,0.04);
            --theme-card: #ffffff;
            --theme-border: #bbf7d0;
            --theme-text: #14532d;
            --theme-text-muted: #16a34a;
            --theme-accent: #059669;
            --theme-accent-bg: #dcfce7;
        }
        header { border-color: #bbf7d0 !important; }
        .border-primary-500, .focus\:border-primary-500:focus { border-color: #059669 !important; }
    </style>
    @elseif($theme === 'violet')
    <style>
        :root {
            --theme-bg: #faf5ff;
            --theme-dot: rgba(124,58,237,0.04);
            --theme-card: #ffffff;
            --theme-border: #e9d5ff;
            --theme-text: #4c1d95;
            --theme-text-muted: #7c3aed;
            --theme-accent: #7c3aed;
            --theme-accent-bg: #f3e8ff;
        }
        header { border-color: #e9d5ff !important; }
    </style>
    @endif
</head>

<body class="font-sans text-zinc-800 antialiased min-h-screen selection:bg-primary-100 selection:text-zinc-900">

    <!-- Sticky Global Header: Full Width -->
    <header class="sticky top-0 z-[50] w-full bg-white/80 backdrop-blur-md border-b border-zinc-150/60 h-14 shrink-0">
        <div class="max-w-3xl mx-auto h-full px-5 sm:px-8 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200/60 flex items-center justify-center shadow-3xs">
                    <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-4 h-4 object-contain" onerror="this.src='{{ asset('favicon.png') }}'">
                </div>
                <div class="h-4 w-[1px] bg-zinc-200"></div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-zinc-800 tracking-tight">{{ $user->name }}</span>
                    <span class="px-1.5 py-0.5 bg-emerald-50 border border-emerald-100 text-emerald-800 text-[8.5px] font-bold rounded flex items-center gap-1 leading-none uppercase tracking-wider">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Live</span>
                    </span>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                @if($user->profile && is_array($user->profile->social_links))
                    <div class="hidden sm:flex items-center gap-3">
                        @foreach($user->profile->social_links as $link)
                            <a href="{{ $link['url'] }}" target="_blank" class="text-[10px] font-bold text-zinc-400 hover:text-zinc-800 transition-colors uppercase tracking-wider">
                                {{ $link['icon'] }}
                            </a>
                        @endforeach
                    </div>
                @endif
                <a href="mailto:{{ $user->email }}" class="h-8 px-3.5 bg-primary-50 text-zinc-800 border border-primary-200/60 hover:bg-primary-100 text-xs font-bold rounded-md shadow-3xs transition-all flex items-center justify-center gap-1.5 active:scale-97">
                    <i class="ph ph-envelope text-sm"></i>
                    <span>Contact</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Global Layout Wrapper -->
    <div class="max-w-3xl mx-auto px-5 sm:px-8 pb-20">

        <!-- Main Content -->
        <main class="space-y-10 pt-10">

            <!-- Hero Section -->
            <section class="reveal flex flex-col-reverse md:flex-row md:items-center justify-between gap-6 pb-8 border-b border-zinc-200/60">
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="px-1.5 py-0.5 bg-zinc-100 text-zinc-700 text-[9px] font-bold uppercase tracking-wider rounded border border-zinc-200/60 leading-none">
                            {{ $user->profile?->domicile ?? 'Indonesia' }}
                        </span>
                        <span class="text-[9px] font-bold text-zinc-450 uppercase tracking-widest">Portfolio Site</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-zinc-900 leading-none">
                        {{ $user->name }}
                    </h1>
                    <p class="text-sm font-medium text-zinc-500 leading-relaxed max-w-xl">
                        {{ $user->profile?->headline ?? 'Creative Problem Solver & Professional Identity' }}
                    </p>
                    
                    {{-- Stats Summary --}}
                    <div class="flex flex-wrap gap-4 pt-1">
                        <div class="flex items-center gap-1.5">
                            <div class="w-4 h-4 rounded bg-zinc-100 border border-zinc-200/60 flex items-center justify-center text-zinc-500">
                                <i class="ph ph-briefcase text-[10px]"></i>
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-650">{{ $user->experiences->count() }} Experiences</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-4 h-4 rounded bg-zinc-100 border border-zinc-200/60 flex items-center justify-center text-zinc-500">
                                <i class="ph ph-code text-[10px]"></i>
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-650">{{ $user->projects->count() }} Projects</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-4 h-4 rounded bg-zinc-100 border border-zinc-200/60 flex items-center justify-center text-zinc-500">
                                <i class="ph ph-wrench text-[10px]"></i>
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-650">{{ $user->skills->count() }} Skills</span>
                        </div>
                    </div>
                </div>

                {{-- Avatar Box --}}
                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-lg bg-zinc-50 border border-zinc-200/60 overflow-hidden shadow-3xs shrink-0 grayscale hover:grayscale-0 transition-all duration-500">
                    @if($user->logo)
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-zinc-50 text-zinc-400 font-bold text-3xl select-none">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
            </section>

            <!-- Bio / Background Section -->
            <section class="reveal space-y-3 pb-8 border-b border-zinc-200/60">
                <div class="flex items-center gap-1.5">
                    <i class="ph ph-user-focus text-zinc-400 text-xs"></i>
                    <span class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest block leading-none">Background</span>
                </div>
                <p class="text-xs font-semibold text-zinc-650 leading-relaxed max-w-2xl whitespace-pre-line pl-0.5">
                    {{ $user->profile?->bio ?? 'Dedicated to professional excellence and delivering impactful results across every endeavor.' }}
                </p>
            </section>

            <!-- Experiences Section -->
            @if($user->experiences->count() > 0)
                <section class="reveal space-y-4 pb-8 border-b border-zinc-200/60">
                    <div class="flex items-center gap-1.5">
                        <i class="ph ph-briefcase text-zinc-400 text-xs"></i>
                        <span class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest block leading-none">Work Experience</span>
                    </div>
                    
                    <div class="relative pl-4 border-l border-zinc-200 space-y-6 ml-1.5">
                        @foreach($user->experiences as $exp)
                            <div class="relative space-y-2 group">
                                {{-- Timeline Dot --}}
                                <span class="absolute -left-[20.5px] top-1 w-2 h-2 rounded-full border-2 border-white bg-zinc-300 group-hover:bg-primary-500 transition-colors"></span>
                                
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                                    <h3 class="text-xs font-bold text-zinc-800 tracking-tight flex items-center gap-2">
                                        <span>{{ $exp->position }}</span>
                                        <span class="px-1.5 py-0.2 bg-primary-50 text-zinc-800 text-[8.5px] font-bold rounded border border-primary-100/60 leading-none">{{ $exp->company_name }}</span>
                                    </h3>
                                    <span class="text-[9px] font-bold text-zinc-450 uppercase tracking-wider">
                                        {{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} —
                                        {{ $exp->is_current ? 'Present' : \Carbon\Carbon::parse($exp->end_date)->format('M Y') }}
                                    </span>
                                </div>
                                <p class="text-[11px] font-medium text-zinc-500 leading-relaxed pl-0.5">
                                    {{ $exp->description }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- Projects Section -->
            @if($user->projects->count() > 0)
                <section class="reveal space-y-4 pb-8 border-b border-zinc-200/60">
                    <div class="flex items-center gap-1.5">
                        <i class="ph ph-folder text-zinc-400 text-xs"></i>
                        <span class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest block leading-none">Selected Projects</span>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        @foreach($user->projects as $project)
                            <div class="p-4 bg-zinc-50/20 border border-zinc-200/60 rounded-lg hover:border-zinc-300 transition-all hover:bg-zinc-50/50 hover:shadow-3xs group relative flex flex-col justify-between">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between gap-2">
                                        <h4 class="text-xs font-bold text-zinc-850 tracking-tight">{{ $project->project_name }}</h4>
                                        <span class="px-1.5 py-0.5 bg-zinc-100 text-zinc-650 text-[8px] font-bold rounded border border-zinc-200/65 leading-none">
                                            {{ $project->role }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] font-medium text-zinc-500 leading-relaxed">
                                        {{ $project->description }}
                                    </p>
                                </div>
                                @if($project->project_url)
                                    <div class="pt-3 mt-3 border-t border-zinc-150/40 flex justify-end">
                                        <a href="{{ $project->project_url }}" target="_blank" class="text-[9px] font-bold text-zinc-400 hover:text-zinc-850 transition-colors inline-flex items-center gap-1">
                                            <span>Launch Project</span>
                                            <i class="ph ph-arrow-square-out text-[10px]"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- Skills & Expertise -->
            @if($user->skills->count() > 0)
                <section class="reveal space-y-3.5 pb-8 border-b border-zinc-200/60">
                    <div class="flex items-center gap-1.5">
                        <i class="ph ph-wrench text-zinc-400 text-xs"></i>
                        <span class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest block leading-none">Expertise & Technologies</span>
                    </div>
                    <div class="flex flex-wrap gap-1.5 pl-0.5">
                        @foreach($user->skills as $skill)
                            <span class="px-2.5 py-1 bg-white border border-zinc-200 text-zinc-700 text-[10px] font-semibold rounded hover:border-zinc-350 transition-colors cursor-default">
                                {{ $skill->skill_name }}
                            </span>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- Education Section -->
            @if($user->educations->count() > 0)
                <section class="reveal space-y-4 pb-8 border-b border-zinc-200/60">
                    <div class="flex items-center gap-1.5">
                        <i class="ph ph-graduation-cap text-zinc-400 text-xs"></i>
                        <span class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest block leading-none">Education</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pl-0.5">
                        @foreach($user->educations as $edu)
                            <div class="p-3.5 bg-white border border-zinc-200/60 rounded-lg flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-xs font-bold text-zinc-850 tracking-tight leading-none">{{ $edu->institution_name }}</p>
                                    <p class="text-[9px] font-semibold text-zinc-400 mt-1.5 leading-none">{{ $edu->field_of_study ?? '' }}</p>
                                </div>
                                <span class="px-1.5 py-0.5 bg-zinc-50 text-zinc-500 text-[8.5px] font-bold rounded border border-zinc-200/65 leading-none shrink-0">
                                    {{ $edu->degree }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- Contact Block -->
            <section class="reveal py-10 space-y-5 text-center">
                <div class="max-w-md mx-auto space-y-2.5">
                    <span class="px-1.5 py-0.5 bg-emerald-50 text-emerald-800 text-[8px] font-bold uppercase tracking-wider rounded border border-emerald-100/60 leading-none">
                        Available for Opportunities
                    </span>
                    <h2 class="text-base font-bold text-zinc-800 tracking-tight">Let's work together</h2>
                    <p class="text-xs font-medium text-zinc-450 leading-relaxed">
                        Ready to bring expertise and dedication to your next project. Reach out and let's build something meaningful.
                    </p>
                </div>
                <div>
                    <a href="mailto:{{ $user->email }}" class="inline-flex items-center gap-1.5 px-4 h-9 bg-zinc-900 hover:bg-zinc-850 text-white rounded-md text-xs font-bold shadow-3xs transition-colors focus:outline-none">
                        <i class="ph ph-envelope text-sm"></i>
                        <span>{{ $user->email }}</span>
                    </a>
                </div>
            </section>

        </main>

        <!-- Footer -->
        <footer class="pt-6 border-t border-zinc-200/60 flex justify-between items-center text-[10px] font-bold text-zinc-400 uppercase tracking-wider">
            <div>
                &copy; {{ date('Y') }} {{ $user->name }}
            </div>
            <div class="flex items-center gap-1.5 opacity-60 hover:opacity-100 transition-opacity">
                <img src="{{ asset('images/icon.png') }}" class="w-3.5 h-3.5 object-contain" onerror="this.src='{{ asset('favicon.png') }}'">
                <span class="text-zinc-650">TraKerja Sites</span>
            </div>
        </footer>

    </div>

    <script>
        /* ── Scroll reveal observer ── */
        const observer = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    observer.unobserve(e.target);
                }
            });
        }, { threshold: 0.08 });
        
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    </script>
</body>

</html>