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

<div class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-6 space-y-6 sm:space-y-8 pb-10">
    <!-- Header -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-6 sm:mb-8">
        <div class="flex items-center gap-3 sm:gap-4 min-w-0 w-full md:w-auto">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white border border-slate-200/60 rounded-[1.25rem] sm:rounded-[1.5rem] flex items-center justify-center text-primary-600 shadow-sm shrink-0">
                <i class="ph-duotone ph-paper-plane-tilt text-xl sm:text-2xl"></i>
            </div>
            <div class="flex flex-col min-w-0">
                <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight truncate">Email Blast</h3>
                <p class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mt-1 sm:mt-1.5 truncate">Marketing & Communication</p>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.email-blast.history') }}" class="flex items-center justify-center gap-2 px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-black uppercase tracking-widest transition-colors w-full sm:w-auto shadow-md">
                <i class="ph-bold ph-clock-counter-clockwise text-sm"></i>
                Riwayat Blasting
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl shadow-sm flex items-start gap-3">
            <i class="ph-fill ph-check-circle text-2xl text-emerald-500 mt-0.5"></i>
            <div>
                <h3 class="text-sm font-bold">{{ session('success') }}</h3>
                @if(session('details'))
                    <div class="mt-2 text-xs space-y-1 text-emerald-700 bg-emerald-100/50 p-3 rounded-xl">
                        <p class="font-medium">Total Audience: {{ session('details')['total'] }} users</p>
                        <p class="font-medium">Berhasil Terkirim: {{ session('details')['success'] }} users</p>
                        @if(isset(session('details')['failed']) && session('details')['failed'] > 0)
                            <p class="font-bold text-red-600 mt-1">Gagal Terkirim: {{ session('details')['failed'] }} users</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-2xl shadow-sm flex items-start gap-3">
            <i class="ph-fill ph-warning-circle text-2xl text-red-500 mt-0.5"></i>
            <div>
                <h3 class="text-sm font-bold">{{ session('error') }}</h3>
            </div>
        </div>
    @endif

    <!-- Main Layout -->
    <form action="{{ route('admin.email-blast.send') }}" method="POST" id="emailBlastForm">
        @csrf
        <div class="space-y-6">
            
            <!-- Top Section: Email Configuration -->
            <div class="space-y-6">
                
                <!-- Email Type Selection -->
                <div class="bento-card bg-white rounded-[2rem] border border-slate-200/60 overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h2 class="text-sm font-black text-slate-900 tracking-tight uppercase flex items-center gap-2">
                            <i class="ph-duotone ph-envelope-open text-primary-500 text-xl"></i>
                            Pilih Tipe Kampanye
                        </h2>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-1">Template Email yang Akan Dikirim</p>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Welcome -->
                            <label class="group relative flex cursor-pointer rounded-2xl border-2 border-slate-100 p-5 focus:outline-none hover:border-primary-200 hover:bg-slate-50 transition-all">
                                <input type="radio" name="email_type" value="welcome" class="sr-only" required>
                                <div class="flex items-center gap-4 w-full">
                                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center  transition-transform">
                                        <i class="ph-duotone ph-hand-waving text-2xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-bold text-slate-900">Welcome Email</h3>
                                        <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Sambut pengguna baru ke platform TraKerja.</p>
                                    </div>
                                    <div class="indicator hidden w-5 h-5 rounded-full bg-primary-500 text-white flex items-center justify-center flex-shrink-0">
                                        <i class="ph-bold ph-check text-xs"></i>
                                    </div>
                                </div>
                            </label>

                            <!-- Verification -->
                            <label class="group relative flex cursor-pointer rounded-2xl border-2 border-slate-100 p-5 focus:outline-none hover:border-primary-200 hover:bg-slate-50 transition-all">
                                <input type="radio" name="email_type" value="verification" class="sr-only" required>
                                <div class="flex items-center gap-4 w-full">
                                    <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-500 flex items-center justify-center  transition-transform">
                                        <i class="ph-duotone ph-shield-check text-2xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-bold text-slate-900">Verification Email</h3>
                                        <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Kirim ulang link verifikasi akun.</p>
                                    </div>
                                    <div class="indicator hidden w-5 h-5 rounded-full bg-primary-500 text-white flex items-center justify-center flex-shrink-0">
                                        <i class="ph-bold ph-check text-xs"></i>
                                    </div>
                                </div>
                            </label>

                            <!-- Verification Reminder -->
                            <label class="group relative flex cursor-pointer rounded-2xl border-2 border-slate-100 p-5 focus:outline-none hover:border-primary-200 hover:bg-slate-50 transition-all">
                                <input type="radio" name="email_type" value="verification_reminder" class="sr-only" required>
                                <div class="flex items-center gap-4 w-full">
                                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center  transition-transform">
                                        <i class="ph-duotone ph-warning-circle text-2xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-bold text-slate-900">Verif. Reminder</h3>
                                        <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Pengingat belum verifikasi (Mendesak).</p>
                                    </div>
                                    <div class="indicator hidden w-5 h-5 rounded-full bg-primary-500 text-white flex items-center justify-center flex-shrink-0">
                                        <i class="ph-bold ph-check text-xs"></i>
                                    </div>
                                </div>
                            </label>

                            <!-- AI Analyzer -->
                            <label class="group relative flex cursor-pointer rounded-2xl border-2 border-slate-100 p-5 focus:outline-none hover:border-primary-200 hover:bg-slate-50 transition-all">
                                <input type="radio" name="email_type" value="ai_analyzer" class="sr-only" required>
                                <div class="flex items-center gap-4 w-full">
                                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-500 flex items-center justify-center  transition-transform">
                                        <i class="ph-duotone ph-sparkle text-2xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-bold text-slate-900">AI Analyzer Trial</h3>
                                        <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Promosikan fitur AI Review Resume.</p>
                                    </div>
                                    <div class="indicator hidden w-5 h-5 rounded-full bg-primary-500 text-white flex items-center justify-center flex-shrink-0">
                                        <i class="ph-bold ph-check text-xs"></i>
                                    </div>
                                </div>
                            </label>

                            <!-- Job Reminder -->
                            <label class="group relative flex cursor-pointer rounded-2xl border-2 border-slate-100 p-5 focus:outline-none hover:border-primary-200 hover:bg-slate-50 transition-all">
                                <input type="radio" name="email_type" value="job_reminder" class="sr-only" required>
                                <div class="flex items-center gap-4 w-full">
                                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center  transition-transform">
                                        <i class="ph-duotone ph-bell-ringing text-2xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-bold text-slate-900">Job Reminder</h3>
                                        <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Pengingat mencatat riwayat lamaran.</p>
                                    </div>
                                    <div class="indicator hidden w-5 h-5 rounded-full bg-primary-500 text-white flex items-center justify-center flex-shrink-0">
                                        <i class="ph-bold ph-check text-xs"></i>
                                    </div>
                                </div>
                            </label>

                            <!-- Monthly Motivation -->
                            <label class="group relative flex cursor-pointer rounded-2xl border-2 border-slate-100 p-5 focus:outline-none hover:border-primary-200 hover:bg-slate-50 transition-all">
                                <input type="radio" name="email_type" value="monthly_motivation" class="sr-only" required>
                                <div class="flex items-center gap-4 w-full">
                                    <div class="w-10 h-10 rounded-xl bg-pink-50 text-pink-500 flex items-center justify-center  transition-transform">
                                        <i class="ph-duotone ph-fire text-2xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-bold text-slate-900">Monthly Motivation</h3>
                                        <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Pesan motivasi awal bulan.</p>
                                    </div>
                                    <div class="indicator hidden w-5 h-5 rounded-full bg-primary-500 text-white flex items-center justify-center flex-shrink-0">
                                        <i class="ph-bold ph-check text-xs"></i>
                                    </div>
                                </div>
                            </label>

                            <!-- Product Update -->
                            <label class="group relative flex cursor-pointer rounded-2xl border-2 border-slate-100 p-5 focus:outline-none hover:border-primary-200 hover:bg-slate-50 transition-all">
                                <input type="radio" name="email_type" value="product_update" class="sr-only" required>
                                <div class="flex items-center gap-4 w-full">
                                    <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center  transition-transform">
                                        <i class="ph-duotone ph-rocket-launch text-2xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-bold text-slate-900">Major Product Update</h3>
                                        <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Pengumuman resmi fitur dan pembaruan sistem terbaru.</p>
                                    </div>
                                    <div class="indicator hidden w-5 h-5 rounded-full bg-primary-500 text-white flex items-center justify-center flex-shrink-0">
                                        <i class="ph-bold ph-check text-xs"></i>
                                    </div>
                                </div>
                            </label>

                            <!-- Hiring Season Alert -->
                            <label class="group relative flex cursor-pointer rounded-2xl border-2 border-slate-100 p-5 focus:outline-none hover:border-primary-200 hover:bg-slate-50 transition-all">
                                <input type="radio" name="email_type" value="hiring_season" class="sr-only" required>
                                <div class="flex items-center gap-4 w-full">
                                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center  transition-transform">
                                        <i class="ph-duotone ph-briefcase text-2xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-bold text-slate-900">Hiring Season Alert</h3>
                                        <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Informasi musim rekrutmen aktif dan tips strategis mencari kerja.</p>
                                    </div>
                                    <div class="indicator hidden w-5 h-5 rounded-full bg-primary-500 text-white flex items-center justify-center flex-shrink-0">
                                        <i class="ph-bold ph-check text-xs"></i>
                                    </div>
                                </div>
                            </label>

                            <!-- Follow Up Feature Announcement -->
                            <label class="group relative flex cursor-pointer rounded-2xl border-2 border-slate-100 p-5 focus:outline-none hover:border-primary-200 hover:bg-slate-50 transition-all">
                                <input type="radio" name="email_type" value="follow_up_feature" class="sr-only" required>
                                <div class="flex items-center gap-4 w-full">
                                    <div class="w-10 h-10 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center  transition-transform">
                                        <i class="ph-duotone ph-paper-plane-tilt text-2xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-bold text-slate-900">Follow Up Feature</h3>
                                        <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Pengumuman fitur baru AI Follow Up Email (Tanya Kabar).</p>
                                    </div>
                                    <div class="indicator hidden w-5 h-5 rounded-full bg-primary-500 text-white flex items-center justify-center flex-shrink-0">
                                        <i class="ph-bold ph-check text-xs"></i>
                                    </div>
                                </div>
                            </label>

                            <!-- Chrome Extension Promo -->
                            <label class="group relative flex cursor-pointer rounded-2xl border-2 border-slate-100 p-5 focus:outline-none hover:border-primary-200 hover:bg-slate-50 transition-all">
                                <input type="radio" name="email_type" value="chrome_extension" class="sr-only" required>
                                <div class="flex items-center gap-4 w-full">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center  transition-transform">
                                        <i class="ph-duotone ph-puzzle-piece text-2xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-bold text-slate-900">Chrome Extension Announcement</h3>
                                        <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Promosikan fitur ekstensi TraKerja untuk LinkedIn, JobStreet, dsb.</p>
                                    </div>
                                    <div class="indicator hidden w-5 h-5 rounded-full bg-primary-500 text-white flex items-center justify-center flex-shrink-0">
                                        <i class="ph-bold ph-check text-xs"></i>
                                    </div>
                                </div>
                            </label>

                            <!-- AI Photo Announcement -->
                            <label class="group relative flex cursor-pointer rounded-2xl border-2 border-slate-100 p-5 focus:outline-none hover:border-primary-200 hover:bg-slate-50 transition-all">
                                <input type="radio" name="email_type" value="ai_photo" class="sr-only" required>
                                <div class="flex items-center gap-4 w-full">
                                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center  transition-transform">
                                        <i class="ph-duotone ph-camera-plus text-2xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-bold text-slate-900">AI Photo Announcement</h3>
                                        <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Promosikan fitur AI Photo Studio untuk pas foto profesional.</p>
                                    </div>
                                    <div class="indicator hidden w-5 h-5 rounded-full bg-primary-500 text-white flex items-center justify-center flex-shrink-0">
                                        <i class="ph-bold ph-check text-xs"></i>
                                    </div>
                                </div>
                            </label>

                            <!-- Re-engagement -->
                            <label class="group relative flex cursor-pointer rounded-2xl border-2 border-slate-100 p-5 focus:outline-none hover:border-primary-200 hover:bg-slate-50 transition-all">
                                <input type="radio" name="email_type" value="re_engagement" class="sr-only" required>
                                <div class="flex items-center gap-4 w-full">
                                    <div class="w-10 h-10 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center  transition-transform">
                                        <i class="ph-duotone ph-hand-heart text-2xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-bold text-slate-900">Re-engagement</h3>
                                        <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Ajak kembali pengguna yang sudah lama tidak aktif di platform.</p>
                                    </div>
                                    <div class="indicator hidden w-5 h-5 rounded-full bg-primary-500 text-white flex items-center justify-center flex-shrink-0">
                                        <i class="ph-bold ph-check text-xs"></i>
                                    </div>
                                </div>
                            </label>

                            <!-- Hari Peringatan Nasional/Internasional -->
                            <div class="col-span-1 md:col-span-2 pt-2 border-t border-slate-100 mt-2">
                                <button type="button" onclick="toggleHariPeringatan()" class="w-full flex items-center justify-between p-4 bg-slate-50 border border-slate-200 rounded-xl hover:bg-slate-100 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center">
                                            <i class="ph-duotone ph-calendar-star text-lg"></i>
                                        </div>
                                        <div class="text-left flex-1">
                                            <div class="flex items-center gap-2">
                                                <h3 class="text-sm font-bold text-slate-900">Hari Peringatan Nasional/Internasional</h3>
                                                <span class="text-[10px] font-bold px-2 py-0.5 bg-amber-100 text-amber-700 rounded-md">Efektif: Mei-Desember 2026</span>
                                            </div>
                                            <p class="text-xs text-slate-500 mt-0.5">Template email untuk perayaan dan hari besar.</p>
                                        </div>
                                    </div>
                                    <i id="hariPeringatanIcon" class="ph-bold ph-caret-down text-slate-400 transition-transform"></i>
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
                                            $event['badge_class'] = 'bg-blue-100 text-blue-700 border border-blue-200';
                                        } elseif ($now->gt($event['date'])) {
                                            $event['status'] = 'Lewat';
                                            $event['badge_class'] = 'bg-slate-100 text-slate-500 border border-slate-200';
                                        } else {
                                            $event['status'] = 'Akan Datang';
                                            $event['badge_class'] = 'bg-emerald-100 text-emerald-700 border border-emerald-200';
                                        }
                                    }
                                @endphp
                                
                                <div id="hariPeringatanContainer" class="hidden mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Idul Adha -->
                                    <label class="group relative flex cursor-pointer rounded-2xl border-2 border-slate-100 p-5 focus:outline-none hover:border-primary-200 hover:bg-slate-50 transition-all">
                                        <input type="radio" name="email_type" value="idul_adha" class="sr-only" required>
                                        <div class="flex items-center gap-4 w-full">
                                            <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center  transition-transform">
                                                <i class="ph-duotone ph-mosque text-2xl"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2">
                                                    <h3 class="text-sm font-bold text-slate-900">Idul Adha</h3>
                                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md {{ $events['idul_adha']['badge_class'] }}">
                                                        {{ $events['idul_adha']['label'] }} &bull; {{ $events['idul_adha']['status'] }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Ucapan Selamat Hari Raya Idul Adha.</p>
                                            </div>
                                            <div class="indicator hidden w-5 h-5 rounded-full bg-primary-500 text-white flex items-center justify-center flex-shrink-0">
                                                <i class="ph-bold ph-check text-xs"></i>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Waisak -->
                                    <label class="group relative flex cursor-pointer rounded-2xl border-2 border-slate-100 p-5 focus:outline-none hover:border-primary-200 hover:bg-slate-50 transition-all">
                                        <input type="radio" name="email_type" value="waisak" class="sr-only" required>
                                        <div class="flex items-center gap-4 w-full">
                                            <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center  transition-transform">
                                                <i class="ph-duotone ph-flower-lotus text-2xl"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2">
                                                    <h3 class="text-sm font-bold text-slate-900">Hari Raya Waisak</h3>
                                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md {{ $events['waisak']['badge_class'] }}">
                                                        {{ $events['waisak']['label'] }} &bull; {{ $events['waisak']['status'] }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Ucapan Hari Raya Tri Suci Waisak 2570 BE.</p>
                                            </div>
                                            <div class="indicator hidden w-5 h-5 rounded-full bg-primary-500 text-white flex items-center justify-center flex-shrink-0">
                                                <i class="ph-bold ph-check text-xs"></i>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Pancasila -->
                                    <label class="group relative flex cursor-pointer rounded-2xl border-2 border-slate-100 p-5 focus:outline-none hover:border-primary-200 hover:bg-slate-50 transition-all">
                                        <input type="radio" name="email_type" value="pancasila" class="sr-only" required>
                                        <div class="flex items-center gap-4 w-full">
                                            <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center  transition-transform">
                                                <i class="ph-duotone ph-flag-banner text-2xl"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2">
                                                    <h3 class="text-sm font-bold text-slate-900">Hari Lahir Pancasila</h3>
                                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md {{ $events['pancasila']['badge_class'] }}">
                                                        {{ $events['pancasila']['label'] }} &bull; {{ $events['pancasila']['status'] }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Ucapan peringatan Hari Lahir Pancasila.</p>
                                            </div>
                                            <div class="indicator hidden w-5 h-5 rounded-full bg-primary-500 text-white flex items-center justify-center flex-shrink-0">
                                                <i class="ph-bold ph-check text-xs"></i>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Tahun Baru Islam -->
                                    <label class="group relative flex cursor-pointer rounded-2xl border-2 border-slate-100 p-5 focus:outline-none hover:border-primary-200 hover:bg-slate-50 transition-all">
                                        <input type="radio" name="email_type" value="tahun_baru_islam" class="sr-only" required>
                                        <div class="flex items-center gap-4 w-full">
                                            <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center transition-transform">
                                                <i class="ph-duotone ph-moon-stars text-2xl"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2">
                                                    <h3 class="text-sm font-bold text-slate-900">Tahun Baru Islam</h3>
                                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md {{ $events['tahun_baru_islam']['badge_class'] }}">
                                                        {{ $events['tahun_baru_islam']['label'] }} &bull; {{ $events['tahun_baru_islam']['status'] }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Ucapan peringatan 1 Muharram.</p>
                                            </div>
                                            <div class="indicator hidden w-5 h-5 rounded-full bg-primary-500 text-white flex items-center justify-center flex-shrink-0">
                                                <i class="ph-bold ph-check text-xs"></i>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Kemerdekaan RI -->
                                    <label class="group relative flex cursor-pointer rounded-2xl border-2 border-slate-100 p-5 focus:outline-none hover:border-primary-200 hover:bg-slate-50 transition-all">
                                        <input type="radio" name="email_type" value="kemerdekaan_ri" class="sr-only" required>
                                        <div class="flex items-center gap-4 w-full">
                                            <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center transition-transform">
                                                <i class="ph-duotone ph-flag text-2xl"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2">
                                                    <h3 class="text-sm font-bold text-slate-900">HUT RI</h3>
                                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md {{ $events['kemerdekaan_ri']['badge_class'] }}">
                                                        {{ $events['kemerdekaan_ri']['label'] }} &bull; {{ $events['kemerdekaan_ri']['status'] }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Ucapan peringatan Kemerdekaan RI.</p>
                                            </div>
                                            <div class="indicator hidden w-5 h-5 rounded-full bg-primary-500 text-white flex items-center justify-center flex-shrink-0">
                                                <i class="ph-bold ph-check text-xs"></i>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Maulid Nabi -->
                                    <label class="group relative flex cursor-pointer rounded-2xl border-2 border-slate-100 p-5 focus:outline-none hover:border-primary-200 hover:bg-slate-50 transition-all">
                                        <input type="radio" name="email_type" value="maulid_nabi" class="sr-only" required>
                                        <div class="flex items-center gap-4 w-full">
                                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center transition-transform">
                                                <i class="ph-duotone ph-mosque text-2xl"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2">
                                                    <h3 class="text-sm font-bold text-slate-900">Maulid Nabi</h3>
                                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md {{ $events['maulid_nabi']['badge_class'] }}">
                                                        {{ $events['maulid_nabi']['label'] }} &bull; {{ $events['maulid_nabi']['status'] }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Ucapan peringatan Maulid Nabi.</p>
                                            </div>
                                            <div class="indicator hidden w-5 h-5 rounded-full bg-primary-500 text-white flex items-center justify-center flex-shrink-0">
                                                <i class="ph-bold ph-check text-xs"></i>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Sumpah Pemuda -->
                                    <label class="group relative flex cursor-pointer rounded-2xl border-2 border-slate-100 p-5 focus:outline-none hover:border-primary-200 hover:bg-slate-50 transition-all">
                                        <input type="radio" name="email_type" value="sumpah_pemuda" class="sr-only" required>
                                        <div class="flex items-center gap-4 w-full">
                                            <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center transition-transform">
                                                <i class="ph-duotone ph-users-three text-2xl"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2">
                                                    <h3 class="text-sm font-bold text-slate-900">Hari Sumpah Pemuda</h3>
                                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md {{ $events['sumpah_pemuda']['badge_class'] }}">
                                                        {{ $events['sumpah_pemuda']['label'] }} &bull; {{ $events['sumpah_pemuda']['status'] }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Ucapan peringatan Sumpah Pemuda.</p>
                                            </div>
                                            <div class="indicator hidden w-5 h-5 rounded-full bg-primary-500 text-white flex items-center justify-center flex-shrink-0">
                                                <i class="ph-bold ph-check text-xs"></i>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Hari Pahlawan -->
                                    <label class="group relative flex cursor-pointer rounded-2xl border-2 border-slate-100 p-5 focus:outline-none hover:border-primary-200 hover:bg-slate-50 transition-all">
                                        <input type="radio" name="email_type" value="pahlawan" class="sr-only" required>
                                        <div class="flex items-center gap-4 w-full">
                                            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center transition-transform">
                                                <i class="ph-duotone ph-medal text-2xl"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2">
                                                    <h3 class="text-sm font-bold text-slate-900">Hari Pahlawan</h3>
                                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md {{ $events['pahlawan']['badge_class'] }}">
                                                        {{ $events['pahlawan']['label'] }} &bull; {{ $events['pahlawan']['status'] }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Ucapan peringatan Hari Pahlawan.</p>
                                            </div>
                                            <div class="indicator hidden w-5 h-5 rounded-full bg-primary-500 text-white flex items-center justify-center flex-shrink-0">
                                                <i class="ph-bold ph-check text-xs"></i>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Hari Guru Nasional -->
                                    <label class="group relative flex cursor-pointer rounded-2xl border-2 border-slate-100 p-5 focus:outline-none hover:border-primary-200 hover:bg-slate-50 transition-all">
                                        <input type="radio" name="email_type" value="guru_nasional" class="sr-only" required>
                                        <div class="flex items-center gap-4 w-full">
                                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center transition-transform">
                                                <i class="ph-duotone ph-student text-2xl"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2">
                                                    <h3 class="text-sm font-bold text-slate-900">Hari Guru Nasional</h3>
                                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md {{ $events['guru_nasional']['badge_class'] }}">
                                                        {{ $events['guru_nasional']['label'] }} &bull; {{ $events['guru_nasional']['status'] }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Ucapan peringatan Hari Guru Nasional.</p>
                                            </div>
                                            <div class="indicator hidden w-5 h-5 rounded-full bg-primary-500 text-white flex items-center justify-center flex-shrink-0">
                                                <i class="ph-bold ph-check text-xs"></i>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Hari Ibu -->
                                    <label class="group relative flex cursor-pointer rounded-2xl border-2 border-slate-100 p-5 focus:outline-none hover:border-primary-200 hover:bg-slate-50 transition-all">
                                        <input type="radio" name="email_type" value="hari_ibu" class="sr-only" required>
                                        <div class="flex items-center gap-4 w-full">
                                            <div class="w-10 h-10 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center transition-transform">
                                                <i class="ph-duotone ph-heart text-2xl"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2">
                                                    <h3 class="text-sm font-bold text-slate-900">Hari Ibu</h3>
                                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md {{ $events['hari_ibu']['badge_class'] }}">
                                                        {{ $events['hari_ibu']['label'] }} &bull; {{ $events['hari_ibu']['status'] }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Ucapan peringatan Hari Ibu.</p>
                                            </div>
                                            <div class="indicator hidden w-5 h-5 rounded-full bg-primary-500 text-white flex items-center justify-center flex-shrink-0">
                                                <i class="ph-bold ph-check text-xs"></i>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Natal -->
                                    <label class="group relative flex cursor-pointer rounded-2xl border-2 border-slate-100 p-5 focus:outline-none hover:border-primary-200 hover:bg-slate-50 transition-all">
                                        <input type="radio" name="email_type" value="natal" class="sr-only" required>
                                        <div class="flex items-center gap-4 w-full">
                                            <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center transition-transform">
                                                <i class="ph-duotone ph-tree text-2xl"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2">
                                                    <h3 class="text-sm font-bold text-slate-900">Hari Raya Natal</h3>
                                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md {{ $events['natal']['badge_class'] }}">
                                                        {{ $events['natal']['label'] }} &bull; {{ $events['natal']['status'] }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Ucapan Hari Raya Natal.</p>
                                            </div>
                                            <div class="indicator hidden w-5 h-5 rounded-full bg-primary-500 text-white flex items-center justify-center flex-shrink-0">
                                                <i class="ph-bold ph-check text-xs"></i>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>


                            <!-- Custom Email -->
                            <label class="group relative flex cursor-pointer rounded-2xl border-2 border-slate-100 p-5 focus:outline-none hover:border-primary-200 hover:bg-slate-50 transition-all">
                                <input type="radio" name="email_type" value="custom" class="sr-only" required onchange="toggleCustomEmailFields()">
                                <div class="flex items-center gap-4 w-full">
                                    <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center shadow-lg  transition-transform">
                                        <i class="ph-duotone ph-pencil-simple text-2xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-bold text-slate-900">Custom Email</h3>
                                        <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Buat pesan spesifik Anda sendiri.</p>
                                    </div>
                                    <div class="indicator hidden w-5 h-5 rounded-full bg-primary-500 text-white flex items-center justify-center flex-shrink-0">
                                        <i class="ph-bold ph-check text-xs"></i>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Custom Email Fields Container -->
                <div id="customEmailFields" class="hidden transform transition-all duration-300">
                    <div class="bento-card bg-white rounded-[2rem] border border-slate-200/60 overflow-hidden relative">
                        <!-- Decorative glow -->
                        <div class="absolute -left-20 -top-20 w-40 h-40 bg-indigo-100 rounded-full blur-3xl opacity-50"></div>
                        
                        <div class="px-6 py-5 border-b border-slate-100 relative z-10 bg-white/50 backdrop-blur-sm">
                            <h2 class="text-sm font-black text-slate-900 tracking-tight uppercase flex items-center gap-2">
                                <i class="ph-duotone ph-pencil-line text-indigo-500 text-xl"></i>
                                Tulis Pesan Custom
                            </h2>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-1">Rancang Konten Email Anda</p>
                        </div>

                        <div class="p-6 space-y-6 relative z-10">
                            <div>
                                <label for="custom_subject" class="block text-sm font-bold text-slate-700 mb-2">
                                    Subject Email <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="custom_subject" 
                                    id="custom_subject"
                                    class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-medium text-sm text-slate-900 placeholder:text-slate-400"
                                    placeholder="Contoh: Info Update Fitur Terbaru"
                                    maxlength="255"
                                >
                            </div>

                            <div>
                                <label for="custom_content" class="block text-sm font-bold text-slate-700 mb-2">
                                    Isi Konten Email <span class="text-red-500">*</span>
                                </label>
                                <textarea 
                                    name="custom_content" 
                                    id="custom_content"
                                    rows="8"
                                    class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-medium text-sm text-slate-900 placeholder:text-slate-400 resize-none"
                                    placeholder="Tulis pesan Anda di sini..."
                                    maxlength="5000"
                                ></textarea>
                                <div class="flex justify-between items-center mt-2">
                                    <p class="text-[11px] font-bold text-slate-400">Gunakan Markdown atau HTML dasar jika diperlukan.</p>
                                    <p class="text-[11px] font-bold text-slate-400">Max 5000 chars</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label for="custom_button_text" class="block text-sm font-bold text-slate-700 mb-2">
                                        Teks Tombol (Opsional)
                                    </label>
                                    <input 
                                        type="text" 
                                        name="custom_button_text" 
                                        id="custom_button_text"
                                        class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-medium text-sm text-slate-900 placeholder:text-slate-400"
                                        placeholder="Misal: Coba Fitur Sekarang"
                                        maxlength="100"
                                    >
                                </div>
                                <div>
                                    <label for="custom_button_url" class="block text-sm font-bold text-slate-700 mb-2">
                                        URL Tujuan (Opsional)
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="ph-bold ph-link text-slate-400"></i>
                                        </div>
                                        <input 
                                            type="url" 
                                            name="custom_button_url" 
                                            id="custom_button_url"
                                            class="w-full pl-10 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-medium text-sm text-slate-900 placeholder:text-slate-400"
                                            placeholder="https://..."
                                            maxlength="500"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Section: Target Audience & Actions -->
            <div class="space-y-6">
                
                <div class="bento-card bg-white rounded-[2rem] border border-slate-200/60 overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h2 class="text-sm font-black text-slate-900 tracking-tight uppercase flex items-center gap-2">
                            <i class="ph-duotone ph-users-three text-emerald-500 text-xl"></i>
                            Pilih Target Audience
                        </h2>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-1">Grup Penerima Kampanye</p>
                    </div>

                    <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                        <!-- All Users -->
                        <label class="group relative flex items-center justify-between cursor-pointer rounded-xl border border-slate-100 p-4 focus:outline-none hover:border-emerald-200 hover:bg-emerald-50/30 transition-all">
                            <input type="radio" name="target_user" value="all" class="sr-only" checked required>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center group-hover:bg-emerald-100 group-hover:text-emerald-600 transition-colors">
                                    <i class="ph-duotone ph-globe text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900">Semua User</h3>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 text-xs font-bold">{{ number_format($stats['all']) }} users</span>
                                <div class="target-indicator hidden w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">
                                    <i class="ph-bold ph-check text-xs"></i>
                                </div>
                            </div>
                        </label>

                        <!-- Premium Users -->
                        <label class="group relative flex items-center justify-between cursor-pointer rounded-xl border border-slate-100 p-4 focus:outline-none hover:border-emerald-200 hover:bg-emerald-50/30 transition-all">
                            <input type="radio" name="target_user" value="premium" class="sr-only" required>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center group-hover:bg-amber-100 group-hover:text-amber-600 transition-colors">
                                    <i class="ph-duotone ph-crown text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900">Premium Users</h3>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 text-xs font-bold">{{ number_format($stats['premium']) }} users</span>
                                <div class="target-indicator hidden w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">
                                    <i class="ph-bold ph-check text-xs"></i>
                                </div>
                            </div>
                        </label>

                        <!-- Free Users -->
                        <label class="group relative flex items-center justify-between cursor-pointer rounded-xl border border-slate-100 p-4 focus:outline-none hover:border-emerald-200 hover:bg-emerald-50/30 transition-all">
                            <input type="radio" name="target_user" value="free" class="sr-only" required>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center group-hover:bg-slate-200 group-hover:text-slate-800 transition-colors">
                                    <i class="ph-duotone ph-user text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900">Free Users</h3>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 text-xs font-bold">{{ number_format($stats['free']) }} users</span>
                                <div class="target-indicator hidden w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">
                                    <i class="ph-bold ph-check text-xs"></i>
                                </div>
                            </div>
                        </label>

                        <!-- Verified Users -->
                        <label class="group relative flex items-center justify-between cursor-pointer rounded-xl border border-slate-100 p-4 focus:outline-none hover:border-emerald-200 hover:bg-emerald-50/30 transition-all">
                            <input type="radio" name="target_user" value="verified" class="sr-only" required>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center group-hover:bg-blue-100 group-hover:text-blue-600 transition-colors">
                                    <i class="ph-duotone ph-seal-check text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900">Terverifikasi</h3>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 text-xs font-bold">{{ number_format($stats['verified']) }} users</span>
                                <div class="target-indicator hidden w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">
                                    <i class="ph-bold ph-check text-xs"></i>
                                </div>
                            </div>
                        </label>

                        <!-- Unverified Users -->
                        <label class="group relative flex items-center justify-between cursor-pointer rounded-xl border border-slate-100 p-4 focus:outline-none hover:border-emerald-200 hover:bg-emerald-50/30 transition-all">
                            <input type="radio" name="target_user" value="unverified" class="sr-only" required>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center group-hover:bg-red-100 group-hover:text-red-600 transition-colors">
                                    <i class="ph-duotone ph-seal-warning text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900">Belum Verifikasi</h3>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 text-xs font-bold">{{ number_format($stats['unverified']) }} users</span>
                                <div class="target-indicator hidden w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">
                                    <i class="ph-bold ph-check text-xs"></i>
                                </div>
                            </div>
                        </label>

                        <!-- New Users -->
                        <label class="group relative flex items-center justify-between cursor-pointer rounded-xl border border-slate-100 p-4 focus:outline-none hover:border-emerald-200 hover:bg-emerald-50/30 transition-all">
                            <input type="radio" name="target_user" value="new" class="sr-only" required>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center group-hover:bg-pink-100 group-hover:text-pink-600 transition-colors">
                                    <i class="ph-duotone ph-user-plus text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900">User Baru (7 Hari)</h3>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 text-xs font-bold">{{ number_format($stats['new']) }} users</span>
                                <div class="target-indicator hidden w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">
                                    <i class="ph-bold ph-check text-xs"></i>
                                </div>
                            </div>
                        </label>

                    </div>
                </div>

                <!-- Anti Spam Warning -->
                <div class="bg-rose-50 border border-rose-200 rounded-2xl p-5 shadow-sm">
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center flex-shrink-0">
                            <i class="ph-duotone ph-warning-octagon text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-rose-900 mb-1">DILARANG SPAM!</h3>
                            <p class="text-xs font-medium text-rose-700 leading-relaxed mb-3">Email yang dikirim berlebihan dapat merusak reputasi domain, menyebabkan *blacklist* oleh penyedia email (Gmail, dll).</p>
                            <ul class="text-[11px] font-bold text-rose-800 space-y-1 bg-rose-100/50 p-3 rounded-xl border border-rose-200">
                                <li class="flex items-center gap-2"><i class="ph-bold ph-x text-rose-500"></i> Jangan kirim email harian</li>
                                <li class="flex items-center gap-2"><i class="ph-bold ph-check text-emerald-500"></i> Gunakan segmentasi target yang tepat</li>
                                <li class="flex items-center gap-2"><i class="ph-bold ph-check text-emerald-500"></i> Kirim info yang relevan dan penting</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <button type="button" onclick="showPreviewModal()" class="w-full flex items-center justify-center gap-2 bg-slate-900 hover:bg-slate-800 text-white px-6 py-4 rounded-xl text-sm font-bold shadow-lg shadow-slate-900/20 transition-all hover:scale-[1.02] active:scale-95">
                    <i class="ph-bold ph-eye text-lg"></i>
                    Preview & Kirim Email Blast
                </button>

            </div>
        </div>
    </form>
</div>

<!-- Dark Backdrop Modals (z-[100]) -->

<!-- Error Modal -->
<div id="errorModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xl hidden z-[9999] flex items-center justify-center p-4 transition-all duration-300">
    <div class="bg-white rounded-[2rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.2)] max-w-sm w-full max-h-[90vh] flex flex-col overflow-hidden border border-slate-100 transform transition-all animate-in fade-in zoom-in-95 duration-200">
        <!-- Modal Header -->
        <div class="bg-white px-6 py-5 text-slate-900 flex justify-between items-center border-b border-slate-100 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-50 border border-red-100 flex items-center justify-center shadow-sm text-red-500">
                    <i class="ph-bold ph-warning-circle text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black tracking-tight">Validasi Gagal</h3>
                    <p class="text-slate-400 text-[8px] font-bold uppercase tracking-widest mt-0.5">Periksa Formulir</p>
                </div>
            </div>
            <button type="button" onclick="hideErrorModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-50 transition-all text-slate-400 hover:text-slate-900">
                <i class="ph-bold ph-x text-base"></i>
            </button>
        </div>

        <div class="p-6 bg-white overflow-y-auto custom-scrollbar flex-1 text-center">
            <p id="errorMessage" class="text-sm font-medium text-slate-600 leading-relaxed"></p>
        </div>

        <div class="bg-slate-50 px-6 py-4 flex justify-end border-t border-slate-100 shrink-0">
            <button type="button" onclick="hideErrorModal()" class="w-full px-5 py-2.5 bg-slate-900 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-sm hover:bg-slate-800 transition-colors">
                Mengerti, Perbaiki
            </button>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xl hidden z-[9999] flex items-center justify-center p-4 transition-all duration-300">
    <div class="bg-white rounded-[2rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.2)] max-w-3xl w-full max-h-[90vh] flex flex-col overflow-hidden border border-slate-100 transform transition-all animate-in fade-in zoom-in-95 duration-200">
        <!-- Modal Header -->
        <div class="bg-white px-6 py-5 text-slate-900 flex justify-between items-center border-b border-slate-100 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-50 border border-primary-100 flex items-center justify-center shadow-sm text-primary-600">
                    <i class="ph-bold ph-eye text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black tracking-tight">Preview Email</h3>
                    <p class="text-slate-400 text-[8px] font-bold uppercase tracking-widest mt-0.5">Tampilan Email Asli</p>
                </div>
            </div>
            <button type="button" onclick="hidePreviewModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-50 transition-all text-slate-400 hover:text-slate-900">
                <i class="ph-bold ph-x text-base"></i>
            </button>
        </div>
        
        <div class="p-0 bg-slate-100/50 flex-1 overflow-hidden flex flex-col items-center justify-center relative min-h-[50vh]">
            <!-- Loader -->
            <div id="previewLoader" class="absolute inset-0 bg-slate-100/80 backdrop-blur-sm flex flex-col items-center justify-center z-10">
                <i class="ph-duotone ph-spinner-gap animate-spin text-4xl text-primary-500 mb-2"></i>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Memuat Preview...</p>
            </div>
            
            <!-- Iframe for Preview -->
            <div class="w-full h-full bg-white relative flex-1">
                <iframe id="previewFrame" class="w-full h-full absolute inset-0 border-0" sandbox="allow-same-origin"></iframe>
            </div>
        </div>
        
        <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-3 border-t border-slate-100 shrink-0">
            <button type="button" onclick="proceedToConfirm()" class="flex-1 px-5 py-2.5 bg-primary-600 border border-primary-600 text-white hover:bg-primary-700 rounded-xl text-xs font-black uppercase tracking-widest shadow-sm transition-colors flex items-center justify-center gap-2">
                <i class="ph-bold ph-check text-sm"></i>
                Lanjut Konfirmasi
            </button>
            <button type="button" onclick="hidePreviewModal()" class="flex-1 px-5 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl text-xs font-black uppercase tracking-widest shadow-sm transition-colors">
                Batal
            </button>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xl hidden z-[9999] flex items-center justify-center p-4 transition-all duration-300">
    <div class="bg-white rounded-[2rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.2)] max-w-sm w-full max-h-[90vh] flex flex-col overflow-hidden border border-slate-100 transform transition-all animate-in fade-in zoom-in-95 duration-200">
        <!-- Modal Header -->
        <div class="bg-white px-6 py-5 text-slate-900 flex justify-between items-center border-b border-slate-100 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-50 border border-primary-100 flex items-center justify-center shadow-sm text-primary-600 relative">
                    <i class="ph-bold ph-paper-plane-right text-xl"></i>
                    <div class="absolute -right-1 -top-1 w-2.5 h-2.5 bg-primary-500 rounded-full animate-ping"></div>
                </div>
                <div>
                    <h3 class="text-sm font-black tracking-tight">Konfirmasi</h3>
                    <p class="text-slate-400 text-[8px] font-bold uppercase tracking-widest mt-0.5">Pengiriman Serentak</p>
                </div>
            </div>
            <button type="button" onclick="hideConfirmModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-50 transition-all text-slate-400 hover:text-slate-900">
                <i class="ph-bold ph-x text-base"></i>
            </button>
        </div>

        <div class="p-6 bg-white overflow-y-auto custom-scrollbar flex-1">
            <p class="text-xs font-medium text-slate-500 leading-relaxed mb-6">Email akan segera diproses dan dikirimkan secara serentak ke kotak masuk target audience Anda.</p>
            
            <div class="bg-slate-50 rounded-xl p-4 text-left border border-slate-100 space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tipe Kampanye</span>
                    <span id="confirmEmailType" class="text-xs font-black text-slate-900"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Target Audience</span>
                    <span id="confirmTargetUser" class="text-xs font-black text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100"></span>
                </div>
            </div>
        </div>
        
        <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-3 border-t border-slate-100 shrink-0">
            <button type="button" onclick="submitEmailBlast()" class="flex-1 px-5 py-2.5 bg-primary-600 border border-primary-600 text-white hover:bg-primary-700 rounded-xl text-xs font-black uppercase tracking-widest shadow-sm transition-colors flex items-center justify-center gap-2">
                <i class="ph-bold ph-rocket-launch text-sm"></i>
                Kirim
            </button>
            <button type="button" onclick="hideConfirmModal()" class="flex-1 px-5 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl text-xs font-black uppercase tracking-widest shadow-sm transition-colors flex items-center justify-center">
                Batal
            </button>
        </div>
    </div>
</div>

<!-- Blasting Progress Modal -->
<div id="progressModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xl hidden z-[9999] flex items-center justify-center p-4 transition-all duration-300">
    <div class="bg-white rounded-[2rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.2)] max-w-lg w-full max-h-[90vh] flex flex-col overflow-hidden border border-slate-100 transform transition-all animate-in fade-in zoom-in-95 duration-200">
        <!-- Modal Header -->
        <div class="bg-white px-6 py-5 text-slate-900 flex justify-between items-center border-b border-slate-100 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-50 border border-primary-100 flex items-center justify-center shadow-sm text-primary-600">
                    <i class="ph-bold ph-paper-plane-tilt text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black tracking-tight">Blasting Email</h3>
                    <p id="progressStatusText" class="text-slate-400 text-[8px] font-bold uppercase tracking-widest mt-0.5">Mempersiapkan Audience</p>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white overflow-y-auto custom-scrollbar flex-1">
            <!-- Main circular/bar progress -->
            <div class="space-y-4">
                <!-- Progress Stats -->
                <div class="flex justify-between items-end mb-1">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Progress Pengiriman</span>
                    <span id="progressPercent" class="text-lg font-black text-primary-600">0%</span>
                </div>
                
                <!-- Progress Bar -->
                <div class="w-full bg-slate-50 rounded-full h-3 overflow-hidden border border-slate-100">
                    <div id="progressBar" class="bg-primary-500 h-full rounded-full transition-all duration-300 w-0"></div>
                </div>
                
                <!-- Numeric Counter Card -->
                <div class="grid grid-cols-3 gap-3 bg-slate-50 border border-slate-100 rounded-2xl p-4 text-center mt-6">
                    <div class="border-r border-slate-200/60">
                        <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest">Target</span>
                        <span id="progressTotalCount" class="text-lg font-black text-slate-800">0</span>
                    </div>
                    <div class="border-r border-slate-200/60">
                        <span class="block text-[9px] font-bold text-emerald-500 uppercase tracking-widest">Sukses</span>
                        <span id="progressSuccessCount" class="text-lg font-black text-emerald-600">0</span>
                    </div>
                    <div>
                        <span class="block text-[9px] font-bold text-rose-500 uppercase tracking-widest">Gagal</span>
                        <span id="progressFailedCount" class="text-lg font-black text-rose-600">0</span>
                    </div>
                </div>
            </div>

            <!-- Current Sending Card -->
            <div id="currentSendingContainer" class="mt-6 p-4 bg-indigo-50/50 border border-indigo-100 rounded-2xl flex items-center gap-3 text-left">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center flex-shrink-0">
                    <i class="ph-bold ph-envelope text-lg"></i>
                </div>
                <div class="overflow-hidden">
                    <span class="block text-[9px] font-bold text-indigo-500 uppercase tracking-widest">Mengirim ke</span>
                    <span id="currentSendingEmail" class="block text-xs font-bold text-indigo-900 truncate">Menunggu antrean...</span>
                </div>
            </div>

            <!-- Live Log Container -->
            <div class="mt-6 border border-slate-100 rounded-2xl overflow-hidden bg-slate-900 text-slate-100 text-left">
                <div class="px-4 py-3 bg-slate-800 border-b border-slate-700 flex justify-between items-center">
                    <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span> Live Log Aktivitas
                    </span>
                    <span class="text-[8px] font-bold text-slate-500 bg-slate-900 px-1.5 py-0.5 rounded border border-slate-700">Console</span>
                </div>
                <div id="progressLog" class="p-4 h-36 overflow-y-auto custom-scrollbar font-mono text-[10px] space-y-1.5 scroll-smooth">
                    <div class="text-slate-400">[SYSTEM] Sistem siap melakukan blasting...</div>
                </div>
            </div>
        </div>
        
        <!-- Finish Actions -->
        <div id="progressFinishActions" class="hidden bg-slate-50 px-6 py-4 border-t border-slate-100 shrink-0">
            <button type="button" onclick="window.location.reload()" class="w-full px-5 py-2.5 bg-slate-900 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-sm hover:bg-slate-800 transition-colors">
                Selesai & Muat Ulang Halaman
            </button>
        </div>
    </div>
</div>

<script>
    // Radio selection styling logic
    function setupRadioStyles(groupName, activeBorderClass, activeBgClass, indicatorClass) {
        const radios = document.querySelectorAll(`input[name="${groupName}"]`);
        
        function update() {
            radios.forEach(r => {
                const label = r.closest('label');
                const indicator = label.querySelector(indicatorClass);
                
                if (r.checked) {
                    label.classList.add(activeBorderClass, activeBgClass);
                    label.classList.remove('border-slate-100');
                    if(indicator) indicator.classList.remove('hidden');
                } else {
                    label.classList.remove(activeBorderClass, activeBgClass);
                    label.classList.add('border-slate-100');
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
            // Slight delay to allow display block to process before adding transform (for fade-in effect)
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
        customEmailFields.classList.add('opacity-0', '-translate-y-4');
        
        // Setup styled radios
        setupRadioStyles('email_type', 'border-primary-500', 'bg-primary-50/30', '.indicator');
        setupRadioStyles('target_user', 'border-emerald-500', 'bg-emerald-50/50', '.target-indicator');
        
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
        logContainer.innerHTML = '<div class="text-slate-400">[SYSTEM] Memulai proses blasting email...</div>';
        
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
            'slate': 'text-slate-400',
            'emerald': 'text-emerald-400 font-bold',
            'rose': 'text-rose-400 font-bold',
            'indigo': 'text-indigo-400'
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
            document.getElementById('currentSendingContainer').className = 'mt-6 p-3 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 text-left';
            document.getElementById('currentSendingContainer').querySelector('div').className = 'w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0';
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
            // Update progress percent and bar
            blastingCurrentIndex++;
            const percent = Math.round((blastingCurrentIndex / blastingUsers.length) * 100);
            document.getElementById('progressBar').style.width = `${percent}%`;
            document.getElementById('progressPercent').textContent = `${percent}%`;
            
            // Send to next user
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
