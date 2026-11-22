<?php

namespace App\JobEngine\Personalization;

use App\Models\JobBookmark;
use App\Models\NaraInterview;
use App\Models\NaraMemory;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Cache;

/**
 * The tools Nara can call (OpenAI function calling). Nara decides when to search the TraKerja 2.0 catalogue and with
 * which filters; this class does the work on the server, on the user's own data only, and records which vacancies
 * were offered so that only those can become job cards in the reply.
 *
 * Reading tools: search_jobs, get_job_details, compare_jobs.
 * Writing tools (only when the user asked): save_job, create_job_alert, remember_preference, forget_preference.
 */
class NaraTools
{
    public function __construct(private NaraJobFinder $finder) {}

    /** Tool declarations (converted for OpenAI by OpenAiChat::tools). @return array<int, array<string, mixed>> */
    public static function declarations(): array
    {
        $string = fn (string $description, ?array $enum = null) => array_filter(['type' => 'STRING', 'description' => $description, 'enum' => $enum]);
        $integer = fn (string $description) => ['type' => 'INTEGER', 'description' => $description];
        $id = $integer('Vacancy id taken from an earlier search_jobs / get_job_details result. Never invent one.');

        return [[
            'functionDeclarations' => [
                [
                    'name' => 'search_jobs',
                    'description' => 'Search the open jobs in the TraKerja catalogue. Call it whenever the user wants to find, browse or be recommended jobs, and for "more like these". Do NOT call it for questions about the user themself, their memory, applications or interviews. All filters are optional; with none, the search follows the user\'s profile.',
                    'parameters' => ['type' => 'OBJECT', 'properties' => [
                        'keywords' => ['type' => 'ARRAY', 'items' => ['type' => 'STRING'], 'description' => 'Job titles and related terms, any of which may match, in the language job titles use (many are English). Add synonyms, fix typos, translate: "programmer web" -> ["web developer", "frontend", "backend", "software engineer"]; "akuntan" -> ["accountant", "akuntan", "finance"].'],
                        'location' => $string('City, regency or province in Indonesia, e.g. "Bandung", "Jakarta Selatan", "Jawa Timur".'),
                        'work_mode' => $string('Where the work happens.', ['remote', 'onsite', 'hybrid']),
                        'employment_type' => $string('Contract type.', ['full_time', 'part_time', 'contract', 'internship', 'freelance']),
                        'level' => $string('Seniority. "fresh graduate" is entry.', ['intern', 'entry', 'mid', 'senior', 'lead', 'manager']),
                        'internship' => ['type' => 'BOOLEAN', 'description' => 'True for internships (magang).'],
                        'min_monthly_salary_idr' => $integer('Minimum monthly salary in rupiah, e.g. 8000000 for "8 juta". ONLY when the user asks to filter by salary in this very request (never from a remembered expectation or from earlier turns). Only jobs that state a salary can match.'),
                        'closing_within_days' => $integer('Only jobs whose application deadline falls within this many days.'),
                        'posted_within_days' => $integer('Only jobs posted within this many days.'),
                        'company' => $string('Company name, or part of it.'),
                        'only_new' => ['type' => 'BOOLEAN', 'description' => 'True when the user asks for more / other jobs: skips jobs already shown in this conversation.'],
                        'prefer_best_match' => ['type' => 'BOOLEAN', 'description' => 'True to rank by how well the jobs fit the user\'s CV (premium users).'],
                    ]],
                ],
                [
                    'name' => 'get_job_details',
                    'description' => 'Full details of one job (description, skills, salary, deadline, CV match with skills the user has and lacks). Use it to answer questions about a specific job.',
                    'parameters' => ['type' => 'OBJECT', 'properties' => ['job_id' => $id], 'required' => ['job_id']],
                ],
                [
                    'name' => 'compare_jobs',
                    'description' => 'Facts about 2 or 3 jobs side by side, so you can compare them for the user.',
                    'parameters' => ['type' => 'OBJECT', 'properties' => ['job_ids' => ['type' => 'ARRAY', 'items' => ['type' => 'INTEGER'], 'description' => '2 or 3 vacancy ids from earlier results.']], 'required' => ['job_ids']],
                ],
                [
                    'name' => 'save_job',
                    'description' => 'Save a job to the user\'s saved jobs. Only when the user asks to save/bookmark it. It does not record an application.',
                    'parameters' => ['type' => 'OBJECT', 'properties' => ['job_id' => $id], 'required' => ['job_id']],
                ],
                [
                    'name' => 'create_job_alert',
                    'description' => 'Turn the latest job search into a saved search with a daily email alert for new matching jobs. Only when the user asks for alerts/notifications about it.',
                    'parameters' => ['type' => 'OBJECT', 'properties' => ['name' => $string('Short name for the alert, e.g. "Frontend Jakarta".')]],
                ],
                [
                    'name' => 'remember_preference',
                    'description' => 'Remember a lasting preference of the user about their job search (target roles, places, work mode, salary expectation), so they do not have to repeat it. Store one short fact per call, in the language the user used. Never store sensitive data (religion, health, politics, ethnicity, relationships) or anything temporary.',
                    'parameters' => ['type' => 'OBJECT', 'properties' => [
                        'fact' => $string('One short sentence, e.g. "Looking for remote frontend roles" or "Expects around Rp10 juta per month".'),
                        'category' => $string('Kind of preference.', NaraMemory::CATEGORIES),
                    ], 'required' => ['fact']],
                ],
                [
                    'name' => 'start_interview',
                    'description' => 'Start a structured interview practice (default 5 questions: behavioural, technical, motivation). Call it when the user wants to practise interviewing and there is no active_interview. Replaces any unfinished session. Use job_id when the practice is for a job from the catalogue or from the tracker (application job_posting).',
                    'parameters' => ['type' => 'OBJECT', 'properties' => [
                        'role' => $string('The role being practised for, e.g. "Frontend Engineer".'),
                        'company' => $string('Company, if known.'),
                        'job_id' => $integer('Vacancy id when the practice is for a specific catalogue job.'),
                        'question_count' => $integer('3 to 8 questions; 5 by default.'),
                    ], 'required' => ['role']],
                ],
                [
                    'name' => 'record_answer',
                    'description' => 'Record the score of the user\'s answer to the current interview question. Call it after evaluating each answer, before asking the next question.',
                    'parameters' => ['type' => 'OBJECT', 'properties' => [
                        'question_number' => $integer('Number of the question that was answered (1-based).'),
                        'question' => $string('The question that was asked, shortened.'),
                        'score' => ['type' => 'NUMBER', 'description' => 'Score 0 to 10, based only on the evidence in the answer.'],
                        'strength' => $string('The strongest point of the answer, one short sentence.'),
                        'improvement' => $string('The single most useful improvement, one short sentence.'),
                        'answer_excerpt' => $string('Up to 200 characters of the user\'s answer worth quoting later.'),
                    ], 'required' => ['question_number', 'score']],
                ],
                [
                    'name' => 'finish_interview',
                    'description' => 'End the interview practice (after the last answer, or when the user stops) and get the numbers for the final summary.',
                    'parameters' => ['type' => 'OBJECT', 'properties' => ['reason' => $string('Why it ends: "completed" or "stopped".')]],
                ],
                [
                    'name' => 'forget_preference',
                    'description' => 'Forget something remembered earlier, when the user asks you to forget or correct it. Use the memory id from remembered_preferences.',
                    'parameters' => ['type' => 'OBJECT', 'properties' => ['memory_id' => $integer('Id from remembered_preferences.')], 'required' => ['memory_id']],
                ],
            ],
        ]];
    }

    /** Short status shown while a tool runs. */
    public static function status(string $tool): string
    {
        return match ($tool) {
            'search_jobs' => __('Searching the TraKerja job catalog…'),
            'get_job_details' => __('Reading the job details…'),
            'compare_jobs' => __('Comparing the jobs…'),
            'save_job' => __('Saving the job…'),
            'create_job_alert' => __('Setting up the alert…'),
            'forget_preference' => __('Updating your preferences…'),
            'start_interview' => __('Setting up your interview practice…'),
            'record_answer' => __('Scoring your answer…'),
            'finish_interview' => __('Putting together your summary…'),
            default => __('Noting your preference…'),
        };
    }

    /**
     * Run one tool call. Always returns an array (errors included), so a bad call from the model never breaks the chat.
     *
     * @param  array<string, mixed>  $args
     * @return array<string, mixed>
     */
    public function execute(string $name, array $args, User $user, NaraTurn $turn): array
    {
        return rescue(fn () => match ($name) {
            'search_jobs' => $this->searchJobs($args, $user, $turn),
            'get_job_details' => $this->jobDetails($args, $user, $turn),
            'compare_jobs' => $this->compareJobs($args, $user, $turn),
            'save_job' => $this->saveJob($args, $user, $turn),
            'create_job_alert' => $this->createAlert($args, $user, $turn),
            'remember_preference' => $this->remember($args, $user),
            'forget_preference' => $this->forget($args, $user),
            'start_interview' => $this->startInterview($args, $user, $turn),
            'record_answer' => $this->recordAnswer($args, $user, $turn),
            'finish_interview' => $this->finishInterview($args, $user, $turn),
            default => ['error' => "unknown tool {$name}"],
        }, ['error' => 'the tool failed; answer without it and say so briefly'], true);
    }

    private function searchJobs(array $args, User $user, NaraTurn $turn): array
    {
        $previous = Cache::get(\App\Services\KawanKerjaService::searchKey($user));
        $exclude = ! empty($args['only_new']) ? array_map('intval', (array) ($previous['shown'] ?? [])) : [];
        $result = $this->finder->search($user, $args, $exclude);
        $ids = array_column($result['jobs'], 'id');
        $turn->offer($ids);
        $turn->search = ['criteria' => $result['criteria'], 'label' => $result['label'], 'shown' => array_values(array_unique([...$exclude, ...$ids]))];
        Cache::put(\App\Services\KawanKerjaService::searchKey($user), $turn->search, now()->addMinutes(30));

        return [
            'total_matching' => $result['total'],
            'shown' => count($ids),
            'based_on_profile' => $result['personal'],
            'label' => $result['label'],
            'jobs' => $result['jobs'],
            'notes' => $result['notes'],
            'salary_filter_dropped' => $result['salary_filter_dropped'] ?? false,
            'instruction' => $ids ? 'Write each recommended job as its [job:ID] tag, as told in the system prompt.' : 'Nothing matched. Say so, and suggest broader keywords or another city.',
        ];
    }

    private function jobDetails(array $args, User $user, NaraTurn $turn): array
    {
        $id = (int) ($args['job_id'] ?? 0);
        $details = $this->finder->details($user, [$id]);
        if (! $details) {
            return ['error' => 'job not found'];
        }
        $turn->offer([$id]);

        return ['job' => $details[0]];
    }

    private function compareJobs(array $args, User $user, NaraTurn $turn): array
    {
        $ids = array_slice(array_values(array_unique(array_filter(array_map('intval', (array) ($args['job_ids'] ?? []))))), 0, 3);
        $details = count($ids) >= 2 ? $this->finder->details($user, $ids) : [];
        if (count($details) < 2) {
            return ['error' => 'give 2 or 3 vacancy ids that exist'];
        }
        $turn->offer(array_column($details, 'id'));
        $turn->compared = array_column($details, 'id');

        return ['jobs' => $details, 'instruction' => 'The system draws the comparison table: write the tag [compare:'.implode(',', $turn->compared).'] on its own line, then 2 to 4 sentences with a clear recommendation for this user (fit, skills gap, salary, level, location, deadline). Do not write a table yourself.'];
    }

    private function saveJob(array $args, User $user, NaraTurn $turn): array
    {
        $vacancy = Vacancy::visible()->find((int) ($args['job_id'] ?? 0));
        if (! $vacancy) {
            return ['error' => 'this job is not open any more'];
        }
        JobBookmark::firstOrCreate(['user_id' => $user->id, 'vacancy_id' => $vacancy->id]);
        $turn->offer([$vacancy->id]);

        return ['saved' => true, 'title' => $vacancy->title, 'saved_in' => '"Saved jobs" in Explore (not the application tracker)', 'note' => 'No application was recorded.'];
    }

    private function createAlert(array $args, User $user, NaraTurn $turn): array
    {
        $latest = $turn->search ?? Cache::get(\App\Services\KawanKerjaService::searchKey($user));
        if (! $latest) {
            return ['error' => 'there is no search to turn into an alert yet; search first'];
        }
        $saved = $this->finder->saveAlert($user, $latest['criteria'], (string) ($args['name'] ?? $latest['label']));
        if (! $saved) {
            return ['error' => 'the search is too broad for an alert (add a role or a place) or the user already has 20 saved searches'];
        }
        $turn->alertSaved = true;

        return ['alert_created' => true, 'name' => $saved->name, 'frequency' => 'daily email'];
    }

    private function remember(array $args, User $user): array
    {
        $fact = trim((string) ($args['fact'] ?? ''));
        if (mb_strlen($fact) < 4) {
            return ['error' => 'nothing to remember'];
        }
        $memory = NaraMemory::remember($user, $fact, (string) ($args['category'] ?? 'other'));

        return ['remembered' => $memory->fact, 'memory_id' => $memory->id];
    }

    private function forget(array $args, User $user): array
    {
        $deleted = NaraMemory::where('user_id', $user->id)->whereKey((int) ($args['memory_id'] ?? 0))->delete();

        return $deleted ? ['forgotten' => true] : ['error' => 'no such memory'];
    }

    private function startInterview(array $args, User $user, NaraTurn $turn): array
    {
        $role = trim((string) ($args['role'] ?? ''));
        if ($role === '') {
            return ['error' => 'a role is needed to start an interview practice'];
        }
        NaraInterview::where('user_id', $user->id)->where('status', 'active')->update(['status' => 'done']);
        $total = max(NaraInterview::MIN_QUESTIONS, min(NaraInterview::MAX_QUESTIONS, (int) ($args['question_count'] ?? 5)));
        $job = ! empty($args['job_id']) ? ($this->finder->details($user, [(int) $args['job_id']])[0] ?? null) : null;
        $session = NaraInterview::create([
            'user_id' => $user->id, 'vacancy_id' => $job['id'] ?? null, 'role' => mb_substr($role, 0, 120),
            'company' => filled($args['company'] ?? null) ? mb_substr((string) $args['company'], 0, 120) : ($job['company'] ?? null),
            'total' => $total, 'plan' => NaraInterview::planFor($total), 'answers' => [], 'status' => 'active',
        ]);
        $turn->interview = $session;

        return [
            'started' => true, 'total_questions' => $total,
            'question_plan' => collect($session->plan)->map(fn ($type, $i) => ['n' => $i + 1, 'type' => $type])->all(),
            'job' => $job,
            'instruction' => 'Ask question 1 now (a blockquote), built from the job data if there is any and naming the skill it tests. Then wait for the answer.',
        ];
    }

    private function recordAnswer(array $args, User $user, NaraTurn $turn): array
    {
        $session = NaraInterview::activeFor($user);
        if (! $session) {
            return ['error' => 'there is no active interview practice; call start_interview first'];
        }
        $n = (int) ($args['question_number'] ?? 0);
        if ($n < 1 || $n > $session->total) {
            return ['error' => "question_number must be between 1 and {$session->total}"];
        }
        $session->recordAnswer($n, [
            'question' => mb_substr((string) ($args['question'] ?? ''), 0, 240),
            'score' => max(0, min(10, round((float) ($args['score'] ?? 0), 1))),
            'strength' => mb_substr((string) ($args['strength'] ?? ''), 0, 240),
            'improvement' => mb_substr((string) ($args['improvement'] ?? ''), 0, 240),
            'excerpt' => mb_substr((string) ($args['answer_excerpt'] ?? ''), 0, 200),
        ]);
        $session->refresh();
        $turn->interview = $session;
        $remaining = $session->total - $session->answered();

        return $session->progress() + [
            'recorded' => true, 'remaining' => $remaining,
            'instruction' => $remaining > 0 ? 'Now ask question '.($session->answered() + 1).' (a blockquote), type: '.($session->plan[$session->answered()] ?? 'technical').'.' : 'That was the last question: call finish_interview and write the summary.',
        ];
    }

    private function finishInterview(array $args, User $user, NaraTurn $turn): array
    {
        $session = NaraInterview::activeFor($user);
        if (! $session) {
            return ['error' => 'there is no active interview practice'];
        }
        $session->update(['status' => 'done']);
        $turn->interview = $session->refresh();
        $answers = collect($session->answers)->values();
        if ($answers->isEmpty()) {
            return ['finished' => true, 'answered' => 0, 'note' => 'No answers were recorded; say so and offer to start again.'];
        }
        $byScore = $answers->sortByDesc('score')->values();

        return [
            'finished' => true, 'role' => $session->role, 'company' => $session->company,
            'answered' => $answers->count(), 'total' => $session->total, 'average_score' => $session->average(),
            'answers' => $answers->map(fn ($a) => array_intersect_key($a, array_flip(['n', 'type', 'question', 'score', 'strength', 'improvement'])))->all(),
            'best_answer' => array_intersect_key($byScore->first(), array_flip(['n', 'question', 'score', 'excerpt', 'strength'])),
            'weakest_answer' => array_intersect_key($byScore->last(), array_flip(['n', 'question', 'score', 'improvement'])),
            'instruction' => 'Write the summary: average score, two strengths, ONE main improvement (from the weakest answer), the best answer quoted, and one next practice step.',
        ];
    }
}
