<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TraKerja - Platform Manajemen & Pelacakan Lamaran Kerja #1 di Indonesia</title>
    <meta name="description" content="TraKerja adalah platform ATS & tracker lamaran kerja gratis. Pantau status lamaran, buat CV standar ATS, dan dapatkan insight analitik untuk karir impian Anda.">
    <meta name="keywords" content="loker, lowongan kerja, tracker lamaran kerja, ats checker, cv ats friendly, karir, hrd, job portal, trakerja, manajemen lamaran">
    <meta name="author" content="PT. Teknalogi Transformasi Digital">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/') }}">
    <meta name="theme-color" content="#d983e4">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="TraKerja - Platform Manajemen & Pelacakan Lamaran Kerja">
    <meta property="og:description" content="Tingkatkan peluang lolos kerja dengan tracker cerdas, AI Cover Letter, dan analitik lengkap. Gratis untuk pencari kerja Indonesia.">
    <meta property="og:image" content="{{ asset('images/og-image.png') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url('/') }}">
    <meta property="twitter:title" content="TraKerja - Aplikasi Pelacakan Karir & Pekerjaan">
    <meta property="twitter:description" content="Pantau status lamaran, buat CV standar ATS, dan analisis skor interview Anda.">
    <meta property="twitter:image" content="{{ asset('images/og-image.png') }}">

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

    <!-- Schema.org / JSON-LD -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "SoftwareApplication",
      "name": "TraKerja",
      "operatingSystem": "WebBrowser",
      "applicationCategory": "BusinessApplication",
      "offers": {
        "@@type": "Offer",
        "price": "0",
        "priceCurrency": "IDR"
      },
      "description": "TraKerja adalah platform ATS & tracker lamaran kerja gratis di Indonesia. Pantau status lamaran, buat CV standar ATS, dan dapatkan insight analitik untuk karir impian Anda.",
      "url": "{{ url('/') }}"
    }
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
            <style>
            :root {
                --primary: #d983e4;
                --secondary: #4e71c5;
                --accent: #d946ef;
                --dark: #0f172a;
                --light: #f8fafc;
            }

            * {
                scroll-behavior: smooth;
            }

            body {
                overflow-x: hidden;
            }

        .gradient-text {
                background: linear-gradient(135deg, #d983e4 0%, #4e71c5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
                background-size: 200% 200%;
                animation: gradientShift 3s ease-in-out infinite;
        }

        .hero-gradient {
                background: linear-gradient(135deg, #d983e4 0%, #4e71c5 100%);
                background-size: 200% 200%;
                animation: gradientShift 8s ease-in-out infinite;
        }

        .floating-animation {
                animation: float 6s ease-in-out infinite;
        }

        .pulse-animation {
                animation: pulse 3s ease-in-out infinite;
        }

        .glow-effect {
                box-shadow: 0 0 60px rgba(139, 92, 246, 0.4);
                transition: all 0.3s ease;
            }

            .glass-effect {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                transition: all 0.3s ease;
            }

            .premium-shadow {
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                transition: all 0.3s ease;
            }

            .cyber-grid {
                background-image: 
                    linear-gradient(rgba(139, 92, 246, 0.1) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(139, 92, 246, 0.1) 1px, transparent 1px);
                background-size: 50px 50px;
                animation: gridMove 20s linear infinite;
            }

            .neon-glow {
                box-shadow: 
                    0 0 20px rgba(139, 92, 246, 0.3),
                    0 0 40px rgba(139, 92, 246, 0.2),
                    0 0 60px rgba(139, 92, 246, 0.1);
            }

            .holographic {
                background: linear-gradient(45deg, 
                    rgba(139, 92, 246, 0.8) 0%, 
                    rgba(109, 40, 217, 0.6) 25%, 
                    rgba(168, 85, 247, 0.8) 50%, 
                    rgba(139, 92, 246, 0.6) 75%, 
                    rgba(139, 92, 246, 0.8) 100%);
                background-size: 400% 400%;
                animation: holographic 4s ease-in-out infinite;
            }

            /* Enhanced Scroll Animations - Smooth & Modern */
            .scroll-fade-in {
                opacity: 0;
                transform: translateY(40px);
                transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1), 
                            transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .scroll-fade-in.visible {
                opacity: 1;
                transform: translateY(0);
            }

            .scroll-slide-left {
                opacity: 0;
                transform: translateX(-50px);
                transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1), 
                            transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .scroll-slide-left.visible {
                opacity: 1;
                transform: translateX(0);
            }

            .scroll-slide-right {
                opacity: 0;
                transform: translateX(50px);
                transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1), 
                            transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .scroll-slide-right.visible {
                opacity: 1;
                transform: translateX(0);
            }

            .scroll-scale {
                opacity: 0;
                transform: scale(0.85);
                transition: opacity 0.8s cubic-bezier(0.34, 1.56, 0.64, 1), 
                            transform 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
            }

            .scroll-scale.visible {
                opacity: 1;
                transform: scale(1);
            }

            .scroll-rotate {
                opacity: 0;
                transform: translateY(30px) rotate(-5deg);
                transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1), 
                            transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .scroll-rotate.visible {
                opacity: 1;
                transform: translateY(0) rotate(0deg);
            }

            .scroll-blur {
                opacity: 0;
                filter: blur(10px);
                transform: translateY(20px);
                transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1), 
                            transform 0.8s cubic-bezier(0.4, 0, 0.2, 1),
                            filter 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .scroll-blur.visible {
                opacity: 1;
                filter: blur(0);
                transform: translateY(0);
            }

            .scroll-bounce {
                opacity: 0;
                transform: translateY(50px) scale(0.9);
                transition: opacity 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55), 
                            transform 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            }

            .scroll-bounce.visible {
                opacity: 1;
                transform: translateY(0) scale(1);
            }

            .scroll-flip {
                opacity: 0;
                transform: perspective(1000px) rotateX(20deg) translateY(30px);
                transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1), 
                            transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .scroll-flip.visible {
                opacity: 1;
                transform: perspective(1000px) rotateX(0deg) translateY(0);
            }

            /* Stagger animations for grid items */
            .scroll-stagger > * {
                opacity: 0;
                transform: translateY(30px);
                transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1), 
                            transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .scroll-stagger.visible > *:nth-child(1) { transition-delay: 0.1s; }
            .scroll-stagger.visible > *:nth-child(2) { transition-delay: 0.2s; }
            .scroll-stagger.visible > *:nth-child(3) { transition-delay: 0.3s; }
            .scroll-stagger.visible > *:nth-child(4) { transition-delay: 0.4s; }
            .scroll-stagger.visible > *:nth-child(5) { transition-delay: 0.5s; }
            .scroll-stagger.visible > *:nth-child(6) { transition-delay: 0.6s; }
            .scroll-stagger.visible > *:nth-child(7) { transition-delay: 0.7s; }
            .scroll-stagger.visible > *:nth-child(8) { transition-delay: 0.8s; }

            .scroll-stagger.visible > * {
                opacity: 1;
                transform: translateY(0);
            }

            /* Delay classes */
            .scroll-delay-100 { transition-delay: 0.1s; }
            .scroll-delay-200 { transition-delay: 0.2s; }
            .scroll-delay-300 { transition-delay: 0.3s; }
            .scroll-delay-400 { transition-delay: 0.4s; }
            .scroll-delay-500 { transition-delay: 0.5s; }
            .scroll-delay-600 { transition-delay: 0.6s; }

            .morphing-blob {
                border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
                animation: morph 8s ease-in-out infinite;
            }

            .text-reveal {
                opacity: 0;
                transform: translateY(30px);
                animation: textReveal 0.8s ease-out forwards;
            }

            .feature-card {
                transform: translateY(0);
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .feature-card:hover {
                transform: translateY(-10px) scale(1.02);
                box-shadow: 0 25px 50px -12px rgba(139, 92, 246, 0.25);
            }

            .testimonial-card {
                transform: perspective(1000px) rotateX(0deg);
                transition: all 0.4s ease;
            }

            .testimonial-card:hover {
                transform: perspective(1000px) rotateX(5deg) translateY(-5px);
            }

            .cta-button {
                position: relative;
                overflow: hidden;
                transition: all 0.3s ease;
            }

            .cta-button::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
                transition: left 0.5s;
            }

            .cta-button:hover::before {
                left: 100%;
            }

            .cyber-border {
                position: relative;
                border: 2px solid transparent;
                background: linear-gradient(45deg, #d983e4, #4e71c5) border-box;
                border-radius: 12px;
            }

            .cyber-border::before {
                content: '';
                position: absolute;
                inset: 0;
                padding: 2px;
                background: linear-gradient(45deg, #d983e4, #4e71c5);
                border-radius: inherit;
                mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
                mask-composite: exclude;
            }

            @keyframes gradientShift {
                0%, 100% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
            }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(2deg); }
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.8; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.05); }
        }

            @keyframes gridMove {
                0% { transform: translate(0, 0); }
                100% { transform: translate(50px, 50px); }
            }

            @keyframes holographic {
                0%, 100% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
            }

            @keyframes morph {
                0%, 100% { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; }
                25% { border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%; }
                50% { border-radius: 50% 30% 60% 40% / 30% 50% 60% 70%; }
                75% { border-radius: 40% 70% 30% 60% / 70% 40% 50% 30%; }
            }

            @keyframes textReveal {
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .parallax-bg {
                background-attachment: fixed;
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
            }

            .scroll-indicator {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 4px;
                background: linear-gradient(90deg, #d983e4, #4e71c5);
                transform-origin: left;
                z-index: 50;
            }

            .typing-animation {
                overflow: hidden;
                border-right: 2px solid var(--primary);
                white-space: nowrap;
                animation: typing 3s steps(40, end), blink-caret 0.75s step-end infinite;
            }

            @keyframes typing {
                from { width: 0; }
                to { width: 100%; }
            }

            @keyframes blink-caret {
                from, to { border-color: transparent; }
                50% { border-color: var(--primary); }
            }

            .loading-dots {
                display: inline-block;
            }

            .loading-dots::after {
                content: '';
                animation: dots 1.5s infinite;
            }

            @keyframes dots {
                0%, 20% { content: ''; }
                40% { content: '.'; }
                60% { content: '..'; }
                80%, 100% { content: '...'; }
            }

            /* Mobile Optimizations */
            @media (max-width: 768px) {
                /* Improve text rendering on mobile */
                body {
                    -webkit-font-smoothing: antialiased;
                    -moz-osx-font-smoothing: grayscale;
                    text-rendering: optimizeLegibility;
                }
                
                .hero-section {
                    min-height: 100vh;
                    padding: 2rem 1rem;
                }
                
                .hero-title {
                    font-size: 2.5rem;
                    line-height: 1.2;
                    -webkit-font-smoothing: antialiased;
                    -moz-osx-font-smoothing: grayscale;
                }
                
                .hero-subtitle {
                    font-size: 1.125rem;
                    line-height: 1.6;
                    -webkit-font-smoothing: antialiased;
                    -moz-osx-font-smoothing: grayscale;
                }
                
                .feature-card {
                    padding: 1.5rem;
                }
                
                .testimonial-card {
                    padding: 1.5rem;
                }
                
                .cta-buttons {
                    flex-direction: column;
                    gap: 1rem;
                }
                
                .cta-button {
                    width: 100%;
                    text-align: center;
                }
                
                .trust-indicators {
                    flex-direction: column;
                    gap: 1rem;
                    text-align: center;
                }
                
                /* Reduce blur effects on mobile for better performance */
                .floating-animation {
                    display: none;
                }
                
                .cyber-grid {
                    opacity: 0.05;
                }
                
                /* Optimize background elements for mobile */
                .morphing-blob {
                    filter: blur(1px) !important;
                }
                
                .glass-effect {
                    backdrop-filter: blur(5px);
                    background: rgba(255, 255, 255, 0.8);
                }
                
                /* Improve text clarity */
                .gradient-text {
                    -webkit-font-smoothing: antialiased;
                    -moz-osx-font-smoothing: grayscale;
                }
                
                /* Reduce complex animations on mobile */
                .pulse-animation {
                    animation-duration: 4s;
                }
                
                .floating-animation {
                    animation-duration: 8s;
                }
                
                /* Optimize hero section for mobile */
                .hero-section {
                    background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 50%, #f0fdf4 100%) !important;
                }
                
                /* Remove blur effects from gradient text on mobile */
                .gradient-text {
                    background: linear-gradient(135deg, #d983e4 0%, #4e71c5 100%);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                    filter: none !important;
                }
                
                /* Optimize badge for mobile */
                .inline-flex.items-center.px-6.py-3.rounded-full {
                    background: rgba(255, 255, 255, 0.95) !important;
                    backdrop-filter: none !important;
                }
                
                /* Optimize trust indicators for mobile */
                .trust-indicators .flex.items-center {
                    background: rgba(255, 255, 255, 0.9) !important;
                    backdrop-filter: none !important;
                }
            }

            /* ========== ADVANCED ANIMATIONS & EFFECTS ========== */
            
            /* 3D Tilt Card Effect */
            .card-3d {
                transform-style: preserve-3d;
                transition: transform 0.6s cubic-bezier(0.23, 1, 0.32, 1);
            }
            
            .card-3d:hover {
                transform: perspective(1000px) rotateX(var(--rotate-x, 0)) rotateY(var(--rotate-y, 0)) scale3d(1.05, 1.05, 1.05);
            }
            
            .card-3d-inner {
                transform: translateZ(50px);
            }
            
            /* Magnetic Button Effect */
            .magnetic-button {
                position: relative;
                transition: transform 0.3s cubic-bezier(0.23, 1, 0.32, 1);
            }
            
            .magnetic-button:hover {
                transform: translate(var(--magnetic-x, 0), var(--magnetic-y, 0));
            }
            
            /* Stagger Animation for Cards */
            @keyframes slideUpFade {
                from {
                    opacity: 0;
                    transform: translateY(60px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            .stagger-item {
                opacity: 0;
                animation: slideUpFade 0.8s cubic-bezier(0.23, 1, 0.32, 1) forwards;
            }
            
            .stagger-item:nth-child(1) { animation-delay: 0.1s; }
            .stagger-item:nth-child(2) { animation-delay: 0.2s; }
            .stagger-item:nth-child(3) { animation-delay: 0.3s; }
            .stagger-item:nth-child(4) { animation-delay: 0.4s; }
            .stagger-item:nth-child(5) { animation-delay: 0.5s; }
            .stagger-item:nth-child(6) { animation-delay: 0.6s; }
            
            /* Animated Gradient Mesh Background */
            @keyframes gradientMesh {
                0%, 100% {
                    background-position: 0% 50%;
                    background-size: 200% 200%;
                }
                50% {
                    background-position: 100% 50%;
                    background-size: 220% 220%;
                }
            }
            
            .gradient-mesh {
                background: linear-gradient(
                    120deg,
                    #f8fafc 0%,
                    #e0f2fe 20%,
                    #f0fdf4 40%,
                    #fef3f2 60%,
                    #f8fafc 80%,
                    #e0f2fe 100%
                );
                background-size: 200% 200%;
                animation: gradientMesh 15s ease infinite;
            }
            
            /* Particle Background */
            .particles {
                position: absolute;
                inset: 0;
                overflow: hidden;
                pointer-events: none;
            }
            
            .particle {
                position: absolute;
                background: radial-gradient(circle, rgba(217, 131, 228, 0.3) 0%, transparent 70%);
                border-radius: 50%;
                animation: particleFloat 20s linear infinite;
            }
            
            @keyframes particleFloat {
                0% {
                    transform: translateY(100vh) scale(0);
                    opacity: 0;
                }
                10% {
                    opacity: 1;
                }
                90% {
                    opacity: 1;
                }
                100% {
                    transform: translateY(-100vh) scale(1);
                    opacity: 0;
                }
            }
            
            .particle:nth-child(1) { left: 10%; animation-duration: 25s; animation-delay: 0s; }
            .particle:nth-child(2) { left: 20%; animation-duration: 30s; animation-delay: 2s; }
            .particle:nth-child(3) { left: 30%; animation-duration: 22s; animation-delay: 4s; }
            .particle:nth-child(4) { left: 40%; animation-duration: 28s; animation-delay: 1s; }
            .particle:nth-child(5) { left: 50%; animation-duration: 26s; animation-delay: 3s; }
            .particle:nth-child(6) { left: 60%; animation-duration: 24s; animation-delay: 5s; }
            .particle:nth-child(7) { left: 70%; animation-duration: 27s; animation-delay: 2.5s; }
            .particle:nth-child(8) { left: 80%; animation-duration: 23s; animation-delay: 1.5s; }
            .particle:nth-child(9) { left: 90%; animation-duration: 29s; animation-delay: 3.5s; }
            
            /* Enhanced Glassmorphism */
            .glass-enhanced {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(20px) saturate(180%);
                -webkit-backdrop-filter: blur(20px) saturate(180%);
                border: 1px solid rgba(255, 255, 255, 0.3);
                box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            }
            
            /* Glow Effect on Hover */
            .glow-effect {
                position: relative;
                overflow: hidden;
            }
            
            .glow-effect::before {
                content: '';
                position: absolute;
                inset: -2px;
                background: linear-gradient(45deg, #d983e4, #4e71c5, #d983e4);
                background-size: 200% 200%;
                border-radius: inherit;
                opacity: 0;
                z-index: -1;
                transition: opacity 0.5s;
                animation: glowRotate 3s linear infinite;
            }
            
            .glow-effect:hover::before {
                opacity: 0.6;
            }
            
            @keyframes glowRotate {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            
            /* Text Gradient Animation */
            .gradient-text-animated {
                background: linear-gradient(90deg, #d983e4, #4e71c5, #d983e4, #4e71c5);
                background-size: 300% 100%;
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                animation: gradientTextFlow 5s linear infinite;
            }
            
            @keyframes gradientTextFlow {
                0% { background-position: 0% 50%; }
                100% { background-position: 300% 50%; }
            }
            
            /* Scroll Reveal Animation */
            .scroll-reveal {
                opacity: 0;
                transform: translateY(50px);
                transition: opacity 0.8s cubic-bezier(0.23, 1, 0.32, 1), 
                            transform 0.8s cubic-bezier(0.23, 1, 0.32, 1);
            }
            
            .scroll-reveal.revealed {
                opacity: 1;
                transform: translateY(0);
            }
            
            /* Spotlight Effect */
            .spotlight-effect {
                position: relative;
                overflow: hidden;
            }
            
            .spotlight-effect::after {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, transparent 50%);
                opacity: 0;
                transition: opacity 0.5s;
                pointer-events: none;
                transform: translate(var(--spotlight-x, 50%), var(--spotlight-y, 50%));
            }
            
            .spotlight-effect:hover::after {
                opacity: 1;
            }
            
            /* Liquid Blob Animation */
            @keyframes liquidBlob {
                0%, 100% {
                    border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
                    transform: rotate(0deg) scale(1);
                }
                25% {
                    border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%;
                    transform: rotate(90deg) scale(1.1);
                }
                50% {
                    border-radius: 50% 30% 60% 40% / 30% 50% 60% 70%;
                    transform: rotate(180deg) scale(0.9);
                }
                75% {
                    border-radius: 40% 70% 30% 60% / 70% 40% 50% 30%;
                    transform: rotate(270deg) scale(1.05);
                }
            }
            
            .liquid-blob {
                animation: liquidBlob 20s ease-in-out infinite;
            }
            
            /* Parallax Effect */
            .parallax-layer {
                transition: transform 0.3s cubic-bezier(0.23, 1, 0.32, 1);
            }
            
            /* Hover Lift Effect */
            .hover-lift {
                transition: transform 0.4s cubic-bezier(0.23, 1, 0.32, 1), 
                            box-shadow 0.4s cubic-bezier(0.23, 1, 0.32, 1);
            }
            
            .hover-lift:hover {
                transform: translateY(-12px);
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            }
            
            /* Number Counter Animation */
            @keyframes countUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            .count-up {
                animation: countUp 1s ease-out;
            }

            /* Responsive animations */
            @media (prefers-reduced-motion: reduce) {
                *, *::before, *::after {
                    animation-duration: 0.01ms !important;
                    animation-iteration-count: 1 !important;
                    transition-duration: 0.01ms !important;
                }
            }

            /* ========== NEW CREATIVE DESIGN STYLES ========== */
            
            /* Diagonal Section */
            .diagonal-section {
                position: relative;
                transform: skewY(-2deg);
                margin-top: -50px;
                margin-bottom: -50px;
            }
            
            .diagonal-section > * {
                transform: skewY(2deg);
            }
            
            /* Asymmetric Layout */
            .asymmetric-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 2rem;
            }
            
            .asymmetric-grid > :nth-child(odd) {
                margin-top: 2rem;
            }
            
            /* Glass Card with Border Gradient */
            .glass-card-gradient {
                background: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(20px);
                border: 2px solid transparent;
                background-clip: padding-box;
                position: relative;
            }
            
            .glass-card-gradient::before {
                content: '';
                position: absolute;
                inset: 0;
                border-radius: inherit;
                padding: 2px;
                background: linear-gradient(135deg, #d983e4, #4e71c5, #d983e4);
                -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
                -webkit-mask-composite: xor;
                mask-composite: exclude;
                opacity: 0;
                transition: opacity 0.3s;
            }
            
            .glass-card-gradient:hover::before {
                opacity: 1;
            }
            
            /* Floating Badge */
            .floating-badge {
                position: relative;
                animation: floatBadge 3s ease-in-out infinite;
            }
            
            @keyframes floatBadge {
                0%, 100% { transform: translateY(0) rotate(0deg); }
                50% { transform: translateY(-10px) rotate(2deg); }
            }
            
            /* Gradient Border Animation */
            .gradient-border {
                position: relative;
                background: white;
                border-radius: 1rem;
            }
            
            .gradient-border::before {
                content: '';
                position: absolute;
                inset: -2px;
                border-radius: inherit;
                padding: 2px;
                background: linear-gradient(45deg, #d983e4, #4e71c5, #d983e4);
                background-size: 200% 200%;
                -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
                -webkit-mask-composite: xor;
                mask-composite: exclude;
                animation: gradientBorder 3s linear infinite;
            }
            
            @keyframes gradientBorder {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            
            /* Split Screen Effect */
            .split-screen {
                position: relative;
                overflow: hidden;
            }
            
            .split-screen::before {
                content: '';
                position: absolute;
                top: 0;
                left: 50%;
                width: 2px;
                height: 100%;
                background: linear-gradient(to bottom, transparent, #d983e4, transparent);
                opacity: 0.3;
            }
            
            /* Neon Text Effect */
            .neon-text {
                text-shadow: 
                    0 0 10px rgba(217, 131, 228, 0.5),
                    0 0 20px rgba(217, 131, 228, 0.3),
                    0 0 30px rgba(217, 131, 228, 0.2);
            }
            
            /* 3D Card Flip */
            .card-flip {
                perspective: 1000px;
            }
            
            .card-flip-inner {
                transition: transform 0.6s;
                transform-style: preserve-3d;
            }
            
            .card-flip:hover .card-flip-inner {
                transform: rotateY(5deg) rotateX(5deg);
            }
            
            /* Mesh Gradient Background */
            .mesh-gradient {
                background: 
                    radial-gradient(at 0% 0%, rgba(217, 131, 228, 0.15) 0px, transparent 50%),
                    radial-gradient(at 100% 0%, rgba(78, 113, 197, 0.15) 0px, transparent 50%),
                    radial-gradient(at 100% 100%, rgba(217, 131, 228, 0.1) 0px, transparent 50%),
                    radial-gradient(at 0% 100%, rgba(78, 113, 197, 0.1) 0px, transparent 50%);
            }
            
            /* Animated Underline */
            .animated-underline {
                position: relative;
                display: inline-block;
            }
            
            .animated-underline::after {
                content: '';
                position: absolute;
                bottom: -4px;
                left: 0;
                width: 0;
                height: 3px;
                background: linear-gradient(90deg, #d983e4, #4e71c5);
                transition: width 0.5s ease;
            }
            
            .animated-underline:hover::after {
                width: 100%;
            }
            
            /* Glowing Icon */
            .glowing-icon {
                filter: drop-shadow(0 0 8px rgba(217, 131, 228, 0.6));
                transition: filter 0.3s;
            }
            
            .glowing-icon:hover {
                filter: drop-shadow(0 0 16px rgba(217, 131, 228, 0.9));
            }
            
            /* Staggered Reveal */
            .stagger-reveal {
                opacity: 0;
                transform: translateY(30px);
                animation: staggerReveal 0.6s ease-out forwards;
            }
            
            @keyframes staggerReveal {
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            /* Blob Shape */
            .blob-shape {
                border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
                animation: blobShape 8s ease-in-out infinite;
            }
            
            @keyframes blobShape {
                0%, 100% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
                25% { border-radius: 70% 30% 30% 70% / 70% 70% 30% 30%; }
                50% { border-radius: 30% 70% 70% 30% / 70% 30% 30% 70%; }
                75% { border-radius: 70% 30% 30% 70% / 30% 70% 70% 30%; }
            }
            
            /* Text Glow on Hover */
            .text-glow {
                transition: text-shadow 0.3s;
            }
            
            .text-glow:hover {
                text-shadow: 0 0 20px rgba(217, 131, 228, 0.5);
            }
            
            /* Floating Elements */
            .float-element {
                animation: floatElement 6s ease-in-out infinite;
            }
            
            @keyframes floatElement {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-20px) rotate(5deg); }
            }
            
            /* Gradient Text with Animation */
            .gradient-text-animated-2 {
                background: linear-gradient(90deg, #d983e4 0%, #4e71c5 50%, #d983e4 100%);
                background-size: 200% auto;
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                animation: gradientText 3s linear infinite;
            }
            
            @keyframes gradientText {
                to { background-position: 200% center; }
            }
            
            /* Card Hover Lift with Shadow */
            .card-lift {
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            }
            
            .card-lift:hover {
                transform: translateY(-8px) scale(1.02);
                box-shadow: 0 20px 40px rgba(217, 131, 228, 0.2);
            }
            
            /* Section Divider */
            .section-divider {
                height: 1px;
                background: linear-gradient(90deg, transparent, rgba(217, 131, 228, 0.5), transparent);
                margin: 4rem 0;
            }
            
            /* Pulse Ring */
            .pulse-ring {
                position: relative;
            }
            
            .pulse-ring::before {
                content: '';
                position: absolute;
                inset: -8px;
                border: 2px solid rgba(217, 131, 228, 0.5);
                border-radius: inherit;
                animation: pulseRing 2s ease-out infinite;
            }
            
            @keyframes pulseRing {
                0% {
                    transform: scale(1);
                    opacity: 1;
                }
                100% {
                    transform: scale(1.3);
                    opacity: 0;
                }
            }
        }
            </style>
    </head>
<body class="font-sans antialiased bg-white">
    <div id="welcome-popup" class="fixed inset-0 z-[100] flex items-center justify-center hidden opacity-0 transition-opacity duration-300 bg-black/60 backdrop-blur-sm px-4">
        <div class="relative bg-white rounded-2xl shadow-2xl overflow-hidden max-w-md md:max-w-lg w-full transform scale-95 transition-transform duration-300" id="welcome-popup-content">
            
            <button onclick="closeWelcomePopup()" class="absolute top-3 right-3 z-10 w-8 h-8 flex items-center justify-center bg-black/20 hover:bg-black/40 text-white rounded-full backdrop-blur-md transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            
            <img src="{{ asset('images/msg.png') }}" 
                 alt="Welcome Message" 
                 class="w-full h-auto block" 
                 onerror="this.src='https://placehold.co/600x400/d983e4/ffffff?text=Pengumuman'">
                 
        </div>
    </div>
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 bg-white/80 backdrop-blur-xl border-b border-gray-100/50 shadow-sm z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand -->
                <a href="{{ url('/') }}" class="flex items-center space-x-2 group">
                    <img src="{{ asset('images/icon.png') }}" 
                         alt="TraKerja" 
                         class="w-8 h-8 transform group-hover:scale-110 transition-transform duration-300"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="w-8 h-8 bg-[#d983e4] rounded-lg flex items-center justify-center transform group-hover:scale-110 transition-transform duration-300" style="display: none;">
                        <span class="text-white font-bold text-sm">T</span>
                    </div>
                    <span class="text-xl font-bold bg-gradient-to-r from-[#d983e4] to-[#4e71c5] bg-clip-text text-transparent">
                        TraKerja
                    </span>
                </a>

                <!-- Auth Links -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/tracker') }}" 
                               class="px-3 sm:px-4 py-2 bg-[#4e71c5] text-white rounded-lg text-xs sm:text-sm font-semibold hover:bg-[#3d5ba3] hover:shadow-md transition-all duration-200 flex items-center">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span class="hidden sm:inline">Dashboard</span>
                                <span class="sm:hidden">Dash</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                            class="px-3 sm:px-4 py-2 bg-white border border-[#b165c7] text-[#b165c7] rounded-lg text-xs sm:text-sm font-semibold hover:bg-[#f9f2fc] hover:shadow-md transition-all duration-200">
                                Login
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" 
                                   class="px-3 sm:px-4 py-2 bg-gradient-to-r from-[#d983e4] to-[#4e71c5] text-white rounded-lg text-xs sm:text-sm font-semibold hover:from-[#c973d4] hover:to-[#3d5ba3] hover:shadow-md transition-all duration-200">
                                    <span class="hidden sm:inline">Daftar Gratis</span>
                                    <span class="sm:hidden">Daftar</span>
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section - Split Layout -->
    <section class="relative py-24 lg:min-h-screen lg:flex lg:items-center overflow-hidden">
        <!-- Animated Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-transparent to-blue-50"></div>
        <div class="absolute inset-0 cyber-grid opacity-[0.03] hidden md:block"></div>
        
        <!-- Floating Blobs -->
        <div class="absolute top-20 right-10 w-72 h-72 bg-gradient-to-br from-[#d983e4]/20 to-[#4e71c5]/20 rounded-full blur-3xl floating-animation hidden md:block"></div>
        <div class="absolute bottom-20 left-10 w-96 h-96 bg-gradient-to-br from-[#4e71c5]/15 to-[#d983e4]/15 rounded-full blur-3xl floating-animation hidden md:block" style="animation-delay: 2s;"></div>
        
        <!-- Content - Split Layout -->
        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                <!-- Left Side - Text Content -->
                <div class="text-center lg:text-left space-y-6">
                    <!-- Badge -->
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/80 backdrop-blur-sm border border-gray-200/50 text-xs font-medium text-gray-700 mb-2 shadow-sm hover:shadow-md transition-all duration-300 scroll-bounce">
                        <span class="w-1.5 h-1.5 bg-gradient-to-r from-[#d983e4] to-[#4e71c5] rounded-full pulse-animation"></span>
                        <span>#1 Job Tracking Platform Indonesia</span>
                    </div>
                    
                    <!-- Main Heading -->
                    <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold text-gray-900 mb-4 leading-tight scroll-fade-in">
                        Kelola <span class="gradient-text-animated"><br>Lamaran<br></span> Lebih Cerdas
                    </h1>
                    
                    <!-- Subheading -->
                    <p class="text-base sm:text-lg md:text-xl text-gray-600 mb-8 max-w-xl mx-auto lg:mx-0 scroll-blur">
                        Platform pelacakan lamaran kerja yang 
                        <span class="font-semibold text-[#d983e4]">sederhana</span>, 
                        <span class="font-semibold text-[#4e71c5]">efektif</span>, dan 
                        <span class="font-semibold text-gray-900">handal</span>
                        untuk pencari kerja Indonesia.
                    </p>
                    
                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row items-center lg:items-start gap-3 mb-8">
                        @auth
                            <a href="{{ url('/tracker') }}" 
                               class="group relative px-6 py-3 bg-gradient-to-r from-[#d983e4] to-[#4e71c5] text-white rounded-xl font-bold text-sm hover:shadow-xl hover:scale-105 transition-all duration-300 overflow-hidden">
                                <span class="relative z-10 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    Buka Dashboard
                                </span>
                                <div class="absolute inset-0 bg-white/20 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
                            </a>
                        @else
                            <a href="{{ route('register') }}" 
                               class="group relative px-6 py-3 bg-gradient-to-r from-[#d983e4] to-[#4e71c5] text-white rounded-xl font-bold text-sm hover:shadow-xl hover:scale-105 transition-all duration-300 overflow-hidden">
                                <span class="relative z-10 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    Mulai Gratis
                                </span>
                                <div class="absolute inset-0 bg-white/20 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
                            </a>
                            <a href="{{ route('login') }}" 
                               class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold text-sm hover:border-[#d983e4] hover:text-[#d983e4] hover:bg-[#d983e4]/5 transition-all duration-300">
                                Login
                            </a>
                        @endauth
                    </div>
                    
                    <!-- Trust Indicators -->
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 sm:gap-5 mt-2">
                        
                        <div class="group flex items-center gap-2.5 cursor-default">
                            <div class="flex items-center justify-center w-7 h-7 rounded-full bg-[#4e71c5]/10 text-[#4e71c5] ring-1 ring-[#4e71c5]/20 group-hover:bg-[#4e71c5] group-hover:text-white group-hover:scale-110 transition-all duration-300">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-gray-600 group-hover:text-gray-900 transition-colors duration-300">
                                Setup <span class="text-[#4e71c5]">Instan</span>
                            </span>
                        </div>

                        <div class="group flex items-center gap-2.5 cursor-default">
                            <div class="flex items-center justify-center w-7 h-7 rounded-full bg-[#d983e4]/10 text-[#d983e4] ring-1 ring-[#d983e4]/20 group-hover:bg-[#d983e4] group-hover:text-white group-hover:scale-110 transition-all duration-300">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-gray-600 group-hover:text-gray-900 transition-colors duration-300">
                                Privasi <span class="text-[#d983e4]">Aman</span>
                            </span>
                        </div>

                        <div class="group flex items-center gap-2.5 cursor-default">
                            <div class="flex items-center justify-center w-7 h-7 rounded-full bg-gray-100 text-gray-500 ring-1 ring-gray-200 group-hover:bg-gray-800 group-hover:text-white group-hover:scale-110 transition-all duration-300">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8h18M5 12h2m4 0h6m-12 8h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-gray-600 group-hover:text-gray-900 transition-colors duration-300">
                                Tanpa Kartu Kredit
                            </span>
                        </div>

                    </div>
                </div>
                
                <!-- Right Side - Photo Display -->
                <div class="relative hidden md:flex justify-center lg:justify-end">
                    <img src="{{ asset('images/mu-trakerja.png') }}" 
                        alt="TraKerja Hero" 
                        class="w-full max-w-2xl lg:max-w-3xl h-auto scale-110 lg:scale-125 drop-shadow-2xl"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                    <!-- Fallback jika foto tidak ada -->
                    <div class="w-full aspect-video bg-gradient-to-br from-[#d983e4]/20 to-[#4e71c5]/20 flex items-center justify-center rounded-2xl" style="display: none;">
                        <p class="text-gray-500 text-sm">
                            Tambahkan foto di: public/images/mu-trakerja.png
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>


    {{-- Features Section - Moka Style --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">

                {{-- Kiri: Visual / Foto --}}
                <div class="relative scroll-slide-left">
                    {{-- Background blob --}}
                    <div class="absolute -z-10 transform -rotate-1"></div>
                    
                    {{-- Ganti dengan gambar screenshot atau mockup kamu --}}
                    {{-- Contoh: <img src="{{ asset('images/mockup-dashboard.png') }}" ...> --}}
                    <div class="relative overflow-hidden ">
                        <img src="{{ asset('images/fitur-section.jpg') }}" 
                            alt="TraKerja Dashboard Mockup" 
                            class="w-full h-auto block"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                        {{-- Floating card kecil di pojok --}}
                        <div class="absolute bottom-5 left-5 bg-white rounded-xl px-4 py-3 shadow-lg flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-800">12 Lamaran Aktif</p>
                                <p class="text-xs text-gray-400">diperbarui baru saja</p>
                            </div>
                        </div>

                        {{-- Floating card kedua --}}
                        <div class="absolute top-5 right-5 bg-white rounded-xl px-4 py-3 shadow-lg flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-[#d983e4]"></div>
                            <p class="text-xs font-bold text-gray-700">Skor ATS: 92/100</p>
                        </div>
                    </div>
                </div>

                {{-- Kanan: Teks Fitur --}}
                <div class="scroll-slide-right">
                    {{-- Label --}}
                    <p class="text-xs font-bold tracking-widest uppercase text-[#4e71c5] mb-2">Platform Lengkap</p>

                    {{-- Heading --}}
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 leading-tight mb-2">
                        Semua yang Kamu Butuhkan,<br>
                        <span class="text-[#d983e4]">Dalam Satu Tempat</span>
                    </h2>

                    {{-- Subtext --}}
                    <p class="text-gray-500 text-sm leading-relaxed mb-6">
                        TraKerja hadir dengan fitur-fitur lengkap untuk membantu pencarian kerja kamu lebih terorganisir dan efektif.
                    </p>

                    {{-- Daftar Fitur - 2 kolom grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-10 mb-10">

                    {{-- Fitur 01 --}}
                    <div class="relative group">
                        <div class="absolute -top-8 -left-4 text-[5rem] font-black text-gray-50 group-hover:text-[#4e71c5]/5 transition-colors duration-500 z-0 select-none pointer-events-none tracking-tighter">01</div>
                        <div class="relative z-10">
                            <div class="flex items-center gap-4 mb-3">
                                <div class="w-12 h-12 rounded-2xl bg-white shadow-md shadow-gray-200/50 border border-gray-100 flex items-center justify-center transform group-hover:-translate-y-1 transition-transform duration-300">
                                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="4" y="4" width="16" height="16" rx="2" stroke="#4e71c5" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M8 10V16" stroke="#d983e4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M12 8V16" stroke="#d983e4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M16 12V16" stroke="#d983e4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 leading-tight group-hover:text-[#4e71c5] transition-colors">Kanban<br>Board</h3>
                            </div>
                            <p class="text-sm text-gray-600 leading-relaxed pr-4">Sistem visual <em>drag-and-drop</em> untuk memantau status setiap lamaran tanpa pusing.</p>
                        </div>
                    </div>

                    {{-- Fitur 02 --}}
                    <div class="relative group">
                        <div class="absolute -top-8 -left-4 text-[5rem] font-black text-gray-50 group-hover:text-[#d983e4]/5 transition-colors duration-500 z-0 select-none pointer-events-none tracking-tighter">02</div>
                        <div class="relative z-10">
                            <div class="flex items-center gap-4 mb-3">
                                <div class="w-12 h-12 rounded-2xl bg-white shadow-md shadow-gray-200/50 border border-gray-100 flex items-center justify-center transform group-hover:-translate-y-1 transition-transform duration-300">
                                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M14 2H6C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2Z" stroke="#4e71c5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M14 2V8H20" stroke="#4e71c5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M9 13L10.5 15.5L15 10" stroke="#d983e4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 leading-tight group-hover:text-[#d983e4] transition-colors">AI CV<br>Analyzer</h3>
                            </div>
                            <p class="text-sm text-gray-600 leading-relaxed pr-4">Dapatkan skor instan dan saran cerdas agar CV Anda lolos sistem filter ATS HRD.</p>
                        </div>
                    </div>

                    {{-- Fitur 03 --}}
                    <div class="relative group">
                        <div class="absolute -top-8 -left-4 text-[5rem] font-black text-gray-50 group-hover:text-[#4e71c5]/5 transition-colors duration-500 z-0 select-none pointer-events-none tracking-tighter">03</div>
                        <div class="relative z-10">
                            <div class="flex items-center gap-4 mb-3">
                                <div class="w-12 h-12 rounded-2xl bg-white shadow-md shadow-gray-200/50 border border-gray-100 flex items-center justify-center transform group-hover:-translate-y-1 transition-transform duration-300">
                                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="12" cy="12" r="9" stroke="#4e71c5" stroke-width="2"/>
                                        <path d="M12 3V12H21" stroke="#d983e4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 leading-tight group-hover:text-[#4e71c5] transition-colors">Data &<br>Analytics</h3>
                            </div>
                            <p class="text-sm text-gray-600 leading-relaxed pr-4">Pahami pola panggilan *interview* Anda melalui grafik dan wawasan statistik otomatis.</p>
                        </div>
                    </div>

                    {{-- Fitur 04 --}}
                    <div class="relative group">
                        <div class="absolute -top-8 -left-4 text-[5rem] font-black text-gray-50 group-hover:text-[#d983e4]/5 transition-colors duration-500 z-0 select-none pointer-events-none tracking-tighter">04</div>
                        <div class="relative z-10">
                            <div class="flex items-center gap-4 mb-3">
                                <div class="w-12 h-12 rounded-2xl bg-white shadow-md shadow-gray-200/50 border border-gray-100 flex items-center justify-center transform group-hover:-translate-y-1 transition-transform duration-300">
                                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12" stroke="#4e71c5" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M12 12L16 8" stroke="#d983e4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <circle cx="12" cy="12" r="2" fill="#d983e4"/>
                                        <circle cx="16" cy="8" r="2" fill="#4e71c5"/>
                                        <circle cx="8" cy="16" r="2" fill="#4e71c5"/>
                                        <path d="M12 12L8 16" stroke="#4e71c5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 leading-tight group-hover:text-[#d983e4] transition-colors">Target<br>Tracker</h3>
                            </div>
                            <p class="text-sm text-gray-600 leading-relaxed pr-4">Tetapkan target mingguan, tingkatkan konsistensi, dan amankan pekerjaan impian Anda.</p>
                        </div>
                    </div>
                    {{-- CTA --}}
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 text-[#4e71c5] font-bold text-sm hover:gap-4 transition-all duration-200">
                        Mulai Gratis Sekarang
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- Problem Solution Section -->
    <section class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="reveal">
                    <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-6 tracking-tight">
                        Bingung Kelola <span class="text-slate-400 line-through decoration-red-500">Lamaran Kerja?</span>
                    </h2>
                    <p class="text-slate-600 mb-8 text-lg">Pencari kerja seringkali kesulitan mengorganisir lamaran yang sudah dikirim. Akibatnya banyak yang terlewat follow-up atau lupa status lamaran.</p>
                    
                    <ul class="space-y-4">
                        <li class="flex items-center gap-3 text-slate-700">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-red-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </div>
                            Lupa sudah apply di mana saja
                        </li>
                        <li class="flex items-center gap-3 text-slate-700">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-red-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </div>
                            Tidak tahu kapan harus follow-up
                        </li>
                        <li class="flex items-center gap-3 text-slate-700">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-red-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </div>
                            Tidak ada tracking progress yang jelas
                        </li>
                    </ul>
                </div>

                <div class="bg-white p-8 sm:p-10 rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 reveal">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-50 text-green-700 text-sm font-medium mb-6">
                        Solusi
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">TraKerja Solusinya!</h3>
                    <p class="text-slate-600 mb-8">Dirancang khusus untuk pencari kerja Indonesia. Sederhana, efektif, dan mudah digunakan.</p>
                    
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3 text-slate-700 font-medium">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-green-100 flex items-center justify-center mt-0.5">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            Semua lamaran terorganisir dalam satu dashboard
                        </li>
                        <li class="flex items-start gap-3 text-slate-700 font-medium">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-green-100 flex items-center justify-center mt-0.5">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            Smart reminder untuk follow-up tepat waktu
                        </li>
                        <li class="flex items-start gap-3 text-slate-700 font-medium">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-green-100 flex items-center justify-center mt-0.5">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            Analytics untuk optimasi strategi lamaran
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section - Compact & Soft Design -->
    <section class="py-16 sm:py-24 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 scroll-fade-in">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 leading-tight">
                    Mitra Pencarian Kerja <span class="text-[#d983e4]">Terbaik</span> Anda di <br class="hidden sm:block" /> Indonesia
                </h2>
                <p class="mt-4 text-gray-500 text-sm sm:text-base max-w-2xl mx-auto">
                    Berkomitmen membantu Anda menemukan pekerjaan impian dengan lebih terorganisir dan cerdas.
                </p>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center mb-16 scroll-fade-in scroll-delay-100">
                <div>
                    <h3 class="text-4xl sm:text-5xl font-bold text-[#d983e4] mb-2">>200</h3>
                    <p class="text-gray-500 text-sm sm:text-base">pengguna aktif</p>
                </div>
                
                <div>
                    <h3 class="text-4xl sm:text-5xl font-bold text-[#d983e4] mb-2">>700</h3>
                    <p class="text-gray-500 text-sm sm:text-base">lamaran disimpan</p>
                </div>
                
                <div>
                    <h3 class="text-4xl sm:text-5xl font-bold text-[#d983e4] mb-2">100%</h3>
                    <p class="text-gray-500 text-sm sm:text-base">gratis digunakan</p>
                </div>
                
                <div>
                    <h3 class="text-4xl sm:text-5xl font-bold text-[#d983e4] mb-2">24/7</h3>
                    <p class="text-gray-500 text-sm sm:text-base">akses tracking real-time</p>
                </div>
            </div>

            <div class="text-center scroll-fade-in scroll-delay-200">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-[#d983e4] hover:bg-[#c06bc5] transition-colors shadow-sm hover:shadow-md">
                    Mulai Gunakan Sekarang
                    <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>

        </div>
    </section>

    <!-- Pricing Section -->
    <section class="py-16 sm:py-24 bg-gradient-to-b from-white to-slate-50 relative" id="pricing">
        
        <style>
            @keyframes float-slow {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-8px); }
            }
            .animate-float-slow {
                animation: float-slow 4s ease-in-out infinite;
            }
            
            @keyframes scroll-left {
                0% { transform: translateX(0); }
                100% { transform: translateX(-50%); }
            }
            .animate-scroll-left {
                animation: scroll-left 20s linear infinite;
            }

            /* Menyembunyikan pinggiran scroll agar terlihat elegan (fade out) */
            .mask-gradient {
                -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
                mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
            }
        </style>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <div class="relative overflow-hidden bg-gradient-to-r from-[#fff3f8] to-white border border-[#d983e4]/30 shadow-lg shadow-[#d983e4]/10 rounded-2xl p-5 sm:p-6 mb-12 flex flex-col sm:flex-row items-start sm:items-center gap-4 hover:shadow-[#d983e4]/20 transition-all duration-500 hover:-translate-y-1">
                <div class="absolute inset-0 pointer-events-none overflow-hidden rounded-2xl">
                    <div class="absolute top-0 left-[-100%] w-1/2 h-full bg-gradient-to-r from-transparent via-white/40 to-transparent animate-[shine_3s_ease-in-out_infinite]"></div>
                </div>
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-[#d983e4] to-[#c973d4] rounded-xl flex items-center justify-center shadow-inner">
                    <svg class="w-6 h-6 text-white animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                </div>
                <div class="flex-1 relative z-10">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <span class="inline-flex items-center gap-1.5 bg-[#d983e4] text-white text-[10px] sm:text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                            <span class="w-1.5 h-1.5 bg-white rounded-full animate-ping"></span>
                            Baru Dirilis
                        </span>
                        <span class="text-[#d983e4] text-sm font-semibold">Tersedia mulai hari ini!</span>
                    </div>
                    <p class="text-gray-900 font-extrabold text-lg sm:text-xl leading-tight">TraKerja Premium Lifetime</p>
                    <p class="text-gray-500 text-sm mt-0.5">Akses tanpa batas, sekali bayar. Amankan harga promo peluncuran eksklusif.</p>
                </div>
                <div class="flex-shrink-0 text-right relative z-10 mt-2 sm:mt-0">
                    <p class="text-xs text-gray-400 mb-0.5 line-through">Rp 99.000</p>
                    <p class="text-2xl sm:text-3xl font-extrabold text-[#d983e4]">Rp 19.999</p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Sekali Bayar</p>
                </div>
            </div>

            <div class="text-center mb-12 scroll-fade-in">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 leading-tight">
                    Pilih  <span class="text-[#d983e4]">Paket</span> Anda 
                </h2>
                <p class="text-gray-500 text-base max-w-xl mx-auto">Mulai dari gratis selamanya, atau upgrade untuk membuka kekuatan penuh pelacakan karir Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">

                <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 bg-gray-50 rounded-lg group-hover:bg-gray-100 transition-colors">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <span class="font-bold text-gray-700">Free Standar</span>
                    </div>
                    <p class="text-4xl font-extrabold text-gray-900 mb-1">Gratis</p>
                    <p class="text-sm text-gray-400 mb-6">Selamanya, tanpa syarat</p>
                    <div class="border-t border-gray-100 pt-6 space-y-4 mb-8">
                        <div class="flex items-start gap-3 text-sm text-gray-600"><svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Maksimal 25 job tracker</div>
                        <div class="flex items-start gap-3 text-sm text-gray-600"><svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> 2 template CV standar</div>
                        <div class="flex items-start gap-3 text-sm text-gray-600"><svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> 1 kredit AI Analyzer gratis</div>
                        <div class="flex items-start gap-3 text-sm text-gray-600"><svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> 3 kredit Cover Letter Generator</div>
                        <div class="flex items-start gap-3 text-sm text-gray-600"><svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Dashboard & analitik standar</div>
                    </div>
                    <a href="{{ route('register') }}" class="block w-full text-center px-6 py-3.5 bg-gray-50 border border-gray-200 text-gray-600 rounded-xl font-semibold hover:bg-gray-100 hover:text-gray-900 transition-colors">
                        Mulai Gratis
                    </a>
                </div>

                <div class="relative bg-white border-2 border-[#d983e4] rounded-3xl p-8 shadow-2xl shadow-[#d983e4]/20 animate-float-slow z-10">
                    <div class="absolute -top-5 left-1/2 -translate-x-1/2 flex items-center gap-1.5 bg-gradient-to-r from-[#d983e4] to-[#4e71c5] text-white text-xs font-bold px-5 py-2 rounded-full whitespace-nowrap shadow-lg">
                        <svg class="w-4 h-4 animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                        Pilihan Paling Populer
                    </div>
                    <div class="flex items-center gap-3 mb-4 mt-2">
                        <div class="p-2 bg-[#d983e4]/10 rounded-lg">
                            <svg class="w-6 h-6 text-[#d983e4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                        </div>
                        <span class="font-bold text-gray-900">Premium Pro</span>
                    </div>
                    <div class="flex items-end gap-1 mb-1">
                        <p class="text-4xl font-extrabold text-gray-900">Rp 19.999</p>
                    </div>
                    <p class="text-sm text-[#d983e4] font-medium mb-6">Sekali bayar, akses selamanya</p>
                    <div class="border-t border-gray-100 pt-6 space-y-4 mb-8">
                        <div class="flex items-start gap-3 text-sm text-gray-700 font-medium"><svg class="w-5 h-5 text-[#d983e4] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Unlimited job tracker (∞)</div>
                        <div class="flex items-start gap-3 text-sm text-gray-600"><svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Akses 50+ template CV premium</div>
                        <div class="flex items-start gap-3 text-sm text-gray-600"><svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Bulk importer lamaran kerja</div>
                        <div class="flex items-start gap-3 text-sm text-gray-600"><svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Full analytics & dashboard</div>
                        <div class="flex items-start gap-3 text-sm text-gray-600"><svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> 5 kredit bonus AI CV Analyzer</div>
                        <div class="flex items-start gap-3 text-sm text-gray-600"><svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> 15 kredit Cover Letter Generator</div>
                        <div class="flex items-start gap-3 text-sm text-gray-600"><svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Prioritas update fitur terbaru</div>
                    </div>
                    <a href="{{ route('payment.index') }}" class="block w-full text-center px-6 py-4 bg-gradient-to-r from-[#d983e4] to-[#4e71c5] text-white rounded-xl font-bold hover:shadow-lg hover:scale-[1.02] transition-all duration-300">
                        Beli Premium Sekarang
                    </a>
                    </a>
                </div>

                <div class="relative bg-white border border-gray-100 rounded-3xl p-8 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group mt-4 md:mt-0">
                    <div class="absolute -top-4 left-6 bg-[#4e71c5] text-white text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wider">
                        Add-on
                    </div>
                    <div class="flex items-center gap-3 mb-4 mt-2">
                        <div class="p-2 bg-[#4e71c5]/10 rounded-lg group-hover:bg-[#4e71c5]/20 transition-colors">
                            <svg class="w-6 h-6 text-[#4e71c5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <span class="font-bold text-gray-700">AI Boost</span>
                    </div>
                    <p class="text-4xl font-extrabold text-gray-900 mb-1">Rp 15.000</p>
                    <p class="text-sm text-gray-400 mb-6">Per paket, beli kapan saja</p>
                    <div class="border-t border-gray-100 pt-6 space-y-4 mb-8">
                        <div class="flex items-start gap-3 text-sm text-gray-600"><svg class="w-5 h-5 text-[#4e71c5] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> +10 kredit AI CV Analyzer</div>
                        <div class="flex items-start gap-3 text-sm text-gray-600"><svg class="w-5 h-5 text-[#4e71c5] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> +15 kredit Cover Letter Generator</div>
                        <div class="flex items-start gap-3 text-sm text-gray-600"><svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Berlaku selamanya</div>
                    </div>
                    <a href="{{ route('payment.topup') }}" class="block w-full text-center px-6 py-3.5 bg-white border-2 border-[#4e71c5] text-[#4e71c5] rounded-xl font-bold hover:bg-[#4e71c5] hover:text-white transition-all duration-300">
                        Top-Up Sekarang
                    </a>
                    </a>
                </div>

            </div>

            <div class="mt-8 bg-white/80 backdrop-blur-md rounded-2xl border border-gray-100 p-5 md:p-6 flex flex-col md:flex-row items-center justify-between gap-6 shadow-sm overflow-hidden">
                
                <div class="text-center md:text-left flex-shrink-0 relative z-10 bg-white/90 pr-4">
                    <h3 class="text-sm sm:text-base font-bold text-gray-800 flex items-center justify-center md:justify-start gap-2">
                        <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                        </span>
                        Pembayaran Instan & Otomatis
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">Aktivasi hitungan detik via QRIS & VA</p>
                </div>

                <div class="w-full md:w-auto flex-1 overflow-hidden mask-gradient relative h-12">
                    <div class="absolute flex w-[200%] gap-6 items-center animate-scroll-left h-full">
                        <div class="flex items-center gap-6 px-3">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" alt="QRIS" class="h-6 object-contain grayscale hover:grayscale-0 transition-all duration-300">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" alt="BCA" class="h-5 object-contain grayscale hover:grayscale-0 transition-all duration-300">
                            <img src="{{ asset('images/bni.png') }}" onerror="this.src='https://upload.wikimedia.org/wikipedia/id/thumb/5/55/BNI_logo.svg/1200px-BNI_logo.svg.png'" alt="BNI" class="h-4 object-contain grayscale hover:grayscale-0 transition-all duration-300">
                            <img src="{{ asset('images/bri.png') }}" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/6/68/BANK_BRI_logo.svg/1200px-BANK_BRI_logo.svg.png'" alt="BRI" class="h-5 object-contain grayscale hover:grayscale-0 transition-all duration-300">
                            <img src="{{ asset('images/permata2.png') }}" onerror="this.src='https://upload.wikimedia.org/wikipedia/id/thumb/7/7b/PermataBank_logo.svg/1200px-PermataBank_logo.svg.png'" alt="Permata" class="h-5 object-contain grayscale hover:grayscale-0 transition-all duration-300">
                        </div>
                        <div class="flex items-center gap-6 px-3">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" alt="QRIS" class="h-6 object-contain grayscale hover:grayscale-0 transition-all duration-300">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" alt="BCA" class="h-5 object-contain grayscale hover:grayscale-0 transition-all duration-300">
                            <img src="{{ asset('images/bni.png') }}" onerror="this.src='https://upload.wikimedia.org/wikipedia/id/thumb/5/55/BNI_logo.svg/1200px-BNI_logo.svg.png'" alt="BNI" class="h-4 object-contain grayscale hover:grayscale-0 transition-all duration-300">
                            <img src="{{ asset('images/bri.png') }}" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/6/68/BANK_BRI_logo.svg/1200px-BANK_BRI_logo.svg.png'" alt="BRI" class="h-5 object-contain grayscale hover:grayscale-0 transition-all duration-300">
                            <img src="{{ asset('images/permata2.png') }}" onerror="this.src='https://upload.wikimedia.org/wikipedia/id/thumb/7/7b/PermataBank_logo.svg/1200px-PermataBank_logo.svg.png'" alt="Permata" class="h-5 object-contain grayscale hover:grayscale-0 transition-all duration-300">
                        </div>
                        <div class="flex items-center gap-6 px-3">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" alt="QRIS" class="h-6 object-contain grayscale hover:grayscale-0 transition-all duration-300">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" alt="BCA" class="h-5 object-contain grayscale hover:grayscale-0 transition-all duration-300">
                            <img src="{{ asset('images/bni.png') }}" onerror="this.src='https://upload.wikimedia.org/wikipedia/id/thumb/5/55/BNI_logo.svg/1200px-BNI_logo.svg.png'" alt="BNI" class="h-4 object-contain grayscale hover:grayscale-0 transition-all duration-300">
                            <img src="{{ asset('images/bri.png') }}" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/6/68/BANK_BRI_logo.svg/1200px-BANK_BRI_logo.svg.png'" alt="BRI" class="h-5 object-contain grayscale hover:grayscale-0 transition-all duration-300">
                            <img src="{{ asset('images/permata2.png') }}" onerror="this.src='https://upload.wikimedia.org/wikipedia/id/thumb/7/7b/PermataBank_logo.svg/1200px-PermataBank_logo.svg.png'" alt="Permata" class="h-5 object-contain grayscale hover:grayscale-0 transition-all duration-300">
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-8 bg-blue-50/50 border border-blue-100 rounded-2xl p-5 flex gap-4 items-start shadow-sm">
                <div class="p-2 bg-blue-100 rounded-full flex-shrink-0 mt-0.5">
                    <svg class="w-5 h-5 text-[#4e71c5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-800 mb-1.5">Kenapa menggunakan model sekali bayar (Lifetime)?</p>
                    <p class="text-sm text-gray-600 leading-relaxed">Kami percaya alat pencarian kerja harus memudahkan, bukan membebani dengan biaya bulanan. Sistem <em>pay once, use forever</em> ini adalah bentuk apresiasi kami bagi pengguna awal (<em>early adopters</em>) yang mendukung perkembangan TraKerja.</p>
                </div>
            </div>

        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-16 sm:py-24 bg-slate-50 relative overflow-x-hidden" id="testimonials">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <div class="text-center mb-12 scroll-fade-in">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-3">
                    Kata <span class="text-[#d983e4]">Pengguna</span> TraKerja
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Ulasan dari pencari kerja yang sudah menggunakan TraKerja
                </p>
            </div>

        <div class="relative w-full mt-8 scroll-fade-in scroll-delay-100 py-6">
            
            <div class="flex transition-transform duration-500 ease-in-out" id="testimonial-track">
                
                <div class="w-full flex-shrink-0 px-2 md:px-4">
                    <div class="flex flex-col md:flex-row bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden h-full">
                        <div class="w-full md:w-[40%] bg-[#5b2a86] relative min-h-[300px] md:min-h-[400px]">
                            <img src="{{ asset('images/sarah.png') }}" 
                                alt="Sarah" 
                                class="absolute inset-0 w-full h-full object-cover"
                                onerror="this.src='https://placehold.co/400x600/5b2a86/ffffff?text=Sarah'">
                        </div>
                        <div class="w-full md:w-[60%] p-8 md:p-10 lg:p-12 flex flex-col justify-center bg-white">
                            <h3 class="text-3xl md:text-4xl font-bold text-gray-900 mb-1">Sarah</h3>
                            <p class="text-[#2e1065] font-bold text-lg mb-6">Fresh Graduate</p>
                            <p class="text-gray-700 text-base md:text-lg leading-relaxed">
                                "TraKerja sangat membantu saya merapikan lamaran kerja yang berantakan jadi jauh lebih terorganisasi. Kerennya lagi, saya bisa set goals jumlah apply harian makin banyak yang diapply, peluang dipanggil pun makin besar!"
                            </p>
                        </div>
                    </div>
                </div>

                <div class="w-full flex-shrink-0 px-2 md:px-4">
                    <div class="flex flex-col md:flex-row bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden h-full">
                        <div class="w-full md:w-[40%] bg-[#5b2a86] relative min-h-[300px] md:min-h-[400px]">
                            <img src="{{ asset('images/ahmad.png') }}" 
                                alt="Ahmad" 
                                class="absolute inset-0 w-full h-full object-cover"
                                onerror="this.src='https://placehold.co/400x600/5b2a86/ffffff?text=Ahmad'">
                        </div>
                        <div class="w-full md:w-[60%] p-8 md:p-10 lg:p-12 flex flex-col justify-center bg-white">
                            <h3 class="text-3xl md:text-4xl font-bold text-gray-900 mb-1">Ahmad</h3>
                            <p class="text-[#2e1065] font-bold text-lg mb-6">Career Switcher</p>
                            <p class="text-gray-700 text-base md:text-lg leading-relaxed">
                                "Platform yang sederhana tapi efektif. Analytics-nya membantu saya tahu platform mana yang paling sering memanggil interview. Sempurna untuk yang ingin pindah karier! Semua data tersimpan aman."
                            </p>
                        </div>
                    </div>
                </div>

                <div class="w-full flex-shrink-0 px-2 md:px-4">
                    <div class="flex flex-col md:flex-row bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden h-full">
                        <div class="w-full md:w-[40%] bg-[#5b2a86] relative min-h-[300px] md:min-h-[400px]">
                            <img src="{{ asset('images/maya.png') }}" 
                                alt="Fakhri" 
                                class="absolute inset-0 w-full h-full object-cover"
                                onerror="this.src='https://placehold.co/400x600/5b2a86/ffffff?text=Fakhri'">
                        </div>
                        <div class="w-full md:w-[60%] p-8 md:p-10 lg:p-12 flex flex-col justify-center bg-white">
                            <h3 class="text-3xl md:text-4xl font-bold text-gray-900 mb-1">Fakhri</h3>
                            <p class="text-[#2e1065] font-bold text-lg mb-6">Pencari Kerja</p>
                            <p class="text-gray-700 text-base md:text-lg leading-relaxed">
                                "Goal tracking-nya bagus banget! Membantu saya tetap konsisten apply kerja setiap minggu. Pengingat follow-up-nya juga juara. Akhirnya dapat kerja juga! Terima kasih TraKerja!"
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

            <div class="flex justify-end items-center mt-10 gap-6 scroll-fade-in scroll-delay-200">
                <div class="text-[#5b2a86] font-bold text-sm tracking-widest" id="testi-counter">
                    01 / 03
                </div>
                <div class="flex gap-3">
                    <button onclick="moveTestimonial(-1)" class="w-10 h-10 flex items-center justify-center rounded-full text-[#5b2a86] hover:bg-[#5b2a86]/10 transition-colors border border-transparent hover:border-[#5b2a86]/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button onclick="moveTestimonial(1)" class="w-10 h-10 flex items-center justify-center rounded-full text-[#5b2a86] hover:bg-[#5b2a86]/10 transition-colors border border-transparent hover:border-[#5b2a86]/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="relative pt-12 sm:pt-16 pb-12 sm:pb-24 bg-[#fafbfe] overflow-hidden">
        <!-- Dekorasi Background (Subtle Glows) -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
            <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-purple-200/20 blur-3xl"></div>
            <div class="absolute top-[20%] -right-[10%] w-[40%] h-[60%] rounded-full bg-blue-200/20 blur-3xl"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-10 md:mb-16 scroll-fade-in">
                <p class="text-sm font-bold tracking-wide text-gray-800 mb-2">Cara Kerja TraKerja.</p>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-4 tracking-tight">
                    Mulai dalam <span class="text-[#b165c7]">3 Langkah</span>, Nikmati 9 Fitur
                </h2>
                <p class="text-base sm:text-lg text-gray-600 px-4">
                    Dari daftar sampai dapat kerja — TraKerja menemani setiap langkah
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 md:gap-12 items-center">

                <!-- Left: Accordion Steps -->
                <div class="lg:col-span-5 space-y-4 scroll-flip">

                    <!-- Step 1 (Active State) -->
                    <div class="relative how-it-works-item bg-gradient-to-r from-white to-[#fdf4ff] border-2 border-[#d983e4] rounded-2xl overflow-hidden shadow-sm transition-all duration-300" id="hiw-item-1">
                        <!-- Big Background Number -->
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-8xl font-black text-[#d983e4] opacity-20 pointer-events-none select-none z-0">01</div>
                        
                        <button class="relative z-10 how-it-works-btn w-full px-6 py-5 text-left flex items-center justify-between" data-step="1" onclick="toggleHowItWorks(this)">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-[#fdf4ff] flex items-center justify-center flex-shrink-0 shadow-sm border border-purple-100">
                                    <span class="text-[#d983e4] font-bold text-base">01</span>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 text-base sm:text-lg">Daftar & Atur Profil</h3>
                                    <p class="text-sm text-gray-500 mt-0.5">Setup 2 menit, langsung pakai.</p>
                                </div>
                            </div>
                            <svg class="w-6 h-6 text-gray-800 how-it-works-icon transition-transform flex-shrink-0 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="relative z-10 how-it-works-content px-6 pb-6">
                            <p class="text-base text-gray-700 leading-relaxed mb-5">Buat akun gratis dalam 2 menit. Isi biodata dan preferensi kerja di <strong class="text-gray-900 font-bold">My Profile</strong> sekali saja — data ini otomatis dipakai di seluruh fitur lainnya.</p>
                            <div class="flex flex-wrap gap-2.5">
                                <span class="inline-flex items-center gap-1.5 text-sm bg-white border border-gray-200 text-gray-700 px-3 py-1.5 rounded-full shadow-sm">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    My Profile
                                </span>
                                <span class="inline-flex items-center gap-1.5 text-sm bg-white border border-gray-200 text-gray-700 px-3 py-1.5 rounded-full shadow-sm">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Goals
                                </span>
                                <span class="inline-flex items-center gap-1.5 text-sm bg-white border border-gray-200 text-gray-700 px-3 py-1.5 rounded-full shadow-sm">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    CSV Tools
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 (Inactive State) -->
                    <div class="relative how-it-works-item bg-white/80 backdrop-blur-sm border border-gray-200 rounded-2xl overflow-hidden hover:border-[#4e71c5]/50 transition-all duration-300" id="hiw-item-2">
                        <!-- Big Background Number -->
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-8xl font-black text-gray-200 opacity-40 pointer-events-none select-none z-0">02</div>

                        <button class="relative z-10 how-it-works-btn w-full px-6 py-5 text-left flex items-center justify-between" data-step="2" onclick="toggleHowItWorks(this)">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center flex-shrink-0 border border-gray-100">
                                    <span class="text-gray-500 font-bold text-base">02</span>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-700 text-base sm:text-lg">Kelola Lamaran & Dokumen</h3>
                                    <p class="text-sm text-gray-400 mt-0.5">Semua dalam satu dashboard.</p>
                                </div>
                            </div>
                            <svg class="w-6 h-6 text-gray-400 how-it-works-icon transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <!-- Content hidden by default -->
                        <div class="relative z-10 how-it-works-content hidden px-6 pb-6">
                            <p class="text-base text-gray-700 leading-relaxed mb-5">Tambah lamaran ke <strong class="text-gray-900 font-bold">Kanban Board</strong>, catat jadwal interview, dan buat CV profesional — semua terorganisir rapi tanpa spreadsheet berantakan.</p>
                            <div class="flex flex-wrap gap-2.5">
                                <span class="inline-flex items-center gap-1.5 text-sm bg-white border border-gray-200 text-gray-700 px-3 py-1.5 rounded-full shadow-sm">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7"></path></svg>
                                    Track Progress
                                </span>
                                <span class="inline-flex items-center gap-1.5 text-sm bg-white border border-gray-200 text-gray-700 px-3 py-1.5 rounded-full shadow-sm">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Interviews
                                </span>
                                <span class="inline-flex items-center gap-1.5 text-sm bg-white border border-gray-200 text-gray-700 px-3 py-1.5 rounded-full shadow-sm">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    CV Builder
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3 (Inactive State) -->
                    <div class="relative how-it-works-item bg-white/80 backdrop-blur-sm border border-gray-200 rounded-2xl overflow-hidden hover:border-[#d983e4]/50 transition-all duration-300" id="hiw-item-3">
                        <!-- Big Background Number -->
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-8xl font-black text-gray-200 opacity-40 pointer-events-none select-none z-0">03</div>

                        <button class="relative z-10 how-it-works-btn w-full px-6 py-5 text-left flex items-center justify-between" data-step="3" onclick="toggleHowItWorks(this)">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center flex-shrink-0 border border-gray-100">
                                    <span class="text-gray-500 font-bold text-base">03</span>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-700 text-base sm:text-lg">Analisis & Optimalkan</h3>
                                    <p class="text-sm text-gray-400 mt-0.5">AI + data untuk hasil terbaik.</p>
                                </div>
                            </div>
                            <svg class="w-6 h-6 text-gray-400 how-it-works-icon transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <!-- Content hidden by default -->
                        <div class="relative z-10 how-it-works-content hidden px-6 pb-6">
                            <p class="text-base text-gray-700 leading-relaxed mb-5">Pantau statistik lamaran di <strong class="text-gray-900 font-bold">Summary</strong>, optimalkan CV dengan AI Analyzer, dan generate cover letter otomatis untuk setiap posisi yang dilamar.</p>
                            <div class="flex flex-wrap gap-2.5">
                                <span class="inline-flex items-center gap-1.5 text-sm bg-white border border-gray-200 text-gray-700 px-3 py-1.5 rounded-full shadow-sm">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                    Summary
                                </span>
                                <span class="inline-flex items-center gap-1.5 text-sm bg-[#fdf4ff] border border-[#e9d5ff] text-purple-700 px-3 py-1.5 rounded-full font-medium shadow-sm">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                    AI Analyzer ✦
                                </span>
                                <span class="inline-flex items-center gap-1.5 text-sm bg-[#fdf4ff] border border-[#e9d5ff] text-purple-700 px-3 py-1.5 rounded-full font-medium shadow-sm">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    Cover Letter ✦
                                </span>
                            </div>
                            <p class="text-sm text-purple-600 mt-4 flex items-center gap-1.5 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Fitur ✦ tersedia di paket Gratis & Premium
                            </p>
                        </div>
                    </div>

                </div>

                <!-- Right: Image Display (Mockup Area) -->
                <div class="lg:col-span-7 relative hidden lg:flex flex-col items-center justify-center">
                    
                    <!-- Wrapper untuk Mockup -->
                    <div class="relative w-full max-w-3xl mx-auto z-10 aspect-[16/10]">
                        
                        <!-- Dekorasi Background -->
                        <div class="absolute -top-10 -right-10 w-64 h-64 bg-gradient-to-br from-purple-100 to-transparent rounded-2xl opacity-50 -z-10"></div>
                        <div class="absolute -bottom-5 -left-5 w-48 h-48 bg-gradient-to-tr from-blue-100 to-transparent rounded-full opacity-50 -z-10"></div>

                        <!-- STEP 1 -->
                        <img id="how-it-works-img-1"
                            src="{{ asset('images/mu0.png') }}"
                            alt="Daftar & Atur Profil di TraKerja"
                            class="how-it-works-image absolute inset-0 w-full h-full object-contain drop-shadow-2xl transition-opacity duration-500"
                            style="opacity:1;"
                            onerror="this.style.display='none'; document.getElementById('hiw-fallback-1').style.display='flex';">

                        <!-- STEP 2 -->
                        <img id="how-it-works-img-2"
                            src="{{ asset('images/mu1.png') }}"
                            alt="Kelola Lamaran & Dokumen"
                            class="how-it-works-image absolute inset-0 w-full h-full object-contain drop-shadow-2xl transition-opacity duration-500 hidden">

                        <!-- STEP 3 -->
                        <img id="how-it-works-img-3"
                            src="{{ asset('images/mu2.png') }}"
                            alt="Analisis & Optimalkan"
                            class="how-it-works-image absolute inset-0 w-full h-full object-contain drop-shadow-2xl transition-opacity duration-500 hidden">

                        <!-- FALLBACK -->
                        <div id="hiw-fallback-1"
                            class="absolute inset-0 bg-white border border-gray-200 rounded-xl shadow-xl hidden items-center justify-center text-center p-8">

                            <div>
                                <div class="w-16 h-16 bg-[#fdf4ff] rounded-2xl flex items-center justify-center mx-auto mb-4 border border-purple-100">
                                    <svg class="w-8 h-8 text-[#d983e4]"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.5"
                                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>

                                <p class="text-lg font-bold text-gray-800 mb-1">
                                    Mockup Laptop
                                </p>

                                <p class="text-sm text-gray-500">
                                    Silakan masukkan gambar mockup dashboard.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Dot indicators -->
                    <div class="flex items-center justify-center gap-3 mt-8">

                        <!-- Dot 1 -->
                        <div class="w-4 h-4 rounded-full border-2 border-[#b165c7] flex items-center justify-center transition-all duration-300"
                            id="hiw-dot-1">
                            <div class="w-2 h-2 rounded-full bg-[#b165c7]"></div>
                        </div>

                        <!-- Dot 2 -->
                        <div class="w-2.5 h-2.5 rounded-full bg-gray-300 transition-all duration-300 cursor-pointer hover:bg-gray-400"
                            id="hiw-dot-2">
                        </div>

                        <!-- Dot 3 -->
                        <div class="w-2.5 h-2.5 rounded-full bg-gray-300 transition-all duration-300 cursor-pointer hover:bg-gray-400"
                            id="hiw-dot-3">
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-8 sm:py-10 bg-gradient-to-r from-[#4e71c5] to-[#d983e4] relative overflow-hidden mt-6 sm:mt-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white/10 backdrop-blur-sm rounded-xl md:rounded-2xl border border-white/20 p-5 sm:p-6 md:p-8 scroll-scale">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4 md:gap-6">
                    <!-- Left: Text Content -->
                    <div class="flex-1 text-center md:text-left">
                        <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-white mb-2">
                            Siap Mengorganisir Pencarian Kerja Anda?
                        </h2>
                        <p class="text-white/90 text-xs sm:text-sm mb-3 md:mb-4">
                            Bergabunglah dengan pencari kerja Indonesia yang sudah menggunakan TraKerja. Mulai gratis dan rasakan perbedaannya.
                        </p>
                        <!-- Trust Indicators - Compact -->
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 text-white/80 text-xs">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Setup 2 Menit</span>
                            </div>
                            <div class="w-1 h-1 bg-white/50 rounded-full"></div>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <span>Data Aman & Privat</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right: Action Buttons -->
                    <div class="flex-shrink-0">
                        @guest
                            <div class="flex flex-col sm:flex-row gap-2.5">
                                <a href="{{ route('register') }}" 
                                   class="bg-white text-[#4e71c5] px-5 py-2.5 rounded-lg font-semibold text-xs hover:shadow-lg transition-all duration-200 hover:scale-105 inline-flex items-center justify-center whitespace-nowrap">
                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    Daftar Gratis
                                </a>
                                <a href="{{ route('login') }}" 
                                class="bg-white border border-[#b165c7] text-[#b165c7] px-5 py-2.5 rounded-lg font-semibold text-xs hover:bg-[#f9f2fc] transition-all duration-200 inline-flex items-center justify-center whitespace-nowrap">
                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                    </svg>
                                    Login
                                </a>
                            </div>
                        @else
                            <a href="{{ url('/tracker') }}" 
                               class="bg-white text-[#4e71c5] px-5 py-2.5 rounded-lg font-semibold text-xs hover:shadow-lg transition-all duration-200 hover:scale-105 inline-flex items-center justify-center whitespace-nowrap">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Buka Dashboard
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact & FAQ Section -->
    <section class="py-16 sm:py-24 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Top Contact Banner (2 Columns) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-16 items-center mb-20 scroll-fade-in">
                <!-- Image Area -->
                <div class="order-2 md:order-1 flex justify-center">
                    <!-- Ganti src dengan foto tim support Anda -->
                    <img src="{{ asset('images/support-team.png') }}" alt="Customer Support" class="w-full max-w-sm object-contain" onerror="this.src='https://ui-avatars.com/api/?name=Support&background=fdf4ff&color=d983e4&size=400'">
                </div>
                
                <!-- Text Area -->
                <div class="order-1 md:order-2 text-center md:text-left">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4 leading-tight">
                        Pertanyaan? Kami<br>Selalu Online
                    </h2>
                    <p class="text-base text-gray-600 mb-6 leading-relaxed">
                        Kami dapat membantu Anda untuk mengenal TraKerja lebih baik dengan menyediakan langkah-langkah menggunakan platform kami.
                    </p>
                    <a href="mailto:support@trakerja.com" class="inline-flex items-center text-[#d983e4] font-bold hover:text-[#6336CF] transition-colors text-lg">
                        Tanya Apapun 
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- FAQ Minimalist Section -->
            <div class="max-w-3xl mx-auto scroll-fade-in scroll-delay-100">
                <!-- Centered Title -->
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-extrabold text-gray-900">FAQ</h2>
                </div>
                
                <!-- Simplified Accordion List -->
                <div class="space-y-0">
                    
                    <!-- FAQ Item 1 -->
                    <div class="border-b border-gray-200">
                        <button class="faq-question w-full py-5 text-left flex items-center justify-between focus:outline-none" onclick="toggleFaq(this)">
                            <span class="font-bold text-gray-900 text-base">Apakah TraKerja gratis?</span>
                            <svg class="w-5 h-5 text-gray-900 faq-icon transition-transform duration-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="faq-answer hidden pb-6 text-gray-600 text-sm leading-relaxed pr-8">
                            Ya! TraKerja menawarkan paket <strong>Gratis</strong> yang sudah lengkap dengan fitur-fitur penting seperti Kanban Board, Smart Reminders, dan Goal Tracking untuk mengelola hingga 25 lamaran kerja. Untuk kebutuhan yang lebih advanced, Anda bisa upgrade ke <strong>Premium</strong>.
                        </div>
                    </div>
                    
                    <!-- FAQ Item 2 -->
                    <div class="border-b border-gray-200">
                        <button class="faq-question w-full py-5 text-left flex items-center justify-between focus:outline-none" onclick="toggleFaq(this)">
                            <span class="font-bold text-gray-900 text-base">Bagaimana cara menggunakan TraKerja?</span>
                            <svg class="w-5 h-5 text-gray-900 faq-icon transition-transform duration-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="faq-answer hidden pb-6 text-gray-600 text-sm leading-relaxed pr-8">
                            Sangat mudah! Cukup daftar akun gratis (tidak perlu verifikasi email), lalu tambahkan lamaran kerja Anda. TraKerja akan otomatis mengorganisir semua lamaran dalam Kanban Board dan mengingatkan Anda untuk follow-up. Setup hanya butuh 2 menit!
                        </div>
                    </div>
                    
                    <!-- FAQ Item 3 -->
                    <div class="border-b border-gray-200">
                        <button class="faq-question w-full py-5 text-left flex items-center justify-between focus:outline-none" onclick="toggleFaq(this)">
                            <span class="font-bold text-gray-900 text-base">Data saya aman di TraKerja?</span>
                            <svg class="w-5 h-5 text-gray-900 faq-icon transition-transform duration-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="faq-answer hidden pb-6 text-gray-600 text-sm leading-relaxed pr-8">
                            Keamanan data adalah prioritas utama kami. Semua data Anda dienkripsi dengan teknologi end-to-end dan disimpan dengan aman. Kami juga melakukan backup otomatis untuk memastikan data Anda tidak hilang.
                        </div>
                    </div>
                    
                    <!-- FAQ Item 4 -->
                    <div class="border-b border-gray-200">
                        <button class="faq-question w-full py-5 text-left flex items-center justify-between focus:outline-none" onclick="toggleFaq(this)">
                            <span class="font-bold text-gray-900 text-base">Bisakah saya akses TraKerja dari berbagai device?</span>
                            <svg class="w-5 h-5 text-gray-900 faq-icon transition-transform duration-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="faq-answer hidden pb-6 text-gray-600 text-sm leading-relaxed pr-8">
                            Tentu saja! TraKerja menggunakan teknologi cloud yang memungkinkan Anda mengakses data dari mana saja dan kapan saja. Semua perubahan akan tersinkronisasi secara real-time di semua device dan browser Anda.
                        </div>
                    </div>
                    
                    <!-- FAQ Item 5 -->
                    <div class="border-b border-gray-200">
                        <button class="faq-question w-full py-5 text-left flex items-center justify-between focus:outline-none" onclick="toggleFaq(this)">
                            <span class="font-bold text-gray-900 text-base">Apa keuntungan dibanding spreadsheet?</span>
                            <svg class="w-5 h-5 text-gray-900 faq-icon transition-transform duration-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="faq-answer hidden pb-6 text-gray-600 text-sm leading-relaxed pr-8">
                            TraKerja dirancang khusus dengan Kanban Board visual, Analytics Dashboard, Smart Reminders otomatis untuk follow-up, Goal Tracking, dan sinkronisasi real-time. Ini jauh lebih efisien daripada spreadsheet manual.
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white py-12 relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-200/30 to-blue-200/30 rounded-full blur-3xl hidden md:block"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-br from-blue-200/30 to-purple-200/30 rounded-full blur-3xl hidden md:block"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
                <!-- Brand Section -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('images/icon.png') }}"
                             alt="TraKerja Logo"
                             class="w-10 h-10"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center" style="display: none;">
                            <span class="text-white font-bold text-sm">T</span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold bg-gradient-to-r from-[#d983e4] to-[#4e71c5] bg-clip-text text-transparent">
                                TraKerja
                            </h3>
                            <p class="text-sm text-gray-500">Professional Job Application Management</p>
                        </div>
                    </div>
                    <p class="text-gray-600 leading-relaxed text-sm">
                        Platform pelacakan lamaran kerja yang dirancang khusus untuk pencari kerja Indonesia. 
                        Sederhana, efektif, dan mudah digunakan.
                    </p>
                </div>

                <!-- Contact Section -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Hubungi Kami</h4>
                    <div class="space-y-3">
                        <!-- Email -->
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <a href="mailto:trakerja@teknalogi.id" class="text-gray-700 hover:text-primary-600 transition-colors duration-200 font-medium text-sm">
                                    trakerja@teknalogi.id
                                </a>
                            </div>
                        </div>

                        <!-- Instagram -->
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Instagram</p>
                                <a href="https://www.instagram.com/teknalogi.id/" target="_blank" rel="noopener noreferrer" class="text-gray-700 hover:text-primary-600 transition-colors duration-200 font-medium text-sm">
                                    @teknalogi.id
                                </a>
                            </div>
                        </div>

                        <!-- LinkedIn -->
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.784 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-1.337-.026-3.059-1.865-3.059-1.865 0-2.151 1.455-2.151 2.963v5.7h-3v-11h2.881v1.507h.041c.401-.761 1.381-1.563 2.845-1.563 3.043 0 3.604 2.004 3.604 4.609v6.447z"/>
                                </svg>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">LinkedIn</p>

                                <a href="https://www.linkedin.com/company/teknalogi/"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-gray-700 hover:text-primary-600 transition-colors duration-200 font-medium text-sm">
                                    Teknalogi Transformasi Digital
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Company Info -->
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Dikelola oleh</p>
                        <p class="text-gray-900 font-semibold text-sm">PT Teknalogi Transformasi Digital</p>
                    </div>
                    <div class="pt-3 border-t border-gray-200">
                        <p class="text-xs text-gray-500">
                            © 2025 TraKerja. All rights reserved.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Bottom Section -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-3 md:space-y-0">
                    <p class="text-sm text-gray-500 text-center md:text-left">
                        Untuk pencari kerja di Indonesia
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <button
        id="contact-fab"
        onclick="openContactWidget()"
        class="fixed bottom-6 right-6 z-[90] w-14 h-14 bg-gradient-to-br from-[#d983e4] to-[#4e71c5] rounded-full shadow-2xl flex items-center justify-center hover:scale-110 transition-all duration-300 group"
        aria-label="Hubungi Kami"
    >
        {{-- Icon chat --}}
        <svg id="contact-fab-icon" class="w-6 h-6 text-white transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        {{-- Tooltip --}}
        <span class="absolute right-16 bg-gray-900 text-white text-xs px-3 py-1.5 rounded-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
            Hubungi Kami
        </span>
        {{-- Pulse ring --}}
        <span class="absolute inset-0 rounded-full bg-[#d983e4] opacity-30 animate-ping pointer-events-none"></span>
    </button>
    
    {{-- ===== FLOATING WIDGET PANEL ===== --}}
    <div
        id="contact-widget"
        class="fixed bottom-24 right-6 z-[90] w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden
            transform scale-95 opacity-0 pointer-events-none transition-all duration-300 origin-bottom-right"
    >
        {{-- Header --}}
        <div class="bg-gray-900 px-5 py-4 flex items-center justify-between">
            <span class="text-white font-bold text-sm tracking-wide">Hubungi kami</span>
            <button onclick="closeContactWidget()" class="text-white/70 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/>
                </svg>
            </button>
        </div>
    
        {{-- SCREEN 1: Pilihan topik --}}
        <div id="contact-screen-1" class="p-6">
            <h3 class="text-2xl font-bold text-gray-900 mb-1">Halo! 👋</h3>
            <p class="text-gray-500 text-sm mb-6">Terima kasih telah menghubungi TraKerja, apa yang bisa kami bantu?</p>
    
            <div class="space-y-0 divide-y divide-gray-100">
                <button onclick="showContactTopic('form', 'Ingin berlangganan baru atau perpanjang?')"
                        class="contact-topic-btn w-full text-left py-4 flex items-center justify-between text-gray-900 font-medium text-sm hover:text-[#d983e4] transition-colors group">
                    Ingin berlangganan baru atau perpanjang?
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-[#d983e4] transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <button onclick="showContactTopic('form', 'Pertanyaan tentang paket berlangganan')"
                        class="contact-topic-btn w-full text-left py-4 flex items-center justify-between text-gray-900 font-medium text-sm hover:text-[#d983e4] transition-colors group">
                    Punya pertanyaan tentang paket berlangganan?
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-[#d983e4] transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <button onclick="showContactTopic('whatsapp', '')"
                        class="contact-topic-btn w-full text-left py-4 flex items-center justify-between text-gray-900 font-medium text-sm hover:text-[#d983e4] transition-colors group">
                    Mau melakukan kerja sama atau partnership?
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-[#d983e4] transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <button onclick="showContactTopic('form', 'Ada masalah atau butuh bantuan')"
                        class="contact-topic-btn w-full text-left py-4 flex items-center justify-between text-gray-900 font-medium text-sm hover:text-[#d983e4] transition-colors group">
                    Ada masalah atau butuh bantuan? Kami siap membantu.
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-[#d983e4] transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    
        {{-- SCREEN 2: Form kirim pesan --}}
        <div id="contact-screen-2" class="hidden p-6">
            <button onclick="backToScreen1()" class="flex items-center gap-1 text-gray-500 hover:text-gray-900 text-sm mb-5 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </button>
    
            <form id="contact-form" onsubmit="submitContactForm(event)" class="space-y-3">
                @csrf
                <input type="hidden" id="contact-subject-hidden" name="subject" value="">
    
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nama <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="contact-name" required
                        placeholder="Nama lengkap Anda"
                        class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#d983e4]/30 focus:border-[#d983e4] outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="contact-email" required
                        placeholder="email@contoh.com"
                        class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#d983e4]/30 focus:border-[#d983e4] outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Pesan <span class="text-red-500">*</span></label>
                    <textarea name="message" id="contact-message" required rows="4"
                        placeholder="Tulis pesan Anda di sini..."
                        class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#d983e4]/30 focus:border-[#d983e4] outline-none transition-all resize-none"></textarea>
                </div>
    
                {{-- Error message --}}
                <div id="contact-error" class="hidden text-xs text-red-600 bg-red-50 border border-red-100 px-3 py-2 rounded-lg"></div>
    
                <button type="submit" id="contact-submit-btn"
                    class="w-full py-3 bg-gradient-to-r from-[#d983e4] to-[#4e71c5] text-white rounded-xl font-bold text-sm hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2 disabled:opacity-60">
                    <span id="contact-btn-text">Kirim Pesan</span>
                    <svg id="contact-btn-spinner" class="hidden w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                </button>
            </form>
        </div>
    
        {{-- SCREEN 3: WhatsApp --}}
        <div id="contact-screen-3" class="hidden p-6">
            <button onclick="backToScreen1()" class="flex items-center gap-1 text-gray-500 hover:text-gray-900 text-sm mb-5 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </button>
            <p class="text-gray-500 text-sm mb-5 leading-relaxed">Hubungi tim kami langsung melalui WhatsApp untuk bantuan segera.</p>
            {{-- Ganti nomor WA di bawah ini --}}
            <a href="https://wa.me/6281234567890?text=Halo%20TraKerja%2C%20saya%20ingin%20menjadwalkan%20demo"
            target="_blank" rel="noopener noreferrer"
            class="flex items-center gap-3 w-full bg-gray-50 hover:bg-green-50 border border-gray-200 hover:border-green-200 rounded-xl px-4 py-4 transition-all duration-200 group">
                {{-- WhatsApp icon SVG --}}
                <svg class="w-8 h-8 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                <span class="font-semibold text-gray-800 text-sm group-hover:text-green-700 transition-colors">Chat dengan tim TraKerja</span>
            </a>
        </div>
    
        {{-- SCREEN 4: Sukses --}}
        <div id="contact-screen-success" class="hidden p-8 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h4 class="text-lg font-bold text-gray-900 mb-2">Pesan Terkirim! </h4>
            <p class="text-sm text-gray-500 mb-5">Terima kasih! Kami akan menghubungi Anda di email yang diberikan dalam waktu 1x24 jam.</p>
            <button onclick="backToScreen1()" class="text-sm text-[#d983e4] font-semibold hover:underline">Kembali ke menu utama</button>
        </div>
    </div>

    @livewireScripts
    
    <!-- Scroll Progress Indicator -->
    <div class="scroll-indicator" id="scrollProgress"></div>
    
    <script>
        // Scroll Progress Indicator
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset;
            const docHeight = document.body.offsetHeight - window.innerHeight;
            const scrollPercent = (scrollTop / docHeight) * 100;
            document.getElementById('scrollProgress').style.transform = `scaleX(${scrollPercent / 100})`;
        });

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationDelay = '0s';
                    entry.target.classList.add('text-reveal');
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.addEventListener('DOMContentLoaded', function() {
            const animatedElements = document.querySelectorAll('.feature-card, .testimonial-card');
            animatedElements.forEach((el, index) => {
                el.style.animationDelay = `${index * 0.1}s`;
                observer.observe(el);
            });
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Parallax effect for background elements
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const parallaxElements = document.querySelectorAll('.floating-animation');
            
            parallaxElements.forEach((element, index) => {
                const speed = 0.5 + (index * 0.1);
                element.style.transform = `translateY(${scrolled * speed}px)`;
            });
        });

        // ========== ADVANCED INTERACTIONS ==========
        
        // 3D Card Tilt Effect
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card-3d');
            
            cards.forEach(card => {
                card.addEventListener('mousemove', function(e) {
                    const rect = card.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    
                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;
                    
                    const rotateX = (y - centerY) / 10;
                    const rotateY = (centerX - x) / 10;
                    
                    card.style.setProperty('--rotate-x', `${rotateX}deg`);
                    card.style.setProperty('--rotate-y', `${rotateY}deg`);
                });
                
                card.addEventListener('mouseleave', function() {
                    card.style.setProperty('--rotate-x', '0deg');
                    card.style.setProperty('--rotate-y', '0deg');
                });
            });
        });

        // Magnetic Button Effect
        document.addEventListener('DOMContentLoaded', function() {
            const magneticButtons = document.querySelectorAll('.magnetic-button');
            
            magneticButtons.forEach(button => {
                button.addEventListener('mousemove', function(e) {
                    const rect = button.getBoundingClientRect();
                    const x = e.clientX - rect.left - rect.width / 2;
                    const y = e.clientY - rect.top - rect.height / 2;
                    
                    const moveX = x * 0.3;
                    const moveY = y * 0.3;
                    
                    button.style.setProperty('--magnetic-x', `${moveX}px`);
                    button.style.setProperty('--magnetic-y', `${moveY}px`);
                });
                
                button.addEventListener('mouseleave', function() {
                    button.style.setProperty('--magnetic-x', '0px');
                    button.style.setProperty('--magnetic-y', '0px');
                });
            });
        });

        // Scroll Reveal Animation with Intersection Observer
        document.addEventListener('DOMContentLoaded', function() {
            const scrollRevealElements = document.querySelectorAll('.scroll-reveal');
            
            const revealObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('revealed');
                        revealObserver.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });
            
            scrollRevealElements.forEach(el => {
                revealObserver.observe(el);
            });
        });

        // Spotlight Effect
        document.addEventListener('DOMContentLoaded', function() {
            const spotlightElements = document.querySelectorAll('.spotlight-effect');
            
            spotlightElements.forEach(element => {
                element.addEventListener('mousemove', function(e) {
                    const rect = element.getBoundingClientRect();
                    const x = ((e.clientX - rect.left) / rect.width) * 100;
                    const y = ((e.clientY - rect.top) / rect.height) * 100;
                    
                    element.style.setProperty('--spotlight-x', `${x}%`);
                    element.style.setProperty('--spotlight-y', `${y}%`);
                });
            });
        });

        // Parallax Layers on Scroll
        document.addEventListener('DOMContentLoaded', function() {
            window.addEventListener('scroll', function() {
                const scrolled = window.pageYOffset;
                const parallaxLayers = document.querySelectorAll('.parallax-layer');
                
                parallaxLayers.forEach((layer, index) => {
                    const speed = 0.2 + (index * 0.15);
                    const yPos = -(scrolled * speed);
                    layer.style.transform = `translateY(${yPos}px)`;
                });
            });
        });

        // Smooth performance for animations
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            document.documentElement.style.setProperty('--animation-duration', '0.01ms');
        }

        // ========== PHOTO GALLERY FUNCTIONALITY ==========
        
        // Check if user is admin (set from server)
        @php
            $isAdmin = auth()->check() && auth()->user()->isAdmin();
        @endphp
        const isAdmin = @json($isAdmin);
        
        // Load photos from API
        async function loadPhotos() {
            try {
                const response = await fetch('/api/landing-photos');
                const data = await response.json();
                
                const galleryContainer = document.getElementById('photo-gallery');
                const loadingElement = document.getElementById('gallery-loading');
                const emptyElement = document.getElementById('gallery-empty');
                
                if (!galleryContainer) return;
                
                if (data.success && data.photos && data.photos.length > 0) {
                    if (loadingElement) loadingElement.classList.add('hidden');
                    if (emptyElement) emptyElement.classList.add('hidden');
                    
                    // Clear existing photos (except loading/empty)
                    const existingPhotos = galleryContainer.querySelectorAll('.photo-item');
                    existingPhotos.forEach(photo => photo.remove());
                    
                    // Add photos to gallery
                    data.photos.forEach((photo, index) => {
                        const photoCard = createPhotoCard(photo, index);
                        galleryContainer.appendChild(photoCard);
                    });
                } else {
                    if (loadingElement) loadingElement.classList.add('hidden');
                    if (emptyElement) emptyElement.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error loading photos:', error);
                const loadingElement = document.getElementById('gallery-loading');
                const emptyElement = document.getElementById('gallery-empty');
                if (loadingElement) loadingElement.classList.add('hidden');
                if (emptyElement) emptyElement.classList.remove('hidden');
            }
        }

        // Create photo card element
        function createPhotoCard(photo, index) {
            const card = document.createElement('div');
            card.className = 'photo-item group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:scale-105 cursor-pointer';
            card.style.animationDelay = `${index * 0.1}s`;
            card.classList.add('stagger-item');
            
            card.innerHTML = `
                <div class="aspect-w-16 aspect-h-12 bg-gray-200 overflow-hidden">
                    <img src="${photo.image_url}" 
                         alt="${photo.title || 'Gallery photo'}" 
                         class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-700"
                         loading="lazy">
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        ${photo.title ? `<h3 class="text-lg font-bold mb-2">${photo.title}</h3>` : ''}
                        ${photo.description ? `<p class="text-sm text-white/90 line-clamp-2">${photo.description}</p>` : ''}
                    </div>
                </div>
                ${isAdmin ? `
                <button onclick="deletePhoto(${photo.id})" 
                        class="absolute top-4 right-4 bg-red-500 hover:bg-red-600 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
                ` : ''}
            `;
            
            // Add click handler for lightbox
            card.addEventListener('click', () => {
                openLightbox(photo);
            });
            
            return card;
        }

        // Open lightbox/modal for photo
        function openLightbox(photo) {
            const lightbox = document.createElement('div');
            lightbox.className = 'fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4';
            lightbox.style.animation = 'fadeIn 0.3s ease-out';
            
            lightbox.innerHTML = `
                <div class="relative max-w-4xl w-full">
                    <button onclick="this.closest('.fixed').remove()" 
                            class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    <img src="${photo.image_url}" 
                         alt="${photo.title || 'Gallery photo'}" 
                         class="w-full h-auto rounded-lg shadow-2xl">
                    ${photo.title || photo.description ? `
                    <div class="mt-4 text-white text-center">
                        ${photo.title ? `<h3 class="text-2xl font-bold mb-2">${photo.title}</h3>` : ''}
                        ${photo.description ? `<p class="text-gray-300">${photo.description}</p>` : ''}
                    </div>
                    ` : ''}
                </div>
            `;
            
            lightbox.addEventListener('click', (e) => {
                if (e.target === lightbox) {
                    lightbox.remove();
                }
            });
            
            document.body.appendChild(lightbox);
        }

        // Delete photo (admin only)
        async function deletePhoto(photoId) {
            if (!confirm('Apakah Anda yakin ingin menghapus foto ini?')) {
                return;
            }
            
            try {
                const response = await fetch(`/admin/landing-photos/${photoId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Reload photos
                    loadPhotos();
                } else {
                    alert('Gagal menghapus foto: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error deleting photo:', error);
                alert('Terjadi kesalahan saat menghapus foto');
            }
        }


        // Handle photo upload form
        document.addEventListener('DOMContentLoaded', function() {
            const uploadForm = document.getElementById('photo-upload-form');
            
            if (uploadForm) {
                uploadForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData();
                    const photoInput = document.getElementById('photo-input');
                    const titleInput = document.getElementById('photo-title');
                    const descriptionInput = document.getElementById('photo-description');
                    const orderInput = document.getElementById('photo-order');
                    const uploadBtnText = document.getElementById('upload-btn-text');
                    const uploadBtnLoading = document.getElementById('upload-btn-loading');
                    const submitBtn = uploadForm.querySelector('button[type="submit"]');
                    
                    if (!photoInput.files[0]) {
                        alert('Please select a photo to upload');
                        return;
                    }
                    
                    formData.append('photo', photoInput.files[0]);
                    if (titleInput.value) formData.append('title', titleInput.value);
                    if (descriptionInput.value) formData.append('description', descriptionInput.value);
                    formData.append('order', orderInput.value || 0);
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                    
                    // Show loading state
                    uploadBtnText.classList.add('hidden');
                    uploadBtnLoading.classList.remove('hidden');
                    submitBtn.disabled = true;
                    
                    try {
                        const response = await fetch('/admin/landing-photos/upload', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'Accept': 'application/json',
                            }
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            // Reset form
                            uploadForm.reset();
                            document.getElementById('photo-order').value = 0;
                            
                            // Reload photos
                            await loadPhotos();
                            
                            alert('Photo uploaded successfully!');
                        } else {
                            alert('Upload failed: ' + (data.message || 'Unknown error'));
                        }
                    } catch (error) {
                        console.error('Error uploading photo:', error);
                        alert('Terjadi kesalahan saat upload foto');
                    } finally {
                        // Reset loading state
                        uploadBtnText.classList.remove('hidden');
                        uploadBtnLoading.classList.add('hidden');
                        submitBtn.disabled = false;
                    }
                });
            }
            
            // Load photos on page load
            loadPhotos();
        });

        // Add fadeIn animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
        `;
        document.head.appendChild(style);

        // FAQ Toggle Function
        function toggleFaq(button) {
            const answer = button.nextElementSibling;
            const icon = button.querySelector('.faq-icon');
            const isOpen = !answer.classList.contains('hidden');
            
            // Close all FAQs
            document.querySelectorAll('.faq-answer').forEach(item => {
                item.classList.add('hidden');
            });
            document.querySelectorAll('.faq-icon').forEach(item => {
                item.style.transform = 'rotate(0deg)';
            });
            
            // Toggle current FAQ
            if (isOpen) {
                answer.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            } else {
                answer.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            }
        }

        // How It Works Toggle Function
        function toggleHowItWorks(button) {
            if (!button) return;
            
            // Find the content div - try nextElementSibling first, then search by class
            let content = button.nextElementSibling;
            if (!content || !content.classList.contains('how-it-works-content')) {
                const parent = button.closest('.how-it-works-item');
                if (parent) {
                    content = parent.querySelector('.how-it-works-content');
                }
            }
            
            if (!content) return;
            
            const icon = button.querySelector('.how-it-works-icon');
            const step = button.getAttribute('data-step');
            const isOpen = !content.classList.contains('hidden');
            
            // Close all accordions first
            document.querySelectorAll('.how-it-works-content').forEach(item => {
                item.classList.add('hidden');
            });
            document.querySelectorAll('.how-it-works-icon').forEach(item => {
                item.style.transform = 'rotate(0deg)';
            });
            
            // Toggle current item
            if (isOpen) {
                content.classList.add('hidden');
                if (icon) icon.style.transform = 'rotate(0deg)';
            } else {
                content.classList.remove('hidden');
                if (icon) icon.style.transform = 'rotate(180deg)';
                
                // Change image based on step
                if (step) {
                    changeHowItWorksImage(step);
                }
            }
        }

        // Change Image Function
        function changeHowItWorksImage(step) {
            // Hide all images with fade out
            document.querySelectorAll('.how-it-works-image').forEach(img => {
                img.style.opacity = '0';
                setTimeout(() => {
                    img.classList.add('hidden');
                }, 250);
            });
            
            // Show selected step image with fade in
            setTimeout(() => {
                const targetImg = document.getElementById(`how-it-works-img-${step}`);
                if (targetImg) {
                    targetImg.classList.remove('hidden');
                    setTimeout(() => {
                        targetImg.style.opacity = '1';
                    }, 50);
                }
            }, 250);
        }

        // Initialize: Set default image (Step 1) on page load
        document.addEventListener('DOMContentLoaded', function() {
            const defaultImg = document.getElementById('how-it-works-img-1');
            if (defaultImg) {
                defaultImg.style.opacity = '1';
                defaultImg.classList.remove('hidden');
            }
        });
        // Scroll Animations with Intersection Observer (Lightweight)
        (function() {
            'use strict';
            
            // Check if Intersection Observer is supported
            if (!('IntersectionObserver' in window)) {
                // Fallback: show all elements immediately
                document.querySelectorAll('.scroll-fade-in, .scroll-slide-left, .scroll-slide-right, .scroll-scale').forEach(el => {
                    el.classList.add('visible');
                });
                return;
            }

            // Enhanced observer with better performance
            const observerOptions = {
                root: null,
                rootMargin: '0px 0px -80px 0px', // Trigger when element is 80px from viewport
                threshold: [0, 0.1, 0.2] // Multiple thresholds for smoother detection
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // Add visible class with slight delay for smoother animation
                        setTimeout(() => {
                            entry.target.classList.add('visible');
                        }, 50);
                        // Unobserve after animation to improve performance
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            // Observe all elements with scroll animation classes
            document.addEventListener('DOMContentLoaded', () => {
                const animatedElements = document.querySelectorAll(
                    '.scroll-fade-in, .scroll-slide-left, .scroll-slide-right, .scroll-scale, .scroll-rotate, .scroll-blur, .scroll-bounce, .scroll-flip, .scroll-stagger'
                );
                
                animatedElements.forEach(el => {
                    observer.observe(el);
                });
            });
        })();

        // Logika Carousel Testimonial
    let currentTesti = 0;
    const totalTesti = 3; // Sesuaikan dengan jumlah slide yang Anda miliki
    
    function moveTestimonial(direction) {
        currentTesti += direction;
        
        // Looping (Jika sedang di slide terakhir, kembali ke awal)
        if (currentTesti < 0) {
            currentTesti = totalTesti - 1;
        } else if (currentTesti >= totalTesti) {
            currentTesti = 0;
        }
        
        // Geser track (wajib ada id="testimonial-track" di div wrapper-nya)
        const track = document.getElementById('testimonial-track');
        if (track) {
            track.style.transform = `translateX(-${currentTesti * 100}%)`;
        }
        
        // Update angka text (contoh: 01 / 03)
        const counter = document.getElementById('testi-counter');
        if (counter) {
            counter.innerText = `0${currentTesti + 1} / 0${totalTesti}`;
        }
    }

    function toggleHowItWorks(button) {
        const step = parseInt(button.getAttribute('data-step'));
        const allItems = document.querySelectorAll('.how-it-works-item');
        const allContents = document.querySelectorAll('.how-it-works-content');
        const allIcons = document.querySelectorAll('.how-it-works-icon');

        allItems.forEach((item, i) => {
            const n = i + 1;
            const content = allContents[i];
            const icon = allIcons[i];
            const dot = document.getElementById('hiw-dot-' + n);

            if (n === step) {
                // Cek apakah sudah terbuka
                const isOpen = !content.classList.contains('hidden');
                if (isOpen) return;

                item.classList.remove('border-gray-200');
                item.classList.add('border-2', 'border-[#d983e4]');
                content.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
                if (dot) { dot.style.width = '24px'; dot.style.backgroundColor = '#d983e4'; }
                changeHowItWorksImage(n);
            } else {
                item.classList.remove('border-2', 'border-[#d983e4]');
                item.classList.add('border-gray-200');
                content.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
                if (dot) { dot.style.width = '8px'; dot.style.backgroundColor = '#e5e7eb'; }
            }
        });
    }

    function changeHowItWorksImage(step) {
        document.querySelectorAll('.how-it-works-image').forEach(img => {
            img.style.opacity = '0';
            setTimeout(() => img.classList.add('hidden'), 250);
        });
        setTimeout(() => {
            const target = document.getElementById('how-it-works-img-' + step);
            if (target) {
                target.classList.remove('hidden');
                setTimeout(() => { target.style.opacity = '1'; }, 30);
            }
        }, 250);
    }

    // Init step 1 terbuka saat load
    document.addEventListener('DOMContentLoaded', function () {
        const img1 = document.getElementById('how-it-works-img-1');
        if (img1) img1.style.opacity = '1';
    });

    // ========== WELCOME POPUP FUNCTIONALITY ==========
    document.addEventListener('DOMContentLoaded', function() {
        const popup = document.getElementById('welcome-popup');
        const popupContent = document.getElementById('welcome-popup-content');
        
        // Tampilkan popup secara langsung (dengan delay 500ms untuk efek animasi)
        setTimeout(() => {
            popup.classList.remove('hidden');
            // Trigger reflow agar animasi CSS jalan
            void popup.offsetWidth;
            popup.classList.remove('opacity-0');
            popupContent.classList.remove('scale-95');
            popupContent.classList.add('scale-100');
        }, 500);

        // Tutup popup jika user klik di area luar gambar (backdrop gelap)
        popup.addEventListener('click', function(e) {
            if (e.target === popup) {
                closeWelcomePopup();
            }
        });
    });

    // Fungsi tombol Close
    function closeWelcomePopup() {
        const popup = document.getElementById('welcome-popup');
        const popupContent = document.getElementById('welcome-popup-content');
        
        // Mulai animasi menghilang
        popup.classList.add('opacity-0');
        popupContent.classList.remove('scale-100');
        popupContent.classList.add('scale-95');
        
        // Sembunyikan elemen dari layar sepenuhnya setelah animasi selesai (300ms)
        setTimeout(() => {
            popup.classList.add('hidden');
        }, 300);
    }

    // Toggle widget buka/tutup
    function openContactWidget() {
        const widget = document.getElementById('contact-widget');
        widget.classList.remove('scale-95', 'opacity-0', 'pointer-events-none');
        widget.classList.add('scale-100', 'opacity-100');
    }
 
    function closeContactWidget() {
        const widget = document.getElementById('contact-widget');
        widget.classList.add('scale-95', 'opacity-0', 'pointer-events-none');
        widget.classList.remove('scale-100', 'opacity-100');
    }
 
    // Tampilkan screen berdasarkan topik yang dipilih
    function showContactTopic(type, subject) {
        hideAllScreens();
        if (type === 'whatsapp') {
            document.getElementById('contact-screen-3').classList.remove('hidden');
        } else {
            document.getElementById('contact-subject-hidden').value = subject;
            document.getElementById('contact-screen-2').classList.remove('hidden');
        }
    }
 
    function backToScreen1() {
        hideAllScreens();
        resetForm();
        document.getElementById('contact-screen-1').classList.remove('hidden');
    }
 
    function hideAllScreens() {
        ['contact-screen-1', 'contact-screen-2', 'contact-screen-3', 'contact-screen-success']
            .forEach(id => document.getElementById(id).classList.add('hidden'));
    }
 
    function resetForm() {
        const form = document.getElementById('contact-form');
        if (form) form.reset();
        document.getElementById('contact-error').classList.add('hidden');
        setSubmitLoading(false);
    }
 
    function setSubmitLoading(loading) {
        const btn = document.getElementById('contact-submit-btn');
        const text = document.getElementById('contact-btn-text');
        const spinner = document.getElementById('contact-btn-spinner');
        btn.disabled = loading;
        text.textContent = loading ? 'Mengirim...' : 'Kirim Pesan';
        spinner.classList.toggle('hidden', !loading);
    }
 
    // Submit form via AJAX
    async function submitContactForm(event) {
        event.preventDefault();
        const errorEl = document.getElementById('contact-error');
        errorEl.classList.add('hidden');
        setSubmitLoading(true);
 
        const form = event.target;
        const formData = new FormData(form);
 
        try {
            const response = await fetch('{{ route("contact.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            });
 
            const data = await response.json();
 
            if (data.success) {
                hideAllScreens();
                document.getElementById('contact-screen-success').classList.remove('hidden');
            } else {
                // Tampilkan pesan error dari server
                const msg = data.message || 'Terjadi kesalahan. Silakan coba lagi.';
                errorEl.textContent = msg;
                errorEl.classList.remove('hidden');
            }
        } catch (err) {
            errorEl.textContent = 'Gagal mengirim pesan. Periksa koneksi internet Anda.';
            errorEl.classList.remove('hidden');
        } finally {
            setSubmitLoading(false);
        }
    }
 
    // Tutup widget jika klik di luar area widget
    document.addEventListener('click', function (e) {
        const widget = document.getElementById('contact-widget');
        const fab = document.getElementById('contact-fab');
        if (widget && fab && !widget.contains(e.target) && !fab.contains(e.target)) {
            const isOpen = !widget.classList.contains('pointer-events-none');
            if (isOpen) closeContactWidget();
        }
    });
    </script>
    </body>
</html>