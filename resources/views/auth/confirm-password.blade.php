<x-guest-layout>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <div class="min-h-screen bg-[#fdfcf9] font-sans antialiased text-[#30313b] flex flex-col justify-center items-center py-12 px-4 selection:bg-[#2383e2]/10">
        
        <!-- Main Form Column (No Card Container) -->
        <div class="w-full max-w-[340px] flex flex-col items-center">
            
            <!-- Logo Section -->
            <div class="flex flex-col items-center text-center mb-4">
                <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-9 h-9 object-contain mb-1.5">
                <h1 class="text-[18px] sm:text-[19px] font-bold text-[#30313b] tracking-tight leading-tight">
                    Confirm password.
                </h1>
                <p class="text-xs text-slate-500 font-normal mt-1">Please confirm your password before continuing</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('password.confirm') }}" class="w-full space-y-3.5 text-left">
                @csrf

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-600 mb-1.5">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           class="w-full px-3.5 py-2 bg-white border border-slate-200/90 rounded-md text-[13px] font-normal text-[#30313b] placeholder-slate-400 focus:border-[#2383e2] focus:bg-white focus:ring-1 focus:ring-[#2383e2]/20 shadow-3xs outline-none transition-colors @error('password') border-rose-300 bg-rose-50/20 @enderror"
                           placeholder="Enter your password">
                    @error('password')
                        <p class="mt-1 text-[11px] text-rose-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-2 bg-[#2383e2] hover:bg-[#1c72cb] active:bg-[#1560b3] text-white rounded-md text-[13px] font-bold transition-colors shadow-3xs flex items-center justify-center gap-2 active:scale-98 focus:outline-none mt-2 cursor-pointer">
                    <span>Confirm Password</span>
                </button>
            </form>

            <!-- Workspace Footer Disclaimer -->
            <div class="w-full text-center space-y-2 pt-4 mt-5 border-t border-slate-200/60">
                <p class="text-[10px] text-slate-400 leading-normal">
                    By continuing, you acknowledge that you understand and agree to the <a href="{{ route('legal.terms') }}" class="text-slate-500 hover:text-zinc-800 font-semibold underline underline-offset-2">Terms & Conditions</a> and <a href="{{ route('legal.privacy') }}" class="text-slate-500 hover:text-zinc-800 font-semibold underline underline-offset-2">Privacy Policy</a>.
                </p>
                <div class="flex items-center justify-center gap-1.5 text-[9px] font-bold text-slate-400 uppercase tracking-wider">
                    <span>Powered by</span>
                    <span class="text-slate-600">PT. Teknalogi Transformasi Digital</span>
                </div>
            </div>

        </div>

    </div>
</x-guest-layout>