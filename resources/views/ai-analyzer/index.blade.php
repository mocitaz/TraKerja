<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-2xl font-black text-slate-900 leading-tight tracking-tight">
                AI <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#d983e4] via-primary-600 to-[#4e71c5]">Analyzer</span>
            </h1>
            <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest mt-1">Smart resume matching against job requirements</p>
        </div>
    </x-slot>

    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        @keyframes scanLine {
            0%   { transform: translateY(-100%); opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: 1; }
            100% { transform: translateY(400%); opacity: 0; }
        }
        @keyframes pulse-border {
            0%, 100% { border-color: rgb(221, 214, 254); }
            50%       { border-color: rgb(165, 112, 240); }
        }
        .upload-scanning::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, transparent, rgba(165,112,240,0.06) 50%, transparent);
            animation: scanLine 2s ease-in-out infinite;
            pointer-events: none;
            border-radius: inherit;
        }
        .drag-active {
            border-color: rgb(165,112,240) !important;
            background-color: rgb(245,243,255) !important;
        }
        .char-bar-fill { transition: width 0.3s ease; }
        
        .mesh-gradient-ai {
            background-color: #ffffff;
            background-image: 
                radial-gradient(at 0% 0%, rgba(217, 131, 228, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(78, 113, 197, 0.05) 0px, transparent 50%);
        }
        .bento-step-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.8), 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        }
    </style>

    <div class="bg-[#f8fafc] min-h-screen pb-20">
        <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 pt-8">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                {{-- ── Main Form ─────────────────────────────────────── --}}
                <div class="lg:col-span-8 space-y-6">

                    @if ($errors->has('analyze_error'))
                    <div class="flex items-start gap-4 bg-rose-50 border border-rose-200 rounded-3xl px-6 py-5 shadow-sm">
                        <i class="ph-bold ph-warning-circle text-rose-500 text-2xl shrink-0"></i>
                        <p class="text-sm font-bold text-rose-700 leading-relaxed">{{ $errors->first('analyze_error') }}</p>
                    </div>
                    @endif

                    <form action="{{ route('ai-analyzer.analyze') }}" method="POST" enctype="multipart/form-data" id="analyzeForm">
                        @csrf

                        {{-- ── Step 1: Upload ─── --}}
                        <div class="mesh-gradient-ai border border-slate-200/70 rounded-[2.5rem] shadow-sm overflow-hidden mb-6 bento-step-card">
                            <div class="px-8 pt-7 pb-5 border-b border-slate-100 flex items-center gap-4">
                                <div class="w-10 h-10 bg-primary-600 rounded-2xl flex items-center justify-center text-white font-black text-sm shadow-lg shadow-primary-100">1</div>
                                <div>
                                    <h3 class="text-base font-black text-slate-900 tracking-tight">Upload Resume</h3>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">High precision parsing</p>
                                </div>
                            </div>

                            <div class="p-8">
                                {{-- Drop Zone --}}
                                <div id="upload-area"
                                     class="relative group border-2 border-dashed border-slate-200 rounded-[2rem] p-12 text-center cursor-pointer transition-all duration-300 hover:border-primary-400 hover:bg-white hover:shadow-xl hover:shadow-primary-50/50 overflow-hidden"
                                     ondragover="handleDragOver(event)"
                                     ondragleave="handleDragLeave(event)"
                                     ondrop="handleDrop(event)">
                                    <input id="resume" name="resume" type="file" accept=".pdf"
                                           class="absolute inset-0 opacity-0 cursor-pointer z-10 w-full h-full" required>
                                    <div class="relative z-10 flex flex-col items-center gap-4">
                                        <div class="w-16 h-16 bg-slate-50 border border-slate-200 rounded-3xl flex items-center justify-center group-hover:bg-primary-50 group-hover:border-primary-200 transition-all shadow-sm">
                                            <i class="ph-bold ph-file-arrow-up text-slate-400 group-hover:text-primary-500 text-3xl transition-colors"></i>
                                        </div>
                                        <div>
                                            <p class="text-base font-black text-slate-800">Drop your PDF here or <span class="text-primary-600 underline decoration-2 underline-offset-4">browse</span></p>
                                            <p class="text-xs text-slate-400 font-bold mt-1.5 uppercase tracking-widest">PDF format · Max 10 MB</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- File selected state --}}
                                <div id="file-success" class="hidden mt-4">
                                    <div class="flex items-center gap-5 bg-white border border-emerald-200 rounded-[1.5rem] px-6 py-5 shadow-lg shadow-emerald-50/50">
                                        <div class="w-14 h-14 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center justify-center shrink-0">
                                            <i class="ph-fill ph-file-pdf text-rose-500 text-3xl"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p id="file-name" class="text-[15px] font-black text-slate-900 truncate tracking-tight"></p>
                                            <p id="file-size" class="text-[11px] text-slate-500 font-black mt-1 uppercase tracking-tighter"></p>
                                        </div>
                                        <div class="flex items-center gap-3 shrink-0">
                                            <span class="text-[10px] font-black text-emerald-600 bg-emerald-50 border border-emerald-100 px-3 py-1.5 rounded-full uppercase tracking-widest">Analysis Ready</span>
                                            <button type="button" id="remove-file" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all border border-transparent hover:border-rose-100">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── Step 2: Job Description ─── --}}
                        <div class="mesh-gradient-ai border border-slate-200/70 rounded-[2.5rem] shadow-sm overflow-hidden mb-8 bento-step-card">
                            <div class="px-8 pt-7 pb-5 border-b border-slate-100 flex items-center gap-4">
                                <div class="w-10 h-10 bg-primary-600 rounded-2xl flex items-center justify-center text-white font-black text-sm shadow-lg shadow-primary-100">2</div>
                                <div>
                                    <h3 class="text-base font-black text-slate-900 tracking-tight">Job Requirements</h3>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">contextual matching engine</p>
                                </div>
                            </div>

                            <div class="p-8">
                                <div class="relative group">
                                    <textarea id="job_description" name="job_description" rows="10"
                                              class="w-full px-6 pt-5 pb-12 bg-slate-50/50 border border-slate-200 rounded-[1.5rem] text-[15px] font-bold text-slate-700 focus:ring-4 focus:ring-primary-500/5 focus:border-primary-400 focus:bg-white transition-all outline-none resize-none leading-relaxed shadow-inner"
                                              placeholder="Paste the job description here. Include requirements, responsibilities, and key skills for best results..." required minlength="50" maxlength="2500">{{ old('job_description') }}</textarea>

                                    {{-- Char counter bar --}}
                                    <div class="absolute bottom-4 left-6 right-6">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center gap-2">
                                                <div id="char-indicator" class="w-2 h-2 rounded-full bg-slate-300"></div>
                                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest"><span id="char-count">0</span> / 2500</span>
                                            </div>
                                            <span id="char-hint" class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Detail is key</span>
                                        </div>
                                        <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden shadow-inner">
                                            <div id="char-bar" class="char-bar-fill h-full rounded-full bg-slate-300 shadow-sm" style="width:0%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── Actions ─── --}}
                        <div class="flex items-center justify-between px-2">
                            <a href="{{ route('tracker') }}"
                               class="flex items-center gap-2 text-sm font-black text-slate-400 hover:text-slate-900 transition-colors uppercase tracking-[0.15em]">
                                <i class="ph ph-arrow-left text-base"></i> Back
                            </a>
                            <button type="submit" id="submit-btn"
                                    class="group flex items-center gap-3 px-10 py-4 bg-primary-600 text-white rounded-[1.25rem] font-black text-sm hover:bg-primary-700 transition-all shadow-2xl shadow-primary-200 active:scale-95">
                                <i id="loading-spinner" class="ph ph-spinner animate-spin hidden text-lg"></i>
                                <i id="submit-icon" class="ph ph-lightning text-lg group-hover:scale-125 transition-transform"></i>
                                <span id="submit-text" class="uppercase tracking-widest">Start Analysis</span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ── Sidebar ────────────────────────────────────────── --}}
                <div class="lg:col-span-4 space-y-6">

                    {{-- How it works --}}
                    <div class="bg-white border border-slate-200/70 rounded-[2.5rem] p-8 shadow-sm bento-step-card">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] mb-7">Analysis Engine</p>
                        <div class="space-y-6">
                            @foreach([
                                ['ph-upload-simple',    'blue',      'Data Extraction',  'Advanced NLP parsing of your professional history and skills.'],
                                ['ph-clipboard-text',   'purple',    'Contextual Mapping', 'Cross-referencing resume data with job intent and keywords.'],
                                ['ph-sparkle',          'indigo',    'Actionable Insights',  'Generating gap analysis and specific improvement roadmaps.'],
                            ] as [$icon, $color, $title, $desc])
                            <div class="flex gap-4 group">
                                <div class="w-10 h-10 bg-{{ $color }}-50 rounded-2xl flex items-center justify-center shrink-0 shadow-sm transition-transform group-hover:scale-110">
                                    <i class="ph-bold {{ $icon }} text-{{ $color }}-500 text-lg"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-black text-slate-900 tracking-tight leading-none mb-1.5">{{ $title }}</p>
                                    <p class="text-[11px] text-slate-500 font-bold leading-relaxed">{{ $desc }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Privacy card --}}
                    <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-2xl">
                        <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-primary-600/20 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="absolute -left-4 -top-4 w-24 h-24 bg-primary-500/10 rounded-full blur-2xl pointer-events-none"></div>
                        <div class="relative z-10">
                            <div class="w-11 h-11 bg-white/10 border border-white/10 rounded-2xl flex items-center justify-center mb-5 shadow-lg">
                                <i class="ph-fill ph-shield-check text-primary-400 text-xl"></i>
                            </div>
                            <h4 class="text-[15px] font-black tracking-tight mb-2 uppercase tracking-wide">Privacy Standard</h4>
                            <p class="text-xs text-slate-400 font-bold leading-relaxed italic opacity-80">
                                Secure end-to-end processing. Your data is analyzed in-memory and never persistent on our storage.
                            </p>
                        </div>
                    </div>

                    {{-- Tips --}}
                    <div class="bg-amber-50 border border-amber-200/60 rounded-[2rem] p-7 shadow-sm">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600">
                                <i class="ph-fill ph-lightbulb text-lg"></i>
                            </div>
                            <p class="text-[10px] font-black text-amber-700 uppercase tracking-[0.15em]">Pro Tips</p>
                        </div>
                        <ul class="space-y-3">
                            @foreach([
                                'Use standard PDF encoding',
                                'Paste full requirements text',
                                'Clear, structured resume layout',
                            ] as $tip)
                            <li class="flex items-start gap-3 text-[11px] text-amber-800 font-black italic">
                                <i class="ph-bold ph-check text-amber-500 text-xs mt-0.5 shrink-0"></i>
                                {{ $tip }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
            </div>
        </div>
    </div>

    <script>
        // ── Upload area drag & drop ───────────────────────────────
        const resumeInput   = document.getElementById('resume');
        const uploadArea    = document.getElementById('upload-area');
        const fileSuccess   = document.getElementById('file-success');
        const fileNameEl    = document.getElementById('file-name');
        const fileSizeEl    = document.getElementById('file-size');
        const removeFileBtn = document.getElementById('remove-file');

        function showFile(file) {
            const mb = (file.size / (1024 * 1024)).toFixed(2);
            fileNameEl.textContent = file.name;
            fileSizeEl.textContent = mb + ' MB';
            fileSuccess.classList.remove('hidden');
            uploadArea.classList.add('hidden');
        }

        resumeInput.addEventListener('change', e => {
            if (e.target.files[0]) showFile(e.target.files[0]);
        });

        removeFileBtn.addEventListener('click', () => {
            resumeInput.value = '';
            fileSuccess.classList.add('hidden');
            uploadArea.classList.remove('hidden');
        });

        function handleDragOver(e) {
            e.preventDefault();
            uploadArea.classList.add('drag-active');
        }
        function handleDragLeave(e) {
            uploadArea.classList.remove('drag-active');
        }
        function handleDrop(e) {
            e.preventDefault();
            uploadArea.classList.remove('drag-active');
            const file = e.dataTransfer.files[0];
            // Relaxed check: accept if it's PDF or if the name ends in .pdf
            if (file && (file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf'))) {
                const dt = new DataTransfer();
                dt.items.add(file);
                resumeInput.files = dt.files;
                showFile(file);
            }
        }

        // ── Char counter ──────────────────────────────────────────
        const textarea      = document.getElementById('job_description');
        const charCountEl   = document.getElementById('char-count');
        const charIndicator = document.getElementById('char-indicator');
        const charBar       = document.getElementById('char-bar');
        const charHint      = document.getElementById('char-hint');

        textarea.addEventListener('input', function () {
            const len = this.value.length;
            charCountEl.textContent = len;
            const pct = Math.min((len / 2500) * 100, 100);
            charBar.style.width = pct + '%';

            if (len < 50) {
                charIndicator.className = 'w-1.5 h-1.5 rounded-full bg-rose-400';
                charBar.className       = 'char-bar-fill h-full rounded-full bg-rose-400';
                charHint.textContent    = 'Minimum 50 characters';
                charHint.className      = 'text-[10px] font-bold text-rose-500';
            } else if (len < 300) {
                charIndicator.className = 'w-1.5 h-1.5 rounded-full bg-amber-400';
                charBar.className       = 'char-bar-fill h-full rounded-full bg-amber-400';
                charHint.textContent    = 'Add more detail for better accuracy';
                charHint.className      = 'text-[10px] font-bold text-amber-500';
            } else {
                charIndicator.className = 'w-1.5 h-1.5 rounded-full bg-emerald-400';
                charBar.className       = 'char-bar-fill h-full rounded-full bg-emerald-400';
                charHint.textContent    = 'Looking good!';
                charHint.className      = 'text-[10px] font-bold text-emerald-500';
            }
        });

        // ── Submit ────────────────────────────────────────────────
        document.getElementById('analyzeForm').addEventListener('submit', function () {
            const btn = document.getElementById('submit-btn');
            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-not-allowed');
            document.getElementById('submit-text').textContent = 'Analyzing…';
            document.getElementById('submit-icon').classList.add('hidden');
            document.getElementById('loading-spinner').classList.remove('hidden');
        });
    </script>
</x-app-layout>
