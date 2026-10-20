<footer class="workspace-footer">
    <div class="workspace-footer-main">
        <div class="workspace-footer-identity">
            <a href="{{ url('/') }}" class="workspace-footer-brand" aria-label="{{ __('TraKerja home') }}"><img src="{{ asset('images/icon.png') }}" alt="" width="28" height="28"><span>TraKerja</span></a>
            <p class="workspace-footer-tagline">{{ __('Organize your search. Prepare for your next step.') }}</p>
            <div class="workspace-footer-social">
                <a href="https://instagram.com/jointrakerja" target="_blank" rel="noopener noreferrer" aria-label="Instagram" title="Instagram"><i class="ph ph-instagram-logo" aria-hidden="true"></i></a>
                <a href="mailto:trakerja@teknalogi.id" aria-label="{{ __('Email us') }}" title="trakerja@teknalogi.id"><i class="ph ph-envelope-simple" aria-hidden="true"></i></a>
                <a href="https://t.me/HubTrakerja" target="_blank" rel="noopener noreferrer" aria-label="{{ __('Telegram community') }}" title="{{ __('Telegram community') }}"><i class="ph ph-telegram-logo" aria-hidden="true"></i></a>
            </div>
        </div>
        <nav class="workspace-footer-links" aria-label="{{ __('Footer navigation') }}">
            <div class="workspace-footer-group"><h2>{{ __('Product') }}</h2><ul>
                @if(auth()->user()?->isAdmin())
                <li><a href="{{ route('admin.index') }}" wire:navigate>{{ __('Admin panel') }}</a></li>
                <li><a href="{{ route('admin.feedbacks.index') }}" wire:navigate>{{ __('Support requests') }}</a></li>
                <li><a href="{{ url('/#product') }}">{{ __('Product') }}</a></li>
                @else
                <li><a href="{{ route('tracker') }}" wire:navigate>{{ __('Applications') }}</a></li>
                <li><a href="{{ route('jobs.explore') }}" wire:navigate>{{ __('Explore Jobs') }}</a></li>
                <li><a href="{{ route('cv.builder') }}" wire:navigate>{{ __('CV Builder') }}</a></li>
                <li><a href="{{ route('ai-studio.index') }}" wire:navigate>{{ __('AI Studio') }}</a></li>
                <li><a href="{{ route('automation.index') }}" wire:navigate>{{ __('Automation & CSV') }}</a></li>
                @endif
            </ul></div>
            <div class="workspace-footer-group"><h2>TraKerja</h2><ul>
                <li><a href="{{ url('/#our-story') }}">{{ __('landing.nav.workflow') }}</a></li>
                <li><a href="{{ url('/#pricing') }}">{{ __('landing.nav.plans') }}</a></li>
                <li><a href="{{ url('/#faq') }}">{{ __('FAQ') }}</a></li>
                <li><a href="{{ route(auth()->user()?->isAdmin() ? 'admin.feedbacks.index' : 'support.index') }}" wire:navigate>{{ __('Support') }}</a></li>
            </ul></div>
            <div class="workspace-footer-group"><h2>{{ __('Legal') }}</h2><ul>
                <li><a href="{{ route('legal.privacy') }}">{{ __('Privacy policy') }}</a></li>
                <li><a href="{{ route('legal.terms') }}">{{ __('Terms of service') }}</a></li>
            </ul></div>
        </nav>
    </div>
    <div class="workspace-footer-copy"><p>© {{ now()->year }} TraKerja · PT Teknalogi Transformasi Digital. {{ __('All rights reserved.') }}</p></div>
</footer>
