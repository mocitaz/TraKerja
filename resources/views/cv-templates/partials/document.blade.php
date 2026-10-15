@php
    use App\Support\CvDocument;
    $pageMargin = in_array($margin ?? null, ['0', '10mm', '12mm', '15mm', '20mm', '22mm', '28mm'], true) ? $margin : '15mm';
    $bodySize = in_array($fontSize ?? null, ['8.5pt', '9.5pt', '10.5pt'], true) ? $fontSize : '9.5pt';
    $profile = $user->profile;
    $sections = [
        ['title' => 'Work Experience', 'items' => $experiences->map(fn($x) => ['title' => $variant === 'professional' && filled($x->company_name) ? $x->company_name : $x->position, 'subtitle' => $variant === 'professional' && filled($x->company_name) ? $x->position : $x->company_name, 'location' => $x->location, 'date' => format_date_range($x->start_date, $x->end_date, $x->is_current), 'description' => $x->description])],
        ['title' => 'Education', 'items' => $educations->map(fn($x) => ['title' => $x->institution_name, 'subtitle' => implode(in_array($variant, ['minimal', 'professional', 'elegant'], true) ? ', ' : ' · ', array_filter([$x->degree, $x->major])), 'location' => $x->location, 'date' => format_date_range($x->start_date, $x->end_date, $x->is_current), 'description' => $x->description, 'detail' => $x->gpa !== null ? 'GPA: '.$x->gpa : null])],
        ['title' => 'Projects', 'items' => $projects->map(fn($x) => ['title' => $x->project_name, 'subtitle' => $x->role, 'date' => format_date_range($x->start_date, $x->end_date, $x->is_ongoing), 'description' => $x->description, 'detail' => is_array($x->technologies) && count($x->technologies) ? 'Technologies: '.implode(', ', $x->technologies) : null, 'links' => array_filter(['Project' => CvDocument::url($x->project_url), 'Repository' => CvDocument::url($x->repository_url)])])],
        ['title' => 'Leadership & Organizations', 'items' => $organizations->map(fn($x) => ['title' => $variant === 'professional' && filled($x->organization_name) ? $x->organization_name : $x->position, 'subtitle' => $variant === 'professional' && filled($x->organization_name) ? $x->position : $x->organization_name, 'date' => format_date_range($x->start_date, $x->end_date, $x->is_current), 'description' => $x->description])],
        ['title' => 'Certifications & Awards', 'items' => $achievements->map(fn($x) => ['title' => $x->title, 'subtitle' => $x->issuer, 'date' => $x->date?->format('M Y'), 'description' => $x->description, 'links' => array_filter(['Certificate' => CvDocument::url($x->certificate_url)])])],
    ];
    $sectionNumber = 0;
    $contacts = array_filter([$user->email, $profile?->phone_number, $profile?->domicile]);
    $links = array_filter(['LinkedIn' => CvDocument::url($profile?->linkedin_url), 'Portfolio' => CvDocument::url($profile?->portfolio_url)]);
    $photo = $variant === 'creative' ? CvDocument::profilePhoto($user->logo) : null;
    if ($variant === 'creative') {
        $sections = [$sections[2], $sections[0], $sections[1], $sections[3], $sections[4]];
    }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $user->name }} - CV</title>
    <style>
        @page { size: A4 portrait; margin: {{ $pageMargin }}; }
        * { box-sizing: border-box; }
        body { margin:0; padding:0; font-family:"DejaVu Sans",sans-serif; font-size:{{ $bodySize }}; line-height:1.36; color:#25313d; background:#fff; }
        h1,h2,h3,p,ul { margin:0; padding:0; }
        a { color:inherit; text-decoration:none; }
        .identity { margin-bottom:17pt; padding-bottom:15pt; border-bottom:1pt solid #d7dce0; }
        .name { font-size:25pt; font-weight:bold; line-height:1.17; letter-spacing:-.65pt; color:#17232d; overflow-wrap:break-word; }
        .headline { margin-top:5pt; font-size:10pt; color:#52606d; }
        .contacts { margin-top:9pt; font-size:8pt; line-height:1.7; color:#53606b; }
        .contact { display:inline; }.separator { padding:0 5pt; color:#9ba3aa; }
        .profile-links { font-size:8pt; line-height:1.7; color:#53606b; margin-top:2pt; }
        .profile-links a { text-decoration:underline; }
        .section { margin-top:12pt; }
        .section-title { font-size:9pt; font-weight:bold; color:#17232d; line-height:1.5; letter-spacing:1.2pt; text-transform:uppercase; padding-bottom:4pt; margin-bottom:7pt; border-bottom:.6pt solid #d7dce0; page-break-after:avoid; }
        .entry { margin-bottom:9pt; }
        .entry-heading { page-break-inside:avoid; page-break-after:avoid; }
        .entry-title { font-size:1em; font-weight:bold; color:#17232d; line-height:1.4; }
        .entry-date { font-size:.82em; color:#596671; margin-top:2pt; line-height:1.5; }
        .entry-subtitle { font-size:.92em; color:#4f5f6c; margin-top:1pt; }
        .location { color:#68747d; }
        .description { margin-top:3pt; }
        .description p { margin:0 0 3pt; orphans:2; widows:2; }
        .description ul { margin:2pt 0 4pt; padding-left:13pt; }
        .description li { margin-bottom:2pt; padding-left:2pt; orphans:2; widows:2; }
        .detail,.entry-links { font-size:.86em; color:#53606b; margin-top:4pt; }
        .entry-links a { text-decoration:underline; }
        .skill-group { margin-bottom:5pt; orphans:2; widows:2; }
        .skill-group strong { color:#17232d; }
        .header-table { width:100%; border-collapse:collapse; table-layout:fixed; }
        .header-table td { padding:0; vertical-align:top; }.photo-cell { width:66pt; text-align:right; }
        .photo { width:54pt; height:66pt; object-fit:cover; }
        @include('cv-templates.partials.' . $variant . '-style')
    </style>
</head>
<body class="cv-{{ $variant }}">
    @if($variant === 'creative')
        @include('cv-templates.partials.creative-header')
    @else
    <header class="identity">
        @if($photo)<table class="header-table"><tr><td>@endif
        <h1 class="name">{!! CvDocument::text($user->name) !!}</h1>
        @if(!in_array($variant, ['minimal', 'professional'], true) && $experiences->first()?->position)<p class="headline">{!! CvDocument::text($experiences->first()->position) !!}</p>@endif
        <div class="contacts">@foreach($contacts as $contact)<span class="contact">{!! CvDocument::text($contact) !!}</span>@if(!$loop->last)<span class="separator">{{ $variant === 'minimal' ? '|' : '·' }}</span>@endif @endforeach</div>
        @if($links)<div class="profile-links">@foreach($links as $label => $url)<a href="{{ $url }}">{{ $label }}: {!! CvDocument::text(preg_replace('#^https?://#i', '', $url)) !!}</a>@if(!$loop->last)<span class="separator">{{ $variant === 'minimal' ? '|' : '·' }}</span>@endif @endforeach</div>@endif
        @if($photo)</td><td class="photo-cell"><img class="photo" src="{{ $photo }}" alt="Profile photo"></td></tr></table>@endif
    </header>
    @endif
    @if(filled($profile?->bio))
        <section class="section"><h2 class="section-title">@if($variant === 'creative')<span class="section-number">{{ str_pad(++$sectionNumber, 2, '0', STR_PAD_LEFT) }}</span>@endif{{ $variant === 'creative' ? 'Profile' : 'Professional Summary' }}</h2><div class="description">{!! CvDocument::description($profile->bio) !!}</div></section>
    @endif
    @if($variant === 'creative' && $skills->isNotEmpty())
        @php $sectionNumber++; @endphp
        @include('cv-templates.partials.creative-skills')
    @endif
    @foreach($sections as $section)
        @if($section['items']->isNotEmpty())
            <section class="section"><h2 class="section-title">@if($variant === 'creative')<span class="section-number">{{ str_pad(++$sectionNumber, 2, '0', STR_PAD_LEFT) }}</span>@endif{{ $section['title'] }}</h2>
                @foreach($section['items'] as $entry)
                    <article class="entry">
                        <div class="entry-heading">
                            <h3 class="entry-title">{!! CvDocument::text($entry['title']) !!}</h3>
                            @if($variant === 'minimal')
                                @php $metadata = array_filter([$entry['subtitle'], $entry['location'] ?? null, $entry['date']], fn($value) => filled($value)); @endphp
                                @if($metadata)<p class="entry-subtitle">{!! CvDocument::text(implode(' | ', $metadata)) !!}</p>@endif
                            @elseif($variant === 'professional')
                                @if(filled($entry['subtitle']) || filled($entry['location'] ?? null) || filled($entry['date']))
                                    <p class="entry-subtitle">
                                        @if(filled($entry['subtitle']))<em class="professional-role">{!! CvDocument::text($entry['subtitle']) !!}</em>@endif
                                        @if(filled($entry['location'] ?? null))<span class="professional-location">{{ filled($entry['subtitle']) ? ' | ' : '' }}{!! CvDocument::text($entry['location']) !!}</span>@endif
                                        @if(filled($entry['date']))<span class="professional-date">{{ $entry['date'] }}</span>@endif
                                    </p>
                                @endif
                            @else
                                @if(filled($entry['subtitle']) || filled($entry['location'] ?? null))<p class="entry-subtitle">{!! CvDocument::text($entry['subtitle']) !!}@if(filled($entry['location'] ?? null))<span class="location">{{ filled($entry['subtitle']) ? ' · ' : '' }}{!! CvDocument::text($entry['location']) !!}</span>@endif</p>@endif
                                @if(filled($entry['date']))<p class="entry-date">{{ $entry['date'] }}</p>@endif
                            @endif
                        </div>
                        @if(filled($entry['detail'] ?? null))<p class="detail">{!! CvDocument::text($entry['detail']) !!}</p>@endif
                        @if(filled($entry['description']))<div class="description">{!! CvDocument::description($entry['description']) !!}</div>@endif
                        @if(!empty($entry['links']))<p class="entry-links">@foreach($entry['links'] as $label => $url)<a href="{{ $url }}">{{ $label }}: {!! CvDocument::text(preg_replace('#^https?://#i', '', $url)) !!}</a>@if(!$loop->last)<span class="separator">{{ $variant === 'minimal' ? '|' : '·' }}</span>@endif @endforeach</p>@endif
                    </article>
                @endforeach
            </section>
        @endif
    @endforeach
    @if($variant !== 'creative' && $skills->isNotEmpty())
        <section class="section {{ in_array($variant, ['minimal', 'professional', 'elegant'], true) && $skills->count() <= 20 && mb_strlen($skills->pluck('skill_name')->join(', ')) <= 800 ? 'keep-skills-together' : '' }}"><h2 class="section-title">@if($variant === 'creative')<span class="section-number">{{ str_pad(++$sectionNumber, 2, '0', STR_PAD_LEFT) }}</span>@endif Skills</h2>
            @foreach($skills->groupBy('category') as $category => $categorySkills)
                <p class="skill-group">@if(filled($category))<strong>{!! CvDocument::text($category) !!}{{ in_array($variant, ['minimal', 'professional', 'elegant'], true) ? ':' : '' }}</strong> &nbsp; @endif{!! CvDocument::text($categorySkills->pluck('skill_name')->join(in_array($variant, ['minimal', 'professional', 'elegant'], true) ? ', ' : ' · ')) !!}</p>
            @endforeach
        </section>
    @endif
</body>
</html>
