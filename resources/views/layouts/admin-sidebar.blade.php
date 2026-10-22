@php
    $navigationGroups = [
        __('Overview') => [
            ['admin.index', __('Dashboard'), 'squares-four', 'admin.index'],
            ['admin.users', __('User Management'), 'users', 'admin.users*'],
            ['admin.user-activities', __('Activity Log'), 'clock-counter-clockwise', 'admin.user-activities'],
            ['admin.analytics', __('Analytics'), 'chart-line-up', 'admin.analytics'],
        ],
        __('Operations') => [
            ['admin.operations-center', __('Operations Center'), 'pulse', 'admin.operations-center'],
            ['admin.payments', __('Payments & plans'), 'credit-card', 'admin.payments*'],
            ['admin.email-blast', __('Email Blast'), 'envelope-simple', 'admin.email-blast*'],
            ['admin.feedbacks.index', __('User Feedback'), 'chat-circle-text', 'admin.feedbacks*'],
            ['admin.survey.index', __('User Survey'), 'clipboard-text', 'admin.survey*'],
            ['admin.settings', __('Settings'), 'sliders-horizontal', 'admin.settings'],
        ],
    ];
@endphp
<nav class="ac-navigation" aria-label="{{ __('Admin navigation') }}">
    @foreach($navigationGroups as $group => $links)
        <div class="ac-nav-group">
            <p>{{ $group }}</p>
            @foreach($links as [$route, $label, $icon, $pattern])
                <a href="{{ route($route) }}" class="ac-nav-link" aria-label="{{ $label }}" title="{{ $label }}" @if(request()->routeIs($pattern)) aria-current="page" @endif><i class="ph-fill ph-{{ $icon }}" aria-hidden="true"></i><span>{{ $label }}</span></a>
            @endforeach
        </div>
    @endforeach
</nav>
