<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg border border-white/30">
                        <img src="{{ asset('images/icon.png') }}" 
                             alt="TraKerja Logo" 
                             class="w-5 h-5 sm:w-6 sm:h-6"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg sm:text-2xl font-bold bg-gradient-to-r from-[#d983e4] to-[#4e71c5] bg-clip-text text-transparent">
                            Resume Analysis Results
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">AI-powered resume review and recommendations</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('ai-analyzer.index') }}" class="px-3 sm:px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors duration-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span class="hidden sm:inline">Back</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            <!-- Success Alert -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-gray-900 mb-1">Analysis Complete!</h3>
                        <p class="text-xs text-gray-600">
                            Your resume has been analyzed against the job description. Review the recommendations below to improve your CV.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Job Description Reference -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 mb-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <svg class="w-4 h-4 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Target Job Description
                    </h3>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-purple-400 max-h-48 overflow-y-auto">
                    <p class="text-sm text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $job_description }}</p>
                </div>
            </div>

            <!-- Analysis Results -->
            <div class="space-y-4 mb-6">
                @php
                    $sections = [
                        'profil' => ['title' => 'Profile / Summary', 'icon' => 'user'],
                        'pendidikan' => ['title' => 'Education', 'icon' => 'graduation-cap'],
                        'pengalaman_kerja' => ['title' => 'Work Experience', 'icon' => 'briefcase'],
                        'pengalaman_organisasi' => ['title' => 'Organizational Experience', 'icon' => 'users'],
                        'projek' => ['title' => 'Projects', 'icon' => 'code'],
                        'keterampilan' => ['title' => 'Skills', 'icon' => 'star'],
                        'prestasi_dan_publikasi' => ['title' => 'Achievements & Publications', 'icon' => 'trophy'],
                    ];
                @endphp

                @foreach($sections as $key => $section)
                    @if(isset($result[$key]) && (!empty($result[$key]['teks_revisi']) || !empty($result[$key]['alasan_perubahan'])))
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <!-- Section Header -->
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <h3 class="text-base font-semibold text-gray-900 flex items-center">
                                    @if($section['icon'] === 'user')
                                        <svg class="w-4 h-4 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    @elseif($section['icon'] === 'graduation-cap')
                                        <svg class="w-4 h-4 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                                        </svg>
                                    @elseif($section['icon'] === 'briefcase')
                                        <svg class="w-4 h-4 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                                        </svg>
                                    @elseif($section['icon'] === 'users')
                                        <svg class="w-4 h-4 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                    @elseif($section['icon'] === 'code')
                                        <svg class="w-4 h-4 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                        </svg>
                                    @elseif($section['icon'] === 'star')
                                        <svg class="w-4 h-4 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                        </svg>
                                    @elseif($section['icon'] === 'trophy')
                                        <svg class="w-4 h-4 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                        </svg>
                                    @endif
                                    {{ $section['title'] }}
                                </h3>
                            </div>

                            <!-- Section Content -->
                            <div class="p-4 space-y-4">
                                @if(!empty($result[$key]['teks_revisi']))
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 mb-2 flex items-center uppercase tracking-wide">
                                            <svg class="w-3 h-3 text-green-600 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Revised Content
                                        </h4>
                                        <div class="bg-green-50 border-l-4 border-green-400 p-3 rounded-r-lg">
                                            <div class="text-sm text-gray-800 leading-relaxed formatted-content">{{ $result[$key]['teks_revisi'] }}</div>
                                        </div>
                                    </div>
                                @endif
                                
                                @if(!empty($result[$key]['alasan_perubahan']))
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 mb-2 flex items-center uppercase tracking-wide">
                                            <svg class="w-3 h-3 text-blue-600 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                            </svg>
                                            Reason for Changes
                                        </h4>
                                        <div class="bg-blue-50 border-l-4 border-blue-400 p-3 rounded-r-lg">
                                            <div class="text-sm text-gray-800 leading-relaxed formatted-content">{{ $result[$key]['alasan_perubahan'] }}</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach

                <!-- Action Plan -->
                @if(isset($result['rencana_aksi']) && is_array($result['rencana_aksi']) && count($result['rencana_aksi']) > 0)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h3 class="text-base font-semibold text-gray-900 flex items-center">
                                <svg class="w-4 h-4 text-amber-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                                Action Plan
                            </h3>
                        </div>
                        <div class="p-4">
                            <ol class="space-y-3">
                                @foreach($result['rencana_aksi'] as $index => $action)
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-amber-100 text-amber-800 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">
                                            {{ $index + 1 }}
                                        </span>
                                        <span class="text-sm text-gray-700 leading-relaxed pt-0.5">{{ $action }}</span>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                @endif

                <!-- Empty State -->
                @if(!isset($result['profil']) && !isset($result['pendidikan']) && !isset($result['pengalaman_kerja']) && !isset($result['pengalaman_organisasi']) && !isset($result['projek']) && !isset($result['keterampilan']) && !isset($result['prestasi_dan_publikasi']))
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-base font-semibold text-gray-900 mb-1">No Analysis Results Available</h3>
                        <p class="text-sm text-gray-600">Please try analyzing your resume again.</p>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3">
                <div class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-2">
                    <a href="{{ route('ai-analyzer.index') }}" class="w-full sm:w-auto px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all duration-200 font-medium text-center text-sm flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span>Analyze Another Resume</span>
                    </a>
                    <a href="{{ route('cv.builder') }}" class="w-full sm:w-auto px-4 py-2 bg-white border border-purple-600 text-purple-600 rounded-lg hover:bg-purple-50 transition-all duration-200 font-medium text-center text-sm flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span>Edit in CV Builder</span>
                    </a>
                </div>
            </div>

            <!-- Debug Section (only in development) -->
            @if(config('app.debug') && isset($result['raw_text']))
                <details class="bg-gray-50 rounded-lg border border-gray-200 p-4 mt-4">
                    <summary class="text-sm font-medium text-gray-700 cursor-pointer hover:text-gray-900">Debug: Raw Response</summary>
                    <pre class="mt-2 text-xs text-gray-600 overflow-x-auto bg-gray-900 text-green-400 p-4 rounded">{{ json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </details>
            @endif
        </div>
    </div>

    <style>
        /* Formatting untuk konten hasil analisis */
        .formatted-content {
            word-wrap: break-word;
            overflow-wrap: break-word;
            line-height: 1.6;
        }

        /* Remove default spacing */
        .formatted-content > div:last-child {
            margin-bottom: 0 !important;
        }

        /* Strong/Bold text */
        .formatted-content strong,
        .formatted-content b {
            font-weight: 600;
            color: #1f2937;
        }

        /* Italic text */
        .formatted-content em,
        .formatted-content i {
            font-style: italic;
        }
    </style>

    <script>
        // Format konten untuk menampilkan bullet points dan numbering dengan rapi
        document.addEventListener('DOMContentLoaded', function() {
            const formattedContents = document.querySelectorAll('.formatted-content');
            
            formattedContents.forEach(content => {
                let text = content.textContent;
                if (!text || !text.trim()) return;
                
                let formatted = [];
                
                // Pattern to detect job title headers - must end with closing parenthesis followed immediately by position
                // Examples: "(PT Bank DBS Indonesia)Machine Learning EngineerJanuary 2025"
                // or "(PT. Multimedia Nusantara)IT OperationJuly 2024"
                // Key: must have ")" followed immediately by capital letter (no period before ")")
                const headerPattern = /\((?:PT\.?|CV\.?)\s+[^)]+\)([A-Z][^●•\n]*?(?:Engineer|Manager|Developer|Analyst|Specialist|Operation|Intern|Director|Coordinator)[^●•\n]*?(?:January|February|March|April|May|June|July|August|September|October|November|December)[^●•\n]*?\d{4}[^●•]*?(?:(?:January|February|March|April|May|June|July|August|September|October|November|December)[^●•]*?\d{4})?)/g;
                
                // First, mark job title headers
                let processedText = text.replace(headerPattern, function(match) {
                    // Only mark as header if it's at the beginning of text or after bullets
                    return `<HEADER>${match}</HEADER>`;
                });
                
                // Function to apply bold formatting (detect **text** or lines that are all caps or end with colon as titles)
                function applyBoldFormatting(text) {
                    // Convert **bold** markdown style to <strong>
                    text = text.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');
                    
                    // Detect section titles (all caps words or lines ending with colon at start of line)
                    // Example: "WORK EXPERIENCE", "Education:", "Skills:"
                    text = text.replace(/^([A-Z][A-Z\s]{2,}):?\s*$/gm, '<strong>$1</strong>');
                    text = text.replace(/^([A-Z][^:\n]{2,30}):(?=\s|$)/gm, '<strong>$1:</strong>');
                    
                    return text;
                }
                
                processedText = applyBoldFormatting(processedText);
                
                // Split by bullet symbols (●, •) to separate each bullet point
                const bulletPattern = /([●•])\s*/g;
                const parts = processedText.split(bulletPattern);
                
                let currentBulletText = '';
                let hasBullets = false;
                
                for (let i = 0; i < parts.length; i++) {
                    const part = parts[i];
                    
                    // If this is a bullet symbol
                    if (part === '●' || part === '•') {
                        // Save previous content before this bullet
                        if (currentBulletText.trim()) {
                            // Check if there's a header in the text
                            const headerMatch = currentBulletText.match(/<HEADER>(.*?)<\/HEADER>/);
                            if (headerMatch) {
                                const headerText = headerMatch[1];
                                const remainingText = currentBulletText.replace(/<HEADER>.*?<\/HEADER>/, '').trim();
                                
                                // Add header as bold, separate line
                                formatted.push(`<div style="margin-bottom: 0.75rem; margin-top: 0.75rem; font-weight: 600; color: #1f2937;">${headerText}</div>`);
                                
                                // Add remaining text as bullet if exists (preserve HTML tags)
                                if (remainingText) {
                                    formatted.push(`<div style="display: flex; margin-bottom: 0.5rem; align-items: flex-start;"><span style="min-width: 1.25rem; margin-right: 0.5rem; color: #6b7280; flex-shrink: 0;">•</span><span style="flex: 1;">${remainingText}</span></div>`);
                                }
                            } else {
                                // Regular bullet point (preserve HTML tags like <strong>)
                                const cleanText = currentBulletText.trim();
                                formatted.push(`<div style="display: flex; margin-bottom: 0.5rem; align-items: flex-start;"><span style="min-width: 1.25rem; margin-right: 0.5rem; color: #6b7280; flex-shrink: 0;">•</span><span style="flex: 1;">${cleanText}</span></div>`);
                            }
                        }
                        currentBulletText = '';
                        hasBullets = true;
                    } else if (hasBullets) {
                        // Add to current bullet text
                        currentBulletText += part;
                    } else if (part.trim()) {
                        // First part before any bullet (might contain header)
                        const headerMatch = part.match(/<HEADER>(.*?)<\/HEADER>/);
                        if (headerMatch) {
                            const headerText = headerMatch[1];
                            const remainingText = part.replace(/<HEADER>.*?<\/HEADER>/, '').trim();
                            
                            // Add header as bold, separate line
                            formatted.push(`<div style="margin-bottom: 0.75rem; font-weight: 600; color: #1f2937;">${headerText}</div>`);
                            
                            // Add remaining text if exists
                            if (remainingText) {
                                const lines = remainingText.split('\n');
                                lines.forEach(line => {
                                    if (line.trim()) {
                                        formatted.push(`<div style="margin-bottom: 0.5rem;">${line.trim()}</div>`);
                                    }
                                });
                            }
                        } else {
                            const lines = part.trim().split('\n');
                            lines.forEach(line => {
                                if (line.trim()) {
                                    // Check if line is a section title (has <strong> tag or all caps)
                                    const hasStrong = line.includes('<strong>');
                                    const isAllCaps = /^[A-Z\s]{3,}$/.test(line.trim().replace(/<\/?strong>/g, ''));
                                    
                                    if (hasStrong || isAllCaps) {
                                        formatted.push(`<div style="margin-bottom: 0.75rem; margin-top: 0.75rem; font-weight: 600; color: #1f2937;">${line.trim()}</div>`);
                                    } else {
                                        formatted.push(`<div style="margin-bottom: 0.5rem;">${line.trim()}</div>`);
                                    }
                                }
                            });
                        }
                    }
                }
                
                // Add last bullet
                if (currentBulletText.trim()) {
                    // Check if there's a header in the last bullet text
                    const headerMatch = currentBulletText.match(/<HEADER>(.*?)<\/HEADER>/);
                    if (headerMatch) {
                        const headerText = headerMatch[1];
                        const remainingText = currentBulletText.replace(/<HEADER>.*?<\/HEADER>/, '').trim();
                        
                        // Add header as bold, separate line
                        formatted.push(`<div style="margin-bottom: 0.75rem; margin-top: 0.75rem; font-weight: 600; color: #1f2937;">${headerText}</div>`);
                        
                        // Add remaining text as bullet if exists
                        if (remainingText) {
                            formatted.push(`<div style="display: flex; margin-bottom: 0.5rem; align-items: flex-start;"><span style="min-width: 1.25rem; margin-right: 0.5rem; color: #6b7280; flex-shrink: 0;">•</span><span style="flex: 1;">${remainingText}</span></div>`);
                        }
                    } else {
                        // Regular bullet point
                        formatted.push(`<div style="display: flex; margin-bottom: 0.5rem; align-items: flex-start;"><span style="min-width: 1.25rem; margin-right: 0.5rem; color: #6b7280; flex-shrink: 0;">•</span><span style="flex: 1;">${currentBulletText.trim()}</span></div>`);
                    }
                }
                
                // If no bullets found, check for other formats
                if (!hasBullets) {
                    formatted = [];
                    const lines = processedText.split('\n');
                    
                    lines.forEach((line, index) => {
                        if (!line.trim()) {
                            if (index > 0 && index < lines.length - 1) {
                                formatted.push('<div style="height: 0.5rem;"></div>');
                            }
                            return;
                        }
                        
                        // Check for header tags
                        const headerMatch = line.match(/<HEADER>(.*?)<\/HEADER>/);
                        if (headerMatch) {
                            const headerText = headerMatch[1];
                            const remainingText = line.replace(/<HEADER>.*?<\/HEADER>/, '').trim();
                            
                            // Add header as bold, separate line
                            formatted.push(`<div style="margin-bottom: 0.75rem; ${index > 0 ? 'margin-top: 0.75rem;' : ''} font-weight: 600; color: #1f2937;">${headerText}</div>`);
                            
                            // Add remaining text if exists
                            if (remainingText) {
                                formatted.push(`<div style="margin-bottom: 0.5rem;">${remainingText}</div>`);
                            }
                            return;
                        }
                        
                        // Numbered list (1., 2., 3., etc)
                        const numberMatch = line.match(/^\s*(\d+)\.\s+(.+)/);
                        if (numberMatch) {
                            formatted.push(`<div style="display: flex; margin-bottom: 0.5rem; align-items: flex-start;"><span style="min-width: 2rem; margin-right: 0.5rem; font-weight: 600; color: #374151; flex-shrink: 0;">${numberMatch[1]}.</span><span style="flex: 1;">${numberMatch[2].trim()}</span></div>`);
                            return;
                        }
                        
                        // Regular line (check if it's a title/header based on <strong> or all caps)
                        const hasStrong = line.includes('<strong>');
                        const isAllCaps = /^[A-Z\s]{3,}$/.test(line.trim().replace(/<\/?strong>/g, ''));
                        
                        if (hasStrong || isAllCaps) {
                            formatted.push(`<div style="margin-bottom: 0.75rem; ${index > 0 ? 'margin-top: 0.75rem;' : ''} font-weight: 600; color: #1f2937;">${line.trim()}</div>`);
                        } else {
                            formatted.push(`<div style="margin-bottom: 0.5rem;">${line.trim()}</div>`);
                        }
                    });
                }
                
                if (formatted.length > 0) {
                    content.innerHTML = formatted.join('');
                }
            });
        });
    </script>
</x-app-layout>