<div class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-5 space-y-4 pb-10">

    <!-- Sticky Global Sub-Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3.5 border-b border-zinc-150/60">
        <div class="flex items-center gap-1.5 min-w-0">
            <span class="text-xs font-mono font-bold text-zinc-400 uppercase tracking-wider">Admin Portal</span>
            <span class="text-zinc-300 text-xs">/</span>
            <h1 class="text-xs font-mono font-bold text-zinc-800 uppercase tracking-wider">User Activities</h1>
        </div>
    </div>

    @php
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

    {{-- Filters & Search (Ramping & Sleek) --}}
    <div class="bg-[#f7f7f5] rounded border border-zinc-200/60 p-2 flex flex-col lg:flex-row gap-2.5 items-center justify-between shadow-none">
        <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto flex-1">
            <!-- Search -->
            <div class="relative w-full sm:max-w-xs">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-zinc-400">
                    <i class="ph ph-magnifying-glass text-xs"></i>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau deskripsi..." 
                       class="w-full h-8 pl-8 pr-3 bg-white border border-zinc-250 focus:border-zinc-400 rounded text-xs font-medium text-zinc-800 focus:outline-none focus:ring-0 transition-colors placeholder:text-zinc-450">
            </div>

            <!-- Tipe Aktivitas Filter -->
            <div class="relative w-full sm:w-56">
                <select wire:model.live="filterType" 
                        class="w-full h-8 pl-2.5 pr-8 bg-white border border-zinc-250 focus:border-zinc-400 rounded text-xs font-medium text-zinc-800 focus:outline-none focus:ring-0 transition-colors bg-none appearance-none cursor-pointer">
                    <option value="all">Semua Tipe Aktivitas</option>
                    @foreach($groupedTypes as $category => $types)
                        <optgroup label="{{ $category }}" class="font-sans font-semibold text-[11px] text-zinc-400 bg-white">
                            @foreach($types as $type)
                                <option value="{{ $type }}" class="font-sans font-normal text-xs text-zinc-850">{{ $typeLabels[$type] ?? ucwords(str_replace('_', ' ', $type)) }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-zinc-400">
                    <i class="ph ph-caret-down text-[10px]"></i>
                </div>
            </div>

            <!-- Status Filter -->
            <div class="relative w-full sm:w-40">
                <select wire:model.live="filterStatus" 
                        class="w-full h-8 pl-2.5 pr-8 bg-white border border-zinc-250 focus:border-zinc-400 rounded text-xs font-medium text-zinc-800 focus:outline-none focus:ring-0 transition-colors bg-none appearance-none cursor-pointer">
                    <option value="all">Semua Status</option>
                    <option value="success">Success</option>
                    <option value="failed">Failed</option>
                    <option value="pending">Pending</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-zinc-400">
                    <i class="ph ph-caret-down text-[10px]"></i>
                </div>
            </div>
            
            <!-- Rows per page -->
            <div class="relative w-full sm:w-28 hidden md:block">
                <select wire:model.live="perPage" 
                        class="w-full h-8 pl-2.5 pr-8 bg-white border border-zinc-250 focus:border-zinc-400 rounded text-xs font-medium text-zinc-800 focus:outline-none focus:ring-0 transition-colors bg-none appearance-none cursor-pointer">
                    <option value="15">15 Baris</option>
                    <option value="30">30 Baris</option>
                    <option value="50">50 Baris</option>
                    <option value="100">100 Baris</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-zinc-400">
                    <i class="ph ph-caret-down text-[10px]"></i>
                </div>
            </div>
        </div>

        <!-- Settings Action -->
        <div class="flex items-center gap-2 w-full lg:w-auto shrink-0">
            <button wire:click="$set('showSettingsModal', true)" 
                    class="w-full sm:w-auto h-8 px-4 bg-white border border-zinc-250 text-zinc-700 hover:bg-zinc-50 rounded transition-colors flex items-center justify-center gap-1.5 text-xs font-semibold shadow-none">
                <i class="ph ph-gear text-xs"></i>
                Pengaturan Log
            </button>
        </div>
    </div>

    {{-- Activities Table (Notion Minimal List) --}}
    <div class="bg-white rounded border border-zinc-200/60 overflow-hidden flex flex-col relative min-h-[480px]">
        <div class="overflow-x-auto custom-scrollbar flex-1" wire:loading.class="opacity-50">
            <table class="min-w-full divide-y divide-zinc-200/40">
                <thead class="bg-white border-b border-zinc-200/60">
                    <tr>
                        <th class="px-4 py-2.5 text-left text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider whitespace-nowrap">Waktu</th>
                        <th class="px-4 py-2.5 text-left text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider whitespace-nowrap">Pengguna</th>
                        <th class="px-4 py-2.5 text-left text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider whitespace-nowrap">Tipe</th>
                        <th class="px-4 py-2.5 text-left text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-4 py-2.5 text-center text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider whitespace-nowrap">Status</th>
                        <th class="px-4 py-2.5 text-right text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider whitespace-nowrap">IP Address</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-zinc-200/30">
                    @forelse($activities as $activity)
                    <tr class="hover:bg-[#f7f7f5]/55 transition-colors">
                        <td class="px-4 py-2.5 whitespace-nowrap">
                            <div class="text-xs font-bold text-zinc-800">{{ $activity->created_at->format('d M Y') }}</div>
                            <div class="text-[9px] font-mono text-zinc-400 mt-0.5">{{ $activity->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-4 py-2.5 whitespace-nowrap">
                            @if($activity->user)
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="flex-shrink-0 w-7 h-7 rounded bg-[#efefed] border border-zinc-200/30 flex items-center justify-center font-bold text-zinc-800 text-[10px] shadow-none">
                                        <span>{{ strtoupper(substr($activity->user->name, 0, 1)) }}</span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-xs font-bold text-zinc-900 truncate leading-tight">{{ $activity->user->name }}</div>
                                        <div class="text-[9px] font-mono text-zinc-400 mt-0.5 truncate leading-none">{{ $activity->user->email }}</div>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="flex-shrink-0 w-7 h-7 rounded bg-zinc-50 border border-zinc-200/30 flex items-center justify-center text-zinc-400 shadow-none">
                                        <i class="ph ph-robot text-xs"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-xs text-zinc-450 italic truncate leading-none">System / Guest</div>
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-2.5 whitespace-nowrap">
                            @php
                                $typeColors = [
                                    // Autentikasi & Akun
                                    'login'           => 'bg-blue-50/50 text-blue-700 border-blue-100/70',
                                    'logout'          => 'bg-zinc-50 text-zinc-600 border-zinc-200/70',
                                    'register'        => 'bg-cyan-50/50 text-cyan-700 border-cyan-100/70',
                                    'profile_update'  => 'bg-sky-50/50 text-sky-700 border-sky-100/70',
                                    'password_change' => 'bg-orange-50/50 text-orange-700 border-orange-100/70',
                                    'account_delete'  => 'bg-rose-50/50 text-rose-700 border-rose-100/70',
                                    // Manajemen Lamaran
                                    'job_add'            => 'bg-emerald-50/50 text-emerald-700 border-emerald-100/70',
                                    'job_edit'           => 'bg-amber-50/50 text-amber-700 border-amber-100/70',
                                    'job_delete'         => 'bg-rose-50/50 text-rose-700 border-rose-100/70',
                                    'interview_schedule' => 'bg-teal-50/50 text-teal-700 border-teal-100/70',
                                    'csv_export'         => 'bg-lime-50/50 text-lime-700 border-lime-100/70',
                                    // CV Builder
                                    'cv_data_update' => 'bg-violet-50/50 text-violet-700 border-violet-100/70',
                                    'cv_export'      => 'bg-indigo-50/50 text-indigo-700 border-indigo-100/70',
                                    // Fitur AI
                                    'ai_analyzer'       => 'bg-purple-50/50 text-purple-700 border-purple-100/70',
                                    'ai_analyzer_usage' => 'bg-purple-50/50 text-purple-700 border-purple-100/70',
                                    'cover_letter'      => 'bg-fuchsia-50/50 text-fuchsia-700 border-fuchsia-100/70',
                                    'ai_photo'          => 'bg-pink-50/50 text-pink-700 border-pink-100/70',
                                    // Target & Goals
                                    'goal_set'    => 'bg-purple-50/50 text-purple-650 border-purple-100/70',
                                    'goal_update' => 'bg-fuchsia-50/50 text-fuchsia-650 border-fuchsia-100/70',
                                    // Chrome Extension
                                    'extension_login'    => 'bg-blue-50/50 text-blue-700 border-blue-100/70',
                                    'extension_job_save' => 'bg-cyan-50/50 text-cyan-700 border-cyan-100/70',
                                    // Pembayaran & Langganan
                                    'top_up'          => 'bg-amber-50/50 text-amber-700 border-amber-100/70',
                                    'premium_upgrade' => 'bg-amber-50/50 text-amber-700 border-amber-200/70',
                                    // Bantuan & Feedback
                                    'support_ticket'  => 'bg-red-50/50 text-red-700 border-red-100/70',
                                    'feedback_submit' => 'bg-orange-50/50 text-orange-700 border-orange-100/70',
                                ];
                                $typeLabels = [
                                    'login' => 'Login', 'logout' => 'Logout', 'register' => 'Register',
                                    'profile_update' => 'Update Profil', 'password_change' => 'Ganti Password',
                                    'account_delete' => 'Hapus Akun', 'job_add' => 'Tambah Lamaran',
                                    'job_edit' => 'Edit Lamaran', 'job_delete' => 'Hapus Lamaran',
                                    'interview_schedule' => 'Jadwal Interview', 'csv_export' => 'Export CSV',
                                    'cv_data_update' => 'Update CV', 'cv_export' => 'Export CV',
                                    'ai_analyzer' => 'AI Analyzer', 'ai_analyzer_usage' => 'AI Analyzer',
                                    'cover_letter' => 'Cover Letter', 'ai_photo' => 'AI Photo',
                                    'goal_set' => 'Set Target', 'goal_update' => 'Update Target',
                                    'extension_login' => 'Ext Login', 'extension_job_save' => 'Ext Save Job',
                                    'top_up' => 'Top-up', 'premium_upgrade' => 'Premium',
                                    'support_ticket' => 'Support Ticket', 'feedback_submit' => 'Feedback',
                                ];
                                $color = $typeColors[$activity->activity_type] ?? 'bg-zinc-55/50 text-zinc-600 border-zinc-200/70';
                                $label = $typeLabels[$activity->activity_type] ?? ucwords(str_replace('_', ' ', $activity->activity_type));
                            @endphp
                            <span class="px-1.5 py-0.5 text-[8px] font-mono font-bold uppercase rounded border select-none {{ $color }}">
                                {{ $label }}
                            </span>
                        </td>
                        <td class="px-4 py-2.5">
                            <div class="text-xs text-zinc-600 max-w-xs md:max-w-md line-clamp-2 leading-relaxed" title="{{ $activity->description }}">
                                {{ $activity->description }}
                            </div>
                        </td>
                        <td class="px-4 py-2.5 whitespace-nowrap text-center">
                            @if($activity->status === 'success')
                                <span class="px-1.5 py-0.5 text-[8px] font-mono font-bold uppercase rounded border bg-emerald-50/50 text-emerald-700 border-emerald-100/70 inline-flex items-center gap-1 select-none">
                                    Success
                                </span>
                            @elseif($activity->status === 'failed')
                                <span class="px-1.5 py-0.5 text-[8px] font-mono font-bold uppercase rounded border bg-red-50/50 text-red-700 border-red-100/70 inline-flex items-center gap-1 select-none">
                                    Failed
                                </span>
                            @else
                                <span class="px-1.5 py-0.5 text-[8px] font-mono font-bold uppercase rounded border bg-amber-50/50 text-amber-700 border-amber-100/70 inline-flex items-center gap-1 select-none">
                                    {{ $activity->status }}
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-2.5 whitespace-nowrap text-right">
                            <div class="text-[9px] font-mono text-zinc-400">{{ $activity->ip_address ?? '-' }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="ph ph-clock-counter-clockwise text-xl text-zinc-300 mb-2"></i>
                                <p class="text-xs font-bold text-zinc-800">Tidak Ada Aktivitas</p>
                                <p class="text-[9px] text-zinc-400 mt-0.5">Belum ada log aktivitas yang tercatat atau sesuai filter.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Loading Overlay -->
        <div wire:loading class="absolute inset-0 bg-white/50 backdrop-blur-sm z-10 flex items-center justify-center">
            <div class="flex items-center space-x-2 text-zinc-900 bg-white px-4 py-2 rounded border border-zinc-200 shadow-sm">
                <i class="ph ph-spinner animate-spin text-xs"></i>
                <span class="text-xs font-semibold">Memuat data...</span>
            </div>
        </div>

        <div class="px-4 py-3 bg-[#f7f7f5] border-t border-zinc-200/60 flex flex-col sm:flex-row items-center justify-between gap-3 shrink-0">
            <div class="text-[10px] text-zinc-400 font-mono">
                Menampilkan <span class="font-bold text-zinc-700">{{ $activities->firstItem() ?? 0 }}</span> - <span class="font-bold text-zinc-700">{{ $activities->lastItem() ?? $activities->count() }}</span> dari <span class="font-bold text-zinc-700">{{ $activities->total() }}</span> log
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
                <div class="bg-white rounded border border-zinc-200 shadow-sm max-w-md w-full max-h-[90vh] flex flex-col overflow-hidden">
                    
                    {{-- Modal Header --}}
                    <div class="bg-white px-6 py-4 flex justify-between items-center border-b border-zinc-150 shrink-0">
                        <div class="flex items-center gap-2">
                            <i class="ph ph-gear text-zinc-400 text-base"></i>
                            <div>
                                <h3 class="text-xs font-bold text-zinc-900">Pengaturan Log</h3>
                                <p class="text-[9px] text-zinc-400 mt-0.5">Atur Lama Penyimpanan</p>
                            </div>
                        </div>
                        <button wire:click="$set('showSettingsModal', false)" class="w-5 h-5 flex items-center justify-center rounded hover:bg-zinc-100 text-zinc-400 hover:text-zinc-650 transition-colors">
                            <i class="ph ph-x text-sm"></i>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-6 overflow-y-auto custom-scrollbar space-y-5 text-left">
                        <div>
                            <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide mb-1.5">Auto-Prune (Hapus Otomatis)</label>
                            <p class="text-[11px] text-zinc-500 mb-3.5 leading-relaxed">Pilih durasi penyimpanan log aktivitas sebelum dihapus otomatis secara permanen untuk menghemat kapasitas database.</p>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                                @php $is90 = $pruneDays == 90; @endphp
                                <label class="relative flex cursor-pointer rounded border p-3 transition-all {{ $is90 ? 'border-zinc-900 bg-zinc-50/50 shadow-none' : 'border-zinc-200 bg-white hover:bg-zinc-50/50' }}">
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
                                <label class="relative flex cursor-pointer rounded border p-3 transition-all {{ $is120 ? 'border-zinc-900 bg-zinc-50/50 shadow-none' : 'border-zinc-200 bg-white hover:bg-zinc-50/50' }}">
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
                                <label class="relative flex cursor-pointer rounded border p-3 transition-all {{ $is360 ? 'border-zinc-900 bg-zinc-50/50 shadow-none' : 'border-zinc-200 bg-white hover:bg-zinc-50/50' }}">
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

                        <div class="bg-red-50/30 border border-red-100 rounded p-4 space-y-2">
                            <div class="flex items-center gap-2">
                                <i class="ph ph-trash text-red-500"></i>
                                <span class="text-xs font-bold text-red-800">Pembersihan Manual</span>
                            </div>
                            <p class="text-[11px] text-red-700 leading-relaxed">Hapus log aktivitas yang sudah usang secara manual dari database sekarang.</p>
                            <div class="flex items-center gap-2 pt-1">
                                <button wire:click="cleanNow" wire:confirm="Hapus log lebih dari {{ $pruneDays }} hari? Aksi ini tidak dapat dibatalkan." 
                                        class="h-7 px-3 bg-red-600 hover:bg-red-700 text-white rounded text-[10px] font-semibold transition-colors flex items-center justify-center">
                                    Hapus {{ $pruneDays }} Hari
                                </button>
                                <button wire:click="cleanAll" wire:confirm="PERINGATAN: Ini akan menghapus SEMUA log aktivitas secara permanen. Lanjutkan?" 
                                        class="h-7 px-3 border border-red-200 hover:bg-red-50 text-red-700 rounded text-[10px] font-semibold transition-colors flex items-center justify-center">
                                    Hapus Semua
                                </button>
                            </div>
                        </div>

                        {{-- Footer Buttons --}}
                        <div class="pt-4 flex justify-end gap-2 border-t border-zinc-100">
                            <button type="button" wire:click="$set('showSettingsModal', false)" class="h-8 px-4 border border-zinc-250 hover:bg-zinc-50 text-zinc-700 rounded text-xs font-semibold transition-colors flex items-center justify-center">
                                Batal
                            </button>
                            <button wire:click="saveSettings" class="h-8 px-4 bg-zinc-900 text-white hover:bg-zinc-800 rounded text-xs font-semibold transition-colors flex items-center justify-center">
                                Simpan Pengaturan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif
</div>
