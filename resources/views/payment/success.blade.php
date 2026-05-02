<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col items-center text-center">
            <h1 class="text-xl font-black text-slate-900 tracking-tight">Payment Status</h1>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Konfirmasi transaksi berhasil</p>
        </div>
    </x-slot>

    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        .success-checkmark {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            background: #ecfdf5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 4px solid #fff;
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.1);
        }
        
        .success-checkmark i {
            font-size: 40px;
            color: #10b981;
            animation: bounceIn 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }

        .premium-badge-glow {
            background: linear-gradient(135deg, #a570f0 0%, #6366f1 100%);
            box-shadow: 0 10px 20px rgba(165, 112, 240, 0.2);
        }
    </style>

    <div class="bg-[#f8fafc] min-h-screen pb-20 pt-10">
        <div class="max-w-[480px] mx-auto px-4">
            
            <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.03)] border border-slate-100 overflow-hidden">
                
                {{-- ── Success Header ────────────────────────── --}}
                <div class="p-10 text-center space-y-6">
                    <div class="success-checkmark">
                        <i class="ph-fill ph-check-circle"></i>
                    </div>
                    
                    <div class="space-y-2">
                        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Pembayaran Berhasil!</h2>
                        <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest leading-relaxed px-10">
                            Selamat! Akun Anda kini aktif sebagai <span class="text-primary-600">Lifetime Premium</span>.
                        </p>
                    </div>
                </div>

                {{-- ── Transaction Details ────────────────────── --}}
                <div class="px-10 pb-10 space-y-8">
                    <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 space-y-4">
                        <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest text-slate-400">
                            <span>Order ID</span>
                            <span class="text-slate-900">{{ $payment->order_id }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest text-slate-400">
                            <span>Metode</span>
                            <span class="text-slate-900">{{ strtoupper($payment->payment_method ?? 'QRIS') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest text-slate-400">
                            <span>Total Bayar</span>
                            <span class="text-primary-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="pt-4 border-t border-slate-200/60 flex justify-between items-center text-[10px] font-black uppercase tracking-widest text-slate-400">
                            <span>Status</span>
                            <span class="flex items-center gap-1.5 text-emerald-600">
                                <i class="ph-fill ph-seal-check text-base"></i> Terverifikasi
                            </span>
                        </div>
                    </div>

                    <div class="premium-badge-glow p-6 rounded-2xl text-white">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                                <i class="ph-fill ph-sparkle text-xl text-white"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest opacity-80">Membership Aktif</p>
                                <p class="text-xs font-black tracking-tight">Semua Fitur Premium Terbuka</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3 pt-2">
                        <a href="{{ route('dashboard') }}" class="w-full py-4 bg-slate-900 text-white rounded-xl font-black text-[10px] uppercase tracking-[0.2em] shadow-xl hover:bg-primary-600 transition-all flex items-center justify-center gap-2 group">
                            <i class="ph-bold ph-layout text-base group-hover:rotate-12 transition-transform"></i>
                            Ke Dashboard
                        </a>
                        <a href="{{ route('tracker') }}" class="w-full py-4 bg-white text-slate-900 border border-slate-200 rounded-xl font-black text-[10px] uppercase tracking-[0.2em] hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                            Mulai Melacak Pekerjaan <i class="ph-bold ph-arrow-right"></i>
                        </a>
                    </div>
                </div>

                {{-- ── Footer Note ────────────────────────────── --}}
                <div class="p-6 bg-slate-50 text-center border-t border-slate-100">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                        Email konfirmasi telah dikirim ke <span class="text-slate-900">{{ Auth::user()->email }}</span>
                    </p>
                </div>
            </div>
            
            <div class="mt-8 text-center">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Butuh Bantuan? Hubungi support@trakerja.com</p>
            </div>
        </div>
    </div>
</x-app-layout>
