<div class="space-y-6">

    {{-- Filters & Search --}}
    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center flex-shrink-0 text-indigo-600 shadow-inner">
                    <i class="ph-fill ph-funnel text-xl"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="text-lg font-extrabold text-slate-900 truncate">Filter & Search</h3>
                    <p class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Cari dan filter aktivitas</p>
                </div>
            </div>
        </div>
        <div class="p-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ph ph-magnifying-glass text-slate-400"></i>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau deskripsi..." class="w-full pl-9 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all placeholder:text-slate-400">
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Tipe Aktivitas</label>
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
                            'cover_letter'      => 'AI Cover Letter / Follow-up',
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
                    <select wire:model.live="filterType" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                        <option value="all">— Semua Tipe —</option>
                        @foreach($groupedTypes as $category => $types)
                            <optgroup label="{{ $category }}">
                                @foreach($types as $type)
                                    <option value="{{ $type }}">{{ $typeLabels[$type] ?? ucwords(str_replace('_', ' ', $type)) }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Status</label>
                    <select wire:model.live="filterStatus" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                        <option value="all">Semua Status</option>
                        <option value="success">Success</option>
                        <option value="failed">Failed</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Rows per page</label>
                    <select wire:model.live="perPage" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                        <option value="15">15</option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Activities Table --}}
    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden relative">
        {{-- Table Header --}}
        <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-indigo-50 rounded-xl flex items-center justify-center flex-shrink-0 text-indigo-600">
                    <i class="ph-fill ph-list-bullets text-lg"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Log Aktivitas</h3>
                    <p class="text-[11px] font-medium text-slate-400">Riwayat interaksi pengguna</p>
                </div>
            </div>
            <button wire:click="$set('showSettingsModal', true)" class="px-3.5 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 hover:text-primary-600 transition-colors flex items-center gap-2 text-xs font-bold shadow-sm">
                <i class="ph-bold ph-gear text-sm"></i>
                Pengaturan Log
            </button>
        </div>
        <div class="overflow-x-auto" wire:loading.class="opacity-50">
            <table class="min-w-full divide-y divide-slate-100/80">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-4 lg:px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider w-px">Waktu</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pengguna</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tipe</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-4 lg:px-6 py-4 text-center text-[11px] font-bold text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="px-4 lg:px-6 py-4 text-right text-[11px] font-bold text-slate-400 uppercase tracking-wider">IP Address</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($activities as $activity)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-slate-900">{{ $activity->created_at->format('d M Y') }}</div>
                            <div class="text-[10px] font-medium text-slate-500">{{ $activity->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                            @if($activity->user)
                                <div class="flex items-center min-w-0">
                                    <div class="flex-shrink-0 h-8 w-8 sm:h-10 sm:w-10 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center">
                                        <span class="text-white font-semibold text-xs sm:text-sm">{{ strtoupper(substr($activity->user->name, 0, 1)) }}</span>
                                    </div>
                                    <div class="ml-4 min-w-0 flex-1">
                                        <div class="text-sm font-extrabold text-slate-900 truncate">{{ $activity->user->name }}</div>
                                        <div class="text-[10px] font-medium text-slate-500 truncate">{{ $activity->user->email }}</div>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center min-w-0">
                                    <div class="flex-shrink-0 h-8 w-8 sm:h-10 sm:w-10 bg-slate-200 rounded-lg flex items-center justify-center text-slate-400">
                                        <i class="ph-fill ph-robot"></i>
                                    </div>
                                    <div class="ml-4 min-w-0 flex-1">
                                        <div class="text-sm font-extrabold text-slate-500 truncate italic">System / Guest</div>
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
                            <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md border {{ $color }}">
                                {{ $label }}
                            </span>
                        </td>
                        <td class="px-4 lg:px-6 py-4">
                            <div class="text-sm font-medium text-slate-700 max-w-xs md:max-w-md line-clamp-2" title="{{ $activity->description }}">
                                {{ $activity->description }}
                            </div>
                        </td>
                        <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-center">
                            @if($activity->status === 'success')
                                <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md bg-emerald-50 text-emerald-600 border border-emerald-100 inline-flex items-center gap-1.5">
                                    <i class="ph-fill ph-check-circle text-xs"></i>
                                    Success
                                </span>
                            @elseif($activity->status === 'failed')
                                <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md bg-rose-50 text-rose-600 border border-rose-100 inline-flex items-center gap-1.5">
                                    <i class="ph-fill ph-x-circle text-xs"></i>
                                    Failed
                                </span>
                            @else
                                <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md bg-amber-50 text-amber-600 border border-amber-100 inline-flex items-center gap-1.5">
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
        <div class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="$set('showSettingsModal', false)"></div>
            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-[0_12px_40px_rgba(0,0,0,0.12)] border border-slate-100 transform transition-all z-10" @click.stop>
                    {{-- Modal Header --}}
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0 flex-1">
                                <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center flex-shrink-0 text-slate-600 shadow-inner">
                                    <i class="ph-fill ph-gear text-xl"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-lg font-extrabold text-slate-900 truncate">Pengaturan Activity Log</h3>
                                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Atur lama penyimpanan log</p>
                                </div>
                            </div>
                            <button wire:click="$set('showSettingsModal', false)" class="text-slate-400 hover:text-slate-600 bg-white hover:bg-slate-100 rounded-xl p-2 transition-all flex-shrink-0 border border-slate-100 shadow-sm">
                                <i class="ph-bold ph-x text-lg"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-6">
                        <div class="mb-6">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Auto-Prune (Hapus Otomatis)</label>
                            <p class="text-sm font-medium text-slate-500 mb-4">Pilih durasi penyimpanan log aktivitas sebelum dihapus otomatis secara permanen (untuk menghemat memori database).</p>
                            
                            <div class="grid grid-cols-3 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model="pruneDays" value="90" class="peer sr-only">
                                    <div class="rounded-xl border border-slate-200 bg-white p-4 text-center hover:bg-slate-50 peer-checked:border-primary-500 peer-checked:bg-primary-50 peer-checked:ring-2 peer-checked:ring-primary-500/20 transition-all">
                                        <span class="block text-base font-extrabold text-slate-800">90 Hari</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model="pruneDays" value="120" class="peer sr-only">
                                    <div class="rounded-xl border border-slate-200 bg-white p-4 text-center hover:bg-slate-50 peer-checked:border-primary-500 peer-checked:bg-primary-50 peer-checked:ring-2 peer-checked:ring-primary-500/20 transition-all">
                                        <span class="block text-base font-extrabold text-slate-800">120 Hari</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model="pruneDays" value="360" class="peer sr-only">
                                    <div class="rounded-xl border border-slate-200 bg-white p-4 text-center hover:bg-slate-50 peer-checked:border-primary-500 peer-checked:bg-primary-50 peer-checked:ring-2 peer-checked:ring-primary-500/20 transition-all">
                                        <span class="block text-base font-extrabold text-slate-800">360 Hari</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="bg-rose-50 border border-rose-100 rounded-xl p-5 mt-6">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center flex-shrink-0 text-rose-500 shadow-sm">
                                    <i class="ph-fill ph-trash text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-extrabold text-rose-900 mb-1">Pembersihan Manual</h4>
                                    <p class="text-xs font-medium text-rose-700 mb-3">Hapus log usang berdasarkan durasi ({{ $pruneDays }} hari), atau hapus <strong>semua log</strong> sekaligus.</p>
                                    <div class="flex flex-wrap gap-2">
                                        <button wire:click="cleanNow" wire:confirm="Hapus log lebih dari {{ $pruneDays }} hari? Aksi ini tidak dapat dibatalkan." class="px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white rounded-lg transition-colors text-xs font-bold shadow-sm flex items-center gap-2">
                                            <i class="ph-bold ph-trash"></i>
                                            Hapus Log Usang ({{ $pruneDays }} hari)
                                        </button>
                                        <button wire:click="cleanAll" wire:confirm="PERINGATAN: Ini akan menghapus SEMUA log aktivitas secara permanen. Lanjutkan?" class="px-4 py-2 bg-rose-800 hover:bg-rose-900 text-white rounded-lg transition-colors text-xs font-bold shadow-sm flex items-center gap-2">
                                            <i class="ph-bold ph-warning"></i>
                                            Hapus Semua Log
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 rounded-b-2xl flex justify-end gap-3">
                        <button type="button" wire:click="$set('showSettingsModal', false)" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 hover:text-slate-900 transition-all text-sm font-bold shadow-sm">
                            Batal
                        </button>
                        <button wire:click="saveSettings" class="px-5 py-2.5 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all text-sm font-bold shadow-sm shadow-primary-500/20 flex items-center gap-2">
                            <i class="ph-bold ph-floppy-disk"></i>
                            Simpan Pengaturan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
