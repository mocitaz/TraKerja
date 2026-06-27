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
            background-color: #fafafa;
            color: #18181b;
            margin: 0;
            overflow-x: hidden;
        }

        /* Precision Header */
        .preview-header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
        }

        /* Layout Container */
        .main-showcase {
            padding-top: 76px;
            padding-bottom: 100px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Paper Canvas */
        .paper-wrapper {
            padding: 16px;
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .paper-container {
            background: white;
            width: 210mm;
            min-height: 297mm;
            box-shadow: 
                0 0 0 1px rgba(0, 0, 0, 0.04),
                0 2px 4px rgba(0, 0, 0, 0.01),
                0 8px 16px rgba(0, 0, 0, 0.02);
            transform-origin: top center;
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), padding 0.2s ease, font-size 0.2s ease;
            position: relative;
            padding: 10mm;
            border-radius: 4px;
            border: 1px solid #e4e4e7;
        }

        /* Minimal Controls */
        .control-dock {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%);
            background: #ffffff;
            border: 1px solid #e4e4e7;
            padding: 4px 6px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 10px -2px rgba(0, 0, 0, 0.05);
            z-index: 1000;
        }

        /* Buttons */
        .btn-minimal {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .btn-dark {
            background: #18181b;
            color: white;
        }
        .btn-dark:hover {
            background: #27272a;
        }
        .btn-light {
            background: #ffffff;
            color: #71717a;
            border: 1px solid #e4e4e7;
        }
        .btn-light:hover {
            background: #f4f4f5;
            color: #18181b;
            border-color: #d4d4d8;
        }

        .zoom-group {
            display: flex;
            align-items: center;
            gap: 2px;
            padding: 0 6px;
        }
        .zoom-icon {
            width: 24px; height: 24px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 4px;
            color: #a1a1aa;
            transition: all 0.15s;
        }
        .zoom-icon:hover {
            background: #f4f4f5;
            color: #18181b;
        }

        @keyframes scaleUp {
            from { opacity: 0; transform: scale(0.98) translateY(6px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .animate-scale-up {
            animation: scaleUp 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
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
    <form id="export-form" action="{{ route('cv-builder.export') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="template" value="{{ $template }}">
        <input type="hidden" name="margin" id="export-margin" value="{{ $template == 'creative' ? '20mm' : '15mm' }}">
        <input type="hidden" name="font_size" id="export-font-size" value="9.5pt">
    </form>

    <!-- Precision Header -->
    <header class="preview-header no-print">
        <div class="flex items-center gap-2.5">
            <a href="{{ route('cv.builder') }}" class="w-7 h-7 flex items-center justify-center rounded hover:bg-zinc-100 transition-colors">
                <i class="ph ph-arrow-left text-zinc-400"></i>
            </a>
            <div class="h-4 w-px bg-zinc-200"></div>
            <div>
                <h1 class="text-[11px] font-bold text-zinc-800 tracking-tight uppercase leading-none">CV Preview</h1>
                <p class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest mt-0.5">{{ $template }} Template</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button onclick="simulateATS()" id="ats-btn" class="btn-minimal bg-blue-50/70 hover:bg-blue-100 text-blue-700 border border-blue-200/50 shadow-3xs transition-all focus:outline-none">
                <i class="ph ph-magic-wand text-xs"></i>
                <span id="ats-btn-text">Simulate ATS</span>
            </button>
            <button onclick="window.print()" class="btn-minimal btn-light shadow-3xs focus:outline-none">
                <i class="ph ph-printer text-xs"></i>
                <span>Print</span>
            </button>
            <button onclick="submitExport()" class="btn-minimal btn-dark shadow-3xs focus:outline-none">
                <i class="ph ph-download-simple text-xs"></i>
                <span>Export PDF</span>
            </button>
        </div>
    </header>

    <main class="main-showcase">
        <!-- Layout Control Bar -->
        <div class="no-print bg-white border border-zinc-200 rounded-lg px-4 py-2.5 mb-5 shadow-3xs flex flex-wrap items-center justify-center gap-5 w-auto mx-4 z-50">
            <div class="flex items-center gap-2.5">
                <span class="text-[10px] font-bold text-zinc-450 uppercase tracking-wider flex items-center gap-1">
                    <i class="ph ph-corners-out text-primary-500"></i> Margin
                </span>
                <div class="flex bg-zinc-50 p-0.5 rounded border border-zinc-200/60 gap-0.5">
                    <button onclick="updateMargin('compact', this)" class="margin-btn px-2 py-1 rounded text-[10px] font-semibold text-zinc-500 hover:text-zinc-800 transition-colors focus:outline-none">Compact</button>
                    <button onclick="updateMargin('standard', this)" class="margin-btn px-2 py-1 rounded text-[10px] font-semibold bg-white text-zinc-800 shadow-3xs border border-zinc-200 transition-colors focus:outline-none">Standard</button>
                    <button onclick="updateMargin('spacious', this)" class="margin-btn px-2 py-1 rounded text-[10px] font-semibold text-zinc-500 hover:text-zinc-800 transition-colors focus:outline-none">Spacious</button>
                </div>
            </div>

            <div class="h-4 w-px bg-zinc-200 hidden md:block"></div>

            <div class="flex items-center gap-2.5">
                <span class="text-[10px] font-bold text-zinc-450 uppercase tracking-wider flex items-center gap-1">
                    <i class="ph ph-text-t text-primary-500"></i> Font Size
                </span>
                <div class="flex bg-zinc-50 p-0.5 rounded border border-zinc-200/60 gap-0.5">
                    <button onclick="updateFontSize('small', this)" class="font-btn px-2 py-1 rounded text-[10px] font-semibold text-zinc-500 hover:text-zinc-800 transition-colors focus:outline-none">Small</button>
                    <button onclick="updateFontSize('normal', this)" class="font-btn px-2 py-1 rounded text-[10px] font-semibold bg-white text-zinc-800 shadow-3xs border border-zinc-200 transition-colors focus:outline-none">Normal</button>
                    <button onclick="updateFontSize('large', this)" class="font-btn px-2 py-1 rounded text-[10px] font-semibold text-zinc-500 hover:text-zinc-800 transition-colors focus:outline-none">Large</button>
                </div>
            </div>
        </div>

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
    <div class="control-dock no-print shadow-3xs border border-zinc-200 rounded-md">
        <div class="zoom-group">
            <button onclick="changeZoom(-0.1)" class="zoom-icon focus:outline-none">
                <i class="ph ph-minus"></i>
            </button>
            <span id="zoom-text" class="text-[10px] font-black text-zinc-800 min-w-[36px] text-center">100%</span>
            <button onclick="changeZoom(0.1)" class="zoom-icon focus:outline-none">
                <i class="ph ph-plus"></i>
            </button>
        </div>
        <div class="w-px h-4 bg-zinc-200"></div>
        <button onclick="resetZoom()" class="btn-minimal btn-light !px-2.5 !py-1 focus:outline-none">
            Fit to Screen
        </button>
    </div>

    <!-- ATS Score Modal -->
    <div id="ats-modal" class="fixed inset-0 z-[200] hidden flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div onclick="closeATSModal()" class="absolute inset-0 bg-zinc-950/40 backdrop-blur-xs transition-opacity"></div>
        
        <!-- Modal Content -->
        <div class="relative bg-white rounded-lg w-full max-w-lg shadow-xl overflow-hidden transform transition-all animate-scale-up flex flex-col max-h-[90vh] border border-zinc-200 text-left">
            <!-- Header -->
            <div class="px-4 py-3.5 border-b border-zinc-150/60 flex items-center justify-between bg-white shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center text-blue-600 shadow-3xs">
                        <i class="ph ph-robot"></i>
                    </div>
                    <div>
                        <h2 class="text-xs font-bold text-zinc-800 tracking-tight">Laporan Simulasi ATS</h2>
                        <p class="text-[8px] text-zinc-400 uppercase tracking-widest font-bold mt-0.5">Pre-Check System</p>
                    </div>
                </div>
                <button onclick="closeATSModal()" class="w-7 h-7 flex items-center justify-center rounded hover:bg-zinc-50 text-zinc-450 hover:text-zinc-800 transition-colors focus:outline-none">
                    <i class="ph ph-x text-sm"></i>
                </button>
            </div>

            <!-- Scrollable Content -->
            <div class="overflow-y-auto flex-grow p-4 sm:p-5 space-y-5 custom-scrollbar">
                <!-- Score Display -->
                <div class="flex items-center justify-between p-4 rounded-md border border-zinc-200/60 bg-zinc-50/15 shadow-3xs">
                    <div>
                        <span class="text-[8.5px] font-bold text-zinc-400 uppercase tracking-wider block mb-1">Total Score</span>
                        <div class="flex items-baseline gap-1">
                            <span id="ats-score-display" class="text-3xl font-bold text-zinc-850">0</span>
                            <span class="text-zinc-400 font-semibold text-xs">/ 100</span>
                        </div>
                        <p id="ats-status-badge" class="mt-1 inline-flex items-center px-1.5 py-0.5 rounded text-[8.5px] font-bold bg-blue-50 text-blue-700 border border-blue-100/50">
                            Analyzing...
                        </p>
                    </div>
                    <div class="relative w-16 h-16 flex items-center justify-center">
                        <svg class="w-full h-full transform -rotate-90">
                            <circle cx="32" cy="32" r="26" class="text-zinc-150/60" stroke-width="6" stroke="currentColor" fill="transparent"/>
                            <circle id="ats-circle" cx="32" cy="32" r="26" class="text-blue-500 transition-all duration-1000 ease-out" stroke-width="6" stroke-dasharray="163.3" stroke-dashoffset="163.3" stroke-linecap="round" stroke="currentColor" fill="transparent"/>
                        </svg>
                        <i class="ph-fill ph-check-circle absolute text-xl text-blue-500 bg-white rounded-full leading-none"></i>
                    </div>
                </div>

                <!-- Checks Breakdown -->
                <div>
                    <h3 class="text-[9px] font-bold uppercase text-zinc-400 tracking-wider mb-2.5">Detail Evaluasi</h3>
                    <div id="ats-checks-list" class="space-y-2">
                        <!-- Dynamic checks injected here -->
                    </div>
                </div>

                <!-- Actionable Tips -->
                <div id="ats-tips-container">
                    <h3 class="text-[9px] font-bold uppercase text-zinc-400 tracking-wider mb-2.5">Rekomendasi</h3>
                    <div id="ats-tips-list" class="space-y-2">
                        <!-- Dynamic tips injected here -->
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="px-4 py-3 border-t border-zinc-150/60 bg-zinc-50/20 flex justify-end shrink-0">
                <button onclick="closeATSModal()" class="px-4 py-1.5 bg-white border border-zinc-200 text-zinc-700 text-xs font-bold rounded-md hover:bg-zinc-50 transition-colors focus:outline-none">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        let currentZoom = 1;
        const paper = document.getElementById('cv-paper');
        const zoomText = document.getElementById('zoom-text');
        const template = "{{ $template }}";

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

        function updateMargin(type, btn) {
            document.querySelectorAll('.margin-btn').forEach(b => b.className = 'margin-btn px-2 py-1 rounded text-[10px] font-semibold text-zinc-500 hover:text-zinc-800 transition-colors focus:outline-none');
            btn.className = 'margin-btn px-2 py-1 rounded text-[10px] font-semibold bg-white text-zinc-800 shadow-3xs border border-zinc-200 transition-colors focus:outline-none';
            
            let val = '15mm';
            if (template === 'creative') {
                val = type === 'compact' ? '12mm' : (type === 'standard' ? '20mm' : '28mm');
            } else if (template === 'professional') {
                val = '0';
            } else {
                val = type === 'compact' ? '10mm' : (type === 'standard' ? '15mm' : '22mm');
            }
            
            document.getElementById('export-margin').value = val;
            
            if (template === 'professional') {
                const p = type === 'compact' ? '8mm' : (type === 'standard' ? '12mm' : '16mm');
                const rightContent = document.querySelector('.content');
                if(rightContent) rightContent.style.padding = `${p} ${p}`;
            } else {
                paper.style.padding = val;
            }
        }

        function updateFontSize(type, btn) {
            document.querySelectorAll('.font-btn').forEach(b => b.className = 'font-btn px-2 py-1 rounded text-[10px] font-semibold text-zinc-500 hover:text-zinc-800 transition-colors focus:outline-none');
            btn.className = 'font-btn px-2 py-1 rounded text-[10px] font-semibold bg-white text-zinc-800 shadow-3xs border border-zinc-200 transition-colors focus:outline-none';
            
            const sizes = {
                small: '8.5pt',
                normal: '9.5pt',
                large: '10.5pt'
            };
            document.getElementById('export-font-size').value = sizes[type];
            paper.style.fontSize = sizes[type];
            
            // Adjust any inner elements if needed
            document.querySelectorAll('#cv-paper .description, #cv-paper .entry-description').forEach(el => {
                el.style.fontSize = sizes[type];
            });
        }

        function submitExport() {
            document.getElementById('export-form').submit();
        }

        function simulateATS() {
            const btn = document.getElementById('ats-btn');
            const btnText = document.getElementById('ats-btn-text');
            btn.disabled = true;
            btnText.innerHTML = '<i class="ph ph-spinner animate-spin inline-block mr-1"></i> Analyzing...';

            fetch("{{ route('cv-builder.simulate-ats') }}", {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btnText.innerHTML = 'Simulate ATS';
                
                if (data.success) {
                    showATSModal(data);
                } else {
                    alert('Gagal mensimulasikan ATS');
                }
            })
            .catch(err => {
                console.error(err);
                btn.disabled = false;
                btnText.innerHTML = 'Simulate ATS';
                alert('Terjadi kesalahan saat menghubungi server.');
            });
        }

        function showATSModal(data) {
            document.getElementById('ats-modal').classList.remove('hidden');
            
            const scoreDisplay = document.getElementById('ats-score-display');
            const statusBadge = document.getElementById('ats-status-badge');
            const circle = document.getElementById('ats-circle');
            const checksList = document.getElementById('ats-checks-list');
            const tipsList = document.getElementById('ats-tips-list');

            scoreDisplay.innerText = data.score;
            
            if (data.score >= 80) {
                statusBadge.className = 'mt-1 inline-flex items-center px-1.5 py-0.5 rounded text-[8.5px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100/50';
                statusBadge.innerText = 'Excellent Fit';
            } else if (data.score >= 60) {
                statusBadge.className = 'mt-1 inline-flex items-center px-1.5 py-0.5 rounded text-[8.5px] font-bold bg-amber-50 text-amber-700 border border-amber-100/50';
                statusBadge.innerText = 'Moderate Fit';
            } else {
                statusBadge.className = 'mt-1 inline-flex items-center px-1.5 py-0.5 rounded text-[8.5px] font-bold bg-rose-50 text-rose-700 border border-rose-100/50';
                statusBadge.innerText = 'Needs Optimization';
            }

            const circumference = 163.3;
            const offset = circumference - (data.score / 100) * circumference;
            setTimeout(() => {
                circle.style.strokeDashoffset = offset;
            }, 100);

            checksList.innerHTML = '';
            data.checks.forEach(c => {
                let statusIcon = '<i class="ph-fill ph-check-circle text-emerald-500 text-base"></i>';
                if (c.status === 'warning') statusIcon = '<i class="ph-fill ph-warning-circle text-amber-500 text-base"></i>';
                if (c.status === 'danger') statusIcon = '<i class="ph-fill ph-x-circle text-rose-500 text-base"></i>';

                checksList.innerHTML += `
                    <div class="p-3 rounded border border-zinc-200 bg-white flex items-start gap-2.5 shadow-3xs">
                        <div class="mt-0.5">${statusIcon}</div>
                        <div class="flex-grow">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-zinc-800 leading-none">${c.name}</span>
                                <span class="text-[9px] font-bold text-zinc-500 bg-zinc-50 px-1.5 py-0.5 rounded border border-zinc-200">${c.score}/${c.max} pts</span>
                            </div>
                            <p class="text-[10px] text-zinc-500 mt-1.5 leading-relaxed">${c.message}</p>
                        </div>
                    </div>
                `;
            });

            tipsList.innerHTML = '';
            if (data.tips && data.tips.length > 0) {
                document.getElementById('ats-tips-container').style.display = 'block';
                data.tips.forEach(t => {
                    tipsList.innerHTML += `
                        <div class="flex items-start gap-2 text-[10px] font-semibold text-zinc-700 bg-blue-50/50 border border-blue-100/50 p-3 rounded">
                            <i class="ph-fill ph-lightbulb text-blue-500 mt-0.5 flex-shrink-0 text-base"></i>
                            <span class="leading-relaxed">${t}</span>
                        </div>
                    `;
                });
            } else {
                document.getElementById('ats-tips-container').style.display = 'none';
            }
        }

        function closeATSModal() {
            document.getElementById('ats-modal').classList.add('hidden');
        }
    </script>
</body>
</html>
