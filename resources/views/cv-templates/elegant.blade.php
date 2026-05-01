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
            font-size: 9.5pt;
            line-height: 1.5;
            color: #1e293b;
            background: white;
            -webkit-font-smoothing: antialiased;
        }
        
        /* Header */
        .header {
            background: #0f172a;
            color: white;
            padding: 15mm 15mm 10mm 15mm;
            text-align: center;
        }
        
        .header .name {
            font-size: 26pt;
            font-weight: 800;
            letter-spacing: -1.5px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        
        .header .contact-grid {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 12px;
            font-size: 8.5pt;
            opacity: 0.85;
        }

        /* Body Layout */
        .content-body {
            display: flex;
            padding: 10mm 12mm;
            gap: 10mm;
            margin-bottom: 0 !important;
            padding-bottom: 0 !important;
        }

        .main-col {
            flex: 2;
        }

        .side-col {
            flex: 1;
            padding-left: 5mm;
            border-left: 1.5px solid #f1f5f9;
        }
        
        .section-title {
            font-size: 11pt;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            border-bottom: 3px solid #0f172a;
            padding-bottom: 3px;
            margin-bottom: 15px;
        }
        
        .entry {
            margin-bottom: 18px;
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
            font-size: 10.5pt;
            color: #0f172a;
        }
        
        .entry-date {
            font-size: 8.5pt;
            color: #64748b;
            font-weight: 600;
        }
        
        .entry-subtitle {
            font-size: 9pt;
            font-weight: 600;
            color: #475569;
            margin-bottom: 4px;
        }
        
        .entry-description {
            font-size: 9pt;
            color: #475569;
            line-height: 1.5;
        }

        .skill-badge {
            display: inline-block;
            background: #f1f5f9;
            color: #475569;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 7.5pt;
            font-weight: 600;
            margin: 0 4px 5px 0;
        }

        @media print {
            body { -webkit-print-color-adjust: exact; }
            .header { background-color: #0f172a !important; color: white !important; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="name">{{ $user->name }}</div>
        <div class="contact-grid">
            @php
                $contact = [];
                if($user->profile && $user->profile->phone_number) $contact[] = $user->profile->phone_number;
                if($user->email) $contact[] = $user->email;
                if($user->profile && $user->profile->domicile) $contact[] = $user->profile->domicile;
            @endphp
            {{ implode(' | ', $contact) }}
        </div>
    </div>

    <div class="content-body">
        <div class="main-col">
            @if($user->profile && $user->profile->bio)
            <div class="section">
                <div class="section-title">Summary</div>
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
        </div>

        <div class="side-col">
            @if($skills->count() > 0)
            <div class="section">
                <div class="section-title">Expertise</div>
                @foreach($skills->groupBy('category') as $category => $categorySkills)
                    <div style="margin-bottom: 12px;">
                        <div style="font-size: 8pt; font-weight: 800; color: #64748b; margin-bottom: 4px; text-transform: uppercase;">{{ $category }}</div>
                        @foreach($categorySkills as $skill)
                            <span class="skill-badge">{{ $skill->skill_name }}</span>
                        @endforeach
                    </div>
                @endforeach
            </div>
            @endif

            @if($educations->count() > 0)
            <div class="section">
                <div class="section-title">Education</div>
                @foreach($educations as $edu)
                <div style="margin-bottom: 10px;">
                    <div style="font-weight: 700; color: #0f172a; font-size: 9.5pt;">{{ $edu->institution_name }}</div>
                    <div style="font-size: 8.5pt; color: #475569;">{{ $edu->degree }}</div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</body>
</html>
