<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>503 - Service Unavailable | TraKerja</title>
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
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .animate-pulse-slow {
            animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full text-center">
            <!-- Error Code -->
            <div class="mb-6">
                <h1 class="text-8xl sm:text-9xl font-bold gradient-text mb-2">503</h1>
                <div class="w-24 h-1 bg-gradient-to-r from-[#d983e4] to-[#4e71c5] mx-auto rounded-full"></div>
            </div>

            <!-- Icon -->
            <div class="mb-6 flex justify-center">
                <div class="w-24 h-24 sm:w-28 sm:h-28 gradient-bg rounded-2xl flex items-center justify-center shadow-lg transform animate-pulse-slow">
                    <svg class="w-12 h-12 sm:w-14 sm:h-14 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Message -->
            <div class="mb-8">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3">Sedang Dalam Perawatan</h2>
                <p class="text-gray-600 text-sm sm:text-base leading-relaxed">
                    Kami sedang melakukan pemeliharaan untuk meningkatkan layanan. Kami akan kembali segera.
                </p>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <button onclick="window.location.reload()" 
                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-[#d983e4] to-[#4e71c5] text-white font-medium rounded-lg hover:from-[#c973d4] hover:to-[#3e61b5] transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Coba Lagi
                </button>
                <a href="mailto:support@trakerja.com" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-white text-gray-700 font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Hubungi Support
                </a>
            </div>

            <!-- Help Text -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-xs text-gray-500">
                    Perkiraan waktu perawatan: 15-30 menit. Terima kasih atas kesabaran Anda.
                </p>
            </div>
        </div>
    </div>
</body>
</html>

