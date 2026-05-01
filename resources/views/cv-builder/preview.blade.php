<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CV Preview — {{ ucfirst($template) }} | TraKerja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 
                            50: '#f5f3ff', 
                            100: '#ede9fe', 
                            200: '#ddd6fe',
                            300: '#c4b5fd',
                            400: '#a78bfa',
                            500: '#a570f0', 
                            600: '#a570f0', 
                            700: '#9333ea',
                            800: '#7c3aed',
                            900: '#6d28d9'
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            margin: 0;
            overflow-x: hidden;
        }

        /* Ambient Background */
        .ambient-bg {
            position: fixed;
            inset: 0;
            z-index: -1;
            background: radial-gradient(circle at 0% 0%, rgba(165, 112, 240, 0.05) 0%, transparent 50%),
                        radial-gradient(circle at 100% 100%, rgba(78, 113, 197, 0.05) 0%, transparent 50%);
        }

        /* Top Bar - Glassmorphism */
        .preview-header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
        }

        /* Main area */
        .main-content {
            padding-top: 72px;
            padding-bottom: 100px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .paper-wrapper {
            padding: 60px 20px;
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .paper-container {
            background: white;
            width: 210mm;
            min-height: 297mm;
            box-shadow: 0 20px 50px -12px rgba(15, 23, 42, 0.12), 
                        0 30px 60px -15px rgba(165, 112, 240, 0.08);
            transform-origin: top center;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            padding: 15mm;
            border-radius: 4px;
            border: 1px solid rgba(226, 232, 240, 0.5);
        }

        /* Floating Toolbar */
        .floating-toolbar {
            position: fixed;
            bottom: 32px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 8px;
            border-radius: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            z-index: 1000;
        }

        .zoom-pill {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 2px;
            gap: 4px;
        }

        .zoom-btn {
            width: 32px; height: 32px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 12px;
            color: rgba(255,255,255,0.6);
            transition: all 0.2s;
            cursor: pointer;
        }

        .zoom-btn:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .zoom-value {
            font-size: 11px;
            font-weight: 800;
            color: white;
            min-width: 44px;
            text-align: center;
            letter-spacing: 0.05em;
        }

        .toolbar-divider {
            width: 1px;
            height: 24px;
            background: rgba(255, 255, 255, 0.1);
        }

        /* Buttons */
        .btn-premium {
            padding: 10px 20px;
            border-radius: 16px;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary-premium {
            background: #a570f0;
            color: white;
            box-shadow: 0 10px 20px -5px rgba(165, 112, 240, 0.4);
        }
        .btn-primary-premium:hover {
            background: #9333ea;
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 15px 30px -5px rgba(165, 112, 240, 0.5);
        }

        .btn-ghost-premium {
            color: white;
            background: rgba(255, 255, 255, 0.05);
        }
        .btn-ghost-premium:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-1px);
        }

        /* Page indicator */
        .page-badge {
            position: absolute;
            top: -40px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }

        @media print {
            .no-print { display: none !important; }
            body { background: white !important; margin: 0 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .main-content { padding: 0 !important; }
            .paper-wrapper { padding: 0 !important; }
            .paper-container { box-shadow: none !important; transform: none !important; width: 100% !important; padding: 0 !important; border: none !important; }
        }
    </style>
</head>
<body>
    <div class="ambient-bg no-print"></div>

    <!-- Premium Header -->
    <header class="preview-header no-print">
        <div class="flex items-center gap-6">
            {{-- Logo --}}
            <a href="{{ route('cv.builder') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center transition-transform group-hover:scale-105 active:scale-95">
                    <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-6 h-6">
                </div>
                <div class="hidden sm:block">
                    <h1 class="text-sm font-black text-slate-900 tracking-tight leading-none">TraKerja <span class="text-primary-500">CV</span></h1>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Professional Preview</p>
                </div>
            </a>

            <div class="h-6 w-px bg-slate-200 mx-2"></div>

            {{-- Template Info --}}
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 bg-primary-50 text-primary-600 rounded-full text-[9px] font-black uppercase tracking-widest border border-primary-100">
                    {{ $template }} Template
                </span>
                <span class="text-xs font-bold text-slate-400 italic">{{ Auth::user()->name }}</span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('cv.builder') }}" class="flex items-center gap-2 px-5 py-2.5 bg-slate-50 text-slate-600 rounded-xl font-bold text-xs hover:bg-slate-100 transition-all">
                <i class="ph-bold ph-pencil-simple"></i>
                Edit Content
            </a>
            <button onclick="window.print()" class="btn-premium btn-primary-premium">
                <i class="ph-bold ph-download-simple"></i>
                Export PDF
            </button>
        </div>
    </header>

    <main class="main-content">
        <div class="paper-wrapper">
            <div id="cv-paper" class="paper-container">
                <div class="page-badge no-print">A4 Page Standard</div>
                @include("cv-templates.{$template}", [
                    'user'          => $user,
                    'experiences'   => $experiences,
                    'educations'    => $educations,
                    'skills'        => $skills,
                    'organizations' => $organizations,
                    'achievements'  => $achievements,
                    'projects'      => $projects,
                ])
            </div>
        </div>
    </main>

    <!-- Floating Professional Toolbar -->
    <div class="floating-toolbar no-print">
        <div class="zoom-pill">
            <button onclick="changeZoom(-0.1)" class="zoom-btn">
                <i class="ph-bold ph-minus-circle"></i>
            </button>
            <span id="zoom-text" class="zoom-value">100%</span>
            <button onclick="changeZoom(0.1)" class="zoom-btn">
                <i class="ph-bold ph-plus-circle"></i>
            </button>
        </div>
        <div class="toolbar-divider"></div>
        <button onclick="resetZoom()" class="btn-premium btn-ghost-premium px-4">
            Fit to Screen
        </button>
        <div class="toolbar-divider"></div>
        <button onclick="window.print()" class="btn-premium btn-primary-premium">
            <i class="ph-bold ph-printer"></i>
            Print
        </button>
    </div>

    <script>
        let currentZoom = 1;
        const paper = document.getElementById('cv-paper');
        const zoomText = document.getElementById('zoom-text');

        function changeZoom(delta) {
            currentZoom = Math.min(Math.max(0.4, currentZoom + delta), 1.5);
            updateZoom();
        }

        function resetZoom() {
            const containerWidth = window.innerWidth - 80;
            const paperWidth = 794; // 210mm in pixels at 96dpi
            if (window.innerWidth < 1200) {
                currentZoom = (containerWidth / paperWidth) * 0.95;
            } else {
                currentZoom = 1;
            }
            updateZoom();
        }

        function updateZoom() {
            paper.style.transform = `scale(${currentZoom})`;
            zoomText.innerText = `${Math.round(currentZoom * 100)}%`;
        }

        // Initialize
        window.addEventListener('load', resetZoom);
        window.addEventListener('resize', () => {
            if (currentZoom < 1) resetZoom();
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.metaKey || e.ctrlKey) {
                if (e.key === '=' || e.key === '+') { e.preventDefault(); changeZoom(0.1); }
                if (e.key === '-') { e.preventDefault(); changeZoom(-0.1); }
                if (e.key === '0') { e.preventDefault(); resetZoom(); }
                if (e.key === 'p') { e.preventDefault(); window.print(); }
            }
        });
    </script>
</body>
</html>
