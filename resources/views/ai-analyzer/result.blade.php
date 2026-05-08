<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-2xl font-black text-slate-900 leading-tight tracking-tight">
                Analysis <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#d983e4] via-primary-600 to-[#4e71c5]">Results</span>
            </h1>
            <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest mt-1">Deep AI analysis of your professional profile</p>
        </div>
    </x-slot>

    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <div class="bg-[#fcfcfd] min-h-screen pb-24 font-sans">
        <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            
            {{-- Compact Top Dashboard --}}
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-8">
                {{-- Score Card --}}
                <div class="md:col-span-3 bg-white rounded-[2rem] sm:rounded-[2.5rem] p-6 sm:p-8 border border-slate-200/60 shadow-[inset_0_1px_2px_rgba(255,255,255,0.8),0_4px_6px_-1px_rgba(0,0,0,0.02)] flex flex-col items-center justify-center relative overflow-hidden group">
                    <div class="absolute -right-10 -top-10 w-32 h-32 bg-primary-50/50 rounded-full blur-2xl group-hover:bg-primary-100 transition-colors duration-500"></div>
                    <div class="relative w-24 h-24 sm:w-28 sm:h-28 mb-4">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                            <circle class="text-slate-100" stroke-width="8" stroke="currentColor" fill="none" r="42" cx="50" cy="50" />
                            <circle class="text-primary-600 transition-all duration-1000 ease-out" stroke-width="8" stroke-dasharray="264" stroke-dashoffset="{{ 264 * (1 - 85/100) }}" stroke-linecap="round" stroke="currentColor" fill="none" r="42" cx="50" cy="50" />
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tighter italic">85<span class="text-xs">%</span></span>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Match Score</p>
                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[8px] sm:text-[9px] font-black uppercase tracking-widest border border-emerald-100">Strong</span>
                    </div>
                </div>

                {{-- Key Insights Card --}}
                <div class="md:col-span-9 bg-slate-900 rounded-[2rem] sm:rounded-[2.5rem] p-6 sm:p-8 text-white shadow-xl shadow-primary-100 flex flex-col md:flex-row items-center gap-6 sm:gap-8 relative overflow-hidden">
                    <div class="relative z-10 flex-1">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 bg-primary-400/20 rounded-xl flex items-center justify-center shadow-inner">
                                <i class="ph-bold ph-lightning text-primary-300 text-sm"></i>
                            </div>
                            <h3 class="text-[9px] sm:text-[10px] font-black uppercase tracking-[0.3em] text-primary-300">Executive Summary</h3>
                        </div>
                        <p class="text-sm sm:text-[15px] font-medium text-primary-50 leading-relaxed italic text-justify">
                            "Your technical background is impressive. To reach the next level, focus on quantifying your impact with metrics and aligning your skill descriptions more closely with the specific industry jargon used in this JD."
                        </p>
                    </div>
                    
                    <div class="relative z-10 flex flex-row md:flex-col gap-3 w-full md:w-auto min-w-0 md:min-w-[200px] border-t border-white/10 md:border-t-0 pt-4 md:pt-0">
                        <div class="flex-1 md:flex-initial px-4 sm:px-5 py-3 bg-white/5 border border-white/10 rounded-2xl flex items-center gap-2 sm:gap-3 justify-center md:justify-start">
                            <i class="ph-bold ph-check-circle text-emerald-400"></i>
                            <span class="text-[8px] sm:text-[9px] font-black uppercase tracking-widest text-primary-100">Quantified Impact</span>
                        </div>
                        <div class="flex-1 md:flex-initial px-4 sm:px-5 py-3 bg-white/5 border border-white/10 rounded-2xl flex items-center gap-2 sm:gap-3 justify-center md:justify-start">
                            <i class="ph-bold ph-check-circle text-emerald-400"></i>
                            <span class="text-[8px] sm:text-[9px] font-black uppercase tracking-widest text-primary-100">Keyword Optimized</span>
                        </div>
                    </div>
                    {{-- Decorative blur --}}
                    <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-primary-500/10 rounded-full blur-3xl"></div>
                </div>
            </div>

            {{-- The "AI Conversation" Style Results --}}
            <div class="space-y-8 mb-12">
                @php
                    $sections = [
                        'profil' => ['title' => 'Profile & Identity', 'icon' => 'ph-identification-card'],
                        'pendidikan' => ['title' => 'Academic History', 'icon' => 'ph-graduation-cap'],
                        'pengalaman_kerja' => ['title' => 'Professional Career', 'icon' => 'ph-briefcase'],
                        'pengalaman_organisasi' => ['title' => 'Leadership & Community', 'icon' => 'ph-users-three'],
                        'projek' => ['title' => 'Technical Projects', 'icon' => 'ph-layout'],
                        'keterampilan' => ['title' => 'Skill Ecosystem', 'icon' => 'ph-atom'],
                        'prestasi_dan_publikasi' => ['title' => 'Awards & Impact', 'icon' => 'ph-medal'],
                    ];
                @endphp

                @foreach($sections as $key => $section)
                    @if(isset($result[$key]) && (!empty($result[$key]['teks_revisi']) || !empty($result[$key]['alasan_perubahan'])))
                        <div class="relative pl-10 sm:pl-16 group">
                            {{-- Thread Line --}}
                            <div class="absolute left-5 sm:left-8 top-0 bottom-0 w-px bg-slate-200 group-last:bottom-auto group-last:h-12"></div>
                            {{-- Icon --}}
                            <div class="absolute left-0 top-0 w-10 sm:w-16 h-10 sm:h-16 flex items-center justify-center">
                                <div class="w-8 sm:w-12 h-8 sm:h-12 bg-white border border-slate-200 rounded-xl sm:rounded-2xl flex items-center justify-center text-slate-400 group-hover:text-primary-600 group-hover:border-primary-600/30 transition-all shadow-sm z-10 bg-[#fcfcfd]">
                                    <i class="ph-bold {{ $section['icon'] }} text-base sm:text-xl"></i>
                                </div>
                            </div>

                            <div class="space-y-4 sm:space-y-6">
                                <h3 class="text-sm sm:text-base font-black text-slate-900 tracking-tight flex flex-wrap items-center gap-1.5 sm:gap-3">
                                    {{ $section['title'] }}
                                    <span class="hidden sm:inline w-2 h-2 bg-slate-200 rounded-full"></span>
                                    <span class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest block w-full sm:w-auto mt-0.5 sm:mt-0">Optimized Analysis</span>
                                </h3>

                                <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6">
                                    {{-- Reasoning (Thinking Process) --}}
                                    <div class="lg:col-span-5 bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/60 shadow-sm">
                                        <div class="flex items-center gap-3 mb-4">
                                            <i class="ph-bold ph-brain text-primary-600"></i>
                                            <h4 class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Thinking Process</h4>
                                        </div>
                                        <div class="text-xs sm:text-sm font-medium text-slate-600 leading-relaxed italic formatted-content">
                                            {{ $result[$key]['alasan_perubahan'] }}
                                        </div>
                                    </div>

                                    {{-- Revised Result (Output) --}}
                                    <div class="lg:col-span-7 bg-primary-50/50 rounded-3xl p-5 sm:p-6 border border-primary-100 relative group/card">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex items-center gap-3">
                                                <i class="ph-bold ph-sparkle text-primary-600"></i>
                                                <h4 class="text-[9px] font-black text-primary-600 uppercase tracking-widest">Optimized Output</h4>
                                            </div>
                                            <button onclick="copyToClipboard(this)" class="text-primary-400 hover:text-primary-600 transition-colors">
                                                <i class="ph-bold ph-copy text-lg"></i>
                                            </button>
                                        </div>
                                        <div class="text-xs sm:text-sm font-bold text-slate-900 leading-relaxed formatted-content">
                                            {{ $result[$key]['teks_revisi'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- Action Roadmap --}}
            @if(isset($result['rencana_aksi']) && is_array($result['rencana_aksi']) && count($result['rencana_aksi']) > 0)
                <div class="bg-white rounded-[2rem] sm:rounded-[3rem] p-6 sm:p-10 border border-slate-200/60 shadow-sm relative overflow-hidden mb-16">
                    <div class="relative z-10 mb-6 sm:mb-10">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-primary-50 rounded-2xl flex items-center justify-center text-primary-600 shrink-0">
                                <i class="ph-duotone ph-list-numbers text-xl sm:text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">The Roadmap to 95%+</h3>
                                <p class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">A clear path to making your profile irresistible</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 relative z-10">
                        @foreach($result['rencana_aksi'] as $index => $action)
                            <div class="flex gap-4 sm:gap-5 p-4 sm:p-5 rounded-2xl sm:rounded-3xl hover:bg-slate-50 transition-all border border-transparent hover:border-slate-100 group">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 shrink-0 bg-slate-900 text-white rounded-xl flex items-center justify-center text-[11px] sm:text-xs font-black shadow-lg shadow-slate-200 group-hover:scale-110 transition-transform">
                                    {{ $index + 1 }}
                                </div>
                                <div class="pt-0.5 sm:pt-1">
                                    <p class="text-xs sm:text-sm font-bold text-slate-800 leading-relaxed text-justify">{{ $action }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

    @push('modals')
    {{-- Sticky Action Bar --}}
    <div class="fixed bottom-4 sm:bottom-8 left-1/2 -translate-x-1/2 w-full max-w-lg px-4 z-50">
        <div class="bg-slate-900/90 backdrop-blur-xl rounded-[1.5rem] sm:rounded-3xl p-2 sm:p-3 flex items-center gap-2 sm:gap-3 shadow-2xl border border-white/10">
            <a href="{{ route('cv.builder') }}" class="flex-1 py-3 sm:py-4 bg-primary-600 text-white rounded-xl sm:rounded-2xl font-black text-[9px] sm:text-[10px] uppercase tracking-[1.5px] sm:tracking-[2px] hover:bg-primary-500 transition-all flex items-center justify-center gap-1.5 sm:gap-2 active:scale-95">
                <i class="ph-bold ph-pencil-simple text-sm"></i>
                APPLY CHANGES
            </a>
            <a href="{{ route('ai-analyzer.index') }}" class="px-4 sm:px-6 py-3 sm:py-4 bg-white/10 text-white rounded-xl sm:rounded-2xl font-black text-[9px] sm:text-[10px] uppercase tracking-[1.5px] sm:tracking-[2px] hover:bg-white/20 transition-all flex items-center justify-center gap-1.5 sm:gap-2 active:scale-95">
                <i class="ph-bold ph-arrow-counter-clockwise text-sm"></i>
                RESET
            </a>
        </div>
    </div>
    @endpush
        </div>
    </div>

    <style>
        .formatted-content { line-height: 1.8; text-align: justify; }
        .formatted-content strong { font-weight: 900; color: #0f172a; }
    </style>

    <script>
        function copyToClipboard(btn) {
            const content = btn.closest('div').nextElementSibling.innerText;
            navigator.clipboard.writeText(content).then(() => {
                const icon = btn.querySelector('i');
                icon.className = 'ph-bold ph-check text-emerald-500';
                setTimeout(() => { icon.className = 'ph-bold ph-copy'; }, 2000);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const formattedContents = document.querySelectorAll('.formatted-content');
            formattedContents.forEach((content) => {
                let text = content.textContent.trim();
                if (!text) return;

                let lines = text.split('\n');
                let formatted = lines.map(line => {
                    line = line.trim();
                    if (!line) return '<div class="h-4"></div>';
                    
                    line = line.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');
                    
                    if (line.startsWith('●') || line.startsWith('•') || line.startsWith('-')) {
                        return `<div class="flex items-start gap-3 mb-2"><span class="text-primary-600 shrink-0 mt-1.5 font-black">•</span><span>${line.substring(1).trim()}</span></div>`;
                    }
                    
                    const numMatch = line.match(/^\d+\.\s+(.+)/);
                    if (numMatch) {
                        return `<div class="flex items-start gap-4 mb-2"><span class="font-black text-primary-600 shrink-0 w-5 h-5 bg-primary-50 rounded-lg flex items-center justify-center text-[10px]">${line.split('.')[0]}</span><span>${numMatch[1]}</span></div>`;
                    }
                    
                    return `<p class="mb-2">${line}</p>`;
                });
                
                content.innerHTML = formatted.join('');
            });
        });
    </script>
</x-app-layout>