<form method="post" action="{{ route('password.update') }}" class="space-y-4" id="passwordUpdateForm">
    @csrf
    @method('put')

    <!-- Hidden username field for accessibility (password managers) -->
    <input type="hidden" name="username" value="{{ Auth::user()->email }}" autocomplete="username">

    <!-- Password Requirements Banner -->
    <div class="bg-zinc-50 border border-zinc-200 rounded-md p-3.5 shadow-3xs">
        <div class="flex items-start gap-2.5">
            <i class="ph ph-shield-check text-zinc-500 text-sm shrink-0 mt-0.5"></i>
            <div class="flex-1">
                <h4 class="text-[11px] font-bold text-zinc-800 tracking-tight uppercase mb-2">Password Requirements</h4>
                <ul class="text-[10px] text-zinc-500 font-semibold space-y-1.5">
                    <li class="flex items-center gap-1.5" id="req-length">
                        <span class="w-3.5 h-3.5 flex items-center justify-center shrink-0 border border-zinc-300 rounded text-[9px] font-bold">○</span>
                        <span>At least 8 characters long</span>
                    </li>
                    <li class="flex items-center gap-1.5" id="req-uppercase">
                        <span class="w-3.5 h-3.5 flex items-center justify-center shrink-0 border border-zinc-300 rounded text-[9px] font-bold">○</span>
                        <span>Contains uppercase letter (A-Z)</span>
                    </li>
                    <li class="flex items-center gap-1.5" id="req-lowercase">
                        <span class="w-3.5 h-3.5 flex items-center justify-center shrink-0 border border-zinc-300 rounded text-[9px] font-bold">○</span>
                        <span>Contains lowercase letter (a-z)</span>
                    </li>
                    <li class="flex items-center gap-1.5" id="req-number">
                        <span class="w-3.5 h-3.5 flex items-center justify-center shrink-0 border border-zinc-300 rounded text-[9px] font-bold">○</span>
                        <span>Contains number (0-9)</span>
                    </li>
                    <li class="flex items-center gap-1.5" id="req-special">
                        <span class="w-3.5 h-3.5 flex items-center justify-center shrink-0 border border-zinc-300 rounded text-[9px] font-bold">○</span>
                        <span>Contains special character (!@#$%^&*)</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Current Password -->
    <div>
        <label for="update_password_current_password" class="block text-[9.5px] font-bold text-zinc-400 uppercase tracking-wider pl-0.5 mb-1.5">Current Password <span class="text-rose-500">*</span></label>
        <div class="relative">
            <input id="update_password_current_password" 
                   name="current_password" 
                   type="password" 
                   required
                   autocomplete="current-password"
                   style="padding-right: 32px !important;"
                   class="w-full pl-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-md text-[11px] font-semibold text-zinc-700 focus:ring-1 focus:ring-zinc-400 focus:border-zinc-400 focus:bg-white transition-colors outline-none @error('current_password', 'updatePassword') border-rose-350 @enderror">
            <button type="button" onclick="togglePassword('update_password_current_password')" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-650 focus:outline-none">
                <i class="ph ph-eye text-sm"></i>
            </button>
        </div>
        @error('current_password', 'updatePassword')
            <p class="mt-1 text-[9px] text-rose-500 font-bold uppercase tracking-wider">{{ $message }}</p>
        @enderror
    </div>

    <!-- New Password -->
    <div>
        <label for="update_password_password" class="block text-[9.5px] font-bold text-zinc-400 uppercase tracking-wider pl-0.5 mb-1.5">New Password <span class="text-rose-500">*</span></label>
        <div class="relative">
            <input id="update_password_password" 
                   name="password" 
                   type="password"
                   required
                   autocomplete="new-password"
                   oninput="checkPasswordStrength()"
                   style="padding-right: 32px !important;"
                   class="w-full pl-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-md text-[11px] font-semibold text-zinc-700 focus:ring-1 focus:ring-zinc-400 focus:border-zinc-400 focus:bg-white transition-colors outline-none @error('password', 'updatePassword') border-rose-3-50 @enderror">
            <button type="button" onclick="togglePassword('update_password_password')" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-650 focus:outline-none">
                <i class="ph ph-eye text-sm"></i>
            </button>
        </div>
        
        <!-- Password Strength Meter -->
        <div class="mt-2 bg-zinc-50/50 border border-zinc-200 rounded-md px-2.5 py-1.5 max-w-xs shadow-3xs">
            <div class="flex items-center gap-2">
                <div class="flex-1 bg-zinc-200 rounded-full h-1 overflow-hidden">
                    <div id="strength-bar" class="h-full bg-zinc-300 transition-all duration-300" style="width: 0%"></div>
                </div>
                <span id="strength-text" class="text-[8px] font-bold text-zinc-400 uppercase tracking-wider">No password</span>
            </div>
        </div>
        
        @error('password', 'updatePassword')
            <p class="mt-1 text-[9px] text-rose-500 font-bold uppercase tracking-wider">{{ $message }}</p>
        @enderror
    </div>

    <!-- Confirm Password -->
    <div>
        <label for="update_password_password_confirmation" class="block text-[9.5px] font-bold text-zinc-400 uppercase tracking-wider pl-0.5 mb-1.5">Confirm New Password <span class="text-rose-500">*</span></label>
        <div class="relative">
            <input id="update_password_password_confirmation" 
                   name="password_confirmation" 
                   type="password"
                   required
                   autocomplete="new-password"
                   oninput="checkPasswordMatch()"
                   style="padding-right: 32px !important;"
                   class="w-full pl-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-md text-[11px] font-semibold text-zinc-700 focus:ring-1 focus:ring-zinc-400 focus:border-zinc-400 focus:bg-white transition-colors outline-none @error('password_confirmation', 'updatePassword') border-rose-355 @enderror">
            <button type="button" onclick="togglePassword('update_password_password_confirmation')" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-650 focus:outline-none">
                <i class="ph ph-eye text-sm"></i>
            </button>
        </div>
        <p id="match-message" class="mt-1 text-[9px] uppercase font-bold tracking-wider hidden"></p>
        @error('password_confirmation', 'updatePassword')
            <p class="mt-1 text-[9px] text-rose-500 font-bold uppercase tracking-wider">{{ $message }}</p>
        @enderror
    </div>

    <!-- Submit -->
    <div class="flex items-center justify-end pt-3 border-t border-zinc-100">
        <button type="submit" id="submitPasswordBtn" disabled class="px-3.5 py-1.5 bg-primary-50 text-zinc-800 border border-primary-200/60 hover:bg-primary-100 disabled:opacity-50 disabled:cursor-not-allowed rounded-md text-[10px] font-bold uppercase tracking-wider transition-all flex items-center gap-1 shadow-3xs focus:outline-none active:scale-97">
            <i class="ph ph-shield-check text-xs"></i>
            <span>Update Password</span>
        </button>
    </div>
</form>

<script>
    // Toggle password visibility
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        input.type = input.type === 'password' ? 'text' : 'password';
    }

    // Check password strength
    function checkPasswordStrength() {
        const password = document.getElementById('update_password_password').value;
        const strengthBar = document.getElementById('strength-bar');
        const strengthText = document.getElementById('strength-text');
        
        let strength = 0;
        
        // Check requirements
        const hasLength = password.length >= 8;
        const hasUppercase = /[A-Z]/.test(password);
        const hasLowercase = /[a-z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
        
        // Update requirement indicators
        updateRequirement('req-length', hasLength);
        updateRequirement('req-uppercase', hasUppercase);
        updateRequirement('req-lowercase', hasLowercase);
        updateRequirement('req-number', hasNumber);
        updateRequirement('req-special', hasSpecial);
        
        // Calculate strength
        if (hasLength) strength += 20;
        if (hasUppercase) strength += 20;
        if (hasLowercase) strength += 20;
        if (hasNumber) strength += 20;
        if (hasSpecial) strength += 20;
        
        // Update strength bar
        strengthBar.style.width = strength + '%';
        
        // Update color and text based on strength
        if (strength === 0) {
            strengthBar.className = 'h-full transition-all duration-300 bg-zinc-300';
            strengthText.textContent = 'No password';
            strengthText.className = 'text-[8px] font-bold text-zinc-400 uppercase tracking-wider';
        } else if (strength < 40) {
            strengthBar.className = 'h-full transition-all duration-300 bg-rose-500';
            strengthText.textContent = 'Very Weak';
            strengthText.className = 'text-[8px] font-bold text-rose-600 uppercase tracking-wider';
        } else if (strength < 60) {
            strengthBar.className = 'h-full transition-all duration-300 bg-amber-500';
            strengthText.textContent = 'Weak';
            strengthText.className = 'text-[8px] font-bold text-amber-600 uppercase tracking-wider';
        } else if (strength < 80) {
            strengthBar.className = 'h-full transition-all duration-300 bg-yellow-500';
            strengthText.textContent = 'Good';
            strengthText.className = 'text-[8px] font-bold text-yellow-600 uppercase tracking-wider';
        } else if (strength < 100) {
            strengthBar.className = 'h-full transition-all duration-300 bg-zinc-800';
            strengthText.textContent = 'Strong';
            strengthText.className = 'text-[8px] font-bold text-zinc-800 uppercase tracking-wider';
        } else {
            strengthBar.className = 'h-full transition-all duration-300 bg-emerald-600';
            strengthText.textContent = 'Very Strong';
            strengthText.className = 'text-[8px] font-bold text-emerald-600 uppercase tracking-wider';
        }
        
        checkPasswordMatch();
        updateSubmitButton();
    }

    // Update requirement indicator
    function updateRequirement(id, isMet) {
        const element = document.getElementById(id);
        const icon = element.querySelector('span:first-child');
        
        if (isMet) {
            icon.textContent = '✓';
            icon.className = 'w-3.5 h-3.5 flex items-center justify-center shrink-0 border border-emerald-500 bg-emerald-50 text-emerald-600 font-bold rounded text-[9px]';
            element.classList.add('text-emerald-700');
            element.classList.remove('text-zinc-500');
        } else {
            icon.textContent = '○';
            icon.className = 'w-3.5 h-3.5 flex items-center justify-center shrink-0 border border-zinc-300 rounded text-[9px] font-bold';
            element.classList.remove('text-emerald-700');
            element.classList.add('text-zinc-500');
        }
    }

    // Check if passwords match
    function checkPasswordMatch() {
        const password = document.getElementById('update_password_password').value;
        const confirmation = document.getElementById('update_password_password_confirmation').value;
        const matchMessage = document.getElementById('match-message');
        
        if (confirmation.length === 0) {
            matchMessage.classList.add('hidden');
            return;
        }
        
        matchMessage.classList.remove('hidden');
        
        if (password === confirmation) {
            matchMessage.className = 'mt-1 text-[9px] font-bold text-emerald-600 flex items-center gap-1 uppercase tracking-wider';
            matchMessage.innerHTML = `<i class="ph ph-check-circle text-xs"></i><span>Passwords match</span>`;
        } else {
            matchMessage.className = 'mt-1 text-[9px] font-bold text-rose-500 flex items-center gap-1 uppercase tracking-wider';
            matchMessage.innerHTML = `<i class="ph ph-x-circle text-xs"></i><span>Passwords do not match</span>`;
        }
        
        updateSubmitButton();
    }

    // Update submit button state
    function updateSubmitButton() {
        const password = document.getElementById('update_password_password').value;
        const confirmation = document.getElementById('update_password_password_confirmation').value;
        const currentPassword = document.getElementById('update_password_current_password').value;
        const submitBtn = document.getElementById('submitPasswordBtn');
        
        // Check all requirements
        const hasLength = password.length >= 8;
        const hasUppercase = /[A-Z]/.test(password);
        const hasLowercase = /[a-z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
        const passwordsMatch = password === confirmation && confirmation.length > 0;
        const hasCurrentPassword = currentPassword.length > 0;
        
        const allValid = hasLength && hasUppercase && hasLowercase && hasNumber && hasSpecial && passwordsMatch && hasCurrentPassword;
        
        submitBtn.disabled = !allValid;
    }

    // Add event listeners
    document.getElementById('update_password_current_password').addEventListener('input', updateSubmitButton);
</script>
