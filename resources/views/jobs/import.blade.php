<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-2xl font-black text-slate-900 leading-tight tracking-tight">
                Bulk <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#d983e4] via-purple-600 to-[#4e71c5]">Importer</span>
            </h1>
            <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest mt-1">Supercharge your job tracking data</p>
        </div>
    </x-slot>

    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <div class="bg-[#f8fafc] min-h-screen pb-20 relative overflow-hidden">
        {{-- Subtle Ambient Glows --}}
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-indigo-100/40 rounded-full blur-[120px] -mr-64 -mt-64"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-purple-100/40 rounded-full blur-[100px] -ml-48 -mb-48"></div>

        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 pt-8 relative z-10">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                {{-- Main Action Area --}}
                <div class="lg:col-span-8 space-y-6">
                    
                    {{-- Compact Import Card --}}
                    <div class="bg-white rounded-[2.5rem] border border-slate-200/60 shadow-xl shadow-slate-200/20 overflow-hidden relative group">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
                        
                        <div class="p-8 sm:p-10">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-10">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 bg-indigo-50 rounded-[1.5rem] flex items-center justify-center text-indigo-600 shadow-inner group-hover:scale-110 transition-transform duration-500">
                                        <i class="ph-bold ph-file-arrow-up text-2xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-black text-slate-900 tracking-tight">CSV Data Intake</h3>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Select your job application database</p>
                                    </div>
                                </div>
                                
                                <a href="{{ route('csv.template') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-emerald-50 text-emerald-600 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                                    <i class="ph-bold ph-microsoft-excel-logo text-lg"></i>
                                    Download Template
                                </a>
                            </div>

                            <form action="{{ route('csv.import.process') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                                @csrf
                                
                                <div id="upload-area" class="relative group/upload">
                                    <input type="file" id="csv_file" name="csv_file" accept=".csv,.txt" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required>
                                    <div class="border-2 border-dashed border-slate-200 rounded-[2.5rem] p-10 sm:p-16 text-center group-hover/upload:border-indigo-400 group-hover/upload:bg-indigo-50/30 transition-all duration-500 relative overflow-hidden">
                                        {{-- Background Decorative Icons --}}
                                        <i class="ph ph-table absolute -left-4 -bottom-4 text-8xl text-slate-50/50 group-hover/upload:text-indigo-100/50 transition-colors"></i>
                                        <i class="ph ph-file-csv absolute -right-4 -top-4 text-8xl text-slate-50/50 group-hover/upload:text-indigo-100/50 transition-colors"></i>
                                        
                                        <div class="relative z-10">
                                            <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center mx-auto mb-6 group-hover/upload:scale-110 group-hover/upload:bg-indigo-600 group-hover/upload:text-white transition-all duration-500 shadow-sm group-hover/upload:shadow-xl group-hover/upload:shadow-indigo-200">
                                                <i class="ph-bold ph-upload-simple text-3xl"></i>
                                            </div>
                                            <h4 class="text-sm font-black text-slate-900 uppercase tracking-[2px] mb-2">Drop your CSV here</h4>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">or click to explore file manager</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Success Feedback (Hidden by default) --}}
                                <div id="file-success" class="hidden transform scale-95 opacity-0 transition-all duration-500">
                                    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-100 rounded-3xl p-6 flex items-center gap-5 shadow-sm">
                                        <div class="w-12 h-12 bg-emerald-500 text-white rounded-[1.25rem] flex items-center justify-center shadow-lg shadow-emerald-100">
                                            <i class="ph-bold ph-check text-2xl"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Verification Success</p>
                                            <p id="file-name" class="text-sm font-black text-slate-900 truncate"></p>
                                        </div>
                                        <button type="button" id="remove-file" class="w-10 h-10 flex items-center justify-center rounded-xl bg-emerald-100/50 text-emerald-600 hover:bg-rose-500 hover:text-white transition-all">
                                            <i class="ph-bold ph-trash text-lg"></i>
                                        </button>
                                    </div>
                                </div>

                                @if($errors->any())
                                    <div class="bg-rose-50 border border-rose-100 rounded-[2rem] p-6 animate-shake">
                                        <div class="flex items-start gap-4">
                                            <div class="w-10 h-10 bg-rose-500 text-white rounded-xl flex items-center justify-center shrink-0">
                                                <i class="ph-bold ph-warning-octagon text-xl"></i>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="text-[10px] font-black text-rose-600 uppercase tracking-widest mb-2">Structure Errors Detected</h4>
                                                <ul class="text-xs text-rose-700 font-bold space-y-1">
                                                    @foreach($errors->all() as $error)
                                                        <li class="flex items-center gap-2">
                                                            <span class="w-1 h-1 bg-rose-400 rounded-full"></span>
                                                            {{ $error }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="flex flex-col sm:flex-row items-center gap-4 pt-8">
                                    <button type="submit" class="w-full sm:flex-1 py-5 bg-slate-900 text-white rounded-[1.5rem] font-black text-[10px] uppercase tracking-[3px] hover:bg-indigo-600 transition-all shadow-2xl shadow-slate-200 active:scale-95 flex items-center justify-center gap-3 group/btn">
                                        <i class="ph-bold ph-arrow-circle-up text-xl group-hover:scale-110 transition-transform"></i>
                                        Process Import Now
                                    </button>
                                    <a href="{{ route('tracker') }}" class="w-full sm:w-auto px-10 py-5 bg-white border border-slate-200 text-slate-500 rounded-[1.5rem] font-black text-[10px] uppercase tracking-[2px] hover:bg-slate-50 transition-all flex items-center justify-center">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Desktop Field Specs (Hidden on Mobile) --}}
                    <div class="hidden md:grid grid-cols-2 gap-6">
                        <div class="bg-white rounded-[2rem] p-6 border border-slate-200/60 shadow-sm">
                            <h4 class="text-[10px] font-black text-rose-500 uppercase tracking-[2px] mb-4 flex items-center gap-2">
                                <i class="ph-fill ph-warning-circle text-lg"></i> Required Columns
                            </h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach(['Company Name', 'Position', 'Location', 'Platform', 'Status', 'Date'] as $f)
                                    <span class="px-3 py-1.5 bg-slate-50 rounded-lg text-[9px] font-black text-slate-500 border border-slate-100">{{ $f }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="bg-white rounded-[2rem] p-6 border border-slate-200/60 shadow-sm">
                            <h4 class="text-[10px] font-black text-indigo-500 uppercase tracking-[2px] mb-4 flex items-center gap-2">
                                <i class="ph-fill ph-plus-circle text-lg"></i> Optional Columns
                            </h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach(['Platform Link', 'Interview Date', 'Interview Type', 'Notes'] as $f)
                                    <span class="px-3 py-1.5 bg-slate-50 rounded-lg text-[9px] font-black text-slate-500 border border-slate-100">{{ $f }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Side Panel: Instructions & Mobile Specs --}}
                <div class="lg:col-span-4 space-y-6">
                    {{-- Guideline Card --}}
                    <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white shadow-xl shadow-indigo-100 relative overflow-hidden group/guide">
                        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl group-hover/guide:scale-150 transition-transform duration-700"></div>
                        <div class="relative z-10">
                            <h3 class="text-lg font-black tracking-tight mb-6">Quick Guide</h3>
                            <div class="space-y-4">
                                @foreach([
                                    ['icon' => 'ph-layout', 'title' => 'Standard Format', 'desc' => 'Use the provided CSV template'],
                                    ['icon' => 'ph-calendar-blank', 'title' => 'Date Format', 'desc' => 'YYYY-MM-DD (e.g. 2024-05-01)'],
                                    ['icon' => 'ph-clock', 'title' => 'Time Format', 'desc' => 'YYYY-MM-DD HH:MM (24-hour)'],
                                ] as $guide)
                                    <div class="flex items-start gap-4">
                                        <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center shrink-0 border border-white/10">
                                            <i class="ph-bold {{ $guide['icon'] }} text-indigo-200"></i>
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-black uppercase tracking-widest text-white">{{ $guide['title'] }}</p>
                                            <p class="text-[10px] font-medium text-indigo-100/70 mt-0.5">{{ $guide['desc'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Mobile-only Field Specs (Collapsible/Simplified) --}}
                    <div class="md:hidden bg-white rounded-[2rem] p-6 border border-slate-200/60 shadow-sm">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Quick Field Check</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-[10px] font-bold">
                                <span class="text-slate-500">Required:</span>
                                <span class="text-slate-900">Company, Position, Status, Date...</span>
                            </div>
                            <div class="flex justify-between text-[10px] font-bold">
                                <span class="text-slate-500">Optional:</span>
                                <span class="text-slate-900">Link, Interview, Notes</span>
                            </div>
                        </div>
                    </div>

                    {{-- Support Card --}}
                    <div class="bg-white rounded-[2.5rem] border border-slate-200/60 p-8 shadow-sm text-center">
                        <div class="w-14 h-14 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="ph-bold ph-question text-2xl"></i>
                        </div>
                        <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-2">Need help?</h4>
                        <p class="text-[10px] font-medium text-slate-500 leading-relaxed px-4">Contact our support if you're having trouble with the bulk import process.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .animate-fadeIn { animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .animate-shake { animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both; }
        @keyframes fadeIn { from { opacity: 0; transform: scale(0.95) translateY(10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
        @keyframes shake { 10%, 90% { transform: translate3d(-1px, 0, 0); } 20%, 80% { transform: translate3d(2px, 0, 0); } 30%, 50%, 70% { transform: translate3d(-4px, 0, 0); } 40%, 60% { transform: translate3d(4px, 0, 0); } }
    </style>

    <script>
        const csvFileInput = document.getElementById('csv_file');
        const uploadArea = document.querySelector('#upload-area > div');
        const fileSuccess = document.getElementById('file-success');
        const fileName = document.getElementById('file-name');
        const removeFileBtn = document.getElementById('remove-file');
        
        csvFileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                fileName.textContent = file.name;
                fileSuccess.classList.remove('hidden');
                fileSuccess.classList.add('flex', 'animate-fadeIn');
                uploadArea.classList.add('border-emerald-400', 'bg-emerald-50/20');
                uploadArea.querySelector('.ph-upload-simple').className = 'ph-bold ph-check text-emerald-600';
            }
        });
        
        removeFileBtn.addEventListener('click', function() {
            csvFileInput.value = '';
            fileSuccess.classList.add('hidden');
            fileSuccess.classList.remove('flex');
            uploadArea.classList.remove('border-emerald-400', 'bg-emerald-50/20');
            uploadArea.querySelector('.ph-bold').className = 'ph-bold ph-upload-simple';
        });
    </script>
</x-app-layout>
