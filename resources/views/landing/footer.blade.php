<footer class="lp-footer" data-reveal>
    @include('landing.footer-landscape')
    <div class="lp-footer-inner lp-wrap">
        <div class="lp-footer-top">
            <div class="lp-footer-contact"><p>{{ __('landing.footer.contact_intro') }}</p><a href="mailto:trakerja@teknalogi.id">trakerja@teknalogi.id @include('landing.icon', ['name' => 'arrow'])</a></div>
            <nav class="lp-footer-nav" aria-label="{{ __('landing.footer.navigation') }}">
                <a href="#our-story">{{ __('landing.nav.workflow') }}</a><a href="#product">{{ __('landing.nav.product') }}</a><a href="#pricing">{{ __('landing.nav.plans') }}</a><a href="#faq">{{ __('landing.nav.faq') }}</a>
            </nav>
        </div>
        <a class="lp-footer-wordmark" data-footer-wordmark href="#top" aria-label="{{ __('landing.footer.top') }}"><img draggable="false" src="{{ asset('images/icon.png') }}" width="200" height="200" alt="" loading="lazy"><span class="lp-footer-name" aria-hidden="true">@foreach(str_split('TraKerja') as $letter)<span class="lp-footer-letter">{{ $letter }}</span>@endforeach</span></a>
        <div class="lp-footer-bottom">
            <span>{{ __('landing.footer.copyright', ['year' => now()->year]) }}</span>
            <nav class="lp-footer-legal" aria-label="{{ __('landing.footer.legal') }}"><a href="{{ route('legal.privacy') }}">{{ __('landing.footer.privacy') }}</a><a href="{{ route('legal.terms') }}">{{ __('landing.footer.terms') }}</a></nav>
            <div class="lp-footer-social"><a href="https://instagram.com/jointrakerja" target="_blank" rel="noopener noreferrer">Instagram</a><a href="https://t.me/HubTrakerja" target="_blank" rel="noopener noreferrer">Telegram</a></div>
        </div>
    </div>
</footer>
