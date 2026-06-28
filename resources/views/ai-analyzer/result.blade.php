<x-app-layout>
    <div class="bg-zinc-50/50 min-h-screen pb-32 font-sans">
        <div class="max-w-[850px] mx-auto px-4 sm:px-6 lg:px-8 pt-5 space-y-6">
            
            {{-- Premium Header --}}
            <div class="flex items-center justify-between pb-3 border-b border-zinc-200/80">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-purple-50 rounded border border-purple-150 flex items-center justify-center text-purple-650 shrink-0">
                        <i class="ph ph-sparkle text-base"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <h1 class="text-xs font-bold text-zinc-900 tracking-tight">AI Analyzer Log</h1>
                            <span class="px-1.5 py-0.5 bg-purple-50 text-purple-700 text-[8px] font-mono font-bold uppercase tracking-wider rounded border border-purple-150">Active Thread</span>
                        </div>
                        <p class="text-[10px] text-zinc-400">Conversational resume critique & optimization roadmap</p>
                    </div>
                </div>

                <div>
                    <a href="{{ route('ai-analyzer.index') }}" 
                       class="px-2.5 py-1.5 bg-white border border-zinc-250 text-zinc-700 hover:bg-zinc-50 text-[10px] font-bold uppercase tracking-wider rounded-md transition-colors flex items-center gap-1">
                        <i class="ph ph-arrow-left text-xs"></i>
                        <span>Studio</span>
                    </a>
                </div>
            </div>

            {{-- Conversational Thread --}}
            <div class="space-y-6">
                
                {{-- MESSAGE 1: User Request --}}
                <div class="flex items-start justify-end gap-3">
                    <div class="flex flex-col items-end max-w-[85%]">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[9px] font-mono font-bold text-zinc-400">You</span>
                            <span class="text-[9px] font-mono text-zinc-300">•</span>
                            <span class="text-[9px] font-mono text-zinc-400">Just Now</span>
                        </div>
                        <div class="bg-zinc-900 text-white text-xs font-medium rounded-lg rounded-tr-none px-4 py-2.5 shadow-sm space-y-2">
                            <p class="leading-relaxed">Halo TraKerja AI! Tolong analisis kecocokan profil profesional saya dengan target pekerjaan yang ingin saya lamar.</p>
                            @if(isset($job_description))
                                <div class="bg-white/5 border border-white/10 rounded-md p-2 text-[10px] text-zinc-300">
                                    <p class="font-bold font-mono text-[8px] uppercase tracking-wider text-zinc-400 mb-1">Target Job Description:</p>
                                    <p class="line-clamp-2 italic leading-relaxed text-justify">{{ $job_description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    @php $user = Auth::user(); @endphp
                    @if($user && $user->logo)
                        <img src="{{ $user->avatar_url }}" alt="You" class="h-8 w-8 rounded-full object-cover shrink-0 border border-zinc-200">
                    @else
                        <div class="h-8 w-8 bg-zinc-100 border border-zinc-200 rounded-full flex items-center justify-center shrink-0">
                            <span class="text-zinc-700 font-bold text-xs">{{ substr($user->name ?? 'U', 0, 1) }}</span>
                        </div>
                    @endif
                </div>

                {{-- MESSAGE 2: AI Overview Response --}}
                <div class="flex items-start gap-3">
                    <div class="h-8 w-8 bg-purple-50 text-purple-650 border border-purple-150 rounded-full flex items-center justify-center shrink-0 shadow-sm">
                        <i class="ph ph-sparkle text-sm"></i>
                    </div>
                    <div class="flex flex-col items-start max-w-[85%] w-full">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[9px] font-mono font-bold text-purple-750">TraKerja AI</span>
                            <span class="text-[9px] font-mono text-zinc-300">•</span>
                            <span class="text-[9px] font-mono text-zinc-400">Just Now</span>
                        </div>
                        
                        <div class="bg-white border border-zinc-200/80 rounded-lg rounded-tl-none p-4 shadow-sm w-full space-y-4">
                            <p class="text-xs text-zinc-650 leading-relaxed text-justify">Halo! Saya telah melakukan analisis mendalam terhadap profil Anda. Berikut adalah ringkasan skor dan executive summary kecocokan profil Anda:</p>
                            
                            {{-- Executive Summary Bento Layout --}}
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                {{-- Score --}}
                                <div class="md:col-span-1 bg-zinc-50 border border-zinc-200 rounded-lg p-3 flex flex-col items-center justify-center">
                                    <div class="relative w-16 h-16 mb-2">
                                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                                            <circle class="text-zinc-200" stroke-width="8" stroke="currentColor" fill="none" r="42" cx="50" cy="50" />
                                            <circle class="text-zinc-900 transition-all duration-1000 ease-out" stroke-width="8" stroke-dasharray="264" stroke-dashoffset="{{ 264 * (1 - 85/100) }}" stroke-linecap="round" stroke="currentColor" fill="none" r="42" cx="50" cy="50" />
                                        </svg>
                                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                                            <span class="text-sm font-bold text-zinc-900 tracking-tight">85<span class="text-[9px] font-bold text-zinc-455">%</span></span>
                                        </div>
                                    </div>
                                    <p class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-0.5">Match Score</p>
                                    <span class="px-1.5 py-0.2 bg-emerald-50 text-emerald-750 text-[8px] font-mono font-bold uppercase tracking-wider rounded border border-emerald-150">Strong</span>
                                </div>

                                {{-- Text Summary --}}
                                <div class="md:col-span-3 bg-zinc-900 text-white rounded-lg p-3.5 relative overflow-hidden flex flex-col justify-center">
                                    <div class="flex items-center gap-1.5 mb-1.5">
                                        <i class="ph ph-lightning text-amber-400 text-sm"></i>
                                        <h4 class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Executive Summary</h4>
                                    </div>
                                    <p class="text-xs font-medium text-zinc-200 leading-relaxed italic text-justify">
                                        "Your technical background is impressive. To reach the next level, focus on quantifying your impact with metrics and aligning your skill descriptions more closely with the specific industry jargon used in this JD."
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- MESSAGE 3: User Ask Details --}}
                <div class="flex items-start justify-end gap-3">
                    <div class="flex flex-col items-end max-w-[85%]">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[9px] font-mono font-bold text-zinc-400">You</span>
                            <span class="text-[9px] font-mono text-zinc-300">•</span>
                            <span class="text-[9px] font-mono text-zinc-400">Just Now</span>
                        </div>
                        <div class="bg-zinc-900 text-white text-xs font-medium rounded-lg rounded-tr-none px-4 py-2">
                            <p class="leading-relaxed">Bagaimana dengan analisis detail per bagian CV saya?</p>
                        </div>
                    </div>
                    @if($user && $user->logo)
                        <img src="{{ $user->avatar_url }}" alt="You" class="h-8 w-8 rounded-full object-cover shrink-0 border border-zinc-200">
                    @else
                        <div class="h-8 w-8 bg-zinc-100 border border-zinc-200 rounded-full flex items-center justify-center shrink-0">
                            <span class="text-zinc-700 font-bold text-xs">{{ substr($user->name ?? 'U', 0, 1) }}</span>
                        </div>
                    @endif
                </div>

                {{-- MESSAGE 4: AI Section critique --}}
                <div class="flex items-start gap-3">
                    <div class="h-8 w-8 bg-purple-50 text-purple-650 border border-purple-150 rounded-full flex items-center justify-center shrink-0 shadow-sm">
                        <i class="ph ph-sparkle text-sm"></i>
                    </div>
                    <div class="flex flex-col items-start max-w-[85%] w-full">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[9px] font-mono font-bold text-purple-750">TraKerja AI</span>
                            <span class="text-[9px] font-mono text-zinc-300">•</span>
                            <span class="text-[9px] font-mono text-zinc-400">Just Now</span>
                        </div>
                        
                        <div class="bg-white border border-zinc-200/80 rounded-lg rounded-tl-none p-4 shadow-sm w-full space-y-4">
                            <p class="text-xs text-zinc-650 leading-relaxed text-justify">Tentu, ini adalah analisis kritis untuk masing-masing bagian CV Anda. Anda dapat memilih tab di bawah untuk melihat rincian hasil revisi serta pemikiran analisis saya:</p>
                            
                            {{-- Segmented Tab Menu --}}
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
                                
                                $validSections = [];
                                foreach($sections as $key => $section) {
                                    if(isset($result[$key]) && (!empty($result[$key]['teks_revisi']) || !empty($result[$key]['alasan_perubahan']))) {
                                        $validSections[$key] = $section;
                                    }
                                }
                                $firstKey = array_key_first($validSections);
                            @endphp

                            @if(count($validSections) > 0)
                                <div x-data="{ activeTab: '{{ $firstKey }}' }" class="space-y-4 w-full">
                                    {{-- Segmented Navigation List --}}
                                    <div class="inline-flex items-center gap-1 p-1 bg-zinc-100 border border-zinc-200/50 rounded-lg max-w-full overflow-x-auto scrollbar-none w-full">
                                        @foreach($validSections as $key => $section)
                                            <button @click="activeTab = '{{ $key }}'" 
                                                    type="button"
                                                    :class="activeTab === '{{ $key }}' ? 'bg-white text-zinc-900 shadow-sm border border-zinc-200/50 font-bold' : 'text-zinc-500 hover:text-zinc-800 border border-transparent font-medium'"
                                                    class="flex-1 px-3 py-1.5 rounded-md text-[10px] uppercase tracking-wider transition-all whitespace-nowrap flex items-center justify-center gap-1.5">
                                                <i class="ph {{ $section['icon'] }} text-xs"></i>
                                                <span>{{ $section['title'] }}</span>
                                            </button>
                                        @endforeach
                                    </div>

                                    {{-- Tab Contents --}}
                                    @foreach($validSections as $key => $section)
                                        <div x-show="activeTab === '{{ $key }}'" x-transition.opacity.duration.150ms class="space-y-3.5">
                                            <div class="grid grid-cols-1 md:grid-cols-12 gap-3.5">
                                                {{-- Thinking Process --}}
                                                <div class="md:col-span-5 bg-zinc-50 rounded-lg p-3.5 border border-zinc-200/80">
                                                    <div class="flex items-center gap-1.5 mb-2 pb-1.5 border-b border-zinc-200">
                                                        <i class="ph ph-brain text-zinc-700 text-sm"></i>
                                                        <h4 class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Thinking Process</h4>
                                                    </div>
                                                    <div class="text-[11px] font-medium text-zinc-650 leading-relaxed italic formatted-content">
                                                        {{ $result[$key]['alasan_perubahan'] }}
                                                    </div>
                                                </div>

                                                {{-- Revised Output --}}
                                                <div class="md:col-span-7 bg-white border border-zinc-250 rounded-lg p-3.5 relative">
                                                    <div class="flex items-center justify-between mb-2 pb-1.5 border-b border-zinc-150">
                                                        <div class="flex items-center gap-1.5">
                                                            <i class="ph ph-sparkle text-purple-600 text-sm"></i>
                                                            <h4 class="text-[8px] font-mono font-bold text-zinc-500 uppercase tracking-wider">Optimized Output</h4>
                                                        </div>
                                                        <button onclick="copyToClipboard(this)" class="text-zinc-450 hover:text-zinc-800 transition-colors">
                                                            <i class="ph ph-copy text-base"></i>
                                                        </button>
                                                    </div>
                                                    <div class="text-[11px] font-semibold text-zinc-800 leading-relaxed formatted-content">
                                                        {{ $result[$key]['teks_revisi'] }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- MESSAGE 5: User Ask Roadmap --}}
                @if(isset($result['rencana_aksi']) && is_array($result['rencana_aksi']) && count($result['rencana_aksi']) > 0)
                    <div class="flex items-start justify-end gap-3">
                        <div class="flex flex-col items-end max-w-[85%]">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-[9px] font-mono font-bold text-zinc-400">You</span>
                                <span class="text-[9px] font-mono text-zinc-300">•</span>
                                <span class="text-[9px] font-mono text-zinc-400">Just Now</span>
                            </div>
                            <div class="bg-zinc-900 text-white text-xs font-medium rounded-lg rounded-tr-none px-4 py-2">
                                <p class="leading-relaxed">Apa rencana aksi yang disarankan untuk meningkatkan profil saya?</p>
                            </div>
                        </div>
                        @if($user && $user->logo)
                            <img src="{{ $user->avatar_url }}" alt="You" class="h-8 w-8 rounded-full object-cover shrink-0 border border-zinc-200">
                        @else
                            <div class="h-8 w-8 bg-zinc-100 border border-zinc-200 rounded-full flex items-center justify-center shrink-0">
                                <span class="text-zinc-700 font-bold text-xs">{{ substr($user->name ?? 'U', 0, 1) }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- MESSAGE 6: AI Roadmap Response --}}
                    <div class="flex items-start gap-3">
                        <div class="h-8 w-8 bg-purple-50 text-purple-650 border border-purple-150 rounded-full flex items-center justify-center shrink-0 shadow-sm">
                            <i class="ph ph-sparkle text-sm"></i>
                        </div>
                        <div class="flex flex-col items-start max-w-[85%] w-full">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-[9px] font-mono font-bold text-purple-750">TraKerja AI</span>
                                <span class="text-[9px] font-mono text-zinc-300">•</span>
                                <span class="text-[9px] font-mono text-zinc-400">Just Now</span>
                            </div>
                            
                            <div class="bg-white border border-zinc-200/80 rounded-lg rounded-tl-none p-4 shadow-sm w-full space-y-4">
                                <p class="text-xs text-zinc-650 leading-relaxed text-justify">Tentu! Berikut adalah rencana aksi langkah-demi-langkah (Roadmap to 95%+) untuk menyempurnakan profil Anda agar sangat menarik bagi rekruter:</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach($result['rencana_aksi'] as $index => $action)
                                        <div class="flex items-start gap-3 p-3 bg-zinc-50/50 border border-zinc-200 hover:bg-white rounded-lg hover:border-zinc-300 transition-colors">
                                            <div class="w-5 h-5 rounded-full bg-zinc-100 border border-zinc-250 flex items-center justify-center text-[10px] font-bold text-zinc-700 shrink-0 font-mono">
                                                {{ $index + 1 }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-xs font-semibold text-zinc-800 leading-relaxed text-justify">
                                                    {{ preg_replace('/^Langkah\s+\d+:\s*/i', '', $action) }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            {{-- Mock Sticky Chat Input (Bottom Action Hub) --}}
            <div class="fixed bottom-4 left-1/2 -translate-x-1/2 w-full max-w-lg px-4 z-50">
                <div class="bg-white border border-zinc-200 rounded-xl p-2 shadow-lg flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2 text-zinc-400 px-2 flex-1">
                        <i class="ph ph-chat-circle-text text-base"></i>
                        <span class="text-[10px] font-semibold text-zinc-400 truncate">Pilih aksi selanjutnya di kanan...</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('cv.builder') }}" 
                           class="px-4 py-1.5 bg-zinc-900 hover:bg-zinc-800 text-white text-[10px] font-bold uppercase tracking-wider rounded-md transition-colors flex items-center gap-1.5 shrink-0">
                            <i class="ph ph-pencil-simple text-sm"></i>
                            <span>Apply Changes</span>
                        </a>
                        <a href="{{ route('ai-analyzer.index') }}" 
                           class="px-3 py-1.5 bg-zinc-100 hover:bg-zinc-200 text-zinc-650 text-[10px] font-bold uppercase tracking-wider rounded-md transition-colors flex items-center gap-1 shrink-0">
                            <i class="ph ph-arrow-counter-clockwise text-xs"></i>
                            <span>Reset</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        .formatted-content { line-height: 1.6; text-align: justify; }
        .formatted-content strong { font-weight: 700; color: #18181b; }
        .scrollbar-none::-webkit-scrollbar { display: none; }
        .scrollbar-none { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <script>
        // Copy functionality
        function copyToClipboard(btn) {
            const content = btn.closest('div').nextElementSibling.innerText;
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
                    if (!line) return '<div class="h-1.5"></div>';
                    
                    line = line.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');
                    
                    if (line.startsWith('●') || line.startsWith('•') || line.startsWith('-')) {
                        return `<div class="flex items-start gap-1.5 mb-1"><span class="text-zinc-600 shrink-0 mt-1 font-bold">•</span><span>${line.substring(1).trim()}</span></div>`;
                    }
                    
                    const numMatch = line.match(/^\d+\.\s+(.+)/);
                    if (numMatch) {
                        return `<div class="flex items-start gap-2 mb-1"><span class="font-bold text-zinc-800 shrink-0 w-3.5 h-3.5 bg-zinc-200 rounded flex items-center justify-center text-[8px] font-mono">${line.split('.')[0]}</span><span>${numMatch[1]}</span></div>`;
                    }
                    
                    return `<p class="mb-1">${line}</p>`;
                });
                
                content.innerHTML = formatted.join('');
            });
        });
    </script>
</x-app-layout>