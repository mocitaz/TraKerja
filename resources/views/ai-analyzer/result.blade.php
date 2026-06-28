<x-app-layout>
    <div class="bg-[#fafafa] min-h-screen pb-24 font-sans">
        <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            
            <!-- Premium Notion-Inspired Page Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-slate-200/60 pb-5 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-tr from-primary-50 to-primary-100/50 rounded-xl flex items-center justify-center text-primary-600 shrink-0 border border-primary-100/50 shadow-inner">
                        <i class="ph ph-sparkle text-lg"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-base font-bold text-slate-800 tracking-tight">Analysis Results</h1>
                            <span class="px-2 py-0.5 bg-primary-50 text-primary-600 text-[9px] font-black uppercase tracking-wider rounded-md border border-primary-100/60">AI Studio</span>
                        </div>
                        <p class="text-xs text-slate-455 mt-0.5">Deep AI analysis of your professional profile.</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('ai-analyzer.index') }}" class="px-3.5 py-1.5 bg-white text-slate-700 border border-slate-200 hover:bg-slate-50 text-xs font-semibold rounded-lg shadow-sm transition-colors flex items-center gap-1.5">
                        <i class="ph ph-arrow-left text-sm"></i>
                        <span>Back to Studio</span>
                    </a>
                </div>
            </div>
            
            {{-- Compact Top Dashboard --}}
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-6">
                {{-- Score Card --}}
                <div class="md:col-span-3 bg-white rounded-xl p-5 border border-slate-200/70 shadow-sm flex flex-col items-center justify-center relative overflow-hidden">
                    <div class="relative w-24 h-24 mb-3">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                            <circle class="text-slate-100" stroke-width="8" stroke="currentColor" fill="none" r="42" cx="50" cy="50" />
                            <circle class="text-slate-800 transition-all duration-1000 ease-out" stroke-width="8" stroke-dasharray="264" stroke-dashoffset="{{ 264 * (1 - 85/100) }}" stroke-linecap="round" stroke="currentColor" fill="none" r="42" cx="50" cy="50" />
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-2xl font-bold text-slate-800 tracking-tight">85<span class="text-xs font-semibold text-slate-400">%</span></span>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Match Score</p>
                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-650 rounded text-[9px] font-bold uppercase tracking-wider border border-emerald-100">Strong</span>
                    </div>
                </div>

                {{-- Key Insights Card --}}
                <div class="md:col-span-9 bg-slate-900 rounded-xl p-5 text-white shadow-sm flex flex-col md:flex-row items-center gap-6 relative overflow-hidden">
                    <div class="relative z-10 flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-6 h-6 bg-white/10 rounded flex items-center justify-center shrink-0">
                                <i class="ph ph-lightning text-white text-xs"></i>
                            </div>
                            <h3 class="text-[9px] font-bold uppercase tracking-wider text-slate-300">Executive Summary</h3>
                        </div>
                        <p class="text-xs sm:text-sm font-medium text-slate-100 leading-relaxed italic text-justify">
                            "Your technical background is impressive. To reach the next level, focus on quantifying your impact with metrics and aligning your skill descriptions more closely with the specific industry jargon used in this JD."
                        </p>
                    </div>
                    
                    <div class="relative z-10 flex flex-row md:flex-col gap-2 w-full md:w-auto min-w-0 md:min-w-[180px] border-t border-white/10 md:border-t-0 pt-3 md:pt-0">
                        <div class="flex-1 md:flex-initial px-3.5 py-2 bg-white/5 border border-white/10 rounded-lg flex items-center gap-2 justify-center md:justify-start">
                            <i class="ph ph-check-circle text-emerald-400"></i>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-slate-200">Quantified Impact</span>
                        </div>
                        <div class="flex-1 md:flex-initial px-3.5 py-2 bg-white/5 border border-white/10 rounded-lg flex items-center gap-2 justify-center md:justify-start">
                            <i class="ph ph-check-circle text-emerald-400"></i>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-slate-200">Keyword Optimized</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- The "AI Conversation" Style Results --}}
            <div class="mb-8 space-y-6">
                {{-- User Prompt Bubble --}}
                <div class="flex items-start gap-3 pl-1 sm:pl-2">
                    <div class="w-8 h-8 rounded-full bg-zinc-100 text-zinc-600 flex items-center justify-center shrink-0 border border-zinc-200">
                        <i class="ph ph-user text-sm"></i>
                    </div>
                    <div class="flex-1 bg-zinc-50/50 border border-zinc-200/60 rounded-2xl rounded-tl-none p-3.5 sm:p-4">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-bold text-zinc-800">You (Job Seeker)</span>
                            <span class="text-[9px] text-zinc-400 font-medium">Uploaded CV & Job Description</span>
                        </div>
                        <p class="text-xs text-zinc-500 leading-relaxed italic">
                            "Analyze my professional profile against the job description. Please provide a detailed analysis of my strengths, weaknesses, and clear revisions to optimize each section of my CV."
                        </p>
                    </div>
                </div>

                {{-- AI Response Thread --}}
                <div class="flex items-start gap-3 pl-1 sm:pl-2">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-primary-500 to-primary-650 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <i class="ph ph-sparkle text-sm"></i>
                    </div>
                    <div class="flex-1 space-y-4">
                        <div class="flex items-center justify-between mb-1">
                            <div>
                                <span class="text-xs font-bold text-zinc-800">TraKerja AI</span>
                                <span class="ml-1.5 px-1.5 py-0.2 bg-primary-50 text-primary-700 text-[8.5px] font-bold uppercase tracking-wider rounded border border-primary-100/60">Assistant</span>
                            </div>
                            <span class="text-[9px] text-zinc-400 font-medium">Just now</span>
                        </div>

                        {{-- The continuous conversation flow --}}
                        <div class="space-y-5">
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
                                    <div class="border border-zinc-200/80 bg-white rounded-2xl p-4 sm:p-5 shadow-3xs hover:shadow-2xs transition-shadow relative group/bubble ai-response-card">
                                        
                                        {{-- Section Header --}}
                                        <div class="flex items-center justify-between border-b border-zinc-100 pb-2 mb-3">
                                            <div class="flex items-center gap-2">
                                                <div class="w-5 h-5 rounded bg-zinc-50 text-zinc-650 flex items-center justify-center border border-zinc-150 shrink-0">
                                                    <i class="ph {{ $section['icon'] }} text-xs"></i>
                                                </div>
                                                <span class="text-xs font-bold text-zinc-800 tracking-tight">{{ $section['title'] }}</span>
                                                <span class="px-1.5 py-0.2 bg-primary-50/60 text-primary-700 text-[8.5px] font-bold uppercase tracking-wider rounded border border-primary-100/30">Optimized</span>
                                            </div>
                                            
                                            <div class="flex items-center gap-2">
                                                <button onclick="copyToClipboard(this)" class="opacity-0 group-hover/bubble:opacity-100 text-zinc-400 hover:text-zinc-700 transition-all cursor-pointer p-1 rounded hover:bg-zinc-50 flex items-center justify-center outline-none">
                                                    <i class="ph ph-copy text-sm"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="space-y-3.5">
                                            {{-- Claude-style Thought Block --}}
                                            @if(!empty($result[$key]['alasan_perubahan']))
                                                <details class="group/details w-full outline-none">
                                                    <summary class="flex items-center gap-1.5 text-[9px] font-bold text-zinc-400 hover:text-zinc-600 uppercase tracking-wider transition-colors outline-none cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                                                        <span class="w-3.5 h-3.5 bg-zinc-50 rounded border border-zinc-150 flex items-center justify-center shrink-0">
                                                            <i class="ph ph-brain text-[10px] text-zinc-450 group-open/details:text-zinc-600"></i>
                                                        </span>
                                                        <span>Thought Process</span>
                                                        <span class="inline-block transition-transform duration-200 group-open/details:rotate-90 text-[7px] ml-0.5">
                                                            <i class="ph-bold ph-caret-right"></i>
                                                        </span>
                                                    </summary>
                                                    <div class="mt-2 p-3 bg-zinc-50/60 border-l-2 border-zinc-300 rounded-r-md text-xs italic text-zinc-550 leading-relaxed formatted-content">
                                                        {{ $result[$key]['alasan_perubahan'] }}
                                                    </div>
                                                </details>
                                            @endif

                                            {{-- Output Content --}}
                                            @if(!empty($result[$key]['teks_revisi']))
                                                <div class="text-zinc-700 text-xs sm:text-sm font-medium leading-relaxed formatted-content formatted-content-revisi pl-0.5">
                                                    {{ $result[$key]['teks_revisi'] }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Roadmap --}}
            @if(isset($result['rencana_aksi']) && is_array($result['rencana_aksi']) && count($result['rencana_aksi']) > 0)
                <div class="bg-white rounded-xl p-5 sm:p-8 border border-slate-200/70 shadow-sm relative overflow-hidden mb-12">
                    <div class="relative z-10 mb-4 sm:mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-slate-50 border border-slate-200 rounded flex items-center justify-center text-slate-600 shrink-0">
                                <i class="ph ph-list-numbers text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-800 tracking-tight">The Roadmap to 95%+</h3>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">A clear path to making your profile irresistible</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 relative z-10">
                        @foreach($result['rencana_aksi'] as $index => $action)
                            <div class="flex gap-3 p-3 rounded-lg border border-slate-150 bg-slate-50 hover:bg-white hover:border-slate-350 transition-colors">
                                <div class="w-6 h-6 shrink-0 bg-slate-900 text-white rounded flex items-center justify-center text-[10px] font-bold">
                                    {{ $index + 1 }}
                                </div>
                                <div class="pt-0.5">
                                    <p class="text-xs font-medium text-slate-600 leading-relaxed text-justify">{{ $action }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @push('modals')
            {{-- Sticky Action Bar --}}
            <div class="fixed bottom-4 left-1/2 -translate-x-1/2 w-full max-w-md px-4 z-50">
                <div class="bg-slate-900/95 rounded-xl p-1.5 flex items-center gap-2 shadow-lg border border-slate-850">
                    <a href="{{ route('cv.builder') }}" class="flex-1 py-2 bg-slate-100 text-slate-800 hover:bg-white text-xs font-semibold rounded-lg transition-colors flex items-center justify-center gap-1.5">
                        <i class="ph ph-pencil text-sm"></i>
                        <span>Apply Changes</span>
                    </a>
                    <a href="{{ route('ai-analyzer.index') }}" class="px-4 py-2 bg-slate-800 text-white hover:bg-slate-700 text-xs font-semibold rounded-lg transition-colors flex items-center justify-center gap-1.5">
                        <i class="ph ph-arrow-counter-clockwise text-sm"></i>
                        <span>Reset</span>
                    </a>
                </div>
            </div>
            @endpush
        </div>
    </div>

    <style>
        .formatted-content { line-height: 1.6; text-align: justify; }
        .formatted-content strong { font-weight: 700; color: #0f172a; }
    </style>

    <script>
        function copyToClipboard(btn) {
            const card = btn.closest('.ai-response-card');
            const contentEl = card.querySelector('.formatted-content-revisi');
            if (!contentEl) return;
            const content = contentEl.innerText;
            navigator.clipboard.writeText(content).then(() => {
                const icon = btn.querySelector('i');
                icon.className = 'ph ph-check text-emerald-500';
                setTimeout(() => { icon.className = 'ph ph-copy'; }, 2000);
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
                    if (!line) return '<div class="h-2"></div>';
                    
                    line = line.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');
                    
                    if (line.startsWith('●') || line.startsWith('•') || line.startsWith('-')) {
                        return `<div class="flex items-start gap-2 mb-1"><span class="text-slate-600 shrink-0 mt-1 font-bold">•</span><span>${line.substring(1).trim()}</span></div>`;
                    }
                    
                    const numMatch = line.match(/^\d+\.\s+(.+)/);
                    if (numMatch) {
                        return `<div class="flex items-start gap-2.5 mb-1"><span class="font-bold text-slate-800 shrink-0 w-4 h-4 bg-slate-200 rounded flex items-center justify-center text-[9px]">${line.split('.')[0]}</span><span>${numMatch[1]}</span></div>`;
                    }
                    
                    return `<p class="mb-1">${line}</p>`;
                });
                
                content.innerHTML = formatted.join('');
            });
        });
    </script>
</x-app-layout>