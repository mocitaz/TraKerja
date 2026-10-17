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
                    {{ __('Reset your password.') }}
                </h1>
                <p class="text-xs text-zinc-500 dark:text-[#94a3b8] font-medium mt-1">{{ __('Enter your new password details') }}</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('password.store') }}" class="w-full space-y-3.5 text-left">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">{{ __('Email') }}</label>
                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email', $request->email) }}"
                           class="w-full px-3.5 py-2.5 bg-white dark:bg-[#1e1f20] border border-[#dedbd6] dark:border-white/10 rounded-lg text-[13px] font-normal text-[#1a191e] dark:text-[#f0f4f9] placeholder-zinc-400 dark:placeholder-zinc-500 focus:border-[#5b3d8c] dark:focus:border-purple-400 focus:bg-white dark:focus:bg-[#1e1f20] focus:ring-1 focus:ring-[#5b3d8c]/20 dark:focus:ring-purple-400/20 shadow-3xs outline-none transition-colors @error('email') border-rose-300 bg-rose-50/20 dark:bg-rose-950/20 @enderror"
                           placeholder="{{ __('Enter your email address...') }}">
                    @error('email')
                        <p class="mt-1 text-[11px] text-rose-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">{{ __('New Password') }}</label>
                    <div class="relative">
                        <input id="password" name="password" type="password" autocomplete="new-password" required
                               style="padding-right: 38px !important;"
                               class="w-full px-3.5 py-2.5 bg-white dark:bg-[#1e1f20] border border-[#dedbd6] dark:border-white/10 rounded-lg text-[13px] font-normal text-[#1a191e] dark:text-[#f0f4f9] placeholder-zinc-400 dark:placeholder-zinc-500 focus:border-[#5b3d8c] dark:focus:border-purple-400 focus:bg-white dark:focus:bg-[#1e1f20] focus:ring-1 focus:ring-[#5b3d8c]/20 dark:focus:ring-purple-400/20 shadow-3xs outline-none transition-colors @error('password') border-rose-300 bg-rose-50/20 dark:bg-rose-950/20 @enderror"
                               placeholder="{{ __('Enter new password') }}">
                        <button type="button" onclick="togglePassword('password', 'eye-password', 'eye-slash-password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 focus:outline-none">
                            <i id="eye-password" class="ph ph-eye text-base"></i>
                            <i id="eye-slash-password" class="ph ph-eye-slash text-base hidden"></i>
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
                               class="w-full px-3.5 py-2.5 bg-white dark:bg-[#1e1f20] border border-[#dedbd6] dark:border-white/10 rounded-lg text-[13px] font-normal text-[#1a191e] dark:text-[#f0f4f9] placeholder-zinc-400 dark:placeholder-zinc-500 focus:border-[#5b3d8c] dark:focus:border-purple-400 focus:bg-white dark:focus:bg-[#1e1f20] focus:ring-1 focus:ring-[#5b3d8c]/20 shadow-3xs outline-none transition-colors"
                               placeholder="{{ __('Re-enter password') }}">
                        <button type="button" onclick="togglePassword('password_confirmation', 'eye-confirm', 'eye-slash-confirm')" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 focus:outline-none">
                            <i id="eye-confirm" class="ph ph-eye text-base"></i>
                            <i id="eye-slash-confirm" class="ph ph-eye-slash text-base hidden"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-2.5 bg-[#5b3d8c] hover:bg-[#4a3173] active:bg-[#3d2760] text-white rounded-lg text-xs font-bold transition-all shadow-sm flex items-center justify-center gap-2 active:scale-98 focus:outline-none mt-2 cursor-pointer">
                    <span>{{ __('Reset Password') }}</span>
                </button>
            </form>

            <!-- Sign In Footer link -->
            <div class="text-xs font-normal text-zinc-500 dark:text-[#94a3b8] mt-5">
                <span>{{ __('Remember your password?') }}</span> <a href="{{ route('login') }}" class="text-[#5b3d8c] dark:text-purple-400 font-bold hover:underline">{{ __('Back to login') }}</a>
            </div>

            <!-- Workspace Footer Disclaimer -->
            <div class="w-full text-center space-y-2 pt-4 mt-4 border-t border-[#dedbd6]/70 dark:border-white/10">
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
    </script>
</x-guest-layout>