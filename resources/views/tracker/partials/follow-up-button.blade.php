<button type="button" wire:click="generateFollowUp({{ $job->id }})" wire:loading.attr="disabled" wire:target="generateFollowUp" class="tracker-follow-up" aria-label="Draft follow-up for {{ $job->position }} at {{ $job->company_name }}" title="Available after 14 days since applying">
    <i class="ph ph-paper-plane-tilt" aria-hidden="true"></i>
    <span wire:loading.remove wire:target="generateFollowUp({{ $job->id }})">Draft follow-up</span>
    <span wire:loading wire:target="generateFollowUp({{ $job->id }})" role="status">Preparing draft…</span>
</button>
