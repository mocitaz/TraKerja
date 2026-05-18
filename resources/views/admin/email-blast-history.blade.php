<x-admin-layout>
@php
    // Calculate total lifetime stats
    $totalCampaigns = \App\Models\EmailBlastLog::count();
    $totalTarget = \App\Models\EmailBlastLog::sum('total_target');
    $totalSuccess = \App\Models\EmailBlastLog::sum('success_count');
    $totalFailed = \App\Models\EmailBlastLog::sum('failed_count');
    $successRate = $totalTarget > 0 ? round(($totalSuccess / $totalTarget) * 100, 1) : 0;
@endphp

<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
        <div class="px-5 py-6 sm:px-8 sm:py-8 border-b border-slate-100 bg-slate-50/50 relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-100 rounded-full blur-3xl opacity-50 pointer-events-none"></div>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 relative z-10">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
                        <i class="ph-duotone ph-clock-counter-clockwise text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Riwayat Blasting Email</h1>
                        <p class="text-sm font-medium text-slate-500 mt-1 font-bold">Arsip aktivitas pengiriman kampanye email massal.</p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('admin.email-blast') }}" class="flex items-center gap-2 px-5 py-3 bg-white border border-slate-200 text-slate-700 hover:text-primary-600 hover:border-primary-200 rounded-xl font-bold text-sm shadow-sm transition-all">
                        <i class="ph-bold ph-arrow-left text-lg text-primary-500"></i>
                        <span>Kembali ke Blasting</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Lifetime Stats widgets -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1: Total Campaigns -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0 text-2xl">
                <i class="ph-duotone ph-megaphone"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Kampanye</p>
                <h3 class="text-2xl font-extrabold text-slate-800 mt-0.5">{{ $totalCampaigns }}</h3>
            </div>
        </div>

        <!-- Card 2: Total Emails Sent -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 text-2xl">
                <i class="ph-duotone ph-paper-plane-tilt"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Terkirim</p>
                <h3 class="text-2xl font-extrabold text-slate-800 mt-0.5">{{ number_format($totalTarget) }}</h3>
            </div>
        </div>

        <!-- Card 3: Total Failed -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0 text-2xl">
                <i class="ph-duotone ph-x-circle"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Gagal</p>
                <h3 class="text-2xl font-extrabold text-slate-800 mt-0.5">{{ number_format($totalFailed) }}</h3>
            </div>
        </div>

        <!-- Card 4: Success Rate -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0 text-2xl">
                <i class="ph-duotone ph-trend-up"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Rasio Keberhasilan</p>
                <h3 class="text-2xl font-extrabold text-slate-800 mt-0.5">{{ $successRate }}%</h3>
            </div>
        </div>
    </div>

    <!-- History Logs Table Card -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/20 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Arsip Kampanye Blasting</h2>
                <p class="text-xs text-slate-500 mt-0.5">Daftar lengkap log pengiriman email massal.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-4 px-6">Tanggal &amp; Waktu</th>
                        <th class="py-4 px-6">Tipe Kampanye</th>
                        <th class="py-4 px-6">Target Segment</th>
                        <th class="py-4 px-6 text-center">Total Target</th>
                        <th class="py-4 px-6 text-center">Status (S / G)</th>
                        <th class="py-4 px-6 text-center">Rasio Sukses</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm">
                    @forelse($logs as $log)
                        @php
                            $logRate = $log->total_target > 0 ? round(($log->success_count / $log->total_target) * 100) : 0;
                            
                            // Map template names
                            $templateMap = [
                                'welcome' => ['label' => 'Welcome Email', 'color' => 'bg-blue-50 text-blue-700 border-blue-100'],
                                'verification' => ['label' => 'Verification Email', 'color' => 'bg-purple-50 text-purple-700 border-purple-100'],
                                'ai_analyzer' => ['label' => 'AI Analyzer Trial', 'color' => 'bg-indigo-50 text-indigo-700 border-indigo-100'],
                                'job_reminder' => ['label' => 'Job Reminder', 'color' => 'bg-amber-50 text-amber-700 border-amber-100'],
                                'monthly_motivation' => ['label' => 'Monthly Motivation', 'color' => 'bg-teal-50 text-teal-700 border-teal-100'],
                                'product_update' => ['label' => 'Product Update', 'color' => 'bg-pink-50 text-pink-700 border-pink-100'],
                                'custom' => ['label' => 'Custom Email', 'color' => 'bg-slate-50 text-slate-700 border-slate-200']
                            ];
                            $tmpl = $templateMap[$log->email_type] ?? ['label' => $log->email_type, 'color' => 'bg-slate-50 text-slate-700 border-slate-100'];

                            // Map audience segment names
                            $audienceMap = [
                                'all' => ['label' => 'Semua User', 'color' => 'bg-slate-100 text-slate-800'],
                                'new' => ['label' => 'User Baru', 'color' => 'bg-cyan-50 text-cyan-800'],
                                'unverified' => ['label' => 'Belum Verifikasi', 'color' => 'bg-rose-50 text-rose-800'],
                                'verified' => ['label' => 'Terverifikasi', 'color' => 'bg-emerald-50 text-emerald-800'],
                                'premium' => ['label' => 'Premium Users', 'color' => 'bg-gradient-to-r from-amber-500/10 to-yellow-500/10 text-amber-800 border border-amber-500/20'],
                                'free' => ['label' => 'Free Users', 'color' => 'bg-slate-50 text-slate-600 border border-slate-200']
                            ];
                            $aud = $audienceMap[$log->target_audience] ?? ['label' => $log->target_audience, 'color' => 'bg-slate-100 text-slate-800'];
                        @endphp
                        <tr class="hover:bg-slate-50/40 transition-colors">
                            <!-- Date & Time -->
                            <td class="py-4 px-6 font-bold text-slate-700">
                                {{ \Carbon\Carbon::parse($log->created_at)->translatedFormat('d M Y') }}
                                <div class="text-[11px] text-slate-400 font-medium mt-0.5">
                                    {{ \Carbon\Carbon::parse($log->created_at)->translatedFormat('H:i') }} WIB
                                </div>
                            </td>

                            <!-- Campaign Type -->
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 rounded-xl text-xs font-bold border {{ $tmpl['color'] }}">
                                    {{ $tmpl['label'] }}
                                </span>
                            </td>

                            <!-- Target Segment -->
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-0.5 rounded-lg text-xs font-semibold {{ $aud['color'] }}">
                                    {{ $aud['label'] }}
                                </span>
                            </td>

                            <!-- Total Target -->
                            <td class="py-4 px-6 text-center font-bold text-slate-700">
                                {{ $log->total_target }}
                            </td>

                            <!-- Status (Success / Failed) -->
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-1.5 text-xs font-bold">
                                    <span class="text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">{{ $log->success_count }}</span>
                                    <span class="text-slate-400">/</span>
                                    <span class="{{ $log->failed_count > 0 ? 'text-rose-600 bg-rose-50' : 'text-slate-500 bg-slate-50' }} px-2 py-0.5 rounded">{{ $log->failed_count }}</span>
                                </div>
                            </td>

                            <!-- Success Rate Progress -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2 justify-center">
                                    <div class="w-16 bg-slate-100 rounded-full h-2 overflow-hidden flex-shrink-0">
                                        <div class="h-full rounded-full {{ $logRate === 100 ? 'bg-emerald-500' : ($logRate > 50 ? 'bg-indigo-500' : 'bg-rose-500') }}" style="width: {{ $logRate }}%"></div>
                                    </div>
                                    <span class="text-xs font-bold text-slate-700 w-8 text-right">{{ $logRate }}%</span>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-6 text-right">
                                @if($log->failed_count > 0 && !empty($log->failed_details))
                                    <button onclick="openFailureModal({{ json_encode($log->failed_details) }}, '{{ $log->id }}')" class="px-3.5 py-1.5 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-xl text-xs font-bold transition-colors inline-flex items-center gap-1">
                                        <i class="ph-bold ph-warning"></i>
                                        <span>Detail Eror</span>
                                    </button>
                                @else
                                    <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-100 inline-flex items-center gap-1">
                                        <i class="ph-bold ph-check-circle"></i>
                                        <span>Clean / Sukses</span>
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400 font-medium">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="ph-duotone ph-archive text-5xl text-slate-300"></i>
                                    <p>Belum ada riwayat blasting email yang tersimpan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Failure Details -->
<div id="failureModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-100 animate-in fade-in zoom-in-95 duration-200">
            <!-- Modal Header -->
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl">
                        <i class="ph-bold ph-warning"></i>
                    </div>
                    <div>
                        <h3 class="text-md font-extrabold text-slate-900" id="modal-title">Detail Kegagalan Pengiriman</h3>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">Daftar user gagal dan alasan eror dari server SMTP.</p>
                    </div>
                </div>
                <button onclick="closeFailureModal()" class="w-8 h-8 rounded-lg hover:bg-slate-200 text-slate-400 hover:text-slate-600 flex items-center justify-center transition-colors">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="px-6 py-6 max-h-[400px] overflow-y-auto space-y-4">
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Daftar User &amp; Logs Eror:</div>
                <div class="divide-y divide-slate-100" id="failureList">
                    <!-- Dynamic failure list rows go here -->
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-slate-50 px-6 py-4 flex justify-end border-t border-slate-100">
                <button onclick="closeFailureModal()" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl text-sm font-bold shadow-sm transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openFailureModal(details, logId) {
        const failureList = document.getElementById('failureList');
        failureList.innerHTML = '';

        if (!details || details.length === 0) {
            failureList.innerHTML = '<div class="text-sm text-slate-500 py-4 text-center">Tidak ada rincian kegagalan.</div>';
        } else {
            details.forEach(item => {
                const row = document.createElement('div');
                row.className = 'py-3 flex flex-col md:flex-row md:items-start gap-2 md:gap-4';
                row.innerHTML = `
                    <div class="flex-shrink-0 w-full md:w-1/3">
                        <div class="font-bold text-slate-800 text-xs truncate">${item.name || 'No Name'}</div>
                        <div class="text-[11px] text-slate-400 font-medium truncate mt-0.5">${item.email}</div>
                    </div>
                    <div class="flex-1 bg-rose-50/50 border border-rose-100 p-2.5 rounded-xl">
                        <div class="text-[11px] text-rose-700 font-mono break-all leading-normal">
                            <i class="ph-bold ph-warning-circle mr-1"></i>${item.error || 'Server error / Unknown error'}
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
