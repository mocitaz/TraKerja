@php
    $toolLabels = ['analyzer' => __('Analyze CV'), 'cover-letter' => __('Cover letter'), 'photo' => __('Photo studio'), 'outreach' => __('Recruiter outreach')];
    $toolIcons = ['analyzer' => 'ph-file-magnifying-glass', 'cover-letter' => 'ph-envelope-simple-open', 'photo' => 'ph-user-focus', 'outreach' => 'ph-paper-plane-tilt'];
@endphp
<div class="aiw-composer-wrap" x-ref="composer">
    @if($errors->any())<div role="alert" class="aiw-alert"><strong>{{ __('Please check your request.') }}</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <div role="alert" class="aiw-alert" x-show="error" x-cloak x-text="error"></div>
    <form method="POST" enctype="multipart/form-data" :action="endpoints[task]" @submit="submit($event)" x-ref="form" class="aiw-composer" :class="{ 'is-busy': busy }">
        @csrf
        <input type="hidden" name="_studio_task" :value="task">
        <input type="hidden" name="request_key" :value="requestKey">
        <input type="hidden" name="application_id" :value="applicationId" :disabled="task === 'photo' || !applicationId">
        <fieldset :disabled="busy" class="aiw-fields">
            <legend class="sr-only">{{ __('Request details') }}</legend>

            {{-- Context: tool, application, attachment --}}
            <div class="aiw-context">
                <div class="aiw-menu" @click.outside="menu = menu === 'tool' ? null : menu">
                    <button type="button" class="aiw-tool-chip" @click="menu = menu === 'tool' ? null : 'tool'" :aria-expanded="(menu === 'tool').toString()" aria-haspopup="true">
                        @foreach($toolLabels as $value => $label)<span x-show="task === @js($value)" @if($value !== $task) x-cloak @endif><i class="ph {{ $toolIcons[$value] }}" aria-hidden="true"></i>{{ $label }}</span>@endforeach
                        <i class="ph ph-caret-down" aria-hidden="true"></i>
                    </button>
                    <div class="aiw-popover" x-show="menu === 'tool'" x-cloak x-transition.origin.top.left role="menu">
                        @foreach($toolLabels as $value => $label)
                            <button type="button" role="menuitemradio" :aria-checked="(task === @js($value)).toString()" @click="choose(@js($value)); menu = null; focusComposer()"><i class="ph {{ $toolIcons[$value] }}" aria-hidden="true"></i>{{ $label }}<i class="ph ph-check aiw-check" x-show="task === @js($value)" aria-hidden="true"></i></button>
                        @endforeach
                    </div>
                </div>

                <template x-if="task !== 'photo' && applications.length">
                    <div class="aiw-menu" @click.outside="menu = menu === 'application' ? null : menu">
                        <button type="button" class="aiw-ctx-chip" :class="{ 'is-set': applicationId }" @click="menu = menu === 'application' ? null : 'application'" :aria-expanded="(menu === 'application').toString()">
                            <i class="ph ph-briefcase" aria-hidden="true"></i>
                            <span x-text="applicationLabel || @js(__('Use an application'))"></span>
                        </button>
                        <button type="button" class="aiw-chip-clear" x-show="applicationId" @click="clearApplication()" aria-label="{{ __('Remove application') }}"><i class="ph ph-x" aria-hidden="true"></i></button>
                        <div class="aiw-popover aiw-popover--list" x-show="menu === 'application'" x-cloak x-transition.origin.top.left role="menu">
                            <p class="aiw-popover-hint">{{ __('Fills the company and role from your tracker.') }}</p>
                            <template x-for="item in applications" :key="item.id">
                                <button type="button" role="menuitem" @click="useApplication(item.id); menu = null"><i class="ph ph-briefcase" aria-hidden="true"></i><span x-text="`${item.position} · ${item.company_name}`"></span></button>
                            </template>
                        </div>
                    </div>
                </template>

                <span class="aiw-ctx-chip is-set" x-show="attachmentLabel" x-cloak><i class="ph" :class="task === 'photo' ? 'ph-image' : 'ph-file-pdf'" aria-hidden="true"></i><span x-text="attachmentLabel"></span><button type="button" class="aiw-chip-clear" @click="removeAttachment()" aria-label="{{ __('Remove attachment') }}"><i class="ph ph-x" aria-hidden="true"></i></button></span>
            </div>

            {{-- Company and role (needed for letters and messages) --}}
            <div class="aiw-inline-fields" x-show="task === 'cover-letter' || task === 'outreach'" x-cloak>
                <label><span class="sr-only">{{ __('Company') }}</span><input id="studio-company" name="company_name" x-model="values.company_name" @input="applicationId = ''" :required="task === 'cover-letter' || task === 'outreach'" :disabled="task === 'photo' || task === 'analyzer'" maxlength="255" placeholder="{{ __('Company') }}" autocomplete="organization"></label>
                <label><span class="sr-only">{{ __('Role') }}</span><input id="studio-role" name="job_title" x-model="values.job_title" @input="applicationId = ''" :required="task === 'cover-letter' || task === 'outreach'" :disabled="task === 'photo' || task === 'analyzer'" maxlength="255" placeholder="{{ __('Role you’re applying for') }}"></label>
            </div>

            {{-- The main box --}}
            <label class="sr-only" for="studio-description">{{ __('Job description') }}</label>
            <textarea id="studio-description" x-ref="description" name="job_description" x-model="values.job_description" x-show="task !== 'photo'" @input="grow($el)" @keydown.enter="if (($event.metaKey || $event.ctrlKey) && !busy) { $event.preventDefault(); $refs.form.requestSubmit(); }"
                      :disabled="task === 'photo'" :required="task === 'analyzer' || task === 'cover-letter'" :minlength="task === 'outreach' ? 0 : 50" maxlength="10000" rows="3"
                      :placeholder="taskInfo.placeholder"></textarea>
            <button type="button" class="aiw-dropzone" x-show="task === 'photo'" x-cloak @click="$refs.file.click()">
                <i class="ph ph-user-focus" aria-hidden="true"></i>
                <strong x-text="fileName || @js(__('Choose a clear portrait'))"></strong>
                <span>{{ __('JPG, PNG, or WEBP · up to 20 MB') }}</span>
            </button>
            <input id="studio-file" x-ref="file" class="sr-only" type="file" :name="task === 'photo' ? 'photo' : 'resume'" :accept="task === 'photo' ? 'image/jpeg,image/png,image/webp' : 'application/pdf,.pdf'" @change="fileChanged($event, task === 'photo' ? 'image' : 'pdf')" aria-label="{{ __('Upload CV or photo') }}">
            <select name="resume_id" x-model="resumeId" class="sr-only" tabindex="-1" aria-hidden="true" :disabled="task === 'photo' || !!fileName"><option value=""></option>@foreach($savedResumes as $resume)<option value="{{ $resume->id }}">{{ $resume->title ?: $resume->file_name }}</option>@endforeach</select>

            {{-- Options --}}
            <div class="aiw-options" x-show="optionsOpen" x-cloak x-transition>
                <template x-if="task === 'cover-letter' || task === 'outreach'">
                    <label class="aiw-field"><span>{{ __('Language') }}</span><select name="language"><option value="en" @selected(app()->getLocale() === 'en')>{{ __('English') }}</option><option value="id" @selected(app()->getLocale() === 'id')>{{ __('Indonesian') }}</option></select></label>
                </template>
                <template x-if="task === 'cover-letter'"><label class="aiw-field"><span>{{ __('Tone') }}</span><select name="tone">@foreach(['professional','creative','bold','warm'] as $tone)<option value="{{ $tone }}">{{ __(ucfirst($tone)) }}</option>@endforeach</select></label></template>
                <template x-if="task === 'cover-letter'"><label class="aiw-field"><span>{{ __('Length') }}</span><select name="length"><option value="standard">{{ __('Standard') }}</option><option value="short">{{ __('Short') }}</option></select></label></template>
                <template x-if="task === 'cover-letter'"><label class="aiw-field aiw-field--wide"><span>{{ __('Highlight') }} · {{ __('Optional') }}</span><input name="highlight_focus" maxlength="1000" placeholder="{{ __('A strength or achievement') }}"></label></template>
                <template x-if="task === 'outreach'"><label class="aiw-field"><span>{{ __('Message purpose') }}</span><select name="purpose"><option value="introduction">{{ __('Introduction') }}</option><option value="follow-up">{{ __('Follow-up') }}</option><option value="thank-you">{{ __('Thank-you') }}</option></select></label></template>
                <template x-if="task === 'outreach'"><label class="aiw-field"><span>{{ __('Channel') }}</span><select name="channel"><option value="Email">{{ __('Email') }}</option><option value="LinkedIn InMail">{{ __('LinkedIn InMail') }}</option><option value="Direct Message">{{ __('Direct Message') }}</option></select></label></template>
                <template x-if="task === 'outreach'"><label class="aiw-field"><span>{{ __('Tone') }}</span><select name="tone"><option value="Professional">{{ __('Professional') }}</option><option value="Friendly">{{ __('Friendly') }}</option><option value="Persuasive">{{ __('Persuasive') }}</option></select></label></template>
                <template x-if="task === 'outreach'"><label class="aiw-field"><span>{{ __('Recruiter') }} · {{ __('Optional') }}</span><input name="recruiter_name" maxlength="100" placeholder="{{ __('Hiring team') }}"></label></template>
                <template x-if="task === 'photo'">
                    <div class="aiw-options-grid">
                        <label class="aiw-field"><span>{{ __('Process') }}</span><select name="type" x-model="photoType" @change="photoBackground = photoType === 'enhance' ? 'studio_plain' : 'transparan'"><option value="enhance">{{ __('Enhance portrait') }}</option><option value="remove_bg">{{ __('Remove background') }}</option></select></label>
                        <label class="aiw-field"><span>{{ __('Background') }}</span><select name="background" x-model="photoBackground"><template x-if="photoType === 'enhance'"><optgroup label="{{ __('Portrait backgrounds') }}"><option value="studio_plain">{{ __('Studio plain') }}</option><option value="studio_gradient">{{ __('Studio gradient') }}</option><option value="modern_office">{{ __('Modern office') }}</option><option value="meeting_room">{{ __('Meeting room') }}</option></optgroup></template><template x-if="photoType === 'remove_bg'"><optgroup label="{{ __('Background removal') }}"><option value="transparan">{{ __('Transparent') }}</option><option value="putih">{{ __('White') }}</option><option value="merah">{{ __('Red') }}</option><option value="biru">{{ __('Blue') }}</option></optgroup></template></select></label>
                        <label class="aiw-field" x-show="photoType === 'enhance'"><span>{{ __('Style') }}</span><select name="style"><option value="auto">{{ __('Auto') }}</option><option value="linkedin_pria">{{ __('LinkedIn suit') }}</option><option value="linkedin_wanita">{{ __('LinkedIn blazer') }}</option><option value="rapi_formal">{{ __('Formal suit & tie') }}</option></select></label>
                        <label class="aiw-field"><span>{{ __('Size') }}</span><select name="size"><option value="original">{{ __('Original ratio') }}</option><option value="3x4">3 × 4</option><option value="4x6">4 × 6</option><option value="2x3">2 × 3</option></select></label>
                        <input type="hidden" name="mode" value="portrait">
                    </div>
                </template>
                <p class="aiw-options-empty" x-show="task === 'analyzer'">{{ __('The review is written in the language of your TraKerja settings.') }}</p>
            </div>

            {{-- Bar --}}
            <div class="aiw-bar">
                <div class="aiw-bar-left">
                    <div class="aiw-menu" @click.outside="menu = menu === 'attach' ? null : menu" x-show="task !== 'photo'">
                        <button type="button" class="aiw-icon-btn" @click="menu = menu === 'attach' ? null : 'attach'" :aria-expanded="(menu === 'attach').toString()" aria-label="{{ __('Attach your CV') }}" title="{{ __('Attach your CV') }}"><i class="ph ph-paperclip" aria-hidden="true"></i></button>
                        <div class="aiw-popover aiw-popover--up aiw-popover--list" x-show="menu === 'attach'" x-cloak x-transition.origin.bottom.left role="menu">
                            <button type="button" role="menuitem" @click="$refs.file.click(); menu = null"><i class="ph ph-upload-simple" aria-hidden="true"></i>{{ __('Upload PDF') }} <small>{{ __('PDF up to 10 MB.') }}</small></button>
                            @if($savedResumes->isNotEmpty())<p class="aiw-popover-hint">{{ __('Your saved CVs') }}</p>@endif
                            @foreach($savedResumes as $resume)
                                <button type="button" role="menuitemradio" :aria-checked="(String(resumeId) === @js((string) $resume->id)).toString()" @click="chooseResume(@js((string) $resume->id)); menu = null"><i class="ph ph-file-pdf" aria-hidden="true"></i><span>{{ $resume->title ?: $resume->file_name }}</span></button>
                            @endforeach
                            <a href="{{ route('cv.builder') }}" role="menuitem"><i class="ph ph-identification-card" aria-hidden="true"></i>{{ __('Update CV profile') }}</a>
                        </div>
                    </div>
                    <button type="button" class="aiw-pill" @click="optionsOpen = !optionsOpen" :aria-expanded="optionsOpen.toString()"><i class="ph ph-sliders-horizontal" aria-hidden="true"></i>{{ __('Options') }}</button>
                    <span class="aiw-count" x-show="task !== 'photo' && values.job_description.length > 0" x-text="`${values.job_description.length.toLocaleString()} / 10.000`"></span>
                </div>
                <div class="aiw-bar-right">
                    <span class="aiw-cost">
                        @foreach($balances as $value => $balance)<span x-show="task === @js($value)" @if($value !== $task) x-cloak @endif>{{ $balance === 'unlimited' ? __('No credit charged') : __('1 credit · :count left', ['count' => number_format($balance)]) }}</span>@endforeach
                        <span x-show="task === 'outreach'" @if($task !== 'outreach') x-cloak @endif>{{ __('No credit required') }}</span>
                    </span>
                    <button type="submit" class="aiw-send" :disabled="busy || !access[task]" :aria-label="taskInfo.action">
                        <i class="ph-bold ph-arrow-up" x-show="!busy" aria-hidden="true"></i><span class="aiw-spinner" x-show="busy" x-cloak aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </fieldset>
    </form>
    <p class="aiw-hint">{{ __('Requests and results are saved in History.') }} {{ __('AI can make mistakes. Check the details before using or sharing a result.') }}</p>
    @unless($studioReady)<p class="aiw-alert" role="status" x-show="task !== 'photo'">{{ __('AI Studio is being updated. Please try again shortly.') }}</p>@endunless
    <p class="aiw-alert" x-show="!access[task] && (task === 'photo' || @js($studioReady))" x-cloak>{{ __('No credits available for this task.') }} <a href="{{ route('profile.edit', ['tab' => 'membership']) }}">{{ __('Manage credits') }}</a></p>
    <p role="status" aria-live="polite" x-show="notice" x-cloak x-text="notice" class="aiw-hint"></p>
</div>
