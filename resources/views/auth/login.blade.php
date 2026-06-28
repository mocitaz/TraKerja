<x-guest-layout>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-12 bg-white font-sans antialiased text-zinc-900 selection:bg-primary-100 selection:text-primary-700">
        
        <!-- Left Side: Clean Light-Slate Corporate Showcase Pane -->
        <div class="hidden lg:flex lg:col-span-6 xl:col-span-7 bg-[#f8fafc] border-r border-slate-200/60 relative flex-col justify-between p-8 lg:p-12 overflow-hidden select-none">
            
            <!-- Grid Backdrop lines -->
            <div class="absolute inset-0 z-0 pointer-events-none opacity-[0.4]" style="background-image: linear-gradient(to right, rgba(0,0,0,0.03) 1px, transparent 1px), linear-gradient(to bottom, rgba(0,0,0,0.03) 1px, transparent 1px); background-size: 32px 32px;"></div>

            <!-- Top Header Logo Section -->
            <div class="relative z-10 flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-white border border-slate-200/80 flex items-center justify-center p-1.5 shadow-3xs">
                    <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-full h-full object-contain">
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-black text-slate-800 tracking-tight leading-none mb-0.5">TraKerja</span>
                    <span class="text-[9px] text-slate-500 font-semibold">by PT. Teknalogi Transformasi Digital</span>
                </div>
            </div>

            <!-- Main Corporate Content -->
            <div class="relative z-10 my-auto py-12 max-w-md space-y-8">
                <div class="space-y-4">
                    <span class="inline-flex px-2.5 py-0.5 bg-primary-50 border border-primary-200/60 rounded text-[9px] font-bold uppercase tracking-wider text-primary-700">
                        WORKSPACE KARIR PROFESIONAL
                    </span>
                    <h1 class="text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        Kelola pelamaran kerja. <br>Tingkatkan peluang diterima.
                    </h1>
                    <p class="text-xs text-slate-600 leading-relaxed font-normal">
                        TraKerja memfasilitasi pencari kerja profesional dalam mengorganisasikan berkas, mengukur kelayakan ATS resume, serta melacak tahapan rekrutmen secara mandiri dan aman.
                    </p>
                </div>

                <!-- Premium Light Mode Mockup Card (The WOW factor) -->
                <div class="p-5 bg-white border border-slate-200/80 rounded-xl shadow-3xs space-y-4 max-w-sm">
                    <!-- Header -->
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-6.5 h-6.5 rounded-full bg-primary-50 border border-primary-200/60 flex items-center justify-center font-bold text-primary-750 text-[10px]">
                                LF
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[11px] font-bold text-slate-800 leading-none mb-0.5">Luthfi Fauzi</span>
                                <span class="text-[8.5px] text-slate-500 font-semibold leading-none">Engineering Lead</span>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[8.5px] font-black rounded border border-emerald-200/60 uppercase">Aktif</span>
                    </div>

                    <!-- List of parsed status -->
                    <div class="space-y-2.5">
                        <div class="flex items-center justify-between text-[10px] border-b border-slate-50 pb-2">
                            <span class="text-slate-500 font-medium">Kecocokan ATS Resume</span>
                            <span class="font-bold text-slate-800">98% Sangat Sesuai</span>
                        </div>
                        <div class="flex items-center justify-between text-[10px] border-b border-slate-50 pb-2">
                            <span class="text-slate-500 font-medium">Jadwal Interview Aktif</span>
                            <span class="font-bold text-primary-605">3 Terjadwal</span>
                        </div>
                        <div class="flex items-center justify-between text-[10px]">
                            <span class="text-slate-500 font-medium">Akurasi Validasi Gaji (UMK)</span>
                            <span class="font-bold text-slate-800">Sesuai Standar 2026</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Badge -->
            <div class="relative z-10 pt-6 border-t border-slate-200/80 flex items-center justify-between text-[11px] text-slate-500">
                <span class="font-medium">&copy; {{ date('Y') }} TraKerja by PT. Teknalogi Transformasi Digital.</span>
                <span class="flex items-center gap-1 font-bold text-slate-600"><i class="ph-fill ph-shield-check text-emerald-600 text-sm"></i> Enterprise Security</span>
            </div>
        </div>

        <!-- Right Side: Clean Workspace Form Pane -->
        <div class="col-span-12 lg:col-span-6 xl:col-span-5 flex flex-col justify-between p-6 sm:p-10 bg-white relative min-h-screen">
            
            <!-- Mobile Header / Top Bar Switcher -->
            <div class="flex items-center justify-between w-full mb-6 lg:mb-0">
                <a href="/" class="flex items-center gap-2.5 lg:hidden">
                    <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-7 h-7 object-contain">
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-zinc-855 leading-tight">TraKerja</span>
                        <div class="flex items-center gap-1">
                            <span class="text-[9px] text-zinc-400">by</span>
                            <img src="{{ asset('images/teknalogi-logo.png') }}" alt="Teknalogi" class="h-2.5 w-auto opacity-80" onerror="this.style.display='none';">
                            <span class="text-[9px] font-semibold text-zinc-600">Teknalogi</span>
                        </div>
                    </div>
                </a>

                <div class="flex items-center gap-2 ml-auto">
                    <span class="text-xs text-zinc-400 font-medium hidden sm:inline">Don't have an account?</span>
                    <a href="{{ route('register') }}" class="px-3 py-1.5 bg-white hover:bg-zinc-50 border border-zinc-200/80 rounded-md text-xs font-bold text-zinc-800 transition-colors shadow-3xs flex items-center gap-1">
                        <span>Sign up</span>
                        <i class="ph-bold ph-arrow-right text-[10px] text-zinc-400"></i>
                    </a>
                </div>
            </div>

            <!-- Centered Workspace Card -->
            <div class="w-full max-w-[360px] mx-auto my-auto py-4">
                
                <!-- Header -->
                <div class="mb-6">
                    <h2 class="text-xl font-black text-zinc-855 tracking-tight">Sign in to TraKerja</h2>
                    <p class="text-xs text-zinc-400 font-medium mt-1">Welcome back! Please enter your details.</p>
                </div>

                <!-- Status Alerts -->
                @if (session('status') === 'registration-successful' || session('status') === 'verification-link-sent')
                    <div class="mb-4 p-3 bg-amber-50/50 border border-amber-200/80 rounded-md text-amber-900 text-xs font-semibold flex items-start gap-2">
                        <i class="ph-fill ph-warning-circle text-amber-600 text-sm shrink-0 mt-0.5"></i>
                        <div>
                            <p class="font-bold text-[11px] leading-tight">Verification Required</p>
                            <p class="text-[10px] text-amber-700 mt-0.5 font-medium leading-normal">Check your email inbox or spam folder to verify your account.</p>
                        </div>
                    </div>
                @endif

                @if (session('status') === 'email-not-verified')
                    <div class="mb-4 p-3 bg-rose-50/50 border border-rose-200/80 rounded-md text-rose-900 text-xs font-semibold flex items-start gap-2">
                        <i class="ph-fill ph-x-circle text-rose-600 text-sm shrink-0 mt-0.5"></i>
                        <div>
                            <p class="font-bold text-[11px] leading-tight">Email Unverified</p>
                            <p class="text-[10px] text-rose-700 mt-0.5 font-medium leading-normal">Please verify your email address before signing in.</p>
                        </div>
                    </div>
                @endif

                @if ($errors->has('email') && !$errors->has('password'))
                    <div class="mb-4 p-3 bg-rose-50/50 border border-rose-200/80 rounded-md text-rose-900 text-xs font-semibold flex items-start gap-2">
                        <i class="ph-fill ph-warning text-rose-600 text-sm shrink-0 mt-0.5"></i>
                        <div>
                            <p class="font-bold text-[11px] leading-tight">Authentication Failed</p>
                            <p class="text-[10px] text-rose-700 mt-0.5 font-medium leading-normal">{{ $errors->first('email') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-[9.5px] font-bold text-zinc-400 uppercase tracking-wider pl-0.5 mb-1.5">Email Address</label>
                        <div class="relative">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 pointer-events-none flex items-center">
                                <i class="ph ph-envelope text-sm"></i>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                                   style="padding-left: 34px !important;"
                                   class="w-full pr-3 py-2 bg-[#fafafa] border border-zinc-200 rounded-md text-xs font-semibold text-zinc-800 focus:border-zinc-400 focus:bg-white shadow-3xs transition-colors outline-none @error('email') border-rose-300 bg-rose-50/20 @enderror"
                                   placeholder="name@company.com">
                        </div>
                        @error('email')
                            <p class="mt-1 text-[9px] text-rose-500 font-bold uppercase tracking-wider">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-[9.5px] font-bold text-zinc-400 uppercase tracking-wider pl-0.5 mb-0">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[11px] font-bold text-zinc-500 hover:text-zinc-855 transition-colors">
                                    Forgot?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 pointer-events-none flex items-center">
                                <i class="ph ph-lock-key text-sm"></i>
                            </div>
                            <input id="password" name="password" type="password" autocomplete="current-password" required
                                   style="padding-left: 34px !important; padding-right: 34px !important;"
                                   class="w-full py-2 bg-[#fafafa] border border-zinc-200 rounded-md text-xs font-semibold text-zinc-800 focus:border-zinc-400 focus:bg-white shadow-3xs transition-colors outline-none @error('password') border-rose-300 bg-rose-50/20 @enderror"
                                   placeholder="••••••••">
                            <button type="button" onclick="togglePassword()" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-700 focus:outline-none">
                                <i id="eye-icon" class="ph ph-eye text-sm"></i>
                                <i id="eye-off-icon" class="ph ph-eye-slash text-sm hidden"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-[9px] text-rose-500 font-bold uppercase tracking-wider">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between pt-0.5">
                        <label for="remember_me" class="flex items-center gap-2 cursor-pointer group">
                            <input id="remember_me" name="remember" type="checkbox" class="w-3.5 h-3.5 rounded border-zinc-300 text-zinc-900 focus:ring-0 cursor-pointer">
                            <span class="text-xs font-semibold text-zinc-650 group-hover:text-zinc-855 transition-colors">Remember this browser</span>
                        </label>
                    </div>

                    <!-- Turnstile Verification -->
                    <div class="flex flex-col items-center justify-center pt-1">
                        <div class="cf-turnstile scale-90 origin-center" data-sitekey="{{ env('TURNSTILE_SITE_KEY') }}" data-theme="light"></div>
                        @error('cf-turnstile-response')
                            <p class="mt-1 text-[9px] text-rose-500 font-bold uppercase tracking-wider">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-2.5 px-4 bg-primary-50 text-zinc-855 border border-primary-200/80 hover:bg-primary-100 rounded-md text-xs font-bold uppercase tracking-wider transition-all duration-150 shadow-3xs flex items-center justify-center gap-2 active:scale-98 focus:outline-none mt-2">
                        <i class="ph-bold ph-sign-in text-sm"></i>
                        <span>Sign In</span>
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-zinc-200/80"></div>
                    </div>
                    <div class="relative flex justify-center text-[9px] uppercase tracking-wider font-bold">
                        <span class="px-2.5 bg-white text-zinc-400">Or continue with</span>
                    </div>
                </div>

                <!-- OAuth Buttons Grid -->
                <div class="grid grid-cols-2 gap-2.5">
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

            </div>

            <!-- Workspace Footer -->
            <div class="text-center text-[10.5px] font-medium text-zinc-400 mt-6 lg:mt-0">
                Protected by reCAPTCHA & Cloudflare Turnstile.
            </div>

        </div>

    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeOffIcon = document.getElementById('eye-off-icon');
            
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
    </script>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</x-guest-layout>