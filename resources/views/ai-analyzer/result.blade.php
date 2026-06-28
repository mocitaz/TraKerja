<x-app-layout>
    <div class="bg-[#fafafa] min-h-screen pb-28 font-sans">
        <div class="max-w-[950px] mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            
            <!-- Chat Session Header -->
            <div class="flex items-center justify-between border-b border-zinc-200/50 pb-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-primary-50 border border-primary-100 text-primary-650 rounded-xl flex items-center justify-center shrink-0 shadow-2xs">
                        <i class="ph ph-sparkle text-base"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-sm font-bold text-zinc-800 tracking-tight">AI Resume Analyst</h1>
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-[9.5px] font-bold text-emerald-650 uppercase tracking-wider">Active Session</span>
                        </div>
                        <p class="text-[11px] text-zinc-400 mt-0.5">Interactive optimization based on your target job description.</p>
                    </div>
                </div>

                <a href="{{ route('ai-analyzer.index') }}" class="px-3 py-1.5 bg-white border border-zinc-200 hover:bg-zinc-50 text-[11px] font-bold text-zinc-650 rounded-md transition-all shadow-3xs flex items-center gap-1.5">
                    <i class="ph ph-arrow-counter-clockwise text-xs"></i>
                    <span>New Analysis</span>
                </a>
            </div>

            @php
                // Safe parsing of match score and summary values
                $score = $result['score'] ?? $result['match_score'] ?? 85;
                if (is_string($score)) {
                    $score = (int) filter_var($score, FILTER_SANITIZE_NUMBER_INT);
                }
                $score = $score > 0 ? $score : 85;

                $summary = $result['summary'] ?? $result['ringkasan'] ?? $result['executive_summary'] ?? '';
                if (empty($summary)) {
                    $summary = "Your technical background is impressive. To reach the next level, focus on quantifying your impact with metrics and aligning your skill descriptions more closely with the specific industry jargon used in this JD.";
                }
            @endphp

            <!-- The Chat Conversation Thread -->
            <div class="space-y-6">
                
                {{-- 1. USER MESSAGE (The Prompt/Upload) --}}
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-full bg-zinc-150 border border-zinc-200 text-zinc-600 flex items-center justify-center shrink-0">
                        <i class="ph ph-user text-sm"></i>
                    </div>
                    <div class="flex-1 bg-zinc-50 border border-zinc-200/50 rounded-2xl rounded-tl-none p-4">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs font-bold text-zinc-800">You (Job Seeker)</span>
                            <span class="text-[9px] text-zinc-400 font-medium">Uploaded CV & JD</span>
                        </div>
                        <p class="text-[12.5px] text-zinc-550 leading-relaxed italic">
                            "Analyze my professional profile against the job description. Please suggest optimizations and provide revisions for each CV section."
                        </p>
                    </div>
                </div>

                {{-- 2. AI ASSISTANT MESSAGE (The Entire Analysis) --}}
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-primary-500 to-primary-650 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <i class="ph ph-sparkle text-sm"></i>
                    </div>
                    <div class="flex-1 space-y-6">
                        {{-- AI Identity Header --}}
                        <div class="flex items-center justify-between mb-1">
                            <div>
                                <span class="text-xs font-bold text-zinc-800">TraKerja AI</span>
                                <span class="ml-1.5 px-1.5 py-0.2 bg-primary-50 text-primary-700 text-[8.5px] font-bold uppercase tracking-wider rounded border border-primary-100/60">Assistant</span>
                            </div>
                            <span class="text-[9px] text-zinc-400 font-medium">Just now</span>
                        </div>

                        {{-- Score & Executive Summary Block --}}
                        <div class="border border-zinc-200/80 bg-white rounded-2xl p-4 sm:p-5 shadow-3xs space-y-4">
                            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4">
                                {{-- Match Score Gauge (Small) --}}
                                <div class="relative w-16 h-16 shrink-0">
                                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                                        <circle class="text-zinc-100" stroke-width="10" stroke="currentColor" fill="none" r="40" cx="50" cy="50" />
                                        <circle class="text-primary-600 transition-all duration-1000 ease-out" stroke-width="10" stroke-dasharray="251" stroke-dashoffset="{{ 251 * (1 - $score/100) }}" stroke-linecap="round" stroke="currentColor" fill="none" r="40" cx="50" cy="50" />
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="text-sm font-bold text-zinc-800">{{ $score }}<span class="text-[10px] text-zinc-400">%</span></span>
                                    </div>
                                </div>
                                
                                {{-- Executive Summary --}}
                                <div class="flex-1 text-center sm:text-left">
                                    <div class="flex items-center justify-center sm:justify-start gap-2 mb-1.5">
                                        <span class="text-[9px] font-bold text-primary-650 uppercase tracking-wider">Analysis Summary</span>
                                        <span class="w-1 h-1 bg-zinc-300 rounded-full"></span>
                                        <span class="px-1.5 py-0.2 bg-emerald-50 text-emerald-750 text-[8.5px] font-bold uppercase tracking-wider rounded border border-emerald-100/60">
                                            {{ $score >= 80 ? 'Strong Match' : ($score >= 60 ? 'Good Match' : 'Review Needed') }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-zinc-600 leading-relaxed italic text-justify">
                                        "{{ $summary }}"
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Section-by-Section Revisions --}}
                        <div class="space-y-4">
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
                                                    <div class="mt-2 p-3 bg-zinc-50/60 border-l-2 border-zinc-300 rounded-r-md text-xs italic text-zinc-555 leading-relaxed formatted-content">
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

                        {{-- Timeline Action Roadmap --}}
                        @if(isset($result['rencana_aksi']) && is_array($result['rencana_aksi']) && count($result['rencana_aksi']) > 0)
                            <div class="border border-zinc-200/80 bg-white rounded-2xl p-5 sm:p-6 shadow-3xs relative overflow-hidden">
                                <!-- Heading -->
                                <div class="flex items-center gap-3 mb-5 pb-3 border-b border-zinc-100">
                                    <div class="w-7 h-7 bg-primary-50 text-primary-650 rounded-lg flex items-center justify-center border border-primary-100/50 shrink-0">
                                        <i class="ph ph-map-trifold text-sm"></i>
                                    </div>
                                    <div>
                                        <span class="text-xs font-bold text-zinc-800">The Roadmap to 95%+</span>
                                        <p class="text-[9px] font-bold text-zinc-400 uppercase tracking-wider mt-0.5">Recommended sequence of actions</p>
                                    </div>
                                </div>

                                <!-- Vertical Roadmap Timeline -->
                                <div class="relative pl-1 sm:pl-2 space-y-5">
                                    {{-- Connecting line --}}
                                    <div class="absolute left-[13px] sm:left-[17px] top-3 bottom-3 w-[2px] bg-gradient-to-b from-primary-100 via-primary-200 to-zinc-100"></div>

                                    @foreach($result['rencana_aksi'] as $index => $action)
                                        @php
                                            // Clean up duplicate "Langkah X:" prefixes from AI response text
                                            $cleanAction = preg_replace('/^(Langkah\s+\d+:\s*|Step\s+\d+:\s*)/iu', '', $action);
                                        @endphp
                                        <div class="relative flex gap-4 items-start group">
                                            {{-- Timeline Node Badge --}}
                                            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-white border-2 border-primary-200 text-primary-700 flex items-center justify-center text-xs font-bold shrink-0 z-10 shadow-3xs group-hover:border-primary-500 group-hover:bg-primary-50 transition-all">
                                                {{ $index + 1 }}
                                            </div>
                                            
                                            {{-- Step Card --}}
                                            <div class="flex-1 bg-zinc-50/40 hover:bg-zinc-50 border border-zinc-150/70 hover:border-zinc-200 rounded-xl p-3.5 transition-all">
                                                <div class="flex items-center gap-2 mb-1.5">
                                                    <span class="text-[9px] font-bold text-primary-650 uppercase tracking-wider">Step {{ $index + 1 }}</span>
                                                    <span class="w-1 h-1 bg-zinc-300 rounded-full"></span>
                                                    <span class="text-[9px] font-bold text-zinc-400 uppercase tracking-wider">Priority {{ $index == 0 ? 'High' : ($index < 3 ? 'Medium' : 'Standard') }}</span>
                                                </div>
                                                <p class="text-xs font-semibold text-zinc-700 leading-relaxed text-justify">{{ $cleanAction }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            </div>

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