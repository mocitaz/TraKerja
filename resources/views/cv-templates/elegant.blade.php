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
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9.5pt;
            line-height: 1.4;
            color: #1a202c;
        }
        
        .container {
            max-width: 210mm;
            margin: 0 auto;
        }
        
        /* Two-column layout */
        .layout {
            display: table;
            width: 100%;
            table-layout: fixed;
        }
        
        /* Left Sidebar - 35% */
        .sidebar {
            display: table-cell;
            width: 35%;
            background: linear-gradient(180deg, #2d3748 0%, #1a202c 100%);
            color: white;
            padding: 25mm 15mm;
            vertical-align: top;
        }
        
        /* Main Content - 65% */
        .main-content {
            display: table-cell;
            width: 65%;
            padding: 25mm 20mm;
            vertical-align: top;
            background: white;
        }
        
        /* Sidebar Styles */
        .sidebar .profile-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #e2e8f0;
            margin: 0 auto 20px;
            display: block;
            border: 4px solid rgba(255, 255, 255, 0.3);
            overflow: hidden;
            object-fit: cover;
        }
        
        .sidebar .profile-photo-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 4px solid rgba(255, 255, 255, 0.3);
            font-size: 40pt;
            font-weight: bold;
            color: white;
        }
        
        .sidebar .name {
            font-size: 15pt;
            font-weight: bold;
            margin-bottom: 6px;
            text-align: center;
            letter-spacing: 0.8px;
            line-height: 1.3;
        }
        
        .sidebar .tagline {
            font-size: 8.5pt;
            text-align: center;
            margin-bottom: 25px;
            opacity: 0.9;
            font-style: italic;
            line-height: 1.4;
        }
        
        .sidebar .section {
            margin-bottom: 22px;
        }
        
        .sidebar .section-title {
            font-size: 9.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-bottom: 12px;
            padding-bottom: 6px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.4);
        }
        
        .sidebar .contact-item {
            font-size: 8pt;
            margin-bottom: 10px;
            line-height: 1.6;
            word-wrap: break-word;
        }
        
        .sidebar .contact-item svg {
            display: inline-block;
            vertical-align: middle;
            margin-right: 5px;
        }
        
        .sidebar .skill-item {
            background: rgba(255, 255, 255, 0.15);
            padding: 6px 12px;
            margin-bottom: 7px;
            border-radius: 5px;
            font-size: 8pt;
            text-align: center;
            transition: all 0.2s;
        }
        
        .sidebar .skill-category {
            font-weight: bold;
            font-size: 8.5pt;
            margin-top: 12px;
            margin-bottom: 8px;
            opacity: 0.95;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Main Content Styles */
        .main-content .header {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #2d3748;
        }
        
        .main-content .header .title {
            font-size: 18pt;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 8px;
        }
        
        .main-content .summary {
            font-size: 9pt;
            line-height: 1.7;
            text-align: justify;
            color: #4a5568;
            margin-bottom: 25px;
            padding: 15px;
            background: #f7fafc;
            border-left: 4px solid #2d3748;
            border-radius: 0 4px 4px 0;
        }
        
        .main-content .section {
            margin-bottom: 22px;
            page-break-inside: avoid;
        }
        
        .main-content .section-title {
            font-size: 11pt;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #cbd5e0;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        
        /* Entry Styles */
        .entry {
            margin-bottom: 16px;
            position: relative;
            padding-left: 15px;
            border-left: 2px solid #cbd5e0;
        }
        
        .entry:before {
            content: '';
            position: absolute;
            left: -6px;
            top: 6px;
            width: 10px;
            height: 10px;
            background: #2d3748;
            border-radius: 50%;
            border: 2px solid white;
        }
        
        .entry-header {
            margin-bottom: 5px;
        }
        
        .entry-title {
            font-weight: bold;
            font-size: 10pt;
            color: #2d3748;
            line-height: 1.4;
        }
        
        .entry-date {
            float: right;
            font-size: 8pt;
            color: #718096;
            font-style: italic;
            font-weight: 500;
        }
        
        .entry-company {
            font-size: 9.5pt;
            color: #4a5568;
            font-weight: 600;
            margin-bottom: 3px;
        }
        
        .entry-location {
            font-size: 8pt;
            color: #718096;
            margin-bottom: 8px;
        }
        
        .entry-description {
            font-size: 8.5pt;
            line-height: 1.6;
            color: #4a5568;
            clear: both;
        }
        
        .entry-description ul {
            margin: 4px 0;
            padding-left: 16px;
        }
        
        .entry-description li {
            margin-bottom: 3px;
        }
        
        /* Achievement Badge */
        .achievement-badge {
            display: inline-block;
            background: #2d3748;
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 7.5pt;
            margin-left: 5px;
            font-weight: 600;
        }
        
        /* Watermark */
        .watermark {
            position: fixed;
            bottom: 10mm;
            left: 35%;
            right: 0;
            text-align: center;
            font-size: 7pt;
            color: #cbd5e0;
        }
        
        @page {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="layout">
            <!-- Left Sidebar -->
            <div class="sidebar">
                <!-- Profile Photo -->
                @if($user->profile && $user->profile->profile_picture)
                    <img src="{{ Storage::url($user->profile->profile_picture) }}" alt="{{ $user->name }}" class="profile-photo">
                @else
                    <div class="profile-photo-placeholder">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                
                <div class="name">{{ strtoupper($user->name) }}</div>
                
                @if($user->profile && $user->profile->bio)
                    <div class="tagline">{{ Str::limit($user->profile->bio, 100) }}</div>
                @endif
                
                <!-- Contact Information -->
                <div class="section">
                    <div class="section-title">Contact</div>
                    
                    @if($user->profile && $user->profile->phone_number)
                        <div class="contact-item">📞 {{ $user->profile->phone_number }}</div>
                    @endif
                    
                    @if($user->email)
                        <div class="contact-item">✉️ {{ $user->email }}</div>
                    @endif
                    
                    @if($user->profile && $user->profile->domicile)
                        <div class="contact-item">📍 {{ $user->profile->domicile }}</div>
                    @endif
                    
                    @if($user->profile && $user->profile->linkedin_url)
                        <div class="contact-item">🔗 {{ str_replace(['https://www.linkedin.com/in/', 'https://linkedin.com/in/', 'https://', 'http://'], '', $user->profile->linkedin_url) }}</div>
                    @endif
                    
                    @if($user->profile && $user->profile->github_url)
                        <div class="contact-item">💻 {{ str_replace(['https://github.com/', 'https://www.github.com/', 'https://', 'http://'], '', $user->profile->github_url) }}</div>
                    @endif
                </div>
                
                <!-- Skills by Category -->
                @if($skills->count() > 0)
                    <div class="section">
                        <div class="section-title">Skills</div>
                        @foreach($skills->groupBy('category') as $category => $categorySkills)
                            <div class="skill-category">{{ $category }}</div>
                            @foreach($categorySkills as $skill)
                                <div class="skill-item">{{ $skill->skill_name }}</div>
                            @endforeach
                        @endforeach
                    </div>
                @endif
                
                <!-- Organizations Summary -->
                @if($organizations->count() > 0)
                    <div class="section">
                        <div class="section-title">Leadership</div>
                        @foreach($organizations as $org)
                            <div class="contact-item">
                                <strong>{{ $org->role }}</strong><br>
                                {{ $org->organization_name }}<br>
                                <small style="opacity: 0.8;">
                                    {{ $org->start_date ? $org->start_date->format('Y') : '' }} - 
                                    {{ $org->is_current ? 'Present' : ($org->end_date ? $org->end_date->format('Y') : '') }}
                                </small>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            <!-- Main Content -->
            <div class="main-content">
                <!-- Professional Summary -->
                @if($user->profile && $user->profile->bio)
                    <div class="summary">
                        {{ $user->profile->bio }}
                    </div>
                @endif
                
                <!-- Work Experience -->
                @if($experiences->count() > 0)
                    <div class="section">
                        <div class="section-title">Professional Experience</div>
                        @foreach($experiences as $exp)
                            <div class="entry">
                                <div class="entry-header">
                                    <span class="entry-date">
                                        {{ $exp->start_date ? $exp->start_date->format('M Y') : '' }} - 
                                        {{ $exp->is_current ? 'Present' : ($exp->end_date ? $exp->end_date->format('M Y') : '') }}
                                    </span>
                                    <div class="entry-title">{{ $exp->position }}</div>
                                </div>
                                <div class="entry-company">{{ $exp->company_name }}</div>
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
                                    <span class="entry-date">
                                        {{ $edu->start_date ? $edu->start_date->format('M Y') : '' }} - 
                                        {{ $edu->is_current ? 'Present' : ($edu->end_date ? $edu->end_date->format('M Y') : '') }}
                                    </span>
                                    <div class="entry-title">{{ $edu->degree }}{{ $edu->major ? ' in ' . $edu->major : '' }}</div>
                                </div>
                                <div class="entry-company">{{ $edu->institution_name }}</div>
                                @if($edu->location || $edu->gpa)
                                    <div class="entry-location">
                                        @if($edu->location){{ $edu->location }}@endif
                                        @if($edu->location && $edu->gpa) • @endif
                                        @if($edu->gpa)GPA: {{ $edu->gpa }}@endif
                                    </div>
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
                
                <!-- Projects -->
                @if($projects->count() > 0)
                    <div class="section">
                        <div class="section-title">Notable Projects</div>
                        @foreach($projects as $project)
                            <div class="entry">
                                <div class="entry-header">
                                    @if($project->start_date)
                                        <span class="entry-date">{{ $project->start_date->format('M Y') }}</span>
                                    @endif
                                    <div class="entry-title">{{ $project->project_name }}</div>
                                </div>
                                @if($project->description)
                                    <div class="entry-description">{{ $project->description }}</div>
                                @endif
                                @if($project->technologies)
                                    <div class="entry-description">
                                        <strong>Technologies:</strong> 
                                        {{ is_array($project->technologies) ? implode(', ', $project->technologies) : $project->technologies }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
                
                <!-- Achievements & Certifications -->
                @if($achievements->count() > 0)
                    <div class="section">
                        <div class="section-title">Achievements & Certifications</div>
                        @foreach($achievements as $achievement)
                            <div class="entry">
                                <div class="entry-header">
                                    @if($achievement->date)
                                        <span class="entry-date">{{ $achievement->date->format('M Y') }}</span>
                                    @endif
                                    <div class="entry-title">
                                        {{ $achievement->title }}
                                        @if($achievement->issuer)
                                            <span class="achievement-badge">{{ $achievement->issuer }}</span>
                                        @endif
                                    </div>
                                </div>
                                @if($achievement->description)
                                    <div class="entry-description">{{ $achievement->description }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Watermark for free users -->
    @if(!$user->is_premium)
        <div class="watermark">
            Generated by TraKerja
        </div>
    @endif
</body>
</html>
