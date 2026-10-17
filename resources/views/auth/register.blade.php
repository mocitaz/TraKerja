<x-guest-layout>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <div class="min-h-screen bg-[#fdfcf9] dark:bg-[#131314] font-sans antialiased text-[#1a191e] dark:text-[#f0f4f9] flex flex-col justify-center items-center py-12 px-4 selection:bg-[#5b3d8c]/15 transition-colors duration-200">

        <!-- Main Form Column (No Card Container) -->
        <div class="w-full max-w-[340px] flex flex-col items-center">

            <!-- Logo Section -->
            <div class="flex flex-col items-center text-center mb-5">
                <a href="{{ url('/') }}" class="inline-block transition-transform hover:scale-105">
                    <img src="{{ asset('images/icon.png') }}" alt="{{ __('TraKerja') }}" class="w-10 h-10 object-contain mb-2">
                </a>
                <h1 class="text-xl font-black text-zinc-900 dark:text-[#f0f4f9] tracking-tight leading-tight">
                    {{ __('Create your account.') }}
                </h1>
                <p class="text-xs text-zinc-500 dark:text-[#94a3b8] font-medium mt-1">{{ __('Free forever. No credit card required.') }}</p>
            </div>

            <!-- Validation Errors Alert Summary -->
                @if ($errors->any())
                    <div class="w-full mb-4 p-3 bg-rose-50/80 dark:bg-rose-950/40 border border-rose-200/90 dark:border-rose-500/30 rounded-lg text-rose-955 dark:text-rose-300 text-xs font-semibold flex items-start gap-2.5 text-left">
                        <i class="ph-fill ph-warning-circle text-rose-600 dark:text-rose-400 text-base shrink-0 mt-0.5"></i>
                        <div>
                            <p class="font-bold text-xs leading-tight">{{ __('Registration Failed') }}</p>
                            <ul class="mt-1 text-[11px] text-rose-750 dark:text-rose-300/80 font-medium list-disc list-inside space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('register') }}" class="w-full space-y-3.5 text-left">
                    @csrf

                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">{{ __('Full Name') }}</label>
                        <input id="name" name="name" type="text" autocomplete="name" required value="{{ old('name') }}"
                               class="w-full px-3.5 py-2.5 bg-white dark:bg-[#282a2c] border border-[#dedbd6] dark:border-white/10 rounded-lg text-[13px] font-normal text-[#1a191e] dark:text-[#f0f4f9] placeholder-zinc-400 dark:placeholder-zinc-500 focus:border-[#5b3d8c] dark:focus:border-purple-400 focus:bg-white dark:focus:bg-[#282a2c] focus:ring-1 focus:ring-[#5b3d8c]/20 dark:focus:ring-purple-400/20 shadow-3xs outline-none transition-colors @error('name') border-rose-300 bg-rose-50/20 dark:bg-rose-950/20 @enderror"
                               placeholder="{{ __('John Doe') }}">
                        @error('name')
                            <p class="mt-1 text-[11px] text-rose-500 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">{{ __('Email') }}</label>
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                               class="w-full px-3.5 py-2.5 bg-white dark:bg-[#282a2c] border border-[#dedbd6] dark:border-white/10 rounded-lg text-[13px] font-normal text-[#1a191e] dark:text-[#f0f4f9] placeholder-zinc-400 dark:placeholder-zinc-500 focus:border-[#5b3d8c] dark:focus:border-purple-400 focus:bg-white dark:focus:bg-[#282a2c] focus:ring-1 focus:ring-[#5b3d8c]/20 dark:focus:ring-purple-400/20 shadow-3xs outline-none transition-colors @error('email') border-rose-300 bg-rose-50/20 dark:bg-rose-950/20 @enderror"
                               placeholder="{{ __('Enter your email address...') }}">
                        @error('email')
                            <p class="mt-1 text-[11px] text-rose-500 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">{{ __('Password') }}</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" autocomplete="new-password" required
                                   style="padding-right: 38px !important;"
                                   class="w-full px-3.5 py-2.5 bg-white dark:bg-[#282a2c] border border-[#dedbd6] dark:border-white/10 rounded-lg text-[13px] font-normal text-[#1a191e] dark:text-[#f0f4f9] placeholder-zinc-400 dark:placeholder-zinc-500 focus:border-[#5b3d8c] dark:focus:border-purple-400 focus:bg-white dark:focus:bg-[#282a2c] focus:ring-1 focus:ring-[#5b3d8c]/20 dark:focus:ring-purple-400/20 shadow-3xs outline-none transition-colors @error('password') border-rose-300 bg-rose-50/20 dark:bg-rose-950/20 @enderror"
                                   placeholder="{{ __('Min. 8 characters') }}">
                            <button type="button" onclick="togglePassword('password', 'eye-icon-1', 'eye-off-icon-1')" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 focus:outline-none">
                                <i id="eye-icon-1" class="ph ph-eye text-base"></i>
                                <i id="eye-off-icon-1" class="ph ph-eye-slash text-base hidden"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-[11px] text-rose-500 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password Field -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">{{ __('Confirm Password') }}</label>
                        <div class="relative">
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                                   style="padding-right: 38px !important;"
                                   class="w-full px-3.5 py-2.5 bg-white dark:bg-[#282a2c] border border-[#dedbd6] dark:border-white/10 rounded-lg text-[13px] font-normal text-[#1a191e] dark:text-[#f0f4f9] placeholder-zinc-400 dark:placeholder-zinc-500 focus:border-[#5b3d8c] dark:focus:border-purple-400 focus:bg-white dark:focus:bg-[#282a2c] focus:ring-1 focus:ring-[#5b3d8c]/20 dark:focus:ring-purple-400/20 shadow-3xs outline-none transition-colors"
                                   placeholder="{{ __('Re-enter password') }}">
                            <button type="button" onclick="togglePassword('password_confirmation', 'eye-icon-2', 'eye-off-icon-2')" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 focus:outline-none">
                                <i id="eye-icon-2" class="ph ph-eye text-base"></i>
                                <i id="eye-off-icon-2" class="ph ph-eye-slash text-base hidden"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Turnstile Verification -->
                    @if (!app()->environment('local') && !in_array(request()->getHost(), ['localhost', '127.0.0.1']))
                    <div class="flex flex-col items-center justify-center pt-1">
                        <div class="cf-turnstile scale-90 origin-center" data-sitekey="{{ config('services.turnstile.site_key', '0x4AAAAAADVwvVUur2OE6_b9') }}" data-theme="light"></div>
                        @error('cf-turnstile-response')
                            <p class="mt-1 text-[11px] text-rose-500 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                    @endif

                    <!-- Submit Button -->
                    <button type="submit" id="register-submit-btn" class="w-full py-2.5 bg-[#5b3d8c] hover:bg-[#4a3173] active:bg-[#3d2760] text-white rounded-lg text-xs font-bold transition-all shadow-sm flex items-center justify-center gap-2 active:scale-98 focus:outline-none mt-1 cursor-pointer">
                        <span id="register-submit-spinner" class="hidden w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                        <span id="register-submit-text">{{ __('Create Account') }}</span>
                    </button>
                </form>

            <!-- Divider -->
            <div class="relative w-full my-4">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-[#dedbd6]/80 dark:border-white/10"></div>
                </div>
                <div class="relative flex justify-center text-[11px] font-medium text-zinc-400 dark:text-[#94a3b8]">
                    <span class="px-3 bg-[#fdfcf9] dark:bg-[#131314]">{{ __('or continue with') }}</span>
                </div>
            </div>

            <!-- OAuth Buttons Grid -->
            <div class="grid grid-cols-2 gap-2.5 w-full">
                <!-- Google Card -->
                <a href="{{ route('auth.google') }}" class="flex items-center justify-center gap-2 py-2.5 px-3 bg-white dark:bg-[#1e1f20] hover:bg-[#faf8f5] dark:hover:bg-[#282a2c] border border-[#dedbd6] dark:border-white/10 rounded-lg transition-colors shadow-3xs active:scale-98 cursor-pointer">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    <span class="text-xs font-semibold text-zinc-700 dark:text-[#f0f4f9]">{{ __('Google') }}</span>
                </a>

                <!-- LinkedIn Card -->
                <a href="{{ route('auth.linkedin') }}" class="flex items-center justify-center gap-2 py-2.5 px-3 bg-white dark:bg-[#1e1f20] hover:bg-[#faf8f5] dark:hover:bg-[#282a2c] border border-[#dedbd6] dark:border-white/10 rounded-lg transition-colors shadow-3xs active:scale-98 cursor-pointer">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="#0077B5" style="width: 16px; height: 16px;">
                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.225 0h.003z"/>
                    </svg>
                    <span class="text-xs font-semibold text-zinc-700 dark:text-[#f0f4f9]">{{ __('LinkedIn') }}</span>
                </a>
            </div>

            <!-- Sign In Footer link -->
            <div class="text-xs font-normal text-zinc-500 dark:text-[#94a3b8] mt-5">
                <span>{{ __('Already registered?') }}</span> <a href="{{ route('login') }}" class="text-[#5b3d8c] dark:text-purple-400 font-bold hover:underline">{{ __('Sign in') }}</a>
            </div>

            <!-- Workspace Footer Disclaimer -->
            <div class="w-full text-center space-y-2 pt-4 mt-5 border-t border-[#dedbd6]/70 dark:border-white/10">
                <p class="text-[10px] text-zinc-400 dark:text-zinc-500 leading-normal">
                    {{ __('By continuing, you acknowledge that you understand and agree to the') }} <a href="{{ route('legal.terms') }}" class="text-zinc-600 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white font-semibold underline underline-offset-2">{{ __('Terms & Conditions') }}</a> {{ __('and') }} <a href="{{ route('legal.privacy') }}" class="text-zinc-600 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white font-semibold underline underline-offset-2">{{ __('Privacy Policy') }}</a>.
                </p>
                <div class="flex items-center justify-center gap-1.5 text-[9px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider">
                    <span>{{ __('Powered by') }}</span>
                    <span class="text-zinc-600 dark:text-zinc-400">{{ __('PT. Teknalogi Transformasi Digital') }}</span>
                </div>
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
            const spinner = document.getElementById('register-submit-spinner');
            const text = document.getElementById('register-submit-text');
            if (btn) {
                btn.disabled = true;
                btn.classList.add('opacity-70', 'cursor-not-allowed');
                spinner?.classList.remove('hidden');
                if (text) text.textContent = @js(__('Creating Account…'));
            }
        });
    </script>
    @if (!app()->environment('local') && !in_array(request()->getHost(), ['localhost', '127.0.0.1']))
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endif
</x-guest-layout>