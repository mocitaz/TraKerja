<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

    <!-- Page header -->
    <div class="sm:flex sm:justify-between sm:items-center mb-8">

        <!-- Left: Title -->
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">Activity Log ✨</h1>
            <p class="text-sm text-slate-500 mt-1">Pantau aktivitas dan riwayat interaksi pengguna di sistem.</p>
        </div>

        <!-- Right: Actions -->
        <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
            <!-- Filter by Type -->
            <select wire:model.live="filterType" class="form-select text-sm border-slate-200 hover:border-slate-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg">
                <option value="all">Semua Tipe Aktivitas</option>
                @foreach($activityTypes as $type)
                    <option value="{{ $type }}">{{ ucwords(str_replace('_', ' ', $type)) }}</option>
                @endforeach
            </select>

            <!-- Filter by Status -->
            <select wire:model.live="filterStatus" class="form-select text-sm border-slate-200 hover:border-slate-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg">
                <option value="all">Semua Status</option>
                <option value="success">Success</option>
                <option value="failed">Failed</option>
                <option value="pending">Pending</option>
            </select>

            <!-- Search -->
            <div class="relative">
                <input wire:model.live.debounce.300ms="search" id="search" class="form-input w-full sm:w-64 pl-9 text-sm border-slate-200 hover:border-slate-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg" type="search" placeholder="Cari user atau deskripsi..." />
                <div class="absolute inset-0 right-auto flex items-center justify-center">
                    <i class="ph ph-magnifying-glass text-slate-400 mx-3"></i>
                </div>
            </div>
            
            <!-- Settings Button -->
            <button wire:click="$set('showSettingsModal', true)" class="btn bg-white border-slate-200 hover:border-slate-300 text-slate-500 hover:text-slate-600 rounded-lg">
                <i class="ph-bold ph-gear text-lg"></i>
            </button>
        </div>

    </div>

    <!-- Table -->
    <div class="bg-white shadow-sm rounded-xl border border-slate-200 mb-8 relative overflow-hidden">
        <div wire:loading.delay.class="opacity-50" class="transition-opacity duration-200 overflow-x-auto">
            <table class="table-auto w-full">
                <!-- Table header -->
                <thead class="text-xs font-semibold uppercase text-slate-500 bg-slate-50 border-t border-b border-slate-200">
                    <tr>
                        <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                            <div class="font-semibold text-left">Waktu</div>
                        </th>
                        <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                            <div class="font-semibold text-left">Pengguna</div>
                        </th>
                        <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                            <div class="font-semibold text-left">Tipe</div>
                        </th>
                        <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                            <div class="font-semibold text-left">Deskripsi</div>
                        </th>
                        <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                            <div class="font-semibold text-center">Status</div>
                        </th>
                        <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                            <div class="font-semibold text-right">IP Address</div>
                        </th>
                    </tr>
                </thead>
                <!-- Table body -->
                <tbody class="text-sm divide-y divide-slate-200">
                    @forelse($activities as $activity)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap text-slate-500">
                            <div class="text-slate-800 font-medium">{{ $activity->created_at->format('d M Y') }}</div>
                            <div class="text-xs">{{ $activity->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                            @if($activity->user)
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full shrink-0 bg-slate-100 flex items-center justify-center mr-3 font-semibold text-slate-600">
                                        {{ substr($activity->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-800">{{ $activity->user->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $activity->user->email }}</div>
                                    </div>
                                </div>
                            @else
                                <span class="text-slate-400 italic">Guest / System</span>
                            @endif
                        </td>
                        <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                            @php
                                $typeColors = [
                                    'login' => 'bg-blue-100 text-blue-600',
                                    'logout' => 'bg-slate-100 text-slate-600',
                                    'job_add' => 'bg-emerald-100 text-emerald-600',
                                    'job_edit' => 'bg-amber-100 text-amber-600',
                                    'job_delete' => 'bg-rose-100 text-rose-600',
                                    'goal_set' => 'bg-purple-100 text-purple-600',
                                    'goal_update' => 'bg-fuchsia-100 text-fuchsia-600',
                                ];
                                $color = $typeColors[$activity->activity_type] ?? 'bg-slate-100 text-slate-600';
                            @endphp
                            <div class="inline-flex font-medium rounded-full text-center px-2.5 py-0.5 {{ $color }} text-xs">
                                {{ ucwords(str_replace('_', ' ', $activity->activity_type)) }}
                            </div>
                        </td>
                        <td class="px-2 first:pl-5 last:pr-5 py-3">
                            <div class="text-slate-800 max-w-xs md:max-w-md truncate" title="{{ $activity->description }}">
                                {{ $activity->description }}
                            </div>
                        </td>
                        <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap text-center">
                            @if($activity->status === 'success')
                                <div class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-600" title="Success">
                                    <i class="ph-bold ph-check"></i>
                                </div>
                            @elseif($activity->status === 'failed')
                                <div class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-rose-100 text-rose-600" title="Failed">
                                    <i class="ph-bold ph-x"></i>
                                </div>
                            @else
                                <div class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-100 text-amber-600" title="{{ ucfirst($activity->status) }}">
                                    <i class="ph-bold ph-warning"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap text-right">
                            <div class="text-slate-500 font-mono text-xs">{{ $activity->ip_address ?? '-' }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-2 first:pl-5 last:pr-5 py-8 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                                <i class="ph-duotone ph-magnifying-glass text-2xl text-slate-400"></i>
                            </div>
                            <h2 class="text-slate-800 font-semibold mb-1">Tidak ada log aktivitas</h2>
                            <p class="text-slate-500 text-sm">Belum ada aktivitas yang tercatat atau tidak ada yang sesuai filter.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Loading Overlay -->
        <div wire:loading.delay class="absolute inset-0 bg-white/50 backdrop-blur-sm z-10 flex items-center justify-center">
            <div class="flex items-center space-x-2 text-primary-500 bg-white px-4 py-2 rounded-lg shadow-sm border border-slate-200">
                <i class="ph-bold ph-spinner animate-spin text-lg"></i>
                <span class="font-medium text-sm">Memuat data...</span>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $activities->links() }}
    </div>

    <!-- Settings Modal -->
    <div x-data="{ modalOpen: @entangle('showSettingsModal') }"
         x-show="modalOpen"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        
        <div class="fixed inset-0 bg-slate-900 bg-opacity-30 z-50 transition-opacity"
             x-show="modalOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-out duration-100"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             aria-hidden="true" x-cloak></div>

        <div class="fixed inset-0 z-50 overflow-hidden flex items-center justify-center px-4 sm:px-6">
            <div class="bg-white rounded-2xl shadow-xl overflow-auto max-w-lg w-full max-h-full"
                 x-show="modalOpen"
                 x-transition:enter="transition ease-in-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in-out duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-4"
                 x-cloak>
                
                <div class="px-6 py-5 border-b border-slate-200">
                    <div class="flex justify-between items-center">
                        <div class="font-bold text-slate-800 text-lg">Pengaturan Activity Log</div>
                        <button class="text-slate-400 hover:text-slate-500" @click="modalOpen = false">
                            <i class="ph-bold ph-x text-lg"></i>
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-800 mb-2">Auto-Prune (Hapus Otomatis)</label>
                        <p class="text-sm text-slate-500 mb-4">Pilih durasi penyimpanan log aktivitas sebelum dihapus otomatis secara permanen (untuk menghemat memori database).</p>
                        
                        <div class="grid grid-cols-3 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="pruneDays" value="90" class="peer sr-only">
                                <div class="rounded-xl border border-slate-200 bg-white p-4 text-center hover:bg-slate-50 peer-checked:border-primary-500 peer-checked:bg-primary-50 peer-checked:ring-1 peer-checked:ring-primary-500 transition-all">
                                    <span class="block text-base font-bold text-slate-800">90 Hari</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="pruneDays" value="120" class="peer sr-only">
                                <div class="rounded-xl border border-slate-200 bg-white p-4 text-center hover:bg-slate-50 peer-checked:border-primary-500 peer-checked:bg-primary-50 peer-checked:ring-1 peer-checked:ring-primary-500 transition-all">
                                    <span class="block text-base font-bold text-slate-800">120 Hari</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="pruneDays" value="360" class="peer sr-only">
                                <div class="rounded-xl border border-slate-200 bg-white p-4 text-center hover:bg-slate-50 peer-checked:border-primary-500 peer-checked:bg-primary-50 peer-checked:ring-1 peer-checked:ring-primary-500 transition-all">
                                    <span class="block text-base font-bold text-slate-800">360 Hari</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="bg-rose-50 border border-rose-100 rounded-xl p-4 mt-6">
                        <div class="flex">
                            <i class="ph-duotone ph-warning-circle text-rose-500 text-xl mr-3 mt-0.5"></i>
                            <div>
                                <h4 class="text-sm font-semibold text-rose-800 mb-1">Pembersihan Manual</h4>
                                <p class="text-sm text-rose-600 mb-3">Ingin menghapus log usang sekarang juga? Klik tombol di bawah untuk mengeksekusi Auto-Prune (menghapus log yang berumur lebih dari {{ $pruneDays }} hari).</p>
                                <button wire:click="cleanNow" wire:confirm="Anda yakin ingin menghapus permanen log aktivitas yang usang? Aksi ini tidak dapat dibatalkan." class="btn-sm bg-rose-500 hover:bg-rose-600 text-white rounded-lg px-4 shadow-sm">
                                    Clean Now
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex justify-end space-x-3">
                    <button class="btn bg-white border-slate-200 hover:border-slate-300 text-slate-600 rounded-lg px-4" @click="modalOpen = false">Batal</button>
                    <button wire:click="saveSettings" class="btn bg-primary-500 hover:bg-primary-600 text-white rounded-lg px-6 shadow-sm">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>
