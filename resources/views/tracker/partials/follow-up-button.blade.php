<button type="button" wire:click="generateFollowUp({{ $job->id }})" wire:loading.attr="disabled" wire:target="generateFollowUp" class="tracker-follow-up" aria-label="{{ __('Draft follow-up for :value1 at :value2', ['value1' => ($job->position), 'value2' => ($job->company_name)]) }}" title="{{ __('Available after 14 days since applying') }}">
    <i class="ph ph-paper-plane-tilt" aria-hidden="true"></i>
    <span wire:loading.remove wire:target="generateFollowUp({{ $job->id }})">{{ __('Draft follow-up') }}</span>
    <span wire:loading wire:target="generateFollowUp({{ $job->id }})" role="status">{{ __('Preparing draft…') }}</span>
</button>
