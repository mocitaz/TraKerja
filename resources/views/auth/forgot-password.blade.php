<x-guest-layout>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <div class="min-h-screen bg-white font-sans antialiased text-[#37352f] flex flex-col items-center pt-16 pb-6 px-6 selection:bg-[#2383e2]/10">
        
        <!-- Main Container -->
        <div class="w-full max-w-[340px] flex-1 flex flex-col justify-center items-center space-y-6">
            
            <!-- Logo Section -->
            <div class="flex flex-col items-center text-center">
                <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-12 h-12 object-contain mb-4">
                <h1 class="text-xl font-bold text-[#37352f] tracking-tight leading-snug">
                    Forgot your password?
                </h1>
                <p class="text-sm text-slate-400 font-normal mt-0.5">Enter your email to receive a recovery link</p>
            </div>

            <!-- Session Status Alert -->
            <div class="w-full">
                <x-auth-session-status class="mb-4 text-xs font-semibold text-emerald-600" :status="session('status')" />
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('password.email') }}" class="w-full space-y-4 text-left">
                @csrf

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-[11px] font-medium text-slate-550 mb-1.5">Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                           class="w-full px-3 py-1.5 bg-[#fcfcfc] border border-slate-200 rounded-md text-xs font-normal text-[#37352f] placeholder-slate-400 focus:border-[#2383e2] focus:bg-white shadow-3xs outline-none transition-colors @error('email') border-rose-300 bg-rose-50/20 @enderror"
                           placeholder="Enter your email address...">
                    @error('email')
                        <p class="mt-1 text-[9px] text-rose-500 font-bold uppercase tracking-wider">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-2 bg-[#2383e2] hover:bg-[#1c72cb] active:bg-[#1560b3] text-white rounded-md text-xs font-bold transition-colors shadow-3xs flex items-center justify-center gap-2 active:scale-98 focus:outline-none mt-2 cursor-pointer">
                    <i class="ph-bold ph-paper-plane-tilt text-sm"></i>
                    <span>Send Reset Link</span>
                </button>
            </form>

            <!-- Back Link -->
            <div class="text-xs font-normal text-slate-550 pt-2">
                Remember your password? <a href="{{ route('login') }}" class="text-[#2383e2] hover:underline">Back to login</a>
            </div>

        </div>

        <!-- Workspace Footer Disclaimer -->
        <div class="text-center space-y-3 max-w-[280px]">
            <div class="text-[10px] text-slate-400 leading-relaxed">
                By continuing, you acknowledge that you understand and agree to the <span class="hover:underline cursor-pointer">Terms & Conditions</span> and <span class="hover:underline cursor-pointer">Privacy Policy</span>.
            </div>
            <div class="flex items-center justify-center gap-1.5 text-[9px] font-bold text-slate-400 uppercase tracking-wider pt-2 border-t border-slate-100">
                <span>Powered by</span>
                <span class="text-slate-600">PT. Teknalogi Transformasi Digital</span>
            </div>
        </div>

    </div>
</x-guest-layout>