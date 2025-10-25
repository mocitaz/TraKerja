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
            font-family: Arial, sans-serif;
            font-size: 9.5pt;
            line-height: 1.35;
            color: #000;
        }
        
        .container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 20mm 20mm 15mm 20mm;
        }
        
        /* Header */
        .header {
            margin-bottom: 15px;
        }
        
        .header .name {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        
        .header .contact {
            font-size: 8.5pt;
            line-height: 1.4;
        }
        
        .header .contact a {
            color: #000;
            text-decoration: none;
        }
        
        /* Sections */
        .section {
            margin-bottom: 14px;
        }
        
        .section-title {
            font-size: 10.5pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 8px;
            padding-bottom: 3px;
            border-bottom: 1.5px solid #000;
        }
        
        /* Entry styles */
        .entry {
            margin-bottom: 12px;
        }
        
        .entry-header {
            display: table;
            width: 100%;
            margin-bottom: 3px;
        }
        
        .entry-title {
            display: table-cell;
            font-weight: bold;
            font-size: 9.5pt;
        }
        
        .entry-date {
            display: table-cell;
            text-align: right;
            font-size: 9.5pt;
            white-space: nowrap;
        }
        
        .entry-subtitle {
            font-size: 9.5pt;
            margin-bottom: 3px;
        }
        
        .entry-location {
            font-size: 9.5pt;
            font-style: italic;
            margin-bottom: 6px;
        }
        
        .entry-description {
            font-size: 9.5pt;
            line-height: 1.35;
        }
        
        .entry-description ul {
            margin: 0;
            padding-left: 18px;
            list-style-type: disc;
        }
        
        .entry-description li {
            margin-bottom: 3px;
        }
        
        /* Skills */
        .skills-list {
            font-size: 9.5pt;
            line-height: 1.5;
        }
        
        /* Watermark for free users */
        .watermark {
            position: fixed;
            bottom: 10mm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7.5pt;
            color: #999;
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
                    @if($user->profile && $user->profile->phone_number) | @endif
                    {{ $user->email }}
                @endif
                @if($user->profile && $user->profile->linkedin_url)
                    @if($user->email || ($user->profile && $user->profile->phone_number)) | @endif
                    <a href="{{ $user->profile->linkedin_url }}">{{ str_replace(['https://www.linkedin.com/in/', 'https://linkedin.com/in/'], '', $user->profile->linkedin_url) }}</a>
                @endif
                @if($user->profile && $user->profile->github_url)
                    @if($user->email || ($user->profile && ($user->profile->phone_number || $user->profile->linkedin_url))) | @endif
                    <a href="{{ $user->profile->github_url }}">{{ str_replace(['https://github.com/', 'https://www.github.com/'], '', $user->profile->github_url) }}</a>
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
            <div class="entry-description">{{ $user->profile->bio }}</div>
        </div>
        @endif

        <!-- Experience -->
        @if($experiences->count() > 0)
        <div class="section">
            <div class="section-title">Work Experience</div>
            @foreach($experiences as $exp)
            <div class="entry">
                <div class="entry-header">
                    <div class="entry-title">{{ $exp->position }}</div>
                    <div class="entry-date">
                        {{ $exp->start_date ? $exp->start_date->format('M Y') : '' }} - 
                        {{ $exp->is_current ? 'Present' : ($exp->end_date ? $exp->end_date->format('M Y') : '') }}
                    </div>
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
                    <div class="entry-date">
                        {{ $edu->start_date ? $edu->start_date->format('M Y') : '' }} - 
                        {{ $edu->is_current ? 'Present' : ($edu->end_date ? $edu->end_date->format('M Y') : '') }}
                    </div>
                </div>
                <div class="entry-subtitle">{{ $edu->institution_name }}</div>
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
            <div class="section-title">Organizations</div>
            @foreach($organizations as $org)
            <div class="entry">
                <div class="entry-header">
                    <div class="entry-title">{{ $org->role }}</div>
                    <div class="entry-date">
                        {{ $org->start_date ? $org->start_date->format('M Y') : '' }} - 
                        {{ $org->is_current ? 'Present' : ($org->end_date ? $org->end_date->format('M Y') : '') }}
                    </div>
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
            <div class="section-title">Projects</div>
            @foreach($projects as $project)
            <div class="entry">
                <div class="entry-header">
                    <div class="entry-title">{{ $project->project_name }}</div>
                    @if($project->start_date)
                    <div class="entry-date">{{ $project->start_date->format('M Y') }}</div>
                    @endif
                </div>
                @if($project->description)
                <div class="entry-description">{{ $project->description }}</div>
                @endif
                @if($project->technologies)
                <div class="entry-description"><strong>Technologies:</strong> {{ is_array($project->technologies) ? implode(', ', $project->technologies) : $project->technologies }}</div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        <!-- Skills -->
        @if($skills->count() > 0)
        <div class="section">
            <div class="section-title">Skills</div>
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
            <div class="section-title">Achievements</div>
            @foreach($achievements as $achievement)
            <div class="entry">
                <div class="entry-header">
                    <div class="entry-title">{{ $achievement->title }}</div>
                    @if($achievement->date)
                    <div class="entry-date">{{ $achievement->date->format('M Y') }}</div>
                    @endif
                </div>
                @if($achievement->description)
                <div class="entry-description">{{ $achievement->description }}</div>
                @endif
                @if($achievement->issuer)
                <div class="entry-description"><em>Issued by: {{ $achievement->issuer }}</em></div>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <!-- Watermark for free users -->
    @if(!$user->is_premium)
    <div class="watermark">
        Generated by TraKerja
    </div>
    @endif
</body>
</html>
