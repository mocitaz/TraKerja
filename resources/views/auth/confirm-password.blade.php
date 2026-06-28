<x-guest-layout>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <div class="min-h-screen bg-white font-sans antialiased text-[#37352f] flex flex-col justify-between items-center py-16 px-6 selection:bg-[#2383e2]/10">
        
        <!-- Main Container -->
        <div class="w-full max-w-[340px] mx-auto my-auto flex flex-col items-center space-y-6">
            
            <!-- Logo Section -->
            <div class="flex flex-col items-center text-center">
                <div class="w-12 h-12 rounded-xl bg-white border border-slate-200 shadow-2xs flex items-center justify-center p-2 mb-4">
                    <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-full h-full object-contain">
                </div>
                <h1 class="text-xl font-bold text-[#37352f] tracking-tight leading-snug">
                    Confirm password.
                </h1>
                <p class="text-sm text-slate-400 font-normal mt-0.5">Please confirm your password before continuing</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('password.confirm') }}" class="w-full space-y-4 text-left">
                @csrf

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-[11px] font-medium text-slate-550 mb-1.5">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           class="w-full px-3 py-1.5 bg-[#fcfcfc] border border-slate-200 rounded-md text-xs font-normal text-[#37352f] placeholder-slate-400 focus:border-[#2383e2] focus:bg-white shadow-3xs outline-none transition-colors @error('password') border-rose-300 bg-rose-50/20 @enderror"
                           placeholder="Enter your password">
                    @error('password')
                        <p class="mt-1 text-[9px] text-rose-500 font-bold uppercase tracking-wider">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-2 bg-[#2383e2] hover:bg-[#1c72cb] active:bg-[#1560b3] text-white rounded-md text-xs font-bold transition-colors shadow-3xs flex items-center justify-center gap-2 active:scale-98 focus:outline-none mt-2 cursor-pointer">
                    <span>Confirm Password</span>
                </button>
            </form>

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