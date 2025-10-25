<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $user->name }} - CV</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Arial', sans-serif; font-size: 9.5pt; line-height: 1.35; color: #000; }
        .container { max-width: 210mm; margin: 0 auto; padding: 20mm 20mm 15mm 20mm; }
        
        /* Header */
        .header { margin-bottom: 15px; }
        .header h1 { font-size: 16pt; font-weight: bold; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px; }
        .header .contact-line { font-size: 8.5pt; color: #000; margin-bottom: 2px; line-height: 1.2; }
        
        /* Profile Summary */
        .profile-summary { margin-bottom: 15px; text-align: justify; line-height: 1.4; }
        
        /* Section */
        .section { margin-bottom: 16px; page-break-inside: avoid; }
        .section-title { font-size: 10.5pt; font-weight: bold; margin-bottom: 10px; padding-bottom: 2px; border-bottom: 1.5px solid #000; text-transform: uppercase; }
        
        /* Experience Entry */
        .entry { margin-bottom: 12px; page-break-inside: avoid; }
        .entry-header { display: table; width: 100%; margin-bottom: 2px; }
        .entry-header-left { display: table-cell; width: 70%; }
        .entry-header-right { display: table-cell; width: 30%; text-align: right; vertical-align: top; }
        .entry-company { font-weight: bold; }
        .entry-location { font-weight: normal; color: #000; }
        .entry-date { font-style: normal; white-space: nowrap; }
        .entry-position { font-style: italic; margin-bottom: 4px; margin-top: 1px; }
        .entry-points { margin-left: 18px; margin-top: 2px; padding-left: 0; }
        .entry-points li { margin-bottom: 3px; line-height: 1.35; }
        
        /* Education */
        .education-header { display: table; width: 100%; margin-bottom: 2px; }
        .education-header-left { display: table-cell; width: 70%; }
        .education-header-right { display: table-cell; width: 30%; text-align: right; vertical-align: top; }
        .education-school { font-weight: bold; }
        .education-location { font-weight: normal; color: #000; }
        .education-degree { font-style: italic; margin-bottom: 3px; margin-top: 1px; }
        .education-points { margin-left: 18px; margin-top: 2px; padding-left: 0; }
        .education-points li { margin-bottom: 3px; line-height: 1.35; }
        
        /* Skills Section */
        .skills-entry { margin-bottom: 5px; line-height: 1.4; }
        .skills-entry strong { font-weight: bold; }
        
        @page { margin: 0; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>{{ strtoupper($user->name) }}</h1>
            <div class="contact-line">
                @php
                    $contactParts = [];
                    if($user->profile && $user->profile->phone_number) {
                        $contactParts[] = $user->profile->phone_number;
                    }
                    $contactParts[] = $user->email;
                    if($user->profile && $user->profile->linkedin_url) {
                        $contactParts[] = $user->profile->linkedin_url;
                    }
                    if($user->profile && $user->profile->github_url) {
                        $contactParts[] = $user->profile->github_url;
                    }
                    echo implode(' | ', $contactParts);
                @endphp
            </div>
            @if($user->profile && $user->profile->domicile)
                <div class="contact-line">{{ $user->profile->domicile }}</div>
            @endif
        </div>

        <!-- Profile Summary -->
        @if($user->profile && $user->profile->bio)
            <div class="profile-summary">
                {{ $user->profile->bio }}
            </div>
        @endif

        <!-- Work Experience -->
        @if($experiences->count() > 0)
            <div class="section">
                <div class="section-title">Work Experiences</div>
                @foreach($experiences as $exp)
                    <div class="entry">
                        <div class="entry-header">
                            <div class="entry-header-left">
                                <span class="entry-company">{{ $exp->company_name }}</span>
                                @if($exp->location)
                                    <span class="entry-location"> - {{ $exp->location }}</span>
                                @endif
                            </div>
                            <div class="entry-header-right">
                                <span class="entry-date">
                                    {{ $exp->start_date ? $exp->start_date->format('M Y') : '' }} - 
                                    {{ $exp->is_current ? 'Jun 2025' : ($exp->end_date ? $exp->end_date->format('M Y') : '') }}
                                </span>
                            </div>
                        </div>
                        <div class="entry-position">{{ $exp->position }}</div>
                        @if($exp->description)
                            <ul class="entry-points">
                                @foreach(explode("\n", $exp->description) as $point)
                                    @if(trim($point))
                                        <li>{{ trim($point, "• -") }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Education -->
        @if($educations->count() > 0)
            <div class="section">
                <div class="section-title">Education Level</div>
                @foreach($educations as $edu)
                    <div class="entry">
                        <div class="education-header">
                            <div class="education-header-left">
                                <span class="education-school">{{ $edu->institution_name }}</span>
                                @if($edu->location)
                                    <span class="education-location"> - {{ $edu->location }}</span>
                                @endif
                            </div>
                            <div class="education-header-right">
                                <span class="entry-date">
                                    {{ $edu->start_date ? $edu->start_date->format('M Y') : '' }} - 
                                    {{ $edu->is_current ? 'Aug 2025' : ($edu->end_date ? $edu->end_date->format('M Y') : '') }}
                                </span>
                            </div>
                        </div>
                        <div class="education-degree">
                            {{ $edu->degree }}{{ $edu->major ? ' of ' . $edu->major : '' }}{{ $edu->gpa ? ', ' . $edu->gpa : '' }}
                        </div>
                        @if($edu->description)
                            <ul class="education-points">
                                @foreach(explode("\n", $edu->description) as $point)
                                    @if(trim($point))
                                        <li>{{ trim($point, "• -") }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif        <!-- Education -->
        @if($educations->count() > 0)
            <div class="section">
                <div class="section-title">Education Level</div>
                @foreach($educations as $edu)
                    <div class="entry">
                        <div class="education-header">
                            <div>
                                <span class="education-school">{{ $edu->institution_name }}</span>
                                @if($edu->location)
                                    <span> - {{ $edu->location }}</span>
                                @endif
                            </div>
                            <div class="entry-date">
                                {{ $edu->start_date ? $edu->start_date->format('M Y') : '' }} - 
                                {{ $edu->is_current ? 'Present' : ($edu->end_date ? $edu->end_date->format('M Y') : '') }}
                            </div>
                        </div>
                        <div class="education-degree">
                            {{ $edu->degree }}{{ $edu->major ? ' of ' . $edu->major : '' }}{{ $edu->gpa ? ', ' . $edu->gpa : '' }}
                        </div>
                        @if($edu->description)
                            <ul class="education-points">
                                @foreach(explode("\n", $edu->description) as $point)
                                    @if(trim($point))
                                        <li>{{ trim($point, "• -") }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Skills, Achievements & Other Experience -->
        @if($skills->count() > 0 || $projects->count() > 0 || $achievements->count() > 0)
            <div class="section">
                <div class="section-title">Skills, Achievements & Other Experience</div>
                
                <!-- Skills by Category -->
                @foreach($skills->groupBy('category') as $category => $categorySkills)
                    <div class="skills-entry">
                        <strong>{{ $category }} ({{ $categorySkills->first()->years_of_experience ?? date('Y') }}):</strong>
                        @foreach($categorySkills as $skill){{ $skill->skill_name }}{{ !$loop->last ? ', ' : '' }}@endforeach
                    </div>
                @endforeach
                
                <!-- Projects -->
                @foreach($projects as $project)
                    <div class="skills-entry">
                        <strong>Projects ({{ $project->start_date ? $project->start_date->format('Y') : date('Y') }}):</strong>
                        {{ $project->description ?? $project->project_name }}
                    </div>
                @endforeach
                
                <!-- Achievements -->
                @foreach($achievements as $achievement)
                    <div class="skills-entry">
                        <strong>{{ $achievement->title }}:</strong>
                        {{ $achievement->description ?? $achievement->issuer }}
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Watermark for Free Users -->
        @if(!$user->is_premium)
            <div style="text-align: center; margin-top: 30px; padding-top: 15px; border-top: 1px solid #ccc; color: #999; font-size: 8pt;">
                Generated by TraKerja - Contact admin to remove watermark
            </div>
        @endif
    </div>
</body>
</html>
