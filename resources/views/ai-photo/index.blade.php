<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-2xl font-black text-slate-900 leading-tight tracking-tight">
                AI <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#d983e4] via-primary-600 to-[#4e71c5]">Photo Studio</span>
            </h1>
            <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest mt-1">Enhance and generate professional profile photos with AI</p>
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

        @keyframes scanLine {
            0%   { transform: translateY(-100%); opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: 1; }
            100% { transform: translateY(400%); opacity: 0; }
        }
        .drag-active {
            border-color: rgb(165,112,240) !important;
            background-color: rgb(245,243,255) !important;
        }
    </style>

    <div class="bg-[#f8fafc] min-h-screen pb-20">
        <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 pt-8">

            <div class="flex items-center justify-end mb-4">
                <a href="{{ route('ai-photo.history') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl font-bold text-[10px] text-slate-600 uppercase tracking-widest shadow-sm hover:bg-slate-50 hover:text-slate-900 transition-all">
                    <i class="ph-bold ph-clock-counter-clockwise text-sm"></i>
                    History
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                {{-- ── Left Form Container (7 Cols) ───────────────────── --}}
                <div class="lg:col-span-7 space-y-6">
                    
                    @if (session('error'))
                    <div class="flex items-start gap-4 bg-rose-50 border border-rose-200 rounded-[2rem] px-6 py-5 shadow-sm">
                        <i class="ph-bold ph-warning-circle text-rose-500 text-2xl shrink-0"></i>
                        <p class="text-sm font-bold text-rose-700 leading-relaxed">{{ session('error') }}</p>
                    </div>
                    @endif

                    <form action="{{ route('ai-photo.process') }}" method="POST" enctype="multipart/form-data" id="photoForm" x-data="{ type: 'remove_bg', mode: 'portrait' }">
                        @csrf

                        {{-- ── Step 1: Upload ─── --}}
                        <div class="mesh-gradient-ai border border-slate-200/70 rounded-[2.5rem] shadow-sm overflow-hidden mb-6 bento-step-card">
                            <div class="px-6 sm:px-8 py-5 sm:pt-7 sm:pb-5 border-b border-slate-100 flex items-center gap-4">
                                <div class="w-10 h-10 bg-primary-600 rounded-2xl flex items-center justify-center text-white font-black text-sm shadow-lg shadow-primary-100">1</div>
                                <div>
                                    <h3 class="text-base font-black text-slate-900 tracking-tight">Upload Photo</h3>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Face recognition enabled</p>
                                </div>
                            </div>

                            <div class="p-5 sm:p-8">
                                {{-- Drop Zone --}}
                                <div id="upload-area"
                                     class="relative group border-2 border-dashed border-slate-200 rounded-[2rem] p-6 sm:p-12 text-center cursor-pointer transition-all duration-300 hover:border-primary-400 hover:bg-white hover:shadow-xl hover:shadow-primary-50/50 overflow-hidden"
                                     ondragover="handleDragOver(event)"
                                     ondragleave="handleDragLeave(event)"
                                     ondrop="handleDrop(event)">
                                    <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp"
                                           class="absolute inset-0 opacity-0 cursor-pointer z-10 w-full h-full" required>
                                    <div class="relative z-10 flex flex-col items-center gap-4">
                                        <div class="w-16 h-16 bg-slate-50 border border-slate-200 rounded-3xl flex items-center justify-center group-hover:bg-primary-50 group-hover:border-primary-200 transition-all shadow-sm">
                                            <i class="ph-bold ph-image text-slate-400 group-hover:text-primary-500 text-3xl transition-colors"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm sm:text-base font-black text-slate-800">Drop your photo here or <span class="text-primary-600 underline decoration-2 underline-offset-4">browse</span></p>
                                            <p class="text-[10px] sm:text-xs text-slate-400 font-bold mt-1.5 uppercase tracking-widest">JPG, PNG, WEBP · Max 20 MB</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- File selected state --}}
                                <div id="file-success" class="hidden mt-4">
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white border border-emerald-200 rounded-[1.5rem] p-5 sm:px-6 sm:py-5 shadow-lg shadow-emerald-50/50">
                                        <div class="flex items-center gap-4 w-full sm:w-auto min-w-0">
                                            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center justify-center shrink-0 overflow-hidden relative shadow-inner">
                                                <img id="image-preview" src="#" alt="Preview" class="absolute inset-0 w-full h-full object-cover">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p id="file-name" class="text-sm sm:text-[15px] font-black text-slate-900 truncate tracking-tight"></p>
                                                <p id="file-size" class="text-[10px] sm:text-[11px] text-slate-500 font-black mt-1 uppercase tracking-tighter"></p>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between sm:justify-end gap-3 w-full sm:w-auto border-t border-slate-100 sm:border-t-0 pt-3 sm:pt-0">
                                            <span class="text-[9px] sm:text-[10px] font-black text-emerald-600 bg-emerald-50 border border-emerald-100 px-3 py-1.5 rounded-full uppercase tracking-widest">Ready</span>
                                            <button type="button" id="remove-file" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all border border-transparent hover:border-rose-100 shrink-0">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── Step 2: Service Selection ─── --}}
                        <div class="mesh-gradient-ai border border-slate-200/70 rounded-[2.5rem] shadow-sm overflow-hidden mb-6 bento-step-card">
                            <div class="px-6 sm:px-8 py-5 sm:pt-7 sm:pb-5 border-b border-slate-100 flex items-center gap-4">
                                <div class="w-10 h-10 bg-primary-600 rounded-2xl flex items-center justify-center text-white font-black text-sm shadow-lg shadow-primary-100">2</div>
                                <div>
                                    <h3 class="text-base font-black text-slate-900 tracking-tight">Select Service</h3>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">What do you want to do?</p>
                                </div>
                            </div>

                            <div class="p-5 sm:p-6 space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    {{-- Remove BG Option --}}
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="type" value="remove_bg" x-model="type" class="sr-only">
                                        <div class="border rounded-[1.25rem] p-4 transition-all duration-300"
                                             :class="type === 'remove_bg' ? 'border-[#a570f0] bg-[#f5f3ff] shadow-[0_10px_25px_-5px_rgba(165,112,240,0.15)]' : 'border-slate-200 bg-white hover:border-[#a570f0]/30'">
                                            <div class="flex gap-3.5 items-center">
                                                <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-300 shrink-0"
                                                     :class="type === 'remove_bg' ? 'bg-[#6366f1] text-white shadow-[0_4px_10px_rgba(99,102,241,0.3)]' : 'bg-slate-50 text-slate-400 group-hover:bg-[#f5f3ff] group-hover:text-[#6366f1]'">
                                                    <i class="ph-bold ph-eraser text-[20px]"></i>
                                                </div>
                                                <div>
                                                    <p class="text-[14px] font-black tracking-tight" :class="type === 'remove_bg' ? 'text-slate-900' : 'text-slate-900'">Remove Background</p>
                                                    <p class="text-[10px] font-bold mt-0.5 leading-snug" :class="type === 'remove_bg' ? 'text-[#6366f1]' : 'text-slate-500'">Keeps original clothes. For Pas Foto.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                    
                                    {{-- AI Enhance Option --}}
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="type" value="enhance" x-model="type" class="sr-only">
                                        <div class="border rounded-[1.25rem] p-4 transition-all duration-300"
                                             :class="type === 'enhance' ? 'border-[#a570f0] bg-[#f5f3ff] shadow-[0_10px_25px_-5px_rgba(165,112,240,0.15)]' : 'border-slate-200 bg-white hover:border-[#a570f0]/30'">
                                            <div class="flex gap-3.5 items-center">
                                                <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-300 shrink-0"
                                                     :class="type === 'enhance' ? 'bg-[#6366f1] text-white shadow-[0_4px_10px_rgba(99,102,241,0.3)]' : 'bg-slate-50 text-slate-400 group-hover:bg-[#f5f3ff] group-hover:text-[#6366f1]'">
                                                    <i class="ph-bold ph-magic-wand text-[20px]"></i>
                                                </div>
                                                <div>
                                                    <p class="text-[14px] font-black tracking-tight" :class="type === 'enhance' ? 'text-slate-900' : 'text-slate-900'">AI Enhance</p>
                                                    <p class="text-[10px] font-bold mt-0.5 leading-snug" :class="type === 'enhance' ? 'text-[#6366f1]' : 'text-slate-500'">Professional suits or blazers.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- ── Step 3: Preferences (Remove BG) ─── --}}
                        <div x-show="type === 'remove_bg'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="mesh-gradient-ai border border-slate-200/70 rounded-[2.5rem] shadow-sm overflow-hidden mb-6 bento-step-card">
                            <div class="px-6 sm:px-8 py-5 sm:pt-7 sm:pb-5 border-b border-slate-100 flex items-center gap-4">
                                <div class="w-10 h-10 bg-primary-600 rounded-2xl flex items-center justify-center text-white font-black text-sm shadow-lg shadow-primary-100">3</div>
                                <div>
                                    <h3 class="text-base font-black text-slate-900 tracking-tight">Output Settings</h3>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Customize background and size</p>
                                </div>
                            </div>

                            <div class="p-6 sm:p-8 space-y-5">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div class="group/field space-y-2">
                                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest pl-1">Background Color</label>
                                        <div class="relative">
                                            <div class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 transition-all pointer-events-none">
                                                <i class="ph-bold ph-palette text-sm"></i>
                                            </div>
                                            <select name="background" class="w-full pl-14 pr-10 py-4 bg-slate-50/50 border border-slate-200 rounded-[1.25rem] text-[13px] font-bold text-slate-700 focus:ring-4 focus:ring-primary-500/5 focus:border-primary-400 focus:bg-white transition-all outline-none appearance-none cursor-pointer">
                                                <option value="transparan">Transparent (PNG)</option>
                                                <option value="merah">Merah (Red)</option>
                                                <option value="biru">Biru (Blue)</option>
                                                <option value="biru_muda">Biru Muda (Light Blue)</option>
                                                <option value="putih">Putih (White)</option>
                                            </select>
                                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                                <i class="ph-bold ph-caret-down text-xs"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="group/field space-y-2">
                                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest pl-1">Photo Size</label>
                                        <div class="relative">
                                            <div class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 transition-all pointer-events-none">
                                                <i class="ph-bold ph-frame-corners text-sm"></i>
                                            </div>
                                            <select name="size" class="w-full pl-14 pr-10 py-4 bg-slate-50/50 border border-slate-200 rounded-[1.25rem] text-[13px] font-bold text-slate-700 focus:ring-4 focus:ring-primary-500/5 focus:border-primary-400 focus:bg-white transition-all outline-none appearance-none cursor-pointer">
                                                <option value="original">Original Aspect Ratio</option>
                                                <option value="3x4">3x4 (Standard)</option>
                                                <option value="4x6">4x6 (Standard)</option>
                                                <option value="2x3">2x3</option>
                                            </select>
                                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                                <i class="ph-bold ph-caret-down text-xs"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── Step 3: Preferences (Enhance) ─── --}}
                        <div x-show="type === 'enhance'" style="display:none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="mesh-gradient-ai border border-slate-200/70 rounded-[2.5rem] shadow-sm overflow-hidden mb-6 bento-step-card">
                            <div class="px-6 sm:px-8 py-5 sm:pt-7 sm:pb-5 border-b border-slate-100 flex items-center gap-4">
                                <div class="w-10 h-10 bg-primary-600 rounded-2xl flex items-center justify-center text-white font-black text-sm shadow-lg shadow-primary-100">3</div>
                                <div>
                                    <h3 class="text-base font-black text-slate-900 tracking-tight">AI Settings</h3>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Customize style and environment</p>
                                </div>
                            </div>

                            <div class="p-6 sm:p-8 space-y-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div class="group/field space-y-2">
                                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest pl-1">Clothing Style</label>
                                        <div class="relative">
                                            <div class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 transition-all pointer-events-none">
                                                <i class="ph-bold ph-t-shirt text-sm"></i>
                                            </div>
                                            <select name="style" class="w-full pl-14 pr-10 py-4 bg-slate-50/50 border border-slate-200 rounded-[1.25rem] text-[13px] font-bold text-slate-700 focus:ring-4 focus:ring-primary-500/5 focus:border-primary-400 focus:bg-white transition-all outline-none appearance-none cursor-pointer">
                                                <option value="auto">Auto Detect Gender</option>
                                                <option value="linkedin_pria">LinkedIn (Male Suit)</option>
                                                <option value="linkedin_wanita">LinkedIn (Female Blazer)</option>
                                                <option value="rapi_formal">Rapi Formal (Suit & Tie)</option>
                                                <option value="professional_wanita">Professional (Blazer)</option>
                                                <option value="pasfoto_formal">Pas Foto Formal</option>
                                            </select>
                                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                                <i class="ph-bold ph-caret-down text-xs"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="group/field space-y-2">
                                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest pl-1">AI Background</label>
                                        <div class="relative">
                                            <div class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 transition-all pointer-events-none">
                                                <i class="ph-bold ph-image-square text-sm"></i>
                                            </div>
                                            <select name="background" class="w-full pl-14 pr-10 py-4 bg-slate-50/50 border border-slate-200 rounded-[1.25rem] text-[13px] font-bold text-slate-700 focus:ring-4 focus:ring-primary-500/5 focus:border-primary-400 focus:bg-white transition-all outline-none appearance-none cursor-pointer">
                                                <option value="studio_plain">Studio Plain</option>
                                                <option value="studio_gradient">Studio Gradient</option>
                                                <option value="modern_office">Modern Office</option>
                                                <option value="meeting_room">Meeting Room</option>
                                                <option value="city_rooftop">City Rooftop</option>
                                                <option value="outdoor_park">Outdoor Park</option>
                                                <option value="merah">Pas Foto (Merah)</option>
                                                <option value="biru">Pas Foto (Biru)</option>
                                            </select>
                                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                                <i class="ph-bold ph-caret-down text-xs"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest pl-1">Processing Mode</label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" name="mode" value="portrait" x-model="mode" class="sr-only">
                                            <div class="border rounded-[1.25rem] py-3.5 px-4 flex items-center justify-center gap-2.5 transition-all duration-300"
                                                 :class="mode === 'portrait' ? 'border-primary-500 bg-primary-50/50 text-primary-700 shadow-sm' : 'border-slate-200 bg-slate-50/50 hover:bg-white text-slate-600'">
                                                <i class="ph-bold ph-user-focus text-[18px]" :class="mode === 'portrait' ? 'text-primary-600' : 'text-slate-400'"></i>
                                                <span class="text-xs font-black">Portrait (Preserves Face)</span>
                                            </div>
                                        </label>
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" name="mode" value="headshot" x-model="mode" class="sr-only">
                                            <div class="border rounded-[1.25rem] py-3.5 px-4 flex items-center justify-center gap-2.5 transition-all duration-300"
                                                 :class="mode === 'headshot' ? 'border-primary-500 bg-primary-50/50 text-primary-700 shadow-sm' : 'border-slate-200 bg-slate-50/50 hover:bg-white text-slate-600'">
                                                <i class="ph-bold ph-magic-wand text-[18px]" :class="mode === 'headshot' ? 'text-primary-600' : 'text-slate-400'"></i>
                                                <span class="text-xs font-black">Headshot (Full AI)</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── Actions ─── --}}
                        <div class="flex items-center justify-between px-2 pt-2">
                            <a href="{{ route('tracker') }}" class="flex items-center gap-2 text-xs sm:text-sm font-black text-slate-400 hover:text-slate-900 transition-colors uppercase tracking-[0.15em]">
                                <i class="ph ph-arrow-left text-base"></i> Back
                            </a>
                            <button type="submit" id="submit-btn" disabled
                                class="group flex items-center gap-2.5 sm:gap-3 px-6 sm:px-10 py-3.5 sm:py-4 bg-primary-600 text-white rounded-[1.25rem] font-black text-xs sm:text-sm hover:bg-primary-700 transition-all shadow-2xl shadow-primary-200 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                                <i id="loading-spinner" class="ph ph-spinner animate-spin hidden text-base sm:text-lg"></i>
                                <i id="submit-icon" class="ph-bold ph-lightning text-base sm:text-lg group-hover:scale-125 transition-transform"></i>
                                <span id="submit-text" class="uppercase tracking-widest">Process Photo</span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ── Sidebar (5 Cols) ────────────────────────────────── --}}
                <div class="lg:col-span-5 space-y-6">

                    {{-- ── Credits Status Card ── --}}
                    @php 
                        $user = Auth::user();
                        $isPremium = $user->is_premium;
                        $remainingUses = $stats['remaining_credits'] ?? 0;
                    @endphp
                    
                    <div class="relative overflow-hidden rounded-[2.5rem] border {{ $isPremium ? 'border-indigo-100 bg-gradient-to-br from-indigo-900 via-indigo-950 to-slate-950 text-white' : 'border-slate-200 bg-gradient-to-br from-slate-900 via-slate-950 to-indigo-950 text-white' }} p-8 shadow-2xl">
                        {{-- Ambient backdrop glows --}}
                        <div class="absolute -right-12 -top-12 w-40 h-40 {{ $isPremium ? 'bg-indigo-500/25' : 'bg-primary-500/10' }} rounded-full blur-3xl pointer-events-none"></div>
                        <div class="absolute -left-8 -bottom-8 w-32 h-32 {{ $isPremium ? 'bg-primary-600/20' : 'bg-indigo-500/10' }} rounded-full blur-2xl pointer-events-none"></div>

                        <div class="relative z-10 flex flex-col justify-between h-full min-h-[180px]">
                            {{-- Card Header --}}
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-xl {{ $isPremium ? 'bg-indigo-500/20 border border-indigo-400/30' : 'bg-white/10 border border-white/10' }} flex items-center justify-center">
                                        <i class="ph-bold {{ $isPremium ? 'ph-crown text-amber-300' : 'ph-user text-slate-300' }} text-base"></i>
                                    </div>
                                    <div>
                                        <p class="text-[7.5px] font-black tracking-widest text-slate-400 uppercase">Membership</p>
                                        <p class="text-[10px] font-black {{ $isPremium ? 'text-indigo-300' : 'text-slate-300' }} uppercase tracking-wider mt-0.5">
                                            {{ $isPremium ? 'Premium Plan' : 'Free Trial' }}
                                        </p>
                                    </div>
                                </div>
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $isPremium ? 'bg-emerald-400' : 'bg-amber-400' }} opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 {{ $isPremium ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                </span>
                            </div>

                            {{-- Credit Info --}}
                            <div class="my-6">
                                <p class="text-[8px] font-black tracking-widest text-slate-400 uppercase">Sisa Kredit Photo Studio</p>
                                <div class="flex items-baseline gap-1 mt-1.5">
                                    <span class="text-3xl font-black tracking-tight {{ $isPremium ? 'text-white' : 'text-slate-100' }}">
                                        {{ $remainingUses === 'unlimited' ? '∞' : $remainingUses }}
                                    </span>
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Kredit</span>
                                </div>
                                <p class="text-[10px] text-slate-400 font-bold mt-1.5 italic">
                                    {{ $isPremium ? 'Kredit aktif untuk manipulasi foto AI.' : 'Gunakan fitur Photo AI secara gratis.' }}
                                </p>
                            </div>

                            <div class="pt-4 border-t border-white/10">
                                @if($isPremium)
                                    <a href="{{ route('payment.topup', ['package' => 'cover_letter']) }}"
                                        class="group flex items-center justify-center gap-2 w-full py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-black text-[9px] uppercase tracking-widest transition-all shadow-lg shadow-indigo-950/40">
                                        <i class="ph-bold ph-plus-circle text-sm group-hover:scale-125 transition-transform"></i>
                                        Top Up Kredit
                                    </a>
                                    <p class="text-[7px] text-center text-indigo-300/80 font-black uppercase tracking-widest mt-2">Dapatkan paket bundling hemat</p>
                                @else
                                    <a href="{{ route('payment.premium') }}"
                                        class="group flex items-center justify-center gap-2 w-full py-3.5 bg-gradient-to-r from-primary-500 to-indigo-600 hover:from-primary-600 hover:to-indigo-700 text-white rounded-xl font-black text-[9px] uppercase tracking-widest transition-all shadow-lg shadow-primary-950/40">
                                        <i class="ph-bold ph-lightning text-sm group-hover:animate-bounce"></i>
                                        Upgrade ke Premium
                                    </a>
                                    <p class="text-[7px] text-center text-primary-300/80 font-black uppercase tracking-widest mt-2">Dapatkan Bonus 5 Kredit Photo AI</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- How it works --}}
                    <div class="bg-white border border-slate-200/70 rounded-[2.5rem] p-8 shadow-sm bento-step-card">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] mb-7">Photo Engine</p>
                        <div class="space-y-6">
                            @foreach([
                                ['ph-scan', 'blue', 'Face Preservation', 'Maintains your unique facial features.'],
                                ['ph-t-shirt', 'purple', 'Smart Wardrobe', 'Automatically generates professional attire.'],
                                ['ph-image', 'indigo', 'Studio Quality', 'Outputs high-resolution 4K studio lighting.'],
                            ] as [$icon, $color, $title, $desc])
                            <div class="flex gap-4 group">
                                <div class="w-10 h-10 bg-{{ $color }}-50 rounded-2xl flex items-center justify-center shrink-0 shadow-sm transition-transform group-hover:scale-110">
                                    <i class="ph-bold {{ $icon }} text-{{ $color }}-500 text-lg"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-black text-slate-900 tracking-tight leading-none mb-1.5">{{ $title }}</p>
                                    <p class="text-[11px] text-slate-500 font-bold leading-relaxed">{{ $desc }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    {{-- Tips --}}
                    <div class="bg-amber-50 border border-amber-200/60 rounded-[2rem] p-7 shadow-sm">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600">
                                <i class="ph-fill ph-lightbulb text-lg"></i>
                            </div>
                            <p class="text-[10px] font-black text-amber-700 uppercase tracking-[0.15em]">Pro Tips</p>
                        </div>
                        <ul class="space-y-3">
                            @foreach([
                                'Gunakan foto yang cerah dan jelas.',
                                'Pastikan wajah menghadap kamera secara lurus.',
                                'Foto tidak terpotong bagian kepala.'
                            ] as $tip)
                            <li class="flex items-start gap-3 text-[11px] text-amber-800 font-black italic">
                                <i class="ph-bold ph-check text-amber-500 text-xs mt-0.5 shrink-0"></i>
                                {{ $tip }}
                            </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        // ── Upload area drag & drop ───────────────────────────────
        var photoInput    = document.getElementById('photo');
        var uploadArea    = document.getElementById('upload-area');
        var fileSuccess   = document.getElementById('file-success');
        var fileNameEl    = document.getElementById('file-name');
        var fileSizeEl    = document.getElementById('file-size');
        var imagePreview  = document.getElementById('image-preview');
        var removeFileBtn = document.getElementById('remove-file');
        var submitBtn     = document.getElementById('submit-btn');

        function showFile(file) {
            const mb = (file.size / (1024 * 1024)).toFixed(2);
            fileNameEl.textContent = file.name;
            fileSizeEl.textContent = mb + ' MB';
            
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
            }
            reader.readAsDataURL(file);

            fileSuccess.classList.remove('hidden');
            uploadArea.classList.add('hidden');
            submitBtn.disabled = false;
        }

        photoInput.addEventListener('change', e => {
            if (e.target.files[0]) showFile(e.target.files[0]);
        });

        removeFileBtn.addEventListener('click', () => {
            photoInput.value = '';
            fileSuccess.classList.add('hidden');
            uploadArea.classList.remove('hidden');
            submitBtn.disabled = true;
        });

        function handleDragOver(e) {
            e.preventDefault();
            uploadArea.classList.add('drag-active');
        }
        function handleDragLeave(e) {
            uploadArea.classList.remove('drag-active');
        }
        function handleDrop(e) {
            e.preventDefault();
            uploadArea.classList.remove('drag-active');
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                const dt = new DataTransfer();
                dt.items.add(file);
                photoInput.files = dt.files;
                showFile(file);
            }
        }

        // ── Submit ────────────────────────────────────────────────
        document.getElementById('photoForm').addEventListener('submit', function () {
            const btn = document.getElementById('submit-btn');
            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-not-allowed');
            document.getElementById('submit-text').textContent = 'Processing...';
            document.getElementById('submit-icon').classList.add('hidden');
            document.getElementById('loading-spinner').classList.remove('hidden');
        });
    </script>
</x-app-layout>
