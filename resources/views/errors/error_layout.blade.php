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
    <link href="https://fonts.googleapis.com/css?family=Outfit:800,900|Plus+Jakarta+Sans:300,400,500,600,700&display=swap" rel="stylesheet" />
    
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
            background: radial-gradient(350px circle at var(--x, 0px) var(--y, 0px), rgba(99, 102, 241, 0.06), transparent 85%);
            pointer-events: none;
            z-index: 1;
            transition: opacity 0.4s ease;
            opacity: 0;
        }
        .tilt-card:hover::before {
            opacity: 1;
        }
        /* Make content pop up from the 3D surface slightly */
        .tilt-card > *:not(#watermark-code) {
            transform: translateZ(10px);
        }
    </style>
</head>
<body class="antialiased text-zinc-650 selection:bg-zinc-200 selection:text-zinc-800 flex items-center justify-center min-h-screen relative bg-[#fafafa] overflow-hidden">

    <!-- Interactive Constellation Physics Canvas Grid (Goks!) -->
    <canvas id="interactive-grid" class="absolute inset-0 z-0 pointer-events-none"></canvas>

    <div class="relative z-10 w-full max-w-[430px] px-6 py-12 flex flex-col">
        
        <!-- Header -->
        <div class="flex items-center space-x-2 mb-6">
            <img src="{{ asset('images/icon.png') }}" alt="TraKerja Logo" class="w-5.5 h-5.5 object-contain">
            <span class="text-xs font-bold text-zinc-800 tracking-tight">TraKerja</span>
        </div>

        <!-- 3D Card Wrapper -->
        <div class="card-perspective w-full">
            <!-- Main Card -->
            <div class="tilt-card bg-white rounded-2xl p-7 sm:p-8 border border-zinc-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.02),0_16px_36px_-6px_rgba(0,0,0,0.03)] hover:shadow-[0_2px_8px_rgba(99,102,241,0.02),0_24px_48px_-12px_rgba(0,0,0,0.05)] overflow-hidden">
                
                <!-- Large 3D Parallax Watermark Code (Notion/Linear Graphic vibe) -->
                <div class="absolute -top-4 -right-2 text-[100px] font-black text-zinc-100/60 select-none tracking-tighter leading-none pointer-events-none transition-transform duration-75 ease-out" id="watermark-code" style="font-family: 'Outfit', sans-serif; z-index: 0;">@yield('code')</div>

                <!-- Title & Icon row -->
                <div class="flex items-start justify-between gap-4 mb-3 relative z-10">
                    <h1 class="text-xl font-bold text-zinc-800 tracking-tight leading-tight">
                        @yield('title')
                    </h1>
                    <div class="w-10 h-10 rounded-xl bg-zinc-50 border border-zinc-200/60 flex items-center justify-center text-zinc-400 shrink-0">
                        <i class="ph @yield('icon') text-xl"></i>
                    </div>
                </div>
                
                <!-- Description -->
                <p class="text-[12.5px] text-zinc-500 font-medium leading-relaxed mb-6 text-left relative z-10">
                    @yield('description')
                </p>

                <div class="border-t border-zinc-100 my-5 relative z-10"></div>
                
                <!-- Command Palette Keyboard Navigation -->
                <div class="space-y-2 relative z-10">
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
            <span>Butuh bantuan? <a href="mailto:trakerja@teknalogi.id" class="text-zinc-500 hover:text-zinc-800 underline underline-offset-2 transition-colors">Hubungi kami</a></span>
        </div>
        
    </div>

    <!-- Interactive Grid & 3D Tilt Script (Goks!) -->
    <script>
        // 1. Math Physics Canvas Grid Animation with Constellation Mesh
        const canvas = document.getElementById('interactive-grid');
        const ctx = canvas.getContext('2d');

        let width = canvas.width = window.innerWidth;
        let height = canvas.height = window.innerHeight;

        const dots = [];
        const spacing = 28;
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
            
            // First loop: Update dot positions with mouse repulsion physics
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
                
                const forceLimit = 95;
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
            }

            // Second loop: Draw constellation connection lines for close dots
            if (mouse.x !== null) {
                for (let i = 0; i < dots.length; i++) {
                    const dot1 = dots[i];
                    const dxM1 = dot1.x - mouse.x;
                    const dyM1 = dot1.y - mouse.y;
                    const distToMouse1 = Math.sqrt(dxM1 * dxM1 + dyM1 * dyM1);

                    if (distToMouse1 < 100) {
                        for (let j = i + 1; j < dots.length; j++) {
                            const dot2 = dots[j];
                            const dxM2 = dot2.x - mouse.x;
                            const dyM2 = dot2.y - mouse.y;
                            const distToMouse2 = Math.sqrt(dxM2 * dxM2 + dyM2 * dyM2);

                            if (distToMouse2 < 100) {
                                const dxDot = dot1.x - dot2.x;
                                const dyDot = dot1.y - dot2.y;
                                const distDot = Math.sqrt(dxDot * dxDot + dyDot * dyDot);

                                if (distDot < 38) {
                                    ctx.beginPath();
                                    ctx.moveTo(dot1.x, dot1.y);
                                    ctx.lineTo(dot2.x, dot2.y);
                                    ctx.strokeStyle = '#6366f1';
                                    ctx.globalAlpha = (1 - (distToMouse1 / 100)) * 0.08;
                                    ctx.lineWidth = 0.55;
                                    ctx.stroke();
                                }
                            }
                        }
                    }
                }
            }

            // Third loop: Draw grid dots
            for (let i = 0; i < dots.length; i++) {
                const dot = dots[i];
                let dist = 99999;
                
                if (mouse.x !== null) {
                    const dx = dot.x - mouse.x;
                    const dy = dot.y - mouse.y;
                    dist = Math.sqrt(dx * dx + dy * dy);
                }

                let alpha = 0.06;
                let color = '#a1a1aa';
                if (dist < 120) {
                    const factor = (1 - dist / 120);
                    alpha = 0.06 + factor * 0.28;
                    color = '#6366f1';
                }
                
                ctx.beginPath();
                ctx.arc(dot.x, dot.y, 1.25, 0, Math.PI * 2);
                ctx.fillStyle = color;
                ctx.globalAlpha = alpha;
                ctx.fill();
            }
            
            requestAnimationFrame(animate);
        }
        requestAnimationFrame(animate);

        // 2. 3D Card Parallax Tilt & Watermark Depth Translation
        const card = document.querySelector('.tilt-card');
        const watermark = document.getElementById('watermark-code');
        if (card) {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                
                // Tilt card angle calculation
                const rotateX = ((y - centerY) / centerY) * -5; // max 5deg
                const rotateY = ((x - centerX) / centerX) * 5;
                
                card.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.008, 1.008, 1.008)`;
                card.style.setProperty('--x', `${x}px`);
                card.style.setProperty('--y', `${y}px`);

                // Move watermark in opposite direction to create real 3D depth parallax!
                if (watermark) {
                    const wX = ((x - centerX) / centerX) * -12; // offset max -12px
                    const wY = ((y - centerY) / centerY) * -12;
                    watermark.style.transform = `translate3d(${wX}px, ${wY}px, -15px)`;
                }
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
                if (watermark) {
                    watermark.style.transform = 'translate3d(0, 0, 0)';
                }
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
