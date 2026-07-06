<x-admin-layout>
@php
    // Calculate total lifetime stats
    $totalCampaigns = \App\Models\EmailBlastLog::count();
    $totalTarget = \App\Models\EmailBlastLog::sum('total_target');
    $totalSuccess = \App\Models\EmailBlastLog::sum('success_count');
    $totalFailed = \App\Models\EmailBlastLog::sum('failed_count');
    $successRate = $totalTarget > 0 ? round(($totalSuccess / $totalTarget) * 100, 1) : 0;
@endphp

<<div class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-5 space-y-4 pb-10">
    <!-- Sticky Global Sub-Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3.5 border-b border-zinc-150/60">
        <div class="flex items-center gap-1.5 min-w-0">
            <span class="text-xs font-mono font-bold text-zinc-400 uppercase tracking-wider">Admin Portal</span>
            <span class="text-zinc-300 text-xs">/</span>
            <span class="text-xs font-mono font-bold text-zinc-400 uppercase tracking-wider">Email Blast</span>
            <span class="text-zinc-300 text-xs">/</span>
            <h1 class="text-xs font-mono font-bold text-zinc-800 uppercase tracking-wider">History Log</h1>
        </div>
        <div>
            <a href="{{ route('admin.email-blast') }}" 
               class="inline-flex items-center justify-center gap-1.5 h-8 px-3 border border-zinc-250 hover:bg-zinc-50 rounded text-xs font-semibold text-zinc-800 transition-colors bg-white shadow-none">
                <i class="ph ph-arrow-left text-sm"></i>
                Kembali ke Blasting
            </a>
        </div>
    </div>

    <!-- Quick Lifetime Stats widgets -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total Campaigns -->
        <div class="border border-zinc-200/60 rounded bg-white p-4 shadow-none flex flex-col justify-between h-[82px]">
            <div class="flex items-center justify-between w-full">
                <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Total Kampanye</span>
                <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200/40 text-zinc-500 flex items-center justify-center shrink-0">
                    <i class="ph ph-megaphone text-xs"></i>
                </div>
            </div>
            <p class="text-xl font-bold tracking-tight text-zinc-900 mt-1 leading-none">{{ $totalCampaigns }}</p>
        </div>

        <!-- Card 2: Total Emails Sent -->
        <div class="border border-zinc-200/60 rounded bg-white p-4 shadow-none flex flex-col justify-between h-[82px]">
            <div class="flex items-center justify-between w-full">
                <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Total Terkirim</span>
                <div class="w-6 h-6 rounded bg-purple-50 border border-purple-100/45 text-purple-650 flex items-center justify-center shrink-0">
                    <i class="ph ph-paper-plane-tilt text-xs"></i>
                </div>
            </div>
            <p class="text-xl font-bold tracking-tight text-zinc-900 mt-1 leading-none">{{ number_format($totalTarget) }}</p>
        </div>

        <!-- Card 3: Total Failed -->
        <div class="border border-zinc-200/60 rounded bg-white p-4 shadow-none flex flex-col justify-between h-[82px]">
            <div class="flex items-center justify-between w-full">
                <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Total Gagal</span>
                <div class="w-6 h-6 rounded bg-red-50 border border-red-100/45 text-red-650 flex items-center justify-center shrink-0">
                    <i class="ph ph-x-circle text-xs"></i>
                </div>
            </div>
            <p class="text-xl font-bold tracking-tight text-zinc-900 mt-1 leading-none">{{ number_format($totalFailed) }}</p>
        </div>

        <!-- Card 4: Success Rate -->
        <div class="border border-zinc-200/60 rounded bg-white p-4 shadow-none flex flex-col justify-between h-[82px]">
            <div class="flex items-center justify-between w-full">
                <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Rasio Keberhasilan</span>
                <div class="w-6 h-6 rounded bg-emerald-50 border border-emerald-100/45 text-emerald-650 flex items-center justify-center shrink-0">
                    <i class="ph ph-trend-up text-xs"></i>
                </div>
            </div>
            <p class="text-xl font-bold tracking-tight text-zinc-900 mt-1 leading-none">{{ $successRate }}%</p>
        </div>
    </div>

    <!-- History Logs Table Card -->
    <div class="bg-white rounded border border-zinc-200/60 overflow-hidden shadow-none">
        <div class="px-4 py-3 border-b border-zinc-150/60 bg-zinc-50/20 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xs font-bold text-zinc-900 tracking-tight flex items-center gap-1.5">
                    <i class="ph ph-archive text-zinc-400 text-sm"></i>
                    Arsip Kampanye Blasting
                </h2>
                <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wide mt-0.5">Daftar Lengkap Riwayat SMTP Outbox</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-zinc-150/60 bg-zinc-50/50 text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider">
                        <th class="py-3 px-4">Tanggal &amp; Waktu</th>
                        <th class="py-3 px-4">Tipe Kampanye</th>
                        <th class="py-3 px-4">Target Segment</th>
                        <th class="py-3 px-4 text-center">Total Target</th>
                        <th class="py-3 px-4 text-center">Status (S / G)</th>
                        <th class="py-3 px-4 text-center">Rasio Sukses</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-150/30 text-xs text-zinc-800">
                    @forelse($logs as $log)
                        @php
                            $logRate = $log->total_target > 0 ? round(($log->success_count / $log->total_target) * 100) : 0;
                            
                            // Map template names
                            $templateMap = [
                                'welcome' => ['label' => 'Welcome Email', 'color' => 'bg-blue-50/50 text-blue-700 border-blue-100/60'],
                                'verification' => ['label' => 'Verification Email', 'color' => 'bg-purple-50/50 text-purple-700 border-purple-100/60'],
                                'ai_analyzer' => ['label' => 'AI Analyzer Trial', 'color' => 'bg-indigo-50/50 text-indigo-700 border-indigo-100/60'],
                                'job_reminder' => ['label' => 'Job Reminder', 'color' => 'bg-amber-50/50 text-amber-700 border-amber-100/60'],
                                'monthly_motivation' => ['label' => 'Monthly Motivation', 'color' => 'bg-teal-50/50 text-teal-700 border-teal-100/60'],
                                'product_update' => ['label' => 'Product Update', 'color' => 'bg-pink-50/50 text-pink-700 border-pink-100/60'],
                                'custom' => ['label' => 'Custom Email', 'color' => 'bg-zinc-50 text-zinc-700 border-zinc-200/60']
                            ];
                            $tmpl = $templateMap[$log->email_type] ?? ['label' => $log->email_type, 'color' => 'bg-zinc-50 text-zinc-700 border-zinc-200/60'];

                            // Map audience segment names
                            $audienceMap = [
                                'all' => ['label' => 'Semua User', 'color' => 'bg-zinc-50 border border-zinc-200/50 text-zinc-650'],
                                'new' => ['label' => 'User Baru', 'color' => 'bg-blue-50/30 border border-blue-100/40 text-blue-800'],
                                'unverified' => ['label' => 'Belum Verifikasi', 'color' => 'bg-red-50/30 border border-red-100/40 text-red-800'],
                                'verified' => ['label' => 'Terverifikasi', 'color' => 'bg-emerald-50/30 border border-emerald-100/40 text-emerald-800'],
                                'premium' => ['label' => 'Premium Users', 'color' => 'bg-purple-50/40 border border-purple-100/40 text-purple-800'],
                                'free' => ['label' => 'Free Users', 'color' => 'bg-zinc-50 border border-zinc-200/60 text-zinc-650']
                            ];
                            $aud = $audienceMap[$log->target_audience] ?? ['label' => $log->target_audience, 'color' => 'bg-zinc-50 border border-zinc-200/60 text-zinc-650'];
                        @endphp
                        <tr class="hover:bg-[#f7f7f5]/40 transition-colors">
                            <!-- Date & Time -->
                            <td class="py-3 px-4 font-bold text-zinc-700">
                                {{ \Carbon\Carbon::parse($log->created_at)->translatedFormat('d M Y') }}
                                <div class="text-[10px] text-zinc-400 font-medium mt-0.5">
                                    {{ \Carbon\Carbon::parse($log->created_at)->translatedFormat('H:i') }} WIB
                                </div>
                            </td>

                            <!-- Campaign Type -->
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-mono border {{ $tmpl['color'] }}">
                                    {{ $tmpl['label'] }}
                                </span>
                            </td>

                            <!-- Target Segment -->
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-mono {{ $aud['color'] }}">
                                    {{ $aud['label'] }}
                                </span>
                            </td>

                            <!-- Total Target -->
                            <td class="py-3 px-4 text-center font-bold text-zinc-700">
                                {{ $log->total_target }}
                            </td>

                            <!-- Status (Success / Failed) -->
                            <td class="py-3 px-4 text-center font-mono">
                                <div class="flex items-center justify-center gap-1 text-[10px] font-bold">
                                    <span class="text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100/30">{{ $log->success_count }}</span>
                                    <span class="text-zinc-400">/</span>
                                    <span class="{{ $log->failed_count > 0 ? 'text-red-650 bg-red-50 border border-red-100/30' : 'text-zinc-500 bg-zinc-50 border border-zinc-200/30' }} px-1.5 py-0.5 rounded">{{ $log->failed_count }}</span>
                                </div>
                            </td>

                            <!-- Success Rate Progress -->
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2 justify-center">
                                    <div class="w-16 bg-zinc-100 rounded-full h-1.5 overflow-hidden flex-shrink-0">
                                        <div class="h-full rounded-full {{ $logRate === 100 ? 'bg-emerald-500' : ($logRate > 50 ? 'bg-purple-500' : 'bg-red-500') }}" style="width: {{ $logRate }}%"></div>
                                    </div>
                                    <span class="text-[10px] font-mono font-bold text-zinc-650 w-8 text-right">{{ $logRate }}%</span>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="py-3 px-4 text-right">
                                @if($log->failed_count > 0 && !empty($log->failed_details))
                                    <button onclick="openFailureModal({{ json_encode($log->failed_details) }}, '{{ $log->id }}')" class="h-6 px-2 border border-red-200 text-red-650 bg-red-50 hover:bg-red-100/80 rounded text-[10px] font-bold transition-colors inline-flex items-center gap-1 focus:outline-none shadow-none">
                                        <i class="ph ph-warning-circle text-xs"></i>
                                        <span>Detail Eror</span>
                                    </button>
                                @else
                                    <span class="h-6 px-2 border border-emerald-250 text-emerald-650 bg-emerald-50 rounded text-[10px] font-bold inline-flex items-center gap-1">
                                        <i class="ph ph-check-circle text-xs"></i>
                                        <span>Clean / Sukses</span>
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-zinc-400 font-medium">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="ph ph-archive text-3xl text-zinc-300"></i>
                                    <p class="text-[10px]">Belum ada riwayat blasting email yang tersimpan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="px-4 py-3 border-t border-zinc-150/60 notion-pagination">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Failure Details -->
<div id="failureModal" class="fixed inset-0 bg-zinc-950/20 backdrop-blur-[2px] hidden z-[9999] flex items-center justify-center p-4 transition-all duration-300">
    <div class="bg-white rounded max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden border border-zinc-200/80 animate-in fade-in zoom-in-95 duration-150 shadow-none">
        <!-- Modal Header: Clean White -->
        <div class="bg-white px-4 py-3 text-zinc-900 flex justify-between items-center border-b border-zinc-150/60 shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200/40 flex items-center justify-center">
                    <i class="ph ph-warning-circle text-red-500 text-sm"></i>
                </div>
                <div class="text-left">
                    <h3 class="text-xs font-bold text-zinc-900 font-sans" id="modal-title">Detail Kegagalan Pengiriman</h3>
                    <p class="text-zinc-400 text-[8px] font-mono font-bold uppercase tracking-wider mt-0.5">Log & Error Server SMTP</p>
                </div>
            </div>
            <button onclick="closeFailureModal()" class="w-6 h-6 flex items-center justify-center rounded hover:bg-zinc-100 transition-colors text-zinc-400 hover:text-zinc-900 focus:outline-none">
                <i class="ph ph-x text-sm"></i>
            </button>
        </div>

        <!-- Modal Content -->
        <div class="p-4 bg-white overflow-y-auto custom-scrollbar flex-1 text-left">
            <div class="space-y-4">
                <div class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-2">Daftar User &amp; Logs Eror</div>
                <div class="divide-y divide-zinc-150/30" id="failureList">
                    <!-- Dynamic failure list rows go here -->
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-zinc-50/50 px-4 py-3 flex justify-end border-t border-zinc-150/60 shrink-0">
            <button onclick="closeFailureModal()" class="h-8 px-4 bg-white border border-zinc-250 text-zinc-650 hover:bg-zinc-50 rounded text-xs font-semibold transition-colors focus:outline-none shadow-none">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function openFailureModal(details, logId) {
        const failureList = document.getElementById('failureList');
        failureList.innerHTML = '';

        if (!details || details.length === 0) {
            failureList.innerHTML = '<div class="text-[10px] text-zinc-500 py-4 text-center">Tidak ada rincian kegagalan.</div>';
        } else {
            details.forEach(item => {
                const row = document.createElement('div');
                row.className = 'py-2.5 flex flex-col md:flex-row md:items-start gap-2 md:gap-4 last:pb-0 border-b border-zinc-150/30 last:border-b-0';
                row.innerHTML = `
                    <div class="flex-shrink-0 w-full md:w-1/3 text-left">
                        <div class="font-bold text-zinc-800 text-xs truncate">${item.name || 'No Name'}</div>
                        <div class="text-[10px] text-zinc-400 font-medium truncate mt-0.5">${item.email}</div>
                    </div>
                    <div class="flex-1 bg-red-50/30 border border-red-100/30 p-2.5 rounded text-left">
                        <div class="text-[10px] text-red-700 font-mono break-all leading-normal flex items-start gap-1">
                            <i class="ph ph-warning-circle text-xs mt-0.5 shrink-0"></i>
                            <span>${item.error || 'Server error / Unknown error'}</span>
                        </div>
                    </div>
                `;
                failureList.appendChild(row);
            });
        }

        document.getElementById('failureModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeFailureModal() {
        document.getElementById('failureModal').classList.add('hidden');
        document.body.style.overflow = '';
    }
</script>
</x-admin-layout>
