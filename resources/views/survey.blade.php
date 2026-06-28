<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>User Feedback - {{ config('app.name', 'TraKerja') }}</title>
        
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        
        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <script src="https://unpkg.com/@phosphor-icons/web"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
        </style>
    </head>
    <body class="text-zinc-800 antialiased bg-zinc-50/50 flex items-center justify-center min-h-screen p-4">
        
        <div class="w-full max-w-md bg-white border border-zinc-200 rounded-lg p-5 sm:p-6 shadow-sm space-y-6">
            
            {{-- Header --}}
            <div class="text-center space-y-2">
                <div class="w-10 h-10 bg-purple-50 rounded border border-purple-150 flex items-center justify-center text-purple-650 mx-auto">
                    <i class="ph-bold ph-heart text-xl"></i>
                </div>
                <div>
                    <h1 class="text-base font-bold text-zinc-900 tracking-tight">Bantu Kami Meningkatkan TraKerja</h1>
                    <p class="text-[11px] text-zinc-450 max-w-xs mx-auto leading-relaxed mt-0.5">Tanggapan Anda sangat berharga bagi kami untuk menyempurnakan platform ini.</p>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('survey.submit') }}" method="POST" class="space-y-5">
                @csrf
                
                {{-- Overall Satisfaction --}}
                <div class="space-y-2">
                    <label class="block text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider">
                        1. Kepuasan Keseluruhan
                    </label>
                    <p class="text-[11px] text-zinc-550 leading-none">Seberapa puas Anda dengan layanan TraKerja secara keseluruhan?</p>
                    
                    <div class="flex items-center justify-between gap-1.5 pt-1.5">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="flex-1">
                                <input type="radio" name="score" value="{{ $i }}" class="sr-only peer" required {{ old('score') == $i ? 'checked' : '' }}>
                                <div class="py-2 text-center rounded border border-zinc-200 text-xs font-mono font-bold text-zinc-500 bg-zinc-50 hover:bg-zinc-100 peer-checked:bg-zinc-900 peer-checked:text-white peer-checked:border-zinc-900 transition-all cursor-pointer">
                                    {{ $i }}
                                </div>
                            </label>
                        @endfor
                    </div>
                    <div class="flex justify-between text-[9px] font-mono text-zinc-400 px-1 pt-0.5">
                        <span>Sangat Tidak Puas</span>
                        <span>Sangat Puas</span>
                    </div>
                    @error('score')
                        <p class="text-[10px] text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ease of Use --}}
                <div class="space-y-2">
                    <label class="block text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider">
                        2. Kemudahan Penggunaan
                    </label>
                    <p class="text-[11px] text-zinc-550 leading-none">Seberapa mudah Anda menggunakan Resume Builder & Job Tracker?</p>
                    
                    <div class="flex items-center justify-between gap-1.5 pt-1.5">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="flex-1">
                                <input type="radio" name="ease_of_use" value="{{ $i }}" class="sr-only peer" required {{ old('ease_of_use') == $i ? 'checked' : '' }}>
                                <div class="py-2 text-center rounded border border-zinc-200 text-xs font-mono font-bold text-zinc-500 bg-zinc-50 hover:bg-zinc-100 peer-checked:bg-zinc-900 peer-checked:text-white peer-checked:border-zinc-900 transition-all cursor-pointer">
                                    {{ $i }}
                                </div>
                            </label>
                        @endfor
                    </div>
                    <div class="flex justify-between text-[9px] font-mono text-zinc-400 px-1 pt-0.5">
                        <span>Sangat Sulit</span>
                        <span>Sangat Mudah</span>
                    </div>
                    @error('ease_of_use')
                        <p class="text-[10px] text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Helpfulness of AI --}}
                <div class="space-y-2">
                    <label class="block text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider">
                        3. Kegunaan Fitur AI
                    </label>
                    <p class="text-[11px] text-zinc-550 leading-none">Bagaimana Anda menilai kegunaan AI Analyzer dalam mereview resume Anda?</p>
                    
                    <div class="flex items-center justify-between gap-1.5 pt-1.5">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="flex-1">
                                <input type="radio" name="features_helpful" value="{{ $i }}" class="sr-only peer" required {{ old('features_helpful') == $i ? 'checked' : '' }}>
                                <div class="py-2 text-center rounded border border-zinc-200 text-xs font-mono font-bold text-zinc-500 bg-zinc-50 hover:bg-zinc-100 peer-checked:bg-zinc-900 peer-checked:text-white peer-checked:border-zinc-900 transition-all cursor-pointer">
                                    {{ $i }}
                                </div>
                            </label>
                        @endfor
                    </div>
                    <div class="flex justify-between text-[9px] font-mono text-zinc-400 px-1 pt-0.5">
                        <span>Sangat Buruk</span>
                        <span>Sangat Berguna</span>
                    </div>
                    @error('features_helpful')
                        <p class="text-[10px] text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Written Feedback --}}
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider">
                        4. Saran & Masukan (Opsional)
                    </label>
                    <textarea name="feedback" rows="3" placeholder="Tuliskan saran perbaikan atau fitur baru yang Anda inginkan..."
                              class="w-full px-3 py-2 bg-zinc-50 border border-zinc-200 focus:bg-white focus:ring-1 focus:ring-zinc-900 focus:border-zinc-900 rounded-md text-xs text-zinc-800 transition-all">{{ old('feedback') }}</textarea>
                    @error('feedback')
                        <p class="text-[10px] text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2 bg-zinc-900 hover:bg-zinc-800 text-white rounded-md text-xs font-bold uppercase tracking-wider transition-colors">
                        Kirim Tanggapan
                    </button>
                </div>
            </form>

        </div>

    </body>
</html>
