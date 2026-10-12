<x-guest-layout>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <div class="min-h-screen bg-[#fdfcf9] font-sans antialiased text-[#30313b] flex flex-col justify-center items-center py-12 px-4 selection:bg-[#2383e2]/10">
        
        <!-- Main Form Column (No Card Container) -->
        <div class="w-full max-w-[340px] flex flex-col items-center">
            
            <!-- Logo Section -->
            <div class="flex flex-col items-center text-center mb-4">
                <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-9 h-9 object-contain mb-1.5">
                <h1 class="text-[18px] sm:text-[19px] font-bold text-[#30313b] tracking-tight leading-tight">
                    Forgot your password?
                </h1>
                <p class="text-xs text-slate-500 font-normal mt-1">Enter your email to receive a recovery link</p>
            </div>

            <!-- Session Status Alert -->
            @if (session('status'))
                <div class="w-full mb-3.5 p-3 bg-emerald-50/80 border border-emerald-200/90 rounded-md text-emerald-950 text-xs font-semibold text-left">
                    <x-auth-session-status :status="session('status')" />
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('password.email') }}" class="w-full space-y-3.5 text-left">
                @csrf

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-600 mb-1.5">Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                           class="w-full px-3.5 py-2 bg-white border border-slate-200/90 rounded-md text-[13px] font-normal text-[#30313b] placeholder-slate-400 focus:border-[#2383e2] focus:bg-white focus:ring-1 focus:ring-[#2383e2]/20 shadow-3xs outline-none transition-colors @error('email') border-rose-300 bg-rose-50/20 @enderror"
                           placeholder="Enter your email address...">
                    @error('email')
                        <p class="mt-1 text-[11px] text-rose-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-2 bg-[#2383e2] hover:bg-[#1c72cb] active:bg-[#1560b3] text-white rounded-md text-[13px] font-bold transition-colors shadow-3xs flex items-center justify-center gap-2 active:scale-98 focus:outline-none mt-2 cursor-pointer">
                    <i class="ph-bold ph-paper-plane-tilt text-base"></i>
                    <span>Send Reset Link</span>
                </button>
            </form>

            <!-- Back Link -->
            <div class="text-xs font-normal text-slate-500 mt-4">
                <span>Remember your password?</span> <a href="{{ route('login') }}" class="text-[#2383e2] font-semibold hover:underline">Back to login</a>
            </div>

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