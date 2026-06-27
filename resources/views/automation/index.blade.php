<x-app-layout>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <div class="bg-[#fafafa] min-h-screen pb-16" x-data="{ activeTab: '{{ $activeTab }}' }">
        <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 pt-6">

            <!-- Premium Notion-Inspired Page Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-zinc-200/50 pb-4 mb-5">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-zinc-100 border border-zinc-200/60 rounded-lg flex items-center justify-center text-zinc-500 shrink-0 shadow-2xs">
                        <i class="ph ph-puzzle-piece text-base"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-sm font-bold text-zinc-800 tracking-tight">Automation & Data Tools</h1>
                            <span class="px-1.5 py-0.5 bg-primary-50 text-zinc-800 text-[9px] font-black uppercase tracking-wider rounded border border-primary-100/60">Tools</span>
                        </div>
                        <p class="text-[11px] text-zinc-400 mt-0.5">Accelerate your job search pipeline with integrations and CSV imports.</p>
                    </div>
                </div>
            </div>

            <style>
                [x-cloak] { display: none !important; }
                .drag-active { border-color: #a1a1aa !important; background-color: #f4f4f5 !important; }
                .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
                .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
                .custom-scrollbar::-webkit-scrollbar-thumb { background: #e4e4e7; border-radius: 3px; }
                
                /* Custom Notion Switcher Active Styles */
                .tab-btn { color: #71717a; }
                .tab-btn.active { 
                    background-color: #f5f3ff; 
                    color: #27272a; 
                    border: 1px solid rgba(221, 214, 254, 0.8);
                    font-weight: 700;
                    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
                }
                .tab-btn:not(.active):hover {
                    background-color: #f4f4f5;
                    color: #18181b;
                }
            </style>

            <!-- Premium Notion-Inspired Tab Switcher -->
            <div class="flex p-0.5 bg-white border border-zinc-200/70 rounded-md shadow-3xs mb-6 max-w-xs">
                <button @click="activeTab = 'extension'; updateUrl('extension')" 
                        :class="activeTab === 'extension' ? 'active' : ''"
                        class="tab-btn flex-1 justify-center flex items-center gap-1.5 py-1.5 text-[11px] font-bold rounded transition-colors focus:outline-none">
                    <i class="ph ph-puzzle-piece text-xs"></i>
                    <span>Chrome Extension</span>
                </button>
                <button @click="activeTab = 'csv'; updateUrl('csv')" 
                        :class="activeTab === 'csv' ? 'active' : ''"
                        class="tab-btn flex-1 justify-center flex items-center gap-1.5 py-1.5 text-[11px] font-bold rounded transition-colors focus:outline-none">
                    <i class="ph ph-file-csv text-xs"></i>
                    <span>CSV Tools</span>
                </button>
            </div>

            <!-- --- TAB 1: CHROME EXTENSION --- -->
            <div x-show="activeTab === 'extension'" class="space-y-6">
                
                <!-- Main Promo Hero Section -->
                <div class="relative bg-white rounded-lg border border-zinc-200/60 shadow-3xs overflow-hidden p-5 sm:p-6">
                    @if(!$hasAccess)
                    <!-- Premium Blur Lock Overlay -->
                    <div class="absolute inset-0 bg-white/95 z-30 flex flex-col items-center justify-center p-6 text-center backdrop-blur-xs">
                        <div class="w-10 h-10 bg-zinc-50 border border-zinc-200 text-zinc-700 rounded flex items-center justify-center mb-3.5">
                            <i class="ph ph-crown text-xl text-amber-500"></i>
                        </div>
                        <h2 class="text-xs font-bold text-zinc-800 tracking-tight uppercase">One-Click Extension Scraper</h2>
                        <p class="text-[11px] text-zinc-500 max-w-xs mt-1 mb-4 leading-normal font-semibold">Instantly populate your TraKerja pipeline directly from LinkedIn, Glints, JobStreet, and Kalibrr. Upgrade to Premium to unlock this feature.</p>
                        <a href="{{ route('payment.premium') }}" class="px-3 py-1.5 bg-primary-50 text-zinc-800 border border-primary-200/60 hover:bg-primary-100 text-[10px] font-bold uppercase tracking-wider rounded transition-all flex items-center gap-1 shadow-3xs focus:outline-none active:scale-97">
                            <span>Upgrade to Premium</span>
                            <i class="ph ph-crown text-xs"></i>
                        </a>
                    </div>
                    @endif

                    <div class="flex flex-col lg:flex-row items-center gap-6">
                        <!-- Left: Typography & CTA -->
                        <div class="flex-1 text-center lg:text-left flex flex-col items-center lg:items-start">
                            <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-zinc-50 border border-zinc-200 text-zinc-650 text-[8.5px] font-bold uppercase tracking-wider mb-3.5">
                                <span class="w-1 h-1 rounded-full bg-emerald-500"></span>
                                Stable Release 1.0.0
                            </div>
                            
                            <h2 class="text-base sm:text-lg font-bold text-zinc-800 tracking-tight leading-snug mb-2">
                                Save Job Listings <br class="hidden lg:block"/>
                                <span class="text-zinc-450">In a Single Click.</span>
                            </h2>
                            
                            <p class="text-[11px] text-zinc-500 font-semibold leading-relaxed max-w-md mb-4.5">
                                Automate scraping vacancy details from LinkedIn, Glints, JobStreet, Dealls, and Kalibrr directly into your Kanban board without copying and pasting manually.
                            </p>
                            
                            <div class="flex flex-col items-center lg:items-start w-full sm:w-auto">
                                @if($hasAccess)
                                    <a href="{{ asset('downloads/trakerja-extension.zip') }}" download class="w-full sm:w-auto flex items-center justify-center gap-1.5 px-3.5 py-1.5 bg-primary-50 text-zinc-800 border border-primary-200/60 hover:bg-primary-100 text-[10px] font-bold uppercase tracking-wider rounded transition-all shadow-3xs focus:outline-none active:scale-97">
                                        <i class="ph ph-download-simple text-xs"></i>
                                        <span>Download Extension</span>
                                    </a>
                                @endif
                                <div class="mt-3.5 inline-flex items-center gap-1 text-zinc-450 text-[9px] font-bold uppercase tracking-wider">
                                    <i class="ph ph-desktop text-zinc-400"></i>
                                    <span>PC & Laptop only (Mobile browsers not supported)</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Mockup Panel -->
                        <div class="w-full lg:w-[35%] flex justify-center lg:justify-end">
                            <div class="w-full max-w-[240px] bg-zinc-50 rounded-lg border border-zinc-200 shadow-3xs p-0.5">
                                <!-- Browser Header -->
                                <div class="h-6.5 bg-zinc-100 rounded-t-md border-b border-zinc-200 flex items-center px-2 gap-1.5">
                                    <div class="flex gap-0.5">
                                        <div class="w-1.5 h-1.5 rounded-full bg-zinc-300"></div>
                                        <div class="w-1.5 h-1.5 rounded-full bg-zinc-300"></div>
                                        <div class="w-1.5 h-1.5 rounded-full bg-zinc-300"></div>
                                    </div>
                                    <div class="flex-1 mx-2 h-3.5 bg-white rounded border border-zinc-150 flex items-center px-1">
                                        <i class="ph ph-lock text-[6px] text-zinc-400 mr-1 shrink-0"></i>
                                        <div class="h-0.5 w-10 bg-zinc-200 rounded-full"></div>
                                    </div>
                                    <i class="ph ph-puzzle-piece text-zinc-500 text-[10px]"></i>
                                </div>
                                <!-- Extension Body -->
                                <div class="p-3 space-y-2 bg-white rounded-b-md">
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-6.5 h-6.5 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-700 shrink-0">
                                            <i class="ph ph-briefcase text-xs"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-[9px] font-bold text-zinc-800 leading-tight">TraKerja Extension</h3>
                                            <p class="text-[7.5px] text-emerald-600 font-bold flex items-center gap-0.5 mt-0.5 leading-none">
                                                <i class="ph ph-check-circle"></i> Data Detected
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-1.5">
                                        <div class="space-y-0.5">
                                            <div class="text-[7.5px] font-bold text-zinc-400 uppercase tracking-widest leading-none">Position</div>
                                            <div class="py-1 px-1.5 bg-zinc-50 border border-zinc-200 rounded text-[10px] text-zinc-700 font-semibold leading-none">
                                                Software Engineer
                                            </div>
                                        </div>
                                        <div class="space-y-0.5">
                                            <div class="text-[7.5px] font-bold text-zinc-400 uppercase tracking-widest leading-none">Company</div>
                                            <div class="py-1 px-1.5 bg-zinc-50 border border-zinc-200 rounded text-[10px] text-zinc-700 font-semibold leading-none">
                                                Tech Corp Inc.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pt-0.5">
                                        <div class="py-1.5 bg-zinc-950 rounded flex items-center justify-center gap-1">
                                            <i class="ph ph-floppy-disk text-white text-xs"></i>
                                            <span class="text-[8.5px] text-white font-bold uppercase tracking-wider">Save to Board</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Platform strip -->
                    <div class="border-t border-zinc-150 bg-zinc-50/50 px-5 py-3 rounded-b-lg flex flex-wrap justify-between items-center gap-4 mt-5">
                        <p class="text-[8px] font-bold text-zinc-400 uppercase tracking-widest w-full md:w-auto text-center leading-none">Supported Platforms</p>
                        <div class="flex flex-wrap justify-center items-center gap-4 sm:gap-5 flex-1">
                            @foreach([
                                ['id' => 'linkedin.com', 'name' => 'LinkedIn'],
                                ['id' => 'glints.com', 'name' => 'Glints'],
                                ['id' => 'jobstreet.co.id', 'name' => 'JobStreet'],
                                ['id' => 'kalibrr.com', 'name' => 'Kalibrr'],
                                ['id' => 'usedeall.com', 'name' => 'Dealls']
                            ] as $platform)
                            <div class="flex items-center gap-1 opacity-70">
                                <img src="https://www.google.com/s2/favicons?domain={{ $platform['id'] }}&sz=64" class="w-3 h-3 object-contain shrink-0" alt="{{ $platform['name'] }}" />
                                <span class="text-[9px] font-bold text-zinc-550 leading-none">{{ $platform['name'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Setup Steps -->
                <div class="grid lg:grid-cols-2 gap-6 items-start">
                    <div class="space-y-6">
                        <div class="bg-white rounded-lg border border-zinc-200/60 p-4 shadow-3xs">
                            <h3 class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-3.5 pb-1.5 border-b border-zinc-100 leading-none">Features Overview</h3>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="p-2.5 bg-zinc-50 border border-zinc-200 rounded-md">
                                    <i class="ph ph-lightning text-base text-zinc-650 mb-1 block"></i>
                                    <h4 class="text-xs font-bold text-zinc-800 leading-tight">1-Click Scraping</h4>
                                    <p class="text-[8px] text-zinc-400 font-bold leading-normal mt-0.5 uppercase tracking-wider">Auto detect titles & details</p>
                                </div>
                                <div class="p-2.5 bg-zinc-50 border border-zinc-200 rounded-md">
                                    <i class="ph ph-rocket text-base text-zinc-655 mb-1 block"></i>
                                    <h4 class="text-xs font-bold text-zinc-800 leading-tight">Board Sync</h4>
                                    <p class="text-[8px] text-zinc-400 font-bold leading-normal mt-0.5 uppercase tracking-wider">Direct transmit to Kanban</p>
                                </div>
                            </div>
                        </div>

                        <!-- Statistics Counter -->
                        <div class="bg-white rounded-lg border border-zinc-200/60 p-3.5 shadow-3xs flex items-center justify-between">
                            <div>
                                <h3 class="text-[8px] font-bold text-zinc-400 uppercase tracking-widest leading-none mb-1">Extension Usage</h3>
                                <p class="text-sm font-bold text-zinc-800 tracking-tight leading-none">{{ $extensionSavedCount }} jobs saved</p>
                            </div>
                            <div class="w-7 h-7 bg-zinc-50 border border-zinc-200 rounded flex items-center justify-center text-zinc-655 shrink-0">
                                <i class="ph ph-puzzle-piece text-sm"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Setup manual card -->
                    <div class="bg-white rounded-lg border border-zinc-200/60 p-4 shadow-3xs">
                        <h3 class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-3.5 pb-1.5 border-b border-zinc-100 leading-none">Manual Developer Mode Install</h3>
                        <div class="space-y-3.5 relative before:absolute before:inset-0 before:ml-[0.65rem] before:h-[75%] before:w-px before:bg-zinc-150">
                            
                            <div class="relative flex items-start gap-2.5">
                                <div class="w-5.5 h-5.5 rounded bg-zinc-50 border border-zinc-200 shadow-3xs flex items-center justify-center shrink-0 z-10 text-[8px] font-bold text-zinc-400 leading-none">01</div>
                                <div>
                                    <h4 class="text-xs font-bold text-zinc-800 leading-tight">Extract Zip Package</h4>
                                    <p class="text-[8.5px] text-zinc-400 font-bold leading-normal uppercase tracking-wider mt-0.5">Download and unzip to a permanent local path.</p>
                                </div>
                            </div>

                            <div class="relative flex items-start gap-2.5">
                                <div class="w-5.5 h-5.5 rounded bg-zinc-50 border border-zinc-200 shadow-3xs flex items-center justify-center shrink-0 z-10 text-[8px] font-bold text-zinc-400 leading-none">02</div>
                                <div>
                                    <h4 class="text-xs font-bold text-zinc-800 leading-tight">Open Extensions Console</h4>
                                    <p class="text-[8.5px] text-zinc-400 font-bold leading-normal uppercase tracking-wider mt-0.5">Navigate to <code class="bg-zinc-100 text-zinc-650 px-1 py-0.5 rounded font-mono text-[8px] lowercase">chrome://extensions/</code> in browser.</p>
                                </div>
                            </div>

                            <div class="relative flex items-start gap-2.5">
                                <div class="w-5.5 h-5.5 rounded bg-zinc-50 border border-zinc-200 shadow-3xs flex items-center justify-center shrink-0 z-10 text-[8px] font-bold text-zinc-400 leading-none">03</div>
                                <div>
                                    <h4 class="text-xs font-bold text-zinc-800 leading-tight">Toggle Developer Mode</h4>
                                    <p class="text-[8.5px] text-zinc-400 font-bold leading-normal uppercase tracking-wider mt-0.5">Enable the developer switch in the top right corner.</p>
                                </div>
                            </div>

                            <div class="relative flex items-start gap-2.5">
                                <div class="w-5.5 h-5.5 rounded bg-emerald-50 border border-emerald-200 shadow-3xs flex items-center justify-center shrink-0 z-10 text-[8px] font-bold text-emerald-600 leading-none">
                                    <i class="ph ph-check"></i>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-zinc-800 leading-tight">Load Unpacked</h4>
                                    <p class="text-[8.5px] text-zinc-400 font-bold leading-normal uppercase tracking-wider mt-0.5">Click "Load unpacked", select your extracted folder, and reload.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- --- TAB 2: CSV TOOLS --- -->
            <div x-show="activeTab === 'csv'" class="space-y-6" style="display:none;" x-cloak>

                @if(!$hasAccess)
                <!-- Locked View for Non-Premium -->
                <div class="max-w-md mx-auto">
                    <div class="bg-white rounded-lg border border-zinc-200/60 p-4 shadow-3xs relative text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-9 h-9 bg-zinc-50 border border-zinc-200 rounded flex items-center justify-center text-amber-500 mb-3.5">
                                <i class="ph ph-crown text-lg"></i>
                            </div>
                            
                            <h2 class="text-xs font-bold text-zinc-800 tracking-tight uppercase">Enterprise-Grade Ingestion</h2>
                            <p class="text-[11px] text-zinc-500 leading-normal max-w-xs mt-1 mb-4 font-semibold">Seamlessly map and ingest thousands of records instantly. This advanced tool is exclusively available for Premium members.</p>
                            
                            <a href="{{ route('payment.premium') }}" class="px-3.5 py-1.5 bg-primary-50 text-zinc-800 border border-primary-200/60 hover:bg-primary-100 rounded font-bold text-[10px] uppercase tracking-wider transition-all shadow-3xs focus:outline-none active:scale-97">
                                <span>Upgrade to Premium</span>
                            </a>
                        </div>
                    </div>
                </div>
                @else
                
                <!-- CSV Importer Stepper and Form -->
                <div x-data="{ 
                    currentStep: 1, 
                    fileName: '', 
                    isScanning: false, 
                    scanProgress: 0,
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
                    <!-- Stepper Progress -->
                    <div class="mb-6 max-w-2xl mx-auto">
                        <div class="relative flex justify-between items-center">
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-0.5 bg-zinc-200">
                                <div class="h-full bg-zinc-900 transition-all duration-300" 
                                     :style="'width: ' + ((currentStep - 1) * 33.3) + '%'"></div>
                            </div>
                            
                            @foreach([
                                ['icon' => 'ph-file-arrow-up', 'label' => 'Source'],
                                ['icon' => 'ph-git-merge', 'label' => 'Mapping'],
                                ['icon' => 'ph-shield-check', 'label' => 'Validation'],
                                ['icon' => 'ph-rocket', 'label' => 'Finalize']
                            ] as $i => $step)
                            <div class="relative z-10 flex flex-col items-center gap-1">
                                <div class="w-6 h-6 rounded border border-zinc-200 bg-white flex items-center justify-center transition-colors"
                                     :class="currentStep > {{ $i+1 }} ? 'bg-zinc-950 border-zinc-950 text-white' : (currentStep == {{ $i+1 }} ? 'border-zinc-800 text-zinc-900 font-bold' : 'bg-zinc-50 text-zinc-400')">
                                    <i class="ph {{ $step['icon'] }} text-[11px]"></i>
                                </div>
                                <span class="text-[8px] font-bold uppercase tracking-wider" :class="currentStep == {{ $i+1 }} ? 'text-zinc-805' : 'text-zinc-400'">{{ $step['label'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Single Integrated Form wrapping uploader and mapping -->
                    <form action="{{ route('csv.import.process') }}" method="POST" enctype="multipart/form-data" id="csvImportForm" class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                        @csrf
                        
                        <!-- Main stage card -->
                        <div class="lg:col-span-8">
                            <!-- Step 1: Upload Source -->
                            <div x-show="currentStep === 1" class="space-y-6">
                                <div class="bg-white rounded-lg border border-zinc-200/60 shadow-3xs overflow-hidden">
                                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-4 py-3 border-b border-zinc-150 w-full">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-6.5 h-6.5 rounded bg-zinc-950 flex items-center justify-center text-white shrink-0">
                                                <i class="ph ph-cloud-arrow-up text-sm"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-xs font-bold text-zinc-800 tracking-tight">Ingestion Hub</h3>
                                                <p class="text-[8px] font-bold text-zinc-400 uppercase mt-0.5 leading-none">Choose your CSV batch file</p>
                                            </div>
                                        </div>
                                        <div class="flex gap-1.5 w-full sm:w-auto">
                                            <a href="{{ route('csv.template') }}" class="flex-1 sm:flex-initial text-center justify-center flex items-center gap-1 px-2.5 py-1 bg-zinc-50 text-zinc-700 border border-zinc-200 rounded text-[9.5px] font-bold uppercase tracking-wider hover:bg-zinc-100 transition-colors">
                                                <i class="ph ph-file-csv text-xs"></i>
                                                <span>Get Template</span>
                                            </a>
                                            <a href="{{ route('csv.export') }}" class="flex-1 sm:flex-initial text-center justify-center flex items-center gap-1 px-2.5 py-1 bg-zinc-50 text-zinc-700 border border-zinc-200 rounded text-[9.5px] font-bold uppercase tracking-wider hover:bg-zinc-100 transition-colors">
                                                <i class="ph ph-download text-xs"></i>
                                                <span>Export CSV</span>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="p-4">
                                        <template x-if="!isScanning">
                                            <div id="drop-zone" 
                                                 class="relative border border-dashed border-zinc-250 rounded-lg p-6 text-center cursor-pointer hover:border-zinc-400 hover:bg-zinc-50/50 transition-colors group"
                                                 onclick="document.getElementById('csv_file').click()">
                                                <input type="file" id="csv_file" name="csv_file" accept=".csv,.txt" class="hidden" 
                                                       @change="fileName = $event.target.files[0].name; startScan()">
                                                
                                                <div class="upload-icon flex flex-col items-center gap-2">
                                                    <div class="w-10 h-10 rounded bg-zinc-50 border border-zinc-150 flex items-center justify-center text-zinc-400 group-hover:text-zinc-600 group-hover:border-zinc-350 transition-colors shadow-3xs">
                                                        <i class="ph ph-file-csv text-xl"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs font-bold text-zinc-800">Drop CSV file here</p>
                                                        <p class="text-[8px] font-bold text-zinc-400 uppercase mt-0.5 leading-none">or browse local documents</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>

                                        <template x-if="isScanning">
                                            <div class="py-8 flex flex-col items-center justify-center space-y-3">
                                                <span class="w-6 h-6 border-2 border-zinc-300 border-t-zinc-850 rounded-full animate-spin"></span>
                                                <div class="text-center">
                                                    <p class="text-xs font-bold text-zinc-700">Validating batches...</p>
                                                    <p class="text-[8px] font-bold text-zinc-400 uppercase mt-0.5 leading-none" x-text="scanProgress + '%'"></p>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Mapping / Preview -->
                            <div x-show="currentStep === 2" x-cloak class="space-y-6">
                                <div class="bg-white rounded-lg border border-zinc-200/60 p-4 space-y-3.5 shadow-3xs">
                                    <h4 class="text-[8px] font-bold text-zinc-400 uppercase tracking-widest flex items-center gap-1 leading-none">
                                        <i class="ph ph-git-merge text-zinc-700 text-xs"></i> Smart Column Mapping
                                    </h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3.5">
                                        @foreach([
                                            'company_name' => 'Company',
                                            'position' => 'Job Title',
                                            'location' => 'Location',
                                            'platform' => 'Source Platform',
                                            'application_status' => 'Status',
                                            'application_date' => 'Date'
                                        ] as $key => $label)
                                            <div class="space-y-1">
                                                <label class="block text-[9.5px] font-bold text-zinc-400 uppercase tracking-wider pl-0.5">{{ $label }}</label>
                                                <div class="relative">
                                                    <select class="w-full pl-3 pr-8 py-1.5 bg-zinc-50 border border-zinc-200 rounded-md text-[11px] font-semibold text-zinc-700 transition-colors outline-none cursor-pointer appearance-none">
                                                        <option value="{{ $key }}">{{ $key }} (Auto)</option>
                                                        <option value="custom">Custom Column...</option>
                                                    </select>
                                                    <i class="ph ph-caret-down absolute right-2.5 top-1/2 -translate-y-1/2 text-zinc-400 pointer-events-none text-[9px]"></i>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Preview Table -->
                                <div class="bg-white rounded-lg border border-zinc-200/60 shadow-3xs overflow-hidden">
                                    <div class="flex items-center justify-between px-4 py-3 border-b border-zinc-150">
                                        <div>
                                            <h3 class="text-xs font-bold text-zinc-800 tracking-tight">Structured Preview</h3>
                                            <p class="text-[8px] font-bold text-zinc-400 uppercase mt-0.5 leading-none" x-text="fileName"></p>
                                        </div>
                                        <span class="px-1.5 py-0.5 bg-emerald-50 border border-emerald-100 text-emerald-650 text-[8px] font-bold rounded uppercase tracking-wider leading-none">3 Rows Detected</span>
                                    </div>

                                    <div class="overflow-x-auto custom-scrollbar">
                                        <table class="w-full border-collapse min-w-[600px]">
                                            <thead>
                                                <tr class="bg-zinc-50/50">
                                                    <th class="w-8 px-2 py-1.5 border-b border-zinc-150"></th>
                                                    @foreach(['Company','Position','Location','Platform','Status','Date'] as $header)
                                                        <th class="px-3 py-1.5 text-left border-b border-zinc-150">
                                                            <span class="text-[8px] font-bold text-zinc-400 uppercase tracking-widest leading-none">{{ $header }}</span>
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
                                                <tr class="hover:bg-zinc-50/50 transition-colors">
                                                    <td class="px-2 py-2 text-center border-b border-zinc-100">
                                                        <span class="text-[8px] font-bold text-zinc-350">{{ $i+1 }}</span>
                                                    </td>
                                                    @foreach($row as $cell)
                                                        <td class="px-3 py-2 border-b border-zinc-100">
                                                            <span class="text-xs text-zinc-600 font-semibold">{{ $cell }}</span>
                                                        </td>
                                                    @endforeach
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="px-4 py-2.5 flex items-center justify-between border-t border-zinc-150">
                                        <button type="button" @click="currentStep = 1" class="text-[9px] font-bold text-zinc-400 hover:text-zinc-700 uppercase tracking-wider flex items-center gap-1 focus:outline-none">
                                            <i class="ph ph-arrow-left"></i> Re-Upload
                                        </button>
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-primary-50 text-zinc-800 border border-primary-200/60 hover:bg-primary-100 rounded-md text-[10px] font-bold uppercase tracking-wider transition-all shadow-3xs focus:outline-none active:scale-97">
                                            <i class="ph ph-rocket-launch text-xs"></i>
                                            <span>Finalize Ingestion</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right sidebar panel for validation rules -->
                        <div class="lg:col-span-4 space-y-6">
                            <div class="bg-white rounded-lg border border-zinc-200/60 p-4 shadow-3xs">
                                <h4 class="text-[8px] font-bold text-zinc-400 uppercase tracking-widest mb-3.5 flex items-center gap-1 pb-1.5 border-b border-zinc-100 leading-none">
                                    <i class="ph ph-shield-check text-zinc-600 text-xs"></i> Import Protocol
                                </h4>
                                <div class="space-y-3">
                                    @foreach([
                                        ['Date Format', 'YYYY-MM-DD (e.g. 2024-05-10)'],
                                        ['Stages Match', 'Applied, Follow Up, HR - Interview, Offering'],
                                        ['Platform Source', 'Free-form platform names'],
                                        ['Career Levels', 'Intern, Full Time, Contract, MT, Freelance']
                                    ] as [$title, $desc])
                                    <div class="flex items-start gap-2">
                                        <div class="w-1 h-1 rounded-full bg-zinc-400 mt-1 shrink-0"></div>
                                        <div>
                                            <p class="text-[11px] font-bold text-zinc-700 leading-none">{{ $title }}</p>
                                            <p class="text-[8.5px] font-bold text-zinc-400 mt-1 uppercase tracking-wider leading-normal">{{ $desc }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="bg-zinc-950 rounded-lg p-4 shadow-3xs border border-zinc-900 text-white">
                                <h4 class="text-[8px] font-bold text-zinc-450 uppercase tracking-widest mb-3.5 pb-1.5 border-b border-white/5 leading-none">Ingestion Limits</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-base font-bold text-white leading-none">500</p>
                                        <p class="text-[8px] font-bold text-zinc-450 uppercase mt-1 leading-none">Max Records</p>
                                    </div>
                                    <div>
                                        <p class="text-base font-bold text-white leading-none">2 MB</p>
                                        <p class="text-[8px] font-bold text-zinc-450 uppercase mt-1 leading-none">Max File Size</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                @endif

            </div>

        </div>
    </div>

    <script>
        function updateUrl(tab) {
            const url = new URL(window.location);
            url.searchParams.set('tab', tab);
            window.history.pushState({}, '', url);
        }

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
