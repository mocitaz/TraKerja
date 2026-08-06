<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col items-center text-center">
            <h1 class="text-xs font-black text-zinc-400 uppercase tracking-widest">Transaction Status</h1>
        </div>
    </x-slot>

    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    <style>
        .receipt-card {
            background-color: #ffffff;
            border: 1px solid #e4e4e7;
            border-radius: 12px;
            position: relative;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        }
        /* Notion dot border styles for receipt feeling */
        .receipt-divider {
            height: 1px;
            background-image: linear-gradient(to right, #e4e4e7 50%, rgba(255, 255, 255, 0) 0%);
            background-position: bottom;
            background-size: 8px 1px;
            background-repeat: repeat-x;
            position: relative;
        }
        /* Left and Right half-circular punch holes */
        .receipt-divider::before, .receipt-divider::after {
            content: '';
            position: absolute;
            width: 14px;
            height: 14px;
            background-color: #fafafa;
            border: 1px solid #e4e4e7;
            border-radius: 50%;
            top: -7px;
        }
        .receipt-divider::before {
            left: -8px;
            clip-path: polygon(50% 0%, 100% 0%, 100% 100%, 50% 100%);
        }
        .receipt-divider::after {
            right: -8px;
            clip-path: polygon(0% 0%, 50% 0%, 50% 100%, 0% 100%);
        }
        .receipt-status-badge {
            background-color: #f4f4f5;
            border: 1px solid #e4e4e7;
        }
        .success-dot {
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(0.9); opacity: 1; }
            50% { transform: scale(1.15); opacity: 0.7; }
            100% { transform: scale(0.9); opacity: 1; }
        }
    </style>

    <div class="bg-[#fafafa] min-h-screen pb-20 pt-10 select-none">
        <div class="max-w-[400px] mx-auto px-4">
            
            <div class="receipt-card overflow-hidden">
                
                {{-- Header Pembayaran --}}
                <div class="p-8 text-center">
                    <div class="w-11 h-11 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center mx-auto mb-4">
                        <i class="ph-bold ph-check text-emerald-600 text-lg"></i>
                    </div>
                    
                    <span class="px-2.5 py-0.8 rounded-full text-[9px] font-black text-zinc-700 uppercase tracking-wider receipt-status-badge inline-flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full success-dot"></span>
                        Success
                    </span>
                    
                    <h2 class="text-lg font-black text-zinc-900 tracking-tight mt-4 mb-1">Receipt for Lifetime Access</h2>
                    <p class="text-[10px] text-zinc-400 font-semibold">Thank you! Your payment was verified successfully.</p>
                </div>

                {{-- Tear-off Line (Gerigi Efek) --}}
                <div class="receipt-divider"></div>

                {{-- Konten Pembayaran / Rincian Struk --}}
                <div class="p-8 space-y-6">
                    
                    {{-- Row Info Struk --}}
                    <div class="space-y-3.5 text-xs">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-zinc-400 text-[10px] uppercase tracking-wider">Order ID</span>
                            <span class="font-mono font-bold text-zinc-900">{{ $payment->order_id }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-zinc-400 text-[10px] uppercase tracking-wider">Date</span>
                            <span class="font-semibold text-zinc-800">{{ $payment->paid_at?->format('d M Y, H:i') ?? now()->format('d M Y, H:i') }} WIB</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-zinc-400 text-[10px] uppercase tracking-wider">Method</span>
                            <span class="font-bold text-zinc-800 uppercase">{{ $payment->payment_method ?? 'QRIS' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-zinc-400 text-[10px] uppercase tracking-wider">Plan Access</span>
                            <span class="font-black text-zinc-900 uppercase text-[9.5px] tracking-wider">Lifetime Premium</span>
                        </div>
                        
                        <div class="border-t border-zinc-100 pt-3 flex justify-between items-center">
                            <span class="font-bold text-zinc-900 text-[10px] uppercase tracking-wider">Amount Paid</span>
                            <span class="font-mono font-black text-zinc-950 text-base">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Manfaat Premium Mini Grid --}}
                    <div class="p-4 bg-zinc-50 border border-zinc-200/60 rounded-lg space-y-2.5">
                        <p class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Member Privileges</p>
                        <div class="grid grid-cols-2 gap-2 text-[10.5px] font-bold text-zinc-650">
                            <div class="flex items-center gap-1.5"><i class="ph ph-check text-emerald-600 text-xs"></i> <span>Unlimited Tracker</span></div>
                            <div class="flex items-center gap-1.5"><i class="ph ph-check text-emerald-600 text-xs"></i> <span>Full Dashboard</span></div>
                            <div class="flex items-center gap-1.5"><i class="ph ph-check text-emerald-600 text-xs"></i> <span>Premium PDF</span></div>
                            <div class="flex items-center gap-1.5"><i class="ph ph-check text-emerald-600 text-xs"></i> <span>Auto AI Credits</span></div>
                        </div>
                    </div>

                    {{-- Navigation Actions --}}
                    <div class="space-y-2 pt-2">
                        <a href="{{ route('dashboard') }}" class="w-full py-3 bg-zinc-900 hover:bg-zinc-800 text-white rounded-lg font-bold text-[10px] uppercase tracking-wider shadow-3xs hover:shadow-2xs transition text-center flex items-center justify-center gap-1.5 active:scale-[0.98]">
                            <i class="ph-bold ph-layout text-sm"></i>
                            Go to Dashboard
                        </a>
                        <a href="{{ route('tracker') }}" class="w-full py-3 bg-white hover:bg-zinc-50 text-zinc-700 border border-zinc-200 rounded-lg font-bold text-[10px] uppercase tracking-wider transition text-center flex items-center justify-center gap-1">
                            Start Tracking Jobs <i class="ph-bold ph-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>

                {{-- Footer Struk --}}
                <div class="py-4 bg-zinc-50/70 text-center border-t border-zinc-150 text-[9.5px] font-bold text-zinc-450 uppercase tracking-wider">
                    Receipt sent to {{ Auth::user()->email }}
                </div>
            </div>
            
            <div class="mt-8 text-center text-[9px] font-bold text-zinc-400 uppercase tracking-widest space-y-1">
                <p>TraKerja - Simple Career Management Platform</p>
                <p>Support: <a href="mailto:support@trakerja.com" class="text-zinc-650 hover:underline">support@trakerja.com</a></p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Delightful subtle burst sequence
            var count = 150;
            var defaults = {
                origin: { y: 0.65 },
                zIndex: 1000
            };

            function fire(particleRatio, opts) {
                confetti(Object.assign({}, defaults, opts, {
                    particleCount: Math.floor(count * particleRatio)
                }));
            }

            setTimeout(() => {
                fire(0.25, { spread: 26, startVelocity: 55 });
                fire(0.2, { spread: 60 });
                fire(0.35, { spread: 100, decay: 0.91, scalar: 0.8 });
                fire(0.1, { spread: 120, startVelocity: 25, decay: 0.92, scalar: 1.2 });
            }, 300);
        });
    </script>
</x-app-layout>
