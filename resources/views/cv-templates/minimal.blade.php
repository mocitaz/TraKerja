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
            font-family: Arial, Helvetica, sans-serif;
            font-size: {{ !empty($fontSize) ? $fontSize : '9.5pt' }};
            line-height: 1.45;
            color: #111827;
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
            text-align: center;
            margin-bottom: 16px;
        }
        
        .header .name {
            font-size: 22pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            color: #0f172a;
            line-height: 1.1;
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .header .headline {
            font-size: 9.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #475569;
            margin-bottom: 5px;
        }
        
        .header .contact-row {
            font-size: 8.5pt;
            color: #4b5563;
            line-height: 1.4;
        }

        .header .contact-separator {
            color: #94a3b8;
            padding: 0 5px;
        }
        
        /* Sections */
        .section {
            margin-top: 13px;
            margin-bottom: 3px;
        }
        
        .section-title {
            font-size: 9.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #0f172a;
            border-bottom: 1.5px solid #0f172a;
            padding-bottom: 3px;
            margin-bottom: 8px;
            page-break-after: avoid;
            break-after: avoid;
        }
        
        /* Entry styles */
        .entry {
            margin-bottom: 10px;
            page-break-inside: avoid;
            break-inside: avoid;
        }
        
        .entry-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 0 2px 0;
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
            font-size: 9.5pt;
            color: #0f172a;
            text-align: left;
        }
        
        .entry-date {
            font-size: 8.5pt;
            color: #475569;
            font-weight: bold;
            text-align: right;
            white-space: nowrap;
        }
        
        .entry-subtitle {
            font-size: 9pt;
            font-weight: normal;
            font-style: italic;
            color: #334155;
            text-align: left;
        }
        
        .entry-location {
            font-size: 8.5pt;
            color: #64748b;
            font-style: italic;
            text-align: right;
            white-space: nowrap;
        }
        
        .entry-description {
            font-size: 9pt;
            line-height: 1.45;
            color: #334155;
            margin-top: 2px;
            text-align: justify;
        }
        
        .skills-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2px;
            border: 0;
        }

        .skills-table td {
            padding: 2px 0;
            border: 0;
            vertical-align: top;
            font-size: 9pt;
            line-height: 1.45;
        }

        .skills-category {
            width: 130px;
            font-weight: bold;
            color: #0f172a;
            white-space: nowrap;
            padding-right: 10px;
        }

        .skills-list {
            color: #334155;
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: {{ !empty($margin) ? $margin : '15mm' }};
            }
            html, body {
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
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

        <!-- Summary -->
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
                        <strong style="color: #0f172a;">Technologies:</strong> {{ implode(', ', $project->technologies) }}
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

        <!-- Organizations -->
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

        <!-- Skills -->
        @if($skills->count() > 0)
        <div class="section">
            <div class="section-title">Skills & Expertise</div>
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
    </div>
</body>
</html>
