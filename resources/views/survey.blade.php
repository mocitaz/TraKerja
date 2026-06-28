<x-app-layout>
    <div class="bg-[#f0f4f9] min-h-screen pb-20">
        <div class="max-w-[770px] mx-auto px-4 pt-6 space-y-3.5">
            
            @if($hasCompleted)
                {{-- THANK YOU STATE --}}
                <div class="bg-white border border-zinc-200 rounded-lg shadow-sm overflow-hidden mt-10">
                    <div class="h-2 bg-primary-500"></div>
                    <div class="p-6 sm:p-8 text-center space-y-5 max-w-md mx-auto">
                        <div class="w-14 h-14 bg-emerald-50 border border-emerald-100 rounded-full flex items-center justify-center text-emerald-600 mx-auto shadow-2xs">
                            <i class="ph-bold ph-heart text-2xl animate-pulse"></i>
                        </div>
                        
                        <div class="space-y-2">
                            <h2 class="text-base font-bold text-zinc-900 tracking-tight">Terima Kasih Banyak!</h2>
                            <p class="text-xs text-zinc-500 leading-relaxed">
                                Tanggapan dan masukan berharga Anda telah berhasil kami simpan. Umpan balik dari Anda sangat membantu kami dalam menyempurnakan fitur-fitur TraKerja agar menjadi lebih baik.
                            </p>
                        </div>

                        <div class="pt-2">
                            <a href="{{ route('tracker') }}" class="inline-flex items-center justify-center w-full py-2 bg-zinc-900 hover:bg-zinc-800 text-white rounded-md text-xs font-bold uppercase tracking-wider transition-colors">
                                Lanjut ke Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            @else
                {{-- SURVEY FORM STATE --}}
                <form action="{{ route('survey.submit') }}" method="POST" class="space-y-3.5">
                    @csrf

                    {{-- Card 1: Form Header (Google Forms Style) --}}
                    <div class="bg-white border border-zinc-200 rounded-lg shadow-2xs overflow-hidden">
                        <div class="h-2.5 bg-primary-500"></div>
                        <div class="p-5 sm:p-6 space-y-3">
                            <h1 class="text-xl sm:text-2xl font-bold text-zinc-900 tracking-tight">Evaluasi & Kepuasan Layanan TraKerja</h1>
                            <p class="text-xs text-zinc-650 leading-relaxed text-justify">
                                Tanggapan Anda akan membantu kami terus meningkatkan dan mempersonalisasi fitur-fitur di TraKerja. Mohon luangkan waktu sebentar untuk menjawab 11 pertanyaan di bawah ini.
                            </p>
                            <div class="pt-2 border-t border-zinc-100 flex items-center gap-1 text-[10px] text-red-600 font-semibold font-mono">
                                <span>*</span>
                                <span>Menunjukkan pertanyaan yang wajib diisi</span>
                            </div>
                        </div>
                    </div>

                    {{-- Warning Banner Card --}}
                    <div class="bg-amber-50/70 border border-amber-200 rounded-lg p-3.5 flex items-start gap-3 shadow-2xs">
                        <i class="ph-bold ph-warning-circle text-base text-amber-600 shrink-0 mt-0.5"></i>
                        <div class="text-[11px] text-amber-800 leading-relaxed">
                            <p class="font-bold">Akses Terbatas Sementara</p>
                            <p class="mt-0.5">Akun Anda terpilih untuk memberikan umpan balik berkala. Silakan selesaikan survey ini untuk mengaktifkan kembali akses penuh ke seluruh fitur Job Tracker dan AI Studio.</p>
                        </div>
                    </div>

                    {{-- Dynamic Question Cards --}}
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
                        <div class="bg-white border border-zinc-200 rounded-lg p-5 space-y-3.5 shadow-2xs">
                            {{-- Title & Desc --}}
                            <div class="space-y-1">
                                <h3 class="text-xs sm:text-sm font-bold text-zinc-900 leading-snug">
                                    {{ $q['num'] }}. {{ $q['title'] }} <span class="text-red-500 font-mono ml-0.5">*</span>
                                </h3>
                                <p class="text-[11px] text-zinc-500 leading-relaxed">{{ $q['desc'] }}</p>
                            </div>
                            
                            {{-- GForm Linear Scale Row --}}
                            <div class="flex items-center justify-between gap-4 py-3 px-2 overflow-x-auto border border-zinc-100 rounded-lg bg-zinc-50/20">
                                <span class="text-[10px] sm:text-xs text-zinc-500 font-medium max-w-[80px] sm:max-w-[100px] leading-tight shrink-0">{{ $q['low'] }}</span>
                                
                                <div class="flex items-center justify-center gap-5 sm:gap-7 flex-1 min-w-[200px]">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="flex flex-col items-center gap-1.5 cursor-pointer group">
                                            <span class="text-[10px] font-bold text-zinc-400 group-hover:text-zinc-800 transition-colors font-mono">{{ $i }}</span>
                                            <input type="radio" name="{{ $key }}" value="{{ $i }}" class="w-4 h-4 text-zinc-900 border-zinc-300 focus:ring-zinc-950 focus:ring-offset-1 cursor-pointer" required {{ old($key) == $i ? 'checked' : '' }}>
                                        </label>
                                    @endfor
                                </div>
                                
                                <span class="text-[10px] sm:text-xs text-zinc-500 font-medium max-w-[80px] sm:max-w-[100px] leading-tight text-right shrink-0">{{ $q['high'] }}</span>
                            </div>
                            
                            @error($key)
                                <p class="text-[10px] text-red-600 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach

                    {{-- Written Feedback Card --}}
                    <div class="bg-white border border-zinc-200 rounded-lg p-5 space-y-3.5 shadow-2xs">
                        <div class="space-y-1">
                            <h3 class="text-xs sm:text-sm font-bold text-zinc-900 leading-snug">
                                12. Saran, Kritik, atau Fitur Tambahan (Opsional)
                            </h3>
                            <p class="text-[11px] text-zinc-550 leading-relaxed">Berikan saran spesifik mengenai hal-hal yang perlu kami tingkatkan pada platform ini.</p>
                        </div>
                        <div class="pt-1.5">
                            <textarea name="feedback" rows="4" placeholder="Tuliskan jawaban Anda di sini..."
                                      class="w-full px-3 py-2 bg-zinc-50/50 border border-zinc-200 focus:bg-white focus:ring-1 focus:ring-zinc-900 focus:border-zinc-900 rounded-md text-xs text-zinc-850 transition-all">{{ old('feedback') }}</textarea>
                            @error('feedback')
                                <p class="text-[10px] text-red-600 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Action Row Card --}}
                    <div class="flex items-center justify-between pt-2">
                        <button type="submit" class="px-6 py-2 bg-primary-500 hover:bg-primary-650 text-white rounded-md text-[10px] font-bold uppercase tracking-wider transition-colors shadow-2xs">
                            Kirim Tanggapan
                        </button>
                        <button type="reset" class="px-4 py-2 text-zinc-500 hover:text-zinc-800 text-[10px] font-bold uppercase tracking-wider transition-colors">
                            Batal
                        </button>
                    </div>
                </form>
            @endif

        </div>
    </div>
</x-app-layout>
