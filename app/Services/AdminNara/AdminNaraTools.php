<?php

namespace App\Services\AdminNara;

use App\Models\AdminNaraAudit;
use App\Models\User;

/** The tools the model may call for Nara Admin: declarations, the audited execution, and a last redaction pass. */
class AdminNaraTools
{
    /** Keys that never reach the model, whatever a query returned. */
    private const SECRET = '/(^|_)(password|passwd|token|secret|otp|api_?key|webhook|signature|phone|va_number|card_number)(_|$)/i';

    /** Tool calls per turn that proposed actions (for the cards). @var array<int, int> */
    public array $proposed = [];

    public function __construct(private AdminNaraData $data, private AdminNaraActions $actions, private AdminNaraDigest $digest) {}

    /** @return array<int, array<string, mixed>> */
    public static function declarations(): array
    {
        $s = fn (string $d, ?array $enum = null) => array_filter(['type' => 'STRING', 'description' => $d, 'enum' => $enum]);
        $i = fn (string $d) => ['type' => 'INTEGER', 'description' => $d];
        $tool = fn (string $name, string $description, ?array $params = null) => ['name' => $name, 'description' => $description] + ($params ? ['parameters' => ['type' => 'OBJECT', 'properties' => $params]] : []);
        $actionTypes = array_keys(AdminNaraActions::rules());

        return [[
            'functionDeclarations' => [
                $tool('daily_digest', 'What needs attention today across the whole platform (payments, tickets, incidents, job sources, crawl, AI cost). Use for "ringkas hari ini", "apa yang perlu diperhatikan".'),
                $tool('system_status', 'Live platform state: service checks, open incidents, job-source health, last crawl, queue and failed jobs.'),
                $tool('incidents', 'List operation incidents.', ['status' => $s('unresolved (default), all, open, investigating, monitoring or resolved.'), 'severity' => $s('critical, high, medium or low.'), 'limit' => $i('Max rows, up to 15.')]),
                $tool('source_health', 'Job sources with problems (failing or paused) and what they did in the last 24h. only_problems=false lists all.', ['only_problems' => ['type' => 'BOOLEAN', 'description' => 'Default true.'], 'limit' => $i('Max rows, up to 15.')]),
                $tool('crawl_overview', 'Totals of crawl runs in the last N hours: found, new jobs, failures, AI cost, top errors.', ['hours' => $i('Default 24, up to 336.')]),
                $tool('vacancy_stats', 'Job catalogue health: counts by status, new jobs, missing salary %, quality, suspected scams, closing soon, top sources.'),
                $tool('search_vacancies', 'Find jobs in the catalogue by title or company, status or scam flag.', ['query' => $s('Part of a title or company.'), 'status' => $s('active, closed, suspect…'), 'scam_only' => ['type' => 'BOOLEAN', 'description' => 'Only suspected scams.'], 'limit' => $i('Max rows, up to 15.')]),
                $tool('find_user', 'Find members by id, email or name (up to 5).', ['query' => $s('id, email or name.')]),
                $tool('user_summary', 'One member in detail: plan, credits, applications, payments, tickets, restrictions.', ['user_id' => $i('Member id.')]),
                $tool('signup_funnel', 'Signups in the last N days and how many verified, added an application or became premium; per-day series.', ['days' => $i('Default 7, up to 90.')]),
                $tool('payments_overview', 'Payments in the last N days by status, revenue, channels, and payments waiting over an hour.', ['days' => $i('Default 7, up to 90.')]),
                $tool('find_payment', 'Look up payments by order id (part), user id or status.', ['order_id' => $s('Order id or part of it.'), 'user_id' => $i('Member id.'), 'status' => $s('SUCCESS, WAITING, FAILED, EXPIRED, CANCELED…'), 'limit' => $i('Max rows, up to 15.')]),
                $tool('open_tickets', 'Support tickets (oldest first). Default: pending ones.', ['status' => $s('pending (default), replied, completed, on_hold or all.'), 'older_than_days' => $i('Only tickets older than this.'), 'limit' => $i('Max rows, up to 15.')]),
                $tool('get_ticket', 'The full text of one ticket.', ['ticket_id' => $i('Ticket id.')]),
                $tool('satisfaction', 'Survey averages (overall, navigation, speed, CV builder, AI…) and recent comments.', ['days' => $i('Default 30.')]),
                $tool('ai_usage', 'AI usage and cost per purpose and model, Nara ratings, AI photo/studio outcomes.', ['days' => $i('Default 7.')]),
                $tool('email_blasts', 'Recent email blasts with delivery results.', ['limit' => $i('Default 5.')]),
                $tool('explain_metric', 'Compare a metric with the previous period, with daily numbers, to explain a rise or drop. Then check a related tool before naming a cause.', [
                    'metric' => $s('What to compare.', ['signups', 'revenue', 'payments_success', 'new_jobs', 'crawl_failures', 'ai_cost_usd', 'tickets', 'applications_added']), 'days' => $i('Period length, default 7.'),
                ]),
                $tool('propose_action', 'Propose ONE change for the admin to approve. Nothing runs until they press Approve. Fill only the fields of the chosen type. Types and fields: reply_ticket{ticket_id,reply,status?}, set_ticket_status{ticket_id,status}, create_incident{title,description,service,severity}, update_incident{incident_id,status,note}, set_source_active{source_id,active}, flag_vacancy{vacancy_id,flag}, close_vacancy{vacancy_id,reason?}, grant_credits{user_id,kind(ai|cover_letter|photo),amount≤20}, sync_payments{}, run_service_check{service}. Look the target up first; never guess an id.', [
                    'type' => $s('Action type.', $actionTypes), 'summary' => $s('One sentence the admin reads on the approval card, in the language of the conversation.'),
                    // Parameters are flat (one schema for every type); only those of the chosen type are used.
                    'ticket_id' => $i('reply_ticket, set_ticket_status.'), 'reply' => $s('reply_ticket: the reply text.'), 'status' => $s('Ticket or incident status.'),
                    'title' => $s('create_incident.'), 'description' => $s('create_incident.'), 'service' => $s('create_incident: platform, login, ai, email, payment, storage. run_service_check: ai, email, payment, storage.'),
                    'severity' => $s('create_incident.', ['critical', 'high', 'medium', 'low']), 'incident_id' => $i('update_incident.'), 'note' => $s('update_incident: what changed.'),
                    'source_id' => $i('set_source_active.'), 'active' => ['type' => 'BOOLEAN', 'description' => 'set_source_active.'], 'vacancy_id' => $i('flag_vacancy, close_vacancy.'),
                    'flag' => $s('flag_vacancy.', ['suspected', 'none']), 'reason' => $s('close_vacancy.'), 'user_id' => $i('grant_credits.'), 'kind' => $s('grant_credits.', ['ai', 'cover_letter', 'photo']), 'amount' => $i('grant_credits, 1 to 20.'),
                ]),
            ],
        ]];
    }

    public static function status(string $tool): string
    {
        return match ($tool) {
            'propose_action' => __('Preparing a change for your approval…'),
            'explain_metric' => __('Comparing with the previous period…'),
            'daily_digest' => __('Checking what needs attention…'),
            default => __('Looking at the data…'),
        };
    }

    /** Run one tool. Every call is audited; failures come back as {error} so the chat never breaks. @return array<string, mixed> */
    public function execute(string $name, array $args, User $admin): array
    {
        $ok = true;
        try {
            $result = match ($name) {
                'daily_digest' => $this->digest->data(),
                'system_status' => $this->data->systemStatus(),
                'incidents' => $this->data->incidents($args),
                'source_health' => $this->data->sourceHealth($args),
                'crawl_overview' => $this->data->crawlOverview($args),
                'vacancy_stats' => $this->data->vacancyStats(),
                'search_vacancies' => $this->data->searchVacancies($args),
                'find_user' => $this->data->findUser($args),
                'user_summary' => $this->data->userSummary($args),
                'signup_funnel' => $this->data->signupFunnel($args),
                'payments_overview' => $this->data->paymentsOverview($args),
                'find_payment' => $this->data->findPayment($args),
                'open_tickets' => $this->data->openTickets($args),
                'get_ticket' => $this->data->getTicket($args),
                'satisfaction' => $this->data->satisfaction($args),
                'ai_usage' => $this->data->aiUsage($args),
                'email_blasts' => $this->data->emailBlasts($args),
                'explain_metric' => $this->data->explainMetric($args),
                'propose_action' => $this->propose($args, $admin),
                default => ['error' => 'unknown tool'],
            };
            $ok = ! isset($result['error']);
        } catch (\Throwable $e) {
            report($e);
            $ok = false;
            $result = ['error' => 'the tool failed; tell the admin and suggest the matching admin page instead'];
        }
        $this->audit($admin, $name, $args, $ok, $result);

        return $this->redact($result);
    }

    private function propose(array $args, User $admin): array
    {
        $result = $this->actions->propose($admin, (string) ($args['type'] ?? ''), array_diff_key($args, ['type' => 1, 'summary' => 1]), (string) ($args['summary'] ?? ''));
        if (isset($result['action_id'])) {
            $this->proposed[] = $result['action_id'];
        }

        return $result;
    }

    private function audit(User $admin, string $tool, array $args, bool $ok, array $result): void
    {
        try {
            $rows = collect($result)->first(fn ($v) => is_array($v) && array_is_list($v));
            AdminNaraAudit::create(['user_id' => $admin->id, 'tool' => $tool, 'args' => $this->redact($args) ?: null, 'ok' => $ok, 'rows' => is_array($rows) ? count($rows) : null]);
        } catch (\Throwable $e) {
            report($e); // an audit failure must not break the answer, but it must not be silent either
        }
    }

    /** Drop secret-looking keys at any depth and cap long strings. */
    public function redact(array $data): array
    {
        $clean = [];
        foreach ($data as $key => $value) {
            if (is_string($key) && preg_match(self::SECRET, $key)) {
                continue;
            }
            $clean[$key] = is_array($value) ? $this->redact($value) : (is_string($value) && mb_strlen($value) > 2000 ? mb_substr($value, 0, 2000).'…' : $value);
        }

        return $clean;
    }
}
