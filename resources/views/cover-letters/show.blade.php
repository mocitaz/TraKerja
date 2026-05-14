<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('cover-letters.history') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="ph-bold ph-arrow-left text-xl"></i>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Detail Cover Letter') }}
                </h2>
            </div>
            <button onclick="copyToClipboard()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2 px-4 rounded-lg text-sm transition-colors flex items-center gap-2">
                <i class="ph-bold ph-copy"></i>
                Salin Teks
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-100">
                <div class="p-8">
                    <div class="border-b border-slate-100 pb-6 mb-6">
                        <h1 class="text-2xl font-black text-slate-900 mb-2">{{ $coverLetter->job_title }}</h1>
                        <p class="text-lg font-medium text-slate-600 flex items-center gap-2">
                            <i class="ph-fill ph-buildings text-blue-500"></i> {{ $coverLetter->company_name }}
                        </p>
                        
                        <div class="flex flex-wrap gap-2 mt-4">
                            <span class="bg-blue-50 text-blue-700 border border-blue-100 px-2.5 py-1 rounded-md text-xs font-bold uppercase tracking-wider">
                                {{ $coverLetter->tone }} Tone
                            </span>
                            <span class="bg-slate-50 text-slate-600 border border-slate-200 px-2.5 py-1 rounded-md text-xs font-bold uppercase tracking-wider">
                                {{ strtoupper($coverLetter->language) }}
                            </span>
                            <span class="text-slate-400 text-sm ml-auto flex items-center gap-1">
                                <i class="ph-regular ph-calendar"></i>
                                {{ $coverLetter->created_at->format('d M Y') }}
                            </span>
                        </div>
                    </div>

                    <div class="prose prose-slate max-w-none">
                        <div class="bg-slate-50 border border-slate-100 rounded-xl p-6 text-slate-800 whitespace-pre-wrap font-serif text-[15px] leading-relaxed shadow-inner" id="letter-content">{{ $coverLetter->content }}</div>
                    </div>
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
