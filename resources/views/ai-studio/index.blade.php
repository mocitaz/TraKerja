<x-app-layout>
    <div class="bg-[#fafafa] min-h-screen pb-16" x-data="{ activeTab: '{{ $activeTab }}' }">
        <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            
            <!-- Standardized Page Header (Aligned with all other TraKerja pages) -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-zinc-200/50 pb-4 mb-5">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-zinc-100 border border-zinc-200/60 rounded-lg flex items-center justify-center text-zinc-500 shrink-0 shadow-2xs">
                        <i class="ph ph-sparkle text-base"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-sm font-bold text-zinc-800 tracking-tight">AI Career Studio</h1>
                            <span class="px-1.5 py-0.5 bg-primary-50 text-zinc-800 text-[9px] font-black uppercase tracking-wider rounded border border-primary-100/60">Studio</span>
                        </div>
                        <p class="text-[11px] text-zinc-400 mt-0.5">Empower your career growth with our suite of intelligent AI tools.</p>
                    </div>
                </div>

                <!-- Action/Stats summary -->
                <div class="flex items-center gap-4 bg-white border border-zinc-200/60 rounded-md px-3 py-1.5 shadow-3xs shrink-0">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
                        <span class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider">AI Engines Active</span>
                    </div>
                </div>
            </div>

            <!-- Standardized Tab Switcher (Lavender Outline Theme) -->
            <div class="flex p-0.5 bg-white border border-zinc-200/70 rounded-md shadow-3xs mb-6 max-w-xl">
                <button @click="activeTab = 'analyzer'; updateUrl('analyzer')" 
                        :class="activeTab === 'analyzer' ? 'bg-primary-50 text-zinc-800 border border-primary-200/60 font-bold shadow-3xs' : 'text-zinc-500 hover:bg-zinc-50 font-semibold'"
                        class="flex-1 justify-center flex items-center gap-1.5 py-1.5 text-[11px] rounded transition-all focus:outline-none">
                    <i class="ph ph-sparkle text-xs"></i>
                    <span>Resume Analyzer</span>
                </button>
                <button @click="activeTab = 'cover-letter'; updateUrl('cover-letter')" 
                        :class="activeTab === 'cover-letter' ? 'bg-primary-50 text-zinc-800 border border-primary-200/60 font-bold shadow-3xs' : 'text-zinc-500 hover:bg-zinc-50 font-semibold'"
                        class="flex-1 justify-center flex items-center gap-1.5 py-1.5 text-[11px] rounded transition-all focus:outline-none">
                    <i class="ph ph-envelope-simple text-xs"></i>
                    <span>Cover Letter</span>
                </button>
                <button @click="activeTab = 'photo'; updateUrl('photo')" 
                        :class="activeTab === 'photo' ? 'bg-primary-50 text-zinc-800 border border-primary-200/60 font-bold shadow-3xs' : 'text-zinc-500 hover:bg-zinc-50 font-semibold'"
                        class="flex-1 justify-center flex items-center gap-1.5 py-1.5 text-[11px] rounded transition-all focus:outline-none">
                    <i class="ph ph-camera text-xs"></i>
                    <span>Photo Studio</span>
                </button>
                <button @click="activeTab = 'outreach'; updateUrl('outreach')" 
                        :class="activeTab === 'outreach' ? 'bg-primary-50 text-zinc-800 border border-primary-200/60 font-bold shadow-3xs' : 'text-zinc-500 hover:bg-zinc-50 font-semibold'"
                        class="flex-1 justify-center flex items-center gap-1.5 py-1.5 text-[11px] rounded transition-all focus:outline-none">
                    <i class="ph ph-paper-plane-tilt text-xs"></i>
                    <span>Outreach</span>
                </button>
            </div>

            <style>
                .char-bar-fill { transition: width 0.3s ease; }
                .option-radio:checked+.option-card {
                    border-color: #27272a !important;
                    background-color: #f4f4f5 !important;
                }
                .option-radio:checked+.option-card i {
                    color: #18181b !important;
                }
                @keyframes skeleton-shimmer {
                    0% { background-position: -200% 0; }
                    100% { background-position: 200% 0; }
                }
                .shimmer-bar {
                    background: linear-gradient(90deg, #e4e4e7 25%, #f4f4f5 50%, #e4e4e7 75%);
                    background-size: 200% 100%;
                    animation: skeleton-shimmer 1.5s infinite;
                }
                @media print {
                    body * { visibility: hidden; }
                    #printable-cover-letter, #printable-cover-letter * { visibility: visible; }
                    #printable-cover-letter {
                        position: absolute;
                        left: 0;
                        top: 0;
                        width: 100%;
                        font-size: 11pt;
                        line-height: 1.6;
                        background: white !important;
                        color: black !important;
                        border: none !important;
                        box-shadow: none !important;
                        padding: 0 !important;
                        margin: 0 !important;
                    }
                }
            </style>

            <!-- ========================================== -->
            <!-- TAB 1: AI RESUME ANALYZER (Symmetrical 6/6 Grid) -->
            <!-- ========================================== -->
            <div x-show="activeTab === 'analyzer'" class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
                    
                    <!-- Left Workspace: Input Studio Card (lg:col-span-6) -->
                    <div class="lg:col-span-6 flex flex-col">
                        @if ($errors->has('analyze_error'))
                        <div class="flex items-start gap-2.5 bg-rose-50 border border-rose-100 rounded-md p-3.5 shadow-3xs mb-4">
                            <i class="ph ph-warning-circle text-rose-500 text-base shrink-0 mt-0.5"></i>
                            <p class="text-[11px] font-semibold text-rose-700 leading-normal">{{ $errors->first('analyze_error') }}</p>
                        </div>
                        @endif

                        <form action="{{ route('ai-analyzer.analyze') }}" method="POST" enctype="multipart/form-data" id="analyzeForm" class="bg-white border border-zinc-200/60 rounded-xl shadow-3xs overflow-hidden flex flex-col justify-between flex-1">
                            @csrf

                            <div class="p-5 space-y-5">
                                <!-- Step 1: Upload Resume -->
                                <div>
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="w-5 h-5 bg-zinc-100 border border-zinc-200 rounded flex items-center justify-center text-zinc-700 font-bold text-[10px]">1</div>
                                        <h3 class="text-xs font-bold text-zinc-800 tracking-tight">Upload Resume</h3>
                                        <span class="text-[8px] font-bold text-zinc-400 uppercase tracking-wider ml-auto">PDF ONLY · MAX 10MB</span>
                                    </div>

                                    <!-- Drop Zone -->
                                    <div id="upload-area-analyzer"
                                         class="relative group border border-dashed border-zinc-200 hover:border-zinc-300 rounded-lg p-6 text-center cursor-pointer hover:bg-zinc-50/40 transition-colors overflow-hidden"
                                         ondragover="handleDragOverAnalyzer(event)"
                                         ondragleave="handleDragLeaveAnalyzer(event)"
                                         ondrop="handleDropAnalyzer(event)">
                                        <input id="resume-file" name="resume" type="file" accept=".pdf"
                                               class="absolute inset-0 opacity-0 cursor-pointer z-20 w-full h-full" required>
                                        <div class="relative z-10 flex flex-col items-center gap-2 pointer-events-none">
                                            <div class="w-8 h-8 bg-zinc-50 border border-zinc-200/60 rounded-lg flex items-center justify-center text-zinc-400 group-hover:text-zinc-600 transition-colors shadow-3xs">
                                                <i class="ph ph-file-arrow-up text-base"></i>
                                            </div>
                                            <div>
                                                <p class="text-[11px] font-bold text-zinc-700">Drop your PDF here or <span class="text-primary-650 underline underline-offset-2">browse</span></p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- File Selected State -->
                                    <div id="file-success-analyzer" class="hidden mt-2">
                                        <div class="flex items-center justify-between gap-3 bg-zinc-50/50 border border-zinc-200 rounded-lg p-3 shadow-3xs">
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                <div class="w-8 h-8 bg-emerald-50 border border-emerald-100/50 rounded-md flex items-center justify-center text-emerald-600 shrink-0">
                                                    <i class="ph ph-file-pdf text-base"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <p id="file-name-analyzer" class="text-[11px] font-bold text-zinc-800 truncate tracking-tight leading-tight"></p>
                                                    <p id="file-size-analyzer" class="text-[8.5px] text-zinc-400 font-bold uppercase mt-0.5"></p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[8px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-100/60 px-1.5 py-0.5 rounded uppercase tracking-wider leading-none">Ready</span>
                                                <button type="button" id="remove-file-analyzer" class="w-6 h-6 flex items-center justify-center text-zinc-400 hover:text-rose-500 hover:bg-rose-50 rounded transition-colors focus:outline-none">
                                                    <i class="ph ph-trash text-xs"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-t border-zinc-100"></div>

                                <!-- Step 2: Job Requirements -->
                                <div>
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="w-5 h-5 bg-zinc-100 border border-zinc-200 rounded flex items-center justify-center text-zinc-700 font-bold text-[10px]">2</div>
                                        <h3 class="text-xs font-bold text-zinc-800 tracking-tight">Job Requirements</h3>
                                    </div>

                                    <textarea id="job_description" name="job_description" rows="6" required minlength="50" maxlength="2500"
                                              class="w-full px-3 py-2 bg-zinc-50/50 border border-zinc-200 focus:bg-white focus:ring-1 focus:ring-zinc-400 focus:border-zinc-400 rounded-lg text-[11px] font-medium text-zinc-700 transition-colors outline-none resize-none leading-relaxed"
                                              placeholder="Paste the target job description or requirements list here to scan for matching skills and criteria..."></textarea>

                                    <!-- Progress Indicator -->
                                    <div class="bg-zinc-50/60 border border-zinc-200/80 rounded-lg p-2.5 mt-2.5 flex flex-col gap-1.5">
                                        <div class="flex justify-between text-[8.5px] font-bold text-zinc-500">
                                            <span class="flex items-center gap-1">
                                                <span id="char-indicator" class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                                <span id="char-hint" class="text-rose-500">Min 50 characters</span>
                                            </span>
                                            <span class="text-zinc-400"><span id="char-count" class="font-bold">0</span> / 2500</span>
                                        </div>
                                        <div class="w-full h-1 bg-zinc-200/80 rounded-full overflow-hidden">
                                            <div id="char-bar" class="char-bar-fill h-full rounded-full bg-rose-400" style="width: 0%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Integrated Form Action Footer -->
                            <div class="px-5 py-3.5 bg-zinc-50/60 border-t border-zinc-150/60 flex items-center justify-between">
                                <p class="text-[10px] text-zinc-400 font-medium">ATS Keyword Match Scan</p>
                                <button type="submit" id="submit-btn-analyzer"
                                        {{ !$analyzerCanAccess ? 'disabled' : '' }}
                                        class="flex items-center justify-center gap-2 py-2 px-4 bg-primary-50 text-zinc-800 border border-primary-200/60 hover:bg-primary-100 text-[10.5px] font-bold uppercase tracking-wider rounded-lg transition-all shadow-3xs disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none">
                                    <span id="submit-icon-analyzer" class="hidden"></span>
                                    <span id="loading-spinner-analyzer" class="hidden w-3 h-3 border-2 border-zinc-400 border-t-zinc-800 rounded-full animate-spin"></span>
                                    <span id="submit-text-analyzer">Start CV Analysis</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Right Workspace: System Status & History Panel (lg:col-span-6) -->
                    <div class="lg:col-span-6 flex flex-col justify-between space-y-5">
                        <!-- Quick Stats & Limits Card -->
                        <div class="bg-white border border-zinc-200/60 rounded-xl p-4 shadow-3xs">
                            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-3">
                                <div>
                                    <h3 class="text-xs font-bold text-zinc-800 tracking-tight">System Status</h3>
                                    <p class="text-[8.5px] font-semibold text-zinc-400 uppercase tracking-wider">Usage limit allocation</p>
                                </div>
                                <span class="font-bold px-2 py-0.5 text-[8.5px] rounded border uppercase tracking-wider {{ $analyzerIsPremium ? 'bg-amber-50 text-amber-800 border-amber-200/60' : 'bg-primary-50 text-zinc-800 border-primary-200/60' }}">
                                    {{ $analyzerIsPremium ? 'Premium Pro' : 'Free Trial' }}
                                </span>
                            </div>
                            
                            <div class="flex justify-between items-center text-[11px] font-semibold text-zinc-600">
                                <span>Remaining Runs This Month</span>
                                <span class="font-bold text-zinc-800">{{ $analyzerRemainingUses }} runs</span>
                            </div>

                            @if(!$analyzerIsPremium)
                                <div class="bg-zinc-50/60 border border-zinc-200/80 rounded-lg p-3 mt-3 flex items-center justify-between gap-3">
                                    <div class="max-w-xs">
                                        <p class="text-[10px] font-bold text-zinc-800 uppercase tracking-wider">Unlock Deeper Matching</p>
                                        <p class="text-[10px] text-zinc-400 mt-0.5 font-medium">Upgrade for advanced ATS keyword phrase matching and 5x analysis limit.</p>
                                    </div>
                                    <a href="{{ route('payment.premium') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary-50 text-zinc-800 border border-primary-200/60 hover:bg-primary-100 text-[10px] font-bold uppercase tracking-wider rounded-lg transition-colors shrink-0">
                                        <i class="ph-bold ph-crown text-amber-500 text-xs"></i>
                                        <span>Upgrade</span>
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Past Analysis History Card -->
                        <div class="bg-white rounded-xl border border-zinc-200/60 p-4 shadow-3xs flex-1 flex flex-col">
                            <h3 class="text-xs font-bold text-zinc-800 tracking-tight mb-3">Past Analysis Runs</h3>
                            
                            @if($analyzerHistory->isEmpty())
                                <div class="flex-1 flex flex-col items-center justify-center text-center p-6 border border-dashed border-zinc-200 rounded-lg bg-zinc-50/20">
                                    <div class="w-8 h-8 rounded-lg bg-zinc-100 flex items-center justify-center text-zinc-400 mb-2">
                                        <i class="ph ph-file-text text-lg"></i>
                                    </div>
                                    <p class="text-[11px] font-bold text-zinc-600">No CV scans recorded</p>
                                    <p class="text-[9.5px] font-medium text-zinc-400 mt-0.5">Upload your PDF on the left to generate ATS compatibility insights.</p>
                                </div>
                            @else
                                <div class="divide-y divide-zinc-100 border border-zinc-200/80 rounded-lg overflow-hidden bg-white shadow-3xs flex-1">
                                    @foreach($analyzerHistory as $result)
                                        <a href="{{ route('ai-analyzer.show', $result->id) }}" class="flex items-center justify-between px-3.5 py-3 hover:bg-zinc-50/60 transition-colors">
                                            <div class="min-w-0 flex-1">
                                                <h4 class="text-[11px] font-bold text-zinc-800 truncate leading-none">{{ $result->company_name ?? 'Resume Scan' }}</h4>
                                                <p class="text-[9px] text-zinc-400 font-semibold uppercase tracking-wider mt-1">{{ $result->created_at->diffForHumans() }}</p>
                                            </div>
                                            <div class="flex items-center gap-2 shrink-0">
                                                @if(isset($result->analysis_result['ats_compatibility_score']))
                                                    <span class="px-2 py-0.5 rounded bg-primary-50 border border-primary-200/60 text-[10px] font-bold text-zinc-800">
                                                        {{ $result->analysis_result['ats_compatibility_score'] }} pts
                                                    </span>
                                                @endif
                                                <i class="ph-bold ph-caret-right text-zinc-400 text-xs"></i>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Symmetrical Feature Tip Banner -->
                        <div class="bg-primary-50/40 border border-primary-200/50 rounded-xl p-3.5 flex items-center gap-3 shadow-3xs">
                            <div class="w-7 h-7 bg-white border border-primary-200/60 rounded-lg flex items-center justify-center text-amber-500 shrink-0 shadow-3xs">
                                <i class="ph-fill ph-lightbulb text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-[11px] font-bold text-zinc-800">ATS Optimization Tip</h4>
                                <p class="text-[10px] text-zinc-500 font-medium leading-tight mt-0.5">Ensure your target job description contains hard technical skills for maximum keyword matching precision.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- TAB 2: AI COVER LETTER (Symmetrical 6/6 Grid) -->
            <!-- ========================================== -->
            <div x-show="activeTab === 'cover-letter'" class="space-y-6" style="display:none;" x-cloak>
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
                    
                    <!-- Left Configuration Form Card (lg:col-span-6) -->
                    <form action="{{ route('cover-letters.generate') }}" method="POST" id="coverLetterForm" class="lg:col-span-6 flex flex-col">
                        @csrf
                        <div class="bg-white border border-zinc-200/60 rounded-xl shadow-3xs overflow-hidden flex flex-col justify-between flex-1">
                            <div class="p-5 space-y-4">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="w-5 h-5 bg-zinc-100 border border-zinc-200 rounded flex items-center justify-center text-zinc-700 font-bold text-[10px]">1</div>
                                    <h3 class="text-xs font-bold text-zinc-800 tracking-tight">Target Parameters</h3>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                    <div class="space-y-1">
                                        <label for="cl_company_name" class="text-[8.5px] font-bold text-zinc-400 uppercase tracking-wider pl-0.5">Company Name</label>
                                        <input type="text" id="cl_company_name" name="company_name" required
                                            class="w-full px-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-lg text-[11px] font-semibold text-zinc-700 focus:ring-1 focus:ring-zinc-400 focus:border-zinc-400 focus:bg-white transition-colors outline-none"
                                            placeholder="e.g. Gojek" value="{{ old('company_name') }}">
                                    </div>

                                    <div class="space-y-1">
                                        <label for="cl_job_title" class="text-[8.5px] font-bold text-zinc-400 uppercase tracking-wider pl-0.5">Position</label>
                                        <input type="text" id="cl_job_title" name="job_title" required
                                            class="w-full px-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-lg text-[11px] font-semibold text-zinc-700 focus:ring-1 focus:ring-zinc-400 focus:border-zinc-400 focus:bg-white transition-colors outline-none"
                                            placeholder="e.g. Software Engineer" value="{{ old('job_title') }}">
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <label for="cl_job_description" class="text-[8.5px] font-bold text-zinc-400 uppercase tracking-wider pl-0.5">Job Description</label>
                                    <textarea id="cl_job_description" name="job_description" rows="4" required minlength="50" maxlength="2500"
                                        class="w-full px-3 py-2 bg-zinc-50/50 border border-zinc-200 rounded-lg text-[11px] font-medium text-zinc-700 focus:ring-1 focus:ring-zinc-400 focus:border-zinc-400 focus:bg-white transition-colors outline-none resize-none leading-relaxed"
                                        placeholder="Paste target job requirements and qualifications here..."></textarea>
                                </div>

                                <div class="border-t border-zinc-100 pt-3">
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="w-5 h-5 bg-zinc-100 border border-zinc-200 rounded flex items-center justify-center text-zinc-700 font-bold text-[10px]">2</div>
                                        <h3 class="text-xs font-bold text-zinc-800 tracking-tight">Style Configuration</h3>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 mb-3">
                                        <div class="space-y-1">
                                            <label for="language" class="text-[8.5px] font-bold text-zinc-400 uppercase tracking-wider pl-0.5">Language</label>
                                            <select name="language" id="language" class="w-full px-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-lg text-[11px] font-bold text-zinc-700 focus:ring-1 focus:ring-zinc-400 focus:bg-white outline-none cursor-pointer">
                                                <option value="id">Indonesian</option>
                                                <option value="en">English (US)</option>
                                            </select>
                                        </div>

                                        <div class="space-y-1">
                                            <label for="length" class="text-[8.5px] font-bold text-zinc-400 uppercase tracking-wider pl-0.5">Target Length</label>
                                            <select name="length" id="length" class="w-full px-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-lg text-[11px] font-bold text-zinc-700 focus:ring-1 focus:ring-zinc-400 focus:bg-white outline-none cursor-pointer">
                                                <option value="standard">Standard (3-4 Paragraphs)</option>
                                                <option value="short">Short & Punchy</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Brand Tone Segment Pills -->
                                    <div class="space-y-1">
                                        <label class="text-[8.5px] font-bold text-zinc-400 uppercase tracking-wider pl-0.5">Brand Tone</label>
                                        <div class="grid grid-cols-4 gap-2">
                                            <label class="relative cursor-pointer">
                                                <input type="radio" name="tone" value="professional" checked class="sr-only option-radio">
                                                <div class="option-card border border-zinc-200 rounded-lg p-2 text-center bg-white hover:border-zinc-300 transition-colors shadow-3xs">
                                                    <i class="ph ph-briefcase text-sm text-zinc-400 mb-1 block mx-auto"></i>
                                                    <span class="text-[10px] font-bold text-zinc-700 block">Pro</span>
                                                </div>
                                            </label>
                                            <label class="relative cursor-pointer">
                                                <input type="radio" name="tone" value="creative" class="sr-only option-radio">
                                                <div class="option-card border border-zinc-200 rounded-lg p-2 text-center bg-white hover:border-zinc-300 transition-colors shadow-3xs">
                                                    <i class="ph ph-palette text-sm text-zinc-400 mb-1 block mx-auto"></i>
                                                    <span class="text-[10px] font-bold text-zinc-700 block">Creative</span>
                                                </div>
                                            </label>
                                            <label class="relative cursor-pointer">
                                                <input type="radio" name="tone" value="bold" class="sr-only option-radio">
                                                <div class="option-card border border-zinc-200 rounded-lg p-2 text-center bg-white hover:border-zinc-300 transition-colors shadow-3xs">
                                                    <i class="ph ph-lightning text-sm text-zinc-400 mb-1 block mx-auto"></i>
                                                    <span class="text-[10px] font-bold text-zinc-700 block">Bold</span>
                                                </div>
                                            </label>
                                            <label class="relative cursor-pointer">
                                                <input type="radio" name="tone" value="warm" class="sr-only option-radio">
                                                <div class="option-card border border-zinc-200 rounded-lg p-2 text-center bg-white hover:border-zinc-300 transition-colors shadow-3xs">
                                                    <i class="ph ph-smiley text-sm text-zinc-400 mb-1 block mx-auto"></i>
                                                    <span class="text-[10px] font-bold text-zinc-700 block">Warm</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="px-5 py-3.5 bg-zinc-50/60 border-t border-zinc-150/60 flex items-center justify-between">
                                <p class="text-[10px] text-zinc-400 font-medium">Generative AI Compiler</p>
                                <button type="submit" id="submit-btn-cl"
                                    {{ !$clRemainingUses && !\App\Models\Setting::isMonetizationEnabled() ? 'disabled' : '' }}
                                    class="flex items-center justify-center gap-2 py-2 px-4 bg-primary-50 text-zinc-800 border border-primary-200/60 hover:bg-primary-100 text-[10.5px] font-bold uppercase tracking-wider rounded-lg transition-all shadow-3xs focus:outline-none">
                                    <span id="submit-icon-cl" class="hidden"></span>
                                    <span id="loading-spinner-cl" class="hidden w-3 h-3 border-2 border-zinc-400 border-t-zinc-800 rounded-full animate-spin"></span>
                                    <span id="submit-text-cl">Generate Letter</span>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Right Workspace: Live Editor & Saved Letters Pane (lg:col-span-6) -->
                    <div class="lg:col-span-6 flex flex-col justify-between space-y-5">
                        <!-- Live AI Editor Card -->
                        <div class="bg-white border border-zinc-200/60 rounded-xl p-4 shadow-3xs">
                            <div class="flex items-center justify-between border-b border-zinc-100 pb-3 mb-4">
                                <div>
                                    <h3 class="text-xs font-bold text-zinc-800 tracking-tight">Live AI Editor</h3>
                                    <p class="text-[8.5px] font-semibold text-zinc-400 uppercase tracking-wider">Dynamic draft compiler</p>
                                </div>
                                <span class="px-2 py-0.5 bg-primary-50 text-zinc-800 border border-primary-200/60 text-[8.5px] font-bold uppercase tracking-wider rounded">AI Canvas</span>
                            </div>

                            <!-- Skeleton Loading State -->
                            <div id="skeleton-content" class="space-y-2.5 py-2">
                                <div class="shimmer-bar h-3 rounded w-1/3"></div>
                                <div class="shimmer-bar h-3 rounded w-1/2 mt-3"></div>
                                <div class="space-y-1 mt-4">
                                    <div class="shimmer-bar h-2.5 rounded w-full"></div>
                                    <div class="shimmer-bar h-2.5 rounded w-5/6"></div>
                                    <div class="shimmer-bar h-2.5 rounded w-11/12"></div>
                                </div>
                            </div>

                            <!-- Real Content Editor Canvas -->
                            <div id="real-content" class="hidden">
                                <div class="bg-zinc-50/80 border border-zinc-200/80 p-4 rounded-lg shadow-inner max-h-[300px] overflow-y-auto" id="printable-cover-letter">
                                    <p id="text-content" class="text-[11px] font-medium leading-relaxed text-zinc-800 whitespace-pre-wrap select-all font-mono"></p>
                                </div>
                            </div>

                            <!-- Generated Actions Panel -->
                            <div id="generated-actions" class="hidden grid grid-cols-2 gap-2.5 mt-4">
                                <button type="button" onclick="copyGeneratedLetter()" id="copy-btn"
                                    class="flex items-center justify-center gap-1.5 py-2 px-3 bg-primary-50 text-zinc-800 border border-primary-200/60 hover:bg-primary-100 text-[10px] font-bold uppercase tracking-wider rounded-lg transition-colors focus:outline-none">
                                    <i id="copy-icon" class="ph-bold ph-copy text-xs"></i>
                                    <span id="copy-text">Copy Letter</span>
                                </button>
                                <button type="button" onclick="window.print()"
                                    class="flex items-center justify-center gap-1.5 py-2 px-3 bg-white border border-zinc-200 text-zinc-700 hover:bg-zinc-50 text-[10px] font-bold uppercase tracking-wider rounded-lg transition-colors focus:outline-none">
                                    <i class="ph-bold ph-printer text-xs"></i>
                                    <span>Print Draft</span>
                                </button>
                            </div>
                        </div>

                        <!-- Saved Letters History Card -->
                        <div class="bg-white rounded-xl border border-zinc-200/60 p-4 shadow-3xs flex-1 flex flex-col">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-xs font-bold text-zinc-800 tracking-tight">Saved Letters</h3>
                                <span class="text-[9px] font-bold text-zinc-400 uppercase tracking-wider">{{ $clRemainingUses }} Runs Remaining</span>
                            </div>

                            @if($clHistory->isEmpty())
                                <div class="flex-1 flex flex-col items-center justify-center text-center p-6 border border-dashed border-zinc-200 rounded-lg bg-zinc-50/20">
                                    <i class="ph ph-envelope text-xl text-zinc-300 mb-2"></i>
                                    <p class="text-[11px] font-bold text-zinc-600">No drafts compiled yet</p>
                                    <p class="text-[9.5px] font-medium text-zinc-400 mt-0.5">Configure parameters on the left to generate customized cover letters.</p>
                                </div>
                            @else
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 flex-1">
                                    @foreach($clHistory as $letter)
                                        <a href="{{ route('cover-letters.show', $letter->id) }}" class="block p-3 border border-zinc-200/80 rounded-lg hover:border-zinc-300 transition-all bg-zinc-50/30 hover:bg-white shadow-3xs flex flex-col justify-between">
                                            <div>
                                                <div class="flex justify-between items-start mb-1">
                                                    <h4 class="font-bold text-zinc-800 truncate text-[11px] leading-tight flex-1 pr-2">{{ $letter->job_title }}</h4>
                                                    <span class="bg-primary-50 text-zinc-800 text-[8px] font-bold px-1.5 py-0.5 rounded border border-primary-200/60 uppercase leading-none select-none shrink-0">
                                                        {{ $letter->tone }}
                                                    </span>
                                                </div>
                                                <p class="text-[9.5px] text-zinc-400 font-semibold truncate">{{ $letter->company_name }}</p>
                                            </div>
                                            <p class="text-[8.5px] text-zinc-400 font-bold uppercase tracking-wider mt-3 flex items-center gap-1">
                                                <i class="ph ph-clock text-xs"></i>
                                                {{ $letter->created_at->diffForHumans() }}
                                            </p>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- TAB 3: AI PHOTO STUDIO (Symmetrical 6/6 Grid) -->
            <!-- ========================================== -->
            <div x-show="activeTab === 'photo'" class="space-y-6" style="display:none;" x-cloak>
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
                    
                    <!-- Left Configuration Form Card (lg:col-span-6) -->
                    <div class="lg:col-span-6 flex flex-col">
                        @if (session('error'))
                        <div class="flex items-start gap-2.5 bg-rose-50 border border-rose-100 rounded-md p-3.5 shadow-3xs mb-4">
                            <i class="ph ph-warning-circle text-rose-500 text-base shrink-0 mt-0.5"></i>
                            <p class="text-[11px] font-semibold text-rose-700 leading-normal">{{ session('error') }}</p>
                        </div>
                        @endif

                        <form action="{{ route('ai-photo.process') }}" method="POST" enctype="multipart/form-data" id="photoForm" x-data="{ type: 'remove_bg', mode: 'portrait' }" class="bg-white border border-zinc-200/60 rounded-xl shadow-3xs overflow-hidden flex flex-col justify-between flex-1">
                            @csrf
                            <div class="p-5 space-y-4">
                                <!-- Step 1: Upload Portrait -->
                                <div>
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="w-5 h-5 bg-zinc-100 border border-zinc-200 rounded flex items-center justify-center text-zinc-700 font-bold text-[10px]">1</div>
                                        <h3 class="text-xs font-bold text-zinc-800 tracking-tight">Upload Portrait</h3>
                                        <span class="text-[8px] font-bold text-zinc-400 uppercase tracking-wider ml-auto">JPG, PNG, WEBP · MAX 20MB</span>
                                    </div>

                                    <div id="upload-area-photo"
                                         class="relative group border border-dashed border-zinc-200 hover:border-zinc-300 rounded-lg p-6 text-center cursor-pointer hover:bg-zinc-50/40 transition-colors overflow-hidden"
                                         ondragover="handleDragOverPhoto(event)"
                                         ondragleave="handleDragLeavePhoto(event)"
                                         ondrop="handleDropPhoto(event)">
                                        <input id="photo-file" name="photo" type="file" accept="image/jpeg,image/png,image/webp"
                                               class="absolute inset-0 opacity-0 cursor-pointer z-20 w-full h-full" required>
                                        <div class="relative z-10 flex flex-col items-center gap-2 pointer-events-none">
                                            <div class="w-8 h-8 bg-zinc-50 border border-zinc-200/60 rounded-lg flex items-center justify-center text-zinc-400 group-hover:text-zinc-600 transition-colors shadow-3xs">
                                                <i class="ph ph-image text-base"></i>
                                            </div>
                                            <div>
                                                <p class="text-[11px] font-bold text-zinc-700">Drop photo here or <span class="text-primary-650 underline underline-offset-2">browse</span></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="file-success-photo" class="hidden mt-2">
                                        <div class="flex items-center justify-between gap-3 bg-zinc-50/50 border border-zinc-200 rounded-lg p-3 shadow-3xs">
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                <div class="w-10 h-10 bg-zinc-100 border border-zinc-200 rounded-md overflow-hidden relative shadow-3xs shrink-0">
                                                    <img id="image-preview-photo" src="#" alt="Preview" class="absolute inset-0 w-full h-full object-cover">
                                                </div>
                                                <div class="min-w-0">
                                                    <p id="file-name-photo" class="text-[11px] font-bold text-zinc-800 truncate tracking-tight leading-tight"></p>
                                                    <p id="file-size-photo" class="text-[8.5px] text-zinc-400 font-bold uppercase mt-0.5"></p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[8px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-100/60 px-1.5 py-0.5 rounded uppercase tracking-wider leading-none">Ready</span>
                                                <button type="button" id="remove-file-photo" class="w-6 h-6 flex items-center justify-center text-zinc-400 hover:text-rose-500 hover:bg-rose-50 rounded transition-colors focus:outline-none">
                                                    <i class="ph ph-trash text-xs"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-t border-zinc-100 pt-3">
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="w-5 h-5 bg-zinc-100 border border-zinc-200 rounded flex items-center justify-center text-zinc-700 font-bold text-[10px]">2</div>
                                        <h3 class="text-xs font-bold text-zinc-800 tracking-tight">Service Mode</h3>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3 mb-3">
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" name="type" value="remove_bg" x-model="type" class="sr-only">
                                            <div class="border rounded-lg p-3 transition-all duration-150"
                                                 :class="type === 'remove_bg' ? 'border-primary-200/80 bg-primary-50/50 shadow-3xs' : 'border-zinc-200 bg-white hover:border-zinc-300'">
                                                <div class="flex gap-2.5 items-center">
                                                    <div class="w-7 h-7 rounded-md flex items-center justify-center shrink-0"
                                                         :class="type === 'remove_bg' ? 'bg-primary-50 text-zinc-800 border border-primary-200/60' : 'bg-zinc-50 text-zinc-400'">
                                                        <i class="ph-bold ph-eraser text-xs"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-[11px] font-bold text-zinc-800">Remove BG</p>
                                                        <p class="text-[8px] font-semibold text-zinc-400 leading-none mt-0.5">Keep original apparel.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                        
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" name="type" value="enhance" x-model="type" class="sr-only">
                                            <div class="border rounded-lg p-3 transition-all duration-150"
                                                 :class="type === 'enhance' ? 'border-primary-200/80 bg-primary-50/50 shadow-3xs' : 'border-zinc-200 bg-white hover:border-zinc-300'">
                                                <div class="flex gap-2.5 items-center">
                                                    <div class="w-7 h-7 rounded-md flex items-center justify-center shrink-0"
                                                         :class="type === 'enhance' ? 'bg-primary-50 text-zinc-800 border border-primary-200/60' : 'bg-zinc-50 text-zinc-400'">
                                                        <i class="ph-bold ph-magic-wand text-xs"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-[11px] font-bold text-zinc-800">AI Enhance</p>
                                                        <p class="text-[8px] font-semibold text-zinc-400 leading-none mt-0.5">Add professional blazers.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Step 3: Options -->
                                <div x-show="type === 'remove_bg'" class="border-t border-zinc-100 pt-3">
                                    <div class="grid grid-cols-2 gap-3.5">
                                        <div class="space-y-1">
                                            <label class="text-[8.5px] font-bold text-zinc-400 uppercase tracking-wider pl-0.5">Background Color</label>
                                            <select name="background" class="w-full px-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-lg text-[11px] font-bold text-zinc-700 focus:ring-1 focus:ring-zinc-400 focus:bg-white outline-none cursor-pointer">
                                                <option value="transparan">Transparent (PNG)</option>
                                                <option value="merah">Merah (Red)</option>
                                                <option value="biru">Biru (Blue)</option>
                                                <option value="biru_muda">Biru Muda (Light Blue)</option>
                                                <option value="putih">Putih (White)</option>
                                            </select>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[8.5px] font-bold text-zinc-400 uppercase tracking-wider pl-0.5">Photo Size</label>
                                            <select name="size" class="w-full px-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-lg text-[11px] font-bold text-zinc-700 focus:ring-1 focus:ring-zinc-400 focus:bg-white outline-none cursor-pointer">
                                                <option value="original">Original Aspect Ratio</option>
                                                <option value="3x4">3x4 (Standard)</option>
                                                <option value="4x6">4x6 (Standard)</option>
                                                <option value="2x3">2x3</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div x-show="type === 'enhance'" style="display:none;" class="border-t border-zinc-100 pt-3">
                                    <div class="grid grid-cols-2 gap-3.5">
                                        <div class="space-y-1">
                                            <label class="text-[8.5px] font-bold text-zinc-400 uppercase tracking-wider pl-0.5">Clothing Style</label>
                                            <select name="style" class="w-full px-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-lg text-[11px] font-bold text-zinc-700 focus:ring-1 focus:ring-zinc-400 focus:bg-white outline-none cursor-pointer">
                                                <option value="auto">Auto Detect Gender</option>
                                                <option value="linkedin_pria">LinkedIn (Male Suit)</option>
                                                <option value="linkedin_wanita">LinkedIn (Female Blazer)</option>
                                                <option value="rapi_formal">Rapi Formal (Suit & Tie)</option>
                                                <option value="professional_wanita">Professional (Blazer)</option>
                                            </select>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[8.5px] font-bold text-zinc-400 uppercase tracking-wider pl-0.5">AI Background</label>
                                            <select name="background" class="w-full px-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-lg text-[11px] font-bold text-zinc-700 focus:ring-1 focus:ring-zinc-400 focus:bg-white outline-none cursor-pointer">
                                                <option value="studio_plain">Studio Plain</option>
                                                <option value="studio_gradient">Studio Gradient</option>
                                                <option value="modern_office">Modern Office</option>
                                                <option value="meeting_room">Meeting Room</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="px-5 py-3.5 bg-zinc-50/60 border-t border-zinc-150/60 flex items-center justify-between">
                                <p class="text-[10px] text-zinc-400 font-medium">Face Recognition AI Engine</p>
                                <button type="submit" id="submit-btn-photo"
                                        class="flex items-center justify-center gap-2 py-2 px-4 bg-primary-50 text-zinc-800 border border-primary-200/60 hover:bg-primary-100 text-[10.5px] font-bold uppercase tracking-wider rounded-lg transition-all shadow-3xs focus:outline-none">
                                    <span id="submit-icon-photo" class="hidden"></span>
                                    <span id="loading-spinner-photo" class="hidden w-3 h-3 border-2 border-zinc-400 border-t-zinc-800 rounded-full animate-spin"></span>
                                    <span id="submit-text-photo">Enhance Photo</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Right Workspace: Gallery & Guidelines (lg:col-span-6) -->
                    <div class="lg:col-span-6 flex flex-col justify-between space-y-5">
                        <!-- Studio Status Card -->
                        <div class="bg-white border border-zinc-200/60 rounded-xl p-4 shadow-3xs">
                            <h3 class="text-xs font-bold text-zinc-800 tracking-tight pb-2.5 mb-2.5 border-b border-zinc-100">Studio Configuration</h3>
                            <div class="grid grid-cols-2 gap-4 text-[11px] font-semibold">
                                <div class="p-3 bg-zinc-50/50 border border-zinc-200/60 rounded-lg">
                                    <span class="text-[8.5px] font-bold text-zinc-400 uppercase tracking-wider block mb-1">Available Credits</span>
                                    <span class="text-zinc-800 font-bold text-xs">{{ $photoStats['remaining_credits'] }} generations</span>
                                </div>
                                <div class="p-3 bg-zinc-50/50 border border-zinc-200/60 rounded-lg">
                                    <span class="text-[8.5px] font-bold text-zinc-400 uppercase tracking-wider block mb-1">Total Generated</span>
                                    <span class="text-zinc-800 font-bold text-xs">{{ $photoStats['total_generated'] }} photos</span>
                                </div>
                            </div>
                        </div>

                        <!-- Generated Portraits Gallery Card -->
                        <div class="bg-white rounded-xl border border-zinc-200/60 p-4 shadow-3xs flex-1 flex flex-col">
                            <h3 class="text-xs font-bold text-zinc-800 tracking-tight mb-3">Generated Portraits</h3>
                            @if($photoHistory->isEmpty())
                                <div class="flex-1 flex flex-col items-center justify-center text-center p-6 border border-dashed border-zinc-200 rounded-lg bg-zinc-50/20">
                                    <i class="ph ph-image text-xl text-zinc-300 mb-2"></i>
                                    <p class="text-[11px] font-bold text-zinc-600">No AI portraits generated yet</p>
                                    <p class="text-[9.5px] font-medium text-zinc-400 mt-0.5">Upload a photo on the left to remove background or apply professional suits.</p>
                                </div>
                            @else
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 flex-1">
                                    @foreach($photoHistory as $photo)
                                        <div class="group bg-white border border-zinc-200/80 rounded-lg overflow-hidden shadow-3xs flex flex-col justify-between">
                                            <div class="relative bg-zinc-50 overflow-hidden pt-[120%] shrink-0 border-b border-zinc-150">
                                                <img src="{{ $photo->result_url }}" alt="AI Portrait" class="absolute inset-0 w-full h-full object-cover object-top transition-transform duration-300 group-hover:scale-105">
                                            </div>
                                            <div class="p-2 flex flex-col gap-1.5 bg-zinc-50/30">
                                                <span class="inline-flex items-center self-start text-[8px] font-bold uppercase tracking-wider px-1.5 py-0.5 bg-primary-50 text-zinc-800 rounded border border-primary-200/60">
                                                    {{ str_replace('_', ' ', $photo->type) }}
                                                </span>
                                                <div class="grid grid-cols-2 gap-1 pt-0.5">
                                                    <a href="{{ route('ai-photo.show', $photo->id) }}" class="flex items-center justify-center gap-0.5 text-[9px] font-bold text-zinc-700 bg-white border border-zinc-200 hover:bg-zinc-50 py-1 rounded-md transition-colors uppercase">
                                                        <span>Detail</span>
                                                    </a>
                                                    <a href="{{ $photo->result_url }}" download class="flex items-center justify-center gap-0.5 text-[9px] font-bold text-zinc-800 bg-primary-50 border border-primary-200/60 hover:bg-primary-100 py-1 rounded-md transition-colors uppercase">
                                                        <span>Unduh</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            <!-- ========================================== -->
            <!-- TAB 4: RECRUITER OUTREACH GENERATOR -->
            <!-- ========================================== -->
            <div x-show="activeTab === 'outreach'" class="space-y-6" style="display:none;" x-cloak>
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
                    <!-- Left Workspace: Input Studio Card -->
                    <div class="lg:col-span-6 flex flex-col">
                        <form id="outreachForm" onsubmit="generateOutreach(event)" class="bg-white border border-zinc-200/60 rounded-xl shadow-3xs overflow-hidden flex flex-col justify-between flex-1">
                            <div class="p-5 space-y-4">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="w-5 h-5 bg-zinc-100 border border-zinc-200 rounded flex items-center justify-center text-zinc-700 font-bold text-[10px]">1</div>
                                    <h3 class="text-xs font-bold text-zinc-800 tracking-tight">Outreach Target Details</h3>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-wider mb-1">Recruiter / Hiring Manager Name</label>
                                        <input type="text" id="outreach_recruiter" placeholder="e.g. Sarah Jenkins (Optional)" class="block w-full px-3 h-[32px] bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 focus:ring-1 focus:ring-primary-500/20 focus:bg-white focus:border-primary-500 transition-all outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-wider mb-1">Target Company *</label>
                                        <input type="text" id="outreach_company" required placeholder="e.g. Tokopedia" class="block w-full px-3 h-[32px] bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 focus:ring-1 focus:ring-primary-500/20 focus:bg-white focus:border-primary-500 transition-all outline-none">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-wider mb-1">Applied / Target Job Title *</label>
                                    <input type="text" id="outreach_job" required placeholder="e.g. Senior Product Designer" class="block w-full px-3 h-[32px] bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 focus:ring-1 focus:ring-primary-500/20 focus:bg-white focus:border-primary-500 transition-all outline-none">
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                                    <div>
                                        <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-wider mb-1">Outreach Channel</label>
                                        <select id="outreach_channel" class="block w-full px-2.5 py-0 h-[32px] bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 outline-none focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 transition-all cursor-pointer">
                                            <option value="LinkedIn InMail">LinkedIn InMail</option>
                                            <option value="Email">Direct Email</option>
                                            <option value="Direct Message">Direct Message (DM)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-wider mb-1">Communication Tone</label>
                                        <select id="outreach_tone" class="block w-full px-2.5 py-0 h-[32px] bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 outline-none focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 transition-all cursor-pointer">
                                            <option value="Professional">Professional & Polished</option>
                                            <option value="Friendly">Friendly & Enthusiastic</option>
                                            <option value="Persuasive">Persuasive & Direct</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 bg-zinc-50/50 border-t border-zinc-150/60 flex items-center justify-between">
                                <span class="text-[9.5px] font-bold text-zinc-400 uppercase tracking-wider">Instant AI Outreach</span>
                                <button type="submit" id="submit-btn-outreach" class="px-4 h-[32px] bg-primary-50 text-zinc-800 hover:bg-primary-100 border border-primary-200/60 text-xs font-bold rounded-md shadow-3xs transition-all active:scale-97 flex items-center gap-1.5 focus:outline-none">
                                    <span id="submit-text-outreach">Generate Outreach Message</span>
                                    <span id="loading-spinner-outreach" class="hidden"><i class="ph ph-spinner animate-spin text-xs"></i></span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Right Output Preview Card -->
                    <div class="lg:col-span-6 flex flex-col">
                        <div class="bg-white border border-zinc-200/60 rounded-xl shadow-3xs p-5 flex flex-col justify-between flex-1 relative min-h-[350px]">
                            <div class="flex items-center justify-between pb-3 border-b border-zinc-150/60 mb-4">
                                <div class="flex items-center gap-2">
                                    <i class="ph-bold ph-paper-plane-tilt text-zinc-700 text-sm"></i>
                                    <h3 class="text-xs font-bold text-zinc-800">Generated Message Preview</h3>
                                </div>
                                <button type="button" onclick="copyOutreachMessage()" id="copy-outreach-btn" class="hidden px-2.5 py-1 bg-primary-50 hover:bg-primary-100 border border-primary-200/60 text-zinc-800 text-[10px] font-bold rounded transition-all items-center gap-1">
                                    <i id="copy-outreach-icon" class="ph-bold ph-copy text-xs"></i>
                                    <span id="copy-outreach-text">Copy Message</span>
                                </button>
                            </div>

                            <!-- Empty / Placeholder State -->
                            <div id="outreach-empty" class="flex-1 flex flex-col items-center justify-center text-center p-8">
                                <div class="w-10 h-10 bg-zinc-50 border border-zinc-200/60 rounded-lg flex items-center justify-center text-zinc-400 mb-2 shadow-3xs">
                                    <i class="ph ph-paper-plane-tilt text-lg"></i>
                                </div>
                                <p class="text-xs font-bold text-zinc-700">No Outreach Drafted Yet</p>
                                <p class="text-[10px] text-zinc-400 max-w-xs mt-1">Fill out the target details on the left and click generate to build a personalized cold message.</p>
                            </div>

                            <!-- Output Box -->
                            <div id="outreach-result" class="hidden flex-1 flex flex-col justify-between">
                                <textarea id="outreach-text-content" readonly rows="10" class="w-full bg-zinc-50 p-3.5 border border-zinc-200/80 rounded-lg text-xs font-medium text-zinc-800 leading-relaxed outline-none resize-none select-all"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Loading overlay for AI Photo Studio -->
    <div id="ai-loading-overlay" class="fixed inset-0 z-[99999] hidden items-center justify-center bg-zinc-950/75 backdrop-blur-xs transition-opacity duration-300 opacity-0" style="position: fixed !important;">
        <div class="flex flex-col items-center justify-center text-center transform scale-98 transition-transform duration-500 w-full max-w-sm px-4" id="ai-loading-card">
            <div class="relative w-20 h-20 flex items-center justify-center mb-4">
                <svg class="w-16 h-16 transform -rotate-90">
                    <circle cx="32" cy="32" r="28" stroke="#e4e4e7" stroke-width="3.5" fill="transparent" class="opacity-15" />
                    <circle cx="32" cy="32" r="28" stroke="url(#spinnerGradient)" stroke-width="3.5" fill="transparent"
                        stroke-dasharray="175.8" stroke-dashoffset="50" class="stroke-indigo-600 transition-all duration-300 animate-spin"
                        style="animation: spinnerDash 2s ease-in-out infinite, spinnerRotate 2s linear infinite; transform-origin: 32px 32px;" />
                    <defs>
                        <linearGradient id="spinnerGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#4f46e5" />
                            <stop offset="50%" stop-color="#8b5cf6" />
                            <stop offset="100%" stop-color="#d946ef" />
                        </linearGradient>
                    </defs>
                </svg>
                <div class="absolute w-10 h-10 bg-white rounded-full flex items-center justify-center border border-zinc-200/50 shadow-3xs">
                    <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-5 h-5 object-contain" onerror="this.src='{{ asset('favicon.png') }}'">
                </div>
            </div>

            <h3 class="text-xs font-bold text-white tracking-tight mb-0.5 flex items-center gap-1.5">
                Processing Photo... <i class="ph ph-sparkle text-amber-400 animate-bounce"></i>
            </h3>
            <p class="text-[8px] font-bold text-zinc-400 uppercase tracking-widest mb-4">TraKerja Studio Engine</p>

            <div class="w-full bg-zinc-900 border border-zinc-800 rounded-xl p-3.5 shadow-xl relative overflow-hidden text-left">
                <div class="flex items-center gap-2">
                    <span id="loading-spinner" class="w-3 h-3 border-2 border-indigo-400 border-t-white rounded-full animate-spin shrink-0"></span>
                    <p class="text-[10px] font-semibold text-zinc-300 tracking-wide transition-opacity duration-300" id="loading-message">
                        Uploading portrait to secure processing node...
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateUrl(tab) {
            const url = new URL(window.location);
            url.searchParams.set('tab', tab);
            window.history.pushState({}, '', url);
        }

        var resumeInput   = document.getElementById('resume-file');
        var uploadArea    = document.getElementById('upload-area-analyzer');
        var fileSuccess   = document.getElementById('file-success-analyzer');
        var fileNameEl    = document.getElementById('file-name-analyzer');
        var fileSizeEl    = document.getElementById('file-size-analyzer');
        var removeFileBtn = document.getElementById('remove-file-analyzer');

        function showFileAnalyzer(file) {
            const mb = (file.size / (1024 * 1024)).toFixed(2);
            fileNameEl.textContent = file.name;
            fileSizeEl.textContent = mb + ' MB';
            fileSuccess.classList.remove('hidden');
            uploadArea.classList.add('hidden');
        }

        if (resumeInput) {
            resumeInput.addEventListener('change', e => {
                if (e.target.files[0]) showFileAnalyzer(e.target.files[0]);
            });
        }

        if (removeFileBtn) {
            removeFileBtn.addEventListener('click', () => {
                resumeInput.value = '';
                fileSuccess.classList.add('hidden');
                uploadArea.classList.remove('hidden');
            });
        }

        function handleDragOverAnalyzer(e) {
            e.preventDefault();
            uploadArea.classList.add('drag-active');
        }
        function handleDragLeaveAnalyzer(e) {
            uploadArea.classList.remove('drag-active');
        }
        function handleDropAnalyzer(e) {
            e.preventDefault();
            uploadArea.classList.remove('drag-active');
            const file = e.dataTransfer.files[0];
            if (file && (file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf'))) {
                const dt = new DataTransfer();
                dt.items.add(file);
                resumeInput.files = dt.files;
                showFileAnalyzer(file);
            }
        }

        var textarea      = document.getElementById('job_description');
        var charCountEl   = document.getElementById('char-count');
        var charIndicator = document.getElementById('char-indicator');
        var charBar       = document.getElementById('char-bar');
        var charHint      = document.getElementById('char-hint');

        if (textarea) {
            textarea.addEventListener('input', function () {
                const len = this.value.length;
                charCountEl.textContent = len;
                const pct = Math.min((len / 2500) * 100, 100);
                charBar.style.width = pct + '%';

                if (len < 50) {
                    charIndicator.className = 'w-1.5 h-1.5 rounded-full bg-rose-400';
                    charBar.className       = 'char-bar-fill h-full rounded-full bg-rose-400';
                    charHint.textContent    = 'Min 50 characters';
                    charHint.className      = 'text-[8px] font-bold text-rose-500';
                } else if (len < 300) {
                    charIndicator.className = 'w-1.5 h-1.5 rounded-full bg-amber-400';
                    charBar.className       = 'char-bar-fill h-full rounded-full bg-amber-400';
                    charHint.textContent    = 'Add details for better accuracy';
                    charHint.className      = 'text-[8px] font-bold text-amber-500';
                } else {
                    charIndicator.className = 'w-1.5 h-1.5 rounded-full bg-emerald-400';
                    charBar.className       = 'char-bar-fill h-full rounded-full bg-emerald-400';
                    charHint.textContent    = 'Looking good!';
                    charHint.className      = 'text-[8px] font-bold text-emerald-500';
                }
            });
        }

        var analyzeForm = document.getElementById('analyzeForm');
        if (analyzeForm) {
            analyzeForm.addEventListener('submit', function () {
                const btn = document.getElementById('submit-btn-analyzer');
                btn.disabled = true;
                btn.classList.add('opacity-70', 'cursor-not-allowed');
                document.getElementById('submit-text-analyzer').textContent = 'Analyzing…';
                document.getElementById('submit-icon-analyzer')?.classList.add('hidden');
                document.getElementById('loading-spinner-analyzer').classList.remove('hidden');
            });
        }

        window.copyGeneratedLetter = function() {
            const textEl = document.getElementById('text-content');
            if (!textEl) return;
            const textToCopy = textEl.textContent;
            navigator.clipboard.writeText(textToCopy).then(() => {
                const btn = document.getElementById('copy-btn');
                const btnText = document.getElementById('copy-text');
                const btnIcon = document.getElementById('copy-icon');

                if (btnText) btnText.textContent = "Copied!";
                if (btnIcon) btnIcon.className = "ph-bold ph-check text-[10px]";

                setTimeout(() => {
                    if (btnText) btnText.textContent = "Copy Letter";
                    if (btnIcon) btnIcon.className = "ph-bold ph-copy text-xs";
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        };

        var coverLetterForm = document.getElementById('coverLetterForm');
        if (coverLetterForm) {
            coverLetterForm.addEventListener('submit', function (e) {
                e.preventDefault();

                const btn = document.getElementById('submit-btn-cl');
                btn.disabled = true;
                btn.classList.add('opacity-70', 'cursor-not-allowed');
                document.getElementById('submit-text-cl').textContent = 'Generating…';
                document.getElementById('submit-icon-cl')?.classList.add('hidden');
                document.getElementById('loading-spinner-cl').classList.remove('hidden');

                const realContent = document.getElementById('real-content');
                const skeletonContent = document.getElementById('skeleton-content');
                const textContent = document.getElementById('text-content');
                const generatedActions = document.getElementById('generated-actions');

                realContent?.classList.add('hidden');
                generatedActions?.classList.add('hidden');
                skeletonContent?.classList.remove('hidden');

                const formData = new FormData(this);

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    btn.disabled = false;
                    btn.classList.remove('opacity-70', 'cursor-not-allowed');
                    document.getElementById('submit-text-cl').textContent = 'Generate Letter';
                    document.getElementById('submit-icon-cl')?.classList.remove('hidden');
                    document.getElementById('loading-spinner-cl').classList.add('hidden');
                    if (data.success && data.cover_letter) {
                        if (textContent) textContent.textContent = data.cover_letter;
                        skeletonContent?.classList.add('hidden');
                        realContent?.classList.remove('hidden');
                        generatedActions?.classList.remove('hidden');
                    } else {
                        alert(data.message || 'Error: Gagal memproses data AI.');
                    }
                })
                .catch(error => {
                    console.error('Error generating cover letter:', error);
                    btn.disabled = false;
                    btn.classList.remove('opacity-70', 'cursor-not-allowed');
                    document.getElementById('submit-text-cl').textContent = 'Generate Letter';
                    document.getElementById('submit-icon-cl')?.classList.remove('hidden');
                    document.getElementById('loading-spinner-cl').classList.add('hidden');
                    alert('Gagal menghubungi AI model. Mohon coba beberapa saat lagi.');
                });
            });
        }

        var photoInput = document.getElementById('photo-file');
        var uploadAreaPhoto = document.getElementById('upload-area-photo');
        var fileSuccessPhoto = document.getElementById('file-success-photo');
        var fileNameElPhoto = document.getElementById('file-name-photo');
        var fileSizeElPhoto = document.getElementById('file-size-photo');
        var removeFileBtnPhoto = document.getElementById('remove-file-photo');
        var imgPreview = document.getElementById('image-preview-photo');

        function showFilePhoto(file) {
            const mb = (file.size / (1024 * 1024)).toFixed(2);
            fileNameElPhoto.textContent = file.name;
            fileSizeElPhoto.textContent = mb + ' MB';
            fileSuccessPhoto.classList.remove('hidden');
            uploadAreaPhoto.classList.add('hidden');

            const reader = new FileReader();
            reader.onload = function(e) {
                imgPreview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }

        if (photoInput) {
            photoInput.addEventListener('change', e => {
                if (e.target.files[0]) showFilePhoto(e.target.files[0]);
            });
        }

        if (removeFileBtnPhoto) {
            removeFileBtnPhoto.addEventListener('click', () => {
                photoInput.value = '';
                fileSuccessPhoto.classList.add('hidden');
                uploadAreaPhoto.classList.remove('hidden');
                imgPreview.src = '#';
            });
        }

        function handleDragOverPhoto(e) {
            e.preventDefault();
            uploadAreaPhoto.classList.add('drag-active');
        }
        function handleDragLeavePhoto(e) {
            uploadAreaPhoto.classList.remove('drag-active');
        }
        function handleDropPhoto(e) {
            e.preventDefault();
            uploadAreaPhoto.classList.remove('drag-active');
            const file = e.dataTransfer.files[0];
            const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (file && (allowedTypes.includes(file.type) || file.name.toLowerCase().match(/\.(jpg|jpeg|png|webp)$/))) {
                const dt = new DataTransfer();
                dt.items.add(file);
                photoInput.files = dt.files;
                showFilePhoto(file);
            }
        }

        const loadingMessages = [
            "Uploading portrait to secure processing node...",
            "AI detecting facial landmarks...",
            "Analyzing studio light vectors...",
            "Matching and dressing up in professional suit...",
            "Enhancing portrait resolution and focus...",
            "Applying final photo studio grading...",
            "Almost ready, finalizing portrait output..."
        ];

        const overlay = document.getElementById('ai-loading-overlay');
        const photoForm = document.getElementById('photoForm');

        if (photoForm && overlay) {
            photoForm.addEventListener('submit', function () {
                const btn = document.getElementById('submit-btn-photo');
                btn.disabled = true;
                btn.classList.add('opacity-70', 'cursor-not-allowed');
                document.getElementById('submit-text-photo').textContent = 'Processing...';
                document.getElementById('submit-icon-photo')?.classList.add('hidden');
                document.getElementById('loading-spinner-photo').classList.remove('hidden');

                const card = document.getElementById('ai-loading-card');
                const msgEl = document.getElementById('loading-message');
                
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
                void overlay.offsetWidth;
                overlay.classList.remove('opacity-0');
                card.classList.remove('scale-98');

                let msgIndex = 0;
                setInterval(() => {
                    msgEl.style.opacity = '0';
                    setTimeout(() => {
                        msgIndex = (msgIndex + 1) % loadingMessages.length;
                        msgEl.textContent = loadingMessages[msgIndex];
                        msgEl.style.opacity = '1';
                    }, 300);
                }, 4000);
            });
        }

        window.generateOutreach = window.generateOutreach || function(e) {
            e.preventDefault();
            const btn = document.getElementById('submit-btn-outreach');
            const spinner = document.getElementById('loading-spinner-outreach');
            const text = document.getElementById('submit-text-outreach');
            const emptyState = document.getElementById('outreach-empty');
            const resultBox = document.getElementById('outreach-result');
            const textContent = document.getElementById('outreach-text-content');
            const copyBtn = document.getElementById('copy-outreach-btn');

            if (btn) btn.disabled = true;
            if (spinner) spinner.classList.remove('hidden');
            if (text) text.textContent = 'Generating...';

            const payload = {
                recruiter_name: document.getElementById('outreach_recruiter')?.value || '',
                target_company: document.getElementById('outreach_company')?.value || '',
                job_title: document.getElementById('outreach_job')?.value || '',
                channel: document.getElementById('outreach_channel')?.value || 'LinkedIn InMail',
                tone: document.getElementById('outreach_tone')?.value || 'Professional'
            };

            fetch('/ai-studio/outreach', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if (btn) btn.disabled = false;
                if (spinner) spinner.classList.add('hidden');
                if (text) text.textContent = 'Generate Outreach Message';

                if (data.success && data.message) {
                    if (emptyState) emptyState.classList.add('hidden');
                    if (resultBox) resultBox.classList.remove('hidden');
                    if (textContent) textContent.value = data.message;
                    if (copyBtn) {
                        copyBtn.classList.remove('hidden');
                        copyBtn.classList.add('flex');
                    }
                } else {
                    alert(data.message || 'Gagal menggenerasi pesan outreach.');
                }
            })
            .catch(err => {
                if (btn) btn.disabled = false;
                if (spinner) spinner.classList.add('hidden');
                if (text) text.textContent = 'Generate Outreach Message';
                alert('Gagal menghubungi server.');
            });
        };

        window.copyOutreachMessage = window.copyOutreachMessage || function() {
            const textContent = document.getElementById('outreach-text-content');
            if (!textContent) return;
            navigator.clipboard.writeText(textContent.value).then(() => {
                const btnText = document.getElementById('copy-outreach-text');
                const btnIcon = document.getElementById('copy-outreach-icon');
                if (btnText) btnText.textContent = 'Copied!';
                if (btnIcon) btnIcon.className = 'ph-bold ph-check text-xs';
                setTimeout(() => {
                    if (btnText) btnText.textContent = 'Copy Message';
                    if (btnIcon) btnIcon.className = 'ph-bold ph-copy text-xs';
                }, 2000);
            });
        };
    </script>
</x-app-layout>
