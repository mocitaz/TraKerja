<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Cover Letter') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">Surat Lamaran Tersimpan</h3>
                        <a href="{{ route('cover-letters.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg text-sm transition-colors">
                            + Buat Baru
                        </a>
                    </div>

                    @if($coverLetters->isEmpty())
                        <div class="text-center py-8">
                            <div class="text-slate-400 mb-2">
                                <i class="ph-fill ph-file-text text-4xl"></i>
                            </div>
                            <p class="text-gray-500">Belum ada riwayat cover letter.</p>
                            <p class="text-sm text-gray-400 mt-1">Gunakan AI untuk membuat surat lamaran profesional pertama Anda.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($coverLetters as $letter)
                                <a href="{{ route('cover-letters.show', $letter->id) }}" class="block p-5 border border-slate-200 rounded-xl hover:border-blue-300 hover:shadow-md transition-all group bg-slate-50 hover:bg-white">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex-grow">
                                            <h4 class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors line-clamp-1">{{ $letter->job_title }}</h4>
                                            <p class="text-sm text-slate-500">{{ $letter->company_name }}</p>
                                        </div>
                                        <div class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-1 rounded">
                                            {{ ucfirst($letter->tone) }}
                                        </div>
                                    </div>
                                    <p class="text-xs text-slate-400 mt-4 flex items-center gap-1">
                                        <i class="ph-regular ph-clock"></i>
                                        {{ $letter->created_at->diffForHumans() }}
                                    </p>
                                </a>
                            @endforeach
                        </div>
                        
                        <div class="mt-6">
                            {{ $coverLetters->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
