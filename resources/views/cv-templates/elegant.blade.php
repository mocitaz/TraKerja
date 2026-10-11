<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $user->name }} - CV</title>
    <style>
        @page {
            size: A4 portrait;
            margin: {{ !empty($margin) ? $margin : '15mm' }};
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: "DejaVu Serif", Georgia, "Times New Roman", serif;
            font-size: {{ !empty($fontSize) ? $fontSize : '9.5pt' }};
            line-height: 1.38;
            color: #111827;
            background: #ffffff;
            -webkit-font-smoothing: antialiased;
        }

        .container {
            width: 100%;
            margin: 0;
            padding: 0;
        }
        
        /* Header - Perfectly Centered */
        .header {
            text-align: center;
            margin-bottom: 11px;
        }
        
        .header .name {
            font-size: 20pt;
            font-weight: bold;
            margin-bottom: 2px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #000000;
            line-height: 1.1;
        }

        .header .headline {
            font-size: 0.95em;
            font-style: italic;
            letter-spacing: 1px;
            color: #4b5563;
            margin-bottom: 3px;
        }
        
        .header .contact-row {
            font-size: 0.88em;
            color: #374151;
            letter-spacing: 0.5px;
            line-height: 1.35;
        }

        .header .contact-separator {
            color: #9ca3af;
            padding: 0 4px;
        }

        /* Section Header - Full Width Line */
        .section {
            margin-top: 9px;
            margin-bottom: 0px;
        }

        .section-title {
            font-size: 0.95em;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #000000;
            border-bottom: 0.8pt solid #000000;
            padding-bottom: 2px;
            margin-bottom: 5px;
            width: 100%;
            page-break-after: avoid;
            break-after: avoid;
        }
        
        /* Entry Styling */
        .entry {
            margin-bottom: 6px;
            width: 100%;
            page-break-inside: avoid;
            break-inside: avoid;
        }
        
        .entry-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 0 1px 0;
            padding: 0;
            border: 0;
        }

        .entry-table td {
            padding: 0;
            border: 0;
            vertical-align: baseline;
        }
        
        .entry-title {
            font-weight: bold;
            font-size: 1em;
            color: #000000;
            text-align: left;
            width: 72%;
        }
        
        .entry-date {
            font-weight: bold;
            font-size: 0.9em;
            color: #374151;
            text-align: right;
            white-space: nowrap;
            width: 28%;
        }
        
        .entry-subtitle {
            font-style: italic;
            font-size: 0.95em;
            color: #374151;
            text-align: left;
            width: 72%;
        }

        .entry-location {
            font-style: italic;
            font-size: 0.9em;
            color: #64748b;
            text-align: right;
            white-space: nowrap;
            width: 28%;
        }

        .entry-description {
            font-size: 0.95em;
            color: #1f2937;
            line-height: 1.38;
            margin-top: 1px;
            text-align: justify;
        }

        .skills-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2px;
            border: 0;
        }

        .skills-table td {
            padding: 1.5px 0;
            border: 0;
            vertical-align: top;
            font-size: 0.95em;
            line-height: 1.38;
        }

        .skills-category {
            width: 110px;
            font-weight: bold;
            color: #000000;
            white-space: nowrap;
            padding-right: 8px;
        }

        .skills-list {
            color: #374151;
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: {{ !empty($margin) ? $margin : '15mm 20mm' }};
            }
            html, body {
                margin: 0 !important;
                padding: 0 !important;
                background: #ffffff !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .container {
                width: 100% !important;
            }
            .section-title {
                page-break-after: avoid !important;
                break-after: avoid !important;
            }
            .entry {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
        }
    </style>
    @if(!empty($browserPreview))
    <style>
        html {
            background-color: #f1f5f9;
            padding: 20px 0;
        }
        body {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto !important;
            padding: {{ !empty($margin) ? $margin : '15mm' }} !important;
            background: #ffffff !important;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.12);
            box-sizing: border-box;
        }
        @media print {
            html { background: #ffffff; padding: 0; }
            body { width: 100% !important; margin: 0 !important; padding: 0 !important; box-shadow: none !important; }
        }
    </style>
    @endif
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="name">{{ $user->name }}</div>
            @if($experiences->first()?->position)
                <div class="headline">{{ $experiences->first()->position }}</div>
            @endif
            <div class="contact-row">
                @php
                    $contact = [];
                    if($user->profile && $user->profile->phone_number) $contact[] = $user->profile->phone_number;
                    if($user->email) $contact[] = $user->email;
                    if($user->profile && $user->profile->domicile) $contact[] = $user->profile->domicile;
                    if($user->profile && $user->profile->linkedin_url) $contact[] = 'LinkedIn';
                    if($user->profile && $user->profile->portfolio_url) $contact[] = 'Portfolio';
                @endphp
                {!! implode('<span class="contact-separator">&#8226;</span>', $contact) !!}
            </div>
        </div>

        <!-- Profile -->
        @if($user->profile && $user->profile->bio)
        <div class="section" style="margin-top: 0;">
            <div class="section-title">Professional Summary</div>
            <div class="entry-description">
                {!! format_cv_text($user->profile->bio) !!}
            </div>
        </div>
        @endif

        <!-- Experience -->
        @if($experiences->count() > 0)
        <div class="section">
            <div class="section-title">Professional Experience</div>
            @foreach($experiences as $exp)
            <div class="entry">
                <table class="entry-table">
                    <tr>
                        <td class="entry-title">{{ $exp->company_name }}</td>
                        <td class="entry-date">{{ format_date_range($exp->start_date, $exp->end_date, $exp->is_current) }}</td>
                    </tr>
                </table>
                <table class="entry-table">
                    <tr>
                        <td class="entry-subtitle">{{ $exp->position }}</td>
                        <td class="entry-location">{{ $exp->location }}</td>
                    </tr>
                </table>
                @if($exp->description)
                <div class="entry-description">
                    {!! format_cv_text($exp->description) !!}
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        <!-- Education -->
        @if($educations->count() > 0)
        <div class="section">
            <div class="section-title">Education</div>
            @foreach($educations as $edu)
            <div class="entry">
                <table class="entry-table">
                    <tr>
                        <td class="entry-title">{{ $edu->institution_name }}</td>
                        <td class="entry-date">{{ format_date_range($edu->start_date, $edu->end_date, $edu->is_current) }}</td>
                    </tr>
                </table>
                <table class="entry-table">
                    <tr>
                        <td class="entry-subtitle">{{ $edu->degree }}{{ $edu->major ? ' in ' . $edu->major : '' }}</td>
                        <td class="entry-location">{{ $edu->location }}</td>
                    </tr>
                </table>
                @if($edu->gpa)
                <div class="entry-description" style="color: #4b5563; font-size: 8.5pt;">
                    GPA: {{ $edu->gpa }}
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        <!-- Projects -->
        @if($projects->count() > 0)
        <div class="section">
            <div class="section-title">Key Projects</div>
            @foreach($projects as $project)
            <div class="entry">
                <table class="entry-table">
                    <tr>
                        <td class="entry-title">{{ $project->project_name }}</td>
                        <td class="entry-date">{{ format_date_range($project->start_date, $project->end_date, $project->is_ongoing) }}</td>
                    </tr>
                </table>
                <table class="entry-table">
                    <tr>
                        <td class="entry-subtitle">{{ $project->role }}</td>
                        @if($project->project_url)
                            <td class="entry-location">{{ $project->project_url }}</td>
                        @endif
                    </tr>
                </table>
                @if($project->technologies && is_array($project->technologies))
                    <div style="font-size: 8pt; color: #475569; margin: 2px 0;">
                        <strong>Technologies:</strong> {{ implode(', ', $project->technologies) }}
                    </div>
                @endif
                @if($project->description)
                <div class="entry-description">
                    {!! format_cv_text($project->description) !!}
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        <!-- Skills -->
        @if($skills->count() > 0)
        <div class="section">
            <div class="section-title">Technical & Professional Skills</div>
            <table class="skills-table">
                @foreach($skills->groupBy('category') as $category => $categorySkills)
                <tr>
                    <td class="skills-category">{{ $category }}:</td>
                    <td class="skills-list">{{ $categorySkills->pluck('skill_name')->join(', ') }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        @endif

        <!-- Leadership & Organizations -->
        @if($organizations->count() > 0)
        <div class="section">
            <div class="section-title">Leadership & Organizations</div>
            @foreach($organizations as $org)
            <div class="entry">
                <table class="entry-table">
                    <tr>
                        <td class="entry-title">{{ $org->organization_name }}</td>
                        <td class="entry-date">{{ format_date_range($org->start_date, $org->end_date, $org->is_current) }}</td>
                    </tr>
                </table>
                <div class="entry-subtitle">{{ $org->position }}</div>
                @if($org->description)
                <div class="entry-description">
                    {!! format_cv_text($org->description) !!}
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        <!-- Achievements -->
        @if($achievements->count() > 0)
        <div class="section">
            <div class="section-title">Certifications & Honors</div>
            @foreach($achievements as $achievement)
            <div class="entry">
                <table class="entry-table">
                    <tr>
                        <td class="entry-title">{{ $achievement->title }}</td>
                        <td class="entry-date">{{ $achievement->issue_date?->format('M Y') }}</td>
                    </tr>
                </table>
                <div class="entry-subtitle">{{ $achievement->issuer }}</div>
                @if($achievement->description)
                <div class="entry-description">
                    {!! format_cv_text($achievement->description) !!}
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </div>
</body>
</html>
