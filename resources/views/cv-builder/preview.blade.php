<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Professional Preview | TraKerja</title>
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
            background-color: #fdfdfd; /* Ultra clean off-white */
            color: #0f172a;
            margin: 0;
            overflow-x: hidden;
        }

        /* Subtle Ambient Gradient */
        .ambient-light {
            position: fixed;
            inset: 0;
            z-index: -1;
            background: radial-gradient(circle at 50% -20%, rgba(165, 112, 240, 0.03) 0%, transparent 70%);
        }

        /* Precision Header */
        .preview-header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
        }

        /* Layout Container */
        .main-showcase {
            padding-top: 64px;
            padding-bottom: 120px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Paper Canvas */
        .paper-wrapper {
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .paper-container {
            background: white;
            width: 210mm;
            min-height: 297mm;
            /* Multi-layered soft shadows for 'expensive' feel */
            box-shadow: 
                0 0 0 1px rgba(0, 0, 0, 0.03),
                0 2px 4px rgba(0, 0, 0, 0.02),
                0 12px 24px rgba(0, 0, 0, 0.03),
                0 32px 64px -12px rgba(0, 0, 0, 0.05);
            transform-origin: top center;
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            padding: 10mm;
            border-radius: 2px;
        }

        /* Minimal Controls */
        .control-dock {
            position: fixed;
            bottom: 32px;
            left: 50%;
            transform: translateX(-50%);
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.08);
            padding: 6px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            z-index: 1000;
        }

        /* Buttons */
        .btn-minimal {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 14px;
            font-size: 11px;
            font-weight: 700;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .btn-dark {
            background: #0f172a;
            color: white;
        }
        .btn-dark:hover {
            background: #1e293b;
            transform: translateY(-1px);
        }
        .btn-light {
            background: #f8fafc;
            color: #64748b;
            border: 1px solid #f1f5f9;
        }
        .btn-light:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        .zoom-group {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 0 8px;
        }
        .zoom-icon {
            width: 28px; height: 28px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 10px;
            color: #94a3b8;
            transition: all 0.2s;
        }
        .zoom-icon:hover {
            background: #f8fafc;
            color: #0f172a;
        }

        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .main-showcase { padding: 0 !important; }
            .paper-wrapper { padding: 0 !important; }
            .paper-container { 
                box-shadow: none !important; 
                transform: none !important; 
                width: 100% !important; 
                padding: 0 !important;
                border: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="ambient-light no-print"></div>

    <!-- Precision Header -->
    <header class="preview-header no-print">
        <div class="flex items-center gap-4">
            <a href="{{ route('cv.builder') }}" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-50 transition-colors">
                <i class="ph-bold ph-arrow-left text-slate-400"></i>
            </a>
            <div class="h-4 w-px bg-slate-200"></div>
            <div>
                <h1 class="text-xs font-black text-slate-900 tracking-tight uppercase leading-none">CV Preview</h1>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $template }} Template</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="btn-minimal btn-light">
                <i class="ph-bold ph-printer"></i>
                Print
            </button>
            <button onclick="window.print()" class="btn-minimal btn-dark">
                <i class="ph-bold ph-download-simple"></i>
                Export PDF
            </button>
        </div>
    </header>

    <main class="main-showcase">
        <div class="paper-wrapper">
            <div id="cv-paper" class="paper-container">
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

    <!-- Minimal Control Dock -->
    <div class="control-dock no-print">
        <div class="zoom-group">
            <button onclick="changeZoom(-0.1)" class="zoom-icon">
                <i class="ph-bold ph-minus"></i>
            </button>
            <span id="zoom-text" class="text-[10px] font-black text-slate-900 min-w-[40px] text-center">100%</span>
            <button onclick="changeZoom(0.1)" class="zoom-icon">
                <i class="ph-bold ph-plus"></i>
            </button>
        </div>
        <div class="w-px h-4 bg-slate-100"></div>
        <button onclick="resetZoom()" class="btn-minimal btn-light !px-3">
            Fit to Screen
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
            const padding = window.innerWidth < 768 ? 20 : 80;
            const containerWidth = window.innerWidth - padding;
            const paperWidth = 794; 
            
            if (window.innerWidth < 1000) {
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

        window.addEventListener('load', resetZoom);
        window.addEventListener('resize', resetZoom);

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

