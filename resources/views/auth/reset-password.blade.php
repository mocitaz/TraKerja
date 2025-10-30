<x-guest-layout>
    <style>
        /* Enhanced Animations */
        @keyframes gradientFlow {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        .gradient-animated {
            background: linear-gradient(120deg, #6b46c1, #9333ea, #4e71c5, #d983e4);
            background-size: 300% 300%;
            animation: gradientFlow 15s ease infinite;
        }
        
        .glass-morphism {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15), 
                        0 0 0 1px rgba(255, 255, 255, 0.5) inset;
        }
        
        .input-glow:focus {
            box-shadow: 0 0 0 3px rgba(217, 131, 228, 0.15),
                        0 10px 30px -10px rgba(78, 113, 197, 0.2);
        }
        
        .input-float {
            transition: transform 0.3s cubic-bezier(0.23, 1, 0.32, 1);
        }
        
        .input-float:focus {
            transform: translateY(-2px);
        }
    </style>
    
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50/30 to-purple-50/20 flex">
        <!-- Left Side - Branding -->
        <div class="hidden lg:flex lg:w-1/2 gradient-animated relative overflow-hidden">
            <!-- Animated Background Elements -->
            <div class="absolute top-10 left-8 w-72 h-72 bg-white/5 rounded-full mix-blend-multiply filter blur-2xl animate-pulse"></div>
            <div class="absolute top-32 right-16 w-48 h-48 bg-white/8 rounded-full mix-blend-multiply filter blur-xl animate-bounce" style="animation-duration: 3s;"></div>
            <div class="absolute bottom-20 left-20 w-64 h-64 bg-white/6 rounded-full mix-blend-multiply filter blur-2xl animate-pulse" style="animation-delay: 1s;"></div>
            <div class="absolute bottom-32 right-8 w-56 h-56 bg-white/4 rounded-full mix-blend-multiply filter blur-xl animate-bounce" style="animation-duration: 4s; animation-delay: 2s;"></div>
            
            <!-- Floating Geometric Shapes -->
            <div class="absolute top-24 right-24 w-4 h-4 bg-white/30 rounded-full animate-ping"></div>
            <div class="absolute top-40 left-32 w-3 h-3 bg-white/40 rounded-full animate-ping" style="animation-delay: 0.5s;"></div>
            <div class="absolute bottom-40 right-40 w-5 h-5 bg-white/25 rounded-full animate-ping" style="animation-delay: 1.5s;"></div>
            <div class="absolute bottom-24 left-16 w-2 h-2 bg-white/35 rounded-full animate-ping" style="animation-delay: 2.5s;"></div>
            
            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent"></div>
            
            <div class="relative z-10 flex flex-col justify-center p-12 text-white">
                <!-- Logo Section -->
                <div class="mb-10">
                    <div class="flex items-center space-x-5 mb-6">
                        <div class="relative">
                            <div class="absolute inset-0 bg-white/20 rounded-3xl blur-lg"></div>
                            <div class="w-20 h-20 rounded-3xl bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30 shadow-2xl">
                                <img src="{{ asset('images/icon.png') }}" 
                                     alt="TraKerja Logo" 
                                     class="w-12 h-12"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                            </svg>
                            </div>
                        </div>
                        <div>
                            <h1 class="text-5xl font-black mb-3 bg-gradient-to-r from-white to-white/80 bg-clip-text text-transparent drop-shadow-lg">TraKerja</h1>
                            <p class="text-white/90 text-xl font-medium">Smart Tracking for Job Seekers</p>
                        </div>
                    </div>
                </div>
                
                <!-- Welcome Section -->
                <div class="mb-10">
                    <h2 class="text-4xl font-bold mb-4 drop-shadow-lg">Reset Password</h2>
                    <p class="text-white/90 text-xl leading-relaxed">Create a new secure password for your account</p>
                </div>
                
                <!-- Enhanced Features -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-4 p-4 bg-white/10 rounded-2xl backdrop-blur-sm border border-white/20">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">Secure Reset Process</h3>
                            <p class="text-white/80 text-sm">Your password reset is encrypted and secure</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4 p-4 bg-white/10 rounded-2xl backdrop-blur-sm border border-white/20">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-7a2 2 0 00-2-2H6a2 2 0 00-2 2v7a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">Strong Password Requirements</h3>
                            <p class="text-white/80 text-sm">Must include letters, numbers, and symbols</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4 p-4 bg-white/10 rounded-2xl backdrop-blur-sm border border-white/20">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">Real-time Validation</h3>
                            <p class="text-white/80 text-sm">Instant feedback on password strength</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="flex-1 flex items-center justify-center p-8 lg:p-12">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-8">
                    <div class="flex items-center justify-center space-x-3 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">TraKerja</h1>
                            <p class="text-gray-600 text-sm">Smart Tracking untuk Job Seeker</p>
                        </div>
                    </div>
                </div>

                <!-- Form Card with Enhanced Glassmorphism -->
                <div class="glass-morphism rounded-3xl p-8 transform transition-all duration-500 hover:shadow-2xl">
                    <!-- Form Header -->
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Reset Password</h2>
                        <p class="text-gray-600">Enter your new password below</p>
                    </div>

                    <!-- Form -->
                    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                        @csrf

                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                    <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                </div>
                                <input id="email" 
                                       name="email" 
                                       type="email" 
                                       autocomplete="email" 
                                       required 
                                       value="{{ old('email', $request->email) }}"
                                       class="input-float input-glow block w-full pl-12 pr-4 py-4 border border-gray-200 rounded-xl focus:outline-none focus:ring-0 focus:border-transparent transition-all duration-300 bg-white/80 backdrop-blur-sm @error('email') border-red-300 bg-red-50/50 @enderror"
                                       placeholder="Enter your email address">
                            </div>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                    <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-7a2 2 0 00-2-2H6a2 2 0 00-2 2v7a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <input id="password" 
                                       name="password" 
                                       type="password" 
                                       autocomplete="new-password" 
                                       required
                                       class="input-float input-glow block w-full pl-12 pr-12 py-4 border border-gray-200 rounded-xl focus:outline-none focus:ring-0 focus:border-transparent transition-all duration-300 bg-white/80 backdrop-blur-sm @error('password') border-red-300 bg-red-50/50 @enderror"
                                       placeholder="Enter your new password">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <button type="button" onclick="togglePassword('password')" class="text-gray-400 hover:text-gray-600">
                                        <svg id="eye-password" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <svg id="eye-slash-password" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Password Strength Indicator -->
                            <div class="mt-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-medium text-gray-600">Password Strength:</span>
                                    <span id="strength-text" class="text-xs font-medium text-gray-400">Enter password</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div id="strength-bar" class="h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                                </div>
                            </div>

                            <!-- Password Requirements -->
                            <div class="mt-4 space-y-2">
                                <div class="flex items-center space-x-2">
                                    <div id="req-length" class="w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white hidden" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-gray-600">At least 8 characters</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div id="req-uppercase" class="w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white hidden" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-gray-600">Uppercase letter (A-Z)</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div id="req-lowercase" class="w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white hidden" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-gray-600">Lowercase letter (a-z)</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div id="req-number" class="w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white hidden" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-gray-600">Number (0-9)</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div id="req-symbol" class="w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white hidden" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-gray-600">Special character (!@#$%^&*)</span>
                                </div>
                            </div>

                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                    <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <input id="password_confirmation" 
                                       name="password_confirmation" 
                                       type="password"
                                       autocomplete="new-password" 
                                       required
                                       class="input-float input-glow block w-full pl-12 pr-12 py-4 border border-gray-200 rounded-xl focus:outline-none focus:ring-0 focus:border-transparent transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                       placeholder="Confirm your new password">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <button type="button" onclick="togglePassword('password_confirmation')" class="text-gray-400 hover:text-gray-600">
                                        <svg id="eye-password_confirmation" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <svg id="eye-slash-password_confirmation" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Password Match Indicator -->
                            <div class="mt-3">
                                <div id="password-match" class="flex items-center space-x-2 hidden">
                                    <div class="w-4 h-4 rounded-full bg-green-500 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-green-600 font-medium">Passwords match!</span>
                                </div>
                                <div id="password-mismatch" class="flex items-center space-x-2 hidden">
                                    <div class="w-4 h-4 rounded-full bg-red-500 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-red-600 font-medium">Passwords don't match</span>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-primary-600 to-secondary-500 hover:shadow-2xl hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-600 transition-all duration-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Reset Password
                        </button>
                    </form>

                    <!-- Back to Login -->
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            Remember your password?
                            <a href="{{ route('login') }}" class="font-medium text-purple-600 hover:text-purple-800">
                                Back to login
                            </a>
                        </p>
                    </div>

                    <!-- Security Notice -->
                    <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-600/20 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">Security Notice</h4>
                                <p class="text-xs text-gray-600 mt-1">
                                    This password reset link will expire in 60 minutes for your security. 
                                    If you didn't request this reset, please ignore this email.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const eye = document.getElementById('eye-' + fieldId);
            const eyeSlash = document.getElementById('eye-slash-' + fieldId);
            
            if (field.type === 'password') {
                field.type = 'text';
                eye.classList.add('hidden');
                eyeSlash.classList.remove('hidden');
            } else {
                field.type = 'password';
                eye.classList.remove('hidden');
                eyeSlash.classList.add('hidden');
            }
        }

        function checkPasswordStrength(password) {
            let score = 0;
            let requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                symbol: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
            };

            // Update requirement indicators
            Object.keys(requirements).forEach(req => {
                const indicator = document.getElementById('req-' + req);
                const checkmark = indicator.querySelector('svg');
                
                if (requirements[req]) {
                    indicator.classList.remove('border-gray-300');
                    indicator.classList.add('border-green-500', 'bg-green-500');
                    checkmark.classList.remove('hidden');
                    score++;
                } else {
                    indicator.classList.remove('border-green-500', 'bg-green-500');
                    indicator.classList.add('border-gray-300');
                    checkmark.classList.add('hidden');
                }
            });

            // Update strength bar and text
            const strengthBar = document.getElementById('strength-bar');
            const strengthText = document.getElementById('strength-text');
            
            const percentage = (score / 5) * 100;
            strengthBar.style.width = percentage + '%';
            
            if (score === 0) {
                strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-gray-300';
                strengthText.textContent = 'Enter password';
                strengthText.className = 'text-xs font-medium text-gray-400';
            } else if (score <= 2) {
                strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-red-500';
                strengthText.textContent = 'Weak';
                strengthText.className = 'text-xs font-medium text-red-500';
            } else if (score <= 3) {
                strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-yellow-500';
                strengthText.textContent = 'Fair';
                strengthText.className = 'text-xs font-medium text-yellow-500';
            } else if (score <= 4) {
                strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-blue-500';
                strengthText.textContent = 'Good';
                strengthText.className = 'text-xs font-medium text-blue-500';
            } else {
                strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-green-500';
                strengthText.textContent = 'Strong';
                strengthText.className = 'text-xs font-medium text-green-500';
            }

            return score === 5;
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const matchIndicator = document.getElementById('password-match');
            const mismatchIndicator = document.getElementById('password-mismatch');
            
            if (confirmPassword.length === 0) {
                matchIndicator.classList.add('hidden');
                mismatchIndicator.classList.add('hidden');
                return;
            }
            
            if (password === confirmPassword) {
                matchIndicator.classList.remove('hidden');
                mismatchIndicator.classList.add('hidden');
            } else {
                matchIndicator.classList.add('hidden');
                mismatchIndicator.classList.remove('hidden');
            }
        }

        // Event listeners
        document.getElementById('password').addEventListener('input', function() {
            checkPasswordStrength(this.value);
            checkPasswordMatch();
        });

        document.getElementById('password_confirmation').addEventListener('input', function() {
            checkPasswordMatch();
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            
            if (!checkPasswordStrength(password)) {
                e.preventDefault();
                alert('Please ensure your password meets all requirements.');
                return false;
            }
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match.');
                return false;
            }
        });
    </script>
</x-guest-layout>