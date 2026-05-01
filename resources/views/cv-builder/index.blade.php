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

    <div class="bg-[#f8fafc] min-h-screen pb-20">
        <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            
            {{-- Profile Completion & Quick Actions --}}
            <div class="bg-white rounded-3xl border border-slate-200/60 p-6 sm:p-8 shadow-sm mb-8">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-primary-50 rounded-2xl flex items-center justify-center text-primary-600 shadow-inner">
                                <i class="ph-duotone ph-user-circle-gear text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-slate-900 tracking-tight">Profile Completion</h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mt-1.5">Prepare your data for export</p>
                            </div>
                        </div>

                        @php
                            $total = 4;
                            $filled = 0;
                            if ($profile) $filled++;
                            if ($experiences->count() > 0) $filled++;
                            if ($educations->count() > 0) $filled++;
                            if ($skills->count() > 0) $filled++;
                            $percentage = ($filled / $total) * 100;
                        @endphp

                        <div class="space-y-3">
                            <div class="flex justify-between items-end">
                                <span class="text-xs font-black text-slate-700 uppercase tracking-widest">{{ number_format($percentage, 0) }}% COMPLETE</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $filled }} / {{ $total }} SECTIONS</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                                <div class="bg-gradient-to-r from-[#d983e4] via-primary-600 to-[#4e71c5] h-full rounded-full transition-all duration-1000 ease-out" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 shrink-0">
                        <a href="{{ route('cv.generator') }}" class="magnetic-btn group relative px-8 py-4 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-[2px] hover:bg-slate-800 transition-all shadow-xl shadow-slate-200 flex items-center justify-center gap-3 active:scale-95">
                            <i class="ph-bold ph-file-pdf text-base group-hover:scale-110 transition-transform"></i>
                            GENERATE CV
                        </a>
                        @if(!auth()->user()->is_premium)
                            <a href="#" class="px-8 py-4 bg-amber-50 text-amber-700 border border-amber-200 rounded-2xl font-black text-xs uppercase tracking-[2px] hover:bg-amber-100 transition-all flex items-center justify-center gap-3">
                                <i class="ph-bold ph-crown text-base"></i>
                                UPGRADE
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Main Tabs Navigation --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200/60 overflow-hidden mb-8" x-data="{ activeTab: 'experiences' }">
                {{-- Horizontal Tabs Bar --}}
                <div class="bg-slate-50/50 border-b border-slate-100">
                    <nav class="flex items-center gap-2 p-2 overflow-x-auto scrollbar-hide">
                        <button @click="activeTab = 'experiences'" 
                                :class="activeTab === 'experiences' ? 'bg-white text-primary-600 shadow-sm border-slate-200/60' : 'text-slate-400 hover:text-slate-600 border-transparent'"
                                class="flex items-center gap-2 px-6 py-3 rounded-2xl border text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap">
                            <i class="ph-bold ph-briefcase"></i>
                            Experience
                        </button>
                        <button @click="activeTab = 'education'" 
                                :class="activeTab === 'education' ? 'bg-white text-primary-600 shadow-sm border-slate-200/60' : 'text-slate-400 hover:text-slate-600 border-transparent'"
                                class="flex items-center gap-2 px-6 py-3 rounded-2xl border text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap">
                            <i class="ph-bold ph-graduation-cap"></i>
                            Education
                        </button>
                        <button @click="activeTab = 'skills'" 
                                :class="activeTab === 'skills' ? 'bg-white text-primary-600 shadow-sm border-slate-200/60' : 'text-slate-400 hover:text-slate-600 border-transparent'"
                                class="flex items-center gap-2 px-6 py-3 rounded-2xl border text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap">
                            <i class="ph-bold ph-star"></i>
                            Skills
                        </button>
                        <button @click="activeTab = 'organizations'" 
                                :class="activeTab === 'organizations' ? 'bg-white text-primary-600 shadow-sm border-slate-200/60' : 'text-slate-400 hover:text-slate-600 border-transparent'"
                                class="flex items-center gap-2 px-6 py-3 rounded-2xl border text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap">
                            <i class="ph-bold ph-users-three"></i>
                            Organizations
                        </button>
                        <button @click="activeTab = 'achievements'" 
                                :class="activeTab === 'achievements' ? 'bg-white text-primary-600 shadow-sm border-slate-200/60' : 'text-slate-400 hover:text-slate-600 border-transparent'"
                                class="flex items-center gap-2 px-6 py-3 rounded-2xl border text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap">
                            <i class="ph-bold ph-trophy"></i>
                            Achievements
                        </button>
                        <button @click="activeTab = 'projects'" 
                                :class="activeTab === 'projects' ? 'bg-white text-primary-600 shadow-sm border-slate-200/60' : 'text-slate-400 hover:text-slate-600 border-transparent'"
                                class="flex items-center gap-2 px-6 py-3 rounded-2xl border text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap">
                            <i class="ph-bold ph-code"></i>
                            Projects
                        </button>
                    </nav>
                </div>

                {{-- Tab Content Panel --}}
                <div class="p-8">
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
            </div>

            {{-- Pro Tip / Info Box --}}
            <div class="flex items-center gap-3 px-4 py-3 bg-primary-50 border border-primary-200 rounded-2xl text-sm">
                <i class="ph-duotone ph-lightbulb text-primary-500 text-lg shrink-0"></i>
                <p class="text-primary-700 font-medium leading-snug">
                    <span class="font-black">Pro Tip:</span> Complete all sections to boost your ATS score. Use <span class="font-black">Generate CV</span> to export your resume.
                </p>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-app-layout>
