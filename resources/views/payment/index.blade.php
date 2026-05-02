<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary-600/10 border border-primary-600/20 flex items-center justify-center">
                <i class="ph-fill ph-shield-check text-xl text-primary-600"></i>
            </div>
            <div>
                <h1 class="text-xl font-black text-slate-900 tracking-tight">Checkout Secure</h1>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Transaksi Terenkripsi & Terverifikasi</p>
            </div>
        </div>
    </x-slot>

    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        [x-cloak] { display: none !important; }
        
        .payment-method-card {
            background: white;
            border: 1.5px solid #f1f5f9;
            transition: all 0.2s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            padding: 1.25rem;
            border-radius: 1.25rem;
        }

        .payment-method-card:hover {
            border-color: #e2e8f0;
            background: #f8fafc;
        }

        input[type="radio"]:checked + .payment-method-card {
            border-color: #a570f0;
            background: #fdfaff;
            box-shadow: 0 0 0 1px #a570f0;
        }

        .logo-box {
            width: 100%;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border-radius: 0.75rem;
            margin-bottom: 0.75rem;
            padding: 0.5rem;
        }

        .logo-box img {
            max-width: 80%;
            max-height: 80%;
            object-fit: contain;
        }

        .check-indicator {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            width: 18px;
            height: 18px;
            background: #a570f0;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transform: scale(0.5);
            transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        input[type="radio"]:checked + .payment-method-card .check-indicator {
            opacity: 1;
            transform: scale(1);
        }

        .summary-card {
            position: sticky;
            top: 2rem;
            background: white;
            border: 1px solid #f1f5f9;
            border-radius: 1.5rem;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        }
    </style>

    <div class="bg-[#fcfcfd] min-h-screen pb-20" x-data="{ selectedMethod: '' }">
        <div class="max-w-[1000px] mx-auto px-4 pt-8">
            
            {{-- ── Stepper ────────────────────────────────── --}}
            <div class="flex items-center justify-center gap-4 mb-10">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-primary-600 shadow-[0_0_0_4px_rgba(165,112,240,0.1)]"></div>
                    <span class="text-[9px] font-black text-slate-900 uppercase tracking-widest">Metode</span>
                </div>
                <div class="w-10 h-[1px] bg-slate-200"></div>
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-slate-200"></div>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Bayar</span>
                </div>
                <div class="w-10 h-[1px] bg-slate-200"></div>
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-slate-200"></div>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Selesai</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {{-- ── Left: Methods ─────────────────────────── --}}
                <div class="lg:col-span-7 space-y-8">
                    <form action="{{ route('payment.checkout') }}" method="POST" id="paymentForm">
                        @csrf
                        <div class="space-y-10">
                            @foreach($groupedChannels as $categoryCode => $channels)
                            <div>
                                <div class="flex items-center justify-between mb-5">
                                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                        {{ $categoryCode == 'E_WALLET' ? 'Digital Wallet & QRIS' : 'Virtual Account' }}
                                    </h3>
                                    <span class="text-[8px] font-black text-emerald-500 uppercase tracking-widest bg-emerald-50 px-2 py-0.5 rounded">Otomatis</span>
                                </div>
                                
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    @foreach($channels as $channel)
                                    <label class="relative block cursor-pointer h-full">
                                        <input type="radio" name="payment_channel_code" value="{{ $channel['code'] }}" class="peer sr-only" x-model="selectedMethod" required>
                                        <div class="payment-method-card border border-slate-100">
                                            <div class="check-indicator">
                                                <i class="ph-bold ph-check text-[10px]"></i>
                                            </div>
                                            
                                            <div class="logo-box border border-slate-50">
                                                <img src="{{ $channel['image_url'] }}" alt="{{ $channel['name'] }}" loading="lazy" 
                                                     onerror="this.onerror=null; this.src='https://api.iconify.design/ph:bank-bold.svg?color=%23cbd5e1'">
                                            </div>
                                            
                                            <div class="text-center">
                                                <p class="text-[10px] font-black text-slate-800 leading-tight">{{ $channel['name'] }}</p>
                                                <p class="text-[7px] font-bold text-slate-400 uppercase tracking-widest mt-1">Instant</p>
                                            </div>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </form>
                </div>

                {{-- ── Right: Summary ────────────────────────── --}}
                <div class="lg:col-span-5">
                    <div class="summary-card space-y-6">
                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest">Ringkasan Pesanan</h4>
                        
                        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 space-y-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-[11px] font-black text-slate-900">TraKerja Lifetime Pro</p>
                                    <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest mt-1">Unlimited Access</p>
                                </div>
                                <p class="text-[11px] font-black text-slate-900 tracking-tight">Rp 15.000</p>
                            </div>
                            
                            <div class="pt-4 border-t border-slate-200/60 space-y-2">
                                <div class="flex justify-between text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                                    <span>Subtotal</span>
                                    <span class="text-slate-900">Rp 15.000</span>
                                </div>
                                <div class="flex justify-between text-[9px] font-bold text-emerald-500 uppercase tracking-widest">
                                    <span>Biaya Layanan</span>
                                    <span>Gratis</span>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-slate-200 flex justify-between items-center">
                                <span class="text-[10px] font-black text-slate-900 uppercase tracking-widest">Total</span>
                                <span class="text-lg font-black text-primary-600 tracking-tight">Rp 15.000</span>
                            </div>
                        </div>

                        <button type="submit" form="paymentForm"
                                :disabled="!selectedMethod"
                                class="w-full py-4 bg-slate-900 text-white rounded-xl font-black text-[10px] uppercase tracking-[0.2em] shadow-xl hover:bg-primary-600 disabled:opacity-30 transition-all flex items-center justify-center gap-2 group">
                            <i class="ph-bold ph-lock-key text-base group-hover:rotate-12 transition-transform"></i>
                            Konfirmasi & Bayar
                        </button>

                        <div class="flex items-center justify-center gap-6 opacity-60">
                            <div class="flex items-center gap-1.5">
                                <img src="{{ asset('images/qris.png') }}" class="h-3">
                            </div>
                            <div class="w-[1px] h-3 bg-slate-200"></div>
                            <div class="flex items-center gap-1.5">
                                <i class="ph-bold ph-shield-check text-slate-900"></i>
                                <span class="text-[8px] font-black text-slate-900 uppercase tracking-widest">PCI-DSS Secure</span>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            const btn = document.querySelector('button[form="paymentForm"]');
            btn.innerHTML = '<i class="ph-bold ph-circle-notch animate-spin text-base"></i> Loading...';
            btn.disabled = true;
        });
    </script>
</x-app-layout>
