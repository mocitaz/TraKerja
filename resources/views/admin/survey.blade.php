<x-admin-layout>
    <div class="space-y-6 pb-12 w-full">
        
        {{-- Success Message --}}
        @if(session('success'))
            <div class="p-3.5 bg-emerald-50 border border-emerald-250 text-emerald-800 rounded-xl flex items-center gap-2.5 shadow-3xs">
                <i class="ph-bold ph-check-circle text-base text-emerald-650 shrink-0"></i>
                <p class="text-xs font-bold">{{ session('success') }}</p>
            </div>
        @endif
        
        <!-- Header Section (Sleek and Clean) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-3 border-b border-zinc-200">
            <div>
                <h1 class="text-lg font-black text-zinc-900 tracking-tight flex items-center gap-1.5">
                    Survey Dashboard <span class="text-[9px] font-mono font-bold text-zinc-450 bg-zinc-100 border border-zinc-200 px-1.5 py-0.2 rounded">Feedback Stats</span>
                </h1>
                <p class="text-[10px] text-zinc-500 font-semibold mt-0.5">Monitor user satisfaction metrics, service rating distributions, and toggle mandatory redirection.</p>
            </div>

            <!-- Survey Toggle Control in Header (Sleek & Space-saving) -->
            <div class="flex items-center gap-3 bg-zinc-50 border border-zinc-200 px-3 py-2 rounded-xl shadow-3xs shrink-0 self-start sm:self-center">
                <div class="text-left pl-1">
                    <span class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wider leading-none mb-1">Mandatory Survey</span>
                    <span class="inline-flex items-center text-[10px] font-bold uppercase tracking-wider {{ $surveyEnabled ? 'text-emerald-600' : 'text-zinc-450' }}">
                        @if($surveyEnabled)
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span> Active (Forced)
                        @else
                            <span class="w-1.5 h-1.5 rounded-full bg-zinc-350 mr-1.5"></span> Inactive
                        @endif
                    </span>
                </div>
                <div class="w-px h-6 bg-zinc-250 mx-1"></div>
                <form action="{{ route('admin.survey.toggle') }}" method="POST" class="m-0">
                    @csrf
                    <input type="hidden" name="enabled" value="{{ $surveyEnabled ? '0' : '1' }}">
                    <button type="submit" 
                            class="inline-flex items-center justify-center h-8 px-3 rounded-lg text-[10px] font-extrabold uppercase tracking-wide transition-all focus:outline-none shadow-3xs border {{ $surveyEnabled ? 'bg-zinc-900 hover:bg-zinc-800 border-zinc-950 text-white' : 'bg-white border-zinc-200 hover:bg-zinc-50 text-zinc-700' }}">
                        {{ $surveyEnabled ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                </form>
            </div>
        </div>

        {{-- Bento Grid: 5 Uniform Stat Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            
            {{-- Overall Score --}}
            <div class="border border-zinc-200 rounded-xl bg-white p-4 hover:border-zinc-300 transition-all flex flex-col justify-between group shadow-3xs text-left">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest block mb-0.5">Kepuasan</span>
                        <h3 class="text-xl font-extrabold tracking-tight text-zinc-900 font-mono leading-none flex items-baseline gap-0.5 mt-1">
                            {{ $avgQ1Overall }}
                            <span class="text-[9px] font-normal text-zinc-450">/5</span>
                        </h3>
                    </div>
                    <div class="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-950 group-hover:text-white transition duration-200 shrink-0 shadow-3xs">
                        <i class="ph-bold ph-heart text-xs"></i>
                    </div>
                </div>
                <p class="text-[10px] text-zinc-400 font-bold mt-5 uppercase tracking-wider text-[8px] font-mono">Overall Rating</p>
            </div>

            {{-- Ease of use Score --}}
            <div class="border border-zinc-200 rounded-xl bg-white p-4 hover:border-zinc-300 transition-all flex flex-col justify-between group shadow-3xs text-left">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest block mb-0.5">Kemudahan</span>
                        <h3 class="text-xl font-extrabold tracking-tight text-zinc-900 font-mono leading-none flex items-baseline gap-0.5 mt-1">
                            {{ $avgQ2Navigation }}
                            <span class="text-[9px] font-normal text-zinc-450">/5</span>
                        </h3>
                    </div>
                    <div class="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-950 group-hover:text-white transition duration-200 shrink-0 shadow-3xs">
                        <i class="ph-bold ph-navigation-arrow text-xs"></i>
                    </div>
                </div>
                <p class="text-[10px] text-zinc-400 font-bold mt-5 uppercase tracking-wider text-[8px] font-mono">Ease of Use</p>
            </div>

            {{-- Page Speed Score --}}
            <div class="border border-zinc-200 rounded-xl bg-white p-4 hover:border-zinc-300 transition-all flex flex-col justify-between group shadow-3xs text-left">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest block mb-0.5">Kecepatan</span>
                        <h3 class="text-xl font-extrabold tracking-tight text-zinc-900 font-mono leading-none flex items-baseline gap-0.5 mt-1">
                            {{ $avgQ3Speed }}
                            <span class="text-[9px] font-normal text-zinc-450">/5</span>
                        </h3>
                    </div>
                    <div class="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-950 group-hover:text-white transition duration-200 shrink-0 shadow-3xs">
                        <i class="ph-bold ph-lightning text-xs"></i>
                    </div>
                </div>
                <p class="text-[10px] text-zinc-400 font-bold mt-5 uppercase tracking-wider text-[8px] font-mono">Page Speed</p>
            </div>

            {{-- AI Features Score --}}
            <div class="border border-zinc-200 rounded-xl bg-white p-4 hover:border-zinc-300 transition-all flex flex-col justify-between group shadow-3xs text-left">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest block mb-0.5">Fitur AI</span>
                        <h3 class="text-xl font-extrabold tracking-tight text-zinc-900 font-mono leading-none flex items-baseline gap-0.5 mt-1">
                            {{ $avgQ5AiAnalyzer }}
                            <span class="text-[9px] font-normal text-zinc-450">/5</span>
                        </h3>
                    </div>
                    <div class="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-950 group-hover:text-white transition duration-200 shrink-0 shadow-3xs">
                        <i class="ph-bold ph-robot text-xs"></i>
                    </div>
                </div>
                <p class="text-[10px] text-zinc-400 font-bold mt-5 uppercase tracking-wider text-[8px] font-mono">AI Feature Help</p>
            </div>

            {{-- Total Respondents --}}
            <div class="border border-zinc-200 rounded-xl bg-white p-4 hover:border-zinc-300 transition-all flex flex-col justify-between group shadow-3xs text-left">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest block mb-0.5">Responden</span>
                        <h3 class="text-xl font-extrabold tracking-tight text-zinc-900 font-mono leading-none mt-1">
                            {{ number_format($totalRespondents) }}
                        </h3>
                    </div>
                    <div class="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-950 group-hover:text-white transition duration-200 shrink-0 shadow-3xs">
                        <i class="ph-bold ph-users text-xs"></i>
                    </div>
                </div>
                <p class="text-[10px] text-zinc-400 font-bold mt-5 uppercase tracking-wider text-[8px] font-mono">Total Responses</p>
            </div>
        </div>

        {{-- 12 Parameter Summary Card --}}
        <div class="bg-white rounded-xl border border-zinc-200 p-5 space-y-4 shadow-3xs text-left">
            <div class="flex items-center gap-1.5 pb-3 border-b border-zinc-100">
                <i class="ph-bold ph-sliders-horizontal text-zinc-800 text-sm"></i>
                <h3 class="text-xs font-bold text-zinc-900 tracking-tight">Rata-rata Skor per Parameter Layanan (12 Pertanyaan)</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3">
                @php
                    $metrics = [
                        ['label' => '1. Kepuasan Keseluruhan', 'val' => $avgQ1Overall],
                        ['label' => '2. Kemudahan Navigasi & Struktur Menu', 'val' => $avgQ2Navigation],
                        ['label' => '3. Kecepatan & Performa Halaman', 'val' => $avgQ3Speed],
                        ['label' => '4. Kualitas Resume / CV Builder', 'val' => $avgQ4CvBuilder],
                        ['label' => '5. Ketepatan Review AI Analyzer', 'val' => $avgQ5AiAnalyzer],
                        ['label' => '6. Efektivitas Pelacak Lamaran (Job Tracker)', 'val' => $avgQ6JobTracker],
                        ['label' => '7. AI Cover Letter Generator', 'val' => $avgQ7CoverLetter],
                        ['label' => '8. Manajemen & Penjadwalan Wawancara (Interviews)', 'val' => $avgQ8Interviews],
                        ['label' => '9. Nilai Ekonomis Layanan Premium', 'val' => $avgQ9Premium],
                        ['label' => '10. Tingkat Rekomendasi Layanan (NPS)', 'val' => $avgQ10Recommend],
                        ['label' => '11. Estetika & Desain Visual Halaman', 'val' => $avgQ11Design],
                        ['label' => '12. Desain & Kerapihan Template CV (CV Builder)', 'val' => $avgQ12CvTemplates],
                    ];
                @endphp
                @foreach($metrics as $m)
                    <div class="flex items-center justify-between text-xs py-1 border-b border-zinc-100 last:border-b-0">
                        <span class="text-zinc-650 font-semibold">{{ $m['label'] }}</span>
                        <div class="flex items-center gap-2">
                            <div class="w-24 bg-zinc-100 h-1.5 rounded-lg overflow-hidden shadow-3xs">
                                <div class="bg-zinc-900 h-1.5 rounded-lg" style="width: {{ ($m['val'] / 5) * 100 }}%"></div>
                            </div>
                            <span class="font-mono font-bold text-zinc-900 w-6 text-right">{{ $m['val'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Distribution & Detailed Feed Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            
            {{-- Distribution Chart Card (1/3 width) --}}
            <div class="bg-white rounded-xl border border-zinc-200 p-5 shadow-3xs text-left">
                <h3 class="text-xs font-bold text-zinc-900 mb-4 tracking-tight">Distribusi Skor Kepuasan (Q1)</h3>
                
                <div class="space-y-2.5">
                    @for($i = 5; $i >= 1; $i--)
                        @php 
                            $count = $distributions[$i] ?? 0;
                            $percentage = $totalRespondents > 0 ? ($count / $totalRespondents) * 100 : 0;
                        @endphp
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-bold text-zinc-500 font-mono w-3 text-right">{{ $i }}</span>
                            <i class="ph-bold ph-star text-amber-400 text-xs shrink-0"></i>
                            
                            <div class="flex-1 bg-zinc-100 rounded-lg h-1.5 overflow-hidden shadow-3xs">
                                <div class="bg-zinc-900 h-1.5 rounded-lg" style="width: {{ $percentage }}%"></div>
                            </div>
                            
                            <span class="text-[9px] font-mono font-bold text-zinc-450 w-8 text-right">{{ $count }}</span>
                        </div>
                    @endfor
                </div>
            </div>

            {{-- Feed Table (2/3 width) --}}
            <div class="lg:col-span-2 bg-white rounded-xl border border-zinc-200 overflow-hidden flex flex-col shadow-3xs">
                <div class="px-5 py-4 border-b border-zinc-100 bg-zinc-50/20 flex items-center justify-between">
                    <h3 class="text-xs font-bold text-zinc-900 tracking-tight flex items-center gap-1.5">
                        <i class="ph-bold ph-chats text-zinc-400 text-sm"></i>
                        Tanggapan Terbaru
                    </h3>
                    <span class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-widest">Real-time Feed</span>
                </div>

                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-zinc-50/50 border-b border-zinc-100 text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider">
                                <th class="py-2.5 px-4">User</th>
                                <th class="py-2.5 px-4 text-center" title="Q1: Kepuasan">Q1</th>
                                <th class="py-2.5 px-4 text-center" title="Q2: Navigasi">Q2</th>
                                <th class="py-2.5 px-4 text-center" title="Q3: Kecepatan">Q3</th>
                                <th class="py-2.5 px-4 text-center" title="Q5: AI Analyzer">Q5</th>
                                <th class="py-2.5 px-4 text-center" title="Q11: Desain">Q11</th>
                                <th class="py-2.5 px-4">Saran / Masukan</th>
                                <th class="py-2.5 px-4 text-right">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 text-xs text-zinc-800">
                            @forelse($responses as $response)
                                <tr class="hover:bg-[#f7f7f5]/80 transition-colors text-xs text-zinc-700 font-medium">
                                    <td class="py-2.5 px-4 text-left">
                                        <p class="font-bold text-zinc-950">{{ $response->user->name ?? 'Deleted User' }}</p>
                                        <p class="text-[10px] text-zinc-400 font-mono mt-0.5">{{ $response->user->email ?? 'N/A' }}</p>
                                    </td>
                                    <td class="py-2.5 px-4 text-center font-mono font-bold text-zinc-800">{{ $response->q1_overall }}</td>
                                    <td class="py-2.5 px-4 text-center font-mono font-bold text-zinc-800">{{ $response->q2_navigation }}</td>
                                    <td class="py-2.5 px-4 text-center font-mono font-bold text-zinc-800">{{ $response->q3_speed }}</td>
                                    <td class="py-2.5 px-4 text-center font-mono font-bold text-zinc-800">{{ $response->q5_ai_analyzer }}</td>
                                    <td class="py-2.5 px-4 text-center font-mono font-bold text-zinc-800">{{ $response->q11_design }}</td>
                                    <td class="py-2.5 px-4 text-left">
                                        @if($response->feedback)
                                            <p class="max-w-[200px] truncate" title="{{ $response->feedback }}">{{ $response->feedback }}</p>
                                        @else
                                            <span class="text-zinc-400 italic text-[10px]">No comment</span>
                                        @endif
                                    </td>
                                    <td class="py-2.5 px-4 text-right text-[10px] text-zinc-400 whitespace-nowrap">
                                        {{ $response->created_at->format('d M Y H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-8 text-center text-zinc-400">
                                        <i class="ph-bold ph-chat-circle-slash text-xl mb-1.5 block mx-auto text-zinc-300"></i>
                                        <span class="text-xs font-bold text-zinc-800 block">Belum ada respon survey</span>
                                        <span class="text-[9px] text-zinc-400 block mt-0.5">Tanggapan akan muncul di sini setelah survey diisi.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($responses->hasPages())
                    <div class="p-3 border-t border-zinc-100 notion-pagination">
                        {{ $responses->links() }}
                    </div>
                @endif
            </div>

        </div>

    </div>
</x-admin-layout>
