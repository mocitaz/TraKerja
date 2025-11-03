<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $user->name }} - CV</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #000000;
        }
        
        .container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 20mm 25mm;
        }
        
        /* Header */
        .header {
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #000000;
        }
        
        .header .name {
            font-size: 28pt;
            font-weight: 700;
            color: #000000;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
        }
        
        .header .contact {
            font-size: 9pt;
            color: #333333;
            line-height: 1.8;
        }
        
        .header .contact a {
            color: #000000;
            text-decoration: none;
        }
        
        .header .contact a:hover {
            text-decoration: underline;
        }
        
        /* Sections */
        .section {
            margin-bottom: 24px;
            page-break-inside: avoid;
        }
        
        .section-title {
            font-size: 13pt;
            font-weight: 700;
            color: #000000;
            margin-bottom: 14px;
            padding-bottom: 6px;
            border-bottom: 2px solid #cccccc;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Entry styles */
        .entry {
            margin-bottom: 18px;
            page-break-inside: avoid;
        }
        
        .entry-header {
            margin-bottom: 6px;
            display: flex;
            justify-content: space-between;
            align-items: baseline;
        }
        
        .entry-title {
            font-weight: 700;
            font-size: 11pt;
            color: #000000;
            flex: 1;
        }
        
        .entry-date {
            font-size: 9pt;
            color: #555555;
            font-weight: 500;
            white-space: nowrap;
            margin-left: 15px;
        }
        
        .entry-subtitle {
            font-size: 10pt;
            font-weight: 600;
            color: #333333;
            margin-bottom: 4px;
        }
        
        .entry-location {
            font-size: 9pt;
            color: #555555;
            margin-bottom: 8px;
        }
        
        .entry-description {
            font-size: 9.5pt;
            line-height: 1.6;
            color: #000000;
        }
        
        .entry-description ul {
            margin: 6px 0;
            padding-left: 20px;
        }
        
        .entry-description li {
            margin-bottom: 5px;
        }
        
        /* Skills */
        .skills-list {
            font-size: 9.5pt;
            line-height: 1.9;
            color: #000000;
        }
        
        .skills-list strong {
            color: #000000;
            font-weight: 600;
        }
        
        /* Summary */
        .summary {
            font-size: 10pt;
            line-height: 1.7;
            text-align: justify;
            color: #000000;
        }
        
        /* Print optimization */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="name">{{ $user->name }}</div>
            <div class="contact">
                @if($user->profile && $user->profile->phone_number)
                    {{ $user->profile->phone_number }}
                @endif
                @if($user->email)
                    @if($user->profile && $user->profile->phone_number) • @endif
                    {{ $user->email }}
                @endif
                @if($user->profile && $user->profile->linkedin_url)
                    @if($user->email || ($user->profile && $user->profile->phone_number)) • @endif
                    <a href="{{ $user->profile->linkedin_url }}">LinkedIn</a>
                @endif
                @if($user->profile && $user->profile->github_url)
                    @if($user->email || ($user->profile && ($user->profile->phone_number || $user->profile->linkedin_url))) • @endif
                    <a href="{{ $user->profile->github_url }}">GitHub</a>
                @endif
                @if($user->profile && $user->profile->domicile)
                    <br>{{ $user->profile->domicile }}
                @endif
            </div>
        </div>

        <!-- Summary -->
        @if($user->profile && $user->profile->bio)
        <div class="section">
            <div class="section-title">Professional Summary</div>
            <div class="summary">{{ $user->profile->bio }}</div>
        </div>
        @endif

        <!-- Experience -->
        @if($experiences->count() > 0)
        <div class="section">
            <div class="section-title">Professional Experience</div>
            @foreach($experiences as $exp)
            <div class="entry">
                <div class="entry-header">
                    <div class="entry-title">{{ $exp->position }}</div>
                    <span class="entry-date">
                        {{ $exp->start_date ? $exp->start_date->format('M Y') : '' }} - 
                        @if($exp->is_current)
                            Present
                        @else
                            {{ $exp->end_date ? $exp->end_date->format('M Y') : '' }}
                        @endif
                    </span>
                </div>
                <div class="entry-subtitle">{{ $exp->company_name }}</div>
                @if($exp->location)
                <div class="entry-location">{{ $exp->location }}</div>
                @endif
                @if($exp->description)
                <div class="entry-description">
                    <ul>
                        @foreach(explode("\n", $exp->description) as $line)
                            @if(trim($line))
                            <li>{{ trim($line, "• -") }}</li>
                            @endif
                        @endforeach
                    </ul>
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
                    <div class="entry-title">{{ $edu->degree }}{{ $edu->major ? ' in ' . $edu->major : '' }}</div>
                    <span class="entry-date">
                        {{ $edu->start_date ? $edu->start_date->format('M Y') : '' }} - 
                        @if($edu->is_current)
                            Present
                        @else
                            {{ $edu->end_date ? $edu->end_date->format('M Y') : '' }}
                        @endif
                    </span>
                </div>
                <div class="entry-subtitle">{{ $edu->institution_name }}</div>
                @if($edu->location)
                <div class="entry-location">{{ $edu->location }}</div>
                @endif
                @if($edu->gpa)
                <div class="entry-location">GPA: {{ $edu->gpa }}</div>
                @endif
                @if($edu->description)
                <div class="entry-description">
                    <ul>
                        @foreach(explode("\n", $edu->description) as $line)
                            @if(trim($line))
                            <li>{{ trim($line, "• -") }}</li>
                            @endif
                        @endforeach
                    </ul>
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
                <div class="entry-header">
                    <div class="entry-title">{{ $org->role }}</div>
                    <span class="entry-date">
                        {{ $org->start_date ? $org->start_date->format('M Y') : '' }} - 
                        @if($org->is_current)
                            Present
                        @else
                            {{ $org->end_date ? $org->end_date->format('M Y') : '' }}
                        @endif
                    </span>
                </div>
                <div class="entry-subtitle">{{ $org->organization_name }}</div>
                @if($org->description)
                <div class="entry-description">
                    <ul>
                        @foreach(explode("\n", $org->description) as $line)
                            @if(trim($line))
                            <li>{{ trim($line, "• -") }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        <!-- Projects -->
        @if($projects->count() > 0)
        <div class="section">
            <div class="section-title">Notable Projects</div>
            @foreach($projects as $project)
            <div class="entry">
                <div class="entry-header">
                    <div class="entry-title">{{ $project->project_name }}</div>
                    @if($project->start_date)
                    <span class="entry-date">{{ $project->start_date->format('M Y') }}</span>
                    @endif
                </div>
                @if($project->description)
                <div class="entry-description">{{ $project->description }}</div>
                @endif
                @if($project->technologies)
                <div class="entry-description" style="margin-top: 4px;"><strong>Technologies:</strong> {{ is_array($project->technologies) ? implode(', ', $project->technologies) : $project->technologies }}</div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        <!-- Skills -->
        @if($skills->count() > 0)
        <div class="section">
            <div class="section-title">Skills & Competencies</div>
            <div class="skills-list">
                @foreach($skills->groupBy('category') as $category => $categorySkills)
                    <strong>{{ $category }}:</strong> {{ $categorySkills->pluck('skill_name')->join(', ') }}<br>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Achievements -->
        @if($achievements->count() > 0)
        <div class="section">
            <div class="section-title">Honors & Achievements</div>
            @foreach($achievements as $achievement)
            <div class="entry">
                <div class="entry-header">
                    <div class="entry-title">{{ $achievement->title }}</div>
                    @if($achievement->date)
                    <span class="entry-date">{{ $achievement->date->format('M Y') }}</span>
                    @endif
                </div>
                @if($achievement->issuer)
                <div class="entry-subtitle">{{ $achievement->issuer }}</div>
                @endif
                @if($achievement->description)
                <div class="entry-description">{{ $achievement->description }}</div>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </div>
</body>
</html>
