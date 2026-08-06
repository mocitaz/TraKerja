@php
    if (!function_exists('getAvatarStyleLocal')) {
        function getAvatarStyleLocal($name) {
            $char = strtoupper(substr($name ?? 'A', 0, 1));
            $colors = [
                'A' => ['#4f46e5', '#3b82f6'],
                'B' => ['#3b82f6', '#0ea5e9'],
                'C' => ['#0ea5e9', '#06b6d4'],
                'D' => ['#06b6d4', '#14b8a6'],
                'E' => ['#14b8a6', '#10b981'],
                'F' => ['#10b981', '#22c55e'],
                'G' => ['#22c55e', '#84cc16'],
                'H' => ['#84cc16', '#eab308'],
                'I' => ['#eab308', '#f97316'],
                'J' => ['#f97316', '#ef4444'],
                'K' => ['#ef4444', '#f43f5e'],
                'L' => ['#f43f5e', '#0ea5e9'],
                'M' => ['#52525b', '#27272a'],
                'N' => ['#71717a', '#3f3f46'],
                'O' => ['#4f46e5', '#0ea5e9'],
                'P' => ['#14b8a6', '#22c55e'],
                'Q' => ['#f97316', '#ef4444'],
                'R' => ['#eab308', '#f97316'],
                'S' => ['#0ea5e9', '#3b82f6'],
                'T' => ['#10b981', '#14b8a6'],
                'U' => ['#06b6d4', '#3b82f6'],
                'V' => ['#f43f5e', '#f97316'],
                'W' => ['#22c55e', '#0ea5e9'],
                'X' => ['#52525b', '#71717a'],
                'Y' => ['#4f46e5', '#14b8a6'],
                'Z' => ['#ef4444', '#f97316'],
            ];
            $pair = $colors[$char] ?? ['#71717a', '#52525b'];
            return "background: linear-gradient(135deg, {$pair[0]} 0%, {$pair[1]} 100%);";
        }
    }

    $typeLabels = [
        // Autentikasi & Akun
        'login'           => 'Login',
        'logout'          => 'Logout',
        'register'        => 'Registrasi Akun',
        'profile_update'  => 'Update Profil',
        'password_change' => 'Ganti Password',
        'account_delete'  => 'Hapus Akun',
        // Manajemen Lamaran
        'job_add'            => 'Tambah Lamaran',
        'job_edit'           => 'Edit Lamaran',
        'job_delete'         => 'Hapus Lamaran',
        'interview_schedule' => 'Jadwal Interview',
        'csv_export'         => 'Export CSV Lamaran',
        // CV Builder
        'cv_data_update' => 'Update Data CV',
        'cv_export'      => 'Export / Download CV',
        // Fitur AI
        'ai_analyzer'       => 'AI Analyzer CV',
        'ai_analyzer_usage' => 'Penggunaan AI Analyzer',
        'cover_letter'      => 'AI Cover Letter',
        'ai_photo'          => 'AI Photo',
        // Target & Goals
        'goal_set'    => 'Set Target Lamaran',
        'goal_update' => 'Update Target Lamaran',
        // Chrome Extension
        'extension_login'    => 'Extension — Login',
        'extension_job_save' => 'Extension — Simpan Loker',
        // Pembayaran & Langganan
        'top_up'          => 'Top-up Saldo',
        'premium_upgrade' => 'Beli Premium',
        // Bantuan & Feedback
        'support_ticket'  => 'Kirim Support Ticket',
        'feedback_submit' => 'Kirim Feedback / Pesan',
    ];
@endphp

<div class="space-y-6 pb-12 w-full">

    <!-- Header Section (Sleek and Clean) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-zinc-200">
        <div>
            <h1 class="text-lg font-black text-zinc-900 tracking-tight flex items-center gap-1.5">
                User Activities <span class="text-[9px] font-mono font-bold text-zinc-450 bg-zinc-100 border border-zinc-200 px-1.5 py-0.2 rounded">System Logs</span>
            </h1>
            <p class="text-[10px] text-zinc-500 font-semibold mt-0.5">Audit, filter, and monitor user interactions, API logs, and critical operations.</p>
        </div>
    </div>

    {{-- Filters & Search (Ramping & Sleek) --}}
    <div class="bg-[#f7f7f5]/40 rounded-xl border border-zinc-200 p-2.5 flex flex-col lg:flex-row gap-3 items-center justify-between shadow-3xs">
        <div class="flex flex-col sm:flex-row gap-2.5 w-full lg:w-auto flex-1">
            <!-- Search -->
            <div class="relative w-full sm:max-w-xs">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-400">
                    <i class="ph-bold ph-magnifying-glass text-xs"></i>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau deskripsi..." 
                       class="w-full h-9 pl-9 pr-3 bg-white border border-zinc-200 focus:border-zinc-400 rounded-lg text-xs font-semibold text-zinc-800 focus:outline-none focus:ring-0 transition-colors placeholder:text-zinc-400 shadow-3xs">
            </div>

            <!-- Tipe Aktivitas Filter -->
            <div class="relative w-full sm:w-56">
                <select wire:model.live="filterType" 
                        class="w-full h-9 pl-3 pr-8 bg-white border border-zinc-200 focus:border-zinc-400 rounded-lg text-xs font-semibold text-zinc-850 focus:outline-none focus:ring-0 transition-colors bg-none appearance-none cursor-pointer shadow-3xs">
                    <option value="all">Semua Tipe Aktivitas</option>
                    @foreach($groupedTypes as $category => $types)
                        <optgroup label="{{ $category }}" class="font-sans font-bold text-[10px] text-zinc-400 bg-white">
                            @foreach($types as $type)
                                <option value="{{ $type }}" class="font-sans font-semibold text-xs text-zinc-800">{{ $typeLabels[$type] ?? ucwords(str_replace('_', ' ', $type)) }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-zinc-400">
                    <i class="ph-bold ph-caret-down text-[10px]"></i>
                </div>
            </div>

            <!-- Status Filter -->
            <div class="relative w-full sm:w-40">
                <select wire:model.live="filterStatus" 
                        class="w-full h-9 pl-3 pr-8 bg-white border border-zinc-200 focus:border-zinc-400 rounded-lg text-xs font-semibold text-zinc-850 focus:outline-none focus:ring-0 transition-colors bg-none appearance-none cursor-pointer shadow-3xs">
                    <option value="all">Semua Status</option>
                    <option value="success">Success</option>
                    <option value="failed">Failed</option>
                    <option value="pending">Pending</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-zinc-400">
                    <i class="ph-bold ph-caret-down text-[10px]"></i>
                </div>
            </div>
            
            <!-- Rows per page -->
            <div class="relative w-full sm:w-28 hidden md:block">
                <select wire:model.live="perPage" 
                        class="w-full h-9 pl-3 pr-8 bg-white border border-zinc-200 focus:border-zinc-400 rounded-lg text-xs font-semibold text-zinc-850 focus:outline-none focus:ring-0 transition-colors bg-none appearance-none cursor-pointer shadow-3xs">
                    <option value="15">15 Baris</option>
                    <option value="30">30 Baris</option>
                    <option value="50">50 Baris</option>
                    <option value="100">100 Baris</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-zinc-400">
                    <i class="ph-bold ph-caret-down text-[10px]"></i>
                </div>
            </div>
        </div>

        <!-- Settings Action -->
        <div class="flex items-center gap-2 w-full lg:w-auto shrink-0">
            <button wire:click="$set('showSettingsModal', true)" 
                    class="w-full sm:w-auto h-9 px-4 bg-zinc-900 text-white hover:bg-zinc-800 rounded-lg transition-colors flex items-center justify-center gap-1.5 text-xs font-bold shadow-3xs">
                <i class="ph-bold ph-gear text-xs"></i>
                Pengaturan Log
            </button>
        </div>
    </div>

    {{-- Activities Table (Notion Minimal List) --}}
    <div class="bg-white rounded-xl border border-zinc-200 overflow-hidden flex flex-col relative min-h-[480px] shadow-3xs">
        <div class="overflow-x-auto custom-scrollbar flex-1" wire:loading.class="opacity-50">
            <table class="min-w-full divide-y divide-zinc-100">
                <thead class="bg-zinc-50/20 border-b border-zinc-100">
                    <tr>
                        <th class="px-5 py-3.5 text-left text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider whitespace-nowrap">Waktu</th>
                        <th class="px-5 py-3.5 text-left text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider whitespace-nowrap">Pengguna</th>
                        <th class="px-5 py-3.5 text-left text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider whitespace-nowrap">Tipe</th>
                        <th class="px-5 py-3.5 text-left text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-5 py-3.5 text-center text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider whitespace-nowrap">Status</th>
                        <th class="px-5 py-3.5 text-right text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider whitespace-nowrap">IP Address</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-zinc-100">
                    @forelse($activities as $activity)
                    <tr class="hover:bg-[#f7f7f5]/80 transition-colors">
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <div class="text-xs font-bold text-zinc-800">{{ $activity->created_at->format('d M Y') }}</div>
                            <div class="text-[9px] font-mono text-zinc-400 mt-0.5">{{ $activity->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @if($activity->user)
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="flex-shrink-0 w-7 h-7 rounded-full overflow-hidden border border-zinc-200/60 flex items-center justify-center text-white text-[9px] font-black shadow-3xs"
                                         style="{{ getAvatarStyleLocal($activity->user->name) }}">
                                        <span>{{ strtoupper(substr($activity->user->name, 0, 1)) }}</span>
                                    </div>
                                    <div class="min-w-0 flex-1 text-left">
                                        <div class="text-xs font-bold text-zinc-900 truncate leading-tight">{{ $activity->user->name }}</div>
                                        <div class="text-[9px] font-mono text-zinc-400 mt-0.5 truncate leading-none">{{ $activity->user->email }}</div>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="flex-shrink-0 w-7 h-7 rounded-full bg-zinc-50 border border-zinc-200/80 flex items-center justify-center text-zinc-400 shadow-3xs">
                                        <i class="ph-bold ph-robot text-[11px]"></i>
                                    </div>
                                    <div class="min-w-0 flex-1 text-left">
                                        <div class="text-xs text-zinc-450 italic font-semibold truncate leading-none">System / Guest</div>
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @php
                                $typeColors = [
                                    // Autentikasi & Akun
                                    'login'           => 'bg-blue-55/60 text-blue-700 border-blue-100',
                                    'logout'          => 'bg-zinc-50 text-zinc-650 border-zinc-200',
                                    'register'        => 'bg-cyan-55/60 text-cyan-700 border-cyan-100',
                                    'profile_update'  => 'bg-sky-55/60 text-sky-700 border-sky-100',
                                    'password_change' => 'bg-orange-55/60 text-orange-700 border-orange-100',
                                    'account_delete'  => 'bg-rose-55/60 text-rose-700 border-rose-100',
                                    // Manajemen Lamaran
                                    'job_add'            => 'bg-emerald-55/60 text-emerald-700 border-emerald-100',
                                    'job_edit'           => 'bg-amber-55/60 text-amber-700 border-amber-100',
                                    'job_delete'         => 'bg-rose-55/60 text-rose-700 border-rose-100',
                                    'interview_schedule' => 'bg-teal-55/60 text-teal-700 border-teal-100',
                                    'csv_export'         => 'bg-lime-55/60 text-lime-700 border-lime-100',
                                    // CV Builder
                                    'cv_data_update' => 'bg-violet-55/60 text-violet-700 border-violet-100',
                                    'cv_export'      => 'bg-indigo-55/60 text-indigo-700 border-indigo-100',
                                    // Fitur AI
                                    'ai_analyzer'       => 'bg-purple-55/60 text-purple-700 border-purple-100',
                                    'ai_analyzer_usage' => 'bg-purple-55/60 text-purple-700 border-purple-100',
                                    'cover_letter'      => 'bg-fuchsia-55/60 text-fuchsia-700 border-fuchsia-100',
                                    'ai_photo'          => 'bg-pink-55/60 text-pink-700 border-pink-100',
                                    // Target & Goals
                                    'goal_set'    => 'bg-purple-55/60 text-purple-700 border-purple-100',
                                    'goal_update' => 'bg-fuchsia-55/60 text-fuchsia-700 border-fuchsia-100',
                                    // Chrome Extension
                                    'extension_login'    => 'bg-blue-55/60 text-blue-700 border-blue-100',
                                    'extension_job_save' => 'bg-cyan-55/60 text-cyan-700 border-cyan-100',
                                    // Pembayaran & Langganan
                                    'top_up'          => 'bg-amber-55/60 text-amber-700 border-amber-100',
                                    'premium_upgrade' => 'bg-amber-55/60 text-amber-700 border-amber-200',
                                    // Bantuan & Feedback
                                    'support_ticket'  => 'bg-red-55/60 text-red-700 border-red-100',
                                    'feedback_submit' => 'bg-orange-55/60 text-orange-700 border-orange-100',
                                ];
                                $color = $typeColors[$activity->activity_type] ?? 'bg-zinc-50 text-zinc-600 border-zinc-200';
                                $label = $typeLabels[$activity->activity_type] ?? ucwords(str_replace('_', ' ', $activity->activity_type));
                            @endphp
                            <span class="px-2 py-0.5 text-[8px] font-mono font-bold uppercase rounded-lg border select-none {{ $color }}">
                                {{ $label }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-left">
                            <div class="text-xs font-medium text-zinc-600 max-w-xs md:max-w-md line-clamp-2 leading-relaxed" title="{{ $activity->description }}">
                                {{ $activity->description }}
                            </div>
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap text-center">
                            @if($activity->status === 'success')
                                <span class="px-2 py-0.5 text-[8px] font-mono font-bold uppercase rounded-lg border bg-emerald-50/50 text-emerald-700 border-emerald-100 inline-flex items-center gap-1 select-none">
                                    Success
                                </span>
                            @elseif($activity->status === 'failed')
                                <span class="px-2 py-0.5 text-[8px] font-mono font-bold uppercase rounded-lg border bg-red-55/60 text-red-700 border-red-100 inline-flex items-center gap-1 select-none">
                                    Failed
                                </span>
                            @else
                                <span class="px-2 py-0.5 text-[8px] font-mono font-bold uppercase rounded-lg border bg-amber-55/60 text-amber-700 border-amber-100 inline-flex items-center gap-1 select-none">
                                    {{ $activity->status }}
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap text-right font-mono text-[9px] text-zinc-400">
                            {{ $activity->ip_address ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center bg-zinc-50/30 border border-dashed border-zinc-200 rounded-xl py-12">
                                <i class="ph-bold ph-clock-counter-clockwise text-2xl text-zinc-300 mb-2"></i>
                                <p class="text-xs font-bold text-zinc-800">Tidak Ada Aktivitas</p>
                                <p class="text-[10px] text-zinc-450 mt-0.5 font-medium">Belum ada log aktivitas yang tercatat atau sesuai filter.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Loading Overlay -->
        <div wire:loading class="absolute inset-0 bg-white/50 backdrop-blur-sm z-10 flex items-center justify-center">
            <div class="flex items-center space-x-2 text-zinc-900 bg-white px-4 py-2 rounded-xl border border-zinc-200 shadow-3xs">
                <i class="ph-bold ph-spinner animate-spin text-xs"></i>
                <span class="text-xs font-bold">Memuat data...</span>
            </div>
        </div>

        <!-- Footer Pagination -->
        <div class="px-5 py-3.5 bg-zinc-50/20 border-t border-zinc-100 flex flex-col sm:flex-row items-center justify-between gap-3 shrink-0">
            <div class="text-[10px] text-zinc-400 font-mono font-bold">
                Menampilkan <span class="text-zinc-700">{{ $activities->firstItem() ?? 0 }}</span> - <span class="text-zinc-700">{{ $activities->lastItem() ?? $activities->count() }}</span> dari <span class="text-zinc-700">{{ $activities->total() }}</span> log
            </div>
            <div class="w-full sm:w-auto notion-pagination font-mono text-[10px]">
                {{ $activities->links() }}
            </div>
        </div>
    </div>

    {{-- Modal: Settings --}}
    @if($showSettingsModal)
        <template x-teleport="body">
            <div class="fixed inset-0 bg-zinc-950/20 backdrop-blur-[2px] z-50 flex items-center justify-center p-4" style="z-index: 9999;">
                <div class="bg-white rounded-xl border border-zinc-200 shadow-md max-w-md w-full max-h-[90vh] flex flex-col overflow-hidden">
                    
                    {{-- Modal Header --}}
                    <div class="bg-white px-6 py-4.5 flex justify-between items-center border-b border-zinc-100 shrink-0">
                        <div class="flex items-center gap-2">
                            <i class="ph-bold ph-gear text-zinc-455 text-base"></i>
                            <div>
                                <h3 class="text-xs font-bold text-zinc-900">Pengaturan Log</h3>
                                <p class="text-[10px] text-zinc-455 mt-0.5 font-medium">Atur Lama Penyimpanan</p>
                            </div>
                        </div>
                        <button wire:click="$set('showSettingsModal', false)" class="w-6 h-6 flex items-center justify-center rounded-lg hover:bg-zinc-100 text-zinc-400 hover:text-zinc-650 transition-colors">
                            <i class="ph-bold ph-x text-sm"></i>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-6 overflow-y-auto custom-scrollbar space-y-5 text-left">
                        <div>
                            <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest mb-1.5">Auto-Prune (Hapus Otomatis)</label>
                            <p class="text-[11px] text-zinc-500 mb-3.5 leading-relaxed font-medium">Pilih durasi penyimpanan log aktivitas sebelum dihapus otomatis secara permanen untuk menghemat kapasitas database.</p>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                                @php $is90 = $pruneDays == 90; @endphp
                                <label class="relative flex cursor-pointer rounded-xl border p-3.5 transition-all {{ $is90 ? 'border-zinc-900 bg-zinc-50/50 shadow-3xs' : 'border-zinc-200 bg-white hover:bg-zinc-50/50' }}">
                                    <input type="radio" wire:model="pruneDays" value="90" class="sr-only">
                                    <div class="flex items-center justify-between w-full">
                                        <span class="text-xs font-bold text-zinc-800">90 Hari</span>
                                        <div class="w-3.5 h-3.5 rounded-full border flex items-center justify-center shrink-0 {{ $is90 ? 'border-zinc-900 bg-zinc-900' : 'border-zinc-300 bg-transparent' }}">
                                            @if($is90)
                                                <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
                                            @endif
                                        </div>
                                    </div>
                                </label>

                                @php $is120 = $pruneDays == 120; @endphp
                                <label class="relative flex cursor-pointer rounded-xl border p-3.5 transition-all {{ $is120 ? 'border-zinc-900 bg-zinc-50/50 shadow-3xs' : 'border-zinc-200 bg-white hover:bg-zinc-50/50' }}">
                                    <input type="radio" wire:model="pruneDays" value="120" class="sr-only">
                                    <div class="flex items-center justify-between w-full">
                                        <span class="text-xs font-bold text-zinc-800">120 Hari</span>
                                        <div class="w-3.5 h-3.5 rounded-full border flex items-center justify-center shrink-0 {{ $is120 ? 'border-zinc-900 bg-zinc-900' : 'border-zinc-300 bg-transparent' }}">
                                            @if($is120)
                                                <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
                                            @endif
                                        </div>
                                    </div>
                                </label>

                                @php $is360 = $pruneDays == 360; @endphp
                                <label class="relative flex cursor-pointer rounded-xl border p-3.5 transition-all {{ $is360 ? 'border-zinc-900 bg-zinc-50/50 shadow-3xs' : 'border-zinc-200 bg-white hover:bg-zinc-50/50' }}">
                                    <input type="radio" wire:model="pruneDays" value="360" class="sr-only">
                                    <div class="flex items-center justify-between w-full">
                                        <span class="text-xs font-bold text-zinc-800">360 Hari</span>
                                        <div class="w-3.5 h-3.5 rounded-full border flex items-center justify-center shrink-0 {{ $is360 ? 'border-zinc-900 bg-zinc-900' : 'border-zinc-300 bg-transparent' }}">
                                            @if($is360)
                                                <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
                                            @endif
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="bg-red-50/30 border border-red-100 rounded-xl p-4.5 space-y-2">
                            <div class="flex items-center gap-2">
                                <i class="ph-bold ph-trash text-red-500"></i>
                                <span class="text-xs font-bold text-red-800">Pembersihan Manual</span>
                            </div>
                            <p class="text-[11px] text-red-700 leading-relaxed font-medium">Hapus log aktivitas yang sudah usang secara manual dari database sekarang.</p>
                            <div class="flex items-center gap-2 pt-1">
                                <button wire:click="cleanNow" wire:confirm="Hapus log lebih dari {{ $pruneDays }} hari? Aksi ini tidak dapat dibatalkan." 
                                        class="h-8 px-3.5 bg-red-650 hover:bg-red-700 text-white rounded-lg text-[10px] font-bold transition-colors flex items-center justify-center shadow-3xs">
                                    Hapus {{ $pruneDays }} Hari
                                </button>
                                <button wire:click="cleanAll" wire:confirm="PERINGATAN: Ini akan menghapus SEMUA log aktivitas secara permanen. Lanjutkan?" 
                                        class="h-8 px-3.5 border border-red-200 hover:bg-red-50 text-red-700 rounded-lg text-[10px] font-bold transition-colors flex items-center justify-center">
                                    Hapus Semua
                                </button>
                            </div>
                        </div>

                        {{-- Footer Buttons --}}
                        <div class="pt-4 flex justify-end gap-2 border-t border-zinc-100">
                            <button type="button" wire:click="$set('showSettingsModal', false)" class="h-9 px-4 border border-zinc-200 hover:bg-zinc-50 text-zinc-700 rounded-lg text-xs font-bold transition-colors flex items-center justify-center">
                                Batal
                            </button>
                            <button wire:click="saveSettings" class="h-9 px-4 bg-zinc-900 text-white hover:bg-zinc-800 rounded-lg text-xs font-bold transition-colors flex items-center justify-center shadow-3xs">
                                Simpan Pengaturan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif
</div>
