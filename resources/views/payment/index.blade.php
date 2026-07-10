<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <h1 class="text-base font-semibold text-zinc-900 tracking-tight">Checkout Secure</h1>
                <span class="px-2 py-0.5 bg-primary-50 text-zinc-800 border border-primary-200/60 rounded text-[10px] font-bold tracking-wide uppercase">Checkout</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200/60 text-[10px] font-bold uppercase tracking-wider">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    256-Bit SSL Encrypted
                </span>
            </div>
        </div>
    </x-slot>

    <div class="max-w-[1100px] mx-auto px-4 py-6" x-data="{ selectedMethod: '' }">
        
        {{-- ── Hyper-Compact Notion Stepper ────────────────── --}}
        <div class="flex items-center justify-center gap-3 mb-6 bg-white border border-zinc-200/80 rounded-lg p-2.5 max-w-xl mx-auto shadow-3xs">
            <div class="flex items-center gap-2">
                <span class="w-5 h-5 rounded-full bg-primary-500 text-white flex items-center justify-center text-[10px] font-bold">1</span>
                <span class="text-xs font-semibold text-zinc-900 tracking-tight">Metode Pembayaran</span>
            </div>
            <div class="w-8 h-[1px] bg-zinc-200"></div>
            <div class="flex items-center gap-2 opacity-50">
                <span class="w-5 h-5 rounded-full bg-zinc-100 text-zinc-500 border border-zinc-200 flex items-center justify-center text-[10px] font-bold">2</span>
                <span class="text-xs font-medium text-zinc-500 tracking-tight">Konfirmasi Ringkasan</span>
            </div>
            <div class="w-8 h-[1px] bg-zinc-200"></div>
            <div class="flex items-center gap-2 opacity-50">
                <span class="w-5 h-5 rounded-full bg-zinc-100 text-zinc-500 border border-zinc-200 flex items-center justify-center text-[10px] font-bold">3</span>
                <span class="text-xs font-medium text-zinc-500 tracking-tight">Aktivasi Instan</span>
            </div>
        </div>

        {{-- ── Error Notification ────────────────────── --}}
        @if ($errors->any())
        <div class="mb-6 p-3.5 bg-rose-50 border border-rose-200 rounded-lg shadow-3xs">
            <div class="flex gap-2.5 items-start">
                <i class="ph-bold ph-warning-circle text-rose-600 text-base shrink-0 mt-0.5"></i>
                <div>
                    <h5 class="text-xs font-bold text-rose-900 uppercase tracking-wider">Transaksi Gagal Diproses</h5>
                    <ul class="mt-1 list-disc list-inside text-xs text-rose-700 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            {{-- ── Left Column: Payment Methods (7 Cols) ─────────────────── --}}
            <div class="lg:col-span-7 space-y-6">
                <form action="{{ route('payment.checkout') }}" method="POST" id="paymentForm">
                    @csrf
                    <input type="hidden" name="package_type" value="premium">

                    <div class="space-y-6">
                        @foreach($groupedChannels as $categoryCode => $channels)
                        <div class="bg-white border border-zinc-200/80 rounded-lg p-4 shadow-3xs">
                            <div class="flex items-center justify-between pb-3 mb-3 border-b border-zinc-100">
                                <h3 class="text-xs font-bold text-zinc-800 tracking-tight uppercase">
                                    {{ $categoryCode == 'E_WALLET' ? 'Digital Wallet & QRIS' : 'Virtual Account Transfer' }}
                                </h3>
                                <span class="text-[9px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200/60 px-2 py-0.5 rounded uppercase tracking-wider">Verifikasi Otomatis</span>
                            </div>
                            
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                                @foreach($channels as $channel)
                                <label class="relative block cursor-pointer select-none">
                                    <input type="radio" name="payment_channel_code" value="{{ $channel['code'] }}" class="peer sr-only" x-model="selectedMethod" required>
                                    <div class="p-3 rounded-lg border border-zinc-200 bg-white hover:bg-zinc-50 transition-all peer-checked:border-zinc-800 peer-checked:ring-1 peer-checked:ring-zinc-800/10 flex flex-col justify-between h-full min-h-[95px] relative group">
                                        
                                        {{-- Selected Checkmark Indicator --}}
                                        <div class="absolute top-2 right-2 w-4 h-4 rounded-full bg-zinc-900 text-white opacity-0 transition-all flex items-center justify-center shadow-3xs"
                                             :class="selectedMethod === '{{ $channel['code'] }}' ? 'opacity-100 scale-100' : 'opacity-0 scale-75'">
                                            <i class="ph-bold ph-check text-[9px]"></i>
                                        </div>
                                        
                                        {{-- Logo Container --}}
                                        <div class="h-9 w-full bg-zinc-50/50 rounded p-1 flex items-center justify-center mb-2 group-hover:bg-white transition-colors">
                                            <img src="{{ $channel['image_url'] }}" alt="{{ $channel['name'] }}" loading="lazy" class="max-h-full max-w-[85%] object-contain"
                                                 onerror="this.onerror=null; this.src='https://api.iconify.design/ph:bank-bold.svg?color=%2371717a'">
                                        </div>
                                        
                                        <div class="text-left">
                                            <p class="text-[10px] font-extrabold text-zinc-800 leading-tight tracking-tight truncate">{{ $channel['name'] }}</p>
                                            <p class="text-[8px] font-bold text-zinc-400 uppercase tracking-widest mt-0.5">Instant</p>
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

            {{-- ── Right Column: Order Summary (5 Cols) ────────────────── --}}
            <div class="lg:col-span-5">
                <div class="sticky top-20 bg-white border border-zinc-200/80 rounded-lg p-4 space-y-4 shadow-3xs">
                    
                    <div class="flex items-center justify-between pb-3 border-b border-zinc-100">
                        <h4 class="text-xs font-bold text-zinc-900 uppercase tracking-wider">Ringkasan Pesanan</h4>
                        <span class="text-[10px] font-bold text-primary-700 bg-primary-50 border border-primary-200/60 px-2 py-0.5 rounded">Tagihan Final</span>
                    </div>
                    
                    {{-- Included Features List --}}
                    <div class="p-3.5 bg-zinc-50/60 rounded-lg border border-zinc-200/60 space-y-3">
                        <div class="flex justify-between items-start pb-2.5 border-b border-zinc-200/60">
                            <div>
                                <p class="text-xs font-bold text-zinc-900 tracking-tight">TraKerja Lifetime Pro Pass</p>
                                <p class="text-[10px] text-zinc-500 font-medium mt-0.5">Akses Penuh Tanpa Batas Waktu</p>
                            </div>
                            <span class="text-xs font-bold text-zinc-900 tracking-tight shrink-0">Rp {{ number_format($premiumPrice, 0, ',', '.') }}</span>
                        </div>

                        <div class="space-y-1.5 pt-0.5">
                            <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Fitur Pro Termasuk:</p>
                            <div class="grid grid-cols-1 gap-1 text-[11px] font-medium text-zinc-700">
                                <div class="flex items-center gap-2">
                                    <i class="ph-bold ph-check text-emerald-600 text-xs shrink-0"></i>
                                    <span>Unlimited Job Tracking & Application Management</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="ph-bold ph-check text-emerald-600 text-xs shrink-0"></i>
                                    <span>Full AI Resume Analyzer & Scoring</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="ph-bold ph-check text-emerald-600 text-xs shrink-0"></i>
                                    <span>AI Cover Letter Generator (Unlimited)</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="ph-bold ph-check text-emerald-600 text-xs shrink-0"></i>
                                    <span>AI Photo Studio Enhancer</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="ph-bold ph-check text-emerald-600 text-xs shrink-0"></i>
                                    <span>Export All ATS Premium Templates</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Financial Breakdown --}}
                    <div class="space-y-2 px-1 text-xs">
                        <div class="flex justify-between font-medium text-zinc-500">
                            <span>Subtotal Harga</span>
                            <span class="text-zinc-800 font-semibold">Rp {{ number_format($premiumPrice, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between font-medium text-emerald-600">
                            <span>Biaya Layanan & PPN</span>
                            <span class="font-bold">Gratis (Rp 0)</span>
                        </div>
                        <div class="pt-2 border-t border-zinc-200/80 flex justify-between items-center text-sm">
                            <span class="font-bold text-zinc-900">Total Pembayaran</span>
                            <span class="font-black text-primary-600 tracking-tight text-base">Rp {{ number_format($premiumPrice, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Submit Action Button --}}
                    <button type="submit" form="paymentForm" id="btn-submit-payment"
                            :disabled="!selectedMethod"
                            class="w-full py-2.5 px-4 bg-primary-50 hover:bg-primary-100 text-zinc-850 border border-primary-200/80 rounded-md text-xs font-bold uppercase tracking-wider transition-all duration-150 shadow-3xs flex items-center justify-center gap-2 disabled:opacity-40 disabled:cursor-not-allowed active:scale-98 focus:outline-none mt-3">
                        <i id="icon-submit-payment" class="ph-bold ph-lock-key text-sm"></i>
                        <span id="spinner-submit-payment" class="hidden w-3.5 h-3.5 border-2 border-zinc-400 border-t-zinc-800 rounded-full animate-spin"></span>
                        <span id="text-submit-payment">Konfirmasi & Proses Pembayaran</span>
                    </button>

                    {{-- Trust Badges --}}
                    <div class="pt-2 flex items-center justify-center gap-4 text-[10px] font-medium text-zinc-400 border-t border-zinc-100">
                        <span class="flex items-center gap-1">
                            <i class="ph-bold ph-shield-check text-zinc-600 text-xs"></i>
                            256-Bit SSL
                        </span>
                        <span class="text-zinc-300">•</span>
                        <span class="flex items-center gap-1">
                            <i class="ph-bold ph-lightning text-zinc-600 text-xs"></i>
                            Verifikasi Instan
                        </span>
                        <span class="text-zinc-300">•</span>
                        <span class="flex items-center gap-1">
                            <i class="ph-bold ph-qr-code text-zinc-600 text-xs"></i>
                            QRIS Supported
                        </span>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script>
        document.getElementById('paymentForm')?.addEventListener('submit', function() {
            const btn = document.getElementById('btn-submit-payment');
            const icon = document.getElementById('icon-submit-payment');
            const spinner = document.getElementById('spinner-submit-payment');
            const text = document.getElementById('text-submit-payment');
            if (btn) {
                btn.disabled = true;
                btn.classList.add('opacity-70', 'cursor-not-allowed');
                icon?.classList.add('hidden');
                spinner?.classList.remove('hidden');
                if (text) text.textContent = 'Memproses Transaksi…';
            }
        });
    </script>
</x-app-layout>
