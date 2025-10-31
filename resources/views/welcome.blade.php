<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TraKerja - Professional Job Application Management Platform</title>
    <meta name="description" content="Transform your job search with TraKerja. The most sophisticated job application tracking platform designed for ambitious professionals in Indonesia.">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

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
            </style>
    </head>
<body class="font-sans antialiased bg-white">
    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-md border-b border-gray-200/50 shadow-sm sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">
                <!-- Left Section: Brand -->
                <div class="flex items-center">
                    <div class="shrink-0 flex items-center">
                        <a href="{{ url('/') }}" class="group">
                            <span class="text-xl font-bold bg-gradient-to-r from-[#d983e4] to-[#4e71c5] bg-clip-text text-transparent">
                                TraKerja
                            </span>
                        </a>
                    </div>
                </div>

                <!-- Right Section: Auth Links -->
                <div class="flex items-center space-x-3">
            @if (Route::has('login'))
                    @auth
                            <a href="{{ url('/tracker') }}" 
                               class="bg-gradient-to-r from-[#d983e4] to-[#4e71c5] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:shadow-lg transition-all duration-200">
                            Dashboard
                        </a>
                    @else
                            <a href="{{ route('login') }}" 
                               class="text-gray-600 hover:text-[#d983e4] text-sm font-medium transition-colors duration-200">
                                Login
                            </a>
                        @if (Route::has('register'))
                                <a href="{{ route('register') }}" 
                                   class="bg-gradient-to-r from-[#d983e4] to-[#4e71c5] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:shadow-lg transition-all duration-200">
                                    Daftar
                            </a>
                        @endif
                    @endauth
                    @endif
                </div>
            </div>
        </div>
                </nav>

    <!-- Hero Section -->
    <section class="hero-section relative min-h-screen flex items-center justify-center overflow-hidden gradient-mesh">
        <!-- Particle Background -->
        <div class="particles hidden md:block">
            <div class="particle" style="width: 80px; height: 80px;"></div>
            <div class="particle" style="width: 60px; height: 60px;"></div>
            <div class="particle" style="width: 100px; height: 100px;"></div>
            <div class="particle" style="width: 70px; height: 70px;"></div>
            <div class="particle" style="width: 90px; height: 90px;"></div>
            <div class="particle" style="width: 50px; height: 50px;"></div>
            <div class="particle" style="width: 75px; height: 75px;"></div>
            <div class="particle" style="width: 85px; height: 85px;"></div>
            <div class="particle" style="width: 65px; height: 65px;"></div>
        </div>
        
        <!-- Enhanced Background Blobs with Liquid Animation -->
        <div class="absolute top-20 right-20 w-40 h-40 bg-gradient-to-r from-primary-600/20 to-secondary-500/20 mix-blend-multiply filter blur-2xl floating-animation liquid-blob hidden md:block"></div>
        <div class="absolute bottom-20 left-20 w-32 h-32 bg-gradient-to-r from-secondary-500/20 to-primary-600/20 mix-blend-multiply filter blur-2xl floating-animation liquid-blob hidden md:block" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-60 h-60 bg-gradient-to-r from-primary-600/10 to-secondary-500/10 mix-blend-multiply filter blur-3xl pulse-animation liquid-blob hidden md:block"></div>
        <div class="absolute top-1/4 left-1/4 w-20 h-20 bg-gradient-to-r from-primary-700/30 to-secondary-500/30 mix-blend-multiply filter blur-xl floating-animation liquid-blob hidden md:block" style="animation-delay: 4s;"></div>
        <div class="absolute bottom-1/4 right-1/4 w-24 h-24 bg-gradient-to-r from-secondary-500/30 to-primary-600/30 mix-blend-multiply filter blur-xl floating-animation liquid-blob hidden md:block" style="animation-delay: 1s;"></div>
        
        <!-- Cyber Grid Overlay -->
        <div class="absolute inset-0 cyber-grid opacity-30 hidden md:block"></div>
        
        <div class="relative z-10 max-w-6xl mx-auto px-4 text-center">
            <!-- Animated Badge -->
            <div class="inline-flex items-center px-6 py-3 rounded-full bg-white/90 text-sm font-medium text-primary-600 mb-8 text-reveal shadow-lg" style="animation-delay: 0.2s;">
                <span class="w-3 h-3 bg-secondary-500 rounded-full mr-3 pulse-animation"></span>
                <span class="typing-animation">Smart Job Application Tracking</span>
            </div>
            
            <!-- Main Heading with Advanced Typography -->
            <h1 class="hero-title text-5xl md:text-7xl font-bold text-gray-900 mb-8 leading-tight text-reveal" style="animation-delay: 0.4s;">
                Kelola Proses Rekrutmen dengan 
                <span class="gradient-text-animated">
                    TraKerja
                </span>
            </h1>
            
            <!-- Subheading with Animation -->
            <p class="hero-subtitle text-xl md:text-2xl text-gray-600 mb-12 max-w-3xl mx-auto text-reveal" style="animation-delay: 0.6s;">
                Platform tracking job application yang 
                <span class="font-semibold text-[#d983e4]">simple</span> dan 
                <span class="font-semibold text-[#4e71c5]">efektif</span> 
                untuk job seeker Indonesia
            </p>
            
            <!-- Enhanced CTA Buttons with Magnetic & Glow Effects -->
            <div class="cta-buttons flex flex-col sm:flex-row gap-6 justify-center mb-16 text-reveal" style="animation-delay: 0.8s;">
                @auth
                    <a href="{{ url('/tracker') }}" 
                       class="cta-button magnetic-button glow-effect bg-gradient-to-r from-[#d983e4] to-[#4e71c5] text-white px-10 py-4 rounded-xl font-bold text-lg hover:shadow-2xl transition-all duration-300 hover:scale-105">
                        <span class="relative z-10">Buka Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('register') }}" 
                       class="cta-button magnetic-button glow-effect bg-gradient-to-r from-[#d983e4] to-[#4e71c5] text-white px-10 py-4 rounded-xl font-bold text-lg hover:shadow-2xl transition-all duration-300 hover:scale-105">
                        <span class="relative z-10">Mulai Gratis</span>
                    </a>
                    <a href="{{ route('login') }}" 
                       class="cta-button magnetic-button border-2 border-[#d983e4] text-[#d983e4] px-10 py-4 rounded-xl font-bold text-lg hover:bg-[#d983e4] hover:text-white transition-all duration-300 hover:shadow-xl hover:scale-105">
                        <span class="relative z-10">Login</span>
                    </a>
                @endauth
            </div>
            
            <!-- Enhanced Trust Indicators -->
            <div class="trust-indicators flex flex-wrap items-center justify-center gap-8 text-sm text-gray-600 text-reveal" style="animation-delay: 1s;">
                <div class="flex items-center bg-white/80 px-4 py-2 rounded-full hover:scale-105 transition-all duration-300 shadow-sm">
                    <svg class="w-5 h-5 text-secondary-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="font-semibold">Setup 2 Menit</span>
                </div>
                <div class="flex items-center bg-white/80 px-4 py-2 rounded-full hover:scale-105 transition-all duration-300 shadow-sm">
                    <svg class="w-5 h-5 text-secondary-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="font-semibold">Data Aman & Privat</span>
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-gray-400 animate-bounce">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>


    <!-- Features Section -->
    <section class="py-20 bg-gradient-to-br from-slate-50 via-white to-blue-50 relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute top-0 left-0 w-full h-full cyber-grid opacity-20 hidden md:block"></div>
        <div class="absolute top-20 right-20 w-32 h-32 bg-gradient-to-r from-primary-600/10 to-secondary-500/10 rounded-full blur-2xl floating-animation hidden md:block"></div>
        <div class="absolute bottom-20 left-20 w-24 h-24 bg-gradient-to-r from-secondary-500/10 to-primary-600/10 rounded-full blur-2xl floating-animation hidden md:block" style="animation-delay: 3s;"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 rounded-full glass-effect text-sm font-medium text-primary-600 mb-6">
                    <span class="w-2 h-2 bg-secondary-500 rounded-full mr-2 pulse-animation"></span>
                    Powerful Features
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                    Fitur-Fitur <span class="gradient-text">TraKerja</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Tools sederhana tapi powerful untuk mengelola pencarian kerja Anda dengan teknologi terdepan
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card stagger-item card-3d hover-lift spotlight-effect group glass-enhanced rounded-2xl p-8 shadow-lg border border-gray-100/50 hover:border-primary-600/20 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-600/5 to-secondary-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 card-3d-inner">
                        <div class="w-16 h-16 bg-gradient-to-br from-primary-600 to-[#1e40af] rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h2a2 2 0 002-2z"></path>
                        </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4 group-hover:text-primary-600 transition-colors duration-300">Kanban Board</h3>
                        <p class="text-gray-600 leading-relaxed">Kelola lamaran kerja dengan drag & drop yang mudah. Lihat progress lamaran Anda dalam satu tampilan yang intuitif.</p>
                    </div>
                </div>
                
                <!-- Feature 2 -->
                <div class="feature-card stagger-item card-3d hover-lift spotlight-effect group glass-enhanced rounded-2xl p-8 shadow-lg border border-gray-100/50 hover:border-secondary-500/20 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-secondary-500/5 to-primary-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 card-3d-inner">
                        <div class="w-16 h-16 bg-gradient-to-br from-secondary-500 to-secondary-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4 group-hover:text-secondary-500 transition-colors duration-300">Analytics Dashboard</h3>
                        <p class="text-gray-600 leading-relaxed">Lihat statistik lamaran Anda. Platform mana yang paling efektif dan posisi apa yang paling sering dipanggil interview.</p>
                    </div>
                </div>
                
                <!-- Feature 3 -->
                <div class="feature-card stagger-item card-3d hover-lift spotlight-effect group glass-enhanced rounded-2xl p-8 shadow-lg border border-gray-100/50 hover:border-primary-600/20 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-600/5 to-secondary-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 card-3d-inner">
                        <div class="w-16 h-16 bg-gradient-to-br from-primary-600 to-[#1e40af] rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4 group-hover:text-primary-600 transition-colors duration-300">Smart Reminders</h3>
                        <p class="text-gray-600 leading-relaxed">Dapatkan notifikasi untuk jadwal interview, deadline tugas, dan waktu yang tepat untuk follow-up.</p>
                    </div>
                </div>
                
                <!-- Feature 4 -->
                <div class="feature-card stagger-item card-3d hover-lift spotlight-effect group glass-enhanced rounded-2xl p-8 shadow-lg border border-gray-100/50 hover:border-secondary-500/20 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-secondary-500/5 to-primary-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 card-3d-inner">
                        <div class="w-16 h-16 bg-gradient-to-br from-secondary-500 to-secondary-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4 group-hover:text-secondary-500 transition-colors duration-300">Goal Tracking</h3>
                        <p class="text-gray-600 leading-relaxed">Set target mingguan untuk lamaran kerja dan pantau progress Anda. Tetap termotivasi dengan streak counter.</p>
                    </div>
                </div>
                
                <!-- Feature 5 -->
                <div class="feature-card stagger-item card-3d hover-lift spotlight-effect group glass-enhanced rounded-2xl p-8 shadow-lg border border-gray-100/50 hover:border-primary-600/20 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-600/5 to-secondary-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 card-3d-inner">
                        <div class="w-16 h-16 bg-gradient-to-br from-primary-600 to-[#1e40af] rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4 group-hover:text-primary-600 transition-colors duration-300">Real-time Sync</h3>
                        <p class="text-gray-600 leading-relaxed">Akses data Anda di mana saja. Update otomatis di semua device dan browser Anda dengan teknologi cloud.</p>
                    </div>
                </div>
                
                <!-- Feature 6 -->
                <div class="feature-card stagger-item card-3d hover-lift spotlight-effect group glass-enhanced rounded-2xl p-8 shadow-lg border border-gray-100/50 hover:border-secondary-500/20 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-secondary-500/5 to-primary-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 card-3d-inner">
                        <div class="w-16 h-16 bg-gradient-to-br from-secondary-500 to-secondary-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4 group-hover:text-secondary-500 transition-colors duration-300">Data Aman</h3>
                        <p class="text-gray-600 leading-relaxed">Data Anda aman dengan enkripsi end-to-end dan backup otomatis. Privasi Anda adalah prioritas kami.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 bg-gradient-to-br from-gray-50 via-white to-slate-50 relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute top-0 left-0 w-full h-full cyber-grid opacity-10 hidden md:block"></div>
        <div class="absolute top-10 right-10 w-20 h-20 bg-gradient-to-r from-primary-600/20 to-secondary-500/20 rounded-full blur-xl floating-animation hidden md:block"></div>
        <div class="absolute bottom-10 left-10 w-16 h-16 bg-gradient-to-r from-secondary-500/20 to-primary-600/20 rounded-full blur-xl floating-animation hidden md:block" style="animation-delay: 2s;"></div>
        
        <div class="relative z-10 max-w-6xl mx-auto px-4">
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 rounded-full glass-effect text-sm font-medium text-primary-600 mb-6">
                    <span class="w-2 h-2 bg-secondary-500 rounded-full mr-2 pulse-animation"></span>
                    User Stories
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                    Kata <span class="gradient-text">Pengguna</span> TraKerja
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Feedback dari job seeker yang sudah menggunakan TraKerja dan merasakan manfaatnya
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="testimonial-card group bg-white/90 backdrop-blur-sm rounded-2xl p-8 shadow-lg border border-gray-100/50 hover:border-primary-600/20 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-600/5 to-secondary-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="flex items-center mb-6">
                            <div class="w-14 h-14 bg-gradient-to-br from-primary-600 to-[#1e40af] rounded-2xl flex items-center justify-center text-white font-bold text-lg shadow-lg group-hover:scale-110 transition-transform duration-300">
                                S
                            </div>
                            <div class="ml-4">
                                <div class="font-bold text-gray-900 text-lg group-hover:text-primary-600 transition-colors duration-300">Sarah</div>
                                <div class="text-sm text-gray-500 font-medium">Fresh Graduate</div>
                            </div>
                        </div>
                        <div class="flex mb-4">
                            {!! str_repeat('<svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>', 5) !!}
                        </div>
                        <p class="text-gray-600 leading-relaxed italic">"TraKerja membantu saya mengorganisir lamaran kerja dengan baik. Sekarang saya tahu mana yang sudah di-apply dan mana yang perlu di-follow up. Sangat membantu untuk fresh graduate seperti saya!"</p>
                    </div>
                </div>
                
                <!-- Testimonial 2 -->
                <div class="testimonial-card group bg-white/90 backdrop-blur-sm rounded-2xl p-8 shadow-lg border border-gray-100/50 hover:border-secondary-500/20 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-secondary-500/5 to-primary-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="flex items-center mb-6">
                            <div class="w-14 h-14 bg-gradient-to-br from-secondary-500 to-secondary-600 rounded-2xl flex items-center justify-center text-white font-bold text-lg shadow-lg group-hover:scale-110 transition-transform duration-300">
                                A
                            </div>
                            <div class="ml-4">
                                <div class="font-bold text-gray-900 text-lg group-hover:text-secondary-500 transition-colors duration-300">Ahmad</div>
                                <div class="text-sm text-gray-500 font-medium">Career Switcher</div>
                            </div>
                        </div>
                        <div class="flex mb-4">
                            {!! str_repeat('<svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>', 5) !!}
                        </div>
                        <p class="text-gray-600 leading-relaxed italic">"Platform yang simple tapi efektif. Analytics-nya membantu saya tahu platform mana yang paling sering memanggil interview. Perfect untuk career switcher!"</p>
                    </div>
                </div>
                
                <!-- Testimonial 3 -->
                <div class="testimonial-card group bg-white/90 backdrop-blur-sm rounded-2xl p-8 shadow-lg border border-gray-100/50 hover:border-primary-600/20 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-600/5 to-secondary-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="flex items-center mb-6">
                            <div class="w-14 h-14 bg-gradient-to-br from-primary-600 to-[#1e40af] rounded-2xl flex items-center justify-center text-white font-bold text-lg shadow-lg group-hover:scale-110 transition-transform duration-300">
                                M
                            </div>
                            <div class="ml-4">
                                <div class="font-bold text-gray-900 text-lg group-hover:text-primary-600 transition-colors duration-300">Maya</div>
                                <div class="text-sm text-gray-500 font-medium">Job Seeker</div>
                            </div>
                        </div>
                        <div class="flex mb-4">
                            {!! str_repeat('<svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>', 5) !!}
                        </div>
                        <p class="text-gray-600 leading-relaxed italic">"Goal tracking-nya bagus banget! Membantu saya tetap konsisten apply kerja setiap minggu. Akhirnya dapat kerja juga! Terima kasih TraKerja!"</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-20 bg-white relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute top-0 left-0 w-full h-full cyber-grid opacity-5 hidden md:block"></div>
        <div class="absolute top-20 right-20 w-32 h-32 bg-gradient-to-r from-primary-600/10 to-secondary-500/10 rounded-full blur-2xl floating-animation hidden md:block"></div>
        <div class="absolute bottom-20 left-20 w-24 h-24 bg-gradient-to-r from-secondary-500/10 to-primary-600/10 rounded-full blur-2xl floating-animation hidden md:block" style="animation-delay: 2s;"></div>
        
        <div class="relative z-10 max-w-6xl mx-auto px-4">
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 rounded-full glass-effect text-sm font-medium text-primary-600 mb-6">
                    <span class="w-2 h-2 bg-secondary-500 rounded-full mr-2 pulse-animation"></span>
                    Simple Process
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                    Cara Kerja <span class="gradient-text">TraKerja</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Hanya butuh 3 langkah sederhana untuk mengorganisir pencarian kerja Anda
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="text-center group">
                    <div class="relative mb-8">
                        <div class="w-20 h-20 bg-gradient-to-br from-primary-600 to-[#1e40af] rounded-2xl flex items-center justify-center text-white font-bold text-2xl mx-auto shadow-lg group-hover:scale-110 transition-transform duration-300">
                            1
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-secondary-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Daftar & Setup</h3>
                    <p class="text-gray-600 leading-relaxed">Buat akun gratis dalam 2 menit. Tidak perlu verifikasi email yang ribet, langsung bisa pakai!</p>
                </div>
                
                <!-- Step 2 -->
                <div class="text-center group">
                    <div class="relative mb-8">
                        <div class="w-20 h-20 bg-gradient-to-br from-secondary-500 to-secondary-600 rounded-2xl flex items-center justify-center text-white font-bold text-2xl mx-auto shadow-lg group-hover:scale-110 transition-transform duration-300">
                            2
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Tambah Lamaran</h3>
                    <p class="text-gray-600 leading-relaxed">Input detail lamaran kerja Anda. TraKerja akan otomatis mengorganisir dan mengingatkan follow-up.</p>
                </div>
                
                <!-- Step 3 -->
                <div class="text-center group">
                    <div class="relative mb-8">
                        <div class="w-20 h-20 bg-gradient-to-br from-primary-600 to-[#1e40af] rounded-2xl flex items-center justify-center text-white font-bold text-2xl mx-auto shadow-lg group-hover:scale-110 transition-transform duration-300">
                            3
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-secondary-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Track & Analyze</h3>
                    <p class="text-gray-600 leading-relaxed">Pantau progress dan analisis performa lamaran Anda. Dapatkan insight untuk strategi yang lebih efektif.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Problem Solution Section -->
    <section class="py-20 bg-gradient-to-br from-gray-50 via-white to-slate-50 relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute top-0 left-0 w-full h-full cyber-grid opacity-10 hidden md:block"></div>
        <div class="absolute top-10 right-10 w-20 h-20 bg-gradient-to-r from-primary-600/20 to-secondary-500/20 rounded-full blur-xl floating-animation hidden md:block"></div>
        <div class="absolute bottom-10 left-10 w-16 h-16 bg-gradient-to-r from-secondary-500/20 to-primary-600/20 rounded-full blur-xl floating-animation hidden md:block" style="animation-delay: 2s;"></div>
        
        <div class="relative z-10 max-w-6xl mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- Problem Side -->
                <div class="space-y-8">
                    <div>
                        <div class="inline-flex items-center px-4 py-2 rounded-full bg-red-100 text-red-700 text-sm font-medium mb-4">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            Masalah Umum
                        </div>
                        <h2 class="text-4xl font-bold text-gray-900 mb-6">
                            Bingung Kelola <span class="text-red-600">Lamaran Kerja?</span>
                        </h2>
                        <p class="text-lg text-gray-600 mb-8">
                            Job seeker seringkali kesulitan mengorganisir lamaran kerja yang sudah dikirim. 
                            Akibatnya banyak yang terlewat follow-up atau lupa status lamaran.
                        </p>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                <svg class="w-3 h-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <p class="text-gray-600">Lupa sudah apply di mana saja</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                <svg class="w-3 h-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <p class="text-gray-600">Tidak tahu kapan harus follow-up</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                <svg class="w-3 h-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <p class="text-gray-600">Tidak ada tracking progress yang jelas</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                <svg class="w-3 h-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <p class="text-gray-600">Tidak tahu strategi mana yang efektif</p>
                        </div>
                    </div>
                </div>
                
                <!-- Solution Side -->
                <div class="space-y-8">
                    <div>
                        <div class="inline-flex items-center px-4 py-2 rounded-full bg-green-100 text-green-700 text-sm font-medium mb-4">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Solusi TraKerja
                        </div>
                        <h2 class="text-4xl font-bold text-gray-900 mb-6">
                            <span class="gradient-text">TraKerja</span> Solusinya!
                        </h2>
                        <p class="text-lg text-gray-600 mb-8">
                            Platform tracking job application yang dirancang khusus untuk job seeker Indonesia. 
                            Simple, efektif, dan mudah digunakan.
                        </p>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <p class="text-gray-600">Semua lamaran terorganisir dalam satu dashboard</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <p class="text-gray-600">Smart reminder untuk follow-up tepat waktu</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <p class="text-gray-600">Analytics untuk optimasi strategi lamaran</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <p class="text-gray-600">Goal tracking untuk tetap termotivasi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="py-20 bg-white relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute top-0 left-0 w-full h-full cyber-grid opacity-5 hidden md:block"></div>
        <div class="absolute top-20 right-20 w-32 h-32 bg-gradient-to-r from-primary-600/10 to-secondary-500/10 rounded-full blur-2xl floating-animation hidden md:block"></div>
        <div class="absolute bottom-20 left-20 w-24 h-24 bg-gradient-to-r from-secondary-500/10 to-primary-600/10 rounded-full blur-2xl floating-animation hidden md:block" style="animation-delay: 2s;"></div>
        
        <div class="relative z-10 max-w-6xl mx-auto px-4">
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 rounded-full glass-effect text-sm font-medium text-primary-600 mb-6">
                    <span class="w-2 h-2 bg-secondary-500 rounded-full mr-2 pulse-animation"></span>
                    Key Benefits
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                    Mengapa Pilih <span class="gradient-text">TraKerja?</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Platform yang dirancang khusus untuk job seeker Indonesia dengan fitur-fitur yang benar-benar dibutuhkan
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Benefit 1 -->
                <div class="group bg-gradient-to-br from-primary-600/5 to-secondary-500/5 rounded-2xl p-8 border border-gray-100/50 hover:border-primary-600/20 transition-all duration-300 hover:shadow-xl">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary-600 to-[#1e40af] rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4 group-hover:text-primary-600 transition-colors duration-300">100% Gratis</h3>
                    <p class="text-gray-600 leading-relaxed">Tidak ada biaya tersembunyi atau upgrade berbayar. Semua fitur premium tersedia untuk semua pengguna.</p>
                </div>
                
                <!-- Benefit 2 -->
                <div class="group bg-gradient-to-br from-secondary-500/5 to-primary-600/5 rounded-2xl p-8 border border-gray-100/50 hover:border-secondary-500/20 transition-all duration-300 hover:shadow-xl">
                    <div class="w-16 h-16 bg-gradient-to-br from-secondary-500 to-secondary-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4 group-hover:text-secondary-500 transition-colors duration-300">Setup 2 Menit</h3>
                    <p class="text-gray-600 leading-relaxed">Tidak perlu setup rumit. Daftar, login, langsung bisa mulai tracking lamaran kerja Anda.</p>
                </div>
                
                <!-- Benefit 3 -->
                <div class="group bg-gradient-to-br from-primary-600/5 to-secondary-500/5 rounded-2xl p-8 border border-gray-100/50 hover:border-primary-600/20 transition-all duration-300 hover:shadow-xl">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary-600 to-[#1e40af] rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4 group-hover:text-primary-600 transition-colors duration-300">Data Aman</h3>
                    <p class="text-gray-600 leading-relaxed">Data Anda dienkripsi dan disimpan dengan aman. Privasi dan keamanan adalah prioritas utama kami.</p>
                </div>
                
                <!-- Benefit 4 -->
                <div class="group bg-gradient-to-br from-secondary-500/5 to-primary-600/5 rounded-2xl p-8 border border-gray-100/50 hover:border-secondary-500/20 transition-all duration-300 hover:shadow-xl">
                    <div class="w-16 h-16 bg-gradient-to-br from-secondary-500 to-secondary-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4 group-hover:text-secondary-500 transition-colors duration-300">Analytics Cerdas</h3>
                    <p class="text-gray-600 leading-relaxed">Dapatkan insight tentang performa lamaran Anda. Platform mana yang paling efektif dan strategi apa yang berhasil.</p>
                </div>
                
                <!-- Benefit 5 -->
                <div class="group bg-gradient-to-br from-primary-600/5 to-secondary-500/5 rounded-2xl p-8 border border-gray-100/50 hover:border-primary-600/20 transition-all duration-300 hover:shadow-xl">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary-600 to-[#1e40af] rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4 group-hover:text-primary-600 transition-colors duration-300">Goal Tracking</h3>
                    <p class="text-gray-600 leading-relaxed">Set target mingguan dan pantau progress Anda. Tetap termotivasi dengan streak counter dan achievement badges.</p>
                </div>
                
                <!-- Benefit 6 -->
                <div class="group bg-gradient-to-br from-secondary-500/5 to-primary-600/5 rounded-2xl p-8 border border-gray-100/50 hover:border-secondary-500/20 transition-all duration-300 hover:shadow-xl">
                    <div class="w-16 h-16 bg-gradient-to-br from-secondary-500 to-secondary-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4 group-hover:text-secondary-500 transition-colors duration-300">Real-time Sync</h3>
                    <p class="text-gray-600 leading-relaxed">Akses data Anda di mana saja, kapan saja. Update otomatis di semua device dengan teknologi cloud terdepan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 hero-gradient relative overflow-hidden">
        <!-- Advanced Background Elements -->
        <div class="absolute inset-0 cyber-grid opacity-20 hidden md:block"></div>
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-primary-600/20 via-transparent to-secondary-500/20"></div>
        <div class="absolute top-20 right-20 w-40 h-40 bg-white/10 rounded-full blur-3xl floating-animation hidden md:block"></div>
        <div class="absolute bottom-20 left-20 w-32 h-32 bg-white/10 rounded-full blur-3xl floating-animation hidden md:block" style="animation-delay: 3s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-60 h-60 bg-white/5 rounded-full blur-3xl pulse-animation hidden md:block"></div>
        
        <div class="relative z-10 max-w-4xl mx-auto text-center px-4">
            <!-- Animated Badge -->
            <div class="inline-flex items-center px-6 py-3 rounded-full glass-effect text-sm font-medium text-white/90 mb-8 neon-glow">
                <span class="w-3 h-3 bg-white rounded-full mr-3 pulse-animation"></span>
                <span class="loading-dots">Ready to Transform Your Job Search</span>
            </div>
            
            <h2 class="text-4xl md:text-6xl font-bold text-white mb-8 leading-tight">
                Siap Mengorganisir Pencarian Kerja Anda?
            </h2>
            <p class="text-xl text-white/90 mb-12 max-w-3xl mx-auto leading-relaxed">
                Bergabunglah dengan job seeker Indonesia yang sudah menggunakan TraKerja. 
                Mulai gratis dan rasakan perbedaannya dalam mengelola lamaran kerja Anda.
            </p>
            
            @guest
                <div class="flex flex-col sm:flex-row gap-6 justify-center mb-12">
                    <a href="{{ route('register') }}" 
                       class="cta-button bg-white text-[#d983e4] px-10 py-4 rounded-2xl font-bold text-lg hover:shadow-2xl transition-all duration-300 hover:scale-105 relative overflow-hidden group">
                        <span class="relative z-10 flex items-center justify-center">
                            <svg class="w-6 h-6 mr-2 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        Daftar Gratis Sekarang
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-[#d983e4] to-[#4e71c5] opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    </a>
                    <a href="{{ route('login') }}" 
                       class="cta-button border-2 border-white text-white px-10 py-4 rounded-2xl font-bold text-lg hover:bg-white hover:text-[#d983e4] transition-all duration-300 hover:shadow-xl relative overflow-hidden group">
                        <span class="relative z-10 flex items-center justify-center">
                            <svg class="w-6 h-6 mr-2 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                        Login
                        </span>
                    </a>
                </div>
            @else
                <a href="{{ url('/tracker') }}" 
                   class="cta-button bg-white text-[#d983e4] px-10 py-4 rounded-2xl font-bold text-lg hover:shadow-2xl transition-all duration-300 inline-block relative overflow-hidden group">
                    <span class="relative z-10 flex items-center justify-center">
                        <svg class="w-6 h-6 mr-2 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    Buka Dashboard
                    </span>
                    <div class="absolute inset-0 bg-gradient-to-r from-[#d983e4] to-[#4e71c5] opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                </a>
            @endguest
            
            <!-- Enhanced Trust Indicators -->
            <div class="flex flex-wrap items-center justify-center gap-8 text-white/80 text-sm">
                <div class="flex items-center glass-effect px-4 py-2 rounded-full hover:scale-105 transition-all duration-300">
                    <svg class="w-5 h-5 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-semibold">Setup dalam 2 Menit</span>
                </div>
                <div class="flex items-center glass-effect px-4 py-2 rounded-full hover:scale-105 transition-all duration-300">
                    <svg class="w-5 h-5 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <span class="font-semibold">Data Aman & Privat</span>
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
                        Platform tracking job application yang dirancang khusus untuk job seeker Indonesia. 
                        Simple, efektif, dan mudah digunakan.
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
                                <a href="mailto:infoteknalogi@gmail.com" class="text-gray-700 hover:text-primary-600 transition-colors duration-200 font-medium text-sm">
                                    infoteknalogi@gmail.com
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
                    </div>
                </div>

                <!-- Company Info -->
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Dikelola oleh</p>
                        <p class="text-gray-900 font-semibold text-sm">PT Teknologi Transformasi Digital</p>
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
                        For job seekers in Indonesia
                    </p>
                    <div class="flex items-center space-x-4">
                        <a href="https://www.instagram.com/teknalogi.id/" target="_blank" rel="noopener noreferrer" 
                           class="text-gray-400 hover:text-primary-600 transition-colors duration-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <a href="mailto:infoteknalogi@gmail.com" 
                           class="text-gray-400 hover:text-primary-600 transition-colors duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

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
    </script>
    </body>
</html>
