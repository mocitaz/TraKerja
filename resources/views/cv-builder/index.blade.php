<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-2xl font-black text-slate-900 leading-tight tracking-tight">
                CV <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#d983e4] via-primary-600 to-[#4e71c5]">Builder</span>
            </h1>
            <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest mt-1">Craft your professional identity</p>
        </div>
    </x-slot>

    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <div class="bg-[#f8fafc] min-h-screen pb-20 relative overflow-hidden" x-data="{ activeTab: 'experiences', previewOpen: false }">
        {{-- Decorative Background Elements --}}
        <div class="absolute top-0 left-0 w-full h-64 bg-gradient-to-b from-primary-50/30 to-transparent -z-10"></div>
        <div class="absolute top-40 -right-24 w-96 h-96 bg-primary-200/10 blur-[120px] rounded-full -z-10"></div>
        <div class="absolute bottom-20 -left-24 w-80 h-80 bg-purple-200/10 blur-[120px] rounded-full -z-10"></div>

        <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            
            {{-- Header Action Bar: Re-aligned for precision --}}
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-10">

                <div class="flex items-center gap-3 w-full lg:w-auto">
                    <button @click="$dispatch('openPublishModal')" class="flex-1 lg:flex-none px-6 py-4 bg-primary-50 text-primary-600 border border-primary-100 rounded-2xl font-black text-[10px] uppercase tracking-[2px] hover:bg-primary-100 transition-all flex items-center justify-center gap-2 shadow-sm active:scale-95">
                        <i class="ph-bold ph-globe text-base"></i>
                        Publish Site
                    </button>
                    <button @click="previewOpen = true" class="flex-1 lg:flex-none px-6 py-4 bg-white text-slate-900 border border-slate-200 rounded-2xl font-black text-[10px] uppercase tracking-[2px] hover:bg-slate-50 transition-all flex items-center justify-center gap-2 shadow-sm active:scale-95">
                        <i class="ph-bold ph-eye text-base"></i>
                        Quick Preview
                    </button>
                    <a href="{{ route('cv.generator') }}" class="flex-1 lg:flex-none group relative px-8 py-4 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-[2px] hover:bg-primary-600 transition-all shadow-xl shadow-slate-200 flex items-center justify-center gap-3 active:scale-95">
                        <i class="ph-bold ph-magic-wand text-base"></i>
                        Generate CV
                    </a>
                    @if(!auth()->user()->is_premium)
                        <a href="{{ route('payment.premium') }}" class="px-6 py-4 bg-white text-amber-600 border border-amber-200 rounded-2xl font-black text-[10px] uppercase tracking-[2px] hover:bg-amber-50 transition-all flex items-center justify-center gap-2 shadow-sm">
                            <i class="ph-bold ph-crown text-base"></i>
                            Upgrade
                        </a>
                    @endif
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 text-emerald-700 animate-fade-in-down">
                    <i class="ph-bold ph-check-circle text-xl"></i>
                    <p class="text-xs font-bold uppercase tracking-wider">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-center gap-3 text-rose-700 animate-fade-in-down">
                    <i class="ph-bold ph-warning-circle text-xl"></i>
                    <p class="text-xs font-bold uppercase tracking-wider">{{ session('error') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                {{-- Left Sidebar: Navigation & Strength --}}
                <div class="lg:col-span-4 space-y-6">
                    {{-- Profile Strength Card: Absolute Precision Padding --}}
                    <div class="bg-white rounded-[2.5rem] border border-slate-200/60 p-8 shadow-sm overflow-hidden relative h-fit">
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-8">
                                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[2px]">Profile Strength</h3>
                                <div class="w-8 h-8 bg-primary-50 rounded-lg flex items-center justify-center text-primary-600">
                                    <i class="ph-bold ph-chart-pie-slice"></i>
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

                            <div class="relative w-40 h-40 mx-auto mb-8">
                                <svg class="w-full h-full transform -rotate-90">
                                    <circle cx="80" cy="80" r="70" stroke="currentColor" stroke-width="14" fill="transparent" class="text-slate-50" />
                                    <circle cx="80" cy="80" r="70" stroke="currentColor" stroke-width="14" fill="transparent" 
                                        stroke-dasharray="439.8" 
                                        stroke-dashoffset="{{ 439.8 - (439.8 * $percentage / 100) }}" 
                                        class="text-primary-600 transition-all duration-1000 ease-out" 
                                        stroke-linecap="round" />
                                </svg>
                                <div class="absolute inset-0 flex flex-col items-center justify-center">
                                    <span class="text-3xl font-black text-slate-900 leading-none">{{ number_format($percentage, 0) }}<span class="text-sm font-bold text-slate-400">%</span></span>
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-1">Optimized</span>
                                </div>
                            </div>

                            <div class="bg-slate-50 rounded-2xl p-4 text-center">
                                <p class="text-[11px] font-bold text-slate-600 leading-relaxed">
                                    {{ $filled < $total ? 'Complete ' . ($total - $filled) . ' more sections to reach peak performance.' : 'Your profile is 100% complete and perfectly optimized.' }}
                                </p>
                            </div>
                        </div>
                        {{-- Background Accent --}}
                        <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-primary-100/50 rounded-full blur-3xl"></div>
                    </div>

                    {{-- Vertical Section Navigation --}}
                    <div class="bg-white rounded-[2.5rem] border border-slate-200/60 p-3 shadow-sm h-fit">
                        <nav class="space-y-1">
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
                                    :class="activeTab === '{{ $tab['key'] }}' ? 'bg-primary-600 text-white shadow-lg shadow-primary-100 border-primary-600' : 'text-slate-500 hover:bg-slate-50 border-transparent'"
                                    class="w-full flex items-center justify-between px-6 py-4 rounded-2xl border transition-all duration-300 group">
                                <div class="flex items-center gap-4">
                                    <i class="ph-duotone {{ $tab['icon'] }} text-xl transition-transform duration-300 group-hover:scale-110"></i>
                                    <span class="text-xs font-black uppercase tracking-widest">{{ $tab['label'] }}</span>
                                </div>
                                @if($tab['count'] > 0)
                                    <div :class="activeTab === '{{ $tab['key'] }}' ? 'bg-white/20' : 'bg-emerald-50 text-emerald-600'" class="w-5 h-5 rounded-md flex items-center justify-center">
                                        <i class="ph-bold ph-check text-[10px]"></i>
                                    </div>
                                @else
                                    <div class="w-5 h-5 border-2 border-slate-100 rounded-md"></div>
                                @endif
                            </button>
                            @endforeach
                        </nav>
                    </div>
                </div>

                {{-- Right Content: Form Panel --}}
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200/60 overflow-hidden min-h-full flex flex-col relative">
                        {{-- Purple Header Removed for Cleanliness & Precision --}}
                        
                        {{-- Absolute Precision Padding: p-8 matching sidebar exactly --}}
                        <div class="p-8 flex-1">
                            <div x-show="activeTab === 'experiences'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                                @livewire('cv-builder.experience-form')
                            </div>

                            <div x-show="activeTab === 'education'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                                @livewire('cv-builder.education-form')
                            </div>

                            <div x-show="activeTab === 'skills'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                                @livewire('cv-builder.skills-form')
                            </div>

                            <div x-show="activeTab === 'organizations'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                                @livewire('cv-builder.organization-form')
                            </div>

                            <div x-show="activeTab === 'achievements'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                                @livewire('cv-builder.achievement-form')
                            </div>

                            <div x-show="activeTab === 'projects'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                                @livewire('cv-builder.project-form')
                            </div>
                        </div>

                        {{-- Precision Footer --}}
                        <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100 flex items-center gap-4 group">
                            <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-primary-500 shrink-0 group-hover:rotate-12 transition-transform">
                                <i class="ph-duotone ph-lightbulb text-xl"></i>
                            </div>
                            <p class="text-[11px] text-slate-500 font-medium leading-relaxed">
                                <span class="font-black text-slate-700">Expert Advice:</span> Quantify your achievements with numbers to make your CV stand out.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Live Preview Modal --}}
        <template x-teleport="body">
            <div x-show="previewOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-10"
                 style="display: none;">
                
                {{-- Backdrop --}}
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-xl" @click="previewOpen = false"></div>

                {{-- Modal Container --}}
                <div class="relative w-full h-full max-w-5xl bg-white rounded-[2.5rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.2)] overflow-hidden border border-slate-100 flex flex-col"
                     x-show="previewOpen"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-10"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                    
                    {{-- Modal Header --}}
                    <div class="px-8 py-5 border-b border-slate-50 flex items-center justify-between bg-white shrink-0">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center text-primary-600">
                                <i class="ph-fill ph-eye text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-900 tracking-tight uppercase">Live CV Preview</h3>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Real-time visualization</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex p-1 bg-slate-50 rounded-xl border border-slate-100">
                                <a href="{{ route('cv.generator') }}" class="px-4 py-2 text-[9px] font-black uppercase tracking-widest text-slate-500 hover:text-primary-600 transition-colors">
                                    Change Template
                                </a>
                            </div>
                            <button @click="previewOpen = false" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all border border-transparent hover:border-rose-100">
                                <i class="ph-bold ph-x text-lg"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Modal Content: The Iframe --}}
                    <div class="flex-1 bg-slate-100/50 p-4 relative overflow-hidden">
                        <div class="w-full h-full rounded-2xl bg-white shadow-inner overflow-hidden relative">
                            <iframe id="cvPreviewFrame" 
                                    :src="previewOpen ? '{{ route('cv-builder.preview.get') }}' : 'about:blank'" 
                                    class="w-full h-full border-none"
                                    loading="lazy"></iframe>
                            
                            {{-- Loading Overlay for iframe --}}
                            <div class="absolute inset-0 bg-white flex flex-col items-center justify-center gap-4 z-10 transition-opacity duration-500"
                                 id="iframeLoader"
                                 x-init="const frame = document.getElementById('cvPreviewFrame'); 
                                         if(frame) { frame.onload = () => { document.getElementById('iframeLoader').style.opacity = '0'; setTimeout(() => { document.getElementById('iframeLoader').style.display = 'none'; }, 500); } }">
                                <div class="w-12 h-12 border-4 border-primary-100 border-t-primary-600 rounded-full animate-spin"></div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest animate-pulse">Rendering Resume...</p>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="px-8 py-5 border-t border-slate-50 bg-slate-50/50 flex items-center justify-between shrink-0">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                            <i class="ph-fill ph-info mr-1"></i> Data syncs automatically on save
                        </p>
                        <div class="flex items-center gap-3">
                            <button @click="document.getElementById('cvPreviewFrame').contentWindow.window.print()" 
                                    class="px-6 py-3 bg-white border border-slate-200 text-slate-900 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
                                <i class="ph-bold ph-printer text-base"></i>
                                Print CV
                            </button>
                            <a href="{{ route('cv.generator') }}" class="px-6 py-3 bg-primary-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-primary-700 transition-all shadow-xl shadow-primary-100 flex items-center gap-2">
                                <i class="ph-bold ph-download-simple text-base"></i>
                                Export Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        .magnetic-btn { transition: transform 0.2s cubic-bezier(0.23, 1, 0.32, 1); }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        
        @keyframes fade-in-down {
            0% { opacity: 0; transform: translateY(-10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-down { animation: fade-in-down 0.4s ease-out forwards; }
    </style>
    @livewire('cv-builder.portfolio-publish-modal')
</x-app-layout>
