@if(request()->attributes->get('showSurveyInvitation', false))
    <div class="survey-invitation-frame workspace-page-frame"><aside data-survey-invitation aria-label="{{ __('Optional experience survey') }}" class="survey-invitation">
        <div><strong>{{ __('Help shape TraKerja.') }}</strong><p>{{ __('Share your experience whenever you’re ready.') }}</p></div>
        <div class="survey-invitation-actions"><a href="{{ route('survey.show') }}" wire:navigate class="survey-button survey-button--primary">{{ __('Share feedback') }}</a><form method="POST" action="{{ route('survey.dismiss') }}">@csrf<button type="submit" class="survey-button">{{ __('Remind me in 7 days') }}</button></form></div>
    </aside></div>
@endif
