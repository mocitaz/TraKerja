<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $user->name }} - CV</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
            font-size: {{ !empty($fontSize) ? $fontSize : '9pt' }};
            line-height: 1.5;
            color: #1e293b;
            background: white;
            -webkit-font-smoothing: antialiased;
        }
        
        .main-wrapper {
            display: flex;
            width: 100%;
            min-height: 297mm;
        }

        /* Sidebar (Left) */
        .sidebar {
            width: 70mm;
            background: #f8fafc;
            border-right: 1px solid #e2e8f0;
            padding: 12mm 8mm;
            flex-shrink: 0;
        }

        .sidebar-section {
            margin-bottom: 25px;
        }

        .sidebar-title {
            font-size: 8pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #475569;
            margin-bottom: 12px;
            padding-bottom: 4px;
            border-bottom: 2px solid #cbd5e1;
        }

        .contact-item {
            margin-bottom: 8px;
            font-size: 8pt;
        }

        .contact-label {
            display: block;
            font-weight: 800;
            color: #94a3b8;
            font-size: 7pt;
            text-transform: uppercase;
        }

        .skill-group {
            margin-bottom: 12px;
        }

        .skill-name {
            font-weight: 600;
            font-size: 8.5pt;
            color: #334155;
        }

        /* Content (Right) */
        .content {
            flex-grow: 1;
            padding: 12mm 12mm;
        }

        .header {
            margin-bottom: 30px;
        }

        .name {
            font-size: 26pt;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -1.5px;
            line-height: 1;
            margin-bottom: 5px;
        }

        .job-title {
            font-size: 11pt;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 2.5px;
            margin-top: 4px;
        }

        .section {
            margin-bottom: 25px;
            break-inside: avoid;
        }

        .section-title {
            font-size: 10pt;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        .entry {
            margin-bottom: 15px;
        }

        .entry-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 2px;
        }

        .entry-title {
            font-weight: 800;
            font-size: 10pt;
            color: #0f172a;
        }

        .entry-date {
            font-size: 8.5pt;
            color: #64748b;
            font-weight: 700;
        }

        .entry-subtitle {
            font-size: 9pt;
            font-weight: 600;
            color: #475569;
            margin-bottom: 4px;
        }

        .description {
            font-size: 9pt;
            color: #334155;
            line-height: 1.5;
            text-align: justify;
        }

        @media print {
            body { -webkit-print-color-adjust: exact; }
            .sidebar { background-color: #f8fafc !important; }
        }
    </style>
</head>
<body>
    <div class="main-wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-section">
                <div class="sidebar-title">Contact</div>
                @if($user->email)
                    <div class="contact-item">
                        <span class="contact-label">Email</span>
                        {{ $user->email }}
                    </div>
                @endif
                @if($user->profile && $user->profile->phone_number)
                    <div class="contact-item">
                        <span class="contact-label">Phone</span>
                        {{ $user->profile->phone_number }}
                    </div>
                @endif
                @if($user->profile && $user->profile->domicile)
                    <div class="contact-item">
                        <span class="contact-label">Location</span>
                        {{ $user->profile->domicile }}
                    </div>
                @endif
            </div>

            @if($skills->count() > 0)
            <div class="sidebar-section">
                <div class="sidebar-title">Expertise</div>
                @foreach($skills->groupBy('category') as $category => $categorySkills)
                    <div class="skill-group">
                        <span class="contact-label">{{ $category }}</span>
                        <div class="skill-name">{{ $categorySkills->pluck('skill_name')->join(', ') }}</div>
                    </div>
                @endforeach
            </div>
            @endif

            @if($educations->count() > 0)
            <div class="sidebar-section">
                <div class="sidebar-title">Education</div>
                @foreach($educations as $edu)
                    <div style="margin-bottom: 12px;">
                        <div class="skill-name">{{ $edu->institution_name }}</div>
                        <div style="font-size: 8pt; color: #475569;">{{ $edu->degree }}</div>
                        <div style="font-size: 7.5pt; color: #94a3b8;">{{ $edu->start_date?->format('Y') }} - {{ $edu->is_current ? 'Present' : $edu->end_date?->format('Y') }}</div>
                    </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Content -->
        <div class="content">
            <div class="header">
                <div class="name">{{ $user->name }}</div>
                <div class="job-title">{{ $experiences->first()?->position ?? 'Professional Executive' }}</div>
            </div>

            @if($user->profile && $user->profile->bio)
            <div class="section">
                <div class="section-title">Profile</div>
                <div class="description">
                    {!! format_cv_text($user->profile->bio) !!}
                </div>
            </div>
            @endif

            @if($experiences->count() > 0)
            <div class="section">
                <div class="section-title">Experience</div>
                @foreach($experiences as $exp)
                <div class="entry">
                    <div class="entry-header">
                        <div class="entry-title">{{ $exp->company_name }}</div>
                        <div class="entry-date">{{ format_date_range($exp->start_date, $exp->end_date, $exp->is_current) }}</div>
                    </div>
                    <div class="entry-subtitle">{{ $exp->position }}</div>
                    @if($exp->description)
                    <div class="description">
                        {!! format_cv_text($exp->description) !!}
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @endif

            @if($projects->count() > 0)
            <div class="section">
                <div class="section-title">Projects</div>
                @foreach($projects as $project)
                <div class="entry">
                    <div class="entry-header">
                        <div class="entry-title">{{ $project->project_name }}</div>
                        <div class="entry-date">{{ format_date_range($project->start_date, $project->end_date, $project->is_ongoing) }}</div>
                    </div>
                    <div class="entry-subtitle" style="font-style: italic; color: #64748b;">{{ $project->role }}</div>
                    @if($project->description)
                    <div class="description">
                        {!! format_cv_text($project->description) !!}
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @endif

            @if($achievements->count() > 0 || $organizations->count() > 0)
            <div class="section">
                <div class="section-title">Additional Information</div>
                @foreach($achievements as $ach)
                    <div style="margin-bottom: 8px;">
                        <span style="font-weight: 700;">{{ $ach->title }}</span>
                        <span style="color: #64748b; font-size: 8.5pt;"> — {{ $ach->issue_date?->format('Y') }}</span>
                    </div>
                @endforeach
                @foreach($organizations as $org)
                    <div style="margin-bottom: 8px;">
                        <span style="font-weight: 700;">{{ $org->organization_name }}</span>
                        <span style="color: #64748b; font-size: 8.5pt;"> — {{ $org->position }}</span>
                    </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</body>
</html>

