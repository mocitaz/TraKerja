<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code') - @yield('title') | TraKerja</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('images/icon.png') }}?v=2">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Plus+Jakarta+Sans:300,400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- App assets (Tailwind CSS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #fafafa;
        }
        [x-cloak] { display: none !important; }

        /* 3D Card Parallax & Interactive Border Glow */
        .card-perspective {
            perspective: 1200px;
        }
        .tilt-card {
            transform-style: preserve-3d;
            transition: transform 0.1s cubic-bezier(0.25, 1, 0.5, 1), box-shadow 0.2s ease;
            position: relative;
        }
        .tilt-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(350px circle at var(--x, 0px) var(--y, 0px), rgba(99, 102, 241, 0.05), transparent 80%);
            pointer-events: none;
            z-index: 1;
            transition: opacity 0.4s ease;
            opacity: 0;
        }
        .tilt-card:hover::before {
            opacity: 1;
        }
        /* Make content pop up from the 3D surface slightly */
        .tilt-card > * {
            transform: translateZ(10px);
        }
    </style>
</head>
<body class="antialiased text-zinc-650 selection:bg-zinc-200 selection:text-zinc-800 flex items-center justify-center min-h-screen relative bg-[#fafafa] overflow-hidden">

    <!-- Interactive Mathematical Physics Dot Grid Canvas (Goks!) -->
    <canvas id="interactive-grid" class="absolute inset-0 z-0 pointer-events-none"></canvas>

    <div class="relative z-10 w-full max-w-[430px] px-6 py-12 flex flex-col">
        
        <!-- Header -->
        <div class="flex items-center space-x-2.5 mb-6">
            <div class="w-7 h-7 bg-white rounded-lg border border-zinc-200/80 flex items-center justify-center p-1.5 shadow-[0_1px_2px_rgba(0,0,0,0.03)]">
                <img src="{{ asset('images/icon.png') }}" alt="TraKerja Logo" class="w-full h-full object-contain">
            </div>
            <span class="text-xs font-bold text-zinc-800 tracking-tight">TraKerja</span>
        </div>

        <!-- 3D Card Wrapper -->
        <div class="card-perspective w-full">
            <!-- Main Card -->
            <div class="tilt-card bg-white rounded-2xl p-7 sm:p-8 border border-zinc-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.02),0_16px_36px_-6px_rgba(0,0,0,0.03)] hover:shadow-[0_2px_8px_rgba(99,102,241,0.02),0_24px_48px_-12px_rgba(0,0,0,0.05)] overflow-hidden">
                
                <!-- Tech Terminal style command line -->
                <div class="flex items-center space-x-2 font-mono text-[10px] text-zinc-500 bg-zinc-50 border border-zinc-200/60 rounded-xl px-3.5 py-2.5 mb-6">
                    <span class="text-emerald-500 font-bold select-none">$</span>
                    <span class="flex-1">trakerja --error=@yield('code')</span>
                    <span class="w-1.5 h-3 bg-zinc-400 animate-[pulse_1s_infinite]"></span>
                </div>

                <!-- Title & Icon row -->
                <div class="flex items-start justify-between gap-4 mb-3">
                    <h1 class="text-xl font-bold text-zinc-800 tracking-tight leading-tight">
                        @yield('title')
                    </h1>
                    <div class="w-10 h-10 rounded-xl bg-zinc-50 border border-zinc-200/60 flex items-center justify-center text-zinc-400 shrink-0">
                        <i class="ph @yield('icon') text-xl"></i>
                    </div>
                </div>
                
                <!-- Description -->
                <p class="text-[12.5px] text-zinc-500 font-medium leading-relaxed mb-6 text-left">
                    @yield('description')
                </p>

                <div class="border-t border-zinc-100 my-5"></div>
                
                <!-- Command Palette Keyboard Navigation -->
                <div class="space-y-2">
                    <span class="block text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-3">Pintasan Navigasi</span>
                    
                    <!-- Home Action -->
                    <a href="{{ url('/') }}" class="flex items-center justify-between px-4 py-3 bg-zinc-50 hover:bg-zinc-900 text-zinc-700 hover:text-white border border-zinc-200/80 rounded-xl transition-all duration-150 group">
                        <div class="flex items-center space-x-2.5">
                            <i class="ph ph-house text-zinc-400 group-hover:text-zinc-300 text-base"></i>
                            <span class="text-xs font-bold">Pergi ke Beranda</span>
                        </div>
                        <kbd class="font-mono text-[9px] font-bold text-zinc-400 group-hover:text-zinc-500 bg-white group-hover:bg-zinc-850 border border-zinc-250 group-hover:border-zinc-700 rounded px-1.5 py-0.5 shadow-[0_1px_1px_rgba(0,0,0,0.03)] select-none">H</kbd>
                    </a>
                    
                    <!-- Back Action -->
                    <button onclick="window.history.back()" class="w-full flex items-center justify-between px-4 py-3 bg-zinc-50 hover:bg-zinc-900 text-zinc-700 hover:text-white border border-zinc-200/80 rounded-xl transition-all duration-150 group">
                        <div class="flex items-center space-x-2.5">
                            <i class="ph ph-arrow-left text-zinc-400 group-hover:text-zinc-300 text-base"></i>
                            <span class="text-xs font-bold">Kembali ke Halaman Sebelumnya</span>
                        </div>
                        <kbd class="font-mono text-[9px] font-bold text-zinc-400 group-hover:text-zinc-500 bg-white group-hover:bg-zinc-850 border border-zinc-250 group-hover:border-zinc-700 rounded px-1.5 py-0.5 shadow-[0_1px_1px_rgba(0,0,0,0.03)] select-none">B</kbd>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="mt-6 text-left px-1 flex justify-between items-center text-[9px] font-semibold text-zinc-400">
            <span>TraKerja &copy; {{ date('Y') }}</span>
            <span>Butuh bantuan? <a href="mailto:support@trakerja.com" class="text-zinc-500 hover:text-zinc-800 underline underline-offset-2 transition-colors">Hubungi kami</a></span>
        </div>
        
    </div>

    <!-- Interactive Grid & 3D Tilt Script (Goks!) -->
    <script>
        // 1. Math Physics Canvas Grid Animation
        const canvas = document.getElementById('interactive-grid');
        const ctx = canvas.getContext('2d');

        let width = canvas.width = window.innerWidth;
        let height = canvas.height = window.innerHeight;

        const dots = [];
        const spacing = 26;
        let rows = Math.ceil(height / spacing);
        let cols = Math.ceil(width / spacing);

        function initGrid() {
            dots.length = 0;
            rows = Math.ceil(height / spacing);
            cols = Math.ceil(width / spacing);
            for (let r = 0; r < rows; r++) {
                for (let c = 0; c < cols; c++) {
                    dots.push({
                        originX: c * spacing + (spacing / 2),
                        originY: r * spacing + (spacing / 2),
                        x: c * spacing + (spacing / 2),
                        y: r * spacing + (spacing / 2),
                    });
                }
            }
        }
        initGrid();

        let mouse = { x: null, y: null };
        window.addEventListener('mousemove', (e) => {
            mouse.x = e.clientX;
            mouse.y = e.clientY;
        });
        window.addEventListener('mouseleave', () => {
            mouse.x = null;
            mouse.y = null;
        });
        window.addEventListener('resize', () => {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
            initGrid();
        });

        function animate() {
            ctx.clearRect(0, 0, width, height);
            
            for (let i = 0; i < dots.length; i++) {
                const dot = dots[i];
                let dx = 0;
                let dy = 0;
                let dist = 99999;
                
                if (mouse.x !== null) {
                    dx = dot.x - mouse.x;
                    dy = dot.y - mouse.y;
                    dist = Math.sqrt(dx * dx + dy * dy);
                }
                
                const forceLimit = 90;
                if (dist < forceLimit) {
                    const force = (forceLimit - dist) / forceLimit;
                    const angle = Math.atan2(dy, dx);
                    
                    const targetX = dot.originX + Math.cos(angle) * force * 18;
                    const targetY = dot.originY + Math.sin(angle) * force * 18;
                    
                    dot.x += (targetX - dot.x) * 0.18;
                    dot.y += (targetY - dot.y) * 0.18;
                } else {
                    dot.x += (dot.originX - dot.x) * 0.1;
                    dot.y += (dot.originY - dot.y) * 0.1;
                }
                
                let alpha = 0.07;
                let color = '#a1a1aa';
                if (dist < 120) {
                    const factor = (1 - dist / 120);
                    alpha = 0.07 + factor * 0.28;
                    color = '#6366f1';
                }
                
                ctx.beginPath();
                ctx.arc(dot.x, dot.y, 1.2, 0, Math.PI * 2);
                ctx.fillStyle = color;
                ctx.globalAlpha = alpha;
                ctx.fill();
            }
            
            requestAnimationFrame(animate);
        }
        requestAnimationFrame(animate);

        // 2. 3D Card Parallax Tilt & Mouse Light Tracking
        const card = document.querySelector('.tilt-card');
        if (card) {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                
                const rotateX = ((y - centerY) / centerY) * -5.5; // max 5.5deg
                const rotateY = ((x - centerX) / centerX) * 5.5;
                
                card.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.008, 1.008, 1.008)`;
                card.style.setProperty('--x', `${x}px`);
                card.style.setProperty('--y', `${y}px`);
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
            });
        }

        // 3. Keyboard Shortcuts Listener
        document.addEventListener('keydown', function(event) {
            if (event.target.tagName === 'INPUT' || event.target.tagName === 'TEXTAREA') {
                return;
            }
            const key = event.key.toLowerCase();
            if (key === 'h') {
                window.location.href = "{{ url('/') }}";
            } else if (key === 'b') {
                window.history.back();
            }
        });
    </script>

</body>
</html>
