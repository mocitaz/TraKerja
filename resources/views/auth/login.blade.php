<x-guest-layout>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <div class="min-h-screen bg-white font-sans antialiased text-[#37352f] flex flex-col justify-center items-center py-12 px-4 selection:bg-[#2383e2]/10">
        
        <!-- Main Container -->
        <div class="w-full max-w-[320px] flex flex-col items-center">
            
            <!-- Logo Section -->
            <div class="flex flex-col items-center text-center mb-4">
                <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-9 h-9 object-contain mb-1.5">
                <h1 class="text-[17px] font-bold text-[#37352f] tracking-tight leading-tight">
                    Your integrated career workspace.
                </h1>
                <p class="text-xs text-slate-400 font-normal mt-0.5">Log in to your TraKerja account</p>
            </div>

            <!-- Status Alerts -->
            @if (session('status') === 'registration-successful' || session('status') === 'verification-link-sent')
                <div class="w-full mb-3 p-3 bg-amber-50/50 border border-amber-200/80 rounded-md text-amber-955 text-xs font-semibold flex items-start gap-2 text-left">
                    <i class="ph-fill ph-warning-circle text-amber-600 text-sm shrink-0 mt-0.5"></i>
                    <div>
                        <p class="font-bold text-[11px] leading-tight">Verification Required</p>
                        <p class="text-[10px] text-amber-700 mt-0.5 font-medium leading-normal">Check your email inbox or spam folder to verify your account.</p>
                    </div>
                </div>
            @endif

            @if (session('status') === 'email-not-verified')
                <div class="w-full mb-3 p-3 bg-rose-50/50 border border-rose-200/80 rounded-md text-rose-955 text-xs font-semibold flex items-start gap-2 text-left">
                    <i class="ph-fill ph-x-circle text-rose-600 text-sm shrink-0 mt-0.5"></i>
                    <div>
                        <p class="font-bold text-[11px] leading-tight">Email Unverified</p>
                        <p class="text-[10px] text-rose-700 mt-0.5 font-medium leading-normal">Please verify your email address before signing in.</p>
                    </div>
                </div>
            @endif

            @if ($errors->has('email') && !$errors->has('password'))
                <div class="w-full mb-3 p-3 bg-rose-50/50 border border-rose-200/80 rounded-md text-rose-955 text-xs font-semibold flex items-start gap-2 text-left">
                    <i class="ph-fill ph-warning text-rose-600 text-sm shrink-0 mt-0.5"></i>
                    <div>
                        <p class="font-bold text-[11px] leading-tight">Authentication Failed</p>
                        <p class="text-[10px] text-rose-700 mt-0.5 font-medium leading-normal">{{ $errors->first('email') }}</p>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}" class="w-full space-y-3 text-left">
                @csrf

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-[10.5px] font-medium text-slate-500 mb-1">Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                           class="w-full px-3 py-1.5 bg-[#fcfcfc] border border-slate-200 rounded-md text-xs font-normal text-[#37352f] placeholder-slate-400 focus:border-[#2383e2] focus:bg-white shadow-3xs outline-none transition-colors @error('email') border-rose-300 bg-rose-50/20 @enderror"
                           placeholder="Enter your email address...">
                    @error('email')
                        <p class="mt-1 text-[9px] text-rose-500 font-bold uppercase tracking-wider">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-[10.5px] font-medium text-slate-500 mb-0">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[10.5px] text-slate-400 hover:text-slate-600 transition-colors">
                                Forgot?
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                               style="padding-right: 34px !important;"
                               class="w-full px-3 py-1.5 bg-[#fcfcfc] border border-slate-200 rounded-md text-xs font-normal text-[#37352f] placeholder-slate-400 focus:border-[#2383e2] focus:bg-white shadow-3xs outline-none transition-colors @error('password') border-rose-300 bg-rose-50/20 @enderror"
                               placeholder="••••••••">
                        <button type="button" onclick="togglePassword()" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-650 focus:outline-none">
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
                        <input id="remember_me" name="remember" type="checkbox" class="w-3.5 h-3.5 rounded border-slate-300 text-[#2383e2] focus:ring-0 cursor-pointer">
                        <span class="text-xs text-slate-550 group-hover:text-slate-700 transition-colors">Remember this browser</span>
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
                <button type="submit" class="w-full py-1.5 bg-[#2383e2] hover:bg-[#1c72cb] active:bg-[#1560b3] text-white rounded-md text-xs font-bold transition-colors shadow-3xs flex items-center justify-center gap-2 active:scale-98 focus:outline-none mt-1 cursor-pointer">
                    <span>Continue</span>
                </button>
            </form>

            <!-- Divider -->
            <div class="relative w-full my-3">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-200/60"></div>
                </div>
                <div class="relative flex justify-center text-[10px] font-medium text-slate-400">
                    <span class="px-3 bg-white">or continue with</span>
                </div>
            </div>

            <!-- OAuth Buttons Grid -->
            <div class="grid grid-cols-2 gap-2 w-full">
                <!-- Google Card -->
                <a href="{{ route('auth.google') }}" class="flex items-center justify-center gap-2 py-1.5 px-3 bg-white hover:bg-slate-50 border border-slate-200 rounded-md transition-colors shadow-3xs active:scale-98 cursor-pointer">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    <span class="text-xs font-semibold text-slate-700">Google</span>
                </a>

                <!-- LinkedIn Card -->
                <a href="{{ route('auth.linkedin') }}" class="flex items-center justify-center gap-2 py-1.5 px-3 bg-white hover:bg-slate-50 border border-slate-200 rounded-md transition-colors shadow-3xs active:scale-98 cursor-pointer">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="#0077B5" style="width: 16px; height: 16px;">
                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.225 0h.003z"/>
                    </svg>
                    <span class="text-xs font-semibold text-slate-700">LinkedIn</span>
                </a>
            </div>

            <!-- Sign Up Footer link -->
            <div class="text-xs font-normal text-[#37352f] mt-3">
                <span class="text-slate-500">New to TraKerja?</span> <a href="{{ route('register') }}" class="text-[#2383e2] hover:underline">Sign up</a>
            </div>

            <!-- Workspace Footer Disclaimer -->
            <div class="w-full text-center space-y-2 pt-5 mt-5 border-t border-slate-100">
                <p class="text-[9.5px] text-slate-400 leading-normal">
                    By continuing, you acknowledge that you understand and agree to the <span class="hover:underline cursor-pointer">Terms & Conditions</span> and <span class="hover:underline cursor-pointer">Privacy Policy</span>.
                </p>
                <div class="flex items-center justify-center gap-1.5 text-[8.5px] font-bold text-slate-400 uppercase tracking-wider">
                    <span>Powered by</span>
                    <span class="text-slate-600">PT. Teknalogi Transformasi Digital</span>
                </div>
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