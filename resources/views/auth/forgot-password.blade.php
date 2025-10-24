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
                    <h2 class="text-4xl font-bold mb-4 drop-shadow-lg">Lupa Password?</h2>
                    <p class="text-white/90 text-xl leading-relaxed">Jangan khawatir! Kami akan membantu Anda reset password dengan cepat dan aman</p>
                </div>
                
                <!-- Enhanced Features -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-4 group">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:bg-white/30 transition-all duration-300 group-hover:scale-110">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-white font-semibold text-lg">Verifikasi Email Aman</h3>
                            <p class="text-white/80">Kami akan mengirim link reset yang aman ke email Anda</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4 group">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:bg-white/30 transition-all duration-300 group-hover:scale-110">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-white font-semibold text-lg">Pemulihan Cepat</h3>
                            <p class="text-white/80">Reset password Anda hanya dalam beberapa menit</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4 group">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:bg-white/30 transition-all duration-300 group-hover:scale-110">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-white font-semibold text-lg">Perlindungan Akun</h3>
                            <p class="text-white/80">Keamanan akun Anda adalah prioritas utama kami</p>
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
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Lupa Password?</h2>
                        <p class="text-gray-600">Masukkan alamat email Anda dan kami akan mengirimkan link reset password</p>
                    </div>

                <!-- Status Messages -->
                <x-auth-session-status class="mb-6" :status="session('status')" />

                <!-- Form -->
                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
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
                                   class="block w-full pl-12 pr-4 py-4 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-600 focus:border-transparent transition-all duration-200 bg-gray-50/50 @error('email') border-red-300 bg-red-50/50 @enderror"
                                   placeholder="Masukkan alamat email Anda">
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

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-primary-600 to-secondary-500 hover:shadow-xl hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-600 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Kirim Link Reset
                    </button>
                </form>

                <!-- Back to Login -->
                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-600">
                        Sudah ingat password?
                        <a href="{{ route('login') }}" class="font-bold text-primary-600 hover:text-primary-700 transition-colors">
                            Kembali ke login
                        </a>
                    </p>
                </div>

                <!-- Help Information -->
                <div class="mt-6 bg-primary-50/50 border border-primary-200 rounded-xl p-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900">Butuh Bantuan?</h4>
                            <p class="text-xs text-gray-600 mt-1">
                                Jika Anda tidak menerima email dalam beberapa menit, cek folder spam. 
                                Link reset akan kadaluarsa dalam 60 menit untuk keamanan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>