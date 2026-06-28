<x-guest-layout>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <div class="min-h-screen bg-white font-sans antialiased text-[#37352f] flex flex-col justify-center items-center py-12 px-4 selection:bg-[#2383e2]/10">
        
        <!-- Main Container -->
        <div class="w-full max-w-[320px] flex flex-col items-center">
            
            <!-- Logo Section -->
            <div class="flex flex-col items-center text-center mb-4">
                <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-9 h-9 object-contain mb-1.5">
                <h1 class="text-[17px] font-bold text-[#37352f] tracking-tight leading-tight">
                    Forgot your password?
                </h1>
                <p class="text-xs text-slate-400 font-normal mt-0.5">Enter your email to receive a recovery link</p>
            </div>

            <!-- Session Status Alert -->
            @if (session('status'))
                <div class="w-full mb-3 text-xs font-semibold text-emerald-600 text-left">
                    <x-auth-session-status :status="session('status')" />
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('password.email') }}" class="w-full space-y-3 text-left">
                @csrf

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-[10.5px] font-medium text-slate-555 mb-1">Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                           class="w-full px-3 py-1.5 bg-[#fcfcfc] border border-slate-200 rounded-md text-xs font-normal text-[#37352f] placeholder-slate-400 focus:border-[#2383e2] focus:bg-white shadow-3xs outline-none transition-colors @error('email') border-rose-300 bg-rose-50/20 @enderror"
                           placeholder="Enter your email address...">
                    @error('email')
                        <p class="mt-1 text-[9px] text-rose-500 font-bold uppercase tracking-wider">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-1.5 bg-[#2383e2] hover:bg-[#1c72cb] active:bg-[#1560b3] text-white rounded-md text-xs font-bold transition-colors shadow-3xs flex items-center justify-center gap-2 active:scale-98 focus:outline-none mt-2 cursor-pointer">
                    <i class="ph-bold ph-paper-plane-tilt text-sm"></i>
                    <span>Send Reset Link</span>
                </button>
            </form>

            <!-- Back Link -->
            <div class="text-xs font-normal text-[#37352f] mt-3">
                <span class="text-slate-500">Remember your password?</span> <a href="{{ route('login') }}" class="text-[#2383e2] hover:underline">Back to login</a>
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
</x-guest-layout>