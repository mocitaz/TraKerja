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
            font-family: "DejaVu Sans", "Helvetica Neue", Arial, sans-serif;
            font-size: {{ !empty($fontSize) ? $fontSize : '9.5pt' }};
            line-height: 1.38;
            color: #1e293b;
            background: #ffffff;
            -webkit-font-smoothing: antialiased;
        }
        
        .container {
            width: 100%;
            margin: 0;
            padding: 0;
        }
        
        /* Header */
        .header {
            margin-bottom: 11px;
        }
        
        .name {
            font-size: 21pt;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: -0.5px;
            line-height: 1.1;
            margin-bottom: 2px;
        }
        
        .job-title {
            font-size: 0.95em;
            font-weight: bold;
            color: #4f46e5;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 4px;
        }

        .header-accent {
            width: 36px;
            height: 2.5px;
            background-color: #4f46e5;
            margin-bottom: 6px;
        }
        
        .contact-row {
            font-size: 0.88em;
            color: #64748b;
            font-weight: 500;
            line-height: 1.35;
        }

        .contact-separator {
            color: #cbd5e1;
            padding: 0 4px;
        }

        /* Sections */
        .section {
            margin-top: 9px;
            margin-bottom: 0px;
        }

        .section-header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
            border-bottom: 1.2px solid #e0e7ff;
            page-break-after: avoid;
            break-after: avoid;
        }

        .section-header-table td {
            padding: 0 0 2px 0;
            border: 0;
            vertical-align: middle;
        }

        .section-dot-cell {
            width: 14px;
            vertical-align: middle;
        }

        .section-dot {
            display: inline-block;
            width: 6px;
            height: 6px;
            background-color: #4f46e5;
            border-radius: 1px;
        }

        .section-title {
            font-size: 0.95em;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 1.2px;
        }

        /* Entries */
        .entry {
            margin-bottom: 6px;
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
            font-size: 1em;
            font-weight: bold;
            color: #0f172a;
            text-align: left;
            width: 72%;
        }
        
        .entry-date {
            font-size: 0.9em;
            font-weight: bold;
            color: #4f46e5;
            text-align: right;
            white-space: nowrap;
            width: 28%;
        }
        
        .entry-subtitle {
            font-size: 0.95em;
            font-weight: normal;
            font-style: italic;
            color: #334155;
            margin-bottom: 1px;
            width: 72%;
        }

        .entry-location {
            font-size: 0.9em;
            color: #64748b;
            font-style: italic;
            text-align: right;
            white-space: nowrap;
            width: 28%;
        }
        
        .entry-description {
            font-size: 0.95em;
            color: #334155;
            line-height: 1.38;
            margin-top: 1px;
            text-align: justify;
        }

        /* Skills Pills */
        .skills-category-group {
            margin-bottom: 5px;
        }

        .skills-category-title {
            font-size: 0.9em;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .skill-pill {
            display: inline-block;
            padding: 1.5px 6px;
            background-color: #eef2ff;
            color: #3730a3;
            font-size: 0.82em;
            font-weight: bold;
            margin: 0 3px 3px 0;
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: {{ !empty($margin) ? $margin : '15mm' }};
            }
            html, body {
                margin: 0 !important;
                padding: 0 !important;
                background: #ffffff !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .section-header-table {
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
            <div class="job-title">{{ $experiences->first()?->position ?? 'Creative Professional' }}</div>
            <div class="header-accent"></div>
            <div class="contact-row">
                @php
                    $contact = [];
                    if($user->profile && $user->profile->phone_number) $contact[] = $user->profile->phone_number;
                    if($user->email) $contact[] = $user->email;
                    if($user->profile && $user->profile->domicile) $contact[] = $user->profile->domicile;
                    if($user->profile && $user->profile->portfolio_url) $contact[] = 'Portfolio';
                    if($user->profile && $user->profile->linkedin_url) $contact[] = 'LinkedIn';
                @endphp
                {!! implode('<span class="contact-separator">&#8226;</span>', $contact) !!}
            </div>
        </div>

        <!-- Summary -->
        @if($user->profile && $user->profile->bio)
        <div class="section" style="margin-top: 0;">
            <table class="section-header-table">
                <tr>
                    <td class="section-dot-cell"><span class="section-dot"></span></td>
                    <td class="section-title">Profile Summary</td>
                </tr>
            </table>
            <div class="entry-description">
                {!! format_cv_text($user->profile->bio) !!}
            </div>
        </div>
        @endif

        <!-- Experience -->
        @if($experiences->count() > 0)
        <div class="section">
            <table class="section-header-table">
                <tr>
                    <td class="section-dot-cell"><span class="section-dot"></span></td>
                    <td class="section-title">Work Experience</td>
                </tr>
            </table>
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

        <!-- Key Projects -->
        @if($projects->count() > 0)
        <div class="section">
            <table class="section-header-table">
                <tr>
                    <td class="section-dot-cell"><span class="section-dot"></span></td>
                    <td class="section-title">Featured Projects</td>
                </tr>
            </table>
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
                    <div style="margin: 2px 0;">
                        @foreach($project->technologies as $tech)
                            <span class="skill-pill" style="background-color: #f1f5f9; color: #475569; border-color: #e2e8f0;">{{ trim($tech) }}</span>
                        @endforeach
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

        <!-- Skills / Capabilities -->
        @if($skills->count() > 0)
        <div class="section">
            <table class="section-header-table">
                <tr>
                    <td class="section-dot-cell"><span class="section-dot"></span></td>
                    <td class="section-title">Skills & Capabilities</td>
                </tr>
            </table>
            @foreach($skills->groupBy('category') as $category => $categorySkills)
            <div class="skills-category-group">
                <div class="skills-category-title">{{ $category }}</div>
                <div>
                    @foreach($categorySkills as $skill)
                        <span class="skill-pill">{{ $skill->skill_name }}</span>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Education -->
        @if($educations->count() > 0)
        <div class="section">
            <table class="section-header-table">
                <tr>
                    <td class="section-dot-cell"><span class="section-dot"></span></td>
                    <td class="section-title">Education</td>
                </tr>
            </table>
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

        <!-- Organizations -->
        @if($organizations->count() > 0)
        <div class="section">
            <table class="section-header-table">
                <tr>
                    <td class="section-dot-cell"><span class="section-dot"></span></td>
                    <td class="section-title">Leadership & Organizations</td>
                </tr>
            </table>
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
            <table class="section-header-table">
                <tr>
                    <td class="section-dot-cell"><span class="section-dot"></span></td>
                    <td class="section-title">Certifications & Honors</td>
                </tr>
            </table>
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
