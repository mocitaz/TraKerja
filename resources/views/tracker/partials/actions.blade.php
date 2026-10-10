<div class="tracker-actions">
    @if($mode === 'table')<button type="button" wire:click="togglePin({{ $job->id }})" aria-label="{{ $job->is_pinned ? 'Unpin' : 'Pin' }} {{ $job->position }} at {{ $job->company_name }}" title="{{ $job->is_pinned ? 'Unpin' : 'Pin' }}" class="{{ $job->is_pinned ? 'is-pinned' : '' }}"><i class="ph{{ $job->is_pinned ? '-fill' : '' }} ph-push-pin" aria-hidden="true"></i></button>@endif
    <button type="button" wire:click="edit({{ $job->id }})" aria-label="Edit {{ $job->position }} at {{ $job->company_name }}" title="Edit"><i class="ph ph-pencil-simple" aria-hidden="true"></i></button>
    <button type="button" wire:click="confirmDelete({{ $job->id }})" aria-label="Delete {{ $job->position }} at {{ $job->company_name }}" title="Delete" class="tracker-action-delete"><i class="ph ph-trash" aria-hidden="true"></i></button>
</div>
