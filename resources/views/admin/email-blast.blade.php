<x-admin-layout>
@php
    $baseQuery = \App\Models\User::where('role', '!=', 'admin');
    
    // Calculate Audience Stats
    $stats = [
        'all' => (clone $baseQuery)->count(),
        'new' => (clone $baseQuery)->where('created_at', '>=', \Carbon\Carbon::now()->subDays(7))->count(),
        'unverified' => (clone $baseQuery)->whereNull('email_verified_at')->count(),
        'verified' => (clone $baseQuery)->whereNotNull('email_verified_at')->count(),
        'premium' => (clone $baseQuery)->where(function($q) {
            $q->where('is_premium', true)->orWhere('payment_status', 'paid');
        })->count(),
        'free' => (clone $baseQuery)->where(function($q) {
            $q->where('is_premium', false)->orWhereNull('payment_status')->orWhere('payment_status', '!=', 'paid');
        })->count(),
    ];
@endphp

<<div class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-5 space-y-4 pb-10">
    <!-- Sticky Global Sub-Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3.5 border-b border-zinc-150/60">
        <div class="flex items-center gap-1.5 min-w-0">
            <span class="text-xs font-mono font-bold text-zinc-400 uppercase tracking-wider">Admin Portal</span>
            <span class="text-zinc-300 text-xs">/</span>
            <h1 class="text-xs font-mono font-bold text-zinc-800 uppercase tracking-wider">Email Blast</h1>
        </div>
        <div>
            <a href="{{ route('admin.email-blast.history') }}" 
               class="inline-flex items-center justify-center gap-1.5 h-8 px-3 border border-zinc-250 hover:bg-zinc-50 rounded text-xs font-semibold text-zinc-800 transition-colors bg-white shadow-none">
                <i class="ph ph-clock-counter-clockwise text-sm"></i>
                Riwayat Blasting
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded flex items-start gap-3 shadow-none">
            <i class="ph ph-check-circle text-lg text-emerald-600 mt-0.5"></i>
            <div class="space-y-1">
                <h3 class="text-xs font-bold">{{ session('success') }}</h3>
                @if(session('details'))
                    <div class="mt-2 text-[11px] space-y-0.5 text-emerald-700 bg-emerald-100/10 p-2.5 rounded border border-emerald-200/40 font-mono">
                        <p>Total Target: {{ session('details')['total'] }} users</p>
                        <p>Sent: {{ session('details')['success'] }} users</p>
                        @if(isset(session('details')['failed']) && session('details')['failed'] > 0)
                            <p class="font-bold text-red-600 mt-1">Failed: {{ session('details')['failed'] }} users</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded flex items-start gap-3 shadow-none">
            <i class="ph ph-warning-circle text-lg text-red-600 mt-0.5"></i>
            <div>
                <h3 class="text-xs font-bold">{{ session('error') }}</h3>
            </div>
        </div>
    @endif

    <!-- Main Form -->
    <form action="{{ route('admin.email-blast.send') }}" method="POST" id="emailBlastForm">
        @csrf
        <div class="space-y-4">
            
            <!-- Campaign Type Selection -->
            <div class="bg-white rounded border border-zinc-200/60 overflow-hidden shadow-none">
                <div class="px-4 py-3 border-b border-zinc-150/60 bg-zinc-50/20">
                    <h2 class="text-xs font-bold text-zinc-900 tracking-tight flex items-center gap-1.5">
                        <i class="ph ph-envelope text-zinc-400 text-sm"></i>
                        Pilih Tipe Kampanye
                    </h2>
                    <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wide mt-0.5">Template Email yang Akan Dikirim</p>
                </div>
                
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <!-- Welcome -->
                        <label class="group relative flex cursor-pointer rounded border border-zinc-200/80 p-3 hover:bg-[#f7f7f5]/40 transition-colors shadow-none">
                            <input type="radio" name="email_type" value="welcome" class="sr-only" required>
                            <div class="flex items-center gap-3 w-full">
                                <div class="w-8 h-8 rounded bg-zinc-50 border border-zinc-200/40 text-zinc-500 flex items-center justify-center shrink-0">
                                    <i class="ph ph-hand-waving text-base"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-xs font-bold text-zinc-900 leading-none">Welcome Email</h3>
                                    <p class="text-[10px] text-zinc-500 mt-1 leading-snug">Sambut pengguna baru ke platform TraKerja.</p>
                                </div>
                                <div class="indicator hidden w-4 h-4 rounded-full bg-purple-650 text-white flex items-center justify-center shrink-0">
                                    <i class="ph ph-check text-[9px]"></i>
                                </div>
                            </div>
                        </label>

                        <!-- Verification -->
                        <label class="group relative flex cursor-pointer rounded border border-zinc-200/80 p-3 hover:bg-[#f7f7f5]/40 transition-colors shadow-none">
                            <input type="radio" name="email_type" value="verification" class="sr-only" required>
                            <div class="flex items-center gap-3 w-full">
                                <div class="w-8 h-8 rounded bg-zinc-50 border border-zinc-200/40 text-zinc-500 flex items-center justify-center shrink-0">
                                    <i class="ph ph-shield-check text-base"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-xs font-bold text-zinc-900 leading-none">Verification Email</h3>
                                    <p class="text-[10px] text-zinc-500 mt-1 leading-snug">Kirim ulang link verifikasi akun.</p>
                                </div>
                                <div class="indicator hidden w-4 h-4 rounded-full bg-purple-650 text-white flex items-center justify-center shrink-0">
                                    <i class="ph ph-check text-[9px]"></i>
                                </div>
                            </div>
                        </label>

                        <!-- Verification Reminder -->
                        <label class="group relative flex cursor-pointer rounded border border-zinc-200/80 p-3 hover:bg-[#f7f7f5]/40 transition-colors shadow-none">
                            <input type="radio" name="email_type" value="verification_reminder" class="sr-only" required>
                            <div class="flex items-center gap-3 w-full">
                                <div class="w-8 h-8 rounded bg-zinc-50 border border-zinc-200/40 text-zinc-500 flex items-center justify-center shrink-0">
                                    <i class="ph ph-warning-circle text-base"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-xs font-bold text-zinc-900 leading-none">Verif. Reminder</h3>
                                    <p class="text-[10px] text-zinc-500 mt-1 leading-snug">Pengingat belum verifikasi (Mendesak).</p>
                                </div>
                                <div class="indicator hidden w-4 h-4 rounded-full bg-purple-650 text-white flex items-center justify-center shrink-0">
                                    <i class="ph ph-check text-[9px]"></i>
                                </div>
                            </div>
                        </label>

                        <!-- AI Analyzer -->
                        <label class="group relative flex cursor-pointer rounded border border-zinc-200/80 p-3 hover:bg-[#f7f7f5]/40 transition-colors shadow-none">
                            <input type="radio" name="email_type" value="ai_analyzer" class="sr-only" required>
                            <div class="flex items-center gap-3 w-full">
                                <div class="w-8 h-8 rounded bg-zinc-50 border border-zinc-200/40 text-zinc-500 flex items-center justify-center shrink-0">
                                    <i class="ph ph-robot text-base"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-xs font-bold text-zinc-900 leading-none">AI Analyzer Trial</h3>
                                    <p class="text-[10px] text-zinc-500 mt-1 leading-snug">Promosikan fitur AI Review Resume.</p>
                                </div>
                                <div class="indicator hidden w-4 h-4 rounded-full bg-purple-650 text-white flex items-center justify-center shrink-0">
                                    <i class="ph ph-check text-[9px]"></i>
                                </div>
                            </div>
                        </label>

                        <!-- Suasana Baru -->
                        <label class="group relative flex cursor-pointer rounded border border-zinc-200/80 p-3 hover:bg-[#f7f7f5]/40 transition-colors shadow-none">
                            <input type="radio" name="email_type" value="new_vibe" class="sr-only" required>
                            <div class="flex items-center gap-3 w-full">
                                <div class="w-8 h-8 rounded bg-zinc-50 border border-zinc-200/40 text-zinc-500 flex items-center justify-center shrink-0">
                                    <i class="ph ph-sparkles text-base"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-xs font-bold text-zinc-900 leading-none">Suasana Baru TraKerja</h3>
                                    <p class="text-[10px] text-zinc-500 mt-1 leading-snug">Pengumuman penyegaran antarmuka dan transisi pengalaman karier baru.</p>
                                </div>
                                <div class="indicator hidden w-4 h-4 rounded-full bg-purple-650 text-white flex items-center justify-center shrink-0">
                                    <i class="ph ph-check text-[9px]"></i>
                                </div>
                            </div>
                        </label>

                        <!-- Hiring Season Alert -->
                        <label class="group relative flex cursor-pointer rounded border border-zinc-200/80 p-3 hover:bg-[#f7f7f5]/40 transition-colors shadow-none">
                            <input type="radio" name="email_type" value="hiring_season" class="sr-only" required>
                            <div class="flex items-center gap-3 w-full">
                                <div class="w-8 h-8 rounded bg-zinc-50 border border-zinc-200/40 text-zinc-500 flex items-center justify-center shrink-0">
                                    <i class="ph ph-buildings text-base"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-xs font-bold text-zinc-900 leading-none">Hiring Season Alert</h3>
                                    <p class="text-[10px] text-zinc-500 mt-1 leading-snug">Informasi musim rekrutmen aktif dan tips strategis mencari kerja.</p>
                                </div>
                                <div class="indicator hidden w-4 h-4 rounded-full bg-purple-650 text-white flex items-center justify-center shrink-0">
                                    <i class="ph ph-check text-[9px]"></i>
                                </div>
                            </div>
                        </label>

                        <!-- Follow Up Feature Announcement -->
                        <label class="group relative flex cursor-pointer rounded border border-zinc-200/80 p-3 hover:bg-[#f7f7f5]/40 transition-colors shadow-none">
                            <input type="radio" name="email_type" value="follow_up_feature" class="sr-only" required>
                            <div class="flex items-center gap-3 w-full">
                                <div class="w-8 h-8 rounded bg-zinc-50 border border-zinc-200/40 text-zinc-500 flex items-center justify-center shrink-0">
                                    <i class="ph ph-paper-plane-tilt text-base"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-xs font-bold text-zinc-900 leading-none">Follow Up Feature</h3>
                                    <p class="text-[10px] text-zinc-500 mt-1 leading-snug">Pengumuman fitur baru AI Follow Up Email (Tanya Kabar).</p>
                                </div>
                                <div class="indicator hidden w-4 h-4 rounded-full bg-purple-650 text-white flex items-center justify-center shrink-0">
                                    <i class="ph ph-check text-[9px]"></i>
                                </div>
                            </div>
                        </label>

                        <!-- Chrome Extension Promo -->
                        <label class="group relative flex cursor-pointer rounded border border-zinc-200/80 p-3 hover:bg-[#f7f7f5]/40 transition-colors shadow-none">
                            <input type="radio" name="email_type" value="chrome_extension" class="sr-only" required>
                            <div class="flex items-center gap-3 w-full">
                                <div class="w-8 h-8 rounded bg-zinc-50 border border-zinc-200/40 text-zinc-500 flex items-center justify-center shrink-0">
                                    <i class="ph ph-puzzle-piece text-base"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-xs font-bold text-zinc-900 leading-none">Chrome Extension Announcement</h3>
                                    <p class="text-[10px] text-zinc-500 mt-1 leading-snug">Promosikan fitur ekstensi TraKerja untuk LinkedIn, JobStreet, dsb.</p>
                                </div>
                                <div class="indicator hidden w-4 h-4 rounded-full bg-purple-650 text-white flex items-center justify-center shrink-0">
                                    <i class="ph ph-check text-[9px]"></i>
                                </div>
                            </div>
                        </label>

                        <!-- AI Photo Announcement -->
                        <label class="group relative flex cursor-pointer rounded border border-zinc-200/80 p-3 hover:bg-[#f7f7f5]/40 transition-colors shadow-none">
                            <input type="radio" name="email_type" value="ai_photo" class="sr-only" required>
                            <div class="flex items-center gap-3 w-full">
                                <div class="w-8 h-8 rounded bg-zinc-50 border border-zinc-200/40 text-zinc-500 flex items-center justify-center shrink-0">
                                    <i class="ph ph-camera text-base"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-xs font-bold text-zinc-900 leading-none">AI Photo Announcement</h3>
                                    <p class="text-[10px] text-zinc-500 mt-1 leading-snug">Promosikan fitur AI Photo Studio untuk pas foto profesional.</p>
                                </div>
                                <div class="indicator hidden w-4 h-4 rounded-full bg-purple-650 text-white flex items-center justify-center shrink-0">
                                    <i class="ph ph-check text-[9px]"></i>
                                </div>
                            </div>
                        </label>

                        <!-- Re-engagement -->
                        <label class="group relative flex cursor-pointer rounded border border-zinc-200/80 p-3 hover:bg-[#f7f7f5]/40 transition-colors shadow-none">
                            <input type="radio" name="email_type" value="re_engagement" class="sr-only" required>
                            <div class="flex items-center gap-3 w-full">
                                <div class="w-8 h-8 rounded bg-zinc-50 border border-zinc-200/40 text-zinc-500 flex items-center justify-center shrink-0">
                                    <i class="ph ph-hand-heart text-base"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-xs font-bold text-zinc-900 leading-none">Re-engagement</h3>
                                    <p class="text-[10px] text-zinc-500 mt-1 leading-snug">Ajak kembali pengguna yang sudah lama tidak aktif di platform.</p>
                                </div>
                                <div class="indicator hidden w-4 h-4 rounded-full bg-purple-650 text-white flex items-center justify-center shrink-0">
                                    <i class="ph ph-check text-[9px]"></i>
                                </div>
                            </div>
                        </label>

                        <!-- Maintenance Completed -->
                        <label class="group relative flex cursor-pointer rounded border border-zinc-200/80 p-3 hover:bg-[#f7f7f5]/40 transition-colors shadow-none">
                            <input type="radio" name="email_type" value="maintenance_completed" class="sr-only" required>
                            <div class="flex items-center gap-3 w-full">
                                <div class="w-8 h-8 rounded bg-zinc-50 border border-zinc-200/40 text-zinc-500 flex items-center justify-center shrink-0">
                                    <i class="ph ph-check-circle text-base"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-xs font-bold text-zinc-900 leading-none">Maintenance Selesai</h3>
                                    <p class="text-[10px] text-zinc-500 mt-1 leading-snug">Pemberitahuan bahwa TraKerja sudah bisa digunakan kembali.</p>
                                </div>
                                <div class="indicator hidden w-4 h-4 rounded-full bg-purple-650 text-white flex items-center justify-center shrink-0">
                                    <i class="ph ph-check text-[9px]"></i>
                                </div>
                            </div>
                        </label>

                        <!-- Custom Email -->
                        <label class="group relative flex cursor-pointer rounded border border-zinc-200/80 p-3 hover:bg-[#f7f7f5]/40 transition-colors shadow-none">
                            <input type="radio" name="email_type" value="custom" class="sr-only" required>
                            <div class="flex items-center gap-3 w-full">
                                <div class="w-8 h-8 rounded bg-zinc-50 border border-zinc-200/40 text-zinc-500 flex items-center justify-center shrink-0">
                                    <i class="ph ph-note-pencil text-base"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-xs font-bold text-zinc-900 leading-none">Custom Campaign</h3>
                                    <p class="text-[10px] text-zinc-500 mt-1 leading-snug">Tulis subjek dan isi konten email secara manual sesuka Anda.</p>
                                </div>
                                <div class="indicator hidden w-4 h-4 rounded-full bg-purple-650 text-white flex items-center justify-center shrink-0">
                                    <i class="ph ph-check text-[9px]"></i>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Hari Peringatan Accordion -->
                    <div class="pt-3 border-t border-zinc-150/60 mt-3">
                        <button type="button" onclick="toggleHariPeringatan()" 
                                class="w-full flex items-center justify-between p-3 bg-zinc-50/50 hover:bg-[#f7f7f5]/80 border border-zinc-200 rounded transition-colors focus:outline-none">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded bg-zinc-100 border border-zinc-200/40 text-zinc-500 flex items-center justify-center shrink-0">
                                    <i class="ph ph-calendar-star text-base"></i>
                                </div>
                                <div class="text-left flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="text-xs font-bold text-zinc-900 leading-none">Hari Peringatan Nasional/Internasional</h3>
                                        <span class="text-[8px] font-mono font-bold px-1.5 py-0.5 bg-amber-50 text-amber-700 rounded border border-amber-200">Effective: May-Dec 2026</span>
                                    </div>
                                    <p class="text-[10px] text-zinc-400 mt-1">Template email khusus perayaan dan hari besar.</p>
                                </div>
                            </div>
                            <i id="hariPeringatanIcon" class="ph ph-caret-down text-zinc-400 text-xs transition-transform"></i>
                        </button>
                        
                        @php
                            $now = \Carbon\Carbon::now()->startOfDay();
                            $events = [
                                'idul_adha' => ['date' => \Carbon\Carbon::parse('2026-05-27'), 'label' => '27 Mei 2026'],
                                'waisak' => ['date' => \Carbon\Carbon::parse('2026-05-31'), 'label' => '31 Mei 2026'],
                                'pancasila' => ['date' => \Carbon\Carbon::parse('2026-06-01'), 'label' => '1 Jun 2026'],
                                'tahun_baru_islam' => ['date' => \Carbon\Carbon::parse('2026-06-16'), 'label' => '16 Jun 2026'],
                                'kemerdekaan_ri' => ['date' => \Carbon\Carbon::parse('2026-08-17'), 'label' => '17 Agu 2026'],
                                'maulid_nabi' => ['date' => \Carbon\Carbon::parse('2026-08-25'), 'label' => '25 Agu 2026'],
                                'sumpah_pemuda' => ['date' => \Carbon\Carbon::parse('2026-10-28'), 'label' => '28 Okt 2026'],
                                'pahlawan' => ['date' => \Carbon\Carbon::parse('2026-11-10'), 'label' => '10 Nov 2026'],
                                'guru_nasional' => ['date' => \Carbon\Carbon::parse('2026-11-25'), 'label' => '25 Nov 2026'],
                                'hari_ibu' => ['date' => \Carbon\Carbon::parse('2026-12-22'), 'label' => '22 Des 2026'],
                                'natal' => ['date' => \Carbon\Carbon::parse('2026-12-25'), 'label' => '25 Des 2026'],
                            ];

                            foreach($events as $key => &$event) {
                                if ($now->isSameDay($event['date'])) {
                                    $event['status'] = 'Hari Ini';
                                    $event['badge'] = 'bg-blue-50 text-blue-600 border-blue-100';
                                } elseif ($now->gt($event['date'])) {
                                    $event['status'] = 'Lewat';
                                    $event['badge'] = 'bg-zinc-100 text-zinc-400 border-zinc-200';
                                } else {
                                    $event['status'] = 'Akan Datang';
                                    $event['badge'] = 'bg-emerald-50 text-emerald-600 border-emerald-150';
                                }
                            }
                        @endphp
                        
                        <div id="hariPeringatanContainer" class="hidden mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($events as $eventKey => $evt)
                                @php
                                    $evtIcons = [
                                        'idul_adha' => 'ph-mosque', 'waisak' => 'ph-sun-dim', 'pancasila' => 'ph-flag',
                                        'tahun_baru_islam' => 'ph-moon', 'kemerdekaan_ri' => 'ph-sparkles',
                                        'maulid_nabi' => 'ph-star', 'sumpah_pemuda' => 'ph-users',
                                        'pahlawan' => 'ph-shield', 'guru_nasional' => 'ph-book-open',
                                        'hari_ibu' => 'ph-heart', 'natal' => 'ph-gift'
                                    ];
                                    $icon = $evtIcons[$eventKey] ?? 'ph-calendar-check';
                                @endphp
                                <label class="group relative flex cursor-pointer rounded border border-zinc-200/80 p-3 hover:bg-[#f7f7f5]/40 transition-colors shadow-none">
                                    <input type="radio" name="email_type" value="{{ $eventKey }}" class="sr-only" required>
                                    <div class="flex items-center gap-3 w-full">
                                        <div class="w-8 h-8 rounded bg-zinc-50 border border-zinc-200/40 text-zinc-500 flex items-center justify-center shrink-0">
                                            <i class="ph {{ $icon }} text-base"></i>
                                        </div>
                                        <div class="flex-1 min-w-0 text-left">
                                            <div class="flex flex-wrap items-center gap-1.5">
                                                <h3 class="text-xs font-bold text-zinc-900 leading-none">{{ ucwords(str_replace('_', ' ', $eventKey)) }}</h3>
                                                <span class="text-[8px] font-mono font-bold px-1 py-0.5 rounded border {{ $evt['badge'] }}">
                                                    {{ $evt['label'] }} &bull; {{ $evt['status'] }}
                                                </span>
                                            </div>
                                            <p class="text-[10px] text-zinc-500 mt-1">Ucapan & Blast Peringatan {{ ucwords(str_replace('_', ' ', $eventKey)) }}.</p>
                                        </div>
                                        <div class="indicator hidden w-4 h-4 rounded-full bg-purple-650 text-white flex items-center justify-center shrink-0">
                                            <i class="ph ph-check text-[9px]"></i>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Custom Content Fields (Show when "custom" is selected) -->
            <div id="customEmailFields" class="bg-white rounded border border-zinc-200/60 overflow-hidden transition-all duration-300 hidden shadow-none">
                <div class="px-4 py-3 border-b border-zinc-150/60 bg-zinc-50/20">
                    <h2 class="text-xs font-bold text-zinc-900 tracking-tight flex items-center gap-1.5">
                        <i class="ph ph-note-pencil text-zinc-400 text-sm"></i>
                        Custom Campaign Composer
                    </h2>
                </div>
                <div class="p-4 space-y-4">
                    <div>
                        <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide mb-1">Subject Email *</label>
                        <input type="text" name="custom_subject" id="custom_subject" 
                               class="w-full px-3 h-8 bg-white border border-zinc-250 rounded text-xs text-zinc-900 focus:outline-none focus:border-zinc-400 transition-colors" 
                               placeholder="Masukkan subjek kampanye email">
                    </div>
                    
                    <div>
                        <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide mb-1">Isi Konten Email *</label>
                        <textarea name="custom_content" id="custom_content" rows="6" 
                                  class="w-full px-3 py-2 bg-white border border-zinc-250 rounded text-xs text-zinc-900 focus:outline-none focus:border-zinc-400 transition-colors resize-none" 
                                  placeholder="Tulis pesan lengkap Anda di sini... (Mendukung paragraf baru)"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-3 border-t border-zinc-150/60">
                        <div>
                            <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide mb-1">Teks Tombol Aksi (Opsional)</label>
                            <input type="text" name="custom_button_text" id="custom_button_text" 
                                   class="w-full px-3 h-8 bg-white border border-zinc-250 rounded text-xs text-zinc-900 focus:outline-none focus:border-zinc-400 transition-colors" 
                                   placeholder="Contoh: Mulai Catat Lamaran">
                        </div>
                        <div>
                            <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide mb-1">URL Tujuan Tombol Aksi (Opsional)</label>
                            <input type="url" name="custom_button_url" id="custom_button_url" 
                                   class="w-full px-3 h-8 bg-white border border-zinc-250 rounded text-xs text-zinc-900 focus:outline-none focus:border-zinc-400 transition-colors" 
                                   placeholder="https://trakerja.com/career-tracker">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Target Audience Selection -->
            <div class="bg-white rounded border border-zinc-200/60 overflow-hidden shadow-none">
                <div class="px-4 py-3 border-b border-zinc-150/60 bg-zinc-50/20">
                    <h2 class="text-xs font-bold text-zinc-900 tracking-tight flex items-center gap-1.5">
                        <i class="ph ph-users text-zinc-400 text-sm"></i>
                        Pilih Target Audience
                    </h2>
                    <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wide mt-0.5">Filter Pengguna Penerima Email</p>
                </div>
                
                <div class="p-4">
                    <div class="grid grid-cols-2 md:grid-cols-6 gap-3">
                        @foreach([
                            'all' => ['label' => 'Semua User', 'count' => $stats['all'], 'icon' => 'ph-users'],
                            'new' => ['label' => 'Baru (7 Hari)', 'count' => $stats['new'], 'icon' => 'ph-user-plus'],
                            'unverified' => ['label' => 'Unverified', 'count' => $stats['unverified'], 'icon' => 'ph-envelope'],
                            'verified' => ['label' => 'Verified', 'count' => $stats['verified'], 'icon' => 'ph-seal-check'],
                            'premium' => ['label' => 'Premium', 'count' => $stats['premium'], 'icon' => 'ph-crown'],
                            'free' => ['label' => 'Free', 'count' => $stats['free'], 'icon' => 'ph-user'],
                        ] as $key => $info)
                            <label class="group relative flex flex-col cursor-pointer rounded border border-zinc-200 p-3.5 items-center justify-between text-center hover:bg-[#f7f7f5]/40 transition-colors shadow-none">
                                <input type="radio" name="target_user" value="{{ $key }}" class="sr-only" required>
                                <div class="w-8 h-8 rounded bg-zinc-50 border border-zinc-200/40 text-zinc-500 flex items-center justify-center mb-2.5 shrink-0">
                                    <i class="ph {{ $info['icon'] }} text-base"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[10px] font-bold text-zinc-900 truncate leading-none mb-1.5">{{ $info['label'] }}</p>
                                    <p class="text-[9px] font-mono font-bold text-zinc-400">{{ number_format($info['count']) }} users</p>
                                </div>
                                <div class="target-indicator hidden absolute right-1.5 top-1.5 w-3.5 h-3.5 rounded-full bg-purple-650 text-white flex items-center justify-center shrink-0">
                                    <i class="ph ph-check text-[8px]"></i>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Action Ribbon -->
            <div class="flex items-center gap-3">
                <button type="button" onclick="showPreviewModal()" 
                        class="flex-1 sm:flex-initial h-8 px-4 bg-white border border-zinc-300 hover:bg-zinc-50 text-zinc-800 rounded text-xs font-semibold transition-colors flex items-center justify-center gap-1.5 shadow-none focus:outline-none">
                    <i class="ph ph-eye text-sm"></i>
                    Preview Email
                </button>
                <button type="button" onclick="showPreviewModal()" 
                        class="flex-1 sm:flex-initial h-8 px-4 bg-zinc-900 hover:bg-zinc-800 text-white rounded text-xs font-semibold transition-colors flex items-center justify-center gap-1.5 shadow-none focus:outline-none">
                    <i class="ph ph-paper-plane-tilt text-sm"></i>
                    Proses Blasting
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Modal: Error Alert -->
<div id="errorModal" class="fixed inset-0 bg-zinc-950/20 backdrop-blur-[2px] hidden z-[9999] flex items-center justify-center p-4">
    <div class="bg-white rounded max-w-sm w-full border border-zinc-200/80 relative animate-in fade-in zoom-in-95 duration-150 shadow-none">
        <div class="p-5 text-center">
            <div class="w-10 h-10 bg-red-50 text-red-500 border border-red-200/40 rounded-lg flex items-center justify-center mx-auto mb-3">
                <i class="ph ph-warning-circle text-lg"></i>
            </div>
            <h3 class="text-xs font-bold text-zinc-900">Validasi Gagal</h3>
            <p id="errorMessage" class="text-[11px] text-zinc-500 leading-relaxed mt-2"></p>
            
            <div class="mt-4">
                <button type="button" onclick="hideErrorModal()" 
                        class="w-full h-8 bg-zinc-900 text-white rounded text-xs font-semibold hover:bg-zinc-800 transition-colors shadow-none focus:outline-none">
                    Mengerti, Perbaiki
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Preview Email -->
<div id="previewModal" class="fixed inset-0 bg-zinc-950/20 backdrop-blur-[2px] hidden z-[9999] flex items-center justify-center p-4">
    <div class="bg-white rounded max-w-3xl w-full max-h-[90vh] flex flex-col overflow-hidden border border-zinc-200/85 animate-in fade-in zoom-in-95 duration-150 shadow-none">
        
        {{-- Modal Header --}}
        <div class="bg-white px-4 py-3 flex justify-between items-center border-b border-zinc-150/60 shrink-0">
            <div class="flex items-center gap-2">
                <i class="ph ph-eye text-zinc-400 text-base"></i>
                <div class="text-left">
                    <h3 class="text-xs font-bold text-zinc-900 font-sans">Preview Email</h3>
                    <p class="text-[9px] text-zinc-450 mt-0.5 font-sans">Tampilan Email Asli</p>
                </div>
            </div>
            <button type="button" onclick="hidePreviewModal()" class="w-6 h-6 flex items-center justify-center rounded hover:bg-zinc-100 text-zinc-400 hover:text-zinc-800 transition-colors focus:outline-none">
                <i class="ph ph-x text-sm"></i>
            </button>
        </div>
        
        <div class="p-0 bg-zinc-50 flex-1 overflow-hidden flex flex-col items-center justify-center relative min-h-[50vh]">
            <!-- Loader -->
            <div id="previewLoader" class="absolute inset-0 bg-zinc-50/90 flex flex-col items-center justify-center z-10">
                <i class="ph ph-spinner animate-spin text-2xl text-zinc-500 mb-1.5"></i>
                <p class="text-[10px] font-mono font-bold text-zinc-450 uppercase tracking-wider">Memuat Preview...</p>
            </div>
            
            <!-- Iframe for Preview -->
            <div class="w-full h-full bg-white relative flex-1">
                <iframe id="previewFrame" class="w-full h-full absolute inset-0 border-0" sandbox="allow-same-origin"></iframe>
            </div>
        </div>
        
        <div class="bg-zinc-50/50 px-4 py-3 flex flex-row-reverse gap-2.5 border-t border-zinc-150/60 shrink-0">
            <button type="button" onclick="proceedToConfirm()" 
                    class="flex-1 sm:flex-none h-8 px-4 bg-zinc-900 text-white rounded text-xs font-semibold hover:bg-zinc-800 transition-colors focus:outline-none">
                Lanjut Konfirmasi
            </button>
            <button type="button" onclick="hidePreviewModal()" 
                    class="flex-1 sm:flex-none h-8 px-4 bg-white border border-zinc-250 text-zinc-650 hover:bg-zinc-50 rounded text-xs font-semibold transition-colors focus:outline-none">
                Batal
            </button>
        </div>
    </div>
</div>

<!-- Modal: Send Confirmation -->
<div id="confirmModal" class="fixed inset-0 bg-zinc-950/20 backdrop-blur-[2px] hidden z-[9999] flex items-center justify-center p-4">
    <div class="bg-white rounded max-w-sm w-full border border-zinc-200/80 relative animate-in fade-in zoom-in-95 duration-150 shadow-none">
        
        {{-- Modal Header --}}
        <div class="bg-white px-4 py-3 flex justify-between items-center border-b border-zinc-150/60 shrink-0">
            <div class="flex items-center gap-2">
                <i class="ph ph-paper-plane text-zinc-400 text-base"></i>
                <div class="text-left">
                    <h3 class="text-xs font-bold text-zinc-900 font-sans">Konfirmasi</h3>
                    <p class="text-[9px] text-zinc-450 mt-0.5 font-sans">Pengiriman Serentak</p>
                </div>
            </div>
            <button type="button" onclick="hideConfirmModal()" class="w-6 h-6 flex items-center justify-center rounded hover:bg-zinc-100 text-zinc-400 hover:text-zinc-800 transition-colors focus:outline-none">
                <i class="ph ph-x text-sm"></i>
            </button>
        </div>

        <div class="p-4 space-y-4 text-left">
            <p class="text-[11px] text-zinc-500 leading-relaxed font-sans">Email akan segera diproses dan dikirimkan secara serentak ke kotak masuk target audience Anda.</p>
            
            <div class="bg-zinc-50 border border-zinc-200/60 rounded p-3 space-y-2.5">
                <div class="flex justify-between items-center text-[10px]">
                    <span class="font-mono font-bold text-zinc-400 uppercase tracking-wide">Tipe Kampanye</span>
                    <span id="confirmEmailType" class="font-bold text-zinc-800"></span>
                </div>
                <div class="flex justify-between items-center text-[10px] pt-2.5 border-t border-zinc-150/60">
                    <span class="font-mono font-bold text-zinc-400 uppercase tracking-wide">Target Audience</span>
                    <span id="confirmTargetUser" class="font-bold text-purple-650 bg-purple-50/50 px-2 py-0.5 rounded border border-purple-100 text-[9px] font-sans"></span>
                </div>
            </div>
            
            <div class="flex gap-2">
                <button type="button" onclick="submitEmailBlast()" 
                        class="flex-1 h-8 bg-zinc-900 hover:bg-zinc-800 text-white rounded text-xs font-semibold transition-colors focus:outline-none">
                    Kirim Sekarang
                </button>
                <button type="button" onclick="hideConfirmModal()" 
                        class="flex-1 h-8 bg-white border border-zinc-250 hover:bg-zinc-50 text-zinc-650 rounded text-xs font-semibold transition-colors focus:outline-none">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Blasting Send Progress -->
<div id="progressModal" class="fixed inset-0 bg-zinc-950/20 backdrop-blur-[2px] hidden z-[9999] flex items-center justify-center p-4">
    <div class="bg-white rounded max-w-lg w-full max-h-[90vh] flex flex-col overflow-hidden border border-zinc-200/80 animate-in fade-in zoom-in-95 duration-150 shadow-none">
        
        {{-- Modal Header --}}
        <div class="bg-white px-4 py-3 flex justify-between items-center border-b border-zinc-150/60 shrink-0">
            <div class="flex items-center gap-2">
                <i class="ph ph-paper-plane-tilt text-zinc-400 text-base animate-pulse"></i>
                <div class="text-left">
                    <h3 class="text-xs font-bold text-zinc-900 font-sans">Blasting Email</h3>
                    <p id="progressStatusText" class="text-[9px] text-zinc-450 mt-0.5 font-sans">Mempersiapkan Audience</p>
                </div>
            </div>
        </div>

        <div class="p-4 space-y-4 overflow-y-auto custom-scrollbar flex-1 text-left">
            <div class="space-y-2">
                <div class="flex justify-between items-end text-[10px]">
                    <span class="font-mono font-bold text-zinc-400 uppercase tracking-wide">Progress Pengiriman</span>
                    <span id="progressPercent" class="font-bold text-zinc-900">0%</span>
                </div>
                
                {{-- Progress Bar --}}
                <div class="w-full bg-zinc-100 rounded-full h-1.5 overflow-hidden">
                    <div id="progressBar" class="bg-purple-600 h-full rounded-full transition-all duration-300 w-0"></div>
                </div>
                
                {{-- Counters --}}
                <div class="grid grid-cols-3 gap-2 bg-zinc-50 border border-zinc-200/60 rounded p-3 text-center mt-3">
                    <div class="border-r border-zinc-150">
                        <span class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Target</span>
                        <span id="progressTotalCount" class="text-sm font-bold text-zinc-800 mt-0.5 block">0</span>
                    </div>
                    <div class="border-r border-zinc-150">
                        <span class="block text-[8px] font-mono font-bold text-emerald-500 uppercase tracking-wide">Sukses</span>
                        <span id="progressSuccessCount" class="text-sm font-bold text-emerald-600 mt-0.5 block">0</span>
                    </div>
                    <div>
                        <span class="block text-[8px] font-mono font-bold text-red-500 uppercase tracking-wide">Gagal</span>
                        <span id="progressFailedCount" class="text-sm font-bold text-red-600 mt-0.5 block">0</span>
                    </div>
                </div>
            </div>

            <!-- Current Recipient Card -->
            <div id="currentSendingContainer" class="p-3 bg-zinc-50 border border-zinc-200/60 rounded flex items-center gap-2.5 text-left">
                <div class="w-7 h-7 rounded bg-zinc-200/50 text-zinc-500 flex items-center justify-center shrink-0">
                    <i class="ph ph-envelope text-sm"></i>
                </div>
                <div class="overflow-hidden min-w-0">
                    <span class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Mengirim ke</span>
                    <span id="currentSendingEmail" class="block text-[11px] font-semibold text-zinc-800 truncate">Menunggu antrean...</span>
                </div>
            </div>

            <!-- Live Logs Console -->
            <div class="border border-zinc-200/60 rounded overflow-hidden bg-zinc-950 text-zinc-100 text-left">
                <div class="px-3 py-1.5 bg-zinc-900 border-b border-zinc-850 flex justify-between items-center text-[9px]">
                    <span class="font-mono font-bold uppercase tracking-wide text-zinc-400 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span> Live Logs
                    </span>
                    <span class="text-[8px] font-mono text-zinc-500 bg-zinc-950 px-1 py-0.5 rounded border border-zinc-800">Console</span>
                </div>
                <div id="progressLog" class="p-3 h-32 overflow-y-auto custom-scrollbar font-mono text-[9px] space-y-1 scroll-smooth">
                    <div class="text-zinc-500">[SYSTEM] Sistem siap melakukan blasting...</div>
                </div>
            </div>
        </div>
        
        <!-- Finish Actions -->
        <div id="progressFinishActions" class="hidden bg-zinc-50/50 px-4 py-3 border-t border-zinc-150/60 shrink-0">
            <button type="button" onclick="window.location.reload()" 
                    class="w-full h-8 bg-zinc-900 text-white rounded text-xs font-semibold hover:bg-zinc-800 transition-colors focus:outline-none">
                Selesai & Muat Ulang Halaman
            </button>
        </div>
    </div>
</div>

<script>
    // Radio selection styling logic (Notion theme version)
    function setupRadioStyles(groupName, activeBorderClass, activeBgClass, indicatorClass) {
        const radios = document.querySelectorAll(`input[name="${groupName}"]`);
        
        function update() {
            radios.forEach(r => {
                const label = r.closest('label');
                const indicator = label.querySelector(indicatorClass);
                
                if (r.checked) {
                    label.classList.add(activeBorderClass, activeBgClass);
                    label.classList.remove('border-zinc-200');
                    if(indicator) indicator.classList.remove('hidden');
                } else {
                    label.classList.remove(activeBorderClass, activeBgClass);
                    label.classList.add('border-zinc-200');
                    if(indicator) indicator.classList.add('hidden');
                }
            });
        }
        
        radios.forEach(r => r.addEventListener('change', update));
        update();
    }

    function toggleCustomEmailFields() {
        const customEmailRadio = document.querySelector('input[name="email_type"][value="custom"]');
        const customEmailFields = document.getElementById('customEmailFields');
        
        if (customEmailRadio && customEmailRadio.checked) {
            customEmailFields.classList.remove('hidden');
            setTimeout(() => {
                customEmailFields.classList.remove('opacity-0', '-translate-y-4');
            }, 10);
            
            document.getElementById('custom_subject').setAttribute('required', 'required');
            document.getElementById('custom_content').setAttribute('required', 'required');
        } else {
            customEmailFields.classList.add('opacity-0', '-translate-y-4');
            setTimeout(() => {
                customEmailFields.classList.add('hidden');
            }, 300);
            
            document.getElementById('custom_subject').removeAttribute('required');
            document.getElementById('custom_content').removeAttribute('required');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Init Custom fields UI state
        const customEmailFields = document.getElementById('customEmailFields');
        if(customEmailFields) customEmailFields.classList.add('opacity-0', '-translate-y-4');
        
        // Setup styled radios
        setupRadioStyles('email_type', 'border-zinc-900', 'bg-zinc-50/50', '.indicator');
        setupRadioStyles('target_user', 'border-zinc-900', 'bg-zinc-50/50', '.target-indicator');
        
        // Listeners
        document.querySelectorAll('input[name="email_type"]').forEach(r => {
            r.addEventListener('change', toggleCustomEmailFields);
        });
        
        toggleCustomEmailFields();
    });

    // Modals
    function showErrorModal(message) {
        document.getElementById('errorMessage').textContent = message;
        document.getElementById('errorModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function hideErrorModal() {
        document.getElementById('errorModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function showPreviewModal() {
        const emailType = document.querySelector('input[name="email_type"]:checked')?.value;
        const targetUser = document.querySelector('input[name="target_user"]:checked')?.value;
        
        if (!emailType || !targetUser) {
            let message = 'Harap pilih ';
            if (!emailType && !targetUser) message += 'tipe email dan target user terlebih dahulu.';
            else if (!emailType) message += 'tipe kampanye terlebih dahulu.';
            else message += 'target audience terlebih dahulu.';
            showErrorModal(message);
            return;
        }

        if (emailType === 'custom') {
            const customSubject = document.getElementById('custom_subject').value.trim();
            const customContent = document.getElementById('custom_content').value.trim();
            
            if (!customSubject || !customContent) {
                showErrorModal('Harap isi Subject dan Isi Konten Email untuk tipe Custom.');
                return;
            }

            const buttonText = document.getElementById('custom_button_text').value.trim();
            const buttonUrl = document.getElementById('custom_button_url').value.trim();
            
            if ((buttonText && !buttonUrl) || (!buttonText && buttonUrl)) {
                showErrorModal('Jika ingin menyertakan tombol aksi, harap isi Teks Tombol dan URL Tujuan.');
                return;
            }
        }
        
        const emailTypeText = {
            'welcome': 'Welcome Email',
            'verification': 'Verification Email',
            'verification_reminder': 'Verif. Reminder',
            'ai_analyzer': 'AI Analyzer Trial',
            'job_reminder': 'Job Reminder',
            'monthly_motivation': 'Monthly Motivation',
            'product_update': 'Major Product Update',
            'new_vibe': 'Suasana Baru TraKerja',
            'hiring_season': 'Hiring Season Alert',
            'chrome_extension': 'Chrome Extension Promo',
            'ai_photo': 'AI Photo Announcement',
            'follow_up_feature': 'Follow Up Feature',
            're_engagement': 'Re-engagement',
            'idul_adha': 'Idul Adha',
            'waisak': 'Hari Raya Waisak',
            'pancasila': 'Hari Lahir Pancasila',
            'tahun_baru_islam': 'Tahun Baru Islam',
            'kemerdekaan_ri': 'Hari Kemerdekaan RI',
            'maulid_nabi': 'Maulid Nabi Muhammad SAW',
            'sumpah_pemuda': 'Hari Sumpah Pemuda',
            'pahlawan': 'Hari Pahlawan',
            'guru_nasional': 'Hari Guru Nasional',
            'hari_ibu': 'Hari Ibu',
            'natal': 'Hari Raya Natal',
            'custom': 'Custom Email'
        }[emailType] || 'Email Blast';
        
        const targetText = {
            'all': 'Semua User',
            'new': 'User Baru',
            'unverified': 'Belum Verifikasi',
            'verified': 'Terverifikasi',
            'premium': 'Premium Users',
            'free': 'Free Users'
        }[targetUser];

        document.getElementById('confirmEmailType').textContent = emailTypeText;
        document.getElementById('confirmTargetUser').textContent = targetText;
        
        // Show Preview Modal
        document.getElementById('previewModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Reset and show loader
        const iframe = document.getElementById('previewFrame');
        const loader = document.getElementById('previewLoader');
        iframe.srcdoc = '';
        loader.classList.remove('hidden');
        
        // Fetch Preview
        const form = document.getElementById('emailBlastForm');
        const formData = new FormData(form);
        
        fetch("{{ route('admin.email-blast.preview') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 422) {
                    throw new Error('Validasi form gagal.');
                }
                throw new Error('Gagal memuat preview dari server.');
            }
            return response.text(); // Return HTML text
        })
        .then(html => {
            iframe.srcdoc = html;
            loader.classList.add('hidden');
        })
        .catch(err => {
            hidePreviewModal();
            showErrorModal(err.message || 'Terjadi kesalahan saat memuat preview.');
        });
    }

    function hidePreviewModal() {
        document.getElementById('previewModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function proceedToConfirm() {
        hidePreviewModal();
        document.getElementById('confirmModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function hideConfirmModal() {
        document.getElementById('confirmModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    let blastingUsers = [];
    let blastingCurrentIndex = 0;
    let blastingSuccessCount = 0;
    let blastingFailedCount = 0;
    let blastingFailedDetails = [];
    let blastingEmailType = '';
    let blastingCustomData = {};

    function submitEmailBlast() {
        // Hide confirmation modal
        hideConfirmModal();
        
        // Show progress modal
        const progressModal = document.getElementById('progressModal');
        progressModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Reset progress stats
        blastingCurrentIndex = 0;
        blastingSuccessCount = 0;
        blastingFailedCount = 0;
        blastingFailedDetails = [];
        document.getElementById('progressBar').style.width = '0%';
        document.getElementById('progressPercent').textContent = '0%';
        document.getElementById('progressSuccessCount').textContent = '0';
        document.getElementById('progressFailedCount').textContent = '0';
        document.getElementById('progressTotalCount').textContent = '0';
        document.getElementById('progressStatusText').textContent = 'Mempersiapkan Audience...';
        document.getElementById('currentSendingEmail').textContent = 'Menghubungkan ke server...';
        
        const logContainer = document.getElementById('progressLog');
        logContainer.innerHTML = '<div class="text-zinc-500">[SYSTEM] Memulai proses blasting email...</div>';
        
        // Gather form data
        const form = document.getElementById('emailBlastForm');
        const formData = new FormData(form);
        
        // Call init endpoint
        fetch("{{ route('admin.email-blast.init') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                blastingUsers = data.users;
                document.getElementById('progressTotalCount').textContent = data.total;
                appendLog(`[SYSTEM] Ditemukan ${data.total} user penerima.`, 'emerald');
                
                // Save custom parameters if any
                blastingEmailType = formData.get('email_type');
                blastingCustomData = {
                    email_type: blastingEmailType,
                    custom_subject: formData.get('custom_subject'),
                    custom_content: formData.get('custom_content'),
                    custom_button_text: formData.get('custom_button_text'),
                    custom_button_url: formData.get('custom_button_url')
                };
                
                // Start sending the first email
                sendNextEmail();
            } else {
                throw new Error(data.message || 'Gagal menginisialisasi audience.');
            }
        })
        .catch(err => {
            appendLog(`[ERROR] Inisialisasi Gagal: ${err.message || 'Koneksi terputus.'}`, 'rose');
            document.getElementById('progressStatusText').textContent = 'BLASTING GAGAL';
            document.getElementById('currentSendingEmail').textContent = 'Gagal memproses audience.';
            document.getElementById('progressFinishActions').classList.remove('hidden');
        });
    }

    function appendLog(text, colorClass = 'slate') {
        const logContainer = document.getElementById('progressLog');
        const logDiv = document.createElement('div');
        
        const colors = {
            'slate': 'text-zinc-400',
            'emerald': 'text-emerald-400 font-bold',
            'rose': 'text-red-400 font-bold',
            'indigo': 'text-zinc-500'
        };
        
        logDiv.className = colors[colorClass] || colors['slate'];
        
        const timestamp = new Date().toLocaleTimeString();
        logDiv.textContent = `[${timestamp}] ${text}`;
        
        logContainer.appendChild(logDiv);
        logContainer.scrollTop = logContainer.scrollHeight;
    }

    function sendNextEmail() {
        if (blastingCurrentIndex >= blastingUsers.length) {
            // Blasting is finished!
            document.getElementById('progressStatusText').textContent = 'BLASTING SELESAI';
            document.getElementById('currentSendingContainer').className = 'p-3 bg-emerald-50 border border-emerald-200 rounded flex items-center gap-2.5 text-left';
            document.getElementById('currentSendingContainer').querySelector('div').className = 'w-7 h-7 rounded bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0';
            document.getElementById('currentSendingContainer').querySelector('div').innerHTML = '<i class="ph-bold ph-check"></i>';
            document.getElementById('currentSendingEmail').innerHTML = '<span class="text-emerald-800 font-bold">Semua email kampanye berhasil dikirim!</span>';
            
            appendLog(`[SUCCESS] Kampanye berhasil diselesaikan.`, 'emerald');
            appendLog(`[STATS] Total: ${blastingUsers.length} | Sukses: ${blastingSuccessCount} | Gagal: ${blastingFailedCount}`, 'indigo');
            
            // Post log report to database via AJAX
            appendLog(`[SYSTEM] Menyimpan laporan riwayat blasting ke database...`, 'slate');
            
            const logPayload = {
                _token: document.querySelector('input[name="_token"]').value,
                email_type: blastingEmailType,
                target_audience: document.querySelector('input[name="target_user"]:checked')?.value || 'all',
                total_target: blastingUsers.length,
                success_count: blastingSuccessCount,
                failed_count: blastingFailedCount,
                failed_details: blastingFailedDetails
            };

            fetch("{{ route('admin.email-blast.store-log') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(logPayload)
            })
            .then(res => res.json())
            .then(resData => {
                if (resData.success) {
                    appendLog(`[SYSTEM] Riwayat blasting berhasil disimpan! Log ID: #${resData.log_id}`, 'emerald');
                } else {
                    appendLog(`[SYSTEM] Gagal menyimpan riwayat blasting ke database.`, 'rose');
                }
            })
            .catch(err => {
                appendLog(`[SYSTEM] Gagal menghubungi server untuk menyimpan riwayat.`, 'rose');
            })
            .finally(() => {
                document.getElementById('progressFinishActions').classList.remove('hidden');
            });
            
            return;
        }
        
        const user = blastingUsers[blastingCurrentIndex];
        document.getElementById('progressStatusText').textContent = `MENGIRIM (${blastingCurrentIndex + 1}/${blastingUsers.length})`;
        document.getElementById('currentSendingEmail').textContent = `${user.name} (${user.email})`;
        
        appendLog(`Mengirim email ke: ${user.name} <${user.email}>...`, 'slate');
        
        // Prepare request body
        const payload = {
            _token: document.querySelector('input[name="_token"]').value,
            user_id: user.id,
            ...blastingCustomData
        };
        
        // Make AJAX request
        fetch("{{ route('admin.email-blast.send-single') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                blastingSuccessCount++;
                document.getElementById('progressSuccessCount').textContent = blastingSuccessCount;
                appendLog(`[BERHASIL] Terkirim ke ${data.email}`, 'emerald');
            } else {
                blastingFailedCount++;
                document.getElementById('progressFailedCount').textContent = blastingFailedCount;
                appendLog(`[GAGAL] Pengiriman ke ${data.email} error: ${data.error || 'Server error'}`, 'rose');
                blastingFailedDetails.push({
                    email: data.email,
                    name: data.name || '',
                    error: data.error || 'Server error'
                });
            }
        })
        .catch(err => {
            blastingFailedCount++;
            document.getElementById('progressFailedCount').textContent = blastingFailedCount;
            appendLog(`[ERROR] Koneksi terputus saat mengirim ke ${user.email}`, 'rose');
            blastingFailedDetails.push({
                email: user.email,
                name: user.name || '',
                error: err.message || 'Koneksi terputus'
            });
        })
        .finally(() => {
            blastingCurrentIndex++;
            const percent = Math.round((blastingCurrentIndex / blastingUsers.length) * 100);
            document.getElementById('progressBar').style.width = `${percent}%`;
            document.getElementById('progressPercent').textContent = `${percent}%`;
            
            sendNextEmail();
        });
    }

    function toggleHariPeringatan() {
        const container = document.getElementById('hariPeringatanContainer');
        const icon = document.getElementById('hariPeringatanIcon');
        
        if (container.classList.contains('hidden')) {
            container.classList.remove('hidden');
            icon.classList.add('rotate-180');
        } else {
            container.classList.add('hidden');
            icon.classList.remove('rotate-180');
        }
    }
</script>
</x-admin-layout>
