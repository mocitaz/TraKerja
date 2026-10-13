<x-guest-layout>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <div class="min-h-screen bg-[#fdfcf9] dark:bg-[#131314] font-sans antialiased text-[#1a191e] dark:text-[#f0f4f9] flex flex-col justify-center items-center py-12 px-4 selection:bg-[#35264f]/15 transition-colors duration-200">
        
        <!-- Main Form Column (No Card Container) -->
        <div class="w-full max-w-[340px] flex flex-col items-center">
            
            <!-- Logo Section -->
            <div class="flex flex-col items-center text-center mb-5">
                <a href="{{ url('/') }}" class="inline-block transition-transform hover:scale-105">
                    <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-10 h-10 object-contain mb-2">
                </a>
                <h1 class="text-[19px] font-bold text-[#1a191e] dark:text-[#f0f4f9] tracking-tight leading-tight">
                    Confirm password.
                </h1>
                <p class="text-xs text-zinc-500 dark:text-[#94a3b8] font-normal mt-1">Please confirm your password before continuing</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('password.confirm') }}" class="w-full space-y-3.5 text-left">
                @csrf

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           class="w-full px-3.5 py-2.5 bg-white dark:bg-[#1e1f20] border border-[#dedbd6] dark:border-white/10 rounded-lg text-[13px] font-normal text-[#1a191e] dark:text-[#f0f4f9] placeholder-zinc-400 dark:placeholder-zinc-500 focus:border-[#35264f] dark:focus:border-purple-400 focus:bg-white dark:focus:bg-[#1e1f20] focus:ring-1 focus:ring-[#35264f]/20 dark:focus:ring-purple-400/20 shadow-3xs outline-none transition-colors @error('password') border-rose-300 bg-rose-50/20 dark:bg-rose-950/20 @enderror"
                           placeholder="Enter your password">
                    @error('password')
                        <p class="mt-1 text-[11px] text-rose-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-2.5 bg-[#35264f] hover:bg-[#281c3e] active:bg-[#1f1530] text-white rounded-lg text-xs font-bold transition-all shadow-3xs flex items-center justify-center gap-2 active:scale-98 focus:outline-none mt-2 cursor-pointer">
                    <span>Confirm Password</span>
                </button>
            </form>

            <!-- Workspace Footer Disclaimer -->
            <div class="w-full text-center space-y-2 pt-4 mt-5 border-t border-[#dedbd6]/70 dark:border-white/10">
                <p class="text-[10px] text-zinc-400 dark:text-zinc-500 leading-normal">
                    By continuing, you acknowledge that you understand and agree to the <a href="{{ route('legal.terms') }}" class="text-zinc-600 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white font-semibold underline underline-offset-2">Terms & Conditions</a> and <a href="{{ route('legal.privacy') }}" class="text-zinc-600 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white font-semibold underline underline-offset-2">Privacy Policy</a>.
                </p>
                <div class="flex items-center justify-center gap-1.5 text-[9px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider">
                    <span>Powered by</span>
                    <span class="text-zinc-600 dark:text-zinc-400">PT. Teknalogi Transformasi Digital</span>
                </div>
            </div>

        </div>

    </div>
</x-guest-layout>