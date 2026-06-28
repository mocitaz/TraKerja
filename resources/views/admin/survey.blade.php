<x-admin-layout>
    <div class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-5 space-y-5 pb-10">
        
        {{-- Success Message --}}
        @if(session('success'))
            <div class="p-3.5 bg-emerald-50 border border-emerald-250 text-emerald-800 rounded-md flex items-center gap-2.5">
                <i class="ph-bold ph-check-circle text-base text-emerald-650 shrink-0"></i>
                <p class="text-xs font-semibold">{{ session('success') }}</p>
            </div>
        @endif
        
        <!-- Sticky Global Sub-Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-zinc-200/80">
            <div class="flex items-center gap-2.5 min-w-0">
                <span class="text-xs font-mono font-medium text-zinc-400">Admin</span>
                <span class="text-zinc-300">/</span>
                <h1 class="text-sm font-semibold tracking-tight text-zinc-900">User Survey & Feedback</h1>
            </div>
        </div>

        {{-- Bento Grid: Survey Toggle & Statistics --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            
            {{-- Control Toggle (1/3 width) --}}
            <div class="bg-white rounded-lg border border-zinc-200/80 p-4 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-2.5 mb-3">
                        <div class="w-8 h-8 bg-zinc-50 border border-zinc-200 rounded flex items-center justify-center shrink-0 text-zinc-650">
                            <i class="ph-bold ph-toggle-left text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-zinc-900">Survey Active Status</h3>
                            <p class="text-[9px] text-zinc-400 mt-0.5 font-mono uppercase tracking-wider">Control forced redirection</p>
                        </div>
                    </div>
                    <p class="text-[11px] text-zinc-500 leading-relaxed">Saat diaktifkan, pengguna biasa yang baru login akan dipaksa mengisi kuisioner kepuasan sebelum diizinkan mengakses dashboard tracker mereka.</p>
                </div>

                <div class="pt-4 border-t border-zinc-150 mt-4 flex items-center justify-between">
                    <span class="text-xs font-semibold {{ $surveyEnabled ? 'text-purple-650' : 'text-zinc-450' }}">
                        {{ $surveyEnabled ? 'Active (Forced)' : 'Inactive' }}
                    </span>
                    <form action="{{ route('admin.survey.toggle') }}" method="POST">
                        @csrf
                        <input type="hidden" name="enabled" value="{{ $surveyEnabled ? '0' : '1' }}">
                        <button type="submit" 
                                class="px-3.5 py-1.5 rounded-md text-[10px] font-bold uppercase tracking-wider transition-colors {{ $surveyEnabled ? 'bg-zinc-900 text-white hover:bg-zinc-800' : 'bg-zinc-100 hover:bg-zinc-200 text-zinc-700' }}">
                            {{ $surveyEnabled ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                </div>
            </div>

            {{-- Stat Cards (2/3 width) --}}
            <div class="lg:col-span-2 grid grid-cols-2 sm:grid-cols-4 gap-4">
                {{-- Overall Score --}}
                <div class="bg-white rounded-lg p-4 border border-zinc-200/80 relative overflow-hidden flex flex-col justify-between">
                    <div>
                        <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Kepuasan</p>
                        <h3 class="text-xl font-bold tracking-tight text-zinc-900">{{ $avgScore }} <span class="text-xs font-normal text-zinc-400">/ 5</span></h3>
                    </div>
                    <p class="text-[9px] text-zinc-400 mt-2 font-mono uppercase tracking-wider">Overall Satisfaction</p>
                </div>

                {{-- Ease of use Score --}}
                <div class="bg-white rounded-lg p-4 border border-zinc-200/80 relative overflow-hidden flex flex-col justify-between">
                    <div>
                        <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Kemudahan</p>
                        <h3 class="text-xl font-bold tracking-tight text-zinc-900">{{ $avgEaseOfUse }} <span class="text-xs font-normal text-zinc-400">/ 5</span></h3>
                    </div>
                    <p class="text-[9px] text-zinc-400 mt-2 font-mono uppercase tracking-wider">Ease of Use</p>
                </div>

                {{-- AI Features Score --}}
                <div class="bg-white rounded-lg p-4 border border-zinc-200/80 relative overflow-hidden flex flex-col justify-between">
                    <div>
                        <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Fitur AI</p>
                        <h3 class="text-xl font-bold tracking-tight text-zinc-900">{{ $avgFeaturesHelpful }} <span class="text-xs font-normal text-zinc-400">/ 5</span></h3>
                    </div>
                    <p class="text-[9px] text-zinc-400 mt-2 font-mono uppercase tracking-wider">AI Helpfulness</p>
                </div>

                {{-- Total Respondents --}}
                <div class="bg-white rounded-lg p-4 border border-zinc-200/80 relative overflow-hidden flex flex-col justify-between">
                    <div>
                        <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Responden</p>
                        <h3 class="text-xl font-bold tracking-tight text-zinc-900">{{ number_format($totalRespondents) }}</h3>
                    </div>
                    <p class="text-[9px] text-zinc-400 mt-2 font-mono uppercase tracking-wider">Total Responses</p>
                </div>
            </div>

        </div>

        {{-- Distribution & Detailed Feed Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            
            {{-- Distribution Chart Card (1/3 width) --}}
            <div class="bg-white rounded-lg border border-zinc-200/80 p-4">
                <h3 class="text-xs font-bold text-zinc-900 mb-4 tracking-tight">Distribusi Skor Kepuasan</h3>
                
                <div class="space-y-2.5">
                    @for($i = 5; $i >= 1; $i--)
                        @php 
                            $count = $distributions[$i] ?? 0;
                            $percentage = $totalRespondents > 0 ? ($count / $totalRespondents) * 100 : 0;
                        @endphp
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-bold text-zinc-500 font-mono w-3 text-right">{{ $i }}</span>
                            <i class="ph-fill ph-star text-amber-400 text-xs shrink-0"></i>
                            
                            <div class="flex-1 bg-zinc-100 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-zinc-900 h-1.5 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                            
                            <span class="text-[9px] font-mono font-bold text-zinc-450 w-8 text-right">{{ $count }}</span>
                        </div>
                    @endfor
                </div>
            </div>

            {{-- Feed Table (2/3 width) --}}
            <div class="lg:col-span-2 bg-white rounded-lg border border-zinc-200/80 overflow-hidden flex flex-col">
                <div class="px-4 py-3 border-b border-zinc-150 bg-zinc-50/50 flex items-center justify-between">
                    <h3 class="text-xs font-bold text-zinc-900 tracking-tight">Tanggapan Terbaru</h3>
                    <span class="text-[9px] font-mono font-bold text-zinc-450 uppercase tracking-wider">Real-time Feed</span>
                </div>

                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-zinc-50/30 border-b border-zinc-150 text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider">
                                <th class="py-2.5 px-4">User</th>
                                <th class="py-2.5 px-4 text-center">Kepuasan</th>
                                <th class="py-2.5 px-4 text-center">Kemudahan</th>
                                <th class="py-2.5 px-4 text-center">Fitur AI</th>
                                <th class="py-2.5 px-4">Saran / Masukan</th>
                                <th class="py-2.5 px-4 text-right">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-150">
                            @forelse($responses as $response)
                                <tr class="hover:bg-zinc-50/50 transition-colors text-xs text-zinc-700">
                                    <td class="py-2 px-4">
                                        <p class="font-semibold text-zinc-900">{{ $response->user->name ?? 'Deleted User' }}</p>
                                        <p class="text-[10px] text-zinc-400 font-mono">{{ $response->user->email ?? 'N/A' }}</p>
                                    </td>
                                    <td class="py-2 px-4 text-center font-bold font-mono text-zinc-800">{{ $response->score }}</td>
                                    <td class="py-2 px-4 text-center font-bold font-mono text-zinc-800">{{ $response->ease_of_use }}</td>
                                    <td class="py-2 px-4 text-center font-bold font-mono text-zinc-800">{{ $response->features_helpful }}</td>
                                    <td class="py-2 px-4">
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
                                    <td colspan="6" class="py-8 text-center text-zinc-400">
                                        <i class="ph-bold ph-chats text-xl mb-1.5 block mx-auto text-zinc-350"></i>
                                        <span class="text-xs font-bold text-zinc-800 block">Belum ada respon survey</span>
                                        <span class="text-[9px] text-zinc-400 block mt-0.5">Tanggapan akan muncul di sini setelah survey diisi.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($responses->hasPages())
                    <div class="p-3 border-t border-zinc-150 bg-zinc-50/50">
                        {{ $responses->links() }}
                    </div>
                @endif
            </div>

        </div>

    </div>
</x-admin-layout>
