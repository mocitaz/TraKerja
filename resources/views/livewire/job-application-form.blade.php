@php($fieldPrefix = 'job-application-'.$this->getId())
<div class="relative" data-job-application-form>


    {{-- Global Error Feedback --}}
    @if ($errors->any())
        <div class="mb-4 bg-rose-50 border border-rose-100 rounded-md p-3 flex items-start gap-2.5 animate-fadeIn">
            <i aria-hidden="true" class="ph-fill ph-warning-circle text-rose-700 text-base shrink-0 mt-0.5"></i>
            <div>
                <p class="text-xs font-bold text-rose-800 uppercase tracking-wider">{{ __('Check your details') }}</p>
                <p class="text-xs font-medium text-rose-600 mt-0.5">{{ __('Please review the highlighted fields below.') }}</p>
            </div>
        </div>
    @endif

    <form wire:submit="save" class="job-form">
        <section class="job-form__section" aria-labelledby="{{ $fieldPrefix }}-details-title">
            <h3 id="{{ $fieldPrefix }}-details-title">{{ __('Job details') }}</h3>
        {{-- Row 1: Company & Position --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
            <div>
                <label for="{{ $fieldPrefix }}-company_name" class="block text-xs font-bold text-ui-muted uppercase tracking-wider mb-1">{{ __('Company *') }}</label>
                <div class="relative group">
                    <input id="{{ $fieldPrefix }}-company_name" wire:model.live="company_name" type="text" class="block w-full pl-8 pr-3 h-[32px] bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 focus:ring-1 focus:ring-primary-500/20 focus:bg-white focus:border-primary-500 transition-all outline-none" placeholder="{{ __('Company name') }}">
                </div>
                @error('company_name') <p class="text-rose-700 text-xs font-semibold mt-1 ml-0.5">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="{{ $fieldPrefix }}-position" class="block text-xs font-bold text-ui-muted uppercase tracking-wider mb-1">{{ __('Position *') }}</label>
                <div class="relative group">
                    <input id="{{ $fieldPrefix }}-position" wire:model.live="position" list="{{ $fieldPrefix }}-position-suggestions" type="text" class="block w-full pl-8 pr-3 h-[32px] bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 focus:ring-1 focus:ring-primary-500/20 focus:bg-white focus:border-primary-500 transition-all outline-none" placeholder="{{ __('e.g. Product Designer') }}">
                    @if(!empty($previousPositions))
                    <datalist id="{{ $fieldPrefix }}-position-suggestions">
                        @foreach($previousPositions as $pos)
                            <option value="{{ $pos }}"></option>
                        @endforeach
                    </datalist>
                    @endif
                </div>
                @error('position') <p class="text-rose-700 text-xs font-semibold mt-1 ml-0.5">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Row 2: Location --}}
        @if(!$isRemote && !$isInternational)
        <div class="animate-fadeIn">
            <label for="{{ $fieldPrefix }}-selectedProvince" class="block text-xs font-bold text-ui-muted uppercase tracking-wider mb-1">{{ __('Location *') }}</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <select id="{{ $fieldPrefix }}-selectedProvince" wire:key="province-select" wire:model.live="selectedProvince" class="block w-full py-0 pl-2.5 pr-8 h-[32px] bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 outline-none focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white transition-all cursor-pointer">
                    <option value="">{{ __('Select province') }}</option>
                    @foreach($provinces as $province => $cities) <option value="{{ $province }}">{{ $province }}</option> @endforeach
                </select>
                <label for="{{ $fieldPrefix }}-selectedCity" class="sr-only">{{ __('City') }}</label>
                <select id="{{ $fieldPrefix }}-selectedCity" wire:key="city-select-{{ $selectedProvince }}" wire:model.live="selectedCity" class="block w-full py-0 pl-2.5 pr-8 h-[32px] bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 outline-none transition-all focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white {{ empty($selectedProvince) ? 'disabled:text-ui-disabled disabled:bg-zinc-100 cursor-not-allowed' : 'cursor-pointer' }}" {{ empty($selectedProvince) ? 'disabled' : '' }}>
                    <option value="">{{ __('Select city') }}</option>
                    @if(!empty($selectedProvince) && isset($provinces[$selectedProvince]))
                        @foreach($provinces[$selectedProvince] as $city) <option value="{{ $city }}">{{ $city }}</option> @endforeach
                    @endif
                </select>
            </div>
            @error('selectedProvince') <p class="text-rose-700 text-xs font-semibold mt-1 ml-0.5">{{ $message }}</p> @enderror
        </div>
        @endif

        {{-- Row 3: Work Mode (Remote & International) --}}
        @if(empty($selectedProvince))
        <div class="grid grid-cols-2 gap-3 animate-fadeIn">
            <label for="{{ $fieldPrefix }}-isRemote" class="job-form__mode-pill text-xs flex items-center justify-center gap-2.5 py-2 px-3 cursor-pointer group transition-all">
                <input id="{{ $fieldPrefix }}-isRemote" type="checkbox" wire:model.live="isRemote" class="sr-only peer">
                <div class="job-form__mode-icon">
                    <i aria-hidden="true" class="ph-bold ph-house-line text-xs"></i>
                </div>
                <span class="text-xs font-bold uppercase tracking-wider transition-colors">{{ __('Remote') }}</span>
            </label>
            <label for="{{ $fieldPrefix }}-isInternational" class="job-form__mode-pill text-xs flex items-center justify-center gap-2.5 py-2 px-3 cursor-pointer group transition-all">
                <input id="{{ $fieldPrefix }}-isInternational" type="checkbox" wire:model.live="isInternational" class="sr-only peer">
                <div class="job-form__mode-icon">
                    <i aria-hidden="true" class="ph-bold ph-airplane-tilt text-xs"></i>
                </div>
                <span class="text-xs font-bold uppercase tracking-wider transition-colors">{{ __('International') }}</span>
            </label>
        </div>
        @endif

        {{-- International Location (Visible when International is checked) --}}
        @if($isInternational)
        <div class="animate-fadeIn pt-0.5">
            <label for="{{ $fieldPrefix }}-selectedCountry" class="block text-xs font-bold text-ui-muted uppercase tracking-wider mb-1">{{ __('International location *') }}</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <select id="{{ $fieldPrefix }}-selectedCountry" wire:key="country-select" wire:model.live="selectedCountry" class="block w-full py-0 pl-2.5 pr-8 h-[32px] bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 outline-none focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white transition-all cursor-pointer">
                    <option value="">{{ __('Select country') }}</option>
                    @foreach($countries as $country => $cities) <option value="{{ $country }}">{{ $country }}</option> @endforeach
                </select>
                <label for="{{ $fieldPrefix }}-selectedInternationalCity" class="sr-only">{{ __('International city') }}</label>
                <select id="{{ $fieldPrefix }}-selectedInternationalCity" wire:key="int-city-select-{{ $selectedCountry }}" wire:model.live="selectedInternationalCity" class="block w-full py-0 pl-2.5 pr-8 h-[32px] bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 outline-none focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white {{ empty($selectedCountry) ? 'disabled:text-ui-disabled disabled:bg-zinc-100 cursor-not-allowed' : 'cursor-pointer' }}" {{ empty($selectedCountry) ? 'disabled' : '' }}>
                    <option value="">{{ __('Select city') }}</option>
                    @if(!empty($selectedCountry) && isset($countries[$selectedCountry]))
                        @foreach($countries[$selectedCountry] as $city) <option value="{{ $city }}">{{ $city }}</option> @endforeach
                    @endif
                </select>
            </div>
            @error('selectedCountry') <p class="text-rose-700 text-xs font-semibold mt-1 ml-0.5">{{ $message }}</p> @enderror
        </div>
        @endif

        {{-- Row 4: Platform & Employment type --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
            <div>
                <label for="{{ $fieldPrefix }}-platform" class="block text-xs font-bold text-ui-muted uppercase tracking-wider mb-1">{{ __('Platform *') }}</label>
<div class="platform-select-field"><x-platform-brand :name="$platform" :show-name="false" />                <select id="{{ $fieldPrefix }}-platform" wire:model.live="platform" class="block w-full py-0 pl-2.5 pr-8 h-[32px] bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 outline-none focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white transition-all cursor-pointer">
                    <option value="">{{ __('Select platform') }}</option>
                    @foreach($platformOptions as $opt)
                        <option value="{{ $opt }}">{{ \App\Support\UiLabel::value($opt) }}</option>
                    @endforeach
                </select>
</div>
                @if(empty($platform) && !empty($topPlatforms))
                <div class="job-form__platform-shortcuts">
                    @foreach($topPlatforms as $tp)
                        <button type="button" wire:click="selectPlatform(@js($tp))" class="px-2 py-1 bg-primary-50 hover:bg-primary-100 border border-primary-200/50 text-zinc-800 rounded text-xs font-bold transition-all whitespace-nowrap active:scale-97">
                            <x-platform-brand :name="$tp" class="platform-brand--compact" />
                        </button>
                    @endforeach
                </div>
                @endif
                <details class="platform-picker" x-data="{ query: '' }" wire:key="platform-picker" @keydown.escape="if ($el.open) { $event.stopPropagation(); $el.open = false; $el.querySelector('summary').focus(); }">
                    <summary>{{ __('Browse all platforms') }} <i class="ph ph-caret-down" aria-hidden="true"></i></summary>
                    <div class="platform-picker__body">
                        <label class="sr-only" for="{{ $fieldPrefix }}-platform-search">{{ __('Search platforms') }}</label>
                        <input id="{{ $fieldPrefix }}-platform-search" type="search" x-model="query" placeholder="{{ __('Search platforms…') }}" autocomplete="off">
                        <div class="platform-picker__grid">
                            @foreach($platformOptions as $opt)
                                <button type="button" wire:key="platform-choice-{{ $loop->index }}" wire:click="selectPlatform(@js($opt))" @click="query = ''; const picker = $el.closest('details'); picker.open = false; picker.querySelector('summary').focus()" x-show="@js(mb_strtolower($opt)).includes(query.toLowerCase().trim())" class="{{ $platform === $opt ? 'is-selected' : '' }}" aria-pressed="{{ $platform === $opt ? 'true' : 'false' }}"><x-platform-brand :name="$opt" /><i class="ph ph-check" aria-hidden="true"></i></button>
                            @endforeach
                        </div>
                        <p x-show="query.trim() && !@js(array_map('mb_strtolower', $platformOptions)).some(name => name.includes(query.toLowerCase().trim()))" x-cloak class="platform-picker__empty">{{ __('No match. Select Other to enter a custom platform.') }}</p>
                    </div>
                </details>
                @if($platform === 'Other')
                    <div class="mt-3"><label for="{{ $fieldPrefix }}-platform-other">{{ __('Custom platform *') }}</label><input id="{{ $fieldPrefix }}-platform-other" wire:model.live="platformOther" type="text" maxlength="255" placeholder="{{ __('Platform name') }}" required>@error('platformOther')<p class="text-rose-700 text-xs mt-1">{{ $message }}</p>@enderror</div>
                @endif
                @error('platform') <p class="text-rose-700 text-xs font-semibold mt-1 ml-0.5">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="{{ $fieldPrefix }}-career_level" class="block text-xs font-bold text-ui-muted uppercase tracking-wider mb-1">{{ __('Employment type') }}</label>
                <select id="{{ $fieldPrefix }}-career_level" wire:model.live="career_level" class="block w-full py-0 pl-2.5 pr-8 h-[32px] bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 outline-none focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white transition-all cursor-pointer">
                    <option value="">{{ __('Select type') }}</option>
                    @foreach($careerLevelOptions as $cl) <option value="{{ $cl }}">{{ $cl === 'MT' ? __('Management Trainee') : $cl }}</option> @endforeach
                </select>
                @error('career_level') <p class="text-rose-700 text-xs font-semibold mt-1 ml-0.5">{{ $message }}</p> @enderror
            </div>
        </div>

        </section>
        <section class="job-form__section job-form__section--progress" aria-labelledby="{{ $fieldPrefix }}-progress-title">
            <h3 id="{{ $fieldPrefix }}-progress-title">{{ __('Application progress') }}</h3>
        {{-- Row 5: Status & Stage --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
            <div>
                <label for="{{ $fieldPrefix }}-application_status" class="block text-xs font-bold text-ui-muted uppercase tracking-wider mb-1">{{ __('Application status *') }}</label>
                <select id="{{ $fieldPrefix }}-application_status" wire:model.live="application_status" class="block w-full py-0 pl-2.5 pr-8 h-[32px] bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 outline-none focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white transition-all cursor-pointer">
                    @foreach($applicationStatusOptions as $opt) <option value="{{ $opt }}">{{ \App\Support\UiLabel::value($opt) }}</option> @endforeach
                </select>
                @error('application_status') <p class="text-rose-700 text-xs font-semibold mt-1 ml-0.5">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="{{ $fieldPrefix }}-recruitment_stage" class="block text-xs font-bold text-ui-muted uppercase tracking-wider mb-1">{{ __('Current stage *') }}</label>
                <select id="{{ $fieldPrefix }}-recruitment_stage" wire:model.live="recruitment_stage" class="block w-full py-0 pl-2.5 pr-8 h-[32px] bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 outline-none focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white transition-all cursor-pointer">
                    @foreach($recruitmentStageOptions as $stg) <option value="{{ $stg }}">{{ $stg }}</option> @endforeach
                </select>
                @error('recruitment_stage') <p class="text-rose-700 text-xs font-semibold mt-1 ml-0.5">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Row 6: App Link & Date --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
            <div>
                <label for="{{ $fieldPrefix }}-platform_link" class="block text-xs font-bold text-ui-muted uppercase tracking-wider mb-1">{{ __('Job posting URL') }}</label>
                <div class="flex items-center gap-2">
                    <div class="relative group flex-1">
                        <input id="{{ $fieldPrefix }}-platform_link" wire:model.live="platform_link" type="url" class="block w-full pl-8 pr-3 h-[32px] bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 focus:ring-1 focus:ring-primary-500/20 focus:bg-white focus:border-primary-500 transition-all outline-none" placeholder="https://...">
                    </div>
                    <button type="button" onclick="fetchJobDetailsFromUrl(this)" id="{{ $fieldPrefix }}-scrape-btn" data-scrape-button class="h-[32px] px-3.5 bg-primary-50 hover:bg-primary-100 text-zinc-800 border border-primary-200/60 rounded-md text-xs font-bold transition-all flex items-center justify-center active:scale-95 select-none shrink-0">
                        <span>{{ __('Autofill') }}</span>
                    </button>
                </div>
                @error('platform_link') <p class="text-rose-700 text-xs font-semibold mt-1 ml-0.5">{{ $message }}</p> @enderror

                <p class="job-form__hint">{{ __('Autofill from supported job sites, or enter the details manually.') }}</p>

                <!-- Scraper Status Alert message inside FE -->
                <div id="{{ $fieldPrefix }}-scrape-status-message" data-scrape-status-message role="status" aria-live="polite" class="hidden mt-1.5 text-xs font-semibold items-center gap-1 transition-all duration-300 select-none">
                    <i aria-hidden="true" class="ph-bold text-xs" id="{{ $fieldPrefix }}-scrape-status-icon" data-scrape-status-icon></i>
                    <span id="{{ $fieldPrefix }}-scrape-status-text" data-scrape-status-text></span>
                </div>
            </div>
            <div>
                <label for="{{ $fieldPrefix }}-application_date" class="block text-xs font-bold text-ui-muted uppercase tracking-wider mb-1">{{ __('Date applied *') }}</label>
                <div class="relative">
                    <input id="{{ $fieldPrefix }}-application_date" wire:model.live="application_date" type="date" class="block w-full px-3 h-[32px] bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 outline-none focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white transition-all cursor-pointer">
                </div>
                @error('application_date') <p class="text-rose-700 text-xs font-semibold mt-1 ml-0.5">{{ $message }}</p> @enderror
            </div>
        </div>

        @if(\App\Support\JobApplicationStatus::canSchedule($recruitment_stage))
            <section class="job-form__interview-box selection-schedule" aria-labelledby="{{ $fieldPrefix }}-schedule-title">
                <header><h3 id="{{ $fieldPrefix }}-schedule-title">{{ \App\Support\SelectionSchedule::heading($recruitment_stage) }}</h3><span>WIB · UTC+7</span></header>
                <div class="selection-schedule-field"><label for="{{ $fieldPrefix }}-interview_date">{{ __('Date & time · WIB') }}</label><input id="{{ $fieldPrefix }}-interview_date" wire:model="interview_date" type="datetime-local"><p class="selection-field-help">{{ __('Leave blank if the schedule has not been confirmed.') }}</p>@error('interview_date')<p class="selection-field-error" role="alert">{{ $message }}</p>@enderror</div>
                @include('selection.schedule-fields', ['calendarForm' => false])
            </section>
        @endif

        {{-- Notes --}}
        <div>
            <label for="{{ $fieldPrefix }}-notes" class="block text-xs font-bold text-ui-muted uppercase tracking-wider mb-1">{{ __('Notes') }}</label>
            <textarea id="{{ $fieldPrefix }}-notes" wire:model.live="notes" rows="2" class="block w-full px-3 py-2 bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-medium text-zinc-700 outline-none resize-none transition-all focus:bg-white focus:border-primary-500" placeholder="{{ __('Add a reminder or details you want to keep…') }}"></textarea>
            @error('notes') <p class="text-rose-700 text-xs font-semibold mt-1 ml-0.5">{{ $message }}</p> @enderror
        </div>

        </section>
        {{-- Actions --}}
        <div class="job-form__actions">
            <button type="button" wire:click="resetForm" wire:loading.attr="disabled" wire:target="save" class="text-xs font-bold text-ui-muted hover:text-rose-700 uppercase tracking-wider transition-colors">{{ __('Clear form') }}</button>
            <div class="flex items-center gap-3">
                <button type="button" onclick="closeJobModal()" class="text-xs font-bold text-ui-muted hover:text-zinc-700 uppercase tracking-wider transition-all">{{ __('Cancel') }}</button>
                <button type="submit" wire:loading.attr="disabled" wire:target="save" class="px-4 h-[30px] bg-primary-50 text-zinc-800 hover:bg-primary-100 border border-primary-200/60 text-xs font-bold rounded-md shadow-3xs transition-all active:scale-97 hover:shadow-2xs uppercase tracking-wider flex items-center gap-1.5 group focus:outline-none">
                    <span wire:loading.remove wire:target="save" class="flex items-center gap-1.5">
                        <i aria-hidden="true" class="ph-bold ph-check text-xs"></i>
                        {{ $isEditing ? __('Save changes') : __('Save application') }}
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center gap-1.5">
                        <i aria-hidden="true" class="ph-bold ph-spinner animate-spin text-xs"></i>
                        {{ __('Saving…') }}
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
window.updateScrapeStatus = function(state, message = '', root) {
    const statusDiv = root?.querySelector('[data-scrape-status-message]');
    const iconEl = root?.querySelector('[data-scrape-status-icon]');
    const textEl = root?.querySelector('[data-scrape-status-text]');
    const btn = root?.querySelector('[data-scrape-button]');

    if (!statusDiv || !iconEl || !textEl) return;

    statusDiv.className = "mt-1.5 text-[11px] font-medium leading-snug flex items-start gap-1.5";
    textEl.className = "min-w-0 break-words";

    if (state === 'idle') {
        statusDiv.classList.add('hidden');
        if (btn) {
            btn.disabled = false;
            btn.className = "h-[32px] px-3.5 bg-primary-50 hover:bg-primary-100 text-zinc-800 border border-primary-200/60 rounded-md text-xs font-bold transition-all flex items-center justify-center active:scale-95 select-none shrink-0";
            btn.innerHTML = '<span>Autofill</span>';
        }
    } else if (state === 'fetching') {
        statusDiv.classList.remove('hidden');
        statusDiv.className = "mt-1.5 text-[11px] font-medium leading-snug flex items-start gap-1.5 transition-colors select-none text-ui-muted";
        iconEl.className = "mt-0.5 shrink-0 ph-bold ph-spinner animate-spin text-xs text-ui-muted";
        textEl.textContent = message || @js(__('Reading job details…'));

        if (btn) {
            btn.disabled = true;
            btn.className = "h-[32px] px-3.5 bg-zinc-50 text-ui-muted border border-zinc-200 rounded-md text-xs font-bold transition-all flex items-center justify-center select-none shrink-0";
            btn.innerHTML = '<i aria-hidden="true" class="ph-bold ph-spinner animate-spin text-xs mr-1"></i><span>Reading…</span>';
        }
    } else if (state === 'bypassing') {
        statusDiv.classList.remove('hidden');
        statusDiv.className = "mt-1.5 text-[11px] font-medium leading-snug flex items-start gap-1.5 transition-colors select-none text-amber-800";
        iconEl.className = "mt-0.5 shrink-0 ph-bold ph-shield-warning animate-bounce text-xs text-amber-500";
        textEl.textContent = message || @js(__('Access restricted. Retrying…'));

        if (btn) {
            btn.disabled = true;
            btn.className = "h-[32px] px-3.5 bg-amber-50 text-amber-800 border border-amber-200 rounded-md text-xs font-bold transition-all flex items-center justify-center select-none shrink-0 animate-pulse";
            btn.innerHTML = '<i aria-hidden="true" class="ph-bold ph-shield-warning text-xs mr-1"></i><span>Retrying…</span>';
        }
    } else if (state === 'success') {
        statusDiv.classList.remove('hidden');
        statusDiv.className = "mt-1.5 text-[11px] font-medium leading-snug flex items-start gap-1.5 transition-colors select-none text-emerald-700";
        iconEl.className = "mt-0.5 shrink-0 ph-bold ph-check-circle text-xs text-emerald-700";
        textEl.textContent = message || @js(__('Job details filled. Review before saving.'));

        if (btn) {
            btn.disabled = false;
            btn.className = "h-[32px] px-3.5 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-md text-xs font-bold transition-all flex items-center justify-center select-none shrink-0";
            btn.innerHTML = '<i aria-hidden="true" class="ph-bold ph-check text-xs mr-1"></i><span>Filled</span>';
        }


    } else if (state === 'error') {
        statusDiv.classList.remove('hidden');
        statusDiv.className = "mt-1.5 text-[11px] font-medium leading-snug flex items-start gap-1.5 transition-colors select-none text-rose-600";
        iconEl.className = "mt-0.5 shrink-0 ph-bold ph-warning-circle text-xs text-rose-700";
        textEl.textContent = message || @js(__('Could not read this listing. Please try again.'));

        if (btn) {
            btn.disabled = false;
            btn.className = "h-[32px] px-3.5 bg-rose-50 text-rose-800 border border-rose-200 rounded-md text-xs font-bold transition-all flex items-center justify-center select-none shrink-0";
            btn.innerHTML = '<i aria-hidden="true" class="ph-bold ph-x text-xs mr-1"></i><span>Retry</span>';
        }

        setTimeout(() => {
            window.updateScrapeStatus('idle', '', root);
        }, 5000);
    }
};

</script>
