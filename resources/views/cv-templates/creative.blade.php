<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $user->name }} - CV</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
            font-family: 'Inter', -apple-system, sans-serif;
            font-size: 9pt;
            line-height: 1.4;
            color: #1e293b;
            background: white;
            -webkit-font-smoothing: antialiased;
        }
        
        .main-wrapper {
            display: flex;
            width: 100%;
            min-height: 297mm;
            margin-bottom: 0 !important;
            padding-bottom: 0 !important;
        }
        
        /* Sidebar (Left) */
        .sidebar {
            width: 75mm;
            background: #0f172a;
            color: white;
            padding: 15mm 8mm;
            flex-shrink: 0;
        }
        
        .sidebar-section {
            margin-bottom: 25px;
        }
        
        .sidebar-title {
            font-size: 10pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #6366f1;
            margin-bottom: 12px;
            border-left: 3px solid #6366f1;
            padding-left: 10px;
        }
        
        .contact-item {
            margin-bottom: 10px;
            font-size: 8.5pt;
        }
        
        .contact-label {
            color: #64748b;
            display: block;
            font-size: 7.5pt;
            text-transform: uppercase;
            font-weight: 800;
            margin-bottom: 2px;
        }
        
        /* Main Content (Right) */
        .content {
            flex-grow: 1;
            padding: 15mm 12mm;
            background: white;
        }
        
        .name-header {
            margin-bottom: 25px;
        }
        
        .name {
            font-size: 28pt;
            font-weight: 800;
            color: #0f172a;
            line-height: 1;
            letter-spacing: -1.5px;
            text-transform: uppercase;
        }
        
        .title {
            font-size: 14pt;
            font-weight: 500;
            color: #4f46e5;
            margin-top: 5px;
        }
        
        .section-title {
            font-size: 12pt;
            font-weight: 800;
            color: #0f172a;
            border-bottom: 3px solid #f1f5f9;
            padding-bottom: 5px;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        
        .entry {
            margin-bottom: 15px;
            break-inside: avoid;
        }
        
        .entry-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 3px;
        }
        
        .entry-title {
            font-weight: 700;
            font-size: 10.5pt;
            color: #0f172a;
        }
        
        .entry-date {
            font-size: 8.5pt;
            color: #64748b;
            font-weight: 600;
        }
        
        .entry-subtitle {
            font-size: 9.5pt;
            font-weight: 600;
            color: #4f46e5;
            margin-bottom: 4px;
        }
        
        .entry-description {
            font-size: 9pt;
            color: #475569;
            line-height: 1.5;
        }
        
        .skill-tag {
            display: inline-block;
            background: rgba(99, 102, 241, 0.15);
            color: #a5b4fc;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 7.5pt;
            font-weight: 600;
            margin: 0 4px 5px 0;
        }

        @media print {
            body { -webkit-print-color-adjust: exact; }
            .sidebar { background-color: #0f172a !important; color: white !important; }
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
                    <div style="margin-bottom: 12px;">
                        <div class="contact-label">{{ $category }}</div>
                        @foreach($categorySkills as $skill)
                            <span class="skill-tag">{{ $skill->skill_name }}</span>
                        @endforeach
                    </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Content -->
        <div class="content">
            <div class="name-header">
                <div class="name">{{ $user->name }}</div>
                <div class="title">{{ $experiences->first()?->position ?? 'Professional' }}</div>
            </div>

            @if($user->profile && $user->profile->bio)
            <div class="section">
                <div class="section-title">About Me</div>
                <div class="entry-description">
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
                    <div class="entry-description">
                        {!! format_cv_text($exp->description) !!}
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @endif

            @if($projects->count() > 0)
            <div class="section">
                <div class="section-title">Key Projects</div>
                @foreach($projects as $project)
                <div class="entry">
                    <div class="entry-header">
                        <div class="entry-title">{{ $project->project_name }}</div>
                        @if($project->start_date)
                            <div class="entry-date">{{ $project->start_date->format('M Y') }}</div>
                        @endif
                    </div>
                    @if($project->description)
                    <div class="entry-description">
                        {!! format_cv_text($project->description) !!}
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</body>
</html>
