<x-guest-layout>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <div class="min-h-screen bg-white font-sans antialiased text-[#37352f] flex flex-col justify-center items-center py-12 px-4 selection:bg-[#2383e2]/10">
        
        <!-- Main Container -->
        <div class="w-full max-w-[320px] flex flex-col items-center space-y-5">
            
            <!-- Logo Section -->
            <div class="flex flex-col items-center text-center">
                <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-9 h-9 object-contain mb-2">
                <h1 class="text-[17px] font-bold text-[#37352f] tracking-tight leading-tight">
                    Verify your email.
                </h1>
                <p class="text-xs text-slate-400 font-normal mt-0.5">Please check your inbox to continue</p>
            </div>

            <!-- Success Alert -->
            <div class="w-full">
                @if (session('status') == 'verification-link-sent')
                    <div class="mb-3 p-3 bg-emerald-50/60 border border-emerald-200/80 rounded-md text-emerald-900 text-xs font-semibold flex items-start gap-2 shadow-3xs text-left">
                        <i class="ph-fill ph-check-circle text-emerald-600 text-sm shrink-0 mt-0.5"></i>
                        <div>
                            <p class="font-bold text-[11px] leading-tight">Verification Link Sent</p>
                            <p class="text-[10px] text-emerald-700 mt-0.5 font-medium leading-normal">We have sent a new verification link to your email address.</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Target Email Box -->
            <div class="w-full bg-[#fcfcfc] border border-slate-200/80 rounded-lg p-3 text-left">
                <p class="text-[9px] font-bold text-slate-450 uppercase tracking-wider mb-1">Email Sent To</p>
                <p class="text-xs font-bold text-slate-800 break-all select-all">
                    {{ auth()->user()->email }}
                </p>
            </div>

            <!-- Step-by-step Verification Guide -->
            <div class="w-full bg-[#fcfcfc] border border-slate-200/80 rounded-lg p-4 space-y-3 text-left">
                <div class="flex items-start gap-3">
                    <div class="w-5 h-5 rounded bg-primary-50 border border-primary-200/60 flex items-center justify-center text-zinc-800 font-bold text-[10px] shrink-0 mt-0.5">1</div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800">Check Email Inbox</h4>
                        <p class="text-[10.5px] text-slate-500 font-medium leading-normal mt-0.5">Open your email client and find the verification message sent by <span class="font-bold text-slate-700">TraKerja</span>.</p>
                    </div>
                </div>

                <div class="border-t border-slate-100"></div>

                <div class="flex items-start gap-3">
                    <div class="w-5 h-5 rounded bg-primary-50 border border-primary-200/60 flex items-center justify-center text-zinc-800 font-bold text-[10px] shrink-0 mt-0.5">2</div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800">Click Verification Link</h4>
                        <p class="text-[10.5px] text-slate-500 font-medium leading-normal mt-0.5">Click the confirmation button inside the email to instantly activate your workspace.</p>
                    </div>
                </div>

                <div class="border-t border-slate-100"></div>

                <div class="flex items-start gap-3">
                    <div class="w-5 h-5 rounded bg-amber-50 border border-amber-200/60 flex items-center justify-center text-amber-800 font-bold text-[10px] shrink-0 mt-0.5">3</div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800">Check Spam Folder</h4>
                        <p class="text-[10.5px] text-slate-500 font-medium leading-normal mt-0.5">If you do not see it in your primary inbox, check your <span class="font-bold text-slate-700">Spam</span> or <span class="font-bold text-slate-700">Promotions</span> folders.</p>
                    </div>
                </div>
            </div>

            <!-- Resend Action Form -->
            <form method="POST" action="{{ route('verification.send') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full py-1.5 bg-[#2383e2] hover:bg-[#1c72cb] active:bg-[#1560b3] text-white rounded-md text-xs font-bold transition-colors shadow-3xs flex items-center justify-center gap-2 active:scale-98 focus:outline-none cursor-pointer">
                    <i class="ph-bold ph-paper-plane-tilt text-sm"></i>
                    <span>Resend Email</span>
                </button>
            </form>

            <!-- Sign Out form -->
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full py-1.5 bg-white hover:bg-slate-50 border border-slate-200 rounded-md text-xs font-bold text-slate-750 transition-colors shadow-3xs flex items-center justify-center gap-1.5 cursor-pointer">
                    <i class="ph-bold ph-sign-out text-sm text-slate-455"></i>
                    <span>Sign Out</span>
                </button>
            </form>

            <!-- Workspace Footer Disclaimer -->
            <div class="w-full text-center space-y-2 pt-6 border-t border-slate-100">
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
