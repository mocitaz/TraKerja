<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $user->name }} - CV</title>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4;
            margin: 15mm 20mm;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Lora', serif;
            font-size: 9.5pt;
            line-height: 1.4;
            color: #000000;
            background: #ffffff;
            -webkit-font-smoothing: antialiased;
        }

        .container {
            width: 100%;
        }
        
        /* Header - Perfectly Centered */
        .header {
            text-align: center;
            margin-bottom: 8mm;
        }
        
        .header .name {
            font-size: 22pt;
            font-weight: 700;
            margin-bottom: 3mm;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .header .contact-row {
            font-size: 8.5pt;
            color: #333333;
            letter-spacing: 0.5px;
        }

        /* Section Header - Full Width Line */
        .section-title {
            font-size: 10pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            border-bottom: 0.8pt solid #000000;
            padding-bottom: 1mm;
            margin-top: 6mm;
            margin-bottom: 3mm;
            width: 100%;
        }
        
        /* Entry Styling - The 'Wall Street' Alignment */
        .entry {
            margin-bottom: 4mm;
            width: 100%;
            break-inside: avoid;
        }
        
        .entry-top {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            width: 100%;
        }
        
        .entry-left {
            font-weight: 700;
            font-size: 10pt;
        }
        
        .entry-right {
            font-weight: 700;
            font-size: 9pt;
            text-align: right;
        }
        
        .entry-sub {
            display: flex;
            justify-content: space-between;
            font-style: italic;
            font-size: 9pt;
            margin-bottom: 1.5mm;
        }

        .description {
            font-size: 9pt;
            color: #1a1a1a;
            line-height: 1.5;
            text-align: justify;
        }

        .skills-container {
            font-size: 9pt;
            line-height: 1.6;
        }

        .skill-group {
            margin-bottom: 1mm;
        }

        .skill-label {
            font-weight: 700;
            display: inline-block;
            min-width: 30mm;
        }

        @media print {
            body { -webkit-print-color-adjust: exact; background: white !important; }
            .section-title { border-bottom-color: #000000 !important; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <div class="name">{{ $user->name }}</div>
            <div class="contact-row">
                @php
                    $contact = [];
                    if($user->profile && $user->profile->phone_number) $contact[] = $user->profile->phone_number;
                    if($user->email) $contact[] = $user->email;
                    if($user->profile && $user->profile->domicile) $contact[] = $user->profile->domicile;
                @endphp
                {{ implode('  •  ', $contact) }}
            </div>
        </header>

        <!-- Profile -->
        @if($user->profile && $user->profile->bio)
        <div class="section">
            <h2 class="section-title">Professional Summary</h2>
            <div class="description" style="padding-left: 0;">
                {!! format_cv_text($user->profile->bio) !!}
            </div>
        </div>
        @endif

        <!-- Experience -->
        @if($experiences->count() > 0)
        <div class="section">
            <h2 class="section-title">Experience</h2>
            @foreach($experiences as $exp)
            <div class="entry">
                <div class="entry-top">
                    <div class="entry-left">{{ $exp->company_name }}</div>
                    <div class="entry-right">{{ format_date_range($exp->start_date, $exp->end_date, $exp->is_current) }}</div>
                </div>
                <div class="entry-sub">
                    <div>{{ $exp->position }}</div>
                    <div style="font-style: normal; font-size: 8pt; color: #444;">{{ $exp->location ?? '' }}</div>
                </div>
                @if($exp->description)
                <div class="description">
                    {!! format_cv_text($exp->description) !!}
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        <!-- Projects -->
        @if($projects->count() > 0)
        <div class="section">
            <h2 class="section-title">Key Projects</h2>
            @foreach($projects as $project)
            <div class="entry">
                <div class="entry-top">
                    <div class="entry-left">{{ $project->project_name }}</div>
                    <div class="entry-right">{{ format_date_range($project->start_date, $project->end_date, $project->is_ongoing) }}</div>
                </div>
                <div class="entry-sub">
                    <div>{{ $project->role }}</div>
                </div>
                @if($project->description)
                <div class="description">
                    {!! format_cv_text($project->description) !!}
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        <!-- Education -->
        @if($educations->count() > 0)
        <div class="section">
            <h2 class="section-title">Education</h2>
            @foreach($educations as $edu)
            <div class="entry">
                <div class="entry-top">
                    <div class="entry-left">{{ $edu->institution_name }}</div>
                    <div class="entry-right">{{ $edu->start_date?->format('Y') }} — {{ $edu->is_current ? 'Present' : $edu->end_date?->format('Y') }}</div>
                </div>
                <div class="entry-sub">
                    <div>{{ $edu->degree }}</div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Skills -->
        @if($skills->count() > 0)
        <div class="section">
            <h2 class="section-title">Technical Skills</h2>
            <div class="skills-container">
                @foreach($skills->groupBy('category') as $category => $categorySkills)
                <div class="skill-group">
                    <span class="skill-label">{{ $category }}:</span>
                    <span>{{ $categorySkills->pluck('skill_name')->join(', ') }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Recognition -->
        @if($achievements->count() > 0 || $organizations->count() > 0)
        <div class="section">
            <h2 class="section-title">Additional Information</h2>
            <div class="skills-container">
                @if($achievements->count() > 0)
                <div class="skill-group">
                    <span class="skill-label">Honors:</span>
                    <span>{{ $achievements->pluck('title')->join('; ') }}</span>
                </div>
                @endif
                @if($organizations->count() > 0)
                <div class="skill-group">
                    <span class="skill-label">Activity:</span>
                    <span>{{ $organizations->map(fn($org) => $org->organization_name . ' (' . $org->position . ')')->join('; ') }}</span>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</body>
</html>
