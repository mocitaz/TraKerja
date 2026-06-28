<x-app-layout>
    <div class="bg-[#fafafa] min-h-screen pb-20">
        <div class="max-w-[850px] mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            
            {{-- Standard Page Header --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-zinc-200/50 pb-4 mb-6">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-purple-50 border border-purple-100 rounded-lg flex items-center justify-center text-purple-650 shrink-0 shadow-2xs">
                        <i class="ph ph-heart text-base"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-sm font-bold text-zinc-800 tracking-tight">Evaluasi & Kepuasan Layanan</h1>
                            <span class="px-1.5 py-0.5 bg-purple-50 text-purple-700 text-[8px] font-black uppercase tracking-wider rounded border border-purple-100">Feedback</span>
                        </div>
                        <p class="text-[11px] text-zinc-400 mt-0.5">Tanggapan Anda akan membantu kami terus meningkatkan dan mempersonalisasi fitur TraKerja.</p>
                    </div>
                </div>
            </div>

            @if($hasCompleted)
                {{-- THANK YOU STATE --}}
                <div class="bg-white border border-zinc-200/80 rounded-lg shadow-sm p-8 text-center space-y-5 max-w-md mx-auto mt-10">
                    <div class="w-14 h-14 bg-emerald-50 border border-emerald-100 rounded-full flex items-center justify-center text-emerald-600 mx-auto shadow-2xs">
                        <i class="ph-bold ph-heart text-2xl animate-pulse"></i>
                    </div>
                    
                    <div class="space-y-2">
                        <h2 class="text-base font-bold text-zinc-900 tracking-tight">Terima Kasih Banyak!</h2>
                        <p class="text-xs text-zinc-500 leading-relaxed max-w-xs mx-auto">
                            Tanggapan dan masukan berharga Anda telah berhasil kami simpan. Umpan balik dari Anda sangat membantu kami dalam menyempurnakan fitur-fitur TraKerja agar menjadi lebih baik.
                        </p>
                    </div>

                    <div class="pt-2">
                        <a href="{{ route('tracker') }}" class="inline-flex items-center justify-center w-full py-2 bg-zinc-900 hover:bg-zinc-800 text-white rounded-md text-xs font-bold uppercase tracking-wider transition-colors">
                            Lanjut ke Dashboard
                        </a>
                    </div>
                </div>
            @else
                {{-- SURVEY FORM STATE --}}
                {{-- Warning Banner --}}
                <div class="mb-6 p-3 bg-amber-50 border border-amber-200 rounded-lg flex items-start gap-2.5">
                    <i class="ph-bold ph-warning-circle text-base text-amber-600 shrink-0 mt-0.5"></i>
                    <div class="text-[11px] text-amber-800 leading-relaxed">
                        <p class="font-bold">Akses Terbatas Sementara</p>
                        <p class="mt-0.5">Akun Anda terpilih untuk memberikan umpan balik berkala. Silakan lengkapi survey 11 pertanyaan di bawah ini untuk mengaktifkan kembali akses penuh ke seluruh fitur Job Tracker dan AI Studio.</p>
                    </div>
                </div>

                {{-- Survey Form --}}
                <form action="{{ route('survey.submit') }}" method="POST" class="bg-white border border-zinc-200/80 rounded-lg shadow-sm overflow-hidden">
                    @csrf
                    
                    {{-- Form Body --}}
                    <div class="p-5 sm:p-6 space-y-6 divide-y divide-zinc-150">
                        
                        @php
                            $questions = [
                                'q1_overall' => [
                                    'num' => 1,
                                    'title' => 'Kepuasan Keseluruhan',
                                    'desc' => 'Seberapa puas Anda dengan layanan TraKerja secara keseluruhan?',
                                    'low' => 'Sangat Tidak Puas',
                                    'high' => 'Sangat Puas'
                                ],
                                'q2_navigation' => [
                                    'num' => 2,
                                    'title' => 'Kemudahan Navigasi & Struktur Menu',
                                    'desc' => 'Seberapa mudah Anda bernavigasi menggunakan sidebar dan menu di TraKerja?',
                                    'low' => 'Sangat Sulit',
                                    'high' => 'Sangat Mudah'
                                ],
                                'q3_speed' => [
                                    'num' => 3,
                                    'title' => 'Kecepatan & Performa Halaman',
                                    'desc' => 'Bagaimana penilaian Anda terhadap kecepatan waktu muat halaman?',
                                    'low' => 'Sangat Lambat',
                                    'high' => 'Sangat Cepat'
                                ],
                                'q4_cv_builder' => [
                                    'num' => 4,
                                    'title' => 'Kualitas Resume / CV Builder',
                                    'desc' => 'Seberapa terbantu Anda dengan fitur pembuat resume (CV Builder)?',
                                    'low' => 'Tidak Membantu',
                                    'high' => 'Sangat Membantu'
                                ],
                                'q5_ai_analyzer' => [
                                    'num' => 5,
                                    'title' => 'Ketepatan Review AI Analyzer',
                                    'desc' => 'Bagaimana Anda menilai kegunaan AI Analyzer dalam mereview resume?',
                                    'low' => 'Tidak Berguna',
                                    'high' => 'Sangat Berguna'
                                ],
                                'q6_job_tracker' => [
                                    'num' => 6,
                                    'title' => 'Efektivitas Pelacak Lamaran (Job Tracker)',
                                    'desc' => 'Bagaimana kemudahan mengelola & memperbarui status lamaran kerja Anda?',
                                    'low' => 'Sangat Sulit',
                                    'high' => 'Sangat Mudah'
                                ],
                                'q7_cover_letter' => [
                                    'num' => 7,
                                    'title' => 'AI Cover Letter Generator',
                                    'desc' => 'Seberapa puas Anda dengan kualitas surat lamaran yang dihasilkan AI?',
                                    'low' => 'Tidak Puas',
                                    'high' => 'Sangat Puas'
                                ],
                                'q8_summary' => [
                                    'num' => 8,
                                    'title' => 'Visualisasi Statistik (Halaman Summary)',
                                    'desc' => 'Seberapa informatif visualisasi data ringkasan lamaran kerja Anda?',
                                    'low' => 'Tidak Informatif',
                                    'high' => 'Sangat Informatif'
                                ],
                                'q9_premium' => [
                                    'num' => 9,
                                    'title' => 'Nilai Ekonomis Layanan Premium',
                                    'desc' => 'Bagaimana Anda menilai kesesuaian harga premium dengan fitur yang didapat?',
                                    'low' => 'Sangat Buruk',
                                    'high' => 'Sangat Baik'
                                ],
                                'q10_recommend' => [
                                    'num' => 10,
                                    'title' => 'Tingkat Rekomendasi (Net Promoter Score)',
                                    'desc' => 'Seberapa besar kemungkinan Anda merekomendasikan TraKerja ke rekan Anda?',
                                    'low' => 'Sangat Kecil',
                                    'high' => 'Sangat Besar'
                                ],
                                'q11_design' => [
                                    'num' => 11,
                                    'title' => 'Estetika & Desain Visual Halaman',
                                    'desc' => 'Bagaimana penilaian Anda terhadap aspek estetika visual (Notion-Cupertino style)?',
                                    'low' => 'Sangat Buruk',
                                    'high' => 'Sangat Estetis'
                                ]
                            ];
                        @endphp

                        @foreach($questions as $key => $q)
                            <div class="space-y-2.5 {{ $q['num'] > 1 ? 'pt-5' : '' }}">
                                <div class="flex items-start gap-2.5">
                                    <span class="w-5 h-5 rounded-full bg-zinc-100 border border-zinc-250 flex items-center justify-center text-[10px] font-bold text-zinc-700 shrink-0 font-mono">
                                        {{ $q['num'] }}
                                    </span>
                                    <div class="space-y-0.5">
                                        <h3 class="text-xs font-bold text-zinc-800">{{ $q['title'] }}</h3>
                                        <p class="text-[11px] text-zinc-400 leading-relaxed">{{ $q['desc'] }}</p>
                                    </div>
                                </div>
                                
                                <div class="max-w-md ml-7">
                                    <div class="flex items-center justify-between gap-1.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label class="flex-1">
                                                <input type="radio" name="{{ $key }}" value="{{ $i }}" class="sr-only peer" required {{ old($key) == $i ? 'checked' : '' }}>
                                                <div class="py-2 text-center rounded border border-zinc-200 text-xs font-mono font-bold text-zinc-500 bg-zinc-50 hover:bg-zinc-100 peer-checked:bg-zinc-900 peer-checked:text-white peer-checked:border-zinc-900 transition-all cursor-pointer">
                                                    {{ $i }}
                                                </div>
                                            </label>
                                        @endfor
                                    </div>
                                    <div class="flex justify-between text-[9px] font-mono text-zinc-400 px-1 pt-0.5">
                                        <span>{{ $q['low'] }}</span>
                                        <span>{{ $q['high'] }}</span>
                                    </div>
                                    @error($key)
                                        <p class="text-[10px] text-red-600 font-medium mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        @endforeach

                        {{-- Written Feedback --}}
                        <div class="space-y-2.5 pt-5">
                            <div class="flex items-start gap-2.5">
                                <span class="w-5 h-5 rounded-full bg-zinc-100 border border-zinc-250 flex items-center justify-center text-[10px] font-bold text-zinc-700 shrink-0 font-mono">
                                    12
                                </span>
                                <div class="space-y-0.5">
                                    <h3 class="text-xs font-bold text-zinc-800">Saran, Kritik, atau Fitur Tambahan (Opsional)</h3>
                                    <p class="text-[11px] text-zinc-400 leading-relaxed">Berikan saran spesifik mengenai hal-hal yang perlu kami tingkatkan pada platform ini.</p>
                                </div>
                            </div>
                            <div class="ml-7 max-w-lg">
                                <textarea name="feedback" rows="4" placeholder="Tuliskan saran Anda di sini..."
                                          class="w-full px-3 py-2 bg-zinc-50 border border-zinc-250 focus:bg-white focus:ring-1 focus:ring-zinc-900 focus:border-zinc-900 rounded-md text-xs text-zinc-850 transition-all">{{ old('feedback') }}</textarea>
                                @error('feedback')
                                    <p class="text-[10px] text-red-600 font-medium mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                    </div>

                    {{-- Form Footer Actions --}}
                    <div class="bg-zinc-50/50 border-t border-zinc-150 px-5 py-4 flex items-center justify-end">
                        <button type="submit" class="px-5 py-2 bg-zinc-900 hover:bg-zinc-800 text-white rounded-md text-[10px] font-bold uppercase tracking-wider transition-colors">
                            Kirim Tanggapan Survey
                        </button>
                    </div>
                </form>
            @endif

        </div>
    </div>
</x-app-layout>
