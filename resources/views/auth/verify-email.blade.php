<x-guest-layout>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-12 bg-white font-sans antialiased text-zinc-900 selection:bg-primary-100 selection:text-primary-700">
        
        <!-- Left Side: Sleek Simple Obsidian Pane with IKN Overlay -->
        <div class="hidden lg:flex lg:col-span-6 xl:col-span-7 bg-zinc-950 border-r border-zinc-800/80 text-white relative flex-col justify-between p-8 lg:p-12 overflow-hidden select-none">
            
            <!-- Low Opacity IKN Background Image Overlay -->
            <div class="absolute inset-0 z-0 pointer-events-none">
                <img src="{{ asset('images/ikn.png') }}" alt="IKN Background" class="w-full h-full object-cover opacity-20 mix-blend-luminosity scale-105 filter blur-[0.5px]">
                <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/75 to-zinc-950/40"></div>
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-primary-950/30 via-transparent to-transparent"></div>
            </div>

            <!-- Top Header Logo Section -->
            <div class="relative z-10 flex items-center justify-between">
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-zinc-900/90 border border-zinc-700/80 flex items-center justify-center p-1.5 shadow-md group-hover:border-primary-500/50 transition-colors backdrop-blur-md">
                        <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-full h-full object-contain">
                    </div>
                    <div class="flex flex-col">
                        <span class="text-base font-black text-white tracking-tight">TraKerja</span>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="text-[10px] font-medium text-zinc-400">powered by</span>
                            <img src="{{ asset('images/teknalogi-logo.png') }}" alt="Teknalogi" class="h-3.5 w-auto opacity-90" onerror="this.style.display='none';">
                            <span class="text-[10px] font-bold text-zinc-300">Teknalogi</span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Main Simple Showcase Content -->
            <div class="relative z-10 my-auto py-12 max-w-xl">
                <h1 class="text-3xl lg:text-4xl xl:text-5xl font-black text-white tracking-tight leading-[1.15]">
                    Almost there! Confirm your <span class="bg-gradient-to-r from-primary-300 via-primary-200 to-violet-400 bg-clip-text text-transparent">email address.</span>
                </h1>
                
                <p class="text-sm text-zinc-400 mt-4 leading-relaxed font-normal max-w-lg">
                    We've sent a unique verification link to your email address to ensure account authenticity and protect your career data.
                </p>
            </div>

            <!-- Footer Badge -->
            <div class="relative z-10 pt-6 border-t border-zinc-800/80 flex items-center justify-between text-xs text-zinc-400">
                <span class="font-medium">&copy; {{ date('Y') }} TraKerja by PT. Teknalogi Transformasi Digital.</span>
                <span class="flex items-center gap-1 text-[11px] font-semibold text-zinc-300"><i class="ph-fill ph-check-circle text-emerald-400 text-sm"></i> Email Protocol</span>
            </div>
        </div>

        <!-- Right Side: Clean Workspace Form Pane -->
        <div class="col-span-12 lg:col-span-6 xl:col-span-5 flex flex-col justify-between p-6 sm:p-10 bg-[#fafafa] relative min-h-screen">
            
            <!-- Mobile Header / Top Bar Switcher -->
            <div class="flex items-center justify-between w-full mb-6 lg:mb-0">
                <a href="/" class="flex items-center gap-2.5 lg:hidden">
                    <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-7 h-7 object-contain">
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-zinc-850 leading-tight">TraKerja</span>
                        <div class="flex items-center gap-1">
                            <span class="text-[9px] text-zinc-400">by</span>
                            <img src="{{ asset('images/teknalogi-logo.png') }}" alt="Teknalogi" class="h-2.5 w-auto opacity-80" onerror="this.style.display='none';">
                            <span class="text-[9px] font-semibold text-zinc-600">Teknalogi</span>
                        </div>
                    </div>
                </a>

                <div class="flex items-center gap-2 ml-auto">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 bg-white hover:bg-zinc-50 border border-zinc-200/80 rounded-md text-xs font-bold text-zinc-800 transition-colors shadow-3xs flex items-center gap-1.5">
                            <i class="ph-bold ph-sign-out text-xs text-zinc-500"></i>
                            <span>Sign Out</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Centered Workspace Card -->
            <div class="w-full max-w-[380px] mx-auto my-auto py-4">
                
                <!-- Header -->
                <div class="mb-5 text-center sm:text-left">
                    <h2 class="text-xl font-black text-zinc-850 tracking-tight">Verifikasi Email Anda</h2>
                    <p class="text-xs text-zinc-500 font-medium mt-1">Satu langkah lagi untuk mengaktifkan penuh akun TraKerja Anda.</p>
                </div>

                <!-- Success Alert -->
                @if (session('status') == 'verification-link-sent')
                    <div class="mb-4 p-3 bg-emerald-50/60 border border-emerald-200/80 rounded-md text-emerald-900 text-xs font-semibold flex items-start gap-2 shadow-3xs">
                        <i class="ph-fill ph-check-circle text-emerald-600 text-sm shrink-0 mt-0.5"></i>
                        <div>
                            <p class="font-bold text-[11px] leading-tight">Link Verifikasi Terkirim!</p>
                            <p class="text-[10px] text-emerald-700 mt-0.5 font-medium leading-normal">Kami telah mengirimkan ulang pesan verifikasi terbaru ke email Anda.</p>
                        </div>
                    </div>
                @endif

                <!-- Target Email Box -->
                <div class="bg-white border border-zinc-200/80 rounded-lg p-3 shadow-3xs mb-4 text-center sm:text-left">
                    <p class="text-[9px] font-bold text-zinc-400 uppercase tracking-wider mb-1">Email Tujuan Verifikasi</p>
                    <p class="text-xs font-bold text-zinc-800 bg-zinc-50 py-1.5 px-3 rounded border border-zinc-200/60 break-all inline-block w-full">
                        {{ auth()->user()->email }}
                    </p>
                </div>

                <!-- Step-by-step Verification Guide -->
                <div class="bg-white border border-zinc-200/80 rounded-lg p-4 shadow-3xs space-y-3 mb-4 text-left">
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded bg-primary-50 border border-primary-200/60 flex items-center justify-center text-zinc-800 font-bold text-[10px] shrink-0 mt-0.5">1</div>
                        <div>
                            <h4 class="text-xs font-bold text-zinc-800">Cek Kotak Masuk Email</h4>
                            <p class="text-[10.5px] text-zinc-500 font-medium leading-normal mt-0.5">Buka aplikasi email Anda dan cari pesan masuk dari <span class="font-bold text-zinc-700">TraKerja</span>.</p>
                        </div>
                    </div>

                    <div class="border-t border-zinc-100"></div>

                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded bg-primary-50 border border-primary-200/60 flex items-center justify-center text-zinc-800 font-bold text-[10px] shrink-0 mt-0.5">2</div>
                        <div>
                            <h4 class="text-xs font-bold text-zinc-800">Klik Link Verifikasi</h4>
                            <p class="text-[10.5px] text-zinc-500 font-medium leading-normal mt-0.5">Klik tombol atau tautan konfirmasi di dalam email untuk mengaktifkan akun.</p>
                        </div>
                    </div>

                    <div class="border-t border-zinc-100"></div>

                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded bg-amber-50 border border-amber-200/60 flex items-center justify-center text-amber-800 font-bold text-[10px] shrink-0 mt-0.5">3</div>
                        <div>
                            <h4 class="text-xs font-bold text-zinc-800">Cek Folder Spam / Junk</h4>
                            <p class="text-[10.5px] text-zinc-500 font-medium leading-normal mt-0.5">Jika pesan tidak ada di Inbox utama, mohon periksa folder <span class="font-bold text-zinc-700">Spam</span>, <span class="font-bold text-zinc-700">Promotions</span>, atau <span class="font-bold text-zinc-700">Junk</span> Anda.</p>
                        </div>
                    </div>
                </div>

                <!-- Resend Action Form -->
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="w-full py-2.5 px-4 bg-primary-50 text-zinc-800 border border-primary-200/80 hover:bg-primary-100 rounded-lg text-xs font-bold uppercase tracking-wider transition-all duration-150 shadow-3xs flex items-center justify-center gap-2 active:scale-98 focus:outline-none">
                        <i class="ph-bold ph-paper-plane-tilt text-sm"></i>
                        <span>Kirim Ulang Email Verifikasi</span>
                    </button>
                </form>

            </div>

            <!-- Workspace Footer -->
            <div class="text-center text-[10.5px] font-medium text-zinc-400 mt-6 lg:mt-0">
                Protected by reCAPTCHA & Cloudflare Turnstile.
            </div>

        </div>

    </div>
</x-guest-layout>
