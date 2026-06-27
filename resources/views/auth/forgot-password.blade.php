<x-guest-layout>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-12 bg-white font-sans antialiased text-zinc-900 selection:bg-primary-100 selection:text-primary-700">
        
        <!-- Left Side: Sleek Simple Obsidian Pane with IKN Overlay -->
        <div class="hidden lg:flex lg:col-span-6 xl:col-span-7 bg-zinc-950 border-r border-zinc-800/80 text-white relative flex-col justify-between p-8 lg:p-12 overflow-hidden select-none">
            
            <!-- Low Opacity IKN Background Image Overlay -->
            <div class="absolute inset-0 z-0 pointer-events-none">
                <img src="{{ asset('images/ikn.png') }}" alt="IKN Background" class="w-full h-full object-cover opacity-20 mix-blend-luminosity scale-105 filter blur-[0.5px]">
                <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/75 to-zinc-950/40"></div>
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-primary-950/30 via-transparent to-transparent"></div>
            </div>

            <!-- Top Header Logo Section -->
            <div class="relative z-10 flex items-center justify-between">
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-zinc-900/90 border border-zinc-700/80 flex items-center justify-center p-1.5 shadow-md group-hover:border-primary-500/50 transition-colors backdrop-blur-md">
                        <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-full h-full object-contain">
                    </div>
                    <div class="flex flex-col">
                        <span class="text-base font-black text-white tracking-tight">TraKerja</span>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="text-[10px] font-medium text-zinc-400">powered by</span>
                            <img src="{{ asset('images/teknalogi-logo.png') }}" alt="Teknalogi" class="h-3.5 w-auto opacity-90" onerror="this.style.display='none';">
                            <span class="text-[10px] font-bold text-zinc-300">Teknalogi</span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Main Simple Showcase Content -->
            <div class="relative z-10 my-auto py-12 max-w-xl">
                <h1 class="text-3xl lg:text-4xl xl:text-5xl font-black text-white tracking-tight leading-[1.15]">
                    Fast, secure <span class="bg-gradient-to-r from-primary-300 via-primary-200 to-violet-400 bg-clip-text text-transparent">password restoration.</span>
                </h1>
                
                <p class="text-sm text-zinc-400 mt-4 leading-relaxed font-normal max-w-lg">
                    Don't worry! Enter your email address to receive an encrypted link and regain immediate access to your workspace.
                </p>
            </div>

            <!-- Footer Badge -->
            <div class="relative z-10 pt-6 border-t border-zinc-800/80 flex items-center justify-between text-xs text-zinc-400">
                <span class="font-medium">&copy; {{ date('Y') }} TraKerja by PT. Teknalogi Transformasi Digital.</span>
                <span class="flex items-center gap-1 text-[11px] font-semibold text-zinc-300"><i class="ph-fill ph-lock-key text-emerald-400 text-sm"></i> SSL Encrypted</span>
            </div>
        </div>

        <!-- Right Side: Clean Workspace Form Pane -->
        <div class="col-span-12 lg:col-span-6 xl:col-span-5 flex flex-col justify-between p-6 sm:p-10 bg-[#fafafa] relative min-h-screen">
            
            <!-- Mobile Header / Top Bar Switcher -->
            <div class="flex items-center justify-between w-full mb-6 lg:mb-0">
                <a href="/" class="flex items-center gap-2.5 lg:hidden">
                    <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-7 h-7 object-contain">
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-zinc-850 leading-tight">TraKerja</span>
                        <div class="flex items-center gap-1">
                            <span class="text-[9px] text-zinc-400">by</span>
                            <img src="{{ asset('images/teknalogi-logo.png') }}" alt="Teknalogi" class="h-2.5 w-auto opacity-80" onerror="this.style.display='none';">
                            <span class="text-[9px] font-semibold text-zinc-600">Teknalogi</span>
                        </div>
                    </div>
                </a>

                <div class="flex items-center gap-2 ml-auto">
                    <span class="text-xs text-zinc-400 font-medium hidden sm:inline">Remember password?</span>
                    <a href="{{ route('login') }}" class="px-3 py-1.5 bg-white hover:bg-zinc-50 border border-zinc-200/80 rounded-md text-xs font-bold text-zinc-800 transition-colors shadow-3xs flex items-center gap-1">
                        <span>Sign in</span>
                        <i class="ph-bold ph-arrow-right text-[10px] text-zinc-400"></i>
                    </a>
                </div>
            </div>

            <!-- Centered Workspace Card -->
            <div class="w-full max-w-[360px] mx-auto my-auto py-4">
                
                <!-- Header -->
                <div class="mb-6">
                    <h2 class="text-xl font-black text-zinc-850 tracking-tight">Forgot Password?</h2>
                    <p class="text-xs text-zinc-400 font-medium mt-1">Enter your email to receive a recovery link.</p>
                </div>

                <!-- Status Session Messages -->
                <x-auth-session-status class="mb-4 text-xs font-semibold" :status="session('status')" />

                <!-- Form -->
                <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-[9.5px] font-bold text-zinc-400 uppercase tracking-wider pl-0.5 mb-1.5">Email Address</label>
                        <div class="relative">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 pointer-events-none flex items-center">
                                <i class="ph ph-envelope text-sm"></i>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                                   style="padding-left: 34px !important;"
                                   class="w-full pr-3 py-2 bg-white border border-zinc-200 rounded-md text-xs font-semibold text-zinc-800 focus:border-zinc-400 shadow-3xs transition-colors outline-none @error('email') border-rose-300 bg-rose-50/20 @enderror"
                                   placeholder="name@company.com">
                        </div>
                        @error('email')
                            <p class="mt-1 text-[9px] text-rose-500 font-bold uppercase tracking-wider">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-2.5 px-4 bg-primary-50 text-zinc-850 border border-primary-200/80 hover:bg-primary-100 rounded-md text-xs font-bold uppercase tracking-wider transition-all duration-150 shadow-3xs flex items-center justify-center gap-2 active:scale-98 focus:outline-none mt-2">
                        <i class="ph-bold ph-paper-plane-tilt text-sm"></i>
                        <span>Send Reset Link</span>
                    </button>
                </form>

                <!-- Help Info -->
                <div class="mt-6 p-3.5 bg-white border border-zinc-200/80 rounded-md flex items-start gap-2.5 shadow-3xs">
                    <i class="ph-fill ph-info text-zinc-400 text-sm shrink-0 mt-0.5"></i>
                    <div>
                        <p class="font-bold text-[10px] text-zinc-700 leading-tight uppercase tracking-wider">Need Assistance?</p>
                        <p class="text-[10.5px] text-zinc-500 mt-0.5 font-medium leading-normal">Check your spam folder if you don't see the email within 2 minutes.</p>
                    </div>
                </div>

                <!-- Back Link -->
                <div class="mt-6 pt-4 border-t border-zinc-200/80 text-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-zinc-600 hover:text-zinc-900 transition-colors">
                        <i class="ph-bold ph-arrow-left text-xs"></i>
                        <span>Back to Sign In</span>
                    </a>
                </div>

            </div>

            <!-- Workspace Footer -->
            <div class="text-center text-[10.5px] font-medium text-zinc-400 mt-6 lg:mt-0">
                Protected by reCAPTCHA & Cloudflare Turnstile.
            </div>

        </div>

    </div>
</x-guest-layout>