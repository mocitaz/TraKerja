<x-admin-layout>
@php
    $audiences = [
        'all' => ['All members', 'Every non-admin account', 'users'],
        'verified' => ['Verified', 'Confirmed email addresses', 'shield-check'],
        'unverified' => ['Unverified', 'Waiting for email verification', 'envelope-simple'],
        'premium' => ['Premium', 'Active paid membership', 'credit-card'],
        'free' => ['Free', 'Without active premium access', 'user'],
        'new' => ['New members', 'Registered in the last 7 days', 'user-plus'],
    ];
    $steps = ['Audience', 'Message', 'Language', 'Banner', 'Review'];
    $headings = ['Choose your audience', 'Prepare your message', 'Choose the email language', 'Would you like a banner?', 'Review your campaign'];
    $descriptions = ['Start with the members you want to reach.', 'Use a ready-made template, write your own, or create one with Gemini.', 'This controls the email, independently of your admin language.', 'Keep it text-only or add one of the TraKerja banners.', 'Preview the final email, then check the recipients before sending.'];
    $workspace = [
        'previewUrl' => route('admin.email-blast.preview'), 'initUrl' => route('admin.email-blast.init'),
        'sendUrl' => route('admin.email-blast.send-single'), 'logUrl' => route('admin.email-blast.store-log'),
        'generateUrl' => route('admin.email-blast.templates.generate'), 'draftPreviewUrl' => route('admin.email-blast.templates.preview'),
        'saveTemplateUrl' => route('admin.email-blast.templates.store'), 'savedTemplates' => $savedTemplates, 'counts' => $audienceCounts,
        'banners' => collect(\App\Support\EmailBannerCatalog::all())->map(fn ($item) => __($item['label']))->all(),
        'audienceLabels' => collect($audiences)->map(fn ($item) => __($item[0]))->all(),
        'templateLabels' => collect($templates)->flatMap(fn ($options) => collect($options)->map(fn ($label) => __($label)))->all(),
    ];
@endphp
<div class="ec-workspace" x-data="emailBlastWorkspace(@js($workspace))">
    <header class="am-page-heading"><div><h1>{{ __('Email Blast') }}</h1><p>{{ __('A thoughtful message, one step at a time.') }}</p></div><a href="{{ route('admin.email-blast.history') }}" class="am-button"><i class="ph ph-clock-counter-clockwise" aria-hidden="true"></i>{{ __('Campaign history') }}</a></header>
    <nav class="ec-steps" aria-label="{{ __('Campaign steps') }}">
        @foreach($steps as $label)<button type="button" @click="goStep({{ $loop->iteration }})" :disabled="step < {{ $loop->iteration }} || loading || sending" :aria-current="step === {{ $loop->iteration }} ? 'step' : null" :class="{ 'is-current': step === {{ $loop->iteration }}, 'is-complete': completed >= {{ $loop->iteration }} }"><span><i class="ph ph-check" x-show="completed >= {{ $loop->iteration }}" aria-hidden="true"></i><b x-show="completed < {{ $loop->iteration }}">{{ $loop->iteration }}</b></span><strong>{{ __($label) }}</strong></button>@endforeach
    </nav>
    <div class="ec-layout">
        <section class="ec-stage">
            <header class="ec-stage-heading"><span class="ec-step-count" x-text="step + ' / 5'"></span><h2 x-ref="stepHeading" tabindex="-1" x-text="@js(array_map(fn ($key) => __($key), $headings))[step - 1]"></h2><p x-text="@js(array_map(fn ($key) => __($key), $descriptions))[step - 1]"></p></header>
            <form x-ref="composer" @submit.prevent="advance()" class="ec-form">
                <fieldset x-show="step === 1" :disabled="step !== 1" class="ec-step-content"><legend class="sr-only">{{ __('Choose your audience') }}</legend><div class="ec-audiences">
                    @foreach($audiences as $key => [$label, $description, $icon])<label class="ec-audience" :class="{ 'is-selected': audience === '{{ $key }}' }"><input type="radio" value="{{ $key }}" x-model="audience" name="target_user"><i class="ph ph-{{ $icon }}" aria-hidden="true"></i><span><strong>{{ __($label) }}</strong><small>{{ __($description) }}</small></span><b>{{ number_format($audienceCounts[$key]) }}</b></label>@endforeach
                </div><p class="ec-note"><i class="ph ph-info" aria-hidden="true"></i>{{ __('Administrators are excluded. The final recipient list is checked at preview.') }}</p></fieldset>
                <fieldset x-show="step === 2" :disabled="step !== 2" class="ec-step-content"><legend class="sr-only">{{ __('Prepare your message') }}</legend>
                    <div class="ec-source" role="group" aria-label="{{ __('Message source') }}">@foreach(['existing' => 'Ready-made', 'saved' => 'Your templates', 'custom' => 'Write your own'] as $source => $label)<button type="button" @click="chooseSource('{{ $source }}')" :aria-pressed="messageSource === '{{ $source }}'">{{ __($label) }}</button>@endforeach</div>
                    <div x-show="messageSource === 'existing'" class="ec-field"><label for="blast-template">{{ __('Email template') }}</label><select id="blast-template" name="email_type" x-model="emailType" :disabled="messageSource !== 'existing'">@foreach($templates as $group => $options)<optgroup label="{{ __($group) }}">@foreach($options as $key => $label)@if($key !== 'custom')<option value="{{ $key }}">{{ __($label) }}</option>@endif@endforeach</optgroup>@endforeach</select><p class="ec-note" x-text="templateDescription()"></p></div>
                    <div x-show="messageSource === 'saved'" class="ec-field"><label for="saved-template">{{ __('Verified templates') }} <span class="am-count" x-text="savedTemplates.length"></span></label><select id="saved-template" x-model="savedId" @change="chooseSaved()"><option value="">{{ __('Choose a saved template') }}</option><template x-for="item in savedTemplates" :key="item.id"><option :value="item.id" x-text="item.name + ' · ' + item.language.toUpperCase()"></option></template></select><p class="ec-note" x-show="!savedTemplates.length">{{ __('No saved templates yet. Create and review your first draft with Gemini.') }}</p></div>
                    <div x-show="messageSource === 'custom' || (messageSource === 'saved' && savedId)" class="ec-custom" :inert="messageSource === 'existing' || (messageSource === 'saved' && !savedId)">
                        <div class="ec-field"><label for="custom-subject">{{ __('Subject') }}</label><input id="custom-subject" x-model="custom.subject" maxlength="255" :required="step === 2 && emailType === 'custom'" :disabled="emailType !== 'custom'"></div>
                        <div class="ec-field"><label for="custom-content">{{ __('Message body') }}</label><textarea id="custom-content" x-model="custom.content" rows="8" maxlength="5000" :required="step === 2 && emailType === 'custom'" :disabled="emailType !== 'custom'"></textarea><p class="ec-note">{{ __('The recipient greeting, TraKerja signoff, and footer are added automatically.') }}</p></div>
                        <div class="ec-field-pair"><div class="ec-field"><label for="custom-button">{{ __('Button text (optional)') }}</label><input id="custom-button" x-model="custom.buttonText" maxlength="100" :disabled="emailType !== 'custom'"></div><div class="ec-field"><label for="custom-url">{{ __('Button URL (optional)') }}</label><input id="custom-url" x-model="custom.buttonUrl" type="url" maxlength="500" placeholder="https://" :disabled="emailType !== 'custom'"></div></div>
                        <p class="ec-note" x-show="messageSource === 'saved'">{{ __('Edits apply to this campaign. Your saved template stays unchanged.') }}</p>
                    </div>
                    <div class="ec-gemini-entry"><span class="ec-tool-icon"><i class="ph ph-pencil-line" aria-hidden="true"></i></span><div><strong>{{ __('Have an idea? Draft it with Gemini.') }}</strong><p>{{ __('Describe the message. Edit the result, preview it, then verify and save.') }}</p></div><button type="button" @click="studio = !studio" class="am-button" :aria-expanded="studio.toString()">{{ __('Create a template') }}</button></div>
                    <p class="ec-success" role="status" x-show="studioNotice" x-text="studioNotice"></p>
                </fieldset>
                <fieldset x-show="step === 3" :disabled="step !== 3" class="ec-step-content"><legend class="sr-only">{{ __('Choose the email language') }}</legend><div class="ec-choice-grid">@foreach(['id' => ['Bahasa Indonesia', 'Hangat, ringan, dan dekat.'], 'en' => ['English', 'Friendly, clear, and conversational.']] as $code => [$label, $copy])<label class="ec-choice" :class="{ 'is-selected': language === '{{ $code }}' }"><input type="radio" name="email_language" value="{{ $code }}" x-model="language" :disabled="messageSource === 'saved'"><strong>{{ $label }}</strong><span>{{ $copy }}</span></label>@endforeach</div><p class="ec-note" x-show="emailType === 'custom'">{{ __('Custom copy is kept exactly as written. This language sets the greeting and footer; it does not translate your message.') }}</p><p class="ec-note" x-show="messageSource === 'saved'">{{ __('Saved templates use the language they were reviewed in.') }}</p></fieldset>
                <fieldset x-show="step === 4" :disabled="step !== 4" class="ec-step-content"><legend class="sr-only">{{ __('Would you like a banner?') }}</legend><div class="ec-choice-grid">@foreach(['no' => ['No banner', 'Let the words take the lead.'], 'yes' => ['Add a banner', 'Give your message a visual opening.']] as $choice => [$label, $copy])<label class="ec-choice" :class="{ 'is-selected': bannerChoice === '{{ $choice }}' }"><input type="radio" name="banner_choice" value="{{ $choice }}" x-model="bannerChoice" @change="banner = ''"><strong>{{ __($label) }}</strong><span>{{ __($copy) }}</span></label>@endforeach</div>
                    <div x-show="bannerChoice === 'yes'" class="ec-banners" role="group" aria-label="{{ __('Choose a banner') }}">@foreach(\App\Support\EmailBannerCatalog::all() as $key => $item)<label :class="{ 'is-selected': banner === '{{ $key }}' }"><input type="radio" name="banner" value="{{ $key }}" x-model="banner"><img src="{{ asset($item['path']) }}" alt="" width="600" height="200" loading="lazy"><span>{{ __($item['label']) }}</span></label>@endforeach</div>
                </fieldset>
                <section x-show="step === 5" class="ec-review"><i class="ph ph-envelope-open" aria-hidden="true"></i><h3>{{ __('One last look before it goes out.') }}</h3><p>{{ __('Open the rendered email to check the copy, language, and banner. Nothing is sent during preview.') }}</p><button type="button" @click="preview()" :disabled="loading || sending" class="am-button am-button--primary"><i class="ph" :class="loading ? 'ph-spinner ph-spin' : 'ph-eye'" aria-hidden="true"></i><span x-text="loading ? @js(__('Preparing preview…')) : @js(__('Preview email'))"></span></button></section>
                <p class="ec-error" role="alert" x-show="error" x-text="error"></p>
                <footer class="ec-stage-actions"><button type="button" @click="goStep(step - 1)" x-show="step > 1" class="am-button">{{ __('Back') }}</button><span x-show="step === 1" class="ec-note">{{ __('Choose an audience to begin.') }}</span><button type="submit" x-show="step < 5" :disabled="studio" class="am-button am-button--primary">{{ __('Continue') }}<i class="ph ph-arrow-right" aria-hidden="true"></i></button></footer>
            </form>
            @include('admin.partials.email-template-studio')
        </section>
        <aside class="ec-summary" aria-labelledby="campaign-summary"><h2 id="campaign-summary">{{ __('Your campaign') }}</h2><dl><div><dt>{{ __('Audience') }}</dt><dd x-text="audienceLabel()"></dd><small x-show="audience" x-text="audienceCount() + ' ' + @js(__('eligible accounts'))"></small></div><div><dt>{{ __('Message') }}</dt><dd x-text="completed >= 2 ? templateLabel() : @js(__('Not selected'))"></dd></div><div><dt>{{ __('Language') }}</dt><dd x-text="language ? (language === 'id' ? 'Bahasa Indonesia' : 'English') : @js(__('Not selected'))"></dd></div><div><dt>{{ __('Banner') }}</dt><dd x-text="bannerChoice === 'no' ? @js(__('No banner')) : (banner ? bannerLabel() : @js(__('Not selected')))"></dd></div></dl><p><i class="ph ph-envelope-simple" aria-hidden="true"></i>{{ __('You decide when to send. Preview and recipient review come first.') }}</p></aside>
    </div>
    @include('admin.partials.email-delivery-dialog')
</div>
</x-admin-layout>
