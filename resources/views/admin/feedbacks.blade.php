<x-admin-layout>
    <div class="space-y-6 pb-12 w-full" x-data="{ replyingTicketId: null }">

        <!-- Header Section (Sleek and Clean) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-zinc-200">
            <div>
                <h1 class="text-lg font-black text-zinc-900 tracking-tight flex items-center gap-1.5">
                    Feedbacks & Support <span class="text-[9px] font-mono font-bold text-zinc-450 bg-zinc-100 border border-zinc-200 px-1.5 py-0.2 rounded">Tickets</span>
                </h1>
                <p class="text-[10px] text-zinc-500 font-semibold mt-0.5">Manage support inquiries, technical reports, and feature requests from registered users.</p>
            </div>
        </div>

        {{-- Flash Alerts --}}
        @if(session('success_message'))
            <div class="p-3.5 bg-emerald-50 border border-emerald-250 text-emerald-800 rounded-xl flex items-center gap-2.5 shadow-3xs">
                <i class="ph-bold ph-check text-base text-emerald-600 shrink-0"></i>
                <p class="text-xs font-bold">{{ session('success_message') }}</p>
            </div>
        @endif

        {{-- Statistics Bento Grid --}}
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            {{-- Total Tickets --}}
            <div class="border border-zinc-200 rounded-xl bg-white p-4 shadow-3xs flex flex-col justify-between h-[82px] hover:border-zinc-300 transition-colors group">
                <div class="flex items-center justify-between w-full">
                    <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest">Total Feedback</span>
                    <div class="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-950 group-hover:text-white transition duration-200 shrink-0 shadow-3xs">
                        <i class="ph-bold ph-folder text-xs"></i>
                    </div>
                </div>
                <div class="flex items-baseline justify-between mt-1">
                    <p class="text-xl font-extrabold tracking-tight text-zinc-900 leading-none font-mono">{{ number_format($totalFeedbacks) }}</p>
                    <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wide leading-none">Semua Tiket</p>
                </div>
            </div>

            {{-- Pending Tickets --}}
            <div class="border border-zinc-200 rounded-xl bg-white p-4 shadow-3xs flex flex-col justify-between h-[82px] hover:border-zinc-300 transition-colors group">
                <div class="flex items-center justify-between w-full">
                    <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest">Pending</span>
                    <div class="w-7 h-7 rounded-lg bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center shrink-0 shadow-3xs">
                        <i class="ph-bold ph-clock text-xs"></i>
                    </div>
                </div>
                <div class="flex items-baseline justify-between mt-1">
                    <p class="text-xl font-extrabold tracking-tight text-amber-600 leading-none flex items-center gap-1 font-mono">
                        {{ number_format($pendingFeedbacks) }}
                        @if($pendingFeedbacks > 0)
                            <span class="flex h-1.5 w-1.5 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-amber-500"></span>
                            </span>
                        @endif
                    </p>
                    <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wide leading-none">Butuh Balas</p>
                </div>
            </div>

            {{-- Replied Tickets --}}
            <div class="border border-zinc-200 rounded-xl bg-white p-4 shadow-3xs flex flex-col justify-between h-[82px] hover:border-zinc-300 transition-colors group">
                <div class="flex items-center justify-between w-full">
                    <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest">Replied</span>
                    <div class="w-7 h-7 rounded-lg bg-blue-50 border border-blue-200 text-blue-650 flex items-center justify-center shrink-0 shadow-3xs">
                        <i class="ph-bold ph-chat-circle-dots text-xs"></i>
                    </div>
                </div>
                <div class="flex items-baseline justify-between mt-1">
                    <p class="text-xl font-extrabold tracking-tight text-blue-650 leading-none font-mono">{{ number_format($repliedFeedbacks) }}</p>
                    <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wide leading-none">Direspon</p>
                </div>
            </div>

            {{-- Completed Tickets --}}
            <div class="border border-zinc-200 rounded-xl bg-white p-4 shadow-3xs flex flex-col justify-between h-[82px] hover:border-zinc-300 transition-colors group">
                <div class="flex items-center justify-between w-full">
                    <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest">Completed</span>
                    <div class="w-7 h-7 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-650 flex items-center justify-center shrink-0 shadow-3xs">
                        <i class="ph-bold ph-check-circle text-xs"></i>
                    </div>
                </div>
                <div class="flex items-baseline justify-between mt-1">
                    <p class="text-xl font-extrabold tracking-tight text-emerald-650 leading-none font-mono">{{ number_format($completedFeedbacks) }}</p>
                    <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wide leading-none">Selesai</p>
                </div>
            </div>

            {{-- On Hold Tickets --}}
            <div class="border border-zinc-200 rounded-xl bg-white p-4 shadow-3xs flex flex-col justify-between h-[82px] hover:border-zinc-300 transition-colors group">
                <div class="flex items-center justify-between w-full">
                    <span class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest">On Hold</span>
                    <div class="w-7 h-7 rounded-lg bg-red-50 border border-red-200 text-red-650 flex items-center justify-center shrink-0 shadow-3xs">
                        <i class="ph-bold ph-warning-circle text-xs"></i>
                    </div>
                </div>
                <div class="flex items-baseline justify-between mt-1">
                    <p class="text-xl font-extrabold tracking-tight text-red-650 leading-none font-mono">{{ number_format($onHoldFeedbacks) }}</p>
                    <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wide leading-none">Tertunda</p>
                </div>
            </div>
        </div>

        {{-- Filter Panel (Ramping & Sleek) --}}
        <div class="bg-[#f7f7f5]/40 p-2.5 rounded-xl border border-zinc-200 shadow-3xs">
            <form action="{{ route('admin.feedbacks.index') }}" method="GET" class="flex flex-wrap items-end gap-2.5">
                <div class="w-full sm:w-48 text-left">
                    <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest mb-1.5">Filter Status</label>
                    <select name="status" onchange="this.form.submit()"
                        class="w-full px-3 h-9 bg-white border border-zinc-200 rounded-lg text-xs font-semibold text-zinc-800 focus:outline-none focus:border-zinc-400 transition-colors shadow-3xs cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending (Belum Dibalas)</option>
                        <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>Replied (Sudah Dibalas)</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed (Selesai / Tutup)</option>
                        <option value="on_hold" {{ request('status') === 'on_hold' ? 'selected' : '' }}>On Hold (Tertunda)</option>
                    </select>
                </div>
                <div class="w-full sm:w-56 text-left">
                    <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest mb-1.5">Filter Kategori</label>
                    <select name="category" onchange="this.form.submit()"
                        class="w-full px-3 h-9 bg-white border border-zinc-200 rounded-lg text-xs font-semibold text-zinc-800 focus:outline-none focus:border-zinc-400 transition-colors shadow-3xs cursor-pointer">
                        <option value="">Semua Kategori</option>
                        <option value="technical_issue" {{ request('category') === 'technical_issue' ? 'selected' : '' }}>Technical Issue</option>
                        <option value="payment_billing" {{ request('category') === 'payment_billing' ? 'selected' : '' }}>Payment & Billing</option>
                        <option value="feature_request" {{ request('category') === 'feature_request' ? 'selected' : '' }}>Feature Request</option>
                        <option value="general_feedback" {{ request('category') === 'general_feedback' ? 'selected' : '' }}>General Feedback</option>
                    </select>
                </div>
                @if(request()->filled('status') || request()->filled('category'))
                    <a href="{{ route('admin.feedbacks.index') }}"
                        class="inline-flex items-center justify-center gap-1.5 h-9 px-4 border border-zinc-200 hover:bg-zinc-50 rounded-lg text-xs font-bold text-zinc-650 transition-colors bg-white shadow-3xs w-full sm:w-auto">
                        <i class="ph-bold ph-x text-xs"></i> Hapus Filter
                    </a>
                @endif
            </form>
        </div>

        {{-- Main Feedbacks List --}}
        <div class="space-y-4">
            @forelse($tickets as $ticket)
                @php
                    $statusClasses = [
                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'replied' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-250',
                        'on_hold' => 'bg-red-50 text-red-700 border-red-200',
                    ][$ticket->status] ?? 'bg-zinc-100 text-zinc-700 border-zinc-200';

                    $statusIcon = [
                        'pending' => 'ph-clock',
                        'replied' => 'ph-chat-circle-dots',
                        'completed' => 'ph-check-circle',
                        'on_hold' => 'ph-warning-circle',
                    ][$ticket->status] ?? 'ph-question';
                    
                    $statusBgIcon = [
                        'pending' => 'bg-amber-50/50 text-amber-600 border border-amber-100 shadow-3xs',
                        'replied' => 'bg-blue-50/50 text-blue-600 border border-blue-150 shadow-3xs',
                        'completed' => 'bg-emerald-50/50 text-emerald-600 border border-emerald-150 shadow-3xs',
                        'on_hold' => 'bg-red-50/50 text-red-600 border border-red-150 shadow-3xs',
                    ][$ticket->status] ?? 'bg-zinc-50 text-zinc-650 border border-zinc-200';
                @endphp

                <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden transition-all shadow-3xs"
                    x-data="{ expanded: false }">

                    {{-- Summary Card Row --}}
                    <div class="p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 cursor-pointer hover:bg-[#f7f7f5]/80 transition-colors"
                        @click="expanded = !expanded">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 {{ $statusBgIcon }}">
                                <i class="ph-bold {{ $statusIcon }} text-base"></i>
                            </div>
                            <div class="min-w-0 text-left">
                                <div class="flex flex-wrap items-center gap-1.5 mb-1.5">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-lg text-[8px] font-mono font-bold border uppercase tracking-wider {{ $statusClasses }}">
                                        {{ $ticket->status }}
                                    </span>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-lg text-[8px] font-mono font-bold bg-zinc-50 text-zinc-600 border border-zinc-200 uppercase tracking-wider">
                                        {{ $ticket->category_label }}
                                    </span>
                                </div>
                                <h4 class="text-xs font-bold text-zinc-900 leading-snug tracking-tight mb-1">
                                    {{ $ticket->subject }}</h4>
                                <div class="flex items-center gap-1.5 text-[10px] text-zinc-400 flex-wrap">
                                    <span class="font-bold text-zinc-600">{{ $ticket->user ? $ticket->user->name : ($ticket->guest_name ?? 'Guest') }}</span>
                                    <span>({{ $ticket->user ? $ticket->user->email : ($ticket->guest_email ?? 'no-email') }})</span>
                                    <span class="w-1 h-1 bg-zinc-200 rounded-full"></span>
                                    <span class="font-semibold">{{ $ticket->created_at->format('d M Y H:i') }} WIB</span>
                                </div>
                            </div>
                        </div>

                        {{-- Row Actions --}}
                        <div class="flex items-center gap-2 self-end md:self-center">
                            <form action="{{ route('admin.feedbacks.destroy', $ticket->id) }}" method="POST" class="inline"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus feedback pengguna ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" @click.stop
                                    class="w-7.5 h-7.5 rounded-lg bg-white border border-zinc-200 text-zinc-400 hover:text-red-650 hover:bg-red-50 flex items-center justify-center transition-colors focus:outline-none shadow-3xs">
                                    <i class="ph-bold ph-trash text-xs"></i>
                                </button>
                            </form>
                            <button
                                class="inline-flex items-center gap-1.5 h-7.5 px-3 bg-zinc-50 border border-zinc-200 hover:bg-zinc-100 text-zinc-700 rounded-lg text-[10px] font-extrabold transition-all focus:outline-none shadow-3xs">
                                <span x-text="expanded ? 'Tutup' : 'Lihat & Kelola'"></span>
                                <i class="ph-bold ph-caret-down text-[9px] transition-transform"
                                    :class="expanded ? 'rotate-180' : ''"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Expanded Detail View & Reply Box --}}
                    <div x-show="expanded" x-collapse x-cloak
                        class="border-t border-zinc-100 bg-zinc-50/20 p-5 space-y-4">

                        {{-- Original Message block --}}
                        <div class="bg-white rounded-xl border border-zinc-200 p-4.5 shadow-3xs text-left">
                            <h5 class="text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest mb-2 flex items-center gap-1.5">
                                <i class="ph-bold ph-user-circle text-sm text-zinc-500"></i> Pesan Pengguna
                            </h5>
                            <p class="text-xs text-zinc-800 leading-relaxed whitespace-pre-line font-medium">
                                {{ $ticket->message }}</p>
                        </div>

                        {{-- Reply history --}}
                        @if($ticket->isReplied())
                            <div class="bg-blue-50/30 border border-blue-150 p-4.5 rounded-xl text-left shadow-3xs">
                                <h5 class="text-[8px] font-mono font-bold text-blue-600 uppercase tracking-widest mb-2 flex items-center gap-1.5">
                                    <i class="ph-bold ph-shield-check text-sm"></i> Balasan Terkirim
                                </h5>
                                <p class="text-xs text-zinc-900 leading-relaxed whitespace-pre-line mb-2 font-medium">
                                    {{ $ticket->admin_reply }}</p>
                                <p class="text-[8px] font-mono font-bold text-blue-500 uppercase tracking-wider">Dibalas oleh Admin pada
                                    {{ $ticket->replied_at->format('d M Y H:i') }} WIB</p>
                            </div>
                        @endif

                        {{-- Direct Status Selector --}}
                        <div class="bg-white rounded-xl border border-zinc-200 p-4.5 shadow-3xs text-left">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <h5 class="text-xs font-bold text-zinc-800 mb-0.5 flex items-center gap-1.5">
                                        <i class="ph-bold ph-sliders-horizontal text-zinc-400"></i> Ganti Status Tiket Secara Langsung
                                    </h5>
                                    <p class="text-[10px] text-zinc-400 font-semibold">Ganti status tiket bantuan secara langsung tanpa menulis balasan baru.</p>
                                </div>
                                <form action="{{ route('admin.feedbacks.status', $ticket->id) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" required
                                        class="px-3 h-9 bg-white border border-zinc-200 rounded-lg text-xs font-semibold text-zinc-800 focus:outline-none focus:border-zinc-400 transition-colors shadow-3xs cursor-pointer">
                                        <option value="pending" {{ $ticket->status === 'pending' ? 'selected' : '' }}>Pending (Butuh Balasan)</option>
                                        <option value="replied" {{ $ticket->status === 'replied' ? 'selected' : '' }}>Replied (Sudah Dibalas)</option>
                                        <option value="completed" {{ $ticket->status === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                                        <option value="on_hold" {{ $ticket->status === 'on_hold' ? 'selected' : '' }}>On Hold (Tertunda)</option>
                                    </select>
                                    <button type="submit" 
                                            class="inline-flex items-center justify-center h-9 px-4 bg-zinc-900 hover:bg-zinc-800 text-white rounded-lg text-xs font-bold transition-colors shadow-3xs focus:outline-none">
                                        Simpan
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Reply form panel --}}
                        <div class="bg-white rounded-xl border border-zinc-200 p-4.5 shadow-3xs text-left">
                            <h5 class="text-xs font-bold text-zinc-800 mb-3 flex items-center gap-1.5">
                                <i class="ph-bold ph-arrow-bend-up-left text-zinc-450"></i>
                                {{ $ticket->isReplied() ? 'Kirim Balasan Baru / Perbarui Balasan' : 'Tulis Balasan Dukungan' }}
                            </h5>
                            <form action="{{ route('admin.feedbacks.reply', $ticket->id) }}" method="POST" class="space-y-3">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <div class="md:col-span-2 text-left">
                                        <textarea name="admin_reply" rows="4" required
                                            placeholder="Tulis tanggapan atau instruksi solusi di sini..."
                                            class="w-full px-3 py-2 bg-white border border-zinc-200 rounded-lg text-xs text-zinc-900 focus:outline-none focus:border-zinc-400 transition-all shadow-3xs resize-none h-[110px]">{{ $ticket->admin_reply }}</textarea>
                                        @error('admin_reply')
                                            <p class="mt-1 text-[10px] font-bold text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="bg-zinc-50/50 p-3.5 rounded-xl border border-zinc-200 flex flex-col justify-between text-left">
                                        <div>
                                            <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-widest mb-1.5">Update Status Tiket</label>
                                            <select name="status" required
                                                class="w-full px-3 h-9 bg-white border border-zinc-200 rounded-lg text-xs font-semibold text-zinc-800 focus:outline-none focus:border-zinc-400 transition-colors shadow-3xs cursor-pointer">
                                                <option value="replied" {{ $ticket->status === 'replied' ? 'selected' : '' }}>Replied (Dibalas)</option>
                                                <option value="completed" {{ $ticket->status === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                                                <option value="on_hold" {{ $ticket->status === 'on_hold' ? 'selected' : '' }}>On Hold (Tertunda)</option>
                                                <option value="pending" {{ $ticket->status === 'pending' ? 'selected' : '' }}>Pending (Butuh Balasan)</option>
                                            </select>
                                        </div>
                                        <div class="mt-2 text-[8px] text-zinc-400 leading-relaxed font-mono uppercase tracking-widest font-bold">
                                            Status diperbarui bersamaan dengan pengiriman balasan.
                                        </div>
                                    </div>
                                </div>
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 h-9 px-4 bg-zinc-900 hover:bg-zinc-800 text-white rounded-lg text-xs font-bold transition-all focus:outline-none shadow-3xs">
                                    <i class="ph-bold ph-paper-plane-tilt"></i> Kirim Balasan & Update Status
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl border border-zinc-200 p-12 text-center shadow-3xs">
                    <i class="ph-bold ph-chat-circle-slash text-2xl text-zinc-300 mb-2"></i>
                    <h4 class="text-xs font-bold text-zinc-800 mb-0.5">Tidak ada feedback ditemukan</h4>
                    <p class="text-[10px] text-zinc-450 max-w-sm mx-auto font-medium">Belum ada tiket support atau feedback yang diajukan oleh pengguna dengan filter ini.</p>
                </div>
            @endforelse

            {{-- Pagination Links --}}
            @if($tickets->hasPages())
                <div class="pt-2 notion-pagination">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>