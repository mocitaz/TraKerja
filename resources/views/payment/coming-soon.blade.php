<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-2xl font-black text-slate-900 leading-tight tracking-tight">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#d983e4] via-purple-600 to-[#4e71c5]">Premium</span> Upgrade
            </h1>
            <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest mt-1">Unlock your full potential</p>
        </div>
    </x-slot>

    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        @keyframes fillBar { from { width:0 } to { width:65% } }
        @keyframes fadeUp  { from { opacity:0;transform:translateY(12px) } to { opacity:1;transform:translateY(0) } }
        @keyframes glow    { 0%,100% { opacity:.6 } 50% { opacity:1 } }
        .bar-fill   { animation: fillBar 1.4s .5s cubic-bezier(.4,0,.2,1) both; }
        .a1 { animation: fadeUp .45s .05s both; }
        .a2 { animation: fadeUp .45s .12s both; }
        .a3 { animation: fadeUp .45s .20s both; }
        .glow-dot   { animation: glow 2s ease-in-out infinite; }
    </style>

    <div class="bg-[#f8fafc] min-h-screen pb-20">
        <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 pt-8 space-y-6">

            {{-- ─── TOP HERO: DARK INDIGO (matches Pro Tip style) ──────────────── --}}
            <div class="a1 bg-indigo-900 rounded-3xl p-8 relative overflow-hidden shadow-xl shadow-indigo-100">
                {{-- Decorative blobs --}}
                <div class="absolute -right-16 -top-16 w-56 h-56 bg-violet-500/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -left-16 -bottom-16 w-56 h-56 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute right-0 bottom-0 w-80 h-80 bg-[#d983e4]/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col lg:flex-row items-start lg:items-center gap-8">
                    {{-- Icon --}}
                    <div class="w-20 h-20 bg-white/10 backdrop-blur-xl rounded-[2rem] flex items-center justify-center shrink-0 border border-white/20 shadow-inner">
                        <i class="ph-bold ph-crown text-amber-300 text-4xl"></i>
                    </div>

                    {{-- Text --}}
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="glow-dot w-2 h-2 bg-amber-400 rounded-full"></span>
                            <span class="text-[10px] font-black uppercase tracking-[0.18em] text-amber-300">Coming Soon</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight leading-tight mb-2">
                            TraKerja Premium is<br class="hidden sm:block"> almost here.
                        </h2>
                        <p class="text-indigo-200 font-medium leading-relaxed max-w-xl text-sm">
                            We're building a world-class upgrade experience — secure payments, flexible plans, and everything you need to accelerate your career. Be the first to know when we launch.
                        </p>

                        {{-- Progress --}}
                        <div class="mt-5 max-w-sm">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-[10px] font-black text-indigo-300 uppercase tracking-widest">Development Progress</span>
                                <span class="text-[10px] font-black text-amber-400">65% Complete</span>
                            </div>
                            <div class="h-2 bg-white/10 rounded-full overflow-hidden">
                                <div class="bar-fill h-full rounded-full bg-gradient-to-r from-[#d983e4] via-purple-400 to-[#4e71c5]"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Right: stats + CTA --}}
                    <div class="shrink-0 flex flex-col items-start lg:items-end gap-4 w-full lg:w-auto">
                        <div class="flex gap-6 lg:gap-4">
                            <div class="text-center">
                                <div class="text-2xl font-black text-white">Q2</div>
                                <div class="text-[9px] font-black text-indigo-400 uppercase tracking-widest">Target</div>
                            </div>
                            <div class="w-px bg-white/10 self-stretch"></div>
                            <div class="text-center">
                                <div class="text-2xl font-black text-white">3</div>
                                <div class="text-[9px] font-black text-indigo-400 uppercase tracking-widest">Phases</div>
                            </div>
                            <div class="w-px bg-white/10 self-stretch"></div>
                            <div class="text-center">
                                <div class="text-2xl font-black text-white">6+</div>
                                <div class="text-[9px] font-black text-indigo-400 uppercase tracking-widest">Features</div>
                            </div>
                        </div>
                        <a href="{{ route('tracker') }}"
                           class="group flex items-center gap-2 px-6 py-3 bg-white text-indigo-900 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-indigo-50 transition-all active:scale-95 shadow-lg shadow-indigo-900/30">
                            <i class="ph-bold ph-arrow-left text-sm group-hover:-translate-x-0.5 transition-transform"></i>
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>

            {{-- ─── FEATURES + TIMELINE (2 col) ─────────────────────────────── --}}
            <div class="a2 grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Features 2/3 --}}
                <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200/60 p-6 sm:p-8 shadow-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-indigo-50 rounded-2xl flex items-center justify-center">
                            <i class="ph-duotone ph-sparkle text-indigo-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-900 tracking-tight">What's Included</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Premium feature set</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @php
                        $features = [
                            ['ph-shield-check',      'text-emerald-600', 'bg-emerald-50',  'Secure Gateway',        'Bank-level end-to-end encryption'],
                            ['ph-credit-card',       'text-blue-600',    'bg-blue-50',     'Multi Payment',         'Cards, transfers & e-wallets'],
                            ['ph-clock-clockwise',   'text-violet-600',  'bg-violet-50',   'Transaction History',   'Full record of all payments'],
                            ['ph-receipt',           'text-amber-600',   'bg-amber-50',    'Auto Invoicing',        'Instant invoice generation'],
                            ['ph-arrows-clockwise',  'text-rose-600',    'bg-rose-50',     'Flexible Plans',        'Upgrade & downgrade anytime'],
                            ['ph-lightning',         'text-indigo-600',  'bg-indigo-50',   'Early Feature Access',  'First to get every new feature'],
                        ];
                        @endphp

                        @foreach($features as [$icon, $color, $bg, $title, $desc])
                        <div class="group flex items-center gap-3 p-3.5 rounded-2xl border border-slate-100 hover:border-indigo-200 hover:bg-indigo-50/30 transition-all duration-200 cursor-default">
                            <div class="{{ $bg }} w-9 h-9 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform duration-200">
                                <i class="ph-bold {{ $icon }} {{ $color }} text-base"></i>
                            </div>
                            <div>
                                <div class="text-xs font-black text-slate-900">{{ $title }}</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">{{ $desc }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Timeline 1/3 --}}
                <div class="bg-white rounded-3xl border border-slate-200/60 p-6 sm:p-8 shadow-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-purple-50 rounded-2xl flex items-center justify-center">
                            <i class="ph-duotone ph-map-trifold text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-900 tracking-tight">Roadmap</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">3 phases total</p>
                        </div>
                    </div>

                    <div class="relative pl-8 space-y-6">
                        {{-- Vertical connector --}}
                        <div class="absolute left-[14px] top-1 bottom-1 w-0.5 bg-gradient-to-b from-emerald-400 via-violet-400 to-slate-200 rounded-full"></div>

                        {{-- Phase 1 --}}
                        <div class="relative">
                            <div class="absolute -left-8 top-0.5 w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center shadow-md shadow-emerald-200">
                                <i class="ph-bold ph-check text-white text-[10px]"></i>
                            </div>
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <div class="text-xs font-black text-slate-900">Planning & Design</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5 leading-snug">UI/UX design and system architecture</div>
                                </div>
                                <span class="shrink-0 px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-lg text-[9px] font-black uppercase tracking-wide">Done</span>
                            </div>
                        </div>

                        {{-- Phase 2 --}}
                        <div class="relative">
                            <div class="absolute -left-8 top-0.5 w-6 h-6 bg-violet-500 rounded-full flex items-center justify-center shadow-md shadow-violet-200 animate-pulse">
                                <i class="ph-bold ph-code text-white text-[10px]"></i>
                            </div>
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <div class="text-xs font-black text-slate-900">Development</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5 leading-snug">Secure payment infrastructure</div>
                                </div>
                                <span class="shrink-0 px-2 py-0.5 bg-violet-100 text-violet-700 rounded-lg text-[9px] font-black uppercase tracking-wide">Active</span>
                            </div>
                        </div>

                        {{-- Phase 3 --}}
                        <div class="relative opacity-40">
                            <div class="absolute -left-8 top-0.5 w-6 h-6 bg-slate-200 rounded-full flex items-center justify-center">
                                <i class="ph-bold ph-lock text-slate-400 text-[10px]"></i>
                            </div>
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <div class="text-xs font-black text-slate-900">Testing & Launch</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5 leading-snug">Security audits & beta release</div>
                                </div>
                                <span class="shrink-0 px-2 py-0.5 bg-slate-100 text-slate-500 rounded-lg text-[9px] font-black uppercase tracking-wide">Soon</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─── BOTTOM STRIP (matches cv-builder Pro Tip) ──────────────── --}}
            <div class="a3 bg-indigo-900 rounded-3xl p-8 text-white relative overflow-hidden shadow-xl shadow-indigo-100">
                <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -left-20 -top-20 w-48 h-48 bg-violet-500/20 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
                    <div class="w-16 h-16 bg-white/10 backdrop-blur-xl rounded-[2rem] flex items-center justify-center shrink-0 border border-white/20">
                        <i class="ph-duotone ph-envelope-simple text-3xl text-white"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-black tracking-tight mb-1">Stay in the loop</h4>
                        <p class="text-indigo-100 font-medium leading-relaxed max-w-2xl text-sm">
                            You're already registered on TraKerja. We'll send a notification to your email <span class="font-black text-white">{{ auth()->user()->email }}</span> the moment Premium goes live. No action needed.
                        </p>
                    </div>
                    <div class="md:ml-auto shrink-0">
                        <div class="flex items-center gap-2 px-5 py-3 bg-white/10 border border-white/20 backdrop-blur-sm rounded-2xl text-white text-[10px] font-black uppercase tracking-widest">
                            <i class="ph-bold ph-check-circle text-emerald-400 text-sm"></i>
                            You're on the list
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
