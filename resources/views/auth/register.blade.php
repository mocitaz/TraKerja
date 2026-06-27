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
                    Start your journey with <span class="bg-gradient-to-r from-primary-300 via-primary-200 to-violet-400 bg-clip-text text-transparent">smart career tools.</span>
                </h1>
                
                <p class="text-sm text-zinc-400 mt-4 leading-relaxed font-normal max-w-lg">
                    Create your account today to unlock automated application syncing, AI-powered resume analysis, and customized interview preparation suites.
                </p>
            </div>

            <!-- Footer Badge -->
            <div class="relative z-10 pt-6 border-t border-zinc-800/80 flex items-center justify-between text-xs text-zinc-400">
                <span class="font-medium">&copy; {{ date('Y') }} TraKerja by PT. Teknalogi Transformasi Digital.</span>
                <span class="flex items-center gap-1 text-[11px] font-semibold text-zinc-300"><i class="ph-fill ph-shield-check text-emerald-400 text-sm"></i> Free Account</span>
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
                    <span class="text-xs text-zinc-400 font-medium hidden sm:inline">Already registered?</span>
                    <a href="{{ route('login') }}" class="px-3 py-1.5 bg-white hover:bg-zinc-50 border border-zinc-200/80 rounded-md text-xs font-bold text-zinc-800 transition-colors shadow-3xs flex items-center gap-1">
                        <span>Sign in</span>
                        <i class="ph-bold ph-arrow-right text-[10px] text-zinc-400"></i>
                    </a>
                </div>
            </div>

            <!-- Centered Workspace Card -->
            <div class="w-full max-w-[380px] mx-auto my-auto py-4">
                
                <!-- Header -->
                <div class="mb-5">
                    <h2 class="text-xl font-black text-zinc-850 tracking-tight">Create your account</h2>
                    <p class="text-xs text-zinc-400 font-medium mt-1">Free forever. No credit card required.</p>
                </div>

                <!-- Success Notification -->
                @if (session('status') === 'registration-successful')
                    <div class="mb-4 p-3 bg-emerald-50/50 border border-emerald-200/80 rounded-md text-emerald-900 text-xs font-semibold flex items-start gap-2">
                        <i class="ph-fill ph-check-circle text-emerald-600 text-sm shrink-0 mt-0.5"></i>
                        <div>
                            <p class="font-bold text-[11px] leading-tight">Registration Successful</p>
                            <p class="text-[10px] text-emerald-700 mt-0.5 font-medium leading-normal">Welcome! Please check your email to verify your account.</p>
                        </div>
                    </div>
                @endif

                <!-- Validation Errors Alert Summary -->
                @if ($errors->any())
                    <div class="mb-4 p-3.5 bg-rose-50/70 border border-rose-200/80 rounded-md text-rose-900 text-xs font-semibold flex items-start gap-2.5 shadow-3xs">
                        <i class="ph-fill ph-warning-circle text-rose-600 text-sm shrink-0 mt-0.5"></i>
                        <div>
                            <p class="font-bold text-[11px] leading-tight">Registrasi Belum Dapat Diproses</p>
                            <ul class="mt-1 text-[10.5px] text-rose-700 font-medium list-disc list-inside space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- OAuth Buttons Grid -->
                <div class="grid grid-cols-2 gap-2.5 mb-4">
                    <a href="{{ route('auth.google') }}" class="flex items-center justify-center gap-2 py-2 px-3 bg-white hover:bg-zinc-50 border border-zinc-200/80 rounded-md text-xs font-semibold text-zinc-700 transition-colors shadow-3xs active:scale-98">
                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        <span>Google</span>
                    </a>

                    <a href="{{ route('auth.linkedin') }}" class="flex items-center justify-center gap-2 py-2 px-3 bg-white hover:bg-zinc-50 border border-zinc-200/80 rounded-md text-xs font-semibold text-zinc-700 transition-colors shadow-3xs active:scale-98">
                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="#0077B5">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                        <span>LinkedIn</span>
                    </a>
                </div>

                <!-- Divider -->
                <div class="relative my-4">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-zinc-200/80"></div>
                    </div>
                    <div class="relative flex justify-center text-[9px] uppercase tracking-wider font-bold">
                        <span class="px-2.5 bg-[#fafafa] text-zinc-400">Or register with email</span>
                    </div>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-3">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-[9.5px] font-bold text-zinc-400 uppercase tracking-wider pl-0.5 mb-1">Full Name</label>
                        <div class="relative">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 pointer-events-none flex items-center">
                                <i class="ph ph-user text-sm"></i>
                            </div>
                            <input id="name" name="name" type="text" autocomplete="name" required value="{{ old('name') }}"
                                   style="padding-left: 34px !important;"
                                   class="w-full pr-3 py-1.5 bg-white border border-zinc-200 rounded-md text-xs font-semibold text-zinc-800 focus:border-zinc-400 shadow-3xs transition-colors outline-none @error('name') border-rose-300 bg-rose-50/20 @enderror"
                                   placeholder="John Doe">
                        </div>
                        @error('name')
                            <p class="mt-1 text-[9px] text-rose-500 font-bold uppercase tracking-wider">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-[9.5px] font-bold text-zinc-400 uppercase tracking-wider pl-0.5 mb-1">Email Address</label>
                        <div class="relative">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 pointer-events-none flex items-center">
                                <i class="ph ph-envelope text-sm"></i>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                                   style="padding-left: 34px !important;"
                                   class="w-full pr-3 py-1.5 bg-white border border-zinc-200 rounded-md text-xs font-semibold text-zinc-800 focus:border-zinc-400 shadow-3xs transition-colors outline-none @error('email') border-rose-300 bg-rose-50/20 @enderror"
                                   placeholder="name@company.com">
                        </div>
                        @error('email')
                            <p class="mt-1 text-[9px] text-rose-500 font-bold uppercase tracking-wider">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-[9.5px] font-bold text-zinc-400 uppercase tracking-wider pl-0.5 mb-1">Password</label>
                        <div class="relative">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 pointer-events-none flex items-center">
                                <i class="ph ph-lock-key text-sm"></i>
                            </div>
                            <input id="password" name="password" type="password" autocomplete="new-password" required
                                   style="padding-left: 34px !important; padding-right: 34px !important;"
                                   class="w-full py-1.5 bg-white border border-zinc-200 rounded-md text-xs font-semibold text-zinc-800 focus:border-zinc-400 shadow-3xs transition-colors outline-none @error('password') border-rose-300 bg-rose-50/20 @enderror"
                                   placeholder="Min. 8 characters">
                            <button type="button" onclick="togglePassword('password', 'eye-icon-1', 'eye-off-icon-1')" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-700 focus:outline-none">
                                <i id="eye-icon-1" class="ph ph-eye text-sm"></i>
                                <i id="eye-off-icon-1" class="ph ph-eye-slash text-sm hidden"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-[9px] text-rose-500 font-bold uppercase tracking-wider">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-[9.5px] font-bold text-zinc-400 uppercase tracking-wider pl-0.5 mb-1">Confirm Password</label>
                        <div class="relative">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 pointer-events-none flex items-center">
                                <i class="ph ph-shield-check text-sm"></i>
                            </div>
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                                   style="padding-left: 34px !important; padding-right: 34px !important;"
                                   class="w-full py-1.5 bg-white border border-zinc-200 rounded-md text-xs font-semibold text-zinc-800 focus:border-zinc-400 shadow-3xs transition-colors outline-none"
                                   placeholder="Re-enter password">
                            <button type="button" onclick="togglePassword('password_confirmation', 'eye-icon-2', 'eye-off-icon-2')" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-700 focus:outline-none">
                                <i id="eye-icon-2" class="ph ph-eye text-sm"></i>
                                <i id="eye-off-icon-2" class="ph ph-eye-slash text-sm hidden"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Turnstile Verification -->
                    @if (!app()->environment('local') && !in_array(request()->getHost(), ['localhost', '127.0.0.1']))
                    <div class="flex flex-col items-center justify-center pt-1">
                        <div class="cf-turnstile scale-90 origin-center" data-sitekey="{{ env('TURNSTILE_SITE_KEY') }}" data-theme="light"></div>
                        @error('cf-turnstile-response')
                            <p class="mt-1 text-[9px] text-rose-500 font-bold uppercase tracking-wider">{{ $message }}</p>
                        @enderror
                    </div>
                    @endif

                    <!-- Submit Button -->
                    <button type="submit" id="register-submit-btn" class="w-full py-2.5 px-4 bg-primary-50 text-zinc-850 border border-primary-200/80 hover:bg-primary-100 rounded-md text-xs font-bold uppercase tracking-wider transition-all duration-150 shadow-3xs flex items-center justify-center gap-2 active:scale-98 focus:outline-none mt-2">
                        <i id="register-submit-icon" class="ph-bold ph-check text-sm"></i>
                        <span id="register-submit-spinner" class="hidden w-3.5 h-3.5 border-2 border-zinc-400 border-t-zinc-800 rounded-full animate-spin"></span>
                        <span id="register-submit-text">Create Account</span>
                    </button>
                </form>

            </div>

            <!-- Workspace Footer -->
            <div class="text-center text-[10.5px] font-medium text-zinc-400 mt-6 lg:mt-0">
                Protected by reCAPTCHA & Cloudflare Turnstile.
            </div>

        </div>

    </div>

    <script>
        function togglePassword(inputId, eyeId, eyeOffId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(eyeId);
            const eyeOffIcon = document.getElementById(eyeOffId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        }

        document.querySelector('form[action="{{ route('register') }}"]')?.addEventListener('submit', function() {
            const btn = document.getElementById('register-submit-btn');
            const icon = document.getElementById('register-submit-icon');
            const spinner = document.getElementById('register-submit-spinner');
            const text = document.getElementById('register-submit-text');
            if (btn) {
                btn.disabled = true;
                btn.classList.add('opacity-70', 'cursor-not-allowed');
                icon?.classList.add('hidden');
                spinner?.classList.remove('hidden');
                if (text) text.textContent = 'Creating Account…';
            }
        });
    </script>
    @if (!app()->environment('local') && !in_array(request()->getHost(), ['localhost', '127.0.0.1']))
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endif
</x-guest-layout>