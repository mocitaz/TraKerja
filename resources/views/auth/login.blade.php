<x-guest-layout>
    <style>
        /* Enhanced Animations */
        @keyframes gradientFlow {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        @keyframes floatUp {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(5deg); }
        }
        
        @keyframes ripple {
            0% { transform: scale(0); opacity: 1; }
            100% { transform: scale(4); opacity: 0; }
        }
        
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }
        
        @keyframes glow {
            0%, 100% { box-shadow: 0 0 20px rgba(217, 131, 228, 0.3), 0 0 40px rgba(78, 113, 197, 0.2); }
            50% { box-shadow: 0 0 30px rgba(217, 131, 228, 0.5), 0 0 60px rgba(78, 113, 197, 0.4); }
        }
        
        .gradient-animated {
            background: linear-gradient(120deg, #6b46c1, #9333ea, #4e71c5, #d983e4);
            background-size: 300% 300%;
            animation: gradientFlow 15s ease infinite;
        }
        
        .float-animation {
            animation: floatUp 6s ease-in-out infinite;
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
        
        .button-glow {
            position: relative;
            overflow: hidden;
        }
        
        .button-glow::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transform: rotate(45deg);
            animation: shimmer 3s infinite;
        }
        
        .button-glow:hover {
            animation: glow 2s ease-in-out infinite;
        }
        
        .ripple-container {
            position: relative;
            overflow: hidden;
        }
        
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.6);
            animation: ripple 0.6s ease-out;
        }
        
        .particle-float {
            animation: floatUp 8s ease-in-out infinite;
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
                    <h2 class="text-4xl font-bold mb-4 drop-shadow-lg">Welcome Back</h2>
                    <p class="text-white/90 text-xl leading-relaxed">Continue your job search journey with smart tracking and powerful insights</p>
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
                            <p class="text-white/80">Get detailed insights about your job search progress</p>
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
                            <img src="{{ asset('images/icon.png') }}" 
                                 alt="TraKerja Logo" 
                                 class="w-8 h-8"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">TraKerja</h1>
                            <p class="text-gray-600 text-sm">Smart Tracking for Job Seekers</p>
                        </div>
                    </div>
                </div>

                <!-- Form Card with Enhanced Glassmorphism -->
                <div class="glass-morphism rounded-3xl p-8 transform transition-all duration-500 hover:shadow-2xl">
                    <!-- Form Header -->
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h2>
                        <p class="text-gray-600">Sign in to continue your journey</p>
                    </div>

                    <!-- Notices -->
                    @if (session('status') === 'please-verify-email')
                        <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-xl">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 6a9 9 0 100 18 9 9 0 000-18z" />
                                </svg>
                                <p class="text-sm font-medium text-yellow-800">Registration successful. Please check your email and verify your account before logging in.</p>
                            </div>
                        </div>
                    @endif
                    @if (session('status') === 'email-not-verified')
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 6a9 9 0 100 18 9 9 0 000-18z" />
                                </svg>
                                <p class="text-sm font-medium text-red-700">Your email is not verified yet. Please verify your email from the link we sent.</p>
                            </div>
                        </div>
                    @endif

                    <!-- Form -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

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
                                       autocomplete="current-password" 
                                       required
                                       class="input-float input-glow block w-full pl-12 pr-12 py-4 border border-gray-200 rounded-xl focus:outline-none focus:ring-0 focus:border-transparent transition-all duration-300 bg-white/80 backdrop-blur-sm @error('password') border-red-300 bg-red-50/50 @enderror"
                                       placeholder="Enter your password">
                                <button type="button" 
                                        onclick="togglePassword()" 
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                    <svg id="eye-icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg id="eye-off-icon" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                    </svg>
                                </button>
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
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                            <div class="flex items-center group cursor-pointer">
                                <div class="relative">
                                    <input id="remember_me" 
                                           name="remember" 
                                           type="checkbox" 
                                           class="sr-only">
                                    <div class="w-5 h-5 border-2 border-gray-300 rounded-md flex items-center justify-center transition-all duration-200 group-hover:border-primary-500 checkbox-custom">
                                        <svg class="w-3 h-3 text-white opacity-0 transition-opacity duration-200 check-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <label for="remember_me" class="ml-3 block text-sm font-semibold text-gray-700 cursor-pointer group-hover:text-primary-600 transition-colors duration-200">
                                    Remember Me
                                </label>
                            </div>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" 
                                   class="text-sm text-primary-600 hover:text-primary-700 font-semibold transition-colors duration-200 flex items-center group self-start sm:self-auto">
                                    Forgot Password?
                                    <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            @endif
                        </div>

                        <!-- Submit Button with Glow & Ripple Effect -->
                        <button type="submit" 
                                onclick="createRipple(event)"
                                class="ripple-container button-glow w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-primary-600 to-secondary-500 hover:shadow-2xl hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-600 transition-all duration-300 relative overflow-hidden">
                            <span class="relative z-10 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                                Sign In
                            </span>
                        </button>
                    </form>

                    <!-- Register Link -->
                    <div class="mt-8 text-center">
                        <p class="text-sm text-gray-600">
                            Don't have an account?
                            <a href="{{ route('register') }}" class="font-bold text-primary-600 hover:text-primary-700 transition-colors">
                                Sign up here
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Ripple Effect
        function createRipple(event) {
            const button = event.currentTarget;
            const circle = document.createElement('span');
            const diameter = Math.max(button.clientWidth, button.clientHeight);
            const radius = diameter / 2;
            
            circle.style.width = circle.style.height = `${diameter}px`;
            circle.style.left = `${event.clientX - button.offsetLeft - radius}px`;
            circle.style.top = `${event.clientY - button.offsetTop - radius}px`;
            circle.classList.add('ripple');
            
            const ripple = button.getElementsByClassName('ripple')[0];
            if (ripple) {
                ripple.remove();
            }
            
            button.appendChild(circle);
        }
        
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeOffIcon = document.getElementById('eye-off-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        }

        // Custom checkbox functionality
        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.getElementById('remember_me');
            const customCheckbox = document.querySelector('.checkbox-custom');
            const checkIcon = document.querySelector('.check-icon');
            
            // Handle checkbox click
            customCheckbox.addEventListener('click', function() {
                checkbox.checked = !checkbox.checked;
                updateCheckboxAppearance();
            });
            
            // Handle label click
            document.querySelector('label[for="remember_me"]').addEventListener('click', function(e) {
                e.preventDefault();
                checkbox.checked = !checkbox.checked;
                updateCheckboxAppearance();
            });
            
            // Update checkbox appearance
            function updateCheckboxAppearance() {
                if (checkbox.checked) {
                    customCheckbox.classList.add('bg-primary-600', 'border-primary-600');
                    customCheckbox.classList.remove('border-gray-300');
                    checkIcon.classList.remove('opacity-0');
                } else {
                    customCheckbox.classList.remove('bg-primary-600', 'border-primary-600');
                    customCheckbox.classList.add('border-gray-300');
                    checkIcon.classList.add('opacity-0');
                }
            }
            
            // Initialize checkbox appearance
            updateCheckboxAppearance();
        });

        // Handle login success notification with delay
        @if (session('status') === 'login-successful')
            document.addEventListener('DOMContentLoaded', function() {
                const notification = document.getElementById('login-success-notification');
                if (notification) {
                    // Show notification with animation
                    setTimeout(() => {
                        notification.classList.remove('opacity-0', '-translate-y-2');
                        notification.classList.add('opacity-100', 'translate-y-0');
                    }, 100);
                    
                    // Redirect after 3 seconds (increased from 2 seconds)
                    setTimeout(() => {
                        // Hide notification with animation
                        notification.classList.remove('opacity-100', 'translate-y-0');
                        notification.classList.add('opacity-0', '-translate-y-2');
                        
                        // Redirect after animation completes
                        setTimeout(() => {
                            @if (Auth::user()->isAdmin() || Auth::user()->role === 'admin')
                                window.location.href = '{{ route('admin.index') }}';
                            @else
                                window.location.href = '{{ route('tracker') }}';
                            @endif
                        }, 300);
                    }, 3000); // 3 seconds delay
                }
            });
        @endif
    </script>
</x-guest-layout>