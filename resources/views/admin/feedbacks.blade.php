<x-admin-layout>
@php
    $statuses = ['pending' => 'Pending', 'replied' => 'Replied', 'completed' => 'Completed', 'on_hold' => 'On hold'];
    $categories = ['technical_issue' => 'Technical Issue', 'payment_billing' => 'Payment & Billing', 'feature_request' => 'Feature Request', 'general_feedback' => 'General Feedback'];
    $filtered = collect(request()->only(['search','status','category','ticket']))->filter(fn($value) => filled($value))->isNotEmpty();
@endphp
<div class="av-workspace av-feedbacks" >
    <header class="am-page-heading"><div><h1>{{ __('User Feedbacks') }}</h1><p>{{ __('Read member feedback, respond to requests, and keep follow-ups in view.') }}</p></div><span class="av-heading-count"><i class="ph ph-chats" aria-hidden="true"></i>{{ __(':count tickets in total', ['count' => number_format($totalFeedbacks)]) }}</span></header>
    @if(session('success_message') || session('success'))<div class="av-notice" role="status"><i class="ph ph-check-circle" aria-hidden="true"></i>{{ session('success_message') ?? session('success') }}</div>@endif
    @if($errors->any())<div class="av-notice av-notice--error" role="alert">{{ $errors->first() }}</div>@endif
    <section class="ac-stats av-stats" aria-label="{{ __('All ticket statuses') }}">
    @foreach([['pending',$pendingFeedbacks,'clock'],['replied',$repliedFeedbacks,'paper-plane-tilt'],['completed',$completedFeedbacks,'check-circle'],['on_hold',$onHoldFeedbacks,'pause-circle']] as [$status,$count,$icon])
        <a href="{{ route('admin.feedbacks.index', array_merge(request()->except('status','page','ticket'), ['status'=>$status])) }}" class="ac-stat av-stat-link {{ request('status') === $status ? 'is-selected' : '' }}" @if(request('status') === $status) aria-current="true" @endif><span class="ac-stat-icon"><i class="ph ph-{{ $icon }}" aria-hidden="true"></i></span><div><span class="ac-stat-label">{{ __($statuses[$status]) }}</span><div class="ac-stat-value">{{ number_format($count) }}</div></div><i class="ph ph-caret-right av-stat-caret" aria-hidden="true"></i></a>
    @endforeach
    </section>
    <section class="am-directory av-panel" aria-labelledby="feedback-heading">
        <header class="am-directory-heading"><div><h2 id="feedback-heading">{{ __('Support tickets') }} <span>{{ number_format($tickets->total()) }}</span></h2><p>{{ __('Newest first. Status totals above include all tickets.') }}</p></div>@if($filtered)<a href="{{ route('admin.feedbacks.index') }}" class="am-button">{{ __('Clear filters') }}</a>@endif</header>
        <form method="GET" action="{{ route('admin.feedbacks.index') }}" class="av-filters"><label class="am-filter av-search"><span>{{ __('Search tickets') }}</span><input type="search" name="search" value="{{ request('search') }}" maxlength="100" placeholder="{{ __('Subject, message, name or email') }}"></label><label class="am-filter"><span>{{ __('Status') }}</span><select name="status"><option value="">{{ __('All statuses') }}</option>@foreach($statuses as $key=>$label)<option value="{{ $key }}" @selected(request('status') === $key)>{{ __($label) }}</option>@endforeach</select></label><label class="am-filter"><span>{{ __('Category') }}</span><select name="category"><option value="">{{ __('All categories') }}</option>@foreach($categories as $key=>$label)<option value="{{ $key }}" @selected(request('category') === $key)>{{ __($label) }}</option>@endforeach</select></label><button class="am-button am-button--primary" type="submit"><i class="ph ph-funnel" aria-hidden="true"></i>{{ __('Apply filters') }}</button></form>
        <div class="av-table-scroll"><table class="am-table av-table"><thead><tr><th scope="col">{{ __('Member') }}</th><th scope="col">{{ __('Request') }}</th><th scope="col">{{ __('Status') }}</th><th scope="col">{{ __('Received') }}</th><th scope="col"><span class="sr-only">{{ __('Actions') }}</span></th></tr></thead><tbody>
        @forelse($tickets as $ticket)
        @php($tone = ['pending'=>'blue','replied'=>'blue','completed'=>'success','on_hold'=>'warning'][$ticket->status] ?? 'neutral')
        <tr x-data="adminFeedbackReview(@js(request()->integer('ticket') === $ticket->id || (int) old('review_ticket') === $ticket->id))">
            <td data-label="{{ __('Member') }}"><div class="am-person"><img class="am-avatar" src="{{ $ticket->user?->avatar_url ?? \App\Support\AvatarCatalog::url('amber') }}" alt="" loading="lazy"><span><strong>{{ $ticket->user?->name ?? $ticket->guest_name ?? __('Guest') }}</strong><small>{{ $ticket->user?->email ?? $ticket->guest_email ?? __('No email on record') }}</small></span></div></td>
            <td data-label="{{ __('Request') }}"><strong class="av-subject">{{ $ticket->subject }}</strong><small class="av-row-meta">#{{ $ticket->id }} · {{ __($categories[$ticket->category] ?? 'Other') }}</small></td>
            <td data-label="{{ __('Status') }}"><span class="am-badge am-badge--{{ $tone }}">{{ __($statuses[$ticket->status] ?? $ticket->status) }}</span></td>
            <td data-label="{{ __('Received') }}"><time datetime="{{ $ticket->created_at->toIso8601String() }}">{{ $ticket->created_at->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M Y') }}<small>{{ $ticket->created_at->timezone('Asia/Jakarta')->format('H:i') }} WIB</small></time></td>
            <td><button type="button" class="am-button av-review" @click="review()" aria-haspopup="dialog">{{ __('Review') }}<i class="ph ph-caret-right" aria-hidden="true"></i></button>@include('admin.partials.feedback-dialog')</td>
        </tr>
        @empty<tr><td colspan="5"><div class="am-empty"><i class="ph ph-chats" aria-hidden="true"></i><h3>{{ $filtered ? __('No matching tickets found') : __('No support tickets received yet') }}</h3><p>{{ $filtered ? __('Try another search or clear your filters.') : __('Member inquiries and feedback submissions will appear here.') }}</p></div></td></tr>@endforelse
        </tbody></table></div>
        <footer class="am-directory-footer"><span>{{ __('Showing :value1 to :value2 of :value3 tickets', ['value1'=>$tickets->firstItem() ?? 0,'value2'=>$tickets->lastItem() ?? 0,'value3'=>number_format($tickets->total())]) }}</span><div class="am-pagination">{{ $tickets->links() }}</div></footer>
    </section>
</div>
</x-admin-layout>
