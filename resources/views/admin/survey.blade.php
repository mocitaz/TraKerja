<x-admin-layout>
    <div class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-5 space-y-4 pb-10">
        
        {{-- Success Message --}}
        @if(session('success'))
            <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded flex items-center gap-2.5 shadow-none">
                <i class="ph ph-check-circle text-base text-emerald-650 shrink-0"></i>
                <p class="text-xs font-semibold">{{ session('success') }}</p>
            </div>
        @endif
        
        <!-- Sticky Global Sub-Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3.5 border-b border-zinc-150/60">
            <div class="flex items-center gap-1.5 min-w-0">
                <span class="text-xs font-mono font-bold text-zinc-400 uppercase tracking-wider">Admin Portal</span>
                <span class="text-zinc-300 text-xs">/</span>
                <h1 class="text-xs font-mono font-bold text-zinc-800 uppercase tracking-wider">Survey &amp; Feedbacks</h1>
            </div>
        </div>

        {{-- Bento Grid: Survey Toggle & Statistics --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            
            {{-- Control Toggle (1/3 width) --}}
            <div class="bg-white rounded border border-zinc-200/60 p-4 flex flex-col justify-between shadow-none text-left">
                <div>
                    <div class="flex items-center gap-2.5 mb-3">
                        <div class="w-7 h-7 bg-zinc-50 border border-zinc-200/40 rounded flex items-center justify-center shrink-0 text-zinc-500">
                            <i class="ph ph-toggle-left text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-zinc-900 leading-none">Survey Active Status</h3>
                            <p class="text-[8px] font-mono font-bold text-zinc-400 mt-1 uppercase tracking-wide">Control forced redirection</p>
                        </div>
                    </div>
                    <p class="text-[11px] text-zinc-500 leading-relaxed font-sans">Saat diaktifkan, pengguna biasa yang umur akunnya sudah melebihi 3 hari akan dipaksa mengisi kuisioner kepuasan sebelum diizinkan mengakses dashboard tracker mereka.</p>
                </div>

                <div class="pt-3 border-t border-zinc-150/60 mt-3 flex items-center justify-between">
                    <span class="text-[10px] font-mono font-bold uppercase tracking-wider {{ $surveyEnabled ? 'text-purple-600' : 'text-zinc-450' }}">
                        {{ $surveyEnabled ? 'Active (Forced)' : 'Inactive' }}
                    </span>
                    <form action="{{ route('admin.survey.toggle') }}" method="POST">
                        @csrf
                        <input type="hidden" name="enabled" value="{{ $surveyEnabled ? '0' : '1' }}">
                        <button type="submit" 
                                class="inline-flex items-center justify-center h-8 px-3.5 rounded text-[10px] font-bold uppercase tracking-wide transition-colors focus:outline-none shadow-none {{ $surveyEnabled ? 'bg-zinc-900 text-white hover:bg-zinc-800' : 'bg-zinc-50 border border-zinc-250 hover:bg-zinc-100 text-zinc-700' }}">
                            {{ $surveyEnabled ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                </div>
            </div>

            {{-- Stat Cards (2/3 width) --}}
            <div class="lg:col-span-2 grid grid-cols-2 sm:grid-cols-4 gap-4">
                {{-- Overall Score --}}
                <div class="border border-zinc-200/60 rounded bg-white p-4 shadow-none flex flex-col justify-between h-[82px] hover:bg-[#f7f7f5]/40 transition-colors text-left">
                    <div class="flex items-center justify-between w-full">
                        <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Kepuasan</span>
                        <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200/40 text-zinc-500 flex items-center justify-center shrink-0">
                            <i class="ph ph-heart text-xs"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline justify-between mt-1">
                        <p class="text-xl font-bold tracking-tight text-zinc-900 leading-none">{{ $avgQ1Overall }} <span class="text-[10px] font-normal text-zinc-400">/ 5</span></p>
                        <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wide leading-none">Overall</p>
                    </div>
                </div>

                {{-- Ease of use Score --}}
                <div class="border border-zinc-200/60 rounded bg-white p-4 shadow-none flex flex-col justify-between h-[82px] hover:bg-[#f7f7f5]/40 transition-colors text-left">
                    <div class="flex items-center justify-between w-full">
                        <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Kemudahan</span>
                        <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200/40 text-zinc-500 flex items-center justify-center shrink-0">
                            <i class="ph ph-navigation-arrow text-xs"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline justify-between mt-1">
                        <p class="text-xl font-bold tracking-tight text-zinc-900 leading-none">{{ $avgQ2Navigation }} <span class="text-[10px] font-normal text-zinc-400">/ 5</span></p>
                        <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wide leading-none">Ease of Use</p>
                    </div>
                </div>

                {{-- AI Features Score --}}
                <div class="border border-zinc-200/60 rounded bg-white p-4 shadow-none flex flex-col justify-between h-[82px] hover:bg-[#f7f7f5]/40 transition-colors text-left">
                    <div class="flex items-center justify-between w-full">
                        <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Fitur AI</span>
                        <div class="w-6 h-6 rounded bg-purple-50 border border-purple-100/45 text-purple-650 flex items-center justify-center shrink-0">
                            <i class="ph ph-robot text-xs"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline justify-between mt-1">
                        <p class="text-xl font-bold tracking-tight text-zinc-900 leading-none">{{ $avgQ5AiAnalyzer }} <span class="text-[10px] font-normal text-zinc-400">/ 5</span></p>
                        <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wide leading-none">AI Help</p>
                    </div>
                </div>

                {{-- Total Respondents --}}
                <div class="border border-zinc-200/60 rounded bg-white p-4 shadow-none flex flex-col justify-between h-[82px] hover:bg-[#f7f7f5]/40 transition-colors text-left">
                    <div class="flex items-center justify-between w-full">
                        <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Responden</span>
                        <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200/40 text-zinc-500 flex items-center justify-center shrink-0">
                            <i class="ph ph-users text-xs"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline justify-between mt-1">
                        <p class="text-xl font-bold tracking-tight text-zinc-900 leading-none">{{ number_format($totalRespondents) }}</p>
                        <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wide leading-none">Responses</p>
                    </div>
                </div>
            </div>

        </div>

        {{-- 12 Parameter Summary Card --}}
        <div class="bg-white rounded border border-zinc-200/60 p-4 space-y-3 shadow-none text-left">
            <div class="flex items-center gap-1.5 pb-2 border-b border-zinc-150/60">
                <i class="ph ph-sliders text-zinc-800 text-sm"></i>
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
                    <div class="flex items-center justify-between text-xs py-1 border-b border-zinc-150/30 last:border-b-0">
                        <span class="text-zinc-650 font-medium">{{ $m['label'] }}</span>
                        <div class="flex items-center gap-2">
                            <div class="w-24 bg-zinc-100 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-purple-650 h-1.5 rounded-full" style="width: {{ ($m['val'] / 5) * 100 }}%"></div>
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
            <div class="bg-white rounded border border-zinc-200/60 p-4 shadow-none text-left">
                <h3 class="text-xs font-bold text-zinc-900 mb-4 tracking-tight">Distribusi Skor Kepuasan (Q1)</h3>
                
                <div class="space-y-2.5">
                    @for($i = 5; $i >= 1; $i--)
                        @php 
                            $count = $distributions[$i] ?? 0;
                            $percentage = $totalRespondents > 0 ? ($count / $totalRespondents) * 100 : 0;
                        @endphp
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-bold text-zinc-500 font-mono w-3 text-right">{{ $i }}</span>
                            <i class="ph ph-star text-amber-400 text-xs shrink-0"></i>
                            
                            <div class="flex-1 bg-zinc-100 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-purple-650 h-1.5 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                            
                            <span class="text-[9px] font-mono font-bold text-zinc-450 w-8 text-right">{{ $count }}</span>
                        </div>
                    @endfor
                </div>
            </div>

            {{-- Feed Table (2/3 width) --}}
            <div class="lg:col-span-2 bg-white rounded border border-zinc-200/60 overflow-hidden flex flex-col shadow-none">
                <div class="px-4 py-3 border-b border-zinc-150/60 bg-zinc-50/20 flex items-center justify-between">
                    <h3 class="text-xs font-bold text-zinc-900 tracking-tight flex items-center gap-1.5">
                        <i class="ph ph-chats text-zinc-400 text-sm"></i>
                        Tanggapan Terbaru
                    </h3>
                    <span class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Real-time Feed</span>
                </div>

                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-zinc-50/50 border-b border-zinc-150/60 text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider">
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
                        <tbody class="divide-y divide-zinc-150/30 text-xs text-zinc-800">
                            @forelse($responses as $response)
                                <tr class="hover:bg-[#f7f7f5]/40 transition-colors text-xs text-zinc-700">
                                    <td class="py-2 px-4 text-left">
                                        <p class="font-semibold text-zinc-950">{{ $response->user->name ?? 'Deleted User' }}</p>
                                        <p class="text-[10px] text-zinc-400 font-mono mt-0.5">{{ $response->user->email ?? 'N/A' }}</p>
                                    </td>
                                    <td class="py-2 px-4 text-center font-mono font-bold text-zinc-800">{{ $response->q1_overall }}</td>
                                    <td class="py-2 px-4 text-center font-mono font-bold text-zinc-800">{{ $response->q2_navigation }}</td>
                                    <td class="py-2 px-4 text-center font-mono font-bold text-zinc-800">{{ $response->q3_speed }}</td>
                                    <td class="py-2 px-4 text-center font-mono font-bold text-zinc-800">{{ $response->q5_ai_analyzer }}</td>
                                    <td class="py-2 px-4 text-center font-mono font-bold text-zinc-800">{{ $response->q11_design }}</td>
                                    <td class="py-2 px-4 text-left">
                                        @if($response->feedback)
                                            <p class="max-w-[200px] truncate" title="{{ $response->feedback }}">{{ $response->feedback }}</p>
                                        @else
                                            <span class="text-zinc-400 italic text-[10px]">No comment</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-4 text-right text-[10px] text-zinc-400 whitespace-nowrap">
                                        {{ $response->created_at->format('d M Y H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-8 text-center text-zinc-400">
                                        <i class="ph ph-chat-circle-slash text-xl mb-1.5 block mx-auto text-zinc-300"></i>
                                        <span class="text-xs font-bold text-zinc-800 block">Belum ada respon survey</span>
                                        <span class="text-[9px] text-zinc-400 block mt-0.5">Tanggapan akan muncul di sini setelah survey diisi.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($responses->hasPages())
                    <div class="p-3 border-t border-zinc-150/60 notion-pagination">
                        {{ $responses->links() }}
                    </div>
                @endif
            </div>

        </div>

    </div>
</x-admin-layout>
