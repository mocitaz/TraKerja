<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-2xl font-black text-slate-900 leading-tight tracking-tight">
                AI <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#d983e4] via-purple-600 to-[#4e71c5]">Analyzer</span>
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
            0%, 100% { border-color: rgb(199,210,254); }
            50%       { border-color: rgb(99,102,241); }
        }
        .upload-scanning::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, transparent, rgba(99,102,241,0.06) 50%, transparent);
            animation: scanLine 2s ease-in-out infinite;
            pointer-events: none;
            border-radius: inherit;
        }
        .drag-active {
            border-color: rgb(99,102,241) !important;
            background-color: rgb(238,242,255) !important;
        }
        .char-bar-fill { transition: width 0.3s ease; }
    </style>

    <div class="bg-[#f8fafc] min-h-screen pb-20">
        <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 pt-8">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- ── Main Form ─────────────────────────────────────── --}}
                <div class="lg:col-span-8 space-y-5">

                    @if ($errors->has('analyze_error'))
                    <div class="flex items-start gap-3 bg-rose-50 border border-rose-200 rounded-2xl px-5 py-4">
                        <i class="ph-bold ph-warning-circle text-rose-500 text-xl shrink-0 mt-0.5"></i>
                        <p class="text-sm font-semibold text-rose-700">{{ $errors->first('analyze_error') }}</p>
                    </div>
                    @endif

                    <form action="{{ route('ai-analyzer.analyze') }}" method="POST" enctype="multipart/form-data" id="analyzeForm">
                        @csrf

                        {{-- ── Step 1: Upload ─── --}}
                        <div class="bg-white border border-slate-200/70 rounded-3xl shadow-sm overflow-hidden mb-5">
                            <div class="px-7 pt-6 pb-4 border-b border-slate-100 flex items-center gap-3">
                                <div class="w-8 h-8 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-black text-xs">1</div>
                                <div>
                                    <h3 class="text-sm font-black text-slate-900 tracking-tight">Upload Resume</h3>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">PDF format only</p>
                                </div>
                            </div>

                            <div class="p-7">
                                {{-- Drop Zone --}}
                                <div id="upload-area"
                                     class="relative group border-2 border-dashed border-slate-200 rounded-2xl p-10 text-center cursor-pointer transition-all duration-200 hover:border-indigo-400 hover:bg-indigo-50/30 overflow-hidden"
                                     ondragover="handleDragOver(event)"
                                     ondragleave="handleDragLeave(event)"
                                     ondrop="handleDrop(event)">
                                    <input id="resume" name="resume" type="file" accept=".pdf"
                                           class="absolute inset-0 opacity-0 cursor-pointer z-10 w-full h-full" required>
                                    <div class="relative z-10 flex flex-col items-center gap-3">
                                        <div class="w-14 h-14 bg-slate-50 border border-slate-200 rounded-2xl flex items-center justify-center group-hover:bg-indigo-50 group-hover:border-indigo-200 transition-all">
                                            <i class="ph-bold ph-file-arrow-up text-slate-400 group-hover:text-indigo-500 text-2xl transition-colors"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-slate-800">Drop your PDF here or <span class="text-indigo-600">browse</span></p>
                                            <p class="text-xs text-slate-400 mt-0.5">PDF only · Max 10 MB</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- File selected state --}}
                                <div id="file-success" class="hidden mt-4">
                                    <div class="flex items-center gap-4 bg-emerald-50 border border-emerald-200 rounded-2xl px-5 py-4">
                                        <div class="w-11 h-11 bg-white border border-emerald-200 rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                                            <i class="ph-fill ph-file-pdf text-rose-500 text-xl"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p id="file-name" class="text-sm font-black text-slate-900 truncate"></p>
                                            <p id="file-size" class="text-[11px] text-slate-500 font-medium mt-0.5"></p>
                                        </div>
                                        <div class="flex items-center gap-2 shrink-0">
                                            <span class="text-[10px] font-black text-emerald-600 bg-emerald-100 px-2.5 py-1 rounded-full uppercase tracking-wide">Ready</span>
                                            <button type="button" id="remove-file" class="w-7 h-7 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-all">
                                                <i class="ph-bold ph-x text-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── Step 2: Job Description ─── --}}
                        <div class="bg-white border border-slate-200/70 rounded-3xl shadow-sm overflow-hidden mb-5">
                            <div class="px-7 pt-6 pb-4 border-b border-slate-100 flex items-center gap-3">
                                <div class="w-8 h-8 bg-purple-600 rounded-xl flex items-center justify-center text-white font-black text-xs">2</div>
                                <div>
                                    <h3 class="text-sm font-black text-slate-900 tracking-tight">Target Job Description</h3>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Paste requirements & responsibilities</p>
                                </div>
                            </div>

                            <div class="p-7">
                                <div class="relative">
                                    <textarea id="job_description" name="job_description" rows="10"
                                              class="w-full px-5 pt-4 pb-10 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium text-slate-700 focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 focus:bg-white transition-all outline-none resize-none leading-relaxed"
                                              placeholder="Paste the full job description — requirements, responsibilities, and qualifications..." required minlength="50" maxlength="2500">{{ old('job_description') }}</textarea>

                                    {{-- Char counter bar --}}
                                    <div class="absolute bottom-3 left-5 right-5">
                                        <div class="flex items-center justify-between mb-1">
                                            <div class="flex items-center gap-1.5">
                                                <div id="char-indicator" class="w-1.5 h-1.5 rounded-full bg-slate-300"></div>
                                                <span class="text-[10px] font-bold text-slate-400"><span id="char-count">0</span> / 2500</span>
                                            </div>
                                            <span id="char-hint" class="text-[10px] font-bold text-slate-400">Minimum 50 characters</span>
                                        </div>
                                        <div class="h-1 bg-slate-100 rounded-full overflow-hidden">
                                            <div id="char-bar" class="char-bar-fill h-full rounded-full bg-slate-300" style="width:0%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── Actions ─── --}}
                        <div class="flex items-center justify-between">
                            <a href="{{ route('tracker') }}"
                               class="flex items-center gap-1.5 text-sm font-bold text-slate-400 hover:text-slate-700 transition-colors">
                                <i class="ph-bold ph-arrow-left text-sm"></i> Cancel
                            </a>
                            <button type="submit" id="submit-btn"
                                    class="group flex items-center gap-2.5 px-8 py-3.5 bg-slate-900 text-white rounded-2xl font-black text-sm hover:bg-indigo-600 transition-all shadow-xl shadow-slate-200 active:scale-95">
                                <i id="loading-spinner" class="ph-bold ph-spinner animate-spin hidden text-sm"></i>
                                <i id="submit-icon" class="ph-bold ph-sparkle text-sm group-hover:rotate-12 transition-transform"></i>
                                <span id="submit-text">Run AI Analysis</span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ── Sidebar ────────────────────────────────────────── --}}
                <div class="lg:col-span-4 space-y-5">

                    {{-- How it works --}}
                    <div class="bg-white border border-slate-200/70 rounded-3xl p-6 shadow-sm">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-5">How It Works</p>
                        <div class="space-y-4">
                            @foreach([
                                ['ph-upload-simple',    'indigo',   'Upload Your Resume',  'PDF up to 10 MB. The AI will parse your skills, experience, and qualifications.'],
                                ['ph-clipboard-text',   'purple',   'Add Job Description', 'Paste the full job post. More detail = more accurate matching score.'],
                                ['ph-chart-bar',        'violet',   'Get Instant Results',  'A match score, keyword gaps, and actionable tips to improve your CV.'],
                            ] as [$icon, $color, $title, $desc])
                            <div class="flex gap-3">
                                <div class="w-8 h-8 bg-{{ $color }}-50 rounded-xl flex items-center justify-center shrink-0">
                                    <i class="ph-bold {{ $icon }} text-{{ $color }}-500 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-black text-slate-900">{{ $title }}</p>
                                    <p class="text-[11px] text-slate-500 leading-snug mt-0.5">{{ $desc }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Privacy card --}}
                    <div class="bg-slate-900 rounded-3xl p-6 text-white relative overflow-hidden">
                        <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-indigo-600/15 rounded-full blur-2xl pointer-events-none"></div>
                        <div class="absolute -left-4 -top-4 w-20 h-20 bg-violet-500/10 rounded-full blur-xl pointer-events-none"></div>
                        <div class="relative z-10">
                            <div class="w-9 h-9 bg-white/10 border border-white/10 rounded-xl flex items-center justify-center mb-4">
                                <i class="ph-bold ph-shield-check text-indigo-400 text-base"></i>
                            </div>
                            <h4 class="text-sm font-black tracking-tight mb-1.5">Privacy First</h4>
                            <p class="text-xs text-slate-400 leading-relaxed">
                                Your resume is processed securely and never stored permanently. Analysis runs in an isolated session.
                            </p>
                        </div>
                    </div>

                    {{-- Tips --}}
                    <div class="bg-amber-50 border border-amber-200 rounded-3xl p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="ph-duotone ph-lightbulb text-amber-500 text-base"></i>
                            <p class="text-[10px] font-black text-amber-700 uppercase tracking-widest">Tips for best results</p>
                        </div>
                        <ul class="space-y-2">
                            @foreach([
                                'Use a text-based PDF (not scanned image)',
                                'Include the full job description, not just the title',
                                'Paste requirements and responsibilities sections',
                            ] as $tip)
                            <li class="flex items-start gap-2 text-[11px] text-amber-800 font-medium">
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
            if (file && file.type === 'application/pdf') {
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
