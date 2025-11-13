<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Page Not Found | TraKerja</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #d983e4 0%, #4e71c5 100%);
        }
        .gradient-text {
            background: linear-gradient(135deg, #d983e4 0%, #4e71c5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full text-center">
            <!-- Error Code -->
            <div class="mb-6">
                <h1 class="text-8xl sm:text-9xl font-bold gradient-text mb-2">404</h1>
                <div class="w-24 h-1 bg-gradient-to-r from-[#d983e4] to-[#4e71c5] mx-auto rounded-full"></div>
            </div>

            <!-- Icon -->
            <div class="mb-6 flex justify-center">
                <div class="w-24 h-24 sm:w-28 sm:h-28 gradient-bg rounded-2xl flex items-center justify-center shadow-lg transform rotate-6 hover:rotate-0 transition-transform duration-300">
                    <svg class="w-12 h-12 sm:w-14 sm:h-14 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Message -->
            <div class="mb-8">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3">Halaman Tidak Ditemukan</h2>
                <p class="text-gray-600 text-sm sm:text-base leading-relaxed">
                    Maaf, halaman yang Anda cari tidak ada atau telah dipindahkan.
                </p>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ auth()->check() ? route('tracker') : route('login') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-[#d983e4] to-[#4e71c5] text-white font-medium rounded-lg hover:from-[#c973d4] hover:to-[#3e61b5] transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    {{ auth()->check() ? 'Kembali ke Dashboard' : 'Masuk' }}
                </a>
                <button onclick="window.history.back()" 
                        class="inline-flex items-center justify-center px-6 py-3 bg-white text-gray-700 font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </button>
            </div>

            <!-- Help Text -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-xs text-gray-500">
                    Jika Anda yakin ini adalah kesalahan, silakan <a href="mailto:support@trakerja.com" class="text-[#d983e4] hover:text-[#4e71c5] font-medium">hubungi support</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>

