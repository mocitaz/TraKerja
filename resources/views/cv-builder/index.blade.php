<x-app-layout>
    <x-slot name="header">
        <!-- Ignored layout slot, header is handled inline inside template container for premium consistency -->
    </x-slot>

    <div class="bg-[#fafafa] min-h-screen pb-16" x-data="{ activeTab: 'experiences', previewOpen: false, importModalOpen: false }">
        <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            
            <!-- Premium Notion-Inspired Page Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-zinc-200/50 pb-4 mb-5">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-zinc-100 border border-zinc-200/60 rounded-lg flex items-center justify-center text-zinc-500 shrink-0 shadow-2xs">
                        <i class="ph ph-identification-card text-base"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-sm font-bold text-zinc-800 tracking-tight">CV Builder</h1>
                            <span class="px-1.5 py-0.5 bg-primary-50 text-zinc-800 text-[9px] font-black uppercase tracking-wider rounded border border-primary-100/60">Portfolio</span>
                        </div>
                        <p class="text-[11px] text-zinc-400 mt-0.5">Craft, organize, and export your professional resume and credentials.</p>
                    </div>
                </div>

                <!-- Header Actions Grid -->
                <div class="flex items-center gap-2 w-full md:w-auto overflow-x-auto no-scrollbar py-1 shrink-0">
                    <button type="button" @click="importModalOpen = true" id="import-pdf-btn" class="px-3 py-1.5 bg-white text-zinc-700 border border-zinc-200 rounded-md hover:bg-zinc-50 text-[11px] font-bold shadow-3xs transition-all duration-200 flex items-center gap-1.5 shrink-0 focus:outline-none">
                        <i class="ph ph-file-arrow-up text-xs"></i>
                        <span id="import-pdf-text">Import PDF</span>
                    </button>
                    <button @click="$dispatch('openPublishModal')" class="px-3 py-1.5 bg-white text-zinc-700 border border-zinc-200 rounded-md hover:bg-zinc-50 text-[11px] font-bold shadow-3xs transition-all duration-200 flex items-center gap-1.5 shrink-0 focus:outline-none">
                        <i class="ph ph-globe text-xs"></i>
                        <span>Publish Site</span>
                    </button>
                    <button @click="previewOpen = true" class="px-3 py-1.5 bg-white text-zinc-700 border border-zinc-200 rounded-md hover:bg-zinc-50 text-[11px] font-bold shadow-3xs transition-all duration-200 flex items-center gap-1.5 shrink-0 focus:outline-none">
                        <i class="ph ph-eye text-xs"></i>
                        <span>Quick Preview</span>
                    </button>
                    <a href="{{ route('cv.generator') }}" class="px-3 py-1.5 bg-primary-50 text-zinc-800 border border-primary-200/60 hover:bg-primary-100 text-[11px] font-bold rounded-md shadow-3xs transition-all duration-200 flex items-center gap-1.5 shrink-0 focus:outline-none">
                        <i class="ph ph-magic-wand text-xs"></i>
                        <span>Generate CV</span>
                    </a>
                    @if(!auth()->user()->is_premium)
                        <a href="{{ route('payment.premium') }}" class="px-3 py-1.5 bg-white text-amber-600 border border-amber-250 hover:bg-amber-50/70 text-[11px] font-bold rounded-md shadow-3xs transition-all duration-200 flex items-center gap-1.5 shrink-0 focus:outline-none">
                            <i class="ph ph-crown text-xs"></i>
                            <span>Upgrade</span>
                        </a>
                    @endif
                </div>
            </div>

            @if(session('success'))
                <div class="mb-5 p-3 bg-emerald-50 border border-emerald-150/50 rounded-md flex items-center gap-2.5 text-emerald-700">
                    <i class="ph ph-check-circle text-base"></i>
                    <p class="text-[10px] font-bold uppercase tracking-wider">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 p-3 bg-rose-50 border border-rose-150/50 rounded-md flex items-center gap-2.5 text-rose-700">
                    <i class="ph ph-warning-circle text-base"></i>
                    <p class="text-[10px] font-bold uppercase tracking-wider">{{ session('error') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">
                {{-- Left Sidebar: Navigation & Strength --}}
                <div class="lg:col-span-4 space-y-5">
                    {{-- Profile Strength Card --}}
                    <div class="bg-white rounded-lg border border-zinc-200/60 p-4 shadow-3xs relative overflow-hidden">
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest leading-none">Profile Strength</h3>
                                <div class="w-6 h-6 bg-zinc-50 border border-zinc-200/50 rounded flex items-center justify-center text-zinc-400 shadow-3xs shrink-0">
                                    <i class="ph ph-chart-pie-slice text-xs"></i>
                                </div>
                            </div>
                            
                            @php
                                $total = 6;
                                $filled = 0;
                                if ($profile) $filled++;
                                if ($experiences->count() > 0) $filled++;
                                if ($educations->count() > 0) $filled++;
                                if ($skills->count() > 0) $filled++;
                                if ($organizations->count() > 0) $filled++;
                                if ($projects->count() > 0) $filled++;
                                $percentage = ($filled / $total) * 100;
                            @endphp

                            <div class="relative w-28 h-28 mx-auto mb-3">
                                <svg class="w-full h-full transform -rotate-90">
                                    <circle cx="56" cy="56" r="46" stroke="currentColor" stroke-width="7" fill="transparent" class="text-zinc-50" />
                                    <circle cx="56" cy="56" r="46" stroke="currentColor" stroke-width="7" fill="transparent" 
                                        stroke-dasharray="289" 
                                        stroke-dashoffset="{{ 289 - (289 * $percentage / 100) }}" 
                                        class="text-primary-500 /* [BRAND_PRIMARY] */ transition-all duration-1000 ease-out" 
                                        stroke-linecap="round" />
                                </svg>
                                <div class="absolute inset-0 flex flex-col items-center justify-center">
                                    <span class="text-xl font-bold text-zinc-800 leading-none tracking-tight">{{ number_format($percentage, 0) }}<span class="text-xs font-semibold text-zinc-400">%</span></span>
                                    <span class="text-[8px] font-bold text-zinc-400 uppercase tracking-widest mt-0.5">Optimized</span>
                                </div>
                            </div>

                            <div class="bg-zinc-50/50 rounded border border-zinc-200/60 p-2.5 text-center">
                                <p class="text-[10px] font-medium text-zinc-500 leading-normal">
                                    {{ $filled < $total ? 'Complete ' . ($total - $filled) . ' more sections to reach peak performance.' : 'Your profile is 100% complete and perfectly optimized.' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Vertical Section Navigation --}}
                    <div class="bg-white rounded-lg border border-zinc-200/60 p-1 shadow-3xs">
                        <nav class="flex lg:flex-col overflow-x-auto lg:overflow-x-visible no-scrollbar p-0.5 gap-1">
                            @php
                                $tabs = [
                                    ['key' => 'experiences', 'label' => 'Experience', 'icon' => 'ph-briefcase', 'count' => $experiences->count()],
                                    ['key' => 'education', 'label' => 'Education', 'icon' => 'ph-graduation-cap', 'count' => $educations->count()],
                                    ['key' => 'skills', 'label' => 'Skills', 'icon' => 'ph-star', 'count' => $skills->count()],
                                    ['key' => 'organizations', 'label' => 'Organizations', 'icon' => 'ph-users-three', 'count' => $organizations->count()],
                                    ['key' => 'achievements', 'label' => 'Achievements', 'icon' => 'ph-trophy', 'count' => $achievements->count()],
                                    ['key' => 'projects', 'label' => 'Projects', 'icon' => 'ph-code', 'count' => $projects->count()],
                                ];
                            @endphp

                            @foreach($tabs as $tab)
                            <button @click="activeTab = '{{ $tab['key'] }}'" 
                                    :class="activeTab === '{{ $tab['key'] }}' ? 'bg-zinc-900 text-white' : 'text-zinc-600 hover:bg-zinc-50'"
                                    class="flex-shrink-0 flex items-center justify-between px-3.5 py-2 rounded-md text-[11px] font-bold transition-all duration-150 group focus:outline-none w-full">
                                <div class="flex items-center gap-2.5">
                                    <i class="ph {{ $tab['icon'] }} text-sm"></i>
                                    <span class="tracking-tight">{{ $tab['label'] }}</span>
                                </div>
                                @if($tab['count'] > 0)
                                    <div :class="activeTab === '{{ $tab['key'] }}' ? 'bg-white/20 text-white' : 'bg-emerald-50 text-emerald-600 border border-emerald-100/50'" class="w-4 h-4 rounded-md flex items-center justify-center text-[9px] shrink-0">
                                        <i class="ph ph-check"></i>
                                    </div>
                                @else
                                    <div class="w-4 h-4 border border-zinc-200/80 rounded-md shrink-0"></div>
                                @endif
                            </button>
                            @endforeach
                        </nav>
                    </div>
                </div>

                {{-- Right Content: Form Panel --}}
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-lg border border-zinc-200/60 overflow-hidden min-h-full flex flex-col shadow-3xs">
                        
                        <div class="p-4 sm:p-5 flex-1">
                            <div x-show="activeTab === 'experiences'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                @livewire('cv-builder.experience-form')
                            </div>

                            <div x-show="activeTab === 'education'" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                @livewire('cv-builder.education-form')
                            </div>

                            <div x-show="activeTab === 'skills'" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                @livewire('cv-builder.skills-form')
                            </div>

                            <div x-show="activeTab === 'organizations'" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                @livewire('cv-builder.organization-form')
                            </div>

                            <div x-show="activeTab === 'achievements'" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                @livewire('cv-builder.achievement-form')
                            </div>

                            <div x-show="activeTab === 'projects'" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                @livewire('cv-builder.project-form')
                            </div>
                        </div>

                        {{-- Precision Footer --}}
                        <div class="px-4 py-3 bg-zinc-50/30 border-t border-zinc-150/60 flex items-center gap-3">
                            <div class="w-7 h-7 bg-white border border-zinc-200/50 rounded shadow-3xs flex items-center justify-center text-primary-500 shrink-0">
                                <i class="ph ph-lightbulb text-base"></i>
                            </div>
                            <p class="text-[10px] text-zinc-500 font-medium leading-relaxed">
                                <span class="font-bold text-zinc-700">Expert Advice:</span> Quantify your achievements with numbers to make your CV stand out.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Live Preview Modal --}}
        <template x-teleport="body">
            <div x-show="previewOpen" 
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
                 style="display: none;"
                 x-cloak>
                
                {{-- Backdrop --}}
                <div class="absolute inset-0 bg-zinc-950/40 backdrop-blur-xs" @click="previewOpen = false"></div>

                {{-- Modal Container --}}
                <div class="relative w-full h-full max-w-4xl bg-white rounded-lg shadow-xl overflow-hidden border border-zinc-200 flex flex-col z-10 text-left"
                     x-show="previewOpen"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 scale-98 translateY(6px)"
                     x-transition:enter-end="opacity-100 scale-100 translateY(0)">
                    
                    {{-- Modal Header --}}
                    <div class="px-4 py-3.5 border-b border-zinc-150/60 flex items-center justify-between bg-white shrink-0">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500 shadow-3xs shrink-0">
                                <i class="ph ph-eye text-sm"></i>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-zinc-800 tracking-tight">Live CV Preview</h3>
                                <p class="text-[8px] font-bold text-zinc-400 uppercase tracking-widest mt-0.5">Real-time visualization</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('cv.generator') }}" class="px-3 py-1.5 bg-zinc-50 border border-zinc-200 rounded-md text-[10px] font-bold text-zinc-600 hover:bg-zinc-100 transition-colors uppercase tracking-wider focus:outline-none">
                                Change Template
                            </a>
                            <button @click="previewOpen = false" class="w-7 h-7 flex items-center justify-center rounded hover:bg-zinc-50 text-zinc-400 hover:text-zinc-800 transition-colors focus:outline-none">
                                <i class="ph ph-x text-base"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Modal Content: The Iframe --}}
                    <div class="flex-1 bg-zinc-100/30 p-2.5 relative overflow-hidden flex">
                        <div class="w-full h-full rounded-md bg-white overflow-hidden relative border border-zinc-200/60 shadow-3xs flex">
                            <iframe id="cvPreviewFrame" 
                                    :src="previewOpen ? '{{ route('cv-builder.preview.get') }}' : 'about:blank'" 
                                    class="w-full h-full border-none"
                                    loading="lazy"></iframe>
                            
                            {{-- Loading Overlay for iframe --}}
                            <div class="absolute inset-0 bg-white flex flex-col items-center justify-center gap-3 z-10 transition-opacity duration-300"
                                 id="iframeLoader"
                                 x-init="const frame = document.getElementById('cvPreviewFrame'); 
                                         if(frame) { frame.onload = () => { document.getElementById('iframeLoader').style.opacity = '0'; setTimeout(() => { document.getElementById('iframeLoader').style.display = 'none'; }, 300); } }">
                                 <div class="w-7 h-7 border-2 border-primary-100 border-t-primary-600 rounded-full animate-spin"></div>
                                 <p class="text-[8px] font-bold text-zinc-400 uppercase tracking-widest animate-pulse">Rendering Resume...</p>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="px-4 py-3 border-t border-zinc-150/60 bg-zinc-50/20 flex flex-col sm:flex-row items-center justify-between gap-3 shrink-0">
                        <p class="text-[8.5px] text-zinc-400 font-bold uppercase tracking-wider text-center sm:text-left leading-none">
                            <i class="ph ph-info mr-1 text-xs"></i> Data syncs automatically on save
                        </p>
                        <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                            <button @click="document.getElementById('cvPreviewFrame').contentWindow.window.print()" 
                                    class="flex-1 sm:flex-none px-3.5 py-1.5 bg-white border border-zinc-200 text-zinc-700 rounded-md text-[11px] font-bold hover:bg-zinc-50 transition-colors flex items-center justify-center gap-1.5 focus:outline-none">
                                <i class="ph ph-printer text-xs"></i>
                                <span>Print CV</span>
                            </button>
                            <a href="{{ route('cv.generator') }}" class="flex-1 sm:flex-none px-3.5 py-1.5 bg-zinc-900 text-white rounded-md text-[11px] font-bold hover:bg-zinc-800 transition-colors flex items-center justify-center gap-1.5 shadow-3xs focus:outline-none">
                                <i class="ph ph-download-simple text-xs"></i>
                                <span>Export Now</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </template>


    <!-- Import CV Modal -->
    <div x-show="importModalOpen"
         class="fixed inset-0 z-[150] flex items-center justify-center overflow-y-auto"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak>
         
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-zinc-950/40 backdrop-blur-xs" @click="importModalOpen = false"></div>

        <!-- Modal Content Card -->
        <div class="bg-white border border-zinc-200/80 rounded-xl shadow-xl w-full max-w-md mx-4 overflow-hidden transform transition-all relative z-10 flex flex-col"
             x-show="importModalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2">

            <!-- Modal Header -->
            <div class="px-5 py-4 border-b border-zinc-150/60 bg-[#ffffff] flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-primary-50 border border-primary-200/60 flex items-center justify-center text-zinc-800">
                        <i class="ph ph-file-arrow-up text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-zinc-800 tracking-tight leading-none mb-0.5">Import CV PDF</h3>
                        <p class="text-[9px] text-zinc-400 font-bold uppercase tracking-wider leading-none">AI Resume ingestion</p>
                    </div>
                </div>
                <button type="button" @click="importModalOpen = false" class="w-7 h-7 rounded-md hover:bg-zinc-100 text-zinc-400 hover:text-zinc-700 flex items-center justify-center transition-colors">
                    <i class="ph ph-x text-sm"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <!-- Dropzone Area -->
                <div id="dropzone"
                     onclick="document.getElementById('import-pdf-input').click()"
                     class="border-2 border-dashed border-zinc-200 hover:border-primary-400/80 hover:bg-zinc-50/50 rounded-xl p-8 flex flex-col items-center justify-center text-center cursor-pointer transition-all duration-200 group">
                    
                    <div class="w-12 h-12 bg-zinc-50 group-hover:bg-primary-50 border border-zinc-150 group-hover:border-primary-200 rounded-xl flex items-center justify-center text-zinc-450 group-hover:text-primary-650 shadow-3xs mb-3 transition-colors">
                        <i class="ph ph-file-pdf text-xl"></i>
                    </div>
                    
                    <h4 class="text-xs font-bold text-zinc-700 leading-tight">Drag & Drop CV PDF Anda di sini</h4>
                    <p class="text-[10px] text-zinc-400 font-medium mt-1">atau <span class="text-primary-650 font-bold group-hover:underline">Pilih berkas dari komputer</span></p>
                    
                    <div class="mt-4 pt-3.5 border-t border-zinc-100/80 w-full flex items-center justify-center gap-4 text-[9px] font-semibold text-zinc-400 uppercase tracking-wider">
                        <span>PDF Format Only</span>
                        <span class="text-zinc-300">•</span>
                        <span>Max 10 MB</span>
                    </div>
                </div>

                <!-- Hidden Input -->
                <input type="file" id="import-pdf-input" accept=".pdf" class="hidden" onchange="uploadPdfResume(this)">

                <!-- Progress State (Hidden by default) -->
                <div id="upload-progress-state" class="hidden mt-4 space-y-3.5">
                    <div class="bg-zinc-50 border border-zinc-200/60 rounded-lg p-3.5 flex items-center gap-3">
                        <div class="w-8 h-8 rounded bg-primary-50 text-primary-650 flex items-center justify-center shrink-0 border border-primary-100">
                            <i class="ph ph-spinner animate-spin text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-xs font-bold text-zinc-800 truncate leading-none mb-0.5" id="uploading-filename">resume.pdf</h4>
                            <p class="text-[10px] font-medium text-zinc-450 leading-none">Mengekstrak data menggunakan AI Parser...</p>
                        </div>
                    </div>
                    <!-- Animated Bar -->
                    <div class="w-full h-1 bg-zinc-150 rounded-full overflow-hidden">
                        <div class="h-full bg-primary-650 rounded-full animate-shimmer" style="width: 100%; background-image: linear-gradient(to right, #4f46e5, #8b5cf6, #4f46e5); background-size: 200% 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .animate-shimmer {
            animation: shimmer 1.5s infinite linear;
        }
    </style>
    @livewire('cv-builder.portfolio-publish-modal')

    <script>
    window.uploadPdfResume = window.uploadPdfResume || function(input) {
        if (!input.files || !input.files[0]) return;
        const file = input.files[0];
        
        const dropzone = document.getElementById('dropzone');
        const progressState = document.getElementById('upload-progress-state');
        const filenameLabel = document.getElementById('uploading-filename');

        if (filenameLabel) filenameLabel.textContent = file.name;
        if (dropzone) dropzone.classList.add('hidden');
        if (progressState) progressState.classList.remove('hidden');

        const formData = new FormData();
        formData.append('resume', file);

        fetch('/cv/import-pdf', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(async res => {
            if (!res.ok) {
                if (res.status === 422) {
                    const errData = await res.json();
                    throw new Error(errData.message || 'Validation error');
                }
                const errText = await res.text();
                throw new Error('Server error (' + res.status + '): ' + errText.substring(0, 100));
            }
            return res.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.reload();
            } else {
                alert(data.message || 'Gagal mengimpor file PDF.');
                if (dropzone) dropzone.classList.remove('hidden');
                if (progressState) progressState.classList.add('hidden');
            }
        })
        .catch(err => {
            console.error('Import PDF Error:', err);
            alert('Gagal mengimpor CV: ' + err.message);
            if (dropzone) dropzone.classList.remove('hidden');
            if (progressState) progressState.classList.add('hidden');
        });
    };

    document.addEventListener('DOMContentLoaded', () => {
        const dropzone = document.getElementById('dropzone');
        if (dropzone) {
            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    dropzone.classList.add('border-primary-400', 'bg-zinc-50/50');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    dropzone.classList.remove('border-primary-400', 'bg-zinc-50/50');
                }, false);
            });

            dropzone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                if (files && files[0]) {
                    const input = document.getElementById('import-pdf-input');
                    input.files = files;
                    uploadPdfResume(input);
                }
            }, false);
        }
    });
    </script>
</x-app-layout>
