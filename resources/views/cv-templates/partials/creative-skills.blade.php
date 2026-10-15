@php use App\Support\CvDocument; @endphp
<section class="section creative-skills">
    <h2 class="section-title"><span class="section-number">{{ str_pad($sectionNumber, 2, '0', STR_PAD_LEFT) }}</span>Skills</h2>
    @foreach($skills->groupBy('category') as $category => $categorySkills)
        <p class="creative-skill-group">@if(filled($category))<strong>{!! CvDocument::text($category) !!}:</strong> @endif @foreach($categorySkills as $skill)<span class="creative-skill">{!! CvDocument::text($skill->skill_name) !!}</span>@if(!$loop->last), @endif @endforeach</p>
    @endforeach
</section>
