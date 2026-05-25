<x-admin-layout>
@php
    // Calculate total lifetime stats
    $totalCampaigns = \App\Models\EmailBlastLog::count();
    $totalTarget = \App\Models\EmailBlastLog::sum('total_target');
    $totalSuccess = \App\Models\EmailBlastLog::sum('success_count');
    $totalFailed = \App\Models\EmailBlastLog::sum('failed_count');
    $successRate = $totalTarget > 0 ? round(($totalSuccess / $totalTarget) * 100, 1) : 0;
@endphp

<div class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-6 space-y-6 sm:space-y-8 pb-10">
    <!-- Header -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-6 sm:mb-8">
        <div class="flex items-center gap-3 sm:gap-4 min-w-0 w-full md:w-auto">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white border border-slate-200/60 rounded-[1.25rem] sm:rounded-[1.5rem] flex items-center justify-center text-primary-600 shadow-sm shrink-0">
                <i class="ph-duotone ph-clock-counter-clockwise text-xl sm:text-2xl"></i>
            </div>
            <div class="flex flex-col min-w-0">
                <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight truncate">Riwayat Blasting</h3>
                <p class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mt-1 sm:mt-1.5 truncate">Log Kampanye Email</p>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.email-blast') }}" class="flex items-center justify-center gap-2 px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-black uppercase tracking-widest transition-colors w-full sm:w-auto shadow-md">
                <i class="ph-bold ph-arrow-left text-sm"></i>
                Kembali ke Blasting
            </a>
        </div>
    </div>

    <!-- Quick Lifetime Stats widgets -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- Card 1: Total Campaigns -->
        <div class="bento-card-stat mesh-gradient-primary rounded-[2rem] border border-slate-100 p-5 flex flex-col group relative overflow-hidden">
            <div class="h-10 flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 transition-transform">
                    <i class="ph-fill ph-megaphone text-xl"></i>
                </div>
            </div>
            <div class="flex flex-col">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1 leading-none">Total Kampanye</p>
                <p class="text-2xl font-black text-slate-900 tracking-tighter leading-none">{{ $totalCampaigns }}</p>
            </div>
        </div>

        <!-- Card 2: Total Emails Sent -->
        <div class="bento-card-stat mesh-gradient-emerald rounded-[2rem] border border-slate-100 p-5 flex flex-col group relative overflow-hidden">
            <div class="h-10 flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 transition-transform">
                    <i class="ph-fill ph-paper-plane-tilt text-xl"></i>
                </div>
            </div>
            <div class="flex flex-col">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1 leading-none">Total Terkirim</p>
                <p class="text-2xl font-black text-slate-900 tracking-tighter leading-none">{{ number_format($totalTarget) }}</p>
            </div>
        </div>

        <!-- Card 3: Total Failed -->
        <div class="bento-card-stat mesh-gradient-rose rounded-[2rem] border border-slate-100 p-5 flex flex-col group relative overflow-hidden">
            <div class="h-10 flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-rose-50 rounded-xl flex items-center justify-center text-rose-600 transition-transform">
                    <i class="ph-fill ph-x-circle text-xl"></i>
                </div>
            </div>
            <div class="flex flex-col">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1 leading-none">Total Gagal</p>
                <p class="text-2xl font-black text-slate-900 tracking-tighter leading-none">{{ number_format($totalFailed) }}</p>
            </div>
        </div>

        <!-- Card 4: Success Rate -->
        <div class="bento-card-stat mesh-gradient-amber rounded-[2rem] border border-slate-100 p-5 flex flex-col group relative overflow-hidden">
            <div class="h-10 flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 transition-transform">
                    <i class="ph-fill ph-trend-up text-xl"></i>
                </div>
            </div>
            <div class="flex flex-col">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1 leading-none">Rasio Keberhasilan</p>
                <p class="text-2xl font-black text-slate-900 tracking-tighter leading-none">{{ $successRate }}%</p>
            </div>
        </div>
    </div>

    <!-- History Logs Table Card -->
    <div class="bento-card bg-white rounded-[2rem] border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/20 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-sm font-black text-slate-900 tracking-tight uppercase flex items-center gap-2">Arsip Kampanye Blasting</h2>
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
<div id="failureModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xl hidden z-[9999] flex items-center justify-center p-4 transition-all duration-300">
    <div class="bg-white rounded-[2rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.2)] max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden border border-slate-100 transform transition-all animate-in fade-in zoom-in-95 duration-200">
        <!-- Modal Header: Clean White -->
        <div class="bg-white px-6 py-5 text-slate-900 flex justify-between items-center border-b border-slate-100 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center shadow-sm">
                    <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-6 h-6 object-contain">
                </div>
                <div>
                    <h3 class="text-sm font-black tracking-tight" id="modal-title">Detail Kegagalan Pengiriman</h3>
                    <p class="text-slate-400 text-[8px] font-bold uppercase tracking-widest mt-0.5">Log & Error Server SMTP</p>
                </div>
            </div>
            <button onclick="closeFailureModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-50 transition-all text-slate-400 hover:text-slate-900">
                <i class="ph-bold ph-x text-base"></i>
            </button>
        </div>

        <!-- Modal Content -->
        <div class="p-6 bg-white overflow-y-auto custom-scrollbar flex-1">
            <div class="space-y-4">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Daftar User &amp; Logs Eror</div>
                <div class="divide-y divide-slate-100" id="failureList">
                    <!-- Dynamic failure list rows go here -->
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-slate-50 px-6 py-4 flex justify-end border-t border-slate-100 shrink-0">
            <button onclick="closeFailureModal()" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl text-xs font-black uppercase tracking-widest shadow-sm transition-colors">
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
