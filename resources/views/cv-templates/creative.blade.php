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
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #2d3748;
        }
        
        .container {
            max-width: 210mm;
            margin: 0 auto;
        }
        
        /* Header with accent color */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 25mm;
            margin-bottom: 0;
        }
        
        .header .name {
            font-size: 22pt;
            font-weight: bold;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }
        
        .header .contact {
            font-size: 9pt;
            opacity: 0.95;
        }
        
        .header .contact a {
            color: white;
            text-decoration: none;
            border-bottom: 1px solid rgba(255,255,255,0.4);
        }
        
        /* Main content */
        .main-content {
            padding: 20px 25mm;
        }
        
        /* Sections */
        .section {
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 12px;
            padding-bottom: 5px;
            border-bottom: 2px solid #667eea;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Entry styles */
        .entry {
            margin-bottom: 16px;
            position: relative;
            padding-left: 15px;
            border-left: 3px solid #e2e8f0;
        }
        
        .entry-header {
            margin-bottom: 4px;
        }
        
        .entry-title {
            font-weight: bold;
            font-size: 11pt;
            color: #2d3748;
        }
        
        .entry-date {
            float: right;
            font-size: 8.5pt;
            color: #718096;
            background: #edf2f7;
            padding: 2px 10px;
            border-radius: 10px;
            font-weight: 500;
        }
        
        .entry-subtitle {
            font-size: 10pt;
            color: #667eea;
            font-weight: 500;
            margin-bottom: 3px;
        }
        
        .entry-location {
            font-size: 9pt;
            color: #a0aec0;
            margin-bottom: 6px;
        }
        
        .entry-description {
            font-size: 9.5pt;
            line-height: 1.5;
            clear: both;
            color: #4a5568;
        }
        
        .entry-description ul {
            margin: 5px 0;
            padding-left: 18px;
        }
        
        .entry-description li {
            margin-bottom: 3px;
        }
        
        /* Skills with tags */
        .skills-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        
        .skill-category {
            margin-bottom: 10px;
        }
        
        .skill-category-title {
            font-weight: bold;
            color: #667eea;
            font-size: 10pt;
            margin-bottom: 5px;
        }
        
        .skill-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        
        .skill-tag {
            background: #edf2f7;
            color: #4a5568;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 8.5pt;
            display: inline-block;
        }
        
        /* Summary with background */
        .summary-box {
            background: #f7fafc;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            font-size: 9.5pt;
            line-height: 1.6;
            color: #4a5568;
        }
        
        /* Achievements with icons */
        .achievement-item {
            display: flex;
            gap: 10px;
            margin-bottom: 12px;
        }
        
        .achievement-icon {
            width: 20px;
            height: 20px;
            background: #667eea;
            border-radius: 50%;
            flex-shrink: 0;
            margin-top: 2px;
        }
        
        .achievement-content {
            flex: 1;
        }
    </style>
</head>
<body>
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
                • {{ $user->profile->domicile }}
            @endif
        </div>
    </div>

    <div class="main-content">
        <!-- Summary -->
        @if($user->profile && $user->profile->bio)
        <div class="section">
            <div class="section-title">About Me</div>
            <div class="summary-box">{{ $user->profile->bio }}</div>
        </div>
        @endif

        <!-- Experience -->
        @if($experiences->count() > 0)
        <div class="section">
            <div class="section-title">Experience</div>
            @foreach($experiences as $exp)
            <div class="entry">
                <div class="entry-header">
                    <span class="entry-date">
                        {{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} - 
                        @if($exp->is_current)
                            Present
                        @else
                            {{ \Carbon\Carbon::parse($exp->end_date)->format('M Y') }}
                        @endif
                    </span>
                    <div class="entry-title">{{ $exp->job_title }}</div>
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
                            <li>{{ trim($line) }}</li>
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
                    <span class="entry-date">
                        {{ \Carbon\Carbon::parse($edu->start_date)->format('M Y') }} - 
                        @if($edu->is_current)
                            Present
                        @else
                            {{ \Carbon\Carbon::parse($edu->end_date)->format('M Y') }}
                        @endif
                    </span>
                    <div class="entry-title">{{ $edu->degree }} in {{ $edu->field_of_study }}</div>
                </div>
                <div class="entry-subtitle">{{ $edu->institution }}</div>
                @if($edu->description)
                <div class="entry-description">
                    <ul>
                        @foreach(explode("\n", $edu->description) as $line)
                            @if(trim($line))
                            <li>{{ trim($line) }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        <!-- Skills -->
        @if($skills->count() > 0)
        <div class="section">
            <div class="section-title">Skills</div>
            <div class="skills-grid">
                @foreach($skills->groupBy('category') as $category => $categorySkills)
                <div class="skill-category">
                    <div class="skill-category-title">{{ $category }}</div>
                    <div class="skill-tags">
                        @foreach($categorySkills as $skill)
                        <span class="skill-tag">{{ $skill->name }}</span>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Projects -->
        @if($projects->count() > 0)
        <div class="section">
            <div class="section-title">Projects</div>
            @foreach($projects as $project)
            <div class="entry">
                <div class="entry-header">
                    @if($project->date)
                    <span class="entry-date">{{ \Carbon\Carbon::parse($project->date)->format('M Y') }}</span>
                    @endif
                    <div class="entry-title">{{ $project->name }}</div>
                </div>
                @if($project->description)
                <div class="entry-description">{{ $project->description }}</div>
                @endif
                @if($project->technologies)
                <div class="entry-description"><strong>Tech Stack:</strong> {{ $project->technologies }}</div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        <!-- Organizations -->
        @if($organizations->count() > 0)
        <div class="section">
            <div class="section-title">Leadership</div>
            @foreach($organizations as $org)
            <div class="entry">
                <div class="entry-header">
                    <span class="entry-date">
                        {{ \Carbon\Carbon::parse($org->start_date)->format('M Y') }} - 
                        @if($org->is_current)
                            Present
                        @else
                            {{ \Carbon\Carbon::parse($org->end_date)->format('M Y') }}
                        @endif
                    </span>
                    <div class="entry-title">{{ $org->position }}</div>
                </div>
                <div class="entry-subtitle">{{ $org->organization_name }}</div>
                @if($org->description)
                <div class="entry-description">
                    <ul>
                        @foreach(explode("\n", $org->description) as $line)
                            @if(trim($line))
                            <li>{{ trim($line) }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        <!-- Achievements -->
        @if($achievements->count() > 0)
        <div class="section">
            <div class="section-title">Achievements</div>
            @foreach($achievements as $achievement)
            <div class="achievement-item">
                <div class="achievement-icon"></div>
                <div class="achievement-content">
                    <div class="entry-header">
                        @if($achievement->date)
                        <span class="entry-date">{{ \Carbon\Carbon::parse($achievement->date)->format('M Y') }}</span>
                        @endif
                        <div class="entry-title">{{ $achievement->title }}</div>
                    </div>
                    @if($achievement->description)
                    <div class="entry-description">{{ $achievement->description }}</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</body>
</html>
