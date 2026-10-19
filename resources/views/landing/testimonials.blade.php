@php
    $reviews = [
        ['name' => 'Bintang Syafrian Rizal', 'role' => 'Data Analyst at CIMB Niaga', 'image' => 'review-bintang.webp', 'portrait_top' => true, 'quote' => '"TraKerja transformed my chaotic job application spreadsheet into a super clean, visual Kanban tracker. It\'s an absolute game-changer for organizing my career search!"'],
        ['name' => 'Aretha Khansa', 'role' => 'IT Project Management Officer', 'company' => 'PT Asuransi Kredit Indonesia (Askrindo)', 'image' => 'review-aretha.webp', 'portrait_top' => true, 'quote' => '"The dashboard is incredibly clean and intuitive. Having real-time stage progression metrics and automated AI insights made my entire application process transparent."'],
        ['name' => 'Al Ferro Putra', 'role' => 'App Developer at BRI', 'image' => 'review-al-ferro.webp', 'portrait_top' => true, 'quote' => '"The Chrome Extension is spectacular. Saving jobs from LinkedIn directly into my Kanban board in one click saves me hours of manual data entry every single week."'],
    ];
@endphp
<section class="lp-reviews lp-wrap lp-section" id="testimonials" data-review-carousel aria-roledescription="carousel" aria-label="{{ __('landing.testimonials.nav') }}" data-reveal>
    <div class="lp-reviews-heading"><p class="lp-eyebrow">{{ __('landing.testimonials.eyebrow') }}</p>@include('landing.heading', ['copy' => 'testimonials.title'])</div>
    <div class="lp-review-stage"><div class="lp-review-grid">
        @foreach($reviews as $review)
            <article class="lp-review-card" data-review-card data-position="{{ ['before','center','after'][$loop->index] }}" aria-hidden="{{ $loop->index === 1 ? 'false' : 'true' }}">
                <div @class(['lp-review-photo', 'lp-review-photo--top' => $review['portrait_top'] ?? false])><img draggable="false" src="{{ asset('images/landing/'.$review['image']) }}" width="600" height="800" alt="{{ $review['name'] }}" loading="lazy" decoding="async"></div>
                <div class="lp-review-content"><div class="lp-review-author"><img draggable="false" src="{{ asset('images/landing/'.$review['image']) }}" width="42" height="42" alt="" loading="lazy"><div><h3>{{ $review['name'] }}</h3><p>{{ __($review['role']) }}@if(isset($review['company']))<span class="lp-review-company">{{ ' at '.$review['company'] }}</span>@endif</p></div></div><blockquote>{{ __($review['quote']) }}</blockquote><div class="lp-review-footer"><span class="lp-review-signature"><img draggable="false" src="{{ asset('images/icon.png') }}" width="19" height="19" alt="">TraKerja</span><span class="lp-review-rating" role="img" aria-label="{{ __('landing.testimonials.rating') }}">@for($i = 0; $i < 5; $i++)@include('landing.icon', ['name' => 'star'])@endfor</span></div></div>
            </article>
        @endforeach
    </div>
    <div class="lp-review-nav"><button type="button" data-review-previous aria-label="{{ __('landing.testimonials.previous') }}">@include('landing.icon', ['name' => 'arrow'])</button><span data-review-count role="status" aria-live="polite" data-count-label="{{ __('landing.testimonials.position', ['current' => ':current', 'total' => ':total']) }}">{{ __('landing.testimonials.position', ['current' => 2, 'total' => 3]) }}</span><button type="button" data-review-next aria-label="{{ __('landing.testimonials.next') }}">@include('landing.icon', ['name' => 'arrow'])</button></div></div>
</section>
