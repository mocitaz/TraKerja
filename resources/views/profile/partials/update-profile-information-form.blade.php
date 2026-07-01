<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" class="space-y-4" id="identityUpdateForm">
    @csrf
    @method('patch')

    <!-- Name -->
    <div>
        <label for="name" class="block text-[9.5px] font-bold text-zinc-400 uppercase tracking-wider pl-0.5 mb-1.5">Full Name</label>
        <div class="relative">
            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 pointer-events-none flex items-center">
                <i class="ph ph-user text-[13px]"></i>
            </div>
            <input id="name" name="name" type="text" 
                   value="{{ old('name', $user->name) }}" 
                   required autocomplete="name"
                   style="padding-left: 32px !important;"
                   class="w-full pr-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-md text-[11px] font-semibold text-zinc-700 focus:ring-1 focus:ring-zinc-400 focus:border-zinc-400 focus:bg-white transition-colors outline-none @error('name') border-rose-300 @enderror">
        </div>
        @error('name')
            <p class="mt-1 text-[9px] text-rose-500 font-bold uppercase tracking-wider">{{ $message }}</p>
        @enderror
    </div>

    <!-- Email -->
    <div>
        <label for="email" class="block text-[9.5px] font-bold text-zinc-400 uppercase tracking-wider pl-0.5 mb-1.5">Email Address</label>
        <div class="relative">
            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 pointer-events-none flex items-center">
                <i class="ph ph-envelope text-[13px]"></i>
            </div>
            <input id="email" name="email" type="email" 
                   value="{{ old('email', $user->email) }}" 
                   required autocomplete="username"
                   style="padding-left: 32px !important;"
                   class="w-full pr-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-md text-[11px] font-semibold text-zinc-700 focus:ring-1 focus:ring-zinc-400 focus:border-zinc-400 focus:bg-white transition-colors outline-none @error('email') border-rose-300 @enderror">
        </div>
        @error('email')
            <p class="mt-1 text-[9px] text-rose-500 font-bold uppercase tracking-wider">{{ $message }}</p>
        @enderror

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-3.5 bg-amber-50/40 border border-amber-200 rounded-md p-3">
                <div class="flex items-start gap-2">
                    <i class="ph ph-warning-circle text-amber-600 text-sm shrink-0 mt-0.5"></i>
                    <div class="flex-1">
                        <p class="text-[11px] text-amber-800 leading-normal font-semibold">
                            Your email address is unverified.
                            <button form="send-verification" class="font-bold underline hover:no-underline focus:outline-none">
                                Re-send verification email
                            </button>
                        </p>
                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-1 text-[9px] font-bold text-emerald-600 uppercase tracking-wider">
                                ✓ A new verification link has been sent!
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Preferences -->
    <div class="pt-2">
        <label class="block text-[9.5px] font-bold text-zinc-400 uppercase tracking-wider pl-0.5 mb-2.5">Preferences</label>
        
        <div class="flex items-start gap-2.5 p-3.5 bg-zinc-50/50 rounded-lg border border-zinc-200/60 transition-all hover:bg-zinc-50/80">
            <div class="flex items-center h-5 shrink-0">
                <input id="auto_archive_rejected" name="auto_archive_rejected" type="checkbox" value="1"
                       {{ old('auto_archive_rejected', $user->auto_archive_rejected) ? 'checked' : '' }}
                       class="w-3.5 h-3.5 text-zinc-800 bg-white border-zinc-300 rounded focus:ring-zinc-400 focus:ring-1">
            </div>
            <div>
                <label for="auto_archive_rejected" class="block text-[11px] font-bold text-zinc-800 select-none cursor-pointer">
                    Auto-Archive Rejected Applications
                </label>
                <p class="text-[9.5px] text-zinc-450 font-medium leading-normal mt-0.5 select-none">
                    Automatically move applications that have been in **Declined/Rejected** status or **Not Processed** stage for more than 14 days to the Archive tab.
                </p>
            </div>
        </div>
    </div>

    <!-- Submit -->
    <div class="flex items-center justify-end pt-3 border-t border-zinc-100">
        <button type="submit" class="px-3.5 py-1.5 bg-primary-50 text-zinc-800 border border-primary-200/60 hover:bg-primary-100 rounded-md text-[10px] font-bold uppercase tracking-wider transition-all flex items-center gap-1 shadow-3xs focus:outline-none active:scale-97">
            <i class="ph ph-check text-xs"></i>
            <span>Save Identity</span>
        </button>
    </div>
</form>
