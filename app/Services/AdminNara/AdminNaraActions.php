<?php

namespace App\Services\AdminNara;

use App\Models\AdminNaraAction;
use App\Models\JobSource;
use App\Models\OperationEvent;
use App\Models\OperationIncident;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\Vacancy;
use App\Services\Operations\ServiceMonitor;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * Changes Nara Admin can propose. Proposing validates and stores the change; nothing happens until the admin who asked
 * presses "Approve" (AdminNaraController), and only within AdminNaraAction::TTL_MINUTES. Each type is small, reversible
 * or low-risk, and none moves money: refunds, cancellations and plan prices stay in the Payments page.
 */
class AdminNaraActions
{
    private const SERVICES = ['platform', 'login', 'gemini', 'email', 'payment', 'storage'];

    /** @return array<string, array<string, mixed>> validation rules per action type */
    public static function rules(): array
    {
        return [
            'reply_ticket' => ['ticket_id' => 'required|integer', 'reply' => 'required|string|min:5|max:2000', 'status' => ['nullable', Rule::in(['replied', 'completed', 'on_hold'])]],
            'set_ticket_status' => ['ticket_id' => 'required|integer', 'status' => ['required', Rule::in(['pending', 'replied', 'completed', 'on_hold'])]],
            'create_incident' => ['title' => 'required|string|min:5|max:160', 'description' => 'required|string|min:10|max:2000', 'service' => ['required', Rule::in(self::SERVICES)], 'severity' => ['required', Rule::in(['critical', 'high', 'medium', 'low'])]],
            'update_incident' => ['incident_id' => 'required|integer', 'status' => ['required', Rule::in(['open', 'investigating', 'monitoring', 'resolved'])], 'note' => 'required|string|min:5|max:1000'],
            'set_source_active' => ['source_id' => 'required|integer', 'active' => 'required|boolean'],
            'flag_vacancy' => ['vacancy_id' => 'required|integer', 'flag' => ['required', Rule::in(['suspected', 'none'])]],
            'close_vacancy' => ['vacancy_id' => 'required|integer', 'reason' => 'nullable|string|max:120'],
            'grant_credits' => ['user_id' => 'required|integer', 'kind' => ['required', Rule::in(['ai', 'cover_letter', 'photo'])], 'amount' => 'required|integer|min:1|max:20'],
            'sync_payments' => [],
            'run_service_check' => ['service' => ['required', Rule::in(array_keys(ServiceMonitor::SERVICES))]],
        ];
    }

    /** Human-readable lines for the approval card. @return array<int, string> */
    public static function describe(string $type, array $p): array
    {
        $lines = [];
        $label = fn (string $k) => ucfirst(str_replace('_', ' ', $k));
        foreach ($p as $key => $value) {
            $lines[] = $label($key).': '.(is_bool($value) ? ($value ? 'yes' : 'no') : \Illuminate\Support\Str::limit((string) $value, 400));
        }
        $extra = match ($type) {
            'reply_ticket', 'set_ticket_status' => ($t = SupportTicket::find($p['ticket_id'] ?? 0)) ? 'Ticket: '.\Illuminate\Support\Str::limit($t->subject, 80) : null,
            'set_source_active' => ($s = JobSource::find($p['source_id'] ?? 0)) ? 'Source: '.$s->name : null,
            'flag_vacancy', 'close_vacancy' => ($v = Vacancy::find($p['vacancy_id'] ?? 0)) ? 'Job: '.\Illuminate\Support\Str::limit($v->title, 80) : null,
            'grant_credits' => ($u = User::find($p['user_id'] ?? 0)) ? 'User: '.$u->name : null,
            'update_incident' => ($i = OperationIncident::find($p['incident_id'] ?? 0)) ? 'Incident: '.\Illuminate\Support\Str::limit($i->title, 80) : null,
            default => null,
        };

        return array_values(array_filter([$extra, ...$lines]));
    }

    /** Validate and store a proposal. @return array<string, mixed> what the model needs to tell the admin */
    public function propose(User $admin, string $type, array $params, string $summary): array
    {
        $rules = self::rules();
        if (! isset($rules[$type])) {
            return ['error' => 'unknown action type; use one of: '.implode(', ', array_keys($rules))];
        }
        $params = array_intersect_key($params, $rules[$type]);
        $validator = Validator::make($params, $rules[$type]);
        if ($validator->fails()) {
            return ['error' => 'invalid parameters: '.implode(' ', $validator->errors()->all())];
        }
        if ($problem = $this->targetProblem($type, $validator->validated())) {
            return ['error' => $problem];
        }
        $action = AdminNaraAction::create(['user_id' => $admin->id, 'type' => $type, 'params' => $validator->validated(), 'summary' => mb_substr(trim($summary) ?: $type, 0, 300), 'status' => 'pending']);

        return ['proposed' => true, 'action_id' => $action->id, 'instruction' => 'An approval card is shown to the admin under your reply. Say what you proposed and that nothing has changed until they press Approve. Do NOT say it is done.'];
    }

    /** The thing the action points at must exist now (and be allowed). */
    private function targetProblem(string $type, array $p): ?string
    {
        return match ($type) {
            'reply_ticket', 'set_ticket_status' => SupportTicket::whereKey($p['ticket_id'])->exists() ? null : 'no such ticket',
            'update_incident' => OperationIncident::whereKey($p['incident_id'])->exists() ? null : 'no such incident',
            'set_source_active' => JobSource::whereKey($p['source_id'])->exists() ? null : 'no such job source',
            'flag_vacancy', 'close_vacancy' => Vacancy::whereKey($p['vacancy_id'])->exists() ? null : 'no such vacancy',
            'grant_credits' => User::where('role', '!=', 'admin')->whereKey($p['user_id'])->exists() ? null : 'no such member (admins cannot be granted credits here)',
            default => null,
        };
    }

    /** Run an approved action. Never throws. @return array{ok: bool, message: string} */
    public function execute(AdminNaraAction $action, User $admin): array
    {
        $p = (array) $action->params;
        try {
            $message = DB::transaction(fn () => match ($action->type) {
                'reply_ticket' => $this->replyTicket($p),
                'set_ticket_status' => $this->ticketStatus($p),
                'create_incident' => $this->createIncident($p, $admin),
                'update_incident' => $this->updateIncident($p, $admin),
                'set_source_active' => $this->sourceActive($p),
                'flag_vacancy' => $this->flagVacancy($p),
                'close_vacancy' => $this->closeVacancy($p),
                'grant_credits' => $this->grantCredits($p, $admin),
                'sync_payments' => $this->syncPayments(),
                'run_service_check' => $this->serviceCheck($p, $admin),
            });
            Log::info('Nara Admin action executed', ['admin' => $admin->id, 'action' => $action->id, 'type' => $action->type, 'params' => $p]);

            return ['ok' => true, 'message' => $message];
        } catch (\Throwable $e) {
            Log::warning('Nara Admin action failed: '.$e->getMessage(), ['action' => $action->id]);

            return ['ok' => false, 'message' => 'Failed: '.\Illuminate\Support\Str::limit($e->getMessage(), 160)];
        }
    }

    private function replyTicket(array $p): string
    {
        SupportTicket::findOrFail($p['ticket_id'])->update(['admin_reply' => $p['reply'], 'status' => $p['status'] ?? 'replied', 'replied_at' => now()]);

        return 'Reply saved on ticket #'.$p['ticket_id'].'.';
    }

    private function ticketStatus(array $p): string
    {
        SupportTicket::findOrFail($p['ticket_id'])->update(['status' => $p['status']]);

        return 'Ticket #'.$p['ticket_id'].' is now '.$p['status'].'.';
    }

    private function createIncident(array $p, User $admin): string
    {
        $incident = OperationIncident::create(['title' => $p['title'], 'description' => $p['description'], 'severity' => $p['severity'], 'service' => $p['service'], 'created_by' => $admin->id, 'status' => 'open']);
        OperationEvent::create(['action' => 'incident_created', 'note' => 'Created through Nara Admin.', 'incident_id' => $incident->id, 'actor_id' => $admin->id, 'changes' => ['status' => 'open', 'severity' => $p['severity']]]);

        return 'Incident #'.$incident->id.' created.';
    }

    private function updateIncident(array $p, User $admin): string
    {
        $incident = OperationIncident::lockForUpdate()->findOrFail($p['incident_id']);
        $before = $incident->only('status', 'severity', 'assigned_to');
        $incident->update(['status' => $p['status'], 'resolved_at' => $p['status'] === 'resolved' ? ($incident->resolved_at ?? now()) : null]);
        OperationEvent::create(['action' => 'incident_updated', 'note' => $p['note'], 'incident_id' => $incident->id, 'user_id' => $incident->affected_user_id, 'actor_id' => $admin->id,
            'changes' => ['before' => $before, 'after' => $incident->only('status', 'severity', 'assigned_to')]]);

        return 'Incident #'.$incident->id.' is now '.$p['status'].'.';
    }

    private function sourceActive(array $p): string
    {
        $source = JobSource::findOrFail($p['source_id']);
        $source->update(['is_active' => (bool) $p['active']]);

        return $source->name.($p['active'] ? ' is active again.' : ' is paused.');
    }

    private function flagVacancy(array $p): string
    {
        Vacancy::findOrFail($p['vacancy_id'])->update(['scam_flag' => $p['flag']]);

        return $p['flag'] === 'suspected' ? 'Job #'.$p['vacancy_id'].' is hidden from Explore as suspected scam.' : 'Job #'.$p['vacancy_id'].' is no longer flagged.';
    }

    private function closeVacancy(array $p): string
    {
        Vacancy::findOrFail($p['vacancy_id'])->update(['status' => 'closed', 'closed_at' => now(), 'closed_reason' => $p['reason'] ?? 'admin']);

        return 'Job #'.$p['vacancy_id'].' is closed.';
    }

    private function grantCredits(array $p, User $admin): string
    {
        $column = ['ai' => 'ai_credits', 'cover_letter' => 'cl_credits', 'photo' => 'photo_credits'][$p['kind']];
        $user = User::where('role', '!=', 'admin')->lockForUpdate()->findOrFail($p['user_id']);
        $user->increment($column, (int) $p['amount']);
        OperationEvent::create(['action' => 'credits_granted', 'note' => "Granted {$p['amount']} {$p['kind']} credits through Nara Admin.", 'user_id' => $user->id, 'actor_id' => $admin->id, 'changes' => [$column => (int) $p['amount']]]);

        return "{$p['amount']} {$p['kind']} credits added to {$user->name}.";
    }

    private function syncPayments(): string
    {
        Artisan::call('payments:sync-pending');

        return 'Pending payments were synced with the payment provider.';
    }

    private function serviceCheck(array $p, User $admin): string
    {
        $check = app(ServiceMonitor::class)->check($p['service'], $admin->id);

        return ServiceMonitor::SERVICES[$p['service']].': '.$check->status.' — '.\Illuminate\Support\Str::limit((string) $check->summary, 140);
    }
}
