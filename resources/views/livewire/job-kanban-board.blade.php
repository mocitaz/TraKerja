<div class="tracker-board" x-data="{ dragging: null, over: null, startDrag(event, id) { this.dragging = id; event.dataTransfer.setData('text/plain', String(id)); event.dataTransfer.effectAllowed = 'move'; }, finishDrag() { this.dragging = null; this.over = null; }, moveCard(event, status) { if (this.dragging !== null) { $wire.updateStatus(this.dragging, status); } this.finishDrag(); } }">
    @include('tracker.partials.filters', ['mode' => 'board'])
    <div class="tracker-board-heading"><p>{{ __('Drag a card to change its status, or use Move to.') }}</p><span wire:loading.delay role="status">{{ __('Updating board…') }}</span></div>
    <div class="tracker-board-grid">
        @foreach($statuses as $status)
            @php($tone = match($status->name) { 'Accepted' => 'accepted', 'Rejected' => 'rejected', default => 'process' })
            <section class="tracker-column tracker-column--{{ $tone }}" data-status-container="{{ $status->name }}" aria-label="{{ __(':value1 applications', ['value1' => ($status->name)]) }}" :class="{ 'is-drag-over': over === @js($status->name) }" @dragover.prevent="if (dragging !== null) over = @js($status->name)" @dragleave="if (!$el.contains($event.relatedTarget)) over = null" @drop.prevent="moveCard($event, @js($status->name))">
                <header class="tracker-column-header"><div><span class="tracker-column-dot" aria-hidden="true"></span><h2>{{ \App\Support\UiLabel::value($status->name) }}</h2><span class="tracker-column-count">{{ $status->total }}</span></div><button type="button" onclick="openJobModal(false, '{{ $status->name === 'On Process' ? __('Applied') : $status->name }}')" aria-label="{{ __('Add application in :value1', ['value1' => ($status->name)]) }}" class="tracker-column-add"><i class="ph ph-plus" aria-hidden="true"></i></button></header>
                <div class="tracker-column-cards">@forelse($status->jobApplications as $job)@include('tracker.partials.application-card', ['mode' => 'board'])@empty<div class="tracker-column-empty"><p>{{ __('No applications here yet.') }}</p><span>{{ $status->name === 'On Process' ? __('Add an opportunity to get started.') : __('Move a card here when its status changes.') }}</span></div>@endforelse</div>
                <footer class="tracker-column-pagination" aria-label="{{ __(':value1 pages', ['value1' => ($status->name)]) }}">{{ $status->jobApplications->links() }}</footer>
            </section>
        @endforeach
    </div>
    @include('tracker.partials.follow-up')
</div>
