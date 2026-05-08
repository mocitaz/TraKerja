<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-2xl font-black text-slate-900 leading-tight tracking-tight">
                Cover Letter <span
                    class="bg-clip-text text-transparent bg-gradient-to-r from-[#d983e4] via-primary-600 to-[#4e71c5]">Generator</span>
            </h1>
            <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest mt-1">Design and tailor premium,
                high-converting job applications with AI</p>
        </div>
    </x-slot>

    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        .mesh-gradient-ai {
            background-color: #ffffff;
            background-image:
                radial-gradient(at 0% 0%, rgba(217, 131, 228, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(78, 113, 197, 0.05) 0px, transparent 50%);
        }

        .bento-step-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.8), 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        }

        .char-bar-fill {
            transition: width 0.3s ease;
        }

        /* Custom luxury option checkbox styles matching AI Analyzer premium aesthetics */
        .option-radio:checked+.option-card {
            border-color: rgb(165, 112, 240) !important;
            background-color: rgb(245, 243, 255) !important;
            box-shadow: 0 10px 25px -5px rgba(165, 112, 240, 0.1) !important;
        }

        .option-radio:checked+.option-card .icon-box {
            background-color: #6366f1 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(99, 102, 241, 0.2) !important;
        }

        .option-radio:checked+.option-card .radio-circle {
            border-color: #6366f1 !important;
            background-color: #6366f1 !important;
        }

        .option-radio:checked+.option-card i {
            color: #6366f1 !important;
        }

        /* Simulator Shimmer loading effect */
        @keyframes skeleton-shimmer {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }

        .shimmer-bar {
            background: linear-gradient(90deg, #e2e8f0 25%, #f1f5f9 50%, #e2e8f0 75%);
            background-size: 200% 100%;
            animation: skeleton-shimmer 1.5s infinite;
        }

        /* CSS Print Styling - Hides everything except the paper during print! */
        @media print {
            body * {
                visibility: hidden;
            }

            #printable-cover-letter,
            #printable-cover-letter * {
                visibility: visible;
            }

            #printable-cover-letter {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                font-size: 12pt;
                line-height: 1.6;
                background: white !important;
                color: black !important;
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }
        }
    </style>

    <div class="bg-[#f8fafc] min-h-screen pb-20">
        <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 pt-8">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                {{-- ── Left Form Container (7 Cols) ───────────────────── --}}
                <form action="{{ route('cover-letters.generate') }}" method="POST" id="coverLetterForm"
                    class="lg:col-span-7 space-y-6">
                    <div hidden>
                        @csrf
                    </div>

                    @if ($errors->any())
                        <div
                            class="flex items-start gap-4 bg-rose-50 border border-rose-200 rounded-[2rem] px-6 py-5 shadow-sm">
                            <i class="ph-bold ph-warning-circle text-rose-500 text-2xl shrink-0"></i>
                            <div class="flex-1">
                                <p class="text-sm font-black text-rose-700 leading-none mb-1.5">Review validation issues:
                                </p>
                                <ul class="list-disc list-inside text-xs text-rose-600 font-bold space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    {{-- ── Step 1: Application Target ─── --}}
                    <div
                        class="mesh-gradient-ai border border-slate-200/70 rounded-[2.5rem] shadow-sm overflow-hidden bento-step-card">
                        <div
                            class="px-6 sm:px-8 py-5 sm:pt-7 sm:pb-5 border-b border-slate-100 flex items-center gap-4">
                            <div
                                class="w-10 h-10 bg-primary-600 rounded-2xl flex items-center justify-center text-white font-black text-sm shadow-lg shadow-primary-100">
                                1</div>
                            <div>
                                <h3 class="text-base font-black text-slate-900 tracking-tight">Application Target</h3>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Where are
                                    you applying today?</p>
                            </div>
                        </div>

                        <div class="p-6 sm:p-8 space-y-5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                {{-- Company Input with Badge Icon Wrapper for absolute alignment --}}
                                <div class="group/field space-y-2">
                                    <label for="company_name"
                                        class="text-[10px] font-black text-slate-500 uppercase tracking-widest pl-1">Company
                                        / Organization</label>
                                    <div class="relative">
                                        <div
                                            class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 transition-all">
                                            <i class="ph-bold ph-buildings text-sm"></i>
                                        </div>
                                        <input type="text" id="company_name" name="company_name" required
                                            class="w-full pl-14 sm:pl-16 pr-5 py-4 bg-slate-50/50 border border-slate-200 rounded-[1.25rem] text-[15px] font-bold text-slate-700 focus:ring-4 focus:ring-primary-500/5 focus:border-primary-400 focus:bg-white transition-all outline-none"
                                            placeholder="e.g. Gojek, Shopee, PT. Teknalogi"
                                            value="{{ old('company_name') }}">
                                    </div>
                                </div>

                                {{-- Role Input with Badge Icon Wrapper for absolute alignment --}}
                                <div class="group/field space-y-2">
                                    <label for="job_title"
                                        class="text-[10px] font-black text-slate-500 uppercase tracking-widest pl-1">Target
                                        Role / Position</label>
                                    <div class="relative">
                                        <div
                                            class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 transition-all">
                                            <i class="ph-bold ph-briefcase text-sm"></i>
                                        </div>
                                        <input type="text" id="job_title" name="job_title" required
                                            class="w-full pl-14 sm:pl-16 pr-5 py-4 bg-slate-50/50 border border-slate-200 rounded-[1.25rem] text-[15px] font-bold text-slate-700 focus:ring-4 focus:ring-primary-500/5 focus:border-primary-400 focus:bg-white transition-all outline-none"
                                            placeholder="e.g. Backend Developer, UI/UX Designer"
                                            value="{{ old('job_title') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label for="job_description"
                                    class="text-[10px] font-black text-slate-500 uppercase tracking-widest pl-1">Job
                                    Description / Requirements</label>
                                <div class="relative">
                                    <textarea id="job_description" name="job_description" rows="7" required
                                        minlength="50"
                                        class="w-full px-6 pt-5 pb-12 bg-slate-50/50 border border-slate-200 rounded-[1.5rem] text-[15px] font-bold text-slate-700 focus:ring-4 focus:ring-primary-500/5 focus:border-primary-400 focus:bg-white transition-all outline-none resize-none leading-relaxed shadow-inner"
                                        placeholder="Paste the target job description or requirements here. AI will match your resume profile to extract key keywords..."></textarea>

                                    {{-- Char count progress bar matching AI Analyzer exactly --}}
                                    <div class="absolute bottom-4 left-6 right-6">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center gap-2">
                                                <div id="char-indicator" class="w-1.5 h-1.5 rounded-full bg-slate-300">
                                                </div>
                                                <span
                                                    class="text-[9px] font-black text-slate-400 uppercase tracking-widest"><span
                                                        id="char-count">0</span> chars</span>
                                            </div>
                                            <span id="char-hint"
                                                class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Min.
                                                50 characters</span>
                                        </div>
                                        <div class="h-1 bg-slate-100 rounded-full overflow-hidden shadow-inner">
                                            <div id="char-bar" class="char-bar-fill h-full rounded-full bg-slate-300"
                                                style="width:0%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Step 2: AI Generation Preferences ─── --}}
                    <div
                        class="mesh-gradient-ai border border-slate-200/70 rounded-[2.5rem] shadow-sm overflow-hidden bento-step-card">
                        <div
                            class="px-6 sm:px-8 py-5 sm:pt-7 sm:pb-5 border-b border-slate-100 flex items-center gap-4">
                            <div
                                class="w-10 h-10 bg-primary-600 rounded-2xl flex items-center justify-center text-white font-black text-sm shadow-lg shadow-primary-100">
                                2</div>
                            <div>
                                <h3 class="text-base font-black text-slate-900 tracking-tight">AI Tailoring Preferences
                                </h3>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Customize
                                    tone, language & structure</p>
                            </div>
                        </div>

                        <div class="p-6 sm:p-8 space-y-5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                {{-- Language Option --}}
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] pl-1">Language</label>
                                    <div class="grid grid-cols-2 gap-3">
                                        {{-- English --}}
                                        <label class="relative cursor-pointer">
                                            <input type="radio" name="language" value="en" class="sr-only option-radio"
                                                checked>
                                            <div
                                                class="option-card border border-slate-200 rounded-xl py-3 px-4 flex items-center justify-center gap-2.5 bg-slate-50/50 hover:bg-white transition-all text-xs font-black text-slate-700">
                                                <i class="ph-bold ph-globe text-base text-slate-400"></i>
                                                <span>English</span>
                                            </div>
                                        </label>
                                        {{-- Indonesia --}}
                                        <label class="relative cursor-pointer">
                                            <input type="radio" name="language" value="id" class="sr-only option-radio">
                                            <div
                                                class="option-card border border-slate-200 rounded-xl py-3 px-4 flex items-center justify-center gap-2.5 bg-slate-50/50 hover:bg-white transition-all text-xs font-black text-slate-700">
                                                <i class="ph-bold ph-translate text-base text-slate-400"></i>
                                                <span>Indonesia</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                {{-- Letter Length --}}
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] pl-1">Letter
                                        Length</label>
                                    <div class="grid grid-cols-2 gap-3">
                                        {{-- Standard --}}
                                        <label class="relative cursor-pointer">
                                            <input type="radio" name="length" value="standard"
                                                class="sr-only option-radio" checked>
                                            <div
                                                class="option-card border border-slate-200 rounded-xl py-3 px-4 flex items-center justify-center gap-2.5 bg-slate-50/50 hover:bg-white transition-all text-xs font-black text-slate-700">
                                                <i class="ph-bold ph-file-text text-base text-slate-400"></i>
                                                <span>Standard</span>
                                            </div>
                                        </label>
                                        {{-- Short --}}
                                        <label class="relative cursor-pointer">
                                            <input type="radio" name="length" value="short"
                                                class="sr-only option-radio">
                                            <div
                                                class="option-card border border-slate-200 rounded-xl py-3 px-4 flex items-center justify-center gap-2.5 bg-slate-50/50 hover:bg-white transition-all text-xs font-black text-slate-700">
                                                <i class="ph-bold ph-paper-plane text-base text-slate-400"></i>
                                                <span>Short Email</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Tone --}}
                            <div class="space-y-2">
                                <label
                                    class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] pl-1">Tone
                                    of Voice</label>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    {{-- Professional --}}
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="tone" value="professional"
                                            class="sr-only option-radio" checked>
                                        <div
                                            class="option-card border border-slate-200 rounded-xl py-3 px-3 flex items-center justify-center gap-2 bg-slate-50/50 hover:bg-white transition-all text-xs font-black text-slate-700">
                                            <i class="ph-bold ph-briefcase text-base text-slate-400"></i>
                                            <span>Professional</span>
                                        </div>
                                    </label>
                                    {{-- Creative --}}
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="tone" value="creative" class="sr-only option-radio">
                                        <div
                                            class="option-card border border-slate-200 rounded-xl py-3 px-3 flex items-center justify-center gap-2 bg-slate-50/50 hover:bg-white transition-all text-xs font-black text-slate-700">
                                            <i class="ph-bold ph-paint-brush text-base text-slate-400"></i>
                                            <span>Creative</span>
                                        </div>
                                    </label>
                                    {{-- Bold --}}
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="tone" value="bold" class="sr-only option-radio">
                                        <div
                                            class="option-card border border-slate-200 rounded-xl py-3 px-3 flex items-center justify-center gap-2 bg-slate-50/50 hover:bg-white transition-all text-xs font-black text-slate-700">
                                            <i class="ph-bold ph-trend-up text-base text-slate-400"></i>
                                            <span>Bold</span>
                                        </div>
                                    </label>
                                    {{-- Warm --}}
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="tone" value="warm" class="sr-only option-radio">
                                        <div
                                            class="option-card border border-slate-200 rounded-xl py-3 px-3 flex items-center justify-center gap-2 bg-slate-50/50 hover:bg-white transition-all text-xs font-black text-slate-700">
                                            <i class="ph-bold ph-chat-centered-text text-base text-slate-400"></i>
                                            <span>Warm</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            {{-- Story Highlight Focus --}}
                            <div class="space-y-2">
                                <div class="flex items-center justify-between pl-1">
                                    <label for="highlight_focus"
                                        class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Story
                                        Highlight Focus (Optional)</label>
                                    <span
                                        class="text-[8px] font-black text-primary-600 bg-primary-50 border border-primary-100/50 px-2 py-0.5 rounded-md uppercase tracking-wider">AI
                                        Option</span>
                                </div>
                                <textarea id="highlight_focus" name="highlight_focus" rows="2"
                                    class="w-full px-5 py-4 bg-slate-50/50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-700 placeholder-slate-400 focus:ring-4 focus:ring-primary-500/5 focus:border-primary-400 focus:bg-white transition-all outline-none resize-none leading-relaxed shadow-inner"
                                    placeholder="e.g. Focus on my 6-month React internship at Gojek, or emphasize my experience in scaling database traffic. This instructs AI to prioritize this content..."></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- ── Actions ─── --}}
                    <div class="flex items-center justify-between px-2 pt-2">
                        <a href="{{ route('tracker') }}"
                            class="flex items-center gap-2 text-xs sm:text-sm font-black text-slate-400 hover:text-slate-900 transition-colors uppercase tracking-[0.15em]">
                            <i class="ph ph-arrow-left text-base"></i> Back
                        </a>
                        <button type="submit" id="submit-btn" disabled
                            class="group flex items-center gap-2.5 sm:gap-3 px-6 sm:px-10 py-3.5 sm:py-4 bg-primary-600 text-white rounded-[1.25rem] font-black text-xs sm:text-sm hover:bg-primary-700 transition-all shadow-2xl shadow-primary-200 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                            <i id="loading-spinner" class="ph ph-spinner animate-spin hidden text-base sm:text-lg"></i>
                            <i id="submit-icon"
                                class="ph-bold ph-lightning text-base sm:text-lg group-hover:scale-125 transition-transform"></i>
                            <span id="submit-text" class="uppercase tracking-widest">Generate Cover Letter</span>
                        </button>
                    </div>
                </form>

                {{-- ── Sidebar & Live Simulator (5 Cols) ────────────────── --}}
                <div class="lg:col-span-5 space-y-6">

                    {{-- ── Cover Letter Credits Status Card ── --}}
                    <div
                        class="relative overflow-hidden rounded-[2.5rem] border {{ $isPremium ? 'border-purple-100 bg-gradient-to-br from-indigo-900 via-purple-950 to-slate-950 text-white' : 'border-slate-200 bg-gradient-to-br from-slate-900 via-slate-950 to-purple-950 text-white' }} p-8 shadow-2xl">
                        {{-- Ambient backdrop glows --}}
                        <div
                            class="absolute -right-12 -top-12 w-40 h-40 {{ $isPremium ? 'bg-purple-500/25' : 'bg-primary-500/10' }} rounded-full blur-3xl pointer-events-none">
                        </div>
                        <div
                            class="absolute -left-8 -bottom-8 w-32 h-32 {{ $isPremium ? 'bg-indigo-600/20' : 'bg-purple-500/10' }} rounded-full blur-2xl pointer-events-none">
                        </div>

                        <div class="relative z-10 flex flex-col justify-between h-full min-h-[180px]">
                            {{-- Card Header --}}
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-8 h-8 rounded-xl {{ $isPremium ? 'bg-purple-500/20 border border-purple-400/30' : 'bg-white/10 border border-white/10' }} flex items-center justify-center">
                                        <i
                                            class="ph-bold {{ $isPremium ? 'ph-crown text-amber-300' : 'ph-user text-slate-300' }} text-base"></i>
                                    </div>
                                    <div>
                                        <p class="text-[7.5px] font-black tracking-widest text-slate-400 uppercase">
                                            Membership</p>
                                        <p
                                            class="text-[10px] font-black {{ $isPremium ? 'text-purple-300' : 'text-slate-300' }} uppercase tracking-wider mt-0.5">
                                            {{ $isPremium ? 'Premium Plan' : 'Free Trial' }}
                                        </p>
                                    </div>
                                </div>
                                <span class="relative flex h-2 w-2">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $isPremium ? 'bg-emerald-400' : 'bg-amber-400' }} opacity-75"></span>
                                    <span
                                        class="relative inline-flex rounded-full h-2 w-2 {{ $isPremium ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                </span>
                            </div>

                            {{-- Credit Info --}}
                            <div class="my-6">
                                <p class="text-[8px] font-black tracking-widest text-slate-400 uppercase">Sisa Kredit
                                    Cover Letter</p>
                                <div class="flex items-baseline gap-1 mt-1.5">
                                    <span
                                        class="text-3xl font-black tracking-tight {{ $isPremium ? 'text-white' : 'text-slate-100' }}">
                                        {{ $remainingUses === 'unlimited' ? '∞' : $remainingUses }}
                                    </span>
                                    <span
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Kredit</span>
                                </div>
                                <p class="text-[10px] text-slate-400 font-bold mt-1.5 italic">
                                    {{ $isPremium ? 'Kredit aktif untuk generasi surat lamaran presisi tinggi.' : 'Trial gratis untuk 3x pembuatan surat lamaran.' }}
                                </p>
                            </div>

                            <div class="pt-4 border-t border-white/10">
                                @if($isPremium)
                                    <a href="{{ route('payment.topup', ['package' => 'cover_letter']) }}"
                                        class="group flex items-center justify-center gap-2 w-full py-3.5 bg-purple-600 hover:bg-purple-500 text-white rounded-xl font-black text-[9px] uppercase tracking-widest transition-all shadow-lg shadow-purple-950/40">
                                        <i
                                            class="ph-bold ph-plus-circle text-sm group-hover:scale-125 transition-transform"></i>
                                        Top Up +15 CL & +10 AI Kredit
                                    </a>
                                    <p
                                        class="text-[7px] text-center text-purple-300/80 font-black uppercase tracking-widest mt-2">
                                        Super Hemat! Hanya Rp 14.999 saja</p>
                                @else
                                    <a href="{{ route('payment.premium') }}"
                                        class="group flex items-center justify-center gap-2 w-full py-3.5 bg-gradient-to-r from-primary-500 to-indigo-600 hover:from-primary-600 hover:to-indigo-700 text-white rounded-xl font-black text-[9px] uppercase tracking-widest transition-all shadow-lg shadow-primary-950/40">
                                        <i class="ph-bold ph-lightning text-sm group-hover:animate-bounce"></i>
                                        Upgrade ke Premium
                                    </a>
                                    <p
                                        class="text-[7px] text-center text-primary-300/80 font-black uppercase tracking-widest mt-2">
                                        Dapatkan Bonus 15 Kredit & Unlimited PDF Export!</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Dynamic Live Simulator (High Highlighted Bento Card) --}}
                    <div
                        class="bg-gradient-to-br from-indigo-50/70 via-purple-50/40 to-white/90 border-2 border-indigo-200/90 rounded-[2.5rem] p-6 sm:p-7 shadow-[0_20px_50px_rgba(99,102,241,0.06)] relative overflow-hidden bento-step-card">

                        {{-- Ambient pulsing spot glow inside simulator --}}
                        <div
                            class="absolute -right-20 -top-20 w-52 h-52 bg-primary-400/10 rounded-full blur-3xl animate-pulse">
                        </div>
                        <div class="absolute -left-20 -bottom-20 w-44 h-44 bg-[#d983e4]/10 rounded-full blur-2xl"></div>

                        {{-- Header Pill Navigation --}}
                        <div class="relative z-10 flex items-center justify-between mb-5">
                            <div class="flex items-center gap-2">
                                <span class="relative flex h-2.5 w-2.5">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                                </span>
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Live
                                    Simulator</p>
                            </div>
                            <span
                                class="text-[9px] font-black text-primary-600 bg-primary-50 border border-primary-100 px-2.5 py-1 rounded-full uppercase tracking-wider flex items-center gap-1.5">
                                <i class="ph-bold ph-sparkle text-xs animate-spin-slow"></i> AI Live Engine
                            </span>
                        </div>

                        {{-- Template Picker / Switcher --}}
                        <div
                            class="relative z-10 bg-slate-100/80 backdrop-blur border border-slate-200/40 p-1 rounded-xl mb-4 flex items-center justify-between text-[9px] font-black uppercase tracking-widest shadow-inner">
                            <button onclick="switchTemplate('modern')" id="tpl-btn-modern"
                                class="flex-1 py-2 rounded-lg transition-all text-slate-800 bg-white shadow-sm font-black">Modern
                                Bar</button>
                            <button onclick="switchTemplate('minimal')" id="tpl-btn-minimal"
                                class="flex-1 py-2 rounded-lg transition-all text-slate-400 hover:text-slate-800 font-bold">Classic
                                Serif</button>
                            <button onclick="switchTemplate('tech')" id="tpl-btn-tech"
                                class="flex-1 py-2 rounded-lg transition-all text-slate-400 hover:text-slate-800 font-bold">Tech
                                Minimal</button>
                        </div>

                        {{-- Printable/Simulated Letter Canvas (morphs styling based on Template Picker) --}}
                        <div id="printable-cover-letter"
                            class="relative z-10 bg-white border border-slate-200/80 rounded-[1.75rem] p-6 text-slate-700 text-[10px] font-bold leading-relaxed min-h-[440px] shadow-sm transition-all duration-500 font-sans"
                            style="font-size: 9.5px;">

                            {{-- Target Modern Left Border Strip --}}
                            <div id="tpl-accent-bar"
                                class="absolute left-0 top-6 bottom-6 w-1 bg-primary-500 rounded-r-full transition-all">
                            </div>

                            {{-- Header Section --}}
                            <div class="border-b border-slate-100 pb-3.5 mb-4">
                                <p id="preview-user-name"
                                    class="font-black text-[12.5px] text-slate-900 tracking-tight leading-none transition-all">
                                    {{ Auth::user()->name }}
                                </p>
                                <p id="preview-user-contact"
                                    class="text-slate-400 font-black mt-1.5 uppercase tracking-wider text-[7.5px]">
                                    {{ Auth::user()->email }} |
                                    {{ Auth::user()->profile?->phone_number ?? '0812-xxxx-xxxx' }}
                                </p>
                            </div>

                            {{-- Address & Date Block --}}
                            <div class="space-y-1 mb-4 text-slate-500">
                                <p class="font-black text-slate-400 text-[8px] uppercase tracking-wider">Jakarta,
                                    {{ now()->locale('id')->translatedFormat('d F Y') }}
                                </p>
                                <p class="font-black text-slate-800 mt-2">Hiring Committee</p>
                                <p id="preview-company-name"
                                    class="font-black text-primary-600 bg-primary-50 px-2.5 py-1 rounded-md inline-block text-[9px] transition-all">
                                    Recipient Company</p>
                            </div>

                            {{-- Subject line --}}
                            <div class="mb-4">
                                <p class="font-black text-slate-800">
                                    Subject: <span id="preview-job-title"
                                        class="font-black text-slate-900 bg-slate-100 px-2 py-1 rounded-md text-[9px] transition-all">Target
                                        Job Position</span>
                                </p>
                            </div>

                            {{-- Dynamic Shimmer/Skeleton Block (Initially shown) --}}
                            <div id="preview-skeleton-content" class="space-y-3.5">
                                <p class="font-black text-slate-800">Dear Hiring Committee,</p>
                                <div class="space-y-2 opacity-80">
                                    <div class="h-2.5 shimmer-bar rounded-full w-full"></div>
                                    <div class="h-2.5 shimmer-bar rounded-full w-11/12"></div>
                                    <div class="h-2.5 shimmer-bar rounded-full w-5/6"></div>
                                </div>
                                <div
                                    class="border-l-2 border-primary-500 pl-3 py-1.5 bg-slate-50/50 rounded-r-lg space-y-2">
                                    <div class="flex items-center gap-1.5 text-primary-600">
                                        <i class="ph-bold ph-database text-xs animate-pulse"></i>
                                        <span class="uppercase text-[8px] font-black tracking-widest">Matched Resume
                                            Context</span>
                                    </div>
                                    <div class="h-2 shimmer-bar rounded-full w-11/12"></div>
                                    <div class="h-2 shimmer-bar rounded-full w-4/5"></div>
                                </div>
                                <div class="space-y-2 opacity-80">
                                    <div class="h-2.5 shimmer-bar rounded-full w-full"></div>
                                    <div class="h-2.5 shimmer-bar rounded-full w-4/5"></div>
                                </div>
                                <div class="pt-4">
                                    <p class="font-black text-slate-800">Sincerely,</p>
                                    <p class="font-black text-slate-900 mt-2">{{ Auth::user()->name }}</p>
                                </div>
                            </div>

                            {{-- Real Generated Cover Letter Text (Shown on Success) --}}
                            <div id="preview-real-content" class="hidden">
                                <div id="generated-text-content"
                                    class="text-[9.5px] font-bold text-slate-800 whitespace-pre-line leading-relaxed selection:bg-primary-100 selection:text-primary-900 transition-all">
                                </div>
                            </div>
                        </div>

                        {{-- Action Controls at bottom of paper --}}
                        <div id="generated-actions"
                            class="hidden mt-4 pt-4 border-t border-slate-100/80 flex items-center justify-end gap-2 relative z-10">
                            <button id="copy-btn" onclick="copyGeneratedLetter()"
                                class="flex items-center gap-1.5 px-4 py-2.5 bg-slate-900 text-white hover:bg-primary-600 rounded-xl text-[9px] font-black uppercase tracking-wider transition-all shadow-md active:scale-95">
                                <i id="copy-icon" class="ph-bold ph-copy text-sm"></i> <span id="copy-text">Copy
                                    Text</span>
                            </button>
                        </div>

                        {{-- Active Options Indicator --}}
                        <div
                            class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4 relative z-10">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 bg-indigo-50 border border-indigo-100 rounded-xl flex items-center justify-center text-indigo-500 shrink-0">
                                    <i class="ph-bold ph-sparkle text-sm"></i>
                                </div>
                                <div class="text-left">
                                    <p
                                        class="text-[8px] font-black text-slate-400 uppercase tracking-wider leading-none">
                                        Tone Target</p>
                                    <p id="preview-active-tone"
                                        class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mt-1">
                                        Professional</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 bg-purple-50 border border-purple-100 rounded-xl flex items-center justify-center text-purple-500 shrink-0">
                                    <i class="ph-bold ph-translate text-sm"></i>
                                </div>
                                <div class="text-left">
                                    <p
                                        class="text-[8px] font-black text-slate-400 uppercase tracking-wider leading-none">
                                        Language</p>
                                    <p id="preview-active-lang"
                                        class="text-[10px] font-black text-purple-600 uppercase tracking-widest mt-1">
                                        English</p>
                                </div>
                            </div>
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>

    <script>
        const companyInput = document.getElementById('company_name');
        const roleInput = document.getElementById('job_title');
        const descInput = document.getElementById('job_description');

        // Preview targets
        const previewCompany = document.getElementById('preview-company-name');
        const previewRole = document.getElementById('preview-job-title');

        // Counter Targets
        const charCount = document.getElementById('char-count');
        const charIndicator = document.getElementById('char-indicator');
        const charBar = document.getElementById('char-bar');
        const charHint = document.getElementById('char-hint');
        const submitBtn = document.getElementById('submit-btn');

        // Dynamic results targets
        const skeletonContent = document.getElementById('preview-skeleton-content');
        const realContent = document.getElementById('preview-real-content');
        const textContent = document.getElementById('generated-text-content');
        const generatedActions = document.getElementById('generated-actions');

        // Text input event synchronization
        companyInput.addEventListener('input', function () {
            previewCompany.textContent = this.value.trim() || 'Recipient Company';
        });

        roleInput.addEventListener('input', function () {
            previewRole.textContent = this.value.trim() || 'Target Job Position';
        });

        descInput.addEventListener('input', function () {
            const len = this.value.length;
            charCount.textContent = len;
            const pct = Math.min((len / 2500) * 100, 100);
            charBar.style.width = pct + '%';

            if (len >= 50) {
                charIndicator.className = 'w-1.5 h-1.5 rounded-full bg-emerald-400';
                charBar.className = 'char-bar-fill h-full rounded-full bg-emerald-400';
                charHint.textContent = 'Looking great!';
                charHint.className = 'text-[9px] font-black text-emerald-500 uppercase tracking-widest';
                submitBtn.disabled = false;
            } else {
                charIndicator.className = 'w-1.5 h-1.5 rounded-full bg-rose-400';
                charBar.className = 'char-bar-fill h-full rounded-full bg-rose-400';
                charHint.textContent = 'Min. 50 characters';
                charHint.className = 'text-[9px] font-black text-rose-500 uppercase tracking-widest';
                submitBtn.disabled = true;
            }
        });

        // Interactive Options Sync UI
        const toneRadios = document.querySelectorAll('input[name="tone"]');
        const langRadios = document.querySelectorAll('input[name="language"]');

        const previewActiveTone = document.getElementById('preview-active-tone');
        const previewActiveLang = document.getElementById('preview-active-lang');

        toneRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                if (this.checked) {
                    const value = this.value;
                    previewActiveTone.textContent = value.charAt(0).toUpperCase() + value.slice(1);

                    // Match visual simulator borders based on tone
                    const paper = document.getElementById('printable-cover-letter');
                    if (value === 'creative') {
                        previewActiveTone.className = "text-[10px] font-black text-purple-600 uppercase tracking-widest mt-1";
                        paper.style.borderColor = 'rgb(216, 180, 254)';
                    } else if (value === 'bold') {
                        previewActiveTone.className = "text-[10px] font-black text-emerald-600 uppercase tracking-widest mt-1";
                        paper.style.borderColor = 'rgb(110, 231, 183)';
                    } else if (value === 'warm') {
                        previewActiveTone.className = "text-[10px] font-black text-amber-600 uppercase tracking-widest mt-1";
                        paper.style.borderColor = 'rgb(252, 211, 77)';
                    } else {
                        previewActiveTone.className = "text-[10px] font-black text-indigo-600 uppercase tracking-widest mt-1";
                        paper.style.borderColor = 'rgb(226, 232, 240)';
                    }
                }
            });
        });

        langRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                if (this.checked) {
                    previewActiveLang.textContent = this.value === 'id' ? 'Indonesia' : 'English';
                }
            });
        });

        // Template Switcher Morphing System
        function switchTemplate(tpl) {
            const paper = document.getElementById('printable-cover-letter');
            const accentBar = document.getElementById('tpl-accent-bar');

            // Buttons list
            const btnModern = document.getElementById('tpl-btn-modern');
            const btnMinimal = document.getElementById('tpl-btn-minimal');
            const btnTech = document.getElementById('tpl-btn-tech');

            // Reset active button classes
            [btnModern, btnMinimal, btnTech].forEach(btn => {
                btn.className = "flex-1 py-2 rounded-lg transition-all text-slate-400 hover:text-slate-800 font-bold";
            });

            if (tpl === 'modern') {
                btnModern.className = "flex-1 py-2 rounded-lg transition-all text-slate-800 bg-white shadow-sm font-black";

                // Morph styling
                paper.className = "relative z-10 bg-white border border-slate-200/80 rounded-[1.75rem] p-6 text-slate-700 text-[10px] font-bold leading-relaxed min-h-[440px] shadow-sm transition-all duration-500 font-sans";
                accentBar.className = "absolute left-0 top-6 bottom-6 w-1 bg-primary-500 rounded-r-full transition-all";
                accentBar.style.display = "block";

            } else if (tpl === 'minimal') {
                btnMinimal.className = "flex-1 py-2 rounded-lg transition-all text-slate-800 bg-white shadow-sm font-black";

                // Morph styling to luxury Ivory Serif
                paper.className = "relative z-10 bg-[#fdfbf7] border border-amber-100/80 rounded-[1.75rem] p-6 text-amber-950 text-[10.5px] font-medium leading-relaxed min-h-[440px] shadow-sm transition-all duration-500 font-serif";
                accentBar.style.display = "none";

            } else if (tpl === 'tech') {
                btnTech.className = "flex-1 py-2 rounded-lg transition-all text-slate-800 bg-white shadow-sm font-black";

                // Morph styling to Technical monospace minimal
                paper.className = "relative z-10 bg-slate-900 border border-slate-800 rounded-[1.75rem] p-6 text-slate-300 text-[9px] leading-relaxed min-h-[440px] shadow-lg transition-all duration-500 font-mono";
                accentBar.className = "absolute left-0 top-6 bottom-6 w-1.5 bg-[#4e71c5] transition-all";
                accentBar.style.display = "block";
            }
        }

        // Copy generated letter to clipboard
        function copyGeneratedLetter() {
            const textToCopy = textContent.textContent;
            navigator.clipboard.writeText(textToCopy).then(() => {
                const btn = document.getElementById('copy-btn');
                const btnText = document.getElementById('copy-text');
                const btnIcon = document.getElementById('copy-icon');

                // Success morph animation
                btnText.textContent = "Copied!";
                btnIcon.className = "ph-bold ph-check-square text-sm";
                btn.classList.replace('bg-slate-900', 'bg-emerald-600');

                setTimeout(() => {
                    btnText.textContent = "Copy Text";
                    btnIcon.className = "ph-bold ph-copy text-sm";
                    btn.classList.replace('bg-emerald-600', 'bg-slate-900');
                }, 2000);

                if (typeof window.showToast === 'function') {
                    window.showToast('success', 'Copied', 'Cover Letter successfully copied to clipboard!');
                }
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        }

        // Real AJAX Form Submission to Laravel Backend with typing transition
        document.getElementById('coverLetterForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const btn = document.getElementById('submit-btn');
            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-not-allowed');
            document.getElementById('submit-text').textContent = 'Generating Cover Letter…';
            document.getElementById('submit-icon').classList.add('hidden');
            document.getElementById('loading-spinner').classList.remove('hidden');

            // Hide previous results
            realContent.classList.add('hidden');
            generatedActions.classList.add('hidden');
            skeletonContent.classList.remove('hidden');

            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    btn.disabled = false;
                    btn.classList.remove('opacity-70', 'cursor-not-allowed');
                    document.getElementById('submit-text').textContent = 'Generate Cover Letter';
                    document.getElementById('submit-icon').classList.remove('hidden');
                    document.getElementById('loading-spinner').classList.add('hidden');

                    if (data.success && data.cover_letter) {
                        // Inject and display real cover letter with dynamic fades
                        textContent.textContent = data.cover_letter;
                        skeletonContent.classList.add('hidden');
                        realContent.classList.remove('hidden');
                        generatedActions.classList.remove('hidden');

                        if (typeof window.showToast === 'function') {
                            window.showToast('success', 'Letter Tailored', 'Cover Letter successfully drafted with AI!');
                        }
                    } else {
                        alert('Error: Gagal memproses data AI.');
                    }
                })
                .catch(error => {
                    console.error('Error generating cover letter:', error);
                    btn.disabled = false;
                    btn.classList.remove('opacity-70', 'cursor-not-allowed');
                    document.getElementById('submit-text').textContent = 'Generate Cover Letter';
                    document.getElementById('submit-icon').classList.remove('hidden');
                    document.getElementById('loading-spinner').classList.add('hidden');
                    alert('Gagal menghubungi AI model. Mohon coba beberapa saat lagi.');
                });
        });
    </script>
</x-app-layout>