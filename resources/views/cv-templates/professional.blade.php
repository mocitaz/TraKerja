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
            line-height: 1.5;
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
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 4px solid #111827;
        }
        
        .header .name {
            font-size: 24pt;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: #000;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        
        .header .contact {
            font-size: 9pt;
            color: #4b5563;
        }
        
        /* Sections */
        .section {
            margin-bottom: 20px;
            break-inside: avoid;
        }
        
        .section-title {
            font-size: 11pt;
            font-weight: 800;
            color: #000;
            margin-bottom: 10px;
            padding-bottom: 3px;
            border-bottom: 1.5px solid #111827;
            text-transform: uppercase;
        }
        
        /* Entry styles */
        .entry {
            margin-bottom: 15px;
            break-inside: avoid;
        }
        
        .entry-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 2px;
        }
        
        .entry-title {
            font-weight: 700;
            font-size: 11pt;
            color: #000;
        }
        
        .entry-date {
            font-size: 9pt;
            color: #111827;
            font-weight: 700;
        }
        
        .entry-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
        }

        .entry-subtitle {
            font-size: 10pt;
            font-weight: 600;
            color: #374151;
        }
        
        .entry-location {
            font-size: 9pt;
            color: #6b7280;
            font-style: italic;
        }
        
        .entry-description {
            font-size: 9.5pt;
            color: #1f2937;
            margin-top: 3px;
        }

        .entry-description ul {
            margin-left: 15px;
            margin-top: 4px;
        }

        @media print {
            body { margin: 0; padding: 0; }
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
                {{ implode(' • ', $contact) }}
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
                <div class="entry-header">
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
                <div class="entry-header">
                    <div class="entry-title">{{ $edu->institution_name }}</div>
                    <div class="entry-date">{{ format_date_range($edu->start_date, $edu->end_date, $edu->is_current) }}</div>
                </div>
                <div class="entry-row">
                    <div class="entry-subtitle">{{ $edu->degree }}{{ $edu->major ? ', ' . $edu->major : '' }}</div>
                    <div class="entry-location">{{ $edu->location }}</div>
                </div>
                @if($edu->gpa)
                <div class="entry-description" style="font-weight: 700; font-style: italic;">GPA: {{ $edu->gpa }}</div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        <!-- Skills -->
        @if($skills->count() > 0)
        <div class="section">
            <div class="section-title">Skills & Competencies</div>
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
