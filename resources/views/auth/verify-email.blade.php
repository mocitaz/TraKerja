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
                                    <span class="text-white/85 text-[10px] xl:text-xs font-medium tracking-wide group-hover:text-white transition-colors duration-300 mr-1.5">
                                        powered by
                                    </span>
                                    <img src="{{ asset('images/teknalogi-logo.png') }}" 
                                         alt="Teknalogi Logo" 
                                         class="h-4 w-auto opacity-90 group-hover:opacity-100 transition-opacity duration-300 mr-0.5"
                                         onerror="this.style.display='none';">
                                    <span class="text-white/85 text-[10px] xl:text-xs font-semibold group-hover:text-white transition-colors duration-300">
                                        Teknalogi
                                    </span>
                                </div>
                            </div>
                            <p class="text-white/90 text-base xl:text-lg font-medium">Smart Tracking for Job Seekers</p>
                        </div>
                    </div>
                </div>
                
                <!-- Welcome Section - Compact -->
                <div class="mb-6">
                    <h2 class="text-3xl xl:text-4xl font-bold mb-2 drop-shadow-lg">Verify Your Email</h2>
                    <p class="text-white/85 text-base xl:text-lg leading-relaxed">Check your inbox and click the verification link to activate your account</p>
                </div>
                
                <!-- Enhanced Features - Compact Grid -->
                <div class="space-y-3">
                    <div class="flex items-start space-x-3 group p-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 transition-all duration-300 cursor-pointer">
                        <div class="w-10 h-10 bg-gradient-to-br from-white/25 to-white/15 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:from-white/35 group-hover:to-white/25 transition-all duration-300 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-white font-semibold text-base mb-0.5">Email Verification</h3>
                            <p class="text-white/75 text-sm leading-snug">Secure verification process to protect your account</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3 group p-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 transition-all duration-300 cursor-pointer">
                        <div class="w-10 h-10 bg-gradient-to-br from-white/25 to-white/15 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:from-white/35 group-hover:to-white/25 transition-all duration-300 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-white font-semibold text-base mb-0.5">Quick Activation</h3>
                            <p class="text-white/75 text-sm leading-snug">Get started with your job tracking journey</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3 group p-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 transition-all duration-300 cursor-pointer">
                        <div class="w-10 h-10 bg-gradient-to-br from-white/25 to-white/15 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:from-white/35 group-hover:to-white/25 transition-all duration-300 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-7a2 2 0 00-2-2H6a2 2 0 00-2 2v7a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-white font-semibold text-base mb-0.5">Account Security</h3>
                            <p class="text-white/75 text-sm leading-snug">Your account is protected with industry-standard security</p>
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
                        <div class="flex items-center px-2 py-0.5 rounded-md bg-gray-100/80 backdrop-blur-sm border border-gray-200/50 hover:bg-gray-100 transition-all duration-300 group">
                            <span class="text-gray-600 text-[10px] font-medium tracking-wide group-hover:text-gray-700 transition-colors duration-300 mr-1.5">
                                powered by
                            </span>
                            <img src="{{ asset('images/teknalogi-logo.png') }}" 
                                 alt="Teknalogi Logo" 
                                 class="h-3.5 w-auto opacity-90 group-hover:opacity-100 transition-opacity duration-300 mr-0.5"
                                 onerror="this.style.display='none';">
                            <span class="text-gray-600 text-[10px] font-semibold group-hover:text-gray-700 transition-colors duration-300">
                                Teknalogi
                            </span>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm">Smart Tracking for Job Seekers</p>
                </div>

                <!-- Form Card with Enhanced Glassmorphism -->
                <div class="glass-morphism rounded-3xl p-8 transform transition-all duration-500 hover:shadow-2xl">
                    <!-- Form Header -->
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Verify Your Email</h2>
                        <p class="text-gray-600">Check your inbox for the verification link</p>
                    </div>

                    @if (session('status') == 'verification-link-sent')
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-800 font-medium">
                                        A new verification link has been sent to your email address.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="text-center space-y-6">
                        <!-- Email Icon -->
                        <div class="mx-auto w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>

                        <!-- Instructions -->
                        <div class="space-y-3">
                            <h3 class="text-lg font-semibold text-gray-900">Check Your Email</h3>
                            <p class="text-sm text-gray-600">
                                We've sent a verification link to <strong>{{ auth()->user()->email }}</strong>
                            </p>
                            <p class="text-sm text-gray-600">
                                Click the link in the email to verify your account and start using TraKerja.
                            </p>
                        </div>

                        <!-- Resend Button -->
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex justify-center items-center py-3 px-5 border border-transparent rounded-lg shadow-md text-sm font-semibold text-white bg-gradient-to-r from-purple-600 via-purple-500 to-purple-400 hover:from-purple-700 hover:via-purple-600 hover:to-purple-500 hover:shadow-xl hover:scale-[1.02] active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500/50 transition-all duration-200 relative overflow-hidden group">
                                <svg class="w-4 h-4 mr-2 group-hover:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Resend Verification Email
                            </button>
                        </form>

                        <!-- Logout Button -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="text-sm text-gray-600 hover:text-gray-800 font-medium transition-colors duration-200 hover:underline">
                                Sign out and use different account
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Help Section -->
                <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-600/20 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 000-18z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900">Need Help?</h4>
                            <p class="text-xs text-gray-600 mt-1">
                                Check your spam folder if you don't see the email. 
                                The verification link will expire in 60 minutes for security.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
