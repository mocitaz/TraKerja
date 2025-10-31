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

            /* Lightning Glow Effect */
            @keyframes lightningGlow {
                0%, 100% {
                    box-shadow: 0 0 20px rgba(217, 131, 228, 0.4),
                                0 0 40px rgba(78, 113, 197, 0.3),
                                0 0 60px rgba(217, 131, 228, 0.2);
                }
                50% {
                    box-shadow: 0 0 30px rgba(217, 131, 228, 0.6),
                                0 0 60px rgba(78, 113, 197, 0.5),
                                0 0 90px rgba(217, 131, 228, 0.4);
                }
            }
            
            .lightning-glow {
                animation: lightningGlow 2s ease-in-out infinite;
            }
            
            /* Ultra Smooth Gradient Flow */
            @keyframes ultraGradientFlow {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            
            .ultra-gradient {
                background: linear-gradient(-45deg, #d983e4, #4e71c5, #c573dc, #5b7dd4);
                background-size: 400% 400%;
                animation: ultraGradientFlow 10s ease infinite;
            }
            
            /* Neon Border Effect */
            .neon-border {
                position: relative;
            }
            
            .neon-border::before {
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
                animation: ultraGradientFlow 3s linear infinite;
                opacity: 0.6;
            }
            
            /* Holographic Shimmer */
            @keyframes holographicShimmer {
                0% { background-position: -200% center; }
                100% { background-position: 200% center; }
            }
            
            .holographic-shimmer {
                background: linear-gradient(
                    90deg,
                    rgba(217, 131, 228, 0.8) 0%,
                    rgba(78, 113, 197, 0.8) 50%,
                    rgba(217, 131, 228, 0.8) 100%
                );
                background-size: 200% 100%;
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                animation: holographicShimmer 3s linear infinite;
            }
            
            /* Ultra Smooth Float */
            @keyframes ultraFloat {
                0%, 100% { 
                    transform: translateY(0px) translateX(0px) rotate(0deg);
                    opacity: 1;
                }
                33% { 
                    transform: translateY(-20px) translateX(10px) rotate(2deg);
                    opacity: 0.9;
                }
                66% { 
                    transform: translateY(-10px) translateX(-10px) rotate(-2deg);
                    opacity: 0.95;
                }
            }
            
            .ultra-float {
                animation: ultraFloat 8s ease-in-out infinite;
            }
            
            /* Particle Glow */
            .particle-glow {
                position: relative;
            }
            
            .particle-glow::after {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                width: 200%;
                height: 200%;
                background: radial-gradient(circle, rgba(217, 131, 228, 0.3) 0%, transparent 70%);
                transform: translate(-50%, -50%);
                animation: pulse 3s ease-in-out infinite;
                pointer-events: none;
            }
            
            /* Ultra Smooth Scale */
            .ultra-scale {
                transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            }
            
            .ultra-scale:hover {
                transform: scale(1.08);
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

    <!-- Hero Section - Ultra Compact & Amazing -->
    <section class="hero-section relative min-h-[90vh] flex items-center justify-center overflow-hidden gradient-mesh">
        <!-- Advanced Particle System -->
        <div class="particles hidden md:block">
            <div class="particle" style="width: 100px; height: 100px; left: 10%;"></div>
            <div class="particle" style="width: 80px; height: 80px; left: 20%;"></div>
            <div class="particle" style="width: 120px; height: 120px; left: 30%;"></div>
            <div class="particle" style="width: 90px; height: 90px; left: 40%;"></div>
            <div class="particle" style="width: 70px; height: 70px; left: 50%;"></div>
            <div class="particle" style="width: 110px; height: 110px; left: 60%;"></div>
            <div class="particle" style="width: 85px; height: 85px; left: 70%;"></div>
            <div class="particle" style="width: 95px; height: 95px; left: 80%;"></div>
            <div class="particle" style="width: 75px; height: 75px; left: 90%;"></div>
        </div>
        
        <!-- Ultra Modern Gradient Orbs -->
        <div class="absolute top-10 right-10 w-72 h-72 bg-gradient-to-r from-[#d983e4]/30 via-[#4e71c5]/20 to-[#d983e4]/30 mix-blend-multiply filter blur-3xl rounded-full floating-animation liquid-blob hidden md:block"></div>
        <div class="absolute bottom-10 left-10 w-64 h-64 bg-gradient-to-r from-[#4e71c5]/30 via-[#d983e4]/20 to-[#4e71c5]/30 mix-blend-multiply filter blur-3xl rounded-full floating-animation liquid-blob hidden md:block" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-r from-[#d983e4]/15 via-[#4e71c5]/10 to-[#d983e4]/15 mix-blend-multiply filter blur-[100px] rounded-full pulse-animation liquid-blob hidden md:block"></div>
        
        <!-- Animated Grid Overlay -->
        <div class="absolute inset-0 cyber-grid opacity-20 hidden md:block"></div>
        
        <!-- Glowing Orb Effect -->
        <div class="absolute top-1/4 right-1/4 w-32 h-32 bg-gradient-to-r from-[#d983e4] to-[#4e71c5] rounded-full filter blur-2xl opacity-20 pulse-animation hidden md:block"></div>
        <div class="absolute bottom-1/4 left-1/4 w-28 h-28 bg-gradient-to-r from-[#4e71c5] to-[#d983e4] rounded-full filter blur-2xl opacity-20 pulse-animation hidden md:block" style="animation-delay: 1s;"></div>
        
        <div class="relative z-10 max-w-5xl mx-auto px-4 text-center py-20">
            <!-- Ultra Modern Badge -->
            <div class="inline-flex items-center px-5 py-2.5 rounded-full bg-white/95 backdrop-blur-lg text-xs font-semibold text-primary-600 mb-6 text-reveal shadow-xl border border-white/50 glow-effect" style="animation-delay: 0.2s;">
                <span class="w-2.5 h-2.5 bg-gradient-to-r from-[#d983e4] to-[#4e71c5] rounded-full mr-2.5 pulse-animation"></span>
                <span class="font-bold tracking-wide">✨ Smart Job Tracking Platform</span>
            </div>
            
            <!-- Ultra Compact Hero Title -->
            <h1 class="hero-title text-4xl md:text-6xl lg:text-7xl font-black text-gray-900 mb-6 leading-[1.1] text-reveal" style="animation-delay: 0.3s;">
                Track Lamaran Kerja dengan 
                <br>
                <span class="gradient-text-animated inline-block mt-2">
                    TraKerja
                </span>
            </h1>
            
            <!-- Compact Subtitle -->
            <p class="hero-subtitle text-lg md:text-xl text-gray-600 mb-10 max-w-2xl mx-auto text-reveal leading-relaxed" style="animation-delay: 0.4s;">
                Platform 
                <span class="font-bold text-[#d983e4]">tracking</span> & 
                <span class="font-bold text-[#4e71c5]">analytics</span> 
                untuk job seeker Indonesia
            </p>
            
            <!-- Compact CTA Buttons -->
            <div class="cta-buttons flex flex-col sm:flex-row gap-4 justify-center mb-10 text-reveal" style="animation-delay: 0.5s;">
                @auth
                    <a href="{{ url('/tracker') }}" 
                       class="cta-button magnetic-button glow-effect bg-gradient-to-r from-[#d983e4] via-[#c573dc] to-[#4e71c5] text-white px-8 py-3.5 rounded-xl font-bold text-base hover:shadow-2xl hover:shadow-purple-500/50 transition-all duration-300 hover:scale-105 relative overflow-hidden group">
                        <span class="relative z-10 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Buka Dashboard
                        </span>
                    </a>
                @else
                    <a href="{{ route('register') }}" 
                       class="cta-button magnetic-button glow-effect bg-gradient-to-r from-[#d983e4] via-[#c573dc] to-[#4e71c5] text-white px-8 py-3.5 rounded-xl font-bold text-base hover:shadow-2xl hover:shadow-purple-500/50 transition-all duration-300 hover:scale-105 relative overflow-hidden group">
                        <span class="relative z-10 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Mulai Gratis Sekarang
                        </span>
                    </a>
                    <a href="{{ route('login') }}" 
                       class="cta-button magnetic-button border-2 border-[#d983e4] bg-white/80 backdrop-blur-sm text-[#d983e4] px-8 py-3.5 rounded-xl font-bold text-base hover:bg-[#d983e4] hover:text-white hover:shadow-xl transition-all duration-300 hover:scale-105 relative overflow-hidden group">
                        <span class="relative z-10 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Login
                        </span>
                    </a>
                @endauth
            </div>
            
            <!-- Ultra Compact Trust Badges -->
            <div class="trust-indicators flex flex-wrap items-center justify-center gap-4 text-xs text-gray-600 text-reveal" style="animation-delay: 0.6s;">
                <div class="flex items-center bg-white/90 backdrop-blur-sm px-3.5 py-1.5 rounded-full hover:scale-105 transition-all duration-300 shadow-md border border-gray-100">
                    <svg class="w-4 h-4 text-[#4e71c5] mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="font-semibold">Setup 2 Menit</span>
                </div>
                <div class="flex items-center bg-white/90 backdrop-blur-sm px-3.5 py-1.5 rounded-full hover:scale-105 transition-all duration-300 shadow-md border border-gray-100">
                    <svg class="w-4 h-4 text-[#4e71c5] mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <span class="font-semibold">100% Gratis</span>
                </div>
                <div class="flex items-center bg-white/90 backdrop-blur-sm px-3.5 py-1.5 rounded-full hover:scale-105 transition-all duration-300 shadow-md border border-gray-100">
                    <svg class="w-4 h-4 text-[#4e71c5] mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <span class="font-semibold">Data Aman</span>
                </div>
            </div>
        </div>
        
        <!-- Compact Scroll Indicator -->
        <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 text-gray-400 animate-bounce">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>


    <!-- Features Section - Ultra Compact -->
    <section class="py-16 bg-gradient-to-br from-slate-50 via-white to-blue-50 relative overflow-hidden">
        <!-- Subtle Background Elements -->
        <div class="absolute top-0 left-0 w-full h-full cyber-grid opacity-10 hidden md:block"></div>
        <div class="absolute top-10 right-10 w-40 h-40 bg-gradient-to-r from-[#d983e4]/8 to-[#4e71c5]/8 rounded-full blur-3xl floating-animation hidden md:block"></div>
        <div class="absolute bottom-10 left-10 w-32 h-32 bg-gradient-to-r from-[#4e71c5]/8 to-[#d983e4]/8 rounded-full blur-3xl floating-animation hidden md:block" style="animation-delay: 2s;"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4">
            <!-- Compact Header -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/80 backdrop-blur-sm text-xs font-semibold text-primary-600 mb-4 shadow-md border border-white/50">
                    <span class="w-2 h-2 bg-gradient-to-r from-[#d983e4] to-[#4e71c5] rounded-full mr-2 pulse-animation"></span>
                    Powerful Features
                </div>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-gray-900 mb-4">
                    Fitur-Fitur <span class="gradient-text">TraKerja</span>
                </h2>
                <p class="text-base md:text-lg text-gray-600 max-w-2xl mx-auto">
                    Tools sederhana tapi powerful untuk tracking lamaran kerja Anda
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Feature 1 -->
                <div class="feature-card stagger-item card-3d hover-lift spotlight-effect group glass-enhanced rounded-xl p-6 shadow-xl border border-gray-100/50 hover:border-primary-600/30 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#d983e4]/10 via-[#4e71c5]/5 to-[#d983e4]/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 card-3d-inner">
                        <div class="w-14 h-14 bg-gradient-to-br from-[#d983e4] to-[#4e71c5] rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h2a2 2 0 002-2z"></path>
                        </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-3 group-hover:text-[#d983e4] transition-colors duration-300">Kanban Board</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">Kelola lamaran kerja dengan drag & drop yang mudah dan intuitif.</p>
                    </div>
                </div>
                
                <!-- Feature 2 -->
                <div class="feature-card stagger-item card-3d hover-lift spotlight-effect group glass-enhanced rounded-xl p-6 shadow-xl border border-gray-100/50 hover:border-[#4e71c5]/30 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#4e71c5]/10 via-[#d983e4]/5 to-[#4e71c5]/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 card-3d-inner">
                        <div class="w-14 h-14 bg-gradient-to-br from-[#4e71c5] to-[#d983e4] rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-3 group-hover:text-[#4e71c5] transition-colors duration-300">Analytics Dashboard</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">Lihat statistik dan insights tentang performa lamaran kerja Anda.</p>
                    </div>
                </div>
                
                <!-- Feature 3 -->
                <div class="feature-card stagger-item card-3d hover-lift spotlight-effect group glass-enhanced rounded-xl p-6 shadow-xl border border-gray-100/50 hover:border-[#d983e4]/30 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#d983e4]/10 via-[#4e71c5]/5 to-[#d983e4]/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 card-3d-inner">
                        <div class="w-14 h-14 bg-gradient-to-br from-[#d983e4] to-[#4e71c5] rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-3 group-hover:text-[#d983e4] transition-colors duration-300">Smart Reminders</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">Notifikasi otomatis untuk interview dan waktu follow-up yang tepat.</p>
                    </div>
                </div>
                
                <!-- Feature 4 -->
                <div class="feature-card stagger-item card-3d hover-lift spotlight-effect group glass-enhanced rounded-xl p-6 shadow-xl border border-gray-100/50 hover:border-[#4e71c5]/30 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#4e71c5]/10 via-[#d983e4]/5 to-[#4e71c5]/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 card-3d-inner">
                        <div class="w-14 h-14 bg-gradient-to-br from-[#4e71c5] to-[#d983e4] rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-3 group-hover:text-[#4e71c5] transition-colors duration-300">Goal Tracking</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">Set target mingguan dan pantau progress dengan streak counter.</p>
                    </div>
                </div>
                
                <!-- Feature 5 -->
                <div class="feature-card stagger-item card-3d hover-lift spotlight-effect group glass-enhanced rounded-xl p-6 shadow-xl border border-gray-100/50 hover:border-[#d983e4]/30 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#d983e4]/10 via-[#4e71c5]/5 to-[#d983e4]/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 card-3d-inner">
                        <div class="w-14 h-14 bg-gradient-to-br from-[#d983e4] to-[#4e71c5] rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-3 group-hover:text-[#d983e4] transition-colors duration-300">Real-time Sync</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">Akses data di mana saja dengan update otomatis real-time.</p>
                    </div>
                </div>
                
                <!-- Feature 6 -->
                <div class="feature-card stagger-item card-3d hover-lift spotlight-effect group glass-enhanced rounded-xl p-6 shadow-xl border border-gray-100/50 hover:border-[#4e71c5]/30 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#4e71c5]/10 via-[#d983e4]/5 to-[#4e71c5]/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 card-3d-inner">
                        <div class="w-14 h-14 bg-gradient-to-br from-[#4e71c5] to-[#d983e4] rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-3 group-hover:text-[#4e71c5] transition-colors duration-300">Data Aman</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">Enkripsi end-to-end dan backup otomatis untuk privasi Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Ultra Compact CTA Section -->
    <section class="py-16 hero-gradient relative overflow-hidden">
        <!-- Ultra Modern Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-[#d983e4]/30 via-[#4e71c5]/20 to-[#d983e4]/30"></div>
        <div class="absolute inset-0 cyber-grid opacity-15 hidden md:block"></div>
        <div class="absolute top-10 right-10 w-64 h-64 bg-white/10 rounded-full blur-[80px] floating-animation hidden md:block"></div>
        <div class="absolute bottom-10 left-10 w-48 h-48 bg-white/10 rounded-full blur-[80px] floating-animation hidden md:block" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-white/5 rounded-full blur-[100px] pulse-animation hidden md:block"></div>
        
        <div class="relative z-10 max-w-4xl mx-auto text-center px-4">
            <!-- Compact Animated Badge -->
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/95 backdrop-blur-lg text-xs font-bold text-[#d983e4] mb-6 shadow-xl border border-white/30 glow-effect">
                <span class="w-2 h-2 bg-gradient-to-r from-[#d983e4] to-[#4e71c5] rounded-full mr-2 pulse-animation"></span>
                <span class="font-bold tracking-wide">🚀 Ready to Transform Your Job Search</span>
            </div>
            
            <!-- Ultra Compact Heading -->
            <h2 class="text-3xl md:text-5xl lg:text-6xl font-black text-white mb-6 leading-[1.1]">
                Siap Mengorganisir 
                <br>
                <span class="bg-gradient-to-r from-white to-white/80 bg-clip-text text-transparent">
                    Pencarian Kerja Anda?
                </span>
            </h2>
            
            <!-- Compact Description -->
            <p class="text-base md:text-lg text-white/90 mb-10 max-w-2xl mx-auto leading-relaxed">
                Bergabunglah dengan job seeker Indonesia. 
                <span class="font-semibold">Mulai gratis</span> dan rasakan perbedaannya!
            </p>
            
            <!-- Ultra Compact CTA Buttons -->
            @guest
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                    <a href="{{ route('register') }}" 
                       class="cta-button magnetic-button glow-effect bg-white text-[#d983e4] px-8 py-3.5 rounded-xl font-bold text-base hover:shadow-2xl hover:shadow-purple-500/50 transition-all duration-300 hover:scale-105 relative overflow-hidden group">
                        <span class="relative z-10 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Daftar Gratis Sekarang
                        </span>
                    </a>
                    <a href="{{ route('login') }}" 
                       class="cta-button magnetic-button border-2 border-white/90 bg-white/10 backdrop-blur-sm text-white px-8 py-3.5 rounded-xl font-bold text-base hover:bg-white hover:text-[#d983e4] transition-all duration-300 hover:shadow-xl hover:scale-105 relative overflow-hidden group">
                        <span class="relative z-10 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Login
                        </span>
                    </a>
                </div>
            @else
                <a href="{{ url('/tracker') }}" 
                   class="cta-button magnetic-button glow-effect bg-white text-[#d983e4] px-8 py-3.5 rounded-xl font-bold text-base hover:shadow-2xl hover:shadow-purple-500/50 transition-all duration-300 inline-block hover:scale-105 relative overflow-hidden group">
                    <span class="relative z-10 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Buka Dashboard
                    </span>
                </a>
            @endguest
            
            <!-- Ultra Compact Trust Indicators -->
            <div class="flex flex-wrap items-center justify-center gap-4 text-white/90 text-xs mt-8">
                <div class="flex items-center bg-white/20 backdrop-blur-sm px-3 py-1.5 rounded-full hover:scale-105 transition-all duration-300 shadow-md border border-white/20">
                    <svg class="w-4 h-4 text-white mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="font-semibold">Setup 2 Menit</span>
                </div>
                <div class="flex items-center bg-white/20 backdrop-blur-sm px-3 py-1.5 rounded-full hover:scale-105 transition-all duration-300 shadow-md border border-white/20">
                    <svg class="w-4 h-4 text-white mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="font-semibold">100% Gratis</span>
                </div>
                <div class="flex items-center bg-white/20 backdrop-blur-sm px-3 py-1.5 rounded-full hover:scale-105 transition-all duration-300 shadow-md border border-white/20">
                    <svg class="w-4 h-4 text-white mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <span class="font-semibold">Data Aman</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer - Ultra Compact -->
    <footer class="bg-white py-10 relative overflow-hidden">
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