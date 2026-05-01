<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CV Preview — {{ ucfirst($template) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 50: '#eef2ff', 100: '#e0e7ff', 500: '#6366f1', 600: '#4f46e5', 700: '#4338ca' }
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
            background-color: #f1f5f9;
            color: #1e293b;
            margin: 0;
            overflow-x: hidden;
        }

        /* Top Bar */
        .preview-header {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.03);
        }

        /* Paper area */
        .main-content {
            padding-top: 64px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: #f1f5f9;
        }

        .paper-wrapper {
            padding: 40px 20px 60px;
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .paper-container {
            background: white;
            width: 210mm;
            min-height: 297mm;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.07), 0 20px 60px rgba(0,0,0,0.08);
            transform-origin: top center;
            transition: transform 0.2s cubic-bezier(0.4,0,0.2,1);
            position: relative;
            padding: 15mm;
            border-radius: 2px;
        }

        /* Zoom controls */
        .zoom-pill {
            display: flex;
            align-items: center;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 3px;
            gap: 2px;
        }

        .zoom-btn {
            width: 28px; height: 28px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 7px;
            color: #64748b;
            transition: all 0.15s;
            cursor: pointer;
            font-size: 16px;
        }

        .zoom-btn:hover {
            background: white;
            color: #4f46e5;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .zoom-value {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            min-width: 36px;
            text-align: center;
            letter-spacing: 0.05em;
        }

        /* Buttons */
        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-dark {
            background: #0f172a;
            color: white;
            border: 1px solid #0f172a;
        }
        .btn-dark:hover {
            background: #1e293b;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(15,23,42,0.2);
        }

        .btn-ghost {
            background: transparent;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }
        .btn-ghost:hover {
            background: #f8fafc;
            color: #1e293b;
            border-color: #cbd5e1;
        }

        .btn-indigo {
            background: #4f46e5;
            color: white;
            border: 1px solid #4338ca;
        }
        .btn-indigo:hover {
            background: #4338ca;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79,70,229,0.3);
        }

        /* Template badge */
        .template-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            background: #eef2ff;
            color: #4f46e5;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        /* Page indicator */
        .page-info {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(15,23,42,0.75);
            backdrop-filter: blur(8px);
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            z-index: 50;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .page-info.show { opacity: 1; }

        @media print {
            .no-print { display: none !important; }
            body {
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .main-content { padding: 0 !important; display: block !important; }
            .paper-wrapper { padding: 0 !important; display: block !important; }
            .paper-container {
                box-shadow: none !important;
                transform: none !important;
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
                border-radius: 0 !important;
            }
        }

        @media (max-width: 1024px) {
            .paper-container { width: 100%; }
        }
    </style>
</head>
<body>
    <!-- Premium Header Bar -->
    <header class="preview-header no-print">
        <!-- Left: Close + Title -->
        <div class="flex items-center gap-4">
            <button onclick="window.history.back()" class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all" title="Back">
                <i class="ph ph-arrow-left text-lg"></i>
            </button>
            <div class="h-5 w-px bg-slate-200"></div>
            <div class="flex items-center gap-3">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">CV Preview</p>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</span>
                        <span class="template-badge"><i class="ph-fill ph-layout text-[10px]"></i>{{ $template }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Center: Zoom Controls -->
        <div class="flex items-center gap-3 no-print">
            <div class="zoom-pill">
                <button onclick="changeZoom(-0.1)" class="zoom-btn" title="Zoom Out">
                    <i class="ph ph-minus text-sm"></i>
                </button>
                <span id="zoom-text" class="zoom-value">100%</span>
                <button onclick="changeZoom(0.1)" class="zoom-btn" title="Zoom In">
                    <i class="ph ph-plus text-sm"></i>
                </button>
            </div>
            <button onclick="resetZoom()" class="text-[11px] font-bold text-slate-400 hover:text-primary-600 transition-colors px-1">Reset</button>
        </div>

        <!-- Right: Actions -->
        <div class="flex items-center gap-2 no-print">
            <a href="{{ route('cv.builder') }}" class="btn btn-ghost">
                <i class="ph ph-pencil-simple text-base"></i>
                Edit
            </a>
            <button onclick="window.print()" class="btn btn-indigo">
                <i class="ph ph-printer text-base"></i>
                Print / Save PDF
            </button>
        </div>
    </header>

    <main class="main-content">
        <!-- CV Paper -->
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

    <!-- Page Indicator (shows on zoom change) -->
    <div id="page-info" class="page-info no-print">A4 · 210 × 297mm</div>

    <script>
        let currentZoom = 1;
        const paper = document.getElementById('cv-paper');
        const zoomText = document.getElementById('zoom-text');
        const pageInfo = document.getElementById('page-info');
        let hideTimer;

        function changeZoom(delta) {
            currentZoom = Math.min(Math.max(0.4, currentZoom + delta), 1.5);
            updateZoom();
        }

        function resetZoom() {
            currentZoom = window.innerWidth < 1200 ? 0.75 : 1;
            updateZoom();
        }

        function updateZoom() {
            paper.style.transform = `scale(${currentZoom})`;
            zoomText.innerText = `${Math.round(currentZoom * 100)}%`;

            // Show page info pill
            pageInfo.classList.add('show');
            clearTimeout(hideTimer);
            hideTimer = setTimeout(() => pageInfo.classList.remove('show'), 2000);
        }

        // Auto-scale on small screens
        if (window.innerWidth < 1200) {
            currentZoom = 0.75;
            updateZoom();
        }

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
