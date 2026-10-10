<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $user->name }} | Portfolio</title>
    @if($preview)<meta name="robots" content="noindex,nofollow">@endif
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($user->profile?->bio ?? $user->profile?->headline ?? 'Portfolio of '.$user->name), 160) }}">
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
@php
    $safeUrl = static fn($url) => is_string($url) && filter_var($url, FILTER_VALIDATE_URL) && in_array(strtolower(parse_url($url, PHP_URL_SCHEME) ?? ''), ['http', 'https'], true) ? $url : null;
    $links = collect($user->profile?->social_links ?? [])->filter(fn($link) => isset($link['url'], $link['name']) && $safeUrl($link['url']));
    $hasWork = $user->projects->isNotEmpty() || $user->experiences->isNotEmpty();
    $hasBackground = $user->educations->isNotEmpty() || $user->organizations->isNotEmpty() || $user->achievements->isNotEmpty();
@endphp
<body class="portfolio-page" data-theme="{{ $theme }}">
    <a href="#portfolio-main" class="portfolio-skip">Skip to content</a>
    <div class="portfolio-shell">
        @if($preview)<div class="portfolio-preview-bar"><div><strong>Private preview</strong><span>This view does not publish your site.</span></div><a href="{{ route('cv.builder') }}">Back to Builder</a></div>@endif
        <header class="portfolio-topbar"><a href="#portfolio-main" class="portfolio-wordmark">{{ $user->name }}</a><nav aria-label="Portfolio navigation">@if(filled($user->profile?->bio))<a href="#about">About</a>@endif @if($hasWork)<a href="#work">Work</a>@endif @if($hasBackground)<a href="#background">Background</a>@endif</nav><a href="mailto:{{ $user->email }}" class="portfolio-contact-button">Get in touch <span aria-hidden="true">↗</span></a></header>
        <main id="portfolio-main">
            <section class="portfolio-intro">
                <div class="portfolio-intro-copy"><p class="portfolio-kicker">Personal portfolio @if($user->profile?->domicile)<span>· {{ $user->profile->domicile }}</span>@endif</p><h1>{{ $user->name }}</h1>@if($user->profile?->headline)<p class="portfolio-headline">{{ $user->profile->headline }}</p>@endif
                    @if($links->isNotEmpty())<div class="portfolio-socials">@foreach($links as $link)<a href="{{ $safeUrl($link['url']) }}" target="_blank" rel="noopener noreferrer">{{ $link['name'] }} <span aria-hidden="true">↗</span></a>@endforeach</div>@endif
                </div>
                <div class="portfolio-portrait"><img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" width="180" height="180"></div>
            </section>
            @if(filled($user->profile?->bio))<section id="about" class="portfolio-about"><h2>A little about me</h2><p>{{ $user->profile->bio }}</p></section>@elseif($preview && !$hasWork && !$hasBackground && $user->skills->isEmpty())<section class="portfolio-empty"><h2>Your story belongs here.</h2><p>Add a summary, project, skill, or education in CV Builder. This preview uses only your saved content.</p><a href="{{ route('cv.builder') }}">Start building your content</a></section>@endif
            @if($hasWork)<div id="work" class="portfolio-work">
                @if($user->projects->isNotEmpty())<section class="portfolio-section"><header><div><p class="portfolio-kicker">Selected work</p><h2>Things I've built</h2></div><span>{{ $user->projects->count() }} {{ \Illuminate\Support\Str::plural('project', $user->projects->count()) }}</span></header><div class="portfolio-project-grid">@foreach($user->projects as $project)<article class="portfolio-project portfolio-project-{{ $loop->index % 3 }}"><div class="portfolio-project-top"><span>{{ $project->role ?: 'Project' }}</span>@if($project->start_date)<time datetime="{{ $project->start_date->toDateString() }}">{{ $project->start_date->format('Y') }}</time>@endif</div><h3>{{ $project->project_name }}</h3>@if($project->description)<p class="portfolio-description">{{ $project->description }}</p>@endif @if(is_array($project->technologies) && count($project->technologies))<ul class="portfolio-tags" aria-label="Project technologies">@foreach($project->technologies as $tech)<li>{{ $tech }}</li>@endforeach</ul>@endif<div class="portfolio-project-links">@if($url = $safeUrl($project->project_url))<a href="{{ $url }}" target="_blank" rel="noopener noreferrer">View project <span aria-hidden="true">↗</span></a>@endif @if($url = $safeUrl($project->repository_url))<a href="{{ $url }}" target="_blank" rel="noopener noreferrer">Repository <span aria-hidden="true">↗</span></a>@endif</div></article>@endforeach</div></section>@endif
                @if($user->experiences->isNotEmpty())<section class="portfolio-section"><header><div><p class="portfolio-kicker">Experience</p><h2>Where I've contributed</h2></div></header><div class="portfolio-timeline">@foreach($user->experiences as $experience)<article><div class="portfolio-period">{{ $experience->start_date?->format('M Y') }}@if($experience->start_date) &ndash; @endif{{ $experience->is_current ? 'Present' : $experience->end_date?->format('M Y') }}</div><div><h3>{{ $experience->position }}</h3><p class="portfolio-entry-meta">{{ $experience->company_name }}@if($experience->location) · {{ $experience->location }}@endif</p>@if($experience->description)<p class="portfolio-description">{{ $experience->description }}</p>@endif</div></article>@endforeach</div></section>@endif
            </div>@endif
            @if($user->skills->isNotEmpty())<section class="portfolio-section portfolio-skills"><header><div><p class="portfolio-kicker">My toolkit</p><h2>Skills & strengths</h2></div></header><div class="portfolio-skill-groups">@foreach($user->skills->groupBy('category') as $category => $skills)<div><h3>{{ $category ?: 'Skills' }}</h3><ul class="portfolio-tags">@foreach($skills as $skill)<li>{{ $skill->skill_name }}</li>@endforeach</ul></div>@endforeach</div></section>@endif
            @if($hasBackground)<section id="background" class="portfolio-section"><header><div><p class="portfolio-kicker">Beyond the work</p><h2>Learning & milestones</h2></div></header><div class="portfolio-background-grid">
                @if($user->educations->isNotEmpty())<section class="portfolio-background-card"><h3>Education</h3>@foreach($user->educations as $education)<article><h4>{{ $education->institution_name }}</h4><p>{{ $education->degree }}@if($education->major) · {{ $education->major }}@endif</p><span>{{ $education->start_date?->format('Y') }}@if($education->start_date) &ndash; @endif{{ $education->is_current ? 'Present' : $education->end_date?->format('Y') }}</span>@if($education->description)<p class="portfolio-description">{{ $education->description }}</p>@endif</article>@endforeach</section>@endif
                @if($user->organizations->isNotEmpty())<section class="portfolio-background-card"><h3>Organizations</h3>@foreach($user->organizations as $organization)<article><h4>{{ $organization->organization_name }}</h4><p>{{ $organization->role }}</p><span>{{ $organization->start_date?->format('M Y') }}@if($organization->start_date) &ndash; @endif{{ $organization->is_current ? 'Present' : $organization->end_date?->format('M Y') }}</span>@if($organization->description)<p class="portfolio-description">{{ $organization->description }}</p>@endif</article>@endforeach</section>@endif
                @if($user->achievements->isNotEmpty())<section class="portfolio-background-card"><h3>Achievements</h3>@foreach($user->achievements as $achievement)<article><h4>{{ $achievement->title }}</h4><p>{{ $achievement->issuer }}@if($achievement->date) · {{ $achievement->date->format('M Y') }}@endif</p>@if($achievement->description)<p class="portfolio-description">{{ $achievement->description }}</p>@endif @if($url = $safeUrl($achievement->certificate_url))<a href="{{ $url }}" target="_blank" rel="noopener noreferrer">View credential <span aria-hidden="true">↗</span></a>@endif</article>@endforeach</section>@endif
            </div></section>@endif
            <section class="portfolio-outro"><div><p class="portfolio-kicker">Let's connect</p><h2>Start a conversation.</h2><p>Reach me at <a href="mailto:{{ $user->email }}">{{ $user->email }}</a></p></div><a href="mailto:{{ $user->email }}" class="portfolio-contact-button">Say hello <span aria-hidden="true">↗</span></a></section>
        </main>
        <footer class="portfolio-footer"><span>{{ $user->name }}</span><span>Made with TraKerja Sites</span><a href="#portfolio-main">Back to top ↑</a></footer>
    </div>
</body>
</html>
