<?php

namespace App\Livewire\Admin;

use App\Models\OperationEvent;
use App\Models\OperationIncident;
use App\Models\OperationRestriction;
use App\Models\OperationServiceCheck;
use App\Models\User;
use App\Services\Operations\ServiceMonitor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class OperationsCenter extends Component
{
    use WithoutUrlPagination, WithPagination;

    public string $tab = 'incidents';

    public string $search = '';

    public string $filter = 'all';

    public string $severityFilter = 'all';

    #[Locked]
    public ?string $drawer = null;

    #[Locked]
    public ?int $incidentId = null;

    #[Locked]
    public ?int $memberId = null;

    #[Locked]
    public ?string $checkService = null;

    #[Locked]
    public ?int $restrictionId = null;

    #[Locked]
    public string $accountAction = 'restrict';

    public string $title = '';

    public string $description = '';

    public string $severity = 'medium';

    public string $service = 'platform';

    public string $status = 'open';

    public ?int $assignedTo = null;

    public ?int $affectedUser = null;

    public string $memberLookup = '';

    public string $note = '';

    public string $scope = 'account';

    public string $expiresAt = '';

    public ?int $linkedIncident = null;

    public bool $confirmed = false;

    public const SEVERITIES = ['critical' => 'Critical', 'high' => 'High', 'medium' => 'Medium', 'low' => 'Low'];

    public const STATUSES = ['open' => 'Open', 'investigating' => 'Investigating', 'monitoring' => 'Monitoring', 'resolved' => 'Resolved'];

    public const SERVICES = ['platform' => 'Platform', 'login' => 'Login', 'gemini' => 'Gemini', 'email' => 'Email', 'payment' => 'Pakasir', 'storage' => 'Storage'];

    public function boot(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilter(): void
    {
        $this->resetPage();
    }

    public function updatedSeverityFilter(): void
    {
        $this->resetPage();
    }

    public function selectTab(string $tab): void
    {
        abort_unless(in_array($tab, ['incidents', 'accounts', 'services']), 422);
        $this->tab = $tab;
        $this->reset('search', 'filter', 'severityFilter');
        $this->resetPage();
        $this->closeDrawer();
    }

    public function showSummary(string $key): void
    {
        abort_unless(in_array($key, ['degraded', 'open', 'critical', 'restricted']), 422);
        $this->selectTab(match ($key) {
            'degraded' => 'services', 'restricted' => 'accounts', default => 'incidents'
        });
        if ($key === 'restricted') {
            $this->filter = 'restricted';
        }
        if (in_array($key, ['open', 'critical'])) {
            $this->filter = 'unresolved';
        }
        if ($key === 'critical') {
            $this->severityFilter = 'critical';
        }
    }

    public function closeDrawer(): void
    {
        $this->reset('drawer', 'incidentId', 'memberId', 'restrictionId', 'checkService', 'confirmed', 'note', 'memberLookup');
        $this->resetValidation();
    }

    public function newIncident(?string $service = null): void
    {
        $this->closeDrawer();
        $this->reset('title', 'description', 'severity', 'service', 'assignedTo', 'affectedUser', 'status');
        if ($service) {
            abort_unless(isset(ServiceMonitor::SERVICES[$service]), 422);
            $check = OperationServiceCheck::where('service', $service)->latest('id')->firstOrFail();
            $this->service = $service;
            $this->title = ServiceMonitor::SERVICES[$service].' · '.__('Service issue');
            $this->description = __($check->summary)."\n".__('Checked at').': '.$check->created_at->format('d M Y H:i').' WIB';
        }
        $this->drawer = 'new';
    }

    private function incidentRules(): array
    {
        return [
            'severity' => ['required', Rule::in(array_keys(self::SEVERITIES))],
            'assignedTo' => ['nullable', Rule::exists('users', 'id')->where('role', 'admin')],
        ];
    }

    public function createIncident(): void
    {
        abort_unless($this->drawer === 'new', 422);
        $this->validate($this->incidentRules() + [
            'title' => 'required|string|min:5|max:160', 'description' => 'required|string|min:10|max:5000',
            'service' => ['required', Rule::in(array_keys(self::SERVICES))],
            'affectedUser' => ['nullable', Rule::exists('users', 'id')->where('role', '!=', 'admin')],
        ]);
        $incident = DB::transaction(function () {
            $incident = OperationIncident::create([
                'title' => trim($this->title), 'description' => trim($this->description), 'severity' => $this->severity,
                'service' => $this->service, 'assigned_to' => $this->assignedTo, 'affected_user_id' => $this->affectedUser,
                'created_by' => auth()->id(), 'status' => 'open',
            ]);
            $this->event('incident_created', __('Incident created.'), $incident->id, $this->affectedUser, ['status' => 'open', 'severity' => $this->severity]);

            return $incident;
        });
        $this->openIncident($incident->id);
        session()->flash('operations_notice', __('Incident created.'));
    }

    public function openIncident(int $id): void
    {
        $incident = OperationIncident::findOrFail($id);
        $this->closeDrawer();
        $this->incidentId = $incident->id;
        $this->severity = $incident->severity;
        $this->status = $incident->status;
        $this->assignedTo = $incident->assigned_to;
        $this->drawer = 'incident';
    }

    public function updateIncident(): void
    {
        abort_unless($this->drawer === 'incident' && $this->incidentId, 422);
        $this->validate($this->incidentRules() + ['status' => ['required', Rule::in(array_keys(self::STATUSES))], 'note' => 'required|string|min:5|max:3000']);
        DB::transaction(function () {
            $incident = OperationIncident::lockForUpdate()->findOrFail($this->incidentId);
            $before = $incident->only('status', 'severity', 'assigned_to');
            $incident->update(['status' => $this->status, 'severity' => $this->severity, 'assigned_to' => $this->assignedTo,
                'resolved_at' => $this->status === 'resolved' ? ($incident->resolved_at ?? now()) : null]);
            $this->event('incident_updated', trim($this->note), $incident->id, $incident->affected_user_id,
                ['before' => $before, 'after' => $incident->only('status', 'severity', 'assigned_to')]);
        });
        $this->reset('note');
        session()->flash('operations_notice', __('Incident updated.'));
    }

    public function openAccount(int $id): void
    {
        $originIncident = $this->drawer === 'incident' ? $this->incidentId : null;
        $member = User::findOrFail($id);
        abort_if($member->isAdmin(), 422);
        $this->closeDrawer();
        $this->memberId = $id;
        $this->reset('accountAction', 'scope', 'expiresAt', 'linkedIncident');
        $this->linkedIncident = $originIncident;
        $this->drawer = 'account';
    }

    public function prepareAccountAction(string $action, ?int $restriction = null): void
    {
        abort_unless($this->drawer === 'account' && in_array($action, ['restrict', 'release', 'revoke_api']), 422);
        $record = $action === 'release' ? OperationRestriction::where('user_id', $this->memberId)->active()->find($restriction) : null;
        abort_if($action === 'release' && ! $record, 404);
        $this->restrictionId = $record?->id;
        $this->linkedIncident ??= $record?->incident_id;
        $this->accountAction = $action;
        $this->reset('note', 'confirmed');
        $this->resetValidation();
    }

    public function applyAccountAction(): void
    {
        abort_unless($this->drawer === 'account' && $this->memberId, 422);
        $rules = ['note' => 'required|string|min:5|max:2000', 'confirmed' => 'accepted',
            'linkedIncident' => 'nullable|exists:operation_incidents,id'];
        if ($this->accountAction === 'restrict') {
            $rules += ['scope' => ['required', Rule::in(['account', 'api'])], 'expiresAt' => 'nullable|date|after:now'];
        }
        $this->validate($rules);
        DB::transaction(function () {
            $member = User::lockForUpdate()->findOrFail($this->memberId);
            abort_if($member->isAdmin() || $member->id === auth()->id(), 403);
            $changes = [];
            if ($this->accountAction === 'restrict') {
                if (OperationRestriction::where('user_id', $member->id)->active()->where('scope', $this->scope)->exists()) {
                    $this->addError('scope', __('This restriction is already active.'));

                    return;
                }
                $restriction = OperationRestriction::create(['user_id' => $member->id, 'scope' => $this->scope, 'reason' => trim($this->note),
                    'created_by' => auth()->id(), 'expires_at' => $this->expiresAt ?: null, 'incident_id' => $this->linkedIncident]);
                $changes = ['restriction_id' => $restriction->id, 'scope' => $this->scope, 'expires_at' => $restriction->expires_at?->toIso8601String(), 'tokens_revoked' => $member->tokens()->delete()];
            } elseif ($this->accountAction === 'release') {
                $restriction = OperationRestriction::where('user_id', $member->id)->active()->lockForUpdate()->findOrFail($this->restrictionId);
                $restriction->update(['released_at' => now(), 'released_by' => auth()->id(), 'release_reason' => trim($this->note)]);
                $changes = ['restriction_id' => $restriction->id, 'scope' => $restriction->scope];
            } elseif ($this->accountAction === 'revoke_api') {
                $changes = ['tokens_revoked' => $member->tokens()->delete()];
            } else {
                abort(422);
            }
            $this->event('account_'.$this->accountAction, trim($this->note), $this->linkedIncident, $member->id, $changes);
        });
        if ($this->getErrorBag()->isNotEmpty()) {
            return;
        }
        $this->reset('note', 'confirmed');
        $this->accountAction = 'restrict';
        $this->restrictionId = null;
        session()->flash('operations_notice', __('Account action recorded.'));
    }

    private function event(string $action, string $note, ?int $incident, ?int $member, array $changes = []): void
    {
        OperationEvent::create(['action' => $action, 'note' => $note, 'incident_id' => $incident, 'user_id' => $member,
            'actor_id' => auth()->id(), 'changes' => $changes ?: null]);
    }

    public function inspectService(string $service): void
    {
        abort_unless(isset(ServiceMonitor::SERVICES[$service]), 422);
        $this->closeDrawer();
        $this->checkService = $service;
        $this->drawer = 'service';
    }

    public function checkServiceNow(string $service): void
    {
        abort_unless(isset(ServiceMonitor::SERVICES[$service]), 422);
        $key = 'operations-check:'.$service;
        if (RateLimiter::tooManyAttempts($key, 1) || OperationServiceCheck::where('service', $service)->where('created_at', '>', now()->subSeconds(30))->exists()) {
            $this->addError('monitor', __('Wait 30 seconds before checking this service again.'));

            return;
        }
        RateLimiter::hit($key, 30);
        app(ServiceMonitor::class)->check($service, auth()->id());
        session()->flash('operations_notice', __('Service check completed.'));
    }

    public function render()
    {
        $search = mb_substr(trim($this->search), 0, 150);
        $incidents = OperationIncident::with('assignee')->when($search !== '', fn ($q) => $q->where(fn ($q) => $q->where('title', 'like', '%'.$search.'%')->orWhere('description', 'like', '%'.$search.'%')))
            ->when($this->filter === 'unresolved', fn ($q) => $q->where('status', '!=', 'resolved'))
            ->when(isset(self::STATUSES[$this->filter]), fn ($q) => $q->where('status', $this->filter))
            ->when(isset(self::SEVERITIES[$this->severityFilter]), fn ($q) => $q->where('severity', $this->severityFilter))
            ->orderByRaw("CASE WHEN status = 'resolved' THEN 1 ELSE 0 END")
            ->orderByRaw("CASE severity WHEN 'critical' THEN 0 WHEN 'high' THEN 1 WHEN 'medium' THEN 2 ELSE 3 END")->latest('id');
        $members = User::where('role', '!=', 'admin')->when($search !== '', fn ($q) => $q->where(fn ($q) => $q->where('name', 'like', '%'.$search.'%')->orWhere('email', 'like', '%'.$search.'%')))
            ->when($this->filter === 'restricted', fn ($q) => $q->whereIn('id', OperationRestriction::active()->select('user_id')))->latest('id');
        $checks = collect(ServiceMonitor::SERVICES)->map(fn ($name, $key) => ['name' => $name, 'key' => $key, 'latest' => OperationServiceCheck::where('service', $key)->latest('id')->first()]);
        $member = $this->drawer === 'account' ? User::find($this->memberId) : null;
        $lookup = mb_substr(trim($this->memberLookup), 0, 100);

        return view('livewire.admin.operations-center', [
            'incidents' => $this->tab === 'incidents' ? $incidents->paginate(10) : null,
            'members' => $this->tab === 'accounts' ? $members->paginate(10) : null,
            'restrictedIds' => OperationRestriction::active()->distinct()->pluck('user_id'),
            'checks' => $checks,
            'stats' => [
                'degraded' => $checks->filter(fn ($row) => $row['latest']?->status === 'degraded')->count(),
                'open' => OperationIncident::where('status', '!=', 'resolved')->count(),
                'critical' => OperationIncident::where('status', '!=', 'resolved')->where('severity', 'critical')->count(),
                'restricted' => OperationRestriction::active()->distinct()->count('user_id'),
            ],
            'incident' => $this->drawer === 'incident' ? OperationIncident::with('affectedUser')->find($this->incidentId) : null,
            'member' => $member,
            'restrictions' => $member ? OperationRestriction::where('user_id', $member->id)->latest('id')->limit(30)->get() : collect(),
            'events' => $this->drawer === 'incident' ? OperationEvent::with('actor')->where('incident_id', $this->incidentId)->latest('id')->limit(50)->get()
                : ($member ? OperationEvent::with('actor')->where('user_id', $member->id)->latest('id')->limit(50)->get() : collect()),
            'serviceHistory' => $this->drawer === 'service' ? OperationServiceCheck::with('checker')->where('service', $this->checkService)->latest('id')->limit(20)->get() : collect(),
            'admins' => User::where('role', 'admin')->orderBy('name')->get(['id', 'name']),
            'memberMatches' => $this->drawer === 'new' && mb_strlen($lookup) >= 2 ? User::where('role', '!=', 'admin')->where(fn ($q) => $q->where('name', 'like', '%'.$lookup.'%')->orWhere('email', 'like', '%'.$lookup.'%'))->limit(8)->get(['id', 'name', 'email']) : collect(),
        ]);
    }
}
