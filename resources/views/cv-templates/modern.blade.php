<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $user->name }} - CV</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11pt; line-height: 1.5; color: #333; }
        .container { max-width: 800px; margin: 0 auto; padding: 30px; }
        .header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 3px solid #3b82f6; }
        .header h1 { font-size: 32pt; color: #1e40af; margin-bottom: 5px; }
        .header .subtitle { font-size: 12pt; color: #6b7280; margin-bottom: 10px; }
        .contact-info { display: flex; justify-content: center; flex-wrap: wrap; gap: 15px; font-size: 10pt; color: #4b5563; margin-top: 10px; }
        .contact-item { display: inline-block; }
        .section { margin-bottom: 25px; }
        .section-title { font-size: 16pt; color: #1e40af; margin-bottom: 12px; padding-bottom: 5px; border-bottom: 2px solid #3b82f6; font-weight: bold; }
        .profile-text { text-align: justify; line-height: 1.6; color: #374151; }
        .entry { margin-bottom: 15px; page-break-inside: avoid; }
        .entry-header { display: flex; justify-content: space-between; margin-bottom: 5px; }
        .entry-title { font-weight: bold; color: #1f2937; font-size: 12pt; }
        .entry-subtitle { color: #3b82f6; font-size: 11pt; }
        .entry-meta { color: #6b7280; font-size: 10pt; font-style: italic; }
        .entry-description { margin-top: 5px; color: #4b5563; text-align: justify; }
        .skills-grid { display: flex; flex-wrap: wrap; gap: 8px; }
        .skill-item { background: #eff6ff; color: #1e40af; padding: 5px 12px; border-radius: 15px; font-size: 10pt; display: inline-block; }
        .skill-category { margin-bottom: 12px; }
        .category-name { font-weight: bold; color: #1f2937; margin-bottom: 6px; font-size: 11pt; }
        .two-column { display: flex; gap: 15px; }
        .column { flex: 1; }
        .achievement-item { margin-bottom: 10px; padding-left: 15px; position: relative; }
        .achievement-item:before { content: "•"; position: absolute; left: 0; color: #3b82f6; font-weight: bold; }
        .project-item { background: #f9fafb; padding: 12px; margin-bottom: 12px; border-radius: 8px; border-left: 4px solid #3b82f6; }
        .tech-stack { margin-top: 5px; }
        .tech-badge { background: #dbeafe; color: #1e40af; padding: 3px 8px; border-radius: 10px; font-size: 9pt; display: inline-block; margin-right: 5px; }
        .watermark { text-align: center; margin-top: 30px; padding-top: 15px; border-top: 1px solid #e5e7eb; color: #9ca3af; font-size: 9pt; }
        @page { margin: 20mm; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>{{ $user->name }}</h1>
            @if($user->desired_position)
                <div class="subtitle">{{ $user->desired_position }}</div>
            @endif
            <div class="contact-info">
                <span class="contact-item">📧 {{ $user->email }}</span>
                @if($user->phone)
                    <span class="contact-item">📱 {{ $user->phone }}</span>
                @endif
                @if($user->location)
                    <span class="contact-item">📍 {{ $user->location }}</span>
                @endif
                @if($user->linkedin_url)
                    <span class="contact-item">🔗 LinkedIn</span>
                @endif
            </div>
        </div>

        <!-- Profile Summary -->
        @if($user->profile_summary)
            <div class="section">
                <h2 class="section-title">Profile Summary</h2>
                <p class="profile-text">{{ $user->profile_summary }}</p>
            </div>
        @endif

        <!-- Experience -->
        @if($experiences->count() > 0)
            <div class="section">
                <h2 class="section-title">Professional Experience</h2>
                @foreach($experiences as $exp)
                    <div class="entry">
                        <div class="entry-header">
                            <div>
                                <div class="entry-title">{{ $exp->position }}</div>
                                <div class="entry-subtitle">{{ $exp->company }}</div>
                            </div>
                            <div class="entry-meta" style="text-align: right;">
                                {{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} - 
                                {{ $exp->is_current ? 'Present' : \Carbon\Carbon::parse($exp->end_date)->format('M Y') }}
                                <br>
                                {{ $exp->location }}
                            </div>
                        </div>
                        @if($exp->description)
                            <div class="entry-description">{{ $exp->description }}</div>
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
                        <div class="entry-header">
                            <div>
                                <div class="entry-title">{{ $edu->degree }} in {{ $edu->major }}</div>
                                <div class="entry-subtitle">{{ $edu->institution_name }}</div>
                            </div>
                            <div class="entry-meta" style="text-align: right;">
                                {{ \Carbon\Carbon::parse($edu->start_date)->format('Y') }} - 
                                {{ $edu->is_current ? 'Present' : \Carbon\Carbon::parse($edu->end_date)->format('Y') }}
                                @if($edu->gpa)
                                    <br>GPA: {{ $edu->gpa }}
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Skills -->
        @if($skills->count() > 0)
            <div class="section">
                <h2 class="section-title">Skills</h2>
                @foreach($skills->groupBy('category') as $category => $categorySkills)
                    <div class="skill-category">
                        <div class="category-name">{{ $category }}</div>
                        <div class="skills-grid">
                            @foreach($categorySkills as $skill)
                                <span class="skill-item">{{ $skill->skill_name }} ({{ $skill->proficiency_level }})</span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Projects -->
        @if($projects->count() > 0)
            <div class="section">
                <h2 class="section-title">Projects</h2>
                @foreach($projects as $project)
                    <div class="project-item">
                        <div class="entry-title">{{ $project->project_name }}</div>
                        <div class="entry-meta">
                            {{ $project->role }} | 
                            {{ \Carbon\Carbon::parse($project->start_date)->format('M Y') }} - 
                            {{ $project->is_ongoing ? 'Ongoing' : \Carbon\Carbon::parse($project->end_date)->format('M Y') }}
                        </div>
                        @if($project->description)
                            <div class="entry-description" style="margin-top: 8px;">{{ $project->description }}</div>
                        @endif
                        @if($project->technologies)
                            <div class="tech-stack">
                                @foreach(explode(',', $project->technologies) as $tech)
                                    <span class="tech-badge">{{ trim($tech) }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Achievements -->
        @if($achievements->count() > 0)
            <div class="section">
                <h2 class="section-title">Certifications & Achievements</h2>
                @foreach($achievements as $achievement)
                    <div class="achievement-item">
                        <strong>{{ $achievement->title }}</strong> - {{ $achievement->issuer }}
                        ({{ \Carbon\Carbon::parse($achievement->issue_date)->format('M Y') }})
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Organizations -->
        @if($organizations->count() > 0)
            <div class="section">
                <h2 class="section-title">Organizations & Activities</h2>
                @foreach($organizations as $org)
                    <div class="entry">
                        <div class="entry-header">
                            <div>
                                <div class="entry-title">{{ $org->position }}</div>
                                <div class="entry-subtitle">{{ $org->organization_name }}</div>
                            </div>
                            <div class="entry-meta">
                                {{ \Carbon\Carbon::parse($org->start_date)->format('M Y') }} - 
                                {{ $org->is_current ? 'Present' : \Carbon\Carbon::parse($org->end_date)->format('M Y') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Watermark for Free Users -->
        @if(!$user->is_premium)
            <div class="watermark">
                Generated by TraKerja - <a href="{{ config('app.url') }}">Upgrade to Premium</a> to remove this watermark
            </div>
        @endif
    </div>
</body>
</html>
