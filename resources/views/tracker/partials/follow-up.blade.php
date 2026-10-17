<template x-teleport="body">
    <div wire:ignore.self x-data="{ show: @entangle('showFollowUpModal'), copied: false, copyError: false }" x-show="show" x-cloak x-dialog="show" data-dialog-close="$wire.closeFollowUpModal()" role="dialog" aria-modal="true" aria-label="{{ __('Draft follow-up email') }}" tabindex="-1" class="tracker-draft-backdrop" style="display: none;">
        <div class="tracker-draft-panel">
            <header><div><h2>{{ __('Draft a follow-up') }}</h2><p>{{ __('Review your message before sharing it.') }}</p></div><button type="button" wire:click="closeFollowUpModal" aria-label="{{ __('Close follow-up draft') }}"><i class="ph ph-x" aria-hidden="true"></i></button></header>
            <div class="tracker-draft-body"><label for="follow-up-{{ $this->getId() }}">{{ __('Email draft') }}</label><textarea id="follow-up-{{ $this->getId() }}" wire:model="followUpDraft" rows="10"></textarea><p>{{ __('Check the company, recipient, and any placeholders before sending.') }}</p><p x-show="copyError" role="alert">{{ __('Could not copy. Select the draft and copy it manually.') }}</p></div>
            <footer><button type="button" wire:click="closeFollowUpModal" class="workspace-button workspace-button--quiet">{{ __('Close') }}</button><button type="button" class="workspace-button workspace-button--primary" @click="copyError = false; navigator.clipboard.writeText($wire.followUpDraft).then(() => { copied = true; setTimeout(() => copied = false, 2500); }).catch(() => copyError = true)"><i class="ph ph-copy" aria-hidden="true"></i><span x-text="copied ? @js(__('Copied')) : @js(__('Copy draft'))">{{ __('Copy draft') }}</span></button></footer>
        </div>
    </div>
</template>
