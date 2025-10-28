<x-guest-layout>
    <div class="min-h-screen bg-gray-50 flex">
        <!-- Left Side - Branding -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-primary-600 via-primary-700 to-secondary-500 relative overflow-hidden">
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
                    <h2 class="text-4xl font-bold mb-4 drop-shadow-lg">Verify Your Email</h2>
                    <p class="text-white/90 text-xl leading-relaxed">Check your inbox and click the verification link to activate your account</p>
                </div>
                
                <!-- Enhanced Features -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-4 p-4 bg-white/10 rounded-2xl backdrop-blur-sm border border-white/20">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">Email Verification</h3>
                            <p class="text-white/80 text-sm">Secure verification process to protect your account</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4 p-4 bg-white/10 rounded-2xl backdrop-blur-sm border border-white/20">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">Quick Activation</h3>
                            <p class="text-white/80 text-sm">Get started with your job tracking journey</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4 p-4 bg-white/10 rounded-2xl backdrop-blur-sm border border-white/20">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-7a2 2 0 00-2-2H6a2 2 0 00-2 2v7a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">Account Security</h3>
                            <p class="text-white/80 text-sm">Your account is protected with industry-standard security</p>
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

                <!-- Form Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                    <!-- Form Header -->
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Verify Your Email</h2>
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
                                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-[#d983e4] to-[#4e71c5] hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Resend Verification Email
                            </button>
                        </form>

                        <!-- Logout Button -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="text-sm text-gray-600 hover:text-gray-800 font-medium">
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
