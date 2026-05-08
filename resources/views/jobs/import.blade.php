<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-2xl font-black text-slate-900 leading-tight tracking-tight">
                Bulk <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#d983e4] via-primary-600 to-[#4e71c5]">Importer</span>
            </h1>
            <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest mt-1">Transform your CSV data into professional records</p>
        </div>
    </x-slot>

    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        [x-cloak] { display: none !important; }
        .step-active { color: #a570f0; border-color: #a570f0; }
        .step-complete { background-color: #a570f0; color: white; border-color: #a570f0; }
        .drag-active { border-color: #a570f0 !important; background-color: rgba(165, 112, 240, 0.05) !important; transform: scale(1.02); }
        .drag-active .upload-icon { color: #a570f0; animation: bounce 1s infinite; }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes pulse-ring {
            0% { transform: scale(.33); }
            80%, 100% { opacity: 0; }
        }
        
        .pulse-btn::before {
            content: '';
            position: absolute;
            display: block;
            width: 300%;
            height: 300%;
            box-sizing: border-box;
            margin-left: -100%;
            margin-top: -100%;
            border-radius: 45px;
            background-color: #a570f0;
            animation: pulse-ring 1.25s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
        }

        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
        
        .glass-card { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.3); }
    </style>

    @php
        $user = auth()->user();
        $hasAccess = \App\Models\Setting::isMonetizationEnabled() ? $user->isPremium() : true;
    @endphp

    <div class="bg-[#f8fafc] {{ !$hasAccess ? 'h-[calc(100vh-73px)] overflow-hidden flex items-center justify-center' : 'min-h-screen pb-20' }}" x-data="{ 
        currentStep: 1, 
        fileName: '', 
        isScanning: false, 
        scanProgress: 0,
        mapping: {
            company: 'company_name',
            position: 'position',
            location: 'location',
            platform: 'platform',
            status: 'application_status',
            date: 'application_date'
        },
        startScan() {
            this.isScanning = true;
            let interval = setInterval(() => {
                this.scanProgress += 5;
                if (this.scanProgress >= 100) {
                    clearInterval(interval);
                    this.isScanning = false;
                    this.currentStep = 2;
                }
            }, 50);
        }
    }">
        <div class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 {{ !$hasAccess ? '' : 'pt-8' }}">

            @if(!$hasAccess)
                <div class="max-w-xl mx-auto">
                    <div class="bg-white rounded-[2rem] border border-slate-200/60 shadow-sm overflow-hidden relative text-center p-8 sm:p-10">
                        <div class="absolute top-0 right-0 w-48 h-48 bg-amber-50 rounded-full blur-[60px] -mr-24 -mt-24 opacity-60"></div>
                        <div class="absolute bottom-0 left-0 w-48 h-48 bg-primary-50 rounded-full blur-[60px] -ml-24 -mb-24 opacity-60"></div>
                        
                        <div class="relative z-10 flex flex-col items-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl shadow-lg shadow-amber-200/50 flex items-center justify-center text-white mb-6 border border-white/20">
                                <i class="ph-fill ph-crown text-3xl"></i>
                            </div>
                            
                            <h2 class="text-xl font-black text-slate-900 tracking-tight mb-3">Enterprise-Grade Ingestion</h2>
                            <p class="text-xs font-medium text-slate-500 leading-relaxed max-w-sm mb-8">Seamlessly map and ingest thousands of records instantly. This advanced data migration tool is exclusively available for Premium members.</p>
                            
                            <a href="{{ route('payment.premium') }}" class="px-8 py-3.5 bg-slate-900 text-white rounded-xl font-black text-[10px] uppercase tracking-[0.15em] hover:bg-slate-800 transition-all shadow-md active:scale-95 flex items-center gap-2">
                                Upgrade to Premium
                                <i class="ph-bold ph-arrow-right"></i>
                            </a>
                            
                            <div class="mt-8 pt-6 border-t border-slate-100 flex flex-wrap gap-4 sm:gap-6 justify-center w-full">
                                <div class="text-center">
                                    <i class="ph-duotone ph-lightning text-lg text-primary-500 mb-1.5"></i>
                                    <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest">Instant Sync</p>
                                </div>
                                <div class="text-center">
                                    <i class="ph-duotone ph-git-merge text-emerald-500 mb-1.5"></i>
                                    <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest">Smart Map</p>
                                </div>
                                <div class="text-center">
                                    <i class="ph-duotone ph-shield-check text-blue-500 mb-1.5"></i>
                                    <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest">Data Integrity</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
            {{-- ── Advanced Stepper ──────────────────────────────── --}}
            <div class="mb-8 sm:mb-12 max-w-5xl mx-auto">
                <div class="relative flex justify-between items-center">
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-slate-200 rounded-full">
                        <div class="h-full bg-gradient-to-r from-primary-400 to-primary-600 transition-all duration-700 rounded-full" 
                             :style="'width: ' + ((currentStep - 1) * 33.3) + '%'"></div>
                    </div>
                    
                    @foreach([
                        ['icon' => 'ph-file-arrow-up', 'label' => 'Source'],
                        ['icon' => 'ph-git-merge', 'label' => 'Mapping'],
                        ['icon' => 'ph-shield-check', 'label' => 'Validation'],
                        ['icon' => 'ph-rocket-launch', 'label' => 'Finalize']
                    ] as $i => $step)
                    <div class="relative z-10 flex flex-col items-center gap-2 sm:gap-4">
                        <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl sm:rounded-2xl border-[4px] sm:border-[6px] border-[#f8fafc] flex items-center justify-center transition-all duration-500"
                             :class="currentStep > {{ $i+1 }} ? 'bg-primary-600 text-white border-primary-600 shadow-xl shadow-primary-100' : (currentStep == {{ $i+1 }} ? 'bg-white text-primary-600 border-primary-600 shadow-2xl shadow-primary-200' : 'bg-slate-100 text-slate-400 border-slate-100')">
                            <i class="ph-bold {{ $step['icon'] }} text-sm sm:text-xl transition-transform" :class="currentStep == {{ $i+1 }} ? 'scale-125' : ''"></i>
                        </div>
                        <div class="text-center">
                            <span class="text-[8px] sm:text-[10px] font-black uppercase tracking-[0.15em] sm:tracking-[0.2em]"
                                  :class="currentStep == {{ $i+1 }} ? 'text-primary-600' : 'text-slate-400'">{{ $step['label'] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-10 items-start">
                
                {{-- ── Main Stage ─────────────────────────────────── --}}
                <div class="lg:col-span-8">
                    
                    {{-- Step 1: Upload --}}
                    <div x-show="currentStep === 1" class="space-y-6" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="bg-white rounded-[2rem] sm:rounded-[3rem] border border-slate-200/60 shadow-[0_20px_50px_rgba(0,0,0,0.03)] overflow-hidden relative">
                            <div class="absolute top-0 right-0 w-64 h-64 bg-primary-50 rounded-full blur-[80px] -mr-32 -mt-32 opacity-60"></div>
                            
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 px-6 sm:px-10 py-6 sm:py-8 border-b border-slate-50 relative z-10 w-full">
                                <div class="flex items-center gap-4 sm:gap-5 w-full sm:w-auto">
                                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-slate-900 flex items-center justify-center text-white shadow-xl shadow-slate-200 shrink-0">
                                        <i class="ph-duotone ph-cloud-arrow-up text-2xl sm:text-3xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">Ingestion Hub</h3>
                                        <p class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Select your CSV or TXT data source</p>
                                    </div>
                                </div>
                                <a href="{{ route('csv.template') }}" class="w-full sm:w-auto justify-center group flex items-center gap-2.5 px-6 py-3 bg-emerald-50 text-emerald-700 rounded-2xl font-black text-[11px] uppercase tracking-widest border border-emerald-100 hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                                    <i class="ph-bold ph-microsoft-excel-logo text-lg group-hover:rotate-12 transition-transform"></i>
                                    Get Template
                                </a>
                            </div>

                            <div class="p-6 sm:p-10 relative z-10">
                                <template x-if="!isScanning">
                                    <div id="drop-zone" 
                                         class="relative border-4 border-dashed border-slate-100 rounded-[2rem] sm:rounded-[2.5rem] p-8 sm:p-20 text-center cursor-pointer transition-all duration-500 hover:border-primary-200 hover:bg-primary-50/10 group"
                                         onclick="document.getElementById('csv_file').click()">
                                        <input type="file" id="csv_file" name="csv_file" accept=".csv,.txt" class="hidden" 
                                               @change="fileName = $event.target.files[0].name; startScan()">
                                        
                                        <div class="upload-icon flex flex-col items-center gap-4 sm:gap-6 transition-all duration-500">
                                            <div class="w-16 h-16 sm:w-24 sm:h-24 rounded-2xl sm:rounded-[2rem] bg-white flex items-center justify-center text-slate-300 border border-slate-100 shadow-xl group-hover:shadow-primary-100 group-hover:text-primary-600 group-hover:border-primary-200 transition-all duration-500">
                                                <i class="ph-bold ph-file-csv text-3xl sm:text-5xl"></i>
                                            </div>
                                            <div class="space-y-1 sm:space-y-2">
                                                <p class="text-lg sm:text-2xl font-black text-slate-900 tracking-tight">Drop your batch here</p>
                                                <p class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-[0.15em] sm:tracking-[0.2em]">or browse your professional archive</p>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="isScanning">
                                    <div class="py-12 sm:py-20 flex flex-col items-center justify-center space-y-6 sm:space-y-8 animate-pulse">
                                        <div class="relative w-20 h-20 sm:w-24 sm:h-24">
                                            <div class="absolute inset-0 border-4 border-primary-100 rounded-full"></div>
                                            <div class="absolute inset-0 border-4 border-primary-600 rounded-full border-t-transparent animate-spin"></div>
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <i class="ph-fill ph-magnifying-glass text-2xl sm:text-3xl text-primary-600"></i>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">Scanning Structure...</p>
                                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-2" x-text="scanProgress + '%'"></p>
                                        </div>
                                        <div class="w-48 sm:w-64 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-primary-600 transition-all duration-300" :style="'width: ' + scanProgress + '%'"></div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Step 2: Mapping & Preview --}}
                    <div x-show="currentStep === 2" x-cloak class="space-y-6 sm:space-y-8" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-x-12" x-transition:enter-end="opacity-100 translate-x-0">
                        {{-- Mapping Controls --}}
                        <div class="bg-white rounded-[2rem] sm:rounded-[2.5rem] border border-slate-200/60 shadow-sm p-5 sm:p-8">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-6 sm:mb-8 flex items-center gap-3">
                                <i class="ph-bold ph-git-merge text-primary-600"></i> Smart Field Mapping
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
                                @foreach([
                                    'company_name' => 'Company',
                                    'position' => 'Job Title',
                                    'location' => 'Location',
                                    'platform' => 'Source Platform',
                                    'application_status' => 'Status',
                                    'application_date' => 'Date'
                                ] as $key => $label)
                                    <div class="space-y-2">
                                        <label class="block text-[9px] font-black text-slate-500 uppercase tracking-widest ml-1">{{ $label }}</label>
                                        <div class="relative group">
                                            <select class="w-full pl-4 pr-10 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-[11px] font-bold text-slate-700 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-400 transition-all appearance-none outline-none">
                                                <option value="{{ $key }}">{{ $key }} (Auto)</option>
                                                <option value="custom">Custom Column...</option>
                                            </select>
                                            <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary-600 transition-colors"></i>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Spreadsheet-style Preview --}}
                        <div class="bg-white rounded-[2rem] sm:rounded-[3rem] border border-slate-200/60 shadow-2xl shadow-slate-200/50 overflow-hidden">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 px-6 sm:px-10 py-5 sm:py-6 border-b border-slate-50 bg-slate-50/30 w-full">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-100 shrink-0">
                                        <i class="ph-bold ph-table text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-black text-slate-900 tracking-tight">Pre-Ingestion Preview</h3>
                                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5" x-text="fileName"></p>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between sm:justify-end gap-3 w-full sm:w-auto">
                                    <span class="px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-emerald-100">3 Rows Detected</span>
                                    <button @click="currentStep = 1" class="p-2.5 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all">
                                        <i class="ph-bold ph-trash text-base sm:text-lg"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="overflow-x-auto custom-scrollbar">
                                <table class="w-full border-collapse min-w-[800px]">
                                    <thead>
                                        <tr class="bg-slate-50/50">
                                            <th class="w-12 px-4 py-4 border-b border-slate-100"></th>
                                            @foreach(['Company','Position','Location','Platform','Status','Date'] as $h)
                                                <th class="px-6 py-4 text-left border-b border-slate-100">
                                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $h }}</span>
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach([
                                            ['Google','Senior Designer','Remote','LinkedIn','Applied','2024-05-10'],
                                            ['Airbnb','Product Engineer','Bali','Direct','Interview','2024-05-12'],
                                            ['Grab','Backend Lead','Jakarta','JobStreet','Offered','2024-05-15']
                                        ] as $i => $row)
                                        <tr class="hover:bg-primary-50/30 transition-colors group">
                                            <td class="px-4 py-4 text-center border-b border-slate-50">
                                                <span class="text-[10px] font-black text-slate-300">{{ $i+1 }}</span>
                                            </td>
                                            @foreach($row as $cell)
                                                <td class="px-6 py-4 border-b border-slate-50">
                                                    <div class="relative">
                                                        <input type="text" value="{{ $cell }}" class="w-full bg-transparent border-none text-xs font-bold text-slate-600 focus:ring-0 p-0 outline-none">
                                                        <div class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary-500 group-hover:w-full transition-all duration-300"></div>
                                                    </div>
                                                </td>
                                            @endforeach
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="p-6 sm:p-10 bg-white flex flex-col-reverse sm:flex-row items-center justify-between gap-4 w-full border-t border-slate-100 sm:border-t-0">
                                <button @click="currentStep = 1" class="flex items-center gap-2 text-xs font-black text-slate-400 hover:text-slate-600 uppercase tracking-widest transition-all w-full sm:w-auto justify-center py-2">
                                    <i class="ph-bold ph-arrow-left"></i> Re-Upload
                                </button>
                                <form action="{{ route('csv.import.process') }}" method="POST" enctype="multipart/form-data" id="finalForm" class="w-full sm:w-auto">
                                    @csrf
                                    {{-- Hidden inputs for real processing --}}
                                    <button type="submit" class="w-full sm:w-auto justify-center relative overflow-hidden px-10 sm:px-12 py-4 sm:py-5 bg-primary-600 text-white rounded-2xl sm:rounded-[2rem] font-black text-[11px] sm:text-xs uppercase tracking-[0.2em] hover:bg-primary-700 transition-all shadow-2xl shadow-primary-200 active:scale-95 group flex items-center gap-3">
                                        <i class="ph-bold ph-rocket-launch text-lg sm:text-xl group-hover:rotate-12 transition-transform"></i>
                                        Finalize Ingestion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Precision Sidebar ───────────────────────────── --}}
                <div class="lg:col-span-4 space-y-8">
                    {{-- Validation Rules Card --}}
                    <div class="bg-white rounded-[2.5rem] border border-slate-200/60 p-8 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 rounded-full blur-3xl opacity-60"></div>
                        <h4 class="relative z-10 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-6 flex items-center gap-2">
                            <i class="ph-bold ph-shield-check text-emerald-500"></i> Validation Protocol
                        </h4>
                        <div class="space-y-4 relative z-10">
                            @foreach([
                                ['Date Integrity', 'Must follow YYYY-MM-DD standard'],
                                ['Status Match', 'Applied, Interview, Offered, Rejected'],
                                ['Link Security', 'HTTPS required for platform links'],
                                ['Unique Check', 'Prevents duplicate company/date entries']
                            ] as [$title, $desc])
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 rounded-full bg-emerald-400 mt-1.5 shrink-0"></div>
                                <div>
                                    <p class="text-[11px] font-black text-slate-700 leading-none">{{ $title }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-wider">{{ $desc }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Pro Stats --}}
                    <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-2xl shadow-slate-200 relative overflow-hidden group">
                        <div class="absolute -right-12 -top-12 w-48 h-48 bg-primary-600/20 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-1000"></div>
                        <div class="relative z-10 space-y-8">
                            <h4 class="text-[10px] font-black text-primary-400 uppercase tracking-[0.3em]">Batch Ingestion Stats</h4>
                            <div class="grid grid-cols-2 gap-6">
                                <div class="space-y-1">
                                    <p class="text-3xl font-black tracking-tighter">500</p>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Max Records</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-3xl font-black tracking-tighter">0.8s</p>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Avg Process</p>
                                </div>
                            </div>
                            <div class="pt-6 border-t border-white/10">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[10px] font-black text-slate-400 uppercase">System Capacity</span>
                                    <span class="text-[10px] font-black text-primary-400">98%</span>
                                </div>
                                <div class="w-full h-1.5 bg-white/5 rounded-full overflow-hidden">
                                    <div class="h-full bg-primary-500 w-[98%]"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropZone = document.getElementById('drop-zone');
            
            if (dropZone) {
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, e => {
                        e.preventDefault(); e.stopPropagation();
                    }, false);
                });

                ['dragenter', 'dragover'].forEach(eventName => {
                    dropZone.addEventListener(eventName, () => {
                        dropZone.classList.add('drag-active');
                    }, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, () => {
                        dropZone.classList.remove('drag-active');
                    }, false);
                });

                dropZone.addEventListener('drop', e => {
                    const dt = e.dataTransfer;
                    const files = dt.files;
                    const input = document.getElementById('csv_file');
                    
                    if (files.length > 0) {
                        input.files = files;
                        const event = new Event('change', { bubbles: true });
                        input.dispatchEvent(event);
                    }
                }, false);
            }
        });
    </script>
</x-app-layout>
