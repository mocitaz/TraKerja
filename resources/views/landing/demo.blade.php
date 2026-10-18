<section class="lp-product lp-wrap lp-section" id="product" data-demo-player data-reveal>
    <div class="lp-section-top">
        <div><p class="lp-eyebrow">{{ __('landing.demo.eyebrow') }}</p>@include('landing.heading', ['copy' => 'demo.title'])</div>
        <p class="lp-section-intro">{{ __('landing.demo.intro') }}</p>
    </div>
    <div class="lp-product-toolbar">
        <div class="lp-tabs" role="tablist" aria-label="{{ __('landing.nav.product') }}" data-tabs="product">
            @foreach(['tracker', 'calendar', 'ai'] as $tab)
                <button type="button" role="tab" id="product-tab-{{ $tab }}" aria-controls="product-panel-{{ $tab }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}" tabindex="{{ $loop->first ? '0' : '-1' }}" data-tab="{{ $tab }}">{{ __('landing.demo.'.$tab) }}</button>
            @endforeach
        </div>
        <button type="button" class="lp-text-button lp-demo-play" data-play aria-label="{{ __('landing.demo.play') }}" data-label-play="{{ __('landing.demo.play') }}" data-label-pause="{{ __('landing.demo.pause') }}" data-label-replay="{{ __('landing.demo.replay') }}">@include('landing.icon', ['name' => 'play'])<span>{{ __('landing.demo.play') }}</span></button>
    </div>
    <div class="lp-demo-window" data-demo-step="0">
        <div class="lp-demo-topbar"><span class="lp-demo-brand"><img draggable="false" src="{{ asset('images/icon.png') }}" width="22" height="22" alt="">TraKerja</span><span class="lp-demo-sample">{{ __('landing.demo.sample') }}</span></div>
        <div id="product-panel-tracker" class="lp-demo-panel" role="tabpanel" aria-labelledby="product-tab-tracker" tabindex="0" data-panel="tracker">
            <div class="lp-preview-title"><h3>{{ __('landing.demo.applications') }}</h3><span>03</span></div>
            <div class="lp-preview-table">
                <div class="lp-preview-row lp-preview-row--head"><span>{{ __('landing.demo.opportunity') }}</span><span>{{ __('landing.demo.stage') }}</span><span>{{ __('landing.demo.next') }}</span></div>
                @foreach(['applied', 'interview', 'assessment'] as $stage)
                    <div class="lp-preview-row" data-example-row="{{ $loop->index }}">
                        <div class="lp-preview-opportunity"><img draggable="false" src="{{ asset('images/roles/'.(['creative', 'frontend', 'data'][$loop->index]).'.svg') }}" width="44" height="44" alt=""><div><strong>{{ __('landing.demo.roles')[$loop->index] }}</strong><span>{{ __('landing.demo.company') }} {{ ['A', 'B', 'C'][$loop->index] }}</span></div></div>
                        <span class="lp-stage lp-stage--{{ $stage }}">{{ __('landing.demo.'.$stage) }}</span>
                        <span class="lp-preview-next">{{ __('landing.demo.'.(['check', 'prepare', 'review'][$loop->index])) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        <div id="product-panel-calendar" class="lp-demo-panel" role="tabpanel" aria-labelledby="product-tab-calendar" tabindex="0" data-panel="calendar" hidden>
            <div class="lp-preview-title"><h3>{{ __('landing.demo.up_next') }}</h3><span>{{ __('landing.demo.month') }}</span></div>
            <div class="lp-agenda-preview"><div class="lp-agenda-date"><span>{{ __('landing.demo.day') }}</span><strong>19</strong><span>{{ __('landing.demo.month') }}</span></div><div class="lp-agenda-event"><span class="lp-stage lp-stage--interview">{{ __('landing.demo.schedule') }}</span><h4>Product Designer</h4><p>{{ __('landing.demo.company') }} A</p><strong class="lp-agenda-time">{{ __('landing.demo.time') }}</strong><span>{{ __('landing.demo.online') }}</span></div><div class="lp-agenda-aside">@include('landing.icon', ['name' => 'calendar'])<p>{{ __('landing.demo.agenda_note') }}</p></div></div>
        </div>
        <div id="product-panel-ai" class="lp-demo-panel" role="tabpanel" aria-labelledby="product-tab-ai" tabindex="0" data-panel="ai" hidden>
            <div class="lp-preview-title"><h3>AI Studio</h3><span>{{ __('landing.demo.no_request') }}</span></div>
            <div class="lp-demo-conversation"><p class="lp-chat-question">{{ __('landing.demo.ai_prompt') }}</p><div class="lp-chat-answer"><img draggable="false" src="{{ asset('images/brand/nara-avatar.svg') }}" width="36" height="36" alt=""><div><strong>Nara</strong><p>{{ __('landing.demo.ai_answer') }}</p><blockquote>{{ __('landing.demo.ai_followup') }}</blockquote></div></div><small>{{ __('landing.demo.ai_note') }}</small></div>
        </div>
        <div class="lp-demo-progress" aria-label="{{ __('landing.nav.workflow') }}">@foreach(__('landing.demo.steps') as $step)<button type="button" data-step="{{ $loop->index }}" aria-current="{{ $loop->first ? 'step' : 'false' }}"><span class="lp-progress-track" aria-hidden="true"></span><span>{{ $step }}</span></button>@endforeach</div>
    </div>
    <div class="lp-demo-description" role="status" aria-live="polite">@foreach(__('landing.demo.step_copy') as $text)<span data-step-copy="{{ $loop->index }}" @if(!$loop->first) hidden @endif>{{ $text }}</span>@endforeach</div>
</section>
