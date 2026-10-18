<div class="lp-people lp-wrap" aria-hidden="true" data-reveal>
    <div class="lp-people-collage">
        @for($person = 0; $person < 8; $person++)
            <div class="lp-person lp-person--{{ $person + 1 }}"><img draggable="false" src="{{ asset('images/landing/indonesian-portraits.webp') }}" width="1774" height="887" alt="" loading="lazy" decoding="async" style="--portrait-column:{{ $person % 4 }};--portrait-row:{{ intdiv($person, 4) }}"></div>
        @endfor
    </div>
</div>
