<x-guest-layout>
    <div class="min-h-screen bg-gray-50 flex">
        <!-- Left Side - Branding -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-[#0056B3] to-[#28A745] relative overflow-hidden">
            <!-- Background Elements -->
            <div class="absolute top-20 left-10 w-64 h-64 bg-white/10 rounded-full mix-blend-multiply filter blur-xl"></div>
            <div class="absolute bottom-20 right-10 w-64 h-64 bg-white/10 rounded-full mix-blend-multiply filter blur-xl"></div>
            
            <div class="relative z-10 flex flex-col justify-center p-12 text-white">
                <!-- Logo -->
                <div class="mb-8">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold mb-2">TraKerja</h1>
                            <p class="text-white/80 text-lg">Smart Tracking untuk Job Seeker</p>
                        </div>
                    </div>
                </div>
                
                <h2 class="text-3xl font-bold mb-6">Reset Password</h2>
                <p class="text-white/80 text-lg mb-8">Create a new secure password for your account</p>
                
                <!-- Security Features -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <span class="text-white/80">Secure password reset</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-7a2 2 0 00-2-2H6a2 2 0 00-2 2v7a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <span class="text-white/80">Strong password requirements</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-white/80">Account security protection</span>
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

                <!-- Form Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                    <!-- Form Header -->
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Reset Password</h2>
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
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                </div>
                                <input id="email" 
                                       name="email" 
                                       type="email" 
                                       autocomplete="email" 
                                       required 
                                       value="{{ old('email', $request->email) }}"
                                       class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0056B3] focus:border-transparent transition-colors @error('email') border-red-300 @enderror"
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
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-7a2 2 0 00-2-2H6a2 2 0 00-2 2v7a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <input id="password" 
                                       name="password" 
                                       type="password" 
                                       autocomplete="new-password" 
                                       required
                                       class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0056B3] focus:border-transparent transition-colors @error('password') border-red-300 @enderror"
                                       placeholder="Enter your new password">
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <input id="password_confirmation" 
                                       name="password_confirmation" 
                                       type="password"
                                       autocomplete="new-password" 
                                       required
                                       class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0056B3] focus:border-transparent transition-colors"
                                       placeholder="Confirm your new password">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-[#0056B3] to-[#28A745] hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0056B3] transition-all duration-200">
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
                            <a href="{{ route('login') }}" class="font-medium text-[#0056B3] hover:text-[#003d82]">
                                Back to login
                            </a>
                        </p>
                    </div>

                    <!-- Security Notice -->
                    <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-[#28A745]/20 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-[#28A745]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
</x-guest-layout>
                <!-- Form Header -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-[#212529] mb-2">Reset Password</h2>
                    <p class="text-gray-600">Enter your new password below</p>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email -->
        <div>
                        <label for="email" class="block text-sm font-medium text-[#212529] mb-2">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                            </div>
                            <input id="email" 
                                   name="email" 
                                   type="email" 
                                   autocomplete="email" 
                                   required 
                                   value="{{ old('email', $request->email) }}"
                                   class="block w-full pl-10 pr-4 py-3 border border-[#E9ECEF] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0056B3] focus:border-transparent transition-colors @error('email') border-red-300 @enderror"
                                   placeholder="Enter your email address">
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
        </div>

                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-[#212529] mb-2">New Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-7a2 2 0 00-2-2H6a2 2 0 00-2 2v7a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input id="password" 
                                   name="password" 
                                   type="password" 
                                   autocomplete="new-password" 
                                   required
                                   class="block w-full pl-10 pr-4 py-3 border border-[#E9ECEF] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0056B3] focus:border-transparent transition-colors @error('password') border-red-300 @enderror"
                                   placeholder="Enter your new password">
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
        </div>

        <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-[#212529] mb-2">Confirm New Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <input id="password_confirmation" 
                                   name="password_confirmation" 
                                type="password"
                                   autocomplete="new-password" 
                                   required
                                   class="block w-full pl-10 pr-4 py-3 border border-[#E9ECEF] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0056B3] focus:border-transparent transition-colors"
                                   placeholder="Confirm your new password">
                        </div>
                    </div>

                    <!-- Password Requirements -->
                    <div class="bg-[#E9ECEF]/30 border border-[#E9ECEF] rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-[#212529] mb-3">Password Requirements:</h4>
                        <ul class="text-xs text-gray-600 space-y-1">
                            <li class="flex items-center">
                                <svg class="w-3 h-3 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                At least 8 characters long
                            </li>
                            <li class="flex items-center">
                                <svg class="w-3 h-3 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Mix of uppercase and lowercase letters
                            </li>
                            <li class="flex items-center">
                                <svg class="w-3 h-3 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Include numbers and special characters
                            </li>
                        </ul>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-[#0056B3] hover:bg-[#003d82] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0056B3] transition-all duration-200 transform hover:scale-105">
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
                        <a href="{{ route('login') }}" class="font-medium text-[#0056B3] hover:text-[#003d82]">
                            Back to login
                        </a>
                    </p>
        </div>

                <!-- Security Notice -->
                <div class="mt-8 bg-[#E9ECEF]/30 border border-[#E9ECEF] rounded-lg p-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-[#28A745]/20 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-[#28A745]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-[#212529]">Security Notice</h4>
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
</x-guest-layout>