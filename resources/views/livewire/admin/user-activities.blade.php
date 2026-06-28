<div class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-5 space-y-5 pb-10">

    <!-- Sticky Global Sub-Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-zinc-200/80">
        <div class="flex items-center gap-2.5 min-w-0">
            <span class="text-xs font-mono font-medium text-zinc-400">Admin</span>
            <span class="text-zinc-300">/</span>
            <h1 class="text-sm font-semibold tracking-tight text-zinc-900">User Activities</h1>
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
    <div class="bg-white rounded-lg border border-zinc-200/80 p-3 flex flex-col lg:flex-row gap-3 items-center justify-between">
        <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto flex-1">
            <!-- Search -->
            <div class="relative w-full sm:max-w-xs">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau deskripsi..." 
                       class="w-full px-3 py-1.5 bg-zinc-50 border border-zinc-200 rounded-md text-xs font-medium text-zinc-800 focus:bg-white focus:ring-1 focus:ring-primary-600 focus:border-primary-600 transition-all placeholder:text-zinc-400">
            </div>

            <!-- Tipe Aktivitas Filter -->
            <div class="relative w-full sm:w-56">
                <select wire:model.live="filterType" 
                        class="w-full pl-3 pr-8 py-1.5 bg-zinc-50 border border-zinc-200 rounded-md text-xs font-medium text-zinc-800 focus:bg-white focus:ring-1 focus:ring-primary-600 focus:border-primary-600 transition-all appearance-none cursor-pointer">
                    <option value="all">— Semua Tipe —</option>
                    @foreach($groupedTypes as $category => $types)
                        <optgroup label="{{ $category }}">
                            @foreach($types as $type)
                                <option value="{{ $type }}">{{ $typeLabels[$type] ?? ucwords(str_replace('_', ' ', $type)) }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none">
                    <i class="ph-bold ph-caret-down text-zinc-400 text-xs"></i>
                </div>
            </div>

            <!-- Status Filter -->
            <div class="relative w-full sm:w-40">
                <select wire:model.live="filterStatus" 
                        class="w-full pl-3 pr-8 py-1.5 bg-zinc-50 border border-zinc-200 rounded-md text-xs font-medium text-zinc-800 focus:bg-white focus:ring-1 focus:ring-primary-600 focus:border-primary-600 transition-all appearance-none cursor-pointer">
                    <option value="all">Semua Status</option>
                    <option value="success">Success</option>
                    <option value="failed">Failed</option>
                    <option value="pending">Pending</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none">
                    <i class="ph-bold ph-caret-down text-zinc-400 text-xs"></i>
                </div>
            </div>
            
            <!-- Rows per page -->
            <div class="relative w-full sm:w-28 hidden md:block">
                <select wire:model.live="perPage" 
                        class="w-full pl-3 pr-8 py-1.5 bg-zinc-50 border border-zinc-200 rounded-md text-xs font-medium text-zinc-800 focus:bg-white focus:ring-1 focus:ring-primary-600 focus:border-primary-600 transition-all appearance-none cursor-pointer">
                    <option value="15">15 Rows</option>
                    <option value="30">30 Rows</option>
                    <option value="50">50 Rows</option>
                    <option value="100">100 Rows</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none">
                    <i class="ph-bold ph-caret-down text-zinc-400 text-xs"></i>
                </div>
            </div>
        </div>

        <!-- Settings Action -->
        <div class="flex items-center gap-2 w-full lg:w-auto shrink-0">
            <button wire:click="$set('showSettingsModal', true)" 
                    class="w-full sm:w-auto px-4 py-1.5 bg-white border border-zinc-200 text-zinc-700 hover:bg-zinc-50 rounded-md transition-all flex items-center justify-center gap-1.5 text-xs font-semibold shadow-none">
                <i class="ph-bold ph-gear text-sm"></i>
                Pengaturan Log
            </button>
        </div>
    </div>

    {{-- Activities Table (Notion Minimal List) --}}
    <div class="bg-white rounded-lg border border-zinc-200/80 overflow-hidden flex flex-col relative min-h-[480px]">
        <div class="overflow-x-auto custom-scrollbar flex-1" wire:loading.class="opacity-50">
            <table class="min-w-full divide-y divide-zinc-150">
                <thead class="bg-zinc-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider whitespace-nowrap">Waktu</th>
                        <th class="px-4 py-3 text-left text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider whitespace-nowrap">Pengguna</th>
                        <th class="px-4 py-3 text-left text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider whitespace-nowrap">Tipe</th>
                        <th class="px-4 py-3 text-left text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-4 py-3 text-center text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider whitespace-nowrap">Status</th>
                        <th class="px-4 py-3 text-right text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider whitespace-nowrap">IP Address</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-zinc-100">
                    @forelse($activities as $activity)
                    <tr class="hover:bg-zinc-50/50 transition-colors">
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="text-xs font-semibold text-zinc-900">{{ $activity->created_at->format('d M Y') }}</div>
                            <div class="text-[9px] font-mono text-zinc-400 mt-0.5">{{ $activity->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if($activity->user)
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-zinc-100 border border-zinc-200 flex items-center justify-center font-bold text-zinc-600 text-xs shadow-none">
                                        <span>{{ strtoupper(substr($activity->user->name, 0, 1)) }}</span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-xs font-semibold text-zinc-900 truncate">{{ $activity->user->name }}</div>
                                        <div class="text-[9px] font-mono text-zinc-400 mt-0.5 truncate">{{ $activity->user->email }}</div>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-400 shadow-none">
                                        <i class="ph-bold ph-robot text-sm"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-xs text-zinc-400 italic truncate">System / Guest</div>
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @php
                                $typeColors = [
                                    // Autentikasi & Akun
                                    'login'           => 'bg-blue-50 text-blue-700 border-blue-100',
                                    'logout'          => 'bg-zinc-100 text-zinc-600 border-zinc-200',
                                    'register'        => 'bg-cyan-50 text-cyan-700 border-cyan-100',
                                    'profile_update'  => 'bg-sky-50 text-sky-700 border-sky-100',
                                    'password_change' => 'bg-orange-50 text-orange-700 border-orange-100',
                                    'account_delete'  => 'bg-rose-50 text-rose-700 border-rose-100',
                                    // Manajemen Lamaran
                                    'job_add'            => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    'job_edit'           => 'bg-amber-50 text-amber-700 border-amber-100',
                                    'job_delete'         => 'bg-rose-50 text-rose-700 border-rose-100',
                                    'interview_schedule' => 'bg-teal-50 text-teal-700 border-teal-100',
                                    'csv_export'         => 'bg-lime-50 text-lime-700 border-lime-100',
                                    // CV Builder
                                    'cv_data_update' => 'bg-violet-50 text-violet-700 border-violet-100',
                                    'cv_export'      => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                    // Fitur AI
                                    'ai_analyzer'       => 'bg-purple-50 text-purple-700 border-purple-100',
                                    'ai_analyzer_usage' => 'bg-purple-50 text-purple-700 border-purple-100',
                                    'cover_letter'      => 'bg-fuchsia-50 text-fuchsia-700 border-fuchsia-100',
                                    'ai_photo'          => 'bg-pink-50 text-pink-700 border-pink-100',
                                    // Target & Goals
                                    'goal_set'    => 'bg-purple-50 text-purple-600 border-purple-100',
                                    'goal_update' => 'bg-fuchsia-50 text-fuchsia-600 border-fuchsia-100',
                                    // Chrome Extension
                                    'extension_login'    => 'bg-blue-50 text-blue-700 border-blue-100',
                                    'extension_job_save' => 'bg-cyan-50 text-cyan-700 border-cyan-100',
                                    // Pembayaran & Langganan
                                    'top_up'          => 'bg-amber-50 text-amber-700 border-amber-100',
                                    'premium_upgrade' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    // Bantuan & Feedback
                                    'support_ticket'  => 'bg-red-50 text-red-700 border-red-100',
                                    'feedback_submit' => 'bg-orange-50 text-orange-700 border-orange-100',
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
                                $color = $typeColors[$activity->activity_type] ?? 'bg-zinc-100 text-zinc-600 border-zinc-200';
                                $label = $typeLabels[$activity->activity_type] ?? ucwords(str_replace('_', ' ', $activity->activity_type));
                            @endphp
                            <span class="px-2 py-0.5 text-[9px] font-mono font-bold uppercase rounded-md border {{ $color }}">
                                {{ $label }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-xs text-zinc-650 max-w-xs md:max-w-md line-clamp-2 leading-relaxed" title="{{ $activity->description }}">
                                {{ $activity->description }}
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            @if($activity->status === 'success')
                                <span class="px-2 py-0.5 text-[9px] font-mono font-bold uppercase rounded-md bg-emerald-50 text-emerald-600 border border-emerald-100 inline-flex items-center gap-1">
                                    Success
                                </span>
                            @elseif($activity->status === 'failed')
                                <span class="px-2 py-0.5 text-[9px] font-mono font-bold uppercase rounded-md bg-red-50 text-red-600 border border-red-100 inline-flex items-center gap-1">
                                    Failed
                                </span>
                            @else
                                <span class="px-2 py-0.5 text-[9px] font-mono font-bold uppercase rounded-md bg-amber-50 text-amber-600 border border-amber-100 inline-flex items-center gap-1">
                                    {{ $activity->status }}
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right">
                            <div class="text-[10px] font-mono text-zinc-400">{{ $activity->ip_address ?? '-' }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="ph-bold ph-clock-counter-clockwise text-2xl text-zinc-300 mb-2"></i>
                                <p class="text-xs font-semibold text-zinc-900">Tidak Ada Aktivitas</p>
                                <p class="text-[10px] text-zinc-400 mt-0.5">Belum ada log aktivitas yang tercatat atau sesuai filter.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Loading Overlay -->
        <div wire:loading class="absolute inset-0 bg-white/50 backdrop-blur-sm z-10 flex items-center justify-center">
            <div class="flex items-center space-x-2 text-zinc-900 bg-white px-4 py-2 rounded-lg border border-zinc-200 shadow-lg">
                <i class="ph-bold ph-spinner animate-spin text-sm"></i>
                <span class="text-xs font-medium">Memuat data...</span>
            </div>
        </div>

        <div class="px-4 py-3 bg-zinc-50 border-t border-zinc-200/80 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="text-[10px] text-zinc-400">
                Showing <span class="font-bold text-zinc-700">{{ $activities->firstItem() ?? 0 }}</span> to <span class="font-bold text-zinc-700">{{ $activities->lastItem() ?? $activities->count() }}</span> of <span class="font-bold text-zinc-700">{{ $activities->total() }}</span> logs
            </div>
            <div class="w-full sm:w-auto">
                {{ $activities->links() }}
            </div>
        </div>
    </div>

    {{-- Modal: Settings --}}
    @if($showSettingsModal)
        <template x-teleport="body">
            <div class="fixed inset-0 bg-zinc-950/40 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="z-index: 9999;">
                <div class="bg-white rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] flex flex-col overflow-hidden border border-zinc-200">
                    
                    {{-- Modal Header --}}
                    <div class="bg-white px-5 py-4 flex justify-between items-center border-b border-zinc-150 shrink-0">
                        <div class="flex items-center gap-2">
                            <i class="ph-bold ph-gear text-zinc-400 text-lg"></i>
                            <div>
                                <h3 class="text-xs font-bold text-zinc-900">Pengaturan Log</h3>
                                <p class="text-[9px] text-zinc-400 mt-0.5">Atur Lama Penyimpanan</p>
                            </div>
                        </div>
                        <button wire:click="$set('showSettingsModal', false)" class="w-6 h-6 flex items-center justify-center rounded hover:bg-zinc-100 text-zinc-400 hover:text-zinc-800 transition-colors">
                            <i class="ph-bold ph-x text-sm"></i>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-5 overflow-y-auto custom-scrollbar space-y-5">
                        <div>
                            <label class="block text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Auto-Prune (Hapus Otomatis)</label>
                            <p class="text-[11px] text-zinc-500 mb-3">Pilih durasi penyimpanan log aktivitas sebelum dihapus otomatis secara permanen (untuk menghemat memori database).</p>
                            
                            <div class="grid grid-cols-3 gap-2">
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model="pruneDays" value="90" class="peer sr-only">
                                    <div class="rounded border border-zinc-200 bg-white p-2.5 text-center hover:bg-zinc-50 peer-checked:border-zinc-900 peer-checked:bg-zinc-50 transition-colors">
                                        <span class="block text-xs font-semibold text-zinc-800">90 Hari</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model="pruneDays" value="120" class="peer sr-only">
                                    <div class="rounded border border-zinc-200 bg-white p-2.5 text-center hover:bg-zinc-50 peer-checked:border-zinc-900 peer-checked:bg-zinc-50 transition-colors">
                                        <span class="block text-xs font-semibold text-zinc-800">120 Hari</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model="pruneDays" value="360" class="peer sr-only">
                                    <div class="rounded border border-zinc-200 bg-white p-2.5 text-center hover:bg-zinc-50 peer-checked:border-zinc-900 peer-checked:bg-zinc-50 transition-colors">
                                        <span class="block text-xs font-semibold text-zinc-800">360 Hari</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="bg-red-50/50 border border-red-150 rounded p-4 space-y-2">
                            <div class="flex items-center gap-2">
                                <i class="ph-bold ph-trash text-red-500"></i>
                                <span class="text-xs font-bold text-red-800">Pembersihan Manual</span>
                            </div>
                            <p class="text-[11px] text-red-700 leading-relaxed">Hapus log aktivitas yang sudah usang secara manual.</p>
                            <div class="flex items-center gap-2 pt-1">
                                <button wire:click="cleanNow" wire:confirm="Hapus log lebih dari {{ $pruneDays }} hari? Aksi ini tidak dapat dibatalkan." 
                                        class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-[10px] font-bold transition-colors">
                                    Hapus {{ $pruneDays }} Hr
                                </button>
                                <button wire:click="cleanAll" wire:confirm="PERINGATAN: Ini akan menghapus SEMUA log aktivitas secara permanen. Lanjutkan?" 
                                        class="px-3 py-1 bg-red-850 hover:bg-red-900 text-white rounded text-[10px] font-bold transition-colors">
                                    Hapus Semua
                                </button>
                            </div>
                        </div>

                        {{-- Footer Buttons --}}
                        <div class="pt-2 flex justify-end gap-2">
                            <button type="button" wire:click="$set('showSettingsModal', false)" class="px-4 py-1.5 bg-zinc-100 text-zinc-600 hover:bg-zinc-200 rounded-md text-xs font-semibold transition-colors">
                                Batal
                            </button>
                            <button wire:click="saveSettings" class="px-4 py-1.5 bg-zinc-900 text-white hover:bg-zinc-800 rounded-md text-xs font-semibold transition-colors">
                                Simpan Pengaturan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif
</div>
