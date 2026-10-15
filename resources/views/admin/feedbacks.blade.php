<x-admin-layout>
    <div class="admin-operations admin-feedbacks-workspace">

        {{-- ==========================================================================
             PAGE HEADER (Harmonized with User Management & Summary)
             ========================================================================== --}}
        <section class="af-welcome">
            <div>
                <h1>User Feedbacks</h1>
                <p>Review questions, resolve member issues, and reply to support inquiries in real time.</p>
            </div>
            <div class="af-header-actions">
                <span class="af-badge-total">
                    <i class="ph ph-ticket" aria-hidden="true"></i>
                    <strong>{{ number_format($totalFeedbacks) }}</strong> Total Tickets
                </span>
            </div>
        </section>

        {{-- ==========================================================================
             ALERT NOTICES
             ========================================================================== --}}
        @if(session('success_message') || session('success'))
            <div class="af-notice af-notice--success" role="status">
                <i class="ph ph-check-circle" aria-hidden="true"></i>
                <span>{{ session('success_message') ?? session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="af-notice af-notice--error" role="alert">
                <i class="ph ph-warning-circle" aria-hidden="true"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        {{-- ==========================================================================
             OVERVIEW METRIC STRIP (Interactive Status Filtering)
             ========================================================================== --}}
        <section class="af-metric-strip" aria-label="Support tickets status summary">
            {{-- Pending --}}
            <a href="{{ route('admin.feedbacks.index', array_merge(request()->except('status', 'page'), ['status' => 'pending'])) }}" 
               class="af-metric-card af-metric-card--pending {{ request('status') === 'pending' ? 'is-active' : '' }}"
               title="Filter by Pending tickets">
                <div class="af-metric-head">
                    <span class="af-metric-label">
                        <i class="ph ph-clock-countdown" aria-hidden="true"></i>
                        Pending Review
                    </span>
                    <i class="ph ph-arrow-up-right" aria-hidden="true"></i>
                </div>
                <strong>{{ number_format($pendingFeedbacks) }}</strong>
                <small>Awaiting administrative response</small>
            </a>

            {{-- Replied --}}
            <a href="{{ route('admin.feedbacks.index', array_merge(request()->except('status', 'page'), ['status' => 'replied'])) }}" 
               class="af-metric-card af-metric-card--replied {{ request('status') === 'replied' ? 'is-active' : '' }}"
               title="Filter by Replied tickets">
                <div class="af-metric-head">
                    <span class="af-metric-label">
                        <i class="ph ph-paper-plane-tilt" aria-hidden="true"></i>
                        Replied
                    </span>
                    <i class="ph ph-arrow-up-right" aria-hidden="true"></i>
                </div>
                <strong>{{ number_format($repliedFeedbacks) }}</strong>
                <small>Response sent to member</small>
            </a>

            {{-- Completed --}}
            <a href="{{ route('admin.feedbacks.index', array_merge(request()->except('status', 'page'), ['status' => 'completed'])) }}" 
               class="af-metric-card af-metric-card--completed {{ request('status') === 'completed' ? 'is-active' : '' }}"
               title="Filter by Completed tickets">
                <div class="af-metric-head">
                    <span class="af-metric-label">
                        <i class="ph ph-check-circle" aria-hidden="true"></i>
                        Completed
                    </span>
                    <i class="ph ph-arrow-up-right" aria-hidden="true"></i>
                </div>
                <strong>{{ number_format($completedFeedbacks) }}</strong>
                <small>Issue successfully resolved</small>
            </a>

            {{-- On Hold --}}
            <a href="{{ route('admin.feedbacks.index', array_merge(request()->except('status', 'page'), ['status' => 'on_hold'])) }}" 
               class="af-metric-card af-metric-card--on_hold {{ request('status') === 'on_hold' ? 'is-active' : '' }}"
               title="Filter by On hold tickets">
                <div class="af-metric-head">
                    <span class="af-metric-label">
                        <i class="ph ph-pause-circle" aria-hidden="true"></i>
                        On Hold
                    </span>
                    <i class="ph ph-arrow-up-right" aria-hidden="true"></i>
                </div>
                <strong>{{ number_format($onHoldFeedbacks) }}</strong>
                <small>Requires further follow-up</small>
            </a>
        </section>

        {{-- ==========================================================================
             DIRECTORY & MANAGEMENT PANEL
             ========================================================================== --}}
        <section class="af-panel" aria-labelledby="tickets-directory-heading">
            <div class="af-panel-heading">
                <div>
                    <h2 id="tickets-directory-heading">
                        Support Tickets <span>{{ number_format($tickets->total()) }}</span>
                    </h2>
                    <p>All incoming inquiries, issues, and feature suggestions from users</p>
                </div>
                @if(request()->hasAny(['search', 'status', 'category']))
                    <a href="{{ route('admin.feedbacks.index') }}" class="af-btn af-btn--quiet af-btn--sm">
                        <i class="ph ph-arrow-counter-clockwise" aria-hidden="true"></i>
                        Clear All Filters
                    </a>
                @endif
            </div>

            {{-- Filters Bar --}}
            <form action="{{ route('admin.feedbacks.index') }}" method="GET" class="af-filters">
                <label class="af-search">
                    <span class="sr-only">Search tickets</span>
                    <i class="ph ph-magnifying-glass" aria-hidden="true"></i>
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Search by subject, email, or message keyword…" maxlength="100">
                </label>

                <label>
                    <span class="sr-only">Status</span>
                    <select name="status">
                        <option value="">All Statuses</option>
                        @foreach(['pending' => 'Pending', 'replied' => 'Replied', 'completed' => 'Completed', 'on_hold' => 'On Hold'] as $key => $label)
                            <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>

                <label>
                    <span class="sr-only">Category</span>
                    <select name="category">
                        <option value="">All Categories</option>
                        @foreach([
                            'technical_issue' => 'Technical Issue',
                            'payment_billing' => 'Payment & Billing',
                            'feature_request' => 'Feature Request',
                            'general_feedback' => 'General Feedback'
                        ] as $key => $label)
                            <option value="{{ $key }}" @selected(request('category') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>

                <button type="submit" class="af-btn af-btn--primary">
                    <i class="ph ph-funnel" aria-hidden="true"></i>
                    Filter
                </button>

                @if(request()->hasAny(['search', 'status', 'category']))
                    <a href="{{ route('admin.feedbacks.index') }}" class="af-btn af-btn--quiet">
                        <i class="ph ph-x" aria-hidden="true"></i>
                        Reset
                    </a>
                @endif
            </form>

            {{-- Tickets List --}}
            <div class="af-tickets-list">
                @forelse($tickets as $ticket)
                    <article class="af-ticket-item" x-data="{ expanded: @js(request()->integer('ticket') === $ticket->id) }">
                        {{-- Row Summary --}}
                        <div class="af-ticket-row" @click="expanded = !expanded">
                            {{-- Member Info --}}
                            <div class="af-member-col">
                                <img src="{{ $ticket->user?->avatar_url ?? \App\Support\AvatarCatalog::url('amber') }}" 
                                     alt="" 
                                     class="af-avatar" 
                                     loading="lazy">
                                <div class="af-member-meta">
                                    <strong class="af-member-name">{{ $ticket->user?->name ?? $ticket->guest_name ?? 'Guest User' }}</strong>
                                    <span class="af-member-email">{{ $ticket->user?->email ?? $ticket->guest_email }}</span>
                                    @if($ticket->user)
                                        <span class="af-member-tag">
                                            <i class="ph-fill ph-check-circle" aria-hidden="true"></i>
                                            Member
                                        </span>
                                    @else
                                        <span class="af-member-tag af-member-tag--guest">
                                            <i class="ph ph-user" aria-hidden="true"></i>
                                            Guest
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Subject & Tags --}}
                            <div class="af-content-col">
                                <span class="af-subject" title="{{ $ticket->subject }}">{{ $ticket->subject }}</span>
                                <div class="af-pills">
                                    {{-- Category Pill --}}
                                    <span class="af-pill af-pill--category">
                                        @switch($ticket->category)
                                            @case('technical_issue')
                                                <i class="ph ph-wrench" aria-hidden="true"></i>
                                                @break
                                            @case('payment_billing')
                                                <i class="ph ph-credit-card" aria-hidden="true"></i>
                                                @break
                                            @case('feature_request')
                                                <i class="ph ph-lightbulb" aria-hidden="true"></i>
                                                @break
                                            @default
                                                <i class="ph ph-chat-circle-dots" aria-hidden="true"></i>
                                        @endswitch
                                        {{ $ticket->category_label }}
                                    </span>

                                    {{-- Status Pill --}}
                                    <span class="af-pill af-pill--status-{{ $ticket->status }}">
                                        <span class="af-status-dot"></span>
                                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                    </span>
                                </div>
                            </div>

                            {{-- Received Time --}}
                            <div class="af-time-col">
                                <time datetime="{{ $ticket->created_at->toIso8601String() }}">
                                    <strong>{{ $ticket->created_at->format('d M Y') }}</strong>
                                    <small>{{ $ticket->created_at->format('H:i') }} WIB · {{ $ticket->created_at->diffForHumans() }}</small>
                                </time>
                            </div>

                            {{-- Action Button --}}
                            <div class="af-action-col">
                                <button type="button" 
                                        class="af-btn af-btn--quiet af-btn--sm" 
                                        :aria-expanded="expanded.toString()"
                                        aria-controls="ticket-drawer-{{ $ticket->id }}"
                                        @click.stop="expanded = !expanded">
                                    <span x-text="expanded ? 'Close' : 'Review & Reply'"></span>
                                    <i class="ph ph-caret-down transition-transform duration-200" :class="{ 'rotate-180': expanded }" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Expanded Detail Drawer --}}
                        <div id="ticket-drawer-{{ $ticket->id }}" 
                             x-show="expanded" 
                             x-collapse 
                             x-cloak 
                             class="af-detail-drawer">
                            
                            {{-- Conversation Thread --}}
                            <div class="af-thread">
                                {{-- Member Inquiry --}}
                                <div class="af-bubble">
                                    <div class="af-bubble-head">
                                        <div class="af-bubble-author">
                                            <i class="ph ph-user-circle" aria-hidden="true"></i>
                                            <span>Inquiry from {{ $ticket->user?->name ?? $ticket->guest_name ?? 'Guest' }}</span>
                                        </div>
                                        <span class="af-bubble-time">{{ $ticket->created_at->format('d M Y, H:i') }} WIB</span>
                                    </div>
                                    <div class="af-bubble-body">{{ $ticket->message }}</div>
                                </div>

                                {{-- Latest Admin Reply (if exists) --}}
                                @if($ticket->admin_reply)
                                    <div class="af-bubble af-bubble--reply">
                                        <div class="af-bubble-head">
                                            <div class="af-bubble-author">
                                                <i class="ph ph-shield-check" aria-hidden="true"></i>
                                                <span>Administrator Reply</span>
                                            </div>
                                            <span class="af-bubble-time">{{ $ticket->replied_at?->format('d M Y, H:i') }} WIB</span>
                                        </div>
                                        <div class="af-bubble-body">{{ $ticket->admin_reply }}</div>
                                    </div>
                                @endif
                            </div>

                            {{-- Reply & Actions Panel --}}
                            <div class="af-actions-panel">
                                <h3 class="af-actions-title">
                                    <i class="ph ph-paper-plane-tilt" aria-hidden="true"></i>
                                    {{ $ticket->admin_reply ? 'Update Reply & Status' : 'Write Response' }}
                                </h3>

                                <form action="{{ route('admin.feedbacks.reply', $ticket->id) }}" method="POST" class="af-reply-form">
                                    @csrf
                                    <label>
                                        <span>Response message</span>
                                        <textarea name="admin_reply" 
                                                  rows="4" 
                                                  minlength="5" 
                                                  maxlength="5000" 
                                                  required 
                                                  placeholder="Write a clear, helpful response to the member…">{{ old('admin_reply', $ticket->admin_reply) }}</textarea>
                                    </label>

                                    <label>
                                        <span>Update ticket status</span>
                                        <select name="status">
                                            @foreach(['replied' => 'Replied', 'completed' => 'Completed', 'on_hold' => 'On hold', 'pending' => 'Pending'] as $key => $label)
                                                <option value="{{ $key }}" @selected($key === $ticket->status)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </label>

                                    <button type="submit" class="af-btn af-btn--primary">
                                        <i class="ph ph-paper-plane-tilt" aria-hidden="true"></i>
                                        Send Reply & Update Status
                                    </button>
                                </form>

                                {{-- Quick Status & Delete --}}
                                <div class="af-quick-actions">
                                    <form method="POST" action="{{ route('admin.feedbacks.status', $ticket->id) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="text-xs py-1.5 px-2.5 rounded-lg border border-gray-200 dark:bg-[#1e1f20] dark:border-white/10 dark:text-[#f0f4f9]">
                                            @foreach(['pending' => 'Pending', 'replied' => 'Replied', 'completed' => 'Completed', 'on_hold' => 'On hold'] as $status => $label)
                                                <option value="{{ $status }}" @selected($ticket->status === $status)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="af-btn af-btn--quiet af-btn--sm">Status only</button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.feedbacks.destroy', $ticket->id) }}" onsubmit="return confirm('Delete this support ticket permanently? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="af-btn af-btn--danger af-btn--sm">
                                            <i class="ph ph-trash" aria-hidden="true"></i>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="af-empty">
                        <div class="af-empty-icon">
                            <i class="ph ph-tray" aria-hidden="true"></i>
                        </div>
                        <h3>{{ request()->hasAny(['search', 'status', 'category']) ? 'No matching tickets found' : 'No support tickets received yet' }}</h3>
                        <p>{{ request()->hasAny(['search', 'status', 'category']) ? 'Try clearing or modifying your search filters to find tickets.' : 'Member inquiries and feedback submissions will appear here.' }}</p>
                    </div>
                @endforelse
            </div>

            {{-- Panel Footer --}}
            <div class="af-panel-footer">
                <span>Showing {{ $tickets->firstItem() ?? 0 }} to {{ $tickets->lastItem() ?? 0 }} of {{ number_format($tickets->total()) }} tickets</span>
                <div>
                    {{ $tickets->links() }}
                </div>
            </div>
        </section>

    </div>
</x-admin-layout>
