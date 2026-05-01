<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-2xl font-bold text-slate-900 leading-tight tracking-tight">
                AI <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#d983e4] via-purple-600 to-[#4e71c5]">Analyzer</span>
            </h1>
            <p class="text-xs text-slate-500 font-medium mt-1">Optimize your resume against specific job requirements using AI</p>
        </div>
    </x-slot>

    <div class="bg-gray-50 min-h-screen pb-20">
        <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                {{-- Main Analysis Form --}}
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                        {{-- Widget Header --}}
                        <div class="px-8 py-8 bg-slate-50/50 border-b border-slate-100">
                            <h2 class="text-xl font-black text-slate-900 tracking-tight">Analysis Configuration</h2>
                            <p class="text-sm text-slate-500 mt-1 font-medium">Provide your resume and the target job details to start the deep-scan process.</p>
                        </div>

                        <div class="p-8 sm:p-12">
                            <form action="{{ route('ai-analyzer.analyze') }}" method="POST" enctype="multipart/form-data" id="analyzeForm" class="space-y-10">
                                @csrf

                                @if ($errors->has('analyze_error'))
                                    <div class="bg-red-50 border border-red-100 rounded-xl p-4 flex items-center gap-3 text-red-700">
                                        <i class="ph-bold ph-warning-circle text-lg"></i>
                                        <p class="text-sm font-semibold">{{ $errors->first('analyze_error') }}</p>
                                    </div>
                                @endif

                                {{-- Section 1: Resume Upload --}}
                                <div class="space-y-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-900 font-bold text-sm">1</div>
                                        <h3 class="text-base font-bold text-slate-900">Upload Resume</h3>
                                    </div>
                                    
                                    <div id="upload-area" class="relative group border-2 border-dashed border-slate-200 rounded-2xl p-12 transition-all hover:border-indigo-500 hover:bg-slate-50/50 cursor-pointer text-center">
                                        <input id="resume" name="resume" type="file" accept=".pdf" class="absolute inset-0 opacity-0 cursor-pointer z-10" required>
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 group-hover:text-indigo-600 transition-colors mb-4">
                                                <i class="ph ph-file-arrow-up text-3xl"></i>
                                            </div>
                                            <p class="text-sm font-bold text-slate-900 mb-1">Click to upload or drag and drop</p>
                                            <p class="text-xs text-slate-500">PDF format only (Max. 10MB)</p>
                                        </div>
                                    </div>

                                    {{-- Selected File State --}}
                                    <div id="file-success" class="hidden">
                                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 flex items-center justify-between">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-indigo-600 border border-slate-100 shadow-sm">
                                                    <i class="ph-fill ph-file-pdf text-2xl"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <p id="file-name" class="text-sm font-bold text-slate-900 truncate max-w-[200px] sm:max-w-md"></p>
                                                    <p id="file-size" class="text-[11px] text-slate-500 font-medium"></p>
                                                </div>
                                            </div>
                                            <button type="button" id="remove-file" class="p-2 text-slate-400 hover:text-red-500 transition-colors">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- Section 2: Job Description --}}
                                <div class="space-y-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-900 font-bold text-sm">2</div>
                                        <h3 class="text-base font-bold text-slate-900">Target Job Description</h3>
                                    </div>
                                    
                                    <div class="relative">
                                        <textarea id="job_description" name="job_description" rows="12" 
                                                  class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium text-slate-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-all outline-none resize-none leading-relaxed" 
                                                  placeholder="Paste the requirements and responsibilities of the job you're targeting..." required minlength="50" maxlength="2500">{{ old('job_description') }}</textarea>
                                        <div class="absolute bottom-4 right-5 flex items-center gap-2">
                                            <div id="char-indicator" class="w-2 h-2 rounded-full bg-slate-200"></div>
                                            <span class="text-[11px] font-bold text-slate-400">
                                                <span id="char-count">0</span> / 2500 characters
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="flex items-center justify-end gap-4 pt-4">
                                    <a href="{{ route('tracker') }}" class="px-6 py-3 text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors">Cancel</a>
                                    <button type="submit" id="submit-btn" class="magnetic-btn px-10 py-3.5 bg-indigo-600 text-white rounded-xl font-bold text-sm hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 active:scale-95 flex items-center gap-3">
                                        <i id="loading-spinner" class="ph-bold ph-spinner animate-spin hidden"></i>
                                        <span id="submit-text">Run AI Analysis</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Sidebar: Guidance --}}
                <div class="lg:col-span-4 space-y-8">
                    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
                        <h4 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-6">Instructions</h4>
                        <div class="space-y-6">
                            <div class="flex gap-4">
                                <div class="w-6 h-6 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 text-xs font-bold shrink-0">1</div>
                                <p class="text-sm text-slate-600 leading-relaxed">Upload your current resume in **PDF format** for the AI to parse your skills and experience.</p>
                            </div>
                            <div class="flex gap-4">
                                <div class="w-6 h-6 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 text-xs font-bold shrink-0">2</div>
                                <p class="text-sm text-slate-600 leading-relaxed">Provide the **complete job description** including requirements to ensure accurate matching.</p>
                            </div>
                            <div class="flex gap-4">
                                <div class="w-6 h-6 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 text-xs font-bold shrink-0">3</div>
                                <p class="text-sm text-slate-600 leading-relaxed">Our AI will generate a **Match Score** and suggest specific improvements for your CV.</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-900 rounded-3xl p-8 text-white relative overflow-hidden">
                        <div class="relative z-10">
                            <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center mb-6">
                                <i class="ph-bold ph-shield-check text-xl text-indigo-400"></i>
                            </div>
                            <h4 class="text-base font-bold mb-3 tracking-tight">Privacy & Security</h4>
                            <p class="text-xs text-slate-400 leading-relaxed">
                                Your data is processed securely and is only used to provide your analysis. Resumes are not stored permanently.
                            </p>
                        </div>
                        <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-indigo-600/10 rounded-full blur-2xl"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const resumeInput = document.getElementById('resume');
        const uploadArea = document.getElementById('upload-area');
        const fileSuccess = document.getElementById('file-success');
        const fileName = document.getElementById('file-name');
        const fileSize = document.getElementById('file-size');
        const removeFileBtn = document.getElementById('remove-file');
        
        resumeInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
                fileName.textContent = file.name;
                fileSize.textContent = sizeInMB + ' MB';
                fileSuccess.classList.remove('hidden');
                uploadArea.classList.add('hidden');
            }
        });
        
        removeFileBtn.addEventListener('click', function() {
            resumeInput.value = '';
            fileSuccess.classList.add('hidden');
            uploadArea.classList.remove('hidden');
        });

        const jobDescTextarea = document.getElementById('job_description');
        const charCount = document.getElementById('char-count');
        const charIndicator = document.getElementById('char-indicator');
        
        jobDescTextarea.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = length;
            
            if (length < 50) {
                charIndicator.className = 'w-2 h-2 rounded-full bg-red-400';
            } else if (length < 500) {
                charIndicator.className = 'w-2 h-2 rounded-full bg-amber-400';
            } else {
                charIndicator.className = 'w-2 h-2 rounded-full bg-emerald-400';
            }
        });

        document.getElementById('analyzeForm').addEventListener('submit', function() {
            const btn = document.getElementById('submit-btn');
            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-not-allowed');
            document.getElementById('submit-text').textContent = 'Analyzing...';
            document.getElementById('loading-spinner').classList.remove('hidden');
        });
    </script>
</x-app-layout>
