<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $user->name }} - CV</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4;
            margin: {{ !empty($margin) ? $margin : '20mm' }};
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
            font-size: {{ !empty($fontSize) ? $fontSize : '9pt' }};
            line-height: 1.6;
            color: #334155;
            background: white;
            -webkit-font-smoothing: antialiased;
        }
        
        .container {
            width: 100%;
        }
        
        /* Header */
        .header {
            margin-bottom: 40px;
        }
        
        .name {
            font-size: 24pt;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -1px;
            margin-bottom: 4px;
        }
        
        .job-title {
            font-size: 11pt;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .contact-bar {
            display: flex;
            gap: 20px;
            margin-top: 15px;
            font-size: 8pt;
            color: #94a3b8;
            font-weight: 600;
        }

        /* Section Layout */
        .section {
            display: flex;
            margin-bottom: 30px;
            break-inside: avoid;
        }
        
        .section-label {
            width: 45mm;
            flex-shrink: 0;
            font-size: 8pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #0f172a;
            padding-top: 4px;
        }
        
        .section-content {
            flex-grow: 1;
            border-left: 1px solid #f1f5f9;
            padding-left: 25px;
        }

        /* Entries */
        .entry {
            margin-bottom: 20px;
        }
        
        .entry-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 4px;
        }
        
        .entry-title {
            font-size: 10pt;
            font-weight: 700;
            color: #0f172a;
        }
        
        .entry-date {
            font-size: 8pt;
            font-weight: 600;
            color: #94a3b8;
        }
        
        .entry-subtitle {
            font-size: 9pt;
            font-weight: 600;
            color: #6366f1;
            margin-bottom: 6px;
        }
        
        .description {
            font-size: 9pt;
            color: #475569;
            text-align: justify;
        }

        /* Skills Grid */
        .skills-grid {
            display: grid;
            grid-template-cols: repeat(2, 1fr);
            gap: 15px;
        }
        
        .skill-group-title {
            font-size: 7.5pt;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        
        .skill-list {
            font-size: 8.5pt;
            color: #475569;
            font-weight: 500;
        }

        /* Recognition List */
        .rec-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 9pt;
        }
        
        .rec-name {
            font-weight: 600;
            color: #334155;
        }
        
        .rec-date {
            color: #94a3b8;
            font-size: 8pt;
        }

        @media print {
            body { -webkit-print-color-adjust: exact; }
            .section-content { border-left-color: #f1f5f9 !important; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <h1 class="name">{{ $user->name }}</h1>
            <div class="job-title">{{ $experiences->first()?->position ?? 'Professional' }}</div>
            
            <div class="contact-bar">
                @if($user->email) <span>{{ $user->email }}</span> @endif
                @if($user->profile && $user->profile->phone_number) <span>&bull; {{ $user->profile->phone_number }}</span> @endif
                @if($user->profile && $user->profile->domicile) <span>&bull; {{ $user->profile->domicile }}</span> @endif
            </div>
        </header>

        <!-- Summary -->
        @if($user->profile && $user->profile->bio)
        <div class="section">
            <div class="section-label">Executive Profile</div>
            <div class="section-content">
                <div class="description">
                    {!! format_cv_text($user->profile->bio) !!}
                </div>
            </div>
        </div>
        @endif

        <!-- Experience -->
        @if($experiences->count() > 0)
        <div class="section">
            <div class="section-label">Professional History</div>
            <div class="section-content">
                @foreach($experiences as $exp)
                <div class="entry">
                    <div class="entry-header">
                        <h3 class="entry-title">{{ $exp->company_name }}</h3>
                        <span class="entry-date">{{ format_date_range($exp->start_date, $exp->end_date, $exp->is_current) }}</span>
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
        </div>
        @endif

        <!-- Projects -->
        @if($projects->count() > 0)
        <div class="section">
            <div class="section-label">Selected Projects</div>
            <div class="section-content">
                @foreach($projects as $project)
                <div class="entry" style="margin-bottom: 15px;">
                    <div class="entry-header">
                        <h3 class="entry-title">{{ $project->project_name }}</h3>
                        <span class="entry-date">{{ format_date_range($project->start_date, $project->end_date, $project->is_ongoing) }}</span>
                    </div>
                    @if($project->description)
                    <div class="description">
                        {!! format_cv_text($project->description) !!}
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Skills -->
        @if($skills->count() > 0)
        <div class="section">
            <div class="section-label">Core Capabilities</div>
            <div class="section-content">
                <div class="skills-grid">
                    @foreach($skills->groupBy('category') as $category => $categorySkills)
                    <div>
                        <h4 class="skill-group-title">{{ $category }}</h4>
                        <p class="skill-list">{{ $categorySkills->pluck('skill_name')->join(', ') }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Education -->
        @if($educations->count() > 0)
        <div class="section">
            <div class="section-label">Academic Background</div>
            <div class="section-content">
                @foreach($educations as $edu)
                <div class="rec-item">
                    <div>
                        <span class="rec-name">{{ $edu->institution_name }}</span>
                        <span style="color: #64748b; margin-left: 5px;">&mdash; {{ $edu->degree }}</span>
                    </div>
                    <span class="rec-date">{{ $edu->start_date?->format('Y') }} - {{ $edu->is_current ? 'Present' : $edu->end_date?->format('Y') }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Recognition -->
        @if($achievements->count() > 0 || $organizations->count() > 0)
        <div class="section">
            <div class="section-label">Recognition & Activity</div>
            <div class="section-content">
                @foreach($achievements as $ach)
                <div class="rec-item">
                    <span class="rec-name">{{ $ach->title }}</span>
                    <span class="rec-date">{{ $ach->issue_date?->format('Y') }}</span>
                </div>
                @endforeach
                @foreach($organizations as $org)
                <div class="rec-item">
                    <span class="rec-name">{{ $org->organization_name }}</span>
                    <span class="rec-date">{{ $org->position }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</body>
</html>


