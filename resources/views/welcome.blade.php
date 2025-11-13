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
                               class="px-3 sm:px-4 py-2 text-gray-700 hover:text-[#4e71c5] text-xs sm:text-sm font-medium transition-colors duration-200 rounded-lg hover:bg-gray-50">
                                Login
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" 
                                   class="px-3 sm:px-4 py-2 bg-[#4e71c5] text-white rounded-lg text-xs sm:text-sm font-semibold hover:bg-[#3d5ba3] hover:shadow-md transition-all duration-200">
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
    <section class="relative py-20 sm:py-24 md:py-32 flex items-center overflow-hidden pt-16">
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
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/80 backdrop-blur-sm border border-gray-200/50 text-xs font-medium text-gray-700 mb-6 shadow-sm hover:shadow-md transition-all duration-300">
                        <span class="w-1.5 h-1.5 bg-gradient-to-r from-[#d983e4] to-[#4e71c5] rounded-full pulse-animation"></span>
                        <span>#1 Job Tracking Platform Indonesia</span>
                    </div>
                    
                    <!-- Main Heading -->
                    <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold text-gray-900 mb-4 leading-tight">
                        Kelola <span class="gradient-text-animated">Rekrutmen</span> Lebih Cerdas
                    </h1>
                    
                    <!-- Subheading -->
                    <p class="text-base sm:text-lg md:text-xl text-gray-600 mb-8 max-w-xl mx-auto lg:mx-0">
                        Platform pelacakan lamaran kerja yang 
                        <span class="font-semibold text-[#d983e4]">sederhana</span>, 
                        <span class="font-semibold text-[#4e71c5]">efektif</span>, dan 
                        <span class="font-semibold text-gray-900">handal</span>
                        untuk pencari kerja Indonesia
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
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 text-xs">
                        <div class="flex items-center gap-1.5 text-gray-600">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="font-medium">Setup 2 Menit</span>
                        </div>
                        <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                        <div class="flex items-center gap-1.5 text-gray-600">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <span class="font-medium">Data Aman</span>
                        </div>
                        <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                        <div class="flex items-center gap-1.5 text-gray-600">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="font-medium">Mulai Gratis</span>
                        </div>
                    </div>
                </div>
                
                <!-- Right Side - Photo Display -->
                <img src="{{ asset('images/hero-photo.jpg') }}" 
                     alt="TraKerja Hero" 
                     class="w-full h-auto"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <!-- Fallback jika foto tidak ada -->
                <div class="w-full aspect-video bg-gradient-to-br from-[#d983e4]/20 to-[#4e71c5]/20 flex items-center justify-center" style="display: none;">
                    <p class="text-gray-500 text-sm">Tambahkan foto di: public/images/hero-photo.jpg</p>
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


    <!-- Features Section - Compact Grid -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-3">
                    Fitur-Fitur <span class="text-[#d983e4]">TraKerja</span>
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Alat sederhana tapi <span class="font-semibold text-[#d983e4]">handal</span> untuk mengelola pencarian kerja Anda
                </p>
            </div>
            
            <!-- Features Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Feature 1 -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 hover:border-[#d983e4]/50 transition-colors">
                    <div class="mb-4">
                        <svg class="w-6 h-6 text-[#4e71c5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h2a2 2 0 002-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Kanban Board</h3>
                    <p class="text-sm text-gray-600">Kelola lamaran kerja dengan drag & drop yang mudah. Lihat progress dalam satu tampilan intuitif.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 hover:border-[#4e71c5]/50 transition-colors">
                    <div class="mb-4">
                        <svg class="w-6 h-6 text-[#4e71c5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Analytics Dashboard</h3>
                    <p class="text-sm text-gray-600">Lihat statistik lamaran Anda. Platform mana yang paling efektif dan posisi apa yang paling sering dipanggil interview.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 hover:border-[#d983e4]/50 transition-colors">
                    <div class="mb-4">
                        <svg class="w-6 h-6 text-[#4e71c5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Smart Reminders</h3>
                    <p class="text-sm text-gray-600">Dapatkan notifikasi untuk jadwal interview, deadline tugas, dan waktu yang tepat untuk follow-up.</p>
                </div>
                
                <!-- Feature 4 -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 hover:border-[#4e71c5]/50 transition-colors">
                    <div class="mb-4">
                        <svg class="w-6 h-6 text-[#4e71c5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Goal Tracking</h3>
                    <p class="text-sm text-gray-600">Set target mingguan untuk lamaran kerja dan pantau progress Anda. Tetap termotivasi dengan streak counter.</p>
                </div>
                
                <!-- Feature 5 -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 hover:border-[#d983e4]/50 transition-colors">
                    <div class="mb-4">
                        <svg class="w-6 h-6 text-[#4e71c5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Real-time Sync</h3>
                    <p class="text-sm text-gray-600">Akses data Anda di mana saja. Update otomatis di semua device dan browser Anda dengan teknologi cloud.</p>
                </div>
                
                <!-- Feature 6 -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 hover:border-[#4e71c5]/50 transition-colors">
                    <div class="mb-4">
                        <svg class="w-6 h-6 text-[#4e71c5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Data Aman</h3>
                    <p class="text-sm text-gray-600">Data Anda aman dengan enkripsi end-to-end dan backup otomatis. Privasi Anda adalah prioritas kami.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Problem Solution Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-gradient-to-br from-gray-50 via-white to-slate-50 relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute top-0 left-0 w-full h-full cyber-grid opacity-10 hidden md:block"></div>
        <div class="absolute top-10 right-10 w-20 h-20 bg-gradient-to-r from-primary-600/20 to-secondary-500/20 rounded-full blur-xl floating-animation hidden md:block"></div>
        <div class="absolute bottom-10 left-10 w-16 h-16 bg-gradient-to-r from-secondary-500/20 to-primary-600/20 rounded-full blur-xl floating-animation hidden md:block" style="animation-delay: 2s;"></div>
        
        <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12 lg:gap-16 items-center">
                <!-- Problem Side -->
                <div class="space-y-6 md:space-y-8">
                    <div>
                        <div class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 rounded-full bg-red-100 text-red-700 text-xs sm:text-sm font-medium mb-4">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            Masalah Umum
                        </div>
                        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-4 md:mb-6">
                            Bingung Kelola <span class="text-red-600">Lamaran Kerja?</span>
                        </h2>
                        <p class="text-sm sm:text-base md:text-lg text-gray-600 mb-6 md:mb-8">
                            Pencari kerja seringkali kesulitan mengorganisir lamaran kerja yang sudah dikirim. 
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
                <div class="space-y-6 md:space-y-8">
                    <div>
                        <div class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 rounded-full bg-green-100 text-green-700 text-xs sm:text-sm font-medium mb-4">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Solusi TraKerja
                        </div>
                        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-4 md:mb-6">
                            <span class="gradient-text">TraKerja</span> Solusinya!
                        </h2>
                        <p class="text-sm sm:text-base md:text-lg text-gray-600 mb-6 md:mb-8">
                            Platform pelacakan lamaran kerja yang dirancang khusus untuk pencari kerja Indonesia. 
                            Sederhana, efektif, dan mudah digunakan.
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
    <section class="py-12 sm:py-16 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 md:mb-10">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-2 md:mb-3">
                    Mengapa Pilih <span class="text-[#d983e4]">TraKerja?</span>
                </h2>
                <p class="text-sm sm:text-base text-gray-600 max-w-2xl mx-auto px-4">
                    Platform yang dirancang khusus untuk pencari kerja Indonesia dengan fitur-fitur yang benar-benar dibutuhkan
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Benefit 1 -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 hover:border-[#d983e4]/50 transition-colors">
                    <div class="mb-4">
                        <svg class="w-6 h-6 text-[#4e71c5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Mulai Gratis, Upgrade Kapan Saja</h3>
                    <p class="text-sm text-gray-600">Versi gratis sudah lengkap untuk kebutuhan tracking dasar. Upgrade ke Premium untuk fitur advanced seperti unlimited lamaran, advanced analytics, dan priority support.</p>
                </div>
                
                <!-- Benefit 2 -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 hover:border-[#4e71c5]/50 transition-colors">
                    <div class="mb-4">
                        <svg class="w-6 h-6 text-[#4e71c5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Setup 2 Menit</h3>
                    <p class="text-sm text-gray-600">Tidak perlu setup rumit. Daftar, login, langsung bisa mulai tracking lamaran kerja Anda.</p>
                </div>
                
                <!-- Benefit 3 -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 hover:border-[#d983e4]/50 transition-colors">
                    <div class="mb-4">
                        <svg class="w-6 h-6 text-[#4e71c5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Data Aman</h3>
                    <p class="text-sm text-gray-600">Data Anda dienkripsi dan disimpan dengan aman. Privasi dan keamanan adalah prioritas utama kami.</p>
                </div>
                
                <!-- Benefit 4 -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 hover:border-[#4e71c5]/50 transition-colors">
                    <div class="mb-4">
                        <svg class="w-6 h-6 text-[#4e71c5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Analytics Cerdas</h3>
                    <p class="text-sm text-gray-600">Dapatkan insight tentang performa lamaran Anda. Platform mana yang paling efektif dan strategi apa yang berhasil.</p>
                </div>
                
                <!-- Benefit 5 -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 hover:border-[#d983e4]/50 transition-colors">
                    <div class="mb-4">
                        <svg class="w-6 h-6 text-[#4e71c5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Goal Tracking</h3>
                    <p class="text-sm text-gray-600">Set target mingguan dan pantau progress Anda. Tetap termotivasi dengan streak counter dan achievement badges.</p>
                </div>
                
                <!-- Benefit 6 -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 hover:border-[#4e71c5]/50 transition-colors">
                    <div class="mb-4">
                        <svg class="w-6 h-6 text-[#4e71c5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Real-time Sync</h3>
                    <p class="text-sm text-gray-600">Akses data Anda di mana saja, kapan saja. Update otomatis di semua device dengan teknologi cloud terdepan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-12 sm:py-16 md:py-20 lg:py-24 bg-gradient-to-b from-slate-50 to-white relative overflow-hidden">
        <!-- Background -->
        <div class="absolute inset-0 cyber-grid opacity-[0.02] hidden md:block"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-8 md:mb-10">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-2 md:mb-3">
                    Kata <span class="text-[#d983e4]">Pengguna</span> TraKerja
                </h2>
                <p class="text-sm sm:text-base text-gray-600 max-w-2xl mx-auto px-4">
                    Ulasan dari pencari kerja yang sudah menggunakan TraKerja
                </p>
            </div>
            
            <!-- Testimonials - Asymmetric Layout -->
            <div class="space-y-6">
                <!-- Testimonial 1 - Sarah (Left aligned with photo) -->
                <div class="group relative bg-white rounded-2xl p-6 border border-gray-200 hover:border-[#d983e4]/50 transition-all duration-300 hover:shadow-lg">
                    <div class="flex flex-col md:flex-row gap-5 items-start">
                        <!-- Photo -->
                        <div class="flex-shrink-0">
                            <div class="relative">
                                <img src="{{ asset('images/sarah.png') }}" 
                                     alt="Sarah" 
                                     class="w-20 h-20 rounded-2xl object-cover border-2 border-[#d983e4]/20 group-hover:border-[#d983e4] transition-colors shadow-md">
                                <div class="absolute -top-1 -right-1 w-6 h-6 bg-gradient-to-br from-[#d983e4] to-[#4e71c5] rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="font-bold text-gray-900 text-lg">Sarah</h3>
                                <span class="text-xs px-2 py-1 bg-[#d983e4]/10 text-[#d983e4] rounded-full font-medium">Fresh Graduate</span>
                            </div>
                            <p class="text-sm text-gray-600 leading-relaxed">"TraKerja membantu saya mengorganisir lamaran kerja dengan baik. Sekarang saya tahu mana yang sudah di-apply dan mana yang perlu di-follow up. Sangat membantu untuk fresh graduate seperti saya!"</p>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial 2 - Ahmad (Right aligned with photo) -->
                <div class="group relative bg-white rounded-2xl p-6 border border-gray-200 hover:border-[#4e71c5]/50 transition-all duration-300 hover:shadow-lg md:ml-4 lg:ml-8">
                    <div class="flex flex-col md:flex-row gap-5 items-start">
                        <!-- Content -->
                        <div class="flex-1 min-w-0 order-2 md:order-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="font-bold text-gray-900 text-lg">Ahmad</h3>
                                <span class="text-xs px-2 py-1 bg-[#4e71c5]/10 text-[#4e71c5] rounded-full font-medium">Career Switcher</span>
                            </div>
                            <p class="text-sm text-gray-600 leading-relaxed">"Platform yang sederhana tapi efektif. Analytics-nya membantu saya tahu platform mana yang paling sering memanggil interview. Sempurna untuk yang ingin pindah karier!"</p>
                        </div>
                        <!-- Photo -->
                        <div class="flex-shrink-0 order-1 md:order-2">
                            <div class="relative">
                                <img src="{{ asset('images/ahmad.png') }}" 
                                     alt="Ahmad" 
                                     class="w-20 h-20 rounded-2xl object-cover border-2 border-[#4e71c5]/20 group-hover:border-[#4e71c5] transition-colors shadow-md">
                                <div class="absolute -top-1 -right-1 w-6 h-6 bg-gradient-to-br from-[#4e71c5] to-[#d983e4] rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial 3 - Maya (Left aligned with photo) -->
                <div class="group relative bg-white rounded-2xl p-6 border border-gray-200 hover:border-[#d983e4]/50 transition-all duration-300 hover:shadow-lg">
                    <div class="flex flex-col md:flex-row gap-5 items-start">
                        <!-- Photo -->
                        <div class="flex-shrink-0">
                            <div class="relative">
                                <img src="{{ asset('images/maya.png') }}" 
                                     alt="Fakhri" 
                                     class="w-20 h-20 rounded-2xl object-cover border-2 border-[#d983e4]/20 group-hover:border-[#d983e4] transition-colors shadow-md">
                                <div class="absolute -top-1 -right-1 w-6 h-6 bg-gradient-to-br from-[#d983e4] to-[#4e71c5] rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="font-bold text-gray-900 text-lg">Fakhri</h3>
                                <span class="text-xs px-2 py-1 bg-[#d983e4]/10 text-[#d983e4] rounded-full font-medium">Pencari Kerja</span>
                            </div>
                            <p class="text-sm text-gray-600 leading-relaxed">"Goal tracking-nya bagus banget! Membantu saya tetap konsisten apply kerja setiap minggu. Akhirnya dapat kerja juga! Terima kasih TraKerja!"</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Photo Gallery Section -->
    <section class="py-8 sm:py-10 md:py-12 bg-gradient-to-br from-white via-gray-50 to-slate-50 relative overflow-hidden" id="gallery">
        <!-- Background Elements -->
        <div class="absolute top-0 left-0 w-full h-full cyber-grid opacity-5 hidden md:block"></div>
        <div class="absolute top-20 right-20 w-40 h-40 bg-gradient-to-r from-primary-600/10 to-secondary-500/10 rounded-full blur-3xl floating-animation hidden md:block"></div>
        <div class="absolute bottom-20 left-20 w-32 h-32 bg-gradient-to-r from-secondary-500/10 to-primary-600/10 rounded-full blur-3xl floating-animation hidden md:block" style="animation-delay: 3s;"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-6 md:mb-8">
                <div class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 rounded-full glass-effect text-xs sm:text-sm font-medium text-primary-600 mb-3 md:mb-4">
                    <span class="w-2 h-2 bg-secondary-500 rounded-full mr-2 pulse-animation"></span>
                    Gallery
                </div>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-2 md:mb-3">
                    TraKerja <span class="gradient-text">In Action</span>
                </h2>
                <p class="text-xs sm:text-sm md:text-base text-gray-600 max-w-2xl mx-auto px-4">
                    Lihat bagaimana TraKerja membantu pencari kerja Indonesia mengorganisir pencarian kerja mereka
                </p>
            </div>
            
            <!-- Photo Display -->
            <div class="flex justify-center">
                <img src="{{ asset('images/image.png') }}" 
                     alt="TraKerja In Action" 
                     class="w-full max-w-2xl md:max-w-3xl h-auto rounded-xl shadow-lg"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <!-- Fallback jika foto tidak ada -->
                <div class="w-full max-w-2xl md:max-w-3xl aspect-video bg-gradient-to-br from-[#d983e4]/20 to-[#4e71c5]/20 rounded-xl flex items-center justify-center" style="display: none;">
                    <p class="text-gray-500 text-sm">Tambahkan foto di: public/images/image.png</p>
                </div>
            </div>

            <!-- Admin Upload Section (Only visible to admins) -->
            @auth
                @if(Auth::user()->isAdmin())
                <div class="mt-12 p-6 bg-white/80 backdrop-blur-sm rounded-2xl border border-gray-200/50 shadow-lg">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Upload Photo ke Gallery</h3>
                    <form id="photo-upload-form" class="space-y-4" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Photo</label>
                                <input type="file" name="photo" id="photo-input" accept="image/*" required
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-600 file:text-white hover:file:bg-primary-700 transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Title (Optional)</label>
                                <input type="text" name="title" id="photo-title" placeholder="Photo title"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-transparent">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                            <textarea name="description" id="photo-description" rows="2" placeholder="Photo description"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-transparent"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                            <input type="number" name="order" id="photo-order" value="0" min="0"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-transparent">
                        </div>
                        <button type="submit" 
                                class="w-full md:w-auto bg-gradient-to-r from-[#d983e4] to-[#4e71c5] text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all duration-300 hover:scale-105">
                            <span id="upload-btn-text">Upload Photo</span>
                            <span id="upload-btn-loading" class="hidden">Uploading...</span>
                        </button>
                    </form>
                </div>
                @endif
            @endauth
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="pt-12 sm:pt-16 pb-12 sm:pb-16 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-6 md:mb-8">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                    Cara Kerja <span class="text-[#d983e4]">TraKerja</span>
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 px-4">
                    Hanya butuh 3 langkah sederhana untuk mengorganisir pencarian kerja Anda
                </p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8 items-start">
                <!-- Left: Accordion Steps -->
                <div class="space-y-3">
                    <!-- Step 1 -->
                    <div class="how-it-works-item bg-white border border-gray-200 rounded-lg overflow-hidden hover:border-[#d983e4]/50 transition-all duration-300" style="margin-top: 0;">
                        <button class="how-it-works-btn w-full px-5 py-4 text-left flex items-center justify-between group" data-step="1" onclick="toggleHowItWorks(this)">
                            <div class="flex items-center gap-4">
                                <span class="text-[#d983e4] font-bold text-3xl flex-shrink-0">01</span>
                                <h3 class="font-semibold text-gray-900 text-base">Daftar & Setup</h3>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 how-it-works-icon transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="how-it-works-content hidden px-5 pb-4 pl-16">
                            <p class="text-sm text-gray-600 leading-relaxed">Buat akun gratis dalam 2 menit. Tidak perlu verifikasi email yang ribet, langsung bisa pakai!</p>
                        </div>
                    </div>
                    
                    <!-- Step 2 -->
                    <div class="how-it-works-item bg-white border border-gray-200 rounded-lg overflow-hidden hover:border-[#4e71c5]/50 transition-all duration-300">
                        <button class="how-it-works-btn w-full px-5 py-4 text-left flex items-center justify-between group" data-step="2" onclick="toggleHowItWorks(this)">
                            <div class="flex items-center gap-4">
                                <span class="text-[#4e71c5] font-bold text-3xl flex-shrink-0">02</span>
                                <h3 class="font-semibold text-gray-900 text-base">Tambah Lamaran</h3>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 how-it-works-icon transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="how-it-works-content hidden px-5 pb-4 pl-16">
                            <p class="text-sm text-gray-600 leading-relaxed">Input detail lamaran kerja Anda. TraKerja akan otomatis mengorganisir dan mengingatkan follow-up.</p>
                        </div>
                    </div>
                    
                    <!-- Step 3 -->
                    <div class="how-it-works-item bg-white border border-gray-200 rounded-lg overflow-hidden hover:border-[#d983e4]/50 transition-all duration-300">
                        <button class="how-it-works-btn w-full px-5 py-4 text-left flex items-center justify-between group" data-step="3" onclick="toggleHowItWorks(this)">
                            <div class="flex items-center gap-4">
                                <span class="text-[#d983e4] font-bold text-3xl flex-shrink-0">03</span>
                                <h3 class="font-semibold text-gray-900 text-base">Track & Analyze</h3>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 how-it-works-icon transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="how-it-works-content hidden px-5 pb-4 pl-16">
                            <p class="text-sm text-gray-600 leading-relaxed">Pantau progress dan analisis performa lamaran Anda. Dapatkan insight untuk strategi yang lebih efektif.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Right: Visual Elements with Images -->
                <div class="relative hidden lg:block" style="margin-top: 0; padding-top: 0;">
                    <div class="relative" style="margin-top: 0;">
                        <!-- Step 1 Image: Daftar & Setup (Default) -->
                        <img id="how-it-works-img-1" 
                             src="{{ asset('images/how-it-works-1.png') }}" 
                             alt="Daftar & Setup" 
                             class="how-it-works-image w-full h-auto object-contain transition-opacity duration-500 rounded-xl"
                             style="opacity: 1; margin-top: 0; padding-top: 0;"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="w-full h-full flex items-center justify-center rounded-xl" style="display: none;">
                            <p class="text-gray-400 text-sm text-center px-4">Tambahkan gambar di: public/images/how-it-works-1.png</p>
                        </div>
                        
                        <!-- Step 2 Image: Tambah Lamaran -->
                        <img id="how-it-works-img-2" 
                             src="{{ asset('images/how-it-works-2.png') }}" 
                             alt="Tambah Lamaran" 
                             class="how-it-works-image absolute top-0 left-0 w-full h-auto object-contain transition-opacity duration-500 hidden rounded-xl"
                             style="margin-top: 0; padding-top: 0;"
                             onerror="this.style.display='none';">
                        
                        <!-- Step 3 Image: Track & Analyze -->
                        <img id="how-it-works-img-3" 
                             src="{{ asset('images/how-it-works-3.png') }}" 
                             alt="Track & Analyze" 
                             class="how-it-works-image absolute top-0 left-0 w-full h-auto object-contain transition-opacity duration-500 hidden rounded-xl"
                             style="margin-top: 0; padding-top: 0;"
                             onerror="this.style.display='none';">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-8 sm:py-10 bg-gradient-to-r from-[#4e71c5] to-[#d983e4] relative overflow-hidden mt-6 sm:mt-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white/10 backdrop-blur-sm rounded-xl md:rounded-2xl border border-white/20 p-5 sm:p-6 md:p-8">
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
                                   class="bg-white/10 backdrop-blur-sm border border-white/30 text-white px-5 py-2.5 rounded-lg font-semibold text-xs hover:bg-white/20 transition-all duration-200 inline-flex items-center justify-center whitespace-nowrap">
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

    <!-- FAQ Section -->
    <section class="py-12 sm:py-16 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-6 md:mb-8">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                    Pertanyaan <span class="text-[#d983e4]">Umum</span>
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 px-4">
                    Jawaban untuk pertanyaan yang sering ditanyakan tentang TraKerja
                </p>
            </div>
            
            <div class="space-y-3">
                <!-- FAQ 1 -->
                <div class="bg-white border-l-4 border-[#4e71c5] rounded-lg shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                    <button class="faq-question w-full px-5 py-3.5 text-left flex items-center justify-between group" onclick="toggleFaq(this)">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-[#4e71c5] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-semibold text-gray-900 text-sm">Apakah TraKerja gratis?</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 faq-icon transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-answer hidden px-5 pb-3.5 pl-13">
                        <p class="text-xs text-gray-600 leading-relaxed">Ya! TraKerja menawarkan paket <strong>Gratis</strong> yang sudah lengkap dengan fitur-fitur penting seperti Kanban Board, Smart Reminders, dan Goal Tracking untuk mengelola hingga 20 lamaran kerja. Untuk kebutuhan yang lebih advanced, Anda bisa upgrade ke <strong>Premium</strong> untuk mendapatkan unlimited lamaran, advanced analytics dengan insights mendalam, export data, dan priority support. Mulai gratis dulu, upgrade kapan saja sesuai kebutuhan Anda!</p>
                    </div>
                </div>
                
                <!-- FAQ 2 -->
                <div class="bg-white border-l-4 border-[#d983e4] rounded-lg shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                    <button class="faq-question w-full px-5 py-3.5 text-left flex items-center justify-between group" onclick="toggleFaq(this)">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-[#d983e4] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span class="font-semibold text-gray-900 text-sm">Bagaimana cara menggunakan TraKerja?</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 faq-icon transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-answer hidden px-5 pb-3.5 pl-13">
                        <p class="text-xs text-gray-600 leading-relaxed">Sangat mudah! Cukup daftar akun gratis (tidak perlu verifikasi email), lalu tambahkan lamaran kerja Anda. TraKerja akan otomatis mengorganisir semua lamaran dalam Kanban Board dan mengingatkan Anda untuk follow-up. Setup hanya butuh 2 menit!</p>
                    </div>
                </div>
                
                <!-- FAQ 3 -->
                <div class="bg-white border-l-4 border-[#4e71c5] rounded-lg shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                    <button class="faq-question w-full px-5 py-3.5 text-left flex items-center justify-between group" onclick="toggleFaq(this)">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-[#4e71c5] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <span class="font-semibold text-gray-900 text-sm">Data saya aman di TraKerja?</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 faq-icon transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-answer hidden px-5 pb-3.5 pl-13">
                        <p class="text-xs text-gray-600 leading-relaxed">Keamanan data adalah prioritas utama kami. Semua data Anda dienkripsi dengan teknologi end-to-end dan disimpan dengan aman. Kami juga melakukan backup otomatis untuk memastikan data Anda tidak hilang. Privasi Anda terjaga dan kami tidak akan membagikan data Anda ke pihak ketiga.</p>
                    </div>
                </div>
                
                <!-- FAQ 4 -->
                <div class="bg-white border-l-4 border-[#d983e4] rounded-lg shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                    <button class="faq-question w-full px-5 py-3.5 text-left flex items-center justify-between group" onclick="toggleFaq(this)">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-[#d983e4] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-semibold text-gray-900 text-sm">Bisakah saya akses TraKerja dari berbagai device?</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 faq-icon transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-answer hidden px-5 pb-3.5 pl-13">
                        <p class="text-xs text-gray-600 leading-relaxed">Tentu saja! TraKerja menggunakan teknologi cloud yang memungkinkan Anda mengakses data dari mana saja dan kapan saja. Semua perubahan akan tersinkronisasi secara real-time di semua device dan browser Anda, baik dari laptop, tablet, maupun smartphone.</p>
                    </div>
                </div>
                
                <!-- FAQ 5 -->
                <div class="bg-white border-l-4 border-[#4e71c5] rounded-lg shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                    <button class="faq-question w-full px-5 py-3.5 text-left flex items-center justify-between group" onclick="toggleFaq(this)">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-[#4e71c5] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="font-semibold text-gray-900 text-sm">Apa keuntungan menggunakan TraKerja dibanding spreadsheet?</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 faq-icon transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-answer hidden px-5 pb-3.5 pl-13">
                        <p class="text-xs text-gray-600 leading-relaxed">TraKerja dirancang khusus untuk tracking lamaran kerja dengan fitur-fitur yang tidak ada di spreadsheet biasa: Kanban Board visual untuk melihat progress, Analytics Dashboard untuk analisis performa, Smart Reminders otomatis untuk follow-up, Goal Tracking dengan streak counter, dan sinkronisasi real-time di semua device. Semua ini membuat proses tracking lebih efisien dan terorganisir.</p>
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
                
                if (data.success && data.photos && data.photos.length > 0) {
                    loadingElement.classList.add('hidden');
                    emptyElement.classList.add('hidden');
                    
                    // Clear existing photos (except loading/empty)
                    const existingPhotos = galleryContainer.querySelectorAll('.photo-item');
                    existingPhotos.forEach(photo => photo.remove());
                    
                    // Add photos to gallery
                    data.photos.forEach((photo, index) => {
                        const photoCard = createPhotoCard(photo, index);
                        galleryContainer.appendChild(photoCard);
                    });
                } else {
                    loadingElement.classList.add('hidden');
                    emptyElement.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error loading photos:', error);
                document.getElementById('gallery-loading').classList.add('hidden');
                document.getElementById('gallery-empty').classList.remove('hidden');
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
    </script>
    </body>
</html>
