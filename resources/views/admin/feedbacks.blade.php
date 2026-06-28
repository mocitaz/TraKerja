<x-admin-layout>
    <div class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-5 space-y-5 pb-10" x-data="{ replyingTicketId: null }">

        <!-- Sticky Global Sub-Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-zinc-200/80">
            <div class="flex items-center gap-2.5 min-w-0">
                <span class="text-xs font-mono font-medium text-zinc-400">Admin</span>
                <span class="text-zinc-300">/</span>
                <h1 class="text-sm font-semibold tracking-tight text-zinc-900">Feedbacks & Support</h1>
            </div>
        </div>

        {{-- Flash Alerts --}}
        @if(session('success_message'))
            <div class="p-3.5 bg-emerald-50 border border-emerald-250 text-emerald-800 rounded-lg flex items-center gap-2.5">
                <i class="ph-bold ph-check text-base text-emerald-650 shrink-0"></i>
                <p class="text-xs font-semibold">{{ session('success_message') }}</p>
            </div>
        @endif

        {{-- Statistics Bento Grid --}}
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            {{-- Total Tickets --}}
            <div class="bg-white rounded-lg p-4 border border-zinc-200/80 hover:bg-zinc-50/50 transition-colors col-span-2 lg:col-span-1">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Total Feedback</p>
                        <h3 class="text-lg font-semibold tracking-tight text-zinc-900">{{ number_format($totalFeedbacks) }}</h3>
                        <p class="text-[10px] text-zinc-400 mt-1">Semua tiket masuk</p>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200/80 flex items-center justify-center text-zinc-650 shrink-0">
                        <i class="ph-bold ph-folder text-base"></i>
                    </div>
                </div>
            </div>

            {{-- Pending Tickets --}}
            <div class="bg-white rounded-lg p-4 border border-zinc-200/80 hover:bg-zinc-50/50 transition-colors">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Pending</p>
                        <h3 class="text-lg font-semibold tracking-tight text-amber-600 flex items-center gap-1">
                            {{ number_format($pendingFeedbacks) }}
                            @if($pendingFeedbacks > 0)
                                <span class="flex h-1.5 w-1.5 relative">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-amber-500"></span>
                                </span>
                            @endif
                        </h3>
                        <p class="text-[10px] text-zinc-400 mt-1">Butuh balasan</p>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200/80 flex items-center justify-center text-zinc-650 shrink-0">
                        <i class="ph-bold ph-clock text-base"></i>
                    </div>
                </div>
            </div>

            {{-- Replied Tickets --}}
            <div class="bg-white rounded-lg p-4 border border-zinc-200/80 hover:bg-zinc-50/50 transition-colors">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Replied</p>
                        <h3 class="text-lg font-semibold tracking-tight text-blue-600">{{ number_format($repliedFeedbacks) }}</h3>
                        <p class="text-[10px] text-zinc-400 mt-1">Telah direspon</p>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200/80 flex items-center justify-center text-zinc-650 shrink-0">
                        <i class="ph-bold ph-chat-circle-dots text-base"></i>
                    </div>
                </div>
            </div>

            {{-- Completed Tickets --}}
            <div class="bg-white rounded-lg p-4 border border-zinc-200/80 hover:bg-zinc-50/50 transition-colors">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Completed</p>
                        <h3 class="text-lg font-semibold tracking-tight text-emerald-600">{{ number_format($completedFeedbacks) }}</h3>
                        <p class="text-[10px] text-zinc-400 mt-1">Selesai / ditutup</p>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200/80 flex items-center justify-center text-zinc-650 shrink-0">
                        <i class="ph-bold ph-check-circle text-base"></i>
                    </div>
                </div>
            </div>

            {{-- On Hold Tickets --}}
            <div class="bg-white rounded-lg p-4 border border-zinc-200/80 hover:bg-zinc-50/50 transition-colors">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">On Hold</p>
                        <h3 class="text-lg font-semibold tracking-tight text-red-650">{{ number_format($onHoldFeedbacks) }}</h3>
                        <p class="text-[10px] text-zinc-400 mt-1">Tertunda / review</p>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200/80 flex items-center justify-center text-zinc-650 shrink-0">
                        <i class="ph-bold ph-warning-circle text-base"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Panel (Ramping & Sleek) --}}
        <div class="bg-white p-3.5 rounded-lg border border-zinc-200/80">
            <form action="{{ route('admin.feedbacks.index') }}" method="GET" class="flex flex-wrap items-end gap-3">
                <div class="w-full sm:w-48">
                    <label class="block text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1.5">Filter Status</label>
                    <select name="status" onchange="this.form.submit()"
                        class="w-full px-2.5 py-1.5 bg-zinc-50 border border-zinc-200 rounded-md text-xs font-medium text-zinc-800 focus:bg-white focus:ring-1 focus:ring-primary-600 focus:border-primary-600 transition-colors">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending (Belum Dibalas)</option>
                        <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>Replied (Sudah Dibalas)</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed (Selesai / Tutup)</option>
                        <option value="on_hold" {{ request('status') === 'on_hold' ? 'selected' : '' }}>On Hold (Tertunda)</option>
                    </select>
                </div>
                <div class="w-full sm:w-56">
                    <label class="block text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1.5">Filter Kategori</label>
                    <select name="category" onchange="this.form.submit()"
                        class="w-full px-2.5 py-1.5 bg-zinc-50 border border-zinc-200 rounded-md text-xs font-medium text-zinc-800 focus:bg-white focus:ring-1 focus:ring-primary-600 focus:border-primary-600 transition-colors">
                        <option value="">Semua Kategori</option>
                        <option value="technical_issue" {{ request('category') === 'technical_issue' ? 'selected' : '' }}>Technical Issue</option>
                        <option value="payment_billing" {{ request('category') === 'payment_billing' ? 'selected' : '' }}>Payment & Billing</option>
                        <option value="feature_request" {{ request('category') === 'feature_request' ? 'selected' : '' }}>Feature Request</option>
                        <option value="general_feedback" {{ request('category') === 'general_feedback' ? 'selected' : '' }}>General Feedback</option>
                    </select>
                </div>
                @if(request()->filled('status') || request()->filled('category'))
                    <a href="{{ route('admin.feedbacks.index') }}"
                        class="px-3 py-1.5 bg-zinc-100 hover:bg-zinc-200 text-zinc-600 rounded-md text-xs font-medium transition-colors flex items-center justify-center gap-1 w-full sm:w-auto h-[30px]">
                        <i class="ph-bold ph-x text-sm"></i> Hapus Filter
                    </a>
                @endif
            </form>
        </div>

        {{-- Main Feedbacks List --}}
        <div class="space-y-3.5">
            @forelse($tickets as $ticket)
                @php
                    $statusClasses = [
                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'replied' => 'bg-blue-50 text-blue-700 border-blue-150',
                        'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-150',
                        'on_hold' => 'bg-red-50 text-red-700 border-red-200',
                    ][$ticket->status] ?? 'bg-zinc-100 text-zinc-700 border-zinc-200';

                    $statusIcon = [
                        'pending' => 'ph-clock',
                        'replied' => 'ph-chat-circle-dots',
                        'completed' => 'ph-check-circle',
                        'on_hold' => 'ph-warning-circle',
                    ][$ticket->status] ?? 'ph-question';
                    
                    $statusBgIcon = [
                        'pending' => 'bg-amber-50/50 text-amber-600 border border-amber-200/40',
                        'replied' => 'bg-blue-50/50 text-blue-600 border border-blue-150/40',
                        'completed' => 'bg-emerald-50/50 text-emerald-600 border border-emerald-150/40',
                        'on_hold' => 'bg-red-50/50 text-red-600 border border-red-200/40',
                    ][$ticket->status] ?? 'bg-zinc-50 text-zinc-650 border border-zinc-200/50';
                @endphp

                <div class="bg-white border border-zinc-200/80 rounded-lg overflow-hidden transition-all"
                    x-data="{ expanded: false }">

                    {{-- Summary Card Row --}}
                    <div class="p-4 sm:p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 cursor-pointer hover:bg-zinc-50/50 transition-colors"
                        @click="expanded = !expanded">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 {{ $statusBgIcon }}">
                                <i class="ph-bold {{ $statusIcon }} text-base"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-1.5 mb-1">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-mono font-bold border uppercase tracking-wider {{ $statusClasses }}">
                                        {{ $ticket->status }}
                                    </span>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-mono font-bold bg-zinc-50 text-zinc-600 border border-zinc-200 uppercase tracking-wider">
                                        {{ $ticket->category_label }}
                                    </span>
                                </div>
                                <h4 class="text-xs font-semibold text-zinc-900 leading-snug tracking-tight mb-0.5">
                                    {{ $ticket->subject }}</h4>
                                <div class="flex items-center gap-1.5 text-[10px] text-zinc-400 flex-wrap">
                                    <span class="font-bold text-zinc-600">{{ $ticket->user ? $ticket->user->name : ($ticket->guest_name ?? 'Guest') }}</span>
                                    <span>({{ $ticket->user ? $ticket->user->email : ($ticket->guest_email ?? 'no-email') }})</span>
                                    <span class="w-1 h-1 bg-zinc-200 rounded-full"></span>
                                    <span>{{ $ticket->created_at->format('d M Y H:i') }} WIB</span>
                                </div>
                            </div>
                        </div>

                        {{-- Row Actions --}}
                        <div class="flex items-center gap-2.5 self-end md:self-center">
                            <form action="{{ route('admin.feedbacks.destroy', $ticket->id) }}" method="POST" class="inline"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus feedback pengguna ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" @click.stop
                                    class="w-7 h-7 rounded hover:bg-red-50 border border-zinc-200 text-zinc-400 hover:text-red-600 flex items-center justify-center transition-colors">
                                    <i class="ph-bold ph-trash text-sm"></i>
                                </button>
                            </form>
                            <button
                                class="px-2.5 py-1 bg-zinc-50 hover:bg-zinc-100 text-zinc-700 rounded border border-zinc-200 text-[10px] font-bold transition-all flex items-center gap-1">
                                <span x-text="expanded ? 'Tutup' : 'Lihat & Kelola'"></span>
                                <i class="ph-bold ph-caret-down text-[9px] transition-transform"
                                    :class="expanded ? 'rotate-180' : ''"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Expanded Detail View & Reply Box --}}
                    <div x-show="expanded" x-collapse x-cloak
                        class="border-t border-zinc-150 bg-zinc-50/50 p-4 sm:p-5 space-y-4">

                        {{-- Original Message block --}}
                        <div class="bg-white rounded border border-zinc-200 p-4">
                            <h5 class="text-[9px] font-mono font-bold text-zinc-450 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                <i class="ph-bold ph-user-circle text-sm text-zinc-500"></i> Pesan Pengguna
                            </h5>
                            <p class="text-xs text-zinc-800 leading-relaxed whitespace-pre-line">
                                {{ $ticket->message }}</p>
                        </div>

                        {{-- Reply history --}}
                        @if($ticket->isReplied())
                            <div class="bg-indigo-50/40 border border-indigo-150 p-4 rounded">
                                <h5 class="text-[9px] font-mono font-bold text-indigo-500 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                    <i class="ph-bold ph-shield-check text-sm"></i> Balasan Terkirim
                                </h5>
                                <p class="text-xs text-indigo-900 leading-relaxed whitespace-pre-line mb-2">
                                    {{ $ticket->admin_reply }}</p>
                                <p class="text-[9px] font-mono font-bold text-indigo-400">Dibalas oleh Admin pada
                                    {{ $ticket->replied_at->format('d M Y H:i') }} WIB</p>
                            </div>
                        @endif

                        {{-- Direct Status Selector --}}
                        <div class="bg-white rounded border border-zinc-200 p-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <h5 class="text-xs font-bold text-zinc-800 mb-0.5 flex items-center gap-1.5">
                                        <i class="ph-bold ph-sliders-horizontal text-zinc-450"></i> Ganti Status Tiket Secara Langsung
                                    </h5>
                                    <p class="text-[10px] text-zinc-450">Ganti status tiket bantuan secara langsung tanpa menulis balasan baru.</p>
                                </div>
                                <form action="{{ route('admin.feedbacks.status', $ticket->id) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" required
                                        class="px-2.5 py-1 bg-zinc-50 border border-zinc-200 rounded text-xs font-medium text-zinc-800 focus:bg-white focus:ring-1 focus:ring-primary-600 focus:border-primary-600 transition-colors h-[30px]">
                                        <option value="pending" {{ $ticket->status === 'pending' ? 'selected' : '' }}>Pending (Butuh Balasan)</option>
                                        <option value="replied" {{ $ticket->status === 'replied' ? 'selected' : '' }}>Replied (Sudah Dibalas)</option>
                                        <option value="completed" {{ $ticket->status === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                                        <option value="on_hold" {{ $ticket->status === 'on_hold' ? 'selected' : '' }}>On Hold (Tertunda)</option>
                                    </select>
                                    <button type="submit" 
                                            class="px-3 py-1.5 bg-zinc-900 hover:bg-zinc-800 text-white rounded text-[10px] font-bold transition-colors h-[30px]">
                                        Simpan
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Reply form panel --}}
                        <div class="bg-white rounded border border-zinc-200 p-4">
                            <h5 class="text-xs font-bold text-zinc-800 mb-3 flex items-center gap-1.5">
                                <i class="ph-bold ph-arrow-bend-up-left text-zinc-400"></i>
                                {{ $ticket->isReplied() ? 'Kirim Balasan Baru / Perbarui Balasan' : 'Tulis Balasan Dukungan' }}
                            </h5>
                            <form action="{{ route('admin.feedbacks.reply', $ticket->id) }}" method="POST" class="space-y-3">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <div class="md:col-span-2">
                                        <textarea name="admin_reply" rows="4" required
                                            placeholder="Tulis tanggapan atau instruksi solusi di sini..."
                                            class="w-full px-3 py-2 bg-zinc-50 border border-zinc-200 rounded text-xs text-zinc-900 focus:bg-white focus:ring-1 focus:ring-primary-600 focus:border-primary-600 transition-all resize-none h-[110px]">{{ $ticket->admin_reply }}</textarea>
                                        @error('admin_reply')
                                            <p class="mt-1 text-[10px] font-bold text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="bg-zinc-50/50 p-3.5 rounded border border-zinc-200/80 flex flex-col justify-between">
                                        <div>
                                            <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Update Status Tiket</label>
                                            <select name="status" required
                                                class="w-full px-2.5 py-1 bg-white border border-zinc-200 rounded text-xs font-medium text-zinc-800 focus:ring-1 focus:ring-primary-600 focus:border-primary-600 transition-colors">
                                                <option value="replied" {{ $ticket->status === 'replied' ? 'selected' : '' }}>Replied (Dibalas)</option>
                                                <option value="completed" {{ $ticket->status === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                                                <option value="on_hold" {{ $ticket->status === 'on_hold' ? 'selected' : '' }}>On Hold (Tertunda)</option>
                                                <option value="pending" {{ $ticket->status === 'pending' ? 'selected' : '' }}>Pending (Butuh Balasan)</option>
                                            </select>
                                        </div>
                                        <div class="mt-2 text-[9px] text-zinc-450 leading-relaxed font-mono uppercase tracking-wider">
                                            Status diperbarui bersamaan dengan pengiriman balasan.
                                        </div>
                                    </div>
                                </div>
                                <button type="submit"
                                    class="px-4 py-2 bg-zinc-900 hover:bg-zinc-800 text-white rounded text-[10px] font-bold transition-all flex items-center gap-1">
                                    <i class="ph-bold ph-paper-plane-tilt"></i> Kirim Balasan & Update Status
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg border border-zinc-200/80 p-10 text-center">
                    <i class="ph-bold ph-chat-circle-slash text-2xl text-zinc-300 mb-2"></i>
                    <h4 class="text-xs font-bold text-zinc-800 mb-0.5">Tidak ada feedback ditemukan</h4>
                    <p class="text-[10px] text-zinc-400 max-w-sm mx-auto">Belum ada tiket support atau feedback yang diajukan oleh pengguna dengan filter ini.</p>
                </div>
            @endforelse

            {{-- Pagination Links --}}
            @if($tickets->hasPages())
                <div class="pt-2">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>