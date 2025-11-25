<x-guest-layout>
    <style>
        /* Enhanced Animations */
        @keyframes gradientFlow {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        .gradient-animated {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #4facfe 75%, #667eea 100%);
            background-size: 400% 400%;
            animation: gradientFlow 20s ease infinite;
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
            <!-- Animated Background Elements - More Compact -->
            <div class="absolute top-8 left-6 w-56 h-56 bg-white/6 rounded-full mix-blend-overlay filter blur-3xl animate-pulse"></div>
            <div class="absolute top-24 right-12 w-40 h-40 bg-white/8 rounded-full mix-blend-overlay filter blur-2xl animate-pulse" style="animation-duration: 4s; animation-delay: 1s;"></div>
            <div class="absolute bottom-16 left-16 w-48 h-48 bg-white/5 rounded-full mix-blend-overlay filter blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
            <div class="absolute bottom-24 right-6 w-36 h-36 bg-white/7 rounded-full mix-blend-overlay filter blur-2xl animate-pulse" style="animation-duration: 5s; animation-delay: 0.5s;"></div>
            
            <!-- Floating Geometric Shapes - Subtle -->
            <div class="absolute top-20 right-20 w-3 h-3 bg-white/40 rounded-full animate-ping" style="animation-duration: 3s;"></div>
            <div class="absolute top-36 left-28 w-2.5 h-2.5 bg-white/35 rounded-full animate-ping" style="animation-duration: 4s; animation-delay: 1s;"></div>
            <div class="absolute bottom-32 right-32 w-3.5 h-3.5 bg-white/30 rounded-full animate-ping" style="animation-duration: 3.5s; animation-delay: 2s;"></div>
            
            <!-- Gradient Overlay - Subtle -->
            <div class="absolute inset-0 bg-gradient-to-br from-black/10 via-transparent to-black/5"></div>
            
            <div class="relative z-10 flex flex-col justify-center p-8 xl:p-12 text-white">
                <!-- Logo Section - Compact -->
                <div class="mb-8">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="relative">
                            <div class="absolute inset-0 bg-white/25 rounded-2xl blur-md"></div>
                            <div class="relative w-16 h-16 rounded-2xl bg-white/25 backdrop-blur-md flex items-center justify-center border border-white/40 shadow-xl">
                                <img src="{{ asset('images/icon.png') }}" 
                                     alt="TraKerja Logo" 
                                     class="w-10 h-10"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-1.5">
                                <h1 class="text-4xl xl:text-5xl font-black bg-gradient-to-r from-white via-white/95 to-white/85 bg-clip-text text-transparent drop-shadow-lg">TraKerja</h1>
                                <div class="flex items-center space-x-1.5 px-2.5 py-1 rounded-md bg-white/15 backdrop-blur-sm border border-white/25 hover:bg-white/20 hover:border-white/35 transition-all duration-300 group">
                                    <img src="{{ asset('images/teknalogi-logo.png') }}" 
                                         alt="Teknalogi Logo" 
                                         class="h-4 w-auto opacity-90 group-hover:opacity-100 transition-opacity duration-300"
                                         onerror="this.style.display='none';">
                                    <span class="text-white/85 text-[10px] xl:text-xs font-medium tracking-wide group-hover:text-white transition-colors duration-300">
                                        powered by <span class="font-semibold">Teknalogi</span>
                                    </span>
                                </div>
                            </div>
                            <p class="text-white/90 text-base xl:text-lg font-medium">Smart Tracking for Job Seekers</p>
                        </div>
                    </div>
                </div>
                
                <!-- Welcome Section - Compact -->
                <div class="mb-6">
                    <h2 class="text-3xl xl:text-4xl font-bold mb-2 drop-shadow-lg">Join TraKerja</h2>
                    <p class="text-white/85 text-base xl:text-lg leading-relaxed">Start your journey with the most intelligent job tracking platform</p>
                </div>
                
                <!-- Enhanced Features - Compact Grid -->
                <div class="space-y-3">
                    <div class="flex items-start space-x-3 group p-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 transition-all duration-300 cursor-pointer">
                        <div class="w-10 h-10 bg-gradient-to-br from-white/25 to-white/15 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:from-white/35 group-hover:to-white/25 transition-all duration-300 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-white font-semibold text-base mb-0.5">Centralized Tracking</h3>
                            <p class="text-white/75 text-sm leading-snug">Manage all applications in one powerful dashboard</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3 group p-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 transition-all duration-300 cursor-pointer">
                        <div class="w-10 h-10 bg-gradient-to-br from-white/25 to-white/15 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:from-white/35 group-hover:to-white/25 transition-all duration-300 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-white font-semibold text-base mb-0.5">Visual Kanban Board</h3>
                            <p class="text-white/75 text-sm leading-snug">Drag and drop interface for seamless workflow</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3 group p-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 transition-all duration-300 cursor-pointer">
                        <div class="w-10 h-10 bg-gradient-to-br from-white/25 to-white/15 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:from-white/35 group-hover:to-white/25 transition-all duration-300 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-white font-semibold text-base mb-0.5">Smart Analytics</h3>
                            <p class="text-white/75 text-sm leading-snug">Get insights that help you land your dream job</p>
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
                    <div class="flex items-center justify-center space-x-2 mb-1">
                        <h1 class="text-2xl font-bold text-gray-900">TraKerja</h1>
                        <div class="flex items-center space-x-1.5 px-2 py-0.5 rounded-md bg-gray-100/80 backdrop-blur-sm border border-gray-200/50 hover:bg-gray-100 transition-all duration-300 group">
                            <img src="{{ asset('images/teknalogi-logo.png') }}" 
                                 alt="Teknalogi Logo" 
                                 class="h-3.5 w-auto opacity-90 group-hover:opacity-100 transition-opacity duration-300"
                                 onerror="this.style.display='none';">
                            <span class="text-gray-600 text-[10px] font-medium tracking-wide group-hover:text-gray-700 transition-colors duration-300">
                                powered by <span class="font-semibold">Teknalogi</span>
                            </span>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm">Smart Tracking for Job Seekers</p>
                </div>

                <!-- Form Card with Enhanced Glassmorphism -->
                <div class="glass-morphism rounded-3xl p-8 transform transition-all duration-500 hover:shadow-2xl">
                    <!-- Form Header -->
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Join TraKerja</h2>
                        <p class="text-gray-600">Start your job tracking journey</p>
                    </div>

                    <!-- Success Notification -->
                    @if (session('status') === 'registration-successful')
                        <div id="registration-success-notification" class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm opacity-0 transform -translate-y-2 transition-all duration-300">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm font-medium text-green-800">Registration successful! Welcome to TraKerja!</p>
                            </div>
                        </div>
                    @endif


                <!-- Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <input id="name" 
                                   name="name" 
                                   type="text" 
                                   autocomplete="name" 
                                   required 
                                   value="{{ old('name') }}"
                                   class="input-float input-glow block w-full pl-12 pr-4 py-4 border border-gray-200 rounded-xl focus:outline-none focus:ring-0 focus:border-transparent transition-all duration-300 bg-white/80 backdrop-blur-sm @error('name') border-red-300 bg-red-50/50 @enderror"
                                   placeholder="Enter your full name">
                        </div>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
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
                                   value="{{ old('email') }}"
                                   class="input-float input-glow block w-full pl-12 pr-4 py-4 border border-gray-200 rounded-xl focus:outline-none focus:ring-0 focus:border-transparent transition-all duration-300 bg-white/80 backdrop-blur-sm @error('email') border-red-300 bg-red-50/50 @enderror"
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
                                   minlength="8"
                                   class="input-float input-glow block w-full pl-12 pr-4 py-4 border border-gray-200 rounded-xl focus:outline-none focus:ring-0 focus:border-transparent transition-all duration-300 bg-white/80 backdrop-blur-sm @error('password') border-red-300 bg-red-50/50 @enderror"
                                   placeholder="Create a strong password"
                                   oninput="validatePassword(this.value)">
                        </div>
                        
                        <!-- Password Requirements - Compact -->
                        <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                            <div class="flex items-center space-x-2" id="req-length">
                                <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-600">8+ characters</span>
                            </div>
                            <div class="flex items-center space-x-2" id="req-uppercase">
                                <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-600">Uppercase</span>
                            </div>
                            <div class="flex items-center space-x-2" id="req-lowercase">
                                <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-600">Lowercase</span>
                            </div>
                            <div class="flex items-center space-x-2" id="req-number">
                                <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-600">Number</span>
                            </div>
                            <div class="flex items-center space-x-2 col-span-2" id="req-symbol">
                                <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-600">Special character</span>
                            </div>
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

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
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
                                   class="input-float input-glow block w-full pl-12 pr-4 py-4 border border-gray-200 rounded-xl focus:outline-none focus:ring-0 focus:border-transparent transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                   placeholder="Confirm your password">
                        </div>
                    </div>

                    <!-- Terms Reminder -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4 mb-4">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold text-blue-900 mb-1">Important: Please Read First</h4>
                                <p class="text-xs text-blue-700">Before creating your account, please read our Terms of Service and Privacy Policy to understand your rights and our commitments.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Terms Agreement -->
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="terms" 
                                       name="terms" 
                                       type="checkbox" 
                                       required
                                       class="h-4 w-4 text-primary-600 focus:ring-primary-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="terms" class="text-gray-700">
                                    I have read and agree to the 
                                    <button type="button" onclick="openTerms()" class="text-primary-600 hover:text-primary-700 font-semibold underline">Terms of Service</button> 
                                    and 
                                    <button type="button" onclick="openPrivacy()" class="text-primary-600 hover:text-primary-700 font-semibold underline">Privacy Policy</button>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            onclick="return validateRegistration()"
                            class="w-full flex justify-center items-center py-3 px-5 border border-transparent rounded-lg shadow-md text-sm font-semibold text-white bg-gradient-to-r from-primary-600 via-primary-500 to-secondary-500 hover:from-primary-700 hover:via-primary-600 hover:to-secondary-600 hover:shadow-xl hover:scale-[1.02] active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500/50 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 disabled:hover:shadow-md relative overflow-hidden group"
                            disabled>
                        <svg class="w-4 h-4 mr-2 group-hover:rotate-90 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        Create Account
                    </button>
                </form>

                <!-- Professional Modal JavaScript -->
                <script>
                // Modal functions
                function openTerms() {
                    const modal = document.getElementById('termsModal');
                    const modalContent = document.getElementById('termsModalContent');
                    if (modal && modalContent) {
                        modal.classList.remove('hidden');
                        document.body.style.overflow = 'hidden';
                        // Trigger animation
                        setTimeout(() => {
                            modalContent.classList.remove('scale-95', 'opacity-0');
                            modalContent.classList.add('scale-100', 'opacity-100');
                        }, 10);
                        startTermsReadingProgress();
                    }
                }
                
                function openPrivacy() {
                    const modal = document.getElementById('privacyModal');
                    const modalContent = document.getElementById('privacyModalContent');
                    if (modal && modalContent) {
                        modal.classList.remove('hidden');
                        document.body.style.overflow = 'hidden';
                        // Trigger animation
                        setTimeout(() => {
                            modalContent.classList.remove('scale-95', 'opacity-0');
                            modalContent.classList.add('scale-100', 'opacity-100');
                        }, 10);
                        startPrivacyReadingProgress();
                    }
                }
                
                function closeTerms() {
                    const modal = document.getElementById('termsModal');
                    const modalContent = document.getElementById('termsModalContent');
                    if (modal && modalContent) {
                        modalContent.classList.remove('scale-100', 'opacity-100');
                        modalContent.classList.add('scale-95', 'opacity-0');
                        setTimeout(() => {
                            modal.classList.add('hidden');
                            document.body.style.overflow = 'auto';
                        }, 300);
                    }
                }
                
                function closePrivacy() {
                    const modal = document.getElementById('privacyModal');
                    const modalContent = document.getElementById('privacyModalContent');
                    if (modal && modalContent) {
                        modalContent.classList.remove('scale-100', 'opacity-100');
                        modalContent.classList.add('scale-95', 'opacity-0');
                        setTimeout(() => {
                            modal.classList.add('hidden');
                            document.body.style.overflow = 'auto';
                        }, 300);
                    }
                }
                
                // Reading progress functions
                function startTermsReadingProgress() {
                    const modalContent = document.getElementById('termsModalContent');
                    const content = modalContent ? modalContent.querySelector('[class*="max-h"]') : null;
                    const progressBar = document.getElementById('termsProgressBar');
                    const progressText = document.getElementById('termsProgress');
                    const checkbox = document.getElementById('termsReadCheckbox');
                    
                    if (!content || !progressBar || !progressText || !checkbox) return;
                    
                    let scrollComplete = false;
                    let timeComplete = false;
                    
                    // Time requirement (3 seconds)
                    setTimeout(() => {
                        timeComplete = true;
                        updateProgress();
                    }, 3000);
                    
                    // Scroll requirement
                    content.addEventListener('scroll', () => {
                        const scrollTop = content.scrollTop;
                        const scrollHeight = content.scrollHeight - content.clientHeight;
                        const progress = Math.min((scrollTop / scrollHeight) * 100, 100);
                        
                        progressBar.style.width = progress + '%';
                        progressText.textContent = Math.round(progress) + '%';
                        
                        if (progress >= 95) {
                            scrollComplete = true;
                            updateProgress();
                        }
                    });
                    
                    function updateProgress() {
                        if (scrollComplete && timeComplete) {
                            checkbox.disabled = false;
                            checkbox.classList.add('ring-2', 'ring-purple-500');
                            checkbox.nextElementSibling.textContent = '✓ I have read and understood the Terms of Service';
                            updateRegisterButton();
                        }
                    }
                }
                
                function startPrivacyReadingProgress() {
                    const modalContent = document.getElementById('privacyModalContent');
                    const content = modalContent ? modalContent.querySelector('[class*="max-h"]') : null;
                    const progressBar = document.getElementById('privacyProgressBar');
                    const progressText = document.getElementById('privacyProgress');
                    const checkbox = document.getElementById('privacyReadCheckbox');
                    
                    if (!content || !progressBar || !progressText || !checkbox) return;
                    
                    let scrollComplete = false;
                    let timeComplete = false;
                    
                    // Time requirement (3 seconds)
                    setTimeout(() => {
                        timeComplete = true;
                        updateProgress();
                    }, 3000);
                    
                    // Scroll requirement
                    content.addEventListener('scroll', () => {
                        const scrollTop = content.scrollTop;
                        const scrollHeight = content.scrollHeight - content.clientHeight;
                        const progress = Math.min((scrollTop / scrollHeight) * 100, 100);
                        
                        progressBar.style.width = progress + '%';
                        progressText.textContent = Math.round(progress) + '%';
                        
                        if (progress >= 95) {
                            scrollComplete = true;
                            updateProgress();
                        }
                    });
                    
                    function updateProgress() {
                        if (scrollComplete && timeComplete) {
                            checkbox.disabled = false;
                            checkbox.classList.add('ring-2', 'ring-purple-500');
                            checkbox.nextElementSibling.textContent = '✓ I have read and understood the Privacy Policy';
                            updateRegisterButton();
                        }
                    }
                }
                
                function updateRegisterButton() {
                    const termsCheckbox = document.getElementById('termsReadCheckbox');
                    const privacyCheckbox = document.getElementById('privacyReadCheckbox');
                    const mainCheckbox = document.getElementById('terms');
                    const submitBtn = document.querySelector('button[type="submit"]');
                    
                    if (termsCheckbox && privacyCheckbox && mainCheckbox) {
                        if (termsCheckbox.checked && privacyCheckbox.checked) {
                            // Auto-check the main checkbox if both have been read
                            mainCheckbox.checked = true;
                            // Do not disable submit here; password validation controls it
                        } else {
                            // Leave the main checkbox state as the user set it; never disable it
                        }
                    }
                }
                
                // Add event listeners to individual checkboxes to auto-check main checkbox
                document.addEventListener('DOMContentLoaded', function() {
                    const termsCheckbox = document.getElementById('termsReadCheckbox');
                    const privacyCheckbox = document.getElementById('privacyReadCheckbox');
                    const mainCheckbox = document.getElementById('terms');
                    
                    if (termsCheckbox) {
                        termsCheckbox.addEventListener('change', function() {
                            updateRegisterButton();
                        });
                    }
                    
                    if (privacyCheckbox) {
                        privacyCheckbox.addEventListener('change', function() {
                            updateRegisterButton();
                        });
                    }
                });
                
                function validateRegistration() {
                    const mainCheckbox = document.getElementById('terms');
                    if (!mainCheckbox.checked) {
                        showTermsAlert();
                        return false;
                    }
                    return true;
                }

                function showTermsAlert() {
                    const modal = document.getElementById('termsAlertModal');
                    if (!modal) return;
                    modal.classList.remove('hidden');
                    // animate in
                    const panel = document.getElementById('termsAlertCard');
                    panel.classList.remove('opacity-0', 'translate-y-4');
                    panel.classList.add('opacity-100', 'translate-y-0');
                    document.body.style.overflow = 'hidden';
                }

                function hideTermsAlert() {
                    const modal = document.getElementById('termsAlertModal');
                    if (!modal) return;
                    const panel = document.getElementById('termsAlertCard');
                    panel.classList.remove('opacity-100', 'translate-y-0');
                    panel.classList.add('opacity-0', 'translate-y-4');
                    setTimeout(() => {
                        modal.classList.add('hidden');
                        document.body.style.overflow = 'auto';
                    }, 200);
                }
                
                // ESC key support
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        const termsModal = document.getElementById('termsModal');
                        const privacyModal = document.getElementById('privacyModal');
                        const termsAlertModal = document.getElementById('termsAlertModal');
                        
                        if (termsModal && !termsModal.classList.contains('hidden')) {
                            closeTerms();
                        } else if (privacyModal && !privacyModal.classList.contains('hidden')) {
                            closePrivacy();
                        } else if (termsAlertModal && !termsAlertModal.classList.contains('hidden')) {
                            hideTermsAlert();
                        }
                    }
                });
                </script>

                <!-- Login Link -->
                    <div class="mt-8 text-center">
                        <p class="text-sm text-gray-600">
                            Already have an account?
                            <a href="{{ route('login') }}" class="font-bold text-primary-600 hover:text-primary-700 transition-colors">
                                Sign in here
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Professional Legal Modals -->
    <!-- Terms of Service Modal -->
    <div id="termsModal" class="fixed inset-0 z-[9999] hidden overflow-y-auto">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300" onclick="closeTerms()"></div>
        <!-- Modal Content Wrapper -->
        <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
            <div class="relative bg-white rounded-3xl shadow-2xl max-w-3xl w-full max-h-[85vh] overflow-hidden border border-gray-100 transform transition-all duration-300 scale-95 opacity-0 pointer-events-auto" id="termsModalContent">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-purple-400 to-purple-500 p-5 text-white relative">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold">Terms of Service</h3>
                                <p class="text-white/90 text-sm">Please read and understand our service terms</p>
                            </div>
                        </div>
                        <button type="button" onclick="closeTerms()" class="w-8 h-8 bg-white/20 hover:bg-white/30 rounded-lg flex items-center justify-center transition-colors relative z-10 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="p-6 max-h-[50vh] overflow-y-auto">
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-5 mb-5 border border-indigo-100">
                        <div class="text-center">
                            <h4 class="text-lg font-bold text-gray-900 mb-2">Our Commitment to You</h4>
                            <p class="text-gray-700 text-sm leading-relaxed">
                                At TraKerja, we believe every job seeker deserves a secure, transparent, and trustworthy platform. 
                                We provide comprehensive job application tracking tools while building an ecosystem that supports 
                                your career success with unwavering integrity and data security.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="bg-white border-l-4 border-blue-500 p-4 rounded-r-lg shadow-sm">
                            <h5 class="font-semibold text-gray-900 mb-1 text-sm">1. Service Agreement & Legal Framework</h5>
                            <p class="text-gray-600 text-xs">By using TraKerja, you accept and agree to be bound by these terms and conditions, governed by Indonesian Law No. 19 of 2016 concerning Electronic Information and Transactions (ITE Law) and other applicable Indonesian regulations.</p>
                        </div>

                        <div class="bg-white border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
                            <h5 class="font-semibold text-gray-900 mb-1 text-sm">2. Data Protection & Privacy Rights</h5>
                            <p class="text-gray-600 text-xs">We comply with Indonesian Government Regulation No. 71 of 2019 concerning Electronic System and Transaction Operations, ensuring your personal data is protected with AES-256 encryption and never sold to third parties.</p>
                        </div>

                        <div class="bg-white border-l-4 border-purple-500 p-4 rounded-r-lg shadow-sm">
                            <h5 class="font-semibold text-gray-900 mb-1 text-sm">3. User Rights & Responsibilities</h5>
                            <p class="text-gray-600 text-xs">Under Article 26 of ITE Law, you have the right to access, correct, and delete your personal data. You are responsible for maintaining the confidentiality of your account credentials.</p>
                        </div>

                        <div class="bg-white border-l-4 border-orange-500 p-4 rounded-r-lg shadow-sm">
                            <h5 class="font-semibold text-gray-900 mb-1 text-sm">4. Content & Intellectual Property</h5>
                            <p class="text-gray-600 text-xs">All content you upload remains your property. We respect intellectual property rights as protected under Indonesian Law No. 28 of 2014 concerning Copyright.</p>
                        </div>

                        <div class="bg-white border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                            <h5 class="font-semibold text-gray-900 mb-1 text-sm">5. Prohibited Activities</h5>
                            <p class="text-gray-600 text-xs">Users must not engage in activities that violate ITE Law Article 27-29, including defamation, hate speech, or spreading false information. Violations may result in account termination.</p>
                        </div>

                        <div class="bg-white border-l-4 border-teal-500 p-4 rounded-r-lg shadow-sm">
                            <h5 class="font-semibold text-gray-900 mb-1 text-sm">6. Dispute Resolution</h5>
                            <p class="text-gray-600 text-xs">Any disputes will be resolved through Indonesian courts in accordance with Indonesian Civil Code and ITE Law provisions.</p>
                        </div>
                    </div>

                    <!-- Reading Progress -->
                    <div class="mt-5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-medium text-gray-600">Reading Progress</span>
                            <span id="termsProgress" class="text-xs font-medium text-purple-600">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                            <div id="termsProgressBar" class="bg-gradient-to-r from-purple-400 to-purple-500 h-1.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                    <div class="flex items-center">
                        <input type="checkbox" id="termsReadCheckbox" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" disabled>
                        <label for="termsReadCheckbox" class="ml-2 text-xs text-gray-700">
                            I have read and understood the Terms of Service
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Privacy Policy Modal -->
    <div id="privacyModal" class="fixed inset-0 z-[9999] hidden overflow-y-auto">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300" onclick="closePrivacy()"></div>
        <!-- Modal Content Wrapper -->
        <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
            <div class="relative bg-white rounded-3xl shadow-2xl max-w-3xl w-full max-h-[85vh] overflow-hidden border border-gray-100 transform transition-all duration-300 scale-95 opacity-0 pointer-events-auto" id="privacyModalContent">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-purple-400 to-purple-500 p-5 text-white relative">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold">Privacy Policy</h3>
                                <p class="text-white/90 text-sm">Understand how we protect your privacy</p>
                            </div>
                        </div>
                        <button type="button" onclick="closePrivacy()" class="w-8 h-8 bg-white/20 hover:bg-white/30 rounded-lg flex items-center justify-center transition-colors relative z-10 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="p-6 max-h-[50vh] overflow-y-auto">
                    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-2xl p-5 mb-5 border border-emerald-100">
                        <div class="text-center">
                            <h4 class="text-lg font-bold text-gray-900 mb-2">Your Privacy is Our Priority</h4>
                            <p class="text-gray-700 text-sm leading-relaxed">
                                At TraKerja, we understand that your personal data is your most valuable asset. 
                                We not only protect your information but ensure that every byte of data is treated 
                                with respect, security, and complete transparency in compliance with Indonesian data protection laws.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="bg-white border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                            <h5 class="font-semibold text-gray-900 mb-1 text-sm">1. Data Protection Compliance</h5>
                            <p class="text-gray-600 text-xs">We comply with Indonesian Government Regulation No. 71 of 2019 concerning Electronic System and Transaction Operations, ensuring your personal data is never sold or misused.</p>
                        </div>

                        <div class="bg-white border-l-4 border-blue-500 p-4 rounded-r-lg shadow-sm">
                            <h5 class="font-semibold text-gray-900 mb-1 text-sm">2. Encryption & Security Standards</h5>
                            <p class="text-gray-600 text-xs">Your data is protected with AES-256 encryption, meeting international security standards and Indonesian cybersecurity requirements under ITE Law Article 26.</p>
                        </div>

                        <div class="bg-white border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
                            <h5 class="font-semibold text-gray-900 mb-1 text-sm">3. Data Subject Rights</h5>
                            <p class="text-gray-600 text-xs">Under ITE Law Article 26, you have the right to access, correct, delete, and port your personal data. We provide complete transparency in data processing activities.</p>
                        </div>

                        <div class="bg-white border-l-4 border-purple-500 p-4 rounded-r-lg shadow-sm">
                            <h5 class="font-semibold text-gray-900 mb-1 text-sm">4. Data Processing & Retention</h5>
                            <p class="text-gray-600 text-xs">We process your data only for legitimate business purposes as permitted under Indonesian Law No. 11 of 2008 concerning Electronic Information and Transactions.</p>
                        </div>

                        <div class="bg-white border-l-4 border-orange-500 p-4 rounded-r-lg shadow-sm">
                            <h5 class="font-semibold text-gray-900 mb-1 text-sm">5. Third-Party Data Sharing</h5>
                            <p class="text-gray-600 text-xs">We do not share your personal data with third parties except as required by Indonesian law or with your explicit consent, in compliance with ITE Law provisions.</p>
                        </div>

                        <div class="bg-white border-l-4 border-teal-500 p-4 rounded-r-lg shadow-sm">
                            <h5 class="font-semibold text-gray-900 mb-1 text-sm">6. Data Breach Notification</h5>
                            <p class="text-gray-600 text-xs">In case of any data breach, we will notify affected users within 72 hours as required by Indonesian Government Regulation No. 71 of 2019.</p>
                        </div>

                        <div class="bg-white border-l-4 border-indigo-500 p-4 rounded-r-lg shadow-sm">
                            <h5 class="font-semibold text-gray-900 mb-1 text-sm">7. Cross-Border Data Transfer</h5>
                            <p class="text-gray-600 text-xs">Any international data transfer complies with Indonesian regulations and ensures adequate protection of your personal data.</p>
                        </div>
                    </div>

                    <!-- Reading Progress -->
                    <div class="mt-5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-medium text-gray-600">Reading Progress</span>
                            <span id="privacyProgress" class="text-xs font-medium text-purple-600">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                            <div id="privacyProgressBar" class="bg-gradient-to-r from-purple-400 to-purple-500 h-1.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                    <div class="flex items-center">
                        <input type="checkbox" id="privacyReadCheckbox" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" disabled>
                        <label for="privacyReadCheckbox" class="ml-2 text-xs text-gray-700">
                            I have read and understood the Privacy Policy
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms Alert Modal -->
    <div id="termsAlertModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="termsAlertTitle">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm z-0" onclick="hideTermsAlert()"></div>
        <div id="termsAlertPanel" class="fixed inset-0 z-10 flex items-center justify-center p-4">
            <div id="termsAlertCard" class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden opacity-0 translate-y-4 transition-all duration-200 w-full max-w-md">
                <div class="px-6 py-5">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 5a7 7 0 100 14 7 7 0 000-14z" />
                            </svg>
                        </div>
                        <div>
                            <h3 id="termsAlertTitle" class="text-base font-semibold text-gray-900">Action Required</h3>
                            <p class="mt-1 text-sm text-gray-600">Please read and accept the Terms of Service and Privacy Policy before registering.</p>
                        </div>
                    </div>
                </div>
                <div class="px-6 pb-5 flex items-center justify-end space-x-3">
                    <button type="button" onclick="hideTermsAlert()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg border border-gray-200">Close</button>
                    <button type="button" onclick="openTerms(); hideTermsAlert();" class="px-4 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-lg">Read Terms</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function validatePassword(password) {
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                symbol: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
            };

            // Update visual indicators
            Object.keys(requirements).forEach(req => {
                const element = document.getElementById(`req-${req}`);
                const icon = element.querySelector('svg');
                const text = element.querySelector('span');
                
                if (requirements[req]) {
                    icon.classList.remove('text-gray-400');
                    icon.classList.add('text-purple-600');
                    text.classList.remove('text-gray-600');
                    text.classList.add('text-purple-600');
                } else {
                    icon.classList.remove('text-purple-600');
                    icon.classList.add('text-gray-400');
                    text.classList.remove('text-purple-600');
                    text.classList.add('text-gray-600');
                }
            });

            // Check if all requirements are met
            const allMet = Object.values(requirements).every(req => req);
            
            // Update submit button state
            const submitBtn = document.querySelector('button[type="submit"]');
            if (submitBtn) {
                if (allMet) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }
        }

        // Initialize validation on page load
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            if (passwordInput) {
                validatePassword(passwordInput.value);
            }
            
            // Handle registration success notification
            @if (session('status') === 'registration-successful')
                const notification = document.getElementById('registration-success-notification');
                if (notification) {
                    // Show notification with animation
                    setTimeout(() => {
                        notification.classList.remove('opacity-0', '-translate-y-2');
                        notification.classList.add('opacity-100', 'translate-y-0');
                    }, 100);
                    
                    // Redirect after 3 seconds
                    setTimeout(() => {
                        // Hide notification with animation
                        notification.classList.remove('opacity-100', 'translate-y-0');
                        notification.classList.add('opacity-0', '-translate-y-2');
                        
                        // Redirect after animation completes
                        setTimeout(() => {
                            window.location.href = '{{ route('dashboard') }}';
                        }, 300);
                    }, 3000);
                }
            @endif
        });
    </script>
</x-guest-layout>