<x-app-layout>
    <div class="survey-page workspace-page-frame">
        @if($hasCompleted)
            <section class="survey-complete" aria-labelledby="survey-thanks">
                <div class="survey-complete-art" aria-hidden="true"><svg viewBox="0 0 210 145" fill="none"><rect x="47" y="22" width="117" height="105" rx="17" fill="#f0e7f5" stroke="#d8c6e1" transform="rotate(-8 47 22)"/><rect x="58" y="16" width="110" height="112" rx="15" fill="white" stroke="#d8c6e1"/><path d="M82 83H132M82 96H118" stroke="#d8c6e1" stroke-width="5" stroke-linecap="round"/><circle cx="112" cy="52" r="19" fill="#f6e4ef"/><path d="M103 52L109 58L122 45" stroke="#ae568d" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M28 64L32 72L40 76L32 80L28 88L24 80L16 76L24 72Z" fill="#efc7dc"/><circle cx="185" cy="100" r="6" fill="#b69bd0"/><path d="M178 35L186 43M186 35L178 43" stroke="#cc91b2" stroke-width="2" stroke-linecap="round"/></svg></div>
                <h1 id="survey-thanks">Thanks for sharing<span>.</span></h1>
                <p class="survey-complete-lead">Your feedback has been saved.</p><p>Your answers help us understand what works and what needs more attention. You’re ready to get back to your job search.</p>
                <div class="survey-complete-actions"><a class="survey-button survey-button--primary" href="{{ route('dashboard') }}" wire:navigate>Go to Dashboard <span aria-hidden="true">→</span></a><a class="survey-button" href="{{ route('tracker') }}" wire:navigate>View applications</a></div>
            </section>
        @else
            @php
                $sections = \App\Support\SurveyQuestions::sections();
                $sectionFields = array_map(fn ($section) => array_keys($section['questions']), $sections);
                $initialSection = 0;
                foreach($sectionFields as $index => $fields) {
                    if(collect($fields)->contains(fn ($field) => $errors->has($field))) { $initialSection = $index; break; }
                }
                if($errors->has('feedback') && !$errors->hasAny(array_merge(...$sectionFields))) $initialSection = 2;
            @endphp
            <header class="survey-heading"><div><a href="{{ route('dashboard') }}" wire:navigate class="survey-back">← Back to your workspace</a><h1>Help shape TraKerja<span>.</span></h1><p>Tell us what works for you, and what could work better.</p></div><form method="POST" action="{{ route('survey.dismiss') }}">@csrf<button type="submit" class="survey-defer">Remind me in 7 days</button></form></header>
            <div class="survey-grid" x-data="surveyWorkspace(@js($sectionFields), @js($initialSection))">
                <aside class="survey-guide">
                    <div class="survey-guide-art" aria-hidden="true"><svg viewBox="0 0 230 150" fill="none"><rect x="45" y="33" width="139" height="90" rx="19" fill="#e8def3" transform="rotate(-7 45 33)"/><rect x="47" y="18" width="136" height="105" rx="17" fill="white" stroke="#d9c7e3"/><path d="M68 40H149M68 51H112" stroke="#dfd3e8" stroke-width="5" stroke-linecap="round"/><rect x="68" y="73" width="23" height="25" rx="7" fill="#f5e7ee"/><rect x="97" y="73" width="23" height="25" rx="7" fill="#eddbeb"/><rect x="126" y="73" width="34" height="25" rx="7" fill="#bc6c9f"/><path d="M138 85L142 89L150 80" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M192 41L196 49L204 53L196 57L192 65L188 57L180 53L188 49Z" fill="#efc7dc"/><circle cx="32" cy="97" r="7" fill="#c9b1dc"/></svg></div>
                    <h2>Your experience matters.</h2><p>Your survey is optional. You can keep using your workspace whether you complete it now or later.</p>
                    <div class="survey-progress" x-cloak><div><span>Questions answered</span><strong><span x-text="answered">0</span> / 12</strong></div><div class="survey-progress-track" role="progressbar" aria-label="Survey questions answered" aria-valuemin="0" aria-valuemax="12" :aria-valuenow="answered"><span :style="{ width: (answered / 12 * 100) + '%' }"></span></div></div>
                    <p class="survey-guide-note">Each rating uses a scale from 1 to 5. Written feedback is optional.</p>
                </aside>
                <form class="survey-form" x-ref="form" method="POST" action="{{ route('survey.submit') }}" :novalidate="true" @change="sync()" @reset="reset()" @submit="submit($event)">
                    @csrf
                    @if($errors->any())<div class="survey-errors" role="alert"><strong>Check your answers</strong><p>Your previous answers are still here. Review the highlighted questions.</p><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
                    <nav class="survey-steps" aria-label="Survey sections" x-cloak>@foreach($sections as $index => $section)<button type="button" @click="go({{ $index }})" :class="{ 'is-active': step === {{ $index }} }" :aria-current="step === {{ $index }} ? 'step' : null"><span>{{ $index + 1 }}</span><strong>{{ ['Experience', 'Tools', 'Next steps'][$index] }}</strong></button>@endforeach</nav>
                    @foreach($sections as $index => $section)
                        <section class="survey-section" x-show="step === {{ $index }}" aria-labelledby="survey-section-{{ $index }}">
                            <div class="survey-section-heading" tabindex="-1" data-survey-heading="{{ $index }}"><span>Section {{ $index + 1 }} of 3</span><h2 id="survey-section-{{ $index }}">{{ $section['title'] }}</h2><p>{{ $section['description'] }}</p></div>
                            <div class="survey-questions">
                                @foreach($section['questions'] as $key => $question)
                                    <fieldset class="survey-question {{ $errors->has($key) ? 'has-error' : '' }}"><legend>{{ $question[0] }} <span class="sr-only">(required)</span></legend><p id="{{ $key }}-description">{{ $question[1] }}</p>
                                        <div class="survey-rating" aria-describedby="{{ $key }}-description">@for($rating = 1; $rating <= 5; $rating++)<label><input type="radio" name="{{ $key }}" value="{{ $rating }}" required @checked(old($key) == $rating) aria-label="{{ $rating }} out of 5{{ $rating === 1 ? ': '.$question[2] : ($rating === 5 ? ': '.$question[3] : '') }}" aria-describedby="{{ $key }}-description{{ $errors->has($key) ? ' '.$key.'-error' : '' }}" @if($errors->has($key)) aria-invalid="true" @endif><span>{{ $rating }}</span></label>@endfor</div>
                                        <div class="survey-scale"><span>{{ $question[2] }}</span><span>{{ $question[3] }}</span></div>
                                        @error($key)<p class="survey-field-error" id="{{ $key }}-error">{{ $message }}</p>@enderror
                                    </fieldset>
                                @endforeach
                            </div>
                            @if($index === 2)
                                <div class="survey-feedback"><label for="survey-feedback">Anything else we should know? <span>Optional</span></label><p id="survey-feedback-hint">An idea, something frustrating, or a detail you’d like us to improve.</p><textarea id="survey-feedback" name="feedback" rows="4" maxlength="1000" x-ref="feedback" @input="feedbackLength = $event.target.value.length" placeholder="Tell us in your own words…" aria-describedby="survey-feedback-hint{{ $errors->has('feedback') ? ' feedback-error' : '' }}">{{ old('feedback') }}</textarea><span class="survey-character-count" x-cloak><span x-text="feedbackLength">0</span> / 1,000</span>@error('feedback')<p id="feedback-error" class="survey-field-error">{{ $message }}</p>@enderror</div>
                            @endif
                        </section>
                    @endforeach
                    <p class="survey-client-error" x-cloak x-show="error" x-text="error" role="alert"></p>
                    <footer class="survey-form-footer"><button class="survey-button" type="button" x-cloak x-show="step > 0" @click="go(step - 1)">← Back</button><span class="survey-form-footnote">12 ratings · optional written feedback</span><button class="survey-button survey-button--primary" type="button" x-cloak x-show="step < 2" @click="next()">Continue <span aria-hidden="true">→</span></button><button class="survey-button survey-button--primary" type="submit" x-show="step === 2" :disabled="submitting"><span x-text="submitting ? 'Sending…' : 'Send feedback'">Send feedback</span><span aria-hidden="true">→</span></button></footer>
                </form>
            </div>
        @endif
    </div>
</x-app-layout>
