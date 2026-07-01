<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary-600/10 border border-primary-600/20 flex items-center justify-center">
                <i class="ph-fill ph-crown text-xl text-primary-600"></i>
            </div>
            <div>
                <h1 class="text-xl font-black text-slate-900 tracking-tight">TraKerja Pro</h1>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">The ultimate career acceleration tool</p>
            </div>
        </div>
    </x-slot>

    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        [x-cloak] { display: none !important; }
        
        .premium-container {
            max-width: 1100px;
            margin: 0 auto;
        }

        .benefit-card {
            background: white;
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
            height: 100%;
        }

        .benefit-card:hover {
            border-color: #e2e8f0;
            box-shadow: 0 10px 25px rgba(0,0,0,0.02);
            transform: translateY(-2px);
        }

        .pricing-section {
            background: #0f172a;
            border-radius: 2rem;
            position: relative;
            overflow: hidden;
        }

        .pricing-section::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle at top right, rgba(99, 102, 241, 0.1), transparent 70%);
        }

        .check-icon {
            width: 18px;
            height: 18px;
            background: #ecfdf5;
            color: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
        }
    </style>

    <div class="bg-[#fcfcfd] min-h-screen pb-24">
        <div class="premium-container px-4 pt-8 sm:pt-16">
            
            {{-- ── Centered Hero ────────────────────────── --}}
            <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-20 space-y-6 sm:space-y-8">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white border border-slate-200 rounded-full shadow-sm">
                    <span class="text-[9px] font-black text-primary-600 uppercase tracking-widest">Upgrade to Lifetime Pro</span>
                </div>
                
                <h2 class="text-3xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tighter leading-[1.1] sm:leading-[1.05]">
                    Forget the old way. <br/> Upgrade your <span class="text-primary-600">strategy</span>.
                </h2>
                
                <p class="text-slate-500 text-sm sm:text-base lg:text-lg leading-relaxed font-medium px-2 sm:px-6">
                    TraKerja Pro provides AI-powered tools and unlimited data management to ensure every application has the maximum chance of success.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4 w-full px-4 sm:px-0">
                    <a href="{{ route('payment.index') }}" class="w-full sm:w-auto text-center px-6 sm:px-8 py-3.5 sm:py-4 bg-slate-900 text-white rounded-xl font-black text-[10px] sm:text-[11px] uppercase tracking-[0.2em] shadow-2xl hover:bg-primary-600 hover:-translate-y-1 transition-all active:scale-95">
                        Upgrade Now — Rp {{ number_format($premiumPrice, 0, ',', '.') }}
                    </a>
                </div>
            </div>

            {{-- ── Precision Grid ────────────────────────── --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 sm:mb-20">
                <div class="benefit-card p-6 sm:p-10 rounded-[1.5rem] sm:rounded-[2rem] flex flex-col items-center text-center">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-primary-50 flex items-center justify-center mb-6 sm:mb-8">
                        <i class="ph-fill ph-magic-wand text-xl sm:text-2xl text-primary-600"></i>
                    </div>
                    <h3 class="text-base sm:text-lg font-black text-slate-900 tracking-tight mb-3 sm:mb-4">AI Analysis</h3>
                    <p class="text-xs text-slate-400 leading-relaxed font-medium">Instant compatibility analysis between your CV and job descriptions using the latest GPT models.</p>
                </div>

                <div class="benefit-card p-6 sm:p-10 rounded-[1.5rem] sm:rounded-[2rem] flex flex-col items-center text-center">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-emerald-50 flex items-center justify-center mb-6 sm:mb-8">
                        <i class="ph-fill ph-infinity text-xl sm:text-2xl text-emerald-600"></i>
                    </div>
                    <h3 class="text-base sm:text-lg font-black text-slate-900 tracking-tight mb-3 sm:mb-4">Unlimited Jobs</h3>
                    <p class="text-xs text-slate-400 leading-relaxed font-medium">Track unlimited job applications. Keep your entire career history in one safe place.</p>
                </div>

                <div class="benefit-card p-6 sm:p-10 rounded-[1.5rem] sm:rounded-[2rem] flex flex-col items-center text-center">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-amber-50 flex items-center justify-center mb-6 sm:mb-8">
                        <i class="ph-fill ph-layout text-xl sm:text-2xl text-amber-600"></i>
                    </div>
                    <h3 class="text-base sm:text-lg font-black text-slate-900 tracking-tight mb-3 sm:mb-4">50+ Templates</h3>
                    <p class="text-xs text-slate-400 leading-relaxed font-medium">Unlock access to all ATS-friendly CV templates designed by recruitment experts.</p>
                </div>
            </div>

            {{-- ── Pricing & CTA ─────────────────────────── --}}
            <div class="pricing-section p-6 sm:p-12 lg:p-20 flex flex-col lg:flex-row items-center gap-10 lg:gap-16 text-white shadow-2xl rounded-[1.5rem] sm:rounded-[2.5rem]">
                <div class="lg:w-1/2 space-y-6 sm:space-y-8 w-full">
                    <div class="space-y-3 sm:space-y-4">
                        <h4 class="text-xs font-black text-primary-400 uppercase tracking-widest">Why Pro?</h4>
                        <h3 class="text-3xl sm:text-4xl font-black tracking-tight leading-tight">Pay Once. <br/> Access Forever.</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div class="flex items-center gap-3">
                            <div class="check-icon shrink-0"><i class="ph-bold ph-check"></i></div>
                            <span class="text-xs font-bold text-slate-200">AI Cover Letter Gen</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="check-icon shrink-0"><i class="ph-bold ph-check"></i></div>
                            <span class="text-xs font-bold text-slate-200">Bulk Import Tools</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="check-icon shrink-0"><i class="ph-bold ph-check"></i></div>
                            <span class="text-xs font-bold text-slate-200">Priority AI Queue</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="check-icon shrink-0"><i class="ph-bold ph-check"></i></div>
                            <span class="text-xs font-bold text-slate-200">Smart Job Alerts</span>
                        </div>
                    </div>
                </div>

                <div class="lg:w-1/2 w-full">
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 p-6 sm:p-10 rounded-[1.5rem] sm:rounded-[2rem] text-center space-y-6 sm:space-y-8">
                        <div class="space-y-2">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Special Offer</p>
                            <div class="flex items-center justify-center gap-3">
                                <span class="text-3xl sm:text-5xl font-black">Rp {{ number_format($premiumPrice, 0, ',', '.') }}</span>
                                <span class="text-slate-500 line-through text-xs sm:text-sm">Rp 150.000</span>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <a href="{{ route('payment.index') }}" class="block w-full py-4 sm:py-5 bg-white text-slate-900 rounded-xl font-black text-[10px] sm:text-xs uppercase tracking-[0.2em] shadow-xl hover:bg-slate-100 transition-all active:scale-95 text-center">
                                Claim Pro Access
                            </a>
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">No subscription. No hidden fees.</p>
                        </div>

                        <div class="flex items-center justify-center gap-6 pt-4 border-t border-white/5 opacity-50 grayscale">
                            <img src="{{ asset('images/qris.png') }}" class="h-4">
                            <div class="w-[1px] h-4 bg-white/20"></div>
                            <div class="flex items-center gap-2">
                                <i class="ph-fill ph-shield-check text-xl"></i>
                                <span class="text-[9px] font-black uppercase tracking-widest">Secure</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Footer Note ────────────────────────────── --}}
            <div class="mt-12 sm:mt-16 text-center px-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] leading-relaxed">
                    Join <span class="text-slate-900">1,200+ users</span> who upgraded their career strategy this week.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
