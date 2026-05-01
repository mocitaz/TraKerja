<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-2xl font-black text-slate-900 leading-tight tracking-tight">
                Bulk <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#d983e4] via-purple-600 to-[#4e71c5]">Importer</span>
            </h1>
            <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest mt-1">Import job applications from a CSV file</p>
        </div>
    </x-slot>

    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        .drag-active { border-color: rgb(99,102,241) !important; background-color: rgb(238,242,255) !important; }
        .drag-active .upload-icon { background: rgb(99,102,241); color: white; }
        @keyframes fadeUp { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
        @keyframes shake { 10%,90%{transform:translate3d(-1px,0,0)}20%,80%{transform:translate3d(2px,0,0)}30%,50%,70%{transform:translate3d(-4px,0,0)}40%,60%{transform:translate3d(4px,0,0)} }
        .fade-up { animation: fadeUp .4s ease both; }
        .shake   { animation: shake .5s cubic-bezier(.36,.07,.19,.97) both; }
    </style>

    <div class="bg-[#f8fafc] min-h-screen pb-20">
        <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                {{-- ── Left: Form ──────────────────────────────────── --}}
                <div class="lg:col-span-8 space-y-5">

                    @if($errors->any())
                    <div class="shake flex items-start gap-3 bg-rose-50 border border-rose-200 rounded-2xl px-5 py-4">
                        <i class="ph-bold ph-warning-circle text-rose-500 text-xl shrink-0 mt-0.5"></i>
                        <ul class="space-y-0.5">
                            @foreach($errors->all() as $err)
                            <li class="text-sm font-semibold text-rose-700">{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {{-- Upload card --}}
                    <div class="bg-white border border-slate-200/70 rounded-3xl shadow-sm overflow-hidden">
                        <div class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-indigo-500 to-purple-500"></div>

                        {{-- Card header --}}
                        <div class="px-7 pt-6 pb-4 border-b border-slate-100 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-indigo-50 rounded-xl flex items-center justify-center">
                                    <i class="ph-bold ph-file-arrow-up text-indigo-600 text-base"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-black text-slate-900 tracking-tight">Upload CSV File</h3>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">CSV or TXT format</p>
                                </div>
                            </div>
                            <a href="{{ route('csv.template') }}"
                               class="flex items-center gap-1.5 px-4 py-2 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-emerald-600 hover:text-white hover:border-emerald-600 transition-all">
                                <i class="ph-bold ph-microsoft-excel-logo text-sm"></i>
                                Download Template
                            </a>
                        </div>

                        <div class="p-7">
                            <form action="{{ route('csv.import.process') }}" method="POST" enctype="multipart/form-data" id="importForm">
                                @csrf

                                {{-- Drop zone --}}
                                <div id="upload-area" class="relative border-2 border-dashed border-slate-200 rounded-2xl p-10 text-center cursor-pointer transition-all duration-200 overflow-hidden"
                                     ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)">
                                    <input type="file" id="csv_file" name="csv_file" accept=".csv,.txt"
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required>

                                    {{-- Decorative bg --}}
                                    <i class="ph ph-table absolute -left-3 -bottom-3 text-8xl text-slate-50 pointer-events-none"></i>
                                    <i class="ph ph-file-csv absolute -right-3 -top-3 text-8xl text-slate-50 pointer-events-none"></i>

                                    <div class="relative z-10 flex flex-col items-center gap-3">
                                        <div class="upload-icon w-14 h-14 bg-slate-50 border border-slate-200 rounded-2xl flex items-center justify-center transition-all duration-200">
                                            <i class="ph-bold ph-upload-simple text-slate-400 text-2xl"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-slate-800">Drop CSV here or <span class="text-indigo-600">browse files</span></p>
                                            <p class="text-xs text-slate-400 mt-0.5">CSV or TXT format only</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- File selected --}}
                                <div id="file-success" class="hidden mt-4 fade-up">
                                    <div class="flex items-center gap-4 bg-emerald-50 border border-emerald-200 rounded-2xl px-5 py-4">
                                        <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center shrink-0 shadow-sm shadow-emerald-200">
                                            <i class="ph-bold ph-check text-white text-base"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">File Ready</p>
                                            <p id="file-name" class="text-sm font-black text-slate-900 truncate mt-0.5"></p>
                                        </div>
                                        <button type="button" id="remove-file"
                                                class="w-7 h-7 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-all">
                                            <i class="ph-bold ph-x text-sm"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="flex items-center justify-between mt-6 pt-5 border-t border-slate-100">
                                    <a href="{{ route('tracker') }}"
                                       class="flex items-center gap-1.5 text-sm font-bold text-slate-400 hover:text-slate-700 transition-colors">
                                        <i class="ph-bold ph-arrow-left text-sm"></i> Cancel
                                    </a>
                                    <button type="submit" id="submit-btn"
                                            class="flex items-center gap-2 px-7 py-3 bg-slate-900 text-white rounded-2xl font-black text-sm hover:bg-indigo-600 transition-all shadow-xl shadow-slate-200 active:scale-95">
                                        <i class="ph-bold ph-arrow-circle-up text-base"></i>
                                        Process Import
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Column spec cards --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-white border border-slate-200/70 rounded-2xl p-5 shadow-sm">
                            <div class="flex items-center gap-2 mb-3">
                                <i class="ph-fill ph-warning-circle text-rose-500 text-base"></i>
                                <h4 class="text-[10px] font-black text-rose-600 uppercase tracking-widest">Required Columns</h4>
                            </div>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach(['Company Name', 'Position', 'Location', 'Platform', 'Status', 'Date'] as $f)
                                <span class="px-2.5 py-1 bg-rose-50 border border-rose-100 rounded-lg text-[10px] font-black text-rose-600">{{ $f }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="bg-white border border-slate-200/70 rounded-2xl p-5 shadow-sm">
                            <div class="flex items-center gap-2 mb-3">
                                <i class="ph-fill ph-plus-circle text-indigo-500 text-base"></i>
                                <h4 class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">Optional Columns</h4>
                            </div>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach(['Platform Link', 'Interview Date', 'Interview Type', 'Notes'] as $f)
                                <span class="px-2.5 py-1 bg-indigo-50 border border-indigo-100 rounded-lg text-[10px] font-black text-indigo-600">{{ $f }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- CSV Preview sample --}}
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-sm">
                        <div class="flex items-center justify-between px-5 py-3 border-b border-slate-800">
                            <div class="flex items-center gap-2">
                                <div class="flex gap-1.5">
                                    <span class="w-2.5 h-2.5 bg-rose-500 rounded-full"></span>
                                    <span class="w-2.5 h-2.5 bg-amber-500 rounded-full"></span>
                                    <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full"></span>
                                </div>
                                <span class="text-[10px] font-bold text-slate-500 ml-2">applications.csv</span>
                            </div>
                            <span class="text-[9px] font-black text-slate-600 uppercase tracking-widest">Preview</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-[10px] font-mono">
                                <thead>
                                    <tr class="border-b border-slate-800">
                                        @foreach(['company_name','position','location','platform','status','date','notes'] as $col)
                                        <th class="px-4 py-2.5 text-left text-indigo-400 font-bold whitespace-nowrap">{{ $col }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach([
                                        ['Google','Software Engineer','Remote','LinkedIn','Applied','2024-05-01',''],
                                        ['Tokopedia','Backend Dev','Jakarta','JobStreet','Interview','2024-05-03','HR Screen done'],
                                        ['Shopee','Data Analyst','Jakarta','Direct','Offered','2024-05-06','Waiting for offer letter'],
                                    ] as $row)
                                    <tr class="border-b border-slate-800/60 hover:bg-slate-800/30 transition-colors">
                                        @foreach($row as $cell)
                                        <td class="px-4 py-2 text-slate-400 whitespace-nowrap">{{ $cell ?: '—' }}</td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ── Right: Sidebar ───────────────────────────────── --}}
                <div class="lg:col-span-4 space-y-5">

                    {{-- Quick guide --}}
                    <div class="bg-indigo-600 rounded-3xl p-6 text-white relative overflow-hidden shadow-xl shadow-indigo-100">
                        <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                        <div class="absolute -left-4 -top-4 w-20 h-20 bg-indigo-400/20 rounded-full blur-xl pointer-events-none"></div>
                        <div class="relative z-10">
                            <p class="text-[10px] font-black text-indigo-200 uppercase tracking-widest mb-4">Quick Guide</p>
                            <div class="space-y-4">
                                @foreach([
                                    ['ph-layout',          'Use the Template',   'Download the CSV template and fill it in'],
                                    ['ph-calendar-blank',  'Date Format',        'Use YYYY-MM-DD (e.g. 2024-05-01)'],
                                    ['ph-clock',           'Interview Time',     'YYYY-MM-DD HH:MM (24-hour format)'],
                                    ['ph-check-circle',    'Status Values',      'Applied, Interview, Offered, Rejected'],
                                ] as [$icon, $title, $desc])
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 bg-white/10 border border-white/10 rounded-xl flex items-center justify-center shrink-0">
                                        <i class="ph-bold {{ $icon }} text-indigo-200 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-white">{{ $title }}</p>
                                        <p class="text-[10px] text-indigo-200/70 mt-0.5 leading-snug">{{ $desc }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Tips --}}
                    <div class="bg-amber-50 border border-amber-200 rounded-3xl p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="ph-duotone ph-lightbulb text-amber-500 text-base"></i>
                            <p class="text-[10px] font-black text-amber-700 uppercase tracking-widest">Tips</p>
                        </div>
                        <ul class="space-y-2">
                            @foreach([
                                'Save file as UTF-8 encoded CSV',
                                'Don\'t change the column header names',
                                'Leave optional fields blank, not "N/A"',
                                'Max 500 rows per import',
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
        const csvInput      = document.getElementById('csv_file');
        const uploadArea    = document.getElementById('upload-area');
        const fileSuccess   = document.getElementById('file-success');
        const fileNameEl    = document.getElementById('file-name');
        const removeFileBtn = document.getElementById('remove-file');

        function showFile(file) {
            fileNameEl.textContent = file.name;
            fileSuccess.classList.remove('hidden');
            uploadArea.classList.add('hidden');
        }

        csvInput.addEventListener('change', e => {
            if (e.target.files[0]) showFile(e.target.files[0]);
        });

        removeFileBtn.addEventListener('click', () => {
            csvInput.value = '';
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
            if (file) {
                const dt = new DataTransfer();
                dt.items.add(file);
                csvInput.files = dt.files;
                showFile(file);
            }
        }
    </script>
</x-app-layout>
