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
            padding-top: 84px;
            padding-bottom: 120px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Paper Canvas */
        .paper-wrapper {
            padding: 20px;
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .paper-container {
            background: white;
            width: 210mm;
            min-height: 297mm;
            box-shadow: 
                0 0 0 1px rgba(0, 0, 0, 0.03),
                0 2px 4px rgba(0, 0, 0, 0.02),
                0 12px 24px rgba(0, 0, 0, 0.03),
                0 32px 64px -12px rgba(0, 0, 0, 0.05);
            transform-origin: top center;
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), padding 0.3s ease, font-size 0.3s ease;
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

        @keyframes scaleUp {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .animate-scale-up {
            animation: scaleUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
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

    <form id="export-form" action="{{ route('cv-builder.export') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="template" value="{{ $template }}">
        <input type="hidden" name="margin" id="export-margin" value="{{ $template == 'creative' ? '20mm' : '15mm' }}">
        <input type="hidden" name="font_size" id="export-font-size" value="9.5pt">
    </form>

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
            <button onclick="simulateATS()" id="ats-btn" class="btn-minimal bg-blue-600 hover:bg-blue-700 text-white shadow-md shadow-blue-500/20 px-4 py-2 transition-all">
                <i class="ph-bold ph-magic-wand text-sm"></i>
                <span id="ats-btn-text">Simulasikan ATS</span>
            </button>
            <button onclick="window.print()" class="btn-minimal btn-light">
                <i class="ph-bold ph-printer"></i>
                Print
            </button>
            <button onclick="submitExport()" class="btn-minimal btn-dark">
                <i class="ph-bold ph-download-simple"></i>
                Export PDF
            </button>
        </div>
    </header>

    <main class="main-showcase">
        <!-- Layout Control Bar -->
        <div class="no-print bg-white border border-slate-200 rounded-xl px-5 py-3 mb-6 shadow-sm flex flex-wrap items-center justify-center gap-6 w-auto mx-4 z-50">
            <div class="flex items-center gap-3">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
                    <i class="ph-fill ph-corners-out text-blue-600"></i> Margin
                </span>
                <div class="flex bg-slate-50 p-1 rounded-lg border border-slate-100 gap-1">
                    <button onclick="updateMargin('compact', this)" class="margin-btn px-3 py-1.5 rounded-md text-[11px] font-semibold text-slate-600 hover:text-slate-900 transition-all">Compact</button>
                    <button onclick="updateMargin('standard', this)" class="margin-btn px-3 py-1.5 rounded-md text-[11px] font-semibold bg-white text-slate-900 shadow-sm border border-slate-200 transition-all">Standard</button>
                    <button onclick="updateMargin('spacious', this)" class="margin-btn px-3 py-1.5 rounded-md text-[11px] font-semibold text-slate-600 hover:text-slate-900 transition-all">Spacious</button>
                </div>
            </div>

            <div class="h-5 w-px bg-slate-200 hidden md:block"></div>

            <div class="flex items-center gap-3">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
                    <i class="ph-fill ph-text-t text-blue-600"></i> Font Size
                </span>
                <div class="flex bg-slate-50 p-1 rounded-lg border border-slate-100 gap-1">
                    <button onclick="updateFontSize('small', this)" class="font-btn px-3 py-1.5 rounded-md text-[11px] font-semibold text-slate-600 hover:text-slate-900 transition-all">Small</button>
                    <button onclick="updateFontSize('normal', this)" class="font-btn px-3 py-1.5 rounded-md text-[11px] font-semibold bg-white text-slate-900 shadow-sm border border-slate-200 transition-all">Normal</button>
                    <button onclick="updateFontSize('large', this)" class="font-btn px-3 py-1.5 rounded-md text-[11px] font-semibold text-slate-600 hover:text-slate-900 transition-all">Large</button>
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

    <!-- ATS Score Modal -->
    <div id="ats-modal" class="fixed inset-0 z-[200] hidden flex items-center justify-center p-4 sm:p-0">
        <!-- Backdrop -->
        <div onclick="closeATSModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
        
        <!-- Modal Content -->
        <div class="relative bg-white rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden transform transition-all animate-scale-up flex flex-col max-h-[90vh]">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                        <i class="ph-fill ph-robot"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Laporan Simulasi ATS</h2>
                        <p class="text-[10px] text-slate-500 uppercase tracking-wider font-semibold">Pre-Check System</p>
                    </div>
                </div>
                <button onclick="closeATSModal()" class="text-slate-400 hover:text-slate-700 transition-colors">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>

            <!-- Scrollable Content -->
            <div class="overflow-y-auto flex-grow p-6 space-y-6">
                <!-- Score Display -->
                <div class="flex items-center justify-between p-5 rounded-xl border border-slate-100 bg-white shadow-sm">
                    <div>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Total Score</span>
                        <div class="flex items-baseline gap-1.5">
                            <span id="ats-score-display" class="text-4xl font-black text-slate-900">0</span>
                            <span class="text-slate-400 font-semibold text-sm">/ 100</span>
                        </div>
                        <p id="ats-status-badge" class="mt-1 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                            Analyzing...
                        </p>
                    </div>
                    <div class="relative w-20 h-20 flex items-center justify-center">
                        <svg class="w-full h-full transform -rotate-90">
                            <circle cx="40" cy="40" r="34" class="text-slate-100" stroke-width="8" stroke="currentColor" fill="transparent"/>
                            <circle id="ats-circle" cx="40" cy="40" r="34" class="text-blue-600 transition-all duration-1000 ease-out" stroke-width="8" stroke-dasharray="213.6" stroke-dashoffset="213.6" stroke-linecap="round" stroke="currentColor" fill="transparent"/>
                        </svg>
                        <i class="ph-fill ph-check-circle absolute text-2xl text-blue-600 bg-white rounded-full"></i>
                    </div>
                </div>

                <!-- Checks Breakdown -->
                <div>
                    <h3 class="text-[11px] font-bold uppercase text-slate-500 tracking-wider mb-3">Detail Evaluasi</h3>
                    <div id="ats-checks-list" class="space-y-2">
                        <!-- Dynamic checks injected here -->
                    </div>
                </div>

                <!-- Actionable Tips -->
                <div id="ats-tips-container">
                    <h3 class="text-[11px] font-bold uppercase text-slate-500 tracking-wider mb-3">Rekomendasi</h3>
                    <div id="ats-tips-list" class="space-y-2">
                        <!-- Dynamic tips injected here -->
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end">
                <button onclick="closeATSModal()" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-bold rounded-lg hover:bg-slate-50 transition-colors">Tutup</button>
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
            document.querySelectorAll('.margin-btn').forEach(b => b.className = 'margin-btn px-3 py-1.5 rounded-md text-[11px] font-semibold text-slate-600 hover:text-slate-900 transition-all');
            btn.className = 'margin-btn px-3 py-1.5 rounded-md text-[11px] font-semibold bg-white text-slate-900 shadow-sm border border-slate-200 transition-all';
            
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
            document.querySelectorAll('.font-btn').forEach(b => b.className = 'font-btn px-3 py-1.5 rounded-md text-[11px] font-semibold text-slate-600 hover:text-slate-900 transition-all');
            btn.className = 'font-btn px-3 py-1.5 rounded-md text-[11px] font-semibold bg-white text-slate-900 shadow-sm border border-slate-200 transition-all';
            
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
            btnText.innerHTML = '<i class="ph-bold ph-spinner animate-spin inline-block mr-1"></i> Menganalisis...';

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
                btnText.innerHTML = 'Simulasikan ATS';
                
                if (data.success) {
                    showATSModal(data);
                } else {
                    alert('Gagal mensimulasikan ATS');
                }
            })
            .catch(err => {
                console.error(err);
                btn.disabled = false;
                btnText.innerHTML = 'Simulasikan ATS';
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
                statusBadge.className = 'mt-1 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100';
                statusBadge.innerText = 'Excellent Fit';
            } else if (data.score >= 60) {
                statusBadge.className = 'mt-1 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-100';
                statusBadge.innerText = 'Moderate Fit';
            } else {
                statusBadge.className = 'mt-1 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-100';
                statusBadge.innerText = 'Needs Optimization';
            }

            const circumference = 213.6;
            const offset = circumference - (data.score / 100) * circumference;
            setTimeout(() => {
                circle.style.strokeDashoffset = offset;
            }, 100);

            checksList.innerHTML = '';
            data.checks.forEach(c => {
                let statusIcon = '<i class="ph-fill ph-check-circle text-emerald-500 text-lg"></i>';
                if (c.status === 'warning') statusIcon = '<i class="ph-fill ph-warning-circle text-amber-500 text-lg"></i>';
                if (c.status === 'danger') statusIcon = '<i class="ph-fill ph-x-circle text-rose-500 text-lg"></i>';

                checksList.innerHTML += `
                    <div class="p-3 rounded-xl border border-slate-100 bg-white flex items-start gap-3 shadow-sm">
                        <div class="mt-0.5">${statusIcon}</div>
                        <div class="flex-grow">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-800">${c.name}</span>
                                <span class="text-[10px] font-bold text-slate-500 bg-slate-50 px-1.5 py-0.5 rounded">${c.score}/${c.max} pts</span>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">${c.message}</p>
                        </div>
                    </div>
                `;
            });

            tipsList.innerHTML = '';
            if (data.tips && data.tips.length > 0) {
                document.getElementById('ats-tips-container').style.display = 'block';
                data.tips.forEach(t => {
                    tipsList.innerHTML += `
                        <div class="flex items-start gap-2.5 text-[11px] font-medium text-slate-700 bg-blue-50/50 border border-blue-100/50 p-3 rounded-xl">
                            <i class="ph-fill ph-lightbulb text-blue-500 mt-0.5 flex-shrink-0 text-sm"></i>
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
