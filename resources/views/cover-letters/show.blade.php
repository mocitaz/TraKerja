<x-app-layout>
    <div class="bg-[#fafafa] min-h-screen pb-16 font-sans">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            
            <!-- Premium Notion-Inspired Page Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-slate-200/60 pb-5 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-tr from-primary-50 to-primary-100/50 rounded-xl flex items-center justify-center text-primary-600 shrink-0 border border-primary-100/50 shadow-inner">
                        <i class="ph ph-envelope text-lg"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-base font-bold text-slate-800 tracking-tight">Cover Letter Detail</h1>
                            <span class="px-2 py-0.5 bg-primary-50 text-primary-600 text-[9px] font-black uppercase tracking-wider rounded-md border border-primary-100/60">AI Studio</span>
                        </div>
                        <p class="text-xs text-slate-455 mt-0.5">Read, copy, or print your generated cover letter.</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button onclick="copyToClipboard()" class="px-3.5 py-1.5 bg-slate-900 text-white hover:bg-slate-800 text-xs font-semibold rounded-lg shadow-sm transition-colors flex items-center gap-1.5">
                        <i class="ph ph-copy text-sm"></i>
                        <span>Copy Text</span>
                    </button>
                    <a href="{{ route('ai-studio.index', ['tab' => 'cover-letter']) }}" class="px-3.5 py-1.5 bg-white text-slate-700 border border-slate-200 hover:bg-slate-50 text-xs font-semibold rounded-lg shadow-sm transition-colors flex items-center gap-1.5">
                        <i class="ph ph-arrow-left text-sm"></i>
                        <span>Back to Studio</span>
                    </a>
                </div>
            </div>

            <div class="bg-white border border-slate-200/70 rounded-xl p-5 shadow-sm">
                <div class="border-b border-slate-150/60 pb-4 mb-4">
                    <h1 class="text-base font-bold text-slate-850 mb-1">{{ $coverLetter->job_title }}</h1>
                    <p class="text-xs font-semibold text-slate-500 flex items-center gap-1.5">
                        <i class="ph ph-buildings text-sm text-slate-400"></i> {{ $coverLetter->company_name }}
                    </p>
                    
                    <div class="flex flex-wrap items-center gap-2 mt-3">
                        <span class="bg-slate-50 text-slate-650 border border-slate-200 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider">
                            {{ $coverLetter->tone }} Tone
                        </span>
                        <span class="bg-slate-50 text-slate-650 border border-slate-200 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider">
                            {{ strtoupper($coverLetter->language) }}
                        </span>
                        <span class="text-slate-400 text-xs ml-auto flex items-center gap-1">
                            <i class="ph ph-calendar"></i>
                            {{ $coverLetter->created_at->format('d M Y') }}
                        </span>
                    </div>
                </div>

                <div class="prose prose-slate max-w-none">
                    <div class="bg-slate-50/50 border border-slate-200 rounded-lg p-4 text-slate-800 whitespace-pre-wrap font-serif text-sm leading-relaxed" id="letter-content">{{ $coverLetter->content }}</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard() {
            const content = document.getElementById('letter-content').innerText;
            navigator.clipboard.writeText(content).then(() => {
                alert('Cover letter berhasil disalin ke clipboard!');
            }).catch(err => {
                console.error('Failed to copy: ', err);
                alert('Gagal menyalin teks.');
            });
        }
    </script>
</x-app-layout>
