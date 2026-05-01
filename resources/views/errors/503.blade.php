<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Sedang Diperbarui - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-white text-slate-900 flex items-center justify-center min-h-screen p-6">
    <div class="max-w-sm w-full">
        <!-- Logo & Brand -->
        <div class="flex items-center gap-3 mb-12">
            <img src="{{ asset('images/icon.png') }}" alt="Logo" class="h-8 w-auto">
            <div class="h-6 w-px bg-slate-200"></div>
            <span class="text-xl font-extrabold tracking-tight">{{ config('app.name') }}</span>
        </div>

        <!-- Content -->
        <h1 class="text-2xl font-extrabold text-slate-900 mb-4 tracking-tight">
            Sedang Perbaikan Sistem
        </h1>
        <p class="text-slate-500 text-sm font-medium leading-relaxed mb-12">
            Kami sedang melakukan peningkatan performa rutin untuk memberikan pengalaman terbaik bagi Anda. Kami akan segera kembali dalam waktu singkat!
        </p>

        <!-- Action -->
        <div class="mb-20">
            <button onclick="window.location.reload()" class="inline-flex items-center gap-2 text-sm font-bold text-primary-600 hover:text-primary-700 transition-colors group">
                Coba Refresh Halaman
                <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </button>
        </div>

        <!-- Minimal Footer -->
        <div class="pt-8 border-t border-slate-100">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                &copy; {{ date('Y') }} {{ config('app.name') }} Management
            </p>
        </div>
    </div>
</body>
</html>
