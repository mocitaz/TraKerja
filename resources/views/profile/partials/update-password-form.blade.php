<form method="post" action="{{ route('password.update') }}" class="space-y-4" id="passwordUpdateForm">
    @csrf
    @method('put')

    <!-- Hidden username field for accessibility (password managers) -->
    <input type="hidden" name="username" value="{{ Auth::user()->email }}" autocomplete="username">

    <!-- Password Requirements Banner -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            <div class="flex-1">
                <h4 class="text-sm font-semibold text-blue-900 mb-2">Password Requirements</h4>
                <ul class="text-xs text-blue-800 space-y-1">
                    <li class="flex items-center" id="req-length">
                        <span class="w-4 h-4 mr-2">○</span>
                        <span>At least 8 characters long</span>
                    </li>
                    <li class="flex items-center" id="req-uppercase">
                        <span class="w-4 h-4 mr-2">○</span>
                        <span>Contains uppercase letter (A-Z)</span>
                    </li>
                    <li class="flex items-center" id="req-lowercase">
                        <span class="w-4 h-4 mr-2">○</span>
                        <span>Contains lowercase letter (a-z)</span>
                    </li>
                    <li class="flex items-center" id="req-number">
                        <span class="w-4 h-4 mr-2">○</span>
                        <span>Contains number (0-9)</span>
                    </li>
                    <li class="flex items-center" id="req-special">
                        <span class="w-4 h-4 mr-2">○</span>
                        <span>Contains special character (!@#$%^&*)</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Current Password -->
    <div>
        <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 mb-1">
            Current Password <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <input id="update_password_current_password" 
                   name="current_password" 
                   type="password" 
                   required
                   autocomplete="current-password"
                   class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 text-sm pr-10 @error('current_password', 'updatePassword') border-red-300 @enderror">
            <button type="button" onclick="togglePassword('update_password_current_password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </button>
        </div>
        @error('current_password', 'updatePassword')
            <p class="mt-1 text-sm text-red-600 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- New Password -->
    <div>
        <label for="update_password_password" class="block text-sm font-medium text-gray-700 mb-1">
            New Password <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <input id="update_password_password" 
                   name="password" 
                   type="password"
                   required
                   autocomplete="new-password"
                   oninput="checkPasswordStrength()"
                   class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 text-sm pr-10 @error('password', 'updatePassword') border-red-300 @enderror">
            <button type="button" onclick="togglePassword('update_password_password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </button>
        </div>
        
        <!-- Password Strength Meter -->
        <div class="mt-2">
            <div class="flex items-center space-x-2">
                <div class="flex-1 bg-gray-200 rounded-full h-2 overflow-hidden">
                    <div id="strength-bar" class="h-full transition-all duration-300" style="width: 0%"></div>
                </div>
                <span id="strength-text" class="text-xs font-medium text-gray-500">No password</span>
            </div>
        </div>
        
        @error('password', 'updatePassword')
            <p class="mt-1 text-sm text-red-600 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Confirm Password -->
    <div>
        <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
            Confirm New Password <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <input id="update_password_password_confirmation" 
                   name="password_confirmation" 
                   type="password"
                   required
                   autocomplete="new-password"
                   oninput="checkPasswordMatch()"
                   class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 text-sm pr-10 @error('password_confirmation', 'updatePassword') border-red-300 @enderror">
            <button type="button" onclick="togglePassword('update_password_password_confirmation')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </button>
        </div>
        <p id="match-message" class="mt-1 text-sm hidden"></p>
        @error('password_confirmation', 'updatePassword')
            <p class="mt-1 text-sm text-red-600 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Submit -->
    <div class="flex items-center justify-end pt-4 border-t border-gray-100">
        <button type="submit" id="submitPasswordBtn" disabled class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
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
        let feedback = [];
        
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
            strengthBar.className = 'h-full transition-all duration-300 bg-gray-300';
            strengthText.textContent = 'No password';
            strengthText.className = 'text-xs font-medium text-gray-500';
        } else if (strength < 40) {
            strengthBar.className = 'h-full transition-all duration-300 bg-red-500';
            strengthText.textContent = 'Very Weak';
            strengthText.className = 'text-xs font-medium text-red-600';
        } else if (strength < 60) {
            strengthBar.className = 'h-full transition-all duration-300 bg-orange-500';
            strengthText.textContent = 'Weak';
            strengthText.className = 'text-xs font-medium text-orange-600';
        } else if (strength < 80) {
            strengthBar.className = 'h-full transition-all duration-300 bg-yellow-500';
            strengthText.textContent = 'Good';
            strengthText.className = 'text-xs font-medium text-yellow-600';
        } else if (strength < 100) {
            strengthBar.className = 'h-full transition-all duration-300 bg-blue-500';
            strengthText.textContent = 'Strong';
            strengthText.className = 'text-xs font-medium text-blue-600';
        } else {
            strengthBar.className = 'h-full transition-all duration-300 bg-green-500';
            strengthText.textContent = 'Very Strong';
            strengthText.className = 'text-xs font-medium text-green-600';
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
            icon.className = 'w-4 h-4 mr-2 text-green-600 font-bold';
            element.classList.add('text-green-700');
            element.classList.remove('text-blue-800');
        } else {
            icon.textContent = '○';
            icon.className = 'w-4 h-4 mr-2';
            element.classList.remove('text-green-700');
            element.classList.add('text-blue-800');
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
            matchMessage.className = 'mt-1 text-sm text-green-600 flex items-center';
            matchMessage.innerHTML = `
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Passwords match
            `;
        } else {
            matchMessage.className = 'mt-1 text-sm text-red-600 flex items-center';
            matchMessage.innerHTML = `
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Passwords do not match
            `;
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
