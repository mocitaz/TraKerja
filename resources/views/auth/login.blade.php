<x-guest-layout>
    <div class="min-h-screen bg-gray-50 flex">
        <!-- Left Side - Branding -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-[#0056B3] via-[#1e40af] to-[#28A745] relative overflow-hidden">
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
                                <img src="{{ asset('storage/logos/icon.png') }}" 
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
                            <p class="text-white/90 text-xl font-medium">Smart Tracking untuk Job Seeker</p>
                        </div>
                    </div>
                </div>
                
                <!-- Welcome Section -->
                <div class="mb-10">
                    <h2 class="text-4xl font-bold mb-4 drop-shadow-lg">Welcome Back</h2>
                    <p class="text-white/90 text-xl leading-relaxed">Continue your job search journey with intelligent tracking and powerful insights</p>
                </div>
                
                <!-- Enhanced Features -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-4 group">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:bg-white/30 transition-all duration-300 group-hover:scale-110">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-white font-semibold text-lg">Track Applications</h3>
                            <p class="text-white/80">Monitor all your job applications in one place</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4 group">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:bg-white/30 transition-all duration-300 group-hover:scale-110">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-white font-semibold text-lg">Progress Analytics</h3>
                            <p class="text-white/80">Get detailed insights on your job search progress</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4 group">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:bg-white/30 transition-all duration-300 group-hover:scale-110">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-white font-semibold text-lg">Stay Organized</h3>
                            <p class="text-white/80">Keep your job search focused and efficient</p>
                        </div>
                    </div>
                </div>
                
                <!-- Bottom Decoration -->
                <div class="mt-12 flex items-center space-x-2">
                    <div class="w-2 h-2 bg-white/60 rounded-full animate-pulse"></div>
                    <div class="w-2 h-2 bg-white/40 rounded-full animate-pulse" style="animation-delay: 0.2s;"></div>
                    <div class="w-2 h-2 bg-white/60 rounded-full animate-pulse" style="animation-delay: 0.4s;"></div>
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
                            <img src="{{ asset('storage/logos/icon.png') }}" 
                                 alt="TraKerja Logo" 
                                 class="w-8 h-8"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
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
                <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 p-8">
                    <!-- Form Header -->
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h2>
                        <p class="text-gray-600">Sign in to continue your journey</p>
                    </div>

                    <!-- Form -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                </div>
                                <input id="email" 
                                       name="email" 
                                       type="email" 
                                       autocomplete="email" 
                                       required 
                                       value="{{ old('email') }}"
                                       class="block w-full pl-12 pr-4 py-4 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0056B3] focus:border-transparent transition-all duration-200 bg-gray-50/50 @error('email') border-red-300 bg-red-50/50 @enderror"
                                       placeholder="Enter your email">
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-7a2 2 0 00-2-2H6a2 2 0 00-2 2v7a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <input id="password" 
                                       name="password" 
                                       type="password" 
                                       autocomplete="current-password" 
                                       required
                                       class="block w-full pl-12 pr-4 py-4 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0056B3] focus:border-transparent transition-all duration-200 bg-gray-50/50 @error('password') border-red-300 bg-red-50/50 @enderror"
                                       placeholder="Enter your password">
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember_me" 
                                       name="remember" 
                                       type="checkbox" 
                                       class="h-4 w-4 text-[#0056B3] focus:ring-[#0056B3] border-gray-300 rounded">
                                <label for="remember_me" class="ml-2 block text-sm font-medium text-gray-700">
                                    Remember me
                                </label>
                            </div>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" 
                                   class="text-sm text-[#0056B3] hover:text-[#003d82] font-semibold transition-colors">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-[#0056B3] to-[#28A745] hover:shadow-xl hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0056B3] transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Sign In
                        </button>
                    </form>

                    <!-- Register Link -->
                    <div class="mt-8 text-center">
                        <p class="text-sm text-gray-600">
                            Don't have an account?
                            <a href="{{ route('register') }}" class="font-bold text-[#0056B3] hover:text-[#003d82] transition-colors">
                                Sign up here
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>