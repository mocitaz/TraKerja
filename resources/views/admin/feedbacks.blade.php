<x-admin-layout>
<div class="admin-operations">
    <header class="ops-page-heading"><div><h1>User feedback</h1><p>Review questions, resolve issues, and keep members informed.</p></div><span class="ops-refresh-note">{{ number_format($totalFeedbacks) }} tickets received</span></header>
    @if(session('success_message'))<div class="ops-notice" role="status">{{ session('success_message') }}</div>@endif
    @if($errors->any())<div class="ops-notice ops-notice--error" role="alert">{{ $errors->first() }}</div>@endif
    <div class="ops-metric-strip">
        @foreach(['Pending'=>$pendingFeedbacks,'Replied'=>$repliedFeedbacks,'Completed'=>$completedFeedbacks,'On hold'=>$onHoldFeedbacks] as $label=>$count)
        <a href="{{ route('admin.feedbacks.index', ['status'=>strtolower(str_replace(' ','_',$label))]) }}"><span>{{ $label }}</span><strong class="admin-number--{{ ['Pending'=>'orange','Replied'=>'blue','Completed'=>'purple','On hold'=>'pink'][$label] }}">{{ number_format($count) }}</strong><small>View tickets</small></a>
        @endforeach
    </div>
    <section class="ops-panel">
        <form action="{{ route('admin.feedbacks.index') }}" method="GET" class="ops-filter">
            <label class="ops-search">Search tickets<input name="search" value="{{ request('search') }}" placeholder="Subject or message…" maxlength="100"></label>
            <label>Status<select name="status"><option value="">All statuses</option>@foreach(['pending'=>'Pending','replied'=>'Replied','completed'=>'Completed','on_hold'=>'On hold'] as $key=>$label)<option value="{{ $key }}" @selected(request('status')===$key)>{{ $label }}</option>@endforeach</select></label>
            <label>Category<select name="category"><option value="">All categories</option>@foreach(['technical_issue'=>'Technical issue','payment_billing'=>'Payment & billing','feature_request'=>'Feature request','general_feedback'=>'General feedback'] as $key=>$label)<option value="{{ $key }}" @selected(request('category')===$key)>{{ $label }}</option>@endforeach</select></label>
            <button class="ops-button" type="submit">Filter</button>@if(request()->hasAny(['search','status','category']))<a class="ops-button ops-button--quiet" href="{{ route('admin.feedbacks.index') }}">Clear</a>@endif
        </form>
        <div class="ops-ticket-head"><span>Ticket & member</span><span>Status</span><span>Received</span><span>Manage</span></div>
        @forelse($tickets as $ticket)
        <article class="ops-ticket" x-data="{ expanded: {{ $errors->any() ? 'true' : 'false' }} }">
            <div class="ops-ticket-row">
                <div><strong>{{ $ticket->subject }}</strong><p>{{ $ticket->user?->name ?? $ticket->guest_name ?? 'Guest' }} · {{ $ticket->category_label }}</p><small>{{ $ticket->user?->email ?? $ticket->guest_email }}</small></div>
                <span class="ops-status" data-status="{{ $ticket->status }}">{{ ucfirst(str_replace('_',' ',$ticket->status)) }}</span>
                <time datetime="{{ $ticket->created_at->toIso8601String() }}">{{ $ticket->created_at->format('d M Y') }}<small>{{ $ticket->created_at->format('H:i') }} WIB</small></time>
                <button type="button" class="ops-button ops-button--quiet" @click="expanded = !expanded" :aria-expanded="expanded" aria-controls="ticket-{{ $ticket->id }}"><span x-text="expanded ? 'Close' : 'Review'"></span><i class="ph ph-caret-down" :class="expanded && 'rotate-180'" aria-hidden="true"></i></button>
            </div>
            <div id="ticket-{{ $ticket->id }}" x-show="expanded" x-collapse x-cloak class="ops-ticket-detail">
                <div class="ops-conversation"><h3>Member message</h3><p>{{ $ticket->message }}</p>@if($ticket->admin_reply)<div class="ops-reply"><h3>Your latest reply</h3><p>{{ $ticket->admin_reply }}</p><small>{{ $ticket->replied_at?->format('d M Y, H:i') }} WIB</small></div>@endif</div>
                <div class="ops-ticket-actions">
                    <form action="{{ route('admin.feedbacks.reply',$ticket->id) }}" method="POST" class="ops-form">@csrf<h3>Write a reply</h3><label>Reply<textarea name="admin_reply" rows="4" minlength="5" maxlength="5000" required placeholder="Write a helpful response…">{{ old('admin_reply',$ticket->admin_reply) }}</textarea></label><label>Ticket status<select name="status">@foreach(['replied'=>'Replied','completed'=>'Completed','on_hold'=>'On hold','pending'=>'Pending'] as $key=>$label)<option value="{{ $key }}" @selected($key===$ticket->status)>{{ $label }}</option>@endforeach</select></label><button type="submit" class="ops-button">Save reply & status</button><small>The reply is saved to this ticket.</small></form>
                    <details class="ops-more"><summary>Other actions</summary><form method="POST" action="{{ route('admin.feedbacks.status',$ticket->id) }}" class="ops-inline-form">@csrf @method('PATCH')<label>Status only<select name="status">@foreach(['pending','replied','completed','on_hold'] as $status)<option value="{{ $status }}" @selected($ticket->status===$status)>{{ ucfirst(str_replace('_',' ',$status)) }}</option>@endforeach</select></label><button class="ops-button ops-button--quiet">Update status</button></form><form method="POST" action="{{ route('admin.feedbacks.destroy',$ticket->id) }}" onsubmit="return confirm('Delete this ticket permanently? This cannot be undone.')">@csrf @method('DELETE')<button class="ops-button ops-button--danger">Delete ticket</button></form></details>
                </div>
            </div>
        </article>
        @empty<div class="ops-empty"><h3>{{ request()->hasAny(['search','status','category']) ? 'No matching tickets' : 'You’re all caught up' }}</h3><p>{{ request()->hasAny(['search','status','category']) ? 'Try a different filter to find a ticket.' : 'Member questions and feedback will appear here.' }}</p></div>@endforelse
        <footer class="ops-panel-footer"><span>{{ $tickets->total() }} matching tickets</span>{{ $tickets->links() }}</footer>
    </section>
</div>
</x-admin-layout>
