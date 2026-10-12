<x-guest-layout>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <div class="min-h-screen bg-[#fdfcf9] font-sans antialiased text-[#30313b] flex flex-col justify-center items-center py-12 px-4 selection:bg-[#2383e2]/10">
        
        <!-- Main Form Column (No Card Container) -->
        <div class="w-full max-w-[380px] flex flex-col items-center">
            
            <!-- Logo Section -->
            <div class="flex flex-col items-center text-center mb-4">
                <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-9 h-9 object-contain mb-1.5">
                <h1 class="text-[18px] sm:text-[19px] font-bold text-[#30313b] tracking-tight leading-tight">
                    Verify your email.
                </h1>
                <p class="text-xs text-slate-500 font-normal mt-1">Please check your inbox to continue</p>
            </div>

            <!-- Success Alert -->
            @if (session('status') == 'verification-link-sent')
                <div class="w-full mb-3.5 p-3 bg-emerald-50/80 border border-emerald-200/90 rounded-md text-emerald-950 text-xs font-semibold flex items-start gap-2.5 text-left">
                    <i class="ph-fill ph-check-circle text-emerald-600 text-base shrink-0 mt-0.5"></i>
                    <div>
                        <p class="font-bold text-xs leading-tight">Verification Link Sent</p>
                        <p class="text-[11px] text-emerald-700 mt-0.5 font-medium leading-normal">We have sent a new verification link to your email address.</p>
                    </div>
                </div>
            @endif

            <!-- Target Email Box -->
            <div class="w-full bg-white border border-slate-200/90 rounded-md p-3.5 text-left mb-3.5 shadow-3xs">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Email Sent To</p>
                <p class="text-xs sm:text-[13px] font-bold text-[#30313b] break-all select-all">
                    {{ auth()->user()->email }}
                </p>
            </div>

            <!-- Step-by-step Verification Guide -->
            <div class="w-full bg-white border border-slate-200/90 rounded-md p-4 space-y-3.5 text-left mb-3.5 shadow-3xs">
                <div class="flex items-start gap-3">
                    <div class="w-5.5 h-5.5 rounded bg-slate-100 border border-slate-200 flex items-center justify-center text-[#30313b] font-bold text-[11px] shrink-0 mt-0.5">1</div>
                    <div>
                        <h4 class="text-xs sm:text-[13px] font-bold text-[#30313b]">Check Email Inbox</h4>
                        <p class="text-[11.5px] text-slate-500 font-medium leading-normal mt-0.5">Open your email client and find the verification message sent by <span class="font-bold text-slate-700">TraKerja</span>.</p>
                    </div>
                </div>

                <div class="border-t border-slate-100"></div>

                <div class="flex items-start gap-3">
                    <div class="w-5.5 h-5.5 rounded bg-slate-100 border border-slate-200 flex items-center justify-center text-[#30313b] font-bold text-[11px] shrink-0 mt-0.5">2</div>
                    <div>
                        <h4 class="text-xs sm:text-[13px] font-bold text-[#30313b]">Click Verification Link</h4>
                        <p class="text-[11.5px] text-slate-500 font-medium leading-normal mt-0.5">Click the confirmation button inside the email to instantly activate your workspace.</p>
                    </div>
                </div>

                <div class="border-t border-slate-100"></div>

                <div class="flex items-start gap-3">
                    <div class="w-5.5 h-5.5 rounded bg-amber-50 border border-amber-200/60 flex items-center justify-center text-amber-800 font-bold text-[11px] shrink-0 mt-0.5">3</div>
                    <div>
                        <h4 class="text-xs sm:text-[13px] font-bold text-[#30313b]">Check Spam Folder</h4>
                        <p class="text-[11.5px] text-slate-500 font-medium leading-normal mt-0.5">If you do not see it in your primary inbox, check your <span class="font-bold text-slate-700">Spam</span> or <span class="font-bold text-slate-700">Promotions</span> folders.</p>
                    </div>
                </div>
            </div>

            <!-- Resend Action Form -->
            <form method="POST" action="{{ route('verification.send') }}" class="w-full mb-2">
                @csrf
                <button type="submit" class="w-full py-2 bg-[#2383e2] hover:bg-[#1c72cb] active:bg-[#1560b3] text-white rounded-md text-[13px] font-bold transition-colors shadow-3xs flex items-center justify-center gap-2 active:scale-98 focus:outline-none cursor-pointer">
                    <i class="ph-bold ph-paper-plane-tilt text-base"></i>
                    <span>Resend Email</span>
                </button>
            </form>

            <!-- Sign Out form -->
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full py-2 bg-white hover:bg-slate-50 border border-slate-200/90 rounded-md text-[13px] font-semibold text-slate-600 transition-colors shadow-3xs flex items-center justify-center gap-2 cursor-pointer">
                    <i class="ph-bold ph-sign-out text-base text-slate-500"></i>
                    <span>Sign Out</span>
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
