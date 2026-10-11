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
        *, *:before, *:after {
            box-sizing: border-box;
        }
        h1, h2, h3, h4, h5, h6, p, ul, ol, li, table, td, th {
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: "DejaVu Serif", Georgia, "Times New Roman", serif;
            font-size: {{ !empty($fontSize) ? $fontSize : '9.5pt' }};
            line-height: 1.40;
            color: #111827;
            background: #ffffff;
            -webkit-font-smoothing: antialiased;
        }

        .container {
            width: 100%;
            margin: 0;
            padding: 0;
        }
        
        /* Executive Header - Perfectly Centered & Letter-spaced */
        .header {
            text-align: center;
            margin-bottom: 12px;
            padding-bottom: 2px;
        }
        
        .header .name {
            font-size: 21pt;
            font-weight: bold;
            margin-bottom: 3px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #0f172a;
            line-height: 1.1;
        }

        .header .headline {
            font-size: 9.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #475569;
            margin-bottom: 4px;
        }
        
        .header .contact-row {
            font-size: 8.8pt;
            color: #374151;
            line-height: 1.35;
        }

        .header .contact-separator {
            color: #94a3b8;
            padding: 0 5px;
        }

        .header-divider-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            border: 0;
        }

        .header-divider-table td {
            padding: 0;
            border: 0;
            border-bottom: 1.2px solid #0f172a;
            height: 1px;
        }

        /* Section Header - Editorial Prestige Full Width Rule */
        .section {
            margin-top: 9px;
            margin-bottom: 0px;
        }

        .section-title-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
            page-break-after: avoid;
            break-after: avoid;
        }

        .section-title-table td {
            padding: 0 0 2px 0;
            border: 0;
            border-bottom: 1.2px solid #0f172a;
            font-size: 9.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #0f172a;
            vertical-align: bottom;
            text-align: left;
        }
        
        /* Entry Styling - Linear ATS Flow */
        .entry {
            margin-bottom: 5px;
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
            color: #0f172a;
            text-align: left;
            width: 72%;
        }
        
        .entry-date {
            font-weight: bold;
            font-size: 0.9em;
            color: #475569;
            text-align: right;
            white-space: nowrap;
            width: 28%;
        }
        
        .entry-subtitle {
            font-style: italic;
            font-size: 0.95em;
            color: #334155;
            text-align: left;
            width: 72%;
            padding-top: 0.5px;
        }

        .entry-location {
            font-style: italic;
            font-size: 0.9em;
            color: #64748b;
            text-align: right;
            white-space: nowrap;
            width: 28%;
            padding-top: 0.5px;
        }

        .entry-description {
            font-size: 0.95em;
            color: #334155;
            line-height: 1.40;
            margin-top: 1px;
            text-align: left;
        }

        /* Skills Table */
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
            line-height: 1.40;
        }

        .skills-category {
            width: 20%;
            font-weight: bold;
            color: #0f172a;
            white-space: nowrap;
            padding-right: 8px;
            text-align: left;
        }

        .skills-list {
            width: 80%;
            color: #334155;
            text-align: left;
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: {{ !empty($margin) ? $margin : '15mm' }};
            }
            body {
                background: #ffffff !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .section-title-table {
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
            <table class="header-divider-table">
                <tr><td></td></tr>
            </table>
        </div>

        <!-- Summary -->
        @if($user->profile && $user->profile->bio)
        <div class="section" style="margin-top: 0;">
            <table class="section-title-table">
                <tr>
                    <td>Professional Summary</td>
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
            <table class="section-title-table">
                <tr>
                    <td>Professional Experience</td>
                </tr>
            </table>
            @foreach($experiences as $exp)
            <div class="entry">
                <table class="entry-table">
                    <tr>
                        <td class="entry-title">{{ $exp->company_name }}</td>
                        <td class="entry-date">{{ format_date_range($exp->start_date, $exp->end_date, $exp->is_current) }}</td>
                    </tr>
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
            <table class="section-title-table">
                <tr>
                    <td>Education</td>
                </tr>
            </table>
            @foreach($educations as $edu)
            <div class="entry">
                <table class="entry-table">
                    <tr>
                        <td class="entry-title">{{ $edu->institution_name }}</td>
                        <td class="entry-date">{{ format_date_range($edu->start_date, $edu->end_date, $edu->is_current) }}</td>
                    </tr>
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

        <!-- Key Projects -->
        @if($projects->count() > 0)
        <div class="section">
            <table class="section-title-table">
                <tr>
                    <td>Key Projects</td>
                </tr>
            </table>
            @foreach($projects as $project)
            <div class="entry">
                <table class="entry-table">
                    <tr>
                        <td class="entry-title">{{ $project->project_name }}</td>
                        <td class="entry-date">{{ format_date_range($project->start_date, $project->end_date, $project->is_current) }}</td>
                    </tr>
                    <tr>
                        <td class="entry-subtitle">{{ $project->role }}</td>
                        <td class="entry-location">{{ $project->project_url ? 'Project Link' : '' }}</td>
                    </tr>
                </table>
                @if($project->description)
                <div class="entry-description">
                    {!! format_cv_text($project->description) !!}
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        <!-- Skills & Expertise -->
        @if($skills->count() > 0)
        <div class="section">
            <table class="section-title-table">
                <tr>
                    <td>Skills & Expertise</td>
                </tr>
            </table>
            <table class="skills-table">
                @foreach($skills->groupBy('category') as $category => $categorySkills)
                <tr>
                    <td class="skills-category">{{ $category ?: 'Expertise' }}:</td>
                    <td class="skills-list">{{ $categorySkills->pluck('skill_name')->implode(', ') }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        @endif

        <!-- Leadership & Organizations -->
        @if($organizations->count() > 0)
        <div class="section">
            <table class="section-title-table">
                <tr>
                    <td>Leadership & Organizations</td>
                </tr>
            </table>
            @foreach($organizations as $org)
            <div class="entry">
                <table class="entry-table">
                    <tr>
                        <td class="entry-title">{{ $org->organization_name }}</td>
                        <td class="entry-date">{{ format_date_range($org->start_date, $org->end_date, $org->is_current) }}</td>
                    </tr>
                    <tr>
                        <td class="entry-subtitle">{{ $org->position }}</td>
                        <td class="entry-location"></td>
                    </tr>
                </table>
                @if($org->description)
                <div class="entry-description">
                    {!! format_cv_text($org->description) !!}
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        <!-- Certifications & Achievements -->
        @if($achievements->count() > 0)
        <div class="section">
            <table class="section-title-table">
                <tr>
                    <td>Certifications & Achievements</td>
                </tr>
            </table>
            @foreach($achievements as $achievement)
            <div class="entry">
                <table class="entry-table">
                    <tr>
                        <td class="entry-title">{{ $achievement->title }}</td>
                        <td class="entry-date">{{ $achievement->date ? \Carbon\Carbon::parse($achievement->date)->format('M Y') : '' }}</td>
                    </tr>
                    <tr>
                        <td class="entry-subtitle">{{ $achievement->issuer }}</td>
                        <td class="entry-location"></td>
                    </tr>
                </table>
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
