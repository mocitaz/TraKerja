<div class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-6 space-y-6 sm:space-y-8 pb-10">

    <!-- Header -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-6 sm:mb-8">
        <div class="flex items-center gap-3 sm:gap-4 min-w-0 w-full md:w-auto">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white border border-slate-200/60 rounded-[1.25rem] sm:rounded-[1.5rem] flex items-center justify-center text-primary-600 shadow-sm shrink-0">
                <i class="ph-duotone ph-clock-counter-clockwise text-xl sm:text-2xl"></i>
            </div>
            <div class="flex flex-col min-w-0">
                <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight truncate">User Activities</h3>
                <p class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mt-1 sm:mt-1.5 truncate">Log & Monitoring</p>
            </div>
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

    {{-- Filters & Search --}}
    <div class="bento-card bg-white rounded-[2rem] border border-slate-100 p-3 sm:p-4 flex flex-col lg:flex-row gap-3 items-center justify-between mb-4 mt-6">
        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto flex-1">
            <!-- Search -->
            <div class="relative w-full sm:max-w-xs">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau deskripsi..." class="w-full px-4 py-2.5 bg-slate-50 hover:bg-slate-100 border-none rounded-xl text-sm font-bold text-slate-700 focus:bg-white focus:ring-2 focus:ring-primary-500/20 transition-all placeholder:text-slate-400 placeholder:font-medium">
            </div>

            <!-- Tipe Aktivitas Filter -->
            <div class="relative w-full sm:w-56">
                <select wire:model.live="filterType" class="w-full pl-4 pr-8 py-2.5 bg-slate-50 hover:bg-slate-100 border-none rounded-xl text-sm font-bold text-slate-700 focus:bg-white focus:ring-2 focus:ring-primary-500/20 transition-all appearance-none cursor-pointer">
                    <option value="all">— Semua Tipe —</option>
                    @foreach($groupedTypes as $category => $types)
                        <optgroup label="{{ $category }}">
                            @foreach($types as $type)
                                <option value="{{ $type }}">{{ $typeLabels[$type] ?? ucwords(str_replace('_', ' ', $type)) }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                    <i class="ph-bold ph-caret-down text-slate-400 text-xs"></i>
                </div>
            </div>

            <!-- Status Filter -->
            <div class="relative w-full sm:w-40">
                <select wire:model.live="filterStatus" class="w-full pl-4 pr-8 py-2.5 bg-slate-50 hover:bg-slate-100 border-none rounded-xl text-sm font-bold text-slate-700 focus:bg-white focus:ring-2 focus:ring-primary-500/20 transition-all appearance-none cursor-pointer">
                    <option value="all">Semua Status</option>
                    <option value="success">Success</option>
                    <option value="failed">Failed</option>
                    <option value="pending">Pending</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                    <i class="ph-bold ph-caret-down text-slate-400 text-xs"></i>
                </div>
            </div>
            
            <!-- Rows per page -->
            <div class="relative w-full sm:w-28 hidden md:block">
                <select wire:model.live="perPage" class="w-full pl-4 pr-8 py-2.5 bg-slate-50 hover:bg-slate-100 border-none rounded-xl text-sm font-bold text-slate-700 focus:bg-white focus:ring-2 focus:ring-primary-500/20 transition-all appearance-none cursor-pointer">
                    <option value="15">15 Rows</option>
                    <option value="30">30 Rows</option>
                    <option value="50">50 Rows</option>
                    <option value="100">100 Rows</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                    <i class="ph-bold ph-caret-down text-slate-400 text-xs"></i>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-3 w-full lg:w-auto shrink-0">
            <button wire:click="$set('showSettingsModal', true)" class="w-full sm:w-auto px-5 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 hover:text-primary-600 transition-colors flex items-center justify-center gap-2 text-sm font-bold shadow-sm group">
                <i class="ph-bold ph-gear text-lg group-hover:rotate-90 transition-transform"></i>
                Pengaturan Log
            </button>
        </div>
    </div>

    {{-- Activities Table --}}
    <div class="bento-card bg-white rounded-[2rem] border border-slate-100 overflow-hidden flex flex-col relative min-h-[500px]">
        <div class="overflow-x-auto custom-scrollbar flex-1" wire:loading.class="opacity-50">
            <table class="min-w-full divide-y divide-slate-100/80">
                <thead>
                    <tr>
                        <th class="px-4 lg:px-6 py-4 text-left text-[11px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap bg-slate-50/50">Waktu</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-[11px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap bg-slate-50/50">Pengguna</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-[11px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap bg-slate-50/50">Tipe</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-[11px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap bg-slate-50/50">Deskripsi</th>
                        <th class="px-4 lg:px-6 py-4 text-center text-[11px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap bg-slate-50/50">Status</th>
                        <th class="px-4 lg:px-6 py-4 text-right text-[11px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap bg-slate-50/50">IP Address</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100/80">
                    @forelse($activities as $activity)
                    <tr class="hover:bg-slate-50/50 transition-colors duration-200">
                        <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-black tracking-tight text-slate-900">{{ $activity->created_at->format('d M Y') }}</div>
                            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ $activity->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                            @if($activity->user)
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-[14px] bg-gradient-to-br from-primary-500 to-indigo-600 flex items-center justify-center text-white font-black shadow-sm">
                                        <span class="text-white font-semibold text-xs sm:text-sm">{{ strtoupper(substr($activity->user->name, 0, 1)) }}</span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-sm font-black tracking-tight text-slate-900 truncate">{{ $activity->user->name }}</div>
                                        <div class="text-[11px] font-medium text-slate-500 truncate">{{ $activity->user->email }}</div>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-[14px] bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 shadow-sm">
                                        <i class="ph-fill ph-robot text-lg"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-sm font-black tracking-tight text-slate-500 truncate italic">System / Guest</div>
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                            @php
                                $typeColors = [
                                    // Autentikasi & Akun
                                    'login'           => 'bg-blue-50 text-blue-700 border-blue-100',
                                    'logout'          => 'bg-slate-100 text-slate-600 border-slate-200',
                                    'register'        => 'bg-cyan-50 text-cyan-700 border-cyan-100',
                                    'profile_update'  => 'bg-sky-50 text-sky-700 border-sky-100',
                                    'password_change' => 'bg-orange-50 text-orange-700 border-orange-100',
                                    'account_delete'  => 'bg-rose-100 text-rose-800 border-rose-200',
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
                                    'extension_login'    => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'extension_job_save' => 'bg-cyan-100 text-cyan-800 border-cyan-200',
                                    // Pembayaran & Langganan
                                    'top_up'          => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                                    'premium_upgrade' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
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
                                $color = $typeColors[$activity->activity_type] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                                $label = $typeLabels[$activity->activity_type] ?? ucwords(str_replace('_', ' ', $activity->activity_type));
                            @endphp
                            <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg border shadow-sm {{ $color }}">
                                {{ $label }}
                            </span>
                        </td>
                        <td class="px-4 lg:px-6 py-4">
                            <div class="text-sm font-medium text-slate-700 max-w-xs md:max-w-md line-clamp-2 leading-relaxed" title="{{ $activity->description }}">
                                {{ $activity->description }}
                            </div>
                        </td>
                        <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-center">
                            @if($activity->status === 'success')
                                <span class="px-3 py-1.5 text-[10px] font-black uppercase tracking-widest rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-100 inline-flex items-center gap-1.5 shadow-sm">
                                    <i class="ph-fill ph-check-circle text-xs"></i>
                                    Success
                                </span>
                            @elseif($activity->status === 'failed')
                                <span class="px-3 py-1.5 text-[10px] font-black uppercase tracking-widest rounded-lg bg-rose-50 text-rose-600 border border-rose-100 inline-flex items-center gap-1.5 shadow-sm">
                                    <i class="ph-fill ph-x-circle text-xs"></i>
                                    Failed
                                </span>
                            @else
                                <span class="px-3 py-1.5 text-[10px] font-black uppercase tracking-widest rounded-lg bg-amber-50 text-amber-600 border border-amber-100 inline-flex items-center gap-1.5 shadow-sm">
                                    <i class="ph-fill ph-clock text-xs"></i>
                                    {{ $activity->status }}
                                </span>
                            @endif
                        </td>
                        <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-[11px] font-mono font-medium text-slate-500">{{ $activity->ip_address ?? '-' }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                                    <i class="ph-fill ph-clock-counter-clockwise text-3xl text-slate-300"></i>
                                </div>
                                <p class="text-lg font-extrabold text-slate-900 mb-1">Tidak Ada Aktivitas</p>
                                <p class="text-sm font-medium text-slate-500">Belum ada log aktivitas yang tercatat atau sesuai filter.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Loading Overlay -->
        <div wire:loading class="absolute inset-0 bg-white/50 backdrop-blur-sm z-10 flex items-center justify-center">
            <div class="flex items-center space-x-2 text-primary-600 bg-white px-5 py-3 rounded-xl shadow-lg border border-slate-100">
                <i class="ph-bold ph-spinner animate-spin text-xl"></i>
                <span class="font-bold text-sm">Memuat data...</span>
            </div>
        </div>

        <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-xs font-medium text-slate-500 text-center sm:text-left">
                Showing <span class="font-bold text-slate-900">{{ $activities->firstItem() ?? 0 }}</span> to <span class="font-bold text-slate-900">{{ $activities->lastItem() ?? $activities->count() }}</span> of <span class="font-bold text-slate-900">{{ $activities->total() }}</span> logs
            </div>
            <div class="w-full sm:w-auto">
                {{ $activities->links() }}
            </div>
        </div>
    </div>

    {{-- Settings Modal --}}
    @if($showSettingsModal)
        <template x-teleport="body">
            <div class="fixed inset-0 z-[100] overflow-y-auto" x-data x-init="document.body.classList.add('overflow-hidden')" @destroyed="document.body.classList.remove('overflow-hidden')">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xl transition-opacity" wire:click="$set('showSettingsModal', false)"></div>
                <div class="relative min-h-screen flex items-center justify-center p-4">
                    <div class="relative w-full max-w-lg bg-white rounded-[2rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.2)] border border-slate-100 flex flex-col overflow-hidden z-10 transform transition-all" @click.stop
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 translate-y-4">
                    {{-- Modal Header: Clean White --}}
                    <div class="bg-white px-6 py-5 text-slate-900 flex justify-between items-center border-b border-slate-100 shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center shadow-sm text-slate-600">
                                <i class="ph-fill ph-gear text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-black tracking-tight">Pengaturan Log</h3>
                                <p class="text-slate-400 text-[8px] font-bold uppercase tracking-widest mt-0.5">Atur Lama Penyimpanan</p>
                            </div>
                        </div>
                        <button wire:click="$set('showSettingsModal', false)" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-50 transition-all text-slate-400 hover:text-slate-900">
                            <i class="ph-bold ph-x text-base"></i>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-6 bg-white overflow-y-auto custom-scrollbar">
                        <div class="mb-6">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Auto-Prune (Hapus Otomatis)</label>
                            <p class="text-xs font-medium text-slate-500 mb-4">Pilih durasi penyimpanan log aktivitas sebelum dihapus otomatis secara permanen (untuk menghemat memori database).</p>
                            
                            <div class="grid grid-cols-3 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model="pruneDays" value="90" class="peer sr-only">
                                    <div class="rounded-[1rem] border border-slate-200 bg-white p-3 text-center hover:bg-slate-50 peer-checked:border-primary-500 peer-checked:bg-primary-50 peer-checked:ring-2 peer-checked:ring-primary-500/20 transition-all">
                                        <span class="block text-sm font-black text-slate-800">90 Hari</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model="pruneDays" value="120" class="peer sr-only">
                                    <div class="rounded-[1rem] border border-slate-200 bg-white p-3 text-center hover:bg-slate-50 peer-checked:border-primary-500 peer-checked:bg-primary-50 peer-checked:ring-2 peer-checked:ring-primary-500/20 transition-all">
                                        <span class="block text-sm font-black text-slate-800">120 Hari</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model="pruneDays" value="360" class="peer sr-only">
                                    <div class="rounded-[1rem] border border-slate-200 bg-white p-3 text-center hover:bg-slate-50 peer-checked:border-primary-500 peer-checked:bg-primary-50 peer-checked:ring-2 peer-checked:ring-primary-500/20 transition-all">
                                        <span class="block text-sm font-black text-slate-800">360 Hari</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="bg-rose-50 border border-rose-100 rounded-[1rem] p-4 mt-6">
                            <div class="flex flex-col sm:flex-row items-start gap-4">
                                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center flex-shrink-0 text-rose-500 shadow-sm border border-rose-100">
                                    <i class="ph-fill ph-trash text-xl"></i>
                                </div>
                                <div class="flex-1 w-full">
                                    <h4 class="text-sm font-black text-rose-900 tracking-tight">Pembersihan Manual</h4>
                                    <p class="text-xs font-medium text-rose-700 mt-0.5 mb-3">Hapus log usang secara instan.</p>
                                    <div class="flex flex-col sm:flex-row flex-wrap gap-2">
                                        <button wire:click="cleanNow" wire:confirm="Hapus log lebih dari {{ $pruneDays }} hari? Aksi ini tidak dapat dibatalkan." class="w-full sm:w-auto px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white rounded-lg transition-colors text-[11px] font-bold shadow-sm flex items-center justify-center gap-1.5">
                                            <i class="ph-bold ph-trash"></i>
                                            Hapus {{ $pruneDays }} Hr
                                        </button>
                                        <button wire:click="cleanAll" wire:confirm="PERINGATAN: Ini akan menghapus SEMUA log aktivitas secara permanen. Lanjutkan?" class="w-full sm:w-auto px-4 py-2 bg-rose-800 hover:bg-rose-900 text-white rounded-lg transition-colors text-[11px] font-bold shadow-sm flex items-center justify-center gap-1.5">
                                            <i class="ph-bold ph-warning"></i>
                                            Hapus Semua
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex flex-col-reverse sm:flex-row justify-end gap-2 shrink-0">
                        <button type="button" wire:click="$set('showSettingsModal', false)" class="w-full sm:w-auto px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 hover:text-slate-900 transition-all text-xs font-bold shadow-sm">
                            Batal
                        </button>
                        <button wire:click="saveSettings" class="w-full sm:w-auto px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all text-xs font-bold shadow-sm shadow-primary-500/20 flex items-center justify-center gap-2">
                            <i class="ph-bold ph-floppy-disk"></i>
                            Simpan Pengaturan
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </template>
    @endif
</div>
