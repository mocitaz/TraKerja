<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>CV Preview - {{ ucfirst($template) }} Template</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f9fafb;
            min-height: 100vh;
        }
        /* Fixed action buttons - always visible */
        .action-buttons {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 60;
            display: flex;
            gap: 12px;
        }
        /* Template badge */
        .template-badge {
            position: fixed;
            top: 16px;
            left: 16px;
            z-index: 60;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }
        .template-badge-icon {
            width: 18px;
            height: 18px;
            color: #7c3aed;
        }
        .template-name {
            color: #7c3aed;
            text-transform: capitalize;
        }
        .action-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: #7c3aed;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: all 0.2s;
        }
        .action-btn-primary:hover {
            background: #6d28d9;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .action-btn-secondary {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: white;
            color: #6b7280;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: all 0.2s;
        }
        .action-btn-secondary:hover {
            background: #f9fafb;
            border-color: #d1d5db;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        /* Remove pulse animation for cleaner look */
        
        /* Responsive design for mobile */
        @media (max-width: 640px) {
            .template-badge {
                top: 12px;
                left: 12px;
                padding: 6px 10px;
                font-size: 11px;
            }
            .template-badge-icon {
                width: 16px;
                height: 16px;
            }
            .action-buttons {
                bottom: 16px;
                right: 16px;
                left: 16px;
                justify-content: space-between;
                gap: 8px;
            }
            .action-btn-primary {
                flex: 1;
                justify-content: center;
                padding: 10px 16px;
                font-size: 13px;
            }
            .action-btn-primary span {
                display: inline;
            }
            .action-btn-secondary {
                width: 40px;
                height: 40px;
                flex-shrink: 0;
            }
            .preview-container {
                margin: 0.75rem;
                border-radius: 8px;
            }
        }
        
        @media (max-width: 480px) {
            .action-btn-primary span {
                display: none;
            }
            .action-btn-primary {
                width: 40px;
                height: 40px;
                padding: 0;
                justify-content: center;
                flex: 0;
            }
            .action-buttons {
                gap: 8px;
                justify-content: flex-end;
            }
        }

        .preview-container {
            max-width: 210mm;
            margin: 1rem auto;
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            animation: slideUp 0.25s ease-out;
        }
        .btn-primary {
            background: #7c3aed;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }
        .btn-primary:hover {
            background: #6d28d9;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(124, 58, 237, 0.3);
        }
        .btn-secondary {
            background: white;
            color: #374151;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s;
            border: 1px solid #d1d5db;
            cursor: pointer;
        }
        .btn-secondary:hover {
            background: #f9fafb;
            border-color: #9ca3af;
        }
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
        .pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white;
            }
            .preview-container {
                margin: 0;
                box-shadow: none;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Template Badge -->
    <div class="template-badge no-print">
        <svg class="template-badge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
        </svg>
        <span>Template: <strong class="template-name">{{ ucfirst($template ?? 'unknown') }}</strong></span>
    </div>
    
    <!-- Debug info (temporary) -->
    <div class="no-print" style="position: fixed; top: 10px; right: 10px; background: black; color: white; padding: 5px 10px; font-size: 11px; z-index: 9999; border-radius: 4px;">
        Raw: {{ $template ?? 'NULL' }}
    </div>

    <!-- Fixed action buttons - always visible -->
    <div class="action-buttons no-print">
        <form method="POST" action="{{ route('cv-builder.export') }}">
            @csrf
            <input type="hidden" name="template" value="{{ $template }}">
            <button type="submit" class="action-btn-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                <span>Download PDF</span>
            </button>
        </form>
        <button onclick="window.close()" class="action-btn-secondary" title="Close">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- CV Preview -->
    <div class="preview-container">
        @include("cv-templates.{$template}", [
            'user' => $user,
            'experiences' => $experiences,
            'educations' => $educations,
            'skills' => $skills,
            'organizations' => $organizations,
            'achievements' => $achievements,
            'projects' => $projects,
        ])
    </div>
</body>
</html>
    <!-- Compact Footer Instructions -->
    <div class="no-print container mx-auto px-4 pb-6 max-w-4xl">
        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 text-sm">CV Preview Ready</h3>
                        <p class="text-xs text-gray-600">Click download to export as PDF or close to try another template</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Professional Quality</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-adjust zoom for better preview on different screen sizes
        function adjustZoom() {
            const preview = document.querySelector('.preview-container');
            const windowWidth = window.innerWidth;
            
            if (windowWidth < 1024) {
                preview.style.transform = 'scale(0.85)';
                preview.style.transformOrigin = 'top center';
                preview.style.marginBottom = '3rem';
            } else {
                preview.style.transform = 'scale(1)';
                preview.style.marginBottom = '2rem';
            }
        }
        
        window.addEventListener('load', adjustZoom);
        window.addEventListener('resize', adjustZoom);
        
        // Simple success message after download
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('downloaded') === 'true') {
            // Show subtle success indicator
            const templateBadge = document.querySelector('.template-badge');
            if (templateBadge) {
                templateBadge.style.background = '#f0fdf4';
                templateBadge.style.borderColor = '#22c55e';
                templateBadge.innerHTML = `
                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-green-700">Downloaded Successfully!</span>
                `;
                setTimeout(() => {
                    templateBadge.style.background = 'white';
                    templateBadge.style.borderColor = '#e5e7eb';
                    templateBadge.innerHTML = `
                        <svg class="template-badge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                        </svg>
                        <span>Template: <span class="template-name">{{ ucfirst($template) }}</span></span>
                    `;
                }, 3000);
            }
        }
        
        // Smooth scroll behavior
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
        
        // Clean, minimal approach - no extra animations
    </script>
</body>
</html>
