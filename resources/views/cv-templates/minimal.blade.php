<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $user->name }} - CV</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            font-size: 9.5pt;
            line-height: 1.45;
            color: #111827;
            background: white;
            -webkit-font-smoothing: antialiased;
        }
        
        .container {
            width: 100%;
            margin-bottom: 0 !important;
            padding-bottom: 0 !important;
        }
        
        /* Header */
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .header .name {
            font-size: 22pt;
            font-weight: 800;
            letter-spacing: -0.04em;
            color: #000;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        
        .header .contact {
            font-size: 9pt;
            color: #4b5563;
            display: block;
        }
        
        /* Sections */
        .section {
            margin-bottom: 18px;
            break-inside: avoid;
        }
        
        .section-title {
            font-size: 10pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #000;
            border-bottom: 1.5px solid #111827;
            padding-bottom: 2px;
            margin-bottom: 10px;
        }
        
        /* Entry styles */
        .entry {
            margin-bottom: 12px;
            break-inside: avoid;
        }
        
        .entry-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
        }
        
        .entry-title {
            font-weight: 700;
            font-size: 10.5pt;
            color: #000;
        }
        
        .entry-date {
            font-size: 8.5pt;
            color: #4b5563;
            font-weight: 600;
        }
        
        .entry-subtitle {
            font-size: 9.5pt;
            font-weight: 600;
            color: #4b5563;
            font-style: italic;
        }
        
        .entry-location {
            font-size: 8.5pt;
            color: #6b7280;
            font-style: italic;
        }
        
        .entry-description {
            font-size: 9pt;
            line-height: 1.5;
            color: #374151;
            margin-top: 2px;
            text-align: justify;
        }
        
        .entry-description ul {
            margin: 2px 0;
            padding-left: 15px;
        }
        
        .entry-description li {
            margin-bottom: 2px;
        }

        @media print {
            body { margin: 0; padding: 0; }
            .container { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="name">{{ $user->name }}</div>
            <div class="contact">
                @php
                    $contact = [];
                    if($user->profile && $user->profile->phone_number) $contact[] = $user->profile->phone_number;
                    if($user->email) $contact[] = $user->email;
                    if($user->profile && $user->profile->domicile) $contact[] = $user->profile->domicile;
                @endphp
                {{ implode(' | ', $contact) }}
            </div>
        </div>

        <!-- Summary -->
        @if($user->profile && $user->profile->bio)
        <div class="section">
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
                <div class="entry-row">
                    <div class="entry-title">{{ $exp->company_name }}</div>
                    <div class="entry-date">{{ format_date_range($exp->start_date, $exp->end_date, $exp->is_current) }}</div>
                </div>
                <div class="entry-row">
                    <div class="entry-subtitle">{{ $exp->position }}</div>
                    <div class="entry-location">{{ $exp->location }}</div>
                </div>
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
                <div class="entry-row">
                    <div class="entry-title">{{ $edu->institution_name }}</div>
                    <div class="entry-date">{{ format_date_range($edu->start_date, $edu->end_date, $edu->is_current) }}</div>
                </div>
                <div class="entry-row">
                    <div class="entry-subtitle">{{ $edu->degree }}{{ $edu->major ? ', ' . $edu->major : '' }}</div>
                    <div class="entry-location">{{ $edu->location }}</div>
                </div>
                @if($edu->gpa)
                <div class="entry-description" style="font-style: italic;">GPA: {{ $edu->gpa }}</div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        <!-- Organizations -->
        @if($organizations->count() > 0)
        <div class="section">
            <div class="section-title">Organizations</div>
            @foreach($organizations as $org)
            <div class="entry">
                <div class="entry-row">
                    <div class="entry-title">{{ $org->organization_name }}</div>
                    <div class="entry-date">{{ format_date_range($org->start_date, $org->end_date, $org->is_current) }}</div>
                </div>
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

        <!-- Projects -->
        @if($projects->count() > 0)
        <div class="section">
            <div class="section-title">Key Projects</div>
            @foreach($projects as $project)
            <div class="entry">
                <div class="entry-row">
                    <div class="entry-title">{{ $project->project_name }}</div>
                    <div class="entry-date">{{ format_date_range($project->start_date, $project->end_date, $project->is_ongoing) }}</div>
                </div>
                <div class="entry-row">
                    <div class="entry-subtitle">{{ $project->role }}</div>
                    @if($project->project_url)
                        <div class="entry-location">{{ $project->project_url }}</div>
                    @endif
                </div>
                @if($project->technologies && is_array($project->technologies))
                    <div class="entry-description" style="font-size: 8pt; color: #6b7280; margin: 4px 0;">
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

        <!-- Achievements -->
        @if($achievements->count() > 0)
        <div class="section">
            <div class="section-title">Achievements & Certifications</div>
            @foreach($achievements as $achievement)
            <div class="entry">
                <div class="entry-row">
                    <div class="entry-title">{{ $achievement->title }}</div>
                    <div class="entry-date">{{ $achievement->issue_date?->format('M Y') }}</div>
                </div>
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
            <div class="entry-description">
                @foreach($skills->groupBy('category') as $category => $categorySkills)
                    <p style="margin-bottom: 4px;"><strong>{{ $category }}:</strong> {{ $categorySkills->pluck('skill_name')->join(', ') }}</p>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</body>
</html>
