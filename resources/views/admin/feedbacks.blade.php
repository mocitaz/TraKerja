<x-admin-layout>
    <div class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-6 space-y-6 sm:space-y-8 pb-10" x-data="{ replyingTicketId: null }">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 sm:mb-8">
            <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white border border-slate-200/60 rounded-[1.25rem] sm:rounded-[1.5rem] flex items-center justify-center text-primary-600 shadow-sm shrink-0">
                    <i class="ph-duotone ph-chat-circle text-xl sm:text-2xl"></i>
                </div>
                <div class="flex flex-col min-w-0">
                    <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight truncate">User Feedback & Support</h3>
                    <p class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mt-1 sm:mt-1.5 truncate">Kelola feedback & tiket bantuan</p>
                </div>
            </div>
        </div>

        {{-- Flash Alerts --}}
        @if(session('success_message'))
            <div
                class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 shadow-sm animate-fade-in">
                <div class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow">
                    <i class="ph-bold ph-check text-base"></i>
                </div>
                <p class="text-sm font-semibold">{{ session('success_message') }}</p>
            </div>
        @endif

        {{-- Statistics Grid --}}
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            {{-- Total Tickets --}}
            <div class="bg-white rounded-[2rem] p-6 border border-slate-200/60 bento-card-stat relative overflow-hidden col-span-2 lg:col-span-1">
                <div class="absolute -right-6 -top-6 w-20 h-20 bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-full blur-2xl -z-10"></div>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Feedback</p>
                        <h3 class="text-xl lg:text-2xl font-black text-slate-900 tracking-tight">
                            {{ number_format($totalFeedbacks) }}</h3>
                        <p class="text-[9px] font-bold text-slate-400 mt-1">Semua tiket masuk</p>
                    </div>
                    <div class="w-10 h-10 rounded-[1.25rem] bg-indigo-50 flex items-center justify-center text-indigo-600 shadow-sm border border-indigo-100">
                        <i class="ph-bold ph-folder text-lg"></i>
                    </div>
                </div>
            </div>

            {{-- Pending Tickets --}}
            <div class="bg-white rounded-[2rem] p-6 border border-slate-200/60 bento-card-stat relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-20 h-20 bg-gradient-to-br from-amber-50 to-amber-100 rounded-full blur-2xl -z-10"></div>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pending</p>
                        <h3 class="text-xl lg:text-2xl font-black text-amber-600 tracking-tight flex items-center gap-1.5">
                            {{ number_format($pendingFeedbacks) }}
                            @if($pendingFeedbacks > 0)
                                <span class="flex h-2 w-2 relative">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                </span>
                            @endif
                        </h3>
                        <p class="text-[9px] font-bold text-slate-400 mt-1">Butuh balasan</p>
                    </div>
                    <div class="w-10 h-10 rounded-[1.25rem] bg-amber-50 flex items-center justify-center text-amber-600 shadow-sm border border-amber-100">
                        <i class="ph-bold ph-clock text-lg"></i>
                    </div>
                </div>
            </div>

            {{-- Replied Tickets --}}
            <div class="bg-white rounded-[2rem] p-6 border border-slate-200/60 bento-card-stat relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-20 h-20 bg-gradient-to-br from-sky-50 to-sky-100 rounded-full blur-2xl -z-10"></div>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Replied</p>
                        <h3 class="text-xl lg:text-2xl font-black text-sky-600 tracking-tight">
                            {{ number_format($repliedFeedbacks) }}</h3>
                        <p class="text-[9px] font-bold text-slate-400 mt-1">Telah direspon</p>
                    </div>
                    <div class="w-10 h-10 rounded-[1.25rem] bg-sky-50 flex items-center justify-center text-sky-600 shadow-sm border border-sky-100">
                        <i class="ph-bold ph-chat-circle-dots text-lg"></i>
                    </div>
                </div>
            </div>

            {{-- Completed Tickets --}}
            <div class="bg-white rounded-[2rem] p-6 border border-slate-200/60 bento-card-stat relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-20 h-20 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-full blur-2xl -z-10"></div>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Completed</p>
                        <h3 class="text-xl lg:text-2xl font-black text-emerald-600 tracking-tight">
                            {{ number_format($completedFeedbacks) }}</h3>
                        <p class="text-[9px] font-bold text-slate-400 mt-1">Selesai / ditutup</p>
                    </div>
                    <div class="w-10 h-10 rounded-[1.25rem] bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-sm border border-emerald-100">
                        <i class="ph-bold ph-check-circle text-lg"></i>
                    </div>
                </div>
            </div>

            {{-- On Hold Tickets --}}
            <div class="bg-white rounded-[2rem] p-6 border border-slate-200/60 bento-card-stat relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-20 h-20 bg-gradient-to-br from-rose-50 to-rose-100 rounded-full blur-2xl -z-10"></div>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">On Hold</p>
                        <h3 class="text-xl lg:text-2xl font-black text-rose-600 tracking-tight">
                            {{ number_format($onHoldFeedbacks) }}</h3>
                        <p class="text-[9px] font-bold text-slate-400 mt-1">Tertunda / review</p>
                    </div>
                    <div class="w-10 h-10 rounded-[1.25rem] bg-rose-50 flex items-center justify-center text-rose-600 shadow-sm border border-rose-100">
                        <i class="ph-bold ph-warning-circle text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter panel --}}
        <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 bento-card">
            <form action="{{ route('admin.feedbacks.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                <div class="w-full sm:w-48">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Filter Status</label>
                    <select name="status" onchange="this.form.submit()"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:bg-white focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending (Belum Dibalas)</option>
                        <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>Replied (Sudah Dibalas)</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed (Selesai / Tutup)</option>
                        <option value="on_hold" {{ request('status') === 'on_hold' ? 'selected' : '' }}>On Hold (Tertunda)</option>
                    </select>
                </div>
                <div class="w-full sm:w-56">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Filter Kategori</label>
                    <select name="category" onchange="this.form.submit()"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:bg-white focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                        <option value="">Semua Kategori</option>
                        <option value="technical_issue" {{ request('category') === 'technical_issue' ? 'selected' : '' }}>Technical Issue</option>
                        <option value="payment_billing" {{ request('category') === 'payment_billing' ? 'selected' : '' }}>Payment & Billing</option>
                        <option value="feature_request" {{ request('category') === 'feature_request' ? 'selected' : '' }}>Feature Request</option>
                        <option value="general_feedback" {{ request('category') === 'general_feedback' ? 'selected' : '' }}>General Feedback</option>
                    </select>
                </div>
                @if(request()->filled('status') || request()->filled('category'))
                    <a href="{{ route('admin.feedbacks.index') }}"
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 h-[34px] w-full sm:w-auto">
                        <i class="ph-bold ph-x"></i> Hapus Filter
                    </a>
                @endif
            </form>
        </div>

        {{-- Main Feedbacks List --}}
        <div class="space-y-4">
            @forelse($tickets as $ticket)
                @php
                    $statusClasses = [
                        'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                        'replied' => 'bg-sky-100 text-sky-800 border-sky-200',
                        'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                        'on_hold' => 'bg-rose-100 text-rose-800 border-rose-200',
                    ][$ticket->status] ?? 'bg-slate-100 text-slate-800 border-slate-200';

                    $statusIcon = [
                        'pending' => 'ph-clock',
                        'replied' => 'ph-chat-circle-dots',
                        'completed' => 'ph-check-circle',
                        'on_hold' => 'ph-warning-circle',
                    ][$ticket->status] ?? 'ph-question';
                    
                    $statusBgIcon = [
                        'pending' => 'bg-amber-50 text-amber-600',
                        'replied' => 'bg-sky-50 text-sky-600',
                        'completed' => 'bg-emerald-50 text-emerald-600',
                        'on_hold' => 'bg-rose-50 text-rose-600',
                    ][$ticket->status] ?? 'bg-slate-50 text-slate-600';
                @endphp

                <div class="bg-white border border-slate-200/60 rounded-[2rem] overflow-hidden bento-card transition-all duration-300"
                    x-data="{ expanded: false }">

                    {{-- Summary Card Row --}}
                    <div class="p-5 sm:p-6 flex flex-col md:flex-row md:items-center justify-between gap-5 cursor-pointer hover:bg-slate-50/20"
                        @click="expanded = !expanded">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 shadow-inner {{ $statusBgIcon }}">
                                <i class="ph-bold {{ $statusIcon }} text-lg"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-black border uppercase tracking-widest {{ $statusClasses }}">
                                        {{ $ticket->status }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-black bg-indigo-50 text-indigo-700 border border-indigo-100 uppercase tracking-widest">
                                        {{ $ticket->category_label }}
                                    </span>
                                </div>
                                <h4 class="text-base font-black text-slate-800 leading-snug tracking-tight mb-1">
                                    {{ $ticket->subject }}</h4>
                                <div class="flex items-center gap-2 text-xs text-slate-400 flex-wrap">
                                    <span class="font-extrabold text-slate-600">{{ $ticket->user ? $ticket->user->name : ($ticket->guest_name ?? 'Guest') }}</span>
                                    <span>({{ $ticket->user ? $ticket->user->email : ($ticket->guest_email ?? 'no-email') }})</span>
                                    <span class="w-1.5 h-1.5 bg-slate-200 rounded-full"></span>
                                    <span>{{ $ticket->created_at->format('d M Y H:i') }} WIB</span>
                                </div>
                            </div>
                        </div>

                        {{-- Quick Row Action --}}
                        <div class="flex items-center gap-3 self-end md:self-center">
                            <form action="{{ route('admin.feedbacks.destroy', $ticket->id) }}" method="POST" class="inline"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus feedback pengguna ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" @click.stop
                                    class="w-9 h-9 rounded-xl hover:bg-rose-50 text-slate-400 hover:text-rose-500 flex items-center justify-center transition-colors">
                                    <i class="ph-bold ph-trash text-lg"></i>
                                </button>
                            </form>
                            <button
                                class="px-4 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl text-xs font-bold transition-all duration-300 flex items-center gap-1.5">
                                <span x-text="expanded ? 'Tutup' : 'Lihat & Kelola'"></span>
                                <i class="ph-bold ph-caret-down text-xs transition-transform duration-300"
                                    :class="expanded ? 'rotate-180' : ''"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Expanded Detail View & Reply Box --}}
                    <div x-show="expanded" x-collapse x-cloak
                        class="border-t border-slate-100 bg-slate-50/40 p-6 space-y-6 animate-fade-in">

                        {{-- Original Message block --}}
                        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                            <h5
                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-1.5">
                                <i class="ph-bold ph-user-circle text-base text-slate-500"></i> Pesan Pengguna
                            </h5>
                            <p class="text-sm font-medium text-slate-700 leading-relaxed whitespace-pre-line">
                                {{ $ticket->message }}</p>
                        </div>

                        {{-- If already replied, render reply history --}}
                        @if($ticket->isReplied())
                            <div class="bg-indigo-50/20 border border-indigo-100 p-5 rounded-2xl shadow-sm">
                                <h5
                                    class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-3 flex items-center gap-1.5">
                                    <i class="ph-bold ph-shield-check text-base text-indigo-500"></i> Balasan Terkirim
                                </h5>
                                <p class="text-sm font-medium text-indigo-900 leading-relaxed whitespace-pre-line mb-3">
                                    {{ $ticket->admin_reply }}</p>
                                <p class="text-[10px] font-bold text-indigo-400">Dibalas oleh Admin pada
                                    {{ $ticket->replied_at->format('d M Y H:i') }} WIB</p>
                            </div>
                        @endif

                        {{-- Direct Status Selector --}}
                        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <h5 class="text-xs font-black text-slate-800 mb-1 flex items-center gap-1.5">
                                        <i class="ph-bold ph-sliders-horizontal text-base text-slate-500"></i> Ganti Status Tiket Secara Langsung
                                    </h5>
                                    <p class="text-[11px] font-medium text-slate-400">Ganti status tiket bantuan secara langsung tanpa harus menulis balasan pesan baru.</p>
                                </div>
                                <form action="{{ route('admin.feedbacks.status', $ticket->id) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" required
                                        class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:bg-white focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition-colors h-[36px]">
                                        <option value="pending" {{ $ticket->status === 'pending' ? 'selected' : '' }}>Pending (Butuh Balasan)</option>
                                        <option value="replied" {{ $ticket->status === 'replied' ? 'selected' : '' }}>Replied (Sudah Dibalas)</option>
                                        <option value="completed" {{ $ticket->status === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                                        <option value="on_hold" {{ $ticket->status === 'on_hold' ? 'selected' : '' }}>On Hold (Tertunda)</option>
                                    </select>
                                    <button type="submit" class="px-4 py-2 bg-slate-900 text-white hover:bg-slate-800 rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-sm h-[36px]">
                                        Simpan
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Reply form panel --}}
                        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                            <h5 class="text-xs font-black text-slate-800 mb-3 flex items-center gap-1.5">
                                <i class="ph-bold ph-arrow-bend-up-left text-base text-primary-500"></i>
                                {{ $ticket->isReplied() ? 'Kirim Balasan Baru / Perbarui Balasan' : 'Tulis Balasan Dukungan' }}
                            </h5>
                            <form action="{{ route('admin.feedbacks.reply', $ticket->id) }}" method="POST"
                                class="space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="md:col-span-2">
                                        <textarea name="admin_reply" rows="5" required
                                            placeholder="Tulis tanggapan atau instruksi solusi di sini..."
                                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all shadow-sm resize-none h-[140px]">{{ $ticket->admin_reply }}</textarea>
                                        @error('admin_reply')
                                            <p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="bg-slate-50/50 p-4 rounded-xl border border-slate-200/60 flex flex-col justify-between">
                                        <div>
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Update Status Tiket</label>
                                            <select name="status" required
                                                class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                                                <option value="replied" {{ $ticket->status === 'replied' ? 'selected' : '' }}>Replied (Dibalas)</option>
                                                <option value="completed" {{ $ticket->status === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                                                <option value="on_hold" {{ $ticket->status === 'on_hold' ? 'selected' : '' }}>On Hold (Tertunda)</option>
                                                <option value="pending" {{ $ticket->status === 'pending' ? 'selected' : '' }}>Pending (Butuh Balasan)</option>
                                            </select>
                                        </div>
                                        <div class="mt-3 text-[10px] text-slate-400 leading-relaxed font-bold uppercase tracking-wider">
                                            Status diperbarui bersamaan dengan pengiriman balasan.
                                        </div>
                                    </div>
                                </div>
                                <button type="submit"
                                    class="px-6 py-2.5 bg-slate-900 text-white hover:bg-slate-800 rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-md flex items-center gap-1.5">
                                    <i class="ph-bold ph-paper-plane-tilt"></i> Kirim Balasan & Update Status
                                </button>
                            </form>
                        </div>

                    </div>

                </div>
            @empty
                <div class="bg-white rounded-[2rem] border border-slate-200/60 p-12 text-center bento-card">
                    <div
                        class="w-16 h-16 mx-auto bg-slate-50 text-slate-400 rounded-[1.25rem] flex items-center justify-center mb-4 border border-slate-200/60 shadow-sm">
                        <i class="ph-duotone ph-chat-circle-slash text-3xl"></i>
                    </div>
                    <h4 class="text-base font-black text-slate-800 mb-1">Tidak ada feedback ditemukan</h4>
                    <p class="text-xs font-medium text-slate-500 max-w-sm mx-auto leading-relaxed">Belum ada tiket support
                        atau feedback yang diajukan oleh pengguna dengan filter ini.</p>
                </div>
            @endforelse

            {{-- Pagination Links --}}
            @if($tickets->hasPages())
                <div class="pt-4">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>

    </div>
</x-admin-layout>