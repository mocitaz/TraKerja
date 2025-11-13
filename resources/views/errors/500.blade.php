<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 - Server Error | TraKerja</title>
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
                <h1 class="text-8xl sm:text-9xl font-bold gradient-text mb-2">500</h1>
                <div class="w-24 h-1 bg-gradient-to-r from-[#d983e4] to-[#4e71c5] mx-auto rounded-full"></div>
            </div>

            <!-- Icon -->
            <div class="mb-6 flex justify-center">
                <div class="w-24 h-24 sm:w-28 sm:h-28 gradient-bg rounded-2xl flex items-center justify-center shadow-lg transform -rotate-6 hover:rotate-0 transition-transform duration-300">
                    <svg class="w-12 h-12 sm:w-14 sm:h-14 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>

            <!-- Message -->
            <div class="mb-8">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3">Kesalahan Server</h2>
                <p class="text-gray-600 text-sm sm:text-base leading-relaxed">
                    Maaf, terjadi kesalahan pada server. Tim kami telah diberitahu dan sedang memperbaikinya.
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
                <button onclick="window.location.reload()" 
                        class="inline-flex items-center justify-center px-6 py-3 bg-white text-gray-700 font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Muat Ulang
                </button>
            </div>

            <!-- Help Text -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-xs text-gray-500">
                    Jika masalah berlanjut, silakan <a href="mailto:support@trakerja.com" class="text-[#d983e4] hover:text-[#4e71c5] font-medium">hubungi support</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>

