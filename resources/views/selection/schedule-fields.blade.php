@php
    $methodBinding = $calendarForm ? 'scheduleType' : 'interview_type';
    $platformBinding = $calendarForm ? 'schedulePlatform' : 'interview_platform';
    $locationBinding = $calendarForm ? 'scheduleLocation' : 'interview_location';
    $notesBinding = $calendarForm ? 'scheduleNotes' : 'interview_notes';
    $methodValue = $calendarForm ? $scheduleType : $interview_type;
    $existingMethod = $calendarForm ? $selectedInterview->interview_type : ($jobApplication?->interview_type ?? null);
    $stageValue = $calendarForm ? $selectedInterview->recruitment_stage : $recruitment_stage;
    $prefix = $calendarForm ? 'calendar-selection' : $fieldPrefix.'-selection';
    $online = \App\Support\SelectionSchedule::online($methodValue);
@endphp
<div class="selection-schedule-fields">
    <div @class(['selection-schedule-field', 'selection-schedule-wide' => !$online])>
        <label for="{{ $prefix }}-method">{{ __('Delivery method') }}</label>
        <select id="{{ $prefix }}-method" wire:model.live="{{ $methodBinding }}">
            <option value="">{{ __('Not decided yet') }}</option>
            @foreach(\App\Support\SelectionSchedule::methods($stageValue, $existingMethod) as $value => $label)<option value="{{ $value }}">{{ __($label) }}</option>@endforeach
        </select>
        <p class="selection-field-help">{{ match($methodValue) { 'Onsite', 'In-person' => __('Attend in person at the scheduled venue.'), 'Online', 'Video' => __('Join a video session or use an online test portal.'), 'Phone' => __('A phone call with the recruiter or interviewer.'), 'Panel' => __('Panel describes the interview format. Choose a delivery method when confirmed.'), default => __('Choose how this selection round will take place.') } }}</p>
        @error($methodBinding)<p class="selection-field-error" role="alert">{{ $message }}</p>@enderror
    </div>
    @if($online)
        @if(\App\Support\SelectionSchedule::platformAvailable())
            <div class="selection-schedule-field"><label for="{{ $prefix }}-platform">{{ __('Online platform') }}</label><select id="{{ $prefix }}-platform" wire:model="{{ $platformBinding }}"><option value="">{{ __('Select platform (optional)') }}</option>@foreach(\App\Support\SelectionSchedule::PLATFORMS as $platformOption)<option value="{{ $platformOption }}">{{ __($platformOption) }}</option>@endforeach</select>@error($platformBinding)<p class="selection-field-error" role="alert">{{ $message }}</p>@enderror</div>
        @else<p class="selection-field-help">{{ __('Platform selection will be available after the schedule update.') }}</p>@endif
    @endif
    <div class="selection-schedule-field selection-schedule-wide">
        <label for="{{ $prefix }}-location">{{ \App\Support\SelectionSchedule::locationLabel($methodValue) }}</label>
        <input id="{{ $prefix }}-location" wire:model="{{ $locationBinding }}" type="text" maxlength="255" placeholder="{{ $online ? __('Meeting or test link, when available') : ($methodValue === 'Phone' ? __('Contact number or who will call whom') : __('Venue, address, or instructions')) }}">
        @error($locationBinding)<p class="selection-field-error" role="alert">{{ $message }}</p>@enderror
    </div>
    <div class="selection-schedule-field selection-schedule-wide"><label for="{{ $prefix }}-notes">{{ __('Preparation & instructions') }}</label><textarea id="{{ $prefix }}-notes" wire:model="{{ $notesBinding }}" rows="3" maxlength="10000" placeholder="{{ __('Materials to bring, test instructions, or questions to prepare') }}"></textarea>@error($notesBinding)<p class="selection-field-error" role="alert">{{ $message }}</p>@enderror</div>
</div>
